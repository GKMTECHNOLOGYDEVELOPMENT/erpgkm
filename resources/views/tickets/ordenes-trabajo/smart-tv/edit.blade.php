<x-layout.default>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        .panel {
            overflow: visible !important;
            /* Asegura que el modal no restrinja contenido */
        }

    </style>
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
                    Detalles OTT
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


        <!-- Contenido de los Tabs -->
        <div class="panel mt-6 p-5 max-w-4xl mx-auto">
            <div x-show="tab === 'detalle'">
                @include('tickets.ordenes-trabajo.smart-tv.detalle.index', ['orden' => $orden, 'modelos' => $modelos])
            </div>
            <div x-show="tab === 'visitas'">
                @include('tickets.ordenes-trabajo.smart-tv.visitas.index')
            </div>
            <div x-show="tab === 'firmas'">
                @include('tickets.ordenes-trabajo.smart-tv.firmas.index')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    {{-- <script src="{{ asset('assets/js/tickets/smart/smart.js') }}"></script> --}}
</x-layout.default>
