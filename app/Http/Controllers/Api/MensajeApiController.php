<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mensaje;
use Illuminate\Http\Request;

class MensajeApiController extends Controller
{
    public function index(Request $request)
    {
        $usuario = $request->user();

        $mensajes = Mensaje::with(['emisor', 'receptor'])
            ->where(function ($query) use ($usuario) {
                $query->where('idEmisor', $usuario->IDUsuario)
                    ->orWhere('idReceptor', $usuario->IDUsuario);
            })
            ->orderBy('FechaEnvio', 'desc')
            ->get();

        return response()->json($mensajes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'idReceptor' => 'required|exists:usuarios,IDUsuario',
            'Asunto' => 'required|string|max:255',
            'Mensaje' => 'required|string',
        ]);

        $usuario = $request->user();

        $mensaje = Mensaje::create([
            'idEmisor' => $usuario->IDUsuario,
            'idReceptor' => $request->idReceptor,
            'Asunto' => $request->Asunto,
            'Mensaje' => $request->Mensaje,
            'FechaEnvio' => now(),
            'Leido' => false,
        ]);

        return response()->json($mensaje->load(['emisor', 'receptor']), 201);
    }

    public function show(Request $request, $id)
    {
        $usuario = $request->user();

        $mensaje = Mensaje::with(['emisor', 'receptor'])
            ->where('IDMensaje', $id)
            ->where(function ($query) use ($usuario) {
                $query->where('idEmisor', $usuario->IDUsuario)
                    ->orWhere('idReceptor', $usuario->IDUsuario);
            })
            ->firstOrFail();

        // Marcar como leído si el usuario es el receptor
        if ($mensaje->idReceptor === $usuario->IDUsuario && !$mensaje->Leido) {
            $mensaje->Leido = true;
            $mensaje->save();
        }

        return response()->json($mensaje);
    }

    public function markAsRead(Request $request, $id)
    {
        $usuario = $request->user();

        $mensaje = Mensaje::where('IDMensaje', $id)
            ->where('idReceptor', $usuario->IDUsuario)
            ->firstOrFail();

        $mensaje->Leido = true;
        $mensaje->save();

        return response()->json($mensaje);
    }
}
