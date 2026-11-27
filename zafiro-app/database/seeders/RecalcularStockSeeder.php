<?php

namespace Database\Seeders;

use App\Models\InventarioTienda;
use App\Models\LoteInsumo;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RecalcularStockSeeder extends Seeder
{
    /**
     * Seeder RecalcularStockSeeder: Deja stock_actual igual a 
     * la suma de lo que queda en los lotes de cada insumo en cada tienda.
     * 
     * stock_actual siempre es la suma de lo que queda en los lotes de ese
     * insumo en esa tienda.
     * 
     * Se llama al final de la Ola 2 y otra vez al final de la Ola 5.
     */

    public function run(): void
    {
        foreach (InventarioTienda::all() as $fila) {
            $fila->update([
                'stock_actual' => LoteInsumo::where('tienda_id', $fila->tienda_id)
                    ->where('insumo_id', $fila->insumo_id)
                    ->sum('cantidad_disponible'),
            ]);
        }
    }
}
