<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoteInsumo extends Model
{
    protected $table = 'lotes_insumo';

    protected $fillable = [
        'tienda_id', 
        'insumo_id', 
        'detalle_compra_id', 
        'codigo_lote', 
        'cantidad_inicial',
        'cantidad_disponible', 
        'costo_unitario', 
        'fecha_ingreso', 
        'fecha_vencimiento',
    ];

    protected $casts = [
        'cantidad_inicial' => 'decimal:2',
        'cantidad_disponible' => 'decimal:2',
        'costo_unitario' => 'decimal:2',
        'fecha_ingreso' => 'datetime',
        'fecha_vencimiento' => 'date',
    ];

    public function tienda()
    {
        return $this->belongsTo(Tienda::class, 'tienda_id');
    }

    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'insumo_id');
    }

    public function detalleCompra()
    {
        return $this->belongsTo(DetalleCompra::class, 'detalle_compra_id');
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoInventario::class, 'lote_insumo_id');
    }

    public function mermas()
    {
        return $this->hasMany(Merma::class, 'lote_insumo_id');
    }
}
