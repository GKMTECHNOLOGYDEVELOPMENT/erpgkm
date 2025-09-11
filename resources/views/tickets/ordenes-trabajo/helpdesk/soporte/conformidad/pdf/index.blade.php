<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Constancia de Conformidad</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #0a0a0a;
            background: url('{{ $backgroundBase64 }}') no-repeat center top;
            background-size: cover;
            margin: 0;
            padding: 0;
        }

        /* Contenedor general */
        .contenido-wrapper {
            max-width: 720px;
            /* 👈 ancho base del contenido */
            margin: 200px auto 60px auto;
            padding: 0 40px;
        }

        /* Título más angosto */
        .titulo {
            max-width: 400px;
            /* 👈 más angosto que el contenido */
            margin: 0 auto 25px auto;
            /* centrado */
            text-align: center;
        }

        .titulo h2 {
            font-size: 16px;
            text-transform: uppercase;
            font-weight: bold;
            line-height: 1.4;
        }

        .contenido {
            max-width: 650px;
            /* 👈 más ancho que el título */
            margin: 0 auto;
            text-align: justify;
            line-height: 1.8;
            font-size: 13px;
        }

        .bold {
            font-weight: bold;
        }

        .firmas {
            display: flex;
            justify-content: space-between;
            margin-top: 120px;
        }

        .firmas .bloque {
            text-align: center;
            width: 45%;
        }

        .firmas img {
            height: 100px;
            object-fit: contain;
            margin-bottom: -10px;
            transform: translateY(10px);
        }

        .firmas .datos-firma {
            margin-top: 4px;
            font-size: 11px;
            color: #000;
        }

        .footer {
            margin-top: 120px;
            text-align: center;
            font-size: 11px;
            color: #333;
        }

        hr {
            width: 80%;
            margin: 8px auto 4px auto;
            border: none;
            border-top: 1px solid #000;
        }
    </style>
</head>

<body>
    <div class="contenido-wrapper">
        @php
            $cliente = $orden->cliente;
            $tienda = $orden->tienda;
            $fecha = $fechaCreacion ?? \Carbon\Carbon::now()->format('d/m/Y');
            $nombreTienda = strtoupper($tienda->nombre ?? 'TIENDA');
            $direccionTienda = $tienda->direccion ?? 'DIRECCIÓN NO DISPONIBLE';
            $descripcionServicio = strtoupper($orden->fallaReportada ?? 'EL SERVICIO REALIZADO');
        @endphp

        <!-- 🔻 TÍTULO tipo pirámide (más angosto) -->
        <div class="titulo">
            <h2>
                CONFORMIDAD DEL SERVICIO <br> 
                {{ $nombreTienda }}
            </h2>
        </div>

        <!-- 🔻 CONTENIDO más ancho -->
        <div class="contenido">
            <p>
                Mediante la presente se brinda la conformidad del servicio realizado por la empresa
                <span class="bold">GKM TECHNOLOGY S.A.C.</span> por el servicio de
                <span class="bold">“{{ $descripcionServicio }}”</span>,
                en la tienda <span class="bold">{{ $nombreTienda }}</span>,
                ubicada en <span class="bold">{{ $direccionTienda }}</span>,
                en el distrito de <span class="bold">{{ mb_strtoupper($distritoNombre) }}</span>,
                provincia de <span class="bold">{{ mb_strtoupper($provinciaNombre) }}</span>.
            </p>


            <p style="margin-top: 30px;">
                Lima,
                @if ($fechaCreacion !== 'N/A')
                    {{ \Carbon\Carbon::createFromFormat('d/m/Y', $fechaCreacion)->translatedFormat('d \\d\\e F \\d\\e Y') }}
                @else
                    Fecha no disponible
                @endif
            </p>
        </div>

        <!-- 🔻 Firmas -->
        <div class="firmas">
            <!-- Cliente -->
            <div class="bloque">
                @if ($firmaCliente)
                    <img src="{{ $firmaCliente }}" alt="Firma Cliente">
                @else
                    <span class="text-xs text-gray-500 font-bold">Cliente no firmó</span>
                @endif
                <hr>
                <strong>PERSONA RESPONSABLE DE TIENDA</strong><br>
                <div class="datos-firma" style="color: #555;">
                    {{ $firma->nombreencargado ?? ($cliente->nombre ?? 'N/A') }}<br>
                    @if (!empty($firma->cargo))
                        <p style="margin: 2px 0;">
                            CARGO: {{ mb_strtoupper($firma->cargo) }}<br>
                        </p>
                    @endif
                    <p style="margin: 2px 0;">
                        {{ mb_strtoupper($firma->tipodocumento ?? ($cliente->tipodocumento->nombre ?? 'DNI')) }}:
                        {{ mb_strtoupper($firma->documento ?? ($cliente->documento ?? 'N/A')) }}
                    </p>
                </div>
            </div>

            <!-- Técnico -->
            <div class="bloque">
                @if ($firmaTecnico)
                    <img src="{{ $firmaTecnico }}" alt="Firma Técnico">
                @else
                    <span class="text-xs text-gray-500 font-bold">Técnico no firmó</span>
                @endif
                <hr>
                <strong>PERSONA RESPONSABLE PROVEEDOR</strong><br>
                @if (!empty($visitas[0]))
                    <div class="datos-firma" style="color: #555;">
                        <p style="margin: 2px 0;">
                            {{ strtoupper($visitas[0]['tecnico'] ?? 'N/A') }}<br>
                            {{ strtoupper($visitas[0]['tipo_documento'] ?? 'Documento') }}:
                            {{ strtoupper($visitas[0]['documento'] ?? 'N/A') }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>

</html>
