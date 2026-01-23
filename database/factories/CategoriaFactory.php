<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoriaFactory extends Factory
{
    protected $model = \App\Models\Categoria::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->word(),
            'descripcion' => $this->faker->sentence(),
            'color' => $this->faker->hexColor(),
            'activa' => true,
            'Imagen' => 'categorias/' . $this->faker->image('storage/app/public/categorias', 400, 300, null, false),

        ];
    }
}
