<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario; // Import Usuario model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UsuarioApiController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(null, 401);
        }

        // Cargar relaciones necesarias para el perfil
        $user->load([
            'rol',
            'servicios.categoria',
            'reservas.detalles.servicio',
        ]);

        // Construir URL de la foto de perfil (aunque el accessor ya existe, mantenemos lógica similar o usamos accessor)
        $fotoPerfilUrl = $user->foto_perfil_url;

        return response()->json([
            'IDUsuario' => $user->IDUsuario,
            'Nombre' => $user->Nombre,
            'Apellidos' => $user->Apellidos,
            'NombreCompleto' => $user->Nombre . ' ' . $user->Apellidos,
            'email' => $user->email,
            'idRol' => $user->idRol,
            'Rol' => $user->rol ? $user->rol->Nombre : null,
            'Activo' => $user->Activo,
            'FotoPerfilUrl' => $fotoPerfilUrl,
            // Datos adicionales para el perfil
            'servicios' => $user->servicios,
            'reservas' => $user->reservas->map(function ($reserva) {
                // Asegurarse de formatear o incluir lo necesario
                return $reserva;
            }),
        ]);
    }

    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'foto_perfil' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
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

    public function update(Request $request)
    {
        $request->validate([
            'Nombre' => 'sometimes|string|max:100',
            'Apellidos' => 'sometimes|string|max:100',
            'email' => 'sometimes|email|unique:usuarios,email,' . $request->user()->IDUsuario . ',IDUsuario',
        ]);

        $user = $request->user();

        if ($request->has('Nombre')) {
            $user->Nombre = $request->Nombre;
        }

        if ($request->has('Apellidos')) {
            $user->Apellidos = $request->Apellidos;
        }

        if ($request->has('email')) {
            $user->email = $request->email;
        }

        $user->save();
        $user->load('rol'); // Reload rol relationship

        return response()->json([
            'success' => true,
            'usuario' => [
                'IDUsuario' => $user->IDUsuario,
                'Nombre' => $user->Nombre,
                'Apellidos' => $user->Apellidos,
                'email' => $user->email,
                'idRol' => $user->idRol,
                'Rol' => $user->rol ? $user->rol->Nombre : null,
                'FotoPerfilUrl' => $user->foto_perfil_url, // Assuming Accessor exists or construct manually if needed
            ]
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'La contraseña actual es incorrecta.'
            ], 422);
        }

        $user->password = $request->new_password;
        $user->save();

        return response()->json([
            'message' => 'Contraseña actualizada exitosamente.'
        ]);
    }

    // Admin: Listar todos los usuarios
    public function index(Request $request)
    {
        $query = Usuario::query();
        
        // Excluir al usuario actual
        $query->where('IDUsuario', '!=', $request->user()->IDUsuario);

        // Búsqueda por nombre o email
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('Nombre', 'like', "%{$search}%")
                  ->orWhere('Apellidos', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Incluir Rol
        $query->with('rol');

        // Paginar
        $usuarios = $query->paginate(10);

        // Transformar para el frontend
        $usuarios->getCollection()->transform(function ($user) {
            // Reusar lógica de foto perfil si no hay accessor
            $fotoUrl = $user->FotoPerfil ? Storage::disk('public')->url($user->FotoPerfil) : Storage::disk('public')->url('perfiles/default.jpg');
            
            return [
                'IDUsuario' => $user->IDUsuario,
                'Nombre' => $user->Nombre,
                'Apellidos' => $user->Apellidos,
                'email' => $user->email,
                'idRol' => $user->idRol,
                'Rol' => $user->rol ? $user->rol->Nombre : null,
                'Activo' => $user->Activo,
                'FotoPerfilUrl' => $fotoUrl,
            ];
        });

        return response()->json($usuarios);
    }

    // Admin: Editar usuario específico
    public function updateUser(Request $request, $id)
    {
        $user = Usuario::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $request->validate([
            'Nombre' => 'sometimes|string|max:100',
            'Apellidos' => 'sometimes|string|max:100',
            'email' => 'sometimes|email|unique:usuarios,email,' . $id . ',IDUsuario',
            'idRol' => 'sometimes|exists:rols,IDRol', // Asegurarse que la tabla se llama 'rols' o 'roles'
        ]);

        if ($request->has('Nombre')) $user->Nombre = $request->Nombre;
        if ($request->has('Apellidos')) $user->Apellidos = $request->Apellidos;
        if ($request->has('email')) $user->email = $request->email;
        if ($request->has('idRol')) $user->idRol = $request->idRol;

        $user->save();

        // Recargar rol para devolverlo actualizado
        $user->load('rol');

        return response()->json([
            'success' => true,
            'message' => 'Usuario actualizado correctamente',
            'usuario' => [
                'IDUsuario' => $user->IDUsuario,
                'Nombre' => $user->Nombre,
                'Apellidos' => $user->Apellidos,
                'email' => $user->email,
                'idRol' => $user->idRol,
                'Rol' => $user->rol ? $user->rol->Nombre : null,
            ]
        ]);
    }
}