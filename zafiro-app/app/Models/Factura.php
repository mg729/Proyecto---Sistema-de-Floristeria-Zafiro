<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Factura extends Model
{
    protected $table = 'facturas';

    protected $fillable = [
        'autorizacion_cai_id', 'pedido_id', 'numero_factura', 'correlativo', 'fecha_emision',
        'cliente_nombre', 'cliente_rtn', 'importe_exento', 'importe_gravado_15', 'descuento',
        'isv_15', 'total', 'total_en_letras', 'estado', 'motivo_anulacion', 'anulada_por', 'fecha_anulacion',
    ];

    protected function casts(): array
    {
        return [
            'fecha_emision' => 'datetime',
            'fecha_anulacion' => 'datetime',
            'importe_exento' => 'decimal:2',
            'importe_gravado_15' => 'decimal:2',
            'descuento' => 'decimal:2',
            'isv_15' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function autorizacionCai(): BelongsTo
    {
        return $this->belongsTo(AutorizacionCai::class, 'autorizacion_cai_id');
    }

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    public function anuladaPor(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'anulada_por');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(FacturaDetalle::class, 'factura_id');
    }
}