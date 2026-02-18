<?php

namespace Database\Factories;

use App\Models\Servicio;
use App\Models\ServicioDisponibilidad;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServicioDisponibilidadFactory extends Factory
{
    protected $model = ServicioDisponibilidad::class;

    public function definition(): array
    {
        return [
            'idServicio' => fn() => Servicio::inRandomOrder()->first()?->IDServicio 
                ?? Servicio::factory()->create()->IDServicio,
            'dia_semana' => $this->faker->numberBetween(0, 6),
            'hora_inicio' => $this->faker->randomElement([
                '08:00', '09:00', '10:00', '14:00', '15:00', '16:00'
            ]),
            'hora_fin' => $this->faker->randomElement([
                '13:00', '14:00', '18:00', '19:00', '20:00', '21:00'
            ]),
            'activo' => $this->faker->boolean(90),
        ];
    }

    public function laborable(): static
    {
        return $this->state(fn (array $attributes) => [
            'dia_semana' => $this->faker->numberBetween(1, 5),
        ]);
    }

    public function finDeSemana(): static
    {
        return $this->state(fn (array $attributes) => [
            'dia_semana' => $this->faker->randomElement([0, 6]),
        ]);
    }
}