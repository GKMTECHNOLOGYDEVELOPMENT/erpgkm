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
            url: '/asistencias/listado',
            data: function (d) {
                d.startDate = document.getElementById('startDate').value;
                d.endDate = document.getElementById('endDate').value;
            }
        },
        order: [],
        columns: [
            {
                data: 'empleado',
                className: 'text-center align-middle',
                render: function (data, type, row) {
                    if (type === 'display') {
                        return '<strong style="font-weight: 600; text-transform: uppercase;">' + data + '</strong>';
                    }
                    return data;
                }
            },
            {
                data: 'fecha',
                className: 'text-center align-middle',
                render: function (data, type, row) {
                    if (type === 'display') {
                        return '<span class="badge badge-outline-primary">' + data + '</span>';
                    }
                    return data;
                }
            },
            {
                data: 'entrada',
                className: 'text-center align-middle',
                render: function (data) {
                    return data
                        ? '<span class="badge badge-outline-primary">' + data + '</span>'
                        : '-';
                }
            },
            {
                data: 'ubicacion_entrada',
                className: 'text-center align-middle',
                render: function (data, type, row) {
                    if (type === 'display') {
                        if (!data) return '-';
                        const textoVisible = data.length > 25 ? data.substr(0, 25) + '...' : data;
                        return `
                            <div class="w-[160px] mx-auto overflow-hidden whitespace-nowrap text-ellipsis" title="${data}">
                                <span style="display: none;">${data}</span>
                                ${textoVisible}
                            </div>
                        `;
                    }
                    return data || '-';
                }
            },
            {
                data: 'inicio_break',
                className: 'text-center align-middle',
                render: function (data) {
                    return data
                        ? '<span class="badge badge-outline-warning">' + data + '</span>'
                        : '-';
                }
            },
            {
                data: 'fin_break',
                className: 'text-center align-middle',
                render: function (data) {
                    return data
                        ? '<span class="badge badge-outline-info">' + data + '</span>'
                        : '-';
                }
            },
            {
                data: 'salida',
                className: 'text-center align-middle',
                render: function (data) {
                    return data
                        ? '<span class="badge badge-outline-danger">' + data + '</span>'
                        : '-';
                }
            },
            {
                data: 'ubicacion_salida',
                className: 'text-center align-middle',
                render: function (data, type, row) {
                    if (type === 'display') {
                        if (!data) return '-';
                        const textoVisible = data.length > 25 ? data.substr(0, 25) + '...' : data;
                        return `
                            <div class="w-[160px] mx-auto overflow-hidden whitespace-nowrap text-ellipsis" title="${data}">
                                <span style="display: none;">${data}</span>
                                ${textoVisible}
                            </div>
                        `;
                    }
                    return data || '-';
                }
            },
            {
                data: 'asistencia',
                className: 'text-center align-middle',
                render: function (data, type, row) {
                    if (type === 'display') {
                        if (data === 'ASISTIÓ') {
                            return '<span class="badge bg-success">ASISTIÓ</span>';
                        } else {
                            return '<span class="badge bg-danger">NO ASISTIÓ</span>';
                        }
                    }
                    return data;
                }
            }
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