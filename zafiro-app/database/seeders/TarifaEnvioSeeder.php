<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TarifaEnvioSeeder extends Seeder
{
    public function run(): void
    {
        $tiendas = [
            '0801' => 'TGU01',
            '0501' => 'SPS01',
        ];

        foreach ($tiendas as $codigoMunicipio => $codigoTienda) {
            $municipio = DB::table('municipios')
                ->where('codigo', $codigoMunicipio)
                ->first();

            $tienda = DB::table('tiendas')
                ->where('codigo', $codigoTienda)
                ->first();

            if (!$municipio || !$tienda) {
                throw new \RuntimeException(
                    "No se encontró el municipio {$codigoMunicipio} o la tienda {$codigoTienda}."
                );
            }

            $zonas = DB::table('zonas')
                ->where('municipio_id', $municipio->id)
                ->get();

            foreach ($zonas as $zona) {
                DB::table('tarifas_envio')->updateOrInsert(
                    [
                        'tienda_id' => $tienda->id,
                        'zona_id' => $zona->id,
                    ],
                    [
                        'costo' => rand(60, 150),
                        'tiempo_estimado_min' => rand(30, 90),
                        'activo' => true,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }
    }
}
