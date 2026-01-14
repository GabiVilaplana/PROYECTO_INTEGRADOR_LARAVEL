<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UsuarioFactory extends Factory
{
    protected $model = \App\Models\Usuario::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'rol_id' => \App\Models\Rol::factory(),
            'activo' => $this->faker->boolean(90),
        ];
    }
}
