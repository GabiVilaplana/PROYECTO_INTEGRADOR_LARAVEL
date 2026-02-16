<?php

namespace App\Http\Controllers;

use App\Models\Zona;
use Illuminate\Http\Request;

class ZonaController extends Controller
{
    // Listar todas las zonas (para selector)
    public function index()
    {
        return Zona::orderBy('nombre')->get(['id', 'nombre', 'slug', 'lat', 'lng']);
    }

    // Autocomplete (búsqueda por nombre)
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        return Zona::where('nombre', 'like', "%{$query}%")
            ->orderBy('nombre')
            ->limit(10)
            ->get(['id', 'nombre', 'slug', 'lat', 'lng']);
    }

    // Obtener una zona por slug (ej: /api/zonas/madrid)
    public function show($slug)
    {
        return Zona::where('slug', $slug)->firstOrFail();
    }
}