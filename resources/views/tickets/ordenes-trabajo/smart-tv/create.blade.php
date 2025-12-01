<x-layout.default>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">


    <style>
        .panel {
            overflow: visible !important;
            /* Asegura que el modal no restrinja contenido */
        }

        #map {
            height: 400px;
            width: 100%;
        }
        
        .loading {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath d='M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8z' opacity='.5'/%3E%3Cpath d='M12 2a10 10 0 0 0-10 10h2a8 8 0 0 1 8-8z'%3E%3CanimateTransform attributeName='transform' type='rotate' from='0 12 12' to='360 12 12' dur='1s' repeatCount='indefinite'/%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 20px 20px;
        }
    </style>

    <!-- Breadcrumb -->
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse mt-4">
            <li>
                <a href="{{ route('ordenes.smart') }}" class="text-primary hover:underline">Órdenes</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Agregar Orden de Trabajo</span>
            </li>
        </ul>

    </div>




    <!-- Contenedor principal -->
    <div x-data="{ openClienteModal: false, openClienteGeneralModal: false, openMarcaModal: false, openModeloModal: false }" class="panel mt-6 p-5 max-w-4x2 mx-auto">
        <h2 class="text-xl font-bold mb-5">Agregar Orden de Trabajo</h2>



        <div class="p-5">
            <form id="ordenTrabajoForm" class="grid grid-cols-1 md:grid-cols-2 gap-4" method="POST"
                action="{{ route('ordenes.storesmart') }}">
                @csrf



                <!-- Cliente: Seleccionar o crear nuevo -->
                <div class="col-span-1">
                    <div class="flex items-center space-x-2">
                        <label for="idCliente" class="block text-sm font-medium">Usuario Final</label>
                        @if(\App\Helpers\PermisoHelper::tienePermiso('AGREGAR USUARIO FINAL ORDEN DE TRABAJO SMART'))    
                        <button type="button" class="btn btn-primary p-1 mb-2"
                            @click="openClienteModal = true;     cargarClientesGenerales('idClienteGeneraloption'); ">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        @endif
                    
                    </div>
                    <!-- Se usa nice-select2 (clase select2) -->
                    <select id="idCliente" name="idCliente" style="width: 100%"></select>

                    </select>
                </div>


                <!-- Cliente General -->
                <div>


                    <div class="flex items-center space-x-2">
                        <label for="idClienteGeneral" class="block text-sm font-medium">Cliente General</label>
                        @if(\App\Helpers\PermisoHelper::tienePermiso('AGREGAR CLIENTE GENERAL ORDEN DE TRABAJO SMART'))
                        <button type="button" class="btn btn-primary p-1 mb-2" @click="openClienteGeneralModal = true">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 22 22"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        @endif
                    </div>


                    <!-- <label for="idClienteGeneral" class="block text-sm font-medium">Cliente General</label> -->

                    <select id="idClienteGeneral" name="idClienteGeneral" style="width: 100%">
                        <option value="" selected>Seleccionar Cliente General</option>
                    </select>
                </div>

                <!-- Número de Ticket -->
                <div>
                    <label for="nroTicket" class="block text-sm font-medium">N. Ticket</label>
                    <input id="nroTicket" name="nroTicket" type="text" class="form-input w-full"
                        placeholder="Ingrese el número de ticket">
                    <p id="errorTicket" class="text-sm text-red-500 mt-2 hidden"></p>
                </div>


                <!-- Contenedor de Tienda -->
                <div>
                    <div class="flex items-center space-x-2 ">
                        <label for="idTienda" class="block text-sm font-medium">Tienda</label>
                        @if(\App\Helpers\PermisoHelper::tienePermiso('CREAR TIENDA ORDEN DE TRABAJO SMART'))
                        <a href="{{ route('tienda.create') }}" class="btn btn-primary p-1" role="button"
                            target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </a>
                        @endif

                    </div>

                    <!-- Select para tiendas (solo si el cliente es tienda) -->
                    <div id="selectTiendaContainer">
                        <select id="idTienda" name="idTienda">
                            <option value="">Seleccionar Tienda</option>
                        </select>

                    </div>


                </div>


                <!-- Dirección -->
                <div>
                    <label for="direccion" class="block text-sm font-medium">Dirección / Referencia</label>
                    <input id="direccion" type="text" name="direccion" class="form-input w-full"
                        placeholder="Ingrese la dirección o referencia">
                </div>

                <!-- Fecha de Compra -->
                <div>
                    <label for="fechaCompra" class="block text-sm font-medium">Fecha de Compra</label>
                    <input id="fechaCompra" name="fechaCompra" type="date" class="form-input w-full"
                        placeholder="Seleccionar fecha">
                </div>
                <!-- Fecha de fecha_creacion -->
                <div>
                    <label for="fechaTicket" class="block text-sm font-medium">Fecha de Ticket</label>
                    <input id="fechaTicket" name="fecha_creacion" type="datetime-local" class="form-input w-full"
                        placeholder="Seleccionar fecha y hora">
                </div>


                <!-- Marca -->
                <div>
                    <div class="flex items-center space-x-2">
                        <label for="idMarca" class="block text-sm font-medium">Marca</label>
                        @if(\App\Helpers\PermisoHelper::tienePermiso('AGREGAR MARCA ORDEN DE TRABAJO SMART'))
                        <button type="button" class="btn btn-primary p-1 mb-2" @click="openMarcaModal = true">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        @endif
                    </div>
                    <!-- Select para las marcas -->
                    <!-- Preload (spinner o mensaje) sobre el select -->
                    <div class="relative">
                        <div id="preload"
                            class="flex justify-center items-center absolute inset-0 bg-gray-100 bg-opacity-50"
                            style="display:none;">
                            <span>Cargando...</span>
                        </div>

                        <select id="idMarca" name="idMarca" class="w-full">
                            <option value="" disabled selected>Seleccionar Marca</option>
                        </select>
                    </div>

                </div>


                <!-- Modelo -->
                <div>
                    <div class="flex items-center space-x-2">
                        <label for="idModelo" class="block text-sm font-medium">Modelo</label>
                        @if(\App\Helpers\PermisoHelper::tienePermiso('AGREGAR MODELO ORDEN DE TRABAJO SMART'))
                        <button type="button" class="btn btn-primary p-1 mb-2" @click="openModeloModal = true">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        @endif
                    </div>
                    <!-- Select para los modelos con preload -->
                    <div class="relative">
                        <select id="idModelo" name="idModelo" class="w-full"></select>
                        <!-- Opciones dinámicas se insertarán vía JS -->
                        </select>
                    </div>
                </div>

                <!-- Serie -->
                <div>
                    <label for="serie" class="block text-sm font-medium">N. Serie</label>
                    <input id="serie" name="serie" type="text" class="form-input w-full"
                        placeholder="Ingrese la serie">
                </div>


                <!-- Selección de Tickets -->
                <div id="ticketSelectionContainer">
                    <label for="selectTickets" class="block text-sm font-medium" style="display: none;">Tickets
                        Relacionados</label>
                    <select id="selectTickets" name="selectTickets" class="form-input w-full"
                        style="display: none;">
                        <option value="" selected>Seleccionar Ticket</option>
                    </select>
                </div>





                <!-- Falla Reportada -->
                <div class="col-span-2">
                    <label for="fallaReportada" class="block text-sm font-medium">Falla Reportada</label>
                    <textarea id="fallaReportada" name="fallaReportada" rows="3" class="form-input w-full"
                        placeholder="Describa la falla reportada"></textarea>
                </div>


                <div class="flex gap-12">
                    <div class="flex-1 mb-6">
                        <label for="esRecojo" class="block text-sm font-medium mb-2">Es recojo</label>
                        <div>
                            <label class="w-12 h-6 relative mt-3">
                                <input type="checkbox" id="esRecojo" name="esRecojo"
                                    class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer" />
                                <span
                                    class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                            </label>
                        </div>
                    </div>

                    <div id="evaluacionTiendaContainer" class="flex-1 mb-6  ">
                        <label for="evaluaciontienda" class="block text-sm font-medium mb-2">Evaluación a
                            Tienda</label>
                        <div>
                            <label class="w-12 h-6 relative mt-3">
                                <input type="checkbox" id="evaluaciontienda" name="evaluaciontienda"
                                    class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer" />
                                <span
                                    class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                            </label>
                        </div>
                    </div>

                    <div class="flex-1 mb-6">
                        <label for="entregaLab" class="block text-sm font-medium mb-2">Entrega a Lab.</label>
                        <div>
                            <label class="w-12 h-6 relative mt-3">
                                <input type="checkbox" id="entregaLab" name="entregaLab"
                                    class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer" />
                                <span
                                    class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <!-- Falla Reportada -->
                <div class="col-span-2">
                    <label for="linkubicacion" class="block text-sm font-medium">Link de Ubicacion</label>
                    <input id="linkubicacion" name="linkubicacion" rows="3" class="form-input w-full"
                        placeholder="Ingrese el link">
                </div>

                <!-- Latitud -->
                <div>
                    <label for="latitud" class="block text-sm font-medium">Latitud</label>
                    <input id="latitud" type="text" name="lat" class="form-input w-full"
                        placeholder="Latitud">
                </div>

                <!-- Longitud -->
                <div>
                    <label for="longitud" class="block text-sm font-medium">Longitud</label>
                    <input id="longitud" type="text" name="lng" class="form-input w-full"
                        placeholder="Longitud">
                </div>

                <input id="mapSearchBox" class="form-control" type="text" placeholder="Buscar lugar..."
                    style="width: 100%; max-width: 400px; margin-bottom: 10px;">
                <!-- Mapa -->
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-medium">Mapa</label>
                    <div id="map" class="w-full h-64 rounded border"></div>
                </div>

                <!-- Botones -->
                <div class="col-span-1 md:col-span-2 flex justify-end mt-4 gap-2">
                    <a href="{{ route('ordenes.index') }}" class="btn btn-outline-danger">Cancelar</a>
                    @if(\App\Helpers\PermisoHelper::tienePermiso('GUARDAR ORDEN DE TRABAJO SMART'))
                    <button type="submit" id="btnGuardar" class="btn btn-primary ml-4">Guardar</button>
                    @endif
                </div>
            </form>
        </div>




        <!-- Modal para crear nuevo Cliente (opcional) -->
        <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="openClienteModal && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="openClienteModal = false">
                <div x-show="openClienteModal" x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-3xl">
                    <!-- Header del Modal -->
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <h5 class="font-bold text-lg">Agregar Cliente</h5>
                        <button type="button" class="text-white-dark hover:text-dark"
                            @click="openClienteModal = false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" class="w-6 h-6">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <div class="modal-scroll">
                        <!-- Formulario para nuevo Cliente -->
                        <!-- Formulario -->
                        <form class="p-5 space-y-4" id="clienteForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            <!-- Asegúrate de incluir el token CSRF -->
                            <!-- ClienteGeneral -->
                            <div>
                                <label for="idClienteGeneral" class="block text-sm font-medium">Cliente
                                    General</label>
                                <select id="idClienteGeneraloption" name="idClienteGeneraloption[]"
                                    placeholder="Seleccionar Cliente General" multiple>
                                    @foreach ($clientesGenerales as $clienteGeneral)
                                    <option value="{{ $clienteGeneral->idClienteGeneral }}">
                                        {{ $clienteGeneral->descripcion }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>







                            <!-- Contenedor para mostrar los seleccionados -->
                            <div id="selected-items-container">
                                <strong>Seleccionados:</strong>
                                <div id="selected-items-list"
                                    class="overflow-y-auto border border-gray-300 rounded-md p-2 flex flex-wrap gap-2">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Nombre -->
                                <div>
                                    <label for="nombre" class="block text-sm font-medium">Nombre</label>
                                    <input id="nombre" type="text" name="nombre" class="form-input w-full"
                                        placeholder="Ingrese el nombre">
                                </div>
                                <!-- Tipo Documento -->
                                <div>
                                    <label for="idTipoDocumento" class="block text-sm font-medium">Tipo
                                        Documento</label>
                                    <select id="idTipoDocumento" name="idTipoDocumento" class="form-input w-full">
                                        <option value="" disabled selected>Seleccionar Tipo Documento</option>
                                        @foreach ($tiposDocumento as $tipoDocumento)
                                        <option value="{{ $tipoDocumento->idTipoDocumento }}">
                                            {{ $tipoDocumento->nombre }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Contenedor del switch "Es tienda" -->
                                <div id="esTiendaContainer" class="hidden mt-4">
                                    <label for="esTienda" class="block text-sm font-medium">¿Es tienda?</label>
                                    <div class="flex items-center">
                                        <!-- Campo hidden para enviar valor 0 si el switch no está activado -->
                                        <input type="hidden" name="esTienda" value="0">
                                        <div class="w-12 h-6 relative">
                                            <input type="checkbox" id="esTienda" name="esTienda"
                                                class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                                                value="1" />
                                            <span for="esTienda"
                                                class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                                        </div>
                                    </div>
                                </div>


                                <!-- Documento -->
                                <div>
                                    <label for="documento" class="block text-sm font-medium">Documento</label>
                                    <input id="documento" type="text" name="documento" class="form-input w-full"
                                        placeholder="Ingrese el documento">
                                </div>
                                <!-- Teléfono -->
                                <div>
                                    <label for="telefono" class="block text-sm font-medium">Teléfono</label>
                                    <input id="telefono" type="text" name="telefono" class="form-input w-full"
                                        placeholder="Ingrese el teléfono">
                                </div>
                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium">Email</label>
                                    <input id="email" type="email" class="form-input w-full" name="email"
                                        placeholder="Ingrese el email">
                                </div>
                                <!-- departamento -->
                                <div>
                                    <label for="departamento" class="block text-sm font-medium">Departamento</label>
                                    <select id="departamento" name="departamento" class="form-input w-full">
                                        <option value="" disabled selected>Seleccionar Departamento</option>
                                        @foreach ($departamentos as $departamento)
                                        <option value="{{ $departamento['id_ubigeo'] }}">
                                            {{ $departamento['nombre_ubigeo'] }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Provincia -->
                                <div>
                                    <label for="provincia" class="block text-sm font-medium">Provincia</label>
                                    <select id="provincia" name="provincia" class="form-input w-full" disabled>
                                        <option value="" disabled selected>Seleccionar Provincia</option>
                                    </select>
                                </div>

                                <!-- Distrito -->
                                <div>
                                    <label for="distrito" class="block text-sm font-medium">Distrito</label>
                                    <select id="distrito" name="distrito" class="form-input w-full" disabled>
                                        <option value="" disabled selected>Seleccionar Distrito</option>
                                    </select>
                                </div>
                                <!-- Dirección (Ocupa 2 columnas) -->
                                <!-- Dirección -->
                                <div>
                                    <label for="direccion" class="block text-sm font-medium">Dirección / Referencia</label>
                                    <input id="direccion" type="text" name="direccion" class="form-input w-full"
                                        placeholder="Ingrese la dirección o referencia" onchange="buscarDireccionEnMapa()">
                                </div>
                            </div>
                            <!-- Botones del modal -->
                            <div class="flex justify-end items-center mt-4">
                                <button type="button" class="btn btn-outline-danger"
                                    @click="openClienteModal = false">Cancelar</button>
                                @if(\App\Helpers\PermisoHelper::tienePermiso('GUARDAR USUARIO FINAL ORDEN DE TRABAJO SMART'))
                                <button type="submit" id="btnGuardarCliente"
                                    class="btn btn-primary ltr:ml-4 rtl:mr-4">Guardar</button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>








        <!-- Modal para crear nuevo Cliente GENERAL(opcional) -->
        <div x-data="{ open: false, imagenPreview: null, imagenActual: '/assets/images/file-preview.svg' }" x-show="openClienteGeneralModal"
            class="fixed inset-0 bg-black/60 z-[999] overflow-y-auto" style="display: none;"
            @click.self="openClienteGeneralModal = false">
            <div class="flex items-start justify-center min-h-screen px-4"
                @click.self="openClienteGeneralModal = false">
                <div x-show="openClienteGeneralModal" x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-3xl">
                    <!-- Header del Modal -->
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <h5 class="font-bold text-lg">Agregar Cliente General</h5>
                        <button type="button" class="text-white-dark hover:text-dark"
                            @click="openClienteGeneralModal = false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" class="w-6 h-6">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <div class="modal-scroll">
                        <!-- Formulario para nuevo Cliente -->

                        <form class="p-5 space-y-4" id="clientGeneralForm" enctype="multipart/form-data"
                            method="post">
                            @csrf
                            <!-- Asegúrate de incluir el token CSRF -->
                            <!-- Descripción -->
                            <div>
                                <label for="descripcion" class="block text-sm font-medium">Nombre</label>
                                <input type="text" id="descripcion" name="descripcion" class="form-input w-full"
                                    placeholder="Ingrese la descripción" required>
                            </div>
                            <!-- Foto -->
                            <div class="mb-5">
                                <label for="foto" class="block text-sm font-medium mb-2">Foto</label>
                                <!-- Campo de archivo -->
                                <input id="ctnFile" type="file" name="logo" accept="image/*" required
                                    class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold p-0 file:bg-primary/90 ltr:file:mr-5 rtl:file-ml-5 file:text-white file:hover:bg-primary w-full"
                                    @change="imagenPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : imagenActual" />

                                <!-- Contenedor de previsualización -->
                                <div
                                    class="mt-4 w-full border border-gray-300 rounded-lg overflow-hidden flex justify-center items-center">
                                    <template x-if="imagenPreview">
                                        <img :src="imagenPreview" alt="Previsualización de la imagen"
                                            class="w-40 h-40 object-cover">
                                    </template>
                                    <template x-if="!imagenPreview">
                                        <img src="/assets/images/file-preview.svg" alt="Imagen predeterminada"
                                            class="w-50 h-40 object-cover">
                                    </template>
                                </div>
                            </div>
                            <!-- Botones -->
                            <div class="flex justify-end items-center mb-4">
                                <button type="button" class="btn btn-outline-danger"
                                    @click="openClienteGeneralModal = false">Cancelar</button>
                                @if(\App\Helpers\PermisoHelper::tienePermiso('GUARDAR CLIENTE GENERAL ORDEN DE TRABAJO SMART'))
                                <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">Guardar</button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>






        <!-- Modal para crear nueva Marca(opcional) -->
        <div x-show="openMarcaModal" class="fixed inset-0 bg-black/60 z-[999] overflow-y-auto" style="display: none;"
            @click.self="openMarcaModal = false">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="openMarcaModal = false">
                <div x-show="openMarcaModal" x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-3xl">
                    <!-- Header del Modal -->
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <h5 class="font-bold text-lg">Agregar Marca</h5>
                        <button type="button" class="text-white-dark hover:text-dark"
                            @click="openMarcaModal = false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" class="w-6 h-6">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <div class="modal-scroll">
                        <!-- Formulario para nuevo Cliente -->

                        <form class="p-5 space-y-4" id="marcaForm" enctype="multipart/form-data" method="post">
                            @csrf
                            <!-- Asegúrate de incluir el token CSRF -->
                            <!-- Nombre -->
                            <div>
                                <label for="nombre" class="block text-sm font-medium">Nombre</label>
                                <input type="text" id="nombre" name="nombre" class="form-input w-full"
                                    placeholder="Ingrese el nombre de la marca" required>
                            </div>
                            <!-- Botones -->
                            <div class="flex justify-end items-center mt-4">
                                <button type="button" class="btn btn-outline-danger"
                                    @click="openMarcaModal = false">Cancelar</button>
                                    @if(\App\Helpers\PermisoHelper::tienePermiso('GUARDAR MARCA ORDEN DE TRABAJO SMART'))
                                <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">Guardar</button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>






        <!-- Modal para crear nueva Marca(opcional) -->
        <div x-show="openModeloModal" class="fixed inset-0 bg-black/60 z-[999] overflow-y-auto"
            style="display: none;" @click.self="openModeloModal = false">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="openModeloModal = false">
                <div x-show="openModeloModal" x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-3xl">
                    <!-- Header del Modal -->
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <h5 class="font-bold text-lg">Agregar Modelo</h5>
                        <button type="button" class="text-white-dark hover:text-dark"
                            @click="openModeloModal = false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" class="w-6 h-6">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <div class="modal-scroll">
                        <!-- Formulario para nuevo Cliente -->

                        <form class="p-5 space-y-4" id="modeloForm" enctype="multipart/form-data" method="post">
                            @csrf
                            <!-- Asegúrate de incluir el token CSRF -->
                            <!-- Nombre -->
                            <div>
                                <label for="nombre" class="block text-sm font-medium">Nombre</label>
                                <input id="nombre" name="nombre" class="form-input w-full"
                                    placeholder="Ingrese el nombre del modelo" required>
                            </div>
                            <!-- Marca -->
                            <div>
                                <label for="idMarcas" class="block text-sm font-medium">Marca</label>
                                <select id="idMarcas" name="idMarca">

                                </select>
                            </div>

                            <script>
                                let marcasCargadas = false; // Flag para verificar si las marcas ya han sido cargadas

                                // Función para cargar las marcas desde el servidor
                                function cargarMarcass() {
                                    const $select = $('#idMarcas');

                                    fetch('/get-marcas')
                                        .then(response => response.json())
                                        .then(data => {
                                            // Limpiar opciones
                                            $select.empty();
                                            $select.append('<option value="" disabled selected>Seleccione la Marca</option>');

                                            data.forEach(marca => {
                                                $select.append(new Option(marca.nombre, marca.idMarca));
                                            });

                                            // Destruir e inicializar Select2
                                            if ($select.hasClass('select2-hidden-accessible')) {
                                                $select.select2('destroy');
                                            }

                                            $select.select2({
                                                placeholder: 'Seleccione la Marca',
                                                width: '100%',
                                            });

                                            $select.show();
                                        })
                                        .catch(error => console.error('Error al cargar las marcas:', error));
                                }


                                // Ocultar el select de marcas inicialmente
                                let selectMarca = document.getElementById('idMarcas');
                                selectMarca.style.display = 'none'; // Esto oculta el select de marcas al principio

                                // Cargar las marcas solo si no se han cargado previamente
                                if (!marcasCargadas) {
                                    cargarMarcass();
                                    marcasCargadas = true;
                                }
                            </script>
                            <!-- Categoría -->
                            <div>
                                <label for="idCategoria" class="block text-sm font-medium">Categoria</label>
                                <select id="idCategoria" name="idCategoria" class="w-full" style="width: 100%">
                                    <option value="" disabled selected>Seleccione la Categoría</option>
                                    @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->idCategoria }}">{{ $categoria->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Botones -->
                            <div class="flex justify-end items-center mt-4">
                                <button type="button" class="btn btn-outline-danger"
                                    @click="openModeloModal = false">Cancelar</button>
                                @if(\App\Helpers\PermisoHelper::tienePermiso('GUARDAR MODELO ORDEN DE TRABAJO SMART'))
                                <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">Guardar</button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>





    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDQKbJK_7JMR45InjGsGuHQcsQ7toEVIf4&libraries=places&callback=initMap">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>



    <!-- SCRIPT FINAL -->
    <script>
     let map, marker, geocoder, autocomplete;
let buscarDireccionEnMapa; // Declarar la variable globalmente

function initMap() {
    const latInput = document.getElementById("latitud");
    const lngInput = document.getElementById("longitud");
    const linkInput = document.getElementById("linkubicacion");
    const direccionInput = document.getElementById("direccion");
    const mapContainer = document.getElementById("map");
    const searchInput = document.getElementById("mapSearchBox");

    const initialLat = parseFloat(latInput.value) || -11.957242;
    const initialLng = parseFloat(lngInput.value) || -77.0731862;

    map = new google.maps.Map(mapContainer, {
        center: {
            lat: initialLat,
            lng: initialLng
        },
        zoom: 15,
    });

    marker = new google.maps.Marker({
        position: {
            lat: initialLat,
            lng: initialLng
        },
        map: map,
        draggable: true,
    });

    geocoder = new google.maps.Geocoder();

    // 🔄 Actualiza inputs + dirección
    function updateInputs(lat, lng, direccion = "") {
        latInput.value = lat.toFixed(6);
        lngInput.value = lng.toFixed(6);
        linkInput.value = `https://www.google.com/maps?q=${lat},${lng}`;
        if (direccion) {
            direccionInput.value = direccion;
        } else {
            getAddressFromCoords(lat, lng);
        }
    }

    // 🔁 Geocodificación inversa
    function getAddressFromCoords(lat, lng) {
        geocoder.geocode({
            location: {
                lat,
                lng
            }
        }, (results, status) => {
            if (status === "OK" && results[0]) {
                const direccion = results[0].formatted_address;
                direccionInput.value = direccion;
                marker.setTitle(direccion);
            } else {
                console.warn("⚠️ Dirección no encontrada:", status);
            }
        });
    }

    // 🖱 Clic en el mapa
    map.addListener("click", function(event) {
        const lat = event.latLng.lat();
        const lng = event.latLng.lng();
        marker.setPosition({
            lat,
            lng
        });
        updateInputs(lat, lng);
    });

    // 📍 Drag marker
    marker.addListener("dragend", () => {
        const pos = marker.getPosition();
        updateInputs(pos.lat(), pos.lng());
    });

    // 🔍 Autocompletado para búsqueda
    if (searchInput) {
        autocomplete = new google.maps.places.Autocomplete(searchInput);
        autocomplete.bindTo("bounds", map);

        autocomplete.addListener("place_changed", () => {
            const place = autocomplete.getPlace();
            if (!place.geometry || !place.geometry.location) return;

            const loc = place.geometry.location;
            map.panTo(loc);
            map.setZoom(17);
            marker.setPosition(loc);
            updateInputs(loc.lat(), loc.lng(), place.formatted_address || "");
        });
    }

    // ✏️ Inputs de coordenadas
    function updateMarker() {
        const lat = parseFloat(latInput.value);
        const lng = parseFloat(lngInput.value);
        if (!isNaN(lat) && !isNaN(lng)) {
            const newPos = {
                lat,
                lng
            };
            marker.setPosition(newPos);
            map.setCenter(newPos);
            getAddressFromCoords(lat, lng);
        }
    }

    latInput.addEventListener("change", updateMarker);
    lngInput.addEventListener("change", updateMarker);

    // FUNCIÓN PARA BUSCAR DIRECCIÓN EN EL MAPA (VERSIÓN CORREGIDA)
    buscarDireccionEnMapa = function() {
        const direccionTexto = direccionInput.value.trim();
        
        if (!direccionTexto) {
            toastr.warning("Ingresa una dirección para buscar en el mapa.");
            return;
        }
        
        // Mostrar loading
        direccionInput.classList.add('loading');
        
        geocoder.geocode({
            'address': direccionTexto
        }, function(results, status) {
            direccionInput.classList.remove('loading');
            
            if (status === google.maps.GeocoderStatus.OK) {
                const location = results[0].geometry.location;
                const direccionCompleta = results[0].formatted_address;
                
                // Actualizar el buscador del mapa
                if (searchInput) {
                    searchInput.value = direccionCompleta;
                }
                
                // Mover el mapa
                map.setCenter(location);
                map.setZoom(17);
                marker.setPosition(location);
                
                // Actualizar coordenadas y link
                updateInputs(location.lat(), location.lng(), direccionCompleta);
                
                toastr.success("Dirección encontrada en el mapa.");
            } else {
                console.error("Error al geocodificar:", status);
                toastr.error("No se pudo encontrar la dirección. Intenta con una dirección más específica.");
            }
        });
    };

    // Agregar evento al campo de dirección (SOLUCIÓN ALTERNATIVA)
    direccionInput.addEventListener('change', buscarDireccionEnMapa);
    
    // También agregar evento al perder el foco
    direccionInput.addEventListener('blur', function() {
        if (this.value.trim() && !this.classList.contains('loading')) {
            buscarDireccionEnMapa();
        }
    });

    // FUNCIÓN MEJORADA PARA EXTRAER COORDENADAS DE CUALQUIER LINK DE GOOGLE MAPS
    function extractCoordinates(url) {
        console.log("🔗 Procesando URL:", url);
        
        let lat, lng;
        
        // Lista de patrones para diferentes formatos de Google Maps
        const patterns = [
            // 1. Formato con @: https://www.google.com/maps/@-12.123456,-77.123456,15z
            /@(-?\d+\.\d+),(-?\d+\.\d+)/,
            
            // 2. Formato con !3d y !4d: https://www.google.com/maps/place/.../data=!3m1!4b1!4m5!3m4!1s0x0:0x0!8m2!3d-12.0464!4d-77.0428
            /!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/g,
            
            // 3. Formato con /place/ y coordenadas: https://www.google.com/maps/place/Lima/@-12.0464,-77.0428,15z
            /\/place\/.*?@(-?\d+\.\d+),(-?\d+\.\d+)/,
            
            // 4. Formato con parámetro q: https://www.google.com/maps?q=-12.0464,-77.0428
            /q=(-?\d+\.\d+),(-?\d+\.\d+)/,
            
            // 5. Formato con parámetro ll: https://www.google.com/maps?ll=-12.0464,-77.0428
            /ll=(-?\d+\.\d+),(-?\d+\.\d+)/,
            
            // 6. Formato con /search/: https://www.google.com/maps/search/restaurante/@-12.0464,-77.0428,15z
            /\/search\/.*?@(-?\d+\.\d+),(-?\d+\.\d+)/,
            
            // 7. Coordenadas directas en la URL: https://www.google.com/maps/-12.0464,-77.0428,15z
            /maps\/(\-?\d+\.\d+),(\-?\d+\.\d+)/,
            
            // 8. Formato con /@ solo: /@-12.0464,-77.0428,15z
            /\/@(-?\d+\.\d+),(-?\d+\.\d+)/,
            
            // 9. Formato antiguo con search y coordenadas: https://www.google.com/maps/search/-12.0464,-77.0428
            /\/search\/(\-?\d+\.\d+),(\-?\d+\.\d+)/,
            
            // 10. Formato con data parameter: data=!3m1!4b1!4m5!3m4!1s0x0:0x0!8m2!3d-12.0464!4d-77.0428
            /data=.*?3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/,
        ];
        
        // Intentar todos los patrones
        for (const pattern of patterns) {
            const match = url.match(pattern);
            if (match) {
                // Para patrones globales (como !3d!4d), tomar la última coincidencia
                if (pattern.toString().includes('g')) {
                    const allMatches = [...url.matchAll(pattern)];
                    if (allMatches.length > 0) {
                        const lastMatch = allMatches[allMatches.length - 1];
                        lat = parseFloat(lastMatch[1]);
                        lng = parseFloat(lastMatch[2]);
                    }
                } else {
                    lat = parseFloat(match[1]);
                    lng = parseFloat(match[2]);
                }
                
                if (!isNaN(lat) && !isNaN(lng)) {
                    console.log("✅ Coordenadas extraídas:", lat, lng);
                    break;
                }
            }
        }
        
        // Si encontramos coordenadas
        if (!isNaN(lat) && !isNaN(lng)) {
            marker.setPosition({
                lat,
                lng
            });
            map.setCenter({
                lat,
                lng
            });
            updateInputs(lat, lng);
            return true;
        } else {
            console.warn("⚠️ No se pudieron extraer coordenadas del link");
            toastr.warning("No se pudieron extraer coordenadas del link. Verifica que sea un link válido de Google Maps.");
            return false;
        }
    }

    // FUNCIÓN PARA EXPANDIR URL CORTA (maps.app.goo.gl)
    async function expandShortURL(shortURL) {
        console.log("🔗 Expandir URL corta:", shortURL);
        
        try {
            // Usar TU endpoint PHP para expandir la URL
            const response = await fetch(`/ubicacion/direccion.php?url=${encodeURIComponent(shortURL)}`);
            
            if (response.ok) {
                const data = await response.json();
                
                if (data.expanded_url) {
                    console.log("✅ URL expandida:", data.expanded_url);
                    
                    // Intentar extraer coordenadas de la URL expandida
                    if (extractCoordinates(data.expanded_url)) {
                        return;
                    }
                }
            }
            
            // Si falla la expansión, intentar extraer directamente
            console.log("⚠️ Falló la expansión, intentando extraer directamente");
            if (!extractCoordinates(shortURL)) {
                // Si no funciona, mostrar ayuda
                toastr.warning("No se pudo procesar el link corto. Intenta con el link completo de Google Maps.");
            }
            
        } catch (error) {
            console.error("❌ Error al expandir URL:", error);
            
            // Intentar extraer directamente como fallback
            if (!extractCoordinates(shortURL)) {
                toastr.error("Error al procesar el link. Intenta con el link completo de Google Maps.");
            }
        }
    }

    // PROCESAR LINK CUANDO CAMBIA
    linkInput.addEventListener("change", () => {
        const link = linkInput.value.trim();
        if (!link) return;
        
        // Mostrar loading
        linkInput.classList.add('loading');
        
        if (link.includes("maps.app.goo.gl") || link.includes("goo.gl/maps")) {
            expandShortURL(link);
        } else {
            extractCoordinates(link);
        }
        
        // Quitar loading después de 1 segundo
        setTimeout(() => {
            linkInput.classList.remove('loading');
        }, 1000);
    });
    
    // También procesar cuando se pega con Ctrl+V
    linkInput.addEventListener("paste", (e) => {
        setTimeout(() => {
            const link = linkInput.value.trim();
            if (link) {
                linkInput.classList.add('loading');
                if (link.includes("maps.app.goo.gl") || link.includes("goo.gl/maps")) {
                    expandShortURL(link);
                } else {
                    extractCoordinates(link);
                }
                setTimeout(() => {
                    linkInput.classList.remove('loading');
                }, 1000);
            }
        }, 100);
    });

    // Primera carga
    updateInputs(initialLat, initialLng);
}
    </script>
    <script>
       

        // Función para mostrar un toastr de error
        function showToast(message) {
            toastr.error(message, "Error", {
                positionClass: "toast-top-right", // Posición en la pantalla
                timeOut: 3000, // Duración de la notificación
                closeButton: true
            });
        }

        // Validación en tiempo real para el número de ticket
        document.getElementById('nroTicket').addEventListener('input', function() {
            const inputTicket = document.getElementById('nroTicket');
            const errorTicket = document.getElementById('errorTicket');
            const nroTicketValue = inputTicket.value.trim();

            if (nroTicketValue === "") {
                inputTicket.classList.remove('border-red-500', 'border-green-500');
                errorTicket.textContent = "Campo vacío";
                errorTicket.classList.remove('hidden');
            } else {
                fetch(`/validar-ticket/${nroTicketValue}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.existe) {
                            inputTicket.classList.add('border-red-500');
                            inputTicket.classList.remove('border-green-500');
                            errorTicket.textContent =
                                'El número de ticket ya está en uso. Por favor, ingrese otro número.';
                            errorTicket.classList.remove('hidden');
                            showToast('El número de ticket ya está en uso. Por favor, ingrese otro número.');
                        } else {
                            inputTicket.classList.remove('border-red-500');
                            inputTicket.classList.add('border-green-500');
                            errorTicket.classList.add('hidden');
                        }
                    })
                    .catch(error => {
                        console.error('Error al verificar el ticket:', error);
                        inputTicket.classList.add('border-red-500');
                        errorTicket.textContent =
                            'Ocurrió un error al verificar el ticket. Inténtelo de nuevo más tarde.';
                        errorTicket.classList.remove('hidden');
                        showToast('Ocurrió un error al verificar el ticket. Inténtelo de nuevo más tarde.');
                    });
            }
        });

        // Lista de campos obligatorios
        const camposObligatorios = [
            'idCliente', 'idClienteGeneral',
            'direccion', 'fechaCompra', 'idMarca', 'idTienda', 'idModelo',
            'serie', 'fallaReportada', 'fechaTicket', 'linkubicacion', 'nroTicket'
        ];

        // Validación en tiempo real para campos obligatorios
        camposObligatorios.forEach(campo => {
            const input = document.getElementById(campo);

            // Para selects (Select2), usar change
            if (input.tagName === 'SELECT' || input.type === 'date') {
                input.addEventListener('change', function() {
                    validateCampo(input);
                    if (campo === 'fechaCompra') {
                        validateFechaCompra(input);
                    }
                });
            } else {
                input.addEventListener('input', function() {
                    validateCampo(input);
                });

                input.addEventListener('focusout', function() {
                    validateCampo(input);
                });
            }
        });

        // Función para validar un campo (compatible con Select2)
        function validateCampo(input) {
            const errorId = `error-${input.id}`;
            let errorText = document.getElementById(errorId);

            // Obtener valor de manera diferente para Select2
            let valor;
            if ($(input).hasClass('select2-hidden-accessible')) {
                valor = $(input).select2('val');
            } else {
                valor = input.value;
            }

            if (!valor || valor.length === 0 || valor === "") {
                $(input).addClass('border-red-500');
                if (!errorText) {
                    errorText = document.createElement('p');
                    errorText.id = errorId;
                    errorText.classList.add('text-sm', 'text-red-500', 'mt-1');
                    $(input).parent().append(errorText);
                }
                errorText.textContent = "Campo vacío";
            } else {
                $(input).removeClass('border-red-500');
                if (errorText) {
                    errorText.remove();
                }
            }
        }
        // Para cada select con Select2
        $('#idCliente, #idClienteGeneral, #idTienda, #idMarca, #idModelo').on('select2:select', function(e) {
            validateCampo(this);
            // Fuerza la actualización del error
            $(`#error-${this.id}`).remove();
            $(this).removeClass('border-red-500');
        });
        // Validación específica para fecha de compra
        function validateFechaCompra(input) {
            const fechaCompra = new Date(input.value);
            const fechaHoy = new Date();
            fechaHoy.setHours(0, 0, 0, 0);

            if (fechaCompra > fechaHoy) {
                input.classList.add('border-red-500');
                let errorText = document.getElementById(`error-${input.id}`);
                if (!errorText) {
                    errorText = document.createElement('p');
                    errorText.id = `error-${input.id}`;
                    errorText.classList.add('text-sm', 'text-red-500', 'mt-1');
                    input.parentNode.appendChild(errorText);
                }
                errorText.textContent = "La fecha de compra no puede ser mayor a la fecha actual.";
                showToast('La fecha de compra no puede ser mayor a la fecha actual.');
            } else {
                input.classList.remove('border-red-500');
                let errorText = document.getElementById(`error-${input.id}`);
                if (errorText) {
                    errorText.remove();
                }
            }
        }

        // Validación al enviar el formulario
        document.getElementById('ordenTrabajoForm').addEventListener('submit', function(event) {
            let errorFound = false;
            let errorMessages = [];

            camposObligatorios.forEach(campo => {
                const input = document.getElementById(campo);
                const label = document.querySelector(`label[for="${campo}"]`);
                const nombreCampo = label ? label.innerText.trim() : campo;
                const valor = $(input).val(); // soporte para Select2

                if (!valor || valor.length === 0) {
                    errorFound = true;
                    validateCampo(input);
                    errorMessages.push(
                        `El campo "${nombreCampo}" está vacío. Por favor, complete el campo.`);
                }
            });

            const nroTicketInput = document.getElementById('nroTicket');
            const errorTicket = document.getElementById('errorTicket');
            if (nroTicketInput.value.trim() === "" || nroTicketInput.classList.contains('border-red-500')) {
                errorFound = true;
                errorTicket.classList.remove('hidden');
                errorMessages.push('El número de ticket está vacío o inválido.');
            }

            if (errorFound) {
                event.preventDefault();
                showToast(errorMessages[0]);
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar Select2 para categoría cuando el modal se abre
            const modeloModal = document.querySelector('[x-show="openModeloModal"]');

            // Observar cuando el modal se abre
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                        const isModalOpen = !modeloModal.style.display || modeloModal.style.display !== 'none';
                        if (isModalOpen) {
                            initializeModeloModal();
                        }
                    }
                });
            });

            if (modeloModal) {
                observer.observe(modeloModal, {
                    attributes: true
                });
            }

            function initializeModeloModal() {
                // Inicializar Select2 para categoría
                const categoriaSelect = document.getElementById('idCategoria');
                if (categoriaSelect && !$(categoriaSelect).hasClass('select2-hidden-accessible')) {
                    $(categoriaSelect).select2({
                        placeholder: 'Seleccione la Categoría',
                        width: '100%',
                        dropdownParent: $(categoriaSelect).closest('.modal-scroll')
                    });
                    // Mostrar el select después de inicializar
                    categoriaSelect.style.display = 'block';
                }

                // Cargar marcas si no se han cargado
                const marcasSelect = document.getElementById('idMarcas');
                if (marcasSelect && marcasSelect.children.length <= 1) {
                    cargarMarcasModal();
                }
            }

            function cargarMarcasModal() {
                const $select = $('#idMarcas');

                fetch('/get-marcas')
                    .then(response => response.json())
                    .then(data => {
                        $select.empty();
                        $select.append('<option value="" disabled selected>Seleccione la Marca</option>');

                        data.forEach(marca => {
                            $select.append(new Option(marca.nombre, marca.idMarca));
                        });

                        // Inicializar Select2 para marcas
                        if (!$select.hasClass('select2-hidden-accessible')) {
                            $select.select2({
                                placeholder: 'Seleccione la Marca',
                                width: '100%',
                                dropdownParent: $select.closest('.modal-scroll')
                            });
                        }

                        $select.show();
                    })
                    .catch(error => console.error('Error al cargar las marcas:', error));
            }
        });
    </script>


    <script>
        const checkboxes = document.querySelectorAll('#esRecojo, #evaluaciontienda, #entregaLab');
        const evaluacionTiendaDiv = document.getElementById('evaluacionTiendaContainer');
        const evaluacionTiendaCheckbox = document.getElementById('evaluaciontienda');
        const clienteSelect = document.getElementById('idCliente');

        // Lógica: solo permitir un checkbox activo a la vez
        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', () => {
                if (checkbox.checked) {
                    console.log(`✅ Activado: ${checkbox.id}`);
                    checkboxes.forEach((cb) => {
                        if (cb !== checkbox) {
                            cb.checked = false;
                            console.log(`⛔ Desactivado: ${cb.id}`);
                        }
                    });
                } else {
                    console.log(`❌ Desmarcado: ${checkbox.id}`);
                }
            });
        });
    </script>

    <script src="{{ asset('assets/js/ubigeo.js') }}"></script>
    <script src="{{ asset('assets/js/tickets/smart/configuraciones.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/tienda/tiendavalidaciones.js') }}"></script> --}}

</x-layout.default>