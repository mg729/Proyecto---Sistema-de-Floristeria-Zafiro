<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empleado extends Model
{
    use SoftDeletes;

    protected $table = 'empleados';

    protected $fillable = [
        'user_id', 'rol_id', 'tienda_id', 'numero_identidad', 'fecha_contratacion', 'activo',
    ];

    protected function casts(): array
    {
        return [
            'fecha_contratacion' => 'date',
            'activo' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    public function tienda(): BelongsTo
    {
        return $this->belongsTo(Tienda::class, 'tienda_id');
    }

    public function facturasAnuladas(): HasMany
    {
        return $this->hasMany(Factura::class, 'anulada_por');
    }
}