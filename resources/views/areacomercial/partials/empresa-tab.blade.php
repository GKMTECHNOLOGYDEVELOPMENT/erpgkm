<h3 class="text-xl font-semibold mb-6 text-gray-700 flex items-center">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24"
        stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
    </svg>
    Editar Empresa
</h3>

@isset($empresa)
    <form id="formEmpresa" class="space-y-6" action="{{ route('empresasformem.update', $empresa->id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="idSeguimiento" value="{{ $seguimiento->idSeguimiento }}">

       <!-- Nombre o Razón Social -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre o Razón Social <span class="text-red-500">*</span></label>
                            <div class="flex">
                                <div
                                    class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-gray-600 dark:text-gray-400" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                               <input type="text" name="razon_social" placeholder="Ingrese nombre o razón social"
    class="form-input ltr:rounded-l-none rtl:rounded-r-none h-[46px]"
    value="{{ old('razon_social', $empresa->nombre_razon_social) }}" required>

                            </div>
                        </div>

                        <!-- RUC -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">RUC <span class="text-red-500">*</span></label>
                            <div class="flex space-x-2">
                                <div class="flex flex-grow">
                                    <div
                                        class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5 text-gray-600 dark:text-gray-400" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                  <input type="text" name="ruc" id="inputRuc" placeholder="Ingrese RUC"
    class="form-input ltr:rounded-l-none rtl:rounded-r-none h-[46px]"
    value="{{ old('ruc', $empresa->ruc) }}" required>

                                </div>

                                <!-- Botón Buscar -->
                                <button type="button" id="btnBuscarRuc"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center transition-colors h-[46px]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Buscar
                                </button>

                                <!-- Botón Limpiar -->
                                <button type="button" id="btnLimpiarRuc" class="btn btn-info">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M6 6a1 1 0 011.414 0L10 8.586 12.586 6a1 1 0 011.414 1.414L11.414 10l2.586 2.586a1 1 0 01-1.414 1.414L10 11.414 7.414 14a1 1 0 01-1.414-1.414L8.586 10 6 7.414A1 1 0 016 6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Limpiar
                                </button>
                            </div>
                        </div>

                        <!-- Rubro o Giro Comercial -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rubro o Giro Comercial <span class="text-red-500">*</span></label>
                            <div class="flex">
                                <div
                                    class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-gray-600 dark:text-gray-400" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" name="rubro" placeholder="Ingrese rubro o giro comercial"
    class="form-input ltr:rounded-l-none rtl:rounded-r-none h-[46px]"
    value="{{ old('rubro', $empresa->giro_comercial) }}" required>

                            </div>
                        </div>

                        <!-- Ubicación Geográfica -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ubicación Geográfica <span class="text-red-500">*</span></label>
                            <div class="flex">
                                <div
                                    class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-gray-600 dark:text-gray-400" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" name="ubicacion" placeholder="Ingrese ubicación geográfica"
    class="form-input ltr:rounded-l-none rtl:rounded-r-none h-[46px]"
    value="{{ old('ubicacion', $empresa->ubicacion_geografica) }}">

                            </div>
                        </div>

                        <!-- Fuente de Captación -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fuente de Captación <span class="text-red-500">*</span></label>
                            <div class="flex">
                                <div
                                    class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-gray-600 dark:text-gray-400" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                    </svg>
                                </div>
                            <select name="fuente_captacion_id" id="fuenteCaptacion"
    class="form-select ltr:rounded-l-none rtl:rounded-r-none text-white-dark h-[46px] w-full" required>
    <option value="">-- Seleccione --</option>
    @foreach ($fuentes as $fuente)
        <option value="{{ $fuente->id }}"
            {{ $empresa->fuente_captacion_id == $fuente->id ? 'selected' : '' }}>
            {{ $fuente->nombre }}
        </option>
    @endforeach
</select>

                            </div>
                        </div>


        <!-- Resto del formulario de empresa... -->

        <div class="pt-2 flex space-x-4">
            <button type="submit"
                class="btn-primary text-white font-medium py-2 px-4 rounded-md transition-colors duration-300 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path
                        d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h5a2 2 0 012 2v7a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h5v5.586l-1.293-1.293zM9 4a1 1 0 012 0v2H9V4z" />
                </svg>
                Actualizar Empresa
            </button>
            <a href="#"
                class="btn-dark text-white font-medium py-2 px-4 rounded-md transition-colors duration-300 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                        clip-rule="evenodd" />
                </svg>
                Volver
            </a>
        </div>
    </form>



 
@endisset




<script>
document.getElementById('formEmpresa').addEventListener('submit', function(e) {
    e.preventDefault(); // Evitar submit normal

    const form = e.target;
    const url = form.action;

    // Crear un objeto FormData con los datos del formulario
    const formData = new FormData(form);

    // Para enviar como JSON, convertimos FormData a objeto plano
    const data = {};
    formData.forEach((value, key) => {
        data[key] = value;
    });

    fetch(url, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Muy importante para seguridad en Laravel
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            // Si falla validación o error del servidor
            return response.json().then(err => {throw err});
        }
        return response.json();
    })
    .then(json => {
        alert(json.message); // Mostrar mensaje de éxito
        // Aquí puedes actualizar la UI si quieres, con json.empresa
    })
    .catch(error => {
        // Manejar errores, por ejemplo mostrar mensajes de validación
        if(error.errors) {
            let mensajes = Object.values(error.errors).flat().join('\n');
            alert("Errores:\n" + mensajes);
        } else if(error.message) {
            alert("Error: " + error.message);
        } else {
            alert("Error inesperado");
        }
    });
});
</script>
