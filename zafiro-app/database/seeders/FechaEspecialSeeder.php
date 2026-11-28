<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\FechaEspecial;
use App\Models\Ocasion;
use App\Models\User;
use Illuminate\Database\Seeder;

class FechaEspecialSeeder extends Seeder
{
    public function run(): void
    {
        $usuario = User::where('email', 'cliente@zafiro.hn')->firstOrFail();
        $principal = Cliente::where('user_id', $usuario->id)->firstOrFail();

        $otros = Cliente::where('id', '!=', $principal->id)->pluck('id')->all();
        if (empty($otros)) {
            $otros = [$principal->id];
        }

        $ocasiones = Ocasion::pluck('id')->all();
        $ocasion = fn (int $i) => $ocasiones ? $ocasiones[$i % count($ocasiones)] : null;

        $ahora = now();
        $filas = [];

        $delPrincipal = [
            ['Aniversario de bodas', 'Daniela Reyes', now()->addDays(6), true, 3, 'Prefiere rosas rojas y tarjeta personalizada.'],
            ['Cumpleaños de mamá', 'Rosa Elena Martínez', now()->addMonths(2), true, 5, 'Arreglo en caja sombrerera.'],
            ['Graduación', 'Andrea Flores', now()->addMonths(5), false, 7, null],
        ];

        foreach ($delPrincipal as $i => [$titulo, $persona, $fecha, $anual, $anticipacion, $notas]) {
            $filas[] = [
                'cliente_id' => $principal->id,
                'ocasion_id' => $ocasion($i),
                'titulo' => $titulo,
                'nombre_persona' => $persona,
                'fecha' => $fecha->toDateString(),
                'es_anual' => $anual,
                'dias_anticipacion' => $anticipacion,
                'notas' => $notas,
                'activo' => true,
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ];
        }

        $deOtros = [
            ['Cumpleaños de mi esposa', 'Karla Molina', 3, true, 3],
            ['Aniversario de novios', 'Luis Pineda', 8, true, 5],
            ['Cumpleaños de papá', 'José Ramón Cruz', 11, true, 5],
            ['Cumpleaños de mi hermana', 'Sofía Castellanos', 14, true, 7],
            ['Cumpleaños de mi mejor amiga', 'Gabriela Ordóñez', 21, true, 3],
            ['Aniversario de bodas', 'Marlon Zelaya', 35, true, 7],
            ['Cumpleaños de abuela', 'María Luisa Banegas', 48, true, 5],
            ['Graduación universitaria', 'Kevin Alvarado', 60, false, 10],
            ['Cumpleaños de mi pareja', 'Daniel Cálix', 75, true, 3],
            ['Aniversario laboral', 'Wendy Herrera', 90, true, 5],
            ['Cumpleaños de mi suegra', 'Teresa Mejía', 110, true, 7],
            ['Cumpleaños de mi hijo', 'Mateo Rivera', 130, true, 5],
            ['Aniversario de novios', 'Fernanda Paz', 150, true, 3],
            ['Cumpleaños de mi tío', 'Héctor Maradiaga', 170, true, 7],
            ['Baby shower', 'Lucía Aguilar', 190, false, 10],
            ['Cumpleaños de mi prima', 'Ximena Ávila', 210, true, 5],
            ['Aniversario de bodas', 'Ricardo Núñez', 230, true, 7],
            ['Cumpleaños de mi jefa', 'Patricia Interiano', 250, true, 3],
            ['Cumpleaños de mi hermano', 'Oscar Padilla', 270, true, 5],
            ['Cumpleaños de mi amiga', 'Melissa Funes', 290, true, 5],
            ['Aniversario de graduación', 'Allan Torres', 310, true, 7],
            ['Cumpleaños de mi madrina', 'Dilcia Suazo', 330, true, 3],
        ];

        foreach ($deOtros as $i => [$titulo, $persona, $dias, $anual, $anticipacion]) {
            $filas[] = [
                'cliente_id' => $otros[$i % count($otros)],
                'ocasion_id' => $ocasion($i + 3),
                'titulo' => $titulo,
                'nombre_persona' => $persona,
                'fecha' => now()->addDays($dias)->toDateString(),
                'es_anual' => $anual,
                'dias_anticipacion' => $anticipacion,
                'notas' => null,
                'activo' => true,
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ];
        }

        FechaEspecial::insert($filas);
    }
}