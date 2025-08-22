<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Combo;
use App\Models\Product;
use Illuminate\Http\Request;

class ComboController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $combos = Combo::with('products')->latest()->paginate(10);
        return view('admin.combos.index', compact('combos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        return view('admin.combos.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'min_items' => 'required|integer|min:2',
            'products' => 'required|array|min:2',
            'products.*' => 'exists:products,id',
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:1',
        ]);

        // Calcular precio original (suma de productos individuales)
        $originalPrice = 0;
        $selectedProducts = Product::whereIn('id', $request->products)->get();
        
        foreach ($selectedProducts as $product) {
            $quantity = $request->quantities[$product->id] ?? 1;
            $originalPrice += ($product->price * $quantity);
        }

        $combo = Combo::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'original_price' => $originalPrice,
            'discount_amount' => $originalPrice - $request->price,
            'min_items' => $request->min_items,
            'auto_suggest' => $request->has('auto_suggest'),
            'is_active' => $request->has('is_active'),
        ]);

        // Asociar productos al combo
        foreach ($request->products as $productId) {
            $combo->products()->attach($productId, [
                'quantity' => $request->quantities[$productId] ?? 1,
                'is_required' => in_array($productId, $request->required_products ?? []),
                'is_alternative' => in_array($productId, $request->alternative_products ?? []),
            ]);
        }

        return redirect()->route('admin.combos.index')
                        ->with('success', 'Combo creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Combo $combo)
    {
        $combo->load('products');
        return view('admin.combos.show', compact('combo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Combo $combo)
    {
        $combo->load('products');
        $products = Product::all();
        return view('admin.combos.edit', compact('combo', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Combo $combo)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'min_items' => 'required|integer|min:2',
            'products' => 'required|array|min:2',
            'products.*' => 'exists:products,id',
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:1',
        ]);

        // Recalcular precio original
        $originalPrice = 0;
        $selectedProducts = Product::whereIn('id', $request->products)->get();
        
        foreach ($selectedProducts as $product) {
            $quantity = $request->quantities[$product->id] ?? 1;
            $originalPrice += ($product->price * $quantity);
        }

        $combo->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'original_price' => $originalPrice,
            'discount_amount' => $originalPrice - $request->price,
            'min_items' => $request->min_items,
            'auto_suggest' => $request->has('auto_suggest'),
            'is_active' => $request->has('is_active'),
        ]);

        // Sincronizar productos
        $syncData = [];
        foreach ($request->products as $productId) {
            $syncData[$productId] = [
                'quantity' => $request->quantities[$productId] ?? 1,
                'is_required' => in_array($productId, $request->required_products ?? []),
                'is_alternative' => in_array($productId, $request->alternative_products ?? []),
            ];
        }
        
        $combo->products()->sync($syncData);

        return redirect()->route('admin.combos.index')
                        ->with('success', 'Combo actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Combo $combo)
    {
        $combo->delete();
        return redirect()->route('admin.combos.index')
                        ->with('success', 'Combo eliminado exitosamente');
    }

    /**
     * Toggle combo status
     */
    public function toggleStatus(Combo $combo)
    {
        $combo->update(['is_active' => !$combo->is_active]);
        
        $status = $combo->is_active ? 'activado' : 'desactivado';
        return back()->with('success', "Combo {$status} exitosamente");
    }
}
