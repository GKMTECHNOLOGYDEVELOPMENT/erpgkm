<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Acta de Conformidad</title>

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

        /* TÍTULO PRINCIPAL */
        .document-title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-family: "Times New Roman", Times, serif;
            padding-bottom: 8px;
        }

        /* SECCIONES */
        .section {
            margin-bottom: 16px;
        }

        .section-title {
            font-weight: bold;
            padding: 6px;
            border-left: 4px solid #2c3e50;
            background: #f4f6f7;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 0.2px;
        }

        /* TEXTO DEL ACTA */
        .acta-text {
            margin-bottom: 12px;
            text-align: justify;
            font-size: 12px;
            line-height: 1.5;
        }

        .linea {
            font-weight: bold;
            text-align: justify;
        }

        /* LISTA DE COMPROMISOS */
        .compromisos {
            margin: 10px 0 15px 20px;
            padding: 0;
        }

        .compromisos li {
            margin-bottom: 8px;
            text-align: justify;
            line-height: 1.4;
        }

        /* TABLA */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-top: 10px;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 8px;
            vertical-align: top;
            background-color: rgba(255, 255, 255, 0.8);
        }

        th {
            background: #E1322D;
            color: #fff;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
        }

        /* FIRMAS */
        .signature-section {
            margin-top: 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }

        .signature-line {
            border-top: 1px solid #333;
            margin-top: 50px;
            text-align: center;
            font-weight: bold;
            padding-top: 8px;
            font-size: 12px;
            text-transform: uppercase;
            min-height: 60px;
        }

        .sig-label {
            text-align: center;
            font-size: 10px;
            margin-top: 6px;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            font-weight: bold;
        }


        /* FOOTER */
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            border-top: 1px solid #aaa;
            padding-top: 8px;
            color: #444;
        }

        /* Evitar que la tabla o firmas se corten feo */
        .no-break {
            page-break-inside: avoid;
        }

        /* Estilos para datos específicos */
        .bold-text {
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="page">

        <!-- TÍTULO PRINCIPAL -->
        <h1 class="document-title">ACTA DE CONFORMIDAD</h1>

        <!-- TEXTO DEL ACTA -->
        <div class="section no-break">
            <p class="acta-text">
                Por medio de la presente, yo
                <span class="linea">
                    {{ $solicitud->solicitante_nombre ?? 'NOMBRES' }}
                    {{ $solicitud->solicitante_apellido_paterno ?? 'APELLIDO PATERNO' }}
                    {{ $solicitud->solicitante_apellido_materno ?? 'APELLIDO MATERNO' }}
                </span>,
                identificado(a) con {{ $solicitud->solicitante_tipo_documento ?? 'DOCUMENTO' }} N.°
                <span class="linea">{{ $solicitud->solicitante_documento ?? 'NÚMERO' }}</span>
                en mi calidad de técnico asignado, dejo constancia de haber recibido a conformidad por parte del área
                correspondiente
                los repuestos detallados en el presente documento.
            </p>

            <p class="acta-text">
                La entrega de los repuestos se realiza en atención al
                <strong>Ticket N.° {{ $solicitud->numero_ticket ?? 'N/A' }}</strong>,
                el cual sustenta la necesidad operativa y técnica de los bienes entregados para la ejecución de las
                labores asignadas.
                Declaro que los repuestos han sido recibidos en buen estado de conservación y funcionamiento, sin
                observaciones al momento de la entrega.
            </p>

            <p class="acta-text">
                Asimismo, asumo plena responsabilidad por la custodia, uso adecuado y conservación de los repuestos
                entregados,
                comprometiéndome a destinarlos únicamente para los fines establecidos en el ticket que origina la
                presente entrega.
            </p>

            <p class="acta-text" style="margin-top: 10px; font-weight: bold;">
                Me comprometo expresamente a:
            </p>

            <ul class="compromisos">
                <li>Utilizar los repuestos únicamente para las labores técnicas autorizadas.</li>
                <li>Evitar su pérdida, deterioro, daño o uso indebido.</li>
                <li>No transferir, prestar ni ceder los repuestos a terceros sin autorización expresa del área
                    responsable.</li>
                <li>Informar de manera inmediata cualquier incidente, falla o daño que se presente durante su uso.</li>
                <li>Devolver los repuestos cuando sean requeridos por la empresa o una vez concluida la labor para la
                    cual fueron asignados.</li>
            </ul>

            <p class="acta-text">
                La pérdida, daño o uso indebido de los repuestos entregados será de mi entera responsabilidad,
                conforme a las disposiciones internas de la empresa.
            </p>

            <p class="acta-text">
                En señal de conformidad, se firma la presente acta, dejando constancia de la entrega por parte de
                <strong>
                    {{ $solicitud->aprobador_nombre ?? 'NOMBRES' }}
                    {{ $solicitud->aprobador_apellido_paterno ?? 'APELLIDO PATERNO' }}
                    {{ $solicitud->aprobador_apellido_materno ?? 'APELLIDO MATERNO' }}
                </strong>,
                identificado(a) con {{ $solicitud->aprobador_tipo_documento ?? 'DOCUMENTO' }} N.°
                <strong>{{ $solicitud->aprobador_documento ?? 'NÚMERO' }}</strong>
                quien actúa en representación del área que efectúa la entrega.
            </p>
        </div>


        <!-- TABLA DE REPUESTOS -->
        <div class="section no-break">
            <table>
                <thead>
                    <tr>
                        <th class="text-center" style="width:5%;">N°</th>
                        <th class="text-center" style="width:18%;">Código</th>
                        <th class="text-center" style="width:20%;">Modelo</th>
                        <th class="text-center" style="width:20%;">Tipo Repuesto</th>
                        <th class="text-center" style="width:10%;">Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    @php $contador = 1; @endphp
                    @forelse($repuestos as $r)
                        <tr>
                            <td class="text-center">{{ $contador++ }}</td>
                            <td class="text-center">
                                {{ $r->codigo_repuesto ?? 'N/A' }}
                            </td>
                            <td class="text-center">
                                {{ $r->modelo ?? 'N/A' }}
                            </td>
                            <td class="text-center">
                                {{ $r->tipo_repuesto ?? 'N/A' }}
                            </td>
                            <td class="text-center bold-text">
                                {{ $r->cantidad ?? 0 }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center" style="padding: 20px;">
                                No hay repuestos registrados en esta solicitud
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>



        <!-- FIRMAS -->
        <div class="signature-section no-break">
            <!-- QUIEN RECIBE -->
            <div>
                <div class="signature-line">
                    {{ $solicitud->solicitante_nombre ?? 'NOMBRES' }}
                    {{ $solicitud->solicitante_apellido_paterno ?? 'APELLIDO PATERNO' }}
                    {{ $solicitud->solicitante_apellido_materno ?? 'APELLIDO MATERNO' }}
                    <br>
                    @if (!empty($solicitud->solicitante_cargo))
                        {{ $solicitud->solicitante_cargo }}
                    @else
                        CARGO
                    @endif
                </div>

            </div>

            <!-- QUIEN ENTREGA -->
            <div>
                <div class="signature-line">
                    {{ $solicitud->aprobador_nombre ?? 'NOMBRES' }}
                    {{ $solicitud->aprobador_apellido_paterno ?? 'APELLIDO PATERNO' }}
                    {{ $solicitud->aprobador_apellido_materno ?? 'APELLIDO MATERNO' }}
                    <br>
                    @if (!empty($solicitud->aprobador_cargo))
                        {{ $solicitud->aprobador_cargo }}
                    @else
                        RESPONSABLE DE ALMACÉN
                    @endif
                </div>
            </div>
        </div>

    </div>
</body>

</html>
