<?php

namespace Database\Seeders;

use App\Models\Departamento;
use Illuminate\Database\Seeder;

class DepartamentoSeeder extends Seeder
{
    public function run(): void
    {
        $departamentos = [
            '01' => 'Atlántida', '02' => 'Colón', '03' => 'Comayagua', '04' => 'Copán',
            '05' => 'Cortés', '06' => 'Choluteca', '07' => 'El Paraíso', '08' => 'Francisco Morazán',
            '09' => 'Gracias a Dios', '10' => 'Intibucá', '11' => 'Islas de la Bahía', '12' => 'La Paz',
            '13' => 'Lempira', '14' => 'Ocotepeque', '15' => 'Olancho', '16' => 'Santa Bárbara',
            '17' => 'Valle', '18' => 'Yoro',
        ];

        foreach ($departamentos as $codigo => $nombre) {
            Departamento::create(['codigo' => $codigo, 'nombre' => $nombre]);
        }
    }
}