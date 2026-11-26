<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TurnoEntregaSeeder extends Seeder
{
    public function run(): void
    {
        $turnos = [
            [
                'nombre' => 'Mañana',
                'hora_inicio' => '08:00:00',
                'hora_fin' => '12:00:00',
            ],
            [
                'nombre' => 'Tarde',
                'hora_inicio' => '13:00:00',
                'hora_fin' => '17:00:00',
            ],
        ];

        foreach ($turnos as $turno) {
            DB::table('turnos_entrega')->updateOrInsert(
                ['nombre' => $turno['nombre']],
                [
                    'hora_inicio' => $turno['hora_inicio'],
                    'hora_fin' => $turno['hora_fin'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
