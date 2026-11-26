<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnidadMedida extends Model
{
    protected $table = 'unidades_medida';

    protected $fillable = ['nombre', 'abreviatura'];

    public function insumos()
    {
        return $this->hasMany(Insumo::class, 'unidad_medida_id');
    }
}
