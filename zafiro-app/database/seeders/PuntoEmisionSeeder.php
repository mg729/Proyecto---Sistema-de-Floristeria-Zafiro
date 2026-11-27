<?php

namespace Database\Seeders;

use App\Models\PuntoEmision;
use App\Models\Tienda;
use Illuminate\Database\Seeder;

class PuntoEmisionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Tienda::all() as $tienda) {
            PuntoEmision::create([
                'tienda_id' => $tienda->id,
                'codigo' => '001',
                'descripcion' => 'Caja principal',
                'activo' => true,
            ]);
        }
    }
}