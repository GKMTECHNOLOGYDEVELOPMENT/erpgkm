<x-layout.default>
    <div x-data="cotizacion()" class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Crear Nueva Cotización</h1>
            <p class="text-gray-600">Complete los datos de la cotización</p>
        </div>

        <!-- Formulario Principal -->
        <form @submit.prevent="guardarCotizacion" class="bg-white rounded-lg shadow-md p-6 mb-6">
            <!-- Información del Cliente -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cliente</label>
                    <select x-model="form.cliente_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Seleccionar cliente</option>
                        <template x-for="cliente in clientes" :key="cliente.id">
                            <option :value="cliente.id" x-text="cliente.nombre"></option>
                        </template>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Válido hasta</label>
                    <input type="date" x-model="form.valido_hasta" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Notas -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Notas</label>
                <textarea x-model="form.notas" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Notas adicionales..."></textarea>
            </div>
        </form>

        <!-- Sección de Artículos -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Artículos</h2>
                <button type="button" @click="agregarArticulo" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200">
                    + Agregar Artículo
                </button>
            </div>

            <!-- Lista de Artículos -->
            <div class="space-y-4">
                <template x-for="(articulo, index) in form.articulos" :key="index">
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                            <!-- Artículo -->
                            <div class="md:col-span-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Artículo</label>
                                <select x-model="articulo.articulo_id" @change="cambiarArticulo(index)" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Seleccionar artículo</option>
                                    <template x-for="art in listaArticulos" :key="art.id">
                                        <option :value="art.id" x-text="art.nombre"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Cantidad -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cantidad</label>
                                <input type="number" x-model="articulo.cantidad" @input="calcularSubtotal(index)" min="1" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <!-- Precio Unitario -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Precio Unitario</label>
                                <input type="number" x-model="articulo.precio_unitario" @input="calcularSubtotal(index)" step="0.01" min="0" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <!-- Subtotal -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Subtotal</label>
                                <input type="text" x-model="articulo.subtotal" readonly class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-50 font-semibold">
                            </div>

                            <!-- Eliminar -->
                            <div class="md:col-span-2">
                                <button type="button" @click="eliminarArticulo(index)" class="w-full bg-red-600 text-white px-3 py-2 rounded-md hover:bg-red-700 transition duration-200">
                                    Eliminar
                                </button>
                            </div>
                        </div>

                        <!-- Descripción del Artículo -->
                        <div x-show="articulo.descripcion" class="mt-2">
                            <p class="text-sm text-gray-600" x-text="articulo.descripcion"></p>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Resumen y Total -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Total de la Cotización</h3>
                    <p class="text-3xl font-bold text-blue-600" x-text="`$${total.toFixed(2)}`"></p>
                </div>
                
                <div class="space-x-4">
                    <button type="button" @click="limpiarFormulario" class="bg-gray-500 text-white px-6 py-3 rounded-md hover:bg-gray-600 transition duration-200">
                        Limpiar
                    </button>
                    <button type="button" @click="guardarCotizacion" class="bg-green-600 text-white px-6 py-3 rounded-md hover:bg-green-700 transition duration-200">
                        Guardar Cotización
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function cotizacion() {
            return {
                form: {
                    cliente_id: '',
                    valido_hasta: '',
                    notas: '',
                    articulos: []
                },
                clientes: [],
                listaArticulos: [],
                total: 0,

                init() {
                    // Cargar datos iniciales (simulados)
                    this.cargarClientes();
                    this.cargarArticulos();
                    this.agregarArticulo(); // Agregar primer artículo por defecto
                },

                cargarClientes() {
                    // Simular carga de clientes desde API
                    this.clientes = [
                        { id: 1, nombre: 'Cliente A' },
                        { id: 2, nombre: 'Cliente B' },
                        { id: 3, nombre: 'Cliente C' }
                    ];
                },

                cargarArticulos() {
                    // Simular carga de artículos desde API
                    this.listaArticulos = [
                        { id: 1, nombre: 'Laptop HP', precio: 1200, descripcion: 'Laptop HP 15.6" Core i5' },
                        { id: 2, nombre: 'Mouse Inalámbrico', precio: 25, descripcion: 'Mouse óptico inalámbrico' },
                        { id: 3, nombre: 'Teclado Mecánico', precio: 80, descripcion: 'Teclado mecánico RGB' },
                        { id: 4, nombre: 'Monitor 24"', precio: 300, descripcion: 'Monitor LED 24 pulgadas' }
                    ];
                },

                agregarArticulo() {
                    this.form.articulos.push({
                        articulo_id: '',
                        cantidad: 1,
                        precio_unitario: 0,
                        subtotal: 0,
                        descripcion: ''
                    });
                },

                eliminarArticulo(index) {
                    if (this.form.articulos.length > 1) {
                        this.form.articulos.splice(index, 1);
                    }
                    this.calcularTotal();
                },

                cambiarArticulo(index) {
                    const articuloSeleccionado = this.listaArticulos.find(
                        art => art.id == this.form.articulos[index].articulo_id
                    );
                    
                    if (articuloSeleccionado) {
                        this.form.articulos[index].precio_unitario = articuloSeleccionado.precio;
                        this.form.articulos[index].descripcion = articuloSeleccionado.descripcion;
                        this.calcularSubtotal(index);
                    }
                },

                calcularSubtotal(index) {
                    const articulo = this.form.articulos[index];
                    const cantidad = parseFloat(articulo.cantidad) || 0;
                    const precio = parseFloat(articulo.precio_unitario) || 0;
                    
                    articulo.subtotal = (cantidad * precio).toFixed(2);
                    this.calcularTotal();
                },

                calcularTotal() {
                    this.total = this.form.articulos.reduce((sum, articulo) => {
                        return sum + (parseFloat(articulo.subtotal) || 0);
                    }, 0);
                },

                limpiarFormulario() {
                    if (confirm('¿Estás seguro de que quieres limpiar el formulario?')) {
                        this.form = {
                            cliente_id: '',
                            valido_hasta: '',
                            notas: '',
                            articulos: []
                        };
                        this.total = 0;
                        this.agregarArticulo();
                    }
                },

                async guardarCotizacion() {
                    // Validaciones básicas
                    if (!this.form.cliente_id) {
                        alert('Por favor selecciona un cliente');
                        return;
                    }

                    if (this.form.articulos.length === 0) {
                        alert('Debes agregar al menos un artículo');
                        return;
                    }

                    // Validar que todos los artículos tengan datos
                    const articulosInvalidos = this.form.articulos.some(art => 
                        !art.articulo_id || art.cantidad <= 0 || art.precio_unitario <= 0
                    );

                    if (articulosInvalidos) {
                        alert('Por favor completa todos los datos de los artículos');
                        return;
                    }

                    // Aquí iría la llamada a tu API para guardar la cotización
                    try {
                        console.log('Datos a enviar:', this.form);
                        
                        // Simular envío
                        alert('Cotización guardada exitosamente!');
                        this.limpiarFormulario();
                        
                    } catch (error) {
                        console.error('Error al guardar:', error);
                        alert('Error al guardar la cotización');
                    }
                }
            }
        }
    </script>
</x-layout.default>