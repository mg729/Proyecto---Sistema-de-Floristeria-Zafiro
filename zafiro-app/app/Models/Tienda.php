<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tienda extends Model
{
    use SoftDeletes;

    protected $table = 'tiendas';

    protected $fillable = [
        'empresa_id', 'municipio_id', 'codigo', 'nombre', 'codigo_establecimiento',
        'direccion', 'telefono', 'correo', 'latitud', 'longitud',
        'hora_apertura', 'hora_cierre', 'horas_preparacion_min', 'activo',
    ];

    protected function casts(): array
    {
        return [
            'latitud' => 'decimal:7',
            'longitud' => 'decimal:7',
            'activo' => 'boolean',
        ];
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }

    public function empleados(): HasMany
    {
        return $this->hasMany(Empleado::class, 'tienda_id');
    }

    public function puntosEmision(): HasMany
    {
        return $this->hasMany(PuntoEmision::class, 'tienda_id');
    }

    public function insumos(): BelongsToMany
    {
        return $this->belongsToMany(Insumo::class, 'inventario_tienda', 'tienda_id', 'insumo_id')
            ->withPivot('stock_actual', 'stock_minimo', 'ubicacion')
            ->withTimestamps();
    }

    public function zonas(): BelongsToMany
    {
        return $this->belongsToMany(Zona::class, 'tarifas_envio', 'tienda_id', 'zona_id')
            ->withPivot('costo', 'tiempo_estimado_min', 'activo')
            ->withTimestamps();
    }

    public function turnosEntrega(): BelongsToMany
    {
        return $this->belongsToMany(TurnoEntrega::class, 'tienda_turno', 'tienda_id', 'turno_entrega_id')
            ->withPivot('cupo_maximo', 'activo')
            ->withTimestamps();
    }
}