<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

class SocialAuthApiController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            // Buscar usuario por google_id o por email
            $user = Usuario::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            if ($user) {
                // Actualizar google_id y tokens si es necesario
                $user->update([
                    'google_id' => $googleUser->id,
                    'google_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken,
                ]);
            } else {
                // Si el usuario no existe, crearlo (auto-registro)
                $fullName = $googleUser->name;
                $nameParts = explode(' ', $fullName, 2);
                $nombre = $nameParts[0];
                $apellidos = $nameParts[1] ?? '';

                $user = Usuario::create([
                    'Nombre' => $nombre,
                    'Apellidos' => $apellidos,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'google_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken,
                    'idRol' => 2, // Rol de cliente por defecto
                    'Activo' => true,
                    // No necesita contraseña real para social login, pero el modelo requiere una
                    'password' => \Illuminate\Support\Str::random(16),
                ]);
            }

            // Iniciar sesión (session-based)
            Auth::login($user);
            request()->session()->regenerate();
            request()->session()->save(); // Asegurar que la sesión se guarda antes de redirigir

            // Redirigir al frontend (a la vista de callback específica)
            $frontendUrl = config('app.frontend_url');
            return redirect()->away($frontendUrl . '/auth/google/callback');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al autenticar con Google: ' . $e->getMessage()
            ], 500);
        }
    }
}
