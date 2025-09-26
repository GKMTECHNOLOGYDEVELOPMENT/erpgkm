document.addEventListener('alpine:init', () => {
    Alpine.data('multipleTable', () => ({
        datatable: null,

        init() {
            this.initTable();
        },

        initTable() {
            this.datatable = $('#myTable1').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: '/api/ubicaciones-articulo',
                    type: 'GET',
                },
                columns: [
                    { 
                        data: 'nombre_articulo', 
                        className: 'text-center',
                        render: function(data, type, row) {
                            return `<span class="font-semibold">${data}</span>`;
                        }
                    },
                    { 
                        data: 'nombre_ubicacion', 
                        className: 'text-center',
                        render: function(data, type, row) {
                            return `<span class="font-semibold">${data}</span>`;
                        }
                    },
                    { 
                        data: 'cantidad', 
                        className: 'text-center',
                        render: function(data, type, row) {
                            return `<span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm font-semibold">${data}</span>`;
                        }
                    },
                    
                   
                    
                ],
                language: {
                    search: 'Buscar...',
                    zeroRecords: 'No se encontraron artículos por ubicación',
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
                dom: '<"flex justify-end mb-4"f>rt<"flex justify-between items-center mt-4"ilp>',
            });
        },

        deleteArticuloUbicacion(idArticuloUbicacion) {
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
                    fetch(`/articulo-ubicaciones/${idArticuloUbicacion}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                        },
                    })
                        .then(async res => {
                            const data = await res.json();

                            if (res.ok) {
                                Swal.fire('¡Eliminado!', data.message, 'success').then(() => {
                                    this.datatable.ajax.reload();
                                });
                            } else {
                                Swal.fire('Atención', data.message || 'No se pudo eliminar.', 'warning');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error', error.message || 'Ocurrió un error.', 'error');
                        });
                }
            });
        },

    }));
});