<x-layout.default>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    
    <!-- Contenedor principal -->
    <div x-data="modalHandler()">
        <!-- Breadcrumbs -->
        <div class="mb-6">
            <ul class="flex space-x-3 rtl:space-x-reverse text-base">
                <li>
                    <a href="{{ route('articulos.index') }}" class="text-primary hover:underline">Repuestos</a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-2 rtl:before:ml-2">
                    <span>Detalle Repuesto</span>
                </li>
            </ul>
        </div>
        
        <!-- Panel principal -->
        <div class="panel mt-6 p-6 max-w-4xl mx-auto">
           <div class="text-center mb-6">
                <h1 class="text-xl font-bold uppercase">
                    {{ $articulo->codigo_repuesto }} /
                    @if($modelos->isNotEmpty())
                        {{ $modelos->first()->categoria->nombre ?? 'Sin categoría' }} /
                        {{ $modelos->first()->nombre ?? 'Sin modelo' }}
                    @else
                        Sin modelo / Sin categoría
                    @endif
                </h1>
            </div>


          <!-- Sección de código de repuesto -->
<div class="mb-6">
    <div class="border rounded-lg p-4 max-w-md mx-auto">
        <div class="space-y-3">
          @php
    $textDisplay = $articulo->codigo_repuesto;
    $marcaNombre = '';
    $categoriaNombre = '';
    $modeloNombre = '';

    if ($articulo->modelos->isNotEmpty()) {
        $modelo = $articulo->modelos->first();
        $modeloNombre = $modelo->nombre ?? 'Sin modelo';
        $categoriaNombre = $modelo->categoria->nombre ?? 'Sin categoría';
        $marcaNombre = $modelo->marca->nombre ?? 'Sin marca';
        $textDisplay = "$marcaNombre / $categoriaNombre / $modeloNombre";
    }
@endphp

<div class="text-center">
    {{-- NOMBRE DE LA MARCA --}}
    @if($marcaNombre)
        <h3 class="text-lg font-bold text-gray-700 uppercase mb-2">{{ $marcaNombre }}</h3>
    @endif

    {{-- CÓDIGO DE BARRAS (x2 como pediste) --}}
    @if($articulo->{'br-codigo-repuesto'})
        <div class="flex flex-col items-center gap-2 mb-2">
            <img src="data:image/png;base64,{{ base64_encode($articulo->{'br-codigo-repuesto'}) }}" 
                 alt="Código de barras del repuesto" 
                 class="h-16 mx-auto">
        </div>
    @endif

    {{-- CÓDIGO DE REPUESTO --}}
    <p class="font-mono text-base font-bold mb-1">
        {{ $articulo->codigo_repuesto }}
    </p>

    {{-- MARCA / CATEGORÍA / MODELO --}}
    <p class="text-sm text-gray-600">
        {{ $textDisplay }}
    </p>
</div>


            <div class="border-t pt-3">
                <button 
    type="button" 
    @click="openModal(
        '{{ $articulo->codigo_repuesto }}',
        '{{ base64_encode($articulo->{'br-codigo-repuesto'}) }}',
        '{{ $articulo->modelos->first()->marca->nombre ?? '' }}',
        '{{ $articulo->modelos->first()->categoria->nombre ?? '' }}',
        '{{ $articulo->modelos->first()->nombre ?? '' }}'
    )" 
    class="w-full flex justify-between items-center text-base text-gray-600 hover:text-primary py-2 px-1"
>
    <span>IMPRIMIR</span>
    <span class="font-mono font-bold">{{ $articulo->codigo_repuesto }}</span>
</button>
            </div>
        </div>
    </div>
</div>


            <!-- Contenedor principal con foto y detalles lado a lado -->
            <div class="mb-6 flex flex-col md:flex-row gap-4">
                <!-- Sección de información del producto (izquierda) -->
                <div class="bg-white p-4 rounded-lg border border-gray-200 flex-1">
                    <h2 class="font-bold mb-4 text-lg text-gray-700 border-b pb-2">Detalles del Repuesto</h2>
                    
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
                                <tr>
                                    <td class="py-2 px-4 border-b">Código de Barras</td>
                                    <td class="py-2 px-4 border-b font-mono">{{ $articulo->codigo_barras }}</td>
                                    <td class="py-2 px-4 border-b">SKU</td>
                                    <td class="py-2 px-4 border-b font-mono">{{ $articulo->sku }}</td>
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
                    <h2 class="font-bold mb-4 text-lg text-gray-700 border-b pb-2">Imagen del Repuesto</h2>
                    <div class="flex justify-center">
                        <img src="data:image/png;base64,{{ base64_encode($articulo->foto) }}" 
                             alt="Imagen del repuesto" 
                             class="max-h-96 rounded-lg shadow object-contain">
                    </div>
                </div>
                @endif
            </div>

            <!-- Ficha técnica si existe -->
            @if($articulo->ficha_tecnica)
                <div class="mb-6 bg-white p-4 rounded-lg border border-gray-200">
                    <h2 class="font-bold mb-4 text-lg text-gray-700 border-b pb-2">Ficha Técnica</h2>
                    <div class="text-center">
                        <a href="{{ asset('storage/fichas/' . $articulo->ficha_tecnica) }}" 
                        target="_blank" 
                        class="text-primary hover:underline inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd" />
                            </svg>
                            Descargar Ficha Técnica
                        </a>
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
        currentMarca: '',
        currentCategoria: '',
        currentModelo: '',
        quantity: 1,

        openModal(code, barcodeImage = '', marca = '', categoria = '', modelo = '') {
            this.currentCode = code;
            this.currentBarcodeImage = barcodeImage;
            this.currentMarca = marca;
            this.currentCategoria = categoria;
            this.currentModelo = modelo;
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

            let printContent = `<!DOCTYPE html>
                <html>
                <head>
                    <title>Impresión de Código de Barras</title>
                    <style>
                        @page {
                            size: 80mm 50mm;
                            margin: 0;
                        }
                        body {
                            margin: 0;
                            padding: 2mm;
                            font-family: Arial, sans-serif;
                            font-size: 12px;
                        }
                        .label {
                            width: 100%;
                            height: 100%;
                            border: 1px dashed #ccc;
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            justify-content: center;
                            text-align: center;
                        }
                        .brand {
                            font-weight: bold;
                            font-size: 18px;
                            margin-bottom: 3mm;
                            text-transform: uppercase;
                        }
                        .barcode-img {
                            height: 25mm;
                            margin: 1mm 0;
                        }
                        .code {
                            font-size: 14px;
                            font-weight: bold;
                            margin: 1mm 0;
                        }
                        .details {
                            font-size: 12px;
                            margin-top: 2mm;
                        }
                    </style>
                </head>
                <body>`;

            for (let i = 0; i < this.quantity; i++) {
                printContent += `
                    <div class="label">
                        <div class="brand">${this.currentMarca}</div>
                        <img src="data:image/png;base64,${this.currentBarcodeImage}" class="barcode-img">
                        <div class="code">${this.currentCode}</div>
                        <div class="details">${this.currentMarca} / ${this.currentCategoria} / ${this.currentModelo}</div>
                    </div>
                    <div style="page-break-after: always;"></div>`;
            }

            printContent += `
                <script>
                    window.onload = function() {
                        setTimeout(() => { 
                            window.print(); 
                            window.close(); 
                        }, 200);
                    };
                <\/script>
                </body>
                </html>`;

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