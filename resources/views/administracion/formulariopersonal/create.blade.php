<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha de Datos del Personal</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

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
    </style>
</head>

<body class="min-h-screen p-4 md:p-6">
    <div class="w-full mx-auto">

        <!-- Encabezado -->
        @include('administracion.formulariopersonal.partials.header')

        <!-- Formulario -->
        <form id="personal-data-form" class="space-y-8" method="POST" action="">
            @csrf

            <!-- Sección 1: Información General -->
            @include('administracion.formulariopersonal.partials.section1')

            <!-- Sección 2: Información Académica -->
            @include('administracion.formulariopersonal.partials.section2')

            <!-- Sección 3: Información Familiar -->
            @include('administracion.formulariopersonal.partials.section3')

            <!-- Sección 4: Información de Salud -->
            @include('administracion.formulariopersonal.partials.section4')

            <!-- Sección 5: Datos Laborales -->
            @include('administracion.formulariopersonal.partials.section5')

            <!-- Sección 6: Documentos Importantes -->
            @include('administracion.formulariopersonal.partials.section6')


            <!-- Botones y pie -->
            @include('administracion.formulariopersonal.partials.footer')
        </form>
    </div>

    <script>
        // JavaScript del formulario
        document.addEventListener('DOMContentLoaded', function() {
            // Calcular progreso
            function calculateProgress() {
                const requiredFields = document.querySelectorAll('input[required], select[required]');
                let completed = 0;

                requiredFields.forEach(field => {
                    if (field.type === 'checkbox' || field.type === 'radio') {
                        const name = field.name;
                        const checked = document.querySelector(`input[name="${name}"]:checked`);
                        if (checked) completed++;
                    } else {
                        if (field.value.trim() !== '') completed++;
                    }
                });

                const percentage = Math.round((completed / requiredFields.length) * 100);
                document.getElementById('progress-percentage').textContent = `${percentage}%`;
                document.getElementById('progress-bar').style.width = `${percentage}%`;
            }

            // Escuchar cambios
            document.querySelectorAll('input[required], select[required]').forEach(field => {
                field.addEventListener('input', calculateProgress);
                field.addEventListener('change', calculateProgress);
            });

            // Calcular edad
            function calculateAge() {
                const day = document.getElementById('dia').value;
                const month = document.getElementById('mes').value;
                const year = document.getElementById('anio').value;

                if (day && month && year) {
                    const birthDate = new Date(year, month - 1, day);
                    const today = new Date();
                    let age = today.getFullYear() - birthDate.getFullYear();
                    const monthDiff = today.getMonth() - birthDate.getMonth();

                    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                        age--;
                    }

                    document.getElementById('edad').value = age;
                }
            }

            // Eventos para fecha de nacimiento
            document.getElementById('dia').addEventListener('input', calculateAge);
            document.getElementById('mes').addEventListener('change', calculateAge);
            document.getElementById('anio').addEventListener('input', calculateAge);

            // Limpiar formulario
            document.getElementById('clear-form')?.addEventListener('click', function() {
                if (confirm('¿Está seguro de que desea limpiar todo el formulario?')) {
                    document.getElementById('personal-data-form').reset();
                    calculateProgress();
                }
            });

            // Guardar borrador
            document.getElementById('save-draft')?.addEventListener('click', function() {
                alert('Borrador guardado exitosamente.');
            });

            // Enviar formulario
            document.getElementById('personal-data-form')?.addEventListener('submit', function(e) {
                e.preventDefault();

                const requiredFields = document.querySelectorAll('input[required], select[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (field.type === 'checkbox' || field.type === 'radio') {
                        const name = field.name;
                        const checked = document.querySelector(`input[name="${name}"]:checked`);
                        if (!checked) isValid = false;
                    } else {
                        if (field.value.trim() === '') {
                            isValid = false;
                            field.classList.add('border-red-500');
                        }
                    }
                });

                if (isValid) {
                    alert('Formulario enviado exitosamente.');
                    this.submit();
                } else {
                    alert('Complete todos los campos obligatorios.');
                }
            });

            // Inicializar progreso
            calculateProgress();
        });
    </script>
</body>

</html>
