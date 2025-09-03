<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Comprobante de Compra</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            color: #000;
        }

        body {
            width: 80mm;
            max-width: 80mm;
            margin: 0 auto;
            padding: 4mm;
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 11px;
            line-height: 1.3;
            background-color: #fff;
        }

        .header {
            text-align: center;
            padding-bottom: 6px;
            border-bottom: 1px solid #333;
            margin-bottom: 8px;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 3px;
            letter-spacing: 0.5px;
        }

        .company-info {
            font-size: 9px;
            margin-bottom: 2px;
            color: #444;
        }

        .ticket-title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            margin: 6px 0;
            text-transform: uppercase;
        }

        .ticket-info {
            padding: 6px;
            margin-bottom: 6px;
            border: 1px solid #ccc;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .info-label {
            font-weight: bold;
            color: #444;
        }

        .divider {
            height: 1px;
            background: #ccc;
            margin: 6px 0;
        }

        .section-title {
            font-weight: bold;
            margin: 5px 0;
            font-size: 11px;
            color: #444;
        }

        .supplier-info {
            padding: 6px;
            border: 1px solid #ccc;
            margin-bottom: 6px;
        }

        .supplier-info div {
            margin-bottom: 2px;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            padding: 3px 0;
            border-bottom: 1px solid #333;
            margin-bottom: 3px;
            font-size: 10px;
        }

        .products-container {
            max-height: 50vh;
            overflow-y: auto;
            margin-bottom: 6px;
            padding-right: 2px;
        }

        .product-row {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
            border-bottom: 1px solid #eee;
            font-size: 10px;
        }

        .product-name {
            flex: 3;
            text-align: left;
        }

        .product-qty {
            flex: 1;
            text-align: center;
        }

        .product-price {
            flex: 2;
            text-align: right;
        }

        .product-total {
            flex: 2;
            text-align: right;
            font-weight: bold;
        }

        .summary {
            margin-top: 6px;
            padding-top: 6px;
            border-top: 1px solid #ccc;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .total-row {
            font-weight: bold;
            font-size: 12px;
            margin-top: 5px;
            padding-top: 5px;
            border-top: 1px solid #333;
        }

        .barcode-container {
            text-align: center;
            margin: 10px 0;
            padding: 6px;
        }

        .barcode-text {
            font-family: 'Monaco', 'Consolas', monospace;
            letter-spacing: 1px;
            margin-top: 3px;
            font-size: 9px;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
            padding-top: 6px;
            border-top: 1px solid #ccc;
            font-size: 9px;
            color: #444;
        }

        .print-btn {
            display: block;
            width: 100%;
            padding: 8px;
            margin-top: 10px;
            background: #333;
            color: white;
            border: none;
            border-radius: 2px;
            cursor: pointer;
            font-weight: bold;
            font-size: 10px;
        }

        @media print {
            .print-btn {
                display: none;
            }

            body {
                padding: 0;
                margin: 0;
            }

            .products-container {
                max-height: none;
                overflow: visible;
            }
        }

        .products-container::-webkit-scrollbar {
            width: 3px;
        }

        .products-container::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .products-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 2px;
        }
    </style>
</head>

<body>
    <div class="header text-center">
        <!-- Nombre más grande -->
        <div class="font-extrabold text-2xl tracking-wide">
            GKM TECHNOLOGY S.A.C
        </div>

        <!-- RUC un poco más pequeño -->
        <div class="text-lg text-gray-800 mt-1">
            20543618587
        </div>

        <!-- Dirección más pequeña -->
        <div class="text-base text-gray-700 mt-1">
            Av. Santa Elvira Mza. B Lote. 8
        </div>

        <!-- Teléfono aún más pequeño -->
        <div class="text-sm text-gray-600 mt-1">
            0800 80142
        </div>

        <!-- Email el más pequeño -->
        <div class="text-xs text-gray-500 mt-1">
            atencionalcliente@gkmtechnology.com.pe
        </div>
    </div>



    <div class="ticket-title">COMPROBANTE DE COMPRA</div>

    <div class="ticket-info">
        <div class="info-row">
            <span class="info-label">Fecha:</span>
            <span>{{ \Carbon\Carbon::parse($compra->fechaEmision)->format('d/m/Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Cajero:</span>
            <span>{{ $compra->usuario->Nombre ?? 'Kevin' }} {{ $compra->usuario->apellidoPaterno ?? 'Ruiz' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Nro. Documento:</span>
            <span>{{ $compra->serie }}-{{ $compra->nro }}</span>
        </div>
    </div>

    <div class="divider"></div>

    <div class="section-title">INFORMACIÓN DEL PROVEEDOR</div>

    <div class="supplier-info">
        <div><strong>{{ $compra->proveedor->nombre ?? 'Microsoft' }}</strong></div>
        <div>RUC: {{ $compra->proveedor->ruc ?? '9875432132' }}</div>
        <div>Teléfono: {{ $compra->proveedor->telefono ?? '875432123' }}</div>
        <div>Email: {{ $compra->proveedor->email ?? 'microsft@microsoft.com' }}</div>
        <div>Dirección: {{ $compra->proveedor->direccion ?? 'Estado Unidos, California' }}</div>
    </div>

    <div class="divider"></div>

    <div class="section-title">DETALLES DE COMPRA</div>

    <div class="table-header">
        <div class="product-name">Producto</div>
        <div class="product-qty">Cant</div>
        <div class="product-price">P. Unit</div>
        <div class="product-total">Total</div>
    </div>

    <div class="products-container">
        @foreach ($compra->detalles as $d)
            <div class="product-row">
                <div class="product-name">{{ $d->producto->nombre ?? 'Producto' }}</div>
                <div class="product-qty">{{ number_format($d->cantidad, 0) }}</div>
                <div class="product-price">{{ $compra->moneda->simbolo ?? '$' }}{{ number_format($d->precio, 2) }}
                </div>
                <div class="product-total">
                    {{ $compra->moneda->simbolo ?? '$' }}{{ number_format($d->cantidad * $d->precio, 2) }}</div>
            </div>
        @endforeach
    </div>

    <div class="summary">
        <div class="summary-row">
            <span>Subtotal</span>
            <span>{{ $compra->moneda->simbolo ?? '$' }}{{ number_format($compra->total - $compra->igv, 2) }}</span>
        </div>
        <div class="summary-row">
            <span>IVA ({{ $compra->sujetoporcentaje ?? 16 }}%)</span>
            <span>{{ $compra->moneda->simbolo ?? '$' }}{{ number_format($compra->igv, 2) }}</span>
        </div>
        <div class="summary-row total-row">
            <span>TOTAL</span>
            <span>{{ $compra->moneda->simbolo ?? '$' }}{{ number_format($compra->total, 2) }}
                {{ $compra->moneda->codigo ?? 'USD' }}</span>
        </div>
    </div>

    <div class="barcode-container">
        <img alt="barcode" src="data:image/png;base64,{{ $barcode }}" style="max-width: 100%; height: 35px;">
        <div class="barcode-text">{{ $barcodeText }}</div>
    </div>

    <div class="footer">
        <div>Documento válido como comprobante de compra</div>
        <div>{{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</div>
        <div>¡Gracias por su preferencia!</div>
    </div>

    <button class="print-btn" onclick="window.print()">IMPRIMIR COMPROBANTE</button>
</body>

</html>
