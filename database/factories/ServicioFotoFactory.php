<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ServicioFoto;
use App\Models\Servicio;

class ServicioFotoFactory extends Factory
{
    protected $model = ServicioFoto::class;

    public function definition()
    {
        $servicio = Servicio::inRandomOrder()->first();

        return [
            'idServicio' => $servicio->IDServicio,
            'RutaFoto' => 'images/'.$this->faker->word().'.jpg',
            'EsPrincipal' => $this->faker->boolean(50),
        ];
    }
}
