<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conformidad de Entrega - {{ $solicitud->codigo }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .company-info {
            text-align: center;
            margin-bottom: 15px;
        }
        .company-info h2 {
            margin: 0;
            color: #2c3e50;
            font-size: 18px;
        }
        .company-info p {
            margin: 5px 0;
            color: #7f8c8d;
        }
        .document-title {
            font-size: 16px;
            font-weight: bold;
            margin: 15px 0;
            color: #2c3e50;
        }
        .section {
            margin-bottom: 15px;
        }
        .section-title {
            font-weight: bold;
            background-color: #f8f9fa;
            padding: 8px;
            border-left: 4px solid #3498db;
            margin-bottom: 10px;
            color: #2c3e50;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 10px;
        }
        .info-item {
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: bold;
            color: #34495e;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 11px;
        }
        th, td {
            border: 1px solid #bdc3c7;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #34495e;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .signature-section {
            margin-top: 50px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }
        .signature-line {
            border-top: 1px solid #7f8c8d;
            margin-top: 60px;
            text-align: center;
            padding-top: 5px;
            font-weight: bold;
        }
        .signature-label {
            text-align: center;
            margin-top: 5px;
            color: #7f8c8d;
            font-size: 11px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #95a5a6;
            border-top: 1px solid #bdc3c7;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <div class="header">
        <div class="company-info">
            <h2>GKM TECHNOLOGY</h2>
            <p>Av. Principal 123 | Tel: 9999 | RUC: 000000</p>
        </div>
        
        <div class="document-title">ACTA DE CONFORMIDAD DE ENTREGA - REPUESTOS</div>
        <div><strong>Solicitud:</strong> {{ $solicitud->codigo }}</div>
    </div>

    <!-- Información de la Solicitud -->
    <div class="section">
        <div class="section-title">INFORMACIÓN DE LA SOLICITUD</div>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Código Solicitud:</span> {{ $solicitud->codigo }}
            </div>
            <div class="info-item">
                <span class="info-label">Ticket:</span> {{ $solicitud->numero_ticket ?? 'N/A' }}
            </div>
            <div class="info-item">
                <span class="info-label">Solicitante:</span> {{ $solicitud->solicitante_nombre }} {{ $solicitud->solicitante_apellido }}
            </div>
            <div class="info-item">
                <span class="info-label">Fecha de Creación:</span> {{ \Carbon\Carbon::parse($solicitud->fechacreacion)->format('d/m/Y') }}
            </div>
            <div class="info-item">
                <span class="info-label">Tipo de Servicio:</span> {{ $solicitud->tiposervicio }}
            </div>
            <div class="info-item">
                <span class="info-label">Nivel de Urgencia:</span> {{ ucfirst($solicitud->niveldeurgencia) }}
            </div>
            @if($solicitud->marca_nombre)
            <div class="info-item">
                <span class="info-label">Marca:</span> {{ $solicitud->marca_nombre }}
            </div>
            @endif
            @if($solicitud->modelo_nombre)
            <div class="info-item">
                <span class="info-label">Modelo:</span> {{ $solicitud->modelo_nombre }}
            </div>
            @endif
            @if($solicitud->serie)
            <div class="info-item">
                <span class="info-label">Serie:</span> {{ $solicitud->serie }}
            </div>
            @endif
        </div>
    </div>

    <!-- Repuestos Entregados -->
    <div class="section">
        <div class="section-title">REPUESTOS ENTREGADOS</div>
        <table>
            <thead>
                <tr>
                    <th width="25%">Repuesto</th>
                    <th width="15%">Código</th>
                    <th width="10%">Cantidad</th>
                    <th width="15%">Ubicación</th>
                    <th width="20%">Destinatario</th>
                    <th width="15%">Ticket</th>
                </tr>
            </thead>
            <tbody>
                @foreach($repuestos as $repuesto)
                <tr>
                    <td>{{ $repuesto->repuesto_nombre }}</td>
                    <td>{{ $repuesto->codigo_barras ?: $repuesto->codigo_repuesto }}</td>
                    <td style="text-align: center;">{{ $repuesto->cantidad }}</td>
                    <td>{{ $repuesto->ubicacion_utilizada ?? 'N/A' }}</td>
                    <td>
                        @if($repuesto->destinatario_nombre)
                            {{ $repuesto->destinatario_nombre }} {{ $repuesto->destinatario_apellido }}
                            @if($repuesto->tipo_entrega)
                                <br><small>({{ ucfirst(str_replace('_', ' ', $repuesto->tipo_entrega)) }})</small>
                            @endif
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $repuesto->numero_ticket ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Resumen -->
    <div class="section">
        <div class="section-title">RESUMEN</div>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Total Repuestos Únicos:</span> {{ $solicitud->cantidad }}
            </div>
            <div class="info-item">
                <span class="info-label">Total Unidades Entregadas:</span> {{ $solicitud->totalcantidadproductos }}
            </div>
            <div class="info-item">
                <span class="info-label">Estado:</span> <strong>{{ ucfirst($solicitud->estado) }}</strong>
            </div>
            <div class="info-item">
                <span class="info-label">Fecha de Aprobación:</span> 
                {{ $solicitud->fechaaprobacion ? \Carbon\Carbon::parse($solicitud->fechaaprobacion)->format('d/m/Y H:i') : 'N/A' }}
            </div>
        </div>
    </div>

    <!-- Observaciones -->
    @if($solicitud->observaciones)
    <div class="section">
        <div class="section-title">OBSERVACIONES</div>
        <p>{{ $solicitud->observaciones }}</p>
    </div>
    @endif

    <!-- Firmas -->
    <div class="signature-section">
        <div>
            <div class="signature-line">
                {{ $solicitud->solicitante_nombre }} {{ $solicitud->solicitante_apellido }}
            </div>
            <div class="signature-label">SOLICITANTE</div>
        </div>
        
        <div>
            <div class="signature-line">
                {{ $solicitud->aprobador_nombre ?? 'Administrador del Sistema' }} {{ $solicitud->aprobador_apellido ?? '' }}
            </div>
            <div class="signature-label">PERSONA QUE ENTREGA</div>
        </div>
    </div>

    <!-- Pie de página -->
    <div class="footer">
        <p><strong>Documento generado automáticamente</strong> | Fecha de generación: {{ $fecha_generacion }}</p>
        <p>GKM TECHNOLOGY - Sistema de Gestión de Repuestos</p>
    </div>
</body>
</html>