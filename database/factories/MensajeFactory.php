<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MensajeFactory extends Factory
{
    protected $model = \App\Models\Mensaje::class;

    public function definition()
    {
        return [
            'IDUsuario' => \App\Models\Usuario::factory(),
            'Nombre' => $this->faker->name(),
            'Email' => $this->faker->unique()->safeEmail(),
            'Asunto' => $this->faker->sentence(3),
            'Contenido' => $this->faker->paragraph(),
            'Estado' => $this->faker->randomElement(['Pendiente','Leído','Respondido']),
            'FechaEnvio' => $this->faker->dateTimeThisYear(),
        ];
    }
}
