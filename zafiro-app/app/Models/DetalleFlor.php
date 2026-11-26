<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleFlor extends Model
{
    protected $table = 'detalles_flor';
    protected $primaryKey = 'insumo_id';
    public $incrementing = false;

    protected $fillable = [
        'insumo_id', 
        'nombre_cientifico', 
        'origen', 
        'vida_florero_dias', 
        'temporada', 
        'cuidados',
    ];

    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'insumo_id');
    }
}
