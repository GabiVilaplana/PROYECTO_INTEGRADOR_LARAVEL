<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\ValoracionServicioController;
use App\Models\Categoria;
use App\Models\Servicio;
use Illuminate\Support\Facades\Route;

// Nueva ruta para manejar la búsqueda con todos los filtros
Route::get('/buscar', function (\Illuminate\Http\Request $request) {
    // Si se seleccionó una categoría, redirigir a la página de categoría
    if ($request->filled('servicio')) {
        $nombreCategoria = $request->servicio;
        $categoria = Categoria::where('Nombre', $nombreCategoria)->where('Activa', true)->first();

        if ($categoria) {
            return redirect()->route('categoria.show', ['categoria' => $nombreCategoria]);
        }
    }

    // Si hay otros filtros (zona o fechas), mostrar resultados en home con parámetros
    if ($request->filled('zona') || $request->filled('fecha_desde') || $request->filled('fecha_hasta')) {
        return redirect()->route('home', [
            'zona' => $request->zona,
            'fecha_desde' => $request->fecha_desde,
            'fecha_hasta' => $request->fecha_hasta
        ]);
    }

    // Si no hay ningún filtro, redirigir a home sin parámetros
    return redirect()->route('home');
})->name('buscar');

// Ruta para ver servicios por categoría - CORREGIDA
Route::get('/categoria/{categoria}', function ($nombreCategoria) {
    $categoria = Categoria::where('Nombre', $nombreCategoria)->where('Activa', true)->firstOrFail();
    $servicios = Servicio::with('fotoPrincipal', 'categoria')
        ->where('IDCategoria', $categoria->IDCategoria) // ← Cambiado aquí
        ->where('Activo', 1)
        ->get();

    $categorias = Categoria::where('Activa', true)->get();

    return view('categorias.categoria', compact('categorias', 'servicios', 'categoria'));

})->name('categoria.show');

// Ruta principal (actualizada para aceptar parámetros)
Route::get('/', function (\Illuminate\Http\Request $request) {
    $query = Servicio::with('fotoPrincipal', 'categoria')->where('Activo', 1);
    $categorias = Categoria::where('Activa', true)->get();

    // Aplicar filtros si existen
    if ($request->filled('zona')) {
        // Aquí aplicarías el filtro por zona cuando lo implementes
        // Ejemplo: $query->where('ciudad', $request->zona);
    }

    if ($request->filled('fecha_desde') && $request->filled('fecha_hasta')) {
        // Aquí aplicarías el filtro por fechas cuando lo implementes
        // Ejemplo: $query->whereBetween('fecha_disponible', [$request->fecha_desde, $request->fecha_hasta]);
    }

    $servicios = $query->get();

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
    Route::put('/profile/rol', [ProfileController::class, 'updateRol'])->name('profile.rol.update');
    Route::view('/favoritos', 'favoritos.index')->name('favoritos');
    Route::view('/mensajes', 'mensajes.index')->name('mensajes');
    Route::view('/ayuda', 'ayuda.index')->name('ayuda');

    // 👇 ESTA ES LA CLAVE
    Route::resource('servicios', ServicioController::class);
    Route::get('/servicios/{servicio}/edit', [ServicioController::class, 'edit'])->name('servicios.edit');
    Route::post('/servicios/{servicio}/toggle-activo', [ServicioController::class, 'toggleActivo'])->name('servicios.toggle-activo');
    Route::post('/valoraciones', [ValoracionServicioController::class, 'store'])->name('valoraciones.store');
});

require __DIR__ . '/auth.php';