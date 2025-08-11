// util
const toISO = (d) => new Date(d).toISOString().slice(0, 10);

window.renderCronograma = function (el, tasks, opts = {}) {
    const data = tasks || [
        { id: 'P1', name: 'Proyecto A', start: '2025-08-01', end: '2025-08-10', progress: 65, custom_class: 'custom-blue' },
        { id: 'T1', name: 'Levantamiento', start: '2025-08-01', end: '2025-08-03', progress: 100, dependencies: 'P1', custom_class: 'custom-green' },
        { id: 'T2', name: 'Desarrollo', start: '2025-08-04', end: '2025-08-08', progress: 50, dependencies: 'T1', custom_class: 'custom-blue' },
        { id: 'T3', name: 'QA', start: '2025-08-08', end: '2025-08-10', progress: 20, dependencies: 'T2', custom_class: 'custom-red' },
    ];
    if (!el) return;
    // Locale ES y ajustes generales (ANTES de configurar/initializar)
    if (gantt.i18n && gantt.i18n.setLocale) {
        gantt.i18n.setLocale('es');
    }
    // ---------- Config base ----------
    gantt.config.date_format = '%Y-%m-%d';
    gantt.config.readonly = false;

    // 🔸 Importante para scroll responsive:
    gantt.config.autosize = false; // no “estirar” al contenido
    gantt.config.fit_tasks = false;

    // Layout con scrollbars sincronizados (grid izquierda + timeline derecha)
    gantt.config.layout = {
        css: 'gantt_container',
        rows: [
            {
                cols: [
                    { view: 'grid', width: 300, scrollY: 'scrollVer' },
                    { resizer: true, width: 1 },
                    { view: 'timeline', scrollX: 'scrollHor', scrollY: 'scrollVer' },
                    { view: 'scrollbar', id: 'scrollVer' },
                ],
            },
            { view: 'scrollbar', id: 'scrollHor', height: 18 },
        ],
    };

    // Columnas del grid
    gantt.config.columns = [
        { name: 'text', label: 'Tareas', tree: true, width: '*' },
        { name: 'start_date', label: 'Inicio', align: 'center', width: 100 },
        { name: 'end_date', label: 'Fin', align: 'center', width: 100 },
    ];

    // Colores por clase
    // Colores automáticos según progreso
    gantt.templates.task_class = function (s, e, t) {
        if (t.progress >= 0.8) {
            return 'custom-green'; // 80-100%
        } else if (t.progress >= 0.4) {
            return 'custom-blue'; // 40-79%
        } else {
            return 'custom-red'; // 0-39%
        }
    };
    // Quitamos el % de dentro de la barra
    gantt.templates.progress_text = () => '';

    // Mostramos el % a la derecha
    gantt.templates.rightside_text = (start, end, task) => {
        const porcentaje = Math.round((task.progress || 0) * 100) + '%';
        const pos = gantt.getTaskPosition(task);
        return pos.width < 80 ? '' : porcentaje; // evita mostrarlo si la barra es muy pequeña
    };
    // ---------- Escalas responsive ----------
    const applyScales = (w) => {
        if (w < 640) {
            // móvil: semanas arriba, días abajo (texto corto)
            gantt.config.scale_height = 48;
            gantt.config.scales = [
                { unit: 'week', step: 1, format: 'Sem %W' },
                { unit: 'day', step: 1, format: '%D' },
            ];
            gantt.config.grid_width = 220;
            gantt.config.row_height = 34;
        } else if (w < 1024) {
            // tablet: días arriba, horas abajo (cada 6h)
            gantt.config.scale_height = 50;
            gantt.config.scales = [
                { unit: 'day', step: 1, format: '%D %d %M' },
                { unit: 'hour', step: 6, format: '%H' },
            ];
            gantt.config.grid_width = 280;
            gantt.config.row_height = 36;
        } else {
            // desktop: semanas + días con formato largo
            gantt.config.scale_height = 52;
            gantt.config.scales = [
                { unit: 'week', step: 1, format: (d) => gantt.date.date_to_str('%D %d %M')(d).toUpperCase() },
                { unit: 'day', step: 1, format: '%D' },
            ];
            gantt.config.grid_width = 320;
            gantt.config.row_height = 38;
        }
    };

    // ---------- Render ----------
    applyScales(el.clientWidth);
    gantt.init(el.id || el);
    gantt.parse({
        data: data.map((t) => ({
            id: t.id,
            text: t.name,
            start_date: gantt.date.parseDate(t.start, '%Y-%m-%d'),
            end_date: gantt.date.parseDate(t.end, '%Y-%m-%d'),
            progress: Math.max(0, Math.min(100, Number(t.progress || 0))) / 100,
            custom_class: t.custom_class || '',
        })),
        links: (() => {
            const links = [];
            data.forEach((t) => {
                (t.dependencies || '')
                    .split(',')
                    .map((s) => s.trim())
                    .filter(Boolean)
                    .forEach((dep) => links.push({ id: `${dep}->${t.id}`, source: dep, target: t.id, type: '0' }));
            });
            return links;
        })(),
    });

    // ---------- Controles ----------
    const viewSel = document.getElementById('cronograma_view');
    const btnNew = document.getElementById('cronograma_new');
    const btnToday = document.getElementById('cronograma_today');
    const btnFit = document.getElementById('cronograma_fit');
    const btnExport = document.getElementById('cronograma_export');

    viewSel?.addEventListener('change', (e) => {
        const v = e.target.value;
        const map = {
            'Quarter Day': [
                { unit: 'hour', step: 6, format: '%H' },
                { unit: 'day', step: 1, format: '%D %d %M' },
            ],
            'Half Day': [
                { unit: 'hour', step: 12, format: '%H' },
                { unit: 'day', step: 1, format: '%D %d %M' },
            ],
            Day: [
                { unit: 'day', step: 1, format: '%D %d %M' },
                { unit: 'week', step: 1, format: 'Sem %W' },
            ],
            Week: [
                { unit: 'week', step: 1, format: 'Sem %W' },
                { unit: 'month', step: 1, format: '%M %Y' },
            ],
            Month: [
                { unit: 'month', step: 1, format: '%M %Y' },
                { unit: 'year', step: 1, format: '%Y' },
            ],
        };
        gantt.config.scales = map[v] || map['Day'];
        gantt.render();
    });

    btnNew?.addEventListener('click', () => openModal(null));
    btnToday?.addEventListener('click', () => gantt.showDate(new Date()));
    btnFit?.addEventListener('click', () => gantt.render()); // con layout de scroll no “fit”, solo re-render
    btnExport?.addEventListener('click', () => gantt.exportToPNG());

    // ---------- Modal (recicla tu código actual) ----------
    const modal = document.getElementById('cronograma_modal');
    const title = document.getElementById('cronograma_modal_title');
    const f = {
        id: document.getElementById('t_id'),
        name: document.getElementById('t_name'),
        s: document.getElementById('t_start'),
        e: document.getElementById('t_end'),
        p: document.getElementById('t_progress'),
        d: document.getElementById('t_deps'),
        c: document.getElementById('t_color'),
    };
    const btnDel = document.getElementById('cronograma_delete');
    const btnCancel = document.getElementById('cronograma_cancel');
    const btnCancelTop = document.getElementById('cronograma_cancel_top');
    const form = document.getElementById('cronograma_form');

    const show = () => {
        modal.classList.remove('hidden');
        requestAnimationFrame(() => modal.classList.add('is-open'));
    };
    const hide = () => {
        modal.classList.remove('is-open');
        modal.addEventListener('transitionend', function done(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
                modal.removeEventListener('transitionend', done);
            }
        });
    };

    const fillForm = (t) => {
        f.id.value = t?.id || '';
        f.name.value = t?.text || '';
        f.s.value = t ? toISO(t.start_date) : '';
        f.e.value = t ? toISO(t.end_date) : '';
        f.p.value = t ? Math.round((t.progress || 0) * 100) : 0;
        if (t) {
            const incoming = gantt
                .getLinks()
                .filter((l) => l.target == t.id)
                .map((l) => l.source);
            f.d.value = incoming.join(',');
        } else f.d.value = '';
        f.c.value = t?.custom_class || '';
    };
    const openModal = (t = null) => {
        title.textContent = t ? 'Editar tarea' : 'Nueva tarea';
        btnDel.classList.toggle('hidden', !t);
        fillForm(t);
        show();
    };

    gantt.attachEvent('onTaskClick', function (id) {
        openModal(gantt.getTask(id));
        return false;
    });
    btnCancel?.addEventListener('click', hide);
    btnCancelTop?.addEventListener('click', hide);
    btnDel?.addEventListener('click', () => {
        const id = f.id.value;
        if (!id) return;
        gantt.getLinks().forEach((l) => {
            if (l.source == id || l.target == id) gantt.deleteLink(l.id);
        });
        gantt.deleteTask(id);
        hide();
    });
    form?.addEventListener('submit', (e) => {
        e.preventDefault();

        const id = f.id.value || 'T' + (Date.now() % 100000);

        // convierte strings "YYYY-MM-DD" a Date
        const start = gantt.date.parseDate(f.s.value, '%Y-%m-%d');
        const end = gantt.date.parseDate(f.e.value, '%Y-%m-%d');
        if (!start || !end || isNaN(+start) || isNaN(+end)) return; // fechas inválidas

        const text = f.name.value.trim();
        const progress = Math.max(0, Math.min(100, Number(f.p.value || 0))) / 100;
        const custom_class = f.c.value || '';

        if (gantt.isTaskExists(id)) {
            const t = gantt.getTask(id);
            t.text = text;
            t.start_date = start;
            t.end_date = end;
            t.progress = progress;
            t.custom_class = custom_class;
            gantt.updateTask(id);

            // limpia dependencias entrantes
            gantt.getLinks().forEach((l) => {
                if (l.target == id) gantt.deleteLink(l.id);
            });
        } else {
            gantt.addTask({
                id,
                text,
                start_date: start,
                end_date: end,
                progress,
                custom_class,
            });
        }

        // recrea dependencias
        (f.d.value || '')
            .split(',')
            .map((s) => s.trim())
            .filter(Boolean)
            .forEach((dep) => {
                if (gantt.isTaskExists(dep)) gantt.addLink({ source: dep, target: id, type: '0' });
            });

        hide();
    });

    // ---------- Responsive en vivo ----------
    const ro = new ResizeObserver((entries) => {
        const w = entries[0].contentRect.width;
        applyScales(w);
        gantt.render();
    });
    ro.observe(el);
};
// ---------- Controles ----------
const btnExport = document.getElementById('cronograma_export');

// Click normal => PNG ; Shift+Click => Excel
btnExport?.addEventListener('click', (ev) => {
    if (ev.shiftKey) {
        gantt.exportToExcel({
            name: `cronograma_${toISO(new Date())}.xlsx`,
            locale: 'es',
            columns: [
                { id: 'text', header: 'Tarea' },
                { id: 'start_date', header: 'Inicio' },
                { id: 'end_date', header: 'Fin' },
                { id: 'progress', header: '%', template: (t) => Math.round((t.progress || 0) * 100) },
            ],
            // server: 'https://export.dhtmlx.com/gantt' // por defecto
        });
    } else {
        gantt.exportToPNG({
            name: `cronograma_${toISO(new Date())}.png`,
            locale: 'es',
        });
    }
});
