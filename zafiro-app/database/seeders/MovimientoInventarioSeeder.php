<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\LoteInsumo;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MovimientoInventarioSeeder extends Seeder
{
    // Lotes en memoria: [id => ['tienda_id', 'insumo_id', 'fecha_ingreso', 'disponible']]
    private array $lotes = [];

    // Movimientos que se insertan al final
    private array $movimientos = [];

    // Recetas ya consultadas, para no repetir la misma consulta
    private array $recetas = [];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Se borra solo el kardex, para poder volver a correr este seeder sin duplicar
        DB::table('movimientos_inventario')->delete();

        $this->cargarLotes();
        $this->registrarEntradas();

        // Mermas y consumos en orden de fecha, como ocurrieron
        $eventos = array_merge($this->mermas(), $this->consumos());
        usort($eventos, fn ($a, $b) => $a['fecha'] <=> $b['fecha']);

        foreach ($eventos as $evento) {
            if ($evento['tipo'] === 'merma') {
                $this->aplicarMerma($evento);
            } else {
                $this->aplicarConsumo($evento);
            }
        }

        $this->guardar();
    }

    // Todos los lotes empiezan con su cantidad inicial; las salidas se restan aquí
    private function cargarLotes(): void
    {
        foreach (LoteInsumo::orderBy('fecha_ingreso')->orderBy('id')->get() as $lote) {
            $this->lotes[$lote->id] = [
                'tienda_id' => (int) $lote->tienda_id,
                'insumo_id' => (int) $lote->insumo_id,
                'fecha_ingreso' => $lote->fecha_ingreso->copy(),
                'disponible' => (float) $lote->cantidad_inicial,
                'detalle_compra_id' => $lote->detalle_compra_id,
                'cantidad_inicial' => (float) $lote->cantidad_inicial,
                'en_base' => (float) $lote->cantidad_disponible,   // lo que hay guardado ahora
            ];
        }
    }

     // 1. Una entrada por cada lote
    private function registrarEntradas(): void
    {
        // Empleado que registró cada compra
        $empleadoCompra = DB::table('detalle_compras')
            ->join('compras_insumo', 'compras_insumo.id', '=', 'detalle_compras.compra_insumo_id')
            ->pluck('compras_insumo.empleado_id', 'detalle_compras.id');

        foreach ($this->lotes as $id => $lote) {
            $esCompra = $lote['detalle_compra_id'] !== null;

            $this->agregarMovimiento([
                'lote_insumo_id' => $id,
                'empleado_id' => $esCompra ? ($empleadoCompra[$lote['detalle_compra_id']] ?? null) : null,
                'tipo' => $esCompra ? 'entrada_compra' : 'ajuste_positivo',
                'cantidad' => $lote['cantidad_inicial'],
                'pedido_item_id' => null,
                'merma_id' => null,
                'motivo' => $esCompra ? 'Ingreso por compra' : 'Inventario inicial',
                'created_at' => $lote['fecha_ingreso'],
            ]);
        }
    }

    // Mermas registradas por el I3
    private function mermas(): array
    {
        $eventos = [];

        foreach (DB::table('mermas')->get() as $merma) {
            $eventos[] = [
                'tipo' => 'merma',
                'fecha' => Carbon::parse($merma->fecha),
                'merma' => $merma,
            ];
        }

        return $eventos;
    }

    // Arreglos que ya empezaron a armarse (en_armado o listo), del I4
    private function consumos(): array
    {
        $eventos = [];

        $items = DB::table('pedido_items')
            ->join('pedidos', 'pedidos.id', '=', 'pedido_items.pedido_id')
            ->whereIn('pedido_items.estado_preparacion', ['en_armado', 'listo'])
            ->select('pedido_items.*', 'pedidos.tienda_id', 'pedidos.created_at as fecha_pedido')
            ->get();

        foreach ($items as $item) {
            $eventos[] = [
                'tipo' => 'consumo',
                'fecha' => Carbon::parse($item->fecha_inicio_armado ?? $item->fecha_pedido),
                'item' => $item,
            ];
        }

        return $eventos;
    }

    // 2. La merma sale del lote que indicó el I3
    private function aplicarMerma(array $evento): void
    {
        $merma = $evento['merma'];
        $cantidad = (float) $merma->cantidad;

        if (! isset($this->lotes[$merma->lote_insumo_id])) {
            throw new \RuntimeException("La merma {$merma->id} apunta a un lote que no existe.");
        }

        if ($this->lotes[$merma->lote_insumo_id]['disponible'] + 0.001 < $cantidad) {
            throw new \RuntimeException("El lote {$merma->lote_insumo_id} no alcanza para la merma {$merma->id}. Suba la cantidad del lote inicial.");
        }

        $this->lotes[$merma->lote_insumo_id]['disponible'] = round($this->lotes[$merma->lote_insumo_id]['disponible'] - $cantidad, 2);

        $this->agregarMovimiento([
            'lote_insumo_id' => $merma->lote_insumo_id,
            'empleado_id' => $merma->empleado_id,
            'tipo' => 'merma',
            'cantidad' => $cantidad,
            'pedido_item_id' => null,
            'merma_id' => $merma->id,
            'motivo' => 'Merma: ' . $merma->motivo,
            'created_at' => $evento['fecha'],
        ]);
    }

    // 3. El arreglo consume su receta de los lotes de la tienda, del más antiguo al más nuevo (PEPS)
    private function aplicarConsumo(array $evento): void
    {
        $item = $evento['item'];

        foreach ($this->receta($item) as $insumoId => $cantidadPorArreglo) {
            $this->consumirPeps(
                (int) $item->tienda_id,
                (int) $insumoId,
                round($cantidadPorArreglo * $item->cantidad, 2),
                $item,
                $evento['fecha']
            );
        }
    }

    // Insumos y cantidades de un arreglo: la receta de su variante o los componentes de su diseño
    private function receta(object $item): array
    {
        $clave = $item->variante_arreglo_id ? 'v' . $item->variante_arreglo_id : 'd' . $item->diseno_personalizado_id;

        return $this->recetas[$clave] ??= $this->consultarReceta($item);
    }

    private function consultarReceta(object $item): array
    {
        if ($item->variante_arreglo_id) {
            return DB::table('receta_items')
                ->where('variante_arreglo_id', $item->variante_arreglo_id)
                ->pluck('cantidad', 'insumo_id')
                ->map(fn ($cantidad) => (float) $cantidad)
                ->all();
        }

        if ($item->diseno_personalizado_id) {
            $insumos = DB::table('diseno_componentes')
                ->where('diseno_personalizado_id', $item->diseno_personalizado_id)
                ->pluck('cantidad', 'insumo_id')
                ->map(fn ($cantidad) => (float) $cantidad)
                ->all();

            // La base del diseño también se consume (una por arreglo)
            $base = DB::table('disenos_personalizados')->where('id', $item->diseno_personalizado_id)->value('base_insumo_id');
            if ($base) {
                $insumos[$base] = ($insumos[$base] ?? 0) + 1;
            }

            return $insumos;
        }

        return [];
    }

    private function consumirPeps(int $tiendaId, int $insumoId, float $cantidad, object $item, Carbon $fecha): void
    {
        $pendiente = $cantidad;

        // $this->lotes ya viene ordenado por fecha_ingreso: el primero es el más antiguo
        foreach ($this->lotes as $id => $lote) {
            if ($pendiente <= 0.001) {
                break;
            }

            // Solo lotes de esa tienda y ese insumo, con existencia y que ya habían ingresado
            if ($lote['tienda_id'] !== $tiendaId || $lote['insumo_id'] !== $insumoId
                || $lote['disponible'] <= 0 || $lote['fecha_ingreso']->gt($fecha)) {
                continue;
            }

            $tomado = min($lote['disponible'], $pendiente);
            $this->lotes[$id]['disponible'] = round($lote['disponible'] - $tomado, 2);
            $pendiente = round($pendiente - $tomado, 2);

            $this->agregarMovimiento([
                'lote_insumo_id' => $id,
                'empleado_id' => $item->florista_id,
                'tipo' => 'consumo_armado',
                'cantidad' => $tomado,
                'pedido_item_id' => $item->id,
                'merma_id' => null,
                'motivo' => 'Consumo de armado',
                'created_at' => $fecha,
            ]);
        }

        if ($pendiente > 0.001) {
            throw new \RuntimeException("No alcanzan los lotes del insumo {$insumoId} en la tienda {$tiendaId} para el arreglo {$item->id}. Suba la cantidad del lote inicial.");
        }
    }

    private function agregarMovimiento(array $movimiento): void
    {
        $this->movimientos[] = $movimiento;
    }

    // Se guardan los lotes que cambiaron y se insertan los movimientos en bloques
    private function guardar(): void
    {
        DB::transaction(function () {
            // Solo se actualizan los lotes cuyo disponible cambió con respecto a lo guardado
            foreach ($this->lotes as $id => $lote) {
                if (abs($lote['disponible'] - $lote['en_base']) > 0.001) {
                    LoteInsumo::whereKey($id)->update(['cantidad_disponible' => $lote['disponible']]);
                }
            }

            foreach (array_chunk($this->movimientos, 200) as $bloque) {
                DB::table('movimientos_inventario')->insert($bloque);
            }
        });
    }
}
