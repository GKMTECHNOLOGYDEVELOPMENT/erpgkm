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
            venciadas: 0
        },
        loading: false,

        init() {
            this.cargarEstadisticas();
            this.cargarCotizaciones();
        },

        async cargarCotizaciones() {
            this.loading = true;
            try {
                const params = new URLSearchParams();

                if (this.searchTerm) params.append('search', this.searchTerm);
                if (this.filtroEstado) params.append('estado', this.filtroEstado);
                if (this.filtroMes) params.append('mes', this.filtroMes);

                // CORREGIDO: Usar la ruta API
                const response = await fetch(`/api/cotizaciones?${params}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.cotizaciones = data.cotizaciones;
                    this.cotizacionesFiltradas = data.cotizaciones;
                    this.calcularEstadisticasLocales(); // Calcular stats con los datos cargados
                } else {
                    this.mostrarError(data.message || 'Error al cargar cotizaciones');
                }
            } catch (error) {
                console.error('Error cargando cotizaciones:', error);
                this.mostrarError('Error de conexión al cargar las cotizaciones');
            } finally {
                this.loading = false;
            }
        },

        async cargarEstadisticas() {
            try {
                // CORREGIDO: Usar endpoint API de estadísticas
                const response = await fetch('/api/cotizaciones/estadisticas', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.stats = data.stats;
                }
            } catch (error) {
                console.error('Error cargando estadísticas:', error);
                // Si falla, calcular localmente
                this.calcularEstadisticasLocales();
            }
        },

        calcularEstadisticasLocales() {
            const total = this.cotizaciones.length;
            const aprobadas = this.cotizaciones.filter(c => c.estado === 'aprobada').length;
            const pendientes = this.cotizaciones.filter(c => c.estado === 'pendiente').length;

            // Calcular vencidas (fecha validaHasta menor a hoy)
            const vencidas = this.cotizaciones.filter(c => {
                if (!c.validaHasta) return false;
                return new Date(c.validaHasta) < new Date() && c.estado !== 'aprobada';
            }).length;

            this.stats = {
                total: total,
                aprobadas: aprobadas,
                pendientes: pendientes,
                venciadas: vencidas
            };
        },

        // Método para mostrar errores
        mostrarError(mensaje) {
            if (typeof toastr !== 'undefined') {
                toastr.error(mensaje);
            } else {
                console.error('Error:', mensaje);
                alert('Error: ' + mensaje);
            }
        },

        mostrarInfo(mensaje) {
            if (typeof toastr !== 'undefined') {
                toastr.info(mensaje);
            } else {
                console.info('Info:', mensaje);
            }
        },

        mostrarExito(mensaje) {
            if (typeof toastr !== 'undefined') {
                toastr.success(mensaje);
            } else {
                console.success('Éxito:', mensaje);
            }
        },

        filtrarCotizaciones() {
            this.cargarCotizaciones();
        },

        limpiarFiltros() {
            this.searchTerm = '';
            this.filtroEstado = '';
            this.filtroMes = '';
            this.cargarCotizaciones();
        },

        formatearFecha(fecha) {
            if (!fecha) return 'N/A';
            return new Date(fecha).toLocaleDateString('es-ES', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
            });
        },

        formatearMoneda(monto) {
            return new Intl.NumberFormat('es-PE', {
                style: 'currency',
                currency: 'PEN',
                minimumFractionDigits: 2
            }).format(monto);
        },

        esVencida(fechaVencimiento) {
            if (!fechaVencimiento) return false;
            return new Date(fechaVencimiento) < new Date();
        },

        obtenerNombreMes(mes) {
            const meses = [
                '', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
            ];
            return meses[parseInt(mes)] || '';
        },

        // CORREGIDO: Rutas web (no API) con prefijo administracion/cotizaciones
        verCotizacion(id) {
            window.location.href = `/administracion/cotizaciones/${id}/detalles`;
        },

        editarCotizacion(id) {
            window.location.href = `/administracion/cotizaciones/${id}/edit`;
        },

        async generarPDF(id) {
            try {
                this.mostrarInfo('Generando PDF...');

                // CORREGIDO: Usar la ruta web para PDF
                window.open(`/administracion/cotizaciones/${id}/pdf`, '_blank');

                this.mostrarExito('PDF generado correctamente');
            } catch (error) {
                console.error('Error al generar PDF:', error);
                this.mostrarError('Error al generar PDF');
            }
        },

        async enviarEmail(id) {
            try {
                const email = prompt('Ingrese el email destinatario:');
                if (!email) return;

                this.mostrarInfo('Enviando email...');

                // CORREGIDO: Usar la ruta web para email
                const response = await fetch(`/administracion/cotizaciones/${id}/enviar-email`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ email: email })
                });

                const data = await response.json();

                if (data.success) {
                    this.mostrarExito(data.message || 'Email enviado correctamente');
                    // Recargar para actualizar estado
                    this.cargarCotizaciones();
                } else {
                    this.mostrarError(data.message || 'Error al enviar email');
                }
            } catch (error) {
                console.error('Error al enviar email:', error);
                this.mostrarError('Error al enviar email');
            }
        },

        async eliminarCotizacion(id) {
            if (!confirm('¿Está seguro de eliminar esta cotización? Esta acción no se puede deshacer.')) {
                return;
            }

            try {
                // CORREGIDO: Usar la ruta web DELETE
                const response = await fetch(`/administracion/cotizaciones/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.mostrarExito(data.message || 'Cotización eliminada correctamente');
                    // Recargar la lista
                    this.cargarCotizaciones();
                    this.cargarEstadisticas();
                } else {
                    this.mostrarError(data.message || 'Error al eliminar cotización');
                }
            } catch (error) {
                console.error('Error al eliminar cotización:', error);
                this.mostrarError('Error al eliminar cotización');
            }
        },

        get totalPaginas() {
            return Math.ceil(this.cotizacionesFiltradas.length / this.itemsPorPagina);
        },

        get cotizacionesPaginadas() {
            const startIndex = (this.paginaActual - 1) * this.itemsPorPagina;
            return this.cotizacionesFiltradas.slice(startIndex, startIndex + this.itemsPorPagina);
        }
    }
}
