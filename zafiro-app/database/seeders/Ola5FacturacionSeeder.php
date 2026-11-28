<?php

namespace Database\Seeders;

use App\Models\AutorizacionCai;
use App\Models\Factura;
use App\Models\FacturaDetalle;
use App\Support\MontoEnLetras;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Ola5FacturacionSeeder extends Seeder
{
    public function run(): void
    {
        $pedidos = DB::table('pedidos')
            ->join('estados_pedido', 'estados_pedido.id', '=', 'pedidos.estado_pedido_id')
            ->join('pagos', function ($union) {
                $union->on('pagos.pedido_id', '=', 'pedidos.id')->where('pagos.estado', 'aprobado');
            })
            ->join('clientes', 'clientes.id', '=', 'pedidos.cliente_id')
            ->join('users', 'users.id', '=', 'clientes.user_id')
            ->whereIn('estados_pedido.slug', ['pagado', 'en_ruta', 'entregado'])
            ->whereNotExists(function ($consulta) {
                $consulta->select(DB::raw(1))->from('facturas')->whereColumn('facturas.pedido_id', 'pedidos.id');
            })
            ->orderBy('pagos.fecha_pago')
            ->select('pedidos.*', 'pagos.fecha_pago', 'clientes.rtn', 'clientes.nombre_facturacion', 'users.nombre', 'users.apellido')
            ->get();

        if ($pedidos->isEmpty()) {
            $this->command->warn('Ola5FacturacionSeeder: no hay pedidos pagados, se omiten las facturas.');
            return;
        }

        $cais = DB::table('autorizaciones_cai')
            ->join('puntos_emision', 'puntos_emision.id', '=', 'autorizaciones_cai.punto_emision_id')
            ->join('tiendas', 'tiendas.id', '=', 'puntos_emision.tienda_id')
            ->join('tipos_documento_fiscal', 'tipos_documento_fiscal.id', '=', 'autorizaciones_cai.tipo_documento_fiscal_id')
            ->where('autorizaciones_cai.activo', true)
            ->where('autorizaciones_cai.fecha_limite_emision', '>=', now()->toDateString())
            ->where('tipos_documento_fiscal.codigo', '01')
            ->select(
                'autorizaciones_cai.id',
                'autorizaciones_cai.ultimo_correlativo',
                'autorizaciones_cai.rango_final',
                'puntos_emision.tienda_id',
                'puntos_emision.codigo as codigo_punto',
                'tiendas.codigo_establecimiento'
            )
            ->get()
            ->keyBy('tienda_id');

        DB::transaction(function () use ($pedidos, $cais) {
            foreach ($pedidos as $pedido) {
                $cai = $cais[$pedido->tienda_id] ?? null;

                if (! $cai || $cai->ultimo_correlativo >= $cai->rango_final) {
                    $this->command->warn("Pedido {$pedido->codigo}: su tienda no tiene CAI vigente o con rango disponible.");
                    continue;
                }

                $cai->ultimo_correlativo++;
                $numero = "{$cai->codigo_establecimiento}-{$cai->codigo_punto}-01-"
                    . str_pad($cai->ultimo_correlativo, 8, '0', STR_PAD_LEFT);

                $factura = Factura::create([
                    'autorizacion_cai_id' => $cai->id,
                    'pedido_id' => $pedido->id,
                    'numero_factura' => $numero,
                    'correlativo' => $cai->ultimo_correlativo,
                    'fecha_emision' => $pedido->fecha_pago,
                    'cliente_nombre' => $pedido->rtn && $pedido->nombre_facturacion
                        ? $pedido->nombre_facturacion
                        : "{$pedido->nombre} {$pedido->apellido}",
                    'cliente_rtn' => $pedido->rtn,
                    'importe_exento' => 0,
                    'importe_gravado_15' => round($pedido->subtotal - $pedido->descuento_cupon + $pedido->costo_envio, 2),
                    'descuento' => $pedido->descuento_cupon,
                    'isv_15' => $pedido->isv,
                    'total' => $pedido->total,
                    'total_en_letras' => MontoEnLetras::convertir($pedido->total),
                    'estado' => 'emitida',
                ]);

                $this->crearDetalles($factura->id, $pedido);
            }

            foreach ($cais as $cai) {
                AutorizacionCai::where('id', $cai->id)->update(['ultimo_correlativo' => $cai->ultimo_correlativo]);
            }
        });
    }

    private function crearDetalles(int $facturaId, object $pedido): void
    {
        $items = DB::table('pedido_items')->where('pedido_id', $pedido->id)->get();

        foreach ($items as $item) {
            FacturaDetalle::create([
                'factura_id' => $facturaId,
                'descripcion' => $item->descripcion,
                'cantidad' => $item->cantidad,
                'precio_unitario' => $item->precio_unitario,
                'descuento' => $item->descuento,
                'total_linea' => round($item->cantidad * $item->precio_unitario - $item->descuento, 2),
            ]);
        }

        if ($pedido->costo_envio > 0) {
            FacturaDetalle::create([
                'factura_id' => $facturaId,
                'descripcion' => 'Servicio de envío',
                'cantidad' => 1,
                'precio_unitario' => $pedido->costo_envio,
                'descuento' => 0,
                'total_linea' => $pedido->costo_envio,
            ]);
        }
    }
}