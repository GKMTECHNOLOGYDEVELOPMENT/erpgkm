<x-layout.default>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">


    <style>
        .panel {
            overflow: visible !important;
            /* Asegura que el modal no restrinja contenido */
        }
    </style>
    <div x-data="multipleTable">
        <div>
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="javascript:;" class="text-primary hover:underline">Tickets</a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>Ordenes de Trabajo</span>
                </li>
            </ul>
        </div>
        <div class="panel mt-6">
            <div class="md:absolute md:top-5 ltr:md:left-5 rtl:md:right-5">
                <div class="flex flex-wrap items-center justify-center gap-2 mb-5 sm:justify-start md:flex-nowrap">
                    <!-- Botón Exportar a Excel -->
                    <button type="button" class="btn btn-success btn-sm flex items-center gap-2"
                        onclick="window.location.href='{{ route('clientes.exportExcel') }}'">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                            <path
                                d="M4 3H20C21.1046 3 22 3.89543 22 5V19C22 20.1046 21.1046 21 20 21H4C2.89543 21 2 20.1046 2 19V5C2 3.89543 2 3 4 3Z"
                                stroke="currentColor" stroke-width="1.5" />
                            <path d="M16 10L8 14M8 10L16 14" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span>Excel</span>
                    </button>


                    <!-- Botón Exportar a PDF -->
                    <button type="button" class="btn btn-danger btn-sm flex items-center gap-2"
                        @click="window.location.href = '{{ route('reporte.clientes') }}'">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                            <path
                                d="M2 5H22M2 5H22C22 6.10457 21.1046 7 20 7H4C2.89543 7 2 6.10457 2 5ZM2 5V19C2 20.1046 2.89543 21 4 21H20C21.1046 21 22 20.1046 22 19V5M9 14L15 14"
                                stroke="currentColor" stroke-width="1.5" />
                            <path d="M12 11L12 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                        <span>PDF</span>
                    </button>


                    <!-- Botón Agregar -->
                    <button type="button" class="btn btn-primary btn-sm flex items-center gap-2"
                        @click="$dispatch('toggle-modal')">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                            <circle cx="10" cy="6" r="4" stroke="currentColor" stroke-width="1.5" />
                            <path opacity="0.5"
                                d="M18 17.5C18 19.9853 18 22 10 22C2 22 2 19.9853 2 17.5C2 15.0147 5.58172 13 10 13C14.4183 13 18 15.0147 18 17.5Z"
                                stroke="currentColor" stroke-width="1.5" />
                            <path d="M21 10H19M19 10H17M19 10L19 8M19 10L19 12" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                        </svg>
                        <span>Agregar</span>
                    </button>
                </div>
            </div>

            <table id="myTable1" class="whitespace-nowrap"></table>
        </div>
    </div>

    <!-- Modal -->
    <div x-data="{ open: false }" class="mb-5" @toggle-modal.window="open = !open">
        <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
                <div x-show="open" x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-3xl my-8 animate__animated animate__zoomInUp">
                    <!-- Header del Modal -->
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <h5 class="font-bold text-lg">Agregar Orden de Trabajo</h5>
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
                        <form class="p-5 space-y-4" id="ordenTrabajoForm" enctype="multipart/form-data" method="post">
                            @csrf <!-- Asegúrate de incluir el token CSRF -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Tipo Ticket -->
                                <div>
                                    <select id="idTipotickets" name="idTipotickets" class="select2 w-full">
                                        <option value="" disabled selected>Seleccionar Tipo de Ticket</option>
                                        @foreach ($tiposTickets as $tipoTicket)
                                            <option value="{{ $tipoTicket->idTipotickets }}">
                                                {{ $tipoTicket->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Nro. Ticket -->
                                <div>
                                    <label for="nroTicket" class="block text-sm font-medium">Nro. Ticket</label>
                                    <input id="nroTicket" type="text" name="nroTicket" class="form-input w-full"
                                        placeholder="Ingrese el número de ticket">
                                </div>
                                <!-- Cliente General -->
                                <div>

                                    <!-- <label for="idCliente" class="block text-sm font-medium">Cliente</label> -->
                                    <select id="idClienteGeneral" name="idClienteGeneral" class="select2 w-full">
                                        <option value="" disabled selected>Seleccionar Cliente General</option>
                                        <!-- Llenar el select con clientes dinámicamente -->
                                        @foreach ($clientesGenerales as $clientesGenerale)
                                            <option value="{{ $clientesGenerale->idClienteGeneral }}"
                                                {{ old('idCliente') == $clientesGenerale->idClienteGeneral ? 'selected' : '' }}>
                                                {{ $clientesGenerale->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Cliente -->
                                <div>
                                    <select id="idCliente" name="idCliente" class="select2 w-full">
                                        <option value="" disabled selected>Seleccionar Cliente</option>
                                        <!-- Llenar el select con clientes dinámicamente -->
                                        @foreach ($clientes as $cliente)
                                            <option value="{{ $cliente->idCliente }}"
                                                {{ old('idCliente') == $cliente->idCliente ? 'selected' : '' }}>
                                                {{ $cliente->nombre }} - {{ $cliente->documento }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Tienda -->
                                <div>
                                    <select id="idTienda" name="idTienda" class="select2 w-full">
                                        <option value="" disabled selected>Seleccionar Tienda</option>
                                        <!-- Llenar el select con clientes dinámicamente -->
                                        @foreach ($tiendas as $tienda)
                                            <option value="{{ $tienda->idTienda }}"
                                                {{ old('idCliente') == $tienda->idTienda ? 'selected' : '' }}>
                                                {{ $tienda->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Técnico Responsable -->
                                <div>
                                    <select id="tecnico" name="tecnico" class="select2 w-full">
                                        <option value="" disabled selected>Seleccionar Técnico</option>
                                        @foreach ($usuarios as $usuario)
                                            <option value="{{ $usuario->idUsuario }}">
                                                {{ $usuario->Nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Tipo Servicio -->
                                <div>
                                    <select id="tipoServicio" name="tipoServicio" class="select2 w-full">
                                        <option value="" disabled selected>Seleccionar Tipo Servicio</option>
                                        @foreach ($tiposServicio as $tiposServicios)
                                            <option value="{{ $tiposServicios->idTipoServicio }}">
                                                {{ $tiposServicios->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>

                            <!-- Botones -->
                            <div class="flex justify-end items-center mt-4">
                                <button type="button" class="btn btn-outline-danger"
                                    @click="open = false">Cancelar</button>
                                <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.select2').forEach(function(select) {
                NiceSelect.bind(select, {
                    searchable: true
                });
            });
        });
    </script>

    <script src="{{ asset('assets/js/ordenes/ordenes.js') }}"></script>
    <script src="{{ asset('assets/js/ordenes/ordenesStore.js') }}"></script>
    <script src="{{ asset('assets/js/ordenes/ordenesValidaciones.js') }}"></script>
    <script src="/assets/js/simple-datatables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>

</x-layout.default>
