<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ValoracionServicio;
use App\Models\Usuario;
use App\Models\Servicio;

class ValoracionServicioFactory extends Factory
{
    protected $model = ValoracionServicio::class;

    public function definition()
    {
        $usuario = Usuario::whereHas('rol', function($q){
            $q->where('Nombre','usuario');
        })->inRandomOrder()->first();

        $servicio = Servicio::inRandomOrder()->first();

        return [
            'idUsuario' => $usuario->IDUsuario,
            'idServicio' => $servicio->IDServicio,
            'Puntuacion' => $this->faker->numberBetween(3,5),
            'Comentario' => $this->faker->sentence(),
            'Fecha' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
