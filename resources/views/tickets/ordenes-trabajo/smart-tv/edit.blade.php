<x-layout.default>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        .panel {
            overflow: visible !important;
            /* Asegura que el modal no restrinja contenido */
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

    <div class="mb-5" x-data="{
        tab: 'detalle',
        loading: false,
        cargarPdf() {
            const iframe = document.getElementById('informePdfFrame');
    
            const loadingSpinner = document.getElementById('loadingSpinner');
    
            // Mostrar el spinner
            loadingSpinner.classList.remove('hidden');
    
            iframe.src = '{{ route('ordenes.generateInformePdf', ['idOt' => $orden->idTickets]) }}' + '?' + new Date().getTime();
    
            // Ocultar el spinner cuando el PDF se cargue
            iframe.onload = function() {
                loadingSpinner.classList.add('hidden');
            };
    
        }
    }">
        <!-- Contenedor de Tabs Responsivo -->
        <div class="overflow-x-auto">
            <ul class="flex gap-2 sm:gap-3 justify-start sm:justify-center mt-3 mb-5 whitespace-nowrap">
                <li>
                    <a href="javascript:;"
                        class="p-5 sm:p-7 py-2 sm:py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] dark:bg-[#191e3a] hover:!bg-success hover:text-white hover:shadow-md transition-all text-xs sm:text-sm"
                        :class="{ '!bg-success text-white': tab === 'detalle' }" @click="tab = 'detalle'">
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
                        class="p-5 sm:p-7 py-2 sm:py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] dark:bg-[#191e3a] hover:!bg-success hover:text-white hover:shadow-md transition-all text-xs sm:text-sm"
                        :class="{ '!bg-success text-white': tab === 'visitas' }" @click="tab = 'visitas'">
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
                        class="p-5 sm:p-7 py-2 sm:py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] dark:bg-[#191e3a] hover:!bg-success hover:text-white hover:shadow-md transition-all text-xs sm:text-sm"
                        :class="{ '!bg-success text-white': tab === 'desarrollo' }" @click="tab = 'desarrollo'">
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
                        class="p-5 sm:p-7 py-2 sm:py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] dark:bg-[#191e3a] hover:!bg-success hover:text-white hover:shadow-md transition-all text-xs sm:text-sm"
                        :class="{ '!bg-success text-white': tab === 'firmas' }" @click="tab = 'firmas'">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.5 3L21 7.5M3 21l9-9M15 6l6 6M3 21h6l9-9-6-6-9 9v6z" />
                        </svg>
                        Firmas
                    </a>
                </li>
                <li>
                    <a href="javascript:;"
                        class="p-5 sm:p-7 py-2 sm:py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] dark:bg-[#191e3a] hover:!bg-success hover:text-white hover:shadow-md transition-all text-xs sm:text-sm"
                        :class="{ '!bg-success text-white': tab === 'informe' }"
                        @click="tab = 'informe'; $nextTick(() => cargarPdf())">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h16v16H4V4zM8 10h8M8 14h4" />
                        </svg>
                        Informe
                    </a>
                </li>
            </ul>
        </div>
        <!-- Contenido de los Tabs -->
        <div class="panel mt-6 p-5 max-w-4x2 mx-auto">
            <div x-show="tab === 'detalle'">
                @include('tickets.ordenes-trabajo.smart-tv.detalle.index', [
                    'orden' => $orden,
                    'modelos' => $modelos,
                ])
            </div>
            <div x-show="tab === 'visitas'">
                @include('tickets.ordenes-trabajo.smart-tv.visitas.index')
            </div>
            <div x-show="tab === 'desarrollo'">
                @include('tickets.ordenes-trabajo.smart-tv.informacion.index')
            </div>
            <div x-show="tab === 'firmas'">
                @include('tickets.ordenes-trabajo.smart-tv.firmas.index')
            </div>
            <div x-show="tab === 'informe'">

                @include('tickets.ordenes-trabajo.smart-tv.informe.index')

                <div id="loadingSpinner"
                    class="absolute inset-0 flex items-center justify-center bg-gray-200 bg-opacity-70 z-10 hidden">
                    <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 border-t-4 border-gray-200 rounded-full"
                        role="status">
                        <span class="w-5 h-5 m-auto mb-10"><span
                                class="animate-ping inline-flex h-full w-full rounded-full bg-info"></span></span>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    {{-- <script src="{{ asset('assets/js/tickets/smart/smart.js') }}"></script> --}}
</x-layout.default>
