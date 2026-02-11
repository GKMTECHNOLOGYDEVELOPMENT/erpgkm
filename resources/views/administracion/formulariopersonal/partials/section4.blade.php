<!-- Sección 4: Información de Salud -->
<div class="form-section bg-white rounded-2xl shadow-lg p-6 md:p-8 mt-8 border border-gray-200">
    <!-- Encabezado de sección -->
    <div class="section-header mb-8 pb-6 border-b border-gray-200">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center">
                <div
                    class="bg-gradient-to-r from-red-500 to-red-600 w-12 h-12 rounded-xl flex items-center justify-center mr-4 shadow-md">
                    <i class="fas fa-heartbeat text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl md:text-2xl font-bold text-gray-800">Información de Salud</h3>
                    <p class="text-gray-500 mt-1 text-sm md:text-base">Complete sus datos de salud importantes para
                        emergencias</p>
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
                    Esta información es vital en caso de emergencias médicas. Complete con precisión todos los campos
                    requeridos.
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

            <!-- Vacuna COVID-19 (3 DOSIS) -->
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
                        <div
                            class="flex items-center p-3 border border-gray-300 rounded-xl hover:bg-red-50 transition-colors flex-1">
                            <input type="radio" id="covid_si" name="vacuna_covid" value="SI"
                                class="h-5 w-5 text-red-600 focus:ring-red-500">
                            <label for="covid_si" class="ml-3 text-gray-700 cursor-pointer flex-1">Sí</label>
                        </div>
                        <div
                            class="flex items-center p-3 border border-gray-300 rounded-xl hover:bg-red-50 transition-colors flex-1">
                            <input type="radio" id="covid_no" name="vacuna_covid" value="NO"
                                class="h-5 w-5 text-red-600 focus:ring-red-500">
                            <label for="covid_no" class="ml-3 text-gray-700 cursor-pointer flex-1">No</label>
                        </div>
                    </div>
                </div>

                <!-- Especificaciones - 3 DOSIS -->
                <div class="p-6 bg-white">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">1° Dosis</label>
                            <div class="relative">
                                <input type="text" name="covid_dosis1"
                                    class="flatpickr-salud w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all bg-white"
                                    placeholder="Seleccione fecha">
                                <i
                                    class="fas fa-calendar text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">2° Dosis</label>
                            <div class="relative">
                                <input type="text" name="covid_dosis2"
                                    class="flatpickr-salud w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all bg-white"
                                    placeholder="Seleccione fecha">
                                <i
                                    class="fas fa-calendar-check text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">3° Dosis (Refuerzo)</label>
                            <div class="relative">
                                <input type="text" name="covid_dosis3"
                                    class="flatpickr-salud w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all bg-white"
                                    placeholder="Seleccione fecha">
                                <i
                                    class="fas fa-syringe text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- OPERACIONES - NUEVO CAMPO -->
            <div class="grid md:grid-cols-3 border-b border-gray-200">
                <!-- Pregunta -->
                <div class="p-6 border-r border-gray-200">
                    <div class="flex items-start">
                        <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center mr-3">
                            <i class="fas fa-procedures text-purple-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-2">Operaciones Quirúrgicas</h4>
                            <p class="text-gray-600 text-sm">¿Ha tenido alguna operación?</p>
                        </div>
                    </div>
                </div>

                <!-- Opciones SI/NO -->
                <div class="p-6 border-r border-gray-200">
                    <div class="flex space-x-8">
                        <div
                            class="flex items-center p-3 border border-gray-300 rounded-xl hover:bg-purple-50 transition-colors flex-1">
                            <input type="radio" id="operacion_si" name="tiene_operacion" value="SI"
                                class="h-5 w-5 text-purple-600 focus:ring-purple-500">
                            <label for="operacion_si" class="ml-3 text-gray-700 cursor-pointer flex-1">Sí</label>
                        </div>
                        <div
                            class="flex items-center p-3 border border-gray-300 rounded-xl hover:bg-purple-50 transition-colors flex-1">
                            <input type="radio" id="operacion_no" name="tiene_operacion" value="NO"
                                class="h-5 w-5 text-purple-600 focus:ring-purple-500">
                            <label for="operacion_no" class="ml-3 text-gray-700 cursor-pointer flex-1">No</label>
                        </div>
                    </div>
                </div>

                <!-- Especificaciones -->
                <div class="p-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Especificar operación(es)</label>
                        <div class="relative">
                            <input type="text" name="operacion_especificar"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all bg-white"
                                placeholder="Ej: Apendicectomía, Cesárea, Amígdalas">
                            <i
                                class="fas fa-notes-medical text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
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
                            <p class="text-gray-600 text-sm">¿Padece de alguna dolencia crónica? (asma, diabetes,
                                hipertensión, etc.)</p>
                        </div>
                    </div>
                </div>

                <!-- Opciones SI/NO -->
                <div class="p-6 border-r border-gray-200">
                    <div class="flex space-x-8">
                        <div
                            class="flex items-center p-3 border border-gray-300 rounded-xl hover:bg-orange-50 transition-colors flex-1">
                            <input type="radio" id="dolencia_si" name="dolencia_cronica" value="SI"
                                class="h-5 w-5 text-orange-600 focus:ring-orange-500">
                            <label for="dolencia_si" class="ml-3 text-gray-700 cursor-pointer flex-1">Sí</label>
                        </div>
                        <div
                            class="flex items-center p-3 border border-gray-300 rounded-xl hover:bg-orange-50 transition-colors flex-1">
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
                            <i
                                class="fas fa-file-medical text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
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
                        <div
                            class="flex items-center p-3 border border-gray-300 rounded-xl hover:bg-blue-50 transition-colors flex-1">
                            <input type="radio" id="discapacidad_si" name="discapacidad" value="SI"
                                class="h-5 w-5 text-blue-600 focus:ring-blue-500">
                            <label for="discapacidad_si" class="ml-3 text-gray-700 cursor-pointer flex-1">Sí</label>
                        </div>
                        <div
                            class="flex items-center p-3 border border-gray-300 rounded-xl hover:bg-blue-50 transition-colors flex-1">
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
                            <i
                                class="fas fa-hearing text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
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
                    <div
                        class="flex items-center justify-center p-4 border border-gray-300 rounded-xl hover:bg-red-50 transition-colors cursor-pointer grupo-sangre">
                        <input type="radio" id="{{ $tipo['id'] }}" name="tipo_sangre"
                            value="{{ $tipo['value'] }}" class="h-5 w-5 text-red-600 focus:ring-red-500 hidden">
                        <label for="{{ $tipo['id'] }}" class="flex items-center cursor-pointer">
                            <div
                                class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center mr-2 grupo-sangre-icono">
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
                    <span>Esta información es crucial en caso de emergencias médicas que requieran transfusión de
                        sangre</span>
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
                    <span class="text-sm font-semibold text-red-800 uppercase tracking-wide">Dirección y
                        Teléfono</span>
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
                            <i
                                class="fas fa-user text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                        </div>
                    </div>

                    <!-- Parentesco -->
                    <div class="p-6 bg-white border-r border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Parentesco</label>
                        <div class="relative">
                            <input type="text" name="emergencia1_parentesco"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all bg-white"
                                placeholder="Ej: Padre, Madre, Hermano">
                            <i
                                class="fas fa-users text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
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
                            <i
                                class="fas fa-user text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                        </div>
                    </div>

                    <!-- Parentesco -->
                    <div class="p-6 bg-gray-50 border-r border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Parentesco</label>
                        <div class="relative">
                            <input type="text" name="emergencia2_parentesco"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all bg-white"
                                placeholder="Ej: Esposo/a, Hijo/a">
                            <i
                                class="fas fa-users text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
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
        if (typeof toastr !== 'undefined') {
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: 3000,
                extendedTimeOut: 1000,
                showEasing: "swing",
                hideEasing: "linear",
                showMethod: "fadeIn",
                hideMethod: "fadeOut",
                showDuration: 300,
                hideDuration: 300,
                toastClass: '',
                iconClass: 'toast-success',
                preventDuplicates: true
            };
        }

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

        // Inicializar Flatpickr para fechas de salud y guardar instancia
        document.querySelectorAll('.flatpickr-salud').forEach(function(element) {
            element._flatpickr = flatpickr(element, flatpickrSaludOptions);
        });

        // ========== FUNCIÓN PARA MANEJAR CAMPOS CONDICIONALES DE SALUD ==========
        function handleCondicionalSalud(radioSeleccionado) {
            const nombreRadio = radioSeleccionado.getAttribute('name');
            const valor = radioSeleccionado.value;
            
            // Mapeo de campos condicionales según el radio button
            const configuracionCampos = {
                'vacuna_covid': {
                    campos: [
                        'input[name="covid_dosis1"]',
                        'input[name="covid_dosis2"]',
                        'input[name="covid_dosis3"]'
                    ],
                    requerido: true,
                    placeholder: 'Seleccione fecha'
                },
                'tiene_operacion': {
                    campos: [
                        'input[name="operacion_especificar"]'
                    ],
                    requerido: true,
                    placeholder: 'Ej: Apendicectomía, Cesárea, Amígdalas'
                },
                'dolencia_cronica': {
                    campos: [
                        'input[name="dolencia_especificar"]'
                    ],
                    requerido: true,
                    placeholder: 'Ej: Diabetes tipo 2, Hipertensión arterial'
                },
                'discapacidad': {
                    campos: [
                        'input[name="discapacidad_especificar"]'
                    ],
                    requerido: true,
                    placeholder: 'Ej: Discapacidad visual, motora, auditiva'
                }
            };

            const config = configuracionCampos[nombreRadio];
            if (!config) return;

            if (valor === 'SI') {
                // HABILITAR campos y hacerlos REQUERIDOS
                config.campos.forEach(selector => {
                    const campo = document.querySelector(selector);
                    if (campo) {
                        campo.disabled = false;
                        campo.readOnly = false;
                        campo.required = config.requerido;
                        
                        campo.classList.remove('bg-gray-100', 'cursor-not-allowed', 'text-gray-400', 'opacity-60');
                        campo.classList.add('bg-white');
                        campo.placeholder = config.placeholder;
                        
                        if (campo.classList.contains('flatpickr-salud') && campo._flatpickr) {
                            if (campo._flatpickr.altInput) {
                                campo._flatpickr.altInput.disabled = false;
                                campo._flatpickr.altInput.classList.remove('bg-gray-100', 'cursor-not-allowed', 'text-gray-400');
                                campo._flatpickr.altInput.placeholder = 'Seleccione fecha';
                                campo._flatpickr.altInput.value = '';
                            }
                            campo._flatpickr.input.disabled = false;
                            campo._flatpickr.altInput.readOnly = false;
                        }
                    }
                });
            } else if (valor === 'NO') {
                // DESHABILITAR campos y LIMPIAR valores
                config.campos.forEach(selector => {
                    const campo = document.querySelector(selector);
                    if (campo) {
                        campo.disabled = true;
                        campo.readOnly = true;
                        campo.required = false;
                        campo.value = '';
                        
                        campo.classList.add('bg-gray-100', 'cursor-not-allowed', 'text-gray-400', 'opacity-60');
                        campo.classList.remove('bg-white');
                        campo.placeholder = 'No aplica';
                        
                        if (campo.classList.contains('flatpickr-salud') && campo._flatpickr) {
                            if (campo._flatpickr.altInput) {
                                campo._flatpickr.altInput.disabled = true;
                                campo._flatpickr.altInput.value = '';
                                campo._flatpickr.altInput.classList.add('bg-gray-100', 'cursor-not-allowed', 'text-gray-400');
                                campo._flatpickr.altInput.placeholder = 'No aplica';
                                campo._flatpickr.altInput.readOnly = true;
                            }
                            campo._flatpickr.input.disabled = true;
                        }
                    }
                });
            }
            
            calculateSaludProgress();
        }

        // ========== EVENT LISTENERS PARA RADIOS CONDICIONALES ==========
        document.querySelectorAll('input[name="vacuna_covid"], input[name="tiene_operacion"], input[name="dolencia_cronica"], input[name="discapacidad"]').forEach(radio => {
            radio.addEventListener('change', function() {
                handleCondicionalSalud(this);
            });
            
            // Verificar estado inicial
            if (radio.checked) {
                setTimeout(() => handleCondicionalSalud(radio), 100);
            }
        });

        // ========== FUNCIÓN PARA CALCULAR PROGRESO ==========
        function calculateSaludProgress() {
            let completed = 0;
            let total = 0;

            // VACUNA COVID
            const vacunaCovid = document.querySelector('input[name="vacuna_covid"]:checked');
            total += 1;
            if (vacunaCovid) {
                completed += 1;
                
                if (vacunaCovid.value === 'SI') {
                    const dosis1 = document.querySelector('input[name="covid_dosis1"]');
                    const dosis2 = document.querySelector('input[name="covid_dosis2"]');
                    const dosis3 = document.querySelector('input[name="covid_dosis3"]');
                    
                    total += 3;
                    
                    if (dosis1 && !dosis1.disabled && dosis1.value && dosis1.value.trim() !== '') completed += 1;
                    if (dosis2 && !dosis2.disabled && dosis2.value && dosis2.value.trim() !== '') completed += 1;
                    if (dosis3 && !dosis3.disabled && dosis3.value && dosis3.value.trim() !== '') completed += 1;
                }
            }

            // OPERACIONES
            const operacion = document.querySelector('input[name="tiene_operacion"]:checked');
            total += 1;
            if (operacion) {
                completed += 1;
                
                if (operacion.value === 'SI') {
                    const especificar = document.querySelector('input[name="operacion_especificar"]');
                    total += 1;
                    if (especificar && !especificar.disabled && especificar.value && especificar.value.trim() !== '') {
                        completed += 1;
                    }
                }
            }

            // DOLENCIA CRÓNICA
            const dolencia = document.querySelector('input[name="dolencia_cronica"]:checked');
            total += 1;
            if (dolencia) {
                completed += 1;
                
                if (dolencia.value === 'SI') {
                    const especificar = document.querySelector('input[name="dolencia_especificar"]');
                    total += 1;
                    if (especificar && !especificar.disabled && especificar.value && especificar.value.trim() !== '') {
                        completed += 1;
                    }
                }
            }

            // DISCAPACIDAD
            const discapacidad = document.querySelector('input[name="discapacidad"]:checked');
            total += 1;
            if (discapacidad) {
                completed += 1;
                
                if (discapacidad.value === 'SI') {
                    const especificar = document.querySelector('input[name="discapacidad_especificar"]');
                    total += 1;
                    if (especificar && !especificar.disabled && especificar.value && especificar.value.trim() !== '') {
                        completed += 1;
                    }
                }
            }

            // TIPO DE SANGRE
            const tipoSangre = document.querySelector('input[name="tipo_sangre"]:checked');
            total += 2;
            if (tipoSangre) {
                completed += 2;
            }

            // CONTACTOS DE EMERGENCIA
            const contactosVisibles = document.querySelectorAll('[data-contacto-index]');
            
            contactosVisibles.forEach((contacto) => {
                const index = contacto.getAttribute('data-contacto-index');
                const nombre = document.querySelector(`input[name="emergencia${index}_nombres"]`);
                const parentesco = document.querySelector(`input[name="emergencia${index}_parentesco"]`);
                const direccion = document.querySelector(`textarea[name="emergencia${index}_direccion"]`);
                
                total += 1;
                
                if (nombre && !nombre.disabled && nombre.value && nombre.value.trim() !== '') {
                    completed += 0.5;
                }
                if (parentesco && !parentesco.disabled && parentesco.value && parentesco.value.trim() !== '') {
                    completed += 0.3;
                }
                if (direccion && !direccion.disabled && direccion.value && direccion.value.trim() !== '') {
                    completed += 0.2;
                }
            });

            const percentage = total > 0 ? Math.round((completed / total) * 100) : 0;
            const displayPercentage = Math.min(percentage, 100);

            const percentageElement = document.getElementById('salud-percentage');
            const progressElement = document.getElementById('salud-progress');

            if (percentageElement) {
                percentageElement.textContent = `${displayPercentage}%`;
            }
            if (progressElement) {
                progressElement.style.width = `${displayPercentage}%`;
                
                progressElement.classList.remove('bg-green-500', 'bg-green-400', 'bg-yellow-500', 'bg-yellow-400', 'bg-red-500', 'bg-gray-300');
                if (displayPercentage >= 100) {
                    progressElement.classList.add('bg-green-500');
                } else if (displayPercentage >= 80) {
                    progressElement.classList.add('bg-green-400');
                } else if (displayPercentage >= 60) {
                    progressElement.classList.add('bg-yellow-500');
                } else if (displayPercentage >= 40) {
                    progressElement.classList.add('bg-yellow-400');
                } else if (displayPercentage > 0) {
                    progressElement.classList.add('bg-red-500');
                } else {
                    progressElement.classList.add('bg-gray-300');
                }
            }
            
            return displayPercentage;
        }

        // ========== TIPO DE SANGRE ==========
        document.querySelectorAll('.grupo-sangre').forEach(function(element) {
            element.addEventListener('click', function(e) {
                if (!e.target.classList.contains('hidden')) {
                    const radio = this.querySelector('input[type="radio"]');
                    if (radio) {
                        radio.checked = true;
                        radio.dispatchEvent(new Event('change'));
                    }
                }
            });
        });

        document.querySelectorAll('input[name="tipo_sangre"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.grupo-sangre-icono').forEach(function(icono) {
                    icono.classList.remove('bg-red-600');
                    icono.classList.add('bg-red-100');
                    icono.querySelector('span').classList.remove('text-white');
                    icono.querySelector('span').classList.add('text-red-600');
                });

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

        // ========== CONTACTOS DE EMERGENCIA ==========
        let currentContactoCount = 2;
        const maxContactos = 5;

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

        function reorganizarBordesContactos() {
            const contactos = document.querySelectorAll('[data-contacto-index]');
            contactos.forEach((contacto, index) => {
                contacto.classList.remove('border-b');
                if (index < contactos.length - 1) {
                    contacto.classList.add('border-b', 'border-gray-200');
                }
            });
        }

        function removeContacto(index) {
            if (index <= 2) {
                Swal.fire({
                    title: '⚠️ Contacto obligatorio',
                    html: 'Los primeros <strong>2 contactos</strong> son obligatorios y no pueden eliminarse.',
                    icon: 'warning',
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Entendido',
                    background: 'white',
                    backdrop: 'rgba(239, 68, 68, 0.1)',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl px-6 py-2 bg-gradient-to-r from-red-500 to-red-600 border-0',
                        title: 'text-lg font-bold',
                        htmlContainer: 'text-gray-600'
                    }
                });
                return;
            }

            Swal.fire({
                title: '¿Eliminar contacto?',
                html: `Esta acción no se puede deshacer.<br><span class="text-red-500 font-medium">Contacto #${index}</span> será eliminado.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                background: 'white',
                backdrop: 'rgba(0, 0, 0, 0.1)',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6 py-2 bg-gradient-to-r from-red-500 to-red-600 border-0',
                    cancelButton: 'rounded-xl px-6 py-2 bg-gradient-to-r from-gray-500 to-gray-600 border-0',
                    title: 'text-lg font-bold',
                    htmlContainer: 'text-gray-600'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const contactoRow = document.querySelector(`[data-contacto-index="${index}"]`);
                    if (contactoRow) {
                        contactoRow.style.transition = 'all 0.3s ease';
                        contactoRow.style.opacity = '0';
                        contactoRow.style.transform = 'translateY(-10px)';
                        contactoRow.style.height = contactoRow.offsetHeight + 'px';

                        setTimeout(() => {
                            contactoRow.remove();
                            currentContactoCount--;

                            reorganizarBordesContactos();

                            if (currentContactoCount < maxContactos) {
                                const addBtn = document.getElementById('add-contacto-btn');
                                addBtn.disabled = false;
                                addBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                                addBtn.innerHTML = '<i class="fas fa-plus mr-2"></i><span>Agregar otro contacto</span>';
                            }

                            if (typeof toastr !== 'undefined') {
                                toastr.success(
                                    `Contacto #${index} eliminado correctamente`,
                                    'Contacto eliminado', {
                                        timeOut: 3000,
                                        progressBar: true,
                                        closeButton: true,
                                        positionClass: "toast-top-right"
                                    }
                                );
                            }

                            calculateSaludProgress();
                        }, 300);
                    }
                }
            });
        }

        document.getElementById('add-contacto-btn').addEventListener('click', function() {
            if (currentContactoCount >= maxContactos) {
                Swal.fire({
                    title: '⚠️ Límite alcanzado',
                    html: `Máximo <strong>${maxContactos} contactos</strong> de emergencia permitidos`,
                    icon: 'info',
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Entendido',
                    background: 'white',
                    backdrop: 'rgba(239, 68, 68, 0.1)',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl px-6 py-2 bg-gradient-to-r from-red-500 to-red-600 border-0',
                        title: 'text-lg font-bold',
                        htmlContainer: 'text-gray-600'
                    }
                });
                return;
            }

            currentContactoCount++;
            const contactoIndex = currentContactoCount;
            const isEven = contactoIndex % 2 === 0;
            const tieneBordeInferior = contactoIndex < maxContactos;

            const contactoRow = document.createElement('div');
            contactoRow.className = `grid md:grid-cols-3 ${tieneBordeInferior ? 'border-b border-gray-200' : ''}`;
            contactoRow.setAttribute('data-contacto-index', contactoIndex);
            contactoRow.innerHTML = `
                <div class="p-6 ${isEven ? 'bg-gray-50' : 'bg-white'} border-r border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contacto de Emergencia #${contactoIndex}</label>
                    <div class="relative">
                        <input type="text" name="emergencia${contactoIndex}_nombres"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all bg-white"
                            placeholder="Nombre completo del contacto">
                        <i class="fas fa-user text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                    </div>
                </div>
                <div class="p-6 ${isEven ? 'bg-gray-50' : 'bg-white'} border-r border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Parentesco</label>
                    <div class="relative">
                        <input type="text" name="emergencia${contactoIndex}_parentesco"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all bg-white"
                            placeholder="Ej: Amigo/a, Vecino/a">
                        <i class="fas fa-users text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                    </div>
                </div>
                <div class="p-6 ${isEven ? 'bg-gray-50' : 'bg-white'}">
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contacto</label>
                        <textarea name="emergencia${contactoIndex}_direccion" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all bg-white resize-none"
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

            document.getElementById('contactos-dynamic-container').appendChild(contactoRow);
            
            setupContactoEventListeners(contactoIndex);

            contactoRow.querySelector('.remove-contacto-btn').addEventListener('click', function() {
                removeContacto(contactoIndex);
            });

            if (currentContactoCount >= maxContactos) {
                this.disabled = true;
                this.classList.add('opacity-50', 'cursor-not-allowed');
                this.innerHTML = '<i class="fas fa-ban mr-2"></i><span>Límite alcanzado</span>';
            }

            contactoRow.style.opacity = '0';
            contactoRow.style.transform = 'translateY(10px)';

            setTimeout(() => {
                contactoRow.style.transition = 'all 0.3s ease';
                contactoRow.style.opacity = '1';
                contactoRow.style.transform = 'translateY(0)';
            }, 10);

            if (typeof toastr !== 'undefined') {
                toastr.success(`Contacto #${contactoIndex} agregado correctamente`, 'Contacto agregado');
            }

            calculateSaludProgress();
        });

        // Event listeners para botones de eliminar existentes
        document.querySelectorAll('.remove-contacto-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const index = this.getAttribute('data-index');
                removeContacto(parseInt(index));
            });
        });

        // ========== SETUP INICIAL ==========
        function setupEventListeners() {
            document.querySelectorAll('input[type="radio"]').forEach(field => {
                field.addEventListener('change', calculateSaludProgress);
            });

            document.querySelectorAll('input[type="text"], textarea, .flatpickr-salud').forEach(field => {
                field.addEventListener('input', calculateSaludProgress);
                field.addEventListener('change', calculateSaludProgress);
            });
        }

        setupEventListeners();
        for (let i = 1; i <= 2; i++) {
            setupContactoEventListeners(i);
        }

        // Estado inicial de campos condicionales
        setTimeout(() => {
            document.querySelectorAll('input[name="vacuna_covid"]:checked, input[name="tiene_operacion"]:checked, input[name="dolencia_cronica"]:checked, input[name="discapacidad"]:checked').forEach(radio => {
                handleCondicionalSalud(radio);
            });
            calculateSaludProgress();
        }, 200);
    });
</script>