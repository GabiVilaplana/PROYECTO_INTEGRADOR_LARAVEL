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
        $rolUsuario = Rol::firstOrCreate(['Nombre' => 'usuario'])->IDRol;

        return [
            'Nombre' => $this->faker->firstName(),
            'Apellidos' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => 'password',
            'idRol' => $rolUsuario,
            'Activo' => true,
            'FotoPerfil' => 'perfiles/default.jpg',
        ];
    }
}
