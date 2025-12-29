<x-layout.default>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Breadcrumb -->
    <div class="mx-auto w-full px-4 py-6">
        <div class="mb-4">
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="{{ route('asignar-articulos.index') }}" class="text-primary hover:underline">
                        Asignación de Artículos
                    </a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span class="font-medium text-gray-700">Nueva Asignación</span>
                </li>
            </ul>
        </div>

        <!-- Header -->
        <div class="panel mb-8 p-6 rounded-2xl shadow-lg border-0 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 bg-gradient-to-br from-primary to-primary/80 rounded-xl shadow-lg flex items-center justify-center">
                    <i class="fas fa-plus text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Nueva Asignación</h1>
                    <p class="text-gray-600 mt-1">Asignar artículos a usuario del sistema</p>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Sección principal del formulario -->
            <div class="lg:col-span-2">
                <div class="panel rounded-2xl shadow-lg border-0 bg-white p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 pb-4 border-b border-gray-100">
                        <i class="fas fa-info-circle text-primary mr-2"></i>
                        Información de la Asignación
                    </h3>

                    <div class="space-y-6">
                        <!-- Usuario -->
                        <div class="space-y-3 group">
                            <label class="block text-sm font-semibold text-gray-700 flex items-center">
                                <div class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center mr-2">
                                    <i class="fas fa-user text-primary text-sm"></i>
                                </div>
                                Usuario
                            </label>
                            <div class="relative">
                                <select id="usuarioSelect"
                                    class="w-full px-4 py-3.5 bg-white border-2 border-gray-200 rounded-xl hover:border-primary/40 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-200 appearance-none shadow-sm focus:shadow-md cursor-pointer">
                                    <option value="" class="text-gray-400">Seleccionar usuario...</option>
                                    @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->idUsuario }}" class="text-gray-700">
                                        {{ $usuario->nombre_completo }} - {{ $usuario->correo }}
                                    </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Lista de Artículos -->
                        <div class="space-y-3 group">
                            <div class="flex items-center justify-between">
                                <label class="block text-sm font-semibold text-gray-700 flex items-center">
                                    <div class="w-8 h-8 bg-success/10 rounded-lg flex items-center justify-center mr-2">
                                        <i class="fas fa-boxes text-success text-sm"></i>
                                    </div>
                                    Artículos a asignar
                                </label>
                                <button type="button" id="agregarArticuloBtn"
                                    class="px-3 py-1.5 bg-success text-white text-sm font-medium rounded-lg hover:bg-success-dark transition-all duration-200 flex items-center">
                                    <i class="fas fa-plus mr-1"></i> Agregar artículo
                                </button>
                            </div>

                            <!-- Contenedor de artículos con altura fija y scroll condicional -->
                            <div id="articulosContainer" class="articulos-scroll-container">
                                <!-- Artículo inicial -->
                                <div class="articulo-item bg-gray-50 rounded-xl p-4 border border-gray-200">
                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                        <!-- Artículo -->
                                        <div class="md:col-span-6">
                                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                                Artículo
                                            </label>
                                            <select class="articulo-select w-full px-3 py-2.5 bg-white border border-gray-300 rounded-lg focus:border-success focus:ring-2 focus:ring-success/20 transition-all duration-200">
                                                <option value="" class="text-gray-400">Buscar artículo...</option>
                                                @foreach($articulos as $articulo)
                                                @if($articulo->stock_disponible > 0)
                                                <option value="{{ $articulo->idArticulos }}" 
                                                    data-stock="{{ $articulo->stock_disponible }}"
                                                    data-maneja-serie="{{ $articulo->maneja_serie }}"
                                                    data-precio="{{ $articulo->precio_venta ?? 0 }}">
                                                    {{ $articulo->nombre }} (Stock: {{ $articulo->stock_disponible }})
                                                </option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Cantidad -->
                                        <div class="md:col-span-4">
                                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                                Cantidad
                                            </label>
                                            <div class="flex items-center">
                                                <input type="number" min="1" value="1" max="1"
                                                    class="articulo-cantidad w-full px-3 py-2.5 bg-white border border-gray-300 rounded-lg focus:border-warning focus:ring-2 focus:ring-warning/20 transition-all duration-200">
                                                <div class="ml-2 text-xs text-gray-500 stock-info">
                                                    Stock: <span class="font-medium">0</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Botón eliminar -->
                                        <div class="md:col-span-2 flex items-end">
                                            <button type="button" class="eliminar-articulo-btn w-full px-3 py-2.5 bg-danger text-white rounded-lg hover:bg-danger-dark transition-all duration-200 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                                <i class="fas fa-trash mr-1"></i> Quitar
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Número de serie -->
                                    <div class="mt-3 articulo-serie hidden">
                                        <label class="block text-xs font-medium text-gray-600 mb-1">
                                            Número de serie
                                        </label>
                                        <input type="text" class="articulo-serie-input w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:border-info focus:ring-2 focus:ring-info/20 transition-all duration-200"
                                            placeholder="Número de serie del artículo...">
                                        <p class="text-xs text-gray-500 mt-1">Este artículo requiere número de serie</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Total de artículos -->
                            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                                <span class="text-sm font-medium text-gray-700">Total de artículos:</span>
                                <span id="totalArticulos" class="text-lg font-bold text-primary">0</span>
                            </div>
                        </div>

                        <!-- Cantidad y Fecha -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-3 group">
                                <label class="block text-sm font-semibold text-gray-700">
                                    <i class="fas fa-calendar-alt text-info mr-2"></i>
                                    Fecha de Asignación
                                </label>
                                <div class="relative">
                                    <input type="date" id="fechaAsignacion"
                                        class="w-full px-4 py-3.5 bg-white border-2 border-gray-200 rounded-xl hover:border-info/40 focus:border-info focus:ring-2 focus:ring-info/20 transition-all duration-200 shadow-sm focus:shadow-md">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-calendar text-gray-400"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3 group">
                                <label class="block text-sm font-semibold text-gray-700">
                                    <i class="fas fa-calendar-check text-warning mr-2"></i>
                                    Fecha de Devolución (Opcional)
                                </label>
                                <div class="relative">
                                    <input type="date" id="fechaDevolucion"
                                        class="w-full px-4 py-3.5 bg-white border-2 border-gray-200 rounded-xl hover:border-warning/40 focus:border-warning focus:ring-2 focus:ring-warning/20 transition-all duration-200 shadow-sm focus:shadow-md">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-calendar text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Observaciones -->
                        <div class="space-y-3 group">
                            <label class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-sticky-note text-secondary mr-2"></i>
                                Observaciones
                            </label>
                            <textarea id="observaciones" rows="4"
                                class="w-full px-4 py-3.5 bg-white border-2 border-gray-200 rounded-xl hover:border-secondary/40 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all duration-200 shadow-sm focus:shadow-md resize-none"
                                placeholder="Notas adicionales sobre esta asignación..."></textarea>
                        </div>

                        <!-- Botones de acción -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-100">
                            <a href="{{ route('asignar-articulos.index') }}"
                                class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all duration-200 shadow-sm hover:shadow flex items-center">
                                <i class="fas fa-times mr-2"></i> Cancelar
                            </a>
                            <button id="crearAsignacionBtn"
                                class="px-5 py-2.5 bg-primary text-white font-semibold rounded-xl hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary transition-all duration-200 shadow-md hover:shadow-lg flex items-center group">
                                <i class="fas fa-check mr-2 group-hover:animate-pulse"></i> Crear Asignación
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel lateral informativo -->
            <div class="space-y-6">
                <!-- Resumen rápido -->
                <div class="panel rounded-2xl shadow-lg border-0 bg-white p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b border-gray-100">
                        <i class="fas fa-clipboard-check text-primary mr-2"></i>
                        Resumen de Asignación
                    </h3>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <span class="text-gray-600">Usuario:</span>
                            <span id="resumenUsuario" class="font-semibold text-gray-900">-</span>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-xl">
                            <div class="mb-2">
                                <span class="text-gray-600">Artículos:</span>
                            </div>
                            <ul id="resumenArticulos" class="space-y-3">
                                <li class="text-sm text-gray-500 italic">Ningún artículo seleccionado</li>
                            </ul>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <span class="text-gray-600">Total artículos:</span>
                            <span id="resumenTotal" class="font-bold text-primary">0</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <span class="text-gray-600">Valor total:</span>
                            <span id="resumenValor" class="font-bold text-success">S/0.00</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <span class="text-gray-600">Fecha asignación:</span>
                            <span id="resumenFecha" class="font-semibold text-gray-900">Hoy</span>
                        </div>
                    </div>
                </div>

                <!-- Consejos -->
                <div class="panel rounded-2xl shadow-lg border-0 bg-gradient-to-br from-blue-50 to-white p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="fas fa-lightbulb text-warning mr-2"></i>
                        Recomendaciones
                    </h3>

                    <div class="space-y-3">
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-check-circle text-success mt-1"></i>
                            <p class="text-sm text-gray-600">Verifica el stock disponible del artículo</p>
                        </div>
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-check-circle text-success mt=1"></i>
                            <p class="text-sm text-gray-600">Asigna fechas realistas de devolución</p>
                        </div>
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-check-circle text-success mt-1"></i>
                            <p class="text-sm text-gray-600">Documenta el estado del artículo</p>
                        </div>
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-check-circle text-success mt-1"></i>
                            <p class="text-sm text-gray-600">Verifica el número de serie cuando aplique</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Contenedor dinámico con scroll condicional */
        .articulos-scroll-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            max-height: 400px;
            overflow-y: auto;
            padding-right: 8px;
            transition: max-height 0.3s ease;
        }

        .articulos-scroll-container::-webkit-scrollbar {
            width: 6px;
        }
        
        .articulos-scroll-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .articulos-scroll-container::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        
        .articulos-scroll-container::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }
        
        .articulos-scroll-container {
            scrollbar-width: thin;
            scrollbar-color: #c1c1c1 #f1f1f1;
        }

        .articulos-scroll-container.scroll-activo {
            max-height: 400px;
            overflow-y: auto;
        }

        .articulos-scroll-container.scroll-inactivo {
            max-height: none;
            overflow-y: visible;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elementos principales
            const usuarioSelect = document.getElementById('usuarioSelect');
            const articulosContainer = document.getElementById('articulosContainer');
            const agregarArticuloBtn = document.getElementById('agregarArticuloBtn');
            const fechaAsignacion = document.getElementById('fechaAsignacion');
            const fechaDevolucion = document.getElementById('fechaDevolucion');
            const totalArticulosSpan = document.getElementById('totalArticulos');
            
            // Elementos del resumen
            const resumenUsuario = document.getElementById('resumenUsuario');
            const resumenArticulos = document.getElementById('resumenArticulos');
            const resumenTotal = document.getElementById('resumenTotal');
            const resumenValor = document.getElementById('resumenValor');
            const resumenFecha = document.getElementById('resumenFecha');

            // Establecer fecha por defecto
            const today = new Date().toISOString().split('T')[0];
            fechaAsignacion.value = today;

            // Contador de artículos
            let contadorArticulos = 1;

            // Función para actualizar el scroll
            function actualizarScroll() {
                const articulosItems = document.querySelectorAll('.articulo-item');
                const container = document.getElementById('articulosContainer');
                
                let alturaTotal = 0;
                articulosItems.forEach(item => {
                    alturaTotal += item.offsetHeight + 16;
                });
                
                if (alturaTotal > 400) {
                    container.classList.remove('scroll-inactivo');
                    container.classList.add('scroll-activo');
                } else {
                    container.classList.remove('scroll-activo');
                    container.classList.add('scroll-inactivo');
                }
            }

            // Función para actualizar el stock y número de serie
            function actualizarArticulo(selectElement) {
                const cantidadInput = selectElement.closest('.articulo-item').querySelector('.articulo-cantidad');
                const stockSpan = selectElement.closest('.articulo-item').querySelector('.stock-info span');
                const serieContainer = selectElement.closest('.articulo-item').querySelector('.articulo-serie');
                
                if (selectElement.value) {
                    const selectedOption = selectElement.options[selectElement.selectedIndex];
                    const stock = parseInt(selectedOption.getAttribute('data-stock')) || 0;
                    const manejaSerie = selectedOption.getAttribute('data-maneja-serie') == 1;
                    
                    stockSpan.textContent = stock;
                    cantidadInput.max = stock;
                    
                    // Mostrar campo de número de serie si el artículo lo requiere
                    if (manejaSerie) {
                        serieContainer.classList.remove('hidden');
                    } else {
                        serieContainer.classList.add('hidden');
                    }
                } else {
                    stockSpan.textContent = '0';
                    cantidadInput.max = 1;
                    serieContainer.classList.add('hidden');
                }
            }

            // Función para actualizar el resumen
            function actualizarResumen() {
                // Actualizar usuario
                if (usuarioSelect.value) {
                    const usuarioNombre = usuarioSelect.options[usuarioSelect.selectedIndex].text.split(' - ')[0];
                    resumenUsuario.textContent = usuarioNombre;
                    resumenUsuario.classList.remove('text-gray-500');
                    resumenUsuario.classList.add('text-primary', 'font-semibold');
                } else {
                    resumenUsuario.textContent = '-';
                    resumenUsuario.classList.remove('text-primary', 'font-semibold');
                    resumenUsuario.classList.add('text-gray-500');
                }

                // Actualizar artículos en el resumen
                const articulosItems = document.querySelectorAll('.articulo-item');
                let totalItems = 0;
                let totalValor = 0;
                
                if (articulosItems.length > 0) {
                    resumenArticulos.innerHTML = '';
                    
                    articulosItems.forEach((item, index) => {
                        const select = item.querySelector('.articulo-select');
                        const cantidadInput = item.querySelector('.articulo-cantidad');
                        const serieInput = item.querySelector('.articulo-serie-input');
                        
                        if (select.value && cantidadInput.value) {
                            const articuloNombre = select.options[select.selectedIndex].text;
                            const cantidad = parseInt(cantidadInput.value) || 0;
                            const serie = serieInput ? serieInput.value : '';
                            const precio = parseFloat(select.options[select.selectedIndex].getAttribute('data-precio')) || 0;
                            
                            totalItems += cantidad;
                            totalValor += cantidad * precio;
                            
                            const li = document.createElement('li');
                            li.className = 'space-y-1';
                            
                            // Información del artículo
                            const articuloInfo = document.createElement('div');
                            articuloInfo.className = 'flex items-start justify-between';
                            
                            const nombreSpan = document.createElement('span');
                            nombreSpan.className = 'text-sm text-gray-700 truncate';
                            nombreSpan.textContent = articuloNombre;
                            
                            const cantidadSpan = document.createElement('span');
                            cantidadSpan.className = 'font-medium text-success ml-2 whitespace-nowrap';
                            cantidadSpan.textContent = `x${cantidad}`;
                            
                            articuloInfo.appendChild(nombreSpan);
                            articuloInfo.appendChild(cantidadSpan);
                            
                            // Número de serie (si existe)
                            if (serie) {
                                const serieDiv = document.createElement('div');
                                serieDiv.className = 'text-xs text-info bg-info/10 rounded-lg px-2 py-1 mt-1';
                                
                                const serieLabel = document.createElement('span');
                                serieLabel.className = 'font-medium mr-1';
                                serieLabel.textContent = 'Serie:';
                                
                                const serieValue = document.createElement('span');
                                serieValue.className = 'font-mono';
                                serieValue.textContent = serie;
                                
                                serieDiv.appendChild(serieLabel);
                                serieDiv.appendChild(serieValue);
                                
                                li.appendChild(articuloInfo);
                                li.appendChild(serieDiv);
                            } else {
                                li.appendChild(articuloInfo);
                            }
                            
                            resumenArticulos.appendChild(li);
                        }
                    });
                    
                    if (totalItems === 0) {
                        const li = document.createElement('li');
                        li.className = 'text-sm text-gray-500 italic';
                        li.textContent = 'Ningún artículo seleccionado';
                        resumenArticulos.appendChild(li);
                    }
                } else {
                    resumenArticulos.innerHTML = '<li class="text-sm text-gray-500 italic">Ningún artículo seleccionado</li>';
                }

                // Actualizar totales
                resumenTotal.textContent = totalItems;
                resumenValor.textContent = 'S/' + totalValor.toFixed(2);
                totalArticulosSpan.textContent = totalItems;

                // Actualizar fecha
                if (fechaAsignacion.value) {
                    const fecha = new Date(fechaAsignacion.value);
                    const hoy = new Date();
                    
                    if (fecha.toDateString() === hoy.toDateString()) {
                        resumenFecha.textContent = 'Hoy';
                    } else {
                        resumenFecha.textContent = fechaAsignacion.value;
                    }
                    resumenFecha.classList.remove('text-gray-500');
                    resumenFecha.classList.add('text-info', 'font-semibold');
                } else {
                    resumenFecha.textContent = 'Hoy';
                    resumenFecha.classList.remove('text-info', 'font-semibold');
                    resumenFecha.classList.add('text-gray-500');
                }
            }

            // Función para agregar nuevo artículo
            function agregarArticulo() {
                contadorArticulos++;
                
                const nuevoArticulo = document.createElement('div');
                nuevoArticulo.className = 'articulo-item bg-gray-50 rounded-xl p-4 border border-gray-200';
                nuevoArticulo.innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <!-- Artículo -->
                        <div class="md:col-span-6">
                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                Artículo
                            </label>
                            <select class="articulo-select w-full px-3 py-2.5 bg-white border border-gray-300 rounded-lg focus:border-success focus:ring-2 focus:ring-success/20 transition-all duration-200">
                                <option value="" class="text-gray-400">Buscar artículo...</option>
                                @foreach($articulos as $articulo)
                                @if($articulo->stock_disponible > 0)
                                <option value="{{ $articulo->idArticulos }}" 
                                    data-stock="{{ $articulo->stock_disponible }}"
                                    data-maneja-serie="{{ $articulo->maneja_serie }}"
                                    data-precio="{{ $articulo->precio_venta ?? 0 }}">
                                    {{ $articulo->nombre }} (Stock: {{ $articulo->stock_disponible }})
                                </option>
                                @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- Cantidad -->
                        <div class="md:col-span-4">
                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                Cantidad
                            </label>
                            <div class="flex items-center">
                                <input type="number" min="1" value="1" max="1"
                                    class="articulo-cantidad w-full px-3 py-2.5 bg-white border border-gray-300 rounded-lg focus:border-warning focus:ring-2 focus:ring-warning/20 transition-all duration-200">
                                <div class="ml-2 text-xs text-gray-500 stock-info">
                                    Stock: <span class="font-medium">0</span>
                                </div>
                            </div>
                        </div>

                        <!-- Botón eliminar -->
                        <div class="md:col-span-2 flex items-end">
                            <button type="button" class="eliminar-articulo-btn w-full px-3 py-2.5 bg-danger text-white rounded-lg hover:bg-danger-dark transition-all duration-200 flex items-center justify-center">
                                <i class="fas fa-trash mr-1"></i> Quitar
                            </button>
                        </div>
                    </div>

                    <!-- Número de serie -->
                    <div class="mt-3 articulo-serie hidden">
                        <label class="block text-xs font-medium text-gray-600 mb-1">
                            Número de serie
                        </label>
                        <input type="text" class="articulo-serie-input w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:border-info focus:ring-2 focus:ring-info/20 transition-all duration-200"
                            placeholder="Número de serie del artículo...">
                        <p class="text-xs text-gray-500 mt-1">Este artículo requiere número de serie</p>
                    </div>
                `;

                articulosContainer.appendChild(nuevoArticulo);
                
                // Actualizar botones eliminar
                actualizarBotonesEliminar();
                actualizarResumen();
                actualizarScroll();
            }

            // Función para actualizar botones eliminar
            function actualizarBotonesEliminar() {
                const botonesEliminar = document.querySelectorAll('.eliminar-articulo-btn');
                botonesEliminar.forEach((btn, index) => {
                    if (botonesEliminar.length === 1) {
                        btn.disabled = true;
                        btn.classList.add('disabled:opacity-50', 'disabled:cursor-not-allowed');
                    } else {
                        btn.disabled = false;
                        btn.classList.remove('disabled:opacity-50', 'disabled:cursor-not-allowed');
                    }
                    
                    // Remover evento anterior si existe
                    btn.onclick = null;
                    
                    // Agregar nuevo evento
                    btn.onclick = function() {
                        if (botonesEliminar.length > 1) {
                            this.closest('.articulo-item').remove();
                            actualizarBotonesEliminar();
                            actualizarResumen();
                            actualizarScroll();
                        }
                    };
                });
            }

            // Event Listeners
            agregarArticuloBtn.addEventListener('click', agregarArticulo);
            usuarioSelect.addEventListener('change', actualizarResumen);
            fechaAsignacion.addEventListener('change', actualizarResumen);
            fechaDevolucion.addEventListener('change', actualizarResumen);

            // Event delegation para artículos dinámicos
            articulosContainer.addEventListener('change', function(e) {
                if (e.target.classList.contains('articulo-select')) {
                    actualizarArticulo(e.target);
                    actualizarResumen();
                }
                if (e.target.classList.contains('articulo-cantidad')) {
                    actualizarResumen();
                }
            });

            articulosContainer.addEventListener('input', function(e) {
                if (e.target.classList.contains('articulo-serie-input')) {
                    actualizarResumen();
                }
            });

            // Inicializar
            actualizarBotonesEliminar();
            actualizarResumen();
            actualizarScroll();
            
            // Inicializar el primer artículo
            const primerSelect = document.querySelector('.articulo-select');
            if (primerSelect) {
                actualizarArticulo(primerSelect);
            }

            // Validar y crear la asignación
            document.getElementById('crearAsignacionBtn').addEventListener('click', function() {
                if (!usuarioSelect.value) {
                    alert('Por favor, selecciona un usuario.');
                    usuarioSelect.focus();
                    return;
                }
                
                // Recolectar datos
                const articulosData = [];
                let error = false;
                
                document.querySelectorAll('.articulo-item').forEach(item => {
                    const select = item.querySelector('.articulo-select');
                    const cantidadInput = item.querySelector('.articulo-cantidad');
                    const serieInput = item.querySelector('.articulo-serie-input');
                    
                    if (select.value) {
                        const selectedOption = select.options[select.selectedIndex];
                        const stock = parseInt(selectedOption.getAttribute('data-stock')) || 0;
                        const cantidad = parseInt(cantidadInput.value) || 1;
                        const manejaSerie = selectedOption.getAttribute('data-maneja-serie') == 1;
                        
                        if (cantidad > stock) {
                            error = true;
                            cantidadInput.classList.add('border-danger');
                            alert(`La cantidad para "${selectedOption.text}" excede el stock disponible (${stock})`);
                        } else {
                            cantidadInput.classList.remove('border-danger');
                        }
                        
                        if (manejaSerie && (!serieInput || !serieInput.value.trim())) {
                            error = true;
                            alert(`El artículo "${selectedOption.text}" requiere número de serie`);
                        }
                        
                        // En la función del botón "Crear Asignación", actualiza:
articulosData.push({
    articulo_id: select.value, // Cambiado a 'articulo_id'
    cantidad: cantidad,
    numero_serie: serieInput ? serieInput.value.trim() : null
});
                    }
                });
                
                if (error) return;
                
                if (articulosData.length === 0) {
                    alert('Por favor, agrega al menos un artículo.');
                    return;
                }
                
                // Enviar datos
                const btn = this;
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Creando...';
                
                fetch('{{ route("asignar-articulos.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        idUsuario: usuarioSelect.value,
                        fecha_asignacion: fechaAsignacion.value,
                        fecha_devolucion: fechaDevolucion.value || null,
                        observaciones: document.getElementById('observaciones').value,
                        articulos: articulosData
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.location.href = data.redirect;
                    } else {
                        alert(data.message);
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-check mr-2"></i> Crear Asignación';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al crear la asignación');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-check mr-2"></i> Crear Asignación';
                });
            });
        });
    </script>
</x-layout.default>