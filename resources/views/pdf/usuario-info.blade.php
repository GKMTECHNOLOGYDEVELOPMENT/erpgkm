<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Información del Usuario</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .section { margin-bottom: 20px; }
        .section-title { background: #f0f0f0; padding: 5px 10px; font-weight: bold; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f0f0f0; text-align: left; padding: 8px; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        .info-row { margin-bottom: 5px; }
        .label { font-weight: bold; display: inline-block; width: 200px; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Información del Usuario</h1>
        <p>Generado el: {{ $fecha }}</p>
    </div>

    <!-- Información Personal -->
    <div class="section">
        <div class="section-title">Información Personal</div>
        <div class="info-row"><span class="label">Nombres:</span> {{ $usuario->Nombre }}</div>
        <div class="info-row"><span class="label">Apellidos:</span> {{ $usuario->apellidoPaterno }} {{ $usuario->apellidoMaterno }}</div>
        <div class="info-row"><span class="label">Documento:</span> {{ $usuario->documento }} ({{ $usuario->tipoDocumento->nombre ?? 'N/A' }})</div>
        <div class="info-row"><span class="label">Fecha Nacimiento:</span> {{ $usuario->fechaNacimiento ? date('d/m/Y', strtotime($usuario->fechaNacimiento)) : 'N/A' }}</div>
        <div class="info-row"><span class="label">Teléfono:</span> {{ $usuario->telefono }}</div>
        <div class="info-row"><span class="label">Email:</span> {{ $usuario->correo }}</div>
        <div class="info-row"><span class="label">Usuario:</span> {{ $usuario->usuario }}</div>
    </div>

    <!-- Información Laboral -->
    <div class="section">
        <div class="section-title">Información Laboral</div>
        <div class="info-row"><span class="label">Tipo de Usuario:</span> {{ $usuario->tipoUsuario->nombre ?? 'N/A' }}</div>
        <div class="info-row"><span class="label">Rol:</span> {{ $usuario->rol->nombre ?? 'N/A' }}</div>
        <div class="info-row"><span class="label">Área:</span> {{ $usuario->tipoArea->nombre ?? 'N/A' }}</div>
        <div class="info-row"><span class="label">Sucursal:</span> {{ $usuario->sucursal->nombre ?? 'N/A' }}</div>
        <div class="info-row"><span class="label">Sueldo por Hora:</span> S/ {{ number_format($usuario->sueldoPorHora, 2) }}</div>
        <div class="info-row"><span class="label">Sueldo Mensual:</span> S/ {{ number_format($usuario->sueldoMensual, 2) }}</div>
        <div class="info-row"><span class="label">Estado:</span> {{ $usuario->estado == 1 ? 'Activo' : 'Inactivo' }}</div>
    </div>

    <!-- Información de Dirección -->
    <div class="section">
        <div class="section-title">Información de Dirección</div>
        <div class="info-row"><span class="label">Nacionalidad:</span> {{ $usuario->nacionalidad }}</div>
        <div class="info-row"><span class="label">Departamento:</span> {{ $usuario->departamento }}</div>
        <div class="info-row"><span class="label">Provincia:</span> {{ $usuario->provincia }}</div>
        <div class="info-row"><span class="label">Distrito:</span> {{ $usuario->distrito }}</div>
        <div class="info-row"><span class="label">Dirección:</span> {{ $usuario->direccion }}</div>
    </div>

    <!-- Documentos Adjuntos -->
    @if($documentos->count() > 0)
    <div class="section">
        <div class="section-title">Documentos Adjuntos ({{ $documentos->count() }})</div>
        <table>
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Nombre</th>
                    <th>Tamaño</th>
                    <th>Fecha Subida</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documentos as $doc)
                <tr>
                    <td>{{ $doc->tipo_documento }}</td>
                    <td>{{ $doc->nombre_archivo }}</td>
                    <td>{{ number_format($doc->tamano / 1024, 2) }} KB</td>
                    <td>{{ $doc->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Cuentas Bancarias -->
    @if($cuentasBancarias->count() > 0)
    <div class="section">
        <div class="section-title">Cuentas Bancarias ({{ $cuentasBancarias->count() }})</div>
        <table>
            <thead>
                <tr>
                    <th>Tipo de Cuenta</th>
                    <th>Banco</th>
                    <th>Número de Cuenta</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cuentasBancarias as $cuenta)
                <tr>
                    <td>{{ $cuenta->tipodecuenta == 1 ? 'Interbancario' : 'Cuenta' }}</td>
                    <td>{{ $cuenta->banco }}</td>
                    <td>{{ $cuenta->numerocuenta }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        <p>Documento generado automáticamente - {{ config('app.name') }}</p>
        <p>Página 1 de 1</p>
    </div>
</body>
</html>