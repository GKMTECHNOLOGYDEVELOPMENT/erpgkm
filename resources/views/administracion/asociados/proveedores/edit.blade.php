<x-layout.default>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">

    <!-- Formulario de Crear o Editar Proveedor -->
    <div class="panel mt-6 p-5 max-w-2xl mx-auto">
        <h2 class="text-xl font-bold mb-5">Editar Proveedor</h2>
        <div class="p-5">
            <form id="proveedorForm" class="grid grid-cols-1 md:grid-cols-2 gap-4" method="POST" action="{{ route('proveedores.update', $proveedor->idProveedor) }}">
                @csrf
                @method('PUT')

                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-medium">Nombre</label>
                    <input id="nombre" type="text" class="form-input w-full" name="nombre" placeholder="Ingrese el nombre" value="{{ old('nombre', $proveedor->nombre) }}">
                </div>

                <!-- Tipo Documento -->
                <div>
                    
                    <select id="idTipoDocumento" name="idTipoDocumento" class="select2 w-full">
                        <option value="" disabled selected>Seleccionar Tipo Documento</option>
                        @foreach ($tiposDocumento as $tipoDocumento)
                            <option value="{{ $tipoDocumento->idTipoDocumento }}" {{ old('idTipoDocumento', $proveedor->idTipoDocumento) == $tipoDocumento->idTipoDocumento ? 'selected' : '' }}>
                                {{ $tipoDocumento->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Número de Documento -->
                <div>
                    <label for="numeroDocumento" class="block text-sm font-medium">Número de Documento</label>
                    <input id="numeroDocumento" type="text" class="form-input w-full" name="numeroDocumento" placeholder="Ingrese el número de documento" value="{{ old('numeroDocumento', $proveedor->numeroDocumento) }}">
                </div>

                <!-- Departamento -->
                <div>
                    <label for="departamento" class="block text-sm font-medium">Departamento</label>
                    <select id="departamento" name="departamento" class="form-input w-full">
                        <option value="" disabled selected>Seleccionar Departamento</option>
                        @foreach ($departamentos as $departamento)
                            <option value="{{ $departamento['id_ubigeo'] }}" {{ old('departamento', $proveedor->departamento) == $departamento['id_ubigeo'] ? 'selected' : '' }}>
                                {{ $departamento['nombre_ubigeo'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Provincia -->
                <div>
                    <label for="provincia" class="block text-sm font-medium">Provincia</label>
                    <select id="provincia" name="provincia" class="form-input w-full" disabled>
                        <option value="" disabled selected>Seleccionar Provincia</option>
                    </select>
                </div>

                <!-- Distrito -->
                <div>
                    <label for="distrito" class="block text-sm font-medium">Distrito</label>
                    <select id="distrito" name="distrito" class="form-input w-full" disabled>
                        <option value="" disabled selected>Seleccionar Distrito</option>
                    </select>
                </div>

                <!-- Area -->
                <div>
                    <select id="idArea" name="idArea" class="select2 w-full">
                        <option value="" disabled selected>Seleccionar Area</option>
                        @foreach ($areas as $area)
                            <option value="{{ $area->idArea }}" {{ old('idArea', $proveedor->idArea) == $area->idArea ? 'selected' : '' }}>
                                {{ $area->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Código Postal -->
                <div>
                    <label for="codigoPostal" class="block text-sm font-medium">Código Postal</label>
                    <input id="codigoPostal" type="text" class="form-input w-full" name="codigoPostal" placeholder="Ingrese el código postal" value="{{ old('codigoPostal', $proveedor->codigoPostal) }}">
                </div>

                <!-- Teléfono -->
                <div>
                    <label for="telefono" class="block text-sm font-medium">Teléfono</label>
                    <input id="telefono" type="text" class="form-input w-full" name="telefono" placeholder="Ingrese el teléfono" value="{{ old('telefono', $proveedor->telefono) }}">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input id="email" type="email" class="form-input w-full" name="email" placeholder="Ingrese el email" value="{{ old('email', $proveedor->email) }}">
                </div>

                <!-- Dirección -->
                <div>
                    <label for="direccion" class="block text-sm font-medium">Dirección</label>
                    <input id="direccion" type="text" name="direccion" class="form-input w-full" placeholder="Ingrese la dirección" value="{{ old('direccion', $proveedor->direccion) }}">
                </div>

                <!-- Botones -->
                <div class="flex justify-end items-center mt-4">
                    <a href="{{ route('administracion.proveedores') }}" class="btn btn-outline-danger">Cancelar</a>
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
                    var provinciaSeleccionada = '{{ old('provincia', $proveedor->provincia) }}';
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
                    var distritoSeleccionado = '{{ old('distrito', $proveedor->distrito) }}';
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
