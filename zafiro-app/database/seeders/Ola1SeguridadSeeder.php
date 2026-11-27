<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class Ola1SeguridadSeeder extends Seeder
{
    // Integrante 1 · Ola 1: roles, geografía, empresa, tiendas, usuarios y datos SAR
         public function run(): void
    {
        $this->call([
            RolSeeder::class,
            DepartamentoSeeder::class,
            MunicipioSeeder::class,
            EmpresaSeeder::class,
            TiendaSeeder::class,
            TipoDocumentoFiscalSeeder::class,
            PuntoEmisionSeeder::class,
            AutorizacionCaiSeeder::class,
        ]);
    }
}
