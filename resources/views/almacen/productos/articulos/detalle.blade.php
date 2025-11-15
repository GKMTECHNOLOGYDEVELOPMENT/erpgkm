<x-layout.default>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Contenedor principal -->
    <div x-data="modalHandler()">
        <!-- Breadcrumbs -->
        <div>
            <ul class="flex space-x-2 rtl:space-x-reverse mb-4">
                <li>
                    <a href="{{ route('articulos.index') }}" class="text-primary hover:underline flex items-center gap-2">
                        <i class="fas fa-cube w-4 h-4"></i>
                        Artículos
                    </a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1 flex items-center gap-2">
                    <i class="fas fa-info-circle w-4 h-4 text-gray-500"></i>
                    <span>Detalle Artículo</span>
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

                            <div class="bg-panel rounded-lg p-4 border border-blue-100 mb-4 w-full">
                                <p class="font-mono text-xl font-bold text-gray-800 tracking-wider">
                                    {{ $articulo->codigo_barras }}</p>
                                @if ($articulo->foto_codigobarras)
                                    <div class="mt-4 p-3 bg-panel rounded border">
                                        <img src="data:image/png;base64,{{ base64_encode($articulo->foto_codigobarras) }}"
                                            alt="Código de barras" class="h-16 mx-auto">
                                    </div>
                                @endif
                            </div>

                            @if(\App\Helpers\PermisoHelper::tienePermiso('IMPRIMIR CODIGO PRODUCTO'))
                            <button type="button"
                                @click="openModal('{{ $articulo->codigo_barras }}', '{{ $articulo->foto_codigobarras ? base64_encode($articulo->foto_codigobarras) : '' }}')"
                                class="btn btn-primary w-full text-base py-3 gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z" />
                                </svg>
                                IMPRIMIR CÓDIGO
                            </button>
                            @endif
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

                            <div class="panel rounded-lg p-4 border border-purple-100 mb-4 w-full">
                                <p class="font-mono text-xl font-bold text-gray-800 tracking-wider">{{ $articulo->sku }}
                                </p>
                                @if ($articulo->fotosku)
                                    <div class="mt-4 p-3 rounded border">
                                        <img src="data:image/png;base64,{{ base64_encode($articulo->fotosku) }}"
                                            alt="SKU" class="h-16 mx-auto">
                                    </div>
                                @endif
                            </div>
                            @if(\App\Helpers\PermisoHelper::tienePermiso('IMPRIMIR SKU PRODUCTO'))
                            <button type="button" @click="openModal('{{ $articulo->sku }}')"
                                class="btn btn-secondary w-full text-base py-3 gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z" />
                                </svg>
                                IMPRIMIR SKU
                            </button>
                            @endif
                        </div>
                    </div>
                </div>


                <!-- Sección de información principal -->
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                    <!-- Información del producto -->
                    <div class="xl:col-span-2">
                        <div class="bg-panel rounded-lg border border-gray-200 shadow-sm overflow-hidden">
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

                <!-- Sección de garantía y proveedor -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b">
                        <h2 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                            <svg class="w-6 h-6 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            INFORMACIÓN ADICIONAL
                        </h2>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div class="text-center">
                                <div
                                    class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="text-sm font-medium text-gray-600 mb-1">Garantía de Fábrica</div>
                                <div class="text-lg font-semibold text-gray-800">
                                    {{ $articulo->garantia_fabrica ?? '--' }}
                                </div>
                            </div>

                            <div class="text-center">
                                <div
                                    class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10 2a5 5 0 00-5 5v2a2 2 0 00-2 2v5a2 2 0 002 2h10a2 2 0 002-2v-5a2 2 0 00-2-2H7V7a3 3 0 015.905-.75 1 1 0 001.937-.5A5.002 5.002 0 0010 2z" />
                                    </svg>
                                </div>
                                <div class="text-sm font-medium text-gray-600 mb-1">Unidad de Tiempo</div>
                                <div class="text-lg font-semibold text-gray-800">
                                    @switch($articulo->unidad_tiempo_garantia)
                                        @case('dias')
                                            Días
                                        @break

                                        @case('semanas')
                                            Semanas
                                        @break

                                        @case('meses')
                                            Meses
                                        @break

                                        @case('años')
                                            Años
                                        @break

                                        @default
                                            {{ $articulo->unidad_tiempo_garantia ?? 'N/A' }}
                                    @endswitch
                                </div>

                            </div>

                            <div class="text-center">
                                <div
                                    class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                                    </svg>
                                </div>
                                <div class="text-sm font-medium text-gray-600 mb-1">Proveedor</div>
                                <div class="text-lg font-semibold text-gray-800">
                                    @if ($articulo->proveedor)
                                        {{ $articulo->proveedor->nombre }}
                                    @else
                                        <span class="text-gray-400">No asignado</span>
                                    @endif
                                </div>
                            </div>

                            <div class="text-center">
                                <div
                                    class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="text-sm font-medium text-gray-600 mb-1">Documento Proveedor</div>
                                <div class="text-lg font-semibold text-gray-800">
                                    @if ($articulo->proveedor)
                                        <span class="font-mono">{{ $articulo->proveedor->numeroDocumento }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal mejorado -->
        <template x-if="isOpen">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] flex items-center justify-center p-4">
                <div x-show="isOpen" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z" />
                                </svg>
                                Imprimir código
                            </h3>
                            <button @click="closeModal()" class="text-white/80 hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Código a imprimir</label>
                            <div class="bg-gray-50 rounded-lg p-3 border">
                                <p x-text="currentCode" class="font-mono text-lg font-bold text-gray-800 text-center">
                                </p>
                            </div>
                        </div>
                        <div>
                            <label for="printQuantity" class="block text-sm font-medium text-gray-700 mb-2">
                                Cantidad de copias
                            </label>
                            <input type="number" x-model="quantity" id="printQuantity" min="1"
                                max="100"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 bg-gray-50 border-t flex justify-end space-x-3">
                        <button @click="closeModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancelar
                        </button>
                        <button @click="printBarcode()"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-md">
                            Imprimir
                        </button>
                    </div>
                </div>
            </div>
        </template>

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
