<x-layout.default>
    <!-- Incluir Axios CDN -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <div x-data="solicitudApp()" class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Solicitudes de Ingreso</h1>
            <p class="text-gray-600">Gestiona las solicitudes de ingreso agrupadas por origen</p>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex flex-wrap gap-4 items-center">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Filtrar por estado:</label>
                    <select x-model="filtroEstado" class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="todos">Todos</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="recibido">Recibido</option>
                        <option value="ubicado">Ubicado</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Filtrar por origen:</label>
                    <select x-model="filtroOrigen" class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="todos">Todos</option>
                        <option value="compra">Compra</option>
                        <option value="entrada_proveedor">Entrada Proveedor</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[300px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Buscar:</label>
                    <input type="text" x-model="searchTerm" placeholder="Buscar por código, proveedor, artículo..."
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i class="fas fa-clock text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-blue-600">Pendientes</p>
                        <p class="text-2xl font-bold text-blue-800" x-text="contadores.pendiente"></p>
                    </div>
                </div>
            </div>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <i class="fas fa-check text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-green-600">Recibidos</p>
                        <p class="text-2xl font-bold text-green-800" x-text="contadores.recibido"></p>
                    </div>
                </div>
            </div>
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <i class="fas fa-map-marker-alt text-purple-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-purple-600">Ubicados</p>
                        <p class="text-2xl font-bold text-purple-800" x-text="contadores.ubicado"></p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-gray-100 rounded-lg">
                        <i class="fas fa-boxes text-gray-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Total Grupos</p>
                        <p class="text-2xl font-bold text-gray-800" x-text="contadores.total"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid de Cards Agrupadas -->
        <template x-if="solicitudesAgrupadasFiltradas.length === 0">
            <div class="text-center py-12">
                <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">No se encontraron solicitudes agrupadas</p>
            </div>
        </template>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-2 gap-6">
            <template x-for="grupo in solicitudesAgrupadasFiltradas" :key="grupo.origen + '_' + grupo.origen_id">
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 hover:shadow-xl transition-shadow duration-300">
                    <!-- Header de la Card Grupal -->
                    <div class="border-b border-gray-200 p-4 bg-gray-50">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                    :class="getEstadoClasses(grupo.estado_general)">
                                    <i :class="getEstadoIcon(grupo.estado_general)" class="mr-1"></i>
                                    <span x-text="grupo.estado_general.charAt(0).toUpperCase() + grupo.estado_general.slice(1)"></span>
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium"
                                    :class="grupo.origen === 'compra' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'">
                                    <i :class="grupo.origen === 'compra' ? 'fas fa-shopping-cart' : 'fas fa-truck'" class="mr-1"></i>
                                    <span x-text="grupo.origen === 'compra' ? 'Compra' : 'Entrada Proveedor'"></span>
                                </span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-semibold text-gray-800"
                                    x-text="grupo.origen === 'compra' ? grupo.origen_especifico.codigocompra : grupo.origen_especifico.codigo_entrada"></h3>
                                <p class="text-sm text-gray-600" 
                                   x-text="grupo.mostrar_cliente ? 'Cliente: ' + grupo.cliente_general.descripcion : 'Proveedor: ' + grupo.proveedor.nombre"></p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-800" x-text="grupo.total_articulos + ' artículos'"></p>
                                <p class="text-sm text-gray-600" x-text="'Total: ' + grupo.total_cantidad + ' unidades'"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Body de la Card - Lista de Artículos -->
                    <div class="p-4">
                        <div class="mb-3">
                            <p class="text-sm font-medium text-gray-700">Artículos en esta solicitud:</p>
                        </div>

                        <div class="space-y-3 max-h-60 overflow-y-auto">
                            <template x-for="solicitud in grupo.solicitudes" :key="solicitud.idSolicitudIngreso">
                                <div class="border border-gray-200 rounded-lg p-3">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-800"
                                                x-text="getNombreArticulo(solicitud.articulo)"></p>
                                            <p class="text-xs text-gray-500"
                                                x-text="'Tipo: ' + getTipoArticulo(solicitud.articulo?.idTipoArticulo)"></p>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium ml-2"
                                            :class="getEstadoClasses(solicitud.estado)">
                                            <span x-text="solicitud.estado"></span>
                                        </span>
                                    </div>

                                    <!-- MODIFICADO: Quitamos Lote y Vence, solo mostramos Cantidad y Ubicación -->
                                    <div class="grid grid-cols-2 gap-2 text-xs">
                                        <div>
                                            <span class="text-gray-500">Cantidad:</span>
                                            <span class="font-medium ml-1" x-text="solicitud.cantidad"></span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Ubicación:</span>
                                            <span class="font-medium ml-1" x-text="getUbicacionesTexto(solicitud)"></span>
                                        </div>
                                    </div>

                                    <!-- Botones de estado individuales -->
                                    <div class="flex space-x-1 mt-2">
                                        <button @click="cambiarEstado(solicitud.idSolicitudIngreso, 'pendiente')"
                                            class="flex-1 px-2 py-1 text-xs rounded transition-colors"
                                            :class="solicitud.estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-600 hover:bg-yellow-50'">
                                            Pendiente
                                        </button>
                                        <button @click="cambiarEstado(solicitud.idSolicitudIngreso, 'recibido')"
                                            class="flex-1 px-2 py-1 text-xs rounded transition-colors"
                                            :class="solicitud.estado === 'recibido' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600 hover:bg-green-50'">
                                            Recibido
                                        </button>
                                        <button @click="abrirModalUbicacion(solicitud)"
                                            class="flex-1 px-2 py-1 text-xs rounded transition-colors"
                                            :class="solicitud.estado === 'ubicado' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-600 hover:bg-purple-50'">
                                            Ubicar
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Footer de la Card Grupal -->
                    <div class="border-t border-gray-200 p-4 bg-gray-50">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500"
                                x-text="'Fecha origen: ' + formatFecha(grupo.fecha_origen)"></span>
                            <div class="flex space-x-2">
                                <button @click="cambiarEstadoGrupo(grupo, 'pendiente')"
                                    class="px-3 py-1 text-xs rounded-lg transition-colors"
                                    :class="grupo.estado_general === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-600 hover:bg-yellow-50'">
                                    Todos Pendiente
                                </button>
                                <button @click="cambiarEstadoGrupo(grupo, 'recibido')"
                                    class="px-3 py-1 text-xs rounded-lg transition-colors"
                                    :class="grupo.estado_general === 'recibido' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600 hover:bg-green-50'">
                                    Todos Recibido
                                </button>
                                <button @click="cambiarEstadoGrupo(grupo, 'ubicado')"
                                    class="px-3 py-1 text-xs rounded-lg transition-colors"
                                    :class="grupo.estado_general === 'ubicado' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-600 hover:bg-purple-50'">
                                    Todos Ubicado
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Modal de Ubicación -->
        <div x-show="modalUbicacionAbierto" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-map-marker-alt mr-2 text-purple-500"></i>
                        Ubicar Artículo
                    </h3>
                    
                    <!-- Información del artículo -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-4" x-show="solicitudSeleccionada">
                        <p class="font-medium" x-text="'Artículo: ' + getNombreArticulo(solicitudSeleccionada?.articulo)"></p>
                        <p class="text-sm text-gray-600" x-text="'Cantidad total: ' + (solicitudSeleccionada?.cantidad || 0)"></p>
                    </div>

                    <!-- Formulario de ubicaciones -->
                    <div class="space-y-4">
                        <template x-for="(ubicacion, index) in ubicacionesForm" :key="index">
                            <div class="flex gap-3 items-start border border-gray-200 rounded-lg p-3">
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Ubicación</label>
                                    <select x-model="ubicacion.ubicacion_id" 
                                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Seleccionar ubicación</option>
                                        <template x-for="ubic in ubicaciones" :key="ubic.idUbicacion">
                                            <option :value="ubic.idUbicacion" x-text="ubic.nombre"></option>
                                        </template>
                                    </select>
                                </div>
                                <div class="w-32">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Cantidad</label>
                                    <input type="number" x-model="ubicacion.cantidad" 
                                           :max="cantidadDisponible"
                                           min="1" 
                                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div class="pt-6">
                                    <button type="button" 
                                            @click="eliminarUbicacion(index)"
                                            class="text-red-600 hover:text-red-800 p-1"
                                            :disabled="ubicacionesForm.length === 1">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </template>

                        <!-- Cantidad disponible -->
                        <div class="bg-blue-50 p-3 rounded-lg">
                            <p class="text-sm text-blue-800">
                                <span x-text="'Cantidad disponible: ' + cantidadDisponible"></span>
                                <span x-show="cantidadDisponible === 0" class="text-red-600 font-medium"> - ¡Toda la cantidad ha sido distribuida!</span>
                            </p>
                        </div>

                        <!-- Botón para agregar más ubicaciones -->
                        <button type="button" 
                                @click="agregarUbicacion"
                                :disabled="cantidadDisponible === 0"
                                class="flex items-center gap-2 text-blue-600 hover:text-blue-800 disabled:text-gray-400 disabled:cursor-not-allowed">
                            <i class="fas fa-plus"></i>
                            Agregar otra ubicación
                        </button>
                    </div>

                    <!-- Botones del modal -->
                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                        <button @click="cerrarModalUbicacion" 
                                class="px-4 py-2 text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg">
                            Cancelar
                        </button>
                        <button @click="guardarUbicaciones" 
                                :disabled="!puedeGuardar"
                                class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 disabled:bg-gray-400 disabled:cursor-not-allowed">
                            <i class="fas fa-save mr-2"></i>
                            Guardar Ubicaciones
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>

    <script>
        function solicitudApp() {
            return {
                solicitudesAgrupadas: @json($solicitudesAgrupadas),
                ubicaciones: @json($ubicaciones),
                modalUbicacionAbierto: false,
                solicitudSeleccionada: null,
                ubicacionesForm: [],
                
                filtroEstado: 'todos',
                filtroOrigen: 'todos',
                searchTerm: '',

                get contadores() {
                    const grupos = this.solicitudesAgrupadasFiltradas;
                    return {
                        pendiente: grupos.filter(g => g.estado_general === 'pendiente').length,
                        recibido: grupos.filter(g => g.estado_general === 'recibido').length,
                        ubicado: grupos.filter(g => g.estado_general === 'ubicado').length,
                        total: grupos.length
                    };
                },

                get solicitudesAgrupadasFiltradas() {
                    return this.solicitudesAgrupadas.filter(grupo => {
                        const coincideEstado = this.filtroEstado === 'todos' || grupo.estado_general === this.filtroEstado;
                        const coincideOrigen = this.filtroOrigen === 'todos' || grupo.origen === this.filtroOrigen;

                        const codigoOrigen = grupo.origen === 'compra' ?
                            grupo.origen_especifico.codigocompra : grupo.origen_especifico.codigo_entrada;

                        const textoBusqueda = this.searchTerm.toLowerCase();
                        const coincideBusqueda = !this.searchTerm ||
                            codigoOrigen.toLowerCase().includes(textoBusqueda) ||
                            (grupo.proveedor?.nombre?.toLowerCase().includes(textoBusqueda)) ||
                            (grupo.cliente_general?.descripcion?.toLowerCase().includes(textoBusqueda)) ||
                            grupo.solicitudes.some(s =>
                                this.getNombreArticulo(s.articulo).toLowerCase().includes(textoBusqueda)
                            );

                        return coincideEstado && coincideOrigen && coincideBusqueda;
                    });
                },

                get cantidadDisponible() {
                    const totalAsignado = this.ubicacionesForm.reduce((sum, ubic) => sum + (parseInt(ubic.cantidad) || 0), 0);
                    return (this.solicitudSeleccionada?.cantidad || 0) - totalAsignado;
                },

                get puedeGuardar() {
                    if (this.ubicacionesForm.length === 0) return false;
                    
                    const totalAsignado = this.ubicacionesForm.reduce((sum, ubic) => sum + (parseInt(ubic.cantidad) || 0), 0);
                    const cantidadTotal = this.solicitudSeleccionada?.cantidad || 0;
                    
                    const ubicacionesValidas = this.ubicacionesForm.every(ubic => 
                        ubic.ubicacion_id && ubic.cantidad > 0
                    );
                    
                    return ubicacionesValidas && totalAsignado === cantidadTotal;
                },

                // Función para obtener el nombre correcto del artículo
                getNombreArticulo(articulo) {
                    if (!articulo) return 'N/A';

                    if (articulo.idTipoArticulo === 2) {
                        return articulo.codigo_repuesto || articulo.nombre || 'N/A';
                    } else {
                        return articulo.nombre || 'N/A';
                    }
                },

                // Función para mostrar las ubicaciones del artículo
getUbicacionesTexto(solicitud) {
    if (!solicitud.ubicaciones || solicitud.ubicaciones.length === 0) {
        return 'Sin ubicar';
    }
    
    // ✅ USAR nombre_ubicacion SI EXISTE, SINO BUSCAR EL NOMBRE
    return solicitud.ubicaciones.map(ubic => {
        if (ubic.nombre_ubicacion) {
            return `${ubic.nombre_ubicacion} (${ubic.cantidad})`;
        } else {
            // Buscar el nombre en el array de ubicaciones
            const nombre = this.getNombreUbicacion(ubic.ubicacion_id);
            return `${nombre} (${ubic.cantidad})`;
        }
    }).join(', ');
},

                // Función para mostrar el nombre correcto del tipo de artículo
                getTipoArticulo(idTipoArticulo) {
                    const tipos = {
                        1: 'Producto',
                        2: 'Repuesto',
                        3: 'Herramienta',
                        4: 'Suministro'
                    };
                    return tipos[idTipoArticulo] || 'Desconocido';
                },

                getEstadoClasses(estado) {
                    const classes = {
                        pendiente: 'bg-yellow-100 text-yellow-800',
                        recibido: 'bg-green-100 text-green-800',
                        ubicado: 'bg-purple-100 text-purple-800'
                    };
                    return classes[estado] || 'bg-gray-100 text-gray-800';
                },

                getEstadoIcon(estado) {
                    const icons = {
                        pendiente: 'fas fa-clock',
                        recibido: 'fas fa-check',
                        ubicado: 'fas fa-map-marker-alt'
                    };
                    return icons[estado] || 'fas fa-question';
                },

                formatFecha(fecha) {
                    if (!fecha) return 'N/A';
                    return new Date(fecha).toLocaleDateString('es-ES');
                },

                abrirModalUbicacion(solicitud) {
                    this.solicitudSeleccionada = solicitud;
                    
                    // ✅ VERIFICAR SI YA EXISTEN UBICACIONES Y CARGARLAS
                    if (solicitud.ubicaciones && solicitud.ubicaciones.length > 0) {
                        // Cargar las ubicaciones existentes
                        this.ubicacionesForm = solicitud.ubicaciones.map(ubic => ({
                            ubicacion_id: ubic.ubicacion_id,
                            cantidad: ubic.cantidad
                        }));
                    } else {
                        // Si no hay ubicaciones, empezar con una vacía
                        this.ubicacionesForm = [{
                            ubicacion_id: '',
                            cantidad: solicitud.cantidad
                        }];
                        
                        // Si la cantidad es mayor a 1, permitir distribución automática
                        if (solicitud.cantidad > 1) {
                            this.ubicacionesForm[0].cantidad = 1;
                        }
                    }
                    
                    this.modalUbicacionAbierto = true;
                },

                cerrarModalUbicacion() {
                    this.modalUbicacionAbierto = false;
                    this.solicitudSeleccionada = null;
                    this.ubicacionesForm = [];
                },

                agregarUbicacion() {
                    if (this.cantidadDisponible > 0) {
                        this.ubicacionesForm.push({
                            ubicacion_id: '',
                            cantidad: Math.min(this.cantidadDisponible, 1)
                        });
                    }
                },

                eliminarUbicacion(index) {
                    if (this.ubicacionesForm.length > 1) {
                        this.ubicacionesForm.splice(index, 1);
                    }
                },

               async guardarUbicaciones() {
    if (!this.puedeGuardar) return;

    try {
        const response = await axios.post('/solicitud-ingreso/guardar-ubicacion', {
            solicitud_id: this.solicitudSeleccionada.idSolicitudIngreso,
            ubicaciones: this.ubicacionesForm
        });

        if (response.data.success) {
            this.mostrarNotificacion(response.data.message, 'success');
            
            // ✅ ACTUALIZAR CORRECTAMENTE LAS UBICACIONES EN LA SOLICITUD
            this.solicitudesAgrupadas.forEach(grupo => {
                grupo.solicitudes.forEach(solicitud => {
                    if (solicitud.idSolicitudIngreso === this.solicitudSeleccionada.idSolicitudIngreso) {
                        // Actualizar estado
                        solicitud.estado = 'ubicado';
                        
                        // ✅ ACTUALIZAR LAS UBICACIONES CON LOS DATOS CORRECTOS
                        solicitud.ubicaciones = this.ubicacionesForm.map(ubic => ({
                            ubicacion_id: ubic.ubicacion_id,
                            cantidad: parseInt(ubic.cantidad),
                            // ✅ AGREGAR EL NOMBRE DE LA UBICACIÓN BUSCÁNDOLO EN EL ARRAY
                            nombre_ubicacion: this.getNombreUbicacion(ubic.ubicacion_id)
                        }));
                        
                        // Recalcular estado general del grupo
                        grupo.estado_general = this.calcularEstadoGeneral(grupo.solicitudes);
                    }
                });
            });

            this.cerrarModalUbicacion();
        }
    } catch (error) {
        console.error('Error al guardar ubicaciones:', error);
        const message = error.response?.data?.message || 'Error al guardar las ubicaciones';
        this.mostrarNotificacion(message, 'error');
    }
},

// ✅ AGREGAR ESTA FUNCIÓN PARA OBTENER EL NOMBRE DE LA UBICACIÓN
getNombreUbicacion(ubicacionId) {
    const ubicacion = this.ubicaciones.find(u => u.idUbicacion == ubicacionId);
    return ubicacion ? ubicacion.nombre : 'Ubicación desconocida';
},

                async cambiarEstado(id, nuevoEstado) {
                    if (nuevoEstado === 'ubicado') {
                        // Buscar la solicitud y abrir modal de ubicación
                        let solicitudEncontrada = null;
                        this.solicitudesAgrupadas.forEach(grupo => {
                            const solicitud = grupo.solicitudes.find(s => s.idSolicitudIngreso === id);
                            if (solicitud) solicitudEncontrada = solicitud;
                        });
                        
                        if (solicitudEncontrada) {
                            this.abrirModalUbicacion(solicitudEncontrada);
                        }
                        return;
                    }

                    try {
                        if (confirm('¿Estás seguro de cambiar el estado de este artículo?')) {
                            const response = await axios.post(`/solicitud-ingreso/${id}/cambiar-estado`, {
                                estado: nuevoEstado
                            });

                            if (response.data.success) {
                                this.solicitudesAgrupadas.forEach(grupo => {
                                    const solicitud = grupo.solicitudes.find(s => s.idSolicitudIngreso === id);
                                    if (solicitud) {
                                        solicitud.estado = nuevoEstado;
                                        grupo.estado_general = this.calcularEstadoGeneral(grupo.solicitudes);
                                    }
                                });

                                this.mostrarNotificacion('Estado actualizado correctamente', 'success');
                            }
                        }
                    } catch (error) {
                        console.error('Error al cambiar estado:', error);
                        this.mostrarNotificacion('Error al cambiar el estado', 'error');
                    }
                },

                async cambiarEstadoGrupo(grupo, nuevoEstado) {
                    try {
                        if (confirm(`¿Estás seguro de cambiar el estado de todos los artículos (${grupo.solicitudes.length}) a ${nuevoEstado}?`)) {
                            // Cambiar estado de todas las solicitudes del grupo
                            const promises = grupo.solicitudes.map(solicitud =>
                                axios.post(`/solicitud-ingreso/${solicitud.idSolicitudIngreso}/cambiar-estado`, {
                                    estado: nuevoEstado
                                })
                            );

                            const results = await Promise.all(promises);

                            if (results.every(result => result.data.success)) {
                                // Actualizar estados localmente
                                grupo.solicitudes.forEach(solicitud => {
                                    solicitud.estado = nuevoEstado;
                                });
                                grupo.estado_general = nuevoEstado;

                                this.mostrarNotificacion(`Estado de ${grupo.solicitudes.length} artículos actualizado a ${nuevoEstado}`, 'success');
                            }
                        }
                    } catch (error) {
                        console.error('Error al cambiar estado del grupo:', error);
                        this.mostrarNotificacion('Error al cambiar el estado del grupo', 'error');
                    }
                },

                calcularEstadoGeneral(solicitudes) {
                    const estados = [...new Set(solicitudes.map(s => s.estado))];

                    if (estados.length === 1) {
                        return estados[0];
                    }

                    if (estados.includes('pendiente')) {
                        return 'pendiente';
                    }

                    if (estados.includes('recibido')) {
                        return 'recibido';
                    }

                    return 'ubicado';
                },

                mostrarNotificacion(mensaje, tipo) {
                    const alerta = document.createElement('div');
                    alerta.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
                        tipo === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
                    }`;
                    alerta.textContent = mensaje;
                    document.body.appendChild(alerta);

                    setTimeout(() => {
                        if (document.body.contains(alerta)) {
                            document.body.removeChild(alerta);
                        }
                    }, 3000);
                }
            }
        }
    </script>
</x-layout.default>