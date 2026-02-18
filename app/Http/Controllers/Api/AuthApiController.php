<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $usuario = Usuario::where('email', $request->email)->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return response()->json([
                'message' => 'Las credenciales son incorrectas.'
            ], 401);
        }

        if (!$usuario->Activo) {
            return response()->json([
                'message' => 'Tu cuenta está desactivada.'
            ], 403);
        }

        Auth::login($usuario);

        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'usuario' => [
                'IDUsuario' => $usuario->IDUsuario,
                'Nombre' => $usuario->Nombre,
                'Apellidos' => $usuario->Apellidos,
                'email' => $usuario->email,
                'idRol' => $usuario->idRol,
                'FotoPerfilUrl' => $usuario->foto_perfil_url,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Sesión cerrada exitosamente'
        ]);
    }

    public function check(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'authenticated' => false
            ]);
        }

        return response()->json([
            'authenticated' => true,
            'usuario' => [
                'IDUsuario' => $user->IDUsuario,
                'Nombre' => $user->Nombre,
                'Apellidos' => $user->Apellidos,
                'email' => $user->email,
                'idRol' => $user->idRol,
                'FotoPerfilUrl' => $user->foto_perfil_url,
            ]
        ]);
    }
}
