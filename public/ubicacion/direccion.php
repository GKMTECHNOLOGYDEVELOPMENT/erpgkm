<?php
if (isset($_GET['url'])) {
    $shortURL = $_GET['url'];

    // Inicializar cURL
    $ch = curl_init($shortURL);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true); // No descargar el contenido, solo los headers

    curl_exec($ch);
    $finalURL = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL); // Obtener la URL final
    curl_close($ch);

    echo json_encode(["expanded_url" => $finalURL]);
} else {
    echo json_encode(["error" => "No se proporcionó una URL"]);
}
