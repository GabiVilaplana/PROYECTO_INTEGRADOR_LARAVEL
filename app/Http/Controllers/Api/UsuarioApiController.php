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
}