<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use App\Models\Reserva;
use App\Models\Usuario;
use Illuminate\Http\Request;

class ChatbotApiController extends Controller
{
    /**
     * Devuelve el catálogo de servicios activos con sus precios.
     */
    public function servicios()
    {
        $servicios = Servicio::where('Activo', 1)
            ->select('Nombre', 'Precio', 'Descripcion')
            ->get();

        return response()->json($servicios);
    }

    /**
     * Busca la última reserva de un usuario por su email.
     */
    public function reservaStatus(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $usuario = Usuario::where('email', $request->email)->first();

        if (!$usuario) {
            return response()->json([
                'error' => 'No se encontró ningún usuario con ese correo electrónico.',
            ], 404);
        }

        $ultimaReserva = Reserva::where('idUsuario', $usuario->IDUsuario)
            ->with(['detalles.servicio'])
            ->orderBy('FechaReserva', 'desc')
            ->first();

        if (!$ultimaReserva) {
            return response()->json([
                'mensaje' => 'No tienes reservaciones registradas.',
            ]);
        }

        $servicios = $ultimaReserva->detalles->map(function ($detalle) {
            return $detalle->servicio->Nombre;
        })->implode(', ');

        return response()->json([
            'reserva_id' => $ultimaReserva->IDReserva,
            'fecha' => $ultimaReserva->FechaReserva,
            'estado' => $ultimaReserva->Estado,
            'servicios' => $servicios,
            'total' => $ultimaReserva->Total,
            'mensaje' => "Tu reserva #{$ultimaReserva->IDReserva} de los servicios ($servicios) está actualmente {$ultimaReserva->Estado}."
        ]);
    }
}
