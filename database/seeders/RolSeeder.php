<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolSeeder extends Seeder
{
    public function run(): void
    {
        Rol::insert([
            ['Nombre' => 'admin', 'Descripcion' => 'Administrador del sistema'],
            ['Nombre' => 'usuario', 'Descripcion' => 'Usuario estándar'],
            ['Nombre' => 'creadorServicio', 'Descripcion' => 'Creador de servicios y Usuario estándar'],
        ]);
    }
}
