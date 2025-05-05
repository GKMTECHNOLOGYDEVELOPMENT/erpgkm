<x-layout.default>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{ route('articulos.index') }}" class="text-primary hover:underline">Artículos</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Detalle Artículo</span>
            </li>
        </ul>
    </div>
    
    <div class="panel mt-6 p-5 max-w-4xl mx-auto">
        <h2 class="text-xl font-bold mb-5">DETALLE ARTÍCULO</h2>

        <!-- Información básica -->
        <div class="mb-6 grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-500">Código</label>
                <p class="mt-1 font-semibold">{{ $articulo->codigo_barras }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Nombre</label>
                <p class="mt-1 font-semibold">{{ $articulo->nombre }}</p>
            </div>
        </div>

        <!-- Sección de códigos -->
        <div class="border rounded-lg p-6">
            <h3 class="text-lg font-bold mb-4">Código de barras y SKU</h3>
            
            <!-- Código de barras 1 -->
            <div class="mb-4 flex items-center justify-between border-b pb-3">
                <span class="text-sm font-medium text-gray-500">Código de barras</span>
                <div class="text-right">
                    <p class="font-mono font-bold">{{ $articulo->codigo_barras }}</p>
                    @if($articulo->foto_codigobarras)
                        <img src="data:image/png;base64,{{ base64_encode($articulo->foto_codigobarras) }}" 
                             alt="Código de barras" 
                             class="h-16 mt-2 mx-auto">
                    @endif
                </div>
            </div>

            <!-- Código de barras 2 -->
            <div class="mb-4 flex items-center justify-between border-b pb-3">
                <span class="text-sm font-medium text-gray-500">Código de barras</span>
                <p class="font-mono font-bold">{{ $articulo->codigo_barras }}</p>
            </div>

            <!-- IMPRIMIR 1 -->
            <div class="mb-4 flex items-center justify-between border-b pb-3">
                <span class="text-sm font-medium text-gray-500">IMPRIMIR</span>
                <p class="font-mono font-bold">{{ $articulo->sku }}</p>
            </div>

            <!-- SKU -->
            <div class="mb-4 flex items-center justify-between border-b pb-3">
                <span class="text-sm font-medium text-gray-500">SKU</span>
                <div class="text-right">
                    <p class="font-mono font-bold">{{ $articulo->sku }}</p>
                    @if($articulo->fotosku)
                        <img src="data:image/png;base64,{{ base64_encode($articulo->fotosku) }}" 
                             alt="SKU" 
                             class="h-16 mt-2 mx-auto">
                    @endif
                </div>
            </div>

            <!-- IMPRIMIR 2 -->
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-gray-500">IMPRIMIR</span>
                <p class="font-mono font-bold">{{ $articulo->sku }}</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
</x-layout.default>