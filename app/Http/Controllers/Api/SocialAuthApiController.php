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
                // Si el usuario no existe, redirigir al login con un error
                $frontendUrl = env('FRONTEND_URL', 'http://localhost:5173');
                return redirect()->away($frontendUrl . '/login?error=google_user_not_found');
            }

            // Iniciar sesión (session-based)
            Auth::login($user);
            request()->session()->regenerate();
            request()->session()->save(); // Asegurar que la sesión se guarda antes de redirigir

            // Redirigir al frontend
            $frontendUrl = env('FRONTEND_URL', 'http://localhost:5173');
            return redirect()->away($frontendUrl);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al autenticar con Google: ' . $e->getMessage()
            ], 500);
        }
    }
}
