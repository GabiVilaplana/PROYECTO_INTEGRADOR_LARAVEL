<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FaqCategory;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqApiController extends Controller
{
    public function index()
    {
        $categories = FaqCategory::with('faqs')->get();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'idFaqCategoria' => 'required|exists:faq_categorias,IDFaqCategoria',
            'Pregunta' => 'required|string',
            'Respuesta' => 'required|string',
        ]);

        $faq = Faq::create($validated);
        return response()->json($faq, 201);
    }

    public function update(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);
        $validated = $request->validate([
            'idFaqCategoria' => 'sometimes|required|exists:faq_categorias,IDFaqCategoria',
            'Pregunta' => 'sometimes|required|string',
            'Respuesta' => 'sometimes|required|string',
        ]);

        $faq->update($validated);
        return response()->json($faq);
    }

    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();
        return response()->json(null, 204);
    }

    // Categories
    public function categories()
    {
        return response()->json(FaqCategory::all());
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'Nombre' => 'required|string|unique:faq_categorias,Nombre',
            'Icono' => 'nullable|string',
        ]);

        $category = FaqCategory::create($validated);
        return response()->json($category, 201);
    }

    public function updateCategory(Request $request, $id)
    {
        $category = FaqCategory::findOrFail($id);
        $validated = $request->validate([
            'Nombre' => 'sometimes|required|string|unique:faq_categorias,Nombre,' . $id . ',IDFaqCategoria',
            'Icono' => 'nullable|string',
        ]);

        $category->update($validated);
        return response()->json($category);
    }

    public function destroyCategory($id)
    {
        $category = FaqCategory::findOrFail($id);
        // Cascading delete is already handled in migration if set up correctly, 
        // but Faqs depends on Category.
        $category->delete();
        return response()->json(null, 204);
    }
}
