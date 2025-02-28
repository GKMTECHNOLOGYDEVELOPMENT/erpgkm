<div class="mt-4 border rounded-lg overflow-hidden">
    <!-- Contenedor del PDF -->
    <div id="pdfContainer">
        <iframe id="informePdfFrame" class="w-full h-[calc(100vh-150px)] block" frameborder="0"></iframe>
    </div>

    <!-- Mensaje para móviles -->
    <div id="mobilePdfMessage" class="hidden flex flex-col items-center justify-center p-5 text-center">
        <p class="text-gray-700 text-sm mb-2">📄 No se puede mostrar el PDF en dispositivos móviles.</p>
        <a href="{{ route('ordenes.generateInformePdf', ['idOt' => $orden->idTickets]) }}" download
            class="btn btn-primary text-white px-4 py-2 rounded-lg shadow-md transition-all duration-200">
            📥 Descargar PDF
        </a>
    </div>
</div>

@if (isset($orden) && isset($orden->idTickets))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const iframe = document.getElementById('informePdfFrame');
            const pdfContainer = document.getElementById('pdfContainer');
            const mobilePdfMessage = document.getElementById('mobilePdfMessage');
            let ultimaActualizacion = null;

            // Ajustar vista según el tamaño de la pantalla
            function ajustarVista() {
                if (window.innerWidth < 640) { 
                    pdfContainer.classList.add("hidden"); // Oculta el iframe en móviles
                    mobilePdfMessage.classList.remove("hidden"); // Muestra el mensaje y botón de descarga
                } else {
                    pdfContainer.classList.remove("hidden"); // Muestra el iframe en pantallas grandes
                    mobilePdfMessage.classList.add("hidden"); // Oculta el mensaje de descarga
                    ajustarAltura(); // Ajustar altura del iframe
                }
            }

            // Ajustar altura del PDF automáticamente
            function ajustarAltura() {
                iframe.style.height = (window.innerHeight - 150) + 'px';
            }

            // Cargar el PDF en el iframe
            function cargarPdf() {
                console.log("Recargando PDF...");
                iframe.src = "{{ route('ordenes.generateInformePdf', ['idOt' => $orden->idTickets]) }}" + '?' +
                    new Date().getTime();
            }

            // Verificar cambios en la base de datos
            function verificarCambios() {
                fetch("{{ route('ordenes.checkUpdates', ['idOt' => $orden->idTickets]) }}")
                    .then(response => response.json())
                    .then(data => {
                        if (data.ultimaActualizacion && data.ultimaActualizacion !== ultimaActualizacion) {
                            ultimaActualizacion = data.ultimaActualizacion;
                            console.log("Se detectaron cambios en la base de datos. Recargando PDF...");
                            cargarPdf();
                        }
                    })
                    .catch(error => console.error("Error verificando cambios:", error));
            }

            // Ajustar vista en la carga inicial
            ajustarVista();

            // Ajustar vista al cambiar el tamaño de la ventana
            window.addEventListener('resize', ajustarVista);
        });
    </script>
@endif
