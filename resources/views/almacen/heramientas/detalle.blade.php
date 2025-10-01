<x-layout.default>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Contenedor principal -->
    <div x-data="modalHandler()">
        <!-- Breadcrumbs -->
        <div>
            <ul class="flex space-x-2 rtl:space-x-reverse mb-4">
                <li>
                    <a href="{{ route('heramientas.index') }}" 
                       class="text-primary hover:underline flex items-center gap-2">
                        <i class="fas fa-tools w-4 h-4"></i>
                        Herramientas
                    </a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1 flex items-center gap-2">
                    <i class="fas fa-info-circle w-4 h-4 text-gray-500"></i>
                    <span>Detalle Herramienta</span>
                </li>
            </ul>
        </div>

        <!-- Panel principal mejorado -->
        <div class="panel rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Header con gradiente -->
            <div class="px-6 py-8">
                <div class="text-center">
                    <h1 class="text-4xl sm:text-5xl font-bold uppercase tracking-tight mb-2">
                        {{ $articulo->nombre }}
                    </h1>

                    <div class="flex justify-center items-center space-x-4 text-primary">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 me-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ date('d/m/Y', strtotime($articulo->fecha_ingreso)) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Contenido principal -->
            <div class="p-6 space-y-6">
                <!-- Sección de códigos mejorada -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Tarjeta Código de Barras -->
                    <div
                        class="bg-gradient-to-br from-white to-blue-50 rounded-lg border border-primary p-6 shadow-sm hover:shadow-md transition-all duration-300">
                        <div class="flex flex-col items-center text-center">
                            <div
                                class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full mb-4">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-blue-700 mb-3">CÓDIGO DE BARRAS</h3>

                            <div class="bg-white rounded-lg p-4 border border-blue-200 mb-4 w-full">
                                <p class="font-mono text-xl font-bold text-gray-800 tracking-wider">
                                    {{ $articulo->codigo_barras }}</p>
                                @if ($articulo->foto_codigobarras)
                                    <div class="mt-4 p-3 bg-white rounded border border-blue-100">
                                        <img src="data:image/png;base64,{{ base64_encode($articulo->foto_codigobarras) }}"
                                            alt="Código de barras" class="h-16 mx-auto">
                                    </div>
                                @endif
                            </div>

                            <button type="button"
                                @click="openModal('{{ $articulo->codigo_barras }}', '{{ $articulo->foto_codigobarras ? base64_encode($articulo->foto_codigobarras) : '' }}')"
                                class="btn btn-primary w-full text-base py-3 gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z" />
                                </svg>
                                IMPRIMIR CÓDIGO
                            </button>
                        </div>
                    </div>

                    <!-- Tarjeta SKU -->
                    <div
                        class="bg-gradient-to-br from-white to-purple-50 rounded-lg border border-secondary p-6 shadow-sm hover:shadow-md transition-all duration-300">
                        <div class="flex flex-col items-center text-center">
                            <div
                                class="inline-flex items-center justify-center w-12 h-12 bg-purple-100 rounded-full mb-4">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-purple-700 mb-3">SKU</h3>

                            <div class="bg-white rounded-lg p-4 border border-purple-200 mb-4 w-full">
                                <p class="font-mono text-xl font-bold text-gray-800 tracking-wider">{{ $articulo->sku }}
                                </p>
                                @if ($articulo->fotosku)
                                    <div class="mt-4 p-3 bg-white rounded border border-orange-100">
                                        <img src="data:image/png;base64,{{ base64_encode($articulo->fotosku) }}"
                                            alt="SKU" class="h-16 mx-auto">
                                    </div>
                                @endif
                            </div>

                            <button type="button" @click="openModal('{{ $articulo->sku }}')"
                                class="btn btn-secondary w-full text-base py-3 gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z" />
                                </svg>
                                IMPRIMIR SKU
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sección de información principal -->
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
                                    DETALLES DEL PRODUCTO
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
                                            <td class="px-6 py-4 font-medium text-gray-600">Peso</td>
                                            <td class="px-6 py-4 font-mono font-semibold">{{ $articulo->peso }} kg</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 font-medium text-gray-600">Stock Total</td>
                                            <td class="px-6 py-4">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                    {{ $articulo->stock_total }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 font-medium text-gray-600">Stock Mínimo</td>
                                            <td class="px-6 py-4">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                                    {{ $articulo->stock_minimo }}
                                                </span>
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
                                <div class="font-semibold text-gray-800">{{ $articulo->modelo->nombre ?? 'N/A' }}
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
                                    IMAGEN DEL PRODUCTO
                                </h2>
                            </div>

                            <div class="p-6">
                                <div class="flex justify-center">
                                    <img src="data:image/png;base64,{{ base64_encode($articulo->foto) }}"
                                        alt="Imagen del producto"
                                        class="max-h-80 rounded-lg shadow-md object-contain transition-transform duration-300 hover:scale-105">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>

        <!-- Modal -->
        <div class="mb-5">
            <!-- modal -->
            <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="isOpen && '!block'">
                <div class="flex items-start justify-center min-h-screen px-4" @click.self="closeModal()">
                    <div x-show="isOpen" x-transition x-transition.duration.300
                        class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-lg">

                        <!-- Header -->
                        <div
                            class="flex bg-gradient-to-r from-amber-500 to-amber-600 items-center justify-between px-5 py-3">
                            <div class="font-bold text-lg text-[#1f2937] dark:text-white">

                                Imprimir código
                            </div>
                            <button type="button" class="text-white/80 hover:text-white" @click="closeModal()">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Body -->
                        <div class="p-5">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Código a imprimir</label>
                                <div class="bg-gray-50 rounded-lg p-3 border text-center">
                                    <p x-text="currentCode" class="font-mono text-lg font-bold text-gray-800"></p>
                                </div>
                            </div>
                            <div>
                                <label for="printQuantity" class="block text-sm font-medium text-gray-700 mb-2">
                                    Cantidad de copias
                                </label>
                                <input type="number" x-model="quantity" id="printQuantity" min="1"
                                    max="100"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="flex justify-end items-center px-5 py-3 border-t bg-gray-50">
                            <button type="button" class="btn btn-outline-danger"
                                @click="closeModal()">Cancelar</button>
                            <button type="button" class="btn btn-primary ltr:ml-4 rtl:mr-4"
                                @click="printBarcode()">Imprimir</button>
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
                currentBarcodeImage: '',
                quantity: 1,
                barcodesPerRow: 3, // Número de códigos por fila

                openModal(code, barcodeImage = '') {
                    this.currentCode = code;
                    this.currentBarcodeImage = barcodeImage;
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

                    // Crear contenido HTML para la impresión
                    let printContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Impresión de Código de Barras</title>
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
                            word-break: break-all;
                        }
                        @page { 
                            size: auto; 
                            margin: 5mm;
                        }
                        @media print {
                            body { padding: 0; }
                            .barcode-row {
                                page-break-inside: avoid;
                            }
                        }
                    </style>
                    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"><\/script>
                </head>
                <body>
                    <div class="barcode-page">
            `;

                    // Calcular cuántas filas necesitamos
                    const totalRows = Math.ceil(this.quantity / this.barcodesPerRow);

                    // Agregar múltiples copias según la cantidad
                    for (let row = 0; row < totalRows; row++) {
                        printContent += `<div class="barcode-row">`;

                        // Agregar los códigos de barras para esta fila
                        const startIndex = row * this.barcodesPerRow;
                        const endIndex = Math.min(startIndex + this.barcodesPerRow, this.quantity);

                        for (let i = startIndex; i < endIndex; i++) {
                            if (this.currentBarcodeImage) {
                                // Usar la imagen existente del código de barras
                                printContent += `
                            <div class="barcode-container">
                                <img src="data:image/png;base64,${this.currentBarcodeImage}" style="max-width: 100%; height: auto;">
                                <div class="barcode-text">${this.currentCode}</div>
                            </div>
                        `;
                            } else {
                                // Generar nuevo código de barras (Code128)
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

                        printContent += `</div>`; // Cierre de barcode-row
                    }

                    printContent += `
                    </div>
                    <script>
                        window.onload = function() {
                            setTimeout(function() {
                                window.print();
                                window.close();
                            }, 200);
                        }
                    <\/script>
                </body>
                </html>
            `;

                    // Obtener el iframe y cargar el contenido
                    const printFrame = document.getElementById('printFrame');
                    const frameWindow = printFrame.contentWindow;

                    if (frameWindow) {
                        frameWindow.document.open();
                        frameWindow.document.write(printContent);
                        frameWindow.document.close();
                    } else {
                        console.error("No se pudo acceder al iframe para imprimir");
                    }

                    this.closeModal();
                }
            }));
        });
    </script>
</x-layout.default>