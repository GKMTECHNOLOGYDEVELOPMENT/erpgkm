<x-layout.default>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/nice-select2@2.1.0/dist/css/nice-select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/nice-select2@2.1.0/dist/js/nice-select2.min.js"></script>

    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{ route('administracion.cliente-general') }}" class="text-primary hover:underline">Cliente General</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Editar Cliente General</span>
            </li>
        </ul>
    </div>

    <div class="panel mt-6 p-5 max-w-4x2 mx-auto">
        <h2 class="text-xl font-bold mb-5">EDITAR CLIENTE GENERAL</h2>

        <form action="{{ route('cliente-general.update', $cliente->idClienteGeneral) }}" method="POST"
            enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Campo para la descripción -->
            <div>
                <label for="descripcion" class="block text-sm font-medium">Nombre</label>
                <input type="text" id="descripcion" name="descripcion" class="form-input w-full"
                    value="{{ old('descripcion', $cliente->descripcion) }}" placeholder="Ingrese la descripción"
                    required>
                @error('descripcion')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Select múltiple para Marcas -->
            <div>
                <label for="marcas" class="block text-sm font-medium mb-1">Marcas</label>
                <select name="marcas[]" id="marcas" class="nice-select w-full" multiple>
                    @foreach ($marcas as $marca)
                        <option value="{{ $marca->idMarca }}"
                            {{ $marcasAsociadas->contains($marca->idMarca) ? 'selected' : '' }}>
                            {{ $marca->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Contenedor para mostrar marcas seleccionadas -->
            <div class="mt-3">
                <strong>Marcas Seleccionadas:</strong>
                <div id="selected-marcas"
                    class="mt-2 flex flex-wrap gap-2 border border-gray-300 rounded-md p-2 min-h-[45px] text-xs">
                </div>
            </div>

            <!-- 🔥 NUEVO: Select múltiple para Contactos Finales -->
            <div>
                <label for="contactos_finales" class="block text-sm font-medium mb-1">Contactos Finales</label>
                <select name="contactos_finales[]" id="contactos_finales" class="nice-select w-full" multiple>
                    @foreach ($contactosFinales as $contacto)
                        <option value="{{ $contacto->idContactoFinal }}"
                            {{ $contactosFinalesAsociados->contains($contacto->idContactoFinal) ? 'selected' : '' }}>
                            {{ $contacto->nombre_completo }} - {{ $contacto->numero_documento }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- 🔥 NUEVO: Contenedor para mostrar contactos finales seleccionados -->
            <div class="mt-3">
                <strong>Contactos Finales Seleccionados:</strong>
                <div id="selected-contactos-finales"
                    class="mt-2 flex flex-wrap gap-2 border border-gray-300 rounded-md p-2 min-h-[45px] text-xs">
                </div>
            </div>

            <!-- Campo para la imagen -->
            <div x-data="{ fotoPreview: '{{ $cliente->foto ? $cliente->foto : '' }}' }">
                <label for="foto" class="block text-sm font-medium mb-2">Foto</label>
                <input type="file" id="foto" name="foto" accept="image/*"
                    class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold p-0 file:bg-primary/90 file:text-white file:hover:bg-primary w-full"
                    @change="fotoPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : '{{ $cliente->foto }}'">

                <!-- Previsualización centrada -->
                <div class="flex justify-center mt-4">
                    <div
                        class="w-full max-w-xs h-40 border border-gray-300 rounded-lg overflow-hidden flex justify-center items-center bg-white">
                        <template x-if="fotoPreview">
                            <img :src="fotoPreview" alt="Previsualización de la foto"
                                class="w-full h-full object-contain" />
                        </template>
                        <template x-if="!fotoPreview">
                            <div class="flex items-center justify-center w-full h-full text-gray-400 text-sm">
                                Sin imagen
                            </div>
                        </template>
                    </div>
                </div>

                @error('foto')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Estado -->
            <div>
                <label for="estado" class="block text-sm font-medium">Estado</label>
                <div class="flex items-center">
                    <input type="hidden" name="estado" value="0">
                    <div class="w-12 h-6 relative">
                        <input type="checkbox" id="estado" name="estado"
                            class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                            value="1" {{ $cliente->estado ? 'checked' : '' }} />
                        <span for="estado"
                            class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('administracion.cliente-general') }}" class="btn btn-outline-danger">Cancelar</a>
                @if(\App\Helpers\PermisoHelper::tienePermiso('ACTUALIZAR CLIENTE GENERAL'))
                <button type="submit" class="btn btn-primary">Actualizar</button>
                @endif
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Inicializar NiceSelect2 para Marcas
            NiceSelect.bind(document.getElementById("marcas"), {
                searchable: true,
            });

            // Inicializar NiceSelect2 para Contactos Finales
            NiceSelect.bind(document.getElementById("contactos_finales"), {
                searchable: true,
            });

            // Mostrar seleccionados personalizados para Marcas
            function actualizarSeleccionadosMarcas() {
                const select = document.getElementById("marcas");
                const seleccionados = Array.from(select.selectedOptions).map(opt => opt.text);
                const contenedor = document.getElementById("selected-marcas");

                contenedor.innerHTML = "";
                seleccionados.forEach(nombre => {
                    const chip = document.createElement("span");
                    chip.className = "bg-primary text-white px-2 py-1 rounded";
                    chip.textContent = nombre;
                    contenedor.appendChild(chip);
                });
            }

            // 🔥 NUEVO: Mostrar seleccionados personalizados para Contactos Finales
            function actualizarSeleccionadosContactosFinales() {
                const select = document.getElementById("contactos_finales");
                const seleccionados = Array.from(select.selectedOptions).map(opt => opt.text);
                const contenedor = document.getElementById("selected-contactos-finales");

                contenedor.innerHTML = "";
                seleccionados.forEach(nombre => {
                    const chip = document.createElement("span");
                    chip.className = "bg-green-500 text-white px-2 py-1 rounded";
                    chip.textContent = nombre;
                    contenedor.appendChild(chip);
                });
            }

            // Detectar cambios en Marcas
            document.getElementById("marcas").addEventListener("change", actualizarSeleccionadosMarcas);

            // 🔥 NUEVO: Detectar cambios en Contactos Finales
            document.getElementById("contactos_finales").addEventListener("change", actualizarSeleccionadosContactosFinales);

            // Mostrar los que ya están seleccionados al iniciar
            actualizarSeleccionadosMarcas();
            actualizarSeleccionadosContactosFinales();
        });
    </script>
</x-layout.default>