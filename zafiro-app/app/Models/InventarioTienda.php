<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarioTienda extends Model
{
    protected $table = 'inventario_tienda';

    protected $fillable = [
    'tienda_id', 
    'insumo_id', 
    'stock_actual', 
    'stock_minimo', 
    'ubicacion'
    ];

    protected $casts = [
        'stock_actual'  => 'decimal:2',
        'stock_minimo'  => 'decimal:2',
    ];

    public function tienda()
    {
        return $this->belongsTo(Tienda::class, 'tienda_id');
    }

    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'insumo_id');
    }
}
