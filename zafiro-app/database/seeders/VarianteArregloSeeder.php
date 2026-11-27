<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Arreglo;
use App\Models\Tamano;
use App\Models\VarianteArreglo;

class VarianteArregloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arreglos = Arreglo::orderBy('id')->get();
        $tamanos = Tamano::where('activo', true)->orderBy('orden')->get();

        foreach ($arreglos as $arreglo) {
            foreach ($tamanos as $tamano) {
                $multiplicador = (float) ($tamano->multiplicador_precio ?? 1);
                $precio = round((float) $arreglo->precio_base * $multiplicador, 2);
                $skuVariante = $arreglo->sku . '-' . strtoupper(substr($tamano->slug ?? $tamano->nombre, 0, 3));

                //Comentado mientras sale la ola necesaria
               /* VarianteArreglo::updateOrCreate(
                    ['sku' => $skuVariante],
                    [
                        'arreglo_id' => $arreglo->id,
                        'tamano_id' => $tamano->id,
                        'nombre' => $arreglo->nombre . ' · ' . $tamano->nombre,
                        'precio' => $precio,
                        'activo' => true,
                    ]
                );*/
            }
        }
    }
}
