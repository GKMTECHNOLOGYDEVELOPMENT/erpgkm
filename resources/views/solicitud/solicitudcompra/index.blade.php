<x-layout.default>

<link rel="stylesheet" href="{{ asset('assets/css/solicitudcompra.css') }}">
    <div x-data="purchaseRequests()" x-init="init()">
        <div class="container">
            <!-- Header -->
            <div class="header">
                <div class="title-section">
                    <div class="title-with-icon">
                        <div class="icon-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M0 1.5A1.5 1.5 0 0 1 1.5 0h13A1.5 1.5 0 0 1 16 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13zM1.5 1a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-13z"/>
                                <path d="M3.5 3a.5.5 0 0 0-.5.5v2a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 0-.5-.5h-2zm3 0a.5.5 0 0 0-.5.5v5a.5.5 0 0 0 .5.5h5a.5.5 0 0 0 .5-.5v-5a.5.5 0 0 0-.5-.5h-5zm3 5a.5.5 0 0 0-.5.5v2a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 0-.5-.5h-2z"/>
                            </svg>
                        </div>
                        <div>
                            <h1>Solicitudes de Compra</h1>
                            <p>Gestiona y revisa todas las solicitudes de compra</p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('solicitudcompra.create') }}" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                    Nueva Solicitud
                </a>
            </div>

            <!-- Stats -->
            <div class="stats">
                <div class="stat-card" @mouseenter="hoverStat = 'total'" @mouseleave="hoverStat = ''">
                    <div class="stat-icon total">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z"/>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value" x-text="requests.length"></div>
                        <div class="stat-label">Total Solicitudes</div>
                    </div>
                </div>
                <div class="stat-card" @mouseenter="hoverStat = 'pending'" @mouseleave="hoverStat = ''">
                    <div class="stat-icon pending">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value" x-text="getRequestsByStatus('pendiente').length"></div>
                        <div class="stat-label">Pendientes</div>
                    </div>
                </div>
                <div class="stat-card" @mouseenter="hoverStat = 'approved'" @mouseleave="hoverStat = ''">
                    <div class="stat-icon approved">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value" x-text="getRequestsByStatus('aprobada').length"></div>
                        <div class="stat-label">Aprobadas</div>
                    </div>
                </div>
                <div class="stat-card" @mouseenter="hoverStat = 'rejected'" @mouseleave="hoverStat = ''">
                    <div class="stat-icon rejected">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value" x-text="getRequestsByStatus('rechazada').length"></div>
                        <div class="stat-label">Rechazadas</div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="filters-card">
                <div class="filters-header">
                    <h3>Filtros</h3>
                    <button class="btn-clear" @click="clearFilters()">Limpiar Filtros</button>
                </div>
                <div class="filters">
                    <div class="filter-group">
                        <label class="filter-label">Estado</label>
                        <select class="filter-select" x-model="filters.status">
                            <option value="">Todos los estados</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="aprobada">Aprobada</option>
                            <option value="rechazada">Rechazada</option>
                            <option value="en_proceso">En Proceso</option>
                            <option value="completada">Completada</option>
                            <option value="cancelada">Cancelada</option>
                            <option value="presupuesto_aprobado">Presupuesto Aprobado</option>
                            <option value="pagado">Pagado</option>
                            <option value="finalizado">Finalizado</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Prioridad</label>
                        <select class="filter-select" x-model="filters.priority">
                            <option value="">Todas las prioridades</option>
                            @foreach($prioridades as $prioridad)
                                <option value="{{ $prioridad->idPrioridad }}">{{ $prioridad->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Área</label>
                        <select class="filter-select" x-model="filters.area">
                            <option value="">Todas las áreas</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->idTipoArea }}">{{ $area->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="search-box">
                        <span class="search-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                            </svg>
                        </span>
                        <input type="text" class="search-input" placeholder="Buscar por código, proyecto, solicitante..." x-model="filters.search">
                    </div>
                </div>
            </div>

            <!-- Cards Container -->
            <div class="cards-container">
                <template x-for="request in filteredRequests" :key="request.idSolicitudCompra">
                    <div class="card" :class="'priority-' + (request.prioridad?.nivel || 'medium')">
                        <div class="card-header">
                            <div class="card-codes">
                                <div class="card-id" x-text="request.codigo_solicitud"></div>
                                <div class="card-almacen-code" x-show="request.solicitud_almacen?.codigo_solicitud" 
                                     x-text="'Almacén: ' + request.solicitud_almacen?.codigo_solicitud"></div>
                            </div>
                            <div class="card-status" :class="'status-' + request.estado" x-text="getStatusText(request.estado)"></div>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title" x-text="request.proyecto_asociado || 'Solicitud de Compra'"></h3>
                            <div class="card-details">
                                <!-- Solicitante Compra -->
                                <div class="detail-item">
                                    <span class="detail-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                                        </svg>
                                        Solicitante Compra:
                                    </span>
                                    <span class="detail-value" x-text="request.solicitante_compra || 'N/A'"></span>
                                </div>
                                
                                <!-- Solicitante Almacén -->
                                <div class="detail-item">
                                    <span class="detail-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                                        </svg>
                                        Solicitante Almacén:
                                    </span>
                                    <span class="detail-value" x-text="request.solicitante_almacen || 'N/A'"></span>
                                </div>

                                <!-- Área -->
                                <div class="detail-item">
                                    <span class="detail-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M4.5 5a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1zM3 4.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm2 7a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm-2.5.5a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                                            <path d="M2 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H2zm12 1a1 1 0 0 1 1 1v1H1V3a1 1 0 0 1 1-1h12zM1 7v6a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V7H1z"/>
                                        </svg>
                                        Área:
                                    </span>
                                    <span class="detail-value" x-text="request.tipo_area?.nombre || 'N/A'"></span>
                                </div>
                                
                                <!-- Prioridad -->
                                <div class="detail-item">
                                    <span class="detail-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-9z"/>
                                        </svg>
                                        Prioridad:
                                    </span>
                                    <span class="detail-value" x-text="request.prioridad?.nombre || 'N/A'"></span>
                                </div>
                                
                                <!-- Total -->
                                <div class="detail-item">
                                    <span class="detail-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M12.136.326A1.5 1.5 0 0 1 14 1.78V3h.5A1.5 1.5 0 0 1 16 4.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 13.5v-9a1.5 1.5 0 0 1 1.432-1.499L12.136.326zM5.562 3H13V1.78a.5.5 0 0 0-.621-.484L5.562 3zM1.5 4a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-13z"/>
                                        </svg>
                                        Total:
                                    </span>
                                    <span class="detail-value" x-text="getCurrencySymbol(request) + (request.total ? Number(request.total).toLocaleString('es-PE', {minimumFractionDigits: 2}) : '0.00')"></span>
                                </div>
                                
                                <!-- Moneda Principal -->
                                <div class="detail-item" x-show="getMainCurrency(request)">
                                    <span class="detail-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M8 10.933l-5.247-5.25a.5.5 0 0 1 .708-.708L8 9.526l4.539-4.55a.5.5 0 1 1 .708.708L8 10.933z"/>
                                        </svg>
                                        Moneda:
                                    </span>
                                    <span class="detail-value" x-text="getMainCurrency(request)"></span>
                                </div>
                            </div>
                            
                            <!-- Justificación -->
                            <div class="card-justification" x-show="request.justificacion">
                                <strong>Justificación:</strong>
                                <span x-text="request.justificacion?.substring(0, 100) + (request.justificacion?.length > 100 ? '...' : '')"></span>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="card-date">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM2 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H2z"/>
                                    <path d="M2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H3a.5.5 0 0 1-.5-.5V4z"/>
                                </svg>
                                <span x-text="formatDate(request.created_at)"></span>
                            </div>
                            <div class="card-actions">
                                <button class="btn-icon" title="Ver detalles" @click="viewRequest(request.idSolicitudCompra)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                    </svg>
                                </button>
                                <button class="btn-icon" title="Editar" @click="editRequest(request.idSolicitudCompra)" x-show="request.estado === 'pendiente'">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>

                <div x-show="filteredRequests.length === 0" class="empty-state">
                    <div class="empty-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M7 11.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0-3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0-3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5z"/>
                        </svg>
                    </div>
                    <h3>No se encontraron solicitudes</h3>
                    <p>No hay solicitudes que coincidan con los filtros aplicados</p>
                    <button class="btn btn-secondary" @click="clearFilters()">Limpiar filtros</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Pasar datos de PHP a JavaScript -->
    <script>
        const solicitudesData = @json($solicitudes);
    </script>

    <script>
        function purchaseRequests() {
            return {
                requests: [],
                filters: {
                    status: '',
                    priority: '',
                    area: '',
                    search: ''
                },
                showNewRequestModal: false,
                hoverStat: '',
                
                init() {
                    // Usar los datos reales de Laravel
                    this.requests = solicitudesData.map(solicitud => ({
                        ...solicitud,
                        // Asegurar que las relaciones estén disponibles
                        tipo_area: solicitud.tipo_area || null,
                        prioridad: solicitud.prioridad || null,
                        solicitud_almacen: solicitud.solicitud_almacen || null,
                        detalles: solicitud.detalles || []
                    }));
                },
                
                get filteredRequests() {
                    return this.requests.filter(request => {
                        // Filtrar por estado
                        if (this.filters.status && request.estado !== this.filters.status) {
                            return false;
                        }
                        
                        // Filtrar por prioridad
                        if (this.filters.priority && request.idPrioridad != this.filters.priority) {
                            return false;
                        }
                        
                        // Filtrar por área
                        if (this.filters.area && request.idTipoArea != this.filters.area) {
                            return false;
                        }
                        
                        // Filtrar por búsqueda
                        if (this.filters.search) {
                            const searchTerm = this.filters.search.toLowerCase();
                            return (
                                (request.proyecto_asociado || '').toLowerCase().includes(searchTerm) ||
                                (request.solicitante_compra || '').toLowerCase().includes(searchTerm) ||
                                (request.solicitante_almacen || '').toLowerCase().includes(searchTerm) ||
                                (request.codigo_solicitud || '').toLowerCase().includes(searchTerm) ||
                                (request.solicitud_almacen?.codigo_solicitud || '').toLowerCase().includes(searchTerm) ||
                                (request.justificacion || '').toLowerCase().includes(searchTerm)
                            );
                        }
                        
                        return true;
                    });
                },
                
                getRequestsByStatus(status) {
                    return this.requests.filter(request => request.estado === status);
                },
                
                getStatusText(status) {
                    const statusMap = {
                        'pendiente': 'Pendiente',
                        'aprobada': 'Aprobada',
                        'rechazada': 'Rechazada',
                        'en_proceso': 'En Proceso',
                        'completada': 'Completada',
                        'cancelada': 'Cancelada',
                        'presupuesto_aprobado': 'Presupuesto Aprobado',
                        'pagado': 'Pagado',
                        'finalizado': 'Finalizado'
                    };
                    return statusMap[status] || status;
                },
                
                getCurrencySymbol(request) {
                    // Obtener el símbolo de moneda más común de los detalles
                    if (!request.detalles || request.detalles.length === 0) {
                        return 'S/';
                    }
                    
                    // Contar monedas por símbolo
                    const currencyCount = {};
                    request.detalles.forEach(detalle => {
                        if (detalle.moneda && detalle.moneda.simbolo) {
                            currencyCount[detalle.moneda.simbolo] = (currencyCount[detalle.moneda.simbolo] || 0) + 1;
                        }
                    });
                    
                    // Encontrar la moneda más común
                    const mostCommonCurrency = Object.keys(currencyCount).reduce((a, b) => 
                        currencyCount[a] > currencyCount[b] ? a : b, 'S/'
                    );
                    
                    return mostCommonCurrency;
                },
                
                getMainCurrency(request) {
                    // Obtener el nombre de la moneda principal
                    if (!request.detalles || request.detalles.length === 0) {
                        return '';
                    }
                    
                    const currencyCount = {};
                    request.detalles.forEach(detalle => {
                        if (detalle.moneda && detalle.moneda.nombre) {
                            currencyCount[detalle.moneda.nombre] = (currencyCount[detalle.moneda.nombre] || 0) + 1;
                        }
                    });
                    
                    const mostCommonCurrency = Object.keys(currencyCount).reduce((a, b) => 
                        currencyCount[a] > currencyCount[b] ? a : b, ''
                    );
                    
                    return mostCommonCurrency;
                },
                
                formatDate(dateString) {
                    if (!dateString) return 'N/A';
                    const date = new Date(dateString);
                    const options = { year: 'numeric', month: 'short', day: 'numeric' };
                    return date.toLocaleDateString('es-ES', options);
                },
                
                clearFilters() {
                    this.filters = {
                        status: '',
                        priority: '',
                        area: '',
                        search: ''
                    };
                },
                
                viewRequest(id) {
                    // Redirigir a la vista de detalles usando la ruta correcta
                    window.location.href = `/solicitudcompra/${id}`;
                },

                editRequest(id) {
                    // Redirigir a la vista de edición usando la ruta correcta
                    window.location.href = `/solicitudcompra/${id}/edit`;
                }
            }
        }
    </script>

    <style>
    .card-codes {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    
    .card-almacen-code {
        font-size: 0.75rem;
        color: #6b7280;
        font-weight: 500;
    }
    
    .detail-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 4px 0;
    }
    
    .detail-label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8rem;
        color: #6b7280;
        font-weight: 500;
    }
    
    .detail-value {
        font-size: 0.8rem;
        font-weight: 600;
        color: #374151;
        text-align: right;
    }
    
    .card-justification {
        margin-top: 8px;
        padding: 8px;
        background: #f8fafc;
        border-radius: 4px;
        font-size: 0.8rem;
        color: #6b7280;
    }
    
    .card-justification strong {
        color: #374151;
    }
    </style>
</x-layout.default>