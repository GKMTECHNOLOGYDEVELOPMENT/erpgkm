<x-layout.default>
  @php
      $fechaFormateada = \Carbon\Carbon::parse($kardex->fecha)->format('d/m/Y');
      $meses = [
          1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
          5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
          9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
      ];
      $nombreMes = $meses[$month] ?? 'Mes';
      
      // Obtener el mes y año actual para el contador de registros
      $mesActual = now()->month;
      $anoActual = now()->year;
      
      // Contar registros de entrada (compra o entrada_proveedor) de este mes
      $totalRegistrosEntradaMes = $movimientos->filter(function($movimiento) use ($mesActual, $anoActual) {
          $fechaMovimiento = \Carbon\Carbon::parse($movimiento->created_at);
          return ($movimiento->tipo_ingreso == 'compra' || $movimiento->tipo_ingreso == 'entrada_proveedor') &&
                 $fechaMovimiento->month == $mesActual &&
                 $fechaMovimiento->year == $anoActual;
      })->count();
  @endphp

  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50/30">
    <div class="container mx-auto px-4 py-8">
      <!-- Header -->
      <div class="mb-8">
        <div class="flex items-center gap-3 mb-4">
          <div class="p-2 bg-primary rounded-xl">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
          </div>
          <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
            MOVIMIENTOS DEL MES - {{ $nombreMes }} {{ $year }}
          </h1>
        </div>

        <p class="text-gray-600 text-lg max-w-3xl leading-relaxed">
          Todos los movimientos del mes que generaron el kardex para 
          <span class="font-semibold text-indigo-600">{{ $articulo->nombre }}</span>
          del cliente <span class="font-semibold text-indigo-600">{{ $cliente->descripcion ?? 'Cliente' }}</span>
          <br>
          <span class="text-sm text-gray-500">(Registro del kardex: {{ $fechaFormateada }})</span>
        </p>
      </div>

      <!-- Resumen del Kardex -->
      <div class="panel shadow-xl rounded-3xl p-6 mb-8 border border-white/20 bg-white">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div class="bg-blue-50 rounded-xl p-4">
            <div class="text-sm text-blue-600 font-semibold">Entradas Totales</div>
            <div class="text-2xl font-bold text-blue-800">{{ $kardex->unidades_entrada }}</div>
          </div>
          <div class="bg-red-50 rounded-xl p-4">
            <div class="text-sm text-red-600 font-semibold">Salidas Totales</div>
            <div class="text-2xl font-bold text-red-800">{{ $kardex->unidades_salida }}</div>
          </div>
          <div class="bg-green-50 rounded-xl p-4">
            <div class="text-sm text-green-600 font-semibold">Inventario Inicial</div>
            <div class="text-2xl font-bold text-green-800">{{ $kardex->inventario_inicial }}</div>
          </div>
          <div class="bg-purple-50 rounded-xl p-4">
            <div class="text-sm text-purple-600 font-semibold">Inventario Final</div>
            <div class="text-2xl font-bold text-purple-800">{{ $kardex->inventario_actual }}</div>
          </div>
        </div>
        
        <!-- Resumen de movimientos del mes - SOLO ENTRADAS -->
        <div class="mt-6">
          <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-200">
            <div class="flex items-center gap-2 mb-2">
              <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
              </svg>
              <div class="text-sm text-emerald-600 font-semibold">Registros de Entrada este Mes</div>
            </div>
            <div class="text-2xl font-bold text-emerald-800">{{ $totalRegistrosEntradaMes }}</div>
            <div class="text-xs text-emerald-500 mt-1">Compras y entradas de proveedor</div>
          </div>
        </div>
      </div>

      <!-- Tabla de movimientos -->
      <div class="panel backdrop-blur-sm rounded-3xl shadow-xl overflow-hidden border border-white/20">
        <div class="bg-gradient-to-r from-slate-800 to-slate-700 px-6 py-5">
          <div class="flex items-center gap-3">
            <div class="p-2 bg-white/10 rounded-xl">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <h2 class="text-xl font-bold text-white">Movimientos del Mes ({{ $movimientos->count() }})</h2>
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full">
            <thead class="bg-gradient-to-r from-gray-50 to-slate-50">
              <tr>
                <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tipo</th>
                <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Fecha/Hora</th>
                <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Cantidad</th>
                <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Origen</th>
                <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Día</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              @forelse($movimientos as $movimiento)
              @php
                  $fechaMovimiento = \Carbon\Carbon::parse($movimiento->created_at);
                  $esMismoDia = $fechaMovimiento->format('Y-m-d') === \Carbon\Carbon::parse($kardex->fecha)->format('Y-m-d');
              @endphp
              <tr class="hover:bg-gray-50 transition-all duration-200 {{ $esMismoDia ? 'bg-yellow-50' : '' }}">
                <td class="px-4 py-4">
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                    {{ $movimiento->tipo_ingreso == 'compra' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                    {{ strtoupper($movimiento->tipo_ingreso) }}
                  </span>
                </td>
                <td class="px-4 py-4">
                  {{ $fechaMovimiento->format('d/m/Y H:i:s') }}
                  @if($esMismoDia)
                  <span class="ml-2 text-xs text-yellow-600 font-semibold">(Día del corte)</span>
                  @endif
                </td>
                <td class="px-4 py-4 font-semibold">
                  {{ $movimiento->cantidad }}
                </td>
                <td class="px-4 py-4">
                  ID: {{ $movimiento->ingreso_id }}
                  @if($movimiento->compra_id)
                  <br><span class="text-xs text-gray-500">Compra: {{ $movimiento->compra_id }}</span>
                  @endif
                </td>
                <td class="px-4 py-4">
                  {{ $fechaMovimiento->format('d') }}
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                  No se encontraron movimientos para este mes
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- Botón de volver -->
      <div class="mt-6">
        <a href="#" 
           class="btn btn-primary">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
          </svg>
          Volver al Kardex
        </a>
      </div>
    </div>
  </div>
</x-layout.default>