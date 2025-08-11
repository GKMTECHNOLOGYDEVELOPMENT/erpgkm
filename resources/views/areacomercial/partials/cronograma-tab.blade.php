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
</style>

<div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div class="text-lg font-semibold">Cronograma</div>

    <!-- Controles: ahora con wrap y sin scroll -->
    <div class="flex flex-wrap items-center gap-2">
        <select id="cronograma_view" class="form-select text-white-dark w-full sm:w-auto">
            <option value="Quarter Day">Cuarto de día</option>
            <option value="Half Day">Medio día</option>
            <option value="Day" selected>Día</option>
            <option value="Week">Semana</option>
            <option value="Month">Mes</option>
        </select>

        <button id="cronograma_new" type="button" class="btn btn-primary w-full sm:w-auto">Nueva tarea</button>
        <button id="cronograma_today" type="button" class="btn btn-outline-primary w-full sm:w-auto">Hoy</button>
        <button id="cronograma_fit" type="button" class="btn btn-outline-info w-full sm:w-auto">Ajustar</button>
        <button id="cronograma_export" type="button"
            class="btn btn-outline-secondary w-full sm:w-auto">Descargar</button>
    </div>
</div>


<!-- Contenedor Gantt -->
<div id="gantt_cronograma" style="height:600px;width:100%"></div>

<!-- Modal (JS puro con animación) -->
<div id="cronograma_modal" class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto">
    <div class="flex items-start justify-center min-h-screen px-4">
        <div id="cronograma_panel" class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-lg">
            <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                <div class="font-bold text-lg" id="cronograma_modal_title">Nueva tarea</div>
                <button type="button" class="text-white-dark hover:text-dark" id="cronograma_cancel_top">✕</button>
            </div>
            <div class="p-5">
                <form id="cronograma_form" class="space-y-3">
                    <input type="hidden" id="t_id">

                    <div>
                        <label class="block text-sm mb-1">Nombre <span class="text-gray-500 text-xs">(Ej: Proyecto
                                A)</span></label>
                        <input id="t_name" class="form-input w-full" placeholder="Escribe el nombre de la tarea"
                            required>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm mb-1">Inicio <span class="text-gray-500 text-xs">(Fecha de
                                    comienzo)</span></label>
                            <input id="t_start" type="date" class="form-input w-full" required>
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Fin <span class="text-gray-500 text-xs">(Fecha de
                                    finalización)</span></label>
                            <input id="t_end" type="date" class="form-input w-full" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm mb-1">% Progreso <span class="text-gray-500 text-xs">(0 a
                                    100)</span></label>
                            <input id="t_progress" type="number" min="0" max="100" value="0"
                                class="form-input w-full" placeholder="Ej: 50">
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Depende de (IDs, coma)
                                <span class="text-gray-500 text-xs">(Ej: T1,T2)</span>
                            </label>
                            <input id="t_deps" class="form-input w-full" placeholder="IDs de tareas previas">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm mb-1">Color</label>
                        <select id="t_color" class="form-select w-full">
                            <option value="">Violeta</option>
                        </select>
                    </div>

                    <div class="flex justify-end items-center pt-4">
                        <button type="button" id="cronograma_delete"
                            class="btn btn-outline-danger hidden">Eliminar</button>
                        <button type="button" id="cronograma_cancel"
                            class="btn btn-outline-secondary ltr:ml-2 rtl:mr-2">Cancelar</button>
                        <button type="submit" class="btn btn-primary ltr:ml-2 rtl:mr-2">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
