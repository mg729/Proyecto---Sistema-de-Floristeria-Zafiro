<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class Ola1AgendaSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ZonaSeeder::class,
            TarifaEnvioSeeder::class,
            TurnoEntregaSeeder::class,
            TiendaTurnoSeeder::class,
            ExcepcionAgendaSeeder::class,
            MetodoPagoSeeder::class,
        ]);
    }
}
