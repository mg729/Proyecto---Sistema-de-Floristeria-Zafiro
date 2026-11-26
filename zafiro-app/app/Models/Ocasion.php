<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ocasion extends Model
{
    protected $table = 'ocasiones';

    protected $fillable = [
                    'nombre',
                    'descripcion',
                    'mes_inicio',
                    'mes_fin',
                    'color_hex',
                    'orden',
                    'activo',
    ];
}
