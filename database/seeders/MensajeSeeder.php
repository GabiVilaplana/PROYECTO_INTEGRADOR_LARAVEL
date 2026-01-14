<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mensaje;

class MensajeSeeder extends Seeder
{
    public function run()
    {
        Mensaje::factory(30)->create();
    }
}
