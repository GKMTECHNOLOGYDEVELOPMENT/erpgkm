<div x-data="{
    articulosAsignados: [],
    loading: true,
    userId: {{ $usuario->idUsuario ?? 0 }},
    
    // Nuevas variables para estadísticas
    estadisticas: {},
    filtroEstado: 'todos',
    filtroTipo: 'todos',

    async loadArticulosAsignados() {
        try {
            this.loading = true;
            const response = await fetch(`/usuario/${this.userId}/articulos-activos`);
            const data = await response.json();
            
            if (data.success) {
                this.articulosAsignados = data.articulos;
                this.estadisticas = data.estadisticas || {};
            } else {
                toastr.error('Error al cargar artículos asignados');
                this.articulosAsignados = [];
                this.estadisticas = {};
            }
        } catch (error) {
            console.error('Error:', error);
            toastr.error('Error al cargar artículos asignados');
            this.articulosAsignados = [];
            this.estadisticas = {};
        } finally {
            this.loading = false;
        }
    },

    // Método para obtener artículos filtrados
    get articulosFiltrados() {
        let filtrados = this.articulosAsignados;
        
        // Filtrar por estado
        if (this.filtroEstado !== 'todos') {
            filtrados = filtrados.filter(a => a.estado_articulo === this.filtroEstado);
        }
        
        // Filtrar por tipo
        if (this.filtroTipo !== 'todos') {
            filtrados = filtrados.filter(a => a.tipo_asignacion === this.filtroTipo);
        }
        
        return filtrados;
    },

    formatFecha(fecha) {
        if (!fecha) return 'No asignada';
        return new Date(fecha).toLocaleDateString('es-PE', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    },

    formatFechaCompleta(fecha) {
        if (!fecha) return '';
        return new Date(fecha).toLocaleDateString('es-PE', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    },

    getEstadoClass(estado) {
        const classes = {
            'activo': 'badge bg-green-500 text-white',
            'entregado': 'badge bg-blue-500 text-white',
            'pendiente': 'badge bg-yellow-500 text-white',
            'dañado': 'badge bg-orange-500 text-white',
            'perdido': 'badge bg-red-500 text-white',
            'devuelto': 'badge bg-gray-500 text-white'
        };
        return classes[estado] || 'badge bg-gray-500 text-white';
    },

    getTipoAsignacionClass(tipo) {
        const classes = {
            'uso_diario': 'badge bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
            'prestamo': 'badge bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            'reposicion': 'badge bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'trabajo_a_realizar': 'badge bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200'
        };
        return classes[tipo] || 'badge bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
    },

    getTipoAsignacionText(tipo) {
        const textos = {
            'uso_diario': 'Uso Diario',
            'prestamo': 'Préstamo',
            'reposicion': 'Reposición',
            'trabajo_a_realizar': 'Trabajo a Realizar'
        };
        return textos[tipo] || tipo;
    },

    getTipoIcono(tipo) {
        const iconos = {
            'uso_diario': 'fas fa-user-clock',
            'prestamo': 'fas fa-handshake',
            'reposicion': 'fas fa-boxes',
            'trabajo_a_realizar': 'fas fa-tools'
        };
        return iconos[tipo] || 'fas fa-box';
    },

    getNombreArticulo(articulo) {
        // Prioridad: 1. nombre_articulo, 2. nombre, 3. nombre_mostrar
        return articulo.nombre_articulo || articulo.nombre || articulo.nombre_mostrar || 'Artículo sin nombre';
    },

    getCodigoArticulo(articulo) {
        // Prioridad: 1. codigo_articulo, 2. codigo, 3. codigo_repuesto
        return articulo.codigo_articulo || articulo.codigo || articulo.codigo_repuesto || null;
    },

    getDescripcionArticulo(articulo) {
        const partes = [];
        
        if (articulo.codigo_barras) {
            partes.push('Código: ' + articulo.codigo_barras);
        }
        
        if (articulo.sku) {
            partes.push('SKU: ' + articulo.sku);
        }
        
        const codigo = this.getCodigoArticulo(articulo);
        if (codigo) {
            partes.push('Ref: ' + codigo);
        }
        
        return partes.join(' | ');
    },

    getTipoNombre(tipoId) {
        const tipos = {
            '1': 'Producto',
            '2': 'Repuesto',
            '3': 'Insumo',
            '4': 'Herramienta',
            '0': 'Sin tipo'
        };
        return tipos[tipoId] || 'Tipo ' + tipoId;
    },

    getTipoColor(tipoId) {
        const colores = {
            '1': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            '2': 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
            '3': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            '4': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            '0': 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
        };
        return colores[tipoId] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
    },

    // Métodos para estadísticas
    getTotalArticulos() {
        return this.articulosAsignados.length;
    },

    getCantidadTotal() {
        return this.articulosAsignados.reduce((total, articulo) => total + articulo.cantidad, 0);
    },

    getArticulosActivos() {
        return this.articulosAsignados.filter(a => a.estado_articulo === 'activo').length;
    },

    getArticulosEntregados() {
        return this.articulosAsignados.filter(a => a.estado_articulo === 'entregado').length;
    },

    getArticulosPendientes() {
        return this.articulosAsignados.filter(a => a.estado_articulo === 'pendiente').length;
    },

    getArticulosDanados() {
        return this.articulosAsignados.filter(a => a.estado_articulo === 'dañado').length;
    },

    getArticulosPerdidos() {
        return this.articulosAsignados.filter(a => a.estado_articulo === 'perdido').length;
    },

    getArticulosDevueltos() {
        return this.articulosAsignados.filter(a => a.estado_articulo === 'devuelto').length;
    },

    getUsoDiarioCount() {
        return this.articulosAsignados.filter(a => a.tipo_asignacion === 'uso_diario').length;
    },

    getPrestamoCount() {
        return this.articulosAsignados.filter(a => a.tipo_asignacion === 'prestamo').length;
    },

    getReposicionCount() {
        return this.articulosAsignados.filter(a => a.tipo_asignacion === 'reposicion').length;
    },

    getTrabajoRealizarCount() {
        return this.articulosAsignados.filter(a => a.tipo_asignacion === 'trabajo_a_realizar').length;
    },

    getConDevolucionCount() {
        return this.articulosAsignados.filter(a => a.requiere_devolucion == 1).length;
    },

    // Métodos para resumen
    groupByTipoAsignacion() {
        const grupos = {};
        
        this.articulosAsignados.forEach(articulo => {
            const tipo = articulo.tipo_asignacion || 'prestamo';
            if (!grupos[tipo]) {
                grupos[tipo] = {
                    nombre: this.getTipoAsignacionText(tipo),
                    clase: this.getTipoAsignacionClass(tipo),
                    icono: this.getTipoIcono(tipo),
                    cantidad: 0,
                    items: []
                };
            }
            grupos[tipo].cantidad += articulo.cantidad;
            grupos[tipo].items.push(articulo);
        });
        
        return grupos;
    },

    getEstadisticasAvanzadas() {
        return {
            total_articulos: this.getTotalArticulos(),
            cantidad_total: this.getCantidadTotal(),
            activos: this.getArticulosActivos(),
            entregados: this.getArticulosEntregados(),
            pendientes: this.getArticulosPendientes(),
            dañados: this.getArticulosDanados(),
            perdidos: this.getArticulosPerdidos(),
            devueltos: this.getArticulosDevueltos(),
            uso_diario: this.getUsoDiarioCount(),
            prestamo: this.getPrestamoCount(),
            reposicion: this.getReposicionCount(),
            trabajo_a_realizar: this.getTrabajoRealizarCount(),
            con_devolucion: this.getConDevolucionCount()
        };
    },

    resetFiltros() {
        this.filtroEstado = 'todos';
        this.filtroTipo = 'todos';
    },

    init() {
        this.loadArticulosAsignados();
    }
}" x-init="init()">
    
    <div class="mb-5">
        <h3 class="text-xl font-bold">Equipos Asignados</h3>
        <p class="text-gray-500">Artículos asignados a este usuario (incluye pendientes, activos y entregados)</p>
    </div>

    <!-- Filtros -->
    <div class="panel mb-5">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <h5 class="font-semibold">Filtrar por:</h5>
                <select x-model="filtroEstado" class="form-select w-40">
                    <option value="todos">Todos los estados</option>
                    <option value="pendiente">Pendientes</option>
                    <option value="entregado">Entregados</option>
                    <option value="activo">Activos</option>
                    <option value="dañado">Dañados</option>
                    <option value="perdido">Perdidos</option>
                    <option value="devuelto">Devueltos</option>
                </select>
                
                <select x-model="filtroTipo" class="form-select w-40">
                    <option value="todos">Todos los tipos</option>
                    <option value="uso_diario">Uso Diario</option>
                    <option value="prestamo">Préstamo</option>
                    <option value="reposicion">Reposición</option>
                    <option value="trabajo_a_realizar">Trabajo a Realizar</option>
                </select>
                
                <button @click="resetFiltros()" class="btn btn-outline-primary">
                    <i class="fas fa-redo mr-1"></i> Limpiar filtros
                </button>
            </div>
            
            <div class="text-sm text-gray-500">
                <span x-text="articulosFiltrados.length + ' artículo(s) filtrados'"></span>
                <span class="mx-2">•</span>
                <span x-text="articulosFiltrados.reduce((t,a) => t + a.cantidad, 0) + ' unidad(es)'"></span>
            </div>
        </div>
    </div>

    <!-- Resumen Estadístico -->
    <template x-if="!loading && articulosAsignados.length > 0">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-5">
            <div class="panel text-center">
                <div class="text-3xl font-bold text-primary" x-text="getTotalArticulos()"></div>
                <div class="text-gray-500">Total Artículos</div>
                <div class="text-sm text-gray-400 mt-1" x-text="getCantidadTotal() + ' unidades'"></div>
            </div>
            
            <div class="panel text-center">
                <div class="text-3xl font-bold text-blue-600" x-text="getArticulosActivos()"></div>
                <div class="text-gray-500">Activos</div>
                <div class="text-sm text-blue-400 mt-1">
                    <template x-if="getArticulosActivos() > 0">
                        <span x-text="Math.round((getArticulosActivos()/getTotalArticulos())*100) + '% activos'"></span>
                    </template>
                </div>
            </div>
            
            <div class="panel text-center">
                <div class="text-3xl font-bold text-yellow-600" x-text="getArticulosPendientes()"></div>
                <div class="text-gray-500">Pendientes</div>
                <div class="text-sm text-yellow-400 mt-1">
                    <template x-if="getArticulosPendientes() > 0">
                        <i class="fas fa-clock mr-1"></i>Por entregar
                    </template>
                </div>
            </div>
            
            <div class="panel text-center">
                <div class="text-3xl font-bold text-purple-600" x-text="getUsoDiarioCount()"></div>
                <div class="text-gray-500">Uso Diario</div>
                <div class="text-sm text-purple-400 mt-1">
                    <template x-if="getUsoDiarioCount() > 0">
                        <i class="fas fa-calendar-day mr-1"></i>Asignación permanente
                    </template>
                </div>
            </div>
            
            <div class="panel text-center">
                <div class="text-3xl font-bold text-orange-600" x-text="getTrabajoRealizarCount()"></div>
                <div class="text-gray-500">Trabajo a Realizar</div>
                <div class="text-sm text-orange-400 mt-1">
                    <template x-if="getTrabajoRealizarCount() > 0">
                        <i class="fas fa-tools mr-1"></i>Para trabajos/servicios
                    </template>
                </div>
            </div>
        </div>
    </template>

    <!-- Resumen por Tipo de Asignación -->
    <template x-if="!loading && articulosAsignados.length > 0">
        <div class="panel mb-5">
            <h5 class="font-semibold text-lg mb-4">Distribución por Tipo de Asignación</h5>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <template x-for="(grupo, tipo) in groupByTipoAsignacion()" :key="tipo">
                    <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-2">
                                <i :class="grupo.icono" class="text-gray-500"></i>
                                <span class="font-medium" x-text="grupo.nombre"></span>
                            </div>
                            <span :class="grupo.clase" class="px-2 py-1 rounded text-xs"
                                  x-text="grupo.items.length + ' items'"></span>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Cantidad total:</span>
                                <span class="font-medium" x-text="grupo.cantidad"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-blue-500">Activos:</span>
                                <span class="font-medium" 
                                      x-text="grupo.items.filter(i => i.estado_articulo === 'activo').length"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-yellow-500">Pendientes:</span>
                                <span class="font-medium"
                                      x-text="grupo.items.filter(i => i.estado_articulo === 'pendiente').length"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-green-500">Entregados:</span>
                                <span class="font-medium"
                                      x-text="grupo.items.filter(i => i.estado_articulo === 'entregado').length"></span>
                            </div>
                            <template x-if="grupo.nombre === 'Préstamo'">
                                <div class="flex justify-between text-sm">
                                    <span class="text-red-500">Con devolución:</span>
                                    <span class="font-medium"
                                          x-text="grupo.items.filter(i => i.requiere_devolucion == 1).length"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </template>

    <!-- Contenido Principal -->
    <div class="space-y-4">
        <template x-if="loading">
            <div class="text-center py-10">
                <div class="animate-spin border-4 border-primary border-t-transparent rounded-full w-12 h-12 mx-auto"></div>
                <p class="mt-3 text-gray-600">Cargando artículos asignados...</p>
            </div>
        </template>

        <template x-if="!loading && articulosAsignados.length === 0">
            <div class="text-center py-10 border-2 border-dashed rounded-lg">
                <i class="fas fa-laptop text-gray-400 text-4xl mb-3"></i>
                <h4 class="text-lg font-semibold text-gray-600">No hay artículos asignados</h4>
                <p class="text-gray-500">Este usuario no tiene artículos asignados actualmente.</p>
            </div>
        </template>

        <template x-if="!loading && articulosFiltrados.length > 0">
            <!-- Tabla de artículos -->
            <div class="panel">
                <div class="flex justify-between items-center mb-4">
                    <h5 class="font-semibold text-lg">Listado de Artículos Asignados</h5>
                    <div class="text-sm text-gray-500">
                        <span x-text="articulosFiltrados.length + ' artículo(s)'"></span>
                        <span class="mx-2">•</span>
                        <span x-text="articulosFiltrados.reduce((t,a) => t + a.cantidad, 0) + ' unidad(es)'"></span>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-800">
                                <th class="py-3 px-4 text-left">Artículo</th>
                                <th class="py-3 px-4 text-left">Tipo</th>
                                <th class="py-3 px-4 text-left">Identificación</th>
                                <th class="py-3 px-4 text-left">Cantidad</th>
                                <th class="py-3 px-4 text-left">Fechas</th>
                                <th class="py-3 px-4 text-left">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="articulo in articulosFiltrados" :key="articulo.id">
                                <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-900">
                                    <td class="py-3 px-4">
                                        <div class="font-medium" x-text="getNombreArticulo(articulo)"></div>
                                        <div class="text-xs text-gray-500 mt-1" x-text="getDescripcionArticulo(articulo)"></div>
                                        <template x-if="articulo.observaciones">
                                            <div class="text-xs text-blue-500 mt-1" title="Observaciones">
                                                <i class="fas fa-sticky-note mr-1"></i>
                                                <span x-text="articulo.observaciones.substring(0, 50) + (articulo.observaciones.length > 50 ? '...' : '')"></span>
                                            </div>
                                        </template>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="space-y-1">
                                            <div class="flex items-center">
                                                <i :class="getTipoIcono(articulo.tipo_asignacion)" class="mr-2 text-gray-400"></i>
                                                <span class="px-2 py-1 rounded text-xs" 
                                                      :class="getTipoAsignacionClass(articulo.tipo_asignacion)"
                                                      x-text="getTipoAsignacionText(articulo.tipo_asignacion)"></span>
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                <template x-if="articulo.requiere_devolucion == 1">
                                                    <i class="fas fa-undo mr-1 text-red-400"></i> Con devolución
                                                </template>
                                                <template x-if="articulo.requiere_devolucion == 0 && articulo.tipo_asignacion !== 'trabajo_a_realizar'">
                                                    <i class="fas fa-infinity mr-1 text-green-400"></i> Sin devolución
                                                </template>
                                                <template x-if="articulo.tipo_asignacion === 'trabajo_a_realizar'">
                                                    <i class="fas fa-tools mr-1 text-orange-400"></i> Para trabajos/servicios
                                                </template>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div>
                                            <template x-if="articulo.numero_serie">
                                                <div class="font-medium">
                                                    <i class="fas fa-hashtag mr-1 text-gray-400"></i>
                                                    <span x-text="articulo.numero_serie"></span>
                                                </div>
                                            </template>
                                            <template x-if="articulo.codigo_asignacion">
                                                <div class="text-xs text-gray-500 mt-1">
                                                    <i class="fas fa-tag mr-1"></i>
                                                    Asignación: <span x-text="articulo.codigo_asignacion"></span>
                                                </div>
                                            </template>
                                            <template x-if="articulo.codigo_solicitud">
                                                <div class="text-xs text-gray-500 mt-1">
                                                    <i class="fas fa-file-alt mr-1"></i>
                                                    Solicitud: <span x-text="articulo.codigo_solicitud"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="font-medium text-lg" x-text="articulo.cantidad"></span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex flex-col space-y-1">
                                            <div class="text-sm">
                                                <i class="fas fa-calendar-plus mr-1 text-blue-400"></i>
                                                <span x-text="formatFecha(articulo.fecha_asignacion)"></span>
                                            </div>
                                            <template x-if="articulo.fecha_entrega_real">
                                                <div class="text-xs text-green-600">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Entregado: <span x-text="formatFecha(articulo.fecha_entrega_real)"></span>
                                                </div>
                                            </template>
                                            <template x-if="articulo.fecha_devolucion_esperada">
                                                <div class="text-xs text-orange-600">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Devuelve: <span x-text="formatFecha(articulo.fecha_devolucion_esperada)"></span>
                                                </div>
                                            </template>
                                            <template x-if="articulo.fecha_devolucion">
                                                <div class="text-xs text-gray-500">
                                                    <i class="fas fa-undo mr-1"></i>
                                                    Devuelto: <span x-text="formatFecha(articulo.fecha_devolucion)"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span :class="getEstadoClass(articulo.estado_articulo)" 
                                              class="px-3 py-1 rounded-full text-xs font-medium"
                                              x-text="articulo.estado_articulo.toUpperCase()"></span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Pie de tabla -->
                <div class="mt-4 pt-4 border-t">
                    <div class="flex justify-between items-center text-sm text-gray-500">
                        <div>
                            Mostrando <span class="font-medium" x-text="articulosFiltrados.length"></span> artículo(s) filtrados
                        </div>
                        <div class="flex flex-wrap gap-4">
                            <div>
                                <span class="inline-block w-3 h-3 bg-blue-500 rounded-full mr-1"></span>
                                <span>Activos: </span>
                                <span class="font-medium" x-text="getArticulosActivos()"></span>
                            </div>
                            <div>
                                <span class="inline-block w-3 h-3 bg-yellow-500 rounded-full mr-1"></span>
                                <span>Pendientes: </span>
                                <span class="font-medium" x-text="getArticulosPendientes()"></span>
                            </div>
                            <div>
                                <span class="inline-block w-3 h-3 bg-green-500 rounded-full mr-1"></span>
                                <span>Entregados: </span>
                                <span class="font-medium" x-text="getArticulosEntregados()"></span>
                            </div>
                            <div>
                                <span class="inline-block w-3 h-3 bg-orange-500 rounded-full mr-1"></span>
                                <span>Trabajo: </span>
                                <span class="font-medium" x-text="getTrabajoRealizarCount()"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <template x-if="!loading && articulosFiltrados.length === 0 && articulosAsignados.length > 0">
            <div class="text-center py-10 border-2 border-dashed rounded-lg">
                <i class="fas fa-filter text-gray-400 text-4xl mb-3"></i>
                <h4 class="text-lg font-semibold text-gray-600">No hay resultados con los filtros aplicados</h4>
                <p class="text-gray-500">Intenta con otros filtros o <a @click="resetFiltros()" class="text-blue-500 cursor-pointer hover:underline">limpiar los filtros</a>.</p>
            </div>
        </template>
    </div>
</div>