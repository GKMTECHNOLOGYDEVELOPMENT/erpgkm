<template x-if="tab === 'perfil'">


    <div>
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

    <form data-userid="{{ $usuario->idUsuario }}" method="POST" enctype="multipart/form-data" id="update-forma" 
      class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-4 mb-5 bg-white dark:bg-[#0e1726]">
  
    <h6 class="text-lg font-bold mb-5">Información General</h6>
    <div class="flex flex-col sm:flex-row">
        <!-- Imagen de perfil -->
        <div class="ltr:sm:mr-4 rtl:sm:ml-4 w-full sm:w-2/12 mb-5">
            <label for="profile-image">
                <img id="profile-img"
                     src="{{ $usuario->avatar ? 'data:image/jpeg;base64,' . base64_encode($usuario->avatar) : '/assets/images/profile-34.jpeg' }}"
                     alt="image"
                     class="w-20 h-20 md:w-32 md:h-32 rounded-full object-cover mx-auto cursor-pointer" />
            </label>
            <input type="file" id="profile-image" name="profile-image" style="display:none;" accept="image/*"
                   onchange="previewImage(event)" />
        </div>

        <!-- Formulario de campos -->
        <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-5">
            <!-- Nombre Completo -->
            <div>
                <label for="Nombre">Nombre Completo</label>
                <input id="Nombre" name="Nombre" type="text" value="{{ $usuario->Nombre }}" class="form-input" />
            </div>

            <!-- Apellido Paterno -->
            <div>
                <label for="apellidoPaterno">Apellido Paterno</label>
                <input id="apellidoPaterno" name="apellidoPaterno" type="text" value="{{ $usuario->apellidoPaterno }}" class="form-input" />
            </div>

            <!-- Apellido Materno -->
            <div>
                <label for="apellidoMaterno">Apellido Materno</label>
                <input id="apellidoMaterno" name="apellidoMaterno" type="text" value="{{ $usuario->apellidoMaterno }}" class="form-input" />
            </div>

            <!-- Tipo Documento -->
            <div>
                <label for="idTipoDocumento" class="block text-sm font-medium">Tipo Documento</label>
                <select id="idTipoDocumento" name="idTipoDocumento" class="form-input w-full">
                    <option value="" disabled>Seleccionar Tipo Documento</option>
                    @foreach ($tiposDocumento as $tipoDocumento)
                        <option value="{{ $tipoDocumento->idTipoDocumento }}" {{ $tipoDocumento->idTipoDocumento == $usuario->idTipoDocumento ? 'selected' : '' }}>
                            {{ $tipoDocumento->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Documento -->
            <div>
                <label for="documento">Documento</label>
                <input id="documento" name="documento" type="text" value="{{ $usuario->documento }}" class="form-input" />
            </div>

            <!-- Teléfono -->
            <div>
                <label for="telefono">Teléfono</label>
                <input id="telefono" type="text" name="telefono" value="{{ $usuario->telefono }}" class="form-input" />
            </div>

            <!-- Email -->
            <div>
                <label for="correo">Correo Electronico</label>
                <input id="correo" name="correo" type="email" value="{{ $usuario->correo }}" class="form-input" />
            </div>
            <!-- Estado Civil -->
            <div>
                <label for="estadocivil">Estado Civil</label>
                <select id="estadocivil" name="estadocivil" class="form-input">
                    <option value="" disabled>Seleccionar Estado Civil</option>
                    <option value="1" {{ $usuario->estadocivil == 1 ? 'selected' : '' }}>Soltero</option>
                    <option value="2" {{ $usuario->estadocivil == 2 ? 'selected' : '' }}>Casado</option>
                    <option value="3" {{ $usuario->estadocivil == 3 ? 'selected' : '' }}>Divorciado</option>
                    <option value="4" {{ $usuario->estadocivil == 4 ? 'selected' : '' }}>Viudo</option>
                </select>
            </div>

            <!-- Botón de actualización -->
            <div class="sm:col-span-2 mt-3">
                <button type="button" id="update-button" class="btn btn-primary mr-2">Actualizar</button>
            </div>
        </div>
    </div>
</form>


<script>
$(document).ready(function() {
    $('#update-button').click(function(e) {
        e.preventDefault(); // Prevenir el comportamiento por defecto del botón
        
        let formData = new FormData($('#update-forma')[0]); // Crear un FormData con los datos del formulario

        // Obtener el ID del usuario desde el atributo data-userid del formulario
        let userId = $('#update-forma').data('userid');
        
        // Obtener el CSRF token desde el meta tag
        let csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Enviar los datos por AJAX
        $.ajax({
            url: '/usuarios/' + userId + '/update', // Ruta para la actualización
            type: 'POST',
            data: formData,
            contentType: false, // Dejar que jQuery maneje el tipo de contenido
            processData: false, // No procesar los datos del formulario
            headers: {
                'X-CSRF-TOKEN': csrfToken // Incluir el token CSRF en los encabezados
            },
            success: function(response) {
                // Si la actualización es exitosa, muestra un mensaje o realiza alguna acción
                toastr.success(response.success); // O puedes actualizar la vista si es necesario
            },
            error: function(xhr, status, error) {
                // Si ocurre algún error, puedes mostrar un mensaje de error
                toastr.error('Hubo un error al actualizar los datos');
            }
        });
    });
});


</script>
<div class="panel">
                <div class="mb-5">
                    <h5 class="font-semibold text-lg mb-4">Agregar dirección </h5>
                    <!-- <p>Changes your New <span class="text-primary">Billing</span> Information.</p> -->
                </div>
                <div class="mb-5">
                    <form id="direccion-form">
                        @csrf
                        @method('PUT')

                        <!-- Información Básica -->
                        <div class="mb-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Nacionalidad -->
                            <div>
                                <label for="nacionalidad">Nacionalidad</label>
                                <input id="nacionalidad" name="nacionalidad" type="text"
                                    value="{{ old('nacionalidad', $usuario->nacionalidad) }}" class="form-input" />
                            </div>
                            <!-- Departamento -->
                            <div>
                                <label for="departamento" class="block text-sm font-medium">Departamento</label>
                                <select id="departamento" name="departamento" class="form-input w-full">
                                    <option value="" disabled selected>Seleccionar Departamento</option>
                                    @foreach ($departamentos as $departamento)
                                        <option value="{{ $departamento['id_ubigeo'] }}"
                                            {{ old('departamento', $usuario->departamento) == $departamento['id_ubigeo'] ? 'selected' : '' }}>
                                            {{ $departamento['nombre_ubigeo'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="departamento-error" class="text-red-500 text-sm" style="display: none;"></div>
                            </div>
                        </div>

                        <!-- Información de Ubicación -->
                        <div class="mb-5">
                            <div>
                                <label for="provincia" class="block text-sm font-medium">Provincia</label>
                                <select id="provincia" name="provincia" class="form-input w-full">
                                    <option value="" disabled>Seleccionar Provincia</option>
                                    @foreach ($provinciasDelDepartamento as $provincia)
                                        <option value="{{ $provincia['id_ubigeo'] }}"
                                            {{ old('provincia', $usuario->provincia) == $provincia['id_ubigeo'] ? 'selected' : '' }}>
                                            {{ $provincia['nombre_ubigeo'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="provincia-error" class="text-red-500 text-sm" style="display: none;"></div>
                            </div>
                            <div>
                                <label for="distrito" class="block text-sm font-medium mt-2">Distrito</label>
                                <select id="distrito" name="distrito" class="form-input w-full">
                                    <option value="" disabled>Seleccionar Distrito</option>
                                    @foreach ($distritosDeLaProvincia as $distrito)
                                        <option value="{{ $distrito['id_ubigeo'] }}"
                                            {{ old('distrito', $usuario->distrito) == $distrito['id_ubigeo'] ? 'selected' : '' }}>
                                            {{ $distrito['nombre_ubigeo'] }}
                                        </option>
                                    @endforeach
                                </select>

                                <div id="distrito-error" class="text-red-500 text-sm" style="display: none;"></div>
                            </div>

                            <div>
                                <label for="direccion" class="block text-sm font-medium mt-2">Dirección</label>
                                <input id="direccion" name="direccion" type="text" class="form-input w-full"
                                    value="{{ old('direccion', $usuario->direccion) }}"
                                    placeholder="Ingrese la dirección">
                                <div id="direccion-error" class="text-red-500 text-sm" style="display: none;"></div>
                            </div>
                        </div>

                        <!-- Botón de Actualización -->
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </form>

                    <script>
                        $(document).ready(function() {
                            // Cargar provincias y distritos al cargar el formulario si ya hay un departamento seleccionado
                            function cargarProvincias(departamentoId) {
                                $.get('/ubigeo/provincias/' + departamentoId, function(data) {
                                    var provinciaSelect = $('#provincia');
                                    provinciaSelect.empty().prop('disabled', false);
                                    provinciaSelect.append(
                                        '<option value="" disabled selected>Seleccionar Provincia</option>');

                                    data.forEach(function(provincia) {
                                        provinciaSelect.append('<option value="' + provincia.id_ubigeo + '">' +
                                            provincia.nombre_ubigeo + '</option>');
                                    });

                                    // Si hay provincia seleccionada previamente, se selecciona automáticamente
                                    var provinciaSeleccionada = '{{ old('provincia', $usuario->provincia) }}';
                                    if (provinciaSeleccionada) {
                                        $('#provincia').val(provinciaSeleccionada).change();
                                    }
                                });
                            }

                            function cargarDistritos(provinciaId) {
                                $.get('/ubigeo/distritos/' + provinciaId, function(data) {
                                    var distritoSelect = $('#distrito');
                                    distritoSelect.empty().prop('disabled', false);
                                    distritoSelect.append(
                                        '<option value="" disabled selected>Seleccionar Distrito</option>');

                                    data.forEach(function(distrito) {
                                        distritoSelect.append('<option value="' + distrito.id_ubigeo + '">' +
                                            distrito.nombre_ubigeo + '</option>');
                                    });

                                    // Si hay distrito seleccionado previamente, se selecciona automáticamente
                                    var distritoSeleccionado = '{{ old('distrito', $usuario->distrito) }}';
                                    if (distritoSeleccionado) {
                                        $('#distrito').val(distritoSeleccionado);
                                    }
                                });
                            }

                            // Si ya hay un departamento seleccionado al cargar la página
                            var departamentoId = $('#departamento').val();
                            if (departamentoId) {
                                cargarProvincias(departamentoId);
                            }

                            // Cargar distritos si ya hay una provincia seleccionada al cargar la página
                            var provinciaId = $('#provincia').val();
                            if (provinciaId) {
                                cargarDistritos(provinciaId);
                            }

                            // Cuando se selecciona un nuevo departamento
                            $('#departamento').change(function() {
                                var departamentoId = $(this).val();
                                if (departamentoId) {
                                    // Limpiar los selects de provincia y distrito
                                    $('#provincia').empty().prop('disabled', true);
                                    $('#distrito').empty().prop('disabled', true);

                                    cargarProvincias(departamentoId);
                                }
                            });

                            // Cuando se selecciona una provincia
                            $('#provincia').on('change', function() {
                                var provinciaId = $(this).val();
                                if (provinciaId) {
                                    // Limpiar el select de distritos
                                    $('#distrito').empty().prop('disabled', true);

                                    cargarDistritos(provinciaId);
                                }
                            });
                        });
                    </script>

                    <!-- <script src="{{ asset('assets/js/ubigeo.js') }}"></script> -->
                    <script>
                        document.getElementById('direccion-form').addEventListener('submit', function(event) {
                            event.preventDefault(); // Evita el envío normal del formulario

                            // Recolecta los datos del formulario
                            const formData = new FormData(this);
                            const formDataObj = {};
                            formData.forEach((value, key) => {
                                formDataObj[key] = value;
                            });

                            // ID del usuario (será inyectado en tu Blade)
                            const userId = {{ $usuario->idUsuario }};

                            // URL para actualizar la dirección
                            const url = `/usuario/direccion/${userId}`;

                            // Obtener el token CSRF
                            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                            // Realiza la solicitud `fetch` con el método PUT y los datos JSON
                            fetch(url, {
                                    method: 'PUT', // Usamos el método PUT para actualización
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken
                                    },
                                    body: JSON.stringify(formDataObj) // Convierte los datos del formulario a JSON
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Si la respuesta es exitosa, puedes mostrar un mensaje de éxito
                                        toastr.success('Dirección actualizada correctamente');
                                    } else {
                                        // Si ocurre un error, mostrar los mensajes de error
                                        toastr.error('Error al actualizar la dirección');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error al enviar la solicitud:', error);
                                    toastr.error('Error al intentar actualizar');
                                });
                        });
                    </script>





                </div>
            </div>
    </div>
</template>