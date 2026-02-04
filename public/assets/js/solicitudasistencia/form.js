// assets/js/solicitudasistencia/form.js
(function () {
    const tipoSelect = document.getElementById('tipoSolicitud');
    const boxEducativo = document.getElementById('boxEducativo');
    const boxTipoEducacion = document.getElementById('boxTipoEducacion');
    const boxLicencia = document.getElementById('boxLicenciaMedica');
    const diasBody = document.getElementById('diasEducativoBody');

    const diasSemana = ['lunes','martes','miercoles','jueves','viernes','sabado'];

    // Configuración global de flatpickr para tiempo
    const flatpickrConfig = {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        minuteIncrement: 5,
        locale: {
            firstDayOfWeek: 1
        }
    };

    function tipoActual() {
        const opt = tipoSelect?.selectedOptions[0];
        return opt?.value ? parseInt(opt.value) : 0;
    }

    function toggleSections() {
        const tipoId = tipoActual();

        // IMPORTANTE: ID 6 es Educativo (como en tu app)
        const esEducativo = (tipoId === 6);
        const esLicencia = (tipoId === 2);

        boxEducativo.classList.toggle('hidden', !esEducativo);
        boxTipoEducacion.classList.toggle('hidden', !esEducativo);
        boxLicencia.classList.toggle('hidden', !esLicencia);

        if (esEducativo && diasBody.children.length === 0) {
            renderDias();
            inicializarFlatpickr();
        }
    }

    function renderDias() {
        diasBody.innerHTML = '';

        diasSemana.forEach((dia, i) => {
            diasBody.insertAdjacentHTML('beforeend', `
                <tr data-dia-index="${i}">
                    <td class="p-2 font-semibold capitalize">${dia}</td>

                    <td class="p-2 text-center">
                        <input type="checkbox"
                               name="dias[${i}][es_todo_el_dia]"
                               value="1"
                               class="todo-dia">
                        <input type="hidden"
                               name="dias[${i}][dia]"
                               value="${dia}">
                    </td>

                    <td class="p-2">
                        <input type="text"
                               name="dias[${i}][hora_entrada]"
                               class="w-full border rounded hora hora-entrada flatpickr-time px-4 py-4"
                               placeholder="HH:mm"
                               readonly>
                    </td>

                    <td class="p-2">
                        <input type="text"
                               name="dias[${i}][hora_salida]"
                               class="w-full border rounded hora hora-salida flatpickr-time px-4 py-4"
                               placeholder="HH:mm"
                               readonly>
                    </td>

                    <td class="p-2">
                        <input type="text"
                               name="dias[${i}][hora_llegada_trabajo]"
                               class="w-full border rounded hora hora-llegada flatpickr-time px-4 py-4"
                               placeholder="HH:mm"
                               readonly>
                    </td>

                    <td class="p-2">
                        <input type="text"
                               name="dias[${i}][observacion]"
                               class="w-full border rounded"
                               placeholder="Observación">
                    </td>
                </tr>
            `);
        });

        // Manejar eventos
        diasBody.querySelectorAll('.todo-dia').forEach(chk => {
            chk.addEventListener('change', e => {
                const row = e.target.closest('tr');
                const horas = row.querySelectorAll('.flatpickr-time');
                const esTodoElDia = e.target.checked;

                horas.forEach(h => {
                    if (esTodoElDia) {
                        // Deshabilitar visualmente pero mantener el input
                        h.disabled = true;
                        h.style.opacity = '0.5';
                        h.style.cursor = 'not-allowed';

                        // IMPORTANTE: Cambiar el nombre para que NO se envíe
                        const name = h.getAttribute('name');
                        h.setAttribute('data-original-name', name);
                        h.removeAttribute('name');

                        // Limpiar el valor y destruir flatpickr
                        h.value = '';
                        if (h._flatpickr) {
                            h._flatpickr.destroy();
                        }
                    } else {
                        // Habilitar visualmente
                        h.disabled = false;
                        h.style.opacity = '1';
                        h.style.cursor = 'auto';

                        // Restaurar el nombre para que se envíe
                        const originalName = h.getAttribute('data-original-name');
                        if (originalName) {
                            h.setAttribute('name', originalName);
                        }

                        // Inicializar flatpickr si no existe
                        if (!h._flatpickr) {
                            flatpickr(h, flatpickrConfig);
                        }
                    }
                });
            });
        });

        // Validar que hora_entrada < hora_salida
        diasBody.querySelectorAll('.hora-entrada, .hora-salida').forEach(input => {
            input.addEventListener('change', function() {
                const row = this.closest('tr');
                const entrada = row.querySelector('.hora-entrada');
                const salida = row.querySelector('.hora-salida');

                if (entrada.value && salida.value) {
                    const horaEntrada = convertirAHora(entrada.value);
                    const horaSalida = convertirAHora(salida.value);

                    if (horaEntrada >= horaSalida) {
                        alert('La hora de salida debe ser mayor que la hora de entrada');
                        salida.value = '';
                        if (salida._flatpickr) {
                            salida._flatpickr.clear();
                        }
                        salida.focus();
                    }
                }
            });
        });

        // Antes de enviar el formulario, asegurarnos de que se envíe correctamente
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                // Asegurarnos de que los campos de tiempo se envían correctamente
                diasBody.querySelectorAll('tr').forEach((row, i) => {
                    const todoElDiaCheckbox = row.querySelector('.todo-dia');
                    const esTodoElDia = todoElDiaCheckbox.checked;

                    if (!esTodoElDia) {
                        // Asegurar que los campos de tiempo tengan nombre
                        const horas = row.querySelectorAll('.flatpickr-time');
                        horas.forEach((h, index) => {
                            if (!h.hasAttribute('name')) {
                                const tipoHora = ['hora_entrada', 'hora_salida', 'hora_llegada_trabajo'][index];
                                h.setAttribute('name', `dias[${i}][${tipoHora}]`);
                            }
                        });
                    }
                });
            });
        }
    }

    function inicializarFlatpickr() {
        // Inicializar flatpickr en todos los campos de tiempo
        diasBody.querySelectorAll('.flatpickr-time').forEach(input => {
            // Verificar si ya tiene flatpickr inicializado
            if (!input._flatpickr) {
                flatpickr(input, flatpickrConfig);
            }
        });
    }

    // Función auxiliar para convertir string de hora a minutos para comparación
    function convertirAHora(horaStr) {
        if (!horaStr) return 0;
        const [horas, minutos] = horaStr.split(':').map(Number);
        return horas * 60 + minutos;
    }

    // Inicializar
    if (tipoSelect) {
        tipoSelect.addEventListener('change', toggleSections);
        // Verificar estado inicial
        toggleSections();
    }
})();
