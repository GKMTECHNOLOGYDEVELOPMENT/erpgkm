<x-layout.default>
    <!-- Fancybox CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />


    {{-- <div x-data="invoicePreview">
        <div class="flex items-center lg:justify-end justify-center flex-wrap gap-4 mb-6">
            <button type="button" class="btn btn-info gap-2">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5">
                    <path
                        d="M17.4975 18.4851L20.6281 9.09373C21.8764 5.34874 22.5006 3.47624 21.5122 2.48782C20.5237 1.49939 18.6511 2.12356 14.906 3.37189L5.57477 6.48218C3.49295 7.1761 2.45203 7.52305 2.13608 8.28637C2.06182 8.46577 2.01692 8.65596 2.00311 8.84963C1.94433 9.67365 2.72018 10.4495 4.27188 12.0011L4.55451 12.2837C4.80921 12.5384 4.93655 12.6658 5.03282 12.8075C5.22269 13.0871 5.33046 13.4143 5.34393 13.7519C5.35076 13.9232 5.32403 14.1013 5.27057 14.4574C5.07488 15.7612 4.97703 16.4131 5.0923 16.9147C5.32205 17.9146 6.09599 18.6995 7.09257 18.9433C7.59255 19.0656 8.24576 18.977 9.5522 18.7997L9.62363 18.79C9.99191 18.74 10.1761 18.715 10.3529 18.7257C10.6738 18.745 10.9838 18.8496 11.251 19.0285C11.3981 19.1271 11.5295 19.2585 11.7923 19.5213L12.0436 19.7725C13.5539 21.2828 14.309 22.0379 15.1101 21.9985C15.3309 21.9877 15.5479 21.9365 15.7503 21.8474C16.4844 21.5244 16.8221 20.5113 17.4975 18.4851Z"
                        stroke="currentColor" stroke-width="1.5" />
                    <path opacity="0.5" d="M6 18L21 3" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" />
                </svg>
                Send Invoice </button>

            <button type="button" class="btn btn-primary gap-2" @click="print">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5">
                    <path
                        d="M6 17.9827C4.44655 17.9359 3.51998 17.7626 2.87868 17.1213C2 16.2426 2 14.8284 2 12C2 9.17157 2 7.75736 2.87868 6.87868C3.75736 6 5.17157 6 8 6H16C18.8284 6 20.2426 6 21.1213 6.87868C22 7.75736 22 9.17157 22 12C22 14.8284 22 16.2426 21.1213 17.1213C20.48 17.7626 19.5535 17.9359 18 17.9827"
                        stroke="currentColor" stroke-width="1.5" />
                    <path opacity="0.5" d="M9 10H6" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" />
                    <path d="M19 14L5 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    <path
                        d="M18 14V16C18 18.8284 18 20.2426 17.1213 21.1213C16.2426 22 14.8284 22 12 22C9.17157 22 7.75736 22 6.87868 21.1213C6 20.2426 6 18.8284 6 16V14"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    <path opacity="0.5"
                        d="M17.9827 6C17.9359 4.44655 17.7626 3.51998 17.1213 2.87868C16.2427 2 14.8284 2 12 2C9.17158 2 7.75737 2 6.87869 2.87868C6.23739 3.51998 6.06414 4.44655 6.01733 6"
                        stroke="currentColor" stroke-width="1.5" />
                    <circle opacity="0.5" cx="17" cy="10" r="1" fill="currentColor" />
                    <path opacity="0.5" d="M15 16.5H9" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" />
                    <path opacity="0.5" d="M13 19H9" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" />
                </svg>
                Print </button>

            <button type="button" class="btn btn-success gap-2">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5">
                    <path opacity="0.5"
                        d="M17 9.00195C19.175 9.01406 20.3529 9.11051 21.1213 9.8789C22 10.7576 22 12.1718 22 15.0002V16.0002C22 18.8286 22 20.2429 21.1213 21.1215C20.2426 22.0002 18.8284 22.0002 16 22.0002H8C5.17157 22.0002 3.75736 22.0002 2.87868 21.1215C2 20.2429 2 18.8286 2 16.0002L2 15.0002C2 12.1718 2 10.7576 2.87868 9.87889C3.64706 9.11051 4.82497 9.01406 7 9.00195"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    <path d="M12 2L12 15M12 15L9 11.5M12 15L15 11.5" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                Download </button>

            <a href="/apps/invoice/add" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="w-5 h-5">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Create
            </a>

            <a href="/apps/invoice/edit" class="btn btn-warning gap-2">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                    <path opacity="0.5"
                        d="M22 10.5V12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2H13.5"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    <path
                        d="M17.3009 2.80624L16.652 3.45506L10.6872 9.41993C10.2832 9.82394 10.0812 10.0259 9.90743 10.2487C9.70249 10.5114 9.52679 10.7957 9.38344 11.0965C9.26191 11.3515 9.17157 11.6225 8.99089 12.1646L8.41242 13.9L8.03811 15.0229C7.9492 15.2897 8.01862 15.5837 8.21744 15.7826C8.41626 15.9814 8.71035 16.0508 8.97709 15.9619L10.1 15.5876L11.8354 15.0091C12.3775 14.8284 12.6485 14.7381 12.9035 14.6166C13.2043 14.4732 13.4886 14.2975 13.7513 14.0926C13.9741 13.9188 14.1761 13.7168 14.5801 13.3128L20.5449 7.34795L21.1938 6.69914C22.2687 5.62415 22.2687 3.88124 21.1938 2.80624C20.1188 1.73125 18.3759 1.73125 17.3009 2.80624Z"
                        stroke="currentColor" stroke-width="1.5"></path>
                    <path opacity="0.5"
                        d="M16.6522 3.45508C16.6522 3.45508 16.7333 4.83381 17.9499 6.05034C19.1664 7.26687 20.5451 7.34797 20.5451 7.34797M10.1002 15.5876L8.4126 13.9"
                        stroke="currentColor" stroke-width="1.5"></path>
                </svg>
                Edit </a>
        </div> --}}
    <div class="panel">
        <!-- Encabezado -->
        <div class="flex justify-between items-center flex-wrap gap-4 px-4 mb-4">
            <h2 class="text-2xl font-bold uppercase text-gray-800 dark:text-white">
                Datos de Envio: {{ $tipo1 == 1 ? 'LIMA A PROVINCIA' : 'PROVINCIA A LIMA' }}
            </h2>
            <img src="/assets/images/auth/logogkm2.png" alt="GKM Logo" class="w-40 ltr:ml-auto rtl:mr-auto shrink-0" />
        </div>

        <hr class="border-gray-300 dark:border-[#1b2e4b] my-4">

        <!-- Técnico, Info y Ejecutor -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-4">

            {{-- Técnico --}}
            <div
                class="relative space-y-2 text-sm text-gray-700 dark:text-white p-4 rounded-xl shadow-sm border border-gray-200">
                @if ($tecnico1 === 'N/A')
                    <span
                        class="absolute top-2 right-2 bg-yellow-500 text-white text-xs px-2 py-0.5 rounded-full shadow">Pendiente</span>
                @endif
                <h3 class="font-semibold text-base border-b pb-1 mb-2">Técnico del Envio:
                    {{ $tipo1 == 1 ? 'LIMA A PROVINCIA' : 'PROVINCIA A LIMA' }}</h3>
                <div><strong>Nombre:</strong> {{ $tecnico1 }}</div>
                <div><strong>Correo:</strong> {{ $correo1 }}</div>
                <div><strong>Teléfono:</strong> {{ $telefono1 }}</div>
            </div>

            {{-- Info envío/recojo --}}
            <div
                class="relative space-y-2 text-sm text-gray-700 dark:text-white p-4 rounded-xl shadow-sm border border-gray-200">
                @if ($tipoRecojo1 === 'N/A' && $tipoEnvio1 === 'N/A')
                    <span
                        class="absolute top-2 right-2 bg-yellow-500 text-white text-xs px-2 py-0.5 rounded-full shadow">Pendiente</span>
                @endif
                <h3 class="font-semibold text-base border-b pb-1 mb-2">Información del Envio:
                    {{ $tipo1 == 1 ? 'LIMA A PROVINCIA' : 'PROVINCIA A LIMA' }}</h3>
                {{-- <div><strong>ID Ticket:</strong> #{{ $ticketId }}</div> --}}
                <div><strong>Orden de Trabajo</strong></div>
                <div><strong>N° Ticket:</strong> {{ $numero_ticket }}</div>
                <div><strong>Tipo de Recojo:</strong> {{ $tipoRecojo1 }}</div>
                <div><strong>Tipo de Envío:</strong> {{ $tipoEnvio1 }}</div>
            </div>

            {{-- Ejecutor --}}
            <div
                class="relative space-y-2 text-sm text-gray-700 dark:text-white p-4 rounded-xl shadow-sm border border-gray-200">
                @if ($ejecutor === 'N/A')
                    <span
                        class="absolute top-2 right-2 bg-yellow-500 text-white text-xs px-2 py-0.5 rounded-full shadow">Pendiente</span>
                @endif
                <h3 class="font-semibold text-base border-b pb-1 mb-2">Ejecutor</h3>
                <div><strong>Nombre:</strong> {{ $ejecutor }}</div>
            </div>

            {{-- Manejo envío --}}
            @if ($manejoEnvio1)
                <div
                    class="md:col-span-2 space-y-2 text-sm text-gray-700 dark:text-white p-4 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="font-semibold text-base border-b pb-1 mb-2">
                        Manejo del Envio: {{ $tipo1 == 1 ? 'LIMA A PROVINCIA' : 'PROVINCIA A LIMA' }}
                    </h3>
                    <div class="flex justify-between flex-wrap gap-4">
                        <div><strong>N° Guía:</strong> {{ $manejoEnvio1->numero_guia }}</div>
                        <div><strong>Agencia de Recepción:</strong> {{ $manejoEnvio1->agenciaRecepcion }}</div>
                    </div>
                    <div class="flex justify-between flex-wrap gap-4">
                        <div><strong>Agencia de Envío:</strong> {{ $manejoEnvio1->agenciaEnvio }}</div>
                        <div><strong>Clave:</strong> {{ $manejoEnvio1->clave }}</div>
                    </div>
                    <div class="flex justify-between flex-wrap gap-4">
                        <div><strong>Fecha de Envío:</strong>
                            {{ \Carbon\Carbon::parse($manejoEnvio1->fecha_envio)->format('d/m/Y H:i') }}</div>
                        <div><strong>Fecha Estimada de Llegada:</strong>
                            {{ \Carbon\Carbon::parse($manejoEnvio1->fecha_llegada_estimada)->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    <div>
                        <strong>Registrado por:</strong>
                        {{ $usuarioEnvio1 ? "{$usuarioEnvio1->Nombre} {$usuarioEnvio1->apellidoPaterno}" : 'N/A' }}
                    </div>
                </div>
            @endif

            {{-- Receptor --}}
            <div
                class="relative space-y-2 text-sm text-gray-700 dark:text-white p-4 rounded-xl shadow-sm border border-gray-200">
                @if ($receptorNombre === 'N/A' && $receptorDni === 'N/A')
                    <span
                        class="absolute top-2 right-2 bg-yellow-500 text-white text-xs px-2 py-0.5 rounded-full shadow">Pendiente</span>
                @endif
                <h3 class="font-semibold text-base border-b pb-1 mb-2">Datos del Receptor</h3>
                <div><strong>Nombre:</strong> {{ $receptorNombre }}</div>
                <div><strong>DNI:</strong> {{ $receptorDni }}</div>
            </div>

        </div>


        @if ($anexos1 && count($anexos1) > 0)
            <hr class="border-gray-300 dark:border-[#1b2e4b] my-6">
            <div class="px-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Anexos de Retiro -
                    {{ $tipo1 == 1 ? 'PROVINCA' : 'LIMA' }}</h3>

                <div
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 max-h-[600px] overflow-y-auto pr-1">

                    @foreach ($anexos1 as $index => $anexo)
                        @php
                            $imagenVacia = empty($anexo->foto);
                            $fecha = \Carbon\Carbon::parse($anexo->fecha)->format('d/m/Y H:i');
                        @endphp

                        <div class="relative space-y-2 text-sm text-gray-700 dark:text-white">
                            @if ($imagenVacia)
                                <span
                                    class="absolute top-2 right-2 bg-yellow-500 text-white text-xs px-2 py-0.5 rounded-full z-10">Pendiente</span>
                            @endif

                            <a data-fancybox="anexos1" href="data:image/jpeg;base64,{{ base64_encode($anexo->foto) }}"
                                data-caption="Fecha: {{ $fecha }}"
                                class="block w-full aspect-square border border-gray-300 rounded-lg overflow-hidden">
                                <img src="data:image/jpeg;base64,{{ base64_encode($anexo->foto) }}"
                                    alt="Anexo de Retiro"
                                    class="object-contain w-full h-full {{ $imagenVacia ? 'opacity-30' : '' }}" />
                            </a>

                            <div>
                                <strong>Fecha:</strong> {{ $fecha }}
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        @endif



        <!-- Segundo bloque tipo2 -->
        @if ($tipo2)
            <hr class="border-gray-300 dark:border-[#1b2e4b] my-6">
            <div class="px-4 mb-4">
                <h2 class="text-xl font-bold uppercase text-gray-800 dark:text-white">Datos de Envio:
                    {{ $tipo2 == 1 ? 'LIMA A PROVINCIA' : 'PROVINCIA A LIMA' }}
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-4">
                <!-- Técnico -->
                <div
                    class="relative space-y-2 text-sm text-gray-700 dark:text-white p-4 rounded-xl shadow-sm border border-gray-200">
                    @if (!$tecnico2 || $correo2 == 'N/A' || $telefono2 == 'N/A')
                        <span
                            class="absolute top-2 right-2 bg-yellow-500 text-white text-xs px-2 py-0.5 rounded-full z-10">Pendiente</span>
                    @endif
                    <h3 class="font-semibold text-base border-b pb-1 mb-2">Técnico del Envio:
                        {{ $tipo2 == 1 ? 'LIMA A PROVINCIA' : 'PROVINCIA A LIMA' }}</h3>
                    <div><strong>Nombre:</strong> {{ $tecnico2 }}</div>
                    <div><strong>Correo:</strong> {{ $correo2 }}</div>
                    <div><strong>Teléfono:</strong> {{ $telefono2 }}</div>
                </div>

                <!-- Info -->
                <div
                    class="relative space-y-2 text-sm text-gray-700 dark:text-white p-4 rounded-xl shadow-sm border border-gray-200">
                    @if ($tipoRecojo2 == 'N/A' || $tipoEnvio2 == 'N/A')
                        <span
                            class="absolute top-2 right-2 bg-yellow-500 text-white text-xs px-2 py-0.5 rounded-full z-10">Pendiente</span>
                    @endif
                    <h3 class="font-semibold text-base border-b pb-1 mb-2">Información del Envio
                        {{ $tipo2 == 1 ? 'LIMA A PROVINCIA' : 'PROVINCIA A LIMA' }}</h3>
                    <div><strong>Orden de Trabajo</strong></div>
                    <div><strong>N° Ticket:</strong> {{ $numero_ticket }}</div>
                    <div><strong>Tipo de Recojo:</strong> {{ $tipoRecojo2 }}</div>
                    <div><strong>Tipo de Envío:</strong> {{ $tipoEnvio2 }}</div>
                </div>

                <!-- Recogen Paquete -->
                <div
                    class="space-y-2 text-sm text-gray-700 dark:text-white p-4 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="font-semibold text-base border-b pb-1 mb-2">Recogen Paquete</h3>
                    <div><strong>1.</strong> Gian Majuan</div>
                    <div><strong>2.</strong> Fernando</div>
                </div>

                <!-- Manejo Envío -->
                @if ($manejoEnvio2)
                    <div
                        class="md:col-span-2 relative space-y-2 text-sm text-gray-700 dark:text-white p-4 rounded-xl shadow-sm border border-gray-200">
                        @if (!$manejoEnvio2->numero_guia || !$manejoEnvio2->clave || !$usuarioEnvio2)
                            <span
                                class="absolute top-2 right-2 bg-yellow-500 text-white text-xs px-2 py-0.5 rounded-full z-10">Pendiente</span>
                        @endif

                        <h3 class="font-semibold text-base border-b pb-1 mb-2">Manejo del Envio:
                            {{ $tipo2 == 1 ? 'LIMA A PROVINCIA' : 'PROVINCIA A LIMA' }}</h3>

                        <div class="flex justify-between flex-wrap gap-4">
                            <div><strong>N° Guía:</strong> {{ $manejoEnvio2->numero_guia }}</div>
                            <div><strong>Agencia de Recepción:</strong> {{ $manejoEnvio2->agenciaRecepcion }}</div>
                        </div>

                        <div class="flex justify-between flex-wrap gap-4">
                            <div><strong>Agencia de Envío:</strong> {{ $manejoEnvio2->agenciaEnvio }}</div>
                            <div><strong>Clave:</strong> {{ $manejoEnvio2->clave }}</div>
                        </div>

                        <div class="flex justify-between flex-wrap gap-4">
                            <div><strong>Fecha de Envío:</strong>
                                {{ \Carbon\Carbon::parse($manejoEnvio2->fecha_envio)->format('d/m/Y H:i') }}</div>
                            <div><strong>Fecha Estimada de Llegada:</strong>
                                {{ \Carbon\Carbon::parse($manejoEnvio2->fecha_llegada_estimada)->format('d/m/Y H:i') }}
                            </div>
                        </div>

                        <div>
                            <strong>Registrado por:</strong>
                            {{ $usuarioEnvio2 ? "{$usuarioEnvio2->Nombre} {$usuarioEnvio2->apellidoPaterno}" : 'N/A' }}
                        </div>
                    </div>
                @endif
            </div>



            <!-- Anexos tipo2 -->
            @if ($anexos2 && count($anexos2) > 0)
                <hr class="border-gray-300 dark:border-[#1b2e4b] my-6">
                <div class="px-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                        Anexos de Retiro - {{ $tipo2 == 1 ? 'PROVINCA' : 'LIMA' }}</h3>
                    </h3>
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 max-h-[600px] overflow-y-auto pr-1">
                        @foreach ($anexos2 as $index => $anexo)
                            <div class="relative space-y-2 text-sm text-gray-700 dark:text-white">
                                <a data-fancybox="anexos2"
                                    href="data:image/jpeg;base64,{{ base64_encode($anexo->foto) }}"
                                    data-caption="Fecha: {{ \Carbon\Carbon::parse($anexo->fecha)->format('d/m/Y H:i') }}"
                                    class="block aspect-square w-full border border-gray-300 rounded-lg overflow-hidden">
                                    <img src="data:image/jpeg;base64,{{ base64_encode($anexo->foto) }}"
                                        alt="Anexo de Retiro" class="object-contain w-full h-full" />

                                    @if (strlen($anexo->foto ?? '') <= 1)
                                        <span
                                            class="absolute top-2 right-2 bg-yellow-500 text-white text-xs px-2 py-0.5 rounded-full z-10">
                                            Vacío
                                        </span>
                                    @endif
                                </a>
                                <div>
                                    <strong>Fecha:</strong>
                                    {{ \Carbon\Carbon::parse($anexo->fecha)->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        @endif
    </div>

    <!-- Fancybox JS -->
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>






    {{-- <div class="grid sm:grid-cols-2 grid-cols-1 px-4 mt-6">
                <div></div>
                <div class="ltr:text-right rtl:text-left space-y-2">
                    <div class="flex items-center">
                        <div class="flex-1">Subtotal</div>
                        <div class="w-[37%]">$3255</div>
                    </div>
                    <div class="flex items-center">
                        <div class="flex-1">Tax</div>
                        <div class="w-[37%]">$700</div>
                    </div>
                    <div class="flex items-center">
                        <div class="flex-1">Shipping Rate</div>
                        <div class="w-[37%]">$0</div>
                    </div>
                    <div class="flex items-center">
                        <div class="flex-1">Discount</div>
                        <div class="w-[37%]">$10</div>
                    </div>
                    <div class="flex items-center font-semibold text-lg">
                        <div class="flex-1">Grand Total</div>
                        <div class="w-[37%]">$3945</div>
                    </div>
                </div>
            </div> --}}

    {{-- <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data('invoicePreview', () => ({
                items: [{
                        id: 1,
                        title: 'Calendar App Customization',
                        quantity: 1,
                        price: '120',
                        amount: '120'
                    },
                    {
                        id: 2,
                        title: 'Chat App Customization',
                        quantity: 1,
                        price: '230',
                        amount: '230'
                    },
                    {
                        id: 3,
                        title: 'Laravel Integration',
                        quantity: 1,
                        price: '405',
                        amount: '405'
                    },
                    {
                        id: 4,
                        title: 'Backend UI Design',
                        quantity: 1,
                        price: '2500',
                        amount: '2500'
                    },
                ],
                columns: [{
                        key: 'id',
                        label: 'S.NO'
                    },
                    {
                        key: 'title',
                        label: 'ITEMS'
                    },
                    {
                        key: 'quantity',
                        label: 'QTY'
                    },
                    {
                        key: 'price',
                        label: 'PRICE',
                        class: 'ltr:text-right rtl:text-left'
                    },
                    {
                        key: 'amount',
                        label: 'AMOUNT',
                        class: 'ltr:text-right rtl:text-left'
                    },
                ],

                print() {
                    window.print();
                }
            }));
        });
    </script> --}}
</x-layout.default>
