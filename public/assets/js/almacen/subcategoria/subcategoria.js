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
                        render: (_, __, row) => `
                            <div class="flex justify-center gap-2">
                                <a href="/subcategoria/${row.id}/edit" class="mr-2">✏️</a>
                                <button @click="$dispatch('borrar',{id: ${row.id}})">🗑️</button>
                            </div>`
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
