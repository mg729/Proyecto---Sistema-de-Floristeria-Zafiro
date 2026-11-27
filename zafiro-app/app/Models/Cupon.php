<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cupon extends Model
{
    protected $table = 'cupones';

    protected $fillable = ['tienda_id', 'codigo', 'descripcion', 'tipo_descuento', 'valor', 'monto_minimo', 'fecha_inicio', 'fecha_fin', 'usos_maximos', 'usos_por_cliente', 'activo'];

    protected function casts(): array
    {
        return [
            'valor' => 'decimal:2',
            'monto_minimo' => 'decimal:2',
            'fecha_inicio' => 'datetime',
            'fecha_fin' => 'datetime',
            'activo' => 'boolean',
        ];
    }
}