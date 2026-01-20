<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <div class="flex items-center justify-center">
                            <span>#</span>
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <div class="flex items-center justify-center">
                            Código Repuesto
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <div class="flex items-center justify-center">
                            Modelos
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <div class="flex items-center justify-center">
                            Subcategoría
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <div class="flex items-center justify-center">
                            Total Retirado
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <div class="flex items-center justify-center">
                            Acciones
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($repuestos as $index => $repuesto)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-medium flex items-center justify-center">
                                {{ ($repuestos->currentPage() - 1) * $repuestos->perPage() + $index + 1 }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $repuesto->codigo_repuesto }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                // Obtener todos los modelos únicos
                                $modelos = collect();
                                
                                // Agregar modelo principal si existe
                                if ($repuesto->articulo && $repuesto->articulo->modeloPrincipal) {
                                    $modelos->push($repuesto->articulo->modeloPrincipal);
                                }
                                
                                // Agregar modelos de la relación muchos a muchos
                                if ($repuesto->articulo && $repuesto->articulo->modelos) {
                                    $modelos = $modelos->merge($repuesto->articulo->modelos);
                                }
                                
                                $modelosUnicos = $modelos->unique('idModelo');
                            @endphp
                            
                            @if($modelosUnicos->isNotEmpty())
                                <div class="flex flex-wrap gap-1 justify-center">
                                    @foreach($modelosUnicos as $modelo)
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ $modelo->nombre }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex items-center justify-center">
                                    <span class="text-sm text-gray-400">Sin modelos asignados</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    {{ $repuesto->articulo->subcategoria->nombre ?? 'Sin categoría' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <div class="text-center">
                                    <div class="text-lg font-bold text-amber-600">
                                        {{ $repuesto->total_retirado }}
                                    </div>
                                    <div class="text-xs text-gray-500">unidades</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <button onclick="verDetalle({{ $repuesto->id_articulo }})"
                                        class="btn btn-primary btn-sm">
                                    Detalles
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12">
                            <div class="flex flex-col items-center justify-center text-center">
                                <div class="w-16 h-16 mb-4">
                                    <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-500 mb-2">No hay repuestos registrados</h3>
                                <p class="text-gray-400">No se encontraron repuestos de harvest con los filtros aplicados</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($repuestos->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Mostrando 
                <span class="font-medium">{{ $repuestos->firstItem() }}</span>
                a 
                <span class="font-medium">{{ $repuestos->lastItem() }}</span>
                de 
                <span class="font-medium">{{ $repuestos->total() }}</span>
                resultados
            </div>
            <div class="flex space-x-2">
                {{ $repuestos->links() }}
            </div>
        </div>
    </div>
    @endif
</div>