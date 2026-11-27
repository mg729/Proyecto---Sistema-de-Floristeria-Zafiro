<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DisenoPersonalizado extends Model
{
    use SoftDeletes;

    protected $table = 'disenos_personalizados';

    protected $fillable = ['uuid', 'cliente_id', 'tienda_id', 'base_insumo_id', 'ocasion_id', 'nombre', 'configuracion_3d', 'imagen_previa'];

    protected function casts(): array
    {
        return ['configuracion_3d' => 'array'];
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function tienda()
    {
        return $this->belongsTo(Tienda::class, 'tienda_id');
    }

    public function base()
    {
        return $this->belongsTo(Insumo::class, 'base_insumo_id');
    }

    public function ocasion()
    {
        return $this->belongsTo(Ocasion::class, 'ocasion_id');
    }

    public function componentes()
    {
        return $this->hasMany(DisenoComponente::class, 'diseno_personalizado_id');
    }
}