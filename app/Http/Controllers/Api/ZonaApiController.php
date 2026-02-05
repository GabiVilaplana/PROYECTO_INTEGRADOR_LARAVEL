<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Zona;
use Illuminate\Http\Request;

class ZonaApiController extends Controller
{
    public function index()
    {
        return Zona::orderBy('nombre')->get(['id', 'nombre', 'slug']);
    }

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

    public function show($slug)
    {
        return Zona::where('slug', $slug)->firstOrFail();
    }
}