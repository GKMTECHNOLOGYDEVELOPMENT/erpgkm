document.addEventListener("alpine:init", () => {
    Alpine.data("multipleTable", () => ({
        datatable1: null,

        init() {
            this.fetchDataAndInitTable();
        },

        async fetchDataAndInitTable() {
            try {
                const res = await fetch("/api/proveedores");
                if (!res.ok) throw new Error("Error al obtener datos del servidor");

                const data = await res.json();
                console.log("Datos de proveedores:", data);

                this.datatable1 = $('#myTable1').DataTable({
                    data,
                    columns: [
                        { data: 'idTipoDocumento', className: 'text-center', render: tipo => tipo || 'N/A' },
                        { data: 'numeroDocumento', className: 'text-center', render: doc => doc || 'N/A' },
                        { data: 'nombre', className: 'text-center', render: nombre => nombre || 'N/A' },
                        { data: 'telefono', className: 'text-center', render: telefono => telefono || 'N/A' },
                        { data: 'email', className: 'text-center', render: email => email || 'N/A' },
                        { 
                            data: 'idArea', 
                            className: 'text-center', 
                            render: area => area || 'N/A' 
                        },
                        { 
                            data: 'direccion', 
                            className: 'text-center', 
                            render: direccion => `
                                <div style="max-width: 250px; overflow-wrap: break-word; white-space: normal; margin: 0 auto;">
                                    ${direccion || 'N/A'}
                                </div>`
                        },
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
                            render: (data, type, row) => `
                                <div class="flex justify-center items-center gap-2">
                                    <a href="/proveedores/${row.idProveedor}/edit" x-tooltip="Editar">
                                        <svg width="24" height="24" class="w-4.5 h-4.5" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path d="M15.29 3.15L14.36 4.08 5.84 12.6c-.58.58-.87.87-1.11 1.2-.29.38-.54.79-.75 1.2-.17.36-.3.73-.56 1.51L2.32 19.8l-.27.8c-.13.38-.03.8.27 1.1.3.3.72.4 1.1.27l.8-.27 3.28-1.1c.78-.26 1.15-.39 1.51-.56.41-.21.82-.46 1.2-.75.33-.24.62-.53 1.2-1.11l8.52-8.52.93-.93c1.54-1.54 1.54-4.04 0-5.58-1.54-1.54-4.04-1.54-5.58 0z" stroke-width="1.5"/>
                                            <path d="M14.36 4.08s.12 1.97 1.85 3.74c1.73 1.77 3.74 1.79 3.74 1.79M4.2 21.68l-1.88-1.88" opacity="0.5" stroke-width="1.5"/>
                                        </svg>
                                    </a>
                                    <button type="button" x-tooltip="Eliminar" @click="deleteProveedor(${row.idProveedor})">
                                        <svg width="24" height="24" class="w-5 h-5" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path d="M9.17 4c.41-1.17 1.52-2 2.83-2s2.42.83 2.83 2" opacity="0.5" stroke-width="1.5" stroke-linecap="round"/>
                                            <path d="M20.5 6h-17" stroke-width="1.5" stroke-linecap="round"/>
                                            <path d="M18.83 8.5l-.46 6.9c-.18 2.65-.27 3.97-1.13 4.78-.86.8-2.19.82-4.85.82h-.77c-2.66 0-4 .02-4.85-.82-.86-.81-.95-2.13-1.13-4.78l-.46-6.9" stroke-width="1.5" stroke-linecap="round"/>
                                            <path d="M9.5 11l.5 5" opacity="0.5" stroke-width="1.5" stroke-linecap="round"/>
                                            <path d="M14.5 11l-.5 5" opacity="0.5" stroke-width="1.5" stroke-linecap="round"/>
                                        </svg>
                                    </button>
                                </div>
                            `
                        }
                    ],
                    responsive: true,
                    autoWidth: false,
                    order: [[0, 'desc']],
                    pageLength: 10,
                    language: {
                        search: 'Buscar...',
                        zeroRecords: 'No se encontraron registros',
                        lengthMenu: 'Mostrar _MENU_ registros por página',
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

            } catch (error) {
                console.error("Error al inicializar la DataTable:", error);
            }
        },

        deleteProveedor(idProveedor) {
            Swal.fire({
                icon: 'warning',
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esta acción!",
                showCancelButton: true,
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar',
                padding: '2em',
                customClass: 'sweet-alerts',
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/api/proveedores/${idProveedor}`, {
                        method: "DELETE",
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                    })
                    .then(async res => {
                        const data = await res.json();
                        if (!res.ok) throw new Error(data.message || 'Error al eliminar proveedor.');

                        Swal.fire('¡Eliminado!', data.message, 'success').then(() => {
                            location.reload();
                        });
                    })
                    .catch(error => {
                        Swal.fire('Error', error.message || 'Ocurrió un error.', 'error');
                    });
                }
            });
        },

    }));
});
