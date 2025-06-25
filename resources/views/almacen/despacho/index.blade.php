<x-layout.default>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header mejorado -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold mb-2">Documento de Salida</h1>
                <p class="max-w-2xl mx-auto">Gestiona la salida de documentos y productos con facilidad</p>
                <div class="mt-4 border-b border-gray-200"></div>
            </div>

            <form method="POST" action="#" class="space-y-8">
                @csrf

                <!-- Documento Info - Estilo mejorado -->
                <div
                    class="bg-white dark:bg-gray-900 shadow-md rounded-xl border border-gray-200 dark:border-gray-700 p-6 space-y-4">
                    <div class="flex items-center mb-4">
                        <!-- Icono alineado -->
                        <div class="flex items-center justify-center w-8 h-8 rounded-md bg-blue-50 mr-3">
                            <svg class="w-5 h-5 mt-[1px]" fill="none" stroke="#4361ee" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>

                        <!-- Título -->
                        <h3 class="text-base font-semibold mb-0" style="color: #4361ee;">INFORMACIÓN DEL DOCUMENTO</h3>
                    </div>




                    <div class="grid grid-cols-1 md:grid-cols-6 gap-3 text-xs">
                        <div class="col-span-2">
                            <label class="block mb-1">Tipo Guía</label>
                            <select name="guia_tipo"
                                class="text-xs w-full px-3 py-2 bg-white dark:bg-gray-800 text-black dark:text-white border border-gray-300 dark:border-gray-600 rounded-md focus:ring-1 focus:ring-blue-300 focus:border-blue-500 transition">
                                <option value="GR_Electronica_TI01">GR Electronica TI01</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1">Número</label>
                            <input type="text" name="numero" value="5778"
                                class="text-xs w-full px-3 py-2 bg-white dark:bg-gray-800 text-black dark:text-white border border-gray-300 dark:border-gray-600 rounded-md focus:ring-1 focus:ring-blue-300 focus:border-blue-500 transition">
                        </div>
                        <div>
                            <label class="block mb-1">F. Entrega</label>
                            <input type="date" name="fecha_entrega" value="2025-06-23"
                                class="text-xs w-full px-3 py-2 bg-white dark:bg-gray-800 text-black dark:text-white border border-gray-300 dark:border-gray-600 rounded-md focus:ring-1 focus:ring-blue-300 focus:border-blue-500 transition">
                        </div>
                        <div>
                            <label class="block mb-1">F. Traslado</label>
                            <input type="date" name="fecha_traslado" value="2025-06-23"
                                class="text-xs w-full px-3 py-2 bg-white dark:bg-gray-800 text-black dark:text-white border border-gray-300 dark:border-gray-600 rounded-md focus:ring-1 focus:ring-blue-300 focus:border-blue-500 transition">
                        </div>
                        <div>
                            <label class="block mb-1">Documento</label>
                            <select name="documento"
                                class="text-xs w-full px-3 py-2 bg-white dark:bg-gray-800 text-black dark:text-white border border-gray-300 dark:border-gray-600 rounded-md focus:ring-1 focus:ring-blue-300 focus:border-blue-500 transition">
                                <option value="factura">Factura</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Domicilios - Diseño vertical 2 columnas -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 text-xs">
                    <!-- Partida -->
                    <div class="bg-white dark:bg-gray-900 shadow-md rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center mb-5">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-green-50 mr-4">
                                <svg class="w-6 h-6 mt-[1px]" fill="none" stroke="#4361ee" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                            </div>
                            <h3 class="text-base font-semibold mb-0" style="color: #4361ee;">DIRECCIÓN DE PARTIDA</h3>
                        </div>
                
                        <!-- Dirección -->
                        <div class="mb-4">
                            <label class="block mb-1">Dirección</label>
                            <input type="text" name="direccion_partida"
                                value="AV SANTA ELVIRA E MZ B LOTE 8 URBA SAN ELÍAS"
                                class="text-xs w-full px-3 py-2 bg-white dark:bg-gray-800 text-black dark:text-white border border-gray-300 dark:border-gray-600 rounded-md focus:ring-1 focus:ring-blue-300 focus:border-blue-500 transition">
                        </div>
                
                        <!-- Dpto, Prov, Dist -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block mb-1">Departamento</label>
                                <select name="dpto_partida"
                                    class="text-xs w-full px-3 py-2 bg-white dark:bg-gray-800 text-black dark:text-white border border-gray-300 dark:border-gray-600 rounded-md focus:ring-1 focus:ring-blue-300 focus:border-blue-500 transition">
                                    <option value="Lima">Lima</option>
                                </select>
                            </div>
                            <div>
                                <label class="block mb-1">Provincia</label>
                                <select name="provincia_partida"
                                    class="text-xs w-full px-3 py-2 bg-white dark:bg-gray-800 text-black dark:text-white border border-gray-300 dark:border-gray-600 rounded-md focus:ring-1 focus:ring-blue-300 focus:border-blue-500 transition">
                                    <option value="Lima">Lima</option>
                                </select>
                            </div>
                            <div>
                                <label class="block mb-1">Distrito</label>
                                <select name="distrito_partida"
                                    class="text-xs w-full px-3 py-2 bg-white dark:bg-gray-800 text-black dark:text-white border border-gray-300 dark:border-gray-600 rounded-md focus:ring-1 focus:ring-blue-300 focus:border-blue-500 transition">
                                    <option value="Los Olivos">Los Olivos</option>
                                </select>
                            </div>
                        </div>
                    </div>
                
                    <!-- Llegada -->
                    <div class="bg-white dark:bg-gray-900 shadow-md rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center mb-5">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-purple-50 mr-4">
                                <svg class="w-6 h-6 mt-[1px]" fill="none" stroke="#4361ee" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                            </div>
                            <h3 class="text-base font-semibold mb-0" style="color: #4361ee;">DIRECCIÓN DE LLEGADA</h3>
                        </div>
                
                        <!-- Dirección -->
                        <div class="mb-4">
                            <label class="block mb-1">Dirección</label>
                            <input type="text" name="direccion_llegada" placeholder="Buena vista"
                                class="text-xs w-full px-3 py-2 bg-white dark:bg-gray-800 text-black dark:text-white border border-gray-300 dark:border-gray-600 rounded-md focus:ring-1 focus:ring-blue-300 focus:border-blue-500 transition">
                        </div>
                
                        <!-- Dpto, Prov, Dist -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block mb-1">Departamento</label>
                                <select name="dpto_llegada"
                                    class="text-xs w-full px-3 py-2 bg-white dark:bg-gray-800 text-black dark:text-white border border-gray-300 dark:border-gray-600 rounded-md focus:ring-1 focus:ring-blue-300 focus:border-blue-500 transition">
                                    <option value="Amazonas">Amazonas</option>
                                </select>
                            </div>
                            <div>
                                <label class="block mb-1">Provincia</label>
                                <select name="provincia_llegada"
                                    class="text-xs w-full px-3 py-2 bg-white dark:bg-gray-800 text-black dark:text-white border border-gray-300 dark:border-gray-600 rounded-md focus:ring-1 focus:ring-blue-300 focus:border-blue-500 transition">
                                    <option value="Bongara">Bongara</option>
                                </select>
                            </div>
                            <div>
                                <label class="block mb-1">Distrito</label>
                                <select name="distrito_llegada"
                                    class="text-xs w-full px-3 py-2 bg-white dark:bg-gray-800 text-black dark:text-white border border-gray-300 dark:border-gray-600 rounded-md focus:ring-1 focus:ring-blue-300 focus:border-blue-500 transition">
                                    <option value="Corosha">Corosha</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                


                <div class="bg-white dark:bg-gray-900 shadow-md rounded-xl border border-gray-200 dark:border-gray-700 p-6 space-y-4 text-xs">
                    <div class="flex items-center mb-5">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-50 mr-4">
                            <svg class="w-6 h-6 mt-[1px]" fill="none" stroke="#4361ee" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold mb-0" style="color: #4361ee;">CLIENTE & TRANSPORTE</h3>
                    </div>
                
                    <!-- Fila 1 -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block mb-1.5">Cliente</label>
                            <select name="cliente"
                                class="text-xs w-full px-3 py-2 bg-white dark:bg-gray-800 text-black dark:text-white border border-gray-300 dark:border-gray-600 rounded-md focus:ring-1 focus:ring-blue-300 focus:border-blue-500 transition">
                                <option value="network_industries">Network Industries Sac</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1.5">Vendedor</label>
                            <select name="vendedor"
                                class="text-xs w-full px-3 py-2 bg-white dark:bg-gray-800 text-black dark:text-white border border-gray-300 dark:border-gray-600 rounded-md focus:ring-1 focus:ring-blue-300 focus:border-blue-500 transition">
                                <option value="paulino_pascual">Paulino, Pascual, EFRAIN Rodrigo</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1.5">Trasbordo</label>
                            <select name="trasbordo"
                                class="text-xs w-full px-3 py-2 bg-white dark:bg-gray-800 text-black dark:text-white border border-gray-300 dark:border-gray-600 rounded-md focus:ring-1 focus:ring-blue-300 focus:border-blue-500 transition">
                                <option value="si">Sí</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                    </div>
                
                    <!-- Fila 2 -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block mb-1.5">Modo Traslado</label>
                            <select name="modo_traslado"
                                class="text-xs w-full px-3 py-2 bg-white dark:bg-gray-800 text-black dark:text-white border border-gray-300 dark:border-gray-600 rounded-md focus:ring-1 focus:ring-blue-300 focus:border-blue-500 transition">
                                <option value="publico">Público</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1.5">Conductor</label>
                            <input type="text" name="conductor_nombre" value="GKM TECHNOLOGY"
                                class="text-xs w-full px-3 py-2 bg-white dark:bg-gray-800 text-black dark:text-white border border-gray-300 dark:border-gray-600 rounded-md focus:ring-1 focus:ring-blue-300 focus:border-blue-500 transition">
                        </div>
                        <div>
                            <label class="block mb-1.5">Condiciones</label>
                            <select name="condiciones"
                                class="text-xs w-full px-3 py-2 bg-white dark:bg-gray-800 text-black dark:text-white border border-gray-300 dark:border-gray-600 rounded-md focus:ring-1 focus:ring-blue-300 focus:border-blue-500 transition">
                                <option value="contado">Contado</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1.5">Tipo Traslado</label>
                            <select name="tipo_traslado"
                                class="text-xs w-full px-3 py-2 bg-white dark:bg-gray-800 text-black dark:text-white border border-gray-300 dark:border-gray-600 rounded-md focus:ring-1 focus:ring-blue-300 focus:border-blue-500 transition">
                                <option value="venta_sujeta_confirmacion">Venta sujeta a confirmación</option>
                            </select>
                        </div>
                    </div>
                </div>
                


                <div class="bg-white dark:bg-gray-900 shadow-md rounded-xl border border-gray-200 dark:border-gray-700 p-6 space-y-4 overflow-hidden text-xs">
                    <!-- Encabezado -->
                    <div class="flex items-center px-6">
                        <div class="flex items-center justify-center w-8 h-8 rounded-md bg-blue-50 mr-3">
                            <svg class="w-5 h-5 mt-[1px]" fill="none" stroke="#4361ee" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 10h16M4 14h10M4 18h6" />
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold mb-0" style="color: #4361ee;">ARTÍCULOS</h3>
                    </div>
                
                    <!-- Barra de búsqueda -->
                    <div class="border-b border-gray-200 dark:border-gray-700 px-6">
                        <input type="text" placeholder="Buscar artículos..."
                            class="text-xs w-full px-3 py-2 bg-white dark:bg-gray-800 text-black dark:text-white border border-gray-300 dark:border-gray-600 rounded-md focus:ring-1 focus:ring-blue-300 focus:border-blue-500 transition">
                    </div>
                
                    <!-- Lista de artículos -->
                    <div class="space-y-4 px-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-gray-50 dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                            <div>
                                <label class="block mb-1 text-gray-600 dark:text-gray-300">Código</label>
                                <input type="text"
                                    class="text-xs w-full px-3 py-2 bg-white dark:bg-gray-800 text-black dark:text-white border border-gray-300 dark:border-gray-600 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="md:col-span-3">
                                <label class="block mb-1 text-gray-600 dark:text-gray-300">Descripción</label>
                                <input type="text" placeholder="Descripción"
                                    class="text-xs w-full px-3 py-2 bg-white dark:bg-gray-800 text-black dark:text-white border border-gray-300 dark:border-gray-600 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block mb-1 text-gray-600 dark:text-gray-300">Stock</label>
                                <input type="number"
                                    class="text-xs w-full px-3 py-2 bg-white dark:bg-gray-800 text-black dark:text-white border border-gray-300 dark:border-gray-600 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block mb-1 text-gray-600 dark:text-gray-300">Unidad</label>
                                <select
                                    class="text-xs w-full px-3 py-2 bg-white dark:bg-gray-800 text-black dark:text-white border border-gray-300 dark:border-gray-600 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                    <option>Unidad</option>
                                </select>
                            </div>
                            <div>
                                <label class="block mb-1 text-gray-600 dark:text-gray-300">P. Venta</label>
                                <input type="number" step="0.01" value="0.00"
                                    class="text-xs w-full px-3 py-2 bg-white dark:bg-gray-800 text-black dark:text-white border border-gray-300 dark:border-gray-600 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block mb-1 text-gray-600 dark:text-gray-300">Cant.</label>
                                <input type="number" value="0"
                                    class="text-xs w-full px-3 py-2 bg-white dark:bg-gray-800 text-black dark:text-white border border-gray-300 dark:border-gray-600 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="col-span-2 flex items-center justify-between mt-2">
                                <span class="text-xs font-semibold text-gray-800 dark:text-white">Total: S/ 0.00</span>
                                <button class="text-green-500 hover:text-green-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                
                    <!-- Totales -->
                    <div class="flex justify-end gap-10 px-6 py-4 border-t border-gray-200 dark:border-gray-700 text-right bg-gray-50 dark:bg-[#1c1f2e] text-xs">
                        <div>
                            <p class="text-gray-500 dark:text-gray-300">Subtotal</p>
                            <p class="font-semibold text-gray-800 dark:text-white">S/ <span id="subtotal">0.00</span></p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-300">IGV <span class="text-gray-400">(18%)</span></p>
                            <p class="font-semibold text-gray-800 dark:text-white">S/ <span id="igv">0.00</span></p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-300">Total</p>
                            <p class="font-bold text-blue-600 text-sm">S/ <span id="total">0.00</span></p>
                        </div>
                    </div>
                </div>
                






                <div class="flex flex-col sm:flex-row justify-center gap-4 pt-4">
                    <!-- Guardar -->
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check mr-2"></i> Guardar
                    </button>

                    <!-- Limpiar -->
                    <button type="reset" class="btn btn-warning">
                        <i class="fas fa-eraser mr-2"></i> Limpiar
                    </button>

                    <!-- Regresar -->
                    <button type="button" class="btn btn-dark">
                        <i class="fas fa-arrow-left mr-2"></i> Regresar
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
