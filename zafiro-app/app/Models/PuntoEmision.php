<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PuntoEmision extends Model
{
    protected $table = 'puntos_emision';

    protected $fillable = ['tienda_id', 'codigo', 'descripcion', 'activo'];

    protected function casts(): array
    {
        return ['activo' => 'boolean'];
    }

    public function tienda(): BelongsTo
    {
        return $this->belongsTo(Tienda::class, 'tienda_id');
    }

    public function autorizacionesCai(): HasMany
    {
        return $this->hasMany(AutorizacionCai::class, 'punto_emision_id');
    }
}