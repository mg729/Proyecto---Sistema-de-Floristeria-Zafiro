<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class Ola1SeguridadSeeder extends Seeder
{
         public function run(): void
    {
        $this->call([
            RolSeeder::class,
            DepartamentoSeeder::class,
            MunicipioSeeder::class,
            EmpresaSeeder::class,
            TiendaSeeder::class,
            UsuarioSeeder::class,
            TipoDocumentoFiscalSeeder::class,
            PuntoEmisionSeeder::class,
            AutorizacionCaiSeeder::class,
        ]);
    }
}
