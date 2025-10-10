<x-layout.default>
    <!-- Incluir Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Incluir Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Select2 Tailwind Integration */
        .select2-container--default .select2-selection--single {
            height: 44px !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 0.5rem !important;
            background-color: white !important;
            transition: all 0.2s ease !important;
        }
        
        .select2-container--default .select2-selection--single:hover {
            border-color: #d1d5db !important;
        }
        
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1) !important;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 42px !important;
            padding-left: 14px !important;
            color: #111827 !important;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #9ca3af !important;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px !important;
            right: 10px !important;
        }
        
        .select2-dropdown {
            border: 1px solid #e5e7eb !important;
            border-radius: 0.5rem !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
        }
        
        .select2-results__option {
            padding: 10px 14px !important;
        }
        
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #6366f1 !important;
        }
        
        /* Cliente Option Styles */
        .cliente-option {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }
        
        .cliente-nombre {
            flex: 1;
            font-weight: 500;
            color: #111827;
        }
        
        .cliente-documento {
            background: #f3f4f6;
            padding: 2px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            color: #6b7280;
            font-weight: 500;
            white-space: nowrap;
        }
        
        /* Animation */
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .alert-slide {
            animation: slideInRight 0.3s ease-out;
        }
        
        /* Loading spinner */
        .spinner {
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #fff;
            width: 16px;
            height: 16px;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>

    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Nueva Custodia</h1>
                    <p class="mt-2 text-sm text-gray-600">Registrar nuevo equipo en custodia</p>
                </div>
                <a href="{{ route('solicitudcustodia.index') }}" 
                   class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-white bg-warning focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver al Listado
                </a>
            </div>
        </div>

        <!-- Formulario -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <form id="custodiaForm" method="POST" class="p-6 sm:p-8">
                @csrf

                <!-- Sección: Información del Cliente -->
                <div class="mb-10">
                    <div class="flex items-center mb-6 pb-3 border-b-2 border-gray-100">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 mr-3">
                            <i class="fas fa-user-circle text-lg"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900">Información del Cliente</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Cliente con Select2 -->
                        <div>
                            <label for="idcliente" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-users text-gray-400 mr-2 w-4"></i>
                                Cliente
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <select name="idcliente" id="idcliente" required
                                    class="w-full select2-clientes">
                                <option value="">Seleccionar cliente...</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Busca por nombre o documento</p>
                            @error('idcliente')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Número de Ticket -->
                        <div>
                            <label for="numero_ticket" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-ticket-alt text-gray-400 mr-2 w-4"></i>
                                Número de Ticket
                            </label>
                            <input type="text" name="numero_ticket" id="numero_ticket" 
                                value="{{ old('numero_ticket') }}"
                                placeholder="Ej: TKT-001"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                            @error('numero_ticket')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Sección: Información del Equipo -->
                <div class="mb-10">
                    <div class="flex items-center mb-6 pb-3 border-b-2 border-gray-100">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-100 text-blue-600 mr-3">
                            <i class="fas fa-laptop text-lg"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900">Información del Equipo</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Marca con Select2 -->
                        <div>
                            <label for="idMarca" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-tag text-gray-400 mr-2 w-4"></i>
                                Marca
                            </label>
                            <select name="idMarca" id="idMarca" class="w-full select2-marcas">
                                <option value="">Seleccionar marca...</option>
                            </select>
                            @error('idMarca')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Modelo con Select2 -->
                        <div>
                            <label for="idModelo" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-cube text-gray-400 mr-2 w-4"></i>
                                Modelo
                            </label>
                            <select name="idModelo" id="idModelo" class="w-full select2-modelos">
                                <option value="">Seleccionar modelo...</option>
                            </select>
                            @error('idModelo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Serie -->
                        <div>
                            <label for="serie" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-barcode text-gray-400 mr-2 w-4"></i>
                                Número de Serie
                            </label>
                            <input type="text" name="serie" id="serie" 
                                value="{{ old('serie') }}"
                                placeholder="Ej: SN123456789"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                            @error('serie')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Sección: Información de la Custodia -->
                <div class="mb-10">
                    <div class="flex items-center mb-6 pb-3 border-b-2 border-gray-100">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-green-100 text-green-600 mr-3">
                            <i class="fas fa-shield-alt text-lg"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900">Información de la Custodia</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Fecha de Ingreso -->
                        <div>
                            <label for="fecha_ingreso_custodia" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt text-gray-400 mr-2 w-4"></i>
                                Fecha de Ingreso
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="date" name="fecha_ingreso_custodia" id="fecha_ingreso_custodia" required
                                value="{{ old('fecha_ingreso_custodia', date('Y-m-d')) }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                            @error('fecha_ingreso_custodia')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ubicación Actual -->
                        <div>
                            <label for="ubicacion_actual" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-map-marker-alt text-gray-400 mr-2 w-4"></i>
                                Ubicación de Recepción
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="text" name="ubicacion_actual" id="ubicacion_actual" required
                                value="{{ old('ubicacion_actual') }}"
                                placeholder="Ej: Recepción Principal"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                            @error('ubicacion_actual')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Estado -->
                        <div>
                            <label for="estado" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-info-circle text-gray-400 mr-2 w-4"></i>
                                Estado
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <select name="estado" id="estado" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                                <option value="Pendiente" {{ old('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="En revisión" {{ old('estado') == 'En revisión' ? 'selected' : '' }}>En revisión</option>
                                <option value="Aprobado" {{ old('estado') == 'Aprobado' ? 'selected' : '' }}>Aprobado</option>
                            </select>
                            @error('estado')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Observaciones -->
                <div class="mb-8">
                    <label for="observaciones" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-sticky-note text-gray-400 mr-2 w-4"></i>
                        Observaciones
                    </label>
                    <textarea name="observaciones" id="observaciones" rows="4"
                        placeholder="Observaciones adicionales sobre el equipo o la custodia..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 resize-y">{{ old('observaciones') }}</textarea>
                    @error('observaciones')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Botones de Acción -->
                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('solicitudcustodia.index') }}" 
                       class="inline-flex justify-center items-center px-6 py-3 rounded-lg text-sm font-medium text-white bg-danger focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </a>
                    <button type="submit" id="submitBtn"
                            class="inline-flex justify-center items-center px-6 py-3 border border-transparent rounded-lg text-sm font-medium text-white bg-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 shadow-sm">
                        <i id="submitIcon" class="fas fa-save mr-2"></i>
                        <span id="submitText">Guardar Custodia</span>
                        <div id="submitSpinner" class="hidden ml-2 spinner"></div>
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer Info -->
        <div class="mt-6 text-center text-sm text-gray-500">
            <p>Los campos marcados con <span class="text-red-500">*</span> son obligatorios</p>
        </div>
    </div>

    <!-- Contenedor de Alertas -->
    <div id="alertContainer" class="fixed top-4 right-4 z-50 max-w-sm w-full space-y-2"></div>

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

            // Configuración base para Select2
            const baseSelect2Config = {
                language: 'es',
                width: '100%',
                allowClear: true,
                minimumInputLength: 1
            };

            // Inicializar Select2 para clientes
            $('#idcliente').select2({
                ...baseSelect2Config,
                placeholder: 'Buscar cliente...',
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
                templateResult: formatCliente,
                templateSelection: formatClienteSelection
            });

            // Inicializar Select2 para marcas
            $('#idMarca').select2({
                ...baseSelect2Config,
                placeholder: 'Buscar marca...',
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
                }
            });

            // Inicializar Select2 para modelos
            $('#idModelo').select2({
                ...baseSelect2Config,
                placeholder: 'Buscar modelo...',
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
                }
            });

            // Formatear la visualización de clientes
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
                const icons = {
                    success: 'fa-check-circle',
                    error: 'fa-exclamation-circle',
                    warning: 'fa-exclamation-triangle',
                    info: 'fa-info-circle'
                };

                const colors = {
                    success: 'bg-green-500',
                    error: 'bg-red-500',
                    warning: 'bg-yellow-500',
                    info: 'bg-blue-500'
                };

                const alert = $(`
                    <div class="alert-slide ${colors[type]} text-white px-6 py-4 rounded-lg shadow-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas ${icons[type]} mr-3 text-xl"></i>
                                <span class="font-medium">${message}</span>
                            </div>
                            <button class="ml-4 text-white hover:text-gray-200 transition-colors close-alert">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                `);
                
                $('#alertContainer').append(alert);
                
                alert.find('.close-alert').on('click', function() {
                    alert.fadeOut(300, function() { $(this).remove(); });
                });
                
                setTimeout(() => {
                    alert.fadeOut(300, function() { $(this).remove(); });
                }, 5000);
            }

            // Manejar el envío del formulario
            $('#custodiaForm').on('submit', async function(e) {
                e.preventDefault();

                // Validar que se haya seleccionado un cliente
                const clienteId = $('#idcliente').val();
                if (!clienteId) {
                    showAlert('Por favor seleccione un cliente', 'error');
                    $('#idcliente').focus();
                    return;
                }

                // Validar ubicación
                const ubicacion = $('#ubicacion_actual').val().trim();
                if (!ubicacion) {
                    showAlert('Por favor ingrese la ubicación de recepción', 'error');
                    $('#ubicacion_actual').focus();
                    return;
                }

                // Mostrar loading
                const $submitBtn = $('#submitBtn');
                const $submitIcon = $('#submitIcon');
                const $submitText = $('#submitText');
                const $submitSpinner = $('#submitSpinner');

                $submitBtn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed');
                $submitIcon.addClass('hidden');
                $submitText.text('Guardando...');
                $submitSpinner.removeClass('hidden');

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
                        showAlert('¡Custodia creada exitosamente!', 'success');
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
                    showAlert(error.message || 'Error al guardar la custodia. Por favor intente nuevamente.', 'error');
                } finally {
                    // Restaurar botón
                    $submitBtn.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed');
                    $submitIcon.removeClass('hidden');
                    $submitText.text('Guardar Custodia');
                    $submitSpinner.addClass('hidden');
                }
            });

            // Debug: Verificar que Select2 se inicializó correctamente
            console.log('✅ Formulario de custodia inicializado correctamente');
        });
    </script>
</x-layout.default>