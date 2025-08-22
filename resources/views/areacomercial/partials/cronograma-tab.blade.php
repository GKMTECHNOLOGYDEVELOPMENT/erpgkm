<style>
    /* Estilos para el cronograma Gantt */
    .gantt-container {
        height: 500px;
        overflow: auto;
        min-height: 400px;
    }

    .gantt .grid-background {
        fill: none;
    }

    .gantt .grid-row {
        fill: #ffffff;
    }

    .gantt .grid-row:nth-child(even) {
        fill: #f8fafc;
    }

    .gantt .row-line {
        stroke: #e2e8f0;
    }

    .gantt .today-highlight {
        fill: #ffecb3;
        opacity: 0.5;
    }

    .estado-pendiente {
        fill: #fbbf24 !important;
    }

    .estado-en-progreso {
        fill: #3b82f6 !important;
    }

    .estado-completado {
        fill: #10b981 !important;
    }

    .gantt .bar-progress {
        fill: #3b82f6;
    }

    .gantt .bar {
        rx: 3;
        ry: 3;
    }

    /* Asegurar que el contenedor sea visible */
    #gantt_cronograma {
        width: 100%;
        height: 500px;
    }
</style>
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Cronograma del Proyecto</h3>
        <button id="btnNuevaTarea"
            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                </path>
            </svg>
            Nueva Tarea
        </button>
    </div>

    <!-- Filtros y controles -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Filtrar por estado:</label>
            <select id="filtroEstado"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="todos">Todos</option>
                <option value="pendiente">Pendiente</option>
                <option value="en-progreso">En Progreso</option>
                <option value="completado">Completado</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Ver por:</label>
            <select id="vistaGantt"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="Day">Día</option>
                <option value="Week">Semana</option>
                <option value="Month" selected>Mes</option>
                <option value="Quarter">Trimestre</option>
            </select>
        </div>
        <div class="flex items-end">
            <button id="btnExportar"
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Exportar
            </button>
            
        </div>
    </div>

    <!-- Contenedor del Gantt -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <div id="gantt_cronograma" class="gantt-container"></div>
    </div>

    <!-- Estadísticas -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-blue-600" id="total-tareas">0</div>
            <div class="text-sm text-blue-800">Total Tareas</div>
        </div>
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-yellow-600" id="tareas-pendientes">0</div>
            <div class="text-sm text-yellow-800">Pendientes</div>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-green-600" id="tareas-completadas">0</div>
            <div class="text-sm text-green-800">Completadas</div>
        </div>
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-red-600" id="tareas-atrasadas">0</div>
            <div class="text-sm text-red-800">Atrasadas</div>
        </div>
    </div>
</div>

<!-- Modal para editar/crear tarea -->
<div id="modalTarea" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800" id="modalTitulo">Nueva Tarea</h3>
        </div>
        <form id="formTarea" class="p-6">
            <input type="hidden" id="tarea_id" value="">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de la tarea *</label>
                <input type="text" id="tarea_nombre" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha inicio *</label>
                    <input type="date" id="tarea_inicio" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha fin *</label>
                    <input type="date" id="tarea_fin" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Progreso (%)</label>
                <input type="range" id="tarea_progreso" min="0" max="100" step="5"
                    class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                <div class="text-right text-sm text-gray-600">
                    <span id="progreso_valor">0%</span>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select id="tarea_estado"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="pendiente">Pendiente</option>
                    <option value="en-progreso">En Progreso</option>
                    <option value="completado">Completado</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Dependencias</label>
                <select id="tarea_dependencias" multiple
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <!-- Las dependencias se llenarán dinámicamente -->
                </select>
                <p class="text-xs text-gray-500 mt-1">Mantén Ctrl para seleccionar múltiples</p>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" id="btnCancelar"
                    class="px-4 py-2 text-gray-600 hover:text-gray-800 border border-gray-300 rounded-md">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
