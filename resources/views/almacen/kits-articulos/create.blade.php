<x-layout.default>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <div x-data="kitManager">
        <!-- Breadcrumb -->
        <div>
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="javascript:;" class="text-primary hover:underline">Kits de Artículos</a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>Agregar Kits</span>
                </li>
            </ul>
        </div>

        <!-- Formulario para agregar kits -->
        <div class="panel mt-6 p-5 max-w-2xl mx-auto">
            <form @submit.prevent="addKit">
                <h2 class="text-lg font-bold mb-4">AGREGAR KIT</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Código -->
                    <div>
                        <label for="codigo" class="block text-sm font-medium">Código</label>
                        <input type="text" id="codigo" name="codigo" x-model="kit.codigo" placeholder="Ingresa un código"
                            class="form-input w-full" required />
                    </div>
                    <!-- Nombre -->
                    <div>
                        <label for="nombre" class="block text-sm font-medium">Nombre</label>
                        <input type="text" id="nombre" name="nombre" x-model="kit.nombre" placeholder="Ingresa un nombre"
                            class="form-input w-full" required />
                    </div>
                    <!-- Descripción -->
                    <div>
                        <label for="descripcion" class="block text-sm font-medium">Descripción</label>
                        <textarea id="descripcion" name="descripcion" x-model="kit.descripcion" placeholder="Ingresa una descripción" class="form-input w-full" rows="1"></textarea>
                    </div>
                    <!-- Fecha -->
                    <div>
                        <label for="fecha" class="block text-sm font-medium">Fecha</label>
                        <input type="date" id="fecha" name="fecha" x-model="kit.fecha"
                            class="form-input w-full" />
                    </div>
                    <!-- Moneda de Compra -->
                    <div>
                        <label for="moneda_compra" class="block text-sm font-medium">Moneda de Compra</label>
                        <select id="moneda_compra" name="moneda_compra" class="form-input w-full"
                            x-model="kit.moneda_compra" @change="updateMonedaCompra()">
                            <option value="S/">Soles</option>
                            <option value="$">Dólares</option>
                        </select>
                    </div>
                    <!-- Moneda de Venta -->
                    <div>
                        <label for="moneda_venta" class="block text-sm font-medium">Moneda de Venta</label>
                        <select id="moneda_venta" name="moneda_venta" class="form-input w-full"
                            x-model="kit.moneda_venta" @change="updateMonedaVenta()">
                            <option value="S/">Soles</option>
                            <option value="$">Dólares</option>
                        </select>
                    </div>
                    <!-- Precio Compra -->
                    <div>
                        <label for="precio_compra" class="block text-sm font-medium">Precio Compra</label>
                        <div class="flex">
                            <div
                                class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                                <span x-text="kit.symbol_compra">S/</span>
                            </div>
                            <input type="number" id="precio_compra" name="precio_compra"
                                class="form-input ltr:rounded-l-none rtl:rounded-r-none flex-1" step="0.01"
                                placeholder="Ingrese el precio de compra" x-model="kit.precio_compra" />
                        </div>
                    </div>

                    <!-- Precio Venta -->
                    <div>
                        <label for="precio_venta" class="block text-sm font-medium">Precio Venta</label>
                        <div class="flex">
                            <div
                                class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                                <span x-text="kit.symbol_venta">S/</span>
                            </div>
                            <input type="number" id="precio_venta" name="precio_venta"
                                class="form-input ltr:rounded-l-none rtl:rounded-r-none flex-1" step="0.01"
                                placeholder="Ingrese el precio de venta" x-model="kit.precio_venta" />
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="btn btn-primary">Agregar Artículos</button>
                </div>
            </form>
        </div>

        <!-- Drag-and-Drop para gestionar artículos (oculto inicialmente) -->
        <div x-show="showArticlesSection" class="panel mt-6" x-cloak>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-12">
                <!-- Lista izquierda (Artículos en el Kit) -->
                <div>
                    <h3 class="font-bold text-base mb-3 text-center">KIT DE <span x-text="currentKitName"></span></h3>
                    <ul id="kitItemsList" class="custom-scroll overflow-y-auto border rounded-md"
                        style="max-height: 500px; height: 500px;">
                        <template x-for="articulo in kitArticulos" :key="articulo.id">
                            <li class="mb-2.5 cursor-grab" :data-id="articulo.id">
                                <div
                                    class="bg-white dark:bg-[#1b2e4b] rounded-md border border-white-light dark:border-dark px-6 py-3.5 flex items-center">
                                    <div class="flex-1">
                                        <div class="font-semibold text-dark dark:text-[#bfc9d4]"
                                            x-text="articulo.nombre"></div>
                                        <div class="text-gray-500 dark:text-white-dark text-sm"
                                            x-text="articulo.codigo"></div>
                                    </div>
                                    <div class="flex items-center">
                                        <button class="btn btn-info btn-sm mr-2"
                                            @click="viewArticle(articulo)">Ver</button>
                                        <button class="btn btn-success btn-sm mr-2"
                                            @click="articulo.showInput = !articulo.showInput">+</button>
                                        <input x-show="articulo.showInput" type="number" min="1"
                                            step="1" class="form-input w-20 text-center"
                                            x-model="articulo.cantidad" @input="updateQuantity(articulo)" />
                                    </div>
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>

                <!-- Lista derecha (Artículos disponibles) -->
                <div>
                    <h3 class="font-bold text-base mb-3 text-center">ARTÍCULOS</h3>
                    <!-- Buscador -->
                    <div class="mb-4">
                        <input type="text" x-model="searchQuery" placeholder="Buscar por nombre o código"
                            class="form-input w-full" />
                    </div>
                    <ul id="availableItemsList" class="custom-scroll overflow-y-auto border rounded-md"
                        style="max-height: 500px; height: 445px;">
                        <template x-for="articulo in filteredAvailableArticulos" :key="articulo.id">
                            <li class="mb-2.5 cursor-grab" :data-id="articulo.id">
                                <div
                                    class="bg-white dark:bg-[#1b2e4b] rounded-md border border-white-light dark:border-dark px-6 py-3.5 flex items-center">
                                    <div class="flex-1">
                                        <div class="font-semibold text-dark dark:text-[#bfc9d4]"
                                            x-text="articulo.nombre"></div>
                                        <div class="text-gray-500 dark:text-white-dark text-sm"
                                            x-text="articulo.codigo"></div>
                                    </div>
                                    <button class="btn btn-info btn-sm ml-3"
                                        @click="viewArticle(articulo)">Ver</button>
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>


            <div class="mt-6 flex justify-end">
                <button type="button" class="btn btn-primary" @click="saveArticles">Guardar Kit</button>
            </div>
        </div>

        <!-- Modal -->
        <div x-show="showModal" @toggle-modal.window="showModal = !showModal" class="mb-5" x-cloak>
            <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="showModal && '!block'">
                <div class="flex items-start justify-center min-h-screen px-4" @click.self="showModal = false">
                    <div x-show="showModal" x-transition.duration.300
                        class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-3xl my-8 animate__animated animate__zoomInUp">
                        <!-- Header del Modal -->
                        <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                            <h5 class="font-bold text-lg">Detalles del Artículo</h5>
                            <button type="button" class="text-white-dark hover:text-dark"
                                @click="showModal = false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </div>
                        <!-- Contenido del Modal -->
                        <div class="modal-scroll p-5 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Código -->
                                <div>
                                    <label class="block text-sm font-medium">Código</label>
                                    <p class="form-input w-full bg-gray-100 dark:bg-gray-800"
                                        x-text="selectedArticle.codigo">
                                    </p>
                                </div>
                                <!-- Nombre -->
                                <div>
                                    <label class="block text-sm font-medium">Nombre</label>
                                    <p class="form-input w-full bg-gray-100 dark:bg-gray-800"
                                        x-text="selectedArticle.nombre">
                                    </p>
                                </div>
                            </div>
                            <!-- Botones -->
                            <div class="flex justify-end items-center mt-4">
                                <button type="button" class="btn btn-outline-danger"
                                    @click="showModal = false">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <style>
        /* Custom scrollbar styles */
        .custom-scroll::-webkit-scrollbar {
            width: 10px;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background-color: #4A5568;
            border-radius: 10px;
        }

        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background-color: #2D3748;
        }

        .custom-scroll::-webkit-scrollbar-track {
            background-color: #E2E8F0;
            border-radius: 10px;
        }
    </style>

    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("kitManager", () => ({
                // Datos del Kit
                kit: {
                    codigo: "",
                    nombre: "",
                    descripcion: "",
                    fecha: "",
                    moneda_compra: "S/",
                    precio_compra: 0,
                    moneda_venta: "S/",
                    precio_venta: 0,
                    symbol_compra: "S/",
                    symbol_venta: "S/",
                },
                kits: [], // Lista de kits
                kitArticulos: [], // Artículos asignados al kit
                availableArticulos: [ // Artículos disponibles
                    {
                        id: 1,
                        codigo: "A001",
                        nombre: "Artículo 1",
                        cantidad: 0,
                        showInput: false,
                    },
                    {
                        id: 2,
                        codigo: "A002",
                        nombre: "Artículo 2",
                        cantidad: 0,
                        showInput: false,
                    },
                    {
                        id: 3,
                        codigo: "A003",
                        nombre: "Artículo 3",
                        cantidad: 0,
                        showInput: false,
                    },
                    {
                        id: 4,
                        codigo: "A004",
                        nombre: "Artículo 4",
                        cantidad: 0,
                        showInput: false,
                    },
                    {
                        id: 5,
                        codigo: "A005",
                        nombre: "Artículo 5",
                        cantidad: 0,
                        showInput: false,
                    },
                ],
                searchQuery: "", // Query del buscador
                showArticlesSection: false, // Control para mostrar la sección de artículos
                currentKitName: "", // Nombre actual del kit
                showModal: false, // Control para mostrar el modal
                selectedArticle: {}, // Artículo seleccionado para el modal

                // Función para agregar un nuevo kit
                addKit() {
                    this.kits.push({
                        ...this.kit,
                        id: Date.now(),
                    });
                    this.currentKitName = this.kit.nombre; // Actualizar el nombre del kit actual
                    this.kitArticulos = []; // Vaciar los artículos del kit
                    this.kit = { // Reiniciar los datos del kit
                        codigo: "",
                        nombre: "",
                        descripcion: "",
                        fecha: "",
                        moneda_compra: "S/",
                        precio_compra: 0,
                        moneda_venta: "S/",
                        precio_venta: 0,
                        symbol_compra: "S/",
                        symbol_venta: "S/",
                    };
                    this.showArticlesSection = true; // Mostrar la sección de artículos
                    this.initializeSortable(); // Configurar Sortable.js
                    alert("Kit guardado exitosamente!");
                },

                // Función para guardar los artículos en el kit
                saveArticles() {
                    alert(`Artículos guardados en el kit "${this.currentKitName}"!`);
                },

                // Función para ver los detalles de un artículo en el modal
                viewArticle(article) {
                    this.selectedArticle = article; // Asignar el artículo seleccionado
                    this.showModal = true; // Mostrar el modal
                },

                // Actualizar la cantidad de un artículo
                updateQuantity(article) {
                    const index = this.kitArticulos.findIndex((a) => a.id === article.id);
                    if (index !== -1) {
                        this.kitArticulos[index].cantidad = article.cantidad;
                    }
                },

                // Función para actualizar el símbolo de la moneda de compra
                updateMonedaCompra() {
                    this.kit.symbol_compra = this.kit.moneda_compra;
                },

                // Función para actualizar el símbolo de la moneda de venta
                updateMonedaVenta() {
                    this.kit.symbol_venta = this.kit.moneda_venta;
                },

                // Filtrar artículos disponibles en base al buscador
                get filteredAvailableArticulos() {
                    const query = this.searchQuery.toLowerCase();
                    return this.availableArticulos.filter(
                        (art) =>
                        art.nombre.toLowerCase().includes(query) ||
                        art.codigo.toLowerCase().includes(query)
                    );
                },

                // Configurar Sortable.js
                initializeSortable() {
                    // Lista izquierda (kit)
                    Sortable.create(document.getElementById("kitItemsList"), {
                        animation: 150,
                        group: "shared",
                        onAdd: (evt) => {
                            const itemId = parseInt(evt.item.dataset.id, 10);
                            const item = this.availableArticulos.find((art) => art.id ===
                                itemId);
                            if (item) {
                                this.kitArticulos.push({
                                    ...item,
                                    cantidad: 0,
                                    showInput: false,
                                });
                                this.availableArticulos = this.availableArticulos.filter(
                                    (art) => art.id !== itemId
                                );
                            }
                        },
                        onRemove: (evt) => {
                            const itemId = parseInt(evt.item.dataset.id, 10);
                            const item = this.kitArticulos.find((art) => art.id === itemId);
                            if (item) {
                                this.availableArticulos.push(item);
                                this.kitArticulos = this.kitArticulos.filter(
                                    (art) => art.id !== itemId
                                );
                            }
                        },
                    });

                    // Lista derecha (disponibles)
                    Sortable.create(document.getElementById("availableItemsList"), {
                        animation: 150,
                        group: "shared",
                    });
                },
            }));
        });
        document.addEventListener("DOMContentLoaded", function() {
            // Inicializa flatpickr en el campo de fecha
            flatpickr("#fecha", {
                dateFormat: "Y-m-d", // Formato de fecha
                defaultDate: new Date(), // Fecha predeterminada: hoy
                altInput: true, // Mostrar campo alternativo amigable
                altFormat: "F j, Y", // Formato amigable para el usuario
                locale: "es", // Localización en español
            });
        });
    </script>
</x-layout.default>
