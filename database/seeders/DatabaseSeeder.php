<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolSeeder::class,
            UsuarioSeeder::class,
            CategoriaSeeder::class,
            ServicioSeeder::class,
            ServicioFotoSeeder::class,
            ReservaSeeder::class,
            ReservaDetalleSeeder::class,
            PagoSeeder::class,
            ValoracionServicioSeeder::class,
            MensajeSeeder::class,
        ]);
    }
}
