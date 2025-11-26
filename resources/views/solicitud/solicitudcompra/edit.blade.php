<x-layout.default>
    <link rel="stylesheet" href="{{ asset('assets/css/createsolicitudcompra.css') }}">
    <div x-data="editPurchaseRequest()" class="create-container">
        <!-- Header -->
        <div class="create-header">
            <div class="header-content">
                <div class="back-section">
                    <a href="{{ route('solicitudcompra.show', $solicitud->idSolicitudCompra) }}" class="btn-back">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                        </svg>
                        Volver a Detalles
                    </a>
                </div>
                <div class="title-section">
                    <h1>Editar Solicitud de Compra</h1>
                    <p>Modifique los artículos y detalles de la solicitud {{ $solicitud->codigo_solicitud }}</p>
                </div>
                <div class="actions-section">
                    <button type="button" class="btn btn-secondary" @click="resetForm()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                            <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                        </svg>
                        Restablecer
                    </button>
                    <button type="button" class="btn btn-primary" @click="submitForm()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z"/>
                        </svg>
                        Actualizar Solicitud
                    </button>
                </div>
            </div>
        </div>

        <form id="purchaseRequestForm" action="{{ route('solicitudcompra.update', $solicitud->idSolicitudCompra) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Campos hidden importantes -->
            <input type="hidden" name="codigo_solicitud" value="{{ $solicitud->codigo_solicitud }}">
            <input type="hidden" name="idSolicitudAlmacen" value="{{ $solicitud->idSolicitudAlmacen }}">
            <input type="hidden" name="solicitante_compra" value="{{ $solicitud->solicitante_compra }}">
            <input type="hidden" name="solicitante_almacen" value="{{ $solicitud->solicitante_almacen }}">
            
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
                                    <span class="code-value">{{ $solicitud->codigo_solicitud }}</span>
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
                                <div class="form-input bg-gray-50 text-gray-700 cursor-not-allowed">
                                    @if($solicitud->solicitudAlmacen)
                                        {{ $solicitud->solicitudAlmacen->codigo_solicitud }} - {{ $solicitud->solicitudAlmacen->titulo }}
                                    @else
                                        No seleccionada
                                    @endif
                                </div>
                                <small class="form-help text-gray-500">La solicitud de almacén no se puede modificar en la edición</small>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Solicitante Almacén</label>
                                <input type="text" class="form-input bg-gray-50" readonly
                                       value="{{ $solicitud->solicitante_almacen }}">
                                <small class="form-help">Cargado desde la solicitud de almacén original</small>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Solicitante Compra *</label>
                                <input type="text" class="form-input bg-gray-50" readonly
                                    value="{{ $solicitud->solicitante_compra }}">
                                <small class="form-help">Usuario que creó la solicitud</small>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Departamento *</label>
                                <select class="form-select" x-model="form.idTipoArea" name="idTipoArea" required>
                                    <option value="">Seleccione departamento</option>
                                    @foreach($tipoAreas as $area)
                                        <option value="{{ $area->idTipoArea }}" 
                                            {{ $solicitud->idTipoArea == $area->idTipoArea ? 'selected' : '' }}>
                                            {{ $area->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Prioridad *</label>
                                <select class="form-select" x-model="form.idPrioridad" name="idPrioridad" required>
                                    <option value="">Seleccione prioridad</option>
                                    @foreach($prioridades as $prioridad)
                                        <option value="{{ $prioridad->idPrioridad }}"
                                            {{ $solicitud->idPrioridad == $prioridad->idPrioridad ? 'selected' : '' }}>
                                            {{ $prioridad->nombre }} (Nivel {{ $prioridad->nivel }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Fecha Requerida *</label>
                                <input type="date" class="form-input" x-model="form.fecha_requerida" 
                                       name="fecha_requerida" :min="new Date().toISOString().split('T')[0]" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Centro de Costo</label>
                                <select class="form-select" x-model="form.idCentroCosto" name="idCentroCosto">
                                    <option value="">Seleccione centro de costo</option>
                                    @foreach($centrosCosto as $centro)
                                        <option value="{{ $centro->idCentroCosto }}"
                                            {{ $solicitud->idCentroCosto == $centro->idCentroCosto ? 'selected' : '' }}>
                                            {{ $centro->codigo }} - {{ $centro->nombre }}
                                        </option>
                                    @endforeach
                                </select>
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
                            </div>
                        </div>

                        <!-- Lista de Artículos -->
                        <div class="items-container">
                            <template x-for="(item, index) in form.items" :key="index">
                                <div class="item-card">
                                    <div class="item-header">
                                        <div class="item-info">
                                            <span class="item-number" x-text="`Artículo ${index + 1}`"></span>
                                            <span class="item-code" x-text="item.codigo_producto || '{{ $solicitud->codigo_solicitud }}-${String(index + 1).padStart(2, '0')}'"></span>
                                            <span class="item-source" x-show="item.fromAlmacen">✓ Desde Almacén</span>
                                            <span class="item-source" x-show="!item.fromAlmacen">✎ Editado</span>
                                        </div>
                                    </div>

                                    <div class="item-grid">
                                        <div class="form-group full-width">
                                            <label class="form-label">Descripción del Artículo *</label>
                                            <input type="text" class="form-input" 
                                                   x-model="item.descripcion_producto" 
                                                   :name="`items[${index}][descripcion_producto]`" 
                                                   :readonly="item.fromAlmacen" required>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Categoría</label>
                                            <input type="text" class="form-input" 
                                                   x-model="item.categoria" 
                                                   :name="`items[${index}][categoria]`" 
                                                   :readonly="item.fromAlmacen">
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Cantidad *</label>
                                            <input type="number" class="form-input" min="1" 
                                                   x-model="item.cantidad" 
                                                   :name="`items[${index}][cantidad]`" 
                                                   @change="updateItemTotal(index)" required>
                                            <small class="form-help" x-show="item.fromAlmacen && item.cantidad_original">
                                                Cantidad original: <span x-text="item.cantidad_original"></span>
                                            </small>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Unidad</label>
                                            <input type="text" class="form-input" 
                                                   x-model="item.unidad" 
                                                   :name="`items[${index}][unidad]`" 
                                                   :readonly="item.fromAlmacen">
                                        </div>

                                        <!-- PRECIO UNITARIO CON SELECTOR DE MONEDA POR CLIC -->
                                        <div class="form-group">
                                            <label class="form-label">Precio Unitario *</label>
                                            <div class="price-currency-container">
                                                <div class="currency-selector-clickable">
                                                    <button type="button" class="currency-btn" 
                                                            @click="cycleCurrency(index)"
                                                            :title="getMonedaNombre(item.idMonedas)">
                                                        <span class="currency-symbol" x-text="getMonedaSimbolo(item.idMonedas)"></span>
                                                        <svg class="currency-arrow" xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                                                            <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/>
                                                        </svg>
                                                    </button>
                                                    <input type="hidden" :name="`items[${index}][idMonedas]`" x-model="item.idMonedas">
                                                </div>
                                                <div class="price-input-container">
                                                    <input type="number" class="form-input price-input" min="0" step="0.01" 
                                                           x-model="item.precio_unitario_estimado" 
                                                           :name="`items[${index}][precio_unitario_estimado]`" 
                                                           @change="updateItemTotal(index)" required>
                                                </div>
                                            </div>
                                            <small class="form-help">
                                                <span x-text="getMonedaNombre(item.idMonedas)"></span> - Precio estimado para compra
                                            </small>
                                        </div>

                                        <!-- TOTAL DEL PRODUCTO -->
                                        <div class="form-group">
                                            <label class="form-label">Total</label>
                                            <div class="total-display">
                                                <span class="total-amount" x-text="getMonedaSimbolo(item.idMonedas) + (item.total_producto || '0.00')"></span>
                                                <input type="hidden" :name="`items[${index}][total_producto]`" x-model="item.total_producto">
                                            </div>
                                        </div>

                                        <div class="form-group full-width">
                                            <label class="form-label">Código del Producto</label>
                                            <input type="text" class="form-input" 
                                                   x-model="item.codigo_producto" 
                                                   :name="`items[${index}][codigo_producto]`" 
                                                   :readonly="item.fromAlmacen">
                                            <input type="hidden" :name="`items[${index}][idSolicitudAlmacenDetalle]`" x-model="item.idSolicitudAlmacenDetalle">
                                            <input type="hidden" :name="`items[${index}][idArticulo]`" x-model="item.idArticulo">
                                        </div>

                                        <div class="form-group full-width">
                                            <label class="form-label">Marca</label>
                                            <input type="text" class="form-input" 
                                                   x-model="item.marca" 
                                                   :name="`items[${index}][marca]`" 
                                                   :readonly="item.fromAlmacen">
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
                                                      :name="`items[${index}][especificaciones_tecnicas]`" 
                                                      :readonly="item.fromAlmacen"></textarea>
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

                            <div x-show="form.items.length === 0" class="empty-items">
                                <div class="empty-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M0 1.5A1.5 1.5 0 0 1 1.5 0h13A1.5 1.5 0 0 1 16 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13zM1.5 1a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-13z"/>
                                        <path d="M3.5 3a.5.5 0 0 0-.5.5v2a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 0-.5-.5h-2zm3 0a.5.5 0 0 0-.5.5v5a.5.5 0 0 0 .5.5h5a.5.5 0 0 0 .5-.5v-5a.5.5 0 0 0-.5-.5h-5zm3 5a.5.5 0 0 0-.5.5v2a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 0-.5-.5h-2z"/>
                                    </svg>
                                </div>
                                <h3>No hay artículos cargados</h3>
                                <p>No se encontraron productos en esta solicitud</p>
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
                                    <span class="summary-value" x-text="getResumenMoneda() + subtotal.toLocaleString('es-PE', {minimumFractionDigits: 2})"></span>
                                    <input type="hidden" name="subtotal" x-model="subtotal">
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">IGV (18%):</span>
                                    <span class="summary-value" x-text="getResumenMoneda() + igv.toLocaleString('es-PE', {minimumFractionDigits: 2})"></span>
                                    <input type="hidden" name="iva" x-model="igv">
                                </div>
                                <div class="summary-item total">
                                    <span class="summary-label">Total General:</span>
                                    <span class="summary-value" x-text="getResumenMoneda() + total.toLocaleString('es-PE', {minimumFractionDigits: 2})"></span>
                                    <input type="hidden" name="total" x-model="total">
                                    <input type="hidden" name="total_unidades" x-model="totalUnidades">
                                </div>
                                <div class="summary-item" x-show="hasMultipleCurrencies">
                                    <span class="summary-label">Monedas Utilizadas:</span>
                                    <span class="summary-value" x-text="getMonedasUtilizadas()"></span>
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
                                
                                <!-- Archivos existentes -->
                                <div x-show="existingFiles.length > 0" class="file-list mt-4">
                                    <div class="file-section-label">Archivos existentes:</div>
                                    <div class="file-items">
                                        <template x-for="(file, index) in existingFiles" :key="index">
                                            <div class="file-item existing">
                                                <div class="file-info">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zm5.5 1.5v2a1 1 0 0 0 1 1h2l-3-3z"/>
                                                    </svg>
                                                    <span x-text="file.nombre_archivo"></span>
                                                    <span class="file-size" x-text="`(${Math.round(file.tamaño / 1024)} KB)`"></span>
                                                </div>
                                                <div class="file-actions">
                                                    <a :href="file.ruta_completa" target="_blank" class="file-view" title="Ver archivo">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <!-- Nuevos archivos seleccionados -->
                                <div x-show="form.files.length > 0" class="file-list mt-4">
                                    <div class="file-section-label">Nuevos archivos:</div>
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
                                    <p>Vista previa antes de actualizar</p>
                                </div>
                                <div class="preview-code">
                                    <span class="code-label">Código:</span>
                                    <span class="code-value">{{ $solicitud->codigo_solicitud }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="preview-content">
                            <div class="preview-status">
                                <div class="status-badge preview">
                                    Editando Solicitud
                                </div>
                            </div>

                            <div class="preview-section">
                                <h3>Información General</h3>
                                <div class="preview-item">
                                    <label>Código:</label>
                                    <span class="code-preview">{{ $solicitud->codigo_solicitud }}</span>
                                </div>
                                <div class="preview-item">
                                    <label>Solicitud Almacén:</label>
                                    <span x-text="getSolicitudAlmacenText()"></span>
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
                                                    <span class="item-preview-code" x-text="item.codigo_producto || '{{ $solicitud->codigo_solicitud }}-${String(index + 1).padStart(2, '0')}'"></span>
                                                </div>
                                                <span class="item-total" x-text="getMonedaSimbolo(item.idMonedas) + (item.total_producto || '0.00')"></span>
                                            </div>
                                            <div class="preview-item-details">
                                                <span x-text="item.cantidad + ' ' + (item.unidad || 'unidad')"></span>
                                                <span x-text="getMonedaSimbolo(item.idMonedas) + (item.precio_unitario_estimado || '0.00') + ' c/u'"></span>
                                                <span x-show="item.categoria" x-text="item.categoria" class="item-category"></span>
                                                <span class="currency-badge" x-text="getMonedaNombre(item.idMonedas)"></span>
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
                                        <span x-text="getResumenMoneda() + subtotal.toLocaleString('es-PE', {minimumFractionDigits: 2})"></span>
                                    </div>
                                    <div class="preview-summary-item">
                                        <span>IGV (18%):</span>
                                        <span x-text="getResumenMoneda() + igv.toLocaleString('es-PE', {minimumFractionDigits: 2})"></span>
                                    </div>
                                    <div class="preview-summary-item total">
                                        <span>Total:</span>
                                        <span x-text="getResumenMoneda() + total.toLocaleString('es-PE', {minimumFractionDigits: 2})"></span>
                                    </div>
                                    <div class="preview-summary-item" x-show="hasMultipleCurrencies">
                                        <span>Monedas Utilizadas:</span>
                                        <span x-text="getMonedasUtilizadas()"></span>
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
                        <h3>💡 Información de Edición</h3>
                        <ul class="help-list">
                            <li>Los campos marcados como "Desde Almacén" no se pueden modificar</li>
                            <li>Puede ajustar cantidades, precios y proveedores</li>
                            <li>Haga clic en el símbolo de moneda para cambiar entre diferentes tipos</li>
                            <li>Los archivos existentes se mantendrán al actualizar</li>
                            <li>Los nuevos archivos se agregarán a los existentes</li>
                            <li>Verifique que los totales sean correctos antes de actualizar</li>
                            <li>El IGV aplicado es del 18%</li>
                        </ul>
                    </div>
                </div>
            </div>
        </form>
    </div>

 <script>
function editPurchaseRequest() {
    return {
        form: {
            idSolicitudAlmacen: '{{ $solicitud->idSolicitudAlmacen }}',
            solicitante_almacen: '{{ $solicitud->solicitante_almacen }}',
            solicitante_compra: '{{ $solicitud->solicitante_compra }}',
            idTipoArea: '{{ $solicitud->idTipoArea }}',
            idPrioridad: '{{ $solicitud->idPrioridad }}',
            fecha_requerida: '{{ $solicitud->fecha_requerida ? \Carbon\Carbon::parse($solicitud->fecha_requerida)->format('Y-m-d') : '' }}',
            idCentroCosto: '{{ $solicitud->idCentroCosto }}',
            proyecto_asociado: '{{ $solicitud->proyecto_asociado }}',
            justificacion: `{!! addslashes($solicitud->justificacion) !!}`,
            observaciones: `{!! addslashes($solicitud->observaciones) !!}`,
            items: [
                @foreach($solicitud->detalles as $detalle)
                {
                    idSolicitudCompraDetalle: {{ $detalle->idSolicitudCompraDetalle }},
                    idSolicitudAlmacenDetalle: {{ $detalle->idSolicitudAlmacenDetalle ?? 'null' }},
                    idArticulo: {{ $detalle->idArticulo ?? 'null' }},
                    descripcion_producto: '{{ addslashes($detalle->descripcion_producto) }}',
                    categoria: '{{ addslashes($detalle->categoria ?? '') }}',
                    cantidad: {{ $detalle->cantidad }},
                    cantidad_original: {{ $detalle->cantidad }},
                    unidad: '{{ addslashes($detalle->unidad ?? 'unidad') }}',
                    precio_unitario_estimado: {{ $detalle->precio_unitario_estimado }},
                    total_producto: {{ $detalle->total_producto }},
                    codigo_producto: '{{ addslashes($detalle->codigo_producto ?? '') }}',
                    marca: '{{ addslashes($detalle->marca ?? '') }}',
                    especificaciones_tecnicas: '{{ addslashes($detalle->especificaciones_tecnicas ?? '') }}',
                    idProveedor: '{{ $detalle->proveedor_sugerido ? 'otro' : '' }}',
                    proveedor_otro: '{{ addslashes($detalle->proveedor_sugerido ?? '') }}',
                    justificacion_producto: '{{ addslashes($detalle->justificacion_producto ?? '') }}',
                    observaciones_detalle: '{{ addslashes($detalle->observaciones_detalle ?? '') }}',
                    idMonedas: {{ $detalle->idMonedas ?? 1 }},
                    fromAlmacen: {{ $detalle->idSolicitudAlmacenDetalle ? 'true' : 'false' }}
                },
                @endforeach
            ],
            files: []
        },
        
        existingFiles: [
            @foreach($solicitud->archivos as $archivo)
            {
                idSolicitudCompraArchivo: {{ $archivo->idSolicitudCompraArchivo }},
                nombre_archivo: '{{ addslashes($archivo->nombre_archivo) }}',
                ruta_archivo: '{{ addslashes($archivo->ruta_archivo) }}',
                tipo_archivo: '{{ addslashes($archivo->tipo_archivo) }}',
                tamaño: {{ $archivo->tamaño }},
                ruta_completa: '{{ asset('storage/' . $archivo->ruta_archivo) }}'
            },
            @endforeach
        ],
        
        proveedoresData: @json($proveedores),
        monedasData: @json($monedas->keyBy('idMonedas')),
        monedasList: @json($monedas),

        init() {
            console.log('Edit Purchase Request initialized');
            console.log('Items loaded:', this.form.items.length);
            console.log('Existing files:', this.existingFiles.length);
            
            // Inicializar los totales de los items
            this.form.items.forEach((item, index) => {
                this.updateItemTotal(index);
            });
        },

        get totalUnidades() {
            return this.form.items.reduce((sum, item) => {
                return sum + (parseInt(item.cantidad) || 0);
            }, 0);
        },

        get subtotal() {
            return this.form.items.reduce((sum, item) => {
                return sum + (parseFloat(item.total_producto) || 0);
            }, 0);
        },

        get igv() {
            return this.subtotal * 0.18;
        },

        get total() {
            return this.subtotal + this.igv;
        },

        get hasMultipleCurrencies() {
            const currencies = new Set();
            this.form.items.forEach(item => {
                if (item.idMonedas) {
                    currencies.add(item.idMonedas);
                }
            });
            return currencies.size > 1;
        },

        updateItemTotal(index) {
            const item = this.form.items[index];
            const quantity = parseFloat(item.cantidad) || 0;
            const unitPrice = parseFloat(item.precio_unitario_estimado) || 0;
            item.total_producto = (quantity * unitPrice).toFixed(2);
            
            // Forzar actualización de Alpine.js
            this.form.items = [...this.form.items];
        },

        cycleCurrency(index) {
            const item = this.form.items[index];
            const currentCurrencyId = item.idMonedas || 1;
            
            const currentIndex = this.monedasList.findIndex(moneda => moneda.idMonedas == currentCurrencyId);
            const nextIndex = (currentIndex + 1) % this.monedasList.length;
            const nextCurrency = this.monedasList[nextIndex];
            
            item.idMonedas = nextCurrency.idMonedas;
            this.updateItemTotal(index);
        },

        getProveedorNombre(proveedorId) {
            if (!proveedorId || proveedorId === 'otro') return '';
            const proveedor = this.proveedoresData.find(p => p.idProveedor == proveedorId);
            return proveedor ? proveedor.nombre : '';
        },

        getMonedaSimbolo(idMonedas) {
            if (!idMonedas) return 'S/';
            const moneda = this.monedasData[idMonedas];
            return moneda ? moneda.simbolo : 'S/';
        },

        getMonedaNombre(idMonedas) {
            if (!idMonedas) return 'Sol Peruano';
            const moneda = this.monedasData[idMonedas];
            return moneda ? moneda.nombre : 'Sol Peruano';
        },

        getResumenMoneda() {
            if (this.form.items.length === 0) return 'S/';
            
            const currencyCount = {};
            this.form.items.forEach(item => {
                if (item.idMonedas) {
                    currencyCount[item.idMonedas] = (currencyCount[item.idMonedas] || 0) + 1;
                }
            });
            
            const mostCommonCurrency = Object.keys(currencyCount).reduce((a, b) => 
                currencyCount[a] > currencyCount[b] ? a : b, 1
            );
            
            return this.getMonedaSimbolo(mostCommonCurrency);
        },

        getMonedasUtilizadas() {
            const currencies = new Set();
            this.form.items.forEach(item => {
                if (item.idMonedas) {
                    currencies.add(this.getMonedaNombre(item.idMonedas));
                }
            });
            return Array.from(currencies).join(', ');
        },

        getSolicitudAlmacenText() {
            return '{{ $solicitud->solicitudAlmacen ? $solicitud->solicitudAlmacen->codigo_solicitud . " - " . $solicitud->solicitudAlmacen->titulo : "No seleccionada" }}';
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
            if (!dateString) return 'No especificada';
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('es-ES', options);
        },

        copyCode() {
            navigator.clipboard.writeText('{{ $solicitud->codigo_solicitud }}').then(() => {
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
                if (file.size > 10 * 1024 * 1024) {
                    alert('El archivo ' + file.name + ' excede el tamaño máximo de 10MB');
                    return;
                }
                this.form.files.push(file);
            });
            event.target.value = '';
        },

        removeFile(index) {
            this.form.files.splice(index, 1);
        },

        resetForm() {
            if (confirm('¿Está seguro de que desea restablecer todos los cambios?')) {
                window.location.reload();
            }
        },

        submitForm() {
            // Validar campos obligatorios
            if (!this.form.idTipoArea || !this.form.idPrioridad || !this.form.fecha_requerida || !this.form.justificacion) {
                alert('Por favor complete todos los campos obligatorios (*)');
                return;
            }

            // Validar que haya items
            if (this.form.items.length === 0) {
                alert('Debe haber al menos un artículo en la solicitud');
                return;
            }

            // Validar cada item
            for (let i = 0; i < this.form.items.length; i++) {
                const item = this.form.items[i];
                if (!item.descripcion_producto || !item.cantidad || !item.precio_unitario_estimado || !item.idMonedas) {
                    alert(`Por favor complete todos los campos obligatorios del artículo ${i + 1}`);
                    return;
                }
                
                if (item.cantidad <= 0) {
                    alert(`La cantidad del artículo ${i + 1} debe ser mayor a 0`);
                    return;
                }
                
                if (item.precio_unitario_estimado < 0) {
                    alert(`El precio unitario del artículo ${i + 1} no puede ser negativo`);
                    return;
                }
            }

            // Mostrar confirmación
            if (confirm('¿Está seguro de que desea actualizar la solicitud de compra?')) {
                document.getElementById('purchaseRequestForm').submit();
            }
        }
    }
}
</script>
    <style>
    .price-currency-container {
        display: grid;
        grid-template-columns: auto 1fr;
        gap: 10px;
        align-items: start;
    }

    .currency-selector-clickable {
        display: flex;
    }

    .currency-btn {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 8px 12px;
        background: #f8fafc;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
        font-weight: 600;
        color: #374151;
        min-width: 70px;
        justify-content: center;
    }

    .currency-btn:hover {
        background: #e5e7eb;
        border-color: #9ca3af;
    }

    .currency-btn:active {
        background: #d1d5db;
        transform: scale(0.98);
    }

    .currency-symbol {
        font-weight: 700;
        font-size: 0.9rem;
    }

    .currency-arrow {
        opacity: 0.6;
        transition: transform 0.2s ease;
    }

    .currency-btn:hover .currency-arrow {
        opacity: 0.8;
        transform: translateY(1px);
    }

    .price-input-container {
        flex: 1;
    }

    .price-input {
        width: 100%;
    }

    .total-display .total-amount {
        font-weight: 600;
        color: #059669;
        font-size: 1rem;
    }

    .currency-badge {
        background: #f3f4f6;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        color: #6b7280;
        border: 1px solid #e5e7eb;
        font-weight: 500;
    }

    .item-source {
        background: #d1fae5;
        color: #065f46;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .file-item.existing {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
    }

    .file-section-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
        display: block;
    }

    .file-size {
        font-size: 0.75rem;
        color: #9ca3af;
        margin-left: 0.5rem;
    }

    .file-actions {
        display: flex;
        gap: 0.5rem;
    }

    .file-view {
        color: #6b7280;
        transition: color 0.2s;
        padding: 4px;
        border-radius: 4px;
    }

    .file-view:hover {
        color: #3b82f6;
        background: #eff6ff;
    }

    .form-input:read-only {
        background-color: #f9fafb;
        border-color: #d1d5db;
        color: #6b7280;
        cursor: not-allowed;
    }

    .form-textarea:read-only {
        background-color: #f9fafb;
        border-color: #d1d5db;
        color: #6b7280;
        cursor: not-allowed;
    }
    </style>
</x-layout.default>