<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reserva;
use App\Models\Usuario;

class ReservaSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = Usuario::whereIn('idRol', function($q){
            $q->select('IDRol')->from('rols')->whereIn('Nombre',['usuario','creadorServicio']);
        })->get();

        foreach ($usuarios as $usuario) {
            Reserva::create([
                'idUsuario' => $usuario->IDUsuario,
                'FechaReserva' => now()->subDays(rand(1,15)),
                'Estado' => 'pendiente',
                'Total' => rand(50,200),
            ]);
        }
    }
}
