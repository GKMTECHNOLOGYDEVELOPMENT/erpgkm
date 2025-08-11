// Helper global
window.renderCronograma = function (el, tasks) {
    const data = tasks || [
        {
            id: 'Task 1',
            name: 'Proyecto A',
            start: '2025-08-01',
            end: '2025-08-05',
            progress: 60,
        },
        {
            id: 'Task 2',
            name: 'Tarea 1',
            start: '2025-08-01',
            end: '2025-08-03',
            progress: 40,
            dependencies: 'Task 1',
        },
        {
            id: 'Task 3',
            name: 'Tarea 2',
            start: '2025-08-04',
            end: '2025-08-05',
            progress: 80,
            dependencies: 'Task 2',
        },
    ];
    if (!el) return;
    const init = () => {
        if (el.offsetWidth === 0) return requestAnimationFrame(init); // espera a que el tab sea visible
        new Gantt(el, data, {
            view_mode: 'Day',
            date_format: 'YYYY-MM-DD',
        });
    };
    init();
};
