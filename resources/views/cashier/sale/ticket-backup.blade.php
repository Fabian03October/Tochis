<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #{{ $sale->sale_number }} - TOCHIS</title>
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
            margin: 8px 0;
            padding: 8px;
            background: #f0f0f0;
            border: 2px solid #000;
            border-radius: 5px;
        }
        
        .order-number .label {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        .order-number .number {
            font-size: 24px;
            font-weight: bold;
            color: #000;
            letter-spacing: 2px;
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
    </div>
    
    <div class="order-number">
        <div class="label">NÚMERO DE ORDEN</div>
        <div class="number">#{{ substr($sale->sale_number, -4) }}</div>
    </div>
    
    <div style="text-align: center; margin: 20px 0;">
        <h3>TICKET DE PRUEBA</h3>
        <p>Si ves esto, el ticket funciona correctamente</p>
    </div>
    
</body>
</html>
