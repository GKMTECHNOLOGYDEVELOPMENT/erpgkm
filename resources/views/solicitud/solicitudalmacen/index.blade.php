<x-layout.default>
    <link rel="stylesheet" href="{{ asset('assets/css/solicitudalmacen.css') }}">
    <div x-data="warehouseRequests()" x-init="init()">
        <div class="container">
            <!-- Header -->
            <div class="header">
                <div class="title-section">
                    <div class="title-with-icon">
                        <div class="icon-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1H.5zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h1>Solicitudes de Abastecimiento</h1>
                            <p>Gestiona las necesidades de inventario del almacén</p>
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary" @click="showCreateModal = true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                    Nueva Solicitud
                </button>
            </div>

            <!-- Stats -->
            <div class="stats">
                <div class="stat-card">
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
                <div class="stat-card">
                    <div class="stat-icon pending">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                            <path d="M8 16A8 8 0 1 1 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value" x-text="getRequestsByStatus('pendiente').length"></div>
                        <div class="stat-label">Pendientes</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon approved">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.5.5 0 0 1-.708.008l-2-2a.5.5 0 0 1 .708-.708l1.646 1.647 3.28-4.176a.75.75 0 0 1 1.075-.136z"/>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value" x-text="getRequestsByStatus('aprobada').length"></div>
                        <div class="stat-label">Aprobadas</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon completed">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value" x-text="getRequestsByStatus('completada').length"></div>
                        <div class="stat-label">Completadas</div>
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
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Prioridad</label>
                        <select class="filter-select" x-model="filters.priority">
                            <option value="">Todas las prioridades</option>
                            <option value="low">Baja</option>
                            <option value="medium">Media</option>
                            <option value="high">Alta</option>
                            <option value="urgent">Urgente</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Tipo</label>
                        <select class="filter-select" x-model="filters.type">
                            <option value="">Todos los tipos</option>
                            <option value="Reabastecimiento">Reabastecimiento</option>
                            <option value="Producto Nuevo">Producto Nuevo</option>
                            <option value="Reposición">Reposición</option>
                            <option value="Estacional">Estacional</option>
                            <option value="Emergencia">Emergencia</option>
                        </select>
                    </div>
                    <div class="search-box">
                        <span class="search-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                            </svg>
                        </span>
                        <input type="text" class="search-input" placeholder="Buscar por código, título, solicitante..." x-model="filters.search">
                    </div>
                </div>
            </div>

            <!-- Cards Container -->
            <div class="cards-container">
                <template x-for="request in filteredRequests" :key="request.id">
                    <div class="card" :class="'priority-' + request.priority">
                        <div class="card-header">
                            <div class="card-code">
                                <span class="code-value" x-text="request.code"></span>
                                <span class="card-type" x-text="request.type"></span>
                            </div>
                            <div class="card-status" :class="'status-' + request.status" x-text="getStatusText(request.status)"></div>
                        </div>
                        
                        <div class="card-body">
                            <h3 class="card-title" x-text="request.title"></h3>
                            <p class="card-description" x-text="request.description"></p>
                            
                            <div class="card-details">
                                <div class="detail-item">
                                    <span class="detail-label">Solicitado por:</span>
                                    <span class="detail-value" x-text="request.requested_by"></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Fecha requerida:</span>
                                    <span class="detail-value" x-text="formatDate(request.required_date)"></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Productos:</span>
                                    <span class="detail-value" x-text="request.products.length + ' items'"></span>
                                </div>
                                <!-- QUITAR VALOR ESTIMADO -->
                            </div>

                            <!-- Mini lista de productos -->
                            <div class="products-preview" x-show="request.products && request.products.length > 0">
                                <h4>Productos solicitados:</h4>
                                <div class="products-list">
                                    <template x-for="product in request.products.slice(0, 3)" :key="product.id">
                                        <div class="product-item">
                                            <span class="product-name" x-text="product.name"></span>
                                            <span class="product-quantity" x-text="product.quantity + ' ' + product.unit"></span>
                                        </div>
                                    </template>
                                    <div x-show="request.products.length > 3" class="more-items">
                                        + <span x-text="request.products.length - 3"></span> más...
                                    </div>
                                </div>
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
                                <button class="btn-icon" title="Ver detalles" @click="viewRequest(request)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                    </svg>
                                </button>
                                <button class="btn-icon" title="Editar" x-show="request.status === 'pendiente'" @click="editRequest(request)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                    </svg>
                                </button>
                                <button class="btn-icon" title="Convertir a Orden de Compra" 
                                        x-show="request.status === 'aprobada'" 
                                        @click="convertToPurchaseOrder(request)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1H.5zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM9 5.5V7h1.5a.5.5 0 0 1 0 1H9v1.5a.5.5 0 0 1-1 0V8H6.5a.5.5 0 0 1 0-1H8V5.5a.5.5 0 0 1 1 0z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>

                <div x-show="filteredRequests.length === 0" class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M7 11.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0-3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0-3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5z"/>
                    </svg>
                    <h3>No se encontraron solicitudes</h3>
                    <p x-show="hasActiveFilters()">No hay solicitudes que coincidan con los filtros aplicados</p>
                    <p x-show="!hasActiveFilters() && requests.length === 0">No hay solicitudes de abastecimiento registradas</p>
                    <button x-show="requests.length === 0" class="btn btn-primary" @click="showCreateModal = true">
                        Crear Primera Solicitud
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal para crear nueva solicitud -->
        <div x-show="showCreateModal" class="modal-overlay">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Nueva Solicitud de Abastecimiento</h2>
                    <button class="btn-close" @click="showCreateModal = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <p>¿Desea crear una nueva solicitud de abastecimiento para el almacén?</p>
                    <div class="modal-info">
                        <div class="info-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                            </svg>
                            <span>Complete los productos necesarios para el almacén</span>
                        </div>
                        <div class="info-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                            </svg>
                            <!-- QUITAR REFERENCIA A PRECIOS -->
                            <span>Especifique cantidades y justificaciones</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="showCreateModal = false">Cancelar</button>
                    <button class="btn btn-primary" @click="redirectToCreate()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                        Crear Solicitud
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function warehouseRequests() {
            return {
                requests: @json($requests),
                filters: {
                    status: '',
                    priority: '',
                    type: '',
                    search: ''
                },
                showCreateModal: false,
                
                init() {
                    console.log('Solicitudes cargadas:', this.requests);
                },
                
                get filteredRequests() {
                    return this.requests.filter(request => {
                        // Filtrar por estado
                        if (this.filters.status && request.status !== this.filters.status) {
                            return false;
                        }
                        
                        // Filtrar por prioridad
                        if (this.filters.priority && request.priority !== this.filters.priority) {
                            return false;
                        }
                        
                        // Filtrar por tipo
                        if (this.filters.type && request.type !== this.filters.type) {
                            return false;
                        }
                        
                        // Filtrar por búsqueda
                        if (this.filters.search) {
                            const searchTerm = this.filters.search.toLowerCase();
                            return (
                                (request.title && request.title.toLowerCase().includes(searchTerm)) ||
                                (request.description && request.description.toLowerCase().includes(searchTerm)) ||
                                (request.code && request.code.toLowerCase().includes(searchTerm)) ||
                                (request.requested_by && request.requested_by.toLowerCase().includes(searchTerm))
                            );
                        }
                        
                        return true;
                    });
                },
                
                getRequestsByStatus(status) {
                    return this.requests.filter(request => request.status === status);
                },
                
                getStatusText(status) {
                    const statusMap = {
                        'pendiente': 'Pendiente',
                        'aprobada': 'Aprobada',
                        'rechazada': 'Rechazada',
                        'en_proceso': 'En Proceso',
                        'completada': 'Completada'
                    };
                    return statusMap[status] || status;
                },
                
                formatDate(dateString) {
                    if (!dateString) return 'Sin fecha';
                    try {
                        const date = new Date(dateString);
                        if (isNaN(date.getTime())) return 'Fecha inválida';
                        
                        const options = { year: 'numeric', month: 'short', day: 'numeric' };
                        return date.toLocaleDateString('es-ES', options);
                    } catch (error) {
                        return 'Fecha inválida';
                    }
                },
                
                hasActiveFilters() {
                    return this.filters.status || this.filters.priority || this.filters.type || this.filters.search;
                },
                
                clearFilters() {
                    this.filters = {
                        status: '',
                        priority: '',
                        type: '',
                        search: ''
                    };
                },
                
                viewRequest(request) {
                    // Redirigir a la vista de detalles
                    window.location.href = `/solicitudalmacen/${request.id}/detalles`;
                },
                
                editRequest(request) {
                    // Redirigir a edición (solo si está pendiente)
                    if (request.status === 'pendiente') {
                        window.location.href = `/solicitudalmacen/${request.id}/edit`;
                    }
                },
                
                convertToPurchaseOrder(request) {
                    // Convertir a orden de compra (solo si está aprobada)
                    if (request.status === 'aprobada') {
                        if (confirm(`¿Convertir la solicitud ${request.code} a Orden de Compra?`)) {
                            // Aquí puedes implementar la lógica para crear una orden de compra
                            console.log('Convertir a orden de compra:', request);
                            // window.location.href = `/purchase-orders/create?warehouse_request=${request.id}`;
                            
                            // Simulación temporal
                            alert(`Solicitud ${request.code} convertida a Orden de Compra exitosamente`);
                        }
                    } else {
                        alert('Solo se pueden convertir a orden de compra las solicitudes aprobadas');
                    }
                },
                
                redirectToCreate() {
                    this.showCreateModal = false;
                    // Redirigir al formulario de creación
                    window.location.href = '/solicitudalmacen/create';
                }
            }
        }
    </script>
</x-layout.default>