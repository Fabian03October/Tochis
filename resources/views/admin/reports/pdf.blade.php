<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ventas - TOCHIS POS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #2c3e50;
            background: #ffffff;
        }

        .header {
            background: linear-gradient(135deg, #e91e63 0%, #f06292 100%);
            color: white;
            padding: 25px;
            margin-bottom: 30px;
            border-radius: 8px;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .header-content {
            position: relative;
            z-index: 1;
        }

        .logo {
            font-size: 32px;
            font-weight: 900;
            margin-bottom: 8px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .subtitle {
            font-size: 18px;
            margin-bottom: 15px;
            opacity: 0.9;
        }

        .period {
            background: rgba(255, 255, 255, 0.2);
            padding: 10px 20px;
            border-radius: 25px;
            display: inline-block;
            font-weight: 600;
            font-size: 14px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .report-info {
            display: flex;
            justify-content: space-between;
            background: #f8f9fa;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            border-left: 4px solid #e91e63;
        }

        .info-item {
            text-align: center;
        }

        .info-label {
            font-size: 10px;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 3px;
        }

        .info-value {
            font-size: 13px;
            font-weight: bold;
            color: #2c3e50;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            border: 1px solid #e9ecef;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #e91e63, #f06292);
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #e91e63, #f06292);
            border-radius: 50%;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
            font-weight: bold;
        }

        .stat-label {
            font-size: 10px;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 900;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .stat-change {
            font-size: 9px;
            color: #28a745;
            font-weight: 600;
        }

        .section {
            margin-bottom: 35px;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border: 1px solid #e9ecef;
        }

        .section-header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
        }

        .section-icon {
            width: 24px;
            height: 24px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 12px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
            margin: 0;
        }

        .section-content {
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        th {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            font-weight: 700;
            color: #495057;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            font-size: 11px;
            color: #495057;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .amount {
            font-weight: 900;
            color: #e91e63;
            background: rgba(233, 30, 99, 0.1);
            padding: 4px 8px;
            border-radius: 4px;
        }

        .two-column {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 30px;
        }

        .three-column {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .no-data {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 40px 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            text-align: center;
            font-size: 10px;
            padding: 12px;
            border-top: 3px solid #e91e63;
        }

        .page-break {
            page-break-before: always;
        }

        .highlight {
            background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 600;
        }

        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .badge-primary {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 5px;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #e91e63, #f06292);
            border-radius: 4px;
        }

        .summary-box {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border: 1px solid #90caf9;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .summary-title {
            font-size: 14px;
            font-weight: 700;
            color: #1565c0;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .summary-item {
            text-align: center;
        }

        .summary-label {
            font-size: 10px;
            color: #1976d2;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .summary-value {
            font-size: 16px;
            font-weight: 900;
            color: #0d47a1;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(233, 30, 99, 0.03);
            font-weight: 900;
            z-index: -1;
            pointer-events: none;
        }

        .page-number {
            position: fixed;
            bottom: 20px;
            right: 20px;
            font-size: 10px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <!-- Watermark -->
    <div class="watermark">TOCHIS</div>
    
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <div class="logo">🍔 TOCHIS POS</div>
            <div class="subtitle">Reporte Detallado de Ventas y Análisis de Rendimiento</div>
            <div class="period">
                📅 Período: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}
                ({{ $startDate->diffInDays($endDate) + 1 }} días)
            </div>
        </div>
    </div>

    <!-- Report Information -->
    <div class="report-info">
        <div class="info-item">
            <div class="info-label">Fecha de Generación</div>
            <div class="info-value">{{ now()->format('d/m/Y H:i:s') }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Generado por</div>
            <div class="info-value">Sistema TOCHIS POS</div>
        </div>
        <div class="info-item">
            <div class="info-label">Versión</div>
            <div class="info-value">v2.0</div>
        </div>
        <div class="info-item">
            <div class="info-label">Tipo de Reporte</div>
            <div class="info-value">Ventas Completo</div>
        </div>
    </div>

    <!-- Executive Summary -->
    <div class="summary-box">
        <div class="summary-title">
            📊 Resumen Ejecutivo
        </div>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-label">Rendimiento General</div>
                <div class="summary-value">
                    @if($totalSales > 50000)
                        Excelente
                    @elseif($totalSales > 20000)
                        Bueno
                    @elseif($totalSales > 5000)
                        Regular
                    @else
                        Bajo
                    @endif
                </div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Días Analizados</div>
                <div class="summary-value">{{ $startDate->diffInDays($endDate) + 1 }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Promedio Diario</div>
                <div class="summary-value">${{ number_format($totalSales / ($startDate->diffInDays($endDate) + 1), 0) }}</div>
            </div>
        </div>
    </div>

    <!-- Estadísticas Principales -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-label">Ventas Totales</div>
            <div class="stat-value">${{ number_format($totalSales, 2) }}</div>
            <div class="stat-change">+{{ number_format(($totalSales / ($startDate->diffInDays($endDate) + 1)), 2) }}/día</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🛒</div>
            <div class="stat-label">Transacciones</div>
            <div class="stat-value">{{ number_format($totalTransactions) }}</div>
            <div class="stat-change">{{ number_format($totalTransactions / ($startDate->diffInDays($endDate) + 1), 1) }}/día</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📦</div>
            <div class="stat-label">Platillos Vendidos</div>
            <div class="stat-value">{{ number_format($totalProductsSold) }}</div>
            <div class="stat-change">{{ $totalTransactions > 0 ? number_format($totalProductsSold / $totalTransactions, 1) : 0 }} promedio/venta</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📈</div>
            <div class="stat-label">Ticket Promedio</div>
            <div class="stat-value">${{ number_format($averageSale, 2) }}</div>
            <div class="stat-change">Por transacción</div>
        </div>
    </div>

    <!-- Análisis de Métodos de Pago -->
    <div class="section">
        <div class="section-header">
            <div class="section-icon">💳</div>
            <h3 class="section-title">Análisis de Métodos de Pago</h3>
        </div>
        <div class="section-content">
            @if($paymentMethods->count() > 0)
                <div class="three-column" style="margin-bottom: 20px;">
                    @foreach($paymentMethods as $method)
                        <div style="text-align: center; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                            <div style="font-size: 24px; margin-bottom: 8px;">
                                @if($method->payment_method === 'cash') 💵
                                @elseif($method->payment_method === 'card') 💳
                                @else 📱 @endif
                            </div>
                            <div style="font-weight: 700; color: #2c3e50; margin-bottom: 5px;">
                                {{ ucfirst($method->payment_method) }}
                            </div>
                            <div style="font-size: 18px; font-weight: 900; color: #e91e63; margin-bottom: 5px;">
                                ${{ number_format($method->total, 2) }}
                            </div>
                            <div style="font-size: 10px; color: #6c757d;">
                                {{ $method->count }} transacciones
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $totalSales > 0 ? ($method->total / $totalSales) * 100 : 0 }}%"></div>
                            </div>
                            <div style="font-size: 10px; color: #495057; margin-top: 3px;">
                                {{ $totalSales > 0 ? number_format(($method->total / $totalSales) * 100, 1) : 0 }}% del total
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th>Método de Pago</th>
                            <th class="text-right">Transacciones</th>
                            <th class="text-right">Total Vendido</th>
                            <th class="text-right">Promedio/Transacción</th>
                            <th class="text-right">% del Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paymentMethods as $method)
                            <tr>
                                <td>
                                    <span class="badge {{ $method->payment_method === 'cash' ? 'badge-success' : ($method->payment_method === 'card' ? 'badge-primary' : 'badge-warning') }}">
                                        {{ ucfirst($method->payment_method) }}
                                    </span>
                                </td>
                                <td class="text-right">{{ number_format($method->count) }}</td>
                                <td class="text-right amount">${{ number_format($method->total, 2) }}</td>
                                <td class="text-right">${{ number_format($method->total / $method->count, 2) }}</td>
                                <td class="text-right highlight">{{ $totalSales > 0 ? number_format(($method->total / $totalSales) * 100, 1) : 0 }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">No hay datos de métodos de pago disponibles</div>
            @endif
        </div>
    </div>

    <!-- Platillos Más Vendidos y Ventas por Categoría -->
    <div class="two-column">
        <!-- Platillos Más Vendidos -->
        <div class="section">
            <div class="section-header">
                <div class="section-icon">🏆</div>
                <h3 class="section-title">Top 10 Platillos</h3>
            </div>
            <div class="section-content">
                @if($topProducts->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Platillo</th>
                                <th>Categoría</th>
                                <th class="text-right">Vendidos</th>
                                <th class="text-right">Ingresos Est.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topProducts as $index => $product)
                                <tr>
                                    <td class="text-center">
                                        @if($index < 3)
                                            <span style="font-size: 16px;">
                                                @if($index === 0) 🥇
                                                @elseif($index === 1) 🥈
                                                @else 🥉 @endif
                                            </span>
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </td>
                                    <td style="font-weight: 600;">{{ $product->name }}</td>
                                    <td>
                                        <span class="badge badge-primary">{{ $product->category->name }}</span>
                                    </td>
                                    <td class="text-right highlight">{{ number_format($product->total_sold) }}</td>
                                    <td class="text-right amount">${{ number_format($product->total_sold * $product->price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="no-data">No hay datos de Platillos disponibles</div>
                @endif
            </div>
        </div>

        <!-- Ventas por Categoría -->
        <div class="section">
            <div class="section-header">
                <div class="section-icon">🏷️</div>
                <h3 class="section-title">Ventas por Categoría</h3>
            </div>
            <div class="section-content">
                @if($salesByCategory->count() > 0)
                    @foreach($salesByCategory as $category)
                        <div style="margin-bottom: 15px; padding: 12px; background: rgba(233, 30, 99, 0.05); border-radius: 8px; border-left: 4px solid {{ $category->color ?? '#e91e63' }};">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                <div style="font-weight: 700; color: #2c3e50;">{{ $category->name }}</div>
                                <div class="amount">${{ number_format($category->total_sales, 2) }}</div>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 10px; color: #6c757d; margin-bottom: 5px;">
                                <span>{{ $category->products_count }} Platillos diferentes</span>
                                <span>{{ number_format($category->total_quantity) }} unidades vendidas</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $totalSales > 0 ? ($category->total_sales / $totalSales) * 100 : 0 }}%"></div>
                            </div>
                            <div style="font-size: 9px; color: #495057; margin-top: 3px;">
                                {{ $totalSales > 0 ? number_format(($category->total_sales / $totalSales) * 100, 1) : 0 }}% de las ventas totales
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="no-data">No hay datos de categorías disponibles</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Nueva página para análisis detallado -->
    <div class="page-break"></div>

    <!-- Análisis de Tendencias de Ventas -->
    <div class="section">
        <div class="section-header">
            <div class="section-icon">📊</div>
            <h3 class="section-title">Análisis de Tendencias - Ventas Diarias</h3>
        </div>
        <div class="section-content">
            @if($dailySales->count() > 0)
                <!-- Estadísticas de tendencias -->
                <div class="three-column" style="margin-bottom: 25px;">
                    @php
                        $maxSale = $dailySales->max('total');
                        $minSale = $dailySales->min('total');
                        $avgSale = $dailySales->avg('total');
                        $bestDay = $dailySales->where('total', $maxSale)->first();
                        $worstDay = $dailySales->where('total', $minSale)->first();
                    @endphp
                    
                    <div style="text-align: center; padding: 15px; background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%); border-radius: 12px; border: 1px solid #4caf50;">
                        <div style="font-size: 24px; margin-bottom: 8px;">🏆</div>
                        <div style="font-weight: 700; color: #2e7d32; margin-bottom: 5px;">Mejor Día</div>
                        <div style="font-size: 16px; font-weight: 900; color: #1b5e20; margin-bottom: 3px;">
                            ${{ number_format($maxSale, 2) }}
                        </div>
                        <div style="font-size: 10px; color: #388e3c;">{{ $bestDay->date }}</div>
                    </div>
                    
                    <div style="text-align: center; padding: 15px; background: linear-gradient(135deg, #fff3e0 0%, #ffcc02 100%); border-radius: 12px; border: 1px solid #ff9800;">
                        <div style="font-size: 24px; margin-bottom: 8px;">📈</div>
                        <div style="font-weight: 700; color: #f57c00; margin-bottom: 5px;">Promedio Diario</div>
                        <div style="font-size: 16px; font-weight: 900; color: #e65100; margin-bottom: 3px;">
                            ${{ number_format($avgSale, 2) }}
                        </div>
                        <div style="font-size: 10px; color: #ff9800;">Del período</div>
                    </div>
                    
                    <div style="text-align: center; padding: 15px; background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%); border-radius: 12px; border: 1px solid #f44336;">
                        <div style="font-size: 24px; margin-bottom: 8px;">📉</div>
                        <div style="font-weight: 700; color: #d32f2f; margin-bottom: 5px;">Día Menor</div>
                        <div style="font-size: 16px; font-weight: 900; color: #b71c1c; margin-bottom: 3px;">
                            ${{ number_format($minSale, 2) }}
                        </div>
                        <div style="font-size: 10px; color: #e57373;">{{ $worstDay->date }}</div>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th class="text-right">Ventas del Día</th>
                            <th class="text-right">vs. Promedio</th>
                            <th class="text-center">Rendimiento</th>
                            <th class="text-right">% del Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dailySales as $sale)
                            @php
                                $variance = (($sale->total - $avgSale) / $avgSale) * 100;
                                $performance = $sale->total >= $avgSale ? 'Bueno' : 'Bajo';
                                $performanceColor = $sale->total >= $avgSale ? '#28a745' : '#dc3545';
                                $performanceIcon = $sale->total >= $avgSale ? '📈' : '📉';
                            @endphp
                            <tr>
                                <td style="font-weight: 600;">{{ $sale->date }}</td>
                                <td class="text-right amount">${{ number_format($sale->total, 2) }}</td>
                                <td class="text-right" style="color: {{ $variance >= 0 ? '#28a745' : '#dc3545' }};">
                                    {{ $variance >= 0 ? '+' : '' }}{{ number_format($variance, 1) }}%
                                </td>
                                <td class="text-center">
                                    <span style="color: {{ $performanceColor }}; font-weight: 600;">
                                        {{ $performanceIcon }} {{ $performance }}
                                    </span>
                                </td>
                                <td class="text-right">{{ number_format(($sale->total / $totalSales) * 100, 1) }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">
                    📊 No hay datos de ventas diarias disponibles para el período seleccionado
                </div>
            @endif
        </div>
    </div>

    <!-- Ventas Recientes Detalladas -->
    <div class="section">
        <div class="section-header">
            <div class="section-icon">🕐</div>
            <h3 class="section-title">Registro Detallado de Ventas Recientes</h3>
        </div>
        <div class="section-content">
            @if($recentSales->count() > 0)
                <!-- Resumen de ventas recientes -->
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 20px;">
                    <div style="text-align: center; padding: 12px; background: #e3f2fd; border-radius: 8px;">
                        <div style="font-size: 14px; font-weight: 700; color: #1976d2;">Total Ventas</div>
                        <div style="font-size: 16px; font-weight: 900; color: #0d47a1;">{{ $recentSales->count() }}</div>
                    </div>
                    <div style="text-align: center; padding: 12px; background: #f3e5f5; border-radius: 8px;">
                        <div style="font-size: 14px; font-weight: 700; color: #7b1fa2;">Valor Total</div>
                        <div style="font-size: 16px; font-weight: 900; color: #4a148c;">${{ number_format($recentSales->sum('total'), 2) }}</div>
                    </div>
                    <div style="text-align: center; padding: 12px; background: #e8f5e8; border-radius: 8px;">
                        <div style="font-size: 14px; font-weight: 700; color: #388e3c;">Items Vendidos</div>
                        <div style="font-size: 16px; font-weight: 900; color: #1b5e20;">{{ $recentSales->sum(function($sale) { return $sale->saleDetails->sum('quantity'); }) }}</div>
                    </div>
                    <div style="text-align: center; padding: 12px; background: #fff3e0; border-radius: 8px;">
                        <div style="font-size: 14px; font-weight: 700; color: #f57c00;">Ticket Promedio</div>
                        <div style="font-size: 16px; font-weight: 900; color: #e65100;">${{ number_format($recentSales->avg('total'), 2) }}</div>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>N° Venta</th>
                            <th>Cajero</th>
                            <th>Fecha y Hora</th>
                            <th class="text-right">Items</th>
                            <th class="text-center">Método Pago</th>
                            <th class="text-right">Subtotal</th>
                            <th class="text-right">Total</th>
                            <th class="text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentSales as $sale)
                            <tr style="border-left: 3px solid {{ $sale->payment_method === 'cash' ? '#28a745' : ($sale->payment_method === 'card' ? '#007bff' : '#6f42c1') }};">
                                <td style="font-weight: 700; color: #2c3e50;">{{ $sale->sale_number }}</td>
                                <td>
                                    <div style="font-weight: 600;">{{ $sale->user->name }}</div>
                                    <div style="font-size: 9px; color: #6c757d;">ID: {{ $sale->user->id }}</div>
                                </td>
                                <td>
                                    <div style="font-weight: 600;">{{ $sale->created_at->format('d/m/Y') }}</div>
                                    <div style="font-size: 10px; color: #6c757d;">{{ $sale->created_at->format('H:i:s') }}</div>
                                </td>
                                <td class="text-right">
                                    <div class="highlight">{{ $sale->saleDetails->sum('quantity') }}</div>
                                    <div style="font-size: 9px; color: #6c757d;">{{ $sale->saleDetails->count() }} Platillos</div>
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $sale->payment_method === 'cash' ? 'badge-success' : ($sale->payment_method === 'card' ? 'badge-primary' : 'badge-warning') }}">
                                        @if($sale->payment_method === 'cash') 💵 Efectivo
                                        @elseif($sale->payment_method === 'card') 💳 Tarjeta
                                        @else 📱 {{ ucfirst($sale->payment_method) }} @endif
                                    </span>
                                </td>
                                <td class="text-right">
                                    ${{ number_format($sale->total * 0.9, 2) }}
                                    <div style="font-size: 9px; color: #6c757d;">+ impuestos</div>
                                </td>
                                <td class="text-right amount">${{ number_format($sale->total, 2) }}</td>
                                <td class="text-center">
                                    <span class="badge badge-success">✅ Completada</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Análisis adicional -->
                <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #17a2b8;">
                    <div style="font-weight: 700; color: #0c5460; margin-bottom: 10px;">📋 Análisis de Ventas Recientes:</div>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; font-size: 10px;">
                        <div>
                            <strong>Venta más Alta:</strong> ${{ number_format($recentSales->max('total'), 2) }}
                            ({{ $recentSales->where('total', $recentSales->max('total'))->first()->sale_number }})
                        </div>
                        <div>
                            <strong>Venta más Baja:</strong> ${{ number_format($recentSales->min('total'), 2) }}
                            ({{ $recentSales->where('total', $recentSales->min('total'))->first()->sale_number }})
                        </div>
                        <div>
                            <strong>Cajero más Activo:</strong> 
                            {{ $recentSales->groupBy('user.name')->map->count()->sortDesc()->keys()->first() }}
                            ({{ $recentSales->groupBy('user.name')->map->count()->sortDesc()->first() }} ventas)
                        </div>
                        <div>
                            <strong>Horario Pico:</strong> 
                            {{ $recentSales->groupBy(function($sale) { return $sale->created_at->format('H:00'); })->map->count()->sortDesc()->keys()->first() }} hrs
                        </div>
                    </div>
                </div>
            @else
                <div class="no-data">
                    🛒 No hay ventas registradas en el período seleccionado
                </div>
            @endif
        </div>
    </div>

    <!-- Conclusiones y Recomendaciones -->
    <div class="section">
        <div class="section-header">
            <div class="section-icon">💡</div>
            <h3 class="section-title">Conclusiones y Recomendaciones</h3>
        </div>
        <div class="section-content">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <!-- Fortalezas -->
                <div style="padding: 15px; background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%); border-radius: 12px; border: 1px solid #4caf50;">
                    <div style="font-weight: 700; color: #2e7d32; margin-bottom: 12px; display: flex; align-items: center;">
                        <span style="font-size: 18px; margin-right: 8px;">✅</span> Fortalezas Identificadas
                    </div>
                    <ul style="font-size: 10px; color: #1b5e20; line-height: 1.6;">
                        @if($totalTransactions > 100)
                            <li>Alto volumen de transacciones ({{ number_format($totalTransactions) }})</li>
                        @endif
                        @if($averageSale > 100)
                            <li>Ticket promedio saludable (${{ number_format($averageSale, 2) }})</li>
                        @endif
                        @if($paymentMethods->count() > 1)
                            <li>Diversificación en métodos de pago</li>
                        @endif
                        @if($topProducts->count() >= 5)
                            <li>Buen portafolio de Platillos populares</li>
                        @endif
                        <li>Sistema de registro eficiente y detallado</li>
                    </ul>
                </div>

                <!-- Oportunidades -->
                <div style="padding: 15px; background: linear-gradient(135deg, #fff3e0 0%, #ffcc02 100%); border-radius: 12px; border: 1px solid #ff9800;">
                    <div style="font-weight: 700; color: #f57c00; margin-bottom: 12px; display: flex; align-items: center;">
                        <span style="font-size: 18px; margin-right: 8px;">🎯</span> Oportunidades de Mejora
                    </div>
                    <ul style="font-size: 10px; color: #e65100; line-height: 1.6;">
                        @if($averageSale < 50)
                            <li>Incrementar ticket promedio con combos o promociones</li>
                        @endif
                        @if($paymentMethods->where('payment_method', 'card')->first() && $paymentMethods->where('payment_method', 'card')->first()->count < $totalTransactions * 0.3)
                            <li>Promover pagos con tarjeta para mayor seguridad</li>
                        @endif
                        @if($salesByCategory->count() > 0 && $salesByCategory->first()->total_sales > $totalSales * 0.6)
                            <li>Diversificar oferta para balancear categorías</li>
                        @endif
                        <li>Implementar programa de fidelización de clientes</li>
                        <li>Análisis de horarios pico para optimizar personal</li>
                    </ul>
                </div>
            </div>

            <!-- Métricas de rendimiento -->
            <div style="margin-top: 20px; padding: 15px; background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); border-radius: 12px; border: 1px solid #2196f3;">
                <div style="font-weight: 700; color: #1565c0; margin-bottom: 12px; display: flex; align-items: center;">
                    <span style="font-size: 18px; margin-right: 8px;">📊</span> Indicadores Clave de Rendimiento
                </div>
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; font-size: 10px;">
                    <div style="text-align: center;">
                        <div style="font-weight: 600; color: #0d47a1;">Eficiencia de Ventas</div>
                        <div style="font-size: 14px; font-weight: 900; color: #1976d2;">
                            {{ $totalTransactions > 0 ? number_format(($totalProductsSold / $totalTransactions), 1) : 0 }} items/venta
                        </div>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-weight: 600; color: #0d47a1;">Productividad Diaria</div>
                        <div style="font-size: 14px; font-weight: 900; color: #1976d2;">
                            {{ number_format($totalTransactions / ($startDate->diffInDays($endDate) + 1), 1) }} ventas/día
                        </div>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-weight: 600; color: #0d47a1;">Rotación de Platillos</div>
                        <div style="font-size: 14px; font-weight: 900; color: #1976d2;">
                            {{ number_format($totalProductsSold / ($startDate->diffInDays($endDate) + 1), 0) }} und/día
                        </div>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-weight: 600; color: #0d47a1;">Crecimiento Potencial</div>
                        <div style="font-size: 14px; font-weight: 900; color: #1976d2;">
                            @if($totalSales > 0)
                                {{ $dailySales->count() > 1 ? (($dailySales->last()->total > $dailySales->first()->total) ? '📈' : '📉') : '➡️' }}
                            @else
                                ➡️
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer mejorado -->
    <div class="footer">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <strong>🍔 TOCHIS POS</strong> | Sistema de Punto de Venta
            </div>
            <div>
                Reporte generado el {{ now()->format('d/m/Y H:i:s') }}
            </div>
            <div>
                Confidencial | Para uso interno únicamente
            </div>
        </div>
        <div style="margin-top: 5px; font-size: 8px; opacity: 0.8;">
            Datos procesados desde la base de datos en tiempo real | Versión {{ config('app.version', '2.0') }}
        </div>
    </div>

    <!-- Número de página -->
    <div class="page-number">
        Página 1 de 2
    </div>
</body>
</html>
