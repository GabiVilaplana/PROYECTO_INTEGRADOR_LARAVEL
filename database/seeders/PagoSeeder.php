<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pago;
use App\Models\Reserva;
use Carbon\Carbon;

class PagoSeeder extends Seeder
{
    public function run(): void
    {
        $reservas = Reserva::all();

        foreach ($reservas as $reserva) {
            Pago::create([
                'idReserva' => $reserva->IDReserva,
                'MetodoPago' => ['tarjeta','efectivo','paypal'][rand(0,2)],
                'Estado' => 'completado',
                'Importe' => $reserva->Total,
                'FechaPago' => Carbon::parse($reserva->FechaReserva)->addDays(rand(0,1)),
            ]);
        }
    }
}
