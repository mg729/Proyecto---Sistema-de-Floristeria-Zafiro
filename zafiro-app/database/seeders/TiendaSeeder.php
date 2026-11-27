<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\Municipio;
use App\Models\Tienda;
use Illuminate\Database\Seeder;

class TiendaSeeder extends Seeder
{
    public function run(): void
    {
        $empresa = Empresa::where('rtn', '08019026123456')->firstOrFail();
        $municipios = Municipio::whereIn('codigo', ['0801', '0501'])->pluck('id', 'codigo');

        Tienda::create([
            'empresa_id' => $empresa->id,
            'municipio_id' => $municipios['0801'],
            'codigo' => 'TGU01',
            'nombre' => 'Zafiro Tegucigalpa',
            'codigo_establecimiento' => '001',
            'direccion' => 'Col. Palmira, Av. República de Panamá, Tegucigalpa',
            'telefono' => '22390000',
            'correo' => 'tegucigalpa@zafiro.hn',
            'latitud' => 14.0960000,
            'longitud' => -87.1870000,
            'hora_apertura' => '08:00',
            'hora_cierre' => '18:00',
            'horas_preparacion_min' => 4,
            'activo' => true,
        ]);

        Tienda::create([
            'empresa_id' => $empresa->id,
            'municipio_id' => $municipios['0501'],
            'codigo' => 'SPS01',
            'nombre' => 'Zafiro San Pedro Sula',
            'codigo_establecimiento' => '002',
            'direccion' => 'Barrio Río de Piedras, 21 Av. S.O., San Pedro Sula',
            'telefono' => '25500000',
            'correo' => 'sanpedrosula@zafiro.hn',
            'latitud' => 15.5040000,
            'longitud' => -88.0250000,
            'hora_apertura' => '08:00',
            'hora_cierre' => '18:00',
            'horas_preparacion_min' => 4,
            'activo' => true,
        ]);
    }
}