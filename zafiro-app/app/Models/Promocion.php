<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
    protected $table = 'promociones';

    protected $fillable = ['tienda_id', 'ocasion_id', 'nombre', 'descripcion', 'tipo_descuento', 'valor', 'fecha_inicio', 'fecha_fin', 'activo'];

    protected function casts(): array
    {
        return [
            'valor' => 'decimal:2',
            'fecha_inicio' => 'datetime',
            'fecha_fin' => 'datetime',
            'activo' => 'boolean',
        ];
    }

    public function tienda()
    {
        return $this->belongsTo(Tienda::class, 'tienda_id');
    }

    public function ocasion()
    {
        return $this->belongsTo(Ocasion::class, 'ocasion_id');
    }

    public function arreglos()
    {
        return $this->belongsToMany(Arreglo::class, 'arreglo_promocion', 'promocion_id', 'arreglo_id');
    }
}
