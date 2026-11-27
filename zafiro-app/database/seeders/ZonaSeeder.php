<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ZonaSeeder extends Seeder
{
    public function run(): void
    {
        $colonias = [
            '0801' => [
                'Colonia Palmira',
                'Colonia Kennedy',
                'Colonia Miramontes',
                'Colonia Las Minitas',
                'Colonia Humuya',
                'Colonia América',
                'Colonia San Miguel',
                'Colonia Loarque',
                'Colonia El Trapiche',
                'Colonia Lomas del Guijarro',
                'Colonia 15 de Septiembre',
                'Colonia La Reforma',
            ],

            '0501' => [
                'Colonia Trejo',
                'Colonia Moderna',
                'Colonia Fesitranh',
                'Colonia Universidad',
                'Colonia Satélite',
                'Colonia Jardines del Valle',
                'Colonia Los Álamos',
                'Colonia Río de Piedras',
                'Colonia Zerón',
                'Colonia Stibys',
                'Colonia Tara',
                'Colonia Villas del Sol',
            ],
        ];

        foreach ($colonias as $codigoMunicipio => $nombres) {
            $municipio = DB::table('municipios')
                ->where('codigo', $codigoMunicipio)
                ->first();

            if (!$municipio) {
                throw new \RuntimeException(
                    "No existe el municipio con código {$codigoMunicipio}. Ejecuta primero el seeder del I1."
                );
            }

            foreach ($nombres as $nombre) {
                DB::table('zonas')->updateOrInsert(
                    [
                        'municipio_id' => $municipio->id,
                        'nombre' => $nombre,
                    ],
                    [
                        'activo' => true,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }
    }
}
