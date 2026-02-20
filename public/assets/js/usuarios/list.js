document.addEventListener('alpine:init', () => {
    Alpine.data('usuariosTable', () => ({
        datatable: null,

        init() {
            this.initTable();
        },

        // Función para formatear la fila expandida - CON ESTADO WEB Y APP
        formatChildRow(rowData) {
            return `
                <div class="child-row-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Columna 1: Información de usuario -->
                        <div>
                            <h4 class="font-semibold text-primary border-b pb-2 mb-3">Información de Usuario</h4>
                            <table class="w-full text-sm">
                                <tr>
                                    <td class="text-gray-500 dark:text-gray-400 py-1 w-1/3">Usuario:</td>
                                    <td class="font-medium py-1">${rowData.usuario || 'No disponible'}</td>
                                </tr>
                                <tr>
                                    <td class="text-gray-500 dark:text-gray-400 py-1">Email:</td>
                                    <td class="font-medium py-1">${rowData.correo || 'No disponible'}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Columna 2: Rol y área -->
                        <div>
                            <h4 class="font-semibold text-primary border-b pb-2 mb-3">Rol y Área</h4>
                            <table class="w-full text-sm">
                                <tr>
                                    <td class="text-gray-500 dark:text-gray-400 py-1 w-1/3">Tipo Usuario:</td>
                                    <td class="font-medium py-1">${rowData.tipoUsuario || 'No disponible'}</td>
                                </tr>
                                <tr>
                                    <td class="text-gray-500 dark:text-gray-400 py-1">Rol:</td>
                                    <td class="font-medium py-1">${rowData.rol || 'No disponible'}</td>
                                </tr>
                                <tr>
                                    <td class="text-gray-500 dark:text-gray-400 py-1">Área:</td>
                                    <td class="font-medium py-1">${rowData.tipoArea || 'No disponible'}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Segunda fila: Estados Web y App -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <!-- Estado Web -->
                        <div>
                            <h4 class="font-semibold text-primary border-b pb-2 mb-3">Estado Web</h4>
                            <div class="flex items-center gap-2">
                                ${this.renderEstadoWeb(rowData.estadoWeb)}
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    ${rowData.estadoWeb == 1 ? 'Activo en web' : 'Inactivo en web'}
                                </span>
                            </div>
                        </div>

                        <!-- Estado App -->
                        <div>
                            <h4 class="font-semibold text-primary border-b pb-2 mb-3">Estado App</h4>
                            <div class="flex items-center gap-2">
                                ${this.renderEstadoApp(rowData.estadoApp)}
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    ${rowData.estadoApp == 1 ? 'Activo en app' : 'Inactivo en app'}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        },

        // Función para renderizar estado Web
        renderEstadoWeb(estadoWeb) {
            if (estadoWeb == 1) {
                return `<span class="badge badge-outline-success text-green-600 border-green-600 px-2 py-1 rounded-lg text-xs">Activo</span>`;
            } else if (estadoWeb == 0) {
                return `<span class="badge badge-outline-danger text-red-600 border-red-600 px-2 py-1 rounded-lg text-xs">Inactivo</span>`;
            } else {
                return `<span class="badge badge-outline-secondary text-gray-600 border-gray-600 px-2 py-1 rounded-lg text-xs">No definido</span>`;
            }
        },

        // Función para renderizar estado App
        renderEstadoApp(estadoApp) {
            if (estadoApp == 1) {
                return `<span class="badge badge-outline-success text-green-600 border-green-600 px-2 py-1 rounded-lg text-xs">Activo</span>`;
            } else if (estadoApp == 0) {
                return `<span class="badge badge-outline-danger text-red-600 border-red-600 px-2 py-1 rounded-lg text-xs">Inactivo</span>`;
            } else {
                return `<span class="badge badge-outline-secondary text-gray-600 border-gray-600 px-2 py-1 rounded-lg text-xs">No definido</span>`;
            }
        },

        initTable() {
            this.datatable = $('#myTable1').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: '/api/usuarios-datatable',
                    type: 'GET'
                },
                columns: [
                    {
                        // Columna para el botón expandir
                        data: null,
                        className: 'text-center',
                        orderable: false,
                        searchable: false,
                        width: '40px',
                        render: (data, type, row) => {
                            return `
                                <button class="btn-expand" data-id="${row.idUsuario}">
                                    <svg class="w-4 h-4 expand-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            `;
                        }
                    },
                    {
                        data: 'avatar',
                        className: 'text-center',
                        render: avatar => avatar
                            ? `<img src="${avatar}" alt="Avatar" class="w-10 h-10 rounded-full mx-auto object-cover border-2 border-gray-200" />`
                            : `<div class="w-10 h-10 rounded-full mx-auto bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 12c2.8 0 5-2.2 5-5s-2.2-5-5-5-5 2.2-5 5 2.2 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z"/>
                                </svg>
                            </div>`
                    },
                    { data: 'Nombre', className: 'text-center font-medium' },
                    { data: 'apellidoPaterno', className: 'text-center' },
                    {
                        data: 'tipoDocumento',
                        className: 'text-center',
                        render: data => data || '<span class="text-gray-400">-</span>'
                    },
                    {
                        data: 'documento',
                        className: 'text-center font-mono',
                        render: data => data || '<span class="text-gray-400">-</span>'
                    },
                    {
                        data: 'telefono',
                        className: 'text-center',
                        render: data => data || '<span class="text-gray-400">-</span>'
                    },
                    {
                        data: 'estado',
                        className: 'text-center',
                        render: estado => estado == 1
                            ? `<span class="badge badge-outline-success text-green-600 border-green-600 px-2 py-1 rounded-lg text-xs">Activo</span>`
                            : `<span class="badge badge-outline-danger text-red-600 border-red-600 px-2 py-1 rounded-lg text-xs">Inactivo</span>`
                    },
                    {
                        data: 'tieneFirma',
                        className: 'text-center',
                        render: tieneFirma => tieneFirma
                            ? `<svg class="w-5 h-5 mx-auto text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                               </svg>`
                            : `<svg class="w-5 h-5 mx-auto text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                               </svg>`
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: (_, __, row) => {
                            const permisos = window.permisos || {
                                puedeEditar: false,
                                puedeActualizarEstado: false
                            };

                            let botones = '<div class="flex justify-center items-center gap-2">';

                            if (permisos.puedeEditar) {
                                botones += `
                                    <a href="/usuario/${row.idUsuario}/edit" class="text-blue-600 hover:text-blue-800 transition-colors" title="Editar usuario">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844L7.47919 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z" stroke="currentColor" stroke-width="1.5"/>
                                            <path opacity="0.5" d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015" stroke="currentColor" stroke-width="1.5"/>
                                        </svg>
                                    </a>
                                `;
                            }

                            if (permisos.puedeActualizarEstado) {
                                botones += `
                                    <button type="button" class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200 transition-colors" title="Cambiar Estado" onclick="toggleEstadoUsuario(${row.idUsuario}, ${row.estado})">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path d="M17 3L21 7L17 11" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M21 7H9C6.23858 7 4 9.23858 4 12V12" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M7 21L3 17L7 13" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M3 17H15C17.7614 17 20 14.7614 20 12V12" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </button>
                                `;
                            }

                            if (!permisos.puedeEditar && !permisos.puedeActualizarEstado) {
                                botones += `<span class="text-gray-400 text-xs">Sin permisos</span>`;
                            }

                            botones += '</div>';
                            return botones;
                        }
                    }
                ],
                responsive: false,
                autoWidth: false,
                pageLength: 10,
                order: [[2, 'desc']],
                language: {
                    search: "Buscar:",
                    zeroRecords: "No se encontraron registros",
                    lengthMenu: "Mostrar _MENU_ registros por página",
                    loadingRecords: "Cargando...",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    infoEmpty: "Mostrando 0 a 0 de 0 registros",
                    infoFiltered: "(filtrado de _MAX_ registros totales)",
                    paginate: {
                        first: "Primero",
                        last: "Último",
                        next: "Siguiente",
                        previous: "Anterior"
                    }
                },
                dom: 'rt<"flex flex-wrap justify-between items-center mt-4 px-2"ilp>',

                drawCallback: function() {
                    $('.btn-expand').off('click').on('click', function(e) {
                        e.stopPropagation();
                        var tr = $(this).closest('tr');
                        var row = $('#myTable1').DataTable().row(tr);

                        if (row.child.isShown()) {
                            row.child.hide();
                            tr.removeClass('shown');
                        } else {
                            row.child(
                                Alpine.$data(document.querySelector('[x-data="usuariosTable"]'))
                                    .formatChildRow(row.data())
                            ).show();
                            tr.addClass('shown');
                        }
                    });
                },

                initComplete: function () {
                    const wrapper = document.querySelector('.dataTables_wrapper');
                    const table = wrapper.querySelector('table');
                    const scrollable = document.createElement('div');
                    scrollable.className = 'dataTables_scrollable';
                    table.parentNode.insertBefore(scrollable, table);
                    scrollable.appendChild(table);

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

                    if (info) floatingControls.appendChild(info);
                    if (length) floatingControls.appendChild(length);
                    if (paginate) floatingControls.appendChild(paginate);
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
        title: `¿${mensaje === 'activar' ? 'Activar' : 'Desactivar'} usuario?`,
        text: `El usuario quedará ${nuevoEstado === 1 ? 'activo' : 'inactivo'} en el sistema.`,
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: nuevoEstado === 1 ? '#28a745' : '#dc3545',
        confirmButtonText: `Sí, ${mensaje}`,
        cancelButtonText: "Cancelar",
        reverseButtons: true
    }).then(result => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Procesando...',
                text: 'Por favor espere',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/api/usuarios/${idUsuario}/estado`, {
                method: "PATCH",
                headers: {
                    "Content-Type": "application/json",
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ estado: nuevoEstado }),
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw new Error(err.message || 'Error al cambiar el estado'); });
                }
                return response.json();
            })
            .then(data => {
                Swal.fire({
                    title: "¡Actualizado!",
                    text: `El usuario ha sido ${nuevoEstado === 1 ? 'activado' : 'desactivado'} correctamente.`,
                    icon: "success",
                    timer: 2000,
                    showConfirmButton: false
                });

                $('#myTable1').DataTable().ajax.reload(null, false);
            })
            .catch(error => {
                Swal.fire({
                    title: "Error",
                    text: error.message || "Ocurrió un error al cambiar el estado.",
                    icon: "error",
                    confirmButtonColor: '#3b82f6'
                });
            });
        }
    });
}
