<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServicioController;
use App\Models\Categoria;
use App\Models\Servicio;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $servicios = Servicio::with('fotoPrincipal', 'categoria')
        ->where('Activo', 1)
        ->get();
    $categorias = Categoria::where('Activa', true)->get();
    return view('index', compact('categorias', 'servicios'));

});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/servicios/{id}', [ServicioController::class, 'show'])->name('servicios.show');
Route::get('/compra-temporal', function () {
    return '<h1>Página temporal de compra</h1>';
});



require __DIR__ . '/auth.php';
