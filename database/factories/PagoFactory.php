<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Pago;
use App\Models\Reserva;

class PagoFactory extends Factory
{
    protected $model = Pago::class;

    public function definition()
    {
        $reserva = Reserva::inRandomOrder()->first();

        return [
            'idReserva' => $reserva->IDReserva,
            'MetodoPago' => $this->faker->randomElement(['tarjeta','paypal','efectivo']),
            'Estado' => $this->faker->randomElement(['pendiente','completado','fallido']),
            'Importe' => $reserva->Total,
            'FechaPago' => $reserva->FechaReserva->modify('+'.rand(0,1).' days'),
        ];
    }
}
