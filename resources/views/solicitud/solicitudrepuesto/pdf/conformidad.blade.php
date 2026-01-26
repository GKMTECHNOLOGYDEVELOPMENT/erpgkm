<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Acta de Conformidad</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: "DejaVu Sans", Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #111;
        }

        /* === HOJA MEMBRETADA === */
        .page {
            width: 210mm;
            min-height: 297mm;
            background: url('{{ $bgBase64 }}') no-repeat center center;
            background-size: cover;
            box-sizing: border-box;

            /* AJUSTA ESTOS MÁRGENES A TU HOJA */
            padding: 42mm 18mm 22mm 18mm;
        }
    </style>
</head>

<body>
    <div class="page">

        <!-- TÍTULO PRINCIPAL -->
        <h1
            class="text-center text-[16px] font-bold uppercase tracking-[0.5px] mb-5 pb-2 font-['Times_New_Roman',Times,serif]">
            ACTA DE CONFORMIDAD
        </h1>

        <!-- TEXTO DEL ACTA -->
        <div class="mb-4 page-break-inside-avoid">

            <!-- QUIEN ENTREGA / TEXTO INICIAL -->
            @if ($esDevuelto)
                {{-- 🔁 DEVUELTO: SIEMPRE ENTRA AQUÍ --}}

                <p class="mb-3 text-justify text-[12px] leading-[1.5]">
                    Por medio de la presente, yo
                    <span class="font-bold uppercase">
                        {{ $solicitud->solicitante_nombre }}
                        {{ $solicitud->solicitante_apellido_paterno }}
                        {{ $solicitud->solicitante_apellido_materno }}
                    </span>,
                    identificado(a) con
                    {{ $solicitud->solicitante_tipo_documento }} N.°
                    <span class="font-bold uppercase">
                        {{ $solicitud->solicitante_documento }}
                    </span>,
                    en mi calidad de
                    <span class="font-bold uppercase">
                        {{ $solicitud->solicitante_rol }}
                    </span>,
                    dejo constancia de haber realizado la devolución de los repuestos detallados
                    en el presente documento.
                </p>
                <p class="mb-3 text-justify text-[12px] leading-[1.5]">
                    La devolución de los repuestos se realiza en atención al
                    <strong>Ticket N.° {{ $solicitud->numero_ticket ?? 'N/A' }}</strong>,
                    debido a que los mismos no fueron necesarios para la atención técnica correspondiente.
                </p>


                <p class="mb-3 text-justify text-[12px] leading-[1.5]">
                    El receptor de los repuestos,
                    <strong class="uppercase">
                        {{ $solicitud->aprobador_nombre }}
                        {{ $solicitud->aprobador_apellido_paterno }}
                        {{ $solicitud->aprobador_apellido_materno }}
                    </strong>,
                    identificado(a) con
                    {{ $solicitud->aprobador_tipo_documento }} N.°
                    <strong class="uppercase">
                        {{ $solicitud->aprobador_documento }}
                    </strong>,
                    en su calidad de
                    <strong class="uppercase">
                        {{ $solicitud->aprobador_rol }}
                    </strong>,
                    perteneciente al área de
                    <strong class="uppercase">
                        {{ $solicitud->aprobador_area }}
                    </strong>,
                    declara haber recibido los repuestos conforme.
                </p>
            @elseif ($esAutoCesion)
                <!-- AUTO CESIÓN -->
                <p class="mb-6 text-justify text-[12px] leading-[1.5]">
                    Por medio de la presente, se deja constancia que
                    <span class="font-bold uppercase">
                        {{ $solicitud->aprobador_nombre }}
                        {{ $solicitud->aprobador_apellido_paterno }}
                        {{ $solicitud->aprobador_apellido_materno }}
                    </span>,
                    identificado(a) con
                    {{ $solicitud->aprobador_tipo_documento }} N.°
                    <span class="font-bold uppercase">
                        {{ $solicitud->aprobador_documento }}
                    </span>,
                    en su calidad de
                    <span class="font-bold uppercase">
                        {{ $solicitud->aprobador_rol }}
                    </span>,
                    perteneciente al área de
                    <span class="font-bold uppercase">
                        {{ $solicitud->aprobador_area }}
                    </span>,
                    autoriza la reasignación del repuesto solicitado en la orden
                    <strong>{{ $solicitud->codigo }}</strong>,
                    asociada al <strong>Ticket N.° {{ $solicitud->numero_ticket }}</strong>,
                    para ser utilizados en {{ $tipoUsoAutoCesion }}.
                </p>

                <!-- QUIEN RECIBE -->
                <p class="mb-3 text-justify text-[12px] leading-[1.5]">
                    El receptor de los repuestos,
                    <strong class="uppercase">
                        {{ $solicitud->solicitante_nombre }}
                        {{ $solicitud->solicitante_apellido_paterno }}
                        {{ $solicitud->solicitante_apellido_materno }}
                    </strong>,
                    identificado(a) con
                    {{ $solicitud->solicitante_tipo_documento }} N.°
                    <strong class="uppercase">
                        {{ $solicitud->solicitante_documento }}
                    </strong>,
                    en su calidad de
                    <strong class="uppercase">
                        {{ $solicitud->solicitante_rol }}
                    </strong>,
                    perteneciente al área de
                    <strong class="uppercase">
                        {{ $solicitud->solicitante_area }}
                    </strong>,
                    declara haber recibido los repuestos conforme.
                </p>
            @elseif ($esCesion)
                <!-- CESIÓN NORMAL -->
                <p class="mb-3 text-justify text-[12px] leading-[1.5]">
                    Por medio de la presente, yo
                    <span class="font-bold uppercase">
                        {{ $solicitud->aprobador_nombre }}
                        {{ $solicitud->aprobador_apellido_paterno }}
                        {{ $solicitud->aprobador_apellido_materno }}
                    </span>,
                    identificado(a) con
                    {{ $solicitud->aprobador_tipo_documento }} N.°
                    <span class="font-bold uppercase">
                        {{ $solicitud->aprobador_documento }}
                    </span>,
                    en mi calidad de
                    <span class="font-bold uppercase">
                        {{ $solicitud->aprobador_rol }}
                    </span>,
                    dejo constancia de haber realizado la entrega de los repuestos detallados
                    en el presente documento, por parte del área de
                    <span class="font-bold uppercase">
                        {{ $solicitud->aprobador_area }}
                    </span>.
                </p>

                <p class="mb-3 text-justify text-[12px] leading-[1.5]">
                    La entrega de los repuestos se realiza en atención al
                    <strong>Ticket N.° {{ $solicitud->numero_ticket ?? 'N/A' }}</strong>, el cual sustena la necesidad
                    operativa y técnica de los bienes entregados para la ejecución de las labores asignadas.
                </p>

                <p class="mb-3 text-justify text-[12px] leading-[1.5]">
                    El receptor de los repuestos,
                    <strong class="uppercase">
                        {{ $solicitud->solicitante_nombre ?? 'NOMBRES' }}
                        {{ $solicitud->solicitante_apellido_paterno ?? 'APELLIDO PATERNO' }}
                        {{ $solicitud->solicitante_apellido_materno ?? 'APELLIDO MATERNO' }}
                    </strong>,
                    identificado(a) con {{ $solicitud->solicitante_tipo_documento ?? 'DOCUMENTO' }} N.°
                    <strong class="uppercase">{{ $solicitud->solicitante_documento ?? 'NÚMERO' }}</strong>,
                    en su calidad de
                    <strong class="uppercase">{{ $solicitud->solicitante_rol ?? 'TÉCNICO ASIGNADO' }}</strong>,
                    perteneciente al área de
                    <strong class="uppercase">{{ $solicitud->solicitante_area ?? 'N/A' }}</strong>,
                    declara haber recibido los repuestos conforme.
                </p>
            @else
                <!-- FLUJO NORMAL -->
                <p class="mb-3 text-justify text-[12px] leading-[1.5]">
                    Por medio de la presente, yo
                    <span class="font-bold uppercase">
                        {{ $solicitud->aprobador_nombre ?? 'NOMBRES' }}
                        {{ $solicitud->aprobador_apellido_paterno ?? 'APELLIDO PATERNO' }}
                        {{ $solicitud->aprobador_apellido_materno ?? 'APELLIDO MATERNO' }}
                    </span>,
                    identificado(a) con
                    {{ $solicitud->aprobador_tipo_documento ?? 'DOCUMENTO' }} N.°
                    <span class="font-bold uppercase">
                        {{ $solicitud->aprobador_documento ?? 'NÚMERO' }}
                    </span>,
                    en mi calidad de
                    <span class="font-bold uppercase">
                        {{ $solicitud->aprobador_rol ?? 'RESPONSABLE' }}
                    </span>,
                    dejo constancia de haber realizado la entrega de los repuestos detallados
                    en el presente documento, por parte del área de
                    <span class="font-bold uppercase">
                        {{ $solicitud->aprobador_area ?? 'N/A' }}
                    </span>.
                </p>

                <p class="mb-3 text-justify text-[12px] leading-[1.5]">
                    La entrega de los repuestos se realiza en atención al
                    <strong>Ticket N.° {{ $solicitud->numero_ticket ?? 'N/A' }}</strong>, el cual sustena la necesidad
                    operativa y técnica de los bienes entregados para la ejecución de las labores asignadas.
                </p>

                <p class="mb-3 text-justify text-[12px] leading-[1.5]">
                    El receptor de los repuestos,
                    <strong class="uppercase">
                        {{ $solicitud->solicitante_nombre ?? 'NOMBRES' }}
                        {{ $solicitud->solicitante_apellido_paterno ?? 'APELLIDO PATERNO' }}
                        {{ $solicitud->solicitante_apellido_materno ?? 'APELLIDO MATERNO' }}
                    </strong>,
                    identificado(a) con {{ $solicitud->solicitante_tipo_documento ?? 'DOCUMENTO' }} N.°
                    <strong class="uppercase">{{ $solicitud->solicitante_documento ?? 'NÚMERO' }}</strong>,
                    en su calidad de
                    <strong class="uppercase">{{ $solicitud->solicitante_rol ?? 'TÉCNICO ASIGNADO' }}</strong>,
                    perteneciente al área de
                    <strong class="uppercase">{{ $solicitud->solicitante_area ?? 'N/A' }}</strong>,
                    declara haber recibido los repuestos conforme.
                </p>
            @endif
            <!-- TABLA DE REPUESTOS -->
            <div class="mb-6 page-break-inside-avoid">

                <table class="w-full border-collapse text-[11px] mt-2 mb-4">
                    <thead>
                        <tr>
                            <th
                                class="text-black text-center font-bold uppercase text-[11px] px-2 py-2  border-gray-800">
                                N°
                            </th>
                            <th
                                class="text-black text-center font-bold uppercase text-[11px] px-2 py-2 border-l border-gray-800">
                                Código
                            </th>
                            <th
                                class="text-black text-center font-bold uppercase text-[11px] px-2 py-2 border-l border-gray-800">
                                Modelo
                            </th>
                            <th
                                class="text-black text-center font-bold uppercase text-[11px] px-2 py-2 border-l border-gray-800">
                                Tipo Repuesto
                            </th>
                            <th
                                class="text-black text-center font-bold uppercase text-[11px] px-2 py-2 border-l border-gray-800">
                                Cantidad
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @php $contador = 1; @endphp
                        @forelse($repuestos as $r)
                            <tr>
                                <td class="text-center px-2 py-2 bg-white/80  border-gray-800">
                                    {{ $contador++ }}
                                </td>
                                <td class="text-center px-2 py-2 bg-white/80  border-l border-gray-800">
                                    {{ $r->codigo_repuesto ?? 'N/A' }}
                                </td>
                                <td class="text-center px-2 py-2 bg-white/80  border-l border-gray-800">
                                    {{ $r->modelo ?? 'N/A' }}
                                </td>
                                <td class="text-center px-2 py-2 bg-white/80  border-l border-gray-800">
                                    {{ $r->tipo_repuesto ?? 'N/A' }}
                                </td>
                                <td class="text-center px-2 py-2 bg-white/80 font-bold  border-l border-gray-800">
                                    {{ $r->cantidad ?? 0 }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center px-4 py-5 text-[11px] bg-white/80">
                                    No hay repuestos registrados en esta solicitud
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($repuestos->count() < ($solicitud->totalcantidadproductos ?? 0))
                    <p class="text-center text-[11px] font-bold text-red-600 uppercase mt-2">
                        ATENCIÓN: EXISTEN REPUESTOS PENDIENTES DE ENTREGA
                    </p>
                @endif


            </div>

            <!-- RESPONSABILIDADES DEL TÉCNICO -->
            <p class="mb-3 text-justify text-[12px] leading-[1.5]">
                Asimismo, el receptor asume plena responsabilidad por la custodia, uso adecuado y conservación de los
                repuestos entregados, comprometiéndose a destinarlos únicamente para los fines establecidos en el
                ticket que origina la presente entrega.
            </p>

            <p class="mt-2 mb-2 font-bold text-[12px]">
                El receptor se compromete expresamente a:
            </p>

            <ul class="ml-5 mb-4 list-disc space-y-2 text-justify text-[12px] leading-[1.4]">
                <li>Utilizar los repuestos únicamente para las labores técnicas autorizadas.</li>
                <li>Evitar su pérdida, deterioro, daño o uso indebido.</li>
                <li>No transferir, prestar ni ceder los repuestos a terceros sin autorización expresa del área
                    responsable.</li>
                <li>Informar de manera inmediata cualquier incidente, falla o daño que se presente durante su uso.</li>
                <li>Devolver los repuestos cuando sean requeridos por la empresa o una vez concluida la labor para la
                    cual fueron asignados.</li>
            </ul>

            <p class="mb-3 text-justify text-[12px] leading-[1.5]">
                La pérdida, daño o uso indebido de los repuestos entregados será de entera responsabilidad del receptor,
                conforme a las disposiciones internas de la empresa.
            </p>

            <p class="text-justify text-[12px] leading-[1.5]">
                En señal de conformidad, ambas partes firman la presente acta.
            </p>

        </div>




        <!-- FIRMAS -->
        <div class="grid {{ $esCesion ? 'grid-cols-3' : 'grid-cols-2' }} gap-10 mt-12 page-break-inside-avoid">

            <!-- QUIEN RECIBE -->
            <div class="relative text-center h-[120px]">
                @if (!empty($firmaSolicitante))
                    <div class="absolute inset-x-0 top-0 h-[90px] flex items-center justify-center">
                        <img src="{{ $firmaSolicitante }}"
                            class="max-h-[90px] max-w-[360px] opacity-90 object-contain">
                    </div>
                @endif

                <div class="absolute top-[60px] left-1/2 -translate-x-1/2 w-[220px] border-t border-gray-800"></div>

                <div class="absolute top-[68px] left-0 right-0 text-center text-xs font-bold uppercase">
                    {{ $solicitud->solicitante_nombre }}
                    {{ $solicitud->solicitante_apellido_paterno }}
                    {{ $solicitud->solicitante_apellido_materno }}<br>
                    <span class="text-[10px]">{{ $solicitud->solicitante_rol }}</span>
                </div>
            </div>

            <!-- QUIEN ENTREGA -->
            <div class="relative text-center h-[120px]">
                @if (!empty($firmaAprobador))
                    <div class="absolute inset-x-0 top-0 h-[90px] flex items-center justify-center">
                        <img src="{{ $firmaAprobador }}" class="max-h-[90px] max-w-[360px] opacity-90 object-contain">
                    </div>
                @endif

                <div class="absolute top-[60px] left-1/2 -translate-x-1/2 w-[220px] border-t border-gray-800"></div>

                <div class="absolute top-[68px] left-0 right-0 text-center text-xs font-bold uppercase">
                    {{ $solicitud->aprobador_nombre }}
                    {{ $solicitud->aprobador_apellido_paterno }}
                    {{ $solicitud->aprobador_apellido_materno }}<br>
                    <span class="text-[10px]">{{ $solicitud->aprobador_rol }}</span>
                </div>
            </div>

            @if ($esCesion && !empty($firmaPreparo))
                <!-- QUIEN PREPARA -->
                <div class="relative text-center h-[120px]">
                    <div class="absolute inset-x-0 top-0 h-[90px] flex items-center justify-center">
                        <img src="{{ $firmaPreparo }}" class="max-h-[90px] max-w-[360px] opacity-90 object-contain">
                    </div>

                    <!-- Línea -->
                    <div class="absolute top-[60px] left-1/2 -translate-x-1/2 w-[220px] border-t border-gray-800"></div>

                    <!-- Texto -->
                    <div
                        class="absolute top-[68px] left-0 right-0 text-center text-xs font-bold uppercase leading-tight">
                        {{ $usuarioPreparo->Nombre }}
                        {{ $usuarioPreparo->apellidoPaterno }}
                        {{ $usuarioPreparo->apellidoMaterno }}<br>
                        <span class="text-[10px]">
                            {{ $usuarioPreparo->rol ?? 'ROL' }}
                        </span>
                    </div>
                </div>
            @endif
        </div>

    </div>
</body>

</html>
