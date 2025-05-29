<x-layout.default>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{ route('repuestos.index') }}" class="text-primary hover:underline">Repuestos</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Crear Repuesto nuevo</span>
            </li>
        </ul>
    </div>

    <div class="panel mt-6 p-5 max-w-4xl mx-auto">
        <h2 class="text-xl font-bold mb-5">Agregar Nuevo Repuesto</h2>

        <form id="articuloForm" method="POST" action="{{ route('repuestos.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Código de Barras -->
                <div>
                    <label for="codigo_barras" class="block text-sm font-medium">Código de Barras *</label>
                    <input id="codigo_barras" name="codigo_barras" type="text" class="form-input w-full"
                        placeholder="Ingrese código de barras" >
                </div>

                <!-- SKU -->
                <div>
                    <label for="sku" class="block text-sm font-medium">SKU</label>
                    <input id="sku" name="sku" type="text" class="form-input w-full" placeholder="Ingrese SKU">
                </div>

                <!-- Código Repuesto -->
                <div>
                    <label for="codigo_repuesto" class="block text-sm font-medium">Código Repuesto</label>
                    <input id="codigo_repuesto" name="codigo_repuesto" type="text" class="form-input w-full"
                        placeholder="Ingrese código de repuesto">
                </div>

                <!-- Modelo -->
                <div>
                    <label for="idModelo" class="block text-sm font-medium">Modelo *</label>
                    <select id="idModelo" name="idModelo" class="select2 w-full" style="display: none" >
                        <option value="" disabled selected>Seleccionar Modelo</option>
                        @foreach ($modelos as $modelo)
                            <option value="{{ $modelo->idModelo }}" 
                                {{ (isset($modeloSeleccionado) && $modeloSeleccionado == $modelo->idModelo) ? 'selected' : '' }}>
                                {{ $modelo->nombre }} - 
                                {{ $modelo->marca->nombre ?? 'Sin Marca' }} -
                                {{ $modelo->categoria->nombre ?? 'Sin Marca' }}

                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Precio Compra -->
                <div>
                    <label for="precio_compra" class="block text-sm font-medium">Precio de Compra *</label>
                    <div class="flex">
                        <button type="button" id="toggleMonedaCompra"
                            class="bg-[#eee] px-3 font-semibold border border-[#e0e6ed]">
                            <span id="precio_compra_symbol">S/</span>
                        </button>
                        <input id="precio_compra" name="precio_compra" type="number" step="0.01" 
                            class="form-input flex-1" placeholder="0.00" >
                        <input type="hidden" id="moneda_compra" name="moneda_compra" value="0">
                    </div>
                </div>

                <!-- Precio Venta -->
                <div>
                    <label for="precio_venta" class="block text-sm font-medium">Precio de Venta *</label>
                    <div class="flex">
                        <button type="button" id="toggleMonedaVenta"
                            class="bg-[#eee] px-3 font-semibold border border-[#e0e6ed]">
                            <span id="precio_venta_symbol">S/</span>
                        </button>
                        <input id="precio_venta" name="precio_venta" type="number" step="0.01" 
                            class="form-input flex-1" placeholder="0.00" >
                        <input type="hidden" id="moneda_venta" name="moneda_venta" value="0">
                    </div>
                </div>

                <!-- Stock Total -->
                <div>
                    <label for="stock_total" class="block text-sm font-medium">Stock Total *</label>
                    <input id="stock_total" name="stock_total" type="number" class="form-input w-full"
                        placeholder="Ingrese stock total" >
                </div>

                <!-- Stock Mínimo -->
                <div>
                    <label for="stock_minimo" class="block text-sm font-medium">Stock Mínimo</label>
                    <input id="stock_minimo" name="stock_minimo" type="number" class="form-input w-full"
                        placeholder="Ingrese stock mínimo">
                </div>

                <!-- Unidad de Medida -->
                <div>
                    <label for="idUnidad" class="block text-sm font-medium">Unidad de Medida *</label>
                    <select id="idUnidad" name="idUnidad" class="select2 w-full" style="display: none" >
                        <option value="" disabled selected>Seleccionar Unidad</option>
                        @foreach ($unidades as $unidad)
                            <option value="{{ $unidad->idUnidad }}">{{ $unidad->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Pulgadas -->
                <div>
                    <label for="pulgadas" class="block text-sm font-medium">Pulgadas</label>
                    <input id="pulgadas" name="pulgadas" type="text" class="form-input w-full"
                        placeholder="Ej: 14'', 15'', etc.">
                </div>

                <!-- Foto -->
                <div class="mb-5" x-data="{ fotoPreview: '' }">
                    <label for="foto" class="block text-sm font-medium mb-2">Foto</label>
                    <input id="foto" name="foto" type="file" accept="image/*"
                        class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold p-0 file:bg-primary/90 ltr:file:mr-5 rtl:file-ml-5 file:text-white file:hover:bg-primary w-full"
                        @change="fotoPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : ''">
                    
                    <!-- Previsualización de imagen -->
                    <div class="flex justify-center mt-4">
                        <div class="w-full max-w-xs h-40 border border-gray-300 rounded-lg overflow-hidden flex justify-center items-center bg-white">
                            <template x-if="fotoPreview">
                                <img :src="fotoPreview" alt="Previsualización de la imagen"
                                    class="w-full h-full object-contain">
                            </template>
                            <template x-if="!fotoPreview">
                                <div class="flex items-center justify-center w-full h-full text-gray-400 text-sm">
                                    Sin imagen seleccionada
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Ficha Técnica (PDF) -->
                <div class="mb-5">
                    <label for="ficha_tecnica" class="block text-sm font-medium">Ficha Técnica (PDF)</label>
                    <input id="ficha_tecnica" name="ficha_tecnica" type="file" accept=".pdf"
                        class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold p-0 file:bg-primary/90 ltr:file:mr-5 rtl:file-ml-5 file:text-white file:hover:bg-primary w-full">
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <a href="{{ route('articulos.index') }}" class="btn btn-outline-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary ml-4">Guardar Repuesto</button>
            </div>
        </form>
    </div>

   <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Inicializar select2
        document.querySelectorAll('.select2').forEach(function (select) {
            NiceSelect.bind(select, {
                searchable: true
            });
        });

        // MONEDAS
        const monedas = @json($monedas);
        let monedaCompraIndex = 0;
        let monedaVentaIndex = 0;
        const btnCompra = document.getElementById("toggleMonedaCompra");
        const btnVenta = document.getElementById("toggleMonedaVenta");
        const symbolCompra = document.getElementById("precio_compra_symbol");
        const symbolVenta = document.getElementById("precio_venta_symbol");
        const monedaInputCompra = document.getElementById("moneda_compra");
        const monedaInputVenta = document.getElementById("moneda_venta");

        if (monedas.length > 0) {
            symbolCompra.textContent = monedas[monedaCompraIndex].nombre;
            monedaInputCompra.value = monedas[monedaCompraIndex].idMonedas;
            symbolVenta.textContent = monedas[monedaVentaIndex].nombre;
            monedaInputVenta.value = monedas[monedaVentaIndex].idMonedas;

            btnCompra.addEventListener("click", function () {
                monedaCompraIndex = (monedaCompraIndex + 1) % monedas.length;
                symbolCompra.textContent = monedas[monedaCompraIndex].nombre;
                monedaInputCompra.value = monedas[monedaCompraIndex].idMonedas;
            });

            btnVenta.addEventListener("click", function () {
                monedaVentaIndex = (monedaVentaIndex + 1) % monedas.length;
                symbolVenta.textContent = monedas[monedaVentaIndex].nombre;
                monedaInputVenta.value = monedas[monedaVentaIndex].idMonedas;
            });
        } else {
            btnCompra.disabled = true;
            btnVenta.disabled = true;
            symbolCompra.textContent = '';
            symbolVenta.textContent = '';
        }
       
    });
</script>



    
    <script src="{{ asset('assets/js/almacen/repuesto/repuestoValidaciones.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</x-layout.default>