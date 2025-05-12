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
    <div class="panel mt-6 p-5 max-w-4x2 mx-auto">
        <h2 class="text-xl font-bold mb-5">EDITAR ARTÍCULO</h2>

        <form id="edit-articulo-form" method="POST" action="{{ route('articulos.update', $articulo->idArticulos) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
             
            <!-- Código de Barras -->
        <div>
            <label for="codigo_barras" class="block text-sm font-medium">Código</label>
            <input id="codigo_barras" name="codigo_barras" type="text" class="form-input w-full"
                value="{{ old('codigo_barras', $articulo->codigo_barras) }}" placeholder="Ingrese el código" required>
        </div>

        <!-- Foto de Código de Barras -->
        <div>
            <label for="foto_codigobarras" class="block text-sm font-medium">Foto Código de Barras</label>
            @if ($fotoCodigobarras)
                <img src="data:image/jpeg;base64,{{ $fotoCodigobarras }}" alt="Foto Código de Barras" class="w-32 h-32 object-cover mt-2">
            @else
                <p>No hay foto disponible.</p>
            @endif
        </div>

        <!-- Nro. SKU -->
        <div>
            <label for="sku" class="block text-sm font-medium">SKU</label>
            <input id="sku" name="sku" type="text" class="form-input w-full"
                value="{{ old('sku', $articulo->sku) }}" placeholder="Ingrese el SKU">
        </div>

        <!-- Foto de SKU -->
        <div>
            <label for="foto_sku" class="block text-sm font-medium">Foto SKU</label>
            @if ($fotoSku)
                <img src="data:image/jpeg;base64,{{ $fotoSku }}" alt="Foto SKU" class="w-32 h-32 object-cover mt-2">
            @else
                <p>No hay foto disponible.</p>
            @endif
        </div>

                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-medium">Nombre</label>
                    <input id="nombre" name="nombre" type="text" class="form-input w-full"
                        value="{{ old('nombre', $articulo->nombre) }}" placeholder="Ingrese el nombre" required>
                </div>

                <!-- Stock Total -->
                <div>
                    <label for="stock_total" class="block text-sm font-medium">Stock Total</label>
                    <input id="stock_total" name="stock_total" type="number" class="form-input w-full"
                        value="{{ old('stock_total', $articulo->stock_total) }}" placeholder="Ingrese el stock total" required>
                </div>

                <!-- Stock Mínimo -->
                <div>
                    <label for="stock_minimo" class="block text-sm font-medium">Stock Mínimo</label>
                    <input id="stock_minimo" name="stock_minimo" type="number" class="form-input w-full"
                        value="{{ old('stock_minimo', $articulo->stock_minimo) }}" placeholder="Ingrese el stock mínimo">
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
                    <label for="idTipoArticulo" class="block text-sm font-medium">Tipo de Artículo</label>
                    <select id="idTipoArticulo" name="idTipoArticulo" class="select2 w-full" style="display:none">
                        <option value="" disabled>Seleccionar Tipo de Artículo</option>
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
                        <option value="" disabled>Seleccionar Modelo</option>
                        @foreach ($modelos as $modelo)
                            <option value="{{ $modelo->idModelo }}"
                                {{ old('idModelo', $articulo->idModelo) == $modelo->idModelo ? 'selected' : '' }}>
                                {{ $modelo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
<!-- Precio Compra con Selector de Moneda -->
<div>
    <label for="precio_compra" class="block text-sm font-medium">Precio de Compra</label>
    <div class="flex">
        <button type="button" id="toggleMonedaCompra"
            class="bg-[#eee] px-3 font-semibold border border-[#e0e6ed]">
            <span id="precio_compra_symbol">
                {{ $articulo->moneda_compra == 1 ? '$' : 'S/' }}
            </span>
        </button>
        <input id="precio_compra" name="precio_compra" type="number" class="form-input flex-1"
            value="{{ old('precio_compra', $articulo->precio_compra) }}">
        <input type="hidden" id="moneda_compra" name="moneda_compra"
            value="{{ old('moneda_compra', $articulo->moneda_compra) }}">
    </div>
</div>

<!-- Precio Venta con Selector de Moneda -->
<div>
    <label for="precio_venta" class="block text-sm font-medium">Precio de Venta</label>
    <div class="flex">
        <button type="button" id="toggleMonedaVenta"
            class="bg-[#eee] px-3 font-semibold border border-[#e0e6ed]">
            <span id="precio_venta_symbol">
                {{ $articulo->moneda_venta == 1 ? '$' : 'S/' }}
            </span>
        </button>
        <input id="precio_venta" name="precio_venta" type="number" class="form-input flex-1"
            value="{{ old('precio_venta', $articulo->precio_venta) }}">
        <input type="hidden" id="moneda_venta" name="moneda_venta"
            value="{{ old('moneda_venta', $articulo->moneda_venta) }}">
    </div>
</div>


                <!-- Peso -->
                <div>
                    <label for="peso" class="block text-sm font-medium">Peso</label>
                    <input id="peso" name="peso" type="text" class="form-input w-full"
                        value="{{ old('peso', $articulo->peso) }}" placeholder="Ingrese el peso">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Mostrar en Web -->
                <div>
                    <label for="mostrarWeb" class="block text-sm font-medium">Mostrar en Web</label>
                    <div class="flex items-center">
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

            <!-- Botones -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('articulos.index') }}" class="btn btn-outline-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Inicializar select2
            document.querySelectorAll('.select2').forEach(select => {
                NiceSelect.bind(select, {
                    searchable: true
                });
            });

            // Manejar cambios en moneda
            const monedaCompraSelect = document.getElementById("moneda_compra");
            const precioCompraSymbol = document.getElementById("precio_compra_symbol");
            const monedaVentaSelect = document.getElementById("moneda_venta");
            const precioVentaSymbol = document.getElementById("precio_venta_symbol");

            if (monedaCompraSelect && precioCompraSymbol) {
                monedaCompraSelect.addEventListener("change", function() {
                    precioCompraSymbol.textContent = monedaCompraSelect.value == 1 ? "S/" : "$";
                });
            }

            if (monedaVentaSelect && precioVentaSymbol) {
                monedaVentaSelect.addEventListener("change", function() {
                    precioVentaSymbol.textContent = monedaVentaSelect.value == 1 ? "S/" : "$";
                });
            }

            // Manejar envío del formulario
            const form = document.getElementById('edit-articulo-form');
            if (form) {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    try {
                        const formData = new FormData(form);
                        formData.append('_method', 'PUT');
                        
                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: formData
                        });

                        const result = await response.json();
                        
                        if (!response.ok) {
                            throw new Error(result.message || 'Error en el servidor');
                        }

                        alert(result.message || 'Artículo actualizado correctamente');
                        window.location.href = "{{ route('articulos.index') }}";
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Error: ' + error.message);
                    }
                });
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
</x-layout.default>