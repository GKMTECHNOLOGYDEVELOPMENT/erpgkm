<x-layout.default>
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Header mejorado -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-6">
            <div class="w-full">
                <div class="flex items-center gap-4 mb-6 p-6 panel rounded-2xl shadow-sm border border-gray-100">
                    <!-- Icono principal -->
                    <div class="p-3 bg-primary rounded-xl shadow-lg flex-shrink-0">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>

                    <!-- Contenido principal -->
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
                            <!-- Título y información -->
                            <div class="flex-1">
                                <h1 class="text-3xl font-bold text-black mb-3">Detalles de Solicitud</h1>
                                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                    <!-- Código -->
                                    <div
                                        class="flex items-center gap-2 bg-gray-50 px-4 py-2 rounded-lg border border-gray-200">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-700">Código:</span>
                                        <span
                                            class="text-sm font-bold text-indigo-600">{{ $solicitud->codigoSolicitud }}</span>
                                    </div>

                                    <!-- Estado -->
                                    <span
                                        class="px-5 py-2 text-sm font-bold rounded-full shadow-sm
                            @if ($solicitud->estado == 'completada') bg-gradient-to-r from-emerald-500 to-emerald-600 text-white
                            @elseif($solicitud->nivelUrgencia == 3) bg-gradient-to-r from-rose-500 to-rose-600 text-white
                            @elseif($solicitud->nivelUrgencia == 2) bg-gradient-to-r from-amber-500 to-amber-600 text-white
                            @else bg-gradient-to-r from-indigo-500 to-indigo-600 text-white @endif">
                                        {{ ucfirst($solicitud->estado) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Fecha si está disponible -->
                            @if ($solicitud->fechasolicitud)
                                <div
                                    class="flex items-center gap-2 text-sm text-gray-500 bg-white px-3 py-2 rounded-lg border border-gray-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span>{{ \Carbon\Carbon::parse($solicitud->fechasolicitud)->format('d M Y') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap gap-3 w-full lg:w-auto">
                <a href="{{ route('solicitudarticulo.index') }}"
                    class="flex items-center justify-center px-6 py-3 bg-white border-2 border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-300 shadow-md hover:shadow-xl hover:-translate-y-1">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver al Listado
                </a>
                @if ($solicitud->estado != 'completada')
                    <a href="{{ route('solicitudarticulo.edit', $solicitud->idSolicitud) }}"
                        class="flex items-center justify-center px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-xl hover:from-amber-600 hover:to-orange-600 transition-all duration-300 shadow-md hover:shadow-xl hover:-translate-y-1">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        Editar Solicitud
                    </a>
                @endif
            </div>
        </div>

        <!-- Grid de información principal -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mb-8">
            <!-- Información General -->
            <div class="xl:col-span-2">
                <div
                    class="panel rounded-3xl shadow-xl border-2 border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                    <div class="px-6 py-6 bg-black rounded-3xl">
                        <div class="flex items-center">
                            <div class="p-2.5 bg-white/20 backdrop-blur-sm rounded-xl mr-3 shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-extrabold text-white drop-shadow-lg">Información General</h2>
                        </div>
                    </div>

                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Fecha de Solicitud -->
                        <div
                            class="flex items-start space-x-4 p-5 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border-2 border-blue-100 hover:border-blue-300 transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
                            <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-md">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1.5">Fecha de
                                    Solicitud</p>
                                <p class="font-bold text-gray-900 text-lg">
                                    {{ \Carbon\Carbon::parse($solicitud->fechasolicitud)->format('d M Y') }}
                                </p>
                                <p class="text-sm text-gray-600 font-medium">
                                    {{ \Carbon\Carbon::parse($solicitud->fechasolicitud)->format('h:i A') }}
                                </p>
                            </div>
                        </div>

                        <!-- Fecha Requerida -->
                        <div
                            class="flex items-start space-x-4 p-5 bg-warning-light rounded-2xl border-2 border-amber-100 hover:border-amber-300 transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
                            <div class="p-3 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl shadow-md">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-amber-600 uppercase tracking-wider mb-1.5">Fecha
                                    Requerida</p>
                                <p class="font-bold text-gray-900 text-lg">
                                    @if ($solicitud->fecharequerida)
                                        {{ \Carbon\Carbon::parse($solicitud->fecharequerida)->format('d M Y') }}
                                    @else
                                        <span class="text-gray-400">No especificada</span>
                                    @endif
                                </p>
                                @if ($solicitud->fecharequerida)
                                    <p class="text-sm text-gray-600 font-medium">
                                        {{ \Carbon\Carbon::parse($solicitud->fecharequerida)->format('h:i A') }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Días Restantes -->
                        <div
                            class="flex items-start space-x-4 p-5 rounded-2xl border-2 transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5
                            @if ($solicitud->diasrestantes <= 2) bg-gradient-to-br from-rose-50 to-red-50 border-rose-200 hover:border-rose-400
                            @elseif($solicitud->diasrestantes <= 5) bg-gradient-to-br from-amber-50 to-yellow-50 border-amber-200 hover:border-amber-400
                            @else bg-gradient-to-br from-emerald-50 to-green-50 border-emerald-200 hover:border-emerald-400 @endif">
                            <div
                                class="p-3 rounded-xl shadow-md
                                @if ($solicitud->diasrestantes <= 2) bg-gradient-to-br from-rose-500 to-red-600
                                @elseif($solicitud->diasrestantes <= 5) bg-gradient-to-br from-amber-500 to-yellow-500
                                @else bg-success @endif">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p
                                    class="text-xs font-bold uppercase tracking-wider mb-1.5
                                    @if ($solicitud->diasrestantes <= 2) text-rose-600
                                    @elseif($solicitud->diasrestantes <= 5) text-amber-600
                                    @else text-emerald-600 @endif">
                                    Días Restantes</p>
                                <p
                                    class="font-extrabold text-4xl
                                    @if ($solicitud->diasrestantes <= 2) text-rose-600 
                                    @elseif($solicitud->diasrestantes <= 5) text-amber-600
                                    @else text-emerald-600 @endif">
                                    {{ $solicitud->diasrestantes ?? 0 }}
                                </p>
                                <p
                                    class="text-sm font-semibold
                                    @if ($solicitud->diasrestantes <= 2) text-rose-500
                                    @elseif($solicitud->diasrestantes <= 5) text-amber-500
                                    @else text-emerald-500 @endif">
                                    días restantes</p>
                            </div>
                        </div>

                        <!-- Nivel de Urgencia -->
                        <div
                            class="flex items-start space-x-4 p-5 rounded-2xl border-2 transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5
                            @if ($solicitud->nivelUrgencia == 3) bg-gradient-to-br from-rose-50 to-pink-50 border-rose-200 hover:border-rose-400
                            @elseif($solicitud->nivelUrgencia == 2) bg-gradient-to-br from-amber-50 to-orange-50 border-amber-200 hover:border-amber-400
                            @else bg-gradient-to-br from-emerald-50 to-teal-50 border-emerald-200 hover:border-emerald-400 @endif">
                            <div
                                class="p-3 rounded-xl shadow-md
                                @if ($solicitud->nivelUrgencia == 3) bg-gradient-to-br from-rose-500 to-pink-600
                                @elseif($solicitud->nivelUrgencia == 2) bg-gradient-to-br from-amber-500 to-orange-500
                                @else bg-success @endif">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p
                                    class="text-xs font-bold uppercase tracking-wider mb-1.5
                                    @if ($solicitud->nivelUrgencia == 3) text-rose-600
                                    @elseif($solicitud->nivelUrgencia == 2) text-amber-600
                                    @else text-emerald-600 @endif">
                                    Nivel de Urgencia</p>
                                <div class="flex items-center">
                                    @switch($solicitud->nivelUrgencia)
                                        @case(3)
                                            <span class="w-3 h-3 rounded-full bg-rose-500 mr-2 animate-pulse shadow-lg"></span>
                                            <span class="font-extrabold text-gray-900 text-xl">Alta</span>
                                        @break

                                        @case(2)
                                            <span class="w-3 h-3 rounded-full bg-amber-500 mr-2 shadow-lg"></span>
                                            <span class="font-extrabold text-gray-900 text-xl">Media</span>
                                        @break

                                        @case(1)
                                            <span class="w-3 h-3 rounded-full bg-emerald-500 mr-2 shadow-lg"></span>
                                            <span class="font-extrabold text-gray-900 text-xl">Baja</span>
                                        @break

                                        @default
                                            <span class="font-medium text-gray-400">No especificado</span>
                                    @endswitch
                                </div>
                            </div>
                        </div>

                        <!-- Solicitante -->
                        <div
                            class="flex items-start space-x-4 p-5 bg-secondary-light rounded-2xl border-2 border-purple-100 hover:border-purple-300 transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
                            <div class="p-3 bg-secondary rounded-xl shadow-md">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-purple-600 uppercase tracking-wider mb-1.5">
                                    Solicitante</p>
                                <p class="font-bold text-gray-900 text-lg">
                                    {{ $solicitud->solicitante->Nombre ?? 'No especificado' }}</p>
                            </div>
                        </div>

                        <!-- Encargado -->
                        <div
                            class="flex items-start space-x-4 p-5 bg-info-light rounded-2xl border-2 border-info hover:border-indigo-300 transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
                            <div class="p-3 bg-info rounded-xl shadow-md">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-info uppercase tracking-wider mb-1.5">Encargado
                                </p>
                                <p class="font-bold text-gray-900 text-lg">
                                    {{ $solicitud->encargado->Nombre ?? 'No asignado' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Comentario -->
                    @if ($solicitud->comentario)
                        <div class="px-8 pb-8">
                            <div class="p-6 bg-primary-light rounded-2xl border-2 border-blue-200 shadow-inner">
                                <div class="flex items-center mb-4">
                                    <div class="p-2 bg-primary rounded-lg mr-3 shadow-md">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                                            </path>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-bold text-primary uppercase tracking-wider">Comentario
                                        Adicional</p>
                                </div>
                                <p class="text-gray-700 whitespace-pre-line leading-relaxed font-medium">
                                    {{ $solicitud->comentario }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Panel lateral de estado y acciones -->
            <div class="space-y-6">
                <!-- Tarjeta de Estado -->
                <div
                    class="panel rounded-3xl shadow-2xl border-2 border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-3xl hover:-translate-y-1">
                    <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-white border-b">
                        <h3 class="text-xl font-extrabold text-gray-800 flex items-center">
                            <svg class="w-6 h-6 mr-3 text-indigo-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            ESTADO ACTUAL
                        </h3>
                    </div>

                    <div class="p-8">
                        <div class="text-center space-y-6">
                            <!-- Icono y progreso -->
                            <div class="relative inline-flex items-center justify-center">
                                <!-- Círculo de progreso -->
                                <div
                                    class="absolute w-32 h-32 rounded-full border-4 
                    @if ($solicitud->estado == 'completada') border-emerald-200
                    @else border-amber-200 @endif">
                                </div>

                                <!-- Icono central -->
                                <div
                                    class="w-24 h-24 rounded-full flex items-center justify-center shadow-lg
                    @if ($solicitud->estado == 'completada') bg-emerald-500 text-white
                    @else bg-amber-500 text-white @endif">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if ($solicitud->estado == 'completada')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        @endif
                                    </svg>
                                </div>
                            </div>

                            <!-- Información del estado -->
                            <div class="space-y-3">
                                <h4 class="text-2xl font-extrabold text-gray-900">{{ ucfirst($solicitud->estado) }}
                                </h4>
                                <p class="text-gray-600 text-base font-medium">
                                    @if ($solicitud->estado == 'completada')
                                        ✅ Proceso finalizado correctamente
                                    @else
                                        ⚡ En proceso de atención activa
                                    @endif
                                </p>
                            </div>

                            <!-- Indicador visual -->
                            <div
                                class="w-20 h-1.5 mx-auto rounded-full
                @if ($solicitud->estado == 'completada') bg-emerald-500
                @else bg-amber-500 @endif">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones Rápidas -->
                <div
                    class="bg-white rounded-3xl shadow-xl border-2 border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                    <!-- Header con gradiente y animación -->
                    <div
                        class="px-6 py-6 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 relative overflow-hidden">
                        <!-- Efecto de brillo animado -->
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-20 transform -skew-x-12 animate-shimmer">
                        </div>

                        <h3 class="text-xl font-extrabold text-white flex items-center drop-shadow-lg relative z-10">
                            <div class="p-2 bg-white/20 backdrop-blur-sm rounded-lg mr-3 shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            Acciones Rápidas
                        </h3>
                    </div>

                    <!-- Contenido del panel -->
                    <div class="p-6 space-y-4">
                        @if ($solicitud->estado != 'completada')
                            <form action="#" method="POST" class="w-full">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    class="group w-full flex items-center justify-center px-6 py-4 bg-success text-white font-bold text-base rounded-xl hover:from-emerald-600 hover:via-green-600 hover:to-emerald-700 transition-all duration-300 shadow-lg hover:shadow-2xl hover:-translate-y-1 relative overflow-hidden">
                                    <!-- Efecto de brillo en hover -->
                                    <div
                                        class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-0 group-hover:opacity-20 transform -skew-x-12 group-hover:animate-shimmer-fast">
                                    </div>

                                    <!-- Icono con animación -->
                                    <div
                                        class="p-2 bg-white/20 rounded-lg mr-3 group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>

                                    <span class="relative z-10">Marcar como Completada</span>

                                    <!-- Indicador de acción -->
                                    <div
                                        class="ml-auto p-1.5 bg-white/20 rounded-lg group-hover:bg-white/30 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('solicitudarticulo.destroy', $solicitud->idSolicitud) }}"
                            method="POST" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="group w-full flex items-center justify-center px-6 py-4 bg-danger text-white font-bold text-base rounded-xl hover:from-rose-600 hover:via-red-600 hover:to-rose-700 transition-all duration-300 shadow-lg hover:shadow-2xl hover:-translate-y-1 relative overflow-hidden"
                                onclick="return confirm('¿Estás seguro de eliminar esta solicitud?')">
                                <!-- Efecto de brillo en hover -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-0 group-hover:opacity-20 transform -skew-x-12 group-hover:animate-shimmer-fast">
                                </div>

                                <!-- Icono con animación -->
                                <div
                                    class="p-2 bg-white/20 rounded-lg mr-3 group-hover:scale-110 group-hover:rotate-12 transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </div>

                                <span class="relative z-10">Eliminar Solicitud</span>

                                <!-- Indicador de advertencia -->
                                <div
                                    class="ml-auto p-1.5 bg-white/20 rounded-lg group-hover:bg-white/30 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                        </path>
                                    </svg>
                                </div>
                            </button>
                        </form>

                        <!-- Información adicional -->
                        <div class="pt-4 border-t-2 border-gray-100">
                            <div class="flex items-center justify-center gap-2 text-xs text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-medium">Las acciones son permanentes</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Tarjeta de artículos solicitados mejorada -->
        <div
            class="bg-white rounded-3xl shadow-2xl border-2 border-gray-100 overflow-hidden transition-all duration-500 hover:shadow-3xl hover:-translate-y-1 mb-8">
            <!-- Header con gradiente mejorado -->
            <div class="px-8 py-6 bg-gradient-to-r from-amber-500 to-orange-500 rounded-t-3xl">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center">
                        <div
                            class="p-3 bg-white/20 backdrop-blur-lg rounded-2xl mr-4 shadow-2xl border border-white/30">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-3xl font-black text-white drop-shadow-lg tracking-tight">Artículos
                                Solicitados</h2>
                            <p class="text-amber-100 text-sm font-medium mt-1">Lista completa de productos requeridos
                            </p>
                        </div>
                    </div>
                    <span
                        class="px-5 py-3 bg-white text-amber-700 text-base font-black rounded-2xl shadow-2xl border-2 border-amber-200">
                        📦 {{ count($solicitud->articulos) }} artículos
                    </span>
                </div>
            </div>

            <!-- Contenido de la tabla -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200/60">
                    <thead class="bg-gradient-to-r from-slate-50 to-gray-100/80 backdrop-blur-sm">
                        <tr>
                            <th
                                class="px-8 py-6 text-left text-sm font-black text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                <div class="flex items-center">
                                    <span class="mr-2">📋</span>
                                    Artículo
                                </div>
                            </th>
                            <th
                                class="px-8 py-6 text-left text-sm font-black text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                <div class="flex items-center">
                                    <span class="mr-2">🏷️</span>
                                    Código
                                </div>
                            </th>
                            <th
                                class="px-8 py-6 text-left text-sm font-black text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                <div class="flex items-center">
                                    <span class="mr-2">🔢</span>
                                    Cantidad
                                </div>
                            </th>
                            <th
                                class="px-8 py-6 text-left text-sm font-black text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                <div class="flex items-center">
                                    <span class="mr-2">📝</span>
                                    Descripción
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200/50">
                        @forelse($solicitud->articulos as $index => $articulo)
                            <tr
                                class="hover:bg-gradient-to-r hover:from-blue-50/80 hover:to-indigo-50/80 transition-all duration-300 group border-l-4 border-l-transparent hover:border-l-blue-500">
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="flex items-center space-x-4">
                                        <div class="relative">
                                            <div
                                                class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-lg group-hover:from-blue-600 group-hover:to-indigo-700 transition-all duration-300 transform group-hover:scale-105">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2.5"
                                                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div
                                                class="absolute -top-2 -right-2 w-6 h-6 bg-amber-500 rounded-full flex items-center justify-center text-xs font-black text-white shadow-lg">
                                                {{ $index + 1 }}
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="font-bold text-gray-900 text-lg truncate">
                                                {{ $articulo->nombre ?? 'No especificado' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-4 py-3 rounded-2xl text-sm font-bold bg-gradient-to-r from-slate-100 to-gray-200 text-gray-800 border-2 border-gray-300/50 shadow-sm hover:shadow-md transition-all">
                                        <span class="mr-2">🔗</span>
                                        {{ $articulo->codigo_barras ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-5 py-3 rounded-2xl text-lg font-black bg-primary text-white shadow-lg hover:shadow-xl transition-all transform hover:scale-105">
                                        <span class="mr-2">🎯</span>
                                        {{ $articulo->pivot->cantidad }}
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="max-w-xs">
                                        <p
                                            class="text-gray-700 font-medium leading-relaxed line-clamp-2 group-hover:text-gray-900 transition-colors">
                                            {{ $articulo->pivot->descripcion ?: 'Sin descripción adicional' }}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-6">
                                        <div
                                            class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-3xl flex items-center justify-center shadow-2xl border-2 border-gray-300/50">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div class="space-y-3">
                                            <h3 class="text-2xl font-black text-gray-900">No hay artículos registrados
                                            </h3>
                                            <p class="text-gray-500 text-lg font-medium max-w-md">
                                                No se han agregado artículos a esta solicitud en este momento.
                                            </p>
                                        </div>
                                        <button
                                            class="px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl transition-all hover:-translate-y-1">
                                            ➕ Agregar Primer Artículo
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer con resumen -->
            @if (count($solicitud->articulos) > 0)
                <div class="px-8 py-4 bg-gradient-to-r from-gray-50 to-gray-100/80 border-t border-gray-200">
                    <div class="flex items-center justify-between text-sm font-medium text-gray-600">
                        <span>Total de artículos listados: <strong
                                class="text-gray-800">{{ count($solicitud->articulos) }}</strong></span>
                        <span>Última actualización: <strong
                                class="text-gray-800">{{ now()->format('d M Y, H:i') }}</strong></span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Estilos adicionales -->
    <style>
        .truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            .truncate {
                max-width: 200px;
            }
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</x-layout.default>
