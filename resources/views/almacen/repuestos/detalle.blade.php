<x-layout.default>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Contenedor principal -->
    <div x-data="modalHandler()">
        <!-- Breadcrumbs -->
        <div class="mb-6">
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="{{ route('repuestos.index') }}" class="text-primary hover:underline flex items-center gap-2">
                        <i class="fas fa-boxes w-4 h-4"></i>
                        Repuestos
                    </a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1 flex items-center gap-2">
                    <i class="fas fa-file-alt w-4 h-4 text-gray-500"></i>
                    <span>Detalle Repuesto</span>
                </li>
            </ul>
        </div>

        <!-- Panel principal mejorado -->
        <div class="panel rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Header con gradiente -->
            <div class="px-6 py-8">
                <div class="text-center">
                    <h1 class="text-4xl sm:text-5xl font-bold uppercase tracking-tight mb-2">
                        {{ $articulo->codigo_repuesto }}
                    </h1>

                    <div class="text-primary text-font-bold">
                        {{ $articulo->subcategoria->nombre ?? 'Sin subcategoría' }} /
                        @if ($modelos->isNotEmpty())
                            {{ $modelos->first()->nombre ?? 'Sin modelo' }}
                        @else
                            Sin modelo
                        @endif
                    </div>
                </div>
            </div>

            <!-- Contenido principal -->
            <div class="p-6 space-y-6">
                <!-- Sección de códigos mejorada -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Tarjeta Código de Repuesto -->
                    <div
                        class="bg-gradient-to-br from-white to-blue-50 rounded-xl border border-primary p-6 shadow-sm hover:shadow-md transition-all duration-300">
                        <div class="flex flex-col items-center text-center h-full">
                            <div
                                class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full mb-4">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-blue-700 mb-3">CÓDIGO DE REPUESTO</h3>

                            @php
                                $textDisplay = $articulo->codigo_repuesto;
                                $marcaNombre = '';
                                $subcategoriaNombre = '';
                                $modeloNombre = '';

                                if ($articulo->modelos->isNotEmpty()) {
                                    $modelo = $articulo->modelos->first();
                                    $modeloNombre = $modelo->nombre ?? 'Sin modelo';
                                    $marcaNombre = $modelo->marca->nombre ?? 'Sin marca';
                                    $subcategoriaNombre = $articulo->subcategoria->nombre ?? 'Sin subcategoría';
                                    $textDisplay = "$marcaNombre / $subcategoriaNombre / $modeloNombre";
                                }
                            @endphp

                            <!-- NOMBRE DE LA MARCA -->
                            @if ($marcaNombre)
                                <h3 class="text-lg font-bold text-gray-700 uppercase mb-2">{{ $marcaNombre }}</h3>
                            @endif

                            <!-- CÓDIGO DE BARRAS -->
                            @if ($articulo->{'br-codigo-repuesto'})
                                <div class="flex flex-col items-center gap-2 mb-4">
                                    <img src="data:image/png;base64,{{ base64_encode($articulo->{'br-codigo-repuesto'}) }}"
                                        alt="Código de barras del repuesto" class="h-16 mx-auto">
                                </div>
                            @endif

                            <!-- CÓDIGO DE REPUESTO -->
                            <div class="bg-white rounded-lg p-4 border border-blue-100 mb-4 w-full">
                                <p class="font-mono text-xl font-bold text-gray-800 tracking-wider">
                                    {{ $articulo->codigo_repuesto }}
                                </p>
                            </div>

                            <!-- MARCA / CATEGORÍA / MODELO -->
                            <p class="text-sm text-gray-600 mb-4">
                                {{ $textDisplay }}
                            </p>

                            <!-- Botón alineado abajo -->
                            <!-- Botón alineado abajo -->
                            <div class="mt-auto w-full">
                                @if(\App\Helpers\PermisoHelper::tienePermiso('IMPRIMIR CODIGO REPUESTO'))
                                <button
                                    @click="openModal('{{ $articulo->codigo_repuesto }}', '{{ base64_encode($articulo->{'br-codigo-repuesto'}) }}')"
                                    class="btn btn-primary w-full text-base py-3">
                                    IMPRIMIR CÓDIGO
                                </button>
                                @endif
                            </div>

                        </div>
                    </div>

                    <!-- Tarjeta SKU -->
                    <div
                        class="bg-gradient-to-br from-white to-purple-50 rounded-xl border border-secondary p-6 shadow-sm hover:shadow-md transition-all duration-300">
                        <div class="flex flex-col items-center text-center h-full">
                            <div
                                class="inline-flex items-center justify-center w-12 h-12 bg-purple-100 rounded-full mb-4">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                </svg>
                            </div>
                            <!-- 🔥 CAMBIO AQUÍ -->
                            <h3 class="text-lg font-semibold text-secondary mb-3">CÓDIGO SKU</h3>

                            <!-- CÓDIGO DE BARRAS SKU -->
                            @if ($articulo->fotosku)
                                <div class="flex flex-col items-center gap-2 mb-4">
                                    <img src="data:image/png;base64,{{ base64_encode($articulo->fotosku) }}"
                                        alt="Código de barras SKU" class="h-16 mx-auto">
                                </div>
                            @endif

                            <!-- SKU -->
                            <div class="bg-white rounded-lg p-4 border border-purple-100 mb-4 w-full">
                                <p class="font-mono text-xl font-bold text-gray-800 tracking-wider">
                                    {{ $articulo->sku }}
                                </p>
                            </div>

                            <!-- Botón alineado abajo -->
                            <div class="mt-auto w-full">
                                @if(\App\Helpers\PermisoHelper::tienePermiso('IMPRIMIR SKU REPUESTO'))
                                <button
                                    @click="openModal('{{ $articulo->sku }}', '{{ base64_encode($articulo->fotosku) }}')"
                                    class="btn btn-secondary w-full text-base py-3">
                                    IMPRIMIR SKU
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Contenedor principal con foto y detalles lado a lado -->
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                    <!-- Información del producto -->
                    <div class="xl:col-span-2">
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                            <!-- Header de la tarjeta -->
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b">
                                <h2 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                                    <svg class="w-6 h-6 text-blue-600 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 12V6a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 002 6v6a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0020 12z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 22V12" />
                                    </svg>
                                    DETALLES DEL REPUESTO
                                </h2>
                            </div>

                            <!-- Tabla de detalles -->
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <tbody class="divide-y divide-gray-100">
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 font-medium text-gray-600">ID Artículo</td>
                                            <td class="px-6 py-4">
                                                <span
                                                    class="bg-blue-100 text-blue-800 text-sm font-mono px-2 py-1 rounded">{{ $articulo->idArticulos }}</span>
                                            </td>
                                            <td class="px-6 py-4 font-medium text-gray-600">Stock Total</td>
                                            <td class="px-6 py-4">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                    {{ $articulo->stock_total }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 font-medium text-gray-600">Stock Mínimo</td>
                                            <td class="px-6 py-4">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                                    {{ $articulo->stock_minimo }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 font-medium text-gray-600">Peso</td>
                                            <td class="px-6 py-4 font-mono font-semibold">{{ $articulo->peso }} kg
                                            </td>
                                        </tr>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 font-medium text-gray-600">Precio Compra</td>
                                            <td class="px-6 py-4">
                                                <span class="font-mono font-bold text-red-600">
                                                    {{ $articulo->moneda_compra == 1 ? 'S/' : '$' }}{{ number_format($articulo->precio_compra, 2) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 font-medium text-gray-600">Precio Venta</td>
                                            <td class="px-6 py-4">
                                                <span class="font-mono font-bold text-green-600">
                                                    {{ $articulo->moneda_venta == 1 ? 'S/' : '$' }}{{ number_format($articulo->precio_venta, 2) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 font-medium text-gray-600">Estado</td>
                                            <td class="px-6 py-4">
                                                @if ($articulo->estado == 1)
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800">
                                                        <span class="w-2 h-2 bg-emerald-400 rounded-full me-1"></span>
                                                        Activo
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                        <span class="w-2 h-2 bg-red-400 rounded-full me-1"></span>
                                                        Inactivo
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 font-medium text-gray-600">Fecha Ingreso</td>
                                            <td class="px-6 py-4 font-semibold text-gray-700">
                                                {{ date('d/m/Y', strtotime($articulo->fecha_ingreso)) }}</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 font-medium text-gray-600">Código de Barras</td>
                                            <td class="px-6 py-4 font-mono">{{ $articulo->codigo_barras }}</td>
                                            <td class="px-6 py-4 font-medium text-gray-600">SKU</td>
                                            <td class="px-6 py-4 font-mono">{{ $articulo->sku }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Información de relaciones -->
                        <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="bg-white rounded-lg border border-gray-200 p-4 text-center shadow-sm">
                                <div
                                    class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="text-sm font-medium text-gray-600">Categoría</div>
                                <div class="font-semibold text-gray-800">
                                    {{ $articulo->tipoArticulo->nombre ?? 'N/A' }}</div>
                            </div>

                            <div class="bg-white rounded-lg border border-gray-200 p-4 text-center shadow-sm">
                                <div
                                    class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                                    </svg>
                                </div>
                                <div class="text-sm font-medium text-gray-600">Modelo</div>
                                <div class="font-semibold text-gray-800">{{ $articulo->modelos->first()->nombre ?? 'N/A' }}

                                </div>
                            </div>

                            <div class="bg-white rounded-lg border border-gray-200 p-4 text-center shadow-sm">
                                <div
                                    class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="text-sm font-medium text-gray-600">Unidad</div>
                                <div class="font-semibold text-gray-800">{{ $articulo->unidad->nombre ?? 'N/A' }}
                                </div>
                            </div>

                            <div class="bg-white rounded-lg border border-gray-200 p-4 text-center shadow-sm">
                                <div
                                    class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h11-2zm2 4h-2v2h2V9z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="text-sm font-medium text-gray-600">Área</div>
                                <div class="font-semibold text-gray-800">{{ $articulo->tipoArea->nombre ?? 'N/A' }}
                                </div>
                            </div>
                        </div>

                        <!-- Ficha técnica si existe -->
                        @if ($articulo->ficha_tecnica)
                            <div class="mt-6 bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b">
                                    <h2 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                                        <svg class="w-6 h-6 text-blue-600 flex-shrink-0" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        FICHA TÉCNICA
                                    </h2>
                                </div>
                                <div class="p-6 text-center">
                                    <a href="{{ asset('storage/fichas/' . $articulo->ficha_tecnica) }}"
                                        target="_blank"
                                        class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Descargar Ficha Técnica
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Imagen del producto -->
                    @if ($articulo->foto)
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b">
                                <h2 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                                    <svg class="w-6 h-6 text-blue-600 flex-shrink-0" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    IMAGEN DEL REPUESTO
                                </h2>
                            </div>

                            <div class="p-6">
                                <div class="flex justify-center">
                                    <img src="data:image/png;base64,{{ base64_encode($articulo->foto) }}"
                                        alt="Imagen del repuesto"
                                        class="max-h-80 rounded-lg shadow-md object-contain transition-transform duration-300 hover:scale-105">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Modal estilo panel -->
        <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="isOpen && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="closeModal()">
                <div x-show="isOpen" x-transition x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-lg">
                    <!-- Header -->
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <!-- Título del modal controlado por Alpine -->
                        <div class="font-bold text-lg text-[#1f2937] dark:text-white" x-text="modalTitle"></div>

                        <button type="button" class="text-white-dark hover:text-dark" @click="closeModal()">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-5">
                        <div class="space-y-4 text-[#1f2937] dark:text-white-dark/70">
                            <div>
                                <label class="block text-sm font-medium mb-2">Código</label>
                                <p x-text="currentCode" class="font-mono text-lg font-bold text-center"></p>
                            </div>

                            <template x-if="currentDetails">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Detalles</label>
                                    <p x-text="currentDetails" class="text-sm text-center"></p>
                                </div>
                            </template>

                            <div>
                                <label for="printQuantity" class="block text-sm font-medium mb-2">Cantidad a
                                    imprimir</label>
                                <input type="number" x-model="quantity" id="printQuantity" min="1"
                                    max="100" class="form-input w-full text-sm p-2 border-gray-300 rounded-md">
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="flex justify-end items-center mt-8">
                            <button type="button" class="btn btn-outline-danger"
                                @click="closeModal()">Cancelar</button>
                            <button type="button" class="btn btn-primary ltr:ml-4 rtl:mr-4"
                                @click="printBarcode()">Imprimir</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Iframe oculto para impresión -->
        <iframe id="printFrame" class="hidden"></iframe>
    </div>

    <!-- Scripts -->
    <script src="//unpkg.com/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('modalHandler', () => ({
                isOpen: false,
                currentCode: '',
                currentDetails: '',
                currentBarcodeImage: '',
                quantity: 1,
                barcodesPerRow: 3, // como en suministros
                modalTitle: 'Imprimir código',

                openModal(code, barcodeImage = '', details = '') {
                    this.modalTitle = 'Imprimir Código';
                    this.currentCode = code;
                    this.currentBarcodeImage = barcodeImage;
                    this.currentDetails = details;
                    this.showDetails = !!details;
                    this.quantity = 1;
                    this.isOpen = true;
                },

                closeModal() {
                    this.isOpen = false;
                },

                printBarcode() {
                    if (!this.currentCode) {
                        alert('No hay código para imprimir');
                        return;
                    }

                    let printContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Impresión de Código</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 0; padding: 10px; }
                        .barcode-page {
                            display: flex;
                            flex-direction: column;
                            gap: 10px;
                        }
                        .barcode-row {
                            display: flex;
                            justify-content: flex-start;
                            flex-wrap: nowrap;
                            gap: 10px;
                            page-break-inside: avoid;
                        }
                        .barcode-container {
                            flex: 1;
                            min-width: calc(33.33% - 10px);
                            max-width: calc(33.33% - 10px);
                            text-align: center;
                            page-break-inside: avoid;
                        }
                        .barcode-text { 
                            font-size: 12px; 
                            margin-top: 5px; 
                            word-break: break-word;
                        }
                        @page { 
                            size: auto; 
                            margin: 5mm;
                        }
                        @media print {
                            body { padding: 0; }
                            .barcode-row { page-break-inside: avoid; }
                        }
                    </style>
                    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"><\/script>
                </head>
                <body>
                    <div class="barcode-page">
            `;

                    const totalRows = Math.ceil(this.quantity / this.barcodesPerRow);

                    for (let row = 0; row < totalRows; row++) {
                        printContent += `<div class="barcode-row">`;
                        const startIndex = row * this.barcodesPerRow;
                        const endIndex = Math.min(startIndex + this.barcodesPerRow, this.quantity);

                        for (let i = startIndex; i < endIndex; i++) {
                            if (this.currentBarcodeImage) {
                                printContent += `
                            <div class="barcode-container">
                                <img src="data:image/png;base64,${this.currentBarcodeImage}" style="max-width: 100%; height: auto;">
                                <div class="barcode-text">${this.currentCode}</div>
                            </div>
                        `;
                            } else {
                                printContent += `
                            <div class="barcode-container">
                                <svg id="barcode-${i}"></svg>
                                <div class="barcode-text">${this.currentCode}</div>
                            </div>
                            <script>
                                JsBarcode('#barcode-${i}', '${this.currentCode}', {
                                    format: "CODE128",
                                    lineColor: "#000",
                                    width: 2,
                                    height: 50,
                                    displayValue: false
                                });
                            <\/script>
                        `;
                            }
                        }

                        printContent += `</div>`;
                    }

                    printContent += `
                    </div>
                    <script>
                        window.onload = function() {
                            setTimeout(() => { 
                                window.print(); 
                                window.close(); 
                            }, 200);
                        };
                    <\/script>
                </body>
                </html>
            `;

                    const printFrame = document.getElementById('printFrame');
                    const frameDoc = printFrame.contentWindow || printFrame.contentDocument;
                    if (frameDoc.document) {
                        frameDoc.document.open();
                        frameDoc.document.write(printContent);
                        frameDoc.document.close();
                    } else {
                        printFrame.src = 'data:text/html;charset=utf-8,' + encodeURIComponent(
                            printContent);
                    }

                    this.closeModal();
                }
            }));
        });
    </script>
</x-layout.default>
