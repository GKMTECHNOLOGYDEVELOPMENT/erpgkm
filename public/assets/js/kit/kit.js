document.addEventListener('alpine:init', () => {
    Alpine.data('multipleTable', () => ({
        datatable1: null,

        init() {
            this.fetchDataAndInitTable();
        },

        async fetchDataAndInitTable() {
            this.datatable1 = $('#myTable1').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: '/api/kits',
                    type: 'GET'
                },
                columns: [
                    {
                        data: 'foto',
                        className: 'text-center',
                        render: foto => foto?.startsWith('data:image')
                            ? `<img src="${foto}" class="w-12 h-12 object-cover rounded mx-auto" alt="Foto">`
                            : `<img src="/assets/images/articulo/producto-default.png" class="w-12 h-12 object-cover rounded mx-auto" alt="Imagen por defecto">`
                    },
                    { data: 'codigo', className: 'text-center' },
                    { data: 'nombre', className: 'text-center' },
                    { data: 'precio_venta', className: 'text-center' },
                    {
                        data: 'estado',
                        className: 'text-center',
                        render: estado => estado === 'Activo'
                            ? '<span class="badge badge-outline-success">Activo</span>'
                            : '<span class="badge badge-outline-danger">Inactivo</span>'
                    },
                    {
                        data: null,
                        className: 'text-center',
                        orderable: false,
                        searchable: false,
                        render: (__, ___, row) => {
                            return `
                                <div class="flex justify-center items-center gap-2">
                                    <a href="/kits/${row.idKit}/edit" x-tooltip="Editar">✏️</a>
                                    <a href="/kits/${row.idKit}/imagen" x-tooltip="Imagen">🖼️</a>
                                    <a href="/kits/${row.idKit}/detalles" x-tooltip="Detalle">ℹ️</a>
                                    <button type="button" @click="deleteKit(${row.idKit})" x-tooltip="Eliminar">🗑️</button>
                                </div>`;
                        }
                    }
                ],
                responsive: true,
                autoWidth: false,
                pageLength: 10,
                language: {
                    search: 'Buscar...',
                    zeroRecords: 'No se encontraron kits',
                    lengthMenu: 'Mostrar _MENU_ registros',
                    loadingRecords: 'Cargando...',
                    info: 'Mostrando _START_ a _END_ de _TOTAL_ kits',
                    paginate: {
                        first: 'Primero',
                        last: 'Último',
                        next: 'Siguiente',
                        previous: 'Anterior'
                    }
                },
                dom: '<"flex flex-wrap justify-end mb-4"f>rt<"flex flex-wrap justify-between items-center mt-4"ilp>',
                initComplete: function () {
                    const wrapper = document.querySelector('.dataTables_wrapper');
                    const table = wrapper.querySelector('#myTable1');

                    const scrollContainer = document.createElement('div');
                    scrollContainer.className = 'dataTables_scrollable overflow-x-auto border border-gray-200 rounded-md mb-3';
                    table.parentNode.insertBefore(scrollContainer, table);
                    scrollContainer.appendChild(table);

                    const scrollTop = document.createElement('div');
                    scrollTop.className = 'dataTables_scrollTop overflow-x-auto mb-2';
                    scrollTop.style.height = '14px';

                    const topInner = document.createElement('div');
                    topInner.style.width = scrollContainer.scrollWidth + 'px';
                    topInner.style.height = '1px';
                    scrollTop.appendChild(topInner);

                    scrollTop.addEventListener('scroll', () => {
                        scrollContainer.scrollLeft = scrollTop.scrollLeft;
                    });
                    scrollContainer.addEventListener('scroll', () => {
                        scrollTop.scrollLeft = scrollContainer.scrollLeft;
                    });

                    wrapper.insertBefore(scrollTop, scrollContainer);

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
        },

        deleteKit(idKit) {
            Swal.fire({
                icon: 'warning',
                title: '¿Estás seguro?',
                text: '¡No podrás revertir esta acción!',
                showCancelButton: true,
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar',
            }).then(result => {
                if (result.isConfirmed) {
                    fetch(`/kits/${idKit}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                        },
                    })
                        .then(res => res.json())
                        .then(data => {
                            Swal.fire('¡Eliminado!', data.message, 'success').then(() => location.reload());
                        })
                        .catch(error => {
                            Swal.fire('Error', error.message || 'Ocurrió un error.', 'error');
                        });
                }
            });
        }
    }));
});
