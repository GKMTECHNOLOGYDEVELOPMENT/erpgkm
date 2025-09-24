        document.addEventListener('alpine:init', () => {
            Alpine.data('comprasList', () => ({
                compras: [],
                loading: false,
                error: null,
                estadoModalOpen: false,
                selectedCompra: null,
                nuevoEstado: 'pendiente',
                updatingEstado: false,
                
                filters: {
                    fecha_inicio: '',
                    fecha_fin: '',
                    q: '',
                    estado: '', // Nuevo filtro
                },
                pagination: {
                    current_page: 1,
                    last_page: 1,
                    per_page: 10,
                    total: 0,
                    from: 0,
                    to: 0,
                },

                init() {
                    this.loadCompras();
                },

                async loadCompras() {
                    this.loading = true;
                    this.error = null;

                    try {
                        const clean = {};
                        if (this.filters.fecha_inicio) clean.fecha_inicio = this.filters.fecha_inicio;
                        if (this.filters.fecha_fin) clean.fecha_fin = this.filters.fecha_fin;
                        if (this.filters.q && this.filters.q.trim().length >= 3) clean.q = this.filters.q.trim();
                        if (this.filters.estado) clean.estado = this.filters.estado; // Nuevo filtro

                        const params = new URLSearchParams({
                            page: this.pagination.current_page,
                            per_page: this.pagination.per_page,
                            ...clean,
                        });

                        const response = await fetch(`/compras/data?${params}`);

                        if (!response.ok) {
                            throw new Error('Error al cargar los datos');
                        }

                        const data = await response.json();

                        if (data.error) {
                            throw new Error(data.error);
                        }

                        this.compras = data.data;
                        this.pagination = data.pagination;
                    } catch (error) {
                        console.error('Error loading compras:', error);
                        this.error = error.message || 'Error al cargar las compras';
                    } finally {
                        this.loading = false;
                    }
                },

                buscar() {
                    this.pagination.current_page = 1;
                    this.loadCompras();
                },

                // Métodos para los estados
                getEstadoText(estado) {
                    const estados = {
                        'pendiente': 'Pendiente',
                        'recibido': 'Recibido',
                        'enviado_almacen': 'Enviado Almacén',
                        'anulado': 'Anulado'
                    };
                    return estados[estado] || estado;
                },

                getEstadoBadgeClass(estado) {
                    const classes = {
                        'pendiente': 'bg-yellow-100 text-yellow-800',
                        'recibido': 'bg-green-100 text-green-800',
                        'enviado_almacen': 'bg-blue-100 text-blue-800',
                        'anulado': 'bg-red-100 text-red-800'
                    };
                    return classes[estado] || 'bg-gray-100 text-gray-800';
                },

                openEstadoModal(compra) {
                    this.selectedCompra = compra;
                    this.nuevoEstado = compra.estado;
                    this.estadoModalOpen = true;
                },

                closeEstadoModal() {
                    this.estadoModalOpen = false;
                    this.selectedCompra = null;
                    this.nuevoEstado = 'pendiente';
                    this.updatingEstado = false;
                },

                async updateEstado() {
                    if (!this.selectedCompra) return;

                    this.updatingEstado = true;

                    try {
                        const response = await fetch(`/compras/${this.selectedCompra.idCompra}/estado`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                estado: this.nuevoEstado
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            // Actualizar el estado en la lista local
                            const compraIndex = this.compras.findIndex(c => c.idCompra === this.selectedCompra.idCompra);
                            if (compraIndex !== -1) {
                                this.compras[compraIndex].estado = this.nuevoEstado;
                            }
                            
                            this.closeEstadoModal();
                            
                            // Mostrar mensaje de éxito
                            this.showNotification('Estado actualizado correctamente', 'success');
                        } else {
                            throw new Error(data.message || 'Error al actualizar el estado');
                        }
                    } catch (error) {
                        console.error('Error updating estado:', error);
                        this.showNotification(error.message, 'error');
                    } finally {
                        this.updatingEstado = false;
                    }
                },

                showNotification(message, type = 'info') {
                    // Implementar notificación (puedes usar Toast, SweetAlert, etc.)
                    alert(`${type.toUpperCase()}: ${message}`);
                },

                // ... resto de tus métodos existentes
                detallesCompra(idCompra) {
                    window.location.href = `/compras/${idCompra}/detalles`;
                },

                imprimirFactura(idCompra) {
                    window.location.href = `/compras/${idCompra}/factura`;
                },

                imprimirTicket(idCompra) {
                    window.location.href = `/compras/${idCompra}/ticket`;
                },

                handleDateChange(field) {
                    if (this.filters[field]) {
                        const date = new Date(this.filters[field]);
                        if (isNaN(date.getTime())) {
                            this.error = 'Fecha inválida';
                            this.filters[field] = '';
                            return;
                        }
                    }
                    this.error = null;
                    this.loadCompras();
                },

                formatDate(dateString) {
                    if (!dateString) return 'N/A';
                    return new Date(dateString).toLocaleDateString('es-ES');
                },

                formatCurrency(amount) {
                    if (!amount) return 'S/ 0.00';
                    return new Intl.NumberFormat('es-PE', {
                        style: 'currency',
                        currency: 'PEN',
                    }).format(amount);
                },

                previousPage() {
                    if (this.pagination.current_page > 1) {
                        this.pagination.current_page--;
                        this.loadCompras();
                    }
                },

                nextPage() {
                    if (this.pagination.current_page < this.pagination.last_page) {
                        this.pagination.current_page++;
                        this.loadCompras();
                    }
                },

                goToPage(page) {
                    this.pagination.current_page = page;
                    this.loadCompras();
                },

                getPages() {
                    const pages = [];
                    const maxPages = 5;
                    const startPage = Math.max(1, this.pagination.current_page - Math.floor(maxPages / 2));
                    const endPage = Math.min(this.pagination.last_page, startPage + maxPages - 1);

                    for (let i = startPage; i <= endPage; i++) {
                        pages.push(i);
                    }

                    return pages;
                },
            }));
        });
