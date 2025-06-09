<x-layout.default>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .clean-input {
            border: none;
            border-bottom: 1px solid #e0e6ed;
            border-radius: 0;
            padding-left: 30px;
            padding-bottom: 8px;
            background-color: transparent;
        }
        .clean-input:focus {
            border-bottom: 2px solid #3b82f6;
            box-shadow: none;
        }
        .input-icon {
            position: absolute;
            left: 0;
            bottom: 8px;
            color: #6b7280;
        }
        .select2-container--default .select2-selection--single {
            border: none;
            border-bottom: 1px solid #e0e6ed;
            border-radius: 0;
            height: 38px;
        }
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-bottom: 2px solid #3b82f6;
        }
        .file-input-label {
            display: block;
            margin-top: 5px;
            color: #6b7280;
            font-size: 0.875rem;
        }
    </style>

    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{ route('heramientas.index') }}" class="text-primary hover:underline">
                    <i class="fas fa-arrow-left mr-1"></i> Heramientas
                </a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Crear Heramienta nueva</span>
            </li>
        </ul>
    </div>

    <div class="panel mt-6 p-5 max-w-4xl mx-auto">
        <h2 class="text-xl font-bold mb-5 flex items-center">
           <i class="fas fa-wrench text-primary mr-2"></i> Agregar Nueva Herramienta
        </h2>


        <form id="articuloForm" method="POST" action="{{ route('articulos.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Código de Barras -->
                <div class="relative">
                    <label for="codigo_barras" class="block text-sm font-medium text-gray-700">Código de Barras *</label>
                    <div class="relative mt-1">
                        <i class="fas fa-barcode input-icon"></i>
                        <input id="codigo_barras" name="codigo_barras" type="text" class="clean-input w-full"
                            placeholder="Ingrese código de barras" required>
                    </div>
                </div>

                <!-- SKU -->
                <div class="relative">
                    <label for="sku" class="block text-sm font-medium text-gray-700">SKU</label>
                    <div class="relative mt-1">
                        <i class="fas fa-tag input-icon"></i>
                        <input id="sku" name="sku" type="text" class="clean-input w-full" placeholder="Ingrese SKU">
                    </div>
                </div>

                <!-- Nombre -->
                <div class="relative">
                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre *</label>
                    <div class="relative mt-1">
                        <i class="fas fa-cogs input-icon"></i>
                        <input id="nombre" name="nombre" type="text" class="clean-input w-full" placeholder="Ingrese nombre" required>
                    </div>
                </div>

                <!-- Stock Total -->
                <div class="relative">
                    <label for="stock_total" class="block text-sm font-medium text-gray-700">Stock Total *</label>
                    <div class="relative mt-1">
                        <i class="fas fa-boxes input-icon"></i>
                        <input id="stock_total" name="stock_total" type="number" min="0" class="clean-input w-full"
                            placeholder="Ingrese stock total" required>
                    </div>
                </div>

                <!-- Stock Mínimo -->
                <div class="relative">
                    <label for="stock_minimo" class="block text-sm font-medium text-gray-700">Stock Mínimo *</label>
                    <div class="relative mt-1">
                        <i class="fas fa-boxes input-icon"></i>
                        <input id="stock_minimo" name="stock_minimo" type="number" min="0" class="clean-input w-full"
                            placeholder="Ingrese stock mínimo" required>
                    </div>
                </div>

                <!-- Unidad de Medida -->
                <div class="relative">
                    <label for="idUnidad" class="block text-sm font-medium text-gray-700">Unidad de Medida *</label>
                    <div class="relative mt-1">
                        <i class="fas fa-balance-scale input-icon"></i>
                        <select id="idUnidad" name="idUnidad" class="clean-input w-full pl-8" style="appearance: none;" required>
                            <option value="" disabled selected>Seleccionar Unidad</option>
                            @foreach ($unidades as $unidad)
                                <option value="{{ $unidad->idUnidad }}">{{ $unidad->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Modelo -->
                <div>
                    <label for="idModelo" class="block text-sm font-medium text-gray-700">Modelo *</label>
                    <div class="relative mt-1">
                        <i class="fas fa-toolbox input-icon"></i>
                        <select id="idModelo" name="idModelo" class="select2-single w-full" required>
                            <option value="" disabled selected>Seleccionar modelo</option>
                            @foreach ($modelos as $modelo)
                                <option value="{{ $modelo->idModelo }}">
                                    {{ $modelo->nombre }} - {{ $modelo->marca->nombre ?? 'Sin Marca' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Peso -->
                <div>
                    <label for="peso" class="block text-sm font-medium text-gray-700">Peso (kg) *</label>
                    <div class="relative mt-1">
                        <i class="fas fa-weight input-icon"></i>
                        <input id="peso" name="peso" type="number" step="0.01" min="0" class="clean-input w-full" placeholder="Ingrese peso" required>
                    </div>
                </div>

                <!-- Precio de Compra -->
                <div>
                    <label for="precio_compra" class="block text-sm font-medium text-gray-700">Precio de Compra *</label>
                    <div class="flex items-center mt-1">
                        <button type="button" id="toggleMonedaCompra"
                            class="text-gray-500 px-2 h-10 border-b border-gray-300">
                            <span id="precio_compra_symbol" class="w-8 text-center">S/</span>
                        </button>
                        <div class="relative flex-1">
                            <input id="precio_compra" name="precio_compra" type="number" step="0.01" min="0"
                                class="clean-input w-full" placeholder="0.00" required>
                        </div>
                        <input type="hidden" id="moneda_compra" name="moneda_compra" value="0">
                    </div>
                </div>

                <!-- Precio de Venta -->
                <div>
                    <label for="precio_venta" class="block text-sm font-medium text-gray-700">Precio de Venta *</label>
                    <div class="flex items-center mt-1">
                        <button type="button" id="toggleMonedaVenta"
                            class="text-gray-500 px-2 h-10 border-b border-gray-300">
                            <span id="precio_venta_symbol" class="w-8 text-center">S/</span>
                        </button>
                        <div class="relative flex-1">
                            <input id="precio_venta" name="precio_venta" type="number" step="0.01" min="0"
                                class="clean-input w-full" placeholder="0.00" required>
                        </div>
                        <input type="hidden" id="moneda_venta" name="moneda_venta" value="0">
                    </div>
                </div>

                <!-- Foto -->
                <div class="mb-5" x-data="{ fotoPreview: '' }">
                    <label class="block text-sm font-medium text-gray-700">Foto</label>
                    <label for="foto" class="file-input-label">Seleccionar archivo</label>
                    <div class="relative mt-1">
                        <input id="foto" name="foto" type="file" accept="image/*"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                            @change="fotoPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : ''">
                        <div class="border-b border-gray-300 pb-2 flex justify-between items-center">
                            <span x-text="fotoPreview ? 'Archivo seleccionado' : 'Ningún archivo seleccionado'" 
                                  class="text-gray-500 text-sm"></span>
                            <i class="fas fa-camera text-gray-400"></i>
                        </div>
                    </div>

                    <div class="flex justify-center mt-4">
                        <div class="w-full max-w-xs h-40 flex justify-center items-center bg-gray-50 rounded">
                            <template x-if="fotoPreview">
                                <img :src="fotoPreview" alt="Previsualización de la imagen"
                                    class="w-full h-full object-contain">
                            </template>
                            <template x-if="!fotoPreview">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="fas fa-image text-3xl mb-2"></i>
                                    <span class="text-sm">Vista previa de imagen</span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

      <!-- Ficha Técnica -->
<div class="mb-5">
    <label class="block text-sm font-medium text-gray-700">Ficha Técnica (PDF)</label>
    <label for="ficha_tecnica" class="file-input-label">Seleccionar archivo</label>
    <div class="relative mt-1">
        <input id="ficha_tecnica" name="ficha_tecnica" type="file" accept=".pdf"
            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
        <div class="border-b border-gray-300 pb-2 flex justify-between items-center">
            <span id="nombre_archivo" class="text-gray-500 text-sm">Ningún archivo seleccionado</span>
            <i class="fas fa-file-pdf text-gray-400"></i>
        </div>
    </div>

    <!-- Vista previa del PDF -->
<div id="preview_pdf" class="mt-4 max-w-full">
        <label class="block text-sm font-medium text-gray-700 mb-2">Vista previa:</label>
<iframe id="pdf_viewer" class="w-full h-[600px] border rounded" type="application/pdf"></iframe>
    </div>
</div>

            </div>

            <div class="flex justify-end mt-6">
                <a href="{{ route('articulos.index') }}" class="btn btn-outline-danger flex items-center">
                    <i class="fas fa-times mr-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary ml-4 flex items-center">
                    <i class="fas fa-save mr-1"></i> Guardar Artículo
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Inicializar Select2
        $('.select2-single').select2({
            placeholder: "Seleccione un modelo",
            width: '100%',
            minimumResultsForSearch: 5
        });

        const monedas = @json($monedas);
        let monedaCompraIndex = 0;
        let monedaVentaIndex = 0;

        const symbolCompra = document.getElementById("precio_compra_symbol");
        const symbolVenta = document.getElementById("precio_venta_symbol");
        const monedaInputCompra = document.getElementById("moneda_compra");
        const monedaInputVenta = document.getElementById("moneda_venta");

        if (monedas.length > 0) {
            symbolCompra.textContent = monedas[monedaCompraIndex].nombre;
            monedaInputCompra.value = monedas[monedaCompraIndex].idMonedas;

            symbolVenta.textContent = monedas[monedaVentaIndex].nombre;
            monedaInputVenta.value = monedas[monedaVentaIndex].idMonedas;

            document.getElementById("toggleMonedaCompra").addEventListener("click", function () {
                monedaCompraIndex = (monedaCompraIndex + 1) % monedas.length;
                symbolCompra.textContent = monedas[monedaCompraIndex].nombre;
                monedaInputCompra.value = monedas[monedaCompraIndex].idMonedas;
            });

            document.getElementById("toggleMonedaVenta").addEventListener("click", function () {
                monedaVentaIndex = (monedaVentaIndex + 1) % monedas.length;
                symbolVenta.textContent = monedas[monedaVentaIndex].nombre;
                monedaInputVenta.value = monedas[monedaVentaIndex].idMonedas;
            });
        }
    });
    </script>

    <script>
    document.getElementById('ficha_tecnica').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const fileName = document.getElementById('nombre_archivo');
        const previewContainer = document.getElementById('preview_pdf');
        const pdfViewer = document.getElementById('pdf_viewer');

        if (file && file.type === 'application/pdf') {
            fileName.textContent = file.name;

            const fileURL = URL.createObjectURL(file);
            pdfViewer.src = fileURL;
            previewContainer.classList.remove('hidden');
        } else {
            fileName.textContent = 'Archivo no válido';
            previewContainer.classList.add('hidden');
        }
    });
</script>
   <script>
    document.getElementById('ficha_tecnica').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const fileName = document.getElementById('nombre_archivo');
        const previewContainer = document.getElementById('preview_pdf');
        const pdfViewer = document.getElementById('pdf_viewer');

        if (file && file.type === 'application/pdf') {
            fileName.textContent = file.name;

            const fileURL = URL.createObjectURL(file);
            pdfViewer.src = fileURL;
            previewContainer.classList.remove('hidden');
        } else {
            fileName.textContent = 'Archivo no válido';
            previewContainer.classList.add('hidden');
        }
    });
</script>
</x-layout.default>
