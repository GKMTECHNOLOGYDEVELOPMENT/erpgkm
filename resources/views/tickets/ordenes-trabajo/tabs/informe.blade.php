<h3>Informe</h3>
<p>Contenido relacionado con el informe.</p>

<!-- Contenedor donde se mostrará el PDF automáticamente -->
<div id="pdf-container" style="display: none;">
    <embed id="pdf-viewer" src="" type="application/pdf" width="100%" height="600px" />
</div>

<script>
    // Ejecutar la carga del PDF automáticamente al cargar la página
    document.addEventListener('DOMContentLoaded', function () {
        const ordenId = '{{ $orden->idTickets }}'; // El idTickets de la orden
        const pdfViewer = document.getElementById('pdf-viewer');
        const pdfContainer = document.getElementById('pdf-container');

        // Función para cargar el PDF
        function loadPdf() {
            fetch(`/ver-informe-pdf/${ordenId}`)
                .then(response => response.json())
                .then(data => {
                    const pdfUrl = data.pdfUrl;

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
