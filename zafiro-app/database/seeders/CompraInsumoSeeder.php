<?php

namespace Database\Seeders;

use App\Models\CompraInsumo;
use App\Models\DetalleCompra;
use App\Models\Empleado;
use App\Models\Insumo;
use App\Models\LoteInsumo;
use App\Models\Proveedor;
use App\Models\Tienda;

use Illuminate\Support\Facades\DB; //
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompraInsumoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        mt_srand(3030);   // misma semilla = mismos datos cada vez que se siembra

        $tiendas = Tienda::all();
        $proveedores = Proveedor::all();
        $insumos = Insumo::with('categoria')->get();

        // Cantidad que se compra según la categoría
        $cantidades = [
            'bases' => [5, 15],
            'flores' => [50, 120],
            'follajes' => [30, 80],
            'complementos' => [5, 15],
            'materiales' => [20, 50],
        ];

        $idRolAdmin = DB::table('roles')->where('slug', 'admin')->value('id');

        for ($i = 0; $i < 20; $i++) {
            $tienda = $tiendas[$i % $tiendas->count()];
            $proveedor = $proveedores[$i % $proveedores->count()];

            // 16 recibidas, 3 en borrador y 1 anulada
            $estado = $i < 16 ? 'recibida' : ($i < 19 ? 'borrador' : 'anulada');

            // Compras repartidas en los últimos 60 días, de la más antigua a la más reciente
            $fecha = now()->subDays(60 - ($i * 3))->setTime(9, 0);

            $compra = CompraInsumo::create([
                'tienda_id' => $tienda->id,
                'proveedor_id' => $proveedor->id,
                'empleado_id' => $this->empleadoQueCompra($tienda->id, $idRolAdmin),
                'numero_documento' => sprintf('000-001-01-%08d', 1001 + $i),
                'fecha_compra' => $fecha->toDateString(),
                'subtotal' => 0,
                'isv' => 0,
                'total' => 0,
                'estado' => $estado,
                'observaciones' => $estado === 'anulada' ? 'Anulada: el proveedor no entregó el pedido.' : null,
            ]);

            // 2 o 3 insumos distintos por compra (no se repite insumo en la misma compra)
            $subtotal = 0;
            $lineas = $insumos->random(mt_rand(2, 3));

            foreach ($lineas as $insumo) {
                $categoria = $insumo->categoria->slug;
                [$minimo, $maximo] = $cantidades[$categoria] ?? [10, 30];
                $cantidad = mt_rand($minimo, $maximo);

                // Más caro que el lote inicial (40 % del precio): así se nota el PEPS
                $costo = round($insumo->precio_componente * 0.40 * (mt_rand(110, 125) / 100), 2);

                $detalle = DetalleCompra::create([
                    'compra_insumo_id' => $compra->id,
                    'insumo_id' => $insumo->id,
                    'cantidad' => $cantidad,
                    'costo_unitario' => $costo,
                ]);

                $subtotal += $cantidad * $costo;

                // Solo las compras recibidas crean lotes, uno por cada línea
                if ($estado === 'recibida') {
                    $vencimiento = in_array($categoria, ['flores', 'follajes'], true)
                        ? $fecha->copy()->addDays(mt_rand(10, 15))->toDateString()
                        : null;

                    LoteInsumo::create([
                        'tienda_id' => $tienda->id,
                        'insumo_id' => $insumo->id,
                        'detalle_compra_id' => $detalle->id,
                        'codigo_lote' => sprintf('L-%s-%s-C%02d', $tienda->codigo, $insumo->sku, $i + 1),
                        'cantidad_inicial' => $cantidad,
                        'cantidad_disponible' => $cantidad,
                        'costo_unitario' => $costo,
                        'fecha_ingreso' => $fecha,
                        'fecha_vencimiento' => $vencimiento,
                    ]);
                }
            }

            $subtotal = round($subtotal, 2);
            $isv = round($subtotal * 0.15, 2);

            $compra->update([
                'subtotal' => $subtotal,
                'isv' => $isv,
                'total' => $subtotal + $isv,
            ]);
        }
    }

    // El admin de la tienda; si no hay, cualquier empleado de la tienda. Sino, cualquier empleado
    private function empleadoQueCompra(int $tiendaId, ?int $idRolAdmin): int
    {
        return Empleado::where('tienda_id', $tiendaId)->where('rol_id', $idRolAdmin)->value('id')
            ?? Empleado::where('tienda_id', $tiendaId)->value('id')
            ?? Empleado::value('id');
    }
}
