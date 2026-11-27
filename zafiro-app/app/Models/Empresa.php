<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empresa extends Model
{
    protected $table = 'empresas';

    protected $fillable = [
        'razon_social', 'nombre_comercial', 'rtn', 'correo',
        'telefono', 'direccion_fiscal', 'logo', 'activo',
    ];

    protected function casts(): array
    {
        return ['activo' => 'boolean'];
    }

    public function tiendas(): HasMany
    {
        return $this->hasMany(Tienda::class, 'empresa_id');
    }
}