<x-layout.default>
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{ route('marcas.index') }}" class="text-primary hover:underline">Marcas</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Editar Marca</span>
            </li>
        </ul>
    </div>

    <div class="panel mt-6 p-5 max-w-4x2 mx-auto">
        <h2 class="text-xl font-bold mb-5">EDITAR MARCA</h2>

        <form action="{{ route('marcas.update', $marca->idMarca) }}" method="POST" class="space-y-4"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Campo Nombre -->
            <div>
                <label for="nombre" class="block text-sm font-medium">Nombre</label>
                <input type="text" id="nombre" name="nombre" class="form-input w-full"
                    value="{{ old('nombre', $marca->nombre) }}" placeholder="Ingrese el nombre de la marca" required>
                @error('nombre')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Campo Foto con previsualización -->
            <div x-data="{ fotoPreview: '{{ $marca->foto ? 'data:image/jpeg;base64,' . base64_encode($marca->foto) : '' }}' }">
                <label for="foto" class="block text-sm font-medium mb-2">Foto</label>

                <input type="file" name="foto" id="foto" accept="image/*"
                    class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold p-0 file:bg-primary/90 file:text-white file:hover:bg-primary w-full"
                    @change="fotoPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : '{{ $marca->foto ? 'data:image/jpeg;base64,' . base64_encode($marca->foto) : '' }}'">

                <!-- Previsualización centrada -->
                <div class="flex justify-center mt-4">
                    <div
                        class="w-full max-w-xs h-40 border border-gray-300 rounded-lg overflow-hidden flex justify-center items-center bg-white">
                        <template x-if="fotoPreview">
                            <img :src="fotoPreview" alt="Previsualización de la imagen"
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



            <!-- Switch de Estado -->
            <div>
                <label for="estado" class="block text-sm font-medium">Estado</label>
                <div class="flex items-center">
                    <!-- Campo hidden para enviar valor 0 si el switch no está activado -->
                    <input type="hidden" name="estado" value="0">
                    <div class="w-12 h-6 relative">
                        <input type="checkbox" id="estado" name="estado"
                            class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                            value="1" {{ $marca->estado ? 'checked' : '' }} />
                        <span for="estado"
                            class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('marcas.index') }}" class="btn btn-outline-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
</x-layout.default>
