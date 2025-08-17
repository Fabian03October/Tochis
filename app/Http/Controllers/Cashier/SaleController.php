<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Promotion;
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
        $request->validate([
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.observations' => 'nullable|array',
            'products.*.specialties' => 'nullable|array',
            'payment_method' => 'required|in:cash,card,transfer',
            'paid_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $subtotal = 0;
            $saleDetails = [];

            // Validar stock y calcular subtotal
            foreach ($request->products as $productData) {
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

            // Calcular totales con promociones
            $tax = $subtotal * 0; // Sin impuestos por ahora
            $promotionData = $this->calculatePromotions($saleDetails, $subtotal);
            $discount = $promotionData['discount'];
            $appliedPromotions = $promotionData['promotions'];
            $total = $subtotal + $tax - $discount;

            if ($request->paid_amount < $total) {
                throw new \Exception("El monto pagado es insuficiente");
            }

            $change = $request->paid_amount - $total;

            // Crear la venta
            $sale = Sale::create([
                'user_id' => auth()->id(),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
                'paid_amount' => $request->paid_amount,
                'change_amount' => $change,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
            ]);

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

            return response()->json([
                'success' => true,
                'message' => 'Venta realizada exitosamente',
                'sale' => $sale->load('saleDetails'),
                'change' => $change,
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
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
}
