<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Modelo3dSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();
        $procedurales = [
            ['Ramo envuelto', 'base_ramo', 1.00, 30.00],
            ['Caja sombrerera', 'base_caja_sombrerera', 1.00, 18.00],
            ['Centro de mesa bajo', 'base_centro_mesa', 1.00, 15.00],
            ['Canasta de mimbre', 'base_canasta', 1.10, 25.00],
            ['Jarrón de cristal', 'base_jarron', 1.00, 28.00],
            ['Rosa', 'flor_rosa', 1.00, 55.00],
            ['Girasol', 'flor_girasol', 1.20, 70.00],
            ['Tulipán', 'flor_tulipan', 1.00, 45.00],
            ['Margarita', 'flor_margarita', 0.90, 40.00],
            ['Clavel', 'flor_clavel', 0.90, 50.00],
            ['Gerbera', 'flor_gerbera', 1.00, 45.00],
            ['Eucalipto', 'follaje_eucalipto', 1.00, 45.00],
            ['Helecho', 'follaje_helecho', 1.10, 35.00],
            ['Ruscus', 'follaje_ruscus', 1.00, 40.00],
            ['Lazo de satén', 'comp_lazo', 1.00, 8.00],
            ['Mariposa decorativa', 'comp_mariposa', 0.80, 6.00],
            ['Perlas decorativas', 'comp_perlas', 0.80, 5.00],
            ['Soporte de tarjeta', 'comp_soporte_tarjeta', 1.00, 12.00],
        ];
        $glb = [
            ['Lirio premium (GLB)', 'modelos/lirio_premium.glb', 1.00, 60.00],
            ['Orquídea premium (GLB)', 'modelos/orquidea_premium.glb', 1.00, 50.00],
        ];

        $filas = [];

        foreach ($procedurales as [$nombre, $clave, $escala, $altura]) {
            $filas[] = [
                'nombre' => $nombre,
                'tipo_geometria' => 'procedural',
                'clave_procedural' => $clave,
                'archivo' => null,
                'escala' => $escala,
                'altura_cm' => $altura,
                'miniatura' => null,
                'activo' => true,
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ];
        }

        foreach ($glb as [$nombre, $archivo, $escala, $altura]) {
            $filas[] = [
                'nombre' => $nombre,
                'tipo_geometria' => 'glb',
                'clave_procedural' => null,
                'archivo' => $archivo,
                'escala' => $escala,
                'altura_cm' => $altura,
                'miniatura' => null,
                'activo' => false,   
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ];
        }

        DB::table('modelos_3d')->insert($filas);
    }
}