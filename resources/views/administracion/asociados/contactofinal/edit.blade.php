<x-layout.default>
    <!-- Cargar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        /* Estilos para Select2 */
        .select2-container--default .select2-selection--single,
        .select2-container--default .select2-selection--multiple {
            min-height: 42px;
            border: 1px solid #e0e6ed;
            border-radius: 4px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered,
        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            line-height: 42px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #3b82f6;
            border-color: #3b82f6;
            color: white;
            padding: 0 10px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white;
            margin-right: 5px;
        }
    </style>
    
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

    <!-- Formulario de Editar Contacto Final -->
    <div class="panel mt-6 p-5 max-w-6x2 mx-auto">
        <h2 class="text-2xl font-bold mb-5">EDITAR CONTACTO FINAL</h2>
        
        <form id="contactoFinalForm" class="space-y-4" method="POST" action="{{ route('contactofinal.update', $contactoFinal->idContactoFinal) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Cliente General (SELECT MÚLTIPLE) -->
                <div class="md:col-span-2">
                    <label for="idClienteGeneral" class="block text-sm font-medium mb-2">Clientes Generales</label>
                    <select id="idClienteGeneral" name="idClienteGeneral[]" class="select2 w-full" style="display: none" multiple>
                        @foreach ($clientesGenerales as $clienteGeneral)
                            <option value="{{ $clienteGeneral->idClienteGeneral }}"
                                {{ $clientesGeneralesAsociados->contains('idClienteGeneral', $clienteGeneral->idClienteGeneral) ? 'selected' : '' }}>
                                {{ $clienteGeneral->descripcion }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Puede seleccionar múltiples clientes generales</p>
                </div>

                <!-- Tipo Documento -->
                <div>
                    <label for="idTipoDocumento" class="block text-sm font-medium">Tipo Documento</label>
                    <select id="idTipoDocumento" name="idTipoDocumento" class="select2 w-full" style="display: none">
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

    <!-- Cargar jQuery y el plugin -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // ✅ Inicializar Select2 para Cliente General (MÚLTIPLE)
            $('#idClienteGeneral').select2({
                placeholder: "Seleccionar Cliente(s) General(es)",
                allowClear: true,
                width: '100%',
                multiple: true
            });

            // ✅ Inicializar Select2 para Tipo Documento
            $('#idTipoDocumento').select2({
                placeholder: "Seleccionar Tipo Documento",
                allowClear: true,
                width: '100%'
            });
        });
    </script>

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
                    // Mostrar errores de validación si existen
                    let errorMessage = data.message || 'Error desconocido';
                    if (data.errors) {
                        errorMessage = Object.values(data.errors).flat().join(', ');
                    }
                    
                    Swal.fire({
                        title: 'Error',
                        text: errorMessage,
                        icon: 'error',
                        customClass: 'sweet-alerts',
                    });
                }
            })
            .catch(error => {
                console.error('Error en fetch:', error);
                Swal.fire({
                    title: 'Error de conexión',
                    text: 'No se pudo conectar con el servidor.',
                    icon: 'error',
                    customClass: 'sweet-alerts',
                });
            });
        });
    </script>
</x-layout.default>