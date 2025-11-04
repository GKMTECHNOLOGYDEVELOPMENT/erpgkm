<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cotización {{ $datos['numero_cotizacion'] }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .info-section { margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f5f5f5; }
        .totals { margin-top: 20px; text-align: right; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>COTIZACIÓN</h1>
        <h2>{{ $datos['numero_cotizacion'] }}</h2>
    </div>

    <div class="info-section">
        <p><strong>Cliente:</strong> {{ $datos['cliente']['nombre'] }}</p>
        <p><strong>Fecha:</strong> {{ $datos['fecha_emision'] }}</p>
        <p><strong>Válida hasta:</strong> {{ $datos['valida_hasta'] }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Precio Unit.</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($datos['items'] as $item)
            <tr>
                <td>{{ $item['descripcion'] }}</td>
                <td>{{ $item['cantidad'] }}</td>
                <td>{{ $datos['moneda']['simbolo'] }} {{ number_format($item['precio_unitario'], 2) }}</td>
                <td>{{ $datos['moneda']['simbolo'] }} {{ number_format($item['subtotal'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <p><strong>Subtotal:</strong> {{ $datos['moneda']['simbolo'] }} {{ number_format($datos['subtotal'], 2) }}</p>
        @if($datos['incluir_igv'])
        <p><strong>IGV (18%):</strong> {{ $datos['moneda']['simbolo'] }} {{ number_format($datos['igv'], 2) }}</p>
        @endif
        <p><strong>TOTAL:</strong> {{ $datos['moneda']['simbolo'] }} {{ number_format($datos['total'], 2) }}</p>
    </div>

    <div class="footer">
        <p>GKM Technology - Av. Santa Elvira Mza. B Lote. 8.</p>
        <p>Tel: 0800-80142 | Email: atencionalcliente@gkmtechnology.com.pe</p>
    </div>
</body>
</html>