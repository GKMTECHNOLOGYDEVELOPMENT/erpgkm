<!-- Botón para abrir el modal de crear visita -->
<button id="crearVisitaBtn"
    class="px-4 py-2 bg-success text-white rounded-lg shadow-md hover:bg-green-700 w-full sm:w-auto">
    + Crear Visita
</button>

<!-- Contenedor donde se agregarán las visitas -->
<div id="visitasContainer" class="mt-5 space-y-4"></div>

<!-- MODAL PARA CREAR VISITA USANDO ALPINE.JS -->
<div x-data="{ open: false }" class="mb-5" @toggle-modal.window="open = !open">
    <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
        <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
            <div x-show="open" x-transition.duration.300
                class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-3xl my-8 animate__animated animate__zoomInUp">
                <!-- Header del Modal -->
                <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                    <h5 class="font-bold text-lg">Crear Nueva Visita</h5>
                    <button type="button" class="text-white-dark hover:text-dark" @click="open = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" class="w-6 h-6">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                <div class="modal-scroll">
                    <!-- Formulario -->
                    <form class="p-5 space-y-4">
                        <!-- Nombre de la visita -->
                        <div>
                            <label class="block text-sm font-medium">Nombre de la Visita</label>
                            <input id="nombreVisitaInput" type="text" class="form-input w-full bg-gray-200" readonly>
                        </div>
                        <!-- Fecha y hora -->
                        <div>
                            <label class="block text-sm font-medium">Fecha y Hora</label>
                            <input id="fechaVisitaInput" type="datetime-local" class="form-input w-full" required>
                        </div>
                        <!-- Botones -->
                        <div class="flex justify-end items-center mt-4">
                            <button type="button" class="btn btn-outline-danger"
                                @click="open = false">Cancelar</button>
                            <button type="button" class="btn btn-primary ltr:ml-4 rtl:mr-4"
                                onclick="guardarVisita()">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PARA SUBIR IMAGEN USANDO ALPINE.JS -->
<div x-data="{ openImagen: false, imagenUrl: '', visitaId: null, imagenGuardada: null }" class="mb-5" @toggle-modal-imagen.window="openImagen = !openImagen; visitaId = $event.detail.visitaId; imagenUrl = visitasData[visitaId]?.imagen || ''">
    <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="openImagen && '!block'">
        <div class="flex items-start justify-center min-h-screen px-4" @click.self="openImagen = false">
            <div x-show="openImagen" x-transition.duration.300
                class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8 animate__animated animate__zoomInUp">
                <!-- Header del Modal -->
                <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                    <h5 class="font-bold text-lg">Subir Imagen</h5>
                    <button type="button" class="text-white-dark hover:text-dark" @click="openImagen = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" class="w-6 h-6">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                <div class="modal-scroll">
                    <!-- Formulario -->
                    <form class="p-5 space-y-4">
                        <!-- Input para subir imagen -->
                        <div>
                            <label class="block text-sm font-medium">Seleccionar Imagen</label>
                            <input type="file" accept="image/*" class="form-input w-full"
                                @change="imagenUrl = URL.createObjectURL($event.target.files[0]); imagenGuardada = $event.target.files[0]">
                        </div>
                        <!-- Previsualización de la imagen -->
                        <div class="flex justify-center">
                            <img :src="imagenUrl" alt="Previsualización" class="w-40 h-40 object-cover"
                                x-show="imagenUrl">
                        </div>
                        <!-- Botones -->
                        <div class="flex justify-end items-center mt-4">
                            <button type="button" class="btn btn-outline-danger"
                                @click="openImagen = false">Cancelar</button>
                            <button type="button" class="btn btn-primary ltr:ml-4 rtl:mr-4"
                                @click="guardarImagen(visitaId, imagenGuardada)">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let visitasContainer = document.getElementById("visitasContainer");
        let crearVisitaBtn = document.getElementById("crearVisitaBtn");
        let nombreVisitaInput = document.getElementById("nombreVisitaInput");
        let fechaVisitaInput = document.getElementById("fechaVisitaInput");
        let visitaCount = 0;

        // Almacenar datos de las visitas
        let visitasData = {};

        // Estados disponibles
        const estados = [{
                nombre: "Fecha de Programación",
                requiereUbicacion: true
            },
            {
                nombre: "Técnico en desplazamiento",
                requiereUbicacion: true
            },
            {
                nombre: "Llegada a servicio",
                requiereUbicacion: true,
                requiereImagen: true
            },
            {
                nombre: "Inicio de servicio",
                requiereUbicacion: true
            }
        ];

        function formatDate(fecha) {
            const año = fecha.getFullYear();
            const mes = (fecha.getMonth() + 1).toString().padStart(2, "0");
            const dia = fecha.getDate().toString().padStart(2, "0");
            let horas = fecha.getHours();
            const minutos = fecha.getMinutes().toString().padStart(2, "0");
            const ampm = horas >= 12 ? "PM" : "AM";
            horas = horas % 12 || 12;
            return `${año}-${mes}-${dia} ${horas}:${minutos} ${ampm}`;
        }

        // ABRIR MODAL AL CREAR VISITA
        crearVisitaBtn.addEventListener("click", function() {
            visitaCount++;
            nombreVisitaInput.value = `Visita ${visitaCount}`;
            fechaVisitaInput.value = "";
            window.dispatchEvent(new Event('toggle-modal'));
        });

        // GUARDAR VISITA
        window.guardarVisita = function() {
            if (!fechaVisitaInput.value) {
                alert("Por favor, selecciona una fecha y hora.");
                return;
            }

            let fechaSeleccionada = new Date(fechaVisitaInput.value);
            let fechaFormateada = formatDate(fechaSeleccionada);
            let visitaId = `visita-${visitaCount}`;

            // Inicializar datos de la visita
            visitasData[visitaId] = {
                imagen: null,
                estados: []
            };

            let visitaCard = document.createElement("div");
            visitaCard.classList.add("p-4", "shadow-lg", "rounded-lg", "border", "relative");
            visitaCard.id = visitaId;

            visitaCard.innerHTML = `
            <h5 class="text-lg font-semibold mb-3 text-center">${nombreVisitaInput.value}</h5>
            <div id="estadoContainer-${visitaId}" class="flex flex-col space-y-2">
                <!-- Primer estado: Fecha de Programación con botón ✔ -->
                <div class="flex justify-between items-center border p-3 rounded-lg bg-gray-100">
                    <span class="text-sm font-medium w-1/4">Fecha de Programación</span>
                    <span class="hora text-sm text-gray-500 w-1/4 text-center">${fechaFormateada}</span>
                    <span class="ubicacion text-sm text-gray-500 w-1/4 text-center hidden">Sucursal Lima Centro</span>
                    <button class="estado-btn bg-gray-300 text-white px-3 py-1 rounded-md w-1/4" data-estado="0" data-visita="${visitaId}">
                        ✔
                    </button>
                </div>
            </div>
        `;

            visitasContainer.appendChild(visitaCard);
            window.dispatchEvent(new Event('toggle-modal'));
        };

        function agregarEstado(visitaId, estadoIndex) {
            if (estadoIndex >= estados.length) return;

            let estado = estados[estadoIndex];
            let estadoContainer = document.getElementById(`estadoContainer-${visitaId}`);

            let estadoDiv = document.createElement("div");
            estadoDiv.classList.add("flex", "justify-between", "items-center", "border", "p-3", "rounded-lg",
                "bg-gray-100");
            estadoDiv.innerHTML = `
            <span class="text-sm font-medium w-1/4">${estado.nombre}</span>
            <span class="hora text-sm text-gray-500 w-1/4 text-center hidden"></span>
            <span class="ubicacion text-sm text-gray-500 w-1/4 text-center hidden">Sucursal Lima Centro</span>
            ${estado.requiereImagen ? `
                <button class="btn-modal bg-blue-500 text-white px-3 py-1 rounded-md w-1/4" data-visita="${visitaId}" onclick="abrirModalImagen('${visitaId}')">
                    📷
                </button>
                <button class="estado-btn bg-gray-300 text-white px-3 py-1 rounded-md w-1/4" data-estado="${estadoIndex}" data-visita="${visitaId}">
                    ✔
                </button>
            ` : `
                <button class="estado-btn bg-gray-300 text-white px-3 py-1 rounded-md w-1/4" data-estado="${estadoIndex}" data-visita="${visitaId}">
                    ✔
                </button>
            `}
        `;

            estadoContainer.appendChild(estadoDiv);
        }

        // ABRIR MODAL DE IMAGEN
        window.abrirModalImagen = function(visitaId) {
            window.dispatchEvent(new CustomEvent('toggle-modal-imagen', {
                detail: {
                    visitaId
                }
            }));
        };

        // GUARDAR IMAGEN
        window.guardarImagen = function(visitaId, imagen) {
            if (!visitaId || !imagen) {
                alert("Por favor, selecciona una imagen.");
                return;
            }

            // Guardar la imagen en los datos de la visita
            visitasData[visitaId].imagen = URL.createObjectURL(imagen);

            // Cerrar el modal de imagen
            window.dispatchEvent(new Event('toggle-modal-imagen'));
        };

        // AVANZAR ESTADOS UNO A UNO
        document.addEventListener("click", function(event) {
            if (event.target.classList.contains("estado-btn")) {
                let btn = event.target;
                let estadoIndex = parseInt(btn.dataset.estado);
                let visitaId = btn.dataset.visita;
                let horaSpan = btn.closest(".border").querySelector(".hora");
                let ubicacionSpan = btn.closest(".border").querySelector(".ubicacion");

                let fechaActual = new Date();
                let fechaFormateada = formatDate(fechaActual);
                horaSpan.textContent = fechaFormateada;
                horaSpan.classList.remove("hidden");
                ubicacionSpan.classList.remove("hidden");

                btn.closest(".border").classList.add("bg-green-200", "border-green-500");
                btn.disabled = true;

                // Agregar el siguiente estado
                agregarEstado(visitaId, estadoIndex + 1);
            }
        });

    });
</script>