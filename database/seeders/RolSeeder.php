<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['Nombre' => 'admin', 'Descripcion' => 'Administrador del sistema'],
            ['Nombre' => 'usuario', 'Descripcion' => 'Usuario estándar'],
            ['Nombre' => 'creadorServicio', 'Descripcion' => 'Creador de servicios y Usuario estándar'],
        ];

        foreach ($roles as $rol) {
            Rol::updateOrCreate(['Nombre' => $rol['Nombre']], $rol);
        }
    }
}
