<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Rol;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        // Admin fijo
        Usuario::create([
            'Nombre' => 'Admin',
            'Apellidos' => 'admin',
            'email' => 'admin@admin.com',
            'password' => 'admin123',
            'idRol' => Rol::where('Nombre', 'admin')->first()->IDRol,
            'Activo' => true,
        ]);

        // Usuario estándar fijo
        Usuario::create([
            'Nombre' => 'Alex',
            'Apellidos' => 'Lopez',
            'email' => 'alexlopez@tasklink.com',
            'password' => 'alexlopez1234',
            'idRol' => Rol::where('Nombre', 'admin')->first()->IDRol,
            'Activo' => true,
        ]);
        Usuario::create([
            'Nombre' => 'Gabi',
            'Apellidos' => 'Vilaplana',
            'email' => 'gabivilaplana@tasklink.com',
            'password' => 'gabivilaplana1234',
            'idRol' => Rol::where('Nombre', 'admin')->first()->IDRol,
            'Activo' => true,
        ]);
        Usuario::create([
            'Nombre' => 'Andres',
            'Apellidos' => 'Prueba',
            'email' => 'andresprueba@gmail.com',
            'password' => 'andresprueba1234',
            'idRol' => Rol::where('Nombre', 'usuario')->first()->IDRol,
            'Activo' => true,
        ]);
        Usuario::create([
            'Nombre' => 'Andres',
            'Apellidos' => 'Lopez',
            'email' => 'andreslopez@gmail.com',
            'password' => 'andreslopez1234',
            'idRol' => Rol::where('Nombre', 'creadorServicio')->first()->IDRol,
            'Activo' => true,
        ]);
          Usuario::create([
            'Nombre' => 'Alex',
            'Apellidos' => 'Lopez España',
            'email' => 'a2000lex@hotmail.com',
            'password' => 'alexlopez1234',
            'idRol' => Rol::where('Nombre', 'admin')->first()->IDRol,
            'Activo' => true,
        ]);
          Usuario::create([
            'Nombre' => 'Gabi',
            'Apellidos' => 'Vilaplana',
            'email' => 'gabired345@gmail.com',
            'password' => 'gabi1234',
            'idRol' => Rol::where('Nombre', 'admin')->first()->IDRol,
            'Activo' => true,
        ]);

        // Usuarios aleatorios con rol "usuario"
        Usuario::factory(10)->create();
    }
}
