<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\EstadoPedido;

class EstadoPedidoSeeder extends Seeder
{
    /**
     * Seeder: Estado Pedido
     */
    public function run(): void
    {
        // El orden del arreglo es el orden del flujo del pedido
        $estados = [
            ['nombre' => 'Pendiente', 'slug' => 'pendiente', 'color_hex' => '#6C757D', 'es_final' => false],
            ['nombre' => 'En armado', 'slug' => 'en_armado', 'color_hex' => '#0D6EFD', 'es_final' => false],
            ['nombre' => 'Listo para entregar', 'slug' => 'listo_para_entregar', 'color_hex' => '#FFC107', 'es_final' => false],
            ['nombre' => 'Pagado', 'slug' => 'pagado', 'color_hex' => '#198754', 'es_final' => false],
            ['nombre' => 'En ruta', 'slug' => 'en_ruta', 'color_hex' => '#0DCAF0', 'es_final' => false],
            ['nombre' => 'Entregado', 'slug' => 'entregado', 'color_hex' => '#20C997', 'es_final' => true],
            ['nombre' => 'Cancelado', 'slug' => 'cancelado', 'color_hex' => '#DC3545', 'es_final' => true],
        ];

        foreach ($estados as $indice => $estado) {
            EstadoPedido::create($estado + ['orden' => $indice + 1]);
        }
    }
}
