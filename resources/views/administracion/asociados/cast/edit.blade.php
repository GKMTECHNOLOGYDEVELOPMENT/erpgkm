<x-layout.default>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">


    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Cast</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Editar Cast</span>
            </li>
        </ul>
    </div>
    <!-- Formulario de Crear o Editar Cast -->
    <div class="panel mt-6 p-5 max-w-4x2 mx-auto">
        <h2 class="text-xl font-bold mb-5">EDITAR CAST</h2>
        <div class="p-5">
            <form id="castForm" class="grid grid-cols-1 md:grid-cols-2 gap-4" method="POST"
                action="{{ route('casts.update', $cast->idCast) }}">
                @csrf
                @method('PUT')

                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-medium">Nombre</label>
                    <input id="nombre" type="text" class="form-input w-full" name="nombre" required
                        placeholder="Ingrese el nombre" value="{{ old('nombre', $cast->nombre) }}">

                    <div id="nombre-error" class="text-red-500 text-sm" style="display: none;"></div>
                </div>


                <!-- RUC -->
                <div>
                    <label for="ruc" class="block text-sm font-medium">RUC</label>
                    <input id="ruc" name="ruc" type="text" class="form-input w-full"
                        placeholder="Ingrese el RUC"
                        pattern="^\d{8,}$" required
                        title="Solo se permiten números y debe ser mayor a 7 digitos" value="{{ old('numeroDocumento', $cast->ruc) }}">
                    <div id="ruc-error" class="text-red-500 text-sm" style="display: none;"></div>
                </div>
                <!-- Departamento -->
                <div>
                    <label for="departamento" class="block text-sm font-medium">Departamento</label>
                    <select id="departamento" name="departamento" class="form-input w-full" required>
                        <option value="" disabled selected>Seleccionar Departamento</option>
                        @foreach ($departamentos as $departamento)
                        <option value="{{ $departamento['id_ubigeo'] }}"
                            {{ old('departamento', $cast->departamento) == $departamento['id_ubigeo'] ? 'selected' : '' }}>
                            {{ $departamento['nombre_ubigeo'] }}
                        </option>
                        @endforeach
                    </select>
                    <div id="departamento-error" class="text-red-500 text-sm" style="display: none;"></div>

                </div>
                <!-- Provincia -->
                <div>
                    <label for="provincia" class="block text-sm font-medium">Provincia</label>
                    <select id="provincia" name="provincia" class="form-input w-full" disabled required>
                        <option value="" disabled selected>Seleccionar Provincia</option>
                    </select>
                    <div id="provincia-error" class="text-red-500 text-sm" style="display: none;"></div>

                </div>
                <!-- Distrito -->
                <div>
                    <label for="distrito" class="block text-sm font-medium">Distrito</label>
                    <select id="distrito" name="distrito" class="form-input w-full" disabled required>
                        <option value="" disabled selected>Seleccionar Distrito</option>
                    </select>
                    <div id="distrito-error" class="text-red-500 text-sm" style="display: none;"></div>

                </div>
                <div>
                    <label for="telefono" class="block text-sm font-medium">Teléfono</label>
                    <input id="telefono" type="text" class="form-input w-full" name="telefono"
                        value="{{ old ('telefono', $cast->telefono)}}"
                        required
                        pattern="^\d{8,}$"
                        title="El número de celular debe contener solo números y ser mayor a 7 dígitos"
                        placeholder="Ingrese el teléfono">
                    <div id="telefono-error" class="text-red-500 text-sm" style="display: none;"></div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input id="email" type="email" class="form-input w-full" name="email" required placeholder="Ingrese el email" value="{{ old('email', $cast->email) }}"
                        title="Por favor, ingresa un correo electrónico válido. Ejemplo: usuario@dominio.com">
                    <div id="email-error" class="text-red-500 text-sm" style="display: none;"></div>

                </div>

                <!-- Dirección -->
                <div>
                    <label for="direccion" class="block text-sm font-medium">Dirección</label>
                    <input id="direccion" type="text" name="direccion" class="form-input w-full" required
                        placeholder="Ingrese la dirección" value="{{ old('direccion', $cast->direccion) }}">

                    <div id="dirrecion-error" class="text-red-500 text-sm" style="display: none;"></div>
                </div>
                <!-- Estado -->
                <div>
                    <label for="estado" class="block text-sm font-medium">Estado</label>
                    <div class="ml-4 w-12 h-6 relative">
                        <input type="hidden" name="estado" value="0">
                        <input type="checkbox" id="estado" name="estado"
                            class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                            value="1" {{ old('estado', $cast->estado) ? 'checked' : '' }} />
                        <span for="estado"
                            class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end items-center mt-4">
                    <a href="{{ route('administracion.cast') }}" class="btn btn-outline-danger">Cancelar</a>
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
                    var provinciaSeleccionada = '{{ old('
                    provincia ', $cast->provincia) }}';
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
                    var distritoSeleccionado = '{{ old('
                    distrito ', $cast->distrito) }}';
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

<script>
           
           $(document).ready(function() {
           let formValid = true; // Bandera que indica si el formulario es válido
       
           // Validar campos vacíos
       function checkEmptyFields() {
           formValid = true; // Asumimos que el formulario es válido inicialmente
       
           console.log("Verificando campos vacíos..."); // Log para ver si estamos entrando en la función
       
           // Definir los campos a validar
           const camposRequeridos = [
               '#ruc', '#nombre', '#email', '#telefono',  '#referencia', '#dirrecion',
               '#departamento', '#provincia', '#distrito'
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
       $('#departamento, #provincia, #distrito, #dirrecion').on('change', function() {
               checkEmptyFields(); // Revalidar campos vacíos cada vez que cambie la selección
           });
       
            // Interceptar el envío del formulario
            $('#castForm').submit(function(event) {
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
           $.post('{{ route("validar.ruccast") }}', { ruc: ruc, _token: '{{ csrf_token() }}' }, function(response) {
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
       
           $.post('{{ route("validar.nombrecast") }}', { nombre: nombre, _token: '{{ csrf_token() }}' }, function(response) {
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
       
           $.post('{{ route("validar.emailcast") }}', { email: email, _token: '{{ csrf_token() }}' }, function(response) {
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
       // Validar telefono en tiempo real
       $('#telefono').on('input', function() {
           let telefono = $(this).val();
           console.log("Verificando telefono: " + telefono); // Log para ver el valor del telefono
       
           // Verificar si el telefono contiene solo números
           if (/[^0-9]/.test(telefono)) {
               $('#telefono').addClass('border-red-500');
               $('#telefono-error').text('El telefono solo debe contener números').show();
               formValid = false;
               return;
           } else {
               $('#telefono').removeClass('border-red-500');
               $('#telefono-error').hide();
           }
       
           // Verificar que el telefono tenga más de 8 dígitos
           if (telefono.length < 9) {  // Esto asegura que el telefono tenga al menos 9 dígitos
               $('#telefono').addClass('border-red-500');
               $('#telefono-error').text('El telefono debe tener al menos 9 dígitos').show();
               formValid = false;
               return;
           } else {
               $('#telefono').removeClass('border-red-500');
               $('#telefono-error').hide();
           }
       
       
           $.post('{{ route("validar.telefonocast") }}', { telefono: telefono, _token: '{{ csrf_token() }}' }, function(response) {
               console.log("Respuesta telefono: ", response); // Log para ver la respuesta del servidor
               if (response.exists) {
                   $('#telefono').addClass('border-red-500');
                   $('#telefono-error').text('El telefono ya está registrado').show();
                   formValid = false; // Desactivar el envío del formulario
               } else {
                   $('#telefono').removeClass('border-red-500');
                   $('#telefono-error').hide();
                   checkEmptyFields(); // Revalidar campos vacíos
               }
           });
       }); 
       });
       </script>
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
</x-layout.default>