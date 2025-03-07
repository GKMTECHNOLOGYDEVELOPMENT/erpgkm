<!DOCTYPE html>
<html>
<head>
    <title>Detalles de tu cuenta</title>
</head>
<body>
    <h1>Hola {{ $usuario }}</h1>
    <p>Tu cuenta ha sido creada exitosamente. A continuación, te proporcionamos los detalles de tu cuenta:</p>
    <p><strong>Usuario:</strong> {{ $usuario }}</p>
    <p><strong>Clave:</strong> {{ $clave }}</p>
    <p>Por favor, recuerda cambiar tu contraseña después de iniciar sesión.</p>
</body>
</html>
