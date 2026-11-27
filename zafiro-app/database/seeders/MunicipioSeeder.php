<?php

namespace Database\Seeders;

use App\Models\Departamento;
use App\Models\Municipio;
use Illuminate\Database\Seeder;

class MunicipioSeeder extends Seeder
{
    public function run(): void
    {
        $municipios = [
            '0101' => 'La Ceiba', '0107' => 'Tela', '0201' => 'Trujillo',
            '0301' => 'Comayagua', '0318' => 'Siguatepeque', '0401' => 'Santa Rosa de Copán',
            '0404' => 'Copán Ruinas', '0501' => 'San Pedro Sula', '0502' => 'Choloma',
            '0506' => 'Puerto Cortés', '0511' => 'Villanueva', '0512' => 'La Lima',
            '0601' => 'Choluteca', '0703' => 'Danlí', '0801' => 'Distrito Central',
            '0823' => 'Santa Lucía', '0826' => 'Valle de Ángeles', '1001' => 'La Esperanza',
            '1101' => 'Roatán', '1301' => 'Gracias', '1501' => 'Juticalpa',
            '1503' => 'Catacamas', '1701' => 'Nacaome', '1804' => 'El Progreso',
        ];

        $departamentos = Departamento::pluck('id', 'codigo');

        foreach ($municipios as $codigo => $nombre) {
            Municipio::create([
                'departamento_id' => $departamentos[substr($codigo, 0, 2)],
                'codigo' => $codigo,
                'nombre' => $nombre,
            ]);
        }
    }
}
