let counter = 0;
let currentFormType = null;
let editingId = null; // <- id que se está editando (si existe)
let isUpdate = false; // <- modo actualización

function createNew(type) {
    renderForm(type); // limpio
}

let tipoDocumentoOptions = [];
let fuenteCaptacionOptions = [];
let nivelDecisionOptions = [];

// Función para cargar las opciones al iniciar
async function loadSelectOptions() {
    try {
        // Obtener tipos de documento
        const tipoDocResponse = await fetch('/api/tipos-documento');
        tipoDocumentoOptions = await tipoDocResponse.json();

        // Obtener fuentes de captación
        const fuenteResponse = await fetch('/api/fuentes-captacion');
        fuenteCaptacionOptions = await fuenteResponse.json();

        // Obtener niveles de decisión
        const nivelResponse = await fetch('/api/niveles-decision');
        nivelDecisionOptions = await nivelResponse.json();
    } catch (error) {
        console.error('Error cargando opciones:', error);
    }
}

// Llamar a la función cuando se cargue la página
document.addEventListener('DOMContentLoaded', loadSelectOptions);

function renderForm(type, data = {}) {
    // Ocultar solo el texto del mensaje, no el botón
    const noData = document.getElementById('no-data-message');
    if (noData) {
        noData.classList.add('hidden'); // ahora esto solo oculta el texto, no el botón
    }

    const formContainer = document.getElementById('create-form-container');
    formContainer.classList.remove('hidden');

    currentFormType = type;

    const isEmp = type === 'empresa';
    const title = isUpdate ? (isEmp ? 'Actualizar Empresa' : 'Actualizar Contacto') : isEmp ? 'Crear Nueva Empresa' : 'Crear Nuevo Contacto';
    const submitText = isUpdate ? 'Actualizar' : isEmp ? 'Guardar Empresa' : 'Guardar Contacto';

    let formHTML = '';

    if (isEmp) {
        formHTML = `
      <form onsubmit="submitForm(event)" class="bg-white p-6 rounded-lg shadow-md">
        <h4 class="text-lg font-semibold mb-4">${title}</h4>

        <div class="form-group mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">Nombre o Razón Social <span class="text-red-500">*</span></label>
          <div class="flex">
            <div class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed]">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" /></svg>
            </div>
            <input type="text" name="razon_social" class="form-input ltr:rounded-l-none rtl:rounded-r-none h-[42px] flex-grow" required
                   value="${data.razonSocial ?? ''}" placeholder="Ingrese nombre o razón social">
          </div>
        </div>

        <div class="form-group mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">RUC <span class="text-red-500">*</span></label>
          <div class="flex space-x-2">
            <div class="flex flex-grow">
              <div class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" /></svg>
              </div>
              <input type="text" name="ruc" class="form-input ltr:rounded-l-none rtl:rounded-r-none h-[42px] flex-grow" required
                     value="${data.ruc ?? ''}" placeholder="Ingrese RUC">
            </div>
            <button type="button" onclick="buscarRuc()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center transition-colors h-[42px]">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
              Buscar
            </button>
          </div>
        </div>

        <div class="form-group mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">Rubro o Giro Comercial <span class="text-red-500">*</span></label>
          <div class="flex">
            <div class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed]">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" /></svg>
            </div>
            <input type="text" name="rubro" class="form-input ltr:rounded-l-none rtl:rounded-r-none h-[42px] flex-grow" required
                   value="${data.rubro ?? ''}" placeholder="Ingrese rubro o giro comercial">
          </div>
        </div>

        <div class="form-group mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">Ubicación Geográfica</label>
          <div class="flex">
            <div class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed]">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
            </div>
            <input type="text" name="ubicacion" class="form-input ltr:rounded-l-none rtl:rounded-r-none h-[42px] flex-grow"
                   value="${data.ubicacion ?? ''}" placeholder="Ingrese ubicación geográfica">
          </div>
        </div>

        <div class="form-group mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">Fuente de Captación <span class="text-red-500">*</span></label>
          <div class="flex">
            <div class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed]">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" /></svg>
            </div>
            <select name="fuente_captacion" class="form-select ltr:rounded-l-none rtl:rounded-r-none h-[42px] flex-grow" required>
            <option value="">-- Seleccione --</option>
            ${fuenteCaptacionOptions
                .map(
                    (v) =>
                        `<option value="${v.id}" ${data.fuenteCaptacion == v.id ? 'selected' : ''}>
                ${v.nombre}
              </option>`,
                )
                .join('')}
          </select>
          </div>
        </div>

        <div class="flex justify-end space-x-3 mt-6">
          <button type="button" onclick="cancelCreate()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition-colors">Cancelar</button>
          <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition-colors">${submitText}</button>
        </div>
      </form>
    `;
    } else {
        formHTML = `
      <form onsubmit="submitForm(event)" class="bg-white p-6 rounded-lg shadow-md">
        <h4 class="text-lg font-semibold mb-4">${title}</h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <div class="form-group">
            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Documento aqui estoy <span class="text-red-500">*</span></label>
            <div class="flex">
              <div class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" /></svg>
              </div>
               <select name="tipo_documento_id" class="form-select ltr:rounded-l-none rtl:rounded-r-none h-[42px] flex-grow" required>
              <option value="">-- Seleccione --</option>
              ${tipoDocumentoOptions
                  .map(
                      (v) =>
                          `<option value="${v.idTipoDocumento}" ${data.tipodocumento == v.idTipoDocumento ? 'selected' : ''}>
                  ${v.nombre}
                </option>`,
                  )
                  .join('')}
            </select>
            </div>
          </div>

          <div class="form-group">
            <label class="block text-sm font-medium text-gray-700 mb-1">Número de Documento <span class="text-red-500">*</span></label>
            <div class="flex space-x-2">
              <div class="flex flex-grow">
                <div class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed]">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" /></svg>
                </div>
                <input type="text" name="numero_documento" class="form-input ltr:rounded-l-none rtl:rounded-r-none h-[42px] flex-grow" required
                       value="${data.numeroDocumento ?? ''}" placeholder="Ingrese número de documento">
              </div>
              <button type="button" onclick="buscarCliente()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center transition-colors h-[42px]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                Buscar
              </button>
            </div>
          </div>
        </div>

        <div class="form-group mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo <span class="text-red-500">*</span></label>
          <div class="flex">
            <div class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed]">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" /></svg>
            </div>
            <input type="text" name="nombre_completo" class="form-input ltr:rounded-l-none rtl:rounded-r-none h-[42px] flex-grow" required
                   value="${data.nombreCompleto ?? ''}" placeholder="Ingrese nombre completo">
          </div>
        </div>

        <div class="form-group mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">Cargo</label>
          <div class="flex">
            <div class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed]">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd" /><path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z" /></svg>
            </div>
            <input type="text" name="cargo" class="form-input ltr:rounded-l-none rtl:rounded-r-none h-[42px] flex-grow"
                   value="${data.cargo ?? ''}" placeholder="Ingrese cargo">
          </div>
        </div>

        <div class="form-group mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
          <div class="flex">
            <div class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed]">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" /><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" /></svg>
            </div>
            <input type="email" name="correo" class="form-input ltr:rounded-l-none rtl:rounded-r-none h-[42px] flex-grow"
                   value="${data.correo ?? ''}" placeholder="ejemplo@correo.com">
          </div>
        </div>

        <div class="form-group mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono o WhatsApp</label>
          <div class="flex">
            <div class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed]">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" /></svg>
            </div>
            <input type="text" name="telefono" class="form-input ltr:rounded-l-none rtl:rounded-r-none h-[42px] flex-grow"
                   value="${data.telefono ?? ''}" placeholder="+51987654321">
          </div>
        </div>

        <div class="form-group mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">Nivel de Decisión</label>
          <div class="flex">
            <div class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed]">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd" /></svg>
            </div>
          <select name="nivel_decision" class="form-select ltr:rounded-l-none rtl:rounded-r-none h-[42px] flex-grow">
            <option value="">-- Seleccione --</option>
            ${nivelDecisionOptions
                .map(
                    (v) =>
                        `<option value="${v.id}" ${data.nivelDecision == v.id ? 'selected' : ''}>
                ${v.nombre}
              </option>`,
                )
                .join('')}
          </select>
          </div>
        </div>

        <div class="flex justify-end space-x-3 mt-6">
          <button type="button" onclick="cancelCreate()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition-colors">Cancelar</button>
          <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition-colors">${submitText}</button>
        </div>
      </form>
    `;
    }

    formContainer.innerHTML = formHTML;
}

function submitForm(e) {
    e.preventDefault();
    if (currentFormType === 'empresa') submitEmpresa(e);
    else if (currentFormType === 'contacto') submitContacto(e);
}

function cancelCreate() {
    const formContainer = document.getElementById('create-form-container');
    formContainer.classList.add('hidden');
    formContainer.innerHTML = '';
    currentFormType = null;
    editingId = null;
    isUpdate = false;

    // Limpiar el formulario (si existe)
    const form = document.querySelector('form');
    if (form) form.reset();

    // Verificar si hay datos en la lista
    const dataList = document.getElementById('data-list');
    if (dataList.children.length === 0) {
        const noData = document.getElementById('no-data-message');
        if (noData) noData.classList.remove('hidden');
    }
}

/* ========== EMPRESA ========== */
async function submitEmpresa(event) {
    event.preventDefault();
    const form = event.target;

    const empresaData = {
        idSeguimiento: parseInt(idSeguimientoHidden.value), // Convertir a número si es necesario
        nombre_razon_social: form.razon_social.value.trim(),
        ruc: form.ruc.value.trim(),
        giro_comercial: form.rubro.value.trim(),
        ubicacion_geografica: form.ubicacion.value.trim(),
        fuente_captacion_id: form.fuente_captacion.value,
    };

    try {
        let response;
        if (isUpdate && editingId) {
            // Actualizar empresa existente
            response = await fetch(`/api/v1/empresasForm/${editingId.split('-')[1]}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(empresaData),
            });
        } else {
            // Crear nueva empresa
            response = await fetch('/api/v1/empresasForm', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(empresaData),
            });
        }

        const data = await response.json();

        if (data.success) {
            // Actualizar la interfaz según sea necesario
            if (isUpdate && editingId) {
                // Actualizar tarjeta existente
                const item = document.getElementById(editingId);
                if (item) {
                    item.dataset.razon = empresaData.nombre_razon_social;
                    item.dataset.ruc = empresaData.ruc;
                    item.dataset.rubro = empresaData.giro_comercial;
                    item.dataset.ubicacion = empresaData.ubicacion_geografica;
                    item.dataset.fuente = empresaData.fuente_captacion_id;

                    item.querySelector('strong').textContent = empresaData.nombre_razon_social;
                    const lines = item.querySelectorAll('.text-sm p');
                    lines[0].innerHTML = `<span class="font-medium">RUC:</span> ${empresaData.ruc}`;
                    lines[1].innerHTML = `<span class="font-medium">Rubro:</span> ${empresaData.giro_comercial}`;
                    lines[2].innerHTML = `<span class="font-medium">Ubicación:</span> ${empresaData.ubicacion_geografica || 'No especificado'}`;

                    const fuenteNombre = fuenteCaptacionOptions.find((f) => f.id == empresaData.fuente_captacion_id)?.nombre || empresaData.fuente_captacion_id;
                    lines[3].innerHTML = `<span class="font-medium">Fuente:</span> ${fuenteNombre}`;
                }
            } else {
                // Crear nueva tarjeta
                const id = `empresa-${data.data.id}`;
                const fuenteNombre = fuenteCaptacionOptions.find((f) => f.id == empresaData.fuente_captacion_id)?.nombre || empresaData.fuente_captacion_id;

                const item = `
      <div id="${id}"  class="empresa-card p-4 bg-yellow-100 rounded shadow mb-4"
           data-idpersona="${data.data.id}" 
           data-razon="${empresaData.nombre_razon_social}" 
           data-ruc="${empresaData.ruc}" 
           data-rubro="${empresaData.giro_comercial}" 
           data-ubicacion="${empresaData.ubicacion_geografica}" 
           data-fuente="${empresaData.fuente_captacion_id}"
           class="p-4 bg-yellow-100 rounded shadow mb-4">
        <div class="flex justify-between items-start">
          <div>
            <strong class="text-lg">${empresaData.nombre_razon_social}</strong>
            <div class="text-sm text-gray-600 mt-1">
              <p><span class="font-medium">RUC:</span> ${empresaData.ruc}</p>
              <p><span class="font-medium">Rubro:</span> ${empresaData.giro_comercial}</p>
              <p><span class="font-medium">Ubicación:</span> ${empresaData.ubicacion_geografica || 'No especificado'}</p>
              <p><span class="font-medium">Fuente:</span> ${fuenteNombre}</p>
            </div>
          </div>
          <div class="flex space-x-2">
            <button onclick="editEmpresa('${id}')" class="text-blue-500 hover:text-blue-700" title="Editar">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg>
            </button>
            <button onclick="deleteItem('${id}', 'empresa')" class="text-red-500 hover:text-red-700" title="Eliminar">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
            </button>
            <button onclick="seleccionar('${id}', 'empresa')" class="text-green-600 hover:text-green-800" title="Seleccionar">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
            </button>
          </div>
        </div>
      </div>`;
                document.getElementById('data-list').insertAdjacentHTML('beforeend', item);
            }

            cancelCreate();
        } else {
            throw new Error(data.message || 'Error al guardar la empresa');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al guardar la empresa: ' + error.message);
    }
}

// Función para cargar empresas/contactos existentes
async function loadExistingData(idSeguimiento) {
    try {
        // Cachea el contenedor y usa SIEMPRE esta referencia
        const dataList = document.getElementById('data-list');
        if (!dataList || !document.body.contains(dataList)) return;

        // (opcional) limpiar para evitar duplicados al volver al tab
        dataList.innerHTML = '';

        // Trae todo en paralelo para reducir ventana de race
        const [empresasResp, contactosResp] = await Promise.all([
            fetch(`/api/v1/empresasForm/seguimiento/${idSeguimiento}`),
            fetch(`/api/v1/contactosForm/seguimiento/${idSeguimiento}`),
        ]);

        const [empresas, contactos] = await Promise.all([empresasResp.json(), contactosResp.json()]);

        // Si el usuario cambió de tab y el nodo salió del DOM, aborta
        if (!dataList.isConnected) return;

        let html = '';

        // Empresas
        for (const empresa of empresas) {
            const id = `empresa-${empresa.id}`;
            const fuenteNombre = fuenteCaptacionOptions.find((f) => f.id == empresa.fuente_captacion_id)?.nombre || empresa.fuente_captacion_id;

            html += `
        <div id="${id}" data-idpersona="${empresa.id}" class="empresa-card p-4 bg-yellow-100 rounded shadow mb-4" data-razon="${empresa.nombre_razon_social}" data-ruc="${empresa.ruc}" 
             data-rubro="${empresa.giro_comercial}" data-ubicacion="${empresa.ubicacion_geografica}" 
             data-fuente="${empresa.fuente_captacion_id}"
             class="p-4 bg-yellow-100 rounded shadow mb-4">
          <div class="flex justify-between items-start">
            <div>
              <strong class="text-lg">${empresa.nombre_razon_social}</strong>
              <div class="text-sm text-gray-600 mt-1">
                <p><span class="font-medium">RUC:</span> ${empresa.ruc}</p>
                <p><span class="font-medium">Rubro:</span> ${empresa.giro_comercial}</p>
                <p><span class="font-medium">Ubicación:</span> ${empresa.ubicacion_geografica || 'No especificado'}</p>
                <p><span class="font-medium">Fuente:</span> ${fuenteNombre}</p>
              </div>
            </div>
            <div class="flex space-x-2">
              <button onclick="editEmpresa('${id}')" class="text-blue-500 hover:text-blue-700" title="Editar">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg>
              </button>
              <button onclick="deleteItem('${id}', 'empresa')" class="text-red-500 hover:text-red-700" title="Eliminar">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
              </button>
              <button onclick="seleccionar('${id}', 'empresa')" class="text-green-600 hover:text-green-800" title="Seleccionar">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
              </button>
            </div>
          </div>
        </div>`;
        }

        // Contactos
        for (const contacto of contactos) {
            const id = `contacto-${contacto.id}`;
            const tipoDocNombre = tipoDocumentoOptions.find((t) => t.idTipoDocumento == contacto.tipo_documento_id)?.nombre || contacto.tipo_documento_id;
            const nivelDecisionNombre = nivelDecisionOptions.find((n) => n.id == contacto.nivel_decision_id)?.nombre || contacto.nivel_decision_id;

            html += `
        <div id="${id}" class="contacto-card p-4 bg-blue-100 rounded shadow mb-4" data-idpersona="${contacto.id}" data-tipo-documento="${contacto.tipo_documento_id}" data-numero-documento="${contacto.numero_documento}"
             data-nombre-completo="${contacto.nombre_completo}" data-cargo="${contacto.cargo}" data-correo="${contacto.correo_electronico}"
             data-telefono="${contacto.telefono_whatsapp}" data-nivel-decision="${contacto.nivel_decision_id}"
             class="p-4 bg-blue-100 rounded shadow mb-4">
          <div class="flex justify-between items-start">
            <div>
              <strong class="text-lg">${contacto.nombre_completo}</strong>
              <div class="text-sm text-gray-600 mt-1">
                <p><span class="font-medium">Documento:</span> ${tipoDocNombre} ${contacto.numero_documento}</p>
                <p><span class="font-medium">Cargo:</span> ${contacto.cargo || 'No especificado'}</p>
                <p><span class="font-medium">Correo:</span> ${contacto.correo_electronico || 'No especificado'}</p>
                <p><span class="font-medium">Teléfono:</span> ${contacto.telefono_whatsapp || 'No especificado'}</p>
                <p><span class="font-medium">Nivel de decisión:</span> ${nivelDecisionNombre || 'No especificado'}</p>
              </div>
            </div>
            <div class="flex space-x-2">
              <button onclick="editContacto('${id}')" class="text-blue-500 hover:text-blue-700" title="Editar">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg>
              </button>
              <button onclick="deleteItem('${id}', 'contacto')" class="text-red-500 hover:text-red-700" title="Eliminar">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
              </button>
              <button onclick="seleccionar('${id}', 'contacto')" class="text-green-600 hover:text-green-800" title="Seleccionar">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
              </button>
            </div>
          </div>
        </div>`;
        }

        // Inserta TODO de una sola vez (menos parpadeo y menos riesgo)
        if (dataList.isConnected) {
            dataList.insertAdjacentHTML('beforeend', html);
        }

        // Oculta/ muestra el mensaje "no-data"
        const noData = document.getElementById('no-data-message');
        if (noData) {
            noData.classList.toggle('hidden', empresas.length + contactos.length > 0);
        }
    } catch (error) {
        console.error('Error cargando datos existentes:', error);
    }
}

function editEmpresa(id) {
    const item = document.getElementById(id);
    if (!item) return;

    editingId = id;
    isUpdate = true;

    const data = {
        razonSocial: item.dataset.razon ?? item.querySelector('strong').textContent,
        ruc: item.dataset.ruc ?? item.querySelector('.text-sm p:nth-child(1)').textContent.replace('RUC: ', ''),
        rubro: item.dataset.rubro ?? item.querySelector('.text-sm p:nth-child(2)').textContent.replace('Rubro: ', ''),
        ubicacion: item.dataset.ubicacion ?? item.querySelector('.text-sm p:nth-child(3)').textContent.replace('Ubicación: ', ''),
        fuenteCaptacion: item.dataset.fuente ?? item.querySelector('.text-sm p:nth-child(4)').textContent.replace('Fuente: ', '').split(' - ')[0], // Asume que el ID está antes de un guión
    };
    renderForm('empresa', data);
}

/* ========== CONTACTO ========== */
async function submitContacto(event) {
    event.preventDefault();
    const form = event.target;

    // Asegúrate de que el select tiene el nombre correcto (tipo_documento_id)
    const tipoDocumentoSelect = form.elements['tipo_documento_id'];
    if (!tipoDocumentoSelect || !tipoDocumentoSelect.value) {
        alert('Por favor seleccione un tipo de documento');
        return;
    }

    const contactoData = {
        idSeguimiento: parseInt(idSeguimientoHidden.value), // Convertir a número si es necesario
        tipo_documento_id: parseInt(tipoDocumentoSelect.value),
        numero_documento: form.elements['numero_documento'].value.trim(),
        nombre_completo: form.elements['nombre_completo'].value.trim(),
        cargo: form.elements['cargo']?.value.trim() || '',
        correo_electronico: form.elements['correo']?.value.trim() || '',
        telefono_whatsapp: form.elements['telefono']?.value.trim() || '',
        nivel_decision_id: form.elements['nivel_decision']?.value ? parseInt(form.elements['nivel_decision'].value) : null,
    };

    // Depuración - verifica todos los valores
    console.log('Datos del contacto a enviar:', contactoData);

    try {
        let response;
        if (isUpdate && editingId) {
            // Actualizar contacto existente
            response = await fetch(`/api/v1/contactosForm/${editingId.split('-')[1]}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(contactoData),
            });
        } else {
            // Crear nuevo contacto
            response = await fetch('/api/v1/contactosForm', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(contactoData),
            });
        }

        // Resto del código permanece igual...
        const data = await response.json();

        if (data.success) {
            // Actualizar la interfaz según sea necesario
            if (isUpdate && editingId) {
                // Actualizar tarjeta existente
                const item = document.getElementById(editingId);
                if (item) {
                    item.dataset.tipoDocumento = contactoData.tipo_documento_id;
                    item.dataset.numeroDocumento = contactoData.numero_documento;
                    item.dataset.nombreCompleto = contactoData.nombre_completo;
                    item.dataset.cargo = contactoData.cargo;
                    item.dataset.correo = contactoData.correo_electronico;
                    item.dataset.telefono = contactoData.telefono_whatsapp;
                    item.dataset.nivelDecision = contactoData.nivel_decision_id;

                    item.querySelector('strong').textContent = contactoData.nombre_completo;
                    const lines = item.querySelectorAll('.text-sm p');

                    const tipoDocNombre =
                        tipoDocumentoOptions.find((t) => t.idTipoDocumento == contactoData.tipo_documento_id)?.nombre || contactoData.tipo_documento_id;
                    lines[0].innerHTML = `<span class="font-medium">Documento:</span> ${tipoDocNombre} ${contactoData.numero_documento}`;
                    lines[1].innerHTML = `<span class="font-medium">Cargo:</span> ${contactoData.cargo || 'No especificado'}`;
                    lines[2].innerHTML = `<span class="font-medium">Correo:</span> ${contactoData.correo_electronico || 'No especificado'}`;
                    lines[3].innerHTML = `<span class="font-medium">Teléfono:</span> ${contactoData.telefono_whatsapp || 'No especificado'}`;

                    const nivelDecisionNombre =
                        nivelDecisionOptions.find((n) => n.id == contactoData.nivel_decision_id)?.nombre || contactoData.nivel_decision_id;
                    lines[4].innerHTML = `<span class="font-medium">Nivel de decisión:</span> ${nivelDecisionNombre || 'No especificado'}`;
                }
            } else {
                // Crear nueva tarjeta
                const id = `contacto-${data.data.id}`;
                const tipoDocNombre =
                    tipoDocumentoOptions.find((t) => t.idTipoDocumento == contactoData.tipo_documento_id)?.nombre || contactoData.tipo_documento_id;
                const nivelDecisionNombre = nivelDecisionOptions.find((n) => n.id == contactoData.nivel_decision_id)?.nombre || contactoData.nivel_decision_id;

                const item = `
      <div id="${id}" class="contacto-card p-4 bg-blue-100 rounded shadow mb-4"
           data-idpersona="${data.data.id}" 
           data-tipo-documento="${contactoData.tipo_documento_id}" 
           data-numero-documento="${contactoData.numero_documento}"
           data-nombre-completo="${contactoData.nombre_completo}" 
           data-cargo="${contactoData.cargo}" 
           data-correo="${contactoData.correo_electronico}"
           data-telefono="${contactoData.telefono_whatsapp}" 
           data-nivel-decision="${contactoData.nivel_decision_id}"
           class="p-4 bg-blue-100 rounded shadow mb-4">
        <div class="flex justify-between items-start">
          <div>
            <strong class="text-lg">${contactoData.nombre_completo}</strong>
            <div class="text-sm text-gray-600 mt-1">
              <p><span class="font-medium">Documento:</span> ${tipoDocNombre} ${contactoData.numero_documento}</p>
              <p><span class="font-medium">Cargo:</span> ${contactoData.cargo || 'No especificado'}</p>
              <p><span class="font-medium">Correo:</span> ${contactoData.correo_electronico || 'No especificado'}</p>
              <p><span class="font-medium">Teléfono:</span> ${contactoData.telefono_whatsapp || 'No especificado'}</p>
              <p><span class="font-medium">Nivel de decisión:</span> ${nivelDecisionNombre || 'No especificado'}</p>
            </div>
          </div>
          <div class="flex space-x-2">
            <button onclick="editContacto('${id}')" class="text-blue-500 hover:text-blue-700" title="Editar">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg>
            </button>
            <button onclick="deleteItem('${id}', 'contacto')" class="text-red-500 hover:text-red-700" title="Eliminar">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
            </button>
            <button onclick="seleccionar('${id}', 'contacto')" class="text-green-600 hover:text-green-800" title="Seleccionar">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
            </button>
          </div>
        </div>
      </div>`;
                document.getElementById('data-list').insertAdjacentHTML('beforeend', item);
            }

            cancelCreate();
        } else {
            throw new Error(data.message || 'Error al guardar el contacto');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al guardar el contacto: ' + error.message);
    }
}
function editContacto(id) {
    const item = document.getElementById(id);
    if (!item) return;

    editingId = id;
    isUpdate = true;

    // Preferir dataset; si no existe, parsear del DOM
    const p = (n) => item.querySelector(`.text-sm p:nth-child(${n})`).textContent;
    const docText = p(1).replace('Documento:', '').trim(); // e.g. "DNI 12345678"
    const [tipoDoc = '', nroDoc = ''] = docText.split(/\s+/);

    const data = {
        tipoDocumento:
            item.dataset.tipoDocumento || tipoDocumentoOptions.find((t) => t.nombre.toLowerCase() === tipoDoc.toLowerCase())?.idTipoDocumento || tipoDoc,
        numeroDocumento: item.dataset.numeroDocumento || nroDoc,
        nombreCompleto: item.dataset.nombreCompleto || item.querySelector('strong').textContent,
        cargo:
            item.dataset.cargo ||
            p(2)
                .replace('Cargo:', '')
                .trim()
                .replace(/^No especificado$/i, ''),
        correo:
            item.dataset.correo ||
            p(3)
                .replace('Correo:', '')
                .trim()
                .replace(/^No especificado$/i, ''),
        telefono:
            item.dataset.telefono ||
            p(4)
                .replace('Teléfono:', '')
                .trim()
                .replace(/^No especificado$/i, ''),
        nivelDecision:
            item.dataset.nivelDecision ||
            nivelDecisionOptions.find((n) => n.nombre === p(5).replace('Nivel de decisión:', '').trim())?.id ||
            p(5).replace('Nivel de decisión:', '').trim(),
    };
    renderForm('contacto', data);
}

/* ========== comunes ========== */
async function deleteItem(id, type) {
    if (!confirm('¿Eliminar este elemento?')) return;

    try {
        const response = await fetch(`/api/v1/${type}sForm/${id.split('-')[1]}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
        });

        const data = await response.json();

        if (data.success) {
            const el = document.getElementById(id);
            if (el) el.remove();

            const dataList = document.getElementById('data-list');
            if (dataList.children.length === 0) {
                const noData = document.getElementById('no-data-message');
                if (noData) noData.classList.remove('hidden');
            }
        } else {
            throw new Error(data.message || 'Error al eliminar');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al eliminar: ' + error.message);
    }
}

// Función que se ejecuta cuando el DOM está cargado

async function cargarSeleccionPrevia() {
    const idSeguimiento = document.getElementById('idSeguimientoHidden')?.value;
    if (!idSeguimiento) return;

    try {
        const response = await fetch(`/obtener-seleccion/${idSeguimiento}`);
        const data = await response.json();

        if (response.ok && data.success && data.data) {
            const idPersona = data.data.idpersona;
            // Espera que el DOM esté listo
            marcarSeleccionVisual(idPersona);
        }
    } catch (error) {
        console.error('Error al cargar selección previa:', error);
    }
}
function marcarSeleccionVisual(idPersona, intentos = 15) {
    const el = document.querySelector(`[data-idpersona="${idPersona}"]`);

    if (el) {
        // 1. Limpiar TODAS las tarjetas seleccionadas previamente
        document.querySelectorAll('[data-idpersona]').forEach((card) => {
            card.classList.remove('border-2', 'border-green-500');

            // Quitar también todos los textos "Seleccionado"
            card.querySelectorAll('.texto-seleccionado').forEach(e => e.remove());
        });

        // 2. Agregar estilos a la tarjeta actual seleccionada
        el.classList.add('border-2', 'border-green-500');

        const acciones = el.querySelector('.acciones');
        if (acciones) {
            el.insertBefore(checkIcon, acciones);
        } else {
            el.appendChild(checkIcon); // fallback
        }

    } else if (intentos > 0) {
        // Reintenta si aún no está en el DOM
        setTimeout(() => marcarSeleccionVisual(idPersona, intentos - 1), 200);
    }
}


async function seleccionar(id, tipo) {
    const item = document.getElementById(id);
    if (!item) return;

    // Debug: Ver todos los atributos del elemento
    console.log('Elemento seleccionado:', item);
    console.log('Todos los data attributes:', item.dataset);
    console.log('HTML completo del elemento:', item.outerHTML);

    // Resaltar visualmente el elemento seleccionado
    const prev = document.querySelector('.border-2.border-green-500');
    if (prev) prev.classList.remove('border-2', 'border-green-500');
    item.classList.add('border-2', 'border-green-500');

    const prospectoId = id.split('-')[1];
    console.log('prospectoId extraído:', prospectoId);

    const idPersona = parseInt(item.dataset.idpersona, 10);
    console.log('item.dataset.idpersona (raw):', item.dataset.idpersona);
    console.log('idPersona parseado:', idPersona);

    if (!idPersona || isNaN(idPersona)) {
        console.error('No se encontró el ID de persona o es inválido:', item.dataset.idpersona);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se encontró el ID de persona para este elemento',
            toast: true,
            position: 'top-end',
            timer: 2500,
            showConfirmButton: false,
        });

        return;
    }

    const idSeguimiento = document.getElementById('idSeguimientoHidden')?.value;
    if (!idSeguimiento) {
        console.error('No se encontró el ID de seguimiento');
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se encontró el ID de seguimiento',
            toast: true,
            position: 'top-end',
            timer: 2500,
            showConfirmButton: false,
        });

        return;
    }

    const tipoProspecto = tipo === 'empresa' ? 'empresa' : 'contacto';

    console.log('Datos a enviar:', {
        idseguimiento: idSeguimiento,
        idprospecto: prospectoId,
        tipo_prospecto: tipoProspecto,
        idpersona: idPersona,
    });

    try {
        const response = await fetch('/seleccionar-seguimiento', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                idseguimiento: idSeguimiento,
                idprospecto: prospectoId,
                tipo_prospecto: tipoProspecto,
                idpersona: idPersona,
            }),
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Error al guardar la selección');
        }

        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Selección guardada correctamente',
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true,
        });
    } catch (error) {
        console.error('Error al guardar la selección:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error al guardar',
            text: error.message,
            toast: true,
            position: 'top-end',
            timer: 2500,
            showConfirmButton: false,
        });
    }
}

document.addEventListener('DOMContentLoaded', async function () {
    // Solo carga las opciones de los selects al inicio
    await loadSelectOptions();

    cargarSeleccionPrevia();
    // No cargamos datos aquí, porque el DOM aún no tiene el #data-list
});

/* Stub búsquedas */
function buscarRuc() {
    /* implementar AJAX aquí */
}
function buscarCliente() {
    /* implementar AJAX aquí */
}

let hasInitializedNoDataTab = false;

window.initNoDataTab = async function () {
    const list = document.getElementById('data-list');
    if (!list || !document.body.contains(list)) return;

    const idSeguimiento = document.getElementById('idSeguimientoHidden')?.value;

    // Solo vuelve a pedir selects si están vacíos (evita refetch innecesario)
    if (!tipoDocumentoOptions.length || !fuenteCaptacionOptions.length || !nivelDecisionOptions.length) {
        await loadSelectOptions();
    }

    await loadExistingData(idSeguimiento);
};
