<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PedidoSeeder extends Seeder
{
    private const ISV = 0.15;

    private const DISTRIBUCION = [
        'pendiente' => 2,
        'en_armado' => 3,
        'listo_para_entregar' => 3,
        'pagado' => 6,
        'en_ruta' => 4,
        'entregado' => 10,
        'cancelado' => 2,
    ];

    private const ARTICULOS = [1, 2, 2, 1, 3, 2, 1, 2, 3, 1];

    private const DIAS_ATRAS = [
        'en_armado' => [1, 1, 2],
        'listo_para_entregar' => [2, 1, 2],
        'pagado' => [4, 3, 3, 2, 2, 1],
        'en_ruta' => [2, 2, 1, 1],
        'entregado' => [58, 52, 47, 41, 36, 31, 26, 22, 18, 15],
        'cancelado' => [10, 6],
    ];

    private const CUPONES = [
        'pendiente:0' => 'BIENVENIDA10',
        'en_armado:2' => 'TEGUCIGALPA10',
        'listo_para_entregar:1' => 'FLORES15',
        'pagado:1' => 'AGOTADO20',
        'pagado:2' => 'ZAFIRO50',
        'pagado:3' => 'SANPEDRO10',
        'en_ruta:1' => 'AGOTADO20',
        'en_ruta:2' => 'PRIMAVERA8',
    ];

    private const RETIROS = ['pagado:4', 'entregado:3', 'entregado:7'];

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
        $estados = DB::table('estados_pedido')->pluck('id', 'slug')->all();
        $tiendas = DB::table('tiendas')->whereIn('codigo', ['TGU01', 'SPS01'])->pluck('id', 'codigo')->all();
        $turnos = DB::table('turnos_entrega')->orderBy('id')->get()->all();
        $cupos = DB::table('tienda_turno')->get()
            ->mapWithKeys(fn ($t) => [$t->tienda_id . '|' . $t->turno_entrega_id => (int) $t->cupo_maximo])
            ->all();

        $metodos = DB::table('metodos_pago')->pluck('id', 'slug')->all();
        $metodoTarjeta = $metodos['tarjeta'] ?? throw new \RuntimeException('Falta el método de pago "tarjeta".');
        $metodoTransferencia = $metodos['transferencia'] ?? throw new \RuntimeException('Falta el método de pago "transferencia".');
        $metodoEfectivo = $metodos['efectivo-tienda'] ?? $metodos['efectivo'] ?? throw new \RuntimeException('Falta el método de pago en efectivo.');

        $personal = [];
        $empleados = DB::table('empleados as e')
            ->join('roles as r', 'r.id', '=', 'e.rol_id')
            ->where('e.activo', true)
            ->whereNull('e.deleted_at')
            ->orderBy('e.id')
            ->get(['e.id', 'e.tienda_id', 'r.slug as rol']);
        foreach ($empleados as $e) {
            $personal[$e->rol][$e->tienda_id][] = $e->id;
        }
        $contador = [];
        $tomar = function (string $rol, int $tiendaId, ?int $n = null) use ($personal, &$contador): int {
            $lista = $personal[$rol][$tiendaId] ?? throw new \RuntimeException("No hay empleados con rol {$rol} en la tienda {$tiendaId}.");
            $n ??= $contador[$rol][$tiendaId] = ($contador[$rol][$tiendaId] ?? -1) + 1;
            return $lista[$n % count($lista)];
        };

        $usuarioPrincipal = DB::table('users')->where('email', 'cliente@zafiro.hn')->value('id');
        $activos = DB::table('clientes as c')
            ->join('users as u', 'u.id', '=', 'c.user_id')
            ->where('u.activo', true)
            ->orderBy('c.id')
            ->get(['c.id', 'c.user_id', 'u.nombre', 'u.apellido', 'u.telefono']);
        $principal = $activos->first(fn ($c) => $c->user_id == $usuarioPrincipal)
            ?? throw new \RuntimeException('No existe cliente@zafiro.hn.');
        $otros = $activos->filter(fn ($c) => $c->user_id != $usuarioPrincipal)->values();

        $direcciones = DB::table('direcciones')->whereNull('deleted_at')
            ->orderByDesc('es_principal')->orderBy('id')->get()->groupBy('cliente_id');

        $tarifas = [];
        foreach (DB::table('tarifas_envio')->where('activo', true)->orderBy('zona_id')->get() as $t) {
            $tarifas[$t->tienda_id][$t->zona_id] = (float) $t->costo;
        }

        $disenos = DB::table('disenos_personalizados')->whereNull('deleted_at')->orderBy('id')
            ->get(['id', 'cliente_id', 'tienda_id', 'nombre', 'base_insumo_id']);
        $precioDiseno = function (object $d): float {
            $base = (float) DB::table('insumos')->where('id', $d->base_insumo_id)->value('precio_componente');
            $componentes = (float) DB::table('diseno_componentes as dc')
                ->join('insumos as i', 'i.id', '=', 'dc.insumo_id')
                ->where('dc.diseno_personalizado_id', $d->id)
                ->sum(DB::raw('dc.cantidad * i.precio_componente'));
            return round($base + $componentes, 2);
        };

        $variantes = $this->variantes();
        $plantillas = DB::table('plantillas_tarjeta')->where('activo', true)->orderBy('id')->pluck('id')->all();

        $promociones = DB::table('arreglo_promocion as ap')
            ->join('promociones as p', 'p.id', '=', 'ap.promocion_id')
            ->where('p.activo', true)
            ->get(['ap.arreglo_id', 'p.id', 'p.tienda_id', 'p.tipo_descuento', 'p.valor', 'p.fecha_inicio', 'p.fecha_fin'])
            ->groupBy('arreglo_id');

        $cupones = DB::table('cupones')->whereIn('codigo', array_unique(array_values(self::CUPONES)))->get()->keyBy('codigo');

        $usosCupon = [];
        $usosClienteCupon = [];
        $ocupados = [];

        $reservarCupo = function (int $tiendaId, int $turnoId, Carbon $fecha) use (&$ocupados, $cupos): Carbon {
            $limite = $cupos[$tiendaId . '|' . $turnoId] ?? 0;
            while (($ocupados[$tiendaId . '|' . $fecha->toDateString() . '|' . $turnoId] ?? 0) >= $limite) {
                $fecha = $fecha->copy()->addDay();
            }
            $clave = $tiendaId . '|' . $fecha->toDateString() . '|' . $turnoId;
            $ocupados[$clave] = ($ocupados[$clave] ?? 0) + 1;
            return $fecha;
        };

        $registros = [];
        $k = 0;
        $kOtros = 0;
        $articulosTotal = 0;

        foreach (self::DISTRIBUCION as $estado => $cantidad) {
            for ($i = 0; $i < $cantidad; $i++, $k++) {
                $clave = "$estado:$i";
                $codigoTienda = $i % 2 === 0 ? 'TGU01' : 'SPS01';
                $tiendaId = $tiendas[$codigoTienda] ?? throw new \RuntimeException("No existe la tienda $codigoTienda.");
                $esRetiro = in_array($clave, self::RETIROS, true);

                $esPrincipal = $i === 0 && in_array($estado, ['pendiente', 'en_armado', 'pagado', 'en_ruta', 'entregado'], true);
                $cliente = $esPrincipal ? $principal : $otros[$kOtros++ % $otros->count()];

                $creado = match ($estado) {
                    'pendiente' => now()->subHours(2 + $i * 4),
                    default => now()->subDays(self::DIAS_ATRAS[$estado][$i])->setTime(8 + $k % 5, ($k * 7) % 60, 0),
                };
                $turno = $turnos[$k % count($turnos)];
                $fechaBase = match ($estado) {
                    'pendiente' => now()->addDays(3 + $i),
                    'en_armado' => now()->addDays(1 + $i),
                    'listo_para_entregar' => now()->addDays(1 + intdiv($i + 1, 2)),
                    'pagado' => now()->addDays(1 + $i % 3),
                    'en_ruta' => now(),
                    default => $creado->copy()->addDays(2),
                };
                $fechaEntrega = $reservarCupo($tiendaId, $turno->id, $fechaBase->copy()->startOfDay());

                $cuantos = max(self::ARTICULOS[$k % 10], in_array($clave, ['en_armado:1', 'en_armado:2'], true) ? 2 : 1);
                $disenoCliente = $disenos->first(fn ($d) => $d->cliente_id == $cliente->id && $d->tienda_id == $tiendaId);
                $items = [];
                $usadas = [];

                for ($j = 0; $j < $cuantos; $j++, $articulosTotal++) {
                    $conDiseno = $j === 0 && $disenoCliente
                        && ($esPrincipal ? in_array($clave, ['pendiente:0', 'pagado:0'], true) : $k % 3 === 0);
                    $cant = $articulosTotal % 9 === 8 ? 2 : 1;

                    if ($conDiseno) {
                        $items[] = [
                            'variante_arreglo_id' => null,
                            'diseno_personalizado_id' => $disenoCliente->id,
                            'descripcion' => mb_substr($disenoCliente->nombre, 0, 200),
                            'cantidad' => 1,
                            'precio_unitario' => $precioDiseno($disenoCliente),
                            'promocion_id' => null,
                            'descuento' => 0.0,
                        ];
                    } else {
                        $intento = $k * 7 + $j * 5;
                        do {
                            $varianteId = $variantes[$intento % count($variantes)];
                            $intento++;
                        } while (in_array($varianteId, $usadas, true));
                        $usadas[] = $varianteId;

                        $v = DB::table('variantes_arreglo as v')
                            ->join('arreglos as a', 'a.id', '=', 'v.arreglo_id')
                            ->join('tamanos as t', 't.id', '=', 'v.tamano_id')
                            ->where('v.id', $varianteId)
                            ->first(['v.id', 'v.arreglo_id', 'v.precio', 'a.nombre as arreglo', 't.nombre as tamano']);

                        $precio = (float) $v->precio;
                        $bruto = round($precio * $cant, 2);

                        $promo = $promociones->get($v->arreglo_id)?->first(
                            fn ($p) => ($p->tienda_id === null || $p->tienda_id == $tiendaId)
                                && $creado->between(Carbon::parse($p->fecha_inicio), Carbon::parse($p->fecha_fin))
                        );
                        $descuento = 0.0;
                        if ($promo) {
                            $descuento = $promo->tipo_descuento === 'porcentaje'
                                ? round($bruto * (float) $promo->valor / 100, 2)
                                : min((float) $promo->valor, $bruto);
                        }

                        $items[] = [
                            'variante_arreglo_id' => $v->id,
                            'diseno_personalizado_id' => null,
                            'descripcion' => mb_substr($v->arreglo . ' · ' . $v->tamano, 0, 200),
                            'cantidad' => $cant,
                            'precio_unitario' => $precio,
                            'promocion_id' => $promo?->id,
                            'descuento' => $descuento,
                        ];
                    }
                }

                $subtotal = round(array_sum(array_map(
                    fn ($it) => $it['precio_unitario'] * $it['cantidad'] - $it['descuento'], $items
                )), 2);

                $cupon = null;
                $descuentoCupon = 0.0;
                $codigoCupon = self::CUPONES[$clave] ?? null;
                if ($codigoCupon && ($c = $cupones->get($codigoCupon))) {
                    $valido = $c->activo
                        && ($c->tienda_id === null || $c->tienda_id == $tiendaId)
                        && $subtotal >= (float) $c->monto_minimo
                        && $creado->between(Carbon::parse($c->fecha_inicio), Carbon::parse($c->fecha_fin))
                        && ($c->usos_maximos === null || ($usosCupon[$codigoCupon] ?? 0) < $c->usos_maximos)
                        && ($usosClienteCupon[$codigoCupon . '|' . $cliente->id] ?? 0) < $c->usos_por_cliente;

                    if ($valido) {
                        $cupon = $c;
                        $descuentoCupon = $c->tipo_descuento === 'porcentaje'
                            ? round($subtotal * (float) $c->valor / 100, 2)
                            : min((float) $c->valor, $subtotal);
                        $usosCupon[$codigoCupon] = ($usosCupon[$codigoCupon] ?? 0) + 1;
                        $usosClienteCupon[$codigoCupon . '|' . $cliente->id] = ($usosClienteCupon[$codigoCupon . '|' . $cliente->id] ?? 0) + 1;
                    }
                }

                $zonaId = null;
                $costoEnvio = 0.0;
                $contacto = ['nombre' => trim($cliente->nombre . ' ' . $cliente->apellido), 'telefono' => $cliente->telefono ?: fake()->numerify('9#######'), 'direccion' => null, 'referencias' => null];

                if (! $esRetiro) {
                    $zonasTienda = $tarifas[$tiendaId] ?? throw new \RuntimeException("La tienda $codigoTienda no tiene tarifas de envío.");
                    $dir = ($direcciones->get($cliente->id) ?? collect())->first(fn ($d) => isset($zonasTienda[$d->zona_id]));
                    if ($dir) {
                        $zonaId = $dir->zona_id;
                        $contacto = [
                            'nombre' => $dir->nombre_contacto ?: $contacto['nombre'],
                            'telefono' => $dir->telefono_contacto ?: $contacto['telefono'],
                            'direccion' => $dir->direccion_exacta,
                            'referencias' => $dir->referencias,
                        ];
                    } else {
                        $zonaIds = array_keys($zonasTienda);
                        $zonaId = $zonaIds[$k % count($zonaIds)];
                        $contacto['nombre'] = fake()->name();
                        $contacto['direccion'] = 'Bloque ' . fake()->numberBetween(1, 30) . ', casa ' . fake()->numberBetween(1, 200);
                        $contacto['referencias'] = 'Portón negro, frente a la pulpería';
                    }
                    $costoEnvio = $zonasTienda[$zonaId];
                }

                $isv = round(($subtotal - $descuentoCupon + $costoEnvio) * self::ISV, 2);
                $total = round($subtotal - $descuentoCupon + $costoEnvio + $isv, 2);

                foreach ($items as $j => &$it) {
                    $prep = $this->estadoPreparacion($estado, $i, $j);
                    $it['estado_preparacion'] = $prep;
                    $conFlorista = $prep !== 'pendiente' || ($clave === 'pendiente:0' && $j === 0);
                    $it['florista_id'] = $conFlorista
                        ? ($i === 0 && $codigoTienda === 'TGU01' ? $tomar('florista', $tiendaId, 0) : $tomar('florista', $tiendaId))
                        : null;
                    $it['fecha_inicio_armado'] = $prep === 'pendiente' ? null : $creado->copy()->addHours(2);
                    $it['fecha_fin_armado'] = $prep === 'listo' ? $creado->copy()->addHours(4 + $j) : null;
                    $conDedicatoria = $plantillas && $articulosTotal % 2 === 0;
                    $it['plantilla_tarjeta_id'] = $conDedicatoria ? $plantillas[($k + $j) % count($plantillas)] : null;
                    $it['dedicatoria_para'] = $conDedicatoria ? fake()->firstName() : null;
                    $it['dedicatoria_mensaje'] = $conDedicatoria ? self::MENSAJES[($k + $j) % count(self::MENSAJES)] : null;
                    $it['dedicatoria_firma'] = $conDedicatoria ? fake()->firstName() : null;
                }
                unset($it);

                $despachado = in_array($estado, ['en_ruta', 'entregado'], true) && ! $esRetiro;
                $repartidorId = $despachado
                    ? ($i === 0 && $codigoTienda === 'TGU01' ? $tomar('repartidor', $tiendaId, 0) : $tomar('repartidor', $tiendaId))
                    : null;
                $horaEntrega = $fechaEntrega->copy()->setTimeFromTimeString($turno->hora_inicio)->addMinutes(45);

                $entrega = [
                    'tipo' => $esRetiro ? 'retiro_tienda' : 'domicilio',
                    'zona_id' => $zonaId,
                    'turno_entrega_id' => $turno->id,
                    'fecha_entrega' => $fechaEntrega->toDateString(),
                    'nombre_destinatario' => mb_substr($contacto['nombre'], 0, 120),
                    'telefono_destinatario' => $contacto['telefono'],
                    'direccion_entrega' => $contacto['direccion'],
                    'referencias' => $contacto['referencias'],
                    'es_sorpresa' => ! $esRetiro && $k % 5 === 0,
                    'repartidor_id' => $repartidorId,
                    'fecha_hora_salida' => match (true) {
                        $estado === 'en_ruta' => now()->subHour(),
                        $estado === 'entregado' && ! $esRetiro => $horaEntrega->copy()->subHour(),
                        default => null,
                    },
                    'fecha_hora_entrega' => $estado === 'entregado' ? $horaEntrega : null,
                    'recibido_por' => $estado === 'entregado' ? mb_substr($contacto['nombre'], 0, 120) : null,
                ];

                $pagos = [];
                $metodoId = $esRetiro ? $metodoEfectivo : ($k % 2 === 0 ? $metodoTarjeta : $metodoTransferencia);
                if (in_array($estado, ['pagado', 'en_ruta', 'entregado'], true)) {
                    $pagos[] = $this->pago($metodoId, $metodoEfectivo, $metodoTarjeta, $total, 'aprobado', $creado->copy()->addHours(8), $k);
                } elseif ($clave === 'listo_para_entregar:0') {
                    $pagos[] = $this->pago($metodoTarjeta, $metodoEfectivo, $metodoTarjeta, $total, 'rechazado', now()->subHours(3), $k);
                } elseif ($clave === 'listo_para_entregar:1') {
                    $pagos[] = $this->pago($metodoTransferencia, $metodoEfectivo, $metodoTarjeta, $total, 'pendiente', null, $k);
                }

                $actualizado = match ($estado) {
                    'pendiente' => $creado->copy(),
                    'en_armado' => $creado->copy()->addHours(2),
                    'listo_para_entregar' => $creado->copy()->addHours(4 + $cuantos),
                    'pagado' => $creado->copy()->addHours(8),
                    'en_ruta' => now()->subHour(),
                    'entregado' => $horaEntrega->copy(),
                    'cancelado' => $creado->copy()->addDay(),
                };

                $registros[] = [
                    'creado' => $creado,
                    'actualizado' => $actualizado,
                    'tienda' => $codigoTienda,
                    'pedido' => [
                        'cliente_id' => $cliente->id,
                        'tienda_id' => $tiendaId,
                        'estado_pedido_id' => $estados[$estado] ?? throw new \RuntimeException("Falta el estado $estado."),
                        'cupon_id' => $cupon?->id,
                        'subtotal' => $subtotal,
                        'descuento_cupon' => $descuentoCupon,
                        'costo_envio' => $costoEnvio,
                        'isv' => $isv,
                        'total' => $total,
                        'notas_cliente' => $k % 4 === 0 ? 'Por favor entregar con cuidado, es una sorpresa.' : null,
                        'fecha_limite_pago' => $estado === 'listo_para_entregar' ? now()->addHours(24 + $i * 6) : null,
                        'motivo_cancelacion' => $estado === 'cancelado'
                            ? ($i === 0 ? 'El cliente pidió cancelar el pedido' : 'La fecha de entrega ya no era posible')
                            : null,
                    ],
                    'items' => $items,
                    'entrega' => $entrega,
                    'pagos' => $pagos,
                ];
            }
        }

        usort($registros, fn ($a, $b) => $a['creado']->timestamp <=> $b['creado']->timestamp);

        DB::transaction(function () use ($registros) {
            $secuencia = [];

            foreach ($registros as $r) {
                $secuencia[$r['tienda']] = ($secuencia[$r['tienda']] ?? 0) + 1;

                $pedidoId = DB::table('pedidos')->insertGetId($r['pedido'] + [
                        'codigo' => sprintf('PED-%s-%06d', $r['tienda'], $secuencia[$r['tienda']]),
                        'created_at' => $r['creado'],
                        'updated_at' => $r['actualizado'],
                    ]);

                foreach ($r['items'] as $item) {
                    DB::table('pedido_items')->insert($item + [
                            'pedido_id' => $pedidoId,
                            'created_at' => $r['creado'],
                            'updated_at' => $r['actualizado'],
                        ]);
                }

                DB::table('entregas')->insert($r['entrega'] + [
                        'pedido_id' => $pedidoId,
                        'created_at' => $r['creado'],
                        'updated_at' => $r['actualizado'],
                    ]);

                foreach ($r['pagos'] as $pago) {
                    DB::table('pagos')->insert($pago + [
                            'pedido_id' => $pedidoId,
                            'created_at' => $pago['fecha_pago'] ?? $r['creado'],
                            'updated_at' => $pago['fecha_pago'] ?? $r['creado'],
                        ]);
                }
            }
        });

        $agotado = DB::table('cupones')->where('codigo', 'AGOTADO20')->value('id');
        $usos = DB::table('pedidos')->where('cupon_id', $agotado)->count();
        $clientes = DB::table('pedidos')->where('cupon_id', $agotado)->distinct()->count('cliente_id');
        if ($agotado && ($usos !== 2 || $clientes !== 2)) {
            throw new \RuntimeException("AGOTADO20 debe estar en 2 pedidos de clientes distintos (hay $usos pedidos, $clientes clientes).");
        }
    }

    private function estadoPreparacion(string $estado, int $i, int $j): string
    {
        return match (true) {
            $estado === 'en_armado' && $i === 0 => 'en_armado',
            $estado === 'en_armado' && $i === 1 => $j === 0 ? 'en_armado' : 'listo',
            $estado === 'en_armado' => $j === 0 ? 'en_armado' : 'pendiente',
            in_array($estado, ['pendiente', 'cancelado'], true) => 'pendiente',
            default => 'listo',
        };
    }

    private function pago(int $metodoId, int $metodoEfectivo, int $metodoTarjeta, float $total, string $estado, ?Carbon $fecha, int $k): array
    {
        $esTarjeta = $metodoId === $metodoTarjeta;
        $esEfectivo = $metodoId === $metodoEfectivo;

        return [
            'metodo_pago_id' => $metodoId,
            'monto' => $total,
            'estado' => $estado,
            'referencia' => $esEfectivo ? null : ($esTarjeta ? 'AUT-' : 'TRF-') . str_pad((string) (100000 + $k * 137), 6, '0', STR_PAD_LEFT),
            'tarjeta_ultimos4' => $esTarjeta ? str_pad((string) (1000 + $k * 73 % 9000), 4, '0', STR_PAD_LEFT) : null,
            'tarjeta_marca' => $esTarjeta ? ($k % 4 === 0 ? 'Mastercard' : 'Visa') : null,
            'comprobante' => (! $esTarjeta && ! $esEfectivo) ? 'comprobantes/trf-' . (100000 + $k * 137) . '.jpg' : null,
            'fecha_pago' => $fecha,
        ];
    }

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
            throw new \RuntimeException('PedidoSeeder: no hay variantes de arreglo activas.');
        }

        return $ids;
    }
}
