<x-layout.default>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 shadow-lg">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center">
                        <div class="bg-white p-2 rounded-lg shadow-md mr-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-white">Repuestos Harvest</h1>
                            <p class="text-blue-100 mt-1">Gestión de retiros de repuestos en custodia</p>
                        </div>
                    </div>
                    
                    <!-- Stats Cards -->
                    <div class="mt-4 sm:mt-0 grid grid-cols-2 gap-4">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3 border border-white/30">
                            <div class="flex items-center">
                                <div class="bg-white/20 p-2 rounded-full mr-3">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-blue-100">Total Repuestos</p>
                                    <p class="text-2xl font-bold text-white" id="totalRepuestos">{{ $totalRepuestos }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3 border border-white/30">
                            <div class="flex items-center">
                                <div class="bg-white/20 p-2 rounded-full mr-3">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-blue-100">Unidades Retiradas</p>
                                    <p class="text-2xl font-bold text-white" id="totalUnidades">{{ $totalUnidades }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <!-- Filters Card -->
            <div class="bg-white rounded-xl shadow-lg mb-6 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-800">Filtros de Búsqueda</h2>
                        <button onclick="toggleFilters()" class="text-blue-600 hover:text-blue-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div id="filtersContainer" class="px-6 py-4">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Search -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                        Buscar repuesto
                                    </div>
                                </label>
                                <div class="relative">
                                    <input type="text" 
                                           id="searchInput"
                                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                           placeholder="Código, modelo..."
                                           value="{{ request('search') }}"
                                           onkeyup="debounceSearch()">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Subcategory Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                        Subcategoría
                                    </div>
                                </label>
                                <select id="subcategoriaSelect" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                        onchange="applyFilters()">
                                    <option value="todas">
                                        Todas las categorías
                                    </option>
                                    @foreach($subcategorias as $subcat)
                                        <option value="{{ $subcat->id }}">
                                            {{ $subcat->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-end space-x-3">
                                <!-- <button type="button" 
                                        onclick="applyFilters()"
                                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    Buscar
                                </button> -->
                                
                                <button type="button" 
                                        onclick="clearFilters()"
                                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Limpiar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading Indicator -->
            <div id="loadingIndicator" class="hidden mb-6">
                <div class="flex items-center justify-center bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600 mr-3"></div>
                    <p class="text-blue-700 font-medium">Cargando datos...</p>
                </div>
            </div>

            <!-- Export Button -->
            <div class="mb-6 flex justify-end">
                <a href="{{ route('harvest.export') }}?{{ http_build_query(request()->query()) }}" 
                   id="exportLink"
                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Exportar Excel
                </a>
            </div>

            <!-- Table Card -->
            <div id="tableContainer">
                @include('almacen.harvest.partials.table', ['repuestos' => $repuestos])
            </div>
        </div>
    </div>

    <!-- Modal para detalles -->
    <div id="detalleModal" class="fixed inset-0 overflow-y-auto z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal()"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full">
                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <button type="button" onclick="closeModal()" class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <div id="modalContent" class="p-6">
                    <!-- Contenido dinámico -->
                </div>
            </div>
        </div>
    </div>

    <script>
        let debounceTimer;
        
        function toggleFilters() {
            const container = document.getElementById('filtersContainer');
            container.classList.toggle('hidden');
        }
        
        function debounceSearch() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                applyFilters();
            }, 500); // 500ms de delay
        }
        
        function applyFilters() {
            const search = document.getElementById('searchInput').value;
            const subcategoria = document.getElementById('subcategoriaSelect').value;
            
            // Mostrar loading
            document.getElementById('loadingIndicator').classList.remove('hidden');
            
            // Actualizar URL sin recargar
            updateUrlParams({ search, subcategoria });
            
            // Actualizar enlace de exportación
            updateExportLink(search, subcategoria);
            
            // Hacer petición AJAX
            fetchData(search, subcategoria);
        }
        
        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('subcategoriaSelect').value = 'todas';
            
            // Limpiar URL
            history.pushState({}, '', '{{ route("harvest.index") }}');
            
            // Restaurar enlace de exportación original
            document.getElementById('exportLink').href = '{{ route("harvest.export") }}';
            
            applyFilters();
        }
        
        function fetchData(search = '', subcategoria = 'todas', page = 1) {
            // Construir URL con parámetros
            let url = '{{ route("harvest.index") }}?ajax=1';
            
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
            .then(response => response.json())
            .then(data => {
                // Actualizar tabla
                document.getElementById('tableContainer').innerHTML = data.html;
                
                // Actualizar estadísticas
                document.getElementById('totalRepuestos').textContent = data.totalRepuestos;
                document.getElementById('totalUnidades').textContent = data.totalUnidades;
                
                // Ocultar loading
                document.getElementById('loadingIndicator').classList.add('hidden');
                
                // Agregar event listeners a la paginación
                setupPagination();
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('loadingIndicator').classList.add('hidden');
                
                // Mostrar error
                document.getElementById('tableContainer').innerHTML = `
                    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="text-lg font-medium text-red-800">Error al cargar datos</h3>
                                <p class="text-red-600">Intente nuevamente en unos momentos.</p>
                            </div>
                        </div>
                    </div>
                `;
            });
        }
        
        function setupPagination() {
            // Agregar event listeners a los enlaces de paginación
            document.querySelectorAll('.pagination a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const url = new URL(this.href);
                    const page = url.searchParams.get('page') || 1;
                    const search = document.getElementById('searchInput').value;
                    const subcategoria = document.getElementById('subcategoriaSelect').value;
                    
                    document.getElementById('loadingIndicator').classList.remove('hidden');
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
            let exportUrl = '{{ route("harvest.export") }}';
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
            
            document.getElementById('exportLink').href = exportUrl;
        }
        
        function verDetalle(idArticulo) {
            // Mostrar modal con animación
            const modal = document.getElementById('detalleModal');
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            
            // Mostrar loading
            document.getElementById('modalContent').innerHTML = `
                <div class="flex flex-col items-center justify-center py-12">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4"></div>
                    <p class="text-gray-600">Cargando detalles del repuesto...</p>
                </div>
            `;
            
            // Cargar contenido via AJAX
            fetch(`/almacen/harvest/${idArticulo}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                document.getElementById('modalContent').innerHTML = html;
            })
            .catch(error => {
                document.getElementById('modalContent').innerHTML = `
                    <div class="bg-red-50 border-l-4 border-red-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    Error al cargar los detalles. Por favor, intente nuevamente.
                                </p>
                            </div>
                        </div>
                    </div>
                `;
            });
        }
        
        function closeModal() {
            const modal = document.getElementById('detalleModal');
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
        
        // Cerrar modal con ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
        
        // Inicializar filtros desde URL
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const search = urlParams.get('search');
            const subcategoria = urlParams.get('subcategoria');
            
            if (search) {
                document.getElementById('searchInput').value = search;
            }
            
            if (subcategoria) {
                document.getElementById('subcategoriaSelect').value = subcategoria;
            }
            
            // Agregar event listeners a la paginación inicial
            setupPagination();
            
            // Configurar el enlace de exportación inicial
            updateExportLink(
                document.getElementById('searchInput').value,
                document.getElementById('subcategoriaSelect').value
            );
        });
    </script>

    <style>
        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
        }
        
        .pagination li {
            margin: 0 2px;
        }
        
        .pagination li a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 12px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .pagination li a.page-link {
            color: #4B5563;
            border: 1px solid #E5E7EB;
            background-color: white;
        }
        
        .pagination li a.page-link:hover {
            background-color: #F3F4F6;
            border-color: #D1D5DB;
        }
        
        .pagination li.active span {
            background: linear-gradient(135deg, #3B82F6, #8B5CF6);
            color: white;
            border: none;
            box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
        }
        
        .pagination li.disabled span {
            color: #9CA3AF;
            border-color: #E5E7EB;
            cursor: not-allowed;
            background-color: #F9FAFB;
        }
    </style>
</x-layout.default>