<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script> -->

<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


<style>
    .nice-select {
        line-height: 2.25rem !important;
        /* ajusta altura */
        height: 2.5rem !important;
        /* igual que un input base */
        padding: 0 1rem !important;
        font-size: 0.875rem;
        /* text-sm */
    }

    /* Estilo cuando el formulario está "tachado" */
    .formulario-tachado {
        position: relative;
    }

    .formulario-tachado * {
        text-decoration: line-through;
        color: #999 !important;
    }

    /* Opcional: Efecto de rayado diagonal en el fondo */
    .formulario-tachado::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;

        pointer-events: none;
        z-index: 1;
    }

    /* Deshabilitar interacción */
    .formulario-tachado form {
        pointer-events: none;
        opacity: 0.7;
    }

    .custom_switch:disabled+span {
        background-color: #f3f4f6 !important;
    }

    .custom_switch:disabled+span:before {
        background-color: #d1d5db !important;
    }

    .peer:checked~.custom_switch-indicator {
        left: 1.75rem;
    }
</style>
<!-- Contenedor Alpine.js para el botón y el modal -->
<div x-data="{ openModal: false }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between w-full text-center sm:text-left">
        <span class="text-sm sm:text-lg font-semibold mb-2 sm:mb-4 badge bg-success"
            style="background-color: {{ $colorEstado }};">
            Orden de Trabajo N° {{ $orden->idTickets }}
        </span>

        <!-- Botón flotante responsive -->
        <button id="botonFlotante"
            class="bg-dark text-white px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg shadow-md transition-all duration-200
            text-xs sm:text-sm md:text-base flex items-center justify-center gap-1 sm:gap-2 w-full sm:w-auto"
            @click="openModal = true">
            <i class="fa-solid fa-clock-rotate-left text-sm sm:text-base md:text-lg"></i>
            <span class="sm:inline"></span>
        </button>
    </div>


    <!-- Fondo oscuro cuando el modal está abierto -->
    <div x-show="openModal" class="fixed inset-0 bg-[black]/60 z-40 transition-opacity duration-300"
        @click="openModal = false">
    </div>

    <!-- Modal deslizable desde la derecha -->
    <div x-show="openModal" x-transition:enter="transition ease-in-out duration-300 transform"
        x-transition:enter-start="translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
        x-transition:leave="transition ease-in-out duration-300 transform"
        x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="translate-x-full opacity-0"
        class="panel fixed inset-y-0 right-0 z-50 w-full sm:w-[600px] md:w-[700px] lg:w-[800px] 
             dark:bg-gray-900 shadow-lg flex flex-col rounded-l-lg">


        <!-- Panel lateral -->
        <div
            class="relative h-full w-full sm:w-[600px] md:w-[700px] lg:w-[800px] 
                 dark:bg-gray-900 shadow-lg flex flex-col rounded-l-lg">

            <!-- Header fijo -->
            <div
                class="flex justify-between items-center border-b px-4 sm:px-6 py-3 border-gray-300 dark:border-gray-700">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 dark:text-white">Historial de Cambios</h2>
                <button @click="openModal = false"
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <!-- Contenido scrolleable -->
            <div class="flex-1 overflow-y-auto px-4 sm:px-6 py-4">
                <div x-data="{ activeTab: 'estados' }" class="flex flex-col h-full">

                    <!-- Tabs -->
                    <div class="flex space-x-4 border-b border-gray-300 dark:border-gray-700">
                        <button @click="activeTab = 'estados'"
                            :class="activeTab === 'estados' ? 'border-b-2 border-red-600 text-red-600' :
                                'text-gray-500 hover:text-gray-700'"
                            class="pb-2 font-semibold text-xs sm:text-sm uppercase">
                            Estados
                        </button>
                        <button @click="() => { activeTab = 'historial'; cargarHistorialModificaciones(ticketId); }"
                            :class="activeTab === 'historial' ? 'border-b-2 border-red-600 text-red-600' :
                                'text-gray-500 hover:text-gray-700'"
                            class="pb-2 font-semibold text-xs sm:text-sm uppercase">
                            Historial de Cambios
                        </button>
                    </div>

                    <!-- TAB: Estados -->
                    <div x-show="activeTab === 'estados'" class="overflow-x-auto mt-4">
                        <table class="min-w-[500px] w-full border-collapse text-xs sm:text-sm">
                            <thead>
                                <tr class="bg-gray-200 dark:bg-gray-800">
                                    <th class="px-2 sm:px-4 py-2 text-center">Estado</th>
                                    <th class="px-2 sm:px-4 py-2 text-center">Usuario</th>
                                    <th class="px-2 sm:px-4 py-2 text-center">Fecha</th>
                                    <th class="px-2 sm:px-4 py-2 text-center">Más</th>
                                </tr>
                            </thead>
                            <tbody id="estadosTableBody"></tbody>
                        </table>
                    </div>

                    <!-- TAB: Historial -->
                    <div x-show="activeTab === 'historial'" class="overflow-y-auto mt-4 flex-1">
                        <div class="overflow-x-auto rounded-lg shadow border border-gray-300 dark:border-gray-700">
                            <table
                                class="min-w-[600px] w-full divide-y divide-gray-200 dark:divide-gray-600 text-xs sm:text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-800 text-xs uppercase tracking-wider">
                                    <tr>
                                        <th class="px-2 sm:px-6 py-3">Campo</th>
                                        <th class="px-2 sm:px-6 py-3">Valor Antiguo</th>
                                        <th class="px-2 sm:px-6 py-3">Valor Nuevo</th>
                                        <th class="px-2 sm:px-6 py-3">Fecha</th>
                                        <th class="px-2 sm:px-6 py-3">Usuario</th>
                                    </tr>
                                </thead>
                                <tbody id="historialModificaciones"
                                    class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr id="preload" style="display:none;">
                                        <td colspan="5" class="px-6 py-4 text-center">Cargando datos...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="flex justify-center mt-4">
                            <ul id="pagination"
                                class="inline-flex items-center space-x-1 rtl:space-x-reverse m-auto mb-4">
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>



<script>
    // Suponiendo que tienes un ID de ticket disponible en tu página
    const ticketId = '{{ $id }}'; // Error aquí, ya que ticketId ya fue declarado en PHP

    function obtenerLabelsFormulario() {
        const labels = {
            horaInicioInput: 'Hora Inicio',
            horaFinInput: 'Hora Fin',
            fechaVisitaInput: 'Fecha Visita',
            nombreVisitaInput: 'Nombre de la Visita',
            // Podés seguir agregando más si querés personalizar más campos
        };

        document.querySelectorAll("form label").forEach(label => {
            const input = label.nextElementSibling || label.parentElement.querySelector(
                'input, select, textarea');
            if (input) {
                const name = input.getAttribute("name") || input.getAttribute("id");
                if (name && !labels[name]) {
                    labels[name] = label.textContent.trim(); // fallback si no está en el diccionario
                }
            }
        });

        return labels;
    }


    window.addEventListener('toggle-modal', function() {
        obtenerLabelsFormulario(); // 🔹 Asegurar que los labels se capturen antes de cargar el historial
        cargarHistorialModificaciones(ticketId);
    });

    // Variables globales para paginación
    let historialCompleto = [];
    let paginaActual = 1;
    const registrosPorPagina = 10;
    // Función para cargar el historial con paginación
    function cargarHistorialModificaciones(ticketId, page = 1) {
        const labels = obtenerLabelsFormulario();

        $.ajax({
            url: `/ticket/${ticketId}/historial-modificaciones?page=${page}&per_page=${registrosPorPagina}`,
            method: 'GET',
            success: function(response) {
                mostrarPagina(labels, response);
            }
        });
    }


    // Función para mostrar una página específica
    function mostrarPagina(labels, response) {
        const tbody = document.getElementById('historialModificaciones');
        tbody.innerHTML = '';

        response.data.forEach(modificacion => {
            const tr = document.createElement('tr');
            const campoLabel = labels[modificacion.campo] || modificacion.campo;

            tr.innerHTML = `
            <td>${campoLabel}</td>
            <td>${modificacion.valor_antiguo ?? '—'}</td>
            <td>${modificacion.valor_nuevo ?? '—'}</td>
            <td>${modificacion.fecha_modificacion}</td>
            <td>${modificacion.usuario}</td>
        `;
            tbody.appendChild(tr);
        });

        actualizarPaginacion(response.current_page, response.last_page);
    }


    function actualizarPaginacion(currentPage, lastPage) {
        const paginationContainer = document.getElementById('pagination');
        paginationContainer.innerHTML = '';

        // Botón Anterior
        const prevButton = document.createElement('li');
        prevButton.innerHTML = `
        <button 
            class="px-3 py-1 rounded-full text-sm font-semibold transition 
                   ${currentPage === 1 
                       ? 'bg-gray-200 text-gray-500 cursor-not-allowed' 
                       : 'bg-white-light text-dark hover:bg-primary hover:text-white dark:bg-[#191e3a] dark:text-white-light'}">
            <i class="fa-solid fa-chevron-left"></i>
        </button>`;
        if (currentPage > 1) {
            prevButton.querySelector('button').addEventListener('click', () => {
                cargarHistorialModificaciones(ticketId, currentPage - 1);
            });
        }
        paginationContainer.appendChild(prevButton);

        // Botones numéricos
        for (let i = 1; i <= lastPage; i++) {
            const pageButton = document.createElement('li');
            pageButton.innerHTML = `
            <button 
                class="px-3 py-1 rounded-full text-sm font-semibold transition 
                       ${i === currentPage 
                           ? 'bg-primary text-white shadow' 
                           : 'bg-white-light text-dark hover:bg-primary hover:text-white dark:bg-[#191e3a] dark:text-white-light'}">
                ${i}
            </button>`;
            pageButton.querySelector('button').addEventListener('click', () => {
                cargarHistorialModificaciones(ticketId, i);
            });
            paginationContainer.appendChild(pageButton);
        }

        // Botón Siguiente
        const nextButton = document.createElement('li');
        nextButton.innerHTML = `
        <button 
            class="px-3 py-1 rounded-full text-sm font-semibold transition 
                   ${currentPage === lastPage 
                       ? 'bg-gray-200 text-gray-500 cursor-not-allowed' 
                       : 'bg-white-light text-dark hover:bg-primary hover:text-white dark:bg-[#191e3a] dark:text-white-light'}">
            <i class="fa-solid fa-chevron-right"></i>
        </button>`;
        if (currentPage < lastPage) {
            nextButton.querySelector('button').addEventListener('click', () => {
                cargarHistorialModificaciones(ticketId, currentPage + 1);
            });
        }
        paginationContainer.appendChild(nextButton);
    }
</script>





<div class="p-6 mt-4 @if ($idEstadflujo == 33) formulario-tachado @endif">
    <form id="tuFormulario" action="formActualizarOrden" enctype="multipart/form-data" method="POST">
        @CSRF

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Ticket -->
            <div>
                <label class="text-sm font-medium flex items-center gap-2">
                    <i class="fa-solid fa-ticket text-gray-500"></i>
                    Ticket
                </label>
                <input type="text" class="form-input w-full bg-gray-100" value="{{ $orden->numero_ticket }}"
                    readonly>
            </div>

            <!-- Cliente -->
            <div>
                <label class="text-sm font-medium flex items-center gap-2">
                    <i class="fa-solid fa-user text-gray-500"></i>
                    Cliente
                </label>
                <select id="idCliente" name="idCliente" class="select2 w-full bg-gray-100">
                    <option value="">Seleccionar Cliente prueba</option>
                    @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->idCliente }}"
                            {{ optional($orden->cliente)->idCliente == $cliente->idCliente ? 'selected' : '' }}>
                            {{ $cliente->nombre }} - {{ $cliente->documento }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Cliente General -->
            <div>
                <label class="text-sm font-medium flex items-center gap-2">
                    <i class="fa-solid fa-users text-gray-500"></i>
                    Cliente General
                </label>
                <select id="idClienteGeneral" name="idClienteGeneral" class="form-input w-full select2">
                    <option value="" selected>Seleccionar Cliente General</option>
                    @if ($orden->clienteGeneral)
                        <option value="{{ $orden->clienteGeneral->idClienteGeneral }}" selected>
                            {{ $orden->clienteGeneral->descripcion }}
                        </option>
                    @endif
                </select>
            </div>

            <!-- Tienda -->
            <div id="selectTiendaContainer">
                <div>
                    <label class="text-sm font-medium flex items-center gap-2">
                        <i class="fa-solid fa-store text-gray-500"></i>
                        Tienda
                    </label>
                    <select id="idTienda" name="idTienda" class="form-input w-full select2">
                        <option value="" disabled selected>Seleccionar Tienda</option>
                        @if (isset($tiendas) && count($tiendas) > 0)
                            @foreach ($tiendas as $tienda)
                                <option value="{{ $tienda->idTienda }}"
                                    {{ $tienda->idTienda == $orden->idTienda ? 'selected' : '' }}>
                                    {{ $tienda->nombre }}
                                </option>
                            @endforeach
                        @else
                            <option value="" disabled>No hay tiendas disponibles</option>
                        @endif
                    </select>
                </div>
            </div>

            <!-- Dirección -->
            <div>
                <label class="text-sm font-medium flex items-center gap-2">
                    <i class="fa-solid fa-map-marker-alt text-gray-500"></i>
                    Dirección
                </label>
                <input id="direccion" name="direccion" type="text" class="form-input w-full"
                    value="{{ $orden->direccion }}">
            </div>

            <!-- Marca -->
            <div>
                <label class="text-sm font-medium flex items-center gap-2">
                    <i class="fa-solid fa-tag text-gray-500"></i>
                    Marca
                </label>
                <select id="idMarca" name="idMarca" class="select2 w-full bg-gray-100">
                    <option value="" disabled>Seleccionar Marca</option>
                    @foreach ($marcas as $marca)
                        <option value="{{ $marca->idMarca }}"
                            {{ $marca->idMarca == $orden->idMarca ? 'selected' : '' }}>
                            {{ $marca->nombre }}
                        </option>
                    @endforeach
                </select>
                <div id="preload" style="display: none;">Cargando marcas...</div>
            </div>

            <!-- Modelo -->
            <div>
                <label class="text-sm font-medium flex items-center gap-2">
                    <i class="fa-solid fa-cube text-gray-500"></i>
                    Modelo
                </label>
                <select id="idModelo" name="idModelo" class="select2 w-full">
                    <option value="" disabled selected>Seleccionar Modelo</option>
                    @if ($orden->modelo)
                        <option value="{{ $orden->modelo->idModelo }}" selected>
                            {{ $orden->modelo->nombre }}
                        </option>
                    @endif
                </select>
                <div id="preload-modelo" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i> Cargando modelos...
                </div>
            </div>

            <!-- Serie -->
            <div>
                <label class="text-sm font-medium flex items-center gap-2">
                    <i class="fa-solid fa-barcode text-gray-500"></i>
                    N. Serie
                </label>
                <input id="serie" name="serie" type="text" class="form-input w-full"
                    value="{{ $orden->serie }}">
            </div>

            <!-- Fecha de Compra -->
            <div>
                <label class="text-sm font-medium flex items-center gap-2">
                    <i class="fa-solid fa-calendar-check text-gray-500"></i>
                    Fecha de Compra
                </label>
                <input id="fechaCompra" name="fechaCompra" type="text" class="form-input w-full"
                    value="{{ \Carbon\Carbon::parse($orden->fechaCompra)->format('Y-m-d') }}">
            </div>

            <!-- Fecha de Creación -->
            <div>
                <label class="text-sm font-medium flex items-center gap-2">
                    <i class="fa-solid fa-calendar-plus text-gray-500"></i>
                    Fecha de Creación
                </label>
                <input id="fechaCreacion" name="fechaCreacion" type="text" class="form-input w-full"
                    value="{{ \Carbon\Carbon::parse($orden->fecha_creacion)->format('Y-m-d H:i') }}" readonly>
            </div>

            <!-- Falla Reportada -->
            <div class="md:col-span-2">
                <label class="text-sm font-medium"><i class="fa-solid fa-triangle-exclamation text-gray-500"></i>
                    Falla Reportada</label>
                <textarea id="fallaReportada" name="fallaReportada" rows="2" class="form-input w-full">{{ $orden->fallaReportada }}</textarea>
            </div>

            <div x-data="{ erma: '{{ $orden->erma }}' }" class="mb-5">
                <label class="text-sm font-medium"><i class="fa-solid fa-file-signature text-gray-500"></i> N.
                    erma</label>
                <input id="erma" name="erma" type="text" class="form-input w-full"
                    value="{{ $orden->erma }}">
                <div x-data="custodiaModal()" class="mt-5">
                    <!-- Switch Custodia -->
                    <div class="flex gap-12">
                        <div class="flex-1 mb-6">
                            <div class="flex items-center gap-2 mb-2">
                                <label for="EsCustonia" class="block text-sm font-medium">Custodia</label>

                                <!-- Icono de ayuda con tooltip mejorado -->
                                <div class="relative group">
                                    <div
                                        class="w-5 h-5 rounded-full bg-blue-100 flex items-center justify-center cursor-pointer">
                                        <i class="fa fa-question text-blue-500 text-xs"></i>
                                    </div>
                                    <div
                                        class="absolute left-7 -top-2 w-72 bg-blue-50 border border-blue-100 text-blue-700 text-sm rounded-lg p-3 shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                                        <div class="flex items-start gap-2">
                                            <div class="mt-0.5">
                                                <i class="fa fa-info-circle text-blue-500"></i>
                                            </div>
                                            <p>La custodia permite registrar la ubicación y la fecha en la que una
                                                pantalla quedó en resguardo temporal. Activa el switch para ingresar
                                                los datos.</p>
                                        </div>
                                        <div
                                            class="absolute -left-1.5 top-3 w-3 h-3 rotate-45 bg-blue-50 border-l border-b border-blue-100">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Switch mejorado -->
                            <div class="relative">
                                <label class="w-12 h-6 relative mt-3 block">
                                    <input type="checkbox" id="EsCustonia" name="EsCustonia"
                                        class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                                        @change="toggleCustodia($event)"
                                        {{ empty($orden->erma) ? 'disabled' : '' }} />
                                    <span
                                        class="bg-gray-200 dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300 {{ empty($orden->erma) ? 'opacity-70 cursor-not-allowed' : 'opacity-100' }}">
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Activar Custodia -->
                    <div class="fixed inset-0 bg-black/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
                        <div class="flex items-start justify-center min-h-screen px-4" @click.self="close()">
                            <div x-show="open" x-transition x-transition.duration.300
                                class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-lg bg-white shadow-xl">

                                <!-- Header mejorado -->
                                <div class="flex bg-primary items-center justify-between px-5 py-4 text-white">
                                    <div class="font-bold text-lg flex items-center gap-2">
                                        <i class="fa fa-box-archive"></i>
                                        <span>Datos de custodia</span>
                                    </div>
                                    <button type="button" class="text-white hover:text-gray-200 transition-colors"
                                        @click="close()">
                                        <i class="fa fa-times text-lg"></i>
                                    </button>
                                </div>

                                <!-- Body mejorado -->
                                <div class="p-6 space-y-5">
                                    <div class="bg-blue-50 rounded-lg p-3 border border-blue-100 mb-2">
                                        <p class="text-sm text-blue-700 flex items-start gap-2">
                                            <i class="fa fa-circle-info text-blue-500 mt-0.5"></i>
                                            <span>Complete la información de ubicación y fecha de ingreso para el
                                                registro de custodia.</span>
                                        </p>
                                    </div>

                                    <div>
                                        <label for="ubicacion"
                                            class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-1">
                                            <span>Ubicación Entrada - Custodia</span>
                                        </label>
                                        <div class="relative">
                                            <input type="text" id="ubicacion" x-model="ubicacion"
                                                class="form-input w-full border rounded-lg px-4 py-2.5 pl-10 focus:border-blue-500 focus:ring-blue-500 transition-colors"
                                                placeholder="Ej: Laboratorio, Zona TCL" maxlength="100">
                                            <i
                                                class="fa fa-location-dot absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="fecha"
                                            class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-1">
                                            <span>Fecha Ingreso - Custodia</span>
                                        </label>
                                        <div class="relative">
                                            <input type="date" id="fecha" x-model="fecha"
                                                class="form-input w-full border rounded-lg px-4 py-2.5 pl-10 focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                            <i
                                                class="fa fa-calendar-days absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                        </div>
                                    </div>

                                </div>

                                <!-- Footer mejorado -->
                                <div
                                    class="flex justify-end items-center gap-3 px-6 py-4 border-t bg-gray-50 rounded-b-lg">
                                    <button type="button"
                                        class="btn btn-outline-danger px-4 py-2 rounded-lg flex items-center gap-2"
                                        @click="cancelar()">
                                        <i class="fa fa-times"></i>
                                        <span>Cancelar</span>
                                    </button>
                                    <button type="button"
                                        class="btn btn-primary px-4 py-2 rounded-lg flex items-center gap-2 bg-blue-500 hover:bg-blue-600 transition-colors"
                                        @click="guardar()">
                                        <i class="fa fa-check"></i>
                                        <span>Guardar</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>





            <!-- Botón de Guardar -->
            <div class="md:col-span-2 flex justify-end space-x-4">
                <!-- Botón de Volver con outline-danger -->
                <a href="{{ route('ordenes.smart') }}" class="btn btn-outline-danger w-full md:w-auto">Volver</a>

                <!-- Botón de Modificar -->
                <button id="guardarFallaReportada" class="btn btn-primary w-full md:w-auto"
                    @if ($idEstadflujo == 33) disabled @endif>
                    Modificar
                </button>
            </div>
        </div>
    </form>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const idEstadflujo = @json($idEstadflujo);

        if (idEstadflujo === 33) {
            const form = document.getElementById('tuFormulario');

            // Aplicar tachado a todos los elementos de texto
            const textElements = form.querySelectorAll('label, input, select, textarea, button, span, div');
            textElements.forEach(el => {
                el.style.textDecoration = 'line-through';
                el.style.color = '#999';
            });

            // Deshabilitar todos los inputs
            const inputs = form.querySelectorAll('input, select, textarea, button');
            inputs.forEach(input => {
                input.disabled = true;
            });

            // Agregar mensaje
            const warning = document.createElement('div');
            warning.className = 'text-center mt-4 text-red-500 font-bold';
            warning.textContent = '⚠️ Este formulario no puede ser modificado';
            form.parentNode.insertBefore(warning, form.nextSibling);
        }
    });
</script>

<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("custodiaModal", () => ({
            open: false,
            ubicacion: "",
            fecha: "",
            ticketId: "{{ $id }}",
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

            init() {
                // Estado inicial desde backend
                fetch(`/tickets/${this.ticketId}/custodia`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.success && data.es_custodia === 1) {
                            document.getElementById('EsCustonia').checked = true;
                        }
                    });

                // 👉 Detectar input erma y habilitar el switch en tiempo real
                const ermaInput = document.getElementById("erma");
                const custodiaSwitch = document.getElementById("EsCustonia");
                const custodiaSpan = custodiaSwitch.nextElementSibling;

                const actualizarEstadoSwitch = () => {
                    if (ermaInput.value.trim() !== "") {
                        custodiaSwitch.disabled = false;
                        custodiaSpan.classList.remove("opacity-70", "cursor-not-allowed");
                        custodiaSpan.classList.add("opacity-100", "cursor-pointer");
                    } else {
                        custodiaSwitch.checked = false;
                        custodiaSwitch.disabled = true;
                        custodiaSpan.classList.remove("opacity-100", "cursor-pointer");
                        custodiaSpan.classList.add("opacity-70", "cursor-not-allowed");
                    }
                };

                // Estado inicial y escuchar cambios
                actualizarEstadoSwitch();
                ermaInput.addEventListener("input", actualizarEstadoSwitch);
            },

            toggleCustodia(event) {
                if (event.target.checked) {
                    this.open = true;
                } else {
                    this.quitarCustodia();
                }
            },

            close() {
                this.open = false;
                document.getElementById('EsCustonia').checked = false;
            },

            cancelar() {
                this.open = false;
                document.getElementById('EsCustonia').checked = false;
            },

            guardar() {
                if (!this.ubicacion.trim()) {
                    toastr.error("La ubicación es obligatoria");
                    return;
                }
                if (!this.fecha) {
                    toastr.error("La fecha de ingreso es obligatoria");
                    return;
                }

                const erma = document.getElementById("erma").value.trim();

                fetch(`/tickets/${this.ticketId}/actualizar-custodia`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": this.csrf,
                        },
                        body: JSON.stringify({
                            es_custodia: 1,
                            ubicacion_actual: this.ubicacion,
                            fecha_ingreso_custodia: this.fecha,
                            erma: erma // 👈 se envía junto
                        }),
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            toastr.success("El equipo ha sido puesto en custodia");
                            this.open = false;
                            document.getElementById('EsCustonia').checked = true;

                            if (window.updateModificationLog) {
                                window.updateModificationLog(
                                    "Custodia",
                                    "Desactivada",
                                    `Activada (${this.ubicacion}, ${this.fecha}, ${erma})`
                                );
                            }
                        } else {
                            toastr.warning(data.message || "No se pudo activar custodia");
                            document.getElementById('EsCustonia').checked = false;
                            this.open = false;
                        }
                    })
                    .catch(err => {
                        toastr.error("❌ " + err.message);
                        document.getElementById('EsCustonia').checked = false;
                        this.open = false;
                    });
            },


            quitarCustodia() {
                fetch(`/tickets/${this.ticketId}/actualizar-custodia`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": this.csrf,
                        },
                        body: JSON.stringify({
                            es_custodia: 0,
                            fecha_devolucion: new Date().toISOString().split("T")[0],
                        }),
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            toastr.success("Custodia desactivada correctamente");
                            document.getElementById('EsCustonia').checked = false;

                            if (window.updateModificationLog) {
                                window.updateModificationLog(
                                    "Custodia",
                                    "Activada",
                                    "Desactivada"
                                );
                            }
                        } else {
                            throw new Error("Error al quitar custodia");
                        }
                    })
                    .catch(err => {
                        toastr.error("❌ " + err.message);
                        document.getElementById('EsCustonia').checked = true;
                    });
            },
        }));
    });
</script>







<!-- Nueva Card: Historial de Estados -->
<div id="estadosCard" class="mt-4 p-4">
    <span class="text-sm sm:text-lg font-semibold mb-2 sm:mb-4 badge bg-success"
        style="background-color: {{ $colorEstado }};">Historial de Estados</span>
    <!-- Tabla con scroll horizontal -->



    <!-- Contenedor de Estados -->
    <div class="mt-3 overflow-x-auto">
        <div id="draggableContainer" class="flex space-x-2 w-max">
            @foreach ($estadosFlujo as $estado)
                <div class="estado-button min-w-[120px] sm:min-w-[140px] px-4 py-2 rounded-lg cursor-pointer text-white text-center shadow-md"
                    style="background-color: {{ $estado->color }}; color: black;"
                    data-state-description="{{ $estado->descripcion }}">
                    {{ $estado->descripcion }}
                </div>
            @endforeach
        </div>
    </div>



    <!-- Div para mostrar la última modificación (Responsive) -->
    <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:gap-2">
        <span class="text-sm sm:text-base font-medium text-gray-700 dark:text-white">
            Última modificación:
        </span>
        <span id="ultimaModificacion"
            class="bg-gray-100 dark:bg-gray-700 px-3 py-1.5 border border-gray-300 dark:border-gray-600 
               rounded-md text-gray-800 dark:text-white text-xs sm:text-sm w-full sm:w-auto text-center sm:text-left">
        </span>
    </div>



</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ticketId = "{{ $ticket->idTickets }}"; // ID del ticket
        const rowsPerPage = 15;
        let currentPage = 1;

        function cargarEstados() {
            fetch(`/ticket/${ticketId}/estados`)
                .then(response => response.json())
                .then(data => {
                    const estadosTableBody = document.getElementById("estadosTableBody");
                    estadosTableBody.innerHTML = "";

                    if (Array.isArray(data.estadosFlujo)) {
                        const estados = data.estadosFlujo;
                        renderTable(estados, currentPage);
                        setupPagination(estados.length);
                    } else {
                        console.error('Respuesta inválida:', data.estadosFlujo);
                    }
                })
                .catch(error => console.error('Error cargando estados:', error));
        }

        function renderTable(estados, page) {
            const estadosTableBody = document.getElementById("estadosTableBody");
            estadosTableBody.innerHTML = "";

            const start = (page - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            const estadosPaginados = estados.slice(start, end);

            estadosPaginados.forEach(ticketFlujo => {
                const estado = ticketFlujo.estado_descripcion;
                const usuario = ticketFlujo.usuario_nombre;

                const row = document.createElement("tr");

                const estadoCell = document.createElement("td");
                estadoCell.classList.add("px-4", "py-2", "text-center", "text-black");
                estadoCell.style.backgroundColor = ticketFlujo.estado_color;
                estadoCell.textContent = estado;

                const usuarioCell = document.createElement("td");
                usuarioCell.classList.add("px-4", "py-2", "text-center", "text-black");
                usuarioCell.textContent = usuario ? usuario : 'Sin Nombre';
                usuarioCell.style.backgroundColor = ticketFlujo.estado_color;

                const fechaCell = document.createElement("td");
                fechaCell.classList.add("px-4", "py-2", "text-center", "text-black");
                fechaCell.textContent = ticketFlujo.fecha_creacion;
                fechaCell.style.backgroundColor = ticketFlujo.estado_color;

                const masCell = document.createElement("td");
                masCell.classList.add("px-4", "py-2", "text-center", "space-x-2");
                masCell.style.backgroundColor = ticketFlujo.estado_color;

                // Botón "Más" ⋮
                const masBtn = document.createElement("button");
                masBtn.classList.add("toggle-comment", "px-3", "py-1", "rounded", "bg-gray-300");
                masBtn.textContent = "⋮";
                masBtn.dataset.flujoId = ticketFlujo.idTicketFlujo;

                // Botón "Guardar" ✔
                const saveIconBtn = document.createElement("button");
                saveIconBtn.classList.add("save-comment", "px-3", "py-1", "rounded", "bg-success",
                    "text-white");
                saveIconBtn.dataset.flujoId = ticketFlujo.idTicketFlujo;
                saveIconBtn.innerHTML = "✔";

                // Botón "Eliminar" ❌
                const deleteBtn = document.createElement("button");
                deleteBtn.classList.add("delete-flujo", "px-3", "py-1", "rounded", "bg-red-500",
                    "text-white");
                deleteBtn.textContent = "❌";
                deleteBtn.dataset.flujoId = ticketFlujo.idTicketFlujo;

                // Botón "Relacionar" 🔗
                const relacionarBtn = document.createElement("button");
                relacionarBtn.classList.add("relacionar-flujo", "px-3", "py-1", "rounded",
                    "bg-blue-500", "text-white");
                relacionarBtn.textContent = "🔗";
                relacionarBtn.dataset.flujoId = ticketFlujo.idTicketFlujo;

                masCell.appendChild(masBtn);
                masCell.appendChild(saveIconBtn);
                masCell.appendChild(deleteBtn);
                masCell.appendChild(relacionarBtn); // 👉 lo agregas al final



                row.appendChild(estadoCell);
                row.appendChild(usuarioCell);
                row.appendChild(fechaCell);
                row.appendChild(masCell);
                estadosTableBody.appendChild(row);

                // Fila oculta para comentario
                const commentRow = document.createElement("tr");
                commentRow.classList.add("hidden");
                const commentCell = document.createElement("td");
                commentCell.setAttribute("colspan", "4");
                commentCell.classList.add("p-4");
                commentCell.style.backgroundColor = ticketFlujo.estado_color;

                const textArea = document.createElement("textarea");
                textArea.classList.add("w-full", "p-2", "rounded", "border", "border-black");
                textArea.textContent = ticketFlujo.comentarioflujo;
                textArea.placeholder = "Escribe un comentario...";
                textArea.style.backgroundColor = ticketFlujo.estado_color;

                commentCell.appendChild(textArea);
                commentRow.appendChild(commentCell);

                estadosTableBody.appendChild(commentRow);
            });

            agregarEventosComentarios();
        }

        function setupPagination(totalRows) {
            const paginationContainer = document.getElementById("paginationControls");
            paginationContainer.innerHTML = "";

            const totalPages = Math.ceil(totalRows / rowsPerPage);

            if (totalPages > 1) {
                const prevBtn = document.createElement("button");
                prevBtn.textContent = "Anterior";
                prevBtn.classList.add("px-4", "py-2", "bg-gray-300", "rounded", "mx-1");
                prevBtn.disabled = currentPage === 1;
                prevBtn.addEventListener("click", () => {
                    if (currentPage > 1) {
                        currentPage--;
                        cargarEstados();
                    }
                });

                const nextBtn = document.createElement("button");
                nextBtn.textContent = "Siguiente";
                nextBtn.classList.add("px-4", "py-2", "bg-gray-300", "rounded", "mx-1");
                nextBtn.disabled = currentPage === totalPages;
                nextBtn.addEventListener("click", () => {
                    if (currentPage < totalPages) {
                        currentPage++;
                        cargarEstados();
                    }
                });

                paginationContainer.appendChild(prevBtn);
                paginationContainer.appendChild(nextBtn);
            }
        }

        function agregarEventosComentarios() {
            document.querySelectorAll('.toggle-comment').forEach(button => {
                button.addEventListener('click', function() {
                    let row = this.closest('tr').nextElementSibling;
                    row.classList.toggle('hidden');
                });
            });

            document.querySelectorAll('.save-comment').forEach(button => {
                button.addEventListener('click', function() {
                    let flujoId = this.dataset.flujoId;
                    let row = this.closest('tr').nextElementSibling;
                    let textArea = row.querySelector("textarea");
                    let comentario = textArea.value;

                    fetch(`/ticket/${ticketId}/ticketflujo/${flujoId}/update`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute("content")
                            },
                            body: JSON.stringify({
                                comentario
                            })
                        })
                        .then(response => response.json())
                        .then(result => {
                            if (result.success) {
                                toastr.success("Estado actualizado correctamente.");
                            } else {
                                toastr.error("Error al actualizar el estado.");
                            }
                        })
                        .catch(error => console.error("Error al actualizar:", error));
                });
            });

            document.querySelectorAll('.delete-flujo').forEach(button => {
                button.addEventListener('click', function() {
                    const flujoId = this.dataset.flujoId;

                    if (confirm("¿Estás seguro que quieres eliminar este estado de flujo?")) {
                        fetch(`/ticketflujo/${flujoId}/eliminar`, {
                                method: "DELETE",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute(
                                        "content")
                                }
                            })
                            .then(response => response.json())
                            .then(result => {
                                if (result.success) {
                                    toastr.success(
                                        "Estado de flujo eliminado correctamente.");
                                    cargarEstados();
                                } else {
                                    toastr.error("Error al eliminar el estado de flujo.");
                                }
                            })
                            .catch(error => {
                                console.error("Error al eliminar:", error);
                                toastr.error("Error inesperado al eliminar.");
                            });
                    }
                });
            });

            document.querySelectorAll('.relacionar-flujo').forEach(button => {
                button.addEventListener('click', function() {
                    const flujoId = this.dataset.flujoId;

                    if (confirm("¿Deseas relacionar este estado de flujo con el ticket?")) {
                        fetch(`/ticket/${ticketId}/relacionarflujo`, {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute(
                                        "content")
                                },
                                body: JSON.stringify({
                                    flujoId: flujoId
                                })
                            })
                            .then(response => response.json())
                            .then(result => {
                                if (result.success) {
                                    toastr.success(
                                        "Estado de flujo relacionado con el ticket.");
                                    cargarEstados();
                                } else {
                                    toastr.error("No se pudo relacionar el flujo.");
                                }
                            })
                            .catch(error => {
                                console.error("Error al relacionar flujo:", error);
                                toastr.error("Error inesperado al relacionar.");
                            });
                    }
                });
            });

        }

        cargarEstados();
        setInterval(cargarEstados, 30000);
    });
</script>







<!-- Agregar Axios desde un CDN -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        console.log("DOM completamente cargado y analizado");

        $(document).ready(function() {
            console.log("Inicializando Select2 para todos los .select2");
            $('.select2').each(function() {
                const $select = $(this);
                if ($select.hasClass("select2-hidden-accessible")) {
                    $select.select2('destroy');
                }
                $select.select2({
                    placeholder: 'Seleccionar una opción',
                    allowClear: true,
                    width: 'resolve',
                    dropdownParent: $select.parent() // por si está en un modal
                });
            });
        });

        // Inicializar Flatpickr en "Fecha de Compra"
        console.log("Inicializando Flatpickr para fechaCompra");
        flatpickr("#fechaCompra", {
            dateFormat: "Y-m-d",
            allowInput: true
        });

        // Inicializar Flatpickr en "Fecha de Creación"
        console.log("Inicializando Flatpickr para fechaCreacion");
        flatpickr("#fechaCreacion", {
            enableTime: true,
            dateFormat: "Y-m-d H:i:S",
            time_24hr: true,
            allowInput: true
        });

        // Función para formatear la fecha
        function formatDate(fecha) {
            console.log("Formateando fecha:", fecha);
            const año = fecha.getFullYear();
            const mes = (fecha.getMonth() + 1).toString().padStart(2, "0");
            const dia = fecha.getDate().toString().padStart(2, "0");
            let horas = fecha.getHours();
            const minutos = fecha.getMinutes().toString().padStart(2, "0");
            const ampm = horas >= 12 ? "PM" : "AM";
            horas = horas % 12 || 12;
            return `${año}-${mes}-${dia} ${horas}:${minutos} ${ampm}`;
        }

        $(document).ready(function() {
            console.log("jQuery document ready");
            // Obtener el idTickets de la variable de Blade
            const idTickets = "{{ $orden->idTickets }}";
            console.log("idTickets:", idTickets);

            // Llamar al backend para obtener la última modificación
            console.log("Iniciando AJAX para obtener última modificación");
            $.ajax({
                url: '/ultima-modificacion/' + idTickets,
                method: 'GET',
                success: function(response) {
                    console.log("Respuesta AJAX recibida:", response);
                    if (response.success) {
                        const ultimaModificacion = response.ultima_modificacion;
                        console.log("Última modificación encontrada:", ultimaModificacion);

                        const fechaUltimaModificacion = formatDate(new Date(
                            ultimaModificacion.created_at));
                        console.log("Fecha formateada:", fechaUltimaModificacion);

                        const usuarioUltimaModificacion = ultimaModificacion.usuario;
                        const campoUltimaModificacion = ultimaModificacion.campo;
                        const oldValueUltimaModificacion = ultimaModificacion.valor_antiguo;
                        const newValueUltimaModificacion = ultimaModificacion.valor_nuevo;

                        console.log("Actualizando UI con última modificación");
                        document.getElementById('ultimaModificacion').textContent =
                            `${fechaUltimaModificacion} por ${usuarioUltimaModificacion}: Se modificó ${campoUltimaModificacion} de "${oldValueUltimaModificacion}" a "${newValueUltimaModificacion}"`;

                    } else {
                        console.log("No se encontraron modificaciones previas");
                        document.getElementById('ultimaModificacion').textContent =
                            "No hay modificaciones previas.";
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al obtener la última modificación:', error, xhr);
                }
            });
        });

        // Función para actualizar el log de modificación cuando se haga un cambio
        window.updateModificationLog = function(field, oldValue, newValue) {
            console.log("Actualizando log de modificación:", {
                field,
                oldValue,
                newValue
            });

            // 🔹 Normalizar valores vacíos a "N/A"
            oldValue = oldValue && oldValue.trim() !== "" ? oldValue : "N/A";
            newValue = newValue && newValue.trim() !== "" ? newValue : "N/A";

            const usuario = "{{ auth()->user()->Nombre }}";
            const fecha = new Date().toLocaleString();
            const idTickets = "{{ $orden->idTickets }}";

            document.getElementById('ultimaModificacion').textContent =
                `${fecha} por ${usuario}: Se modificó ${field} de "${oldValue}" a "${newValue}"`;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            $.ajax({
                url: '/guardar-modificacion/' + idTickets,
                method: 'POST',
                data: {
                    field,
                    oldValue,
                    newValue,
                    usuario,
                    _token: csrfToken
                },
                success: function(response) {
                    console.log('Modificación guardada correctamente:', response);
                },
                error: function(xhr, status, error) {
                    console.error('Error al guardar la modificación:', error, xhr);
                }
            });
        };



        // Pasa los estados de flujo desde Blade a JavaScript
        const estadosFlujo = @json($estadosFlujo);
        console.log("Estados de flujo cargados:", estadosFlujo);
        // Mostrar idEstadflujo actual
        const idEstadflujo = @json($idEstadflujo);
        console.log("idEstadflujo actual:", idEstadflujo);
        // Función para obtener el ID del estado a partir de la descripción
        function getStateId(stateDescription) {
            console.log("Buscando ID para estado:", stateDescription);
            const estado = estadosFlujo.find(e => e.descripcion === stateDescription);
            return estado ? estado.idEstadflujo : 0;
        }

        // Selecciona todos los botones de estado
        const estadoElements = document.querySelectorAll(".estado-button");
        console.log("Botones de estado encontrados:", estadoElements.length);

        estadoElements.forEach(function(estadoElement) {
            estadoElement.addEventListener("click", function() {
                const stateDescription = estadoElement.dataset.stateDescription;
                console.log("Botón de estado clickeado:", stateDescription);

                const estadoId = getStateId(stateDescription);
                console.log("ID del estado encontrado:", estadoId);

                if (estadoId !== 0) {
                    const usuario = "{{ auth()->user()->id }}";
                    const fecha = formatDate(new Date());
                    const ticketId = "{{ $ticket->idTickets }}";
                    console.log("Datos para actualización:", {
                        usuario,
                        fecha,
                        ticketId
                    });

                    let rowClasses = "";
                    if (estadoId === 1) {
                        rowClasses = "bg-primary/20 border-primary/20";
                    } else if (estadoId === 2) {
                        rowClasses = "bg-secondary/20 border-secondary/20";
                    } else if (estadoId === 3) {
                        rowClasses = "bg-success/20 border-success/20";
                    }
                    console.log("Clases CSS para fila:", rowClasses);

                    const newRow = document.createElement("tr");
                    newRow.className = rowClasses;
                    newRow.innerHTML = `
                        <td class="px-4 py-2 text-center">${stateDescription}</td>
                        <td class="px-4 py-2 text-center">${usuario}</td>
                        <td class="px-4 py-2 text-center">${fecha}</td>
                    `;
                    console.log("Nueva fila creada:", newRow);
                    document.getElementById("estadosTableBody").appendChild(newRow);

                    console.log("Enviando solicitud para guardar estado");
                    axios.post("{{ route('guardarEstado') }}", {
                            idTicket: ticketId,
                            idEstadflujo: estadoId,
                            idUsuario: usuario,
                        })
                        .then(response => {
                            console.log("Respuesta del servidor:", response);
                            console.log("Estado guardado exitosamente");
                            location.reload();
                            document.getElementById('ultimaModificacion').textContent =
                                `${fecha} por ${usuario}: Se modificó Estado a "${stateDescription}"`;
                        })
                        .catch(error => {
                            console.error("Error al guardar el estado", error);
                        });
                } else {
                    console.error("Estado no encontrado");
                }
            });
        });

        function reinitializeDraggable(element) {
            console.log("Reinicializando elemento draggable:", element);
            element.setAttribute("draggable", "true");
            element.addEventListener("dragstart", function(e) {
                console.log("Dragstart en elemento:", this);
                e.dataTransfer.setData("text/plain", this.dataset.state);
            });
        }

        // Solución para el error dropZone is not defined
        const dropZone = document.getElementById('dropZone');
        if (dropZone) {
            dropZone.addEventListener("click", function(e) {
                console.log("Click en dropZone:", e.target);
                if (e.target.classList.contains("delete-state")) {
                    console.log("Eliminando estado");
                    const row = e.target.closest("tr");
                    const state = row.querySelector("td").textContent.trim();
                    console.log("Estado a eliminar:", state);
                    row.remove();
                    if (!document.querySelector("#draggableContainer .draggable-state[data-state='" +
                            state + "']")) {
                        console.log("Recreando elemento draggable para:", state);
                        const container = document.getElementById("draggableContainer");
                        const newDraggable = document.createElement("div");
                        let colorClass = "";
                        if (state === "Recojo") {
                            colorClass = "bg-primary/20";
                        } else if (state === "Coordinado") {
                            colorClass = "bg-secondary/20";
                        } else if (state === "Operativo") {
                            colorClass = "bg-success/20";
                        }
                        newDraggable.className =
                            `draggable-state ${colorClass} px-3 py-1 rounded cursor-move`;
                        newDraggable.dataset.state = state;
                        newDraggable.textContent = state;
                        reinitializeDraggable(newDraggable);
                        container.appendChild(newDraggable);
                    }
                }
            });
        } else {
            console.warn("Elemento dropZone no encontrado en el DOM");
        }

        // Solo inicializa los valores iniciales una vez
        function initializeFieldValues() {
            document.querySelectorAll('#tuFormulario input, #tuFormulario textarea, #tuFormulario select')
                .forEach(field => {
                    if (field.type !== 'hidden') {
                        field.dataset.oldValue = field.value;
                    }
                });
        }

        // Guardar cambios al salir del campo (input/textarea con blur)
        document.addEventListener('blur', function(e) {
            const field = e.target;
            if (!field.matches('#tuFormulario input[type="text"], #tuFormulario textarea')) return;

            const oldVal = field.dataset.oldValue || '';
            const newVal = field.value;

            if (oldVal !== newVal) {
                const label = document.querySelector(`label[for="${field.id}"]`);
                const fieldLabel = label ? label.textContent.trim() : field.name || field.id;

                console.log("Cambio detectado en:", fieldLabel, oldVal, "=>", newVal);

                updateModificationLog(fieldLabel, oldVal, newVal);
                field.dataset.oldValue = newVal; // actualizar valor base
            }
        }, true);

        // Guardar cambios en selects
        document.addEventListener('change', function(e) {
            const field = e.target;
            if (!field.matches('#tuFormulario select')) return;

            const oldVal = field.dataset.oldValue || '';
            const newVal = field.options[field.selectedIndex]?.text || '';

            if (oldVal !== newVal) {
                const label = document.querySelector(`label[for="${field.id}"]`);
                const fieldLabel = label ? label.textContent.trim() : field.name || field.id;
                updateModificationLog(fieldLabel, oldVal, newVal);
                field.dataset.oldValue = newVal;
            }
        }, true);


        // Inicialización
        initializeFieldValues();

    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let clientesCargados = false; // Variable para verificar si los clientes ya fueron cargados
        let marcasCargadas = false; // Flag para verificar si las marcas ya han sido cargadas
        // let tiendasCargadas = false; // Flag para verificar si las tiendas ya han sido cargadas
        // Reemplaza todas las inicializaciones de Select2 con esta versión más robusta
        function initializeSelect2(selector) {
            $(selector).each(function() {
                const $select = $(this);

                if ($select.hasClass("select2-hidden-accessible")) {
                    $select.select2('destroy');
                }

                $select.select2({
                    placeholder: 'Seleccionar opción',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $select.parent()
                });

                // 👉 Escuchar cambios de Select2
                $select.on('select2:select select2:clear', function(e) {
                    let field = this;
                    let oldVal = field.dataset.oldValue || '';
                    let newVal = field.options[field.selectedIndex] ?
                        field.options[field.selectedIndex].text :
                        '';

                    if (oldVal !== newVal) {
                        let fieldLabel = document.querySelector('label[for="' + field.id +
                            '"]');
                        let labelText = fieldLabel ? fieldLabel.textContent.trim() : field
                            .name || field.id;

                        updateModificationLog(labelText, oldVal, newVal);
                        field.dataset.oldValue = newVal;
                    }
                });
            });
        }


        // Inicializar una sola vez al cargar la página
        $(document).ready(function() {
            initializeSelect2('.select2');
        });
        // console.log(cargarClientesGenerales);



        // Función para cargar todas las marcas desde el servidor

        // Función para cargar todas las marcas
        $(document).ready(function() {
            // Inicialización de elementos
            const marcaSelect = $('#idMarca');
            const clienteGeneralSelect = $('#idClienteGeneral');
            const preloadElement = $('#preload');
            const marcaGuardada = '{{ $orden->idMarca ?? '' }}';

            // Función optimizada para cargar marcas
            function cargarMarcas(url) {
                preloadElement.show();
                marcaSelect.prop('disabled', true).empty().append(
                    '<option value="" disabled>Seleccionar Marca</option>');

                $.get(url)
                    .done(function(data) {
                        if (data && data.length > 0) {
                            data.forEach(marca => {
                                const isSelected = marca.idMarca == marcaGuardada;
                                marcaSelect.append(new Option(marca.nombre, marca.idMarca,
                                    false, isSelected));
                            });
                        } else {
                            marcaSelect.append(
                                '<option value="" disabled>No hay marcas disponibles</option>');
                        }

                        // Reiniciar Select2
                        if (marcaSelect.hasClass('select2-hidden-accessible')) {
                            marcaSelect.select2('destroy');
                        }

                        marcaSelect.select2({
                            placeholder: 'Seleccionar Marca',
                            width: '100%'
                        });

                        // Seleccionar la marca guardada si existe
                        if (marcaGuardada) {
                            marcaSelect.val(marcaGuardada).trigger('change');
                        }
                    })
                    .fail(function(error) {
                        console.error('Error al cargar marcas:', error);
                        marcaSelect.append(
                            '<option value="" disabled>Error al cargar marcas</option>');
                    })
                    .always(function() {
                        marcaSelect.prop('disabled', false);
                        preloadElement.hide();
                    });
            }

            // Función para cargar todas las marcas
            function cargarTodasLasMarcas() {
                cargarMarcas('/check-marcas');
            }

            // Función para cargar marcas por cliente general
            function cargarMarcasPorClienteGeneral(clienteGeneralId) {
                if (!clienteGeneralId) {
                    cargarTodasLasMarcas();
                    return;
                }
                cargarMarcas(`/marcas-por-cliente-general/${clienteGeneralId}`);
            }

            // Evento para cambio de Cliente General
            clienteGeneralSelect.on('change', function() {
                const clienteGeneralId = $(this).val();
                cargarMarcasPorClienteGeneral(clienteGeneralId);
            });

            // Carga inicial
            const clienteGeneralId = clienteGeneralSelect.val();
            if (clienteGeneralId) {
                cargarMarcasPorClienteGeneral(clienteGeneralId);
            } else {
                cargarTodasLasMarcas();
            }
        });


        // Cargar todas las marcas inicialmente si no hay cliente general seleccionado
        window.onload = function() {
            let clienteGeneralId = document.getElementById('idClienteGeneral').value;
            if (!clienteGeneralId) {
                cargarTodasLasMarcas
                    (); // Si no hay cliente general seleccionado al cargar la página, cargamos todas las marcas
            }
        };

        // Modifica el código de carga de tiendas así:
        $('#idCliente').on('change', function() {
            const clienteId = $(this).val();
            const tiendaSelect = $('#idTienda');
            const container = $('#selectTiendaContainer');

            if (!clienteId) {
                container.hide();
                tiendaSelect.val('').trigger('change');
                return;
            }

            // Mostrar loading
            container.show();
            tiendaSelect.prop('disabled', true).empty().append('<option value="">Cargando...</option>');

            // Primero obtener datos del cliente
            $.get(`/api/cliente/${clienteId}`)
                .then(clienteData => {
                    const tipoDoc = clienteData.idTipoDocumento;
                    const esTienda = clienteData.esTienda;

                    // Actualizar dirección si es tipo documento 8
                    if (tipoDoc == 8) {
                        $('#direccion').val(clienteData.direccion || '');
                    }

                    // Determinar endpoint para tiendas
                    const endpoint = (tipoDoc == 8 || esTienda == 0) ?
                        '/api/tiendas' :
                        `/api/cliente/${clienteId}/tiendas`;

                    // Cargar tiendas
                    return $.get(endpoint);
                })
                .then(tiendasData => {
                    tiendaSelect.empty().append(
                        '<option value="" disabled selected>Seleccionar Tienda</option>');

                    if (tiendasData?.length > 0) {
                        tiendasData.forEach(tienda => {
                            tiendaSelect.append(new Option(tienda.nombre, tienda.idTienda));
                        });

                        // Seleccionar tienda actual si existe
                        @if ($orden->idTienda)
                            tiendaSelect.val('{{ $orden->idTienda }}').trigger('change');
                        @endif
                    } else {
                        tiendaSelect.append('<option value="" disabled>No hay tiendas</option>');
                    }
                })
                .fail(error => {
                    console.error('Error:', error);
                    tiendaSelect.empty().append(
                        '<option value="" disabled>Error al cargar</option>');
                })
                .always(() => {
                    tiendaSelect.prop('disabled', false);
                    initializeSelect2('#idTienda'); // Re-inicializar Select2
                });
        });


        $('#idTienda').on('change', function() {
            const tiendaId = $(this).val();
            const clienteId = $('#idCliente').val();

            // Solo actualizar dirección si no es tipo documento 8
            $.get(`/api/cliente/${clienteId}`)
                .then(clienteData => {
                    if (clienteData.idTipoDocumento != 8 && tiendaId) {
                        return $.get(`/api/tienda/${tiendaId}`);
                    }
                    return null;
                })
                .then(tiendaData => {
                    if (tiendaData) {
                        $('#direccion').val(tiendaData.direccion || '');
                    }
                })
                .catch(error => console.error('Error:', error));
        });


        $(document).ready(function() {
            // Inicializar Select2 para todos los selects con clase select2
            $('.select2').select2({
                placeholder: 'Seleccionar opción',
                allowClear: true,
                width: '100%'
            });

            // Manejar cambio en Cliente para cargar Clientes Generales
            $('#idCliente').on('change', function() {
                const clienteId = $(this).val();
                const $clienteGeneralSelect = $('#idClienteGeneral');

                if (!clienteId) {
                    $clienteGeneralSelect.empty().append(
                        '<option value="">Seleccionar Cliente General</option>');
                    $clienteGeneralSelect.val('').trigger('change');
                    return;
                }

                // Mostrar loading
                $clienteGeneralSelect.prop('disabled', true);

                fetch(`/clientes-generales/${clienteId}`)
                    .then(response => response.json())
                    .then(data => {
                        $clienteGeneralSelect.empty().append(
                            '<option value="">Seleccionar Cliente General</option>');

                        data.forEach(clienteGeneral => {
                            $clienteGeneralSelect.append(
                                `<option value="${clienteGeneral.idClienteGeneral}">${clienteGeneral.descripcion}</option>`
                            );
                        });

                        // Seleccionar el valor actual si existe
                        @if ($orden->clienteGeneral)
                            $clienteGeneralSelect.val(
                                    '{{ $orden->clienteGeneral->idClienteGeneral }}')
                                .trigger('change');
                        @endif

                        $clienteGeneralSelect.prop('disabled', false);
                    })
                    .catch(error => {
                        console.error('Error al cargar clientes generales:', error);
                        $clienteGeneralSelect.prop('disabled', false);
                    });
            });

            // Si hay un cliente seleccionado al cargar la página, cargar sus clientes generales
            @if ($orden->cliente)
                $('#idCliente').trigger('change');
            @endif
        });



        // Evento de envío del formulario de cliente
        document.getElementById('clienteForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Evitar el envío normal del formulario

            let formData = new FormData(this); // Obtener los datos del formulario
            console.log('Datos del formulario:', Object.fromEntries(formData
                .entries())); // Ver los datos del formulario

            fetch('/guardar-cliente', {
                    method: 'POST',
                    body: formData, // Enviar los datos del formulario
                })
                .then((response) => response.json()) // Parsear la respuesta como JSON
                .then((data) => {
                    console.log('Respuesta del servidor (JSON):', data); // Verificar la respuesta
                    if (data.errors) {
                        // Mostrar solo el primer error
                        for (let field in data.errors) {
                            toastr.error(data.errors[field][
                                0
                            ]); // Mostrar solo el primer error del campo
                            break; // Salir del bucle después de mostrar el primer error
                        }
                    } else {
                        // Mostrar mensaje de éxito
                        toastr.success(data.message);

                        // Recargar los clientes después de guardar el cliente
                        cargarClientes();

                        // Limpiar el formulario y cerrar el modal si es necesario
                        document.getElementById('clienteForm').reset();
                        openClienteModal = false; // Cerrar el modal si lo tienes
                    }
                })
                .catch((error) => {
                    console.error('Error al guardar el cliente:', error);
                });
        });

        // Evento de envío del formulario de cliente
        document.getElementById('clientGeneralForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Evitar el envío normal del formulario

            let formData = new FormData(this); // Obtener los datos del formulario
            console.log('Datos del formulario:', Object.fromEntries(formData
                .entries())); // Ver los datos del formulario

            fetch('/guardar-cliente-general-smart', {
                    method: 'POST',
                    body: formData, // Enviar los datos del formulario
                })
                .then((response) => response.json()) // Parsear la respuesta como JSON
                .then((data) => {
                    console.log('Respuesta del servidor (JSON):', data); // Verificar la respuesta
                    if (data.errors) {
                        // Mostrar errores si los hay
                        toastr.error(data.errors);
                    } else {
                        // Mostrar mensaje de éxito
                        location.reload();
                        toastr.success(data.message);

                        // Recargar los clientes después de guardar el cliente
                        cargarClientes();

                        // Limpiar el formulario y cerrar el modal si es necesario
                        document.getElementById('clientGeneralForm').reset();
                        openClienteGeneralModal = false; // Cerrar el modal si lo tienes
                    }
                })
                .catch((error) => {
                    console.error('Error al guardar el cliente:', error);
                });
        });

        // Evento de envío del formulario de marca
        document.getElementById('marcaForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Evitar el envío normal del formulario

            let formData = new FormData(this); // Obtener los datos del formulario
            console.log('Datos del formulario:', Object.fromEntries(formData
                .entries())); // Ver los datos del formulario

            fetch('/guardar-marca-smart', {
                    method: 'POST',
                    body: formData, // Enviar los datos del formulario
                })
                .then((response) => response.json()) // Parsear la respuesta como JSON
                .then((data) => {
                    console.log('Respuesta del servidor (JSON):', data); // Verificar la respuesta

                    if (data.errors) {
                        // Mostrar solo el primer error de validación
                        for (let field in data.errors) {
                            toastr.error(data.errors[field][
                                0
                            ]); // Mostrar solo el primer error de cada campo
                            break; // Salir del bucle después de mostrar el primer error
                        }
                    } else if (data.error) {
                        // Si hay un error general (como el que mencionas)
                        toastr.error(data.error); // Mostrar el mensaje de error general
                    } else {
                        // Mostrar mensaje de éxito
                        toastr.success(data.message);

                        // Recargar las marcas después de guardar la marca
                        cargarMarcas();
                        cargarMarcass();

                        // Limpiar el formulario y cerrar el modal si es necesario
                        document.getElementById('marcaForm').reset();
                        openMarcaModal = false; // Cerrar el modal si lo tienes
                    }
                })
                .catch((error) => {
                    console.error('Error al guardar la marca:', error);
                });
        });

        // Evento de envío del marca
        document.getElementById('modeloForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Evitar el envío normal del formulario

            let formData = new FormData(this); // Obtener los datos del formulario
            console.log('Datos del formulario:', Object.fromEntries(formData
                .entries())); // Ver los datos del formulario

            fetch('/guardar-modelo-smart', {
                    method: 'POST',
                    body: formData, // Enviar los datos del formulario
                })
                .then((response) => response.json()) // Parsear la respuesta como JSON
                .then((data) => {
                    console.log('Respuesta del servidor (JSON):', data); // Verificar la respuesta
                    if (data.errors) {
                        // Mostrar errores si los hay
                        toastr.error(data.errors);
                    } else {
                        // Mostrar mensaje de éxito
                        toastr.success(data.message);

                        // Recargar los clientes después de guardar el cliente
                        cargarClientes();

                        // Limpiar el formulario y cerrar el modal si es necesario
                        document.getElementById('modeloForm').reset();
                        openClienteGeneralModal = false; // Cerrar el modal si lo tienes
                    }
                })
                .catch((error) => {
                    console.error('Error al guardar el cliente:', error);
                });
        });
    });
</script>





<script>
    $(document).ready(function() {
        var idOrden = @json($orden->idTickets);

        $('#guardarFallaReportada').on('click', function(e) {
            e.preventDefault(); // Prevenir que se recargue la página

            // Recoger los datos del formulario
            var formData = {
                idCliente: $('#idCliente').val(),
                idClienteGeneral: $('#idClienteGeneral').val(),
                idTienda: $('#idTienda').val(),
                direccion: $('input[name="direccion"]').val(),
                idMarca: $('#idMarca').val(),
                idModelo: $('#idModelo').val(),
                serie: $('input[name="serie"]').val(),
                fechaCompra: $('input[name="fechaCompra"]').val(),
                fechaCreacion: $('input[name="fechaCreacion"]').val(), // <--- Aquí la agregas
                fallaReportada: $('textarea[name="fallaReportada"]').val(),
                erma: $('input[name="erma"]').val(),
            };

            // Mostrar los datos del formulario en la consola
            console.log("Datos del formulario:", formData);

            // Verificar si algún campo obligatorio está vacío, excluyendo "erma"
            for (var key in formData) {
                if (key !== 'erma' && (formData[key] === '' || formData[key] === null)) {
                    toastr.error('El campo "' + key +
                        '" está vacío. Por favor, complete todos los campos.');
                    return; // Detener el envío si algún campo obligatorio está vacío
                }
            }

            // Validar que la fecha de compra no sea en el futuro
            var fechaCompra = new Date(formData.fechaCompra);
            var fechaActual = new Date();

            // Eliminar la hora de las fechas para compararlas correctamente
            fechaActual.setHours(0, 0, 0, 0);
            fechaCompra.setHours(0, 0, 0, 0);

            if (fechaCompra > fechaActual) {
                toastr.error('La fecha de compra no puede ser una fecha futura.');
                return; // Detener el envío si la fecha de compra es en el futuro
            }

            // Validar el campo "serie" (permitir letras y números, pero no el signo -)
            var serie = formData.serie;
            var serieRegex =
                /^[a-zA-Z0-9]+$/; // Expresión regular que permite solo letras y números, pero no el signo -

            if (!serie || !serieRegex.test(serie)) {
                toastr.error(
                    'El número de serie no puede contener caracteres especiales o un signo "-".');
                return; // Detener el envío si el número de serie no es válido
            }

            // Obtener el token CSRF desde la página
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            console.log("Token CSRF obtenido:",
                csrfToken); // Asegúrate de que el token se obtiene correctamente

            // Verificar si el token CSRF es válido
            if (!csrfToken) {
                console.error("Token CSRF no encontrado.");
                toastr.error('Hubo un error con el CSRF token.');
                return; // Detener el envío si el CSRF token no es válido
            }

            // Enviar datos por AJAX
            $.ajax({
                url: '/actualizar-orden/' + idOrden, // Pasar el id de la orden en la URL
                method: 'PUT', // Usar PUT para la actualización
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Agregar el token CSRF
                },
                success: function(response) {
                    console.log("Respuesta del servidor:", response);

                    // Mostrar un mensaje de éxito con Toastr
                    toastr.success('Orden actualizada con éxito');
                },
                error: function(xhr, status, error) {
                    console.log("Error al actualizar:", error);
                    console.log("Detalles de la respuesta del error:", xhr.responseText);

                    // Mostrar un mensaje de error con Toastr
                    toastr.error('Hubo un error al actualizar la orden');
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        // Elementos del DOM
        const marcaSelect = $('#idMarca');
        const modeloSelect = $('#idModelo');
        const preloadModelo = $('#preload-modelo');

        // Modelo guardado (si existe)
        const modeloGuardado = '{{ $orden->idModelo ?? '' }}';
        const marcaGuardada = '{{ $orden->idMarca ?? '' }}';

        // Función para cargar modelos
        function cargarModelos(marcaId) {
            if (!marcaId) {
                resetearModeloSelect();
                return;
            }

            mostrarLoadingModelo();

            $.get(`/get-modelos/${marcaId}`)
                .done(function(data) {
                    llenarSelectModelos(data);
                })
                .fail(function(error) {
                    console.error('Error al cargar modelos:', error);
                    mostrarErrorModelos();
                })
                .always(function() {
                    ocultarLoadingModelo();
                });
        }

        // Función para resetear el select de modelos
        function resetearModeloSelect() {
            modeloSelect.empty().append('<option value="" disabled selected>Seleccionar Modelo</option>');
            reiniciarSelect2();
        }

        // Función para mostrar loading
        function mostrarLoadingModelo() {
            modeloSelect.prop('disabled', true);
            preloadModelo.show();
        }

        // Función para ocultar loading
        function ocultarLoadingModelo() {
            modeloSelect.prop('disabled', false);
            preloadModelo.hide();
        }

        // Función para llenar el select de modelos
        function llenarSelectModelos(modelos) {
            modeloSelect.empty().append('<option value="" disabled>Seleccionar Modelo</option>');

            if (modelos && modelos.length > 0) {
                modelos.forEach(modelo => {
                    const isSelected = modelo.idModelo == modeloGuardado;
                    modeloSelect.append(new Option(modelo.nombre, modelo.idModelo, false, isSelected));
                });
            } else {
                modeloSelect.append('<option value="" disabled>No hay modelos disponibles</option>');
            }

            reiniciarSelect2();
            seleccionarModeloGuardado();
        }

        // Función para mostrar error
        function mostrarErrorModelos() {
            modeloSelect.empty().append('<option value="" disabled>Error al cargar modelos</option>');
            reiniciarSelect2();
        }

        // Función para reiniciar Select2
        function reiniciarSelect2() {
            if (modeloSelect.hasClass('select2-hidden-accessible')) {
                modeloSelect.select2('destroy');
            }
            modeloSelect.select2({
                placeholder: 'Seleccionar Modelo',
                width: '100%'
            });
        }

        // Función para seleccionar modelo guardado
        function seleccionarModeloGuardado() {
            if (modeloGuardado) {
                modeloSelect.val(modeloGuardado).trigger('change');
            }
        }

        // Evento al cambiar marca
        marcaSelect.on('change', function() {
            const marcaId = $(this).val();
            cargarModelos(marcaId);
        });

        // Carga inicial si hay marca seleccionada
        if (marcaGuardada) {
            cargarModelos(marcaGuardada);
        }
    });
</script>
