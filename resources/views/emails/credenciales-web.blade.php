{{-- resources/views/emails/credenciales-web.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credenciales Web</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
        .credentials { background: white; border-left: 4px solid #667eea; padding: 20px; margin: 20px 0; border-radius: 5px; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 12px; }
        .button { background: #667eea; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block; }
        .reset-link { background: #f0f0f0; padding: 15px; border-radius: 8px; text-align: center; margin: 20px 0; }
        .reset-link a { color: #667eea; font-weight: bold; text-decoration: none; font-size: 16px; }
        .reset-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Acceso a Plataforma Web</h1>
        </div>
        
        <div class="content">
            <h2>¡Hola {{ $usuario->Nombre }} {{ $usuario->apellidoPaterno }}!</h2>
            
            <p>Se han generado tus credenciales de acceso para la <strong>Plataforma Web</strong>:</p>
            
            <div class="credentials">
                <p><strong>🌐 Usuario:</strong> {{ $correoAcceso }}</p>
                <p><strong>🔑 Contraseña:</strong> <span style="font-family: monospace; background: #f0f0f0; padding: 5px;">{{ $password }}</span></p>
            </div>
            
            <div class="reset-link">
                <p style="margin-bottom: 10px;"><strong>🔐 ¿Necesitas restablecer tu contraseña?</strong></p>
                <p style="margin-bottom: 10px;">Si deseas cambiar tu contraseña por una más segura, puedes hacerlo en el siguiente enlace:</p>
                <p><a href="http://127.0.0.1:8000/auth/cover-password-reset" target="_blank">🔗 Restablecer Contraseña Web</a></p>
                <p style="font-size: 12px; color: #666; margin-top: 10px;">Este enlace te permitirá crear una nueva contraseña para tu acceso web.</p>
            </div>
            
            <p>⚠️ <strong>Importante:</strong></p>
            <ul>
                <li>Por seguridad, te recomendamos cambiar tu contraseña periódicamente</li>
                <li>No compartas tus credenciales con nadie</li>
                <li>El sistema bloqueará el acceso después de varios intentos fallidos</li>
                <li>Las credenciales de la App son independientes y se gestionan desde la aplicación móvil</li>
            </ul>
            
            <p>🔗 <a href="http://127.0.0.1:8000/auth/login" class="button">Ir a la Plataforma Web</a></p>
            
            <p>Saludos,<br><strong>Equipo de Sistemas</strong></p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} GKM TECHNOLOGY - Todos los derechos reservados</p>
            <p>Este es un correo automático, por favor no responder.</p>
        </div>
    </div>
</body>
</html>