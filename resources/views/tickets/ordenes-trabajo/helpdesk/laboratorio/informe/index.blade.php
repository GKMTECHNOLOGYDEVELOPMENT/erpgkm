@php
    $rutaPdf = route('ordenes.helpdesk.pdf.laboratorio', ['idOt' => $orden->idTickets])
@endphp

<div class="mt-4 border rounded-lg overflow-hidden">
    <div id="pdfContainer">
        <iframe id="informePdfFrame"
            data-src="{{ $rutaPdf }}"
            class="w-full h-[calc(100vh-150px)] block" frameborder="0">
        </iframe>
    </div>

    <div id="mobilePdfMessage" class="hidden flex flex-col items-center justify-center p-5 text-center">
        <p class="text-gray-700 text-sm mb-2">📄 No se puede mostrar el PDF en dispositivos móviles.</p>
        <a href="{{ $rutaPdf }}" download
            class="btn btn-primary text-white px-4 py-2 rounded-lg shadow-md transition-all duration-200">
            📥 Descargar PDF
        </a>
    </div>
</div>

@if (isset($orden) && isset($orden->idTickets))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const iframe = document.getElementById('informePdfFrame');
            const pdfContainer = document.getElementById('pdfContainer');
            const mobilePdfMessage = document.getElementById('mobilePdfMessage');
            let ultimaActualizacion = null;

            function ajustarVista() {
                if (window.innerWidth < 640) {
                    pdfContainer.classList.add("hidden");
                    mobilePdfMessage.classList.remove("hidden");
                } else {
                    pdfContainer.classList.remove("hidden");
                    mobilePdfMessage.classList.add("hidden");
                    ajustarAltura();
                }
            }

            function ajustarAltura() {
                iframe.style.height = (window.innerHeight - 150) + 'px';
            }

            window.verificarCambios = function () {
                fetch("{{ route('ordenes.checkUpdates', ['idOt' => $orden->idTickets]) }}")
                    .then(response => response.json())
                    .then(data => {
                        if (data.ultimaActualizacion && data.ultimaActualizacion !== ultimaActualizacion) {
                            ultimaActualizacion = data.ultimaActualizacion;
                            console.log("Se detectaron cambios en la base de datos. Recargando PDF...");
                            window.cargarPdfDesdeAlpine?.();
                        }
                    })
                    .catch(error => console.error("Error verificando cambios:", error));
            };

            ajustarVista();
            window.addEventListener('resize', ajustarVista);
            setInterval(verificarCambios, 10000);
        });
    </script>
@endif
