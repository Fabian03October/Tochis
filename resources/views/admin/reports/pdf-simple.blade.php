<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #ea580c;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #ea580c;
            font-size: 24px;
            margin: 0 0 10px 0;
        }
        
        .header p {
            color: #666;
            margin: 0;
            font-size: 14px;
        }
        
        .period {
            text-align: center;
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 30px;
            border: 1px solid #dee2e6;
        }
        
        .stats-grid {
            width: 100%;
            margin-bottom: 30px;
        }
        
        .stats-row {
            display: table;
            width: 100%;
        }
        
        .stat-card {
            display: table-cell;
            width: 25%;
            padding: 15px;
            text-align: center;
            border: 1px solid #dee2e6;
            background-color: #f8f9fa;
        }
        
        .stat-card h4 {
            margin: 0 0 10px 0;
            color: #666;
            font-size: 11px;
            text-transform: uppercase;
        }
        
        .stat-card .value {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin: 0;
        }
        
        .section {
            margin-bottom: 30px;
        }
        
        .section-title {
            background-color: #ea580c;
            color: white;
            padding: 10px 15px;
            margin: 0 0 15px 0;
            font-size: 14px;
            font-weight: bold;
        }
        
        .item-list {
            border: 1px solid #dee2e6;
        }
        
        .item {
            padding: 12px;
            border-bottom: 1px solid #f8f9fa;
            display: table;
            width: 100%;
        }
        
        .item:last-child {
            border-bottom: none;
        }
        
        .item-info {
            display: table-cell;
            vertical-align: middle;
        }
        
        .item-value {
            display: table-cell;
            text-align: right;
            vertical-align: middle;
            font-weight: bold;
            color: #ea580c;
        }
        
        .item-name {
            font-weight: bold;
            color: #333;
            margin-bottom: 3px;
        }
        
        .item-details {
            color: #666;
            font-size: 10px;
        }
        
        .no-data {
            text-align: center;
            padding: 30px;
            color: #999;
            font-style: italic;
        }
        
        .two-column {
            display: table;
            width: 100%;
        }
        
        .column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 10px;
        }
        
        .column:last-child {
            padding-right: 0;
            padding-left: 10px;
        }
        
        .sales-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #dee2e6;
        }
        
        .sales-table th {
            background-color: #f8f9fa;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            color: #333;
            border-bottom: 1px solid #dee2e6;
            font-size: 10px;
        }
        
        .sales-table td {
            padding: 8px;
            border-bottom: 1px solid #f8f9fa;
            font-size: 10px;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #999;
            font-size: 9px;
            border-top: 1px solid #dee2e6;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>TOCHIS</h1>
        <p>Sistema de Punto de Venta - Reporte de Ventas</p>
    </div>

    <div class="period">
        <h3>Periodo del Reporte</h3>
        <p>{{ isset($startDate) ? $startDate->format('d/m/Y') : 'N/A' }} al {{ isset($endDate) ? $endDate->format('d/m/Y') : 'N/A' }}</p>
    </div>

    <div class="stats-grid">
        <div class="stats-row">
            <div class="stat-card">
                <h4>Ventas Totales</h4>
                <p class="value">${{ number_format($totalSales ?? 0, 2) }}</p>
            </div>
            <div class="stat-card">
                <h4>Transacciones</h4>
                <p class="value">{{ number_format($totalTransactions ?? 0) }}</p>
            </div>
            <div class="stat-card">
                <h4>Platillos Vendidos</h4>
                <p class="value">{{ number_format($totalProductsSold ?? 0) }}</p>
            </div>
            <div class="stat-card">
                <h4>Promedio por Venta</h4>
                <p class="value">${{ number_format($averageSale ?? 0, 2) }}</p>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="two-column">
            <div class="column">
                <h2 class="section-title">Platillos Mas Vendidos</h2>
                @if(isset($topProducts) && $topProducts->count() > 0)
                    <div class="item-list">
                        @foreach($topProducts as $product)
                            <div class="item">
                                <div class="item-info">
                                    <div class="item-name">{{ $product->name ?? 'Sin nombre' }}</div>
                                    <div class="item-details">{{ optional($product->category)->name ?? 'Sin categoria' }}</div>
                                </div>
                                <div class="item-value">{{ $product->total_sold ?? 0 }} vendidos</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="no-data">No hay datos disponibles</div>
                @endif
            </div>

            <div class="column">
                <h2 class="section-title">Ventas por Categoria</h2>
                @if(isset($salesByCategory) && $salesByCategory->count() > 0)
                    <div class="item-list">
                        @foreach($salesByCategory as $category)
                            <div class="item">
                                <div class="item-info">
                                    <div class="item-name">{{ $category->name ?? 'Sin nombre' }}</div>
                                    <div class="item-details">{{ $category->products_count ?? 0 }} platillos - {{ $category->total_quantity ?? 0 }} vendidos</div>
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

    @if(isset($topCombos) && $topCombos->count() > 0)
    <div class="section">
        <h2 class="section-title">Combos Mas Vendidos</h2>
        <div class="item-list">
            @foreach($topCombos as $combo)
                <div class="item">
                    <div class="item-info">
                        <div class="item-name">{{ $combo->name ?? 'Sin nombre' }}</div>
                        <div class="item-details">${{ number_format($combo->price ?? 0, 2) }}</div>
                    </div>
                    <div class="item-value">{{ $combo->total_quantity ?? 0 }} vendidos</div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="section">
        <h2 class="section-title">Metodos de Pago</h2>
        @if(isset($paymentMethods) && $paymentMethods->count() > 0)
            <div class="item-list">
                @foreach($paymentMethods as $method)
                    <div class="item">
                        <div class="item-info">
                            <div class="item-name">
                                @if(($method->payment_method ?? '') === 'cash')
                                    Efectivo
                                @elseif(($method->payment_method ?? '') === 'card')
                                    Tarjeta
                                @else
                                    Otro
                                @endif
                            </div>
                            <div class="item-details">{{ $method->count ?? 0 }} transacciones</div>
                        </div>
                        <div class="item-value">${{ number_format($method->total ?? 0, 2) }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-data">No hay datos disponibles</div>
        @endif
    </div>

    @if(isset($recentSales) && $recentSales->count() > 0)
    <div class="section">
        <h2 class="section-title">Ventas Recientes</h2>
        <table class="sales-table">
            <thead>
                <tr>
                    <th>Venta</th>
                    <th>Cajero</th>
                    <th>Fecha</th>
                    <th>Articulos</th>
                    <th>Metodo</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentSales as $sale)
                    <tr>
                        <td>{{ $sale->sale_number ?? 'N/A' }}</td>
                        <td>{{ optional($sale->user)->name ?? 'N/A' }}</td>
                        <td>{{ isset($sale->created_at) ? $sale->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                        <td>{{ isset($sale->saleDetails) ? $sale->saleDetails->sum('quantity') : 0 }}</td>
                        <td>{{ ucfirst($sale->payment_method ?? 'N/A') }}</td>
                        <td>${{ number_format($sale->total ?? 0, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if(isset($dailySales) && $dailySales->count() > 0)
    <div class="section">
        <h2 class="section-title">Ventas Diarias</h2>
        <table class="sales-table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dailySales as $daily)
                    <tr>
                        <td>{{ isset($daily->date) ? \Carbon\Carbon::parse($daily->date)->format('d/m/Y') : 'N/A' }}</td>
                        <td>${{ number_format($daily->total ?? 0, 2) }}</td>
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