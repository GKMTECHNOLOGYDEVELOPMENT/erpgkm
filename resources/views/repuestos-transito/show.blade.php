<x-layout.default>
    <div x-data="detalleRepuesto()" x-init="init()">
        <!-- Header con Botones -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                    <i class="fas fa-info-circle mr-2"></i>Detalle del Repuesto en Tránsito
                </h4>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Información completa del repuesto en tránsito
                </p>
            </div>
            
            <div class="flex space-x-3">
                <a href="{{ route('repuesto-transito.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Volver
                </a>
                
                <!-- Menú de Acciones -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="btn btn-primary">
                        <i class="fas fa-cog mr-2"></i>Acciones
                        <i class="fas fa-chevron-down ml-2"></i>
                    </button>
                    
                    <div x-show="open" @click.away="open = false" 
                         x-transition class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg z-10">
                        <div class="py-1">
                            <button @click="marcarEstado('recibido')" 
                                    class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-check text-green-500 mr-2"></i>Marcar Recibido
                            </button>
                            <button @click="marcarEstado('usado')" 
                                    class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-wrench text-blue-500 mr-2"></i>Marcar Usado
                            </button>
                            <button @click="marcarEstado('perdido')" 
                                    class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>Reportar Pérdida
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertas de Estado -->
        <div class="mb-6">
            @php
                $dias = \Carbon\Carbon::parse($repuesto->fecha_salida)->diffInDays(now());
            @endphp
            
            @if($dias > 7)
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <strong>¡ALERTA CRÍTICA!</strong> Este repuesto lleva más de 7 días en tránsito. Se recomienda seguimiento inmediato.
            </div>
            @elseif($dias > 3)
            <div class="alert alert-warning">
                <i class="fas fa-clock mr-2"></i>
                <strong>Atención:</strong> Este repuesto lleva más de 3 días en tránsito.
            </div>
            @else
            <div class="alert alert-success">
                <i class="fas fa-check-circle mr-2"></i>
                El repuesto está dentro del tiempo normal de tránsito.
            </div>
            @endif
        </div>

        <!-- Grid de Información -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna 1: Información del Artículo -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Tarjeta de Artículo -->
                <div class="panel">
                    <div class="flex items-center justify-between mb-4">
                        <h5 class="font-semibold text-lg text-gray-800 dark:text-white">
                            <i class="fas fa-box mr-2"></i>Información del Artículo
                        </h5>
                        <span class="badge bg-primary">{{ $repuesto->codigo_articulo }}</span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nombre del Artículo</label>
                            <p class="mt-1 text-gray-800 dark:text-white">{{ $repuesto->nombre_articulo }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Cantidad</label>
                            <p class="mt-1">
                                <span class="badge bg-warning text-lg">{{ $repuesto->cantidad }}</span>
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Días en Tránsito</label>
                            <p class="mt-1">
                                @php
                                    $badgeClass = $dias > 7 ? 'bg-danger' : ($dias > 3 ? 'bg-warning' : 'bg-success');
                                @endphp
                                <span class="badge {{ $badgeClass }} text-lg">{{ $dias }} días</span>
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Fecha de Salida</label>
                            <p class="mt-1 text-gray-800 dark:text-white">
                                {{ \Carbon\Carbon::parse($repuesto->fecha_salida)->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        
                        @if($repuesto->observacion)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Observaciones</label>
                            <p class="mt-1 text-gray-800 dark:text-white p-3 bg-gray-50 dark:bg-gray-700 rounded">
                                {{ $repuesto->observacion }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Tarjeta de Solicitud -->
                <div class="panel">
                    <h5 class="font-semibold text-lg mb-4 text-gray-800 dark:text-white">
                        <i class="fas fa-file-alt mr-2"></i>Información de la Solicitud
                    </h5>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Código de Solicitud</label>
                            <p class="mt-1 text-gray-800 dark:text-white">{{ $repuesto->codigo_solicitud }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Número de Ticket</label>
                            <p class="mt-1 text-gray-800 dark:text-white">{{ $repuesto->numeroTicket }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Fecha Creación</label>
                            <p class="mt-1 text-gray-800 dark:text-white">
                                {{ \Carbon\Carbon::parse($repuesto->fechaCreacion)->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Fecha Requerida</label>
                            <p class="mt-1 text-gray-800 dark:text-white">
                                {{ \Carbon\Carbon::parse($repuesto->fecharequerida)->format('d/m/Y') }}
                            </p>
                        </div>
                        
                        @if($repuesto->tipo_servicio)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tipo de Servicio</label>
                            <p class="mt-1 text-gray-800 dark:text-white">{{ $repuesto->tipo_servicio }}</p>
                        </div>
                        @endif
                        
                        @if($repuesto->nombre_cliente)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Cliente</label>
                            <p class="mt-1 text-gray-800 dark:text-white">{{ $repuesto->nombre_cliente }}</p>
                        </div>
                        @endif
                        
                        @if($repuesto->observaciones_solicitud)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Observaciones de Solicitud</label>
                            <p class="mt-1 text-gray-800 dark:text-white p-3 bg-gray-50 dark:bg-gray-700 rounded">
                                {{ $repuesto->observaciones_solicitud }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Evidencias Fotográficas -->
                @if(!empty($fotos) && count($fotos) > 0)
                <div class="panel">
                    <h5 class="font-semibold text-lg mb-4 text-gray-800 dark:text-white">
                        <i class="fas fa-camera mr-2"></i>Evidencias Fotográficas
                    </h5>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($fotos as $index => $foto)
                        <div class="relative group" x-data="{ open: false }">
                            <img src="{{ Storage::url($foto) }}" 
                                 alt="Evidencia {{ $index + 1 }}"
                                 class="w-full h-32 object-cover rounded-lg cursor-pointer"
                                 @click="open = true">
                            
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 
                                      transition-all duration-200 rounded-lg flex items-center justify-center">
                                <button @click="open = true" 
                                        class="opacity-0 group-hover:opacity-100 text-white">
                                    <i class="fas fa-search-plus text-2xl"></i>
                                </button>
                            </div>
                            
                            <!-- Modal para ver imagen completa -->
                            <div x-show="open" @click.away="open = false" 
                                 x-transition class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4">
                                <div class="max-w-4xl max-h-full">
                                    <img src="{{ Storage::url($foto) }}" 
                                         alt="Evidencia {{ $index + 1 }}"
                                         class="max-w-full max-h-screen rounded-lg">
                                    <div class="text-center mt-4">
                                        <button @click="open = false" class="btn btn-primary">
                                            Cerrar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Columna 2: Información Adicional -->
            <div class="space-y-6">
                <!-- Tarjeta de Destino -->
                <div class="panel">
                    <h5 class="font-semibold text-lg mb-4 text-gray-800 dark:text-white">
                        <i class="fas fa-map-marker-alt mr-2"></i>Información de Destino
                    </h5>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Área Destino</label>
                            <p class="mt-1 text-gray-800 dark:text-white">{{ $repuesto->nombre_area ?? 'No especificado' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Técnico Asignado</label>
                            <p class="mt-1 text-gray-800 dark:text-white">{{ $repuesto->nombre_tecnico ?? 'No asignado' }}</p>
                        </div>
                        
                        @if($repuesto->email_tecnico)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Email del Técnico</label>
                            <p class="mt-1 text-gray-800 dark:text-white">{{ $repuesto->email_tecnico }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Tarjeta de Historial -->
                <div class="panel">
                    <h5 class="font-semibold text-lg mb-4 text-gray-800 dark:text-white">
                        <i class="fas fa-history mr-2"></i>Historial de Estados
                    </h5>
                    
                    <div class="space-y-3">
                        <!-- Evento: Enviado a Tránsito -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center">
                                <i class="fas fa-truck text-white text-sm"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-800 dark:text-white">Enviado a Tránsito</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($repuesto->fecha_salida)->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Aquí podrías agregar más eventos del historial -->
                        <div class="text-center py-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                No hay más eventos registrados
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta de Acciones Rápidas -->
                <div class="panel">
                    <h5 class="font-semibold text-lg mb-4 text-gray-800 dark:text-white">
                        <i class="fas fa-bolt mr-2"></i>Acciones Rápidas
                    </h5>
                    
                    <div class="space-y-3">
                        <button @click="marcarEstado('recibido')" 
                                class="btn btn-success w-full justify-start">
                            <i class="fas fa-check-circle mr-2"></i>Marcar como Recibido
                        </button>
                        
                        <button @click="marcarEstado('usado')" 
                                class="btn btn-primary w-full justify-start">
                            <i class="fas fa-wrench mr-2"></i>Marcar como Usado
                        </button>
                        
                        <button @click="marcarEstado('perdido')" 
                                class="btn btn-danger w-full justify-start">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Reportar Pérdida
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Marcar Estado -->
        <div x-show="mostrarModal" x-transition class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full" @click.away="cerrarModal()">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">
                        <i class="fas" :class="modalIcon"></i> {{ modalTitulo }}
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Fecha</label>
                            <input type="datetime-local" x-model="modalFecha" class="form-input w-full">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium mb-2">Observaciones</label>
                            <textarea x-model="modalObservaciones" rows="3" class="form-textarea w-full" 
                                      placeholder="Observaciones adicionales..."></textarea>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button @click="cerrarModal()" class="btn btn-outline-secondary">
                            Cancelar
                        </button>
                        <button @click="confirmarEstado()" class="btn btn-primary">
                            Confirmar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    function detalleRepuesto() {
        return {
            mostrarModal: false,
            modalEstado: '',
            modalFecha: '',
            modalObservaciones: '',
            modalTitulo: '',
            modalIcon: '',
            
            init() {
                // Establecer fecha actual por defecto
                const now = new Date();
                this.modalFecha = now.toISOString().slice(0, 16);
            },
            
            marcarEstado(estado) {
                this.modalEstado = estado;
                
                // Configurar modal según estado
                const config = {
                    'recibido': {
                        titulo: 'Marcar como Recibido',
                        icon: 'fa-check-circle text-green-500'
                    },
                    'usado': {
                        titulo: 'Marcar como Usado',
                        icon: 'fa-wrench text-blue-500'
                    },
                    'perdido': {
                        titulo: 'Reportar Pérdida',
                        icon: 'fa-exclamation-triangle text-red-500'
                    }
                };
                
                this.modalTitulo = config[estado]?.titulo || 'Marcar Estado';
                this.modalIcon = config[estado]?.icon || 'fa-edit';
                this.mostrarModal = true;
            },
            
            confirmarEstado() {
                fetch('{{ route("repuesto-transito.update", $repuesto->idOrdenesArticulos) }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        estado: this.modalEstado,
                        fecha_entrega_real: this.modalFecha,
                        observaciones: this.modalObservaciones
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.cerrarModal();
                        window.location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al actualizar el estado');
                });
            },
            
            cerrarModal() {
                this.mostrarModal = false;
                this.modalEstado = '';
                this.modalObservaciones = '';
            }
        };
    }
    </script>
    @endpush
</x-layout.default>