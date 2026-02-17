<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:usuarios,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Separar nombre y apellidos
        $nombreCompleto = $request->name;
        $partesNombre = explode(' ', $nombreCompleto, 2);
        $nombre = $partesNombre[0];
        $apellidos = $partesNombre[1] ?? '';

        $user = Usuario::create([
            'Nombre' => $nombre,
            'Apellidos' => $apellidos,
            'email' => $request->email,
            'password' => $request->password, // El mutador del modelo Usuario hace el hash
            'idRol' => 2, // Rol de cliente por defecto
            'Activo' => true,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Si es una petición API (desde Vue), devolver JSON
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Usuario registrado exitosamente',
                'user' => [
                    'IDUsuario' => $user->IDUsuario,
                    'Nombre' => $user->Nombre,
                    'Apellidos' => $user->Apellidos,
                    'email' => $user->email,
                    'idRol' => $user->idRol,
                ]
            ], 201);
        }

        // Si es petición web tradicional, redirigir
        return redirect(route('dashboard', absolute: false));
    }
}
