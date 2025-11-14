<x-layout.default>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/photoswipe@5/dist/photoswipe.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <style>
        .tabla-observaciones th,
        .tabla-observaciones td {
            text-align: center;
            vertical-align: middle;
            white-space: normal !important;
            /* 👈 permite saltos de línea */
        }
    </style>


    <div class="p-4" x-data="historialTable" x-init="init">
        <h2 class="text-xl font-bold mb-4">
            🗂️ Observaciones de
            {{ strtoupper("{$usuario->Nombre} {$usuario->apellidoPaterno} {$usuario->apellidoMaterno}") }}
        </h2>
        <div class="panel mt-4">
            <div class="overflow-x-auto p-4">
                <table id="tablaHistorial" class="min-w-[1200px] w-full tabla-observaciones whitespace-nowrap">

                    <thead>
                        <tr>
                            <th class="hidden">#</th>
                            <th>ACCIONES</th>
                            <th>FECHA</th>
                            <th>UBICACIÓN</th>
                            <th>ASUNTO</th>
                            <th>MENSAJE</th>
                            <th>IMÁGENES</th>
                            <th>RESPUESTA</th>
                            <th>ESTADO</th>
                            <th>USUARIO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($observaciones as $i => $obs)
                            <tr class="hover:bg-gray-50 dark:hover:bg-[#2d3748]">
                                <td class="hidden">{{ $i + 1 }}</td>
                                <td class="px-1 py-1 border">
                                                                @if(\App\Helpers\PermisoHelper::tienePermiso('EDITAR OBSERVACION ASISTENCIA'))

                                    <button type="button" class="text-yellow-600 hover:text-yellow-700" title="Editar"
                                        @click="abrirModalEditar({{ $obs->idObservaciones }}, `{{ $obs->respuesta }}`, {{ $obs->estado ?? 0 }})">
                                        <div
                                            class="bg-primary text-white rounded-full p-1.5 transition duration-150 ease-in-out group-hover:bg-yellow-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15.232 5.232l3.536 3.536M16.5 3.5a2.121 2.121 0 013 3L7 19H4v-3L16.5 3.5z" />
                                            </svg>
                                        </div>
                                    </button>
                                                                @endif

                                </td>
                                <td class="px-1 py-1 border w-[180px]">{{ $obs->fechaHora }}</td>
                                <td class="px-1 py-1 border w-[300px]">{{ $obs->ubicacion }}</td>
                                <td class="px-1 py-1 border">{{ $obs->tipoAsunto?->nombre ?? 'SIN ASUNTO' }}</td>
                                <td class="px-1 py-1 border w-[320px]">{{ $obs->mensaje }}</td>
                                <td class="px-1 py-1 border align-top">
                                    @if ($obs->anexos && count($obs->anexos))
                                        <div class="grid grid-cols-2 gap-1" id="galeria-{{ $i }}">
                                            @foreach ($obs->anexos as $j => $img)
                                                <a href="data:image/jpeg;base64,{{ base64_encode($img->foto) }}"
                                                    data-pswp-width="1600" data-pswp-height="1200" target="_blank">
                                                    <img src="data:image/jpeg;base64,{{ base64_encode($img->foto) }}"
                                                        class="w-full h-12 object-cover rounded border" />
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>

                                <td class="px-1 py-1 border w-[320px]">{{ $obs->respuesta ?? ' ' }}</td>
                                <td class="px-1 py-1 border">
                                    @php
                                        $estadoBadge = match ($obs->estado) {
                                            1 => '<span class="badge bg-primary">Aprobado</span>',
                                            2 => '<span class="badge bg-danger">Denegado</span>',
                                            default => '<span class="badge bg-warning">Pendiente</span>',
                                        };
                                    @endphp
                                    {!! $estadoBadge !!}
                                </td>
                                <td class="px-1 py-1 border">{{ $obs->encargadoUsuario->Nombre ?? '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div x-data="{
            open: false,
            src: '',
            init() {
                window.addEventListener('open-img', e => {
                    this.src = 'data:image/jpeg;base64,' + e.detail;
                    this.open = true;
                });
            },
            close() {
                this.open = false;
                this.src = '';
            }
        }">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center px-4"
                x-show="open" x-transition @click.self="close">
                <div x-show="open" x-transition.scale.100 class="bg-white rounded-lg shadow-xl p-4">
                    <div class="w-[480px] h-[360px] flex items-center justify-center overflow-hidden">
                        <img :src="src" alt="Imagen ampliada" class="object-contain w-full h-full" />
                    </div>
                    <div class="text-center mt-4">
                        <button @click="close" class="px-4 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="modalEditar.open" x-transition
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4"
            @click.self="modalEditar.open = false">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-xl overflow-hidden"
                @click.away="modalEditar.open = false">
                <!-- Encabezado -->
                <div class="flex justify-between items-center px-4 py-3 border-b">
                    <h2 class="text-lg font-semibold">Editar Observación</h2>
                    <button @click="modalEditar.open = false" class="text-gray-500 hover:text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 8.586l4.95-4.95a1 1 0 011.414 1.414L11.414 10l4.95 4.95a1 1 0 01-1.414 1.414L10 11.414l-4.95 4.95a1 1 0 01-1.414-1.414L8.586 10l-4.95-4.95a1 1 0 011.414-1.414L10 8.586z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <!-- Cuerpo -->
                <div class="p-4">
                    <label class="block text-sm font-medium mb-1">Respuesta:</label>
                    <textarea x-model="modalEditar.respuesta"
                        class="w-full border rounded p-2 text-sm resize-none mb-4 focus:ring-2 focus:ring-blue-500" rows="4"></textarea>

                    <label class="block text-sm font-medium mb-1">Estado:</label>
                    <select x-model="modalEditar.estado"
                        class="w-full border rounded p-2 text-sm mb-4 focus:ring-2 focus:ring-blue-500">
                        <option value="1">Aprobado</option>
                        <option value="2">Denegado</option>
                    </select>
                </div>

                <!-- Acciones -->
                <div class="px-4 py-3 border-t flex justify-end gap-2">
                    <button @click="modalEditar.open = false" class="btn btn-outline-danger">
                        Cancelar
                    </button>
                    @if(\App\Helpers\PermisoHelper::tienePermiso('GUARDAR OBSERVACION ASISTENCIA''))
                    <button @click="guardarCambios" class="btn btn-primary ltr:ml-4 rtl:mr-4">
                        Guardar
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://unpkg.com/photoswipe@5/dist/photoswipe-lightbox.esm.min.js" type="module"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <script src="{{ asset('assets/js/asistencias/historial.js') }}"></script>
    <script type="module">
        import PhotoSwipeLightbox from 'https://unpkg.com/photoswipe@5/dist/photoswipe-lightbox.esm.min.js';

        document.addEventListener('DOMContentLoaded', () => {
            const lightbox = new PhotoSwipeLightbox({
                gallery: 'div[id^="galeria-"]',
                children: 'a',
                pswpModule: () => import('https://unpkg.com/photoswipe@5/dist/photoswipe.esm.min.js')
            });
            lightbox.init();
        });
    </script>

</x-layout.default>
