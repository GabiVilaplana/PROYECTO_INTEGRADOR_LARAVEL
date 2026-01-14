<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReservaDetalle;

class ReservaDetalleSeeder extends Seeder
{
    public function run()
    {
        ReservaDetalle::factory(40)->create();
    }
}
