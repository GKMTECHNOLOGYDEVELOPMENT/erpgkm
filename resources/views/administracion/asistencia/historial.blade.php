<x-layout.default>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" />
    <style>
        .tabla-observaciones td:not(:nth-child(5)),
        .tabla-observaciones th:not(:nth-child(5)) {
            text-align: center;
            vertical-align: middle;
        }

        .tabla-observaciones td:nth-child(2),
        .tabla-observaciones th:nth-child(2) {
            max-width: 320px;
            white-space: pre-wrap;
            overflow-wrap: break-word;
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
                        <th class="font-bold">#</th>
                        <th class="font-bold text-center w-[320px]">MENSAJE</th>
                        <th class="font-bold text-center w-[180px] break-words whitespace-normal">FECHA Y HORA</th>
                        <th class="font-bold text-center w-[300px] break-words whitespace-normal">UBICACIÓN</th>
                        <th class="font-bold text-center">IMÁGENES</th>
                        <th class="font-bold text-center">ESTADO</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($observaciones as $i => $obs)
                        <tr class="hover:bg-gray-50 dark:hover:bg-[#2d3748]">
                            <td class="px-1 py-1 border font-semibold">{{ $i + 1 }}</td>
                            <td class="px-1 py-1 border w-[320px]">{{ $obs->mensaje }}</td>
                            <td class="px-1 py-1 border w-[180px]">{{ $obs->fechaHora }}</td>
                            <td class="px-1 py-1 border w-[300px]">{{ $obs->ubicacion }}</td>
                            <td class="px-1 py-1 border align-top">
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
                            <td class="px-1 py-1 border">
                                @php
                                    $estadoBadge = match($obs->estado) {
                                        1 => '<span class="badge bg-primary">Aprobado</span>',
                                        2 => '<span class="badge bg-danger">Denegado</span>',
                                        default => '<span class="badge bg-warning">Pendiente</span>',
                                    };
                                @endphp
                                {!! $estadoBadge !!}
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
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center px-4"
                    x-show="open" x-transition @click.self="close">
                    <div x-show="open" x-transition.scale.100 class="bg-white rounded-lg shadow-xl p-4">
                        <div class="w-[480px] h-[360px] flex items-center justify-center overflow-hidden">
                            <img :src="src" alt="Imagen ampliada" class="object-contain w-full h-full" />
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
