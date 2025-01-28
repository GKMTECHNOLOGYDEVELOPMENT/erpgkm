<h3>Hoja Entrega</h3>
<p>Contenido relacionado con la hoja de entrega.</p>

<!-- Contenedor donde se mostrará el PDF automáticamente -->
<div id="pdf-container-hoja-entrega" style="display: none;">
    <embed id="pdf-viewer-hoja-entrega" src="" type="application/pdf" width="100%" height="600px" />
</div>

<script>
    // Ejecutar la carga del PDF automáticamente al cargar la página
    document.addEventListener('DOMContentLoaded', function () {
        const ordenId = '{{ $orden->idTickets }}'; // El idTickets de la orden
        
        function loadPdf() {
        // Hacer la solicitud para obtener la URL del PDF de la Hoja de Entrega
        fetch(`/ver-hoja-entrega-pdf/${ordenId}`)
            .then(response => response.json())
            .then(data => {
                const pdfUrl = data.pdfUrl;
                const pdfViewer = document.getElementById('pdf-viewer-hoja-entrega');
                const pdfContainer = document.getElementById('pdf-container-hoja-entrega');
                
                // Establecer la URL del PDF en el visor
                pdfViewer.src = pdfUrl;

                // Mostrar el contenedor del PDF
                pdfContainer.style.display = 'block';
            })
            .catch(error => {
                console.error('Error al cargar el PDF:', error);
            });
        }

            // Cargar el PDF al cargar la página
            loadPdf();

// Actualizar el PDF cada 5 segundos
setInterval(loadPdf, 5000);
    });
</script>
