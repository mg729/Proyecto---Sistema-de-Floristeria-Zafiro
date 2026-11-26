<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoInventario extends Model
{
    protected $table = 'movimientos_inventario';

    // La tabla solo tiene created_at
    const UPDATED_AT = null;

    protected $fillable = [
        'lote_insumo_id', 
        'empleado_id', 
        'tipo', 
        'cantidad', 
        'pedido_item_id', 
        'merma_id', 
        'motivo',
    ];

    protected $casts = [
        'cantidad' => 'decimal:2',
    ];

    public function lote()
    {
        return $this->belongsTo(LoteInsumo::class, 'lote_insumo_id');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    public function pedidoItem()
    {
        return $this->belongsTo(PedidoItem::class, 'pedido_item_id');
    }

    public function merma()
    {
        return $this->belongsTo(Merma::class, 'merma_id');
    }
}
