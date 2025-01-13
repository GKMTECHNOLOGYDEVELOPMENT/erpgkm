<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Clientes Generales</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: #fff;
        }
        .badge {
            padding: 5px 10px;
            border-radius: 4px;
            color: #fff;
            font-size: 12px;
        }
        .badge-success {
            background-color: #28a745;
        }
        .badge-danger {
            background-color: #dc3545;
        }
        img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>Reporte de Clientes Generales</h1>
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
                    @if ($cliente->logo)
                        <img src="{{ public_path('storage/' . $cliente->logo) }}" alt="Foto del Cliente">
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
</body>
</html>
