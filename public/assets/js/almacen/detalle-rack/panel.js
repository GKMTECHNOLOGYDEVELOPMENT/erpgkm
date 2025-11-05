function rackDetalle() {
    return {
        // DATOS ESTÁTICOS PARA EL RACK - CON MÁS UBICACIONES
        rack: {
            nombre: 'PANEL-001',
            sede: 'Principal',
            niveles: [
                {
                    numero: 2, // Nivel 2 (arriba)
                    ubicaciones: [
                        {
                            codigo: 'U005',
                            productos: [
                                {
                                    nombre: 'PANEL SAMSUNG',
                                    cantidad: 15,
                                    categoria: 'Televisores',
                                    subcategoria: 'QLED',
                                    pulgadas: '55"',
                                },
                            ],
                            cantidad_total: 15,
                            capacidad: 20,
                            estado: 'medio',
                            categorias_acumuladas: 'Televisores',
                            tiempo: '01:10',
                            pulgadas_ubicacion: '55"', // Medida específica de esta ubicación
                        },
                        {
                            codigo: 'U006',
                            productos: [
                                {
                                    nombre: 'PANEL SONY',
                                    cantidad: 12,
                                    categoria: 'Televisores',
                                    subcategoria: 'LED',
                                    pulgadas: '50"',
                                },
                            ],
                            cantidad_total: 12,
                            capacidad: 20,
                            estado: 'medio',
                            categorias_acumuladas: 'Televisores',
                            tiempo: '00:45',
                            pulgadas_ubicacion: '50"',
                        },
                        {
                            codigo: 'U007',
                            productos: [
                                {
                                    nombre: 'PANEL TCL',
                                    cantidad: 11,
                                    categoria: 'Televisores',
                                    subcategoria: '4K',
                                    pulgadas: '43"',
                                },
                            ],
                            cantidad_total: 11,
                            capacidad: 20,
                            estado: 'bajo',
                            categorias_acumuladas: 'Televisores',
                            tiempo: '00:30',
                            pulgadas_ubicacion: '43"',
                        },
                        {
                            codigo: 'U008',
                            productos: [
                                {
                                    nombre: 'PANEL PANASONIC',
                                    cantidad: 9,
                                    categoria: 'Televisores',
                                    subcategoria: 'SMART TV',
                                    pulgadas: '58"',
                                },
                            ],
                            cantidad_total: 9,
                            capacidad: 20,
                            estado: 'bajo',
                            categorias_acumuladas: 'Televisores',
                            tiempo: '02:15',
                            pulgadas_ubicacion: '58"',
                        },
                        {
                            codigo: 'U009',
                            productos: [],
                            cantidad_total: 0,
                            capacidad: 20,
                            estado: null,
                            tiempo: '--:--',
                            pulgadas_ubicacion: '65"', // Ubicación vacía pero con medida asignada
                        },
                        {
                            codigo: 'U010',
                            productos: [],
                            cantidad_total: 0,
                            capacidad: 20,
                            estado: null,
                            tiempo: '--:--',
                            pulgadas_ubicacion: '75"',
                        },
                    ],
                },
                {
                    numero: 1, // Nivel 1 (abajo)
                    ubicaciones: [
                        {
                            codigo: 'U001',
                            productos: [
                                {
                                    nombre: 'PANEL SAMSUNG THE FRAME',
                                    cantidad: 18,
                                    categoria: 'Televisores',
                                    subcategoria: 'QLED',
                                    pulgadas: '65"',
                                },
                            ],
                            cantidad_total: 18,
                            capacidad: 20,
                            estado: 'alto',
                            categorias_acumuladas: 'Televisores',
                            tiempo: '00:30',
                            pulgadas_ubicacion: '65"',
                        },
                        {
                            codigo: 'U002',
                            productos: [
                                {
                                    nombre: 'PANEL SONY MASTER',
                                    cantidad: 14,
                                    categoria: 'Televisores',
                                    subcategoria: 'OLED',
                                    pulgadas: '77"',
                                },
                            ],
                            cantidad_total: 14,
                            capacidad: 20,
                            estado: 'medio',
                            categorias_acumuladas: 'Televisores',
                            tiempo: '01:45',
                            pulgadas_ubicacion: '77"',
                        },
                        {
                            codigo: 'U003',
                            productos: [
                                {
                                    nombre: 'PANEL TCL MINI LED',
                                    cantidad: 7,
                                    categoria: 'Televisores',
                                    subcategoria: 'MINI LED',
                                    pulgadas: '75"',
                                },
                            ],
                            cantidad_total: 7,
                            capacidad: 20,
                            estado: 'bajo',
                            categorias_acumuladas: 'Televisores',
                            tiempo: '00:20',
                            pulgadas_ubicacion: '75"',
                        },
                        {
                            codigo: 'U004',
                            productos: [],
                            cantidad_total: 0,
                            capacidad: 20,
                            estado: null,
                            tiempo: '--:--',
                            pulgadas_ubicacion: '85"',
                        },
                        {
                            codigo: 'U011',
                            productos: [
                                {
                                    nombre: 'PANEL HISENSE LASER',
                                    cantidad: 9,
                                    categoria: 'Televisores',
                                    subcategoria: 'LASER TV',
                                    pulgadas: '100"',
                                },
                            ],
                            cantidad_total: 9,
                            capacidad: 20,
                            estado: 'bajo',
                            categorias_acumuladas: 'Televisores',
                            tiempo: '00:55',
                            pulgadas_ubicacion: '100"',
                        },
                        {
                            codigo: 'U012',
                            productos: [
                                {
                                    nombre: 'PANEL SAMSUNG NEO QLED',
                                    cantidad: 11,
                                    categoria: 'Televisores',
                                    subcategoria: 'NEO QLED',
                                    pulgadas: '85"',
                                },
                            ],
                            cantidad_total: 11,
                            capacidad: 20,
                            estado: 'medio',
                            categorias_acumuladas: 'Televisores',
                            tiempo: '01:20',
                            pulgadas_ubicacion: '85"',
                        },
                    ],
                },
            ],
        },
        // FUNCIONES AUXILIARES
        getUbicacion(codigo) {
            for (let nivel of this.rack.niveles) {
                const ubi = nivel.ubicaciones.find((u) => u.codigo === codigo);
                if (ubi) return ubi;
            }
            return null;
        },

        getUbicacionesOcupadas(nivelNumero) {
            const nivel = this.rack.niveles.find((n) => n.numero === nivelNumero);
            return nivel ? nivel.ubicaciones.filter((u) => u.productos && u.productos.length > 0).length : 0;
        },

        getStats() {
            let ocupadas = 0;
            let vacias = 0;
            let total = 0;

            this.rack.niveles.forEach((nivel) => {
                nivel.ubicaciones.forEach((ubi) => {
                    total++;
                    if (ubi.productos && ubi.productos.length > 0) {
                        ocupadas++;
                    } else {
                        vacias++;
                    }
                });
            });

            return {
                ocupadas,
                vacias,
                total,
            };
        },

        getColorEstado(estado) {
            switch (estado) {
                case 'bajo':
                    return 'background-color: #22c55e';
                case 'medio':
                    return 'background-color: #facc15';
                case 'alto':
                    return 'background-color: #f97316';
                case 'muy_alto':
                    return 'background-color: #ef4444';
                default:
                    return '';
            }
        },

        // FUNCIONES EXISTENTES (simplificadas para modo estático)
        init() {
            console.log('Rack Panel inicializado - Modo Estático');

            // Inicializar Swiper después de que Alpine.js haya renderizado el DOM
            this.$nextTick(() => {
                this.inicializarSwiper();
            });
        },

        inicializarSwiper() {
            // Inicializar Swiper para el nivel 2
            const swiperRackNivel2 = new Swiper('#swiperRackNivel2', {
                navigation: {
                    nextEl: '.swiper-button-next-rack',
                    prevEl: '.swiper-button-prev-rack',
                },
                pagination: {
                    el: '.swiper-pagination-rack',
                    clickable: true,
                },
                slidesPerView: 1,
                spaceBetween: 20,
                breakpoints: {
                    1024: {
                        slidesPerView: 1,
                        spaceBetween: 30,
                    },
                    768: {
                        slidesPerView: 1,
                        spaceBetween: 20,
                    },
                    320: {
                        slidesPerView: 1,
                        spaceBetween: 10,
                    },
                },
            });

            // Inicializar Swiper para el nivel 1
            const swiperRackNivel1 = new Swiper('#swiperRackNivel1', {
                navigation: {
                    nextEl: '.swiper-button-next-rack',
                    prevEl: '.swiper-button-prev-rack',
                },
                pagination: {
                    el: '.swiper-pagination-rack',
                    clickable: true,
                },
                slidesPerView: 1,
                spaceBetween: 20,
                breakpoints: {
                    1024: {
                        slidesPerView: 1,
                        spaceBetween: 30,
                    },
                    768: {
                        slidesPerView: 1,
                        spaceBetween: 20,
                    },
                    320: {
                        slidesPerView: 1,
                        spaceBetween: 10,
                    },
                },
            });
        },

        manejarClickUbicacion(ubi) {
            if (!ubi) return;

            console.log('Click en ubicación:', ubi.codigo);

            // Buscar el modal de forma más robusta
            const modalElement = document.querySelector('[x-data="modalDetalleUbicacion()"]');

            if (modalElement && modalElement.__x) {
                // Alpine.js v3
                const modalComponent = modalElement.__x;
                modalComponent.abrirModal(ubi);
            } else if (modalElement && modalElement._x_dataStack) {
                // Alpine.js v2
                const modalComponent = modalElement._x_dataStack[0];
                modalComponent.abrirModal(ubi);
            } else {
                // Fallback: usar Alpine global
                console.warn('Modal component not found, trying Alpine global');
                const alpineData = Alpine.$data(modalElement);
                if (alpineData) {
                    alpineData.abrirModal(ubi);
                } else {
                    console.error('No se pudo encontrar el componente del modal');
                }
            }
        },

        cambiarRack(direccion) {
            // Simular cambio de rack
            console.log('Cambiando rack:', direccion);
            alert(`Funcionalidad de ${direccion} rack - En modo estático`);
        },

        // MODALES Y ESTADOS (se mantienen para compatibilidad)
        modal: {
            open: false,
            ubi: {},
        },
        modoReubicacion: {
            activo: false,
            producto: '',
        },
        modalReubicacionRack: {
            open: false,
        },
        modalAgregarProducto: {
            open: false,
        },
        modalHistorial: {
            open: false,
        },
        modalReubicacion: {
            open: false,
        },

        // FUNCIONES DE LOS MODALES (simplificadas)
        cancelarReubicacion() {
            this.modoReubicacion.activo = false;
            this.modalReubicacion.open = false;
        },

        abrirModalAgregarProducto(ubi) {
            alert('Modal agregar producto - En modo estático');
        },

        abrirHistorial(ubi) {
            alert('Modal historial - En modo estático');
        },
    };
}

// Modal para Detalles de Ubicación - VERSIÓN CORREGIDA
function modalDetalleUbicacion() {
    return {
        open: false,
        ubicacion: null,

        init() {
            console.log('Modal de detalle de ubicación inicializado');
        },

        // Abrir modal con datos de la ubicación
        abrirModal(ubicacionData) {
            this.ubicacion = ubicacionData;
            this.open = true;
            document.body.style.overflow = 'hidden';
            console.log('Modal abierto para:', ubicacionData?.codigo);
        },

        // Cerrar modal
        closeModal() {
            this.open = false;
            this.ubicacion = null;
            document.body.style.overflow = '';
        },

        // Calcular porcentaje de ocupación
        calcularPorcentaje(ubicacion) {
            if (!ubicacion || !ubicacion.capacidad || ubicacion.capacidad === 0) return 0;
            return Math.round((ubicacion.cantidad_total / ubicacion.capacidad) * 100);
        },

        // Obtener color según el estado
        getEstadoColor(estado) {
            const colores = {
                bajo: 'text-green-600',
                medio: 'text-yellow-600',
                alto: 'text-orange-600',
                muy_alto: 'text-red-600',
                null: 'text-gray-600',
            };
            return colores[estado] || colores['null'];
        },

        // Obtener texto del estado
        getEstadoTexto(estado) {
            const textos = {
                bajo: 'Bajo',
                medio: 'Medio',
                alto: 'Alto',
                muy_alto: 'Muy Alto',
                null: 'Vacío',
            };
            return textos[estado] || textos['null'];
        },

        // Obtener color del porcentaje
        getPorcentajeColor(ubicacion) {
            const porcentaje = this.calcularPorcentaje(ubicacion);
            if (porcentaje < 50) return 'text-green-600';
            if (porcentaje < 80) return 'text-yellow-600';
            return 'text-red-600';
        },

        // Obtener color de la barra de progreso
        getProgressBarColor(ubicacion) {
            const porcentaje = this.calcularPorcentaje(ubicacion);
            if (porcentaje < 50) return 'bg-success';
            if (porcentaje < 80) return 'bg-warning';
            return 'bg-danger';
        },

        // Obtener las pulgadas de la ubicación
        getPulgadasUbicacion(ubicacion) {
            // Si la ubicación tiene pulgadas definidas
            if (ubicacion?.pulgadas_ubicacion) {
                return ubicacion.pulgadas_ubicacion;
            }
            // Si tiene productos, tomar la medida del primer producto
            if (ubicacion?.productos?.length > 0) {
                return ubicacion.productos[0].pulgadas;
            }
            // Si está vacía, mostrar "N/A"
            return 'N/A';
        },
    };
}

// Inicializar Alpine.js cuando el documento esté listo
document.addEventListener('DOMContentLoaded', function () {
    // Esperar a que Alpine.js esté disponible
    if (typeof Alpine !== 'undefined') {
        Alpine.data('modalDetalleUbicacion', modalDetalleUbicacion);
    } else {
        // Fallback si Alpine no está disponible inmediatamente
        const checkAlpine = setInterval(() => {
            if (typeof Alpine !== 'undefined') {
                Alpine.data('modalDetalleUbicacion', modalDetalleUbicacion);
                clearInterval(checkAlpine);
            }
        }, 100);
    }
});

// También inicializar cuando Alpine esté listo
document.addEventListener('alpine:init', () => {
    Alpine.data('modalDetalleUbicacion', modalDetalleUbicacion);
});
