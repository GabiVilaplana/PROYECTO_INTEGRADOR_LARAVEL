<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use App\Models\Reserva;
use App\Models\Categoria;
use Illuminate\Http\Request;

class ChatbotApiController extends Controller
{
    // Listar servicios para que la IA sepa qué vendes
    public function getCatalogo()
    {
        return response()->json(
            Servicio::with('categoria:IDCategoria,Nombre')
                ->where('Activo', 1)
                ->get(['IDServicio', 'Nombre', 'Precio', 'idCategoria', 'Descripcion'])
        );
    }

    // Consultar el estado de una reserva por el email del usuario
    public function consultarReserva(Request $request)
    {
        // 1. Validar que recibimos el email
        if (!$request->has('email')) {
            return response()->json(['error' => 'No se proporcionó un email'], 400);
        }

        // 2. Buscar la reserva
        // Usamos whereHas para buscar en la tabla 'usuarios' a través de la relación 'usuario'
        $reserva = Reserva::whereHas('usuario', function($query) use ($request) {
            $query->where('CorreoElectronico', $request->email);
        })
        ->with(['detalles.servicio', 'usuario']) // Cargamos también los datos del usuario
        ->latest('FechaReserva')
        ->first();

        if (!$reserva) {
            return response()->json(['message' => 'Lo siento, no he encontrado ninguna reserva para ese correo.'], 404);
        }

        // 3. Devolvemos una respuesta simplificada para que la IA no se líe
        return response()->json([
            'id' => $reserva->IDReserva,
            'estado' => $reserva->Estado,
            'fecha' => $reserva->FechaReserva,
            'total' => $reserva->Total,
            'usuario' => $reserva->usuario->Nombre,
            'servicios' => $reserva->detalles->map(function($d) {
                return $d->servicio->Nombre . ' (' . $d->HoraServicio . ')';
            })
        ]);
    }

    public function hablarConChatbot(Request $request)
    {
        if (!$request->has('message')) {
            return response()->json(['error' => 'No se proporcionó un mensaje'], 400);
        }

        $respuesta = N8nService::hablarConIA($request->message);

        if (!$respuesta) {
            return response()->json(['message' => 'Lo siento, no he podido contactar con mi cerebro digital.'], 500);
        }

        return response()->json([
            'answer' => $respuesta
        ]);
    }
}