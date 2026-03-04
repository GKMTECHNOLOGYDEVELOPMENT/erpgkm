{{-- resources/views/emails/credenciales.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido al Portal GKM TECHNOLOGY</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f7fb;
        }
        .container {
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
            letter-spacing: 1px;
        }
        .header h2 {
            margin: 10px 0 0;
            font-size: 20px;
            font-weight: 300;
            opacity: 0.95;
        }
        .header .empresa {
            font-size: 14px;
            margin-top: 15px;
            opacity: 0.9;
            border-top: 1px solid rgba(255,255,255,0.2);
            padding-top: 15px;
        }
        .content {
            padding: 40px 35px;
            background-color: #ffffff;
        }
        .welcome-message {
            text-align: center;
            margin-bottom: 30px;
        }
        .welcome-message h3 {
            color: #2d3748;
            font-size: 22px;
            margin-bottom: 15px;
        }
        .welcome-message p {
            color: #718096;
            font-size: 16px;
        }
        .features {
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f3ff 100%);
            border-radius: 12px;
            padding: 25px;
            margin: 30px 0;
            border: 1px solid #e0e7ff;
        }
        .features h4 {
            color: #2d3748;
            font-size: 18px;
            margin-top: 0;
            margin-bottom: 20px;
            text-align: center;
        }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        .feature-item {
            text-align: center;
            padding: 15px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .feature-icon {
            font-size: 24px;
            margin-bottom: 8px;
        }
        .feature-item span {
            display: block;
            font-size: 14px;
            color: #4a5568;
            font-weight: 500;
        }
        .credentials {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin: 30px 0;
            text-align: center;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        .credentials h3 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 20px;
            font-weight: 400;
            opacity: 0.95;
        }
        .credential-box {
            background: rgba(255,255,255,0.15);
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .credential-box strong {
            display: inline-block;
            min-width: 100px;
            font-size: 14px;
            opacity: 0.9;
        }
        .credential-box span {
            font-size: 18px;
            font-weight: 600;
            letter-spacing: 0.5px;
            background: rgba(255,255,255,0.2);
            padding: 4px 12px;
            border-radius: 20px;
            display: inline-block;
            margin-left: 10px;
        }
        .documento-note {
            font-size: 14px;
            opacity: 0.9;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid rgba(255,255,255,0.2);
        }
        .button {
            display: inline-block;
            background: white;
            color: #667eea;
            padding: 15px 40px;
            text-decoration: none;
            border-radius: 50px;
            margin-top: 25px;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.15);
        }
        .footer {
            text-align: center;
            padding: 30px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            margin: 5px 0;
            color: #718096;
            font-size: 13px;
        }
        .footer .legal {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #a0aec0;
        }
        .highlight {
            background-color: #fef3c7;
            color: #92400e;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header con branding -->
        <div class="header">
            <h1>GKM TECHNOLOGY</h1>
            <h2>Portal del Cliente</h2>
            <div class="empresa">
                <strong>{{ $cliente }}</strong> · Cliente General
            </div>
        </div>
        
        <!-- Contenido principal -->
        <div class="content">
            <!-- Mensaje de bienvenida personalizado -->
            <div class="welcome-message">
                <h3>¡Bienvenido al Portal, {{ explode(' ', $nombre)[0] }}! 👋</h3>
                <p>Nos complace darte la bienvenida al <strong>Portal de Clientes GKM TECHNOLOGY</strong>, 
                tu espacio exclusivo para gestionar y dar seguimiento a todos tus servicios.</p>
            </div>

            <!-- Características del portal -->
            <div class="features">
                <h4>✨ Lo que podrás hacer en tu portal:</h4>
                <div class="features-grid">
                    <div class="feature-item">
                        <div class="feature-icon">📊</div>
                        <span>Ver Reportes</span>
                        <small style="color: #718096; display: block; margin-top: 5px;">Análisis detallados</small>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">🎫</div>
                        <span>Gestionar Tickets</span>
                        <small style="color: #718096; display: block; margin-top: 5px;">Soporte y seguimiento</small>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">📈</div>
                        <span>Estado de Tickets</span>
                        <small style="color: #718096; display: block; margin-top: 5px;">Seguimiento en tiempo real</small>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">🔔</div>
                        <span>Notificaciones</span>
                        <small style="color: #718096; display: block; margin-top: 5px;">Actualizaciones al instante</small>
                    </div>
                </div>
            </div>

            <!-- Credenciales de acceso (DESTACADO) -->
            <div class="credentials">
                <h3>🔐 TUS CREDENCIALES DE ACCESO</h3>
                
                <div class="credential-box">
                    <strong>USUARIO:</strong>
                    <span>{{ $documento }}</span>
                </div>
                
                <div class="credential-box">
                    <strong>CONTRASEÑA:</strong>
                    <span>{{ $password }}</span>
                </div>
                
                <div class="documento-note">
                    ⚡ <strong>IMPORTANTE:</strong> Tu usuario de acceso es tu <strong class="highlight">NÚMERO DE DOCUMENTO</strong>
                </div>
            </div>

            <!-- Instrucciones adicionales -->
            <div style="background-color: #f0f9ff; padding: 20px; border-radius: 10px; margin: 25px 0; border-left: 4px solid #0ea5e9;">
                <p style="margin: 0; color: #0369a1;">
                    <strong>📌 PASO A PASO:</strong><br>
                    1. Ingresa con tu número de documento y la contraseña proporcionada<br>
                    2. Explora tus reportes y el estado de tus tickets<br>
                    3. Recomendamos cambiar tu contraseña en el primer ingreso
                </p>
            </div>

            <!-- Botón de acceso -->
            <div style="text-align: center;">
                <a href="{{ $url }}" class="button" style="color: #667eea; text-decoration: none;">
                    🌐 ACCEDER AL PORTAL
                </a>
            </div>

            <!-- Nota de seguridad -->
            <p style="margin-top: 30px; font-size: 14px; color: #94a3b8; text-align: center; font-style: italic;">
                Por seguridad, nunca compartas tus credenciales con nadie.<br>
                GKM TECHNOLOGY nunca te solicitará tu contraseña por correo o teléfono.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>GKM TECHNOLOGY</strong> · Innovación y Tecnología</p>
            <p>Av. Principal 123 · Lima, Perú · Tel: (01) 123-4567</p>
            <p>📧 soporte@gkmtechnology.com · 🌐 www.gkmtechnology.com</p>
            <div class="legal">
                <p>Este es un mensaje automático, por favor no responda a este correo.</p>
                <p>&copy; {{ date('Y') }} GKM TECHNOLOGY. Todos los derechos reservados.</p>
            </div>
        </div>
    </div>
</body>
</html>