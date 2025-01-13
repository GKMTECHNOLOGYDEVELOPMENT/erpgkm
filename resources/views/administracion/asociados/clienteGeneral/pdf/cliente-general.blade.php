<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Clientes Generales</title>
    <style>
        /* General */
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            color: #333;
            line-height: 1.5;
        }

        /* Header */
        header {
            background-color: #d32f2f; /* Rojo elegante */
            color: #ffffff;
            padding: 10px 0;
            text-align: center;
            border-bottom: 3px solid #b71c1c;
        }

        header h1 {
            font-size: 20px;
            letter-spacing: 1px;
            margin: 0;
        }

        header p {
            font-size: 12px;
            margin-top: 5px;
        }

        /* Tabla */
        table {
            width: 90%;
            margin: 10px auto;
            border-collapse: collapse;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
        }

        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #b71c1c; /* Rojo más oscuro */
            color: #ffffff;
            font-size: 12px;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa; /* Gris claro */
        }

        tr:hover {
            background-color: #f1f1f1; /* Hover gris */
        }

        td {
            font-size: 12px;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 5px 8px;
            border-radius: 8px;
            color: #fff;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-success {
            background-color: #28a745; /* Verde */
        }

        .badge-danger {
            background-color: #dc3545; /* Rojo */
        }

        /* Imagen */
        img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        /* Footer */
        footer {
            text-align: center;
            margin-top: 10px;
            padding: 5px 0;
            font-size: 10px;
            color: #777;
            border-top: 2px solid #d32f2f;
        }

        footer p {
            margin: 0;
        }

        /* Detalles del PDF */
        .report-details {
            width: 90%;
            margin: 0 auto 10px;
            text-align: right;
            font-size: 10px;
            color: #555;
        }

        .report-details span {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <h1>Reporte de Clientes Generales</h1>
        <p>Generado el: {{ now()->format('d/m/Y') }}</p>
    </header>

    <!-- Detalles del Reporte -->
    <div class="report-details">
        <p><span>Total Clientes:</span> {{ count($clientes) }}</p>
    </div>

    <!-- Tabla -->
    <table>
        <thead>
            <tr>
                <th>Descripción</th>
                <th>Foto</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($clientes as $cliente)
            <tr>
                <td>{{ $cliente->descripcion }}</td>
                <td>
                    @if ($cliente->foto)
                        <img src="{{ public_path('/' . $cliente->foto) }}" alt="Foto del Cliente">
                    @else
                        Sin imagen
                    @endif
                </td>
                <td>
                    <span class="badge {{ $cliente->estado ? 'badge-success' : 'badge-danger' }}">
                        {{ $cliente->estado ? 'Activo' : 'Inactivo' }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <footer>
        <p>&copy; {{ now()->year }} Solutions Force. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
