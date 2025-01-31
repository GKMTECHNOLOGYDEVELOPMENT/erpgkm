<x-layout.default>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <div class="mb-5" x-data="{ tab: 'detalle' }">
        <!-- Tabs -->
        <ul
            class="grid grid-cols-4 gap-2 sm:flex sm:flex-wrap sm:justify-center mt-3 mb-5 sm:space-x-3 rtl:space-x-reverse">
            <li>
                <a href="javascript:;"
                    class="p-7 py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] dark:bg-[#191e3a] hover:!bg-success hover:text-white hover:shadow-[0_5px_15px_0_rgba(0,0,0,0.30)]"
                    :class="{ '!bg-success text-white': tab === 'detalle' }" @click="tab = 'detalle'">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 2H16M8 2V6M16 2V6M4 6H20M4 6V22H20V6M9 10H15M9 14H15M9 18H12" />
                    </svg>
                    Detalles OT
                </a>
            </li>
            <li>
                <a href="javascript:;"
                    class="p-7 py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] dark:bg-[#191e3a] hover:!bg-success hover:text-white hover:shadow-[0_5px_15px_0_rgba(0,0,0,0.30)]"
                    :class="{ '!bg-success text-white': tab === 'visitas' }" @click="tab = 'visitas'">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 19l3-3m-3 3H5v-3l9-9a2 2 0 012.828 0l2.172 2.172a2 2 0 010 2.828l-9 9z" />
                    </svg>
                    Visitas
                </a>
            </li>
            <li>
                <a href="javascript:;"
                    class="p-7 py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] dark:bg-[#191e3a] hover:!bg-success hover:text-white hover:shadow-[0_5px_15px_0_rgba(0,0,0,0.30)]"
                    :class="{ '!bg-success text-white': tab === 'firmas' }" @click="tab = 'firmas'">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 19l3-3m-3 3H5v-3l9-9a2 2 0 012.828 0l2.172 2.172a2 2 0 010 2.828l-9 9z" />
                    </svg>
                    Firmas
                </a>
            </li>
        </ul>


        <!-- Contenido de los tabs -->
        <div class="panel mt-6 p-5 max-w-4xl mx-auto">
            <!-- Tab Detalles OT -->
            <div x-show="tab === 'detalle'">

                <h2 class="text-lg font-semibold mb-4">Detalles de la Orden de Trabajo</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Cliente General -->
                    <div>
                        <label class="block text-sm font-medium">Cliente General</label>
                        <input type="text" class="form-input w-full bg-gray-100"
                            value="{{ $orden->clienteGeneral->descripcion }}" readonly>
                    </div>

                    <!-- Cliente -->
                    <div>
                        <label class="block text-sm font-medium">Cliente</label>
                        <input type="text" class="form-input w-full bg-gray-100"
                            value="{{ $orden->cliente->nombre }} - {{ $orden->cliente->documento }}" readonly>
                    </div>

                    <!-- Tienda -->
                    <div>
                        <label class="block text-sm font-medium">Tienda</label>
                        <input type="text" class="form-input w-full bg-gray-100" value="{{ $orden->tienda->nombre }}"
                            readonly>
                    </div>

                    <!-- Dirección -->
                    <div>
                        <label class="block text-sm font-medium">Dirección</label>
                        <input type="text" class="form-input w-full bg-gray-100" value="{{ $orden->direccion }}"
                            readonly>
                    </div>

                    <!-- Marca -->
                    <div>
                        <label class="block text-sm font-medium">Marca</label>
                        <input type="text" class="form-input w-full bg-gray-100"
                            value="{{ $orden->marca?->nombre ?? 'No asignado' }}" readonly>

                    </div>

                    <!-- Modelo (Editable) -->
                    <div>
                        <label for="idModelo" class="block text-sm font-medium">Modelos</label>
                        <select id="idModelo" name="idModelo" class="select2 w-full" style="display:none">
                            <option value="" disabled>Seleccionar Modelo</option>
                            @foreach ($modelos as $modelo)
                                <option value="{{ $modelo->idModelo }}"
                                    {{ $orden->idModelo == $modelo->idModelo ? 'selected' : '' }}>
                                    {{ $modelo->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>



                    <!-- Serie (Editable) -->
                    <div>
                        <label class="block text-sm font-medium">N. Serie</label>
                        <input id="serie" name="serie" type="text" class="form-input w-full"
                            value="{{ $orden->serie }}">
                    </div>

                    <!-- Técnico -->
                    <div>
                        <label class="block text-sm font-medium">Técnico Principal</label>
                        <input type="text" class="form-input w-full bg-gray-100"
                            value="{{ $orden->tecnico->Nombre }}" readonly>
                    </div>

                    <!-- Fecha de Compra -->
                    <div>
                        <label class="block text-sm font-medium">Fecha de Compra</label>
                        <input id="fechaCompra" name="fechaCompra" type="text" class="form-input w-full bg-gray-100"
                            value="{{ $orden->fechaCompra }}" readonly>
                    </div>

                    <!-- Falla Reportada -->
                    <div class="">
                        <label class="block text-sm font-medium">Falla Reportada</label>
                        <textarea id="fallaReportada" name="fallaReportada" rows="1" class="form-input w-full bg-gray-100" readonly>{{ $orden->fallaReportada }}</textarea>
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

                </div>

            </div>

            <!-- Tab visitas -->
            <div x-show="tab === 'visitas'">
                <h4 class="font-semibold text-2xl mb-4">Visitas</h4>

                <!-- Botón para crear nuevas visitas -->
                <button id="crearVisitaBtn"
                    class="px-4 py-2 bg-success text-white rounded-lg shadow-md hover:bg-green-700 w-full sm:w-auto">
                    + Crear Visita
                </button>

                <!-- Contenedor donde se agregarán las visitas -->
                <div id="visitasContainer" class="mt-5 space-y-4"></div>
            </div>

            <!-- Modal para crear visita -->
            <div id="modalCrearVisita"
                class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
                <div class="modal-content bg-white p-4 rounded shadow-lg w-96">
                    <h5 class="mb-4">Crear Nueva Visita</h5>
                    <input id="nombreVisitaInput" type="text" placeholder="Nombre de la visita"
                        class="form-input w-full p-2 border rounded-lg mb-4">
                    <input id="fechaVisitaInput" type="datetime-local"
                        class="form-input w-full p-2 border rounded-lg">
                    <div class="flex justify-end mt-4">
                        <button type="button" class="btn btn-outline-danger mr-2"
                            onclick="cerrarModalCrearVisita()">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="guardarVisita()">Guardar</button>
                    </div>
                </div>
            </div>



            <!-- Tab Firmas -->
            <div x-show="tab === 'firmas'">
                <h4 class="font-semibold text-2xl mb-4">Firmas</h4>
                <p>Aquí van las firmas de la orden de trabajo.</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Inicializar NiceSelect2
            document.querySelectorAll('.select2').forEach(function(select) {
                NiceSelect.bind(select, {
                    searchable: true
                });
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            let selectTecnicoApoyo = document.getElementById("idTecnicoApoyo");
            let checkboxApoyo = document.getElementById("necesitaApoyo");
            let selectContainer = document.getElementById("apoyoSelectContainer");
            let selectedItemsContainer = document.getElementById("selected-items-container");
            let selectedItemsList = document.getElementById("selected-items-list");

            // Inicializar NiceSelect2 en el select múltiple
            NiceSelect.bind(selectTecnicoApoyo, {
                searchable: true
            });

            // Mostrar/ocultar el select2 de técnicos de apoyo según el checkbox
            checkboxApoyo.addEventListener("change", function() {
                if (this.checked) {
                    selectContainer.classList.remove("hidden");
                    selectedItemsContainer.classList.remove("hidden");
                } else {
                    selectContainer.classList.add("hidden");
                    selectedItemsContainer.classList.add("hidden");
                    selectedItemsList.innerHTML = ""; // Limpiar seleccionados si se desactiva
                    selectTecnicoApoyo.value = ""; // Reiniciar el select
                    NiceSelect.sync(selectTecnicoApoyo);
                }
            });

            // Actualizar la lista de seleccionados dinámicamente
            selectTecnicoApoyo.addEventListener("change", function() {
                selectedItemsList.innerHTML = ""; // Limpiar antes de actualizar

                let selectedOptions = Array.from(selectTecnicoApoyo.selectedOptions);
                selectedOptions.forEach(option => {
                    let item = document.createElement("span");
                    item.classList.add("badge", "bg-primary", "px-3", "py-1", "text-white",
                        "rounded-lg", "text-sm", "font-medium");
                    item.textContent = option.text;
                    selectedItemsList.appendChild(item);
                });
            });
        });


        //ESTO ES DE VISITAS!
        document.addEventListener("DOMContentLoaded", function() {
            let visitasContainer = document.getElementById("visitasContainer");
            let crearVisitaBtn = document.getElementById("crearVisitaBtn");
            let modalCrearVisita = document.getElementById("modalCrearVisita");
            let nombreVisitaInput = document.getElementById("nombreVisitaInput");
            let fechaVisitaInput = document.getElementById("fechaVisitaInput");
            let modalImagen = document.getElementById("modalImagen");
            let inputImagen = document.getElementById("inputImagen");
            let previewImagen = document.getElementById("previewImagen");
            let visitaActual = null;
            let visitaCount = 0;

            // Estados disponibles
            const estados = [{
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

            const ubicaciones = [
                "Sucursal Lima Centro",
                "Sucursal San Isidro",
                "Sucursal Miraflores"
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
                modalCrearVisita.classList.remove("hidden");
            });

            // GUARDAR VISITA
            function guardarVisita() {
                if (!fechaVisitaInput.value) {
                    alert("Por favor, selecciona una fecha y hora.");
                    return;
                }

                let fechaSeleccionada = new Date(fechaVisitaInput.value);
                let fechaFormateada = formatDate(fechaSeleccionada);

                let visitaCard = document.createElement("div");
                visitaCard.classList.add("p-4", "bg-white", "shadow-lg", "rounded-lg", "border", "relative");

                visitaCard.innerHTML = `
            <h5 class="text-lg font-semibold mb-3 text-center">${nombreVisitaInput.value}</h5>
            <div class="space-y-2">
                <div class="flex items-center justify-between border p-3 rounded-lg bg-green-200 border-green-500">
                    <span class="text-sm font-medium w-1/4">Fecha de Programación</span>
                    <span class="hora text-sm text-gray-800 w-3/4 text-center">${fechaFormateada}</span>
                </div>
                ${estados.map((estado, index) => `
                            <div class="flex items-center justify-between border p-3 rounded-lg bg-gray-100">
                                <span class="text-sm font-medium w-1/4">${estado.nombre}</span>
                                ${estado.requiereImagen ? `
                            <button class="btn-modal bg-blue-500 text-white px-3 py-1 rounded-md">
                                📷
                            </button>
                        ` : ""}
                                <button class="estado-btn bg-gray-300 text-white px-3 py-1 rounded-md">
                                    ✔
                                </button>
                            </div>
                        `).join("")}
            </div>
        `;

                visitasContainer.appendChild(visitaCard);
                cerrarModalCrearVisita();
            }

            // CERRAR MODAL DE CREACIÓN DE VISITA
            function cerrarModalCrearVisita() {
                modalCrearVisita.classList.add("hidden");
            }

            // ABRIR MODAL DE IMAGEN
            document.addEventListener("click", function(event) {
                if (event.target.classList.contains("btn-modal")) {
                    modalImagen.classList.remove("hidden");
                    visitaActual = event.target.closest(".border");
                }
            });

            // GUARDAR IMAGEN
            function guardarImagen() {
                if (visitaActual && inputImagen.files.length > 0) {
                    previewImagen.src = URL.createObjectURL(inputImagen.files[0]);
                    previewImagen.classList.remove("hidden");
                    visitaActual.classList.add("bg-green-200", "border-green-500");
                    modalImagen.classList.add("hidden");
                }
            }

            function cerrarModalImagen() {
                modalImagen.classList.add("hidden");
            }

            // AVANZAR ESTADOS
            document.addEventListener("click", function(event) {
                if (event.target.classList.contains("estado-btn")) {
                    let estadoDiv = event.target.closest(".border");
                    let horaSpan = estadoDiv.querySelector(".hora");

                    if (!horaSpan.textContent) {
                        let fechaActual = new Date();
                        let fechaFormateada = formatDate(fechaActual);
                        horaSpan.textContent = fechaFormateada;
                        horaSpan.classList.remove("hidden");
                    }

                    estadoDiv.classList.remove("bg-gray-100");
                    estadoDiv.classList.add("bg-green-200", "border-green-500");
                    event.target.disabled = true;
                }
            });

            // Conectar botones de modal a funciones
            window.guardarVisita = guardarVisita;
            window.guardarImagen = guardarImagen;
            window.cerrarModalCrearVisita = cerrarModalCrearVisita;
            window.cerrarModalImagen = cerrarModalImagen;
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
</x-layout.default>
