<x-layout.default>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <div class="grid grid-cols-1 gap-6">
        <div class="panel" x-data="imageHandler({{ $articulo->idArticulos }})">
            <div class="flex items-center justify-between mb-5">
                <h5 class="font-semibold text-lg dark:text-white-light">Imagen del Repuesto</h5>
            </div>

        <div class="mb-5">
    <div class="flex flex-col items-center">
        <!-- Nombre del artículo -->
        <h2 class="text-xl font-bold text-center text-gray-800 dark:text-white mb-4">
            {{ $articulo->nombre }}
        </h2>

        <!-- Vista previa de la imagen -->
        <div class="relative w-48 h-48 mb-4 border rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700">
            <img id="image-preview" 
                 src="{{ $articulo->foto ? 'data:image/jpeg;base64,' . base64_encode($articulo->foto) : asset('assets/images/articulo/producto-default.png') }}"
                 class="w-full h-full object-contain"
                 alt="Imagen del artículo">
        </div>

        <div class="flex space-x-4">
            <!-- Subir imagen -->
            <label for="image-upload" class="btn btn-primary cursor-pointer">
                Subir imagen
            </label>
            <input id="image-upload" type="file" accept="image/jpeg,image/png" class="hidden"
                   @change="uploadImage">

            <!-- Eliminar imagen -->
            <button type="button" class="btn btn-outline-danger" @click="deleteImage">
                Eliminar imagen
            </button>
        </div>

        <div class="mt-3 text-sm text-gray-500 dark:text-gray-400">
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
