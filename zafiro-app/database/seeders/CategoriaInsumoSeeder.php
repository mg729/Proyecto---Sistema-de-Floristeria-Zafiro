<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\CategoriaInsumo;

class CategoriaInsumoSeeder extends Seeder
{
    /**
     *  Seeder: Categoria Insumo
     */
    public function run(): void
    {
        // paso_creador = paso del Creador donde aparece; null = no aparece en el Creador
        $categorias = [
            ['nombre' => 'Bases', 'slug' => 'bases', 'paso_creador' => 1, 'descripcion' => 'Jarrones, cajas y canastas'],
            ['nombre' => 'Flores', 'slug' => 'flores', 'paso_creador' => 2, 'descripcion' => 'Flores principales y de relleno'],
            ['nombre' => 'Follajes', 'slug' => 'follajes', 'paso_creador' => 3, 'descripcion' => 'Follaje de relleno'],
            ['nombre' => 'Complementos', 'slug' => 'complementos', 'paso_creador' => 4, 'descripcion' => 'Chocolates, peluches, globos y vinos'],
            ['nombre' => 'Materiales', 'slug' => 'materiales', 'paso_creador' => null, 'descripcion' => 'Cintas y material de armado'],
        ];

        foreach($categorias as $indice => $categoria) {
            CategoriaInsumo::create($categoria + ['orden' => $indice + 1]);
        }
    }
}
