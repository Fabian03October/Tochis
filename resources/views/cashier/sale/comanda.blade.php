<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comanda #{{ substr($sale->order_number, -4) }} - TOCHIS COCINA</title>
    <style>
        @page {
            size: 80mm auto;
            margin: 0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        
        /* Estilos optimizados para impresión térmica */
        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            
            /* Convertir backgrounds de color a bordes para impresión térmica */
            .comanda-header {
                background: none !important;
                border: 3px double #000 !important;
            }
            
            .order-number {
                background: none !important;
                color: #000 !important;
                border: 3px solid #000 !important;
            }
            
            /* Eliminar colores de fondo, mantener solo bordes */
            div[style*="background"] {
                background: none !important;
                border: 2px solid #000 !important;
            }
            
            /* Asegurar que el texto sea negro en impresión */
            .priority-urgent {
                color: #000 !important;
                font-weight: bold !important;
                border: 3px solid #000 !important;
                background: none !important;
            }
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
        }
        
        .order-info {
            margin-bottom: 15px;
            font-size: 12px;
            border: 2px solid #000;
            padding: 8px;
            background: #fff;
        }
        
        .order-info div {
            margin-bottom: 3px;
            display: flex;
            justify-content: space-between;
            font-weight: bold;
        }
        
        .items-section {
            margin-bottom: 15px;
        }
        
        .section-header {
            background: #000;
            color: #fff;
            padding: 8px;
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
        }
        
        .category-group {
            margin-bottom: 15px;
            border: 2px solid #333;
            border-radius: 5px;
            overflow: hidden;
        }
        
        .category-header {
            background: #333;
            color: #fff;
            padding: 8px;
            font-weight: bold;
            font-size: 14px;
            text-align: center;
        }
        
        .category-items {
            padding: 8px;
            background: #f9f9f9;
        }
        
        .item {
            margin-bottom: 15px;
            border-bottom: 1px dashed #666;
            padding-bottom: 10px;
        }
        
        .item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        
        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
            background: #fff;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        
        .item-name {
            font-weight: bold;
            font-size: 16px;
            flex: 1;
        }
        
        .item-quantity {
            background: #000;
            color: #fff;
            padding: 5px 10px;
            border-radius: 50%;
            font-weight: bold;
            font-size: 18px;
            min-width: 35px;
            text-align: center;
        }
        
        .item-customizations {
            background: #fffbf0;
            border: 2px solid #ffa500;
            border-radius: 5px;
            padding: 8px;
            margin-top: 5px;
            font-size: 12px;
        }
        
        .customization-title {
            font-weight: bold;
            color: #cc6600;
            margin-bottom: 3px;
            text-transform: uppercase;
        }
        
        .customization-item {
            margin-bottom: 2px;
            padding-left: 5px;
            border-left: 3px solid #ffa500;
        }
        
        .item-notes {
            background: #ffe6e6;
            border: 2px solid #ff6b6b;
            border-radius: 5px;
            padding: 8px;
            margin-top: 5px;
            font-size: 12px;
        }
        
        .notes-title {
            font-weight: bold;
            color: #cc0000;
            margin-bottom: 3px;
            text-transform: uppercase;
        }
        
        .delivery-alert {
            background: #e6f3ff;
            border: 3px solid #0066cc;
            padding: 10px;
            margin: 15px 0;
            text-align: center;
            font-weight: bold;
            border-radius: 5px;
        }
        
        .delivery-icon {
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .preparation-time {
            background: #fff2e6;
            border: 2px solid #ff8c00;
            padding: 10px;
            margin: 15px 0;
            text-align: center;
            font-weight: bold;
            border-radius: 5px;
        }
        
        .footer {
            margin-top: 15px;
            text-align: center;
            border-top: 2px dashed #000;
            padding-top: 10px;
            font-size: 12px;
        }
        
        .priority-badge {
            background: #ff0000;
            color: #fff;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
            margin-left: 5px;
        }
        
        .allergen-warning {
            background: #ffcccc;
            border: 2px solid #ff0000;
            padding: 5px;
            margin-top: 5px;
            font-size: 11px;
            border-radius: 3px;
            text-align: center;
            font-weight: bold;
        }
        
        @media print {
            body {
                width: 80mm !important;
            }
            
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="comanda-header">
        <div class="kitchen-title">🍳 COCINA TOCHIS</div>
        <div class="order-number">ORDEN #{{ substr($sale->order_number, -4) }}</div>
    </div>
    
    <div class="order-info">
        <div>
            <span>🕐 HORA PEDIDO:</span>
            <span>{{ $sale->created_at->format('H:i:s') }}</span>
        </div>
        <div>
            <span>📅 FECHA:</span>
            <span>{{ $sale->created_at->format('d/m/Y') }}</span>
        </div>
        <div>
            <span>👨‍💼 CAJERO:</span>
            <span>{{ $sale->user->name }}</span>
        </div>
        @if($sale->customer_name)
        <div>
            <span>👤 CLIENTE:</span>
            <span>{{ $sale->customer_name }}</span>
        </div>
        @endif
        <div>
            <span>📝 TIPO:</span>
            <span>
                @if($sale->delivery_address)
                    🚚 DOMICILIO
                    <span class="priority-badge">ENTREGA</span>
                @else
                    🏪 MOSTRADOR
                @endif
            </span>
        </div>
    </div>
    
    @if($sale->delivery_address)
    <div class="delivery-alert">
        <div class="delivery-icon">🚚</div>
        <div>PEDIDO PARA ENTREGA A DOMICILIO</div>
        <div style="font-size: 12px; margin-top: 5px;">
            📍 {{ $sale->delivery_address }}
        </div>
        @if($sale->delivery_phone)
        <div style="font-size: 12px;">
            📞 {{ $sale->delivery_phone }}
        </div>
        @endif
    </div>
    @endif
    
    <div class="preparation-time">
        ⏱️ TIEMPO ESTIMADO: 15-20 MIN
    </div>
    
    <div class="items-section">
        <div class="section-header">
            🍽️ PRODUCTOS A PREPARAR
        </div>
        
        @php
            $itemsByCategory = $sale->saleDetails->groupBy(function($detail) {
                return $detail->product->category->name;
            });
            $totalItems = $sale->saleDetails->sum('quantity');
        @endphp
        
        <div style="text-align: center; margin-bottom: 15px; font-size: 16px; font-weight: bold; background: #f0f0f0; padding: 8px; border: 2px solid #333;">
            TOTAL DE PRODUCTOS: {{ $totalItems }}
        </div>
        
        @foreach($itemsByCategory as $categoryName => $details)
            <div class="category-group">
                <div class="category-header">
                    🏷️ {{ strtoupper($categoryName) }}
                </div>
                <div class="category-items">
                    @foreach($details as $detail)
                        <div class="item">
                            <div class="item-header">
                                <span class="item-name">{{ $detail->product->name }}</span>
                                <span class="item-quantity">{{ $detail->quantity }}</span>
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
                                <div class="item-customizations">
                                    <div class="customization-title">🔧 Personalizaciones:</div>
                                    @if(count($observations) > 0)
                                        @foreach($observations as $observation)
                                            <div class="customization-item">
                                                ❌ {{ strtoupper($observation['name'] ?? $observation) }}
                                            </div>
                                        @endforeach
                                    @endif
                                    @if(count($specialties) > 0)
                                        @foreach($specialties as $specialty)
                                            <div class="customization-item">
                                                @if(isset($specialty['price']) && $specialty['price'] > 0)
                                                    ➕ {{ strtoupper($specialty['name'] ?? $specialty) }} (+${{ number_format($specialty['price'], 2) }})
                                                @else
                                                    ➕ {{ strtoupper($specialty['name'] ?? $specialty) }}
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            @endif
                            
                            @if($detail->notes)
                                <div class="item-notes">
                                    <div class="notes-title">⚠️ Instrucciones Especiales:</div>
                                    <div style="font-weight: bold; font-size: 14px;">{{ $detail->notes }}</div>
                                </div>
                            @endif
                            
                            @php
                                $isSpicy = stripos($detail->product->name, 'picante') !== false || 
                                          stripos($detail->product->description, 'picante') !== false;
                                $hasAllergens = stripos($detail->product->description, 'gluten') !== false || 
                                               stripos($detail->product->description, 'lácteo') !== false;
                            @endphp
                            
                            @if($isSpicy || $hasAllergens)
                                <div class="allergen-warning">
                                    @if($isSpicy) 🌶️ PRODUCTO PICANTE @endif
                                    @if($hasAllergens) ⚠️ CONTIENE ALÉRGENOS @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
    
    @if($sale->notes)
    <div class="item-notes">
        <div class="notes-title">📝 OBSERVACIONES GENERALES DEL PEDIDO:</div>
        <div style="font-weight: bold; font-size: 14px;">{{ $sale->notes }}</div>
    </div>
    @endif
    
    @if($sale->delivery_notes)
    <div class="delivery-alert">
        <div class="notes-title">🚚 NOTAS DE ENTREGA:</div>
        <div style="font-size: 12px; margin-top: 5px;">{{ $sale->delivery_notes }}</div>
    </div>
    @endif
    
    <div class="footer">
        <div style="font-weight: bold; font-size: 16px; margin-bottom: 5px;">
            ✅ MARCAR COMO LISTO CUANDO TERMINE
        </div>
        <div style="font-size: 12px;">
            Hora de impresión: {{ now()->format('H:i:s') }}
        </div>
        <div style="font-size: 12px; margin-top: 5px;">
            🍔 TOCHIS - Calidad que se siente
        </div>
    </div>
    
    <!-- Botones de control (no se imprimen) -->
    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()" style="background: #f97316; color: white; padding: 10px 20px; border: none; border-radius: 5px; margin: 5px; cursor: pointer;">
            🖨️ Imprimir Comanda
        </button>
        <button onclick="window.close()" style="background: #6b7280; color: white; padding: 10px 20px; border: none; border-radius: 5px; margin: 5px; cursor: pointer;">
            ❌ Cerrar
        </button>
    </div>
</body>
</html>
