document.addEventListener('alpine:init', () => {
    Alpine.data('comprasList', () => ({
        compras: [],
        loading: false,
        error: null,
        filters: {
            fecha_inicio: '',
            fecha_fin: '',
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
                // Limpiar filtros - solo enviar si tienen valor
                const cleanFilters = {};

                if (this.filters.fecha_inicio) {
                    cleanFilters.fecha_inicio = this.filters.fecha_inicio;
                }

                if (this.filters.fecha_fin) {
                    cleanFilters.fecha_fin = this.filters.fecha_fin;
                }

                const params = new URLSearchParams({
                    page: this.pagination.current_page,
                    per_page: this.pagination.per_page,
                    ...cleanFilters,
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

        detallesCompra(idCompra) {
            // Redireccionar a la página de detalles de compra
            window.location.href = `/compras/${idCompra}/detalles`;
        },

        imprimirFactura(idCompra) {
            // Redireccionar a la página de factura
            window.location.href = `/compras/${idCompra}/factura`;
        },

        imprimirTicket(idCompra) {
            // Redireccionar a la página de ticket
            window.location.href = `/compras/${idCompra}/ticket`;
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
