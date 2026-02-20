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

        /* Estilos para la tabla */
        .table-hover tbody tr:hover {
            background-color: #f9fafb;
            transition: all 0.2s ease;
        }

        .dark .table-hover tbody tr:hover {
            background-color: #1f2937;
        }

        /* Estilos para las tarjetas de información */
        .info-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 1rem;
            padding: 1.5rem;
            color: white;
        }

        .detail-item {
            @apply flex justify-between items-center py-2 border-b border-gray-100;
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
                            Evaluar Horas Extras
                        </span>
                    </li>
                </ul>
            </div>

            <!-- Header mejorado -->
            <div class="bg-white rounded-2xl border border-gray-200 p-5 mb-6 shadow-sm">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            <i class="fas fa-clock text-blue-600 mr-2"></i>
                            Evaluar Horas Extras
                        </h1>
                        <p class="text-gray-600 mt-2">
                            <i class="fas fa-info-circle text-gray-400 mr-1"></i>
                            Gestiona y evalúa las solicitudes de horas extras del personal
                        </p>
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
                        <button onclick="aplicarFiltros()"
                            class="px-5 py-2 bg-gray-900 text-white font-semibold rounded-lg hover:bg-black transition-colors shadow-md hover:shadow-lg flex items-center justify-center">
                            <i class="fas fa-search mr-2"></i>
                            Filtrar
                        </button>
                        <button onclick="limpiarFiltros()"
                            class="px-5 py-2 bg-gray-100 text-gray-800 font-semibold rounded-lg hover:bg-gray-200 transition-colors flex items-center justify-center">
                            <i class="fas fa-redo mr-2"></i>
                            Limpiar
                        </button>
                    </div>
                </div>

                <div class="w-full">
                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                        <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-user mr-1 text-gray-400"></i>
                                    Usuario
                                </label>
                                <select id="filtroUsuario" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                                    <option value="">Todos los usuarios</option>
                                    <option value="1">Juan Pérez González</option>
                                    <option value="2">María García López</option>
                                    <option value="3">Carlos Rodríguez Martínez</option>
                                    <option value="4">Ana Sánchez Torres</option>
                                    <option value="5">Luis Fernando Gómez</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-circle mr-1 text-gray-400"></i>
                                    Estado
                                </label>
                                <select id="filtroEstado" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                                    <option value="">Todos los estados</option>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="aprobado">Aprobado</option>
                                    <option value="rechazado">Rechazado</option>
                                </select>
                            </div>
                        </div>

                        <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-calendar-day mr-1 text-gray-400"></i>
                                    Fecha desde
                                </label>
                                <input type="text" id="filtroFechaDesde"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg flatpickr-input"
                                    placeholder="dd/mm/aaaa" readonly>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-calendar-day mr-1 text-gray-400"></i>
                                    Fecha hasta
                                </label>
                                <input type="text" id="filtroFechaHasta"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg flatpickr-input"
                                    placeholder="dd/mm/aaaa" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contador de solicitudes -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div
                    class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-yellow-50 text-yellow-600 mr-4">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Pendientes</p>
                            <p class="text-2xl font-bold text-gray-900" id="contadorPendientes">4</p>
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
                            <p class="text-2xl font-bold text-gray-900" id="contadorAprobadas">2</p>
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
                            <p class="text-sm text-gray-500">Rechazadas</p>
                            <p class="text-2xl font-bold text-gray-900" id="contadorRechazadas">1</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de solicitudes -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-file-alt mr-2 text-blue-600"></i>
                        Solicitudes de Horas Extras
                    </h2>
                    <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                        <i class="fas fa-hashtag mr-1"></i>
                        Total: <span id="totalSolicitudes">7</span>
                    </span>
                </div>

                <div class="overflow-x-auto w-full">
                    <table class="min-w-full divide-y divide-gray-200 table-hover" id="tablaHorasExtras">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-user mr-1"></i> USUARIO
                                </th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-calendar-alt mr-1"></i> FECHA
                                </th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-hourglass-start mr-1"></i> RANGO INICIO
                                </th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-hourglass-end mr-1"></i> RANGO FINAL
                                </th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-clock mr-1"></i> TIEMPO
                                </th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-cogs mr-1"></i> ACCIONES
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            <!-- SOLICITUD 1 - PENDIENTE -->
                            <tr class="hover:bg-gray-50 transition-colors" data-estado="pendiente" data-id="1"
                                data-usuario="Juan Pérez González" data-fecha="18/02/2026" data-inicio="18:30"
                                data-final="21:00" data-tiempo="2h 30m">
                                <td class="px-6 py-4 text-center">
                                    <div class="font-semibold text-gray-900">Juan Pérez González</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="text-sm text-gray-900">18/02/2026</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="text-sm text-gray-900">18:30</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="text-sm text-gray-900">21:00</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-semibold text-blue-600">2h 30m</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center space-x-2">
                                        <button onclick="mostrarModalAprobar(this)"
                                            class="inline-flex items-center px-3 py-2 rounded-lg bg-green-100 text-green-700 hover:bg-green-200 transition-colors text-sm font-medium">
                                            <i class="fas fa-check-circle mr-1.5"></i>
                                            Aprobar
                                        </button>
                                        <button onclick="mostrarModalRechazar(this)"
                                            class="inline-flex items-center px-3 py-2 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 transition-colors text-sm font-medium">
                                            <i class="fas fa-times-circle mr-1.5"></i>
                                            Rechazar
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- SOLICITUD 2 - PENDIENTE -->
                            <tr class="hover:bg-gray-50 transition-colors" data-estado="pendiente" data-id="2"
                                data-usuario="María García López" data-fecha="19/02/2026" data-inicio="19:00"
                                data-final="22:30" data-tiempo="3h 30m">
                                <td class="px-6 py-4 text-center">
                                    <div class="font-semibold text-gray-900">María García López</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="text-sm text-gray-900">19/02/2026</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="text-sm text-gray-900">19:00</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="text-sm text-gray-900">22:30</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-semibold text-blue-600">3h 30m</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center space-x-2">
                                        <button onclick="mostrarModalAprobar(this)"
                                            class="inline-flex items-center px-3 py-2 rounded-lg bg-green-100 text-green-700 hover:bg-green-200 transition-colors text-sm font-medium">
                                            <i class="fas fa-check-circle mr-1.5"></i>
                                            Aprobar
                                        </button>
                                        <button onclick="mostrarModalRechazar(this)"
                                            class="inline-flex items-center px-3 py-2 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 transition-colors text-sm font-medium">
                                            <i class="fas fa-times-circle mr-1.5"></i>
                                            Rechazar
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- SOLICITUD 3 - APROBADA (con botón Ver) -->
                            <tr class="hover:bg-gray-50 transition-colors" data-estado="aprobado" data-id="3"
                                data-usuario="Carlos Rodríguez Martínez" data-fecha="17/02/2026" data-inicio="20:00"
                                data-final="21:30" data-tiempo="1h 30m">
                                <td class="px-6 py-4 text-center">
                                    <div class="font-semibold text-gray-900">Carlos Rodríguez Martínez</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="text-sm text-gray-900">17/02/2026</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="text-sm text-gray-900">20:00</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="text-sm text-gray-900">21:30</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-semibold text-green-600">1h 30m</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center space-x-2">
                                        <span
                                            class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1.5"></i>
                                            Aprobado
                                        </span>
                                        <button onclick="mostrarDetalleAprobacion(this)"
                                            class="inline-flex items-center px-2 py-1 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors text-xs">
                                            <i class="fas fa-eye mr-1"></i>
                                            Ver
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- SOLICITUD 4 - RECHAZADA (con botón Ver) -->
                            <tr class="hover:bg-gray-50 transition-colors" data-estado="rechazado" data-id="4"
                                data-usuario="Ana Sánchez Torres" data-fecha="16/02/2026" data-inicio="08:00"
                                data-final="12:00" data-tiempo="4h 00m">
                                <td class="px-6 py-4 text-center">
                                    <div class="font-semibold text-gray-900">Ana Sánchez Torres</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="text-sm text-gray-900">16/02/2026</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="text-sm text-gray-900">08:00</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="text-sm text-gray-900">12:00</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-semibold text-red-600">4h 00m</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center space-x-2">
                                        <span
                                            class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1.5"></i>
                                            Rechazado
                                        </span>
                                        <button onclick="mostrarDetalleRechazo(this)"
                                            class="inline-flex items-center px-2 py-1 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors text-xs">
                                            <i class="fas fa-eye mr-1"></i>
                                            Ver
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- SOLICITUD 5 - PENDIENTE -->
                            <tr class="hover:bg-gray-50 transition-colors" data-estado="pendiente" data-id="5"
                                data-usuario="Luis Fernando Gómez" data-fecha="20/02/2026" data-inicio="22:00"
                                data-final="23:30" data-tiempo="1h 30m">
                                <td class="px-6 py-4 text-center">
                                    <div class="font-semibold text-gray-900">Luis Fernando Gómez</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="text-sm text-gray-900">20/02/2026</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="text-sm text-gray-900">22:00</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="text-sm text-gray-900">23:30</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-semibold text-blue-600">1h 30m</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center space-x-2">
                                        <button onclick="mostrarModalAprobar(this)"
                                            class="inline-flex items-center px-3 py-2 rounded-lg bg-green-100 text-green-700 hover:bg-green-200 transition-colors text-sm font-medium">
                                            <i class="fas fa-check-circle mr-1.5"></i>
                                            Aprobar
                                        </button>
                                        <button onclick="mostrarModalRechazar(this)"
                                            class="inline-flex items-center px-3 py-2 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 transition-colors text-sm font-medium">
                                            <i class="fas fa-times-circle mr-1.5"></i>
                                            Rechazar
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- SOLICITUD 6 - PENDIENTE -->
                            <tr class="hover:bg-gray-50 transition-colors" data-estado="pendiente" data-id="6"
                                data-usuario="Patricia Mendoza Díaz" data-fecha="21/02/2026" data-inicio="09:30"
                                data-final="13:00" data-tiempo="3h 30m">
                                <td class="px-6 py-4 text-center">
                                    <div class="font-semibold text-gray-900">Patricia Mendoza Díaz</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="text-sm text-gray-900">21/02/2026</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="text-sm text-gray-900">09:30</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="text-sm text-gray-900">13:00</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-semibold text-blue-600">3h 30m</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center space-x-2">
                                        <button onclick="mostrarModalAprobar(this)"
                                            class="inline-flex items-center px-3 py-2 rounded-lg bg-green-100 text-green-700 hover:bg-green-200 transition-colors text-sm font-medium">
                                            <i class="fas fa-check-circle mr-1.5"></i>
                                            Aprobar
                                        </button>
                                        <button onclick="mostrarModalRechazar(this)"
                                            class="inline-flex items-center px-3 py-2 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 transition-colors text-sm font-medium">
                                            <i class="fas fa-times-circle mr-1.5"></i>
                                            Rechazar
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- SOLICITUD 7 - PENDIENTE -->
                            <tr class="hover:bg-gray-50 transition-colors" data-estado="pendiente" data-id="7"
                                data-usuario="Roberto Jiménez Torres" data-fecha="22/02/2026" data-inicio="18:45"
                                data-final="20:15" data-tiempo="1h 30m">
                                <td class="px-6 py-4 text-center">
                                    <div class="font-semibold text-gray-900">Roberto Jiménez Torres</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="text-sm text-gray-900">22/02/2026</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="text-sm text-gray-900">18:45</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="text-sm text-gray-900">20:15</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-semibold text-blue-600">1h 30m</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center space-x-2">
                                        <button onclick="mostrarModalAprobar(this)"
                                            class="inline-flex items-center px-3 py-2 rounded-lg bg-green-100 text-green-700 hover:bg-green-200 transition-colors text-sm font-medium">
                                            <i class="fas fa-check-circle mr-1.5"></i>
                                            Aprobar
                                        </button>
                                        <button onclick="mostrarModalRechazar(this)"
                                            class="inline-flex items-center px-3 py-2 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 transition-colors text-sm font-medium">
                                            <i class="fas fa-times-circle mr-1.5"></i>
                                            Rechazar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL PARA APROBAR -->
    <div id="modalAprobar"
        class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4 modal-overlay">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl transform transition-all modal-content">
            <!-- Header del modal -->
            <div class="bg-success rounded-t-2xl px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center text-white">
                        <div class="bg-opacity-20 p-2 rounded-lg mr-3">
                            <i class="fas fa-check-circle text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Aprobar Horas Extras</h3>
                            <p class="text-green-100 text-sm mt-0.5">Revisa y confirma la aprobación</p>
                        </div>
                    </div>
                    <button onclick="cerrarModalAprobar()"
                        class="text-white hover:text-gray-200 transition-colors bg-danger rounded-lg p-2">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Contenido del modal -->
            <div class="p-6">
                <!-- Tarjeta de información del empleado -->
                <div class="bg-gray-50 rounded-xl p-5 mb-5 border border-gray-200">
                    <div class="flex items-center mb-3">
                        <div class="bg-blue-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <h4 class="text-md font-semibold text-gray-800">Información del empleado</h4>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Empleado</p>
                            <p class="font-semibold text-gray-900 text-lg" id="aprobarUsuario">Juan Pérez González</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Fecha de solicitud</p>
                            <p class="font-semibold text-gray-900" id="aprobarFecha">18/02/2026</p>
                        </div>
                    </div>
                </div>

                <!-- Detalles de la jornada -->
                <div class="bg-blue-50 rounded-xl p-5 mb-5 border border-blue-200">
                    <div class="flex items-center mb-3">
                        <div class="bg-blue-200 p-2 rounded-lg mr-3">
                            <i class="fas fa-clock text-blue-700"></i>
                        </div>
                        <h4 class="text-md font-semibold text-blue-800">Detalles de la jornada</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Rango de horas -->
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Rango de tiempo</p>
                            <div class="flex items-center justify-between">
                                <div class="text-center">
                                    <p class="text-sm text-gray-600">Inicio</p>
                                    <p class="text-xl font-bold text-blue-600" id="aprobarInicio">18:30</p>
                                </div>
                                <i class="fas fa-arrow-right text-gray-400 mx-2"></i>
                                <div class="text-center">
                                    <p class="text-sm text-gray-600">Final</p>
                                    <p class="text-xl font-bold text-blue-600" id="aprobarFinal">21:00</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tiempo solicitado -->
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Tiempo solicitado</p>
                            <div class="flex items-center justify-center">
                                <div class="text-center">
                                    <p class="text-3xl font-bold text-green-600" id="aprobarTiempoSolicitado">2h 30m
                                    </p>
                                    <p class="text-sm text-gray-500 mt-1">horas totales</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Campo para modificar horas -->
                <div class="border-2 border-blue-200 rounded-xl p-5 bg-white mb-5">
                    <div class="flex items-center mb-3">
                        <div class="bg-yellow-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-pencil-alt text-yellow-600"></i>
                        </div>
                        <div>
                            <h4 class="text-md font-semibold text-gray-800">Ajuste de horas</h4>
                            <p class="text-xs text-gray-500">Modifica las horas si es necesario (opcional)</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-end gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Horas a aprobar
                            </label>
                            <div class="flex items-center gap-2">
                                <input type="number" id="horasModificadas" step="0.5" min="0.5"
                                    max="12" value="2.5"
                                    class="w-24 px-3 py-2 text-center border-2 border-blue-200 rounded-lg focus:border-blue-500 focus:ring-blue-500 font-semibold text-lg">
                                <span class="text-gray-600 font-medium">horas</span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-500 bg-gray-50 p-2 rounded-lg">
                                <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                                Equivalente a <span id="minutosEquivalentes">150</span> minutos
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="cerrarModalAprobar()"
                        class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors flex items-center">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </button>
                    <button type="button" onclick="confirmarAprobacion()"
                        class="px-6 py-3 bg-success text-white font-medium rounded-xl hover:from-green-700 hover:to-green-800 transition-all shadow-lg flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        Confirmar Aprobación
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL PARA RECHAZAR -->
    <div id="modalRechazar"
        class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4 modal-overlay">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl transform transition-all modal-content">
            <!-- Header del modal -->
            <div class="bg-danger rounded-t-2xl px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center text-white">
                        <div class="bg-opacity-20 p-2 rounded-lg mr-3">
                            <i class="fas fa-times-circle text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Rechazar Horas Extras</h3>
                            <p class="text-red-100 text-sm mt-0.5">Confirma el rechazo de la solicitud</p>
                        </div>
                    </div>
                    <button onclick="cerrarModalRechazar()" class="text-danger bg-white rounded-lg p-2">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Contenido del modal -->
            <div class="p-6">
                <!-- Tarjeta de información del empleado -->
                <div class="bg-gray-50 rounded-xl p-5 mb-5 border border-gray-200">
                    <div class="flex items-center mb-3">
                        <div class="bg-red-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-user text-red-600"></i>
                        </div>
                        <h4 class="text-md font-semibold text-gray-800">Información del empleado</h4>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Empleado</p>
                            <p class="font-semibold text-gray-900 text-lg" id="rechazarUsuario">Juan Pérez González
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Fecha de solicitud</p>
                            <p class="font-semibold text-gray-900" id="rechazarFecha">18/02/2026</p>
                        </div>
                    </div>
                </div>

                <!-- Detalles de la jornada -->
                <div class="bg-red-50 rounded-xl p-5 mb-5 border border-red-200">
                    <div class="flex items-center mb-3">
                        <div class="bg-red-200 p-2 rounded-lg mr-3">
                            <i class="fas fa-clock text-red-700"></i>
                        </div>
                        <h4 class="text-md font-semibold text-red-800">Detalles de la jornada</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Rango de horas -->
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Rango de tiempo</p>
                            <div class="flex items-center justify-between">
                                <div class="text-center">
                                    <p class="text-sm text-gray-600">Inicio</p>
                                    <p class="text-xl font-bold text-red-600" id="rechazarInicio">18:30</p>
                                </div>
                                <i class="fas fa-arrow-right text-gray-400 mx-2"></i>
                                <div class="text-center">
                                    <p class="text-sm text-gray-600">Final</p>
                                    <p class="text-xl font-bold text-red-600" id="rechazarFinal">21:00</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tiempo solicitado -->
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Tiempo solicitado</p>
                            <div class="flex items-center justify-center">
                                <div class="text-center">
                                    <p class="text-3xl font-bold text-red-600" id="rechazarTiempo">2h 30m</p>
                                    <p class="text-sm text-gray-500 mt-1">horas totales</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mensaje importante sobre corrección -->
                <div class="bg-yellow-50 rounded-xl p-5 mb-5 border-l-4 border-yellow-400">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="bg-yellow-100 p-2 rounded-lg">
                                <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-md font-semibold text-yellow-800 mb-1">Ajuste automático</h4>
                            <p class="text-sm text-yellow-700">
                                Al rechazar esta solicitud, se realizará una corrección automática ajustando la hora de
                                salida del empleado a su horario habitual. Este cambio quedará registrado en el sistema.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Textarea para comentario de administración -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-comment-dots mr-1 text-gray-400"></i>
                        Comentario de administración
                    </label>
                    <textarea id="comentarioRechazar" rows="4"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors resize-none"
                        placeholder="Explica el motivo del rechazo..."></textarea>
                    <p class="text-xs text-gray-500 mt-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        Este comentario será visible para el empleado y quedará en el historial.
                    </p>
                </div>

                <!-- Botones de acción -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="cerrarModalRechazar()"
                        class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors flex items-center">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </button>
                    <button type="button" onclick="confirmarRechazo()"
                        class="px-6 py-3 bg-danger text-white font-medium rounded-xl hover:from-red-700 hover:to-red-800 transition-all shadow-lg flex items-center">
                        <i class="fas fa-times-circle mr-2"></i>
                        Confirmar Rechazo
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL PARA VER DETALLES DE APROBACIÓN - SIN COMENTARIO -->
    <div id="modalVerAprobacion"
        class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4 modal-overlay">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl transform transition-all modal-content">
            <!-- Header del modal -->
            <div class="bg-success rounded-t-2xl px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center text-white">
                        <div class="bg-opacity-20 p-2 rounded-lg mr-3">
                            <i class="fas fa-check-circle text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Detalles de Aprobación</h3>
                            <p class="text-green-100 text-sm mt-0.5">Información completa de la evaluación</p>
                        </div>
                    </div>
                    <button onclick="cerrarModalVerAprobacion()"
                        class="text-white hover:text-gray-200 transition-colors bg-danger rounded-lg p-2">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Contenido del modal -->
            <div class="p-6">
                <!-- Tarjeta de información del empleado -->
                <div class="bg-gray-50 rounded-xl p-5 mb-5 border border-gray-200">
                    <div class="flex items-center mb-3">
                        <div class="bg-blue-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <h4 class="text-md font-semibold text-gray-800">Información del empleado</h4>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Empleado</p>
                            <p class="font-semibold text-gray-900 text-lg" id="verUsuario">Carlos Rodríguez Martínez
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Fecha de solicitud</p>
                            <p class="font-semibold text-gray-900" id="verFecha">17/02/2026</p>
                        </div>
                    </div>
                </div>

                <!-- Detalles de la jornada -->
                <div class="bg-green-50 rounded-xl p-5 mb-5 border border-green-200">
                    <div class="flex items-center mb-3">
                        <div class="bg-green-200 p-2 rounded-lg mr-3">
                            <i class="fas fa-clock text-green-700"></i>
                        </div>
                        <h4 class="text-md font-semibold text-green-800">Detalles de la jornada</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Rango de horas -->
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Rango de tiempo</p>
                            <div class="flex items-center justify-between">
                                <div class="text-center">
                                    <p class="text-sm text-gray-600">Inicio</p>
                                    <p class="text-xl font-bold text-green-600" id="verInicio">20:00</p>
                                </div>
                                <i class="fas fa-arrow-right text-gray-400 mx-2"></i>
                                <div class="text-center">
                                    <p class="text-sm text-gray-600">Final</p>
                                    <p class="text-xl font-bold text-green-600" id="verFinal">21:30</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tiempo aprobado -->
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Tiempo aprobado</p>
                            <div class="flex items-center justify-center">
                                <div class="text-center">
                                    <p class="text-3xl font-bold text-green-600" id="verTiempoAprobado">1h 30m</p>
                                    <p class="text-sm text-gray-500 mt-1">horas aprobadas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de quién aprobó -->
                <div class="bg-blue-50 rounded-xl p-5 mb-5 border border-blue-200">
                    <div class="flex items-center mb-3">
                        <div class="bg-blue-200 p-2 rounded-lg mr-3">
                            <i class="fas fa-user-check text-blue-700"></i>
                        </div>
                        <h4 class="text-md font-semibold text-blue-800">Evaluado por</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Usuario que aprobó</p>
                            <p class="font-semibold text-gray-900 text-lg" id="verAprobadoPor">Admin Sistema</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Fecha de evaluación</p>
                            <p class="font-semibold text-gray-900" id="verFechaEvaluacion">19/02/2026 10:30</p>
                        </div>
                    </div>
                </div>

                <!-- Botón de cerrar -->
                <div class="flex justify-end pt-4 border-t border-gray-200">
                    <button type="button" onclick="cerrarModalVerAprobacion()"
                        class="px-6 py-3 bg-gray-600 text-white font-medium rounded-xl hover:bg-gray-700 transition-colors flex items-center">
                        <i class="fas fa-times mr-2"></i>
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- MODAL PARA VER DETALLES DE RECHAZO -->
    <div id="modalVerRechazo"
        class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4 modal-overlay">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl transform transition-all modal-content">
            <!-- Header del modal -->
            <div class="bg-danger rounded-t-2xl px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center text-white">
                        <div class="bg-opacity-20 p-2 rounded-lg mr-3">
                            <i class="fas fa-times-circle text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Detalles de Rechazo</h3>
                            <p class="text-red-100 text-sm mt-0.5">Información completa de la evaluación</p>
                        </div>
                    </div>
                    <button onclick="cerrarModalVerRechazo()"
                        class="text-danger bg-white rounded-lg p-2">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Contenido del modal -->
            <div class="p-6">
                <!-- Tarjeta de información del empleado -->
                <div class="bg-gray-50 rounded-xl p-5 mb-5 border border-gray-200">
                    <div class="flex items-center mb-3">
                        <div class="bg-red-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-user text-red-600"></i>
                        </div>
                        <h4 class="text-md font-semibold text-gray-800">Información del empleado</h4>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Empleado</p>
                            <p class="font-semibold text-gray-900 text-lg" id="verRechazoUsuario">Ana Sánchez Torres
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Fecha de solicitud</p>
                            <p class="font-semibold text-gray-900" id="verRechazoFecha">16/02/2026</p>
                        </div>
                    </div>
                </div>

                <!-- Detalles de la jornada -->
                <div class="bg-red-50 rounded-xl p-5 mb-5 border border-red-200">
                    <div class="flex items-center mb-3">
                        <div class="bg-red-200 p-2 rounded-lg mr-3">
                            <i class="fas fa-clock text-red-700"></i>
                        </div>
                        <h4 class="text-md font-semibold text-red-800">Detalles de la jornada</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Rango de horas -->
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Rango de tiempo</p>
                            <div class="flex items-center justify-between">
                                <div class="text-center">
                                    <p class="text-sm text-gray-600">Inicio</p>
                                    <p class="text-xl font-bold text-red-600" id="verRechazoInicio">08:00</p>
                                </div>
                                <i class="fas fa-arrow-right text-gray-400 mx-2"></i>
                                <div class="text-center">
                                    <p class="text-sm text-gray-600">Final</p>
                                    <p class="text-xl font-bold text-red-600" id="verRechazoFinal">12:00</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tiempo solicitado -->
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Tiempo solicitado</p>
                            <div class="flex items-center justify-center">
                                <div class="text-center">
                                    <p class="text-3xl font-bold text-red-600" id="verRechazoTiempo">4h 00m</p>
                                    <p class="text-sm text-gray-500 mt-1">horas solicitadas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de quién rechazó -->
                <div class="bg-purple-50 rounded-xl p-5 mb-5 border border-purple-200">
                    <div class="flex items-center mb-3">
                        <div class="bg-purple-200 p-2 rounded-lg mr-3">
                            <i class="fas fa-user-times text-purple-700"></i>
                        </div>
                        <h4 class="text-md font-semibold text-purple-800">Evaluado por</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Usuario que rechazó</p>
                            <p class="font-semibold text-gray-900 text-lg" id="verRechazadoPor">Admin Sistema</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Fecha de evaluación</p>
                            <p class="font-semibold text-gray-900" id="verRechazoFechaEvaluacion">17/02/2026 14:45</p>
                        </div>
                    </div>
                </div>

                <!-- Mensaje sobre corrección automática -->
                <div class="bg-yellow-50 rounded-xl p-5 mb-5 border-l-4 border-yellow-400">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-md font-semibold text-yellow-800 mb-1">Corrección aplicada</h4>
                            <p class="text-sm text-yellow-700" id="verCorreccionMensaje">
                                Se corrigió la hora de salida del empleado a su horario habitual (18:00)
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Comentario de administración -->
                <div class="bg-gray-50 rounded-xl p-5 mb-5 border border-gray-200">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-comment-dots text-gray-500 mr-2"></i>
                        <h4 class="text-md font-semibold text-gray-800">Comentario de administración</h4>
                    </div>
                    <p class="text-gray-700 bg-white p-3 rounded-lg border border-gray-200" id="verRechazoComentario">
                        Las horas extras solicitadas exceden el presupuesto autorizado para el departamento.
                    </p>
                </div>

                <!-- Botón de cerrar -->
                <div class="flex justify-end pt-4 border-t border-gray-200">
                    <button type="button" onclick="cerrarModalVerRechazo()"
                        class="px-6 py-3 bg-gray-600 text-white font-medium rounded-xl hover:bg-gray-700 transition-colors flex items-center">
                        <i class="fas fa-times mr-2"></i>
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        // Configurar Toastr
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        };

        $(document).ready(function() {
            // Inicializar Select2
            $('#filtroUsuario').select2({
                placeholder: "Seleccione usuario",
                allowClear: true,
                width: '100%'
            });

            $('#filtroEstado').select2({
                placeholder: "Seleccione estado",
                allowClear: true,
                width: '100%'
            });

            // Inicializar Flatpickr
            flatpickr("#filtroFechaDesde", {
                locale: "es",
                dateFormat: "d/m/Y",
                allowInput: true
            });

            flatpickr("#filtroFechaHasta", {
                locale: "es",
                dateFormat: "d/m/Y",
                allowInput: true
            });

            // Inicializar contadores
            setTimeout(() => {
                actualizarContadores();
            }, 100);
        });

        // Variables globales
        let filaActual = null;

        // ========== BASE DE DATOS SIMULADA PARA EVALUACIONES ==========
        const evaluacionesData = {
            3: { // ID de la solicitud aprobada
                evaluadoPor: 'Admin Sistema',
                fechaEvaluacion: '19/02/2026 10:30',
                tiempoAprobado: '1h 30m'
            },
            4: { // ID de la solicitud rechazada
                evaluadoPor: 'Admin Sistema',
                fechaEvaluacion: '17/02/2026 14:45',
                comentario: 'Las horas extras solicitadas exceden el presupuesto autorizado para el departamento.',
                correccion: 'Se corrigió la hora de salida del empleado a su horario habitual (18:00)'
            }
        };

        // ========== FUNCIÓN AUXILIAR PARA CALCULAR HORAS ==========
        function calcularHorasDecimal(inicio, final) {
            if (!inicio || !final) return 0;

            const [horaInicio, minInicio] = inicio.split(':').map(Number);
            const [horaFinal, minFinal] = final.split(':').map(Number);

            const totalMinutos = (horaFinal * 60 + minFinal) - (horaInicio * 60 + minInicio);
            const horasDecimal = totalMinutos / 60;

            // Redondear a 1 decimal pero mantener precisión
            return Math.round(horasDecimal * 10) / 10;
        }

        // ========== FUNCIÓN PARA ACTUALIZAR MINUTOS EQUIVALENTES ==========
        function actualizarMinutosEquivalentes() {
            const horasInput = document.getElementById('horasModificadas');
            if (horasInput) {
                const horas = parseFloat(horasInput.value) || 0;
                const minutos = Math.round(horas * 60);
                const minutosSpan = document.getElementById('minutosEquivalentes');
                if (minutosSpan) {
                    minutosSpan.textContent = minutos;
                }
            }
        }

        // ========== FUNCIONES PARA MODAL DE APROBAR ==========
        function mostrarModalAprobar(boton) {
            filaActual = boton.closest('tr');

            // Obtener datos de la fila
            const usuario = filaActual.getAttribute('data-usuario') || 'Usuario no disponible';
            const fecha = filaActual.getAttribute('data-fecha') || 'Fecha no disponible';
            const inicio = filaActual.getAttribute('data-inicio') || '00:00';
            const final = filaActual.getAttribute('data-final') || '00:00';
            const tiempo = filaActual.getAttribute('data-tiempo') || '0h 0m';

            // Calcular horas decimales
            const horasDecimal = calcularHorasDecimal(inicio, final);

            // Actualizar el modal
            document.getElementById('aprobarUsuario').textContent = usuario;
            document.getElementById('aprobarFecha').textContent = fecha;
            document.getElementById('aprobarTiempoSolicitado').textContent = tiempo;
            document.getElementById('aprobarInicio').textContent = inicio;
            document.getElementById('aprobarFinal').textContent = final;

            const horasInput = document.getElementById('horasModificadas');
            if (horasInput) {
                horasInput.value = horasDecimal;
            }

            // Actualizar minutos equivalentes
            actualizarMinutosEquivalentes();

            // Mostrar modal
            document.getElementById('modalAprobar').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function cerrarModalAprobar() {
            document.getElementById('modalAprobar').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function confirmarAprobacion() {
            const usuario = document.getElementById('aprobarUsuario').textContent;
            const horasModificadas = document.getElementById('horasModificadas').value;
            const id = filaActual.getAttribute('data-id');

            // Mostrar notificación
            toastr.success(`Solicitud de ${usuario} aprobada con ${horasModificadas} horas`);

            // Cambiar el estado en la tabla con botón Ver
            if (filaActual) {
                const accionesCell = filaActual.querySelector('td:last-child');
                accionesCell.innerHTML = `
                <div class="flex justify-center space-x-2">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1.5"></i> Aprobado
                    </span>
                    <button onclick="mostrarDetalleAprobacion(this)" 
                        class="inline-flex items-center px-2 py-1 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors text-xs">
                        <i class="fas fa-eye mr-1"></i> Ver
                    </button>
                </div>
            `;
                filaActual.setAttribute('data-estado', 'aprobado');

                // Guardar datos de evaluación (SIN COMENTARIO)
                const fechaActual = new Date();
                const fechaFormateada = fechaActual.toLocaleDateString('es-ES') + ' ' +
                    fechaActual.getHours() + ':' + String(fechaActual.getMinutes()).padStart(2, '0');

                evaluacionesData[id] = {
                    evaluadoPor: 'Admin Sistema',
                    fechaEvaluacion: fechaFormateada,
                    tiempoAprobado: horasModificadas + 'h'
                    // SIN COMENTARIO
                };
            }

            cerrarModalAprobar();
            actualizarContadores();
        }

        // ========== FUNCIONES PARA MODAL DE RECHAZAR ==========
        function mostrarModalRechazar(boton) {
            filaActual = boton.closest('tr');

            // Obtener datos de la fila
            const usuario = filaActual.getAttribute('data-usuario') || 'Usuario no disponible';
            const fecha = filaActual.getAttribute('data-fecha') || 'Fecha no disponible';
            const inicio = filaActual.getAttribute('data-inicio') || '00:00';
            const final = filaActual.getAttribute('data-final') || '00:00';
            const tiempo = filaActual.getAttribute('data-tiempo') || '0h 0m';

            // Actualizar el modal
            document.getElementById('rechazarUsuario').textContent = usuario;
            document.getElementById('rechazarFecha').textContent = fecha;
            document.getElementById('rechazarTiempo').textContent = tiempo;
            document.getElementById('rechazarInicio').textContent = inicio;
            document.getElementById('rechazarFinal').textContent = final;

            const comentario = document.getElementById('comentarioRechazar');
            if (comentario) {
                comentario.value = '';
            }

            // Mostrar modal
            document.getElementById('modalRechazar').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function cerrarModalRechazar() {
            document.getElementById('modalRechazar').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function confirmarRechazo() {
            const usuario = document.getElementById('rechazarUsuario').textContent;
            const comentario = document.getElementById('comentarioRechazar').value;
            const id = filaActual.getAttribute('data-id');

            // Mostrar notificación
            if (comentario) {
                toastr.success(`Solicitud de ${usuario} rechazada. Comentario: ${comentario}`);
            } else {
                toastr.success(`Solicitud de ${usuario} rechazada correctamente`);
            }

            // Cambiar el estado en la tabla con botón Ver
            if (filaActual) {
                const accionesCell = filaActual.querySelector('td:last-child');
                accionesCell.innerHTML = `
                <div class="flex justify-center space-x-2">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                        <i class="fas fa-times-circle mr-1.5"></i> Rechazado
                    </span>
                    <button onclick="mostrarDetalleRechazo(this)" 
                        class="inline-flex items-center px-2 py-1 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors text-xs">
                        <i class="fas fa-eye mr-1"></i> Ver
                    </button>
                </div>
            `;
                filaActual.setAttribute('data-estado', 'rechazado');

                // Guardar datos de evaluación
                const fechaActual = new Date();
                const fechaFormateada = fechaActual.toLocaleDateString('es-ES') + ' ' +
                    fechaActual.getHours() + ':' + String(fechaActual.getMinutes()).padStart(2, '0');

                evaluacionesData[id] = {
                    evaluadoPor: 'Admin Sistema',
                    fechaEvaluacion: fechaFormateada,
                    comentario: comentario || 'Solicitud rechazada',
                    correccion: 'Se corrigió la hora de salida del empleado a su horario habitual.'
                };
            }

            cerrarModalRechazar();
            actualizarContadores();
        }

        // ========== FUNCIONES PARA VER DETALLES DE APROBACIÓN (SIN COMENTARIO) ==========
        function mostrarDetalleAprobacion(boton) {
            const fila = boton.closest('tr');
            const id = fila.getAttribute('data-id');
            const usuario = fila.getAttribute('data-usuario');
            const fecha = fila.getAttribute('data-fecha');
            const inicio = fila.getAttribute('data-inicio');
            const final = fila.getAttribute('data-final');
            const tiempo = fila.getAttribute('data-tiempo');

            // Obtener datos de evaluación
            const evalData = evaluacionesData[id] || {
                evaluadoPor: 'Admin Sistema',
                fechaEvaluacion: '19/02/2026 10:30',
                tiempoAprobado: tiempo
            };

            // Actualizar el modal (SIN COMENTARIO)
            document.getElementById('verUsuario').textContent = usuario;
            document.getElementById('verFecha').textContent = fecha;
            document.getElementById('verInicio').textContent = inicio;
            document.getElementById('verFinal').textContent = final;
            document.getElementById('verTiempoAprobado').textContent = evalData.tiempoAprobado || tiempo;
            document.getElementById('verAprobadoPor').textContent = evalData.evaluadoPor;
            document.getElementById('verFechaEvaluacion').textContent = evalData.fechaEvaluacion;

            // Mostrar modal
            document.getElementById('modalVerAprobacion').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function cerrarModalVerAprobacion() {
            document.getElementById('modalVerAprobacion').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // ========== FUNCIONES PARA VER DETALLES DE RECHAZO ==========
        function mostrarDetalleRechazo(boton) {
            const fila = boton.closest('tr');
            const id = fila.getAttribute('data-id');
            const usuario = fila.getAttribute('data-usuario');
            const fecha = fila.getAttribute('data-fecha');
            const inicio = fila.getAttribute('data-inicio');
            const final = fila.getAttribute('data-final');
            const tiempo = fila.getAttribute('data-tiempo');

            // Obtener datos de evaluación
            const evalData = evaluacionesData[id] || {
                evaluadoPor: 'Admin Sistema',
                fechaEvaluacion: '17/02/2026 14:45',
                comentario: 'Solicitud rechazada.',
                correccion: 'Se corrigió la hora de salida del empleado a su horario habitual.'
            };

            // Actualizar el modal
            document.getElementById('verRechazoUsuario').textContent = usuario;
            document.getElementById('verRechazoFecha').textContent = fecha;
            document.getElementById('verRechazoInicio').textContent = inicio;
            document.getElementById('verRechazoFinal').textContent = final;
            document.getElementById('verRechazoTiempo').textContent = tiempo;
            document.getElementById('verRechazadoPor').textContent = evalData.evaluadoPor;
            document.getElementById('verRechazoFechaEvaluacion').textContent = evalData.fechaEvaluacion;
            document.getElementById('verRechazoComentario').textContent = evalData.comentario;
            document.getElementById('verCorreccionMensaje').textContent = evalData.correccion;

            // Mostrar modal
            document.getElementById('modalVerRechazo').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function cerrarModalVerRechazo() {
            document.getElementById('modalVerRechazo').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // ========== FUNCIÓN PARA ACTUALIZAR CONTADORES ==========
        function actualizarContadores() {
            const filas = document.querySelectorAll('#tablaHorasExtras tbody tr');
            let pendientes = 0,
                aprobadas = 0,
                rechazadas = 0;

            filas.forEach(fila => {
                const estado = fila.getAttribute('data-estado');
                if (estado === 'pendiente') pendientes++;
                else if (estado === 'aprobado') aprobadas++;
                else if (estado === 'rechazado') rechazadas++;
            });

            const contadorPendientes = document.getElementById('contadorPendientes');
            const contadorAprobadas = document.getElementById('contadorAprobadas');
            const contadorRechazadas = document.getElementById('contadorRechazadas');
            const totalSolicitudes = document.getElementById('totalSolicitudes');

            if (contadorPendientes) contadorPendientes.textContent = pendientes;
            if (contadorAprobadas) contadorAprobadas.textContent = aprobadas;
            if (contadorRechazadas) contadorRechazadas.textContent = rechazadas;
            if (totalSolicitudes) totalSolicitudes.textContent = filas.length;
        }

        // ========== FUNCIONES PARA FILTROS ==========
        function aplicarFiltros() {
            const usuario = $('#filtroUsuario').val();
            const estado = $('#filtroEstado').val();
            const fechaDesde = $('#filtroFechaDesde').val();
            const fechaHasta = $('#filtroFechaHasta').val();

            toastr.info('Filtros aplicados (simulado)');
            console.log('Filtros:', {
                usuario,
                estado,
                fechaDesde,
                fechaHasta
            });
        }

        function limpiarFiltros() {
            $('#filtroUsuario').val('').trigger('change');
            $('#filtroEstado').val('').trigger('change');
            $('#filtroFechaDesde').val('');
            $('#filtroFechaHasta').val('');

            toastr.info('Filtros limpiados');
        }

        // ========== EVENT LISTENER PARA INPUT DE HORAS ==========
        document.addEventListener('DOMContentLoaded', function() {
            const horasInput = document.getElementById('horasModificadas');
            if (horasInput) {
                horasInput.addEventListener('input', actualizarMinutosEquivalentes);
            }
        });

        // ========== CERRAR MODALES CON ESC ==========
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modalAprobar = document.getElementById('modalAprobar');
                const modalRechazar = document.getElementById('modalRechazar');
                const modalVerAprobacion = document.getElementById('modalVerAprobacion');
                const modalVerRechazo = document.getElementById('modalVerRechazo');

                if (modalAprobar && !modalAprobar.classList.contains('hidden')) {
                    cerrarModalAprobar();
                }
                if (modalRechazar && !modalRechazar.classList.contains('hidden')) {
                    cerrarModalRechazar();
                }
                if (modalVerAprobacion && !modalVerAprobacion.classList.contains('hidden')) {
                    cerrarModalVerAprobacion();
                }
                if (modalVerRechazo && !modalVerRechazo.classList.contains('hidden')) {
                    cerrarModalVerRechazo();
                }
            }
        });

        // ========== CERRAR MODALES AL HACER CLIC FUERA ==========
        document.getElementById('modalAprobar')?.addEventListener('click', function(e) {
            if (e.target.id === 'modalAprobar') {
                cerrarModalAprobar();
            }
        });

        document.getElementById('modalRechazar')?.addEventListener('click', function(e) {
            if (e.target.id === 'modalRechazar') {
                cerrarModalRechazar();
            }
        });

        document.getElementById('modalVerAprobacion')?.addEventListener('click', function(e) {
            if (e.target.id === 'modalVerAprobacion') {
                cerrarModalVerAprobacion();
            }
        });

        document.getElementById('modalVerRechazo')?.addEventListener('click', function(e) {
            if (e.target.id === 'modalVerRechazo') {
                cerrarModalVerRechazo();
            }
        });

        // ========== ACTUALIZAR CONTADORES AL CARGAR ==========
        window.addEventListener('load', function() {
            actualizarContadores();
        });
    </script>
</x-layout.default>
