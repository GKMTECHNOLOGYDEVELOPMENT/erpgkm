<x-layout.default>
    <link rel="stylesheet" href="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.css">
    <style>
        .tab-btn {
            font-weight: 600;
            color: #6B7280;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
            cursor: pointer;
        }

        .active-tab {
            color: #3B82F6;
            border-color: #3B82F6;
        }

        .tab-content {
            transition: all 0.3s;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        input:focus,
        select:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            border-color: #3B82F6;
        }

        .btn-hover:hover {
            transform: translateY(-1px);
        }

        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Estilo para tabs responsivos */
        .tabs-container {
            overflow-x: auto;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }

        /* Estilo para tarjetas de observaciones */
        .observacion-card {
            transition: all 0.3s ease;
        }

        .observacion-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        /* Estilo para la línea de tiempo del cronograma */
        .timeline-item {
            position: relative;
            padding-left: 2rem;
        }

        .timeline-item:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 2px;
            height: 100%;
            background-color: #e2e8f0;
        }

        .timeline-dot {
            position: absolute;
            left: -5px;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #3b82f6;
        }
    </style>
    <div class="max-w-6x2 mx-auto p-8 bg-white rounded-lg shadow-lg">
        {{-- <h2 class="text-4x2 font-bold mb-8 text-gray-800">
            @if ($seguimiento->tipoRegistro == 1)
                Editar Empresa
            @else
                Editar Contacto
            @endif
        </h2> --}}

        {{-- Tabs --}}
        <div class="flex space-x-4 border-b border-gray-200 mb-6 overflow-x-auto">
            <button id="tabEmpresaBtn"
                class="tab-btn px-6 py-3 flex items-center whitespace-nowrap {{ $seguimiento->tipoRegistro == 1 ? 'active-tab' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M3 21h18v-2H3v2zm2-4h2v-2H5v2zm0-4h2v-2H5v2zm0-4h2V7H5v2zm4 8h2v-2H9v2zm0-4h2v-2H9v2zm0-4h2V7H9v2zm4 8h2v-2h-2v2zm0-4h2v-2h-2v2zm0-4h2V7h-2v2zm4 8h2v-6h-2v6zm0-8h2V7h-2v2z" />
                </svg>

                Empresa
            </button>
            <button id="tabContactoBtn"
                class="tab-btn px-6 py-3 flex items-center whitespace-nowrap {{ $seguimiento->tipoRegistro == 2 ? 'active-tab' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                        clip-rule="evenodd" />
                </svg>
                Contacto
            </button>
            <button id="tabProyectosBtn" class="tab-btn px-6 py-3 flex items-center whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                Proyectos/Servicios
            </button>
            <button id="tabCronogramaBtn" class="tab-btn px-6 py-3 flex items-center whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                        clip-rule="evenodd" />
                </svg>
                Cronograma
            </button>
            <button id="tabObservacionesBtn" class="tab-btn px-6 py-3 flex items-center whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                        clip-rule="evenodd" />
                </svg>
                Observaciones
            </button>
        </div>

        {{-- Contenedores de los tabs --}}
        <div class="panel">
            <div id="tabEmpresa" class="tab-content {{ $seguimiento->tipoRegistro == 1 ? '' : 'hidden' }}"></div>
            <div id="tabContacto" class="tab-content {{ $seguimiento->tipoRegistro == 2 ? '' : 'hidden' }}"></div>
            <div id="tabProyectos" class="tab-content hidden"></div>
            <div id="tabCronograma" class="tab-content hidden"></div>
            <div id="tabObservaciones" class="tab-content hidden"></div>
        </div>
    </div>


    <input type="hidden" id="idSeguimientoHidden" value="{{ $seguimiento->idSeguimiento ?? '' }}">



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elementos del DOM
            const tabButtons = {
                empresa: document.getElementById('tabEmpresaBtn'),
                contacto: document.getElementById('tabContactoBtn'),
                proyectos: document.getElementById('tabProyectosBtn'),
                cronograma: document.getElementById('tabCronogramaBtn'),
                observaciones: document.getElementById('tabObservacionesBtn')
            };

            const tabContents = {
                empresa: document.getElementById('tabEmpresa'),
                contacto: document.getElementById('tabContacto'),
                proyectos: document.getElementById('tabProyectos'),
                cronograma: document.getElementById('tabCronograma'),
                observaciones: document.getElementById('tabObservaciones')
            };

            const seguimientoId = {{ $seguimiento->idSeguimiento }};
            const initialTab = @json($seguimiento->tipoRegistro == 1 ? 'empresa' : 'contacto');

            // Event Listeners para todos los tabs
            Object.entries(tabButtons).forEach(([tabName, button]) => {
                button.addEventListener('click', () => loadTab(tabName));
            });

            // Cargar el tab inicial
            loadTab(initialTab, true);

            // Función para cargar un tab
            async function loadTab(tab, isInitialLoad = false) {
                try {
                    // Mostrar estado de carga
                    showLoadingState(tab);

                    // Hacer la petición al servidor
                    const response = await fetch(`/seguimiento/${seguimientoId}/edit-tab?tab=${tab}`);

                    // Verificar primero si la respuesta es JSON
                    let data;
                    try {
                        data = await response.json();
                    } catch (jsonError) {
                        // Si falla el parseo JSON, obtener como texto para diagnóstico
                        const textResponse = await response.text();
                        console.error('Respuesta no JSON:', textResponse.substring(0, 300));
                        throw new Error(
                            `El servidor devolvió un formato inesperado. Estado: ${response.status}`);
                    }

                    // Si hay error en la respuesta (pero es JSON válido)
                    if (!response.ok) {
                        const errorMsg = data.error || `Error ${response.status}: ${response.statusText}`;
                        throw new Error(errorMsg);
                    }

                    // Si no hay contenido HTML
                    if (!data.html) {
                        throw new Error('No se recibió contenido para mostrar');
                    }

                    // Actualizar el contenido del tab
                    updateTabContent(tab, data.html, isInitialLoad);

                } catch (error) {
                    console.error(`Error al cargar el tab ${tab}:`, error);

                    // Mostrar mensaje de error apropiado
                    let errorMessage = error.message;

                    // Mensajes más amigables para diferentes tipos de error
                    if (error.message.includes('formato inesperado')) {
                        errorMessage = 'Error al comunicarse con el servidor';
                    } else if (error.message.includes('No se recibió contenido')) {
                        errorMessage = 'No hay datos disponibles para mostrar';
                    }

                    showErrorState(tab, errorMessage);
                }
            }

            function updateTabContent(tab, html, isInitialLoad) {
                // Ocultar otros tabs de sección secundaria
                tabProyectos.classList.add('hidden');
                tabCronograma.classList.add('hidden');
                tabObservaciones.classList.add('hidden');
                tabProyectosBtn.classList.remove('active-tab');
                tabCronogramaBtn.classList.remove('active-tab');
                tabObservacionesBtn.classList.remove('active-tab');

                if (tab === 'empresa') {
                    tabEmpresa.innerHTML = html;

                    if (!isInitialLoad) {
                        tabEmpresa.classList.remove('hidden');
                        tabContacto.classList.add('hidden');
                        tabEmpresaBtn.classList.add('active-tab');
                        tabContactoBtn.classList.remove('active-tab');
                    }

                    // Ejecutar lógica después de renderizar HTML
                    requestAnimationFrame(() => {
                        if (typeof window.initNoDataTab === 'function') {
                            window.initNoDataTab();
                        }
                    });

                    return;
                }

                if (tab === 'contacto') {
                    tabContacto.innerHTML = html;

                    if (!isInitialLoad) {
                        tabContacto.classList.remove('hidden');
                        tabEmpresa.classList.add('hidden');
                        tabContactoBtn.classList.add('active-tab');
                        tabEmpresaBtn.classList.remove('active-tab');
                    }

                    // Ejecutar lógica después de renderizar HTML
                    requestAnimationFrame(() => {
                        if (typeof window.initNoDataTab === 'function') {
                            window.initNoDataTab();
                        }
                    });

                    return;
                }

                // --- Cronograma / Proyectos / Observaciones ---
                const tabElement = tabContents[tab];
                const tabButton = tabButtons[tab];
                tabElement.innerHTML = html;
                tabElement.classList.remove('hidden');
                tabButton.classList.add('active-tab');

                // Oculta empresa/contacto si aplica
                if (!isInitialLoad) {
                    tabEmpresa.classList.add('hidden');
                    tabContacto.classList.add('hidden');
                    tabEmpresaBtn.classList.remove('active-tab');
                    tabContactoBtn.classList.remove('active-tab');
                }

                // Inicializa Gantt si es cronograma
                if (tab === 'cronograma') {
                    requestAnimationFrame(() => {
                        const el = tabElement.querySelector('#gantt_cronograma');
                        if (el && typeof window.renderCronograma === 'function') {
                            window.renderCronograma(el);
                        }
                    });
                }
            }


            // Función para crear nuevo registro
            function createNew(type) {
                if (type === 'empresa') {
                    // Lógica para crear nueva empresa
                    window.location.href = `/empresasForm/create?seguimiento_id=${seguimientoId}`;
                } else {
                    // Lógica para crear nuevo contacto
                    window.location.href = `/contactosForm/create?seguimiento_id=${seguimientoId}`;
                }
            }

            // Mostrar estado de carga
            function showLoadingState(tab) {
                const loaderHtml = `
                    <div class="flex justify-center items-center h-40">
                        <div class="loader"></div>
                        <p class="ml-3 text-gray-600">Cargando...</p>
                    </div>
                `;

                if (tab === 'empresa') {
                    tabEmpresa.innerHTML = loaderHtml;
                } else {
                    tabContacto.innerHTML = loaderHtml;
                }
            }

            // Mostrar estado de error
            function showErrorState(tab, message) {
                const errorHtml = `
                    <div class="flex justify-center items-center h-40">
                        <div class="text-red-500 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="mt-2">${message}</p>
                            <button onclick="loadTab('${tab}')" class="mt-3 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                Reintentar
                            </button>
                        </div>
                    </div>
                `;

                if (tab === 'empresa') {
                    tabEmpresa.innerHTML = errorHtml;
                } else {
                    tabContacto.innerHTML = errorHtml;
                }
            }

            // Inicializar scripts específicos del tab
            function initTabScripts(tab) {
                if (tab === 'empresa') {
                    // Inicializar scripts para el tab de empresa
                    initEmpresaTabScripts();
                } else {
                    // Inicializar scripts para el tab de contacto
                    initContactoTabScripts();
                }
            }

            // Scripts específicos para el tab de empresa
            function initEmpresaTabScripts() {
                // Aquí puedes inicializar cualquier script necesario para el tab de empresa
                const btnBuscarRuc = document.getElementById('btnBuscarRuc');
                if (btnBuscarRuc) {
                    btnBuscarRuc.addEventListener('click', function() {
                        // Tu lógica para buscar RUC
                    });
                }
            }

            // Scripts específicos para el tab de contacto
            function initContactoTabScripts() {
                // Aquí puedes inicializar cualquier script necesario para el tab de contacto
                const buscarClienteBtn = document.getElementById('buscarClienteBtn');
                if (buscarClienteBtn) {
                    buscarClienteBtn.addEventListener('click', function() {
                        // Tu lógica para buscar cliente
                    });
                }
            }

            // Hacer la función loadTab accesible globalmente para el botón de reintentar
            window.loadTab = loadTab;
        });
    </script>

    <script src="/assets/js/Sortable.min.js"></script>
    <script src="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.js" defer></script>
    <script src="https://cdn.dhtmlx.com/gantt/edge/locale/locale_es.js" defer></script>
    <script src="https://export.dhtmlx.com/gantt/api.js" defer></script>
    <!-- SweetAlert2 (para toasts) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/seguimiento/cronograma.js') }}" defer></script>

    <script src="{{ asset('assets/js/seguimiento/proyectos.js') }}" defer></script>
    <script src="{{ asset('assets/js/seguimiento/create-contact-empresa.js') }}" defer></script>
    <script src="{{ asset('assets/js/seguimiento/notas.js') }}" defer></script>
    <script src="{{ asset('assets/js/areacomercial/actualizarcliente.js') }}"></script>

</x-layout.default>
