<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoPedido extends Model
{
    protected $table = 'estados_pedido';

    protected $fillable = ['nombre', 'slug', 'orden', 'color_hex', 'es_final'];

    protected $casts = [
        'es_final' => 'boolean',
    ];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'estado_pedido_id');
    }

    public function historial()
    {
        return $this->hasMany(HistorialEstadoPedido::class, 'estado_pedido_id');
    }
}
