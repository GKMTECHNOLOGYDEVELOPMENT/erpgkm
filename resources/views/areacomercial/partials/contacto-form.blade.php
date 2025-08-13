<h3 class="text-xl font-semibold mb-6 text-gray-700 flex items-center">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
    </svg>
    {{ isset($contacto) ? 'Editar Contacto' : 'Nuevo Contacto' }}
</h3>

<form id="formContacto" class="space-y-6" 
      action="{{ isset($contacto) ? route('contactos.update', $contacto->id) : route('contactos.store') }}" 
      method="POST">
    @csrf
    @if(isset($contacto))
        @method('PUT')
    @endif
 <!-- Nombre Completo -->
    <div class="form-group">
        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo <span class="text-red-500">*</span></label>
        <div class="flex">
            <div class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md px-3 font-semibold border ltr:border-r-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 dark:text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                </svg>
            </div>
            <input type="text" name="nombre_completo" class="form-input ltr:rounded-l-none h-[42px]" value="" placeholder="Ingrese nombre completo" required>
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
            <input type="text" name="cargo" class="form-input ltr:rounded-l-none h-[42px]" value="" placeholder="Ingrese cargo">
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
            <input type="email" name="correo" class="form-input ltr:rounded-l-none h-[42px]" value="" placeholder="ejemplo@correo.com">
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
            <input type="text" name="telefono" class="form-input ltr:rounded-l-none h-[42px]" value="" placeholder="+51987654321">
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
                         <option value="">-- 2 --</option>

            </select>
        </div>
    </div>

    
    <div class="pt-2 flex flex-wrap justify-center gap-4">
        <button type="submit" class="btn btn-primary flex items-center justify-center px-6 h-[46px]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
            </svg>
            {{ isset($contacto) ? 'Actualizar' : 'Guardar' }} Contacto
        </button>

        <button type="button" onclick="document.getElementById('contactoFormContainer').classList.add('hidden')" 
                class="btn btn-dark flex items-center justify-center px-6 h-[46px]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M6 6a1 1 0 011.414 0L10 8.586 12.586 6a1 1 0 011.414 1.414L11.414 10l2.586 2.586a1 1 0 01-1.414 1.414L10 11.414 7.414 14a1 1 0 01-1.414-1.414L8.586 10 6 7.414A1 1 0 016 6z" clip-rule="evenodd" />
            </svg>
            Cancelar
        </button>
    </div>
</form>


