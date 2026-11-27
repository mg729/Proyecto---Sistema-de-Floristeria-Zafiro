<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CuponSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        $tiendas = DB::table('tiendas')->pluck('id', 'codigo')->all();

        $vigenteDesde = now()->subDays(15);
        $vigenteHasta = now()->addMonths(2);
        $vencidoDesde = now()->subMonths(4);
        $vencidoHasta = now()->subMonths(1);

        $cupones = [
           
            ['BIENVENIDA10', '10% de descuento en tu primera compra', 'porcentaje', 10, 0, $vigenteDesde, $vigenteHasta, null, 1, true, null],
            ['ZAFIRO50', 'L 50 de descuento en compras desde L 300', 'monto_fijo', 50, 300, $vigenteDesde, $vigenteHasta, null, 3, true, null],
            ['FLORES15', '15% en arreglos florales desde L 400', 'porcentaje', 15, 400, $vigenteDesde, $vigenteHasta, null, 2, true, null],
            ['MAMA20', '20% para el Día de la Madre desde L 500', 'porcentaje', 20, 500, $vigenteDesde, $vigenteHasta, 200, 1, true, null],
            ['AMOR12', '12% para regalos de San Valentín desde L 350', 'porcentaje', 12, 350, $vigenteDesde, $vigenteHasta, 150, 1, true, null],
            ['PRIMAVERA8', '8% de descuento de temporada', 'porcentaje', 8, 0, $vigenteDesde, $vigenteHasta, null, 2, true, null],
            ['DESCUENTO100', 'L 100 de descuento en compras desde L 800', 'monto_fijo', 100, 800, $vigenteDesde, $vigenteHasta, 100, 1, true, null],
            ['LEMPIRAS75', 'L 75 de descuento en compras desde L 500', 'monto_fijo', 75, 500, $vigenteDesde, $vigenteHasta, null, 2, true, null],
            ['CUMPLE10', '10% para cumpleaños desde L 250', 'porcentaje', 10, 250, $vigenteDesde, $vigenteHasta, null, 2, true, null],
            ['ZAFIRO25', '25% en compras grandes desde L 1,200', 'porcentaje', 25, 1200, $vigenteDesde, $vigenteHasta, 50, 1, true, null],
            ['TEGUCIGALPA10', '10% solo en la tienda de Tegucigalpa', 'porcentaje', 10, 0, $vigenteDesde, $vigenteHasta, null, 2, true, 'TGU01'],
            ['SANPEDRO10', '10% solo en la tienda de San Pedro Sula', 'porcentaje', 10, 0, $vigenteDesde, $vigenteHasta, null, 2, true, 'SPS01'],

            ['VENCIDO15', '15% de una campaña que ya terminó', 'porcentaje', 15, 0, $vencidoDesde, $vencidoHasta, null, 1, true, null],
            ['NAVIDAD30', '30% de la campaña de Navidad', 'porcentaje', 30, 600, now()->subMonths(12), now()->subMonths(11), null, 1, true, null],
            ['SEMANASANTA10', '10% de Semana Santa', 'porcentaje', 10, 0, now()->subMonths(8), now()->subMonths(7), null, 1, true, null],
            ['VERANO20', '20% de la campaña de verano', 'porcentaje', 20, 400, now()->subMonths(6), now()->subMonths(5), null, 1, true, null],

        
            ['AGOTADO20', '20% con cupo limitado a 2 usos', 'porcentaje', 20, 0, $vigenteDesde, $vigenteHasta, 2, 1, true, null],
            ['ULTIMAVEZ15', '15% con cupo limitado a 1 uso', 'porcentaje', 15, 0, $vigenteDesde, $vigenteHasta, 1, 1, true, null],

           
            ['PAUSADO10', '10% pausado por el administrador', 'porcentaje', 10, 0, $vigenteDesde, $vigenteHasta, null, 1, false, null],
            ['SUSPENDIDO50', 'L 50 suspendido por el administrador', 'monto_fijo', 50, 300, $vigenteDesde, $vigenteHasta, null, 1, false, null],
        ];

        $filas = [];

        foreach ($cupones as [$codigo, $descripcion, $tipo, $valor, $minimo, $desde, $hasta, $usosMaximos, $usosPorCliente, $activo, $codigoTienda]) {
            $filas[] = [
                'tienda_id' => $codigoTienda ? ($tiendas[$codigoTienda] ?? null) : null,
                'codigo' => $codigo,
                'descripcion' => $descripcion,
                'tipo_descuento' => $tipo,
                'valor' => $valor,
                'monto_minimo' => $minimo,
                'fecha_inicio' => $desde,
                'fecha_fin' => $hasta,
                'usos_maximos' => $usosMaximos,
                'usos_por_cliente' => $usosPorCliente,
                'activo' => $activo,
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ];
        }

        DB::table('cupones')->insert($filas);
    }
}
