// assets/js/seguimiento/cronograma.js
console.log('Cronograma JS cargado - Frappe Gantt disponible:', typeof Gantt !== 'undefined');

// Función para mostrar error si Frappe Gantt no carga
function mostrarErrorGantt() {
    const ganttContainer = document.getElementById('gantt_cronograma');
    if (ganttContainer) {
        ganttContainer.innerHTML = `
            <div class="flex flex-col items-center justify-center h-64 text-red-500 p-4">
                <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.966-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <h3 class="text-lg font-semibold mb-2">Error al cargar el cronograma</h3>
                <p class="text-sm text-center">La biblioteca Frappe Gantt no se cargó correctamente.</p>
                <button onclick="location.reload()" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Recargar página
                </button>
            </div>
        `;
    }
}

// Función principal para inicializar el cronograma
function initCronograma() {
    console.log('initCronograma llamado');

    let gantt;
    let tareas = [];
    const seguimientoId = document.getElementById('idSeguimientoHidden')?.value;

    console.log('Seguimiento ID:', seguimientoId);
    console.log('Gantt disponible:', typeof Gantt);

    // Verificar si Frappe Gantt está cargado
    if (typeof Gantt === 'undefined') {
        console.error('Frappe Gantt no está cargado correctamente');
        mostrarErrorGantt();
        return;
    }

    // Declarar todas las funciones internas
    function mostrarEstadoVacio() {
        const ganttContainer = document.getElementById('gantt_cronograma');
        if (ganttContainer) {
            ganttContainer.innerHTML = `
                <div class="flex flex-col items-center justify-center h-64 text-gray-500">
                    <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p class="text-lg mb-2">No hay tareas programadas</p>
                </div>
            `;
        }
    }

    async function cargarTareas() {
        try {
            // DATOS DE PRUEBA TEMPORAL - Comenta cuando funcione
            console.log('Usando datos de prueba...');
            tareas = [
                {
                    id: 1,
                    name: 'Diseño inicial del proyecto',
                    fecha_inicio: '2024-01-01',
                    fecha_fin: '2024-01-10',
                    progreso: 75,
                    estado: 'en-progreso',
                    dependencias: '',
                },
                {
                    id: 2,
                    name: 'Desarrollo frontend',
                    fecha_inicio: '2024-01-05',
                    fecha_fin: '2024-01-20',
                    progreso: 25,
                    estado: 'pendiente',
                    dependencias: '1',
                },
                {
                    id: 3,
                    name: 'Testing y QA',
                    fecha_inicio: '2024-01-15',
                    fecha_fin: '2024-01-25',
                    progreso: 0,
                    estado: 'pendiente',
                    dependencias: '2',
                },
            ];

            renderizarGantt();
            actualizarEstadisticas();
            return;
            // FIN DATOS DE PRUEBA

            if (!seguimientoId) {
                console.error('No se encontró el ID de seguimiento');
                return;
            }

            const response = await fetch(`/seguimiento/${seguimientoId}/tareas`);
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }

            const data = await response.json();

            if (data.success) {
                tareas = data.tareas || [];
                renderizarGantt();
                actualizarEstadisticas();
            } else {
                console.error('Error al cargar tareas:', data.message);
                mostrarEstadoVacio();
            }
        } catch (error) {
            console.error('Error al cargar tareas:', error);
            mostrarEstadoVacio();
        }
    }

    function renderizarGantt() {
        const ganttContainer = document.getElementById('gantt_cronograma');
        console.log('Renderizando Gantt en:', ganttContainer);

        if (!ganttContainer) {
            console.error('No se encontró el contenedor del Gantt');
            return;
        }

        if (tareas.length === 0) {
            console.log('No hay tareas, mostrando estado vacío');
            mostrarEstadoVacio();
            return;
        }

        console.log('Tareas a renderizar:', tareas);

        // Convertir tareas al formato que espera Frappe Gantt
        const tasks = tareas.map((tarea) => ({
            id: tarea.id.toString(),
            name: tarea.name || tarea.nombre, // ✅ Compatible con ambos nombres
            start: tarea.fecha_inicio,
            end: tarea.fecha_fin,
            progress: tarea.progreso || 0,
            dependencies: tarea.dependencias ? tarea.dependencias.split(',') : [],
            custom_class: `estado-${tarea.estado || 'pendiente'}`,
        }));

        // Destruir instancia anterior si existe
        if (gantt) {
            gantt.destroy();
        }

        // Crear nueva instancia de Gantt
        try {
            gantt = new Gantt(ganttContainer, tasks, {
                header_height: 60,
                column_width: 40,
                step: 24,
                view_modes: ['Day', 'Week', 'Month', 'Quarter', 'Year'],
                bar_height: 26,
                bar_corner_radius: 6,
                arrow_curve: 6,
                padding: 22,
                view_mode: 'Month',
                date_format: 'YYYY-MM-DD',
                language: 'es',
                custom_popup_html: function (task) {
                    const tarea = tareas.find((t) => t.id.toString() === task.id);
                    const estadoText = {
                        pendiente: 'Pendiente ⏳',
                        'en-progreso': 'En Progreso 🚀',
                        completado: 'Completado ✅',
                        atrasado: 'Atrasado ⚠️',
                    }[tarea?.estado || 'pendiente'];

                    return `
            <div class="bg-white p-4 rounded-lg shadow-xl border border-gray-200 min-w-64">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="font-bold text-gray-800 text-lg">${task.name}</h4>
                    <span class="px-2 py-1 rounded-full text-xs font-medium ${
                        tarea?.estado === 'completado'
                            ? 'bg-green-100 text-green-800'
                            : tarea?.estado === 'en-progreso'
                              ? 'bg-blue-100 text-blue-800'
                              : tarea?.estado === 'atrasado'
                                ? 'bg-red-100 text-red-800'
                                : 'bg-yellow-100 text-yellow-800'
                    }">${estadoText}</span>
                </div>
                
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex justify-between">
                        <span class="font-medium">Inicio:</span>
                        <span>${formatDate(task.start)}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Fin:</span>
                        <span>${formatDate(task.end)}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Duración:</span>
                        <span>${calculateDuration(task.start, task.end)} días</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="font-medium">Progreso:</span>
                        <div class="w-20 bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: ${task.progress}%"></div>
                        </div>
                        <span class="text-xs">${task.progress}%</span>
                    </div>
                </div>
            </div>
        `;
                },
                on_click: function (task) {
                    mostrarDetallesTarea(task.id);
                },
                on_date_change: function (task, start, end) {
                    console.log('Fecha cambiada:', task, start, end);
                    // Aquí puedes agregar lógica para guardar los cambios de fecha
                },
                on_progress_change: function (task, progress) {
                    console.log('Progreso cambiado:', task, progress);
                    // Aquí puedes agregar lógica para guardar los cambios de progreso
                },
            });

            // Eventos de interacción
            gantt.bar_click = function (task) {
                mostrarDetallesTarea(task.id);
            };

            gantt.bar_dblclick = function (task) {
                mostrarDetallesTarea(task.id);
            };

            console.log('Gantt renderizado exitosamente');
        } catch (error) {
            console.error('Error al crear instancia de Gantt:', error);
            mostrarErrorGantt();
        }
    }

    function mostrarDetallesTarea(tareaId) {
        const tarea = tareas.find((t) => t.id.toString() === tareaId);
        if (tarea) {
            Swal.fire({
                title: tarea.name || tarea.nombre, // ✅ Compatible con ambos
                html: `
                <div class="text-left">
                    <p><strong>Fecha inicio:</strong> ${formatDate(tarea.fecha_inicio)}</p>
                    <p><strong>Fecha fin:</strong> ${formatDate(tarea.fecha_fin)}</p>
                    <p><strong>Progreso:</strong> ${tarea.progreso}%</p>
                    <p><strong>Estado:</strong> ${tarea.estado}</p>
                </div>
            `,
                icon: 'info',
                confirmButtonText: 'Cerrar',
            });
        }
    }

    function inicializarEventos() {
        console.log('Inicializando eventos...');

        // Filtro de estado
        document.getElementById('filtroEstado')?.addEventListener('change', (e) => {
            filtrarTareas(e.target.value);
        });

        // Vista Gantt
        document.getElementById('vistaGantt')?.addEventListener('change', (e) => {
            if (gantt) {
                gantt.change_view_mode(e.target.value);
            }
        });

        // Botón exportar
        document.getElementById('btnExportar')?.addEventListener('click', exportarCronograma);
    }

    function filtrarTareas(estado) {
        let tareasFiltradas = tareas;

        if (estado !== 'todos') {
            tareasFiltradas = tareas.filter((t) => t.estado === estado);
        }

        if (tareasFiltradas.length === 0) {
            mostrarEstadoVacio();
            return;
        }

        // ✅ CORREGIR ESTA LÍNEA - usar el mismo mapeo que en renderizarGantt
        const tasks = tareasFiltradas.map((tarea) => ({
            id: tarea.id.toString(),
            name: tarea.name || tarea.nombre, // ✅ Compatible con ambos
            start: tarea.fecha_inicio,
            end: tarea.fecha_fin,
            progress: tarea.progreso || 0,
            dependencies: tarea.dependencias ? tarea.dependencias.split(',') : [],
            custom_class: `estado-${tarea.estado || 'pendiente'}`,
        }));

        if (gantt) {
            gantt.refresh(tasks);
        }
    }

    function actualizarEstadisticas() {
        const total = tareas.length;
        const pendientes = tareas.filter((t) => t.estado === 'pendiente').length;
        const completadas = tareas.filter((t) => t.estado === 'completado').length;

        const hoy = new Date();
        const atrasadas = tareas.filter((t) => {
            if (t.estado !== 'completado') {
                const fechaFin = new Date(t.fecha_fin);
                return fechaFin < hoy;
            }
            return false;
        }).length;

        document.getElementById('total-tareas').textContent = total;
        document.getElementById('tareas-pendientes').textContent = pendientes;
        document.getElementById('tareas-completadas').textContent = completadas;
        document.getElementById('tareas-atrasadas').textContent = atrasadas;
    }

    function formatDate(dateString) {
        try {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('es-ES', options);
        } catch (error) {
            return dateString;
        }
    }

    function calculateDuration(start, end) {
        const startDate = new Date(start);
        const endDate = new Date(end);
        const diffTime = Math.abs(endDate - startDate);
        return Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // +1 para incluir ambos días
    }

    function exportarCronograma() {
        Swal.fire({
            icon: 'info',
            title: 'Exportar',
            text: 'Función de exportación en desarrollo',
            timer: 2000,
            showConfirmButton: false,
        });
    }

    // Iniciar la carga
    cargarTareas();
    inicializarEventos();
}

// Hacer la función global
window.initCronograma = initCronograma;

// Inicializar automáticamente si el elemento existe
document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM completamente cargado');

    if (document.getElementById('gantt_cronograma')) {
        console.log('Elemento gantt_cronograma encontrado, inicializando...');
        initCronograma();
    }
});

// Función global para renderizar desde el tab principal
window.renderCronograma = function (element) {
    console.log('Render cronograma llamado para elemento:', element);

    setTimeout(() => {
        if (typeof initCronograma === 'function') {
            console.log('Inicializando cronograma desde render...');
            initCronograma();
        }
    }, 100);
};