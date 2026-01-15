<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Servicio;
use App\Models\Categoria;
use App\Models\Usuario;

class ServicioFactory extends Factory
{
    protected $model = Servicio::class;

    public function definition()
    {
        $categoria = Categoria::inRandomOrder()->first();
        $proveedor = Usuario::whereHas('rol', function($q){
            $q->where('Nombre','creadorServicio');
        })->inRandomOrder()->first();

        return [
            'Nombre' => $this->faker->sentence(3),
            'Descripcion' => $this->faker->paragraph(),
            'Precio' => $this->faker->randomFloat(2, 50, 200),
            'Duracion' => $this->faker->numberBetween(30, 180),
            'Activo' => true,
            'idCategoria' => $categoria->IDCategoria,
            'idProveedor' => $proveedor->IDUsuario,
        ];
    }
}
