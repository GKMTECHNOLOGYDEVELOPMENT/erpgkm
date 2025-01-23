<x-layout.default>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

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

            <!-- Formulario -->
            <form class="p-5 space-y-4" id="kitForm" enctype="multipart/form-data" @submit.prevent="addKit"
                method="post">
                @csrf
                <h2 class="text-lg font-bold">AGREGAR KIT</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Código -->
                    <div>
                        <label for="codigo" class="block text-sm font-medium">Código</label>
                        <input type="text" id="codigo" name="codigo" x-model="kit.codigo"
                            placeholder="Ingresa un código" class="form-input w-full" required />
                    </div>
                    <!-- Nombre -->
                    <div>
                        <label for="nombre" class="block text-sm font-medium">Nombre</label>
                        <input type="text" id="nombre" name="nombre" x-model="kit.nombre"
                            placeholder="Ingresa un nombre" class="form-input w-full" required />
                    </div>
                    <!-- Descripción -->
                    <div>
                        <label for="descripcion" class="block text-sm font-medium">Descripción</label>
                        <textarea id="descripcion" name="descripcion" x-model="kit.descripcion" placeholder="Ingresa una descripción"
                            class="form-input w-full" rows="1"></textarea>
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
                                class="form-input ltr:rounded-l-none rtl:rounded-r-none flex-1"
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
                                class="form-input ltr:rounded-l-none rtl:rounded-r-none flex-1"
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
                <!-- Lista derecha (Artículos disponibles) -->
                <div>
                    <h3 class="font-bold text-base mb-3 mt-3 text-center">ARTÍCULOS</h3>
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
                <!-- Lista izquierda (Artículos en el Kit) -->
                <div>
                    <h3 class="font-bold text-base mb-3 mt-3 text-center">KIT DE <span x-text="currentKitName"></span>
                    </h3>
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

    <script src="{{ asset('assets/js/kit/kit.js') }}"></script>

</x-layout.default>
