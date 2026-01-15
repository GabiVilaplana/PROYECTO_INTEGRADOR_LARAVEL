<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServicioFoto;
use App\Models\Servicio;

class ServicioFotoSeeder extends Seeder
{
    public function run(): void
    {
        $servicios = Servicio::all();

        foreach ($servicios as $servicio) {
            ServicioFoto::create([
                'idServicio' => $servicio->IDServicio,
                'RutaFoto' => 'images/'.$servicio->IDServicio.'_principal.jpg',
                'EsPrincipal' => true,
            ]);
        }
    }
}
