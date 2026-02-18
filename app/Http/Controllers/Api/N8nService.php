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

    /**
     * Envía un mensaje al Chatbot de n8n
     */
    public static function hablarConIA($mensaje)
    {
        $url = env('N8N_WEBHOOK_CHAT_URL');

        try {
            $response = Http::post($url, [
                'message' => $mensaje,
                'timestamp' => now()->toISOString(),
            ]);

            if ($response->successful()) {
                // n8n suele devolver la respuesta en un campo 'output' o 'response'
                $data = $response->json();
                return $data['output'] ?? $data['response'] ?? $data['message'] ?? 'He recibido tu mensaje.';
            }

            return false;
        } catch (\Exception $e) {
            Log::error("Error enviando mensaje al chatbot de n8n: " . $e->getMessage());
            return false;
        }
    }
}