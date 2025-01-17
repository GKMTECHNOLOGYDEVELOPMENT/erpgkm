<x-layout.default>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">

    <!-- Formulario de Editar Cliente -->
    <div class="panel mt-6 p-5 max-w-2xl mx-auto">
        <h2 class="text-xl font-bold mb-5">Editar Cliente</h2>
        <div class="p-5">
            <form id="clienteForm" class="grid grid-cols-1 md:grid-cols-2 gap-4" method="POST" action="{{ route('clientes.update', $cliente->idCliente) }}">
                @csrf
                @method('PUT')

                <!-- ClienteGeneral -->
                <div>
                    <!-- <label for="idClienteGeneral" class="block text-sm font-medium">Cliente General</label> -->
                    <select id="idClienteGeneral" name="idClienteGeneral" class="select2 w-full">
                        <option value="" disabled selected>Seleccionar Cliente General</option>
                        @foreach ($clientesGenerales as $clienteGeneral)
                            <option value="{{ $clienteGeneral->idClienteGeneral }}" {{ old('idClienteGeneral', $cliente->idClienteGeneral) == $clienteGeneral->idClienteGeneral ? 'selected' : '' }}>
                                {{ $clienteGeneral->descripcion }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-medium">Nombre</label>
                    <input id="nombre" type="text" name="nombre" class="form-input w-full" placeholder="Ingrese el nombre" value="{{ old('nombre', $cliente->nombre) }}">
                </div>

                <!-- Tipo Documento -->
                <div>
                    <!-- <label for="idTipoDocumento" class="block text-sm font-medium">Tipo Documento</label> -->
                    <select id="idTipoDocumento" name="idTipoDocumento" class="select2 w-full">
                        <option value="" disabled selected>Seleccionar Tipo Documento</option>
                        @foreach ($tiposDocumento as $tipoDocumento)
                            <option value="{{ $tipoDocumento->idTipoDocumento }}" {{ old('idTipoDocumento', $cliente->idTipoDocumento) == $tipoDocumento->idTipoDocumento ? 'selected' : '' }}>
                                {{ $tipoDocumento->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Documento -->
                <div>
                    <label for="documento" class="block text-sm font-medium">Documento</label>
                    <input id="documento" type="text" name="documento" class="form-input w-full" placeholder="Ingrese el documento" value="{{ old('documento', $cliente->documento) }}">
                </div>

                <!-- Teléfono -->
                <div>
                    <label for="telefono" class="block text-sm font-medium">Teléfono</label>
                    <input id="telefono" type="text" name="telefono" class="form-input w-full" placeholder="Ingrese el teléfono" value="{{ old('telefono', $cliente->telefono) }}">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input id="email" type="email" class="form-input w-full" name="email" placeholder="Ingrese el email" value="{{ old('email', $cliente->email) }}">
                </div>

                <!-- Departamento -->
                <div>
                    <label for="departamento" class="block text-sm font-medium">Departamento</label>
                    <select id="departamento" name="departamento" class="form-input w-full">
                        <option value="" disabled selected>Seleccionar Departamento</option>
                        @foreach ($departamentos as $departamento)
                            <option value="{{ $departamento['id_ubigeo'] }}" {{ old('departamento', $cliente->departamento) == $departamento['id_ubigeo'] ? 'selected' : '' }}>
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
                            <option value="{{ $provincia['id_ubigeo'] }}" {{ old('provincia', $cliente->provincia) == $provincia['id_ubigeo'] ? 'selected' : '' }}>
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
                            <option value="{{ $distrito['id_ubigeo'] }}" {{ old('distrito', $cliente->distrito) == $distrito['id_ubigeo'] ? 'selected' : '' }}>
                                {{ $distrito['nombre_ubigeo'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Dirección -->
                <div>
                    <label for="direccion" class="block text-sm font-medium">Dirección</label>
                    <input id="direccion" type="text" name="direccion" class="form-input w-full" placeholder="Ingrese la dirección" value="{{ old('direccion', $cliente->direccion) }}">
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
    $(document).ready(function() {
        // Cargar provincias y distritos al cargar el formulario si ya hay un departamento seleccionado
        function cargarProvincias(departamentoId) {
            $.get('/ubigeo/provincias/' + departamentoId, function(data) {
                var provinciaSelect = $('#provincia');
                provinciaSelect.empty().prop('disabled', false);
                provinciaSelect.append('<option value="" disabled selected>Seleccionar Provincia</option>');

                data.forEach(function(provincia) {
                    provinciaSelect.append('<option value="' + provincia.id_ubigeo + '">' + provincia.nombre_ubigeo + '</option>');
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
                distritoSelect.append('<option value="" disabled selected>Seleccionar Distrito</option>');

                data.forEach(function(distrito) {
                    distritoSelect.append('<option value="' + distrito.id_ubigeo + '">' + distrito.nombre_ubigeo + '</option>');
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

      // Inicializar Select2
      document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.select2').forEach(function(select) {
                NiceSelect.bind(select, {
                    searchable: true
                });
            });
        });
</script>
<script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
</x-layout.default>
