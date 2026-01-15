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
        $categorias = Categoria::all();
        $proveedores = Usuario::whereHas('rol', function($q){
            $q->where('Nombre','creadorServicio');
        })->get();

        Servicio::insert([
            [
                'Nombre' => 'Limpieza Integral Hogar',
                'Descripcion' => 'Limpieza completa de toda la casa incluyendo cocina y baño',
                'Precio' => 120.00,
                'Duracion' => 90,
                'Activo' => true,
                'idCategoria' => $categorias->where('Nombre','Limpieza')->first()->IDCategoria,
                'idProveedor' => $proveedores->first()->IDUsuario,
            ],
            [
                'Nombre' => 'Mantenimiento Jardín',
                'Descripcion' => 'Corte de césped, poda de arbustos y limpieza de jardín',
                'Precio' => 80.00,
                'Duracion' => 60,
                'Activo' => true,
                'idCategoria' => $categorias->where('Nombre','Mantenimiento')->first()->IDCategoria,
                'idProveedor' => $proveedores->first()->IDUsuario,
            ],
        ]);
    }
}
