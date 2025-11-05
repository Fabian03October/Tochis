<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comanda Cocina #{{ substr($sale->order_number, -4) }} - TOCHIS</title>
    <style>
        @page {
            size: 80mm auto;
            margin: 0;
        }
        
        /* Estilos optimizados para impresión térmica */
        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
                background: none !important;
                color: #000 !important;
            }
            
            .no-print {
                display: none !important;
            }
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.4;
            color: #000;
            background: #fff;
            width: 80mm;
            padding: 5mm;
        }
        
        .comanda-header {
            text-align: center;
            margin-bottom: 15px;
            border: 3px double #000;
            padding: 10px;
        }
        
        .kitchen-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .order-number {
            font-size: 32px;
            font-weight: bold;
            border: 3px solid #000;
            padding: 8px;
            margin: 10px 0;
            border-radius: 3px;
        }
        
        .order-info {
            font-size: 12px;
            margin-bottom: 10px;
            text-align: left;
            background: #fff;
            padding: 8px;
            border: 1px solid #ddd;
        }
        
        .category-section {
            margin-bottom: 20px;
            border: 2px solid #000;
            padding: 10px;
        }
        
        .category-title {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
            border: 2px solid #000;
            padding: 5px;
        }
        
        .item {
            margin-bottom: 15px;
            border-bottom: 1px dashed #999;
            padding-bottom: 10px;
        }
        
        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
        }
        
        .item-name {
            font-size: 16px;
            font-weight: bold;
        }
        
        .item-qty {
            font-size: 20px;
            font-weight: bold;
            border: 3px solid #000;
            padding: 5px 10px;
            min-width: 35px;
            text-align: center;
        }
        
        .item-options {
            margin-top: 8px;
            border: 1px solid #000;
            padding: 8px;
            border-left: 4px solid #000;
        }
        
        .options-title {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 5px;
        }
        
        .option-item {
            font-size: 13px;
            margin-bottom: 3px;
            padding-left: 10px;
        }
        
        .option-add {
            font-weight: bold;
        }
        
        .option-remove {
            font-weight: bold;
            text-decoration: line-through;
        }
        
        .option-extra {
            font-weight: bold;
        }
        
        .item-notes {
            margin-top: 8px;
            border: 2px solid #000;
            padding: 8px;
        }
        
        .notes-title {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 5px;
        }
        
        .special-alerts {
            margin-top: 8px;
            padding: 5px;
            border: 1px solid #000;
        }
        
        .alert-spicy {
            border: 2px solid #000;
            font-weight: bold;
        }
        
        .alert-allergen {
            border: 2px dashed #000;
            font-weight: bold;
        }
        
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
            border-top: 2px dashed #000;
            padding-top: 10px;
        }
        
        .no-print {
            display: block;
        }
        
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="comanda-header">
        <div class="kitchen-title">🍳 COCINA</div>
        <div class="order-number">ORDEN #{{ substr($sale->order_number, -4) }}</div>
        <div style="font-size: 12px;">{{ now()->format('d/m/Y H:i:s') }}</div>
    </div>
    
    <div class="order-info">
        <div><strong>Venta:</strong> {{ $sale->sale_number }}</div>
        <div><strong>Cajero:</strong> {{ $sale->user->name }}</div>
        <div><strong>Mesa/Cliente:</strong> 
            @if($sale->delivery_address)
                Domicilio
            @else
                Local
            @endif
        </div>
    </div>
    
    @php
        // Filtrar solo productos que NO son bebidas
        $kitchenItems = $sale->saleDetails->filter(function($detail) {
            $categoryName = strtolower($detail->product->category->name);
            return !str_contains($categoryName, 'bebida') && 
                   !str_contains($categoryName, 'refresco') && 
                   !str_contains($categoryName, 'agua') &&
                   !str_contains($categoryName, 'jugo') &&
                   !str_contains($categoryName, 'café') &&
                   !str_contains($categoryName, 'té');
        });
        
        // Agrupar por categoría
        $itemsByCategory = $kitchenItems->groupBy(function($detail) {
            return $detail->product->category->name;
        });
        
        $totalItems = $kitchenItems->sum('quantity');
    @endphp
    
    @if($totalItems > 0)
        <div style="text-align: center; font-weight: bold; margin-bottom: 15px; border: 2px solid #000; padding: 8px;">
            🍽️ PRODUCTOS PARA COCINA: {{ $totalItems }}
        </div>
        
        @foreach($itemsByCategory as $categoryName => $details)
            <div class="category-section">
                <div class="category-title">{{ strtoupper($categoryName) }}</div>
                
                @foreach($details as $detail)
                    <div class="item">
                        <div class="item-header">
                            <span class="item-name">{{ $detail->product->name }}</span>
                            <span class="item-qty">{{ $detail->quantity }}</span>
                        </div>
                        
                        {{-- Mostrar observaciones y especialidades --}}
                        @php
                            $hasCustomizations = false;
                            $observations = [];
                            $specialties = [];
                            
                            // Primero intentar desde selected_options (campo JSON)
                            if ($detail->selected_options) {
                                if (isset($detail->selected_options['observations']) && count($detail->selected_options['observations']) > 0) {
                                    $observations = array_merge($observations, $detail->selected_options['observations']);
                                    $hasCustomizations = true;
                                }
                                if (isset($detail->selected_options['specialties']) && count($detail->selected_options['specialties']) > 0) {
                                    $specialties = array_merge($specialties, $detail->selected_options['specialties']);
                                    $hasCustomizations = true;
                                }
                            }
                            
                            // Si no hay opciones en JSON, intentar desde la relación options
                            if (!$hasCustomizations && $detail->options && $detail->options->count() > 0) {
                                foreach ($detail->options as $option) {
                                    if ($option->type === 'observation') {
                                        $observations[] = ['name' => $option->name, 'price' => $option->price];
                                    } elseif ($option->type === 'specialty') {
                                        $specialties[] = ['name' => $option->name, 'price' => $option->price];
                                    }
                                }
                                $hasCustomizations = (count($observations) > 0 || count($specialties) > 0);
                            }
                        @endphp
                        
                        @if($hasCustomizations)
                            <div class="item-options">
                                <div class="options-title">🔧 PERSONALIZACIONES:</div>
                                @if(count($observations) > 0)
                                    @foreach($observations as $observation)
                                        <div class="option-item">
                                            <span class="option-remove">❌ {{ strtoupper($observation['name'] ?? $observation) }}</span>
                                        </div>
                                    @endforeach
                                @endif
                                @if(count($specialties) > 0)
                                    @foreach($specialties as $specialty)
                                        <div class="option-item">
                                            @if(isset($specialty['price']) && $specialty['price'] > 0)
                                                <span class="option-extra">➕ {{ strtoupper($specialty['name'] ?? $specialty) }} (+${{ number_format($specialty['price'], 2) }})</span>
                                            @else
                                                <span class="option-add">➕ {{ strtoupper($specialty['name'] ?? $specialty) }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        @endif
                        
                        @if($detail->notes)
                            <div class="item-notes">
                                <div class="notes-title">⚠️ NOTAS ESPECIALES:</div>
                                <div style="font-weight: bold; font-size: 14px;">{{ strtoupper($detail->notes) }}</div>
                            </div>
                        @endif
                        
                        @php
                            $isSpicy = stripos($detail->product->name, 'picante') !== false || 
                                      stripos($detail->product->description, 'picante') !== false;
                            $hasAllergens = stripos($detail->product->description, 'gluten') !== false || 
                                           stripos($detail->product->description, 'lácteo') !== false;
                        @endphp
                        
                        @if($isSpicy || $hasAllergens)
                            <div class="special-alerts">
                                @if($isSpicy)
                                    <div class="alert-spicy">🌶️ PRODUCTO PICANTE</div>
                                @endif
                                @if($hasAllergens)
                                    <div class="alert-allergen">⚠️ CONTIENE ALÉRGENOS</div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach
    @else
        <div style="text-align: center; padding: 20px; background: #f3f4f6; border: 2px dashed #9ca3af;">
            <h3>❌ NO HAY PRODUCTOS PARA COCINA</h3>
            <p>Esta orden solo contiene bebidas</p>
        </div>
    @endif
    
    @if($sale->delivery_address)
        <div style="border: 3px solid #000; padding: 10px; margin: 15px 0;">
            <div style="font-weight: bold; text-align: center;">🚚 ORDEN PARA ENTREGA</div>
            <div style="font-size: 12px; margin-top: 5px;">
                <strong>Dirección:</strong> {{ $sale->delivery_address }}<br>
                @if($sale->delivery_phone)
                    <strong>Teléfono:</strong> {{ $sale->delivery_phone }}<br>
                @endif
                @if($sale->delivery_notes)
                    <strong>Notas:</strong> {{ $sale->delivery_notes }}
                @endif
            </div>
        </div>
    @endif
    
    <div class="footer">
        <div style="font-weight: bold; margin-bottom: 5px;">
            HORA DE PREPARACIÓN ESTIMADA: 15-20 MIN
        </div>
        <small>{{ now()->format('d/m/Y H:i:s') }}</small>
    </div>
    
    <!-- Botones de control (no se imprimen) -->
    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()" style="background: #000; color: white; padding: 10px 20px; border: 2px solid #000; margin: 5px; cursor: pointer;">
            🖨️ Imprimir Comanda Cocina
        </button>
        <button onclick="window.close()" style="background: #666; color: white; padding: 10px 20px; border: 2px solid #000; margin: 5px; cursor: pointer;">
            ❌ Cerrar
        </button>
    </div>
</body>
</html>
