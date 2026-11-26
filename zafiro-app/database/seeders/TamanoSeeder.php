<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tamano;
use Illuminate\Support\Str;

class TamanoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tamanos = [
            [
                'nombre' => 'Estándar',
                'slug' => 'estandar',
                'multiplicador_precio' => 1.00,
                'multiplicador_cantidad' => 1.00,
                'orden' => 1,
                'activo' => true,
            ],
            [
                'nombre' => 'Premium',
                'slug' => 'premium',
                'multiplicador_precio' => 1.35,
                'multiplicador_cantidad' => 1.30,
                'orden' => 2,
                'activo' => true,
            ],
            [
                'nombre' => 'Deluxe',
                'slug' => 'deluxe',
                'multiplicador_precio' => 1.75,
                'multiplicador_cantidad' => 1.65,
                'orden' => 3,
                'activo' => true,
            ],
        ];

        foreach ($tamanos as $tamano) {
            $tamano['slug'] = $tamano['slug'] ?? Str::slug($tamano['nombre']);

            Tamano::updateOrCreate(
                ['nombre' => $tamano['nombre']],
                $tamano
            );
        }
    }
}
