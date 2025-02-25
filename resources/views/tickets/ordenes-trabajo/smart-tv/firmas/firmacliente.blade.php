<x-layout.auth>

<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<!-- Contenedor general centrado vertical y horizontalmente -->
<div class="flex justify-center items-center min-h-screen">
    <!-- Contenedor de la firma del cliente -->
    <div class="w-full max-w-[300px] flex flex-col items-center">
        <span class="text-lg font-semibold mb-4 badge bg-success">Firma del Cliente</span>
        <div class="w-full h-[300px] border-2 border-gray-300 rounded-lg relative">
            <canvas id="signatureCanvasCliente" class="w-full h-full"></canvas>
        </div>
        <div class="flex space-x-3 mt-4">
            <button type="button" onclick="clearSignature('signatureCanvasCliente')" class="btn btn-danger">
                Limpiar
            </button>
            <button type="button" onclick="saveSignature('signatureCanvasCliente', 'FirmaCliente')"
                class="btn btn-success">
                Guardar
            </button>
        </div>
    </div>
</div>

<input type="hidden" id="ticketId" value="{{ $id }}">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- Incluir SignaturePad.js -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

<script>
    // Configuración de toastr
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        timeOut: 5000,
        extendedTimeOut: 1000,
    };

    // Objeto para almacenar la instancia de SignaturePad
    let signaturePadCliente = null;

    // Inicializar SignaturePad en el canvas del cliente
    function initializeSignature(canvasId) {
        const canvas = document.getElementById(canvasId);
        const ctx = canvas.getContext("2d");

        // Configuración del contexto
        ctx.lineJoin = "round";
        ctx.lineCap = "round";
        ctx.strokeStyle = "#000000";
        ctx.lineWidth = 2;

        // Función para redimensionar el canvas usando sus dimensiones reales
        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            // Usamos canvas.offsetWidth/Height para obtener el tamaño visual
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            // Aplicamos la escala al contexto
            ctx.setTransform(ratio, 0, 0, ratio, 0, 0);
        }

        // Crear la instancia de SignaturePad
        signaturePadCliente = new SignaturePad(canvas, {
            penColor: "#000000",
            backgroundColor: "rgba(255, 255, 255, 0)",
            velocityFilterWeight: 0.7,
            minWidth: 0.5,
            maxWidth: 2.5,
            throttle: 16
        });

        // Ajustar el canvas inicialmente y en cada resize
        resizeCanvas();
        window.addEventListener("resize", resizeCanvas);
    }

    // Limpiar la firma en el canvas del cliente
    function clearSignature(canvasId) {
        if (signaturePadCliente) {
            signaturePadCliente.clear();
        }
    }

    // Guardar la firma en el servidor
    function saveSignature(canvasId, name) {
        if (signaturePadCliente) {
            if (signaturePadCliente.isEmpty()) {
                toastr.error("Por favor, realiza la firma primero.");
            } else {
                const signatureData = signaturePadCliente.toDataURL(); // Obtener la firma en base64
                const ticketId = document.getElementById('ticketId').value; // Obtener el id del ticket

                // Enviar la firma al servidor usando fetch
                fetch(`/ordenes/smart/${ticketId}/guardar-firma`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ firma: signatureData })
                })
                .then(response => response.json())
                .then(data => {
                    toastr.success(data.message); // Mostrar mensaje de éxito
                    clearSignature(canvasId); // Limpiar el campo de firma después de guardar
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Hubo un error al guardar la firma.');
                });
            }
        }
    }

    // Inicializar el canvas cuando el DOM esté listo
    document.addEventListener("DOMContentLoaded", () => {
        initializeSignature("signatureCanvasCliente");
    });
</script>

</x-layout.auth>