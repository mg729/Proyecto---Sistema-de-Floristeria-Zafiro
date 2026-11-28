<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Ola4PedidosSeeder extends Seeder
{
    // Integrante 4 · Ola 4: carritos y pedidos de prueba
    public function run(): void
    {
        // Si todavía no están los datos de las olas anteriores, se salta sin romper
        $faltantes = [];

        foreach ([
                     'clientes' => 'clientes',
                     'empleados' => 'empleados',
                     'tiendas' => 'tiendas',
                     'estados_pedido' => 'estados del pedido',
                     'turnos_entrega' => 'turnos',
                     'tienda_turno' => 'cupos por turno',
                     'tarifas_envio' => 'tarifas de envío',
                     'metodos_pago' => 'métodos de pago',
                     'variantes_arreglo' => 'variantes de arreglo',
                 ] as $tabla => $nombre) {
            if (! DB::table($tabla)->exists()) {
                $faltantes[] = $nombre;
            }
        }

        if ($faltantes) {
            $this->command->warn('Ola 4 · Pedidos: faltan ' . implode(', ', $faltantes) . '. Se salta.');
            return;
        }

        $this->call([
            CarritoSeeder::class,
            PedidoSeeder::class,
        ]);
    }
}
