<x-layout.default>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .panel {
            overflow: visible !important;
            /* Asegura que el modal no restrinja contenido */
        }
        
        #myTable1 {
            min-width: 1000px;
            /* puedes ajustar si quieres más ancho */
        }
        .dataTables_length select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            padding-right: 1.5rem;
            /* Ajusta espacio a la derecha para que el texto no se corte */
            background-image: none;
            /* Opcional, elimina cualquier ícono */
        }
    </style>
    <div x-data="multipleTable">
        <div>
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="javascript:;" class="text-primary hover:underline">Asociados</a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>Proveedores</span>
                </li>
            </ul>
        </div>
        <div class="panel mt-6">
            <div class="md:absolute md:top-5 ltr:md:left-5 rtl:md:right-5">
                <div class="flex flex-wrap items-center justify-center gap-2 mb-5 sm:justify-start md:flex-nowrap">
                    <!-- Botón Exportar a Excel -->
                    <button type="button" class="btn btn-success btn-sm flex items-center gap-2"
                    onclick="window.location='{{ route('proveedores.exportExcel') }}'">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                            <path
                                d="M4 3H20C21.1046 3 22 3.89543 22 5V19C22 20.1046 21.1046 21 20 21H4C2.89543 21 2 20.1046 2 19V5C2 3.89543 2 3 4 3Z"
                                stroke="currentColor" stroke-width="1.5" />
                            <path d="M16 10L8 14M8 10L16 14" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span>Excel</span>
                    </button>

                    <!-- Botón Exportar a PDF -->
                    <button type="button" class="btn btn-danger btn-sm flex items-center gap-2"
                    onclick="window.location='{{ route('proveedores.pdf') }}'">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                            <path
                                d="M2 5H22M2 5H22C22 6.10457 21.1046 7 20 7H4C2.89543 7 2 6.10457 2 5ZM2 5V19C2 20.1046 2.89543 21 4 21H20C21.1046 21 22 20.1046 22 19V5M9 14L15 14"
                                stroke="currentColor" stroke-width="1.5" />
                            <path d="M12 11L12 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                        <span>PDF</span>
                    </button>
                    <!-- Botón Agregar -->
                    <button type="button" class="btn btn-primary btn-sm flex items-center gap-2"
                        @click="$dispatch('toggle-modal')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                            fill="none">
                            <path
                                d="M5 8C5 6.89543 5.89543 6 7 6H17C18.1046 6 19 6.89543 19 8V16C19 17.1046 18.1046 18 17 18H7C5.89543 18 5 17.1046 5 16V8Z"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M9 12H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M12 9V15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M3 5H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M3 19H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        <span>Agregar</span>
                    </button>
                </div>
            </div>

            <table id="myTable1" class="w-full min-w-[1000px] table whitespace-nowrap">
                <thead>
                    <tr>
                        <th>Tipo Documento</th>
                        <th>Documento</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Área</th>
                        <th>Dirección</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!--Modal -->

    <div x-data="{ open: false }" class="mb-5" @toggle-modal.window="open = !open">
        <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
                <div x-show="open" x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-3xl my-8 animate__animated animate__zoomInUp">
                    <!-- Header del Modal -->
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <h5 class="font-bold text-lg">Agregar Proveedor</h5>
                        <button type="button" class="text-white-dark hover:text-dark" @click="open = false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" class="w-6 h-6">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <div class="modal-scroll">
                        <!-- Formulario -->
                        <form class="p-5 space-y-4" action="{{ route('proveedor.store') }}" id="proveedorForm">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                 <!-- Nombre -->
                                 <div>
                                    <label for="nombre" class="block text-sm font-medium">Nombre</label>
                                    <input id="nombre" type="text" class="form-input w-full" placeholder="Ingrese el nombre" required
                                        name="nombre">
                                        <div id="nombre-error" class="text-red-500 text-sm" style="display: none;"></div>

                                </div>


                                <!-- Tipo Documento -->
                                <div>
                                    <label for="idTipoDocumento" class="block text-sm font-medium">Tipo Documento</label>
                                    <select id="idTipoDocumento" name="idTipoDocumento" class="select2 w-full" style="display:none" required>
                                        <option value="" disabled selected>Seleccionar Tipo Documento</option>
                                        @foreach ($tiposDocumento as $tipoDocumento)
                                            <option value="{{ $tipoDocumento->idTipoDocumento }}">
                                                {{ $tipoDocumento->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <div id="idTipoDocumento-error" class="text-red-500 text-sm" style="display: none;"></div>
                                </div>
                                <!-- Número de Documento -->
                                <div>
                                    <label for="numeroDocumento" class="block text-sm font-medium">Número de
                                        Documento</label>
                                    <input id="numeroDocumento" type="text" class="form-input w-full"
                                        name="numeroDocumento" placeholder="Ingrese el número de documento"
                                        pattern="^\d{8,}$" required
                                        title="Solo se permiten números y debe ser mayor a 7 digitos">
                                        <div id="numeroDocumento-error" class="text-red-500 text-sm" style="display: none;"></div>
                                </div>
                                <!-- Departamento -->
                                <div>
                                    <label for="departamento" class="block text-sm font-medium">Departamento</label>
                                    <select id="departamento" name="departamento" class="form-input w-full" required>
                                        <option value="" disabled selected>Seleccionar Departamento</option>
                                        @foreach ($departamentos as $departamento)
                                            <option value="{{ $departamento['id_ubigeo'] }}">
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

                                <!-- Area -->
                                <div>
                                    <label for="idArea" class="block text-sm font-medium">Área</label>
                                    <select id="idArea" name="idArea" class="select2 w-full" style="display:none" required>
                                        <option value="" disabled selected>Seleccionar Área</option>
                                        @foreach ($tiposArea as $tipoArea)
                                            <option value="{{ $tipoArea->idTipoArea }}">
                                                {{ $tipoArea->nombre }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <div id="idArea-error" class="text-red-500 text-sm" style="display: none;"></div>
                                    
                                    
                                </div>
                                <!-- Código Postal -->
                                <div>
                                    <label for="codigoPostal" class="block text-sm font-medium">Código Postal</label>
                                    <input id="codigoPostal" type="text" class="form-input w-full" required
                                        name="codigoPostal" placeholder="Ingrese el código postal"
                                        pattern="^\d{8,}$" required
                                        title="Solo se permiten números y debe ser mayor a 7 digitos">
                                        <div id="codigoPostal-error" class="text-red-500 text-sm" style="display: none;"></div>
                                </div>
                               <!-- Teléfono -->
                               <div>
                                    <label for="telefono" class="block text-sm font-medium">Teléfono</label>
                                    <input id="telefono" type="text" class="form-input w-full" name="telefono"
                                    required
                                    pattern="^\d{8,}$" 
                                    title="El número de celular debe contener solo números y ser mayor a 7 dígitos"
                                    placeholder="Ingrese el teléfono">
                                    <div id="telefono-error" class="text-red-500 text-sm" style="display: none;"></div>
                                </div>

                               <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium">Email</label>
                                    <input id="email" type="email" class="form-input w-full" placeholder="Ingrese el email"
                                        name="email" required
                                        title="Por favor, ingresa un correo electrónico válido. Ejemplo: usuario@dominio.com">
                                        <div id="email-error" class="text-red-500 text-sm" style="display: none;"></div>

                                </div>
                                  <!-- Dirección -->
                                  <div>
                                    <label for="direccion" class="block text-sm font-medium">Dirección</label>
                                    <input id="direccion" type="text" name="direccion" class="form-input w-full" required
                                        placeholder="Ingrese la dirección">
                                        <div id="dirrecion-error" class="text-red-500 text-sm" style="display: none;"></div>
                                </div>
                            </div>
                            <!-- Botones -->
                            <div class="flex justify-end items-center mt-4">
                                <button type="button" class="btn btn-outline-danger"
                                    @click="open = false">Cancelar</button>
                                <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
$(document).ready(function() {
    let formValid = true; // Bandera que indica si el formulario es válido

    // Función para deshabilitar o habilitar el botón
    function toggleSaveButton() {
        console.log("Estado del formulario:", formValid ? "Válido" : "Inválido");
        $('#proveedorForm button[type="submit"]').prop('disabled', !formValid); // Habilitar o deshabilitar según formValid
    }

    // Función para verificar campos vacíos
    function checkEmptyFields() {
        formValid = true; // Asumimos que el formulario es válido inicialmente
        const camposRequeridos = [
            '#numeroDocumento', '#nombre', '#email', '#telefono', '#direccion',
            '#departamento', '#provincia', '#distrito'
        ];

        camposRequeridos.forEach(function(campo) {
            if ($(campo).is('select') && ($(campo).val() === "" || $(campo).val() === null)) {
                formValid = false;
                $(campo).addClass('border-red-500');
                $(campo).siblings('.text-red-500').text('Este campo es obligatorio').show();
            } else if (!$(campo).is('select') && $(campo).val() === '') {
                formValid = false;
                $(campo).addClass('border-red-500');
                $(campo).siblings('.text-red-500').text('Este campo es obligatorio').show();
            } else {
                $(campo).removeClass('border-red-500');
                $(campo).siblings('.text-red-500').hide();
            }
        });
        toggleSaveButton(); // Llamar después de verificar campos vacíos
    }

    // Validación en tiempo real del numeroDocumento
    $('#numeroDocumento').on('input', function() {
        let numeroDocumento = $(this).val();
        formValid = true; // Resetear la bandera antes de realizar la validación
        if (/[^0-9]/.test(numeroDocumento) || numeroDocumento.length < 8) {
            $('#numeroDocumento').addClass('border-red-500');
            $('#numeroDocumento-error').text('El número debe tener al menos 8 dígitos y solo números').show();
            formValid = false;
        } else {
            $.post('{{ route("validar.numerodocumentoproveedores") }}', { numeroDocumento: numeroDocumento, _token: '{{ csrf_token() }}' }, function(response) {
                if (response.exists) {
                    $('#numeroDocumento').addClass('border-red-500');
                    $('#numeroDocumento-error').text('El numeroDocumento ya está registrado').show();
                    formValid = false;
                } else {
                    $('#numeroDocumento').removeClass('border-red-500');
                    $('#numeroDocumento-error').hide();
                }
                toggleSaveButton(); // Actualizar el estado del botón después de la validación
            });
        }
        toggleSaveButton(); // Asegurarse de que el estado se actualiza
    });

    // Validación en tiempo real del nombre
    $('#nombre').on('input', function() {
        let nombre = $(this).val();
        formValid = true; // Resetear la bandera antes de realizar la validación
        $.post('{{ route("validar.nombreproveedores") }}', { nombre: nombre, _token: '{{ csrf_token() }}' }, function(response) {
            if (response.exists) {
                $('#nombre').addClass('border-red-500');
                $('#nombre-error').text('El nombre ya está registrado').show();
                formValid = false;
            } else {
                $('#nombre').removeClass('border-red-500');
                $('#nombre-error').hide();
            }
            toggleSaveButton(); // Actualizar el estado del botón después de la validación
        });
    });

    // Validación en tiempo real del email
    $('#email').on('input', function() {
        let email = $(this).val();
        formValid = true; // Resetear la bandera antes de realizar la validación
        let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailPattern.test(email)) {
            $('#email').addClass('border-red-500');
            $('#email-error').text('Por favor ingrese un correo válido').show();
            formValid = false;
        } else {
            $.post('{{ route("validar.nombreproveedores") }}', { email: email, _token: '{{ csrf_token() }}' }, function(response) {
                if (response.exists) {
                    $('#email').addClass('border-red-500');
                    $('#email-error').text('El correo electrónico ya está registrado').show();
                    formValid = false;
                } else {
                    $('#email').removeClass('border-red-500');
                    $('#email-error').hide();
                }
                toggleSaveButton(); // Actualizar el estado del botón después de la validación
            });
        }
        toggleSaveButton(); // Asegurarse de que el estado se actualiza
    });

    // Validación en tiempo real del teléfono
    $('#telefono').on('input', function() {
        let telefono = $(this).val();
        formValid = true; // Resetear la bandera antes de realizar la validación
        if (/[^0-9]/.test(telefono) || telefono.length < 9) {
            $('#telefono').addClass('border-red-500');
            $('#telefono-error').text('El teléfono debe tener al menos 9 dígitos').show();
            formValid = false;
        } else {
            $.post('{{ route("validar.telefonoproveedores") }}', { telefono: telefono, _token: '{{ csrf_token() }}' }, function(response) {
                if (response.exists) {
                    $('#telefono').addClass('border-red-500');
                    $('#telefono-error').text('El teléfono ya está registrado').show();
                    formValid = false;
                } else {
                    $('#telefono').removeClass('border-red-500');
                    $('#telefono-error').hide();
                }
                toggleSaveButton(); // Actualizar el estado del botón después de la validación
            });
        }
        toggleSaveButton(); // Asegurarse de que el estado se actualiza
    });

    // Verificar que todos los campos sean válidos antes de enviar
    $('#castForm').submit(function(event) {
        event.preventDefault(); // Prevenir el envío hasta que validemos
        checkEmptyFields(); // Verificar si hay campos vacíos

        // Verificar que las validaciones asíncronas se han completado
        $.when(
            $.post('{{ route("validar.numerodocumentoproveedores") }}', { numeroDocumento: $('#numeroDocumento').val(), _token: '{{ csrf_token() }}' }),
            $.post('{{ route("validar.nombreproveedores") }}', { nombre: $('#nombre').val(), _token: '{{ csrf_token() }}' }),
            $.post('{{ route("validar.emailproveedores") }}', { email: $('#email').val(), _token: '{{ csrf_token() }}' }),
            $.post('{{ route("validar.telefonoproveedores") }}', { telefono: $('#telefono').val(), _token: '{{ csrf_token() }}' })
        ).done(function(numeroDocumentoResp, nombreResp, emailResp, telefonoResp) {
            formValid = true; // Reseteamos la bandera a verdadero al inicio de la validación

            // Verificamos si alguna de las respuestas indica un error
            if (numeroDocumentoResp.exists || nombreResp.exists || emailResp.exists || telefonoResp.exists) {
                formValid = false; // Si hay algún error, deshabilitamos el botón
            }
            toggleSaveButton(); // Actualizamos el estado del botón según el valor de formValid

            // Si no hay errores, enviamos el formulario
            if (formValid) {
                $('#castForm')[0].submit();
            } else {
                alert("Hay campos con datos repetidos o inválidos. Corrija los errores.");
            }
        });
    });
});
document.addEventListener("DOMContentLoaded", function() {
    // Inicializar Nice Select 2 en todos los selects que tengan la clase .select2
    document.querySelectorAll('.select2').forEach(function(select) {
        NiceSelect.bind(select, {
            searchable: true
        });
    });
});



</script>


    <!-- En tu archivo Blade -->
    <script>
        window.sessionMessages = {
            success: '{{ session('success') }}',
            error: '{{ session('error') }}',
        };
    </script>

    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}', // Define el token CSRF
            routeProveedorStore: '{{ route('proveedor.store') }}' // Define la ruta del endpoint
        };
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
    <script src="{{ asset('assets/js/proveedores/proveedoresstore.js') }}"></script>
    <script src="{{ asset('assets/js/notificacion.js') }}"></script>
    <script src="{{ asset('assets/js/ubigeo.js') }}"></script>
    <script src="{{ asset('assets/js/proveedores/proveedores.js') }}"></script>
    <script src="/assets/js/simple-datatables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>

</x-layout.default>
