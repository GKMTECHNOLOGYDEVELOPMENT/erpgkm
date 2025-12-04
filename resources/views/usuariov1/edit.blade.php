<x-layout.default>
    <!-- Librerías -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">

    <div>
        <!-- Breadcrumb -->
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{ route('usuario') }}" class="text-primary hover:underline">Usuarios</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Editar Usuario</span>
            </li>
        </ul>

        <div class="pt-5" x-data="tabsData()">
            <!-- Tabs -->
            <ul class="sm:flex font-semibold border-b border-[#ebedf2] dark:border-[#191e3a] mb-5 whitespace-nowrap overflow-y-auto">
                @php
                    $permisos = [
                        'perfil' => 'VER PERFIL USUARIO',
                        'payment-details' => 'VER DETALLES DE PAGO',
                        'preferences' => 'VER LEGAJO',
                        'informacion' => 'VER INFORMACION IMPORTANTE',
                        'danger-zone' => 'VER ZONA DE PELIGRO',
                    ];
                @endphp

                @foreach($permisos as $tabKey => $permiso)
                    @if(\App\Helpers\PermisoHelper::tienePermiso($permiso))
                        <li class="inline-block">
                            <a href="javascript:;"
                               class="flex gap-2 p-4 border-b border-transparent hover:border-primary hover:text-primary"
                               :class="{ '!border-primary text-primary': tab === '{{ $tabKey }}' }"
                               @click="tab='{{ $tabKey }}'">
                                @if($tabKey == 'perfil') <i class="fas fa-user"></i> @endif
                                @if($tabKey == 'payment-details') <i class="fas fa-credit-card"></i> @endif
                                @if($tabKey == 'preferences') <i class="fas fa-folder"></i> @endif
                                @if($tabKey == 'informacion') <i class="fas fa-info-circle"></i> @endif
                                @if($tabKey == 'danger-zone') <i class="fas fa-exclamation-triangle"></i> @endif
                                {{ ucfirst(str_replace('-', ' ', $tabKey)) }}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>

            <!-- Contenido de Tabs -->
            <div class="panel mt-6 p-5 max-w-4x2 mx-auto">
                @php
                    $tabsDisponibles = array_filter($permisos, function($permiso) {
                        return \App\Helpers\PermisoHelper::tienePermiso($permiso);
                    });
                @endphp

                @if(count($tabsDisponibles) === 0)
                    <div class="text-center text-gray-500 font-semibold">
                        Usted no tiene permisos para ver ninguna sección.
                    </div>
                @else
                    @if(\App\Helpers\PermisoHelper::tienePermiso('VER PERFIL USUARIO'))
                        <div x-show="tab === 'perfil'">
                            @include('usuario.tabs.perfil.index')
                        </div>
                    @endif

                    @if(\App\Helpers\PermisoHelper::tienePermiso('VER DETALLES DE PAGO'))
                        <div x-show="tab === 'payment-details'">
                            @include('usuario.tabs.detalles-pago.index')
                        </div>
                    @endif

                    @if(\App\Helpers\PermisoHelper::tienePermiso('VER LEGAJO'))
                        <div x-show="tab === 'preferences'">
                            @include('usuario.tabs.configuracion.index')
                        </div>
                    @endif

                    @if(\App\Helpers\PermisoHelper::tienePermiso('VER INFORMACION IMPORTANTE'))
                        <div x-show="tab === 'informacion'">
                            @include('usuario.tabs.informacion.index')
                        </div>
                    @endif

                    @if(\App\Helpers\PermisoHelper::tienePermiso('VER ZONA DE PELIGRO'))
                        <div x-show="tab === 'danger-zone'">
                            @include('usuario.tabs.detalles.index')
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <script>
        function tabsData() {
            return {
                tab: {!! count($tabsDisponibles) > 0 ? "'" . array_key_first($tabsDisponibles) . "'" : "null" !!},
            }
        }
    </script>

    <script src="{{ asset('assets/js/ubigeo.js') }}"></script>
</x-layout.default>
