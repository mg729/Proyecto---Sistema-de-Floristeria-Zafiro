<?php

namespace Database\Seeders;

use App\Models\Arreglo;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Insumo;
use App\Models\LoteInsumo;
use Illuminate\Database\Seeder;

class Ola3CreadorSeeder extends Seeder
{
    public function run(): void
    {
        
        if (
            ! Arreglo::exists()
            || ! Insumo::exists()
            || ! LoteInsumo::exists()
            || ! Cliente::exists()
            || ! Empleado::exists()
        ) {
            $this->command->warn('Ola 3 · Creador: faltan arreglos, insumos, lotes, clientes o empleados. Se salta.');
            return;
        }

        $this->call([
            PromocionSeeder::class,
            DisenoPersonalizadoSeeder::class,
            FechaEspecialSeeder::class,
            MermaSeeder::class,
        ]);
    }
}