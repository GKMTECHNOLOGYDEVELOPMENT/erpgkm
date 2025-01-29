<x-layout.default>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">

    <div x-data="multipleTable">
        <div>
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="javascript:;" class="text-primary hover:underline">Administración</a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>Cliente General</span>
                </li>
            </ul>
        </div>
        <div class="panel mt-6">
            <div class="md:absolute md:top-5 ltr:md:left-5 rtl:md:right-5">
                <div class="flex items-center flex-wrap mb-5">
                    <!-- Botón Exportar a Excel -->
                    <button type="button" class="btn btn-success btn-sm m-1" onclick="window.location='{{ route('clientes-general.exportExcel') }}'">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                            <path
                                d="M4 3H20C21.1046 3 22 3.89543 22 5V19C22 20.1046 21.1046 21 20 21H4C2.89543 21 2 20.1046 2 19V5C2 3.89543 2.89543 3 4 3Z"
                                stroke="currentColor" stroke-width="1.5" />
                            <path d="M16 10L8 14M8 10L16 14" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Excel
                    </button>

                    <!-- Botón Exportar a PDF -->
                    <button id="exportPdfBtn" class="btn btn-danger btn-sm"
                        onclick="window.location='{{ route('clientes-general.exportPDF') }}'">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                            <path
                                d="M2 5H22M2 5H22C22 6.10457 21.1046 7 20 7H4C2.89543 7 2 6.10457 2 5ZM2 5V19C2 20.1046 2.89543 21 4 21H20C21.1046 21 22 20.1046 22 19V5M9 14L15 14"
                                stroke="currentColor" stroke-width="1.5" />
                            <path d="M12 11L12 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                        PDF
                    </button>

                    <!-- Botón Agregar -->
                    <button type="button" class="btn btn-primary btn-sm m-1" @click="$dispatch('toggle-modal')">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                            <circle cx="10" cy="6" r="4" stroke="currentColor" stroke-width="1.5" />
                            <path opacity="0.5"
                                d="M18 17.5C18 19.9853 18 22 10 22C2 22 2 19.9853 2 17.5C2 15.0147 5.58172 13 10 13C14.4183 13 18 15.0147 18 17.5Z"
                                stroke="currentColor" stroke-width="1.5" />
                            <path d="M21 10H19M19 10H17M19 10L19 8M19 10L19 12" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                        </svg>
                        Agregar
                    </button>
                </div>
            </div>

            <table id="myTable1" class="whitespace-nowrap"></table>
        </div>
    </div>


    <!-- Modal -->
    <div x-data="{ open: false, imagenPreview: null, imagenActual: '/assets/images/file-preview.svg' }" class="mb-5" @toggle-modal.window="open = !open">
        <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
                <div x-show="open" x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8 animate__animated animate__zoomInUp">
                    <!-- Header del Modal -->
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <h5 class="font-bold text-lg">Agregar Cliente General</h5>
                        <button type="button" class="text-white-dark hover:text-dark" @click="open = false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" class="w-6 h-6">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <!-- Formulario -->
                    <form class="p-5 space-y-4" id="clientGeneralForm" enctype="multipart/form-data" method="post">
                        @csrf <!-- Asegúrate de incluir el token CSRF -->
                        <!-- Descripción -->
                        <div>
                            <label for="descripcion" class="block text-sm font-medium">Nombre</label>
                            <input type="text" id="descripcion" name="descripcion" class="form-input w-full"
                                placeholder="Ingrese la descripción" required>
                        </div>
                        <!-- Foto -->
                        <div class="mb-5">
                            <label for="foto" class="block text-sm font-medium mb-2">Foto</label>
                            <!-- Campo de archivo -->
                            <input id="ctnFile" type="file" name="logo" accept="image/*" required
                                class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold p-0 file:bg-primary/90 ltr:file:mr-5 rtl:file-ml-5 file:text-white file:hover:bg-primary w-full"
                                @change="imagenPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : imagenActual" />

                            <!-- Contenedor de previsualización -->
                            <div
                                class="mt-4 w-full border border-gray-300 rounded-lg overflow-hidden flex justify-center items-center">
                                <template x-if="imagenPreview">
                                    <img :src="imagenPreview" alt="Previsualización de la imagen"
                                        class="w-40 h-40 object-cover">
                                </template>
                                <template x-if="!imagenPreview">
                                    <img src="/assets/images/file-preview.svg" alt="Imagen predeterminada"
                                        class="w-40 h-40 object-cover">
                                </template>
                            </div>
                        </div>
                        <!-- Botones -->
                        <div class="flex justify-end items-center mb-4">
                            <button type="button" class="btn btn-outline-danger"
                                @click="open = false">Cancelar</button>
                            <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <!-- En tu archivo Blade -->
    <script>
        window.sessionMessages = {
            success: '{{ session('success') }}',
            error: '{{ session('error') }}',
        };
    </script>
    <script>
    window.Laravel = {
        csrfToken: '{{ csrf_token() }}',
        routeClientStore: '{{ route('cliente-general.store') }}'
        };
    </script>
    <script src="{{ asset('assets/js/clientegeneral/clientegeneralvalidaciones.js') }}"></script>
    <script src="{{ asset('assets/js/clientegeneral/clientegeneralstore.js') }}"></script>
    <script src="{{ asset('assets/js/notificacion.js') }}"></script>
    <script src="{{ asset('assets/js/clientegeneral/clientegeneral.js') }}"></script>
    <script src="/assets/js/simple-datatables.js"></script>
    <!-- Script de NiceSelect -->
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
</x-layout.default>
