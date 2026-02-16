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
            'fotos'
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
            'valoraciones'
        ])->findOrFail($id);

        return response()->json([
            'IDServicio' => $servicio->IDServicio,
            'Nombre' => $servicio->Nombre,
            'Descripcion' => $servicio->Descripcion,
            'Precio' => $servicio->Precio,
            'Duracion' => $servicio->Duracion,
            'Activo' => $servicio->Activo,
            'lat' => $servicio->lat,
            'lng' => $servicio->lng,
            'radio_km' => $servicio->radio_km,

            // Relaciones
            'categoria' => $servicio->categoria,
            'proveedor' => $servicio->proveedor,
            'fotos' => $servicio->fotos,
            'valoraciones' => $servicio->valoraciones,

            // Imagen calculada centralizadamente en el modelo
            'ImagenUrl' => $servicio->imagen_url,
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
            // so we just ensure it's loaded properly.
            // But we can force it to be clear for the user:
            $servicio->imagen_url = $servicio->imagen_url;
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
}