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

        $servicios = Servicio::with(['categoria', 'fotoPrincipal', 'valoraciones', 'zona'])
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
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'foto' => 'sometimes|image|mimes:jpeg,png,jpg,webp|max:8096',
            'fotos.*' => 'sometimes|image|mimes:jpeg,png,jpg,webp|max:8096',
            'disponibilidad' => 'nullable|string',
        ]);

        $usuario = $request->user();

        $lat = $request->lat;
        $lng = $request->lng;

        if ($request->filled('idZona') && (empty($lat) || empty($lng))) {
            $zona = \App\Models\Zona::find($request->idZona);
            if ($zona) {
                $lat = $zona->lat;
                $lng = $zona->lng;
            }
        }

        $servicio = DB::transaction(function () use ($request, $usuario, $lat, $lng) {
            $servicio = Servicio::create([
                'Nombre' => $request->Nombre,
                'Descripcion' => $request->Descripcion,
                'Precio' => $request->Precio,
                'Duracion' => $request->Duracion,
                'idCategoria' => $request->idCategoria,
                'idZona' => $request->idZona,
                'idProveedor' => $usuario->IDUsuario,
                'Direccion' => $request->Direccion,
                'lat' => $lat,
                'lng' => $lng,
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

            // Manejar disponibilidad
            if ($request->filled('disponibilidad')) {
                $disponibilidades = is_string($request->disponibilidad)
                    ? json_decode($request->disponibilidad, true)
                    : $request->disponibilidad;

                if (is_array($disponibilidades)) {
                    foreach ($disponibilidades as $disp) {
                        ServicioDisponibilidad::create([
                            'idServicio' => $servicio->IDServicio,
                            'dia_semana' => $disp['dia_semana'],
                            'hora_inicio' => $disp['hora_inicio'],
                            'hora_fin' => $disp['hora_fin'],
                            'activo' => $disp['activo'] ?? true,
                        ]);
                    }
                }
            }

            return $servicio->load(['categoria', 'fotoPrincipal', 'disponibilidades']);
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
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'Activo' => 'sometimes|boolean',
            'foto' => 'sometimes|image|mimes:jpeg,png,jpg,webp|max:8096',
            'fotos.*' => 'sometimes|image|mimes:jpeg,png,jpg,webp|max:8096',
            'disponibilidad' => 'nullable|string',
        ]);

        $usuario = $request->user();

        $servicio = Servicio::where('IDServicio', $id)
            ->where('idProveedor', $usuario->IDUsuario)
            ->firstOrFail();

        $lat = $request->lat ?? $servicio->lat;
        $lng = $request->lng ?? $servicio->lng;

        if ($request->filled('idZona') && ($request->idZona != $servicio->idZona) && (!$request->has('lat') || !$request->has('lng'))) {
            $zona = \App\Models\Zona::find($request->idZona);
            if ($zona) {
                $lat = $zona->lat;
                $lng = $zona->lng;
            }
        }

        $servicio->update($request->only([
            'Nombre',
            'Descripcion',
            'Precio',
            'Duracion',
            'idCategoria',
            'idZona',
            'Direccion',
            'Activo'
        ]));

        $servicio->lat = $lat;
        $servicio->lng = $lng;
        $servicio->save();

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

        // Manejar disponibilidad
        if ($request->has('disponibilidad')) {
            $disponibilidades = is_string($request->disponibilidad)
                ? json_decode($request->disponibilidad, true)
                : $request->disponibilidad;

            if (is_array($disponibilidades)) {
                // Sincronizar: eliminar las anteriores y añadir las nuevas
                ServicioDisponibilidad::where('idServicio', $servicio->IDServicio)->delete();

                foreach ($disponibilidades as $disp) {
                    ServicioDisponibilidad::create([
                        'idServicio' => $servicio->IDServicio,
                        'dia_semana' => $disp['dia_semana'],
                        'hora_inicio' => $disp['hora_inicio'],
                        'hora_fin' => $disp['hora_fin'],
                        'activo' => $disp['activo'] ?? true,
                    ]);
                }
            }
        }

        return response()->json($servicio->load(['categoria', 'fotoPrincipal', 'disponibilidades']));
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

        $reservas = Reserva::with(['usuario', 'detalles.servicio.zona'])
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

    // Actualizar estado de una reserva (para el proveedor)
    public function updateReservaEstado(Request $request, $id)
    {
        $request->validate([
            'Estado' => 'required|in:Confirmada,Cancelada',
        ]);

        $usuario = $request->user();

        // Buscar la reserva asegurándose de que pertenece a un servicio del proveedor
        $reserva = Reserva::where('IDReserva', $id)
            ->whereHas('detalles.servicio', function ($query) use ($usuario) {
                $query->where('idProveedor', $usuario->IDUsuario);
            })
            ->firstOrFail();

        $reserva->Estado = $request->Estado;
        $reserva->save();

        return response()->json([
            'message' => 'Estado de la reserva actualizado correctamente.',
            'reserva' => $reserva->load(['usuario', 'detalles.servicio'])
        ]);
    }
}
