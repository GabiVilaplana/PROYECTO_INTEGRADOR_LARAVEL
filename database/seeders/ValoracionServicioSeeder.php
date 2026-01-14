<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ValoracionServicio;

class ValoracionServicioSeeder extends Seeder
{
    public function run()
    {
        ValoracionServicio::factory(50)->create();
    }
}
