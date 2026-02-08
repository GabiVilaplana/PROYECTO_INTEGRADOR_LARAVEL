<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\ValoracionServicioController;
use App\Models\Categoria;
use App\Models\Servicio;
use App\Models\Zona;
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

    // Si hay zona, filtrar por proximidad
    if ($request->filled('zona')) {
        $nombreZona = $request->zona;

        // Buscar la zona en la base de datos para obtener sus coordenadas
        $zona = Zona::where('nombre', $nombreZona)->first();

        if (!$zona || !$zona->lat || !$zona->lng) {
            // Si no se encuentra la zona, redirigir a home
            return redirect()->route('home')->with('error', 'Zona no encontrada.');
        }

        // Pasar las coordenadas a la vista principal para que filtre allí
        return redirect()->route('home', [
            'lat' => $zona->lat,
            'lng' => $zona->lng,
            'zona_nombre' => $nombreZona,
        ]);
    }

    // Si hay fechas u otros filtros, redirigir a home con ellos
    if ($request->filled('fecha_desde') || $request->filled('fecha_hasta')) {
        return redirect()->route('home', [
            'fecha_desde' => $request->fecha_desde,
            'fecha_hasta' => $request->fecha_hasta,
        ]);
    }

    return redirect()->route('home');
})->name('buscar');

// Ruta para ver servicios por categoría - CORREGIDA
Route::get('/categoria/{categoria}', function ($nombreCategoria) {
    $categoria = Categoria::where('Nombre', $nombreCategoria)->where('Activa', true)->firstOrFail();
    $servicios = Servicio::with('fotoPrincipal', 'categoria')
        ->where('IDCategoria', $categoria->IDCategoria)
        ->where('Activo', 1)
        ->get();

    $categorias = Categoria::where('Activa', true)->get();

    return view('categorias.categoria', compact('categorias', 'servicios', 'categoria'));
})->name('categoria.show');

// Ruta principal (actualizada para aceptar parámetros)
Route::get('/', function (\Illuminate\Http\Request $request) {
    $categorias = Categoria::where('Activa', true)->get();

    $query = Servicio::with('fotoPrincipal', 'categoria');

    if ($request->filled(['lat', 'lng'])) {
        $lat = (float) $request->lat;
        $lng = (float) $request->lng;

        $query->selectRaw("
            *,
            radio_km,
            (6371 * acos(
                cos(radians(?)) *
                cos(radians(lat)) *
                cos(radians(lng) - radians(?)) +
                sin(radians(?)) *
                sin(radians(lat))
            )) AS distancia
        ", [$lat, $lng, $lat])
            ->where('Activo', 1) // ✅ clave: solo activos
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->where('radio_km', '>', 0)
            ->having('distancia', '<=', DB::raw('radio_km'))
            ->orderBy('distancia');
    } else {
        $query->where('Activo', 1); // también aquí
    }

    $servicios = $query->get();
    $zonaNombre = $request->zona_nombre ?? null;

    return view('index', compact('categorias', 'servicios', 'zonaNombre'));
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

    // ✅ Corregido: ahora pasan $categorias a la vista
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

    // Recursos de servicios
    Route::resource('servicios', ServicioController::class);
    Route::get('/servicios/{servicio}/edit', [ServicioController::class, 'edit'])->name('servicios.edit');
    Route::post('/servicios/{servicio}/toggle-activo', [ServicioController::class, 'toggleActivo'])->name('servicios.toggle-activo');
    Route::post('/valoraciones', [ValoracionServicioController::class, 'store'])->name('valoraciones.store');
});

require __DIR__ . '/auth.php';