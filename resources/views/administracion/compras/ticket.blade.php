<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Ticket</title>
    <style>
        @page {
            size: 80mm auto;
            margin: 2mm;
        }

        html,
        body {
            width: 80mm;
            margin: 0;
            font: 12px/1.25 Arial, Helvetica, sans-serif;
            color: #000
        }

        .c {
            text-align: center
        }

        .row {
            display: flex;
            justify-content: space-between
        }

        hr {
            border: 0;
            border-top: 1px dashed #000;
            margin: 6px 0
        }

        .m0 {
            margin: 0
        }

        .mb4 {
            margin-bottom: 4px
        }

        .barcode {
            margin-top: 8px;
            text-align: center
        }
    </style>
</head>

<body onload="window.print(); setTimeout(()=>window.close(), 300);">
    <div class="c">
        <strong>CITY FL</strong><br>
        Otro: 46457255<br>
        Paseo Cultural Castro<br>
        Teléfono: 04242476912<br>
        Email: ivngoanpalu@gmail.com
    </div>

    <hr>

    <div class="c">
        <strong>TICKET DE COMPRA</strong><br>
        Fecha: {{ \Carbon\Carbon::parse($compra->fechaEmision)->format('d/m/Y') }}<br>
        Cajero: Kevin Ruiz<br>
        TICKET NRO: {{ $compra->idCompra }}
    </div>

    <hr>

    <div>
        <strong>Proveedor:</strong> {{ $compra->proveedor->nombre ?? '-' }}<br>
        Documento: RUC {{ $compra->proveedor->ruc ?? '-' }}<br>
        Teléfono: {{ $compra->proveedor->telefono ?? '-' }}<br>
        Email: {{ $compra->proveedor->email ?? '-' }}<br>
        Dirección: {{ $compra->proveedor->direccion ?? '-' }}
    </div>

    <hr>

    <div class="row"><strong>Cant.</strong><strong>Precio</strong><strong>Total</strong></div>
    @foreach ($detalles as $d)
        <div class="mb4">{{ $d->articulo }}</div>
        <div class="row">
            <span>{{ number_format($d->cantidad, 0) }}</span>
            <span>${{ number_format($d->precio, 2) }}</span>
            <span>${{ number_format($d->subtotal, 2) }}</span>
        </div>
    @endforeach

    <hr>

    <div class="row"><span>SUBTOTAL</span><span>${{ number_format($compra->gravada, 2) }}</span></div>
    <div class="row"><span>IVA
            ({{ number_format($compra->igv > 0 ? ($compra->igv / max($compra->gravada, 1)) * 100 : 0, 0) }}%)</span><span>${{ number_format($compra->igv, 2) }}</span>
    </div>
    <div class="row"><strong>TOTAL</strong><strong>${{ number_format($compra->total, 2) }}</strong></div>

    <div class="barcode">
        <img alt="barcode" src="data:image/png;base64,{{ $barcode }}">
        <div>{{ $barcodeText }}</div>
    </div>
</body>

</html>
