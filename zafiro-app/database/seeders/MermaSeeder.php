<?php

namespace Database\Seeders;

use App\Models\Empleado;
use App\Models\LoteInsumo;
use App\Models\Merma;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class MermaSeeder extends Seeder
{
    public function run(): void
    {
        $empleadosPorTienda = Empleado::where('activo', true)
            ->whereNotNull('tienda_id')
            ->get()
            ->groupBy('tienda_id');

       
        $lotes = LoteInsumo::where('cantidad_disponible', '>=', 10)
            ->orderBy('id')
            ->get()
            ->filter(fn ($lote) => $empleadosPorTienda->has($lote->tienda_id))
            ->values()
            ->all();

        if (empty($lotes)) {
            $this->command->warn('MermaSeeder: no hay lotes con existencia o empleados por tienda. Se salta.');
            return;
        }

        $motivos = [
            'marchita' => 'Flores marchitas por calor en la bodega.',
            'danada' => 'Tallos dañados durante el manejo.',
            'vencida' => 'Lote con la fecha de vencimiento cumplida.',
            'sobrante_temporada' => 'Sobrante de temporada sin salida.',
            'otro' => 'Merma registrada en el conteo de inventario.',
        ];
        $claves = array_keys($motivos);

        $ahora = now();
        $usado = [];  
        $filas = [];
        $intentos = 0;

        while (count($filas) < 20 && $intentos < 200) {
            $lote = $lotes[$intentos % count($lotes)];
            $intentos++;

            $n = count($filas);
            $cantidad = (($n * 2) % 5) + 1;  
            $yaUsado = $usado[$lote->id] ?? 0;

            
            if ($yaUsado + $cantidad > (float) $lote->cantidad_disponible) {
                continue;
            }

           
            $empleados = $empleadosPorTienda->get($lote->tienda_id)->values();
            $empleado = $empleados->get($n % $empleados->count());

            
            $fecha = now()->subDays(1 + ($n % 10))->setTime(9, 0);
            $ingreso = Carbon::parse($lote->fecha_ingreso);
            if ($fecha->lessThan($ingreso)) {
                $fecha = $ingreso->copy()->addHours(2);
            }
            if ($fecha->greaterThan(now())) {
                $fecha = now();
            }

            $motivo = $claves[$n % count($claves)];  

            $filas[] = [
                'lote_insumo_id' => $lote->id,
                'empleado_id' => $empleado->id,
                'cantidad' => $cantidad,
                'motivo' => $motivo,
                'observacion' => $motivos[$motivo],
                'fecha' => $fecha,
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ];

            $usado[$lote->id] = $yaUsado + $cantidad;
        }

        if (count($filas) < 20) {
            $this->command->warn('MermaSeeder: solo se pudieron crear ' . count($filas) . ' de 20 mermas (faltan lotes con existencia).');
        }

        if (! empty($filas)) {
            Merma::insert($filas);   
        }
    }
}