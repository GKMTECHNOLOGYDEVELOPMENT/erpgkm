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
    <style>
        @page {
            margin: 0; /* Elimina márgenes del PDF */
        }
        body {
            font-size: 10px;
            margin: 0;
            padding: 0;
            background-image: url('{{ public_path('assets/images/hojamembretada.jpg') }}');
            background-size: cover; /* Cubre toda la página */
            background-repeat: no-repeat;
            background-position: center;
            height: 100%;
            width: 100%;
        }
        .content {
            margin: 120px 20px 50px 20px; /* Margen superior, derecho, inferior, izquierdo */
            padding: 20px;
            background-color: rgba(255, 255, 255, 0); /* Fondo blanco semitransparente */
            border-radius: 8px; /* Bordes redondeados */
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 14px;
            color: #e3342f;
            margin: 0;
        }
        .header p {
            font-size: 10px;
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
            font-size: 10px;
        }
        th {
            background-color: #e3342f;
            color: white;
            text-transform: uppercase;
        }
        img {
            max-width: 60px;
            max-height: 60px;
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
            <h1>REPORTE DE CLIENTES GENERALES</h1>
            <p>Generado el: {{ $fechaFormateada }}</p>
        </div>

        <!-- Tabla de clientes -->
        <table>
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Foto</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clientes as $cliente)
                    <tr>
                        <td>{{ $cliente->descripcion }}</td>
                        <td>
                            @if ($cliente->foto_base64)
                                <!-- Imagen en base64 -->
                                <img src="data:image/jpeg;base64,{{ $cliente->foto_base64 }}" alt="Foto">
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

        <!-- Pie de página -->
        <div class="footer">
            <p>Generado el {{ $fechaPeru }}</p>
        </div>
    </div>
</body>
</html>
