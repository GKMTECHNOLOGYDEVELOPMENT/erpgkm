<x-layout.default>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{ route('usuario') }}" class="text-primary hover:underline">Usuarios</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Editar Usuario</span>
            </li>
        </ul>

        <div class="pt-5">

            <!-- 🔥 UN SOLO x-data -->
            <div x-data="tabsComponent({{ $usuario->idUsuario }})" x-init="init()">

                <!-- Tabs con iconos -->
                <ul class="sm:flex font-semibold border-b mb-5 whitespace-nowrap overflow-y-auto">
                    <li class="inline-block">
                        <a href="javascript:;" :class="{ '!border-primary text-primary': tab == 'perfil' }"
                            @click="loadTab('perfil')"
                            class="flex gap-2 p-4 border-b border-transparent hover:border-primary hover:text-primary">
                            <i class="fa-regular fa-user"></i>
                            Perfil
                        </a>
                    </li>

                    <li class="inline-block">
                        <a href="javascript:;" :class="{ '!border-primary text-primary': tab == 'info-salud' }"
                            @click="loadTab('info-salud')"
                            class="flex gap-2 p-4 border-b border-transparent hover:border-primary hover:text-primary">
                            <i class="fa-regular fa-heart"></i>
                            Familiar y Salud
                        </a>
                    </li>

                    <li class="inline-block">
                        <a href="javascript:;" :class="{ '!border-primary text-primary': tab == 'payment-details' }"
                            @click="loadTab('payment-details')"
                            class="flex gap-2 p-4 border-b border-transparent hover:border-primary hover:text-primary">
                            <i class="fa-regular fa-credit-card"></i>
                            Detalles de Pago
                        </a>
                    </li>

                    <li class="inline-block">
                        <a href="javascript:;" :class="{ '!border-primary text-primary': tab == 'informacion' }"
                            @click="loadTab('informacion')"
                            class="flex gap-2 p-4 border-b border-transparent hover:border-primary hover:text-primary">
                            <i class="fa-regular fa-briefcase"></i>
                            Datos Laborales
                        </a>
                    </li>

                    <li class="inline-block">
                        <a href="javascript:;" :class="{ '!border-primary text-primary': tab == 'asignado' }"
                            @click="loadTab('asignado')"
                            class="flex gap-2 p-4 border-b border-transparent hover:border-primary hover:text-primary">
                            <i class="fa-regular fa-user-check"></i>
                            Asignado
                        </a>
                    </li>

                    <li class="inline-block">
                        <a href="javascript:;" :class="{ '!border-primary text-primary': tab == 'preferences' }"
                            @click="loadTab('preferences')"
                            class="flex gap-2 p-4 border-b border-transparent hover:border-primary hover:text-primary">
                            <i class="fa-regular fa-file-lines"></i>
                            Documentos
                        </a>
                    </li>

                    <li class="inline-block">
                        <a href="javascript:;" :class="{ '!border-primary text-primary': tab == 'danger-zone' }"
                            @click="loadTab('danger-zone')"
                            class="flex gap-2 p-4 border-b border-transparent hover:border-primary hover:text-primary">
                            <i class="fa-regular fa-triangle-exclamation"></i>
                            Zona de Peligro
                        </a>
                    </li>
                </ul>

                <div class="panel mt-6 p-5 relative min-h-[200px]">

                    <!-- PRELOADER -->
                    <div x-show="loading"
                        class="absolute inset-0 flex items-center justify-center bg-white/70 dark:bg-[#0e1726]/70 z-10 rounded-md"
                        x-transition>
                        <div class="flex flex-col items-center gap-3">
                            <i class="fas fa-spinner fa-spin text-primary text-3xl"></i>
                            <span class="text-sm text-gray-600 dark:text-gray-300">
                                Cargando sección...
                            </span>
                        </div>
                    </div>

                    <!-- CONTENIDO -->
                    <div x-html="content"></div>

                </div>


            </div>

        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.5/dist/signature_pad.umd.min.js"></script>
    <script src="{{ asset('assets/js/ubigeo.js') }}"></script>
    <script src="{{ asset('assets/js/usuario/tabs/datos-laborales.js') }}"></script>
    <script src="{{ asset('assets/js/usuario/tabs/danger-zone.js') }}"></script>
    <script src="{{ asset('assets/js/usuario/tabs/payment-details.js') }}"></script>
    <script src="{{ asset('assets/js/usuario/tabs/perfil-usuario.js') }}"></script>
    <script src="{{ asset('assets/js/usuario/tabs/familia-salud.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


    <script>
        function tabsComponent(usuarioId) {
            return {
                tab: 'perfil',
                content: '',
                loading: false, // 👈 nuevo

                async init() {
                    await this.loadTab(this.tab);
                },

                async loadTab(tabName) {
                    this.tab = tabName;
                    this.loading = true; // 👈 activar preload

                    try {
                        let response = await fetch(`/usuario/${usuarioId}/tab/${tabName}`);

                        if (!response.ok) {
                            throw new Error('Error cargando tab');
                        }

                        this.content = await response.text();
                    } catch (error) {
                        console.error(error);
                        this.content = `<div class="text-red-500">Error cargando contenido</div>`;
                    } finally {
                        this.loading = false; // 👈 apagar preload
                    }
                }
            }
        }
    </script>


    <script>
        $(document).ready(function() {
            // ============================================
            // ACTUALIZAR INFORMACIÓN GENERAL
            // ============================================
            $(document).on('click', '#update-button', function(e) {
                e.preventDefault();

                console.log('1. Botón clickeado');

                let formData = new FormData($('#update-forma')[0]);
                let userId = $('#update-forma').data('userid');
                let csrfToken = $('meta[name="csrf-token"]').attr('content');

                console.log('2. User ID:', userId);
                console.log('3. URL:', '/usuario/' + userId + '/informacion-general');
                console.log('4. CSRF Token:', csrfToken ? 'Existe' : 'No existe');

                // Mostrar los datos que se van a enviar
                console.log('5. Datos del formulario:');
                for (let pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }

                // Validar correos
                let correo = $('#correo').val();
                let correoPersonal = $('#correo_personal').val();

                if (correo && !isValidEmail(correo)) {
                    toastr.error('El correo corporativo no tiene un formato válido');
                    return;
                }

                if (correoPersonal && !isValidEmail(correoPersonal)) {
                    toastr.error('El correo personal no tiene un formato válido');
                    return;
                }

                console.log('6. Validación de correos pasada');

                $.ajax({
                    url: '/usuario/' + userId + '/informacion-general',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    beforeSend: function() {
                        console.log('7. Enviando petición AJAX...');
                    },
                    success: function(response) {
                        console.log('8. Respuesta exitosa:', response);
                        if (response.success) {
                            toastr.success(response.message);

                            // Actualizar avatar si cambió
                            if (response.data && response.data.avatar) {
                                $('#profile-img').attr('src', response.data.avatar);
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('9. Error en AJAX:');
                        console.log('Status:', status);
                        console.log('Error:', error);
                        console.log('Respuesta:', xhr.responseText);
                        console.log('Status Code:', xhr.status);

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                toastr.error(value[0]);
                            });
                        } else if (xhr.status === 404) {
                            toastr.error('La ruta no existe. Verifica que la URL sea correcta');
                        } else if (xhr.status === 500) {
                            toastr.error('Error en el servidor. Revisa los logs de Laravel');
                        } else {
                            toastr.error('Hubo un error al actualizar los datos: ' + error);
                        }
                    }
                });
            });

            // ============================================
            // ACTUALIZAR FECHA DE NACIMIENTO
            // ============================================
            $(document).on('click', '#guardar-fecha-nacimiento', function() {
                let userId = {{ $usuario->idUsuario }};
                let csrfToken = $('meta[name="csrf-token"]').attr('content');

                let dia = $('#nacimiento_dia').val();
                let mes = $('#nacimiento_mes').val();
                let anio = $('#nacimiento_anio').val();

                if (!dia || !mes || !anio) {
                    toastr.error('Debe completar todos los campos de fecha');
                    return;
                }

                let data = {
                    nacimiento_dia: dia,
                    nacimiento_mes: mes,
                    nacimiento_anio: anio
                };

                $.ajax({
                    url: '/usuario/' + userId + '/fecha-nacimiento',
                    type: 'POST',
                    data: JSON.stringify(data),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            // Actualizar la edad mostrada
                            $('.edad-span').text(response.data.edad + ' años');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                toastr.error(value[0]);
                            });
                        } else {
                            toastr.error('Error al actualizar la fecha de nacimiento');
                        }
                    }
                });
            });

            // ============================================
            // ACTUALIZAR LUGAR DE NACIMIENTO
            // ============================================
            $(document).on('click', '#guardar-lugar-nacimiento', function() {
                let userId = {{ $usuario->idUsuario }};
                let csrfToken = $('meta[name="csrf-token"]').attr('content');

                let departamento = $('#nacimiento_departamento').val();
                let provincia = $('#nacimiento_provincia').val();
                let distrito = $('#nacimiento_distrito').val();

                if (!departamento || !provincia || !distrito) {
                    toastr.error('Debe seleccionar departamento, provincia y distrito');
                    return;
                }

                let data = {
                    nacimiento_departamento: departamento,
                    nacimiento_provincia: provincia,
                    nacimiento_distrito: distrito
                };

                $.ajax({
                    url: '/usuario/' + userId + '/lugar-nacimiento',
                    type: 'POST',
                    data: JSON.stringify(data),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                toastr.error(value[0]);
                            });
                        } else {
                            toastr.error('Error al actualizar el lugar de nacimiento');
                        }
                    }
                });
            });

            // ============================================
            // GUARDAR SEGURO Y PENSIÓN
            // ============================================
            $(document).on('click', '#guardar-seguro-pension', function() {
                let userId = {{ $usuario->idUsuario }};
                let csrfToken = $('meta[name="csrf-token"]').attr('content');

                let data = {
                    seguroSalud: $('input[name="seguroSalud"]:checked').val(),
                    sistemaPensiones: $('input[name="sistemaPensiones"]:checked').val(),
                    afpCompania: $('#afpCompania').val()
                };

                $.ajax({
                    url: '/usuario/' + userId + '/seguro-pension',
                    type: 'POST',
                    data: JSON.stringify(data),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Error al guardar seguro y pensión');
                    }
                });
            });

            // ============================================
            // GUARDAR INFORMACIÓN ACADÉMICA
            // ============================================
            $(document).on('click', '#guardar-estudios', function() {
                let userId = {{ $usuario->idUsuario }};
                let csrfToken = $('meta[name="csrf-token"]').attr('content');

                let data = {
                    // Secundaria
                    secundaria_termino: $('input[name="secundaria_termino"]:checked').val(),
                    secundaria_centro: $('input[name="secundaria_centro"]').val(),
                    secundaria_fin: $('input[name="secundaria_fin"]').val(),

                    // Técnico
                    tecnico_termino: $('input[name="tecnico_termino"]:checked').val(),
                    tecnico_centro: $('input[name="tecnico_centro"]').val(),
                    tecnico_especialidad: $('input[name="tecnico_especialidad"]').val(),
                    tecnico_inicio: $('input[name="tecnico_inicio"]').val(),
                    tecnico_fin: $('input[name="tecnico_fin"]').val(),

                    // Universitario
                    universitario_termino: $('input[name="universitario_termino"]:checked').val(),
                    universitario_centro: $('input[name="universitario_centro"]').val(),
                    universitario_especialidad: $('input[name="universitario_especialidad"]').val(),
                    universitario_grado: $('input[name="universitario_grado"]').val(),
                    universitario_inicio: $('input[name="universitario_inicio"]').val(),
                    universitario_fin: $('input[name="universitario_fin"]').val(),

                    // Postgrado
                    postgrado_termino: $('input[name="postgrado_termino"]:checked').val(),
                    postgrado_centro: $('input[name="postgrado_centro"]').val(),
                    postgrado_especialidad: $('input[name="postgrado_especialidad"]').val(),
                    postgrado_grado: $('input[name="postgrado_grado"]').val(),
                    postgrado_inicio: $('input[name="postgrado_inicio"]').val(),
                    postgrado_fin: $('input[name="postgrado_fin"]').val()
                };

                $.ajax({
                    url: '/usuario/' + userId + '/estudios',
                    type: 'POST',
                    data: JSON.stringify(data),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Error al guardar la información académica');
                    }
                });
            });

            // ============================================
            // ACTUALIZAR DIRECCIÓN
            // ============================================
            $(document).on('submit', '#direccion-form', function(event) {
                event.preventDefault();

                const formData = new FormData(this);
                const formDataObj = {};
                formData.forEach((value, key) => {
                    formDataObj[key] = value;
                });

                const userId = {{ $usuario->idUsuario }};
                const url = `/usuario/direccion/${userId}`;
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: url,
                    method: 'PUT',
                    data: JSON.stringify(formDataObj),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(data) {
                        if (data.success) {
                            toastr.success('Dirección actualizada correctamente');
                        } else {
                            toastr.error('Error al actualizar la dirección');
                        }
                    },
                    error: function() {
                        toastr.error('Error al intentar actualizar');
                    }
                });
            });

            function isValidEmail(email) {
                var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }

            // ============================================
            // PREVISUALIZAR IMAGEN
            // ============================================
            window.previewImage = function(event) {
                var reader = new FileReader();
                reader.onload = function() {
                    var output = document.getElementById('profile-img');
                    output.src = reader.result;
                };
                reader.readAsDataURL(event.target.files[0]);
            };

            // ============================================
            // HABILITAR/DESHABILITAR CAMPOS DE ESTUDIOS
            // ============================================
            function toggleEstudioFields(nivel) {
                $(document).on('change', `input[name="${nivel}_termino"]`, function() {
                    let esSi = $(this).val() == '1';
                    if (esSi) {
                        $(`input[name="${nivel}_centro"], input[name="${nivel}_especialidad"], 
                       input[name="${nivel}_grado"], input[name="${nivel}_inicio"], 
                       input[name="${nivel}_fin"]`).prop('disabled', false);
                    } else {
                        $(`input[name="${nivel}_centro"], input[name="${nivel}_especialidad"], 
                       input[name="${nivel}_grado"], input[name="${nivel}_inicio"], 
                       input[name="${nivel}_fin"]`).val('').prop('disabled', true);
                    }
                });
            }

            // Aplicar a todos los niveles
            toggleEstudioFields('secundaria');
            toggleEstudioFields('tecnico');
            toggleEstudioFields('universitario');
            toggleEstudioFields('postgrado');

            // ============================================
            // CALCULAR EDAD AUTOMÁTICAMENTE
            // ============================================
            function calcularEdad() {
                let dia = $('#nacimiento_dia').val();
                let mes = $('#nacimiento_mes').val();
                let anio = $('#nacimiento_anio').val();

                if (dia && mes && anio) {
                    let fechaNacimiento = new Date(anio, mes - 1, dia);
                    let hoy = new Date();
                    let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
                    let mesActual = hoy.getMonth();
                    let diaActual = hoy.getDate();

                    if (mesActual < (mes - 1) || (mesActual === (mes - 1) && diaActual < dia)) {
                        edad--;
                    }

                    if (edad >= 0 && edad < 150) {
                        $('.edad-span').text(edad + ' años');
                    } else {
                        $('.edad-span').text('-- años');
                    }
                } else {
                    $('.edad-span').text('-- años');
                }
            }

            $(document).on('change keyup', '#nacimiento_dia, #nacimiento_mes, #nacimiento_anio', calcularEdad);

            // ============================================
            // UBIGEO PARA DIRECCIÓN ACTUAL
            // ============================================
            function cargarProvincias(departamentoId) {
                if (!departamentoId) {
                    $('#provincia').empty().prop('disabled', true).append(
                        '<option value="" disabled selected>Seleccionar Provincia</option>');
                    $('#distrito').empty().prop('disabled', true).append(
                        '<option value="" disabled selected>Seleccionar Distrito</option>');
                    return;
                }

                $.ajax({
                    url: '/ubigeo/provincias/' + departamentoId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var provinciaSelect = $('#provincia');
                        provinciaSelect.empty().prop('disabled', false);
                        provinciaSelect.append(
                            '<option value="" disabled selected>Seleccionar Provincia</option>');

                        if (data && data.length > 0) {
                            $.each(data, function(index, provincia) {
                                provinciaSelect.append('<option value="' + provincia.id_ubigeo +
                                    '">' +
                                    provincia.nombre_ubigeo + '</option>');
                            });

                            var provinciaSeleccionada = '{{ old('provincia', $usuario->provincia) }}';
                            if (provinciaSeleccionada) {
                                provinciaSelect.val(provinciaSeleccionada).trigger('change');
                            }
                        }
                    },
                    error: function() {
                        toastr.error('Error al cargar provincias');
                    }
                });
            }

            function cargarDistritos(provinciaId) {
                if (!provinciaId) {
                    $('#distrito').empty().prop('disabled', true).append(
                        '<option value="" disabled selected>Seleccionar Distrito</option>');
                    return;
                }

                $.ajax({
                    url: '/ubigeo/distritos/' + provinciaId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var distritoSelect = $('#distrito');
                        distritoSelect.empty().prop('disabled', false);
                        distritoSelect.append(
                            '<option value="" disabled selected>Seleccionar Distrito</option>');

                        if (data && data.length > 0) {
                            $.each(data, function(index, distrito) {
                                distritoSelect.append('<option value="' + distrito.id_ubigeo +
                                    '">' +
                                    distrito.nombre_ubigeo + '</option>');
                            });

                            var distritoSeleccionado = '{{ old('distrito', $usuario->distrito) }}';
                            if (distritoSeleccionado) {
                                distritoSelect.val(distritoSeleccionado);
                            }
                        }
                    },
                    error: function() {
                        toastr.error('Error al cargar distritos');
                    }
                });
            }

            // Cargar datos iniciales si existen
            var departamentoInicial = $('#departamento').val();
            var provinciaInicial = '{{ $usuario->provincia }}';
            var distritoInicial = '{{ $usuario->distrito }}';

            if (departamentoInicial) {
                cargarProvincias(departamentoInicial);
            }

            $(document).on('change', '#departamento', function() {
                var departamentoId = $(this).val();
                cargarProvincias(departamentoId);
                $('#distrito').empty().prop('disabled', true).append(
                    '<option value="" disabled selected>Seleccionar Distrito</option>');
            });

            $(document).on('change', '#provincia', function() {
                var provinciaId = $(this).val();
                cargarDistritos(provinciaId);
            });

            if (provinciaInicial) {
                cargarDistritos(provinciaInicial);
            }

            // ============================================
            // UBIGEO PARA LUGAR DE NACIMIENTO
            // ============================================
            var provinciasNacimiento = @json($provinciasNacimiento ?? []);
            var distritosNacimiento = @json($distritosNacimiento ?? []);

            function cargarProvinciasNacimiento(departamentoId) {
                if (!departamentoId) {
                    $('#nacimiento_provincia').empty().prop('disabled', true).append(
                        '<option value="">Seleccionar Provincia</option>');
                    $('#nacimiento_distrito').empty().prop('disabled', true).append(
                        '<option value="">Seleccionar Distrito</option>');
                    return;
                }

                // Si ya tenemos los datos precargados del servidor
                if (provinciasNacimiento && provinciasNacimiento.length > 0) {
                    var provinciaSelect = $('#nacimiento_provincia');
                    provinciaSelect.empty().prop('disabled', false);
                    provinciaSelect.append('<option value="">Seleccionar Provincia</option>');

                    $.each(provinciasNacimiento, function(index, provincia) {
                        provinciaSelect.append('<option value="' + provincia.id_ubigeo + '">' +
                            provincia.nombre_ubigeo + '</option>');
                    });

                    var provinciaSeleccionada = '{{ $fichaGeneral->nacimientoProvincia ?? '' }}';
                    if (provinciaSeleccionada) {
                        provinciaSelect.val(provinciaSeleccionada).trigger('change');
                    }
                    return;
                }

                // Si no hay datos precargados, hacer petición AJAX
                $.ajax({
                    url: '/ubigeo/provincias/' + departamentoId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var provinciaSelect = $('#nacimiento_provincia');
                        provinciaSelect.empty().prop('disabled', false);
                        provinciaSelect.append('<option value="">Seleccionar Provincia</option>');

                        if (data && data.length > 0) {
                            $.each(data, function(index, provincia) {
                                provinciaSelect.append('<option value="' + provincia.id_ubigeo +
                                    '">' +
                                    provincia.nombre_ubigeo + '</option>');
                            });

                            var provinciaSeleccionada =
                                '{{ $fichaGeneral->nacimientoProvincia ?? '' }}';
                            if (provinciaSeleccionada) {
                                provinciaSelect.val(provinciaSeleccionada).trigger('change');
                            }
                        }
                    },
                    error: function() {
                        toastr.error('Error al cargar provincias');
                    }
                });
            }

            function cargarDistritosNacimiento(provinciaId) {
                if (!provinciaId) {
                    $('#nacimiento_distrito').empty().prop('disabled', true).append(
                        '<option value="">Seleccionar Distrito</option>');
                    return;
                }

                // Si ya tenemos los datos precargados del servidor
                if (distritosNacimiento && distritosNacimiento.length > 0) {
                    var distritoSelect = $('#nacimiento_distrito');
                    distritoSelect.empty().prop('disabled', false);
                    distritoSelect.append('<option value="">Seleccionar Distrito</option>');

                    $.each(distritosNacimiento, function(index, distrito) {
                        distritoSelect.append('<option value="' + distrito.id_ubigeo + '">' +
                            distrito.nombre_ubigeo + '</option>');
                    });

                    var distritoSeleccionado = '{{ $fichaGeneral->nacimientoDistrito ?? '' }}';
                    if (distritoSeleccionado) {
                        distritoSelect.val(distritoSeleccionado);
                    }
                    return;
                }

                // Si no hay datos precargados, hacer petición AJAX
                $.ajax({
                    url: '/ubigeo/distritos/' + provinciaId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var distritoSelect = $('#nacimiento_distrito');
                        distritoSelect.empty().prop('disabled', false);
                        distritoSelect.append('<option value="">Seleccionar Distrito</option>');

                        if (data && data.length > 0) {
                            $.each(data, function(index, distrito) {
                                distritoSelect.append('<option value="' + distrito.id_ubigeo +
                                    '">' +
                                    distrito.nombre_ubigeo + '</option>');
                            });

                            var distritoSeleccionado = '{{ $fichaGeneral->nacimientoDistrito ?? '' }}';
                            if (distritoSeleccionado) {
                                distritoSelect.val(distritoSeleccionado);
                            }
                        }
                    },
                    error: function() {
                        toastr.error('Error al cargar distritos');
                    }
                });
            }

            // Event listeners para lugar de nacimiento
            $(document).on('change', '#nacimiento_departamento', function() {
                var departamentoId = $(this).val();
                cargarProvinciasNacimiento(departamentoId);
                $('#nacimiento_distrito').empty().prop('disabled', true).append(
                    '<option value="">Seleccionar Distrito</option>');
            });

            $(document).on('change', '#nacimiento_provincia', function() {
                var provinciaId = $(this).val();
                cargarDistritosNacimiento(provinciaId);
            });

            // Cargar datos iniciales si existen
            var deptoNacimiento = '{{ $fichaGeneral->nacimientoDepartamento ?? '' }}';
            var provinciaNacimiento = '{{ $fichaGeneral->nacimientoProvincia ?? '' }}';

            if (deptoNacimiento) {
                $('#nacimiento_departamento').val(deptoNacimiento).trigger('change');

                if (provinciaNacimiento) {
                    setTimeout(function() {
                        $('#nacimiento_provincia').val(provinciaNacimiento).trigger('change');
                    }, 500);
                }
            }

            // ============================================
            // CONTROL DE AFP SEGÚN PENSIÓN
            // ============================================
            $(document).on('change', 'input[name="sistemaPensiones"]', function() {
                if ($(this).val() === 'AFP') {
                    $('#afpCompania').prop('disabled', false);
                } else {
                    $('#afpCompania').prop('disabled', true).val('');
                }
            });
        });
    </script>





    <script>
        // Función para inicializar flatpickr en los inputs de salud
        function initFlatpickrSalud() {
            if (typeof flatpickr !== 'undefined') {
                setTimeout(function() {
                    $('#covid_dosis1, #covid_dosis2, #covid_dosis3').each(function() {
                        // Solo inicializar si el input está visible y habilitado
                        if ($(this).is(':visible') && !$(this).prop('disabled')) {
                            // Destruir instancia anterior si existe
                            if (this._flatpickr) {
                                this._flatpickr.destroy();
                            }
                            // Crear nueva instancia
                            flatpickr(this, {
                                dateFormat: "Y-m-d",
                                allowInput: true,
                                locale: "es"
                            });
                        }
                    });
                }, 100);
            }
        }

        $(document).ready(function() {
            // ============================================
            // INICIALIZAR FLATPICKR PARA FECHAS COVID (PRIMERA VEZ)
            // ============================================
            initFlatpickrSalud();

            // ============================================
            // DETECTAR CAMBIO DE TAB Y REINICIALIZAR FLATPICKR
            // ============================================
            $(document).on('click', '[x-on\\:click="tab = \'info-salud\'"]', function() {
                initFlatpickrSalud();
            });

            // También detectar si usan otro método para cambiar tabs
            $(document).on('click', 'button, a', function() {
                if ($(this).text().includes('Salud') || $(this).text().includes('salud')) {
                    setTimeout(initFlatpickrSalud, 200);
                }
            });

            // ============================================
            // GUARDAR INFORMACIÓN DE SALUD
            // ============================================
            $(document).on('click', '#guardar-salud-btn', function() {
                let userId = {{ $usuario->idUsuario }};
                let csrfToken = $('meta[name="csrf-token"]').attr('content');

                let data = {
                    vacuna_covid: $('input[name="vacuna_covid"]:checked').val(),
                    covid_dosis1: $('#covid_dosis1').val(),
                    covid_dosis2: $('#covid_dosis2').val(),
                    covid_dosis3: $('#covid_dosis3').val(),
                    dolencia_cronica: $('input[name="dolencia_cronica"]:checked').val(),
                    dolencia_detalle: $('input[name="dolencia_detalle"]').val(),
                    discapacidad: $('input[name="discapacidad"]:checked').val(),
                    discapacidad_detalle: $('input[name="discapacidad_detalle"]').val(),
                    tipo_sangre: $('input[name="tipo_sangre"]:checked').val()
                };

                $.ajax({
                    url: '/usuario/' + userId + '/salud/guardar',
                    type: 'POST',
                    data: JSON.stringify(data),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                toastr.error(value[0]);
                            });
                        } else {
                            toastr.error('Error al guardar la información de salud');
                        }
                    }
                });
            });

            // ============================================
            // AGREGAR FAMILIAR
            // ============================================
            $(document).on('click', '#add-familiar-btn', function() {
                Swal.fire({
                    title: '<span class="text-xl font-semibold text-gray-800">Registrar Familiar</span>',
                    html: `
            <form id="familiar-form" class="text-left space-y-4 mt-2">

                <div class="grid grid-cols-2 gap-4">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Parentesco *</label>
                        <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none" name="parentesco" required>
                            <option value="">Seleccionar</option>
                            <option value="CONYUGE">Cónyuge</option>
                            <option value="CONCUBINO">Concubino/a</option>
                            <option value="HIJO">Hijo/a</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Sexo</label>
                        <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none" name="sexo">
                            <option value="">Seleccionar</option>
                            <option value="MASCULINO">Masculino</option>
                            <option value="FEMENINO">Femenino</option>
                        </select>
                    </div>

                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Apellidos y Nombres *</label>
                    <input type="text" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none" 
                        name="apellidosNombres" required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">N° Documento</label>
                        <input type="text" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none" 
                            name="nroDocumento">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Fecha de Nacimiento</label>
                        <input type="text" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 flatpickr focus:ring-2 focus:ring-emerald-500 focus:outline-none" 
                            name="fechaNacimiento">
                    </div>

                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Ocupación</label>
                    <input type="text" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none" 
                        name="ocupacion">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Domicilio Actual</label>
                    <input type="text" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none" 
                        name="domicilioActual">
                </div>

            </form>
        `,
                    showCancelButton: true,
                    confirmButtonText: 'Guardar Registro',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280',
                    width: '700px',
                    customClass: {
                        popup: 'rounded-xl'
                    },
                    didOpen: () => {
                        if (typeof flatpickr !== 'undefined') {
                            flatpickr(".flatpickr", {
                                dateFormat: "Y-m-d",
                                locale: "es"
                            });
                        }
                    },
                    preConfirm: () => {
                        let form = document.getElementById('familiar-form');
                        let formData = new FormData(form);
                        let data = Object.fromEntries(formData);

                        if (!data.parentesco) {
                            Swal.showValidationMessage('Debe seleccionar un parentesco');
                            return false;
                        }
                        if (!data.apellidosNombres) {
                            Swal.showValidationMessage('Debe ingresar apellidos y nombres');
                            return false;
                        }

                        return data;
                    }
                })
            });


            // ============================================
            // ELIMINAR FAMILIAR
            // ============================================
            $(document).on('click', '.delete-familiar', function() {
                let id = $(this).data('id');
                let fila = $(this).closest('tr');

                Swal.fire({
                    title: '¿Eliminar familiar?',
                    text: 'Esta acción no se puede revertir',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/usuario/familiar/' + id,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    toastr.success(response.message);
                                    fila.fadeOut(300, function() {
                                        $(this).remove();
                                        // Si no quedan filas, mostrar mensaje de vacío
                                        if ($('#familiares-tbody tr').length ===
                                            0) {
                                            $('#familiares-tbody').html(`
                                            <tr class="empty-row">
                                                <td colspan="8" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                                    <i class="fas fa-users text-pink-500 text-2xl mb-2"></i>
                                                    <p>No hay familiares agregados</p>
                                                </td>
                                            </tr>
                                        `);
                                        }
                                    });
                                }
                            },
                            error: function() {
                                toastr.error('Error al eliminar familiar');
                            }
                        });
                    }
                });
            });

            // ============================================
            // EDITAR FAMILIAR (DISEÑO FORMAL)
            // ============================================
            $(document).on('click', '.edit-familiar', function() {

                let id = $(this).data('id');
                let fila = $(this).closest('tr');

                $.ajax({
                    url: '/usuario/familiar/' + id,
                    type: 'GET',
                    success: function(response) {

                        if (!response.success) return;

                        let familiar = response.familiar;

                        Swal.fire({
                            title: '<span class="text-xl font-semibold text-gray-800">Actualizar Familiar</span>',
                            html: `
                <form id="familiar-edit-form" class="text-left space-y-4 mt-2">
                    
                    <input type="hidden" name="idFamiliar" value="${familiar.idFamiliar}">

                    <div class="grid grid-cols-2 gap-4">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Parentesco *</label>
                            <select name="parentesco"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                required>
                                <option value="">Seleccionar</option>
                                <option value="CONYUGE" ${familiar.parentesco === 'CONYUGE' ? 'selected' : ''}>Cónyuge</option>
                                <option value="CONCUBINO" ${familiar.parentesco === 'CONCUBINO' ? 'selected' : ''}>Concubino/a</option>
                                <option value="HIJO" ${familiar.parentesco === 'HIJO' ? 'selected' : ''}>Hijo/a</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Sexo</label>
                            <select name="sexo"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                <option value="">Seleccionar</option>
                                <option value="MASCULINO" ${familiar.sexo === 'MASCULINO' ? 'selected' : ''}>Masculino</option>
                                <option value="FEMENINO" ${familiar.sexo === 'FEMENINO' ? 'selected' : ''}>Femenino</option>
                            </select>
                        </div>

                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Apellidos y Nombres *</label>
                        <input type="text" name="apellidosNombres"
                            value="${familiar.apellidosNombres || ''}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">N° Documento</label>
                            <input type="text" name="nroDocumento"
                                value="${familiar.nroDocumento || ''}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Fecha de Nacimiento</label>
                            <input type="text" name="fechaNacimiento"
                                value="${familiar.fechaNacimiento || ''}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 flatpickr focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>

                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Ocupación</label>
                        <input type="text" name="ocupacion"
                            value="${familiar.ocupacion || ''}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Domicilio Actual</label>
                        <input type="text" name="domicilioActual"
                            value="${familiar.domicilioActual || ''}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                </form>
                `,
                            showCancelButton: true,
                            confirmButtonText: 'Actualizar Registro',
                            cancelButtonText: 'Cancelar',
                            confirmButtonColor: '#3b82f6',
                            cancelButtonColor: '#6b7280',
                            width: '700px',
                            customClass: {
                                popup: 'rounded-xl'
                            },
                            didOpen: () => {
                                if (typeof flatpickr !== 'undefined') {
                                    flatpickr(".flatpickr", {
                                        dateFormat: "Y-m-d",
                                        locale: "es"
                                    });
                                }
                            },
                            preConfirm: () => {
                                let form = document.getElementById(
                                    'familiar-edit-form');
                                let formData = new FormData(form);
                                return Object.fromEntries(formData);
                            }
                        }).then((result) => {

                            if (!result.isConfirmed) return;

                            $.ajax({
                                url: '/usuario/familiar/' + id,
                                type: 'PUT',
                                data: JSON.stringify(result.value),
                                contentType: 'application/json',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                        .attr('content')
                                },
                                success: function(response) {

                                    if (!response.success) return;

                                    toastr.success(response.message);

                                    let f = response.familiar;

                                    fila.find('td:eq(0)').text(f
                                        .parentesco);
                                    fila.find('td:eq(1)').text(f
                                        .apellidosNombres);
                                    fila.find('td:eq(2)').text(f
                                        .nroDocumento || 'N/A');
                                    fila.find('td:eq(3)').text(f
                                        .ocupacion || 'N/A');
                                    fila.find('td:eq(4)').text(f.sexo ||
                                        'N/A');
                                    fila.find('td:eq(5)').text(
                                        f.fechaNacimiento ?
                                        new Date(f.fechaNacimiento)
                                        .toLocaleDateString('es-ES') :
                                        'N/A'
                                    );
                                    fila.find('td:eq(6)').text(f
                                        .domicilioActual || 'N/A');
                                },
                                error: function(xhr) {

                                    if (xhr.status === 422) {
                                        $.each(xhr.responseJSON.errors,
                                            function(_, value) {
                                                toastr.error(value[0]);
                                            });
                                    } else {
                                        toastr.error(
                                            'Error al actualizar familiar'
                                        );
                                    }
                                }
                            });
                        });
                    },
                    error: function() {
                        toastr.error('Error al cargar datos del familiar');
                    }
                });
            });


            // ============================================
            // AGREGAR CONTACTO DE EMERGENCIA (DISEÑO FORMAL)
            // ============================================
            $(document).on('click', '#add-contacto-btn', function() {

                Swal.fire({
                    title: '<span class="text-xl font-semibold text-gray-800">Registrar Contacto de Emergencia</span>',
                    html: `
        <form id="contacto-form" class="text-left space-y-4 mt-3">

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Nombre Completo *
                </label>
                <input type="text" name="apellidosNombres"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                    required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Parentesco *
                </label>
                <input type="text" name="parentesco"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                    placeholder="Ej: Padre, Madre"
                    required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Teléfono / Dirección *
                </label>
                <input type="text" name="direccionTelefono"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                    placeholder="Ej: 987654321 - Calle 123"
                    required>
            </div>

        </form>
        `,
                    showCancelButton: true,
                    confirmButtonText: 'Guardar Registro',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280',
                    width: '600px',
                    customClass: {
                        popup: 'rounded-xl'
                    },
                    preConfirm: () => {

                        let form = document.getElementById('contacto-form');
                        let formData = new FormData(form);
                        let data = Object.fromEntries(formData);

                        if (!data.apellidosNombres) {
                            Swal.showValidationMessage('Debe ingresar el nombre completo');
                            return false;
                        }
                        if (!data.parentesco) {
                            Swal.showValidationMessage('Debe ingresar el parentesco');
                            return false;
                        }
                        if (!data.direccionTelefono) {
                            Swal.showValidationMessage('Debe ingresar teléfono o dirección');
                            return false;
                        }

                        return data;
                    }

                }).then((result) => {

                    if (!result.isConfirmed) return;

                    let data = result.value;
                    data.idUsuario = {{ $usuario->idUsuario }};

                    $.ajax({
                        url: '/usuario/contacto-emergencia/guardar',
                        type: 'POST',
                        data: JSON.stringify(data),
                        contentType: 'application/json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {

                            if (!response.success) return;

                            toastr.success(response.message);

                            let contacto = response.contacto;

                            let nuevoContacto = `
                <div class="relative bg-white dark:bg-[#0e1726] border border-gray-200 dark:border-gray-700 rounded-xl p-5 shadow-sm hover:shadow-md transition">

                    <button 
                        class="absolute top-3 right-3 text-gray-400 hover:text-red-600 delete-contacto transition"
                        data-id="${contacto.idContacto}" 
                        title="Eliminar">
                        <i class="fas fa-times"></i>
                    </button>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">
                                Nombre Completo
                            </label>
                            <input type="text"
                                class="form-input w-full text-sm rounded-lg contacto-nombre"
                                value="${contacto.apellidosNombres}"
                                data-id="${contacto.idContacto}">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">
                                Parentesco
                            </label>
                            <input type="text"
                                class="form-input w-full text-sm rounded-lg contacto-parentesco"
                                value="${contacto.parentesco}"
                                data-id="${contacto.idContacto}">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">
                                Teléfono / Dirección
                            </label>
                            <input type="text"
                                class="form-input w-full text-sm rounded-lg contacto-direccion"
                                value="${contacto.direccionTelefono}"
                                data-id="${contacto.idContacto}">
                        </div>

                    </div>
                </div>
                `;

                            if ($('#contactos-container .empty-contactos').length) {
                                $('#contactos-container').empty().append(nuevoContacto);
                            } else {
                                $('#contactos-container').append(nuevoContacto);
                            }
                        },
                        error: function(xhr) {

                            if (xhr.status === 422) {
                                $.each(xhr.responseJSON.errors, function(_, value) {
                                    toastr.error(value[0]);
                                });
                            } else {
                                toastr.error('Error al agregar contacto');
                            }
                        }
                    });
                });
            });


            // ============================================
            // ELIMINAR CONTACTO DE EMERGENCIA
            // ============================================
            $(document).on('click', '.delete-contacto', function() {
                let id = $(this).data('id');
                let contactoDiv = $(this).closest('.grid');

                Swal.fire({
                    title: '¿Eliminar contacto?',
                    text: 'Esta acción no se puede revertir',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/usuario/contacto-emergencia/' + id,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    toastr.success(response.message);
                                    contactoDiv.fadeOut(300, function() {
                                        $(this).remove();
                                        if ($('#contactos-container .grid')
                                            .length === 0) {
                                            $('#contactos-container').html(`
                                            <div class="text-center py-6 text-gray-500 dark:text-gray-400 empty-contactos">
                                                <i class="fas fa-phone-alt text-amber-500 text-2xl mb-2"></i>
                                                <p>No hay contactos de emergencia agregados</p>
                                            </div>
                                        `);
                                        }
                                    });
                                }
                            },
                            error: function() {
                                toastr.error('Error al eliminar contacto');
                            }
                        });
                    }
                });
            });

            // ============================================
            // ACTUALIZAR CONTACTO DE EMERGENCIA (al perder foco)
            // ============================================
            $(document).on('blur', '.contacto-nombre, .contacto-parentesco, .contacto-direccion', function() {
                let id = $(this).data('id');
                let container = $(this).closest('.grid');
                let nombre = container.find('.contacto-nombre').val();
                let parentesco = container.find('.contacto-parentesco').val();
                let direccion = container.find('.contacto-direccion').val();

                let data = {
                    apellidosNombres: nombre,
                    parentesco: parentesco,
                    direccionTelefono: direccion
                };

                $.ajax({
                    url: '/usuario/contacto-emergencia/' + id,
                    type: 'PUT',
                    data: JSON.stringify(data),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Contacto actualizado');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                toastr.error(value[0]);
                            });
                        } else {
                            toastr.error('Error al actualizar contacto');
                        }
                    }
                });
            });

            // ============================================
            // HABILITAR/DESHABILITAR CAMPOS DE SALUD SEGÚN RADIOS
            // ============================================
            $(document).on('change', 'input[name="vacuna_covid"]', function() {
                if ($(this).val() == '1') {
                    // Habilitar inputs
                    $('#covid_dosis1, #covid_dosis2, #covid_dosis3').prop('disabled', false);
                    // Reinicializar flatpickr
                    setTimeout(function() {
                        $('#covid_dosis1, #covid_dosis2, #covid_dosis3').each(function() {
                            if (this._flatpickr) {
                                this._flatpickr.destroy();
                            }
                            flatpickr(this, {
                                dateFormat: "Y-m-d",
                                allowInput: true,
                                locale: "es"
                            });
                        });
                    }, 50);
                } else {
                    // BORRAR VALORES y deshabilitar cuando selecciona NO
                    $('#covid_dosis1, #covid_dosis2, #covid_dosis3').val('').prop('disabled', true);

                    // Destruir instancias de flatpickr
                    $('#covid_dosis1, #covid_dosis2, #covid_dosis3').each(function() {
                        if (this._flatpickr) {
                            this._flatpickr.destroy();
                        }
                    });
                }
            });

            $(document).on('change', 'input[name="dolencia_cronica"]', function() {
                if ($(this).val() == '1') {
                    $('input[name="dolencia_detalle"]').prop('disabled', false);
                } else {
                    // BORRAR VALOR y deshabilitar cuando selecciona NO
                    $('input[name="dolencia_detalle"]').val('').prop('disabled', true);
                }
            });

            $(document).on('change', 'input[name="discapacidad"]', function() {
                if ($(this).val() == '1') {
                    $('input[name="discapacidad_detalle"]').prop('disabled', false);
                } else {
                    // BORRAR VALOR y deshabilitar cuando selecciona NO
                    $('input[name="discapacidad_detalle"]').val('').prop('disabled', true);
                }
            });

            // Estado inicial
            setTimeout(function() {
                if ($('input[name="vacuna_covid"]:checked').val() == '0') {
                    $('#covid_dosis1, #covid_dosis2, #covid_dosis3').val('').prop('disabled', true);
                } else {
                    // Si está en Sí, aseguramos flatpickr
                    $('#covid_dosis1, #covid_dosis2, #covid_dosis3').each(function() {
                        if (!$(this).prop('disabled') && !this._flatpickr) {
                            flatpickr(this, {
                                dateFormat: "Y-m-d",
                                allowInput: true,
                                locale: "es"
                            });
                        }
                    });
                }

                if ($('input[name="dolencia_cronica"]:checked').val() == '0') {
                    $('input[name="dolencia_detalle"]').val('').prop('disabled', true);
                }

                if ($('input[name="discapacidad"]:checked').val() == '0') {
                    $('input[name="discapacidad_detalle"]').val('').prop('disabled', true);
                }
            }, 200);
        });
    </script>

    <script>
        $(document).ready(function() {
            // ============================================
            // INICIALIZAR FLATPICKR
            // ============================================
            window.initFlatpickr = function() {
                if (typeof flatpickr !== 'undefined') {
                    // Fechas
                    flatpickr(".flatpickr", {
                        locale: 'es',
                        dateFormat: "Y-m-d",
                        allowInput: true,
                        altInput: true,
                        altFormat: "d/m/Y",
                        altInputClass: 'form-input w-full bg-gray-50 dark:bg-[#1a1f2e] border-gray-200 dark:border-gray-700 focus:border-blue-500'
                    });

                    // Horas - Configuración con el MISMO estilo que las fechas
                    flatpickr(".flatpickr-time", {
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: "H:i",
                        time_24hr: true,
                        locale: 'es',
                        allowInput: true,
                        // Usar altInput para que tenga el mismo comportamiento que las fechas
                        altInput: true,
                        altFormat: "H:i",
                        altInputClass: 'form-input w-full bg-gray-50 dark:bg-[#1a1f2e] border-gray-200 dark:border-gray-700 focus:border-green-500',
                        // Asegurar que se muestre la hora correcta
                        onReady: function(selectedDates, dateStr, instance) {
                            // El valor ya viene formateado desde PHP, no necesitamos transformar
                            console.log('Hora lista:', instance.input.value);
                        }
                    });
                }
            };

            // Inicializar flatpickr
            window.initFlatpickr();

            // ============================================
            // ACTUALIZAR INFORMACIÓN LABORAL Y CONFIGURACIÓN
            // ============================================
            $(document).on('click', '#update-informacion-btn', function() {
                let userId = {{ $usuario->idUsuario }};
                let csrfToken = $('meta[name="csrf-token"]').attr('content');

                // IMPORTANTE: Obtener el valor del altInput si existe
                let horaInicio = $('#horaInicioJornada').val();
                let horaFin = $('#horaFinJornada').val();

                // Recolectar datos
                let data = {
                    idTipoContrato: $('#idTipoContrato').val(),
                    fechaInicio: $('#fechaInicio').val(),
                    fechaTermino: $('#fechaTermino').val(),
                    horaInicioJornada: horaInicio,
                    horaFinJornada: horaFin,
                    idSucursal: $('#idSucursal').val(),
                    idTipoArea: $('#idTipoArea').val(),
                    idTipoUsuario: $('#idTipoUsuario').val(),
                    idRol: $('#idRol').val(),
                    idSexo: $('#idSexo').val(),
                    sueldoMensual: $('#sueldoMensual').val()
                };

                console.log('Datos a enviar:', data); // Para verificar

                // Mostrar indicador de carga
                let $btn = $(this);
                let originalText = $btn.html();
                $btn.html('<i class="fas fa-spinner fa-spin"></i> Actualizando...').prop('disabled', true);

                $.ajax({
                    url: '/usuario/' + userId + '/informacion',
                    type: 'POST',
                    data: JSON.stringify(data),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                toastr.error(value[0]);
                            });
                        } else {
                            toastr.error('Error al actualizar la información');
                        }
                    },
                    complete: function() {
                        $btn.html(originalText).prop('disabled', false);
                    }
                });
            });
        });
    </script>



<!-- ============================================ -->
<!-- JAVASCRIPT COMPLETO PARA DANGER ZONE -->
<!-- ============================================ -->
<script>
// Variables globales
const usuarioId = {{ $usuario->idUsuario }};
const csrfToken = '{{ csrf_token() }}';
const correoCorporativo = '{{ $usuario->correo ?? "" }}';
const correoPersonal = '{{ $usuario->correo_personal ?? "" }}';
const nombreUsuario = '{{ $usuario->Nombre ?? "" }} {{ $usuario->apellidoPaterno ?? "" }}';
const adminNombre = '{{ auth()->user()->name ?? auth()->user()->usuario ?? "Sistema" }}';

// ============================================ -->
// FUNCIÓN PARA INICIALIZAR CUANDO LA TAB ESTÉ ACTIVA
// ============================================ -->
function inicializarDangerZone() {
    console.log('🚀 Inicializando DANGER ZONE (tab activa)');
    
    // Verificar que los elementos existen antes de inicializar
    const estadoWeb = document.getElementById('estadoWeb');
    const estadoApp = document.getElementById('estadoApp');
    
    if (!estadoWeb || !estadoApp) {
        console.log('⏳ Elementos no encontrados, reintentando en 100ms...');
        setTimeout(inicializarDangerZone, 100);
        return;
    }
    
    console.log('✅ Elementos encontrados, inicializando...');
    initControlAcceso();
    initConfiguracionCorreo();
    initEnvioCredenciales();
    initGestionContrasenas();
    
    // Verificar elementos del generador
    verificarGenerador();
}

// ============================================ -->
// SECCIÓN 1: CONTROL DE ACCESO POR PLATAFORMA
// ============================================ -->
function initControlAcceso() {
    console.log('🎮 Inicializando Control de Acceso...');
    
    const estadoWeb = document.getElementById('estadoWeb');
    const estadoApp = document.getElementById('estadoApp');
    const estadoWebText = document.getElementById('estadoWebText');
    const estadoAppText = document.getElementById('estadoAppText');
    
    console.log('📊 Elementos Control Acceso:', {
        estadoWeb: estadoWeb ? '✅' : '❌',
        estadoApp: estadoApp ? '✅' : '❌',
        estadoWebText: estadoWebText ? '✅' : '❌',
        estadoAppText: estadoAppText ? '✅' : '❌'
    });
    
    if (estadoWeb) {
        // Remover event listeners anteriores
        const newWeb = estadoWeb.cloneNode(true);
        estadoWeb.parentNode.replaceChild(newWeb, estadoWeb);
        
        newWeb.addEventListener('change', function() {
            const estado = this.checked ? 1 : 0;
            console.log('🌐 Web toggled:', { checked: this.checked, estado });
            
            // Actualizar texto
            if (estadoWebText) {
                estadoWebText.textContent = this.checked ? 'Activo' : 'Inactivo';
                estadoWebText.className = this.checked 
                    ? 'text-green-600 dark:text-green-400 font-semibold' 
                    : 'text-red-600 dark:text-red-400 font-semibold';
            }
            
            // Enviar al backend
            actualizarEstado('web', estado);
        });
        console.log('✅ Event listener para Web configurado');
    }
    
    if (estadoApp) {
        // Remover event listeners anteriores
        const newApp = estadoApp.cloneNode(true);
        estadoApp.parentNode.replaceChild(newApp, estadoApp);
        
        newApp.addEventListener('change', function() {
            const estado = this.checked ? 1 : 0;
            console.log('📱 App toggled:', { checked: this.checked, estado });
            
            // Actualizar texto
            if (estadoAppText) {
                estadoAppText.textContent = this.checked ? 'Activo' : 'Inactivo';
                estadoAppText.className = this.checked 
                    ? 'text-green-600 dark:text-green-400 font-semibold' 
                    : 'text-red-600 dark:text-red-400 font-semibold';
            }
            
            // Enviar al backend
            actualizarEstado('app', estado);
        });
        console.log('✅ Event listener para App configurado');
    }
}

// Función para actualizar estado en el backend
async function actualizarEstado(plataforma, estado) {
    console.log(`📡 Enviando actualización de estado ${plataforma}:`, estado);
    
    try {
        const response = await fetch(`/usuario/${usuarioId}/actualizar-estado`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                plataforma: plataforma,
                estado: estado
            })
        });
        
        const data = await response.json();
        console.log('📥 Respuesta del servidor:', data);
        
        if (data.success) {
            mostrarMensaje('✅ Estado actualizado correctamente', 'exito');
        } else {
            mostrarMensaje('❌ ' + (data.message || 'Error al actualizar'), 'error');
        }
    } catch (error) {
        console.error('❌ Error en fetch:', error);
        mostrarMensaje('❌ Error de conexión al actualizar', 'error');
    }
}

// ============================================ -->
// SECCIÓN 2: CONFIGURACIÓN DE CORREO
// ============================================ -->
function initConfiguracionCorreo() {
    console.log('📧 Inicializando Configuración de Correo...');
    
    const btnGuardarCorreo = document.getElementById('btnGuardarCorreoConfig');
    const correoCorporativoRadio = document.getElementById('correoCorporativo');
    const correoPersonalRadio = document.getElementById('correoPersonal');
    const correoConfiguradoMostrar = document.getElementById('correoConfiguradoMostrar');
    const usuarioWebSpan = document.getElementById('usuarioWeb');
    
    console.log('📊 Elementos Configuración Correo:', {
        btnGuardarCorreo: btnGuardarCorreo ? '✅' : '❌',
        correoCorporativoRadio: correoCorporativoRadio ? '✅' : '❌',
        correoPersonalRadio: correoPersonalRadio ? '✅' : '❌',
        correoConfiguradoMostrar: correoConfiguradoMostrar ? '✅' : '❌'
    });
    
    if (btnGuardarCorreo) {
        // Remover event listeners anteriores
        const newBtn = btnGuardarCorreo.cloneNode(true);
        btnGuardarCorreo.parentNode.replaceChild(newBtn, btnGuardarCorreo);
        
        newBtn.addEventListener('click', function() {
            console.log('💾 Guardando configuración de correo...');
            
            let tipoCorreo = 'corporativo';
            if (correoPersonalRadio && correoPersonalRadio.checked) {
                tipoCorreo = 'personal';
            }
            
            console.log('Tipo seleccionado:', tipoCorreo);
            
            // Actualizar UI inmediatamente
            const correoMostrar = tipoCorreo === 'corporativo' ? correoCorporativo : correoPersonal;
            if (correoConfiguradoMostrar) {
                correoConfiguradoMostrar.textContent = correoMostrar || 'No configurado';
            }
            if (usuarioWebSpan) {
                usuarioWebSpan.textContent = correoMostrar || 'No configurado';
            }
            
            // Enviar al backend
            actualizarCorreoConfigurado(tipoCorreo);
        });
        console.log('✅ Event listener para guardar correo configurado');
    }
}

async function actualizarCorreoConfigurado(tipo) {
    console.log('📡 Enviando configuración de correo:', tipo);
    
    try {
        const response = await fetch(`/usuario/${usuarioId}/actualizar-correo-configurado`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                tipo_correo: tipo
            })
        });
        
        const data = await response.json();
        console.log('📥 Respuesta del servidor:', data);
        
        if (data.success) {
            mostrarMensaje('✅ Configuración de correo guardada', 'exito');
        } else {
            mostrarMensaje('❌ ' + (data.message || 'Error al guardar'), 'error');
        }
    } catch (error) {
        console.error('❌ Error en fetch:', error);
        mostrarMensaje('❌ Error de conexión al guardar', 'error');
    }
}

// ============================================ -->
// SECCIÓN 3: ENVÍO DE CREDENCIALES
// ============================================ -->
function initEnvioCredenciales() {
    console.log('📨 Inicializando Envío de Credenciales...');
    
    const btnEnviar = document.getElementById('btnEnviarCredenciales');
    const enviarWeb = document.getElementById('enviarWeb');
    const enviarApp = document.getElementById('enviarApp');
    const credencialesWeb = document.getElementById('credencialesWeb');
    const credencialesApp = document.getElementById('credencialesApp');
    const destinatarioRadios = document.querySelectorAll('input[name="destinatario"]');
    
    console.log('📊 Elementos Envío Credenciales:', {
        btnEnviar: btnEnviar ? '✅' : '❌',
        enviarWeb: enviarWeb ? '✅' : '❌',
        enviarApp: enviarApp ? '✅' : '❌',
        credencialesWeb: credencialesWeb ? '✅' : '❌',
        credencialesApp: credencialesApp ? '✅' : '❌'
    });
    
    // Mostrar/ocultar credenciales según checkboxes
    if (enviarWeb) {
        enviarWeb.addEventListener('change', function() {
            if (credencialesWeb) {
                credencialesWeb.style.display = this.checked ? 'block' : 'none';
            }
            actualizarNotificacion();
        });
    }
    
    if (enviarApp) {
        enviarApp.addEventListener('change', function() {
            if (credencialesApp) {
                credencialesApp.style.display = this.checked ? 'block' : 'none';
            }
            actualizarNotificacion();
        });
    }
    
    // Botón enviar
    if (btnEnviar) {
        // Remover event listeners anteriores
        const newBtn = btnEnviar.cloneNode(true);
        btnEnviar.parentNode.replaceChild(newBtn, btnEnviar);
        
        newBtn.addEventListener('click', function() {
            console.log('🚀 Enviando credenciales...');
            
            const webChecked = enviarWeb ? enviarWeb.checked : false;
            const appChecked = enviarApp ? enviarApp.checked : false;
            
            if (!webChecked && !appChecked) {
                mostrarMensaje('❌ Selecciona al menos un tipo de credencial', 'error');
                return;
            }
            
            let destinatario = 'corporativo';
            destinatarioRadios.forEach(radio => {
                if (radio.checked) destinatario = radio.value;
            });
            
            // Verificar que el destinatario tenga correo
            if (destinatario === 'corporativo' && !correoCorporativo) {
                mostrarMensaje('❌ El correo corporativo no está configurado', 'error');
                return;
            }
            if (destinatario === 'personal' && !correoPersonal) {
                mostrarMensaje('❌ El correo personal no está configurado', 'error');
                return;
            }
            
            // Deshabilitar botón
            newBtn.disabled = true;
            newBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enviando...';
            
            enviarCredenciales(webChecked, appChecked, destinatario, newBtn);
        });
        console.log('✅ Event listener para enviar credenciales configurado');
    }
    
    // Inicializar visibilidad
    if (enviarWeb && credencialesWeb) {
        credencialesWeb.style.display = enviarWeb.checked ? 'block' : 'none';
    }
    if (enviarApp && credencialesApp) {
        credencialesApp.style.display = enviarApp.checked ? 'block' : 'none';
    }
}

function actualizarNotificacion() {
    const notificacion = document.getElementById('notificacionGerencia');
    if (!notificacion) return;
    
    const enviarWeb = document.getElementById('enviarWeb');
    const enviarApp = document.getElementById('enviarApp');
    
    const fecha = new Date().toLocaleDateString('es-ES', {
        day: '2-digit', month: '2-digit', year: 'numeric',
        hour: '2-digit', minute: '2-digit'
    });
    
    let accesos = [];
    if (enviarWeb?.checked) accesos.push('Web');
    if (enviarApp?.checked) accesos.push('App');
    
    notificacion.textContent = `Se notificará a gerencia: Usuario: ${nombreUsuario} | Acceso: ${accesos.join(' + ')} | Fecha: ${fecha} | Admin: ${adminNombre}`;
}

async function enviarCredenciales(web, app, destinatario, btn) {
    console.log('📡 Enviando credenciales:', { web, app, destinatario });
    
    try {
        const response = await fetch(`/usuario/${usuarioId}/enviar-credenciales`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                enviarWeb: web,
                enviarApp: app,
                destinatario: destinatario
            })
        });
        
        const data = await response.json();
        console.log('📥 Respuesta del servidor:', data);
        
        if (data.success) {
            mostrarMensaje('✅ Credenciales enviadas correctamente', 'exito');
        } else {
            mostrarMensaje('❌ ' + (data.message || 'Error al enviar'), 'error');
        }
    } catch (error) {
        console.error('❌ Error en fetch:', error);
        mostrarMensaje('❌ Error de conexión al enviar', 'error');
    } finally {
        // Restaurar botón
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Enviar Credenciales';
        }
    }
}

// ============================================ -->
// SECCIÓN 4: GESTIÓN DE CONTRASEÑAS
// ============================================ -->
function initGestionContrasenas() {
    console.log('🔐 Inicializando Gestión de Contraseñas...');
    
    const btnGuardar = document.getElementById('btnGuardarContrasenas');
    const passwordWeb = document.getElementById('passwordWeb');
    const passwordApp = document.getElementById('passwordApp');
    
    console.log('📊 Elementos Gestión Contraseñas:', {
        btnGuardar: btnGuardar ? '✅' : '❌',
        passwordWeb: passwordWeb ? '✅' : '❌',
        passwordApp: passwordApp ? '✅' : '❌'
    });
    
    if (btnGuardar) {
        // Remover event listeners anteriores
        const newBtn = btnGuardar.cloneNode(true);
        btnGuardar.parentNode.replaceChild(newBtn, btnGuardar);
        
        newBtn.addEventListener('click', function() {
            console.log('💾 Guardando contraseñas...');
            
            const passwordWebVal = document.getElementById('passwordWeb')?.value || '';
            const passwordAppVal = document.getElementById('passwordApp')?.value || '';
            
            // Validar longitud mínima
            if (passwordWebVal && passwordWebVal.length < 8) {
                mostrarMensaje('❌ La contraseña web debe tener al menos 8 caracteres', 'error');
                return;
            }
            if (passwordAppVal && passwordAppVal.length < 8) {
                mostrarMensaje('❌ La contraseña app debe tener al menos 8 caracteres', 'error');
                return;
            }
            
            guardarContrasenas(passwordWebVal, passwordAppVal);
        });
        console.log('✅ Event listener para guardar contraseñas configurado');
    }
}

async function guardarContrasenas(passwordWeb, passwordApp) {
    console.log('📡 Enviando contraseñas al servidor...');
    
    try {
        const response = await fetch(`/usuario/${usuarioId}/guardar-contrasenas`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                passwordWeb: passwordWeb,
                passwordApp: passwordApp
            })
        });
        
        const data = await response.json();
        console.log('📥 Respuesta del servidor:', data);
        
        if (data.success) {
            mostrarMensaje('✅ Contraseñas guardadas correctamente', 'exito');
        } else {
            mostrarMensaje('❌ ' + (data.message || 'Error al guardar'), 'error');
        }
    } catch (error) {
        console.error('❌ Error en fetch:', error);
        mostrarMensaje('❌ Error de conexión al guardar', 'error');
    }
}

// ============================================ -->
// FUNCIONES DEL GENERADOR
// ============================================ -->
function verificarGenerador() {
    const elementosGen = {
        configLongitud: document.getElementById('configLongitud'),
        configMayusculas: document.getElementById('configMayusculas'),
        configNumeros: document.getElementById('configNumeros'),
        configEspeciales: document.getElementById('configEspeciales'),
        passwordGenerada: document.getElementById('passwordGenerada')
    };
    
    console.log('🔍 Verificación generador:', {
        configLongitud: elementosGen.configLongitud ? '✅' : '❌',
        configMayusculas: elementosGen.configMayusculas ? '✅' : '❌',
        configNumeros: elementosGen.configNumeros ? '✅' : '❌',
        configEspeciales: elementosGen.configEspeciales ? '✅' : '❌',
        passwordGenerada: elementosGen.passwordGenerada ? '✅' : '❌'
    });
    
    // Generar contraseña por defecto
    if (elementosGen.passwordGenerada && !elementosGen.passwordGenerada.value) {
        setTimeout(generarPassword, 500);
    }
}

// Función para mostrar/ocultar contraseña
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    
    if (!input || !icon) return;
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Función para generar contraseña segura
function generarPassword() {
    console.log('🎯 Generando contraseña...');
    
    const longitud = parseInt(document.getElementById('configLongitud')?.value) || 12;
    const mayusculas = document.getElementById('configMayusculas')?.checked || false;
    const numeros = document.getElementById('configNumeros')?.checked || false;
    const especiales = document.getElementById('configEspeciales')?.checked || false;
    
    const minusculas = 'abcdefghijklmnopqrstuvwxyz';
    const mayusculasChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const numerosChars = '0123456789';
    const especialesChars = '!@#$%^&*()_+-=[]{}|;:,.<>?';
    
    let caracteres = minusculas;
    if (mayusculas) caracteres += mayusculasChars;
    if (numeros) caracteres += numerosChars;
    if (especiales) caracteres += especialesChars;
    
    let password = '';
    for (let i = 0; i < longitud; i++) {
        password += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
    }
    
    const passwordGenerada = document.getElementById('passwordGenerada');
    if (passwordGenerada) passwordGenerada.value = password;
}

// Función para copiar contraseña al portapapeles
function copiarPassword() {
    console.log('📋 Copiando contraseña...');
    
    const passwordGenerada = document.getElementById('passwordGenerada');
    if (!passwordGenerada || !passwordGenerada.value) {
        console.error('❌ No hay contraseña para copiar');
        return;
    }
    
    navigator.clipboard.writeText(passwordGenerada.value)
        .then(() => {
            console.log('✅ Contraseña copiada');
            mostrarMensaje('✅ Contraseña copiada al portapapeles', 'exito');
        })
        .catch(err => {
            console.error('❌ Error al copiar:', err);
            // Fallback
            passwordGenerada.select();
            document.execCommand('copy');
            mostrarMensaje('✅ Contraseña copiada (método alternativo)', 'exito');
        });
}

// ============================================ -->
// FUNCIONES UTILITARIAS
// ============================================ -->
function mostrarMensaje(texto, tipo) {
    console.log('📢 Mostrando mensaje:', texto, tipo);
    
    const alertaContainer = document.getElementById('alertaContainer');
    const alerta = document.getElementById('alerta');
    
    if (!alertaContainer || !alerta) {
        console.error('❌ No se encontraron elementos para mostrar mensaje');
        return;
    }
    
    alertaContainer.classList.remove('hidden');
    alerta.className = `p-4 rounded-lg ${tipo === 'exito' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}`;
    alerta.innerHTML = `<i class="fas mr-2 ${tipo === 'exito' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>${texto}`;
    
    setTimeout(() => {
        alertaContainer.classList.add('hidden');
    }, 3000);
}

// ============================================ -->
// INICIALIZACIÓN PRINCIPAL
// ============================================ -->
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 ===== INICIALIZANDO DANGER ZONE =====');
    console.log('📊 Datos del usuario:', {
        id: usuarioId,
        correoCorporativo,
        correoPersonal,
        nombreUsuario,
        adminNombre
    });
    
    // Intentar inicializar inmediatamente
    setTimeout(inicializarDangerZone, 300);
});

// También cuando Alpine.js cambie de tab
document.addEventListener('alpine:init', () => {
    console.log('⚡ Alpine.js detectado');
});

document.addEventListener('alpine:initialized', () => {
    console.log('🎯 Alpine.js inicializado completamente');
    // Reintentar inicialización cuando Alpine esté listo
    setTimeout(inicializarDangerZone, 500);
});

// Si hay un evento personalizado cuando cambia la tab
document.addEventListener('tab-changed', (e) => {
    if (e.detail === 'danger-zone') {
        console.log('🔄 Tab cambiada a danger-zone');
        setTimeout(inicializarDangerZone, 200);
    }
});
</script>

<!-- Estilos adicionales -->
<style>
/* Animaciones para mensajes */
#alertaContainer {
    transition: opacity 0.3s ease;
}
#alertaContainer.hidden {
    display: none;
}
#alerta {
    animation: slideIn 0.3s ease;
}
@keyframes slideIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Mejoras para botones */
.btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}
</style>





    <script>
        $(document).ready(function() {
            // ============================================
            // DATOS DE BANCOS, MONEDAS Y TIPOS DE CUENTA (desde PHP)
            // ============================================
            var bancosData = {
                @foreach ($bancos as $key => $banco)
                    "{{ $key }}": "{{ $banco }}",
                @endforeach
            };

            var monedasData = {
                @foreach ($monedas as $key => $moneda)
                    "{{ $key }}": "{{ $moneda }}",
                @endforeach
            };

            var tiposCuentaData = {
                @foreach ($tiposCuenta as $key => $tipo)
                    "{{ $key }}": "{{ $tipo }}",
                @endforeach
            };

            // ============================================
            // GUARDAR CUENTA BANCARIA
            // ============================================
            $(document).on('click', '#saveBtn', function(e) {
                e.preventDefault();

                let userId = {{ $usuario->idUsuario }};
                let csrfToken = $('meta[name="csrf-token"]').attr('content');

                // Obtener valores
                let banco = $('#banco').val();
                let moneda = $('#moneda').val();
                let tipoCuenta = $('#tipoCuenta').val();
                let numeroCuenta = $('#numeroCuenta').val();
                let numeroCCI = $('#numeroCCI').val();

                // Validaciones básicas
                if (!banco) {
                    toastr.error('Por favor, seleccione un banco');
                    return;
                }
                if (!moneda) {
                    toastr.error('Por favor, seleccione una moneda');
                    return;
                }
                if (!tipoCuenta) {
                    toastr.error('Por favor, seleccione un tipo de cuenta');
                    return;
                }
                if (!numeroCuenta || numeroCuenta.trim() === '') {
                    toastr.error('Por favor, ingrese el número de cuenta');
                    return;
                }
                if (!numeroCCI || numeroCCI.trim() === '') {
                    toastr.error('Por favor, ingrese el número de CCI');
                    return;
                }

                let data = {
                    entidadBancaria: banco,
                    moneda: moneda,
                    tipoCuenta: tipoCuenta,
                    numeroCuenta: numeroCuenta,
                    numeroCCI: numeroCCI
                };

                // Mostrar indicador de carga
                let $btn = $(this);
                let originalText = $btn.html();
                $btn.html('<i class="fas fa-spinner fa-spin"></i> Guardando...').prop('disabled', true);

                $.ajax({
                    url: '/usuario/' + userId + '/cuenta-bancaria/guardar',
                    type: 'POST',
                    data: JSON.stringify(data),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);

                            // Actualizar la vista sin recargar
                            actualizarVistaCuentaBancaria(response.data);

                            // Cambiar título y botón
                            $('#form-title').text('Editar Cuenta Bancaria');
                            $btn.html('<i class="fas fa-save"></i> Actualizar Cuenta Bancaria');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                toastr.error(value[0]);
                            });
                        } else {
                            toastr.error('Error al guardar la cuenta bancaria');
                        }
                        $btn.html(originalText);
                    },
                    complete: function() {
                        $btn.prop('disabled', false);
                    }
                });
            });

            // ============================================
            // ACTUALIZAR VISTA DE CUENTA BANCARIA
            // ============================================
            function actualizarVistaCuentaBancaria(data) {
                let nombreBanco = bancosData[data.entidadBancaria] || 'Banco no especificado';
                let nombreMoneda = monedasData[data.moneda] || data.moneda;
                let nombreTipoCuenta = tiposCuentaData[data.tipoCuenta] || data.tipoCuenta;

                let cuentaHTML = `
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-5 border border-green-200 dark:border-green-800/30">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white">
                                <i class="fas fa-university"></i>
                            </div>
                            <div>
                                <h6 class="font-semibold text-gray-800 dark:text-white">${nombreBanco}</h6>
                                <p class="text-xs text-gray-500 dark:text-gray-400">${nombreMoneda} - ${nombreTipoCuenta}</p>
                            </div>
                        </div>
                        <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 text-xs font-medium rounded-full">
                            Principal
                        </span>
                    </div>
                    
                    <div class="space-y-2">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Número de Cuenta</p>
                            <p class="font-mono text-sm font-medium text-gray-800 dark:text-white">${data.numeroCuenta}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Número de CCI</p>
                            <p class="font-mono text-sm font-medium text-gray-800 dark:text-white">${data.numeroCCI}</p>
                        </div>
                    </div>
                    
                    <div class="flex justify-end gap-2 mt-4 pt-3 border-t border-green-200 dark:border-green-800/30">
                        <button type="button" class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-1 edit-cuenta-btn">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                    </div>
                </div>
            `;

                $('#cuenta-container').html(cuentaHTML);
            }

            // ============================================
            // EDITAR CUENTA (scroll al formulario)
            // ============================================
            $(document).on('click', '.edit-cuenta-btn', function() {
                $('html, body').animate({
                    scrollTop: $('#cuenta-bancaria-form').offset().top - 100
                }, 500);

                // Resaltar el formulario
                $('#cuenta-bancaria-form').addClass('ring-2 ring-purple-500 ring-opacity-50');
                setTimeout(function() {
                    $('#cuenta-bancaria-form').removeClass(
                        'ring-2 ring-purple-500 ring-opacity-50');
                }, 2000);
            });

            // ============================================
            // VALIDACIONES EN TIEMPO REAL
            // ============================================
            $('#numeroCuenta').on('input', function() {
                this.value = this.value.replace(/[^0-9-]/g, '');
            });

            $('#numeroCCI').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });
    </script>
</x-layout.default>
