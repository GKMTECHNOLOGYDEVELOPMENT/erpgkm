<div class="mt-4 border rounded-lg overflow-hidden">
    <iframe id="informePdfFrame" class="w-full h-[calc(100vh-150px)] block" frameborder="0"></iframe>
</div>

@if(isset($orden) && isset($orden->idTickets))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const iframe = document.getElementById('informePdfFrame');

            // Ajustar altura al tamaño disponible
            function ajustarAltura() {
                iframe.style.height = (window.innerHeight - 150) + 'px';
            }

            // Cargar automáticamente el PDF
            iframe.src = "{{ route('ordenes.generateInformePdf', ['idOt' => $orden->idTickets]) }}" + '?' + new Date().getTime();
            
            // Ajustar altura en carga y redimensionamiento
            ajustarAltura();
            window.addEventListener('resize', ajustarAltura);
        });
    </script>
@endif
