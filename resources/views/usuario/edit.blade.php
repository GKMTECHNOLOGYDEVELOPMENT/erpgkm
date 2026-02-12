<x-layout.default>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{ route('usuario') }}" class="text-primary hover:underline">Usuarios</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Editar Usuario</span>
            </li>
        </ul>

        <div class="pt-5">

            <!-- 🔥 UN SOLO x-data -->
            <div x-data="tabsComponent({{ $usuario->idUsuario }})" x-init="init()">

                <!-- Tabs con iconos -->
                <ul class="sm:flex font-semibold border-b mb-5 whitespace-nowrap overflow-y-auto">
                    <li class="inline-block">
                        <a href="javascript:;" :class="{ '!border-primary text-primary': tab == 'perfil' }"
                            @click="loadTab('perfil')"
                            class="flex gap-2 p-4 border-b border-transparent hover:border-primary hover:text-primary">
                            <i class="fa-regular fa-user"></i>
                            Perfil
                        </a>
                    </li>

                    <li class="inline-block">
                        <a href="javascript:;" :class="{ '!border-primary text-primary': tab == 'info-salud' }"
                            @click="loadTab('info-salud')"
                            class="flex gap-2 p-4 border-b border-transparent hover:border-primary hover:text-primary">
                            <i class="fa-regular fa-heart"></i>
                            Familiar y Salud
                        </a>
                    </li>

                    <li class="inline-block">
                        <a href="javascript:;" :class="{ '!border-primary text-primary': tab == 'payment-details' }"
                            @click="loadTab('payment-details')"
                            class="flex gap-2 p-4 border-b border-transparent hover:border-primary hover:text-primary">
                            <i class="fa-regular fa-credit-card"></i>
                            Detalles de Pago
                        </a>
                    </li>

                    <li class="inline-block">
                        <a href="javascript:;" :class="{ '!border-primary text-primary': tab == 'informacion' }"
                            @click="loadTab('informacion')"
                            class="flex gap-2 p-4 border-b border-transparent hover:border-primary hover:text-primary">
                            <i class="fa-regular fa-briefcase"></i>
                            Datos Laborales
                        </a>
                    </li>

                    <li class="inline-block">
                        <a href="javascript:;" :class="{ '!border-primary text-primary': tab == 'asignado' }"
                            @click="loadTab('asignado')"
                            class="flex gap-2 p-4 border-b border-transparent hover:border-primary hover:text-primary">
                            <i class="fa-regular fa-user-check"></i>
                            Asignado
                        </a>
                    </li>

                    <li class="inline-block">
                        <a href="javascript:;" :class="{ '!border-primary text-primary': tab == 'preferences' }"
                            @click="loadTab('preferences')"
                            class="flex gap-2 p-4 border-b border-transparent hover:border-primary hover:text-primary">
                            <i class="fa-regular fa-file-lines"></i>
                            Documentos
                        </a>
                    </li>

                    <li class="inline-block">
                        <a href="javascript:;" :class="{ '!border-primary text-primary': tab == 'danger-zone' }"
                            @click="loadTab('danger-zone')"
                            class="flex gap-2 p-4 border-b border-transparent hover:border-primary hover:text-primary">
                            <i class="fa-regular fa-triangle-exclamation"></i>
                            Zona de Peligro
                        </a>
                    </li>
                </ul>

                <div class="panel mt-6 p-5 relative min-h-[200px]">

                    <!-- PRELOADER -->
                    <div x-show="loading"
                        class="absolute inset-0 flex items-center justify-center bg-white/70 dark:bg-[#0e1726]/70 z-10 rounded-md"
                        x-transition>
                        <div class="flex flex-col items-center gap-3">
                            <i class="fas fa-spinner fa-spin text-primary text-3xl"></i>
                            <span class="text-sm text-gray-600 dark:text-gray-300">
                                Cargando sección...
                            </span>
                        </div>
                    </div>

                    <!-- CONTENIDO -->
                    <div x-html="content"></div>

                </div>


            </div>

        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.5/dist/signature_pad.umd.min.js"></script>
    <script src="{{ asset('assets/js/ubigeo.js') }}"></script>
    <script src="{{ asset('assets/js/usuario/tabs/datos-laborales.js') }}"></script>
    <script src="{{ asset('assets/js/usuario/tabs/danger-zone.js') }}"></script>
    <script src="{{ asset('assets/js/usuario/tabs/payment-details.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


    <script>
        function tabsComponent(usuarioId) {
            return {
                tab: 'perfil',
                content: '',
                loading: false, // 👈 nuevo

                async init() {
                    await this.loadTab(this.tab);
                },

                async loadTab(tabName) {
                    this.tab = tabName;
                    this.loading = true; // 👈 activar preload

                    try {
                        let response = await fetch(`/usuario/${usuarioId}/tab/${tabName}`);

                        if (!response.ok) {
                            throw new Error('Error cargando tab');
                        }

                        this.content = await response.text();
                    } catch (error) {
                        console.error(error);
                        this.content = `<div class="text-red-500">Error cargando contenido</div>`;
                    } finally {
                        this.loading = false; // 👈 apagar preload
                    }
                }
            }
        }
    </script>

</x-layout.default>
