<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rol extends Model
{
    protected $table = 'roles';

    protected $fillable = ['nombre', 'slug', 'descripcion'];

    public function empleados(): HasMany
    {
        return $this->hasMany(Empleado::class, 'rol_id');
    }
}