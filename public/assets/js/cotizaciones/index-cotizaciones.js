
function cotizacionesIndex() {
    return {
        cotizaciones: [],
        cotizacionesFiltradas: [],
        searchTerm: '',
        filtroEstado: '',
        filtroMes: '',
        paginaActual: 1,
        itemsPorPagina: 10,
        stats: {
            total: 0,
            aprobadas: 0,
            pendientes: 0,
            vencidas: 0
        },

        init() {
            this.cargarCotizaciones();
        },

        async cargarCotizaciones() {
            try {
                // Simulación de datos - reemplazar con llamada real a tu API
                this.cotizaciones = [{
                    id: 1,
                    cotizacionNo: 'COT-2024-001',
                    cliente: {
                        nombre: 'Juan Pérez',
                        empresa: 'Tech Solutions SAC',
                        email: 'juan@techsolutions.com'
                    },
                    fechaEmision: '2024-01-15',
                    validaHasta: '2024-02-15',
                    total: 2500.00,
                    moneda: 'PEN',
                    incluirIGV: true,
                    estado: 'aprobada'
                },
                {
                    id: 2,
                    cotizacionNo: 'COT-2024-002',
                    cliente: {
                        nombre: 'María García',
                        empresa: 'Innovation Corp',
                        email: 'maria@innovation.com'
                    },
                    fechaEmision: '2024-01-20',
                    validaHasta: '2024-02-20',
                    total: 1800.50,
                    moneda: 'USD',
                    incluirIGV: false,
                    estado: 'pendiente'
                },
                {
                    id: 3,
                    cotizacionNo: 'COT-2024-003',
                    cliente: {
                        nombre: 'Carlos López',
                        empresa: 'Digital Systems',
                        email: 'carlos@digitalsystems.com'
                    },
                    fechaEmision: '2024-01-10',
                    validaHasta: '2024-01-25',
                    total: 3200.75,
                    moneda: 'PEN',
                    incluirIGV: true,
                    estado: 'vencida'
                }
                ];

                this.actualizarEstadisticas();
                this.filtrarCotizaciones();
            } catch (error) {
                console.error('Error cargando cotizaciones:', error);
                toastr.error('Error al cargar las cotizaciones');
            }
        },

        filtrarCotizaciones() {
            let filtered = this.cotizaciones;

            // Filtro por búsqueda
            if (this.searchTerm) {
                const term = this.searchTerm.toLowerCase();
                filtered = filtered.filter(cotizacion =>
                    cotizacion.cotizacionNo.toLowerCase().includes(term) ||
                    cotizacion.cliente.nombre.toLowerCase().includes(term) ||
                    cotizacion.cliente.empresa.toLowerCase().includes(term)
                );
            }

            // Filtro por estado
            if (this.filtroEstado) {
                filtered = filtered.filter(cotizacion =>
                    cotizacion.estado === this.filtroEstado
                );
            }

            // Filtro por mes
            if (this.filtroMes) {
                filtered = filtered.filter(cotizacion => {
                    const fecha = new Date(cotizacion.fechaEmision);
                    return (fecha.getMonth() + 1) === parseInt(this.filtroMes);
                });
            }

            this.cotizacionesFiltradas = filtered;
            this.paginaActual = 1;
        },

        actualizarEstadisticas() {
            this.stats.total = this.cotizaciones.length;
            this.stats.aprobadas = this.cotizaciones.filter(c => c.estado === 'aprobada').length;
            this.stats.pendientes = this.cotizaciones.filter(c => c.estado === 'pendiente').length;
            this.stats.vencidas = this.cotizaciones.filter(c => c.estado === 'vencida').length;
        },

        formatearFecha(fecha) {
            return new Date(fecha).toLocaleDateString('es-ES');
        },

        formatearMoneda(monto) {
            return new Intl.NumberFormat('es-PE', {
                style: 'currency',
                currency: 'PEN'
            }).format(monto);
        },

        esVencida(fechaVencimiento) {
            return new Date(fechaVencimiento) < new Date();
        },

        obtenerClaseEstado(estado) {
            const clases = {
                'pendiente': 'status-pendiente',
                'aprobada': 'status-aprobada',
                'rechazada': 'status-rechazada',
                'enviada': 'status-enviada',
                'vencida': 'status-vencida'
            };
            return clases[estado] || 'status-pendiente';
        },

        verCotizacion(id) {
            window.location.href = `/cotizaciones/${id}`;
        },

        editarCotizacion(id) {
            window.location.href = `/cotizaciones/${id}/edit`;
        },

        async generarPDF(id) {
            try {
                toastr.info('Generando PDF...');
                // Lógica para generar PDF
            } catch (error) {
                toastr.error('Error al generar PDF');
            }
        },

        async enviarEmail(id) {
            try {
                toastr.info('Enviando email...');
                // Lógica para enviar email
            } catch (error) {
                toastr.error('Error al enviar email');
            }
        },

        async eliminarCotizacion(id) {
            if (confirm('¿Está seguro de eliminar esta cotización?')) {
                try {
                    toastr.success('Cotización eliminada');
                    this.cotizaciones = this.cotizaciones.filter(c => c.id !== id);
                    this.filtrarCotizaciones();
                    this.actualizarEstadisticas();
                } catch (error) {
                    toastr.error('Error al eliminar cotización');
                }
            }
        },

        get totalPaginas() {
            return Math.ceil(this.cotizacionesFiltradas.length / this.itemsPorPagina);
        }
    }
}
