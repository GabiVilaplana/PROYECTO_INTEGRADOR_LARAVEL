<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ValoracionServicio;
use App\Models\Servicio;
use Illuminate\Http\Request;

class ValoracionApiController extends Controller
{
    public function index($servicioId)
    {
        $servicio = Servicio::findOrFail($servicioId);

        $valoraciones = ValoracionServicio::with('usuario')
            ->where('idServicio', $servicioId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'valoraciones' => $valoraciones,
            'promedio' => $servicio->promedio_valoracion,
            'total' => $valoraciones->count(),
        ]);
    }

    public function store(Request $request, $servicioId)
    {
        $request->validate([
            'Puntuacion' => 'required|integer|min:1|max:5',
            'Comentario' => 'nullable|string|max:1000',
        ]);

        $usuario = $request->user();
        $servicio = Servicio::findOrFail($servicioId);

        // REGLA: El usuario debe tener al menos una reserva completada para este servicio
        $tieneReservaCompletada = \App\Models\Reserva::where('idUsuario', $usuario->IDUsuario)
            ->where('Estado', 'Completada')
            ->whereHas('detalles', function($query) use ($servicioId) {
                $query->where('idServicio', $servicioId);
            })
            ->exists();

        if (!$tieneReservaCompletada) {
            return response()->json([
                'message' => 'Solo puedes valorar servicios que hayas reservado y completado previamente.'
            ], 403);
        }

        // Verificar si el usuario ya ha valorado este servicio
        $valoracionExistente = ValoracionServicio::where('idServicio', $servicioId)
            ->where('idUsuario', $usuario->IDUsuario)
            ->first();

        if ($valoracionExistente) {
            return response()->json([
                'message' => 'Ya has valorado este servicio. Puedes actualizar tu valoración.'
            ], 422);
        }

        $valoracion = ValoracionServicio::create([
            'idServicio' => $servicioId,
            'idUsuario' => $usuario->IDUsuario,
            'Puntuacion' => $request->Puntuacion,
            'Comentario' => $request->Comentario,
            'FechaValoracion' => now(),
        ]);

        return response()->json($valoracion->load('usuario'), 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Puntuacion' => 'sometimes|integer|min:1|max:5',
            'Comentario' => 'nullable|string|max:1000',
        ]);

        $usuario = $request->user();

        $valoracion = ValoracionServicio::where('IDValoracion', $id)
            ->where('idUsuario', $usuario->IDUsuario)
            ->firstOrFail();

        if ($request->has('Puntuacion')) {
            $valoracion->Puntuacion = $request->Puntuacion;
        }

        if ($request->has('Comentario')) {
            $valoracion->Comentario = $request->Comentario;
        }

        $valoracion->save();

        return response()->json($valoracion->load('usuario'));
    }

    public function destroy(Request $request, $id)
    {
        $usuario = $request->user();

        $valoracion = ValoracionServicio::where('IDValoracion', $id)
            ->where('idUsuario', $usuario->IDUsuario)
            ->firstOrFail();

        $valoracion->delete();

        return response()->json([
            'message' => 'Valoración eliminada exitosamente.'
        ]);
    }
}
