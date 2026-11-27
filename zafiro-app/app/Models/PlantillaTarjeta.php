<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantillaTarjeta extends Model
{
    protected $table = 'plantillas_tarjeta';

    protected $fillable = ['ocasion_id', 'nombre', 'imagen_fondo', 'fuente', 'color_texto', 'activo'];

    protected function casts(): array
    {
        return ['activo' => 'boolean'];
    }

    public function ocasion()
    {
        return $this->belongsTo(Ocasion::class, 'ocasion_id');
    }
}
