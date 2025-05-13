document.addEventListener('alpine:init', () => {
    Alpine.data('historialTable', () => ({
        datatable: null,

        // Nuevo: modal para editar observación
        modalEditar: {
            open: false,
            id: null,
            respuesta: '',
            estado: 0
        },

        abrirModalEditar(id, respuesta, estado) {
            this.modalEditar = {
                open: true,
                id,
                respuesta: respuesta ?? '',
                estado: estado ?? 0
            };
        },

        guardarCambios() {
            fetch('/asistencias/actualizar-observacion', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    id: this.modalEditar.id,
                    respuesta: this.modalEditar.respuesta,
                    estado: this.modalEditar.estado
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.modalEditar.open = false;
                        location.reload();
                    } else {
                        alert('Error al guardar los cambios.');
                    }
                });
        },

        init() {
            this.initTable();
        },

        initTable() {
            if ($.fn.DataTable.isDataTable('#tablaHistorial')) {
                $('#tablaHistorial').DataTable().destroy();
            }

            this.datatable = $('#tablaHistorial').DataTable({
                pageLength: 10,
                responsive: true,
                autoWidth: false,
                columnDefs: [
                    { targets: 0, visible: false },        // Ocultar columna #
                    { targets: 1, width: "60px" },        // ACCIONES
                    { targets: 2, width: "120px" },        // FECHA
                    { targets: 3, width: "300px" },        // UBICACIÓN
                    { targets: 4, width: "120px" },        // ASUNTO
                    { targets: 5, width: "320px" },        // MENSAJE
                    { targets: 6, width: "180px" },         // IMÁGENES
                    { targets: 7, width: "320px" },        // RESPUESTA
                    { targets: 8, width: "100px" },        // ESTADO
                    { targets: 9, width: "160px" }         // USUARIO
                ],

                language: {
                    search: "Buscar...",
                    zeroRecords: "No se encontraron observaciones",
                    lengthMenu: "Mostrar _MENU_ registros",
                    info: "Mostrando _START_ a _END_ de _TOTAL_",
                    paginate: {
                        first: "Primero",
                        last: "Último",
                        next: "Siguiente",
                        previous: "Anterior"
                    }
                },
                dom: '<"flex justify-end mb-2"f>rt<"flex justify-between items-center mt-4"ilp>',
                initComplete: function () {
                    const wrapper = document.querySelector('.dataTables_wrapper');
                    const table = wrapper?.querySelector('#tablaHistorial');
                    if (!table || !wrapper) return;

                    // Creamos scroll sincronizado arriba
                    const scrollTop = document.createElement('div');
                    scrollTop.className = 'dataTables_scrollTop overflow-x-auto mb-2';
                    scrollTop.style.height = '14px';

                    const topInner = document.createElement('div');
                    topInner.style.width = table.scrollWidth + 'px';
                    topInner.style.height = '1px';
                    scrollTop.appendChild(topInner);

                    // Insertamos scrollTop al principio
                    wrapper.insertBefore(scrollTop, wrapper.firstChild);

                    // Creamos contenedor scroll y movemos la tabla dentro
                    const scrollContainer = document.createElement('div');
                    scrollContainer.className = 'dataTables_scrollable overflow-x-auto border border-gray-200 rounded-md mb-3';

                    // Insertamos el contenedor antes de la tabla
                    table.parentNode.insertBefore(scrollContainer, table);
                    scrollContainer.appendChild(table);

                    // Sincronizamos scroll
                    scrollTop.addEventListener('scroll', () => {
                        scrollContainer.scrollLeft = scrollTop.scrollLeft;
                    });
                    scrollContainer.addEventListener('scroll', () => {
                        scrollTop.scrollLeft = scrollContainer.scrollLeft;
                    });

                    // Controles flotantes
                    const floatingControls = document.createElement('div');
                    floatingControls.className = 'floating-controls flex justify-between items-center border-t p-2 shadow-md bg-white dark:bg-[#121c2c]';
                    Object.assign(floatingControls.style, {
                        position: 'sticky',
                        bottom: '0',
                        left: '0',
                        width: '100%',
                        zIndex: '10'
                    });

                    const info = wrapper.querySelector('.dataTables_info');
                    const length = wrapper.querySelector('.dataTables_length');
                    const paginate = wrapper.querySelector('.dataTables_paginate');

                    if (info && length && paginate) {
                        floatingControls.appendChild(info);
                        floatingControls.appendChild(length);
                        floatingControls.appendChild(paginate);
                        wrapper.appendChild(floatingControls);
                    }
                }

            });
        }
    }));
});
