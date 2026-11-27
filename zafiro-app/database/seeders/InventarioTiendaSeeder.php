<?php

namespace Database\Seeders;

use App\Models\Insumo;
use App\Models\InventarioTienda;
use App\Models\Tienda;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InventarioTiendaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mínimo y ubicación según la categoría del insumo
        $parametros = [
            'bases' => ['minimo' => 5, 'ubicacion' => 'Bodega'],
            'flores' => ['minimo' => 20, 'ubicacion' => 'Camara fria 1'],
            'follajes' => ['minimo' => 15, 'ubicacion' => 'Camara fria 2'],
            'complementos' => ['minimo' => 5, 'ubicacion' => 'Estante A'],
            'materiales' => ['minimo' => 10, 'ubicacion' => 'Estante B'],
        ];

        $insumos = Insumo::with('categoria')->get();

        foreach (Tienda::all() as $tienda) {
            foreach ($insumos as $insumo) {
                $parametro = $parametros[$insumo->categoria->slug] ?? ['minimo' => 5, 'ubicacion' => 'Bodega'];

                InventarioTienda::create([
                    'tienda_id' => $tienda->id,
                    'insumo_id' => $insumo->id,
                    'stock_actual' => 0,   // se calcula al final con la suma de los lotes
                    'stock_minimo' => $parametro['minimo'],
                    'ubicacion' => $parametro['ubicacion'],
                ]);
            }
        }
    }
}
