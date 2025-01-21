@php
    setlocale(LC_TIME, 'Spanish_Spain.1252');
    $fechaFormateada = mb_strtoupper(strftime('%d %B %Y'), 'UTF-8');
    $fechaPeru = \Carbon\Carbon::now()->setTimezone('America/Lima')->format('d/m/Y H:i:s');
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Artículos</title>
    <style>
        @page {
            margin: 0; /* Elimina márgenes de la página */
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            background-image: url('{{ public_path('assets/images/hojamembretada.jpg') }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }
        .content {
            margin: 0;
            padding: 20px; /* Espaciado interno */
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e3342f;
        }
        .header h1 {
            font-size: 16px;
            color: #e3342f;
            margin: 0;
        }
        .header p {
            font-size: 12px;
            margin: 5px 0;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #e3342f;
        }
        th, td {
            text-align: center;
            padding: 8px;
            font-size: 12px;
        }
        th {
            background-color: #e3342f;
            color: white;
            text-transform: uppercase;
        }
        .table-row:nth-child(odd) {
            background-color: #f9fafb;
        }
        .table-row:nth-child(even) {
            background-color: #fefefe;
        }
        img {
            max-width: 50px;
            max-height: 50px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #ddd;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 10px;
            color: #fff;
        }
        .badge-success {
            background-color: #28a745;
        }
        .badge-danger {
            background-color: #dc3545;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="content">
        <!-- Encabezado -->
        <div class="header">
            <h1>REPORTE DE ARTÍCULOS</h1>
            <p>Generado el: {{ $fechaFormateada }}</p>
            <p>Hora del servidor (Perú): {{ $fechaPeru }}</p>
        </div>

        <!-- Tabla de artículos -->
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Foto</th>
                    <th>Nombre</th>
                    <th>Unidad</th>
                    <th>Código</th>
                    <th>Stock Total</th>
                    <th>Serie</th>
                    <th>Tipo de Artículo</th>
                    <th>Modelo</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($articulos as $index => $articulo)
                    <tr class="table-row">
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @if ($articulo->foto)
                                <img src="{{ public_path($articulo->foto) }}" alt="Foto">
                            @else
                                <span>Sin imagen</span>
                            @endif
                        </td>
                        <td>{{ $articulo->nombre }}</td>
                        <td>{{ $articulo->unidad->nombre ?? 'N/A' }}</td>
                        <td>{{ $articulo->codigo }}</td>
                        <td>{{ $articulo->stock_total }}</td>
                        <td>{{ $articulo->serie ?: 'N/A' }}</td>
                        <td>{{ $articulo->tipoarticulo->nombre ?? 'N/A' }}</td>
                        <td>{{ $articulo->modelo->nombre ?? 'N/A' }}</td>
                        <td>
                            <span class="badge {{ $articulo->estado ? 'badge-success' : 'badge-danger' }}">
                                {{ $articulo->estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Información adicional -->
        <div class="footer">
            <p>Generado el {{ $fechaPeru }}</p>
        </div>
    </div>
</body>
</html>
