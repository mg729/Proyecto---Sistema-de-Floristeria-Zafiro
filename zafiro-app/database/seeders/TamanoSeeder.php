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
                
            ],
            [
                'nombre' => 'Premium',
               
            ],
            [
                'nombre' => 'Deluxe',
               
            ],
        ];

        foreach ($tamanos as $tamano) {
            //$tamano['slug'] = $tamano['slug'] ?? Str::slug($tamano['nombre']);

            Tamano::updateOrCreate(
                ['nombre' => $tamano['nombre']],
                $tamano
            );
        }
    }
}
