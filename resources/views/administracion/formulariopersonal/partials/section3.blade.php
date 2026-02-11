<!-- Sección 3: Información Familiar -->
<div class="form-section bg-white rounded-2xl shadow-lg p-6 md:p-8 mt-8 border border-gray-200">
    <!-- Encabezado de sección -->
    <div class="section-header mb-8 pb-6 border-b border-gray-200">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center">
                <div class="bg-gradient-to-r from-pink-500 to-rose-600 w-12 h-12 rounded-xl flex items-center justify-center mr-4 shadow-md">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl md:text-2xl font-bold text-gray-800">Información Familiar</h3>
                    <p class="text-gray-500 mt-1 text-sm md:text-base">Agregue sus familiares directos (opcional)</p>
                </div>
            </div>
            <span class="bg-pink-50 text-pink-700 px-4 py-2 rounded-full text-sm font-semibold border border-pink-100">
                Sección 3
            </span>
        </div>
    </div>

    <!-- Instrucciones -->
    <div class="mb-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-gradient-to-r from-pink-50 to-rose-50 border border-pink-100 rounded-xl p-5">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-pink-500 text-lg mt-1 mr-3"></i>
                <div>
                    <h4 class="font-medium text-pink-800 mb-1">Instrucciones</h4>
                    <p class="text-pink-700 text-sm">Esta sección es <span class="font-bold">opcional</span>. Agregue sus familiares directos utilizando el botón "Agregar Familiar".</p>
                </div>
            </div>
        </div>
        
        <div class="flex justify-end items-center">
            <button type="button" id="add-familiar-btn" 
                class="inline-flex items-center px-4 py-3 bg-gradient-to-r from-pink-500 to-rose-500 text-white rounded-xl hover:from-pink-600 hover:to-rose-600 transition-all shadow-md hover:shadow-lg">
                <i class="fas fa-plus mr-2"></i>
                <span>Agregar Familiar</span>
            </button>
        </div>
    </div>

    <!-- Tabla de información familiar -->
    <div class="overflow-hidden rounded-2xl border border-gray-200 shadow-sm">
        <!-- Encabezados para desktop -->
        <div class="hidden md:grid md:grid-cols-8 bg-gradient-to-r from-pink-50 to-rose-100 border-b border-pink-200">
            @php
                $headers = [
                    'Parentesco',
                    'Apellidos y Nombres',
                    'N° Documento',
                    'Ocupación',
                    'Sexo',
                    'Fecha Nacimiento',
                    'Domicilio Actual',
                    'Acciones'
                ];
            @endphp
            @foreach ($headers as $header)
                <div class="px-4 py-4 text-left">
                    <span class="text-sm font-semibold text-pink-800 uppercase tracking-wide">{{ $header }}</span>
                </div>
            @endforeach
        </div>

        <!-- Contenedor de familiares -->
        <div class="divide-y divide-gray-100" id="familia-container">
            <!-- Aquí se agregarán dinámicamente los familiares -->
        </div>
        
        <!-- Mensaje cuando no hay familiares -->
        <div id="no-familiares-message" class="p-12 text-center bg-gray-50">
            <div class="flex flex-col items-center justify-center">
                <div class="w-20 h-20 rounded-full bg-pink-100 flex items-center justify-center mb-4">
                    <i class="fas fa-users text-pink-500 text-3xl"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-800 mb-2">No hay familiares agregados</h4>
                <p class="text-gray-500 mb-4">Haga clic en el botón "Agregar Familiar" para comenzar.</p>
                <button type="button" id="empty-add-btn" 
                    class="inline-flex items-center px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition-all">
                    <i class="fas fa-plus mr-2"></i>
                    Agregar Familiar
                </button>
            </div>
        </div>
    </div>

    <!-- Nota informativa -->
    <div class="mt-8 bg-gradient-to-r from-gray-50 to-white border border-gray-200 rounded-xl p-5">
        <div class="flex items-start">
            <div class="bg-green-100 rounded-lg p-3 mr-4">
                <i class="fas fa-check-circle text-green-600 text-lg"></i>
            </div>
            <div>
                <h4 class="font-medium text-gray-800 mb-1">Importante</h4>
                <p class="text-gray-600 text-sm">
                    Esta sección es <span class="font-bold text-green-600">COMPLETAMENTE OPCIONAL</span>. 
                    La barra de progreso mostrará 100% automáticamente si no tiene familiares directos. 
                    Complete solo si desea registrar información familiar.
                </p>
            </div>
        </div>
    </div>

    <!-- Indicador de progreso -->
    <div class="mt-6 pt-6 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                <span class="text-sm text-gray-600">Sección opcional - 100% si no tiene familiares</span>
            </div>
            <div class="flex items-center">
                <div class="w-32 bg-gray-200 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full transition-all duration-300" style="width: 100%" id="familia-progress"></div>
                </div>
                <span class="ml-3 text-sm font-medium text-gray-700" id="familia-percentage">100%</span>
            </div>
        </div>
    </div>
</div>

<!-- Template para familiar (oculto) - VERSIÓN CORREGIDA CON NAMES -->
<template id="familiar-template">
    <!-- Fila para desktop -->
    <div class="familiar-row hidden md:grid md:grid-cols-8 items-center p-4 hover:bg-gray-50 transition-colors duration-200 bg-white">
        <!-- Parentesco con Select - SOLO 3 OPCIONES -->
        <div class="px-4 py-4">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-lg bg-pink-100 flex items-center justify-center mr-3 parentesco-icon">
                    <i class="fas fa-user text-pink-600"></i>
                </div>
                <div class="relative w-full">
                    <select name="familiares[ID][parentesco]" 
                            class="parentesco-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-all appearance-none bg-white text-sm">
                        <option value="">Seleccione parentesco</option>
                        <option value="conyuge">Cónyuge</option>
                        <option value="concubino">Concubin@</option>
                        <option value="hijo">Hijo</option>
                    </select>
                    <i class="fas fa-chevron-down text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                </div>
            </div>
        </div>

        <!-- Apellidos y Nombres -->
        <div class="px-4 py-4">
            <div class="relative">
                <input type="text" 
                       name="familiares[ID][nombres]"
                       class="campo-familiar nombres-input w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-all bg-white" 
                       placeholder="Nombre completo">
            </div>
        </div>

        <!-- N° Documento -->
        <div class="px-4 py-4">
            <div class="relative">
                <input type="text" 
                       name="familiares[ID][documento]"
                       class="campo-familiar documento-input w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-all bg-white" 
                       placeholder="N° de documento">
            </div>
        </div>

        <!-- Ocupación -->
        <div class="px-4 py-4">
            <div class="relative">
                <input type="text" 
                       name="familiares[ID][ocupacion]"
                       class="campo-familiar ocupacion-input w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-all bg-white" 
                       placeholder="Ocupación actual">
            </div>
        </div>

        <!-- Sexo -->
        <div class="px-4 py-4">
            <div class="relative">
                <select name="familiares[ID][sexo]"
                        class="sexo-select w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-all appearance-none bg-white">
                    <option value="">Seleccione</option>
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                </select>
                <i class="fas fa-venus-mars text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
            </div>
        </div>

        <!-- Fecha Nacimiento -->
        <div class="px-4 py-4">
            <div class="relative">
                <input type="text" 
                       name="familiares[ID][fecha_nacimiento]"
                       class="flatpickr-familia campo-familiar fecha-input w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-all bg-white" 
                       placeholder="Seleccione fecha">
                <i class="fas fa-birthday-cake text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
            </div>
        </div>

        <!-- Domicilio Actual -->
        <div class="px-4 py-4">
            <div class="relative">
                <input type="text" 
                       name="familiares[ID][domicilio]"
                       class="campo-familiar domicilio-input w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-all bg-white" 
                       placeholder="Dirección actual">
            </div>
        </div>

        <!-- Acciones -->
        <div class="px-4 py-4">
            <div class="flex items-center space-x-2">
                <button type="button" class="remove-familiar-btn text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-all" title="Eliminar">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Tarjeta para móvil -->
    <div class="familiar-card md:hidden bg-white border border-gray-200 rounded-xl p-5 mb-4 shadow-sm">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-lg bg-pink-100 flex items-center justify-center mr-3 parentesco-icon-mobile">
                    <i class="fas fa-user text-pink-600"></i>
                </div>
                <div class="relative w-full">
                    <select name="familiares[ID][parentesco]" 
                            class="parentesco-select-mobile w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-all appearance-none bg-white text-sm">
                        <option value="">Seleccione parentesco</option>
                        <option value="conyuge">Cónyuge</option>
                        <option value="concubino">Concubin@</option>
                        <option value="hijo">Hijo</option>
                    </select>
                    <i class="fas fa-chevron-down text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                </div>
            </div>
            <button type="button" class="remove-familiar-btn text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-all">
                <i class="fas fa-trash"></i>
            </button>
        </div>

        <div class="space-y-4">
            <!-- Nombres -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Apellidos y Nombres</label>
                <div class="relative">
                    <input type="text" 
                           name="familiares[ID][nombres]"
                           class="campo-familiar nombres-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-all bg-white" 
                           placeholder="Nombre completo">
                    <i class="fas fa-user text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- Documento -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">N° Documento</label>
                    <div class="relative">
                        <input type="text" 
                               name="familiares[ID][documento]"
                               class="campo-familiar documento-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-all bg-white" 
                               placeholder="N° DNI">
                        <i class="fas fa-id-card text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                    </div>
                </div>

                <!-- Sexo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sexo</label>
                    <div class="relative">
                        <select name="familiares[ID][sexo]"
                                class="sexo-select-mobile w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-all appearance-none bg-white">
                            <option value="">Seleccionar</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                        <i class="fas fa-venus-mars text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                    </div>
                </div>
            </div>

            <!-- Ocupación -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Ocupación</label>
                <div class="relative">
                    <input type="text" 
                           name="familiares[ID][ocupacion]"
                           class="campo-familiar ocupacion-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-all bg-white" 
                           placeholder="Profesión u oficio">
                    <i class="fas fa-briefcase text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                </div>
            </div>

            <!-- Fecha Nacimiento -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Nacimiento</label>
                <div class="relative">
                    <input type="text" 
                           name="familiares[ID][fecha_nacimiento]"
                           class="flatpickr-familia-mobile campo-familiar fecha-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-all bg-white" 
                           placeholder="Seleccione fecha">
                    <i class="fas fa-calendar text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                </div>
            </div>

            <!-- Domicilio -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Domicilio Actual</label>
                <div class="relative">
                    <input type="text" 
                           name="familiares[ID][domicilio]"
                           class="campo-familiar domicilio-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-all bg-white" 
                           placeholder="Dirección donde vive">
                    <i class="fas fa-home text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    // Inicializar Flatpickr para fechas de familiares
    document.addEventListener('DOMContentLoaded', function() {
        // Configuración para Flatpickr familiar
        const flatpickrFamiliaOptions = {
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
                calculateFamiliaProgress();
            }
        };

        // Variables
        let familiarCount = 0;
        const maxFamiliares = 20;

        // Función para actualizar el ícono según el parentesco - SOLO 3 OPCIONES
        function updateParentescoIcon(selectElement, iconElement) {
            const value = selectElement.value;
            let icon = 'fa-user';
            let bgColor = 'bg-pink-100';
            let textColor = 'text-pink-600';
            
            switch(value) {
                case 'conyuge':
                    icon = 'fa-ring';
                    bgColor = 'bg-pink-100';
                    textColor = 'text-pink-600';
                    break;
                case 'concubino':
                    icon = 'fa-heart';
                    bgColor = 'bg-rose-100';
                    textColor = 'text-rose-600';
                    break;
                case 'hijo':
                    icon = 'fa-child';
                    bgColor = 'bg-blue-100';
                    textColor = 'text-blue-600';
                    break;
                default:
                    icon = 'fa-user';
                    bgColor = 'bg-pink-100';
                    textColor = 'text-pink-600';
            }
            
            iconElement.innerHTML = `<i class="fas ${icon} ${textColor}"></i>`;
            iconElement.className = `w-10 h-10 rounded-lg ${bgColor} flex items-center justify-center mr-3`;
        }

        // Función para agregar un nuevo familiar - VERSIÓN CORREGIDA
        function addFamiliar() {
            const template = document.getElementById('familiar-template');
            const familiaContainer = document.getElementById('familia-container');
            const noFamiliaresMsg = document.getElementById('no-familiares-message');
            
            // Clonar el template
            const desktopRow = template.content.querySelector('.familiar-row').cloneNode(true);
            const mobileCard = template.content.querySelector('.familiar-card').cloneNode(true);
            
            // Asignar ID único
            const familiarId = familiarCount;
            desktopRow.setAttribute('data-familiar-id', `familiar_${familiarId}`);
            mobileCard.setAttribute('data-familiar-id', `familiar_${familiarId}`);
            
            // Reemplazar [ID] en TODOS los campos con el índice real
            desktopRow.innerHTML = desktopRow.innerHTML.replace(/\[ID\]/g, `[${familiarId}]`);
            mobileCard.innerHTML = mobileCard.innerHTML.replace(/\[ID\]/g, `[${familiarId}]`);
            
            // Configurar event listeners para el ícono del parentesco (desktop)
            const parentescoSelect = desktopRow.querySelector(`select[name="familiares[${familiarId}][parentesco]"]`);
            const iconContainer = desktopRow.querySelector('.parentesco-icon');
            
            if (parentescoSelect && iconContainer) {
                updateParentescoIcon(parentescoSelect, iconContainer);
                
                parentescoSelect.addEventListener('change', function() {
                    updateParentescoIcon(this, iconContainer);
                    calculateFamiliaProgress();
                });
            }
            
            // Configurar event listeners para el ícono del parentesco (mobile)
            const parentescoSelectMobile = mobileCard.querySelector(`select[name="familiares[${familiarId}][parentesco]"]`);
            const iconContainerMobile = mobileCard.querySelector('.parentesco-icon-mobile');
            
            if (parentescoSelectMobile && iconContainerMobile) {
                updateParentescoIcon(parentescoSelectMobile, iconContainerMobile);
                
                parentescoSelectMobile.addEventListener('change', function() {
                    updateParentescoIcon(this, iconContainerMobile);
                    calculateFamiliaProgress();
                });
            }
            
            // Configurar event listeners para campos
            desktopRow.querySelectorAll('.campo-familiar, select').forEach(field => {
                field.addEventListener('input', calculateFamiliaProgress);
                field.addEventListener('change', calculateFamiliaProgress);
            });
            
            mobileCard.querySelectorAll('.campo-familiar, select').forEach(field => {
                field.addEventListener('input', calculateFamiliaProgress);
                field.addEventListener('change', calculateFamiliaProgress);
            });
            
            // Configurar botón eliminar con SweetAlert
            desktopRow.querySelector('.remove-familiar-btn').addEventListener('click', function() {
                eliminarFamiliarConSweetAlert(`familiar_${familiarId}`);
            });
            
            mobileCard.querySelector('.remove-familiar-btn').addEventListener('click', function() {
                eliminarFamiliarConSweetAlert(`familiar_${familiarId}`);
            });
            
            // Inicializar Flatpickr
            const fechaInput = desktopRow.querySelector(`input[name="familiares[${familiarId}][fecha_nacimiento]"]`);
            if (fechaInput) {
                flatpickr(fechaInput, flatpickrFamiliaOptions);
            }
            
            const fechaInputMobile = mobileCard.querySelector(`input[name="familiares[${familiarId}][fecha_nacimiento]"]`);
            if (fechaInputMobile) {
                flatpickr(fechaInputMobile, flatpickrFamiliaOptions);
            }
            
            // Agregar al DOM
            familiaContainer.appendChild(desktopRow);
            familiaContainer.appendChild(mobileCard);
            
            familiarCount++;
            
            // Ocultar mensaje de no familiares
            if (noFamiliaresMsg) {
                noFamiliaresMsg.style.display = 'none';
            }
            
            // Deshabilitar botón si se alcanza el máximo
            if (familiarCount >= maxFamiliares) {
                document.getElementById('add-familiar-btn').disabled = true;
                document.getElementById('add-familiar-btn').classList.add('opacity-50', 'cursor-not-allowed');
            }
            
            calculateFamiliaProgress();
            
            // Toastr de éxito al agregar
            if (typeof toastr !== 'undefined') {
                toastr.success('Familiar agregado correctamente', '✅ Éxito');
            }
        }

        // Función para eliminar familiar con SweetAlert y Toastr
        function eliminarFamiliarConSweetAlert(familiarId) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción eliminará al familiar de la lista',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Proceder con la eliminación
                    const desktopRow = document.querySelector(`[data-familiar-id="${familiarId}"]`);
                    const mobileCard = document.querySelector(`.familiar-card[data-familiar-id="${familiarId}"]`);
                    
                    if (desktopRow) desktopRow.remove();
                    if (mobileCard) mobileCard.remove();
                    
                    familiarCount--;
                    
                    // Habilitar botón de agregar
                    document.getElementById('add-familiar-btn').disabled = false;
                    document.getElementById('add-familiar-btn').classList.remove('opacity-50', 'cursor-not-allowed');
                    
                    // Mostrar mensaje de no familiares si está vacío
                    if (familiarCount === 0) {
                        document.getElementById('no-familiares-message').style.display = 'block';
                    }
                    
                    calculateFamiliaProgress();
                    
                    // Toastr de éxito al eliminar
                    if (typeof toastr !== 'undefined') {
                        toastr.success('Familiar eliminado correctamente', '✅ Eliminado');
                    }
                }
            });
        }

        // Función para calcular progreso de sección familiar
        function calculateFamiliaProgress() {
            const desktopRows = document.querySelectorAll('.familiar-row');
            const mobileCards = document.querySelectorAll('.familiar-card');
            
            if (desktopRows.length === 0) {
                // No hay familiares
                document.getElementById('familia-percentage').textContent = '100%';
                document.getElementById('familia-progress').style.width = '100%';
                document.getElementById('familia-progress').classList.remove('bg-yellow-500', 'bg-red-500', 'bg-yellow-400');
                document.getElementById('familia-progress').classList.add('bg-green-500');
                return;
            }
            
            let totalCompletos = 0;
            
            desktopRows.forEach(row => {
                const nombre = row.querySelector('.nombres-input');
                const parentesco = row.querySelector('.parentesco-select');
                
                let camposLlenos = 0;
                
                if (nombre && nombre.value && nombre.value.trim() !== '') {
                    camposLlenos++;
                }
                if (parentesco && parentesco.value && parentesco.value !== '') {
                    camposLlenos++;
                }
                
                // Considerar completo si tiene al menos nombre y parentesco
                if (camposLlenos >= 2) {
                    totalCompletos++;
                }
            });
            
            let porcentaje = desktopRows.length > 0 ? Math.round((totalCompletos / desktopRows.length) * 100) : 100;
            
            // Actualizar barra de progreso
            document.getElementById('familia-percentage').textContent = `${porcentaje}%`;
            document.getElementById('familia-progress').style.width = `${porcentaje}%`;
            
            const progressBar = document.getElementById('familia-progress');
            progressBar.classList.remove('bg-green-500', 'bg-yellow-500', 'bg-red-500', 'bg-green-400', 'bg-yellow-400', 'bg-gray-300');
            
            if (porcentaje === 100) {
                progressBar.classList.add('bg-green-500');
            } else if (porcentaje >= 75) {
                progressBar.classList.add('bg-green-400');
            } else if (porcentaje >= 50) {
                progressBar.classList.add('bg-yellow-500');
            } else if (porcentaje >= 25) {
                progressBar.classList.add('bg-yellow-400');
            } else {
                progressBar.classList.add('bg-red-500');
            }
        }

        // Event listeners para botones de agregar
        document.getElementById('add-familiar-btn').addEventListener('click', addFamiliar);
        
        const emptyAddBtn = document.getElementById('empty-add-btn');
        if (emptyAddBtn) {
            emptyAddBtn.addEventListener('click', addFamiliar);
        }

        // Inicializar el progreso (100% al inicio porque no hay familiares)
        calculateFamiliaProgress();
    });
</script>

<style>
.campo-familiar {
    transition: all 0.3s ease;
}

#familia-progress {
    transition: width 0.5s ease-in-out, background-color 0.5s ease;
}

.familiar-row,
.familiar-card {
    transition: all 0.3s ease;
}

/* Estilos para los selects */
select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236B7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
    background-position: right 0.75rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
    appearance: none;
}

/* Animaciones */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.familiar-row,
.familiar-card {
    animation: fadeIn 0.3s ease;
}
</style>