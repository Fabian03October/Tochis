<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductCustomizationOption;
use App\Models\Product;
use Illuminate\Http\Request;

class CustomizationController extends Controller
{
    public function getOptions(Request $request)
    {
        // Si se especifica un Platillo, obtener opciones específicas de su categoría
        if ($request->has('product_id')) {
            $product = Product::with('category.customizationOptions')->find($request->product_id);
            
            if ($product && $product->category && $product->category->is_customizable) {
                $options = $product->category->activeCustomizationOptions;
                
                $observations = $options->where('type', 'observation')->values();
                $specialties = $options->where('type', 'specialty')->values();
                
                return response()->json([
                    'observations' => $observations->map(function($option) {
                        return [
                            'id' => $option->id,
                            'name' => $option->name,
                            'price' => $option->price
                        ];
                    }),
                    'specialties' => $specialties->map(function($option) {
                        return [
                            'id' => $option->id,
                            'name' => $option->name,
                            'price' => $option->price
                        ];
                    })
                ]);
            }
        }
        
        // Fallback: devolver todas las opciones activas
        $observations = ProductCustomizationOption::observations()
            ->active()
            ->ordered()
            ->select('id', 'name', 'price')
            ->get();
            
        $specialties = ProductCustomizationOption::specialties()
            ->active()
            ->ordered()
            ->select('id', 'name', 'price')
            ->get();
            
        return response()->json([
            'observations' => $observations,
            'specialties' => $specialties
        ]);
    }

    public function getProductOptions(Product $product)
    {
        $observations = $product->observationOptions()->where('is_active', true)->get();
        $specialties = $product->specialtyOptions()->where('is_active', true)->get();
        
        return response()->json([
            'observations' => $observations->map(function($option) {
                return [
                    'id' => $option->id,
                    'name' => $option->name,
                    'price' => $option->price
                ];
            }),
            'specialties' => $specialties->map(function($option) {
                return [
                    'id' => $option->id,
                    'name' => $option->name,
                    'price' => $option->price
                ];
            })
        ]);
    }
}
