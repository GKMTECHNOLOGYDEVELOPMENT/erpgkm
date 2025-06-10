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
                    <a href="{{ route('producto.create') }}" class="btn btn-primary btn-sm flex items-center gap-2">
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
                        <th>Marca</th>
                        <th>Categoria</th>
                        <th>Modelo</th>
                        <th>Stock Total</th>
                        <!-- <th>Entradas</th>
                        <th>Salidas</th> -->
                        <th>Estados</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
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
    <script src="{{ asset('assets/js/almacen/productos/productos.js') }}"></script>
    <!-- <script src="{{ asset('assets/js/articulos/articulosValidaciones.js') }}"></script> -->
    <script src="/assets/js/simple-datatables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>

</x-layout.default>
