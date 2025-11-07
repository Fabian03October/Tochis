<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ProductCustomizationOption;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('products')->orderBy('name')->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:500',
            'color' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
        ]);

        Category::create($request->all());

        return redirect()->route('admin.categories.index')
                        ->with('success', 'Categoría creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category->load(['products' => function($query) {
            $query->orderBy('name');
        }]);
        
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
            'color' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'is_active' => 'sometimes|boolean',
            'is_customizable' => 'sometimes|boolean',
        ]);

        // Log para debugging (remover en producción)
        \Log::info('Actualizando categoría', [
            'category_id' => $category->id,
            'request_data' => $request->all(),
            'is_active_before' => $category->is_active,
        ]);

        $category->update($request->all());

        // Log después de actualizar
        \Log::info('Categoría actualizada', [
            'category_id' => $category->id,
            'is_active_after' => $category->fresh()->is_active,
        ]);

        return redirect()->route('admin.categories.index')
                        ->with('success', 'Categoría actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                            ->with('error', 'No se puede eliminar la categoría porque tiene Platillos asociados.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
                        ->with('success', 'Categoría eliminada exitosamente.');
    }

    /**
     * Show the form for managing category customization options
     */
    public function customizationOptions(Category $category)
    {
        $allOptions = ProductCustomizationOption::active()->ordered()->get();
        $categoryOptions = $category->customizationOptions->pluck('id')->toArray();
        
        return view('admin.categories.customization-options', compact('category', 'allOptions', 'categoryOptions'));
    }

    /**
     * Update category customization options
     */
    public function updateCustomizationOptions(Request $request, Category $category)
    {
        $request->validate([
            'customization_options' => 'nullable|array',
            'customization_options.*' => 'exists:product_customization_options,id'
        ]);

        // Sync the customization options
        $category->customizationOptions()->sync($request->customization_options ?? []);

        return redirect()->route('admin.categories.customization-options', $category)
                        ->with('success', 'Opciones de personalización actualizadas exitosamente.');
    }
}
