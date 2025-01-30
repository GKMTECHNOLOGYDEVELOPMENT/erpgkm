<x-layout.default>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    @if(session('error'))
    <!-- error (rojo) -->
    <div class="relative flex items-center border p-3.5 rounded before:inline-block before:absolute before:top-1/2 ltr:before:right-0 rtl:before:left-0 rtl:before:rotate-180 before:-mt-2 before:border-r-8 before:border-t-8 before:border-b-8 before:border-t-transparent before:border-b-transparent before:border-r-inherit text-danger bg-danger-light border-danger ltr:border-r-[64px] rtl:border-l-[64px] dark:bg-danger-dark-light">
        <span class="absolute ltr:-right-11 rtl:-left-11 inset-y-0 text-white w-6 h-6 m-auto">
            <svg> ... </svg> <!-- Aquí va el icono de error si lo deseas -->
        </span>
        <span class="ltr:pr-2 rtl:pl-2"><strong class="ltr:mr-1 rtl:ml-1">¡Error!</strong>{{ session('error') }}</span>
        <button type="button" class="ltr:ml-auto rtl:mr-auto hover:opacity-80">
            <svg> ... </svg> <!-- Icono de cerrar -->
        </button>
    </div>
@endif

@if ($errors->any())
    <!-- validation errors (warning) -->
    <div class="relative flex items-center border p-3.5 rounded before:absolute before:top-1/2 ltr:before:left-0 rtl:before:right-0 rtl:before:rotate-180 before:-mt-2 before:border-l-8 before:border-t-8 before:border-b-8 before:border-t-transparent before:border-b-transparent before:border-l-inherit text-warning bg-warning-light !border-warning ltr:border-l-[64px] rtl:border-r-[64px] dark:bg-warning-dark-light">
        <span class="absolute ltr:-left-11 rtl:-right-11 inset-y-0 text-white w-6 h-6 m-auto">
            <svg> ... </svg> <!-- Aquí va el icono de advertencia -->
        </span>
        <span class="ltr:pr-2 rtl:pl-2"><strong class="ltr:mr-1 rtl:ml-1">¡Advertencia!</strong>Por favor revisa los errores en el formulario.</span>
        <button type="button" class="ltr:ml-auto rtl:mr-auto hover:opacity-80">
            <svg> ... </svg> <!-- Icono de cerrar -->
        </button>
    </div>
@endif
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse mt-4">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Órdenes</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Agregar Orden de Trabajo</span>
            </li>
        </ul>
    </div>

    <div class="panel mt-6 p-5 max-w-4xl mx-auto">
        <h2 class="text-xl font-bold mb-5">Agregar Orden de Trabajo</h2>

        @if (session('success'))
            <div class="alert alert-success mb-4">
                <strong>Éxito!</strong> {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="alert alert-danger mb-4">
                <strong>Error!</strong> {{ session('error') }}
            </div>
        @endif

        <div class="p-5">
            <form id="ordenTrabajoForm" class="grid grid-cols-1 md:grid-cols-2 gap-4" method="POST"
                action="{{ route('ordenes.storehelpdesk') }}">
                @csrf
                <!-- Número de Ticket -->
                <div>
                    <label for="numero_ticket" class="block text-sm font-medium">N. Ticket</label>
                    <input id="numero_ticket" name="numero_ticket" type="text" class="form-input w-full"
                        placeholder="Ingrese el número de ticket">
                </div>

                <!-- Cliente General -->
                <div>
                    <label for="idClienteGeneral" class="block text-sm font-medium">Cliente General</label>
                    <select id="idClienteGeneral" name="idClienteGeneral" class="select2 w-full" style="display:none">
                        <option value="" disabled selected>Seleccionar Cliente General</option>
                        @foreach ($clientesGenerales as $cliente)
                            <option value="{{ $cliente->idClienteGeneral }}">{{ $cliente->descripcion }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Cliente -->
                <div>
                    <label for="idCliente" class="block text-sm font-medium">Cliente</label>
                    <select id="idCliente" name="idCliente" class="select2 w-full" style="display:none">
                        <option value="" disabled selected>Seleccionar Cliente</option>
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->idCliente }}">{{ $cliente->nombre }} - {{ $cliente->documento }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tienda -->
                <div>
                    <label for="idTienda" class="block text-sm font-medium">Tienda</label>
                    <select id="idTienda" name="idTienda" class="select2 w-full" style="display:none">
                        <option value="" disabled selected>Seleccionar Tienda</option>
                        @foreach ($tiendas as $tienda)
                            <option value="{{ $tienda->idTienda }}">{{ $tienda->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Técnico -->
                <div>
                    <label for="idTecnico" class="block text-sm font-medium">Técnico</label>
                    <select id="idTecnico" name="idTecnico" class="select2 w-full" style="display:none">
                        <option value="" disabled selected>Seleccionar Técnico</option>
                        @foreach ($usuarios as $usuario)
                            <option value="{{ $usuario->idUsuario }}">{{ $usuario->Nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tipo de Servicio -->
                <div>
                    <label for="tipoServicio" class="block text-sm font-medium">Tipo de Servicio</label>
                    <select id="tipoServicio" name="tipoServicio" class="select2 w-full" style="display:none">
                        <option value="" disabled selected>Seleccionar Tipo de Servicio</option>
                        @foreach ($tiposServicio as $tipo)
                            <option value="{{ $tipo->idTipoServicio }}" data-nombre="{{ $tipo->nombre }}">
                                {{ $tipo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>



                <!-- Falla Reportada -->
                <div class="col-span-2">
                    <label for="fallaReportada" class="block text-sm font-medium">Falla Reportada</label>
                    <textarea id="fallaReportada" name="fallaReportada" rows="3" class="form-input w-full"
                        placeholder="Describa la falla reportada"></textarea>
                </div>

                <!-- Checkbox Es Recojo (Oculto por defecto) -->
                <div id="esRecojoContainer" class="hidden">
                    <label class="block text-sm font-medium">Opciones Adicionales</label>
                    <div class="flex items-center">
                        <input type="checkbox" id="esRecojo" name="esRecojo" class="form-checkbox">
                        <span>¿Es Recojo?</span>
                    </div>
                </div>

                <!-- Botones -->
                <div class="md:col-span-2 flex justify-end mt-4">
                    <a href="{{ route('ordenes.index') }}" class="btn btn-outline-danger">Cancelar</a>
                    <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Inicializa Select2
            document.querySelectorAll('.select2').forEach(function(select) {
                NiceSelect.bind(select, {
                    searchable: true
                });
            });

            // Lógica para mostrar el checkbox si el tipo de servicio es "SOPORTE ON SITE"
            const selectTipoServicio = document.getElementById("tipoServicio");
            const esRecojoContainer = document.getElementById("esRecojoContainer");

            function verificarTipoServicio() {
                const tipoSeleccionado = selectTipoServicio.options[selectTipoServicio.selectedIndex];
                const nombreTipo = tipoSeleccionado ? tipoSeleccionado.dataset.nombre : "";

                if (nombreTipo === "SOPORTE ON SITE") {
                    esRecojoContainer.classList.remove("hidden");
                } else {
                    esRecojoContainer.classList.add("hidden");
                }
            }

            // Evento de cambio en el select
            selectTipoServicio.addEventListener("change", verificarTipoServicio);

            // Verificar al cargar la página
            verificarTipoServicio();
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>

</x-layout.default>
