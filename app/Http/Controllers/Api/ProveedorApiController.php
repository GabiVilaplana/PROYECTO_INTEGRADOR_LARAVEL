<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use App\Models\ServicioFoto;
use App\Models\ServicioDisponibilidad;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProveedorApiController extends Controller
{
    // Listar servicios del proveedor
    public function servicios(Request $request)
    {
        $usuario = $request->user();

        $servicios = Servicio::with(['categoria', 'fotoPrincipal', 'valoraciones'])
            ->where('idProveedor', $usuario->IDUsuario)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($servicios);
    }

    // Crear nuevo servicio
    public function storeServicio(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|string|max:200',
            'Descripcion' => 'required|string',
            'Precio' => 'required|numeric|min:0',
            'Duracion' => 'required|integer|min:1',
            'idCategoria' => 'required|exists:categorias,IDCategoria',
            'idZona' => 'nullable|exists:zonas,id',
            'Direccion' => 'nullable|string|max:255',
            'Latitud' => 'nullable|numeric',
            'Longitud' => 'nullable|numeric',
            'foto' => 'sometimes|image|mimes:jpeg,png,jpg,webp|max:8096',
            'fotos.*' => 'sometimes|image|mimes:jpeg,png,jpg,webp|max:8096',
        ]);

        $usuario = $request->user();

        $servicio = DB::transaction(function () use ($request, $usuario) {
            $servicio = Servicio::create([
                'Nombre' => $request->Nombre,
                'Descripcion' => $request->Descripcion,
                'Precio' => $request->Precio,
                'Duracion' => $request->Duracion,
                'idCategoria' => $request->idCategoria,
                'idZona' => $request->idZona,
                'idProveedor' => $usuario->IDUsuario,
                'Direccion' => $request->Direccion,
                'Latitud' => $request->Latitud,
                'Longitud' => $request->Longitud,
                'Activo' => true,
            ]);

            // Manejar foto principal
            if ($request->hasFile('foto')) {
                $path = $request->file('foto')->store('servicios', 'public');
                ServicioFoto::create([
                    'idServicio' => $servicio->IDServicio,
                    'RutaFoto' => $path,
                    'EsPrincipal' => true,
                ]);
            } else {
                // Usar foto de categoría por defecto
                $categoria = $servicio->categoria;
                ServicioFoto::create([
                    'idServicio' => $servicio->IDServicio,
                    'RutaFoto' => 'categorias/' . $categoria->Nombre . '.jpg',
                    'EsPrincipal' => true,
                ]);
            }

            // Manejar galería de fotos
            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $foto) {
                    $path = $foto->store('servicios', 'public');
                    ServicioFoto::create([
                        'idServicio' => $servicio->IDServicio,
                        'RutaFoto' => $path,
                        'EsPrincipal' => false,
                    ]);
                }
            }

            return $servicio->load(['categoria', 'fotoPrincipal']);
        });

        return response()->json($servicio, 201);
    }

    // Actualizar servicio
    public function updateServicio(Request $request, $id)
    {
        $request->validate([
            'Nombre' => 'sometimes|string|max:200',
            'Descripcion' => 'sometimes|string',
            'Precio' => 'sometimes|numeric|min:0',
            'Duracion' => 'sometimes|integer|min:1',
            'idCategoria' => 'sometimes|exists:categorias,IDCategoria',
            'idZona' => 'nullable|exists:zonas,id',
            'Direccion' => 'nullable|string|max:255',
            'Latitud' => 'nullable|numeric',
            'Longitud' => 'nullable|numeric',
            'Activo' => 'sometimes|boolean',
            'foto' => 'sometimes|image|mimes:jpeg,png,jpg,webp|max:8096',
            'fotos.*' => 'sometimes|image|mimes:jpeg,png,jpg,webp|max:8096',
        ]);

        $usuario = $request->user();

        $servicio = Servicio::where('IDServicio', $id)
            ->where('idProveedor', $usuario->IDUsuario)
            ->firstOrFail();

        $servicio->update($request->only([
            'Nombre',
            'Descripcion',
            'Precio',
            'Duracion',
            'idCategoria',
            'idZona',
            'Direccion',
            'Latitud',
            'Longitud',
            'Activo'
        ]));

        if ($request->hasFile('foto')) {
            // Eliminar foto principal anterior o demarcarla?
            // Por simplicidad, desmarcamos la anterior principal
            ServicioFoto::where('idServicio', $servicio->IDServicio)
                ->where('EsPrincipal', true)
                ->update(['EsPrincipal' => false]);
                
            $path = $request->file('foto')->store('servicios', 'public');
            ServicioFoto::create([
                'idServicio' => $servicio->IDServicio,
                'RutaFoto' => $path,
                'EsPrincipal' => true,
            ]);
        }

        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $path = $foto->store('servicios', 'public');
                ServicioFoto::create([
                    'idServicio' => $servicio->IDServicio,
                    'RutaFoto' => $path,
                    'EsPrincipal' => false,
                ]);
            }
        }

        return response()->json($servicio->load(['categoria', 'fotoPrincipal']));
    }

    // Eliminar servicio
    public function destroyServicio(Request $request, $id)
    {
        $usuario = $request->user();

        $servicio = Servicio::where('IDServicio', $id)
            ->where('idProveedor', $usuario->IDUsuario)
            ->firstOrFail();

        // Verificar si tiene reservas activas
        $reservasActivas = Reserva::whereHas('detalles', function ($query) use ($id) {
            $query->where('idServicio', $id);
        })->whereIn('Estado', ['Pendiente', 'Confirmada'])->count();

        if ($reservasActivas > 0) {
            return response()->json([
                'message' => 'No se puede eliminar el servicio porque tiene reservas activas.'
            ], 422);
        }

        $servicio->delete();

        return response()->json([
            'message' => 'Servicio eliminado exitosamente.'
        ]);
    }

    // Listar reservas del proveedor
    public function reservas(Request $request)
    {
        $usuario = $request->user();

        $reservas = Reserva::with(['usuario', 'detalles.servicio'])
            ->whereHas('detalles.servicio', function ($query) use ($usuario) {
                $query->where('idProveedor', $usuario->IDUsuario);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($reservas);
    }

    // Estadísticas del proveedor
    public function estadisticas(Request $request)
    {
        $usuario = $request->user();

        $totalServicios = Servicio::where('idProveedor', $usuario->IDUsuario)->count();

        $totalReservas = Reserva::whereHas('detalles.servicio', function ($query) use ($usuario) {
            $query->where('idProveedor', $usuario->IDUsuario);
        })->count();

        $reservasPendientes = Reserva::whereHas('detalles.servicio', function ($query) use ($usuario) {
            $query->where('idProveedor', $usuario->IDUsuario);
        })->where('Estado', 'Pendiente')->count();

        $ingresosTotales = Reserva::whereHas('detalles.servicio', function ($query) use ($usuario) {
            $query->where('idProveedor', $usuario->IDUsuario);
        })->where('Estado', 'Completada')->sum('Total');

        return response()->json([
            'total_servicios' => $totalServicios,
            'total_reservas' => $totalReservas,
            'reservas_pendientes' => $reservasPendientes,
            'ingresos_totales' => $ingresosTotales,
        ]);
    }
}
