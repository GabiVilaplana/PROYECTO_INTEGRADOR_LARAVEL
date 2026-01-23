<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Servicio;
use App\Models\Categoria;
use App\Models\Usuario;

class ServicioSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener categorías directamente desde la BD
        $limpieza = Categoria::where('Nombre', 'Limpieza')->first();
        $jardinero = Categoria::where('Nombre', 'Jardinero')->first();
        $disenador = Categoria::where('Nombre', 'Diseñador')->first();
        $mecanico = Categoria::where('Nombre', 'Mecánico')->first();
        $peluquero = Categoria::where('Nombre', 'Peluquero')->first();
        $profesor = Categoria::where('Nombre', 'Profesor')->first();

        // Obtener un proveedor de servicios
        $proveedor = Usuario::whereHas('rol', function($q){
            $q->where('Nombre','creadorServicio');
        })->first();

        // Verificar que existen categorías y proveedor antes de insertar
        if (!$limpieza || !$jardinero || !$disenador || !$mecanico || !$peluquero || !$profesor || !$proveedor) {
            throw new \Exception('Faltan categorías o proveedor para el Seeder de servicios.');
        }

        // Insertar servicios
        Servicio::insert([
            [
                'Nombre' => 'Limpieza Integral Hogar',
                'Descripcion' => 'Limpieza completa de toda la casa incluyendo cocina y baño',
                'Precio' => 120.00,
                'Duracion' => 90,
                'Activo' => true,
                'idCategoria' => $limpieza->IDCategoria,
                'idProveedor' => $proveedor->IDUsuario,
            ],
            [
                'Nombre' => 'Mantenimiento Jardín',
                'Descripcion' => 'Corte de césped, poda de arbustos y limpieza de jardín',
                'Precio' => 80.00,
                'Duracion' => 60,
                'Activo' => true,
                'idCategoria' => $jardinero->IDCategoria,
                'idProveedor' => $proveedor->IDUsuario,
            ],
            [
                'Nombre' => 'Diseño Web Básico',
                'Descripcion' => 'Creación de páginas web simples y adaptables a móviles',
                'Precio' => 150.00,
                'Duracion' => 120,
                'Activo' => true,
                'idCategoria' => $disenador->IDCategoria,
                'idProveedor' => $proveedor->IDUsuario,
            ],
            [
                'Nombre' => 'Reparación Mecánica',
                'Descripcion' => 'Reparación y mantenimiento de vehículos y maquinaria',
                'Precio' => 200.00,
                'Duracion' => 180,
                'Activo' => true,
                'idCategoria' => $mecanico->IDCategoria,
                'idProveedor' => $proveedor->IDUsuario,
            ],
            [
                'Nombre' => 'Corte de Pelo Profesional',
                'Descripcion' => 'Servicios de peluquería y estilismo',
                'Precio' => 50.00,
                'Duracion' => 60,
                'Activo' => true,
                'idCategoria' => $peluquero->IDCategoria,
                'idProveedor' => $proveedor->IDUsuario,
            ],
            [
                'Nombre' => 'Clases de Matemáticas',
                'Descripcion' => 'Clases particulares de matemáticas para estudiantes',
                'Precio' => 30.00,
                'Duracion' => 60,
                'Activo' => true,
                'idCategoria' => $profesor->IDCategoria,
                'idProveedor' => $proveedor->IDUsuario,
            ],
        ]);
    }
}
