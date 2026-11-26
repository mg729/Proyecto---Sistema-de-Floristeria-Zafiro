<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Proveedor;

class ProveedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = fake('es_ES');

        $empresas = [
            'Inverflores de Honduras',
            'AgroFlores del Valle',
            'Viveros El Zamorano',
            'Cultivos Santa Lucía',
            'Flores y Follajes Sula',
            'Jardines del Centro',
            'Importadora Floral Zafiro',
            'Botánica Tegucigalpa',
            'Finca La Esperanza Floral',
            'Especies y Tallos HN',
            'Comercializadora Rosaleda',
            'Distribuidora Orquídea',
            'Empaques y Cajas del Sur',
            'Listones y Detalles HN',
            'Cosecha Fresca Copán',
            'Insumos Florales Cortés',
            'Lirios y Claveles del Lago',
            'Artesanías en Mimbre HN',
            'Aromas y Chocolates Finos',
            'Suministros Eventos & Hogar',
        ];

        foreach ($empresas as $indice => $nombreEmpresa) {
            // RTN único de 14 dígitos
            $rtn = str_pad((string) (80190000000000 + (($indice + 1) * 1379)), 14, '0', STR_PAD_LEFT);
            // Teléfono de 8 dígitos en Honduras
            $telefono = '9' . str_pad((string) (2000000 + (($indice + 1) * 31415)), 7, '0', STR_PAD_LEFT);

            Proveedor::updateOrCreate(
                ['rtn' => $rtn],
                [
                    'nombre' => $nombreEmpresa,
                    'contacto' => $faker->name(),
                    'telefono' => $telefono,
                    'email' => 'ventas' . ($indice + 1) . '@proveedores-hn.test',
                    'direccion' => 'Col. ' . $faker->streetName() . ', ' . ($indice % 2 === 0 ? 'Tegucigalpa' : 'San Pedro Sula'),
                    'activo' => true,
                ]
            );
        }
    }
}
