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
        ->having('distancia', '<=', DB::raw('radio_km'))
        ->orderBy('distancia')
        ->with(['categoria','proveedor','fotoPrincipal'])
        ->get();

        return response()->json($servicios);
    }
}