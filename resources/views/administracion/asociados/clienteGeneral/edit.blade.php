<x-layout.default>

    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Cliente General</a>
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

            <!-- Campo para la imagen -->
            <div x-data="{ fotoPreview: '{{ $cliente->foto ? $cliente->foto : '' }}' }">
            <label for="foto" class="block text-sm font-medium">Foto</label>
            <input type="file" id="foto" name="foto" accept="image/*"
                class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold p-0 file:bg-primary/90 file:text-white file:hover:bg-primary w-full"
                @change="fotoPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : '{{ $cliente->foto }}'">
            <div class="mt-4 w-full border border-gray-300 rounded-lg overflow-hidden flex justify-center items-center">
                <template x-if="fotoPreview">
                    <img :src="fotoPreview" alt="Previsualización de la foto"
                        class="w-40 h-40 object-cover object-center">
                </template>
                <template x-if="!fotoPreview">
                    <div class="flex items-center justify-center w-40 h-40 text-gray-400 text-sm">
                        Sin imagen
                    </div>
                </template>
            </div>
            @error('foto')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>


            <!-- Estado -->
            <div>
                <label for="estado" class="block text-sm font-medium">Estado</label>
                <div class="flex items-center">
                    <!-- Campo hidden para enviar valor 0 si el switch no está activado -->
                    <input type="hidden" name="estado" value="0">
                    <div class="w-12 h-6 relative">
                        <input type="checkbox" id="estado" name="estado" class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                               value="1" {{ $cliente->estado ? 'checked' : '' }} />
                        <span for="estado" class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('administracion.cliente-general') }}" class="btn btn-outline-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
</x-layout.default>
