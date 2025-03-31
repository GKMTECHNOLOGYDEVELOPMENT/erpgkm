<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">


<!-- Sección de Fotos (se mantiene igual) -->
<div id="cardFotos" class="mt-6 p-5 rounded-lg shadow-md">
    <span class="text-sm sm:text-lg font-semibold mb-2 sm:mb-4 badge" style="background-color: {{ $colorEstado }};">Fotos</span>

    <!-- Botón para abrir el modal -->
    <!-- Botón para abrir el modal -->
    <button id="abrirModalAgregarImagen" class="btn btn-primary mt-4" @click="$dispatch('toggle-modal-agregar-imagen')">
        Agregar Imagen
    </button>
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
                class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8 animate__animated animate__zoomInUp">

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
                            class="preview-container mt-4 p-2 border rounded-lg overflow-y-auto max-h-40 flex flex-wrap gap-2">
                        </div>

                    </form>
                </div>

                <!-- Footer del Modal -->
                <div class="flex justify-end px-5 py-3 gap-2">
                    <button type="button" class="btn btn-outline-danger" @click="open = false">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" id="guardarImagen">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>





<!-- Incluir SignaturePad.js -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>


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

        // ✅ Previsualizar imágenes en el modal al seleccionar archivos
        imagenInput.addEventListener("change", function() {
            imagePreviewContainer.innerHTML =
                ""; // Limpiar el contenedor antes de agregar nuevas imágenes

            Array.from(imagenInput.files).forEach((file, index) => {
                const reader = new FileReader();

                reader.onload = function(event) {
                    const imageUrl = event.target.result;

                    let preview = document.createElement("div");
                    preview.classList.add("preview-item", "flex", "flex-col",
                        "items-center", "gap-2", "p-2", "rounded-lg", "shadow");

                    preview.innerHTML = `
                    <img src="${imageUrl}" alt="Imagen ${index + 1}" class="w-20 h-20 object-cover rounded-lg">
                    <input type="text" placeholder="Descripción de la imagen ${index + 1}" 
                        class="descripcion-input form-input w-full text-sm p-1 rounded border border-gray-300">
                `;

                    imagePreviewContainer.appendChild(preview);
                };

                reader.readAsDataURL(file);
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
            swiperWrapper.innerHTML = ""; // Limpiar el swiper antes de agregar nuevas imágenes

            fetch(`/api/imagenes/${ticketId}/${visitaId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.imagenes) {
                        data.imagenes.forEach((img, index) => {
                            let swiperSlide = document.createElement("div");
                            swiperSlide.classList.add("swiper-slide", "relative", "flex",
                                "items-center", "justify-center");

                            swiperSlide.innerHTML = `
                                <div class="w-[350px] h-[250px] flex items-center justify-center bg-gray-100 overflow-hidden rounded-lg relative">
                                    <img src="${img.src}" alt="Imagen ${index + 1}" class="w-full h-full object-cover rounded-lg" />

                                    <!-- Botón "X" para eliminar -->
                                    <button onclick="eliminarImagen(${img.id})"
                                        class="absolute top-2 right-2 w-8 h-8 bg-danger hover:bg-red-700 text-white transition-colors duration-200
                                            rounded-full shadow-md flex items-center justify-center z-10">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>



                                    <!-- Descripción -->
                                    <div class="absolute bottom-0 left-0 w-full bg-black/60 text-white text-center px-3 py-2 text-sm font-medium 
                                                max-h-[60px] overflow-y-auto rounded-b-lg leading-tight">
                                        <div class="max-h-[60px] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-500 scrollbar-track-gray-300">
                                            ${img.description ? img.description : "Sin descripción"}
                                        </div>
                                    </div>
                                </div>
                            `;

                            swiperWrapper.appendChild(swiperSlide);
                        });

                        // 🔹 Asegurar que el Swiper se actualiza correctamente
                        setTimeout(() => swiper5.update(), 100);
                    }
                })
                .catch(error => {
                    console.error("Error al cargar las imágenes:", error);
                });
        }

        // ✅ Guardar imágenes en la base de datos
        if (guardarImagenBtn) {
            guardarImagenBtn.addEventListener("click", function() {
                if (imagenInput.files.length === 0) {
                    toastr.error("Debe seleccionar al menos una imagen.");
                    return;
                }

                const formData = new FormData();

                Array.from(imagenInput.files).forEach((file, index) => {
                    const descripcion = imagePreviewContainer.children[index]?.querySelector(
                        ".descripcion-input").value || "Sin descripción";
                    formData.append("imagenes[]", file);
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

                            // 🔹 Llamar a renderizarImagenes() para actualizar el Swiper
                            renderizarImagenes();
                        } else {
                            toastr.error("Error al guardar las imágenes.");
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        toastr.error("Hubo un error al guardar las imágenes.");
                    });
            });
        } else {
            console.error("guardarImagenBtn no encontrado en el DOM");
        }

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
