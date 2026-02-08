<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        return response()->json($servicio);
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
            if ($servicio->fotoPrincipal && $servicio->fotoPrincipal->RutaFoto) {
                $servicio->fotoPrincipal->url = asset('storage/' . ltrim($servicio->fotoPrincipal->RutaFoto, '/'));
            }
        });

        return response()->json($servicios);
    }
}