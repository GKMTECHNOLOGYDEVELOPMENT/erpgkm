<!-- Sección 2: Información Académica -->
<div class="form-section bg-white rounded-2xl shadow-lg p-6 md:p-8 mt-8 border border-gray-200">
    <!-- Encabezado de sección -->
    <div class="section-header mb-8 pb-6 border-b border-gray-200">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 w-12 h-12 rounded-xl flex items-center justify-center mr-4 shadow-md">
                    <i class="fas fa-graduation-cap text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl md:text-2xl font-bold text-gray-800">Información Académica</h3>
                    <p class="text-gray-500 mt-1 text-sm md:text-base">Consignar los estudios realizados en orden cronológico</p>
                </div>
            </div>
            <span class="bg-purple-50 text-purple-700 px-4 py-2 rounded-full text-sm font-semibold border border-purple-100">
                Sección 2
            </span>
        </div>
    </div>

    <!-- Instrucciones -->
    <div class="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 rounded-xl p-4 md:p-5">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-500 text-lg mt-1 mr-3"></i>
            <div>
                <h4 class="font-medium text-blue-800 mb-1">Instrucciones</h4>
                <p class="text-blue-700 text-sm">Complete la siguiente tabla con su información académica. Marque "SI" si terminó el nivel educativo correspondiente.</p>
            </div>
        </div>
    </div>

    <!-- Tabla de estudios - Versión responsive -->
    <div class="overflow-hidden rounded-2xl border border-gray-200 shadow-sm">
        <!-- Encabezados de tabla para desktop -->
        <div class="hidden md:grid md:grid-cols-7 bg-gradient-to-r from-purple-50 to-purple-100 border-b border-purple-200">
            @php
                $headers = [
                    'Nivel Educativo',
                    '¿Terminó?',
                    'Centro de Estudios',
                    'Especialidad',
                    'Nivel / Grado',
                    'Fecha Inicio',
                    'Fecha Fin'
                ];
            @endphp
            @foreach ($headers as $header)
                <div class="px-4 py-4 text-left">
                    <span class="text-sm font-semibold text-purple-800 uppercase tracking-wide">{{ $header }}</span>
                </div>
            @endforeach
        </div>

        <!-- Contenido de tabla -->
        <div class="divide-y divide-gray-100">
            @php
                $niveles = [
                    [
                        'id' => 'secundaria',
                        'nombre' => 'Secundaria',
                        'color' => 'blue',
                        'icon' => 'fas fa-school'
                    ],
                    [
                        'id' => 'tecnico',
                        'nombre' => 'Técnico / Instituto',
                        'color' => 'green',
                        'icon' => 'fas fa-tools'
                    ],
                    [
                        'id' => 'universitario',
                        'nombre' => 'Universitario',
                        'color' => 'indigo',
                        'icon' => 'fas fa-university'
                    ],
                    [
                        'id' => 'postgrado',
                        'nombre' => 'Post Grado',
                        'color' => 'purple',
                        'icon' => 'fas fa-user-graduate'
                    ]
                ];
            @endphp

            @foreach ($niveles as $index => $nivel)
                <!-- Fila para desktop -->
                <div class="hidden md:grid md:grid-cols-7 items-center p-4 hover:bg-gray-50 transition-colors duration-200 {{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                    <!-- Nivel Educativo -->
                    <div class="px-4 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-{{ $nivel['color'] }}-100 flex items-center justify-center mr-3">
                                <i class="{{ $nivel['icon'] }} text-{{ $nivel['color'] }}-600"></i>
                            </div>
                            <span class="font-semibold text-gray-800">{{ $nivel['nombre'] }}</span>
                        </div>
                    </div>

                    <!-- ¿Terminó? -->
                    <div class="px-4 py-4">
                        <div class="flex space-x-6">
                            <div class="flex items-center">
                                <input type="radio" id="{{ $nivel['id'] }}_si" name="{{ $nivel['id'] }}_termino" value="SI"
                                    class="h-5 w-5 text-{{ $nivel['color'] }}-600 focus:ring-{{ $nivel['color'] }}-500">
                                <label for="{{ $nivel['id'] }}_si" class="ml-2 text-gray-700 cursor-pointer">SI</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="{{ $nivel['id'] }}_no" name="{{ $nivel['id'] }}_termino" value="NO"
                                    class="h-5 w-5 text-{{ $nivel['color'] }}-600 focus:ring-{{ $nivel['color'] }}-500">
                                <label for="{{ $nivel['id'] }}_no" class="ml-2 text-gray-700 cursor-pointer">NO</label>
                            </div>
                        </div>
                    </div>

                    <!-- Centro de Estudios -->
                    <div class="px-4 py-4">
                        <div class="relative">
                            <input type="text" name="{{ $nivel['id'] }}_centro"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-{{ $nivel['color'] }}-500 focus:ring-2 focus:ring-{{ $nivel['color'] }}-200 transition-all bg-white"
                                placeholder="Nombre del centro">
                        </div>
                    </div>

                    <!-- Especialidad -->
                    <div class="px-4 py-4">
                        <div class="relative">
                            <input type="text" name="{{ $nivel['id'] }}_especialidad"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-{{ $nivel['color'] }}-500 focus:ring-2 focus:ring-{{ $nivel['color'] }}-200 transition-all bg-white"
                                placeholder="Especialidad o carrera">
                        </div>
                    </div>

                    <!-- Nivel / Grado -->
                    <div class="px-4 py-4">
                        <div class="relative">
                            <input type="text" name="{{ $nivel['id'] }}_grado"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-{{ $nivel['color'] }}-500 focus:ring-2 focus:ring-{{ $nivel['color'] }}-200 transition-all bg-white"
                                placeholder="Ej: Bachiller, Titulado">
                        </div>
                    </div>

                    <!-- Fecha Inicio -->
                    <div class="px-4 py-4">
                        <div class="relative">
                            <input type="text" 
                                   name="{{ $nivel['id'] }}_inicio"
                                   class="flatpickr-date w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-{{ $nivel['color'] }}-500 focus:ring-2 focus:ring-{{ $nivel['color'] }}-200 transition-all bg-white"
                                   placeholder="Seleccione fecha"
                                   data-nivel="{{ $nivel['id'] }}">
                            <i class="fas fa-calendar-alt text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                        </div>
                    </div>

                    <!-- Fecha Fin -->
                    <div class="px-4 py-4">
                        <div class="relative">
                            <input type="text" 
                                   name="{{ $nivel['id'] }}_fin"
                                   class="flatpickr-date w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-{{ $nivel['color'] }}-500 focus:ring-2 focus:ring-{{ $nivel['color'] }}-200 transition-all bg-white"
                                   placeholder="Seleccione fecha"
                                   data-nivel="{{ $nivel['id'] }}">
                            <i class="fas fa-calendar-check text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta para móvil -->
                <div class="md:hidden bg-white border border-gray-200 rounded-xl p-5 mb-4 shadow-sm">
                    <!-- Encabezado de la tarjeta -->
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-{{ $nivel['color'] }}-100 flex items-center justify-center mr-3">
                                <i class="{{ $nivel['icon'] }} text-{{ $nivel['color'] }}-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">{{ $nivel['nombre'] }}</h4>
                                <p class="text-sm text-gray-500">Nivel educativo</p>
                            </div>
                        </div>
                        <span class="bg-{{ $nivel['color'] }}-100 text-{{ $nivel['color'] }}-800 text-xs font-semibold px-3 py-1 rounded-full">
                            {{ $index + 1 }}/4
                        </span>
                    </div>

                    <!-- Campos para móvil -->
                    <div class="space-y-4">
                        <!-- ¿Terminó? -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">¿Terminó este nivel?</label>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="flex items-center justify-center p-3 border border-gray-300 rounded-lg hover:bg-{{ $nivel['color'] }}-50 transition-colors">
                                    <input type="radio" id="{{ $nivel['id'] }}_si_mobile" name="{{ $nivel['id'] }}_termino" value="SI"
                                        class="h-5 w-5 text-{{ $nivel['color'] }}-600 focus:ring-{{ $nivel['color'] }}-500">
                                    <label for="{{ $nivel['id'] }}_si_mobile" class="ml-2 text-gray-700 cursor-pointer">Sí, terminé</label>
                                </div>
                                <div class="flex items-center justify-center p-3 border border-gray-300 rounded-lg hover:bg-{{ $nivel['color'] }}-50 transition-colors">
                                    <input type="radio" id="{{ $nivel['id'] }}_no_mobile" name="{{ $nivel['id'] }}_termino" value="NO"
                                        class="h-5 w-5 text-{{ $nivel['color'] }}-600 focus:ring-{{ $nivel['color'] }}-500">
                                    <label for="{{ $nivel['id'] }}_no_mobile" class="ml-2 text-gray-700 cursor-pointer">No, no terminé</label>
                                </div>
                            </div>
                        </div>

                        <!-- Centro de Estudios -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Centro de Estudios</label>
                            <div class="relative">
                                <input type="text" name="{{ $nivel['id'] }}_centro"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-{{ $nivel['color'] }}-500 focus:ring-2 focus:ring-{{ $nivel['color'] }}-200 transition-all bg-white"
                                    placeholder="Ej: Colegio Nacional">
                                <i class="fas fa-school text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                            </div>
                        </div>

                        <!-- Especialidad -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Especialidad</label>
                            <div class="relative">
                                <input type="text" name="{{ $nivel['id'] }}_especialidad"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-{{ $nivel['color'] }}-500 focus:ring-2 focus:ring-{{ $nivel['color'] }}-200 transition-all bg-white"
                                    placeholder="Ej: Ciencias">
                                <i class="fas fa-book text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                            </div>
                        </div>

                        <!-- Nivel / Grado -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nivel / Grado Académico</label>
                            <div class="relative">
                                <input type="text" name="{{ $nivel['id'] }}_grado"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-{{ $nivel['color'] }}-500 focus:ring-2 focus:ring-{{ $nivel['color'] }}-200 transition-all bg-white"
                                    placeholder="Ej: Bachiller">
                                <i class="fas fa-award text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                            </div>
                        </div>

                        <!-- Fechas -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Inicio</label>
                                <div class="relative">
                                    <input type="text" 
                                           name="{{ $nivel['id'] }}_inicio"
                                           class="flatpickr-date-mobile w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-{{ $nivel['color'] }}-500 focus:ring-2 focus:ring-{{ $nivel['color'] }}-200 transition-all bg-white"
                                           placeholder="Seleccione"
                                           data-nivel="{{ $nivel['id'] }}">
                                    <i class="fas fa-calendar text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Fin</label>
                                <div class="relative">
                                    <input type="text" 
                                           name="{{ $nivel['id'] }}_fin"
                                           class="flatpickr-date-mobile w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-{{ $nivel['color'] }}-500 focus:ring-2 focus:ring-{{ $nivel['color'] }}-200 transition-all bg-white"
                                           placeholder="Seleccione"
                                           data-nivel="{{ $nivel['id'] }}">
                                    <i class="fas fa-calendar-check text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Nota informativa -->
    <div class="mt-8 bg-gradient-to-r from-gray-50 to-white border border-gray-200 rounded-xl p-5">
        <div class="flex items-start">
            <div class="bg-blue-100 rounded-lg p-3 mr-4">
                <i class="fas fa-lightbulb text-blue-600 text-lg"></i>
            </div>
            <div>
                <h4 class="font-medium text-gray-800 mb-1">Importante</h4>
                <p class="text-gray-600 text-sm">
                    Complete todos los niveles educativos que haya cursado. Si no cursó algún nivel, puede dejarlo en blanco. 
                    Las fechas deben ser consistentes y en orden cronológico.
                </p>
            </div>
        </div>
    </div>

    <!-- Indicador de progreso -->
    <div class="mt-6 pt-6 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                <span class="text-sm text-gray-600">Complete al menos un nivel educativo</span>
            </div>
            <div class="flex items-center">
                <div class="w-32 bg-gray-200 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full" style="width: 0%" id="academic-progress"></div>
                </div>
                <span class="ml-3 text-sm font-medium text-gray-700" id="academic-percentage">0%</span>
            </div>
        </div>
    </div>
</div>

<script>
    // Inicializar Flatpickr cuando el DOM esté cargado
    document.addEventListener('DOMContentLoaded', function() {
        // Configuración común para Flatpickr
        const flatpickrOptions = {
            locale: "es",
            dateFormat: "Y-m-d",
            altFormat: "d/m/Y",
            altInput: true,
            altInputClass: "flatpickr-alt-input",
            theme: "airbnb",
            maxDate: "today",
            disableMobile: false, // Habilitar en móviles
            allowInput: true, // Permitir entrada manual
            clickOpens: true,
            // Opciones específicas por nivel
            onValueUpdate: function(selectedDates, dateStr, instance) {
                calculateAcademicProgress();
            }
        };

        // Inicializar Flatpickr para desktop
        document.querySelectorAll('.flatpickr-date').forEach(function(element) {
            flatpickr(element, {
                ...flatpickrOptions,
                // Si es fecha de fin, establecer fecha mínima basada en fecha de inicio
                onChange: function(selectedDates, dateStr, instance) {
                    const nivel = element.getAttribute('data-nivel');
                    const isFin = element.name.includes('_fin');
                    
                    if (isFin) {
                        const inicioInput = document.querySelector(`[name="${nivel}_inicio"]`);
                        if (inicioInput && inicioInput.value) {
                            instance.set('minDate', inicioInput.value);
                        }
                    }
                    
                    calculateAcademicProgress();
                }
            });
        });

        // Inicializar Flatpickr para móvil (configuración diferente si es necesario)
        document.querySelectorAll('.flatpickr-date-mobile').forEach(function(element) {
            flatpickr(element, {
                ...flatpickrOptions,
                // En móvil, usar modal para mejor experiencia
                disableMobile: false,
                // Si es fecha de fin, establecer fecha mínima basada en fecha de inicio
                onChange: function(selectedDates, dateStr, instance) {
                    const nivel = element.getAttribute('data-nivel');
                    const isFin = element.name.includes('_fin');
                    
                    if (isFin) {
                        const inicioInput = document.querySelector(`[name="${nivel}_inicio"]`);
                        if (inicioInput && inicioInput.value) {
                            instance.set('minDate', inicioInput.value);
                        }
                    }
                    
                    calculateAcademicProgress();
                }
            });
        });

        // Calcular progreso de sección académica
        function calculateAcademicProgress() {
            const academicFields = document.querySelectorAll('[name$="_centro"], [name$="_termino"], .flatpickr-date, .flatpickr-date-mobile');
            let completed = 0;
            let total = 0;
            
            academicFields.forEach(field => {
                if (field.type === 'radio') {
                    const name = field.name;
                    const checked = document.querySelector(`[name="${name}"]:checked`);
                    if (checked && checked.value === 'SI') {
                        completed++;
                    }
                    total++;
                } else if (field.type === 'text' || field.classList.contains('flatpickr-date') || field.classList.contains('flatpickr-date-mobile')) {
                    // Verificar si tiene valor (incluyendo Flatpickr)
                    if (field.value && field.value.trim() !== '') {
                        completed++;
                    }
                    total++;
                }
            });
            
            const percentage = total > 0 ? Math.round((completed / total) * 100) : 0;
            document.getElementById('academic-percentage').textContent = `${percentage}%`;
            document.getElementById('academic-progress').style.width = `${percentage}%`;
        }
        
        // Escuchar cambios en campos académicos
        document.querySelectorAll('[name$="_centro"], [name$="_termino"], [name$="_especialidad"], [name$="_grado"], .flatpickr-date, .flatpickr-date-mobile').forEach(field => {
            field.addEventListener('input', calculateAcademicProgress);
            field.addEventListener('change', calculateAcademicProgress);
        });
        
        // También escuchar eventos de Flatpickr
        document.addEventListener('flatpickr.change', calculateAcademicProgress);
        
        calculateAcademicProgress();
    });
</script>

