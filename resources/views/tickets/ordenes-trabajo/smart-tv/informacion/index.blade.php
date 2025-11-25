<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
<link rel="stylesheet" href="https://unpkg.com/viewerjs/dist/viewer.min.css" />
<script src="https://cdn.jsdelivr.net/npm/compressorjs@1.2.1/dist/compressor.min.js"></script>
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
        @if(\App\Helpers\PermisoHelper::tienePermiso('GUARDAR DETALLES ESTADO ORDEN DE TRABAJO SMART'))
        <button id="guardarEstado" class="btn btn-primary px-6 py-2">Guardar</button>
        @endif
    </div>
</div>


<div id="cardFotos" class="hidden mt-6 p-5 rounded-lg">
    <span class="text-sm sm:text-lg font-semibold mb-2 sm:mb-4 badge bg-success"
        style="background-color: {{ $colorEstado }};">Fotos</span>

    <div class="flex items-center gap-2 mt-4">
        @if(\App\Helpers\PermisoHelper::tienePermiso('AGREGAR IMAGEN ORDEN DE TRABAJO SMART'))
        <button id="abrirModalAgregarImagen" class="btn btn-primary" @click="$dispatch('toggle-modal-agregar-imagen')">
            Agregar Imagen
        </button>
        @endif
        @if(\App\Helpers\PermisoHelper::tienePermiso('ELIMINAR TODAS LAS IMAGENES ORDEN DE TRABAJO SMART'))
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



<!-- Modal para agregar imágenes -->
<div id="modalAgregarImagen" x-data="{ open: false }" x-ref="modal" @toggle-modal-agregar-imagen.window="open = !open">
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
                    @if(\App\Helpers\PermisoHelper::tienePermiso('GUARDAR IMAGEN ORDEN DE TRABAJO SMART'))
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
            if (estadoSelect.value === "Solución") {
                cardFotos.classList.remove("hidden");
                renderizarImagenes();
            } else {
                cardFotos.classList.add("hidden");
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
        estadoSelect.addEventListener("change", toggleCardFotos);

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
    });
</script>

<script>
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

            // Mostrar u ocultar cardFotos
            if (estadoId == 3) {
                $('#cardFotos').removeClass('hidden');
            } else {
                $('#cardFotos').addClass('hidden');
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
