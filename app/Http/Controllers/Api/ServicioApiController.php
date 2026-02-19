<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Storage;

class ServicioApiController extends Controller
{
    public function index()
    {
        $servicios = Servicio::with([
            'categoria',
            'proveedor',
            'fotoPrincipal',
            'fotos',
            'disponibilidades'
        ])->where('Activo', true)->get();

        return response()->json($servicios);
    }

    public function show($id)
    {
        $servicio = Servicio::with([
            'categoria',
            'proveedor',
            'fotos',
            'fotoPrincipal',
            'valoraciones.usuario',
            'disponibilidades',
            'zona'
        ])->findOrFail($id);

        return response()->json([
            '_debug_endpoint' => 'Api/ServicioApiController@show',
            'IDServicio' => $servicio->IDServicio,
            'Nombre' => $servicio->Nombre,
            'Descripcion' => $servicio->Descripcion,
            'Precio' => $servicio->Precio,
            'Duracion' => $servicio->Duracion,
            'Activo' => $servicio->Activo,
            'lat' => $servicio->lat,
            'lng' => $servicio->lng,
            'radio_km' => $servicio->radio_km,
            'idZona' => $servicio->idZona,
            'ruta' => $servicio->url_mapas,

            // Relaciones
            'categoria' => $servicio->categoria,
            'zona' => $servicio->zona,
            'proveedor' => $servicio->proveedor,
            'fotos' => $servicio->fotos->map(function ($foto) {
                return [
                    'IDFoto' => $foto->IDFoto,
                    'RutaFoto' => $foto->RutaFoto,
                    'EsPrincipal' => $foto->EsPrincipal,
                    'Url' => $foto->url,
                ];
            }),
            'valoraciones' => $servicio->valoraciones,
            'disponibilidad' => $servicio->disponibilidades->map(function ($disponibilidad) {
                return [
                    'IDDisponibilidad' => $disponibilidad->IDDisponibilidad,
                    'dia_semana' => $disponibilidad->dia_semana,
                    'hora_inicio' => $disponibilidad->hora_inicio,
                    'hora_fin' => $disponibilidad->hora_fin,
                    'activo' => $disponibilidad->activo,
                ];
            }),
            'ImagenUrl' => $servicio->imagen_url,
            'PromedioValoracion' => $servicio->promedio_valoracion,
        ]);
    }


    // app/Http/Controllers/Api/ServicioApiController.php

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

        // Asegúrate de que las URLs de fotos sean accesibles
        $servicios->each(function ($servicio) {
            // we don't need to manually set it anymore because it's in $appends
        });

        return response()->json($servicios);
    }
    public function porCategoriaId(int $idCategoria)
    {
        $categoria = Categoria::where('IDCategoria', $idCategoria)
            ->where('Activa', true)
            ->first();

        if (!$categoria) {
            return response()->json([
                'message' => 'Categoría no encontrada o inactiva'
            ], 404);
        }

        $servicios = Servicio::with(['categoria', 'proveedor', 'fotoPrincipal'])
            ->where('idCategoria', $idCategoria)
            ->where('Activo', true)
            ->get();

        return response()->json($servicios);
    }

    public function getSlots(Request $request, $id)
    {
        $request->validate([
            'fecha' => 'required|date_format:Y-m-d|after_or_equal:today',
        ]);

        $servicio = Servicio::findOrFail($id);
        $slots = $servicio->getAvailableSlots($request->fecha);

        return response()->json($slots);
    }
}