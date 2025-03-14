<x-layout.default>
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{ route('categorias.index') }}" class="text-primary hover:underline">Categorías</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Editar Categoría</span>
            </li>
        </ul>        
    </div>
    <div class="panel mt-6 p-5 max-w-4x2 mx-auto">
        <h2 class="text-xl font-bold mb-5">EDITAR CATEGORÍA</h2>

        <form action="{{ route('categorias.update', $categoria->idCategoria) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Nombre de la categoría -->
            <div>
                <label for="nombre" class="block text-sm font-medium">Nombre</label>
                <input type="text" id="nombre" name="nombre" class="form-input w-full"
                       value="{{ old('nombre', $categoria->nombre) }}" placeholder="Ingrese el nombre de la categoría" required>
                @error('nombre')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Estado de la categoría -->
            <div>
                <label for="estado" class="block text-sm font-medium">Estado</label>
                <div class="flex items-center">
                    <!-- Campo hidden para enviar valor 0 si el switch no está activado -->
                    <input type="hidden" name="estado" value="0">
                    <div class="w-12 h-6 relative">
                        <input type="checkbox" id="estado" name="estado" class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                               value="1" {{ $categoria->estado ? 'checked' : '' }} />
                        <span for="estado" class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('categorias.index') }}" class="btn btn-outline-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
</x-layout.default>
