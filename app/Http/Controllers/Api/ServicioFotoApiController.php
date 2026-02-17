<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServicioFoto;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServicioFotoApiController extends Controller
{
    public function store(Request $request, $servicioId)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'EsPrincipal' => 'sometimes|boolean',
        ]);

        $usuario = $request->user();

        // Verificar que el servicio pertenece al usuario
        $servicio = Servicio::where('IDServicio', $servicioId)
            ->where('idProveedor', $usuario->IDUsuario)
            ->firstOrFail();

        $esPrincipal = $request->input('EsPrincipal', false);

        // Si se marca como principal, quitar principal a las demás
        if ($esPrincipal) {
            ServicioFoto::where('idServicio', $servicioId)
                ->update(['EsPrincipal' => false]);
        }

        $path = $request->file('foto')->store('servicios', 'public');

        $foto = ServicioFoto::create([
            'idServicio' => $servicioId,
            'RutaFoto' => $path,
            'EsPrincipal' => $esPrincipal,
        ]);

        return response()->json([
            'IDFoto' => $foto->IDFoto,
            'RutaFoto' => $foto->RutaFoto,
            'EsPrincipal' => $foto->EsPrincipal,
            'Url' => $foto->url,
        ], 201);
    }

    public function setPrincipal(Request $request, $fotoId)
    {
        $usuario = $request->user();

        $foto = ServicioFoto::with('servicio')
            ->where('IDFoto', $fotoId)
            ->firstOrFail();

        // Verificar que el servicio pertenece al usuario
        if ($foto->servicio->idProveedor !== $usuario->IDUsuario) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        // Quitar principal a todas las fotos del servicio
        ServicioFoto::where('idServicio', $foto->idServicio)
            ->update(['EsPrincipal' => false]);

        // Marcar esta como principal
        $foto->EsPrincipal = true;
        $foto->save();

        return response()->json($foto);
    }

    public function destroy(Request $request, $fotoId)
    {
        $usuario = $request->user();

        $foto = ServicioFoto::with('servicio')
            ->where('IDFoto', $fotoId)
            ->firstOrFail();

        // Verificar que el servicio pertenece al usuario
        if ($foto->servicio->idProveedor !== $usuario->IDUsuario) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        // No permitir eliminar si es la única foto
        $totalFotos = ServicioFoto::where('idServicio', $foto->idServicio)->count();
        if ($totalFotos <= 1) {
            return response()->json([
                'message' => 'No puedes eliminar la única foto del servicio.'
            ], 422);
        }

        // Eliminar archivo físico
        if (Storage::disk('public')->exists($foto->RutaFoto)) {
            Storage::disk('public')->delete($foto->RutaFoto);
        }

        $foto->delete();

        return response()->json([
            'message' => 'Foto eliminada exitosamente.'
        ]);
    }
}
