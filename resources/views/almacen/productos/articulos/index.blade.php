<x-layout.default>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .dataTables_length select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            padding-right: 1.5rem;
            /* Ajusta espacio a la derecha para que el texto no se corte */
            background-image: none;
            /* Opcional, elimina cualquier ícono */
        }
    </style>
    <div x-data="multipleTable">
        <div>
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="javascript:;" class="text-primary hover:underline">Almacen</a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>Articulos</span>
                </li>
            </ul>
        </div>
        <div class="panel mt-6">
            <div class="md:absolute md:top-5 ltr:md:left-5 rtl:md:right-5">
                <div class="flex flex-wrap items-center justify-center gap-2 mb-5 sm:justify-start md:flex-nowrap">
                    <!-- Botón Exportar a Excel -->
                    <button type="button" class="btn btn-success btn-sm flex items-center gap-2"
                        onclick="window.location.href='{{ route('articulos.exportExcel') }}'">
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
                        onclick="window.location.href='{{ route('articulos.export.pdf') }}'">
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
                    <a href="{{ route('articulos.create') }}" class="btn btn-primary btn-sm flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                            fill="none">
                            <path
                                d="M4 4H20C20.5523 4 21 4.44772 21 5V19C21 19.5523 20.5523 20 20 20H4C3.44772 20 3 19.5523 3 19V5C3 4.44772 3 4 4 4Z"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M7 9H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M7 13H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M7 17H13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        <span>Agregar</span>
                    </a>

                </div>
            </div>

            <table id="myTable1" class="w-full min-w-[1000px] table whitespace-nowrap">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Código de Barras</th>
                        <th>SKU</th>
                        <th>Nombre</th>
                        <th>Unidad</th>
                        <th>Stock Total</th>
                        <th>Tipo Artículo</th>
                        <th>Modelo</th>
                        <th>Estados</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>



    <!-- Modal -->
    <div x-data="{ open: false, ocultarPrecios: false }" class="mb-5" @toggle-modal.window="open = !open">
        <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
                <div x-show="open" x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-3xl my-8 animate__animated animate__zoomInUp">
                    <!-- Header del Modal -->
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <h5 class="font-bold text-lg">Agregar Artículo</h5>
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
                        <form class="p-5 space-y-4" id="articuloForm" enctype="multipart/form-data" method="post">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- CODIGO DE BARRAS -->
                                <div>
                                    <label for="codigo_barras" class="block text-sm font-medium">Codigo de
                                        barras</label>
                                    <input id="codigo_barras" name="codigo_barras" type="text"
                                        class="form-input w-full" placeholder="CODIGO DE BARRAS" required>
                                </div>

                                <!-- SKU -->
                                <div>
                                    <label for="sku" class="block text-sm font-medium">SKU</label>
                                    <input id="sku" name="sku" type="text" class="form-input w-full"
                                        placeholder="SKU">
                                </div>

                                <!-- Nombre -->
                                <div>
                                    <label for="nombre" class="block text-sm font-medium">Nombre</label>
                                    <input id="nombre" name="nombre" type="text" class="form-input w-full"
                                        placeholder="Ingrese el nombre" required>
                                </div>

                                <!-- Fecha de Ingreso -->
                                <div>
                                    <label for="fechaIngreso" class="block text-sm font-medium">Fecha de
                                        Ingreso</label>
                                    <input id="fechaIngreso" name="fechaIngreso" type="text"
                                        class="form-input w-full" placeholder="Seleccionar fecha">
                                </div>

                                <script>
                                    document.addEventListener("DOMContentLoaded", function() {
                                        flatpickr("#fechaIngreso", {
                                            dateFormat: "Y-m-d",
                                            defaultDate: new Date(),
                                            altInput: true,
                                            altFormat: "F j, Y",
                                            locale: "es",
                                        });
                                    });
                                </script>

                                <!-- Stock Total -->
                                <div>
                                    <label for="stock_total" class="block text-sm font-medium">Stock Total</label>
                                    <input id="stock_total" name="stock_total" type="number"
                                        class="form-input w-full" placeholder="Ingrese el stock total" required>
                                </div>

                                <!-- Stock Mínimo -->
                                <div>
                                    <label for="stock_minimo" class="block text-sm font-medium">Stock Mínimo</label>
                                    <input id="stock_minimo" name="stock_minimo" type="number"
                                        class="form-input w-full" placeholder="Ingrese el stock mínimo">
                                </div>

                                <!-- ID Unidad -->
                                <div>
                                    <label for="idUnidad" class="block text-sm font-medium">Unidad</label>
                                    <select id="idUnidad" name="idUnidad" class="select2 w-full"
                                        style="display:none">
                                        <option value="" disabled selected>Seleccionar Unidad</option>
                                        @foreach ($unidades as $unidad)
                                            <option value="{{ $unidad->idUnidad }}">{{ $unidad->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- ID Tipo Artículo -->
                                <div>
                                    <label for="idTipoArticulo" class="block text-sm font-medium">Tipo
                                        Artículo</label>
                                    <select id="idTipoArticulo" name="idTipoArticulo" class="select2 w-full"
                                        style="display:none">
                                        <option value="" disabled selected>Seleccionar Tipo Artículo</option>
                                        @foreach ($tiposArticulo as $tipoArticulo)
                                            <option value="{{ $tipoArticulo->idTipoArticulo }}">
                                                {{ $tipoArticulo->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- ID Modelo -->
                                <div>
                                    <label for="idModelo" class="block text-sm font-medium">Modelo</label>
                                    <select id="idModelo" name="idModelo" class="select2 w-full"
                                        style="display:none">
                                        <option value="" disabled selected>Seleccionar Modelo</option>
                                        @foreach ($modelos as $modelo)
                                            <option value="{{ $modelo->idModelo }}">
                                                {{ $modelo->nombre }} -
                                                {{ $modelo->marca?->nombre ?? 'Sin Marca' }} -
                                                {{ $modelo->categoria?->nombre ?? 'Sin Categoría' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Peso -->
                                <div>
                                    <label for="peso" class="block text-sm font-medium">Peso</label>
                                    <input id="peso" name="peso" type="text" class="form-input w-full"
                                        placeholder="Ingrese el peso">
                                </div>

                                <!-- Moneda Compra (Oculto si el checkbox está activado) -->
                                <div x-show="!ocultarPrecios" class="mb-5">
                                    <label for="moneda_compra" class="block text-sm font-medium">Moneda de
                                        Compra</label>
                                    <select id="moneda_compra" name="moneda_compra" class="form-input w-full">
                                        @foreach ($monedas as $moneda)
                                            <option value="{{ $moneda->idMonedas }}">{{ $moneda->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Precio Compra (Oculto si el checkbox está activado) -->
                                <div x-show="!ocultarPrecios" class="mb-5">
                                    <label for="precio_compra" class="block text-sm font-medium">Precio de
                                        Compra</label>
                                    <div class="flex">
                                        <div
                                            class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                                            <span id="precio_compra_symbol">S/</span>
                                        </div>
                                        <input id="precio_compra" name="precio_compra" type="number"
                                            class="form-input ltr:rounded-l-none rtl:rounded-r-none flex-1"
                                            placeholder="Ingrese el precio de compra" />
                                    </div>
                                </div>

                                <!-- Moneda Venta (Oculto si el checkbox está activado) -->
                                <div x-show="!ocultarPrecios" class="mb-5">
                                    <label for="moneda_venta" class="block text-sm font-medium">Moneda de
                                        Venta</label>
                                    <select id="moneda_venta" name="moneda_venta" class="form-input w-full">
                                        @foreach ($monedas as $moneda)
                                            <option value="{{ $moneda->idMonedas }}">{{ $moneda->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Precio Venta (Oculto si el checkbox está activado) -->
                                <div x-show="!ocultarPrecios" class="mb-5">
                                    <label for="precio_venta" class="block text-sm font-medium">Precio de
                                        Venta</label>
                                    <div class="flex">
                                        <div
                                            class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                                            <span id="precio_venta_symbol">S/</span>
                                        </div>
                                        <input id="precio_venta" name="precio_venta" type="number"
                                            class="form-input ltr:rounded-l-none rtl:rounded-r-none flex-1"
                                            placeholder="Ingrese el precio de venta" />
                                    </div>
                                </div>

                                <!-- Foto -->
                                <div class="mb-5" x-data="{ fotoPreview: null }">
                                    <label for="foto" class="block text-sm font-medium mb-2">Foto</label>

                                    <input id="foto" name="foto" type="file" accept="image/*"
                                        class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold p-0 file:bg-primary/90 ltr:file:mr-5 rtl:file-ml-5 file:text-white file:hover:bg-primary w-full"
                                        @change="fotoPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null" />

                                    <!-- Previsualización centrada -->
                                    <div class="flex justify-center mt-4">
                                        <div
                                            class="w-full max-w-xs h-40 border border-gray-300 rounded-lg overflow-hidden flex justify-center items-center bg-white">
                                            <template x-if="fotoPreview">
                                                <img :src="fotoPreview" alt="Previsualización de la imagen"
                                                    class="w-full h-full object-contain" />
                                            </template>
                                            <template x-if="!fotoPreview">
                                                <img src="/assets/images/file-preview.svg" alt="Imagen predeterminada"
                                                    class="w-full h-full object-contain" />
                                            </template>
                                        </div>
                                    </div>
                                </div>


                                <!-- Mostrar en Web -->
                                <div class="mb-5">
                                    <label for="mostrarWeb" class="block text-sm font-medium mb-2">Mostrar en
                                        Web</label>
                                    <div>
                                        <label class="w-12 h-6 relative mt-3">
                                            <input type="checkbox" id="mostrarWeb" name="mostrarWeb"
                                                class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer" />
                                            <span
                                                class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Ocultar Precios -->
                                <div class="mb-5">
                                    <label for="ocultarprecios" class="block text-sm font-medium mb-2">Ocultar
                                        Precios</label>
                                    <div>
                                        <label class="w-12 h-6 relative mt-3">
                                            <input type="checkbox" id="ocultarprecios" name="ocultarprecios"
                                                class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                                                x-model="ocultarPrecios" />
                                            <span
                                                class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                                        </label>
                                    </div>
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


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Seleccionar el input de fecha
            const fechaIngresoInput = document.getElementById("fechaIngreso");

            // Obtener la fecha actual en formato YYYY-MM-DD
            const today = new Date().toISOString().split('T')[0];

            // Establecer la fecha actual como valor predeterminado
            fechaIngresoInput.value = today;
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const monedaCompraSelect = document.getElementById("moneda_compra");
            const precioCompraSymbol = document.getElementById("precio_compra_symbol");

            const monedaVentaSelect = document.getElementById("moneda_venta");
            const precioVentaSymbol = document.getElementById("precio_venta_symbol");

            // Cambiar el símbolo para el precio de compra
            monedaCompraSelect.addEventListener("change", function() {
                precioCompraSymbol.textContent = monedaCompraSelect.value == 1 ? "S/" : "$";
            });

            // Cambiar el símbolo para el precio de venta
            monedaVentaSelect.addEventListener("change", function() {
                precioVentaSymbol.textContent = monedaVentaSelect.value == 1 ? "S/" : "$";
            });
        });
    </script>
    <script>
        // Inicializar Select2
        document.addEventListener("DOMContentLoaded", function() {
            // Inicializar todos los select con la clase "select2"
            document.querySelectorAll('.select2').forEach(function(select) {
                NiceSelect.bind(select, {
                    searchable: true
                });
            });
        });
        document.addEventListener("alpine:init", () => {
            Alpine.data("form", () => ({
                date1: '', // Variable para almacenar la fecha seleccionada
                init() {
                    // Establece la fecha actual como valor inicial
                    this.date1 = new Date().toISOString().split('T')[0]; // Formato 'YYYY-MM-DD'

                    // Inicializa el flatpickr con la fecha de hoy por defecto
                    flatpickr(document.getElementById('fechaIngreso'), {
                        dateFormat: 'Y-m-d',
                        defaultDate: this.date1, // Asigna la fecha por defecto
                        onChange: (selectedDates, dateStr) => {
                            this.date1 = dateStr; // Sincroniza el valor con Alpine.js
                        }
                    });
                }
            }));
        });

        document.addEventListener('alpine:init', () => {
            Alpine.store('formData', {
                moneda_compra: 'sol', // Valor inicial para Moneda Compra
                moneda_venta: 'sol', // Valor inicial para Moneda Venta
                precio_compra: '', // Valor inicial para Precio Compra
                precio_venta: '', // Valor inicial para Precio Venta
            });
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
    <script src="{{ asset('assets/js/articulos/articulos.js') }}"></script>
    <!-- <script src="{{ asset('assets/js/articulos/articulosValidaciones.js') }}"></script> -->
    <script src="/assets/js/simple-datatables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>

</x-layout.default>
