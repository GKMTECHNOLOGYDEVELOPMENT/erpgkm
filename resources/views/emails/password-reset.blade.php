{{-- resources/views/emails/password-reset.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Restablecer Contraseña</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .btn {
            display: inline-block;
            background-color: #8B1E3F;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .btn:hover {
            background-color: #A6274C;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Restablecer tu contraseña</h2>
        <p>Hola,</p>
        <p>Recibimos una solicitud para restablecer la contraseña de tu cuenta en Solutions Force.</p>
        <p>Si fuiste tú quien solicitó este cambio, haz clic en el siguiente botón:</p>
        
        <p>
            <a href="{{ $resetUrl }}" class="btn">
                Restablecer Contraseña
            </a>
        </p>
        
        <p>O copia y pega el siguiente enlace en tu navegador:</p>
        <p>{{ $resetUrl }}</p>
        
        <p>Si no solicitaste restablecer tu contraseña, puedes ignorar este mensaje.</p>
        
        <p>Este enlace expirará en 60 minutos.</p>
        
        <hr>
        <p>Saludos,<br>El equipo de Solutions Force</p>
    </div>
</body>
</html>