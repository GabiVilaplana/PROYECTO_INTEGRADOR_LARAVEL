<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\ReservaDetalle;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservaController extends Controller
{
    // Mostrar página de confirmación
    public function create(Servicio $servicio)
    {

        if ($servicio->idProveedor === Auth::id()) {
            return back()->withErrors(['No puedes contratar tu propio servicio.']);
        }

        return view('reservas.confirmarReserva', compact('servicio'));
    }


    // Listar mis reservas
    public function index()
    {
        $reservas = Auth::user()->reservas()
            ->with('detalles.servicio.categoria')
            ->latest()
            ->get();

        return view('reservas.index', compact('reservas'));
    }

    // Mostrar detalle de reserva
    public function show(Reserva $reserva)
    {
        if ($reserva->idUsuario !== Auth::id()) {
            abort(403, 'No autorizado.');
        }

        $detalle = $reserva->detalles->first();
        $servicio = $detalle ? $detalle->servicio : null;

        if (!$servicio) {
            abort(404);
        }

        return view('reservas.show', compact('reserva', 'detalle', 'servicio'));
    }

    public function pago(Reserva $reserva)
    {

        if ($reserva->Estado !== 'pendiente') {
            return redirect()->route('reservas.show', $reserva)
                ->with('error', 'Esta reserva ya fue procesada.');
        }

        $detalle = $reserva->detalles->first();
        $servicio = $detalle ? $detalle->servicio : null;

        if (!$servicio) {
            abort(404);
        }

        return view('reservas.confirmarYpagar', compact('reserva', 'detalle', 'servicio'));
    }
}