<x-layout.default>
    <!-- Incluir Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
   <style>
        .select2-container--default .select2-selection--single {
            height: 42px;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px;
            padding-left: 16px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #4f46e5;
        }
        .select2-results__option {
            padding: 8px 12px;
        }
        .cliente-option {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }
        .cliente-nombre {
            flex: 1;
        }
        .cliente-documento {
            background: #f3f4f6;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            color: #6b7280;
            margin-left: 8px;
        }
    </style>

    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-indigo-700">Nueva Custodia</h1>
                <p class="text-gray-600 mt-2">Registrar nuevo equipo en custodia</p>
            </div>
            <a href="{{ route('solicitudcustodia.index') }}" 
               class="btn btn-outline-primary mt-4 md:mt-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver al Listado
            </a>
        </div>

        <!-- Formulario -->
        <div class="w-full mx-auto">
            <div class="panel rounded-xl shadow-sm p-6">
                <form id="custodiaForm" method="POST">
                    @csrf

                    <!-- Información del Cliente -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Información del Cliente
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Cliente con Select2 -->
                            <div>
                                <label for="idcliente" class="block text-sm font-medium text-gray-700 mb-2">Cliente *</label>
                                <select name="idcliente" id="idcliente" required
                                    class="w-full select2-clientes">
                                    <option value="">Seleccionar Cliente</option>
                                </select>
                                <div id="loadingClientes" class="hidden text-sm text-gray-500 mt-1">
                                    Cargando clientes...
                                </div>
                                @error('idcliente')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Número de Ticket -->
                            <div>
                                <label for="numero_ticket" class="block text-sm font-medium text-gray-700 mb-2">Número de Ticket</label>
                                <input type="text" name="numero_ticket" id="numero_ticket" 
                                    value="{{ old('numero_ticket') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 outline-none transition-colors duration-300"
                                    placeholder="Ej: TKT-001">
                                @error('numero_ticket')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Información del Equipo -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                            </svg>
                            Información del Equipo
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Marca con Select2 -->
                            <div>
                                <label for="idMarca" class="block text-sm font-medium text-gray-700 mb-2">Marca</label>
                                <select name="idMarca" id="idMarca" class="w-full select2-marcas">
                                    <option value="">Seleccionar Marca</option>
                                </select>
                                <div id="loadingMarcas" class="hidden text-sm text-gray-500 mt-1">
                                    Cargando marcas...
                                </div>
                                @error('idMarca')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Modelo con Select2 -->
                            <div>
                                <label for="idModelo" class="block text-sm font-medium text-gray-700 mb-2">Modelo</label>
                                <select name="idModelo" id="idModelo" class="w-full select2-modelos">
                                    <option value="">Seleccionar Modelo</option>
                                </select>
                                <div id="loadingModelos" class="hidden text-sm text-gray-500 mt-1">
                                    Cargando modelos...
                                </div>
                                @error('idModelo')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Serie -->
                            <div>
                                <label for="serie" class="block text-sm font-medium text-gray-700 mb-2">Número de Serie</label>
                                <input type="text" name="serie" id="serie" 
                                    value="{{ old('serie') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 outline-none transition-colors duration-300"
                                    placeholder="Ej: SN123456789">
                                @error('serie')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Información de la Custodia -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Información de la Custodia
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Fecha de Ingreso -->
                            <div>
                                <label for="fecha_ingreso_custodia" class="block text-sm font-medium text-gray-700 mb-2">Fecha de Ingreso *</label>
                                <input type="date" name="fecha_ingreso_custodia" id="fecha_ingreso_custodia" required
                                    value="{{ old('fecha_ingreso_custodia', date('Y-m-d')) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 outline-none transition-colors duration-300">
                                @error('fecha_ingreso_custodia')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Ubicación Actual -->
                            <div>
                                <label for="ubicacion_actual" class="block text-sm font-medium text-gray-700 mb-2">Ubicación de Recepción *</label>
                                <input type="text" name="ubicacion_actual" id="ubicacion_actual" required
                                    value="{{ old('ubicacion_actual') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 outline-none transition-colors duration-300"
                                    placeholder="Ej: Recepción Principal">
                                @error('ubicacion_actual')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Estado -->
                            <div>
                                <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">Estado *</label>
                                <select name="estado" id="estado" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 outline-none transition-colors duration-300">
                                    <option value="Pendiente" {{ old('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="En revisión" {{ old('estado') == 'En revisión' ? 'selected' : '' }}>En revisión</option>
                                    <option value="Aprobado" {{ old('estado') == 'Aprobado' ? 'selected' : '' }}>Aprobado</option>
                                </select>
                                @error('estado')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    <div class="mb-6">
                        <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-2">Observaciones</label>
                        <textarea name="observaciones" id="observaciones" rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 outline-none transition-colors duration-300"
                            placeholder="Observaciones adicionales...">{{ old('observaciones') }}</textarea>
                        @error('observaciones')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('solicitudcustodia.index') }}" 
                           class="btn btn-outline-primary px-6 py-2">
                            Cancelar
                        </a>
                        <button type="submit" id="submitBtn"
                                class="btn btn-primary px-6 py-2 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span id="submitText">Guardar Custodia</span>
                            <div id="submitSpinner" class="hidden animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Alertas -->
    <div id="alertContainer" class="fixed top-4 right-4 z-50 max-w-sm w-full"></div>

    <!-- Incluir Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/es.js"></script>

   <script>
        $(document).ready(function() {
            // URLs para las peticiones AJAX
            const urls = {
                clientes: '{{ route("api.clientescustodia") }}',
                marcas: '{{ route("api.marcascustodia") }}',
                modelos: '{{ route("api.modeloscustodia") }}',
                store: '{{ route("solicitudcustodia.store") }}'
            };

            // Inicializar Select2 para clientes - CONFIGURACIÓN CORREGIDA
            $('#idcliente').select2({
                placeholder: 'Buscar cliente...',
                language: 'es',
                width: '100%',
                allowClear: true,
                ajax: {
                    url: urls.clientes,
                    dataType: 'json',
                    delay: 300,
                    data: function (params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function (data, params) {
                        console.log('Datos recibidos:', data); // Para debug
                        
                        // Asegurar que estamos usando la estructura correcta
                        let results = data.results || data.data || data;
                        
                        return {
                            results: results,
                            pagination: {
                                more: (data.pagination && data.pagination.more) || 
                                      (params.page * 30) < 1000
                            }
                        };
                    },
                    cache: true
                },
                minimumInputLength: 1,
                templateResult: formatCliente,
                templateSelection: formatClienteSelection
            });

            // Inicializar Select2 para marcas
            $('#idMarca').select2({
                placeholder: 'Buscar marca...',
                language: 'es',
                width: '100%',
                allowClear: true,
                ajax: {
                    url: urls.marcas,
                    dataType: 'json',
                    delay: 300,
                    data: function (params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function (data, params) {
                        let results = data.results || data.data || data;
                        
                        return {
                            results: results,
                            pagination: {
                                more: (data.pagination && data.pagination.more) || 
                                      (params.page * 30) < 1000
                            }
                        };
                    },
                    cache: true
                },
                minimumInputLength: 1
            });

            // Inicializar Select2 para modelos
            $('#idModelo').select2({
                placeholder: 'Buscar modelo...',
                language: 'es',
                width: '100%',
                allowClear: true,
                ajax: {
                    url: urls.modelos,
                    dataType: 'json',
                    delay: 300,
                    data: function (params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function (data, params) {
                        let results = data.results || data.data || data;
                        
                        return {
                            results: results,
                            pagination: {
                                more: (data.pagination && data.pagination.more) || 
                                      (params.page * 30) < 1000
                            }
                        };
                    },
                    cache: true
                },
                minimumInputLength: 1
            });

            // Formatear la visualización de clientes - CORREGIDO
            function formatCliente(cliente) {
                if (cliente.loading) {
                    return 'Buscando...';
                }
                
                if (!cliente.id) {
                    return cliente.text;
                }
                
                const nombre = cliente.nombre || cliente.text || '';
                const documento = cliente.documento || '';
                
                return $(
                    '<div class="cliente-option">' +
                        '<span class="cliente-nombre">' + nombre.trim() + '</span>' +
                        (documento ? '<span class="cliente-documento">' + documento + '</span>' : '') +
                    '</div>'
                );
            }

            function formatClienteSelection(cliente) {
                if (!cliente.id) {
                    return cliente.text;
                }
                
                const nombre = cliente.nombre || cliente.text || '';
                return nombre.trim();
            }

            // Función para mostrar alertas
            function showAlert(message, type = 'success') {
                const alert = $(
                    '<div class="p-4 mb-4 rounded-lg shadow-lg transform transition-all duration-300 ' +
                    (type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white') + '">' +
                        '<div class="flex items-center justify-between">' +
                            '<span>' + message + '</span>' +
                            '<button class="ml-4 text-white hover:text-gray-200 close-alert">' +
                                '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>' +
                                '</svg>' +
                            '</button>' +
                        '</div>' +
                    '</div>'
                );
                
                $('#alertContainer').append(alert);
                
                // Cerrar alerta al hacer click
                alert.find('.close-alert').on('click', function() {
                    alert.remove();
                });
                
                // Auto-remove después de 5 segundos
                setTimeout(() => {
                    alert.remove();
                }, 5000);
            }

            // Manejar el envío del formulario
            $('#custodiaForm').on('submit', async function(e) {
                e.preventDefault();

                // Validar que se haya seleccionado un cliente
                const clienteId = $('#idcliente').val();
                if (!clienteId) {
                    showAlert('Por favor seleccione un cliente', 'error');
                    return;
                }

                // Mostrar loading
                $('#submitBtn').prop('disabled', true);
                $('#submitText').text('Guardando...');
                $('#submitSpinner').removeClass('hidden');

                try {
                    const formData = new FormData(this);
                    
                    const response = await fetch(urls.store, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (response.ok) {
                        showAlert('Custodia creada exitosamente!');
                        this.reset();
                        $('#idcliente, #idMarca, #idModelo').val(null).trigger('change');
                        
                        // Redirigir después de 2 segundos
                        setTimeout(() => {
                            window.location.href = '{{ route("solicitudcustodia.index") }}';
                        }, 2000);
                    } else {
                        throw new Error(data.message || 'Error al crear la custodia');
                    }

                } catch (error) {
                    console.error('Error:', error);
                    showAlert(error.message, 'error');
                } finally {
                    // Restaurar botón
                    $('#submitBtn').prop('disabled', false);
                    $('#submitText').text('Guardar Custodia');
                    $('#submitSpinner').addClass('hidden');
                }
            });

            // Debug: Verificar que Select2 se inicializó correctamente
            console.log('Select2 inicializado:', {
                clientes: $('#idcliente').hasClass('select2-hidden-accessible'),
                marcas: $('#idMarca').hasClass('select2-hidden-accessible'),
                modelos: $('#idModelo').hasClass('select2-hidden-accessible')
            });
        });
    </script>
</x-layout.default>