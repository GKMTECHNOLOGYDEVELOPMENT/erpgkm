<div x-data="{
    articulosAsignados: [],
    loading: true,
    userId: {{ $usuario->idUsuario ?? 0 }},

    async loadArticulosAsignados() {
        try {
            this.loading = true;
            const response = await fetch(`/usuario/${this.userId}/articulos-activos`);
            const data = await response.json();
            
            if (data.success) {
                this.articulosAsignados = data.articulos;
            } else {
                toastr.error('Error al cargar artículos asignados');
                this.articulosAsignados = [];
            }
        } catch (error) {
            console.error('Error:', error);
            toastr.error('Error al cargar artículos asignados');
            this.articulosAsignados = [];
        } finally {
            this.loading = false;
        }
    },

    formatFecha(fecha) {
        if (!fecha) return 'No asignada';
        return new Date(fecha).toLocaleDateString('es-PE', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    },

    getEstadoClass(estado) {
        const classes = {
            'activo': 'badge bg-green-500 text-white',
            'dañado': 'badge bg-orange-500 text-white',
            'perdido': 'badge bg-red-500 text-white',
            'devuelto': 'badge bg-gray-500 text-white'
        };
        return classes[estado] || 'badge bg-gray-500 text-white';
    },

    getNombreArticulo(articulo) {
        if (articulo.idTipoArticulo == 2) {
            // Tipo 2: Repuesto - usar código repuesto si existe
            return articulo.codigo_repuesto || articulo.nombre || 'Repuesto sin nombre';
        }
        // Otros tipos: Producto, Insumo, Herramienta - usar nombre
        return articulo.nombre || 'Sin nombre';
    },

    getDescripcionArticulo(articulo) {
        if (articulo.idTipoArticulo == 2) {
            // Para repuestos, mostrar código de barras si existe
            return articulo.codigo_barras ? 'Código: ' + articulo.codigo_barras : '';
        }
        // Para otros tipos, mostrar SKU si existe
        return articulo.sku ? 'SKU: ' + articulo.sku : '';
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

    getTotalArticulos() {
        return this.articulosAsignados.length;
    },

    getArticulosActivos() {
        return this.articulosAsignados.filter(a => a.estado_articulo === 'activo').length;
    },

    getArticulosDanados() {
        return this.articulosAsignados.filter(a => a.estado_articulo === 'dañado').length;
    },

    getArticulosPerdidos() {
        return this.articulosAsignados.filter(a => a.estado_articulo === 'perdido').length;
    },

    getCantidadTotal() {
        return this.articulosAsignados.reduce((total, articulo) => total + articulo.cantidad, 0);
    },

    groupByTipoArticulo() {
        const grupos = {};
        
        this.articulosAsignados.forEach(articulo => {
            const tipo = articulo.idTipoArticulo || '0';
            if (!grupos[tipo]) {
                grupos[tipo] = {
                    nombre: this.getTipoNombre(tipo),
                    color: this.getTipoColor(tipo),
                    cantidad: 0,
                    items: []
                };
            }
            grupos[tipo].cantidad += articulo.cantidad;
            grupos[tipo].items.push(articulo);
        });
        
        return grupos;
    },

    groupByEstado() {
        const grupos = {};
        
        this.articulosAsignados.forEach(articulo => {
            const estado = articulo.estado_articulo || 'desconocido';
            if (!grupos[estado]) {
                grupos[estado] = {
                    cantidad: 0,
                    items: []
                };
            }
            grupos[estado].cantidad += articulo.cantidad;
            grupos[estado].items.push(articulo);
        });
        
        return grupos;
    },

    getEstadisticasTipo() {
        const stats = {};
        Object.entries(this.groupByTipoArticulo()).forEach(([tipoId, grupo]) => {
            stats[tipoId] = {
                nombre: grupo.nombre,
                total: grupo.items.length,
                cantidad: grupo.cantidad,
                activos: grupo.items.filter(i => i.estado_articulo === 'activo').length,
                danados: grupo.items.filter(i => i.estado_articulo === 'dañado').length,
                perdidos: grupo.items.filter(i => i.estado_articulo === 'perdido').length
            };
        });
        return stats;
    },

    init() {
        this.loadArticulosAsignados();
    }
}" x-init="init()">
    
    <div class="mb-5">
        <h3 class="text-xl font-bold">Equipos Asignados</h3>
        <p class="text-gray-500">Artículos activos asignados a este usuario</p>
    </div>

    <!-- Resumen Estadístico -->
    <template x-if="!loading && articulosAsignados.length > 0">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
            <div class="panel text-center">
                <div class="text-3xl font-bold text-primary" x-text="getTotalArticulos()"></div>
                <div class="text-gray-500">Total Artículos</div>
                <div class="text-sm text-gray-400 mt-1" x-text="getCantidadTotal() + ' unidades'"></div>
            </div>
            
            <div class="panel text-center">
                <div class="text-3xl font-bold text-green-600" x-text="getArticulosActivos()"></div>
                <div class="text-gray-500">Activos</div>
                <div class="text-sm text-green-400 mt-1">
                    <template x-if="getArticulosActivos() === getTotalArticulos()">✓ Todos activos</template>
                    <template x-if="getArticulosActivos() < getTotalArticulos()">
                        <span x-text="Math.round((getArticulosActivos()/getTotalArticulos())*100) + '%'"></span>
                    </template>
                </div>
            </div>
            
            <div class="panel text-center">
                <div class="text-3xl font-bold text-orange-600" x-text="getArticulosDanados()"></div>
                <div class="text-gray-500">Dañados</div>
                <div class="text-sm text-orange-400 mt-1">
                    <template x-if="getArticulosDanados() > 0">
                        <i class="fas fa-exclamation-triangle mr-1"></i>Requiere atención
                    </template>
                </div>
            </div>
            
            <div class="panel text-center">
                <div class="text-3xl font-bold text-red-600" x-text="getArticulosPerdidos()"></div>
                <div class="text-gray-500">Perdidos</div>
                <div class="text-sm text-red-400 mt-1">
                    <template x-if="getArticulosPerdidos() > 0">
                        <i class="fas fa-times-circle mr-1"></i>Reportar
                    </template>
                </div>
            </div>
        </div>
    </template>

    <!-- Resumen por Tipo -->
    <template x-if="!loading && articulosAsignados.length > 0">
        <div class="panel mb-5">
            <h5 class="font-semibold text-lg mb-4">Distribución por Tipo</h5>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <template x-for="(stats, tipoId) in getEstadisticasTipo()" :key="tipoId">
                    <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-3">
                            <span class="font-medium" x-text="stats.nombre"></span>
                            <span class="px-2 py-1 rounded text-xs" :class="getTipoColor(tipoId)"
                                  x-text="stats.total + ' items'"></span>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Cantidad total:</span>
                                <span class="font-medium" x-text="stats.cantidad"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-green-500">Activos:</span>
                                <span class="font-medium" x-text="stats.activos"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-orange-500">Dañados:</span>
                                <span class="font-medium" x-text="stats.danados"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-red-500">Perdidos:</span>
                                <span class="font-medium" x-text="stats.perdidos"></span>
                            </div>
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

        <template x-if="!loading && articulosAsignados.length > 0">
            <!-- Tabla de artículos -->
            <div class="panel">
                <div class="flex justify-between items-center mb-4">
                    <h5 class="font-semibold text-lg">Listado de Artículos Asignados</h5>
                    <div class="text-sm text-gray-500">
                        <span x-text="getTotalArticulos() + ' artículo(s)'"></span>
                        <span class="mx-2">•</span>
                        <span x-text="getCantidadTotal() + ' unidad(es)'"></span>
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
                                <th class="py-3 px-4 text-left">Fecha Asignación</th>
                                <th class="py-3 px-4 text-left">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="articulo in articulosAsignados" :key="articulo.id">
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
                                        <span class="px-2 py-1 rounded text-xs" 
                                              :class="getTipoColor(articulo.idTipoArticulo)"
                                              x-text="getTipoNombre(articulo.idTipoArticulo)"></span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <template x-if="articulo.numero_serie">
                                            <div>
                                                <div class="font-medium">
                                                    <i class="fas fa-hashtag mr-1 text-gray-400"></i>
                                                    <span x-text="articulo.numero_serie"></span>
                                                </div>
                                                <template x-if="articulo.codigo_barras">
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        <i class="fas fa-barcode mr-1"></i>
                                                        <span x-text="articulo.codigo_barras"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                        <template x-if="!articulo.numero_serie">
                                            <div>
                                                <template x-if="articulo.codigo_barras">
                                                    <div class="font-medium">
                                                        <i class="fas fa-barcode mr-1 text-gray-400"></i>
                                                        <span x-text="articulo.codigo_barras"></span>
                                                    </div>
                                                </template>
                                                <template x-if="!articulo.codigo_barras">
                                                    <span class="text-gray-400 italic">Sin identificación</span>
                                                </template>
                                            </div>
                                        </template>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="font-medium text-lg" x-text="articulo.cantidad"></span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex flex-col">
                                            <span class="font-medium" x-text="formatFecha(articulo.fecha_asignacion)"></span>
                                            <template x-if="articulo.fecha_devolucion">
                                                <span class="text-xs text-gray-500">
                                                    <i class="fas fa-undo mr-1"></i>
                                                    Devuelto: <span x-text="formatFecha(articulo.fecha_devolucion)"></span>
                                                </span>
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
                            Mostrando <span class="font-medium" x-text="articulosAsignados.length"></span> artículo(s)
                        </div>
                        <div class="flex space-x-4">
                            <div>
                                <span class="inline-block w-3 h-3 bg-green-500 rounded-full mr-1"></span>
                                <span>Activos: </span>
                                <span class="font-medium" x-text="getArticulosActivos()"></span>
                            </div>
                            <div>
                                <span class="inline-block w-3 h-3 bg-orange-500 rounded-full mr-1"></span>
                                <span>Dañados: </span>
                                <span class="font-medium" x-text="getArticulosDanados()"></span>
                            </div>
                            <div>
                                <span class="inline-block w-3 h-3 bg-red-500 rounded-full mr-1"></span>
                                <span>Perdidos: </span>
                                <span class="font-medium" x-text="getArticulosPerdidos()"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>