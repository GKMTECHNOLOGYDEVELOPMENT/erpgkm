<x-layout.default>
    <link rel="stylesheet" href="{{ asset('assets/css/createsolicitudalmacen.css') }}">
    <div x-data="warehouseCreate()" x-init="init()" class="warehouse-create-container">
        <!-- Header -->
        <div class="wc-header">
            <div class="wc-header-content">
                <div class="wc-back-section">
                    <a href="{{ route('solicitudalmacen.index') }}" class="wc-btn-back">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                        </svg>
                        Volver a Solicitudes
                    </a>
                </div>
                <div class="wc-title-section">
                    <h1>Nueva Solicitud de Abastecimiento</h1>
                    <p>Complete los productos necesarios para el almacén</p>
                </div>
                <div class="wc-actions-section">
                    <button class="wc-btn wc-btn-secondary" @click="resetForm()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                            <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                        </svg>
                        Limpiar
                    </button>
                    <button class="wc-btn wc-btn-primary" @click="submitForm()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z"/>
                        </svg>
                        Crear Solicitud
                    </button>
                </div>
            </div>
        </div>

        <div class="wc-content">
            <!-- Form Section -->
            <div class="wc-form-section">
                <!-- Información General -->
                <div class="wc-card">
                    <div class="wc-card-header">
                        <div class="wc-header-with-code">
                            <div>
                                <h2>Información General</h2>
                                <p>Datos básicos de la solicitud de abastecimiento</p>
                            </div>
                            <div class="wc-request-code">
                                <span class="wc-code-label">Código:</span>
                                <span class="wc-code-value" x-text="requestCode"></span>
                                <button type="button" class="wc-btn-copy" @click="copyCode()" title="Copiar código">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                                        <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="wc-form-grid">
                        <div class="wc-form-group">
                            <label class="wc-form-label">Título de la Solicitud *</label>
                            <input 
                                type="text" 
                                class="wc-form-input" 
                                placeholder="Ej: Reabastecimiento Material de Oficina"
                                x-model="form.titulo"
                                required
                            >
                        </div>

                        <div class="wc-form-group">
                            <label class="wc-form-label">Tipo de Solicitud *</label>
                            <select class="wc-form-select" x-model="form.idTipoSolicitud" required>
                                <option value="">Seleccione tipo</option>
                                <template x-for="tipo in tiposSolicitud" :key="tipo.idTipoSolicitud">
                                    <option :value="tipo.idTipoSolicitud" x-text="tipo.nombre"></option>
                                </template>
                            </select>
                        </div>

                        <div class="wc-form-group">
                            <label class="wc-form-label">Área *</label>
                            <select class="wc-form-select" x-model="form.idTipoArea" required>
                                <option value="">Seleccione área</option>
                                <template x-for="area in areas" :key="area.idTipoArea">
                                    <option :value="area.idTipoArea" x-text="area.nombre"></option>
                                </template>
                            </select>
                        </div>

                        <!-- SOLICITANTE: Mostrar automáticamente el usuario autenticado -->
                        <div class="wc-form-group">
                            <label class="wc-form-label">Solicitante *</label>
                            <input 
                                type="text" 
                                class="wc-form-input" 
                                placeholder="Nombre del responsable"
                                x-model="form.solicitante"
                                required
                                readonly
                            >
                        </div>

                        <div class="wc-form-group">
                            <label class="wc-form-label">Prioridad *</label>
                            <select class="wc-form-select" x-model="form.idPrioridad" required>
                                <option value="">Seleccione prioridad</option>
                                <template x-for="prioridad in prioridades" :key="prioridad.idPrioridad">
                                    <option :value="prioridad.idPrioridad" x-text="prioridad.nombre"></option>
                                </template>
                            </select>
                        </div>

                        <div class="wc-form-group">
                            <label class="wc-form-label">Fecha Requerida *</label>
                            <input 
                                type="date" 
                                class="wc-form-input" 
                                x-model="form.fecha_requerida"
                                :min="new Date().toISOString().split('T')[0]"
                                required
                            >
                        </div>

                        <div class="wc-form-group">
                            <label class="wc-form-label">Centro de Costo</label>
                            <select class="wc-form-select" x-model="form.idCentroCosto">
                                <option value="">Seleccione centro de costo</option>
                                <template x-for="centro in centrosCosto" :key="centro.idCentroCosto">
                                    <option :value="centro.idCentroCosto" x-text="centro.nombre"></option>
                                </template>
                            </select>
                        </div>

                        <div class="wc-form-group wc-full-width">
                            <label class="wc-form-label">Descripción *</label>
                            <textarea 
                                class="wc-form-textarea" 
                                rows="3"
                                placeholder="Describa el propósito de esta solicitud de abastecimiento..."
                                x-model="form.descripcion"
                                required
                            ></textarea>
                        </div>

                        <div class="wc-form-group wc-full-width">
                            <label class="wc-form-label">Justificación *</label>
                            <textarea 
                                class="wc-form-textarea" 
                                rows="3"
                                placeholder="Explique por qué es necesario este abastecimiento..."
                                x-model="form.justificacion"
                                required
                            ></textarea>
                        </div>

                        <div class="wc-form-group wc-full-width">
                            <label class="wc-form-label">Observaciones</label>
                            <textarea 
                                class="wc-form-textarea" 
                                rows="2"
                                placeholder="Observaciones adicionales..."
                                x-model="form.observaciones"
                            ></textarea>
                        </div>
                    </div>
                </div>

                <!-- Productos Solicitados -->
                <div class="wc-card">
                    <div class="wc-card-header">
                        <div class="wc-header-with-action">
                            <div>
                                <h2>Productos Solicitados</h2>
                                <p>Agregue los productos que necesita el almacén</p>
                            </div>
                            <button type="button" class="wc-btn wc-btn-primary" @click="addProduct()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                </svg>
                                Agregar Producto
                            </button>
                        </div>
                    </div>

                    <!-- Lista de Productos -->
                    <div class="wc-products-container">
                        <template x-for="(product, index) in form.productos" :key="index">
                            <div class="wc-product-card">
                                <div class="wc-product-header">
                                    <div class="wc-product-info">
                                        <span class="wc-product-number" x-text="`Producto ${index + 1}`"></span>
                                        <span class="wc-product-code" x-text="product.codigo_barras || 'Sin código'"></span>
                                    </div>
                                    <button type="button" class="wc-btn-remove" @click="removeProduct(index)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                        </svg>
                                    </button>
                                </div>

                                <div class="wc-product-grid">
                                    <div class="wc-form-group wc-full-width">
                                        <label class="wc-form-label">Buscar Artículo por Código</label>
                                        <div class="wc-search-container">
                                            <input 
                                                type="text" 
                                                class="wc-form-input" 
                                                placeholder="Ingrese código de barras, SKU o código repuesto..."
                                                x-model="product.searchCode"
                                                @input.debounce.500="searchArticle(index)"
                                            >
                                            <button class="wc-search-btn" @click="searchArticle(index)">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="wc-form-group wc-full-width">
                                        <label class="wc-form-label">Descripción del Producto *</label>
                                        <input 
                                            type="text" 
                                            class="wc-form-input" 
                                            placeholder="Nombre del producto"
                                            x-model="product.descripcion"
                                            required
                                            readonly
                                        >
                                    </div>

                                    <div class="wc-form-group">
                                        <label class="wc-form-label">Categoría</label>
                                        <input 
                                            type="text" 
                                            class="wc-form-input" 
                                            placeholder="Categoría"
                                            x-model="product.categoria_nombre"
                                            readonly
                                        >
                                    </div>

                                    <div class="wc-form-group">
                                        <label class="wc-form-label">Cantidad *</label>
                                        <input 
                                            type="number" 
                                            class="wc-form-input" 
                                            min="1"
                                            placeholder="1"
                                            x-model="product.cantidad"
                                            required
                                        >
                                    </div>

                                    <div class="wc-form-group">
                                        <label class="wc-form-label">Unidad *</label>
                                        <input 
                                            type="text" 
                                            class="wc-form-input" 
                                            placeholder="Unidad de medida"
                                            x-model="product.unidad_nombre"
                                            readonly
                                        >
                                    </div>

                                    <div class="wc-form-group">
                                        <label class="wc-form-label">Código Barras</label>
                                        <input 
                                            type="text" 
                                            class="wc-form-input" 
                                            placeholder="Código de barras"
                                            x-model="product.codigo_barras"
                                            readonly
                                        >
                                    </div>

                                    <div class="wc-form-group">
                                        <label class="wc-form-label">Marca</label>
                                        <input 
                                            type="text" 
                                            class="wc-form-input" 
                                            placeholder="Marca del producto"
                                            x-model="product.marca_nombre"
                                            readonly
                                        >
                                    </div>

                                    <div class="wc-form-group">
                                        <label class="wc-form-label">Modelo</label>
                                        <input 
                                            type="text" 
                                            class="wc-form-input" 
                                            placeholder="Modelo del producto"
                                            x-model="product.modelo_nombre"
                                            readonly
                                        >
                                    </div>

                                    <div class="wc-form-group wc-full-width">
                                        <label class="wc-form-label">Especificaciones Técnicas</label>
                                        <textarea 
                                            class="wc-form-textarea" 
                                            rows="2"
                                            placeholder="Especificaciones, características..."
                                            x-model="product.especificaciones"
                                        ></textarea>
                                    </div>

                                    <div class="wc-form-group wc-full-width">
                                        <label class="wc-form-label">Justificación del Producto</label>
                                        <textarea 
                                            class="wc-form-textarea" 
                                            rows="2"
                                            placeholder="¿Por qué necesita este producto específico?"
                                            x-model="product.justificacion_producto"
                                        ></textarea>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div x-show="form.productos.length === 0" class="wc-empty-products">
                            <div class="wc-empty-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1H.5zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM9 5.5V7h1.5a.5.5 0 0 1 0 1H9v1.5a.5.5 0 0 1-1 0V8H6.5a.5.5 0 0 1 0-1H8V5.5a.5.5 0 0 1 1 0z"/>
                                </svg>
                            </div>
                            <h3>No hay productos agregados</h3>
                            <p>Comience agregando el primer producto a su solicitud</p>
                            <button class="wc-btn wc-btn-primary" @click="addProduct()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                </svg>
                                Agregar Primer Producto
                            </button>
                        </div>
                    </div>

                    <!-- Resumen de Productos (SIN PRECIOS) -->
                    <div class="wc-products-summary" x-show="form.productos.length > 0">
                        <div class="wc-summary-grid">
                            <div class="wc-summary-item">
                                <span class="wc-summary-label">Total de Productos:</span>
                                <span class="wc-summary-value" x-text="form.productos.length"></span>
                            </div>
                            <div class="wc-summary-item">
                                <span class="wc-summary-label">Total de Unidades:</span>
                                <span class="wc-summary-value" x-text="totalUnits"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="wc-card">
                    <div class="wc-card-header">
                        <h2>Información Adicional</h2>
                        <p>Detalles complementarios para el proceso</p>
                    </div>

                    <div class="wc-form-grid">
                        <div class="wc-form-group wc-full-width">
                            <label class="wc-form-label">Archivos Adjuntos</label>
                            <div class="wc-file-upload-area" @click="$refs.fileInput.click()">
                                <input type="file" x-ref="fileInput" multiple class="wc-file-input" @change="handleFileSelect">
                                <div class="wc-file-upload-content">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                        <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/>
                                    </svg>
                                    <h4>Arrastre archivos o haga clic para seleccionar</h4>
                                    <p>Cotizaciones, imágenes, especificaciones - máximo 10MB por archivo</p>
                                </div>
                            </div>
                            
                            <div x-show="form.files.length > 0" class="wc-file-list">
                                <div class="wc-file-items">
                                    <template x-for="(file, index) in form.files" :key="index">
                                        <div class="wc-file-item">
                                            <div class="wc-file-info">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zm5.5 1.5v2a1 1 0 0 0 1 1h2l-3-3z"/>
                                                </svg>
                                                <span x-text="file.name"></span>
                                            </div>
                                            <button type="button" class="wc-file-remove" @click="removeFile(index)">
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
            <div class="wc-preview-section">
                <div class="wc-preview-card">
                    <div class="wc-preview-header">
                        <div class="wc-header-with-code">
                            <div>
                                <h2>Resumen de la Solicitud</h2>
                                <p>Vista previa antes de enviar</p>
                            </div>
                            <div class="wc-preview-code">
                                <span class="wc-code-label">Código:</span>
                                <span class="wc-code-value" x-text="requestCode"></span>
                            </div>
                        </div>
                    </div>

                    <div class="wc-preview-content">
                        <div class="wc-preview-status">
                            <div class="wc-status-badge wc-preview">
                                Nueva Solicitud
                            </div>
                        </div>

                        <div class="wc-preview-section">
                            <h3>Información General</h3>
                            <div class="wc-preview-item">
                                <label>Código:</label>
                                <span class="wc-code-preview" x-text="requestCode"></span>
                            </div>
                            <div class="wc-preview-item">
                                <label>Título:</label>
                                <span x-text="form.titulo || 'Sin título'"></span>
                            </div>
                            <div class="wc-preview-item">
                                <label>Tipo:</label>
                                <span x-text="getTipoSolicitudText(form.idTipoSolicitud) || 'No especificado'"></span>
                            </div>
                            <div class="wc-preview-item" x-show="form.idTipoArea">
                                <label>Área:</label>
                                <span x-text="getAreaText(form.idTipoArea)"></span>
                            </div>
                            <div class="wc-preview-item">
                                <label>Solicitante:</label>
                                <span x-text="form.solicitante || 'No especificado'"></span>
                            </div>
                            <div class="wc-preview-item">
                                <label>Prioridad:</label>
                                <span class="wc-priority-badge" :class="'wc-priority-' + getPrioridadNivel(form.idPrioridad)" 
                                      x-text="getPrioridadText(form.idPrioridad) || 'No especificada'"></span>
                            </div>
                            <div class="wc-preview-item">
                                <label>Fecha Requerida:</label>
                                <span x-text="form.fecha_requerida ? formatPreviewDate(form.fecha_requerida) : 'No especificada'"></span>
                            </div>
                            <div class="wc-preview-item" x-show="form.idCentroCosto">
                                <label>Centro de Costo:</label>
                                <span x-text="getCentroCostoText(form.idCentroCosto)"></span>
                            </div>
                        </div>

                        <div class="wc-preview-section" x-show="form.productos.length > 0">
                            <h3>Productos Solicitados</h3>
                            <div class="wc-preview-products">
                                <template x-for="(product, index) in form.productos" :key="index">
                                    <div class="wc-preview-product-card">
                                        <div class="wc-preview-product-header">
                                            <div class="wc-product-title">
                                                <strong x-text="product.descripcion || 'Sin descripción'"></strong>
                                                <span class="wc-product-preview-code" x-text="product.codigo_barras || 'Sin código'"></span>
                                            </div>
                                            <span class="wc-product-quantity" x-text="product.cantidad + ' ' + (product.unidad_nombre || 'unidad')"></span>
                                        </div>
                                        <div class="wc-preview-product-details">
                                            <span x-show="product.categoria_nombre" x-text="product.categoria_nombre" class="wc-product-category"></span>
                                            <span x-show="product.marca_nombre" x-text="product.marca_nombre" class="wc-product-brand"></span>
                                            <span x-show="product.modelo_nombre" x-text="product.modelo_nombre" class="wc-product-model"></span>
                                        </div>
                                        <div class="wc-preview-product-specs" x-show="product.especificaciones" x-text="product.especificaciones"></div>
                                        <div class="wc-preview-product-justification" x-show="product.justificacion_producto">
                                            <strong>Justificación:</strong> <span x-text="product.justificacion_producto"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="wc-preview-section">
                            <h3>Justificación</h3>
                            <div class="wc-preview-justification" x-text="form.justificacion || 'Sin justificación'"></div>
                        </div>

                        <div class="wc-preview-section" x-show="form.observaciones">
                            <h3>Observaciones</h3>
                            <div class="wc-preview-observations" x-text="form.observaciones"></div>
                        </div>
                    </div>
                </div>

                <!-- Información de ayuda -->
                <div class="wc-help-card">
                    <h3>💡 Consejos para una buena solicitud</h3>
                    <ul class="wc-help-list">
                        <li>Describa claramente cada producto con sus especificaciones</li>
                        <li>Incluya cantidades realistas basadas en necesidades del almacén</li>
                        <li>Justifique la necesidad de cada producto</li>
                        <li>Verifique que los códigos de producto sean correctos</li>
                        <li>Incluya especificaciones técnicas cuando sea necesario</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
    function warehouseCreate() {
        return {
            // Datos cargados desde la base de datos
            tiposSolicitud: @json($tiposSolicitud),
            prioridades: @json($prioridades),
            centrosCosto: @json($centrosCosto),
            areas: @json($areas),

            // QUITAR: proveedores: [],

            form: {
                titulo: '',
                idTipoSolicitud: '',
                solicitante: '{{ $nombreSolicitante }}',
                idPrioridad: '',
                fecha_requerida: '',
                idCentroCosto: '',
                idTipoArea: '', // Añadir esta línea
                descripcion: '',
                justificacion: '',
                observaciones: '',
                productos: [],
                files: []
            },

            get requestCode() {
                const now = new Date();
                const year = now.getFullYear().toString().slice(-2);
                const month = (now.getMonth() + 1).toString().padStart(2, '0');
                const day = now.getDate().toString().padStart(2, '0');
                const random = Math.floor(Math.random() * 999).toString().padStart(3, '0');
                return `SA-${year}${month}${day}-${random}`;
            },

            // Añade esta función para obtener el texto del área
            getAreaText(idTipoArea) {
                const area = this.areas.find(a => a.idTipoArea == idTipoArea);
                return area ? area.nombre : '';
            },

            async init() {
                // Cargar datos de los selects desde la API (si es necesario)
                // await this.loadSelectData();
                
                // Set minimum date to today
                const today = new Date().toISOString().split('T')[0];
                this.form.fecha_requerida = today;
            },

            async loadSelectData() {
                try {
                    const response = await fetch('/solicitudalmacen/select-data');
                    const data = await response.json();
                    
                    this.tiposSolicitud = data.tiposSolicitud;
                    this.prioridades = data.prioridades;
                    this.centrosCosto = data.centrosCosto;
                    // QUITAR: this.proveedores = data.proveedores;
                } catch (error) {
                    console.error('Error cargando datos:', error);
                }
            },

            async searchArticle(index) {
                const product = this.form.productos[index];
                if (!product.searchCode) return;

                try {
                    const response = await fetch(`/solicitudalmacen/buscar-articulo/${product.searchCode}`);
                    const data = await response.json();

                    if (data.success && data.articulo) {
                        const articulo = data.articulo;
                        
                        // Llenar automáticamente los campos del producto
                        product.idArticulo = articulo.idArticulos;
                        product.descripcion = articulo.nombre;
                        product.codigo_barras = articulo.codigo_barras;
                        product.categoria_nombre = articulo.categoria_nombre || '';
                        product.unidad_nombre = articulo.unidad_nombre || '';
                        product.marca_nombre = articulo.marca_nombre || '';
                        product.modelo_nombre = articulo.modelo_nombre || '';
                        
                    } else {
                        alert('Artículo no encontrado');
                    }
                } catch (error) {
                    console.error('Error buscando artículo:', error);
                    alert('Error al buscar el artículo');
                }
            },

            get totalUnits() {
                return this.form.productos.reduce((sum, product) => {
                    return sum + (parseInt(product.cantidad) || 0);
                }, 0);
            },

            addProduct() {
                this.form.productos.push({
                    idArticulo: null,
                    searchCode: '',
                    descripcion: '',
                    cantidad: 1,
                    codigo_barras: '',
                    categoria_nombre: '',
                    unidad_nombre: '',
                    marca_nombre: '',
                    modelo_nombre: '',
                    especificaciones: '',
                    justificacion_producto: ''
                });
            },

            removeProduct(index) {
                this.form.productos.splice(index, 1);
            },

            getTipoSolicitudText(idTipoSolicitud) {
                const tipo = this.tiposSolicitud.find(t => t.idTipoSolicitud == idTipoSolicitud);
                return tipo ? tipo.nombre : '';
            },

            getPrioridadText(idPrioridad) {
                const prioridad = this.prioridades.find(p => p.idPrioridad == idPrioridad);
                return prioridad ? prioridad.nombre : '';
            },

            getPrioridadNivel(idPrioridad) {
                const prioridad = this.prioridades.find(p => p.idPrioridad == idPrioridad);
                if (!prioridad) return 'medium';
                
                const nivelMap = {
                    1: 'low',
                    2: 'medium', 
                    3: 'high',
                    4: 'urgent'
                };
                return nivelMap[prioridad.nivel] || 'medium';
            },

            getCentroCostoText(idCentroCosto) {
                const centro = this.centrosCosto.find(c => c.idCentroCosto == idCentroCosto);
                return centro ? centro.nombre : '';
            },

            formatPreviewDate(dateString) {
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                return new Date(dateString).toLocaleDateString('es-ES', options);
            },

            copyCode() {
                navigator.clipboard.writeText(this.requestCode).then(() => {
                    const btn = event.target.closest('.wc-btn-copy');
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
                        titulo: '',
                        idTipoSolicitud: '',
                        solicitante: '{{ $nombreSolicitante }}', // Mantener el solicitante
                        idPrioridad: '',
                        fecha_requerida: new Date().toISOString().split('T')[0],
                        idCentroCosto: '',
                        descripcion: '',
                        justificacion: '',
                        observaciones: '',
                        productos: [],
                        files: []
                    };
                }
            },

            async submitForm() {
                // Validación básica
                if (!this.form.titulo || !this.form.idTipoSolicitud || !this.form.solicitante || 
                        !this.form.idPrioridad || !this.form.fecha_requerida || !this.form.descripcion || 
                        !this.form.justificacion || !this.form.idTipoArea) { // Añadir esta validación
                        alert('Por favor complete todos los campos obligatorios (*)');
                        return;
                    }

                if (this.form.productos.length === 0) {
                    alert('Debe agregar al menos un producto a la solicitud');
                    return;
                }

                // Validar productos
                for (let i = 0; i < this.form.productos.length; i++) {
                    const product = this.form.productos[i];
                    if (!product.descripcion || !product.cantidad) {
                        alert(`Por favor complete todos los campos obligatorios del producto ${i + 1}`);
                        return;
                    }
                }

                try {
                    const formData = {
                        ...this.form,
                        total_unidades: this.totalUnits
                    };

                    const response = await fetch('/solicitudalmacen', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(formData)
                    });

                    const result = await response.json();

                    if (result.success) {
                        alert(`¡Solicitud ${result.codigo} creada exitosamente!`);
                        // Redirigir al listado
                        window.location.href = '/solicitudalmacen';
                    } else {
                        alert('Error al crear la solicitud: ' + result.message);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error al enviar la solicitud');
                }
            }
        }
    }
</script>
</x-layout.default>