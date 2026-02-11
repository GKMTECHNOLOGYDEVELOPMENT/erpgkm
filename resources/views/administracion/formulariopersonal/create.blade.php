<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha de Datos del Personal</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        /* Estilos base */
        .required:after {
            content: " *";
            color: #ef4444;
        }

        .form-section {
            transition: all 0.3s ease;
        }

        .form-section:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        .section-title {
            position: relative;
        }

        .section-title:after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 60px;
            height: 3px;
            background-color: #3b82f6;
            border-radius: 3px;
        }

        /* Estilos para debug */
        .debug-console {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 400px;
            max-height: 300px;
            background: rgba(0,0,0,0.9);
            color: #0f0;
            font-family: monospace;
            font-size: 12px;
            padding: 15px;
            border-radius: 10px;
            overflow-y: auto;
            z-index: 9999;
            display: none;
            border: 2px solid #00ff00;
            box-shadow: 0 0 20px rgba(0,255,0,0.3);
        }
        
        .debug-console.show {
            display: block;
        }
        
        .debug-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            border-bottom: 1px solid #0f0;
            padding-bottom: 5px;
        }
        
        .debug-title {
            color: #0f0;
            font-weight: bold;
        }
        
        .debug-close {
            cursor: pointer;
            color: #ff4444;
        }
        
        .debug-log {
            margin-bottom: 5px;
            border-bottom: 1px solid #333;
            padding-bottom: 5px;
        }
        
        .debug-time {
            color: #888;
            margin-right: 10px;
        }
        
        .debug-data {
            color: #fff;
            white-space: pre-wrap;
            word-break: break-all;
        }
    </style>
</head>

<body class="min-h-screen p-4 md:p-6">
    
    <!-- DEBUG CONSOLE -->
    <div id="debugConsole" class="debug-console">
        <div class="debug-header">
            <span class="debug-title">🔍 DEBUG CONSOLE - Formulario Personal</span>
            <span id="debugClose" class="debug-close">✕ Cerrar</span>
        </div>
        <div id="debugLogs" style="max-height: 250px; overflow-y: auto;">
            <div class="debug-log">
                <span class="debug-time">[INIT]</span>
                <span class="debug-data">Sistema de depuración activado</span>
            </div>
        </div>
    </div>

    <div class="w-full mx-auto">
        <!-- Encabezado -->
        @include('administracion.formulariopersonal.partials.header')

        <!-- Formulario -->
        <form id="personal-data-form" class="space-y-8" method="POST" action="{{ route('admin.formulario-personal.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Sección 1: Información General -->
            @include('administracion.formulariopersonal.partials.section1')

            <!-- Sección 2: Información Académica -->
            @include('administracion.formulariopersonal.partials.section2')

            <!-- Sección 3: Información Familiar -->
            @include('administracion.formulariopersonal.partials.section3')

            <!-- Sección 4: Información de Salud -->
            @include('administracion.formulariopersonal.partials.section4')

            <!-- Botones y pie -->
            @include('administracion.formulariopersonal.partials.footer')
        </form>
    </div>

    <script>
        // ========== SISTEMA DE DEBUG ==========
        const DEBUG = {
            enabled: true,
            logs: [],
            
            init: function() {
                if (!this.enabled) return;
                
                // Crear console overlay
                const consoleElement = document.getElementById('debugConsole');
                
                // Evento para cerrar
                document.getElementById('debugClose').addEventListener('click', function() {
                    document.getElementById('debugConsole').classList.remove('show');
                });
                
                // Mostrar consola con Ctrl+Shift+D
                document.addEventListener('keydown', function(e) {
                    if (e.ctrlKey && e.shiftKey && e.key === 'D') {
                        e.preventDefault();
                        document.getElementById('debugConsole').classList.toggle('show');
                    }
                });
                
                // Mostrar automáticamente
                setTimeout(() => {
                    document.getElementById('debugConsole').classList.add('show');
                }, 1000);
                
                this.log('🚀 SISTEMA DE DEBUG INICIADO', 'system');
                this.log('💡 Presiona Ctrl+Shift+D para abrir/cerrar consola', 'info');
            },
            
            log: function(message, type = 'info', data = null) {
                if (!this.enabled) return;
                
                const timestamp = new Date().toLocaleTimeString('es-PE', { hour12: false });
                const logEntry = {
                    time: timestamp,
                    message: message,
                    type: type,
                    data: data
                };
                
                this.logs.push(logEntry);
                
                // Agregar al DOM
                const debugLogs = document.getElementById('debugLogs');
                const logElement = document.createElement('div');
                logElement.className = 'debug-log';
                
                let color = '#fff';
                let icon = 'ℹ️';
                
                switch(type) {
                    case 'error': color = '#ff4444'; icon = '❌'; break;
                    case 'success': color = '#44ff44'; icon = '✅'; break;
                    case 'warning': color = '#ffaa44'; icon = '⚠️'; break;
                    case 'system': color = '#44aaff'; icon = '🔧'; break;
                    case 'request': color = '#aa44ff'; icon = '📤'; break;
                    case 'response': color = '#44ffaa'; icon = '📥'; break;
                    default: color = '#fff'; icon = 'ℹ️';
                }
                
                logElement.innerHTML = `
                    <span class="debug-time" style="color: #888;">[${timestamp}]</span>
                    <span style="color: ${color};">${icon} ${message}</span>
                    ${data ? `<div class="debug-data" style="color: #aaa; margin-left: 20px; margin-top: 5px;">${JSON.stringify(data, null, 2)}</div>` : ''}
                `;
                
                debugLogs.appendChild(logElement);
                debugLogs.scrollTop = debugLogs.scrollHeight;
                
                // También al console del navegador
                console.log(`[${timestamp}] [${type}] ${message}`, data || '');
            },
            
            clear: function() {
                document.getElementById('debugLogs').innerHTML = '';
                this.logs = [];
                this.log('🧹 Consola limpiada', 'system');
            }
        };

        // ========== COLECTOR DE DATOS DEL FORMULARIO ==========
        const FormDataCollector = {
            
            // Recolectar TODOS los datos del formulario
            collectAllData: function() {
                DEBUG.log('📊 Recolectando datos del formulario...', 'system');
                
                const formData = new FormData(document.getElementById('personal-data-form'));
                const data = {};
                
                // 1. Recolectar campos normales
                for (let [key, value] of formData.entries()) {
                    if (value instanceof File) {
                        data[key] = {
                            name: value.name,
                            size: value.size,
                            type: value.type
                        };
                    } else {
                        data[key] = value;
                    }
                }
                
                // 2. Recolectar estudios
                const estudios = [];
                const niveles = ['secundaria', 'tecnico', 'universitario', 'postgrado'];
                
                niveles.forEach(nivel => {
                    const terminoDesktop = document.querySelector(`[name="${nivel}_termino"]:not([id$="_mobile"]):checked`);
                    const terminoMobile = document.querySelector(`[name="${nivel}_termino"][id$="_mobile"]:checked`);
                    const termino = terminoDesktop || terminoMobile;
                    
                    if (termino) {
                        const estudio = {
                            nivel: nivel.toUpperCase(),
                            termino: termino.value,
                            centro: document.querySelector(`[name="${nivel}_centro"]`)?.value || '',
                            especialidad: document.querySelector(`[name="${nivel}_especialidad"]`)?.value || '',
                            grado: document.querySelector(`[name="${nivel}_grado"]`)?.value || '',
                            inicio: document.querySelector(`[name="${nivel}_inicio"]`)?.value || '',
                            fin: document.querySelector(`[name="${nivel}_fin"]`)?.value || ''
                        };
                        estudios.push(estudio);
                    }
                });
                
                if (estudios.length > 0) {
                    data.estudios = estudios;
                }
                
                // 3. Recolectar familiares
                const familiares = [];
                document.querySelectorAll('[data-familiar-id]').forEach(familiarRow => {
                    const familiarId = familiarRow.getAttribute('data-familiar-id');
                    
                    const nombres = document.querySelector(`[name="familiares[${familiarId}][nombres]"]`)?.value;
                    if (nombres) {
                        familiares.push({
                            parentesco: document.querySelector(`[name="familiares[${familiarId}][parentesco]"]`)?.value,
                            nombres: nombres,
                            documento: document.querySelector(`[name="familiares[${familiarId}][documento]"]`)?.value,
                            ocupacion: document.querySelector(`[name="familiares[${familiarId}][ocupacion]"]`)?.value,
                            sexo: document.querySelector(`[name="familiares[${familiarId}][sexo]"]`)?.value,
                            fecha_nacimiento: document.querySelector(`[name="familiares[${familiarId}][fecha_nacimiento]"]`)?.value,
                            domicilio: document.querySelector(`[name="familiares[${familiarId}][domicilio]"]`)?.value
                        });
                    }
                });
                
                if (familiares.length > 0) {
                    data.familiares = familiares;
                }
                
                // 4. Recolectar contactos de emergencia adicionales
                for (let i = 3; i <= 5; i++) {
                    const nombres = document.querySelector(`[name="emergencia${i}_nombres"]`)?.value;
                    if (nombres) {
                        data[`emergencia${i}_nombres`] = nombres;
                        data[`emergencia${i}_parentesco`] = document.querySelector(`[name="emergencia${i}_parentesco"]`)?.value;
                        data[`emergencia${i}_direccion`] = document.querySelector(`[name="emergencia${i}_direccion"]`)?.value;
                    }
                }
                
                return { data, formData };
            },
            
            // Validar campos obligatorios
            validateRequired: function() {
                const errors = [];
                
                // Contactos de emergencia
                if (!document.querySelector('[name="emergencia1_nombres"]')?.value) {
                    errors.push('Contacto de emergencia #1 - Nombres');
                }
                if (!document.querySelector('[name="emergencia2_nombres"]')?.value) {
                    errors.push('Contacto de emergencia #2 - Nombres');
                }
                
                // DNI declaración
                if (!document.querySelector('[name="dni_declaracion"]')?.value) {
                    errors.push('DNI en declaración jurada');
                }
                
                // Fecha declaración
                if (!document.querySelector('[name="dia_declaracion"]')?.value) {
                    errors.push('Día de declaración');
                }
                if (!document.querySelector('[name="mes_declaracion"]')?.value) {
                    errors.push('Mes de declaración');
                }
                if (!document.querySelector('[name="anio_declaracion"]')?.value) {
                    errors.push('Año de declaración');
                }
                
                // Checkbox aceptación
                if (!document.getElementById('acepto_declaracion')?.checked) {
                    errors.push('Aceptación de declaración jurada');
                }
                
                return errors;
            }
        };

        // ========== CONFIGURACIÓN DE TOASTR ==========
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: 5000,
            extendedTimeOut: 2000,
            showDuration: 300,
            hideDuration: 300
        };

        // ========== INICIALIZACIÓN ==========
        document.addEventListener('DOMContentLoaded', function() {
            
            // Iniciar DEBUG
            DEBUG.init();
            
            // ========== CALCULAR PROGRESO ==========
            function calculateProgress() {
                const requiredFields = document.querySelectorAll('input[required], select[required]');
                let completed = 0;

                requiredFields.forEach(field => {
                    if (field.type === 'checkbox' || field.type === 'radio') {
                        const name = field.name;
                        const checked = document.querySelector(`input[name="${name}"]:checked`);
                        if (checked) completed++;
                    } else {
                        if (field.value && field.value.trim() !== '') completed++;
                    }
                });

                const percentage = requiredFields.length > 0 ? Math.round((completed / requiredFields.length) * 100) : 0;
                const progressElement = document.getElementById('progress-percentage');
                const progressBar = document.getElementById('progress-bar');
                
                if (progressElement) progressElement.textContent = `${percentage}%`;
                if (progressBar) progressBar.style.width = `${percentage}%`;
                
                DEBUG.log(`📈 Progreso general: ${percentage}% (${completed}/${requiredFields.length})`, 'info');
            }

            // Escuchar cambios
            document.querySelectorAll('input[required], select[required]').forEach(field => {
                field.addEventListener('input', calculateProgress);
                field.addEventListener('change', calculateProgress);
            });

            // ========== CALCULAR EDAD ==========
            function calculateAge() {
                const day = document.getElementById('dia')?.value;
                const month = document.getElementById('mes')?.value;
                const year = document.getElementById('anio')?.value;

                if (day && month && year) {
                    const birthDate = new Date(year, month - 1, day);
                    const today = new Date();
                    let age = today.getFullYear() - birthDate.getFullYear();
                    const monthDiff = today.getMonth() - birthDate.getMonth();

                    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                        age--;
                    }

                    const edadInput = document.getElementById('edad');
                    if (edadInput) {
                        edadInput.value = age;
                        DEBUG.log(`🎂 Edad calculada: ${age} años`, 'success');
                    }
                }
            }

            // Eventos para fecha de nacimiento
            const diaInput = document.getElementById('dia');
            const mesInput = document.getElementById('mes');
            const anioInput = document.getElementById('anio');
            
            if (diaInput) diaInput.addEventListener('input', calculateAge);
            if (mesInput) mesInput.addEventListener('change', calculateAge);
            if (anioInput) anioInput.addEventListener('input', calculateAge);

            // ========== GUARDAR BORRADOR ==========
            document.getElementById('save-draft')?.addEventListener('click', function(e) {
                e.preventDefault();
                
                DEBUG.log('💾 Intentando guardar borrador...', 'request');
                
                const { data, formData } = FormDataCollector.collectAllData();
                
                DEBUG.log('📦 Datos del borrador:', 'info', data);
                
                // Enviar vía AJAX
                $.ajax({
                    url: '{{ route("admin.formulario-personal.draft") }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        DEBUG.log('✅ Borrador guardado exitosamente', 'success', response);
                        toastr.success('Borrador guardado exitosamente', '✅ Éxito');
                    },
                    error: function(xhr) {
                        DEBUG.log('❌ Error al guardar borrador', 'error', {
                            status: xhr.status,
                            response: xhr.responseJSON
                        });
                        
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            Object.keys(errors).forEach(key => {
                                toastr.error(errors[key][0], '❌ Error');
                            });
                        } else {
                            toastr.error('Error al guardar borrador', '❌ Error');
                        }
                    }
                });
            });

            // ========== ENVIAR FORMULARIO ==========
            document.getElementById('personal-data-form')?.addEventListener('submit', function(e) {
                e.preventDefault();
                
                DEBUG.log('📤 INTENTANDO ENVIAR FORMULARIO COMPLETO...', 'request');
                
                // 1. Validar campos obligatorios
                const errors = FormDataCollector.validateRequired();
                
                if (errors.length > 0) {
                    DEBUG.log('❌ Validación fallida - Campos obligatorios:', 'error', errors);
                    
                    let errorMessage = 'Complete los siguientes campos obligatorios:\n';
                    errors.forEach(error => {
                        errorMessage += `\n• ${error}`;
                    });
                    
                    Swal.fire({
                        title: '❌ Campos obligatorios',
                        html: errorMessage.replace(/\n/g, '<br>'),
                        icon: 'error',
                        confirmButtonColor: '#ef4444',
                        confirmButtonText: 'Entendido'
                    });
                    
                    return;
                }
                
                // 2. Recolectar todos los datos
                const { data, formData } = FormDataCollector.collectAllData();
                
                DEBUG.log('📦 DATOS COMPLETOS DEL FORMULARIO:', 'info', data);
                
                // 3. Verificar específicamente los estudios
                if (data.estudios) {
                    DEBUG.log('🎓 Estudios encontrados:', 'success', data.estudios);
                } else {
                    DEBUG.log('⚠️ No se encontraron estudios registrados', 'warning');
                }
                
                // 4. Verificar familiares
                if (data.familiares) {
                    DEBUG.log('👨‍👩‍👧 Familiares encontrados:', 'success', data.familiares);
                }
                
                // 5. Verificar contactos de emergencia
                DEBUG.log('📞 Contactos de emergencia:', 'info', {
                    contacto1: {
                        nombres: data.emergencia1_nombres,
                        parentesco: data.emergencia1_parentesco
                    },
                    contacto2: {
                        nombres: data.emergencia2_nombres,
                        parentesco: data.emergencia2_parentesco
                    }
                });
                
                // 6. Confirmar envío
                Swal.fire({
                    title: '¿Enviar formulario?',
                    html: `¿Está seguro de enviar el formulario?<br><br>
                           <span style="color: #ef4444; font-weight: bold;">Una vez enviado, no podrá modificar la información.</span>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#4f46e5',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Sí, enviar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        
                        // Deshabilitar botón
                        const submitBtn = document.getElementById('submit-form');
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i><span>Enviando...</span>';
                        
                        DEBUG.log('🚀 Enviando petición al servidor...', 'request');
                        
                        // Enviar vía AJAX
                        $.ajax({
                            url: this.action,
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                DEBUG.log('✅ FORMULARIO ENVIADO EXITOSAMENTE', 'success', response);
                                
                                Swal.fire({
                                    title: '✅ ¡Éxito!',
                                    html: `Formulario guardado exitosamente<br>
                                           <span style="color: #4f46e5; font-weight: bold;">ID Usuario: ${response.data.idUsuario}</span><br>
                                           <span>Usuario: ${response.data.usuario}</span>`,
                                    icon: 'success',
                                    confirmButtonColor: '#4f46e5',
                                    confirmButtonText: 'Aceptar'
                                });
                                
                                toastr.success('Formulario enviado exitosamente', '✅ Éxito');
                            },
                            error: function(xhr) {
                                DEBUG.log('❌ ERROR AL ENVIAR FORMULARIO', 'error', {
                                    status: xhr.status,
                                    statusText: xhr.statusText,
                                    response: xhr.responseJSON
                                });
                                
                                // Restaurar botón
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i><span>Enviar Formulario Completado</span>';
                                
                                let errorMessage = 'Error al enviar el formulario:<br>';
                                
                                if (xhr.responseJSON && xhr.responseJSON.errors) {
                                    const errors = xhr.responseJSON.errors;
                                    Object.keys(errors).forEach(key => {
                                        errorMessage += `• ${errors[key][0]}<br>`;
                                    });
                                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage += xhr.responseJSON.message;
                                } else {
                                    errorMessage += 'Error de conexión con el servidor';
                                }
                                
                                Swal.fire({
                                    title: '❌ Error',
                                    html: errorMessage,
                                    icon: 'error',
                                    confirmButtonColor: '#ef4444',
                                    confirmButtonText: 'Cerrar'
                                });
                            }
                        });
                    }
                });
            });

            // ========== LIMPIAR FORMULARIO ==========
            document.getElementById('clear-form')?.addEventListener('click', function() {
                Swal.fire({
                    title: '¿Limpiar formulario?',
                    text: 'Se perderán todos los datos ingresados',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Sí, limpiar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('personal-data-form').reset();
                        calculateProgress();
                        DEBUG.log('🧹 Formulario limpiado', 'system');
                        toastr.warning('Formulario limpiado', '⚠️ Atención');
                    }
                });
            });

            // ========== BOTÓN DE DEBUG ==========
            // Agregar botón flotante de debug
            const debugBtn = document.createElement('button');
            debugBtn.id = 'debugToggleBtn';
            debugBtn.innerHTML = '🐞 Debug';
            debugBtn.style.cssText = `
                position: fixed;
                bottom: 20px;
                left: 20px;
                z-index: 9998;
                background: #4f46e5;
                color: white;
                border: none;
                border-radius: 50px;
                padding: 10px 20px;
                font-weight: bold;
                cursor: pointer;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                transition: all 0.3s ease;
            `;
            
            debugBtn.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.05)';
                this.style.boxShadow = '0 6px 8px rgba(0,0,0,0.2)';
            });
            
            debugBtn.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
                this.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)';
            });
            
            debugBtn.addEventListener('click', function() {
                document.getElementById('debugConsole').classList.toggle('show');
            });
            
            document.body.appendChild(debugBtn);
            
            DEBUG.log('🐞 Botón de debug agregado', 'system');
            DEBUG.log('📋 Listo para recibir datos del formulario', 'success');
            
            // Calcular progreso inicial
            calculateProgress();
        });
    </script>
</body>

</html>