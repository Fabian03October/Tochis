<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OrderControlController extends Controller
{
    /**
     * Mostrar panel de control de órdenes
     */
    public function index()
    {
        $kitchenOrders = Sale::with(['saleDetails.product', 'user'])
            ->whereIn('kitchen_status', ['pending', 'in_kitchen'])
            ->orderBy('created_at', 'asc')
            ->get();

        $deliveredOrders = Sale::with(['saleDetails.product', 'user'])
            ->where('kitchen_status', 'delivered')
            ->whereDate('created_at', today())
            ->orderBy('delivered_at', 'desc')
            ->get();

        return view('admin.order-control.index', compact('kitchenOrders', 'deliveredOrders'));
    }

    /**
     * Iniciar preparación en cocina
     */
    public function startKitchen(Sale $sale): JsonResponse
    {
        try {
            $sale->startKitchen();
            
            return response()->json([
                'success' => true,
                'message' => "Orden #{$sale->order_number} enviada a cocina",
                'order' => $sale->load(['saleDetails.product'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar orden a cocina: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Marcar orden como lista
     */
    public function markReady(Sale $sale): JsonResponse
    {
        try {
            $sale->markReady();
            
            return response()->json([
                'success' => true,
                'message' => "Orden #{$sale->order_number} marcada como lista",
                'order' => $sale->load(['saleDetails.product']),
                'preparation_time' => $sale->preparation_minutes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al marcar orden como lista: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Marcar orden como entregada
     */
    public function markDelivered(Sale $sale): JsonResponse
    {
        try {
            $sale->markDelivered();
            
            return response()->json([
                'success' => true,
                'message' => "Orden #{$sale->order_number} marcada como entregada",
                'order' => $sale->load(['saleDetails.product'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al marcar orden como entregada: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener órdenes en tiempo real (para AJAX)
     */
    public function getOrders(): JsonResponse
    {
        $kitchenOrders = Sale::with(['saleDetails.product', 'user'])
            ->whereIn('kitchen_status', ['pending', 'in_kitchen'])
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($sale) {
                return [
                    'id' => $sale->id,
                    'order_number' => $sale->order_number,
                    'status' => $sale->kitchen_status,
                    'status_text' => $sale->kitchen_status_text,
                    'status_color' => $sale->kitchen_status_color,
                    'created_at' => $sale->created_at->format('H:i'),
                    'kitchen_time' => $sale->kitchen_time,
                    'total' => number_format($sale->total, 2),
                    'items' => $sale->saleDetails->map(function ($detail) {
                        return [
                            'name' => $detail->product->name,
                            'quantity' => $detail->quantity,
                            'price' => number_format($detail->price, 2)
                        ];
                    }),
                    'customer' => $sale->user->name ?? 'Cliente'
                ];
            });

        $deliveredOrders = Sale::with(['saleDetails.product', 'user'])
            ->where('kitchen_status', 'delivered')
            ->whereDate('created_at', today())
            ->orderBy('delivered_at', 'desc')
            ->limit(20) // Solo las últimas 20
            ->get()
            ->map(function ($sale) {
                return [
                    'id' => $sale->id,
                    'order_number' => $sale->order_number,
                    'delivered_at' => $sale->delivered_at ? $sale->delivered_at->format('H:i') : null,
                    'preparation_minutes' => $sale->preparation_minutes,
                    'total' => number_format($sale->total, 2),
                    'items_count' => $sale->saleDetails->count(),
                    'customer' => $sale->user->name ?? 'Cliente'
                ];
            });

        return response()->json([
            'kitchen_orders' => $kitchenOrders,
            'delivered_orders' => $deliveredOrders
        ]);
    }
}
