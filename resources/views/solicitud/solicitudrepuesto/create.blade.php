<x-layout.default>
    <!-- Incluir CSS de Select2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <div class="min-h-screen bg-[#eaf1ff] py-8">
        <div class="container mx-auto px-4 w-full">
            <!-- Header Principal -->
            <div class="bg-white rounded-xl shadow-lg p-8 mb-8 border border-[#eaf1ff]">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="mb-4 lg:mb-0">
                        <h1 class="text-4xl font-bold text-[#0e1726] mb-3">Crear Nueva Orden de Repuesto</h1>
                        <div class="flex flex-wrap gap-6 text-sm">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-[#00ab55] rounded-full mr-3"></div>
                                <span class="text-[#3b3f5c]">Usuario: <strong class="text-[#0e1726]">Administrador
                                        Principal</strong></span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-[#4361ee] rounded-full mr-3"></div>
                                <span id="current-date" class="text-[#0e1726] font-medium"></span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-[#eaf1ff] rounded-lg px-4 py-3">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-[#4361ee]">#ORD-001</div>
                            <div class="text-sm text-[#3b3f5c]">Número de Orden</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
                <!-- Columna Principal -->
                <div class="xl:col-span-3 space-y-8">
                    <!-- Paso 1: Selección de Productos -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-[#eaf1ff]">
                        <div class="bg-[#4361ee] px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="flex items-center justify-center w-10 h-10 bg-white text-[#4361ee] rounded-full font-bold text-lg">
                                    1
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-white">Selección de Productos</h2>
                                    <p class="text-[#eaf1ff] text-sm">Agregue los repuestos necesarios para la orden</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <!-- Tabla de Productos -->
                            <div class="mb-8">
                                <div class="flex justify-between items-center mb-6">
                                    <h3 class="text-xl font-semibold text-[#0e1726]">Productos Seleccionados</h3>
                                    <div class="flex items-center space-x-4">
                                        <span class="text-sm text-[#3b3f5c]" id="product-count">0 productos</span>
                                        <div class="w-2 h-2 bg-[#00ab55] rounded-full animate-pulse"></div>
                                    </div>
                                </div>

                                <div class="overflow-hidden rounded-lg border border-[#eaf1ff]">
                                    <table class="w-full">
                                        <thead class="bg-[#eaf1ff]">
                                            <tr>
                                                <th
                                                    class="px-6 py-4 text-left text-sm font-semibold text-[#4361ee] uppercase tracking-wider">
                                                    Modelo</th>
                                                <th
                                                    class="px-6 py-4 text-left text-sm font-semibold text-[#4361ee] uppercase tracking-wider">
                                                    Tipo de Repuesto</th>
                                                <th
                                                    class="px-6 py-4 text-left text-sm font-semibold text-[#4361ee] uppercase tracking-wider">
                                                    Código</th>
                                                <th
                                                    class="px-6 py-4 text-left text-sm font-semibold text-[#4361ee] uppercase tracking-wider">
                                                    Cantidad</th>
                                                <th
                                                    class="px-6 py-4 text-left text-sm font-semibold text-[#4361ee] uppercase tracking-wider">
                                                    Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="products-table-body" class="bg-white divide-y divide-[#eaf1ff]">
                                            <tr id="empty-state" class="text-center">
                                                <td colspan="5" class="px-6 py-12 text-[#3b3f5c]">
                                                    <svg class="mx-auto h-16 w-16 text-[#eaf1ff]" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1"
                                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                                        </path>
                                                    </svg>
                                                    <p class="mt-4 text-lg font-medium">No hay productos agregados</p>
                                                    <p class="text-sm mt-2">Agregue productos usando el formulario
                                                        inferior</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Formulario para Agregar Producto -->
                            <div class="bg-[#eaf1ff] rounded-xl p-6 border border-[#4361ee]/20">
                                <h3 class="text-xl font-semibold text-[#0e1726] mb-6">Agregar Nuevo Producto</h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                                    <!-- Modelo - Select2 -->
                                    <div>
                                        <label class="block text-sm font-semibold text-[#3b3f5c] mb-3">Modelo</label>
                                        <select id="modelo-select" class="modelo-select2 w-full">
                                            <option value="">Seleccionar modelo</option>
                                            <option value="65C6K">65C6K - Smart TV 65" 4K UHD</option>
                                            <option value="75U7K">75U7K - Smart TV 75" 4K UHD</option>
                                            <option value="55B8K">55B8K - Smart TV 55" OLED</option>
                                            <option value="85X9K">85X9K - Smart TV 85" 8K QLED</option>
                                            <option value="32A4K">32A4K - Smart TV 32" HD Ready</option>
                                            <option value="43B5K">43B5K - Smart TV 43" 4K UHD</option>
                                            <option value="50C7K">50C7K - Smart TV 50" 4K UHD</option>
                                            <option value="58D8K">58D8K - Smart TV 58" 4K UHD</option>
                                        </select>
                                    </div>

                                    <!-- Tipo de Repuesto - Select2 -->
                                    <div>
                                        <label class="block text-sm font-semibold text-[#3b3f5c] mb-3">Tipo de
                                            Repuesto</label>
                                        <select id="tipo-select" class="tipo-select2 w-full">
                                            <option value="">Seleccionar tipo</option>
                                            <option value="PANTALLA">🖥️ PANTALLA</option>
                                            <option value="PLACA BASE">🔌 PLACA BASE</option>
                                            <option value="FUENTE PODER">⚡ FUENTE PODER</option>
                                            <option value="TARJETA VIDEO">🎮 TARJETA VIDEO</option>
                                            <option value="MEMORIA RAM">💾 MEMORIA RAM</option>
                                            <option value="PROCESADOR">🚀 PROCESADOR</option>
                                            <option value="DISCO DURO">💿 DISCO DURO</option>
                                            <option value="VENTILADOR">🌬️ VENTILADOR</option>
                                            <option value="CONECTORES">🔗 CONECTORES</option>
                                            <option value="CABLEADO">📞 CABLEADO</option>
                                        </select>
                                    </div>

                                    <!-- Código - Select2 con búsqueda -->
                                    <div>
                                        <label class="block text-sm font-semibold text-[#3b3f5c] mb-3">Código</label>
                                        <select id="codigo-select" class="codigo-select2 w-full">
                                            <option value="">Buscar código...</option>
                                            <option value="N0404-002068">N0404-002068 - Pantalla Principal</option>
                                            <option value="N0404-002069">N0404-002069 - Pantalla Secundaria</option>
                                            <option value="N0405-001234">N0405-001234 - Placa Base V2</option>
                                            <option value="N0405-001235">N0405-001235 - Placa Base V3</option>
                                            <option value="N0406-003456">N0406-003456 - Fuente 500W</option>
                                            <option value="N0406-003457">N0406-003457 - Fuente 750W</option>
                                            <option value="N0407-004567">N0407-004567 - Tarjeta Video GTX</option>
                                            <option value="N0407-004568">N0407-004568 - Tarjeta Video RTX</option>
                                            <option value="N0408-005678">N0408-005678 - Memoria 8GB</option>
                                            <option value="N0408-005679">N0408-005679 - Memoria 16GB</option>
                                            <option value="N0409-006789">N0409-006789 - Procesador i7</option>
                                            <option value="N0409-006790">N0409-006790 - Procesador i9</option>
                                        </select>
                                    </div>

                                    <!-- Cantidad - Nuevo diseño con botones inline -->
                                    <div>
                                        <label class="block text-sm font-semibold text-[#3b3f5c] mb-3">Cantidad</label>
                                        <div class="inline-flex w-full">
                                            <button type="button" id="decrease-qty"
                                                class="bg-[#4361ee] text-white flex justify-center items-center rounded-l-md px-4 font-semibold border border-r-0 border-[#4361ee] hover:bg-[#3b56e0] transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M20 12H4"></path>
                                                </svg>
                                            </button>
                                            <input type="number" id="cantidad-input" value="1" min="1"
                                                max="100"
                                                class="form-input cantidad-input rounded-none text-center w-16 border-y border-primary focus:border-primary focus:ring-0 bg-white text-[#0e1726] font-semibold"
                                                readonly />
                                            <button type="button" id="increase-qty"
                                                class="bg-primary text-white flex justify-center items-center rounded-r-md px-4 font-semibold border border-l-0 border-primary hover:bg-[#3b56e0] transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="text-xs text-[#3b3f5c] mt-2 text-center">Mín: 1 - Máx: 100</div>
                                    </div>
                                </div>

                                <button id="add-product-btn"
                                    class="w-full bg-[#4361ee] text-white py-4 px-6 rounded-lg font-bold hover:bg-[#3b56e0] transition-all duration-200 transform hover:scale-[1.02] flex items-center justify-center space-x-3 shadow-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <span class="text-lg">Agregar Producto a la Orden</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Paso 2: Información Adicional -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-[#eaf1ff]">
                        <div class="bg-[#2196f3] px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="flex items-center justify-center w-10 h-10 bg-white text-[#2196f3] rounded-full font-bold text-lg">
                                    2
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-white">Información Adicional</h2>
                                    <p class="text-[#e7f7ff] text-sm">Complete los detalles de la orden</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <!-- Tipo de Servicio - Select2 -->
                                <div>
                                    <label class="block text-lg font-semibold text-[#0e1726] mb-4">Tipo de
                                        Servicio</label>
                                    <select id="servicio-select" class="servicio-select2 w-full">
                                        <option value="">Seleccione una serie</option>
                                        <option value="mantenimiento">🛠️ Mantenimiento Preventivo</option>
                                        <option value="reparacion">🔧 Reparación Correctiva</option>
                                        <option value="instalacion">⚡ Instalación</option>
                                        <option value="garantia">📋 Garantía</option>
                                        <option value="emergencia">🚨 Servicio de Emergencia</option>
                                        <option value="actualizacion">🔄 Actualización</option>
                                        <option value="diagnostico">🔍 Diagnóstico</option>
                                    </select>
                                </div>

                                <!-- Nivel de Urgencia -->
                                <div>
                                    <label class="block text-lg font-semibold text-[#0e1726] mb-4">Nivel de
                                        Urgencia</label>
                                    <div class="grid grid-cols-3 gap-3">
                                        <button type="button"
                                            class="py-4 px-4 border-2 border-[#00ab55] rounded-lg text-sm font-bold bg-[#ddfff0] text-[#00ab55] hover:bg-[#00ab55] hover:text-white transition-all duration-200">
                                            🟢 Baja
                                        </button>
                                        <button type="button"
                                            class="py-4 px-4 border-2 border-[#e2a03f] rounded-lg text-sm font-bold bg-[#fff9ed] text-[#e2a03f] hover:bg-[#e2a03f] hover:text-white transition-all duration-200">
                                            🟡 Media
                                        </button>
                                        <button type="button"
                                            class="py-4 px-4 border-2 border-[#e7515a] rounded-lg text-sm font-bold bg-[#fff8f5] text-[#e7515a] hover:bg-[#e7515a] hover:text-white transition-all duration-200">
                                            🔴 Alta
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Observaciones -->
                            <div class="mt-8">
                                <label class="block text-lg font-semibold text-[#0e1726] mb-4">Observaciones y
                                    Comentarios</label>
                                <textarea rows="5"
                                    class="w-full px-4 py-4 border border-[#eaf1ff] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2196f3] focus:border-[#2196f3] bg-white text-[#0e1726] resize-none transition-all duration-200"
                                    placeholder="Describa cualquier observación, comentario adicional o instrucción especial para esta orden..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar - Estadísticas y Acciones -->
                <div class="xl:col-span-1 space-y-8">
                    <!-- Resumen de la Orden -->
                    <div class="bg-white rounded-xl shadow-lg p-6 border border-[#eaf1ff]">
                        <h3 class="text-xl font-bold text-[#0e1726] mb-6 text-center">Resumen de Orden</h3>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-[#eaf1ff]">
                                <span class="text-[#3b3f5c] font-medium">Total Productos</span>
                                <span class="text-2xl font-bold text-[#4361ee]" id="total-products">0</span>
                            </div>

                            <div class="flex justify-between items-center py-3 border-b border-[#eaf1ff]">
                                <span class="text-[#3b3f5c] font-medium">Total Cantidad</span>
                                <span class="text-2xl font-bold text-[#00ab55]" id="total-quantity">0</span>
                            </div>

                            <div class="flex justify-between items-center py-3">
                                <span class="text-[#3b3f5c] font-medium">Estado</span>
                                <span
                                    class="px-3 py-1 bg-[#fff9ed] text-[#e2a03f] rounded-full text-sm font-bold">Pendiente</span>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones Rápidas -->
                    <div class="bg-white rounded-xl shadow-lg p-6 border border-[#eaf1ff]">
                        <h3 class="text-xl font-bold text-[#0e1726] mb-6 text-center">Acciones</h3>

                        <div class="space-y-4">
                            <button id="clear-all-btn" type="button" class="btn btn-warning">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                                <span>Limpiar Todo</span>
                            </button>

                            <button
                                class="w-full px-6 py-4 border-2 border-[#2196f3] text-[#2196f3] rounded-lg font-bold hover:bg-[#2196f3] hover:text-white transition-all duration-200 flex items-center justify-center space-x-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                                    </path>
                                </svg>
                                <span>Guardar Borrador</span>
                            </button>

                            <button id="create-order-btn"
                                class="w-full px-6 py-4 bg-[#00ab55] text-white rounded-lg font-bold hover:bg-[#00994c] transition-all duration-200 transform hover:scale-[1.02] flex items-center justify-center space-x-3 shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-lg">Crear Orden</span>
                            </button>
                        </div>
                    </div>

                    <!-- Información de Contacto -->
                    <div class="bg-[#eaf1ff] rounded-xl p-6 border border-[#4361ee]/30">
                        <h4 class="text-lg font-bold text-[#4361ee] mb-4 text-center">¿Necesita Ayuda?</h4>
                        <div class="text-center text-[#3b3f5c] text-sm space-y-2">
                            <p>📞 <strong>Soporte Técnico:</strong> +1 (555) 123-4567</p>
                            <p>✉️ <strong>Email:</strong> soporte@empresa.com</p>
                            <p>🕒 <strong>Horario:</strong> 24/7</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Incluir jQuery y Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/i18n/es.min.js"></script>

    <script>
        $(document).ready(function() {
            // Inicializar Select2 para Modelo
            $('.modelo-select2').select2({
                placeholder: 'Seleccionar modelo',
                allowClear: true,
                language: 'es',
                width: '100%',
                theme: 'default'
            });

            // Inicializar Select2 para Tipo de Repuesto
            $('.tipo-select2').select2({
                placeholder: 'Seleccionar tipo',
                allowClear: true,
                language: 'es',
                width: '100%',
                theme: 'default'
            });

            // Inicializar Select2 para Código con búsqueda
            $('.codigo-select2').select2({
                placeholder: 'Buscar código...',
                allowClear: true,
                language: 'es',
                width: '100%',
                theme: 'default',
                minimumInputLength: 2,
                ajax: {
                    url: '/api/codigos', // Esta sería tu API real
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;

                        // Simulación de datos - en producción vendría de tu API
                        var mockData = {
                            results: [{
                                    id: 'N0404-002068',
                                    text: 'N0404-002068 - Pantalla Principal 65"'
                                },
                                {
                                    id: 'N0404-002069',
                                    text: 'N0404-002069 - Pantalla Secundaria'
                                },
                                {
                                    id: 'N0405-001234',
                                    text: 'N0405-001234 - Placa Base V2'
                                },
                                {
                                    id: 'N0405-001235',
                                    text: 'N0405-001235 - Placa Base V3'
                                },
                                {
                                    id: 'N0406-003456',
                                    text: 'N0406-003456 - Fuente 500W'
                                }
                            ].filter(item =>
                                item.text.toLowerCase().includes(params.term.toLowerCase())
                            ),
                            pagination: {
                                more: false
                            }
                        };

                        return mockData;
                    },
                    cache: true
                },
                templateResult: function(data) {
                    if (!data.id) {
                        return data.text;
                    }

                    var $result = $(
                        '<div class="flex items-center justify-between">' +
                        '<span class="font-semibold">' + data.id + '</span>' +
                        '<span class="text-sm text-gray-500 ml-2">' + data.text.split(' - ')[1] +
                        '</span>' +
                        '</div>'
                    );

                    return $result;
                }
            });

            // Inicializar Select2 para Tipo de Servicio
            $('.servicio-select2').select2({
                placeholder: 'Seleccione una serie',
                allowClear: true,
                language: 'es',
                width: '100%',
                theme: 'default'
            });

            // Set current date
            const now = new Date();
            $('#current-date').text(now.toLocaleDateString('es-ES', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            }));

            let products = [];
            let productIdCounter = 1;

            // Quantity controls - Nuevo diseño mejorado
            const cantidadInput = document.getElementById('cantidad-input');
            const decreaseBtn = document.getElementById('decrease-qty');
            const increaseBtn = document.getElementById('increase-qty');

            // Función para actualizar cantidad
            function updateCantidad(change) {
                let currentValue = parseInt(cantidadInput.value);
                let newValue = currentValue + change;

                // Validar límites
                if (newValue < 1) newValue = 1;
                if (newValue > 100) newValue = 100;

                cantidadInput.value = newValue;
            }

            // Event listeners para los botones
            decreaseBtn.addEventListener('click', () => {
                updateCantidad(-1);
            });

            increaseBtn.addEventListener('click', () => {
                updateCantidad(1);
            });

            // Prevenir el cambio manual del input (solo lectura)
            cantidadInput.addEventListener('keydown', (e) => {
                e.preventDefault();
            });

            // Control con rueda del mouse
            cantidadInput.addEventListener('wheel', (e) => {
                e.preventDefault();
                if (e.deltaY < 0) {
                    updateCantidad(1);
                } else {
                    updateCantidad(-1);
                }
            });

            // Add product function
            document.getElementById('add-product-btn').addEventListener('click', function() {
                const modelo = $('#modelo-select').val();
                const tipo = $('#tipo-select').val();
                const codigo = $('#codigo-select').val();
                const cantidad = parseInt(cantidadInput.value);

                if (!modelo || !tipo || !codigo) {
                    showNotification('Por favor complete todos los campos del producto', 'error');
                    return;
                }

                const product = {
                    id: productIdCounter++,
                    modelo,
                    tipo,
                    codigo,
                    cantidad
                };

                products.push(product);
                updateProductsTable();
                updateStats();
                clearProductForm();

                showNotification('✅ Producto agregado correctamente', 'success');
            });

            // Clear all products
            document.getElementById('clear-all-btn').addEventListener('click', function() {
                if (products.length === 0) {
                    showNotification('No hay productos para limpiar', 'info');
                    return;
                }

                if (confirm('¿Está seguro de que desea eliminar todos los productos de la orden?')) {
                    products = [];
                    updateProductsTable();
                    updateStats();
                    showNotification('🗑️ Todos los productos han sido eliminados', 'info');
                }
            });

            // Create order
            document.getElementById('create-order-btn').addEventListener('click', function() {
                if (products.length === 0) {
                    showNotification('❌ Debe agregar al menos un producto para crear la orden', 'error');
                    return;
                }

                // Simulate API call
                const btn = this;
                const originalText = btn.innerHTML;
                btn.innerHTML =
                    '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Creando Orden...';
                btn.disabled = true;

                setTimeout(() => {
                    showNotification('🎉 ¡Orden creada exitosamente! Redirigiendo...', 'success');
                    products = [];
                    updateProductsTable();
                    updateStats();
                    btn.innerHTML = originalText;
                    btn.disabled = false;

                    // Simulate redirect
                    setTimeout(() => {
                        window.location.href = "{{ route('solicitudrepuesto.index') }}";
                    }, 1500);
                }, 2000);
            });

            // Update products table
            function updateProductsTable() {
                const tbody = document.getElementById('products-table-body');
                const emptyState = document.getElementById('empty-state');

                if (products.length === 0) {
                    tbody.innerHTML = '';
                    tbody.appendChild(emptyState);
                    return;
                }

                emptyState.remove();

                tbody.innerHTML = products.map(product => `
                    <tr class="hover:bg-[#eaf1ff] transition-colors duration-200" data-product-id="${product.id}">
                        <td class="px-6 py-4 whitespace-nowrap text-base font-semibold text-[#0e1726]">${product.modelo}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-base text-[#3b3f5c]">${product.tipo}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-base text-[#4361ee] font-mono font-bold">${product.codigo}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-base text-[#0e1726]">
                            <div class="flex items-center space-x-3">
                                <span class="font-bold">${product.cantidad}</span>
                                <div class="flex space-x-1">
                                    <button onclick="updateQuantity(${product.id}, -1)" class="p-1 text-[#4361ee] hover:text-[#3b56e0] hover:bg-[#eaf1ff] rounded transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </button>
                                    <button onclick="updateQuantity(${product.id}, 1)" class="p-1 text-[#4361ee] hover:text-[#3b56e0] hover:bg-[#eaf1ff] rounded transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-base">
                            <button onclick="removeProduct(${product.id})" class="text-[#e7515a] hover:text-[#d9454e] font-semibold flex items-center space-x-2 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                <span>Eliminar</span>
                            </button>
                        </td>
                    </tr>
                `).join('');
            }

            // Update statistics
            function updateStats() {
                const totalProducts = products.length;
                const totalQuantity = products.reduce((sum, product) => sum + product.cantidad, 0);

                document.getElementById('product-count').textContent =
                    `${totalProducts} producto${totalProducts !== 1 ? 's' : ''}`;
                document.getElementById('total-products').textContent = totalProducts;
                document.getElementById('total-quantity').textContent = totalQuantity;
            }

            // Clear product form
            function clearProductForm() {
                $('#modelo-select').val('').trigger('change');
                $('#tipo-select').val('').trigger('change');
                $('#codigo-select').val('').trigger('change');
                cantidadInput.value = '1';
            }

            // Show notification
            function showNotification(message, type = 'info') {
                const notification = document.createElement('div');
                const bgColor = type === 'success' ? 'bg-[#00ab55]' :
                    type === 'error' ? 'bg-[#e7515a]' :
                    type === 'warning' ? 'bg-[#e2a03f]' : 'bg-[#2196f3]';

                notification.className =
                    `fixed top-6 right-6 ${bgColor} text-white px-6 py-4 rounded-xl shadow-2xl transform transition-all duration-300 translate-x-full z-50 max-w-sm`;
                notification.innerHTML = `
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            ${type === 'success' ? '✅' : type === 'error' ? '❌' : type === 'warning' ? '⚠️' : 'ℹ️'}
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium">${message}</p>
                        </div>
                    </div>
                `;

                document.body.appendChild(notification);

                // Animate in
                setTimeout(() => notification.classList.remove('translate-x-full'), 100);

                // Remove after 4 seconds
                setTimeout(() => {
                    notification.classList.add('translate-x-full');
                    setTimeout(() => notification.remove(), 300);
                }, 4000);
            }

            // Global functions for product actions
            window.removeProduct = function(productId) {
                products = products.filter(p => p.id !== productId);
                updateProductsTable();
                updateStats();
                showNotification('🗑️ Producto eliminado de la orden', 'info');
            };

            window.updateQuantity = function(productId, change) {
                const product = products.find(p => p.id === productId);
                if (product) {
                    const newQuantity = product.cantidad + change;
                    if (newQuantity >= 1 && newQuantity <= 100) {
                        product.cantidad = newQuantity;
                        updateProductsTable();
                        updateStats();
                    }
                }
            };
        });
    </script>
</x-layout.default>
