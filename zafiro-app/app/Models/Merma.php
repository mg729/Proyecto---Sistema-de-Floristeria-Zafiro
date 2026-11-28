<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Merma extends Model
{
    protected $table = 'mermas';

    protected $fillable = ['lote_insumo_id', 'empleado_id', 'cantidad', 'motivo', 'observacion', 'fecha'];

    protected function casts(): array
    {
        return ['cantidad' => 'decimal:2', 'fecha' => 'datetime'];
    }

    public function lote()
    {
        return $this->belongsTo(LoteInsumo::class, 'lote_insumo_id');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }
}