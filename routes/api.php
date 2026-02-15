<?php

use App\Http\Controllers\Api\CategoriaApiController;
use App\Http\Controllers\Api\ChatbotApiController;
use App\Http\Controllers\Api\ServicioApiController;
use App\Http\Controllers\Api\UsuarioApiController;
use App\Http\Controllers\Api\ZonaApiController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

// Rutas del chatbot
Route::prefix('chatbot')->group(function () {
    Route::get('/servicios', [ChatbotApiController::class, 'getCatalogo']);
    Route::post('/reserva-status', [ChatbotApiController::class, 'consultarReserva']);
});

// Rutas de servicios (API pública)
Route::prefix('servicios')->group(function () {
    Route::get('/', [ServicioApiController::class, 'index']);          // Listar todos los servicios activos
    Route::get('/{id}', [ServicioApiController::class, 'show']);      // Ver un servicio específico
    Route::get('/buscar', [ServicioApiController::class, 'buscar']);  // Buscar por ubicación (lat/lng)
    Route::get('/categoria/{id}', [ServicioApiController::class, 'porCategoriaId']);
});

// Rutas de zonas (ciudades de España)
Route::prefix('zonas')->group(function () {
    Route::get('/', [ZonaApiController::class, 'index']);             // Listar todas las ciudades
    Route::get('/search', [ZonaApiController::class, 'search']);      // Autocompletado por nombre
    Route::get('/{slug}', [ZonaApiController::class, 'show']);        // Obtener ciudad por slug
    Route::post('/servicios/buscar', [ServicioApiController::class, 'buscar']); // Buscar servicios por ubicación (lat/lng) - para el filtro de zonas
});

Route::prefix('categorias')->group(function () {
    // Categorías
    Route::get('', [CategoriaApiController::class, 'index']);
    Route::get('/buscar', [CategoriaApiController::class, 'buscar']);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/usuario', [UsuarioApiController::class, 'show']);
});