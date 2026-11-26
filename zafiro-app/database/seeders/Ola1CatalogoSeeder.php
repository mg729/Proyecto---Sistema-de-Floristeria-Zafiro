<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class Ola1CatalogoSeeder extends Seeder
{
    // Integrante 2 · Ola 1: ocasiones, formatos, tamaños y proveedores
    public function run(): void
    {
        $this->call([
            ProveedorSeeder::class,
            OcasionSeeder::class,
            FormatoArregloSeeder::class,
            TamanoSeeder::class,
        ]);
    }
}