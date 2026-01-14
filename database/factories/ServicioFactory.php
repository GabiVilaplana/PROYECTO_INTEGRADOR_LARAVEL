<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ServicioFactory extends Factory
{
    protected $model = \App\Models\Servicio::class;

    public function definition()
    {
        return [
            'Nombre' => $this->faker->sentence(2),
            'Descripcion' => $this->faker->paragraph(),
            'Precio' => $this->faker->randomFloat(2, 10, 200),
            'Duracion' => $this->faker->numberBetween(30, 180),
            'Activo' => $this->faker->boolean(90),
            'IDCategoria' => \App\Models\Categoria::factory(),
            'IDProveedor' => \App\Models\Proveedor::factory(),
        ];
    }
}
