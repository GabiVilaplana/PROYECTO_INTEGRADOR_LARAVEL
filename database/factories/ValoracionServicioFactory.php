<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ValoracionServicioFactory extends Factory
{
    protected $model = \App\Models\ValoracionServicio::class;

    public function definition()
    {
        return [
            'IDServicio' => \App\Models\Servicio::factory(),
            'IDUsuario' => \App\Models\Usuario::factory(),
            'Puntuacion' => $this->faker->numberBetween(1,5),
            'Comentario' => $this->faker->sentence(),
            'Fecha' => $this->faker->date(),
        ];
    }
}
