<x-layout.default>

    {{-- Flatpickr CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    {{-- Toastr CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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
        <div class="panel rounded-2xl border border-gray-200 p-5 mb-6 shadow-sm">
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
        <div class="panel rounded-lg shadow-sm mb-4 p-4">
            <div class="flex items-center gap-4 flex-wrap">
                <span class="text-gray-600 flex items-center gap-1">
                    <i class="fas fa-calendar-alt"></i>
                    Filtrar por fecha:
                </span>

                <!-- Fecha inicio -->
                <div class="relative">
                    <input type="text" id="startDate" class="form-input pl-8 w-40" placeholder="Fecha inicial">
                    <i
                        class="fas fa-calendar-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                </div>

                <span class="text-gray-400">-</span>

                <!-- Fecha fin -->
                <div class="relative">
                    <input type="text" id="endDate" class="form-input pl-8 w-40" placeholder="Fecha final">
                    <i
                        class="fas fa-calendar-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
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
        <div class="panel rounded-lg shadow-sm">
            <div class="p-4">
                <!-- Filtros de estado y búsqueda -->
                <div class="flex flex-wrap items-center justify-between mb-4">
                    <!-- Filtros de estado -->
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-gray-600 flex items-center gap-1">
                            <i class="fas fa-filter"></i>
                            Estado:
                        </span>

                        <!-- Botón TODOS (se mantiene igual) -->
                        <button
                            class="px-3 py-1.5 text-sm font-medium rounded-lg transition-all duration-200 filter-btn bg-gray-100 text-gray-700 hover:bg-gray-200"
                            data-status="todos" id="filterTodos">
                            Todos
                        </button>

                        <!-- Botón EVALUANDO - Color púrpura -->
                        <button
                            class="px-3 py-1.5 text-sm font-medium rounded-lg transition-all duration-200 filter-btn bg-purple-100 text-purple-800 hover:bg-purple-200 dark:bg-purple-900/30 dark:text-purple-300 dark:hover:bg-purple-900/50"
                            data-status="evaluando" id="filterEvaluando">
                            <i class="fas fa-search mr-1"></i>
                            Evaluando
                        </button>

                        <!-- Botón GESTIONANDO - Color azul -->
                        <button
                            class="px-3 py-1.5 text-sm font-medium rounded-lg transition-all duration-200 filter-btn bg-blue-100 text-blue-800 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:hover:bg-blue-900/50"
                            data-status="gestionando" id="filterGestionando">
                            <i class="fas fa-tools mr-1"></i>
                            Gestionando
                        </button>

                        <!-- Botón FINALIZADO - Color verde -->
                        <button
                            class="px-3 py-1.5 text-sm font-medium rounded-lg transition-all duration-200 filter-btn bg-green-100 text-green-800 hover:bg-green-200 dark:bg-green-900/30 dark:text-green-300 dark:hover:bg-green-900/50"
                            data-status="finalizado" id="filterFinalizado">
                            <i class="fas fa-check-double mr-1"></i>
                            Finalizado
                        </button>
                    </div>

                    <!-- Buscador (se mantiene igual) -->
                    <div class="flex items-center border rounded-lg p-1">
                        <i class="fas fa-search text-gray-400 ml-2"></i>
                        <input type="text" id="searchInput" class="form-input border-0 focus:ring-0 w-64"
                            placeholder="Buscar por ticket, cliente, producto...">
                        <button id="clearSearch" class="text-gray-400 hover:text-gray-600 px-2 hidden">×</button>
                    </div>
                </div>

                <!-- Tabla - CON SOPORTE PARA MODO DARK -->
                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" id="evaluarTicketsTable">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th
                                    class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    N° Ticket
                                </th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Cliente General
                                </th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Contacto
                                </th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Producto
                                </th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Fecha
                                </th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody id="evaluarTicketsTableBody"
                            class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            <!-- Los datos se cargarán vía JavaScript -->
                            <tr>
                                <td colspan="7" class="px-4 py-10 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <i
                                            class="fas fa-spinner fa-spin text-3xl text-primary dark:text-primary-light mb-3"></i>
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

    <!-- Modal para ver detalles del ticket - CON CIERRE AL HACER CLIC FUERA -->
    <div id="ticketModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden overflow-y-auto"
        style="display: none;">
        <!-- Fondo que cierra al hacer clic -->
        <div class="absolute inset-0" onclick="cerrarModal()"></div>

        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto relative"
                onclick="event.stopPropagation()">
                <!-- Cabecera del modal -->
                <div
                    class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700 sticky top-0 bg-white dark:bg-gray-800 z-10">
                    <h3 class="text-lg font-semibold flex items-center gap-2">
                        <i class="fas fa-clipboard-check text-primary"></i>
                        Detalles del Ticket
                    </h3>
                    <button onclick="cerrarModal()"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
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
                <div
                    class="flex items-center justify-end gap-2 p-4 border-t border-gray-200 dark:border-gray-700 sticky bottom-0 bg-white dark:bg-gray-800">
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

    <!-- Modal para imagen ampliada - CORREGIDO: imagen COMPLETA y SIN ZOOM -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center"
        style="display: none;">
        <!-- Fondo que cierra al hacer clic -->
        <div class="absolute inset-0" onclick="cerrarImageModal()"></div>

        <!-- Contenedor de la imagen - SIN LÍMITES DE TAMAÑO ESTRICTOS -->
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-[95vw] max-h-[95vh] overflow-hidden">
            <!-- Botón de cerrar - FUERA del padding de la imagen -->
            <button onclick="cerrarImageModal()"
                class="absolute -top-3 -right-3 bg-danger text-white rounded-full w-10 h-10 flex items-center justify-center shadow-lg hover:bg-danger/90 transition-colors z-20 border-2 border-white">
                <i class="fas fa-times text-white text-xl"></i>
            </button>

            <!-- Contenedor de la imagen - SIN PADDING QUE LA RECORTE -->
            <div class="flex items-center justify-center bg-white dark:bg-gray-800 p-1">
                <img id="ampliadaImagen" src="" alt="Vista ampliada"
                    class="max-w-[90vw] max-h-[90vh] w-auto h-auto object-contain">
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
