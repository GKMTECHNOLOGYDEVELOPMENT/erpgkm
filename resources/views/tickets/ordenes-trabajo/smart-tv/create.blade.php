<x-layout.default>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
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
    <div x-data="{ openClienteModal: false, openClienteGeneralModal: false, openMarcaModal: false, openModeloModal: false }"
        class="panel mt-6 p-5 max-w-4x2 mx-auto">
        <h2 class="text-xl font-bold mb-5">Agregar Orden de Trabajo</h2>



        <div class="p-5">
            <form id="ordenTrabajoForm" class="grid grid-cols-1 md:grid-cols-2 gap-4" method="POST"
                action="{{ route('ordenes.storesmart') }}">
                @csrf



                <!-- Cliente: Seleccionar o crear nuevo -->
                <div class="col-span-1">
                    <div class="flex items-center space-x-2">
                        <label for="idCliente" class="block text-sm font-medium">Usuario Final</label>
                        <button type="button" class="btn btn-primary p-1 mb-2"
                            @click="openClienteModal = true;     cargarClientesGenerales('idClienteGeneraloption'); ">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>
                    <!-- Se usa nice-select2 (clase select2) -->
                    <select id="idCliente" name="idCliente">
                    </select>
                </div>


                <!-- Cliente General -->
                <div>


                    <div class="flex items-center space-x-2">
                        <label for="idClienteGeneral" class="block text-sm font-medium">Cliente General</label>
                        <button type="button" class="btn btn-primary p-1 mb-2" @click="openClienteGeneralModal = true">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 22 22"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>


                    <!-- <label for="idClienteGeneral" class="block text-sm font-medium">Cliente General</label> -->

                    <select id="idClienteGeneral" name="idClienteGeneral" class="form-input w-full ">
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
                        <a href="{{ route('tienda.create') }}" class="btn btn-primary p-1" role="button"
                            target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </a>
                    </div>

                    <!-- Select para tiendas (solo si el cliente es tienda) -->
                    <div id="selectTiendaContainer">
                        <select id="idTienda" name="idTienda" style="display: none;">
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
                        <button type="button" class="btn btn-primary p-1 mb-2" @click="openMarcaModal = true">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>
                    <!-- Select para las marcas -->
                    <!-- Preload (spinner o mensaje) sobre el select -->
                    <div class="relative">
                        <div id="preload"
                            class="flex justify-center items-center absolute inset-0 bg-gray-100 bg-opacity-50"
                            style="display:none;">
                            <span>Cargando...</span>
                        </div>

                        <!-- Select para las marcas -->
                        <select style="display: none;" id="idMarca" name="idMarca">

                        </select>
                    </div>
                </div>


                <!-- Modelo -->
                <div>
                    <div class="flex items-center space-x-2">
                        <label for="idModelo" class="block text-sm font-medium">Modelo</label>
                        <button type="button" class="btn btn-primary p-1 mb-2" @click="openModeloModal = true">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>
                    <!-- Select para los modelos con preload -->
                    <div class="relative">
                        <select style="display: none;" id="idModelo" name="idModelo">
                            <!-- Opciones dinámicas se insertarán vía JS -->
                        </select>
                    </div>
                </div>

                <!-- Serie -->
                <div>
                    <label for="serie" class="block text-sm font-medium">N. Serie</label>
                    <input id="serie" name="serie" type="text" class="form-input w-full" placeholder="Ingrese la serie">
                </div>


                <!-- Selección de Tickets -->
                <div id="ticketSelectionContainer">
                    <label for="selectTickets" class="block text-sm font-medium" style="display: none;">Tickets
                        Relacionados</label>
                    <select id="selectTickets" name="selectTickets" class="form-input w-full" style="display: none;">
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

                    <div id="evaluacionTiendaContainer" class="flex-1 mb-6 hidden">
                        <label for="evaluaciontienda" class="block text-sm font-medium mb-2">Evaluación a Tienda</label>
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
                    <input id="latitud" type="text" name="lat" class="form-input w-full" placeholder="Latitud">
                </div>

                <!-- Longitud -->
                <div>
                    <label for="longitud" class="block text-sm font-medium">Longitud</label>
                    <input id="longitud" type="text" name="lng" class="form-input w-full" placeholder="Longitud">
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
                    <button type="submit" id="btnGuardar" class="btn btn-primary ml-4">Guardar</button>
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
                        <button type="button" class="text-white-dark hover:text-dark" @click="openClienteModal = false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                class="w-6 h-6">
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
                                    placeholder="Seleccionar Cliente General" multiple style="display:none">
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
                                    <select id="idTipoDocumento" name="idTipoDocumento" class="select2 w-full"
                                        style="display:none">
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
                                <div>
                                    <label for="direccion" class="block text-sm font-medium">Dirección</label>
                                    <input id="direccion" type="text" name="direccion" class="form-input w-full"
                                        placeholder="Ingrese el direccion">
                                </div>
                            </div>
                            <!-- Botones del modal -->
                            <div class="flex justify-end items-center mt-4">
                                <button type="button" class="btn btn-outline-danger"
                                    @click="openClienteModal = false">Cancelar</button>
                                <button type="submit" id="btnGuardarCliente"
                                    class="btn btn-primary ltr:ml-4 rtl:mr-4">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>








        <!-- Modal para crear nuevo Cliente GENERAL(opcional) -->
        <div x-data="{ open: false, imagenPreview: null, imagenActual: '/assets/images/file-preview.svg' }"
            x-show="openClienteGeneralModal" class="fixed inset-0 bg-black/60 z-[999] overflow-y-auto"
            style="display: none;" @click.self="openClienteGeneralModal = false">
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
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                class="w-6 h-6">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <div class="modal-scroll">
                        <!-- Formulario para nuevo Cliente -->

                        <form class="p-5 space-y-4" id="clientGeneralForm" enctype="multipart/form-data" method="post">
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
                                <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">Guardar</button>
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
                        <button type="button" class="text-white-dark hover:text-dark" @click="openMarcaModal = false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                class="w-6 h-6">
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
                                <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>






        <!-- Modal para crear nueva Marca(opcional) -->
        <div x-show="openModeloModal" class="fixed inset-0 bg-black/60 z-[999] overflow-y-auto" style="display: none;"
            @click.self="openModeloModal = false">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="openModeloModal = false">
                <div x-show="openModeloModal" x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-3xl">
                    <!-- Header del Modal -->
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <h5 class="font-bold text-lg">Agregar Modelo</h5>
                        <button type="button" class="text-white-dark hover:text-dark" @click="openModeloModal = false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                class="w-6 h-6">
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
                                    const select = document.getElementById('idMarcas');

                                    // Realizamos la solicitud fetch para obtener las marcas
                                    fetch('/get-marcas')
                                        .then(response => response.json()) // Convertir la respuesta en formato JSON
                                        .then(data => {
                                            // Limpiar las opciones actuales del select
                                            select.innerHTML =
                                                '<option value="" disabled selected>Seleccione la Marca</option>';

                                            // Llenar el select con las marcas obtenidas
                                            data.forEach(marca => {
                                                const option = document.createElement('option');
                                                option.value = marca.idMarca;
                                                option.textContent = marca.nombre;
                                                select.appendChild(option);
                                            });

                                            // Si ya existe una instancia previa de nice-select, la destruye
                                            if (select.niceSelectInstance) {
                                                select.niceSelectInstance.destroy();
                                            }

                                            // Inicializar nice-select (si usas nice-select) y guardar la instancia
                                            select.niceSelectInstance = NiceSelect.bind(select, {
                                                searchable: true
                                            });

                                            // Mostrar el select después de cargar las marcas
                                            select.style.display = 'block'; // O 'inline-block' según tu diseño
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
                                <select id="idCategoria" name="idCategoria" class="select2 w-full" style="display:none"
                                    required>
                                    <option value="" disabled selected>Seleccione la Categoría</option>
                                    @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->idCategoria }}">{{ $categoria->nombre }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Botones -->
                            <div class="flex justify-end items-center mt-4">
                                <button type="button" class="btn btn-outline-danger"
                                    @click="openModeloModal = false">Cancelar</button>
                                <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script async
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD1XZ84dlEl7hAAsMR-myjaMpPURq5G3tE&libraries=places&callback=initMap">
    </script>



    <!-- SCRIPT FINAL -->
    <script>
        let map, marker, geocoder, autocomplete;

        function initMap() {
            const latInput = document.getElementById("latitud");
            const lngInput = document.getElementById("longitud");
            const linkInput = document.getElementById("linkubicacion");
            const direccionInput = document.getElementById("direccion");
            const mapContainer = document.getElementById("map");
            const input = document.getElementById("mapSearchBox");

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

            // 🔍 Autocompletado
            autocomplete = new google.maps.places.Autocomplete(input);
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

            // URL Maps -> Coordenadas
            function extractCoordinates(url) {
                let lat, lng;
                if (url.includes("/search/")) {
                    let clean = url.split("/search/")[1].split("?")[0].replace(/\+/g, " ");
                    let coords = clean.split(",").map(c => c.trim());
                    if (coords.length === 2) {
                        lat = parseFloat(coords[0]);
                        lng = parseFloat(coords[1]);
                    }
                }
                if (!lat || !lng) {
                    let regex = /!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/g;
                    let matches = [...url.matchAll(regex)];
                    if (matches.length > 0) {
                        let last = matches[matches.length - 1];
                        lat = parseFloat(last[1]);
                        lng = parseFloat(last[2]);
                    }
                }

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
                } else {
                    console.warn("⚠️ Coordenadas inválidas en el link");
                }
            }

            async function expandShortURL(shortURL) {
                try {
                    const response = await fetch(
                        `https://beyritech.com/ubicacion/direccion.php?url=${encodeURIComponent(shortURL)}`);
                    const data = await response.json();
                    if (data.expanded_url) {
                        extractCoordinates(data.expanded_url);
                    }
                } catch (err) {
                    console.error("❌ Error al expandir URL:", err);
                }
            }

            linkInput.addEventListener("change", () => {
                const link = linkInput.value.trim();
                if (link.includes("maps.app.goo.gl")) {
                    expandShortURL(link);
                } else {
                    extractCoordinates(link);
                }
            });

            // Primera carga
            updateInputs(initialLat, initialLng);
        }

        //     // ✅ Select2 personalizado
        //     document.querySelectorAll('.select2').forEach(function (select) {
        //     NiceSelect.bind(select, { searchable: true });
        // });
    </script>
    <script>
        // document.addEventListener("DOMContentLoaded", function() {
        //     console.log("🔹 DOM completamente cargado");

        //     // Obtener referencias a los elementos
        //     const latInput = document.getElementById("latitud");
        //     const lngInput = document.getElementById("longitud");
        //     const linkInput = document.getElementById("linkubicacion");
        //     const mapContainer = document.getElementById("map");

        //     if (!latInput || !lngInput || !linkInput || !mapContainer) {
        //         console.error("❌ No se encontraron los campos requeridos.");
        //         return;
        //     }

        //     console.log("✅ Campos de latitud, longitud y link de ubicación encontrados");

        //     // Coordenadas iniciales
        //     const initialLat = -11.957242;
        //     const initialLng = -77.0731862;

        //     console.log(`📍 Coordenadas iniciales: Lat: ${initialLat}, Lng: ${initialLng}`);

        //     // **Inicializar el mapa si aún no existe**
        //     if (!window.map || !document.getElementById("map")._leaflet_id) {
        //         console.log("🔄 Inicializando el mapa...");
        //         window.map = L.map("map").setView([initialLat, initialLng], 15);
        //         L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        //             attribution: "&copy; OpenStreetMap contributors"
        //         }).addTo(window.map);
        //     } else {
        //         console.log("✅ Mapa ya estaba inicializado.");
        //     }

        //     // **Crear el marcador inicial y hacerlo arrastrable**
        //     let marker = L.marker([initialLat, initialLng], {
        //         draggable: true
        //     }).addTo(window.map);

        //     console.log("✅ Mapa y marcador inicializados correctamente");

        //     // **Actualizar el marcador y centrar el mapa**
        //     function updateMarker() {
        //         const lat = parseFloat(latInput.value);
        //         const lng = parseFloat(lngInput.value);

        //         console.log(`🔄 Intentando actualizar marcador a: Lat: ${lat}, Lng: ${lng}`);

        //         // Validar coordenadas
        //         if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
        //             marker.setLatLng([lat, lng]); // Mover marcador
        //             window.map.setView([lat, lng], 15); // Centrar el mapa
        //             console.log("✅ Marcador y mapa actualizados correctamente");
        //         } else {
        //             console.warn("⚠️ Coordenadas inválidas, el marcador no se actualizará");
        //         }
        //     }

        //     // **Extraer coordenadas correctas del link largo de Google Maps**
        //     function extractCoordinates(url) {
        //         console.log(`🔗 Analizando URL: ${url}`);

        //         let lat, lng;

        //         // **1️⃣ Si la URL tiene '/search/', limpiar y extraer coordenadas**
        //         if (url.includes("/search/")) {
        //             console.log("🛠 Se detectó una URL con 'search/', limpiando...");

        //             // Tomar solo lo que sigue después de "/search/"
        //             let cleanURL = url.split("/search/")[1];

        //             // Eliminar parámetros adicionales (como ?entry=tts)
        //             cleanURL = cleanURL.split("?")[0];

        //             // Reemplazar `+` por espacio en caso de que haya codificaciones extrañas
        //             cleanURL = cleanURL.replace(/\+/g, " ");

        //             // Separar la latitud y longitud (evitando espacios extra)
        //             let coords = cleanURL.split(",").map(c => c.trim());

        //             if (coords.length === 2) {
        //                 lat = parseFloat(coords[0]);
        //                 lng = parseFloat(coords[1]);

        //                 if (!isNaN(lat) && !isNaN(lng)) {
        //                     console.log(`✅ Coordenadas extraídas después de limpiar: Lat: ${lat}, Lng: ${lng}`);
        //                 } else {
        //                     console.warn("⚠️ No se pudieron convertir correctamente las coordenadas.");
        //                     return;
        //                 }
        //             }
        //         }

        //         // **2️⃣ Intentar extraer coordenadas con la regex clásica (!3d...!4d...)**
        //         if (!lat || !lng) {
        //             let regexClassic = /!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/g;
        //             let matchesClassic = [...url.matchAll(regexClassic)];

        //             if (matchesClassic.length > 0) {
        //                 let lastMatch = matchesClassic[matchesClassic.length - 1]; // Tomar la última coincidencia
        //                 lat = parseFloat(lastMatch[1]);
        //                 lng = parseFloat(lastMatch[2]);
        //                 console.log(`✅ Coordenadas extraídas (formato clásico): Lat: ${lat}, Lng: ${lng}`);
        //             }
        //         }

        //         // **3️⃣ Si no encontró coordenadas, mostrar advertencia**
        //         if (isNaN(lat) || isNaN(lng)) {
        //             console.warn("⚠️ No se encontraron coordenadas válidas en la URL proporcionada.");
        //             return;
        //         }

        //         // **Asignar valores a los inputs**
        //         latInput.value = lat;
        //         lngInput.value = lng;

        //         // **Mover el marcador en el mapa**
        //         updateMarker();
        //     }


        //     // **Llamar al backend para expandir el link corto**
        //     async function expandShortURL(shortURL) {
        //         console.log(`🔄 Intentando expandir el link corto: ${shortURL}`);

        //         try {
        //             let response = await fetch(
        //                 `http://127.0.0.1:8000/ubicacion/direccion.php?url=${encodeURIComponent(shortURL)}`);
        //             let data = await response.json();

        //             if (data.expanded_url) {
        //                 console.log(`✅ Link expandido: ${data.expanded_url}`);
        //                 extractCoordinates(data.expanded_url);
        //             } else {
        //                 console.warn("⚠️ No se pudo expandir el link corto.");
        //             }
        //         } catch (error) {
        //             console.error("❌ Error al expandir el link corto:", error);
        //         }
        //     }

        //     // **Escuchar cambios en el campo de link**
        //     linkInput.addEventListener("change", function() {
        //         let link = linkInput.value.trim();

        //         if (link !== "") {
        //             // **Si el link es corto, lo expandimos primero**
        //             if (link.includes("maps.app.goo.gl")) {
        //                 expandShortURL(link);
        //             } else {
        //                 extractCoordinates(link);
        //             }
        //         }
        //     });

        //     // **Escuchar cambios en los inputs de latitud y longitud**
        //     latInput.addEventListener("change", updateMarker);
        //     lngInput.addEventListener("change", updateMarker);

        //     console.log("👂 Listos para escuchar cambios en los inputs");

        //     // **Cuando el usuario mueve el marcador, actualizar los inputs con la nueva posición**
        //     marker.on("dragend", function() {
        //         const position = marker.getLatLng();
        //         latInput.value = position.lat.toFixed(6);
        //         lngInput.value = position.lng.toFixed(6);
        //         console.log(`🎯 Marcador movido manualmente: Lat: ${position.lat}, Lng: ${position.lng}`);
        //     });
        // });






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
                errorTicket.textContent = "Campo vacío"; // Mostrar mensaje de campo vacío
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
                            // Mostrar toastr
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
                        // Mostrar toastr
                        showToast('Ocurrió un error al verificar el ticket. Inténtelo de nuevo más tarde.');
                    });
            }
        });

        // Validación en tiempo real para campos obligatorios
        const camposObligatorios = [
            'idCliente', 'idClienteGeneral',
            'direccion', 'fechaCompra', 'idMarca', 'idTienda', 'idModelo', 'serie', 'fallaReportada', 'fechaTicket',
            'linkubicacion'
        ];

        camposObligatorios.forEach(campo => {
            const input = document.getElementById(campo);

            // Agregar evento de "input" o "change" para validación en tiempo real
            input.addEventListener('input', function() {
                validateCampo(input);
            });

            // Para campos de tipo "select" o "date", se usa el evento "change"
            if (input.tagName === 'SELECT' || input.type === 'date') {
                input.addEventListener('change', function() {
                    validateCampo(input);
                    // Validación específica para la fecha de compra
                    if (campo === 'fechaCompra') {
                        validateFechaCompra(input);
                    }
                });
            }

            // También puedes agregar evento "focusout" para perder foco (cuando el usuario termina de escribir)
            input.addEventListener('focusout', function() {
                validateCampo(input);
            });
        });

        // Función para validar el campo y mostrar/ocultar el mensaje "Campo vacío"
        function validateCampo(input) {
            const errorId = `error-${input.id}`;
            let errorText = document.getElementById(errorId);

            if (!input.value.trim()) {
                // Si está vacío
                input.classList.add('border-red-500');
                if (!errorText) {
                    errorText = document.createElement('p');
                    errorText.id = errorId;
                    errorText.classList.add('text-sm', 'text-red-500', 'mt-1');
                    input.parentNode.appendChild(errorText);
                }
                errorText.textContent = "Campo vacío";
            } else {
                // Si tiene contenido
                input.classList.remove('border-red-500');
                if (errorText) {
                    errorText.remove();
                }
            }
        }

        // Función para validar la fecha de compra (no debe ser mayor que la fecha actual)
        function validateFechaCompra(input) {
            const fechaCompra = new Date(input.value);
            const fechaHoy = new Date();

            // Restamos un día de la fecha actual para no permitir fechas futuras
            fechaHoy.setHours(0, 0, 0, 0); // Aseguramos que solo se compare la fecha sin la hora

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
            let errorMessages = []; // Para almacenar los mensajes de error

            camposObligatorios.forEach(campo => {
                const input = document.getElementById(campo);
                const label = document.querySelector(`label[for="${campo}"]`);
                const nombreCampo = label ? label.innerText.trim() : campo;

                if (input.value.trim() === "") {
                    errorFound = true;
                    validateCampo(input); // Ejecuta la validación visual
                    errorMessages.push(
                        `El campo "${nombreCampo}" está vacío. Por favor, complete el campo.`);
                }
            });


            // Validación específica para nroTicket (si está vacío o con error)
            const nroTicketInput = document.getElementById('nroTicket');
            const errorTicket = document.getElementById('errorTicket');
            if (nroTicketInput.value.trim() === "" || nroTicketInput.classList.contains('border-red-500')) {
                errorFound = true;
                errorTicket.classList.remove('hidden');
                errorMessages.push('El número de ticket está vacío o inválido.');
            }

            // Si hay errores, mostrar solo el primer mensaje de error
            if (errorFound) {
                event.preventDefault();
                // Mostrar el primer mensaje de error en el toastr
                showToast(errorMessages[0]);
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Evento para cuando se ingresa un número de serie
            document.getElementById('serie').addEventListener('input', function() {
                let serie = this.value.trim(); // Obtener el valor de la serie
                let select = document.getElementById(
                    'selectTickets'); // El select donde se mostrarán los tickets
                let label = document.querySelector("label[for='selectTickets']"); // Seleccionamos el label

                // Si el campo serie no está vacío
                if (serie.length > 0) {
                    fetch(`/tickets-por-serie/${serie}`)
                        .then(response => response.json())
                        .then(data => {
                            // Limpiar las opciones previas
                            select.innerHTML = '<option value="" selected>Seleccionar Ticket</option>';

                            if (data.length > 0) {
                                // Mostrar el select y el label
                                select.style.display = 'block';
                                label.style.display = 'block';

                                data.forEach(ticket => {
                                    let option = document.createElement('option');
                                    option.value = ticket
                                        .idTickets; // El valor será el id del ticket

                                    // Formatear la fecha de creación
                                    let fecha = new Date(ticket.fecha_creacion)
                                        .toLocaleDateString('es-ES', {
                                            day: '2-digit',
                                            month: '2-digit',
                                            year: 'numeric'
                                        });

                                    option.textContent =
                                        `Ticket N°: ${ticket.numero_ticket} - Fecha: ${fecha}`;
                                    select.appendChild(option);
                                });
                            } else {
                                // Si no hay tickets, ocultar el select y el label
                                select.style.display = 'none';
                                label.style.display = 'none';

                                let option = document.createElement('option');
                                option.value = "";
                                option.textContent = "No hay tickets asociados a esta serie";
                                select.appendChild(option);
                            }
                        })
                        .catch(error => console.error('Error al cargar los tickets:', error));
                } else {
                    // Limpiar el select y ocultarlo junto con el label si no se ingresa una serie
                    select.style.display = 'none';
                    label.style.display = 'none';
                    select.innerHTML = '<option value="" selected>Seleccionar Ticket</option>';
                }
            });

            // Evento para cuando se selecciona un ticket
            document.getElementById('selectTickets').addEventListener('change', function() {
                let ticketId = this.value;
                if (ticketId) {
                    window.open(`/ordenes/smart/${ticketId}/edit`, '_blank');
                }
            });
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

        // Lógica: mostrar u ocultar "evaluacionTienda" según si cliente es tienda
        clienteSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const esTienda = selectedOption.dataset.tienda;
            console.log(`👤 Cliente seleccionado: ${selectedOption.textContent.trim()} | esTienda: ${esTienda}`);

            if (esTienda === '1' || esTienda === 1) {
                evaluacionTiendaDiv.classList.remove('hidden');
                console.log('🟢 Mostrando checkbox de Evaluación a Tienda');
            } else {
                evaluacionTiendaDiv.classList.add('hidden');
                evaluacionTiendaCheckbox.checked = false;
                console.log('🔴 Ocultando checkbox de Evaluación a Tienda y desmarcando');
            }
        });
    </script>

    <script src="{{ asset('assets/js/ubigeo.js') }}"></script>
    <script src="{{ asset('assets/js/tickets/smart/configuraciones.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/tienda/tiendavalidaciones.js') }}"></script> --}}

</x-layout.default>