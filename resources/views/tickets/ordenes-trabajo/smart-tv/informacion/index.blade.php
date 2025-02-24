<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">

<span class="text-lg font-semibold mb-4 badge bg-success">Detalles de los Estados</span>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 items-start">
    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="md:col-span-1 mt-4">
            <label for="estado" class="block text-sm font-medium">Estado</label>
            <select id="estado" name="estado" class="selectize" onchange="actualizarColorEstado(this)" style="display: none">
                @foreach ($estadosOTS as $index => $estado)
                    <option value="{{ $estado->idEstadoots }}" data-color="{{ $estado->color }}" {{ $index == 0 ? 'selected' : '' }}>
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
        <button id="guardarEstado" class="btn btn-primary px-6 py-2">Guardar</button>
    </div>
</div>


<style>
    .hidden {
        display: none;
    }
</style>


<div id="cardFotos" class="hidden mt-6 p-5 rounded-lg">
    <span class="text-lg font-semibold mb-4 badge bg-success">Fotos</span>

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



<!-- Modal para agregar imagen -->
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
                        <!-- Descripción -->
                        <div>
                            <label class="block text-sm font-medium">Descripción</label>
                            <input type="text" id="descripcionImagen" class="form-input w-full">
                        </div>

                        <!-- Selección de Imagen -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium mb-2">Seleccionar Imagen</label>
                            <input type="file" id="imagenInput" accept="image/*"
                                class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold p-0 file:bg-primary/90 ltr:file:mr-5 rtl:file-ml-5 file:text-white file:hover:bg-primary w-full">
                        </div>

                        <!-- Botón Agregar -->
                        <div class="flex justify-end mt-4 gap-2">

                            <button type="button" class="btn btn-secondary" id="agregarImagen">Agregar</button>
                        </div>

                        <!-- Contenedor de imágenes seleccionadas en el modal -->
                        <div id="imagePreviewContainer"
                            class="preview-container mt-4 p-2 border rounded-lg overflow-y-auto max-h-40">
                        </div>

                    </form>
                </div>

                <!-- Footer del Modal -->
                <div class="flex justify-end px-5 py-3 gap-2">
                    <button type="button" class="btn btn-outline-danger" @click="open = false">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" id="guardarImagen">Reset</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        /** -------------------------------
         *  1️⃣ Elementos del DOM
         * -------------------------------- */
        const estadoSelect = document.getElementById("estado");
        const guardarEstadoBtn = document.getElementById("guardarEstado");
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



        // ✅ Muestra u oculta la sección de imágenes dependiendo del estado seleccionado
        function toggleCardFotos() {
            if (estadoSelect.value === "Solución") {
                cardFotos.classList.remove("hidden");
                renderizarImagenes();
            } else {
                cardFotos.classList.add("hidden");
            }
        }
        // ✅ Renderiza las imágenes seleccionadas en el modal antes de guardarlas
        function renderizarPrevisualizacion() {
            imagePreviewContainer.innerHTML = ""; // Limpiar el contenedor de previsualización

            // Obtener las imágenes desde la base de datos
            const ticketId = {{ $ticket->idTickets }};
            const visitaId = {{ $visitaId ?? 'null' }};

            fetch(`/api/imagenes/${ticketId}/${visitaId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.imagenes && data.imagenes.length > 0) {
                        imagePreviewContainer.classList.remove("hidden"); // Mostrar el contenedor

                        data.imagenes.forEach((img, index) => {
                            let preview = document.createElement("div");
                            preview.classList.add("preview-item", "flex", "flex-col",
                                "items-center", "gap-2",
                                "p-2", "rounded-lg", "shadow");

                            // Asegúrate de que la propiedad `src` esté correctamente definida
                            const imagenSrc = img.src ||
                                'ruta_por_defecto_si_no_hay_imagen'; // Puedes agregar una ruta por defecto si no hay imagen

                            preview.innerHTML = `
                        <img src="${imagenSrc}" alt="Imagen ${index + 1}" class="w-20 h-20 object-cover rounded-lg">
                        <span class="text-xs font-semibold text-gray-700 text-center">${img.description ? img.description : "Sin descripción"}</span>
                       <button onclick="eliminarImagen(${index}, event)" class="btn btn-danger text-white px-2 py-1 text-xs rounded">
                            Eliminar
                        </button>
                    `;

                            imagePreviewContainer.appendChild(
                                preview); // Agregar la previsualización al contenedor
                        });
                    } else {
                        imagePreviewContainer.classList.add("hidden"); // Ocultar si no hay imágenes
                    }
                })
                .catch(error => {
                    console.error("Error al cargar las imágenes:", error);
                    imagePreviewContainer.classList.add("hidden"); // Ocultar en caso de error
                });
        }







        // ✅ Renderiza las imágenes en el Swiper
        function renderizarImagenes() {
            swiperWrapper.innerHTML = ""; // Limpiar el swiper antes de agregar nuevas imágenes

            // Obtener las imágenes desde la base de datos
            const ticketId = {{ $ticket->idTickets }};
            const visitaId = {{ $visitaId ?? 'null' }};

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

                        swiper5.update(); // Actualizar el swiper para que se muestren las nuevas imágenes
                    }
                })
                .catch(error => {
                    console.error("Error al cargar las imágenes:", error);
                });
        }





        // ✅ Elimina una imagen (tanto del modal como del Swiper)
        window.eliminarImagen = function(index) {
            // Obtener el ID del ticket y la visita
            const ticketId = {{ $ticket->idTickets }};
            const visitaId = {{ $visitaId ?? 'null' }};

            // Obtener las imágenes desde la API
            fetch(`/api/imagenes/${ticketId}/${visitaId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.imagenes && data.imagenes[index]) {
                        const imagenId = data.imagenes[index].id; // Obtener el ID de la imagen

                        // Enviar solicitud para eliminar la imagen en la base de datos
                        fetch(`/api/eliminarImagen/${imagenId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Recargar las imágenes después de eliminar
                                    renderizarImagenes(); // Actualizar el Swiper
                                    renderizarPrevisualizacion(); // Actualizar el modal
                                    toastr.success("Imagen eliminada correctamente.");
                                } else {
                                    toastr.error("Error al eliminar la imagen.");
                                }
                            })
                            .catch(error => {
                                console.error("Error:", error);
                                toastr.error("Hubo un error al eliminar la imagen.");
                            });
                    } else {
                        toastr.error("La imagen no existe o no se pudo encontrar.");
                    }
                })
                .catch(error => {
                    console.error("Error al cargar las imágenes:", error);
                    toastr.error("Hubo un error al cargar las imágenes.");
                });
        };

        /** -------------------------------
         *  4️⃣ Eventos
         * -------------------------------- */

        // ✅ Maneja el cambio del select "Estado"
        estadoSelect.addEventListener("change", toggleCardFotos);

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

        // ✅ Agregar imagen y enviarla al servidor para guardar en la base de datos
        agregarImagenBtn.addEventListener("click", function() {
            const file = imagenInput.files[0];
            const descripcion = descripcionInput.value.trim();

            if (!file || descripcion === "") {
                toastr.error("Debe seleccionar una imagen y escribir una descripción.");
                return;
            }

            const formData = new FormData(); // Usamos FormData para enviar archivos
            formData.append("imagen", file); // Agregar el archivo
            formData.append("descripcion", descripcion); // Agregar la descripción
            formData.append("ticket_id", {{ $ticket->idTickets }}); // ID del ticket
            formData.append("visita_id", {{ $visitaId ?? 'null' }}); // ID de la visita (si existe)

            // Log para verificar que los datos se están agregando al FormData
            console.log("Archivo seleccionado:", file);
            console.log("Descripción:", descripcion);
            console.log("Ticket ID:", {{ $ticket->idTickets }});
            console.log("Visita ID:", {{ $visitaId ?? 'null' }});

            // Enviar la imagen al servidor usando AJAX
            fetch('/api/guardarImagen', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData // Pasamos los datos como FormData
                })
                .then(response => response.json())
                .then(data => {
                    console.log("Respuesta del servidor:",
                        data); // Log para ver la respuesta del servidor
                    if (data.success) {
                        toastr.success("Imagen guardada correctamente.");
                        renderizarImagenes(); // Actualizar la vista de imágenes
                        renderizarPrevisualizacion(); // Actualizar la vista de imágenes en el modal

                    } else {
                        toastr.error("Error al guardar la imagen.");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    toastr.error("Hubo un error al guardar la imagen.");
                });

            // Limpiar los campos después de enviar la imagen
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
                    idTickets: {{ $ticket->idTickets }}, // Pasar el ID del ticket
                    idVisitas: {{ $visitaId ?? 'null' }} // Pasar el ID de la visita (si existe)
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
    document.getElementById("estado").addEventListener("change", function() {
        const estadoId = this.value;
        const ticketId = {{ $ticket->idTickets }};
        const visitaId = {{ $visitaId ?? 'null' }};

        // Obtener la justificación del estado seleccionado
        fetch(`/api/obtenerJustificacion?ticketId=${ticketId}&visitaId=${visitaId}&estadoId=${estadoId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostrar la justificación en el textarea
                    document.getElementById("justificacion").value = data.justificacion || "";
                } else {
                    console.error("Error al obtener la justificación:", data.error);
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });

        // Verificar si el estado seleccionado es igual a 20
        if (estadoId == 3) {
            const cardFotos = document.getElementById("cardFotos");
            if (cardFotos) {
                cardFotos.style.display = "block"; // Mostrar el elemento
                console.log("Card de fotos mostrada porque estadoId es 20");
            }
        } else {
            const cardFotos = document.getElementById("cardFotos");
            if (cardFotos) {
                cardFotos.style.display = "none"; // Ocultar el elemento
                console.log("Card de fotos oculta porque estadoId no es 20");
            }
        }
    });
    document.addEventListener("DOMContentLoaded", function() {
        // Inicializar todos los select con la clase .selectize
        document.querySelectorAll(".selectize").forEach(function(select) {
            NiceSelect.bind(select);
        });
    });
</script>
