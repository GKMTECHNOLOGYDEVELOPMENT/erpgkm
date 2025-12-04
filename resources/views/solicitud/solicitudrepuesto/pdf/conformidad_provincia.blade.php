<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conformidad de Envío a Provincia - {{ $solicitud->codigo }}</title>
    <style>
        /* Mantén todos los estilos iguales... */
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        /* ... resto de estilos ... */
    </style>
</head>
<body>
    <!-- Encabezado -->
    <div class="header">
        <div class="company-info">
            <h2>GKM TECHNOLOGY</h2>
            <p>Av. Principal 123 | Tel: 9999 | RUC: 000000</p>
        </div>
        
        <div class="document-title">ACTA DE CONFORMIDAD DE ENVÍO A PROVINCIA</div>
        <div><strong>Solicitud:</strong> {{ $solicitud->codigo ?? 'N/A' }}</div>
        <div><strong>Ticket:</strong> {{ $solicitud->numeroTicket ?? 'N/A' }}</div>
    </div>

    <!-- Información de la Solicitud -->
    <div class="section">
        <div class="section-title">INFORMACIÓN DE LA SOLICITUD</div>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Código Solicitud:</span>
                <div class="info-value">{{ $solicitud->codigo ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <span class="info-label">Ticket:</span>
                <div class="info-value">{{ $solicitud->numeroTicket ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <span class="info-label">Solicitante (Provincia):</span>
                <div class="info-value">{{ $solicitud->solicitante_nombre ?? 'N/A' }} {{ $solicitud->solicitante_apellido ?? '' }}</div>
            </div>
            <div class="info-item">
                <span class="info-label">Fecha de Creación:</span>
                <div class="info-value">{{ $solicitud->fechacreacion ? \Carbon\Carbon::parse($solicitud->fechacreacion)->format('d/m/Y H:i') : 'N/A' }}</div>
            </div>
            <div class="info-item">
                <span class="info-label">Tipo de Servicio:</span>
                <div class="info-value">{{ $solicitud->tiposervicio ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <span class="info-label">Nivel de Urgencia:</span>
                <div class="info-value">{{ $solicitud->niveldeurgencia ? ucfirst($solicitud->niveldeurgencia) : 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Información del Envío -->
    <div class="section">
        <div class="section-title envio">INFORMACIÓN DEL ENVÍO A PROVINCIA</div>
        
        @if($solicitud->envio_info)
        <div class="transportista-info">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Transportista:</span>
                    <div class="info-value">{{ $solicitud->envio_info->transportista ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <span class="info-label">Placa del Vehículo:</span>
                    <div class="info-value">{{ $solicitud->envio_info->placa_vehiculo ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <span class="info-label">Fecha de Entrega al Transporte:</span>
                    <div class="info-value">{{ $solicitud->envio_info->fecha_entrega_transporte ? \Carbon\Carbon::parse($solicitud->envio_info->fecha_entrega_transporte)->format('d/m/Y H:i') : 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <span class="info-label">Persona que Entrega:</span>
                    <div class="info-value">{{ $solicitud->envio_info->usuario_entrego_nombre ?? 'N/A' }} {{ $solicitud->envio_info->usuario_entrego_apellido ?? '' }}</div>
                </div>
            </div>

            @if($solicitud->envio_info->observaciones)
            <div class="observaciones-envio">
                <strong>Observaciones del Envío:</strong>
                <p>{{ $solicitud->envio_info->observaciones }}</p>
            </div>
            @endif

            <!-- Mostrar foto del comprobante si existe -->
            @if($solicitud->envio_info->foto_comprobante && file_exists(public_path('storage/' . $solicitud->envio_info->foto_comprobante)))
            <div class="foto-comprobante">
                <p><strong>Foto del Comprobante:</strong></p>
                <img src="{{ public_path('storage/' . $solicitud->envio_info->foto_comprobante) }}" alt="Comprobante de entrega">
            </div>
            @elseif($solicitud->envio_info->foto_comprobante)
            <div class="info-item">
                <span class="info-label">Comprobante:</span>
                <div class="info-value">{{ $solicitud->envio_info->foto_comprobante }}</div>
            </div>
            @endif
        </div>
        @else
        <p style="color: #e74c3c; font-style: italic;">No hay información de envío registrada</p>
        @endif
    </div>

    <!-- Repuestos Enviados -->
    <div class="section">
        <div class="section-title repuestos">REPUESTOS ENVIADOS A PROVINCIA</div>
        <table>
            <thead>
                <tr>
                    <th width="25%">Repuesto</th>
                    <th width="15%">Código</th>
                    <th width="10%">Cantidad</th>
                    <th width="15%">Ubicación de Origen</th>
                    <th width="20%">Tipo de Repuesto</th>
                    <th width="15%">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($repuestos as $repuesto)
                <tr>
                    <td>{{ $repuesto->repuesto_nombre ?? 'N/A' }}</td>
                    <td>{{ $repuesto->codigo_barras ?: ($repuesto->codigo_repuesto ?? 'N/A') }}</td>
                    <td style="text-align: center;">{{ $repuesto->cantidad ?? '0' }}</td>
                    <td>{{ $repuesto->ubicacion_utilizada ?? 'N/A' }}</td>
                    <td>{{ $repuesto->tipo_repuesto ?? 'N/A' }}</td>
                    <td style="text-align: center;">
                        <span style="color: #27ae60; font-weight: bold;">
                            ✔ Enviado
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Resumen del Envío -->
    <div class="section">
        <div class="section-title">RESUMEN DEL ENVÍO</div>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Total Repuestos Únicos:</span>
                <div class="info-value">{{ $solicitud->cantidad ?? '0' }}</div>
            </div>
            <div class="info-item">
                <span class="info-label">Total Unidades Enviadas:</span>
                <div class="info-value">{{ $solicitud->totalcantidadproductos ?? '0' }}</div>
            </div>
            <div class="info-item">
                <span class="info-label">Estado de la Solicitud:</span>
                <div class="info-value" style="color: #27ae60; font-weight: bold;">
                    {{ $solicitud->estado ? ucfirst($solicitud->estado) : 'N/A' }} ✅
                </div>
            </div>
            <div class="info-item">
                <span class="info-label">Fecha de Aprobación:</span>
                <div class="info-value">
                    {{ $solicitud->fechaaprobacion ? \Carbon\Carbon::parse($solicitud->fechaaprobacion)->format('d/m/Y H:i') : 'N/A' }}
                </div>
            </div>
            <div class="info-item">
                <span class="info-label">Persona que Aprobó:</span>
                <div class="info-value">{{ $solicitud->aprobador_nombre ?? 'N/A' }} {{ $solicitud->aprobador_apellido ?? '' }}</div>
            </div>
            <div class="info-item">
                <span class="info-label">Fecha de Entrega al Transporte:</span>
                <div class="info-value">
                    {{ $solicitud->envio_info && $solicitud->envio_info->fecha_entrega_transporte ? \Carbon\Carbon::parse($solicitud->envio_info->fecha_entrega_transporte)->format('d/m/Y H:i') : 'N/A' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Observaciones Generales -->
    @if($solicitud->observaciones)
    <div class="section">
        <div class="section-title">OBSERVACIONES GENERALES</div>
        <div class="observaciones-envio">
            <p>{{ $solicitud->observaciones }}</p>
        </div>
    </div>
    @endif

    <!-- Firmas -->
    <div class="section">
        <div class="section-title firma">FIRMAS Y CONFORMIDAD</div>
        <div class="signature-section">
            <div>
                <div class="signature-line">
                    {{ $solicitud->solicitante_nombre ?? 'SOLICITANTE' }} {{ $solicitud->solicitante_apellido ?? '' }}
                </div>
                <div class="signature-label">SOLICITANTE / RECEPTOR EN PROVINCIA</div>
                <div style="text-align: center; margin-top: 10px; font-size: 11px;">
                    __________________________<br>
                    Firma / Sello
                </div>
            </div>
            
            <div>
                <div class="signature-line">
                    {{ $solicitud->aprobador_nombre ?? 'ADMINISTRADOR' }} {{ $solicitud->aprobador_apellido ?? '' }}
                </div>
                <div class="signature-label">PERSONA QUE ENTREGA AL TRANSPORTE</div>
                <div style="text-align: center; margin-top: 10px; font-size: 11px;">
                    __________________________<br>
                    Firma / Sello
                </div>
            </div>
        </div>
    </div>

    <!-- Transportista -->
    @if($solicitud->envio_info)
    <div class="section" style="margin-top: 30px;">
        <div class="signature-line" style="border-top-color: #f39c12;">
            {{ $solicitud->envio_info->transportista ?? 'TRANSPORTISTA' }}
        </div>
        <div class="signature-label">TRANSPORTISTA / EMPRESA DE TRANSPORTE</div>
        <div style="text-align: center; margin-top: 10px; font-size: 11px;">
            Placa: <strong>{{ $solicitud->envio_info->placa_vehiculo ?? 'N/A' }}</strong><br>
            __________________________<br>
            Firma / Sello del Transportista
        </div>
    </div>
    @endif

    <!-- Pie de página -->
    <div class="footer">
        <p><strong>DOCUMENTO OFICIAL DE ENVÍO A PROVINCIA</strong> | Generado el: {{ $fecha_generacion }}</p>
        <p>GKM TECHNOLOGY - Sistema de Gestión de Repuestos | Este documento certifica el envío de repuestos a provincia</p>
        <p style="font-size: 9px; color: #95a5a6;">
            Nota: Los repuestos han sido verificados, embalados y entregados al transporte para su envío a provincia.
            El receptor en provincia deberá verificar el estado de los repuestos al momento de la recepción.
        </p>
    </div>
</body>
</html>