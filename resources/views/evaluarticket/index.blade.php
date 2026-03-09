{{-- resources/views/evaluarticket/index.blade.php --}}
<x-layout.default>

    {{-- Flatpickr CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    {{-- Toastr CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* Estilos para los modales */
        #ticketModal, #imageModal {
            z-index: 9999;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        #modalContent img {
            transition: transform 0.2s;
        }
        
        #modalContent img:hover {
            transform: scale(1.02);
        }
        
        /* Scroll personalizado para el modal */
        #ticketModal .overflow-y-auto::-webkit-scrollbar {
            width: 8px;
        }
        
        #ticketModal .overflow-y-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        #ticketModal .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        
        #ticketModal .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        /* Estilo para el avatar del cliente general */
        .cliente-general-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e5e7eb;
        }
        
        .cliente-general-iniciales {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #4361ee;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
            border: 2px solid #e5e7eb;
        }
    </style>

    <div class="container-fluid py-4">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="{{ url('/dashboard') }}" class="text-primary hover:underline">
                        <i class="fas fa-clipboard-check me-1"></i>Dashboard
                    </a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span class="text-gray-500">Evaluar Tickets</span>
                </li>
            </ul>
        </div>

        <!-- Header -->
        <div class="bg-white rounded-2xl border border-gray-200 p-5 mb-6 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        <i class="fas fa-clipboard-check text-blue-600 mr-2"></i>
                        Evaluar Tickets
                    </h1>
                    <p class="text-gray-600 mt-2">
                        <i class="fas fa-info-circle text-gray-400 mr-1"></i>
                        Gestiona y evalúa los tickets de soporte técnico
                    </p>
                </div>
                <div class="flex gap-2">
                    <button id="refreshData" class="btn btn-outline-primary flex items-center gap-2">
                        <i class="fas fa-sync-alt"></i>
                        Actualizar
                    </button>
                </div>
            </div>
        </div>

        <!-- Filtros de fecha -->
        <div class="bg-white rounded-lg shadow-sm mb-4 p-4">
            <div class="flex items-center gap-4 flex-wrap">
                <span class="text-gray-600 flex items-center gap-1">
                    <i class="fas fa-calendar-alt"></i>
                    Filtrar por fecha:
                </span>

                <!-- Fecha inicio -->
                <div class="relative">
                    <input type="text" id="startDate" class="form-input pl-8 w-40" placeholder="Fecha inicial">
                    <i class="fas fa-calendar-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                </div>

                <span class="text-gray-400">-</span>

                <!-- Fecha fin -->
                <div class="relative">
                    <input type="text" id="endDate" class="form-input pl-8 w-40" placeholder="Fecha final">
                    <i class="fas fa-calendar-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                </div>

                <!-- Botón limpiar filtros -->
                <button id="clearDates" class="btn btn-link text-danger hidden items-center gap-1">
                    <i class="fas fa-times"></i>
                    Limpiar fechas
                </button>

                <!-- Indicador de filtro activo -->
                <span id="dateFilterBadge" class="badge bg-info hidden">Filtro por fechas activo</span>
            </div>
        </div>

        <!-- Panel de filtros y tabla -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-4">
                <!-- Filtros de estado y búsqueda -->
                <div class="flex flex-wrap items-center justify-between mb-4">
                    <!-- Filtros de estado -->
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-gray-600 flex items-center gap-1">
                            <i class="fas fa-filter"></i>
                            Estado:
                        </span>

                        <button class="px-3 py-1.5 text-sm font-medium rounded-lg transition-colors filter-btn"
                            data-status="todos" id="filterTodos">
                            Todos
                        </button>

                        <button class="px-3 py-1.5 text-sm font-medium rounded-lg transition-colors filter-btn"
                            data-status="evaluando" id="filterEvaluando">
                            <i class="fas fa-search mr-1"></i>
                            Evaluando
                        </button>

                        <button class="px-3 py-1.5 text-sm font-medium rounded-lg transition-colors filter-btn"
                            data-status="gestionando" id="filterGestionando">
                            <i class="fas fa-tools mr-1"></i>
                            Gestionando
                        </button>

                        <button class="px-3 py-1.5 text-sm font-medium rounded-lg transition-colors filter-btn"
                            data-status="finalizado" id="filterFinalizado">
                            <i class="fas fa-check-double mr-1"></i>
                            Finalizado
                        </button>
                    </div>

                    <!-- Buscador -->
                    <div class="flex items-center bg-white border rounded-lg p-1">
                        <i class="fas fa-search text-gray-400 ml-2"></i>
                        <input type="text" id="searchInput" class="form-input border-0 focus:ring-0 w-64"
                            placeholder="Buscar por ticket, cliente, producto...">
                        <button id="clearSearch" class="text-gray-400 hover:text-gray-600 px-2 hidden">×</button>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="evaluarTicketsTable">
                      <thead class="bg-gray-50">
    <tr>
        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
            N° Ticket
        </th>
        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
            Cliente General
        </th>
        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
            Contacto
        </th>
        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
            Producto
        </th>
        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
            Fecha
        </th>
        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
            Estado
        </th>
        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
            Acciones
        </th>
    </tr>
</thead>
                        <tbody id="evaluarTicketsTableBody" class="bg-white divide-y divide-gray-200">
                            <!-- Los datos se cargarán vía JavaScript -->
                            <tr>
                                <td colspan="7" class="px-4 py-10 text-center text-gray-500">
                                    <div class="flex justify-center items-center">
                                        <i class="fas fa-spinner fa-spin text-3xl text-primary mr-3"></i>
                                        <p>Cargando tickets para evaluación...</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="flex justify-between items-center mt-4">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Mostrar</span>
                        <select id="perPage" class="form-select text-sm w-16">
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        <span class="text-sm text-gray-600">registros</span>
                    </div>
                    <nav aria-label="Page navigation">
                        <ul class="flex items-center gap-1" id="pagination"></ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para ver detalles del ticket -->
    <div id="ticketModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
                <!-- Cabecera del modal -->
                <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700 sticky top-0 bg-white dark:bg-gray-800 z-10">
                    <h3 class="text-lg font-semibold flex items-center gap-2">
                        <i class="fas fa-clipboard-check text-primary"></i>
                        Detalles del Ticket
                    </h3>
                    <button onclick="cerrarModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Contenido del modal -->
                <div class="p-6" id="modalContent">
                    <!-- El contenido se cargará dinámicamente con JavaScript -->
                    <div class="flex justify-center items-center py-10">
                        <i class="fas fa-spinner fa-spin text-3xl text-primary"></i>
                        <span class="ml-3">Cargando detalles...</span>
                    </div>
                </div>

                <!-- Pie del modal -->
                <div class="flex items-center justify-end gap-2 p-4 border-t border-gray-200 dark:border-gray-700 sticky bottom-0 bg-white dark:bg-gray-800">
                    <button onclick="cerrarModal()" class="btn btn-outline-danger">
                        <i class="fas fa-times mr-1"></i>
                        Cerrar
                    </button>
                    <button class="btn btn-primary evaluate-from-modal" style="display: none;">
                        <i class="fas fa-clipboard-check mr-1"></i>
                        Evaluar Ticket
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para imagen ampliada -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 z-[60] hidden" style="display: none;">
        <div class="flex items-center justify-center h-full p-4">
            <div class="relative max-w-4xl w-full max-h-[90vh] flex items-center justify-center">
                <button onclick="cerrarImageModal()" class="absolute top-4 right-4 bg-white text-gray-800 rounded-full w-10 h-10 flex items-center justify-center shadow-lg hover:bg-gray-100 transition-colors z-10">
                    <i class="fas fa-times text-xl"></i>
                </button>
                <img id="ampliadaImagen" src="" alt="Vista ampliada" class="max-w-full max-h-[90vh] object-contain rounded-lg">
            </div>
        </div>
    </div>

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    {{-- Flatpickr JS --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    {{-- Toastr JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    {{-- Script personalizado --}}
    <script src="{{ asset('assets/js/evaluarticket/evaluarticket.js') }}"></script>

</x-layout.default>