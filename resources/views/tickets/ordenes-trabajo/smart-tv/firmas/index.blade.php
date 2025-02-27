<span class="text-lg font-semibold mb-4 badge bg-success">Firmas</span>

<!-- Contenedor general -->
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
            <button type="button" onclick="saveSignature('signatureCanvasTecnico', 'FirmaTecnico')" class="btn btn-success">
                Guardar
            </button>
        </div>
    </div>

    <!-- Firma del Cliente -->
    <div class="w-full md:w-[500px] flex flex-col items-center">
        <p class="mb-2 text-lg font-medium text-center">Firma del Cliente</p>
        <!-- Aquí reemplazamos el canvas por una imagen -->
        <div class="w-full h-[500px] border-2 border-gray-300 rounded-lg relative" style="height: 300px;">
            <img id="firmaClienteImg" class="w-full h-full object-contain" src="" alt="Firma del Cliente">
        </div>
    </div>
</div>

<input type="hidden" id="ticketId" value="{{ $id }}">

<!-- Botones adicionales -->
<div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <button type="button" class="btn btn-primary w-full" onclick="finalizarServicio()">✅ Finalizar Servicio</button>
    <button type="button" class="btn btn-secondary w-full" onclick="coordinarRecojo()">📅 Coordinar Recojo</button>
    <button type="button" class="btn btn-warning w-full" onclick="fueraDeGarantia()">⚠️ Fuera de Garantía</button>
    <button type="button" class="btn btn-info w-full" onclick="pendienteRepuestos()">⏳ Pendiente por Coordinar Repuestos</button>
</div>

<!-- Incluir SignaturePad.js -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

<script>
    // Objeto para almacenar las instancias de SignaturePad
    let signaturePads = {
        signatureCanvasTecnico: null
    };

    // Inicializar SignaturePad en un canvas
    function initializeSignature(canvasId, callback) {
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

        // Ejecutar el callback si está definido
        if (callback) callback();
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

    // Función para cargar la firma del cliente
    function cargarFirmaCliente() {
        const ticketId = document.getElementById('ticketId').value; // Obtener el id del ticket
        console.log("Obteniendo firma para el ticket ID:", ticketId); // Log para verificar el ticketId

        // Obtener la firma del cliente desde el servidor
        fetch(`/ordenes/smart/${ticketId}/obtener-firma-cliente`)
            .then(response => {
                console.log("Respuesta recibida:", response); // Log para verificar la respuesta
                return response.json();
            })
            .then(data => {
                console.log("Firma recibida:", data); // Verificar la respuesta completa
                if (data.firma) {
                    const base64Data = data.firma.replace(/^data:image\/\w+;base64,/, '');
                    console.log("Firma en base64 sin prefijo:", base64Data); // Verificar la cadena base64 sin el prefijo

                    // Verificar si la firma base64 es válida (longitud)
                    if (base64Data.length > 100) {
                        const imgElement = document.getElementById('firmaClienteImg');
                        imgElement.src = `data:image/png;base64,${base64Data}`;
                        console.log("Firma cargada en la imagen.");
                    } else {
                        console.log("La firma Base64 está vacía o es inválida.");
                    }
                } else {
                    console.log("No se encontró la firma.");
                }
            })
            .catch(error => {
                console.error('Error al cargar la firma:', error);
            });
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
        cargarFirmaCliente(); // Cargar la firma del cliente después de inicializar

         // Recargar la firma del cliente cada 5 segundos
         setInterval(cargarFirmaCliente, 55000);
    });
</script>
