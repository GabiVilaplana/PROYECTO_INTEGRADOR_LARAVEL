<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use App\Models\ReservaDetalle;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservaApiController extends Controller
{
    public function index(Request $request)
    {
        $usuario = $request->user();

        $reservas = Reserva::with(['detalles.servicio.fotoPrincipal', 'detalles.servicio.proveedor'])
            ->where('idUsuario', $usuario->IDUsuario)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($reservas);
    }

    public function store(Request $request)
    {
        $request->validate([
            'idServicio' => 'required|exists:servicios,IDServicio',
            'FechaServicio' => 'required|date|after_or_equal:today',
            'HoraServicio' => 'required|date_format:H:i:s',
        ]);

        $usuario = $request->user();
        $servicio = Servicio::findOrFail($request->idServicio);

        // Verificar disponibilidad del slot
        $slots = $servicio->getAvailableSlots($request->FechaServicio);
        $slotDisponible = collect($slots)->contains(function ($slot) use ($request) {
            return $slot['inicio'] === $request->HoraServicio;
        });

        if (!$slotDisponible) {
            return response()->json([
                'message' => 'El horario seleccionado ya no está disponible.'
            ], 422);
        }

        if ($usuario->IDUsuario === $servicio->idProveedor) {
            return response()->json([
                'message' => 'No puedes reservar tu propio servicio.'
            ], 422);
        }

        $reserva = DB::transaction(function () use ($usuario, $servicio, $request) {
            $reserva = Reserva::create([
                'idUsuario' => $usuario->IDUsuario,
                'FechaReserva' => now(),
                'Estado' => 'Pendiente',
                'Total' => $servicio->Precio,
            ]);

            ReservaDetalle::create([
                'idReserva' => $reserva->IDReserva,
                'idServicio' => $servicio->IDServicio,
                'Precio' => $servicio->Precio,
                'FechaServicio' => $request->FechaServicio,
                'HoraServicio' => $request->HoraServicio,
            ]);

            return $reserva->load(['detalles.servicio.fotoPrincipal', 'detalles.servicio.proveedor']);
        });

        return response()->json($reserva, 201);
    }

    public function show(Request $request, $id)
    {
        $usuario = $request->user();

        $reserva = Reserva::with(['detalles.servicio.fotoPrincipal', 'detalles.servicio.proveedor', 'pagos'])
            ->where('IDReserva', $id)
            ->where('idUsuario', $usuario->IDUsuario)
            ->firstOrFail();

        return response()->json($reserva);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Estado' => 'sometimes|in:Pendiente,Confirmada,Completada,Cancelada',
        ]);

        $usuario = $request->user();

        $reserva = Reserva::where('IDReserva', $id)
            ->where('idUsuario', $usuario->IDUsuario)
            ->firstOrFail();

        if ($request->has('Estado')) {
            $reserva->Estado = $request->Estado;
            $reserva->save();
        }

        return response()->json($reserva->load(['detalles.servicio']));
    }

    public function destroy(Request $request, $id)
    {
        $usuario = $request->user();

        $reserva = Reserva::where('IDReserva', $id)
            ->where('idUsuario', $usuario->IDUsuario)
            ->firstOrFail();

        if ($reserva->Estado === 'Completada') {
            return response()->json([
                'message' => 'No se puede cancelar una reserva completada.'
            ], 422);
        }

        $reserva->Estado = 'Cancelada';
        $reserva->save();

        return response()->json([
            'message' => 'Reserva cancelada exitosamente.'
        ]);
    }
}
