<x-layout.default>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{ route('articulos.index') }}" class="text-primary hover:underline">Artículos</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Crear Artículo</span>
            </li>
        </ul>
    </div>

    <div class="panel mt-6 p-5 max-w-4x2 mx-auto">
        <h2 class="text-xl font-bold mb-5">Agregar Nuevo Artículo</h2>

        <form method="POST" action="{{ route('articulos.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Código de Barras -->
                <div>
                    <label for="codigo_barras" class="block text-sm font-medium">Código de Barras</label>
                    <input id="codigo_barras" name="codigo_barras" type="text" class="form-input w-full"
                        placeholder="Codigo de Barras" required>
                </div>

                <!-- SKU -->
                <div>
                    <label for="sku" class="block text-sm font-medium">SKU</label>
                    <input id="sku" name="sku" type="text" class="form-input w-full" placeholder="SKU">
                </div>

                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-medium">Nombre</label>
                    <input id="nombre" name="nombre" type="text" class="form-input w-full"
                        placeholder="Ingrese el nombre" required>
                </div>

                <!-- Fecha de Ingreso -->
                <div>
                    <label for="fechaIngreso" class="block text-sm font-medium">Fecha de Ingreso</label>
                    <input id="fechaIngreso" name="fechaIngreso" type="text" class="form-input w-full"
                        placeholder="Seleccionar fecha">
                </div>

                <!-- Stock Total -->
                <div>
                    <label for="stock_total" class="block text-sm font-medium">Stock Total</label>
                    <input id="stock_total" name="stock_total" type="number" class="form-input w-full"
                        placeholder="Ingrese el stock total" required>
                </div>

                <!-- Stock Mínimo -->
                <div>
                    <label for="stock_minimo" class="block text-sm font-medium">Stock Mínimo</label>
                    <input id="stock_minimo" name="stock_minimo" type="number" class="form-input w-full"
                        placeholder="Ingrese el stock mínimo">
                </div>

                <!-- Unidad -->
                <div>
                    <label for="idUnidad" class="block text-sm font-medium">Unidad</label>
                    <select id="idUnidad" name="idUnidad" class="select2 w-full" style="display: none">
                        <option value="" disabled selected>Seleccionar Unidad</option>
                        @foreach ($unidades as $unidad)
                            <option value="{{ $unidad->idUnidad }}">{{ $unidad->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tipo de Artículo -->
                <div>
                    <label for="idTipoArticulo" class="block text-sm font-medium">Tipo Artículo</label>
                    <select id="idTipoArticulo" name="idTipoArticulo" class="select2 w-full" style="display: none">
                        <option value="" disabled selected>Seleccionar Tipo Artículo</option>
                        @foreach ($tiposArticulo as $tipoArticulo)
                            <option value="{{ $tipoArticulo->idTipoArticulo }}">{{ $tipoArticulo->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Modelo -->
                <div>
                    <label for="idModelo" class="block text-sm font-medium">Modelo</label>
                    <select id="idModelo" name="idModelo" class="select2 w-full" style="display: none">
                        <option value="" disabled selected>Seleccionar Modelo</option>
                        @foreach ($modelos as $modelo)
                            <option value="{{ $modelo->idModelo }}">{{ $modelo->nombre }} - {{ $modelo->marca->nombre }}
                                - {{ $modelo->categoria->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Peso -->
                <div>
                    <label for="peso" class="block text-sm font-medium">Peso</label>
                    <input id="peso" name="peso" type="text" class="form-input w-full"
                        placeholder="Ingrese el peso">
                </div>

                <!-- Precio Compra con Selector de Moneda -->
                <div>
                    <label for="precio_compra" class="block text-sm font-medium">Precio de Compra</label>
                    <div class="flex">
                        <button type="button" id="toggleMonedaCompra"
                            class="bg-[#eee] px-3 font-semibold border border-[#e0e6ed]">
                            <span id="precio_compra_symbol">S/</span>
                        </button>
                        <input id="precio_compra" name="precio_compra" type="number" class="form-input flex-1">
                        <input type="hidden" id="moneda_compra" name="moneda_compra" value="soles">
                    </div>
                </div>

                <!-- Precio Venta con Selector de Moneda -->
                <div>
                    <label for="precio_venta" class="block text-sm font-medium">Precio de Venta</label>
                    <div class="flex">
                        <button type="button" id="toggleMonedaVenta"
                            class="bg-[#eee] px-3 font-semibold border border-[#e0e6ed]">
                            <span id="precio_venta_symbol">S/</span>
                        </button>
                        <input id="precio_venta" name="precio_venta" type="number" class="form-input flex-1">
                        <input type="hidden" id="moneda_venta" name="moneda_venta" value="soles">
                    </div>
                </div>



                <!-- Foto -->
                <div class="mb-5" x-data="{ fotoPreview: null }">
                    <label for="foto" class="block text-sm font-medium mb-2">Foto</label>
                    <input id="foto" name="foto" type="file" accept="image/*"
                        class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold p-0 file:bg-primary/90 ltr:file:mr-5 rtl:file-ml-5 file:text-white file:hover:bg-primary w-full"
                        @change="fotoPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null" />
                    <div
                        class="mt-4 w-full border border-gray-300 rounded-lg overflow-hidden flex justify-center items-center">
                        <template x-if="fotoPreview">
                            <img :src="fotoPreview" alt="Previsualización de la foto"
                                class="w-40 h-40 object-cover">
                        </template>
                        <template x-if="!fotoPreview">
                            <img src="/assets/images/file-preview.svg" alt="Imagen predeterminada"
                                class="w-50 h-40 object-cover">
                        </template>
                    </div>
                </div>
                <!-- Mostrar en Web -->
                <div class="mb-5">
                    <label for="mostrarWeb" class="block text-sm font-medium mb-2">Mostrar en Web</label>
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
                    <label for="ocultarprecios" class="block text-sm font-medium mb-2">Ocultar Precios</label>
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

            <div class="flex justify-end mt-4">
                <a href="{{ route('articulos.index') }}" class="btn btn-outline-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary ml-4">Guardar</button>
            </div>
        </form>
    </div>

    <!-- 🟢 SCRIPTS INCLUIDOS -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const fechaIngresoInput = document.getElementById("fechaIngreso");
            const today = new Date().toISOString().split('T')[0];
            fechaIngresoInput.value = today;
        });
    </script>

    <script>
        // Alternar moneda de compra
        document.getElementById("toggleMonedaCompra").addEventListener("click", function() {
            let symbol = document.getElementById("precio_compra_symbol");
            let monedaInput = document.getElementById("moneda_compra");

            if (symbol.textContent === "S/") {
                symbol.textContent = "$";
                monedaInput.value = "dolares";
            } else {
                symbol.textContent = "S/";
                monedaInput.value = "soles";
            }
        });

        // Alternar moneda de venta
        document.getElementById("toggleMonedaVenta").addEventListener("click", function() {
            let symbol = document.getElementById("precio_venta_symbol");
            let monedaInput = document.getElementById("moneda_venta");

            if (symbol.textContent === "S/") {
                symbol.textContent = "$";
                monedaInput.value = "dolares";
            } else {
                symbol.textContent = "S/";
                monedaInput.value = "soles";
            }
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.select2').forEach(function(select) {
                NiceSelect.bind(select, {
                    searchable: true
                });
            });
        });

        document.addEventListener("alpine:init", () => {
            Alpine.data("form", () => ({
                date1: '',
                init() {
                    this.date1 = new Date().toISOString().split('T')[0];
                    flatpickr(document.getElementById('fechaIngreso'), {
                        dateFormat: 'Y-m-d',
                        defaultDate: this.date1,
                        onChange: (selectedDates, dateStr) => {
                            this.date1 = dateStr;
                        }
                    });
                }
            }));
        });
    </script>
    <script src="{{ asset('assets/js/articulos/articulosStore.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</x-layout.default>
