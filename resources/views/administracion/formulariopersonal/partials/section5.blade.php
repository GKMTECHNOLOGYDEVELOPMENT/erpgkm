<!-- Sección 5: Datos Laborales -->
<div class="form-section bg-white rounded-2xl shadow-lg p-6 md:p-8 mt-8 border border-gray-200">
    <!-- Encabezado de sección -->
    <div class="section-header mb-8 pb-6 border-b border-gray-200">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center">
                <div class="bg-gradient-to-r from-amber-500 to-orange-600 w-12 h-12 rounded-xl flex items-center justify-center mr-4 shadow-md">
                    <i class="fas fa-briefcase text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl md:text-2xl font-bold text-gray-800">Datos Laborales</h3>
                    <p class="text-gray-500 mt-1 text-sm md:text-base">Complete sus datos laborales en la empresa</p>
                </div>
            </div>
            <span class="bg-amber-50 text-amber-700 px-4 py-2 rounded-full text-sm font-semibold border border-amber-100">
                Sección 5
            </span>
        </div>
    </div>

    <!-- Información importante -->
    <div class="mb-8 bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-100 rounded-xl p-5">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-amber-500 text-lg mt-1 mr-3"></i>
            <div>
                <h4 class="font-medium text-amber-800 mb-1">Información contractual</h4>
                <p class="text-amber-700 text-sm">
                    Complete todos los campos relacionados con su situación laboral actual en la empresa.
                </p>
            </div>
        </div>
    </div>

    <!-- Tabla de datos laborales - Versión responsive -->
    <div class="mb-10">
        <h4 class="text-lg font-semibold text-gray-700 mb-6 flex items-center">
            <i class="fas fa-file-contract text-amber-500 mr-2 text-sm"></i>
            Información del Contrato Laboral
        </h4>

        <!-- Versión desktop -->
        <div class="hidden md:block overflow-hidden rounded-2xl border border-gray-200 shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-amber-50 to-orange-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-800 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-money-bill-wave mr-2"></i>
                                Sueldo
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-800 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-sitemap mr-2"></i>
                                Área
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-800 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-file-signature mr-2"></i>
                                Tipo de Contrato
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-800 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-user-tie mr-2"></i>
                                Cargo
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-800 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-plus mr-2"></i>
                                Fecha Inicio
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-800 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-clock mr-2"></i>
                                Hora Inicio
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-800 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-minus mr-2"></i>
                                Fecha Término
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-800 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-clock mr-2"></i>
                                Hora Término
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <!-- Sueldo -->
                        <td class="px-6 py-5">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">S/</span>
                                </div>
                                <input type="text" name="sueldo"
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all bg-white"
                                    placeholder="0.00"
                                    inputmode="decimal">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-400 text-sm">PEN</span>
                                </div>
                            </div>
                        </td>

                        <!-- Área -->
                        <td class="px-6 py-5">
                            <div class="relative">
                                <input type="text" name="area"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all bg-white"
                                    placeholder="Ej: Marketing, IT">
                                <i class="fas fa-building text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                            </div>
                        </td>

                        <!-- Tipo de Contrato -->
                        <td class="px-6 py-5">
                            <div class="relative">
                                <select name="tipo_contrato"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all appearance-none bg-white">
                                    <option value="">Seleccione tipo</option>
                                    <option value="Indefinido">Indefinido</option>
                                    <option value="Temporal">Temporal</option>
                                    <option value="Practicante">Practicante</option>
                                    <option value="Otro">Otro</option>
                                </select>
                                <i class="fas fa-chevron-down text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                            </div>
                        </td>

                        <!-- Cargo -->
                        <td class="px-6 py-5">
                            <div class="relative">
                                <input type="text" name="cargo"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all bg-white"
                                    placeholder="Ej: Analista, Supervisor">
                                <i class="fas fa-id-badge text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                            </div>
                        </td>

                        <!-- Fecha de Inicio -->
                        <td class="px-6 py-5">
                            <div class="relative">
                                <input type="text" 
                                       name="fecha_inicio"
                                       class="flatpickr-laboral w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all bg-white"
                                       placeholder="Seleccione fecha">
                                <i class="fas fa-calendar-alt text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                            </div>
                        </td>

                        <!-- Hora de Inicio de Jornada -->
                        <td class="px-6 py-5">
                            <div class="relative">
                                <input type="text" 
                                       name="hora_inicio"
                                       class="flatpickr-hora w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all bg-white"
                                       placeholder="HH:MM"
                                       data-enable-time="true"
                                       data-no-calendar="true"
                                       data-date-format="H:i">
                                <i class="fas fa-clock text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                            </div>
                        </td>

                        <!-- Fecha de Término -->
                        <td class="px-6 py-5">
                            <div class="relative">
                                <input type="text" 
                                       name="fecha_termino"
                                       class="flatpickr-laboral w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all bg-white"
                                       placeholder="Seleccione fecha">
                                <i class="fas fa-calendar-check text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                            </div>
                        </td>

                        <!-- Hora de Término de Jornada -->
                        <td class="px-6 py-5">
                            <div class="relative">
                                <input type="text" 
                                       name="hora_termino"
                                       class="flatpickr-hora w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all bg-white"
                                       placeholder="HH:MM"
                                       data-enable-time="true"
                                       data-no-calendar="true"
                                       data-date-format="H:i">
                                <i class="fas fa-clock text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Versión móvil -->
        <div class="md:hidden space-y-6">
            <!-- Tarjeta 1: Información básica -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center mb-4 pb-3 border-b border-gray-100">
                    <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center mr-3">
                        <i class="fas fa-info-circle text-amber-600"></i>
                    </div>
                    <h4 class="font-bold text-gray-800">Información Básica</h4>
                </div>

                <div class="space-y-4">
                    <!-- Sueldo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sueldo (PEN)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500">S/</span>
                            </div>
                            <input type="text" name="sueldo"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all bg-white"
                                placeholder="0.00"
                                inputmode="decimal">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-400 text-sm">PEN</span>
                            </div>
                        </div>
                    </div>

                    <!-- Área -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Área</label>
                        <div class="relative">
                            <input type="text" name="area"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all bg-white"
                                placeholder="Ej: Marketing, IT">
                            <i class="fas fa-building text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                        </div>
                    </div>

                    <!-- Tipo de Contrato -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Contrato</label>
                        <div class="relative">
                            <select name="tipo_contrato"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all appearance-none bg-white">
                                <option value="">Seleccione tipo</option>
                                <option value="Indefinido">Indefinido</option>
                                <option value="Temporal">Temporal</option>
                                <option value="Practicante">Practicante</option>
                                <option value="Otro">Otro</option>
                            </select>
                            <i class="fas fa-chevron-down text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                        </div>
                    </div>

                    <!-- Cargo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cargo</label>
                        <div class="relative">
                            <input type="text" name="cargo"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all bg-white"
                                placeholder="Ej: Analista, Supervisor">
                            <i class="fas fa-id-badge text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 2: Fechas y Horas -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center mb-4 pb-3 border-b border-gray-100">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                        <i class="fas fa-calendar-alt text-blue-600"></i>
                    </div>
                    <h4 class="font-bold text-gray-800">Fechas y Horarios</h4>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <!-- Fecha de Inicio -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Inicio</label>
                        <div class="relative">
                            <input type="text" 
                                   name="fecha_inicio"
                                   class="flatpickr-laboral-mobile w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all bg-white"
                                   placeholder="Seleccione fecha">
                            <i class="fas fa-calendar text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                        </div>
                    </div>

                    <!-- Hora de Inicio -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hora de Inicio</label>
                        <div class="relative">
                            <input type="text" 
                                   name="hora_inicio"
                                   class="flatpickr-hora-mobile w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all bg-white"
                                   placeholder="HH:MM"
                                   data-enable-time="true"
                                   data-no-calendar="true"
                                   data-date-format="H:i">
                            <i class="fas fa-clock text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                        </div>
                    </div>

                    <!-- Fecha de Término -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Término</label>
                        <div class="relative">
                            <input type="text" 
                                   name="fecha_termino"
                                   class="flatpickr-laboral-mobile w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all bg-white"
                                   placeholder="Seleccione fecha">
                            <i class="fas fa-calendar-check text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                        </div>
                    </div>

                    <!-- Hora de Término -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hora de Término</label>
                        <div class="relative">
                            <input type="text" 
                                   name="hora_termino"
                                   class="flatpickr-hora-mobile w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all bg-white"
                                   placeholder="HH:MM"
                                   data-enable-time="true"
                                   data-no-calendar="true"
                                   data-date-format="H:i">
                            <i class="fas fa-clock text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información adicional -->
    <div class="mb-10">
        <h4 class="text-lg font-semibold text-gray-700 mb-6 flex items-center">
            <i class="fas fa-clipboard-list text-amber-500 mr-2 text-sm"></i>
            Información Adicional del Contrato
        </h4>

        <div class="bg-gradient-to-r from-gray-50 to-white border border-gray-200 rounded-2xl p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Jornada Laboral -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jornada Laboral</label>
                    <div class="space-y-3">
                        <div class="flex items-center p-3 border border-gray-300 rounded-xl hover:bg-amber-50 transition-colors">
                            <input type="radio" id="jornada_completa" name="jornada_laboral" value="completa"
                                class="h-5 w-5 text-amber-600 focus:ring-amber-500">
                            <label for="jornada_completa" class="ml-3 text-gray-700 cursor-pointer flex-1">
                                <span class="font-medium">Jornada Completa</span>
                                <p class="text-sm text-gray-500 mt-1">8 horas diarias / 48 horas semanales</p>
                            </label>
                        </div>
                        <div class="flex items-center p-3 border border-gray-300 rounded-xl hover:bg-amber-50 transition-colors">
                            <input type="radio" id="jornada_parcial" name="jornada_laboral" value="parcial"
                                class="h-5 w-5 text-amber-600 focus:ring-amber-500">
                            <label for="jornada_parcial" class="ml-3 text-gray-700 cursor-pointer flex-1">
                                <span class="font-medium">Jornada Parcial</span>
                                <p class="text-sm text-gray-500 mt-1">Menos de 8 horas diarias</p>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Modalidad de Trabajo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Modalidad de Trabajo</label>
                    <div class="space-y-3">
                        <div class="flex items-center p-3 border border-gray-300 rounded-xl hover:bg-amber-50 transition-colors">
                            <input type="radio" id="modalidad_presencial" name="modalidad_trabajo" value="presencial"
                                class="h-5 w-5 text-amber-600 focus:ring-amber-500">
                            <label for="modalidad_presencial" class="ml-3 text-gray-700 cursor-pointer flex-1">
                                <span class="font-medium">Presencial</span>
                                <p class="text-sm text-gray-500 mt-1">Trabajo en oficina</p>
                            </label>
                        </div>
                        <div class="flex items-center p-3 border border-gray-300 rounded-xl hover:bg-amber-50 transition-colors">
                            <input type="radio" id="modalidad_remoto" name="modalidad_trabajo" value="remoto"
                                class="h-5 w-5 text-amber-600 focus:ring-amber-500">
                            <label for="modalidad_remoto" class="ml-3 text-gray-700 cursor-pointer flex-1">
                                <span class="font-medium">Remoto</span>
                                <p class="text-sm text-gray-500 mt-1">Trabajo desde casa</p>
                            </label>
                        </div>
                        <div class="flex items-center p-3 border border-gray-300 rounded-xl hover:bg-amber-50 transition-colors">
                            <input type="radio" id="modalidad_hibrido" name="modalidad_trabajo" value="hibrido"
                                class="h-5 w-5 text-amber-600 focus:ring-amber-500">
                            <label for="modalidad_hibrido" class="ml-3 text-gray-700 cursor-pointer flex-1">
                                <span class="font-medium">Híbrido</span>
                                <p class="text-sm text-gray-500 mt-1">Combinación presencial/remoto</p>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Nota importante -->
    <div class="mb-8 bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-100 rounded-xl p-5">
        <div class="flex items-start">
            <div class="bg-amber-100 rounded-lg p-3 mr-4">
                <i class="fas fa-exclamation-circle text-amber-600 text-lg"></i>
            </div>
            <div>
                <h4 class="font-medium text-amber-800 mb-1">Información contractual</h4>
                <p class="text-amber-700 text-sm">
                    Los datos proporcionados en esta sección deben coincidir con los establecidos en su contrato de trabajo.
                    En caso de discrepancia, prevalecerán los términos del contrato firmado.
                </p>
            </div>
        </div>
    </div>

    <!-- Indicador de progreso -->
    <div class="mt-6 pt-6 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                <span class="text-sm text-gray-600">Información laboral completa</span>
            </div>
            <div class="flex items-center">
                <div class="w-32 bg-gray-200 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full" style="width: 0%" id="laboral-progress"></div>
                </div>
                <span class="ml-3 text-sm font-medium text-gray-700" id="laboral-percentage">0%</span>
            </div>
        </div>
    </div>
</div>

<script>
    // Inicializar Flatpickr para datos laborales
    document.addEventListener('DOMContentLoaded', function() {
        // Configuración para fechas laborales
        const flatpickrLaboralOptions = {
            locale: "es",
            dateFormat: "Y-m-d",
            altFormat: "d/m/Y",
            altInput: true,
            altInputClass: "flatpickr-alt-input",
            theme: "airbnb",
            allowInput: true,
            clickOpens: true,
            onValueUpdate: function(selectedDates, dateStr, instance) {
                calculateLaboralProgress();
            }
        };

        // Configuración para horas
        const flatpickrHoraOptions = {
            locale: "es",
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            theme: "airbnb",
            allowInput: true,
            clickOpens: true,
            onValueUpdate: function(selectedDates, dateStr, instance) {
                calculateLaboralProgress();
            }
        };

        // Inicializar Flatpickr para desktop
        document.querySelectorAll('.flatpickr-laboral').forEach(function(element) {
            flatpickr(element, flatpickrLaboralOptions);
        });

        document.querySelectorAll('.flatpickr-hora').forEach(function(element) {
            flatpickr(element, flatpickrHoraOptions);
        });

        // Inicializar Flatpickr para móvil
        document.querySelectorAll('.flatpickr-laboral-mobile').forEach(function(element) {
            flatpickr(element, flatpickrLaboralOptions);
        });

        document.querySelectorAll('.flatpickr-hora-mobile').forEach(function(element) {
            flatpickr(element, flatpickrHoraOptions);
        });

        // Formatear input de sueldo
        const sueldoInput = document.querySelector('input[name="sueldo"]');
        if (sueldoInput) {
            sueldoInput.addEventListener('input', function(e) {
                // Permitir solo números y un punto decimal
                let value = e.target.value.replace(/[^\d.]/g, '');
                
                // Permitir solo un punto decimal
                const parts = value.split('.');
                if (parts.length > 2) {
                    value = parts[0] + '.' + parts.slice(1).join('');
                }
                
                // Limitar a 2 decimales
                if (parts.length === 2 && parts[1].length > 2) {
                    value = parts[0] + '.' + parts[1].substring(0, 2);
                }
                
                e.target.value = value;
                calculateLaboralProgress();
            });
            
            sueldoInput.addEventListener('blur', function(e) {
                if (e.target.value) {
                    const num = parseFloat(e.target.value);
                    if (!isNaN(num)) {
                        e.target.value = num.toFixed(2);
                    }
                }
            });
        }

        // Función para calcular progreso de sección laboral
        function calculateLaboralProgress() {
            let completed = 0;
            let total = 0;

            // Campos principales (peso 2 cada uno por ser importantes)
            const camposPrincipales = [
                'sueldo',
                'area', 
                'tipo_contrato',
                'cargo',
                'fecha_inicio',
                'hora_inicio'
            ];

            camposPrincipales.forEach(campo => {
                const element = document.querySelector(`[name="${campo}"]`);
                if (element) {
                    if (element.type === 'select-one') {
                        if (element.value && element.value.trim() !== '') {
                            completed += 2;
                        }
                        total += 2;
                    } else {
                        if (element.value && element.value.trim() !== '') {
                            completed += 2;
                        }
                        total += 2;
                    }
                }
            });

            // Campos opcionales (peso 1 cada uno)
            const camposOpcionales = [
                'fecha_termino',
                'hora_termino'
            ];

            camposOpcionales.forEach(campo => {
                const element = document.querySelector(`[name="${campo}"]`);
                if (element) {
                    if (element.value && element.value.trim() !== '') {
                        completed += 1;
                    }
                    total += 1;
                }
            });

            // Jornada laboral (radio button)
            const jornadaLaboral = document.querySelector('input[name="jornada_laboral"]:checked');
            if (jornadaLaboral) {
                completed += 1;
            }
            total += 1;

            // Modalidad de trabajo (radio button)
            const modalidadTrabajo = document.querySelector('input[name="modalidad_trabajo"]:checked');
            if (modalidadTrabajo) {
                completed += 1;
            }
            total += 1;

            // Calcular porcentaje
            const percentage = total > 0 ? Math.round((completed / total) * 100) : 0;
            document.getElementById('laboral-percentage').textContent = `${percentage}%`;
            document.getElementById('laboral-progress').style.width = `${percentage}%`;
        }

        // Agregar event listeners a todos los campos
        function setupEventListeners() {
            // Campos de texto y select
            document.querySelectorAll('input[type="text"], select, .flatpickr-laboral, .flatpickr-hora, .flatpickr-laboral-mobile, .flatpickr-hora-mobile').forEach(field => {
                field.addEventListener('input', calculateLaboralProgress);
                field.addEventListener('change', calculateLaboralProgress);
            });

            // Radio buttons
            document.querySelectorAll('input[type="radio"]').forEach(field => {
                field.addEventListener('change', calculateLaboralProgress);
            });
        }

        // Inicializar event listeners
        setupEventListeners();
        
        // Calcular progreso inicial
        calculateLaboralProgress();
    });
</script>

<style>
    /* Estilos para campos de sueldo */
    input[name="sueldo"] {
        font-family: 'Courier New', monospace;
        font-weight: 600;
    }

    
    /* Estilos para tarjetas en móvil */
    @media (max-width: 768px) {
        .md\\:hidden .space-y-6 > div {
            margin-bottom: 1.5rem;
        }
        
        .flatpickr-calendar {
            max-width: 300px;
            margin: 0 auto;
        }
        
        .flatpickr-time {
            min-width: 200px;
        }
    }
    
    /* Estilos para radio buttons personalizados */
    input[type="radio"]:checked + label {
        font-weight: 600;
    }
    
    /* Animación para cambios en progreso */
    #laboral-progress {
        transition: width 0.5s ease-in-out;
    }
</style>