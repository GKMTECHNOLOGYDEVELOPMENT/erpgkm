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
    <title>Reporte de Clientes Generales</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-size: 10px;
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
        }
        .container {
            margin: 10px;
            padding: 10px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            padding-bottom: 5px;
            margin-bottom: 10px;
            border-bottom: 2px solid #e3342f;
            text-align: center;
        }
        .header h1 {
            font-size: 14px;
            color: #e3342f;
            margin: 0;
        }
        .header p {
            font-size: 10px;
            margin: 0;
            color: #555;
        }
        .table-header {
            background-color: #e3342f;
            color: white;
            text-transform: uppercase;
            font-size: 10px;
        }
        .table-row:nth-child(odd) {
            background-color: #f9fafb;
        }
        .table-row:nth-child(even) {
            background-color: #fefefe;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: auto;
        }
        th, td {
            padding: 8px;
            text-align: center;
            border: 1px solid #e3342f;
        }
        th {
            font-size: 10px;
            font-weight: bold;
        }
        td {
            font-size: 10px;
        }
        img {
            width: 40px;
            height: 40px;
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
    </style>
</head>
<body>
    <div class="container">
        <!-- Encabezado -->
        <div class="header">
            <h1>REPORTE DE CLIENTES GENERALES</h1>
            <p>Generado el: {{ $fechaFormateada }}</p>
        </div>

        <!-- Tabla de clientes -->
        <div class="content">
            <table>
                <thead class="table-header">
                    <tr>
                        <th>Descripción</th>
                        <th>Foto</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clientes as $cliente)
                        <tr class="table-row">
                            <td>{{ $cliente->descripcion }}</td>
                            <td>
                                @if ($cliente->foto)
                                    <img src="{{ public_path('/' . $cliente->foto) }}" alt="Foto">
                                @else
                                    <span>Sin imagen</span>
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
        </div>

        <!-- Información adicional -->
        <div class="text-center mt-4">
            <p class="text-xs">
                Generado el {{ $fechaPeru }}
            </p>
        </div>
    </div>
</body>
</html>
