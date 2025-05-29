<x-layout.default>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{ route('repuestos.index') }}" class="text-primary hover:underline">Repuestos</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Editar Artículo</span>
            </li>
        </ul>
    </div>
    <div class="panel mt-6 p-5 max-w-4x2 mx-auto">
        <h2 class="text-xl font-bold mb-5">EDITAR REPUESTO</h2>

        <form id="articuloForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') <!-- Usamos el método PUT para actualizar -->

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            
            
           <!-- Código de Barras -->
            <div>
                <label for="codigo_barras" class="block text-sm font-medium">Código de Barras *</label>
                <input id="codigo_barras" name="codigo_barras" value="{{ old('codigo_barras', $articulo->codigo_barras) }}" type="text" class="form-input w-full" placeholder="Ingrese código de barras">
            </div>
            <!-- Imagen de Código de Barras -->
            <div class="mb-5">
                <label class="block text-sm font-medium mb-2">Imagen Código de Barras</label>
                @if ($articulo->foto_codigobarras)
                    <div class="flex justify-center mt-4">
                        <div class="w-full max-w-xs h-40 border border-gray-300 rounded-lg overflow-hidden flex justify-center items-center bg-white">
                            <img src="data:image/jpeg;base64,{{ base64_encode($articulo->foto_codigobarras) }}" alt="Imagen Código de Barras" class="w-full h-full object-contain">
                        </div>
                    </div>
                @else
                    <div class="flex items-center justify-center w-full h-40 text-gray-400 text-sm">
                        Sin imagen disponible
                    </div>
                @endif
            </div>
            <!-- SKU -->
            <div>
                <label for="sku" class="block text-sm font-medium">SKU</label>
                <input id="sku" name="sku" type="text" class="form-input w-full" placeholder="Ingrese SKU" value="{{ old('sku', $articulo->sku) }}">
            </div>
            <!-- Imagen SKU -->
            <div class="mb-5">
                <label class="block text-sm font-medium mb-2">Imagen SKU</label>
                @if ($articulo->fotosku)
                    <div class="flex justify-center mt-4">
                        <div class="w-full max-w-xs h-40 border border-gray-300 rounded-lg overflow-hidden flex justify-center items-center bg-white">
                            <img src="data:image/jpeg;base64,{{ base64_encode($articulo->fotosku) }}" alt="Imagen SKU" class="w-full h-full object-contain">
                        </div>
                    </div>
                @else
                    <div class="flex items-center justify-center w-full h-40 text-gray-400 text-sm">
                        Sin imagen disponible
                    </div>
                @endif
            </div>
                <!-- Código Repuesto -->
                <div>
                    <label for="codigo_repuesto" class="block text-sm font-medium">Código Repuesto</label>
                    <input id="codigo_repuesto" name="codigo_repuesto" type="text" class="form-input w-full" placeholder="Ingrese código de repuesto" value="{{ old('codigo_repuesto', $articulo->codigo_repuesto) }}">
                </div>
                <!-- Modelo -->
                <div>
                    <label for="idModelo" class="block text-sm font-medium">Modelo *</label>
                    <select id="idModelo" name="idModelo" class="select2 w-full" style="display: none;">
                        <option value="" disabled>Seleccionar Modelo</option>
                        @foreach ($modelos as $modelo)
                            <option value="{{ $modelo->idModelo }}" {{ old('idModelo', $articulo->idModelo) == $modelo->idModelo ? 'selected' : '' }}>
                                {{ $modelo->nombre }} - {{ $modelo->marca->nombre ?? 'Sin Marca' }} - {{ $modelo->categoria->nombre ?? 'Sin Categoría' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- Precio Compra -->
                <div>
                    <label for="precio_compra" class="block text-sm font-medium">Precio de Compra *</label>
                    <div class="flex">
                        <button type="button" id="toggleMonedaCompra" class="bg-[#eee] px-3 font-semibold border border-[#e0e6ed]">
                            <span id="precio_compra_symbol">S/</span>
                        </button>
                        <input id="precio_compra" name="precio_compra" type="number" step="0.01" class="form-input flex-1" value="{{ old('precio_compra', $articulo->precio_compra) }}" placeholder="0.00">
                        <input type="hidden" id="moneda_compra" name="moneda_compra" value="{{ old('moneda_compra', $articulo->moneda_compra) }}">
                    </div>
                </div>
                <!-- Precio Venta -->
                <div>
                    <label for="precio_venta" class="block text-sm font-medium">Precio de Venta *</label>
                    <div class="flex">
                        <button type="button" id="toggleMonedaVenta" class="bg-[#eee] px-3 font-semibold border border-[#e0e6ed]">
                            <span id="precio_venta_symbol">S/</span>
                        </button>
                        <input id="precio_venta" name="precio_venta" type="number" step="0.01" class="form-input flex-1" value="{{ old('precio_venta', $articulo->precio_venta) }}" placeholder="0.00">
                        <input type="hidden" id="moneda_venta" name="moneda_venta" value="{{ old('moneda_venta', $articulo->moneda_venta) }}">
                    </div>
                </div>

                <!-- Stock Total -->
                <div>
                    <label for="stock_total" class="block text-sm font-medium">Stock Total *</label>
                    <input id="stock_total" name="stock_total" type="number" class="form-input w-full" value="{{ old('stock_total', $articulo->stock_total) }}" placeholder="Ingrese stock total">
                </div>

                <!-- Stock Mínimo -->
                <div>
                    <label for="stock_minimo" class="block text-sm font-medium">Stock Mínimo</label>
                    <input id="stock_minimo" name="stock_minimo" type="number" class="form-input w-full" value="{{ old('stock_minimo', $articulo->stock_minimo) }}" placeholder="Ingrese stock mínimo">
                </div>

                <!-- Unidad de Medida -->
                <div>
                    <label for="idUnidad" class="block text-sm font-medium">Unidad de Medida *</label>
                    <select id="idUnidad" name="idUnidad" class="select2 w-full" style="display: none;">
                        <option value="" disabled>Seleccionar Unidad</option>
                        @foreach ($unidades as $unidad)
                            <option value="{{ $unidad->idUnidad }}" {{ old('idUnidad', $articulo->idUnidad) == $unidad->idUnidad ? 'selected' : '' }}>
                                {{ $unidad->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Pulgadas -->
                <div>
                    <label for="pulgadas" class="block text-sm font-medium">Pulgadas</label>
                    <input id="pulgadas" name="pulgadas" type="text" class="form-input w-full" value="{{ old('pulgadas', $articulo->pulgadas) }}" placeholder="Ej: 14'', 15'', etc.">
                </div>

             

                <!-- Ficha Técnica (PDF)
                <div class="mb-5">
                    <label for="ficha_tecnica" class="block text-sm font-medium">Ficha Técnica (PDF)</label>
                    <input id="ficha_tecnica" name="ficha_tecnica" type="file" accept=".pdf" class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold p-0 file:bg-primary/90 ltr:file:mr-5 rtl:file-ml-5 file:text-white file:hover:bg-primary w-full">
                </div> -->
            </div>

            <div class="flex justify-end mt-6">
                <a href="{{ route('repuestos.index') }}" class="btn btn-outline-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary ml-4">Guardar Repuesto</button>
            </div>
        </form>
    </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
    $(document).ready(function () {
        // Obtener el ID del artículo desde la vista
        const articuloId = {{ $articulo->idArticulos }};
        console.log("ID del artículo:", articuloId);

        // Manejo del envío del formulario con AJAX
        $('#articuloForm').on('submit', function (e) {
            e.preventDefault();  // Evitar envío tradicional

            var formData = new FormData(this);  // Crear objeto FormData
            console.log("FormData a enviar:");
            for (let [key, value] of formData.entries()) {
                console.log(key + ':', value);
            }

            $.ajax({
                url: '/repuestos/update/' + articuloId,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    console.log("Respuesta del servidor:", response);

                    if (response.success) {
                        alert('Artículo actualizado exitosamente');
                        // Actualizar imágenes si es necesario
                    } else {
                        alert('Hubo un error al actualizar el artículo');
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error AJAX:", error);
                    console.error("Estado:", status);
                    console.error("XHR:", xhr);
                    alert('Ocurrió un error: ' + error);
                }
            });
        });
    });
</script>

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

    <script src="{{ asset('assets/js/almacen/repuesto/repuestoValidacionesedit.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
</x-layout.default>
