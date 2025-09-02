<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket de Compra</title>
    <style>
        body {
            width: 80mm;
            max-width: 80mm;
            margin: 0;
            padding: 3mm;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.25;
            color: #000;
        }
        
        .c {
            text-align: center;
        }
        
        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }
        
        hr {
            border: 0;
            border-top: 1px dashed #000;
            margin: 6px 0;
        }
        
        .mb4 {
            margin-bottom: 4px;
        }
        
        .barcode {
            margin-top: 8px;
            text-align: center;
        }
        
        .product-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }
        
        .product-name {
            flex: 2;
            text-align: left;
        }
        
        .product-qty, .product-price, .product-total {
            flex: 1;
            text-align: right;
        }
        
        .print-btn {
            display: block;
            width: 100%;
            padding: 8px;
            margin-top: 10px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="c">
        <strong>CITY FL</strong><br>
        Otro: 46457255<br>
        Paseo Cultural Castro<br>
        Teléfono: 04242476912<br>
        Email: invgoanpalu@gmail.com
    </div>

    <hr>

    <div class="c">
        <strong>TICKET DE COMPRA</strong><br>
        Fecha: {{ \Carbon\Carbon::parse($compra->fechaEmision)->format('d/m/Y') }}<br>
        Cajero: {{ $compra->usuario->Nombre ?? 'Kevin' }} {{ $compra->usuario->apellidoPaterno ?? 'Ruiz' }}<br>
        <strong>TICKET NRO:</strong> {{ $compra->serie }}-{{ $compra->nro }}
    </div>

    <hr>

    <div>
        <strong>Proveedor:</strong> {{ $compra->proveedor->nombre ?? 'Microsoft' }}<br>
        <strong>Documento:</strong> RUC {{ $compra->proveedor->ruc ?? '9875432132' }}<br>
        <strong>Teléfono:</strong> {{ $compra->proveedor->telefono ?? '875432123' }}<br>
        <strong>Email:</strong> {{ $compra->proveedor->email ?? 'microsft@microsoft.com' }}<br>
        <strong>Dirección:</strong> {{ $compra->proveedor->direccion ?? 'Estado Unidos, California' }}
    </div>

    <hr>

    <div class="row">
        <strong>Cant.</strong>
        <strong>Precio</strong>
        <strong>Total</strong>
    </div>
    
    @foreach ($compra->detalles as $d)
    <div class="product-row">
        <div class="product-name">{{ $d->producto->nombre ?? 'Producto' }}</div>
        <div class="product-qty">{{ number_format($d->cantidad, 0) }}</div>
        <div class="product-price">{{ $compra->moneda->simbolo ?? '$' }}{{ number_format($d->precio, 2) }}</div>
        <div class="product-total">{{ $compra->moneda->simbolo ?? '$' }}{{ number_format($d->cantidad * $d->precio, 2) }}</div>
    </div>
    @endforeach

    <hr>

    <div class="row">
        <span>SUBTOTAL</span>
        <span>{{ $compra->moneda->simbolo ?? '$' }}{{ number_format($compra->total - $compra->igv, 2) }}</span>
    </div>
    <div class="row">
        <span>IVA ({{ $compra->sujetoporcentaje ?? 16 }}%)</span>
        <span>{{ $compra->moneda->simbolo ?? '$' }}{{ number_format($compra->igv, 2) }}</span>
    </div>
    <div class="row">
        <strong>TOTAL</strong>
        <strong>{{ $compra->moneda->simbolo ?? '$' }}{{ number_format($compra->total, 2) }} {{ $compra->moneda->codigo ?? 'USD' }}</strong>
    </div>

    <div class="barcode">
        <img alt="barcode" src="data:image/png;base64,{{ $barcode }}" style="max-width: 100%; height: auto;">
        <div>{{ $barcodeText }}</div>
    </div>
    
    <div class="c" style="margin-top: 10px;">
        {{ $compra->proveedor->nombre ?? 'Microsoft' }}<br>
        {{ $compra->usuario->Nombre ?? 'Kevin' }} {{ $compra->usuario->apellidoPaterno ?? 'Ruiz' }}
    </div>

    <button class="print-btn" onclick="window.print()">IMPRIMIR TICKET</button>
</body>
</html>