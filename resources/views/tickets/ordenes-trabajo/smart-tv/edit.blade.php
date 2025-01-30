<x-layout.default>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <div class="mb-5" x-data="{ tab: 'detalle' }">
        <!-- Tabs -->
        <ul class="grid grid-cols-4 gap-2 sm:flex sm:flex-wrap sm:justify-center mt-3 mb-5 sm:space-x-3">
            <li>
                <a href="javascript:;"
                    class="p-7 py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] hover:bg-success hover:text-white"
                    :class="{ 'bg-success text-white': tab === 'detalle' }" @click="tab = 'detalle'">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 2H16M8 2V6M16 2V6M4 6H20M4 6V22H20V6M9 10H15M9 14H15M9 18H12" />
                    </svg>
                    Detalles OT
                </a>
            </li>
            <li>
                <a href="javascript:;"
                    class="p-7 py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] hover:bg-success hover:text-white"
                    :class="{ 'bg-success text-white': tab === 'visitas' }" @click="tab = 'visitas'">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 19l3-3m-3 3H5v-3l9-9a2 2 0 012.828 0l2.172 2.172a2 2 0 010 2.828l-9 9z" />
                    </svg>
                    Visitas
                </a>
            </li>
            <li>
                <a href="javascript:;"
                    class="p-7 py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] hover:bg-success hover:text-white"
                    :class="{ 'bg-success text-white': tab === 'firmas' }" @click="tab = 'firmas'">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 19l3-3m-3 3H5v-3l9-9a2 2 0 012.828 0l2.172 2.172a2 2 0 010 2.828l-9 9z" />
                    </svg>
                    Firmas
                </a>
            </li>
        </ul>

        <!-- Contenido de los tabs -->
        <div class="panel mt-6 p-5 max-w-4xl mx-auto">
            <!-- Tab Detalles OT -->
            <div x-show="tab === 'detalle'">

                <h2 class="text-lg font-semibold mb-4">Detalles de la Orden de Trabajo</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Cliente General -->
                    <div>
                        <label class="block text-sm font-medium">Cliente General</label>
                        <input type="text" class="form-input w-full bg-gray-100"
                            value="{{ $orden->clienteGeneral->descripcion }}" readonly>
                    </div>

                    <!-- Cliente -->
                    <div>
                        <label class="block text-sm font-medium">Cliente</label>
                        <input type="text" class="form-input w-full bg-gray-100"
                            value="{{ $orden->cliente->nombre }} - {{ $orden->cliente->documento }}" readonly>
                    </div>

                    <!-- Tienda -->
                    <div>
                        <label class="block text-sm font-medium">Tienda</label>
                        <input type="text" class="form-input w-full bg-gray-100" value="{{ $orden->tienda->nombre }}"
                            readonly>
                    </div>

                    <!-- Dirección -->
                    <div>
                        <label class="block text-sm font-medium">Dirección</label>
                        <input type="text" class="form-input w-full bg-gray-100" value="{{ $orden->direccion }}"
                            readonly>
                    </div>

                    <!-- Marca -->
                    <div>
                        <label class="block text-sm font-medium">Marca</label>
                        <input type="text" class="form-input w-full bg-gray-100"
                            value="{{ $orden->marca?->nombre ?? 'No asignado' }}" readonly>

                    </div>

                    <!-- Modelo (Editable) -->
                    <div>
                        <label for="idModelo" class="block text-sm font-medium">Modelo</label>
                        <select id="idModelo" name="idModelo" class="select2 w-full" style="display:none">
                            <option value="" disabled>Seleccionar Modelo</option>
                            @foreach ($modelos as $modelo)
                                <option value="{{ $modelo->idModelo }}"
                                    {{ $orden->idModelo == $modelo->idModelo ? 'selected' : '' }}>
                                    {{ $modelo->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>



                    <!-- Serie (Editable) -->
                    <div>
                        <label class="block text-sm font-medium">N. Serie</label>
                        <input id="serie" name="serie" type="text" class="form-input w-full"
                            value="{{ $orden->serie }}">
                    </div>

                    <!-- Técnico -->
                    <div>
                        <label class="block text-sm font-medium">Técnico Principal</label>
                        <input type="text" class="form-input w-full bg-gray-100"
                            value="{{ $orden->tecnico->Nombre }}" readonly>
                    </div>

                    <!-- Fecha de Compra -->
                    <div>
                        <label class="block text-sm font-medium">Fecha de Compra</label>
                        <input id="fechaCompra" name="fechaCompra" type="text" class="form-input w-full bg-gray-100"
                            value="{{ $orden->fechaCompra }}" readonly>
                    </div>

                    <!-- Falla Reportada -->
                    <div class="">
                        <label class="block text-sm font-medium">Falla Reportada</label>
                        <textarea id="fallaReportada" name="fallaReportada" rows="1" class="form-input w-full bg-gray-100" readonly>{{ $orden->fallaReportada }}</textarea>
                    </div>
                    <!-- Checkbox Necesita Apoyo -->
                    <div class="mt-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" id="necesitaApoyo" class="form-checkbox">
                            <span class="ml-2 text-sm font-medium">¿Necesita Apoyo?</span>
                        </label>
                    </div>
                    <!-- Select Múltiple para Técnicos de Apoyo (Inicialmente Oculto) -->
                    <div id="apoyoSelectContainer" class="mt-3 hidden">
                        <label for="idTecnicoApoyo" class="block text-sm font-medium">Seleccione Técnicos de
                            Apoyo</label>
                        <select id="idTecnicoApoyo" name="idTecnicoApoyo[]" multiple
                            placeholder="Seleccionar Técnicos de Apoyo" style="display:none">
                            <option value="2">María López</option>
                            <option value="3">Carlos García</option>
                            <option value="4">Ana Martínez</option>
                            <option value="5">Pedro Sánchez</option>
                        </select>
                    </div>

                    <!-- Contenedor para mostrar los técnicos seleccionados -->
                    <div id="selected-items-container" class="mt-3 hidden">
                        <strong>Seleccionados:</strong>
                        <div id="selected-items-list" class="flex flex-wrap gap-2"></div>
                    </div>

                </div>

            </div>

            <!-- Tab visitas -->
            <div x-show="tab === 'visitas'">
                <h4 class="font-semibold text-2xl mb-4">Visitas</h4>
                <p>Aquí van las firmas de la orden de trabajo.</p>
            </div>

            <!-- Tab Firmas -->
            <div x-show="tab === 'firmas'">
                <h4 class="font-semibold text-2xl mb-4">Firmas</h4>
                <p>Aquí van las firmas de la orden de trabajo.</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Inicializar NiceSelect2
            document.querySelectorAll('.select2').forEach(function(select) {
                NiceSelect.bind(select, {
                    searchable: true
                });
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            let selectTecnicoApoyo = document.getElementById("idTecnicoApoyo");
            let checkboxApoyo = document.getElementById("necesitaApoyo");
            let selectContainer = document.getElementById("apoyoSelectContainer");
            let selectedItemsContainer = document.getElementById("selected-items-container");
            let selectedItemsList = document.getElementById("selected-items-list");

            // Inicializar NiceSelect2 en el select múltiple
            NiceSelect.bind(selectTecnicoApoyo, {
                searchable: true
            });

            // Mostrar/ocultar el select2 de técnicos de apoyo según el checkbox
            checkboxApoyo.addEventListener("change", function() {
                if (this.checked) {
                    selectContainer.classList.remove("hidden");
                    selectedItemsContainer.classList.remove("hidden");
                } else {
                    selectContainer.classList.add("hidden");
                    selectedItemsContainer.classList.add("hidden");
                    selectedItemsList.innerHTML = ""; // Limpiar seleccionados si se desactiva
                    selectTecnicoApoyo.value = ""; // Reiniciar el select
                    NiceSelect.sync(selectTecnicoApoyo);
                }
            });

            // Actualizar la lista de seleccionados dinámicamente
            selectTecnicoApoyo.addEventListener("change", function() {
                selectedItemsList.innerHTML = ""; // Limpiar antes de actualizar

                let selectedOptions = Array.from(selectTecnicoApoyo.selectedOptions);
                selectedOptions.forEach(option => {
                    let item = document.createElement("span");
                    item.classList.add("badge", "bg-primary", "px-3", "py-1", "text-white",
                        "rounded-lg", "text-sm", "font-medium");
                    item.textContent = option.text;
                    selectedItemsList.appendChild(item);
                });
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
</x-layout.default>
