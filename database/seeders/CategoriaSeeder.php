<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
   public function run(): void
    {
        $categorias = [
            [
                'Nombre' => 'Diseñador',
                'Descripcion' => 'Diseñador de páginas Web y gráficos',
                'Color' => '#28a745',
                'Activa' => true,
                'Imagen' => 'categorias/disenyador.jpg'
            ],
            [
                'Nombre' => 'Jardinero',
                'Descripcion' => 'Servicios de mantenimiento de jardines y plantas',
                'Color' => '#33c4ff',
                'Activa' => true,
                'Imagen' => 'categorias/jardinero.jpg'
            ],
            [
                'Nombre' => 'Limpieza',
                'Descripcion' => 'Servicios de limpieza profesional',
                'Color' => '#ff5733',
                'Activa' => true,
                'Imagen' => 'categorias/limpieza.jpg'
            ],
            [
                'Nombre' => 'Mecánico',
                'Descripcion' => 'Reparación y mantenimiento de vehículos y maquinaria',
                'Color' => '#f1c40f',
                'Activa' => true,
                'Imagen' => 'categorias/mecanico.jpg'
            ],
            [
                'Nombre' => 'Peluquero',
                'Descripcion' => 'Servicios de peluquería y estética',
                'Color' => '#e67e22',
                'Activa' => true,
                'Imagen' => 'categorias/peluquero.jpg'
            ],
            [
                'Nombre' => 'Profesor',
                'Descripcion' => 'Clases y formación en diferentes áreas',
                'Color' => '#9b59b6',
                'Activa' => true,
                'Imagen' => 'categorias/profesor.jpg'
            ],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}
