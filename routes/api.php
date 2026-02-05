<?php

use App\Http\Controllers\Api\ChatbotApiController;
use App\Http\Controllers\Api\ServicioApiController;
use App\Http\Controllers\Api\ZonaApiController;
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
});

// Rutas de zonas (ciudades de España)
Route::prefix('zonas')->group(function () {
    Route::get('/', [ZonaApiController::class, 'index']);             // Listar todas las ciudades
    Route::get('/search', [ZonaApiController::class, 'search']);      // Autocompletado por nombre
    Route::get('/{slug}', [ZonaApiController::class, 'show']);        // Obtener ciudad por slug
});