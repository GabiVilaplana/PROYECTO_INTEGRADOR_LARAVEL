<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServicioFoto;

class ServicioFotoSeeder extends Seeder
{
    public function run()
    {
        ServicioFoto::factory(50)->create();
    }
}
