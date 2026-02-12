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

    <!-- Tabla de estudios - SOLO UNA VERSIÓN RESPONSIVE -->
    <div class="overflow-hidden rounded-2xl border border-gray-200 shadow-sm">
        <!-- Encabezados de tabla (visible en desktop) -->
        <div class="hidden md:grid md:grid-cols-7 bg-gradient-to-r from-purple-50 to-purple-100 border-b border-purple-200">
            <div class="px-4 py-4 text-left"><span class="text-sm font-semibold text-purple-800 uppercase tracking-wide">Nivel Educativo</span></div>
            <div class="px-4 py-4 text-left"><span class="text-sm font-semibold text-purple-800 uppercase tracking-wide">¿Terminó?</span></div>
            <div class="px-4 py-4 text-left"><span class="text-sm font-semibold text-purple-800 uppercase tracking-wide">Centro de Estudios</span></div>
            <div class="px-4 py-4 text-left"><span class="text-sm font-semibold text-purple-800 uppercase tracking-wide">Especialidad</span></div>
            <div class="px-4 py-4 text-left"><span class="text-sm font-semibold text-purple-800 uppercase tracking-wide">Nivel / Grado</span></div>
            <div class="px-4 py-4 text-left"><span class="text-sm font-semibold text-purple-800 uppercase tracking-wide">Fecha Inicio</span></div>
            <div class="px-4 py-4 text-left"><span class="text-sm font-semibold text-purple-800 uppercase tracking-wide">Fecha Fin</span></div>
        </div>

        <!-- Contenido de tabla - NIVEL SECUNDARIA (0) -->
        <div class="grid grid-cols-1 md:grid-cols-7 items-start gap-3 p-4 hover:bg-gray-50 transition-colors duration-200 border-b border-gray-100">
            <!-- Nivel Educativo (oculto) -->
            <input type="hidden" name="nivel_0" value="SECUNDARIA">
            
            <!-- Nivel Educativo (visual) -->
            <div class="px-4 py-2 md:py-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                        <i class="fas fa-school text-blue-600"></i>
                    </div>
                    <span class="font-semibold text-gray-800">Secundaria</span>
                </div>
            </div>

            <!-- ¿Terminó? -->
            <div class="px-4 py-2 md:py-4">
                <div class="flex flex-col md:flex-row md:space-x-6 space-y-2 md:space-y-0">
                    <div class="flex items-center">
                        <input type="radio" id="termino_0_si" name="termino_0" value="SI" class="termino-radio h-5 w-5 text-blue-600 focus:ring-blue-500" data-nivel="0">
                        <label for="termino_0_si" class="ml-2 text-gray-700 cursor-pointer">SI</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="termino_0_no" name="termino_0" value="NO" class="termino-radio h-5 w-5 text-blue-600 focus:ring-blue-500" data-nivel="0">
                        <label for="termino_0_no" class="ml-2 text-gray-700 cursor-pointer">NO</label>
                    </div>
                </div>
            </div>

            <!-- Centro de Estudios -->
            <div class="px-4 py-2 md:py-4">
                <label class="md:hidden block text-sm font-medium text-gray-700 mb-1">Centro de Estudios</label>
                <div class="relative">
                    <input type="text" name="centro_0" class="campo-academico w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-white" placeholder="Nombre del centro" data-nivel="0">
                </div>
            </div>

            <!-- Especialidad -->
            <div class="px-4 py-2 md:py-4">
                <label class="md:hidden block text-sm font-medium text-gray-700 mb-1">Especialidad</label>
                <div class="relative">
                    <input type="text" name="especialidad_0" class="campo-academico w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-white" placeholder="Especialidad o carrera" data-nivel="0">
                </div>
            </div>

            <!-- Nivel / Grado -->
            <div class="px-4 py-2 md:py-4">
                <label class="md:hidden block text-sm font-medium text-gray-700 mb-1">Nivel / Grado</label>
                <div class="relative">
                    <input type="text" name="grado_0" class="campo-academico w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-white" placeholder="Ej: Bachiller, Titulado" data-nivel="0">
                </div>
            </div>

            <!-- Fecha Inicio -->
            <div class="px-4 py-2 md:py-4">
                <label class="md:hidden block text-sm font-medium text-gray-700 mb-1">Fecha Inicio</label>
                <div class="relative">
                    <input type="text" name="inicio_0" class="flatpickr-date campo-academico w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-white" placeholder="Seleccione fecha" data-nivel="0">
                    <i class="fas fa-calendar-alt text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                </div>
            </div>

            <!-- Fecha Fin -->
            <div class="px-4 py-2 md:py-4">
                <label class="md:hidden block text-sm font-medium text-gray-700 mb-1">Fecha Fin</label>
                <div class="relative">
                    <input type="text" name="fin_0" class="flatpickr-date campo-academico w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-white" placeholder="Seleccione fecha" data-nivel="0">
                    <i class="fas fa-calendar-check text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                </div>
            </div>
        </div>

        <!-- NIVEL TECNICO (1) -->
        <div class="grid grid-cols-1 md:grid-cols-7 items-start gap-3 p-4 hover:bg-gray-50 transition-colors duration-200 border-b border-gray-100">
            <input type="hidden" name="nivel_1" value="TECNICO">
            
            <div class="px-4 py-2 md:py-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center mr-3">
                        <i class="fas fa-tools text-green-600"></i>
                    </div>
                    <span class="font-semibold text-gray-800">Técnico / Instituto</span>
                </div>
            </div>

            <div class="px-4 py-2 md:py-4">
                <div class="flex flex-col md:flex-row md:space-x-6 space-y-2 md:space-y-0">
                    <div class="flex items-center">
                        <input type="radio" id="termino_1_si" name="termino_1" value="SI" class="termino-radio h-5 w-5 text-green-600 focus:ring-green-500" data-nivel="1">
                        <label for="termino_1_si" class="ml-2 text-gray-700 cursor-pointer">SI</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="termino_1_no" name="termino_1" value="NO" class="termino-radio h-5 w-5 text-green-600 focus:ring-green-500" data-nivel="1">
                        <label for="termino_1_no" class="ml-2 text-gray-700 cursor-pointer">NO</label>
                    </div>
                </div>
            </div>

            <div class="px-4 py-2 md:py-4">
                <label class="md:hidden block text-sm font-medium text-gray-700 mb-1">Centro de Estudios</label>
                <input type="text" name="centro_1" class="campo-academico w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200" placeholder="Nombre del centro" data-nivel="1">
            </div>

            <div class="px-4 py-2 md:py-4">
                <label class="md:hidden block text-sm font-medium text-gray-700 mb-1">Especialidad</label>
                <input type="text" name="especialidad_1" class="campo-academico w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200" placeholder="Especialidad o carrera" data-nivel="1">
            </div>

            <div class="px-4 py-2 md:py-4">
                <label class="md:hidden block text-sm font-medium text-gray-700 mb-1">Nivel / Grado</label>
                <input type="text" name="grado_1" class="campo-academico w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200" placeholder="Ej: Técnico, Profesional" data-nivel="1">
            </div>

            <div class="px-4 py-2 md:py-4">
                <label class="md:hidden block text-sm font-medium text-gray-700 mb-1">Fecha Inicio</label>
                <div class="relative">
                    <input type="text" name="inicio_1" class="flatpickr-date campo-academico w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200" placeholder="Seleccione fecha" data-nivel="1">
                    <i class="fas fa-calendar-alt text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                </div>
            </div>

            <div class="px-4 py-2 md:py-4">
                <label class="md:hidden block text-sm font-medium text-gray-700 mb-1">Fecha Fin</label>
                <div class="relative">
                    <input type="text" name="fin_1" class="flatpickr-date campo-academico w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200" placeholder="Seleccione fecha" data-nivel="1">
                    <i class="fas fa-calendar-check text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                </div>
            </div>
        </div>

        <!-- NIVEL UNIVERSITARIO (2) -->
        <div class="grid grid-cols-1 md:grid-cols-7 items-start gap-3 p-4 hover:bg-gray-50 transition-colors duration-200 border-b border-gray-100">
            <input type="hidden" name="nivel_2" value="UNIVERSITARIO">
            
            <div class="px-4 py-2 md:py-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center mr-3">
                        <i class="fas fa-university text-indigo-600"></i>
                    </div>
                    <span class="font-semibold text-gray-800">Universitario</span>
                </div>
            </div>

            <div class="px-4 py-2 md:py-4">
                <div class="flex flex-col md:flex-row md:space-x-6 space-y-2 md:space-y-0">
                    <div class="flex items-center">
                        <input type="radio" id="termino_2_si" name="termino_2" value="SI" class="termino-radio h-5 w-5 text-indigo-600 focus:ring-indigo-500" data-nivel="2">
                        <label for="termino_2_si" class="ml-2 text-gray-700 cursor-pointer">SI</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="termino_2_no" name="termino_2" value="NO" class="termino-radio h-5 w-5 text-indigo-600 focus:ring-indigo-500" data-nivel="2">
                        <label for="termino_2_no" class="ml-2 text-gray-700 cursor-pointer">NO</label>
                    </div>
                </div>
            </div>

            <div class="px-4 py-2 md:py-4">
                <label class="md:hidden block text-sm font-medium text-gray-700 mb-1">Centro de Estudios</label>
                <input type="text" name="centro_2" class="campo-academico w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" placeholder="Nombre del centro" data-nivel="2">
            </div>

            <div class="px-4 py-2 md:py-4">
                <label class="md:hidden block text-sm font-medium text-gray-700 mb-1">Especialidad</label>
                <input type="text" name="especialidad_2" class="campo-academico w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" placeholder="Carrera" data-nivel="2">
            </div>

            <div class="px-4 py-2 md:py-4">
                <label class="md:hidden block text-sm font-medium text-gray-700 mb-1">Nivel / Grado</label>
                <input type="text" name="grado_2" class="campo-academico w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" placeholder="Ej: Bachiller, Titulado" data-nivel="2">
            </div>

            <div class="px-4 py-2 md:py-4">
                <label class="md:hidden block text-sm font-medium text-gray-700 mb-1">Fecha Inicio</label>
                <div class="relative">
                    <input type="text" name="inicio_2" class="flatpickr-date campo-academico w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" placeholder="Seleccione fecha" data-nivel="2">
                    <i class="fas fa-calendar-alt text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                </div>
            </div>

            <div class="px-4 py-2 md:py-4">
                <label class="md:hidden block text-sm font-medium text-gray-700 mb-1">Fecha Fin</label>
                <div class="relative">
                    <input type="text" name="fin_2" class="flatpickr-date campo-academico w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" placeholder="Seleccione fecha" data-nivel="2">
                    <i class="fas fa-calendar-check text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                </div>
            </div>
        </div>

        <!-- NIVEL POSTGRADO (3) -->
        <div class="grid grid-cols-1 md:grid-cols-7 items-start gap-3 p-4 hover:bg-gray-50 transition-colors duration-200">
            <input type="hidden" name="nivel_3" value="POSTGRADO">
            
            <div class="px-4 py-2 md:py-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center mr-3">
                        <i class="fas fa-user-graduate text-purple-600"></i>
                    </div>
                    <span class="font-semibold text-gray-800">Post Grado</span>
                </div>
            </div>

            <div class="px-4 py-2 md:py-4">
                <div class="flex flex-col md:flex-row md:space-x-6 space-y-2 md:space-y-0">
                    <div class="flex items-center">
                        <input type="radio" id="termino_3_si" name="termino_3" value="SI" class="termino-radio h-5 w-5 text-purple-600 focus:ring-purple-500" data-nivel="3">
                        <label for="termino_3_si" class="ml-2 text-gray-700 cursor-pointer">SI</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="termino_3_no" name="termino_3" value="NO" class="termino-radio h-5 w-5 text-purple-600 focus:ring-purple-500" data-nivel="3">
                        <label for="termino_3_no" class="ml-2 text-gray-700 cursor-pointer">NO</label>
                    </div>
                </div>
            </div>

            <div class="px-4 py-2 md:py-4">
                <label class="md:hidden block text-sm font-medium text-gray-700 mb-1">Centro de Estudios</label>
                <input type="text" name="centro_3" class="campo-academico w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200" placeholder="Nombre del centro" data-nivel="3">
            </div>

            <div class="px-4 py-2 md:py-4">
                <label class="md:hidden block text-sm font-medium text-gray-700 mb-1">Especialidad</label>
                <input type="text" name="especialidad_3" class="campo-academico w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200" placeholder="Especialidad" data-nivel="3">
            </div>

            <div class="px-4 py-2 md:py-4">
                <label class="md:hidden block text-sm font-medium text-gray-700 mb-1">Nivel / Grado</label>
                <input type="text" name="grado_3" class="campo-academico w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200" placeholder="Ej: Maestría, Doctorado" data-nivel="3">
            </div>

            <div class="px-4 py-2 md:py-4">
                <label class="md:hidden block text-sm font-medium text-gray-700 mb-1">Fecha Inicio</label>
                <div class="relative">
                    <input type="text" name="inicio_3" class="flatpickr-date campo-academico w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200" placeholder="Seleccione fecha" data-nivel="3">
                    <i class="fas fa-calendar-alt text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                </div>
            </div>

            <div class="px-4 py-2 md:py-4">
                <label class="md:hidden block text-sm font-medium text-gray-700 mb-1">Fecha Fin</label>
                <div class="relative">
                    <input type="text" name="fin_3" class="flatpickr-date campo-academico w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200" placeholder="Seleccione fecha" data-nivel="3">
                    <i class="fas fa-calendar-check text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                </div>
            </div>
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
                    <div class="bg-green-500 h-2 rounded-full transition-all duration-300" style="width: 0%" id="academic-progress"></div>
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
        disableMobile: false,
        allowInput: true,
        clickOpens: true,
        onValueUpdate: function(selectedDates, dateStr, instance) {
            calculateAcademicProgress();
        }
    };

    // Inicializar Flatpickr
    document.querySelectorAll('.flatpickr-date').forEach(function(element) {
        element._flatpickr = flatpickr(element, {
            ...flatpickrOptions,
            onChange: function(selectedDates, dateStr, instance) {
                const nivelId = element.getAttribute('data-nivel');
                const isFin = element.name.includes('fin');
                
                if (isFin) {
                    const inicioInput = document.querySelector(`[name="inicio_${nivelId}"]`);
                    if (inicioInput && inicioInput.value) {
                        instance.set('minDate', inicioInput.value);
                    }
                }
                
                calculateAcademicProgress();
            }
        });
    });

    // Función para manejar el cambio en los radio buttons - NAMES SIMPLES
    function handleTerminoChange(event) {
        const nivelId = event.target.getAttribute('data-nivel');
        const value = event.target.value;
        
        console.log(`Cambio en nivel ${nivelId}: ${value}`);
        
        // SELECCIONAR TODOS LOS CAMPOS POR NAME SIMPLE
        const selectores = [
            `[name="centro_${nivelId}"]`,
            `[name="especialidad_${nivelId}"]`,
            `[name="grado_${nivelId}"]`,
            `[name="inicio_${nivelId}"]`,
            `[name="fin_${nivelId}"]`
        ];
        
        if (value === 'NO') {
            // Deshabilitar campos
            selectores.forEach(selector => {
                document.querySelectorAll(selector).forEach(campo => {
                    campo.disabled = true;
                    campo.value = '';
                    campo.classList.add('bg-gray-100', 'cursor-not-allowed', 'text-gray-400');
                    campo.classList.remove('bg-white');
                    campo.placeholder = 'No aplica';
                    
                    if (campo._flatpickr) {
                        campo._flatpickr.altInput.disabled = true;
                        campo._flatpickr.altInput.value = '';
                        campo._flatpickr.altInput.classList.add('bg-gray-100', 'cursor-not-allowed', 'text-gray-400');
                        campo._flatpickr.altInput.placeholder = 'No aplica';
                    }
                });
            });
            
        } else if (value === 'SI') {
            // Habilitar campos
            selectores.forEach(selector => {
                document.querySelectorAll(selector).forEach(campo => {
                    campo.disabled = false;
                    campo.classList.remove('bg-gray-100', 'cursor-not-allowed', 'text-gray-400');
                    campo.classList.add('bg-white');
                    
                    if (selector.includes('centro')) {
                        campo.placeholder = 'Nombre del centro';
                    } else if (selector.includes('especialidad')) {
                        campo.placeholder = 'Especialidad o carrera';
                    } else if (selector.includes('grado')) {
                        campo.placeholder = 'Ej: Bachiller, Titulado';
                    } else if (selector.includes('inicio') || selector.includes('fin')) {
                        campo.placeholder = 'Seleccione fecha';
                        
                        if (campo._flatpickr) {
                            campo._flatpickr.altInput.disabled = false;
                            campo._flatpickr.altInput.classList.remove('bg-gray-100', 'cursor-not-allowed', 'text-gray-400');
                            campo._flatpickr.altInput.placeholder = 'Seleccione fecha';
                        }
                    }
                });
            });
        }
        
        calculateAcademicProgress();
    }

    // Función para calcular el progreso
    function calculateAcademicProgress() {
        const niveles = [0, 1, 2, 3];
        let nivelesCompletados = 0;
        let totalNiveles = niveles.length;
        
        niveles.forEach(nivelId => {
            const radioTerminado = document.querySelector(`[name="termino_${nivelId}"]:checked`);
            
            if (radioTerminado) {
                if (radioTerminado.value === 'SI') {
                    const centro = document.querySelector(`[name="centro_${nivelId}"]`);
                    const especialidad = document.querySelector(`[name="especialidad_${nivelId}"]`);
                    const grado = document.querySelector(`[name="grado_${nivelId}"]`);
                    const inicio = document.querySelector(`[name="inicio_${nivelId}"]`);
                    const fin = document.querySelector(`[name="fin_${nivelId}"]`);
                    
                    const centroValor = (centro && !centro.disabled) ? centro.value.trim() : '';
                    const especialidadValor = (especialidad && !especialidad.disabled) ? especialidad.value.trim() : '';
                    const gradoValor = (grado && !grado.disabled) ? grado.value.trim() : '';
                    const inicioValor = (inicio && !inicio.disabled) ? inicio.value.trim() : '';
                    const finValor = (fin && !fin.disabled) ? fin.value.trim() : '';
                    
                    if (centroValor !== '' && inicioValor !== '') {
                        nivelesCompletados++;
                    }
                } else if (radioTerminado.value === 'NO') {
                    nivelesCompletados++;
                }
            }
        });
        
        const percentage = totalNiveles > 0 ? Math.round((nivelesCompletados / totalNiveles) * 100) : 0;
        const progressElement = document.getElementById('academic-percentage');
        const progressBar = document.getElementById('academic-progress');
        
        if (progressElement) progressElement.textContent = `${percentage}%`;
        if (progressBar) progressBar.style.width = `${percentage}%`;
        
        if (progressBar) {
            progressBar.classList.remove('bg-green-500', 'bg-green-400', 'bg-yellow-500', 'bg-yellow-400', 'bg-red-500', 'bg-gray-300');
            if (percentage >= 100) progressBar.classList.add('bg-green-500');
            else if (percentage >= 75) progressBar.classList.add('bg-green-400');
            else if (percentage >= 50) progressBar.classList.add('bg-yellow-500');
            else if (percentage >= 25) progressBar.classList.add('bg-yellow-400');
            else if (percentage > 0) progressBar.classList.add('bg-red-500');
            else progressBar.classList.add('bg-gray-300');
        }
    }

    // Agregar event listeners a los radio buttons
    document.querySelectorAll('.termino-radio').forEach(radio => {
        radio.addEventListener('change', handleTerminoChange);
    });

    // Agregar event listeners a los campos de texto
    document.querySelectorAll('[name^="centro_"], [name^="especialidad_"], [name^="grado_"], [name^="inicio_"], [name^="fin_"]').forEach(campo => {
        campo.addEventListener('input', calculateAcademicProgress);
        campo.addEventListener('change', calculateAcademicProgress);
    });

    // Inicializar estado de los campos
    document.querySelectorAll('.termino-radio:checked').forEach(radio => {
        const event = new Event('change', { bubbles: true });
        radio.dispatchEvent(event);
    });

    calculateAcademicProgress();
});

// DEBUG: Ver qué se envía al servidor
document.getElementById('formularioPersonal')?.addEventListener('submit', function(e) {
    console.log('=== VERIFICANDO CAMPOS DE ESTUDIOS ===');
    
    for (let i = 0; i <= 3; i++) {
        const radioSI = document.querySelector(`[name="termino_${i}"][value="SI"]`);
        const radioNO = document.querySelector(`[name="termino_${i}"][value="NO"]`);
        
        console.log(`Nivel ${i}:`, {
            'SI checked': radioSI ? radioSI.checked : false,
            'NO checked': radioNO ? radioNO.checked : false,
        });
        
        if (radioSI && radioSI.checked) {
            ['centro', 'especialidad', 'grado', 'inicio', 'fin'].forEach(campo => {
                const elemento = document.querySelector(`[name="${campo}_${i}"]`);
                if (elemento) {
                    console.log(`  ${campo}_${i} = ${elemento.value}`, `(disabled: ${elemento.disabled})`);
                }
            });
        }
    }
});
</script>

<style>
/* Estilos para campos deshabilitados */
.campo-academico:disabled,
.flatpickr-date:disabled,
.flatpickr-alt-input:disabled {
    background-color: #f3f4f6 !important;
    color: #9ca3af !important;
    cursor: not-allowed !important;
    border-color: #d1d5db !important;
}

/* Estilos para campos habilitados */
.campo-academico:enabled,
.flatpickr-date:enabled,
.flatpickr-alt-input:enabled {
    background-color: white !important;
    color: #374151 !important;
}

/* Transiciones suaves */
.campo-academico, 
.flatpickr-date, 
.flatpickr-alt-input {
    transition: all 0.3s ease;
}

/* Estilos responsive */
@media (max-width: 768px) {
    .md\:grid-cols-7 > * {
        padding: 0.5rem 1rem;
    }
    
    .md\:hidden {
        display: block !important;
    }
}

#academic-progress {
    transition: width 0.5s ease-in-out, background-color 0.5s ease;
}
</style>