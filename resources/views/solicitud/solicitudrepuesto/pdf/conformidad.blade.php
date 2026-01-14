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

            <p class="mb-3 text-justify text-[12px] leading-[1.5]">
                Por medio de la presente, yo
                <span class="font-bold">
                    {{ $solicitud->solicitante_nombre ?? 'NOMBRES' }}
                    {{ $solicitud->solicitante_apellido_paterno ?? 'APELLIDO PATERNO' }}
                    {{ $solicitud->solicitante_apellido_materno ?? 'APELLIDO MATERNO' }}
                </span>,
                identificado(a) con {{ $solicitud->solicitante_tipo_documento ?? 'DOCUMENTO' }} N.°
                <span class="font-bold">
                    {{ $solicitud->solicitante_documento ?? 'NÚMERO' }}
                </span>
                en mi calidad de {{ $solicitud->solicitante_rol ?? 'Técnico asignado' }}, dejo constancia de haber
                recibido a conformidad por parte del área
                {{ $solicitud->solicitante_area ?? 'N/A' }} correspondiente los repuestos detallados en el presente
                documento.
            </p>

            <p class="mb-3 text-justify text-[12px] leading-[1.5]">
                La entrega de los repuestos se realiza en atención al
                <strong>Ticket N.° {{ $solicitud->numero_ticket ?? 'N/A' }}</strong>,
                el cual sustenta la necesidad operativa y técnica de los bienes entregados para la ejecución de las
                labores asignadas. Declaro que los repuestos han sido recibidos en buen estado de conservación, sin
                observaciones al momento de la entrega.
            </p>

            <p class="mb-3 text-justify text-[12px] leading-[1.5]">
                Asimismo, asumo plena responsabilidad por la custodia, uso adecuado y conservación de los repuestos
                entregados, comprometiéndome a destinarlos únicamente para los fines establecidos en el ticket que
                origina la presente entrega.
            </p>

            <p class="mt-2 mb-2 font-bold text-[12px]">
                Me comprometo expresamente a:
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
                La pérdida, daño o uso indebido de los repuestos entregados será de mi entera responsabilidad,
                conforme a las disposiciones internas de la empresa.
            </p>

            <p class="text-justify text-[12px] leading-[1.5]">
                En señal de conformidad, se firma la presente acta, dejando constancia de la entrega por parte de
                <strong>
                    {{ $solicitud->aprobador_nombre ?? 'NOMBRES' }}
                    {{ $solicitud->aprobador_apellido_paterno ?? 'APELLIDO PATERNO' }}
                    {{ $solicitud->aprobador_apellido_materno ?? 'APELLIDO MATERNO' }}
                </strong>,
                identificado(a) con {{ $solicitud->aprobador_tipo_documento ?? 'DOCUMENTO' }} N.°
                <strong>{{ $solicitud->aprobador_documento ?? 'NÚMERO' }}</strong>,
                quien actúa en representación del área de
                <strong>{{ $solicitud->aprobador_area ?? 'N/A' }}</strong>.
            </p>


        </div>

        <!-- TABLA DE REPUESTOS -->
        <div class="mb-4 page-break-inside-avoid">

            <table class="w-full border-collapse text-[11px] mt-2 mb-4">
                <thead>
                    <tr>
                        <th
                            class="border border-gray-800 bg-[#E1322D] text-white text-center font-bold uppercase text-[11px] px-2 py-2 w-[5%]">
                            N°
                        </th>
                        <th
                            class="border border-gray-800 bg-[#E1322D] text-white text-center font-bold uppercase text-[11px] px-2 py-2 w-[18%]">
                            Código
                        </th>
                        <th
                            class="border border-gray-800 bg-[#E1322D] text-white text-center font-bold uppercase text-[11px] px-2 py-2 w-[20%]">
                            Modelo
                        </th>
                        <th
                            class="border border-gray-800 bg-[#E1322D] text-white text-center font-bold uppercase text-[11px] px-2 py-2 w-[20%]">
                            Tipo Repuesto
                        </th>
                        <th
                            class="border border-gray-800 bg-[#E1322D] text-white text-center font-bold uppercase text-[11px] px-2 py-2 w-[10%]">
                            Cantidad
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @php $contador = 1; @endphp

                    @forelse($repuestos as $r)
                        <tr>
                            <td class="border border-gray-800 text-center px-2 py-2 bg-white/80">
                                {{ $contador++ }}
                            </td>
                            <td class="border border-gray-800 text-center px-2 py-2 bg-white/80">
                                {{ $r->codigo_repuesto ?? 'N/A' }}
                            </td>
                            <td class="border border-gray-800 text-center px-2 py-2 bg-white/80">
                                {{ $r->modelo ?? 'N/A' }}
                            </td>
                            <td class="border border-gray-800 text-center px-2 py-2 bg-white/80">
                                {{ $r->tipo_repuesto ?? 'N/A' }}
                            </td>
                            <td class="border border-gray-800 text-center px-2 py-2 bg-white/80 font-bold">
                                {{ $r->cantidad ?? 0 }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5"
                                class="border border-gray-800 text-center px-4 py-5 text-[11px] bg-white/80">
                                No hay repuestos registrados en esta solicitud
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

        <!-- FIRMAS -->
        <div class="grid grid-cols-2 gap-10 mt-12 page-break-inside-avoid">

            <!-- QUIEN RECIBE -->
            <div class="relative text-center h-[120px]">

                @if (!empty($firmaSolicitante))
                    <img src="{{ $firmaSolicitante }}"
                        class="absolute left-1/2 -translate-x-1/2
               top-[4px]
               max-h-[110px] max-w-[320px]
               z-10 opacity-90 object-contain">
                @endif


                <!-- Línea -->
<div class="absolute top-[60px] left-1/2 -translate-x-1/2 w-[220px] border-t border-gray-800"></div>

                <!-- Texto -->
                <div class="absolute top-[68px] left-0 right-0 text-center text-xs font-bold uppercase leading-tight">
                    {{ $solicitud->solicitante_nombre }}
                    {{ $solicitud->solicitante_apellido_paterno }}
                    {{ $solicitud->solicitante_apellido_materno }}<br>
                    <span class="text-[10px]">
                        {{ $solicitud->solicitante_rol ?? 'ROL' }}
                    </span>
                </div>
            </div>

            <!-- QUIEN ENTREGA -->
            <div class="relative text-center h-[120px]">

                @if (!empty($firmaAprobador))
                    <img src="{{ $firmaAprobador }}"
                        class="absolute left-1/2 -translate-x-1/2
               top-[4px]
               max-h-[110px] max-w-[320px]
               z-10 opacity-90 object-contain">
                @endif

                <!-- Línea -->
<div class="absolute top-[60px] left-1/2 -translate-x-1/2 w-[220px] border-t border-gray-800"></div>

                <!-- Texto -->
                <div class="absolute top-[68px] left-0 right-0 text-center text-xs font-bold uppercase leading-tight">
                    {{ $solicitud->aprobador_nombre }}
                    {{ $solicitud->aprobador_apellido_paterno }}
                    {{ $solicitud->aprobador_apellido_materno }}<br>
                    <span class="text-[10px]">
                        {{ $solicitud->aprobador_rol ?? 'ROL' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
