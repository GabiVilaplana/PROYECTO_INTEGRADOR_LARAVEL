<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\ValoracionServicioController;
use App\Models\Categoria;
use App\Models\Servicio;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $servicios = Servicio::with('fotoPrincipal', 'categoria')->where('Activo', 1)->get();
    $categorias = Categoria::where('Activa', true)->get();
    return view('index', compact('categorias', 'servicios'));
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/perfil', [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/perfil/foto', [ProfileController::class, 'actualizarFoto'])->name('perfil.foto.actualizar');

    Route::view('/favoritos', 'favoritos.index')->name('favoritos');
    Route::view('/mensajes', 'mensajes.index')->name('mensajes');
    Route::view('/ayuda', 'ayuda.index')->name('ayuda');

    // 👇 ESTA ES LA CLAVE
    Route::resource('servicios', ServicioController::class);

    Route::post('/valoraciones', [ValoracionServicioController::class, 'store'])->name('valoraciones.store');
});

require __DIR__ . '/auth.php';