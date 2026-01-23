<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    /**
     * Devuelve la vista principal donde estará el carrusel.
     */
    public function index()
    {
        // Si quieres pasar las categorías directamente con Blade (opcional)
        $categorias = Categoria::where('Activa', 1)->get();
        return view('home', compact('categorias'));
    }

    /**
     * Devuelve todas las categorías activas en formato JSON
     * para que el JS del carrusel las pueda consumir.
     */
    public function categoriasJson()
    {
        $categorias = Categoria::where('Activa', 1)->get();
        return response()->json($categorias);
    }

    /**
     * (Opcional) Método para crear una nueva categoría vía formulario
     */
    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|string|max:255',
            'Descripcion' => 'nullable|string',
            'Color' => 'nullable|string|max:7',
            'Imagen' => 'nullable|image|max:2048',
            'Activa' => 'required|boolean',
        ]);

        $data = $request->only(['Nombre', 'Descripcion', 'Color', 'Activa']);

        if ($request->hasFile('Imagen')) {
            $path = $request->file('Imagen')->store('categorias', 'public');
            $data['Imagen'] = $path;
        } else {
            $data['Imagen'] = 'categorias/default.jpg'; // imagen por defecto
        }

        $categoria = Categoria::create($data);

        return redirect()->back()->with('success', 'Categoría creada correctamente');
    }
}
