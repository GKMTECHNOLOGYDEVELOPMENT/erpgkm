<x-layout.default>
    <div class="panel mt-6 p-5 max-w-2xl mx-auto">
        <h2 class="text-xl font-bold mb-5">Editar Cliente General</h2>

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
            <div x-data="{ fotoPreview: '{{ asset($cliente->foto) }}' }">
                <label for="foto" class="block text-sm font-medium">Foto</label>
                <input type="file" id="foto" name="foto" accept="image/*"
                    class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold p-0 file:bg-primary/90 file:text-white file:hover:bg-primary w-full"
                    @change="fotoPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : '{{ asset($cliente->foto) }}'">
                <div
                    class="mt-4 w-full border border-gray-300 rounded-lg overflow-hidden flex justify-center items-center">
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

            <div>
                <label for="estado" class="block text-sm font-medium">Estado</label>
                <div class="flex items-center space-x-4">
                    <!-- Campo hidden para enviar valor 0 si el checkbox no está marcado -->
                    <input type="hidden" name="estado" value="0">

                    <label class="inline-flex items-center">
                        <input type="checkbox" id="estado" name="estado" class="form-checkbox" value="1"
                            {{ $cliente->estado ? 'checked' : '' }}>
                        <span class="ml-2">Activo</span>
                    </label>
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
