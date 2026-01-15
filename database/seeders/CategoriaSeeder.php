<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        Categoria::insert([
            ['Nombre' => 'Limpieza', 'Descripcion' => 'Servicios de limpieza profesional', 'Color' => '#ff5733', 'Activa' => true],
            ['Nombre' => 'Mantenimiento', 'Descripcion' => 'Servicios de mantenimiento del hogar y empresas', 'Color' => '#33c4ff', 'Activa' => true],
            ['Nombre' => 'Reparaciones', 'Descripcion' => 'Reparación de equipos y maquinaria', 'Color' => '#28a745', 'Activa' => true],
        ]);
    }
}
