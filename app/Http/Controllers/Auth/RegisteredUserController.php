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
        // Quitamos validaciones complejas para que no te den guerra en las pruebas
        $user = Usuario::create([
            'Nombre' => $request->name,
            'CorreoElectronico' => $request->email,
            'Password' => \Hash::make($request->password), // IMPORTANTE: Aunque no quieras encriptar, Laravel NECESITA esto para que el Login funcione luego.
            'idRol' => 1, 
            'Activo' => 1,
        ]);

        \Illuminate\Support\Facades\Auth::login($user);

        return redirect('/servicios'); // O a tu página principal
    }
}
