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

                const response = await fetch(`/api/cotizaciones?${params}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.cotizaciones = data.cotizaciones;
                    this.cotizacionesFiltradas = data.cotizaciones;
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
            }
        },

        // Método para mostrar errores (reemplaza toastr)
        mostrarError(mensaje) {
            // Puedes usar alert temporalmente o implementar tu propio sistema de notificaciones
            console.error('Error:', mensaje);
            alert('Error: ' + mensaje); // Temporal - luego lo mejoramos
        },

        mostrarInfo(mensaje) {
            console.info('Info:', mensaje);
            // Temporal también
        },

        mostrarExito(mensaje) {
            console.success('Éxito:', mensaje);
            // Temporal también
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

        verCotizacion(id) {
            window.location.href = `/administracion/cotizaciones/${id}`;
        },

        editarCotizacion(id) {
            window.location.href = `/administracion/cotizaciones/${id}/edit`;
        },

        async generarPDF(id) {
            try {
                this.mostrarInfo('Generando PDF...');
                
                const response = await fetch(`/administracion/cotizaciones/${id}/pdf`, {
                    method: 'GET'
                });

                if (response.ok) {
                    const blob = await response.blob();
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    
                    const contentDisposition = response.headers.get('Content-Disposition');
                    let filename = `cotizacion-${id}.pdf`;
                    if (contentDisposition) {
                        const filenameMatch = contentDisposition.match(/filename="(.+)"/);
                        if (filenameMatch && filenameMatch.length === 2) {
                            filename = filenameMatch[1];
                        }
                    }
                    
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);
                    
                    this.mostrarExito('PDF generado correctamente');
                } else {
                    this.mostrarError('Error al generar PDF');
                }
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