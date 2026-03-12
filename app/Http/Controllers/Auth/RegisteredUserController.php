<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Rol;
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

        $user = Usuario::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'idRol' => Rol::firstOrCreate(['Nombre' => 'usuario'])->IDRol,
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
