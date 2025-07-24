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
                    url: '/api/modelo',
                    type: 'GET'
                },
                columns: [
                    { data: 'nombre', className: 'text-center' },
                    { data: 'marca', className: 'text-center', render: marca => marca || '<span class="text-gray-400">Sin Marca</span>' },
                    { data: 'categoria', className: 'text-center', render: categoria => categoria || '<span class="text-gray-400">Sin Categoría</span>' },
                    {
                        data: 'estado',
                        className: 'text-center',
                        render: estado => estado === 'Activo'
                            ? '<span class="badge badge-outline-success">Activo</span>'
                            : '<span class="badge badge-outline-danger">Inactivo</span>'
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: (_, __, row) => {
                            return `
                                <div class="flex justify-center items-center gap-2">
                                    <a href="/modelos/${row.idModelo}/edit" class="ltr:mr-2 rtl:ml-2" x-tooltip="Editar">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5">
                                            <path d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z" stroke="currentColor" stroke-width="1.5" />
                                            <path opacity="0.5" d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015" stroke="currentColor" stroke-width="1.5" />
                                        </svg>
                                    </a>
                            <a href="/producto/create-producto?modelo=${row.idModelo}" class="ltr:mr-2 rtl:ml-2" x-tooltip="Nuevo producto">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5">
                                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5" fill="none"/>
                                            <path d="M12 8V16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                            <path d="M8 12H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                        </svg>
                                    </a>


                                    <button type="button" class="ltr:mr-2 rtl:ml-2" @click="deleteModel(${row.idModelo})" x-tooltip="Eliminar">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                            <path opacity="0.5" d="M9.17065 4C9.58249 2.83481 10.6937 2 11.9999 2C13.3062 2 14.4174 2.83481 14.8292 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                            <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                            <path d="M18.8334 8.5L18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                            <path opacity="0.5" d="M9.5 11L10 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                            <path opacity="0.5" d="M14.5 11L14 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                        </svg>
                                    </button>
                                </div>`;
                        }
                    }
                ],
                responsive: true,
                autoWidth: false,
                pageLength: 10,
                language: {
                    search: 'Buscar...',
                    zeroRecords: 'No se encontraron registros',
                    lengthMenu: 'Mostrar _MENU_',
                    loadingRecords: 'Cargando...',
                    info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                    paginate: {
                        first: 'Primero',
                        last: 'Último',
                        next: 'Siguiente',
                        previous: 'Anterior'
                    }
                },
                dom: 'rt<"flex flex-wrap justify-between items-center mt-4"ilp>',
                initComplete: function () {
                    const wrapper = document.querySelector('.dataTables_wrapper');
                    const table = wrapper.querySelector('#myTable1');

                    // Scroll top sincronizado
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

        deleteModel(idModelo) {
            Swal.fire({
                icon: 'warning',
                title: '¿Estás seguro?',
                text: '¡No podrás revertir esta acción!',
                showCancelButton: true,
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar',
                padding: '2em',
                customClass: 'sweet-alerts',
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/modelos/${idModelo}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                        },
                    })
                        .then(async res => {
                            const data = await res.json();
                            if (!res.ok) throw new Error(data.error || 'Error al eliminar modelo');

                            Swal.fire('¡Eliminado!', data.message, 'success').then(() => location.reload());
                        })
                        .catch(error => {
                            Swal.fire('Error', error.message || 'Ocurrió un error.', 'error');
                        });
                }
            });
        },
    }));
});
