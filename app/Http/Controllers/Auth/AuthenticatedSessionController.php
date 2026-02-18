<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.loginPropio');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $credenciales = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credenciales)) {
            $usuario = Auth::user();

            if (!$usuario->Activo) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return response()->json([
                    'message' => 'Tu cuenta está desactivada.'
                ], 403);
            }

            $request->session()->regenerate();

            return response()->json([
                'message' => 'Login exitoso',
                'user' => $usuario
            ], 200);
        }

        return response()->json([
            'message' => 'Las credenciales no coinciden o la cuenta no existe.'
        ], 422);
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['message' => 'Sesión cerrada'], 200);
        }

        return redirect()->intended(route('home'));
    }

    protected function credentials(Request $request)
    {
        return [
            'email' => $request->email,
            'password' => $request->password,
            'Activo' => true,
        ];
    }
}
