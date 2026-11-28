<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarritoSeeder extends Seeder
{
    private const ARTICULOS = [2, 1, 2, 2, 1, 2, 1, 2, 2, 1];

    private const MENSAJES = [
        'Feliz cumpleaños, que Dios te bendiga siempre.',
        'Gracias por todo tu cariño y tu apoyo.',
        'Te quiero mucho, que tengas un día hermoso.',
        'Felicidades por tu logro, estoy muy orgulloso de ti.',
        'Pensando en ti en este día tan especial.',
        'Con todo mi amor, feliz aniversario.',
    ];

    public function run(): void
    {
        $ahora = now();

        $tiendas = DB::table('tiendas')->whereIn('codigo', ['TGU01', 'SPS01'])->pluck('id', 'codigo')->all();
        $tgu = $tiendas['TGU01'] ?? throw new \RuntimeException('No existe la tienda TGU01.');
        $sps = $tiendas['SPS01'] ?? throw new \RuntimeException('No existe la tienda SPS01.');

        $usuarioPrincipal = DB::table('users')->where('email', 'cliente@zafiro.hn')->value('id');

        $clientes = DB::table('clientes as c')
            ->join('users as u', 'u.id', '=', 'c.user_id')
            ->where('u.activo', true)
            ->orderBy('c.id')
            ->get(['c.id', 'c.user_id']);

        $disenos = DB::table('disenos_personalizados')
            ->whereNull('deleted_at')
            ->orderBy('id')
            ->get(['id', 'cliente_id', 'tienda_id'])
            ->groupBy('cliente_id');

        $variantes = $this->variantes();
        $plantillas = DB::table('plantillas_tarjeta')->where('activo', true)->orderBy('id')->pluck('id')->all();
        $cuponBienvenida = DB::table('cupones')->where('codigo', 'BIENVENIDA10')->value('id');

              Un carrito por cliente y tienda. cliente@ tiene uno en cada tienda
        $carritos = [];
        foreach ($clientes as $n => $cliente) {
            if ($cliente->user_id == $usuarioPrincipal) {
                $carritos[] = [$cliente->id, $tgu];
                $carritos[] = [$cliente->id, $sps];
                continue;
            }

                  Si el cliente tiene un diseño, su carrito es de la tienda de ese diseño
            $diseno = $disenos->get($cliente->id)?->first();
            $carritos[] = [$cliente->id, $diseno?->tienda_id ?? ($n % 2 === 0 ? $tgu : $sps)];
        }

        DB::transaction(function () use ($carritos, $disenos, $variantes, $plantillas, $cuponBienvenida, $ahora) {
            $articulosTotal = 0;

            foreach ($carritos as $n => [$clienteId, $tiendaId]) {
                $carritoId = DB::table('carritos')->insertGetId([
                    'cliente_id' => $clienteId,
                    'tienda_id' => $tiendaId,
                    'cupon_id' => ($n % 7 === 0) ? $cuponBienvenida : null,
                    'expira_en' => now()->addHours(4),         los carritos vencen a las 4 horas
                    'created_at' => $ahora->copy()->subMinutes(10 + $n * 3),
                    'updated_at' => $ahora,
                ]);

                      Diseño del cliente en esa tienda (si tiene)
                $diseno = $disenos->get($clienteId)?->firstWhere('tienda_id', $tiendaId);
                $usadas = [];

                for ($j = 0; $j < self::ARTICULOS[$n % 10]; $j++) {
                    $esDiseno = $j === 0 && $diseno && $n % 2 === 0;

                    $varianteId = null;
                    if (! $esDiseno) {
                              Variante distinta dentro del mismo carrito
                        $intento = $n * 5 + $j * 11;
                        do {
                            $varianteId = $variantes[$intento % count($variantes)];
                            $intento++;
                        } while (in_array($varianteId, $usadas, true));
                        $usadas[] = $varianteId;
                    }

                    $conDedicatoria = $articulosTotal % 2 === 0 && $plantillas;

                          Nunca variante y diseño en la misma fila
                    DB::table('carrito_items')->insert([
                        'carrito_id' => $carritoId,
                        'variante_arreglo_id' => $esDiseno ? null : $varianteId,
                        'diseno_personalizado_id' => $esDiseno ? $diseno->id : null,
                        'cantidad' => $articulosTotal % 9 === 8 ? 2 : 1,
                        'plantilla_tarjeta_id' => $conDedicatoria ? $plantillas[$articulosTotal % count($plantillas)] : null,
                        'dedicatoria_para' => $conDedicatoria ? fake()->firstName() : null,
                        'dedicatoria_mensaje' => $conDedicatoria ? self::MENSAJES[$articulosTotal % count(self::MENSAJES)] : null,
                        'dedicatoria_firma' => $conDedicatoria ? fake()->firstName() : null,
                        'created_at' => $ahora,
                        'updated_at' => $ahora,
                    ]);

                    $articulosTotal++;
                }
            }
        });
    }

          Ids de variantes activas; primero las que tienen receta (para que la Ola 5 consuma lotes)
    private function variantes(): array
    {
        $base = DB::table('variantes_arreglo as v')
            ->join('arreglos as a', 'a.id', '=', 'v.arreglo_id')
            ->where('v.activo', true)
            ->where('a.activo', true)
            ->whereNull('a.deleted_at')
            ->orderBy('v.id');

        $conReceta = (clone $base)
            ->whereExists(fn ($q) => $q->select(DB::raw(1))->from('receta_items as r')->whereColumn('r.variante_arreglo_id', 'v.id'))
            ->pluck('v.id')
            ->all();

        $ids = $conReceta ?: $base->pluck('v.id')->all();

        if (! $ids) {
            throw new \RuntimeException('CarritoSeeder: no hay variantes de arreglo activas.');
        }

        return $ids;
    }
}
