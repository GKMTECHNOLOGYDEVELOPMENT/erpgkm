<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe Técnico</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-size: 10px;
            margin: 0;
            padding: 0;
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
            width: 90% !important;
            /* 🔹 Fuerza todas las imágenes a tener el mismo ancho */
            max-width: 90% !important;
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
            position: absolute;
            bottom: 40px;
            /* Lo sube un poco */
            left: 0;
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
                <!-- Logo Cliente General a la izquierda -->
                <div class="w-32 h-20 flex items-center justify-center">
                    @if ($logoClienteGeneral)
                        <img src="{{ $logoClienteGeneral }}" alt="Logo Cliente" class="w-full h-full object-contain">
                    @endif
                </div>

                <!-- Datos empresa centrado -->
                <div class="absolute left-1/2 transform -translate-x-1/2 text-center mt-4">
                    <h1 class="text-lg font-bold">INFORME TÉCNICO</h1>
                    <p class="text-md">RUC 20543618587</p>
                    <p class="text-md">CONSULTAS@GKMTECHNOLOGY.COM.PE</p>
                    <p class="text-md">AV. SANTA ELVIRA E URB. SAN ELÍAS, N°MZ B LOTE 8. LOS OLIVOS - LIMA</p>
                </div>

                <!-- Logo GKM a la derecha -->
                <div class="w-32 h-20 flex items-center justify-center">
                    <img src="{{ $logoGKM }}" alt="Logo GKM" class="w-full h-full object-contain">
                </div>
            </div>

            <!-- INFORMACIÓN GENERAL -->
            <div class="flex justify-between mt-6">
                <!-- Información del Cliente -->
                <div class="w-1/2">
                    <ul class="text-xs space-y-1">
                        <li><span class="font-bold">CLIENTE:</span> {{ $orden->cliente->nombre ?? 'No asignado' }}</li>
                        <li><span class="font-bold">DNI/RUC:</span> {{ $orden->cliente->documento ?? 'No disponible' }}
                        </li>
                        <li><span class="font-bold">TIENDA:</span> {{ $orden->tienda->nombre ?? 'No disponible' }}
                        </li>
                        <li><span class="font-bold">DIRECCIÓN:</span> {{ $orden->tienda->direccion ?? 'No registrada' }}
                        </li>
                    </ul>
                </div>

                <!-- Información del Técnico y Ticket -->
                <div class="w-1/2 text-right">
                    <div class="text-xs leading-tight">
                        <p>NRO TICKET: <span class="font-bold">{{ $orden->numero_ticket ?? 'N/A' }}</span></p>
                        <p>FECHA DE ATENCIÓN: <span class="font-bold">{{ $fechaCreacion }}</span></p>
                    </div>
                    <h2 class="text-xs font-bold mb-1 text-gray-700 mt-2">TÉCNICO / RESPONSABLE</h2>
                    @foreach ($visitas as $visita)
                        <p class="text-xs"><span class="font-bold">NOMBRE:</span> {{ strtoupper($visita['tecnico']) }}
                        </p>

                        <p class="text-xs"><span class="font-bold">TELÉFONO: </span>080080142</p>
                    @endforeach
                </div>
            </div>


            @if ($equiposInstalados->isNotEmpty())
                <div class="red-bg mt-2">Equipos Instalados</div>
                <div class="w-full space-y-1">
                    @foreach ($equiposInstalados as $equipo)
                        <div class="text-xs py-1">
                            <div class="flex flex-wrap justify-between gap-x-4">
                                <p><span class="font-bold">TIPO:</span> {{ strtoupper($equipo['tipoProducto']) }}</p>
                                <p><span class="font-bold">MARCA:</span> {{ strtoupper($equipo['marca']) }}</p>
                                <p><span class="font-bold">MODELO:</span> {{ strtoupper($equipo['modelo']) }}</p>
                                <p><span class="font-bold">SERIE:</span> {{ strtoupper($equipo['nserie']) }}</p>
                                <p class="w-full"><span class="font-bold">OBSERVACIÓN:</span>
                                    {{ strtoupper($equipo['observacion']) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if ($equiposRetirados->isNotEmpty())
                <div class="red-bg mt-2">Equipos Retirados</div>
                <div class="w-full space-y-1">
                    @foreach ($equiposRetirados as $equipo)
                        <div class="text-xs py-1">
                            <div class="flex flex-wrap justify-between gap-x-4">
                                <p><span class="font-bold">TIPO:</span> {{ strtoupper($equipo['tipoProducto']) }}</p>
                                <p><span class="font-bold">MARCA:</span> {{ strtoupper($equipo['marca']) }}</p>
                                <p><span class="font-bold">MODELO:</span> {{ strtoupper($equipo['modelo']) }}</p>
                                <p><span class="font-bold">SERIE:</span> {{ strtoupper($equipo['nserie']) }}</p>
                                <p class="w-full"><span class="font-bold">OBSERVACIÓN:</span>
                                    {{ strtoupper($equipo['observacion']) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif





            @if (!empty($producto['fallaReportada']))
                <!-- Sección de Falla Reportada (Aparte de Datos del Producto) -->
                <div class="red-bg mt-4 text-left">Falla Reportada</div>
                <div class="w-full text-xs mt-3">
                    <p>{{ $producto['fallaReportada'] }}</p>
                </div>
            @endif


            @if ($transicionesStatusOt->isNotEmpty())
                @php
                    // 🔥 Definir el orden deseado según el ID de estado en la BD
                    $ordenEstados = [
                        1 => 1, // DETALLES ESTÉTICOS
                        2 => 2, // DIAGNÓSTICO
                        3 => 3, // SOLUCIÓN
                        4 => 4, // OBSERVACIÓN
                    ];

                    // 🔥 Ordenar la colección por idEstadoots
                    $transicionesStatusOt = $transicionesStatusOt->sortBy(function ($item) use ($ordenEstados) {
                        return $ordenEstados[$item->idEstadoots] ?? 999; // Si no está en la lista, lo manda al final
                    });
                @endphp

                <div class="space-y-2 mt-2">
                    @foreach ($transicionesStatusOt as $transicion)
                        <!-- Nombre del Estado con fondo rojo -->
                        <div class="red-bg px-3 py-2 rounded-md">
                            {{ $transicion->estado_ot->descripcion ?? 'Sin Estado' }}
                        </div>

                        <!-- Justificación debajo del estado -->
                        <div class="w-full text-xs">
                            <p class="text-xs text-gray-700">{{ $transicion->justificacion }}</p>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- FOOTER -->
            <div class="footer text-center text-gray-500 text-xs">
                <div class="flex justify-between mt-6 page-break-inside-avoid">
                    <!-- Firma del Técnico -->
                    <div class="w-1/2 text-center flex flex-col items-center">
                        <div class="h-24 flex items-end justify-center mb-1">
                            @if ($firmaTecnico)
                                <img src="{{ $firmaTecnico }}" alt="Firma del Técnico"
                                    class="h-20 max-w-[150px] object-contain -mt-4">
                            @else
                                <span class="text-xs text-gray-500">N/A</span>
                            @endif
                        </div>
                        <hr class="w-48 border-t-2 border-gray-700 mx-auto mb-1">
                        <p class="text-xs font-semibold text-gray-700">FIRMA DEL TÉCNICO</p>
                        <p class="text-xs text-gray-600 uppercase tracking-wide">
                            {{ $visita['tecnico'] ?? 'NOMBRE NO DISPONIBLE' }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $visita['tipo_documento'] ?? 'Documento' }}: {{ $visita['documento'] ?? 'N/A' }}
                        </p>
                    </div>

                    <!-- Firma del Cliente -->
                    <div class="w-1/2 text-center flex flex-col items-center">
                        <div class="h-24 flex items-end justify-center mb-1">
                            @if ($firmaCliente)
                                <img src="{{ $firmaCliente }}" alt="Firma del Cliente"
                                    class="h-20 max-w-[150px] object-contain -mt-4">
                            @else
                                <span class="text-xs text-gray-500 font-bold">Cliente no firmó</span>
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
            </div>





            @if (!$modoVistaPrevia && (!empty($imagenesFotosTickets) || (!empty($imagenesAnexos) && count($imagenesAnexos) > 0)))
                <!-- Nueva página con el título ANEXOS -->
                <div class="red-bg mt-4 font-bold" style="page-break-before: always;">
                    <h2>ANEXOS</h2>
                </div>

                <div class="mt-4">
                    @php
                        $contador = 0;
                        $hayFotosDeVisita = !empty($imagenesAnexos) && count($imagenesAnexos) > 0;
                    @endphp

                    <!-- Primero las imágenes de la visita -->
                    @if ($hayFotosDeVisita)
                        @foreach ($imagenesAnexos as $anexo)
                            @if (!empty($anexo['foto_base64']))
                                @if ($contador % 2 == 0)
                                    <!-- Abrir contenedor para 2 imágenes por página -->
                                    <div class="flex flex-col items-center"
                                        @if ($contador > 0) style="page-break-before: always;" @endif>
                                @endif

                                <!-- Imagen centrada -->
                                <div class="img-container mb-6">
                                    <img src="{{ $anexo['foto_base64'] }}" alt="Imagen de la visita">
                                </div>

                                <!-- Descripción centrada -->
                                <p class="text-sm text-center text-gray-700 font-semibold mt-2">
                                    IMAGEN DE LA VISITA
                                </p>

                                @php $contador++; @endphp

                                @if ($contador % 2 == 0 || $loop->last)
                </div> <!-- Cierra grupo de 2 imágenes -->
            @endif
            @endif
            @endforeach
            @endif

            <!-- Luego las imágenes de los tickets anexos -->
            @if (!empty($imagenesFotosTickets) && count($imagenesFotosTickets) > 0)
                @foreach ($imagenesFotosTickets as $fotoTicket)
                    @if (!empty($fotoTicket['foto_base64']))
                        @if ($contador % 2 == 0)
                            <div class="flex flex-col items-center"
                                @if ($contador > 0) style="page-break-before: always;" @endif>
                        @endif

                        <div class="img-container">
                            <img src="{{ $fotoTicket['foto_base64'] }}" alt="Imagen de la visita">
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
</body>

</html>
