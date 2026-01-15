<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mensaje;
use App\Models\Usuario;

class MensajeSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = Usuario::where('idRol', function($q){
            return $q->select('IDRol')->from('rols')->where('Nombre','usuario');
        })->get();

        foreach ($usuarios as $usuario) {
            Mensaje::create([
                'idUsuario' => $usuario->IDUsuario,
                'Nombre' => $usuario->Nombre.' '.$usuario->Apellidos,
                'Email' => $usuario->CorreoElectronico,
                'Asunto' => 'Consulta sobre servicio',
                'Contenido' => 'Hola, quisiera más información sobre uno de sus servicios.',
                'Estado' => 'pendiente',
                'FechaEnvio' => now()->subDays(rand(1,10)),
            ]);
        }
    }
}
