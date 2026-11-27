<?php

namespace Database\Seeders;

use App\Models\Insumo;
use App\Models\LoteInsumo;
use App\Models\Tienda;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoteInicialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         mt_srand(2026);   // misma semilla = mismos datos cada vez que se siembra

         // Lotes grandes, para que alcancen todas las mermas y consumos sembrados
        $cantidades = [
            'bases' => 40,
            'flores' => 300,
            'follajes' => 200,
            'complementos' => 30,
            'materiales' => 100,
        ];

        $insumos = Insumo::with('categoria')->get();
        $contador = 0;

        foreach(Tienda::all() as $tienda){
            foreach($insumos as $insumo){
                $categoria = $insumo->categoria->slug;
                $cantidad = $cantidades[$categoria] ?? 50;

                // Solo flores y follajes vencen; uno de cada cuatro vence en los próximos 3 dias (para la alerta)
                $vencimiento = null;
                if (in_array($categoria, ['flores', 'follajes'], true)) {
                    $contador++;
                    $dias = $contador % 4 === 0 ? mt_rand(1, 3) : mt_rand(4, 20);
                    $vencimiento = today()->addDays($dias);
                }

                LoteInsumo::create([
                    'tienda_id' => $tienda->id,
                    'insumo_id' => $insumo->id,
                    'detalle_compra_id' => null,   // inventario inicial: no viene de una compra
                    'codigo_lote' => "L-{$tienda->codigo}-{$insumo->sku}-01",
                    'cantidad_inicial' => $cantidad,
                    'cantidad_disponible' => $cantidad,
                    'costo_unitario' => round($insumo->precio_componente * 0.40, 2),
                    'fecha_ingreso' => now()->subDays(90),
                    'fecha_vencimiento' => $vencimiento,
                ]);
            }
        }
    }
}
