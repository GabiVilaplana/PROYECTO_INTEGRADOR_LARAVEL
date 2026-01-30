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
        $request->validate(['email' => 'required|email']);

        $reserva = Reserva::whereHas('usuario', function($query) use ($request) {
            $query->where('CorreoElectronico', $request->email);
        })
        ->with('detalles.servicio')
        ->latest()
        ->first();

        if (!$reserva) {
            return response()->json(['message' => 'No se encontraron reservas.'], 404);
        }

        return response()->json($reserva);
    }
}