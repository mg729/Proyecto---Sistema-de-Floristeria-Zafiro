<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompraInsumo extends Model
{
    protected $table = 'compras_insumo';

    protected $fillable = [
        'tienda_id', 
        'proveedor_id', 
        'empleado_id', 
        'numero_documento', 
        'fecha_compra',
        'subtotal', 
        'isv', 
        'total', 
        'estado', 
        'observaciones',
    ];

    protected $casts = [
        'fecha_compra' => 'date',
        'subtotal' => 'decimal:2',
        'isv' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function tienda()
    {
        return $this->belongsTo(Tienda::class, 'tienda_id');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleCompra::class, 'compra_insumo_id');
    }
}
