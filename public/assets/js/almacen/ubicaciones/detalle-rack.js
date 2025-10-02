
function rackDetalle() {
    return {
        idxRack: 0,
        swipers: [],
        racks: [{
            nombre: "A",
            niveles: [{
                ubicaciones: [{
                    codigo: "A01",
                    producto: "Laptop Dell XPS",
                    cantidad: 5,
                    estado: "alto",
                    fecha: "2025-09-01",
                    historial: [
                        { fecha: "2025-08-20", producto: "Laptop Dell XPS", cantidad: 5, tipo: "entrada" },
                        { fecha: "2025-08-25", producto: "Laptop Dell XPS", cantidad: 2, tipo: "salida" },
                        { fecha: "2025-09-01", producto: "Laptop Dell XPS", cantidad: 5, tipo: "entrada" }
                    ]
                },
                {
                    codigo: "A02",
                    producto: null,
                    cantidad: 0,
                    estado: "vacio",
                    historial: [
                        { fecha: "2025-08-20", producto: "Laptop Dell XPS", cantidad: 5, tipo: "salida" }
                    ]
                },
                {
                    codigo: "A03",
                    producto: "Mouse Logitech",
                    cantidad: 30,
                    estado: "medio",
                    fecha: "2025-09-15",
                    historial: [
                        { fecha: "2025-08-10", producto: "Mouse Logitech", cantidad: 10, tipo: "salida" },
                        { fecha: "2025-08-18", producto: "Teclado Gaming", cantidad: 3, tipo: "reubicacion" }
                    ]
                },
                {
                    codigo: "A04",
                    producto: "Teclado Gaming",
                    cantidad: 8,
                    estado: "bajo",
                    fecha: "2025-09-10",
                    historial: [
                        { fecha: "2025-08-05", producto: "Mouse Logitech", cantidad: 20, tipo: "entrada" },
                        { fecha: "2025-08-28", producto: "Mouse Logitech", cantidad: 10, tipo: "entrada" },
                        { fecha: "2025-09-15", producto: "Mouse Logitech", cantidad: 5, tipo: "salida" }
                    ]
                },
                {
                    codigo: "A05",
                    producto: null,
                    cantidad: 0,
                    estado: "vacio",
                    historial: [
                        { fecha: "2025-08-12", producto: "Teclado Gaming", cantidad: 10, tipo: "entrada" },
                        { fecha: "2025-09-10", producto: "Teclado Gaming", cantidad: 2, tipo: "salida" }
                    ]

                },
                {
                    codigo: "A06",
                    producto: "Webcam HD",
                    cantidad: 12,
                    estado: "medio",
                    fecha: "2025-09-18",
                    historial: [
                        { fecha: "2025-08-30", producto: "Webcam HD", cantidad: 15, tipo: "entrada" },
                        { fecha: "2025-09-18", producto: "Webcam HD", cantidad: 3, tipo: "salida" }
                    ]
                }
                ]
            },
            {
                ubicaciones: [{
                    codigo: "A11",
                    producto: "Teclado Mecánico",
                    cantidad: 15,
                    estado: "alto",
                    fecha: "2025-09-05",
                    historial: [
                        { fecha: "2025-08-15", producto: "Teclado Mecánico", cantidad: 10, tipo: "entrada" },
                        { fecha: "2025-09-05", producto: "Teclado Mecánico", cantidad: 5, tipo: "entrada" }
                    ]
                },
                {
                    codigo: "A12",
                    producto: "Monitor 4K",
                    cantidad: 2,
                    estado: "bajo",
                    fecha: "2025-09-20",
                    historial: [
                        { fecha: "2025-08-22", producto: "Monitor 4K", cantidad: 5, tipo: "entrada" },
                        { fecha: "2025-09-20", producto: "Monitor 4K", cantidad: 3, tipo: "salida" }
                    ]
                },
                {
                    codigo: "A13",
                    producto: "Auriculares",
                    cantidad: 25,
                    estado: "medio",
                    fecha: "2025-09-12",
                    historial: [
                        { fecha: "2025-08-18", producto: "Auriculares", cantidad: 20, tipo: "entrada" },
                        { fecha: "2025-09-12", producto: "Auriculares", cantidad: 5, tipo: "entrada" }
                    ]
                },
                {
                    codigo: "A14",
                    producto: null,
                    cantidad: 0,
                    estado: "vacio",
                    historial: [
                        { fecha: "2025-08-25", producto: "Auriculares", cantidad: 10, tipo: "reubicacion" }
                    ]
                }
                ]
            },
            {
                ubicaciones: [{
                    codigo: "A21",
                    producto: "Cargador USB-C",
                    cantidad: 45,
                    estado: "alto",
                    fecha: "2025-09-08",
                    historial: [
                        { fecha: "2025-08-05", producto: "Cargador USB-C", cantidad: 50, tipo: "entrada" },
                        { fecha: "2025-09-08", producto: "Cargador USB-C", cantidad: 5, tipo: "salida" }
                    ]

                },
                {
                    codigo: "A22",
                    producto: "Hub USB",
                    cantidad: 18,
                    estado: "medio",
                    fecha: "2025-09-16",
                    historial: [
                        { fecha: "2025-08-28", producto: "Hub USB", cantidad: 20, tipo: "entrada" },
                        { fecha: "2025-09-16", producto: "Hub USB", cantidad: 2, tipo: "salida" }
                    ]
                },
                {
                    codigo: "A23",
                    producto: null,
                    cantidad: 0,
                    estado: "vacio",
                    historial: []
                }
                ]
            }
            ]
        },
        {
            nombre: "B",
            niveles: [{
                ubicaciones: [{
                    codigo: "B01",
                    producto: "Cable HDMI",
                    cantidad: 50,
                    estado: "alto",
                    fecha: "2025-09-12",
                    historial: [
                        { fecha: "2025-08-10", producto: "Cable HDMI", cantidad: 30, tipo: "entrada" },
                        { fecha: "2025-09-12", producto: "Cable HDMI", cantidad: 20, tipo: "entrada" }
                    ]
                },
                {
                    codigo: "B02",
                    producto: null,
                    cantidad: 0,
                    estado: "vacio",
                    historial: [
                        { fecha: "2025-08-22", producto: "Cable HDMI", cantidad: 10, tipo: "salida" }
                    ]
                },
                {
                    codigo: "B03",
                    producto: "Adaptador VGA",
                    cantidad: 6,
                    estado: "bajo",
                    fecha: "2025-09-14",
                    historial: [
                        { fecha: "2025-08-15", producto: "Adaptador VGA", cantidad: 12, tipo: "entrada" },
                        { fecha: "2025-09-14", producto: "Adaptador VGA", cantidad: 6, tipo: "salida" }
                    ]
                }
                ]
            },
            {
                ubicaciones: [{
                    codigo: "B11",
                    producto: "UPS 1500VA",
                    cantidad: 1,
                    estado: "bajo",
                    fecha: "2025-09-17",
                    historial: [
                        { fecha: "2025-08-20", producto: "UPS 1500VA", cantidad: 2, tipo: "entrada" },
                        { fecha: "2025-09-17", producto: "UPS 1500VA", cantidad: 1, tipo: "salida" }
                    ]
                },
                {
                    codigo: "B12",
                    producto: "Switch Red",
                    cantidad: 8,
                    estado: "medio",
                    fecha: "2025-09-19",
                    historial: [
                        { fecha: "2025-08-28", producto: "Switch Red", cantidad: 5, tipo: "entrada" },
                        { fecha: "2025-09-19", producto: "Switch Red", cantidad: 3, tipo: "entrada" }
                    ]
                }
                ]
            }
            ]
        }
        ],
        get rack() {
            return this.racks[this.idxRack];
        },
        modal: {
            open: false,
            ubi: {}
        },
        modalReubicacion: {
            open: false,
            origen: "",
            destino: "",
            producto: "",
            cantidad: 0
        },
        modoReubicacion: {
            activo: false,
            origen: "",
            producto: ""
        },
        // 👇 aquí agregas el nuevo modalHistorial
        modalHistorial: {
            open: false,
            ubi: {}
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
                positionClass: "toast-top-right",
                timeOut: "3000"
            };

            const params = new URLSearchParams(window.location.search);
            const rackName = params.get("rack");
            if (rackName) {
                const idx = this.racks.findIndex(r => r.nombre === rackName);
                if (idx !== -1) this.idxRack = idx;
            }
            this.initSwipers();
        },
        initSwipers() {
            this.$nextTick(() => {
                // 🔥 destruir anteriores
                this.swipers.forEach(s => s.destroy(true, true));
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
                            1024: { slidesPerView: 4 }
                        },
                        observer: true,
                        observeParents: true
                    });
                    this.swipers.push(swiper);
                });
            });
        },
        prevRack() {
            this.idxRack = (this.idxRack - 1 + this.racks.length) % this.racks.length;
            this.initSwipers(); // 👈 siempre reinicia
        },

        nextRack() {
            this.idxRack = (this.idxRack + 1) % this.racks.length;
            this.initSwipers(); // 👈 siempre reinicia
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
                destino.producto = origen.producto;
                destino.cantidad = origen.cantidad;
                destino.estado = origen.estado;
                destino.fecha = new Date().toISOString().split('T')[0];

                origen.producto = null;
                origen.cantidad = 0;
                origen.estado = "vacio";
                origen.fecha = null;

                // ✅ llamado global
                window.toastr.success("Producto reubicado exitosamente");
            } else {
                window.toastr.error("Error al reubicar");
            }

            this.cancelarReubicacion();
        },



        getEstadoClass(estado) {
            switch (estado) {
                case "muy_alta": // 75-100%
                    return "text-white shadow-lg"
                        + " bg-[#ef4444] shadow-red-500/30"; // rojo
                case "alta": // 50-74%
                    return "text-white shadow-lg"
                        + " bg-[#f97316] shadow-orange-500/30"; // naranja
                case "media": // 25-49%
                    return "text-black shadow-lg"
                        + " bg-[#facc15] shadow-yellow-400/30"; // amarillo
                case "baja": // 0-24%
                    return "text-white shadow-lg"
                        + " bg-[#22c55e] shadow-green-500/30"; // verde
                default:
                    return "bg-slate-200 text-slate-500 border-2 border-dashed border-slate-400";
            }
        },



        getStats() {
            const todasUbicaciones = this.rack.niveles.flatMap(n => n.ubicaciones);
            return {
                total: todasUbicaciones.length,
                ocupadas: todasUbicaciones.filter(u => u.producto).length,
                vacias: todasUbicaciones.filter(u => !u.producto).length
            };
        },
        formatFecha(fecha) {
            if (!fecha) return 'Sin registros';
            return new Date(fecha).toLocaleDateString('es-ES', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
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
            this.modoReubicacion.origen = "";
            this.modoReubicacion.producto = "";
            this.modalReubicacion.open = false;
        },
        esDestinoValido(ubi) {
            // Un destino es válido si está vacío o es la misma ubicación de origen
            return !ubi.producto || ubi.codigo === this.modoReubicacion.origen;
        },
        manejarClickUbicacion(ubi) {
            if (this.modoReubicacion.activo) {
                // Estamos en modo reubicación
                if (ubi.codigo === this.modoReubicacion.origen) {
                    // Click en la ubicación de origen: cancelar reubicación
                    this.cancelarReubicacion();
                } else if (this.esDestinoValido(ubi)) {
                    // Click en un destino válido: confirmar reubicación
                    this.modalReubicacion.origen = this.modoReubicacion.origen;
                    this.modalReubicacion.destino = ubi.codigo;

                    // Obtener datos del producto desde la ubicación de origen
                    const ubicacionOrigen = this.obtenerUbicacionPorCodigo(this.modoReubicacion.origen);
                    if (ubicacionOrigen) {
                        this.modalReubicacion.producto = ubicacionOrigen.producto;
                        this.modalReubicacion.cantidad = ubicacionOrigen.cantidad;
                    }

                    this.modalReubicacion.open = true;
                }
            } else {
                // Modo normal: mostrar detalles
                this.verDetalle(ubi);
            }
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
