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
    <title>Reporte de CAST</title>
    <style>
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
        .container {
            margin: 10px;
            padding: 10px;
            background-color: rgba(255, 255, 255, 0); /* Blanco semitransparente */
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #e3342f;
            padding-bottom: 10px;
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
        <div class="header">
            <h1>REPORTE DE CAST</h1>
            <p>Generado el: {{ $fechaFormateada }}</p>
            <p>Hora del servidor (Perú): {{ $fechaPeru }}</p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>RUC</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Departamento</th>
                    <th>Provincia</th>
                    <th>Distrito</th>
                    <th>Dirección</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($casts as $cast)
                    <tr class="table-row">
                        <td>{{ $cast->ruc }}</td>
                        <td>{{ $cast->nombre }}</td>
                        <td>{{ $cast->telefono }}</td>
                        <td>{{ $cast->email }}</td>
                        <td>{{ $cast->departamento }}</td>
                        <td>{{ $cast->provincia }}</td>
                        <td>{{ $cast->distrito }}</td>
                        <td>{{ $cast->direccion }}</td>
                        <td>
                            <span class="badge {{ $cast->estado == 'Activo' ? 'badge-success' : 'badge-danger' }}">
                                {{ $cast->estado == 'Activo' ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="text-center mt-4">
            <p class="text-xs">Generado el {{ $fechaPeru }}</p>
        </div>
    </div>
</body>
</html>