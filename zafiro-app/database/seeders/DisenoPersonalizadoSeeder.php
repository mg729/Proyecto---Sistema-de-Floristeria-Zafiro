<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\DisenoComponente;
use App\Models\DisenoPersonalizado;
use App\Models\Insumo;
use App\Models\Ocasion;
use App\Models\Tienda;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DisenoPersonalizadoSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = DB::table('categorias_insumo')->pluck('id', 'slug'); 

        $porCategoria = fn (string $slug) => Insumo::where('categoria_insumo_id', $categorias[$slug] ?? 0)
            ->where('activo', true)
            ->orderBy('id')
            ->get()
            ->values();

        $bases = $porCategoria('bases');
        $flores = $porCategoria('flores');
        $follajes = $porCategoria('follajes');
        $complementos = $porCategoria('complementos');
        $pool = $flores->concat($follajes)->concat($complementos)->values();

        $tiendas = Tienda::whereIn('codigo', ['TGU01', 'SPS01'])->orderBy('codigo')->pluck('id')->all();

        if ($bases->isEmpty() || $pool->isEmpty() || empty($tiendas)) {
            $this->command->warn('DisenoPersonalizadoSeeder: faltan bases, componentes o tiendas. Se salta.');
            return;
        }
        $usuario = User::where('email', 'cliente@zafiro.hn')->firstOrFail();
        $principal = Cliente::where('user_id', $usuario->id)->firstOrFail();

        $otros = Cliente::where('id', '!=', $principal->id)->orderBy('id')->pluck('id')->all();
        if (empty($otros)) {
            $otros = [$principal->id];
        }

        $ocasiones = Ocasion::orderBy('id')->pluck('id')->all();

        $nombres = [
            'Ramo romántico personalizado', 'Caja ejecutiva Zafiro', 'Detalle de cumpleaños en pastel',
            'Arreglo primaveral mixto', 'Sorpresa de aniversario', 'Centro de mesa elegante',
            'Bouquet silvestre', 'Canasta dulce amanecer', 'Jarrón clásico de rosas',
            'Mi arreglo de graduación', 'Regalo para mamá', 'Flores para mi novia',
            'Caja de girasoles', 'Ramo de gerberas', 'Arreglo de agradecimiento',
            'Centro de mesa familiar', 'Ramo blanco minimalista', 'Caja rosa pastel',
            'Mi diseño favorito', 'Bouquet de temporada',
        ];

        $tomar = fn ($lista, int $n) => $lista->isEmpty() ? null : $lista[$n % $lista->count()];
        $ahora = now();

        foreach ($nombres as $i => $nombre) {
            $base = $bases[$i % $bases->count()];
            $cuantos = 3 + ($i % 3);

            $seleccion = [];
            foreach ([
                $tomar($flores, $i),
                $tomar($flores, $i + 1),
                $tomar($follajes, $i),
                $tomar($complementos, $i),
                $tomar($pool, $i * 2 + 3),
            ] as $insumo) {
                if ($insumo && count($seleccion) < $cuantos) {
                    $seleccion[$insumo->id] ??= $insumo;
                }
            }
            for ($k = 0; count($seleccion) < $cuantos && $k < $pool->count(); $k++) {
                $insumo = $pool[($i + $k) % $pool->count()];
                $seleccion[$insumo->id] ??= $insumo;
            }
            $componentes = [];
            foreach ($seleccion as $insumo) {
                $cantidad = match ((int) $insumo->categoria_insumo_id) {
                    (int) ($categorias['flores'] ?? 0) => 3 + ($i % 4),
                    (int) ($categorias['follajes'] ?? 0) => 1 + ($i % 3),
                    default => 1,
                };
                if ($insumo->max_por_diseno) {
                    $cantidad = max(1, min($cantidad, (int) $insumo->max_por_diseno));
                }
                $componentes[] = ['insumo_id' => $insumo->id, 'cantidad' => $cantidad];
            }

            $diseno = DisenoPersonalizado::create([
                'uuid' => (string) Str::uuid(),
                'cliente_id' => $i < 2 ? $principal->id : $otros[($i - 2) % count($otros)],
                'tienda_id' => $tiendas[$i % count($tiendas)],
                'base_insumo_id' => $base->id,
                'ocasion_id' => ($i % 2 === 0 && $ocasiones) ? $ocasiones[$i % count($ocasiones)] : null,
                'nombre' => $nombre,
                'configuracion_3d' => [
                    'version' => 1,
                    'base_insumo_id' => $base->id,
                    'componentes' => $componentes,
                ],
                'imagen_previa' => null,
            ]);

            DisenoComponente::insert(array_map(
                fn (array $c) => $c + [
                    'diseno_personalizado_id' => $diseno->id,
                    'created_at' => $ahora,
                    'updated_at' => $ahora,
                ],
                $componentes
            ));
        }
    }
}