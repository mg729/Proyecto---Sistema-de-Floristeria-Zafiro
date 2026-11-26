<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiendaTurnoSeeder extends Seeder
{
    public function run(): void
    {
        $tiendas = DB::table('tiendas')->get();

        $turnos = DB::table('turnos_entrega')->get();

        foreach ($tiendas as $tienda) {
            foreach ($turnos as $turno) {
                DB::table('tienda_turno')->updateOrInsert(
                    [
                        'tienda_id' => $tienda->id,
                        'turno_entrega_id' => $turno->id,
                    ],
                    [
                        'cupo_maximo' => 15,
                        'activo' => true,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }
    }
}
