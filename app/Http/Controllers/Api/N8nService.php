<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class N8nService
{
    /**
     * Envía la confirmación de compra a n8n
     */
    public static function enviarConfirmacionCompra($datos)
    {
        // La URL de tu Webhook de n8n (recomiendo ponerla en el archivo .env)
        $url = env('N8N_WEBHOOK_COMPRA_URL');

        try {
            $response = Http::post($url, [
                'email' => $datos['email'],
                'cliente_nombre' => $datos['nombre'],
                'servicio_nombre' => $datos['servicio'],
                'precio' => $datos['precio'],
                'status' => 'paid', // Esto es lo que validará tu nodo IF
                'fecha' => now()->format('d-m-Y H:i'),
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error("Error enviando datos a n8n: " . $e->getMessage());
            return false;
        }
    }
}