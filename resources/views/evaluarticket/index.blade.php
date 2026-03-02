<x-layout.default>

    {{-- Flatpickr CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    {{-- Toastr CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <div class="container-fluid py-4">
        <!-- Breadcrumb actualizado -->
        <div class="mb-6">
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="" class="text-primary hover:underline">
                        <i class="fas fa-clipboard-check me-1"></i>Dashboard
                    </a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span class="text-gray-500">Evaluar Tickets</span>
                </li>
            </ul>
        </div>

        <!-- Header actualizado -->
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
                            data-status="pendiente" id="filterPendiente">
                            <i class="fas fa-clock mr-1"></i>
                            Pendiente
                        </button>

                        <button class="px-3 py-1.5 text-sm font-medium rounded-lg transition-colors filter-btn"
                            data-status="evaluado" id="filterEvaluado">
                            <i class="fas fa-check-circle mr-1"></i>
                            Evaluado
                        </button>

                        <button class="px-3 py-1.5 text-sm font-medium rounded-lg transition-colors filter-btn"
                            data-status="aprobado" id="filterAprobado">
                            <i class="fas fa-thumbs-up mr-1"></i>
                            Aprobado
                        </button>

                        <button class="px-3 py-1.5 text-sm font-medium rounded-lg transition-colors filter-btn"
                            data-status="rechazado" id="filterRechazado">
                            <i class="fas fa-thumbs-down mr-1"></i>
                            Rechazado
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

                <!-- TABLA MODIFICADA - IGUAL QUE EN REACT -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="evaluarTicketsTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    N° Ticket
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Cliente
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

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    {{-- Flatpickr JS --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    {{-- Toastr JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    {{-- Bootstrap JS (para tooltips) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Tu archivo JS personalizado --}}
    <script src="{{ asset('assets/js/evaluarticket/evaluarticket.js') }}"></script>

</x-layout.default>
