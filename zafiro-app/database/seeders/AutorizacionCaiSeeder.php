<?php

namespace Database\Seeders;

use App\Models\AutorizacionCai;
use App\Models\PuntoEmision;
use App\Models\TipoDocumentoFiscal;
use Illuminate\Database\Seeder;

class AutorizacionCaiSeeder extends Seeder
{
    public function run(): void
    {
        $factura = TipoDocumentoFiscal::where('codigo', '01')->firstOrFail();

        foreach (PuntoEmision::all() as $punto) {
            AutorizacionCai::create([
                'punto_emision_id' => $punto->id,
                'tipo_documento_fiscal_id' => $factura->id,
                'cai' => $this->generarCai(),
                'rango_inicial' => 1,
                'rango_final' => 5000,
                'ultimo_correlativo' => 0,
                'fecha_autorizacion' => now()->subMonths(2)->toDateString(),
                'fecha_limite_emision' => now()->addMonths(10)->toDateString(),
                'activo' => true,
            ]);
        }

        AutorizacionCai::create([
            'punto_emision_id' => PuntoEmision::first()->id,
            'tipo_documento_fiscal_id' => $factura->id,
            'cai' => $this->generarCai(),
            'rango_inicial' => 1,
            'rango_final' => 1000,
            'ultimo_correlativo' => 1000,
            'fecha_autorizacion' => now()->subYears(2)->toDateString(),
            'fecha_limite_emision' => now()->subYear()->toDateString(),
            'activo' => false,
        ]);
    }

    private function generarCai(): string
    {
        $grupos = [];

        for ($i = 0; $i < 5; $i++) {
            $grupos[] = strtoupper(bin2hex(random_bytes(3)));
        }

        $grupos[] = strtoupper(bin2hex(random_bytes(1)));

        return implode('-', $grupos);
    }
}