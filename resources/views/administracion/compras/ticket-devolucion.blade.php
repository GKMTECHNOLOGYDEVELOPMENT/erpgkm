<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Comprobante de Devolución</title>
    <style>
        body {
            width: 80mm;
            max-width: 80mm;
            margin: 0 auto;
            padding: 4mm;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #000;
            background: #fff;
        }

        .header,
        .footer,
        .ticket-title,
        .section-title {
            text-align: center;
        }

        .header .company-name {
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .header .company-info {
            font-size: 10px;
            margin-top: 2px;
        }

        .ticket-title {
            font-size: 13px;
            font-weight: bold;
            margin: 8px 0;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 5px 0;
        }

        .ticket-info,
        .supplier-info,
        .summary {
            border: 1px solid #ccc;
            padding: 6px;
            margin-bottom: 8px;
        }

        .info-row,
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }

        .info-label {
            font-weight: bold;
        }

        .section-title {
            font-weight: bold;
            font-size: 11px;
            color: #444;
            margin-bottom: 4px;
        }

        .table-header,
        .product-row {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
        }

        .table-header {
            font-weight: bold;
            border-bottom: 1px solid #333;
            padding-bottom: 3px;
            margin-bottom: 3px;
        }

        .product-row {
            border-bottom: 1px dashed #eee;
            padding: 2px 0;
        }

        .product-name {
            flex: 3;
            text-align: left;
        }

        .product-qty {
            flex: 1;
            text-align: center;
        }

        .product-price,
        .product-total {
            flex: 2;
            text-align: right;
        }

        .total-row {
            font-weight: bold;
            font-size: 12px;
            margin-top: 4px;
            border-top: 1px solid #000;
            padding-top: 4px;
        }

        .barcode-container {
            text-align: center;
            margin-top: 10px;
        }

        .barcode-text {
            font-family: 'Courier New', monospace;
            font-size: 10px;
            margin-top: 4px;
        }

        .footer {
            font-size: 9px;
            margin-top: 12px;
            color: #555;
        }

        .print-btn {
            display: block;
            width: 100%;
            padding: 8px;
            background: #000;
            color: #fff;
            border: none;
            font-size: 10px;
            margin-top: 10px;
            cursor: pointer;
        }

        @media print {
            .print-btn {
                display: none;
            }
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

    <!-- TÍTULO -->
    <div class="ticket-title">COMPROBANTE DE DEVOLUCIÓN</div>

    <!-- INFO GENERAL -->
    <div class="ticket-info">
        <div class="info-row">
            <span class="info-label">Fecha:</span>
            <span>{{ \Carbon\Carbon::now()->format('d/m/Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Cajero:</span>
            <span>{{ $compra->usuario->Nombre ?? 'Nombre' }} {{ $compra->usuario->apellidoPaterno ?? '' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Código Compra:</span>
            <span>{{ $codigo }}</span>
        </div>
    </div>

    <!-- DETALLES -->
    <div class="section-title">DETALLES DE DEVOLUCIÓN</div>
    <div class="table-header">
        <div class="product-name">Producto</div>
        <div class="product-qty">Cant</div>
        <div class="product-price">P. Unit</div>
        <div class="product-total">Total</div>
    </div>

    @foreach ($items as $item)
        <div class="product-row">
            <div class="product-name">{{ $item['nombre'] }}</div>
            <div class="product-qty">{{ $item['cantidad'] }}</div>
            <div class="product-price">{{ $simbolo }}{{ number_format($item['precio'], 2) }}</div>
            <div class="product-total">{{ $simbolo }}{{ number_format($item['total'], 2) }}</div>
        </div>
    @endforeach

    <!-- TOTALES -->
    <div class="summary">
        <div class="summary-row">
            <span>Subtotal</span>
            <span>{{ $simbolo }}{{ number_format($subtotal, 2) }}</span>
        </div>
        <div class="summary-row">
            <span>IGV ({{ $porcIgv }}%)</span>
            <span>{{ $simbolo }}{{ number_format($igv, 2) }}</span>
        </div>
        <div class="summary-row total-row">
            <span>TOTAL</span>
            <span>{{ $simbolo }}{{ number_format($total, 2) }}</span>
        </div>
    </div>

    <!-- BARRA CÓDIGO -->
    <div class="barcode-container">
        <img src="data:image/png;base64,{{ $barcode }}" alt="barcode" style="max-width: 100%; height: 40px;">
        <div class="barcode-text">{{ $codigo }}</div>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        Documento válido como comprobante de devolución<br>
        {{ now()->format('d/m/Y H:i:s') }}<br>
        ¡Gracias por su preferencia!
    </div>

    <!-- BOTÓN -->
    <button class="print-btn" onclick="window.print()">IMPRIMIR COMPROBANTE</button>
</body>

</html>
