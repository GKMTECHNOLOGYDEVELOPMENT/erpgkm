<x-layout.default>

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

                <!-- Tabs -->
                <ul class="sm:flex font-semibold border-b mb-5 whitespace-nowrap overflow-y-auto">

                    <li class="inline-block">
                        <a href="javascript:;" :class="{ '!border-primary text-primary': tab == 'perfil' }"
                            @click="loadTab('perfil')"
                            class="flex gap-2 p-4 border-b border-transparent hover:border-primary hover:text-primary">
                            Perfil
                        </a>
                    </li>

                    <li class="inline-block">
                        <a href="javascript:;" :class="{ '!border-primary text-primary': tab == 'payment-details' }"
                            @click="loadTab('payment-details')"
                            class="flex gap-2 p-4 border-b border-transparent hover:border-primary hover:text-primary">
                            Detalles de Pago
                        </a>
                    </li>

                    <li class="inline-block">
                        <a href="javascript:;" :class="{ '!border-primary text-primary': tab == 'informacion' }"
                            @click="loadTab('informacion')"
                            class="flex gap-2 p-4 border-b border-transparent hover:border-primary hover:text-primary">
                            Información
                        </a>
                    </li>

                    <li class="inline-block">
                        <a href="javascript:;" :class="{ '!border-primary text-primary': tab == 'asignado' }"
                            @click="loadTab('asignado')"
                            class="flex gap-2 p-4 border-b border-transparent hover:border-primary hover:text-primary">
                            Asignado
                        </a>
                    </li>

                    <li class="inline-block">
                        <a href="javascript:;" :class="{ '!border-primary text-primary': tab == 'preferences' }"
                            @click="loadTab('preferences')"
                            class="flex gap-2 p-4 border-b border-transparent hover:border-primary hover:text-primary">
                            Preferencias
                        </a>
                    </li>

                    <li class="inline-block">
                        <a href="javascript:;" :class="{ '!border-primary text-primary': tab == 'danger-zone' }"
                            @click="loadTab('danger-zone')"
                            class="flex gap-2 p-4 border-b border-transparent hover:border-primary hover:text-primary">
                            Zona de Peligro
                        </a>
                    </li>

                </ul>

                <!-- 🔥 CONTENIDO DINÁMICO -->
                <div class="panel mt-6 p-5" x-html="content">
                </div>

            </div>

        </div>
    </div>

    <script src="{{ asset('assets/js/ubigeo.js') }}"></script>
    <script src="{{ asset('assets/js/usuario/tabs/danger-zone.js') }}"></script>
    <script src="{{ asset('assets/js/usuario/tabs/payment-details.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.5/dist/signature_pad.umd.min.js"></script>

    <script>
        function tabsComponent(usuarioId) {
            return {
                tab: 'perfil',
                content: '',

                async init() {
                    await this.loadTab(this.tab);
                },

                async loadTab(tabName) {
                    this.tab = tabName;

                    try {
                        let response = await fetch(`/usuario/${usuarioId}/tab/${tabName}`);

                        if (!response.ok) {
                            throw new Error('Error cargando tab');
                        }

                        this.content = await response.text();
                    } catch (error) {
                        console.error(error);
                        this.content = `<div class="text-red-500">Error cargando contenido</div>`;
                    }
                }
            }
        }
    </script>

</x-layout.default>
