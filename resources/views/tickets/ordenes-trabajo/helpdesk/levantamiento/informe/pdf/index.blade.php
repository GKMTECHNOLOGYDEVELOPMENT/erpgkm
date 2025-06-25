<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe Técnico</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif !important;
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
            width: 750px !important;
            height: 450px !important;
            object-fit: fill !important;
            display: block;
            margin: 6px auto 0 auto !important;
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
            margin-top: 40px;
            page-break-inside: avoid;
            bottom: 40px;
            /* Lo sube un poco */
            left: 0;
            width: 100%;
            padding: 6px 10px !important;
            /* Reducir espacio */

        }
    </style>
</head>

<body class="text-gray-900">
    <div class="first-page-container">
        <div class="container mx-auto bg-white p-2 mb-4">
            <!-- ENCABEZADO -->

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
                    <img src="{{ public_path('assets/images/auth/logogkm2.png') }}" alt="Logo GKM"
                        class="w-full h-full object-contain">
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
                        <li><span class="font-bold">TIENDA:</span> {{ $orden->tienda->nombre ?? 'No disponible' }}</li>
                        <li><span class="font-bold">DIRECCIÓN:</span> {{ $orden->tienda->direccion ?? 'No registrada' }}
                        </li>
                    </ul>
                </div>

                <!-- Técnico + Ticket -->
                <div class="w-1/2 text-right">
                    <div class="text-xs leading-tight">
                        <p>NRO OT: <span class="font-bold">{{ $orden->idTickets ?? 'N/A' }}</span></p>
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



            @if (!empty($producto['fallaReportada']))
                <!-- Sección de Falla Reportada (Aparte de Datos del Producto) -->
                <div class="red-bg mt-4 text-left">Falla Reportada</div>
                <div class="w-full text-xs mt-3">
                    <p class="uppercase indent-paragraph">{{ $producto['fallaReportada'] }}</p>
                </div>
            @endif

            @if (!empty($motivoCondicion))
                <!-- Sección de Motivo de Condición (igual a Falla Reportada) -->
                <div class="red-bg mt-4 text-left">Motivo de la Condición</div>
                <div class="w-full text-xs mt-3">
                    <p class="uppercase indent-paragraph">{{ $motivoCondicion }}</p>
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
                            {{ strtoupper($transicion->estado_ot->descripcion ?? 'Sin Estado') }}
                        </div>

                        <!-- Justificación debajo del estado -->
                        <div class="w-full text-xs">
                            <div class="w-full text-xs">
                                <p class="text-xs uppercase indent-paragraph">
                                    {!! nl2br(e($transicion->justificacion)) !!}
                                </p>
                                                             
                            </div>
                    @endforeach
                </div>

            @endif

            @if ($suministros->isNotEmpty())
                <div class="red-bg mt-4 text-left">Artículos Utilizados</div>

                <div class="space-y-2 text-xs mt-4">
                    @foreach ($suministros as $item)
                        <div class="flex justify-between border-b border-dashed border-gray-300 pb-1">
                            <div class="w-1/4 font-semibold text-gray-800">
                                {{ $item->articulo->nombre ?? 'Sin nombre' }}
                            </div>
                            <div class="w-1/4 text-gray-600 italic">
                                {{ $item->articulo->tipoArticulo->nombre ?? 'Sin tipo' }}
                            </div>
                            <div class="w-1/5 text-gray-600">
                                {{ $item->articulo->modelo->nombre ?? '' }}
                            </div>
                            <div class="w-1/5 text-gray-600">

                                {{ $item->articulo->modelo->marca->nombre ?? '' }}
                            </div>
                            <div class="w-1/12 text-right font-bold text-gray-900">
                                x{{ $item->cantidad }}
                            </div>
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
                                    class="w-[90%] h-20 mx-auto object-contain"
                                    style="transform: scale(1.5); transform-origin: bottom center; bottom: -30px; position: relative;">
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
                                    class="w-[90%] h-20 mx-auto object-contain"
                                    style="transform: scale(1.5); transform-origin: bottom center; bottom: -40px; position: relative;">
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


            @php
                $hayFotosDeVisita =
                    !empty($imagenesAnexos) &&
                    collect($imagenesAnexos)->filter(fn($a) => !empty($a['foto_base64']))->isNotEmpty();
                $hayFotosDeTickets =
                    !empty($imagenesFotosTickets) &&
                    collect($imagenesFotosTickets)->filter(fn($a) => !empty($a['foto_base64']))->isNotEmpty();
            @endphp

            @if (!$modoVistaPrevia && ($hayFotosDeVisita || $hayFotosDeTickets))

                <!-- Nueva página con el título ANEXOS -->
                <div class="red-bg mt-4 font-bold" style="page-break-before: always;">
                    <h2>ANEXOS</h2>
                </div>

                <div>
                    @php
                        $contador = 0;
                        $hayFotosDeVisita = !empty($imagenesAnexos) && count($imagenesAnexos) > 0;
                    @endphp
                    @php
                        $descripciones = ['IMAGEN LLEGADA A SERVICIO', 'IMAGEN MOTIVO'];
                        $contador = 0;
                    @endphp
                    <!-- Primero las imágenes de la visita (incluye condiciones también) -->
                    @if ($hayFotosDeVisita)
                        @foreach ($imagenesAnexos as $anexo)
                            @if (!empty($anexo['foto_base64']))
                                @if ($contador % 2 == 0)
                                    <!-- Abrir contenedor para 2 imágenes por página -->
                                    <div class="flex flex-col items-center"
                                        @if ($contador > 0) style="page-break-before: always;" @endif>
                                @endif

                                <!-- Imagen centrada -->
                                <div class="img-container mb-2">
                                    <img src="{{ $anexo['foto_base64'] }}" alt="Imagen de la visita">
                                </div>

                                <!-- Descripción fija -->
                                <p class="text-sm text-center text-gray-700 font-semibold mt-2">
                                    {{ $descripciones[$contador] ?? 'IMAGEN DE LA VISITA' }}
                                </p>

                                @php $contador++; @endphp

                                @if ($contador % 2 == 0 || $loop->last)
                </div> <!-- Cierra grupo de 2 imágenes -->
            @endif
            @endif
            @endforeach
            @endif

            <!-- Luego las imágenes de los tickets anexos, sin saltar a nueva hoja si no hay imágenes de visita -->
            @if (!empty($imagenesFotosTickets) && count($imagenesFotosTickets) > 0)
                @foreach ($imagenesFotosTickets as $fotoTicket)
                    @if (!empty($fotoTicket['foto_base64']))
                        @if ($contador % 2 == 0 || !$hayFotosDeVisita)
                            <div class="flex flex-col items-center"
                                @if ($contador % 2 == 0 && $hayFotosDeVisita) style="page-break-before: always;" @endif>
                        @endif

                        <!-- Imagen centrada -->
                        <div class="img-container">
                            <img src="{{ $fotoTicket['foto_base64'] }}" alt="Imagen de la visita">
                        </div>

                        <!-- Descripción centrada -->
                        <p class="text-sm text-center text-gray-700 font-semibold mt-2">
                            {{ $fotoTicket['descripcion'] ?? 'Sin descripción' }}
                        </p>

                        @php $contador++; @endphp

                        @if ($contador % 2 == 0 || !$hayFotosDeVisita)
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
