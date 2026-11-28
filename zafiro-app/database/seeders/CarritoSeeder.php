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
        $tgu =$tiendas['TGU01'] ?? throw new \RuntimeException('No existe la tienda TGU01.');
        $sps =$tiendas['SPS01'] ?? throw new \RuntimeException('No existe la tienda SPS01.');

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

        $variantes =$this->variantes();
        $plantillas = DB::table('plantillas_tarjeta')->where('activo', true)->orderBy('id')->pluck('id')->all();$cuponBienvenida = DB::table('cupones')->where('codigo', 'BIENVENIDA10')->value('id');

        $carritos = [];
        foreach ($clientes as $n =>$cliente) {
            if ($cliente->user_id ==$usuarioPrincipal) {
                $carritos[] = [$cliente->id, $tgu];$carritos[] = [$cliente->id,$sps];
                continue;
            }

            $diseno = $disenos->get($cliente->id)?->first();
            $carritos[] = [$cliente->id, $diseno?->tienda_id ?? ($n % 2 === 0 ? $tgu :$sps)];
        }

        DB::transaction(function () use ($carritos, $disenos,$variantes, $plantillas,$cuponBienvenida, $ahora) {$articulosTotal = 0;

            foreach ($carritos as $n => [$clienteId, $tiendaId]) {$carritoId = DB::table('carritos')->insertGetId([
                    'cliente_id' => $clienteId,
                    'tienda_id' => $tiendaId,
                    'cupon_id' => ($n \% 7 === 0) ?$cuponBienvenida : null,
                    'expira_en' => now()->addHours(4),
                    'created_at' => $ahora->copy()->subMinutes(10 + $n * 3), 'updated_at' =>$ahora,
                ]);

                $diseno = $disenos->get($clienteId)?->firstWhere('tienda_id', $tiendaId);$usadas = [];

                for ($j = 0; $j < self::ARTICULOS[$n \% 10];$j++) {
                    $esDiseno =$j === 0 && $diseno &&$n % 2 === 0;

                    $varianteId = null;
                    if (! $esDiseno) {
                        $intento =$n * 5
