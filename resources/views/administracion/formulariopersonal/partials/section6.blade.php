<!-- Sección 6: Documentos Importantes -->
<div class="form-section bg-white rounded-2xl shadow-lg p-6 md:p-8 mt-8 border border-gray-200">
    <!-- Encabezado de sección -->
    <div class="section-header mb-8 pb-6 border-b border-gray-200">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center">
                <div class="bg-gradient-to-r from-emerald-500 to-green-600 w-12 h-12 rounded-xl flex items-center justify-center mr-4 shadow-md">
                    <i class="fas fa-file-alt text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl md:text-2xl font-bold text-gray-800">Documentos Importantes</h3>
                    <p class="text-gray-500 mt-1 text-sm md:text-base">Marque con (✓) si ha entregado los siguientes documentos</p>
                </div>
            </div>
            <span class="bg-emerald-50 text-emerald-700 px-4 py-2 rounded-full text-sm font-semibold border border-emerald-100">
                Sección 6
            </span>
        </div>
    </div>

    <!-- Información importante -->
    <div class="mb-8 bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-100 rounded-xl p-5">
        <div class="flex items-start">
            <i class="fas fa-exclamation-triangle text-emerald-500 text-lg mt-1 mr-3"></i>
            <div>
                <h4 class="font-medium text-emerald-800 mb-1">Documentación requerida</h4>
                <p class="text-emerald-700 text-sm">
                    Marque todos los documentos que haya entregado formalmente a Recursos Humanos. 
                    Esta información es vital para su expediente laboral.
                </p>
            </div>
        </div>
    </div>

    <!-- Documentos importantes -->
    <div class="mb-10">
        <h4 class="text-lg font-semibold text-gray-700 mb-6 flex items-center">
            <i class="fas fa-clipboard-list text-emerald-500 mr-2 text-sm"></i>
            Lista de Documentos Entregados
        </h4>

        <!-- Barra de progreso de documentos -->
        <div class="mb-8 bg-gradient-to-r from-gray-50 to-white border border-gray-200 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <i class="fas fa-percentage text-emerald-500 mr-2"></i>
                    <span class="text-sm font-medium text-gray-700">Progreso de documentación</span>
                </div>
                <span class="text-sm font-bold text-emerald-600" id="documentos-percentage">0%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-gradient-to-r from-emerald-400 to-emerald-500 h-3 rounded-full transition-all duration-500" 
                     style="width: 0%" id="documentos-progress"></div>
            </div>
            <p class="text-xs text-gray-500 mt-2" id="documentos-count">0 de 9 documentos marcados</p>
        </div>

        <!-- Documentos en grid responsive -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $documentos = [
                    [
                        'id' => 'doc_cv',
                        'name' => 'documentos[]',
                        'value' => 'CV',
                        'label' => 'Currículum Vitae',
                        'category' => 'personal',
                        'icon' => 'fas fa-user-graduate',
                        'color' => 'emerald',
                        'description' => 'CV actualizado y firmado'
                    ],
                    [
                        'id' => 'doc_dni',
                        'name' => 'documentos[]',
                        'value' => 'DNI',
                        'label' => 'Copia de DNI Vigente',
                        'category' => 'identidad',
                        'icon' => 'fas fa-id-card',
                        'color' => 'blue',
                        'description' => 'Ambas caras legibles'
                    ],
                    [
                        'id' => 'doc_vacunacion',
                        'name' => 'documentos[]',
                        'value' => 'VACUNA',
                        'label' => 'Cartilla de Vacunación',
                        'category' => 'salud',
                        'icon' => 'fas fa-syringe',
                        'color' => 'red',
                        'description' => 'Incluye COVID-19 si aplica'
                    ],
                    [
                        'id' => 'doc_antecedentes',
                        'name' => 'documentos[]',
                        'value' => 'ANTECEDENTES',
                        'label' => 'Certificado de antecedentes policiales',
                        'category' => 'legal',
                        'icon' => 'fas fa-gavel',
                        'color' => 'purple',
                        'description' => 'Vigente (máximo 3 meses)'
                    ],
                    [
                        'id' => 'doc_trabajos',
                        'name' => 'documentos[]',
                        'value' => 'TRABAJOS',
                        'label' => 'Certificados de trabajos anteriores',
                        'category' => 'laboral',
                        'icon' => 'fas fa-briefcase',
                        'color' => 'amber',
                        'description' => 'Últimos 3 trabajos'
                    ],
                    [
                        'id' => 'doc_estudios',
                        'name' => 'documentos[]',
                        'value' => 'ESTUDIOS',
                        'label' => 'Certificados de estudios técnicos u otros',
                        'category' => 'academico',
                        'icon' => 'fas fa-graduation-cap',
                        'color' => 'indigo',
                        'description' => 'Título o grado más alto'
                    ],
                    [
                        'id' => 'doc_domicilio',
                        'name' => 'documentos[]',
                        'value' => 'DOMICILIO',
                        'label' => 'Declaración Jurada de domicilio',
                        'category' => 'legal',
                        'icon' => 'fas fa-home',
                        'color' => 'orange',
                        'description' => 'Formato de la empresa'
                    ],
                    [
                        'id' => 'doc_matrimonio',
                        'name' => 'documentos[]',
                        'value' => 'MATRIMONIO',
                        'label' => 'Partida de Matrimonio u otros',
                        'category' => 'familiar',
                        'icon' => 'fas fa-ring',
                        'color' => 'pink',
                        'description' => 'Si aplica a su situación'
                    ],
                    [
                        'id' => 'doc_dni_hijos',
                        'name' => 'documentos[]',
                        'value' => 'DNI_HIJOS',
                        'label' => 'Copia de DNI de hijos',
                        'category' => 'familiar',
                        'icon' => 'fas fa-child',
                        'color' => 'cyan',
                        'description' => 'Si tiene hijos dependientes'
                    ]
                ];
            @endphp

            @foreach ($documentos as $documento)
                <div class="documento-card bg-white border border-gray-200 rounded-2xl p-5 hover:shadow-lg transition-all duration-300 hover:border-{{ $documento['color'] }}-300">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-{{ $documento['color'] }}-100 flex items-center justify-center mr-3">
                                <i class="{{ $documento['icon'] }} text-{{ $documento['color'] }}-600"></i>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-{{ $documento['color'] }}-600 bg-{{ $documento['color'] }}-50 px-2 py-1 rounded-full">
                                    @switch($documento['category'])
                                        @case('personal')
                                            Personal
                                            @break
                                        @case('identidad')
                                            Identidad
                                            @break
                                        @case('salud')
                                            Salud
                                            @break
                                        @case('legal')
                                            Legal
                                            @break
                                        @case('laboral')
                                            Laboral
                                            @break
                                        @case('academico')
                                            Académico
                                            @break
                                        @case('familiar')
                                            Familiar
                                            @break
                                        @default
                                            General
                                    @endswitch
                                </span>
                            </div>
                        </div>
                        
                        <!-- Checkbox personalizado -->
                        <div class="relative">
                            <input type="checkbox" 
                                   id="{{ $documento['id'] }}" 
                                   name="{{ $documento['name'] }}" 
                                   value="{{ $documento['value'] }}"
                                   class="documento-checkbox absolute opacity-0 w-0 h-0">
                            <label for="{{ $documento['id'] }}" 
                                   class="custom-checkbox w-8 h-8 rounded-lg border-2 border-gray-300 flex items-center justify-center cursor-pointer transition-all duration-200 hover:border-{{ $documento['color'] }}-400 hover:bg-{{ $documento['color'] }}-50">
                                <i class="fas fa-check text-white text-sm check-icon opacity-0"></i>
                            </label>
                        </div>
                    </div>

                    <h4 class="font-semibold text-gray-800 mb-2">{{ $documento['label'] }}</h4>
                    <p class="text-sm text-gray-600 mb-4">{{ $documento['description'] }}</p>
                    
                    <!-- Estado del documento -->
                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                        <div class="flex items-center">
                            <div class="w-2 h-2 rounded-full bg-gray-300 mr-2 documento-status-indicator" id="status-{{ $documento['id'] }}"></div>
                            <span class="text-xs text-gray-500 documento-status-text" id="text-{{ $documento['id'] }}">Pendiente</span>
                        </div>
                        <span class="text-xs font-medium text-gray-400 documento-number">#{{ $loop->iteration }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Documentos adicionales -->
        <div class="mt-8">
            <h5 class="text-md font-semibold text-gray-700 mb-4 flex items-center">
                <i class="fas fa-plus-circle text-emerald-500 mr-2"></i>
                Documentos Adicionales (Opcionales)
            </h5>
            
            <div class="bg-gradient-to-r from-gray-50 to-white border border-gray-200 rounded-2xl p-6">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Especifique otros documentos entregados
                    </label>
                    <div class="relative">
                        <input type="text" 
                               id="nuevo-documento"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all bg-white"
                               placeholder="Ej: Certificado médico, Licencia de conducir, etc.">
                        <i class="fas fa-file-upload text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                    </div>
                </div>
                
                <!-- Lista de documentos adicionales -->
                <div id="documentos-adicionales-container" class="space-y-3">
                    <!-- Los documentos adicionales se agregarán aquí dinámicamente -->
                </div>
                
                <!-- Botón para agregar documento adicional -->
                <div class="mt-4 flex justify-end">
                    <button type="button" 
                            id="agregar-documento-btn"
                            class="inline-flex items-center px-4 py-2 text-sm bg-white border border-emerald-300 text-emerald-600 rounded-xl hover:bg-emerald-50 transition-all">
                        <i class="fas fa-plus mr-2"></i>
                        Agregar documento
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Nota informativa -->
    <div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 rounded-xl p-5">
        <div class="flex items-start">
            <div class="bg-blue-100 rounded-lg p-3 mr-4">
                <i class="fas fa-info-circle text-blue-600 text-lg"></i>
            </div>
            <div>
                <h4 class="font-medium text-blue-800 mb-1">Información importante</h4>
                <p class="text-blue-700 text-sm">
                    • Si algún documento no aplica a su situación, puede dejar sin marcar la casilla correspondiente.<br>
                    • Los documentos marcados deben haber sido entregados físicamente o por correo a Recursos Humanos.<br>
                    • Para documentos adicionales, especifique claramente qué documento fue entregado.
                </p>
            </div>
        </div>
    </div>

    <!-- Resumen de documentación -->
    <div class="mt-8 bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200 rounded-2xl p-6">
        <h4 class="font-semibold text-emerald-800 mb-4 flex items-center">
            <i class="fas fa-chart-pie text-emerald-600 mr-2"></i>
            Resumen de Documentación
        </h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-xl border border-emerald-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Documentos</p>
                        <p class="text-2xl font-bold text-emerald-600" id="total-documentos">9</p>
                    </div>
                    <div class="w-12 h-12 rounded-lg bg-emerald-100 flex items-center justify-center">
                        <i class="fas fa-folder text-emerald-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-4 rounded-xl border border-green-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Entregados</p>
                        <p class="text-2xl font-bold text-green-600" id="entregados-count">0</p>
                    </div>
                    <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-4 rounded-xl border border-amber-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Pendientes</p>
                        <p class="text-2xl font-bold text-amber-600" id="pendientes-count">9</p>
                    </div>
                    <div class="w-12 h-12 rounded-lg bg-amber-100 flex items-center justify-center">
                        <i class="fas fa-clock text-amber-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-4 rounded-xl border border-blue-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Adicionales</p>
                        <p class="text-2xl font-bold text-blue-600" id="adicionales-count">0</p>
                    </div>
                    <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-plus-circle text-blue-600"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Indicador de progreso -->
    <div class="mt-6 pt-6 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                <span class="text-sm text-gray-600">Documentación completa</span>
            </div>
            <div class="flex items-center">
                <div class="w-32 bg-gray-200 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full" style="width: 0%" id="documentos-final-progress"></div>
                </div>
                <span class="ml-3 text-sm font-medium text-gray-700" id="documentos-final-percentage">0%</span>
            </div>
        </div>
    </div>
</div>

<script>
    // Inicializar funcionalidad de documentos
    document.addEventListener('DOMContentLoaded', function() {
        // Variables para documentos adicionales
        let documentosAdicionales = [];
        const maxAdicionales = 10;

        // Función para actualizar contadores y progreso
        function actualizarContadores() {
            const checkboxes = document.querySelectorAll('.documento-checkbox:checked');
            const totalCheckboxes = document.querySelectorAll('.documento-checkbox').length;
            const porcentaje = Math.round((checkboxes.length / totalCheckboxes) * 100);
            
            // Actualizar barra de progreso principal
            document.getElementById('documentos-progress').style.width = `${porcentaje}%`;
            document.getElementById('documentos-percentage').textContent = `${porcentaje}%`;
            document.getElementById('documentos-count').textContent = `${checkboxes.length} de ${totalCheckboxes} documentos marcados`;
            
            // Actualizar resumen
            document.getElementById('entregados-count').textContent = checkboxes.length;
            document.getElementById('pendientes-count').textContent = totalCheckboxes - checkboxes.length;
            document.getElementById('adicionales-count').textContent = documentosAdicionales.length;
            
            // Actualizar progreso final (incluyendo adicionales)
            const totalRequeridos = totalCheckboxes + documentosAdicionales.length;
            const completados = checkboxes.length + documentosAdicionales.length;
            const porcentajeFinal = totalRequeridos > 0 ? Math.round((completados / totalRequeridos) * 100) : 0;
            
            document.getElementById('documentos-final-progress').style.width = `${porcentajeFinal}%`;
            document.getElementById('documentos-final-percentage').textContent = `${porcentajeFinal}%`;
        }

        // Función para actualizar estado visual de un documento
        function actualizarEstadoDocumento(checkbox) {
            const label = checkbox.closest('.relative').querySelector('.custom-checkbox');
            const checkIcon = label.querySelector('.check-icon');
            const statusIndicator = document.getElementById(`status-${checkbox.id}`);
            const statusText = document.getElementById(`text-${checkbox.id}`);
            const card = checkbox.closest('.documento-card');
            
            if (checkbox.checked) {
                // Obtener color del documento
                const colorClass = Array.from(card.classList).find(cls => cls.includes('hover:border-'))?.split('-')[2];
                const color = colorClass || 'emerald';
                
                label.classList.remove('border-gray-300', 'hover:border-gray-400', 'hover:bg-gray-50');
                label.classList.add(`border-${color}-500`, `bg-${color}-500`);
                checkIcon.classList.remove('opacity-0');
                checkIcon.classList.add('opacity-100');
                
                statusIndicator.classList.remove('bg-gray-300');
                statusIndicator.classList.add(`bg-${color}-500`);
                statusText.textContent = 'Entregado';
                statusText.classList.remove('text-gray-500');
                statusText.classList.add(`text-${color}-600`);
                
                // Efecto de animación
                label.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    label.style.transform = 'scale(1)';
                }, 200);
            } else {
                const colorClass = Array.from(card.classList).find(cls => cls.includes('hover:border-'))?.split('-')[2];
                const color = colorClass || 'emerald';
                
                label.classList.remove(`border-${color}-500`, `bg-${color}-500`);
                label.classList.add('border-gray-300', 'hover:border-gray-400', 'hover:bg-gray-50');
                checkIcon.classList.remove('opacity-100');
                checkIcon.classList.add('opacity-0');
                
                statusIndicator.classList.remove(`bg-${color}-500`);
                statusIndicator.classList.add('bg-gray-300');
                statusText.textContent = 'Pendiente';
                statusText.classList.remove(`text-${color}-600`);
                statusText.classList.add('text-gray-500');
            }
        }

        // Inicializar eventos para checkboxes existentes
        document.querySelectorAll('.documento-checkbox').forEach(checkbox => {
            // Configurar estado inicial
            actualizarEstadoDocumento(checkbox);
            
            // Agregar evento change
            checkbox.addEventListener('change', function() {
                actualizarEstadoDocumento(this);
                actualizarContadores();
            });
        });

        // Función para agregar documento adicional
        document.getElementById('agregar-documento-btn').addEventListener('click', function() {
            const input = document.getElementById('nuevo-documento');
            const nombreDocumento = input.value.trim();
            
            if (!nombreDocumento) {
                alert('Por favor, ingrese el nombre del documento.');
                input.focus();
                return;
            }
            
            if (documentosAdicionales.length >= maxAdicionales) {
                alert(`Máximo ${maxAdicionales} documentos adicionales permitidos.`);
                return;
            }
            
            // Agregar a la lista
            documentosAdicionales.push(nombreDocumento);
            
            // Crear elemento de documento adicional
            const nuevoId = `doc_adicional_${documentosAdicionales.length}`;
            const documentoElement = document.createElement('div');
            documentoElement.className = 'flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg';
            documentoElement.innerHTML = `
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center mr-3">
                        <i class="fas fa-file text-indigo-600 text-sm"></i>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-800">${nombreDocumento}</span>
                        <p class="text-xs text-gray-500">Documento adicional</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="flex items-center">
                        <input type="checkbox" id="${nuevoId}" name="documentos_adicionales[]" value="${nombreDocumento}"
                            class="documento-adicional-checkbox h-4 w-4 text-indigo-600 rounded focus:ring-indigo-500">
                        <label for="${nuevoId}" class="ml-2 text-sm text-gray-700 cursor-pointer">Entregado</label>
                    </div>
                    <button type="button" class="eliminar-documento-btn text-red-500 hover:text-red-700" data-nombre="${nombreDocumento}">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            `;
            
            // Agregar al contenedor
            const container = document.getElementById('documentos-adicionales-container');
            container.appendChild(documentoElement);
            
            // Agregar eventos al nuevo documento
            const nuevoCheckbox = documentoElement.querySelector('.documento-adicional-checkbox');
            nuevoCheckbox.addEventListener('change', actualizarContadores);
            
            const eliminarBtn = documentoElement.querySelector('.eliminar-documento-btn');
            eliminarBtn.addEventListener('click', function() {
                const nombre = this.getAttribute('data-nombre');
                eliminarDocumentoAdicional(nombre);
            });
            
            // Limpiar input
            input.value = '';
            input.focus();
            
            // Actualizar contadores
            actualizarContadores();
            
            // Deshabilitar botón si se alcanza el límite
            if (documentosAdicionales.length >= maxAdicionales) {
                document.getElementById('agregar-documento-btn').disabled = true;
                document.getElementById('agregar-documento-btn').classList.add('opacity-50', 'cursor-not-allowed');
            }
        });

        // Función para eliminar documento adicional
        function eliminarDocumentoAdicional(nombre) {
            if (confirm('¿Está seguro de eliminar este documento adicional?')) {
                documentosAdicionales = documentosAdicionales.filter(doc => doc !== nombre);
                
                // Eliminar elemento del DOM
                const elementos = document.querySelectorAll('.eliminar-documento-btn');
                elementos.forEach(btn => {
                    if (btn.getAttribute('data-nombre') === nombre) {
                        btn.closest('.flex.items-center.justify-between').remove();
                    }
                });
                
                // Habilitar botón de agregar si estaba deshabilitado
                if (documentosAdicionales.length < maxAdicionales) {
                    document.getElementById('agregar-documento-btn').disabled = false;
                    document.getElementById('agregar-documento-btn').classList.remove('opacity-50', 'cursor-not-allowed');
                }
                
                // Actualizar contadores
                actualizarContadores();
            }
        }

        // Permitir agregar documento con Enter
        document.getElementById('nuevo-documento').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('agregar-documento-btn').click();
            }
        });

        // Inicializar contadores
        actualizarContadores();
    });
</script>

<style>
    /* Estilos para checkboxes personalizados */
    .documento-checkbox:checked + .custom-checkbox {
        animation: checkmark 0.3s ease;
    }
    
    @keyframes checkmark {
        0% { transform: scale(0.8); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }
    
    /* Estilos para tarjetas de documentos */
    .documento-card {
        transition: all 0.3s ease;
    }
    
    .documento-card:hover {
        transform: translateY(-2px);
    }
    
    /* Estilos para el estado de documentos */
    .documento-status-indicator {
        transition: all 0.3s ease;
    }
    
    /* Estilos para documentos adicionales */
    .documento-adicional-checkbox:checked {
        background-color: #4f46e5;
        border-color: #4f46e5;
    }
    
    .eliminar-documento-btn {
        transition: all 0.2s ease;
    }
    
    .eliminar-documento-btn:hover {
        transform: scale(1.1);
    }
    
    /* Estilos para barras de progreso */
    #documentos-progress {
        transition: width 0.5s ease-in-out;
    }
    
    #documentos-final-progress {
        transition: width 0.5s ease-in-out;
    }
    
    @media (max-width: 768px) {
        .documento-card {
            padding: 1rem;
        }
        
        .custom-checkbox {
            width: 28px;
            height: 28px;
        }
        
        .check-icon {
            font-size: 0.75rem;
        }
    }
</style>