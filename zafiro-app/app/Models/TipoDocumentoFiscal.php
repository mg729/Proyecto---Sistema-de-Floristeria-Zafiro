<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoDocumentoFiscal extends Model
{
    protected $table = 'tipos_documento_fiscal';

    protected $fillable = ['codigo', 'nombre'];

    public function autorizacionesCai(): HasMany
    {
        return $this->hasMany(AutorizacionCai::class, 'tipo_documento_fiscal_id');
    }
}