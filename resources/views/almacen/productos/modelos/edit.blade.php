<x-layout.default>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- ✅ Nueva librería Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li><a href="{{ route('modelos.index') }}" class="text-primary hover:underline">Modelos</a></li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1"><span>Editar Modelo</span></li>
        </ul>
    </div>

    <div class="panel mt-6 p-5 max-w-4x2 mx-auto">
        <h2 class="text-xl font-bold mb-5">EDITAR MODELO</h2>

        <form id="modeloForm" class="grid grid-cols-1 md:grid-cols-2 gap-4" method="POST"
            action="{{ route('modelos.update', $modelo->idModelo) }}">
            @csrf
            @method('PUT')

            <!-- Nombre -->
            <div>
                <label for="nombre" class="block text-sm font-medium">Nombre</label>
                <input id="nombre" type="text" name="nombre" class="form-input w-full"
                    value="{{ old('nombre', $modelo->nombre) }}" placeholder="Ingrese el nombre del modelo" required>
                @error('nombre')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Marca -->
            <div>
                <label for="idMarca" class="block text-sm font-medium">Marca</label>
                <select id="idMarca" name="idMarca" class="select2 w-full" required>
                    <option value="" disabled>Seleccione la Marca</option>
                    @foreach ($marcas as $marca)
                        <option value="{{ $marca->idMarca }}"
                            {{ old('idMarca', $modelo->idMarca) == $marca->idMarca ? 'selected' : '' }}>
                            {{ $marca->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Categoría -->
            <div>
                <label for="idCategoria" class="block text-sm font-medium">Categoría</label>
                <select id="idCategoria" name="idCategoria" class="select2 w-full" required>
                    <option value="" disabled>Seleccione la Categoría</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->idCategoria }}"
                            {{ old('idCategoria', $modelo->idCategoria) == $categoria->idCategoria ? 'selected' : '' }}>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Estado -->
            <div>
                <label for="estado" class="block text-sm font-medium">Estado</label>
                <div class="ml-4 w-12 h-6 relative">
                    <input type="hidden" name="estado" value="0">
                    <input type="checkbox" id="estado" name="estado"
                        class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer" value="1"
                        {{ old('estado', $modelo->estado) ? 'checked' : '' }} />
                    <span
                        class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full 
                          before:absolute before:left-1 before:bg-white dark:before:bg-white-dark 
                          dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 
                          before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary 
                          before:transition-all before:duration-300"></span>
                </div>
            </div>

            <!-- Tipo de Modelo -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Tipo de Modelo</label>
                <div class="space-y-2">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="repuesto" value="1"
                            class="form-checkbox text-primary"
                            {{ old('repuesto', $modelo->repuesto) ? 'checked' : '' }}>
                        <span class="ml-2">Repuestos</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="producto" value="1"
                            class="form-checkbox text-primary"
                            {{ old('producto', $modelo->producto) ? 'checked' : '' }}>
                        <span class="ml-2">Productos</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="heramientas" value="1"
                            class="form-checkbox text-primary"
                            {{ old('heramientas', $modelo->heramientas) ? 'checked' : '' }}>
                        <span class="ml-2">Herramientas</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="suministros" value="1"
                            class="form-checkbox text-primary"
                            {{ old('suministros', $modelo->suministros) ? 'checked' : '' }}>
                        <span class="ml-2">Suministros</span>
                    </label>
                </div>
            </div>

            <!-- Pulgadas -->
            <div id="pulgadasField" class="{{ old('repuesto', $modelo->repuesto) ? '' : 'hidden' }}">
                <label for="pulgadas" class="block text-sm font-medium">Pulgadas</label>
                <input type="text" id="pulgadas" name="pulgadas" class="form-input w-full"
                    value="{{ old('pulgadas', $modelo->pulgadas) }}" placeholder="Ingrese las pulgadas">
                @error('pulgadas')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Botones -->
            <div class="md:col-span-2 flex justify-end mt-4">
                <a href="{{ route('modelos.index') }}" class="btn btn-outline-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">Actualizar</button>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function () {
            $('.select2').select2({
                width: '100%',
                placeholder: 'Seleccione una opción',
                allowClear: true
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const repuestoCheckbox = document.querySelector('input[name="repuesto"]');
            const pulgadasField = document.getElementById('pulgadasField');

            function togglePulgadasField() {
                repuestoCheckbox.checked
                    ? pulgadasField.classList.remove("hidden")
                    : pulgadasField.classList.add("hidden");
            }

            togglePulgadasField();
            repuestoCheckbox.addEventListener("change", togglePulgadasField);
        });
    </script>
</x-layout.default>
