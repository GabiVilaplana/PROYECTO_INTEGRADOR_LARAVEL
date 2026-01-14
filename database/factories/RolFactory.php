<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RolFactory extends Factory
{
    protected $model = \App\Models\Rol::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->unique()->randomElement(['admin','usuario','empleado']),
            'descripcion' => $this->faker->sentence(),
        ];
    }
}
