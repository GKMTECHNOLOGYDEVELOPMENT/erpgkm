<span class="text-lg font-semibold mb-4 badge bg-success">Firmas</span>

<div class="flex flex-col md:flex-row md:space-x-4 space-y-6 md:space-y-0">
    <!-- Canvas para la firma del Técnico -->
    <div class="w-full md:w-1/2 flex flex-col items-center">
        <p class="mb-2 text-lg font-medium text-center">Firma del Técnico</p>
        <div class="w-full h-64 md:h-96 border-2 border-gray-300 rounded-lg  relative">
            <canvas id="signatureCanvasTecnico" class="w-full h-full"></canvas>
        </div>
        <div class="flex space-x-3 mt-4">
            <button type="button" onclick="clearSignature('signatureCanvasTecnico')" class="btn btn-danger">
                🗑 Limpiar
            </button>
            <button type="button" onclick="saveSignature('signatureCanvasTecnico', 'FirmaTecnico')" class="btn btn-success">
                💾 Guardar
            </button>
        </div>
    </div>

    <!-- Canvas para la firma del Cliente -->
    <div class="w-full md:w-1/2 flex flex-col items-center">
        <p class="mb-2 text-lg font-medium text-center">Firma del Cliente</p>
        <div class="w-full h-64 md:h-96 border-2 border-gray-300 rounded-lg  relative">
            <canvas id="signatureCanvasCliente" class="w-full h-full"></canvas>
        </div>
        <div class="flex space-x-3 mt-4">
            <button type="button" onclick="clearSignature('signatureCanvasCliente')" class="btn btn-danger">
                🗑 Limpiar
            </button>
            <button type="button" onclick="saveSignature('signatureCanvasCliente', 'FirmaCliente')" class="btn btn-success">
                💾 Guardar
            </button>
        </div>
    </div>
</div>

<!-- Botones adicionales -->
<div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <button type="button" class="btn btn-primary w-full" onclick="finalizarServicio()">
        ✅ Finalizar Servicio
    </button>
    <button type="button" class="btn btn-secondary w-full" onclick="coordinarRecojo()">
        📅 Coordinar Recojo
    </button>
    <button type="button" class="btn btn-warning w-full" onclick="fueraDeGarantia()">
        ⚠️ Fuera de Garantía
    </button>
    <button type="button" class="btn btn-info w-full" onclick="pendienteRepuestos()">
        ⏳ Pendiente por Coordinar Repuestos
    </button>
</div>

<!-- Incluir SignaturePad.js -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

<script>
    // Objetos para almacenar las instancias de SignaturePad
    let signaturePads = {
        signatureCanvasTecnico: null,
        signatureCanvasCliente: null
    };

    // Inicializar SignaturePad en un canvas
    function initializeSignature(canvasId) {
        const canvas = document.getElementById(canvasId);
        const container = canvas.parentElement;

        // Crear una instancia de SignaturePad
        signaturePads[canvasId] = new SignaturePad(canvas, {

            penColor: "#000000" // Color de la firma
        });

        // Redimensionar el canvas al tamaño del contenedor
        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            const width = container.offsetWidth;
            const height = container.offsetHeight;

            // Guardar la firma actual
            const data = signaturePads[canvasId].toData();

            // Ajustar el tamaño del canvas
            canvas.width = width * ratio;
            canvas.height = height * ratio;
            canvas.getContext("2d").scale(ratio, ratio);

            // Limpiar y redibujar la firma
            signaturePads[canvasId].clear();
            signaturePads[canvasId].fromData(data);
        }

        // Redimensionar el canvas cuando cambie el tamaño de la ventana
        window.addEventListener("resize", resizeCanvas);
        resizeCanvas(); // Llamar a la función inicialmente
    }

    // Limpiar la firma en un canvas específico
    function clearSignature(canvasId) {
        if (signaturePads[canvasId]) {
            signaturePads[canvasId].clear();
        }
    }

    // Guardar la firma en un canvas específico
    function saveSignature(canvasId, name) {
        if (signaturePads[canvasId]) {
            if (signaturePads[canvasId].isEmpty()) {
                alert("Por favor, realiza la firma primero.");
            } else {
                const signatureData = signaturePads[canvasId].toDataURL(); // Obtener la firma como imagen base64
                console.log(`${name}:`, signatureData);
                alert(`${name} guardada correctamente.`);
            }
        }
    }

    // Funciones para los botones
    function finalizarServicio() {
        alert("Servicio finalizado correctamente.");
    }

    function coordinarRecojo() {
        alert("Recojo coordinado correctamente.");
    }

    function fueraDeGarantia() {
        alert("El servicio está fuera de garantía.");
    }

    function pendienteRepuestos() {
        alert("Pendiente por coordinar repuestos.");
    }

    // Inicializar los canvas cuando el DOM esté listo
    document.addEventListener("DOMContentLoaded", () => {
        initializeSignature("signatureCanvasTecnico");
        initializeSignature("signatureCanvasCliente");
    });
</script>