// ======================== STORE DE ALPINE.JS ========================
document.addEventListener('alpine:init', () => {
    // Store para compartir datos entre componentes
    Alpine.store('rackDetalle', {
        modoMovimiento: {
            activo: false,
            articuloSeleccionado: null,
            cajaSeleccionada: null, // ✅ NUEVO: Para manejar cajas
            ubicacionOrigen: null,
            cantidad: 1,
            observaciones: '',
            ubicacionesDisponibles: [],
            cargandoUbicaciones: false,
            ubicacionDestinoSeleccionada: null,
            moviendoArticulo: false,
            moviendoCaja: false, // ✅ NUEVO: Para mostrar loading en cajas
            tipoMovimiento: 'articulo', // ✅ 'articulo', 'caja', 'articulo_en_caja'
        },

        iniciarMovimiento(articulo, ubicacion, tipo = 'articulo') {
            console.log('=== Store.iniciarMovimiento ===');
            console.log('Artículo:', articulo);
            console.log('Ubicación:', ubicacion);
            console.log('Tipo:', tipo);

            this.modoMovimiento.activo = true;
            this.modoMovimiento.articuloSeleccionado = articulo;
            this.modoMovimiento.ubicacionOrigen = ubicacion;
            this.modoMovimiento.cantidad = 1;
            this.modoMovimiento.observaciones = '';
            this.modoMovimiento.ubicacionesDisponibles = [];
            this.modoMovimiento.ubicacionDestinoSeleccionada = null;
            this.modoMovimiento.moviendoArticulo = false;
            this.modoMovimiento.moviendoCaja = false;
            this.modoMovimiento.tipoMovimiento = tipo;

            // Si es una caja, guardar referencia
            if (tipo === 'caja') {
                this.modoMovimiento.cajaSeleccionada = articulo;
                this.modoMovimiento.cantidad = articulo.cantidad || 1;
            }

            // ✅ AÑADIR: Cargar ubicaciones automáticamente
            this.cargarUbicacionesDisponibles();
        },

        iniciarMovimientoCaja(caja, ubicacion) {
            console.log('=== Store.iniciarMovimientoCaja ===');
            console.log('Caja:', caja);
            console.log('Ubicación:', ubicacion);

            this.iniciarMovimiento(caja, ubicacion, 'caja');
        },

        // ✅ AÑADIR esta función al store
        async cargarUbicacionesDisponibles() {
            const store = this.modoMovimiento;

            if (!store.articuloSeleccionado || !store.ubicacionOrigen) {
                return;
            }

            store.cargandoUbicaciones = true;

            try {
                // Preparar datos según el tipo
                const requestData = {
                    ubicacion_origen_id: store.ubicacionOrigen.id,
                    rack_id: window.rackId || null, // Necesitas definir esto globalmente
                };

                if (store.tipoMovimiento === 'caja') {
                    requestData.articulo_id = store.articuloSeleccionado.idArticulo || store.articuloSeleccionado.id;
                    requestData.tipo_articulo = 'caja';
                    requestData.caja_id = store.articuloSeleccionado.idCaja || store.articuloSeleccionado.id;
                } else if (store.tipoMovimiento === 'articulo_en_caja') {
                    requestData.articulo_id = store.articuloSeleccionado.id;
                    requestData.tipo_articulo = store.articuloSeleccionado.custodia_id ? 'custodia' : 'articulo';
                    requestData.caja_id = store.articuloSeleccionado.cajaPadre?.idCaja;
                } else {
                    requestData.articulo_id = store.articuloSeleccionado.id;
                    requestData.tipo_articulo = store.articuloSeleccionado.custodia_id ? 'custodia' : 'articulo';
                }

                console.log('Store cargando ubicaciones con:', requestData);

                // Necesitas definir rackId globalmente o obtenerlo de alguna forma
                if (!window.rackId) {
                    // Intentar obtener del componente rackDetalle
                    const rackDetalleElement = document.querySelector('[x-data="rackDetalle()"]');
                    if (rackDetalleElement && rackDetalleElement.__x) {
                        window.rackId = rackDetalleElement.__x.rack.idRack || rackDetalleElement.__x.rack.id;
                    }
                }

                const response = await fetch('/almacen/ubicaciones/disponibles-panel', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify(requestData),
                });

                const data = await response.json();
                console.log('Response data:', data);

                if (data.success) {
                    store.ubicacionesDisponibles = data.ubicaciones_disponibles || [];
                    console.log('Ubicaciones cargadas:', store.ubicacionesDisponibles.length);
                } else {
                    alert('Error: ' + (data.message || 'No se pudieron cargar las ubicaciones'));
                }
            } catch (error) {
                console.error('Error de conexión:', error);
                alert('Error de conexión al cargar ubicaciones');
            } finally {
                store.cargandoUbicaciones = false;
            }
        },

        iniciarMovimientoArticuloEnCaja(articulo, ubicacion, caja) {
            console.log('=== Store.iniciarMovimientoArticuloEnCaja ===');
            console.log('Artículo en caja:', articulo);
            console.log('Ubicación:', ubicacion);
            console.log('Caja padre:', caja);

            // Agregar referencia a la caja padre en el artículo
            articulo.cajaPadre = caja;
            this.iniciarMovimiento(articulo, ubicacion, 'articulo_en_caja');
        },

        cancelarMovimiento() {
            this.modoMovimiento.activo = false;
            this.modoMovimiento.articuloSeleccionado = null;
            this.modoMovimiento.cajaSeleccionada = null;
            this.modoMovimiento.ubicacionOrigen = null;
            this.modoMovimiento.ubicacionesDisponibles = [];
            this.modoMovimiento.ubicacionDestinoSeleccionada = null;
        },

        seleccionarUbicacionDestino(ubicacion) {
            this.modoMovimiento.ubicacionDestinoSeleccionada = ubicacion;
        },
    });

    // Registrar el componente rackDetalle
    Alpine.data('rackDetalle', rackDetalle);

    // Registrar el componente modalDetalleUbicacion
    Alpine.data('modalDetalleUbicacion', modalDetalleUbicacion);
});

// ======================== COMPONENTE RACK DETALLE ========================
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
                case 'ocupado':
                    return 'background-color: #22c55e'; // Verde
                case 'vacio':
                    return 'background-color: #6b7280'; // Gris
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

        // FUNCIONES EXISTENTES
        init() {
            console.log('Rack Panel inicializado - Datos Dinámicos', this.rack);

            // Inicializar Swiper después de que Alpine.js haya renderizado el DOM
            this.$nextTick(() => {
                this.inicializarSwipers();
            });
        },

        inicializarSwipers() {
            // Inicializar Swiper para cada nivel
            this.rack.niveles.forEach((nivel) => {
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
        // Agrega esta función en modalDetalleUbicacion()
        formatFecha(fechaString) {
            if (!fechaString) return '';

            try {
                const fecha = new Date(fechaString);
                if (isNaN(fecha.getTime())) return fechaString;

                return fecha.toLocaleDateString('es-ES', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                });
            } catch (e) {
                return fechaString;
            }
        },
        // ======================== FUNCIONES DE MOVIMIENTO (USANDO STORE) ========================

        // INICIAR MOVIMIENTO DESDE EL MODAL DE DETALLE
        iniciarMovimiento(articulo, ubicacion) {
            console.log('=== rackDetalle.iniciarMovimiento ===');
            console.log('Artículo recibido:', articulo);
            console.log('Ubicación recibida:', ubicacion);

            if (!articulo || !ubicacion) {
                console.error('Faltan parámetros:', { articulo, ubicacion });
                alert('Error: Faltan datos para iniciar el movimiento');
                return;
            }

            // Determinar tipo de movimiento
            let tipo = 'articulo';

            if (articulo.es_caja === true) {
                tipo = 'caja';
            } else if (articulo.es_contenido_caja === true) {
                tipo = 'articulo_en_caja';
            }

            console.log('Tipo de movimiento determinado:', tipo);

            // Llama al store con el tipo correcto
            this.$store.rackDetalle.iniciarMovimiento(articulo, ubicacion, tipo);

            // Cargar ubicaciones disponibles
            this.cargarUbicacionesDisponibles(tipo);
        },
        // CANCELAR MOVIMIENTO - Llama al store
        cancelarMovimiento() {
            this.$store.rackDetalle.cancelarMovimiento();
        },
        // CARGAR UBICACIONES DISPONIBLES
        async cargarUbicacionesDisponibles(tipo = 'articulo') {
            const store = this.$store.rackDetalle.modoMovimiento;

            console.log('=== INICIANDO CARGA DE UBICACIONES ===');
            console.log('Tipo:', tipo);
            console.log('Artículo:', store.articuloSeleccionado);
            console.log('Ubicación origen:', store.ubicacionOrigen);

            if (!store.articuloSeleccionado || !store.ubicacionOrigen) {
                alert('Error: No se pudo obtener la información necesaria');
                return;
            }

            store.cargandoUbicaciones = true;

            try {
                // Preparar datos según el tipo
                const requestData = {
                    ubicacion_origen_id: store.ubicacionOrigen.id,
                    rack_id: this.rack.idRack || this.rack.id,
                };

                if (tipo === 'caja') {
                    // Para mover caja completa
                    requestData.articulo_id = store.articuloSeleccionado.idArticulo || store.articuloSeleccionado.id;
                    requestData.tipo_articulo = 'caja';
                    requestData.caja_id = store.articuloSeleccionado.idCaja || store.articuloSeleccionado.id;
                } else if (tipo === 'articulo_en_caja') {
                    // Para mover artículo dentro de caja
                    requestData.articulo_id = store.articuloSeleccionado.id;
                    requestData.tipo_articulo = store.articuloSeleccionado.custodia_id ? 'custodia' : 'articulo';
                    requestData.caja_id = store.articuloSeleccionado.cajaPadre?.idCaja;
                } else {
                    // Para mover artículo suelto
                    requestData.articulo_id = store.articuloSeleccionado.id;
                    requestData.tipo_articulo = store.articuloSeleccionado.custodia_id ? 'custodia' : 'articulo';
                }

                console.log('Datos enviados:', requestData);

                const response = await fetch('/almacen/ubicaciones/disponibles-panel', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify(requestData),
                });

                console.log('Response status:', response.status);
                const data = await response.json();
                console.log('Response data:', data);

                if (data.success) {
                    store.ubicacionesDisponibles = data.ubicaciones_disponibles || [];
                    console.log('Ubicaciones cargadas:', store.ubicacionesDisponibles.length);

                    if (store.ubicacionesDisponibles.length === 0) {
                        alert('No se encontraron ubicaciones disponibles de tipo PANEL en esta sede.');
                    }
                } else {
                    alert('Error: ' + (data.message || 'No se pudieron cargar las ubicaciones'));
                }
            } catch (error) {
                console.error('Error de conexión:', error);
                alert('Error de conexión al cargar ubicaciones');
            } finally {
                store.cargandoUbicaciones = false;
            }
        },

        // SELECCIONAR UBICACIÓN DESTINO - Llama al store
        seleccionarUbicacionDestino(ubicacion) {
            this.$store.rackDetalle.seleccionarUbicacionDestino(ubicacion);
        },

        // MOVER ARTÍCULO
        async moverArticulo() {
            const store = this.$store.rackDetalle.modoMovimiento;

            if (!store.ubicacionDestinoSeleccionada || !store.articuloSeleccionado || !store.ubicacionOrigen) {
                alert('Selecciona una ubicación destino');
                return;
            }

            // DEBUG: VER QUÉ DATOS TIENES
            console.log('=== DEBUG ARTÍCULO SELECCIONADO ===');
            console.log('tipoMovimiento:', store.tipoMovimiento);
            console.log('articuloSeleccionado:', store.articuloSeleccionado);
            console.log('¿Tiene idRackUbicacionArticulo?:', store.articuloSeleccionado.idRackUbicacionArticulo);
            console.log('¿Tiene cajaPadre?:', store.articuloSeleccionado.cajaPadre);
            console.log('¿Tiene es_contenido_caja?:', store.articuloSeleccionado.es_contenido_caja);
            console.log('¿Tiene es_caja?:', store.articuloSeleccionado.es_caja);

            // Validar cantidad
            if (store.cantidad < 1) {
                alert('Cantidad inválida');
                return;
            }

            // Mensaje de confirmación
            let mensajeConfirmacion = `¿Mover ${store.cantidad} unidad(es) de "${store.articuloSeleccionado.nombre}"\nde ${store.ubicacionOrigen.codigo} a ${store.ubicacionDestinoSeleccionada.codigo}?`;

            if (!confirm(mensajeConfirmacion)) {
                return;
            }

            // Marcar como procesando
            store.moviendoArticulo = true;

            try {
                let endpoint = '';
                let requestBody = {};

                // ✅ DETERMINAR QUÉ TIPO DE ARTÍCULO ES
                if (store.articuloSeleccionado.es_caja) {
                    // ES UNA CAJA COMPLETA
                    endpoint = '/almacen/mover-caja-panel';
                    requestBody = {
                        ubicacion_origen_id: store.ubicacionOrigen.id,
                        ubicacion_destino_id: store.ubicacionDestinoSeleccionada.idRackUbicacion,
                        caja_id: store.articuloSeleccionado.idCaja,
                        cantidad: store.cantidad,
                        tipo_movimiento: store.cantidad === store.articuloSeleccionado.cantidad ? 'total' : 'parcial',
                        observaciones: store.observaciones || 'Movimiento manual de caja',
                    };
                } else if (store.articuloSeleccionado.cajaPadre || store.articuloSeleccionado.es_contenido_caja) {
                    // ES UN ARTÍCULO DENTRO DE UNA CAJA
                    endpoint = '/almacen/mover-articulo-en-caja-panel';
                    requestBody = {
                        ubicacion_origen_id: store.ubicacionOrigen.id,
                        ubicacion_destino_id: store.ubicacionDestinoSeleccionada.idRackUbicacion,
                        caja_id: store.articuloSeleccionado.cajaPadre?.idCaja || store.articuloSeleccionado.idCaja,
                        articulo_id: store.articuloSeleccionado.id,
                        cantidad: store.cantidad,
                        observaciones: store.observaciones || 'Movimiento manual de artículo desde caja',
                    };
                } else if (store.articuloSeleccionado.idRackUbicacionArticulo) {
                    // ES UN ARTÍCULO SUELTO
                    endpoint = '/almacen/mover-producto-panel';
                    requestBody = {
                        ubicacion_origen_id: store.ubicacionOrigen.id,
                        ubicacion_destino_id: store.ubicacionDestinoSeleccionada.idRackUbicacion,
                        rack_ubicacion_articulo_id: store.articuloSeleccionado.idRackUbicacionArticulo,
                        cantidad: store.cantidad,
                        observaciones: store.observaciones || 'Movimiento manual de artículo suelto',
                    };
                } else {
                    // NO SE PUEDE IDENTIFICAR EL TIPO
                    alert('Error: No se puede identificar el tipo de artículo. Contacta al administrador.');
                    return;
                }

                console.log('Enviando a endpoint:', endpoint);
                console.log('Datos:', requestBody);

                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify(requestBody),
                });

                const data = await response.json();
                console.log('Respuesta del servidor:', data);

                if (data.success) {
                    alert('✓ ' + (data.message || 'Operación realizada exitosamente'));
                    window.location.reload();
                } else {
                    alert('✗ Error: ' + (data.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('✗ Error de conexión');
            } finally {
                store.moviendoArticulo = false;
            }
        },

        // MODAL PARA MOVER CAJAS PARCIALMENTE
        abrirModalMoverParcialCaja(caja, ubicacion) {
            // Crear modal para mover parcialmente
            const modalHtml = `
                <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-bold text-gray-800">Mover Artículos de la Caja</h3>
                                <button onclick="this.closest('.fixed').remove()" 
                                        class="text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 mb-2">Caja: <span class="font-semibold">${caja.nombre}</span></p>
                                <p class="text-sm text-gray-600 mb-4">Artículo: <span class="font-semibold">${caja.articulo_en_caja?.nombre || caja.contenido}</span></p>
                                
                                <div class="flex items-center justify-between mb-4 p-3 bg-amber-50 rounded-lg">
                                    <span class="text-sm text-gray-700">Cantidad disponible:</span>
                                    <span class="font-bold text-lg text-amber-600">${caja.cantidad}</span>
                                </div>
                                
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Cantidad a mover
                                </label>
                                <input type="number" 
                                       id="cantidad-mover-caja" 
                                       min="1" 
                                       max="${caja.cantidad}" 
                                       value="${caja.cantidad}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <p class="text-xs text-gray-500 mt-1">Máximo: ${caja.cantidad} unidades</p>
                            </div>
                            
                            <div class="flex justify-end space-x-3">
                                <button onclick="this.closest('.fixed').remove()" 
                                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                                    Cancelar
                                </button>
                                <button onclick="window.confirmarMovimientoParcialCaja(${JSON.stringify(caja).replace(/"/g, '&quot;')}, ${ubicacion.id}, ${caja.cantidad})"
                                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">
                                    Continuar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', modalHtml);
        },

        // ======================== FIN FUNCIONES DE MOVIMIENTO ========================

        // MODALES Y ESTADOS (mantener compatibilidad)
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

// ======================== COMPONENTE MODAL DETALLE UBICACIÓN ========================
function modalDetalleUbicacion() {
    return {
        open: false,
        ubicacion: null,

        init() {
            console.log('Modal de detalle de ubicación inicializado');
        },

        abrirModal(ubicacionData) {
            this.ubicacion = ubicacionData;
            this.open = true;
            document.body.style.overflow = 'hidden';
            console.log('Modal abierto para:', ubicacionData?.codigo, ubicacionData);
        },

        closeModal() {
            this.open = false;
            this.ubicacion = null;
            document.body.style.overflow = '';
            console.log('Modal cerrado');
        },

        getEstadoColor(estado) {
            const colores = {
                ocupado: 'text-green-600',
                vacio: 'text-gray-600',
                null: 'text-gray-600',
            };
            return colores[estado] || colores['vacio'];
        },

        getEstadoTexto(estado) {
            const textos = {
                ocupado: 'Ocupado',
                vacio: 'Vacío',
                null: 'Vacío',
            };
            return textos[estado] || textos['vacio'];
        },

        // FUNCIÓN SIMPLIFICADA - SOLUCIÓN
        iniciarMovimientoDesdeModalConArticulo(articulo, ubicacion = null) {
            console.log('=== INICIAR MOVIMIENTO DESDE MODAL ===');
            console.log('Artículo:', articulo);
            console.log('Ubicación pasada:', ubicacion);

            // Usar ubicación pasada o la del modal
            const ubicacionActual = ubicacion || this.ubicacion;

            if (!articulo || !ubicacionActual) {
                alert('Error: Faltan datos para iniciar el movimiento');
                return;
            }

            // Cerrar el modal
            this.closeModal();

            // Pequeña espera para que se cierre el modal
            setTimeout(() => {
                // Determinar tipo de movimiento
                let tipo = 'articulo';

                if (articulo.es_caja === true) {
                    tipo = 'caja';
                } else if (articulo.es_contenido_caja === true) {
                    tipo = 'articulo_en_caja';

                    // Si es artículo en caja, buscar la caja padre
                    if (this.ubicacion && this.ubicacion.cajas) {
                        for (const caja of this.ubicacion.cajas) {
                            if (caja.articulo_en_caja && caja.articulo_en_caja.id === articulo.id) {
                                articulo.cajaPadre = caja;
                                break;
                            }
                        }
                    }
                }

                console.log('Tipo de movimiento:', tipo);
                console.log('Artículo final:', articulo);

                // LLAMAR DIRECTAMENTE AL STORE
                Alpine.store('rackDetalle').iniciarMovimiento(articulo, ubicacionActual, tipo);

                // Ahora necesitamos activar el modal de movimiento
                // Esto se puede hacer de varias formas:

                // Opción 1: Usar el store para mostrar el modal
                setTimeout(() => {
                    // Buscar el modal de movimiento y activarlo
                    const modalesMovimiento = document.querySelectorAll('[x-show*="modoMovimiento.activo"]');
                    if (modalesMovimiento.length > 0) {
                        // Ya debería estar activo por el store
                        console.log('Modal de movimiento activado');
                    } else {
                        console.warn('No se encontró el modal de movimiento');
                    }
                }, 200);
            }, 100);
        },

        // Función auxiliar para buscar la caja que contiene un artículo
        buscarCajaContenedora(articulo) {
            if (!this.ubicacion || !this.ubicacion.cajas) {
                return null;
            }

            // Buscar en las cajas de la ubicación actual
            for (const caja of this.ubicacion.cajas) {
                if (caja.articulo_en_caja && caja.articulo_en_caja.id === articulo.id) {
                    return caja;
                }
            }

            return null;
        },

        abrirHistorial(ubicacion) {
            alert('Modal historial - En desarrollo');
        },
    };
}
// ======================== FUNCIONES GLOBALES ========================
// Función global para confirmar movimiento parcial de caja
window.confirmarMovimientoParcialCaja = function (caja, ubicacionOrigenId, cantidadMaxima) {
    const cantidadInput = document.getElementById('cantidad-mover-caja');
    const cantidad = parseInt(cantidadInput.value);

    if (!cantidad || cantidad < 1 || cantidad > cantidadMaxima) {
        alert(`Cantidad inválida. Debe estar entre 1 y ${cantidadMaxima}`);
        return;
    }

    // Cerrar el modal
    document.querySelector('.fixed.bg-black').remove();

    // Buscar el componente rackDetalle
    const rackDetalleElement = document.querySelector('[x-data="rackDetalle()"]');

    if (rackDetalleElement && rackDetalleElement.__x) {
        const rackDetalleComponent = rackDetalleElement.__x;

        // Agregar la cantidad al objeto caja
        caja.cantidad_mover = cantidad;

        // Iniciar movimiento
        setTimeout(() => {
            rackDetalleComponent.iniciarMovimiento(caja, { id: ubicacionOrigenId });
        }, 300);
    }
};
// ======================== INICIALIZACIÓN ========================
// Inicializar Alpine.js cuando el documento esté listo
document.addEventListener('DOMContentLoaded', function () {
    // Esperar a que Alpine.js esté disponible
    if (typeof Alpine !== 'undefined') {
        // Los componentes ya están registrados en el evento alpine:init
        console.log('Alpine.js inicializado correctamente');
    } else {
        // Fallback si Alpine no está disponible inmediatamente
        const checkAlpine = setInterval(() => {
            if (typeof Alpine !== 'undefined') {
                clearInterval(checkAlpine);
                console.log('Alpine.js cargado dinámicamente');
            }
        }, 100);
    }
});
