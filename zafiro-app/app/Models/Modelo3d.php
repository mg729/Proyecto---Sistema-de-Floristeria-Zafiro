<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modelo3d extends Model
{
    protected $table = 'modelos_3d';

    protected $fillable = ['nombre', 'tipo_geometria', 'clave_procedural', 'archivo', 'escala', 'altura_cm', 'miniatura', 'activo'];

    protected function casts(): array
    {
        return ['escala' => 'decimal:2', 'altura_cm' => 'decimal:2', 'activo' => 'boolean'];
    }
}
