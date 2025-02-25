<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe Técnico</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }

        .section {
            background-color: #a71930;
            color: white;
            padding: 5px;
            font-weight: bold;
            margin-top: 10px;
        }

        .data {
            padding: 5px;
        }
    </style>
</head>

<body>

    <div class="title">INFORME TÉCNICO</div>

    <div class="section">DATOS DEL CLIENTE</div>
    <div class="data"><strong>Cliente:</strong> {{ $orden->cliente->nombre ?? 'No asignado' }}</div>
    <div class="data"><strong>DNI/RUC:</strong> {{ $orden->cliente->documento ?? 'No disponible' }}</div>
    <div class="data"><strong>Dirección:</strong> {{ $orden->direccion ?? 'No registrada' }}</div>

    <div class="section">DATOS DEL PRODUCTO</div>
    <div class="data"><strong>Tipo de Producto:</strong> {{ $orden->modelo->categoria->nombre ?? 'No especificado' }}</div>
    <div class="data"><strong>Marca:</strong> {{ $orden->marca->nombre ?? 'Sin marca' }}</div>
    <div class="data"><strong>Modelo:</strong> {{ $orden->modelo->nombre ?? 'Sin modelo' }}</div>
    <div class="data"><strong>Serie:</strong> {{ $orden->serie ?? 'No registrada' }}</div>

    <div class="section">FALLA REPORTADA</div>
    <div class="data">{{ $orden->fallaReportada ?? 'No especificada' }}</div>

    @php
        // Definir los títulos de las secciones según el idEstadoots
        $secciones = [
            1 => 'DETALLES ESTÉTICOS',
            2 => 'DIAGNÓSTICO',
            3 => 'SOLUCIÓN',
            4 => 'OBSERVACIONES',
        ];
    @endphp

    @foreach ($secciones as $idEstado => $titulo)
        @if (isset($justificaciones[$idEstado]) && is_iterable($justificaciones[$idEstado]))
            @php
                $justificacionesValidas = collect($justificaciones[$idEstado])->filter(function ($j) {
                    return !empty($j->justificacion);
                });
            @endphp

            @if ($justificacionesValidas->count() > 0)
                <div class="section">{{ $titulo }}</div>
                <div class="data">
                    @foreach ($justificacionesValidas as $justificacion)
                        <p>{{ $justificacion->justificacion }}</p>
                    @endforeach
                </div>
            @endif
        @endif
    @endforeach

</body>

</html>
