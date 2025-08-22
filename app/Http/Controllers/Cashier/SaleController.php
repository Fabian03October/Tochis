<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Promotion;
use App\Models\Combo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        $categories = Category::active()->with(['activeProducts' => function($query) {
            // No filtrar por stock para productos de comida, solo para productos regulares
            $query->where(function($q) {
                $q->where('is_food', true) // Productos de comida siempre disponibles
                  ->orWhere('stock', '>', 0); // Productos regulares con stock
            })->orderBy('name');
        }])->orderBy('name')->get();

        return view('cashier.sale.index', compact('categories'));
    }

    public function store(Request $request)
    {
        \Log::info('🚀 SaleController::store called', [
            'method' => $request->method(),
            'url' => $request->url(),
            'input' => $request->all(),
            'user_id' => auth()->id()
        ]);

        try {
            // Filtrar descuentos antes de la validación
            $requestData = $request->all();
            $discountAmount = 0;
            
            \Log::info('📦 Productos originales recibidos', [
                'total_products' => count($requestData['products'] ?? []),
                'products_detail' => $requestData['products'] ?? []
            ]);
            
            // Separar productos reales de descuentos
            if (isset($requestData['products'])) {
                $realProducts = [];
                foreach ($requestData['products'] as $index => $product) {
                    \Log::info("📋 Analizando producto en posición {$index}", [
                        'id' => $product['id'] ?? 'sin_id',
                        'name' => $product['name'] ?? 'sin_nombre',
                        'price' => $product['price'] ?? 0,
                        'quantity' => $product['quantity'] ?? 1
                    ]);
                    
                    // Detectar si es un descuento basado en el ID o nombre
                    $isDiscount = (
                        isset($product['id']) && (
                            strpos($product['id'], 'combo_discount_') === 0 ||
                            strpos($product['id'], 'combo-discount-') === 0 ||
                            strpos($product['id'], 'discount_') === 0 ||
                            strpos($product['id'], 'discount-') === 0 ||
                            strpos($product['id'], 'promo_') === 0 ||
                            strpos($product['id'], 'promo-') === 0
                        )
                    ) || (
                        isset($product['name']) && (
                            strpos($product['name'], 'DESCUENTO') !== false ||
                            strpos($product['name'], 'Descuento') !== false
                        )
                    ) || (
                        isset($product['price']) && $product['price'] < 0
                    );
                    
                    if ($isDiscount) {
                        // Acumular descuento
                        $productPrice = isset($product['price']) ? floatval($product['price']) : 0;
                        $productQuantity = isset($product['quantity']) ? intval($product['quantity']) : 1;
                        $discountAmount += abs($productPrice * $productQuantity);
                        \Log::info("💰 Descuento detectado en posición {$index}", [
                            'id' => $product['id'] ?? 'sin_id',
                            'name' => $product['name'] ?? 'sin_nombre',
                            'price' => $productPrice,
                            'discount_accumulated' => $discountAmount,
                            'criteria_matched' => 'combo-discount detected'
                        ]);
                    } else {
                        // Es un producto real
                        $realProducts[] = $product;
                        \Log::info("🛍️ Producto real agregado desde posición {$index}", [
                            'id' => $product['id'],
                            'name' => $product['name'] ?? 'sin_nombre',
                            'price' => $product['price'] ?? 0
                        ]);
                    }
                }
                
                \Log::info('📊 Resumen del filtrado', [
                    'productos_originales' => count($requestData['products']),
                    'productos_reales' => count($realProducts),
                    'descuento_total' => $discountAmount,
                    'ids_productos_reales' => collect($realProducts)->pluck('id')->toArray()
                ]);
                
                $requestData['products'] = $realProducts;
            }
            
            \Log::info('Productos filtrados', [
                'total_products' => count($requestData['products']),
                'discount_amount' => $discountAmount
            ]);
            
            // Validar solo productos reales
            $validator = \Validator::make($requestData, [
                'products' => 'required|array|min:1',
                'products.*.id' => 'required|exists:products,id',
                'products.*.quantity' => 'required|integer|min:1',
                'products.*.observations' => 'nullable|array',
                'products.*.specialties' => 'nullable|array',
                'payment_method' => 'required|in:cash,card,transfer',
                'paid_amount' => 'required|numeric|min:0',
                'notes' => 'nullable|string|max:500',
            ]);
            
            if ($validator->fails()) {
                throw new \Illuminate\Validation\ValidationException($validator);
            }

            \Log::info('Validation passed');

            DB::beginTransaction();

            try {
                $subtotal = 0;
                $saleDetails = [];

                // Validar stock y calcular subtotal
                foreach ($requestData['products'] as $productData) {
                    $product = Product::findOrFail($productData['id']);
                    
                    // Solo validar stock para productos que no son comida
                    if (!$product->is_food && $product->stock < $productData['quantity']) {
                        throw new \Exception("Stock insuficiente para el producto: {$product->name}");
                    }

                    // Calcular precio con especialidades
                    $basePrice = $product->price;
                    $specialtyPrice = 0;
                    
                    if (isset($productData['specialties']) && is_array($productData['specialties'])) {
                        foreach ($productData['specialties'] as $specialty) {
                            if (isset($specialty['price'])) {
                                $specialtyPrice += floatval($specialty['price']);
                            }
                        }
                    }
                    
                    $finalPrice = $basePrice + $specialtyPrice;
                    $lineSubtotal = $finalPrice * $productData['quantity'];
                    $subtotal += $lineSubtotal;

                    $saleDetails[] = [
                        'product' => $product,
                        'quantity' => $productData['quantity'],
                        'price' => $finalPrice,
                        'base_price' => $basePrice,
                        'subtotal' => $lineSubtotal,
                        'observations' => $productData['observations'] ?? [],
                        'specialties' => $productData['specialties'] ?? []
                    ];
                }

                \Log::info('Sale details calculated', ['subtotal' => $subtotal, 'details_count' => count($saleDetails)]);

                // Calcular totales con promociones
                $tax = $subtotal * 0; // Sin impuestos por ahora
                
                // Si ya hay descuentos del frontend (combo aplicado), NO calcular promociones adicionales
                if ($discountAmount > 0) {
                    \Log::info('🎯 Descuentos del frontend detectados - NO calculando promociones adicionales', [
                        'discount_from_frontend' => $discountAmount
                    ]);
                    $promotionDiscount = 0;
                    $appliedPromotions = [];
                    $totalDiscount = $discountAmount;
                } else {
                    \Log::info('💰 Sin descuentos del frontend - Calculando promociones backend');
                    $promotionData = $this->calculatePromotions($saleDetails, $subtotal);
                    $promotionDiscount = $promotionData['discount'];
                    $appliedPromotions = $promotionData['promotions'];
                    $totalDiscount = $promotionDiscount;
                }
                
                $total = $subtotal + $tax - $totalDiscount;
                
                \Log::info('Totales calculados', [
                    'subtotal' => $subtotal,
                    'discount_from_frontend' => $discountAmount,
                    'promotion_discount' => $promotionDiscount,
                    'total_discount' => $totalDiscount,
                    'final_total' => $total
                ]);

                if ($request->paid_amount < $total) {
                    throw new \Exception("El monto pagado es insuficiente");
                }

                $change = $request->paid_amount - $total;

                // Crear la venta
                $sale = Sale::create([
                    'user_id' => auth()->id(),
                    'subtotal' => $subtotal,
                    'tax' => $tax,
                    'discount' => $totalDiscount,
                    'total' => $total,
                    'paid_amount' => $request->paid_amount,
                    'change_amount' => $change,
                    'payment_method' => $request->payment_method,
                    'notes' => $request->notes,
                ]);

                \Log::info('Sale created', ['sale_id' => $sale->id]);

                // Crear detalles de venta y actualizar stock
                foreach ($saleDetails as $detail) {
                    $saleDetail = SaleDetail::create([
                        'sale_id' => $sale->id,
                        'product_id' => $detail['product']->id,
                        'product_name' => $detail['product']->name,
                        'product_price' => $detail['price'],
                        'quantity' => $detail['quantity'],
                        'subtotal' => $detail['subtotal'],
                    ]);

                    // Guardar opciones de personalización si existen
                    if (!empty($detail['observations']) || !empty($detail['specialties'])) {
                        $options = [];
                        
                        // Agregar observaciones
                        foreach ($detail['observations'] as $observation) {
                            $options[] = [
                                'sale_detail_id' => $saleDetail->id,
                                'product_option_id' => $observation['id'] ?? null,
                                'type' => 'observation',
                                'name' => $observation['name'],
                                'price' => 0,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                        }
                        
                        // Agregar especialidades
                        foreach ($detail['specialties'] as $specialty) {
                            $options[] = [
                                'sale_detail_id' => $saleDetail->id,
                                'product_option_id' => $specialty['id'] ?? null,
                                'type' => 'specialty',
                                'name' => $specialty['name'],
                                'price' => $specialty['price'] ?? 0,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                        }
                        
                        if (!empty($options)) {
                            DB::table('sale_detail_options')->insert($options);
                        }
                    }

                    // Actualizar stock solo para productos que no son comida
                    if (!$detail['product']->is_food) {
                        $detail['product']->decreaseStock($detail['quantity']);
                    }
                }

                DB::commit();

                \Log::info('Sale completed successfully', ['sale_id' => $sale->id, 'total' => $total]);

                return response()->json([
                    'success' => true,
                    'message' => 'Venta realizada exitosamente',
                    'sale' => $sale->load('saleDetails'),
                    'change' => $change,
                ]);

            } catch (\Exception $e) {
                DB::rollback();
                \Log::error('Sale transaction failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
                
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Sale validation failed', ['errors' => $e->errors()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Datos de entrada inválidos',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Sale unexpected error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor',
            ], 500);
        }
    }

    public function history(Request $request)
    {
        $query = Sale::with(['user', 'saleDetails.product'])
                    ->where('user_id', auth()->id())
                    ->orderBy('created_at', 'desc');

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $sales = $query->paginate(15);

        return view('cashier.sale.history', compact('sales'));
    }

    /**
     * Calcular promociones aplicables a la venta
     */
    private function calculatePromotions($saleDetails, $subtotal)
    {
        $totalDiscount = 0;
        $appliedPromotions = [];

        // Obtener promociones activas y disponibles
        $promotions = Promotion::available()
                               ->with(['categories', 'products'])
                               ->orderBy('discount_value', 'desc') // Aplicar primero los descuentos mayores
                               ->get();

        foreach ($promotions as $promotion) {
            $discount = $this->calculatePromotionDiscount($promotion, $saleDetails, $subtotal);
            
            if ($discount > 0) {
                $totalDiscount += $discount;
                $appliedPromotions[] = [
                    'promotion' => $promotion,
                    'discount' => $discount
                ];

                // Incrementar contador de usos de la promoción
                $promotion->increment('uses_count');

                // Si se alcanzó el máximo de usos, no aplicar más esta promoción
                if ($promotion->max_uses && $promotion->uses_count >= $promotion->max_uses) {
                    continue;
                }
            }
        }

        return [
            'discount' => $totalDiscount,
            'promotions' => $appliedPromotions
        ];
    }

    /**
     * Calcular descuento específico de una promoción
     */
    private function calculatePromotionDiscount($promotion, $saleDetails, $subtotal)
    {
        // Verificar si la promoción puede ser usada
        if (!$promotion->canBeUsed()) {
            return 0;
        }

        // Verificar monto mínimo
        if ($promotion->minimum_amount && $subtotal < $promotion->minimum_amount) {
            return 0;
        }

        $applicableAmount = 0;

        if ($promotion->apply_to === 'all') {
            // Aplicar a todos los productos
            $applicableAmount = $subtotal;
        } elseif ($promotion->apply_to === 'category') {
            // Aplicar solo a productos de categorías específicas
            $categoryIds = $promotion->categories->pluck('id')->toArray();
            
            foreach ($saleDetails as $detail) {
                if (in_array($detail['product']->category_id, $categoryIds)) {
                    $applicableAmount += $detail['subtotal'];
                }
            }
        } elseif ($promotion->apply_to === 'product') {
            // Aplicar solo a productos específicos
            $productIds = $promotion->products->pluck('id')->toArray();
            
            foreach ($saleDetails as $detail) {
                if (in_array($detail['product']->id, $productIds)) {
                    $applicableAmount += $detail['subtotal'];
                }
            }
        }

        if ($applicableAmount <= 0) {
            return 0;
        }

        // Calcular descuento
        return $promotion->calculateDiscount($applicableAmount);
    }

    /**
     * Obtener promociones disponibles para mostrar en el POS
     */
    public function getAvailablePromotions()
    {
        $promotions = Promotion::available()
                               ->with(['categories', 'products'])
                               ->select(['id', 'name', 'description', 'type', 'discount_value', 'apply_to', 'minimum_amount'])
                               ->get()
                               ->map(function ($promotion) {
                                   return [
                                       'id' => $promotion->id,
                                       'name' => $promotion->name,
                                       'description' => $promotion->description,
                                       'discount_text' => $promotion->type === 'percentage' 
                                           ? "{$promotion->discount_value}% de descuento"
                                           : "$" . number_format($promotion->discount_value, 2) . " de descuento",
                                       'minimum_text' => $promotion->minimum_amount > 0 
                                           ? "Compra mínima: $" . number_format($promotion->minimum_amount, 2)
                                           : null,
                                       'apply_to' => $promotion->apply_to,
                                       'category_ids' => $promotion->categories->pluck('id')->toArray(),
                                       'product_ids' => $promotion->products->pluck('id')->toArray(),
                                   ];
                               });

        return response()->json($promotions);
    }

    /**
     * Detectar combos sugeridos basados en productos del carrito
     */
    public function getSuggestedCombos(Request $request)
    {
        try {
            \Log::info('🔍 INICIANDO getSuggestedCombos', [
                'request_data' => $request->all()
            ]);

            // Filtrar productos reales (sin descuentos) antes de la validación
            $allCartProducts = $request->input('cart_products', []);
            $realCartProducts = [];
            
            foreach ($allCartProducts as $index => $product) {
                $productId = $product['id'] ?? null;
                $productName = $product['name'] ?? '';
                $productPrice = $product['price'] ?? 0;
                
                // Detectar si es un descuento
                $isDiscount = (
                    isset($product['id']) && (
                        strpos($product['id'], 'combo_discount_') === 0 ||
                        strpos($product['id'], 'combo-discount-') === 0 ||
                        strpos($product['id'], 'discount_') === 0 ||
                        strpos($product['id'], 'discount-') === 0 ||
                        strpos($product['id'], 'promo_') === 0 ||
                        strpos($product['id'], 'promo-') === 0
                    )
                ) || (
                    isset($product['name']) && (
                        strpos($product['name'], 'DESCUENTO') !== false ||
                        strpos($product['name'], 'Descuento') !== false
                    )
                ) || (
                    isset($product['price']) && $product['price'] < 0
                );
                
                if (!$isDiscount) {
                    $realCartProducts[] = $product;
                    \Log::info("🛍️ Producto real para sugerencias: {$productId}");
                } else {
                    \Log::info("💰 Descuento excluido de sugerencias: {$productId}");
                }
            }

            // Validar solo productos reales
            $validator = \Validator::make(['cart_products' => $realCartProducts], [
                'cart_products' => 'required|array',
                'cart_products.*.id' => 'required|exists:products,id',
                'cart_products.*.quantity' => 'required|integer|min:1'
            ]);
            
            if ($validator->fails()) {
                \Log::error('❌ Validación falló en getSuggestedCombos', [
                    'errors' => $validator->errors()->toArray(),
                    'real_cart_products' => $realCartProducts
                ]);
                return response()->json([
                    'error' => 'Error interno del servidor',
                    'message' => $validator->errors()->first(),
                    'suggestions' => [],
                    'has_suggestions' => false
                ], 500);
            }

            $cartProducts = collect($realCartProducts);
            $cartProductIds = $cartProducts->pluck('id')->toArray();
            
            \Log::info('📦 Productos del carrito', [
                'cart_product_ids' => $cartProductIds,
                'cart_products_count' => $cartProducts->count()
            ]);
            
            // Obtener combos activos que permiten sugerencias automáticas
            $combos = Combo::active()
                          ->where('auto_suggest', true)
                          ->with('products')
                          ->get();

            \Log::info('🎯 Combos encontrados', [
                'combos_count' => $combos->count(),
                'combo_names' => $combos->pluck('name')->toArray()
            ]);

            $suggestions = [];

            foreach ($combos as $combo) {
                \Log::info('🔄 Procesando combo', [
                    'combo_id' => $combo->id,
                    'combo_name' => $combo->name
                ]);
                
                $matchLevel = $combo->getMatchLevel($cartProducts);
                
                \Log::info('📊 Nivel de coincidencia', [
                    'combo_name' => $combo->name,
                    'match_level' => $matchLevel
                ]);
                
                // Solo sugerir si tiene al menos 50% de coincidencia
                if ($matchLevel['percentage'] >= 50) {
                    $missingProducts = Product::whereIn('id', $matchLevel['missing_products'])->get();
                    
                    $suggestions[] = [
                        'combo' => [
                            'id' => $combo->id,
                            'name' => $combo->name,
                            'description' => $combo->description,
                            'price' => $combo->price,
                            'original_price' => $combo->original_price,
                            'savings' => $combo->savings,
                            'discount_percentage' => $combo->discount_percentage,
                        ],
                        'match_level' => $matchLevel,
                        'missing_products' => $missingProducts->map(function($product) {
                            return [
                                'id' => $product->id,
                                'name' => $product->name,
                                'price' => $product->price,
                            ];
                        }),
                        'suggestion_priority' => $matchLevel['percentage'], // Para ordenar sugerencias
                    ];
                    
                    \Log::info('✅ Combo agregado a sugerencias', [
                        'combo_name' => $combo->name,
                        'match_percentage' => $matchLevel['percentage']
                    ]);
                }
            }

            // Ordenar por prioridad (mayor coincidencia primero)
            usort($suggestions, function($a, $b) {
                return $b['suggestion_priority'] <=> $a['suggestion_priority'];
            });

            $response = [
                'suggestions' => $suggestions,
                'has_suggestions' => count($suggestions) > 0
            ];

            \Log::info('🎉 Respuesta final', [
                'suggestions_count' => count($suggestions),
                'has_suggestions' => count($suggestions) > 0
            ]);

            return response()->json($response);
            
        } catch (\Exception $e) {
            \Log::error('❌ ERROR en getSuggestedCombos', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Error interno del servidor',
                'message' => $e->getMessage(),
                'suggestions' => [],
                'has_suggestions' => false
            ], 500);
        }
    }

    /**
     * Aplicar combo al carrito
     */
    public function applyCombo(Request $request)
    {
        $request->validate([
            'combo_id' => 'required|exists:combos,id',
            'cart_products' => 'required|array'
        ]);

        $combo = Combo::with('products')->findOrFail($request->combo_id);
        
        if (!$combo->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Este combo no está disponible'
            ], 400);
        }

        // Verificar que el carrito tiene los productos necesarios
        $cartProductIds = collect($request->cart_products)->pluck('id')->toArray();
        
        if (!$combo->matchesProducts($cartProductIds)) {
            return response()->json([
                'success' => false,
                'message' => 'El carrito no contiene todos los productos necesarios para este combo'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'combo' => [
                'id' => $combo->id,
                'name' => $combo->name,
                'price' => $combo->price,
                'original_price' => $combo->original_price,
                'savings' => $combo->savings,
                'products' => $combo->products->map(function($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'quantity' => $product->pivot->quantity,
                        'is_required' => $product->pivot->is_required,
                    ];
                }),
            ],
            'message' => '¡Combo aplicado exitosamente! El cliente ahorra $' . number_format($combo->savings, 2)
        ]);
    }
}
