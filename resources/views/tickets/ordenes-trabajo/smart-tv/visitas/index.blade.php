<!-- Botón para abrir el modal de crear visita -->
<button id="crearVisitaBtn"
    class="px-4 py-2 bg-success text-white rounded-lg shadow-md hover:bg-green-700 w-full sm:w-auto">
    Crear Visita
</button>

<!-- Contenedor donde se agregarán las visitas -->
<div id="visitasContainer" class="mt-5 flex flex-col space-y-4"></div>

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
                        <div class="flex space-x-2">
                            <div class="w-1/2">
                                <label class="block text-sm font-medium">Nombre de la Visita</label>
                                <input id="nombreVisitaInput" type="text" class="form-input w-full bg-gray-200"
                                    readonly>
                            </div>
                            <!-- Fecha -->
                            <div class="w-1/2">
                                <label class="block text-sm font-medium">Fecha</label>
                                <!-- Tipo text para que flatpickr lo maneje -->
                                <input id="fechaVisitaInput" type="text" class="form-input w-full"
                                    placeholder="Elige una fecha" required>
                            </div>
                        </div>
                        <!-- Rango de Hora -->
                        <div class="flex space-x-2">
                            <div class="w-1/2">
                                <label class="block text-sm font-medium">Hora de Inicio</label>
                                <input id="horaInicioInput" type="text" class="form-input w-full"
                                    placeholder="Elige la hora de Inicio" required>
                            </div>
                            <div class="w-1/2">
                                <label class="block text-sm font-medium">Hora de Fin</label>
                                <input id="horaFinInput" type="text" class="form-input w-full"
                                    placeholder="Elige la hora de Fin" required>
                            </div>
                        </div>
                        <!-- Técnico -->
                        <div>
                            <label for="tecnico" class="block text-sm font-medium">Técnico</label>
                            <select id="tecnico" name="tecnico" class="select2 w-full" style="display: none">
                                <option value="" disabled selected>Seleccionar Técnico</option>
                                <!-- Aquí se itera sobre los usuarios -->
                                @foreach ($usuario as $usuarios)
                                    <option value="{{ $usuario->idUsuario }}">{{ $usuario->Nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Checkbox Necesita Apoyo -->
                        <div class="mt-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" id="necesitaApoyo" class="form-checkbox">
                                <span class="ml-2 text-sm font-medium">¿Necesita Apoyo?</span>
                            </label>
                        </div>
                        <!-- Select Múltiple para Técnicos de Apoyo (Inicialmente Oculto) -->
                        <div id="apoyoSelectContainer" class="mt-3 hidden">
                            <label for="idTecnicoApoyo" class="block text-sm font-medium">Seleccione Técnicos de
                                Apoyo</label>
                            <select id="idTecnicoApoyo" name="idTecnicoApoyo[]" multiple
                                placeholder="Seleccionar Técnicos de Apoyo" style="display:none">
                                <option value="2">María López</option>
                                <option value="3">Carlos García</option>
                                <option value="4">Ana Martínez</option>
                                <option value="5">Pedro Sánchez</option>
                            </select>
                        </div>
                        <!-- Contenedor para mostrar los técnicos seleccionados -->
                        <div id="selected-items-container" class="mt-3 hidden">
                            <strong>Seleccionados:</strong>
                            <div id="selected-items-list" class="flex flex-wrap gap-2"></div>
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
<div x-data="{
    openImagen: false,
    imagenUrl: '',
    imagenActual: '/assets/images/file-preview.svg',
    visitaId: null,
    imagenGuardada: null
}" class="mb-5"
    @toggle-modal-imagen.window="openImagen = !openImagen; 
                             visitaId = $event.detail.visitaId; 
                             imagenUrl = visitasData[visitaId]?.imagen || ''">
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
                <div class="modal-scroll p-5 space-y-4">
                    <!-- Formulario -->
                    <form>
                        <!-- Input para subir imagen -->
                        <div>
                            <label class="block text-sm font-medium">Foto</label>
                            <input type="file" accept="image/*" class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold p-0 file:bg-primary/90 ltr:file:mr-5 rtl:file-ml-5 file:text-white file:hover:bg-primary w-full"
                                @change="imagenUrl = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : imagenActual; 
                                     imagenGuardada = $event.target.files[0]">
                        </div>
                        <!-- Previsualización de la imagen -->
                        <div class="flex justify-center">
                            <template x-if="imagenUrl">
                                <img :src="imagenUrl" alt="Previsualización" class="w-40 h-40 object-cover">
                            </template>
                            <template x-if="!imagenUrl">
                                <img :src="imagenActual" alt="Imagen predeterminada"
                                    class="w-40 h-40 object-cover">
                            </template>
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


<!-- MODAL PARA MOSTRAR DETALLES DE VISITA (usando Alpine.js) -->
<div x-data="{ openDetalle: false, detalles: '' }" class="mb-5"
    @toggle-modal-detalle.window="openDetalle = !openDetalle; detalles = $event.detail.detalles">
    <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="openDetalle && '!block'">
        <div class="flex items-start justify-center min-h-screen px-4" @click.self="openDetalle = false">
            <div x-show="openDetalle" x-transition.duration.300
                class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8 bg-white dark:bg-gray-800 animate__animated animate__zoomInUp">
                <!-- Header del Modal -->
                <div class="flex bg-gray-100 dark:bg-gray-700 items-center justify-between px-5 py-3">
                    <h5 class="font-bold text-lg">Detalles de Visita</h5>
                    <button type="button" class="text-gray-700 dark:text-gray-300 hover:text-gray-900"
                        @click="openDetalle = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" class="w-6 h-6">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                <div class="p-5">
                    <div class="text-sm text-gray-700 dark:text-gray-300" x-html="detalles"></div>
                    <div class="flex justify-end mt-4">
                        <button type="button" class="btn btn-primary" @click="openDetalle = false">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // INICIALIZAR FLATPICKR
        flatpickr("#fechaVisitaInput", {
            locale: "es",
            dateFormat: "Y-m-d"
        });
        flatpickr("#horaInicioInput", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            locale: "es"
        });
        flatpickr("#horaFinInput", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            locale: "es"
        });

        let visitasContainer = document.getElementById("visitasContainer");
        let crearVisitaBtn = document.getElementById("crearVisitaBtn");
        let nombreVisitaInput = document.getElementById("nombreVisitaInput");
        // En lugar de un input datetime-local, tenemos tres campos:
        let fechaVisitaInput = document.getElementById("fechaVisitaInput"); // tipo date
        let horaInicioInput = document.getElementById("horaInicioInput"); // tipo time
        let horaFinInput = document.getElementById("horaFinInput"); // tipo time
        let visitaCount = 0;

        // Almacenar datos de las visitas (incluyendo la imagen, si se sube)
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
            // Limpiar los campos de fecha y hora
            fechaVisitaInput.value = "";
            horaInicioInput.value = "";
            horaFinInput.value = "";
            window.dispatchEvent(new Event('toggle-modal'));
        });

        // GUARDAR VISITA
        window.guardarVisita = function() {
            const fecha = fechaVisitaInput.value;
            const horaInicio = horaInicioInput.value;
            const horaFin = horaFinInput.value;

            if (!fecha || !horaInicio || !horaFin) {
                alert("Por favor, selecciona la fecha y el rango de hora.");
                return;
            }

            const fechaInicio = new Date(fecha + 'T' + horaInicio);
            const fechaFin = new Date(fecha + 'T' + horaFin);

            if (fechaInicio >= fechaFin) {
                alert("La hora de inicio debe ser menor a la hora de fin.");
                return;
            }

            let fechaFormateada = `${formatDate(fechaInicio)} - ${formatDate(fechaFin)}`;
            let visitaId = `visita-${visitaCount}`;

            // Inicializar datos de la visita (la imagen se almacenará si se sube)
            visitasData[visitaId] = {
                imagen: null,
                estados: []
            };

            let visitaCard = document.createElement("div");
            visitaCard.classList.add("p-4", "shadow-lg", "rounded-lg", "relative");
            visitaCard.id = visitaId;

            // Aplicar el color de fondo correspondiente a "Fecha de Programación"
            let fechaProgramacionColor = "#eaf1ff"; // Color de fondo para "Fecha de Programación"

            // La tarjeta muestra una fila alineada con 4 columnas:
            visitaCard.innerHTML = `
            <div class="flex items-center justify-between mb-4">
                <h5 class="text-lg font-semibold">${nombreVisitaInput.value}</h5>
                <button class="detalles-btn btn btn-info" data-visita="${visitaId}">Detalles de Visita</button>
            </div>
            <div id="estadoContainer-${visitaId}" class="flex flex-col space-y-2">
                <!-- Primer estado: Fecha de Programación -->
                <div class="flex flex-row items-center p-3 rounded-lg estado-row" style="background-color: ${fechaProgramacionColor};">
                    <span class="text-sm font-medium w-1/4 text-center">Fecha de Programación</span>
                    <span class="hora text-sm w-1/4 text-center">${fechaFormateada}</span>
                    <span class="ubicacion text-sm w-1/4 text-center hidden">Sucursal Lima Centro</span>
                    <div class="flex flex-row items-center space-x-1 w-1/4">
                        <span class="estado-btn badge bg-success cursor-pointer" 
            data-estado="0" data-visita="${visitaId}">
            ✔
        </span>
                    </div>
                </div>
            </div>
        `;

            visitasContainer.appendChild(visitaCard);
            window.dispatchEvent(new Event('toggle-modal'));
        };

        // Función para agregar un nuevo estado (cada estado en una sola fila)
        function agregarEstado(visitaId, estadoIndex) {
            if (estadoIndex >= estados.length) return;

            let estado = estados[estadoIndex];
            let estadoContainer = document.getElementById(`estadoContainer-${visitaId}`);
            // Definir los colores en hexadecimal
            const estadoColores = [
                '#eaf1ff', // Fecha de Programación (Primary-Light)
                "#fff9ed", // Técnico en Desplazamiento (Info-Light)
                "#ddf5f0", // Llegada a Servicio (Warning-Light)
                "#fbe5e6" // Inicio de Servicio (Success-Light)
            ];

            let estadoDiv = document.createElement("div");
            estadoDiv.classList.add("flex", "flex-row", "items-center", "p-3", "rounded-lg", 'estado-row');

            // Aplicar color de fondo directamente en el estilo inline
            estadoDiv.style.backgroundColor = estadoColores[estadoIndex];

            let html = `
        <span class="text-sm font-medium w-1/4 text-center">${estado.nombre}</span>
        <span class="hora text-sm w-1/4 text-center hidden"></span>
        <span class="ubicacion text-sm w-1/4 text-center hidden">Sucursal Lima Centro</span>
        <div class="flex flex-row items-center space-x-1 w-1/4">`;

            if (estado.requiereImagen) {
                html += `
                <span class="btn-modal badge bg-primary cursor-pointer" data-visita="${visitaId}" onclick="abrirModalImagen('${visitaId}')">
            📷
        </span>
        <span class="estado-btn badge bg-success cursor-pointer" data-estado="${estadoIndex}" data-visita="${visitaId}">
            ✔
        </span>`;
            } else {
                html += `
                <span class="estado-btn badge bg-success cursor-pointer" data-estado="${estadoIndex}" data-visita="${visitaId}">
            ✔
        </span>`;
            }

            html += `</div>`;
            estadoDiv.innerHTML = html;
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

        // GUARDAR IMAGEN (la imagen se almacena en visitasData)
        window.guardarImagen = function(visitaId, imagen) {
            if (!visitaId || !imagen) {
                alert("Por favor, selecciona una imagen.");
                return;
            }
            visitasData[visitaId].imagen = URL.createObjectURL(imagen);
            window.dispatchEvent(new Event('toggle-modal-imagen'));
        };

        // INICIALIZAR CAMPO TÉCNICO Y SOPORTE
        let selectTecnicoApoyo = document.getElementById("idTecnicoApoyo");
        let checkboxApoyo = document.getElementById("necesitaApoyo");
        let selectContainer = document.getElementById("apoyoSelectContainer");
        let selectedItemsContainer = document.getElementById("selected-items-container");
        let selectedItemsList = document.getElementById("selected-items-list");

        // Inicializar NiceSelect2 en el select múltiple de técnicos de apoyo
        NiceSelect.bind(selectTecnicoApoyo, {
            searchable: true
        });

        checkboxApoyo.addEventListener("change", function() {
            if (this.checked) {
                selectContainer.classList.remove("hidden");
                selectedItemsContainer.classList.remove("hidden");
            } else {
                selectContainer.classList.add("hidden");
                selectedItemsContainer.classList.add("hidden");
                selectedItemsList.innerHTML = "";
                selectTecnicoApoyo.value = "";
                NiceSelect.sync(selectTecnicoApoyo);
            }
        });

        selectTecnicoApoyo.addEventListener("change", function() {
            selectedItemsList.innerHTML = "";
            let selectedOptions = Array.from(selectTecnicoApoyo.selectedOptions);
            selectedOptions.forEach(option => {
                let item = document.createElement("span");
                item.classList.add("badge", "bg-primary", "px-3", "py-1", "text-white",
                    "rounded-lg", "text-sm", "font-medium");
                item.textContent = option.text;
                selectedItemsList.appendChild(item);
            });
        });

        // AVANZAR ESTADOS UNO A UNO
        document.addEventListener("click", function(event) {
            if (event.target.classList.contains("estado-btn")) {
                let btn = event.target;
                let estadoIndex = parseInt(btn.dataset.estado);
                let visitaId = btn.dataset.visita;

                // Si el estado actual requiere imagen (por ejemplo, "Llegada a servicio")
                if (estados[estadoIndex].requiereImagen) {
                    if (!visitasData[visitaId].imagen) {
                        alert("Debe subir una imagen para 'Llegada a servicio'.");
                        window.abrirModalImagen(visitaId);
                        return;
                    }
                }
                let containerDiv = btn.closest(".estado-row");
                let horaSpan = containerDiv.querySelector(".hora");
                let ubicacionSpan = containerDiv.querySelector(".ubicacion");

                // Si NO es el estado 0, actualizamos la hora con la fecha actual;
                // en estado 0 se conserva el valor establecido al guardar la visita.
                if (estadoIndex !== 0) {
                    let fechaActual = new Date();
                    let fechaFormateada = formatDate(fechaActual);
                    horaSpan.textContent = fechaFormateada;
                }
                horaSpan.classList.remove("hidden");
                ubicacionSpan.classList.remove("hidden");

                containerDiv.classList.add("bg-green-200", "border-green-500");
                btn.disabled = true;

                // Agregar el siguiente estado
                agregarEstado(visitaId, estadoIndex + 1);
            }
        });

        // BOTÓN DETALLES DE VISITA: mostrar información de la visita
        document.addEventListener("click", function(event) {
            if (event.target.classList.contains("detalles-btn")) {
                let btn = event.target;
                let visitaId = btn.dataset.visita;
                let datos = visitasData[visitaId];
                // Construir los detalles
                let detalles = `
                <strong>Nombre:</strong> ${datos.nombre}<br>
                <strong>Fecha Programada:</strong> ${datos.rango}<br>
                <strong>Técnico:</strong> ${datos.tecnico ? datos.tecnico.nombre : "No seleccionado"}<br>
                <strong>Técnicos de Apoyo:</strong> ${datos.apoyo && datos.apoyo.length ? datos.apoyo.join(", ") : "Ninguno"}
            `;
                // Abrir modal de detalles
                window.dispatchEvent(new CustomEvent('toggle-modal-detalle', {
                    detail: {
                        detalles
                    }
                }));
            }
        });
    });
</script>
