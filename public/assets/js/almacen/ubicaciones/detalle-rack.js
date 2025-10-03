function rackDetalle() {
    return {
        idxRack: 0,
        swipers: [],
        racks: [
            {
                nombre: 'A',
                niveles: [
                    {
                        ubicaciones: [
                            {
                                codigo: 'A01',
                                producto: 'Laptop Dell XPS',
                                cantidad: 5,
                                estado: 'alto',
                                fecha: '2025-09-01',
                                historial: [
                                    { fecha: '2025-08-20', producto: 'Laptop Dell XPS', cantidad: 5, tipo: 'entrada' },
                                    { fecha: '2025-08-25', producto: 'Laptop Dell XPS', cantidad: 2, tipo: 'salida' },
                                    { fecha: '2025-09-01', producto: 'Laptop Dell XPS', cantidad: 5, tipo: 'entrada' },
                                ],
                            },
                            {
                                codigo: 'A02',
                                producto: null,
                                cantidad: 0,
                                estado: 'vacio',
                                historial: [{ fecha: '2025-08-20', producto: 'Laptop Dell XPS', cantidad: 5, tipo: 'salida' }],
                            },
                            {
                                codigo: 'A03',
                                producto: 'Mouse Logitech',
                                cantidad: 30,
                                estado: 'medio',
                                fecha: '2025-09-15',
                                historial: [
                                    { fecha: '2025-08-10', producto: 'Mouse Logitech', cantidad: 10, tipo: 'salida' },
                                    { fecha: '2025-08-18', producto: 'Teclado Gaming', cantidad: 3, tipo: 'reubicacion' },
                                ],
                            },
                            {
                                codigo: 'A04',
                                producto: 'Teclado Gaming',
                                cantidad: 8,
                                estado: 'bajo',
                                fecha: '2025-09-10',
                                historial: [
                                    { fecha: '2025-08-05', producto: 'Mouse Logitech', cantidad: 20, tipo: 'entrada' },
                                    { fecha: '2025-08-28', producto: 'Mouse Logitech', cantidad: 10, tipo: 'entrada' },
                                    { fecha: '2025-09-15', producto: 'Mouse Logitech', cantidad: 5, tipo: 'salida' },
                                ],
                            },
                            {
                                codigo: 'A05',
                                producto: null,
                                cantidad: 0,
                                estado: 'vacio',
                                historial: [
                                    { fecha: '2025-08-12', producto: 'Teclado Gaming', cantidad: 10, tipo: 'entrada' },
                                    { fecha: '2025-09-10', producto: 'Teclado Gaming', cantidad: 2, tipo: 'salida' },
                                ],
                            },
                            {
                                codigo: 'A06',
                                producto: 'Webcam HD',
                                cantidad: 12,
                                estado: 'medio',
                                fecha: '2025-09-18',
                                historial: [
                                    { fecha: '2025-08-30', producto: 'Webcam HD', cantidad: 15, tipo: 'entrada' },
                                    { fecha: '2025-09-18', producto: 'Webcam HD', cantidad: 3, tipo: 'salida' },
                                ],
                            },
                        ],
                    },
                    {
                        ubicaciones: [
                            {
                                codigo: 'A11',
                                producto: 'Teclado Mecánico',
                                cantidad: 15,
                                estado: 'alto',
                                fecha: '2025-09-05',
                                historial: [
                                    { fecha: '2025-08-15', producto: 'Teclado Mecánico', cantidad: 10, tipo: 'entrada' },
                                    { fecha: '2025-09-05', producto: 'Teclado Mecánico', cantidad: 5, tipo: 'entrada' },
                                ],
                            },
                            {
                                codigo: 'A12',
                                producto: 'Monitor 4K',
                                cantidad: 2,
                                estado: 'bajo',
                                fecha: '2025-09-20',
                                historial: [
                                    { fecha: '2025-08-22', producto: 'Monitor 4K', cantidad: 5, tipo: 'entrada' },
                                    { fecha: '2025-09-20', producto: 'Monitor 4K', cantidad: 3, tipo: 'salida' },
                                ],
                            },
                            {
                                codigo: 'A13',
                                producto: 'Auriculares',
                                cantidad: 25,
                                estado: 'medio',
                                fecha: '2025-09-12',
                                historial: [
                                    { fecha: '2025-08-18', producto: 'Auriculares', cantidad: 20, tipo: 'entrada' },
                                    { fecha: '2025-09-12', producto: 'Auriculares', cantidad: 5, tipo: 'entrada' },
                                ],
                            },
                            {
                                codigo: 'A14',
                                producto: null,
                                cantidad: 0,
                                estado: 'vacio',
                                historial: [{ fecha: '2025-08-25', producto: 'Auriculares', cantidad: 10, tipo: 'reubicacion' }],
                            },
                        ],
                    },
                    {
                        ubicaciones: [
                            {
                                codigo: 'A21',
                                producto: 'Cargador USB-C',
                                cantidad: 45,
                                estado: 'alto',
                                fecha: '2025-09-08',
                                historial: [
                                    { fecha: '2025-08-05', producto: 'Cargador USB-C', cantidad: 50, tipo: 'entrada' },
                                    { fecha: '2025-09-08', producto: 'Cargador USB-C', cantidad: 5, tipo: 'salida' },
                                ],
                            },
                            {
                                codigo: 'A22',
                                producto: 'Hub USB',
                                cantidad: 18,
                                estado: 'medio',
                                fecha: '2025-09-16',
                                historial: [
                                    { fecha: '2025-08-28', producto: 'Hub USB', cantidad: 20, tipo: 'entrada' },
                                    { fecha: '2025-09-16', producto: 'Hub USB', cantidad: 2, tipo: 'salida' },
                                ],
                            },
                            {
                                codigo: 'A23',
                                producto: null,
                                cantidad: 0,
                                estado: 'vacio',
                                historial: [],
                            },
                        ],
                    },
                ],
            },
            {
                nombre: 'B',
                niveles: [
                    {
                        ubicaciones: [
                            {
                                codigo: 'B01',
                                producto: 'Cable HDMI',
                                cantidad: 50,
                                estado: 'alto',
                                fecha: '2025-09-12',
                                historial: [
                                    { fecha: '2025-08-10', producto: 'Cable HDMI', cantidad: 30, tipo: 'entrada' },
                                    { fecha: '2025-09-12', producto: 'Cable HDMI', cantidad: 20, tipo: 'entrada' },
                                ],
                            },
                            {
                                codigo: 'B02',
                                producto: null,
                                cantidad: 0,
                                estado: 'vacio',
                                historial: [{ fecha: '2025-08-22', producto: 'Cable HDMI', cantidad: 10, tipo: 'salida' }],
                            },
                            {
                                codigo: 'B03',
                                producto: 'Adaptador VGA',
                                cantidad: 6,
                                estado: 'bajo',
                                fecha: '2025-09-14',
                                historial: [
                                    { fecha: '2025-08-15', producto: 'Adaptador VGA', cantidad: 12, tipo: 'entrada' },
                                    { fecha: '2025-09-14', producto: 'Adaptador VGA', cantidad: 6, tipo: 'salida' },
                                ],
                            },
                        ],
                    },
                    {
                        ubicaciones: [
                            {
                                codigo: 'B11',
                                producto: 'UPS 1500VA',
                                cantidad: 1,
                                estado: 'bajo',
                                fecha: '2025-09-17',
                                historial: [
                                    { fecha: '2025-08-20', producto: 'UPS 1500VA', cantidad: 2, tipo: 'entrada' },
                                    { fecha: '2025-09-17', producto: 'UPS 1500VA', cantidad: 1, tipo: 'salida' },
                                ],
                            },
                            {
                                codigo: 'B12',
                                producto: 'Switch Red',
                                cantidad: 8,
                                estado: 'medio',
                                fecha: '2025-09-19',
                                historial: [
                                    { fecha: '2025-08-28', producto: 'Switch Red', cantidad: 5, tipo: 'entrada' },
                                    { fecha: '2025-09-19', producto: 'Switch Red', cantidad: 3, tipo: 'entrada' },
                                ],
                            },
                        ],
                    },
                ],
            },
        ],
        get rack() {
            return this.racks[this.idxRack];
        },
        modal: {
            open: false,
            ubi: {},
        },
        modalReubicacion: {
            open: false,
            origen: '',
            destino: '',
            producto: '',
            cantidad: 0,
        },
        modoReubicacion: {
            activo: false,
            origen: '',
            producto: '',
            rackOrigen: 0, // 👈 agregar para trackear el rack de origen
        },
        modalSeleccionRack: {
            open: false,
            origen: '',
            producto: '',
            cantidad: 0,
            rackDestino: 0,
        },
        // 👇 aquí agregas el nuevo modalHistorial
        modalHistorial: {
            open: false,
            ubi: {},
        },

        // 👇 método para abrir historial
        abrirHistorial(ubi) {
            this.modalHistorial.ubi = ubi;
            this.modalHistorial.open = true;
        },

        init() {
            // ⚡ Configuración global de toastr
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-top-right',
                timeOut: '3000',
            };

            const params = new URLSearchParams(window.location.search);
            const rackName = params.get('rack');
            if (rackName) {
                const idx = this.racks.findIndex((r) => r.nombre === rackName);
                if (idx !== -1) this.idxRack = idx;
            }
            this.initSwipers();
        },
        initSwipers() {
            this.$nextTick(() => {
                // 🔥 destruir anteriores
                this.swipers.forEach((s) => s.destroy(true, true));
                this.swipers = [];

                document.querySelectorAll('.mySwiper').forEach((el) => {
                    const swiper = new Swiper(el, {
                        slidesPerView: 4,
                        spaceBetween: 20,
                        navigation: {
                            nextEl: el.querySelector('.swiper-button-next'),
                            prevEl: el.querySelector('.swiper-button-prev'),
                        },
                        pagination: {
                            el: el.querySelector('.swiper-pagination'),
                            clickable: true,
                        },
                        breakpoints: {
                            320: { slidesPerView: 1 },
                            640: { slidesPerView: 2 },
                            1024: { slidesPerView: 4 },
                        },
                        observer: true,
                        observeParents: true,
                    });
                    this.swipers.push(swiper);
                });
            });
        },
        prevRack() {
            this.cambiarRack(this.idxRack - 1);
        },

        nextRack() {
            this.cambiarRack(this.idxRack + 1);
        },
        // ✅ Métodos toastr directos
        success(msg) {
            toastr.success(msg);
        },
        error(msg) {
            toastr.error(msg);
        },
        warning(msg) {
            toastr.warning(msg);
        },
        info(msg) {
            toastr.info(msg);
        },
        confirmarReubicacion() {
            const origen = this.obtenerUbicacionPorCodigo(this.modalReubicacion.origen);
            const destino = this.obtenerUbicacionPorCodigo(this.modalReubicacion.destino);

            if (origen && destino) {
                // Guardar datos antes de limpiar
                const productoMovido = origen.producto;
                const cantidadMovida = origen.cantidad;
                const estadoMovido = origen.estado;

                // Mover a destino
                destino.producto = productoMovido;
                destino.cantidad = cantidadMovida;
                destino.estado = estadoMovido;
                destino.fecha = new Date().toISOString().split('T')[0];

                // Agregar historial al destino
                destino.historial.unshift({
                    fecha: new Date().toISOString().split('T')[0],
                    producto: productoMovido,
                    cantidad: cantidadMovida,
                    tipo: 'reubicacion_entrada',
                    desde: this.modalReubicacion.origen,
                });

                // Limpiar origen y agregar historial (sin borrar historial existente)
                origen.historial.unshift({
                    fecha: new Date().toISOString().split('T')[0],
                    producto: productoMovido,
                    cantidad: cantidadMovida,
                    tipo: 'reubicacion_salida',
                    hacia: this.modalReubicacion.destino,
                });

                origen.producto = null;
                origen.cantidad = 0;
                origen.estado = 'vacio';
                origen.fecha = null;

                this.success('Producto reubicado exitosamente');
            } else {
                this.error('Error al reubicar');
            }

            this.cancelarReubicacion();
        },

        // 👇 Iniciar reubicación entre racks
        // 👇 Iniciar reubicación entre racks - VERSIÓN CORREGIDA
        iniciarReubicacionRack(ubi) {
            if (!ubi.producto) return;

            this.modoReubicacion.activo = true;
            this.modoReubicacion.origen = ubi.codigo;
            this.modoReubicacion.producto = ubi.producto;
            this.modoReubicacion.rackOrigen = this.idxRack;
            this.modal.open = false;

            // Abrir modal de selección de rack destino
            this.modalSeleccionRack.origen = ubi.codigo;
            this.modalSeleccionRack.producto = ubi.producto;
            this.modalSeleccionRack.cantidad = ubi.cantidad;
            this.modalSeleccionRack.rackDestino = (this.idxRack + 1) % this.racks.length; // Selección por defecto
            this.modalSeleccionRack.open = true;

            this.info('Selecciona el rack destino');
        },

        // 👇 Método cuando se selecciona un rack diferente - VERSIÓN CORREGIDA
        seleccionarRackDestino(idxRackDestino) {
            // Asegurar que el índice esté dentro de los límites
            idxRackDestino = (idxRackDestino + this.racks.length) % this.racks.length;

            if (idxRackDestino === this.modoReubicacion.rackOrigen) {
                // Si es el mismo rack, usar la reubicación normal
                this.modoReubicacion.activo = true;
                this.idxRack = idxRackDestino;
                this.modalSeleccionRack.open = false;
                this.info('Selecciona la ubicación destino en este mismo rack');
                return;
            }

            // Para racks diferentes, abrir modal de confirmación
            this.modalSeleccionRack.open = true;
            this.modalSeleccionRack.origen = this.modoReubicacion.origen;
            this.modalSeleccionRack.producto = this.modoReubicacion.producto;
            this.modalSeleccionRack.rackDestino = idxRackDestino;

            const ubicacionOrigen = this.obtenerUbicacionPorCodigo(this.modoReubicacion.origen);
            if (ubicacionOrigen) {
                this.modalSeleccionRack.cantidad = ubicacionOrigen.cantidad;
            }
        },

        // 👇 Método para cambiar de rack con soporte para reubicación
        cambiarRack(nuevoIdx) {
            if (this.modoReubicacion.activo) {
                // Si estamos en modo reubicación, usar el método de selección
                this.seleccionarRackDestino(nuevoIdx);
            } else {
                // Navegación normal
                this.idxRack = (nuevoIdx + this.racks.length) % this.racks.length;
                this.$nextTick(() => {
                    this.initSwipers(); // 👈 Agregar esta línea
                });
            }
        },

        // 👇 Confirmar reubicación entre racks - VERSIÓN CORREGIDA
        confirmarReubicacionRack() {
            const rackOrigenIdx = this.modoReubicacion.rackOrigen;
            const rackDestinoIdx = this.modalSeleccionRack.rackDestino;

            if (rackOrigenIdx === rackDestinoIdx) {
                this.modoReubicacion.activo = true;
                this.modalSeleccionRack.open = false;
                this.info('Modo reubicación activado. Selecciona la ubicación destino');
                return;
            }

            // Cambiar al rack destino
            this.idxRack = rackDestinoIdx;
            this.modalSeleccionRack.open = false;

            // Reinicializar swipers después del cambio
            this.$nextTick(() => {
                this.initSwipers();
            });

            this.info(`Selecciona la ubicación destino en el rack ${this.rack.nombre}`);
        },
        // 👇 Método para completar la reubicación entre racks
        // 👇 Método para completar la reubicación entre racks - VERSIÓN MEJORADA
        completarReubicacionRack(ubicacionDestino) {
            const rackOrigenIdx = this.modoReubicacion.rackOrigen;
            const rackDestinoIdx = this.idxRack;
            const codigoOrigen = this.modoReubicacion.origen;

            // Obtener ubicaciones
            const ubicacionOrigen = this.obtenerUbicacionPorCodigoYrack(codigoOrigen, rackOrigenIdx);

            if (!ubicacionOrigen || !ubicacionDestino) {
                this.error('Error en la reubicación');
                return;
            }

            if (ubicacionDestino.producto) {
                this.error('La ubicación destino ya está ocupada');
                return;
            }

            // Guardar datos del producto antes de limpiar
            const productoMovido = ubicacionOrigen.producto;
            const cantidadMovida = ubicacionOrigen.cantidad;
            const estadoMovido = ubicacionOrigen.estado;

            // Realizar la reubicación - DESTINO
            ubicacionDestino.producto = productoMovido;
            ubicacionDestino.cantidad = cantidadMovida;
            ubicacionDestino.estado = estadoMovido;
            ubicacionDestino.fecha = new Date().toISOString().split('T')[0];

            // Agregar al historial del destino
            ubicacionDestino.historial.unshift({
                fecha: new Date().toISOString().split('T')[0],
                producto: productoMovido,
                cantidad: cantidadMovida,
                tipo: 'reubicacion_entrada',
                desde: codigoOrigen,
                rack_origen: this.racks[rackOrigenIdx].nombre,
            });

            // Agregar al historial del origen
            ubicacionOrigen.historial.unshift({
                fecha: new Date().toISOString().split('T')[0],
                producto: productoMovido,
                cantidad: cantidadMovida,
                tipo: 'reubicacion_salida',
                hacia: ubicacionDestino.codigo,
                rack_destino: this.racks[rackDestinoIdx].nombre,
            });

            // Limpiar solo los datos del producto, NO el historial
            ubicacionOrigen.producto = null;
            ubicacionOrigen.cantidad = 0;
            ubicacionOrigen.estado = 'vacio';
            ubicacionOrigen.fecha = null;

            // Resetear modo ANTES de cambiar de rack
            this.cancelarReubicacion();

            // Ahora cambiar al rack origen para mostrar que el producto se fue
            this.idxRack = rackOrigenIdx;

            // Reinicializar swipers después del cambio
            this.$nextTick(() => {
                this.initSwipers();
            });

            this.success(`Producto reubicado de ${codigoOrigen} a ${ubicacionDestino.codigo} (Rack ${this.racks[rackDestinoIdx].nombre})`);
        },
        // 👇 Método auxiliar para buscar ubicación en cualquier rack
        obtenerUbicacionPorCodigoYrack(codigo, rackIndex) {
            const rack = this.racks[rackIndex];
            for (const nivel of rack.niveles) {
                for (const ubi of nivel.ubicaciones) {
                    if (ubi.codigo === codigo) {
                        return ubi;
                    }
                }
            }
            return null;
        },

        // 👇 SOLO DEBE HABER UN método manejarClickUbicacion - ELIMINA EL DUPLICADO
        manejarClickUbicacion(ubi) {
            if (this.modoReubicacion.activo) {
                // Verificar si estamos en el mismo rack o diferente
                const mismoRack = this.idxRack === this.modoReubicacion.rackOrigen;

                if (ubi.codigo === this.modoReubicacion.origen && mismoRack) {
                    // Click en la ubicación de origen en el mismo rack: cancelar
                    this.cancelarReubicacion();
                } else if (this.esDestinoValido(ubi)) {
                    if (mismoRack) {
                        // Reubicación en el mismo rack
                        this.modalReubicacion.origen = this.modoReubicacion.origen;
                        this.modalReubicacion.destino = ubi.codigo;

                        const ubicacionOrigen = this.obtenerUbicacionPorCodigo(this.modoReubicacion.origen);
                        if (ubicacionOrigen) {
                            this.modalReubicacion.producto = ubicacionOrigen.producto;
                            this.modalReubicacion.cantidad = ubicacionOrigen.cantidad;
                        }

                        this.modalReubicacion.open = true;
                    } else {
                        // Reubicación entre racks diferentes
                        this.completarReubicacionRack(ubi);
                    }
                }
            } else {
                // Modo normal: mostrar detalles
                this.verDetalle(ubi);
            }
        },

        getEstadoClass(estado) {
            switch (estado) {
                case 'muy_alta': // 75-100%
                    return 'text-white shadow-lg' + ' bg-[#ef4444] shadow-red-500/30'; // rojo
                case 'alta': // 50-74%
                    return 'text-white shadow-lg' + ' bg-[#f97316] shadow-orange-500/30'; // naranja
                case 'media': // 25-49%
                    return 'text-black shadow-lg' + ' bg-[#facc15] shadow-yellow-400/30'; // amarillo
                case 'baja': // 0-24%
                    return 'text-white shadow-lg' + ' bg-[#22c55e] shadow-green-500/30'; // verde
                default:
                    return 'bg-slate-200 text-slate-500 border-2 border-dashed border-slate-400';
            }
        },

        getStats() {
            const todasUbicaciones = this.rack.niveles.flatMap((n) => n.ubicaciones);
            return {
                total: todasUbicaciones.length,
                ocupadas: todasUbicaciones.filter((u) => u.producto).length,
                vacias: todasUbicaciones.filter((u) => !u.producto).length,
            };
        },
        formatFecha(fecha) {
            if (!fecha) return 'Sin registros';
            return new Date(fecha).toLocaleDateString('es-ES', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
            });
        },
        verDetalle(ubi) {
            this.modal.ubi = ubi;
            this.modal.open = true;
        },

        // Funciones para reubicación
        iniciarReubicacion(ubi) {
            if (!ubi.producto) return;

            this.modoReubicacion.activo = true;
            this.modoReubicacion.origen = ubi.codigo;
            this.modoReubicacion.producto = ubi.producto;
            this.modal.open = false;
        },
        cancelarReubicacion() {
            this.modoReubicacion.activo = false;
            this.modoReubicacion.origen = '';
            this.modoReubicacion.producto = '';
            this.modalReubicacion.open = false;
        },
        esDestinoValido(ubi) {
            // Un destino es válido si está vacío o es la misma ubicación de origen
            return !ubi.producto || ubi.codigo === this.modoReubicacion.origen;
        },
        obtenerUbicacionPorCodigo(codigo) {
            for (const nivel of this.rack.niveles) {
                for (const ubi of nivel.ubicaciones) {
                    if (ubi.codigo === codigo) {
                        return ubi;
                    }
                }
            }
            return null;
        },
    };
}
