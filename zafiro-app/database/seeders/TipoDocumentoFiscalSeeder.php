<?php

namespace Database\Seeders;

use App\Models\TipoDocumentoFiscal;
use Illuminate\Database\Seeder;

class TipoDocumentoFiscalSeeder extends Seeder
{
    public function run(): void
    {
        TipoDocumentoFiscal::create(['codigo' => '01', 'nombre' => 'Factura']);
    }
}