<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReservaDetalle;
use App\Models\Reserva;
use App\Models\Servicio;
use Carbon\Carbon;
class ReservaDetalleSeeder extends Seeder
{
    public function run(): void
    {
        $reservas = Reserva::all();
        $servicios = Servicio::all();

        foreach ($reservas as $reserva) {
            $servicio = $servicios->random();

            ReservaDetalle::create([
                'idReserva' => $reserva->IDReserva,
                'idServicio' => $servicio->IDServicio,
                'Precio' => $servicio->Precio,
                'FechaServicio' => Carbon::parse($reserva->FechaReserva)->addDays(rand(1,5)),
                'HoraServicio' => sprintf('%02d:00:00', rand(9,18)),
            ]);
        }
    }
}
