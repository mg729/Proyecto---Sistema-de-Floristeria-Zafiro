<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UnidadMedida; //Modelo 

class UnidadMedidaSeeder extends Seeder
{
    /**
     * Seeders de :
     */
    public function run(): void
    {
        //Unidades y abreviaturas
        $unidades = [
            ['nombre' => 'Tallo', 'abreviatura' => 'tallo'],
            ['nombre' => 'Ramo', 'abreviatura' => 'rama'],
            ['nombre' => 'Unidad', 'abreviatura' => 'und'],
            ['nombre' => 'Metro', 'abreviatura' => 'm'],
            ['nombre' => 'Caja', 'abreviatura' => 'caja'],
        ];

        foreach ($unidades as $unidad)
        {
            UnidadMedida::create($unidad);
        }
    }
}
