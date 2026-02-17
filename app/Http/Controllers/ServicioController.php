<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\ServicioFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\N8nService; // Importamos tu nueva clase
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

            // 5. Enviar a n8n
            // dd(env('N8N_WEBHOOK_COMPRA_URL')); 

            N8nService::enviarConfirmacionCompra([
                'email'    => $reserva->usuario->email, // Asegúrate si en Usuario es 'email' o 'Email'
                'nombre'   => $reserva->usuario->Nombre,
                'servicio' => $nombresServicios,
                'precio'   => $pago->Importe,
            ]);

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

        // 5. Notificar a n8n
        N8nService::enviarConfirmacionCompra([
            'email'    => $user->email,
            'nombre'   => $user->Nombre,
            'servicio' => $servicio->Nombre,
            'precio'   => $servicio->Precio,
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
