<?php

namespace Database\Seeders;

use App\Models\Empresa;
use Illuminate\Database\Seeder;

class EmpresaSeeder extends Seeder
{
    public function run(): void
    {
        Empresa::create([
            'razon_social' => 'Floristería Zafiro, S. de R.L.',
            'nombre_comercial' => 'Floristería Zafiro',
            'rtn' => '08019026123456',
            'correo' => 'contacto@zafiro.hn',
            'telefono' => '22390000',
            'direccion_fiscal' => 'Col. Palmira, Av. República de Panamá, Tegucigalpa, Francisco Morazán',
            'activo' => true,
        ]);
    }
}