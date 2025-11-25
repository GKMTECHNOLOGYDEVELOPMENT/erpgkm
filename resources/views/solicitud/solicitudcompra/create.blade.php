<x-layout.default>
    <link rel="stylesheet" href="{{ asset('assets/css/createsolicitudcompra.css') }}">
    <div x-data="createPurchaseRequest()" class="create-container">
        <!-- Header -->
        <div class="create-header">
            <div class="header-content">
                <div class="back-section">
                    <a href="{{ route('solicitudcompra.index') }}" class="btn-back">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                        </svg>
                        Volver a Solicitudes
                    </a>
                </div>
                <div class="title-section">
                    <h1>Nueva Solicitud de Compra</h1>
                    <p>Complete los artículos y detalles de la solicitud</p>
                </div>
                <div class="actions-section">
                    <button type="button" class="btn btn-secondary" @click="resetForm()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                            <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                        </svg>
                        Limpiar
                    </button>
                    <button type="button" class="btn btn-primary" @click="submitForm()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z"/>
                        </svg>
                        Crear Solicitud
                    </button>
                </div>
            </div>
        </div>

        <form id="purchaseRequestForm" action="{{ route('solicitudcompra.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="create-content">
                <!-- Form Section -->
                <div class="form-section">
                    <!-- Información General -->
                    <div class="form-card">
                        <div class="form-header">
                            <div class="header-with-code">
                                <div>
                                    <h2>Información General</h2>
                                    <p>Datos básicos de la solicitud</p>
                                </div>
                                <div class="request-code">
                                    <span class="code-label">Código de Solicitud:</span>
                                    <span class="code-value" x-text="requestCode"></span>
                                    <input type="hidden" name="codigo_solicitud" x-model="requestCode">
                                    <button type="button" class="btn-copy" @click="copyCode()" title="Copiar código">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                                            <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Solicitud de Almacén *</label>
                                <select class="form-select" x-model="form.idSolicitudAlmacen" @change="loadAlmacenItems()" required>
                                    <option value="">Seleccione una solicitud de almacén</option>
                                    @foreach($solicitudesAlmacen as $solicitud)
                                        <option value="{{ $solicitud->idSolicitudAlmacen }}">
                                            {{ $solicitud->codigo_solicitud }} - {{ $solicitud->titulo }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="idSolicitudAlmacen" x-model="form.idSolicitudAlmacen">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Solicitante Almacén</label>
                                <input type="text" class="form-input" readonly
                                       x-model="form.solicitante_almacen" name="solicitante_almacen">
                                <small class="form-help">Cargado automáticamente desde la solicitud de almacén</small>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Solicitante Compra *</label>
                                <input type="text" class="form-input" placeholder="Nombre del solicitante para compra" 
                                    x-model="form.solicitante_compra" name="solicitante" required
                                    value="{{ $solicitanteCompra }}" readonly>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Departamento *</label>
                                <select class="form-select" x-model="form.idTipoArea" name="idTipoArea" required>
                                    <option value="">Seleccione departamento</option>
                                    @foreach($tipoAreas as $area)
                                        <option value="{{ $area->idTipoArea }}">{{ $area->nombre }}</option>
                                    @endforeach
                                </select>
                                <small class="form-help" x-show="form.departamento_auto">
                                    <span x-text="form.departamento_auto"></span> (desde almacén)
                                </small>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Prioridad *</label>
                                <select class="form-select" x-model="form.idPrioridad" name="idPrioridad" required>
                                    <option value="">Seleccione prioridad</option>
                                    @foreach($prioridades as $prioridad)
                                        <option value="{{ $prioridad->idPrioridad }}">
                                            {{ $prioridad->nombre }} (Nivel {{ $prioridad->nivel }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-help" x-show="form.prioridad_auto">
                                    <span x-text="form.prioridad_auto"></span> (desde almacén)
                                </small>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Fecha Requerida *</label>
                                <input type="date" class="form-input" x-model="form.fecha_requerida" 
                                       name="fecha_requerida" :min="new Date().toISOString().split('T')[0]" required>
                                <small class="form-help" x-show="form.fecha_requerida_auto">
                                    <span x-text="form.fecha_requerida_auto"></span> (desde almacén)
                                </small>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Centro de Costo</label>
                                <select class="form-select" x-model="form.idCentroCosto" name="idCentroCosto">
                                    <option value="">Seleccione centro de costo</option>
                                    @foreach($centrosCosto as $centro)
                                        <option value="{{ $centro->idCentroCosto }}">
                                            {{ $centro->codigo }} - {{ $centro->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-help" x-show="form.centro_costo_auto">
                                    <span x-text="form.centro_costo_auto"></span> (desde almacén)
                                </small>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Proyecto Asociado</label>
                                <input type="text" class="form-input" placeholder="Nombre del proyecto" 
                                       x-model="form.proyecto_asociado" name="proyecto_asociado">
                            </div>

                            <div class="form-group full-width">
                                <label class="form-label">Justificación *</label>
                                <textarea class="form-textarea" rows="3" placeholder="Explique por qué es necesaria esta compra" 
                                          x-model="form.justificacion" name="justificacion" required></textarea>
                                <small class="form-help" x-show="form.justificacion_auto">
                                    Justificación cargada desde almacén
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Artículos de la Solicitud -->
                    <div class="form-card">
                        <div class="form-header">
                            <div class="header-with-action">
                                <div>
                                    <h2>Artículos Solicitados</h2>
                                    <p>Productos cargados desde la solicitud de almacén</p>
                                </div>
                                <div class="loading-section" x-show="loadingAlmacen">
                                    <div class="loading-spinner"></div>
                                    <span>Cargando productos...</span>
                                </div>
                            </div>
                        </div>

                        <!-- Lista de Artículos -->
                        <div class="items-container">
                            <template x-for="(item, index) in form.items" :key="index">
                                <div class="item-card">
                                    <div class="item-header">
                                        <div class="item-info">
                                            <span class="item-number" x-text="`Artículo ${index + 1}`"></span>
                                            <span class="item-code" x-text="item.codigo_producto || `${requestCode}-${String(index + 1).padStart(2, '0')}`"></span>
                                            <span class="item-source" x-show="item.fromAlmacen">✓ Desde Almacén</span>
                                        </div>
                                    </div>

                                    <div class="item-grid">
                                        <div class="form-group full-width">
                                            <label class="form-label">Descripción del Artículo *</label>
                                            <input type="text" class="form-input" 
                                                   x-model="item.descripcion_producto" 
                                                   :name="`items[${index}][descripcion_producto]`" required readonly>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Categoría</label>
                                            <input type="text" class="form-input" 
                                                   x-model="item.categoria" 
                                                   :name="`items[${index}][categoria]`" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Cantidad Aprobada *</label>
                                            <input type="number" class="form-input" min="1" 
                                                   x-model="item.cantidad_aprobada" 
                                                   :name="`items[${index}][cantidad]`" 
                                                   @change="updateItemTotal(index)" required>
                                            <small class="form-help">Cantidad original: <span x-text="item.cantidad"></span></small>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Unidad</label>
                                            <input type="text" class="form-input" 
                                                   x-model="item.unidad" 
                                                   :name="`items[${index}][unidad]`" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Precio Unitario *</label>
                                            <div class="input-with-icon">
                                                <span class="input-icon">S/</span>
                                                <input type="number" class="form-input" min="0" step="0.01" 
                                                       x-model="item.precio_unitario_estimado" 
                                                       :name="`items[${index}][precio_unitario_estimado]`" 
                                                       @change="updateItemTotal(index)" required>
                                            </div>
                                            <small class="form-help">Precio estimado para compra</small>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Total</label>
                                            <div class="total-display">
                                                <span x-text="'S/' + (item.total_producto || '0.00')"></span>
                                                <input type="hidden" :name="`items[${index}][total_producto]`" x-model="item.total_producto">
                                            </div>
                                        </div>

                                        <div class="form-group full-width">
                                            <label class="form-label">Código del Producto</label>
                                            <input type="text" class="form-input" 
                                                   x-model="item.codigo_producto" 
                                                   :name="`items[${index}][codigo_producto]`" readonly>
                                            <input type="hidden" :name="`items[${index}][idSolicitudAlmacenDetalle]`" x-model="item.idSolicitudAlmacenDetalle">
                                            <input type="hidden" :name="`items[${index}][idArticulo]`" x-model="item.idArticulo">
                                        </div>

                                        <div class="form-group full-width">
                                            <label class="form-label">Marca</label>
                                            <input type="text" class="form-input" 
                                                   x-model="item.marca" 
                                                   :name="`items[${index}][marca]`" readonly>
                                        </div>

                                        <div class="form-group full-width">
                                            <label class="form-label">Proveedor Sugerido</label>
                                            <select class="form-select" 
                                                    x-model="item.idProveedor" 
                                                    :name="`items[${index}][idProveedor]`">
                                                <option value="">Seleccione un proveedor</option>
                                                @foreach($proveedores as $proveedor)
                                                    <option value="{{ $proveedor->idProveedor }}">
                                                        {{ $proveedor->nombre }} 
                                                        @if($proveedor->telefono)
                                                            - Tel: {{ $proveedor->telefono }}
                                                        @endif
                                                        @if($proveedor->email)
                                                            - Email: {{ $proveedor->email }}
                                                        @endif
                                                    </option>
                                                @endforeach
                                                <option value="otro">Otro proveedor...</option>
                                            </select>
                                            <input type="text" class="form-input mt-2" 
                                                   x-show="item.idProveedor === 'otro'"
                                                   placeholder="Especifique el nombre del proveedor"
                                                   x-model="item.proveedor_otro"
                                                   :name="`items[${index}][proveedor_otro]`">
                                            <input type="hidden" :name="`items[${index}][proveedor_sugerido]`" 
                                                   x-model="item.idProveedor === 'otro' ? item.proveedor_otro : getProveedorNombre(item.idProveedor)">
                                        </div>

                                        <div class="form-group full-width">
                                            <label class="form-label">Especificaciones Técnicas</label>
                                            <textarea class="form-textarea" rows="2" 
                                                      x-model="item.especificaciones_tecnicas" 
                                                      :name="`items[${index}][especificaciones_tecnicas]`" readonly></textarea>
                                        </div>

                                        <div class="form-group full-width">
                                            <label class="form-label">Justificación del Producto</label>
                                            <textarea class="form-textarea" rows="2" placeholder="Justifique por qué necesita este producto específico..." 
                                                      x-model="item.justificacion_producto" 
                                                      :name="`items[${index}][justificacion_producto]`"></textarea>
                                        </div>

                                        <div class="form-group full-width">
                                            <label class="form-label">Observaciones del Detalle</label>
                                            <textarea class="form-textarea" rows="2" placeholder="Observaciones adicionales para este producto..." 
                                                      x-model="item.observaciones_detalle" 
                                                      :name="`items[${index}][observaciones_detalle]`"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <div x-show="form.items.length === 0 && !loadingAlmacen" class="empty-items">
                                <div class="empty-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M0 1.5A1.5 1.5 0 0 1 1.5 0h13A1.5 1.5 0 0 1 16 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13zM1.5 1a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-13z"/>
                                        <path d="M3.5 3a.5.5 0 0 0-.5.5v2a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 0-.5-.5h-2zm3 0a.5.5 0 0 0-.5.5v5a.5.5 0 0 0 .5.5h5a.5.5 0 0 0 .5-.5v-5a.5.5 0 0 0-.5-.5h-5zm3 5a.5.5 0 0 0-.5.5v2a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 0-.5-.5h-2z"/>
                                    </svg>
                                </div>
                                <h3>No hay artículos cargados</h3>
                                <p>Seleccione una solicitud de almacén aprobada para cargar los productos</p>
                            </div>

                            <div x-show="loadingAlmacen" class="empty-items">
                                <div class="loading-spinner large"></div>
                                <h3>Cargando productos...</h3>
                                <p>Obteniendo los artículos de la solicitud de almacén</p>
                            </div>
                        </div>

                        <!-- Resumen de Artículos -->
                        <div class="items-summary" x-show="form.items.length > 0">
                            <div class="summary-grid">
                                <div class="summary-item">
                                    <span class="summary-label">Total de Artículos:</span>
                                    <span class="summary-value" x-text="form.items.length"></span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">Total Unidades:</span>
                                    <span class="summary-value" x-text="totalUnidades"></span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">Subtotal:</span>
                                    <span class="summary-value" x-text="'S/' + subtotal.toLocaleString('es-PE', {minimumFractionDigits: 2})"></span>
                                    <input type="hidden" name="subtotal" x-model="subtotal">
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">IGV (18%):</span>
                                    <span class="summary-value" x-text="'S/' + igv.toLocaleString('es-PE', {minimumFractionDigits: 2})"></span>
                                    <input type="hidden" name="iva" x-model="igv">
                                </div>
                                <div class="summary-item total">
                                    <span class="summary-label">Total General:</span>
                                    <span class="summary-value" x-text="'S/' + total.toLocaleString('es-PE', {minimumFractionDigits: 2})"></span>
                                    <input type="hidden" name="total" x-model="total">
                                    <input type="hidden" name="total_unidades" x-model="totalUnidades">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información Adicional -->
                    <div class="form-card">
                        <div class="form-header">
                            <h2>Información Adicional</h2>
                            <p>Detalles complementarios de la solicitud</p>
                        </div>

                        <div class="form-grid">
                            <div class="form-group full-width">
                                <label class="form-label">Observaciones</label>
                                <textarea class="form-textarea" rows="3" placeholder="Observaciones adicionales, condiciones especiales, etc." 
                                          x-model="form.observaciones" name="observaciones"></textarea>
                                <small class="form-help" x-show="form.observaciones_auto">
                                    Observaciones cargadas desde almacén
                                </small>
                            </div>

                            <div class="form-group full-width">
                                <label class="form-label">Archivos Adjuntos</label>
                                <div class="file-upload-area" @click="$refs.fileInput.click()">
                                    <input type="file" x-ref="fileInput" multiple class="file-input" 
                                           name="archivos[]" @change="handleFileSelect">
                                    <div class="file-upload-content">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                            <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/>
                                        </svg>
                                        <h4>Arrastre archivos o haga clic para seleccionar</h4>
                                        <p>Cotizaciones, imágenes, especificaciones - máximo 10MB por archivo</p>
                                    </div>
                                </div>
                                
                                <div x-show="form.files.length > 0" class="file-list">
                                    <div class="file-items">
                                        <template x-for="(file, index) in form.files" :key="index">
                                            <div class="file-item">
                                                <div class="file-info">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zm5.5 1.5v2a1 1 0 0 0 1 1h2l-3-3z"/>
                                                    </svg>
                                                    <span x-text="file.name"></span>
                                                </div>
                                                <button type="button" class="file-remove" @click="removeFile(index)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preview Section -->
                <div class="preview-section">
                    <div class="preview-card">
                        <div class="preview-header">
                            <div class="header-with-code">
                                <div>
                                    <h2>Resumen de la Solicitud</h2>
                                    <p>Vista previa antes de enviar</p>
                                </div>
                                <div class="preview-code">
                                    <span class="code-label">Código:</span>
                                    <span class="code-value" x-text="requestCode"></span>
                                </div>
                            </div>
                        </div>

                        <div class="preview-content">
                            <div class="preview-status">
                                <div class="status-badge preview">
                                    Nueva Solicitud
                                </div>
                            </div>

                            <div class="preview-section">
                                <h3>Información General</h3>
                                <div class="preview-item">
                                    <label>Código:</label>
                                    <span class="code-preview" x-text="requestCode"></span>
                                </div>
                                <div class="preview-item">
                                    <label>Solicitud Almacén:</label>
                                    <span x-text="getSolicitudAlmacenText(form.idSolicitudAlmacen) || 'No seleccionada'"></span>
                                </div>
                                <div class="preview-item">
                                    <label>Solicitante Almacén:</label>
                                    <span x-text="form.solicitante_almacen || 'No especificado'"></span>
                                </div>
                                <div class="preview-item">
                                    <label>Solicitante Compra:</label>
                                    <span x-text="form.solicitante_compra || 'No especificado'"></span>
                                </div>
                                <div class="preview-item">
                                    <label>Departamento:</label>
                                    <span x-text="getDepartmentText(form.idTipoArea) || 'No especificado'"></span>
                                </div>
                                <div class="preview-item">
                                    <label>Prioridad:</label>
                                    <span class="priority-badge" :class="'priority-' + form.idPrioridad" 
                                          x-text="getPriorityText(form.idPrioridad) || 'No especificada'"></span>
                                </div>
                                <div class="preview-item">
                                    <label>Fecha Requerida:</label>
                                    <span x-text="form.fecha_requerida ? formatPreviewDate(form.fecha_requerida) : 'No especificada'"></span>
                                </div>
                                <div class="preview-item" x-show="form.idCentroCosto">
                                    <label>Centro de Costo:</label>
                                    <span x-text="getCostCenterText(form.idCentroCosto)"></span>
                                </div>
                                <div class="preview-item" x-show="form.proyecto_asociado">
                                    <label>Proyecto:</label>
                                    <span x-text="form.proyecto_asociado"></span>
                                </div>
                            </div>

                            <div class="preview-section" x-show="form.items.length > 0">
                                <h3>Artículos Solicitados</h3>
                                <div class="preview-items">
                                    <template x-for="(item, index) in form.items" :key="index">
                                        <div class="preview-item-card">
                                            <div class="preview-item-header">
                                                <div class="item-title">
                                                    <strong x-text="item.descripcion_producto || 'Sin descripción'"></strong>
                                                    <span class="item-preview-code" x-text="item.codigo_producto || `${requestCode}-${String(index + 1).padStart(2, '0')}`"></span>
                                                </div>
                                                <span class="item-total" x-text="'S/' + (item.total_producto || '0.00')"></span>
                                            </div>
                                            <div class="preview-item-details">
                                                <span x-text="item.cantidad_aprobada + ' ' + (item.unidad || 'unidad')"></span>
                                                <span x-text="'S/' + (item.precio_unitario_estimado || '0.00') + ' c/u'"></span>
                                                <span x-show="item.categoria" x-text="item.categoria" class="item-category"></span>
                                            </div>
                                            <div class="preview-item-specs" x-show="item.especificaciones_tecnicas" 
                                                 x-text="item.especificaciones_tecnicas"></div>
                                            <div class="preview-item-vendor" x-show="item.idProveedor || item.proveedor_otro">
                                                <strong>Proveedor:</strong> 
                                                <span x-text="item.idProveedor === 'otro' ? item.proveedor_otro : getProveedorNombre(item.idProveedor)"></span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div class="preview-section" x-show="form.items.length > 0">
                                <h3>Resumen Financiero</h3>
                                <div class="preview-summary">
                                    <div class="preview-summary-item">
                                        <span>Subtotal:</span>
                                        <span x-text="'S/' + subtotal.toLocaleString('es-PE', {minimumFractionDigits: 2})"></span>
                                    </div>
                                    <div class="preview-summary-item">
                                        <span>IGV (18%):</span>
                                        <span x-text="'S/' + igv.toLocaleString('es-PE', {minimumFractionDigits: 2})"></span>
                                    </div>
                                    <div class="preview-summary-item total">
                                        <span>Total:</span>
                                        <span x-text="'S/' + total.toLocaleString('es-PE', {minimumFractionDigits: 2})"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="preview-section">
                                <h3>Justificación</h3>
                                <div class="preview-justification" x-text="form.justificacion || 'Sin justificación'"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Información de ayuda -->
                    <div class="help-card">
                        <h3>💡 Consejos para una buena solicitud</h3>
                        <ul class="help-list">
                            <li>Los productos se cargan automáticamente desde la solicitud de almacén aprobada</li>
                            <li>Verifique los precios unitarios antes de enviar</li>
                            <li>Revise las cantidades aprobadas</li>
                            <li>Agregue proveedores sugeridos cuando sea posible</li>
                            <li>Verifique que los totales sean correctos</li>
                            <li>El IGV aplicado es del 18%</li>
                        </ul>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function createPurchaseRequest() {
            return {
                form: {
                    idSolicitudAlmacen: '',
                    solicitante_almacen: '',
                    solicitante_compra: '',
                    idTipoArea: '',
                    idPrioridad: '',
                    fecha_requerida: '',
                    idCentroCosto: '',
                    proyecto_asociado: '',
                    justificacion: '',
                    observaciones: '',
                    items: [],
                    files: [],
                    // Campos para mostrar datos automáticos
                    departamento_auto: '',
                    prioridad_auto: '',
                    fecha_requerida_auto: '',
                    centro_costo_auto: '',
                    justificacion_auto: '',
                    observaciones_auto: ''
                },
                
                loadingAlmacen: false,
                solicitudesAlmacenData: @json($solicitudesAlmacen->keyBy('idSolicitudAlmacen')),
                proveedoresData: @json($proveedores),

                get requestCode() {
                    const now = new Date();
                    const year = now.getFullYear().toString().slice(-2);
                    const month = (now.getMonth() + 1).toString().padStart(2, '0');
                    const day = now.getDate().toString().padStart(2, '0');
                    const random = Math.floor(Math.random() * 999).toString().padStart(3, '0');
                    return `SC-${year}${month}${day}-${random}`;
                },

                init() {
                    const today = new Date().toISOString().split('T')[0];
                    this.form.fecha_requerida = today;
                    
                    // Establecer solicitante por defecto desde PHP
                    this.form.solicitante_compra = '{{ $solicitanteCompra }}';
                },

                get totalUnidades() {
                    return this.form.items.reduce((sum, item) => {
                        return sum + (parseInt(item.cantidad_aprobada) || 0);
                    }, 0);
                },

                get subtotal() {
                    return this.form.items.reduce((sum, item) => {
                        return sum + (parseFloat(item.total_producto) || 0);
                    }, 0);
                },

                get igv() {
                    return this.subtotal * 0.18; // IGV 18%
                },

                get total() {
                    return this.subtotal + this.igv;
                },

                async loadAlmacenItems() {
                    if (!this.form.idSolicitudAlmacen) {
                        this.resetAlmacenData();
                        return;
                    }

                    this.loadingAlmacen = true;
                    this.resetAlmacenData();

                    try {
                        const response = await fetch(`/solicitudcompra/solicitud-almacen/${this.form.idSolicitudAlmacen}/detalles`);
                        const result = await response.json();

                        if (result.success && result.detalles && result.detalles.length > 0) {
                            // Cargar datos de la solicitud para autocompletar
                            if (result.solicitud) {
                                this.autocompleteFormData(result.solicitud);
                            }

                            // Cargar los detalles de la solicitud de almacén
                            this.form.items = result.detalles.map(detalle => ({
                                idSolicitudAlmacenDetalle: detalle.idSolicitudAlmacenDetalle,
                                idArticulo: detalle.idArticulo,
                                descripcion_producto: detalle.descripcion_producto || '',
                                categoria: detalle.categoria || '',
                                cantidad: detalle.cantidad || 0,
                                cantidad_aprobada: detalle.cantidad_aprobada || detalle.cantidad || 1,
                                unidad: detalle.unidad || 'unidad',
                                precio_unitario_estimado: detalle.precio_unitario_estimado || 0,
                                total_producto: detalle.total_producto || 0,
                                codigo_producto: detalle.codigo_producto || '',
                                marca: detalle.marca || '',
                                especificaciones_tecnicas: detalle.especificaciones_tecnicas || '',
                                idProveedor: detalle.proveedor_sugerido || '',
                                proveedor_otro: '',
                                justificacion_producto: detalle.justificacion_producto || '',
                                observaciones_detalle: detalle.observaciones_detalle || '',
                                fromAlmacen: true
                            }));

                            // Calcular totales iniciales
                            this.form.items.forEach((item, index) => {
                                this.updateItemTotal(index);
                            });

                        } else {
                            alert('No se encontraron productos aprobados en esta solicitud de almacén');
                            this.form.items = [];
                        }
                    } catch (error) {
                        console.error('Error loading almacen items:', error);
                        alert('Error al cargar los productos de la solicitud de almacén');
                        this.form.items = [];
                    } finally {
                        this.loadingAlmacen = false;
                    }
                },

               autocompleteFormData(solicitudData) {
    // Autocompletar datos del formulario
    this.form.solicitante_almacen = solicitudData.solicitante_almacen || '';
    
    // Si no se ha llenado manualmente, autocompletar solicitante de compra
    if (!this.form.solicitante_compra && solicitudData.solicitante_compra) {
        this.form.solicitante_compra = solicitudData.solicitante_compra;
    }
    
    // Si no se ha llenado manualmente, autocompletar departamento
    if (!this.form.idTipoArea && solicitudData.idTipoArea) {
        this.form.idTipoArea = solicitudData.idTipoArea;
        this.form.departamento_auto = solicitudData.tipo_area_nombre;
    }
    
    if (!this.form.idPrioridad && solicitudData.idPrioridad) {
        this.form.idPrioridad = solicitudData.idPrioridad;
        this.form.prioridad_auto = solicitudData.prioridad_nombre;
    }
    
    if (!this.form.idCentroCosto && solicitudData.idCentroCosto) {
        this.form.idCentroCosto = solicitudData.idCentroCosto;
        this.form.centro_costo_auto = solicitudData.centro_costo_nombre;
    }
    
    if (!this.form.justificacion && solicitudData.justificacion) {
        this.form.justificacion = solicitudData.justificacion;
        this.form.justificacion_auto = solicitudData.justificacion;
    }
    
    if (!this.form.observaciones && solicitudData.observaciones) {
        this.form.observaciones = solicitudData.observaciones;
        this.form.observaciones_auto = solicitudData.observaciones;
    }
},

                resetAlmacenData() {
                    this.form.items = [];
                    this.form.solicitante_almacen = '';
                    this.form.departamento_auto = '';
                    this.form.prioridad_auto = '';
                    this.form.centro_costo_auto = '';
                    this.form.justificacion_auto = '';
                    this.form.observaciones_auto = '';
                },

                updateItemTotal(index) {
                    const item = this.form.items[index];
                    const quantity = parseFloat(item.cantidad_aprobada) || 0;
                    const unitPrice = parseFloat(item.precio_unitario_estimado) || 0;
                    item.total_producto = (quantity * unitPrice).toFixed(2);
                },

                getProveedorNombre(proveedorId) {
                    if (!proveedorId || proveedorId === 'otro') return '';
                    const proveedor = this.proveedoresData.find(p => p.idProveedor == proveedorId);
                    return proveedor ? proveedor.nombre : '';
                },

                getSolicitudAlmacenText(idSolicitudAlmacen) {
                    const solicitud = this.solicitudesAlmacenData[idSolicitudAlmacen];
                    return solicitud ? `${solicitud.codigo_solicitud} - ${solicitud.titulo}` : '';
                },

                getDepartmentText(idTipoArea) {
                    const departments = {
                        @foreach($tipoAreas as $area)
                            '{{ $area->idTipoArea }}': '{{ $area->nombre }}',
                        @endforeach
                    };
                    return departments[idTipoArea] || idTipoArea;
                },

                getCostCenterText(idCentroCosto) {
                    const costCenters = {
                        @foreach($centrosCosto as $centro)
                            '{{ $centro->idCentroCosto }}': '{{ $centro->codigo }} - {{ $centro->nombre }}',
                        @endforeach
                    };
                    return costCenters[idCentroCosto] || idCentroCosto;
                },

                getPriorityText(idPrioridad) {
                    const priorities = {
                        @foreach($prioridades as $prioridad)
                            '{{ $prioridad->idPrioridad }}': '{{ $prioridad->nombre }}',
                        @endforeach
                    };
                    return priorities[idPrioridad] || idPrioridad;
                },

                formatPreviewDate(dateString) {
                    const options = { year: 'numeric', month: 'long', day: 'numeric' };
                    return new Date(dateString).toLocaleDateString('es-ES', options);
                },

                copyCode() {
                    navigator.clipboard.writeText(this.requestCode).then(() => {
                        const btn = event.target.closest('.btn-copy');
                        const originalHTML = btn.innerHTML;
                        btn.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                            </svg>
                        `;
                        btn.style.color = '#10b981';
                        
                        setTimeout(() => {
                            btn.innerHTML = originalHTML;
                            btn.style.color = '';
                        }, 2000);
                    });
                },

                handleFileSelect(event) {
                    const files = Array.from(event.target.files);
                    files.forEach(file => {
                        this.form.files.push(file);
                    });
                },

                removeFile(index) {
                    this.form.files.splice(index, 1);
                },

                resetForm() {
                    if (confirm('¿Está seguro de que desea limpiar todos los campos?')) {
                        this.form = {
                            idSolicitudAlmacen: '',
                            solicitante_almacen: '',
                            solicitante_compra: '',
                            idTipoArea: '',
                            idPrioridad: '',
                            fecha_requerida: new Date().toISOString().split('T')[0],
                            idCentroCosto: '',
                            proyecto_asociado: '',
                            justificacion: '',
                            observaciones: '',
                            items: [],
                            files: [],
                            departamento_auto: '',
                            prioridad_auto: '',
                            fecha_requerida_auto: '',
                            centro_costo_auto: '',
                            justificacion_auto: '',
                            observaciones_auto: ''
                        };
                    }
                },

                submitForm() {
                    // Validación básica
                    if (!this.form.idSolicitudAlmacen || !this.form.solicitante_compra || !this.form.idTipoArea || 
                        !this.form.idPrioridad || !this.form.fecha_requerida || !this.form.justificacion) {
                        alert('Por favor complete todos los campos obligatorios (*)');
                        return;
                    }

                    if (this.form.items.length === 0) {
                        alert('Debe seleccionar una solicitud de almacén con productos aprobados');
                        return;
                    }

                    // Validar items
                    for (let i = 0; i < this.form.items.length; i++) {
                        const item = this.form.items[i];
                        if (!item.descripcion_producto || !item.cantidad_aprobada || !item.precio_unitario_estimado) {
                            alert(`Por favor complete todos los campos obligatorios del artículo ${i + 1}`);
                            return;
                        }
                    }

                    // Enviar formulario
                    document.getElementById('purchaseRequestForm').submit();
                }
            }
        }
    </script>
</x-layout.default>