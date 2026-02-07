<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Categoria;
use App\Models\ServicioFoto;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServicioController extends Controller
{
    /**
     * Listar todos los servicios activos con foto principal y categoría
     */
    public function index()
    {
        $servicios = Servicio::with([
            'categoria',
            'proveedor',
            'fotoPrincipal',
            'fotos'
        ])->where('Activo', true)->get();

        return response()->json($servicios);
    }

    /**
     * Mostrar un servicio concreto con todas sus relaciones
     */
    public function show($id)
    {
        $servicio = Servicio::with([
            'categoria',
            'proveedor',
            'fotos',
            'fotoPrincipal',
            'valoraciones.usuario' // Carga las valoraciones + el usuario que las hizo
        ])->findOrFail($id);

        // 🔥 Calculamos media y total de reseñas
        $valoraciones = $servicio->valoraciones;
        $total_resenas = $valoraciones->count();
        $media = $total_resenas > 0 ? round($valoraciones->avg('Puntuacion'), 1) : 0;

        // Lógica para imagen
        $rutaImagen = null;
        if ($servicio->fotoPrincipal && $servicio->fotoPrincipal->RutaFoto) {
            $rutaImagen = asset('storage/' . ltrim($servicio->fotoPrincipal->RutaFoto, '/'));
        } elseif ($servicio->categoria && $servicio->categoria->Imagen) {
            $nombreImagen = strtolower($servicio->categoria->Imagen);
            $rutaImagen = asset('storage/' . ltrim($nombreImagen, '/'));
        }

        // ✅ Pasamos TODAS las variables necesarias a la vista
        return view('servicios.show', compact(
            'servicio',
            'rutaImagen',
            'media',
            'total_resenas'
        ));
    }

    /**
     * Crear servicio con fotos
     */
    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|string|max:255',
            'Descripcion' => 'required|string',
            'Precio' => 'required|numeric|min:0',
            'Duracion' => 'required|integer|min:1',
            'idCategoria' => 'required|exists:categorias,IDCategoria',
            'idProveedor' => 'required|exists:usuarios,IDUsuario',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'radio_km' => 'required|integer|min:1',
            'fotos' => 'nullable|array',
            'fotos.*' => 'string',
            'fotoPrincipal' => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($request, &$servicio) {

            $servicio = Servicio::create([
                'Nombre' => $request->Nombre,
                'Descripcion' => $request->Descripcion,
                'Precio' => $request->Precio,
                'Duracion' => $request->Duracion,
                'Activo' => true,
                'idCategoria' => $request->idCategoria,
                'idProveedor' => $request->idProveedor,
                'lat' => $request->lat,
                'lng' => $request->lng,
                'radio_km' => $request->radio_km,
            ]);

            // ============================
            // Manejar la foto principal
            // ============================
            if ($request->hasFile('fotoPrincipal')) {
                $path = $request->file('fotoPrincipal')->store('servicios', 'public');
                ServicioFoto::create([
                    'idServicio' => $servicio->IDServicio,
                    'RutaFoto' => $path,
                    'EsPrincipal' => true,
                ]);
            } else {
                $categoria = $servicio->categoria;
                ServicioFoto::create([
                    'idServicio' => $servicio->IDServicio,
                    'RutaFoto' => 'categorias/' . $categoria->Nombre . '.jpg',
                    'EsPrincipal' => true,
                ]);
            }

            // Guardar fotos adicionales
            if ($request->filled('fotos')) {
                foreach ($request->fotos as $ruta) {
                    ServicioFoto::create([
                        'idServicio' => $servicio->IDServicio,
                        'RutaFoto' => $ruta,
                        'EsPrincipal' => false,
                    ]);
                }
            }
        });

        return response()->json([
            'message' => 'Servicio creado correctamente',
            'servicio' => $servicio->load('fotoPrincipal', 'fotos')
        ], 201);
    }

    /**
     * Actualizar servicio
     */
    public function update(Request $request, $id)
    {
        $servicio = Servicio::findOrFail($id);

        $request->validate([
            'Nombre' => 'sometimes|string|max:255',
            'Descripcion' => 'sometimes|string',
            'Precio' => 'sometimes|numeric|min:0',
            'Duracion' => 'sometimes|integer|min:1',
            'Activo' => 'sometimes|boolean',
            'idCategoria' => 'sometimes|exists:categorias,IDCategoria',
            'lat' => 'sometimes|numeric',
            'lng' => 'sometimes|numeric',
            'radio_km' => 'sometimes|integer|min:1',
            'fotoPrincipal' => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($request, $servicio) {

            $servicio->update($request->only([
                'Nombre',
                'Descripcion',
                'Precio',
                'Duracion',
                'Activo',
                'idCategoria',
                'lat',
                'lng',
                'radio_km',
            ]));

            // Actualizar o agregar foto principal
            if ($request->hasFile('fotoPrincipal')) {
                $servicio->fotoPrincipal()->delete();
                $path = $request->file('fotoPrincipal')->store('servicios', 'public');
                ServicioFoto::create([
                    'idServicio' => $servicio->IDServicio,
                    'RutaFoto' => $path,
                    'EsPrincipal' => true,
                ]);
            } elseif (!$servicio->fotoPrincipal) {
                $categoria = $servicio->categoria;
                ServicioFoto::create([
                    'idServicio' => $servicio->IDServicio,
                    'RutaFoto' => 'categorias/' . $categoria->Nombre . '.jpg',
                    'EsPrincipal' => true,
                ]);
            }
        });

        return response()->json([
            'message' => 'Servicio actualizado',
            'servicio' => $servicio->load('fotoPrincipal', 'fotos')
        ]);
    }

    /**
     * Eliminar servicio y sus fotos
     */
    public function destroy($id)
    {
        $servicio = Servicio::findOrFail($id);

        DB::transaction(function () use ($servicio) {
            $servicio->fotos()->delete();
            $servicio->delete();
        });

        return response()->json([
            'message' => 'Servicio eliminado correctamente'
        ]);
    }

    /**
     * Buscar servicios por proximidad (tipo Airbnb)
     */
    public function buscar(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $lat = $request->lat;
        $lng = $request->lng;

        $servicios = Servicio::selectRaw("
            *,
            (6371 * acos(
                cos(radians(?))
                * cos(radians(lat))
                * cos(radians(lng) - radians(?))
                + sin(radians(?)) * sin(radians(lat))
            )) AS distancia
        ", [$lat, $lng, $lat])
            ->having('distancia', '<=', DB::raw('radio_km'))
            ->orderBy('distancia')
            ->with(['categoria', 'proveedor', 'fotoPrincipal'])
            ->get();

        return response()->json($servicios);
    }
    public function create()
    {
        $categorias = Categoria::where('Activa', true)->get();
        return view('servicios.create', compact('categorias'));
    }
    public function edit(Servicio $servicio)
    {
        if ($servicio->idProveedor !== Auth::id()) {
            abort(403, 'No tienes permiso para editar este servicio.');
        }

        $categorias = Categoria::where('Activa', true)->get();
        return view('servicios.edit', compact('servicio', 'categorias'));
    }
    public function toggleActivo(Servicio $servicio)
    {
        if ($servicio->idProveedor !== Auth::id()) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
            }
            abort(403, 'No tienes permiso para editar este servicio.');
        }

        $servicio->Activo = !$servicio->Activo;
        $servicio->save();

        // Si es una petición AJAX (como desde fetch), responder en JSON
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'activo' => $servicio->Activo,
                'message' => 'Estado del servicio actualizado correctamente.'
            ]);
        }

        // Si no es AJAX, redirigir (comportamiento legacy)
        return back()->with('success', 'Estado del servicio actualizado correctamente.');
    }

}
