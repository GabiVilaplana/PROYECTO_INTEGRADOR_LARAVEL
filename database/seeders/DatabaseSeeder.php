<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1️⃣ Roles (no dependen de nadie)
        $this->call([
            RolSeeder::class,
        ]);

        // 2️⃣ Usuarios (dependen de roles)
        $this->call([
            UsuarioSeeder::class,
        ]);

        // 3️⃣ Categorías y proveedores (independientes)
        $this->call([
            CategoriaSeeder::class,
            ProveedorSeeder::class,
        ]);

        // 4️⃣ Servicios (dependen de categorías y proveedores)
        $this->call([
            ServicioSeeder::class,
        ]);

        // 5️⃣ Fotos de servicios (dependen de servicios)
        $this->call([
            ServicioFotoSeeder::class,
        ]);

        // 6️⃣ Valoraciones de servicios (dependen de usuarios y servicios)
        $this->call([
            ValoracionServicioSeeder::class,
        ]);

        // 7️⃣ Reservas (dependen de usuarios)
        $this->call([
            ReservaSeeder::class,
        ]);

        // 8️⃣ Detalles de reservas (dependen de reservas y servicios)
        $this->call([
            ReservaDetalleSeeder::class,
        ]);

        // 9️⃣ Pagos (dependen de reservas)
        $this->call([
            PagoSeeder::class,
        ]);

        // 🔟 Mensajes (dependen de usuarios)
        $this->call([
            MensajeSeeder::class,
        ]);
    }
}
