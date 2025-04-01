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

            <div class="relative flex items-center pb-2 mb-4">
                <!-- Logo a la izquierda -->
                <div class="flex-shrink-0">
                    <img src="{{ public_path('assets/images/auth/logogkm2.png') }}" class="w-32">
                </div>

                <!-- Datos de la empresa centrados y más abajo -->
                <div class="absolute left-1/2 transform -translate-x-1/2 text-center mt-4">
                    <h1 class="text-lg font-bold">TRASLADO DE EQUIPO</h1>
                    <p class="text-md">RUC 20543618587</p>
                    <p class="text-md">AV. SANTA ELVIRA E URB. SAN ELÍAS, N°MZ B LOTE 8. LOS OLIVOS - LIMA</p>
                    <p class="text-md">CONSULTAS@GKMTECHNOLOGY.COM.PE</p>
                    <p class="text-md">080080142</p>
                </div>

                <!-- Título y datos del ticket alineados con logo de marca -->
                <div class="ml-auto text-right">
                    @if (!empty($marca->foto))
                        <div class="mt-4">
                            <img src="data:image/jpeg;base64,{{ base64_encode($marca->foto) }}" alt="Logo Marca"
                                class="w-24 h-12 object-contain mb-1 mx-auto">
                        </div>
                    @endif


                    <div class="text-xs leading-tight mt-3">
                        <p>NRO TICKET: <span class="font-bold">{{ $orden->numero_ticket ?? 'N/A' }}</span></p>
                        <p>FECHA DE RETIRO: <span class="font-bold">{{ $fechaCreacion }}</span></p>
                    </div>
                </div>



            </div>

            <div class="flex justify-between mt-3">
                <!-- Información del Cliente -->
                <div class="w-1/2">
                    <ul class="text-xs space-y-1">
                        <li><span class="font-bold">CLIENTE:</span> {{ $orden->cliente->nombre ?? 'No asignado' }}</li>
                        <li><span class="font-bold">DNI/RUC:</span> {{ $orden->cliente->documento ?? 'No disponible' }}
                        </li>
                        <li><span class="font-bold">DIRECCIÓN:</span> {{ $orden->direccion ?? 'No registrada' }}
                        </li>
                    </ul>
                </div>

                <!-- Información del Técnico alineado -->
                <div class="w-1/2 text-right text-xs">
                    <h2 class="font-bold mb-1 text-gray-700">TRANSPORTISTA / RESPONSABLE</h2>
                    @foreach ($visitas as $visita)
                        <div class="grid grid-cols-2 gap-y-1 justify-end text-right">
                            <p><span class="font-bold">NOMBRE:</span> {{ $visita['tecnico'] }}</p>
                            <p><span class="font-bold">TELÉFONO:</span> {{ $visita['telefono'] }}</p>
                            <p><span class="font-bold">DNI:</span> {{ $visita['documento'] }}</p>
                            <p><span class="font-bold">PLACA:</span> {{ $visita['vehiculo_placa'] }}</p>
                        </div>
                    @endforeach
                </div>



            </div>


            @if (!empty($producto))
                <div class="red-bg mt-4 text-left">Datos de Retiro del Equipo</div>
                <div class="w-full text-xs mt-2">
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
                    <div class="w-1/2 text-center">
                        <div class="inline-block mb-2 h-20 flex justify-center items-center">
                            @if ($firmaTecnico)
                                <img src="{{ $firmaTecnico }}" alt="Firma del Técnico"
                                    class="h-20 max-w-[150px] mx-auto object-contain">
                            @else
                                <div class="h-full flex items-center justify-center w-[150px]">
                                    <p class="text-xs text-gray-500">N/A</p>
                                </div>
                            @endif
                        </div>
                        <hr class="w-48 border-t-2 border-gray-700 mx-auto mb-1">
                        <p class="text-xs font-semibold text-gray-700">FIRMA DEL TRANSPORTISTA</p>
                        <p class="text-xs">{{ $visita['tecnico'] }}</p>
                    </div>

                    <!-- Firma del Cliente -->
                    <div class="w-1/2 text-center">
                        <div class="inline-block mb-2 h-20 flex justify-center items-center">
                            @if ($firmaCliente)
                                <img src="{{ $firmaCliente }}" alt="Firma del Cliente"
                                    class="h-20 max-w-[150px] mx-auto object-contain">
                            @else
                                <div class="h-full flex items-center justify-center w-[150px]">
                                    <p class="text-xs text-gray-500 font-bold">Cliente no firmó</p>
                                </div>
                            @endif
                        </div>
                        <hr class="w-48 border-t-2 border-gray-700 mx-auto mb-1">
                        <p class="text-xs font-semibold text-gray-700">FIRMA DEL CLIENTE</p>
                    </div>
                </div>
            </div>




            @if (!empty($imagenesFotosTickets) || (!empty($imagenesAnexos) && count($imagenesAnexos) > 0))
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
                                    <!-- Primera hoja SIN SALTO DE PÁGINA -->
                                    <div class="flex flex-col items-center">
                                    @else
                                        <!-- A partir de la segunda hoja, forzamos un salto de página -->
                                        <div class="flex flex-col items-center" style="page-break-before: always;">
                                @endif

                                <!-- Imagen centrada -->
                                <div class="img-container">
                                    <img src="{{ $anexo['foto_base64'] }}" alt="Imagen de la visita">
                                </div>

                                <!-- Descripción centrada -->
                                <p class="text-sm text-center text-gray-700 font-semibold mt-2">
                                    IMAGEN DE LA VISITA
                                </p>

                                @php $contador++; @endphp

                                @if ($contador % 2 == 0)
                </div>
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
