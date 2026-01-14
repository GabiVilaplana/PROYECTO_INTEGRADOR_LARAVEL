<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReservaDetalleFactory extends Factory
{
    protected $model = \App\Models\ReservaDetalle::class;

    public function definition()
    {
        return [
            'IDReserva' => \App\Models\Reserva::factory(),
            'IDServicio' => \App\Models\Servicio::factory(),
            'Precio' => $this->faker->randomFloat(2, 10, 200),
            'FechaServicio' => $this->faker->date(),
            'HoraServicio' => $this->faker->time(),
        ];
    }
}
