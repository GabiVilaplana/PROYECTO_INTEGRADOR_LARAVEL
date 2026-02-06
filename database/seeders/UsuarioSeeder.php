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
            'password' => bcrypt('admin123'),
            'idRol' => Rol::where('Nombre', 'admin')->first()->IDRol,
            'Activo' => true,
        ]);

        // Usuario estándar fijo
        Usuario::create([
            'Nombre' => 'Alex',
            'Apellidos' => 'Lopez',
            'CorreoElectronico' => 'alexlopez@tasklink.com',
            'password' => bcrypt('alexlopez1234'),
            'idRol' => Rol::where('Nombre', 'admin')->first()->IDRol,
            'Activo' => true,
        ]);
        Usuario::create([
            'Nombre' => 'Gabi',
            'Apellidos' => 'Vilaplana',
            'CorreoElectronico' => 'gabivilaplana@tasklink.com',
            'password' => bcrypt('gabivilaplana1234'),
            'idRol' => Rol::where('Nombre', 'admin')->first()->IDRol,
            'Activo' => true,
        ]);
        Usuario::create([
            'Nombre' => 'Andres',
            'Apellidos' => 'Prueba',
            'CorreoElectronico' => 'andresprueba@gmail.com',
            'password' => bcrypt('andresprueba1234'),
            'idRol' => Rol::where('Nombre', 'usuario')->first()->IDRol,
            'Activo' => true,
        ]);
        Usuario::create([
            'Nombre' => 'Andres',
            'Apellidos' => 'Lopez',
            'CorreoElectronico' => 'andreslopez@gmail.com',
            'password' => bcrypt('andreslopez1234'),
            'idRol' => Rol::where('Nombre', 'creadorServicio')->first()->IDRol,
            'Activo' => true,
        ]);

        // Usuarios aleatorios con rol "usuario"
        Usuario::factory(10)->create();
    }
}
