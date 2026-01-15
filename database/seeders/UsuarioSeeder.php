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
            'CorreoElectronico' => 'admin@admin.com',
            'Password' => bcrypt('admin123'),
            'idRol' => Rol::where('Nombre', 'admin')->first()->IDRol,
            'Activo' => true,
        ]);

        // Usuario estándar fijo
        Usuario::create([
            'Nombre' => 'Alex',
            'Apellidos' => 'Lopez',
            'CorreoElectronico' => 'alexlopez@tasklink.com',
            'Password' => bcrypt('alexlopez1234'),
            'idRol' => Rol::where('Nombre', 'admin')->first()->IDRol,
            'Activo' => true,
        ]);
        Usuario::create([
            'Nombre' => 'Gabi',
            'Apellidos' => 'Vilaplana',
            'CorreoElectronico' => 'gabivilaplana@tasklink.com',
            'Password' => bcrypt('gabivilaplana1234'),
            'idRol' => Rol::where('Nombre', 'admin')->first()->IDRol,
            'Activo' => true,
        ]);
        Usuario::create([
            'Nombre' => 'Andres',
            'Apellidos' => 'Prueba',
            'CorreoElectronico' => 'andresprueba@gmail.com',
            'Password' => bcrypt('andresprueba1234'),
            'idRol' => Rol::where('Nombre', 'usuario')->first()->IDRol,
            'Activo' => true,
        ]);
        Usuario::create([
            'Nombre' => 'Andres',
            'Apellidos' => 'Lopez',
            'CorreoElectronico' => 'andreslopez@gmail.com',
            'Password' => bcrypt('andreslopez1234'),
            'idRol' => Rol::where('Nombre', 'creadorServicio')->first()->IDRol,
            'Activo' => true,
        ]);

        // Usuarios aleatorios con rol "usuario"
        Usuario::factory(10)->create();
    }
}
