<x-layout.default>
    <!-- Cargar CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Cargar jQuery y el plugin -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        .remove-client {
            background: transparent;
            border: none;
            color: white;
            font-weight: bold;
            cursor: pointer;
            font-size: 16px;
        }  /* Estilos para Select2 */
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
                <a href="javascript:;" class="text-primary hover:underline">Clientes</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Editar Clientes</span>
            </li>
        </ul>
    </div>
    <!-- Formulario de Editar Cliente -->
    <div class="panel mt-6 p-5 max-w-4x2 mx-auto">
        <h2 class="text-xl font-bold mb-5">EDITAR CLIENTES</h2>
        <div class="p-5">
            <form id="clienteForm" class="grid grid-cols-1 md:grid-cols-2 gap-4" method="POST"
                action="{{ route('clientes.update', $cliente->idCliente) }}">
                @csrf
                @method('PUT')

                <!-- Clientes Generales asociados -->
                <div>
                    <strong>Clientes Generales Asociados:</strong>
                    <div id="selected-items-list" class="flex flex-wrap gap-2">
                        @foreach ($clientesGeneralesAsociados as $clienteGeneral)
                            <span class="badge bg-primary">
                                {{ $clienteGeneral->descripcion }}
                                <button class="remove-client text-white ml-2"
                                    data-id="{{ $clienteGeneral->idClienteGeneral }}">X</button>
                            </span>
                        @endforeach
                    </div>
                </div>

                <!-- ClienteGeneral -->
                <div>
                    <label for="idClienteGeneral" class="block text-sm font-medium">Cliente General</label>
                    <select id="idClienteGeneral" name="idClienteGeneral" class="select2 w-full" style="display: none">
                        <option value="" disabled selected>Seleccionar Cliente General</option>
                        @foreach ($clientesGenerales as $clienteGeneral)
                            <option value="{{ $clienteGeneral->idClienteGeneral }}"
                                {{ old('idClienteGeneral', $cliente->idClienteGeneral) == $clienteGeneral->idClienteGeneral ? 'selected' : '' }}>
                                {{ $clienteGeneral->descripcion }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-medium">Nombre</label>
                    <input id="nombre" type="text" name="nombre" class="form-input w-full"
                        placeholder="Ingrese el nombre" value="{{ old('nombre', $cliente->nombre) }}">
                </div>

                <!-- Tipo Documento -->
                <div>
                    <label for="idTipoDocumento" class="block text-sm font-medium">Tipo Documento</label>
                    <select id="idTipoDocumento" name="idTipoDocumento" class="select2 w-full" style="display:none">
                        <option value="" disabled>Seleccionar Tipo Documento</option>
                        @foreach ($tiposDocumento as $tipoDocumento)
                            <option value="{{ $tipoDocumento->idTipoDocumento }}"
                                {{ old('idTipoDocumento', $cliente->idTipoDocumento) == $tipoDocumento->idTipoDocumento ? 'selected' : '' }}>
                                {{ $tipoDocumento->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <!-- Documento -->
                <div>
                    <label for="documento" class="block text-sm font-medium">Documento</label>
                    <input id="documento" type="text" name="documento" class="form-input w-full"
                        placeholder="Ingrese el documento" value="{{ old('documento', $cliente->documento) }}">
                </div>

                

                @php
    $mostrarEsTienda = $cliente->idTipoDocumento == 1; // Por ejemplo, si el tipo de documento es "RUC" (asumiendo que '1' corresponde a RUC)
@endphp

<div id="esTiendaContainer" class="{{ $mostrarEsTienda ? '' : 'hidden' }} mt-4">
    <label for="esTienda" class="block text-sm font-medium">¿Es tienda?</label>
    <div class="flex items-center">
        <!-- Campo hidden para enviar valor 0 si el switch no está activado -->
        <input type="hidden" name="esTienda" value="0">

        <div class="w-12 h-6 relative">
            <input type="checkbox" id="esTienda" name="esTienda" class="custom_sitch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                value="1" @checked($cliente->esTienda == 1) />
            <span for="esTienda" class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
        </div>
    </div>
</div>



                <!-- Teléfono -->
                <div>
                    <label for="telefono" class="block text-sm font-medium">Teléfono</label>
                    <input id="telefono" type="text" name="telefono" class="form-input w-full"
                        placeholder="Ingrese el teléfono" value="{{ old('telefono', $cliente->telefono) }}">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input id="email" type="email" class="form-input w-full" name="email"
                        placeholder="Ingrese el email" value="{{ old('email', $cliente->email) }}">
                </div>

                <!-- Departamento -->
                <div>
                    <label for="departamento" class="block text-sm font-medium">Departamento</label>
                    <select id="departamento" name="departamento" class="form-input w-full">
                        <option value="" disabled selected>Seleccionar Departamento</option>
                        @foreach ($departamentos as $departamento)
                            <option value="{{ $departamento['id_ubigeo'] }}"
                                {{ old('departamento', $cliente->departamento) == $departamento['id_ubigeo'] ? 'selected' : '' }}>
                                {{ $departamento['nombre_ubigeo'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Provincia -->
                <div>
                    <label for="provincia" class="block text-sm font-medium">Provincia</label>
                    <select id="provincia" name="provincia" class="form-input w-full">
                        <option value="" disabled>Seleccionar Provincia</option>
                        @foreach ($provinciasDelDepartamento as $provincia)
                            <option value="{{ $provincia['id_ubigeo'] }}"
                                {{ old('provincia', $cliente->provincia) == $provincia['id_ubigeo'] ? 'selected' : '' }}>
                                {{ $provincia['nombre_ubigeo'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Distrito -->
                <div>
                    <label for="distrito" class="block text-sm font-medium">Distrito</label>
                    <select id="distrito" name="distrito" class="form-input w-full">
                        <option value="" disabled>Seleccionar Distrito</option>
                        @foreach ($distritosDeLaProvincia as $distrito)
                            <option value="{{ $distrito['id_ubigeo'] }}"
                                {{ old('distrito', $cliente->distrito) == $distrito['id_ubigeo'] ? 'selected' : '' }}>
                                {{ $distrito['nombre_ubigeo'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Dirección -->
                <div>
                    <label for="direccion" class="block text-sm font-medium">Dirección</label>
                    <input id="direccion" type="text" name="direccion" class="form-input w-full"
                        placeholder="Ingrese la dirección" value="{{ old('direccion', $cliente->direccion) }}">
                </div>
                <!-- Estado -->
                <!-- Estado -->
                <div>
                    <label for="estado" class="block text-sm font-medium">Estado</label>
                    <div class="ml-4 w-12 h-6 relative">
                        <input type="hidden" name="estado" value="0">
                        <!-- Valor enviado cuando el checkbox no está marcado -->
                        <input type="checkbox" id="estado" name="estado"
                            class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                            value="1" {{ old('estado', $cliente->estado) ? 'checked' : '' }} />
                        <span for="estado"
                            class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                    </div>
                </div>


                <!-- Botones -->
                <div class="md:col-span-2 flex justify-end mt-4">
                    <a href="{{ route('administracion.clientes') }}" class="btn btn-outline-danger">Cancelar</a>
                    <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">Actualizar</button>
                </div>
            </form>
        </div>
    </div>


  <script>
    $(document).ready(function () {
        // ✅ Inicializar Select2
        $('#idClienteGeneral').select2({
            placeholder: "Seleccionar Cliente General",
            allowClear: true,
            width: '100%'
        });

        $('#idTipoDocumento').select2({
            placeholder: "Seleccionar Tipo Documento",
            allowClear: true,
            width: '100%'
        });

        // ✅ Mostrar u ocultar el switch "¿Es tienda?" cuando se selecciona "RUC"
        function toggleEsTienda() {
            const selectedText = $('#idTipoDocumento option:selected').text().trim();
            if (selectedText === 'RUC') {
                $('#esTiendaContainer').removeClass('hidden');
            } else {
                $('#esTiendaContainer').addClass('hidden');
            }
        }

        // Detectar cambios usando eventos de Select2
        $('#idTipoDocumento').on('select2:select select2:clear', toggleEsTienda);

        // Ejecutar al cargar (por si ya está seleccionado "RUC")
        toggleEsTienda();
    });
</script>



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
                    var provinciaSeleccionada = '{{ old('provincia', $cliente->provincia) }}';
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
                    var distritoSeleccionado = '{{ old('distrito', $cliente->distrito) }}';
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




    <script>
        $(document).ready(function() {
            $('#idCliente').change(function() {
                var idCliente = $(this).val();

                if (idCliente) {
                    $.get('/clientes/generales/asociados/' + idCliente, function(data) {
                        // Limpiar los items previos
                        $('#selected-items-list').empty();

                        // Agregar los nuevos badges
                        data.forEach(function(clienteGeneral) {
                            $('#selected-items-list').append(
                                '<span class="badge badge-blue">' + clienteGeneral
                                .descripcion + '</span>');
                        });
                    });
                }
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            // Inicializar Select2
            NiceSelect.bind(document.getElementById("idClienteGeneral"));
            NiceSelect.bind(document.getElementById("idTipoDocumento"));
        });

        // Evento cuando se selecciona un cliente general
        $('#idClienteGeneral').change(function() {
            var idClienteGeneral = $(this).val(); // Obtener el ID del cliente general seleccionado
            var descripcionClienteGeneral = $("#idClienteGeneral option:selected")
                .text(); // Obtener el nombre del cliente general

            // Verificar si hay una opción seleccionada
            if (idClienteGeneral) {
                // Comprobar si el cliente general ya está en la lista
                var existingBadge = $('#selected-items-list').find('[data-id="' + idClienteGeneral +
                    '"]');

                if (existingBadge.length > 0) {
                    alert("Este cliente general ya está asociado.");
                    return; // Evitar agregarlo de nuevo
                }

                // Agregar el nuevo cliente general a la lista
                var newBadge = '<span class="badge bg-primary" data-id="' + idClienteGeneral + '">' +
                    descripcionClienteGeneral +
                    '<button class="remove-client text-white ml-2" data-id="' + idClienteGeneral +
                    '">X</button>' +
                    '</span>';

                $('#selected-items-list').append(newBadge);

                // Enviar al servidor para almacenar la relación
                $.ajax({
                    url: '/clientes/' + '{{ $cliente->idCliente }}' +
                        '/agregar-cliente-general/' + idClienteGeneral,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', // Token CSRF
                        idClienteGeneral: idClienteGeneral,
                    },
                    success: function(response) {
                        if (response.success) {
                            console.log('Cliente general agregado exitosamente');
                        } else {
                            console.error('Error al agregar el cliente general:', response
                                .message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al agregar el cliente general:', error);
                        alert("Hubo un error al agregar el cliente general.");
                    }
                });
            }
        });

        // Eliminar un cliente general de la lista
        $('#selected-items-list').on('click', '.remove-client', function(event) {
            event.preventDefault(); // Evitar que se recargue la página

            var idClienteGeneral = $(this).data('id');
            var badge = $(this).parent();

            // Enviar al servidor para eliminar la relación
            $.ajax({
                url: '/clientes/' + '{{ $cliente->idCliente }}' +
                    '/eliminar-cliente-general/' + idClienteGeneral,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}', // Token CSRF
                },
                success: function(response) {
                    // Si la eliminación es exitosa, eliminar el badge
                    if (response.success) {
                        badge.remove();
                    } else {
                        alert("Hubo un error al eliminar el cliente general.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al eliminar el cliente general', error);
                    alert("Hubo un error al eliminar el cliente general.");
                }
            });
        });
    </script>

</x-layout.default>