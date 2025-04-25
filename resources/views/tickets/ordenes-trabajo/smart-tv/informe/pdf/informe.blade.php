<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe Técnico</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: "Times New Roman", Times, serif !important;
            font-size: 10px;
        }

        .red-bg {
            background-color: #A9240E !important;
            color: white !important;
            padding: 6px 6px !important;
            /* Agrega un poco de espacio a la derecha e izquierda */
            font-size: 12px !important;
            font-weight: bold !important;
            text-transform: uppercase !important;
            width: 100% !important;
            margin-top: 1rem !important;
            border-radius: 4px !important;
        }

        .indent-paragraph {
            text-align: left !important;
            /* alineado a la izquierda */
            text-indent: 2em !important;
            /* sangría en primera línea */
            line-height: 2 !important;
            /* interlineado 2.0 */
            margin-bottom: 0 !important;
            /* sin espacio entre párrafos */
        }



        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 6px;
            font-size: 9px;
        }

        .table th {
            background-color: #f8f9fa;
            text-align: left;
            font-weight: bold;
        }

        .img-container {
            width: 100%;
            text-align: center;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .img-container img {
            width: 98% !important;
            /* 🔹 Fuerza todas las imágenes a tener el mismo ancho */
            max-width: 100% !important;
            height: auto !important;
            max-height: 400px !important;
            /* 🔹 Asegura que las imágenes no sean demasiado altas */
            object-fit: cover !important;
            /* 🔹 Mantiene el recorte sin deformar */
            display: block;
            margin: 20px auto 0 auto !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }


        /* 🔹 Detecta imágenes que están justo después de un salto de página */
        @media print {
            .img-container {
                margin-top: 40px !important;
                /* 🔹 Mayor margen en impresión para evitar que toquen el borde */
            }
        }


        .footer {
            width: 100%;
            padding: 6px 10px !important;
            /* Reducir espacio */
            background-color: white;
            page-break-before: avoid;
            page-break-after: avoid;
        }
    </style>
</head>

<body class="text-gray-900">
    <div class="first-page-container">
        <div class="container mx-auto bg-white p-2">
            <!-- ENCABEZADO -->

            <!-- ENCABEZADO: LOGOS Y DATOS CENTRADOS -->
            <div class="relative flex items-center justify-between pb-2">
                <!-- Logo GKM -->
                <div class="w-32 h-20 flex items-center justify-center">
                    <img src="{{ $logoGKM }}" alt="Logo GKM" class="w-full h-full object-contain">
                </div>

                <!-- Datos empresa -->
                <div class="absolute left-1/2 transform -translate-x-1/2 text-center mt-4">
                    <h1 class="text-lg font-bold">INFORME TÉCNICO</h1>
                    <p class="text-md">RUC 20543618587</p>
                    <p class="text-md">AV. SANTA ELVIRA E URB. SAN ELÍAS, N°MZ B LOTE 8. LOS OLIVOS - LIMA</p>
                    <p class="text-md">CONSULTAS@GKMTECHNOLOGY.COM.PE</p>
                    <p class="text-md">080080142</p>

                </div>

                <!-- Logo Marca -->
                <div class="w-32 h-20 flex items-center justify-center">
                    <img src="{{ $marca->logo_base64 }}" alt="Logo Marca" class="w-full h-full object-contain pt-1">
                </div>
            </div>

            <!-- INFO CLIENTE Y TÉCNICO + TICKET -->
            <div class="flex justify-between mt-6">
                <!-- Información del Cliente -->
                <div class="w-1/2">
                    <ul class="text-xs space-y-1">
                        <li><span class="font-bold">CLIENTE:</span> {{ $orden->cliente->nombre ?? 'No asignado' }}</li>
                        <li><span class="font-bold">DNI/RUC:</span> {{ $orden->cliente->documento ?? 'No disponible' }}
                        </li>
                        <li><span class="font-bold">DIRECCIÓN:</span> {{ $orden->direccion ?? 'No registrada' }}</li>
                    </ul>
                </div>

                <!-- Información del Técnico + Ticket -->
                <div class="w-1/2 text-right">
                    <div class="text-xs leading-tight">
                        <p>NRO TICKET: <span class="font-bold">{{ $orden->numero_ticket ?? 'N/A' }}</span></p>
                        <p>FECHA DE ATENCIÓN: <span class="font-bold">{{ $fechaCreacion }}</span></p>
                    </div>
                    <h2 class="text-xs font-bold mb-1 text-gray-700 mt-2">TÉCNICO / RESPONSABLE</h2>
                    @foreach ($visitas as $visita)
                        <p class="text-xs"><span class="font-bold">NOMBRE:</span> {{ strtoupper($visita['tecnico']) }}
                        </p>
                    @endforeach
                </div>
            </div>

            <!-- 🔹 Dirección en toda la fila -->
            {{-- <div class="w-full mt-4">
                <p class="text-xs"><span class="font-bold">DIRECCIÓN:</span> {{ $orden->direccion ?? 'No registrada' }}
                </p>
            </div> --}}
            <hr class="my-4 border-0">

            @if (!empty($producto))
                <div class="red-bg mt-4 text-left">Datos del Producto</div>
                <div class="w-full text-xs mt-3">
                    <div class="flex justify-between flex-wrap gap-4">
                        <p><span class="font-bold">TIPO DE PRODUCTO:</span> {{ strtoupper($producto['categoria']) }}</p>
                        <p><span class="font-bold">MARCA:</span> {{ strtoupper($producto['marca']) }}</p>
                        <p><span class="font-bold">MODELO:</span> {{ strtoupper($producto['modelo']) }}</p>
                        <p><span class="font-bold">SERIE:</span> {{ strtoupper($producto['serie']) }}</p>
                    </div>
                </div>
            @endif
            
            @if (!empty($producto['fallaReportada']))
                <div class="red-bg mt-4 text-left">Falla Reportada</div>
                <div class="w-full text-xs mt-3">
                    <p class="uppercase indent-paragraph">{{ $producto['fallaReportada'] }}</p>
                </div>
            @endif
            
            @if (trim($motivoCondicion ?? '') !== '')
                <div class="red-bg mt-4 text-left">Motivo de la Condición</div>
                <div class="w-full text-xs mt-3">
                    <p class="uppercase indent-paragraph">{{ $motivoCondicion }}</p>
                </div>
            @endif
            
            @if ($transicionesStatusOt->isNotEmpty())
                @php
                    $ordenEstados = [
                        1 => 1, // DETALLES ESTÉTICOS
                        2 => 2, // DIAGNÓSTICO
                        3 => 3, // SOLUCIÓN
                        4 => 4, // OBSERVACIÓN
                    ];
                    $transicionesStatusOt = $transicionesStatusOt->sortBy(function ($item) use ($ordenEstados) {
                        return $ordenEstados[$item->idEstadoots] ?? 999;
                    });
                @endphp
            
                <div class="space-y-2 mt-2">
                    @foreach ($transicionesStatusOt as $transicion)
                        <div class="red-bg px-3 py-2 rounded-md">
                            {{ $transicion->estado_ot->descripcion ?? 'Sin Estado' }}
                        </div>
            
                        <div class="w-full text-xs">
                            <p class="text-xs uppercase indent-paragraph">{{ $transicion->justificacion }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
            
            @php
                $hayFotosCondicion =
                    !empty($imagenesAnexos) &&
                    collect($imagenesAnexos)->filter(fn($a) => !empty($a['foto_base64']))->isNotEmpty();
            
                $hayFotosDeTickets =
                    !empty($imagenesFotosTickets) &&
                    collect($imagenesFotosTickets)->filter(fn($a) => !empty($a['foto_base64']))->isNotEmpty();
            @endphp
            
            @if (!$modoVistaPrevia && ($hayFotosCondicion || $hayFotosDeTickets))
                <div class="red-bg mt-4 font-bold" style="page-break-before: always;">
                    <h2>ANEXOS</h2>
                </div>
            
                <div class="mt-4">
                    @php $contador = 0; @endphp
            
                    {{-- 🔴 Imágenes de condiciones --}}
                    @if ($hayFotosCondicion)
                        @foreach ($imagenesAnexos as $anexo)
                            @if (!empty($anexo['foto_base64']))
                                @if ($contador % 2 == 0)
                                    <div class="flex flex-col items-center" @if ($contador > 0) style="page-break-before: always;" @endif>
                                @endif
            
                                <div class="img-container mb-6">
                                    <img src="{{ $anexo['foto_base64'] }}" alt="Imagen de condición">
                                </div>
        
            
                                @php $contador++; @endphp
            
                                @if ($contador % 2 == 0 || $loop->last)
                                    </div>
                                @endif
                            @endif
                        @endforeach
                    @endif
            
                    {{-- 🔵 Imágenes de fotos de tickets --}}
                    @if ($hayFotosDeTickets)
                        @foreach ($imagenesFotosTickets as $fotoTicket)
                            @if (!empty($fotoTicket['foto_base64']))
                                @if ($contador % 2 == 0)
                                    <div class="flex flex-col items-center" @if ($contador > 0) style="page-break-before: always;" @endif>
                                @endif
            
                                <div class="img-container mb-6">
                                    <img src="{{ $fotoTicket['foto_base64'] }}" alt="Imagen del ticket">
                                </div>
            
                                <p class="text-sm text-center text-gray-700 font-semibold mt-2">
                                    {{ $fotoTicket['descripcion'] ?? 'Sin descripción' }}
                                </p>
            
                                @php $contador++; @endphp
            
                                @if ($contador % 2 == 0 || $loop->last)
                                    </div>
                                @endif
                            @endif
                        @endforeach
                    @endif
                </div>
            @endif
            

    </div>

    {{-- 🔍 Lógica para saber si la última imagen fue sola en la hoja --}}
    @php
        $mostrarFirmasEnMismaHoja = false;

        if (!$modoVistaPrevia && $hayFotosDeTickets) {
            $imagenesTicketsFiltradas = collect($imagenesFotosTickets)
                ->filter(fn($f) => !empty($f['foto_base64']))
                ->values();

            if ($imagenesTicketsFiltradas->isNotEmpty()) {
                $mostrarFirmasEnMismaHoja = $contador % 2 !== 0;
            }
        }
    @endphp

    {{-- 🔻 Forzar salto de página solo si la firma no debe ir en la misma hoja --}}
    @if (!$mostrarFirmasEnMismaHoja)
        <div style="page-break-before: always;"></div>
    @endif

    <!-- FOOTER: FIRMAS -->
    <div class="footer text-center text-gray-500 text-xs">
        <div class="flex justify-between mt-6">
            <!-- Firma del Técnico -->
            <div class="w-1/2 text-center">
                <div class="inline-block mb-1 h-24 flex justify-center items-end">
                    @if ($firmaTecnico)
                        <img src="{{ $firmaTecnico }}" alt="Firma del Técnico"
                            class="w-[90%] h-20 mx-auto object-contain"
                            style="transform: scale(1.5); transform-origin: bottom center; bottom: -30px; position: relative;">
                    @else
                        <div class="h-full flex items-center justify-center w-full">
                            <p class="text-xs text-gray-500">N/A</p>
                        </div>
                    @endif
                </div>
                <hr class="w-48 border-t-2 border-gray-700 mx-auto mb-0">
                <p class="text-xs font-semibold text-gray-700 mt-1">FIRMA DEL TÉCNICO</p>
                <p class="text-xs uppercase">{{ $visita['tecnico'] }}</p>
                <p class="text-xs text-gray-500">
                    {{ $visita['tipo_documento'] ?? 'Documento' }}: {{ $visita['documento'] ?? 'N/A' }}
                </p>
            </div>

            <!-- Firma del Cliente -->
            <div class="w-1/2 text-center">
                <div class="inline-block mb-1 h-24 flex justify-center items-end">
                    @if ($firmaCliente)
                        <img src="{{ $firmaCliente }}" alt="Firma del Cliente"
                            class="w-[90%] h-20 mx-auto object-contain"
                            style="transform: scale(1.5); position: relative; bottom: 10px;">
                    @else
                        <div class="h-full flex items-center justify-center w-full">
                            <p class="text-xs text-gray-500 font-bold">Cliente no firmó</p>
                        </div>
                    @endif
                </div>
                <hr class="w-48 border-t-2 border-gray-700 mx-auto mb-1">
                <p class="text-xs font-semibold text-gray-700">FIRMA DEL CLIENTE</p>
                <p class="text-xs text-gray-600 uppercase tracking-wide">
                    {{ $firma->nombreencargado ?? ($orden->cliente->nombre ?? 'N/A') }}
                </p>
                <p class="text-xs text-gray-500">
                    {{ mb_strtoupper($firma->tipodocumento ?? ($orden->cliente->tipodocumento->nombre ?? 'Documento')) }}:
                    {{ mb_strtoupper($firma->documento ?? ($orden->cliente->documento ?? 'No disponible')) }}
                </p>
            </div>
        </div>
        <br>
    </div>


</body>

</html>
