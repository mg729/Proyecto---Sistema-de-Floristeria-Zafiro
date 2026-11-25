<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // Ejecuta los seeders por olas y cada ola solo usa datos de las anteriores
    public function run(): void
    {
        $this->call([
            // Ola 1 - catálogos base (sin datos)
            Ola1SeguridadSeeder::class,     
            Ola1CatalogoSeeder::class,      
            Ola1InsumosSeeder::class,       
            Ola1CreadorSeeder::class,       
            Ola1AgendaSeeder::class,

            // Ola 2 - inventario y direcciones
            Ola2InventarioSeeder::class, 
            Ola2DireccionesSeeder::class, 

            // Ola 3 - arreglos, promociones, diseños y mermas
            Ola3ArreglosSeeder::class,      
            Ola3CreadorSeeder::class,       

            // Ola 4 - carritos y pedidos de prueba
            Ola4PedidosSeeder::class,     

            // Ola 5 - historial, kardex y facturas
            Ola5TallerSeeder::class,        
            Ola5FacturacionSeeder::class,
        ]);
    }
}