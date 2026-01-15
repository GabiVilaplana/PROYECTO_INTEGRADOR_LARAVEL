<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ValoracionServicio;
use App\Models\Usuario;
use App\Models\Servicio;

class ValoracionServicioSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = Usuario::where('idRol', function($q){
            return $q->select('IDRol')->from('rols')->where('Nombre','usuario');
        })->get();

        $servicios = Servicio::all();

        foreach ($usuarios as $usuario) {
            foreach ($servicios as $servicio) {
                ValoracionServicio::create([
                    'idUsuario' => $usuario->IDUsuario,
                    'idServicio' => $servicio->IDServicio,
                    'Puntuacion' => rand(3,5),
                    'Comentario' => 'Muy buen servicio, cumplió mis expectativas.',
                    'Fecha' => now()->subDays(rand(1,30)),
                ]);
            }
        }
    }
}
