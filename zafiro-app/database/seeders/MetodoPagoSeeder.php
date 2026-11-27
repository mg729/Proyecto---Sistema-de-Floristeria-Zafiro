<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetodoPagoSeeder extends Seeder
{
    public function run(): void
    {
        $metodos = [
            [
                'nombre' => 'Efectivo',
                'slug' => 'efectivo',
                'requiere_comprobante' => false,
            ],
            [
                'nombre' => 'Tarjeta',
                'slug' => 'tarjeta',
                'requiere_comprobante' => false,
            ],
            [
                'nombre' => 'Transferencia',
                'slug' => 'transferencia',
                'requiere_comprobante' => true,
            ],
        ];

        foreach ($metodos as $metodo) {
            DB::table('metodos_pago')->updateOrInsert(
                ['slug' => $metodo['slug']],
                [
                    'nombre' => $metodo['nombre'],
                    'requiere_comprobante' => $metodo['requiere_comprobante'],
                    'activo' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
