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

    <div class="panel mt-6 p-5 max-w-2xl mx-auto">
        <h2 class="text-xl font-bold mb-5">EDITAR MARCA</h2>

        <form action="{{ route('marcas.update', $marca->idMarca) }}" method="POST" class="space-y-4">
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

            <!-- Botones -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('marcas.index') }}" class="btn btn-outline-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
</x-layout.default>
