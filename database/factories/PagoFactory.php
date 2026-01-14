<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PagoFactory extends Factory
{
    protected $model = \App\Models\Pago::class;

    public function definition()
    {
        return [
            'IDReserva' => \App\Models\Reserva::factory(),
            'MetodoPago' => $this->faker->randomElement(['Tarjeta','Efectivo','Transferencia']),
            'Estado' => $this->faker->randomElement(['Pendiente','Pagado','Fallido']),
            'Importe' => $this->faker->randomFloat(2, 20, 500),
            'FechaPago' => $this->faker->date(),
        ];
    }
}
