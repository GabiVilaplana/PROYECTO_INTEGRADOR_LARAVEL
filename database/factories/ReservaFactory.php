<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReservaFactory extends Factory
{
    protected $model = \App\Models\Reserva::class;

    public function definition()
    {
        return [
            'IDUsuario' => \App\Models\Usuario::factory(),
            'FechaReserva' => $this->faker->date(),
            'Estado' => $this->faker->randomElement(['Pendiente','Confirmada','Cancelada']),
            'Total' => $this->faker->randomFloat(2, 20, 500),
        ];
    }
}
