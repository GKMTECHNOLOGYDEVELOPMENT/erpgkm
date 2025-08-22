<!-- Toolbar -->
<style>
    /* Backdrop */
    #cronograma_modal {
        opacity: 0;
        transition: opacity .25s ease;
    }

    #cronograma_modal.is-open {
        opacity: 1;
    }

    /* Panel (cajita) */
    #cronograma_panel {
        transform: translateY(20px) scale(.98);
        opacity: 0;
        transition: transform .25s ease, opacity .25s ease;
    }

    #cronograma_modal.is-open #cronograma_panel {
        transform: translateY(0) scale(1);
        opacity: 1;
    }

    #gantt_cronograma {
        width: 100%;
        height: 70vh;
        /* alto inicial */
        min-height: 360px;
        /* evita quedar muy chico */
        max-height: 90vh;
        resize: vertical;
        /* ⬆️⬇️ para arrastrar el borde */
        overflow: auto;
        /* para que el resize funcione */
    }

    #gantt_wrap {
        width: 100%;
        height: 70vh;
        /* alto inicial */
        min-height: 360px;
        max-height: 90vh;
    }

    #gantt_cronograma {
        width: 100%;
        height: 100%;
        overflow: auto;
        /* scroll interno */
        resize: none !important;
        /* 🔒 desactiva cualquier resize del navegador */
    }

    #gantt_resize {
        /* opcional: marca visual del tirador */
        background: transparent;
        /* cámbialo a #e5e7eb si quieres verlo */
    }


    /* Estilo del “hoy” si llegas a usar markers más adelante */
    .gantt_marker.today .gantt_marker_content {
        background: #ef4444;
        color: #fff;
        padding: 2px 6px;
        border-radius: 4px;
    }

    /* Colores de barras (si usas custom_class) */
    .gantt_task_line.custom-blue {
        background: #4f66ff;
        border-color: #4f66ff;
    }

    .gantt_task_line.custom-green {
        background: #22c55e;
        border-color: #22c55e;
    }

    .gantt_task_line.custom-red {
        background: #ef4444;
        border-color: #ef4444;
    }

    .gantt_task_progress.custom-blue {
        background: #3340b3;
    }

    .gantt_task_progress.custom-green {
        background: #1a9e4b;
    }

    .gantt_task_progress.custom-red {
        background: #c63b3b;
    }
</style>

<div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div class="text-lg font-semibold">Cronograma</div>

    <!-- Controles: ahora con wrap y sin scroll -->
    <div class="flex flex-wrap items-center gap-2">
        <select id="cronograma_view" class="form-select text-white-dark w-full sm:w-auto">
            <option value="Day" selected>Día</option>
            <option value="Week">Semana</option>
            <option value="Month">Mes</option>
        </select>
        <button id="cronograma_today" type="button" class="btn btn-outline-primary w-full sm:w-auto">Hoy</button>
        <button id="cronograma_export_pdf"
            class="btn btn-outline-danger inline-flex shrink-0 whitespace-nowrap">PDF</button>
        <button id="cronograma_export_png"
            class="btn btn-outline-info inline-flex shrink-0 whitespace-nowrap">PNG</button>
        <button id="cronograma_export_excel"
            class="btn btn-outline-success inline-flex shrink-0 whitespace-nowrap">EXCEL</button>

    </div>


</div>

<!-- Wrapper con tirador -->
<div id="gantt_wrap" class="relative">
    <div id="gantt_cronograma"></div>
    <div id="gantt_resize" class="absolute left-0 right-0 bottom-0 h-2 cursor-ns-resize"></div>
</div>


<input type="hidden" id="idSeguimientoHidden" value="{{ $seguimiento->idSeguimiento ?? '' }}">

