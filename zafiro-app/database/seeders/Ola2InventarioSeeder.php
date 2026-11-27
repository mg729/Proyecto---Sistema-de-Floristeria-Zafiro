<?php

namespace Database\Seeders;

use App\Models\CategoriaInsumo;
use App\Models\Empleado;
use App\Models\FormatoArreglo;
use App\Models\Proveedor;
use App\Models\Tienda;
use App\Models\UnidadMedida;

use Illuminate\Database\Seeder;

class Ola2InventarioSeeder extends Seeder
{
    // I5 - Ola 2: insumos, inventario, lotes y compras
    public function run(): void
    {
        //Verifica datos de la Primera Ola
        if(!CategoriaInsumo::exists() || !UnidadMedida::exists())
        {
            $this->command->warn('Ola 2 - INVENTARIO: Falta Categorias o Unidades. Se salta.');
            return;
        }

        // Si no hay datos se salta sin romperse
        if (! Tienda::exists() || ! FormatoArreglo::exists() || ! Proveedor::exists() || ! Empleado::exists()) {
            $this->command->warn('Ola 2 - INVENTARIO: faltan tiendas, formatos, proveedores o empleados. Se salta.');
            return;
        }

        $this->call([
            InsumoSeeder::class,
            InventarioTiendaSeeder::class,
            LoteInicialSeeder::class,
            CompraInsumoSeeder::class,
            RecalcularStockSeeder::class,
        ]);
    }
}


