<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Get product options for customization
     */
    public function getOptions($id)
    {
        try {
            $product = Product::with(['category.customizationOptions' => function($query) {
                $query->where('is_active', true)->orderBy('sort_order');
            }])->findOrFail($id);
            
            // Verificar si la categoría es personalizable
            if (!$product->category || !$product->category->is_customizable) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'observations' => [],
                        'specialties' => []
                    ]
                ]);
            }
            
            // Obtener opciones de personalización de la categoría
            $options = $product->category->customizationOptions;
            
            // Separar opciones por tipo
            $observations = $options->where('type', 'observation')->values();
            $specialties = $options->where('type', 'specialty')->values();
            
            return response()->json([
                'success' => true,
                'data' => [
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
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Platillo no encontrado',
                'data' => [
                    'observations' => [],
                    'specialties' => []
                ]
            ], 404);
        }
    }
}
