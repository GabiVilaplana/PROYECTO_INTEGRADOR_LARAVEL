<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Usuario;
use App\Models\Rol;

class UsuarioFactory extends Factory
{
    protected $model = Usuario::class;

    public function definition()
    {
        // Escoger rol usuario por defecto para los aleatorios
        $rolUsuario = Rol::where('Nombre', 'usuario')->first()->IDRol;

        return [
            'Nombre' => $this->faker->firstName(),
            'Apellidos' => $this->faker->lastName(),
            'CorreoElectronico' => $this->faker->unique()->safeEmail(),
            'Password' => bcrypt('password'),
            'idRol' => $rolUsuario,
            'Activo' => $this->faker->boolean(90),
        ];
    }
}
