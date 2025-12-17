// ======================== STORE DE ALPINE.JS ========================
document.addEventListener('alpine:init', () => {
    // Store para compartir datos entre componentes
    Alpine.store('rackDetalle', {
        modoMovimiento: {
            activo: false,
            articuloSeleccionado: null,
            cajaSeleccionada: null,
            ubicacionOrigen: null,
            cantidad: 1,
            observaciones: '',
            ubicacionesDisponibles: [],
            ubicacionesFiltradas: null,
            busquedaUbicacion: '',
            cargandoUbicaciones: false,
            ubicacionDestinoSeleccionada: null,
            moviendoArticulo: false,
            moviendoCaja: false,
            tipoMovimiento: 'articulo',
            _ubicacionesPreparadas: null,
            _timeoutBusqueda: null,
        },

        // ✅ NUEVO MÉTODO PARA FILTRAR UBICACIONES
        filtrarUbicaciones() {
            const modoMov = this.modoMovimiento;
            const busqueda = modoMov.busquedaUbicacion.trim();

            // Limpiar timeout anterior
            if (modoMov._timeoutBusqueda) {
                clearTimeout(modoMov._timeoutBusqueda);
            }

            // Si la búsqueda está vacía
            if (!busqueda) {
                modoMov.ubicacionesFiltradas = null;
                modoMov._ubicacionesPreparadas = null; // Liberar memoria
                return;
            }

            // Debouncing: Esperar 150ms después de la última tecla
            modoMov._timeoutBusqueda = setTimeout(() => {
                this._ejecutarBusquedaReal(busqueda.toLowerCase());
            }, 150);
        },

        copiarCodigo() {
            console.log('=== COPIAR CÓDIGO ===');

            // Acceder correctamente a los datos
            const modoMov = this.modoMovimiento;

            if (!modoMov.articuloSeleccionado) {
                console.error('No hay artículo seleccionado');

                // Toastr de error
                if (typeof toastr !== 'undefined') {
                    toastr.error('No hay artículo seleccionado', 'Error');
                } else {
                    alert('Error: No hay artículo seleccionado');
                }
                return;
            }

            // Obtener el código del artículo
            const codigo =
                modoMov.articuloSeleccionado?.codigo_repuesto || modoMov.articuloSeleccionado?.codigo_barras || modoMov.articuloSeleccionado?.sku || 'N/A';

            console.log('Código a copiar:', codigo);

            if (codigo === 'N/A') {
                if (typeof toastr !== 'undefined') {
                    toastr.warning('No hay código disponible para copiar', 'Aviso');
                } else {
                    alert('No hay código para copiar');
                }
                return;
            }

            // Crear un elemento temporal para copiar
            const textarea = document.createElement('textarea');
            textarea.value = codigo;
            textarea.style.position = 'fixed';
            textarea.style.left = '-9999px';
            document.body.appendChild(textarea);
            textarea.select();

            try {
                // Intentar usar la API moderna del portapapeles
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard
                        .writeText(codigo)
                        .then(() => {
                            this.mostrarToastrExito(codigo);
                        })
                        .catch((err) => {
                            console.error('Error con clipboard API:', err);
                            this.copiarConFallbackToastr(codigo, textarea);
                        });
                } else {
                    // Fallback para navegadores antiguos
                    this.copiarConFallbackToastr(codigo, textarea);
                }
            } catch (error) {
                console.error('Error general al copiar:', error);
                this.copiarConFallbackToastr(codigo, textarea);
            } finally {
                // Limpiar
                document.body.removeChild(textarea);
            }
        },

        // Función para mostrar Toastr de éxito
        mostrarToastrExito(codigo) {
            if (typeof toastr !== 'undefined') {
                // Toastr con HTML personalizado
                toastr.success(
                    `<div class="flex items-start">
                <div class="flex-1">
                    <div class="font-semibold">Código copiado al portapapeles</div>
                    <div class="text-sm font-mono mt-1 p-1 rounded">${codigo}</div>
                </div>
            </div>`,
                    '¡Listo!',
                    {
                        closeButton: true,
                        timeOut: 4000,
                        extendedTimeOut: 2000,
                        progressBar: true,
                        escapeHtml: false, // IMPORTANTE: Permite HTML
                        tapToDismiss: false,
                    },
                );
            } else {
                // Fallback si Toastr no está disponible
                alert('✓ Código copiado: ' + codigo);
            }
        },

        // Método fallback con Toastr
        copiarConFallbackToastr(codigo, textarea) {
            try {
                const exitoso = document.execCommand('copy');
                if (exitoso) {
                    this.mostrarToastrExito(codigo);
                } else {
                    // Mostrar Toastr con opción para copiar
                    if (typeof toastr !== 'undefined') {
                        const toast = toastr.warning(
                            `<div class="flex flex-col">
                        <div class="font-semibold mb-2">Copia manualmente:</div>
                        <div class="font-mono text-lg bg-gray-100 p-2 rounded mb-2 select-all cursor-pointer" onclick="navigator.clipboard.writeText('${codigo}')">
                            ${codigo}
                        </div>
                        <button onclick="navigator.clipboard.writeText('${codigo}')" 
                                class="px-3 py-1 bg-blue-500 text-white rounded text-sm hover:bg-blue-600 transition-colors">
                            <i class="fas fa-copy mr-1"></i>Haz clic para copiar
                        </button>
                    </div>`,
                            'Copiar código',
                            {
                                timeOut: 0, // No desaparece automáticamente
                                extendedTimeOut: 0,
                                closeButton: true,
                                escapeHtml: false,
                            },
                        );
                    } else {
                        prompt('Selecciona y copia manualmente:', codigo);
                    }
                }
            } catch (err) {
                console.error('Error con execCommand:', err);
                if (typeof toastr !== 'undefined') {
                    toastr.error('Error al copiar el código', 'Error');
                } else {
                    prompt('Copia manualmente este código:', codigo);
                }
            }
        },

        // ✅ MÉTODO PRIVADO: Ejecuta la búsqueda real
        _ejecutarBusquedaReal(busqueda) {
            const modoMov = this.modoMovimiento;
            const ubicaciones = modoMov.ubicacionesDisponibles;

            // Si no hay ubicaciones, no buscar
            if (!ubicaciones || ubicaciones.length === 0) {
                modoMov.ubicacionesFiltradas = [];
                return;
            }

            // ✅ OPTIMIZACIÓN 1: Preparar datos una sola vez
            if (!modoMov._ubicacionesPreparadas) {
                modoMov._ubicacionesPreparadas = ubicaciones.map((ubicacion) => ({
                    id: ubicacion.idRackUbicacion,
                    // Pre-calcular valores en minúsculas
                    codigoUnico: (ubicacion.codigo_unico || '').toLowerCase(),
                    codigo: (ubicacion.codigo || '').toLowerCase(),
                    rackNombre: (ubicacion.rack_nombre || '').toLowerCase(),
                    // Mantener referencia al objeto original
                    original: ubicacion,
                }));
            }

            // ✅ OPTIMIZACIÓN 2: Búsqueda más eficiente
            const resultados = [];
            const preCalculadas = modoMov._ubicacionesPreparadas;

            // Para búsquedas muy cortas, buscar solo en código único
            if (busqueda.length <= 3) {
                for (let i = 0; i < preCalculadas.length; i++) {
                    const item = preCalculadas[i];
                    if (item.codigoUnico.includes(busqueda)) {
                        resultados.push(item.original);
                        // Si ya encontramos suficientes, parar
                        if (resultados.length > 100) break;
                    }
                }
            } else {
                // Para búsquedas más largas, buscar en todos los campos
                for (let i = 0; i < preCalculadas.length; i++) {
                    const item = preCalculadas[i];
                    if (item.codigoUnico.includes(busqueda) || item.codigo.includes(busqueda) || item.rackNombre.includes(busqueda)) {
                        resultados.push(item.original);
                        if (resultados.length > 100) break;
                    }
                }
            }

            modoMov.ubicacionesFiltradas = resultados;
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
            this.modoMovimiento.ubicacionesFiltradas = null; // ✅ Resetear filtros
            this.modoMovimiento.busquedaUbicacion = ''; // ✅ Resetear búsqueda
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

            // Limpiar cache de búsqueda anterior
            store._ubicacionesPreparadas = null;
            if (store._timeoutBusqueda) {
                clearTimeout(store._timeoutBusqueda);
                store._timeoutBusqueda = null;
            }

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
                    store.ubicacionesFiltradas = null;
                    store.busquedaUbicacion = '';
                    store._ubicacionesPreparadas = null; // Resetear cache

                    // ✅ OPCIONAL: Ordenar por código para búsquedas más rápidas
                    if (store.ubicacionesDisponibles.length > 0) {
                        store.ubicacionesDisponibles.sort((a, b) => {
                            return (a.codigo_unico || '').localeCompare(b.codigo_unico || '');
                        });
                    }

                    console.log('Ubicaciones cargadas:', store.ubicacionesDisponibles.length);
                }
            } catch (error) {
                console.error('Error de conexión:', error);
                alert('Error de conexión al cargar ubicaciones');
            } finally {
                store.cargandoUbicaciones = false;
            }
        },

        // ✅ LIMPIAR CACHE cuando ya no se necesita
        cancelarMovimiento() {
            const modoMov = this.modoMovimiento;

            if (modoMov._timeoutBusqueda) {
                clearTimeout(modoMov._timeoutBusqueda);
            }

            modoMov.activo = false;
            modoMov.articuloSeleccionado = null;
            modoMov.cajaSeleccionada = null;
            modoMov.ubicacionOrigen = null;
            modoMov.ubicacionesDisponibles = [];
            modoMov.ubicacionesFiltradas = null;
            modoMov.busquedaUbicacion = '';
            modoMov._ubicacionesPreparadas = null; // ✅ Liberar memoria
            modoMov.ubicacionDestinoSeleccionada = null;
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
            this.modoMovimiento.ubicacionesFiltradas = null; // ✅ Limpiar filtros
            this.modoMovimiento.busquedaUbicacion = ''; // ✅ Limpiar búsqueda
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
        rack: window.rackData || {}, // <-- USA window.
        todosRacks: window.todosRacks || [],
        rackActual: window.rackActual || '',
        sedeActual: window.sedeActual || '',

        // ✅ NUEVO: Función para manejar la búsqueda
        manejarBusquedaUbicacion(event) {
            this.$store.rackDetalle.filtrarUbicaciones();
        },

        // ✅ NUEVO: Función para limpiar búsqueda
        limpiarBusquedaUbicacion() {
            this.$store.rackDetalle.modoMovimiento.busquedaUbicacion = '';
            this.$store.rackDetalle.modoMovimiento.ubicacionesFiltradas = null;
        },

        // ✅ NUEVO: Getter para obtener ubicaciones visibles (filtradas o todas)
        get ubicacionesVisibles() {
            const store = this.$store.rackDetalle.modoMovimiento;
            return store.ubicacionesFiltradas !== null ? store.ubicacionesFiltradas : store.ubicacionesDisponibles;
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

        // SELECCIONAR UBICACIÓN DESTINO - Llama al store
        seleccionarUbicacionDestino(ubicacion) {
            this.$store.rackDetalle.seleccionarUbicacionDestino(ubicacion);
        },
        // MOVER ARTÍCULO CON SWEETALERT2
        async moverArticulo() {
            const store = this.$store.rackDetalle.modoMovimiento;

            if (!store.ubicacionDestinoSeleccionada || !store.articuloSeleccionado || !store.ubicacionOrigen) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Ubicación requerida',
                    text: 'Por favor, selecciona una ubicación destino',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#4f46e5',
                    backdrop: true,
                });
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
                Swal.fire({
                    icon: 'error',
                    title: 'Cantidad inválida',
                    text: 'La cantidad debe ser mayor a 0',
                    confirmButtonText: 'Corregir',
                    confirmButtonColor: '#ef4444',
                });
                return;
            }

            // ========== CONFIRMACIÓN CON SWEETALERT2 ==========
            const result = await Swal.fire({
                title: '¿Confirmar movimiento?',
                html: `
        <div class="text-center">
            
            <p class="text-lg font-semibold text-gray-800 mb-2">¿Estás seguro de mover este artículo?</p>
            <p class="text-gray-600 mb-6">No podrás deshacer esta acción.</p>
            
            <!-- Detalles del movimiento -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                <div class="space-y-3 text-left">
                    <div class="flex items-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600 mr-3">
                            <i class="fas fa-box text-sm"></i>
                        </span>
                        <div class="flex-1">
                            <div class="text-xs text-gray-500">Artículo</div>
                            <div class="font-medium text-gray-800">${store.articuloSeleccionado?.codigo_repuesto || store.articuloSeleccionado?.codigo_barras || 'Artículo'}</div>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-100 text-amber-600 mr-3">
                            <i class="fas fa-layer-group text-sm"></i>
                        </span>
                        <div class="flex-1">
                            <div class="text-xs text-gray-500">Cantidad</div>
                            <div class="font-medium text-gray-800">${store.cantidad} unidad(es)</div>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-100 text-red-600 mr-3">
                            <i class="fas fa-sign-out-alt text-sm"></i>
                        </span>
                        <div class="flex-1">
                            <div class="text-xs text-gray-500">Origen</div>
                            <div class="font-medium text-gray-800">${store.ubicacionOrigen.codigo}</div>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-100 text-green-600 mr-3">
                            <i class="fas fa-sign-in-alt text-sm"></i>
                        </span>
                        <div class="flex-1">
                            <div class="text-xs text-gray-500">Destino</div>
                            <div class="font-medium text-gray-800">${store.ubicacionDestinoSeleccionada.codigo_unico}</div>
                        </div>
                    </div>
                </div>
            </div>
               
            <div class="text-xs text-gray-500 mt-4">
                <i class="fas fa-exclamation-circle mr-1"></i>
                Esta acción moverá el artículo a la nueva ubicación
            </div>
        </div>
    `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, mover artículo',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#3085d6', // Rojo como en la imagen
                cancelButtonColor: '#d33', // Azul como en la imagen
                reverseButtons: true, // Cancelar primero, luego Confirmar
                focusCancel: true, // Enfocar el botón de cancelar por defecto
                showLoaderOnConfirm: true,
                customClass: {
                    popup: 'rounded-xl',
                    title: 'hidden', // Ocultamos el título por defecto porque lo incluimos en el HTML
                    htmlContainer: 'pt-0',
                    confirmButton: 'px-5 py-2.5 font-medium rounded-lg',
                    cancelButton: 'px-5 py-2.5 font-medium rounded-lg mr-2',
                },
                preConfirm: () => {
                    return this.ejecutarMovimiento(store);
                },
                allowOutsideClick: () => !Swal.isLoading(),
                backdrop: 'rgba(0,0,0,0.4)',
            });

            // Si el usuario canceló - USANDO TOASTR
            if (result.dismiss === Swal.DismissReason.cancel) {
                toastr.info('Movimiento cancelado', 'Cancelado', {
                    timeOut: 3000,
                    progressBar: true,
                    positionClass: 'toast-top-right',
                    closeButton: true,
                });
                return;
            }

            // Si hubo éxito en el movimiento - USANDO TOASTR
            if (result.value && result.value.success) {
                toastr.success('El artículo ha sido movido exitosamente', '¡Movimiento exitoso!', {
                    timeOut: 3000,
                    progressBar: true,
                    positionClass: 'toast-top-right',
                    closeButton: true,
                    onHidden: () => {
                        window.location.reload();
                    },
                });
            }
        },

        // ========== FUNCIÓN PARA EJECUTAR EL MOVIMIENTO ==========
        async ejecutarMovimiento(store) {
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
                    Swal.showValidationMessage('Error: No se puede identificar el tipo de artículo');
                    return null;
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
                    return { success: true, message: data.message };
                } else {
                    // MOSTRAR ERROR CON TOASTR
                    toastr.error(data.message || 'Error desconocido', 'Error', {
                        timeOut: 5000,
                        progressBar: true,
                        positionClass: 'toast-top-right',
                        closeButton: true,
                    });
                    Swal.showValidationMessage(data.message || 'Error desconocido');
                    return null;
                }
            } catch (error) {
                console.error('Error:', error);
                // MOSTRAR ERROR DE CONEXIÓN CON TOASTR
                toastr.error('Error de conexión con el servidor', 'Error', {
                    timeOut: 5000,
                    progressBar: true,
                    positionClass: 'toast-top-right',
                    closeButton: true,
                });
                Swal.showValidationMessage('Error de conexión con el servidor');
                return null;
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

        // ========== CAMBIA ESTAS FUNCIONES ==========
        downloadQR() {
            console.log('downloadQR ejecutado');
            console.log('this.ubicacion:', this.ubicacion);

            // Usa this.ubicacion del componente actual
            const qrCode = this.ubicacion?.codigo_unico || this.ubicacion?.codigo;

            if (!qrCode) {
                console.error('No se pudo obtener código de ubicación:', this.ubicacion);
                toastr.error('No hay código de ubicación disponible', 'Error');
                return;
            }

            console.log('Código para QR:', qrCode);

            // Crear enlace de descarga
            const downloadUrl = `/almacen/ubicaciones/qr/${encodeURIComponent(qrCode)}?download=true`;
            console.log('URL de descarga:', downloadUrl);

            // Mostrar mensaje de carga
            toastr.info('Preparando descarga...', 'Procesando');

            // Crear un enlace temporal para la descarga
            const link = document.createElement('a');
            link.href = downloadUrl;
            link.download = `qr-ubicacion-${qrCode}.svg`; // Cambia a .svg
            document.body.appendChild(link);

            // Simular clic para iniciar descarga
            link.click();
            document.body.removeChild(link);

            // Mostrar notificación de éxito
            toastr.success('QR descargado correctamente', '¡Listo!', {
                timeOut: 3000,
                progressBar: true,
                closeButton: true,
            });

            console.log('QR descargado:', qrCode);
        },

        copyQRUrl() {
            console.log('copyQRUrl ejecutado');
            console.log('this.ubicacion:', this.ubicacion);

            // Usa this.ubicacion del componente actual
            const qrCode = this.ubicacion?.codigo_unico || this.ubicacion?.codigo;

            if (!qrCode) {
                console.error('No se pudo obtener código de ubicación:', this.ubicacion);
                toastr.error('No hay código de ubicación disponible', 'Error');
                return;
            }

            const qrUrl = `${window.location.origin}/almacen/ubicaciones/qr/${encodeURIComponent(qrCode)}`;
            console.log('URL para copiar:', qrUrl);

            // Mostrar mensaje de procesando
            toastr.info('Copiando al portapapeles...', 'Procesando');

            // Usar Clipboard API
            navigator.clipboard
                .writeText(qrUrl)
                .then(() => {
                    // Éxito
                    toastr.success('URL copiada al portapapeles', '¡Copiado!', {
                        timeOut: 3000,
                        progressBar: true,
                        closeButton: true,
                    });
                })
                .catch((err) => {
                    console.error('Error al copiar:', err);
                    toastr.error('Error al copiar URL', 'Error');
                });
        },
        // FUNCIÓN SIMPLIFICADA - SOLUCIÓN
        iniciarMovimientoDesdeModalConArticulo(articulo, ubicacion = null) {
            console.log('=== INICIAR MOVIMIENTO DESDE MODAL ===');
            console.log('Artículo:', articulo);

            const ubicacionActual = ubicacion || this.ubicacion;
            if (!articulo || !ubicacionActual) {
                alert('Error: Faltan datos para iniciar el movimiento');
                return;
            }

            this.closeModal();
            setTimeout(() => {
                let tipo = 'articulo';
                if (articulo.es_caja === true) {
                    tipo = 'caja';
                } else if (articulo.es_contenido_caja === true) {
                    tipo = 'articulo_en_caja';
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
                Alpine.store('rackDetalle').iniciarMovimiento(articulo, ubicacionActual, tipo);
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
