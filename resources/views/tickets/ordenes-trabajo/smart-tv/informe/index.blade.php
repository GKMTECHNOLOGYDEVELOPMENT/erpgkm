<div class="mt-4 border rounded-lg overflow-hidden">
    <iframe id="informePdfFrame" class="w-full h-[calc(100vh-150px)] block" frameborder="0"></iframe>
</div>

@if(isset($orden) && isset($orden->idTickets))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const iframe = document.getElementById('informePdfFrame');
                let ultimaActualizacion = null; // Guardar la última actualización conocida

                // Ajustar altura automáticamente al tamaño de la ventana
                function ajustarAltura() {
                    iframe.style.height = (window.innerHeight - 150) + 'px';
                }

                // Cargar el PDF en el iframe
                function cargarPdf() {
                    console.log("Recargando PDF...");
                    iframe.src = "{{ route('ordenes.generateInformePdf', ['idOt' => $orden->idTickets]) }}" + '?' + new Date().getTime();
                }

                // Verificar cambios en la base de datos
                function verificarCambios() {
                    fetch("{{ route('ordenes.checkUpdates', ['idOt' => $orden->idTickets]) }}")
                        .then(response => response.json())
                        .then(data => {
                            if (data.ultimaActualizacion && data.ultimaActualizacion !== ultimaActualizacion) {
                                ultimaActualizacion = data.ultimaActualizacion; // Actualizar la última fecha
                                console.log("Se detectaron cambios en la base de datos. Recargando PDF...");
                                cargarPdf(); // Recargar PDF si hay cambios
                            }
                        })
                        .catch(error => console.error("Error verificando cambios:", error));
                }

                // Cargar PDF al inicio
                ajustarAltura();
         
                window.addEventListener('resize', ajustarAltura);
            });
        </script>
    @endif