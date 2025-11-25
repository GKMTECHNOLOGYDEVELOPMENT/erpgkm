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
                    <button type="submit" class="btn btn-primary" @click="submitForm()">
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
                                    <input type="hidden" name="codigo_solicitud" value="{{ $solicitud->codigo_solicitud }}">
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
                                <input type="hidden" name="idSolicitudAlmacen" value="{{ $solicitud->idSolicitudAlmacen }}">
                                <small class="form-help text-gray-500">La solicitud de almacén no se puede modificar en la edición</small>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Solicitante *</label>
                                <input type="text" class="form-input" placeholder="Nombre completo" 
                                       name="solicitante" value="{{ old('solicitante', $solicitud->solicitante) }}" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Departamento *</label>
                                <select class="form-select" name="idTipoArea" required>
                                    <option value="">Seleccione departamento</option>
                                    @foreach($tipoAreas as $area)
                                        <option value="{{ $area->idTipoArea }}" 
                                            {{ old('idTipoArea', $solicitud->idTipoArea) == $area->idTipoArea ? 'selected' : '' }}>
                                            {{ $area->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Prioridad *</label>
                                <select class="form-select" name="idPrioridad" required>
                                    <option value="">Seleccione prioridad</option>
                                    @foreach($prioridades as $prioridad)
                                        <option value="{{ $prioridad->idPrioridad }}"
                                            {{ old('idPrioridad', $solicitud->idPrioridad) == $prioridad->idPrioridad ? 'selected' : '' }}>
                                            {{ $prioridad->nombre }} (Nivel {{ $prioridad->nivel }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Fecha Requerida *</label>
                                <input type="date" class="form-input" 
                                    name="fecha_requerida" 
                                    value="{{ old('fecha_requerida', $solicitud->fecha_requerida ? \Carbon\Carbon::parse($solicitud->fecha_requerida)->format('Y-m-d') : '') }}" 
                                    min="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Centro de Costo</label>
                                <select class="form-select" name="idCentroCosto">
                                    <option value="">Seleccione centro de costo</option>
                                    @foreach($centrosCosto as $centro)
                                        <option value="{{ $centro->idCentroCosto }}"
                                            {{ old('idCentroCosto', $solicitud->idCentroCosto) == $centro->idCentroCosto ? 'selected' : '' }}>
                                            {{ $centro->codigo }} - {{ $centro->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Proyecto Asociado</label>
                                <input type="text" class="form-input" placeholder="Nombre del proyecto" 
                                       name="proyecto_asociado" value="{{ old('proyecto_asociado', $solicitud->proyecto_asociado) }}">
                            </div>

                            <div class="form-group full-width">
                                <label class="form-label">Justificación *</label>
                                <textarea class="form-textarea" rows="3" placeholder="Explique por qué es necesaria esta compra" 
                                          name="justificacion" required>{{ old('justificacion', $solicitud->justificacion) }}</textarea>
                            </div>

                            <div class="form-group full-width">
                                <label class="form-label">Observaciones</label>
                                <textarea class="form-textarea" rows="3" placeholder="Observaciones adicionales, condiciones especiales, etc." 
                                          name="observaciones">{{ old('observaciones', $solicitud->observaciones) }}</textarea>
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
                            @foreach($solicitud->detalles as $index => $detalle)
                            <div class="item-card">
                                <div class="item-header">
                                    <div class="item-info">
                                        <span class="item-number">Artículo {{ $index + 1 }}</span>
                                        <span class="item-code">{{ $detalle->codigo_producto ?? $solicitud->codigo_solicitud . '-' . str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                        <span class="item-source">{{ $detalle->idSolicitudAlmacenDetalle ? '✓ Desde Almacén' : '✎ Editado' }}</span>
                                    </div>
                                </div>

                                <div class="item-grid">
                                    <div class="form-group full-width">
                                        <label class="form-label">Descripción del Artículo *</label>
                                        <input type="text" class="form-input" 
                                               name="items[{{ $index }}][descripcion_producto]" 
                                               value="{{ old('items.' . $index . '.descripcion_producto', $detalle->descripcion_producto) }}" 
                                               {{ $detalle->idSolicitudAlmacenDetalle ? 'readonly' : '' }} required>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Categoría</label>
                                        <input type="text" class="form-input" 
                                               name="items[{{ $index }}][categoria]" 
                                               value="{{ old('items.' . $index . '.categoria', $detalle->categoria) }}" 
                                               {{ $detalle->idSolicitudAlmacenDetalle ? 'readonly' : '' }}>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Cantidad *</label>
                                        <input type="number" class="form-input" min="1" 
                                               name="items[{{ $index }}][cantidad]" 
                                               value="{{ old('items.' . $index . '.cantidad', $detalle->cantidad) }}" 
                                               onchange="updateItemTotal({{ $index }})" required>
                                        @if($detalle->idSolicitudAlmacenDetalle)
                                        <small class="form-help">Cantidad original: {{ $detalle->cantidad }}</small>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Unidad</label>
                                        <input type="text" class="form-input" 
                                               name="items[{{ $index }}][unidad]" 
                                               value="{{ old('items.' . $index . '.unidad', $detalle->unidad) }}" 
                                               {{ $detalle->idSolicitudAlmacenDetalle ? 'readonly' : '' }}>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Precio Unitario *</label>
                                        <div class="input-with-icon">
                                            <span class="input-icon">$</span>
                                            <input type="number" class="form-input" min="0" step="0.01" 
                                                   name="items[{{ $index }}][precio_unitario_estimado]" 
                                                   value="{{ old('items.' . $index . '.precio_unitario_estimado', $detalle->precio_unitario_estimado) }}" 
                                                   onchange="updateItemTotal({{ $index }})" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Total</label>
                                        <div class="total-display">
                                            <span id="total-{{ $index }}">${{ number_format($detalle->total_producto, 2) }}</span>
                                            <input type="hidden" name="items[{{ $index }}][total_producto]" 
                                                   value="{{ old('items.' . $index . '.total_producto', $detalle->total_producto) }}" 
                                                   id="total-input-{{ $index }}">
                                        </div>
                                    </div>

                                    <div class="form-group full-width">
                                        <label class="form-label">Código del Producto</label>
                                        <input type="text" class="form-input" 
                                               name="items[{{ $index }}][codigo_producto]" 
                                               value="{{ old('items.' . $index . '.codigo_producto', $detalle->codigo_producto) }}" 
                                               {{ $detalle->idSolicitudAlmacenDetalle ? 'readonly' : '' }}>
                                        <input type="hidden" name="items[{{ $index }}][idSolicitudAlmacenDetalle]" 
                                               value="{{ $detalle->idSolicitudAlmacenDetalle }}">
                                        <input type="hidden" name="items[{{ $index }}][idArticulo]" 
                                               value="{{ $detalle->idArticulo }}">
                                    </div>

                                    <div class="form-group full-width">
                                        <label class="form-label">Marca</label>
                                        <input type="text" class="form-input" 
                                               name="items[{{ $index }}][marca]" 
                                               value="{{ old('items.' . $index . '.marca', $detalle->marca) }}" 
                                               {{ $detalle->idSolicitudAlmacenDetalle ? 'readonly' : '' }}>
                                    </div>

                                    <div class="form-group full-width">
                                        <label class="form-label">Especificaciones Técnicas</label>
                                        <textarea class="form-textarea" rows="2" 
                                                  name="items[{{ $index }}][especificaciones_tecnicas]" 
                                                  {{ $detalle->idSolicitudAlmacenDetalle ? 'readonly' : '' }}>{{ old('items.' . $index . '.especificaciones_tecnicas', $detalle->especificaciones_tecnicas) }}</textarea>
                                    </div>

                                    <div class="form-group full-width">
                                        <label class="form-label">Proveedor Sugerido</label>
                                        <input type="text" class="form-input" placeholder="Nombre del proveedor" 
                                               name="items[{{ $index }}][proveedor_sugerido]" 
                                               value="{{ old('items.' . $index . '.proveedor_sugerido', $detalle->proveedor_sugerido) }}">
                                    </div>

                                    <div class="form-group full-width">
                                        <label class="form-label">Justificación del Producto</label>
                                        <textarea class="form-textarea" rows="2" placeholder="Justifique por qué necesita este producto específico..." 
                                                  name="items[{{ $index }}][justificacion_producto]">{{ old('items.' . $index . '.justificacion_producto', $detalle->justificacion_producto) }}</textarea>
                                    </div>

                                    <div class="form-group full-width">
                                        <label class="form-label">Observaciones del Detalle</label>
                                        <textarea class="form-textarea" rows="2" placeholder="Observaciones adicionales para este producto..." 
                                                  name="items[{{ $index }}][observaciones_detalle]">{{ old('items.' . $index . '.observaciones_detalle', $detalle->observaciones_detalle) }}</textarea>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Resumen de Artículos -->
                        <div class="items-summary">
                            <div class="summary-grid">
                                <div class="summary-item">
                                    <span class="summary-label">Total de Artículos:</span>
                                    <span class="summary-value">{{ $solicitud->detalles->count() }}</span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">Total Unidades:</span>
                                    <span class="summary-value" id="total-unidades">{{ $solicitud->total_unidades }}</span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">Subtotal:</span>
                                    <span class="summary-value" id="subtotal">${{ number_format($solicitud->subtotal, 2) }}</span>
                                    <input type="hidden" name="subtotal" value="{{ $solicitud->subtotal }}" id="subtotal-input">
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">IVA (19%):</span>
                                    <span class="summary-value" id="iva">${{ number_format($solicitud->iva, 2) }}</span>
                                    <input type="hidden" name="iva" value="{{ $solicitud->iva }}" id="iva-input">
                                </div>
                                <div class="summary-item total">
                                    <span class="summary-label">Total General:</span>
                                    <span class="summary-value" id="total-general">${{ number_format($solicitud->total, 2) }}</span>
                                    <input type="hidden" name="total" value="{{ $solicitud->total }}" id="total-general-input">
                                    <input type="hidden" name="total_unidades" value="{{ $solicitud->total_unidades }}" id="total-unidades-input">
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
                                <label class="form-label">Archivos Adjuntos</label>
                                <div class="file-upload-area" onclick="document.getElementById('archivos').click()">
                                    <input type="file" id="archivos" multiple class="file-input" 
                                           name="archivos[]" onchange="handleFileSelect(event)">
                                    <div class="file-upload-content">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                            <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/>
                                        </svg>
                                        <h4>Arrastre archivos o haga clic para seleccionar</h4>
                                        <p>Cotizaciones, imágenes, especificaciones - máximo 10MB por archivo</p>
                                    </div>
                                </div>
                                
                                <div id="file-list" class="file-list" style="display: none;">
                                    <div class="file-items" id="file-items">
                                        <!-- Los archivos seleccionados aparecerán aquí -->
                                    </div>
                                </div>

                                <!-- Mostrar archivos existentes -->
                                @if($solicitud->archivos && $solicitud->archivos->count() > 0)
                                <div class="file-list mt-4">
                                    <div class="file-items">
                                        <div class="file-section-label">Archivos existentes:</div>
                                        @foreach($solicitud->archivos as $archivo)
                                        <div class="file-item existing">
                                            <div class="file-info">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zm5.5 1.5v2a1 1 0 0 0 1 1h2l-3-3z"/>
                                                </svg>
                                                <span>{{ $archivo->nombre_archivo }}</span>
                                                <span class="file-size">({{ round($archivo->tamaño / 1024, 2) }} KB)</span>
                                            </div>
                                            <div class="file-actions">
                                                <a href="{{ asset('storage/' . $archivo->ruta_archivo) }}" target="_blank" class="file-view" title="Ver archivo">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function updateItemTotal(index) {
            const cantidad = parseFloat(document.querySelector(`input[name="items[${index}][cantidad]"]`).value) || 0;
            const precio = parseFloat(document.querySelector(`input[name="items[${index}][precio_unitario_estimado]"]`).value) || 0;
            const total = cantidad * precio;
            
            document.getElementById(`total-${index}`).textContent = '$' + total.toFixed(2);
            document.getElementById(`total-input-${index}`).value = total.toFixed(2);
            
            // Actualizar totales generales
            updateTotales();
        }

        function updateTotales() {
            let subtotal = 0;
            let totalUnidades = 0;
            
            // Calcular subtotal y total unidades
            document.querySelectorAll('input[name^="items["][name$="][cantidad]"]').forEach((input, index) => {
                const cantidad = parseFloat(input.value) || 0;
                const precio = parseFloat(document.querySelector(`input[name="items[${index}][precio_unitario_estimado]"]`).value) || 0;
                subtotal += cantidad * precio;
                totalUnidades += cantidad;
            });
            
            const iva = subtotal * 0.19;
            const total = subtotal + iva;
            
            // Actualizar display
            document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
            document.getElementById('iva').textContent = '$' + iva.toFixed(2);
            document.getElementById('total-general').textContent = '$' + total.toFixed(2);
            document.getElementById('total-unidades').textContent = totalUnidades;
            
            // Actualizar inputs hidden
            document.getElementById('subtotal-input').value = subtotal.toFixed(2);
            document.getElementById('iva-input').value = iva.toFixed(2);
            document.getElementById('total-general-input').value = total.toFixed(2);
            document.getElementById('total-unidades-input').value = totalUnidades;
        }

        function handleFileSelect(event) {
            const files = event.target.files;
            const fileList = document.getElementById('file-list');
            const fileItems = document.getElementById('file-items');
            
            fileItems.innerHTML = '';
            
            if (files.length > 0) {
                fileList.style.display = 'block';
                
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const fileItem = document.createElement('div');
                    fileItem.className = 'file-item';
                    fileItem.innerHTML = `
                        <div class="file-info">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zm5.5 1.5v2a1 1 0 0 0 1 1h2l-3-3z"/>
                            </svg>
                            <span>${file.name}</span>
                        </div>
                        <button type="button" class="file-remove" onclick="removeFile(this)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                            </svg>
                        </button>
                    `;
                    fileItems.appendChild(fileItem);
                }
            } else {
                fileList.style.display = 'none';
            }
        }

        function removeFile(button) {
            button.closest('.file-item').remove();
            const fileItems = document.getElementById('file-items');
            if (fileItems.children.length === 0) {
                document.getElementById('file-list').style.display = 'none';
            }
        }

        function copyCode() {
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
        }

        // Inicializar Alpine.js con funciones básicas
        function editPurchaseRequest() {
            return {
                submitForm() {
                    // Validación básica
                    const form = document.getElementById('purchaseRequestForm');
                    let isValid = true;
                    
                    // Validar campos requeridos
                    const requiredFields = form.querySelectorAll('[required]');
                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            isValid = false;
                            field.style.borderColor = '#ef4444';
                        } else {
                            field.style.borderColor = '';
                        }
                    });
                    
                    if (!isValid) {
                        alert('Por favor complete todos los campos obligatorios (*)');
                        return;
                    }
                    
                    // Validar items
                    const items = document.querySelectorAll('input[name^="items["][name$="][descripcion_producto]"]');
                    if (items.length === 0) {
                        alert('Debe haber al menos un artículo en la solicitud');
                        return;
                    }
                    
                    // Enviar formulario
                    form.submit();
                },
                
                resetForm() {
                    if (confirm('¿Está seguro de que desea restablecer todos los cambios?')) {
                        document.getElementById('purchaseRequestForm').reset();
                        // Recargar la página para volver a los valores originales
                        window.location.reload();
                    }
                }
            }
        }
    </script>

    <style>
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