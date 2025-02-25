<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe PDF</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        .title { text-align: center; font-size: 18px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="title">Informe de la Orden de Trabajo</div>
    <p><strong>ID:</strong> {{ $orden->id }}</p>
    <p><strong>Cliente:</strong> {{ $orden->cliente }}</p>
    <p><strong>Fecha de Creación:</strong> {{ $orden->created_at }}</p>
</body>
</html>
