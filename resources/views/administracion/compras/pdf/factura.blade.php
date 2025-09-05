<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Factura de Compra</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
            padding: 20px;
        }

        h2 {
            margin-bottom: 0;
        }

        p {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        th {
            background-color: #f1f1f1;
        }

        .totales td {
            font-weight: bold;
            text-align: right;
        }
    </style>
</head>

<body>
    <h2>Factura de Compra</h2>
    <p><strong>Proveedor:</strong> {{ $compra->proveedor->nombre ?? 'N/A' }}</p>
    <p><strong>Dirección:</strong> {{ $compra->proveedor->direccion ?? 'N/A' }}</p>
    <p><strong>Documento:</strong> {{ $compra->proveedor->numeroDocumento ?? '---' }}</p>
    <p><strong>Fecha de Emisión:</strong> {{ \Carbon\Carbon::parse($compra->fechaEmision)->format('d/m/Y') }}</p>
    <p><strong>Serie / Número:</strong> {{ $compra->serie }} - {{ $compra->nro }}</p>
    <p><strong>Moneda:</strong> {{ $compra->moneda->nombre ?? 'SOLES' }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $subtotal = 0; @endphp
            @foreach ($compra->detalles as $i => $detalle)
                @php
                    $cantidad = $detalle->cantidad;
                    $precio = $detalle->precio;
                    $sub = $cantidad * $precio;
                    $subtotal += $sub;
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $detalle->producto->nombre ?? '---' }}</td>
                    <td>{{ $cantidad }}</td>
                    <td>{{ $compra->moneda->simbolo }}{{ number_format($precio, 2) }}</td>
                    <td>{{ $compra->moneda->simbolo }}{{ number_format($sub, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            @php
                $igv = ((float) ($compra->sujetoporcentaje ?? 0) / 100) * $subtotal;
                $total = $subtotal + $igv;
            @endphp
            <tr class="totales">
                <td colspan="4">SUBTOTAL</td>
                <td>{{ $compra->moneda->simbolo }}{{ number_format($subtotal, 2) }}</td>
            </tr>
            <tr class="totales">
                <td colspan="4">IGV ({{ $compra->sujetoporcentaje ?? 0 }}%)</td>
                <td>{{ $compra->moneda->simbolo }}{{ number_format($igv, 2) }}</td>
            </tr>
            <tr class="totales">
                <td colspan="4">TOTAL</td>
                <td>{{ $compra->moneda->simbolo }}{{ number_format($total, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</body>

</html>
