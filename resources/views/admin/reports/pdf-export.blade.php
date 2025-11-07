<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas - TOCHIS POS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 11px; /* Reducido para más info */
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #ea580c;
            padding-bottom: 15px;
        }
        
        .header h1 {
            color: #ea580c;
            font-size: 24px;
            margin: 0 0 5px 0;
        }
        
        .header p {
            color: #666;
            margin: 0;
            font-size: 14px;
        }
        
        .period {
            text-align: center;
            background-color: #f8f9fa;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
            font-size: 12px;
        }
        
        .stats-grid {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        
        .stat-card {
            width: 25%;
            padding: 10px;
            text-align: center;
            border: 1px solid #dee2e6;
            background-color: #f8f9fa;
        }
        
        .stat-card h4 {
            margin: 0 0 5px 0;
            color: #666;
            font-size: 10px;
            text-transform: uppercase;
        }
        
        .stat-card .value {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin: 0;
        }
        
        .section {
            margin-bottom: 20px;
            page-break-inside: avoid; /* Evita que las tablas se corten entre páginas */
        }
        
        .section-title {
            background-color: #ea580c;
            color: white;
            padding: 8px 12px;
            margin: 0 0 10px 0;
            font-size: 13px;
            font-weight: bold;
        }
        
        .item-list {
            border: 1px solid #dee2e6;
        }
        
        .item {
            padding: 8px 10px;
            border-bottom: 1px solid #f1f1f1;
        }
        
        .item:last-child {
            border-bottom: none;
        }

        .item-info {
             display: inline-block;
             width: 70%;
        }
        .item-value {
            display: inline-block;
            width: 28%;
            text-align: right;
            font-weight: bold;
            color: #ea580c;
        }
        
        .item-name {
            font-weight: bold;
            color: #333;
            font-size: 12px;
        }
        
        .item-details {
            color: #666;
            font-size: 9px;
        }
        
        .no-data {
            text-align: center;
            padding: 20px;
            color: #999;
            font-style: italic;
            border: 1px dashed #ccc;
        }
        
        .two-column {
            display: table;
            width: 100%;
            border-spacing: 20px 0; /* Espacio entre columnas */
        }
        
        .column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        
        .sales-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #dee2e6;
        }
        
        .sales-table th {
            background-color: #f8f9fa;
            padding: 6px;
            text-align: left;
            font-weight: bold;
            color: #333;
            border-bottom: 1px solid #dee2e6;
            font-size: 10px;
        }
        
        .sales-table td {
            padding: 6px;
            border-bottom: 1px solid #f1f1f1;
            font-size: 10px;
        }

        .sales-table tr:last-child td {
            border-bottom: none;
        }
        
        .footer {
            margin-top: 20px;
            text-align: center;
            color: #999;
            font-size: 9px;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>TOCHIS</h1>
        <p>Sistema de Punto de Venta - Reporte de Ventas</p>
    </div>

    <div class="period">
        Periodo del Reporte:
        <strong>{{ $startDate->format('d/m/Y') }}</strong> al <strong>{{ $endDate->format('d/m/Y') }}</strong>
        ({{ $startDate->diffInDays($endDate) + 1 }} días)
    </div>

    <table class="stats-grid">
        <tr>
            <td class="stat-card">
                <h4>Ventas Totales</h4>
                <p class="value">${{ number_format($totalSales, 2) }}</p>
            </td>
            <td class="stat-card">
                <h4>Transacciones</h4>
                <p class="value">{{ number_format($totalTransactions) }}</p>
            </td>
            <td class="stat-card">
                <h4>Platillos Vendidos</h4>
                <p class="value">{{ number_format($totalProductsSold) }}</p>
            </td>
            <td class="stat-card">
                <h4>Promedio por Venta</h4>
                <p class="value">${{ number_format($averageSale, 2) }}</p>
            </td>
        </tr>
    </table>

    <div class="two-column">
        <div class="column">
            <div class="section">
                <h2 class="section-title">Platillos Más Vendidos</h2>
                @if($topProducts->count() > 0)
                    <div class="item-list">
                        @foreach($topProducts as $product)
                            <div class="item">
                                <div class="item-info">
                                    <div class="item-name">{{ $product->name }}</div>
                                    <div class="item-details">{{ optional($product->category)->name ?? 'Sin categoría' }}</div>
                                </div>
                                <div class="item-value">{{ $product->total_sold }} vendidos</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="no-data">No hay datos disponibles</div>
                @endif
            </div>
        </div>

        <div class="column">
            <div class="section">
                <h2 class="section-title">Ventas por Categoría</h2>
                @if($salesByCategory->count() > 0)
                    <div class="item-list">
                        @foreach($salesByCategory as $category)
                            <div class="item">
                                <div class="item-info">
                                    <div class="item-name">{{ $category->name }}</div>
                                    <div class="item-details">{{ $category->total_quantity ?? 0 }} vendidos</div>
                                </div>
                                <div class="item-value">${{ number_format($category->total_sales ?? 0, 2) }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="no-data">No hay datos disponibles</div>
                @endif
            </div>
        </div>
    </div>

    @if($topCombos->count() > 0)
    <div class="section">
        <h2 class="section-title">Combos Más Vendidos</h2>
        <div class="item-list">
            @foreach($topCombos as $combo)
                <div class="item">
                    <div class="item-info">
                        <div class="item-name">{{ $combo->name }}</div>
                        <div class="item-details">${{ number_format($combo->price, 2) }}</div>
                    </div>
                    <div class="item-value">{{ $combo->total_quantity }} vendidos</div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="section">
        <h2 class="section-title">Métodos de Pago</h2>
        @if($paymentMethods->count() > 0)
            <div class="item-list">
                @foreach($paymentMethods as $method)
                    <div class="item">
                        <div class="item-info">
                            <div class="item-name">
                                @if($method->payment_method === 'cash')
                                    Efectivo
                                @elseif($method->payment_method === 'card')
                                    Tarjeta
                                @else
                                    {{ ucfirst($method->payment_method) }}
                                @endif
                            </div>
                            <div class="item-details">{{ $method->count }} transacciones</div>
                        </div>
                        <div class="item-value">${{ number_format($method->total, 2) }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-data">No hay datos disponibles</div>
        @endif
    </div>

    @if($dailySales->count() > 0)
    <div class="section">
        <h2 class="section-title">Ventas Diarias</h2>
        <table class="sales-table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dailySales as $daily)
                    <tr>
                        <td>{{ $daily->date }}</td> <td style="text-align: right;">${{ number_format($daily->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    
    @if($recentSales->count() > 0)
    <div class="section">
        <h2 class="section-title">Ventas Recientes</h2>
        <table class="sales-table">
            <thead>
                <tr>
                    <th>Venta</th>
                    <th>Cajero</th>
                    <th>Fecha</th>
                    <th>Artículos</th>
                    <th>Método</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentSales as $sale)
                    <tr>
                        <td>{{ $sale->sale_number ?? 'N/A' }}</td>
                        <td>{{ optional($sale->user)->name ?? 'N/A' }}</td>
                        <td>{{ $sale->created_at ? $sale->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                        <td>{{ ($sale->saleDetails && $sale->saleDetails->count() > 0) ? $sale->saleDetails->sum('quantity') : 0 }}</td>
                        <td>{{ ucfirst($sale->payment_method ?? 'N/A') }}</td>
                        <td style="text-align: right;">${{ number_format($sale->total ?? 0, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        <p>Reporte generado el {{ now()->format('d/m/Y') }} a las {{ now()->format('H:i') }}</p>
        <p>TOCHIS - Sistema de Punto de Venta</p>
    </div>
</body>
</html>