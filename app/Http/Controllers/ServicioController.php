<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\ServicioFoto;
use Illuminate\Http\Request;
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
            'fotoPrincipal', // Relación que definimos en Servicio.php
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
            'valoraciones'
        ])->findOrFail($id);

        return response()->json($servicio);
    }

    /**
     * Crear servicio con fotos
     */
    public function store(Request $request)
    {
        $request->validate([
            'Nombre'        => 'required|string|max:255',
            'Descripcion'   => 'required|string',
            'Precio'        => 'required|numeric|min:0',
            'Duracion'      => 'required|integer|min:1',
            'idCategoria'   => 'required|exists:categorias,IDCategoria',
            'idProveedor'   => 'required|exists:usuarios,IDUsuario',
            'fotos'         => 'nullable|array',
            'fotos.*'       => 'string',
            'fotoPrincipal' => 'nullable|image|max:2048', // si suben archivo
        ]);

        DB::transaction(function () use ($request, &$servicio) {

            $servicio = Servicio::create([
                'Nombre'       => $request->Nombre,
                'Descripcion'  => $request->Descripcion,
                'Precio'       => $request->Precio,
                'Duracion'     => $request->Duracion,
                'Activo'       => true,
                'idCategoria'  => $request->idCategoria,
                'idProveedor'  => $request->idProveedor,
            ]);

            // ============================
            // Manejar la foto principal
            // ============================
            if ($request->hasFile('fotoPrincipal')) {
                $path = $request->file('fotoPrincipal')->store('servicios', 'public');
                ServicioFoto::create([
                    'idServicio' => $servicio->IDServicio,
                    'RutaFoto'   => $path,
                    'EsPrincipal'=> true,
                ]);
            } else {
                // Usar imagen de la categoría si no hay foto subida
                $categoria = $servicio->categoria; // cargamos relación
                ServicioFoto::create([
                    'idServicio' => $servicio->IDServicio,
                    'RutaFoto'   => 'categorias/' . $categoria->Nombre . '.jpg',
                    'EsPrincipal'=> true,
                ]);
            }

            // Guardar fotos adicionales si vienen
            if ($request->filled('fotos')) {
                foreach ($request->fotos as $ruta) {
                    ServicioFoto::create([
                        'idServicio' => $servicio->IDServicio,
                        'RutaFoto'   => $ruta,
                        'EsPrincipal'=> false,
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
            'Nombre'      => 'sometimes|string|max:255',
            'Descripcion' => 'sometimes|string',
            'Precio'      => 'sometimes|numeric|min:0',
            'Duracion'    => 'sometimes|integer|min:1',
            'Activo'      => 'sometimes|boolean',
            'idCategoria' => 'sometimes|exists:categorias,IDCategoria',
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
            ]));

            // ============================
            // Actualizar o agregar foto principal
            // ============================
            if ($request->hasFile('fotoPrincipal')) {
                // Borramos foto principal anterior
                $servicio->fotoPrincipal()->delete();

                // Guardamos nueva
                $path = $request->file('fotoPrincipal')->store('servicios', 'public');
                ServicioFoto::create([
                    'idServicio' => $servicio->IDServicio,
                    'RutaFoto'   => $path,
                    'EsPrincipal'=> true,
                ]);
            } elseif (!$servicio->fotoPrincipal) {
                // Si no hay foto principal, usar imagen de categoría
                $categoria = $servicio->categoria;
                ServicioFoto::create([
                    'idServicio' => $servicio->IDServicio,
                    'RutaFoto'   => 'categorias/' . $categoria->Nombre . '.jpg',
                    'EsPrincipal'=> true,
                ]);
            }

        });

        return response()->json([
            'message' => 'Servicio actualizado',
            'servicio' => $servicio->load('fotoPrincipal', 'fotos')
        ]);
    }
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
}
