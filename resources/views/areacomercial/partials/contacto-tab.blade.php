<h3 class="text-xl font-semibold mb-6 text-gray-700 flex items-center">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
    </svg>
    Editar Contacto
</h3>

<form id="formContacto" class="space-y-6" action="{{ route('contactos.update', $seguimiento->idContacto) }}" method="POST">
    @csrf
    @method('PUT')
    <input type="hidden" name="idSeguimiento" value="{{ $seguimiento->idSeguimiento }}">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Tipo de Documento -->
        <div class="form-group">
            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Documento <span class="text-red-500">*</span></label>
            <div class="flex">
                <div class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md px-3 font-semibold border ltr:border-r-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 dark:text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                    </svg>
                </div>
                <select name="tipo_documento" class="form-select ltr:rounded-l-none text-white-dark h-[42px]" required>
                    <option value="">-- Seleccione --</option>
                    @foreach($documentos as $doc)
                        <option value="{{ $doc->idTipoDocumento }}" {{ $contacto->tipo_documento == $doc->idTipoDocumento ? 'selected' : '' }}>
                            {{ $doc->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Número de Documento -->
        <div class="form-group">
            <label class="block text-sm font-medium text-gray-700 mb-1">Número de Documento <span class="text-red-500">*</span></label>
            <div class="flex space-x-2">
                <div class="flex flex-grow">
                    <div class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md px-3 font-semibold border ltr:border-r-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 dark:text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" name="numero_documento" class="form-input ltr:rounded-l-none h-[46px]" value="{{ old('numero_documento', $contacto->numero_documento) }}" placeholder="Ingrese número de documento" required>
                </div>
            </div>
        </div>
    </div>

    <!-- Nombre Completo -->
    <div class="form-group">
        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo <span class="text-red-500">*</span></label>
        <div class="flex">
            <div class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md px-3 font-semibold border ltr:border-r-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 dark:text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                </svg>
            </div>
            <input type="text" name="nombre_completo" class="form-input ltr:rounded-l-none h-[42px]" value="{{ old('nombre_completo', $contacto->nombre_completo) }}" placeholder="Ingrese nombre completo" required>
        </div>
    </div>

    <!-- Cargo -->
    <div class="form-group">
        <label class="block text-sm font-medium text-gray-700 mb-1">Cargo</label>
        <div class="flex">
            <div class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md px-3 font-semibold border ltr:border-r-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 dark:text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd" />
                    <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z" />
                </svg>
            </div>
            <input type="text" name="cargo" class="form-input ltr:rounded-l-none h-[42px]" value="{{ old('cargo', $contacto->cargo) }}" placeholder="Ingrese cargo">
        </div>
    </div>

    <!-- Correo -->
    <div class="form-group">
        <label class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
        <div class="flex">
            <div class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md px-3 font-semibold border ltr:border-r-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                </svg>
            </div>
            <input type="email" name="correo" class="form-input ltr:rounded-l-none h-[42px]" value="{{ old('correo', $contacto->correo_electronico) }}" placeholder="ejemplo@correo.com">
        </div>
    </div>

    <!-- Teléfono -->
    <div class="form-group">
        <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono o WhatsApp</label>
        <div class="flex">
            <div class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md px-3 font-semibold border ltr:border-r-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                </svg>
            </div>
            <input type="text" name="telefono" class="form-input ltr:rounded-l-none h-[42px]" value="{{ old('telefono', $contacto->telefono_whatsapp) }}" placeholder="+51987654321">
        </div>
    </div>

    <!-- Nivel de decisión -->
    <div class="form-group">
        <label class="block text-sm font-medium text-gray-700 mb-1">Nivel de Decisión</label>
        <div class="flex">
            <div class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md px-3 font-semibold border ltr:border-r-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd" />
                </svg>
            </div>
            <select name="nivel_decision" class="form-select ltr:rounded-l-none text-white-dark h-[42px]">
                <option value="">-- Seleccione --</option>
                @foreach($niveles as $nivel)
                    <option value="{{ $nivel->id }}" {{ $contacto->nivel_decision_id == $nivel->id ? 'selected' : '' }}>
                        {{ $nivel->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

        <div id="formMessage" class="hidden"></div>


    <!-- Botones -->
    <div class="pt-2 flex flex-wrap justify-center gap-4">
        <button type="submit" class="btn btn-primary flex items-center justify-center px-6 h-[46px]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
            </svg>
            Actualizar Contacto
        </button>

        <button type="button" id="btnLimpiarFormularioContacto" class="btn btn-dark flex items-center justify-center px-6 h-[46px]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M6 6a1 1 0 011.414 0L10 8.586 12.586 6a1 1 0 011.414 1.414L11.414 10l2.586 2.586a1 1 0 01-1.414 1.414L10 11.414 7.414 14a1 1 0 01-1.414-1.414L8.586 10 6 7.414A1 1 0 016 6z" clip-rule="evenodd" />
            </svg>
            Limpiar
        </button>
    </div>
</form>





<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formContacto');
    
    if (form) {
        // Crear contenedor de mensajes si no existe
        let messageContainer = document.getElementById('formMessage');
        if (!messageContainer) {
            messageContainer = document.createElement('div');
            messageContainer.id = 'formMessage';
            messageContainer.className = 'mb-4';
            form.insertBefore(messageContainer, form.lastElementChild);
        }
        
        // IMPORTANTE: Remover todos los event listeners anteriores
        form.removeEventListener('submit', handleSubmit);
        form.addEventListener('submit', handleSubmit);
        
        async function handleSubmit(e) {
            // Prevenir el envío normal del formulario
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            
            // Mostrar estado de carga
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Procesando...
            `;
            submitBtn.disabled = true;
            
            // Ocultar mensajes anteriores
            messageContainer.classList.add('hidden');
            
            try {
                const formData = new FormData(form);
                
                // Debug: ver qué datos se están enviando
                console.log('Enviando datos:');
                for (let pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }
                
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();
                console.log('Respuesta del servidor:', data);

                if (!response.ok) {
                    throw data;
                }

                // Mostrar mensaje de éxito
                showMessage('success', data.message || 'Contacto actualizado correctamente.', messageContainer);
                
                // Opcional: Actualizar los valores del formulario con los datos actualizados
                if (data.data) {
                    updateFormValues(form, data.data);
                }

            } catch (error) {
                console.error('Error completo:', error);
                let errorMsg = 'Error inesperado al actualizar el contacto.';
                
                if (error.message) {
                    errorMsg = error.message;
                } else if (error.errors) {
                    errorMsg = Object.values(error.errors).flat().join('<br>');
                }
                
                showMessage('error', errorMsg, messageContainer);
            } finally {
                // Restaurar el botón
                submitBtn.innerHTML = originalBtnText;
                submitBtn.disabled = false;
            }
            
            // Asegurarse de que no se envíe el formulario
            return false;
        }
    }
    
    // Función para mostrar mensajes
    function showMessage(type, message, container) {
        if (!container) return;
        
        const bgColor = type === 'success' ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800';
        const iconColor = type === 'success' ? 'text-green-400' : 'text-red-400';
        const iconPath = type === 'success' 
            ? 'M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z'
            : 'M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z';
        
        container.innerHTML = `
            <div class="p-4 rounded-md ${bgColor}">
                <div class="flex items-center">
                    <svg class="h-5 w-5 ${iconColor}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="${iconPath}" clip-rule="evenodd" />
                    </svg>
                    <span class="ml-2">${message}</span>
                </div>
            </div>
        `;
        container.classList.remove('hidden');
        
        // Ocultar después de 5 segundos si es éxito
        if (type === 'success') {
            setTimeout(() => {
                container.classList.add('hidden');
            }, 5000);
        }
    }
    
    // Función para actualizar los valores del formulario con los datos actualizados
    function updateFormValues(form, data) {
        // Actualizar los campos del formulario con los valores actualizados de la BD
        const fields = {
            'tipo_documento': data.tipo_documento,
            'numero_documento': data.numero_documento,
            'nombre_completo': data.nombre_completo,
            'cargo': data.cargo,
            'correo': data.correo_electronico,
            'telefono': data.telefono_whatsapp,
            'nivel_decision': data.nivel_decision_id
        };
        
        Object.entries(fields).forEach(([fieldName, value]) => {
            const field = form.querySelector(`[name="${fieldName}"]`);
            if (field && value !== null && value !== undefined) {
                field.value = value;
            }
        });
    }
    
    // Botón limpiar formulario
    const btnLimpiar = document.getElementById('btnLimpiarFormularioContacto');
    if (btnLimpiar) {
        btnLimpiar.addEventListener('click', function() {
            const form = document.getElementById('formContacto');
            if (form) {
                form.reset();
                // Limpiar mensajes
                const messageContainer = document.getElementById('formMessage');
                if (messageContainer) {
                    messageContainer.classList.add('hidden');
                }
            }
        });
    }
});
</script>