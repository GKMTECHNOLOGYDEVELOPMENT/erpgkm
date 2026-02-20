<x-layout.default>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        .select2-container--default .select2-selection--single {
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            padding: 0.75rem !important;
            height: 48px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 1.5 !important;
            padding-left: 0 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px !important;
            right: 8px !important;
        }

        .select2-container--default .select2-selection--single:focus {
            outline: none;
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1) !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #3b82f6 !important;
        }

        .flatpickr-input {
            background-color: white !important;
            cursor: pointer !important;
        }

        /* Estilos para los estados seleccionados */
        .estado-radio:checked+.estado-aprobado {
            border-color: #10B981 !important;
            background-color: #ECFDF5 !important;
        }

        .estado-radio:checked+.estado-aprobado .estado-dot {
            border-color: #10B981 !important;
            background-color: #10B981 !important;
        }

        .estado-radio:checked+.estado-aprobado .estado-icon {
            color: #10B981 !important;
        }

        .estado-radio:checked+.estado-denegado {
            border-color: #EF4444 !important;
            background-color: #FEF2F2 !important;
        }

        .estado-radio:checked+.estado-denegado .estado-dot {
            border-color: #EF4444 !important;
            background-color: #EF4444 !important;
        }

        .estado-radio:checked+.estado-denegado .estado-icon {
            color: #EF4444 !important;
        }

        /* Estilos base para los íconos */
        .estado-aprobado .estado-icon {
            color: #6B7280;
        }

        .estado-denegado .estado-icon {
            color: #6B7280;
        }

        /* Animaciones para modales */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideIn {
            from {
                transform: translateY(-20px) scale(0.95);
                opacity: 0;
            }

            to {
                transform: translateY(0) scale(1);
                opacity: 1;
            }
        }

        .modal-overlay {
            animation: fadeIn 0.2s ease-out;
        }

        .modal-content {
            animation: slideIn 0.3s ease-out;
        }
    </style>

    <div class="min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Breadcrumb -->
            <div class="mb-4">
                <ul class="flex space-x-2 rtl:space-x-reverse text-sm">
                    <li>
                        <a href="javascript:;" class="text-primary hover:underline font-medium">
                            Administración
                        </a>
                    </li>
                    <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1 text-gray-400">
                        <span class="text-gray-600 font-medium">
                            Solicitudes de Asistencia
                        </span>
                    </li>
                </ul>
            </div>

            <!-- Header mejorado -->
            <div class="bg-white rounded-2xl border border-gray-200 p-5 mb-6 shadow-sm">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            <i class="fas fa-hands-helping text-blue-600 mr-2"></i>
                            Solicitudes de Asistencia
                        </h1>
                        <p class="text-gray-600 mt-2">
                            <i class="fas fa-info-circle text-gray-400 mr-1"></i>
                            Gestiona y realiza seguimiento de todas las solicitudes del sistema
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('administracion.solicitud-asistencia.create') }}"
                            class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg hover:shadow-xl">
                            <i class="fas fa-plus-circle mr-2"></i>
                            Nueva Solicitud
                        </a>
                    </div>
                </div>
            </div>

            <!-- Panel de filtros mejorado -->
            <div class="bg-white rounded-2xl border border-gray-200 p-5 mb-6 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-4">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-filter text-gray-500 mr-2"></i>
                        Filtros de búsqueda
                    </h2>

                    <div class="flex items-center gap-3">
                        <button type="submit" form="filtrosForm"
                            class="px-5 py-2 bg-gray-900 text-white font-semibold rounded-lg hover:bg-black transition-colors shadow-md hover:shadow-lg flex items-center justify-center">
                            <i class="fas fa-search mr-2"></i>
                            Filtrar
                        </button>
                        <a href="{{ route('administracion.solicitud-asistencia.index') }}"
                            class="px-5 py-2 bg-gray-100 text-gray-800 font-semibold rounded-lg hover:bg-gray-200 transition-colors flex items-center justify-center">
                            <i class="fas fa-redo mr-2"></i>
                            Limpiar
                        </a>
                    </div>
                </div>

                <form class="w-full" id="filtrosForm">
                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                        <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-tag mr-1 text-gray-400"></i>
                                    Tipo de solicitud
                                </label>
                                <select name="tipo" id="filtroTipo"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="">Todos los tipos</option>
                                    @foreach ($tipos as $t)
                                        <option value="{{ $t->id_tipo_solicitud }}" @selected(request('tipo') == $t->id_tipo_solicitud)>
                                            {{ $t->nombre_tip }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-circle mr-1 text-gray-400"></i>
                                    Estado
                                </label>
                                <select name="estado" id="filtroEstado"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="">Todos los estados</option>
                                    @foreach (['pendiente', 'aprobado', 'denegado'] as $st)
                                        <option value="{{ $st }}" @selected(request('estado') === $st)>
                                            {{ ucfirst($st) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-calendar-day mr-1 text-gray-400"></i>
                                    Fecha desde
                                </label>
                                <input type="text" name="fecha_desde" id="filtroFechaDesde"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors flatpickr-input"
                                    placeholder="dd/mm/aaaa" value="{{ request('fecha_desde') }}" readonly>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-calendar-day mr-1 text-gray-400"></i>
                                    Fecha hasta
                                </label>
                                <input type="text" name="fecha_hasta" id="filtroFechaHasta"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors flatpickr-input"
                                    placeholder="dd/mm/aaaa" value="{{ request('fecha_hasta') }}" readonly>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Contador de solicitudes -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div
                    class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-blue-50 text-blue-600 mr-4">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Pendientes</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ $solicitudes->where('estado', 'pendiente')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-green-50 text-green-600 mr-4">
                            <i class="fas fa-check-circle text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Aprobadas</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ $solicitudes->where('estado', 'aprobado')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-red-50 text-red-600 mr-4">
                            <i class="fas fa-times-circle text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Denegadas</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ $solicitudes->where('estado', 'denegado')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de solicitudes -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-file-alt mr-2 text-blue-600"></i>
                        Lista de Solicitudes
                    </h2>
                    <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                        <i class="fas fa-hashtag mr-1"></i>
                        Total: {{ $solicitudes->total() }}
                    </span>
                </div>

                <div class="overflow-x-auto w-full">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-hashtag mr-1"></i> ID
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-tag mr-1"></i> Tipo
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-calendar-alt mr-1"></i> Rango de tiempo
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-circle mr-1"></i> Estado
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-calendar-plus mr-1"></i> Fecha de solicitud
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-cogs mr-1"></i> Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($solicitudes as $s)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-center">
                                        <div
                                            class="text-sm font-medium text-gray-900 flex items-center justify-center">
                                            <i class="fas fa-hashtag text-gray-400 mr-1"></i>
                                            {{ $s->id_solicitud_asistencia }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div>
                                                <div class="font-semibold text-gray-900">
                                                    {{ $s->tipoSolicitud?->nombre_tip }}</div>
                                                @if ($s->tipoEducacion)
                                                    <div
                                                        class="text-xs text-gray-500 mt-1 flex items-center justify-center">
                                                        <i class="fas fa-graduation-cap mr-1"></i>
                                                        {{ $s->tipoEducacion->nombre }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="text-sm text-gray-900 flex items-center">
                                                <i class="fas fa-calendar-day text-gray-400 mr-1"></i>
                                                {{ optional($s->rango_inicio_tiempo)->format('d/m/Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1 flex items-center">
                                                <i class="fas fa-arrow-right text-gray-300 mr-1"></i>
                                                al {{ optional($s->rango_final_tiempo)->format('d/m/Y') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @php
                                            $badgeConfig = match ($s->estado) {
                                                'aprobado' => 'bg-green-100 text-green-800 border-green-200',
                                                'denegado' => 'bg-red-100 text-red-800 border-red-200',
                                                default => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            };
                                            $icon = match ($s->estado) {
                                                'aprobado' => 'check-circle',
                                                'denegado' => 'times-circle',
                                                default => 'clock',
                                            };
                                        @endphp
                                        <div class="flex justify-center">
                                            <span
                                                class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold {{ $badgeConfig }}">
                                                <i class="fas fa-{{ $icon }} mr-1.5"></i>
                                                {{ ucfirst($s->estado) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="text-sm text-gray-900 flex items-center">
                                                <i class="fas fa-calendar text-gray-400 mr-1"></i>
                                                {{ optional($s->fecha_solicitud)->format('d/m/Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1 flex items-center">
                                                <i class="fas fa-clock text-gray-300 mr-1"></i>
                                                {{ optional($s->fecha_solicitud)->format('H:i') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-2">
                                            <a href="{{ route('administracion.solicitud-asistencia.show', $s->id_solicitud_asistencia) }}"
                                                class="inline-flex items-center px-3 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors text-sm font-medium group"
                                                title="Ver detalles">
                                                <i class="fas fa-eye mr-1.5 group-hover:text-blue-600"></i>
                                                Ver
                                            </a>

                                            <a href="{{ route('administracion.solicitud-asistencia.edit', $s->id_solicitud_asistencia) }}"
                                                class="inline-flex items-center px-3 py-2 rounded-lg bg-primary-light text-primary hover:bg-blue-100 transition-colors text-sm font-medium group"
                                                title="Editar">
                                                <i class="fas fa-edit mr-1.5 group-hover:text-blue-800"></i>
                                                Editar
                                            </a>

                                            @if ($s->estado == 'pendiente')
                                                <button type="button"
                                                    onclick="mostrarModalEstado({{ $s->id_solicitud_asistencia }})"
                                                    class="inline-flex items-center px-3 py-2 rounded-lg bg-warning-light text-warning hover:bg-yellow-100 transition-colors text-sm font-medium group"
                                                    title="Evaluar solicitud">
                                                    <i
                                                        class="fas fa-check-double mr-1.5 group-hover:text-yellow-800"></i>
                                                    Evaluar
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="mx-auto max-w-md">
                                            <div
                                                class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 mb-4">
                                                <i class="fas fa-inbox text-gray-400 text-xl"></i>
                                            </div>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">No hay solicitudes</h3>
                                            <p class="text-gray-500 mb-6">No se encontraron solicitudes de asistencia
                                                registradas en el sistema.</p>
                                            <a href="{{ route('administracion.solicitud-asistencia.create') }}"
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                                <i class="fas fa-plus-circle mr-2"></i>
                                                Crear primera solicitud
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                @if ($solicitudes->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                <i class="fas fa-list-ol mr-1"></i>
                                Mostrando <span class="font-medium">{{ $solicitudes->firstItem() }}</span> a
                                <span class="font-medium">{{ $solicitudes->lastItem() }}</span> de
                                <span class="font-medium">{{ $solicitudes->total() }}</span> resultados
                            </div>
                            <div class="flex space-x-2">
                                {{ $solicitudes->links('vendor.pagination.tailwind') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal para cambiar estado -->
    <div id="modalEstado"
        class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg transform transition-all modal-content">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">
                            <i class="fas fa-exchange-alt text-blue-600 mr-2"></i>
                            Cambiar Estado
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Actualiza el estado de la solicitud</p>
                    </div>
                    <button onclick="cerrarModalEstado()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <form id="formEstado" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="solicitud_id" id="solicitud_id">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            <i class="fas fa-sliders-h mr-1 text-gray-400"></i>
                            Selecciona el nuevo estado
                        </label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative flex cursor-pointer">
                                <input type="radio" name="estado" value="aprobado"
                                    class="sr-only peer estado-radio" required>
                                <div
                                    class="w-full p-4 border-2 border-gray-200 rounded-xl hover:bg-gray-50 transition-all estado-option estado-aprobado">
                                    <div class="flex items-center">
                                        <div
                                            class="w-5 h-5 rounded-full border-2 border-gray-300 mr-3 transition-all estado-dot">
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900 flex items-center">
                                                <i class="fas fa-check-circle mr-2 estado-icon"></i>
                                                Aprobar
                                            </div>
                                            <div class="text-sm text-gray-500">Solicitud aceptada</div>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <label class="relative flex cursor-pointer">
                                <input type="radio" name="estado" value="denegado"
                                    class="sr-only peer estado-radio" required>
                                <div
                                    class="w-full p-4 border-2 border-gray-200 rounded-xl hover:bg-gray-50 transition-all estado-option estado-denegado">
                                    <div class="flex items-center">
                                        <div
                                            class="w-5 h-5 rounded-full border-2 border-gray-300 mr-3 transition-all estado-dot">
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900 flex items-center">
                                                <i class="fas fa-times-circle mr-2 estado-icon"></i>
                                                Denegar
                                            </div>
                                            <div class="text-sm text-gray-500">Solicitud rechazada</div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-comment-dots mr-1 text-gray-400"></i>
                            Comentario adicional
                        </label>
                        <textarea name="comentario" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                            placeholder="Agrega un comentario explicando la decisión (opcional)"></textarea>
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Este comentario será registrado en el historial de la solicitud.
                        </p>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="cerrarModalEstado()"
                            class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors flex items-center">
                            <i class="fas fa-times mr-2"></i>
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-6 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors shadow-sm flex items-center">
                            <i class="fas fa-save mr-2"></i>
                            Confirmar Cambio
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        // Configurar Toastr globalmente
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": true,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        $(document).ready(function() {
            // Inicializar Select2 para los filtros
            $('#filtroTipo').select2({
                placeholder: "Seleccione tipo",
                allowClear: true,
                width: '100%'
            });

            $('#filtroEstado').select2({
                placeholder: "Seleccione estado",
                allowClear: true,
                width: '100%'
            });

            // Configuración común para Flatpickr
            const flatpickrConfig = {
                locale: "es",
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d/m/Y",
                allowInput: true
            };

            // Inicializar Flatpickr para fecha desde
            const fechaDesdePicker = flatpickr("#filtroFechaDesde", {
                ...flatpickrConfig,
                defaultDate: "{{ request('fecha_desde') }}",
                maxDate: "today",
                onChange: function(selectedDates, dateStr) {
                    // Cuando se selecciona una fecha desde, actualizar el mínimo del hasta
                    if (dateStr && fechaHastaPicker) {
                        fechaHastaPicker.set('minDate', dateStr);

                        // Si la fecha hasta actual es menor que la nueva fecha desde, limpiarla
                        const fechaHastaValue = document.getElementById('filtroFechaHasta').value;
                        if (fechaHastaValue && new Date(fechaHastaValue) < new Date(dateStr)) {
                            fechaHastaPicker.clear();
                        }
                    }
                }
            });

            // Inicializar Flatpickr para fecha hasta
            const fechaHastaPicker = flatpickr("#filtroFechaHasta", {
                ...flatpickrConfig,
                defaultDate: "{{ request('fecha_hasta') }}",
                maxDate: "today",
                minDate: "{{ request('fecha_desde') }}"
            });

            // Prevenir que el formulario de filtros se envíe si Select2 está abierto
            $('#filtrosForm').on('keydown', function(e) {
                if (e.key === 'Enter' && $('.select2-search__field').is(':focus')) {
                    e.preventDefault();
                }
            });

            // Mostrar mensaje cuando no hay resultados en Select2
            $.fn.select2.defaults.set("language", {
                noResults: function() {
                    return "No se encontraron resultados";
                }
            });

            // Mostrar notificación de éxito si existe en la sesión
            @if (session('success'))
                toastr.success("{{ session('success') }}");
            @endif

            @if (session('error'))
                toastr.error("{{ session('error') }}");
            @endif

            @if (session('warning'))
                toastr.warning("{{ session('warning') }}");
            @endif

            @if (session('info'))
                toastr.info("{{ session('info') }}");
            @endif
        });

        // ========== FUNCIONES PARA MODAL DE ESTADO ==========
        function mostrarModalEstado(solicitudId) {
            document.getElementById('solicitud_id').value = solicitudId;
            document.getElementById('modalEstado').classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Configurar el action del form con la ruta correcta
            const form = document.getElementById('formEstado');
            const url = "{{ route('administracion.solicitud-asistencia.cambiar-estado', ':id') }}".replace(':id',
                solicitudId);
            form.action = url;

            // Resetear los radio buttons
            form.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.checked = false;
            });
        }

        function cerrarModalEstado() {
            document.getElementById('modalEstado').classList.add('hidden');
            document.body.style.overflow = 'auto';
            // Limpiar los radios y comentario
            document.querySelectorAll('#formEstado input[type="radio"]').forEach(radio => {
                radio.checked = false;
            });
            document.querySelector('#formEstado textarea[name="comentario"]').value = '';
        }

        // ========== MANEJAR ENVÍO DEL FORMULARIO ==========
        // Manejar el envío del formulario del modal de estado
        document.getElementById('formEstado').addEventListener('submit', function(e) {
            const estadoSeleccionado = document.querySelector('input[name="estado"]:checked');

            if (!estadoSeleccionado) {
                e.preventDefault();
                toastr.warning('Por favor selecciona un estado');
                return false;
            }

            // Mostrar feedback visual durante el envío
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Procesando...';
            submitBtn.disabled = true;

            // El formulario se enviará normalmente (síncrono)
            return true;
        });

        // ========== CERRAR MODAL CON ESC Y CLICK FUERA ==========
        // Cerrar modal al hacer clic fuera
        document.getElementById('modalEstado').addEventListener('click', function(e) {
            if (e.target.id === 'modalEstado') {
                cerrarModalEstado();
            }
        });

        // Cerrar modal con ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (!document.getElementById('modalEstado').classList.contains('hidden')) {
                    cerrarModalEstado();
                }
            }
        });
    </script>
</x-layout.default>
