<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class Ola1CreadorSeeder extends Seeder
{

    public function run(): void
    {
        $this->call([
            Modelo3dSeeder::class,
            PlantillaTarjetaSeeder::class,
            CuponSeeder::class,
        ]);
    }
}