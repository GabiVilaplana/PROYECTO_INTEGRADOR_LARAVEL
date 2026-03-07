<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\ChatbotApiController;
use App\Http\Controllers\Api\CategoriaApiController;
use App\Http\Controllers\Api\FaqApiController;
use App\Http\Controllers\Api\MensajeApiController;
use App\Http\Controllers\Api\PagoApiController;
use App\Http\Controllers\Api\ProveedorApiController;
use App\Http\Controllers\Api\ReservaApiController;
use App\Http\Controllers\Api\RolApiController;
use App\Http\Controllers\Api\ServicioApiController;
use App\Http\Controllers\Api\ServicioDisponibilidadApiController;
use App\Http\Controllers\Api\ServicioFotoApiController;
use App\Http\Controllers\Api\TarjetaController;
use App\Http\Controllers\Api\UsuarioApiController;
use App\Http\Controllers\Api\ValoracionApiController;
use App\Http\Controllers\Api\ZonaApiController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServicioController;

// ============================================
// RUTAS PÚBLICAS (sin autenticación)
// ============================================

// Autenticación
Route::post('login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthApiController::class, 'logout']);
Route::get('/check', [AuthApiController::class, 'check']);

Route::get('/faqs', [FaqApiController::class, 'index']);
Route::post('/faqs', [FaqApiController::class, 'store']);
Route::put('/faqs/{id}', [FaqApiController::class, 'update']);
Route::delete('/faqs/{id}', [FaqApiController::class, 'destroy']);

Route::get('/faq-categories', [FaqApiController::class, 'categories']);
Route::post('/faq-categories', [FaqApiController::class, 'storeCategory']);
Route::put('/faq-categories/{id}', [FaqApiController::class, 'updateCategory']);
Route::delete('/faq-categories/{id}', [FaqApiController::class, 'destroyCategory']);


// Roles
Route::get('/roles', [RolApiController::class, 'index']);

// Servicios
Route::prefix('servicios')->group(function () {
    Route::get('/', [ServicioApiController::class, 'index']);
    Route::get('/{id}', [ServicioApiController::class, 'show']);
    Route::get('/{id}/slots', [ServicioApiController::class, 'getSlots']);
    Route::get('/buscar', [ServicioApiController::class, 'buscar']);
    Route::get('/categoria/{id}', [ServicioApiController::class, 'porCategoriaId']);

    // Valoraciones de servicios (públicas para lectura)
    Route::get('/{id}/valoraciones', [ValoracionApiController::class, 'index']);

    // Disponibilidad (pública para lectura)
    Route::get('/{id}/disponibilidad', [ServicioDisponibilidadApiController::class, 'index']);
});

// Categorías
Route::prefix('categorias')->group(function () {
    Route::get('', [CategoriaApiController::class, 'index']);
    Route::get('/buscar', [CategoriaApiController::class, 'buscar']);
});

// Zonas
Route::prefix('zonas')->group(function () {
    Route::get('/', [ZonaApiController::class, 'index']);
    Route::get('/search', [ZonaApiController::class, 'search']);
    Route::get('/{slug}', [ZonaApiController::class, 'show']);
    Route::post('/servicios/buscar', [ServicioApiController::class, 'buscar']);
});



Route::middleware('auth:sanctum')->group(function () {

    // Usuario / Perfil (Logueado)
    Route::prefix('usuario')->group(function () {
        Route::get('/', [UsuarioApiController::class, 'show']);
        Route::put('/', [UsuarioApiController::class, 'update']);
        Route::put('/password', [UsuarioApiController::class, 'updatePassword']);
        Route::post('/foto', [UsuarioApiController::class, 'updateProfilePhoto']);
    });

    // Gestión de Usuarios (Admin)
    Route::prefix('usuarios')->group(function () {
        Route::get('/', [UsuarioApiController::class, 'index']); // Listar todos
        Route::put('/{id}', [UsuarioApiController::class, 'updateUser']); // Editar usuario específico
    });

    // Reservas
    Route::prefix('reservas')->group(function () {
        Route::get('/', [ReservaApiController::class, 'index']);
        Route::post('/', [ReservaApiController::class, 'store']);
        Route::get('/{id}', [ReservaApiController::class, 'show']);
        Route::put('/{id}', [ReservaApiController::class, 'update']);
        Route::delete('/{id}', [ReservaApiController::class, 'destroy']);
    });

    // Valoraciones (escritura requiere autenticación)
    Route::prefix('valoraciones')->group(function () {
        Route::post('/servicios/{id}', [ValoracionApiController::class, 'store']);
        Route::put('/{id}', [ValoracionApiController::class, 'update']);
        Route::delete('/{id}', [ValoracionApiController::class, 'destroy']);
    });

    // Mensajes
    Route::prefix('mensajes')->group(function () {
        Route::get('/', [MensajeApiController::class, 'index']);
        Route::post('/', [MensajeApiController::class, 'store']);
        Route::get('/{id}', [MensajeApiController::class, 'show']);
        Route::put('/{id}/leer', [MensajeApiController::class, 'markAsRead']);
    });

    // Pagos
    Route::prefix('pagos')->group(function () {
        Route::get('/', [PagoApiController::class, 'index']);
        Route::post('/', [PagoApiController::class, 'store']);
        Route::get('/{id}', [PagoApiController::class, 'show']);
        Route::put('/{id}', [PagoApiController::class, 'update']);
    });

    // Gestión de Tarjetas
    Route::prefix('tarjetas')->group(function () {
        Route::get('/', [TarjetaController::class, 'index']);
        Route::post('/', [TarjetaController::class, 'store']);
        Route::delete('/{id}', [TarjetaController::class, 'destroy']);
    });

    Route::prefix('proveedor')->group(function () {
        // Gestión de servicios
        Route::get('/servicios', [ProveedorApiController::class, 'servicios']);
        Route::post('/servicios', [ProveedorApiController::class, 'storeServicio']);
        Route::put('/servicios/{id}', [ProveedorApiController::class, 'updateServicio']);
        Route::delete('/servicios/{id}', [ProveedorApiController::class, 'destroyServicio']);

        // Reservas del proveedor
        Route::get('/reservas', [ProveedorApiController::class, 'reservas']);
        Route::put('/reservas/{id}/estado', [ProveedorApiController::class, 'updateReservaEstado']);

        // Estadísticas
        Route::get('/estadisticas', [ProveedorApiController::class, 'estadisticas']);

        // Gestión de fotos de servicios
        Route::post('/servicios/{id}/fotos', [ServicioFotoApiController::class, 'store']);
        Route::put('/servicios/fotos/{id}/principal', [ServicioFotoApiController::class, 'setPrincipal']);
        Route::delete('/servicios/fotos/{id}', [ServicioFotoApiController::class, 'destroy']);

        // Gestión de disponibilidad
        Route::put('/servicios/{id}/disponibilidad', [ServicioDisponibilidadApiController::class, 'update']);
    });
});
Route::post('/confirmar-pago', [ServicioController::class, 'confirmarPago']);
Route::middleware('auth:sanctum')->post('/compra-rapida', [ServicioController::class, 'compraRapida']);

// Chatbot
Route::prefix('chatbot')->group(function () {
    Route::get('/servicios', [ChatbotApiController::class, 'servicios']);
    Route::post('/reserva-status', [ChatbotApiController::class, 'reservaStatus']);
});


