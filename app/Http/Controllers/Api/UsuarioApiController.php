<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UsuarioApiController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(null, 401);
        }

        // Construir URL de la foto de perfil
        $fotoPerfilUrl = null;
        if ($user->FotoPerfil) {
            $fotoPerfilUrl = Storage::disk('public')->url($user->FotoPerfil);
        } else {
            $fotoPerfilUrl = Storage::disk('public')->url('perfiles/default.jpg');
        }

        return response()->json([
            'IDUsuario' => $user->IDUsuario,
            'Nombre' => $user->Nombre,
            'Apellidos' => $user->Apellidos,
            'NombreCompleto' => $user->Nombre . ' ' . $user->Apellidos,
            'email' => $user->email,
            'idRol' => $user->idRol,
            'Activo' => $user->Activo,
            'FotoPerfilUrl' => $fotoPerfilUrl,
        ]);
    }

    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'foto_perfil' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = $request->user();

        if ($request->hasFile('foto_perfil')) {
            // Delete old photo if exists and not default
            if ($user->FotoPerfil && $user->FotoPerfil !== 'perfiles/default.jpg') {
                Storage::disk('public')->delete($user->FotoPerfil);
            }

            $path = $request->file('foto_perfil')->store('perfiles', 'public');
            $user->FotoPerfil = $path;
            $user->save();

            return response()->json([
                'success' => true,
                'FotoPerfilUrl' => Storage::disk('public')->url($path)
            ]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }
}