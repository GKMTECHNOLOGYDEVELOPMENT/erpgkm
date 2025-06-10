<x-layout.default>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{ route('articulos.index') }}" class="text-primary hover:underline">Artículos</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Imagen Artículo</span>
            </li>
        </ul>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-10">
        <div class="panel p-6" x-data="imageHandler({{ $articulo->idArticulos }})">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <!-- Columna 1: Imagen -->
                <div class="flex justify-center">
                    <div class="w-[280px] h-[280px] border rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700 shadow">
                        <img id="image-preview"
                            src="{{ $articulo->foto ? 'data:image/jpeg;base64,' . base64_encode($articulo->foto) : asset('assets/images/articulo/producto-default.png') }}"
                            alt="Imagen del artículo"
                            class="w-full h-full object-contain" />
                    </div>
                </div>
    
                <!-- Columna 2: Título y botones -->
                <div class="flex flex-col justify-center space-y-6">
                    <div class="text-center">
                        <h2 class="text-xl font-bold text-gray-800 dark:text-white leading-tight">
                            {{ $articulo->nombre }}
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Imagen del artículo
                        </p>
                    </div>
    
                    <div class="flex justify-center space-x-4">
                        <label for="image-upload"
                            class="w-36 inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg shadow btn btn-primary cursor-pointer text-white text-center">
                            Subir imagen
                        </label>
                        <input id="image-upload" type="file" accept="image/jpeg,image/png" class="hidden" @change="uploadImage">
    
                        <button type="button"
                            class="w-36 inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg shadow btn btn-outline-danger"
                            @click="deleteImage">
                            Eliminar imagen
                        </button>
                    </div>
    
                    <div class="text-center text-sm text-gray-500 dark:text-gray-400">
                        Formatos permitidos: JPG, PNG | Máx: 3MB
                    </div>
                </div>
            </div>
        </div>
    </div>
    


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Alpine.js -->
    <script src="https://unpkg.com/alpinejs" defer></script>

    <script>
        function imageHandler(idArticulo) {
            return {
                uploadImage(event) {
                    const file = event.target.files[0];
                    const preview = document.getElementById('image-preview');

                    if (!file) return;

                    if (file.size > 3 * 1024 * 1024) {
                        toastr.error('Archivo muy grande. Máx 3MB.');
                        return;
                    }

                    const validTypes = ['image/jpeg', 'image/png'];
                    if (!validTypes.includes(file.type)) {
                        toastr.error('Formato no válido. Solo JPG o PNG.');
                        return;
                    }

                    const formData = new FormData();
                    formData.append('foto', file);

                    fetch(`/articulos/${idArticulo}/fotoupdate`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                preview.src = data.preview_url;

                                // Mostrar toast
                                toastr.success('Imagen actualizada correctamente');
                            } else {
                                toastr.error('Error al subir imagen');
                            }
                        })
                        .catch(() => toastr.error('Error al subir imagen'));
                },

                deleteImage() {
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: 'Esto eliminará la imagen del artículo',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`/articulos/${idArticulo}/fotodelete`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        document.getElementById('image-preview').src = data.preview_url;

                                        Swal.fire('Eliminado', 'Imagen eliminada correctamente', 'success');
                                    } else {
                                        Swal.fire('Error', 'No se pudo eliminar la imagen', 'error');
                                    }
                                })
                                .catch(() => Swal.fire('Error', 'Error al eliminar imagen', 'error'));
                        }
                    });
                }
            }
        }
    </script>
</x-layout.default>
