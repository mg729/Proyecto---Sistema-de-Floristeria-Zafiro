<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FechaEspecial extends Model
{
    protected $table = 'fechas_especiales';

    protected $fillable = ['cliente_id', 'ocasion_id', 'titulo', 'nombre_persona', 'fecha', 'es_anual', 'dias_anticipacion', 'notas', 'activo'];

    protected function casts(): array
    {
        return ['fecha' => 'date', 'es_anual' => 'boolean', 'activo' => 'boolean'];
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function ocasion()
    {
        return $this->belongsTo(Ocasion::class, 'ocasion_id');
    }
}