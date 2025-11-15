document.addEventListener('alpine:init', () => {
    Alpine.data('multipleTable', () => ({
        datatable1: null,
        init() {
            this.fetchDataAndInitTable();
            document.addEventListener('borrar', e => this.deleteSubcategoria(e.detail.id));
        },
        async fetchDataAndInitTable() {
            this.datatable1 = $('#myTable1').DataTable({
                serverSide: true,
                processing: true,
                ajax: { url: '/api/subcategoria', type: 'GET', dataSrc: 'data' },
                columns: [
                    { data: 'nombre', className: 'text-center' },
                    { data: 'descripcion', className: 'text-center' },
                    
                    {
                        data: null, orderable: false, searchable: false, className: 'text-center',
                        render: (_, __, row) => {
    let botones = '<div class="flex justify-center items-center gap-3">';

    // Editar
    if (window.permisos.puedeEditar) {
        botones += `
              <a href="/subcategoria/${row.id}/edit" class="text-blue-500 hover:text-blue-700" x-tooltip="Editar">
                                        <svg width="24" height="24" class="w-5 h-5" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path d="M15.29 3.15L14.36 4.08 5.84 12.6c-.58.58-.87.87-1.11 1.2-.29.38-.54.79-.75 1.2-.17.36-.3.73-.56 1.51L2.32 19.8l-.27.8c-.13.38-.03.8.27 1.1.3.3.72.4 1.1.27l.8-.27 3.28-1.1c.78-.26 1.15-.39 1.51-.56.41-.21.82-.46 1.2-.75.33-.24.62-.53 1.2-1.11l8.52-8.52.93-.93c1.54-1.54 1.54-4.04 0-5.58-1.54-1.54-4.04-1.54-5.58 0z" stroke-width="1.5"/>
                                            <path d="M14.36 4.08s.12 1.97 1.85 3.74c1.73 1.77 3.74 1.79 3.74 1.79M4.2 21.68l-1.88-1.88" opacity="0.5" stroke-width="1.5"/>
                                        </svg>
                                    </a>
                                `;
    }

    // Eliminar
    if (window.permisos.puedeEliminar) {
        botones += `
            <button @click="$dispatch('borrar', { id: ${row.id} })" class="text-red-500 hover:text-red-700" x-tooltip="Eliminar">
                                        <svg width="24" height="24" class="w-5 h-5" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path opacity="0.5" d="M9.17 4c.41-1.17 1.52-2 2.83-2s2.42.83 2.83 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                            <path d="M20.5 6h-17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                            <path d="M18.83 8.5l-.46 6.9c-.18 2.65-.27 3.97-1.13 4.78-.86.8-2.19.82-4.85.82h-.77c-2.66 0-4 .02-4.85-.82-.86-.81-.95-2.13-1.13-4.78l-.46-6.9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                            <path d="M9.5 11l.5 5" opacity="0.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                            <path d="M14.5 11l-.5 5" opacity="0.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                        </svg>
                                    </button>
                                `;
    }

    // Sin permisos
    if (!window.permisos.puedeEditar && !window.permisos.puedeEliminar) {
        botones += `<span class="text-gray-400 text-sm">Sin permisos</span>`;
    }

    botones += '</div>';
    return botones;
}

                    }
                ],
                responsive: true, autoWidth: false, pageLength: 10,
                language: {
                    search: 'Buscar...', zeroRecords: 'No hay registros',
                    lengthMenu: 'Mostrar _MENU_', loadingRecords: 'Cargando...',
                    info: 'Mostrando _START_–_END_ de _TOTAL_', paginate: {
                        first: 'Primero', last: 'Último', next: 'Sig.', previous: 'Ant.'
                    }
                },
                dom: '<"flex justify-end mb-4"f>rt<"flex justify-between mt-4"ilp>',
                initComplete: function() {
                    /* Mantener scroll y footer sticky, como antes */
                }
            });
        },
        deleteSubcategoria(id) {
            Swal.fire({
                icon: 'warning', title: '¿Estás seguro?', text: '¡No podrás deshacerlo!',
                showCancelButton: true, confirmButtonText: 'Eliminar', cancelButtonText: 'Cancelar'
            }).then(result => {
                if (!result.isConfirmed) return;
                fetch(`/subcategoria/destroy/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message || 'Error eliminando');
                    Swal.fire('Eliminado', data.message, 'success').then(() => location.reload());
                })
                .catch(err => Swal.fire('Error', err.message, 'error'));
            });
        }
    }));
});
