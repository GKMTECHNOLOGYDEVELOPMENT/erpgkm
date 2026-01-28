<x-layout.default>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            height: 48px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 11px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 26px;
            padding-left: 10px;
            /* Añadí padding para el icono */
        }

        .select2-container--default .select2-selection--single:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
        }

        .select2-container .select2-selection--single .select2-selection__placeholder {
            color: #9ca3af;
        }

        /* Estilos adicionales para mejor apariencia */
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #6b7280 transparent transparent transparent;
            border-width: 6px 6px 0 6px;
        }

        .select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent #6b7280 transparent;
            border-width: 0 6px 6px 6px;
        }
    </style>
    <div class="min-h-screen bg-gray-50">
        <!-- Main Container -->
        <div class="mx-auto w-full px-4 py-6">
            <!-- Breadcrumb -->
            <div class="mb-4">
                <ul class="flex space-x-2 rtl:space-x-reverse">
                    <li>
                        <a href="" class="text-blue-600 hover:underline">
                            Almacén
                        </a>
                    </li>
                    <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                        <span class="text-gray-600">Repuestos Harvest</span>
                    </li>
                </ul>
            </div>

            <!-- Header -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6 p-5">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                    <div class="flex items-center space-x-4">
                        <div
                            class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-sm">
                            <i class="fas fa-warehouse text-white text-lg"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Repuestos Harvest</h1>
                            <p class="text-gray-600">Gestión de retiros de repuestos en custodia</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('harvest.export') }}?{{ http_build_query(request()->query()) }}"
                            id="exportLink"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-br from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white text-sm font-medium rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                            <i class="fas fa-file-excel mr-2"></i>
                            Exportar Excel
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Repuestos -->
                <div class="bg-gradient-to-br from-blue-50 to-white rounded-xl border border-blue-100 p-6 shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 cursor-pointer"
                    onclick="clearFilters()">
                    <div class="flex items-center gap-4">
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-4 rounded-xl shadow-sm">
                            <i class="fas fa-boxes text-white text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-3xl font-bold text-gray-800" id="totalRepuestos">{{ $totalRepuestos }}
                            </div>
                            <div class="text-gray-600 text-sm font-medium">Total Repuestos</div>
                        </div>
                    </div>
                    <div class="mt-5 pt-4 border-t border-blue-100">
                        <div class="text-xs text-blue-600 font-semibold flex items-center gap-1">
                            <i class="fas fa-sync-alt text-xs"></i>
                            <span>Mostrar todos</span>
                        </div>
                    </div>
                </div>

                <!-- Unidades Retiradas -->
                <div class="bg-gradient-to-br from-orange-50 to-white rounded-xl border border-orange-100 p-6 shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 cursor-pointer"
                    onclick="filterByCategory('retirados')">
                    <div class="flex items-center gap-4">
                        <div class="bg-warning p-4 rounded-xl shadow-sm">
                            <i class="fas fa-calendar-check text-white text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-3xl font-bold text-gray-800" id="totalUnidades">{{ $totalUnidades }}</div>
                            <div class="text-gray-600 text-sm font-medium">Unidades Retiradas</div>
                        </div>
                    </div>
                    <div class="mt-5 pt-4 border-t border-orange-100">
                        <div class="text-xs text-orange-600 font-semibold flex items-center gap-1">
                            <i class="fas fa-check-circle text-xs"></i>
                            <span>Retiros registrados</span>
                        </div>
                    </div>
                </div>

                <!-- Disponibles -->
                <div class="bg-gradient-to-br from-green-50 to-white rounded-xl border border-green-100 p-6 shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 cursor-pointer"
                    onclick="filterByCategory('disponibles')">
                    <div class="flex items-center gap-4">
                        <div class="bg-gradient-to-br from-green-500 to-green-600 p-4 rounded-xl shadow-sm">
                            <i class="fas fa-check-double text-white text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-3xl font-bold text-gray-800">{{ $totalRepuestos - $totalUnidades }}</div>
                            <div class="text-gray-600 text-sm font-medium">Disponibles</div>
                        </div>
                    </div>
                    <div class="mt-5 pt-4 border-t border-green-100">
                        <div class="text-xs text-green-600 font-semibold flex items-center gap-1">
                            <i class="fas fa-thumbs-up text-xs"></i>
                            <span>Listos para retirar</span>
                        </div>
                    </div>
                </div>

                <!-- Último Mes -->
                <div class="bg-gradient-to-br from-purple-50 to-white rounded-xl border border-purple-100 p-6 shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 cursor-pointer"
                    onclick="filterByCategory('reciente')">
                    <div class="flex items-center gap-4">
                        <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-4 rounded-xl shadow-sm">
                            <i class="fas fa-chart-line text-white text-lg"></i>
                        </div>
                        <div class="flex-1">
                            @php
                                $ultimoMes = 0; // Aquí deberías calcular las unidades retiradas en el último mes
                            @endphp
                            <div class="text-3xl font-bold text-gray-800">{{ $ultimoMes }}</div>
                            <div class="text-gray-600 text-sm font-medium">Último Mes</div>
                        </div>
                    </div>
                    <div class="mt-5 pt-4 border-t border-purple-100">
                        <div class="text-xs text-purple-600 font-semibold flex items-center gap-1">
                            <i class="fas fa-trending-up text-xs"></i>
                            <span>Tendencia reciente</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-2 rounded-lg">
                            <i class="fas fa-filter text-white text-sm"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Filtros de Búsqueda</h3>
                            <p class="text-xs text-gray-500 mt-1">Busca y filtra repuestos específicos</p>
                        </div>
                    </div>
                    <button onclick="clearFilters()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all duration-200 flex items-center gap-2">
                        <i class="fas fa-times text-gray-500"></i>
                        Limpiar Filtros
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Buscar -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                            <i class="fas fa-search text-blue-500 text-sm"></i>
                            Buscar repuesto
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" id="searchInput"
                                class="w-full pl-10 pr-10 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200"
                                placeholder="Código, modelo..." value="{{ request('search') }}"
                                onkeyup="debounceSearch()">
                            <button id="clearSearch"
                                onclick="document.getElementById('searchInput').value = ''; applyFilters()"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center hidden">
                                <i class="fas fa-times text-gray-400 hover:text-gray-600 cursor-pointer"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Subcategoría con Select2 -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                            <i class="fas fa-layer-group text-blue-500 text-sm"></i>
                            Subcategoría
                        </label>
                        <select id="subcategoriaSelect" class="w-full">
                            <option value="todas">Todas las categorías</option>
                            @foreach ($subcategorias as $subcat)
                                <option value="{{ $subcat->id }}"
                                    {{ request('subcategoria') == $subcat->id ? 'selected' : '' }}>
                                    {{ $subcat->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>

            <!-- Loading Indicator -->
            <div id="loadingIndicator" class="hidden mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Cargando datos...</h3>
                    <p class="text-gray-600">Por favor, espere un momento</p>
                </div>
            </div>

            <!-- Table Container -->
            <div id="tableContainer">
                @include('almacen.harvest.partials.table', ['repuestos' => $repuestos])
            </div>

            <!-- Empty State (oculto por defecto) -->
            <div id="emptyState" class="hidden mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <i class="fas fa-inbox text-gray-300 text-4xl mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">No se encontraron repuestos</h3>
                    <p class="text-gray-600 mb-6" id="emptyStateMessage">No hay repuestos que coincidan con los
                        filtros aplicados</p>
                    <button onclick="clearFilters()"
                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                        <i class="fas fa-sync-alt"></i>
                        Mostrar todos
                    </button>
                </div>
            </div>
        </div>
    </div>

<div x-data="{ open: false }" x-on:open-detalle.window="open = true" x-on:close-detalle.window="open = false">
    <!-- Overlay y contenido juntos en x-show -->
    <div x-show="open" x-transition.opacity.duration.300ms x-cloak
         class="fixed inset-0 bg-[black]/60 z-[999] overflow-y-auto">

        <div class="flex items-start justify-center min-h-screen px-4" @click.self="$dispatch('close-detalle')">

            <!-- Panel -->
            <div x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-6xl bg-white shadow-xl">

                <!-- Header -->
                <div class="flex items-center justify-between px-5 py-3 bg-primary border-b">
                    <div class="font-bold text-lg text-white">
                        Detalles
                    </div>
                    <button type="button" class="text-white hover:text-red-500" @click="$dispatch('close-detalle')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Content -->
                <div id="modalContent" class="p-6">
                    <!-- contenido dinámico -->
                </div>

                <!-- Footer opcional -->
                <div class="flex justify-end gap-2 px-6 py-4 border-t bg-gray-50">
                    <button class="btn btn-outline-danger" @click="$dispatch('close-detalle')">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Inicializar Select2
        $('#subcategoriaSelect').select2({
            placeholder: "Selecciona una subcategoría",
            allowClear: true,
            language: "es",
            width: '100%',
            dropdownParent: $('#subcategoriaSelect').parent(),
            theme: 'default'
        });

        // Establecer valor inicial desde URL
        @if (request('subcategoria'))
            $('#subcategoriaSelect').val('{{ request('subcategoria') }}').trigger('change');
        @endif

        // Escuchar cambios en Select2
        $('#subcategoriaSelect').on('select2:select', function(e) {
            applyFilters();
        });

        $('#subcategoriaSelect').on('select2:clear', function(e) {
            $(this).val('todas').trigger('change');
            applyFilters();
        });
    });

    let debounceTimer;

    function debounceSearch() {
        const searchInput = document.getElementById('searchInput');
        const clearBtn = document.getElementById('clearSearch');

        if (searchInput && searchInput.value.length > 0) {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }

        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            applyFilters();
        }, 500);
    }

    function applyFilters() {
        const searchInput = document.getElementById('searchInput');
        const subcategoriaSelect = document.getElementById('subcategoriaSelect');
        
        if (!searchInput || !subcategoriaSelect) {
            console.error('Elementos de filtro no encontrados');
            return;
        }
        
        const search = searchInput.value;
        const subcategoria = subcategoriaSelect.value;

        // Mostrar loading
        const loadingIndicator = document.getElementById('loadingIndicator');
        const emptyState = document.getElementById('emptyState');
        
        if (loadingIndicator) loadingIndicator.classList.remove('hidden');
        if (emptyState) emptyState.classList.add('hidden');

        // Actualizar URL sin recargar
        updateUrlParams({
            search,
            subcategoria
        });

        // Actualizar enlace de exportación
        updateExportLink(search, subcategoria);

        // Hacer petición AJAX
        fetchData(search, subcategoria);
    }

    function clearFilters() {
        const searchInput = document.getElementById('searchInput');
        const clearSearch = document.getElementById('clearSearch');
        
        if (searchInput) searchInput.value = '';
        if (clearSearch) clearSearch.classList.add('hidden');
        
        // Resetear Select2 usando jQuery
        $('#subcategoriaSelect').val('todas').trigger('change');

        // Limpiar URL
        history.pushState({}, '', '{{ route('harvest.index') }}');

        // Restaurar enlace de exportación original
        const exportLink = document.getElementById('exportLink');
        if (exportLink) exportLink.href = '{{ route('harvest.export') }}';

        applyFilters();
    }

    function filterByCategory(category) {
        // Puedes implementar filtros específicos por categoría aquí
        console.log('Filtrando por categoría:', category);
        applyFilters();
    }

    function fetchData(search = '', subcategoria = 'todas', page = 1) {
        // Construir URL con parámetros
        let url = '{{ route('harvest.index') }}?ajax=1';

        if (search) {
            url += `&search=${encodeURIComponent(search)}`;
        }

        if (subcategoria && subcategoria !== 'todas') {
            url += `&subcategoria=${subcategoria}`;
        }

        if (page > 1) {
            url += `&page=${page}`;
        }

        fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                // Actualizar tabla
                const tableContainer = document.getElementById('tableContainer');
                if (tableContainer && data.html) {
                    tableContainer.innerHTML = data.html;
                }

                // Actualizar estadísticas
                const totalRepuestos = document.getElementById('totalRepuestos');
                const totalUnidades = document.getElementById('totalUnidades');
                
                if (totalRepuestos && data.totalRepuestos !== undefined) {
                    totalRepuestos.textContent = data.totalRepuestos;
                }
                if (totalUnidades && data.totalUnidades !== undefined) {
                    totalUnidades.textContent = data.totalUnidades;
                }

                // Verificar si hay datos
                const emptyState = document.getElementById('emptyState');
                const emptyStateMessage = document.getElementById('emptyStateMessage');
                
                if (data.totalRepuestos == 0) {
                    if (emptyState) emptyState.classList.remove('hidden');
                    if (emptyStateMessage) {
                        const message = search || subcategoria !== 'todas' ?
                            'No hay repuestos que coincidan con los filtros aplicados' :
                            'No hay repuestos registrados en custodia';
                        emptyStateMessage.textContent = message;
                    }
                } else {
                    if (emptyState) emptyState.classList.add('hidden');
                }

                // Ocultar loading
                const loadingIndicator = document.getElementById('loadingIndicator');
                if (loadingIndicator) loadingIndicator.classList.add('hidden');

                // Agregar event listeners a la paginación
                setupPagination();
            })
            .catch(error => {
                console.error('Error:', error);
                
                const loadingIndicator = document.getElementById('loadingIndicator');
                if (loadingIndicator) loadingIndicator.classList.add('hidden');

                // Mostrar error
                const tableContainer = document.getElementById('tableContainer');
                if (tableContainer) {
                    tableContainer.innerHTML = `
                    <div class="bg-red-50 border-l-4 border-red-400 rounded-lg p-6">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-red-400 text-xl mr-3"></i>
                            <div>
                                <h3 class="text-lg font-medium text-red-800">Error al cargar datos</h3>
                                <p class="text-red-600">Intente nuevamente en unos momentos.</p>
                            </div>
                        </div>
                    </div>
                `;
                }
            });
    }

    function setupPagination() {
        // Agregar event listeners a los enlaces de paginación
        document.querySelectorAll('.pagination a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                const url = new URL(this.href);
                const page = url.searchParams.get('page') || 1;
                const searchInput = document.getElementById('searchInput');
                const subcategoriaSelect = document.getElementById('subcategoriaSelect');
                
                const search = searchInput ? searchInput.value : '';
                const subcategoria = subcategoriaSelect ? subcategoriaSelect.value : 'todas';

                const loadingIndicator = document.getElementById('loadingIndicator');
                if (loadingIndicator) loadingIndicator.classList.remove('hidden');
                
                fetchData(search, subcategoria, page);
            });
        });
    }

    function updateUrlParams(params) {
        const url = new URL(window.location);

        if (params.search) {
            url.searchParams.set('search', params.search);
        } else {
            url.searchParams.delete('search');
        }

        if (params.subcategoria && params.subcategoria !== 'todas') {
            url.searchParams.set('subcategoria', params.subcategoria);
        } else {
            url.searchParams.delete('subcategoria');
        }

        // Actualizar URL sin recargar
        history.pushState({}, '', url);
    }

    function updateExportLink(search, subcategoria) {
        let exportUrl = '{{ route('harvest.export') }}';
        const params = [];

        if (search) {
            params.push(`search=${encodeURIComponent(search)}`);
        }

        if (subcategoria && subcategoria !== 'todas') {
            params.push(`subcategoria=${subcategoria}`);
        }

        if (params.length > 0) {
            exportUrl += '?' + params.join('&');
        }

        const exportLink = document.getElementById('exportLink');
        if (exportLink) exportLink.href = exportUrl;
    }

    function verDetalle(idArticulo) {
        // 🔥 abrir modal Alpine
        window.dispatchEvent(new Event('open-detalle'));

        // loading
        const modalContent = document.getElementById('modalContent');
        if (modalContent) {
            modalContent.innerHTML = `
        <div class="flex flex-col items-center justify-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4"></div>
            <p class="text-gray-600">Cargando detalles del repuesto...</p>
        </div>
    `;
        }

        fetch(`/almacen/harvest/${idArticulo}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(r => {
                if (!r.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return r.text();
            })
            .then(html => {
                const modalContent = document.getElementById('modalContent');
                if (modalContent) modalContent.innerHTML = html;
            })
            .catch(() => {
                const modalContent = document.getElementById('modalContent');
                if (modalContent) {
                    modalContent.innerHTML = `
            <div class="text-red-600 text-center p-6">
                Error al cargar los detalles
            </div>
        `;
                }
            });
    }

    function closeModal() {
        window.dispatchEvent(new Event('close-detalle'));
    }

    // Inicializar filtros desde URL
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const search = urlParams.get('search');
        const subcategoria = urlParams.get('subcategoria');

        const searchInput = document.getElementById('searchInput');
        const clearSearch = document.getElementById('clearSearch');
        
        if (search && searchInput) {
            searchInput.value = search;
            if (clearSearch) clearSearch.classList.remove('hidden');
        }

        // Configurar el enlace de exportación inicial
        updateExportLink(
            searchInput ? searchInput.value : '',
            document.getElementById('subcategoriaSelect') ? document.getElementById('subcategoriaSelect').value : 'todas'
        );
    });
</script>
</x-layout.default>
