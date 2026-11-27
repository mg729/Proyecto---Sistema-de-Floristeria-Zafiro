<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialEstadoPedido extends Model
{
    protected $table = 'historial_estados_pedido';

    // La tabla solo tiene created_at
    const UPDATED_AT = null;

    protected $fillable = [
        'pedido_id', 
        'estado_pedido_id', 
        'user_id', 
        'comentario'
        ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoPedido::class, 'estado_pedido_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
