<?php

namespace Database\Seeders;

use App\Models\LoteInsumo;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class Ola5TallerSeeder extends Seeder
{
    // Integrante 5 - Ola 5: historial de estados y kardex
    public function run(): void
    {
        // Si no existen los pedidos del I4(Ola4) y los lotes (ola2)
        if (!DB::table('pedidos')->exists()) {
            $this->command->warn('Ola 5 - Taller: todavia no hay pedidos (Ola 4 del I4). Se salta.');
            return;
        }

        if (!LoteInsumo::exists()) {
            $this->command->warn('Ola 5 - Taller: todavia no hay lotes (Ola 2). Se salta.');
            return;
        }

        $this->call([
            HistorialEstadoPedidoSeeder::class,
            MovimientoInventarioSeeder::class,
            RecalcularStockSeeder::class,
        ]);
    }
}