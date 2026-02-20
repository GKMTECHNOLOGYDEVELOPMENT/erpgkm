{{-- resources/views/emails/credenciales-app.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credenciales App</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #48c6ef 0%, #6f86d6 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
        .credentials { background: white; border-left: 4px solid #48c6ef; padding: 20px; margin: 20px 0; border-radius: 5px; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 12px; }
        .button { background: #48c6ef; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block; }
        .app-badge { background: #000; color: white; padding: 5px 10px; border-radius: 20px; font-size: 12px; display: inline-block; }
        .note { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📱 Acceso a Aplicación Móvil</h1>
        </div>
        
        <div class="content">
            <h2>¡Hola {{ $usuario->Nombre }} {{ $usuario->apellidoPaterno }}!</h2>
            
            <p>Se han generado tus credenciales de acceso para la <strong>Aplicación Móvil</strong>:</p>
            
            <div class="credentials">
                <p><strong>📱 Usuario:</strong> {{ $usuarioApp }}</p>
                <p><strong>🔑 Contraseña:</strong> <span style="font-family: monospace; background: #f0f0f0; padding: 5px;">{{ $password }}</span></p>
            </div>
            
            <div class="note">
                <p><strong>📌 Nota importante:</strong> Las contraseñas de la aplicación móvil solo pueden ser modificadas desde la misma app. No es posible cambiarlas desde la web.</p>
            </div>
            
            <p>📲 <strong>Descarga la app:</strong></p>
            <p>
                <span class="app-badge">📱 App Store</span> 
                <span class="app-badge">🤖 Google Play</span>
            </p>
            
            <p>⚠️ <strong>Importante:</strong></p>
            <ul>
                <li>Usa estas credenciales solo en la aplicación móvil</li>
                <li>Las contraseñas son independientes de la web</li>
                <li>Mantén tu aplicación actualizada</li>
                <li>Si olvidas tu contraseña, deberás restablecerla desde la app</li>
            </ul>
            
            <p>Saludos,<br><strong>Equipo de Sistemas</strong></p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} GKM TECHNOLOGY - Todos los derechos reservados</p>
            <p>Este es un correo automático, por favor no responder.</p>
        </div>
    </div>
</body>
</html>