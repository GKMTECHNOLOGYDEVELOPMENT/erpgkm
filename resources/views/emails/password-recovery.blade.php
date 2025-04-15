<!-- resources/views/emails/password-recovery.blade.php -->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de Contraseña</title>
</head>
<body>
    <h1>Recuperación de Contraseña</h1>
    <p>Hola,</p>
    <p>Hemos recibido una solicitud para recuperar tu contraseña. Haz clic en el siguiente enlace para restablecerla:</p>
    <a href="{{ url('password/reset', $token) }}">Restablecer mi contraseña</a>
    <p>Si no solicitaste esta recuperación, puedes ignorar este correo.</p>
</body>
</html>
