<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Rol;
use App\Models\Tienda;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    private const CLAVE = 'Zafiro2026*';

    public function run(): void
    {
        $roles = Rol::pluck('id', 'slug');         
        $tiendas = Tienda::pluck('id', 'codigo');

        $this->crearEmpleado('Andrea', 'Martínez', 'admin@zafiro.hn', $roles['admin'], $tiendas['TGU01']);
        $this->crearEmpleado('Carlos', 'Mejía', 'florista@zafiro.hn', $roles['florista'], $tiendas['TGU01']);
        $this->crearEmpleado('José', 'Hernández', 'repartidor@zafiro.hn', $roles['repartidor'], $tiendas['TGU01']);
        $this->crearCliente('María', 'López', 'cliente@zafiro.hn', true, '08011995123456');
        $this->crearCliente('Luis', 'Rivera', 'inactivo@zafiro.hn', false, null);

        $otrosEmpleados = ['admin' => 1, 'florista' => 4, 'repartidor' => 2];
        $contador = 0;

        foreach ($otrosEmpleados as $slug => $cantidad) {
            for ($n = 0; $n < $cantidad; $n++) {
                $codigoTienda = $contador % 2 === 0 ? 'SPS01' : 'TGU01';
                $contador++;

                $this->crearEmpleado(
                    fake()->firstName(),
                    fake()->lastName(),
                    fake()->unique()->safeEmail(),
                    $roles[$slug],
                    $tiendas[$codigoTienda]
                );
            }
        }

        for ($n = 0; $n < 18; $n++) {
            $rtn = $n % 2 === 0 ? fake()->numerify('0801##########') : null;

            $this->crearCliente(fake()->firstName(), fake()->lastName(), fake()->unique()->safeEmail(), true, $rtn);
        }
    }

    private function crearUsuario(string $nombre, string $apellido, string $email, bool $activo): User
    {
        return User::create([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $email,
            'telefono' => fake()->numerify('9#######'),
            'password' => self::CLAVE,
            'activo' => $activo,
        ]);
    }

    private function crearEmpleado(string $nombre, string $apellido, string $email, int $rolId, int $tiendaId): void
    {
        $usuario = $this->crearUsuario($nombre, $apellido, $email, true);

        Empleado::create([
            'user_id' => $usuario->id,
            'rol_id' => $rolId,
            'tienda_id' => $tiendaId,
            'numero_identidad' => fake()->unique()->numerify('0801#########'),
            'fecha_contratacion' => fake()->dateTimeBetween('-3 years', '-1 month')->format('Y-m-d'),
            'activo' => true,
        ]);
    }

    private function crearCliente(string $nombre, string $apellido, string $email, bool $activo, ?string $rtn): void
    {
        $usuario = $this->crearUsuario($nombre, $apellido, $email, $activo);

        Cliente::create([
            'user_id' => $usuario->id,
            'rtn' => $rtn,
            'nombre_facturacion' => $rtn ? "$nombre $apellido" : null,
            'fecha_nacimiento' => fake()->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
        ]);
    }
}