<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Insumo extends Model
{
    use SoftDeletes;

    protected $table = 'insumos';

    protected $fillable = [
        'categoria_insumo_id', 
        'unidad_medida_id', 
        'modelo_3d_id', 
        'sku', 
        'nombre',
        'descripcion', 
        'color_nombre', 
        'color_hex', 
        'precio_componente', 
        'imagen',
        'visible_en_creador', 
        'max_por_diseno', 
        'activo',
    ];

    protected $casts = [
        'precio_componente' => 'decimal:2',
        'visible_en_creador' => 'boolean',
        'activo' => 'boolean',
    ];

    public function categoria()
    {
        return $this->belongsTo(CategoriaInsumo::class, 'categoria_insumo_id');
    }

    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedida::class, 'unidad_medida_id');
    }

    public function modelo3d()
    {
        return $this->belongsTo(Modelo3d::class, 'modelo_3d_id');
    }

    public function detalleBase()
    {
        return $this->hasOne(DetalleBase::class, 'insumo_id');
    }

    public function detalleFlor()
    {
        return $this->hasOne(DetalleFlor::class, 'insumo_id');
    }

    public function inventarios()
    {
        return $this->hasMany(InventarioTienda::class, 'insumo_id');
    }

    public function tiendas()
    {
        return $this->belongsToMany(Tienda::class, 'inventario_tienda', 'insumo_id', 'tienda_id')
            ->withPivot('stock_actual', 'stock_minimo', 'ubicacion')
            ->withTimestamps();
    }

    public function variantes()
    {
        return $this->belongsToMany(VarianteArreglo::class, 'receta_items', 'insumo_id', 'variante_arreglo_id')
            ->withPivot('cantidad')
            ->withTimestamps();
    }

    public function lotes()
    {
        return $this->hasMany(LoteInsumo::class, 'insumo_id');
    }

    public function detallesCompra()
    {
        return $this->hasMany(DetalleCompra::class, 'insumo_id');
    }
}