<?php

namespace Database\Seeders;

use App\Models\FormatoArreglo;
use App\Models\Ocasion;
use App\Models\Proveedor;
use App\Models\Tamano;
use Illuminate\Database\Seeder;

class Ola1CatalogoSeeder extends Seeder
{
    // Integrante 2 · Ola 1: ocasiones, formatos, tamaños y proveedores
    public function run(): void
    {
        $this->call([
            Proveedor::class,
            Ocasion::class,
            FormatoArreglo::class,
            Tamano::class,
        ]);
    }
}