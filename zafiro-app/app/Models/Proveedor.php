<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';

    protected $fillable = [
        'nombre',
            'rtn',
            'contacto',
          'telefono',
           'correo',
            'direccion',
           'activo',


    ];
}
