<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ReservaDetalle;
use App\Models\Reserva;
use App\Models\Servicio;

class ReservaDetalleFactory extends Factory
{
    protected $model = ReservaDetalle::class;

    public function definition()
    {
        $reserva = Reserva::inRandomOrder()->first();
        $servicio = Servicio::inRandomOrder()->first();

        return [
            'idReserva' => $reserva->IDReserva,
            'idServicio' => $servicio->IDServicio,
            'Precio' => $servicio->Precio,
            'FechaServicio' => $reserva->FechaReserva->modify('+'.rand(1,5).' days'),
            'HoraServicio' => sprintf('%02d:00:00', rand(9,18)),
        ];
    }
}
