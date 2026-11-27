<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HistorialEstadoPedidoSeeder extends Seeder
{
    // Recorrido normal del pedido. En orden
    private const FLUJO = ['pendiente', 'en_armado', 'listo_para_entregar', 'pagado', 'en_ruta', 'entregado'];

    private const COMENTARIOS = [
        'pendiente' => 'Pedido confirmado por el cliente.',
        'en_armado' => 'El florista comenzo el armado.',
        'listo_para_entregar' => 'Todos los arreglos estan listos. Se habilita el pago.',
        'pagado' => 'Pago aprobado y factura emitida.',
        'en_ruta' => 'El repartidor salio con el pedido.',
        'entregado' => 'Pedido entregado.',
        'cancelado' => 'Pedido cancelado.',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Se borra solo esta tabla, para poder volver a correr este seeder sin duplicar
        DB::table('historial_estados_pedido')->delete();

        // Se busca por slug, nunca por ID
        $idEstado = DB::table('estados_pedido')->pluck('id', 'slug');
        $slugEstado = DB::table('estados_pedido')->pluck('slug', 'id');

        // Cuentas de usuario de clientes y empleados, para saber quien hizo cada cambio
        $usuarioCliente = DB::table('clientes')->pluck('user_id', 'id');
        $usuarioEmpleado = DB::table('empleados')->pluck('user_id', 'id');
        $usuarioAdminTienda = DB::table('empleados')
            ->join('roles', 'roles.id', '=', 'empleados.rol_id')
            ->where('roles.slug', 'admin')
            ->pluck('empleados.user_id', 'empleados.tienda_id');
        $usuarioAdmin = $usuarioAdminTienda->first();

        $entregas = DB::table('entregas')->get()->keyBy('pedido_id');
        $floristaPorPedido = DB::table('pedido_items')
            ->whereNotNull('florista_id')
            ->pluck('florista_id', 'pedido_id');

        $filas = [];

        foreach (DB::table('pedidos')->orderBy('created_at')->get() as $pedido) {
            $estadoActual = $slugEstado[$pedido->estado_pedido_id] ?? null;
            if (! $estadoActual) {
                continue;
            }

            $entrega = $entregas[$pedido->id] ?? null;
            $recorrido = $this->recorrido($estadoActual, $entrega?->tipo);

            // Quién hizo cada cambio
            $cliente = $usuarioCliente[$pedido->cliente_id] ?? null;
            $admin = $usuarioAdminTienda[$pedido->tienda_id] ?? $usuarioAdmin;
            $florista = isset($floristaPorPedido[$pedido->id]) ? ($usuarioEmpleado[$floristaPorPedido[$pedido->id]] ?? null) : null;
            $repartidor = $entrega?->repartidor_id ? ($usuarioEmpleado[$entrega->repartidor_id] ?? null) : null;

            // Fechas crecientes desde que se creó el pedido, sin pasar de hoy
            $fecha = Carbon::parse($pedido->created_at);
            $segundosDisponibles = max(0, now()->getTimestamp() - $fecha->getTimestamp());
            $paso = max(60, min(6 * 3600, intdiv($segundosDisponibles, count($recorrido))));

            foreach ($recorrido as $slug) {
                $usuario = match ($slug) {
                    'pendiente', 'pagado' => $cliente,
                    'en_armado', 'listo_para_entregar' => $florista ?? $admin,
                    'en_ruta', 'entregado' => $repartidor ?? $admin,
                    default => $admin,
                };

                $filas[] = [
                    'pedido_id' => $pedido->id,
                    'estado_pedido_id' => $idEstado[$slug],
                    'user_id' => $usuario,
                    'comentario' => $slug === 'cancelado'
                        ? ($pedido->motivo_cancelacion ?? self::COMENTARIOS['cancelado'])
                        : self::COMENTARIOS[$slug],
                    'created_at' => $fecha->copy(),
                ];

                $fecha->addSeconds($paso);
            }
        }

        // Se insertan en bloques: es mucho más rápido que una fila por consulta en TiDB
        foreach (array_chunk($filas, 200) as $bloque) {
            DB::table('historial_estados_pedido')->insert($bloque);
        }
    }

     // Estados por los que paso el pedido, desde pendiente hasta su estado actual
    private function recorrido(string $estadoActual, ?string $tipoEntrega): array
    {
        if ($estadoActual === 'cancelado') {
            return ['pendiente', 'cancelado'];
        }

        $flujo = self::FLUJO;

        // El retiro en tienda pasa de pagado a entregado, sin en_ruta
        if ($tipoEntrega === 'retiro_tienda') {
            $flujo = array_values(array_diff($flujo, ['en_ruta']));
        }

        $posicion = array_search($estadoActual, $flujo, true);

        return $posicion === false ? [$estadoActual] : array_slice($flujo, 0, $posicion + 1);
    }
}
