<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = ['user_id', 'rtn', 'nombre_facturacion', 'fecha_nacimiento'];

    protected function casts(): array
    {
        return ['fecha_nacimiento' => 'date'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function direcciones(): HasMany
    {
        return $this->hasMany(Direccion::class, 'cliente_id');
    }
}