<x-layout.default>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        /* Estilo general del select */
        .select2-container--default .select2-selection--single {
            /* azul claro suave */
            border-radius: 0.5rem;
            height: 2.5rem;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            transition: border-color 0.2s, box-shadow 0.2s;
            box-shadow: 0 1px 2px rgba(67, 97, 238, 0.1);
        }

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

    <!-- Breadcrumb -->
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse mt-4">
            <li>
                <a href="{{ route('ordenes.helpdesk') }}" class="text-primary hover:underline">Órdenes</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Editar OT</span>
            </li>
        </ul>
    </div>

    <!-- Definir el tipo de servicio y el rol del usuario -->
    @php
        $tipoServicio = $orden->tipoServicio;
        $idRol = Auth::user()->idRol ?? null;
        $esRol6 = $idRol == 6;
    @endphp

    <div class="mb-5" x-data="{
        tab: 'detalle',
        loading: false,
        cargarPdf() {
            const iframe = document.getElementById('informePdfFrame');
            const loadingSpinner = document.getElementById('loadingSpinner');
    
            loadingSpinner.classList.remove('hidden');
    
            const ruta = iframe.getAttribute('data-src');
            iframe.src = ruta + '?' + new Date().getTime();
    
            iframe.onload = () => loadingSpinner.classList.add('hidden');
    
            window.cargarPdfDesdeAlpine = this.cargarPdf;
        },
        cargarConformidadPdf() {
            const iframe = document.getElementById('conformidadPdfFrame');
            const loadingSpinner = document.getElementById('loadingSpinnerConformidad');
    
            loadingSpinner.classList.remove('hidden');
    
            const ruta = iframe.getAttribute('data-src');
            iframe.src = ruta + '?' + new Date().getTime();
    
            iframe.onload = () => loadingSpinner.classList.add('hidden');
    
            window.cargarConformidadPdfDesdeAlpine = this.cargarConformidadPdf;
        }
    }">

        <!-- Tabs -->
        <ul
            class="grid grid-cols-4 gap-2 sm:flex sm:flex-wrap sm:justify-center mt-3 mb-5 sm:space-x-3 rtl:space-x-reverse">
            @if (!$esRol6)
                <!-- Mostrar todas las pestañas para usuarios que NO son rol 6 -->
                <li>
                    <a href="javascript:;"
                        class="p-5 sm:p-7 py-2 sm:py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] dark:bg-[#191e3a] hover:!bg-success hover:text-white hover:shadow-md transition-all text-xs sm:text-sm"
                        :class="{ '!bg-success text-white': tab === 'detalle' }" @click="tab = 'detalle'">
                        <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2"
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

                @if ($tipoServicio == 6 && $orden->clienteGeneral && $orden->clienteGeneral->idClienteGeneral != 6)
                    <li>
                        <a href="javascript:;"
                            class="p-5 sm:p-7 py-2 sm:py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] dark:bg-[#191e3a] hover:!bg-success hover:text-white hover:shadow-md transition-all text-xs sm:text-sm"
                            :class="{ '!bg-success text white': tab === 'constancia' }" @click="tab = 'constancia'">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 mb-1" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2l4 -4m2 -4v12a2 2 0 0 1 -2 2H7a2 2 0 0 1 -2 -2V6a2 2 0 0 1 2 -2h7l4 4z" />
                            </svg>
                            Constancia de entrega
                        </a>
                    </li>
                @endif

                @if ($existeFlujo31)
                    <li>
                        <a href="javascript:;"
                            class="p-5 sm:p-7 py-2 sm:py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] dark:bg-[#191e3a] hover:!bg-success hover:text-white hover:shadow-md transition-all text-xs sm:text-sm"
                            :class="{ '!bg-success text-white': tab === 'retorno' }" @click="tab = 'retorno'">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 mb-1" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 18l6-6-6-6M8 6L2 12l6 6M12 2L9 22" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                            Retorno
                        </a>
                    </li>
                @endif

                @if ($tipoServicio == 1 || $tipoServicio == 5 || $tipoServicio == 6)
                    <li>
                        <a href="javascript:;"
                            class="p-5 sm:p-7 py-2 sm:py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] dark:bg-[#191e3a] hover:!bg-success hover:text-white hover:shadow-md transition-all text-xs sm:text-sm"
                            :class="{ '!bg-success text-white': tab === 'imagenes' }" @click="tab = 'imagenes'">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 mb-1" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 18l6-6-6-6M8 6L2 12l6 6M12 2L9 22" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                            Imagenes
                        </a>
                    </li>
                @endif

                @if ($tipoServicio == 2)
                    <li>
                        <a href="javascript:;"
                            class="p-5 sm:p-7 py-2 sm:py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] dark:bg-[#191e3a] hover:!bg-success hover:text-white hover:shadow-md transition-all text-xs sm:text-sm"
                            :class="{ '!bg-success text-white': tab === 'recursos' }"
                            @click="tab = 'recursos'; console.log(tab)">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 mb-1" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8L7 4H17L21 8V20H3V8Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12V16M15 12V16" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8H21" />
                            </svg>
                            Recursos
                        </a>
                    </li>
                @endif
                <li>
                    <a href="javascript:;"
                        class="p-5 sm:p-7 py-2 sm:py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] dark:bg-[#191e3a] hover:!bg-success hover:text-white hover:shadow-md transition-all text-xs sm:text-sm"
                        :class="{ '!bg-success text-white': tab === 'firmas' }"
                        @click="tab = 'firmas'; console.log(tab)">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 mb-1" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
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
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 mb-1" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h16v16H4V4zM8 10h8M8 14h4" />
                        </svg>
                        Informe
                    </a>
                </li>
                <li>
                    <a href="javascript:;"
                        class="p-5 sm:p-7 py-2 sm:py-3 flex flex-col items-center justify-center rounded-lg 
               bg-[#f1f2f3] dark:bg-[#191e3a] hover:!bg-success hover:text-white hover:shadow-md 
               transition-all text-xs sm:text-sm"
                        :class="{ '!bg-success text-white': tab === 'conformidad' }"
                        @click="tab = 'conformidad'; $nextTick(() => cargarConformidadPdf())">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 mb-1" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h16v16H4V4zM8 10h8M8 14h4" />
                        </svg>
                        Conformidad
                    </a>
                </li>


                @if ($existeFlujo25)
                    <li>
                        <a href="{{ url('/apps/invoice/preview/' . $ticket->idTickets) }}" target="_blank"
                            class="p-5 sm:p-7 py-2 sm:py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] dark:bg-[#191e3a] hover:!bg-success hover:text-white hover:shadow-md transition-all text-xs sm:text-sm">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 mb-1" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 18l6-6-6-6M8 6L2 12l6 6M12 2L9 22" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                            Ver Envio
                        </a>
                    </li>
                @endif
            @else
                <!-- Mostrar solo Ticket, Coordinación e Informe para usuarios con rol 6 -->
                <li>
                    <a href="javascript:;"
                        class="p-5 sm:p-7 py-2 sm:py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] dark:bg-[#191e3a] hover:!bg-success hover:text-white hover:shadow-md transition-all text-xs sm:text-sm"
                        :class="{ '!bg-success text-white': tab === 'detalle' }" @click="tab = 'detalle'">
                        <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2"
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
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 mb-1" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 2C8.686 2 6 4.686 6 8c0 5 6 11 6 11s6-6 6-11c0-3.314-2.686-6-6-6zM12 10a2 2 0 100-4 2 2 0 000 4z" />
                        </svg>
                        Coordinación
                    </a>
                </li>
                <li>
                    <a href="javascript:;"
                        class="p-5 sm:p-7 py-2 sm:py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] dark:bg-[#191e3a] hover:!bg-success hover:text-white hover:shadow-md transition-all text-xs sm:text-sm"
                        :class="{ '!bg-success text-white': tab === 'informe' }"
                        @click="tab = 'informe'; $nextTick(() => cargarPdf())">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 mb-1" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h16v16H4V4zM8 10h8M8 14h4" />
                        </svg>
                        Informe
                    </a>
                </li>
            @endif
        </ul>

        <!-- Cargar contenido según tipo de servicio -->
        <div class="panel mt-6 p-5 max-w-4x2 mx-auto">
            @if ($tipoServicio == 2)
                {{-- Levantamiento de Información --}}
                <div x-show="tab === 'detalle'">
                    @include('tickets.ordenes-trabajo.helpdesk.levantamiento.detalle.index')
                </div>
                <div x-show="tab === 'visitas'">
                    @include('tickets.ordenes-trabajo.helpdesk.levantamiento.visitas.index')
                </div>
                @if (!$esRol6)
                    <div x-show="tab === 'desarrollo'">
                        @include('tickets.ordenes-trabajo.helpdesk.levantamiento.informacion.index')
                    </div>
                    <div x-show="tab === 'recursos'">
                        @include('tickets.ordenes-trabajo.helpdesk.levantamiento.recursos.index')
                    </div>
                    <div x-show="tab === 'firmas'">
                        @include('tickets.ordenes-trabajo.helpdesk.levantamiento.firmas.index')
                    </div>
                @endif
                <div x-show="tab === 'informe'">
                    @include('tickets.ordenes-trabajo.helpdesk.levantamiento.informe.index')
                    <div id="loadingSpinner"
                        class="absolute inset-0 flex items-center justify-center bg-gray-200 bg-opacity-70 z-10 hidden">
                        <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 border-t-4 border-gray-200 rounded-full"
                            role="status">
                            <span class="w-5 h-5 m-auto mb-10">
                                <span class="animate-ping inline-flex h-full w-full rounded-full bg-info"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div x-show="tab === 'conformidad'">
                    @include('tickets.ordenes-trabajo.helpdesk.levantamiento.conformidad.index')

                    <!-- Spinner -->
                    <div id="loadingSpinnerConformidad"
                        class="absolute inset-0 flex items-center justify-center bg-gray-200 bg-opacity-70 z-10 hidden">
                        <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 border-t-4 border-gray-200 rounded-full"
                            role="status">
                            <span class="w-5 h-5 m-auto mb-10">
                                <span class="animate-ping inline-flex h-full w-full rounded-full bg-info"></span>
                            </span>
                        </div>
                    </div>
                </div>
            @elseif ($tipoServicio == 6)
                {{-- Laboratorio --}}
                <div x-show="tab === 'detalle'">
                    @include('tickets.ordenes-trabajo.helpdesk.laboratorio.detalle.index')
                </div>
                <div x-show="tab === 'visitas'">
                    @include('tickets.ordenes-trabajo.helpdesk.laboratorio.visitas.index')
                </div>
                @if (!$esRol6)
                    <div x-show="tab === 'desarrollo'">
                        @include('tickets.ordenes-trabajo.helpdesk.laboratorio.informacion.index')
                    </div>
                    <div x-show="tab === 'constancia'">
                        @include('tickets.ordenes-trabajo.smart-tv.constancia.index')
                    </div>
                    <div x-show="tab === 'imagenes'">
                        @include('tickets.ordenes-trabajo.helpdesk.laboratorio.imagenes.index')
                    </div>
                    <div x-show="tab === 'firmas'">
                        @include('tickets.ordenes-trabajo.helpdesk.laboratorio.firmas.index')
                    </div>
                @endif
                <div x-show="tab === 'informe'">
                    @include('tickets.ordenes-trabajo.helpdesk.laboratorio.informe.index')
                    <div id="loadingSpinner"
                        class="absolute inset-0 flex items-center justify-center bg-gray-200 bg-opacity-70 z-10 hidden">
                        <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 border-t-4 border-gray-200 rounded-full"
                            role="status">
                            <span class="w-5 h-5 m-auto mb-10">
                                <span class="animate-ping inline-flex h-full w-full rounded-full bg-info"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div x-show="tab === 'conformidad'">
                    @include('tickets.ordenes-trabajo.helpdesk.laboratorio.conformidad.index')

                    <!-- Spinner -->
                    <div id="loadingSpinnerConformidad"
                        class="absolute inset-0 flex items-center justify-center bg-gray-200 bg-opacity-70 z-10 hidden">
                        <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 border-t-4 border-gray-200 rounded-full"
                            role="status">
                            <span class="w-5 h-5 m-auto mb-10">
                                <span class="animate-ping inline-flex h-full w-full rounded-full bg-info"></span>
                            </span>
                        </div>
                    </div>
                </div>
            @elseif ($tipoServicio == 5)
                {{-- Soporte On Site --}}
                <div x-show="tab === 'detalle'">
                    @include('tickets.ordenes-trabajo.helpdesk.ejecucion.detalle.index')
                </div>
                <div x-show="tab === 'visitas'">
                    @include('tickets.ordenes-trabajo.helpdesk.ejecucion.visitas.index')
                </div>
                @if (!$esRol6)
                    <div x-show="tab === 'desarrollo'">
                        @include('tickets.ordenes-trabajo.helpdesk.ejecucion.informacion.index')
                    </div>
                    <div x-show="tab === 'imagenes'">
                        @include('tickets.ordenes-trabajo.helpdesk.ejecucion.imagenes.index')
                    </div>
                    <div x-show="tab === 'firmas'">
                        @include('tickets.ordenes-trabajo.helpdesk.ejecucion.firmas.index')
                    </div>
                @endif
                <div x-show="tab === 'informe'">
                    @include('tickets.ordenes-trabajo.helpdesk.ejecucion.informe.index')
                    <div id="loadingSpinner"
                        class="absolute inset-0 flex items-center justify-center bg-gray-200 bg-opacity-70 z-10 hidden">
                        <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 border-t-4 border-gray-200 rounded-full"
                            role="status">
                            <span class="w-5 h-5 m-auto mb-10">
                                <span class="animate-ping inline-flex h-full w-full rounded-full bg-info"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div x-show="tab === 'conformidad'">
                    @include('tickets.ordenes-trabajo.helpdesk.ejecucion.conformidad.index')

                    <!-- Spinner -->
                    <div id="loadingSpinnerConformidad"
                        class="absolute inset-0 flex items-center justify-center bg-gray-200 bg-opacity-70 z-10 hidden">
                        <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 border-t-4 border-gray-200 rounded-full"
                            role="status">
                            <span class="w-5 h-5 m-auto mb-10">
                                <span class="animate-ping inline-flex h-full w-full rounded-full bg-info"></span>
                            </span>
                        </div>
                    </div>
                </div>
            @elseif ($tipoServicio == 1)
                {{-- Soporte On Site --}}
                <div x-show="tab === 'detalle'">
                    @include('tickets.ordenes-trabajo.helpdesk.soporte.detalle.index')
                </div>
                <div x-show="tab === 'visitas'">
                    @include('tickets.ordenes-trabajo.helpdesk.soporte.visitas.index')
                </div>
                @if (!$esRol6)
                    <div x-show="tab === 'desarrollo'">
                        @include('tickets.ordenes-trabajo.helpdesk.soporte.informacion.index')
                    </div>
                    <div x-show="tab === 'retorno'">
                        @include('tickets.ordenes-trabajo.helpdesk.soporte.retorno.index')
                    </div>
                    <div x-show="tab === 'imagenes'">
                        @include('tickets.ordenes-trabajo.helpdesk.soporte.imagenes.index')
                    </div>
                    <div x-show="tab === 'firmas'">
                        @include('tickets.ordenes-trabajo.helpdesk.soporte.firmas.index')
                    </div>
                @endif
                <div x-show="tab === 'informe'">
                    @include('tickets.ordenes-trabajo.helpdesk.soporte.informe.index')
                    <div id="loadingSpinner"
                        class="absolute inset-0 flex items-center justify-center bg-gray-200 bg-opacity-70 z-10 hidden">
                        <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 border-t-4 border-gray-200 rounded-full"
                            role="status">
                            <span class="w-5 h-5 m-auto mb-10">
                                <span class="animate-ping inline-flex h-full w-full rounded-full bg-info"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div x-show="tab === 'conformidad'">
                    @include('tickets.ordenes-trabajo.helpdesk.soporte.conformidad.index')

                    <!-- Spinner -->
                    <div id="loadingSpinnerConformidad"
                        class="absolute inset-0 flex items-center justify-center bg-gray-200 bg-opacity-70 z-10 hidden">
                        <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 border-t-4 border-gray-200 rounded-full"
                            role="status">
                            <span class="w-5 h-5 m-auto mb-10">
                                <span class="animate-ping inline-flex h-full w-full rounded-full bg-info"></span>
                            </span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</x-layout.default>
