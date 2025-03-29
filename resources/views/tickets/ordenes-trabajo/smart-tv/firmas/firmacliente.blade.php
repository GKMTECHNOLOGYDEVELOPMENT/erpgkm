<x-layout.auth>

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Firma -->
    <div class="flex justify-center items-center min-h-screen px-4">
        <div class="w-full max-w-[360px] flex flex-col items-center" x-data="modal">

            <!-- Vista previa del informe generado (como imagen) -->
            <img src="{{ route('informe.vista-previa.imagen', ['idOt' => $id]) }}" alt="Vista previa del informe"
                class="w-full rounded-xl shadow-xl border border-gray-300 mt-6 mb-4">

            <!-- Título -->
            <span class="text-lg font-semibold mb-2 badge bg-success">FIRMA DEL CLIENTE</span>

            <!-- Canvas firma -->
            <div class="w-full h-[300px] border-2 border-gray-300 rounded-lg relative mt-2">
                <canvas id="signatureCanvasCliente" class="w-full h-full"></canvas>
            </div>

            <!-- Botones -->
            <div class="flex space-x-3 mt-4">
                <button type="button" onclick="clearSignature()" class="btn btn-danger">Limpiar</button>
                <button type="button" onclick="saveSignature()" class="btn btn-success">Guardar</button>
            </div>

            <div class="mb-4">

            </div>

        </div>
    </div>



    <input type="hidden" id="ticketId" value="{{ $id }}">
    <input type="hidden" id="visitaId" value="{{ $idVisitas }}">

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>


    <script>
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: 5000,
        };

        let signaturePadCliente = null;

        function initializeSignature(canvasId) {
            const canvas = document.getElementById(canvasId);
            const ctx = canvas.getContext("2d");

            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                ctx.setTransform(ratio, 0, 0, ratio, 0, 0);
            }

            signaturePadCliente = new SignaturePad(canvas, {
                penColor: "#000000",
                backgroundColor: "rgba(255,255,255,0)",
                velocityFilterWeight: 0.7,
                minWidth: 0.5,
                maxWidth: 2.5,
                throttle: 16
            });

            resizeCanvas();
            window.addEventListener("resize", resizeCanvas);
        }

        function clearSignature() {
            if (signaturePadCliente) signaturePadCliente.clear();
        }

        function saveSignature() {
            if (!signaturePadCliente || signaturePadCliente.isEmpty()) {
                return toastr.error("Por favor, realiza la firma primero.");
            }

            const firma = signaturePadCliente.toDataURL();
            const ticketId = document.getElementById('ticketId').value;
            const visitaId = document.getElementById('visitaId').value;

            if (!visitaId) {
                return toastr.error("No se encontró la visita asociada.");
            }

            fetch(`/ordenes/smart/${ticketId}/guardar-firma/${visitaId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        firma
                    })
                })
                .then(res => res.json())
                .then(data => {
                    toastr.success(data.message);
                })
                .catch(err => {
                    console.error(err);
                    toastr.error('Error al guardar la firma.');
                });
        }

        document.addEventListener("DOMContentLoaded", () => {
            initializeSignature("signatureCanvasCliente");
        });
    </script>

</x-layout.auth>
