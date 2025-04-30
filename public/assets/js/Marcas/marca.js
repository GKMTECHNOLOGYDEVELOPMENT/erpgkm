document.addEventListener('alpine:init', () => {
    Alpine.data('multipleTable', () => ({
        datatable1: null,

        init() {
            this.fetchDataAndInitTable();

            document.addEventListener('borrar', (e) => {
                this.deleteBrand(e.detail.id);
            });
        },

        async fetchDataAndInitTable() {
            try {
                const res = await fetch('/api/marca');
                if (!res.ok) throw new Error('Error al obtener datos del servidor');
                const data = await res.json();

                jQuery.fn.DataTable.ext.type.search.string = function (data) {
                    return !data ?
                        '' :
                        typeof data === 'string' ?
                            data.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase() :
                            data;
                };

                this.datatable1 = $('#myTable1').DataTable({
                    data,
                    columns: [
                        {
                            data: 'nombre',
                            className: 'text-center',
                        },
                        {
                            data: 'foto',
                            className: 'text-center',
                            render: foto => {
                                if (foto) {
                                    return `<img src="${foto}" class="w-16 h-10 object-contain mx-auto rounded" alt="Foto">`;
                                } else {
                                    return `<div class="text-gray-400 text-sm text-center">Sin Imagen</div>`;
                                }
                            }
                        },
                        {
                            data: 'estado',
                            className: 'text-center',
                            render: estado => {
                                return estado === 'Activo'
                                    ? '<span class="badge badge-outline-success">Activo</span>'
                                    : '<span class="badge badge-outline-danger">Inactivo</span>';
                            }
                        },
                        {
                            data: null,
                            orderable: false,
                            searchable: false,
                            className: 'text-center',
                            render: (_, __, row) => {
                                return `
                                <div class="flex justify-center items-center gap-2">
                                    <a href="/marcas/${row.idMarca}/edit" class="ltr:mr-2 rtl:ml-2" x-tooltip="Editar">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5">
                                            <path d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999L5.83881 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z" stroke="currentColor" stroke-width="1.5" />
                                            <path opacity="0.5" d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015" stroke="currentColor" stroke-width="1.5" />
                                        </svg>
                                    </a>
                                    <button type="button" class="ltr:mr-2 rtl:ml-2" @click="deleteBrand(${row.idMarca})" x-tooltip="Eliminar">
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
                    dom: '<"flex flex-wrap justify-end mb-4"f>rt<"flex flex-wrap justify-between items-center mt-4"ilp>',
                    initComplete: function () {
                        const dataTableWrapper = document.querySelector('.dataTables_wrapper');
                
                        const floatingControls = document.createElement('div');
                        floatingControls.className = 'floating-controls flex justify-between items-center border-t p-2 shadow-md bg-white dark:bg-[#121c2c]';
                        floatingControls.style.position = 'sticky';
                        floatingControls.style.bottom = '0';
                        floatingControls.style.left = '0';
                        floatingControls.style.width = '100%';
                        floatingControls.style.zIndex = '10';
                
                        const info = dataTableWrapper.querySelector('.dataTables_info');
                        const length = dataTableWrapper.querySelector('.dataTables_length');
                        const paginate = dataTableWrapper.querySelector('.dataTables_paginate');
                
                        floatingControls.appendChild(info);
                        floatingControls.appendChild(length);
                        floatingControls.appendChild(paginate);
                
                        dataTableWrapper.appendChild(floatingControls);
                    }
                });
            } catch (e) {
                console.error('Error al inicializar DataTable:', e);
            }
        },

        deleteBrand(idMarca) {
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
                    fetch(`/marcas/${idMarca}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                        },
                    })
                        .then(async res => {
                            const data = await res.json();
                            if (!res.ok) {
                                throw new Error(data.error || 'Error al eliminar marca');
                            }
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
