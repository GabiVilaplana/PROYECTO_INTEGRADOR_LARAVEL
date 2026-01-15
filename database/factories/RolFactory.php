<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RolFactory extends Factory
{
    protected $model = \App\Models\Rol::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->word(), // no se usará, los roles se crean en Seeder
            'descripcion' => $this->faker->sentence(),
        ];
    }
}
