<x-layout.default>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="container mx-auto px-4 max-w-7xl">
            <!-- Header Principal -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 border border-gray-200">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-16 h-16 bg-blue-600 rounded-xl flex items-center justify-center shadow-md">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2">
                                    Orden #<span class="text-blue-600">{{ $solicitud->codigo ?? 'N/A' }}</span>
                                </h1>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span
                                        class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold border border-blue-200">
                                        {{ ucfirst($solicitud->estado ?? 'pendiente') }}
                                    </span>
                                    <span
                                        class="px-4 py-2 bg-purple-100 text-purple-800 rounded-full text-sm font-semibold border border-purple-200">
                                        Solicitud de Artículo
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3 mt-4 lg:mt-0">
                        <a href="{{ route('solicitudarticulo.index') }}"
                            class="flex items-center px-5 py-2.5 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-all duration-200 shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Volver
                        </a>
                        @if (($solicitud->estado ?? '') != 'completada')
                            <a href="{{ route('solicitudarticulo.edit', $solicitud->idsolicitudesordenes) }}"
                                class="flex items-center px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Editar
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Columna Principal -->
                <div class="xl:col-span-2 space-y-6">
                    <!-- Artículos Solicitados -->
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center shadow-sm">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900">ARTÍCULOS SOLICITADOS</h2>
                                    <p class="text-gray-600 text-sm">Lista completa de artículos requeridos</p>
                                </div>
                            </div>
                            <div class="text-right bg-blue-50 rounded-xl px-4 py-3 border border-blue-200">
                                <p class="text-xs text-blue-700 font-semibold uppercase tracking-wide">Total</p>
                                <p class="text-2xl font-bold text-blue-800">{{ $articulos->count() }}</p>
                            </div>
                        </div>

                        <div class="overflow-hidden rounded-xl border border-gray-200">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            Código</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            Artículo</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            Tipo</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            Cantidad</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            Estado</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($articulos as $articulo)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="space-y-1">
                                                    <span class="font-mono font-semibold text-blue-700 block text-sm">
                                                        {{ $articulo->codigo_repuesto ?? ($articulo->codigo_barras ?? 'N/A') }}
                                                    </span>
                                                    @if ($articulo->codigo_barras && $articulo->codigo_repuesto)
                                                        <span
                                                            class="text-xs text-gray-500 bg-gray-100 rounded px-2 py-1 inline-block">
                                                            Barras: {{ $articulo->codigo_barras }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <p class="font-semibold text-gray-900 text-sm">
                                                    {{ $articulo->nombre_articulo ?? 'Artículo no especificado' }}</p>
                                                @if ($articulo->precio_compra)
                                                    <p class="text-xs text-green-600 font-medium mt-1">
                                                        ${{ number_format($articulo->precio_compra, 2) }}
                                                    </p>
                                                @endif
                                                @if ($articulo->descripcion)
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        {{ $articulo->descripcion }}
                                                    </p>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <span
                                                    class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">
                                                    {{ $articulo->tipo_articulo ?? 'General' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span
                                                    class="text-xl font-bold text-gray-900 bg-gray-100 rounded-lg px-3 py-1 inline-block">
                                                    {{ $articulo->cantidad ?? 0 }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                @php
                                                    $estadoClase =
                                                        [
                                                            '0' =>
                                                                'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                                            '1' =>
                                                                'bg-green-100 text-green-800 border border-green-200',
                                                            '2' => 'bg-red-100 text-red-800 border border-red-200',
                                                        ][$articulo->estado ?? 0] ??
                                                        'bg-gray-100 text-gray-800 border border-gray-200';

                                                    $estadoTexto =
                                                        [
                                                            '0' => 'Pendiente',
                                                            '1' => 'Completado',
                                                            '2' => 'Rechazado',
                                                        ][$articulo->estado ?? 0] ?? 'Desconocido';
                                                @endphp
                                                <span
                                                    class="px-3 py-1 {{ $estadoClase }} rounded-full text-xs font-semibold">
                                                    {{ $estadoTexto }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-12 text-center">
                                                <svg class="mx-auto h-16 w-16 text-gray-300" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1"
                                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                </svg>
                                                <p class="mt-4 text-lg font-semibold text-gray-500">No hay artículos
                                                    registrados</p>
                                                <p class="mt-1 text-gray-400 text-sm">Agrega artículos para comenzar
                                                </p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($articulos->count() > 0)
                            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-blue-50 rounded-xl p-4 text-center border border-blue-200">
                                    <p class="text-xs text-blue-700 font-semibold uppercase tracking-wide mb-1">Total
                                        Artículos</p>
                                    <p class="text-2xl font-bold text-blue-800">{{ $articulos->count() }}</p>
                                </div>
                                <div class="bg-green-50 rounded-xl p-4 text-center border border-green-200">
                                    <p class="text-xs text-green-700 font-semibold uppercase tracking-wide mb-1">Total
                                        Cantidad</p>
                                    <p class="text-2xl font-bold text-green-800">{{ $articulos->sum('cantidad') }}</p>
                                </div>
                                <div class="bg-purple-50 rounded-xl p-4 text-center border border-purple-200">
                                    <p class="text-xs text-purple-700 font-semibold uppercase tracking-wide mb-1">Tipos
                                        Diferentes</p>
                                    <p class="text-2xl font-bold text-purple-800">
                                        {{ $articulos->unique('tipo_articulo')->count() }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Información de la Orden -->
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">INFORMACIÓN DE LA ORDEN</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                    <label
                                        class="block text-xs font-semibold text-blue-700 mb-2 uppercase tracking-wide">Código
                                        Orden</label>
                                    <p class="text-lg font-bold text-blue-800">{{ $solicitud->codigo ?? 'N/A' }}</p>
                                </div>
                                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                                    <label
                                        class="block text-xs font-semibold text-green-700 mb-2 uppercase tracking-wide">Tipo
                                        de Servicio</label>
                                    <p class="text-lg font-bold text-green-800">
                                        {{ $solicitud->tiposervicio ?? 'No especificado' }}</p>
                                </div>
                                <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                                    <label
                                        class="block text-xs font-semibold text-purple-700 mb-2 uppercase tracking-wide">Fecha
                                        de Creación</label>
                                    <p class="text-lg font-bold text-purple-800">
                                        @if ($solicitud->fechacreacion)
                                            {{ \Carbon\Carbon::parse($solicitud->fechacreacion)->format('d M Y, h:i A') }}
                                        @else
                                            No especificada
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="bg-orange-50 rounded-lg p-4 border border-orange-200">
                                    <label
                                        class="block text-xs font-semibold text-orange-700 mb-2 uppercase tracking-wide">Fecha
                                        Requerida</label>
                                    <p class="text-lg font-bold text-orange-800">
                                        @if ($solicitud->fecharequerida)
                                            {{ \Carbon\Carbon::parse($solicitud->fecharequerida)->format('d M Y, h:i A') }}
                                        @else
                                            No definida
                                        @endif
                                    </p>
                                </div>
                                <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                                    <label
                                        class="block text-xs font-semibold text-red-700 mb-2 uppercase tracking-wide">Urgencia</label>
                                    @php
                                        $urgenciaClase =
                                            [
                                                'baja' => 'bg-green-100 text-green-800 border border-green-200',
                                                'media' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                                'alta' => 'bg-red-100 text-red-800 border border-red-200',
                                            ][$solicitud->urgencia ?? 'baja'] ??
                                            'bg-gray-100 text-gray-800 border border-gray-200';

                                        $urgenciaTexto =
                                            [
                                                'baja' => 'Baja',
                                                'media' => 'Media',
                                                'alta' => 'Alta',
                                            ][$solicitud->urgencia ?? 'baja'] ?? 'No especificado';
                                    @endphp
                                    <span class="px-3 py-1 {{ $urgenciaClase }} rounded-full text-sm font-semibold">
                                        {{ $urgenciaTexto }}
                                    </span>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <label
                                        class="block text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">Productos
                                        Únicos</label>
                                    <p class="text-lg font-bold text-gray-900">
                                        {{ $solicitud->productos_unicos ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Información del Solicitante -->
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">SOLICITANTE</h3>
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">
                                    {{ $solicitud->nombre_solicitante ?? 'No especificado' }}</p>
                                <p class="text-sm text-gray-600">
                                    {{ $solicitud->nombre_area ?? 'Área no especificada' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Resumen de la Orden -->
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">RESUMEN DE LA ORDEN</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-600">Estado:</span>
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">
                                    {{ ucfirst($solicitud->estado ?? 'pendiente') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-600">Total Artículos:</span>
                                <span class="font-semibold text-gray-900">{{ $articulos->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-600">Cantidad Total:</span>
                                <span class="font-semibold text-gray-900">{{ $articulos->sum('cantidad') }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm text-gray-600">Urgencia:</span>
                                @php
                                    $urgenciaColor = [
                                        'baja' => 'text-green-600 bg-green-100',
                                        'media' => 'text-yellow-600 bg-yellow-100',
                                        'alta' => 'text-red-600 bg-red-100',
                                    ][$solicitud->urgencia ?? 'baja'] ?? 'text-gray-600 bg-gray-100';
                                @endphp
                                <span class="px-2 py-1 {{ $urgenciaColor }} rounded text-xs font-semibold">
                                    {{ ucfirst($solicitud->urgencia ?? 'baja') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    @if (!empty($solicitud->observaciones))
                        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Observaciones</h3>
                            <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                                <p class="text-sm text-yellow-800 leading-relaxed">
                                    {{ $solicitud->observaciones }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Tiempos -->
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Tiempos</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wide">Fecha
                                    Requerida</label>
                                <p class="text-sm font-semibold text-gray-900">
                                    @if ($solicitud->fecharequerida)
                                        {{ \Carbon\Carbon::parse($solicitud->fecharequerida)->format('d M Y, h:i A') }}
                                    @else
                                        <span class="text-red-500">No definida</span>
                                    @endif
                                </p>
                            </div>
                            @php
                                $fechaRequerida = $solicitud->fecharequerida
                                    ? \Carbon\Carbon::parse($solicitud->fecharequerida)
                                    : now();
                                $diasRestantes = now()->diffInDays($fechaRequerida, false);
                            @endphp
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wide">Días
                                    Restantes</label>
                                <p
                                    class="text-2xl font-bold {{ $diasRestantes <= 0 ? 'text-red-600' : ($diasRestantes <= 2 ? 'text-orange-600' : 'text-green-600') }}">
                                    {{ $diasRestantes > 0 ? $diasRestantes . ' días' : 'Vencida' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout.default>