@php
    $rutaPdf = route('ordenes.helpdesk.pdf.conformidad.laboratorio', ['idOt' => $orden->idTickets]);
@endphp

<div id="pdfContainerConformidad">
    <iframe id="conformidadPdfFrame" data-src="{{ $rutaPdf }}" class="w-full h-[calc(100vh-150px)] block"
        frameborder="0"></iframe>
</div>

<div id="mobilePdfMessageConformidad" class="hidden flex flex-col items-center justify-center p-5 text-center">
    <p class="text-gray-700 text-sm mb-2">📄 No se puede mostrar el PDF en dispositivos móviles.</p>
    <a href="{{ $rutaPdf }}" download
        class="btn btn-primary text-white px-4 py-2 rounded-lg shadow-md transition-all duration-200">
        📥 Descargar PDF
    </a>
</div>

@if (isset($orden) && isset($orden->idTickets))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const iframe = document.getElementById('conformidadPdfFrameLaboratorio');
            const pdfContainer = document.getElementById('pdfContainerConformidadLaboratorio');
            const mobilePdfMessage = document.getElementById('mobilePdfMessageConformidadLaboratorio');

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

            ajustarVista();
            window.addEventListener('resize', ajustarVista);
        });
    </script>
@endif
