<x-layout.default>
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Artículos</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Editar Artículo</span>
            </li>
        </ul>
    </div>
    <div class="panel mt-6 p-5 max-w-2xl mx-auto">
        <h2 class="text-xl font-bold mb-5">EDITAR ARTÍCULO</h2>

        <form action="{{ route('articulos.update', $articulo->idArticulos) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Código -->
            <div>
                <label for="codigo" class="block text-sm font-medium">Código</label>
                <input type="text" id="codigo" name="codigo" class="form-input w-full"
                       value="{{ old('codigo', $articulo->codigo) }}" placeholder="Ingrese el código del artículo">
            </div>

            <!-- Nombre -->
            <div>
                <label for="nombre" class="block text-sm font-medium">Nombre</label>
                <input type="text" id="nombre" name="nombre" class="form-input w-full"
                       value="{{ old('nombre', $articulo->nombre) }}" placeholder="Ingrese el nombre del artículo" required>
            </div>

            <!-- Precio Compra -->
            <div>
                <label for="precio_compra" class="block text-sm font-medium">Precio Compra</label>
                <input type="number" id="precio_compra" name="precio_compra" class="form-input w-full"
                       value="{{ old('precio_compra', $articulo->precio_compra) }}" placeholder="Ingrese el precio de compra">
            </div>

            <!-- Precio Venta -->
            <div>
                <label for="precio_venta" class="block text-sm font-medium">Precio Venta</label>
                <input type="number" id="precio_venta" name="precio_venta" class="form-input w-full"
                       value="{{ old('precio_venta', $articulo->precio_venta) }}" placeholder="Ingrese el precio de venta">
            </div>

            <!-- Estado -->
            <div>
                <label for="estado" class="block text-sm font-medium">Estado</label>
                <div class="flex items-center">
                    <input type="hidden" name="estado" value="0">
                    <div class="w-12 h-6 relative">
                        <input type="checkbox" id="estado" name="estado" class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                               value="1" {{ old('estado', $articulo->estado) ? 'checked' : '' }}>
                        <span class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('articulos.index') }}" class="btn btn-outline-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
</x-layout.default>
