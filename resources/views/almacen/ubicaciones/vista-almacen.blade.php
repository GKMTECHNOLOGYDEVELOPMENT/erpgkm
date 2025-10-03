<x-layout.default>
    <script src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* HEATMAP EXPANDIDO */
        .heatmap-full-section {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            overflow: auto;
            /* ✅ Ahora sí funciona el scroll */
            max-height: 80vh;
            /* 👈 límite vertical */
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

        /* Mejoras para el heatmap de ECharts */
        .echarts-tooltip {
            font-size: 14px !important;
        }

        /* Espacio para el título del heatmap en la sección de leyenda */
        .heatmap-title-placeholder {
            height: 60px;
            display: flex;
            align-items: center;
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
                        Analistas de actividad y ocupación en tiempo real
                    </p>
                </div>
            </div>
        </div>


        <!-- Filtros -->
        <div class="panel rounded-xl shadow-sm border border-slate-200 p-6 space-y-6">
            <!-- Row filtros -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                <!-- Periodo -->
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">Últimos 30 días</label>
                    <select x-model="filtro.periodo" @change="aplicarFiltros()"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                        <option value="7">Últimos 7 días</option>
                        <option value="30" selected>Últimos 30 días</option>
                        <option value="90">Últimos 90 días</option>
                    </select>
                </div>

                <!-- 🔥 Nueva sede -->
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">Sede</label>
                    <select x-model="filtro.sede" @change="aplicarFiltros()"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                        <option value="PUENTE PIEDRA" selected>PUENTE PIEDRA</option>
                        <option value="LOS OLIVOS">LOS OLIVOS</option>
                    </select>
                </div>

                <!-- Buscador -->
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">Rack</label>
                    <input x-model="filtro.buscar" @input="debounceFilter()" type="text" placeholder="Buscar..."
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm placeholder-slate-400 focus:border-indigo-500 focus:ring focus:ring-indigo-200">
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
            </div>
        </div>



        <!-- NUEVO LAYOUT: Leyenda arriba -->
        <div class="content-section space-y-4 mt-4">
            <!-- Sección superior con leyenda y título -->
            <div class="flex flex-col gap-4">
                <!-- Leyenda Horizontal -->
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

                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded border" style="background-color:#fee2e2"></div>
                                    <span class="text-xs text-gray-600">P1</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded border" style="background-color:#dbeafe"></div>
                                    <span class="text-xs text-gray-600">P2</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded border" style="background-color:#dcfce7"></div>
                                    <span class="text-xs text-gray-600">P3</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded border" style="background-color:#ede9fe"></div>
                                    <span class="text-xs text-gray-600">P4</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded border" style="background-color:#fef9c3"></div>
                                    <span class="text-xs text-gray-600">P5</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded border" style="background-color:#cffafe"></div>
                                    <span class="text-xs text-gray-600">P6</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded border" style="background-color:#fbcfe8"></div>
                                    <span class="text-xs text-gray-600">P7</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded border" style="background-color:#e0f2fe"></div>
                                    <span class="text-xs text-gray-600">P8</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded border" style="background-color:#d9f99d"></div>
                                    <span class="text-xs text-gray-600">P9</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded border" style="background-color:#fcd34d"></div>
                                    <span class="text-xs text-gray-600">P10</span>
                                </div>

                            </div>
                        </div>


                        <!-- Separador -->
                        <div class="h-6 w-px bg-gray-300"></div>

                        <!-- Tip -->
                        <div
                            class="flex items-center gap-2 px-4 py-3 bg-blue-50 border border-blue-200 rounded-lg shadow-sm">
                            <div class="flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full">
                                <span class="text-blue-600 text-sm">💡</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-1">
                                <span class="text-blue-800 font-semibold text-sm">Tip:</span>
                                <span class="text-blue-700 text-sm">
                                    <span class="hidden sm:inline">Haz click en cualquier rack para ver los
                                        detalles</span>
                                    <span class="sm:hidden">Toca cualquier rack para ver detalles</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Título debajo de la leyenda -->
                <div class="text-center">
                    <h3 class="text-xl font-bold text-gray-800">Mapa de Calor del Almacén</h3>
                    <p class="text-gray-600"
                        x-text="`Mostrando ${mode === 'heat' ? 'actividad' : 'ocupación'} - ${periodoLabel()}`"></p>
                </div>
            </div>

            <!-- Heatmap -->
            <div class="heatmap-full-section overflow-auto relative">
                <div id="heatmap" style="width:100%; height:100%; min-height:700px;"></div>

                <!-- Preloader Moderno Elegante -->
                <div x-show="loading" x-transition.opacity
                    class="absolute inset-0 bg-gradient-to-br from-slate-50 to-blue-50 flex flex-col items-center justify-center z-50 backdrop-blur-sm">

                    <!-- Logo/Icono animado -->
                    <div class="relative mb-6">
                        <div
                            class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg animate-pulse">
                            <i class="fa-solid fa-warehouse text-white text-xl"></i>
                        </div>
                        <div class="absolute -inset-2 bg-indigo-200 rounded-2xl blur-lg opacity-30 animate-ping"></div>
                    </div>

                    <!-- Spinner circular moderno -->
                    <div class="relative mb-4">
                        <div class="w-12 h-12 border-4 border-indigo-200 rounded-full"></div>
                        <div
                            class="w-12 h-12 border-4 border-transparent border-t-indigo-600 rounded-full absolute top-0 left-0 animate-spin">
                        </div>
                    </div>

                    <!-- Texto -->
                    <div class="text-center">
                        <h3 class="text-xl font-bold text-slate-800 mb-2">Cargando almacén</h3>
                        <p class="text-slate-600 text-sm">Preparando visualización en tiempo real...</p>
                    </div>

                    <!-- Barra de progreso sutil -->
                    <div class="w-48 h-1 bg-slate-200 rounded-full mt-4 overflow-hidden">
                        <div
                            class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full animate-[progress_2s_ease-in-out_infinite]">
                        </div>
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
                    metric: 'movs',
                    buscar: '',
                    categoria: ''
                },
                dataOriginal: [],
                data: [],
                stats: {
                    totalRacks: 0,
                    activeRacks: 0,
                    avgActivity: 0
                },
                debounceTimer: null,
                filas: 12,
                cols: 24,

                init() {
                    this.loading = true;

                    fetch("{{ asset('racks.json') }}")
                        .then(res => res.json())
                        .then(json => {
                            setTimeout(() => {
                                this.dataOriginal = json;
                                this.data = json;
                                this.calcStats();
                                this.renderChart();
                                // El loading se oculta automáticamente en renderChart() con chart.on('finished')
                            }, 5000);
                        });
                },

                aplicarFiltros() {
                    this.loading = true; // 👈 Mostrar preloader inmediatamente

                    // Filtrar solo por nombre de rack
                    if (this.filtro.buscar.trim()) {
                        const busqueda = this.filtro.buscar.toLowerCase().trim();
                        this.data = this.dataOriginal.filter(item => {
                            return item.rack.toLowerCase().includes(busqueda);
                        });
                    } else {
                        this.data = [...this.dataOriginal];
                    }

                    this.calcStats();
                    this.updateChart();
                    // ❌ Quitamos el setTimeout que cerraba el loading prematuramente
                    // El loading se ocultará automáticamente en updateChart() con chart.on('finished')
                },

                debounceFilter() {
                    clearTimeout(this.debounceTimer);
                    this.debounceTimer = setTimeout(() => this.aplicarFiltros(), 300);
                },

                resetFiltros() {
                    this.filtro = {
                        periodo: '30',
                        metric: 'movs',
                        buscar: '',
                        categoria: ''
                    };

                    this.loading = true;
                    this.data = [...this.dataOriginal];
                    this.calcStats();
                    this.updateChart();
                    // ❌ Quitamos el setTimeout que cerraba el loading prematuramente
                },

                // El resto de tus métodos se mantienen igual...
                genData() {
                    this.data = [];
                    const categorias = ["Electrónica", "Accesorios", "Repuestos", "Herramientas"];
                    const productosEjemplo = {
                        "Electrónica": ["Laptop Dell", "Monitor 24''", "Tablet Samsung",
                            "Router WiFi", "Servidor Rack"
                        ],
                        "Accesorios": ["Mouse Logitech", "Teclado Mecánico", "Auriculares",
                            "Cable HDMI", "Webcam HD"
                        ],
                        "Repuestos": ["Cartucho Tinta", "Fuente Poder", "Disco Duro SSD",
                            "Impresora HP", "Switch 24p"
                        ],
                        "Herramientas": ["Taladro", "Multímetro", "Cautín", "Destornillador",
                            "Llave Inglesa"
                        ]
                    };

                    const sede = "PP";

                    const posicionesMin = 3;
                    const posicionesMax = 8;

                    for (let r = 0; r < 26; r++) {
                        const rack = String.fromCharCode(65 + r);
                        const pisos = Math.floor(Math.random() * 6) + 1;
                        const y = r;
                        let x = 0;

                        for (let piso = 1; piso <= pisos; piso++) {
                            const posicionesPorPiso = Math.floor(Math.random() * (posicionesMax -
                                posicionesMin + 1)) + posicionesMin;

                            for (let pos = 1; pos <= posicionesPorPiso; pos++) {
                                const categoria = categorias[Math.floor(Math.random() * categorias
                                    .length)];
                                const productoLista = productosEjemplo[categoria];
                                const producto = productoLista[Math.floor(Math.random() * productoLista
                                    .length)];

                                const value = Math.floor(Math.random() * 100);
                                const cantidad = Math.floor(Math.random() * 50);

                                const ubicacion =
                                    `${rack}${piso}-${pos.toString().padStart(2, "0")}-${sede}`;

                                this.data.push({
                                    x,
                                    y,
                                    value,
                                    rack: `${rack}${piso}`,
                                    ubicacion,
                                    producto,
                                    cantidad,
                                    categoria,
                                    piso,
                                    rackLetter: rack
                                });

                                x++;
                            }
                        }
                    }
                },

                calcStats() {
                    const racksUnicos = [...new Set(this.data.map(d => d.rack.charAt(0)))];
                    this.stats.totalRacks = racksUnicos.length;

                    const racksActivos = new Set(
                        this.data.filter(d => d.value > 20).map(d => d.rack.charAt(0))
                    );
                    this.stats.activeRacks = racksActivos.size;

                    let sumPorRack = {};
                    let countPorRack = {};
                    this.data.forEach(d => {
                        const letra = d.rack.charAt(0);
                        sumPorRack[letra] = (sumPorRack[letra] || 0) + d.value;
                        countPorRack[letra] = (countPorRack[letra] || 0) + 1;
                    });

                    const promedios = Object.keys(sumPorRack).map(
                        r => sumPorRack[r] / countPorRack[r]
                    );

                    this.stats.avgActivity = Math.round(
                        promedios.reduce((s, p) => s + p, 0) / promedios.length || 0
                    );
                },

                periodoLabel() {
                    return `Últimos ${this.filtro.periodo} días`;
                },

                toggleLabels() {
                    this.labels = !this.labels;
                    this.updateChart();
                },

                toggleMode() {
                    this.mode = this.mode === 'heat' ? 'fill' : 'heat';
                    this.updateChart();
                },

                getFillColorByFloor(piso) {
                    const colors = {
                        1: '#fee2e2',
                        2: '#dbeafe',
                        3: '#dcfce7',
                        4: '#ede9fe',
                        5: '#fef9c3',
                        6: '#cffafe',
                        7: '#fbcfe8',
                        8: '#e0f2fe',
                        9: '#d9f99d',
                        10: '#fcd34d',
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

                    const cols = [...new Set(this.data.map(d => d.x))].length;
                    const rows = [...new Set(this.data.map(d => d.y))].length;

                    const ancho = cols * 100;
                    const alto = rows * 50;

                    heatmapEl.style.width = ancho + 'px';
                    heatmapEl.style.height = alto + 'px';

                    this.loading = true; // 🔥 Forzar preloader

                    // Destruir chart anterior si existe
                    if (this.chart) {
                        this.chart.dispose();
                    }

                    this.chart = echarts.init(heatmapEl);
                    this.updateChart();

                    window.addEventListener('resize', () => this.chart && this.chart.resize());
                },

                baseOption() {
                    const data = this.data.map(d => [
                        d.x, d.y, d.value, d.ubicacion, d.piso,
                        d.rack, d.producto, d.cantidad, d.categoria
                    ]);

                    return {
                        tooltip: {
                            trigger: 'item',
                            backgroundColor: 'rgba(0,0,0,0.85)',
                            borderColor: 'rgba(255,255,255,0.2)',
                            textStyle: {
                                color: '#fff',
                                fontSize: 14
                            },
                            formatter: (p) => {
                                const [x, y, val, ubicacion, piso, rack, producto, cantidad,
                                    categoria
                                ] = p.data;
                                const borderColor = this.getFillColorByFloor(piso);

                                return `
                            <div style="padding:12px; min-width: 280px;">
                                <div style="font-size:18px;font-weight:bold;margin-bottom:10px;color:#60a5fa;">🏢 Rack ${rack}</div>
                                <div style="margin-bottom:6px;">📍 Ubicación: <strong>${ubicacion}</strong></div>
                                <div style="margin-bottom:6px;">📦 Producto: <strong>${producto || 'Vacío'}</strong></div>
                                <div style="margin-bottom:6px;">📊 Cantidad: <strong>${cantidad}</strong></div>
                                <div style="margin-bottom:6px;">🏷️ Categoría: <strong>${categoria || 'N/A'}</strong></div>
                                <div style="margin-bottom:6px;">🏗️ Piso: <strong style="color:${borderColor}">${piso}</strong></div>
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
                            splitArea: {
                                show: false
                            },
                            axisLabel: {
                                show: false
                            },
                            axisLine: {
                                show: false
                            },
                            axisTick: {
                                show: false
                            }
                        },
                        yAxis: {
                            type: 'category',
                            axisLabel: {
                                show: true,
                                color: '#64748b',
                                fontSize: 14,
                                margin: 15
                            },
                            axisLine: {
                                lineStyle: {
                                    color: '#e2e8f0',
                                    width: 1
                                }
                            },
                            axisTick: {
                                show: true,
                                lineStyle: {
                                    color: '#e2e8f0'
                                }
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
                                formatter: (p) =>
                                    `${p.data[5]}\n${this.getIconByValue(p.data[2])} ${p.data[2]}%`,
                                color: '#1e293b',
                                fontSize: 12,
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

                    // ✅ Limpiar listeners anteriores y agregar nuevo
                    this.chart.off('finished');
                    this.chart.on('finished', () => {
                        this.loading = false;
                    });

                    this.chart.off('click');
                    this.chart.on('click', p => {
                        const rack = p.data[5];
                        window.location.href = "{{ url('/almacen/ubicaciones/detalle') }}/" +
                            rack;
                    });

                    // ✅ Backup: si por alguna razón no se dispara 'finished', cerrar después de 3 segundos
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
