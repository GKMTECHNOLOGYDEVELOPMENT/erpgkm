(function () {
    const tipoSelect = document.getElementById('tipoSolicitud');
    const boxEducativo = document.getElementById('boxEducativo');
    const boxTipoEducacion = document.getElementById('boxTipoEducacion');
    const boxLicencia = document.getElementById('boxLicenciaMedica');
    const diasBody = document.getElementById('diasEducativoBody');

    const diasSemana = ['lunes','martes','miercoles','jueves','viernes','sabado'];

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
                               class="w-full border rounded hora">
                    </td>

                    <td class="p-2">
                        <input type="time"
                               name="dias[${i}][hora_salida]"
                               class="w-full border rounded hora">
                    </td>

                    <td class="p-2">
                        <input type="time"
                               name="dias[${i}][hora_llegada_trabajo]"
                               class="w-full border rounded hora">
                    </td>

                    <td class="p-2">
                        <input type="text"
                               name="dias[${i}][observacion]"
                               class="w-full border rounded">
                    </td>
                </tr>
            `);
        });

        diasBody.querySelectorAll('.todo-dia').forEach(chk => {
            chk.addEventListener('change', e => {
                const row = e.target.closest('tr');
                row.querySelectorAll('.hora').forEach(h => {
                    h.disabled = e.target.checked;
                    if (e.target.checked) h.value = '';
                });
            });
        });
    }

    tipoSelect?.addEventListener('change', toggleSections);
})();
