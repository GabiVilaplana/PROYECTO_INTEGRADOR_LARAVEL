<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ServicioFotoFactory extends Factory
{
    protected $model = \App\Models\ServicioFoto::class;

    public function definition()
    {
        return [
            'IDServicio' => \App\Models\Servicio::factory(),
            'RutaFoto' => $this->faker->imageUrl(640, 480, 'business'),
            'EsPrincipal' => $this->faker->boolean(30),
        ];
    }
}
