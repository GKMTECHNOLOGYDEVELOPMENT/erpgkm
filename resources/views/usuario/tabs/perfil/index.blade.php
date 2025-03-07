<template x-if="tab === 'perfil'">
    <div>

        <form id="update-form" method="POST" enctype="multipart/form-data"
            class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-4 mb-5 bg-white dark:bg-[#0e1726]">
            @csrf
            @method('PUT')
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
                        <input id="Nombre" name="Nombre" type="text" value="{{ $usuario->Nombre }}"
                            class="form-input"/>
                    </div>

                    <!-- Apellido Paterno -->
                    <div>
                        <label for="apellidoPaterno">Apellido Paterno</label>
                        <input id="apellidoPaterno" name="apellidoPaterno" type="text"
                            value="{{ $usuario->apellidoPaterno }}" class="form-input" />
                    </div>

                    <!-- Apellido Materno -->
                    <div>
                        <label for="apellidoMaterno">Apellido Materno</label>
                        <input id="apellidoMaterno" name="apellidoMaterno" type="text"
                            value="{{ $usuario->apellidoMaterno }}" class="form-input" />
                    </div>

                    <!-- Tipo Documento -->
                    <div>
                        <label for="idTipoDocumento" class="block text-sm font-medium">Tipo
                            Documento</label>
                        <select id="idTipoDocumento" name="idTipoDocumento" class="select2 w-full" style="display:none">
                            <option value="" disabled>Seleccionar Tipo Documento</option>
                            @foreach ($tiposDocumento as $tipoDocumento)
                                <option value="{{ $tipoDocumento->idTipoDocumento }}"
                                    {{ $tipoDocumento->idTipoDocumento == $usuario->idTipoDocumento ? 'selected' : '' }}>
                                    {{ $tipoDocumento->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Documento -->
                    <div>
                        <label for="documento">Documento</label>
                        <input id="documento" name="documento" type="text" value="{{ $usuario->documento }}"
                            class="form-input" />
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label for="telefono">Teléfono</label>
                        <input id="telefono" type="text" name="telefono" value="{{ $usuario->telefono }}"
                            class="form-input" />
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="correo">Correo Electronico</label>
                        <input id="correo" name="correo" type="email" value="{{ $usuario->correo }}"
                            class="form-input" />
                    </div>

                    <!-- Botones -->
                    <div class="sm:col-span-2 mt-3">
                        <button type="submit" class="btn btn-primary mr-2">Actualizar</button>
                    </div>
                </div>
            </div>
        </form>



        <script>
            $(document).ready(function() {
                $('#update-form').on('submit', function(event) {
                    event.preventDefault(); // Prevenir la recarga de la página

                    var formData = new FormData(this);

                    $.ajax({
                        url: '{{ route('usuarios.update', $usuario->idUsuario) }}',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                // Aquí puedes actualizar el contenido de la página con los nuevos datos
                                $('#profile-img').attr('src', 'data:image/jpeg;base64,' + btoa(
                                    String.fromCharCode.apply(null, new Uint8Array(response
                                        .usuario.avatar))));
                                $('#Nombre').val(response.usuario.Nombre);
                                $('#apellidoPaterno').val(response.usuario.apellidoPaterno);
                                $('#apellidoMaterno').val(response.usuario.apellidoMaterno);
                                $('#documento').val(response.usuario.documento);
                                $('#telefono').val(response.usuario.telefono);
                                $('#correo').val(response.usuario.correo);
                            } else {
                                toastr.error('Hubo un problema al actualizar');
                            }
                        },
                        error: function(xhr, status, error) {
                            // Verificar si el error proviene de un problema de validación
                            if (xhr.status === 422) {
                                var errors = xhr.responseJSON.errors;
                                // Mostrar todos los errores usando toastr
                                for (var field in errors) {
                                    if (errors.hasOwnProperty(field)) {
                                        toastr.error(errors[field].join(
                                            ', ')); // Unir los mensajes de error si hay más de uno
                                    }
                                }
                            } else {
                                toastr.error('Hubo un error al intentar actualizar los datos');
                            }
                        }
                    });
                });
            });
        </script>














        <form id="config-form" method="POST"
            class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-4 bg-white dark:bg-[#0e1726]">
            @csrf
            @method('PUT')
            <h6 class="text-lg font-bold mb-5">Información Importante</h6>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                <!-- Sueldo por Hora -->
                <div>
                    <label for="sueldoPorHora">Sueldo por Hora</label>
                    <input type="number" name="sueldoPorHora" id="sueldoPorHora" placeholder="Ejemplo: 20.5"
                        class="form-input" step="0.01" value="{{ $usuario->sueldoPorHora }}" />
                </div>

                <!-- Sucursal -->
                <div>
                    <label for="idSucursal">Sucursal</label>
                    <select name="idSucursal" id="idSucursal" class="form-input">
                        <option value="" disabled>Selecciona una Sucursal</option>
                        @foreach ($sucursales as $sucursal)
                            <option value="{{ $sucursal->idSucursal }}"
                                {{ $usuario->idSucursal == $sucursal->idSucursal ? 'selected' : '' }}>
                                {{ $sucursal->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tipo de Usuario -->
                <div>
                    <label for="idTipoUsuario">Tipo de Usuario</label>
                    <select name="idTipoUsuario" id="idTipoUsuario" class="form-input">
                        <option value="" disabled>Selecciona un Tipo de Usuario</option>
                        @foreach ($tiposUsuario as $tipoUsuario)
                            <option value="{{ $tipoUsuario->idTipoUsuario }}"
                                {{ $usuario->idTipoUsuario == $tipoUsuario->idTipoUsuario ? 'selected' : '' }}>
                                {{ $tipoUsuario->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Sexo -->
                <div>
                    <label for="idSexo">Sexo</label>
                    <select name="idSexo" id="idSexo" class="form-input">
                        <option value="" disabled>Selecciona un Sexo</option>
                        @foreach ($sexos as $sexo)
                            <option value="{{ $sexo->idSexo }}"
                                {{ $usuario->idSexo == $sexo->idSexo ? 'selected' : '' }}>
                                {{ $sexo->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Rol -->
                <div>
                    <label for="idRol">Rol</label>
                    <select name="idRol" id="idRol" class="form-input">
                        <option value="" disabled>Selecciona un Rol</option>
                        @foreach ($roles as $rol)
                            <option value="{{ $rol->idRol }}"
                                {{ $usuario->idRol == $rol->idRol ? 'selected' : '' }}>
                                {{ $rol->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tipo de Área -->
                <div>
                    <label for="idTipoArea">Tipo de Área</label>
                    <select name="idTipoArea" id="idTipoArea" class="form-input">
                        <option value="" disabled>Selecciona un Tipo de Área</option>
                        @foreach ($tiposArea as $tipoArea)
                            <option value="{{ $tipoArea->idTipoArea }}"
                                {{ $usuario->idTipoArea == $tipoArea->idTipoArea ? 'selected' : '' }}>
                                {{ $tipoArea->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Botones -->
                <div class="sm:col-span-2 mt-3">
                    <button type="submit" class="btn btn-primary mr-2">Actualizar
                        Información</button>
                </div>
            </div>
        </form>

        <script>
            $(document).ready(function() {
                $('#config-form').on('submit', function(event) {
                    event.preventDefault(); // Prevenir la recarga de la página

                    var formData = $(this).serialize(); // Usamos serialize para enviar los datos como JSON

                    $.ajax({
                        url: '{{ route('usuario.config', $usuario->idUsuario) }}',
                        type: 'PUT',
                        data: formData,
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                // Aquí puedes actualizar los datos en el DOM si lo deseas
                                // Ejemplo: actualizar los valores en los campos de entrada
                                $('#sueldoPorHora').val(response.usuario.sueldoPorHora);
                                $('#idSucursal').val(response.usuario.idSucursal);
                                $('#idTipoUsuario').val(response.usuario.idTipoUsuario);
                                $('#idSexo').val(response.usuario.idSexo);
                                $('#idRol').val(response.usuario.idRol);
                                $('#idTipoArea').val(response.usuario.idTipoArea);
                            }
                        },
                        error: function(xhr) {
                            // Si hay errores, manejamos la respuesta de error
                            var errors = xhr.responseJSON.errors;
                            if (errors) {
                                // Mostrar los errores en el frontend
                                for (var field in errors) {
                                    toastr.error(errors[field].join(", "));
                                }
                            } else {
                                toastr.error('Hubo un error al intentar actualizar los datos');
                            }
                        }
                    });
                });
            });
        </script>








    </div>
</template>
