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
            background-color: #E1050F !important;
            color: white !important;
            text-align: center !important;
            padding: 6px 0 !important;
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
            /* Evita que se corten entre páginas */
            break-inside: avoid !important;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .img-container img {
            width: auto !important;
            /* Mantiene la proporción */
            max-width: 100% !important;
            /* Permite expandirse según el contenedor */
            max-height: 200px !important;
            /* Ajusta la altura máxima */
            object-fit: contain !important;
            /* Evita deformaciones */
            display: block;
            margin: 0 auto;
            page-break-inside: avoid !important;
            /* Evita cortes en PDF */
            break-inside: avoid !important;
        }
    </style>
</head>

<body class="text-gray-900">
    <div class="container mx-auto bg-white p-2">
        <!-- ENCABEZADO -->
        <div class="flex justify-between items-center pb-2 mb-4">
            <div class="flex items-center">
                <img src="{{ public_path('assets/images/auth/logogkm2.png') }}" class="w-32">
                <div class="ml-3">
                    <h1 class="text-xs font-bold">GKM TECHNOLOGY S.A.C.</h1>
                    <p class="text-md">RUC 20543618587</p>
                    <p class="text-md">AV. SANTA ELVIRA E URB. SAN ELÍAS</p>
                    <p class="text-md">CONSULTAS@GKMTECHNOLOGY.COM.PE</p>
                    <p class="text-md">080080142</p>
                </div>
            </div>
            <div class="text-right">
                <h2 class="text-md font-bold">INFORME TÉCNICO - SOPORTE ON SITE</h2>
                <p class="text-xs">NRO TICKET: <span class="font-bold">{{ $orden->numero_ticket ?? 'N/A' }}</span></p>
            </div>
        </div>

        <div class="flex justify-between mt-3">
            <!-- Información del Cliente -->
            <div class="w-1/2">
                <ul class="text-xs space-y-1">
                    <li><span class="font-bold">CLIENTE:</span> {{ $orden->cliente->nombre ?? 'No asignado' }}</li>
                    <li><span class="font-bold">CLIENTE GENERAL:</span>
                        {{ $orden->clienteGeneral->descripcion ?? 'No disponible' }}</li>
                    <li><span class="font-bold">TIENDA:</span> {{ $orden->tienda->nombre ?? 'No registrada' }}</li>
                    <li><span class="font-bold">DNI/RUC:</span> {{ $orden->cliente->documento ?? 'No disponible' }}
                    </li>
                    <li><span class="font-bold">DIRECCIÓN:</span> {{ $orden->direccion ?? 'No registrada' }}</li>
                </ul>
            </div>

            <!-- Información del Técnico -->
            <div class="w-1/2 text-right">
                <h2 class="text-xs font-bold mb-1 text-gray-700">TÉCNICO / RESPONSABLE</h2>
                @foreach ($visitas as $visita)
                    <p class="text-xs"><span class="font-bold">NOMBRE:</span> {{ $visita['tecnico'] }}

                    </p>
                    <p class="text-xs"><span class="font-bold">EMAIL:</span> {{ $visita['correo'] }}
                    </p>
                    <p class="text-xs"><span class="font-bold">TELÉFONO:</span>
                        {{ $visita['telefono'] ?? 'No registrado' }}</p>
                @endforeach
            </div>
        </div>
        @if (!empty($producto))
            <div class="red-bg mt-4">Datos del Producto</div>
            <table class="w-full border-collapse mt-3 text-xs shadow-lg rounded-lg overflow-hidden">
                <thead class="bg-gray-200 text-gray-700 uppercase text-center">
                    <tr>
                        <th class="border border-gray-300 px-4 py-2">Categoría</th>
                        <th class="border border-gray-300 px-4 py-2">Marca</th>
                        <th class="border border-gray-300 px-4 py-2">Modelo</th>
                        <th class="border border-gray-300 px-4 py-2">Serie</th>
                    </tr>
                </thead>
                <tbody class="bg-white text-gray-900 text-center">
                    <tr class="hover:bg-gray-100">
                        <td class="border border-gray-300 px-4 py-2 font-semibold">{{ $producto['categoria'] }}</td>
                        <td class="border border-gray-300 px-4 py-2 font-semibold">{{ $producto['marca'] }}</td>
                        <td class="border border-gray-300 px-4 py-2 font-semibold">{{ $producto['modelo'] }}</td>
                        <td class="border border-gray-300 px-4 py-2 font-semibold">{{ $producto['serie'] }}</td>
                    </tr>
                </tbody>
            </table>
        @endif




        @if ($visitas->isNotEmpty())
            <div class="red-bg mt-4">Detalles de la Visita</div>
            <div class="space-y-3 mt-3">
                @foreach ($visitas as $visita)
                    <div
                        class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-300 p-4 flex justify-between items-center">
                        <div class="w-2/3"> <!-- Ajustado para dar más espacio a la imagen -->
                            <h3 class="text-lg font-semibold text-gray-800">{{ $visita['nombre'] }}</h3>

                            <!-- Fecha Programada -->
                            <div class="flex items-center text-xs text-gray-600 mt-1">
                                <svg class="w-4 h-4 text-blue-500 mr-2" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3M16 7V3M4 11h16M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p><span class="font-semibold">Fecha Programada:</span>
                                    {{ $visita['fecha_programada'] }} ({{ $visita['hora_inicio'] }} -
                                    {{ $visita['hora_final'] }})</p>
                            </div>

                            <!-- Inicio de Servicio -->
                            <div class="flex items-center text-xs text-gray-600 mt-1">
                                <svg class="w-4 h-4 text-green-500 mr-2" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7M5 13l4 4L19 7" />
                                </svg>
                                <p><span class="font-semibold">Inicio de Servicio:</span>
                                    {{ $visita['fecha_llegada'] }}</p>
                            </div>

                            <!-- Técnico Responsable -->
                            <div class="flex items-center text-xs text-gray-600 mt-1">
                                <svg class="w-4 h-4 text-purple-500 mr-2" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 7V3M10 7V3M4 11h16M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p><span class="font-semibold">Técnico:</span> {{ $visita['tecnico'] }}</p>
                            </div>
                        </div>

                        <!-- Imágenes de la Visita -->
                        @if (!empty($imagenesAnexos) && count($imagenesAnexos) > 0)
                            <div class="w-1/3 flex justify-end">
                                @foreach ($imagenesAnexos as $anexo)
                                    @if (!empty($anexo['foto_base64']))
                                        <img src="{{ $anexo['foto_base64'] }}"
                                            class="w-full max-w-[250px] max-h-[250px] object-contain rounded-lg border border-gray-300 shadow-lg">
                                        @break
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif



        @if ($transicionesStatusOt->isNotEmpty())
            <div class="red-bg mt-4">
                <h2 class="text-sm font-bold m-0">ESTADO</h2>
            </div>
            <div class="space-y-2 mt-2">
                @foreach ($transicionesStatusOt as $transicion)
                    <div class="bg-white shadow-md rounded-lg border-t-4 border-red-600 page-break-inside-avoid">
                        <div class="p-4 flex items-start space-x-3">
                            <!-- Ícono en círculo rojo -->
                            <div
                                class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-red-600 text-white rounded-full shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>

                            <!-- Estado y Justificación -->
                            <div class="flex-grow">
                                <h3 class="text-lg font-bold text-red-600 mb-1">
                                    {{ $transicion->estado_ot->descripcion ?? 'Sin Estado' }}
                                </h3>

                                <div class="bg-gray-50 p-3 rounded-md border border-gray-200">
                                    <p class="text-xs text-gray-700">{{ $transicion->justificacion }}</p>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if (!empty($imagenesFotosTickets) && count($imagenesFotosTickets) > 0)
            <!-- Forzar nueva página antes de ANEXOS -->
            <div class="red-bg mt-4 font-bold" style="page-break-before: always;">
                <h2>ANEXOS</h2>
            </div>

            <div class="grid grid-cols-1 gap-6 mt-4">
                @foreach ($imagenesFotosTickets as $fotoTicket)
                    @if (!empty($fotoTicket['foto_base64']))
                        <div class="bg-white border border-gray-300 rounded-lg shadow-lg p-4 flex flex-col items-center"
                            style="page-break-inside: avoid;">
                            <!-- Imagen más grande y centrada -->
                            <img src="{{ $fotoTicket['foto_base64'] }}"
                                class="w-full max-w-[500px] max-h-[500px] rounded-lg border border-gray-200 object-contain shadow-md">

                            <!-- Descripción centrada -->
                            <p class="text-sm text-center text-gray-700 font-semibold mt-2">
                                {{ $fotoTicket['descripcion'] ?? 'Sin descripción' }}
                            </p>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif






        <!-- SECCIÓN DE FIRMAS -->
        <div class="red-bg mt-4">
            <h2 class="text-sm font-bold m-0">FIRMAS</h2>
        </div>

        <div class="flex justify-between mt-4 page-break-inside-avoid">
            <!-- Firma del Técnico -->
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
            </div>

            <!-- Firma del Cliente -->
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
        <div class="text-center mt-4 page-break-inside-avoid text-gray-500 text-xs">
            <p>
                {{ $emitente->nome ?? 'GKM TECHNOLOGY S.A.C.' }} - AV. SANTA ELVIRA E URB. SAN ELÍAS, MZ. B LOTE 8, LOS
                OLIVOS - LIMA, TELF: 080080142
            </p>
        </div>

    </div>
</body>

</html>
