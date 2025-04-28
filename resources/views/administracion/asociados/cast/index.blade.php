<x-layout.default>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                    <span>CAST</span>
                </li>
            </ul>
        </div>
        <div class="panel mt-6">
            <div class="md:absolute md:top-5 ltr:md:left-5 rtl:md:right-5">
                <div class="flex flex-wrap items-center justify-center gap-2 mb-5 sm:justify-start md:flex-nowrap">
                    <!-- Botón Exportar a Excel -->
                    <button type="button" class="btn btn-success btn-sm flex items-center gap-2"
                        @click="window.location.href = '{{ route('cast.exportExcel') }}'">
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
                        @click="window.location.href = '{{ route('reporte.cast') }}'">
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
                                d="M12 2C13.6569 2 15 3.34315 15 5C15 6.65685 13.6569 8 12 8C10.3431 8 9 6.65685 9 5C9 3.34315 10.3431 2 12 2Z"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M12 8V15M12 15L7 18M12 15L17 18" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M7 18C8.65685 18 10 19.3431 10 21C10 22.6569 8.65685 24 7 24C5.34315 24 4 22.6569 4 21C4 19.3431 5.34315 18 7 18ZM17 18C18.6569 18 20 19.3431 20 21C20 22.6569 18.6569 24 17 24C15.3431 24 14 22.6569 14 21C14 19.3431 15.3431 18 17 18Z"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        <span>Agregar</span>
                    </button>
                </div>
            </div>

            <table id="myTable1" class="table whitespace-nowrap">
                <thead>
                    <tr>
                        <th>RUC</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Departamento</th>
                        <th>Provincia</th>
                        <th>Distrito</th>
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
                        <h5 class="font-bold text-lg">Agregar Cast</h5>
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
                        <form class="p-5 space-y-4" id="castForm">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                <!-- Nombre -->
                                <div>
                                    <label for="nombre" class="block text-sm font-medium">Nombre</label>
                                    <input id="nombre" type="text" class="form-input w-full"
                                        placeholder="Ingrese el nombre" required name="nombre">
                                    <div id="nombre-error" class="text-red-500 text-sm" style="display: none;"></div>

                                </div>
                                <!-- RUC -->
                                <div>
                                    <label for="ruc" class="block text-sm font-medium">RUC</label>
                                    <input id="ruc" name="ruc" type="text" class="form-input w-full"
                                        placeholder="Ingrese el RUC" pattern="^\d{8,}$" required
                                        title="Solo se permiten números y debe ser mayor a 7 digitos">
                                    <div id="ruc-error" class="text-red-500 text-sm" style="display: none;"></div>
                                </div>
                                <!-- Teléfono -->
                                <div>
                                    <label for="telefono" class="block text-sm font-medium">Teléfono</label>
                                    <input id="telefono" type="text" class="form-input w-full" name="telefono"
                                        required pattern="^\d{8,}$"
                                        title="El número de celular debe contener solo números y ser mayor a 7 dígitos"
                                        placeholder="Ingrese el teléfono">
                                    <div id="telefono-error" class="text-red-500 text-sm" style="display: none;">
                                    </div>
                                </div>
                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium">Email</label>
                                    <input id="email" type="email" class="form-input w-full"
                                        placeholder="Ingrese el email" name="email" required
                                        title="Por favor, ingresa un correo electrónico válido. Ejemplo: usuario@dominio.com">
                                    <div id="email-error" class="text-red-500 text-sm" style="display: none;"></div>

                                </div>

                                <!-- Dirección -->
                                <div>
                                    <label for="direccion" class="block text-sm font-medium">Dirección</label>
                                    <input id="direccion" type="text" name="direccion" class="form-input w-full"
                                        required placeholder="Ingrese la dirección">
                                    <div id="dirrecion-error" class="text-red-500 text-sm" style="display: none;">
                                    </div>
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

                                    <div id="departamento-error" class="text-red-500 text-sm" style="display: none;">
                                    </div>
                                </div>

                                <!-- Provincia -->
                                <div>
                                    <label for="provincia" class="block text-sm font-medium">Provincia</label>
                                    <select id="provincia" name="provincia" class="form-input w-full" disabled
                                        required>
                                        <option value="" disabled selected>Seleccionar Provincia</option>
                                    </select>
                                    <div id="provincia-error" class="text-red-500 text-sm" style="display: none;">
                                    </div>
                                </div>
                                <!-- Distrito -->
                                <div>
                                    <label for="distrito" class="block text-sm font-medium">Distrito</label>
                                    <select id="distrito" name="distrito" class="form-input w-full" disabled
                                        required>
                                        <option value="" disabled selected>Seleccionar Distrito</option>
                                    </select>

                                    <div id="distrito-error" class="text-red-500 text-sm" style="display: none;">
                                    </div>
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
                $('#castForm button[type="submit"]').prop('disabled', !
                formValid); // Habilitar o deshabilitar según formValid
            }

            // Función para verificar campos vacíos
            function checkEmptyFields() {
                formValid = true; // Asumimos que el formulario es válido inicialmente
                const camposRequeridos = [
                    '#ruc', '#nombre', '#email', '#telefono', '#direccion',
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

            // Validación en tiempo real del RUC
            $('#ruc').on('input', function() {
                let ruc = $(this).val();
                formValid = true; // Resetear la bandera antes de realizar la validación
                if (/[^0-9]/.test(ruc) || ruc.length < 8) {
                    $('#ruc').addClass('border-red-500');
                    $('#ruc-error').text('El RUC debe tener al menos 8 dígitos y solo números').show();
                    formValid = false;
                } else {
                    $.post('{{ route('validar.ruccast') }}', {
                        ruc: ruc,
                        _token: '{{ csrf_token() }}'
                    }, function(response) {
                        if (response.exists) {
                            $('#ruc').addClass('border-red-500');
                            $('#ruc-error').text('El RUC ya está registrado').show();
                            formValid = false;
                        } else {
                            $('#ruc').removeClass('border-red-500');
                            $('#ruc-error').hide();
                        }
                        toggleSaveButton
                    (); // Actualizar el estado del botón después de la validación
                    });
                }
                toggleSaveButton(); // Asegurarse de que el estado se actualiza
            });

            // Validación en tiempo real del nombre
            $('#nombre').on('input', function() {
                let nombre = $(this).val();
                formValid = true; // Resetear la bandera antes de realizar la validación
                $.post('{{ route('validar.nombrecast') }}', {
                    nombre: nombre,
                    _token: '{{ csrf_token() }}'
                }, function(response) {
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
                    $.post('{{ route('validar.emailcast') }}', {
                        email: email,
                        _token: '{{ csrf_token() }}'
                    }, function(response) {
                        if (response.exists) {
                            $('#email').addClass('border-red-500');
                            $('#email-error').text('El correo electrónico ya está registrado')
                            .show();
                            formValid = false;
                        } else {
                            $('#email').removeClass('border-red-500');
                            $('#email-error').hide();
                        }
                        toggleSaveButton
                    (); // Actualizar el estado del botón después de la validación
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
                    $.post('{{ route('validar.telefonocast') }}', {
                        telefono: telefono,
                        _token: '{{ csrf_token() }}'
                    }, function(response) {
                        if (response.exists) {
                            $('#telefono').addClass('border-red-500');
                            $('#telefono-error').text('El teléfono ya está registrado').show();
                            formValid = false;
                        } else {
                            $('#telefono').removeClass('border-red-500');
                            $('#telefono-error').hide();
                        }
                        toggleSaveButton
                    (); // Actualizar el estado del botón después de la validación
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
                    $.post('{{ route('validar.ruccast') }}', {
                        ruc: $('#ruc').val(),
                        _token: '{{ csrf_token() }}'
                    }),
                    $.post('{{ route('validar.nombrecast') }}', {
                        nombre: $('#nombre').val(),
                        _token: '{{ csrf_token() }}'
                    }),
                    $.post('{{ route('validar.emailcast') }}', {
                        email: $('#email').val(),
                        _token: '{{ csrf_token() }}'
                    }),
                    $.post('{{ route('validar.telefonocast') }}', {
                        telefono: $('#telefono').val(),
                        _token: '{{ csrf_token() }}'
                    })
                ).done(function(rucResp, nombreResp, emailResp, telefonoResp) {
                    formValid =
                    true; // Reseteamos la bandera a verdadero al inicio de la validación

                    // Verificamos si alguna de las respuestas indica un error
                    if (rucResp.exists || nombreResp.exists || emailResp.exists || telefonoResp
                        .exists) {
                        formValid = false; // Si hay algún error, deshabilitamos el botón
                    }
                    toggleSaveButton
                (); // Actualizamos el estado del botón según el valor de formValid

                    // Si no hay errores, enviamos el formulario
                    if (formValid) {
                        $('#castForm')[0].submit();
                    } else {
                        alert("Hay campos con datos repetidos o inválidos. Corrija los errores.");
                    }
                });
            });
        });
    </script>


    <script>
        window.sessionMessages = {
            success: '{{ session('success') }}',
            error: '{{ session('error') }}',
        };
    </script>

    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}', // Define el token CSRF
            routeCastStore: '{{ route('cast.store') }}' // Define la ruta del endpoint
        };
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
    <script src="{{ asset('assets/js/cast/caststore.js') }}"></script>
    <script src="{{ asset('assets/js/notificacion.js') }}"></script>
    <script src="{{ asset('assets/js/ubigeo.js') }}"></script>
    <script src="{{ asset('assets/js/cast/cast.js') }}"></script>
    <script src="/assets/js/simple-datatables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>

</x-layout.default>
