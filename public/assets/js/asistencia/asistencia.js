document.addEventListener("DOMContentLoaded", function () {
    // Inicializa Flatpickr
    flatpickr("#startDate", {
        dateFormat: "Y-m-d",
        maxDate: "today",
        defaultDate: new Date()
    });

    flatpickr("#endDate", {
        dateFormat: "Y-m-d",
        maxDate: "today",
        defaultDate: new Date()
    });

    // Inicializa DataTable
    const tabla = $('#tablaAsistencias').DataTable({
        processing: true,
        ajax: {
            url: '/asistencia/listado',
            data: function (d) {
                d.startDate = document.getElementById('startDate').value;
                d.endDate = document.getElementById('endDate').value;
            }
        },
        columns: [
            { data: 'empleado' },
            { data: 'fecha' },
            { data: 'entrada' },
            { data: 'ubicacion_entrada' },
            { data: 'inicio_break' },
            { data: 'fin_break' },
            { data: 'salida' },
            { data: 'ubicacion_salida' }
        ],
        language: {
            processing: "Procesando...",
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ registros",
            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
            infoEmpty: "Mostrando 0 a 0 de 0 registros",
            infoFiltered: "(filtrado de _MAX_ registros totales)",
            loadingRecords: "Cargando...",
            zeroRecords: "No se encontraron registros",
            emptyTable: "No hay datos disponibles en la tabla",
            paginate: {
                first: "Primero",
                previous: "Anterior",
                next: "Siguiente",
                last: "Último"
            },
            aria: {
                sortAscending: ": activar para ordenar la columna ascendente",
                sortDescending: ": activar para ordenar la columna descendente"
            }
        }

    });

    // Refresca la tabla al cambiar fechas
    document.getElementById('startDate').addEventListener('change', () => tabla.ajax.reload());
    document.getElementById('endDate').addEventListener('change', () => tabla.ajax.reload());
});
