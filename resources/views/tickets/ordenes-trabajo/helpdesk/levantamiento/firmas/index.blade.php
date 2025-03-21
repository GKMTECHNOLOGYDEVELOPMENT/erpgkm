<span class="text-lg font-semibold mb-4 badge bg-success">Firmas</span>

<!-- Contenedor generaldf -->
<div class="flex flex-col md:flex-row md:justify-center md:space-x-4 space-y-6 md:space-y-0">
    <!-- Firma del Técnico -->
    <div class="w-full md:w-[500px] flex flex-col items-center">
        <p class="mb-2 text-lg font-medium text-center">Firma del Técnico</p>
        <div class="w-full h-[500px] border-2 border-gray-300 rounded-lg relative" style="height: 300px;">
            <canvas id="signatureCanvasTecnico" class="w-full h-full"></canvas>
        </div>
        <div class="flex space-x-3 mt-4">
            <button type="button" onclick="clearSignature('signatureCanvasTecnico')" class="btn btn-danger">
                Limpiar
            </button>
            <button type="button" onclick="saveSignature('signatureCanvasTecnico', 'FirmaTecnico')"
                class="btn btn-success">
                Guardar
            </button>
        </div>
    </div>

    <!-- Firma del Cliente -->
    <div class="w-full md:w-[500px] flex flex-col items-center">
        <p class="mb-2 text-lg font-medium text-center">Firma del Cliente</p>
        <div class="w-full h-[500px] border-2 border-gray-300 rounded-lg relative" style="height: 300px;">
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

<!-- Botones adicionales -->
<div class="mt-10 flex justify-center">
    <button type="button" class="btn btn-primary w-full sm:w-auto" onclick="finalizarServicio()">✅ Finalizar Servicio</button>
</div>


<!-- Incluir SignaturePad.js -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

<script>
    // Objeto para almacenar las instancias de SignaturePad
    let signaturePads = {
        signatureCanvasTecnico: null,
        signatureCanvasCliente: null
    };

    // Inicializar SignaturePad en un canvas
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
        signaturePads[canvasId] = new SignaturePad(canvas, {
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
                const signatureData = signaturePads[canvasId].toDataURL();
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
