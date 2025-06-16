<x-layout.default>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">

    <!-- Contenedor principal -->
    <div x-data="modalHandler()">
        <!-- Breadcrumbs -->
        <div>
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="{{ route('suministros.index') }}" class="text-primary hover:underline">Suministro</a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>Detalle Suministro</span>
                </li>
            </ul>
        </div>

        <!-- Panel principal -->
        <div class="panel mt-6 p-6 max-w-4x2 mx-auto">
            <!-- Encabezado -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold uppercase tracking-wide">
                    {{ $articulo->nombre }}
                </h1>
            </div>

            <!-- Sección de códigos -->
            <div class="mb-6">
                <div class="mb-4 flex items-center gap-2">
                    <span class="badge badge-outline-warning text-sm px-3 py-1.5">CÓDIGO DE BARRAS Y SKU</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Columna izquierda - Código de barras -->
                    <div class="rounded-lg p-4 shadow-sm hover:shadow-md transition-all duration-200"
                        style="border: 1px solid #e2a03f;">

                        <div class="space-y-3">
                            <div class="text-center">
                                <h3 class="text-base font-semibold text-[#e2a03f] mb-2">CÓDIGO DE BARRAS</h3>
                                <p class="font-mono text-lg font-bold">{{ $articulo->codigo_barras }}</p>
                                @if ($articulo->foto_codigobarras)
                                    <div class="mt-3">
                                        <img src="data:image/png;base64,{{ base64_encode($articulo->foto_codigobarras) }}"
                                            alt="Código de barras" class="h-20 mx-auto">
                                    </div>
                                @endif
                            </div>

                            <div class="flex justify-center mt-4">
                                <button type="button"
                                    @click="openModal('{{ $articulo->codigo_barras }}', '{{ $articulo->foto_codigobarras ? base64_encode($articulo->foto_codigobarras) : '' }}')"
                                    class="btn btn-warning px-4 py-1.5 text-sm rounded-md flex items-center gap-2">
                                    <span>IMPRIMIR</span>
                                    <span class="font-mono font-bold">{{ $articulo->codigo_barras }}</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Columna derecha - SKU -->
                    <div class="rounded-lg p-4 shadow-sm hover:shadow-md transition-all duration-200"
                        style="border: 1px solid #e2a03f;">
                        <div class="space-y-3">
                            <div class="text-center">
                                <h3 class="text-base font-semibold text-[#e2a03f] mb-2">SKU</h3>
                                <p class="font-mono text-lg font-bold">{{ $articulo->sku }}</p>
                                @if ($articulo->fotosku)
                                    <div class="mt-3">
                                        <img src="data:image/png;base64,{{ base64_encode($articulo->fotosku) }}"
                                            alt="SKU" class="h-20 mx-auto">
                                    </div>
                                @endif
                            </div>


                            <div class="flex justify-center mt-4">
                                <button type="button" @click="openModal('{{ $articulo->sku }}')"
                                    class="btn btn-warning px-4 py-1.5 text-sm rounded-md flex items-center gap-2">
                                    <span>IMPRIMIR</span>
                                    <span class="font-mono font-bold">{{ $articulo->sku }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Contenedor principal con foto y detalles lado a lado -->
            <div class="mb-6 flex flex-col md:flex-row gap-4">
                <!-- Sección de información del producto (izquierda) -->
                <div class="flex justify-center w-full">
                    <div class="p-4 rounded-lg border border-gray-200 flex-1" style="border: 1px solid #e2a03f;">
                        <h2 class="font-bold mb-4 text-lg text-center">-DETALLES DEL PRODUCTO-</h2>

                        <div class="overflow-x-auto mb-6">
                            <table class="min-w-full rounded-xl shadow-md">
                                <thead class="text-[#e2a03f] uppercase text-sm">
                                    <tr>
                                        <th class="py-3 px-4 text-left">Descripción</th>
                                        <th class="py-3 px-4 text-left">Valor</th>
                                        <th class="py-3 px-4 text-left">Descripción</th>
                                        <th class="py-3 px-4 text-left">Valor</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm text-gray-700 divide-y divide-[#e2a03f]/40">
                                    <tr>
                                        <td class="py-2 px-4">ID Artículo</td>
                                        <td class="py-2 px-4 font-mono text-center">{{ $articulo->idArticulos }}</td>
                                        <td class="py-2 px-4">Peso</td>
                                        <td class="py-2 px-4 font-mono text-center">{{ $articulo->peso }} kg</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-4">Stock Total</td>
                                        <td class="py-2 px-4 font-mono text-center">{{ $articulo->stock_total }}</td>
                                        <td class="py-2 px-4">Stock Mínimo</td>
                                        <td class="py-2 px-4 font-mono text-center">{{ $articulo->stock_minimo }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-4">Precio Compra</td>
                                        <td class="py-2 px-4 font-mono text-center">
                                            {{ $articulo->moneda_compra == 1 ? 'S/' : '$' }}{{ number_format($articulo->precio_compra, 2) }}
                                        </td>
                                        <td class="py-2 px-4">Precio Venta</td>
                                        <td class="py-2 px-4 font-mono text-center">
                                            {{ $articulo->moneda_venta == 1 ? 'S/' : '$' }}{{ number_format($articulo->precio_venta, 2) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-4">Estado</td>
                                        <td class="py-2 px-4 text-center">
                                            @if ($articulo->estado == 1)
                                                <span class="badge bg-warning">Activo</span>
                                            @else
                                                <span class="badge bg-danger">Inactivo</span>
                                            @endif
                                        </td>
                                        <td class="py-2 px-4">Fecha Ingreso</td>
                                        <td class="py-2 px-4 text-center">
                                            {{ date('d/m/Y', strtotime($articulo->fecha_ingreso)) }}
                                        </td>
                                    </tr>
                                </tbody>

                            </table>
                        </div>


                        <!-- Sección de relaciones -->
                        <div class="overflow-x-auto mb-4">
                            <table class="min-w-full">
                                <thead class="text-[#e2a03f] uppercase text-sm">
                                    <tr>
                                        <th class="py-2 px-4 text-center">Categoría</th>
                                        <th class="py-2 px-4 text-center">Modelo</th>
                                        <th class="py-2 px-4 text-center">Unidad</th>
                                        <th class="py-2 px-4 text-center">Área</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="py-2 px-4 text-center">
                                            {{ $articulo->tipoArticulo->nombre ?? 'N/A' }}
                                        </td>
                                        <td class="py-2 px-4 text-center">{{ $articulo->modelo->nombre ?? 'N/A' }}</td>
                                        <td class="py-2 px-4 text-center">{{ $articulo->unidad->nombre ?? 'N/A' }}</td>
                                        <td class="py-2 px-4 text-center">{{ $articulo->tipoArea->nombre ?? 'N/A' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>

                <!-- Sección de foto del producto (derecha) -->
                @if ($articulo->foto)
                    <div class="rounded-lg p-4 w-full md:w-1/3" style="border: 1px solid #e2a03f;">
                        <h2 class="font-bold mb-4 text-lg text-center">-IMAGEN DEL DEL PRODUCTO-</h2>
                        <div class="flex justify-center">
                            <img src="data:image/png;base64,{{ base64_encode($articulo->foto) }}"
                                alt="Imagen del producto" class="max-h-96 rounded-lg shadow object-contain">
                        </div>
                    </div>
                @endif
            </div>


            <!-- Modal de impresión -->
            <template x-if="isOpen">
                <div class="fixed inset-0 bg-[black]/60 z-[999] overflow-y-auto">
                    <div class="flex items-start justify-center min-h-screen px-4" @click.self="closeModal()">
                        <div x-show="isOpen" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-4"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-4"
                            class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-xs bg-white shadow-lg">
                            <!-- Header -->
                            <div class="flex items-center justify-between px-5 py-3 border-b">
                                <div class="font-bold text-base text-[#1f2937]">Imprimir código</div>
                                <button type="button" @click="closeModal()">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Body -->
                            <div class="p-5 space-y-4">
                                <div>
                                    <label class="block text-sm text-gray-600 mb-1">Código</label>
                                    <p x-text="currentCode" class="font-mono text-base font-bold"></p>
                                </div>
                                <div>
                                    <label for="printQuantity" class="block text-sm text-gray-600 mb-1">Cantidad a
                                        imprimir</label>
                                    <input type="number" x-model="quantity" id="printQuantity" min="1"
                                        max="100"
                                        class="form-input w-full text-sm p-2 border-gray-300 rounded-md">
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="flex justify-end items-center p-4 border-t">
                                <button type="button" class="btn btn-outline-danger"
                                    @click="closeModal()">Cancelar</button>
                                <button type="button" class="btn btn-primary ml-4"
                                    @click="printBarcode()">Imprimir</button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>



            <!-- Iframe oculto para impresión -->
            <iframe id="printFrame" style="display: none;"></iframe>
        </div>

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
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
