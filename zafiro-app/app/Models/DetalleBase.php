<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleBase extends Model
{
    protected $table = 'detalles_base';

    //La llave primaria es insumo_id (relacion 1:1 con insumos), no un id propio
    protected $primaryKey = 'insumo_id';

    public $incrementing = false;

    protected $fillable = [
        'insumo_id', 
        'formato_arreglo_id', 
        'material', 
        'capacidad_min_tallos',
        'capacidad_max_tallos', 
        'diametro_apertura_cm', 
        'altura_cm', 
        'cargo_armado',
    ];

    protected $casts = [
        'diametro_apertura_cm' => 'decimal:2',
        'altura_cm' => 'decimal:2',
        'cargo_armado' => 'decimal:2',
    ];

    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'insumo_id');
    }

    public function formatoArreglo()
    {
        return $this->belongsTo(FormatoArreglo::class, 'formato_arreglo_id');
    }
}
