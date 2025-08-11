// util
const toISO = (d) => new Date(d).toISOString().slice(0, 10);

// ===== render Gantt =====
window.renderCronograma = function (el, tasks, opts = {}) {
    const data = tasks || [
        { id: 'P1', name: 'Proyecto A', start: '2025-08-01', end: '2025-08-10', progress: 65, custom_class: 'custom-blue' },
        { id: 'T1', name: 'Levantamiento', start: '2025-08-01', end: '2025-08-03', progress: 100, dependencies: 'P1', custom_class: 'custom-green' },
        { id: 'T2', name: 'Desarrollo', start: '2025-08-04', end: '2025-08-08', progress: 50, dependencies: 'T1', custom_class: 'custom-blue' },
        { id: 'T3', name: 'QA', start: '2025-08-08', end: '2025-08-10', progress: 20, dependencies: 'T2', custom_class: 'custom-red' },
    ];
    if (!el) return;

    // --- Espera a que el contenedor sea visible (tab activo) ---
    const boot = () => {
        if (el.offsetWidth === 0) return requestAnimationFrame(boot);

        // ===== cache UI (AHORA sí existen en DOM) =====
        const viewSel = document.getElementById('cronograma_view');
        const btnNew = document.getElementById('cronograma_new');
        const btnToday = document.getElementById('cronograma_today');
        const btnFit = document.getElementById('cronograma_fit');
        const btnExport = document.getElementById('cronograma_export');

        const modal = document.getElementById('cronograma_modal');
        const panel = document.getElementById('cronograma_panel');
        const form = document.getElementById('cronograma_form');
        const titleEl = document.getElementById('cronograma_modal_title');
        const btnDel = document.getElementById('cronograma_delete');
        const btnCancel = document.getElementById('cronograma_cancel');
        const btnCancelTop = document.getElementById('cronograma_cancel_top');
        const backdrop = document.getElementById('cronograma_modal_backdrop');

        const f = {
            id: document.getElementById('t_id'),
            name: document.getElementById('t_name'),
            s: document.getElementById('t_start'),
            e: document.getElementById('t_end'),
            p: document.getElementById('t_progress'),
            d: document.getElementById('t_deps'),
            c: document.getElementById('t_color'),
        };

        // ===== modal helpers (animación CSS) =====
        function showModal() {
            if (!modal) return;
            modal.classList.remove('hidden');
            requestAnimationFrame(() => modal.classList.add('is-open'));
        }
        function hideModal() {
            if (!modal) return;
            modal.classList.remove('is-open');
            modal.addEventListener('transitionend', function onEnd(e) {
                if (e.target === modal && e.propertyName === 'opacity') {
                    modal.classList.add('hidden');
                    modal.removeEventListener('transitionend', onEnd);
                }
            });
        }
        function fillForm(task) {
            f.id.value = task?.id || '';
            f.name.value = task?.name || '';
            f.s.value = task ? toISO(task.start || task._start) : '';
            f.e.value = task ? toISO(task.end || task._end) : '';
            f.p.value = task?.progress ?? 0;
            f.d.value = task?.dependencies || '';
            f.c.value = task?.custom_class || '';
        }
        function openModal(task = null) {
            if (!titleEl || !btnDel) return;
            titleEl.textContent = task ? 'Editar tarea' : 'Nueva tarea';
            btnDel.classList.toggle('hidden', !task);
            fillForm(task);
            showModal();
        }
        const generateId = (arr) => {
            let i = arr.length + 1;
            while (arr.find((t) => t.id === 'T' + i)) i++;
            return 'T' + i;
        };

        // ===== Instancia Gantt =====
        const gantt = new Gantt(el, data, {
            view_mode: opts.view_mode || 'Day',
            date_format: 'YYYY-MM-DD',
            column_width: 38,
            bar_height: 24,
            bar_corner_radius: 4,
            padding: 18,
            header_height: 48,
            custom_popup_html: (t) => `
    <div style="
      padding: 10px 12px;
      min-width: 240px;
      background: linear-gradient(135deg, #ffffff, #f9fafb);
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.15);
      font-family: 'Nunito', sans-serif;
      color: #333;
    ">
      <div style="font-size: 15px; font-weight: 700; margin-bottom: 8px; color: #111;">
        ${t.name}
      </div>

      <div style="display:flex;align-items:center;font-size:13px;margin-bottom:4px;">
        📅 <span style="margin-left:4px;">${toISO(t._start)} → ${toISO(t._end)}</span>
      </div>

      <div style="display:flex;align-items:center;font-size:13px;margin-bottom:4px;">
        📈 <span style="margin-left:4px;">Progreso: 
          <span style="font-weight:600;color:${t.progress >= 100 ? '#16a34a' : '#2563eb'}">
            ${t.progress}%
          </span>
        </span>
      </div>

      ${
          t.dependencies
              ? `
        <div style="display:flex;align-items:center;font-size:13px;margin-bottom:4px;">
          🔗 <span style="margin-left:4px;">Depende de: ${t.dependencies}</span>
        </div>
      `
              : ''
      }

      <div style="font-size:12px;opacity:0.6;margin-top:6px;">ID: ${t.id}</div>
      <div style="margin-top:8px;font-size:12px;color:#6b7280;font-style:italic;">
        Haz clic para editar
      </div>
    </div>
  `,
            on_click: (task) => openModal(task),
            on_date_change: (task, start, end) => {
                task.start = toISO(start);
                task.end = toISO(end);
                opts.onDateChange && opts.onDateChange(task, start, end);
            },
            on_progress_change: (task, progress) => {
                task.progress = progress;
                opts.onProgressChange && opts.onProgressChange(task, progress);
            },
        });

        // ===== Controles =====
        viewSel?.addEventListener('change', (e) => gantt.change_view_mode(e.target.value));

        const scrollToDate = (date) => {
            const start = gantt.gantt_start;
            const diffDays = Math.floor((date - start) / (24 * 60 * 60 * 1000));
            const target = Math.max(0, diffDays * gantt.options.column_width - el.clientWidth / 2);
            el.scrollLeft = target;
        };

        // muy importante: este listener ya existe porque btnNew NO es null ahora
        btnNew?.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            openModal(null);
        });

        btnToday?.addEventListener('click', () => scrollToDate(new Date()));
        btnFit?.addEventListener('click', () => (el.scrollLeft = 0));
        btnExport?.addEventListener('click', () => {
            const svg = el.querySelector('svg');
            if (!svg) return;
            const ser = new XMLSerializer().serializeToString(svg);
            const blob = new Blob([ser], { type: 'image/svg+xml;charset=utf-8' });
            const a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = `cronograma_${toISO(new Date())}.svg`;
            a.click();
            URL.revokeObjectURL(a.href);
        });

        // ===== Acciones modal =====
        btnCancel?.addEventListener('click', hideModal);
        btnCancelTop?.addEventListener('click', hideModal);
        backdrop?.addEventListener('click', (e) => {
            if (e.target === backdrop) hideModal();
        });

        btnDel?.addEventListener('click', () => {
            const id = f.id.value;
            if (!id) return;
            const next = gantt.tasks.filter((t) => t.id !== id);
            gantt.refresh(next);
            hideModal();
        });

        form?.addEventListener('submit', (e) => {
            e.preventDefault();
            const id = f.id.value || generateId(gantt.tasks);
            const task = {
                id,
                name: f.name.value.trim(),
                start: f.s.value,
                end: f.e.value,
                progress: Math.max(0, Math.min(100, Number(f.p.value || 0))),
                dependencies: f.d.value.replace(/\s+/g, '') || undefined,
                custom_class: f.c.value || undefined,
            };
            if (!task.name || !task.start || !task.end) return;

            const exists = gantt.tasks.some((t) => t.id === id);
            const next = exists ? gantt.tasks.map((t) => (t.id === id ? { ...t, ...task } : t)) : [...gantt.tasks, task];

            gantt.refresh(next);
            hideModal();
        });

        // API pública opcional
        el._cronograma = {
            instance: gantt,
            setView: (mode) => gantt.change_view_mode(mode),
            refresh: (newTasks) => gantt.refresh(newTasks),
            addTask: (task) => gantt.refresh([...gantt.tasks, task]),
            openNew: () => openModal(null),
            openEditById: (id) => {
                const t = gantt.tasks.find((x) => x.id === id);
                if (t) openModal(t);
            },
        };
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                hideModal();
            }
        });
    };

    boot();
};
