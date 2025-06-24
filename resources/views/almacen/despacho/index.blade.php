<x-layout.default>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header mejorado -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Documento de Salida</h1>
                <p class="text-gray-600 max-w-2xl mx-auto">Gestiona la salida de documentos y productos con facilidad</p>
                <div class="mt-4 border-b border-gray-200"></div>
            </div>

            <form method="POST" action="#" class="space-y-8">
                @csrf
                
                <!-- Documento Info - Estilo mejorado -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center mb-5">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50 text-blue-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Información del Documento</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Tipo Guía</label>
                            <select name="guia_tipo" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all">
                                <option value="GR_Electronica_TI01">GR Electronica TI01</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Número</label>
                            <input type="text" name="numero" value="5778" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">F. Entrega</label>
                            <input type="date" name="fecha_entrega" value="2025-06-23" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">F. Traslado</label>
                            <input type="date" name="fecha_traslado" value="2025-06-23" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Documento</label>
                            <select name="documento" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all">
                                <option value="factura">Factura</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Domicilios - Diseño mejorado -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Partida -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center mb-5">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-green-50 text-green-600 mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">Dirección de Partida</h3>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Dirección</label>
                                <input type="text" name="direccion_partida" value="AV SANTA ELVIRA E MZ B LOTE 8 URBA SAN ELÍAS" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-green-50 focus:ring-2 focus:ring-green-200 focus:border-green-500 transition-all">
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Departamento</label>
                                    <select name="dpto_partida" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-green-500 transition-all">
                                        <option value="Lima">Lima</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Provincia</label>
                                    <select name="provincia_partida" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-green-500 transition-all">
                                        <option value="Lima">Lima</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Distrito</label>
                                    <select name="distrito_partida" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-green-500 transition-all">
                                        <option value="Los Olivos">Los Olivos</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Llegada -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center mb-5">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-purple-50 text-purple-600 mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">Dirección de Llegada</h3>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Dirección</label>
                                <input type="text" name="direccion_llegada" placeholder="Buena vista" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-200 focus:border-purple-500 transition-all">
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Departamento</label>
                                    <select name="dpto_llegada" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-200 focus:border-purple-500 transition-all">
                                        <option value="Amazonas">Amazonas</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Provincia</label>
                                    <select name="provincia_llegada" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-200 focus:border-purple-500 transition-all">
                                        <option value="Bongara">Bongara</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Distrito</label>
                                    <select name="distrito_llegada" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-200 focus:border-purple-500 transition-all">
                                        <option value="Corosha">Corosha</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cliente y Transporte - Diseño mejorado -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center mb-5">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-50 text-orange-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Cliente & Transporte</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Cliente</label>
                            <select name="cliente" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-200 focus:border-orange-500 transition-all">
                                <option value="network_industries">Network Industries Sac</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Vendedor</label>
                            <select name="vendedor" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-200 focus:border-orange-500 transition-all">
                                <option value="paulino_pascual">Paulino, Pascual, EFRAIN Rodrigo</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Trasbordo</label>
                            <select name="trasbordo" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-200 focus:border-orange-500 transition-all">
                                <option value="si">Si</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Modo Traslado</label>
                            <select name="modo_traslado" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all">
                                <option value="publico">Público</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Conductor</label>
                            <input type="text" name="conductor_nombre" value="GKM TECHNOLOGY" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Condiciones</label>
                            <select name="condiciones" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all">
                                <option value="contado">Contado</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Tipo Traslado</label>
                            <select name="tipo_traslado" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all">
                                <option value="venta_sujeta_confirmacion">Venta sujeta a confirmación</option>
                            </select>
                        </div>
                    </div>
                </div>

         <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <!-- Encabezado -->
    <div class="bg-blue-600 px-6 py-4">
        <h2 class="text-xl font-bold text-white">Artículos</h2>
    </div>
    
    <!-- Barra de búsqueda -->
    <div class="p-4 border-b border-gray-200">
        <div class="relative">
            <input type="text" placeholder="Buscar artículos..." class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-500">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Tabla de artículos -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">CÓDIGO</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">DESCRIPCIÓN</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">STOCK</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">UND</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">P.VENTA</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">CANT.</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">TOTAL</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">ACCIÓN</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="text" class="w-full px-2 py-1 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="text" placeholder="Descripción" class="w-full px-2 py-1 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="number" class="w-full px-2 py-1 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <select class="w-full px-2 py-1 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option>Und</option>
                        </select>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="number" step="0.01" value="0.00" class="w-full px-2 py-1 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm precio">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="number" value="0" class="w-full px-2 py-1 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm cantidad">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-blue-600 total-cell">
                        0.00
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <button class="text-green-600 hover:text-green-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <!-- Totales -->
    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
        <div class="flex justify-end space-x-8">
            <div class="text-right">
                <p class="text-sm text-gray-600">Subtotal:</p>
                <p class="text-lg font-semibold text-gray-800">S/ <span id="subtotal">0.00</span></p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">IGV (18%):</p>
                <p class="text-lg font-semibold text-gray-800">S/ <span id="igv">0.00</span></p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Total:</p>
                <p class="text-xl font-bold text-blue-600">S/ <span id="total">0.00</span></p>
            </div>
        </div>
    </div>
</div>

           <div class="flex flex-col sm:flex-row justify-center gap-4 pt-4">
    <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold flex items-center justify-center shadow-md hover:shadow-lg">
        <i class="fas fa-check mr-2"></i>
        Guardar Documento
    </button>
    <button type="button" class="px-8 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors font-semibold flex items-center justify-center shadow-md hover:shadow-lg">
        <i class="fas fa-trash-alt mr-2"></i>
        Limpiar Formulario
    </button>
    <button type="button" class="px-8 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-semibold flex items-center justify-center shadow-md hover:shadow-lg">
        <i class="fas fa-arrow-left mr-2"></i>
        Regresar
    </button>
</div>
            </form>
        </div>
    </div>

    <!-- JavaScript mejorado -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let rowCount = 1;
            
            // Función para calcular todos los totales
            function calcularTotales() {
                let subtotal = 0;
                
                document.querySelectorAll('tbody tr').forEach(row => {
                    const precio = parseFloat(row.querySelector('.precio').value) || 0;
                    const cantidad = parseFloat(row.querySelector('.cantidad').value) || 0;
                    const total = precio * cantidad;
                    
                    row.querySelector('.total-cell span').textContent = total.toFixed(2);
                    subtotal += total;
                });
                
                const igv = subtotal * 0.18;
                const total = subtotal + igv;
                
                document.getElementById('subtotal').textContent = subtotal.toFixed(2);
                document.getElementById('igv').textContent = igv.toFixed(2);
                document.getElementById('total').textContent = total.toFixed(2);
            }
            
            // Agregar filas
            document.addEventListener('click', function(e) {
                if (e.target.closest('.add-row')) {
                    const tbody = document.querySelector('tbody');
                    const newRow = tbody.querySelector('tr').cloneNode(true);
                    
                    // Limpiar inputs y actualizar names
                    newRow.querySelectorAll('input, select').forEach(input => {
                        if (input.name.includes('[')) {
                            input.name = input.name.replace(/\[0\]/, `[${rowCount}]`);
                        }
                        if (input.type !== 'hidden') input.value = '';
                    });
                    
                    // Cambiar botón a eliminar
                    const btn = newRow.querySelector('button');
                    btn.classList.remove('add-row');
                    btn.classList.add('remove-row', 'text-red-600', 'hover:text-red-800');
                    btn.innerHTML = `
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    `;
                    
                    tbody.appendChild(newRow);
                    rowCount++;
                }
                
                // Eliminar filas
                if (e.target.closest('.remove-row')) {
                    if (document.querySelectorAll('tbody tr').length > 1) {
                        e.target.closest('tr').remove();
                        calcularTotales();
                    } else {
                        alert('Debe haber al menos una fila de artículo');
                    }
                }
            });
            
            // Calcular totales cuando cambian precios o cantidades
            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('precio') || e.target.classList.contains('cantidad')) {
                    calcularTotales();
                }
            });
            
            // Calcular totales iniciales
            calcularTotales();
        });
    </script>
</x-layout.default>