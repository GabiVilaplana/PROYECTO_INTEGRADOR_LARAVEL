<?php

namespace Database\Seeders;

use App\Models\Servicio;
use App\Models\ServicioDisponibilidad;
use Illuminate\Database\Seeder;

class ServicioDisponibilidadSeeder extends Seeder
{
    private array $horariosPorServicio = [
        1 => ['dias' => [1, 2, 3, 4, 5], 'horario' => ['inicio' => '09:00', 'fin' => '18:00']],
        2 => ['dias' => [1, 2, 3, 4, 5, 6], 'horario' => ['inicio' => '08:00', 'fin' => '14:00']],
        3 => ['dias' => [1, 2, 3, 4, 5], 'horario' => ['inicio' => '10:00', 'fin' => '20:00']],
        4 => ['dias' => [1, 2, 3, 4, 5, 6], 'horario' => ['inicio' => '08:00', 'fin' => '19:00']],
        5 => ['dias' => [2, 3, 4, 5, 6], 'horario' => ['inicio' => '09:00', 'fin' => '21:00']],
        6 => ['dias' => [1, 2, 3, 4, 6], 'horario' => ['inicio' => '16:00', 'fin' => '21:00']],
    ];

    public function run(): void
    {
        ServicioDisponibilidad::truncate();

        $servicios = Servicio::all();

        foreach ($servicios as $servicio) {
            $config = $this->horariosPorServicio[$servicio->IDServicio] ?? null;

            if ($config) {
                foreach ($config['dias'] as $dia) {
                    ServicioDisponibilidad::create([
                        'idServicio' => $servicio->IDServicio,
                        'dia_semana' => $dia,
                        'hora_inicio' => $config['horario']['inicio'],
                        'hora_fin' => $config['horario']['fin'],
                        'activo' => true,
                    ]);
                }
            } else {
                ServicioDisponibilidad::factory()
                    ->count(rand(3, 5))
                    ->for($servicio, 'servicio')
                    ->create();
            }
        }

        $this->command->info('✅ Disponibilidades sembradas correctamente.');
    }
}