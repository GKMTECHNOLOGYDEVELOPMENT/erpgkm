<template x-if="tab === 'informacion'">
    <div>
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
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
                        <option value="" selected disabled>Selecciona una Sucursal</option>
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
                        <option value="" selected disabled>Selecciona un Tipo de Usuario</option>
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
                        <option value="" selected disabled>Selecciona un Sexo</option>
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
                        <option value="" selected disabled>Selecciona un Rol</option>
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
                        <option value="" selected disabled>Selecciona un Tipo de Área</option>
                        @foreach ($tiposArea as $tipoArea)
                            <option value="{{ $tipoArea->idTipoArea }}"
                                {{ $usuario->idTipoArea == $tipoArea->idTipoArea ? 'selected' : '' }}>
                                {{ $tipoArea->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                @if(\App\Helpers\PermisoHelper::tienePermiso('ACTUALIZAR INFORMACION IMPORTANTE'))
                <!-- Botones -->
                <div class="sm:col-span-2 mt-3">
                    <button type="submit" class="btn btn-primary mr-2">Actualizar
                        Información</button>
                </div>
                @endif
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
