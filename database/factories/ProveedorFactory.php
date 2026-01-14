<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProveedorFactory extends Factory
{
    protected $model = \App\Models\Proveedor::class;

    public function definition()
    {
        return [
            'Nombre' => $this->faker->company(),
            'Direccion' => $this->faker->address(),
            'Telefono' => $this->faker->phoneNumber(),
            'CorreoElectronico' => $this->faker->unique()->companyEmail(),
        ];
    }
}
