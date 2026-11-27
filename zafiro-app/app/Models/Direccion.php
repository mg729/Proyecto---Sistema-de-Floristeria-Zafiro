<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Direccion extends Model
{
    use SoftDeletes;

    protected $table = 'direcciones';

    protected $fillable = [
        'cliente_id', 'zona_id', 'alias', 'nombre_contacto', 'telefono_contacto',
        'direccion_exacta', 'referencias', 'es_principal',
    ];

    protected function casts(): array
    {
        return ['es_principal' => 'boolean'];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function zona(): BelongsTo
    {
        return $this->belongsTo(Zona::class, 'zona_id');
    }
}