<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

use App\Models\Servicio;

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$servicio = Servicio::with(['fotoPrincipal'])->first();
if ($servicio) {
    echo json_encode($servicio->toArray(), JSON_PRETTY_PRINT);
} else {
    echo "No servicios found.";
}
