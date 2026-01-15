<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Reserva;
use App\Models\Usuario;

class ReservaFactory extends Factory
{
    protected $model = Reserva::class;

    public function definition()
    {
        $usuario = Usuario::whereHas('rol', function($q){
            $q->where('Nombre','usuario');
        })->inRandomOrder()->first();

        return [
            'idUsuario' => $usuario->IDUsuario,
            'FechaReserva' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'Estado' => $this->faker->randomElement(['pendiente','confirmada','cancelada']),
            'Total' => $this->faker->randomFloat(2, 50, 300),
        ];
    }
}
