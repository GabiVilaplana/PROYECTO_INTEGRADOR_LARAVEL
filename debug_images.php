<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

use App\Models\Categoria;
use App\Models\ServicioFoto;

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- CATEGORIAS ---\n";
foreach (Categoria::all() as $cat) {
    echo "ID: {$cat->IDCategoria}, Imagen: {$cat->Imagen}, URL: {$cat->imagen_url}\n";
}

echo "\n--- SERVICIO FOTOS ---\n";
foreach (ServicioFoto::all() as $foto) {
    echo "ID: {$foto->IDFoto}, Servicio: {$foto->idServicio}, Ruta: {$foto->RutaFoto}\n";
}
