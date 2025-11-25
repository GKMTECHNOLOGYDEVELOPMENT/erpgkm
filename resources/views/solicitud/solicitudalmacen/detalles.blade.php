<x-layout.default>
    <link rel="stylesheet" href="{{ asset('assets/css/solicitudalmacen.css') }}">
    <div x-data="warehouseRequestDetail()" x-init="init()">
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
                            <h1>Detalle de Solicitud de Abastecimiento</h1>
                            <p>Información completa de la solicitud</p>
                        </div>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="{{ route('solicitudalmacen.index') }}" class="btn btn-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                        </svg>
                        Volver al Listado
                    </a>
                    <button class="btn btn-primary" @click="printDetail()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2H5zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1z"/>
                            <path d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2V7zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                        </svg>
                        Imprimir
                    </button>
                </div>
            </div>

            <!-- Información Principal -->
            <div class="detail-container">
                <div class="detail-header">
                    <div class="detail-code-section">
                        <h2 x-text="solicitud.codigo_solicitud"></h2>
                        <div class="detail-status-badge" :class="'status-' + solicitud.estado" x-text="getStatusText(solicitud.estado)"></div>
                    </div>
                    <div class="detail-priority" :class="'priority-' + getPriorityLevel(solicitud.prioridad?.nivel)">
                        <span x-text="solicitud.prioridad?.nombre"></span>
                    </div>
                </div>

                <!-- Alertas de estado -->
                <div class="status-alerts" x-show="solicitud.estado">
                    <div class="alert alert-info" x-show="solicitud.estado === 'pendiente'">
                        <strong>📝 Solicitud Pendiente:</strong> Puede comenzar a aprobar o rechazar productos individualmente.
                    </div>
                    <div class="alert alert-warning" x-show="solicitud.estado === 'en_proceso'">
                        <strong>⚡ Solicitud en Proceso:</strong> Algunos productos han sido evaluados. Continúe con los productos pendientes.
                    </div>
                    <div class="alert alert-success" x-show="solicitud.estado === 'completada'">
                        <strong>✅ Evaluación Completada:</strong> Todos los productos tienen estado. Defina el estado final de la solicitud.
                    </div>
                    <div class="alert alert-success" x-show="solicitud.estado === 'aprobada'">
                        <strong>🎉 Solicitud Aprobada:</strong> La solicitud ha sido aprobada completamente.
                    </div>
                    <div class="alert alert-danger" x-show="solicitud.estado === 'rechazada'">
                        <strong>❌ Solicitud Rechazada:</strong> La solicitud ha sido rechazada completamente.
                    </div>
                </div>

                <div class="detail-grid">
                    <!-- Información General -->
                    <div class="detail-card">
                        <div class="detail-card-header">
                            <h3>Información General</h3>
                        </div>
                        <div class="detail-card-body">
                            <div class="detail-row">
                                <div class="detail-label">Título:</div>
                                <div class="detail-value" x-text="solicitud.titulo"></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Tipo de Solicitud:</div>
                                <div class="detail-value" x-text="solicitud.tipoSolicitud?.nombre"></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Solicitante:</div>
                                <div class="detail-value" x-text="solicitud.solicitante"></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Fecha Requerida:</div>
                                <div class="detail-value" x-text="formatDate(solicitud.fecha_requerida)"></div>
                            </div>
                            <div class="detail-row" x-show="solicitud.centroCosto">
                                <div class="detail-label">Centro de Costo:</div>
                                <div class="detail-value" x-text="solicitud.centroCosto?.nombre"></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Fecha de Creación:</div>
                                <div class="detail-value" x-text="formatDateTime(solicitud.created_at)"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Resumen de Productos -->
                    <div class="detail-card">
                        <div class="detail-card-header">
                            <h3>Resumen de Estados</h3>
                        </div>
                        <div class="detail-card-body">
                            <div class="detail-row">
                                <div class="detail-label">Total Productos:</div>
                                <div class="detail-value" x-text="solicitud.detalles?.length || 0"></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Total Unidades:</div>
                                <div class="detail-value" x-text="solicitud.total_unidades"></div>
                            </div>
                            <div class="detail-row" x-show="solicitud.detalles">
                                <div class="detail-label">Aprobados:</div>
                                <div class="detail-value">
                                    <span class="text-success" x-text="getAprobadosCount()"></span>
                                </div>
                            </div>
                            <div class="detail-row" x-show="solicitud.detalles">
                                <div class="detail-label">Rechazados:</div>
                                <div class="detail-value">
                                    <span class="text-danger" x-text="getRechazadosCount()"></span>
                                </div>
                            </div>
                            <div class="detail-row" x-show="solicitud.detalles">
                                <div class="detail-label">Pendientes:</div>
                                <div class="detail-value">
                                    <span class="text-warning" x-text="getPendientesCount()"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Descripción y Justificación -->
                    <div class="detail-card full-width">
                        <div class="detail-card-header">
                            <h3>Descripción y Justificación</h3>
                        </div>
                        <div class="detail-card-body">
                            <div class="detail-section">
                                <h4>Descripción</h4>
                                <p class="detail-text" x-text="solicitud.descripcion || 'Sin descripción'"></p>
                            </div>
                            <div class="detail-section">
                                <h4>Justificación</h4>
                                <p class="detail-text" x-text="solicitud.justificacion || 'Sin justificación'"></p>
                            </div>
                            <div class="detail-section" x-show="solicitud.observaciones">
                                <h4>Observaciones</h4>
                                <p class="detail-text" x-text="solicitud.observaciones"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Productos Solicitados con Gestión de Estados -->
                    <div class="detail-card full-width">
                        <div class="detail-card-header">
                            <h3>Productos Solicitados - Gestión de Estados</h3>
                            <span class="detail-count" x-text="'(' + (solicitud.detalles?.length || 0) + ' productos)'"></span>
                        </div>
                        <div class="detail-card-body">
                            <div class="products-table">
                                <div class="table-header">
                                    <div class="table-col product-col">Producto</div>
                                    <div class="table-col quantity-col">Cantidad</div>
                                    <div class="table-col unit-col">Unidad</div>
                                    <div class="table-col status-col">Estado Actual</div>
                                    <div class="table-col action-col">Acciones</div>
                                </div>
                                <div class="table-body">
                                    <template x-for="(producto, index) in solicitud.detalles" :key="producto.idSolicitudAlmacenDetalle">
                                        <div class="table-row">
                                            <div class="table-col product-col">
                                                <div class="product-info">
                                                    <strong x-text="producto.descripcion_producto"></strong>
                                                    <div class="product-details">
                                                        <span x-show="producto.categoria" x-text="'Categoría: ' + producto.categoria" class="product-category"></span>
                                                        <span x-show="producto.codigo_producto" x-text="'Código: ' + producto.codigo_producto" class="product-code"></span>
                                                        <span x-show="producto.marca" x-text="'Marca: ' + producto.marca" class="product-brand"></span>
                                                    </div>
                                                    <div class="product-specs" x-show="producto.especificaciones_tecnicas" x-text="'Especificaciones: ' + producto.especificaciones_tecnicas"></div>
                                                    <div class="product-justification" x-show="producto.justificacion_producto" x-text="'Justificación: ' + producto.justificacion_producto"></div>
                                                    <div class="product-observations" x-show="producto.observaciones_detalle" x-text="'Observaciones: ' + producto.observaciones_detalle"></div>
                                                </div>
                                            </div>
                                            <div class="table-col quantity-col" x-text="producto.cantidad"></div>
                                            <div class="table-col unit-col" x-text="producto.unidad"></div>
                                            <div class="table-col status-col">
                                                <span class="status-badge" :class="'status-' + producto.estado" x-text="getProductStatusText(producto.estado)"></span>
                                            </div>
                                            <div class="table-col action-col">
                                                <div class="action-buttons" x-show="canChangeProductStatus(solicitud.estado)">
                                                    <template x-if="producto.estado === 'pendiente' || producto.estado === 'rechazado'">
                                                        <button class="btn btn-success btn-sm" @click="changeProductStatus(producto.idSolicitudAlmacenDetalle, 'aprobado', producto.descripcion_producto)">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                                                                <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
                                                            </svg>
                                                            Aprobar
                                                        </button>
                                                    </template>
                                                    <template x-if="producto.estado === 'pendiente' || producto.estado === 'aprobado'">
                                                        <button class="btn btn-danger btn-sm" @click="changeProductStatus(producto.idSolicitudAlmacenDetalle, 'rechazado', producto.descripcion_producto)">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                                                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                                            </svg>
                                                            Rechazar
                                                        </button>
                                                    </template>
                                                </div>
                                                <span x-show="!canChangeProductStatus(solicitud.estado)" class="text-muted">
                                                    No editable
                                                </span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            
                            <!-- Controles para estado final (cuando está completada) -->
                            <div class="final-actions" x-show="solicitud.estado === 'completada'">
                                <div class="final-status-section">
                                    <h4>📋 Definir Estado Final de la Solicitud</h4>
                                    <div class="final-buttons">
                                        <button class="btn btn-success btn-lg final-btn" @click="changeFinalStatus('aprobada')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
                                            </svg>
                                            ✅ Aprobar Solicitud Completa
                                        </button>
                                        <button class="btn btn-danger btn-lg final-btn" @click="changeFinalStatus('rechazada')" 
                                                x-show="canRejectCompleteRequest(solicitud.detalles)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                            </svg>
                                            ❌ Rechazar Solicitud Completa
                                        </button>
                                    </div>
                                    <div class="final-info">
                                        <small class="text-muted">
                                            * Según los estados de los productos, el sistema determinará qué acción está permitida
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Historial de Estados -->
                    <div class="detail-card full-width" x-show="solicitud.historial && solicitud.historial.length > 0">
                        <div class="detail-card-header">
                            <h3>Historial de Estados</h3>
                        </div>
                        <div class="detail-card-body">
                            <div class="timeline">
                                <template x-for="(evento, index) in solicitud.historial" :key="evento.idHistorial">
                                    <div class="timeline-item">
                                        <div class="timeline-marker" :class="'marker-' + evento.estado_nuevo"></div>
                                        <div class="timeline-content">
                                            <div class="timeline-header">
                                                <span class="timeline-status" x-text="getStatusText(evento.estado_nuevo)"></span>
                                                <span class="timeline-date" x-text="formatDateTime(evento.created_at)"></span>
                                            </div>
                                            <div class="timeline-body" x-text="evento.observaciones || 'Sin observaciones'"></div>
                                            <div class="timeline-user" x-show="evento.usuario" x-text="'Por: ' + evento.usuario?.name"></div>
                                            <div class="timeline-type" x-show="evento.tipo_cambio" x-text="'Tipo: ' + getTipoCambioText(evento.tipo_cambio)"></div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Archivos Adjuntos -->
                    <div class="detail-card full-width" x-show="solicitud.archivos && solicitud.archivos.length > 0">
                        <div class="detail-card-header">
                            <h3>Archivos Adjuntos</h3>
                        </div>
                        <div class="detail-card-body">
                            <div class="files-grid">
                                <template x-for="archivo in solicitud.archivos" :key="archivo.idArchivo">
                                    <div class="file-item">
                                        <div class="file-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zm5.5 1.5v2a1 1 0 0 0 1 1h2l-3-3z"/>
                                            </svg>
                                        </div>
                                        <div class="file-info">
                                            <div class="file-name" x-text="archivo.nombre_archivo"></div>
                                            <div class="file-meta" x-text="formatFileSize(archivo.tamaño) + ' • ' + archivo.tipo_archivo"></div>
                                        </div>
                                        <div class="file-actions">
                                            <a :href="archivo.ruta_archivo" target="_blank" class="btn-file">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                                    <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                                                </svg>
                                                Descargar
                                            </a>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function warehouseRequestDetail() {
            return {
                solicitud: {},
                
                async init() {
                    await this.loadRequestDetail();
                },
                
                async loadRequestDetail() {
                    try {
                        const response = await fetch(`/solicitudalmacen/${@json($id)}/detalles-data`);
                        const data = await response.json();
                        
                        if (data.success) {
                            this.solicitud = data.solicitud;
                        } else {
                            alert('Error al cargar los detalles de la solicitud');
                            window.location.href = '/solicitudalmacen';
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Error al cargar los detalles');
                    }
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

                getProductStatusText(status) {
                    const statusMap = {
                        'pendiente': 'Pendiente',
                        'aprobado': 'Aprobado', 
                        'rechazado': 'Rechazado'
                    };
                    return statusMap[status] || status;
                },

                getTipoCambioText(tipo) {
                    const tipoMap = {
                        'detalle': 'Cambio de Producto',
                        'solicitud': 'Cambio de Solicitud',
                        'final': 'Estado Final'
                    };
                    return tipoMap[tipo] || tipo;
                },
                
                getPriorityLevel(nivel) {
                    const map = {
                        1: 'low',
                        2: 'medium',
                        3: 'high',
                        4: 'urgent'
                    };
                    return map[nivel] || 'medium';
                },
                
                formatDate(dateString) {
                    if (!dateString) return 'No especificada';
                    const options = { year: 'numeric', month: 'long', day: 'numeric' };
                    return new Date(dateString).toLocaleDateString('es-ES', options);
                },
                
                formatDateTime(dateString) {
                    if (!dateString) return 'No especificada';
                    const options = { 
                        year: 'numeric', 
                        month: 'short', 
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    };
                    return new Date(dateString).toLocaleDateString('es-ES', options);
                },
                
                formatFileSize(bytes) {
                    if (!bytes) return '0 Bytes';
                    const k = 1024;
                    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                },

                getAprobadosCount() {
                    if (!this.solicitud.detalles) return 0;
                    return this.solicitud.detalles.filter(d => d.estado === 'aprobado').length;
                },

                getRechazadosCount() {
                    if (!this.solicitud.detalles) return 0;
                    return this.solicitud.detalles.filter(d => d.estado === 'rechazado').length;
                },

                getPendientesCount() {
                    if (!this.solicitud.detalles) return 0;
                    return this.solicitud.detalles.filter(d => d.estado === 'pendiente').length;
                },

                canChangeProductStatus(solicitudEstado) {
                    return ['pendiente', 'en_proceso', 'completada'].includes(solicitudEstado);
                },
        
                canRejectCompleteRequest(detalles) {
                    if (!detalles) return false;
                    const aprobados = detalles.filter(d => d.estado === 'aprobado').length;
                    const rechazados = detalles.filter(d => d.estado === 'rechazado').length;
                    
                    // Solo se puede rechazar completamente si TODOS los productos están rechazados
                    return rechazados === detalles.length && aprobados === 0;
                },
        
              // En el método changeProductStatus, mejora la respuesta:

async changeProductStatus(productoId, nuevoEstado, productoNombre) {
    console.log('Cambiando estado del producto:', {
        productoId: productoId,
        nuevoEstado: nuevoEstado,
        productoNombre: productoNombre
    });

    const accion = nuevoEstado === 'aprobado' ? 'APROBAR' : 'RECHAZAR';
    if (!confirm(`¿Está seguro de ${accion} el producto:\n"${productoNombre}"?`)) {
        return;
    }
    
    try {
        const observaciones = prompt('Ingrese observaciones (opcional):') || '';
        
        const response = await fetch(`/solicitudalmacen/detalle/${productoId}/cambiar-estado`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                estado: nuevoEstado,
                observaciones_detalle: observaciones
            })
        });
        
        const data = await response.json();
        console.log('Respuesta completa del servidor:', data);
        
        if (data.success) {
            let mensaje = '✅ Estado del producto actualizado exitosamente';
            if (data.solicitud_estado) {
                mensaje += `\n📋 Estado de la solicitud: ${this.getStatusText(data.solicitud_estado)}`;
            }
            if (data.detalles_estado) {
                mensaje += `\n📊 Resumen: ${data.detalles_estado.aprobados} aprobados, ${data.detalles_estado.rechazados} rechazados, ${data.detalles_estado.pendientes} pendientes`;
            }
            
            alert(mensaje);
            await this.loadRequestDetail(); // Recargar datos
        } else {
            alert('❌ Error: ' + data.message);
        }
    } catch (error) {
        console.error('Error completo:', error);
        alert('❌ Error al cambiar el estado del producto: ' + error.message);
    }
},
        
               async changeFinalStatus(estadoFinal) {
    const accion = estadoFinal === 'aprobada' ? 'APROBAR' : 'RECHAZAR';
    if (!confirm(`¿Está seguro de ${accion} la solicitud completa?\n\nEsta acción definirá el estado final de toda la solicitud.`)) {
        return;
    }
    
    try {
        let motivo_rechazo = null;
        let observaciones = prompt('Ingrese observaciones (opcional):') || '';
        
        if (estadoFinal === 'rechazada') {
            motivo_rechazo = prompt('Ingrese el motivo del rechazo de la solicitud completa:');
            if (!motivo_rechazo) {
                alert('Debe ingresar un motivo para rechazar la solicitud completa.');
                return;
            }
        }
        
        // URL CORREGIDA para estado final
        const response = await fetch(`/solicitudalmacen/${@json($id)}/cambiar-estado-final`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                estado: estadoFinal,
                motivo_rechazo: motivo_rechazo,
                observaciones: observaciones
            })
        });
        
        const data = await response.json();
        console.log('Respuesta del servidor (estado final):', data);
        
        if (data.success) {
            alert('✅ Estado final actualizado exitosamente');
            await this.loadRequestDetail(); // Recargar datos
        } else {
            alert('❌ Error: ' + data.message);
        }
    } catch (error) {
        console.error('Error completo:', error);
        alert('❌ Error al cambiar el estado final');
    }
},
                
                printDetail() {
                    window.print();
                }
            }
        }
    </script>

    <style>
        /* Estilos adicionales para la gestión de estados */
        .status-alerts {
            margin-bottom: 20px;
        }
        
        .status-alerts .alert {
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 10px;
        }
        
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-pendiente {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .status-aprobado {
            background-color: #d1edff;
            color: #0c5460;
            border: 1px solid #b8daff;
        }
        
        .status-rechazado {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .btn-sm {
            padding: 4px 8px;
            font-size: 12px;
            margin: 2px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        
        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }
        
        .final-actions {
            margin-top: 30px;
            padding: 25px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            background-color: #f8f9fa;
            text-align: center;
        }
        
        .final-status-section h4 {
            margin-bottom: 20px;
            color: #495057;
            font-weight: 600;
        }
        
        .final-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .final-btn {
            padding: 12px 20px;
            font-size: 16px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-width: 250px;
            justify-content: center;
        }
        
        .final-info {
            margin-top: 15px;
        }
        
        .product-observations {
            font-size: 12px;
            color: #6c757d;
            font-style: italic;
            margin-top: 5px;
            padding: 5px;
            background-color: #f8f9fa;
            border-radius: 4px;
            border-left: 3px solid #6c757d;
        }
        
        .timeline-type {
            font-size: 12px;
            color: #6c757d;
            font-style: italic;
            margin-top: 5px;
        }
        
        .marker-aprobado {
            background-color: #28a745;
        }
        
        .marker-rechazado {
            background-color: #dc3545;
        }
        
        .marker-pendiente {
            background-color: #ffc107;
        }
        
        .marker-en_proceso {
            background-color: #17a2b8;
        }
        
        .marker-completada {
            background-color: #6c757d;
        }
        
        /* Ajustes responsive para la tabla */
        @media (max-width: 768px) {
            .final-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .final-btn {
                min-width: 200px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</x-layout.default>