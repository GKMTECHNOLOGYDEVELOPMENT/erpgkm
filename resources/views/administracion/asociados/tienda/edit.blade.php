<x-layout.default>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <style>
        #map {
            height: 300px;
            /* Ajusta el tamaño del mapa según tus necesidades */
            width: 100%;
        }
    </style>

    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{ route('administracion.tienda') }}" class="text-primary hover:underline">Tienda</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Editar Tienda</span>
            </li>
        </ul>
    </div>
    <div class="panel mt-6 p-5 max-w-4x2 mx-auto">
        <h2 class="text-xl font-bold mb-5">EDITAR TIENDA</h2>
        <!-- Formulario -->
        <div class="p-5">
            <form id="tiendaForm" class="grid grid-cols-1 md:grid-cols-2 gap-4" method="POST"
                action="{{ route('tiendas.update', $tienda->idTienda) }}">
                @csrf
                @method('PUT') <!-- Usamos PUT para la actualización -->

                <!-- RUC -->
                <div>
                    <label for="ruc" class="block text-sm font-medium">RUC</label>
                    <input id="ruc" name="ruc" type="text" class="form-input w-full"
                    
                        placeholder="Ingrese el RUC" 
                        pattern="^\d{8,}$" required
                        title="Solo se permiten números y debe ser mayor a 7 digitos"
                        value="{{ old('ruc', $tienda->ruc) }}">

                        <div id="ruc-error" class="text-red-500 text-sm" style="display: none;"></div>
                </div>
                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-medium">Nombre</label>
                    <input id="nombre" type="text" class="form-input w-full" placeholder="Ingrese el nombre"
                        name="nombre"
                        required
                        value="{{ old('nombre', $tienda->nombre) }}" >
                        <div id="nombre-error" class="text-red-500 text-sm" style="display: none;"></div>
                </div>
                <!-- Dirección -->
                <div>
                    <label for="direccion" class="block text-sm font-medium">Dirección</label>
                    <input id="direccion" type="text" class="form-input w-full" placeholder="Ingrese la dirección"
                        name="direccion" 
                        required
                        value="{{ old('direccion', $tienda->direccion) }}">

                        <div id="dirrecion-error" class="text-red-500 text-sm" style="display: none;"></div>
                </div>
                <!-- Cliente -->
                <div>
                    <select id="idCliente" name="idCliente" class="select2 w-full" required>
                        <option value="" disabled selected>Seleccionar Cliente</option>
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->idCliente }}"
                                {{ old('idCliente', $tienda->idCliente) == $cliente->idCliente ? 'selected' : '' }}>
                                {{ $cliente->nombre }} - {{ $cliente->documento }}
                            </option>
                        @endforeach
                    </select>
                    <div id="idCliente-error" class="text-red-500 text-sm" style="display: none;"></div>
                    <!-- @error('idCliente')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror -->
                </div>
                <!-- Celular -->
                <div>
                    <label for="celular" class="block text-sm font-medium">Celular</label>
                    <input id="celular" type="text" class="form-input w-full" placeholder="Ingrese el celular" required
                        name="celular" value="{{ old('celular', $tienda->celular) }}"
                        pattern="^\d{8,}$" 
                        title="El número de celular debe contener solo números y ser mayor a 7 dígitos">
                    <div id="celular-error" class="text-red-500 text-sm" style="display: none;"></div>
                </div>
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input id="email" type="email" class="form-input w-full" placeholder="Ingrese el email"
                        name="email" required value="{{ old('email', $tienda->email) }}">
                        <div id="email-error" class="text-red-500 text-sm" style="display: none;"></div>
                </div>
                <!-- Referencia -->
                <div class="md:col-span-2">
                    <label for="referencia" class="block text-sm font-medium">Referencia</label>
                    <input id="referencia" type="text" class="form-input w-full" placeholder="Ingrese la referencia"
                        name="referencia" required value="{{ old('referencia', $tienda->referencia) }}">
                </div>
                <!-- Latitud -->
                <div>
                    <label for="latitud" class="block text-sm font-medium">Latitud</label>
                    <input id="latitud" type="text" class="form-input w-full" placeholder="Latitud" name="lat"
                        value="{{ old('lat', $tienda->lat) }}" readonly>
                </div>
                <!-- Longitud -->
                <div>
                    <label for="longitud" class="block text-sm font-medium">Longitud</label>
                    <input id="longitud" type="text" name="lng" class="form-input w-full" placeholder="Longitud"
                        value="{{ old('lng', $tienda->lng) }}" readonly>
                </div>

                <!-- Departamento -->
                <div>
                    <label for="departamento" class="block text-sm font-medium">Departamento</label>
                    <select id="departamento" name="departamento" class="form-input w-full">
                        <option value="" disabled selected>Seleccionar Departamento</option>
                        @foreach ($departamentos as $departamento)
                            <option value="{{ $departamento['id_ubigeo'] }}"
                                {{ old('departamento', $tienda->departamento) == $departamento['id_ubigeo'] ? 'selected' : '' }}>
                                {{ $departamento['nombre_ubigeo'] }}
                            </option>
                        @endforeach
                    </select>
                    <div id="departamento-error" class="text-red-500 text-sm" style="display: none;"></div>
                </div>

                <!-- Provincia -->
                <div>
                    <label for="provincia" class="block text-sm font-medium">Provincia</label>
                    <select id="provincia" name="provincia" class="form-input w-full">
                        <option value="" disabled>Seleccionar Provincia</option>
                        @foreach ($provinciasDelDepartamento as $provincia)
                            <option value="{{ $provincia['id_ubigeo'] }}"
                                {{ old('provincia', $tienda->provincia) == $provincia['id_ubigeo'] ? 'selected' : '' }}>
                                {{ $provincia['nombre_ubigeo'] }}
                            </option>
                        @endforeach
                    </select>
                    <div id="provincia-error" class="text-red-500 text-sm" style="display: none;"></div>
                </div>

                <!-- Distrito -->
                <div>
                    <label for="distrito" class="block text-sm font-medium">Distrito</label>
                    <select id="distrito" name="distrito" class="form-input w-full">
                        <option value="" disabled>Seleccionar Distrito</option>
                        @foreach ($distritosDeLaProvincia as $distrito)
                            <option value="{{ $distrito['id_ubigeo'] }}"
                                {{ old('distrito', $tienda->distrito) == $distrito['id_ubigeo'] ? 'selected' : '' }}>
                                {{ $distrito['nombre_ubigeo'] }}
                            </option>
                        @endforeach
                    </select>

                    <div id="distrito-error" class="text-red-500 text-sm" style="display: none;"></div>
                </div>
                <!-- Estado -->
                <!-- Estado -->
                <div class="flex flex-col items-start">
                    <label for="estado" class="block text-sm font-medium mb-2">Estado</label>
                    <div class="w-12 h-6 relative">
                        <!-- Campo hidden para manejar el estado si no está activado -->
                        <input type="hidden" name="estado" value="0">
                        <input type="checkbox"
                            class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                            id="estado" name="estado" value="1" {{ $tienda->estado ? 'checked' : '' }} />
                        <span for="estado"
                            class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                    </div>
                </div>

                <!-- Mapa -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium">Mapa</label>
                    <div id="map" class="w-full h-64 rounded border"></div>
                </div>

                <!-- Botones -->
                <div class="md:col-span-2 flex justify-end mt-4">
                    <a href="{{ route('administracion.tienda') }}" class="btn btn-outline-danger">Cancelar</a>
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
                    provinciaSelect.append(
                        '<option value="" disabled selected>Seleccionar Provincia</option>');

                    data.forEach(function(provincia) {
                        provinciaSelect.append('<option value="' + provincia.id_ubigeo + '">' +
                            provincia.nombre_ubigeo + '</option>');
                    });

                    // Si hay provincia seleccionada previamente, se selecciona automáticamente
                    var provinciaSeleccionada = '{{ old('provincia', $tienda->provincia) }}';
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
                    var distritoSeleccionado = '{{ old('distrito', $tienda->distrito) }}';
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
    let formValid = true; // Bandera que indica si el formulario es válido

    // Validar campos vacíos
function checkEmptyFields() {
    formValid = true; // Asumimos que el formulario es válido inicialmente

    console.log("Verificando campos vacíos..."); // Log para ver si estamos entrando en la función

    // Definir los campos a validar
    const camposRequeridos = [
        '#ruc', '#nombre', '#email', '#celular',  '#referencia', '#dirrecion',
        '#departamento', '#provincia', '#distrito', '#cliente', '#idCliente'
    ];

    // Comprobar si algún campo requerido está vacío
    camposRequeridos.forEach(function(campo) {
        console.log("Verificando campo: " + campo); // Ver qué campo estamos verificando

        if ($(campo).is('select')) {
            // Validación para campos de tipo select (comprobar si no se seleccionó una opción válida)
            if ($(campo).val() === "" || $(campo).val() === null) {
                formValid = false; // Si algún campo está vacío, desactivar el envío
                $(campo).addClass('border-red-500'); // Marcar el campo con borde rojo
                $(campo).siblings('.text-red-500').text('Este campo es obligatorio').show(); // Mostrar el mensaje de error
                console.log("Campo vacío: " + campo); // Log para mostrar el campo vacío
            } else {
                $(campo).removeClass('border-red-500'); // Quitar el borde rojo si no está vacío
                $(campo).siblings('.text-red-500').hide(); // Ocultar el mensaje de error si no está vacío
            }
        } else {
            // Validación para otros tipos de campos (input)
            if ($(campo).val() === '') {
                formValid = false; // Si algún campo está vacío, desactivar el envío
                $(campo).addClass('border-red-500'); // Marcar el campo con borde rojo
                $(campo).siblings('.text-red-500').text('Este campo es obligatorio').show(); // Mostrar el mensaje de error
                console.log("Campo vacío: " + campo); // Log para mostrar el campo vacío
            } else {
                $(campo).removeClass('border-red-500'); // Quitar el borde rojo si no está vacío
                $(campo).siblings('.text-red-500').hide(); // Ocultar el mensaje de error si no está vacío
            }
        }
    });
}
// Añadir evento para los select
$('#departamento, #provincia, #distrito, #idCliente, #dirrecion, #referencia').on('change', function() {
        checkEmptyFields(); // Revalidar campos vacíos cada vez que cambie la selección
    });

     // Interceptar el envío del formulario
     $('#tiendaForm').submit(function(event) {
        console.log("Formulario a enviar..."); // Log para indicar que estamos interceptando el envío
        checkEmptyFields(); // Verificar si hay campos vacíos antes de enviar

        if (!formValid) {
            event.preventDefault(); // Evitar el envío del formulario
            console.log("Formulario no válido, se ha bloqueado el envío"); // Log para ver que el formulario no es válido
            alert('Hay campos vacíos o repetidos. Por favor, corrija los errores y vuelva a intentarlo.'); // Mostrar alerta
        } else {
            console.log("Formulario válido, se enviará"); // Log para ver que el formulario es válido
        }
    });

// Validar RUC en tiempo real
$('#ruc').on('input', function() {
    let ruc = $(this).val();
    console.log("Verificando RUC: " + ruc); // Log para ver el valor del RUC

    // Verificar si el RUC contiene solo números
    if (/[^0-9]/.test(ruc)) {
        $('#ruc').addClass('border-red-500');
        $('#ruc-error').text('El RUC solo debe contener números').show();
        formValid = false;
        return;
    } else {
        $('#ruc').removeClass('border-red-500');
        $('#ruc-error').hide();
    }

    // Verificar que el RUC tenga más de 8 dígitos
    if (ruc.length < 8) {
        $('#ruc').addClass('border-red-500');
        $('#ruc-error').text('El RUC debe tener al menos 8 dígitos').show();
        formValid = false;
        return;
    } else {
        $('#ruc').removeClass('border-red-500');
        $('#ruc-error').hide();
    }

    // Si el RUC tiene más de 8 dígitos, proceder con la validación en el servidor
    $.post('{{ route("validar.ruc") }}', { ruc: ruc, _token: '{{ csrf_token() }}' }, function(response) {
        console.log("Respuesta RUC: ", response); // Log para ver la respuesta del servidor
        if (response.exists) {
            $('#ruc').addClass('border-red-500');
            $('#ruc-error').text('El RUC ya está registrado').show();
            formValid = false; // Desactivar el envío del formulario
        } else {
            $('#ruc').removeClass('border-red-500');
            $('#ruc-error').hide();
            checkEmptyFields(); // Revalidar campos vacíos
        }
    });
});
// Validar Nombre en tiempo real
$('#nombre').on('input', function() {
    let nombre = $(this).val();
    console.log("Verificando Nombre: " + nombre); // Log para ver el valor del nombre

    $.post('{{ route("validar.nombre") }}', { nombre: nombre, _token: '{{ csrf_token() }}' }, function(response) {
        console.log("Respuesta Nombre: ", response); // Log para ver la respuesta del servidor
        if (response.exists) {
            $('#nombre').addClass('border-red-500');
            $('#nombre-error').text('El nombre ya está registrado').show();
            formValid = false; // Desactivar el envío del formulario
        } else {
            $('#nombre').removeClass('border-red-500');
            $('#nombre-error').hide();
            checkEmptyFields(); // Revalidar campos vacíos
        }
    });
});
// Validar Email en tiempo real
$('#email').on('input', function() {
    let email = $(this).val();
    console.log("Verificando Email: " + email); // Log para ver el valor del email

    // Verificar si el correo tiene un formato válido
    let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailPattern.test(email)) {
        $('#email').addClass('border-red-500');
        $('#email-error').text('Por favor ingrese un correo válido').show();
        formValid = false;
        return;
    } else {
        $('#email').removeClass('border-red-500');
        $('#email-error').hide();
    }

    $.post('{{ route("validar.email") }}', { email: email, _token: '{{ csrf_token() }}' }, function(response) {
        console.log("Respuesta Email: ", response); // Log para ver la respuesta del servidor
        if (response.exists) {
            $('#email').addClass('border-red-500');
            $('#email-error').text('El correo electrónico ya está registrado').show();
            formValid = false; // Desactivar el envío del formulario
        } else {
            $('#email').removeClass('border-red-500');
            $('#email-error').hide();
            checkEmptyFields(); // Revalidar campos vacíos
        }
    });
});
// Validar Celular en tiempo real
$('#celular').on('input', function() {
    let celular = $(this).val();
    console.log("Verificando Celular: " + celular); // Log para ver el valor del celular

    // Verificar si el celular contiene solo números
    if (/[^0-9]/.test(celular)) {
        $('#celular').addClass('border-red-500');
        $('#celular-error').text('El celular solo debe contener números').show();
        formValid = false;
        return;
    } else {
        $('#celular').removeClass('border-red-500');
        $('#celular-error').hide();
    }

    // Verificar que el celular tenga más de 8 dígitos
    if (celular.length < 9) {  // Esto asegura que el celular tenga al menos 9 dígitos
        $('#celular').addClass('border-red-500');
        $('#celular-error').text('El celular debe tener al menos 9 dígitos').show();
        formValid = false;
        return;
    } else {
        $('#celular').removeClass('border-red-500');
        $('#celular-error').hide();
    }


    $.post('{{ route("validar.celular") }}', { celular: celular, _token: '{{ csrf_token() }}' }, function(response) {
        console.log("Respuesta Celular: ", response); // Log para ver la respuesta del servidor
        if (response.exists) {
            $('#celular').addClass('border-red-500');
            $('#celular-error').text('El celular ya está registrado').show();
            formValid = false; // Desactivar el envío del formulario
        } else {
            $('#celular').removeClass('border-red-500');
            $('#celular-error').hide();
            checkEmptyFields(); // Revalidar campos vacíos
        }
    });
}); 
});
</script>


    <script>
        // Inicializar Select2
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.select2').forEach(function(select) {
                NiceSelect.bind(select, {
                    searchable: true
                });
            });

            // Inicializar el mapa
            const map = L.map('map').setView([{{ $tienda->lat }}, {{ $tienda->lng }}],
                13); // Coordenadas iniciales de la tienda

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            let marker = L.marker([{{ $tienda->lat }}, {{ $tienda->lng }}]).addTo(
                map); // Agregar el marcador en la posición actual

            map.on('click', function(e) {
                const {
                    lat,
                    lng
                } = e.latlng;

                // Actualizar los inputs de latitud y longitud
                document.getElementById('latitud').value = lat;
                document.getElementById('longitud').value = lng;

                // Agregar marcador al mapa
                marker.setLatLng([lat, lng]);
            });
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
</x-layout.default>
