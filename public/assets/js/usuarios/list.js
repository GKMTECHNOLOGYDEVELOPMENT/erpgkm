document.addEventListener('alpine:init', () => {
    Alpine.data('usuariosTable', () => ({
        datatable: null,

        init() {
            this.fetchUsuarios();
        },

        async fetchUsuarios() {
            try {
                const res = await fetch('/api/usuarios');
                if (!res.ok) throw new Error('Error al obtener usuarios');
                const usuarios = await res.json();

                // Ordenar usuarios más recientes primero
                const usuariosOrdenados = usuarios.sort((a, b) => b.idUsuario - a.idUsuario);

                this.initTable(usuariosOrdenados);
            } catch (error) {
                console.error('❌ Error al obtener usuarios:', error);
            }
        },

        initTable(usuarios) {
            this.datatable = $('#myTable1').DataTable({
                data: usuarios,
                columns: [
                    {
                        data: 'avatar',
                        className: 'text-center',
                        render: avatar => avatar
                            ? `<img src="${avatar}" alt="Avatar" class="w-10 h-10 rounded-full mx-auto object-cover" />`
                            : `<svg class="w-10 h-10 text-gray-400 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                 <path d="M12 12c2.8 0 5-2.2 5-5s-2.2-5-5-5-5 2.2-5 5 2.2 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z"/>
                               </svg>`
                    },
                    { data: 'Nombre', className: 'text-center', render: d => d || 'N/A' },
                    { data: 'apellidoPaterno', className: 'text-center', render: d => d || 'N/A' },
                    { data: 'tipoDocumento', className: 'text-center', render: d => d || 'N/A' },
                    { data: 'documento', className: 'text-center', render: d => d || 'N/A' },
                    { data: 'telefono', className: 'text-center', render: d => d || 'N/A' },
                    { data: 'correo', className: 'text-center', render: d => d || 'N/A' },
                    { data: 'tipoUsuario', className: 'text-center', render: d => d || 'N/A' },
                    { data: 'rol', className: 'text-center', render: d => d || 'N/A' },
                    { data: 'tipoArea', className: 'text-center', render: d => d || 'N/A' },
                    {
                        data: 'estado',
                        className: 'text-center',
                        render: estado => estado == 1
                            ? `<span class="badge badge-outline-success text-green-600 border-green-600 px-2 py-1 rounded-lg">Activo</span>`
                            : `<span class="badge badge-outline-danger text-red-600 border-red-600 px-2 py-1 rounded-lg">Inactivo</span>`
                    },
                    {
                        data: 'tieneFirma',
                        className: 'text-center',
                        render: tieneFirma => tieneFirma
                            ? `<svg class="w-6 h-6 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="#22c55e" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M4.293 12.293a1 1 0 011.414 0L10 16.586l8.293-8.293a1 1 0 011.414 1.414l-9 9a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>`
                            :  `<svg class="w-6 h-6 text-red-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M12 2a10 10 0 100 20 10 10 0 000-20zm0 18a8 8 0 110-16 8 8 0 010 16zm4.707-10.707a1 1 0 00-1.414-1.414L12 10.586 9.707 8.293a1 1 0 10-1.414 1.414L10.586 12l-2.293 2.293a1 1 0 101.414 1.414L12 13.414l2.293 2.293a1 1 0 001.414-1.414L13.414 12l2.293-2.293z" clip-rule="evenodd"/>
                        </svg>`
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: (_, __, row) => `
                            <div class="flex justify-center items-center gap-2">
                                <a href="/usuario/${row.idUsuario}/edit" class="text-blue-600 hover:text-blue-800" x-tooltip="Editar">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5">
                                    <path d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844L7.47919 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z" stroke="currentColor" stroke-width="1.5" />
                                    <path opacity="0.5" d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015" stroke="currentColor" stroke-width="1.5" />
                                </svg>
                                </a>
                                <button type="button" class="text-gray-600 hover:text-gray-800" x-tooltip="Cambiar Estado" onclick="toggleEstadoUsuario(${row.idUsuario}, ${row.estado})">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                <path d="M17 3L21 7L17 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M21 7H9C6.23858 7 4 9.23858 4 12V12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M7 21L3 17L7 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M3 17H15C17.7614 17 20 14.7614 20 12V12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                                </button>
                            </div>
                        `
                    }
                ],
                responsive: true,
                autoWidth: false,
                pageLength: 10,
                order: [[1, 'desc']],
                language: {
                    search: "Buscar...",
                    zeroRecords: "No se encontraron registros",
                    lengthMenu: "Mostrar _MENU_ registros por página",
                    loadingRecords: "Cargando...",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    paginate: {
                        first: "Primero",
                        last: "Último",
                        next: "Siguiente",
                        previous: "Anterior"
                    }
                },
                dom: '<"flex flex-wrap justify-end mb-4"f>rt<"flex flex-wrap justify-between items-center mt-4"ilp>',
                initComplete: function () {
                    const wrapper = document.querySelector('.dataTables_wrapper');
                
                    // Crear contenedor de scroll solo para la tabla
                    const table = wrapper.querySelector('table');
                    const scrollable = document.createElement('div');
                    scrollable.className = 'dataTables_scrollable';
                    table.parentNode.insertBefore(scrollable, table);
                    scrollable.appendChild(table);
                
                    // Crear barra flotante para info, registros y paginación
                    const floatingControls = document.createElement('div');
                    floatingControls.className = 'floating-controls flex justify-between items-center border-t p-2 shadow-md bg-white dark:bg-[#121c2c]';
                    floatingControls.style.position = 'sticky';
                    floatingControls.style.bottom = '0';
                    floatingControls.style.left = '0';
                    floatingControls.style.width = '100%';
                    floatingControls.style.zIndex = '10';
                
                    const info = wrapper.querySelector('.dataTables_info');
                    const length = wrapper.querySelector('.dataTables_length');
                    const paginate = wrapper.querySelector('.dataTables_paginate');
                
                    floatingControls.appendChild(info);
                    floatingControls.appendChild(length);
                    floatingControls.appendChild(paginate);
                
                    wrapper.appendChild(floatingControls);
                }
                
                
            });
        }
    }));
});

function toggleEstadoUsuario(idUsuario, estadoActual) {
    const nuevoEstado = estadoActual === 1 ? 0 : 1;
    const mensaje = nuevoEstado === 1 ? "activar" : "desactivar";

    Swal.fire({
        icon: "warning",
        title: `¿Quieres ${mensaje} este usuario?`,
        showCancelButton: true,
        confirmButtonText: `Sí, ${mensaje}`,
        cancelButtonText: "Cancelar",
    }).then(result => {
        if (result.isConfirmed) {
            fetch(`/api/usuarios/${idUsuario}/estado`, {
                method: "PATCH",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ estado: nuevoEstado }),
            })
            .then(response => {
                if (!response.ok) throw new Error("Error al cambiar el estado del usuario");
                return response.json();
            })
            .then(() => {
                Swal.fire("Actualizado", "El estado del usuario ha sido cambiado con éxito.", "success")
                    .then(() => location.reload());
            })
            .catch(error => {
                Swal.fire("Error", error.message || "Ocurrió un error al cambiar el estado.", "error");
            });
        }
    });
}
