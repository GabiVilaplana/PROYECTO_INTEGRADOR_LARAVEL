<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Categoria;
use App\Models\ServicioFoto;
use App\Models\ServicioDisponibilidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Pago;
use App\Models\Reserva;

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
            'fotos.*' => 'image|max:2048',
            'foto_principal' => 'nullable|integer',
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

            // Si no hay fotos, podríamos poner una por defecto?
            // La lógica anterior ponía una de categoría si no había fotoPrincipal.

            if ($request->hasFile('fotos')) {
                $principalIndex = $request->input('foto_principal', 0);

                foreach ($request->file('fotos') as $index => $file) {
                    if ($file->isValid()) {
                        $path = $file->store('servicios', 'public');
                        ServicioFoto::create([
                            'idServicio' => $servicio->IDServicio,
                            'RutaFoto' => $path,
                            'EsPrincipal' => ($index == $principalIndex),
                        ]);
                    }
                }
            } else {
                // Si no subió ninguna foto, usar la de la categoría como principal
                $categoria = $servicio->categoria;
                ServicioFoto::create([
                    'idServicio' => $servicio->IDServicio,
                    'RutaFoto' => 'categorias/' . $categoria->Nombre . '.jpg',
                    'EsPrincipal' => true,
                ]);
            }

            // 3. Manejar Disponibilidad semanal
            if ($request->has('disponibilidad')) {
                foreach ($request->disponibilidad as $dia => $data) {
                    $activo = isset($data['activo']) && $data['activo'] == '1';

                    ServicioDisponibilidad::create([
                        'idServicio' => $servicio->IDServicio,
                        'dia_semana' => $dia,
                        'hora_inicio' => $data['inicio'] ?? '09:00',
                        'hora_fin' => $data['fin'] ?? '18:00',
                        'activo' => $activo
                    ]);
                }
            }
        });

        // Si es una petición web (no AJAX), redirigir
        if (!$request->expectsJson()) {
            return redirect()->route('servicios.show', $servicio->IDServicio)
                ->with('success', 'Servicio creado correctamente');
        }

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
            'foto_principal_existente' => 'nullable|exists:servicio_fotos,IDFoto',
            'fotos_nuevas' => 'nullable|array',
            'fotos_nuevas.*' => 'image|max:2048',
            'foto_principal_nueva' => 'nullable|integer',
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

            // 1. Manejar cambio de foto principal entre las existentes
            if ($request->filled('foto_principal_existente')) {
                ServicioFoto::where('idServicio', $servicio->IDServicio)->update(['EsPrincipal' => false]);
                ServicioFoto::where('IDFoto', $request->foto_principal_existente)->update(['EsPrincipal' => true]);
            }

            // 2. Manejar nuevas fotos
            if ($request->hasFile('fotos_nuevas')) {
                $nuevaPrincipalIndex = $request->input('foto_principal_nueva');

                foreach ($request->file('fotos_nuevas') as $index => $file) {
                    if ($file->isValid()) {
                        $path = $file->store('servicios', 'public');

                        $isPrincipal = (isset($nuevaPrincipalIndex) && $index == $nuevaPrincipalIndex);

                        if ($isPrincipal) {
                            // Si esta nueva es la principal, quitamos principal a todas las demás
                            ServicioFoto::where('idServicio', $servicio->IDServicio)->update(['EsPrincipal' => false]);
                        }

                        ServicioFoto::create([
                            'idServicio' => $servicio->IDServicio,
                            'RutaFoto' => $path,
                            'EsPrincipal' => $isPrincipal,
                        ]);
                    }
                }
            }

            // 3. Manejar Disponibilidad semanal
            if ($request->has('disponibilidad')) {
                foreach ($request->disponibilidad as $dia => $data) {
                    $activo = isset($data['activo']) && $data['activo'] == '1';

                    ServicioDisponibilidad::updateOrCreate(
                        ['idServicio' => $servicio->IDServicio, 'dia_semana' => $dia],
                        [
                            'hora_inicio' => $data['inicio'] ?? '09:00',
                            'hora_fin' => $data['fin'] ?? '18:00',
                            'activo' => $activo
                        ]
                    );
                }
            }
        });

        if (!$request->expectsJson()) {
            return redirect()->route('servicios.show', $servicio->IDServicio)
                ->with('success', 'Servicio actualizado correctamente');
        }

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

    public function buscar(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric|min:-90|max:90',
            'lng' => 'required|numeric|min:-180|max:180',
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
            ->where('Activo', true)
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->where('radio_km', '>', 0)
            ->having('distancia', '<=', DB::raw('radio_km'))
            ->orderBy('distancia')
            ->with(['categoria', 'proveedor', 'fotoPrincipal'])
            ->limit(50)
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

    public function confirmarPago(Request $request) 
    {
        // 1. Validamos (usando los nombres de tus claves foráneas)
        $request->validate([
            'idReserva'  => 'required|exists:reservas,IDReserva',
            'metodoPago' => 'required|string',
            'importe'    => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            // 2. Crear el registro del pago
            $pago = Pago::create([
                'idReserva'  => $request->idReserva,
                'MetodoPago' => $request->metodoPago,
                'Estado'     => 'completado',
                'Importe'    => $request->importe,
                'FechaPago'  => now(),
            ]);

            // 3. Obtener la reserva con el usuario y los nombres de los servicios
            // Cargamos 'usuario' y 'detalles.servicio' (relación anidada)
            $reserva = Reserva::with(['usuario', 'detalles.servicio'])
                            ->findOrFail($request->idReserva);

            // 4. Preparar los nombres de los servicios contratados
            // Como puede haber varios detalles, los unimos con una coma
            $nombresServicios = $reserva->detalles->map(function($detalle) {
                return $detalle->servicio->Nombre; 
            })->implode(', ');


            DB::commit();

            return response()->json([
                'message' => 'Pago procesado y notificado',
                'pago_id' => $pago->IDPago
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function compraRapida(Request $request) 
{
    // 1. Verificar si el usuario está logueado (desde la API)
    if (!auth()->check()) {
        return response()->json(['redirect' => route('login')], 401);
    }

    $request->validate([
        'idServicio' => 'required|exists:servicios,IDServicio',
    ]);

    try {
        DB::beginTransaction();
        $user = auth()->user();
        $servicio = Servicio::findOrFail($request->idServicio);

        // 2. Crear la Reserva automáticamente
        $reserva = Reserva::create([
            'idUsuario'     => $user->IDUsuario,
            'FechaReserva'  => now(),
            'Estado'        => 'confirmada',
            'Total'         => $servicio->Precio,
        ]);

        // 3. Crear el Detalle de la reserva
        $reserva->detalles()->create([
            'idServicio'    => $servicio->IDServicio,
            'Precio'        => $servicio->Precio,
            'FechaServicio' => now()->addDays(1), // Fecha por defecto: mañana
            'HoraServicio'  => '10:00:00',
        ]);

        // 4. Crear el Pago
        $pago = Pago::create([
            'idReserva'  => $reserva->IDReserva,
            'MetodoPago' => 'compra_rapida',
            'Estado'     => 'completado',
            'Importe'    => $servicio->Precio,
            'FechaPago'  => now(),
        ]);


        DB::commit();

        return response()->json([
            'status' => 'success',
            'message' => '¡Compra realizada con éxito! Revisa tu correo.'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}
