<x-layout.default>
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Contactos Finales</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Editar Contacto</span>
            </li>
        </ul>
    </div>

    <div class="panel mt-6 p-5 max-w-2xl mx-auto">
        <h2 class="text-xl font-bold mb-5">EDITAR CONTACTO FINAL</h2>
        
        <form id="contactoFinalForm" class="space-y-4" method="POST" action="{{ route('contactofinal.update', $contactoFinal->idContactoFinal) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Tipo Documento -->
                <div>
                    <label for="idTipoDocumento" class="block text-sm font-medium">Tipo Documento</label>
                    <select id="idTipoDocumento" name="idTipoDocumento" class="form-select w-full">
                        <option value="" disabled>Seleccionar Tipo Documento</option>
                        @foreach ($tiposDocumento as $tipoDocumento)
                            <option value="{{ $tipoDocumento->idTipoDocumento }}"
                                {{ old('idTipoDocumento', $contactoFinal->idTipoDocumento) == $tipoDocumento->idTipoDocumento ? 'selected' : '' }}>
                                {{ $tipoDocumento->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Número Documento -->
                <div>
                    <label for="numero_documento" class="block text-sm font-medium">Número Documento</label>
                    <input id="numero_documento" type="text" name="numero_documento" class="form-input w-full"
                        value="{{ old('numero_documento', $contactoFinal->numero_documento) }}">
                </div>

                <!-- Nombre Completo -->
                <div class="md:col-span-2">
                    <label for="nombre_completo" class="block text-sm font-medium">Nombre Completo</label>
                    <input id="nombre_completo" type="text" name="nombre_completo" class="form-input w-full"
                        value="{{ old('nombre_completo', $contactoFinal->nombre_completo) }}">
                </div>

                <!-- Correo -->
                <div>
                    <label for="correo" class="block text-sm font-medium">Correo</label>
                    <input id="correo" type="email" name="correo" class="form-input w-full"
                        value="{{ old('correo', $contactoFinal->correo) }}">
                </div>

                <!-- Teléfono -->
                <div>
                    <label for="telefono" class="block text-sm font-medium">Teléfono</label>
                    <input id="telefono" type="text" name="telefono" class="form-input w-full"
                        value="{{ old('telefono', $contactoFinal->telefono) }}">
                </div>

                <!-- Estado -->
                <div>
                    <label for="estado" class="block text-sm font-medium">Estado</label>
                    <div class="ml-4 w-12 h-6 relative">
                        <input type="hidden" name="estado" value="0">
                        <input type="checkbox" id="estado" name="estado"
                            class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                            value="1" {{ old('estado', $contactoFinal->estado) ? 'checked' : '' }} />
                        <span for="estado"
                            class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end mt-4">
                <a href="{{ route('contactofinal.index') }}" class="btn btn-outline-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">Actualizar</button>
            </div>
        </form>
    </div>

    <script>
        // Script para manejar el formulario de edición
        document.getElementById('contactoFinalForm').addEventListener('submit', function (event) {
            event.preventDefault();

            let formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '¡Éxito!',
                        text: data.message,
                        icon: 'success',
                        customClass: 'sweet-alerts',
                    }).then(() => {
                        window.location.href = "{{ route('contactofinal.index') }}";
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message,
                        icon: 'error',
                        customClass: 'sweet-alerts',
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error inesperado.',
                    icon: 'error',
                    customClass: 'sweet-alerts',
                });
            });
        });
    </script>
</x-layout.default>