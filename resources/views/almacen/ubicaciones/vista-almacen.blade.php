<x-layout.default>
    <script src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .heatmap-full-section {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            overflow: auto;
            max-height: 80vh;
        }

        .heatmap-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .heatmap-header h3 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
        }

        .heatmap-header p {
            margin: 8px 0 0 0;
            color: #666;
            font-size: 16px;
        }

        #heatmap {
            width: 100%;
            height: 100%;
            min-height: 700px;
        }

        .echarts-tooltip {
            font-size: 14px !important;
        }

        .heatmap-title-placeholder {
            height: 60px;
            display: flex;
            align-items: center;
        }

        @keyframes progress {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(400%); }
        }
    </style>

    <div x-data="almacenHeatmap" x-init="init()" class="container">
        <!-- Header -->
        <div class="mb-6 rounded-xl border border-slate-200 bg-white px-5 py-4 shadow-sm">
            <div class="flex items-center gap-3">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50">
                    <i class="fa-solid fa-warehouse text-indigo-600"></i>
                </span>
                <div>
                    <h1 class="text-xl md:text-2xl font-bold text-slate-800">
                        Vista del Almacén por SEDES
                    </h1>
                    <p class="mt-0.5 text-sm text-slate-500">
                        Análisis de actividad y ocupación en tiempo real
                    </p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <i class="fas fa-box text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600">Total Racks</p>
                        <p class="text-2xl font-bold text-slate-800" x-text="stats.totalRacks">0</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-green-50 rounded-lg">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600">Racks Activos</p>
                        <p class="text-2xl font-bold text-slate-800" x-text="stats.activeRacks">0</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-purple-50 rounded-lg">
                        <i class="fas fa-chart-line text-purple-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600">Actividad Promedio</p>
                        <p class="text-2xl font-bold text-slate-800" x-text="stats.avgActivity + '%'">0%</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-amber-50 rounded-lg">
                        <i class="fas fa-cube text-amber-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600">Ubicaciones Ocupadas</p>
                        <p class="text-2xl font-bold text-slate-800" x-text="stats.ocupadas + '/' + stats.totalUbicaciones">0/0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="panel rounded-xl shadow-sm border border-slate-200 p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Periodo -->
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">Periodo</label>
                    <select x-model="filtro.periodo" @change="aplicarFiltros()"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                        <option value="7">Últimos 7 días</option>
                        <option value="30" selected>Últimos 30 días</option>
                        <option value="90">Últimos 90 días</option>
                    </select>
                </div>

                <!-- Sede -->
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">Sede</label>
                    <select x-model="filtro.sede" @change="aplicarFiltros()"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                        <option value="">Todas las sedes</option>
                        @foreach($sedes as $sede)
                            <option value="{{ $sede }}">{{ $sede }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Buscador -->
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">Buscar Rack</label>
                    <input x-model="filtro.buscar" @input="debounceFilter()" type="text" placeholder="Ej: R01, R02..."
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm placeholder-slate-400 focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                </div>

                <!-- Modo Vista -->
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">Modo Vista</label>
                    <select x-model="mode" @change="updateChart()"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                        <option value="heat">Por Actividad</option>
                        <option value="fill">Por Ocupación</option>
                    </select>
                </div>
            </div>

            <!-- Botones acciones -->
            <div class="flex flex-wrap gap-3">
                <button @click="toggleLabels()" :class="labels ? 'btn btn-primary' : 'btn btn-secondary'"
                    class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition">
                    <i class="fas fa-tag"></i>
                    Etiquetas: <span x-text="labels ? 'ON' : 'OFF'"></span>
                </button>

                <button @click="resetFiltros()"
                    class="inline-flex items-center gap-2 rounded-lg bg-red-500 text-white px-4 py-2 text-sm font-medium hover:bg-red-600 transition">
                    <i class="fas fa-undo"></i>
                    Resetear
                </button>

                <button @click="cargarDatos()"
                    class="inline-flex items-center gap-2 rounded-lg bg-green-500 text-white px-4 py-2 text-sm font-medium hover:bg-green-600 transition">
                    <i class="fas fa-sync-alt"></i>
                    Actualizar
                </button>
            </div>
        </div>

        <!-- Contenido Principal -->
        <div class="content-section space-y-4 mt-4">
            <!-- Leyenda -->
            <div class="legend-container bg-white rounded-xl p-4 shadow-sm border border-gray-200 w-full">
                <div class="legend-horizontal flex flex-wrap items-center gap-6 justify-between">
                    <!-- Niveles -->
                    <div class="flex items-center gap-4">
                        <div class="font-medium text-gray-700 text-sm">Niveles:</div>
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 rounded bg-green-500 border"></div>
                                <span class="text-xs text-gray-600">Baja (0-24%)</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 rounded bg-amber-400 border"></div>
                                <span class="text-xs text-gray-600">Media (25-49%)</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 rounded border" style="background-color:#f97316"></div>
                                <span class="text-xs text-gray-600">Alta (50-74%)</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 rounded bg-red-500 border"></div>
                                <span class="text-xs text-gray-600">Muy alta (75-100%)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Separador -->
                    <div class="h-6 w-px bg-gray-300"></div>

                    <!-- Pisos -->
                    <div class="flex items-center gap-4">
                        <div class="font-medium text-gray-700 text-sm">Pisos:</div>
                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                            <template x-for="i in 10" :key="i">
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded border" :style="'background-color:' + getFillColorByFloor(i)"></div>
                                    <span class="text-xs text-gray-600" x-text="'P' + i"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Tip -->
                    <div class="flex items-center gap-2 px-4 py-3 bg-blue-50 border border-blue-200 rounded-lg shadow-sm">
                        <div class="flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full">
                            <span class="text-blue-600 text-sm">💡</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-1">
                            <span class="text-blue-800 font-semibold text-sm">Tip:</span>
                            <span class="text-blue-700 text-sm">Haz click en cualquier rack para ver los detalles</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Título -->
            <div class="text-center">
                <h3 class="text-xl font-bold text-gray-800">Mapa de Calor del Almacén</h3>
                <p class="text-gray-600" x-text="`Mostrando ${mode === 'heat' ? 'actividad' : 'ocupación'} - ${periodoLabel()}`"></p>
            </div>

            <!-- Heatmap -->
            <div class="heatmap-full-section overflow-auto relative">
                <div id="heatmap" style="width:100%; height:100%; min-height:700px;"></div>

                <!-- Preloader -->
                <div x-show="loading" x-transition.opacity
                    class="absolute inset-0 bg-gradient-to-br from-slate-50 to-blue-50 flex flex-col items-center justify-center z-50 backdrop-blur-sm">
                    <div class="relative mb-6">
                        <div class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg animate-pulse">
                            <i class="fa-solid fa-warehouse text-white text-xl"></i>
                        </div>
                        <div class="absolute -inset-2 bg-indigo-200 rounded-2xl blur-lg opacity-30 animate-ping"></div>
                    </div>
                    <div class="relative mb-4">
                        <div class="w-12 h-12 border-4 border-indigo-200 rounded-full"></div>
                        <div class="w-12 h-12 border-4 border-transparent border-t-indigo-600 rounded-full absolute top-0 left-0 animate-spin"></div>
                    </div>
                    <div class="text-center">
                        <h3 class="text-xl font-bold text-slate-800 mb-2">Cargando almacén</h3>
                        <p class="text-slate-600 text-sm">Preparando visualización en tiempo real...</p>
                    </div>
                    <div class="w-48 h-1 bg-slate-200 rounded-full mt-4 overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full animate-[progress_2s_ease-in-out_infinite]"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('almacenHeatmap', () => ({
                chart: null,
                labels: true,
                mode: 'heat',
                loading: false,
                filtro: {
                    periodo: '30',
                    sede: '',
                    buscar: ''
                },
                dataOriginal: [],
                data: [],
                stats: {
                    totalRacks: 0,
                    activeRacks: 0,
                    avgActivity: 0,
                    totalUbicaciones: 0,
                    ocupadas: 0
                },
                debounceTimer: null,

                init() {
                    this.cargarDatos();
                },

                async cargarDatos() {
                    this.loading = true;
                    
                    try {
                        const params = new URLSearchParams({
                            periodo: this.filtro.periodo,
                            sede: this.filtro.sede,
                            buscar: this.filtro.buscar
                        });

                        const response = await fetch(`/api/almacen/racks/datos?${params}`);
                        const result = await response.json();

                        if (result.success) {
                            this.dataOriginal = result.data;
                            this.data = result.data;
                            this.stats = result.stats;
                            this.renderChart();
                        } else {
                            console.error('Error al cargar datos:', result);
                            alert('Error al cargar los datos del almacén');
                        }
                    } catch (error) {
                        console.error('Error en cargarDatos:', error);
                        alert('Error de conexión al servidor');
                    }
                },

                aplicarFiltros() {
                    this.cargarDatos();
                },

                debounceFilter() {
                    clearTimeout(this.debounceTimer);
                    this.debounceTimer = setTimeout(() => this.aplicarFiltros(), 500);
                },

                resetFiltros() {
                    this.filtro = {
                        periodo: '30',
                        sede: '',
                        buscar: ''
                    };
                    this.cargarDatos();
                },

                periodoLabel() {
                    return `Últimos ${this.filtro.periodo} días`;
                },

                toggleLabels() {
                    this.labels = !this.labels;
                    this.updateChart();
                },

                getFillColorByFloor(piso) {
                    const colors = {
                        1: '#fee2e2', 2: '#dbeafe', 3: '#dcfce7', 4: '#ede9fe', 5: '#fef9c3',
                        6: '#cffafe', 7: '#fbcfe8', 8: '#e0f2fe', 9: '#d9f99d', 10: '#fcd34d'
                    };
                    return colors[piso] || '#f3f4f6';
                },

                getIconByValue(val) {
                    if (val < 25) return "🟢";
                    if (val < 50) return "🟡";
                    if (val < 75) return "🟠";
                    return "🔴";
                },

                renderChart() {
                    const heatmapEl = document.getElementById('heatmap');
                    const cols = Math.max(...this.data.map(d => d.x)) + 1;
                    const rows = Math.max(...this.data.map(d => d.y)) + 1;

                    heatmapEl.style.width = (cols * 100) + 'px';
                    heatmapEl.style.height = (rows * 50) + 'px';

                    if (this.chart) {
                        this.chart.dispose();
                    }

                    this.chart = echarts.init(heatmapEl);
                    this.updateChart();

                    window.addEventListener('resize', () => this.chart && this.chart.resize());
                },

                baseOption() {
                    const valorCampo = this.mode === 'heat' ? 'value' : 'ocupacion';
                    const data = this.data.map(d => [
                        d.x, d.y, d[valorCampo], d.ubicacion, d.piso,
                        d.rack, d.producto, d.cantidad, d.categoria, d.capacidad
                    ]);

                    return {
                        tooltip: {
                            trigger: 'item',
                            backgroundColor: 'rgba(0,0,0,0.85)',
                            borderColor: 'rgba(255,255,255,0.2)',
                            textStyle: { color: '#fff', fontSize: 14 },
                            formatter: (p) => {
                                  const [x, y, val, ubicacion, piso, rack, producto, cantidad, categoria, capacidad, letraRack, actividadBruta, maxActividad] = p.data;
                                return `
                                    <div style="padding:12px; min-width: 280px;">
                                        <div style="font-size:18px;font-weight:bold;margin-bottom:10px;color:#60a5fa;">🏢 Rack ${rack}</div>
                                        <div style="margin-bottom:6px;">📍 Ubicación: <strong>${ubicacion}</strong></div>
                                        <div style="margin-bottom:6px;">📦 Producto: <strong>${producto}</strong></div>
                                        <div style="margin-bottom:6px;">📊 Cantidad: <strong>${cantidad} / ${capacidad}</strong></div>
                                        <div style="margin-bottom:6px;">🏷️ Categoría: <strong>${categoria}</strong></div>
                                        <div style="margin-bottom:6px;">🏗️ Piso: <strong>${piso}</strong></div>
                                        <div style="margin-bottom:6px;">📈 Actividad: <strong>${val}%</strong></div>
                                        <!-- DEBUG INFO -->
                                        <div style="background:#f3f4f6; padding:8px; border-radius:4px; margin-top:8px;">
                                            <div style="font-size:11px; color:#6b7280;">
                                                <strong>Debug:</strong> Movimientos: ${actividadBruta || 0}, Máx: ${maxActividad || 1}
                                            </div>
                                        </div>
                                        <div style="font-size:13px;color:#94a3b8;margin-top:10px;">${this.periodoLabel()}</div>
                                        <div style="font-size:13px;color:#fbbf24;margin-top:6px;">💡 Click para ver detalles</div>
                                    </div>
                                `;
                            }
                        },
                        grid: {
                            left: 50,
                            right: 20,
                            top: 40,
                            bottom: 40,
                        },
                        xAxis: {
                            type: 'category',
                            splitArea: { show: false },
                            axisLabel: { show: false },
                            axisLine: { show: false },
                            axisTick: { show: false }
                        },
                        yAxis: {
                        type: 'category',
                        axisLabel: {
                            show: true,
                            color: '#64748b',
                            fontSize: 14,
                            margin: 15,
                            formatter: (value) => {
                                // Encontrar la letra del rack para esta fila
                                const rackData = this.data.find(d => d.y == value);
                                return rackData ? rackData.letra : value;
                            }
                        },
                        axisLine: {
                            lineStyle: { color: '#e2e8f0', width: 1 }
                        },
                        axisTick: {
                            show: true,
                            lineStyle: { color: '#e2e8f0' }
                        },
                        inverse: true,
                        data: [...new Set(this.data.map(d => d.y))],
                    },
                        visualMap: [],
                        series: [{
                            type: 'heatmap',
                            data,
                            progressive: 2000,
                            label: {
                                show: this.labels,
                                formatter: (p) => {
                                    const ubicacion = p.data[3]; // ubicación completa
                                    // const letraRack = p.data[11]; // nueva propiedad 'letra'
                                    return `${this.getIconByValue(p.data[2])} ${p.data[2]}%\n${ubicacion}`;
                                },
                                color: '#1e293b',
                                fontSize: 10, // Reducido para caber más información
                                fontWeight: "bold"
                            },
                            itemStyle: {
                                color: p => this.getFillColorByFloor(p.data[4]),
                                borderColor: '#fff',
                                borderWidth: 2,
                                borderType: 'solid'
                            },
                            emphasis: {
                                itemStyle: {
                                    borderColor: '#000',
                                    borderWidth: 4,
                                    shadowBlur: 12,
                                    shadowColor: 'rgba(0, 0, 0, 0.3)'
                                },
                                label: {
                                    show: true,
                                    fontSize: 13,
                                    fontWeight: 'bold'
                                }
                            }
                        }]
                    };
                },

                updateChart() {
                    if (!this.chart) return;

                    this.loading = true;
                    this.chart.setOption(this.baseOption(), true);

                    this.chart.off('finished');
                    this.chart.on('finished', () => {
                        this.loading = false;
                    });

                    this.chart.off('click');
                    this.chart.on('click', p => {
                        const rack = p.data[5];
                        window.location.href = `/almacen/ubicaciones/detalle/${rack}`;
                    });

                    setTimeout(() => {
                        if (this.loading) {
                            this.loading = false;
                        }
                    }, 3000);
                }
            }));
        });
    </script>
</x-layout.default>