document.addEventListener('alpine:init', () => {
            Alpine.data('devolucionesList', () => ({
                devoluciones: [],
                loading: false,
                error: null,
                filters: {
                    fecha_inicio: '',
                    fecha_fin: '',
                    q: '',
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
                    this.loadDevoluciones();
                },

                async loadDevoluciones() {
                    this.loading = true;
                    this.error = null;

                    try {
                        // Limpiar filtros - solo enviar si tienen valor
                        const clean = {};
                        if (this.filters.fecha_inicio) clean.fecha_inicio = this.filters.fecha_inicio;
                        if (this.filters.fecha_fin) clean.fecha_fin = this.filters.fecha_fin;
                        if (this.filters.q && this.filters.q.trim().length >= 3) clean.q = this.filters.q.trim();

                        const params = new URLSearchParams({
                            page: this.pagination.current_page,
                            per_page: this.pagination.per_page,
                            ...clean,
                        });

                        const response = await fetch(`/devoluciones-compra/data?${params}`);

                        if (!response.ok) {
                            throw new Error('Error al cargar los datos');
                        }

                        const data = await response.json();

                        if (data.error) {
                            throw new Error(data.error);
                        }

                        this.devoluciones = data.data;
                        this.pagination = data.pagination;
                    } catch (error) {
                        console.error('Error loading devoluciones:', error);
                        this.error = error.message || 'Error al cargar las devoluciones';
                    } finally {
                        this.loading = false;
                    }
                },

                buscar() {
                    // mínimo 3 caracteres para enviar q
                    this.pagination.current_page = 1;
                    this.loadDevoluciones();
                },

                verDetalles(idDevolucionCompra) {
                    // Redireccionar a la página de detalles de devolución
                    window.location.href = `/devoluciones-compra/${idDevolucionCompra}/detalles`;
                },

                imprimirReporte(idDevolucionCompra) {
                    // Redireccionar a la página de reporte
                    window.location.href = `/devoluciones-compra/${idDevolucionCompra}/reporte`;
                },

                handleDateChange(field) {
                    // Validación simple en el cliente
                    if (this.filters[field]) {
                        const date = new Date(this.filters[field]);
                        if (isNaN(date.getTime())) {
                            this.error = 'Fecha inválida';
                            this.filters[field] = '';
                            return;
                        }
                    }

                    this.error = null;
                    this.loadDevoluciones();
                },

                formatDateTime(dateTimeString) {
                    if (!dateTimeString) return 'N/A';
                    const date = new Date(dateTimeString);
                    return date.toLocaleDateString('es-ES') + ' ' + date.toLocaleTimeString('es-ES');
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
                        this.loadDevoluciones();
                    }
                },

                nextPage() {
                    if (this.pagination.current_page < this.pagination.last_page) {
                        this.pagination.current_page++;
                        this.loadDevoluciones();
                    }
                },

                goToPage(page) {
                    this.pagination.current_page = page;
                    this.loadDevoluciones();
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