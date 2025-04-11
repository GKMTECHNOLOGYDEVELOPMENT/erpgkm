let tabla; // fuera del ready

$(document).ready(function () {
    flatpickr("#startDate", { dateFormat: "Y-m-d" });
    flatpickr("#endDate", { dateFormat: "Y-m-d" });

    function cargarTabla() {
        const $tabla = $('#tablaAsistencias');

        if ($.fn.DataTable.isDataTable($tabla)) {
            tabla.clear().destroy();
        }

        tabla = $tabla.DataTable({
            scrollX: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: '/asistencia/listado',
                data: function (d) {
                    d.startDate = $('#startDate').val();
                    d.endDate = $('#endDate').val();
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
            columnDefs: [
                { targets: '_all', className: 'text-center' } // 🔥 CENTRAR TODO
            ],
            language: {
                processing: "Procesando...",
                lengthMenu: "Mostrar _MENU_ registros",
                zeroRecords: "No se encontraron resultados",
                emptyTable: "No hay datos disponibles en la tabla",
                info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                infoEmpty: "Mostrando 0 a 0 de 0 registros",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "Buscar:",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior"
                },
                loadingRecords: "Cargando...",
                aria: {
                    sortAscending: ": activar para ordenar ascendente",
                    sortDescending: ": activar para ordenar descendente"
                }
            },
            dom:
                "<'flex items-center justify-between mb-4'<'dataTables_length'l><'dataTables_filter'f>>" +
                "<'overflow-x-auto'tr>" +
                "<'flex items-center justify-between mt-4'<'dataTables_info'i><'dataTables_paginate'p>>"
        });
    }

    // carga inicial
    cargarTabla();

    // recargar al cambiar fechas
    $('#startDate, #endDate').on('change', function () {
        cargarTabla();
    });
});
