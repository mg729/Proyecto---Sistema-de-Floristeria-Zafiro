<?php

namespace Database\Seeders;

use App\Models\Arreglo;
use App\Models\Ocasion;
use App\Models\Promocion;
use Illuminate\Database\Seeder;

class PromocionSeeder extends Seeder
{
    public function run(): void
    {
        $ocasiones = Ocasion::orderBy('id')->pluck('id')->all();
        $arreglos = Arreglo::orderBy('id')->pluck('id')->all();

        if (empty($ocasiones) || empty($arreglos)) {
            $this->command->warn('PromocionSeeder: faltan ocasiones o arreglos. Se salta.');
            return;
        }

        $promociones = [
            ['Especial Amor y Amistad', 'Descuento para regalos de amor y amistad.', 'porcentaje', 15, 'vigente', 'ocasion'],
            ['Descuento de Cumpleaños', 'L 120 de descuento en arreglos de cumpleaños.', 'monto_fijo', 120, 'vigente', 'ocasion'],
            ['Semana de Aniversarios', 'Celebra tu aniversario con flores.', 'porcentaje', 10, 'vigente', 'ocasion'],
            ['Detalle de Graduación', 'L 100 de descuento en detalles de graduación.', 'monto_fijo', 100, 'vigente', 'ocasion'],
            ['Flores para Mamá', 'Descuento especial para el Día de la Madre.', 'porcentaje', 20, 'vigente', 'ocasion'],
            ['Selección Premium de Temporada', 'Arreglos premium con descuento.', 'porcentaje', 12, 'vigente', 'arreglos'],
            ['Bono Ramos Clásicos', 'L 85 de descuento en ramos clásicos.', 'monto_fijo', 85, 'vigente', 'arreglos'],
            ['Festival de Rosas Rojas', 'Descuento en arreglos con rosas rojas.', 'porcentaje', 18, 'vigente', 'arreglos'],
            ['Cajas Sombrereras en Oferta', 'L 150 de descuento en cajas sombrereras.', 'monto_fijo', 150, 'vigente', 'arreglos'],
            ['Ahorro en Centros de Mesa', 'Centros de mesa con descuento.', 'porcentaje', 10, 'vigente', 'arreglos'],
            ['Promo de Primavera', 'Campaña de primavera ya terminada.', 'porcentaje', 15, 'vencida', 'ocasion'],
            ['Especial Día del Padre', 'Campaña del Día del Padre ya terminada.', 'porcentaje', 10, 'vencida', 'ocasion'],
            ['Liquidación Fin de Mes', 'L 90 de descuento, ya terminada.', 'monto_fijo', 90, 'vencida', 'ocasion'],
            ['Combo Arreglos Ejecutivos', 'L 110 de descuento, ya terminada.', 'monto_fijo', 110, 'vencida', 'arreglos'],
            ['Flash Sale de Orquídeas', 'Oferta relámpago ya terminada.', 'porcentaje', 25, 'vencida', 'arreglos'],
            ['Adelanto de Navidad', 'Descuento que empieza pronto.', 'porcentaje', 12, 'futura', 'ocasion'],
            ['Especial Día del Maestro', 'L 80 de descuento próximamente.', 'monto_fijo', 80, 'futura', 'ocasion'],
            ['Oferta de Canastas', 'Canastas con descuento próximamente.', 'porcentaje', 14, 'futura', 'arreglos'],
            ['Jarrones de Lujo', 'L 130 de descuento próximamente.', 'monto_fijo', 130, 'futura', 'arreglos'],
            ['Lanzamiento Arreglos Deluxe', 'Descuento de lanzamiento.', 'porcentaje', 8, 'futura', 'arreglos'],
        ];

        $contadorOcasion = 0;
        $contadorArreglos = 0;

        foreach ($promociones as $i => [$nombre, $descripcion, $tipo, $valor, $estado, $destino]) {
            [$inicio, $fin] = match ($estado) {
                'vigente' => [now()->subDays(5 + ($i % 10)), now()->addDays(20 + (($i * 7) % 40))],
                'vencida' => [now()->subMonths(3)->subDays($i), now()->subDays(20 + $i)],
                'futura' => [now()->addDays(7 + ($i % 10)), now()->addDays(45 + ($i % 20))],
            };

            $porOcasion = $destino === 'ocasion';

            $promocion = Promocion::create([
                'tienda_id' => null,  
                'ocasion_id' => $porOcasion ? $ocasiones[$contadorOcasion++ % count($ocasiones)] : null,
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'tipo_descuento' => $tipo,
                'valor' => $valor,
                'fecha_inicio' => $inicio,
                'fecha_fin' => $fin,
                'activo' => true,
            ]);
            if (! $porOcasion) {
                $cuantos = $contadorArreglos % 2 === 0 ? 3 : 2;
                $ids = [];
                for ($j = 0; $j < $cuantos; $j++) {
                    $ids[] = $arreglos[($contadorArreglos * 3 + $j) % count($arreglos)];
                }
                $promocion->arreglos()->sync(array_values(array_unique($ids)));
                $contadorArreglos++;
            }
        }
    }
}