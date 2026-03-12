<x-layout.default>

    <!-- CDNs necesarios -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.5.0/echarts.min.js"></script>

    <div x-data="analyticsDashboard" x-init="init()">
        <!-- Header mejorado con acciones -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-5">
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="/" class="text-primary hover:underline">
                        Dashboard
                    </a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-2 rtl:before:ml-2">
                    <span>Analytics Tickets</span>
                </li>
            </ul>
        </div>

        <div class="pt-5 transition-all duration-300" :class="{ 'scale-100': expandido }">
            <!-- Fila 1: KPIs Principales -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Tickets -->
                <div class="panel bg-gradient-to-r from-[#4361ee] to-[#805dca] text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-8 -mt-8"></div>
                    <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/10 rounded-full -ml-6 -mb-6"></div>

                    <div class="flex justify-between items-center relative z-10">
                        <div>
                            <p class="text-white/70 text-sm flex items-center gap-1">
                                <i class="fa-solid fa-ticket"></i>
                                Total Tickets
                            </p>
                            <div class="flex items-baseline gap-2 mt-1">
                                <h3 class="text-3xl font-bold"
                                    x-text="mockData.ticketsPorPeriodo[periodoTickets]?.toLocaleString()"></h3>
                                <span class="text-xs bg-white/20 px-2 py-1 rounded-full">
                                    <i class="fa-solid fa-arrow-trend-up mr-1"></i>
                                    +<span x-text="mockData.ticketsPorPeriodo.tendencia[periodoTickets]"></span>%
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-3 gap-2 text-white/80 text-xs relative z-10">
                        <div class="bg-white/10 rounded p-2 text-center">
                            <div>Día</div>
                            <div class="font-bold text-sm" x-text="mockData.ticketsPorPeriodo.dia"></div>
                        </div>
                        <div class="bg-white/10 rounded p-2 text-center">
                            <div>Semana</div>
                            <div class="font-bold text-sm" x-text="mockData.ticketsPorPeriodo.semana"></div>
                        </div>
                        <div class="bg-white/10 rounded p-2 text-center">
                            <div>Mes</div>
                            <div class="font-bold text-sm" x-text="mockData.ticketsPorPeriodo.mes"></div>
                        </div>
                    </div>
                </div>

                <!-- Tickets Cerrados -->
                <div class="panel">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-white-dark text-sm">Tickets Cerrados</p>
                        <span class="text-xs bg-success/10 text-success px-2 py-1 rounded-full"
                            x-text="Math.round((mockData.ticketsPorEstado.find(e => e.estado === 'Cerrado')?.cantidad || 0) / mockData.ticketsPorPeriodo.mes * 100) + '% del total'"></span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="bg-success/10 text-success rounded-full w-12 h-12 flex items-center justify-center">
                            <i class="fa-solid fa-circle-check text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="text-2xl font-bold"
                                x-text="mockData.ticketsPorEstado.find(e => e.estado === 'Cerrado')?.cantidad"></h4>
                            <div class="flex items-center gap-2 text-xs">
                                <i class="fa-solid fa-arrow-trend-up text-success"></i>
                                <span class="text-success">+12% vs mes anterior</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tiempo Prom. Resolución -->
                <div class="panel">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-white-dark text-sm">Tiempo Prom. Resolución</p>
                        <span class="text-xs bg-warning/10 text-warning px-2 py-1 rounded-full">
                            Meta: 48h
                        </span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="bg-warning/10 text-warning rounded-full w-12 h-12 flex items-center justify-center">
                            <i class="fa-regular fa-clock text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="text-2xl font-bold"
                                x-text="mockData.tiemposPromedio.resolucionTotal.horas + 'h'"></h4>
                            <div class="flex items-center gap-2 text-xs">
                                <i
                                    :class="mockData.tiemposPromedio.resolucionTotal.tendencia > 0 ?
                                        'fa-solid fa-arrow-trend-up text-danger' :
                                        'fa-solid fa-arrow-trend-down text-success'"></i>
                                <span
                                    :class="mockData.tiemposPromedio.resolucionTotal.tendencia > 0 ? 'text-danger' :
                                        'text-success'"
                                    x-text="Math.abs(mockData.tiemposPromedio.resolucionTotal.tendencia) + 'h vs ayer'"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reincidencias -->
                <div class="panel">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-white-dark text-sm">Reincidencias</p>
                        <span class="text-xs bg-danger/10 text-danger px-2 py-1 rounded-full">
                            <i class="fa-solid fa-arrow-trend-down mr-1"></i>
                            <span x-text="Math.abs(mockData.reincidencias.tendencia) + '%'"></span>
                        </span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="bg-danger/10 text-danger rounded-full w-12 h-12 flex items-center justify-center">
                            <i class="fa-solid fa-rotate-right text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="text-2xl font-bold" x-text="mockData.reincidencias.porcentaje + '%'"></h4>
                            <div class="flex items-center gap-1 text-xs">
                                <span class="text-white-dark"
                                    x-text="mockData.reincidencias.reincidentes + ' tickets'"></span>
                                <span class="text-danger"
                                    x-text="'• ' + mockData.reincidencias.total + ' únicos'"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfico de Tendencia -->
            <div class="grid grid-cols-1 gap-6 mb-6">
                <div class="panel">
                    <div class="flex justify-between items-center mb-5">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-chart-line text-primary text-xl"></i>
                            <h5 class="font-semibold text-lg">Tendencia de Tickets</h5>
                        </div>
                        <div class="flex gap-2">
                            <button class="px-3 py-1 text-xs bg-primary/10 text-primary rounded-md">
                                <i class="fa-regular fa-calendar mr-1"></i> Últimos 30 días
                            </button>
                        </div>
                    </div>
                    <div x-ref="tendenciaChart" style="height: 200px; width: 100%;"></div>
                </div>
            </div>

            <!-- Fila 2: Tickets por Distrito y por Falla -->
            <div class="grid lg:grid-cols-2 gap-6 mb-6">
                <div class="panel">
                    <div class="flex justify-between items-center mb-5">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-map-location text-primary text-xl"></i>
                            <h5 class="font-semibold text-lg">Tickets por Distrito</h5>
                        </div>
                        <div class="flex gap-1 bg-white-dark/10 rounded-lg p-1">
                            <button
                                @click="vistaDistritos = 'grafico'; setTimeout(() => reinicializarDistritosChart(), 50)"
                                class="px-3 py-1 rounded-md text-sm"
                                :class="vistaDistritos === 'grafico' ? 'bg-primary text-white' : ''">
                                <i class="fa-solid fa-chart-bar"></i>
                            </button>
                            <button @click="vistaDistritos = 'tabla'" class="px-3 py-1 rounded-md text-sm"
                                :class="vistaDistritos === 'tabla' ? 'bg-primary text-white' : ''">
                                <i class="fa-solid fa-table"></i>
                            </button>
                        </div>
                    </div>

                    <template x-if="vistaDistritos === 'grafico'">
                        <div>
                            <!-- 👇 AGREGAR x-init PARA REINICIALIZAR EL CHART -->
                            <div x-ref="distritosChart" x-init="setTimeout(() => {
                                if ($refs.distritosChart) {
                                    charts.distritos = echarts.init($refs.distritosChart);
                                    charts.distritos.setOption({ ... });
                                }
                            }, 100)" style="height: 350px; width: 100%;">
                            </div>
                            <div class="mt-4 flex flex-wrap gap-2 text-sm">
                                <span class="bg-success/10 text-success px-2 py-1 rounded-full text-xs">
                                    <i class="fa-solid fa-arrow-trend-up mr-1"></i> Crecimiento
                                </span>
                                <span class="bg-danger/10 text-danger px-2 py-1 rounded-full text-xs">
                                    <i class="fa-solid fa-arrow-trend-down mr-1"></i> Decrecimiento
                                </span>
                            </div>
                        </div>
                    </template>

                    <template x-if="vistaDistritos === 'tabla'">
                        <div class="perfect-scrollbar h-[350px] relative overflow-auto">
                            <table class="table-hover w-full">
                                <thead>
                                    <tr>
                                        <th class="text-left">Distrito</th>
                                        <th class="text-left">Provincia</th>
                                        <th class="text-right">Tickets</th>
                                        <th class="text-right">Variación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(item, index) in mockData.ticketsPorDistrito"
                                        :key="index">
                                        <tr>
                                            <td class="font-semibold" x-text="item.distrito"></td>
                                            <td x-text="item.provincia"></td>
                                            <td class="text-right" x-text="item.cantidad"></td>
                                            <td class="text-right">
                                                <span :class="item.variacion >= 0 ? 'text-success' : 'text-danger'"
                                                    class="flex items-center gap-1 justify-end">
                                                    <i
                                                        :class="item.variacion >= 0 ? 'fa-solid fa-arrow-trend-up' :
                                                            'fa-solid fa-arrow-trend-down'"></i>
                                                    <span
                                                        x-text="(item.variacion >= 0 ? '+' : '') + item.variacion + '%'"></span>
                                                </span>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </template>

                    <div class="mt-4 text-sm text-white-dark flex items-center justify-between">
                        <span>
                            <i class="fa-regular fa-building mr-1"></i>
                            <span
                                x-text="[...new Set(mockData.ticketsPorDistrito.map(item => item.provincia))].length + ' provincias'"></span>
                        </span>
                        <span class="font-semibold"
                            x-text="'Total: ' + mockData.ticketsPorDistrito.reduce((acc, item) => acc + item.cantidad, 0) + ' tickets'"></span>
                    </div>
                </div>

                <div class="panel">
                    <div class="flex items-center gap-2 mb-5">
                        <i class="fa-solid fa-gear text-primary text-xl"></i>
                        <h5 class="font-semibold text-lg">Tickets por Tipo de Falla</h5>
                    </div>

                    <!-- Leyenda interactiva -->
                    <div class="grid grid-cols-2 gap-2 mb-4">
                        <template x-for="(falla, index) in mockData.ticketsPorFalla" :key="index">
                            <div class="flex items-center gap-2 text-sm">
                                <span class="w-3 h-3 rounded-full" :style="{ backgroundColor: falla.color }"></span>
                                <span class="flex-1" x-text="falla.falla"></span>
                                <span class="font-semibold" x-text="falla.porcentaje + '%'"></span>
                            </div>
                        </template>
                    </div>

                    <div x-ref="fallasChart" style="height: 300px; width: 100%;"></div>

                    <div class="mt-4 grid grid-cols-3 gap-2 text-center">
                        <div class="bg-primary/5 p-2 rounded">
                            <div class="text-xs text-white-dark">Principal</div>
                            <div class="font-semibold" x-text="mockData.ticketsPorFalla[0].falla"></div>
                            <div class="text-primary text-sm" x-text="mockData.ticketsPorFalla[0].cantidad"></div>
                        </div>
                        <div class="bg-success/5 p-2 rounded">
                            <div class="text-xs text-white-dark">Mejoría</div>
                            <div class="font-semibold">Software</div>
                            <div class="text-success text-sm">-5%</div>
                        </div>
                        <div class="bg-danger/5 p-2 rounded">
                            <div class="text-xs text-white-dark">Crítico</div>
                            <div class="font-semibold">Mainboard</div>
                            <div class="text-danger text-sm">+8%</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FILA 3: TICKETS POR ESTADO -->
            <div class="grid grid-cols-1 gap-6 mb-6">
                <div class="panel">

                    <!-- Header -->
                    <div class="flex justify-between items-center mb-5">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-chart-simple text-primary text-xl"></i>
                            <h5 class="font-semibold text-lg">Flujo de Tickets por Estado</h5>
                        </div>

                        <!-- Leyenda -->
                        <div class="flex gap-3">
                            <template x-for="(estado, index) in mockData.ticketsPorEstado" :key="index">
                                <div class="flex items-center gap-1 text-xs">
                                    <span class="w-2 h-2 rounded-full"
                                        :style="{ backgroundColor: estado.color }"></span>
                                    <span x-text="estado.estado"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Cards -->
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 mb-6">

                        <template x-for="(estado, index) in mockData.ticketsPorEstado" :key="index">
                            <div
                                class="bg-white-dark/5 rounded-lg p-3 text-center hover:shadow-md transition-all border border-white-dark/10">

                                <div class="flex flex-col items-center">

                                    <!-- Icono -->
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center mb-2"
                                        :style="{ backgroundColor: estado.color + '20' }">

                                        <i :class="{
                                            'fa-regular fa-clock': estado.estado === 'Diagnóstico',
                                            'fa-regular fa-circle-check': estado.estado === 'Visita Finalizada',
                                            'fa-solid fa-ticket': estado.estado === 'Pendiente Recojo',
                                            'fa-solid fa-flask': estado.estado === 'Ingreso a Laboratorio',
                                            'fa-solid fa-check-double': estado.estado === 'Cerrado'
                                        }"
                                            class="text-lg" :style="{ color: estado.color }"></i>

                                    </div>

                                    <!-- Estado -->
                                    <div class="text-xs font-semibold text-white-dark mb-1" x-text="estado.estado">
                                    </div>

                                    <!-- Cantidad -->
                                    <div class="text-xl font-bold" :style="{ color: estado.color }"
                                        x-text="estado.cantidad"></div>

                                    <!-- Porcentaje -->
                                    <div class="text-[10px] px-2 py-0.5 rounded-full mt-1"
                                        :style="{ backgroundColor: estado.color + '15', color: estado.color }"
                                        x-text="Math.round(estado.cantidad / mockData.ticketsPorPeriodo.mes * 100) + '% del total'">
                                    </div>

                                </div>

                            </div>
                        </template>

                    </div>

                    <!-- Gráfico -->
                    <div x-ref="estadosChart" style="height: 230px; width: 100%;"></div>

                </div>
            </div>

            <!-- Fila 4: Tiempos Promedio - CORREGIDA (con los mismos estilos que los demás) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Coordinación Inicial -->
                <div
                    class="panel bg-gradient-to-br from-[#1b2e4b] to-[#253b5b] dark:from-[#1b2e4b] dark:to-[#253b5b] text-white dark:text-white relative overflow-hidden group hover:shadow-xl transition-all duration-300">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500">
                    </div>

                    <div class="flex items-center gap-3 relative z-10">
                        <div class="rounded-full w-12 h-12 flex items-center justify-center text-2xl"
                            style="background-color: #0dcaf0">
                            <i class="fa-regular fa-clock text-white"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-white-dark dark:text-white/70" style="color: #0dcaf0">Coord.
                                Inicial</p>
                            <div class="flex items-baseline justify-between">
                                <h4 class="text-2xl font-bold text-black dark:text-white">
                                    <span x-text="mockData.tiemposPromedio.coordinacionInicial.horas"></span>
                                    <span class="text-sm text-white-dark dark:text-white/70">horas</span>
                                </h4>
                                <span
                                    class="text-success text-xs flex items-center gap-1 bg-success/10 px-2 py-1 rounded-full">
                                    <i class="fa-solid fa-arrow-trend-down"></i>
                                    <span
                                        x-text="Math.abs(mockData.tiemposPromedio.coordinacionInicial.tendencia) + 'h'"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Barra de progreso -->
                    <div class="mt-4">
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-white-dark dark:text-white/70">Meta: 48h</span>
                            <span class="text-white-dark dark:text-white/70"
                                x-text="Math.round((48 - mockData.tiemposPromedio.coordinacionInicial.horas) / 48 * 100) + '% restante'"></span>
                        </div>
                        <div class="w-full bg-white/20 rounded-full h-1.5">
                            <div class="h-1.5 rounded-full transition-all duration-500"
                                style="background-color: #0dcaf0; width: 9%"></div>
                        </div>
                    </div>
                </div>

                <!-- Solución On Site -->
                <div
                    class="panel bg-gradient-to-br from-[#1b2e4b] to-[#253b5b] dark:from-[#1b2e4b] dark:to-[#253b5b] text-white dark:text-white relative overflow-hidden group hover:shadow-xl transition-all duration-300">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500">
                    </div>

                    <div class="flex items-center gap-3 relative z-10">
                        <div class="rounded-full w-12 h-12 flex items-center justify-center text-2xl"
                            style="background-color: #198754">
                            <i class="fa-solid fa-user-check text-white"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-white-dark dark:text-white/70" style="color: #198754">Solución On
                                Site</p>
                            <div class="flex items-baseline justify-between">
                                <h4 class="text-2xl font-bold text-black dark:text-white">
                                    <span x-text="mockData.tiemposPromedio.solucionOnSite.horas"></span>
                                    <span class="text-sm text-white-dark dark:text-white/70">horas</span>
                                </h4>
                                <span
                                    class="text-danger text-xs flex items-center gap-1 bg-danger/10 px-2 py-1 rounded-full">
                                    <i class="fa-solid fa-arrow-trend-up"></i>
                                    <span
                                        x-text="Math.abs(mockData.tiemposPromedio.solucionOnSite.tendencia) + 'h'"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Barra de progreso -->
                    <div class="mt-4">
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-white-dark dark:text-white/70">Meta: 48h</span>
                            <span class="text-white-dark dark:text-white/70"
                                x-text="Math.round((48 - mockData.tiemposPromedio.solucionOnSite.horas) / 48 * 100) + '% restante'"></span>
                        </div>
                        <div class="w-full bg-white/20 rounded-full h-1.5">
                            <div class="h-1.5 rounded-full transition-all duration-500"
                                style="background-color: #198754; width: 6%"></div>
                        </div>
                    </div>
                </div>

                <!-- Solución Laboratorio -->
                <div
                    class="panel bg-gradient-to-br from-[#1b2e4b] to-[#253b5b] dark:from-[#1b2e4b] dark:to-[#253b5b] text-white dark:text-white relative overflow-hidden group hover:shadow-xl transition-all duration-300">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500">
                    </div>

                    <div class="flex items-center gap-3 relative z-10">
                        <div class="rounded-full w-12 h-12 flex items-center justify-center text-2xl"
                            style="background-color: #ffc107">
                            <i class="fa-solid fa-flask text-white"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-white-dark dark:text-white/70" style="color: #ffc107">Solución Lab.
                            </p>
                            <div class="flex items-baseline justify-between">
                                <h4 class="text-2xl font-bold text-black dark:text-white">
                                    <span x-text="mockData.tiemposPromedio.solucionLaboratorio.horas"></span>
                                    <span class="text-sm text-white-dark dark:text-white/70">horas</span>
                                </h4>
                                <span
                                    class="text-success text-xs flex items-center gap-1 bg-success/10 px-2 py-1 rounded-full">
                                    <i class="fa-solid fa-arrow-trend-down"></i>
                                    <span
                                        x-text="Math.abs(mockData.tiemposPromedio.solucionLaboratorio.tendencia) + 'h'"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Barra de progreso -->
                    <div class="mt-4">
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-white-dark dark:text-white/70">Meta: 48h</span>
                            <span class="text-white-dark dark:text-white/70"
                                x-text="Math.round((48 - mockData.tiemposPromedio.solucionLaboratorio.horas) / 48 * 100) + '% restante'"></span>
                        </div>
                        <div class="w-full bg-white/20 rounded-full h-1.5">
                            <div class="h-1.5 rounded-full transition-all duration-500"
                                style="background-color: #ffc107; width: 51%"></div>
                        </div>
                    </div>
                </div>

                <!-- Resolución Total -->
                <div
                    class="panel bg-gradient-to-br from-[#1b2e4b] to-[#253b5b] dark:from-[#1b2e4b] dark:to-[#253b5b] text-white dark:text-white relative overflow-hidden group hover:shadow-xl transition-all duration-300">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500">
                    </div>

                    <div class="flex items-center gap-3 relative z-10">
                        <div class="rounded-full w-12 h-12 flex items-center justify-center text-2xl"
                            style="background-color: #dc3545">
                            <i class="fa-solid fa-check-double text-white"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-white-dark dark:text-white/70" style="color: #dc3545">Resolución
                                Total</p>
                            <div class="flex items-baseline justify-between">
                                <h4 class="text-2xl font-bold text-black dark:text-white">
                                    <span x-text="mockData.tiemposPromedio.resolucionTotal.horas"></span>
                                    <span class="text-sm text-white-dark dark:text-white/70">horas</span>
                                </h4>
                                <span
                                    class="text-danger text-xs flex items-center gap-1 bg-danger/10 px-2 py-1 rounded-full">
                                    <i class="fa-solid fa-arrow-trend-up"></i>
                                    <span
                                        x-text="Math.abs(mockData.tiemposPromedio.resolucionTotal.tendencia) + 'h'"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Barra de progreso -->
                    <div class="mt-4">
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-white-dark dark:text-white/70">Meta: 48h</span>
                            <span class="bg-warning/10 text-warning px-2 py-1 rounded-full text-xs"
                                x-text="Math.round((48 - mockData.tiemposPromedio.resolucionTotal.horas) / 48 * 100) + '% restante'"></span>
                        </div>
                        <div class="w-full bg-white/20 rounded-full h-1.5">
                            <div class="h-1.5 rounded-full transition-all duration-500"
                                style="background-color: #dc3545; width: 151%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fila 5: Tickets por Técnico y Personal -->
            <div class="grid lg:grid-cols-2 gap-6 mb-6">
                <div class="panel">
                    <div class="flex justify-between items-center mb-5">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-user-gear text-primary text-xl"></i>
                            <h5 class="font-semibold text-lg">Rendimiento por Técnico</h5>
                        </div>
                        <div class="flex gap-1 bg-white-dark/10 rounded-lg p-1">
                            <button @click="periodoTecnicos = 'diario'"
                                class="px-3 py-1 rounded-md text-sm flex items-center gap-1"
                                :class="periodoTecnicos === 'diario' ? 'bg-primary text-white' : ''">
                                <i class="fa-regular fa-calendar"></i> Día
                            </button>
                            <button @click="periodoTecnicos = 'semanal'"
                                class="px-3 py-1 rounded-md text-sm flex items-center gap-1"
                                :class="periodoTecnicos === 'semanal' ? 'bg-primary text-white' : ''">
                                <i class="fa-regular fa-calendar-week"></i> Semana
                            </button>
                            <button @click="periodoTecnicos = 'mensual'"
                                class="px-3 py-1 rounded-md text-sm flex items-center gap-1"
                                :class="periodoTecnicos === 'mensual' ? 'bg-primary text-white' : ''">
                                <i class="fa-regular fa-calendar-alt"></i> Mes
                            </button>
                        </div>
                    </div>

                    <!-- Tarjetas de técnicos -->
                    <div class="grid grid-cols-1 gap-3 mb-4">
                        <template x-for="(tecnico, index) in mockData.ticketsPorTecnico[periodoTecnicos]"
                            :key="index">
                            <div class="bg-white-dark/5 rounded-lg p-3 hover:bg-primary/5 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <!-- Círculo con iniciales - usando bg-primary, bg-warning, etc -->
                                        <div :class="{
                                            'bg-warning': index === 0,
                                            'bg-secondary': index === 1,
                                            'bg-info': index === 2,
                                            'bg-primary': index > 2
                                        }"
                                            class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold">
                                            <span x-text="tecnico.tecnico.split(' ').map(n => n[0]).join('')"></span>
                                        </div>
                                        <div>
                                            <div class="font-semibold flex items-center gap-2">
                                                <span x-text="tecnico.tecnico"></span>
                                                <i x-show="index === 0" class="fa-solid fa-medal text-warning"></i>
                                                <i x-show="tecnico.eficiencia >= 95"
                                                    class="fa-solid fa-star text-warning text-xs"></i>
                                            </div>
                                            <div class="flex items-center gap-3 text-xs">
                                                <span class="text-white-dark">
                                                    <i class="fa-solid fa-ticket mr-1"></i>
                                                    <span x-text="tecnico.tickets"></span> tickets
                                                </span>
                                                <!-- Texto con bg-success, bg-warning, bg-danger según eficiencia -->
                                                <span
                                                    :class="{
                                                        'text-success': tecnico.eficiencia >= 90,
                                                        'text-warning': tecnico.eficiencia >= 80 && tecnico.eficiencia <
                                                            90,
                                                        'text-danger': tecnico.eficiencia < 80
                                                    }">
                                                    <i class="fa-solid fa-chart-line mr-1"></i>
                                                    <span x-text="tecnico.eficiencia"></span>% eficiencia
                                                </span>
                                                <span x-show="tecnico.reincidencias > 0" class="text-danger">
                                                    <i class="fa-solid fa-rotate-right mr-1"></i>
                                                    <span x-text="tecnico.reincidencias"></span> re.
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Barra de progreso de eficiencia - usando bg-success, bg-warning, bg-danger -->
                                    <div class="w-24">
                                        <div class="w-full bg-white-dark/20 rounded-full h-2">
                                            <div :class="{
                                                'bg-success': tecnico.eficiencia >= 90,
                                                'bg-warning': tecnico.eficiencia >= 80 && tecnico.eficiencia < 90,
                                                'bg-danger': tecnico.eficiencia < 80
                                            }"
                                                class="h-2 rounded-full" :style="{ width: tecnico.eficiencia + '%' }">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Mini gráfico de comparativa -->
                    <div x-ref="tecnicosChart" style="height: 150px; width: 100%;"></div>
                </div>

                <div class="panel">
                    <div class="flex items-center gap-2 mb-5">
                        <i class="fa-solid fa-users text-primary text-xl"></i>
                        <h5 class="font-semibold text-lg">Rendimiento del Personal</h5>
                    </div>

                    <!-- Tarjetas de períodos -->
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <template x-for="(periodo, index) in ['Diario', 'Semanal', 'Mensual']" :key="index">
                            <div class="text-center p-4 bg-white-dark/5 rounded-lg hover:shadow-lg transition-all">
                                <!-- Iconos con colores -->
                                <i :class="{
                                    'fa-regular fa-calendar text-primary': index === 0,
                                    'fa-regular fa-calendar-week text-success': index === 1,
                                    'fa-regular fa-calendar-alt text-warning': index === 2
                                }"
                                    class="text-2xl mb-2"></i>

                                <p class="text-white-dark text-sm" x-text="periodo"></p>
                                <p class="text-2xl font-bold"
                                    x-text="[mockData.ticketsPorPersonal.diario, mockData.ticketsPorPersonal.semanal, mockData.ticketsPorPersonal.mensual][index]">
                                </p>

                                <!-- Barra de progreso -->
                                <div class="mt-2">
                                    <div class="w-full bg-white-dark/20 rounded-full h-1.5">
                                        <div :class="{
                                            'bg-primary': index === 0,
                                            'bg-success': index === 1,
                                            'bg-warning': index === 2
                                        }"
                                            class="h-1.5 rounded-full"
                                            :style="{
                                                width: Math.min(100, ([mockData.ticketsPorPersonal.diario, mockData
                                                    .ticketsPorPersonal.semanal, mockData.ticketsPorPersonal
                                                    .mensual
                                                ][index] / [mockData.ticketsPorPersonal.objetivos.diario,
                                                    mockData.ticketsPorPersonal.objetivos.semanal, mockData
                                                    .ticketsPorPersonal.objetivos.mensual
                                                ][index]) * 100) + '%'
                                            }">
                                        </div>
                                    </div>
                                    <p class="text-xs text-white-dark mt-1"
                                        x-text="Math.round(([mockData.ticketsPorPersonal.diario, mockData.ticketsPorPersonal.semanal, mockData.ticketsPorPersonal.mensual][index] / [mockData.ticketsPorPersonal.objetivos.diario, mockData.ticketsPorPersonal.objetivos.semanal, mockData.ticketsPorPersonal.objetivos.mensual][index]) * 100) + '% de meta (' + [mockData.ticketsPorPersonal.objetivos.diario, mockData.ticketsPorPersonal.objetivos.semanal, mockData.ticketsPorPersonal.objetivos.mensual][index] + ')'">
                                    </p>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Top técnicos con medallas - CORREGIDO -->
                    <div class="bg-gradient-to-r from-primary/5 to-transparent rounded-lg p-4">
                        <h6 class="font-semibold mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-medal text-warning"></i>
                            Podio de Honor - <span x-text="periodoTecnicos" class="text-primary"></span>
                        </h6>
                        <div class="space-y-3">
                            <template
                                x-for="(tecnico, index) in mockData.ticketsPorTecnico[periodoTecnicos].slice(0, 3)"
                                :key="index">
                                <div
                                    class="flex items-center justify-between p-2 hover:bg-white-dark/5 rounded-lg transition-colors">
                                    <div class="flex items-center gap-3">
                                        <!-- Círculos con bg-warning, bg-secondary, bg-info -->
                                        <div :class="{
                                            'bg-warning': index === 0,
                                            'bg-secondary': index === 1,
                                            'bg-info': index === 2
                                        }"
                                            class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                            <span x-text="index + 1"></span>
                                        </div>
                                        <div>
                                            <span class="font-semibold" x-text="tecnico.tecnico"></span>
                                            <div class="flex items-center gap-2 text-xs text-white-dark">
                                                <span><i class="fa-solid fa-ticket mr-1"></i><span
                                                        x-text="tecnico.tickets"></span> tickets</span>
                                                <span
                                                    :class="{
                                                        'text-success': tecnico.eficiencia >= 90,
                                                        'text-warning': tecnico.eficiencia >= 80 && tecnico.eficiencia <
                                                            90,
                                                        'text-danger': tecnico.eficiencia < 80
                                                    }">
                                                    <i class="fa-solid fa-chart-line mr-1"></i><span
                                                        x-text="tecnico.eficiencia"></span>%
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-lg" x-text="tecnico.tickets"></div>
                                        <div class="text-xs text-success"
                                            x-text="'+' + Math.round(tecnico.tickets * 0.15) + ' vs promedio'"></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fila 6: Análisis de Reincidencias -->
            <div class="grid grid-cols-1 gap-6">
                <div class="panel">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-rotate-right text-danger text-xl"></i>
                            <h5 class="font-semibold text-lg">Análisis de Reincidencias</h5>
                        </div>
                        <div class="flex items-center gap-4">
                            <!-- Badge mejorando -->
                            <div class="flex items-center gap-2 bg-danger/5 px-3 py-1 rounded-full">
                                <i class="fa-solid fa-arrow-trend-down text-success"></i>
                                <span class="text-sm"
                                    x-text="'Mejorando ' + Math.abs(mockData.reincidencias.tendencia) + '%'"></span>
                            </div>
                            <!-- Badge tickets -->
                            <div class="flex items-center gap-2 bg-primary/5 px-3 py-1 rounded-full">
                                <i class="fa-solid fa-ticket text-primary"></i>
                                <span class="text-sm"
                                    x-text="mockData.reincidencias.reincidentes + ' tickets'"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Grid principal -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Columna izquierda - Gráfico de distribución -->
                        <div class="col-span-2">
                            <div class="bg-white-dark/5 p-4 rounded-lg">
                                <p class="text-white-dark mb-3 flex items-center gap-2">
                                    <i class="fa-solid fa-chart-pie"></i>
                                    Distribución de reincidencias por tipo de falla
                                </p>
                                <div class="space-y-4">
                                    <template x-for="(falla, index) in mockData.ticketsPorFalla"
                                        :key="index">
                                        <div>
                                            <div class="flex justify-between text-sm mb-1">
                                                <div class="flex items-center gap-2">
                                                    <span class="w-2 h-2 rounded-full"
                                                        :style="{ backgroundColor: falla.color }"></span>
                                                    <span x-text="falla.falla"></span>
                                                </div>
                                                <div class="flex items-center gap-3">
                                                    <span class="font-semibold"
                                                        x-text="Math.round(falla.cantidad * (mockData.reincidencias.porcentaje / 100)) + ' tickets'"></span>
                                                    <span class="text-xs text-white-dark"
                                                        x-text="Math.round((falla.cantidad * (mockData.reincidencias.porcentaje / 100)) / mockData.reincidencias.reincidentes * 100) + '% del total'"></span>
                                                </div>
                                            </div>
                                            <div class="w-full bg-white-dark/20 rounded-full h-2">
                                                <div class="bg-danger h-2 rounded-full transition-all duration-500"
                                                    :style="{
                                                        width: ((falla.cantidad * (mockData.reincidencias.porcentaje /
                                                            100)) / 30) * 100 + '%'
                                                    }">
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Columna derecha - Cards de estadísticas -->
                        <div class="space-y-4">

                            <!-- Card tickets con más de 2 visitas -->
                            <div class="rounded-lg p-4"
                                style="background: linear-gradient(to bottom right, rgba(220,53,69,0.15), rgba(220,53,69,0.05));">

                                <i class="fa-solid fa-triangle-exclamation text-3xl mb-2" style="color:#dc3545"></i>

                                <p class="text-sm text-white-dark mb-1">
                                    Tickets con más de 2 visitas
                                </p>

                                <p class="text-3xl font-bold" style="color:#dc3545">
                                    87
                                </p>

                                <p class="text-xs text-white-dark mt-1">
                                    Requieren atención especial
                                </p>
                            </div>

                            <!-- Card técnicos con más reincidencias -->
                            <div class="bg-white-dark/5 rounded-lg p-4">

                                <h6 class="font-semibold mb-2 text-sm flex items-center gap-2">
                                    <i class="fa-solid fa-users" style="color:#dc3545"></i>
                                    Técnicos con más reincidencias
                                </h6>

                                <div class="space-y-2">
                                    <template x-for="(item, index) in mockData.reincidencias.porTecnico"
                                        :key="index">

                                        <div
                                            class="flex justify-between items-center text-sm py-1 border-b border-white-dark/10 last:border-0">

                                            <span class="flex items-center gap-2">

                                                <span class="w-2 h-2 rounded-full"
                                                    :style="{
                                                        backgroundColor: index === 0 ? '#dc3545' : index === 1 ?
                                                            '#ffc107' : '#0dcaf0'
                                                    }">
                                                </span>

                                                <span x-text="item.tecnico"></span>

                                            </span>

                                            <span class="font-semibold" style="color:#dc3545"
                                                x-text="item.reincidencias">
                                            </span>

                                        </div>

                                    </template>
                                </div>

                            </div>

                            <!-- Card tasa de éxito -->
                            <div class="rounded-lg p-4" style="background: rgba(25,135,84,0.08);">

                                <div class="flex items-center justify-between">

                                    <div>
                                        <p class="text-xs text-white-dark flex items-center gap-1">
                                            <i class="fa-solid fa-circle-check" style="color:#198754"></i>
                                            Tasa de éxito
                                        </p>

                                        <p class="text-xl font-bold" style="color:#198754"
                                            x-text="(100 - mockData.reincidencias.porcentaje) + '%'">
                                        </p>
                                    </div>

                                    <div class="text-right">
                                        <p class="text-xs text-white-dark">1ra visita</p>

                                        <p class="text-lg font-semibold" style="color:#198754"
                                            x-text="mockData.ticketsPorPeriodo.mes - mockData.reincidencias.total">
                                        </p>
                                    </div>

                                </div>

                                <!-- Barra de progreso -->
                                <div class="mt-3">
                                    <div class="w-full bg-white-dark/20 rounded-full h-1.5">
                                        <div class="h-1.5 rounded-full" style="background:#198754"
                                            :style="{ width: (100 - mockData.reincidencias.porcentaje) + '%' }">
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Fila 7: Resumen Ejecutivo -->
            <div class="grid grid-cols-1 gap-6 mt-6">
                <div class="panel bg-info text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h5 class="font-semibold text-lg mb-2">Resumen Ejecutivo</h5>
                            <p class="text-white/80 text-sm">Última actualización: Hoy 10:30 AM</p>
                        </div>
                        <button class="bg-white/20 hover:bg-white/30 rounded-lg p-2 transition-colors">
                            <i class="fa-solid fa-rotate"></i>
                        </button>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                        <div>
                            <p class="text-white/70 text-xs">SLAs cumplidos</p>
                            <p class="text-2xl font-bold">94%</p>
                            <p class="text-green-300 text-xs">+2.5% vs ayer</p>
                        </div>
                        <div>
                            <p class="text-white/70 text-xs">Satisfacción cliente</p>
                            <p class="text-2xl font-bold">4.8/5.0</p>
                            <p class="text-green-300 text-xs">+0.3 puntos</p>
                        </div>
                        <div>
                            <p class="text-white/70 text-xs">Tickets pendientes</p>
                            <p class="text-2xl font-bold">368</p>
                            <p class="text-yellow-300 text-xs">-12 vs ayer</p>
                        </div>
                        <div>
                            <p class="text-white/70 text-xs">Productividad</p>
                            <p class="text-2xl font-bold">87%</p>
                            <p class="text-green-300 text-xs">Meta: 85%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("analyticsDashboard", () => ({
                // Estado
                periodoTickets: 'mes',
                periodoTecnicos: 'diario',
                vistaDistritos: 'grafico',
                expandido: false,

                // Datos mock (exactamente igual que en React)
                mockData: {
                    ticketsPorPeriodo: {
                        dia: 145,
                        semana: 892,
                        mes: 3241,
                        año: 28456,
                        tendencia: {
                            dia: 12,
                            semana: 8,
                            mes: 15,
                            año: 23
                        }
                    },
                    ticketsPorDistrito: [{
                            distrito: 'Santiago de Surco',
                            provincia: 'Lima',
                            cantidad: 456,
                            variacion: 12
                        },
                        {
                            distrito: 'Miraflores',
                            provincia: 'Lima',
                            cantidad: 389,
                            variacion: -5
                        },
                        {
                            distrito: 'San Isidro',
                            provincia: 'Lima',
                            cantidad: 367,
                            variacion: 8
                        },
                        {
                            distrito: 'La Molina',
                            provincia: 'Lima',
                            cantidad: 298,
                            variacion: 3
                        },
                        {
                            distrito: 'San Borja',
                            provincia: 'Lima',
                            cantidad: 276,
                            variacion: -2
                        },
                        {
                            distrito: 'Arequipa',
                            provincia: 'Arequipa',
                            cantidad: 234,
                            variacion: 15
                        },
                        {
                            distrito: 'Trujillo',
                            provincia: 'La Libertad',
                            cantidad: 198,
                            variacion: 10
                        },
                        {
                            distrito: 'Chiclayo',
                            provincia: 'Lambayeque',
                            cantidad: 167,
                            variacion: -8
                        }
                    ],
                    ticketsPorFalla: [{
                            falla: 'Panel',
                            cantidad: 324,
                            porcentaje: 23,
                            color: '#4361ee'
                        },
                        {
                            falla: 'Mainboard',
                            cantidad: 287,
                            porcentaje: 20,
                            color: '#805dca'
                        },
                        {
                            falla: 'Power',
                            cantidad: 198,
                            porcentaje: 14,
                            color: '#e2a03f'
                        },
                        {
                            falla: 'Limpieza Interna',
                            cantidad: 156,
                            porcentaje: 11,
                            color: '#2196f3'
                        },
                        {
                            falla: 'Software',
                            cantidad: 234,
                            porcentaje: 17,
                            color: '#00ab55'
                        },
                        {
                            falla: 'NTF',
                            cantidad: 67,
                            porcentaje: 5,
                            color: '#e7515a'
                        },
                        {
                            falla: 'Falla Externa',
                            cantidad: 89,
                            porcentaje: 6,
                            color: '#607d8b'
                        }
                    ],
                    ticketsPorEstado: [{
                            estado: 'Diagnóstico',
                            cantidad: 156,
                            color: '#f59e0b'
                        },
                        {
                            estado: 'Visita Finalizada',
                            cantidad: 234,
                            color: '#10b981'
                        },
                        {
                            estado: 'Pendiente Recojo',
                            cantidad: 89,
                            color: '#3b82f6'
                        },
                        {
                            estado: 'Ingreso a Laboratorio',
                            cantidad: 123,
                            color: '#8b5cf6'
                        },
                        {
                            estado: 'Cerrado',
                            cantidad: 567,
                            color: '#6b7280'
                        }
                    ],
                    tiemposPromedio: {
                        coordinacionInicial: {
                            horas: 4.5,
                            tendencia: -0.5
                        },
                        solucionOnSite: {
                            horas: 2.8,
                            tendencia: 0.2
                        },
                        solucionLaboratorio: {
                            horas: 24.5,
                            tendencia: -2.3
                        },
                        resolucionTotal: {
                            horas: 72.3,
                            tendencia: 1.5
                        }
                    },
                    reincidencias: {
                        porcentaje: 8.5,
                        total: 234,
                        reincidentes: 1987,
                        porTecnico: [{
                                tecnico: 'Carlos Ruiz',
                                reincidencias: 12
                            },
                            {
                                tecnico: 'Miguel Torres',
                                reincidencias: 8
                            },
                            {
                                tecnico: 'Ana González',
                                reincidencias: 5
                            }
                        ],
                        tendencia: -2.3
                    },
                    ticketsPorTecnico: {
                        diario: [{
                                tecnico: 'Carlos Ruiz',
                                tickets: 8,
                                eficiencia: 95,
                                reincidencias: 1
                            },
                            {
                                tecnico: 'Miguel Torres',
                                tickets: 7,
                                eficiencia: 88,
                                reincidencias: 2
                            },
                            {
                                tecnico: 'José Pérez',
                                tickets: 6,
                                eficiencia: 92,
                                reincidencias: 0
                            },
                            {
                                tecnico: 'Ana González',
                                tickets: 9,
                                eficiencia: 98,
                                reincidencias: 1
                            },
                            {
                                tecnico: 'Luis Fernández',
                                tickets: 5,
                                eficiencia: 85,
                                reincidencias: 1
                            }
                        ],
                        semanal: [{
                                tecnico: 'Carlos Ruiz',
                                tickets: 42,
                                eficiencia: 94,
                                reincidencias: 5
                            },
                            {
                                tecnico: 'Miguel Torres',
                                tickets: 38,
                                eficiencia: 87,
                                reincidencias: 7
                            },
                            {
                                tecnico: 'José Pérez',
                                tickets: 35,
                                eficiencia: 91,
                                reincidencias: 3
                            },
                            {
                                tecnico: 'Ana González',
                                tickets: 45,
                                eficiencia: 97,
                                reincidencias: 4
                            },
                            {
                                tecnico: 'Luis Fernández',
                                tickets: 32,
                                eficiencia: 84,
                                reincidencias: 6
                            }
                        ],
                        mensual: [{
                                tecnico: 'Carlos Ruiz',
                                tickets: 168,
                                eficiencia: 93,
                                reincidencias: 18
                            },
                            {
                                tecnico: 'Miguel Torres',
                                tickets: 152,
                                eficiencia: 86,
                                reincidencias: 24
                            },
                            {
                                tecnico: 'José Pérez',
                                tickets: 145,
                                eficiencia: 90,
                                reincidencias: 15
                            },
                            {
                                tecnico: 'Ana González',
                                tickets: 178,
                                eficiencia: 96,
                                reincidencias: 14
                            },
                            {
                                tecnico: 'Luis Fernández',
                                tickets: 138,
                                eficiencia: 83,
                                reincidencias: 22
                            }
                        ]
                    },
                    ticketsPorPersonal: {
                        diario: 35,
                        semanal: 187,
                        mensual: 781,
                        objetivos: {
                            diario: 40,
                            semanal: 200,
                            mensual: 800
                        }
                    },
                    tendencias: {
                        diario: [32, 35, 38, 42, 45, 48, 52, 55, 58, 62, 65, 68, 72, 75, 78, 82, 85, 88,
                            92, 95, 98, 102, 105, 108, 112, 115, 118, 122, 125, 128
                        ],
                        mensual: [2800, 2850, 2900, 2950, 3000, 3100, 3150, 3200, 3241, 3300, 3350,
                            3400
                        ]
                    }
                },

                // Referencias a los charts
                charts: {},

                // Inicialización
                init() {
                    this.$nextTick(() => {
                        this.initCharts();

                        // Observar cambios en el sidebar para hacer resize
                        const observer = new MutationObserver(() => {
                            setTimeout(() => {
                                Object.values(this.charts).forEach(chart =>
                                    chart?.resize());
                            }, 100);
                        });

                        const sidebar = document.querySelector('.sidebar') || document
                            .querySelector('.navbar');
                        if (sidebar) {
                            observer.observe(sidebar, {
                                attributes: true,
                                attributeFilter: ['class', 'style']
                            });
                        }
                    });
                },

                initCharts() {
                    const isDark = document.querySelector('html').classList.contains('dark');

                    // Tendencia Chart - configurado UNA SOLA VEZ
                    this.charts.tendencia = echarts.init(this.$refs.tendenciaChart);
                    this.charts.tendencia.setOption({
                        tooltip: {
                            trigger: 'axis'
                        },
                        grid: {
                            left: '3%',
                            right: '4%',
                            bottom: '3%',
                            containLabel: true
                        },
                        xAxis: {
                            type: 'category',
                            data: this.periodoTickets === 'dia' ?
                                Array.from({
                                    length: 30
                                }, (_, i) => `Día ${i + 1}`) : ['Ene', 'Feb', 'Mar', 'Abr',
                                    'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'
                                ],
                            axisLabel: {
                                color: isDark ? '#bfc9d4' : '#506690'
                            }
                        },
                        yAxis: {
                            type: 'value',
                            axisLabel: {
                                color: isDark ? '#bfc9d4' : '#506690'
                            },
                            splitLine: {
                                lineStyle: {
                                    color: isDark ? '#191e3a' : '#e0e6ed'
                                }
                            }
                        },
                        series: [{
                            name: 'Tickets',
                            type: 'line',
                            data: this.periodoTickets === 'dia' ? this.mockData
                                .tendencias.diario : this.mockData.tendencias.mensual,
                            smooth: true,
                            lineStyle: {
                                width: 3,
                                color: '#4361ee'
                            },
                            areaStyle: {
                                color: isDark ? '#4361ee20' : '#4361ee10'
                            },
                            symbol: 'circle',
                            symbolSize: 8
                        }]
                    });

                    // Distritos Chart
                    this.charts.distritos = echarts.init(this.$refs.distritosChart);
                    this.charts.distritos.setOption({
                        tooltip: {
                            trigger: 'axis',
                            axisPointer: {
                                type: 'shadow'
                            },
                            formatter: (params) => {
                                const item = this.mockData.ticketsPorDistrito[params[0]
                                    .dataIndex];
                                return `
                <div class="font-semibold">${item.distrito}</div>
                <div class="text-xs">Provincia: ${item.provincia}</div>
                <div class="flex justify-between mt-1">
                    <span>Tickets:</span>
                    <span class="font-bold">${item.cantidad}</span>
                </div>
                <div class="flex justify-between">
                    <span>Variación:</span>
                    <span class="${item.variacion >= 0 ? 'text-success' : 'text-danger'}">
                        ${item.variacion >= 0 ? '+' : ''}${item.variacion}%
                    </span>
                </div>
            `;
                            }
                        },
                        grid: {
                            left: '3%',
                            right: '4%',
                            bottom: '3%',
                            containLabel: true
                        },
                        xAxis: {
                            type: 'value',
                            axisLabel: {
                                color: isDark ? '#bfc9d4' : '#506690'
                            },
                            splitLine: {
                                lineStyle: {
                                    color: isDark ? '#191e3a' : '#e0e6ed'
                                }
                            }
                        },
                        yAxis: {
                            type: 'category',
                            data: this.mockData.ticketsPorDistrito.map(item => item.distrito),
                            axisLabel: {
                                color: isDark ? '#bfc9d4' : '#506690'
                            },
                            axisLine: {
                                lineStyle: {
                                    color: isDark ? '#3b3f5c' : '#e0e6ed'
                                }
                            }
                        },
                        series: [{
                            name: 'Tickets',
                            type: 'bar',
                            data: this.mockData.ticketsPorDistrito.map(item => item
                                .cantidad),
                            itemStyle: {
                                color: (params) => {
                                    const item = this.mockData.ticketsPorDistrito[
                                        params.dataIndex];
                                    return item.variacion >= 0 ? '#10b981' :
                                        '#e7515a';
                                },
                                borderRadius: [0, 8, 8, 0]
                            },
                            barWidth: 20,
                            label: {
                                show: true,
                                position: 'right',
                                formatter: (params) => {
                                    const item = this.mockData.ticketsPorDistrito[
                                        params.dataIndex];
                                    return `${item.cantidad} (${item.variacion >= 0 ? '+' : ''}${item.variacion}%)`;
                                },
                                color: isDark ? '#bfc9d4' : '#506690',
                                fontSize: 11
                            }
                        }]
                    });

                    // Fallas Chart
                    this.charts.fallas = echarts.init(this.$refs.fallasChart);
                    this.charts.fallas.setOption({
                        tooltip: {
                            trigger: 'item',
                            formatter: (params) => {
                                return `${params.name}: ${params.value} tickets (${params.percent}%)`;
                            }
                        },
                        legend: {
                            orient: 'vertical',
                            left: 'left',
                            textStyle: {
                                color: isDark ? '#bfc9d4' : '#506690'
                            }
                        },
                        series: [{
                            name: 'Tickets por Falla',
                            type: 'pie',
                            radius: ['40%', '70%'],
                            center: ['50%', '50%'],
                            avoidLabelOverlap: false,
                            itemStyle: {
                                borderRadius: 10,
                                borderColor: isDark ? '#1b2e4b' : '#fff',
                                borderWidth: 2
                            },
                            label: {
                                show: true,
                                position: 'outside',
                                formatter: '{b}: {d}%',
                                color: isDark ? '#bfc9d4' : '#506690'
                            },
                            emphasis: {
                                label: {
                                    show: true,
                                    fontSize: 14,
                                    fontWeight: 'bold'
                                }
                            },
                            data: this.mockData.ticketsPorFalla.map(item => ({
                                name: item.falla,
                                value: item.cantidad,
                                itemStyle: {
                                    color: item.color
                                }
                            }))
                        }]
                    });

                    // Estados Chart
                    this.charts.estados = echarts.init(this.$refs.estadosChart);
                    this.charts.estados.setOption({
                        tooltip: {
                            trigger: 'axis',
                            axisPointer: {
                                type: 'shadow'
                            },
                            formatter: (params) => {
                                const item = this.mockData.ticketsPorEstado[params[0]
                                    .dataIndex];
                                return `
                <div class="font-semibold">${item.estado}</div>
                <div class="flex justify-between mt-1">
                    <span>Tickets:</span>
                    <span class="font-bold">${item.cantidad}</span>
                </div>
                <div class="flex justify-between">
                    <span>Porcentaje:</span>
                    <span>${Math.round(item.cantidad / this.mockData.ticketsPorPeriodo.mes * 100)}%</span>
                </div>
            `;
                            }
                        },
                        grid: {
                            left: '3%',
                            right: '4%',
                            bottom: '3%',
                            containLabel: true
                        },
                        xAxis: {
                            type: 'category',
                            data: this.mockData.ticketsPorEstado.map(item => item.estado),
                            axisLabel: {
                                color: isDark ? '#bfc9d4' : '#506690',
                                rotate: 15,
                                fontSize: 11
                            },
                            axisLine: {
                                lineStyle: {
                                    color: isDark ? '#3b3f5c' : '#e0e6ed'
                                }
                            }
                        },
                        yAxis: {
                            type: 'value',
                            axisLabel: {
                                color: isDark ? '#bfc9d4' : '#506690'
                            },
                            splitLine: {
                                lineStyle: {
                                    color: isDark ? '#191e3a' : '#e0e6ed'
                                }
                            }
                        },
                        series: [{
                            name: 'Tickets',
                            type: 'bar',
                            data: this.mockData.ticketsPorEstado.map(item => item
                                .cantidad),
                            itemStyle: {
                                color: (params) => this.mockData.ticketsPorEstado[params
                                    .dataIndex].color,
                                borderRadius: [8, 8, 0, 0]
                            },
                            barWidth: 40,
                            label: {
                                show: true,
                                position: 'top',
                                color: isDark ? '#bfc9d4' : '#506690',
                                fontSize: 11,
                                formatter: (params) => params.value
                            }
                        }]
                    });

                    // Técnicos Chart
                    this.charts.tecnicos = echarts.init(this.$refs.tecnicosChart);
                    this.charts.tecnicos.setOption({
                        tooltip: {
                            trigger: 'axis',
                            axisPointer: {
                                type: 'shadow'
                            },
                            formatter: (params) => {
                                const item = this.mockData.ticketsPorTecnico[this
                                    .periodoTecnicos][params[0].dataIndex];
                                return `
                <div class="font-semibold">${item.tecnico}</div>
                <div class="flex justify-between mt-1">
                    <span>Tickets:</span>
                    <span class="font-bold">${item.tickets}</span>
                </div>
                <div class="flex justify-between">
                    <span>Eficiencia:</span>
                    <span class="${item.eficiencia >= 90 ? 'text-success' : 'text-warning'}">${item.eficiencia}%</span>
                </div>
                <div class="flex justify-between">
                    <span>Reincidencias:</span>
                    <span class="text-danger">${item.reincidencias}</span>
                </div>
            `;
                            }
                        },
                        grid: {
                            left: '3%',
                            right: '4%',
                            bottom: '3%',
                            containLabel: true
                        },
                        xAxis: {
                            type: 'category',
                            data: this.mockData.ticketsPorTecnico[this.periodoTecnicos].map(
                                item => item.tecnico.split(' ')[0]),
                            axisLabel: {
                                color: isDark ? '#bfc9d4' : '#506690',
                                rotate: 15
                            },
                            axisLine: {
                                lineStyle: {
                                    color: isDark ? '#3b3f5c' : '#e0e6ed'
                                }
                            }
                        },
                        yAxis: {
                            type: 'value',
                            axisLabel: {
                                color: isDark ? '#bfc9d4' : '#506690'
                            },
                            splitLine: {
                                lineStyle: {
                                    color: isDark ? '#191e3a' : '#e0e6ed'
                                }
                            }
                        },
                        series: [{
                            name: 'Tickets',
                            type: 'bar',
                            data: this.mockData.ticketsPorTecnico[this.periodoTecnicos]
                                .map(item => item.tickets),
                            itemStyle: {
                                color: '#e2a03f',
                                borderRadius: [8, 8, 0, 0]
                            },
                            barWidth: 30,
                            label: {
                                show: true,
                                position: 'top',
                                color: isDark ? '#bfc9d4' : '#506690',
                                fontSize: 11,
                                formatter: (params) => params.value
                            }
                        }]
                    });

                    // Resize handler
                    window.addEventListener('resize', () => {
                        Object.values(this.charts).forEach(chart => chart?.resize());
                    });
                },

                // ✅ NUEVA FUNCIÓN AGREGADA AQUÍ
                reinicializarDistritosChart() {
                    if (this.vistaDistritos === 'grafico' && this.$refs.distritosChart) {
                        // Destruir el chart anterior si existe
                        if (this.charts.distritos) {
                            this.charts.distritos.dispose();
                        }

                        setTimeout(() => {
                            // Crear nuevo chart
                            this.charts.distritos = echarts.init(this.$refs.distritosChart);

                            const isDark = document.querySelector('html').classList.contains(
                                'dark');

                            this.charts.distritos.setOption({
                                tooltip: {
                                    trigger: 'axis',
                                    axisPointer: {
                                        type: 'shadow'
                                    },
                                    formatter: (params) => {
                                        const item = this.mockData
                                            .ticketsPorDistrito[params[0]
                                            .dataIndex];
                                        return `
                                        <div class="font-semibold">${item.distrito}</div>
                                        <div class="text-xs">Provincia: ${item.provincia}</div>
                                        <div class="flex justify-between mt-1">
                                            <span>Tickets:</span>
                                            <span class="font-bold">${item.cantidad}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Variación:</span>
                                            <span class="${item.variacion >= 0 ? 'text-success' : 'text-danger'}">
                                                ${item.variacion >= 0 ? '+' : ''}${item.variacion}%
                                            </span>
                                        </div>
                                    `;
                                    }
                                },
                                grid: {
                                    left: '3%',
                                    right: '4%',
                                    bottom: '3%',
                                    containLabel: true
                                },
                                xAxis: {
                                    type: 'value',
                                    axisLabel: {
                                        color: isDark ? '#bfc9d4' : '#506690'
                                    },
                                    splitLine: {
                                        lineStyle: {
                                            color: isDark ? '#191e3a' : '#e0e6ed'
                                        }
                                    }
                                },
                                yAxis: {
                                    type: 'category',
                                    data: this.mockData.ticketsPorDistrito.map(item =>
                                        item.distrito),
                                    axisLabel: {
                                        color: isDark ? '#bfc9d4' : '#506690'
                                    },
                                    axisLine: {
                                        lineStyle: {
                                            color: isDark ? '#3b3f5c' : '#e0e6ed'
                                        }
                                    }
                                },
                                series: [{
                                    name: 'Tickets',
                                    type: 'bar',
                                    data: this.mockData.ticketsPorDistrito.map(
                                        item => item.cantidad),
                                    itemStyle: {
                                        color: (params) => {
                                            const item = this.mockData
                                                .ticketsPorDistrito[params
                                                    .dataIndex];
                                            return item.variacion >= 0 ?
                                                '#10b981' : '#e7515a';
                                        },
                                        borderRadius: [0, 8, 8, 0]
                                    },
                                    barWidth: 20,
                                    label: {
                                        show: true,
                                        position: 'right',
                                        formatter: (params) => {
                                            const item = this.mockData
                                                .ticketsPorDistrito[params
                                                    .dataIndex];
                                            return `${item.cantidad} (${item.variacion >= 0 ? '+' : ''}${item.variacion}%)`;
                                        },
                                        color: isDark ? '#bfc9d4' : '#506690',
                                        fontSize: 11
                                    }
                                }]
                            });
                        }, 100);
                    }
                },
            }));
        });
    </script>

</x-layout.default>
