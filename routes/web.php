<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\ValoracionServicioController;
use App\Models\Categoria;
use App\Models\Servicio;
use App\Models\Zona;
use App\Http\Controllers\Api\SocialAuthApiController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// Google OAuth
Route::get('/auth/google', [SocialAuthApiController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [SocialAuthApiController::class, 'handleGoogleCallback']);

// Ruta para ver servicios por zona
Route::get('/zona/{slug}', function ($slug) {
    $zona = Zona::where('slug', $slug)->firstOrFail();

    $servicios = Servicio::with('fotoPrincipal', 'categoria')
        ->selectRaw("
            *,
            radio_km,
            (6371 * acos(
                cos(radians(?)) *
                cos(radians(lat)) *
                cos(radians(lng) - radians(?)) +
                sin(radians(?)) *
                sin(radians(lat))
            )) AS distancia
        ", [$zona->lat, $zona->lng, $zona->lat])
        ->where('Activo', 1)
        ->whereNotNull('lat')
        ->whereNotNull('lng')
        ->where('radio_km', '>', 0)
        ->having('distancia', '<=', DB::raw('radio_km'))
        ->orderBy('distancia')
        ->get();

    $categorias = Categoria::where('Activa', true)->get();

    return view('zonas.show', compact('zona', 'servicios', 'categorias'));
})->name('zona.show');

// Búsqueda con filtros
Route::get('/buscar', function (\Illuminate\Http\Request $request) {
    if ($request->filled('servicio')) {
        $categoria = Categoria::where('Nombre', $request->servicio)->where('Activa', true)->first();
        if ($categoria) {
            return redirect()->route('categoria.show', ['categoria' => $request->servicio]);
        }
    }

    if ($request->filled('zona')) {
        $zona = Zona::where('nombre', $request->zona)->first();
        if ($zona) {
            return redirect()->route('zona.show', ['slug' => $zona->slug]);
        }
    }

    return redirect()->route('home');
})->name('buscar');

// Servicios por categoría
Route::get('/categoria/{categoria}', function ($nombreCategoria) {
    $categoria = Categoria::where('Nombre', $nombreCategoria)->where('Activa', true)->firstOrFail();
    $servicios = Servicio::with('fotoPrincipal', 'categoria')
        ->where('IDCategoria', $categoria->IDCategoria)
        ->where('Activo', 1)
        ->get();

    $categorias = Categoria::where('Activa', true)->get();

    return view('categorias.categoria', compact('categorias', 'servicios', 'categoria'));
})->name('categoria.show');

// Ruta principal
Route::get('/', function () {
    $categorias = Categoria::where('Activa', true)->get();
    $servicios = Servicio::with('fotoPrincipal', 'categoria')
        ->where('Activo', 1)
        ->get();

    return view('index', compact('categorias', 'servicios'));
})->name('home');

// Dashboard protegido
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas protegidas (requieren autenticación)
Route::middleware('auth')->group(function () {
    // RESERVAS Y PAGO
    Route::get('/reservas', [ReservaController::class, 'index'])->name('reservas.mi-lista');
    Route::get('/reservas/{reserva}/pago', [ReservaController::class, 'pago'])->name('reservas.pago');
    Route::get('/reservas/{reserva}', [ReservaController::class, 'show'])->name('reservas.show');

    // PERFIL
    Route::get('/perfil', [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/perfil/foto', [ProfileController::class, 'actualizarFoto'])->name('perfil.foto.actualizar');
    Route::put('/profile/rol', [ProfileController::class, 'updateRol'])->name('profile.rol.update');

    // OTRAS VISTAS
    Route::get('/favoritos', function () {
        $categorias = Categoria::where('Activa', true)->get();
        return view('favoritos.index', compact('categorias'));
    })->name('favoritos');

    Route::get('/mensajes', function () {
        $categorias = Categoria::where('Activa', true)->get();
        return view('mensajes.index', compact('categorias'));
    })->name('mensajes');

    Route::get('/ayuda', function () {
        $categorias = Categoria::where('Activa', true)->get();
        return view('ayuda.index', compact('categorias'));
    })->name('ayuda');

    // SERVICIOS
    Route::resource('servicios', ServicioController::class);
    Route::get('/servicios/{servicio}/edit', [ServicioController::class, 'edit'])->name('servicios.edit');
    Route::post('/servicios/{servicio}/toggle-activo', [ServicioController::class, 'toggleActivo'])->name('servicios.toggle-activo');
    Route::post('/valoraciones', [ValoracionServicioController::class, 'store'])->name('valoraciones.store');
});

require __DIR__ . '/auth.php';