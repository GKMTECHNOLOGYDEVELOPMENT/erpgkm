<x-layout.default>
  @php
      $mostrarPrecios = ($cliente_id == 8); // SOLO cliente ID 8 ve precios
  @endphp

  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50/30">
    <div class="container mx-auto px-4 py-8">
      <!-- Header mejorado -->
      <div class="mb-8">
        <div class="flex items-center gap-3 mb-4">
          <div class="p-2 bg-primary rounded-xl">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
              </path>
            </svg>
          </div>
          <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
            KARDEX POR PRODUCTO - {{ $cliente->descripcion ?? 'Cliente' }}
            @if($mostrarPrecios)
            <span class="text-sm font-normal text-green-600 block mt-1">(Modo con precios)</span>
            @else
            <span class="text-sm font-normal text-gray-500 block mt-1">(Modo sin precios)</span>
            @endif
          </h1>
        </div>

        <p class="text-gray-600 text-lg max-w-3xl leading-relaxed">
          Consulta los <span class="font-semibold text-indigo-600">movimientos</span> y
          @if($mostrarPrecios)
          <span class="font-semibold text-indigo-600">costos</span> de entradas y salidas de productos
          @else
          <span class="font-semibold text-indigo-600">cantidades</span> de entradas y salidas de productos
          @endif
          para el cliente seleccionado.
        </p>
      </div>

      <!-- Card del producto rediseñada -->
      <div class="panel shadow-xl rounded-3xl p-6 mb-8 border border-white/20">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
          <!-- Info principal del producto -->
          <div class="flex-1">
            <div class="flex items-center gap-3 mb-4">
              <!-- Icono principal -->
              <div class="w-12 h-12 bg-primary rounded-2xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                  </path>
                </svg>
              </div>
              <!-- Texto y estado -->
              <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $articulo->nombre }}</h2>
                <div class="flex items-center gap-2 mt-1">
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $articulo->estado ? 'bg-success text-white' : 'bg-danger text-white' }}">
                    <span class="w-2 h-2 bg-white rounded-full mr-2 {{ $articulo->estado ? 'animate-ping' : '' }}"></span>
                    {{ $articulo->estado ? 'Activo' : 'Inactivo' }}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Métricas del producto -->
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4 lg:gap-6">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-4 border border-blue-100">
              <div class="flex items-center gap-2 mb-2">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                </svg>
                <span class="text-xs font-medium text-blue-700 uppercase tracking-wide">Código</span>
              </div>
              <p class="text-lg font-bold text-blue-900">{{ $articulo->codigo_barras }}</p>
            </div>

            <div class="bg-gradient-to-r from-emerald-50 to-green-50 rounded-2xl p-4 border border-emerald-100">
              <div class="flex items-center gap-2 mb-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z">
                  </path>
                </svg>
                <span class="text-xs font-medium text-emerald-700 uppercase tracking-wide">Stock Actual</span>
              </div>
              <p class="text-lg font-bold text-emerald-900">{{ $articulo->stock_total }}</p>
            </div>

            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-4 border border-purple-100">
              <div class="flex items-center gap-2 mb-2">
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="text-xs font-medium text-purple-700 uppercase tracking-wide">Cliente</span>
              </div>
              <p class="text-lg font-bold text-purple-900">{{ $cliente->descripcion ?? 'N/A' }}</p>
            </div>

            <div class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-2xl p-4 border border-orange-100">
              <div class="flex items-center gap-2 mb-2">
                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 01118 0z"></path>
                </svg>
                <span class="text-xs font-medium text-orange-700 uppercase tracking-wide">SKU</span>
              </div>
              <p class="text-lg font-bold text-orange-900">{{ $articulo->sku ?? 'N/A' }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabla mejorada -->
      <div class="panel backdrop-blur-sm rounded-3xl shadow-xl overflow-hidden border border-white/20">
        <!-- Header de la tabla -->
        <div class="bg-gradient-to-r from-slate-800 to-slate-700 px-6 py-5">
          <div class="flex items-center gap-3">
            <div class="p-2 bg-white/10 rounded-xl">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                </path>
              </svg>
            </div>
            <h2 class="text-xl font-bold text-white">Movimientos del Kardex</h2>
            @if($mostrarPrecios)
            <span class="text-sm text-green-300 ml-2">(Con información de precios)</span>
            @else
            <span class="text-sm text-gray-300 ml-2">(Solo cantidades)</span>
            @endif
          </div>
        </div>

        <!-- Tabla responsive mejorada -->
        <div class="overflow-x-auto">
          <table class="min-w-full">
            <thead class="bg-gradient-to-r from-gray-50 to-slate-50">
              <tr>
                <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                  <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                      </path>
                    </svg>
                    Fecha
                  </div>
                </th>
                <th class="px-4 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-gray-200 bg-green-50">
                  <div class="text-green-700">Entrada</div>
                  <div class="grid {{ $mostrarPrecios ? 'grid-cols-2' : 'grid-cols-1' }} gap-1 mt-1 text-[10px]">
                    <span>Unidades</span>
                    @if($mostrarPrecios)
                    <span>C.U.</span>
                    @endif
                  </div>
                </th>
                <th class="px-4 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-gray-200 bg-red-50">
                  <div class="text-red-700">Salida</div>
                  <div class="grid {{ $mostrarPrecios ? 'grid-cols-2' : 'grid-cols-1' }} gap-1 mt-1 text-[10px]">
                    <span>Unidades</span>
                    @if($mostrarPrecios)
                    <span>C.U.</span>
                    @endif
                  </div>
                </th>
                <th class="px-4 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-gray-200 bg-blue-50">
                  <div class="text-blue-700">Inventario</div>
                  <div class="grid {{ $mostrarPrecios ? 'grid-cols-3' : 'grid-cols-2' }} gap-1 mt-1 text-[10px]">
                    <span>Inicial</span>
                    <span>Actual</span>
                    @if($mostrarPrecios)
                    <span>Costo</span>
                    @endif
                  </div>
                </th>

                <th class="px-4 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-gray-200 bg-gray-50">
    Acciones
</th>
               
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              @forelse($movimientos as $movimiento)
              <tr class="hover:bg-gradient-to-r hover:from-blue-50/50 hover:to-indigo-50/50 transition-all duration-200 bg-gray-50/30">
                <td class="px-4 py-4 whitespace-nowrap">
                  <div class="flex items-center gap-2">
                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                    <span class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($movimiento->fecha)->format('d/m/Y') }}</span>
                  </div>
                </td>
                
                <!-- COLUMNA ENTRADA -->
                <td class="px-4 py-4 bg-green-50/50">
                  <div class="grid {{ $mostrarPrecios ? 'grid-cols-2' : 'grid-cols-1' }} gap-2 text-center">
                    <div class="bg-green-100 rounded-lg py-1 px-2">
                      <span class="text-sm font-bold text-green-800">{{ $movimiento->unidades_entrada ?? 0 }}</span>
                    </div>
                    @if($mostrarPrecios)
                    <div class="bg-green-100 rounded-lg py-1 px-2">
                      <span class="text-sm font-bold text-green-800">S/ {{ number_format($movimiento->costo_unitario_entrada ?? 0, 2) }}</span>
                    </div>
                    @endif
                  </div>
                </td>
                
                <!-- COLUMNA SALIDA -->
                <td class="px-4 py-4 bg-red-50/50">
                  <div class="grid {{ $mostrarPrecios ? 'grid-cols-2' : 'grid-cols-1' }} gap-2 text-center">
                    <div class="bg-red-100 rounded-lg py-1 px-2">
                      <span class="text-sm font-bold text-red-800">{{ $movimiento->unidades_salida ?? 0 }}</span>
                    </div>
                    @if($mostrarPrecios)
                    <div class="bg-red-100 rounded-lg py-1 px-2">
                      <span class="text-sm font-bold text-red-800">S/ {{ number_format($movimiento->costo_unitario_salida ?? 0, 2) }}</span>
                    </div>
                    @endif
                  </div>
                </td>
                
                <!-- COLUMNA INVENTARIO -->
                <td class="px-4 py-4 bg-blue-50/50">
                  <div class="grid {{ $mostrarPrecios ? 'grid-cols-3' : 'grid-cols-2' }} gap-2 text-center">
                    <div class="bg-blue-100 rounded-lg py-1 px-2">
                      <span class="text-sm font-bold text-blue-800">{{ $movimiento->inventario_inicial ?? 0 }}</span>
                    </div>
                    <div class="bg-blue-100 rounded-lg py-1 px-2">
                      <span class="text-sm font-bold text-blue-800">{{ $movimiento->inventario_actual ?? 0 }}</span>
                    </div>
                    @if($mostrarPrecios)
                    <div class="bg-purple-100 rounded-lg py-1 px-2">
                      <span class="text-sm font-bold text-purple-800">S/ {{ number_format($movimiento->costo_inventario ?? 0, 2) }}</span>
                    </div>
                    @endif
                  </div>
                </td>

                <!-- Y en cada fila, agrega esto: -->
<td class="px-4 py-4 text-center">
    <div class="flex justify-center">
        <a href="{{ route('kardex.detalle-movimientos', $movimiento->id) }}" 
           class="btn btn-sm btn-primary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            Ver Movimientos
        </a>
    </div>
</td>

                
              </tr>
              @empty
              <tr>
                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                  <div class="flex flex-col items-center justify-center">
                    <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                      </path>
                    </svg>
                    <p class="text-lg font-medium">No hay movimientos registrados</p>
                    <p class="text-sm">No se encontraron movimientos para este producto y cliente</p>
                  </div>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Paginación -->
        @if($movimientos->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
          <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600">
              Mostrando {{ $movimientos->firstItem() }} - {{ $movimientos->lastItem() }} de {{ $movimientos->total() }} resultados
            </div>
            <div class="pagination-wrapper">
              {{ $movimientos->links() }}
            </div>
          </div>
        </div>
        @endif
      </div>

      <!-- Botón de volver -->
      <div class="mt-6">
        <a href="{{ url()->previous() }}" class="btn btn-primary">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
          </svg>
          Volver Atrás
        </a>
      </div>
    </div>
  </div>

  <style>
    .pagination-wrapper ul {
      display: flex;
      list-style: none;
      padding: 0;
      margin: 0;
    }
    
    .pagination-wrapper li {
      margin: 0 2px;
    }
    
    .pagination-wrapper a,
    .pagination-wrapper span {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-width: 32px;
      height: 32px;
      padding: 0 8px;
      border: 1px solid #e5e7eb;
      border-radius: 6px;
      font-size: 14px;
      font-weight: 500;
      text-decoration: none;
      transition: all 0.2s;
    }
    
    .pagination-wrapper a:hover {
      background-color: #f3f4f6;
    }
    
    .pagination-wrapper .active span {
      background-color: #3b82f6;
      color: white;
      border-color: #3b82f6;
    }
  </style>
</x-layout.default>