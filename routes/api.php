<?php

use App\Http\Controllers\Api\ChatbotApiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServicioController;

// Estas rutas serán accesibles en: tu-dominio.com/api/chatbot/...
Route::prefix('chatbot')->group(function () {
    Route::get('/catalogo', [ChatbotApiController::class, 'getCatalogo']);
    Route::post('/reserva', [ChatbotApiController::class, 'consultarReserva']);
    Route::post('/chat', [ChatbotApiController::class, 'hablarConChatbot']);
});

Route::post('/confirmar-pago', [ServicioController::class, 'confirmarPago']);
Route::middleware('auth:sanctum')->post('/compra-rapida', [ServicioController::class, 'compraRapida']);


