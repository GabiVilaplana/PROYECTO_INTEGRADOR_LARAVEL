<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Mensaje;
use App\Models\Usuario;

class MensajeFactory extends Factory
{
    protected $model = Mensaje::class;

    public function definition()
    {
        $usuario = Usuario::inRandomOrder()->first();

        return [
            'idUsuario' => $usuario->IDUsuario,
            'Nombre' => $usuario->Nombre.' '.$usuario->Apellidos,
            'Email' => $usuario->CorreoElectronico,
            'Asunto' => $this->faker->sentence(4),
            'Contenido' => $this->faker->paragraph(),
            'Estado' => $this->faker->randomElement(['pendiente','leido','respondido']),
            'FechaEnvio' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
