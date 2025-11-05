<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promotions = Promotion::with('creator')
                               ->orderBy('created_at', 'desc')
                               ->paginate(10);
        
        return view('admin.promotions.index', compact('promotions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::active()->orderBy('name')->get();
        $products = Product::active()->orderBy('name')->get();
        
        return view('admin.promotions.create', compact('categories', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:percentage,fixed_amount',
            'discount_value' => 'required|numeric|min:0.01',
            'apply_to' => 'required|in:all,category,product',
            'applicable_items' => 'required_if:apply_to,category,product|array',
            'minimum_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'start_date' => 'required|date|after_or_equal:today',
            'duration_type' => 'required|in:hours,days',
            'duration_value' => 'required|integer|min:1',
        ]);

        // Calcular fecha de fin basada en duración
        $startDate = Carbon::parse($request->start_date);
        $durationValue = (int) $request->duration_value;
        $endDate = $request->duration_type === 'hours' 
                    ? $startDate->copy()->addHours($durationValue)
                    : $startDate->copy()->addDays($durationValue);

        // Validaciones adicionales por tipo
        if ($request->type === 'percentage' && $request->discount_value > 100) {
            return back()->withErrors(['discount_value' => 'El porcentaje no puede ser mayor al 100%'])->withInput();
        }

        $promotion = Promotion::create([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'discount_value' => $request->discount_value,
            'apply_to' => $request->apply_to,
            'minimum_amount' => $request->minimum_amount ?? 0,
            'max_uses' => $request->max_uses,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'created_by' => auth()->id(),
        ]);

        // Asociar categorías o productos si aplica
        if ($request->apply_to === 'category' && $request->has('applicable_items')) {
            $promotion->categories()->attach($request->applicable_items);
        } elseif ($request->apply_to === 'product' && $request->has('applicable_items')) {
            $promotion->products()->attach($request->applicable_items);
        }

        return redirect()->route('admin.promotions.index')
                        ->with('success', 'Promoción creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Promotion $promotion)
    {
        $promotion->load('creator');
        return view('admin.promotions.show', compact('promotion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Promotion $promotion)
    {
        $categories = Category::active()->orderBy('name')->get();
        $products = Product::active()->orderBy('name')->get();
        
        return view('admin.promotions.edit', compact('promotion', 'categories', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        $promotion->update([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->boolean('is_active')
        ]);

        return redirect()->route('admin.promotions.index')
                        ->with('success', 'Promoción actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Promotion $promotion)
    {
        $promotion->delete();

        return redirect()->route('admin.promotions.index')
                        ->with('success', 'Promoción eliminada exitosamente.');
    }

    /**
     * Toggle active status of promotion.
     */
    public function toggleStatus(Promotion $promotion)
    {
        $promotion->update(['is_active' => !$promotion->is_active]);

        $status = $promotion->is_active ? 'activada' : 'desactivada';
        return back()->with('success', "Promoción {$status} exitosamente.");
    }
}
