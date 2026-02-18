<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ValoracionServicio;
use App\Models\Servicio;
use Illuminate\Support\Facades\Auth;

class ValoracionServicioController extends Controller
{
    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request)
    {
        // Validación
        $request->validate([
            'servicio_id' => 'required|exists:servicios,IDServicio',
            'rating' => 'required|integer|min:1|max:5',
            'comentario' => 'required|string|max:1000',
        ]);

        $servicioId = $request->servicio_id;
        $userId = Auth::id(); // Usa el ID del usuario autenticado

        // Verificar que el usuario no haya valorado ya este servicio
        $existe = ValoracionServicio::where('idServicio', $servicioId)
            ->where('idUsuario', $userId)
            ->exists();

        if ($existe) {
            return back()->withErrors(['error' => 'Ya has valorado este servicio.']);
        }

        // Crear la valoración
        ValoracionServicio::create([
            'idServicio' => $servicioId,
            'idUsuario' => $userId,
            'Puntuacion' => $request->rating,
            'Comentario' => $request->comentario,
            'Fecha' => now(), // o puedes omitir si usas timestamps
        ]);

        return back()->with('success', '¡Gracias por tu valoración!');
    }
}