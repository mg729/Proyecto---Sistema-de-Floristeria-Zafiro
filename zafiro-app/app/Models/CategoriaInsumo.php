<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaInsumo extends Model
{
    protected $table = 'categorias_insumo';

    protected $fillable = ['nombre', 'slug', 'paso_creador', 'descripcion', 'orden'];

    public function insumos()
    {
        return $this->hasMany(Insumo::class, 'categoria_insumo_id');
    }
}
