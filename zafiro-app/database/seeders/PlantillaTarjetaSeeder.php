<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlantillaTarjetaSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();
        $ocasiones = DB::table('ocasiones')->pluck('id', 'slug')->all();   // slug => id
        $plantillas = [
            ['Cumpleaños alegre', 'cumpleanos', 'Dancing Script', '#B8336A'],
            ['Feliz cumpleaños dorado', 'cumpleanos', 'Playfair Display', '#8A6D1D'],
            ['Aniversario elegante', 'aniversario', 'Great Vibes', '#1F3A5F'],
            ['Nuestro aniversario', 'aniversario', 'Lora', '#5A2A4A'],
            ['Con todo mi amor', 'san-valentin', 'Dancing Script', '#A3123A'],
            ['Rosas de San Valentín', 'san-valentin', 'Great Vibes', '#8B1E3F'],
            ['Para mamá con cariño', 'dia-de-la-madre', 'Dancing Script', '#C2478A'],
            ['Gracias, mamá', 'dia-de-la-madre', 'Lora', '#7A2E5C'],
            ['Felicidades, graduado', 'graduacion', 'Playfair Display', '#1F3A5F'],
            ['En memoria y con cariño', 'condolencias', 'Georgia', '#444444'],
            ['Bienvenido bebé', 'nacimiento', 'Lora', '#3E7C8C'],
            ['Gracias de corazón', 'agradecimiento', 'Great Vibes', '#2F6B4F'],
            ['Con cariño', null, 'Georgia', '#333333'],
            ['Pensando en ti', null, 'Dancing Script', '#555555'],
            ['Para ti', null, 'Lora', '#333333'],
            ['Floral clásica', null, 'Playfair Display', '#4A3B2A'],
            ['Minimalista blanca', null, 'Georgia', '#222222'],
            ['Acuarela de flores', null, 'Dancing Script', '#6B3FA0'],
            ['Zafiro azul', null, 'Playfair Display', '#0F3D91'],
            ['Dorado elegante', null, 'Great Vibes', '#8A6D1D'],
        ];

        $filas = [];

        foreach ($plantillas as [$nombre, $slugOcasion, $fuente, $color]) {
            $filas[] = [
                'ocasion_id' => $slugOcasion ? ($ocasiones[$slugOcasion] ?? null) : null,
                'nombre' => $nombre,
                'imagen_fondo' => null,
                'fuente' => $fuente,
                'color_texto' => $color,
                'activo' => true,
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ];
        }

        DB::table('plantillas_tarjeta')->insert($filas);
    }
}