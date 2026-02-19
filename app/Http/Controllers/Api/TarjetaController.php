<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TarjetaGuardada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TarjetaController extends Controller
{
    /**
     * Listar las tarjetas guardadas del usuario autenticado.
     */
    public function index()
    {
        $usuario = Auth::user();
        $tarjetas = TarjetaGuardada::where('idUsuario', $usuario->IDUsuario)->get();
        return response()->json($tarjetas);
    }

    /**
     * Guardar una nueva tarjeta si no existe ya para el usuario.
     */
    public function store(Request $request)
    {
        $request->validate([
            'NombreTitular' => 'required|string',
            'NumeroTarjeta' => 'required|string',
            'MesExpiracion' => 'required|string|max:2',
            'AnioExpiracion' => 'required|string|max:2',
        ]);

        $usuario = Auth::user();

        // Verificar si ya existe esta tarjeta para el usuario (usamos el número para simplificar)
        $existe = TarjetaGuardada::where('idUsuario', $usuario->IDUsuario)
            ->where('NumeroTarjeta', $request->NumeroTarjeta)
            ->first();

        if ($existe) {
            return response()->json($existe, 200);
        }

        $tarjeta = TarjetaGuardada::create([
            'idUsuario' => $usuario->IDUsuario,
            'NombreTitular' => $request->NombreTitular,
            'NumeroTarjeta' => $request->NumeroTarjeta,
            'MesExpiracion' => $request->MesExpiracion,
            'AnioExpiracion' => $request->AnioExpiracion,
        ]);

        return response()->json($tarjeta, 201);
    }

    /**
     * Eliminar una tarjeta guardada.
     */
    public function destroy($id)
    {
        $usuario = Auth::user();
        $tarjeta = TarjetaGuardada::where('idUsuario', $usuario->IDUsuario)
            ->where('IDTarjeta', $id)
            ->firstOrFail();

        $tarjeta->delete();

        return response()->json(['message' => 'Tarjeta eliminada correctamente.']);
    }
}
