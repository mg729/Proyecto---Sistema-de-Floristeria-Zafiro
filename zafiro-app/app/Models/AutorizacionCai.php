<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AutorizacionCai extends Model
{
    protected $table = 'autorizaciones_cai';

    protected $fillable = [
        'punto_emision_id', 'tipo_documento_fiscal_id', 'cai', 'rango_inicial', 'rango_final',
        'ultimo_correlativo', 'fecha_autorizacion', 'fecha_limite_emision', 'activo',
    ];

    protected function casts(): array
    {
        return [
            'fecha_autorizacion' => 'date',
            'fecha_limite_emision' => 'date',
            'activo' => 'boolean',
        ];
    }

    public function puntoEmision(): BelongsTo
    {
        return $this->belongsTo(PuntoEmision::class, 'punto_emision_id');
    }

    public function tipoDocumentoFiscal(): BelongsTo
    {
        return $this->belongsTo(TipoDocumentoFiscal::class, 'tipo_documento_fiscal_id');
    }

    public function facturas(): HasMany
    {
        return $this->hasMany(Factura::class, 'autorizacion_cai_id');
    }
}