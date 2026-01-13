<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Acta de Conformidad</title>

    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, "DejaVu Sans", sans-serif;
            font-size: 12px;
            line-height: 1.35;
            color: #111;
        }

        /* Hoja A4 con fondo membretado */
        .page {
            width: 210mm;
            min-height: 297mm;
            background: url('{{ $bgBase64 }}') no-repeat center center;
            background-size: cover;
            box-sizing: border-box;

            /* ajusta según tu hoja */
            padding: 42mm 18mm 22mm 18mm;
            /* top right bottom left */
        }

        .title {
            font-family: "Times New Roman", Times, serif;
            text-align: center;
            font-weight: 800;
            letter-spacing: .3px;
            font-size: 14px;
            text-transform: uppercase;
            margin: 0 0 14px 0;
        }


        .p {
            margin: 0 0 10px 0;
            text-align: justify;
        }

        .inline-fill {
            display: inline-block;
            border-bottom: 1px solid #333;
            min-width: 80px;
            padding: 0 2px 1px 2px;
            font-weight: 700;
        }

        .bullets {
            margin: 6px 0 12px 18px;
            padding: 0;
        }

        .bullets li {
            margin: 6px 0;
            text-align: justify;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 11px;
        }

        .table th,
        .table td {
            border: 1px solid #333;
            padding: 6px 8px;
            vertical-align: top;
        }

        .table th {
            font-weight: 800;
            text-transform: uppercase;
            text-align: center;
            background: rgba(255, 255, 255, .65);
        }

        .table td {
            background: rgba(255, 255, 255, .55);
        }

        .signatures {
            margin-top: 22mm;
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .signatures td {
            border: 1px solid #333;
            padding: 0;
            vertical-align: top;
            background: rgba(255, 255, 255, .55);
        }

        .sig-col {
            width: 33.33%;
        }

        .sig-head {
            text-align: center;
            font-weight: 800;
            text-transform: uppercase;
            padding: 6px 6px 4px 6px;
            border-bottom: 1px solid #333;
            background: rgba(255, 255, 255, .65);
        }

        .sig-body {
            padding: 10px 10px 8px 10px;
        }

        .sig-line {
            margin-top: 26px;
            border-top: 1px solid #333;
            padding-top: 4px;
            text-align: center;
            font-weight: 700;
        }

        .sig-sub {
            text-align: center;
            font-size: 10px;
            padding: 6px 6px 8px 6px;
            color: #222;
        }

        .sig-role {
            border-top: 1px solid #333;
            padding: 6px;
            text-align: center;
            font-size: 10px;
            text-transform: uppercase;
            font-weight: 700;
            background: rgba(255, 255, 255, .65);
        }

        /* evita cortes feos */
        .no-break {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>
    <div class="page">

        <div class="title">
            ACTA DE CONFORMIDAD DE ENTREGA BAJO CUSTODIA DEL TÉCNICO
        </div>

        <p class="p">
            Yo <span class="inline-fill">NOMBRE COMPLETO</span>, identificado(a) con DNI/CE
            N.° <span class="inline-fill">00000000</span>, declaro haber recibido a conformidad
            los siguientes bienes, los mismos que se encuentran en buen estado de conservación y funcionamiento,
            quedando bajo mi custodia y responsabilidad.
        </p>

        <p class="p" style="margin-top:8px;">
            Asimismo, me comprometo a:
        </p>

        <ul class="bullets no-break">
            <li>Hacer uso adecuado de los bienes conforme a su finalidad y especificaciones técnicas.</li>
            <li>Mantener los bienes en buen estado, evitando daños por mal uso, negligencia o manipulación indebida.
            </li>
            <li>Informar oportunamente cualquier falla, desperfecto o deterioro que se presente durante su uso.</li>
            <li>No ceder, trasladar o entregar los bienes a terceros sin la autorización correspondiente.</li>
        </ul>

        <table class="table no-break">
            <thead>
                <tr>
                    <th style="width:60%;">INSUMO / BIEN</th>
                    <th style="width:15%;">CANTIDAD</th>
                    <th style="width:25%;">OBSERVACIÓN</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>SILLA DE OFICINA GT-861</td>
                    <td style="text-align:center; font-weight:800;">6</td>
                    <td>1ra Dotación</td>
                </tr>
                <tr>
                    <td>—</td>
                    <td style="text-align:center;">—</td>
                    <td>—</td>
                </tr>
            </tbody>
        </table>

        <table class="signatures no-break">
            <tr>
                <td class="sig-col">
                    <div class="sig-head">ENTREGA CONFORME</div>
                    <div class="sig-body">
                        <div class="sig-line">NOMBRES Y APELLIDOS</div>
                        <div class="sig-sub">Firma y Fecha</div>
                    </div>
                    <div class="sig-role">CARGO</div>
                </td>

                <td class="sig-col">
                    <div class="sig-head">RECIBE CONFORME</div>
                    <div class="sig-body">
                        <div class="sig-line">NOMBRES Y APELLIDOS</div>
                        <div class="sig-sub">Firma y Fecha</div>
                    </div>
                    <div class="sig-role">CARGO</div>
                </td>

                <td class="sig-col">
                    <div class="sig-head">AUTORIZADO POR</div>
                    <div class="sig-body">
                        <div class="sig-line">NOMBRES Y APELLIDOS</div>
                        <div class="sig-sub">Firma y Fecha</div>
                    </div>
                    <div class="sig-role">CARGO</div>
                </td>
            </tr>
        </table>

    </div>
</body>

</html>
