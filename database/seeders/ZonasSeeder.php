<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Zona;

class ZonasSeeder extends Seeder
{
    public function run(): void
    {
        $ciudades = [
            ['Madrid', 40.416775, -3.703790],
            ['Barcelona', 41.385064, 2.173404],
            ['Valencia', 39.469750, -0.377387],
            ['Sevilla', 37.389092, -5.984459],
            ['Zaragoza', 41.652251, -0.880270],
            ['Málaga', 36.721276, -4.421399],
            ['Murcia', 37.992239, -1.130654],
            ['Palma', 39.569389, 2.650239],
            ['Las Palmas', 28.123547, -15.436279],
            ['Bilbao', 43.262713, -2.935013],
            ['Alicante', 38.345177, -0.481492],
            ['Córdoba', 37.888180, -4.779383],
            ['Valladolid', 41.652251, -4.724532],
            ['Vigo', 42.240612, -8.720727],
            ['Gijón', 43.535713, -5.661277],
            ['Hospitalet', 41.359783, 2.100089],
            ['A Coruña', 43.362344, -8.411540],
            ['Vitoria-Gasteiz', 42.846718, -2.671635],
            ['Granada', 37.177338, -3.598557],
            ['Oviedo', 43.360295, -5.844762],
            ['Santa Cruz de Tenerife', 28.463629, -16.251849],
            ['Pamplona', 42.816775, -1.643293],
            ['Almería', 36.834042, -2.463707],
            ['San Sebastián', 43.318302, -1.981165],
            ['Donostia / San Sebastián', 43.318302, -1.981165],
            ['Badajoz', 38.879440, -6.970615],
            ['Cartagena', 37.604130, -0.986010],
            ['Santander', 43.462294, -3.809998],
            ['Castellón', 39.986359, -0.051320],
            ['Jaén', 37.766400, -3.789100],
        ];

        foreach ($ciudades as [$nombre, $lat, $lng]) {
            Zona::updateOrCreate(
                ['slug' => strtolower(str_replace([' ', '/'], '-', $nombre))],
                [
                    'nombre' => $nombre,
                    'lat' => $lat,
                    'lng' => $lng,
                ]
            );
        }
    }
}