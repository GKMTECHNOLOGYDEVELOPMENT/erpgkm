// assets/js/solicitudasistencia/form.js
(function () {
    const tipoSelect = document.getElementById('tipoSolicitud');
    const boxEducativo = document.getElementById('boxEducativo');
    const boxTipoEducacion = document.getElementById('boxTipoEducacion');
    const boxLicencia = document.getElementById('boxLicenciaMedica');
    const diasBody = document.getElementById('diasEducativoBody');

    const diasSemana = ['lunes','martes','miercoles','jueves','viernes','sabado'];

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
        }
    }

    function renderDias() {
        diasBody.innerHTML = '';

        diasSemana.forEach((dia, i) => {
            diasBody.insertAdjacentHTML('beforeend', `
                <tr>
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
                        <input type="time"
                               name="dias[${i}][hora_entrada]"
                               class="w-full border rounded hora hora-entrada"
                               placeholder="HH:mm">
                    </td>

                    <td class="p-2">
                        <input type="time"
                               name="dias[${i}][hora_salida]"
                               class="w-full border rounded hora hora-salida"
                               placeholder="HH:mm">
                    </td>

                    <td class="p-2">
                        <input type="time"
                               name="dias[${i}][hora_llegada_trabajo]"
                               class="w-full border rounded hora hora-llegada"
                               placeholder="HH:mm">
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
                const horas = row.querySelectorAll('.hora');
                
                horas.forEach(h => {
                    h.disabled = e.target.checked;
                    if (e.target.checked) {
                        h.value = '';
                        h.required = false;
                    } else {
                        h.required = true;
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
                
                if (entrada.value && salida.value && entrada.value >= salida.value) {
                    alert('La hora de salida debe ser mayor que la hora de entrada');
                    salida.value = '';
                    salida.focus();
                }
            });
        });
    }

    // Inicializar
    if (tipoSelect) {
        tipoSelect.addEventListener('change', toggleSections);
        // Verificar estado inicial
        toggleSections();
    }
})();