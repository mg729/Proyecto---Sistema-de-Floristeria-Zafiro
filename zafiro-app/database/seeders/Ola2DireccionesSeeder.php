<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Direccion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Ola2DireccionesSeeder extends Seeder
{
    public function run(): void
    {
        $zonas = DB::table('tarifas_envio')->where('activo', true)->distinct()->pluck('zona_id');

        if ($zonas->isEmpty()) {
            $this->command->warn('Ola2DireccionesSeeder: no hay zonas con tarifa, se omiten las direcciones.');
            return;
        }

        $otrosAlias = ['Oficina', 'Casa de mamá', 'Trabajo', 'Casa de la abuela'];

        foreach (Cliente::with('user')->get() as $indice => $cliente) {
            $this->crearDireccion($cliente->id, $zonas->random(), 'Casa', $cliente->user->nombre . ' ' . $cliente->user->apellido, true);

            if ($indice % 2 === 0) {
                $this->crearDireccion($cliente->id, $zonas->random(), fake()->randomElement($otrosAlias), fake()->name(), false);
            }
        }
    }

    private function crearDireccion(int $clienteId, int $zonaId, string $alias, string $contacto, bool $principal): void
    {
        Direccion::create([
            'cliente_id' => $clienteId,
            'zona_id' => $zonaId,
            'alias' => $alias,
            'nombre_contacto' => $contacto,
            'telefono_contacto' => fake()->numerify('9#######'),
            'direccion_exacta' => 'Bloque ' . fake()->numberBetween(1, 30) . ', casa ' . fake()->numberBetween(1, 200) . ', '
                . fake()->randomElement(['calle principal', '1.ª calle', '2.ª avenida', 'pasaje Los Pinos']),
            'referencias' => fake()->randomElement([
                'Portón negro, frente a la pulpería',
                'Casa de dos pisos color crema',
                'A la par de la iglesia',
                'Edificio con rótulo azul, segundo piso',
                null,
            ]),
            'es_principal' => $principal,
        ]);
    }
}

