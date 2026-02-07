<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public function show(Request $request): View
    {
        return view('profile.show', [
            'user' => $request->user(),
        ]);
    }

    public function actualizarFoto(Request $request)
    {
        $request->validate([
            'foto_perfil' => 'required|image|mimes:jpeg,png,jpg,gif|max:8096', // max 2MB
        ]);

        $usuario = auth()->user();

        // Eliminar foto anterior si no es la predeterminada
        if ($usuario->FotoPerfil && !str_contains($usuario->FotoPerfil, 'default.jpg')) {
            Storage::disk('public')->delete($usuario->FotoPerfil);
        }

        // Guardar nueva foto
        $path = $request->file('foto_perfil')->store('perfiles', 'public');

        $usuario->FotoPerfil = $path;
        $usuario->save();

        // Redirigir a la página anterior con mensaje de éxito
        return back()->with('success', 'Foto de perfil actualizada correctamente.');
    }
    public function updateRol(Request $request)
    {
        // Solo los administradores pueden cambiar roles
        if (auth()->user()->idRol != 1) {
            abort(403, 'Solo los administradores pueden modificar roles.');
        }

        $user = $request->user(); // o User::findOrFail($request->user_id) si editas otros

        // Determinar el nuevo idRol según TU lógica real
        $esAdmin = $request->boolean('es_admin');
        $esProveedor = $request->boolean('es_proveedor');

        if ($esAdmin) {
            $nuevoRol = 1; // admin
        } elseif ($esProveedor) {
            $nuevoRol = 3; // creadorServicio (proveedor)
        } else {
            $nuevoRol = 2; // usuario (cliente)
        }

        $user->idRol = $nuevoRol;
        $user->save();

        return back()->with('success', 'Roles actualizados correctamente.');
    }
}
