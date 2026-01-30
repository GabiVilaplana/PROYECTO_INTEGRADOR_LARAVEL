<?php

use App\Http\Controllers\Api\ChatbotApiController;
use Illuminate\Support\Facades\Route;

// Estas rutas serán accesibles en: tu-dominio.com/api/chatbot/...
Route::prefix('chatbot')->group(function () {
    Route::get('/servicios', [ChatbotApiController::class, 'getCatalogo']);
    Route::post('/reserva-status', [ChatbotApiController::class, 'consultarReserva']);
});
