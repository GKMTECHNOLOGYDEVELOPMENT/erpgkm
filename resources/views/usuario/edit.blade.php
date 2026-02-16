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
                $('#provincia').empty().prop('disabled', true).append('<option value="" disabled selected>Seleccionar Provincia</option>');
                $('#distrito').empty().prop('disabled', true).append('<option value="" disabled selected>Seleccionar Distrito</option>');
                return;
            }

            $.ajax({
                url: '/ubigeo/provincias/' + departamentoId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var provinciaSelect = $('#provincia');
                    provinciaSelect.empty().prop('disabled', false);
                    provinciaSelect.append('<option value="" disabled selected>Seleccionar Provincia</option>');

                    if (data && data.length > 0) {
                        $.each(data, function(index, provincia) {
                            provinciaSelect.append('<option value="' + provincia.id_ubigeo + '">' +
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
                $('#distrito').empty().prop('disabled', true).append('<option value="" disabled selected>Seleccionar Distrito</option>');
                return;
            }

            $.ajax({
                url: '/ubigeo/distritos/' + provinciaId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var distritoSelect = $('#distrito');
                    distritoSelect.empty().prop('disabled', false);
                    distritoSelect.append('<option value="" disabled selected>Seleccionar Distrito</option>');

                    if (data && data.length > 0) {
                        $.each(data, function(index, distrito) {
                            distritoSelect.append('<option value="' + distrito.id_ubigeo + '">' +
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
            $('#distrito').empty().prop('disabled', true).append('<option value="" disabled selected>Seleccionar Distrito</option>');
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
                $('#nacimiento_provincia').empty().prop('disabled', true).append('<option value="">Seleccionar Provincia</option>');
                $('#nacimiento_distrito').empty().prop('disabled', true).append('<option value="">Seleccionar Distrito</option>');
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
                            provinciaSelect.append('<option value="' + provincia.id_ubigeo + '">' +
                                provincia.nombre_ubigeo + '</option>');
                        });

                        var provinciaSeleccionada = '{{ $fichaGeneral->nacimientoProvincia ?? '' }}';
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
                $('#nacimiento_distrito').empty().prop('disabled', true).append('<option value="">Seleccionar Distrito</option>');
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
                            distritoSelect.append('<option value="' + distrito.id_ubigeo + '">' +
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
            $('#nacimiento_distrito').empty().prop('disabled', true).append('<option value="">Seleccionar Distrito</option>');
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
    $(document).ready(function() {
        // ============================================
        // INICIALIZAR FLATPICKR PARA FECHAS COVID
        // ============================================
        if (typeof flatpickr !== 'undefined') {
            flatpickr(".flatpickr", {
                dateFormat: "Y-m-d",
                allowInput: true,
                locale: "es"
            });
        }

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
                title: 'Agregar Familiar',
                html: `
                    <form id="familiar-form" class="space-y-3">
                        <select class="swal2-input" name="parentesco" required>
                            <option value="">Seleccionar parentesco</option>
                            <option value="CONYUGE">Cónyuge</option>
                            <option value="CONCUBINO">Concubino/a</option>
                            <option value="HIJO">Hijo/a</option>
                        </select>
                        <input type="text" class="swal2-input" name="apellidosNombres" placeholder="Apellidos y Nombres" required>
                        <input type="text" class="swal2-input" name="nroDocumento" placeholder="N° Documento">
                        <input type="text" class="swal2-input" name="ocupacion" placeholder="Ocupación">
                        <select class="swal2-input" name="sexo">
                            <option value="">Seleccionar sexo</option>
                            <option value="MASCULINO">Masculino</option>
                            <option value="FEMENINO">Femenino</option>
                        </select>
                        <input type="text" class="swal2-input flatpickr" name="fechaNacimiento" placeholder="Fecha Nacimiento">
                        <input type="text" class="swal2-input" name="domicilioActual" placeholder="Domicilio Actual">
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                width: '600px',
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
            }).then((result) => {
                if (result.isConfirmed) {
                    let data = result.value;
                    data.idUsuario = {{ $usuario->idUsuario }};
                    
                    $.ajax({
                        url: '/usuario/familiar/guardar',
                        type: 'POST',
                        data: JSON.stringify(data),
                        contentType: 'application/json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                // Agregar la nueva fila a la tabla sin recargar
                                let nuevoFamiliar = response.familiar;
                                let nuevaFila = `
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">${nuevoFamiliar.parentesco}</td>
                                        <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-200">${nuevoFamiliar.apellidosNombres}</td>
                                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">${nuevoFamiliar.nroDocumento || 'N/A'}</td>
                                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">${nuevoFamiliar.ocupacion || 'N/A'}</td>
                                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">${nuevoFamiliar.sexo || 'N/A'}</td>
                                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">${nuevoFamiliar.fechaNacimiento ? new Date(nuevoFamiliar.fechaNacimiento).toLocaleDateString('es-ES') : 'N/A'}</td>
                                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">${nuevoFamiliar.domicilioActual || 'N/A'}</td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="flex justify-center items-center gap-2">
                                                <button class="text-blue-600 hover:text-blue-800 edit-familiar" data-id="${nuevoFamiliar.idFamiliar}" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="text-red-600 hover:text-red-800 delete-familiar" data-id="${nuevoFamiliar.idFamiliar}" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                `;
                                
                                if ($('#familiares-tbody tr.empty-row').length) {
                                    $('#familiares-tbody').empty().append(nuevaFila);
                                } else {
                                    $('#familiares-tbody').append(nuevaFila);
                                }
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                $.each(errors, function(key, value) {
                                    toastr.error(value[0]);
                                });
                            } else {
                                toastr.error('Error al agregar familiar');
                            }
                        }
                    });
                }
            });
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
                                    if ($('#familiares-tbody tr').length === 0) {
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
        // EDITAR FAMILIAR
        // ============================================
        $(document).on('click', '.edit-familiar', function() {
            let id = $(this).data('id');
            let fila = $(this).closest('tr');
            
            $.ajax({
                url: '/usuario/familiar/' + id,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        let familiar = response.familiar;
                        
                        Swal.fire({
                            title: 'Editar Familiar',
                            html: `
                                <form id="familiar-edit-form" class="space-y-3">
                                    <input type="hidden" name="idFamiliar" value="${familiar.idFamiliar}">
                                    <select class="swal2-input" name="parentesco" required>
                                        <option value="">Seleccionar parentesco</option>
                                        <option value="CONYUGE" ${familiar.parentesco == 'CONYUGE' ? 'selected' : ''}>Cónyuge</option>
                                        <option value="CONCUBINO" ${familiar.parentesco == 'CONCUBINO' ? 'selected' : ''}>Concubino/a</option>
                                        <option value="HIJO" ${familiar.parentesco == 'HIJO' ? 'selected' : ''}>Hijo/a</option>
                                    </select>
                                    <input type="text" class="swal2-input" name="apellidosNombres" value="${familiar.apellidosNombres || ''}" placeholder="Apellidos y Nombres" required>
                                    <input type="text" class="swal2-input" name="nroDocumento" value="${familiar.nroDocumento || ''}" placeholder="N° Documento">
                                    <input type="text" class="swal2-input" name="ocupacion" value="${familiar.ocupacion || ''}" placeholder="Ocupación">
                                    <select class="swal2-input" name="sexo">
                                        <option value="">Seleccionar sexo</option>
                                        <option value="MASCULINO" ${familiar.sexo == 'MASCULINO' ? 'selected' : ''}>Masculino</option>
                                        <option value="FEMENINO" ${familiar.sexo == 'FEMENINO' ? 'selected' : ''}>Femenino</option>
                                    </select>
                                    <input type="text" class="swal2-input flatpickr" name="fechaNacimiento" value="${familiar.fechaNacimiento || ''}" placeholder="Fecha Nacimiento">
                                    <input type="text" class="swal2-input" name="domicilioActual" value="${familiar.domicilioActual || ''}" placeholder="Domicilio Actual">
                                </form>
                            `,
                            showCancelButton: true,
                            confirmButtonText: 'Actualizar',
                            cancelButtonText: 'Cancelar',
                            confirmButtonColor: '#3b82f6',
                            cancelButtonColor: '#6b7280',
                            width: '600px',
                            didOpen: () => {
                                if (typeof flatpickr !== 'undefined') {
                                    flatpickr(".flatpickr", {
                                        dateFormat: "Y-m-d",
                                        locale: "es"
                                    });
                                }
                            },
                            preConfirm: () => {
                                let form = document.getElementById('familiar-edit-form');
                                let formData = new FormData(form);
                                return Object.fromEntries(formData);
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                let data = result.value;
                                
                                $.ajax({
                                    url: '/usuario/familiar/' + id,
                                    type: 'PUT',
                                    data: JSON.stringify(data),
                                    contentType: 'application/json',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function(response) {
                                        if (response.success) {
                                            toastr.success(response.message);
                                            // Actualizar la fila en la tabla
                                            let familiarActualizado = response.familiar;
                                            fila.find('td:eq(0)').text(familiarActualizado.parentesco);
                                            fila.find('td:eq(1)').text(familiarActualizado.apellidosNombres);
                                            fila.find('td:eq(2)').text(familiarActualizado.nroDocumento || 'N/A');
                                            fila.find('td:eq(3)').text(familiarActualizado.ocupacion || 'N/A');
                                            fila.find('td:eq(4)').text(familiarActualizado.sexo || 'N/A');
                                            fila.find('td:eq(5)').text(familiarActualizado.fechaNacimiento ? new Date(familiarActualizado.fechaNacimiento).toLocaleDateString('es-ES') : 'N/A');
                                            fila.find('td:eq(6)').text(familiarActualizado.domicilioActual || 'N/A');
                                        }
                                    },
                                    error: function(xhr) {
                                        if (xhr.status === 422) {
                                            let errors = xhr.responseJSON.errors;
                                            $.each(errors, function(key, value) {
                                                toastr.error(value[0]);
                                            });
                                        } else {
                                            toastr.error('Error al actualizar familiar');
                                        }
                                    }
                                });
                            }
                        });
                    }
                },
                error: function() {
                    toastr.error('Error al cargar datos del familiar');
                }
            });
        });

        // ============================================
        // AGREGAR CONTACTO DE EMERGENCIA
        // ============================================
        $(document).on('click', '#add-contacto-btn', function() {
            Swal.fire({
                title: 'Agregar Contacto de Emergencia',
                html: `
                    <form id="contacto-form" class="space-y-3">
                        <input type="text" class="swal2-input" name="apellidosNombres" placeholder="Nombre completo" required>
                        <input type="text" class="swal2-input" name="parentesco" placeholder="Parentesco (Ej: Padre, Madre)" required>
                        <input type="text" class="swal2-input" name="direccionTelefono" placeholder="Teléfono / Dirección" required>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                width: '500px',
                preConfirm: () => {
                    let form = document.getElementById('contacto-form');
                    let formData = new FormData(form);
                    let data = Object.fromEntries(formData);
                    
                    if (!data.apellidosNombres) {
                        Swal.showValidationMessage('Debe ingresar el nombre');
                        return false;
                    }
                    if (!data.parentesco) {
                        Swal.showValidationMessage('Debe ingresar el parentesco');
                        return false;
                    }
                    if (!data.direccionTelefono) {
                        Swal.showValidationMessage('Debe ingresar teléfono/dirección');
                        return false;
                    }
                    return data;
                }
            }).then((result) => {
                if (result.isConfirmed) {
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
                            if (response.success) {
                                toastr.success(response.message);
                                // Agregar el nuevo contacto sin recargar
                                let contacto = response.contacto;
                                let nuevoContacto = `
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-white dark:bg-[#0e1726] border border-gray-200 dark:border-gray-700 rounded-lg relative group">
                                        <button class="absolute top-2 right-2 text-gray-400 hover:text-red-600 delete-contacto" data-id="${contacto.idContacto}" title="Eliminar">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <div>
                                            <label class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1 block">Nombre Completo</label>
                                            <input type="text" class="form-input w-full text-sm contacto-nombre" value="${contacto.apellidosNombres}" placeholder="Nombre completo" data-id="${contacto.idContacto}">
                                        </div>
                                        <div>
                                            <label class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1 block">Parentesco</label>
                                            <input type="text" class="form-input w-full text-sm contacto-parentesco" value="${contacto.parentesco}" placeholder="Ej: Padre, Madre" data-id="${contacto.idContacto}">
                                        </div>
                                        <div>
                                            <label class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1 block">Teléfono / Dirección</label>
                                            <input type="text" class="form-input w-full text-sm contacto-direccion" value="${contacto.direccionTelefono}" placeholder="Ej: 987654321 - Calle 123" data-id="${contacto.idContacto}">
                                        </div>
                                    </div>
                                `;
                                
                                if ($('#contactos-container .empty-contactos').length) {
                                    $('#contactos-container').empty().append(nuevoContacto);
                                } else {
                                    $('#contactos-container').append(nuevoContacto);
                                }
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                $.each(errors, function(key, value) {
                                    toastr.error(value[0]);
                                });
                            } else {
                                toastr.error('Error al agregar contacto');
                            }
                        }
                    });
                }
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
                                    if ($('#contactos-container .grid').length === 0) {
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
                $('#covid_dosis1, #covid_dosis2, #covid_dosis3').prop('disabled', false);
            } else {
                $('#covid_dosis1, #covid_dosis2, #covid_dosis3').val('').prop('disabled', true);
            }
        });

        $(document).on('change', 'input[name="dolencia_cronica"]', function() {
            if ($(this).val() == '1') {
                $('input[name="dolencia_detalle"]').prop('disabled', false);
            } else {
                $('input[name="dolencia_detalle"]').val('').prop('disabled', true);
            }
        });

        $(document).on('change', 'input[name="discapacidad"]', function() {
            if ($(this).val() == '1') {
                $('input[name="discapacidad_detalle"]').prop('disabled', false);
            } else {
                $('input[name="discapacidad_detalle"]').val('').prop('disabled', true);
            }
        });

        // Estado inicial de los campos según valores guardados
        if ($('input[name="vacuna_covid"]:checked').val() == '0') {
            $('#covid_dosis1, #covid_dosis2, #covid_dosis3').prop('disabled', true);
        }
        if ($('input[name="dolencia_cronica"]:checked').val() == '0') {
            $('input[name="dolencia_detalle"]').prop('disabled', true);
        }
        if ($('input[name="discapacidad"]:checked').val() == '0') {
            $('input[name="discapacidad_detalle"]').prop('disabled', true);
        }
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
                    dateFormat: "Y-m-d",
                    allowInput: true,
                    locale: "es"
                });
                
                // Horas
                flatpickr(".flatpickr-time", {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true,
                    allowInput: true,
                    locale: "es"
                });
            }
        };

        // ============================================
        // ACTUALIZAR INFORMACIÓN LABORAL Y CONFIGURACIÓN
        // ============================================
        $(document).on('click', '#update-informacion-btn', function() {
            let userId = {{ $usuario->idUsuario }};
            let csrfToken = $('meta[name="csrf-token"]').attr('content');
            
            // Recolectar datos
            let data = {
                idTipoContrato: $('#idTipoContrato').val(),
                cargoTexto: $('#cargoTexto').val(),
                areaTexto: $('#areaTexto').val(),
                fechaInicio: $('#fechaInicio').val(),
                fechaTermino: $('#fechaTermino').val(),
                horaInicioJornada: $('#horaInicioJornada').val(),
                horaFinJornada: $('#horaFinJornada').val(),
                idSucursal: $('#idSucursal').val(),
                idTipoArea: $('#idTipoArea').val(),
                idTipoUsuario: $('#idTipoUsuario').val(),
                idRol: $('#idRol').val(),
                idSexo: $('#idSexo').val(),
                sueldoMensual: $('#sueldoMensual').val()
            };
            
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

</x-layout.default>
