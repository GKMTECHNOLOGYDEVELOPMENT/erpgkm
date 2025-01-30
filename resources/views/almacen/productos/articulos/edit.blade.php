<x-layout.default>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{ route('articulos.index') }}" class="text-primary hover:underline">Artículos</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Editar Artículo</span>
            </li>
        </ul>
    </div>
    <div class="panel mt-6 p-5 max-w-4xl mx-auto">
        <h2 class="text-xl font-bold mb-5">EDITAR ARTÍCULO</h2>

        <form action="{{ route('articulos.update', $articulo->idArticulos) }}" method="POST"
            enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Código -->
                <div>
                    <label for="codigo_barras" class="block text-sm font-medium">Código</label>
                    <input id="codigo_barras" name="codigo_barras" type="text" class="form-input w-full"
                        value="{{ old('codigo_barras', $articulo->codigo_barras) }}" placeholder="Ingrese el codigo_barras" required>
                </div>

                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-medium">Nombre</label>
                    <input id="nombre" name="nombre" type="text" class="form-input w-full"
                        value="{{ old('nombre', $articulo->nombre) }}" placeholder="Ingrese el nombre" required>
                </div>

                <!-- Nro. sku -->
                <div>
                    <label for="sku" class="block text-sm font-medium"> SKU</label>
                    <input id="sku" name="sku" type="text" class="form-input w-full"
                        value="{{ old('sku', $articulo->sku) }}" placeholder="Ingrese la sku">
                </div>

                <!-- Stock Total -->
                <div>
                    <label for="stock_total" class="block text-sm font-medium">Stock Total</label>
                    <input id="stock_total" name="stock_total" type="number" class="form-input w-full"
                        value="{{ old('stock_total', $articulo->stock_total) }}" placeholder="Ingrese el stock total"
                        required>
                </div>

                <!-- Stock Mínimo -->
                <div>
                    <label for="stock_minimo" class="block text-sm font-medium">Stock Mínimo</label>
                    <input id="stock_minimo" name="stock_minimo" type="number" class="form-input w-full"
                        value="{{ old('stock_minimo', $articulo->stock_minimo) }}"
                        placeholder="Ingrese el stock mínimo">
                </div>

                <!-- Unidad -->
                <div>
                    <label for="idUnidad" class="block text-sm font-medium">Unidad</label>
                    <select id="idUnidad" name="idUnidad" class="select2 w-full" style="display:none">
                        <option value="" disabled>Seleccionar Unidad</option>
                        @foreach ($unidades as $unidad)
                            <option value="{{ $unidad->idUnidad }}"
                                {{ old('idUnidad', $articulo->idUnidad) == $unidad->idUnidad ? 'selected' : '' }}>
                                {{ $unidad->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tipo Artículo -->
                <div>
                    <label for="idTipoArticulo" class="block text-sm font-medium">Tipo de Articulo</label>
                    <select id="idTipoArticulo" name="idTipoArticulo" class="select2 w-full" style="display:none">
                        <option value="" disabled >Seleccionar Tipo de Artículo</option>
                        @foreach ($tiposArticulo as $tipoArticulo)
                            <option value="{{ $tipoArticulo->idTipoArticulo }}"
                                {{ old('idTipoArticulo', $articulo->idTipoArticulo) == $tipoArticulo->idTipoArticulo ? 'selected' : '' }}>
                                {{ $tipoArticulo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- Modelo -->
                <div>
                    <label for="idModelo" class="block text-sm font-medium">Modelo</label>
                    <select id="idModelo" name="idModelo" class="select2 w-full" style="display:none">
                        <option value="" disabled >Seleccionar Modelo</option>
                        @foreach ($modelos as $modelo)
                            <option value="{{ $modelo->idModelo }}"
                                {{ old('idModelo', $articulo->idModelo) == $modelo->idModelo ? 'selected' : '' }}>
                                {{ $modelo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- Moneda de Compra -->
                <div>
                    <label for="moneda_compra" class="block text-sm font-medium">Moneda de Compra</label>
                    <select id="moneda_compra" name="moneda_compra" class="form-input w-full">
                        @foreach ($monedas as $moneda)
                            <option value="{{ $moneda->idMonedas }}"
                                {{ old('moneda_compra', $articulo->moneda_compra) == $moneda->idMonedas ? 'selected' : '' }}>
                                {{ $moneda->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Precio de Compra -->
                <div>
                    <label for="precio_compra" class="block text-sm font-medium">Precio de Compra</label>
                    <div class="flex">
                        <div
                            class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                            <span id="precio_compra_symbol">
                                {{ old('moneda_compra', $articulo->moneda_compra) == 1 ? 'S/' : '$' }}
                            </span>
                        </div>
                        <input id="precio_compra" name="precio_compra" type="number" step="0.01"
                            class="form-input ltr:rounded-l-none rtl:rounded-r-none flex-1"
                            value="{{ old('precio_compra', $articulo->precio_compra) }}"
                            placeholder="Ingrese el precio de compra">
                    </div>
                </div>

                <!-- Moneda de Venta -->
                <div>
                    <label for="moneda_venta" class="block text-sm font-medium">Moneda de Venta</label>
                    <select id="moneda_venta" name="moneda_venta" class="form-input w-full">
                        @foreach ($monedas as $moneda)
                            <option value="{{ $moneda->idMonedas }}"
                                {{ old('moneda_venta', $articulo->moneda_venta) == $moneda->idMonedas ? 'selected' : '' }}>
                                {{ $moneda->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- Precio de Venta -->
                <div>
                    <label for="precio_venta" class="block text-sm font-medium">Precio de Venta</label>
                    <div class="flex">
                        <div
                            class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                            <span id="precio_venta_symbol">
                                {{ old('moneda_venta', $articulo->moneda_venta) == 1 ? 'S/' : '$' }}
                            </span>
                        </div>
                        <input id="precio_venta" name="precio_venta" type="number" step="0.01"
                            class="form-input ltr:rounded-l-none rtl:rounded-r-none flex-1"
                            value="{{ old('precio_venta', $articulo->precio_venta) }}"
                            placeholder="Ingrese el precio de venta">
                    </div>
                </div>

                <!-- Peso -->
                <div>
                    <label for="peso" class="block text-sm font-medium">Peso</label>
                    <input id="peso" name="peso" type="text" class="form-input w-full"
                        value="{{ old('peso', $articulo->peso) }}" placeholder="Ingrese el peso">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Mostrar en Web -->
                    <div>
                        <label for="mostrarWeb" class="block text-sm font-medium">Mostrar en Web</label>
                        <div class="flex items-center">
                            <!-- Campo hidden para enviar valor 0 si el switch no está activado -->
                            <input type="hidden" name="mostrarWeb" value="0">
                            <div class="w-12 h-6 relative">
                                <input type="checkbox" id="mostrarWeb" name="mostrarWeb"
                                    class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                                    value="1" {{ old('mostrarWeb', $articulo->mostrarWeb) ? 'checked' : '' }}>
                                <span for="mostrarWeb"
                                    class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Estado -->
                    <div>
                        <label for="estado" class="block text-sm font-medium">Estado</label>
                        <div class="flex items-center">
                            <!-- Campo hidden para enviar valor 0 si el switch no está activado -->
                            <input type="hidden" name="estado" value="0">
                            <div class="w-12 h-6 relative">
                                <input type="checkbox" id="estado" name="estado"
                                    class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                                    value="1" {{ old('estado', $articulo->estado) ? 'checked' : '' }}>
                                <span for="estado"
                                    class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Foto -->
                <div x-data="{ fotoPreview: '{{ asset($articulo->foto) }}' }">
                    <label for="foto" class="block text-sm font-medium">Foto</label>
                    <input type="file" id="foto" name="foto" accept="image/*"
                        class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold p-0 file:bg-primary/90 file:text-white file:hover:bg-primary w-full"
                        @change="fotoPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : '{{ asset($articulo->foto) }}'">
                    <div
                        class="mt-4 w-full border border-gray-300 rounded-lg overflow-hidden flex justify-center items-center">
                        <template x-if="fotoPreview">
                            <img :src="fotoPreview" alt="Previsualización de la foto"
                                class="w-40 h-40 object-cover object-center">
                        </template>
                        <template x-if="!fotoPreview">
                            <div class="flex items-center justify-center w-40 h-40 text-gray-400 text-sm">
                                Sin imagen
                            </div>
                        </template>
                    </div>
                    @error('foto')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>



            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('articulos.index') }}" class="btn btn-outline-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>

    <!-- Script para Select2 -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.select2').forEach(select => {
                NiceSelect.bind(select, {
                    searchable: true
                });
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            const monedaCompraSelect = document.getElementById("moneda_compra");
            const precioCompraSymbol = document.getElementById("precio_compra_symbol");

            const monedaVentaSelect = document.getElementById("moneda_venta");
            const precioVentaSymbol = document.getElementById("precio_venta_symbol");

            monedaCompraSelect.addEventListener("change", function() {
                precioCompraSymbol.textContent = monedaCompraSelect.value == 1 ? "S/" : "$";
            });

            monedaVentaSelect.addEventListener("change", function() {
                precioVentaSymbol.textContent = monedaVentaSelect.value == 1 ? "S/" : "$";
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
</x-layout.default>
