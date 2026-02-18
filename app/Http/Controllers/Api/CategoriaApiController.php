<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoriaApiController extends Controller
{
    /**
     * Devuelve todas las categorías activas.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $categorias = Categoria::where('Activa', true)
            ->select('IDCategoria', 'Nombre', 'Descripcion', 'Color', 'Imagen') // ← aquí
            ->get();

        return response()->json($categorias);
    }

    public function buscar(Request $request): JsonResponse
    {
        $query = $request->input('q', '');

        $categorias = Categoria::where('Activa', true)
            ->when($query, function ($builder, $search) {
                return $builder->where('Nombre', 'LIKE', "%{$search}%");
            })
            ->select('IDCategoria', 'Nombre', 'Descripcion', 'Color', 'Imagen') // ← y aquí
            ->get();

        return response()->json($categorias);
    }
}