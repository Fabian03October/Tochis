<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCustomizationOption;
use Illuminate\Http\Request;

class CustomizationOptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $observations = ProductCustomizationOption::observations()
            ->active()
            ->ordered()
            ->get();
            
        $specialties = ProductCustomizationOption::specialties()
            ->active()
            ->ordered()
            ->get();
            
        return view('admin.customization-options.index', compact('observations', 'specialties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.customization-options.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:observation,specialty',
            'price' => 'required|numeric|min:0',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        ProductCustomizationOption::create([
            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => true
        ]);

        return redirect()->route('admin.customization-options.index')
            ->with('success', 'Opción de personalización creada exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductCustomizationOption $customizationOption)
    {
        return view('admin.customization-options.edit', compact('customizationOption'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductCustomizationOption $customizationOption)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:observation,specialty',
            'price' => 'required|numeric|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $customizationOption->update([
            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price,
            'sort_order' => $request->sort_order ?? $customizationOption->sort_order,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.customization-options.index')
            ->with('success', 'Opción de personalización actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCustomizationOption $customizationOption)
    {
        $customizationOption->delete();

        return redirect()->route('admin.customization-options.index')
            ->with('success', 'Opción de personalización eliminada exitosamente.');
    }
}
