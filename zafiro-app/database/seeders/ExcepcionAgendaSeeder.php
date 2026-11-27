<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExcepcionAgendaSeeder extends Seeder
{
    public function run(): void
    {
        $motivos = [
            'Temporada de alta demanda',
            'Capacidad especial de entregas',
            'Preparación para evento especial',
            'Ajuste de capacidad por personal',
            'Jornada especial de entregas',
        ];

        $tiendas = DB::table('tiendas')->get();
        $turnos = DB::table('turnos_entrega')->get();

        foreach ($tiendas as $tienda) {
            foreach ($turnos as $turno) {
                for ($i = 0; $i < 5; $i++) {
                    $fecha = now()->addDays($i + 1)->toDateString();

                    DB::table('excepciones_agenda')->updateOrInsert(
                        [
                            'tienda_id' => $tienda->id,
                            'turno_entrega_id' => $turno->id,
                            'fecha' => $fecha,
                        ],
                        [
                            'cupo_maximo' => [5, 10, 20, 25, 30][$i],
                            'motivo' => $motivos[$i],
                            'updated_at' => now(),
                            'created_at' => now(),
                        ]
                    );
                }
            }
        }
    }
}
