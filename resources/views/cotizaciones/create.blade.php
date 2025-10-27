<x-layout.default>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #1e40af;
            --primary-dark: #1e3a8a;
            --primary-light: #dbeafe;
            --success: #059669;
            --success-dark: #047857;
            --warning: #d97706;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
        }

        body {
            background: #f8fafc;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.08), 0 1px 2px 0 rgba(0, 0, 0, 0.04);
            border: 1px solid var(--gray-200);
            transition: all 0.2s ease;
        }

        .card:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .form-control {
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.2s ease;
            width: 100%;
            font-size: 0.875rem;
            background: white;
            color: var(--gray-800);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
            background: white;
        }

        .form-control::placeholder {
            color: var(--gray-400);
            font-style: italic;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 1px 2px 0 rgba(30, 64, 175, 0.05);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(30, 64, 175, 0.1), 0 2px 4px -1px rgba(30, 64, 175, 0.06);
        }

        .btn-success {
            background: var(--success);
            color: white;
            box-shadow: 0 1px 2px 0 rgba(5, 150, 105, 0.05);
        }

        .btn-success:hover {
            background: var(--success-dark);
            transform: translateY(-1px);
        }

        .table-responsive {
            overflow-x: auto;
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            background: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--gray-200);
            font-size: 0.875rem;
        }

        th {
            background: var(--gray-50);
            font-weight: 600;
            color: var(--gray-800);
            border-bottom: 2px solid var(--gray-300);
        }

        .section-divider {
            border: none;
            height: 1px;
            background: linear-gradient(90deg, transparent 0%, var(--gray-200) 50%, transparent 100%);
            margin: 2rem 0;
        }
    </style>

    <div x-data="cotizacionAdd">
        <!-- Header Elegante -->
        <div class="mb-12 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-sm mb-6 border border-gray-200">
                <i class="fas fa-file-contract text-3xl text-primary"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Cotización Comercial</h1>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto leading-relaxed">
                Complete la información requerida para generar una cotización formal y profesional
            </p>
        </div>

        <div class="grid xl:grid-cols-4 gap-8">
            <!-- Panel Principal -->
            <div class="xl:col-span-3">
                <div class="card p-8 mb-8">
                    <!-- Información de la Empresa -->
                    <div class="flex flex-col lg:flex-row gap-8 mb-8">
                        <div class="lg:w-1/2">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-primary bg-opacity-10 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-building text-primary text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Información Corporativa</h3>
                                    <p class="text-gray-500 text-sm">Datos oficiales de su organización</p>
                                </div>
                            </div>
                            <div class="space-y-4 text-gray-700">
                                <div class="flex items-start">
                                    <i class="fas fa-map-marker-alt text-primary mt-1 mr-4 w-4"></i>
                                    <span class="leading-relaxed">13 Tetrick Road, Cypress Gardens, Florida, 33884, EE. UU.</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-envelope text-primary mr-4 w-4"></i>
                                    <span>vristo@gmail.com</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-phone text-primary mr-4 w-4"></i>
                                    <span>+1 (070) 123-4567</span>
                                </div>
                            </div>
                        </div>

                        <!-- Información de la Cotización -->
                        <div class="lg:w-1/2">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-success bg-opacity-10 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-file-invoice-dollar text-success text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Detalles del Documento</h3>
                                    <p class="text-gray-500 text-sm">Información general de la cotización</p>
                                </div>
                            </div>
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Número de Cotización</label>
                                    <input type="text" class="form-control" placeholder="COT-2024-001" x-model="params.cotizacionNo">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-3">Fecha de Emisión</label>
                                        <input type="date" class="form-control" x-model="params.fechaEmision">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-3">Válida hasta</label>
                                        <input type="date" class="form-control" x-model="params.validaHasta">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="section-divider"></div>

                    <!-- Información del Cliente -->
                    <div class="mb-8">
                        <div class="flex items-center mb-8">
                            <div class="w-10 h-10 bg-warning bg-opacity-10 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-users text-warning text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Información del Cliente</h3>
                                <p class="text-gray-500 text-sm">Datos del cliente destinatario</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Nombre o Razón Social</label>
                                <input type="text" class="form-control" placeholder="Ingrese nombre completo" x-model="params.cliente.nombre">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Correo Electrónico</label>
                                <input type="email" class="form-control" placeholder="correo@empresa.com" x-model="params.cliente.email">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Teléfono</label>
                                <input type="text" class="form-control" placeholder="+1 (XXX) XXX-XXXX" x-model="params.cliente.telefono">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Empresa</label>
                                <input type="text" class="form-control" placeholder="Nombre de la empresa" x-model="params.cliente.empresa">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-3">Dirección</label>
                                <input type="text" class="form-control" placeholder="Dirección completa" x-model="params.cliente.direccion">
                            </div>
                        </div>
                    </div>

                    <div class="section-divider"></div>

                    <!-- Items de la Cotización -->
                    <div>
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-primary bg-opacity-10 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-cube text-primary text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Detalle de Productos/Servicios</h3>
                                    <p class="text-gray-500 text-sm">Lista de items a cotizar</p>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary" @click="addItem()">
                                <i class="fas fa-plus-circle mr-3"></i> Agregar Item
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="w-12 text-center">#</th>
                                        <th class="min-w-[300px]">Descripción</th>
                                        <th class="w-24 text-center">Cantidad</th>
                                        <th class="w-32 text-right">Precio Unit.</th>
                                        <th class="w-32 text-right">Total</th>
                                        <th class="w-16 text-center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-if="items.length <= 0">
                                        <tr>
                                            <td colspan="6" class="text-center py-12 text-gray-500">
                                                <i class="fas fa-clipboard-list text-4xl mb-4 block text-gray-300"></i>
                                                <p class="font-medium text-gray-600">No hay items agregados</p>
                                                <p class="text-sm text-gray-500 mt-1">Comience agregando productos o servicios</p>
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-for="(item, index) in items" :key="item.id">
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="text-center text-gray-600 font-medium" x-text="index + 1"></td>
                                            <td>
                                                <input type="text" class="form-control border-gray-300" 
                                                       placeholder="Descripción detallada del producto o servicio" 
                                                       x-model="item.descripcion">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control text-center border-gray-300" 
                                                       placeholder="0" x-model="item.cantidad" min="0">
                                            </td>
                                            <td>
                                                <div class="relative">
                                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">$</span>
                                                    <input type="number" class="form-control text-right border-gray-300 pl-8" 
                                                           placeholder="0.00" x-model="item.precio" min="0" step="0.01">
                                                </div>
                                            </td>
                                            <td class="text-right font-semibold text-gray-900">
                                                $ <span x-text="(item.precio * item.cantidad).toFixed(2)"></span>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" @click="removeItem(item)" 
                                                        class="text-gray-400 hover:text-red-600 transition-colors duration-150"
                                                        x-show="items.length > 1">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <!-- Totales -->
                        <div class="mt-8 bg-gray-50 rounded-xl p-8 border border-gray-200">
                            <div class="flex justify-end">
                                <div class="w-80 space-y-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700 font-medium">Subtotal:</span>
                                        <span class="text-lg font-semibold text-gray-900">$ <span x-text="subtotal.toFixed(2)"></span></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700 font-medium">IGV (18%):</span>
                                        <span class="text-lg font-semibold text-gray-900">$ <span x-text="igv.toFixed(2)"></span></span>
                                    </div>
                                    <hr class="border-gray-300">
                                    <div class="flex justify-between items-center text-xl">
                                        <span class="font-bold text-gray-900">TOTAL:</span>
                                        <span class="font-bold text-primary">$ <span x-text="total.toFixed(2)"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="section-divider"></div>

                    <!-- Notas -->
                    <div class="mt-8">
                        <label class="block text-sm font-medium text-gray-700 mb-4">Términos y Condiciones</label>
                        <textarea class="form-control h-32" placeholder="Incluya aquí los términos de pago, condiciones de entrega, garantías, y cualquier otra información relevante..." 
                                  x-model="params.notas"></textarea>
                    </div>
                </div>
            </div>

            <!-- Panel Lateral -->
            <div class="xl:col-span-1">
                <div class="card p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-6 pb-4 border-b border-gray-200">
                        <i class="fas fa-cog text-primary mr-3"></i>
                        Configuración
                    </h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Moneda</label>
                            <select class="form-control" x-model="params.moneda">
                                <option value="USD">Dólares Americanos (USD)</option>
                                <option value="PEN">Soles Peruanos (PEN)</option>
                                <option value="EUR">Euros (EUR)</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Términos de Pago</label>
                            <select class="form-control" x-model="params.terminosPago">
                                <option value="contado">Al contado</option>
                                <option value="30dias">30 días neto</option>
                                <option value="60dias">60 días neto</option>
                                <option value="90dias">90 días neto</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Validez (días)</label>
                            <input type="number" class="form-control" x-model="params.diasValidez" min="1" max="90">
                        </div>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-6 pb-4 border-b border-gray-200">
                        <i class="fas fa-play-circle text-primary mr-3"></i>
                        Acciones
                    </h3>
                    
                    <div class="space-y-4">
                        <button type="button" class="btn btn-success w-full justify-center">
                            <i class="fas fa-save mr-3"></i> Guardar Cotización
                        </button>
                        
                        <button type="button" class="btn btn-primary w-full justify-center">
                            <i class="fas fa-eye mr-3"></i> Vista Previa
                        </button>
                        
                        <button type="button" class="btn bg-gray-700 text-white w-full justify-center hover:bg-gray-800 transition-colors">
                            <i class="fas fa-file-pdf mr-3"></i> Generar PDF
                        </button>
                        
                        <button type="button" class="btn bg-orange-600 text-white w-full justify-center hover:bg-orange-700 transition-colors">
                            <i class="fas fa-paper-plane mr-3"></i> Enviar por Email
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data('cotizacionAdd', () => ({
                items: [],
                params: {
                    cotizacionNo: 'COT-' + new Date().getFullYear() + '-' + (Math.random().toString().substr(2, 4)),
                    fechaEmision: new Date().toISOString().split('T')[0],
                    validaHasta: new Date(Date.now() + 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
                    cliente: {
                        nombre: '',
                        email: '',
                        telefono: '',
                        empresa: '',
                        direccion: ''
                    },
                    moneda: 'USD',
                    terminosPago: 'contado',
                    diasValidez: 30,
                    notas: 'Esta cotización incluye todos los impuestos aplicables. Precios válidos por 30 días. Términos de pago según lo acordado.'
                },

                get subtotal() {
                    return this.items.reduce((sum, item) => {
                        return sum + (parseFloat(item.precio) || 0) * (parseInt(item.cantidad) || 0);
                    }, 0);
                },

                get igv() {
                    return this.subtotal * 0.18;
                },

                get total() {
                    return this.subtotal + this.igv;
                },

                init() {
                    this.addItem();
                },

                addItem() {
                    const newId = this.items.length > 0 ? Math.max(...this.items.map(item => item.id)) + 1 : 1;
                    this.items.push({
                        id: newId,
                        descripcion: '',
                        cantidad: 1,
                        precio: 0
                    });
                },

                removeItem(item) {
                    if (this.items.length > 1) {
                        this.items = this.items.filter(i => i.id !== item.id);
                    }
                }
            }));
        });
    </script>
</x-layout.default>