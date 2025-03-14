<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

<!-- Sección de Fotos (se mantiene igual) -->
<div id="cardFotos" class="mt-6 p-5 rounded-lg shadow-md">
    <span class="text-lg font-semibold mb-4 badge bg-success">Fotos</span>

    <!-- Botón para abrir el modal -->
    <button id="abrirModalAgregarImagen" class="btn btn-primary mt-4">Agregar Imagen</button>

    <!-- Swiper Container -->
    <div class="swiper w-full max-w-4x2 h-80 rounded-lg overflow-hidden mt-4" id="slider5">
        <div class="swiper-wrapper" id="swiperWrapper">
            <!-- Las imágenes se agregarán dinámicamente aquí -->
        </div>

        <!-- Botón Anterior -->
        <a href="javascript:;"
            class="swiper-button-prev-ex5 absolute top-1/2 -translate-y-1/2 left-2 z-50 bg-white p-2 rounded-full shadow-md hover:bg-gray-200">
            <svg class="w-6 h-6 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>

        <!-- Botón Siguiente -->
        <a href="javascript:;"
            class="swiper-button-next-ex5 absolute top-1/2 -translate-y-1/2 right-2 z-50 bg-white p-2 rounded-full shadow-md hover:bg-gray-200">
            <svg class="w-6 h-6 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>

        <!-- Paginación -->
        <div class="swiper-pagination"></div>
    </div>
</div>

<!-- Modal para agregar imagen (se mantiene igual) -->
<div id="modalAgregarImagen" class="hidden fixed inset-0 bg-[black]/60 z-[999] flex justify-center items-center">
    <div class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8 animate__animated animate__zoomInUp">
        <!-- Header del Modal -->
        <div class="flex items-center justify-between bg-[#fbfbfb] dark:bg-[#121c2c] px-5 py-3">
            <h5 class="font-bold text-lg">Agregar Imágenes</h5>
            <button id="cerrarModal" class="text-gray-600 hover:text-black">
                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="w-6 h-6">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <!-- Cuerpo del Modal -->
        <div class="p-5 space-y-4 overflow-y-auto max-h-[400px]">
            <form id="formAgregarImagen">
                <!-- Descripción -->
                <div>
                    <label class="block text-sm font-medium">Descripción</label>
                    <input type="text" id="descripcionImagen" class="form-input w-full" required>
                </div>

                <!-- Selección de Imagen -->
                <div class="mt-4">
                    <label class="block text-sm font-medium mb-2">Seleccionar Imagen</label>
                    <input type="file" id="imagenInput" accept="image/*"
                        class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold p-0 file:bg-primary/90 ltr:file:mr-5 rtl:file-ml-5 file:text-white file:hover:bg-primary w-full"
                        required>
                </div>

                <!-- Botón Agregar -->
                <div class="flex justify-end mt-4">
                    <button type="button" class="btn btn-outline-secondary" id="agregarImagen">Agregar</button>
                </div>

                <!-- Contenedor de imágenes seleccionadas en el modal -->
                <div id="imagePreviewContainer"
                    class="preview-container mt-4 p-2 border rounded-lg overflow-y-auto max-h-40">
                </div>
            </form>
        </div>

        <!-- Footer del Modal -->
        <div class="flex justify-end px-5 py-3">
            <button type="button" class="btn btn-outline-danger mr-2" id="cerrarModal">Cancelar</button>
            <button type="submit" class="btn btn-primary" id="guardarImagen">Guardar</button>
        </div>
    </div>
</div>


<!-- Contenedor Principal de Firmas -->
<div id="cardFirmas" class="mt-6 p-5 rounded-lg shadow-md">
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
</div>


<!-- Botones adicionales -->
<div class="mt-10 flex justify-center">
    <button type="button" class="btn btn-primary w-full sm:w-auto" onclick="finalizarServicio()">✅ Finalizar
        Servicio</button>
</div>


<!-- Incluir SignaturePad.js -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
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

    /** -------------------------------
     *  1️⃣ Elementos del DOM
     * -------------------------------- */
    const cardFotos = document.getElementById("cardFotos");
    const abrirModalBtn = document.getElementById("abrirModalAgregarImagen");
    const cerrarModalBtn = document.getElementById("cerrarModal");
    const formAgregarImagen = document.getElementById("formAgregarImagen");
    const modalAgregarImagen = document.getElementById("modalAgregarImagen");
    const imagenInput = document.getElementById("imagenInput");
    const descripcionInput = document.getElementById("descripcionImagen");
    const imagePreviewContainer = document.getElementById("imagePreviewContainer");
    const swiperWrapper = document.getElementById("swiperWrapper");
    const agregarImagenBtn = document.getElementById("agregarImagen");
    const guardarImagenBtn = document.getElementById("guardarImagen");

    /** -------------------------------
     *  2️⃣ Variables Globales
     * -------------------------------- */
    let imagenes = JSON.parse(localStorage.getItem("imagenesSolucion")) || [];

    /** -------------------------------
     *  3️⃣ Funciones
     * -------------------------------- */

    // ✅ Renderiza las imágenes seleccionadas en el modal antes de guardarlas
    function renderizarPrevisualizacion() {
        imagePreviewContainer.innerHTML = "";

        if (imagenes.length === 0) {
            imagePreviewContainer.classList.add("hidden");
            return;
        }

        imagePreviewContainer.classList.remove("hidden");

        imagenes.forEach((img, index) => {
            let preview = document.createElement("div");
            preview.classList.add("preview-item", "flex", "flex-col", "items-center", "gap-2",
                "p-2", "rounded-lg", "shadow");

            preview.innerHTML = `
                <img src="${img.src}" alt="Imagen ${index + 1}" class="w-20 h-20 object-cover rounded-lg">
                <span class="text-xs font-semibold text-gray-700 text-center">${img.description}</span>
                <button onclick="eliminarImagen(${index})" class="btn btn-danger text-white px-2 py-1 text-xs rounded">
                    Eliminar
                </button>
            `;

            imagePreviewContainer.appendChild(preview);
        });
    }

    // ✅ Renderiza imágenes en el Swiper
    function renderizarImagenes() {
        swiperWrapper.innerHTML = "";

        imagenes.forEach((img, index) => {
            let swiperSlide = document.createElement("div");
            swiperSlide.classList.add("swiper-slide", "relative", "flex", "items-center",
                "justify-center");

            swiperSlide.innerHTML = `
                <div class="w-[350px] h-[250px] flex items-center justify-center bg-gray-100 overflow-hidden rounded-lg relative">
                    <img src="${img.src}" alt="${img.description}" class="w-full h-full object-cover rounded-lg" />
                    
                    <!-- Descripción con fondo translúcido, SCROLL, y tamaño limitado -->
                    <div class="absolute bottom-0 left-0 w-full bg-black/60 text-white text-center px-3 py-2 text-sm font-medium 
                                max-h-[60px] overflow-y-auto rounded-b-lg leading-tight">
                        <div class="max-h-[60px] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-500 scrollbar-track-gray-300">
                            ${img.description ? img.description : "Sin descripción"}
                        </div>
                    </div>

                    <!-- Botón para eliminar la imagen -->
                    <button onclick="eliminarImagen(${index})" class="absolute top-2 right-2 btn btn-danger text-white p-1 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            `;
            swiperWrapper.appendChild(swiperSlide);
        });

        swiper5.update();
    }

    // ✅ Elimina una imagen (tanto del modal como del Swiper)
    window.eliminarImagen = function(index) {
        imagenes.splice(index, 1);
        localStorage.setItem("imagenesSolucion", JSON.stringify(imagenes));
        renderizarImagenes();
        renderizarPrevisualizacion();
    };

    /** -------------------------------
     *  4️⃣ Eventos
     * -------------------------------- */

    // ✅ Abre el modal de agregar imagen
    abrirModalBtn.addEventListener("click", function() {
        modalAgregarImagen.classList.remove("hidden");
        renderizarPrevisualizacion(); // Mostrar imágenes al abrir el modal
    });

    // ✅ Cierra el modal de agregar imagen
    cerrarModalBtn.addEventListener("click", function() {
        modalAgregarImagen.classList.add("hidden");
        imagenInput.value = "";
        descripcionInput.value = "";
    });

    // ✅ Agrega una imagen a la previsualización antes de guardar
    agregarImagenBtn.addEventListener("click", function() {
        const file = imagenInput.files[0];
        const descripcion = descripcionInput.value.trim();

        if (!file || descripcion === "") {
            alert("Debe seleccionar una imagen y escribir una descripción.");
            return;
        }

        const reader = new FileReader();
        reader.onload = function(event) {
            imagenes.push({
                src: event.target.result,
                description: descripcion
            });

            localStorage.setItem("imagenesSolucion", JSON.stringify(imagenes));

            renderizarPrevisualizacion(); // Actualiza la previsualización del modal
            renderizarImagenes(); // Actualiza el Swiper
        };

        reader.readAsDataURL(file);

        imagenInput.value = "";
        descripcionInput.value = "";
    });

    // ✅ Guarda las imágenes en localStorage y las muestra en el Swiper
    guardarImagenBtn.addEventListener("click", function() {
        modalAgregarImagen.classList.add("hidden");
        imagenInput.value = "";
        descripcionInput.value = "";
    });

    /** -------------------------------
     *  5️⃣ Inicializar Swiper
     * -------------------------------- */

    // ✅ Inicializar Swiper
    const swiper5 = new Swiper("#slider5", {
        navigation: {
            nextEl: ".swiper-button-next-ex5",
            prevEl: ".swiper-button-prev-ex5",
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            1024: {
                slidesPerView: 3,
                spaceBetween: 30
            },
            768: {
                slidesPerView: 2,
                spaceBetween: 40
            },
            320: {
                slidesPerView: 1,
                spaceBetween: 20
            },
        },
    });

    // ✅ Renderizar imágenes al cargar la página
    document.addEventListener("DOMContentLoaded", function() {
        renderizarImagenes();
    });
</script>
