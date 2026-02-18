<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Models\Reserva;
use Illuminate\Http\Request;

class PagoApiController extends Controller
{
    public function index(Request $request)
    {
        $usuario = $request->user();

        $pagos = Pago::with('reserva')
            ->whereHas('reserva', function ($query) use ($usuario) {
                $query->where('idUsuario', $usuario->IDUsuario);
            })
            ->orderBy('FechaPago', 'desc')
            ->get();

        return response()->json($pagos);
    }

    public function store(Request $request)
    {
        $request->validate([
            'idReserva' => 'required|exists:reservas,IDReserva',
            'MetodoPago' => 'required|in:Tarjeta,PayPal,Transferencia,Efectivo',
            'Monto' => 'required|numeric|min:0',
        ]);

        $usuario = $request->user();

        // Verificar que la reserva pertenece al usuario
        $reserva = Reserva::where('IDReserva', $request->idReserva)
            ->where('idUsuario', $usuario->IDUsuario)
            ->firstOrFail();

        $pago = Pago::create([
            'idReserva' => $request->idReserva,
            'MetodoPago' => $request->MetodoPago,
            'Monto' => $request->Monto,
            'FechaPago' => now(),
            'Estado' => 'Pendiente',
        ]);

        return response()->json($pago->load('reserva'), 201);
    }

    public function show(Request $request, $id)
    {
        $usuario = $request->user();

        $pago = Pago::with('reserva')
            ->where('IDPago', $id)
            ->whereHas('reserva', function ($query) use ($usuario) {
                $query->where('idUsuario', $usuario->IDUsuario);
            })
            ->firstOrFail();

        return response()->json($pago);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Estado' => 'required|in:Pendiente,Completado,Fallido,Reembolsado',
        ]);

        $usuario = $request->user();

        $pago = Pago::where('IDPago', $id)
            ->whereHas('reserva', function ($query) use ($usuario) {
                $query->where('idUsuario', $usuario->IDUsuario);
            })
            ->firstOrFail();

        $pago->Estado = $request->Estado;
        $pago->save();

        return response()->json($pago->load('reserva'));
    }
}
