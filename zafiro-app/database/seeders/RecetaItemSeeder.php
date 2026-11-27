<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\CategoriaInsumo;
use App\Models\Insumo;
use App\Models\RecetaItem;
use App\Models\VarianteArreglo;

class RecetaItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        $categorias = CategoriaInsumo::all()->keyBy('slug');

        $bases = Insumo::where('categoria_insumo_id', $categorias->get('bases')?->id)->orderBy('id')->get();
        $flores = Insumo::where('categoria_insumo_id', $categorias->get('flores')?->id)->orderBy('id')->get();
        $follajes = Insumo::where('categoria_insumo_id', $categorias->get('follajes')?->id)->orderBy('id')->get();
        $complementos = Insumo::where('categoria_insumo_id', $categorias->get('complementos')?->id)->orderBy('id')->get();

        if ($bases->isEmpty() || $flores->isEmpty() || $follajes->isEmpty()) {
            $this->command->warn('RecetaItemSeeder: faltan bases, flores o follajes. Se salta.');
            return;
        }

        $variantes = VarianteArreglo::with(['arreglo', 'tamano'])->orderBy('id')->get();

        foreach ($variantes as $indexVariante => $variante) {
            RecetaItem::where('variante_arreglo_id', $variante->id)->delete();

            $factorTamano = match (strtolower($variante->tamano->nombre ?? '')) {
                'premium' => 1.35,
                'deluxe' => 1.75,
                default => 1.00,
            };

            // 1. Base obligatoria (buscada por categoría 'bases')
            $base = $bases[$indexVariante % $bases->count()];
            RecetaItem::create([
                'variante_arreglo_id' => $variante->id,
                'insumo_id' => $base->id,
                'cantidad' => 1,
            ]);

            // 2. Dos tipos de flores por variante, escalando cantidad con el tamaño
            $florPrincipal = $flores[$indexVariante % $flores->count()];
            $florSecundaria = $flores[($indexVariante + 3) % $flores->count()];

            RecetaItem::create([
                'variante_arreglo_id' => $variante->id,
                'insumo_id' => $florPrincipal->id,
                'cantidad' => max(1, (int) round(6 * $factorTamano)),
            ]);

            RecetaItem::create([
                'variante_arreglo_id' => $variante->id,
                'insumo_id' => $florSecundaria->id,
                'cantidad' => max(1, (int) round(4 * $factorTamano)),
            ]);

            // 3. Follaje por variante
            $follaje = $follajes[$indexVariante % $follajes->count()];
            RecetaItem::create([
                'variante_arreglo_id' => $variante->id,
                'insumo_id' => $follaje->id,
                'cantidad' => max(1, (int) round(3 * $factorTamano)),
            ]);

            // 4. Complemento en variantes intermedias/altas
            if ($complementos->isNotEmpty() && ($indexVariante % 2 === 1)) {
                $complemento = $complementos[$indexVariante % $complementos->count()];
                RecetaItem::create([
                    'variante_arreglo_id' => $variante->id,
                    'insumo_id' => $complemento->id,
                    'cantidad' => max(1, (int) round(1 * $factorTamano)),
                ]);
            }
        }
            */
    }
}
