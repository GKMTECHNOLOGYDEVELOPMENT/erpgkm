@php
    use Carbon\Carbon;
    setlocale(LC_TIME, 'Spanish_Spain.1252');
    $fechaFormateada = mb_strtoupper(strftime('%d %B %Y'), 'UTF-8');
    $fechaPeru = Carbon::now()->setTimezone('America/Lima')->format('d/m/Y H:i:s');
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Tiendas</title>
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
    </style>
</head>
<body>
    <div class="content">
        <!-- Encabezado -->
        <div class="header">
            <h1>REPORTE DE TIENDAS</h1>
            <p>Generado el: {{ $fechaFormateada }}</p>
            <p>Hora del servidor (Perú): {{ $fechaPeru }}</p>
        </div>

        <!-- Tabla de datos -->
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>RUC</th>
                    <th>Celular</th>
                    <th>Email</th>
                    <th>Dirección</th>
                    <th>Referencia</th>
                    <th>Cliente Asociado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tiendas as $index => $tienda)
                    <tr class="table-row">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $tienda->nombre }}</td>
                        <td>{{ $tienda->ruc }}</td>
                        <td>{{ $tienda->celular }}</td>
                        <td>{{ $tienda->email }}</td>
                        <td>{{ $tienda->direccion }}</td>
                        <td>{{ $tienda->referencia }}</td>
                        <td>{{ $tienda->cliente->nombre ?? 'Sin cliente' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Información adicional -->
        <div class="text-center mt-4">
            <p class="text-xs">
                Generado el {{ $fechaPeru }}
            </p>
        </div>
    </div>
</body>
</html>
