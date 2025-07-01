<x-layout.default>

   <style>
       .clean-input {
            border: none;
            border-bottom: 1px solid #e0e6ed;
            border-radius: 0;
            padding-left: 35px; /* asegúrate de dejar espacio al ícono */
            padding-bottom: 8px;
            padding-top: 8px;
            background-color: transparent;
            height: 40px; /* controla la altura si es necesario */
            line-height: 1.25rem;
            font-size: 0.875rem;
        }

        .clean-input:focus {
            border-bottom: 2px solid #3b82f6;
            box-shadow: none;
        }
        .input-icon {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            color: #6b7280;
            font-size: 14px;
            pointer-events: none;
            z-index: 10;
        }
        .select2-container--default .select2-selection--single {
            background-color: transparent !important;
            border: none !important;
            border-bottom: 1px solid #e0e6ed !important;
            border-radius: 0 !important;
            height: 40px !important;
            padding-left: 35px !important;
            display: flex;
            align-items: center;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-bottom: 2px solid #3b82f6;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 6px !important;
            right: 10px !important;
        }

          
        .file-input-label {
            display: block;
            margin-top: 5px;
            color: #6b7280;
            font-size: 0.875rem;
        }

        /* Estilos para inputs con íconos */
        .input-with-icon {
            position: relative;
            margin-bottom: 1.5rem; /* Espacio para mensajes de error */
        }

        .input-with-icon .clean-input {
            padding-left: 35px !important; /* Forzar espacio para el ícono */
        }

        .input-with-icon .input-icon {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            z-index: 10;
            pointer-events: none;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: inherit !important;
        }
        /* Estilos para mensajes de error */
        .error-msg, .error-msg-duplicado {
            position: absolute;
            bottom: -1.25rem;
            left: 0;
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        /* Estilos para campos inválidos */
        .border-red-500 {
            border-color: #ef4444 !important;
        }
        .clean-input::placeholder {
            font-size: 0.85rem;
            /* o 0.75rem si lo quieres aún más pequeño */
        }
    </style>

    <div class="container mx-auto px-4 py-6" x-data="compra">
        <!-- Encabezado -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">NUEVA COMPRA</h1>
            <p class="text-gray-600">
                En el módulo COMPRAS usted podrá registrar compras de productos ya sea nuevos o ya registrados en sistema. 
                También puede ver la lista de todas las compras realizadas, buscar compras y ver información más detallada de cada compra.
            </p>
            
            <!-- Pestañas -->
            <div class="flex border-b border-gray-200 mt-6">
                <button class="px-4 py-2 font-medium text-blue-600 border-b-2 border-blue-600">NUEVA COMPRA</button>
                <button class="px-4 py-2 font-medium text-gray-500 hover:text-blue-500">COMPRAS REALIZADAS</button>
                <button class="px-4 py-2 font-medium text-gray-500 hover:text-blue-500">BUSCAR COMPRA</button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Sección izquierda - Búsqueda y productos -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">Registro de Productos</h2>
                    <p class="text-gray-600 mb-6">
                        Ingrese el código de barras del producto y luego haga clic en "Verificar producto" para cargar los datos en caso el producto ya esté registrado, 
                        en caso contrario se cargará el formulario para registrar un nuevo producto.
                    </p>
                    
                    <!-- Búsqueda por código de barras con modal -->
                    <div class="flex gap-2 mb-8">
                        <div class="flex-grow">
                            <input 
                                type="text" 
                                class="clean-input w-full text-lg py-3 px-4 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Código de barras"
                                x-model="codigoBarras"
                                @keyup.enter="abrirModalVerificacion"
                            />
                        </div>
                        <button 
                            class="bg-blue-600 hover:bg-blue-700 text-black px-4 py-2 rounded-lg flex items-center gap-2 transition-colors"
                            @click="abrirModalVerificacion"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                            VERIFICAR PRODUCTO
                        </button>
                    </div>
                    
                    <!-- Tabla de productos -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código de barras</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remover</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <template x-if="productos.length === 0">
                                    <tr>
                                        <td colspan="7" class="px-4 py-4 text-center text-gray-500">No hay productos agregados</td>
                                    </tr>
                                </template>
                                
                                <template x-for="(producto, index) in productos" :key="producto.id">
                                    <tr>
                                        <td class="px-4 py-4 whitespace-nowrap" x-text="index + 1"></td>
                                        <td class="px-4 py-4 whitespace-nowrap" x-text="producto.codigo_barras"></td>
                                        <td class="px-4 py-4 whitespace-nowrap" x-text="producto.nombre"></td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <input 
                                                type="number" 
                                                class="w-20 border border-gray-300 rounded px-2 py-1 text-center"
                                                x-model="producto.cantidad"
                                                @change="actualizarSubtotal(producto)"
                                                min="1"
                                            >
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <input 
                                                type="number" 
                                                step="0.01"
                                                class="w-24 border border-gray-300 rounded px-2 py-1 text-right"
                                                x-model="producto.precio"
                                                @change="actualizarSubtotal(producto)"
                                            >
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-right" x-text="formatCurrency(producto.subtotal)"></td>
                                        <td class="px-4 py-4 whitespace-nowrap text-center">
                                            <button 
                                                class="text-red-500 hover:text-red-700"
                                                @click="removerProducto(index)"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Sección derecha - Datos de la compra -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-4">
                    <h2 class="text-lg font-semibold mb-4">DATOS DE LA COMPRA</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                            <input 
                                type="date" 
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500"
                                x-model="fecha"
                            >
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Proveedor <span class="text-red-500">*</span></label>
                            <select 
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500"
                                x-model="proveedorId"
                            >
                                <option value="">Seleccione una opción</option>
                                <template x-for="proveedor in proveedores" :key="proveedor.id">
                                    <option :value="proveedor.id" x-text="proveedor.nombre"></option>
                                </template>
                            </select>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="font-medium" x-text="formatCurrency(subtotal)"></span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">IGV (18%):</span>
                                <span class="font-medium" x-text="formatCurrency(itbis)"></span>
                            </div>
                            <div class="flex justify-between text-lg font-semibold">
                                <span class="text-gray-700">Total:</span>
                                <span class="text-blue-600" x-text="formatCurrency(total)"></span>
                            </div>
                        </div>
                        
                        <div class="pt-4">
                            <button 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg"
                                :disabled="!puedeGuardar"
                                :class="{'opacity-50 cursor-not-allowed': !puedeGuardar}"
                                @click="guardarCompra"
                            >
                                GUARDAR COMPRA
                            </button>
                        </div>
                        
                        <p class="text-xs text-gray-500">
                            Los campos marcados con <span class="text-red-500">*</span> son obligatorios
                        </p>
                    </div>
                </div>
            </div>
        </div>

<div x-show="modalAbierto" class="fixed inset-0 bg-gray-900/30 backdrop-blur-sm flex items-center justify-center p-4 z-50 transition-opacity duration-300">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all duration-300 scale-100">
        <div class="border-b border-gray-200 px-6 py-4 flex justify-between items-center bg-gradient-to-r from-blue-50 to-gray-50 rounded-t-xl">
            <h3 class="text-lg font-semibold text-gray-800">Agregar producto a compra</h3>
            <button @click="cerrarModal" class="text-gray-500 hover:text-gray-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <div class="p-6">
            <template x-if="productoEncontrado">
                <div>
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Código y Nombre</h4>
                        <p class="text-gray-600 font-mono" x-text="productoEncontrado.codigo_barras"></p>
                        <p class="text-lg font-semibold text-gray-900 mt-1" x-text="productoEncontrado.nombre"></p>
                    </div>
                    
                    <div class="space-y-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stock o existencias compradas *</label>
                            <input 
                                type="number" 
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                x-model="cantidadProducto"
                                min="1"
                            >
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Precio de compra (Con impuesto incluido) *</label>
                            <input 
                                type="number" 
                                step="0.01"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                x-model="precioCompra"
                            >
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Precio de venta (Con impuesto incluido)</label>
                            <input 
                                type="number" 
                                step="0.01"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100"
                                x-model="productoEncontrado.precio_venta"
                                disabled
                            >
                        </div>
                    </div>
                    
                    <p class="text-xs text-gray-500 mt-4">
                        Los campos marcados con <span class="text-red-500">*</span> son obligatorios
                    </p>
                </div>
            </template>
            
            <template x-if="!productoEncontrado && codigoBarras">
                <div class="text-center py-8">
                    <div class="mx-auto w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-800 mt-2">Producto no encontrado</h4>
                    <p class="text-gray-600 mt-1">El código <span x-text="codigoBarras" class="font-medium text-gray-800"></span> no está registrado</p>
                    <button 
                        @click="registrarNuevoProducto"
                        class="mt-6 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors shadow-md hover:shadow-lg"
                    >
                        Registrar Nuevo Producto
                    </button>
                </div>
            </template>
        </div>
        
        <div class="border-t border-gray-200 px-6 py-4 bg-gray-50 rounded-b-xl flex justify-end space-x-3">
            <button 
                @click="cerrarModal"
                class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors"
            >
                Cerrar
            </button>
            <button 
                x-show="productoEncontrado"
                @click="agregarAlCarrito"
                class="px-5 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-white shadow-md hover:shadow-lg transition-all"
            >
                Agregar Producto
            </button>
        </div>
    </div>
</div>
    </div>

   <script>
document.addEventListener('alpine:init', () => {
    Alpine.data('compra', () => ({
        // Estado del componente
        codigoBarras: '',
        fecha: new Date().toISOString().split('T')[0],
        proveedorId: '',
        proveedores: [
            {id: 1, nombre: 'Proveedor A'},
            {id: 2, nombre: 'Proveedor B'},
            {id: 3, nombre: 'Proveedor C'},
        ],
        productos: [],
        modalAbierto: false,
        productoEncontrado: null,
        cantidadProducto: 1,
        precioCompra: 0,

        // Computadas
        get subtotal() {
            return this.productos.reduce((sum, p) => sum + p.subtotal, 0);
        },

        get itbis() {
            return this.subtotal * 0.18;
        },

        get total() {
            return this.subtotal + this.itbis;
        },

        get puedeGuardar() {
            return this.proveedorId && this.productos.length > 0;
        },

        // Métodos
        async abrirModalVerificacion() {
            if (!this.codigoBarras) return;

            try {
                const response = await fetch(`/buscar-articulo?codigo=${this.codigoBarras}`);
                const data = await response.json();

                if (data.existe) {
                    this.productoEncontrado = {
                        id: data.articulo.idArticulos,
                        codigo: data.articulo.codigo_barras,
                        codigo_barras: data.articulo.codigo_barras,
                        nombre: data.articulo.nombre,
                        stock: data.articulo.stock_total,
                        precio_compra: data.articulo.precio_compra,
                        precio_venta: data.articulo.precio_venta,
                        imagen: data.articulo.foto ? `data:image/jpeg;base64,${data.articulo.foto}` : null
                    };
                    this.precioCompra = data.articulo.precio_compra;
                } else {
                    this.productoEncontrado = null;
                }

                this.modalAbierto = true;
                this.cantidadProducto = 1;
            } catch (error) {
                console.error('Error al buscar producto:', error);
                alert('Error al buscar el producto');
            }
        },

        cerrarModal() {
            this.modalAbierto = false;
            this.productoEncontrado = null;
        },

        agregarAlCarrito() {
            if (!this.productoEncontrado) return;

            const nuevoProducto = {
                id: this.productoEncontrado.id,
                codigo_barras: this.productoEncontrado.codigo_barras,
                nombre: this.productoEncontrado.nombre,
                cantidad: this.cantidadProducto,
                precio: this.precioCompra,
                subtotal: this.cantidadProducto * this.precioCompra,
                stock: this.productoEncontrado.stock,
                precio_venta: this.productoEncontrado.precio_venta
            };

            this.productos.push(nuevoProducto);
            this.cerrarModal();
            this.codigoBarras = '';
        },

        registrarNuevoProducto() {
            window.location.href = `/productos/nuevo?codigo=${this.codigoBarras}`;
        },

        actualizarSubtotal(producto) {
            producto.subtotal = producto.cantidad * producto.precio;
        },

        removerProducto(index) {
            this.productos.splice(index, 1);
        },

        formatCurrency(value) {
            return '$' + value.toFixed(2);
        },

        guardarCompra() {
            if (!this.puedeGuardar) return;

            const compraData = {
                fecha: this.fecha,
                proveedor_id: this.proveedorId,
                productos: this.productos,
                subtotal: this.subtotal,
                itbis: this.itbis,
                total: this.total
            };

            // Aquí iría la llamada AJAX para guardar en backend
            console.log('Datos a enviar:', compraData);
            alert('Compra guardada exitosamente');

            // Reset
            this.productos = [];
            this.proveedorId = '';
        }
    }));
});
</script>
</x-layout.default>