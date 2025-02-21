<x-layout.default>
    <meta name="csrf-token" content="{{ csrf_token() }}">
        
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <style>
        .panel { overflow: visible !important; }
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

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Definir el tipo de servicio -->
    @php
        $tipoServicio = $orden->tipoServicio; // ID del tipo de servicio
    @endphp

    <div class="mb-5" x-data="{ tab: 'detalle' }">
        <!-- Tabs -->
        <ul class="grid grid-cols-4 gap-2 sm:flex sm:flex-wrap sm:justify-center mt-3 mb-5 sm:space-x-3 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="p-7 py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] dark:bg-[#191e3a] hover:!bg-success hover:text-white hover:shadow-[0_5px_15px_0_rgba(0,0,0,0.30)]"
                   :class="{ '!bg-success text-white': tab === 'detalle' }" @click="tab = 'detalle'">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M8 2H16M8 2V6M16 2V6M4 6H20M4 6V22H20V6M9 10H15M9 14H15M9 18H12" />
                    </svg>
                    Ticket
                </a>
            </li>
            <li>
                <a href="javascript:;" class="p-7 py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] dark:bg-[#191e3a] hover:!bg-success hover:text-white hover:shadow-[0_5px_15px_0_rgba(0,0,0,0.30)]"
                   :class="{ '!bg-success text-white': tab === 'visitas' }" @click="tab = 'visitas'">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 19l3-3m-3 3H5v-3l9-9a2 2 0 012.828 0l2.172 2.172a2 2 0 010 2.828l-9 9z" />
                    </svg>
                    Coordinación
                </a>
            </li>
            <li>
                <a href="javascript:;" class="p-7 py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] dark:bg-[#191e3a] hover:!bg-success hover:text-white hover:shadow-[0_5px_15px_0_rgba(0,0,0,0.30)]"
                   :class="{ '!bg-success text-white': tab === 'informacion' }" @click="tab = 'informacion'">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 19l3-3m-3 3H5v-3l9-9a2 2 0 012.828 0l2.172 2.172a2 2 0 010 2.828l-9 9z" />
                    </svg>
                    Informe
                </a>
            </li>
            <li>
                <a href="javascript:;" class="p-7 py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] dark:bg-[#191e3a] hover:!bg-success hover:text-white hover:shadow-[0_5px_15px_0_rgba(0,0,0,0.30)]"
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

        <!-- Cargar contenido según tipo de servicio -->
        <div class="panel mt-6 p-5 max-w-4x2 mx-auto">
            @if ($tipoServicio == 1)  {{-- Levantamiento de Información --}}
                <div x-show="tab === 'detalle'">
                    @include('tickets.ordenes-trabajo.helpdesk.levantamiento.detalle.index')
                </div>
                <div x-show="tab === 'visitas'">
                    @include('tickets.ordenes-trabajo.helpdesk.levantamiento.visitas.index')
                </div>
                <div x-show="tab === 'informacion'">
                    @include('tickets.ordenes-trabajo.helpdesk.levantamiento.informacion.index')
                </div>
                <div x-show="tab === 'firmas'">
                    @include('tickets.ordenes-trabajo.helpdesk.levantamiento.firmas.index')
                </div>
            @elseif ($tipoServicio == 2)  {{-- Soporte On Site --}}
                <div x-show="tab === 'detalle'">
                    @include('tickets.ordenes-trabajo.helpdesk.soporte.detalle.index')
                </div>
                <div x-show="tab === 'visitas'">
                    @include('tickets.ordenes-trabajo.helpdesk.soporte.visitas.index')
                </div>
                <div x-show="tab === 'informacion'">
                    @include('tickets.ordenes-trabajo.helpdesk.soporte.informacion.index')
                </div>
                <div x-show="tab === 'firmas'">
                    @include('tickets.ordenes-trabajo.helpdesk.soporte.firmas.index')
                </div>
            @endif
        </div>
    </div>
</x-layout.default>
