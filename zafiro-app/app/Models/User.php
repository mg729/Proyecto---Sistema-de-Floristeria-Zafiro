<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'telefono',
        'foto_perfil',
        'password',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean',
        ];
    }

    public function cliente(): HasOne
    {
        return $this->hasOne(Cliente::class, 'user_id');
    }

    public function empleado(): HasOne
    {
        return $this->hasOne(Empleado::class, 'user_id');
    }

    public function rolSlug(): ?string
    {
        return $this->empleado?->rol?->slug;
    }

    public function esCliente(): bool
    {
        return $this->cliente !== null;
    }
}
