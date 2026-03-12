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
        Usuario::updateOrCreate(['email' => 'admin@admin.com'], [
            'Nombre' => 'Admin',
            'Apellidos' => 'admin',
            'password' => 'admin123',
            'idRol' => Rol::where('Nombre', 'admin')->first()->IDRol,
            'Activo' => true,
        ]);

        // Usuario estándar fijo
        Usuario::updateOrCreate(['email' => 'alexlopez@tasklink.com'], [
            'Nombre' => 'Alex',
            'Apellidos' => 'Lopez',
            'password' => 'alexlopez1234',
            'idRol' => Rol::where('Nombre', 'admin')->first()->IDRol,
            'Activo' => true,
        ]);
        Usuario::updateOrCreate(['email' => 'gabivilaplana@tasklink.com'], [
            'Nombre' => 'Gabi',
            'Apellidos' => 'Vilaplana',
            'password' => 'gabivilaplana1234',
            'idRol' => Rol::where('Nombre', 'admin')->first()->IDRol,
            'Activo' => true,
        ]);
        Usuario::updateOrCreate(['email' => 'andresprueba@gmail.com'], [
            'Nombre' => 'Andres',
            'Apellidos' => 'Prueba',
            'password' => 'andresprueba1234',
            'idRol' => Rol::where('Nombre', 'usuario')->first()->IDRol,
            'Activo' => true,
        ]);
        Usuario::updateOrCreate(['email' => 'andreslopez@gmail.com'], [
            'Nombre' => 'Andres',
            'Apellidos' => 'Lopez',
            'password' => 'andreslopez1234',
            'idRol' => Rol::where('Nombre', 'creadorServicio')->first()->IDRol,
            'Activo' => true,
        ]);
        Usuario::updateOrCreate(['email' => 'a2000lex@hotmail.com'], [
            'Nombre' => 'Alex',
            'Apellidos' => 'Lopez España',
            'password' => 'alexlopez1234',
            'idRol' => Rol::where('Nombre', 'admin')->first()->IDRol,
            'Activo' => true,
        ]);
        Usuario::updateOrCreate(['email' => 'gabired345@gmail.com'], [
            'Nombre' => 'Gabi',
            'Apellidos' => 'Vilaplana',
            'password' => 'gabi1234',
            'idRol' => Rol::where('Nombre', 'admin')->first()->IDRol,
            'Activo' => true,
        ]);

        // Usuarios aleatorios con rol "usuario"
        Usuario::factory(10)->create();
    }
}
