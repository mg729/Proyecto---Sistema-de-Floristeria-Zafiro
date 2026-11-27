<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    protected $table = 'detalle_compras';

    protected $fillable = [
        'compra_insumo_id', 
        'insumo_id', 
        'cantidad', 
        'costo_unitario'
        ];

    protected $casts = [
        'cantidad' => 'decimal:2',
        'costo_unitario' => 'decimal:2',
    ];

    public function compra()
    {
        return $this->belongsTo(CompraInsumo::class, 'compra_insumo_id');
    }

    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'insumo_id');
    }

    public function lotes()
    {
        return $this->hasMany(LoteInsumo::class, 'detalle_compra_id');
    }
}
