function rackDetalle() {
    return {
        // DATOS DINÁMICOS DESDE EL CONTROLADOR
        rack: rackData,
        todosRacks: todosRacks,
        rackActual: rackActual,
        sedeActual: sedeActual,

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
            return nivel ? nivel.ubicaciones.filter((u) => u.estado !== 'vacio').length : 0;
        },

        getStats() {
            let ocupadas = 0;
            let vacias = 0;
            let total = 0;

            this.rack.niveles.forEach((nivel) => {
                nivel.ubicaciones.forEach((ubi) => {
                    total++;
                    if (ubi.estado !== 'vacio') {
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
                case 'vacio':
                    return 'background-color: #6b7280';
                default:
                    return 'background-color: #6b7280';
            }
        },

        // Agrupar ubicaciones en slides
        agruparUbicacionesEnSlides(ubicaciones, ubicacionesPorSlide) {
            const slides = [];
            for (let i = 0; i < ubicaciones.length; i += ubicacionesPorSlide) {
                slides.push(ubicaciones.slice(i, i + ubicacionesPorSlide));
            }
            return slides;
        },

        // Agrupar ubicaciones en grupos (para mostrar 2 por grupo)
        agruparUbicacionesEnGrupos(ubicaciones, ubicacionesPorGrupo) {
            const grupos = [];
            for (let i = 0; i < ubicaciones.length; i += ubicacionesPorGrupo) {
                grupos.push(ubicaciones.slice(i, i + ubicacionesPorGrupo));
            }
            return grupos;
        },

        // FUNCIONES EXISTENTES (actualizadas para datos dinámicos)
        init() {
            console.log('Rack Panel inicializado - Datos Dinámicos', this.rack);

            // Inicializar Swiper después de que Alpine.js haya renderizado el DOM
            this.$nextTick(() => {
                this.inicializarSwipers();
            });
        },

        inicializarSwipers() {
            // Inicializar Swiper para cada nivel
            this.rack.niveles.forEach(nivel => {
                const swiperId = `#swiperRackNivel${nivel.numero}`;
                if (document.querySelector(swiperId)) {
                    new Swiper(swiperId, {
                        navigation: {
                            nextEl: `${swiperId} .swiper-button-next-rack`,
                            prevEl: `${swiperId} .swiper-button-prev-rack`,
                        },
                        pagination: {
                            el: `${swiperId} .swiper-pagination-rack`,
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
                }
            });
        },

        manejarClickUbicacion(ubi) {
            if (!ubi) return;

            console.log('Click en ubicación:', ubi.codigo, ubi);

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
            const racks = this.todosRacks;
            const currentIndex = racks.indexOf(this.rackActual);
            let newIndex;

            if (direccion === 'prev') {
                newIndex = currentIndex > 0 ? currentIndex - 1 : racks.length - 1;
            } else {
                newIndex = currentIndex < racks.length - 1 ? currentIndex + 1 : 0;
            }

            const nuevoRack = racks[newIndex];
            if (nuevoRack) {
                // Redirigir al nuevo rack
                const url = new URL(window.location.href);
                url.searchParams.set('rack', nuevoRack);
                window.location.href = url.toString();
            }
        },

        // MODALES Y ESTADOS
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

        // FUNCIONES DE LOS MODALES
        cancelarReubicacion() {
            this.modoReubicacion.activo = false;
            this.modalReubicacion.open = false;
        },

        abrirModalAgregarProducto(ubi) {
            alert('Modal agregar producto - En desarrollo');
        },

        abrirHistorial(ubi) {
            alert('Modal historial - En desarrollo');
        },
    };
}

// Modal para Detalles de Ubicación - ACTUALIZADO PARA DATOS DINÁMICOS
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
            console.log('Modal abierto para:', ubicacionData?.codigo, ubicacionData);
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
                vacio: 'text-gray-600',
                null: 'text-gray-600',
            };
            return colores[estado] || colores['vacio'];
        },

        // Obtener texto del estado
        getEstadoTexto(estado) {
            const textos = {
                bajo: 'Bajo',
                medio: 'Medio',
                alto: 'Alto',
                muy_alto: 'Muy Alto',
                vacio: 'Vacío',
                null: 'Vacío',
            };
            return textos[estado] || textos['vacio'];
        },

        // Obtener color del porcentaje
        getPorcentajeColor(ubicacion) {
            const porcentaje = this.calcularPorcentaje(ubicacion);
            if (porcentaje < 25) return 'text-green-600';
            if (porcentaje < 50) return 'text-yellow-600';
            if (porcentaje < 75) return 'text-orange-600';
            return 'text-red-600';
        },

        // Obtener color de la barra de progreso
        getProgressBarColor(ubicacion) {
            const porcentaje = this.calcularPorcentaje(ubicacion);
            if (porcentaje < 25) return 'bg-success';
            if (porcentaje < 50) return 'bg-warning';
            if (porcentaje < 75) return 'bg-orange-500';
            return 'bg-danger';
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