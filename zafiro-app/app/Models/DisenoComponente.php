<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisenoComponente extends Model
{
    protected $table = 'diseno_componentes';

    protected $fillable = ['diseno_personalizado_id', 'insumo_id', 'cantidad'];

    protected function casts(): array
    {
        return ['cantidad' => 'decimal:2'];
    }

    public function diseno()
    {
        return $this->belongsTo(DisenoPersonalizado::class, 'diseno_personalizado_id');
    }

    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'insumo_id');
    }
}