<x-layout.default>
    <div class="mb-5" x-data="{ tab: 'detalle' }">
        <!-- Tabs -->
        <ul class="grid grid-cols-4 gap-2 sm:flex sm:flex-wrap sm:justify-center mt-3 mb-5 sm:space-x-3">
            <li>
                <a href="javascript:;" class="p-7 py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] hover:bg-success hover:text-white"
                    :class="{ 'bg-success text-white': tab === 'detalle' }"
                    @click="tab = 'detalle'">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 2H16M8 2V6M16 2V6M4 6H20M4 6V22H20V6M9 10H15M9 14H15M9 18H12"/>
                    </svg>                    
                    Detalles OT
                </a>
            </li>
            <li>
                <a href="javascript:;" class="p-7 py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] hover:bg-success hover:text-white"
                    :class="{ 'bg-success text-white': tab === 'firmas' }"
                    @click="tab = 'firmas'">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l3-3m-3 3H5v-3l9-9a2 2 0 012.828 0l2.172 2.172a2 2 0 010 2.828l-9 9z"/>
                    </svg>
                    Firmas
                </a>
            </li>
        </ul>

        <!-- Contenido de los tabs -->
        <div class="flex-1 text-sm">
            <!-- Tab Detalles OT -->
            <div x-show="tab === 'detalle'">
                <div class="p-6 bg-white shadow-md rounded-lg">
                    <h2 class="text-lg font-semibold mb-4">Detalles de la Orden de Trabajo</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Cliente General -->
                        <div>
                            <label class="block text-sm font-medium">Cliente General</label>
                            <input type="text" class="form-input w-full bg-gray-100" value="{{ $orden->clienteGeneral->descripcion }}" readonly>
                        </div>

                        <!-- Cliente -->
                        <div>
                            <label class="block text-sm font-medium">Cliente</label>
                            <input type="text" class="form-input w-full bg-gray-100" value="{{ $orden->cliente->nombre }} - {{ $orden->cliente->documento }}" readonly>
                        </div>

                        <!-- Tienda -->
                        <div>
                            <label class="block text-sm font-medium">Tienda</label>
                            <input type="text" class="form-input w-full bg-gray-100" value="{{ $orden->tienda->nombre }}" readonly>
                        </div>

                        <!-- Dirección -->
                        <div>
                            <label class="block text-sm font-medium">Dirección</label>
                            <input type="text" class="form-input w-full bg-gray-100" value="{{ $orden->direccion }}" readonly>
                        </div>

                        <!-- Marca -->
                        <div>
                            <label class="block text-sm font-medium">Marca</label>
                            <input type="text" class="form-input w-full bg-gray-100" value="{{ $orden->marca?->nombre ?? 'No asignado' }}" readonly>

                        </div>

                        <!-- Modelo (Editable) -->
                        <div>
                            <label class="block text-sm font-medium">Modelo</label>
                            <select id="idModelo" name="idModelo" class="select2 w-full">
                                <option value="" disabled selected>Seleccionar Modelo</option>
                                @foreach ($modelos as $modelo)
                                    <option value="{{ $modelo->idModelo }}" {{ $orden->idModelo == $modelo->idModelo ? 'selected' : '' }}>
                                        {{ $modelo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            
                        </div>

                        <!-- Serie (Editable) -->
                        <div>
                            <label class="block text-sm font-medium">N. Serie</label>
                            <input id="serie" name="serie" type="text" class="form-input w-full" value="{{ $orden->serie }}">
                        </div>

                        <!-- Técnico -->
                        <div>
                            <label class="block text-sm font-medium">Técnico</label>
                            <input type="text" class="form-input w-full bg-gray-100" value="{{ $orden->tecnico->Nombre }}" readonly>
                        </div>

                        <!-- Fecha de Compra -->
                        <div>
                            <label class="block text-sm font-medium">Fecha de Compra</label>
                            <input id="fechaCompra" name="fechaCompra" type="text" class="form-input w-full bg-gray-100" value="{{ $orden->fechaCompra }}" readonly>
                        </div>

                        <!-- Falla Reportada -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium">Falla Reportada</label>
                            <textarea id="fallaReportada" name="fallaReportada" rows="3" class="form-input w-full bg-gray-100" readonly>{{ $orden->fallaReportada }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Firmas -->
            <div x-show="tab === 'firmas'">
                <h4 class="font-semibold text-2xl mb-4">Firmas</h4>
                <p>Aquí van las firmas de la orden de trabajo.</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</x-layout.default>
