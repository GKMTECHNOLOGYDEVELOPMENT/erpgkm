<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Constancia de Entrega</title>
    <style>
        body { 
            font-family: 'Arial', sans-serif; 
            font-size: 12px; 
            line-height: 1.5;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        .section { 
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #eee;
            border-radius: 5px;
        }
        .section-title {
            font-weight: bold;
            color: #444;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .info-label {
            font-weight: bold;
            width: 120px;
            display: inline-block;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .signature-area {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-line {
            border-top: 1px solid #333;
            width: 200px;
            text-align: center;
            padding-top: 5px;
        }
        .photos-container {
            margin-top: 20px;
        }
        .photo-section {
            page-break-inside: avoid;
            margin-bottom: 15px;
        }
        .photo-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .photo-image {
            max-width: 100%;
            max-height: 300px;
            border: 1px solid #ddd;
            margin-bottom: 5px;
        }
        .photo-description {
            font-size: 11px;
            color: #555;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>CONSTANCIA DE ENTREGA</h1>
        <div>N° {{ $orden->numero_ticket }}</div>
    </div>

    <div class="section">
        <div class="section-title">INFORMACIÓN DEL DOCUMENTO</div>
        <div><span class="info-label">Fecha de compra:</span> {{ $orden->fechaCompra->format('d/m/Y') }}</div>
        <div><span class="info-label">Tipo de documento:</span> Constancia de Entrega</div>
    </div>

    <div class="section">
        <div class="section-title">DATOS DEL CLIENTE</div>
        <div><span class="info-label">Nombre:</span> {{ optional($orden->cliente)->nombre }}</div>
        <div><span class="info-label">Email:</span> {{ optional($orden->cliente)->email }}</div>
        <div><span class="info-label">Dirección:</span> {{ optional($orden->cliente)->direccion }}</div>
        <div><span class="info-label">Teléfono:</span> {{ optional($orden->cliente)->telefono }}</div>
    </div>

    <div class="section">
        <div class="section-title">OBSERVACIONES</div>
        <div>{!! nl2br(e(optional($constancia)->observaciones)) !!}</div>
    </div>

    @if($fotos && count($fotos) > 0)
    <div class="section">
        <div class="section-title">EVIDENCIA FOTOGRÁFICA</div>
        <div class="photos-container">
            @foreach($fotos as $foto)
                <div class="photo-section">
                    <div class="photo-title">Imagen #{{ $loop->iteration }}</div>
                    <img class="photo-image" src="{{ $foto['imagen_base64'] }}" alt="Evidencia {{ $loop->iteration }}">
                    @if($foto['descripcion'])
                        <div class="photo-description">{{ $foto['descripcion'] }}</div>
                    @endif
                </div>
                
                @if($loop->iteration % 2 == 0 && !$loop->last)
                    <div class="page-break"></div>
                @endif
            @endforeach 
        </div>
    </div>
@endif
    <div class="signature-area">
        <div class="signature-line">Firma del Cliente</div>
        <div class="signature-line">Firma del Representante</div>
    </div>

    <div class="footer">
        Documento generado el {{ now()->format('d/m/Y H:i') }} | Sistema de Gestión
    </div>
</body>
</html>