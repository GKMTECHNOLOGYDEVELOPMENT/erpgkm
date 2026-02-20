{{-- resources/views/emails/notificacion-gerencia.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificación Gerencia</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
        .info-box { background: white; border-left: 4px solid #f093fb; padding: 20px; margin: 20px 0; border-radius: 5px; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 12px; }
        .badge { background: #f093fb; color: white; padding: 3px 8px; border-radius: 3px; font-size: 11px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>📋 Notificación de Generación de Accesos</h2>
        </div>
        
        <div class="content">
            <div class="info-box">
                <p><strong>👤 Usuario:</strong> {{ $usuario->Nombre }} {{ $usuario->apellidoPaterno }} {{ $usuario->apellidoMaterno }}</p>
                <p><strong>📄 Documento:</strong> {{ $usuario->documento }}</p>
                <p><strong>📧 Correo:</strong> {{ $usuario->correo }}</p>
                <p><strong>📱 Teléfono:</strong> {{ $usuario->telefono }}</p>
                
                <p><strong>🔐 Tipo de acceso generado:</strong> 
                    @if($accesoWeb && $accesoApp)
                        <span class="badge">🌐 Web + 📱 App</span>
                    @elseif($accesoWeb)
                        <span class="badge">🌐 Solo Web</span>
                    @elseif($accesoApp)
                        <span class="badge">📱 Solo App</span>
                    @endif
                </p>
                
                <p><strong>⏰ Fecha y hora:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
                <p><strong>👨‍💼 Administrador responsable:</strong> {{ $admin->name ?? $admin->usuario ?? 'Sistema' }}</p>
            </div>
            
            <p style="color: #666; font-style: italic;">
                ⚠️ Este es un correo informativo. No incluye contraseñas por seguridad.
                Las contraseñas se envían directamente al usuario.
            </p>
            
            <p>Saludos,<br><strong>Sistema de Gestión</strong></p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} GKM TECHNOLOGY - Todos los derechos reservados</p>
        </div>
    </div>
</body>
</html>