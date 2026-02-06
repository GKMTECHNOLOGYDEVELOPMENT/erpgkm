<!-- Sección 4: Información de Salud -->
<div class="form-section bg-white rounded-2xl shadow-lg p-6 md:p-8 mt-8 border border-gray-200">
    <!-- Encabezado de sección -->
    <div class="section-header mb-8 pb-6 border-b border-gray-200">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center">
                <div class="bg-gradient-to-r from-red-500 to-red-600 w-12 h-12 rounded-xl flex items-center justify-center mr-4 shadow-md">
                    <i class="fas fa-heartbeat text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl md:text-2xl font-bold text-gray-800">Información de Salud</h3>
                    <p class="text-gray-500 mt-1 text-sm md:text-base">Complete sus datos de salud importantes para emergencias</p>
                </div>
            </div>
            <span class="bg-red-50 text-red-700 px-4 py-2 rounded-full text-sm font-semibold border border-red-100">
                Sección 4
            </span>
        </div>
    </div>

    <!-- Información importante -->
    <div class="mb-8 bg-gradient-to-r from-red-50 to-pink-50 border border-red-100 rounded-xl p-5">
        <div class="flex items-start">
            <i class="fas fa-exclamation-triangle text-red-500 text-lg mt-1 mr-3"></i>
            <div>
                <h4 class="font-medium text-red-800 mb-1">Información importante</h4>
                <p class="text-red-700 text-sm">
                    Esta información es vital en caso de emergencias médicas. Complete con precisión todos los campos requeridos.
                </p>
            </div>
        </div>
    </div>

    <!-- Tabla de información de salud -->
    <div class="mb-10">
        <h4 class="text-lg font-semibold text-gray-700 mb-6 flex items-center">
            <i class="fas fa-clipboard-check text-red-500 mr-2 text-sm"></i>
            Estado de Salud
        </h4>
        
        <div class="overflow-hidden rounded-2xl border border-gray-200 shadow-sm">
            <!-- Encabezados para desktop -->
            <div class="hidden md:grid md:grid-cols-3 bg-gradient-to-r from-red-50 to-red-100 border-b border-red-200">
                <div class="px-6 py-4">
                    <span class="text-sm font-semibold text-red-800 uppercase tracking-wide">Detalles de Salud</span>
                </div>
                <div class="px-6 py-4">
                    <span class="text-sm font-semibold text-red-800 uppercase tracking-wide">¿Aplica?</span>
                </div>
                <div class="px-6 py-4">
                    <span class="text-sm font-semibold text-red-800 uppercase tracking-wide">Especificaciones</span>
                </div>
            </div>

            <!-- Vacuna COVID-19 -->
            <div class="grid md:grid-cols-3 border-b border-gray-200">
                <!-- Pregunta - Mobile y Desktop -->
                <div class="p-6 bg-white border-r border-gray-200">
                    <div class="flex items-start">
                        <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center mr-3">
                            <i class="fas fa-syringe text-red-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-2">Vacuna COVID-19</h4>
                            <p class="text-gray-600 text-sm">¿Ha recibido la vacuna contra la COVID-19?</p>
                        </div>
                    </div>
                </div>

                <!-- Opciones SI/NO -->
                <div class="p-6 bg-white border-r border-gray-200">
                    <div class="flex space-x-8">
                        <div class="flex items-center p-3 border border-gray-300 rounded-xl hover:bg-red-50 transition-colors flex-1">
                            <input type="radio" id="covid_si" name="vacuna_covid" value="SI"
                                class="h-5 w-5 text-red-600 focus:ring-red-500">
                            <label for="covid_si" class="ml-3 text-gray-700 cursor-pointer flex-1">Sí</label>
                        </div>
                        <div class="flex items-center p-3 border border-gray-300 rounded-xl hover:bg-red-50 transition-colors flex-1">
                            <input type="radio" id="covid_no" name="vacuna_covid" value="NO"
                                class="h-5 w-5 text-red-600 focus:ring-red-500">
                            <label for="covid_no" class="ml-3 text-gray-700 cursor-pointer flex-1">No</label>
                        </div>
                    </div>
                </div>

                <!-- Especificaciones -->
                <div class="p-6 bg-white">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">1° Dosis</label>
                            <div class="relative">
                                <input type="text" 
                                       name="covid_dosis1"
                                       class="flatpickr-salud w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all bg-white"
                                       placeholder="Seleccione fecha">
                                <i class="fas fa-calendar text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">2° Dosis</label>
                            <div class="relative">
                                <input type="text" 
                                       name="covid_dosis2"
                                       class="flatpickr-salud w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all bg-white"
                                       placeholder="Seleccione fecha">
                                <i class="fas fa-calendar-check text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dolencia crónica -->
            <div class="grid md:grid-cols-3 border-b border-gray-200 bg-gray-50">
                <!-- Pregunta -->
                <div class="p-6 border-r border-gray-200">
                    <div class="flex items-start">
                        <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center mr-3">
                            <i class="fas fa-stethoscope text-orange-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-2">Dolencias Crónicas</h4>
                            <p class="text-gray-600 text-sm">¿Padece de alguna dolencia crónica? (asma, diabetes, hipertensión, etc.)</p>
                        </div>
                    </div>
                </div>

                <!-- Opciones SI/NO -->
                <div class="p-6 border-r border-gray-200">
                    <div class="flex space-x-8">
                        <div class="flex items-center p-3 border border-gray-300 rounded-xl hover:bg-orange-50 transition-colors flex-1">
                            <input type="radio" id="dolencia_si" name="dolencia_cronica" value="SI"
                                class="h-5 w-5 text-orange-600 focus:ring-orange-500">
                            <label for="dolencia_si" class="ml-3 text-gray-700 cursor-pointer flex-1">Sí</label>
                        </div>
                        <div class="flex items-center p-3 border border-gray-300 rounded-xl hover:bg-orange-50 transition-colors flex-1">
                            <input type="radio" id="dolencia_no" name="dolencia_cronica" value="NO"
                                class="h-5 w-5 text-orange-600 focus:ring-orange-500">
                            <label for="dolencia_no" class="ml-3 text-gray-700 cursor-pointer flex-1">No</label>
                        </div>
                    </div>
                </div>

                <!-- Especificaciones -->
                <div class="p-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Especificar dolencia</label>
                        <div class="relative">
                            <input type="text" name="dolencia_especificar"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all bg-white"
                                placeholder="Ej: Diabetes tipo 2, Hipertensión arterial">
                            <i class="fas fa-file-medical text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Discapacidad -->
            <div class="grid md:grid-cols-3">
                <!-- Pregunta -->
                <div class="p-6 bg-white border-r border-gray-200">
                    <div class="flex items-start">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                            <i class="fas fa-wheelchair text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-2">Discapacidad</h4>
                            <p class="text-gray-600 text-sm">¿Padece de algún tipo de discapacidad?</p>
                        </div>
                    </div>
                </div>

                <!-- Opciones SI/NO -->
                <div class="p-6 bg-white border-r border-gray-200">
                    <div class="flex space-x-8">
                        <div class="flex items-center p-3 border border-gray-300 rounded-xl hover:bg-blue-50 transition-colors flex-1">
                            <input type="radio" id="discapacidad_si" name="discapacidad" value="SI"
                                class="h-5 w-5 text-blue-600 focus:ring-blue-500">
                            <label for="discapacidad_si" class="ml-3 text-gray-700 cursor-pointer flex-1">Sí</label>
                        </div>
                        <div class="flex items-center p-3 border border-gray-300 rounded-xl hover:bg-blue-50 transition-colors flex-1">
                            <input type="radio" id="discapacidad_no" name="discapacidad" value="NO"
                                class="h-5 w-5 text-blue-600 focus:ring-blue-500">
                            <label for="discapacidad_no" class="ml-3 text-gray-700 cursor-pointer flex-1">No</label>
                        </div>
                    </div>
                </div>

                <!-- Especificaciones -->
                <div class="p-6 bg-white">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Especificar discapacidad</label>
                        <div class="relative">
                            <input type="text" name="discapacidad_especificar"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-white"
                                placeholder="Ej: Discapacidad visual, motora, auditiva">
                            <i class="fas fa-hearing text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tipo de sangre -->
    <div class="mb-10">
        <h4 class="text-lg font-semibold text-gray-700 mb-6 flex items-center">
            <i class="fas fa-tint text-red-500 mr-2 text-sm"></i>
            Tipo de Sangre
        </h4>
        
        <div class="bg-gradient-to-r from-gray-50 to-white border border-gray-200 rounded-2xl p-6">
            <p class="text-gray-600 mb-6 text-sm">Seleccione su tipo de sangre para emergencias médicas:</p>
            
            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-8 gap-3">
                @php
                    $tiposSangre = [
                        ['id' => 'sangre_a_pos', 'value' => 'A+', 'label' => 'A+'],
                        ['id' => 'sangre_a_neg', 'value' => 'A-', 'label' => 'A-'],
                        ['id' => 'sangre_b_pos', 'value' => 'B+', 'label' => 'B+'],
                        ['id' => 'sangre_b_neg', 'value' => 'B-', 'label' => 'B-'],
                        ['id' => 'sangre_ab_pos', 'value' => 'AB+', 'label' => 'AB+'],
                        ['id' => 'sangre_ab_neg', 'value' => 'AB-', 'label' => 'AB-'],
                        ['id' => 'sangre_o_pos', 'value' => 'O+', 'label' => 'O+'],
                        ['id' => 'sangre_o_neg', 'value' => 'O-', 'label' => 'O-'],
                    ];
                @endphp
                
                @foreach ($tiposSangre as $tipo)
                    <div class="flex items-center justify-center p-4 border border-gray-300 rounded-xl hover:bg-red-50 transition-colors cursor-pointer grupo-sangre">
                        <input type="radio" id="{{ $tipo['id'] }}" name="tipo_sangre" value="{{ $tipo['value'] }}"
                            class="h-5 w-5 text-red-600 focus:ring-red-500 hidden">
                        <label for="{{ $tipo['id'] }}" class="flex items-center cursor-pointer">
                            <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center mr-2 grupo-sangre-icono">
                                <span class="font-bold text-red-600">{{ $tipo['label'] }}</span>
                            </div>
                            <span class="text-gray-700 font-medium">{{ $tipo['label'] }}</span>
                        </label>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-info-circle text-red-500 mr-2"></i>
                    <span>Esta información es crucial en caso de emergencias médicas que requieran transfusión de sangre</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Contactos de emergencia -->
    <div id="contactos-emergencia-container">
        <h4 class="text-lg font-semibold text-gray-700 mb-6 flex items-center">
            <i class="fas fa-phone-alt text-red-500 mr-2 text-sm"></i>
            Contactos de Emergencia
        </h4>
        
        <div class="bg-gradient-to-r from-red-50 to-red-100 border border-red-200 rounded-2xl p-6 mb-4">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl mr-3"></i>
                <div>
                    <h5 class="font-bold text-red-800">¡IMPORTANTE!</h5>
                    <p class="text-red-700 text-sm">Indique datos de familiar en caso de accidente y/o emergencia</p>
                </div>
            </div>
        </div>

        <!-- Tabla de contactos de emergencia -->
        <div class="overflow-hidden rounded-2xl border border-gray-200 shadow-sm mb-4" id="contactos-table">
            <!-- Encabezados para desktop -->
            <div class="hidden md:grid md:grid-cols-3 bg-gradient-to-r from-red-50 to-red-100 border-b border-red-200">
                <div class="px-6 py-4">
                    <span class="text-sm font-semibold text-red-800 uppercase tracking-wide">Apellidos y Nombres</span>
                </div>
                <div class="px-6 py-4">
                    <span class="text-sm font-semibold text-red-800 uppercase tracking-wide">Parentesco</span>
                </div>
                <div class="px-6 py-4">
                    <span class="text-sm font-semibold text-red-800 uppercase tracking-wide">Dirección y Teléfono</span>
                </div>
            </div>

            <!-- Contenedor para contactos dinámicos -->
            <div id="contactos-dynamic-container">
                <!-- Contacto 1 -->
                <div class="grid md:grid-cols-3 border-b border-gray-200" data-contacto-index="1">
                    <!-- Nombres -->
                    <div class="p-6 bg-white border-r border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contacto de Emergencia #1</label>
                        <div class="relative">
                            <input type="text" name="emergencia1_nombres"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all bg-white"
                                placeholder="Nombre completo del contacto">
                            <i class="fas fa-user text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                        </div>
                    </div>

                    <!-- Parentesco -->
                    <div class="p-6 bg-white border-r border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Parentesco</label>
                        <div class="relative">
                            <input type="text" name="emergencia1_parentesco"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all bg-white"
                                placeholder="Ej: Padre, Madre, Hermano">
                            <i class="fas fa-users text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                        </div>
                    </div>

                    <!-- Dirección y Teléfono -->
                    <div class="p-6 bg-white">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contacto</label>
                        <div class="relative">
                            <textarea name="emergencia1_direccion" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all bg-white resize-none"
                                placeholder="Dirección completa y número de teléfono"></textarea>
                            <i class="fas fa-map-marker-alt text-gray-400 absolute right-3 top-4"></i>
                        </div>
                    </div>
                </div>

                <!-- Contacto 2 -->
                <div class="grid md:grid-cols-3" data-contacto-index="2">
                    <!-- Nombres -->
                    <div class="p-6 bg-gray-50 border-r border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contacto de Emergencia #2</label>
                        <div class="relative">
                            <input type="text" name="emergencia2_nombres"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all bg-white"
                                placeholder="Nombre completo del contacto">
                            <i class="fas fa-user text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                        </div>
                    </div>

                    <!-- Parentesco -->
                    <div class="p-6 bg-gray-50 border-r border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Parentesco</label>
                        <div class="relative">
                            <input type="text" name="emergencia2_parentesco"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all bg-white"
                                placeholder="Ej: Esposo/a, Hijo/a">
                            <i class="fas fa-users text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                        </div>
                    </div>

                    <!-- Dirección y Teléfono -->
                    <div class="p-6 bg-gray-50">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contacto</label>
                        <div class="relative">
                            <textarea name="emergencia2_direccion" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all bg-white resize-none"
                                placeholder="Dirección completa y número de teléfono"></textarea>
                            <i class="fas fa-map-marker-alt text-gray-400 absolute right-3 top-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón para agregar más contactos -->
        <div class="mt-4 flex justify-end">
            <button type="button" id="add-contacto-btn" 
                class="inline-flex items-center px-4 py-2 text-sm bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl hover:from-red-600 hover:to-red-700 transition-all shadow-md hover:shadow-lg">
                <i class="fas fa-plus mr-2"></i>
                <span>Agregar otro contacto</span>
            </button>
        </div>
    </div>

    <!-- Nota final -->
    <div class="mt-8 bg-gradient-to-r from-gray-50 to-white border border-gray-200 rounded-xl p-5">
        <div class="flex items-start">
            <div class="bg-red-100 rounded-lg p-3 mr-4">
                <i class="fas fa-shield-alt text-red-600 text-lg"></i>
            </div>
            <div>
                <h4 class="font-medium text-gray-800 mb-1">Seguridad y confidencialidad</h4>
                <p class="text-gray-600 text-sm">
                    Su información de salud es tratada con estricta confidencialidad y solo será utilizada 
                    en caso de emergencias médicas durante su permanencia en la empresa.
                </p>
            </div>
        </div>
    </div>

    <!-- Indicador de progreso -->
    <div class="mt-6 pt-6 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                <span class="text-sm text-gray-600">Información de salud completa</span>
            </div>
            <div class="flex items-center">
                <div class="w-32 bg-gray-200 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full" style="width: 0%" id="salud-progress"></div>
                </div>
                <span class="ml-3 text-sm font-medium text-gray-700" id="salud-percentage">0%</span>
            </div>
        </div>
    </div>
</div>

<script>
    // Inicializar Flatpickr para fechas de salud
    document.addEventListener('DOMContentLoaded', function() {
        // Configuración para Flatpickr salud
        const flatpickrSaludOptions = {
            locale: "es",
            dateFormat: "Y-m-d",
            altFormat: "d/m/Y",
            altInput: true,
            altInputClass: "flatpickr-alt-input",
            theme: "airbnb",
            maxDate: "today",
            disableMobile: false,
            allowInput: true,
            clickOpens: true,
            onValueUpdate: function(selectedDates, dateStr, instance) {
                calculateSaludProgress();
            }
        };

        // Inicializar Flatpickr para fechas de salud
        document.querySelectorAll('.flatpickr-salud').forEach(function(element) {
            flatpickr(element, flatpickrSaludOptions);
        });

        // Mejorar interacción de tipos de sangre
        document.querySelectorAll('.grupo-sangre').forEach(function(element) {
            element.addEventListener('click', function(e) {
                // Si el click no fue directamente en el radio button
                if (!e.target.classList.contains('hidden')) {
                    const radio = this.querySelector('input[type="radio"]');
                    if (radio) {
                        radio.checked = true;
                        
                        // Resetear todos los iconos
                        document.querySelectorAll('.grupo-sangre-icono').forEach(function(icono) {
                            icono.classList.remove('bg-red-600');
                            icono.classList.add('bg-red-100');
                            icono.querySelector('span').classList.remove('text-white');
                            icono.querySelector('span').classList.add('text-red-600');
                        });
                        
                        // Resaltar el seleccionado
                        const icono = this.querySelector('.grupo-sangre-icono');
                        if (icono) {
                            icono.classList.remove('bg-red-100');
                            icono.classList.add('bg-red-600');
                            icono.querySelector('span').classList.remove('text-red-600');
                            icono.querySelector('span').classList.add('text-white');
                        }
                        
                        calculateSaludProgress();
                    }
                }
            });
        });

        // Manejar selección de radio buttons de grupo sanguíneo
        document.querySelectorAll('input[name="tipo_sangre"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                // Resetear todos los iconos
                document.querySelectorAll('.grupo-sangre-icono').forEach(function(icono) {
                    icono.classList.remove('bg-red-600');
                    icono.classList.add('bg-red-100');
                    icono.querySelector('span').classList.remove('text-white');
                    icono.querySelector('span').classList.add('text-red-600');
                });
                
                // Resaltar el seleccionado
                const label = document.querySelector(`label[for="${this.id}"]`);
                if (label) {
                    const icono = label.querySelector('.grupo-sangre-icono');
                    if (icono) {
                        icono.classList.remove('bg-red-100');
                        icono.classList.add('bg-red-600');
                        icono.querySelector('span').classList.remove('text-red-600');
                        icono.querySelector('span').classList.add('text-white');
                    }
                }
                
                calculateSaludProgress();
            });
        });

        // Variables para contactos de emergencia
        let currentContactoCount = 2;
        const maxContactos = 5;

        // Función para agregar contacto de emergencia
        document.getElementById('add-contacto-btn').addEventListener('click', function() {
            if (currentContactoCount >= maxContactos) {
                alert(`Máximo ${maxContactos} contactos de emergencia permitidos`);
                return;
            }

            currentContactoCount++;
            const contactoIndex = currentContactoCount;
            const isEven = contactoIndex % 2 === 0;
            const tieneBordeInferior = contactoIndex < maxContactos;
            
            // Crear nueva fila de contacto
            const contactoRow = document.createElement('div');
            contactoRow.className = `grid md:grid-cols-3 ${tieneBordeInferior ? 'border-b border-gray-200' : ''}`;
            contactoRow.setAttribute('data-contacto-index', contactoIndex);
            contactoRow.innerHTML = `
                <!-- Nombres -->
                <div class="p-6 ${isEven ? 'bg-gray-50' : 'bg-white'} border-r border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contacto de Emergencia #${contactoIndex}</label>
                    <div class="relative">
                        <input type="text" name="emergencia${contactoIndex}_nombres"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all ${isEven ? 'bg-white' : 'bg-white'}"
                            placeholder="Nombre completo del contacto">
                        <i class="fas fa-user text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                    </div>
                </div>

                <!-- Parentesco -->
                <div class="p-6 ${isEven ? 'bg-gray-50' : 'bg-white'} border-r border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Parentesco</label>
                    <div class="relative">
                        <input type="text" name="emergencia${contactoIndex}_parentesco"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all ${isEven ? 'bg-white' : 'bg-white'}"
                            placeholder="Ej: Amigo/a, Vecino/a">
                        <i class="fas fa-users text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                    </div>
                </div>

                <!-- Dirección y Teléfono -->
                <div class="p-6 ${isEven ? 'bg-gray-50' : 'bg-white'}">
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contacto</label>
                        <textarea name="emergencia${contactoIndex}_direccion" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all ${isEven ? 'bg-white' : 'bg-white'} resize-none"
                            placeholder="Dirección completa y número de teléfono"></textarea>
                        <i class="fas fa-map-marker-alt text-gray-400 absolute right-3 top-4"></i>
                    </div>
                    <div class="mt-2 text-right">
                        <button type="button" class="remove-contacto-btn text-sm text-red-500 hover:text-red-700" data-index="${contactoIndex}">
                            <i class="fas fa-trash-alt mr-1"></i> Eliminar
                        </button>
                    </div>
                </div>
            `;

            // Agregar al DOM dentro del contenedor dinámico
            const contactosContainer = document.getElementById('contactos-dynamic-container');
            contactosContainer.appendChild(contactoRow);

            // Quitar borde inferior del contacto anterior si existe
            if (contactoIndex > 2) {
                const anteriorRow = document.querySelector(`[data-contacto-index="${contactoIndex - 1}"]`);
                if (anteriorRow) {
                    anteriorRow.classList.remove('border-b');
                    anteriorRow.classList.add('border-b');
                }
            }

            // Agregar event listeners a los nuevos campos
            setupContactoEventListeners(contactoIndex);

            // Agregar listener para eliminar
            contactoRow.querySelector('.remove-contacto-btn').addEventListener('click', function() {
                removeContacto(contactoIndex);
            });

            // Actualizar botón si es necesario
            if (currentContactoCount >= maxContactos) {
                document.getElementById('add-contacto-btn').disabled = true;
                document.getElementById('add-contacto-btn').classList.add('opacity-50', 'cursor-not-allowed');
                document.getElementById('add-contacto-btn').innerHTML = '<i class="fas fa-ban mr-2"></i><span>Límite alcanzado</span>';
            }

            // Animación suave
            contactoRow.style.opacity = '0';
            contactoRow.style.transform = 'translateY(10px)';
            contactosContainer.appendChild(contactoRow);
            
            setTimeout(() => {
                contactoRow.style.transition = 'all 0.3s ease';
                contactoRow.style.opacity = '1';
                contactoRow.style.transform = 'translateY(0)';
            }, 10);

            calculateSaludProgress();
        });

        // Función para eliminar contacto
        function removeContacto(index) {
            if (index <= 2) {
                alert('No puede eliminar los primeros 2 contactos obligatorios.');
                return;
            }

            if (confirm('¿Está seguro de eliminar este contacto de emergencia?')) {
                // Buscar y eliminar el contacto
                const contactoRow = document.querySelector(`[data-contacto-index="${index}"]`);
                if (contactoRow) {
                    // Animación de eliminación
                    contactoRow.style.opacity = '0';
                    contactoRow.style.transform = 'translateY(-10px)';
                    contactoRow.style.height = contactoRow.offsetHeight + 'px';
                    
                    setTimeout(() => {
                        contactoRow.remove();
                        currentContactoCount--;
                        
                        // Reorganizar bordes
                        reorganizarBordesContactos();
                        
                        // Habilitar botón de agregar si estaba deshabilitado
                        if (currentContactoCount < maxContactos) {
                            document.getElementById('add-contacto-btn').disabled = false;
                            document.getElementById('add-contacto-btn').classList.remove('opacity-50', 'cursor-not-allowed');
                            document.getElementById('add-contacto-btn').innerHTML = '<i class="fas fa-plus mr-2"></i><span>Agregar otro contacto</span>';
                        }
                        
                        calculateSaludProgress();
                    }, 300);
                }
            }
        }

        // Función para reorganizar bordes después de eliminar
        function reorganizarBordesContactos() {
            const contactos = document.querySelectorAll('[data-contacto-index]');
            contactos.forEach((contacto, index) => {
                contacto.classList.remove('border-b');
                if (index < contactos.length - 1) {
                    contacto.classList.add('border-b');
                }
            });
        }

        // Agregar event listeners a los botones de eliminar existentes
        document.querySelectorAll('.remove-contacto-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const index = this.getAttribute('data-index');
                removeContacto(parseInt(index));
            });
        });

        // Función para calcular progreso de sección de salud
        function calculateSaludProgress() {
            let completed = 0;
            let total = 0;

            // Verificar vacuna COVID
            const vacunaCovid = document.querySelector('input[name="vacuna_covid"]:checked');
            if (vacunaCovid) {
                completed++;
                total++;
                
                // Si es SI, verificar fechas
                if (vacunaCovid.value === 'SI') {
                    const dosis1 = document.querySelector('input[name="covid_dosis1"]').value;
                    const dosis2 = document.querySelector('input[name="covid_dosis2"]').value;
                    
                    if (dosis1 && dosis1.trim() !== '') {
                        completed += 0.5;
                    }
                    total += 0.5;
                    
                    if (dosis2 && dosis2.trim() !== '') {
                        completed += 0.5;
                    }
                    total += 0.5;
                }
            } else {
                total++;
            }

            // Verificar dolencia crónica
            const dolencia = document.querySelector('input[name="dolencia_cronica"]:checked');
            if (dolencia) {
                completed++;
                total++;
                
                // Si es SI, verificar especificación
                if (dolencia.value === 'SI') {
                    const especificar = document.querySelector('input[name="dolencia_especificar"]').value;
                    if (especificar && especificar.trim() !== '') {
                        completed += 0.5;
                    }
                    total += 0.5;
                }
            } else {
                total++;
            }

            // Verificar discapacidad
            const discapacidad = document.querySelector('input[name="discapacidad"]:checked');
            if (discapacidad) {
                completed++;
                total++;
                
                // Si es SI, verificar especificación
                if (discapacidad.value === 'SI') {
                    const especificar = document.querySelector('input[name="discapacidad_especificar"]').value;
                    if (especificar && especificar.trim() !== '') {
                        completed += 0.5;
                    }
                    total += 0.5;
                }
            } else {
                total++;
            }

            // Verificar tipo de sangre
            const tipoSangre = document.querySelector('input[name="tipo_sangre"]:checked');
            if (tipoSangre) {
                completed += 2; // Peso extra por importancia
                total += 2;
            } else {
                total += 2;
            }

            // Verificar contactos de emergencia
            for (let i = 1; i <= currentContactoCount; i++) {
                const nombre = document.querySelector(`input[name="emergencia${i}_nombres"]`)?.value;
                const parentesco = document.querySelector(`input[name="emergencia${i}_parentesco"]`)?.value;
                const direccion = document.querySelector(`textarea[name="emergencia${i}_direccion"]`)?.value;
                
                if (nombre && nombre.trim() !== '') {
                    completed += 0.5;
                }
                total += 0.5;
                
                if (parentesco && parentesco.trim() !== '') {
                    completed += 0.3;
                }
                total += 0.3;
                
                if (direccion && direccion.trim() !== '') {
                    completed += 0.2;
                }
                total += 0.2;
            }

            // Calcular porcentaje
            const percentage = total > 0 ? Math.round((completed / total) * 100) : 0;
            const displayPercentage = Math.min(percentage, 100);
            
            document.getElementById('salud-percentage').textContent = `${displayPercentage}%`;
            document.getElementById('salud-progress').style.width = `${displayPercentage}%`;
        }

        // Agregar event listeners a todos los campos
        function setupEventListeners() {
            // Radio buttons
            document.querySelectorAll('input[type="radio"]').forEach(field => {
                field.addEventListener('change', calculateSaludProgress);
            });

            // Campos de texto
            document.querySelectorAll('input[type="text"], textarea, .flatpickr-salud').forEach(field => {
                field.addEventListener('input', calculateSaludProgress);
                field.addEventListener('change', calculateSaludProgress);
            });
        }

        // Inicializar event listeners para contactos existentes
        function setupContactoEventListeners(index) {
            const fields = [
                `emergencia${index}_nombres`,
                `emergencia${index}_parentesco`,
                `emergencia${index}_direccion`
            ];
            
            fields.forEach(fieldName => {
                const field = document.querySelector(`[name="${fieldName}"]`);
                if (field) {
                    field.addEventListener('input', calculateSaludProgress);
                    field.addEventListener('change', calculateSaludProgress);
                }
            });
        }

        // Inicializar event listeners
        setupEventListeners();
        for (let i = 1; i <= 2; i++) {
            setupContactoEventListeners(i);
        }
        
        // Calcular progreso inicial
        calculateSaludProgress();
    });
</script>

<style>
    /* Estilos para selección de tipo de sangre */
    .grupo-sangre {
        transition: all 0.2s ease;
    }
    
    .grupo-sangre:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .grupo-sangre-icono {
        transition: all 0.2s ease;
    }
    
    /* Estilos para animaciones de contactos */
    [data-contacto-index] {
        transition: all 0.3s ease;
    }
    
    /* Estilos para campos de emergencia */
    textarea {
        min-height: 80px;
    }
    
    .remove-contacto-btn {
        transition: all 0.2s ease;
    }
    
    .remove-contacto-btn:hover {
        transform: scale(1.05);
    }
    
    @media (max-width: 768px) {
        .grupo-sangre {
            padding: 12px 8px;
        }
        
        .grupo-sangre-icono {
            width: 36px;
            height: 36px;
            margin-right: 6px;
        }
        
        /* En móvil, mostrar contactos en columnas */
        #contactos-table .grid {
            grid-template-columns: 1fr !important;
            border-right: none !important;
        }
        
        #contactos-table .border-r {
            border-right: none !important;
            border-bottom: 1px solid #e5e7eb;
        }
        
        #contactos-table .p-6:last-child {
            border-bottom: none;
        }
    }
</style>