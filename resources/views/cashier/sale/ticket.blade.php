<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #{{ substr($sale->order_number, -4) }} - TOCHIS</title>
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
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            background: #fff;
            width: 80mm;
            padding: 5mm;
        }
        
        .ticket-header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
        }
        
        .logo {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .business-info {
            font-size: 10px;
            line-height: 1.2;
            margin-bottom: 5px;
        }
        
        .ticket-info {
            margin-bottom: 10px;
            font-size: 11px;
        }
        
        .ticket-info div {
            margin-bottom: 2px;
            display: flex;
            justify-content: space-between;
        }
        
        .order-number {
            text-align: center;
            margin: 15px 0;
            padding: 10px;
            background: #f0f0f0;
            border: 3px solid #000;
            border-radius: 8px;
        }
        
        .order-number .label {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .order-number .number {
            font-size: 28px;
            font-weight: bold;
            color: #000;
            letter-spacing: 3px;
        }
        
        .items-section {
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }
        
        .items-header {
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
            margin-bottom: 5px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
        }
        
        .item {
            margin-bottom: 3px;
            font-size: 11px;
        }
        
        .item-line {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        
        .item-name {
            flex: 1;
            margin-right: 5px;
            word-wrap: break-word;
        }
        
        .item-qty {
            min-width: 20px;
            text-align: center;
        }
        
        .item-price {
            min-width: 35px;
            text-align: right;
        }
        
        .totals-section {
            margin-bottom: 10px;
            font-size: 11px;
        }
        
        .total-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }
        
        .total-line.final {
            font-weight: bold;
            font-size: 13px;
            border-top: 1px solid #000;
            padding-top: 3px;
            margin-top: 5px;
        }
        
        .payment-section {
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
            font-size: 11px;
        }
        
        .footer {
            text-align: center;
            font-size: 9px;
            line-height: 1.3;
        }
        
        .delivery-info {
            background: #f0f0f0;
            padding: 8px;
            margin: 10px 0;
            border-radius: 3px;
            font-size: 10px;
            text-align: center;
        }
        
        .thanks-message {
            font-weight: bold;
            margin-top: 10px;
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
    <div class="ticket-header">
        <div class="logo">🍔 TOCHIS</div>
        <div class="business-info">
            Sistema de Punto de Venta<br>
            Tel: (555) 123-4567<br>
            www.tochis.com
        </div>
    </div>
    
    <div class="ticket-info">
        <div>
            <span><strong>Ticket:</strong></span>
            <span>#{{ $sale->sale_number }}</span>
        </div>
        <div>
            <span><strong>Fecha:</strong></span>
            <span>{{ $sale->created_at->format('d/m/Y H:i:s') }}</span>
        </div>
        <div>
            <span><strong>Cajero:</strong></span>
            <span>{{ $sale->user->name }}</span>
        </div>
        @if($sale->customer_name)
        <div>
            <span><strong>Cliente:</strong></span>
            <span>{{ $sale->customer_name }}</span>
        </div>
        @endif
        @if($sale->delivery_address)
        <div>
            <span><strong>Entrega:</strong></span>
            <span>Domicilio</span>
        </div>
        @endif
    </div>
    
    <div class="order-number">
        <div class="label">NÚMERO DE ORDEN</div>
        <div class="number">#{{ substr($sale->order_number, -4) }}</div>
    </div>
    
    <div class="items-section">
        <div class="items-header">
            <span>PRODUCTO</span>
            <span>CANT</span>
            <span>PRECIO</span>
        </div>
        
        @foreach($sale->saleDetails as $detail)
            <div class="item">
                <div class="item-line">
                    <span class="item-name">{{ $detail->product->name }}</span>
                    <span class="item-qty">{{ $detail->quantity }}</span>
                    <span class="item-price">${{ number_format($detail->price, 2) }}</span>
                </div>
                @if($detail->quantity > 1)
                <div class="item-line" style="font-size: 10px; color: #666; margin-left: 10px;">
                    <span></span>
                    <span>{{ $detail->quantity }} x ${{ number_format($detail->price, 2) }}</span>
                    <span>${{ number_format($detail->price * $detail->quantity, 2) }}</span>
                </div>
                @endif
                
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
                    <div style="font-size: 9px; color: #666; margin-left: 10px; margin-top: 2px;">
                        @if(count($observations) > 0)
                            <div>{{ implode(', ', array_column($observations, 'name')) }}</div>
                        @endif
                        @if(count($specialties) > 0)
                            <div>Con: {{ implode(', ', array_column($specialties, 'name')) }}</div>
                        @endif
                    </div>
                @endif
                
                @if($detail->notes)
                    <div style="font-size: 9px; color: #666; margin-left: 10px; margin-top: 2px;">
                        Nota: {{ $detail->notes }}
                    </div>
                @endif
            </div>
        @endforeach
    </div>
    
    @if($sale->delivery_address)
        <div class="delivery-info">
            <strong>INFORMACIÓN DE ENTREGA</strong><br>
            Dirección: {{ $sale->delivery_address }}<br>
            @if($sale->delivery_phone)
                Teléfono: {{ $sale->delivery_phone }}<br>
            @endif
            @if($sale->delivery_notes)
                Notas: {{ $sale->delivery_notes }}<br>
            @endif
            @if($sale->delivery_fee > 0)
                Costo de envío: ${{ number_format($sale->delivery_fee, 2) }}
            @endif
        </div>
    @endif
    
    <div class="totals-section">
        @php
            $subtotalWithoutTax = $sale->subtotal / 1.16;
            $ivaAmount = $sale->subtotal - $subtotalWithoutTax;
        @endphp
        
        <div class="total-line">
            <span>Subtotal:</span>
            <span>${{ number_format($subtotalWithoutTax, 2) }}</span>
        </div>
        <div class="total-line">
            <span>IVA (16%):</span>
            <span>${{ number_format($ivaAmount, 2) }}</span>
        </div>
        @if($sale->discount > 0)
        <div class="total-line" style="color: #e74c3c;">
            <span>Descuento:</span>
            <span>-${{ number_format($sale->discount, 2) }}</span>
        </div>
        @endif
        @if($sale->delivery_fee > 0)
        <div class="total-line">
            <span>Envío:</span>
            <span>${{ number_format($sale->delivery_fee, 2) }}</span>
        </div>
        @endif
        <div class="total-line final">
            <span>TOTAL:</span>
            <span>${{ number_format($sale->total, 2) }}</span>
        </div>
    </div>
    
    <div class="payment-section">
        <div class="total-line">
            <span>Método de Pago:</span>
            <span>
                @if($sale->payment_method == 'cash')
                    💵 Efectivo
                @elseif($sale->payment_method == 'card')
                    💳 Tarjeta
                @elseif($sale->payment_method == 'transfer')
                    🏦 Transferencia
                @else
                    {{ ucfirst($sale->payment_method) }}
                @endif
            </span>
        </div>
        @if($sale->payment_method == 'cash' && $sale->change_amount > 0)
        <div class="total-line">
            <span>Pagado:</span>
            <span>${{ number_format($sale->paid_amount, 2) }}</span>
        </div>
        <div class="total-line">
            <span>Cambio:</span>
            <span>${{ number_format($sale->change_amount, 2) }}</span>
        </div>
        @endif
    </div>
    
    <div class="footer">
        <div class="thanks-message">¡GRACIAS POR TU PREFERENCIA!</div>
        <div style="margin-top: 10px;">
            Síguenos en redes sociales:<br>
            @tochis_oficial
        </div>
        <div style="margin-top: 10px;">
            Este ticket es válido como<br>
            comprobante de compra
        </div>
        <div style="margin-top: 10px; font-size: 8px;">
            {{ $sale->created_at->format('d/m/Y H:i:s') }}
        </div>
    </div>
    
    <!-- Botones de control (no se imprimen) -->
    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()" style="background: #3b82f6; color: white; padding: 10px 20px; border: none; border-radius: 5px; margin: 5px; cursor: pointer;">
            🖨️ Imprimir Ticket
        </button>
        <button onclick="window.close()" style="background: #6b7280; color: white; padding: 10px 20px; border: none; border-radius: 5px; margin: 5px; cursor: pointer;">
            ❌ Cerrar
        </button>
    </div>
</body>
</html>
