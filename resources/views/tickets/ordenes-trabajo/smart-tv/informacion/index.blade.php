<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
<link rel="stylesheet" href="https://unpkg.com/viewerjs/dist/viewer.min.css" />
<script src="https://cdn.jsdelivr.net/npm/compressorjs@1.2.1/dist/compressor.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<span class="text-sm sm:text-lg font-semibold mb-2 sm:mb-4 badge bg-success"
    style="background-color: {{ $colorEstado }};">Detalles de los Estados</span>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 items-start">
    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="md:col-span-1 mt-4">
            <label for="estado" class="block text-sm font-medium">Estado</label>

            <select id="estado" name="estado" class="select2" style="width: 100%;"
                onchange="actualizarColorEstado(this)">

                <option value="" disabled selected>Selecciona una opción</option>

                @foreach ($estadosOTS as $index => $estado)
                    <option value="{{ $estado->idEstadoots }}" data-color="{{ $estado->color }}">
                        {{ $estado->descripcion }}
                    </option>
                @endforeach
            </select>

        </div>

        <div class="md:col-span-2">
            <label for="justificacion" class="block text-sm font-medium">Justificación</label>
            <textarea id="justificacion" name="justificacion" rows="3" class="form-input w-full"></textarea>
        </div>
    </div>

    <div class="col-span-1 md:col-span-2 flex justify-end mt-2">
        @if (\App\Helpers\PermisoHelper::tienePermiso('GUARDAR DETALLES ESTADO ORDEN DE TRABAJO SMART'))
            <button id="guardarEstado" class="btn btn-primary px-6 py-2">Guardar</button>
        @endif
    </div>
</div>

<!-- Sección de Repuestos Solicitados (Aparece solo en estado Solución) -->
<div id="cardRepuestos" class="hidden mt-6 p-5 rounded-lg border border-gray-200">
    <div class="flex justify-between items-center mb-4">
        <span class="text-sm sm:text-lg font-semibold badge bg-success"
            style="background-color: {{ $colorEstado }};">Repuestos Solicitados</span>
        <button id="btnRefrescarRepuestos" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-redo"></i> Actualizar
        </button>
    </div>

    <div class="mt-4">
        <!-- Loading -->
        <div id="loadingRepuestos" class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2 text-gray-600">Cargando repuestos solicitados...</p>
        </div>

        <!-- Contenedor de repuestos con scroll -->
        <div id="repuestosContainer" class="space-y-4 max-h-[500px] overflow-y-auto p-2 hidden">
            <!-- Los repuestos se cargarán aquí dinámicamente -->
        </div>

        <!-- Sin repuestos -->
        <div id="noRepuestos" class="text-center py-8 hidden">
            <div class="text-gray-400 mb-3">
                <i class="fas fa-box-open fa-3x"></i>
            </div>
            <p class="text-gray-600 font-medium">No hay repuestos solicitados</p>
            <p class="text-gray-500 text-sm mt-1">Los repuestos solicitados aparecerán aquí</p>
        </div>

        <!-- Error -->
        <div id="errorRepuestos" class="text-center py-4 text-danger hidden">
            <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
            <p>Error al cargar los repuestos</p>
            <button class="btn btn-sm btn-outline-primary mt-2" onclick="cargarRepuestos()">
                <i class="fas fa-redo"></i> Reintentar
            </button>
        </div>
    </div>
</div>

<div id="cardFotos" class="hidden mt-6 p-5 rounded-lg">
    <span class="text-sm sm:text-lg font-semibold mb-2 sm:mb-4 badge bg-success"
        style="background-color: {{ $colorEstado }};">Fotos</span>

    <div class="flex items-center gap-2 mt-4">
        @if (\App\Helpers\PermisoHelper::tienePermiso('AGREGAR IMAGEN ORDEN DE TRABAJO SMART'))
            <button id="abrirModalAgregarImagen" class="btn btn-primary"
                @click="$dispatch('toggle-modal-agregar-imagen')">
                Agregar Imagen
            </button>
        @endif
        @if (\App\Helpers\PermisoHelper::tienePermiso('ELIMINAR TODAS LAS IMAGENES ORDEN DE TRABAJO SMART'))
            <button id="eliminarTodas" class="btn btn-danger hidden">Eliminar Todas</button>
        @endif
    </div>

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

<!-- Modal para marcar repuesto como usado/no usado - Mismo estilo que Agregar Imagen -->
<div id="modalMarcarRepuesto" class="modal-custom" style="display: none;">
    <div class="fixed inset-0 bg-[black]/60 z-[999] overflow-y-auto">
        <div class="flex items-start justify-center min-h-screen px-4">
            <div
                class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-3xl my-8 animate__animated animate__zoomInUp">
                <!-- Header del Modal con bg-primary y texto blanco -->
                <div class="flex items-center justify-between bg-primary px-5 py-3">
                    <h5 class="font-bold text-lg text-white">Marcar Estado del Repuesto</h5>
                    <button class="text-white hover:text-gray-200 cerrarModalMarcarRepuesto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" class="w-6 h-6">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <!-- Cuerpo del Modal -->
                <div class="p-5 space-y-6 overflow-y-auto max-h-[400px]">
                    <form id="formMarcarRepuesto">
                        <input type="hidden" id="ordenArticuloId" name="ordenArticuloId">
                        <input type="hidden" id="entregaId" name="entregaId">

                        <!-- Repuesto -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Repuesto</label>
                            <input type="text" id="repuestoNombre" class="form-input w-full bg-gray-100 cursor-not-allowed" readonly>
                        </div>

                        <!-- Estado - con mayor separación -->
                        <div class="pt-2">
                            <label class="block text-sm font-medium mb-2">Estado</label>
                            <select id="repuestoEstado" name="repuestoEstado" class="form-select w-full bg-gray-100 cursor-not-allowed" required disabled>
                                <option value="">Seleccionar estado</option>
                                <option value="usado">Usado</option>
                                <option value="devuelto">No Usado (Devuelto)</option>
                            </select>
                        </div>

                        <!-- Cantidad - con mayor separación -->
                        <div class="pt-2">
                            <label class="block text-sm font-medium mb-2">Cantidad</label>
                            <input type="number" id="repuestoCantidad" name="repuestoCantidad"
                                class="form-input w-full bg-gray-100 cursor-not-allowed" min="1" placeholder="Cantidad usada o devuelta" readonly>
                            <small class="text-muted">Dejar vacío para usar cantidad entregada</small>
                        </div>

                        <!-- Observación - con mayor separación -->
                        <div class="pt-2">
                            <label class="block text-sm font-medium mb-2">Observación</label>
                            <textarea id="repuestoObservacion" name="repuestoObservacion" class="form-textarea w-full" rows="3"
                                placeholder="Observaciones sobre el estado del repuesto"></textarea>
                        </div>

                        <!-- Foto de Evidencia -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Foto de Evidencia</label>
                            <input type="file" id="repuestoFoto" name="repuestoFoto"
                                class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold p-0 file:bg-primary/90 ltr:file:mr-5 rtl:file-ml-5 file:text-white file:hover:bg-primary w-full"
                                accept="image/*">
                        </div>

                        <!-- Preview de la foto -->
                        <div id="previewFotoRepuesto" class="mt-2"></div>
                    </form>
                </div>

                <!-- Footer del Modal - Mismo estilo -->
                <div class="flex justify-end px-5 py-3 gap-2">
                    <button type="button" class="btn btn-outline-danger cerrarModalMarcarRepuesto">
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-primary flex items-center gap-2"
                        id="btnGuardarEstadoRepuesto">
                        <span class="label">Guardar</span>
                        <span
                            class="spinner hidden w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar imágenes -->
<div id="modalAgregarImagen" x-data="{ open: false }" x-ref="modal"
    @toggle-modal-agregar-imagen.window="open = !open">
    <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
        <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
            <div x-show="open" x-transition.duration.300
                class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-3xl my-8 animate__animated animate__zoomInUp">
                <!-- Header del Modal -->
                <div class="flex items-center justify-between bg-[#fbfbfb] dark:bg-[#121c2c] px-5 py-3">
                    <h5 class="font-bold text-lg">Agregar Imágenes</h5>

                    <button id="cerrarModal" class="text-gray-600 hover:text-black">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" class="w-6 h-6">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <!-- Cuerpo del Modal -->
                <div class="p-5 space-y-4 overflow-y-auto max-h-[400px]">
                    <form id="formAgregarImagen">
                        <!-- Selección de Imagen -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium mb-2">Seleccionar Imágenes</label>
                            <input type="file" id="imagenInput" accept="image/*" multiple
                                class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold p-0 file:bg-primary/90 ltr:file:mr-5 rtl:file-ml-5 file:text-white file:hover:bg-primary w-full">
                        </div>

                        <!-- Contenedor de imágenes seleccionadas en el modal -->
                        <div id="imagePreviewContainer"
                            class="preview-container mt-4 p-2 border rounded-lg overflow-y-auto max-h-40 flex flex-wrap justify-center gap-2">
                        </div>

                    </form>
                </div>

                <!-- Footer del Modal -->
                <div class="flex justify-end px-5 py-3 gap-2">
                    <button type="button" class="btn btn-outline-danger" @click="open = false">
                        Cancelar
                    </button>
                    @if (\App\Helpers\PermisoHelper::tienePermiso('GUARDAR IMAGEN ORDEN DE TRABAJO SMART'))
                        <button type="submit" class="btn btn-primary flex items-center gap-2" id="guardarImagen">
                            <span class="label">Guardar</span>
                            <span
                                class="spinner hidden w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="https://unpkg.com/viewerjs/dist/viewer.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        /** -------------------------------
         *  1️⃣ Elementos del DOM
         * -------------------------------- */
        const estadoSelect = document.getElementById("estado");
        const cardFotos = document.getElementById("cardFotos");
        const cardRepuestos = document.getElementById("cardRepuestos");
        const abrirModalBtn = document.getElementById("abrirModalAgregarImagen");
        const cerrarModalBtn = document.getElementById("cerrarModal");
        const modalAgregarImagen = document.getElementById("modalAgregarImagen");
        const imagenInput = document.getElementById("imagenInput");
        const imagePreviewContainer = document.getElementById("imagePreviewContainer");
        const swiperWrapper = document.getElementById("swiperWrapper");
        const guardarImagenBtn = document.getElementById("guardarImagen");

        /** -------------------------------
         *  2️⃣ Variables Globales
         * -------------------------------- */
        const ticketId = "{{ $ticket->idTickets }}";
        const visitaId = "{{ $visitaId ?? 'null' }}";
        let imagenesSeleccionadas = [];

        /** -------------------------------
         *  3️⃣ Funciones
         * -------------------------------- */

        // ✅ Muestra u oculta la sección de imágenes dependiendo del estado seleccionado
        function toggleCardFotos() {
            if (estadoSelect.value === "3") { // Solución tiene id 3
                cardFotos.classList.remove("hidden");
                renderizarImagenes();
            } else {
                cardFotos.classList.add("hidden");
            }
        }

        // ✅ Muestra u oculta la sección de repuestos
        function toggleCardRepuestos() {
            if (estadoSelect.value === "3" && visitaId !== 'null') {
                cardRepuestos.classList.remove("hidden");
                cargarRepuestos();
            } else {
                cardRepuestos.classList.add("hidden");
            }
        }

        // ✅ Previsualizar imágenes en orden garantizado
        imagenInput.addEventListener("change", async function() {
            imagePreviewContainer.innerHTML = ""; // Limpiar contenedor
            imagenesSeleccionadas = []; // Reiniciar lista real

            const files = Array.from(imagenInput.files);

            const imagenes = await Promise.all(
                files.map(file => {
                    return new Promise(resolve => {
                        new Compressor(file, {
                            quality: 0.7,
                            maxWidth: 1024,
                            convertTypes: ['image/webp'],
                            success(compressedFile) {
                                const reader = new FileReader();
                                reader.onload = e => resolve({
                                    file: compressedFile,
                                    url: e.target.result
                                });
                                reader.readAsDataURL(compressedFile);
                            },
                            error(err) {
                                console.error("Error al comprimir:", err);
                                resolve({
                                    file,
                                    url: URL.createObjectURL(file)
                                });
                            }
                        });
                    });
                })
            );

            imagenes.forEach((imgData, index) => {
                imagenesSeleccionadas.push(imgData.file); // Guardamos archivo real

                const preview = document.createElement("div");
                preview.classList.add("preview-item", "flex", "flex-col", "items-center",
                    "gap-2", "p-2", "rounded-lg", "shadow");
                preview.setAttribute("data-index", index);

                preview.innerHTML = `
            <div class="relative">
                <img src="${imgData.url}" alt="Imagen ${index + 1}" class="w-20 h-20 object-cover rounded-lg">
                <button type="button"
                    class="absolute top-0 right-0 w-5 h-5 text-white rounded-full text-xs flex items-center justify-center shadow remove-image"
                    style="background-color: #dc2626;" title="Eliminar imagen">
                    &times;
                </button>
            </div>
            <input type="text" placeholder="Descripción de la imagen ${index + 1}" 
                class="descripcion-input form-input w-full text-sm p-1 rounded border border-gray-300">
        `;

                // Eliminar también del array real
                preview.querySelector(".remove-image").addEventListener("click", () => {
                    const i = Array.from(imagePreviewContainer.children).indexOf(
                        preview);
                    if (i > -1) {
                        imagenesSeleccionadas.splice(i, 1);
                    }
                    preview.remove();
                });

                imagePreviewContainer.appendChild(preview);
            });
        });

        window.eliminarImagen = function(imagenId) {
            fetch(`/api/eliminarImagen/${imagenId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        toastr.success("Imagen eliminada correctamente.");
                        renderizarImagenes(); // Refrescar el swiper
                    } else {
                        toastr.error("Error al eliminar la imagen.");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    toastr.error("Hubo un error al eliminar la imagen.");
                });
        };

        // ✅ Renderizar imágenes existentes en el Swiper
        function renderizarImagenes() {
            swiperWrapper.innerHTML = ""; // Limpiar

            fetch(`/api/imagenes/${ticketId}/${visitaId}`)
                .then(response => response.json())
                .then(data => {
                    const btnEliminarTodas = document.getElementById("eliminarTodas");
                    if (data.imagenes && data.imagenes.length > 0) {
                        btnEliminarTodas.classList.remove("hidden");
                        data.imagenes.forEach((img, index) => {
                            let swiperSlide = document.createElement("div");
                            swiperSlide.classList.add("swiper-slide", "relative", "flex",
                                "items-center", "justify-center");

                            swiperSlide.innerHTML = `
                                <div class="w-[350px] h-[250px] flex items-center justify-center bg-gray-100 overflow-hidden rounded-lg relative viewer-item">
                                    <img src="${img.src}" alt="Imagen ${index + 1}" class="w-full h-full object-cover rounded-lg" />
                                    <button onclick="eliminarImagen(${img.id})"
                                        class="absolute top-2 right-2 w-8 h-8 bg-danger hover:bg-red-700 text-white transition-colors duration-200
                                            rounded-full shadow-md flex items-center justify-center z-10">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                    <div class="absolute bottom-0 left-0 w-full bg-black/60 text-white text-center px-3 py-2 text-sm font-medium max-h-[60px] overflow-y-auto rounded-b-lg leading-tight">
                                        <div class="max-h-[60px] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-500 scrollbar-track-gray-300">
                                            ${img.description ? img.description : "Sin descripción"}
                                        </div>
                                    </div>
                                </div>
                            `;

                            swiperWrapper.appendChild(swiperSlide);
                        });

                        setTimeout(() => {
                            swiper5.update();

                            const container = document.getElementById('swiperWrapper');
                            if (window.swiperViewer) {
                                window.swiperViewer.destroy();
                            }
                            window.swiperViewer = new Viewer(container, {
                                navbar: false,
                                toolbar: true,
                                title: false,
                                transition: true,
                                zoomable: true,
                                movable: true,
                                scalable: false,
                                fullscreen: false
                            });
                        }, 100);

                    } else {
                        btnEliminarTodas.classList.add("hidden");
                    }
                })
                .catch(error => {
                    console.error("Error al cargar las imágenes:", error);
                });
        }

        // ✅ Guardar imágenes en la base de datos
        if (guardarImagenBtn) {
            guardarImagenBtn.addEventListener("click", function() {
                if (imagenesSeleccionadas.length === 0) {
                    toastr.error("Debe seleccionar al menos una imagen.");
                    return;
                }

                const label = guardarImagenBtn.querySelector(".label");
                const spinner = guardarImagenBtn.querySelector(".spinner");
                label.textContent = "Guardando...";
                spinner.classList.remove("hidden");
                guardarImagenBtn.disabled = true;

                const formData = new FormData();
                const descripcionInputs = [...imagePreviewContainer.querySelectorAll(".preview-item")];

                imagenesSeleccionadas.forEach((file, index) => {
                    const descripcionInput = descripcionInputs[index]?.querySelector(
                        ".descripcion-input");
                    const descripcion = descripcionInput?.value || "Sin descripción";

                    formData.append("imagenes[]", file, `imagen_${index}.webp`);
                    formData.append("descripciones[]", descripcion);
                });

                formData.append("ticket_id", ticketId);
                formData.append("visita_id", visitaId);

                fetch('/api/guardarImagen', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => {
                                throw new Error(text);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            toastr.success("Imágenes guardadas correctamente.");
                            modalAgregarImagen.classList.add("hidden");
                            imagenInput.value = "";
                            imagePreviewContainer.innerHTML = "";
                            renderizarImagenes();
                        } else {
                            toastr.error("Error al guardar las imágenes.");
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        toastr.error("Hubo un error al guardar las imágenes.");
                    })
                    .finally(() => {
                        label.textContent = "Guardar";
                        spinner.classList.add("hidden");
                        guardarImagenBtn.disabled = false;
                    });
            });

        } else {
            console.error("guardarImagenBtn no encontrado en el DOM");
        }

        document.getElementById("eliminarTodas").addEventListener("click", function() {
            if (!confirm(
                    "¿Estás seguro de eliminar TODAS las imágenes? Esta acción no se puede deshacer."))
                return;

            fetch(`/api/eliminarImagenesMasivo`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        ticket_id: ticketId,
                        visita_id: visitaId
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        toastr.success("Todas las imágenes fueron eliminadas.");
                        renderizarImagenes();
                    } else {
                        toastr.error("Error al eliminar las imágenes.");
                    }
                })
                .catch(err => {
                    console.error(err);
                    toastr.error("Error en la petición.");
                });
        });

        // ✅ Maneja el cambio del select "Estado"
        estadoSelect.addEventListener("change", function() {
            toggleCardFotos();
            toggleCardRepuestos();
        });

        // ✅ Abre el modal de agregar imagen
        abrirModalBtn.addEventListener("click", function() {
            modalAgregarImagen.classList.remove("hidden");
        });

        // ✅ Cierra el modal de agregar imagen
        cerrarModalBtn.addEventListener("click", function() {
            modalAgregarImagen.classList.add("hidden");
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
        renderizarImagenes();

        // ✅ Inicializar funciones de repuestos
        inicializarFuncionesRepuestos();

        // ✅ Inicializar modal de marcar repuesto
        inicializarModalMarcarRepuesto();

        // ✅ Botón refrescar repuestos
        document.getElementById('btnRefrescarRepuestos').addEventListener('click', cargarRepuestos);
    });

    document.getElementById("guardarEstado").addEventListener("click", function() {
        const estadoSelect = document.getElementById("estado");
        const estadoId = estadoSelect.value;
        const justificacion = document.getElementById("justificacion").value;

        // Validar que se haya seleccionado un estado y se haya ingresado una justificación
        if (!estadoId || !justificacion.trim()) {
            toastr.error("Debe seleccionar un estado y escribir una justificación.");
            return;
        }

        // Enviar los datos al servidor
        fetch('/api/guardarEstado', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    idEstadoots: estadoId,
                    justificacion: justificacion.trim(),
                    idTickets: {{ $ticket->idTickets }} // Solo se pasa el ID del ticket
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.success("Estado guardado correctamente.");
                } else {
                    toastr.error("Error al guardar el estado.");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                toastr.error("Hubo un error al guardar el estado.");
            });
    });
</script>

<script>
    $(document).ready(function() {
        // Inicializar Select2
        $('#estado').select2({
            placeholder: "Selecciona una opción",
            width: '100%',
            dropdownParent: $('#estado').parent()
        });

        // ✅ Evento 'change' compatible con Select2
        $('#estado').on('change', function() {
            const estadoId = $(this).val();
            const ticketId = {{ $ticket->idTickets }};
            const visitaId = {{ $visitaId ?? 'null' }};

            // Limpiar la justificación antes de cargar la nueva
            $('#justificacion').val("");

            fetch(
                    `/api/obtenerJustificacion?ticketId=${ticketId}&visitaId=${visitaId}&estadoId=${estadoId}`
                )
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        $('#justificacion').val(data.justificacion || "");
                    } else {
                        toastr.error(data.message || "Error al obtener la justificación");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    toastr.error("Error al obtener la justificación.");
                });

            // Mostrar u ocultar cardFotos y cardRepuestos
            if (estadoId == 3) {
                $('#cardFotos').removeClass('hidden');
                $('#cardRepuestos').removeClass('hidden');
                cargarRepuestos();
            } else {
                $('#cardFotos').addClass('hidden');
                $('#cardRepuestos').addClass('hidden');
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        $('#estado').select2({
            placeholder: "Selecciona una opción",
            width: '100%',
            dropdownParent: $('#estado').parent()
        });
    });
</script>

<script>
    /** -------------------------------
     *  FUNCIONES PARA REPUESTOS SOLICITADOS
     * -------------------------------- */

    // ✅ Función para cargar repuestos (GLOBAL)
    window.cargarRepuestos = function() {
        const ticketId = "{{ $ticket->idTickets }}";
        const visitaId = "{{ $visitaId ?? 'null' }}";

        // Mostrar loading
        document.getElementById('loadingRepuestos').classList.remove('hidden');
        document.getElementById('repuestosContainer').classList.add('hidden');
        document.getElementById('noRepuestos').classList.add('hidden');
        document.getElementById('errorRepuestos').classList.add('hidden');

        fetch(`/api/obtener-solicitudes-repuestos/${ticketId}/${visitaId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('loadingRepuestos').classList.add('hidden');

                if (data.success && data.solicitudes && data.solicitudes.length > 0) {
                    renderizarRepuestos(data.solicitudes);
                    document.getElementById('repuestosContainer').classList.remove('hidden');
                } else {
                    document.getElementById('noRepuestos').classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error cargando repuestos:', error);
                document.getElementById('loadingRepuestos').classList.add('hidden');
                document.getElementById('errorRepuestos').classList.remove('hidden');
            });
    }

function renderizarRepuestos(solicitudes) {
    const container = document.getElementById('repuestosContainer');
    if (!container) {
        console.error('Contenedor de repuestos no encontrado');
        return;
    }

    container.innerHTML = '';

    // Si no hay solicitudes
    if (!solicitudes || solicitudes.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8">
                <div class="text-muted mb-4">
                    <i class="fas fa-box-open fa-4x opacity-25"></i>
                </div>
                <h5 class="text-gray-600 font-medium mb-2">No hay repuestos solicitados</h5>
                <p class="text-gray-500 text-sm">Los repuestos solicitados aparecerán aquí</p>
            </div>
        `;
        return;
    }

    solicitudes.forEach((solicitud, index) => {
        const card = document.createElement('div');
        card.className = 'card border border-gray-200 rounded-lg mb-4';
        card.style.boxShadow = '0 1px 3px 0 rgba(0, 0, 0, 0.05)';
        card.style.transition = 'box-shadow 0.2s ease';

        // Mapeo de colores para estados (con los colores específicos que pediste)
        const coloresEstados = {
            'entregado': 'dark',        // bg-dark
            'pendiente': 'warning',     // bg-warning
            'cedido': 'secondary',      // bg-secondary
            'preparado': 'success',     // bg-success
            'devuelto': 'danger',       // bg-danger
            'usado': 'success',         // bg-success
            'pendiente_por_retorno': 'warning', // bg-warning
            'default': 'secondary'
        };

        const estadoBadge = solicitud.estado_entrega || 'pendiente';
        const estadoColor = coloresEstados[estadoBadge] || coloresEstados.default;

        card.innerHTML = `
            <div class="card-header bg-gray-50 border-b border-gray-200 px-4 py-3">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas fa-file-alt text-gray-500"></i>
                            <h6 class="text-sm font-semibold text-gray-800 m-0">
                                Solicitud: <span class="text-blue-600">${solicitud.codigo || 'N/A'}</span>
                            </h6>
                        </div>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-500">
                            <span class="flex items-center gap-1">
                                <i class="fas fa-calendar text-gray-400"></i>
                                ${solicitud.fechaCreacion_format || 'Sin fecha'}
                            </span>
                            <span class="flex items-center gap-1">
                                <i class="fas fa-user text-gray-400"></i>
                                ${solicitud.tecnico_completo || 'Sin técnico'}
                            </span>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-${estadoColor} text-white border border-${estadoColor}">
                            ${estadoBadge.toUpperCase().replace(/_/g, ' ')}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-4">
                ${solicitud.observaciones ? `
                    <div class="bg-blue-50 border border-blue-100 rounded-lg p-3 mb-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 pt-0.5">
                                <i class="fas fa-sticky-note text-blue-500"></i>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-blue-800 mb-1">Observaciones</p>
                                <p class="text-sm text-blue-700">${solicitud.observaciones}</p>
                            </div>
                        </div>
                    </div>
                ` : ''}
                
                <div class="overflow-hidden rounded-lg border border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Artículo</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado Entrega</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                ${solicitud.articulos && solicitud.articulos.length > 0 
                                    ? solicitud.articulos.map(articulo => {
                                        // Estado de entrega con colores específicos
                                        const entregaEstado = articulo.estado_entrega || 'pendiente';
                                        let entregaColor = 'secondary'; // Color por defecto
                                        
                                        // Asignar colores según tu especificación
                                        if (entregaEstado === 'entregado') {
                                            entregaColor = 'dark';
                                        } else if (entregaEstado === 'pendiente') {
                                            entregaColor = 'warning';
                                        } else if (entregaEstado === 'cedido') {
                                            entregaColor = 'secondary';
                                        } else if (entregaEstado === 'preparado') {
                                            entregaColor = 'success';
                                        } else if (entregaEstado === 'devuelto') {
                                            entregaColor = 'danger';
                                        } else if (entregaEstado === 'usado') {
                                            entregaColor = 'success';
                                        } else if (entregaEstado === 'pendiente_por_retorno') {
                                            entregaColor = 'warning';
                                        }
                                        
                                        // Estado de uso (solo para lógica interna)
                                        let estadoUso = 'Pendiente';
                                        if (articulo.estado_uso) {
                                            estadoUso = articulo.estado_uso;
                                        } else if (entregaEstado === 'usado') {
                                            estadoUso = 'Usado';
                                        } else if (entregaEstado === 'pendiente_por_retorno') {
                                            estadoUso = 'No Usado';
                                        } else if (entregaEstado === 'devuelto') {
                                            estadoUso = 'Devuelto';
                                        }
                                        
                                        // Determinar si mostrar botones (basado en estado de uso)
                                        const mostrarBotones = !(
                                            estadoUso === 'Usado' || 
                                            estadoUso === 'Devuelto' || 
                                            estadoUso === 'No Usado' || 
                                            entregaEstado === 'devuelto'
                                        );
                                        
                                        return `
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-4 py-3">
                                                    <div class="flex flex-col">
                                                        <div class="text-sm font-medium text-gray-900">${articulo.codigo_articulo || 'N/A'}</div>
                                                        <div class="text-xs text-gray-500 truncate max-w-xs">${articulo.nombre_articulo || ''}</div>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-800 text-sm font-semibold">
                                                        ${articulo.cantidad_entregada || 1}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <div class="flex flex-col items-center space-y-1">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-${entregaColor} text-white">
                                                            ${entregaEstado.toUpperCase().replace(/_/g, ' ')}
                                                        </span>
                                                        ${articulo.tiene_foto_retorno ? 
                                                            '<span class="inline-flex items-center text-xs text-blue-600"><i class="fas fa-camera mr-1"></i> Con foto</span>' : 
                                                            ''}
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    ${mostrarBotones ? `
                                                        <div class="flex flex-col sm:flex-row items-center justify-center gap-2">
                                                            <button class="btn btn-sm btn-success btn-marcar-usado" 
                                                                data-id="${articulo.orden_articulo_id}"
                                                                data-entrega-id="${articulo.entrega_id}"
                                                                data-nombre="${articulo.nombre_articulo || 'Artículo'}"
                                                                data-cantidad="${articulo.cantidad_entregada || 1}"
                                                                data-codigo="${articulo.codigo_articulo || ''}">
                                                                <i class="fas fa-check me-1"></i> Usado
                                                            </button>
                                                            <button class="btn btn-sm btn-danger btn-marcar-devuelto" 
                                                                data-id="${articulo.orden_articulo_id}"
                                                                data-entrega-id="${articulo.entrega_id}"
                                                                data-nombre="${articulo.nombre_articulo || 'Artículo'}"
                                                                data-cantidad="${articulo.cantidad_entregada || 1}"
                                                                data-codigo="${articulo.codigo_articulo || ''}">
                                                                <i class="fas fa-times me-1"></i> No Usado
                                                            </button>
                                                        </div>
                                                    ` : `
                                                        <span class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-xs font-medium text-gray-500 bg-gray-50 w-full sm:w-auto justify-center">
                                                            <i class="fas fa-lock mr-1 text-xs"></i> Finalizado
                                                        </span>
                                                    `}
                                                </td>
                                            </tr>
                                        `;
                                    }).join('') 
                                    : `
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center">
                                            <div class="text-gray-400">
                                                <i class="fas fa-inbox text-2xl mb-2"></i>
                                                <p class="text-sm text-gray-500">No hay artículos entregados</p>
                                            </div>
                                        </td>
                                    </tr>
                                    `}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        `;

        // Añadir efecto hover a la tarjeta
        card.addEventListener('mouseenter', function() {
            this.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.boxShadow = '0 1px 3px 0 rgba(0, 0, 0, 0.05)';
        });

        container.appendChild(card);
    });

    // Agregar event listeners a los botones
    agregarEventListenersRepuestos();
}

    // ✅ Función para agregar event listeners
    function agregarEventListenersRepuestos() {
        // Botón Marcar como Usado
        document.querySelectorAll('.btn-marcar-usado').forEach(btn => {
            btn.addEventListener('click', function() {
                const ordenArticuloId = this.getAttribute('data-id');
                const entregaId = this.getAttribute('data-entrega-id');
                const nombre = this.getAttribute('data-nombre');
                const cantidad = this.getAttribute('data-cantidad');
                const codigo = this.getAttribute('data-codigo');

                abrirModalMarcarRepuesto(ordenArticuloId, entregaId, nombre, cantidad, codigo, 'usado');
            });
        });

        // Agregar event listener para el botón "No Usado"
        document.querySelectorAll('.btn-marcar-devuelto').forEach(btn => {
            btn.addEventListener('click', function() {
                const ordenArticuloId = this.getAttribute('data-id');
                const entregaId = this.getAttribute('data-entrega-id');
                const nombre = this.getAttribute('data-nombre');
                const cantidad = this.getAttribute('data-cantidad');
                const codigo = this.getAttribute('data-codigo');

                abrirModalMarcarRepuesto(ordenArticuloId, entregaId, nombre, cantidad, codigo,
                    'devuelto');
            });
        });
    }

    // ✅ Función para inicializar las funciones de repuestos
    function inicializarFuncionesRepuestos() {
        // Verificar que el modal exista en el DOM
        const modalMarcarRepuestoElement = document.getElementById('modalMarcarRepuesto');
        if (!modalMarcarRepuestoElement) {
            console.error('Modal de marcar repuesto no encontrado en el DOM');
            return;
        }

        // Botón refrescar repuestos
        document.getElementById('btnRefrescarRepuestos').addEventListener('click', cargarRepuestos);

        // Verificar si estamos en estado solución inicialmente
        const estadoSelect = document.getElementById('estado');
        if (estadoSelect && estadoSelect.value == 3 && "{{ $visitaId ?? 'null' }}" !== 'null') {
            setTimeout(() => {
                if (typeof cargarRepuestos === 'function') {
                    cargarRepuestos();
                }
            }, 1000);
        }
    }

    /** -------------------------------
     *  FUNCIONES PARA EL MODAL DE MARCAR REPUESTO
     * -------------------------------- */

    // ✅ Función para abrir el modal de marcar repuesto - VERSIÓN MEJORADA
    function abrirModalMarcarRepuesto(ordenArticuloId, entregaId, nombre, cantidad, codigo, estadoSeleccionado =
        'usado') {
        // Obtener elementos del modal
        const ordenArticuloIdInput = document.getElementById('ordenArticuloId');
        const entregaIdInput = document.getElementById('entregaId');
        const repuestoNombreInput = document.getElementById('repuestoNombre');
        const repuestoEstadoSelect = document.getElementById('repuestoEstado');
        const repuestoCantidadInput = document.getElementById('repuestoCantidad');
        const repuestoObservacionInput = document.getElementById('repuestoObservacion');
        const repuestoFotoInput = document.getElementById('repuestoFoto');
        const previewFotoRepuestoDiv = document.getElementById('previewFotoRepuesto');

        if (!ordenArticuloIdInput || !repuestoNombreInput) {
            console.error('Elementos del modal no encontrados');
            toastr.error('Error al abrir el modal');
            return;
        }

        // Limpiar formulario
        if (repuestoFotoInput) repuestoFotoInput.value = '';
        if (previewFotoRepuestoDiv) previewFotoRepuestoDiv.innerHTML = '';

        // Llenar datos
        ordenArticuloIdInput.value = ordenArticuloId;
        if (entregaIdInput) entregaIdInput.value = entregaId || '';
        repuestoNombreInput.value = `${codigo ? codigo + ' - ' : ''}${nombre}`;

        // Configurar estado y campos según la selección
        if (estadoSeleccionado === 'usado') {
            if (repuestoEstadoSelect) {
                repuestoEstadoSelect.value = 'usado';
            }
            if (repuestoCantidadInput) {
                repuestoCantidadInput.value = cantidad || '';
                repuestoCantidadInput.placeholder = cantidad || 'Cantidad usada';
            }
            if (repuestoObservacionInput) {
                repuestoObservacionInput.placeholder = 'Observaciones sobre el uso del repuesto';
            }
        } else if (estadoSeleccionado === 'devuelto') {
            if (repuestoEstadoSelect) {
                repuestoEstadoSelect.value = 'devuelto';
            }
            if (repuestoCantidadInput) {
                repuestoCantidadInput.value = cantidad || '';
                repuestoCantidadInput.placeholder = cantidad || 'Cantidad devuelta';
            }
            if (repuestoObservacionInput) {
                repuestoObservacionInput.placeholder = 'Observaciones sobre la devolución del repuesto';
            }
        }

        if (repuestoObservacionInput) repuestoObservacionInput.value = '';

        // Abrir modal
        const modalElement = document.getElementById('modalMarcarRepuesto');
        if (modalElement) {
            modalElement.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
    }

    // ✅ Función para cerrar el modal de marcar repuesto
    function cerrarModalMarcarRepuesto() {
        const modalElement = document.getElementById('modalMarcarRepuesto');
        if (modalElement) {
            modalElement.style.display = 'none';
            // Restaurar scroll del body
            document.body.style.overflow = '';
        }
    }

    // ✅ Inicializar listeners del modal
    function inicializarModalMarcarRepuesto() {
        // Cerrar modal con el botón X
        document.querySelectorAll('.cerrarModalMarcarRepuesto').forEach(btn => {
            btn.addEventListener('click', cerrarModalMarcarRepuesto);
        });

        // Cerrar modal haciendo clic fuera del contenido
        const modalElement = document.getElementById('modalMarcarRepuesto');
        if (modalElement) {
            const overlay = modalElement.querySelector('.fixed.inset-0');
            if (overlay) {
                overlay.addEventListener('click', function(e) {
                    // Solo cerrar si se hace clic en el overlay (no en el contenido)
                    if (e.target === this) {
                        cerrarModalMarcarRepuesto();
                    }
                });
            }
        }

        // Preview de foto
        const repuestoFotoInput = document.getElementById('repuestoFoto');
        const previewFotoRepuestoDiv = document.getElementById('previewFotoRepuesto');

        if (repuestoFotoInput && previewFotoRepuestoDiv) {
            repuestoFotoInput.addEventListener('change', function() {
                const file = this.files[0];

                if (file) {
                    // Validar tamaño (5MB)
                    if (file.size > 5 * 1024 * 1024) {
                        toastr.error('La imagen no debe superar los 5MB');
                        this.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewFotoRepuestoDiv.innerHTML = `
                        <div class="border rounded p-2 mt-2">
                            <div class="flex items-center">
                                <img src="${e.target.result}" class="w-20 h-20 object-cover rounded-lg me-3">
                                <div class="flex-1">
                                    <div class="font-medium">Vista previa:</div>
                                    <div class="text-sm text-gray-600">${file.name}</div>
                                    <div class="text-xs text-gray-500">${(file.size / 1024).toFixed(2)} KB</div>
                                </div>
                                <button type="button" class="btn btn-sm btn-danger" 
                                    onclick="document.getElementById('previewFotoRepuesto').innerHTML = ''; 
                                             document.getElementById('repuestoFoto').value = '';">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Guardar estado del repuesto
        const btnGuardarEstadoRepuesto = document.getElementById('btnGuardarEstadoRepuesto');
        if (btnGuardarEstadoRepuesto) {
            btnGuardarEstadoRepuesto.addEventListener('click', function() {
                const ordenArticuloIdInput = document.getElementById('ordenArticuloId');
                const entregaIdInput = document.getElementById('entregaId');
                const repuestoEstadoSelect = document.getElementById('repuestoEstado');
                const repuestoObservacionInput = document.getElementById('repuestoObservacion');
                const repuestoCantidadInput = document.getElementById('repuestoCantidad');
                const repuestoFotoInput = document.getElementById('repuestoFoto');

                if (!ordenArticuloIdInput || !repuestoEstadoSelect) {
                    toastr.error('Error: elementos del formulario no encontrados');
                    return;
                }

                const ordenArticuloId = ordenArticuloIdInput.value;
                const entregaId = entregaIdInput ? entregaIdInput.value : '';
                const estado = repuestoEstadoSelect.value;
                const observacion = repuestoObservacionInput ? repuestoObservacionInput.value : '';
                const cantidad = repuestoCantidadInput ? repuestoCantidadInput.value : '';

                if (!estado) {
                    toastr.error('Debe seleccionar un estado');
                    return;
                }

                if (!ordenArticuloId) {
                    toastr.error('ID del artículo no válido');
                    return;
                }

                const formData = new FormData();
                // Cambié a la nueva estructura que espera el endpoint
                formData.append('items[0][repuesto_entrega_id]', entregaId);
                formData.append('items[0][estado]', estado);

                if (observacion) {
                    formData.append('items[0][observacion]', observacion);
                }

                if (cantidad && cantidad > 0) {
                    formData.append('items[0][cantidad]', cantidad);
                }

                // Agregar foto según el estado
                if (repuestoFotoInput && repuestoFotoInput.files.length > 0) {
                    if (estado === 'usado') {
                        formData.append('foto_usado', repuestoFotoInput.files[0]);
                    } else if (estado === 'pendiente_por_retorno') {
                        formData.append('foto_no_usado', repuestoFotoInput.files[0]);
                    }
                }

                // Deshabilitar botón y mostrar spinner
                const btn = this;
                const originalHtml = btn.innerHTML;
                btn.disabled = true;
                btn.querySelector('.label').textContent = 'Guardando...';
                btn.querySelector('.spinner').classList.remove('hidden');

                // ✅ CAMBIO DE RUTA AQUÍ
                fetch('/api/actualizar-datos-solicitud', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            toastr.success(data.message || 'Estado del repuesto actualizado correctamente');

                            // Cerrar modal
                            cerrarModalMarcarRepuesto();

                            // Limpiar formulario
                            const formMarcarRepuesto = document.getElementById('formMarcarRepuesto');
                            if (formMarcarRepuesto) formMarcarRepuesto.reset();

                            const previewFotoRepuestoDiv = document.getElementById('previewFotoRepuesto');
                            if (previewFotoRepuestoDiv) previewFotoRepuestoDiv.innerHTML = '';

                            // Recargar repuestos después de 1 segundo
                            setTimeout(() => {
                                if (typeof cargarRepuestos === 'function') {
                                    cargarRepuestos();
                                }
                            }, 1000);
                        } else {
                            toastr.error(data.message || 'Error al guardar el estado');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        toastr.error('Error de conexión al guardar el estado');
                    })
                    .finally(() => {
                        btn.disabled = false;
                        btn.querySelector('.label').textContent = 'Guardar';
                        btn.querySelector('.spinner').classList.add('hidden');
                    });
            });
        }
    }

    // ✅ Función para actualizar color del estado
    function actualizarColorEstado(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const color = selectedOption.getAttribute('data-color');

        if (color) {
            const badges = document.querySelectorAll('.badge.bg-success');
            badges.forEach(badge => {
                badge.style.backgroundColor = color;
            });
        }
    }

    // ✅ Inicializar cuando cargue la página
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar funciones de repuestos
        if (typeof inicializarFuncionesRepuestos === 'function') {
            inicializarFuncionesRepuestos();
        }

        // Inicializar el modal de marcar repuesto
        if (typeof inicializarModalMarcarRepuesto === 'function') {
            inicializarModalMarcarRepuesto();
        }

        // Verificar si estamos en estado solución inicialmente
        const estadoSelect = document.getElementById('estado');
        if (estadoSelect && estadoSelect.value == 3 && "{{ $visitaId ?? 'null' }}" !== 'null') {
            setTimeout(() => {
                if (typeof cargarRepuestos === 'function') {
                    cargarRepuestos();
                }
            }, 1000);
        }
    });
</script>

<style>
    /* Estilos adicionales para el modal personalizado */
    .modal-custom {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
    }

    .modal-custom .fixed {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .modal-custom .panel {
        background-color: white;
        border-radius: 0.5rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        max-width: 768px;
        width: 100%;
        max-height: 90vh;
        overflow: hidden;
    }

    /* Animación para el modal */
    .animate__zoomInUp {
        animation-duration: 0.3s;
    }
</style>
