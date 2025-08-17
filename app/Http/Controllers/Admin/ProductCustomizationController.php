<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCustomizationOption;
use App\Models\Category;

class ProductCustomizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $observations = ProductCustomizationOption::observations()->active()->ordered()->get();
        $specialties = ProductCustomizationOption::specialties()->active()->ordered()->get();
        $categories = Category::active()->get();
        
        return view('admin.customization.index', compact('observations', 'specialties', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:observation,specialty',
            'price' => 'nullable|numeric|min:0',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        ProductCustomizationOption::create([
            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price ?? 0,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => true
        ]);

        return response()->json(['success' => true, 'message' => 'Opción creada exitosamente']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductCustomizationOption $customization)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $customization->update([
            'name' => $request->name,
            'price' => $request->price ?? 0,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active')
        ]);

        return response()->json(['success' => true, 'message' => 'Opción actualizada exitosamente']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCustomizationOption $customization)
    {
        $customization->delete();
        return response()->json(['success' => true, 'message' => 'Opción eliminada exitosamente']);
    }

    /**
     * Update category customization settings
     */
    public function updateCategoryCustomization(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'is_customizable' => 'boolean'
        ]);

        $category = Category::findOrFail($request->category_id);
        $category->update([
            'is_customizable' => $request->has('is_customizable')
        ]);

        return response()->json(['success' => true, 'message' => 'Configuración de categoría actualizada']);
    }
}
