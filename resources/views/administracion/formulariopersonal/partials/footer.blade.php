<!-- Sección 7: Declaración Jurada -->
<div class="form-section bg-white rounded-2xl shadow-lg p-6 md:p-8 mt-8 border border-gray-200">
    <!-- Encabezado de sección -->
    <div class="section-header mb-8 pb-6 border-b border-gray-200">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center">
                <div class="bg-gradient-to-r from-indigo-500 to-violet-600 w-12 h-12 rounded-xl flex items-center justify-center mr-4 shadow-md">
                    <i class="fas fa-gavel text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl md:text-2xl font-bold text-gray-800">Declaración Jurada</h3>
                    <p class="text-gray-500 mt-1 text-sm md:text-base">Declaración bajo juramento de veracidad de la información proporcionada</p>
                </div>
            </div>
            <span class="bg-indigo-50 text-indigo-700 px-4 py-2 rounded-full text-sm font-semibold border border-indigo-100">
                Sección 5
            </span>
        </div>
    </div>

    <!-- Alerta importante -->
    <div class="mb-8 bg-gradient-to-r from-indigo-50 to-violet-50 border border-indigo-200 rounded-2xl p-6">
        <div class="flex items-start">
            <div class="bg-indigo-100 rounded-lg p-3 mr-4">
                <i class="fas fa-exclamation-circle text-indigo-600 text-lg"></i>
            </div>
            <div>
                <h4 class="font-medium text-indigo-800 mb-1">Declaración bajo juramento</h4>
                <p class="text-indigo-700 text-sm">
                    Esta es una declaración jurada formal. La información falsa puede tener consecuencias legales y laborales.
                </p>
            </div>
        </div>
    </div>

    <!-- Texto de declaración jurada -->
    <div class="mb-10">
        <div class="bg-gradient-to-br from-gray-50 to-white border-2 border-gray-200 rounded-2xl p-8">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center mr-4">
                    <i class="fas fa-balance-scale text-red-600"></i>
                </div>
                <h4 class="text-lg font-bold text-gray-800">Texto de la Declaración Jurada</h4>
            </div>
            
            <div class="relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-indigo-500 to-violet-600 rounded-full"></div>
                <div class="ml-6">
                    <p class="text-gray-700 leading-relaxed text-justify italic border-l-4 border-indigo-100 pl-4 py-2">
                        <span class="font-semibold text-indigo-700">"DECLARO BAJO JURAMENTO</span> que toda la información proporcionada en el presente formulario 
                        es veraz, completa y exacta. Autorizo expresamente a <span class="font-semibold">LA EMPRESA</span> para que realice las verificaciones 
                        que considere pertinentes, incluyendo consultas a bases de datos públicas y privadas, así como la confirmación 
                        de antecedentes laborales, académicos y penales.
                    </p>
                    
                    <p class="text-gray-700 leading-relaxed text-justify italic border-l-4 border-indigo-100 pl-4 py-2 mt-4">
                        <span class="font-semibold text-indigo-700">ENTIENDO Y ACEPTO</span> que, en caso de comprobarse que alguna información proporcionada 
                        es falsa, inexacta u omitida, <span class="font-semibold">LA EMPRESA</span> podrá aplicar las medidas que considere convenientes, 
                        incluyendo la terminación del vínculo laboral sin responsabilidad alguna, sin perjuicio de las acciones 
                        legales a que hubiere lugar.
                    </p>
                    
                    <p class="text-gray-700 leading-relaxed text-justify italic border-l-4 border-indigo-100 pl-4 py-2 mt-4">
                        <span class="font-semibold text-indigo-700">MANIFIESTO</span> que he leído y comprendido completamente el contenido de esta declaración 
                        y acepto sus términos de manera libre, voluntaria y consciente."
                    </p>
                </div>
            </div>
            
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-info-circle text-indigo-500 mr-2"></i>
                    <span>Esta declaración tiene carácter de juramento y valor legal conforme al artículo 381° del Código Penal.</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Fecha, firma y huella -->
    <div class="space-y-10">
        <!-- Fecha -->
        <div class="bg-gradient-to-r from-gray-50 to-white border border-gray-200 rounded-2xl p-6">
            <h4 class="text-lg font-semibold text-gray-700 mb-6 flex items-center">
                <i class="fas fa-calendar-alt text-indigo-500 mr-2 text-sm"></i>
                Fecha de Declaración
            </h4>
            
            <div class="space-y-4">
                <label class="block text-gray-700 font-medium">Lugar y Fecha</label>
                <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
                    <div class="flex-1">
                        <div class="relative">
                            <input type="text" 
                                   name="lugar_declaracion"
                                   class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all bg-white"
                                   value="Lima" 
                                   readonly>
                            <i class="fas fa-map-marker-alt text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                        </div>
                    </div>
                    <div class="text-gray-500 font-medium">a los</div>
                    <div class="flex-1">
                        <div class="relative">
                            <input type="text" 
                                   name="dia_declaracion"
                                   id="dia_declaracion"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all bg-white"
                                   placeholder="DD"
                                   maxlength="2"
                                   inputmode="numeric">
                            <label class="absolute -top-2 left-3 bg-white px-1 text-xs text-gray-500">Día</label>
                        </div>
                    </div>
                    <div class="text-gray-500 font-medium">días del mes de</div>
                    <div class="flex-1">
                        <div class="relative">
                            <select name="mes_declaracion"
                                    id="mes_declaracion"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all appearance-none bg-white">
                                <option value="">Seleccione mes</option>
                                <option value="Enero">Enero</option>
                                <option value="Febrero">Febrero</option>
                                <option value="Marzo">Marzo</option>
                                <option value="Abril">Abril</option>
                                <option value="Mayo">Mayo</option>
                                <option value="Junio">Junio</option>
                                <option value="Julio">Julio</option>
                                <option value="Agosto">Agosto</option>
                                <option value="Septiembre">Septiembre</option>
                                <option value="Octubre">Octubre</option>
                                <option value="Noviembre">Noviembre</option>
                                <option value="Diciembre">Diciembre</option>
                            </select>
                            <i class="fas fa-chevron-down text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                            <label class="absolute -top-2 left-3 bg-white px-1 text-xs text-gray-500">Mes</label>
                        </div>
                    </div>
                    <div class="text-gray-500 font-medium">del</div>
                    <div class="flex-1">
                        <div class="relative">
                            <input type="text" 
                                   name="anio_declaracion"
                                   id="anio_declaracion"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all bg-white"
                                   placeholder="AAAA"
                                   maxlength="4"
                                   inputmode="numeric">
                            <label class="absolute -top-2 left-3 bg-white px-1 text-xs text-gray-500">Año</label>
                        </div>
                    </div>
                </div>
                
                <!-- Fecha automática -->
                <div class="mt-4 flex items-center">
                    <button type="button" 
                            id="fecha-actual-btn"
                            class="inline-flex items-center px-3 py-1 text-xs bg-indigo-100 text-indigo-600 rounded-lg hover:bg-indigo-200 transition-all">
                        <i class="fas fa-calendar-check mr-1"></i>
                        Usar fecha actual
                    </button>
                    <span class="ml-3 text-sm text-gray-500" id="fecha-display"></span>
                </div>
            </div>
        </div>

        <!-- Firma y DNI -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Firma -->
            <div class="bg-gradient-to-r from-gray-50 to-white border border-gray-200 rounded-2xl p-6">
                <h4 class="text-lg font-semibold text-gray-700 mb-6 flex items-center">
                    <i class="fas fa-signature text-indigo-500 mr-2 text-sm"></i>
                    Firma del Trabajador
                </h4>
                
                <div class="space-y-4">
                    <!-- Área de firma -->
                    <div id="firma-container" 
                         class="border-3 border-dashed border-gray-300 rounded-2xl h-64 flex flex-col items-center justify-center p-6 bg-white hover:border-indigo-400 hover:bg-indigo-50 transition-all duration-300 cursor-pointer relative">
                        <div id="firma-placeholder" class="text-center">
                            <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-signature text-2xl text-indigo-500"></i>
                            </div>
                            <p class="text-gray-600 font-medium mb-1">Haga clic para firmar</p>
                            <p class="text-gray-500 text-sm">o arrastre su firma digitalizada</p>
                            <p class="text-gray-400 text-xs mt-2">Formatos: PNG, JPG, PDF (máx. 5MB)</p>
                        </div>
                        
                        <!-- Vista previa de firma -->
                        <div id="firma-preview" class="hidden w-full h-full flex items-center justify-center">
                            <img id="firma-preview-img" class="max-w-full max-h-48 object-contain" alt="Firma cargada">
                            <div id="firma-preview-text" class="text-lg font-signature text-gray-800 hidden"></div>
                        </div>
                        
                        <!-- Botones de acción -->
                        <div id="firma-actions" class="absolute bottom-4 right-4 space-x-2 hidden">
                            <button type="button" 
                                    id="limpiar-firma-btn"
                                    class="px-3 py-1 text-xs bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-all">
                                <i class="fas fa-trash-alt mr-1"></i>Eliminar
                            </button>
                        </div>
                    </div>
                    
                    <input type="file" 
                           id="firma_input" 
                           name="firma" 
                           class="hidden" 
                           accept="image/*,.pdf"
                           capture="environment">
                    
                    <!-- Firma digital -->
                    <div class="mt-4">
                        <button type="button" 
                                id="firma-digital-btn"
                                class="w-full px-4 py-3 border-2 border-dashed border-indigo-300 text-indigo-600 rounded-xl hover:bg-indigo-50 transition-all flex items-center justify-center">
                            <i class="fas fa-mouse-pointer mr-2"></i>
                            <span>Firmar digitalmente con el mouse/touch</span>
                        </button>
                    </div>
                    
                    <!-- Información de firma -->
                    <div class="mt-4 p-4 bg-blue-50 rounded-xl border border-blue-100">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-500 mt-1 mr-2"></i>
                            <p class="text-blue-700 text-sm">
                                Su firma debe coincidir con la de su documento de identidad. 
                                Puede firmar digitalmente o subir una imagen escaneada.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DNI y Huella -->
            <div class="space-y-8">
                <!-- DNI -->
                <div class="bg-gradient-to-r from-gray-50 to-white border border-gray-200 rounded-2xl p-6">
                    <h4 class="text-lg font-semibold text-gray-700 mb-6 flex items-center">
                        <i class="fas fa-id-card text-indigo-500 mr-2 text-sm"></i>
                        Verificación de Identidad
                    </h4>
                    
                    <div class="space-y-6">
                        <!-- DNI -->
                        <div>
                            <label for="dni_declaracion" class="block text-sm font-medium text-gray-700 mb-2">
                                Número de Documento de Identidad
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       id="dni_declaracion" 
                                       name="dni_declaracion"
                                       class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all bg-white"
                                       placeholder="Ingrese su DNI"
                                       maxlength="8"
                                       inputmode="numeric">
                                <i class="fas fa-hashtag text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Debe coincidir con el DNI proporcionado en la sección 1</p>
                        </div>

                        <!-- Huella -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Registro Biométrico</label>
                            <div id="huella-container" 
                                 class="border-3 border-dashed border-gray-300 rounded-2xl h-48 flex flex-col items-center justify-center p-6 bg-white hover:border-indigo-400 hover:bg-indigo-50 transition-all duration-300 cursor-pointer">
                                <div class="w-16 h-16 rounded-full bg-purple-100 flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-fingerprint text-2xl text-purple-500"></i>
                                </div>
                                <p class="text-gray-600 font-medium mb-1">Huella Digital</p>
                                <p class="text-gray-500 text-sm text-center">Área para registro biométrico opcional</p>
                                <p class="text-gray-400 text-xs mt-2">Se requiere lector biométrico habilitado</p>
                            </div>
                            
                            <!-- Estado huella -->
                            <div class="mt-4 flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-gray-300 mr-2" id="huella-status"></div>
                                    <span class="text-sm text-gray-600" id="huella-text">No registrada</span>
                                </div>
                                <button type="button" 
                                        id="simular-huella-btn"
                                        class="px-3 py-1 text-xs bg-purple-100 text-purple-600 rounded-lg hover:bg-purple-200 transition-all">
                                    <i class="fas fa-fingerprint mr-1"></i>Simular lectura
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Checkbox de aceptación -->
        <div class="mt-8 bg-gradient-to-r from-indigo-50 to-violet-50 border-2 border-indigo-200 rounded-2xl p-6">
            <div class="flex items-start">
                <div class="flex items-center h-5 mt-1">
                    <input type="checkbox" 
                           id="acepto_declaracion" 
                           name="acepto_declaracion"
                           class="h-5 w-5 text-indigo-600 rounded focus:ring-indigo-500 border-gray-300 required-checkbox"
                           required>
                </div>
                <label for="acepto_declaracion" class="ml-4 text-gray-800 cursor-pointer">
                    <div class="flex items-center mb-2">
                        <span class="font-bold text-lg text-indigo-700">✓ DECLARO Y ACEPTO</span>
                    </div>
                    <p class="text-gray-700 leading-relaxed">
                        He leído y comprendo completamente el texto de la declaración jurada anterior. 
                        Acepto que toda la información proporcionada en este formulario es veraz, completa y exacta. 
                        Entiendo que cualquier declaración falsa o información inexacta puede tener consecuencias legales, 
                        laborales y administrativas, incluyendo la terminación del vínculo laboral sin responsabilidad 
                        para la empresa y las acciones legales correspondientes.
                    </p>
                    <div class="mt-4 flex items-center text-sm text-indigo-600">
                        <i class="fas fa-shield-alt mr-2"></i>
                        <span>Esta aceptación tiene carácter vinculante y valor legal.</span>
                    </div>
                </label>
            </div>
            
            <!-- Validación -->
            <div class="mt-4 pt-4 border-t border-indigo-100">
                <div class="flex items-center" id="aceptacion-status">
                    <div class="w-3 h-3 rounded-full bg-red-400 mr-2"></div>
                    <span class="text-sm text-gray-600">Debe aceptar la declaración jurada para enviar el formulario</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Botones de acción -->
<div class="flex flex-col md:flex-row justify-between items-center pt-8 mt-8 border-t border-gray-200">
    <div class="mb-4 md:mb-0">
        <button type="button" 
                id="clear-form"
                class="inline-flex items-center px-6 py-3 border-2 border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all shadow-sm hover:shadow">
            <i class="fas fa-eraser mr-2"></i>
            <span>Limpiar Formulario</span>
        </button>
    </div>
    
    <div class="flex flex-col sm:flex-row gap-4">
        <button type="button" 
                id="save-draft"
                class="inline-flex items-center px-6 py-3 border-2 border-indigo-500 text-indigo-600 font-medium rounded-xl hover:bg-indigo-50 hover:border-indigo-600 transition-all shadow-sm hover:shadow">
            <i class="fas fa-save mr-2"></i>
            <span>Guardar Borrador</span>
        </button>
        
        <button type="submit" 
                id="submit-form"
                class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-medium rounded-xl hover:from-indigo-700 hover:to-violet-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:transform-none"
                disabled>
            <i class="fas fa-paper-plane mr-2"></i>
            <span>Enviar Formulario Completado</span>
        </button>
    </div>
</div>

<script>
    // Inicializar funcionalidad de declaración jurada
    document.addEventListener('DOMContentLoaded', function() {
        // Elementos importantes
        const aceptarCheckbox = document.getElementById('acepto_declaracion');
        const submitBtn = document.getElementById('submit-form');
        const fechaActualBtn = document.getElementById('fecha-actual-btn');
        const diaInput = document.getElementById('dia_declaracion');
        const mesSelect = document.getElementById('mes_declaracion');
        const anioInput = document.getElementById('anio_declaracion');
        const fechaDisplay = document.getElementById('fecha-display');
        const firmaContainer = document.getElementById('firma-container');
        const firmaInput = document.getElementById('firma_input');
        const firmaPreview = document.getElementById('firma-preview');
        const firmaPlaceholder = document.getElementById('firma-placeholder');
        const firmaActions = document.getElementById('firma-actions');
        const limpiarFirmaBtn = document.getElementById('limpiar-firma-btn');
        const firmaDigitalBtn = document.getElementById('firma-digital-btn');
        const huellaContainer = document.getElementById('huella-container');
        const simularHuellaBtn = document.getElementById('simular-huella-btn');
        const huellaStatus = document.getElementById('huella-status');
        const huellaText = document.getElementById('huella-text');
        const dniDeclaracion = document.getElementById('dni_declaracion');
        const clearFormBtn = document.getElementById('clear-form');
        const saveDraftBtn = document.getElementById('save-draft');
        const aceptacionStatus = document.getElementById('aceptacion-status');

        // Función para actualizar fecha actual
        function actualizarFechaActual() {
            const ahora = new Date();
            const dia = ahora.getDate().toString().padStart(2, '0');
            const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                          'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            const mes = meses[ahora.getMonth()];
            const anio = ahora.getFullYear();
            
            diaInput.value = dia;
            mesSelect.value = mes;
            anioInput.value = anio;
            
            actualizarFechaDisplay();
            actualizarEstadoEnvio();
        }

        // Función para actualizar display de fecha
        function actualizarFechaDisplay() {
            if (diaInput.value && mesSelect.value && anioInput.value) {
                fechaDisplay.textContent = `Fecha seleccionada: ${diaInput.value} de ${mesSelect.value} de ${anioInput.value}`;
                fechaDisplay.classList.remove('text-gray-400');
                fechaDisplay.classList.add('text-green-600', 'font-medium');
            } else {
                fechaDisplay.textContent = 'Complete la fecha de declaración';
                fechaDisplay.classList.remove('text-green-600', 'font-medium');
                fechaDisplay.classList.add('text-gray-400');
            }
        }

        // Función para actualizar estado del botón de envío
        function actualizarEstadoEnvio() {
            const fechaCompleta = diaInput.value && mesSelect.value && anioInput.value;
            const dniCompleto = dniDeclaracion.value && dniDeclaracion.value.length === 8;
            const firmaCargada = firmaInput.files && firmaInput.files.length > 0;
            const aceptado = aceptarCheckbox.checked;
            
            const camposRequeridos = fechaCompleta && dniCompleto && aceptado;
            
            // Actualizar estado del botón
            submitBtn.disabled = !camposRequeridos;
            
            // Actualizar estado de aceptación
            if (aceptado) {
                aceptacionStatus.innerHTML = `
                    <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                    <span class="text-sm text-green-600 font-medium">Declaración aceptada</span>
                `;
            } else {
                aceptacionStatus.innerHTML = `
                    <div class="w-3 h-3 rounded-full bg-red-400 mr-2"></div>
                    <span class="text-sm text-gray-600">Debe aceptar la declaración jurada para enviar el formulario</span>
                `;
            }
            
            return camposRequeridos;
        }

        // Función para cargar firma
        function cargarFirma(file) {
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (file.type.includes('image')) {
                        firmaPreview.innerHTML = `<img id="firma-preview-img" class="max-w-full max-h-48 object-contain" src="${e.target.result}" alt="Firma cargada">`;
                    } else if (file.type === 'application/pdf') {
                        firmaPreview.innerHTML = `
                            <div class="text-center">
                                <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-file-pdf text-2xl text-red-500"></i>
                                </div>
                                <p class="text-gray-700 font-medium">Documento PDF cargado</p>
                                <p class="text-gray-500 text-sm">${file.name}</p>
                            </div>
                        `;
                    }
                    
                    firmaPlaceholder.classList.add('hidden');
                    firmaPreview.classList.remove('hidden');
                    firmaActions.classList.remove('hidden');
                    
                    // Cambiar estilo del contenedor
                    firmaContainer.classList.remove('border-dashed', 'border-gray-300');
                    firmaContainer.classList.add('border-solid', 'border-green-400', 'bg-green-50');
                };
                reader.readAsDataURL(file);
            }
        }

        // Función para limpiar firma
        function limpiarFirma() {
            firmaInput.value = '';
            firmaPlaceholder.classList.remove('hidden');
            firmaPreview.classList.add('hidden');
            firmaActions.classList.add('hidden');
            
            // Restaurar estilo del contenedor
            firmaContainer.classList.remove('border-solid', 'border-green-400', 'bg-green-50');
            firmaContainer.classList.add('border-dashed', 'border-gray-300');
            
            actualizarEstadoEnvio();
        }

        // Función para simular firma digital (canvas)
        function iniciarFirmaDigital() {
            // En un sistema real, aquí se implementaría un canvas para firma digital
            alert('Función de firma digital - En un sistema real, aquí aparecería un canvas para firmar con mouse/touch.');
            
            // Simulación: crear firma digital falsa
            const firmaTexto = document.createElement('div');
            firmaTexto.id = 'firma-preview-text';
            firmaTexto.className = 'text-2xl font-signature text-gray-800 p-4';
            firmaTexto.textContent = 'Firma Digital';
            firmaTexto.style.fontFamily = "'Dancing Script', cursive";
            
            firmaPreview.innerHTML = '';
            firmaPreview.appendChild(firmaTexto);
            firmaPlaceholder.classList.add('hidden');
            firmaPreview.classList.remove('hidden');
            firmaActions.classList.remove('hidden');
            firmaPreviewText.classList.remove('hidden');
            
            // Cambiar estilo
            firmaContainer.classList.remove('border-dashed', 'border-gray-300');
            firmaContainer.classList.add('border-solid', 'border-blue-400', 'bg-blue-50');
            
            // Simular que se cargó un archivo
            const fakeFile = new File([''], 'firma-digital.png', { type: 'image/png' });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(fakeFile);
            firmaInput.files = dataTransfer.files;
            
            actualizarEstadoEnvio();
        }

        // Función para simular huella digital
        function simularHuella() {
            huellaStatus.classList.remove('bg-gray-300', 'bg-red-400', 'bg-yellow-400');
            huellaStatus.classList.add('bg-yellow-400');
            huellaText.textContent = 'Leyendo huella...';
            huellaText.classList.remove('text-gray-600', 'text-red-600', 'text-green-600');
            huellaText.classList.add('text-yellow-600');
            
            // Simulación de lectura
            setTimeout(() => {
                const exito = Math.random() > 0.3; // 70% de éxito
                
                if (exito) {
                    huellaStatus.classList.remove('bg-yellow-400');
                    huellaStatus.classList.add('bg-green-500');
                    huellaText.textContent = 'Huella registrada correctamente';
                    huellaText.classList.remove('text-yellow-600');
                    huellaText.classList.add('text-green-600');
                    
                    huellaContainer.classList.remove('border-dashed', 'border-gray-300');
                    huellaContainer.classList.add('border-solid', 'border-green-400', 'bg-green-50');
                    
                    // Animación de éxito
                    huellaContainer.style.transform = 'scale(1.02)';
                    setTimeout(() => {
                        huellaContainer.style.transform = 'scale(1)';
                    }, 300);
                } else {
                    huellaStatus.classList.remove('bg-yellow-400');
                    huellaStatus.classList.add('bg-red-400');
                    huellaText.textContent = 'Error en lectura. Intente nuevamente';
                    huellaText.classList.remove('text-yellow-600');
                    huellaText.classList.add('text-red-600');
                    
                    // Animación de error
                    huellaContainer.style.borderColor = '#f87171';
                    setTimeout(() => {
                        huellaContainer.style.borderColor = '';
                    }, 1000);
                }
            }, 1500);
        }

        // Event Listeners

        // Fecha actual
        fechaActualBtn.addEventListener('click', actualizarFechaActual);

        // Actualizar fecha display cuando cambien los inputs
        [diaInput, mesSelect, anioInput].forEach(input => {
            input.addEventListener('change', actualizarFechaDisplay);
            input.addEventListener('input', actualizarFechaDisplay);
        });

        // Firma - Click en contenedor
        firmaContainer.addEventListener('click', function() {
            firmaInput.click();
        });

        // Firma - Arrastrar y soltar
        firmaContainer.addEventListener('dragover', function(e) {
            e.preventDefault();
            firmaContainer.classList.add('border-indigo-500', 'bg-indigo-100');
        });

        firmaContainer.addEventListener('dragleave', function() {
            firmaContainer.classList.remove('border-indigo-500', 'bg-indigo-100');
        });

        firmaContainer.addEventListener('drop', function(e) {
            e.preventDefault();
            firmaContainer.classList.remove('border-indigo-500', 'bg-indigo-100');
            
            if (e.dataTransfer.files.length) {
                firmaInput.files = e.dataTransfer.files;
                cargarFirma(e.dataTransfer.files[0]);
                actualizarEstadoEnvio();
            }
        });

        // Firma - Cambio en input file
        firmaInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                cargarFirma(this.files[0]);
                actualizarEstadoEnvio();
            }
        });

        // Limpiar firma
        limpiarFirmaBtn.addEventListener('click', limpiarFirma);

        // Firma digital
        firmaDigitalBtn.addEventListener('click', iniciarFirmaDigital);

        // Huella digital
        huellaContainer.addEventListener('click', simularHuella);
        simularHuellaBtn.addEventListener('click', simularHuella);

        // Validación de DNI
        dniDeclaracion.addEventListener('input', function() {
            // Solo números, máximo 8 caracteres
            this.value = this.value.replace(/\D/g, '').slice(0, 8);
            
            // Validar formato
            if (this.value.length === 8) {
                this.classList.remove('border-red-300');
                this.classList.add('border-green-300');
            } else {
                this.classList.remove('border-green-300');
                this.classList.add('border-red-300');
            }
            
            actualizarEstadoEnvio();
        });

        // Aceptación de declaración
        aceptarCheckbox.addEventListener('change', actualizarEstadoEnvio);

        // Limpiar formulario
        clearFormBtn.addEventListener('click', function() {
            if (confirm('¿Está seguro de que desea limpiar todo el formulario? Se perderán todos los datos ingresados.')) {
                document.getElementById('personal-data-form').reset();
                limpiarFirma();
                actualizarFechaActual();
                actualizarEstadoEnvio();
                alert('Formulario limpiado exitosamente.');
            }
        });

        // Guardar borrador
        saveDraftBtn.addEventListener('click', function() {
            // En un sistema real, aquí se enviaría una petición AJAX para guardar
            const puedeGuardar = actualizarEstadoEnvio();
            
            if (puedeGuardar) {
                alert('✅ Borrador guardado exitosamente.\n\nPuede continuar más tarde desde donde lo dejó.');
            } else {
                alert('⚠️ Complete los campos requeridos antes de guardar:\n\n• Fecha de declaración\n• Número de DNI\n• Aceptación de declaración');
            }
        });

        // Envío del formulario
        document.getElementById('personal-data-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!actualizarEstadoEnvio()) {
                alert('❌ Complete todos los campos requeridos de la declaración jurada antes de enviar.');
                return;
            }
            
            if (confirm('¿Está seguro de enviar el formulario? Una vez enviado, no podrá modificar la información.')) {
                // Simulación de envío
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i><span>Enviando...</span>';
                
                setTimeout(() => {
                    alert('✅ Formulario enviado exitosamente.\n\nSu información ha sido registrada correctamente en el sistema.');
                    // En un sistema real, aquí se enviaría el formulario
                    // this.submit();
                }, 1500);
            }
        });

        // Inicialización
        actualizarFechaActual();
        actualizarEstadoEnvio();
        
        // Inicializar validación de fecha
        diaInput.addEventListener('input', function() {
            const dia = parseInt(this.value);
            if (dia < 1) this.value = 1;
            if (dia > 31) this.value = 31;
        });
        
        anioInput.addEventListener('input', function() {
            const anio = parseInt(this.value);
            if (anio < 2020) this.value = 2020;
            if (anio > 2030) this.value = 2030;
        });
    });
</script>

<style>
    /* Estilos personalizados para declaración jurada */
    .font-signature {
        font-family: 'Dancing Script', cursive, 'Brush Script MT', cursive;
    }
    
    /* Animaciones */
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    
    .pulse-animation {
        animation: pulse 2s infinite;
    }
    
    /* Estilos para checkbox personalizado */
    .required-checkbox:checked {
        background-color: #4f46e5;
        border-color: #4f46e5;
        background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e");
    }
    
    /* Estilos para áreas de arrastre */
    #firma-container:hover, #huella-container:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }
    
    /* Estilos para botones */
    #submit-form:not(:disabled):hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgba(79, 70, 229, 0.3);
    }
    
    /* Estilos responsivos */
    @media (max-width: 768px) {
        .flex-col.md\\:flex-row {
            flex-direction: column;
        }
        
        .md\\:flex-row.md\\:items-center {
            align-items: stretch;
        }
        
        .space-y-4.md\\:space-y-0 {
            margin-top: 0;
        }
        
        #firma-container, #huella-container {
            height: 200px;
        }
    }
    
    /* Estilos para estados */
    .border-green-400 {
        border-color: #34d399;
    }
    
    .bg-green-50 {
        background-color: #f0fdf4;
    }
    
    .border-blue-400 {
        border-color: #60a5fa;
    }
    
    .bg-blue-50 {
        background-color: #eff6ff;
    }
</style>