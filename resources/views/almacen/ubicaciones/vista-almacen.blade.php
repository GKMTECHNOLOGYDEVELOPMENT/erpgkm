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
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(400%);
            }
        }
    </style>

    <div x-data="almacenHeatmap" x-init="init()" class="container">
        <!-- Header -->
        <div class="mb-6 rounded-xl border border-slate-200 bg-white px-5 py-4 shadow-sm">
            <div class="flex items-center justify-between">
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

                <!-- Botones de gestión -->
                <div class="flex gap-3">
                    <button @click="abrirModalCrearRack()"
                        class="inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-4 py-2 text-sm font-medium hover:bg-green-700 transition">
                        <i class="fas fa-plus"></i>
                        Crear Rack
                    </button>
                    <!-- <button @click="abrirModalCrearUbicacion()"
                        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-4 py-2 text-sm font-medium hover:bg-blue-700 transition">
                        <i class="fas fa-layer-group"></i>
                        Crear Ubicación
                    </button> -->
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
                        <p class="text-2xl font-bold text-slate-800"
                            x-text="stats.ocupadas + '/' + stats.totalUbicaciones">0/0</p>
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
                        @foreach ($sedes as $sede)
                        <option value="{{ $sede }}" {{ $sede == 'LOS OLIVOS' ? 'selected' : '' }}>
                            <!-- ← Esto asegura que esté seleccionado -->
                            {{ $sede }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Buscador -->
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">Buscar Rack</label>
                    <input x-model="filtro.buscar" @input="debounceFilter()" type="text"
                        placeholder="Ej: R01, R02..."
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

                <button @click="abrirModalSeleccionRack()"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-4 py-2 text-sm font-medium hover:bg-blue-700 transition">
                    <i class="fas fa-edit"></i>
                    Editar Dimensiones
                </button>

                <!-- <button @click="resetFiltros()"
                    class="inline-flex items-center gap-2 rounded-lg bg-red-500 text-white px-4 py-2 text-sm font-medium hover:bg-red-600 transition">
                    <i class="fas fa-undo"></i>
                    Resetear
                </button> -->

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
                                    <div class="w-4 h-4 rounded border"
                                        :style="'background-color:' + getFillColorByFloor(i)"></div>
                                    <span class="text-xs text-gray-600" x-text="'P' + i"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Tip -->
                    <div
                        class="flex items-center gap-2 px-4 py-3 bg-blue-50 border border-blue-200 rounded-lg shadow-sm">
                        <div class="flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full">
                            <span class="text-blue-600 text-sm">💡</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-1">
                            <span class="text-blue-800 font-semibold text-sm">Tip:</span>
                            <span class="text-blue-700 text-sm">Haz click en cualquier rack para ver los
                                detalles</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Título -->
            <div class="text-center">
                <h3 class="text-xl font-bold text-gray-800">Mapa de Calor del Almacén</h3>
                <p class="text-gray-600"
                    x-text="`Mostrando ${mode === 'heat' ? 'actividad' : 'ocupación'} - ${periodoLabel()}`"></p>
            </div>

            <!-- Heatmap -->
            <div class="heatmap-full-section overflow-auto relative">
                <div id="heatmap" style="width:100%; height:100%; min-height:700px;"></div>

                <!-- Preloader -->
                <div x-show="loading" x-transition.opacity
                    class="absolute inset-0 bg-gradient-to-br from-slate-50 to-blue-50 flex flex-col items-center justify-center z-50 backdrop-blur-sm">
                    <div class="relative mb-6">
                        <div
                            class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg animate-pulse">
                            <i class="fa-solid fa-warehouse text-white text-xl"></i>
                        </div>
                        <div class="absolute -inset-2 bg-indigo-200 rounded-2xl blur-lg opacity-30 animate-ping"></div>
                    </div>
                    <div class="relative mb-4">
                        <div class="w-12 h-12 border-4 border-indigo-200 rounded-full"></div>
                        <div
                            class="w-12 h-12 border-4 border-transparent border-t-indigo-600 rounded-full absolute top-0 left-0 animate-spin">
                        </div>
                    </div>
                    <div class="text-center">
                        <h3 class="text-xl font-bold text-slate-800 mb-2">Cargando almacén</h3>
                        <p class="text-slate-600 text-sm">Preparando visualización en tiempo real...</p>
                    </div>
                    <div class="w-48 h-1 bg-slate-200 rounded-full mt-4 overflow-hidden">
                        <div
                            class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full animate-[progress_2s_ease-in-out_infinite]">
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal para Seleccionar Rack -->
        <div x-show="modalSeleccionRack.open" class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto"
            :class="modalSeleccionRack.open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click="cerrarModalSeleccionRack()">
                <div x-show="modalSeleccionRack.open" x-transition x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-2xl" @click.stop>
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <div class="font-bold text-lg">Seleccionar Rack para Editar</div>
                        <button type="button" class="text-white-dark hover:text-dark"
                            @click="cerrarModalSeleccionRack()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <div class="p-5">
                        <!-- Preloader -->
                        <div x-show="modalSeleccionRack.loading" class="flex justify-center py-8">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                        </div>

                        <!-- Lista de racks -->
                        <div x-show="!modalSeleccionRack.loading" class="space-y-3">
                            <template x-if="modalSeleccionRack.racks.length === 0">
                                <div class="text-center py-8 text-gray-500">
                                    <i class="fas fa-box-open text-4xl mb-4"></i>
                                    <p>No hay racks disponibles</p>
                                </div>
                            </template>

                            <template x-for="rack in modalSeleccionRack.racks" :key="rack.idRack">
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer"
                                    @click="seleccionarRackParaEdicion(rack)">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-warehouse text-indigo-600"></i>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-800" x-text="'Rack ' + rack.nombre">
                                            </div>
                                            <div class="text-sm text-gray-600" x-text="rack.sede"></div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm text-gray-500" x-text="rack.filas + 'x' + rack.columnas">
                                        </div>
                                        <div class="text-xs text-gray-400"
                                            x-text="(rack.filas * rack.columnas) + ' ubicaciones'"></div>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-400 ml-4"></i>
                                </div>
                            </template>
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-end items-center mt-6 gap-4">
                            <button type="button" @click="cerrarModalSeleccionRack()"
                                class="btn btn-outline-danger">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Crear Rack -->
        <div x-show="modalCrearRack.open" class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto"
            :class="modalCrearRack.open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click="cerrarModalCrearRack()">
                <div x-show="modalCrearRack.open" x-transition x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-lg" @click.stop>
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <div class="font-bold text-lg">Crear Nuevo Rack</div>
                        <button type="button" class="text-white-dark hover:text-dark"
                            @click="cerrarModalCrearRack()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <div class="p-5">
                        <form @submit.prevent="crearRack()" class="space-y-4">
                            <!-- Sede -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-slate-700 mb-2 dark:text-white-dark/70">Sede
                                    *</label>
                                <select x-model="modalCrearRack.form.sede" required @change="sugerirSiguienteLetra()"
                                    class="form-select w-full rounded-lg border border-slate-300 dark:border-[#17263c] dark:bg-[#121c2c] dark:text-white-dark px-3 py-2 text-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500/20">
                                    <option value="">Seleccione una sede</option>
                                    @foreach ($sedes as $sede)
                                    <option value="{{ $sede }}">{{ $sede }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <!-- NUEVO: Tipo de Rack -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2 dark:text-white-dark/70">Tipo de Rack *</label>
                                <select x-model="modalCrearRack.form.tipo_rack" required
                                    class="form-select w-full rounded-lg border border-slate-300 dark:border-[#17263c] dark:bg-[#121c2c] dark:text-white-dark px-3 py-2 text-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500/20">
                                    <option value="">Seleccione tipo de rack</option> <!-- ✅ NUEVA OPCIÓN -->
                                    <option value="panel">Panel</option>
                                    <option value="spark">Spark</option>
                                </select>
                            </div>

                            <!-- Nombre del Rack con Sugerencia Automática -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2 dark:text-white-dark/70">
                                    Nombre del Rack *
                                    <span x-show="modalCrearRack.sugerencia" class="text-green-600 text-xs ml-2">
                                        💡 Sugerencia: <span x-text="modalCrearRack.sugerencia"
                                            class="font-bold"></span>
                                    </span>
                                </label>
                                <div class="flex gap-2">
                                    <input type="text" x-model="modalCrearRack.form.nombre" required
                                        class="form-input flex-1 rounded-lg border border-slate-300 dark:border-[#17263c] dark:bg-[#121c2c] dark:text-white-dark px-3 py-2 text-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500/20"
                                        placeholder="Ej: A, B, C, A1..."
                                        :class="modalCrearRack.form.nombre && modalCrearRack.sugerencia && modalCrearRack.form
                                            .nombre !== modalCrearRack.sugerencia ? 'border-orange-500' : ''">
                                    <button type="button" @click="usarSugerencia()"
                                        x-show="modalCrearRack.sugerencia"
                                        class="btn btn-outline-primary whitespace-nowrap text-sm px-3 py-2">
                                        Usar Sugerencia
                                    </button>
                                </div>

                                <!-- Información de letras usadas -->
                                <div x-show="modalCrearRack.letrasUsadas && modalCrearRack.letrasUsadas.length > 0"
                                    class="text-xs text-slate-500 mt-2 p-2 bg-slate-50 rounded">
                                    <div class="font-medium mb-1">Letras usadas en <span
                                            x-text="modalCrearRack.form.sede" class="font-bold"></span>:</div>
                                    <div class="flex flex-wrap gap-1">
                                        <template x-for="letra in modalCrearRack.letrasUsadas" :key="letra">
                                            <span class="px-2 py-1 bg-slate-200 rounded" x-text="letra"></span>
                                        </template>
                                    </div>
                                </div>

                                <!-- Información de disponibilidad -->
                                <div x-show="modalCrearRack.letrasUsadas && modalCrearRack.letrasUsadas.length > 0"
                                    class="text-xs text-slate-500 mt-1">
                                    <span x-text="26 - modalCrearRack.letrasUsadas.length"></span> letras disponibles
                                    de 26
                                </div>
                            </div>

                            <!-- Configuración de dimensiones -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-slate-700 mb-2 dark:text-white-dark/70">Filas
                                        *</label>
                                    <input type="number" x-model="modalCrearRack.form.filas" required min="1"
                                        max="12"
                                        class="form-input w-full rounded-lg border border-slate-300 dark:border-[#17263c] dark:bg-[#121c2c] dark:text-white-dark px-3 py-2 text-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500/20"
                                        placeholder="Número de filas">
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-slate-700 mb-2 dark:text-white-dark/70">Columnas
                                        *</label>
                                    <input type="number" x-model="modalCrearRack.form.columnas" required
                                        min="1" max="24"
                                        class="form-input w-full rounded-lg border border-slate-300 dark:border-[#17263c] dark:bg-[#121c2c] dark:text-white-dark px-3 py-2 text-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500/20"
                                        placeholder="Número de columnas">
                                </div>
                            </div>

                            <!-- Capacidad Máxima -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2 dark:text-white-dark/70">
                                    Capacidad Máxima por Ubicación *
                                </label>
                                <input type="number" x-model="modalCrearRack.form.capacidad_maxima" required
                                    min="1" max="10000"
                                    class="form-input w-full rounded-lg border border-slate-300 dark:border-[#17263c] dark:bg-[#121c2c] dark:text-white-dark px-3 py-2 text-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500/20"
                                    placeholder="Ej: 100, 200, 500...">
                                <p class="text-xs text-slate-500 mt-1">
                                    Esta capacidad se aplicará a todas las ubicaciones del rack
                                </p>
                            </div>

                            <!-- Estado -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-slate-700 mb-2 dark:text-white-dark/70">Estado
                                    *</label>
                                <select x-model="modalCrearRack.form.estado" required
                                    class="form-select w-full rounded-lg border border-slate-300 dark:border-[#17263c] dark:bg-[#121c2c] dark:text-white-dark px-3 py-2 text-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500/20">
                                    <option value="activo">Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                </select>
                            </div>


                            <!-- Botones -->
                            <div class="flex justify-end items-center mt-8 gap-4">
                                <button type="button" @click="cerrarModalCrearRack()"
                                    class="btn btn-outline-danger">
                                    Cancelar
                                </button>
                                <button type="submit" :disabled="modalCrearRack.loading"
                                    :class="modalCrearRack.loading ? 'bg-indigo-400 cursor-not-allowed' :
                                        'bg-indigo-600 hover:bg-indigo-700'"
                                    class="btn btn-primary text-white py-2 px-4 rounded-lg font-medium transition flex items-center justify-center gap-2">
                                    <i class="fas fa-spinner fa-spin" x-show="modalCrearRack.loading"></i>
                                    <span x-text="modalCrearRack.loading ? 'Creando...' : 'Crear Rack'"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Crear Ubicación -->
        <div x-show="modalCrearUbicacion.open" class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto"
            :class="modalCrearUbicacion.open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click="cerrarModalCrearUbicacion()">
                <div x-show="modalCrearUbicacion.open" x-transition x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-lg" @click.stop>
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <div class="font-bold text-lg">Crear Ubicación en Rack</div>
                        <button type="button" class="text-white-dark hover:text-dark"
                            @click="cerrarModalCrearUbicacion()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <div class="p-5">
                        <form @submit.prevent="crearUbicacion()" class="space-y-4">
                            <!-- Selección de Rack -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-slate-700 mb-2 dark:text-white-dark/70">Rack
                                    *</label>
                                <select x-model="modalCrearUbicacion.form.rack_id" required @change="cargarRackInfo()"
                                    class="form-select w-full rounded-lg border border-slate-300 dark:border-[#17263c] dark:bg-[#121c2c] dark:text-white-dark px-3 py-2 text-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500/20">
                                    <option value="">Seleccione un rack</option>
                                    <template x-for="rack in modalCrearUbicacion.racks" :key="rack.idRack">
                                        <option :value="rack.idRack"
                                            x-text="'Rack ' + rack.nombre + ' - ' + rack.sede">
                                        </option>
                                    </template>
                                </select>
                            </div>

                            <!-- Información del rack seleccionado - CORREGIDO -->
                            <div x-show="modalCrearUbicacion.rackInfo"
                                class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg">
                                <div class="text-sm text-blue-800 dark:text-blue-300">
                                    <span
                                        x-text="'Rack: ' + (modalCrearUbicacion.rackInfo ? modalCrearUbicacion.rackInfo.nombre : '')"></span>
                                    <span x-show="modalCrearUbicacion.rackInfo"> | </span>
                                    <span
                                        x-text="'Dimensiones: ' + (modalCrearUbicacion.rackInfo ? modalCrearUbicacion.rackInfo.filas + 'x' + modalCrearUbicacion.rackInfo.columnas : '')"></span>
                                </div>
                            </div>

                            <!-- Nivel y Posición -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-slate-700 mb-2 dark:text-white-dark/70">Nivel
                                        *</label>
                                    <input type="number" x-model="modalCrearUbicacion.form.nivel" required
                                        min="1"
                                        class="form-input w-full rounded-lg border border-slate-300 dark:border-[#17263c] dark:bg-[#121c2c] dark:text-white-dark px-3 py-2 text-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500/20"
                                        placeholder="Nivel">
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-slate-700 mb-2 dark:text-white-dark/70">Posición
                                        *</label>
                                    <input type="number" x-model="modalCrearUbicacion.form.posicion" required
                                        min="1"
                                        class="form-input w-full rounded-lg border border-slate-300 dark:border-[#17263c] dark:bg-[#121c2c] dark:text-white-dark px-3 py-2 text-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500/20"
                                        placeholder="Posición">
                                </div>
                            </div>

                            <!-- Código y Capacidad -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-slate-700 mb-2 dark:text-white-dark/70">Código
                                        *</label>
                                    <input type="text" x-model="modalCrearUbicacion.form.codigo" required
                                        class="form-input w-full rounded-lg border border-slate-300 dark:border-[#17263c] dark:bg-[#121c2c] dark:text-white-dark px-3 py-2 text-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500/20"
                                        placeholder="Ej: A1, B2...">
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-slate-700 mb-2 dark:text-white-dark/70">Capacidad
                                        Máxima *</label>
                                    <input type="number" x-model="modalCrearUbicacion.form.capacidad_maxima" required
                                        min="1"
                                        class="form-input w-full rounded-lg border border-slate-300 dark:border-[#17263c] dark:bg-[#121c2c] dark:text-white-dark px-3 py-2 text-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500/20"
                                        placeholder="Ej: 100">
                                </div>
                            </div>

                            <!-- Estado Ocupación -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-slate-700 mb-2 dark:text-white-dark/70">Estado
                                    Inicial *</label>
                                <select x-model="modalCrearUbicacion.form.estado_ocupacion" required
                                    class="form-select w-full rounded-lg border border-slate-300 dark:border-[#17263c] dark:bg-[#121c2c] dark:text-white-dark px-3 py-2 text-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500/20">
                                    <option value="vacio">Vacío</option>
                                    <option value="bajo">Bajo</option>
                                    <option value="medio">Medio</option>
                                    <option value="alto">Alto</option>
                                    <option value="muy_alto">Muy Alto</option>
                                </select>
                            </div>

                            <!-- Botones -->
                            <div class="flex justify-end items-center mt-8 gap-4">
                                <button type="button" @click="cerrarModalCrearUbicacion()"
                                    class="btn btn-outline-danger">
                                    Cancelar
                                </button>
                                <button type="submit" :disabled="modalCrearUbicacion.loading"
                                    :class="modalCrearUbicacion.loading ? 'bg-indigo-400 cursor-not-allowed' :
                                        'bg-indigo-600 hover:bg-indigo-700'"
                                    class="btn btn-primary text-white py-2 px-4 rounded-lg font-medium transition flex items-center justify-center gap-2">
                                    <i class="fas fa-spinner fa-spin" x-show="modalCrearUbicacion.loading"></i>
                                    <span
                                        x-text="modalCrearUbicacion.loading ? 'Creando...' : 'Crear Ubicación'"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>



        <!-- Modal para Editar Dimensiones del Rack -->
        <div x-show="modalEditarDimensiones.open" class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto"
            :class="modalEditarDimensiones.open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click="cerrarModalEditarDimensiones()">
                <div x-show="modalEditarDimensiones.open" x-transition x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-lg" @click.stop>
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <div class="font-bold text-lg">Editar Dimensiones del Rack</div>
                        <button type="button" class="text-white-dark hover:text-dark"
                            @click="cerrarModalEditarDimensiones()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <div class="p-5">
                        <form @submit.prevent="actualizarDimensionesRack()" class="space-y-4">
                            <!-- Información del rack -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg"
                                x-show="modalEditarDimensiones.rack">
                                <div class="text-sm text-blue-800 dark:text-blue-300">
                                    <span x-text="'Rack: ' + (modalEditarDimensiones.rack ? modalEditarDimensiones.rack.nombre : '')"></span>
                                    <span x-show="modalEditarDimensiones.rack"> | </span>
                                    <span x-text="'Sede: ' + (modalEditarDimensiones.rack ? modalEditarDimensiones.rack.sede : '')"></span>
                                    <span x-show="modalEditarDimensiones.rack"> | </span>
                                    <span x-text="'Tipo: ' + (modalEditarDimensiones.rack ? modalEditarDimensiones.rack.tipo_rack.toUpperCase() : '')"></span>

                                    <!-- NUEVO: Mensaje si tiene productos -->
                                    <div x-show="modalEditarDimensiones.tieneProductos" class="mt-2 text-amber-700 dark:text-amber-300">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Este rack contiene productos. No se puede cambiar el tipo.
                                    </div>
                                </div>
                            </div>

                            <!-- NUEVO: Tipo de Rack -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2 dark:text-white-dark/70">
                                    Tipo de Rack
                                    <span x-show="!modalEditarDimensiones.puedeCambiarTipo" class="text-amber-600 text-xs ml-2">
                                        (No se puede cambiar - rack con productos)
                                    </span>
                                </label>
                                <select x-model="modalEditarDimensiones.form.tipo_rack"
                                    :disabled="!modalEditarDimensiones.puedeCambiarTipo"
                                    :class="!modalEditarDimensiones.puedeCambiarTipo ? 'bg-gray-100 cursor-not-allowed' : ''"
                                    class="form-select w-full rounded-lg border border-slate-300 dark:border-[#17263c] dark:bg-[#121c2c] dark:text-white-dark px-3 py-2 text-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500/20">
                                    <option value="">Seleccione tipo de rack</option> <!-- ✅ NUEVA OPCIÓN -->
                                    <option value="panel">Panel</option>
                                    <option value="spark">Spark</option>
                                </select>
                            </div>

                            <!-- Configuración de dimensiones -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2 dark:text-white-dark/70">Filas *</label>
                                    <input type="number" x-model="modalEditarDimensiones.form.filas" required min="1" max="12"
                                        class="form-input w-full rounded-lg border border-slate-300 dark:border-[#17263c] dark:bg-[#121c2c] dark:text-white-dark px-3 py-2 text-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500/20"
                                        placeholder="Número de filas">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2 dark:text-white-dark/70">Columnas *</label>
                                    <input type="number" x-model="modalEditarDimensiones.form.columnas" required min="1" max="24"
                                        class="form-input w-full rounded-lg border border-slate-300 dark:border-[#17263c] dark:bg-[#121c2c] dark:text-white-dark px-3 py-2 text-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500/20"
                                        placeholder="Número de columnas">
                                </div>
                            </div>

                            <!-- Capacidad Máxima -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2 dark:text-white-dark/70">
                                    Capacidad Máxima por Ubicación *
                                </label>
                                <input type="number" x-model="modalEditarDimensiones.form.capacidad_maxima" required min="1" max="10000"
                                    class="form-input w-full rounded-lg border border-slate-300 dark:border-[#17263c] dark:bg-[#121c2c] dark:text-white-dark px-3 py-2 text-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500/20"
                                    placeholder="Ej: 100, 200, 500...">
                                <p class="text-xs text-slate-500 mt-1">
                                    Esta capacidad se aplicará a las nuevas ubicaciones generadas
                                </p>
                            </div>

                            <!-- Resumen de cambios -->
                            <div x-show="modalEditarDimensiones.cambiosDetectados"
                                :class="modalEditarDimensiones.puedeActualizar ? 'bg-amber-50' : 'bg-red-50'"
                                class="p-3 rounded-lg">
                                <div :class="modalEditarDimensiones.puedeActualizar ? 'text-amber-800' : 'text-red-800'" class="text-sm">
                                    <div class="font-medium mb-1">Resumen de cambios:</div>
                                    <div x-text="modalEditarDimensiones.resumenCambios"></div>
                                    <div x-show="!modalEditarDimensiones.puedeActualizar" class="mt-2 text-xs">
                                        ⚠️ Para disminuir dimensiones, primero vacíe las ubicaciones que serían eliminadas.
                                    </div>
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="flex justify-end items-center mt-8 gap-4">
                                <button type="button" @click="cerrarModalEditarDimensiones()"
                                    class="btn btn-outline-danger">
                                    Cancelar
                                </button>
                                <button type="submit"
                                    :disabled="modalEditarDimensiones.loading || !modalEditarDimensiones.puedeActualizar"
                                    :class="modalEditarDimensiones.loading ? 'bg-indigo-400 cursor-not-allowed' :
                                        (!modalEditarDimensiones.puedeActualizar ? 'bg-gray-400 cursor-not-allowed' : 
                                        'bg-indigo-600 hover:bg-indigo-700')"
                                    class="btn btn-primary text-white py-2 px-4 rounded-lg font-medium transition flex items-center justify-center gap-2">
                                    <i class="fas fa-spinner fa-spin" x-show="modalEditarDimensiones.loading"></i>
                                    <span x-text="modalEditarDimensiones.loading ? 'Actualizando...' : 
                                        (!modalEditarDimensiones.puedeActualizar ? 'No se puede actualizar' : 'Actualizar Dimensiones')"></span>
                                </button>
                            </div>
                        </form>
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
                    sede: '',
                    buscar: ''
                },
                modalCrearRack: {
                    open: false,
                    loading: false,
                    sugerencia: null,
                    letrasUsadas: [],
                    form: {
                        nombre: '',
                        sede: '',
                        tipo_rack: '', // ✅ CAMBIADO: string vacío en lugar de 'panel'
                        filas: 1,
                        columnas: 1,
                        capacidad_maxima: 10000,
                        estado: 'activo'
                    }
                },
                // Agrega este nuevo estado al inicio con los otros modales
                modalSeleccionRack: {
                    open: false,
                    racks: [],
                    rackSeleccionado: null,
                    loading: false
                },

                modalEditarDimensiones: {
                    open: false,
                    loading: false,
                    rack: null,
                    tieneProductos: false,
                    puedeCambiarTipo: true,
                    cambiosDetectados: false,
                    resumenCambios: '',
                    form: {
                        filas: 1,
                        columnas: 1,
                        tipo_rack: '', // ✅ CAMBIADO: string vacío en lugar de 'panel'
                        capacidad_maxima: 10000
                    }
                },


                modalCrearUbicacion: {
                    open: false,
                    loading: false,
                    racks: [],
                    rackInfo: null,
                    form: {
                        rack_id: '',
                        codigo: '',
                        nivel: 1,
                        posicion: 1,
                        capacidad_maxima: 10000,
                        estado_ocupacion: 'vacio'
                    }
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

                // MÉTODOS PARA CREAR RACK
                async abrirModalCrearRack() {
                    this.modalCrearRack.open = true;
                    // Resetear formulario
                    this.modalCrearRack.form = {
                        nombre: '',
                        sede: '',
                        tipo_rack: '', // ✅ CAMBIADO: string vacío
                        filas: 1,
                        columnas: 1,
                        capacidad_maxima: 10000,
                        estado: 'activo'
                    };
                    this.modalCrearRack.sugerencia = null;
                    this.modalCrearRack.letrasUsadas = [];
                },
                // Método para abrir modal de selección de rack
                async abrirModalSeleccionRack() {
                    try {
                        this.modalSeleccionRack.loading = true;

                        // Cargar lista de racks disponibles
                        const response = await fetch('/almacen/racks/listar');
                        const result = await response.json();

                        if (result.success) {
                            this.modalSeleccionRack.racks = result.data;
                            this.modalSeleccionRack.open = true;
                            this.modalSeleccionRack.rackSeleccionado = null;
                        } else {
                            this.error('Error al cargar lista de racks');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.error('Error de conexión al servidor');
                    } finally {
                        this.modalSeleccionRack.loading = false;
                    }
                },
                // Método para seleccionar un rack y abrir el modal de edición
                async seleccionarRackParaEdicion(rack) {
                    this.modalSeleccionRack.rackSeleccionado = rack;

                    // Cerrar modal de selección
                    this.modalSeleccionRack.open = false;

                    // Abrir modal de edición con el rack seleccionado
                    await this.abrirModalEditarDimensiones(rack);
                },
                cerrarModalSeleccionRack() {
                    this.modalSeleccionRack.open = false;
                    this.modalSeleccionRack.racks = [];
                    this.modalSeleccionRack.rackSeleccionado = null;
                },
                async abrirModalCrearRack() {
                    this.modalCrearRack.open = true;
                    // Resetear formulario
                    this.modalCrearRack.form = {
                        nombre: '',
                        sede: '',
                        filas: 1,
                        columnas: 1,
                        estado: 'activo'
                    };
                    this.modalCrearRack.sugerencia = null;
                    this.modalCrearRack.letrasUsadas = [];
                },

                // Método para abrir modal de edición
                async abrirModalEditarDimensiones(rack) {
                    try {
                        console.log('Abriendo modal para rack:', rack);

                        // Verificar que el rack existe
                        if (!rack || !rack.idRack) {
                            this.error('Rack no válido');
                            return;
                        }

                        const response = await fetch(`/almacen/racks/${rack.idRack}/info`);

                        if (!response.ok) {
                            throw new Error(`Error HTTP: ${response.status}`);
                        }

                        const result = await response.json();
                        console.log('Info del rack:', result);

                        if (result.success) {
                            this.modalEditarDimensiones.rack = result.data;
                            this.modalEditarDimensiones.form.filas = result.data.filas;
                            this.modalEditarDimensiones.form.columnas = result.data.columnas;

                            // ✅ Asegurar que tipo_rack siempre tenga un valor
                            this.modalEditarDimensiones.form.tipo_rack = result.data.tipo_rack || 'panel';

                            this.modalEditarDimensiones.form.capacidad_maxima = 10000;

                            // Inicializar estados
                            this.modalEditarDimensiones.tieneProductos = false;
                            this.modalEditarDimensiones.puedeCambiarTipo = true;

                            // Verificar si tiene productos para bloquear el cambio de tipo
                            await this.verificarProductosEnRack(rack.idRack);

                            this.modalEditarDimensiones.open = true;
                            this.calcularCambios();
                        } else {
                            console.error('Error al cargar info del rack:', result.message);
                            this.error('Error al cargar información del rack');
                        }
                    } catch (error) {
                        console.error('Error al abrir modal:', error);
                        this.error('Error de conexión al servidor');
                    }
                },

                // NUEVO: Método para verificar si el rack tiene productos
                async verificarProductosEnRack(rackId) {
                    try {
                        console.log('Verificando productos para rack ID:', rackId);

                        const response = await fetch(`/almacen/racks/${rackId}/tiene-productos`);

                        if (!response.ok) {
                            throw new Error(`Error HTTP: ${response.status}`);
                        }

                        const result = await response.json();
                        console.log('Respuesta verificación productos:', result);

                        if (result.success) {
                            this.modalEditarDimensiones.tieneProductos = result.data.tiene_productos;
                            this.modalEditarDimensiones.puedeCambiarTipo = !result.data.tiene_productos;

                            console.log('Estado actualizado:', {
                                tieneProductos: this.modalEditarDimensiones.tieneProductos,
                                puedeCambiarTipo: this.modalEditarDimensiones.puedeCambiarTipo
                            });
                        } else {
                            console.warn('Error en respuesta:', result.message);
                            // Por defecto, asumimos que no tiene productos para no bloquear la UI
                            this.modalEditarDimensiones.tieneProductos = false;
                            this.modalEditarDimensiones.puedeCambiarTipo = true;
                        }
                    } catch (error) {
                        console.error('Error al verificar productos:', error);
                        // Por defecto, asumimos que no tiene productos
                        this.modalEditarDimensiones.tieneProductos = false;
                        this.modalEditarDimensiones.puedeCambiarTipo = true;
                    }
                },

                async sugerirSiguienteLetra() {
                    if (!this.modalCrearRack.form.sede) {
                        this.modalCrearRack.sugerencia = null;
                        this.modalCrearRack.letrasUsadas = [];
                        return;
                    }

                    try {
                        const response = await fetch('/almacen/racks/sugerir-letra', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                sede: this.modalCrearRack.form.sede
                            })
                        });

                        const result = await response.json();

                        if (result.success) {
                            this.modalCrearRack.sugerencia = result.data.sugerencia;
                            this.modalCrearRack.letrasUsadas = result.data.letras_usadas;
                        } else {
                            console.error('Error al obtener sugerencia:', result.message);
                            this.modalCrearRack.sugerencia = null;
                            this.modalCrearRack.letrasUsadas = [];
                        }
                    } catch (error) {
                        console.error('Error al obtener sugerencia:', error);
                        this.modalCrearRack.sugerencia = null;
                        this.modalCrearRack.letrasUsadas = [];
                    }
                },

                usarSugerencia() {
                    if (this.modalCrearRack.sugerencia) {
                        this.modalCrearRack.form.nombre = this.modalCrearRack.sugerencia;

                        // Mostrar mensaje temporal de confirmación
                        this.mostrarMensajeTemporal('Sugerencia aplicada: ' + this.modalCrearRack
                            .sugerencia, 'success');
                    }
                },

                calcularCambios() {
                    const rack = this.modalEditarDimensiones.rack;

                    if (!rack) {
                        this.modalEditarDimensiones.cambiosDetectados = false;
                        this.modalEditarDimensiones.resumenCambios = 'No hay rack seleccionado';
                        return;
                    }

                    const nuevasFilas = this.modalEditarDimensiones.form.filas;
                    const nuevasColumnas = this.modalEditarDimensiones.form.columnas;

                    const ubicacionesActuales = rack.filas * rack.columnas;
                    const ubicacionesNuevas = nuevasFilas * nuevasColumnas;
                    const diferencia = ubicacionesNuevas - ubicacionesActuales;

                    // ✅ NUEVO: Calcular ubicaciones que se eliminarían
                    const ubicacionesAEliminar = Math.max(0, -diferencia);

                    this.modalEditarDimensiones.cambiosDetectados = diferencia !== 0;

                    const intentaDisminuirFilas = nuevasFilas < rack.filas;
                    const intentaDisminuirColumnas = nuevasColumnas < rack.columnas;
                    const puedeDisminuir = !intentaDisminuirFilas && !intentaDisminuirColumnas;

                    if (diferencia > 0) {
                        this.modalEditarDimensiones.resumenCambios =
                            `✅ Se generarán ${diferencia} nuevas ubicaciones. Total: ${ubicacionesNuevas} ubicaciones.`;
                        this.modalEditarDimensiones.puedeActualizar = true;
                    } else if (diferencia < 0) {
                        if (!puedeDisminuir) {
                            this.modalEditarDimensiones.resumenCambios =
                                `❌ Se eliminarían ${ubicacionesAEliminar} ubicaciones, pero hay productos en algunas de ellas.`;
                            this.modalEditarDimensiones.puedeActualizar = false;
                        } else {
                            this.modalEditarDimensiones.resumenCambios =
                                `⚠️ Se eliminarán ${ubicacionesAEliminar} ubicaciones. Total: ${ubicacionesNuevas} ubicaciones.`;
                            this.modalEditarDimensiones.puedeActualizar = true;
                        }
                    } else {
                        this.modalEditarDimensiones.resumenCambios =
                            'No hay cambios en el número de ubicaciones.';
                        this.modalEditarDimensiones.puedeActualizar = true;
                    }
                },
                // Método para actualizar dimensiones
                async actualizarDimensionesRack() {
                    this.modalEditarDimensiones.loading = true;

                    try {
                        console.log('Enviando datos de actualización:', {
                            form: this.modalEditarDimensiones.form,
                            rack: this.modalEditarDimensiones.rack
                        });

                        const response = await fetch(
                            `/almacen/racks/${this.modalEditarDimensiones.rack.idRack}/actualizar-dimensiones`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify(this.modalEditarDimensiones.form)
                            });

                        const result = await response.json();
                        console.log('Respuesta del servidor:', result);

                        if (result.success) {
                            this.success(result.message);
                            this.cerrarModalEditarDimensiones();
                            this.cargarDatos(); // Recargar datos para actualizar la vista
                        } else {
                            this.error(result.message || 'Error al actualizar dimensiones');
                            if (result.errors) {
                                Object.values(result.errors).forEach(errorArray => {
                                    errorArray.forEach(error => {
                                        this.error(error);
                                    });
                                });
                            }
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.error('Error de conexión al servidor');
                    } finally {
                        this.modalEditarDimensiones.loading = false;
                    }
                },
                cerrarModalEditarDimensiones() {
                    this.modalEditarDimensiones.open = false;
                    this.modalEditarDimensiones.loading = false;
                    this.modalEditarDimensiones.rack = null;
                    this.modalEditarDimensiones.cambiosDetectados = false;
                    this.modalEditarDimensiones.resumenCambios = '';
                },

                mostrarMensajeTemporal(mensaje, tipo = 'info') {
                    // Puedes implementar tu sistema de notificaciones aquí
                    console.log(`${tipo.toUpperCase()}: ${mensaje}`);
                    // O usar alert temporal
                    // alert(mensaje);
                },
                cerrarModalCrearRack() {
                    this.modalCrearRack.open = false;
                    this.modalCrearRack.loading = false;
                },

                async crearRack() {
                    this.modalCrearRack.loading = true;

                    try {
                        const response = await fetch('/almacen/racks/crear', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(this.modalCrearRack.form)
                        });

                        const result = await response.json();

                        if (result.success) {
                            this.success(result.message || 'Rack creado exitosamente');
                            this.cerrarModalCrearRack();
                            this.cargarDatos(); // Recargar datos para actualizar stats

                            // Mostrar información adicional si está disponible
                            if (result.data && result.data.total_ubicaciones) {
                                console.log(
                                    `Se crearon ${result.data.total_ubicaciones} ubicaciones automáticamente`
                                );
                            }
                        } else {
                            this.error(result.message || 'Error al crear rack');
                            if (result.errors) {
                                Object.values(result.errors).forEach(errorArray => {
                                    errorArray.forEach(error => {
                                        this.error(error);
                                    });
                                });
                            }
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.error('Error de conexión al servidor');
                    } finally {
                        this.modalCrearRack.loading = false;
                    }
                },

                // MÉTODOS PARA CREAR UBICACIÓN
                async abrirModalCrearUbicacion() {
                    try {
                        // Cargar lista de racks
                        const response = await fetch('/almacen/racks/listar');
                        const result = await response.json();

                        if (result.success) {
                            this.modalCrearUbicacion.racks = result.data;
                            this.modalCrearUbicacion.open = true;
                            // Resetear formulario
                            this.modalCrearUbicacion.form = {
                                rack_id: '',
                                codigo: '',
                                nivel: 1,
                                posicion: 1,
                                capacidad_maxima: 10000,
                                estado_ocupacion: 'vacio'
                            };
                            this.modalCrearUbicacion.rackInfo = null;
                        } else {
                            this.error('Error al cargar lista de racks');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.error('Error de conexión al servidor');
                    }
                },

                cerrarModalCrearUbicacion() {
                    this.modalCrearUbicacion.open = false;
                    this.modalCrearUbicacion.loading = false;
                },

                async cargarRackInfo() {
                    if (!this.modalCrearUbicacion.form.rack_id) {
                        this.modalCrearUbicacion.rackInfo = null;
                        return;
                    }

                    try {
                        console.log('Cargando información del rack:', this.modalCrearUbicacion.form
                            .rack_id);

                        const response = await fetch(
                            `/almacen/racks/${this.modalCrearUbicacion.form.rack_id}/info`);

                        if (!response.ok) {
                            throw new Error(`Error HTTP: ${response.status}`);
                        }

                        const result = await response.json();
                        console.log('Respuesta info rack:', result);

                        if (result.success) {
                            this.modalCrearUbicacion.rackInfo = result.data;
                            console.log('Info del rack cargada:', result.data);
                        } else {
                            console.warn('No se pudo cargar la info del rack:', result.message);
                            this.modalCrearUbicacion.rackInfo = null;
                        }
                    } catch (error) {
                        console.error('Error al cargar info del rack:', error);
                        this.modalCrearUbicacion.rackInfo = null;
                        this.error('Error al cargar información del rack seleccionado');
                    }
                },

                async crearUbicacion() {
                    this.modalCrearUbicacion.loading = true;

                    try {
                        const response = await fetch('/almacen/ubicaciones/crear', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(this.modalCrearUbicacion.form)
                        });

                        const result = await response.json();

                        if (result.success) {
                            this.success('Ubicación creada exitosamente');
                            this.cerrarModalCrearUbicacion();
                            this.cargarDatos(); // Recargar datos para actualizar stats
                        } else {
                            this.error(result.message || 'Error al crear ubicación');
                            if (result.errors) {
                                Object.values(result.errors).forEach(errorArray => {
                                    errorArray.forEach(error => {
                                        this.error(error);
                                    });
                                });
                            }
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.error('Error de conexión al servidor');
                    } finally {
                        this.modalCrearUbicacion.loading = false;
                    }
                },

                // Método para mostrar notificaciones (si no lo tienes)
                success(message) {
                    // Puedes usar toastr, sweetalert, o console.log
                    console.log('Éxito:', message);
                    alert('Éxito: ' +
                        message); // Temporal, puedes cambiar por tu sistema de notificaciones
                },

                error(message) {
                    console.error('Error:', message);
                    alert('Error: ' + message); // Temporal
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

                            // ✅ DEBUG: Verificar que los datos tengan sede
                            console.log('Datos cargados:', {
                                total: this.data.length,
                                tieneSede: this.data.every(d => d.hasOwnProperty('sede')),
                                sample: this.data.slice(0, 3).map(d => ({
                                    rack: d.rack,
                                    sede: d.sede,
                                    x: d.x,
                                    y: d.y
                                }))
                            });

                            this.renderChart();
                        } else {
                            console.error('Error al cargar datos:', result);
                            alert('Error al cargar los datos del almacén');
                        }
                    } catch (error) {
                        console.error('Error en cargarDatos:', error);
                        alert('Error de conexión al servidor');
                        this.loading = false;
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
                        sede: 'LOS OLIVOS', // ← Ahora tiene el valor por defecto
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
                        1: '#fee2e2',
                        2: '#dbeafe',
                        3: '#dcfce7',
                        4: '#ede9fe',
                        5: '#fef9c3',
                        6: '#cffafe',
                        7: '#fbcfe8',
                        8: '#e0f2fe',
                        9: '#d9f99d',
                        10: '#fcd34d'
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

                    // ✅ VALIDAR QUE HAY DATOS
                    if (!this.data || this.data.length === 0) {
                        console.warn('No hay datos para renderizar el heatmap');
                        this.loading = false;

                        // Mostrar mensaje en el contenedor
                        heatmapEl.innerHTML = `
            <div style="display: flex; justify-content: center; align-items: center; height: 100%; flex-direction: column; color: #666;">
                <i class="fas fa-box-open" style="font-size: 48px; margin-bottom: 16px;"></i>
                <h3>No hay datos disponibles</h3>
                <p>No se encontraron racks para mostrar</p>
            </div>
        `;
                        return;
                    }

                    try {
                        // Calcular dimensiones con validación
                        const xValues = this.data.map(d => d.x).filter(x => x !== undefined);
                        const yValues = this.data.map(d => d.y).filter(y => y !== undefined);

                        if (xValues.length === 0 || yValues.length === 0) {
                            throw new Error('Datos de coordenadas inválidos');
                        }

                        const cols = Math.max(...xValues) + 1;
                        const rows = Math.max(...yValues) + 1;

                        heatmapEl.style.width = (cols * 100) + 'px';
                        heatmapEl.style.height = (rows * 50) + 'px';

                        if (this.chart) {
                            this.chart.dispose();
                        }

                        this.chart = echarts.init(heatmapEl);
                        this.updateChart();

                        window.addEventListener('resize', () => this.chart && this.chart.resize());
                    } catch (error) {
                        console.error('Error al renderizar chart:', error);
                        this.loading = false;

                        heatmapEl.innerHTML = `
            <div style="display: flex; justify-content: center; align-items: center; height: 100%; flex-direction: column; color: #dc2626;">
                <i class="fas fa-exclamation-triangle" style="font-size: 48px; margin-bottom: 16px;"></i>
                <h3>Error al cargar el mapa de calor</h3>
                <p>${error.message}</p>
            </div>
        `;
                    }
                },

                baseOption() {
                    // ✅ VALIDAR QUE HAY DATOS ANTES DE PROCESAR
                    if (!this.data || this.data.length === 0) {
                        return {
                            title: {
                                text: 'No hay datos disponibles',
                                left: 'center',
                                top: 'center',
                                textStyle: {
                                    color: '#999',
                                    fontSize: 16
                                }
                            }
                        };
                    }

                    const valorCampo = this.mode === 'heat' ? 'value' : 'ocupacion';

                    // ✅ AGREGAR SEDE AL ARRAY DE DATOS (índice 10)
                    // ✅ AGREGAR TODOS LOS CAMPOS INCLUYENDO CUSTODIAS
                    const data = this.data.map(d => [
                        d.x || 0,
                        d.y || 0,
                        d[valorCampo] || 0,
                        d.ubicacion || 'N/A',
                        d.piso || 1,
                        d.rack || 'N/A',
                        d.cantidad || 0,
                        d.categoria || 'Sin categoría',
                        d.capacidad || 100,
                        d.sede || 'N/A',
                        d.tipo_articulo || 'Sin tipo',
                        d.nivel || 1
                    ]);


                    // ✅ OBTENER DATOS DEL YAXIS CON VALIDACIÓN
                    const yAxisData = [...new Set(this.data.map(d => d.y))].filter(y => y !==
                        undefined);

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
                                try {
                                    // ✅ SOLO LOS CAMPOS QUE NECESITAMOS
                                    const [x, y, val, ubicacion, piso, rack, cantidad,
                                        categoria, capacidad, sede, tipoArticulo, nivel
                                    ] = p.data;

                                    return `
            <div style="padding:12px; min-width: 320px;">
                <div style="font-size:18px;font-weight:bold;margin-bottom:10px;color:#60a5fa;">
                    🏢 Rack ${rack} - ${sede}
                    ${tipoArticulo.includes('CUSTODIA') ? '<span style="font-size:12px;background:#ef4444;color:white;padding:2px 6px;border-radius:10px;margin-left:8px;">CUSTODIA</span>' : ''}
                </div>
                
                <div style="margin-bottom:6px;">📍 <strong>Ubicación:</strong> ${ubicacion}</div>
                <div style="margin-bottom:6px;">🏷️ <strong>Categoría:</strong> ${categoria}</div>
                <div style="margin-bottom:6px;">📊 <strong>Cantidad:</strong> ${cantidad} / ${capacidad}</div>
                <div style="margin-bottom:6px;">🔧 <strong>Tipo Artículo:</strong> ${tipoArticulo}</div>
                <div style="margin-bottom:6px;">🏗️ <strong>Piso:</strong> ${nivel}</div>
                <div style="margin-bottom:6px;">📈 <strong>${this.mode === 'heat' ? 'Actividad' : 'Ocupación'}:</strong> ${val}%</div>
                
                <div style="font-size:13px;color:#94a3b8;margin-top:10px;">${this.periodoLabel()}</div>
                <div style="font-size:13px;color:#fbbf24;margin-top:6px;">💡 Click para ver detalles</div>
            </div>
        `;
                                } catch (error) {
                                    console.error('Error en tooltip:', error);
                                    return '<div>Error al cargar información</div>';
                                }
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
                                margin: 15,
                                formatter: (value) => {
                                    try {
                                        // Encontrar la letra del rack para esta fila
                                        const rackData = this.data.find(d => d.y == value);
                                        return rackData ? (rackData.letra || value) : value;
                                    } catch (error) {
                                        return value;
                                    }
                                }
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
                            data: yAxisData, // ✅ Usar datos validados
                        },
                        visualMap: [],
                        series: [{
                            type: 'heatmap',
                            data: data,
                            progressive: 2000,
                            label: {
                                show: this.labels,
                                formatter: (p) => {
                                    try {
                                        const ubicacion = p.data[3] || 'N/A';
                                        const valor = p.data[2] || 0;
                                        return `${this.getIconByValue(valor)} ${valor}%\n${ubicacion}`;
                                    } catch (error) {
                                        return 'N/A';
                                    }
                                },
                                color: '#1e293b',
                                fontSize: 10,
                                fontWeight: "bold"
                            },
                            itemStyle: {
                                color: p => this.getFillColorByFloor(p.data[4] || 1),
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
                    if (!this.chart) {
                        console.warn('Chart no inicializado');
                        this.loading = false;
                        return;
                    }

                    try {
                        this.loading = true;
                        const option = this.baseOption();
                        this.chart.setOption(option, true);

                        this.chart.off('finished');
                        this.chart.on('finished', () => {
                            this.loading = false;
                        });

                        this.chart.off('click');
                       this.chart.on('click', async (p) => {
    try {
        if (!p.data || p.data.length < 12) {
            console.warn('Datos incompletos en el click:', p.data);
            return;
        }

        const rack = p.data[5];
        const sede = p.data[9];

        if (rack && sede && rack !== 'N/A' && sede !== 'N/A') {
            // Hacer una consulta para obtener el tipo de rack
            try {
                const response = await fetch(`/api/racks/tipo-rack?nombre=${rack}&sede=${encodeURIComponent(sede)}`);
                const result = await response.json();
                
                const tipoRack = result.success ? result.data.tipo_rack : 'spark';
                console.log('🎯 Tipo de rack obtenido:', tipoRack);

                let vista = 'detalle-rack';
                if (tipoRack === 'panel') {
                    vista = 'detalle-rack-panel';
                }
                
                window.location.href = `/almacen/ubicaciones/${vista}/${rack}?sede=${encodeURIComponent(sede)}`;
            } catch (error) {
                console.error('Error al obtener tipo de rack:', error);
                // Fallback a la vista por defecto
                window.location.href = `/almacen/ubicaciones/detalle-rack/${rack}?sede=${encodeURIComponent(sede)}`;
            }
        }
    } catch (error) {
        console.error('Error en click handler:', error);
    }
});

                        setTimeout(() => {
                            if (this.loading) {
                                this.loading = false;
                            }
                        }, 3000);

                    } catch (error) {
                        console.error('Error al actualizar chart:', error);
                        this.loading = false;
                    }
                }
            }));
        });
    </script>
</x-layout.default>