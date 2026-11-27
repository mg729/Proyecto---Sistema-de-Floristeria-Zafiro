<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class Ola1InsumosSeeder extends Seeder
{
    /**
     * Integrante 5 - Ola 1: unidades de medida, categorias de insumo y estados
     * del pedido.
     */
    public function run(): void
    {
        $this->call([
            UnidadMedidaSeeder::class,
            CategoriaInsumoSeeder::class,
            EstadoPedidoSeeder::class,
        ]);
    }
}
