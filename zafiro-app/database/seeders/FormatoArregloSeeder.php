<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FormatoArreglo;

class FormatoArregloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $formatos = [
            [
                'slug' => 'ramo',
                'nombre' => 'Ramo',
                'descripcion' => 'Diseño floral atado a mano con envoltura estética y listón.',
                'activo' => true,
            ],
            [
                'slug' => 'caja-sombrerera',
                'nombre' => 'Caja sombrerera',
                'descripcion' => 'Arreglo estructurado en caja cilíndrica con espuma floral hidratada.',
                'activo' => true,
            ],
            [
                'slug' => 'centro-de-mesa',
                'nombre' => 'Centro de mesa',
                'descripcion' => 'Composición horizontal o equilibrada pensada para mesas y eventos.',
                'activo' => true,
            ],
            [
                'slug' => 'canasta',
                'nombre' => 'Canasta',
                'descripcion' => 'Presentación clásica en canasta tejida con flores y detalles.',
                'activo' => true,
            ],
            [
                'slug' => 'jarron',
                'nombre' => 'Jarrón',
                'descripcion' => 'Arreglo listo para lucir en base o jarrón decorativo.',
                'activo' => true,
            ],
        ];

        foreach ($formatos as $formato) {
            FormatoArreglo::updateOrCreate(
                ['slug' => $formato['slug']],
                $formato
            );
        }
    }
}
