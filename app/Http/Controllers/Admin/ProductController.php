<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Filtro por categoría
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filtro por búsqueda (nombre o código)
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        // Filtro por estado
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $products = $query->orderBy('name')->paginate(15);
        $categories = Category::active()->orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0.01',
            'cost' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'is_food' => 'boolean',
            'preparation_time' => 'nullable|string',
        ]);

        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active');
        $data['is_food'] = $request->has('is_food') ? $request->is_food : true;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')
                        ->with('success', 'Platillo creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('category');
        
        // Ventas recientes del Platillo
        $recentSales = $product->saleDetails()
            ->with('sale')
            ->whereHas('sale', function($query) {
                $query->where('status', 'completed');
            })
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Estadísticas del Platillo (últimos 30 días)
        $totalQuantitySold = $product->saleDetails()
            ->whereHas('sale', function($query) {
                $query->where('status', 'completed')
                      ->where('created_at', '>=', now()->subDays(30));
            })
            ->sum('quantity');

        $totalRevenue = $product->saleDetails()
            ->whereHas('sale', function($query) {
                $query->where('status', 'completed')
                      ->where('created_at', '>=', now()->subDays(30));
            })
            ->sum('subtotal');

        $salesCount = $product->saleDetails()
            ->whereHas('sale', function($query) {
                $query->where('status', 'completed')
                      ->where('created_at', '>=', now()->subDays(30));
            })
            ->count();

        $averageQuantityPerSale = $salesCount > 0 ? $totalQuantitySold / $salesCount : 0;

        return view('admin.products.show', compact(
            'product', 
            'recentSales', 
            'totalQuantitySold', 
            'totalRevenue', 
            'averageQuantityPerSale'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0.01',
            'cost' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'is_food' => 'boolean',
            'preparation_time' => 'nullable|string',
        ]);

        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active');
        $data['is_food'] = $request->has('is_food') ? $request->is_food : true;

        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
                        ->with('success', 'Platillo actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Verificar si el Platillo tiene ventas asociadas
        if ($product->saleDetails()->count() > 0) {
            // En lugar de eliminar, desactivar el Platillo
            $product->update(['is_active' => false]);
            
            return redirect()->route('admin.products.index')
                            ->with('warning', 'El Platillo tiene ventas asociadas, por lo que se ha desactivado en lugar de eliminarse. Los Platillos desactivados no aparecen en el punto de venta pero mantienen el historial de ventas.');
        }

        // Solo eliminar si no tiene ventas (Platillos nuevos sin historial)
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
                        ->with('success', 'Platillo eliminado exitosamente.');
    }
}
