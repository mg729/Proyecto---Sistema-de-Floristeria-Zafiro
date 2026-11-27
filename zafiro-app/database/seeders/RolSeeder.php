<?php

namespace Database\Seeders;

use App\Models\Rol;
use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['nombre' => 'Administrador', 'slug' => 'admin', 'descripcion' => 'Gestiona catálogos, inventario, empleados, ventas y reportes.'],
            ['nombre' => 'Florista', 'slug' => 'florista', 'descripcion' => 'Arma los arreglos asignados y actualiza su avance en el taller.'],
            ['nombre' => 'Repartidor', 'slug' => 'repartidor', 'descripcion' => 'Entrega los pedidos y registra la evidencia de entrega.'],
        ];

        foreach ($roles as $rol) {
            Rol::create($rol);
        }
    }
}