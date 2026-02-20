<x-layout.default>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
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

        /* Animaciones para modales */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
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
        
        /* Loader */
        .loader {
            border: 3px solid #f3f3f3;
            border-radius: 50%;
            border-top: 3px solid #3498db;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Estilo para el input de hora */
        .hora-input {
            font-family: monospace;
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            letter-spacing: 2px;
        }

        /* Estilos para badges de verificación */
        .badge-extra {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .badge-extra-confirmada {
            background-color: #10B981;
            color: white;
        }
        
        .badge-extra-pendiente {
            background-color: #F59E0B;
            color: white;
        }
        
        .badge-extra-sin {
            background-color: #6B7280;
            color: white;
        }

        /* Estilos para la tabla de visitas dentro del modal */
        .tabla-visitas-container {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
        }

        .tabla-visitas {
            width: 100%;
            border-collapse: collapse;
        }

        .tabla-visitas thead {
            position: sticky;
            top: 0;
            background-color: #f9fafb;
            z-index: 5;
        }

        .tabla-visitas th {
            padding: 0.75rem 1rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            border-bottom: 1px solid #e5e7eb;
        }

        .tabla-visitas td {
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .tabla-visitas tr:last-child td {
            border-bottom: none;
        }

        .tabla-visitas tbody tr:hover {
            background-color: #f9fafb;
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
                    <div>
                        <button onclick="procesarHorasExtras()" 
                            class="px-5 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-md hover:shadow-lg flex items-center justify-center">
                            <i class="fas fa-sync-alt mr-2"></i>
                            Procesar Horas Extras
                        </button>
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
                                    <option value="">Cargando usuarios...</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-circle mr-1 text-gray-400"></i>
                                    Estado
                                </label>
                                <select id="filtroEstado" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                                    <option value="">Todos los estados</option>
                                    <option value="PENDIENTE">Pendiente</option>
                                    <option value="APROBADO">Aprobado</option>
                                    <option value="DENEGADO">Rechazado</option>
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
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-yellow-50 text-yellow-600 mr-4">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Pendientes</p>
                            <p class="text-2xl font-bold text-gray-900" id="contadorPendientes">0</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-green-50 text-green-600 mr-4">
                            <i class="fas fa-check-circle text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Aprobadas</p>
                            <p class="text-2xl font-bold text-gray-900" id="contadorAprobadas">0</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-red-50 text-red-600 mr-4">
                            <i class="fas fa-times-circle text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Rechazadas</p>
                            <p class="text-2xl font-bold text-gray-900" id="contadorRechazadas">0</p>
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
                        Total: <span id="totalSolicitudes">0</span>
                    </span>
                </div>

                <div class="overflow-x-auto w-full">
                    <table class="min-w-full divide-y divide-gray-200 table-hover" id="tablaHorasExtras">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-user mr-1"></i> USUARIO
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-calendar-alt mr-1"></i> FECHA
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-hourglass-start mr-1"></i> HORA INICIO EXTRA
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-hourglass-end mr-1"></i> HORA FINAL EXTRA
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-clock mr-1"></i> TIEMPO EXTRA
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-cogs mr-1"></i> ACCIONES
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            <tr>
                                <td colspan="6" class="text-center py-8">
                                    <div class="flex justify-center items-center">
                                        <div class="loader mr-3"></div>
                                        <span class="text-gray-600">Cargando solicitudes...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL PARA APROBAR CON DETALLES DE VISITAS -->
    <div id="modalAprobar" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4 modal-overlay">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl transform transition-all modal-content max-h-[90vh] overflow-y-auto">
            <div class="bg-green-600 rounded-t-2xl px-6 py-4 sticky top-0 z-10">
                <div class="flex items-center justify-between">
                    <div class="flex items-center text-white">
                        <div class="bg-opacity-20 p-2 rounded-lg mr-3">
                            <i class="fas fa-check-circle text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Aprobar Horas Extras</h3>
                            <p class="text-green-100 text-sm mt-0.5">Revisa las visitas del día y confirma</p>
                        </div>
                    </div>
                    <button onclick="cerrarModalAprobar()" class="text-white hover:text-gray-200 transition-colors bg-red-500 rounded-lg p-2">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <div class="p-6">
                <!-- Tarjeta de información del empleado -->
                <div class="bg-gray-50 rounded-xl p-5 mb-5 border border-gray-200">
                    <div class="flex items-center mb-3">
                        <div class="bg-blue-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <h4 class="text-md font-semibold text-gray-800">Información del empleado</h4>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Empleado</p>
                            <p class="font-semibold text-gray-900 text-lg" id="aprobarUsuario">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Fecha de solicitud</p>
                            <p class="font-semibold text-gray-900" id="aprobarFecha">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Hora marcada</p>
                            <p class="font-semibold text-blue-600" id="aprobarHoraMarcada">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Minutos extra</p>
                            <p class="font-semibold text-green-600" id="aprobarMinutosExtra">-</p>
                        </div>
                    </div>
                </div>

                <!-- Detalles de la jornada -->
                <div class="bg-blue-50 rounded-xl p-5 mb-5 border border-blue-200">
                    <div class="flex items-center mb-3">
                        <div class="bg-blue-200 p-2 rounded-lg mr-3">
                            <i class="fas fa-clock text-blue-700"></i>
                        </div>
                        <h4 class="text-md font-semibold text-blue-800">Rango de tiempo extra</h4>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Inicio fijo -->
                        <div class="bg-white rounded-lg p-4 shadow-sm text-center">
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Inicio extra (fijo)</p>
                            <p class="text-2xl font-bold text-blue-600">17:00</p>
                        </div>
                        
                        <!-- Hora final actual -->
                        <div class="bg-white rounded-lg p-4 shadow-sm text-center">
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Final extra actual</p>
                            <p class="text-2xl font-bold text-blue-600" id="aprobarHoraFinalDisplay">--:--</p>
                        </div>
                        
                        <!-- Minutos calculados -->
                        <div class="bg-green-50 rounded-lg p-4 shadow-sm text-center">
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Minutos desde 17:00</p>
                            <p class="text-2xl font-bold text-green-600" id="minutosDesdeInicio">0</p>
                        </div>
                    </div>
                </div>

                <!-- Campo para modificar la HORA FINAL -->
                <div class="border-2 border-blue-200 rounded-xl p-5 bg-white mb-5">
                    <div class="flex items-center mb-3">
                        <div class="bg-yellow-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-pencil-alt text-yellow-600"></i>
                        </div>
                        <div>
                            <h4 class="text-md font-semibold text-gray-800">Ajuste de hora de salida</h4>
                            <p class="text-xs text-gray-500">Modifica la hora de salida extra (HH:MM)</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-end gap-6 justify-center">
                        <div class="text-center">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Hora final extra
                            </label>
                            <input type="time" id="horaFinalExtra" step="60" value="17:00"
                                class="hora-input px-4 py-3 text-center border-2 border-blue-200 rounded-lg focus:border-blue-500 focus:ring-blue-500 font-semibold text-2xl w-40"
                                onchange="calcularTiempoDesdeHora()" onkeyup="calcularTiempoDesdeHora()">
                        </div>
                    </div>
                    
                    <div class="mt-4 p-3 bg-gray-50 rounded-lg text-center">
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                            La hora de inicio es fija: <strong>17:00</strong>. Los minutos se calculan automáticamente.
                        </p>
                    </div>
                </div>

                <!-- Resumen de visitas -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                        <p class="text-xs text-gray-500">Total Visitas del Día</p>
                        <p class="text-2xl font-bold text-gray-800" id="aprobarTotalVisitas">0</p>
                    </div>
                    <div class="bg-orange-50 rounded-lg p-3 border border-orange-200">
                        <p class="text-xs text-orange-600">Visitas después 17:00</p>
                        <p class="text-2xl font-bold text-orange-700" id="aprobarVisitasDespues17">0</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-3 border border-green-200">
                        <p class="text-xs text-green-600">Tiempo extra en visitas</p>
                        <p class="text-2xl font-bold text-green-700" id="aprobarTiempoExtraVisitas">0</p>
                    </div>
                </div>

                <!-- Tabla de visitas -->
                <div class="mb-5">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-semibold text-gray-700">Detalle de Visitas del Día</h4>
                        <span class="text-xs text-gray-500">Las visitas en verde generaron horas extras</span>
                    </div>
                    <div class="tabla-visitas-container">
                        <table class="tabla-visitas">
                            <thead>
                                <tr>
                                    <th>Ticket</th>
                                    <th>Visita</th>
                                    <th>Hora Inicio</th>
                                    <th>Hora Final</th>
                                    <th>Tiempo Extra</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody id="aprobarTablaVisitas">
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-gray-500">
                                        Cargando visitas...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
                        class="px-6 py-3 bg-green-600 text-white font-medium rounded-xl hover:bg-green-700 transition-all shadow-lg flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        Confirmar Aprobación
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL PARA RECHAZAR -->
    <div id="modalRechazar" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4 modal-overlay">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl transform transition-all modal-content">
            <div class="bg-red-600 rounded-t-2xl px-6 py-4">
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
                    <button onclick="cerrarModalRechazar()" class="text-white bg-red-500 rounded-lg p-2">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

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
                            <p class="font-semibold text-gray-900 text-lg" id="rechazarUsuario">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Fecha de solicitud</p>
                            <p class="font-semibold text-gray-900" id="rechazarFecha">-</p>
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
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Rango de tiempo extra</p>
                            <div class="flex items-center justify-between">
                                <div class="text-center">
                                    <p class="text-sm text-gray-600">Inicio extra</p>
                                    <p class="text-xl font-bold text-red-600">17:00</p>
                                </div>
                                <i class="fas fa-arrow-right text-gray-400 mx-2"></i>
                                <div class="text-center">
                                    <p class="text-sm text-gray-600">Final extra</p>
                                    <p class="text-xl font-bold text-red-600" id="rechazarHoraFinal">-</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Tiempo solicitado</p>
                            <div class="flex items-center justify-center">
                                <div class="text-center">
                                    <p class="text-3xl font-bold text-red-600" id="rechazarTiempo">-</p>
                                    <p class="text-sm text-gray-500 mt-1">minutos extra</p>
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
                                salida del empleado a las <strong>17:00</strong>. Este cambio quedará registrado en el sistema.
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
                </div>

                <!-- Botones de acción -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="cerrarModalRechazar()"
                        class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors flex items-center">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </button>
                    <button type="button" onclick="confirmarRechazo()"
                        class="px-6 py-3 bg-red-600 text-white font-medium rounded-xl hover:bg-red-700 transition-all shadow-lg flex items-center">
                        <i class="fas fa-times-circle mr-2"></i>
                        Confirmar Rechazo
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL PARA VER DETALLES DE APROBACIÓN -->
    <div id="modalVerAprobacion" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4 modal-overlay">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl transform transition-all modal-content">
            <div class="bg-green-600 rounded-t-2xl px-6 py-4">
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
                    <button onclick="cerrarModalVerAprobacion()" class="text-white hover:text-gray-200 transition-colors bg-red-500 rounded-lg p-2">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

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
                            <p class="font-semibold text-gray-900 text-lg" id="verUsuario">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Fecha de solicitud</p>
                            <p class="font-semibold text-gray-900" id="verFecha">-</p>
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
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Rango de tiempo extra</p>
                            <div class="flex items-center justify-between">
                                <div class="text-center">
                                    <p class="text-sm text-gray-600">Inicio extra</p>
                                    <p class="text-xl font-bold text-green-600" id="verInicio">-</p>
                                </div>
                                <i class="fas fa-arrow-right text-gray-400 mx-2"></i>
                                <div class="text-center">
                                    <p class="text-sm text-gray-600">Final extra</p>
                                    <p class="text-xl font-bold text-green-600" id="verFinal">-</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Tiempo aprobado</p>
                            <div class="flex items-center justify-center">
                                <div class="text-center">
                                    <p class="text-3xl font-bold text-green-600" id="verTiempoAprobado">-</p>
                                    <p class="text-sm text-gray-500 mt-1">minutos aprobados</p>
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
                            <p class="font-semibold text-gray-900 text-lg" id="verAprobadoPor">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Fecha de evaluación</p>
                            <p class="font-semibold text-gray-900" id="verFechaEvaluacion">-</p>
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
    <div id="modalVerRechazo" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4 modal-overlay">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl transform transition-all modal-content">
            <div class="bg-red-600 rounded-t-2xl px-6 py-4">
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
                    <button onclick="cerrarModalVerRechazo()" class="text-white bg-red-500 rounded-lg p-2">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

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
                            <p class="font-semibold text-gray-900 text-lg" id="verRechazoUsuario">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Fecha de solicitud</p>
                            <p class="font-semibold text-gray-900" id="verRechazoFecha">-</p>
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
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Rango de tiempo extra</p>
                            <div class="flex items-center justify-between">
                                <div class="text-center">
                                    <p class="text-sm text-gray-600">Inicio extra</p>
                                    <p class="text-xl font-bold text-red-600" id="verRechazoInicio">-</p>
                                </div>
                                <i class="fas fa-arrow-right text-gray-400 mx-2"></i>
                                <div class="text-center">
                                    <p class="text-sm text-gray-600">Final extra</p>
                                    <p class="text-xl font-bold text-red-600" id="verRechazoFinal">-</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Tiempo solicitado</p>
                            <div class="flex items-center justify-center">
                                <div class="text-center">
                                    <p class="text-3xl font-bold text-red-600" id="verRechazoTiempo">-</p>
                                    <p class="text-sm text-gray-500 mt-1">minutos solicitados</p>
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
                            <p class="font-semibold text-gray-900 text-lg" id="verRechazadoPor">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Fecha de evaluación</p>
                            <p class="font-semibold text-gray-900" id="verRechazoFechaEvaluacion">-</p>
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
                                Se corrigió la hora de salida del empleado a las 17:00
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
                    <p class="text-gray-700 bg-white p-3 rounded-lg border border-gray-200" id="verRechazoComentario">-</p>
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
            "timeOut": "3000",
            "showDuration": "300",
            "hideDuration": "1000"
        };

        // Variables globales
        let filaActualId = null;
        let datosOriginales = {};
        let fechaOriginalCompleta = null;
        let visitasData = [];

        $(document).ready(function() {
            console.log('Documento listo, inicializando...');
            
            inicializarSelectores();
            inicializarDatePickers();
            cargarUsuariosFiltro();
            cargarHorasExtras();
            actualizarContadores();
        });

        // ========== FUNCIONES DE INICIALIZACIÓN ==========
        function inicializarSelectores() {
            $('#filtroUsuario').select2({
                placeholder: "Seleccione usuario",
                allowClear: true,
                width: '100%',
                language: { noResults: () => "No se encontraron resultados" }
            });

            $('#filtroEstado').select2({
                placeholder: "Seleccione estado",
                allowClear: true,
                width: '100%',
                language: { noResults: () => "No se encontraron resultados" }
            });
        }

        function inicializarDatePickers() {
            flatpickr("#filtroFechaDesde", {
                locale: "es",
                dateFormat: "d/m/Y",
                allowInput: true,
                maxDate: "today"
            });

            flatpickr("#filtroFechaHasta", {
                locale: "es",
                dateFormat: "d/m/Y",
                allowInput: true,
                maxDate: "today"
            });
        }

        // ========== FUNCIONES PARA CARGAR DATOS ==========
        function cargarUsuariosFiltro() {
            $.ajax({
                url: '/administracion/horasextras/usuarios',
                type: 'GET',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    if (response.success) {
                        const select = $('#filtroUsuario');
                        select.empty().append('<option value="">Todos los usuarios</option>');
                        response.data.forEach(usuario => {
                            select.append(`<option value="${usuario.idUsuario}">${usuario.nombre_completo}</option>`);
                        });
                        select.trigger('change');
                    }
                },
                error: function() {
                    $('#filtroUsuario').empty().append('<option value="">Error al cargar</option>');
                }
            });
        }

        function cargarHorasExtras() {
            const filtros = {
                usuario: $('#filtroUsuario').val(),
                estado: $('#filtroEstado').val(),
                fecha_desde: $('#filtroFechaDesde').val(),
                fecha_hasta: $('#filtroFechaHasta').val()
            };

            $('#tablaHorasExtras tbody').html(`
                <tr>
                    <td colspan="6" class="text-center py-8">
                        <div class="flex justify-center items-center">
                            <div class="loader mr-3"></div>
                            <span class="text-gray-600">Cargando solicitudes...</span>
                        </div>
                    </td>
                </tr>
            `);

            $.ajax({
                url: '/administracion/horasextras/listar',
                type: 'GET',
                data: filtros,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    if (response.success) {
                        renderizarTabla(response.data);
                    } else {
                        mostrarErrorTabla('Error al cargar los datos');
                    }
                },
                error: function() {
                    mostrarErrorTabla('Error al cargar las solicitudes');
                }
            });
        }

        function mostrarErrorTabla(mensaje) {
            $('#tablaHorasExtras tbody').html(`
                <tr>
                    <td colspan="6" class="text-center py-8 text-red-600">
                        <i class="fas fa-exclamation-circle text-3xl mb-2"></i>
                        <p>${mensaje}</p>
                        <button onclick="cargarHorasExtras()" class="mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-sync-alt mr-2"></i>Reintentar
                        </button>
                    </td>
                </tr>
            `);
        }

        function renderizarTabla(datos) {
            const tbody = $('#tablaHorasExtras tbody');
            tbody.empty();

            if (!datos || datos.length === 0) {
                tbody.html(`
                    <tr>
                        <td colspan="6" class="text-center py-12">
                            <div class="flex flex-col items-center text-gray-400">
                                <i class="fas fa-inbox text-5xl mb-3"></i>
                                <p class="text-lg">No hay solicitudes de horas extras</p>
                                <p class="text-sm">Las nuevas solicitudes aparecerán aquí automáticamente</p>
                            </div>
                        </td>
                    </tr>
                `);
                return;
            }

            datos.forEach(item => {
                const fecha = new Date(item.fechaHora_original);
                const fechaFormateada = fecha.toLocaleDateString('es-ES');
                
                const horaInicioExtra = '17:00';
                const horaFinalExtra = item.hora_final_extra || fecha.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
                const tiempoExtra = item.tiempo_extra_formateado || 
                    (item.diferencia_minutos ? 
                        (Math.floor(item.diferencia_minutos / 60) > 0 ? 
                            Math.floor(item.diferencia_minutos / 60) + 'h ' + (item.diferencia_minutos % 60) + 'm' : 
                            item.diferencia_minutos + 'm') : 
                        '0m');

                let claseEstado = '';
                let iconoEstado = '';
                let textoEstado = '';
                let acciones = '';

                if (item.estado === 'PENDIENTE') {
                    claseEstado = 'text-blue-600';
                    iconoEstado = 'fa-clock';
                    textoEstado = tiempoExtra;
                    acciones = `
                        <div class="flex justify-center space-x-1">
                            <button onclick="mostrarModalAprobar(${item.idAprobacion}, ${item.idUsuario}, '${fechaFormateada}')" 
                                class="inline-flex items-center px-2 py-1 rounded-lg bg-green-100 text-green-700 hover:bg-green-200 transition-colors text-xs" title="Aprobar">
                                <i class="fas fa-check-circle mr-1"></i>
                                Aprobar
                            </button>
                            <button onclick="mostrarModalRechazar(${item.idAprobacion})" 
                                class="inline-flex items-center px-2 py-1 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 transition-colors text-xs" title="Rechazar">
                                <i class="fas fa-times-circle mr-1"></i>
                                Rechazar
                            </button>
                        </div>
                    `;
                } else if (item.estado === 'APROBADO') {
                    claseEstado = 'text-green-600';
                    iconoEstado = 'fa-check-circle';
                    textoEstado = tiempoExtra;
                    acciones = `
                        <div class="flex justify-center space-x-1">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Aprobado
                            </span>
                            <button onclick="mostrarDetalle(${item.idAprobacion})" 
                                class="inline-flex items-center px-2 py-1 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors text-xs">
                                <i class="fas fa-eye mr-1"></i> Ver
                            </button>
                        </div>
                    `;
                } else if (item.estado === 'DENEGADO') {
                    claseEstado = 'text-red-600';
                    iconoEstado = 'fa-times-circle';
                    textoEstado = tiempoExtra;
                    acciones = `
                        <div class="flex justify-center space-x-1">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i> Rechazado
                            </span>
                            <button onclick="mostrarDetalle(${item.idAprobacion})" 
                                class="inline-flex items-center px-2 py-1 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors text-xs">
                                <i class="fas fa-eye mr-1"></i> Ver
                            </button>
                        </div>
                    `;
                }

                const fila = `
                    <tr class="hover:bg-gray-50 transition-colors" data-id="${item.idAprobacion}" data-estado="${item.estado}" data-usuario="${item.idUsuario}" data-fecha="${fechaFormateada}">
                        <td class="px-6 py-4 text-center">
                            <div class="font-semibold text-gray-900">${item.nombre_completo || 'Sin nombre'}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="text-sm text-gray-900">${fechaFormateada}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="text-sm text-gray-900 font-medium text-blue-600">${horaInicioExtra}</div>
                            <div class="text-xs text-gray-500">(hora salida normal)</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="text-sm text-gray-900 font-medium text-orange-600">${horaFinalExtra}</div>
                            <div class="text-xs text-gray-500">(hora real salida)</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-semibold ${claseEstado}">
                                <i class="fas ${iconoEstado} mr-1"></i>
                                ${textoEstado}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            ${acciones}
                        </td>
                    </tr>
                `;
                
                tbody.append(fila);
            });
            
            actualizarContadores();
        }

        // ========== FUNCIONES PARA CONTADORES ==========
        function actualizarContadores() {
            $.ajax({
                url: '/administracion/horasextras/contadores',
                type: 'GET',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    if (response.success) {
                        $('#contadorPendientes').text(response.data.pendientes || 0);
                        $('#contadorAprobadas').text(response.data.aprobados || 0);
                        $('#contadorRechazadas').text(response.data.rechazados || 0);
                        $('#totalSolicitudes').text(response.data.total || 0);
                    }
                }
            });
        }

        // ========== FUNCIONES PARA FILTROS ==========
        function aplicarFiltros() {
            cargarHorasExtras();
            toastr.info('Filtros aplicados');
        }

        function limpiarFiltros() {
            $('#filtroUsuario').val('').trigger('change');
            $('#filtroEstado').val('').trigger('change');
            $('#filtroFechaDesde').val('');
            $('#filtroFechaHasta').val('');
            cargarHorasExtras();
            toastr.info('Filtros limpiados');
        }

        // ========== FUNCIÓN PARA PROCESAR HORAS EXTRAS ==========
        function procesarHorasExtras() {
            toastr.info('Procesando horas extras...');
            
            $.ajax({
                url: '/administracion/horasextras/procesar',
                type: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        cargarHorasExtras();
                        actualizarContadores();
                    } else {
                        toastr.error(response.message || 'Error al procesar');
                    }
                },
                error: function() {
                    toastr.error('Error al procesar horas extras');
                }
            });
        }

        // ========== FUNCIÓN PARA CALCULAR TIEMPO DESDE HORA FINAL ==========
        function calcularTiempoDesdeHora() {
            const horaFinalInput = document.getElementById('horaFinalExtra');
            if (!horaFinalInput) return;
            
            let horaFinal = horaFinalInput.value;
            
            // Validar que la hora tenga el formato correcto
            if (!horaFinal || horaFinal === '') {
                horaFinalInput.value = '17:00';
                horaFinal = '17:00';
            }
            
            // Asegurar formato HH:MM
            if (!/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/.test(horaFinal)) {
                horaFinalInput.value = '17:00';
                horaFinal = '17:00';
            }
            
            // Validar que no sea menor a 17:00
            if (horaFinal < '17:00') {
                horaFinalInput.value = '17:00';
                horaFinal = '17:00';
            }
            
            // Separar hora y minuto
            const partes = horaFinal.split(':');
            const hora = parseInt(partes[0]);
            const minuto = parseInt(partes[1]);
            
            // Calcular minutos desde 17:00
            const minutosDesdeInicio = (hora - 17) * 60 + minuto;
            
            // Actualizar displays
            document.getElementById('minutosDesdeInicio').textContent = minutosDesdeInicio;
            document.getElementById('aprobarHoraFinalDisplay').textContent = horaFinal;
            
            console.log('Hora final:', horaFinal, 'Minutos desde 17:00:', minutosDesdeInicio);
        }

        // ========== FUNCIÓN PARA CARGAR VISITAS ==========
        function cargarVisitasTecnico(idUsuario, fechaStr, callback) {
            const partes = fechaStr.split('/');
            const fecha = `${partes[2]}-${partes[1].padStart(2, '0')}-${partes[0].padStart(2, '0')}`;
            
            $.ajax({
                url: '/administracion/horasextras/verificar-visitas',
                type: 'GET',
                data: {
                    idUsuario: idUsuario,
                    fecha: fecha
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    if (response.success) {
                        callback(response.data);
                    } else {
                        console.error('Error al cargar visitas:', response.message);
                        callback(null);
                    }
                },
                error: function(xhr) {
                    console.error('Error al cargar visitas:', xhr.responseText);
                    callback(null);
                }
            });
        }

        // ========== FUNCIÓN PARA RENDERIZAR TABLA DE VISITAS EN MODAL ==========
        function renderizarVisitasEnModal(visitas) {
            const tbody = document.getElementById('aprobarTablaVisitas');
            
            if (!visitas || visitas.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500">
                            <i class="fas fa-calendar-times mr-2"></i>
                            No hay visitas para este día
                        </td>
                    </tr>
                `;
                return;
            }
            
            let html = '';
            visitas.forEach(v => {
                const tieneExtra = v.tiene_extra || false;
                const badgeClass = tieneExtra ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
                const badgeText = tieneExtra ? 'Extra confirmada' : 'Sin extra';
                const tiempoClass = tieneExtra ? 'text-green-600 font-semibold' : 'text-gray-500';
                
                html += `
                    <tr class="${tieneExtra ? 'bg-green-50' : ''}">
                        <td class="px-4 py-2">
                            <div class="font-medium">${v.numero_ticket || 'Sin ticket'}</div>
                        </td>
                        <td class="px-4 py-2">
                            <div>${v.nombre_visita || 'Visita'}</div>
                            <div class="text-xs text-gray-500">ID: ${v.idVisitas}</div>
                        </td>
                        <td class="px-4 py-2">${v.hora_inicio || '--:--'}</td>
                        <td class="px-4 py-2">
                            <span class="font-semibold ${tieneExtra ? 'text-green-600' : 'text-gray-600'}">
                                ${v.hora_final || '--:--'}
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            <span class="${tiempoClass}">
                                ${v.tiempo_extra || '0m'}
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${badgeClass}">
                                ${badgeText}
                            </span>
                        </td>
                    </tr>
                `;
            });
            
            tbody.innerHTML = html;
        }

        // ========== FUNCIONES PARA MODAL DE APROBAR ==========
        function mostrarModalAprobar(idAprobacion, idUsuario, fechaStr) {
            filaActualId = idAprobacion;
            
            // Mostrar loader en la tabla de visitas
            document.getElementById('aprobarTablaVisitas').innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-4">
                        <div class="flex justify-center items-center">
                            <div class="loader mr-3"></div>
                            <span class="text-gray-600">Cargando visitas...</span>
                        </div>
                    </td>
                </tr>
            `;
            
            // Cargar datos de la solicitud
            $.ajax({
                url: `/administracion/horasextras/detalle/${idAprobacion}`,
                type: 'GET',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    if (response.success) {
                        datosOriginales = response.data;
                        
                        const fechaOriginal = new Date(datosOriginales.fechaHora_original);
                        fechaOriginalCompleta = fechaOriginal;
                        
                        const minutosExtras = datosOriginales.diferencia_minutos || 0;
                        
                        // Formatear hora final correctamente (siempre con 2 dígitos)
                        const hora = fechaOriginal.getHours().toString().padStart(2, '0');
                        const minuto = fechaOriginal.getMinutes().toString().padStart(2, '0');
                        const horaFinal = `${hora}:${minuto}`;

                        document.getElementById('aprobarUsuario').textContent = datosOriginales.nombre_completo || '-';
                        document.getElementById('aprobarFecha').textContent = fechaOriginal.toLocaleDateString('es-ES');
                        document.getElementById('aprobarHoraMarcada').textContent = horaFinal;
                        
                        const horas = Math.floor(minutosExtras / 60);
                        const minutos = minutosExtras % 60;
                        const tiempoFormateado = horas > 0 ? 
                            (minutos > 0 ? `${horas}h ${minutos}m` : `${horas}h`) : 
                            `${minutos}m`;
                            
                        document.getElementById('aprobarMinutosExtra').textContent = tiempoFormateado;
                        
                        // Mostrar hora final en el display - AHORA SÍ EXISTE
                        document.getElementById('aprobarHoraFinalDisplay').textContent = horaFinal;
                        
                        // Configurar input de hora
                        const horaInput = document.getElementById('horaFinalExtra');
                        if (horaInput) {
                            horaInput.value = horaFinal;
                        }
                        
                        // Actualizar minutos desde inicio
                        document.getElementById('minutosDesdeInicio').textContent = minutosExtras;

                        // Cargar visitas del técnico
                        cargarVisitasTecnico(idUsuario, fechaStr, function(data) {
                            if (data && data.visitas) {
                                visitasData = data.visitas;
                                renderizarVisitasEnModal(data.visitas);
                                
                                // Actualizar resumen
                                document.getElementById('aprobarTotalVisitas').textContent = data.resumen.total_visitas;
                                document.getElementById('aprobarVisitasDespues17').textContent = data.resumen.visitas_despues_17;
                                document.getElementById('aprobarTiempoExtraVisitas').textContent = data.resumen.tiempo_extra_total;
                            } else {
                                document.getElementById('aprobarTotalVisitas').textContent = '0';
                                document.getElementById('aprobarVisitasDespues17').textContent = '0';
                                document.getElementById('aprobarTiempoExtraVisitas').textContent = '0';
                                renderizarVisitasEnModal([]);
                            }
                        });

                        document.getElementById('modalAprobar').classList.remove('hidden');
                        document.body.style.overflow = 'hidden';
                    } else {
                        toastr.error('No se pudo cargar la información');
                    }
                },
                error: function() {
                    toastr.error('Error al cargar los datos');
                }
            });
        }

        function cerrarModalAprobar() {
            document.getElementById('modalAprobar').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function confirmarAprobacion() {
            const horaFinalModificada = document.getElementById('horaFinalExtra').value;
            
            if (horaFinalModificada < '17:00') {
                toastr.error('La hora final no puede ser menor a 17:00');
                return;
            }

            if (!/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/.test(horaFinalModificada)) {
                toastr.error('Formato de hora inválido');
                return;
            }

            const [hora, minuto] = horaFinalModificada.split(':').map(Number);
            const minutosExtras = (hora - 17) * 60 + minuto;
            
            if (minutosExtras < 0) {
                toastr.error('La hora final debe ser mayor o igual a 17:00');
                return;
            }

            const year = fechaOriginalCompleta.getFullYear();
            const month = (fechaOriginalCompleta.getMonth() + 1).toString().padStart(2, '0');
            const day = fechaOriginalCompleta.getDate().toString().padStart(2, '0');
            const fechaOriginalStr = `${year}-${month}-${day}`;

            $.ajax({
                url: '/administracion/horasextras/aprobar',
                type: 'POST',
                data: {
                    idAprobacion: filaActualId,
                    hora_final_modificada: horaFinalModificada,
                    minutos_extras: minutosExtras,
                    fecha_original: fechaOriginalStr
                },
                headers: { 
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        cerrarModalAprobar();
                        cargarHorasExtras();
                        actualizarContadores();
                    } else {
                        toastr.error(response.message || 'Error al aprobar');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error respuesta:', xhr.responseText);
                    try {
                        const response = JSON.parse(xhr.responseText);
                        toastr.error(response.message || 'Error al procesar la aprobación');
                    } catch(e) {
                        toastr.error('Error al procesar la aprobación');
                    }
                }
            });
        }

        // ========== FUNCIONES PARA MODAL DE RECHAZAR ==========
        function mostrarModalRechazar(idAprobacion) {
            filaActualId = idAprobacion;
            
            $.ajax({
                url: `/administracion/horasextras/detalle/${idAprobacion}`,
                type: 'GET',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    if (response.success) {
                        datosOriginales = response.data;
                        
                        const fechaOriginal = new Date(datosOriginales.fechaHora_original);
                        const minutosExtras = datosOriginales.diferencia_minutos || 0;
                        
                        const horaFinal = fechaOriginal.toLocaleTimeString('es-ES', { 
                            hour: '2-digit', 
                            minute: '2-digit',
                            hour12: false 
                        });

                        document.getElementById('rechazarUsuario').textContent = datosOriginales.nombre_completo || '-';
                        document.getElementById('rechazarFecha').textContent = fechaOriginal.toLocaleDateString('es-ES');
                        document.getElementById('rechazarHoraFinal').textContent = horaFinal;
                        
                        const horas = Math.floor(minutosExtras / 60);
                        const minutos = minutosExtras % 60;
                        const tiempoFormateado = horas > 0 ? `${horas}h ${minutos}m` : `${minutos}m`;
                        document.getElementById('rechazarTiempo').textContent = tiempoFormateado;

                        document.getElementById('comentarioRechazar').value = '';

                        document.getElementById('modalRechazar').classList.remove('hidden');
                        document.body.style.overflow = 'hidden';
                    } else {
                        toastr.error('No se pudo cargar la información');
                    }
                },
                error: function() {
                    toastr.error('Error al cargar los datos');
                }
            });
        }

        function cerrarModalRechazar() {
            document.getElementById('modalRechazar').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function confirmarRechazo() {
            const comentario = document.getElementById('comentarioRechazar').value;

            $.ajax({
                url: '/administracion/horasextras/rechazar',
                type: 'POST',
                data: {
                    idAprobacion: filaActualId,
                    comentario: comentario
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        cerrarModalRechazar();
                        cargarHorasExtras();
                        actualizarContadores();
                    } else {
                        toastr.error(response.message || 'Error al rechazar');
                    }
                },
                error: function() {
                    toastr.error('Error al procesar el rechazo');
                }
            });
        }

        // ========== FUNCIONES PARA VER DETALLES ==========
        function mostrarDetalle(idAprobacion) {
            $.ajax({
                url: `/administracion/horasextras/detalle/${idAprobacion}`,
                type: 'GET',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    if (response.success) {
                        const data = response.data;
                        const fechaOriginal = new Date(data.fechaHora_original);
                        const fechaFormateada = fechaOriginal.toLocaleDateString('es-ES');
                        
                        const fechaMostrar = data.fechaHora_modificada ? new Date(data.fechaHora_modificada) : fechaOriginal;
                        
                        const horaInicioExtra = '17:00';
                        const horaFinalExtra = fechaMostrar.toLocaleTimeString('es-ES', { 
                            hour: '2-digit', 
                            minute: '2-digit',
                            hour12: false 
                        });
                        
                        const [hora, minuto] = horaFinalExtra.split(':').map(Number);
                        const minutosExtras = (hora - 17) * 60 + minuto;
                        
                        const horas = Math.floor(minutosExtras / 60);
                        const minutos = minutosExtras % 60;
                        const tiempoFormateado = horas > 0 ? `${horas}h ${minutos}m` : `${minutos}m`;

                        if (data.estado === 'APROBADO') {
                            document.getElementById('verUsuario').textContent = data.nombre_completo || '-';
                            document.getElementById('verFecha').textContent = fechaFormateada;
                            document.getElementById('verInicio').textContent = horaInicioExtra;
                            document.getElementById('verFinal').textContent = horaFinalExtra;
                            document.getElementById('verTiempoAprobado').textContent = tiempoFormateado;
                            document.getElementById('verAprobadoPor').textContent = data.revisado_por_nombre || 'Admin Sistema';
                            
                            const fechaRevision = data.revisado_at ? new Date(data.revisado_at) : new Date();
                            document.getElementById('verFechaEvaluacion').textContent = fechaRevision.toLocaleString('es-ES');
                            
                            document.getElementById('modalVerAprobacion').classList.remove('hidden');
                            
                        } else if (data.estado === 'DENEGADO') {
                            document.getElementById('verRechazoUsuario').textContent = data.nombre_completo || '-';
                            document.getElementById('verRechazoFecha').textContent = fechaFormateada;
                            document.getElementById('verRechazoInicio').textContent = horaInicioExtra;
                            document.getElementById('verRechazoFinal').textContent = horaFinalExtra;
                            document.getElementById('verRechazoTiempo').textContent = tiempoFormateado;
                            document.getElementById('verRechazadoPor').textContent = data.revisado_por_nombre || 'Admin Sistema';
                            
                            const fechaRevision = data.revisado_at ? new Date(data.revisado_at) : new Date();
                            document.getElementById('verRechazoFechaEvaluacion').textContent = fechaRevision.toLocaleString('es-ES');
                            
                            document.getElementById('verRechazoComentario').textContent = data.comentario || 'Sin comentario';
                            document.getElementById('verCorreccionMensaje').textContent = `Se corrigió la hora de salida a las 17:00`;
                            
                            document.getElementById('modalVerRechazo').classList.remove('hidden');
                        }
                        
                        document.body.style.overflow = 'hidden';
                    } else {
                        toastr.error('No se pudo cargar la información');
                    }
                },
                error: function() {
                    toastr.error('Error al cargar los detalles');
                }
            });
        }

        function cerrarModalVerAprobacion() {
            document.getElementById('modalVerAprobacion').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function cerrarModalVerRechazo() {
            document.getElementById('modalVerRechazo').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // ========== CERRAR MODALES CON ESC ==========
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (!document.getElementById('modalAprobar').classList.contains('hidden')) cerrarModalAprobar();
                if (!document.getElementById('modalRechazar').classList.contains('hidden')) cerrarModalRechazar();
                if (!document.getElementById('modalVerAprobacion').classList.contains('hidden')) cerrarModalVerAprobacion();
                if (!document.getElementById('modalVerRechazo').classList.contains('hidden')) cerrarModalVerRechazo();
            }
        });

        // ========== CERRAR MODALES AL HACER CLIC FUERA ==========
        document.getElementById('modalAprobar')?.addEventListener('click', function(e) {
            if (e.target.id === 'modalAprobar') cerrarModalAprobar();
        });

        document.getElementById('modalRechazar')?.addEventListener('click', function(e) {
            if (e.target.id === 'modalRechazar') cerrarModalRechazar();
        });

        document.getElementById('modalVerAprobacion')?.addEventListener('click', function(e) {
            if (e.target.id === 'modalVerAprobacion') cerrarModalVerAprobacion();
        });

        document.getElementById('modalVerRechazo')?.addEventListener('click', function(e) {
            if (e.target.id === 'modalVerRechazo') cerrarModalVerRechazo();
        });
    </script>
</x-layout.default>