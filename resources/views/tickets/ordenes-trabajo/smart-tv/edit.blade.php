<x-layout.default>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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

        .select2-container--default .select2-selection--single {
            border-radius: 0.5rem;
            height: 2.5rem;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            transition: border-color 0.2s, box-shadow 0.2s;
            box-shadow: 0 1px 2px rgba(67, 97, 238, 0.1);
        }
        
        .tab-disabled {
            opacity: 0.5;
            cursor: not-allowed !important;
            pointer-events: none;
        }
        
        .no-permission-message {
            text-align: center;
            padding: 3rem;
            background: #f8f9fa;
            border-radius: 0.5rem;
            margin: 2rem 0;
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

    @php
        // Verificar si tiene permiso para al menos una vista
        $tienePermisoDetalle = \App\Helpers\PermisoHelper::tienePermiso('VER DETALLE ORDEN DE TRABAJO SMART');
        $tienePermisoVisitas = \App\Helpers\PermisoHelper::tienePermiso('VER VISITAS ORDEN DE TRABAJO SMART');
        $tienePermisoConstancia = \App\Helpers\PermisoHelper::tienePermiso('VER CONSTANCIA ORDEN DE TRABAJO SMART') && $idEstadflujo == 10;
        $tienePermisoDesarrollo = \App\Helpers\PermisoHelper::tienePermiso('VER DESARROLLO ORDEN DE TRABAJO SMART') && $visitaExistente;
        $tienePermisoFirmas = \App\Helpers\PermisoHelper::tienePermiso('VER FIRMAS ORDEN DE TRABAJO SMART') && $visitaExistente;
        $tienePermisoInforme = \App\Helpers\PermisoHelper::tienePermiso('VER INFORME ORDEN DE TRABAJO SMART') && $visitaExistente;
        
        $tieneAlgunPermiso = $tienePermisoDetalle || $tienePermisoVisitas || $tienePermisoConstancia || 
                            $tienePermisoDesarrollo || $tienePermisoFirmas || $tienePermisoInforme;
    @endphp

    @if(!$tieneAlgunPermiso)
        <!-- Mensaje cuando no tiene permisos para ninguna vista -->
        <div class="no-permission-message">
            <div class="flex justify-center mb-4">
                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Acceso Restringido</h3>
            <p class="text-gray-600">Usted no tiene permiso para acceder a ninguna vista de esta orden de trabajo.</p>
            <div class="mt-4">
                <a href="{{ route('ordenes.smart') }}" class="btn btn-primary">Volver a Órdenes</a>
            </div>
        </div>
    @else
        <!-- Contenido normal si tiene al menos un permiso -->
        <div class="mb-5" x-data="{
            tab: 'detalle',
            loading: false,
            cargarPdf() {
                const iframe = document.getElementById('informePdfFrame');
                const loadingSpinner = document.getElementById('loadingSpinner');

                loadingSpinner.classList.remove('hidden');
                iframe.src = '{{ route('ordenes.generateInformePdf', ['idOt' => $orden->idTickets]) }}' + '?' + new Date().getTime();

                iframe.onload = function() {
                    loadingSpinner.classList.add('hidden');
                };
            }
        }">
            <!-- Breadcrumb -->
            <div>
                <ul class="flex space-x-2 rtl:space-x-reverse mt-4">
                    <li>
                        <a href="{{ route('ordenes.smart') }}" class="text-primary hover:underline">Órdenes</a>
                    </li>
                    <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                        <span>Editar OT</span>
                    </li>
                </ul>
            </div>

            <!-- Contenedor de Tabs Responsivo -->
            <div class="overflow-x-auto">
                <ul class="flex gap-2 sm:gap-3 justify-start sm:justify-center mt-3 mb-5 whitespace-nowrap">
                    <!-- Tab Detalle - Con permiso -->
                    @if(\App\Helpers\PermisoHelper::tienePermiso('VER DETALLE ORDEN DE TRABAJO SMART'))
                    <li>
                        <a href="javascript:;"
                            class="p-5 sm:p-7 py-2 sm:py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] dark:bg-[#191e3a] hover:!bg-success hover:text-white hover:shadow-md transition-all text-xs sm:text-sm"
                            :class="{ '!bg-success text-white': tab === 'detalle' }" 
                            @click="tab = 'detalle'">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 2H16M8 2V6M16 2V6M4 6H20M4 6V22H20V6M9 10H15M9 14H15M9 18H12" />
                            </svg>
                            Ticket
                        </a>
                    </li>
                    @endif

                    <!-- Tab Visitas - Con permiso -->
                    @if(\App\Helpers\PermisoHelper::tienePermiso('VER VISITAS ORDEN DE TRABAJO SMART'))
                    <li>
                        <a href="javascript:;"
                            class="p-5 sm:p-7 py-2 sm:py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] dark:bg-[#191e3a] hover:!bg-success hover:text-white hover:shadow-md transition-all text-xs sm:text-sm"
                            :class="{ '!bg-success text-white': tab === 'visitas' }" 
                            @click="tab = 'visitas'">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 2C8.686 2 6 4.686 6 8c0 5 6 11 6 11s6-6 6-11c0-3.314-2.686-6-6-6zM12 10a2 2 0 100-4 2 2 0 000 4z" />
                            </svg>
                            Coordinación
                        </a>
                    </li>
                    @endif

                    <!-- Tab Constancia - Condición original + permiso -->
                    @if ($idEstadflujo == 10 && \App\Helpers\PermisoHelper::tienePermiso('VER CONSTANCIA ORDEN DE TRABAJO SMART'))
                        <li>
                            <a href="javascript:;"
                                class="p-5 sm:p-7 py-2 sm:py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] dark:bg-[#191e3a] hover:!bg-success hover:text-white hover:shadow-md transition-all text-xs sm:text-sm"
                                :class="{ '!bg-success text-white': tab === 'constancia' }" 
                                @click="tab = 'constancia'">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 mb-1" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12l2 2l4 -4m2 -4v12a2 2 0 0 1 -2 2H7a2 2 0 0 1 -2 -2V6a2 2 0 0 1 2 -2h7l4 4z" />
                                </svg>
                                Constancia de entrega
                            </a>
                        </li>
                    @endif

                    <!-- Tab Desarrollo - Condición original + permiso -->
                    @if ($visitaExistente && \App\Helpers\PermisoHelper::tienePermiso('VER DESARROLLO ORDEN DE TRABAJO SMART'))
                        <li>
                            <a href="javascript:;"
                                class="p-5 sm:p-7 py-2 sm:py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] dark:bg-[#191e3a] hover:!bg-success hover:text-white hover:shadow-md transition-all text-xs sm:text-sm"
                                :class="{ '!bg-success text-white': tab === 'desarrollo' }" 
                                @click="tab = 'desarrollo'">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 mb-1" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16 18l6-6-6-6M8 6L2 12l6 6M12 2L9 22" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                Desarrollo
                            </a>
                        </li>
                    @endif

                    <!-- Tab Firmas - Condición original + permiso -->
                    @if ($visitaExistente && \App\Helpers\PermisoHelper::tienePermiso('VER FIRMAS ORDEN DE TRABAJO SMART'))
                        <li>
                            <a href="javascript:;"
                                class="p-5 sm:p-7 py-2 sm:py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] dark:bg-[#191e3a] hover:!bg-success hover:text-white hover:shadow-md transition-all text-xs sm:text-sm"
                                :class="{ '!bg-success text-white': tab === 'firmas' }" 
                                @click="tab = 'firmas'">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 mb-1" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.5 3L21 7.5M3 21l9-9M15 6l6 6M3 21h6l9-9-6-6-9 9v6z" />
                                </svg>
                                Firmas
                            </a>
                        </li>
                    @endif

                    <!-- Tab Informe - Condición original + permiso -->
                    @if ($visitaExistente && \App\Helpers\PermisoHelper::tienePermiso('VER INFORME ORDEN DE TRABAJO SMART'))
                        <li>
                            <a href="javascript:;"
                                class="p-5 sm:p-7 py-2 sm:py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] dark:bg-[#191e3a] hover:!bg-success hover:text-white hover:shadow-md transition-all text-xs sm:text-sm"
                                :class="{ '!bg-success text-white': tab === 'informe' }"
                                @click="tab = 'informe'; $nextTick(() => cargarPdf())">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 mb-1" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 4h16v16H4V4zM8 10h8M8 14h4" />
                                </svg>
                                Informe
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            <!-- Contenido de los Tabs -->
            <div class="panel mt-6 p-5 max-w-4x2 mx-auto">
                <!-- Detalle - Con permiso -->
                @if(\App\Helpers\PermisoHelper::tienePermiso('VER DETALLE ORDEN DE TRABAJO SMART'))
                <div x-show="tab === 'detalle'">
                    @include('tickets.ordenes-trabajo.smart-tv.detalle.index', [
                        'orden' => $orden,
                        'modelos' => $modelos,
                    ])
                </div>
                @endif

                <!-- Visitas - Con permiso -->
                @if(\App\Helpers\PermisoHelper::tienePermiso('VER VISITAS ORDEN DE TRABAJO SMART'))
                <div x-show="tab === 'visitas'">
                    @include('tickets.ordenes-trabajo.smart-tv.visitas.index')
                </div>
                @endif

                <!-- Constancia - Solo si tiene permiso y cumple condición -->
                @if(\App\Helpers\PermisoHelper::tienePermiso('VER CONSTANCIA ORDEN DE TRABAJO SMART'))
                <div x-show="tab === 'constancia'">
                    @include('tickets.ordenes-trabajo.smart-tv.constancia.index')
                </div>
                @endif

                <!-- Desarrollo - Solo si tiene permiso y cumple condición -->
                @if(\App\Helpers\PermisoHelper::tienePermiso('VER DESARROLLO ORDEN DE TRABAJO SMART'))
                <div x-show="tab === 'desarrollo'">
                    @include('tickets.ordenes-trabajo.smart-tv.informacion.index')
                </div>
                @endif

                <!-- Firmas - Solo si tiene permiso y cumple condición -->
                @if(\App\Helpers\PermisoHelper::tienePermiso('VER FIRMAS ORDEN DE TRABAJO SMART'))
                <div x-show="tab === 'firmas'">
                    @include('tickets.ordenes-trabajo.smart-tv.firmas.index')
                </div>
                @endif

                <!-- Informe - Solo si tiene permiso y cumple condición -->
                @if(\App\Helpers\PermisoHelper::tienePermiso('VER INFORME ORDEN DE TRABAJO SMART'))
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
                @endif
            </div>
        </div>
    @endif

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
</x-layout.default>