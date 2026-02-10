<x-layout.default title="Repuestos - ERP Solutions Force">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .dataTables_length select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            padding-right: 1.5rem;
            background-image: none;
        }

        .select-cliente-general {
            min-width: 180px !important;
            max-width: 180px !important;
        }
        
        /* Estilos para el modal */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }
        
        .modal-overlay.active {
            display: flex;
        }
        
        .modal-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            width: 90%;
            max-width: 500px;
            animation: modalFadeIn 0.3s ease;
        }
        
        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
        }
        
        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #6b7280;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
        }
        
        .modal-close:hover {
            background: #f3f4f6;
            color: #374151;
        }
        
        .modal-body {
            padding: 1.5rem;
        }
        
        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }
        
        .date-input-group {
            margin-bottom: 1.25rem;
        }
        
        .date-input-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151;
            font-size: 0.875rem;
        }
        
        .date-input {
            width: 100%;
            padding: 0.625rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.875rem;
            transition: border-color 0.2s;
        }
        
        .date-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .loading-spinner {
            display: none;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1rem;
            color: #3b82f6;
            font-weight: 500;
        }
        
        .loading-spinner.active {
            display: flex;
        }
        
        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid #e5e7eb;
            border-top-color: #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
        
        .btn-export {
            min-width: 140px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
    </style>

    <!-- Modal para filtros de fecha -->
    <div id="modalFechas" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="fas fa-calendar-alt mr-2"></i> Configurar Fechas del Reporte
                </h3>
                <button type="button" class="modal-close" onclick="closeModal()">
                    &times;
                </button>
            </div>
            <div class="modal-body">
                <p class="text-gray-600 mb-4 text-sm">
                    Selecciona el rango de fechas para generar el reporte de inventario. 
                    El reporte incluirá el stock actual y los movimientos dentro del período seleccionado.
                </p>
                
                <div class="date-input-group">
                    <label for="fecha_inicio_modal">
                        <i class="fas fa-calendar-plus mr-1"></i> Fecha de Inicio
                    </label>
                    <input type="date" 
                           id="fecha_inicio_modal" 
                           class="date-input"
                           value="{{ date('Y-m-01') }}">
                </div>
                
                <div class="date-input-group">
                    <label for="fecha_fin_modal">
                        <i class="fas fa-calendar-minus mr-1"></i> Fecha de Fin
                    </label>
                    <input type="date" 
                           id="fecha_fin_modal" 
                           class="date-input"
                           value="{{ date('Y-m-d') }}">
                </div>
                
                <!-- Opciones rápidas -->
                <div class="mb-4">
                    <p class="text-gray-600 mb-2 text-sm font-medium">Períodos rápidos:</p>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" onclick="setDateRange('today')" 
                                class="px-3 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded">
                            Hoy
                        </button>
                        <button type="button" onclick="setDateRange('yesterday')" 
                                class="px-3 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded">
                            Ayer
                        </button>
                        <button type="button" onclick="setDateRange('this_week')" 
                                class="px-3 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded">
                            Esta Semana
                        </button>
                        <button type="button" onclick="setDateRange('this_month')" 
                                class="px-3 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded">
                            Este Mes
                        </button>
                        <button type="button" onclick="setDateRange('last_month')" 
                                class="px-3 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded">
                            Mes Anterior
                        </button>
                    </div>
                </div>
                
                <div id="loadingSpinner" class="loading-spinner">
                    <div class="spinner"></div>
                    <span>Generando reporte...</span>
                </div>
                
                <div id="errorMessage" class="text-red-600 text-sm mt-2 hidden">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    <span id="errorText"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal()" 
                        class="px-4 py-2 text-sm border border-gray-300 rounded hover:bg-gray-50">
                    Cancelar
                </button>
                <button type="button" onclick="exportReport()" 
                        class="px-4 py-2 text-sm bg-primary text-white rounded hover:bg-primary-dark flex items-center gap-2">
                    <i class="fas fa-file-excel"></i>
                    <span>Generar Excel</span>
                </button>
            </div>
        </div>
    </div>

    <div x-data="multipleTable">
        <div>
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="javascript:;" class="text-primary hover:underline">Almacen</a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>Repuestos</span>
                </li>
            </ul>
        </div>
        <div class="panel mt-6">
            <div class="md:absolute md:top-5 ltr:md:left-5 rtl:md:right-5">
                <div class="flex flex-wrap items-center justify-center gap-2 mb-5 sm:justify-start md:flex-nowrap">

                    @if(\App\Helpers\PermisoHelper::tienePermiso('DESCARGAR EXCEL REPUESTO'))
                    <!-- Botón Exportar a Excel -->
                    <button type="button" class="btn btn-success btn-sm flex items-center gap-2"
                        onclick="window.location.href='{{ route('articulos.exportExcel') }}'">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                            <path
                                d="M4 3H20C21.1046 3 22 3.89543 22 5V19C22 20.1046 21.1046 21 20 21H4C2.89543 21 2 20.1046 2 19V5C2 3.89543 2 3 4 3Z"
                                stroke="currentColor" stroke-width="1.5" />
                            <path d="M16 10L8 14M8 10L16 14" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span>Excel</span>
                    </button>
                    @endif

                    @if(\App\Helpers\PermisoHelper::tienePermiso('DESCARGAR PDF REPUESTO'))
                    <!-- Botón Exportar a PDF -->
                    <button type="button" class="btn btn-danger btn-sm flex items-center gap-2"
                        onclick="window.location.href='{{ route('articulos.export.pdf') }}'">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                            <path
                                d="M2 5H22M2 5H22C22 6.10457 21.1046 7 20 7H4C2.89543 7 2 6.10457 2 5ZM2 5V19C2 20.1046 2.89543 21 4 21H20C21.1046 21 22 20.1046 22 19V5M9 14L15 14"
                                stroke="currentColor" stroke-width="1.5" />
                            <path d="M12 11L12 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                        <span>PDF</span>
                    </button>
                    @endif

                    <!-- Botón Exportar Reporte de Inventario General CON MODAL -->
                    <button type="button" class="btn btn-info btn-sm flex items-center gap-2 btn-export"
                        onclick="openModal()">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                            <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M12 16V12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M12 8H12.01" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <span>Reporte Inventario</span>
                    </button>

                    @if(\App\Helpers\PermisoHelper::tienePermiso('AGREGAR REPUESTO'))
                    <!-- Botón Agregar -->
                    <a href="{{ route('repuestos.create') }}" class="btn btn-primary btn-sm flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                            fill="none">
                            <path
                                d="M4 4H20C20.5523 4 21 4.44772 21 5V19C21 19.5523 20.5523 20 20 20H4C3.44772 20 3 19.5523 3 19V5C3 4.44772 3 4 4 4Z"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M7 9H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M7 13H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M7 17H13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        <span>Agregar</span>
                    </a>
                    @endif

                </div>
            </div>
            <div class="mb-4 flex justify-end items-center gap-3">
                <!-- Input de búsqueda -->
                <div class="relative w-64">
                    <input type="text" id="searchInput" placeholder="Buscar modelo..."
                        class="pr-10 pl-4 py-2 text-sm w-full border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary">
                    <button type="button" id="clearInput"
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-500 hidden">
                        <i class="fas fa-times-circle"></i>
                    </button>
                </div>

                <!-- Botón Buscar -->
                <button id="btnSearch"
                    class="btn btn-sm bg-primary text-white hover:bg-primary-dark px-4 py-2 rounded shadow-sm">
                    Buscar
                </button>
            </div>
            <table id="myTable1" class="w-full min-w-[1000px] table whitespace-nowrap">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Código de Repuestos</th>
                        <th>Categoria</th>
                        <th>Modelo</th>
                        <th>Stock Total</th>
                        <th>Entradas</th>
                        <th>Salidas</th>
                        <th>Cliente General</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Botón buscar
            $('#btnSearch').off('click').on('click', function() {
                const value = $('#searchInput').val();
                $('#myTable1').DataTable().search(value).draw();
            });

            // Enter para buscar
            $(document).on('keypress', '#searchInput', function(e) {
                if (e.which === 13) {
                    $('#btnSearch').click();
                }
            });

            // Mostrar botón limpiar si hay texto
            const input = document.getElementById('searchInput');
            const clearBtn = document.getElementById('clearInput');

            input.addEventListener('input', () => {
                clearBtn.classList.toggle('hidden', input.value.trim() === '');
            });

            // Botón limpiar
            clearBtn.addEventListener('click', () => {
                input.value = '';
                clearBtn.classList.add('hidden');
                $('#myTable1').DataTable().search('').draw();
            });
            
            // Cerrar modal con Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeModal();
                }
            });
        });

        // Funciones para el modal
        function openModal() {
            document.getElementById('modalFechas').classList.add('active');
            document.body.style.overflow = 'hidden';
            
            // Establecer valores por defecto
            const today = new Date().toISOString().split('T')[0];
            const firstDayOfMonth = new Date(new Date().getFullYear(), new Date().getMonth(), 2)
                .toISOString().split('T')[0];
            
            document.getElementById('fecha_inicio_modal').value = firstDayOfMonth;
            document.getElementById('fecha_fin_modal').value = today;
        }

        function closeModal() {
            document.getElementById('modalFechas').classList.remove('active');
            document.body.style.overflow = 'auto';
            hideError();
            hideLoading();
        }

        function showLoading() {
            document.getElementById('loadingSpinner').classList.add('active');
        }

        function hideLoading() {
            document.getElementById('loadingSpinner').classList.remove('active');
        }

        function showError(message) {
            const errorDiv = document.getElementById('errorMessage');
            const errorText = document.getElementById('errorText');
            errorText.textContent = message;
            errorDiv.classList.remove('hidden');
        }

        function hideError() {
            document.getElementById('errorMessage').classList.add('hidden');
        }

        function setDateRange(rangeType) {
            const today = new Date();
            let startDate, endDate;

            switch(rangeType) {
                case 'today':
                    startDate = today;
                    endDate = today;
                    break;
                    
                case 'yesterday':
                    const yesterday = new Date(today);
                    yesterday.setDate(today.getDate() - 1);
                    startDate = yesterday;
                    endDate = yesterday;
                    break;
                    
                case 'this_week':
                    startDate = new Date(today.setDate(today.getDate() - today.getDay()));
                    endDate = new Date(today.setDate(today.getDate() - today.getDay() + 6));
                    break;
                    
                case 'this_month':
                    startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                    endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                    break;
                    
                case 'last_month':
                    startDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                    endDate = new Date(today.getFullYear(), today.getMonth(), 0);
                    break;
                    
                default:
                    return;
            }

            // Formatear fechas como YYYY-MM-DD
            const formatDate = (date) => {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            };

            document.getElementById('fecha_inicio_modal').value = formatDate(startDate);
            document.getElementById('fecha_fin_modal').value = formatDate(endDate);
        }

        function exportReport() {
            const fechaInicio = document.getElementById('fecha_inicio_modal').value;
            const fechaFin = document.getElementById('fecha_fin_modal').value;
            
            // Validar fechas
            if (!fechaInicio || !fechaFin) {
                showError('Por favor, selecciona ambas fechas');
                return;
            }
            
            if (new Date(fechaInicio) > new Date(fechaFin)) {
                showError('La fecha de inicio no puede ser mayor a la fecha de fin');
                return;
            }
            
            // Mostrar loading
            showLoading();
            hideError();
            
            // Crear URL con parámetros
            const url = new URL('{{ route("repuestos.export.inventario.general") }}');
            url.searchParams.append('fecha_inicio', fechaInicio);
            url.searchParams.append('fecha_fin', fechaFin);
            
            // Crear formulario temporal para la descarga
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = url.toString();
            form.target = '_blank';
            
            // Agregar token CSRF si es necesario
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);
            }
            
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
            
            // Cerrar modal después de un breve retraso
            setTimeout(() => {
                hideLoading();
                closeModal();
                
                // Mostrar mensaje de éxito
                Swal.fire({
                    icon: 'success',
                    title: 'Reporte generado',
                    text: 'El reporte se está descargando. Si no inicia automáticamente, revisa la barra de descargas.',
                    timer: 3000,
                    showConfirmButton: false
                });
            }, 1000);
        }
    </script>

    <script>
        window.permisos = {
            puedeEditar: {{ \App\Helpers\PermisoHelper::tienePermiso('EDITAR REPUESTO') ? 'true' : 'false' }},
            puedeEliminar: {{ \App\Helpers\PermisoHelper::tienePermiso('ELIMINAR REPUESTO') ? 'true' : 'false' }},
            puedeVerdetalles: {{ \App\Helpers\PermisoHelper::tienePermiso('VER DETALLES REPUESTO') ? 'true' : 'false' }},
            puedeVerseries: {{ \App\Helpers\PermisoHelper::tienePermiso('VER SERIES REPUESTO') ? 'true' : 'false' }},
            puedeSeleccionarCliente: {{ \App\Helpers\PermisoHelper::tienePermiso('SELECCIONAR CLIENTE REPUESTO') ? 'true' : 'false' }},
        };
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
    <script src="{{ asset('assets/js/almacen/repuesto/repuesto.js') }}"></script>
    <script src="/assets/js/simple-datatables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <!-- SweetAlert2 para notificaciones -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</x-layout.default>