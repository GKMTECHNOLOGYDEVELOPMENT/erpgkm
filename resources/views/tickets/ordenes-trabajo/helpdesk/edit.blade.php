<x-layout.default>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        .panel {
            overflow: visible !important;
        }

        .alert {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #28a745;
            color: white;
        }

        .alert-danger {
            background-color: #dc3545;
            color: white;
        }
        
        .skeleton-loading {
            background: linear-gradient(-90deg, #e0e0e0 0%, #f5f5f5 50%, #e0e0e0 100%);
            background-size: 400% 400%;
            animation: skeleton-animation 1.2s ease-in-out infinite;
        }

        @keyframes skeleton-animation {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }
    </style>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-5" x-data="tabs({{ $orden->idTickets }}, {{ $orden->tipoServicio }})">

        <!-- Tabs -->
        <ul
            class="grid grid-cols-4 gap-2 sm:flex sm:flex-wrap sm:justify-center mt-3 mb-5 sm:space-x-3 rtl:space-x-reverse">
            <li>
                <a href="javascript:;"
                    class="p-7 py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] dark:bg-[#191e3a] hover:!bg-success hover:text-white"
                    :class="{ '!bg-success text-white': tab === 'detalle' }" @click="setTab('detalle')">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 2H16M8 2V6M16 2V6M4 6H20M4 6V22H20V6M9 10H15M9 14H15M9 18H12" />
                    </svg>
                    Ticket
                </a>
            </li>
            <li>
                <a href="javascript:;"
                    class="p-7 py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] dark:bg-[#191e3a] hover:!bg-success hover:text-white"
                    :class="{ '!bg-success text-white': tab === 'visitas' }" @click="setTab('visitas')">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 2C8.686 2 6 4.686 6 8c0 5 6 11 6 11s6-6 6-11c0-3.314-2.686-6-6-6zM12 10a2 2 0 100-4 2 2 0 000 4z" />
                    </svg>
                    Coordinación
                </a>
            </li>
            <li>
                <a href="javascript:;"
                    class="p-7 py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] dark:bg-[#191e3a] hover:!bg-success hover:text-white"
                    :class="{ '!bg-success text-white': tab === 'informacion' }" @click="setTab('informacion')">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 18l6-6-6-6M8 6L2 12l6 6M12 2L9 22" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                    Desarrollo
                </a>
            </li>
            <li>
                <a href="javascript:;"
                    class="p-7 py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] dark:bg-[#191e3a] hover:!bg-success hover:text-white"
                    :class="{ '!bg-success text-white': tab === 'firmas' }" @click="setTab('firmas')">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.5 3L21 7.5M3 21l9-9M15 6l6 6M3 21h6l9-9-6-6-9 9v6z" />
                    </svg>
                    Firmas
                </a>
            </li>
        </ul>


        <!-- Contenedor de pestañas dinámico con Spinner -->
        <div class="panel mt-6 p-5 max-w-4x2 mx-auto relative min-h-[300px]">
            <!-- Spinner (se muestra mientras carga) -->
            <div id="tabSpinner"
                class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-100 transition-opacity duration-300">
                <span class="w-10 h-10 animate-spin border-4 border-info border-l-transparent rounded-full"></span>
            </div>

            <!-- Contenido de la pestaña (oculto mientras carga) -->
            <div id="tabContent" x-html="content" class="hidden"></div>
        </div>


    </div>
    <!-- jQuery (debe ir antes de cualquier script que lo use) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('tabs', (ordenId, tipoServicio) => ({
                tab: 'detalle',
                content: '',

                init() {
                    if (!ordenId || !tipoServicio) {
                        console.error("Error: ordenId o tipoServicio no están definidos.");
                        return;
                    }
                    this.loadContent();
                },

                async loadContent() {
                    if (!ordenId || !tipoServicio) return;

                    let basePath = tipoServicio == 1 ? 'soporte' : 'levantamiento';
                    let url = `/ordenes/helpdesk/${ordenId}/${basePath}/${this.tab}`;

                    // 🔥 Mostrar spinner y ocultar contenido al cambiar de pestaña
                    document.getElementById("tabSpinner").classList.remove("hidden");
                    document.getElementById("tabContent").classList.add("hidden");

                    try {
                        const response = await fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (!response.ok) {
                            throw new Error(`Error HTTP: ${response.status}`);
                        }

                        this.content = await response.text();
                    } catch (error) {
                        console.error("Error cargando el contenido:", error);
                        this.content = "<p class='text-red-500'>Error al cargar la pestaña.</p>";
                    }

                    // 🔥 Ocultar spinner y mostrar contenido cuando la carga termine
                    document.getElementById("tabSpinner").classList.add("hidden");
                    document.getElementById("tabContent").classList.remove("hidden");

                    // 🔥 Disparar evento cuando el contenido esté cargado
                    document.dispatchEvent(new CustomEvent("alpine:tab-changed", {
                        detail: {
                            tab: this.tab
                        }
                    }));
                },

                setTab(newTab) {
                    this.tab = newTab;
                    this.loadContent();
                }
            }));
        });

    </script>


    <script src="{{ asset('assets/js/tickets/helpdesk/detalles.js') }}"></script>
</x-layout.default>
