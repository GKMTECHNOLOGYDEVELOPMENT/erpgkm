<x-layout.default>
    <div class="max-w-7xl mx-auto p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Solicitudes de Asistencia</h1>
                <p class="text-sm text-gray-500">Gestiona tus solicitudes por tipo y estado.</p>
            </div>

            <a href="{{ route('administracion.solicitud-asistencia.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700">
                + Nueva Solicitud
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <form class="bg-white rounded-xl border border-gray-200 p-4 mb-5">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                    <label class="text-sm font-semibold text-gray-700">Tipo</label>
                    <select name="tipo" class="mt-1 w-full rounded-lg border-gray-300">
                        <option value="">Todos</option>
                        @foreach($tipos as $t)
                            <option value="{{ $t->id_tipo_solicitud }}" @selected(request('tipo') == $t->id_tipo_solicitud)>
                                {{ $t->nombre_tip }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700">Estado</label>
                    <select name="estado" class="mt-1 w-full rounded-lg border-gray-300">
                        <option value="">Todos</option>
                        @foreach(['pendiente','aprobado','denegado'] as $st)
                            <option value="{{ $st }}" @selected(request('estado') === $st)>{{ strtoupper($st) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button class="px-4 py-2 rounded-lg bg-gray-900 text-white font-semibold hover:bg-black">
                        Filtrar
                    </button>
                    <a href="{{ route('administracion.solicitud-asistencia.index') }}"
                       class="px-4 py-2 rounded-lg bg-gray-100 text-gray-800 font-semibold hover:bg-gray-200">
                        Limpiar
                    </a>
                </div>
            </div>
        </form>

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr class="text-left text-gray-600">
                            <th class="p-4 font-semibold">#</th>
                            <th class="p-4 font-semibold">Tipo</th>
                            <th class="p-4 font-semibold">Rango</th>
                            <th class="p-4 font-semibold">Estado</th>
                            <th class="p-4 font-semibold">Solicitado</th>
                            <th class="p-4 font-semibold text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($solicitudes as $s)
                            <tr class="hover:bg-gray-50">
                                <td class="p-4 text-gray-700">{{ $s->id_solicitud_asistencia }}</td>
                                <td class="p-4">
                                    <div class="font-semibold text-gray-900">{{ $s->tipoSolicitud?->nombre_tip }}</div>
                                    @if($s->tipoEducacion)
                                        <div class="text-xs text-gray-500">Educación: {{ $s->tipoEducacion->nombre }}</div>
                                    @endif
                                </td>
                                <td class="p-4 text-gray-700">
                                    {{ optional($s->rango_inicio_tiempo)->format('d/m/Y') }}
                                    -
                                    {{ optional($s->rango_final_tiempo)->format('d/m/Y') }}
                                </td>
                                <td class="p-4">
                                    @php
                                        $badge = match($s->estado) {
                                            'aprobado' => 'bg-green-100 text-green-800 border-green-200',
                                            'denegado' => 'bg-red-100 text-red-800 border-red-200',
                                            default => 'bg-yellow-100 text-yellow-800 border-yellow-200'
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full border {{ $badge }} text-xs font-bold uppercase">
                                        {{ $s->estado }}
                                    </span>
                                </td>
                                <td class="p-4 text-gray-700">
                                    {{ optional($s->fecha_solicitud)->format('d/m/Y H:i') }}
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        {{-- Botón Ver Detalles --}}
                                        <a href="{{ route('administracion.solicitud-asistencia.show', $s->id_solicitud_asistencia) }}"
                                           class="inline-flex items-center px-3 py-1.5 rounded-lg bg-gray-50 text-gray-700 font-semibold hover:bg-gray-100 transition-colors">
                                            Ver
                                        </a>
                                        
                                        {{-- Botón Editar --}}
                                        <a href="{{ route('administracion.solicitud-asistencia.edit', $s->id_solicitud_asistencia) }}"
                                           class="inline-flex items-center px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 font-semibold hover:bg-blue-100 transition-colors">
                                            Editar
                                        </a>
                                        
                                        {{-- Botón Cambiar Estado (solo si está pendiente) --}}
                                        @if($s->estado == 'pendiente')
                                        <button type="button"
                                                onclick="mostrarModalEstado({{ $s->id_solicitud_asistencia }})"
                                                class="inline-flex items-center px-3 py-1.5 rounded-lg bg-yellow-50 text-yellow-700 font-semibold hover:bg-yellow-100 transition-colors">
                                            Evaluar
                                        </button>
                                        @endif
                                        
                                        {{-- Botón Eliminar --}}
                                        <form action="{{ route('administracion.solicitud-asistencia.destroy', $s->id_solicitud_asistencia) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('¿Estás seguro de eliminar esta solicitud? Esta acción no se puede deshacer.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center px-3 py-1.5 rounded-lg bg-red-50 text-red-700 font-semibold hover:bg-red-100 transition-colors">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-6 text-center text-gray-500">
                                    No hay solicitudes registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-200">
                {{ $solicitudes->links() }}
            </div>
        </div>
    </div>

    {{-- Modal para cambiar estado --}}
    <div id="modalEstado" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-bold mb-4">Cambiar Estado de la Solicitud</h3>
            
            <form id="formEstado" method="POST">
                @csrf
                <input type="hidden" name="solicitud_id" id="solicitud_id">
                
                <div class="space-y-4">
                    <div>
                        <label class="font-semibold text-sm mb-2 block">Nuevo Estado</label>
                        <div class="flex gap-3">
                            <label class="flex items-center">
                                <input type="radio" name="estado" value="aprobado" class="mr-2" required>
                                <span class="px-3 py-1.5 rounded-lg bg-green-50 text-green-700 font-semibold">Aprobar</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="estado" value="denegado" class="mr-2" required>
                                <span class="px-3 py-1.5 rounded-lg bg-red-50 text-red-700 font-semibold">Denegar</span>
                            </label>
                        </div>
                    </div>
                    
                    <div>
                        <label class="font-semibold text-sm mb-2 block">Comentario (opcional)</label>
                        <textarea name="comentario" 
                                  rows="3" 
                                  class="w-full border rounded p-2"
                                  placeholder="Escribe un comentario sobre la decisión..."></textarea>
                    </div>
                </div>
                
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" 
                            onclick="cerrarModalEstado()"
                            class="px-4 py-2 bg-gray-100 rounded hover:bg-gray-200">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Confirmar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function mostrarModalEstado(solicitudId) {
            document.getElementById('solicitud_id').value = solicitudId;
            document.getElementById('modalEstado').classList.remove('hidden');
            
            // Configurar el action del form
            const form = document.getElementById('formEstado');
            form.action = `/administracion/solicitud-asistencia/${solicitudId}/cambiar-estado`;
        }
        
        function cerrarModalEstado() {
            document.getElementById('modalEstado').classList.add('hidden');
            document.getElementById('formEstado').reset();
        }
        
        // Cerrar modal al hacer clic fuera
        document.getElementById('modalEstado').addEventListener('click', function(e) {
            if (e.target.id === 'modalEstado') {
                cerrarModalEstado();
            }
        });
        
        // Cerrar con ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                cerrarModalEstado();
            }
        });
    </script>
</x-layout.default>