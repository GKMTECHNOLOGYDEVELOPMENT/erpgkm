    <x-layout.default>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" />
        <style>
            .tabla-observaciones td:nth-child(2),
            .tabla-observaciones th:nth-child(2) {
                max-width: 340px;
                white-space: pre-wrap;
                word-wrap: break-word;
                overflow-wrap: break-word;
                text-align: center !important;
                /* ✅ Esto centra el texto */
            }
        </style>




        <div class="p-4" x-data="historialTable" x-init="init">
            <h2 class="text-xl font-bold mb-4">
                🗂️ Observaciones de
                {{ strtoupper("{$usuario->Nombre} {$usuario->apellidoPaterno} {$usuario->apellidoMaterno}") }}
            </h2>

            <div class="panel mt-6 overflow-x-auto rounded-md border border-gray-200">
                <table id="tablaHistorial" class="table tabla-observaciones w-full whitespace-normal">

                    <thead>
                        <tr>
                            <th class="font-bold text-center">#</th>
                            <th class="text-center font-bold w-[360px]">MENSAJE</th>
                            <th class="font-bold text-center">FECHA Y HORA</th>
                            <th class="font-bold text-center w-[200px] break-words whitespace-normal">UBICACIÓN</th>
                            <th class="font-bold text-center">IMÁGENES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($observaciones as $i => $obs)
                            <tr class="hover:bg-gray-50 dark:hover:bg-[#2d3748]">
                                <td class="px-3 py-2 border text-center font-semibold">{{ $i + 1 }}</td>
                                <td class="px-3 py-2 border align-top w-[360px]">
                                    <div class="max-w-[340px] whitespace-pre-wrap break-words text-center">
                                        {{ $obs->mensaje }}
                                    </div>

                                </td>

                                <td class="px-3 py-2 border text-center">{{ $obs->fechaHora }}</td>
                                <td class="px-3 py-2 border text-center align-middle">{{ $obs->ubicacion }}</td>

                                <td class="px-2 py-2 border w-[200px] align-top">
                                    @if ($obs->anexos && count($obs->anexos))
                                        <div class="grid grid-cols-2 gap-1 viewer-container">
                                            @foreach ($obs->anexos as $img)
                                                <img src="data:image/jpeg;base64,{{ base64_encode($img->foto) }}"
                                                    class="w-full h-16 object-cover rounded border cursor-zoom-in"
                                                    @click="$dispatch('open-img', '{{ base64_encode($img->foto) }}')" />
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400">Sin imágenes</span>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>

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
                    <!-- Modal con tamaño encapsulado moderado -->
                    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center px-4"
                        x-show="open" x-transition @click.self="close">
                        <div x-show="open" x-transition.scale.100 class="bg-white rounded-lg shadow-xl p-4">
                            <div class="w-[480px] h-[360px] flex items-center justify-center overflow-hidden">
                                <img :src="src" alt="Imagen ampliada"
                                    class="object-contain w-full h-full" />
                            </div>
                            <div class="text-center mt-4">
                                <button @click="close"
                                    class="px-4 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm">
                                    Cerrar
                                </button>
                            </div>
                        </div>
                    </div>




                </div>




            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
        <script src="{{ asset('assets/js/asistencias/historial.js') }}"></script>


    </x-layout.default>
