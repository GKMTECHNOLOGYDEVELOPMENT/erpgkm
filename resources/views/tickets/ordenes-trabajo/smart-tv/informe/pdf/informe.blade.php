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
            text-align: left !important;
            padding: 6px 10px !important;
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
            /* Evita cortes entre páginas */
            break-inside: avoid !important;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .img-container img {
            max-width: 100% !important;
            /* La imagen nunca será más ancha que su contenedor */
            width: auto !important;
            /* Permite que la imagen mantenga su proporción */

            max-height: 300px !important;
            /* 🔹 Fija una altura máxima */
            min-height: 100px !important;
            /* 🔹 Fija una altura mínima */

            object-fit: contain !important;
            /* Evita que la imagen se deforme */
            display: block;
            margin: 0 auto;
            page-break-inside: avoid !important;
            /* Evita que la imagen se corte entre páginas */
            /* Evita cortes en la imagen */
            break-inside: avoid !important;

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

            <div class="relative flex items-center pb-2 mb-4">
                <!-- Logo a la izquierda -->
                <div class="flex-shrink-0">
                    <img src="{{ public_path('assets/images/auth/logogkm2.png') }}" class="w-32">
                </div>

                <!-- Datos de la empresa centrados y más abajo -->
                <div class="absolute left-1/2 transform -translate-x-1/2 text-center mt-4">
                    <h1 class="text-lg font-bold">INFORME TÉCNICO</h1>
                    <p class="text-md">RUC 20543618587</p>
                    <p class="text-md">AV. SANTA ELVIRA E URB. SAN ELÍAS, N°MZ B LOTE 8. LOS OLIVOS - LIMA</p>
                    <p class="text-md">CONSULTAS@GKMTECHNOLOGY.COM.PE</p>
                    <p class="text-md">080080142</p>
                </div>

                <!-- Título y datos del ticket alineados -->
                <div class="ml-auto text-right">

                    <div class="text-xs leading-tight mt-1"> <!-- Contenedor con espaciado uniforme -->
                        <p>NRO TICKET: <span class="font-bold">{{ $orden->numero_ticket ?? 'N/A' }}</span></p>
                        <p>FECHA INICIO: <span class="font-bold">{{ $fechaCreacion }}</span></p>
                        <p>FECHA CIERRE: <span class="font-bold">{{ $fechaCierre ?? 'N/A' }}</span></p>
                    </div>
                </div>


            </div>

            <div class="flex justify-between mt-3">
                <!-- Información del Cliente -->
                <div class="w-1/2">
                    <ul class="text-xs space-y-1">
                        <li><span class="font-bold">CLIENTE GENERAL:</span>
                            {{ $orden->clienteGeneral->descripcion ?? 'No disponible' }}</li>
                        <li><span class="font-bold">CLIENTE:</span> {{ $orden->cliente->nombre ?? 'No asignado' }}</li>
                        <li><span class="font-bold">DNI/RUC:</span> {{ $orden->cliente->documento ?? 'No disponible' }}
                        </li>
                        <li><span class="font-bold">TIENDA DE COMPRA:</span>
                            {{ $orden->tienda->nombre ?? 'No registrada' }}</li>
                        <li><span class="font-bold">DIRECCIÓN:</span> {{ $orden->direccion ?? 'No registrada' }}</li>
                    </ul>
                </div>

                <!-- Información del Técnico -->
                <div class="w-1/2 text-right">
                    <h2 class="text-xs font-bold mb-1 text-gray-700">TÉCNICO / RESPONSABLE</h2>
                    @foreach ($visitas as $visita)
                        <p class="text-xs"><span class="font-bold">NOMBRE:</span> {{ $visita['tecnico'] }}
                        </p>
                    @endforeach
                </div>
            </div>
            @if (!empty($producto))
                <div class="red-bg mt-4 text-left">Datos del Producto</div>
                <div class="w-full text-xs mt-3">
                    <div class="flex justify-between">
                        <p><span class="font-bold">TIPO DE PRODUCTO:</span> {{ $producto['categoria'] }}</p>
                        <p><span class="font-bold">MARCA:</span> {{ $producto['marca'] }}</p>
                        <p><span class="font-bold">MODELO:</span> {{ $producto['modelo'] }}</p>
                    </div>
                    <div class="mt-2">
                        <p><span class="font-bold">SERIE:</span> {{ $producto['serie'] }}</p>
                    </div>
                </div>
            @endif

            @if (!empty($producto['fallaReportada']))
                <!-- Sección de Falla Reportada (Aparte de Datos del Producto) -->
                <div class="red-bg mt-4 text-left">Falla Reportada</div>
                <div class="w-full text-xs mt-3">
                    <p>{{ $producto['fallaReportada'] }}</p>
                </div>
            @endif





            {{-- @if ($visitas->isNotEmpty())
            <div class="red-bg mt-4">Detalles de la Visita</div>
            <div class="space-y-3 mt-3">
                @foreach ($visitas as $visita)
                    <div
                        class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-300 p-4 flex justify-between items-center">
                        <div class="w-2/3"> <!-- Ajustado para dar más espacio a la imagen --> --}}
            {{-- <h3 class="text-lg font-semibold text-gray-800">{{ $visita['nombre'] }}</h3> --}}

            <!-- Fecha Programada -->
            {{-- <div class="flex items-center text-xs text-gray-600 mt-1">
                                <svg class="w-4 h-4 text-blue-500 mr-2" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3M16 7V3M4 11h16M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p><span class="font-semibold">Fecha Programada:</span>
                                    {{ $visita['fecha_programada'] }}</p>
                            </div> --}}

            <!-- Inicio de Servicio -->
            {{-- <div class="flex items-center text-xs text-gray-600 mt-1">
                                <svg class="w-4 h-4 text-green-500 mr-2" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7M5 13l4 4L19 7" />
                                </svg>
                                <p><span class="font-semibold">Inicio de Servicio:</span>
                                    {{ $visita['fecha_llegada'] }}</p>
                            </div> --}}

            <!-- Técnico Responsable -->
            {{-- <div class="flex items-center text-xs text-gray-600 mt-1">
                                <svg class="w-4 h-4 text-purple-500 mr-2" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 7V3M10 7V3M4 11h16M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p><span class="font-semibold">Técnico:</span> {{ $visita['tecnico'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif --}}



            @if ($transicionesStatusOt->isNotEmpty())
                <div class="space-y-2 mt-2">
                    @foreach ($transicionesStatusOt as $transicion)
                        <!-- Nombre del Estado con fondo rojo -->
                        <div class="red-bg px-3 py-2 rounded-md">
                            {{ $transicion->estado_ot->descripcion ?? 'Sin Estado' }}
                        </div>

                        <!-- Justificación debajo del estado -->
                        <div class="p-3 rounded-md">
                            <p class="text-xs text-gray-700">{{ $transicion->justificacion }}</p>
                        </div>
                    @endforeach
                </div>
            @endif



            <!-- FOOTER -->
            <div class="footer text-center text-gray-500 text-xs">

                <div class="flex justify-between mt-6 page-break-inside-avoid">
                    <div class="w-1/2 text-center">
                        <div class="border-b-2 border-gray-300 inline-block mb-2">
                            @if ($firmaTecnico)
                                <img src="{{ $firmaTecnico }}" alt="Firma del Técnico"
                                    class="h-20 max-w-[150px] mx-auto object-contain">
                            @else
                                <p class="text-xs text-gray-500">N/A</p>
                            @endif
                        </div>
                        <p class="text-xs font-semibold text-gray-700">FIRMA DEL TÉCNICO</p>
                        <p class="text-xs"><span class="font-bold"></span> {{ $visita['tecnico'] }}
                    </div>

                    <div class="w-1/2 text-center">
                        <div class="border-b-2 border-gray-300 inline-block mb-2">
                            @if ($firmaCliente)
                                <img src="{{ $firmaCliente }}" alt="Firma del Cliente"
                                    class="h-20 max-w-[150px] mx-auto object-contain">
                            @else
                                <p class="text-xs text-gray-500">N/A</p>
                            @endif
                        </div>
                        <p class="text-xs font-semibold text-gray-700">FIRMA DEL CLIENTE</p>
                    </div>
                </div>

                <!-- Información adicional -->
                <p class="mt-2">
                    {{ $emitente->nome ?? 'GKM TECHNOLOGY S.A.C.' }} - AV. SANTA ELVIRA E URB. SAN ELÍAS, MZ. B LOTE 8,
                    LOS OLIVOS - LIMA, TELF: 080080142
                </p>
            </div>




            @if (!empty($imagenesFotosTickets) || (!empty($imagenesAnexos) && count($imagenesAnexos) > 0))
                <!-- Forzar nueva página antes de ANEXOS -->
                <div class="red-bg mt-4 font-bold" style="page-break-before: always;">
                    <h2>ANEXOS</h2>
                </div>

                <div class="grid grid-cols-1 gap-6 mt-4">

                    <!-- Primero las imágenes de la visita -->
                    @if (!empty($imagenesAnexos) && count($imagenesAnexos) > 0)
                        @foreach ($imagenesAnexos as $anexo)
                            @if (!empty($anexo['foto_base64']))
                                <div class="bg-white border border-gray-300 rounded-lg shadow-lg p-4 flex flex-col items-center"
                                    style="page-break-inside: avoid;">
                                    <!-- Imagen más grande y centrada -->
                                    <div class="img-container">
                                        <img src="{{ $anexo['foto_base64'] }}" alt="Imagen de la visita">
                                    </div>

                                    <!-- Descripción centrada -->
                                    <p class="text-sm text-center text-gray-700 font-semibold mt-2">
                                        IMAGEN DE LA VISITA
                                    </p>
                                </div>
                            @endif
                        @endforeach
                    @endif

                    <!-- Luego las imágenes de los tickets anexos -->
                    @if (!empty($imagenesFotosTickets) && count($imagenesFotosTickets) > 0)
                        @foreach ($imagenesFotosTickets as $fotoTicket)
                            @if (!empty($fotoTicket['foto_base64']))
                                <div class="bg-white border border-gray-300 rounded-lg shadow-lg p-4 flex flex-col items-center"
                                    style="page-break-inside: avoid;">
                                    <!-- Imagen más grande y centrada -->
                                    <div class="img-container">
                                        <img src="{{ $fotoTicket['foto_base64'] }}" alt="Imagen de la visita">
                                    </div>

                                    <!-- Descripción centrada -->
                                    <p class="text-sm text-center text-gray-700 font-semibold mt-2">
                                        {{ $fotoTicket['descripcion'] ?? 'Sin descripción' }}
                                    </p>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            @endif

        </div>
</body>

</html>
