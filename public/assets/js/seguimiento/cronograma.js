// util
const toISO = (d) => new Date(d).toISOString().slice(0, 10);

window.renderCronograma = function (el, tasks, opts = {}) {
    const data = tasks || [
        { id: 'P1', name: 'Proyecto A', start: '2025-08-01', end: '2025-08-10', progress: 65, type: 'project', open: true },
        { id: 'T1', name: 'Levantamiento', start: '2025-08-01', end: '2025-08-03', progress: 100, dependencies: 'P1', parent: 'P1' },
        { id: 'T2', name: 'Desarrollo', start: '2025-08-04', end: '2025-08-08', progress: 50, dependencies: 'T1', parent: 'P1' },
        { id: 'T3', name: 'QA', start: '2025-08-08', end: '2025-08-10', progress: 20, dependencies: 'T2', parent: 'P1' },
    ];
    if (!el) return;

    // ===== Locale ES =====
    if (gantt.i18n?.setLocale) gantt.i18n.setLocale('es');

    // ===== Config base =====
    gantt.config.autosize = false;
    gantt.config.fit_tasks = false;
    gantt.config.date_format = '%Y-%m-%d';
    gantt.config.readonly = false;

    // === Plugins (column resize + inline editors + tooltip) ===
    gantt.plugins?.({ columnResize: true, inlineEditors: true, tooltip: true });
    gantt.config.tooltip_timeout = 20;

    // === Resize horizontal (splitter + columnas) ===
    gantt.config.grid_resize = true;
    gantt.config.grid_resizable_columns = true;

    // === Progreso: mostrar + editar ===
    gantt.config.drag_progress = true; // arrastrar dentro de la barra
    gantt.templates.progress_text = (s, e, task) => Math.round((task.progress || 0) * 100) + '%';

    // Editor 0–100 para mapear a progress 0–1
    gantt.config.editor_types.progress_pct = gantt.mixin(
        {
            set_value: function (value, id, column, node) {
                const base = gantt.config.editor_types.number;
                return base.set_value.call(this, Math.round((value || 0) * 100), id, column, node);
            },
            get_value: function (id, column, node) {
                const base = gantt.config.editor_types.number;
                const v = parseFloat(base.get_value.call(this, id, column, node)) || 0;
                return Math.max(0, Math.min(100, v)) / 100;
            },
        },
        gantt.config.editor_types.number,
    );

    // ===== Layout con splitter horizontal =====
    gantt.config.layout = {
        css: 'gantt_container',
        rows: [
            {
                cols: [
                    { view: 'grid', width: 360, min_width: 220, scrollY: 'scrollVer' },
                    { resizer: true, width: 8 },
                    { view: 'timeline', scrollX: 'scrollHor', scrollY: 'scrollVer' },
                    { view: 'scrollbar', id: 'scrollVer' },
                ],
            },
            { view: 'scrollbar', id: 'scrollHor', height: 18 },
        ],
    };

    // ===== interacción nativa =====
    gantt.config.drag_create = true;
    gantt.config.details_on_create = true;
    gantt.config.details_on_dblclick = true;

    // ===== columnas =====
    const d2s = gantt.date.date_to_str('%Y-%m-%d');
    function durationDays(t) {
        const d = gantt.calculateDuration(t.start_date, t.end_date, 'day');
        return Math.round(d * 10) / 10 + 'd';
    }
    function predecessors(t) {
        return gantt
            .getLinks()
            .filter((l) => l.target == t.id)
            .map((l) => l.source)
            .join(',');
    }

    const progressEditor = { type: 'progress_pct', map_to: 'progress', min: 0, max: 100 };

    gantt.config.columns = [
        { name: 'text', label: 'Tareas', tree: true, width: 260, min_width: 180, resize: true, editor: { type: 'text', map_to: 'text' } },
        {
            name: 'progress',
            label: '%',
            align: 'center',
            width: 70,
            min_width: 60,
            resize: true,
            editor: progressEditor,
            template: (t) => Math.round((t.progress || 0) * 100) + '%',
        },
        {
            name: 'start_date',
            label: 'Inicio',
            align: 'center',
            width: 120,
            min_width: 100,
            resize: true,
            editor: { type: 'date', map_to: 'start_date' },
            template: (t) => d2s(t.start_date),
        },
        {
            name: 'end_date',
            label: 'Fin',
            align: 'center',
            width: 120,
            min_width: 100,
            resize: true,
            editor: { type: 'date', map_to: 'end_date' },
            template: (t) => d2s(t.end_date),
        },
        {
            name: 'duration',
            label: 'Duración',
            align: 'center',
            width: 90,
            min_width: 80,
            resize: true,
            editor: { type: 'number', map_to: 'duration', min: 0 },
            template: durationDays,
        },
        { name: 'pred', label: 'Pred.', align: 'center', width: 80, min_width: 70, resize: true, template: predecessors },
        { name: 'add', width: 44 },
    ];

    // ===== lightbox =====
    gantt.config.lightbox.sections = [
        { name: 'description', height: 38, map_to: 'text', type: 'textarea', focus: true },
        { name: 'time', type: 'time', map_to: 'auto' },
    ];
    gantt.locale.labels.section_description = 'Descripción';
    gantt.locale.labels.section_time = 'Tiempo';

    // ===== colores por % =====
    gantt.templates.task_class = function (s, e, t) {
        const p = t.progress || 0;
        if (p >= 0.8) return 'custom-green';
        if (p >= 0.4) return 'custom-blue';
        return 'custom-red';
    };

    // ===== Tooltip =====
    const fmt = gantt.date.date_to_str('%d %M %Y');
    gantt.templates.tooltip_text = (start, end, task) => {
        const deps =
            gantt
                .getLinks()
                .filter((l) => l.target == task.id)
                .map((l) => l.source)
                .join(', ') || '—';
        const dur = gantt.calculateDuration(task.start_date, task.end_date, 'day');
        const pct = Math.round((task.progress || 0) * 100);
        return `
      <div style="min-width:220px">
        <div><b>${task.text}</b></div>
        <div>Inicio: ${fmt(start)}</div>
        <div>Fin: ${fmt(end)}</div>
        <div>Duración: ${dur}d</div>
        <div>Avance: ${pct}%</div>
        <div>Predecesoras: ${deps}</div>
      </div>`;
    };

    // ===== escalas responsive =====
    const applyScales = (w) => {
        if (w < 640) {
            gantt.config.scale_height = 48;
            gantt.config.scales = [
                { unit: 'week', step: 1, format: 'Sem %W' },
                { unit: 'day', step: 1, format: '%D' },
            ];
            gantt.config.row_height = 34;
        } else if (w < 1024) {
            gantt.config.scale_height = 50;
            gantt.config.scales = [
                { unit: 'day', step: 1, format: '%D %d %M' },
                { unit: 'hour', step: 6, format: '%H' },
            ];
            gantt.config.row_height = 36;
        } else {
            gantt.config.scale_height = 52;
            gantt.config.scales = [
                { unit: 'week', step: 1, format: (d) => gantt.date.date_to_str('%D %d %M')(d).toUpperCase() },
                { unit: 'day', step: 1, format: '%D' },
            ];
            gantt.config.row_height = 38;
        }
    };

    // ===== render =====
    applyScales(el.clientWidth);
    gantt.init(el.id || el);

    // Normaliza tus progress (65 => 0.65) y mapea parent/type
    gantt.parse({
        data: data.map((t) => ({
            id: t.id,
            text: t.name,
            start_date: gantt.date.parseDate(t.start, '%Y-%m-%d'),
            end_date: gantt.date.parseDate(t.end, '%Y-%m-%d'),
            progress: Math.max(0, Math.min(100, Number(t.progress || 0))) / 100,
            parent: t.parent || 0,
            type: t.type || 'task',
            open: t.open !== false
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

    // === >>> AÑADIDO: marcar filas padre en el GRID <<< ===
    gantt.templates.grid_row_class = (start, end, task) =>
        gantt.hasChild(task.id) ? 'is-parent' : '';

    // === >>> AÑADIDO: conservar tu task_class y sumar 'is-parent' a la barra <<< ===
    const __oldTaskClass = gantt.templxates.task_class;
    gantt.templates.task_class = function (s, e, t) {
        const base = typeof __oldTaskClass === 'function' ? __oldTaskClass(s, e, t) : '';
        return (gantt.hasChild(t.id) ? 'is-parent ' : '') + base;
    };
    // === /AÑADIDOS ===

    // === AUTO-FIT === (añadido, no quita nada)
    function fitToTasksOnce() {
        const prev = gantt.config.fit_tasks;
        gantt.config.fit_tasks = true;
        gantt.render();
        gantt.config.fit_tasks = prev;
    }
    let fittedOnce = false;
    gantt.attachEvent("onDataRender", function () {
        if (!fittedOnce) { fitToTasksOnce(); fittedOnce = true; }
    });
    function ensureVisibleByTask(task) {
        const st = gantt.getState();
        if (task && (task.start_date < st.min_date || task.end_date > st.max_date)) {
            fitToTasksOnce();
        }
    }
    gantt.attachEvent("onAfterTaskAdd",    (id, t) => ensureVisibleByTask(t));
    gantt.attachEvent("onAfterTaskUpdate", (id, t) => ensureVisibleByTask(t));
    gantt.attachEvent("onAfterTaskDrag",   (id)   => ensureVisibleByTask(gantt.getTask(id)));
    // === /AUTO-FIT ===

    // ===== responsive =====
    const ro = new ResizeObserver((entries) => {
        const w = entries[0].contentRect.width;
        applyScales(w);
        gantt.setSizes();
        gantt.render();
    });
    ro.observe(el);

    // ===== toolbar =====
    const viewSel   = document.getElementById('cronograma_view');
    const btnNew    = document.getElementById('cronograma_new');
    const btnToday  = document.getElementById('cronograma_today');
    const btnFit    = document.getElementById('cronograma_fit');
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
        fitToTasksOnce(); // auto-ajusta al cambiar vista
    });

    btnNew?.addEventListener('click', () => {
        const parent = gantt.getSelectedId() || gantt.getRootId();
        const base = gantt.getState().min_date || new Date();
        const id = gantt.addTask(
            {
                text: 'Nueva tarea',
                start_date: gantt.date.add(base, 0, 'day'),
                end_date: gantt.date.add(base, 2, 'day'),
                progress: 0,
            },
            parent,
        );
        gantt.showLightbox(id);
    });

    btnToday?.addEventListener('click', () => gantt.showDate(new Date()));

    btnFit?.addEventListener('click', () => {
        const prev = gantt.config.fit_tasks;
        gantt.config.fit_tasks = true;
        gantt.render();
        setTimeout(() => {
            gantt.config.fit_tasks = prev;
        }, 0);
    });

    // ======== EXPORTES (debes tener los botones en el HTML) ========
    const btnExpPdf   = document.getElementById('cronograma_export_pdf');
    const btnExpPng   = document.getElementById('cronograma_export_png');
    const btnExpExcel = document.getElementById('cronograma_export_excel');
    const btnExpMsp   = document.getElementById('cronograma_export_msp');

    function doExport(fn, cfg) {
      if (typeof fn !== 'function') {
        alert('Para exportar, incluye: https://export.dhtmlx.com/gantt/api.js');
        return;
      }
      fn.call(gantt, Object.assign({ locale: 'es' }, cfg)); // asegura this === gantt
    }

    // PDF / PNG / MS Project
    btnExpPdf?.addEventListener('click', () => {
      doExport(gantt.exportToPDF,   { name: `cronograma_${toISO(new Date())}.pdf` });
    });
    btnExpPng?.addEventListener('click', () => {
      doExport(gantt.exportToPNG,   { name: `cronograma_${toISO(new Date())}.png` });
    });
    btnExpMsp?.addEventListener('click', () => {
      doExport(gantt.exportToMSProject, { name: `cronograma_${toISO(new Date())}.xml` });
    });

    // EXCEL: dataset propio (fechas formateadas, duración y % como texto) + centrado
    btnExpExcel?.addEventListener('click', () => {
      if (typeof gantt.exportToExcel !== 'function') {
        alert('Para exportar, incluye: https://export.dhtmlx.com/gantt/api.js');
        return;
      }

      const d2s = gantt.date.date_to_str('%Y-%m-%d');
      const rows = [];
      gantt.eachTask((t) => {
        const dur = Math.round(gantt.calculateDuration(t.start_date, t.end_date, 'day')) + 'd';
        const pct = Math.round((t.progress || 0) * 100) + '%';
        rows.push({
          tarea: t.text,
          inicio: d2s(t.start_date),
          fin: d2s(t.end_date),
          duracion: dur,
          pct: pct
        });
      });

      const columns = [
        { id: 'tarea',    header: 'Tarea',    align: 'center' },
        { id: 'inicio',   header: 'Inicio',   align: 'center' },
        { id: 'fin',      header: 'Fin',      align: 'center' },
        { id: 'duracion', header: 'Duración', align: 'center'  },
        { id: 'pct',      header: '%',        align: 'center'},
      ];

      gantt.exportToExcel.call(gantt, {
        name: `cronograma_${toISO(new Date())}.xlsx`,
        columns,
        data: rows
      });
    });
    // ======== /EXPORTES ========


    // ========== TOASTS (SweetAlert2) ==========
    const Toast = window.Swal?.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 2600,
      timerProgressBar: true,
      didOpen: (el) => {
        el.addEventListener('mouseenter', Swal.stopTimer);
        el.addEventListener('mouseleave', Swal.resumeTimer);
      }
    });

    const iconMap = { success: 'success', info: 'info', warning: 'warning', error: 'error' };
    function notify(msg, type = 'info') {
      if (Toast) {
        Toast.fire({ icon: iconMap[type] || 'info', title: msg });
      } else if (typeof gantt.message === 'function') {
        gantt.message({ text: msg, type });
      } else {
        console.log(`[${type}] ${msg}`);
      }
    }

    // Hookear eventos sin eliminar tu lógica:
    gantt.attachEvent("onAfterTaskAdd",    (id, t) => { ensureVisibleByTask(t);               notify(`Tarea creada: ${t.text}`, 'success'); });
    gantt.attachEvent("onAfterTaskUpdate", (id, t) => { ensureVisibleByTask(t);               notify(`Tarea actualizada: ${t.text}`, 'info');   });
    gantt.attachEvent("onAfterTaskDrag",   (id)    => { const t = gantt.getTask(id);          ensureVisibleByTask(t); notify(`Tarea editada: ${t.text}`, 'info'); });
    gantt.attachEvent("onAfterTaskDelete", (id, t) => {                                       notify(`Tarea eliminada: ${t?.text ?? id}`, 'warning'); });
};
