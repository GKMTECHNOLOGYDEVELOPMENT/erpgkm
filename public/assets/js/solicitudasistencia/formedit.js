(function () {
    const tipoSelect = document.getElementById('tipoSolicitud');
    const boxEducativo = document.getElementById('boxEducativo');
    const boxTipoEducacion = document.getElementById('boxTipoEducacion');
    const boxLicencia = document.getElementById('boxLicenciaMedica');
    const diasBody = document.getElementById('diasEducativoBody');

    const diasSemana = ['lunes','martes','miercoles','jueves','viernes','sabado'];
    const dataEdit = window.__DIAS_EDIT__ || {};

    const normalize = s => (s || '').toLowerCase().trim();

    function tipoActual() {
        const opt = tipoSelect?.selectedOptions[0];
        return normalize(opt?.dataset?.nombre);
    }

    function toggleSections() {
        const tipo = tipoActual();

        const esEducativo = tipo === 'educativo';
        const esLicencia = tipo === 'licencia medico' || tipo === 'licencia médico';

        boxEducativo.classList.toggle('hidden', !esEducativo);
        boxTipoEducacion.classList.toggle('hidden', !esEducativo);
        boxLicencia.classList.toggle('hidden', !esLicencia);

        if (esEducativo) renderDias();
    }

    function renderDias() {
        diasBody.innerHTML = '';

        diasSemana.forEach((dia, i) => {
            const d = dataEdit[dia] || {};
            
            // Determinar si está marcado como "todo el día"
            const todoChecked = d.todo ? 'checked' : '';
            const horasDisabled = d.todo ? 'disabled' : '';

            diasBody.insertAdjacentHTML('beforeend', `
                <tr>
                    <td class="p-2 font-semibold capitalize">${dia}</td>

                    <td class="p-2 text-center">
                        <input type="checkbox"
                               name="dias[${i}][es_todo_el_dia]"
                               value="1"
                               class="todo-dia"
                               ${todoChecked}>
                        <input type="hidden"
                               name="dias[${i}][dia]"
                               value="${dia}">
                    </td>

                    <td class="p-2">
                        <input type="time"
                               name="dias[${i}][hora_entrada]"
                               value="${d.entrada || ''}"
                               class="w-full border rounded hora"
                               ${horasDisabled}>
                    </td>

                    <td class="p-2">
                        <input type="time"
                               name="dias[${i}][hora_salida]"
                               value="${d.salida || ''}"
                               class="w-full border rounded hora"
                               ${horasDisabled}>
                    </td>

                    <td class="p-2">
                        <input type="time"
                               name="dias[${i}][hora_llegada_trabajo]"
                               value="${d.llegada || ''}"
                               class="w-full border rounded hora"
                               ${horasDisabled}>
                    </td>

                    <td class="p-2">
                        <input type="text"
                               name="dias[${i}][observacion]"
                               value="${d.observacion || ''}"
                               class="w-full border rounded">
                    </td>
                </tr>
            `);
        });

        // 🔒 Bloquear horas si es todo el día
        diasBody.querySelectorAll('.todo-dia').forEach(chk => {
            const row = chk.closest('tr');

            const toggleHoras = () => {
                row.querySelectorAll('.hora').forEach(h => {
                    h.disabled = chk.checked;
                    if (chk.checked) h.value = '';
                });
            };

            chk.addEventListener('change', toggleHoras);
        });
    }

    tipoSelect?.addEventListener('change', toggleSections);
    
    // Inicializar al cargar la página
    document.addEventListener('DOMContentLoaded', () => {
        toggleSections();
    });
})();