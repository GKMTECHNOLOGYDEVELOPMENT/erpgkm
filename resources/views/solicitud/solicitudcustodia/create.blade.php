<x-layout.default>
    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-indigo-700">Nueva Custodia</h1>
                <p class="text-gray-600 mt-2">Registrar nuevo equipo en custodia</p>
            </div>
            <a href="{{ route('solicitudcustodia.index') }}" 
               class="btn btn-outline-primary mt-4 md:mt-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver al Listado
            </a>
        </div>

        <!-- Formulario -->
        <div class="max-w-4xl mx-auto">
            <div class="panel rounded-xl shadow-sm p-6">
                <form method="POST" action="{{ route('solicitudcustodia.store') }}">
                    @csrf

                    <!-- Información del Cliente -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Información del Cliente
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Cliente -->
                            <div>
                                <label for="idcliente" class="block text-sm font-medium text-gray-700 mb-2">Cliente *</label>
                                <select name="idcliente" id="idcliente" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 outline-none transition-colors duration-300">
                                    <option value="">Seleccionar Cliente</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->idCliente }}" 
                                            {{ old('idcliente') == $cliente->idCliente ? 'selected' : '' }}>
                                            {{ $cliente->nombre }} - {{ $cliente->documento }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('idcliente')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Número de Ticket -->
                            <div>
                                <label for="numero_ticket" class="block text-sm font-medium text-gray-700 mb-2">Número de Ticket</label>
                                <input type="text" name="numero_ticket" id="numero_ticket" 
                                    value="{{ old('numero_ticket') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 outline-none transition-colors duration-300"
                                    placeholder="Ej: TKT-001">
                                @error('numero_ticket')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Información del Equipo -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                            </svg>
                            Información del Equipo
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Marca -->
                            <div>
                                <label for="idMarca" class="block text-sm font-medium text-gray-700 mb-2">Marca</label>
                                <select name="idMarca" id="idMarca"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 outline-none transition-colors duration-300">
                                    <option value="">Seleccionar Marca</option>
                                    @foreach($marcas as $marca)
                                        <option value="{{ $marca->idMarca }}" 
                                            {{ old('idMarca') == $marca->idMarca ? 'selected' : '' }}>
                                            {{ $marca->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('idMarca')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Modelo -->
                            <div>
                                <label for="idModelo" class="block text-sm font-medium text-gray-700 mb-2">Modelo</label>
                                <select name="idModelo" id="idModelo"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 outline-none transition-colors duration-300">
                                    <option value="">Seleccionar Modelo</option>
                                    @foreach($modelos as $modelo)
                                        <option value="{{ $modelo->idModelo }}" 
                                            {{ old('idModelo') == $modelo->idModelo ? 'selected' : '' }}>
                                            {{ $modelo->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('idModelo')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Serie -->
                            <div>
                                <label for="serie" class="block text-sm font-medium text-gray-700 mb-2">Número de Serie</label>
                                <input type="text" name="serie" id="serie" 
                                    value="{{ old('serie') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 outline-none transition-colors duration-300"
                                    placeholder="Ej: SN123456789">
                                @error('serie')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Información de la Custodia -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Información de la Custodia
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Fecha de Ingreso -->
                            <div>
                                <label for="fecha_ingreso_custodia" class="block text-sm font-medium text-gray-700 mb-2">Fecha de Ingreso *</label>
                                <input type="date" name="fecha_ingreso_custodia" id="fecha_ingreso_custodia" required
                                    value="{{ old('fecha_ingreso_custodia', date('Y-m-d')) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 outline-none transition-colors duration-300">
                                @error('fecha_ingreso_custodia')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Ubicación Actual -->
                            <div>
                                <label for="ubicacion_actual" class="block text-sm font-medium text-gray-700 mb-2">Ubicación de Recepción *</label>
                                <input type="text" name="ubicacion_actual" id="ubicacion_actual" required
                                    value="{{ old('ubicacion_actual') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 outline-none transition-colors duration-300"
                                    placeholder="Ej: Recepción Principal">
                                @error('ubicacion_actual')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            

                            <!-- Estado -->
                            <div>
                                <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">Estado *</label>
                                <select name="estado" id="estado" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 outline-none transition-colors duration-300">
                                    <option value="Pendiente" {{ old('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="En revisión" {{ old('estado') == 'En revisión' ? 'selected' : '' }}>En revisión</option>
                                    <option value="Aprobado" {{ old('estado') == 'Aprobado' ? 'selected' : '' }}>Aprobado</option>
                                </select>
                                @error('estado')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    <div class="mb-6">
                        <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-2">Observaciones</label>
                        <textarea name="observaciones" id="observaciones" rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 outline-none transition-colors duration-300"
                            placeholder="Observaciones adicionales...">{{ old('observaciones') }}</textarea>
                        @error('observaciones')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('solicitudcustodia.index') }}" 
                           class="btn btn-outline-primary px-6 py-2">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="btn btn-primary px-6 py-2 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Guardar Custodia
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script para generar código de custodia automático -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Generar código de custodia automáticamente
            function generarCodigoCustodia() {
                const timestamp = new Date().getTime();
                const random = Math.floor(Math.random() * 1000);
                return `CUST-${timestamp}-${random}`;
            }

            // Establecer código generado en un campo hidden (si lo necesitas)
            console.log('Código generado:', generarCodigoCustodia());
        });
    </script>
</x-layout.default>