<x-layout.default>
    <div class="panel mt-6 p-5 max-w-2xl mx-auto">
        <h2 class="text-xl font-bold mb-5">Editar Tienda</h2>

        <form action="{{ route('tienda.update', $tienda->idTienda) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Campo para el nombre -->
            <div>
                <label for="nombre" class="block text-sm font-medium">Nombre</label>
                <input type="text" id="nombre" name="nombre" class="form-input w-full" value="{{ old('nombre', $tienda->nombre) }}" placeholder="Ingrese el nombre de la tienda" required>
                @error('nombre')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('administracion.tienda') }}" class="btn btn-outline-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
</x-layout.default>
