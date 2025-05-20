<x-layout.default>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    
    <!-- Contenedor principal -->
    <div x-data="modalHandler()">
        <!-- Breadcrumbs -->
        <div class="mb-6">
            <ul class="flex space-x-3 rtl:space-x-reverse text-base">
                <li>
                    <a href="{{ route('articulos.index') }}" class="text-primary hover:underline">Artículos</a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-2 rtl:before:ml-2">
                    <span>Detalle Artículo</span>
                </li>
            </ul>
        </div>
        
        <!-- Panel principal -->
        <div class="panel mt-6 p-6 max-w-4xl mx-auto">
            <!-- Encabezado -->
            <div class="text-center mb-6">
                <h1 class="text-xl font-bold uppercase">{{ $articulo->nombre }}</h1>
            </div>


               <!-- Sección de códigos -->
               <div class="mb-6">
                <h2 class="font-bold mb-4 text-lg text-gray-700 border-b pb-2">Código de barras y SKU</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Columna izquierda - Código de barras -->
                    <div class="border rounded-lg p-4">
                        <div class="space-y-3">
                            <div class="text-center">
                                <h3 class="text-base font-semibold text-gray-600 mb-2">Código de barras</h3>
                                <p class="font-mono text-lg font-bold">{{ $articulo->codigo_barras }}</p>
                                @if($articulo->foto_codigobarras)
                                    <div class="mt-3">
                                        <img src="data:image/png;base64,{{ base64_encode($articulo->foto_codigobarras) }}" 
                                             alt="Código de barras" 
                                             class="h-20 mx-auto">
                                    </div>
                                @endif
                            </div>
                            
                            <div class="border-t pt-3">
                                <button type="button" 
                                        @click="openModal('{{ $articulo->codigo_barras }}', '{{ $articulo->foto_codigobarras ? base64_encode($articulo->foto_codigobarras) : '' }}')" 
                                        class="w-full flex justify-between items-center text-base text-gray-600 hover:text-primary py-2 px-1">
                                    <span>IMPRIMIR</span>
                                    <span class="font-mono font-bold">{{ $articulo->codigo_barras }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Columna derecha - SKU -->
                    <div class="border rounded-lg p-4">
                        <div class="space-y-3">
                            <div class="text-center">
                                <h3 class="text-base font-semibold text-gray-600 mb-2">SKU</h3>
                                <p class="font-mono text-lg font-bold">{{ $articulo->sku }}</p>
                                @if($articulo->fotosku)
                                    <div class="mt-3">
                                        <img src="data:image/png;base64,{{ base64_encode($articulo->fotosku) }}" 
                                             alt="SKU" 
                                             class="h-20 mx-auto">
                                    </div>
                                @endif
                            </div>
                            
                            <div class="border-t pt-3">
                                <button type="button" 
                                        @click="openModal('{{ $articulo->sku }}')" 
                                        class="w-full flex justify-between items-center text-base text-gray-600 hover:text-primary py-2 px-1">
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
    <div class="bg-white p-4 rounded-lg border border-gray-200 flex-1">
        <h2 class="font-bold mb-4 text-lg text-gray-700 border-b pb-2">1.2.3 - Detalles del Producto</h2>
        
        <!-- Tabla principal de información -->
        <div class="overflow-x-auto mb-4">
            <table class="min-w-full border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-2 px-4 border-b text-left">Descripción</th>
                        <th class="py-2 px-4 border-b text-left">Valor</th>
                        <th class="py-2 px-4 border-b text-left">Descripción</th>
                        <th class="py-2 px-4 border-b text-left">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="py-2 px-4 border-b">ID Artículo</td>
                        <td class="py-2 px-4 border-b font-mono">{{ $articulo->idArticulos }}</td>
                        <td class="py-2 px-4 border-b">Stock Total</td>
                        <td class="py-2 px-4 border-b font-mono">{{ $articulo->stock_total }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 px-4 border-b">Stock Mínimo</td>
                        <td class="py-2 px-4 border-b font-mono">{{ $articulo->stock_minimo }}</td>
                        <td class="py-2 px-4 border-b">Peso</td>
                        <td class="py-2 px-4 border-b font-mono">{{ $articulo->peso }} kg</td>
                    </tr>
                    <tr>
                        <td class="py-2 px-4 border-b">Precio Compra</td>
                        <td class="py-2 px-4 border-b font-mono">{{ $articulo->moneda_compra == 1 ? 'S/' : '$' }} {{ number_format($articulo->precio_compra, 2) }}</td>
                        <td class="py-2 px-4 border-b">Precio Venta</td>
                        <td class="py-2 px-4 border-b font-mono">{{ $articulo->moneda_venta == 1 ? 'S/' : '$' }} {{ number_format($articulo->precio_venta, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 px-4 border-b">Estado</td>
                        <td class="py-2 px-4 border-b">{{ $articulo->estado == 1 ? 'Activo' : 'Inactivo' }}</td>
                        <td class="py-2 px-4 border-b">Fecha Ingreso</td>
                        <td class="py-2 px-4 border-b">{{ date('d/m/Y', strtotime($articulo->fecha_ingreso)) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Sección de relaciones -->
        <div class="overflow-x-auto mb-4">
            <table class="min-w-full border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-2 px-4 border-b text-left">Categoría</th>
                        <th class="py-2 px-4 border-b text-left">Modelo</th>
                        <th class="py-2 px-4 border-b text-left">Unidad</th>
                        <th class="py-2 px-4 border-b text-left">Área</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $articulo->tipoArticulo->nombre ?? 'N/A' }}</td>
                        <td class="py-2 px-4 border-b">{{ $articulo->modelo->nombre ?? 'N/A' }}</td>
                        <td class="py-2 px-4 border-b">{{ $articulo->unidad->nombre ?? 'N/A' }}</td>
                        <td class="py-2 px-4 border-b">{{ $articulo->tipoArea->nombre ?? 'N/A' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Sección de foto del producto (derecha) -->
    @if($articulo->foto)
    <div class="bg-white border rounded-lg p-4 w-full md:w-1/3">
        <h2 class="font-bold mb-4 text-lg text-gray-700 border-b pb-2">Imagen del Producto</h2>
        <div class="flex justify-center">
            <img src="data:image/png;base64,{{ base64_encode($articulo->foto) }}" 
                 alt="Imagen del producto" 
                 class="max-h-96 rounded-lg shadow object-contain">
        </div>
    </div>
    @endif
</div>


        <!-- Modal de impresión -->
        <template x-if="isOpen">
            <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-6">
                <div class="bg-white rounded-lg shadow-lg w-full max-w-xs" @click.stop>
                    <div class="px-4 py-3 border-b">
                        <h3 class="font-bold text-base">Imprimir código</h3>
                    </div>
                    <div class="p-4">
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Código</label>
                                <p x-text="currentCode" class="font-mono text-base font-bold"></p>
                            </div>
                            
                            <div>
                                <label for="printQuantity" class="block text-sm text-gray-600 mb-1">Cantidad a imprimir</label>
                                <input type="number" 
                                       x-model="quantity" 
                                       id="printQuantity" 
                                       min="1" 
                                       max="100"
                                       class="form-input w-full text-sm p-2 border-gray-300 rounded-md">
                            </div>
                        </div>
                        
                        <div class="flex justify-end items-center mt-6 space-x-2">
                            <button type="button" class="text-sm px-4 py-1.5 border border-gray-300 rounded-md hover:bg-gray-50" @click="closeModal()">CANCELAR</button>
                            <button type="button" class="text-sm px-4 py-1.5 border border-gray-300 rounded-md hover:bg-gray-50" @click="printBarcode()">IMPRIMIR</button>
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
            const frameDoc = printFrame.contentWindow || printFrame.contentDocument;
            if (frameDoc.document) {
                frameDoc.document.open();
                frameDoc.document.write(printContent);
                frameDoc.document.close();
            } else {
                printFrame.src = 'data:text/html;charset=utf-8,' + encodeURIComponent(printContent);
            }

            this.closeModal();
        }
    }));
});
</script>
</x-layout.default>