<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ocasion;
use Illuminate\Support\Str;

class OcasionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $faker = fake('es_ES');

        $ocasionesBase = [
            ['nombre' => 'San Valentín', 'mes_inicio' => 2, 'mes_fin' => 2, 'color_hex' => '#E11D48', 'orden' => 1],
            ['nombre' => 'Día de la Madre', 'mes_inicio' => 5, 'mes_fin' => 5, 'color_hex' => '#EC4899', 'orden' => 2],
            ['nombre' => 'Cumpleaños', 'mes_inicio' => null, 'mes_fin' => null, 'color_hex' => '#F59E0B', 'orden' => 3],
            ['nombre' => 'Aniversario', 'mes_inicio' => null, 'mes_fin' => null, 'color_hex' => '#DB2777', 'orden' => 4],
            ['nombre' => 'Bodas', 'mes_inicio' => null, 'mes_fin' => null, 'color_hex' => '#F4F4F5', 'orden' => 5],
            ['nombre' => 'Condolencias', 'mes_inicio' => null, 'mes_fin' => null, 'color_hex' => '#64748B', 'orden' => 6],
            ['nombre' => 'Graduación', 'mes_inicio' => 11, 'mes_fin' => 12, 'color_hex' => '#2563EB', 'orden' => 7],
            ['nombre' => 'Nacimiento', 'mes_inicio' => null, 'mes_fin' => null, 'color_hex' => '#38BDF8', 'orden' => 8],
            ['nombre' => 'Navidad', 'mes_inicio' => 11, 'mes_fin' => 12, 'color_hex' => '#16A34A', 'orden' => 9],
            ['nombre' => 'Día del Padre', 'mes_inicio' => 6, 'mes_fin' => 6, 'color_hex' => '#0284C7', 'orden' => 10],
            ['nombre' => 'Agradecimiento', 'mes_inicio' => null, 'mes_fin' => null, 'color_hex' => '#10B981', 'orden' => 11],
            ['nombre' => 'Reconciliación', 'mes_inicio' => null, 'mes_fin' => null, 'color_hex' => '#F43F5E', 'orden' => 12],
            ['nombre' => 'Inauguración', 'mes_inicio' => null, 'mes_fin' => null, 'color_hex' => '#D97706', 'orden' => 13],
            ['nombre' => 'Bienvenida', 'mes_inicio' => null, 'mes_fin' => null, 'color_hex' => '#84CC16', 'orden' => 14],
            ['nombre' => 'Pronta Recuperación', 'mes_inicio' => null, 'mes_fin' => null, 'color_hex' => '#14B8A6', 'orden' => 15],
            ['nombre' => 'Amor y Amistad', 'mes_inicio' => 2, 'mes_fin' => 2, 'color_hex' => '#E11D48', 'orden' => 16],
            ['nombre' => 'Día de la Mujer', 'mes_inicio' => 3, 'mes_fin' => 3, 'color_hex' => '#9333EA', 'orden' => 17],
            ['nombre' => 'Corporativo', 'mes_inicio' => null, 'mes_fin' => null, 'color_hex' => '#475569', 'orden' => 18],
            ['nombre' => 'Despedida', 'mes_inicio' => null, 'mes_fin' => null, 'color_hex' => '#6366F1', 'orden' => 19],
            ['nombre' => 'Solo Porque Sí', 'mes_inicio' => null, 'mes_fin' => null, 'color_hex' => '#FB7185', 'orden' => 20],
        ];

        foreach ($ocasionesBase as $item) {
            $slug = Str::slug($item['nombre']);

            Ocasion::updateOrCreate(
                ['slug' => $slug],
                [
                    'nombre' => $item['nombre'],
                    'descripcion' => $faker->sentence(8),
                    'mes_inicio' => $item['mes_inicio'],
                    'mes_fin' => $item['mes_fin'],
                    'color_hex' => $item['color_hex'],
                    'orden' => $item['orden'],
                    'activo' => true,
                ]
            );
        }
    }
}
