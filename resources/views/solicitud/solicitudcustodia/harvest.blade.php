<x-layout.default>
    <div class="container mx-auto px-4 py-6">
        <!-- Notificación AJAX -->
        <div id="ajax-notification" class="hidden rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-800 px-4 py-3 flex items-center gap-3 shadow-sm mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span id="ajax-message" class="font-medium"></span>
            <button class="ml-auto text-emerald-600 hover:text-emerald-800" onclick="hideNotification()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 p-5 bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 17a4 4 0 008 0M5 21h14M9 17V9a4 4 0 014-4h4" />
                    </svg>
                    Sistema Harvest - Retiro de Repuestos
                </h1>

                <!-- Info custodia -->
                <div class="flex flex-wrap items-center gap-3 text-sm text-gray-700 mt-1">
                    <div class="flex items-center gap-1 bg-primary-light px-3 py-1.5 rounded-md">
                        <span class="text-xs uppercase tracking-wide">Código:</span>
                        <span>#{{ $custodia->codigocustodias }}</span>
                    </div>

                    <span class="text-gray-400">-</span>

                    <div class="flex items-center gap-1 bg-success-light px-3 py-1.5 rounded-md">
                        <span class="text-xs uppercase tracking-wide">Equipo:</span>
                        <span>{{ $custodia->marca->nombre ?? '—' }} {{ $custodia->modelo->nombre ?? '' }}</span>
                    </div>

                    <span class="text-gray-400">-</span>

                    <div class="flex items-center gap-1 bg-info-light px-3 py-1.5 rounded-md">
                        <span class="text-xs uppercase tracking-wide">Serie:</span>
                        <span>{{ $custodia->serie ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('solicitudcustodia.opciones', ['id' => $custodia->id]) }}"
                    class="btn btn-warning flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Volver a Opciones
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Formulario para retirar repuestos -->
            <div class="panel rounded-xl shadow-sm overflow-hidden">
                <div class="bg-success-light px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Retirar Repuesto
                    </h2>
                </div>

                <form id="form-retiro" class="p-6 space-y-4">
                    @csrf

                    <!-- Campo oculto para id_articulo -->
                    <input type="hidden" id="id_articulo" name="id_articulo">

                    <!-- Selección de repuesto por código -->
                    <div>
                        <label for="codigo_repuesto" class="block text-sm font-medium text-gray-700 mb-2">
                            Código de Repuesto
                        </label>
                        <select id="codigo_repuesto" name="codigo_repuesto"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200">
                            <option value="">Seleccionar código de repuesto</option>
                            @foreach($repuestos as $repuesto)
                            <option value="{{ $repuesto->codigo_repuesto }}"
                                data-id-articulo="{{ $repuesto->idArticulos }}"
                                data-modelos="{{ $repuesto->modelos->pluck('nombre')->implode(', ') }}"
                                data-subcategoria="{{ $repuesto->subcategoria->nombre ?? 'N/A' }}">
                                {{ $repuesto->codigo_repuesto }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Información del repuesto seleccionado -->
                    <div id="info-repuesto" class="hidden bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-800 mb-3">Información del Repuesto</h4>
                        <div class="grid grid-cols-1 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-600">Modelos compatibles:</span>
                                <span id="info-modelos" class="ml-2 text-gray-800"></span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-600">Subcategoría:</span>
                                <span id="info-subcategoria" class="ml-2 text-gray-800"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Cantidad -->
                    <div>
                        <label for="cantidad" class="block text-sm font-medium text-gray-700 mb-2">
                            Cantidad a retirar
                        </label>
                        <input type="number" id="cantidad" name="cantidad" min="1" value="1"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200">
                    </div>

                    <!-- Observaciones -->
                    <div>
                        <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-2">
                            Observaciones
                        </label>
                        <textarea id="observaciones" name="observaciones" rows="3"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                            placeholder="Motivo del retiro, detalles, etc."></textarea>
                    </div>

                    <!-- Botón de retiro -->
                    <div class="pt-4">
                        <button type="submit"
                            class="w-full btn btn-success flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Retirar Repuesto
                        </button>
                    </div>
                </form>
            </div>

            <!-- Lista de retiros realizados -->
            <div class="panel rounded-xl shadow-sm overflow-hidden">
                <div class="bg-primary-light px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Historial de Retiros
                    </h2>
                </div>

                <div class="p-6">
                    @if($retiros->count() > 0)
                    <div class="space-y-4">
                        @foreach($retiros as $retiro)
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex flex-col gap-2 mt-1 text-xs text-gray-600">
                                    <div>
                                        <span class="font-medium">Modelos compatibles:</span>
                                        <span>
                                            @if($retiro->articulo->modelos->count() > 0)
                                            {{ $retiro->articulo->modelos->pluck('nombre')->implode(', ') }}
                                            @else
                                            N/A
                                            @endif
                                        </span>
                                    </div>
                                    <div>
                                        <span class="font-medium">Subcategoría:</span>
                                        <span>{{ $retiro->articulo->subcategoria->nombre ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    {{ $retiro->cantidad_retirada }} unidades
                                </span>
                            </div>

                            @if($retiro->observaciones)
                            <p class="text-sm text-gray-700 mb-2">{{ $retiro->observaciones }}</p>
                            @endif

                            <div class="flex justify-between items-center text-xs text-gray-500">
                                <span>Retirado por: {{ $retiro->responsable->Nombre ?? 'N/A' }} {{ $retiro->responsable->apellidoPaterno ?? '' }}</span>
                                <span>{{ $retiro->created_at->format('d/m/Y H:i') }}</span>
                            </div>

                            <div class="mt-3 pt-3 border-t border-gray-100 flex justify-end">
                                <button type="button"
                                    onclick="anularRetiro({{ $retiro->id }})"
                                    class="btn btn-danger btn-sm flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Anular
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8 text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <p class="text-sm">No hay retiros registrados</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Ocultar notificación
        function hideNotification() {
            const notification = document.getElementById('ajax-notification');
            if (notification) {
                notification.classList.add('hidden');
            }
        }

        // Mostrar notificación
        function showNotification(message, isSuccess = true) {
            const notification = document.getElementById('ajax-notification');
            const messageElement = document.getElementById('ajax-message');

            if (!notification || !messageElement) return;

            messageElement.textContent = message;

            if (isSuccess) {
                notification.classList.remove('bg-red-50', 'border-red-200', 'text-red-800');
                notification.classList.add('bg-emerald-50', 'border-emerald-200', 'text-emerald-800');
            } else {
                notification.classList.remove('bg-emerald-50', 'border-emerald-200', 'text-emerald-800');
                notification.classList.add('bg-red-50', 'border-red-200', 'text-red-800');
            }

            notification.classList.remove('hidden');

            setTimeout(hideNotification, 5000);
        }

        // Anular retiro
        function anularRetiro(idRetiro) {
            if (!confirm('¿Estás seguro de anular este retiro?')) {
                return;
            }

            fetch("{{ route('solicitudcustodia.anular-retiro', '') }}/" + idRetiro, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(data.message, true);
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showNotification(data.message, false);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error de conexión', false);
                });
        }

        // Esperar a que el DOM esté completamente cargado
        document.addEventListener('DOMContentLoaded', function() {
            const formRetiro = document.getElementById('form-retiro');
            const selectCodigo = document.getElementById('codigo_repuesto');
            const inputIdArticulo = document.getElementById('id_articulo');
            const infoDiv = document.getElementById('info-repuesto');

            if (selectCodigo && inputIdArticulo && infoDiv) {
                // Cuando cambie el select, actualizar el campo oculto y mostrar info
                selectCodigo.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const idArticulo = selectedOption.getAttribute('data-id-articulo');
                    const modelo = selectedOption.getAttribute('data-modelo');
                    const subcategoria = selectedOption.getAttribute('data-subcategoria');

                    if (idArticulo) {
                        inputIdArticulo.value = idArticulo;

                        // Mostrar información del repuesto (sin marca)
                        document.getElementById('info-modelo').textContent = modelo;
                        document.getElementById('info-subcategoria').textContent = subcategoria;
                        infoDiv.classList.remove('hidden');
                    } else {
                        inputIdArticulo.value = '';
                        infoDiv.classList.add('hidden');
                    }
                });
            }

            if (formRetiro) {
                formRetiro.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const btnSubmit = this.querySelector('button[type="submit"]');
                    const originalText = btnSubmit.innerHTML;

                    // Validar campos requeridos
                    const codigoRepuesto = document.getElementById('codigo_repuesto').value;
                    const cantidad = document.getElementById('cantidad').value;
                    const idArticulo = document.getElementById('id_articulo').value;

                    if (!codigoRepuesto) {
                        showNotification('Por favor selecciona un código de repuesto', false);
                        return;
                    }

                    if (!idArticulo) {
                        showNotification('Error: No se pudo obtener el ID del artículo', false);
                        return;
                    }

                    if (!cantidad || cantidad < 1) {
                        showNotification('Por favor ingresa una cantidad válida', false);
                        return;
                    }

                    // Mostrar loader
                    btnSubmit.innerHTML = '<span>Procesando...</span>';
                    btnSubmit.disabled = true;

                    fetch("{{ route('solicitudcustodia.retirar-repuesto', $custodia->id) }}", {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Error en la respuesta del servidor');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                showNotification(data.message, true);
                                this.reset();
                                // Limpiar también el campo oculto
                                document.getElementById('id_articulo').value = '';

                                // Recargar la página para actualizar la lista
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
                            } else {
                                showNotification(data.message, false);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showNotification('Error de conexión: ' + error.message, false);
                        })
                        .finally(() => {
                            btnSubmit.innerHTML = originalText;
                            btnSubmit.disabled = false;
                        });
                });
            }
        });
    </script>
</x-layout.default>