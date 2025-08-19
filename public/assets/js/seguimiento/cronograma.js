const toISO = (d) => new Date(d).toISOString().slice(0, 10);

window.renderCronograma = function (el, idSeguimiento, opts = {}) {

      if (!idSeguimiento) {
        idSeguimiento = document.getElementById('idSeguimientoHidden')?.value;
    }

    if (!el || !idSeguimiento) {
        console.error('Elemento DOM e idSeguimiento son requeridos');
        return;
    }

    // CSRF Token para Laravel
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // URLs base
    const baseUrl = `/cronograma/${idSeguimiento}`;
    
    // ===== Locale ES =====
    if (gantt.i18n?.setLocale) gantt.i18n.setLocale('es');

    // ===== Config base =====
    gantt.config.autosize = false;
    gantt.config.fit_tasks = false;
    gantt.config.date_format = '%Y-%m-%d';
    gantt.config.readonly = false;

    // === Plugins ===
    gantt.plugins?.({ columnResize: true, inlineEditors: true, tooltip: true });
    gantt.config.tooltip_timeout = 20;

    // === Resize horizontal ===
    gantt.config.grid_resize = true;
    gantt.config.grid_resizable_columns = true;

    // === Progreso ===
    gantt.config.drag_progress = true;
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

    // ===== Layout =====
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

    // ===== Interacción nativa =====
    gantt.config.drag_create = true;
    gantt.config.details_on_create = true;
    gantt.config.details_on_dblclick = true;

    // ===== Columnas =====
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

    // ===== Lightbox =====
    gantt.config.lightbox.sections = [
        { name: 'description', height: 38, map_to: 'text', type: 'textarea', focus: true },
        { name: 'time', type: 'time', map_to: 'auto' },
        { name: 'type', type: 'typeselect', map_to: 'type' },
    ];
    gantt.locale.labels.section_description = 'Descripción';
    gantt.locale.labels.section_time = 'Tiempo';
    gantt.locale.labels.section_type = 'Tipo';

    // ===== Colores por % =====
    gantt.templates.task_class = function (s, e, t) {
        const p = t.progress || 0;
        let colorClass = '';
        if (p >= 0.8) colorClass = 'custom-green';
        else if (p >= 0.4) colorClass = 'custom-blue';
        else colorClass = 'custom-red';
        
        return (gantt.hasChild(t.id) ? 'is-parent ' : '') + colorClass;
    };

    // ===== Grid row class =====
    gantt.templates.grid_row_class = (start, end, task) =>
        gantt.hasChild(task.id) ? 'is-parent' : '';

    // ===== Tooltip =====
    const fmt = gantt.date.date_to_str('%d %M %Y');
    gantt.templates.tooltip_text = (start, end, task) => {
        const deps = gantt
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

    // ===== Escalas responsive =====
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

    // ===== FUNCIONES DE API =====
    
    // Función para hacer peticiones con CSRF
    async function apiRequest(url, options = {}) {
        const defaultOptions = {
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        };
        
        const response = await fetch(url, { ...defaultOptions, ...options });
        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.error || 'Error en la petición');
        }
        
        return data;
    }

    // Cargar datos desde Laravel
    async function loadData() {
        try {
            showLoading(true);
const idPersona = document.getElementById('idPersonaHidden')?.value;
const data = await apiRequest(`${baseUrl}/data?idpersona=${idPersona}`);
            
            // Convertir fechas de string a Date objects
            data.data.forEach(task => {
                if (task.start_date) task.start_date = gantt.date.parseDate(task.start_date, '%Y-%m-%d');
                if (task.end_date) task.end_date = gantt.date.parseDate(task.end_date, '%Y-%m-%d');
            });
            
            gantt.parse(data);
            
            // Aplicar configuración guardada
            if (data.config && data.config.vista) {
                const viewSelect = document.getElementById('cronograma_view');
                if (viewSelect) viewSelect.value = data.config.vista;
                applyView(data.config.vista);
            }
            
            showLoading(false);
            notify('Cronograma cargado correctamente', 'success');
            
        } catch (error) {
            showLoading(false);
            notify('Error al cargar cronograma: ' + error.message, 'error');
            console.error('Error loading data:', error);
        }
    }

    // Guardar tarea
async function saveTask(task, isNew = false) {
    try {
        console.log("Preparando datos para guardar tarea:", task);
        
         const taskData = {
            id: String(task.id), // Asegura que sea string
            text: task.text,
            start_date: gantt.date.date_to_str('%Y-%m-%d')(task.start_date),
            end_date: gantt.date.date_to_str('%Y-%m-%d')(task.end_date),
            progress: task.progress || 0,
            parent: String(task.parent || 0), // También convierte parent a string
            type: task.type || 'task'
        };

        console.log("Datos a enviar al servidor:", taskData);

        const url = isNew ? `${baseUrl}/task` : `${baseUrl}/task/${task.id}`;
        const method = isNew ? 'POST' : 'PUT';

        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(taskData)
        });

        console.log("Respuesta del servidor:", response);

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Error al guardar la tarea');
        }

        const data = await response.json();
        console.log("Tarea guardada exitosamente:", data);

        // Si es nueva y el servidor devuelve un ID diferente, actualizamos
        if (isNew && data.id && data.id !== task.id) {
            gantt.changeTaskId(task.id, data.id);
        }

        return true;
    } catch (error) {
        console.error("Error al guardar tarea:", error);
        notify('Error al guardar tarea: ' + error.message, 'error');
        return false;
    }
}
    // Eliminar tarea
    async function deleteTask(taskId) {
        try {
            await apiRequest(`${baseUrl}/task/${taskId}`, {
                method: 'DELETE'
            });
            return true;
        } catch (error) {
            notify('Error al eliminar tarea: ' + error.message, 'error');
            return false;
        }
    }

    // Guardar dependencia
// saveLink actualizada
async function saveLink(link) {
    try {
        if (!link?.id || !link?.source || !link?.target) {
            throw new Error("Datos de link incompletos");
        }

        if (await checkForCycles(link.source, link.target)) {
            throw new Error("Esta dependencia crearía un ciclo");
        }

        const response = await apiRequest(`${baseUrl}/link`, {
            method: 'POST',
            body: JSON.stringify({
                id: String(link.id),
                source: String(link.source),
                target: String(link.target),
                type: link.type || '0'
            })
        });

        return true;
    } catch (error) {
        console.error("Error saving link:", error);
        notify("Error al guardar dependencia: " + error.message, "error");
        return false;
    }
}

// Función para detectar ciclos
function createsCycle(source, target) {
    return new Promise((resolve) => {
        // Implementación simple - puedes mejorarla según tus necesidades
        const links = gantt.getLinks();
        const visited = new Set();
        
        function hasPath(start, end) {
            if (start === end) return true;
            if (visited.has(start)) return false;
            
            visited.add(start);
            
            const outgoingLinks = links.filter(l => l.source == start);
            for (const link of outgoingLinks) {
                if (hasPath(link.target, end)) {
                    return true;
                }
            }
            
            return false;
        }
        
        resolve(hasPath(target, source));
    });
}
    // Eliminar dependencia
    async function deleteLink(linkId) {
        try {
            await apiRequest(`${baseUrl}/link/${linkId}`, {
                method: 'DELETE'
            });
            return true;
        } catch (error) {
            notify('Error al eliminar dependencia: ' + error.message, 'error');
            return false;
        }
    }

    // Guardar configuración
    async function saveConfig(config) {
        try {
            await apiRequest(`${baseUrl}/config`, {
                method: 'POST',
                body: JSON.stringify(config)
            });
        } catch (error) {
            console.error('Error saving config:', error);
        }
    }

    // ===== EVENTOS DE DHTMLX GANTT CONECTADOS A LARAVEL =====
    
    // Cuando se crea una tarea
gantt.attachEvent("onAfterTaskAdd", async function(id, task) {
    console.log("Evento onAfterTaskAdd disparado", task);
    
    // Verificar si es una tarea nueva (temporal)
    const isNewTask = task.$new || 
                     (typeof task.id === 'string' && task.id.startsWith('T')) || 
                     (typeof task.id === 'number' && task.id > 1e12); // IDs temporales grandes
    
    if (!isNewTask) {
        console.log("No es tarea nueva, ignorando");
        return;
    }
    
    console.log("Es una tarea nueva, procediendo a guardar...");
    const success = await saveTask(task, true);
    
    if (success) {
        ensureVisibleByTask(task);
        notify(`Tarea creada: ${task.text}`, 'success');
    } else {
        console.log("Falló el guardado, eliminando tarea temporal");
        gantt.deleteTask(id);
    }
});

    // Cuando se actualiza una tarea
    gantt.attachEvent("onAfterTaskUpdate", async function(id, task) {
        if (task.$new) return; // Skip new tasks
        
        const success = await saveTask(task, false);
        if (success) {
            ensureVisibleByTask(task);
            notify(`Tarea actualizada: ${task.text}`, 'info');
        }
    });

    // Cuando se arrastra una tarea
    gantt.attachEvent("onAfterTaskDrag", async function(id) {
        const task = gantt.getTask(id);
        const success = await saveTask(task, false);
        if (success) {
            ensureVisibleByTask(task);
            notify(`Tarea editada: ${task.text}`, 'info');
        }
    });

    // Antes de eliminar tarea (para confirmación)
    gantt.attachEvent("onBeforeTaskDelete", function(id, task) {
        return confirm(`¿Estás seguro de eliminar la tarea "${task.text}"?`);
    });

    // Después de eliminar tarea
    gantt.attachEvent("onAfterTaskDelete", async function(id, task) {
        const success = await deleteTask(id);
        if (success) {
            notify(`Tarea eliminada: ${task?.text ?? id}`, 'warning');
        }
    });

// Modificar el evento onAfterLinkAdd
gantt.attachEvent("onAfterLinkAdd", async function(id) {
    try {
        const link = gantt.getLink(id);
        if (!link) {
            console.error("No se pudo encontrar el link con id:", id);
            gantt.deleteLink(id);
            return;
        }
        
        // Verificar ciclos antes de enviar al servidor
        const hasCycle = await checkForCycles(link.source, link.target);
        if (hasCycle) {
            gantt.deleteLink(id);
            notify("No se puede crear esta dependencia: crearía un ciclo", "error");
            return;
        }
        
        const success = await saveLink(link);
        if (!success) {
            gantt.deleteLink(id);
        }
    } catch (error) {
        console.error("Error en onAfterLinkAdd:", error);
        notify("Error al crear dependencia: " + error.message, "error");
        gantt.deleteLink(id);
    }
});

async function checkForCycles(source, target) {
    const links = gantt.getLinks();
    const visited = new Set();
    const queue = [target];
    
    while (queue.length > 0) {
        const current = queue.shift();
        
        if (current === source) {
            return true; // Hay un ciclo
        }
        
        if (!visited.has(current)) {
            visited.add(current);
            
            // Encontrar todos los links que salen de 'current'
            const outgoing = links.filter(l => l.source == current);
            outgoing.forEach(l => queue.push(l.target));
        }
    }
    
    return false; // No hay ciclo
}


    // Cuando se elimina una dependencia
    gantt.attachEvent("onAfterLinkDelete", async function(id, link) {
        await deleteLink(id);
    });

    // ===== INICIALIZACIÓN =====
    applyScales(el.clientWidth);
    gantt.init(el.id || el);

    // Cargar datos desde Laravel
    loadData();

    // ===== AUTO-FIT =====
    function fitToTasksOnce() {
        const prev = gantt.config.fit_tasks;
        gantt.config.fit_tasks = true;
        gantt.render();
        gantt.config.fit_tasks = prev;
    }

    let fittedOnce = false;
    gantt.attachEvent("onDataRender", function () {
        if (!fittedOnce) { 
            fitToTasksOnce(); 
            fittedOnce = true; 
        }
    });

    function ensureVisibleByTask(task) {
        const st = gantt.getState();
        if (task && (task.start_date < st.min_date || task.end_date > st.max_date)) {
            fitToTasksOnce();
        }
    }

    // ===== RESPONSIVE =====
    const ro = new ResizeObserver((entries) => {
        const w = entries[0].contentRect.width;
        applyScales(w);
        gantt.setSizes();
        gantt.render();
    });
    ro.observe(el);

    // ===== TOOLBAR FUNCTIONS =====
    function applyView(viewType) {
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
        gantt.config.scales = map[viewType] || map['Day'];
        gantt.render();
        fitToTasksOnce();
        
        // Guardar configuración
        saveConfig({ vista_actual: viewType });
    }

    // ===== EVENT LISTENERS PARA TOOLBAR =====
    const viewSel = document.getElementById('cronograma_view');
    const btnNew = document.getElementById('cronograma_new');
    const btnToday = document.getElementById('cronograma_today');
    const btnFit = document.getElementById('cronograma_fit');

    viewSel?.addEventListener('change', (e) => {
        applyView(e.target.value);
    });

    // En el evento de creación de tareas
btnNew?.addEventListener('click', () => {
    console.log("--- INICIANDO CREACIÓN DE TAREA ---");
    const parent = gantt.getSelectedId() || gantt.getRootId();
    const base = gantt.getState().min_date || new Date();
    const taskId = 'tarea_' + Date.now(); // Prefijo + timestamp
    
    console.log("ID temporal generado:", taskId);
    
    const newTask = {
        id: taskId,
        text: 'Nueva tarea',
        start_date: gantt.date.add(base, 0, 'day'),
        end_date: gantt.date.add(base, 2, 'day'),
        progress: 0,
        parent: parent,
        type: 'task',
        $new: true // Marca explícitamente como nueva
    };
    
    const id = gantt.addTask(newTask);
    gantt.showLightbox(id);
});

    btnToday?.addEventListener('click', () => gantt.showDate(new Date()));

    btnFit?.addEventListener('click', () => {
        fitToTasksOnce();
    });

    // ===== EXPORTES =====
    const btnExpPdf = document.getElementById('cronograma_export_pdf');
    const btnExpPng = document.getElementById('cronograma_export_png');
    const btnExpExcel = document.getElementById('cronograma_export_excel');
    const btnExpMsp = document.getElementById('cronograma_export_msp');

    function doExport(fn, cfg) {
        if (typeof fn !== 'function') {
            alert('Para exportar, incluye: https://export.dhtmlx.com/gantt/api.js');
            return;
        }
        fn.call(gantt, Object.assign({ locale: 'es' }, cfg));
    }

    btnExpPdf?.addEventListener('click', () => {
        doExport(gantt.exportToPDF, { name: `cronograma_${toISO(new Date())}.pdf` });
    });
    
    btnExpPng?.addEventListener('click', () => {
        doExport(gantt.exportToPNG, { name: `cronograma_${toISO(new Date())}.png` });
    });
    
    btnExpMsp?.addEventListener('click', () => {
        doExport(gantt.exportToMSProject, { name: `cronograma_${toISO(new Date())}.xml` });
    });

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
            { id: 'tarea', header: 'Tarea', align: 'center' },
            { id: 'inicio', header: 'Inicio', align: 'center' },
            { id: 'fin', header: 'Fin', align: 'center' },
            { id: 'duracion', header: 'Duración', align: 'center' },
            { id: 'pct', header: '%', align: 'center'},
        ];

        gantt.exportToExcel.call(gantt, {
            name: `cronograma_${toISO(new Date())}.xlsx`,
            columns,
            data: rows
        });
    });

    // ===== UTILIDADES =====
    
    // Loading indicator
    function showLoading(show) {
        // Puedes personalizar esto según tu UI
        if (show) {
            el.style.opacity = '0.5';
            el.style.pointerEvents = 'none';
        } else {
            el.style.opacity = '1';
            el.style.pointerEvents = 'auto';
        }
    }

    // ===== TOASTS (SweetAlert2) =====
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

    // ===== API PÚBLICA DEL CRONOGRAMA =====
    
    // Retornar objeto con métodos públicos
    return {
        reload: loadData,
        saveConfig: saveConfig,
        fitToTasks: fitToTasksOnce,
        gantt: gantt // Acceso directo al objeto gantt si se necesita
    };
};