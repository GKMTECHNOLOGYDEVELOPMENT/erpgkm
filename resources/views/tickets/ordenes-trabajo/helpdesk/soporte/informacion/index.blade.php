<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>
    /* ======== Modo Claro (Default) ======== */
    .select2-container--default .select2-selection--single {
        background-color: #fff;
        border-color: #e8e8e8 !important;
        height: 36px;
        line-height: 34px;
        font-size: 14px;
        padding: 0 10px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: var(--tw-text-opacity) !important;
        line-height: 34px;
        font-size: 14px;
        padding-left: 6px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 34px;
    }

    .select2-container--default .select2-results__option--selected {
        font-weight: 700;
    }

    .select2-container--default .select2-dropdown {
        border-radius: 5px;
        box-shadow: 0 0 0 1px #4444441c;
        font-size: 14px;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 1px solid #e8e8e8;
        font-size: 13px;
        padding: 6px;
    }

    /* ======== Modo Oscuro ======== */
    .dark .select2-container--default .select2-selection--single {
        background-color: #1b2e4b !important;
        border-color: #253b5c !important;
        color: #888ea8 !important;
        height: 36px;
        line-height: 34px;
        font-size: 14px;
        padding: 0 10px;
    }

    .dark .select2-container--default .select2-dropdown {
        background-color: #1b2e4b !important;
    }

    .dark .select2-container--default .select2-results__option--highlighted,
    .dark .select2-container--default .select2-results__option--selected {
        background-color: #132136 !important;
        border-color: #253b5c !important;
    }

    .dark .select2-container--default .select2-search--dropdown .select2-search__field {
        background-color: #132136 !important;
        border-color: #253b5c !important;
        color: #fff !important;
        font-size: 13px;
        padding: 6px;
    }

    .panel {
        overflow: visible !important;
    }

    /* Spinner para botones */
    .btn-spinner {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        vertical-align: middle;
        border: 2px solid currentColor;
        border-right-color: transparent;
        border-radius: 50%;
        animation: spinner-border .75s linear infinite;
        margin-right: 0.5rem;
    }

    @keyframes spinner-border {
        to { transform: rotate(360deg); }
    }

    /* Tooltips */
    [data-toggle="tooltip"] {
        position: relative;
        cursor: pointer;
    }
    
    [data-toggle="tooltip"]::before {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        padding: 0.5rem;
        background-color: #333;
        color: #fff;
        border-radius: 0.25rem;
        font-size: 0.875rem;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s;
        z-index: 10;
    }
    
    [data-toggle="tooltip"]:hover::before {
        opacity: 1;
        visibility: visible;
        bottom: calc(100% + 5px);
    }
</style>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
    <!-- Sección de Detalles de los Estados -->
    <div class="p-5 rounded-lg shadow-md">
        <span class="text-sm sm:text-lg font-semibold mb-2 sm:mb-4 badge" id="badgeEstado" style="background-color: {{ $colorEstado }};">Detalles de los Estados</span>

        <div class="grid grid-cols-1 gap-4 mt-4">
            <!-- Select de Estado con Nice Select -->
            <div>
                <label for="estado" class="block text-sm font-medium">Estado</label>
                <select id="estado" name="estado" style="display: none">
                    <option value="" disabled selected>Selecciona una opción</option>
                    @foreach ($estadosOTS as $index => $estado)
                        <option value="{{ $estado->idEstadoots }}" data-color="{{ $estado->color }}" {{ $index == 0 ? 'selected' : '' }}>
                            {{ $estado->descripcion }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Textarea de Justificación -->
            <div>
                <label for="justificacion" class="block text-sm font-medium">Justificación</label>
                <textarea id="justificacion" name="justificacion" rows="3" class="form-input w-full mt-2" 
                          data-toggle="tooltip" data-tooltip="Describe el motivo del cambio de estado"></textarea>
            </div>

            <!-- Botón Guardar -->
            <div class="flex justify-end">
                <button id="guardarEstado" class="btn btn-primary px-6 py-2">
                    <span class="btn-text">Guardar</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Sección de Herramientas -->
    <div class="p-5 rounded-lg shadow-md">
        <span class="text-sm sm:text-lg font-semibold mb-2 sm:mb-4 badge" style="background-color: {{ $colorEstado }};">Inventario</span>
        <div class="flex justify-end mt-2">
            <button type="button" id="addHerramienta" class="btn btn-primary" 
                    data-toggle="tooltip" data-tooltip="Agregar nueva herramienta">+</button>
        </div>
        
        <!-- Contenedor con altura definida y scroll -->
        <div id="herramientasContainer" class="h-40 border overflow-y-auto p-3 rounded-lg mt-2">
            <div class="flex items-center gap-2 mt-2 herramienta-row">
                <select class="form-input w-full herramienta-select">
                    <option value="DISCO DURO DE 2 TB">DISCO DURO DE 2 TB</option>
                    <option value="MEMORIA RAM 16GB">MEMORIA RAM 16GB</option>
                    <option value="PROCESADOR INTEL I7">PROCESADOR INTEL I7</option>
                </select>
                <input type="number" class="form-input w-20 cantidad-input" min="1" value="1">
                <button type="button" class="btn btn-danger removeHerramienta hidden">-</button>
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <button type="submit" id="guardarHerramientas" class="btn btn-primary px-6 py-2">
                <span class="btn-text">Guardar</span>
            </button>
        </div>
    </div>
</div>

<!-- Contenedor Principal -->
<div id="cardInstalarRetirar" class="mt-4">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sección Instalar -->
        <div class="lg:col-span-1 p-6 rounded-2xl shadow-md bg-white dark:bg-slate-800 space-y-6">
            <span class="text-sm sm:text-lg font-semibold mb-2 sm:mb-4 badge" style="background-color: {{ $colorEstado }};">Instalar</span>

            <form method="POST" id="formInstalar" class="space-y-4">
                @csrf
                <!-- Tipo Producto -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Tipo Producto</label>
                    <select id="tipoProductoInstalar" name="tipoProducto" class="form-select w-full" 
                            data-toggle="tooltip" data-tooltip="Seleccione el tipo de producto a instalar">
                        <option value="" disabled selected>Seleccionar Tipo de Producto</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->idCategoria }}">{{ $categoria->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Marca -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Marca</label>
                    <select id="marcaInstalar" name="marca" class="form-select w-full" 
                            data-toggle="tooltip" data-tooltip="Seleccione la marca del producto">
                        <option value="" disabled selected>Seleccionar Marca</option>
                        @foreach ($marcas as $marca)
                            <option value="{{ $marca->idMarca }}">{{ $marca->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Modelo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Modelo</label>
                    <select id="modeloInstalar" name="modelo" class="form-select w-full hidden" 
                            data-toggle="tooltip" data-tooltip="Seleccione el modelo específico">
                        <option value="" disabled selected>Seleccionar Modelo</option>
                    </select>
                </div>

                <!-- Número de Serie -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Nro. de Serie</label>
                    <input type="text" id="serieInstalar" class="form-input w-full" placeholder="Ingrese Nro. de Serie"
                           data-toggle="tooltip" data-tooltip="Ingrese el número de serie único del producto">
                </div>

                <!-- Observaciones -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Observaciones</label>
                    <textarea id="observacionesInstalar" name="observaciones" class="form-textarea w-full" rows="3"
                              placeholder="Ingrese las observaciones (opcional)"></textarea>
                </div>

                <!-- Botón Guardar -->
                <div class="flex justify-end">
                    <button id="guardarInstalar" class="btn btn-primary px-6 py-2">
                        <span class="btn-text">Guardar</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabla de Productos Instalados -->
        <div class="lg:col-span-2 p-6 rounded-2xl shadow-md bg-white dark:bg-slate-800">
            <span class="text-lg font-semibold text-gray-800 dark:text-white">Productos Instalados</span>
            <div class="overflow-x-auto mt-4">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white">
                            <th class="px-4 py-2 text-center">Producto</th>
                            <th class="px-4 py-2 text-center">Marca</th>
                            <th class="px-4 py-2 text-center">Modelo</th>
                            <th class="px-4 py-2 text-center">Nro. Serie</th>
                            <th class="px-4 py-2 text-center">Observaciones</th>
                            <th class="px-4 py-2 text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="tablaInstalar"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Sección Retirar -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <div class="lg:col-span-1 p-6 rounded-2xl shadow-md bg-white dark:bg-slate-800 space-y-6">
            <span class="text-sm sm:text-lg font-semibold mb-2 sm:mb-4 badge" style="background-color: {{ $colorEstado }};">Retirar</span>

            <form method="POST" id="formRetirar" class="space-y-4">
                @csrf
                <!-- Tipo Producto -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Tipo Producto</label>
                    <select id="tipoProductoRetirar" name="tipoProducto" class="form-select w-full h-10 px-3 py-2 rounded-md truncate">
                        <option value="" disabled selected>Seleccionar Tipo de Producto</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->idCategoria }}">{{ $categoria->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Marca -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Marca</label>
                    <select id="marcaRetirar" name="marca" class="form-select w-full h-10 px-3 py-2 rounded-md truncate">
                        <option value="" disabled selected>Seleccionar Marca</option>
                        @foreach ($marcas as $marca)
                            <option value="{{ $marca->idMarca }}">{{ $marca->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Modelo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Modelo</label>
                    <select id="modeloRetirar" name="modelo" class="form-select w-full h-10 px-3 py-2 rounded-md truncate hidden">
                        <option value="" disabled selected>Seleccionar Modelo</option>
                    </select>
                </div>

                <!-- Número de Serie -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Nro. de Serie</label>
                    <input type="text" id="serieRetirar" class="form-input w-full" placeholder="Ingrese Nro. de Serie">
                </div>

                <!-- Observaciones -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Observaciones</label>
                    <textarea id="observacionesRetirar" name="observaciones" class="form-textarea w-full" rows="3"
                              placeholder="Ingrese observaciones (opcional)"></textarea>
                </div>

                <!-- Botón Guardar -->
                <div class="flex justify-end">
                    <button id="guardarRetirar" class="btn btn-primary px-6 py-2">
                        <span class="btn-text">Guardar</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabla Productos Retirados -->
        <div class="lg:col-span-2 p-6 rounded-2xl shadow-md bg-white dark:bg-slate-800">
            <span class="text-lg font-semibold text-gray-800 dark:text-white">Productos Retirados</span>
            <div class="overflow-x-auto mt-4">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white">
                            <th class="px-4 py-2 text-center">Producto</th>
                            <th class="px-4 py-2 text-center">Marca</th>
                            <th class="px-4 py-2 text-center">Modelo</th>
                            <th class="px-4 py-2 text-center">Nro. Serie</th>
                            <th class="px-4 py-2 text-center">Observaciones</th>
                            <th class="px-4 py-2 text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="tablaRetirar"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Edición Compacto -->
<div id="modalEditarProducto" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50 px-4">
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-xl p-4 w-full max-w-sm">
        <div class="flex justify-between items-center mb-3">
            <h3 class="text-md font-semibold" id="modalTitulo">Editar Producto</h3>
            <button id="cerrarModal" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        
        <form id="formEditarProducto" class="space-y-3">
            @csrf
            <input type="hidden" id="editId" name="id">
            <input type="hidden" id="editTipo" name="tipo">
            
            <!-- Campos en formato más compacto -->
            <div class="grid grid-cols-1 gap-3">
                <!-- Tipo Producto -->
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Tipo Producto</label>
                    <select id="editTipoProducto" name="tipoProducto" class="form-select text-xs h-8 w-full">
                        <option value="" disabled selected>Seleccionar...</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->idCategoria }}">{{ $categoria->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Marca y Modelo en una fila -->
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Marca</label>
                        <select id="editMarca" name="marca" class="form-select text-xs h-8 w-full">
                            <option value="" disabled selected>Seleccionar...</option>
                            @foreach ($marcas as $marca)
                                <option value="{{ $marca->idMarca }}">{{ $marca->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Modelo</label>
                        <select id="editModelo" name="modelo" class="form-select text-xs h-8 w-full">
                            <option value="" disabled selected>Seleccionar...</option>
                        </select>
                    </div>
                </div>

                <!-- Número de Serie -->
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Nro. de Serie</label>
                    <input type="text" id="editSerie" name="numeroSerie" class="form-input text-xs h-8 w-full" placeholder="Nro. de Serie">
                </div>

                <!-- Observaciones -->
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Observaciones</label>
                    <textarea id="editObservaciones" name="observaciones" class="form-textarea text-xs w-full h-16"
                              placeholder="Observaciones (opcional)"></textarea>
                </div>
            </div>

            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" id="cancelarEdicion" class="btn btn-secondary text-xs px-3 py-1.5">Cancelar</button>
                <button type="submit" class="btn btn-primary text-xs px-3 py-1.5">
                    <span class="btn-text">Guardar</span>
                </button>
            </div>
        </form>
    </div>
</div>





<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
// Configuración inicial de Toastr
toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": true,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000"
};





// Función para mostrar loading en botones
function mostrarLoading(btn) {
    btn.prop('disabled', true);
    btn.find('.btn-text').html('<span class="btn-spinner"></span> Procesando...');
}

function ocultarLoading(btn, textoOriginal) {
    btn.prop('disabled', false);
    btn.find('.btn-text').text(textoOriginal);
}

// Función para sanitizar inputs
function sanitizarInput(input) {
    return input.replace(/</g, "&lt;").replace(/>/g, "&gt;");
}

// Función para validar si un número de serie ya existe
async function validarSerieUnico(serie, tipo) {
    return new Promise((resolve) => {
        const tabla = tipo === 'instalar' ? $('#tablaInstalar') : $('#tablaRetirar');
        let esUnico = true;
        
        tabla.find('tr[data-id]').each(function() {
            const serieExistente = $(this).find('td:eq(3)').text().trim();
            if (serieExistente === serie) {
                esUnico = false;
                return false;
            }
        });
        
        resolve(esUnico);
    });
}


function agregarFilaHerramienta(nombre, cantidad) {
    const nuevaFila = $(`
        <div class="flex items-center gap-2 mt-2 herramienta-row">
            <select class="form-input w-full herramienta-select">
                <option value="DISCO DURO DE 2 TB" ${nombre === 'DISCO DURO DE 2 TB' ? 'selected' : ''}>DISCO DURO DE 2 TB</option>
                <option value="MEMORIA RAM 16GB" ${nombre === 'MEMORIA RAM 16GB' ? 'selected' : ''}>MEMORIA RAM 16GB</option>
                <option value="PROCESADOR INTEL I7" ${nombre === 'PROCESADOR INTEL I7' ? 'selected' : ''}>PROCESADOR INTEL I7</option>
            </select>
            <input type="number" class="form-input w-20 cantidad-input" min="1" value="${cantidad || 1}">
            <button type="button" class="btn btn-danger removeHerramienta">-</button>
        </div>
    `);
    
    $('#herramientasContainer').append(nuevaFila);
    nuevaFila.find('.herramienta-select').select2();
    actualizarBotonesEliminar();
}

// Guardar herramientas
function guardarHerramientas() {
    const btn = $('#guardarHerramientas');
    const textoOriginal = btn.find('.btn-text').text();
    mostrarLoading(btn);
    
    const herramientas = [];
    $('.herramienta-row').each(function() {
        const herramienta = {
            nombre: $(this).find('.herramienta-select').val(),
            cantidad: $(this).find('.cantidad-input').val()
        };
        herramientas.push(herramienta);
    });

    $.ajax({
        url: '/guardar-herramientas',
        method: 'POST',
        data: {
            herramientas: herramientas,
            ticketId: {{ $ticket->idTickets }},
            visitaId: {{ $visitaId ?? 'null' }},
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            ocultarLoading(btn, textoOriginal);
            if (response.success) {
                toastr.success('Herramientas guardadas correctamente');
            } else {
                toastr.error(response.message || 'Error al guardar herramientas');
            }
        },
        error: function(xhr) {
            ocultarLoading(btn, textoOriginal);
            toastr.error('Error al procesar la solicitud');
        }
    });
}



// Función para abrir el modal de edición con manejo robusto de datos
function abrirModalEdicion(producto, tipo) {
    // 1. Validación y conversión de IDs
    producto.idCategoria = producto.idCategoria ? parseInt(producto.idCategoria) : null;
    producto.idMarca = producto.idMarca ? parseInt(producto.idMarca) : null;
    producto.idModelo = producto.idModelo ? parseInt(producto.idModelo) : null;
    
    console.log('Datos del producto para edición:', {
        ...producto,
        tipo: tipo
    });

    // 2. Llenar campos básicos del formulario
    $('#editId').val(producto.idEquipos);
    $('#editTipo').val(tipo);
    $('#editSerie').val(producto.nserie || '');
    $('#editObservaciones').val(producto.observaciones || '');

    // 3. Configurar selects con validación
    const $tipoProducto = $('#editTipoProducto');
    const $marca = $('#editMarca');
    const $modelo = $('#editModelo');

    // Resetear selects primero
    $tipoProducto.val(null).trigger('change');
    $marca.val(null).trigger('change');
    $modelo.empty().html('<option value="" disabled selected>Cargando...</option>');

    // 4. Configurar título del modal
    $('#modalTitulo').text(`Editar Producto ${tipo === 'instalar' ? 'Instalado' : 'Retirado'}`);

    // 5. Mostrar modal
    $('#modalEditarProducto').removeClass('hidden');

    // 6. Cargar datos en cascada con retardo para asegurar renderizado
    setTimeout(() => {
        if (producto.idCategoria) {
            $tipoProducto.val(producto.idCategoria).trigger('change');
            
            setTimeout(() => {
                if (producto.idMarca) {
                    $marca.val(producto.idMarca).trigger('change');
                    
                    setTimeout(() => {
                        if (producto.idCategoria && producto.idMarca) {
                            cargarModelosParaEdicion(
                                producto.idMarca, 
                                producto.idCategoria, 
                                producto.idModelo
                            );
                        } else {
                            $modelo.html('<option value="" disabled selected>Seleccione marca</option>');
                        }
                    }, 300);
                } else {
                    $modelo.html('<option value="" disabled selected>Seleccione marca</option>');
                }
            }, 300);
        } else {
            $modelo.html('<option value="" disabled selected>Seleccione categoría</option>');
        }
    }, 100);
}

// Función auxiliar mejorada para cargar modelos
function cargarModelosParaEdicion(idMarca, idCategoria, idModeloSeleccionado = null) {
    const $selectModelo = $('#editModelo');
    $selectModelo.prop('disabled', true).html('<option value="">Cargando modelos...</option>');

    // Validación extrema de parámetros
    if (!idMarca || !idCategoria || isNaN(idMarca) || isNaN(idCategoria)) {
        console.error('IDs inválidos:', {idMarca, idCategoria});
        $selectModelo.html('<option value="" disabled selected>Error en parámetros</option>');
        return;
    }

    $.ajax({
        url: `/modelos/marca/${idMarca}/categoria/${idCategoria}`,
        method: 'GET',
        success: function(response) {
            $selectModelo.empty().append('<option value="" disabled selected>Seleccionar Modelo</option>');
            
            if (response && response.length) {
                response.forEach(modelo => {
                    const selected = modelo.idModelo == idModeloSeleccionado ? 'selected' : '';
                    $selectModelo.append(`<option value="${modelo.idModelo}" ${selected}>${modelo.nombre}</option>`);
                });
            } else {
                $selectModelo.append('<option value="" disabled selected>No hay modelos disponibles</option>');
            }
        },
        error: function(xhr) {
            console.error('Error al cargar modelos:', xhr.responseText);
            $selectModelo.html('<option value="" disabled selected>Error al cargar</option>');
        },
        complete: function() {
            $selectModelo.prop('disabled', false);
        }
    });
}

// Evento para manejar la edición de productos
$(document).on('click', '.editarProducto', function() {
    const fila = $(this).closest('tr');
    const idEquipo = fila.data('id');
    const esInstalado = $(this).closest('#tablaInstalar').length > 0;
    const tipo = esInstalado ? 'instalar' : 'retirar';
    
    // Obtener los datos del producto de la primera celda que ahora contiene todos los data-attributes
    const celdaPrincipal = fila.find('td:first');
    
    const producto = {
        idEquipos: idEquipo,
        idCategoria: celdaPrincipal.data('id-categoria'),
        idMarca: celdaPrincipal.data('id-marca'),
        idModelo: celdaPrincipal.data('id-modelo'),
        nserie: fila.find('td:eq(3)').text().trim(),
        observaciones: fila.find('td:eq(4)').text().trim()
    };
    
    console.log('Datos extraídos para edición:', producto); // Para depuración
    
    abrirModalEdicion(producto, tipo);
});

// Evento para cancelar la edición
$('#cancelarEdicion').click(function() {
    $('#modalEditarProducto').addClass('hidden');
});

// Evento para guardar los cambios
$('#formEditarProducto').submit(function(e) {
    e.preventDefault();
    const btn = $(this).find('button[type="submit"]');
    const textoOriginal = btn.find('.btn-text').text();
    mostrarLoading(btn);
    
    const formData = new FormData(this);
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('idTicket', {{ $ticketId }});
    formData.append('idVisita', {{ $idVisitaSeleccionada ?? 'null' }});
    
    $.ajax({
        url: '/actualizar-equipo',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            ocultarLoading(btn, textoOriginal);
            if (response.success) {
                toastr.success("Producto actualizado correctamente.");
                $('#modalEditarProducto').addClass('hidden');
                
                // Recargar la tabla correspondiente
                if ($('#editTipo').val() === 'instalar') {
                    cargarProductosInstalados();
                } else {
                    cargarProductosRetirados();
                }
            } else {
                toastr.error(response.message || "Hubo un error al actualizar el producto.");
            }
        },
        error: function() {
            ocultarLoading(btn, textoOriginal);
            toastr.error("Hubo un error al procesar la solicitud.");
        }
    });
});




function cargarProductosInstalados() {
    const ticketId = {{ $ticketId }};
    const idVisitaSeleccionada = {{ $idVisitaSeleccionada ?? 'null' }};

    $.ajax({
        url: '/obtener-productos-instalados',
        method: 'GET',
        data: {
            idTicket: ticketId,
            idVisita: idVisitaSeleccionada,
        },
        success: function(response) {
            console.log('Respuesta del servidor:', response); // DEBUG
            $('#tablaInstalar').empty();

            if (response.length > 0) {
                response.forEach(function(producto) {
                    const nuevoProducto = `
                    <tr data-id="${producto.idEquipos}">
                        <td class="text-center" 
                            data-categoria="${producto.idCategoria}">
                            ${producto.categoria_nombre}
                        </td>
                        <td class="text-center" 
                            data-marca="${producto.idMarca}">
                            ${producto.marca_nombre}
                        </td>
                        <td class="text-center" 
                            data-modelo="${producto.idModelo}">
                            ${producto.modelo_nombre}
                        </td>
                        <td class="text-center">${producto.nserie}</td>
                        <td class="text-center">${producto.observaciones}</td>
                        <td class="text-center space-x-2">
                            <button class="btn btn-primary btn-sm editarProducto">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm eliminarProducto">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    `;
                    $('#tablaInstalar').append(nuevoProducto);
                });
            } else {
                $('#tablaInstalar').append(`
                    <tr>
                        <td colspan="6" class="text-center">No hay productos instalados</td>
                    </tr>
                `);
            }
        },
        error: function(xhr) {
            console.error('Error al obtener productos:', xhr.responseText);
            toastr.error("Error al cargar productos instalados");
        }
    });
}

function cargarProductosRetirados() {
    const ticketId = {{ $ticketId }};
    const idVisitaSeleccionada = {{ $idVisitaSeleccionada ?? 'null' }};

    $.ajax({
        url: '/obtener-productos-retirados',
        method: 'GET',
        data: {
            idTicket: ticketId,
            idVisita: idVisitaSeleccionada,
        },
        success: function(response) {
            $('#tablaRetirar').empty();

            if (response.length > 0) {
                response.forEach(function(producto) {
                    const nuevoProducto = `
                    <tr data-id="${producto.idEquipos}">
                        <td class="text-center" data-id="${producto.idCategoria}">${producto.categoria_nombre}</td>
                        <td class="text-center" data-id="${producto.idMarca}">${producto.marca_nombre}</td>
                        <td class="text-center" data-id="${producto.idModelo}">${producto.modelo_nombre}</td>
                        <td class="text-center">${producto.nserie}</td>
                        <td class="text-center">${producto.observaciones}</td>
                        <td class="text-center space-x-2">
                            <button class="btn btn-primary btn-sm editarProducto" 
                                    data-toggle="tooltip" data-tooltip="Editar producto">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm eliminarProducto" 
                                    data-toggle="tooltip" data-tooltip="Eliminar producto">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    `;
                    $('#tablaRetirar').append(nuevoProducto);
                });
            } else {
                $('#tablaRetirar').append(`
                    <tr>
                        <td colspan="6" class="text-center">No hay productos retirados para este ticket y visita.</td>
                    </tr>
                `);
            }
        },
        error: function(xhr) {
            toastr.error("Hubo un error al obtener los productos retirados.");
        }
    });
}



// Inicializar Select2 para modelos
function inicializarSelects() {
    $('#tipoProductoInstalar, #tipoProductoRetirar').select2({
        placeholder: 'Seleccionar Tipo de Producto',
        allowClear: true
    });

    $('#marcaInstalar, #marcaRetirar').select2({
        placeholder: 'Seleccionar Marca',
        allowClear: true
    });

    $('#modeloInstalar, #modeloRetirar').select2({
        placeholder: 'Seleccionar Modelo',
        allowClear: true
    });

    $(".herramienta-select").select2({
        width: "100%",
        placeholder: "Seleccione una herramienta",
        allowClear: true
    });
}

// Actualizar visibilidad de botones eliminar en herramientas
function actualizarBotonesEliminar() {
    const filas = $(".herramienta-row");
    const botonesEliminar = $(".removeHerramienta");

    botonesEliminar.each(function(index) {
        $(this).toggleClass("hidden", filas.length === 1);
    });
}

// Document ready
$(document).ready(function() {
    // Inicializar selects
    inicializarSelects();
    NiceSelect.bind(document.getElementById("estado"));
    
 
    cargarProductosInstalados();
    cargarProductosRetirados();
    
    // Cambio de estado
    $("#estado").change(function() {
        const estadoId = this.value;
        const ticketId = {{ $ticket->idTickets }};
        const visitaId = {{ $visitaId ?? 'null' }};
        const color = $(`#estado option[value="${estadoId}"]`).data('color');
        
        // Actualizar color del badge
        $('#badgeEstado').css('background-color', color);
        
        // Obtener justificación si existe
        $.ajax({
            url: `/api/obtenerJustificacionSoporte?ticketId=${ticketId}&visitaId=${visitaId}&estadoId=${estadoId}`,
            method: 'GET',
            success: function(data) {
                if (data.success) {
                    $("#justificacion").val(data.justificacion || "");
                }
            }
        });
    });

    // Guardar estado
    $("#guardarEstado").click(function() {
        const btn = $(this);
        const textoOriginal = btn.find('.btn-text').text();
        mostrarLoading(btn);
        
        const estadoId = $("#estado").val();
        const justificacion = sanitizarInput($("#justificacion").val());

        if (!estadoId || !justificacion.trim()) {
            toastr.error("Debe seleccionar un estado y escribir una justificación.");
            ocultarLoading(btn, textoOriginal);
            return;
        }

        $.ajax({
            url: '/api/guardarEstadoSoporte',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: JSON.stringify({
                idEstadoots: estadoId,
                justificacion: justificacion.trim(),
                idTickets: {{ $ticket->idTickets }}
            }),
            success: function(data) {
                ocultarLoading(btn, textoOriginal);
                if (data.success) {
                    toastr.success("Estado guardado correctamente.");
                } else {
                    toastr.error(data.message || "Error al guardar el estado.");
                }
            },
            error: function(error) {
                ocultarLoading(btn, textoOriginal);
                toastr.error("Hubo un error al guardar el estado.");
            }
        });
    });

    // Manejo de herramientas
    $("#addHerramienta").click(function() {
        const nuevaFila = $(`
            <div class="flex items-center gap-2 mt-2 herramienta-row">
                <select class="form-input w-full herramienta-select">
                    <option value="DISCO DURO DE 2 TB">DISCO DURO DE 2 TB</option>
                    <option value="MEMORIA RAM 16GB">MEMORIA RAM 16GB</option>
                    <option value="PROCESADOR INTEL I7">PROCESADOR INTEL I7</option>
                </select>
                <input type="number" class="form-input w-20 cantidad-input" min="1" value="1">
                <button type="button" class="btn btn-danger removeHerramienta">-</button>
            </div>
        `);

        $("#herramientasContainer").append(nuevaFila);
        nuevaFila.find(".herramienta-select").select2();
        actualizarBotonesEliminar();
        $("#herramientasContainer").scrollTop($("#herramientasContainer")[0].scrollHeight);
    });

    $("#herramientasContainer").on("click", ".removeHerramienta", function() {
        $(this).closest(".herramienta-row").remove();
        actualizarBotonesEliminar();
    });

    $("#guardarHerramientas").click(guardarHerramientas);

    // Manejo de productos a instalar
    $("#tipoProductoInstalar").change(function() {
        const idCategoria = $(this).val();
        const $marcaInstalar = $("#marcaInstalar");
        const $modeloInstalar = $("#modeloInstalar");

        if (idCategoria) {
            $.ajax({
                url: '/marcas/categoria/' + idCategoria,
                method: 'GET',
                success: function(response) {
                    $marcaInstalar.empty().append('<option value="" disabled selected>Seleccionar Marca</option>');
                    response.forEach(function(marca) {
                        $marcaInstalar.append(`<option value="${marca.idMarca}">${marca.nombre}</option>`);
                    });
                    $marcaInstalar.show().trigger('change');
                    $modeloInstalar.hide();
                },
                error: function() {
                    toastr.error('Error al cargar las marcas');
                }
            });
        } else {
            $marcaInstalar.hide();
            $modeloInstalar.hide();
        }
    });

    $("#marcaInstalar").change(function() {
        const idMarca = $(this).val();
        const idCategoria = $("#tipoProductoInstalar").val();
        const $modeloInstalar = $("#modeloInstalar");

        if (idMarca && idCategoria) {
            $.ajax({
                url: `/modelos/marca/${idMarca}/categoria/${idCategoria}`,
                method: 'GET',
                success: function(response) {
                    $modeloInstalar.empty().append('<option value="" disabled selected>Seleccionar Modelo</option>');
                    response.forEach(function(modelo) {
                        $modeloInstalar.append(`<option value="${modelo.idModelo}">${modelo.nombre}</option>`);
                    });
                    $modeloInstalar.show().trigger('change');
                },
                error: function() {
                    toastr.error('Error al cargar los modelos');
                }
            });
        } else {
            $modeloInstalar.hide();
        }
    });

    $("#guardarInstalar").click(async function(e) {
        e.preventDefault();
        const btn = $(this);
        const textoOriginal = btn.find('.btn-text').text();
        mostrarLoading(btn);
        
        const tipoProducto = $("#tipoProductoInstalar").val();
        const marca = $("#marcaInstalar").val();
        const modelo = $("#modeloInstalar").val();
        const serie = sanitizarInput($("#serieInstalar").val());
        const observaciones = sanitizarInput($("#observacionesInstalar").val());
        const ticketId = {{ $ticketId }};
        const idVisitaSeleccionada = {{ $idVisitaSeleccionada ?? 'null' }};

        if (!tipoProducto || !marca || !modelo || !serie) {
            toastr.error("Por favor, complete todos los campos.");
            ocultarLoading(btn, textoOriginal);
            return;
        }

        const esUnico = await validarSerieUnico(serie, 'instalar');
        if (!esUnico) {
            toastr.error('El número de serie ya existe en los productos instalados');
            ocultarLoading(btn, textoOriginal);
            return;
        }

        const formData = new FormData();
        formData.append('tipoProducto', tipoProducto);
        formData.append('marca', marca);
        formData.append('modelo', modelo);
        formData.append('numeroSerie', serie);
        formData.append('observaciones', observaciones);
        formData.append('idTicket', ticketId);
        formData.append('idVisita', idVisitaSeleccionada);
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: '/guardar-equipo',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                ocultarLoading(btn, textoOriginal);
                if (response.success) {
                    toastr.success("Producto instalado correctamente.");
                    cargarProductosInstalados();
                    $("#formInstalar")[0].reset();
                    $("#modeloInstalar").hide();
                } else {
                    toastr.error(response.message || "Hubo un error al guardar el equipo.");
                }
            },
            error: function() {
                ocultarLoading(btn, textoOriginal);
                toastr.error("Hubo un error al procesar la solicitud.");
            }
        });
    });

    // Manejo de productos a retirar (similar a instalar)
    $("#tipoProductoRetirar").change(function() {
        const idCategoria = $(this).val();
        const $marcaRetirar = $("#marcaRetirar");
        const $modeloRetirar = $("#modeloRetirar");

        if (idCategoria) {
            $.ajax({
                url: '/marcas/categoria/' + idCategoria,
                method: 'GET',
                success: function(response) {
                    $marcaRetirar.empty().append('<option value="" disabled selected>Seleccionar Marca</option>');
                    response.forEach(function(marca) {
                        $marcaRetirar.append(`<option value="${marca.idMarca}">${marca.nombre}</option>`);
                    });
                    $marcaRetirar.show().trigger('change');
                    $modeloRetirar.hide();
                },
                error: function() {
                    toastr.error('Error al cargar las marcas');
                }
            });
        } else {
            $marcaRetirar.hide();
            $modeloRetirar.hide();
        }
    });

    $("#marcaRetirar").change(function() {
        const idMarca = $(this).val();
        const idCategoria = $("#tipoProductoRetirar").val();
        const $modeloRetirar = $("#modeloRetirar");

        if (idMarca && idCategoria) {
            $.ajax({
                url: `/modelos/marca/${idMarca}/categoria/${idCategoria}`,
                method: 'GET',
                success: function(response) {
                    $modeloRetirar.empty().append('<option value="" disabled selected>Seleccionar Modelo</option>');
                    response.forEach(function(modelo) {
                        $modeloRetirar.append(`<option value="${modelo.idModelo}">${modelo.nombre}</option>`);
                    });
                    $modeloRetirar.show().trigger('change');
                },
                error: function() {
                    toastr.error('Error al cargar los modelos');
                }
            });
        } else {
            $modeloRetirar.hide();
        }
    });

    $("#guardarRetirar").click(async function(e) {
        e.preventDefault();
        const btn = $(this);
        const textoOriginal = btn.find('.btn-text').text();
        mostrarLoading(btn);
        
        const tipoProducto = $("#tipoProductoRetirar").val();
        const marca = $("#marcaRetirar").val();
        const modelo = $("#modeloRetirar").val();
        const serie = sanitizarInput($("#serieRetirar").val());
        const observaciones = sanitizarInput($("#observacionesRetirar").val());
        const ticketId = {{ $ticketId }};
        const idVisitaSeleccionada = {{ $idVisitaSeleccionada ?? 'null' }};

        if (!tipoProducto || !marca || !modelo || !serie) {
            toastr.error("Por favor, complete todos los campos.");
            ocultarLoading(btn, textoOriginal);
            return;
        }

        const esUnico = await validarSerieUnico(serie, 'retirar');
        if (!esUnico) {
            toastr.error('El número de serie ya existe en los productos retirados');
            ocultarLoading(btn, textoOriginal);
            return;
        }

        const formData = new FormData();
        formData.append('tipoProducto', tipoProducto);
        formData.append('marca', marca);
        formData.append('modelo', modelo);
        formData.append('numeroSerie', serie);
        formData.append('observaciones', observaciones);
        formData.append('idTicket', ticketId);
        formData.append('idVisita', idVisitaSeleccionada);
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: '/guardar-equipo-retirar',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                ocultarLoading(btn, textoOriginal);
                if (response.success) {
                    toastr.success("Producto retirado correctamente.");
                    cargarProductosRetirados();
                    $("#formRetirar")[0].reset();
                    $("#modeloRetirar").hide();
                } else {
                    toastr.error(response.message || "Hubo un error al guardar el equipo.");
                }
            },
            error: function() {
                ocultarLoading(btn, textoOriginal);
                toastr.error("Hubo un error al procesar la solicitud.");
            }
        });
    });

    // Eliminar productos (instalados o retirados)
    $("#tablaInstalar, #tablaRetirar").on("click", ".eliminarProducto", function() {
        const fila = $(this).closest("tr");
        const idEquipo = fila.data("id");
        const esInstalado = $(this).closest("#tablaInstalar").length > 0;
        const tipo = esInstalado ? "instalado" : "retirado";

        if (confirm(`¿Estás seguro de que deseas eliminar este producto ${tipo}?`)) {
            $.ajax({
                url: '/eliminar-producto/' + idEquipo,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(`Producto ${tipo} eliminado correctamente.`);
                        if (esInstalado) {
                            cargarProductosInstalados();
                        } else {
                            cargarProductosRetirados();
                        }
                    } else {
                        toastr.error(response.message || `Hubo un error al eliminar el producto ${tipo}.`);
                    }
                },
                error: function() {
                    toastr.error(`Hubo un error al procesar la solicitud de eliminación.`);
                }
            });
        }
    });


// Inicializar selects del modal
$('#editTipoProducto, #editMarca, #editModelo').select2({
        placeholder: 'Seleccionar',
        allowClear: true
    });
    
    // Manejar cambio de tipo de producto en el modal
    $('#editTipoProducto').change(function() {
        const idCategoria = $(this).val();
        const $editMarca = $('#editMarca');
        
        if (idCategoria) {
            $.ajax({
                url: '/marcas/categoria/' + idCategoria,
                method: 'GET',
                success: function(response) {
                    $editMarca.empty().append('<option value="" disabled selected>Seleccionar Marca</option>');
                    response.forEach(function(marca) {
                        $editMarca.append(`<option value="${marca.idMarca}">${marca.nombre}</option>`);
                    });
                    $editMarca.trigger('change');
                },
                error: function() {
                    toastr.error('Error al cargar las marcas');
                }
            });
        }
    });
    
    // Manejar cambio de marca en el modal
    $('#editMarca').change(function() {
        const idMarca = $(this).val();
        const idCategoria = $('#editTipoProducto').val();
        
        if (idMarca && idCategoria) {
            $.ajax({
                url: `/modelos/marca/${idMarca}/categoria/${idCategoria}`,
                method: 'GET',
                success: function(response) {
                    const $editModelo = $('#editModelo');
                    $editModelo.empty().append('<option value="" disabled selected>Seleccionar Modelo</option>');
                    response.forEach(function(modelo) {
                        $editModelo.append(`<option value="${modelo.idModelo}">${modelo.nombre}</option>`);
                    });
                    $editModelo.trigger('change');
                },
                error: function() {
                    toastr.error('Error al cargar los modelos');
                }
            });
        }
    });


});
</script>