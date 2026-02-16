<?php

use App\Http\Controllers\Api\CategoriaApiController;
use App\Http\Controllers\Api\ChatbotApiController;
use App\Http\Controllers\Api\ServicioApiController;
use App\Http\Controllers\Api\UsuarioApiController;
use App\Http\Controllers\Api\ZonaApiController;
use Illuminate\Support\Facades\Route;

// Rutas del chatbot
Route::prefix('chatbot')->group(function () {
    Route::get('/servicios', [ChatbotApiController::class, 'getCatalogo']);
    Route::post('/reserva-status', [ChatbotApiController::class, 'consultarReserva']);
});

// Rutas públicas
Route::prefix('servicios')->group(function () {
    Route::get('/', [ServicioApiController::class, 'index']);
    Route::get('/{id}', [ServicioApiController::class, 'show']);
    Route::get('/buscar', [ServicioApiController::class, 'buscar']);
    Route::get('/categoria/{id}', [ServicioApiController::class, 'porCategoriaId']);
});

Route::prefix('zonas')->group(function () {
    Route::get('/', [ZonaApiController::class, 'index']);
    Route::get('/search', [ZonaApiController::class, 'search']);
    Route::get('/{slug}', [ZonaApiController::class, 'show']);
    Route::post('/servicios/buscar', [ServicioApiController::class, 'buscar']);
});

Route::prefix('categorias')->group(function () {
    Route::get('', [CategoriaApiController::class, 'index']);
    Route::get('/buscar', [CategoriaApiController::class, 'buscar']);
});

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/usuario', [UsuarioApiController::class, 'show']);
    Route::post('/perfil/foto', [UsuarioApiController::class, 'updateProfilePhoto']);
});