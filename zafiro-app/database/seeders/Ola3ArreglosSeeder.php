<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\CategoriaInsumo;
use App\Models\FormatoArreglo;
use App\Models\Insumo;
use App\Models\Ocasion;
use App\Models\Tamano;

class Ola3ArreglosSeeder extends Seeder
{
    
    public function run(): void
    {
        /*
        $tieneCatalogoBase = FormatoArreglo::exists()
            && Tamano::exists()
            && Ocasion::exists();

        $tieneInsumos = Insumo::exists()
            && CategoriaInsumo::where('slug', 'bases')->exists();

        if (! $tieneCatalogoBase || ! $tieneInsumos) {
            $this->command->warn('Ola 3 · Arreglos: faltan catálogos base o insumos/categorías. Se salta.');
            return;
        }

        $this->call([
            ArregloSeeder::class,
            VarianteArregloSeeder::class,
            RecetaItemSeeder::class,
        ]); */
    }
}