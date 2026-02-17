<template x-if="tab === 'perfil'">
    <div>
        <!-- ============================================ -->
        <!-- SECCIÓN 1: INFORMACIÓN GENERAL -->
        <!-- ============================================ -->
        <form data-userid="{{ $usuario->idUsuario }}" method="POST" enctype="multipart/form-data" id="update-forma"
            class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-4 mb-5 bg-white dark:bg-[#0e1726]">

            <div class="flex items-center mb-5">
                <div class="w-1 h-6 bg-primary rounded-full mr-3"></div>
                <h6 class="text-lg font-bold text-gray-800 dark:text-white">INFORMACIÓN GENERAL</h6>
            </div>
            <div class="flex flex-col sm:flex-row">
                <!-- Imagen de perfil -->
                <div class="ltr:sm:mr-4 rtl:sm:ml-4 w-full sm:w-2/12 mb-5">
                    <label for="profile-image">
                        <img id="profile-img"
                            src="{{ $usuario->avatar ? 'data:image/jpeg;base64,' . base64_encode($usuario->avatar) : '/assets/images/profile-34.jpeg' }}"
                            alt="image"
                            class="w-20 h-20 md:w-32 md:h-32 rounded-full object-cover mx-auto cursor-pointer" />
                    </label>
                    <input type="file" id="profile-image" name="profile-image" style="display:none;" accept="image/*"
                        onchange="previewImage(event)" />
                </div>

                <!-- Formulario de campos -->
                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="Nombre" class="flex items-center gap-1">
                            <i class="fas fa-user text-gray-500 text-xs"></i>
                            Nombre Completo
                        </label>
                        <input id="Nombre" name="Nombre" type="text" value="{{ $usuario->Nombre }}"
                            class="form-input" />
                    </div>
                    <div>
                        <label for="apellidoPaterno" class="flex items-center gap-1">
                            <i class="fas fa-user-tag text-gray-500 text-xs"></i>
                            Apellido Paterno
                        </label>
                        <input id="apellidoPaterno" name="apellidoPaterno" type="text"
                            value="{{ $usuario->apellidoPaterno }}" class="form-input" />
                    </div>
                    <div>
                        <label for="apellidoMaterno" class="flex items-center gap-1">
                            <i class="fas fa-user-tag text-gray-500 text-xs"></i>
                            Apellido Materno
                        </label>
                        <input id="apellidoMaterno" name="apellidoMaterno" type="text"
                            value="{{ $usuario->apellidoMaterno }}" class="form-input" />
                    </div>

                    <!-- SEXO desde tabla usuarios -->
                    <div>
                        <label for="idSexo" class="flex items-center gap-1 text-sm font-medium">
                            <i class="fas fa-venus-mars text-gray-500 text-xs"></i>
                            Sexo
                        </label>
                        <select id="idSexo" name="idSexo" class="form-input w-full">
                            <option value="">Seleccione</option>
                            @foreach ($sexos ?? [] as $sexo)
                                <option value="{{ $sexo->idSexo }}" {{ $usuario->idSexo == $sexo->idSexo ? 'selected' : '' }}>
                                    {{ $sexo->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="idTipoDocumento" class="flex items-center gap-1 text-sm font-medium">
                            <i class="fas fa-id-card text-gray-500 text-xs"></i>
                            Tipo Documento
                        </label>
                        <select id="idTipoDocumento" name="idTipoDocumento" class="form-input w-full">
                            <option value="" disabled>Seleccionar Tipo Documento</option>
                            @foreach ($tiposDocumento as $tipoDocumento)
                                <option value="{{ $tipoDocumento->idTipoDocumento }}"
                                    {{ $tipoDocumento->idTipoDocumento == $usuario->idTipoDocumento ? 'selected' : '' }}>
                                    {{ $tipoDocumento->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="documento" class="flex items-center gap-1">
                            <i class="fas fa-qrcode text-gray-500 text-xs"></i>
                            Documento
                        </label>
                        <input id="documento" name="documento" type="text" value="{{ $usuario->documento }}"
                            class="form-input" />
                    </div>
                    <div>
                        <label for="telefono" class="flex items-center gap-1">
                            <i class="fas fa-phone text-gray-500 text-xs"></i>
                            Teléfono
                        </label>
                        <input id="telefono" type="text" name="telefono" value="{{ $usuario->telefono }}"
                            class="form-input" />
                    </div>
                    <div>
                        <label for="estadocivil" class="flex items-center gap-1">
                            <i class="fas fa-heart text-gray-500 text-xs"></i>
                            Estado Civil
                        </label>
                        <select id="estadocivil" name="estadocivil" class="form-input">
                            <option value="" disabled>Seleccionar Estado Civil</option>
                            <option value="1" {{ $usuario->estadocivil == 1 ? 'selected' : '' }}>Soltero</option>
                            <option value="2" {{ $usuario->estadocivil == 2 ? 'selected' : '' }}>Casado</option>
                            <option value="3" {{ $usuario->estadocivil == 3 ? 'selected' : '' }}>Divorciado</option>
                            <option value="4" {{ $usuario->estadocivil == 4 ? 'selected' : '' }}>Viudo</option>
                        </select>
                    </div>
                    <div>
                        <label for="correo" class="flex items-center gap-1">
                            <i class="fas fa-envelope text-blue-500 text-xs"></i>
                            Correo Corporativo
                            <span class="text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded">Oficial</span>
                        </label>
                        <input id="correo" name="correo" type="email" value="{{ $usuario->correo }}"
                            class="form-input border-blue-300" placeholder="correo@empresa.com" />
                        <p class="text-xs text-blue-500 mt-1">Correo oficial para comunicaciones de la empresa</p>
                    </div>
                    <div>
                        <label for="correo_personal" class="flex items-center gap-1">
                            <i class="fas fa-envelope text-green-500 text-xs"></i>
                            Correo Personal
                            <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded">Personal</span>
                        </label>
                        <input id="correo_personal" name="correo_personal" type="email"
                            value="{{ $usuario->correo_personal ?? '' }}" class="form-input border-green-300"
                            placeholder="correo@gmail.com" />
                        <p class="text-xs text-green-500 mt-1">Correo personal para comunicaciones personales</p>
                    </div>

                    <div class="sm:col-span-2 mt-3">
                        <button type="button" id="update-button" class="btn btn-primary mr-2">
                            <i class="fas fa-save mr-1"></i> Actualizar Información General
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- ============================================ -->
        <!-- SECCIÓN 2: FECHA Y LUGAR DE NACIMIENTO -->
        <!-- ============================================ -->
        <div class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-4 mb-5 bg-white dark:bg-[#0e1726]">
            <div class="flex items-center mb-5">
                <div class="w-1 h-6 bg-blue-500 rounded-full mr-3"></div>
                <h6 class="text-lg font-bold text-gray-800 dark:text-white">FECHA Y LUGAR DE NACIMIENTO</h6>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Fecha de nacimiento -->
                <div class="bg-gradient-to-br from-blue-50/50 to-transparent dark:from-[#1e293b] p-5 rounded-lg border border-blue-100/50 dark:border-blue-900/30">
                    <div class="flex items-center gap-2 mb-3">
                        <i class="fas fa-calendar-alt text-blue-600 dark:text-blue-400 text-lg"></i>
                        <label class="font-semibold text-gray-700 dark:text-gray-300 leading-none required">
                            Fecha de Nacimiento
                            <span class="ml-1 text-xs text-gray-400 font-normal align-middle">
                                (obligatorio)
                            </span>
                        </label>
                    </div>

                    @php
                        $fechaNacimiento = $usuario->fechaNacimiento;
                        $dia = $fechaNacimiento ? date('d', strtotime($fechaNacimiento)) : '';
                        $mes = $fechaNacimiento ? date('m', strtotime($fechaNacimiento)) : '';
                        $anio = $fechaNacimiento ? date('Y', strtotime($fechaNacimiento)) : '';
                        $edad = $fechaNacimiento ? \Carbon\Carbon::parse($fechaNacimiento)->age : '';
                    @endphp

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <input type="number" id="nacimiento_dia" name="nacimiento_dia" min="1" max="31" 
                                placeholder="Día" value="{{ $dia }}"
                                class="form-input w-full text-center placeholder:text-gray-400" />
                        </div>
                        <div>
                            <select id="nacimiento_mes" name="nacimiento_mes" class="form-input w-full">
                                <option value="">Mes</option>
                                @foreach(range(1, 12) as $mesNum)
                                    @php
                                        $nombreMes = \Carbon\Carbon::create()->month($mesNum)->locale('es')->monthName;
                                    @endphp
                                    <option value="{{ $mesNum }}" {{ $mes == $mesNum ? 'selected' : '' }}>
                                        {{ $nombreMes }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <input type="number" id="nacimiento_anio" name="nacimiento_anio" min="1900" max="{{ date('Y') }}" 
                                placeholder="Año" value="{{ $anio }}"
                                class="form-input w-full text-center placeholder:text-gray-400" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-3">
                        <div class="text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Edad:</span>
                            <span class="ml-2 font-semibold text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900/30 px-3 py-1 rounded-full edad-span">
                                {{ $edad ? $edad . ' años' : '-- años' }}
                            </span>
                        </div>
                        <button type="button" id="guardar-fecha-nacimiento" class="btn btn-sm btn-primary">
                            <i class="fas fa-save mr-1"></i> Guardar Fecha
                        </button>
                    </div>
                </div>

                <!-- Lugar de nacimiento -->
                <div class="bg-gradient-to-br from-indigo-50/50 to-transparent dark:from-[#1e293b] p-5 rounded-lg border border-indigo-100/50 dark:border-indigo-900/30">
                    <div class="flex items-center gap-2 mb-3">
                        <i class="fas fa-map-marker-alt text-indigo-600 dark:text-indigo-400 text-lg"></i>
                        <label class="font-semibold text-gray-700 dark:text-gray-300 leading-none">
                            Lugar de Nacimiento
                        </label>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <select id="nacimiento_departamento" name="nacimiento_departamento" class="form-input w-full">
                                <option value="">Departamento</option>
                                @foreach($departamentos as $depto)
                                    <option value="{{ $depto['id_ubigeo'] }}"
                                        {{ ($fichaGeneral->nacimientoDepartamento ?? '') == $depto['id_ubigeo'] ? 'selected' : '' }}>
                                        {{ $depto['nombre_ubigeo'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select id="nacimiento_provincia" name="nacimiento_provincia" class="form-input w-full" 
                                {{ empty($fichaGeneral->nacimientoDepartamento) ? 'disabled' : '' }}>
                                <option value="">Provincia</option>
                                @if(!empty($provinciasNacimiento))
                                    @foreach($provinciasNacimiento as $provincia)
                                        <option value="{{ $provincia['id_ubigeo'] }}"
                                            {{ ($fichaGeneral->nacimientoProvincia ?? '') == $provincia['id_ubigeo'] ? 'selected' : '' }}>
                                            {{ $provincia['nombre_ubigeo'] }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div>
                            <select id="nacimiento_distrito" name="nacimiento_distrito" class="form-input w-full"
                                {{ empty($fichaGeneral->nacimientoProvincia) ? 'disabled' : '' }}>
                                <option value="">Distrito</option>
                                @if(!empty($distritosNacimiento))
                                    @foreach($distritosNacimiento as $distrito)
                                        <option value="{{ $distrito['id_ubigeo'] }}"
                                            {{ ($fichaGeneral->nacimientoDistrito ?? '') == $distrito['id_ubigeo'] ? 'selected' : '' }}>
                                            {{ $distrito['nombre_ubigeo'] }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center justify-between mt-3">
                        <p class="text-xs text-gray-400 flex items-center gap-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Seleccione un departamento para habilitar provincia y distrito
                        </p>
                        <button type="button" id="guardar-lugar-nacimiento" class="btn btn-sm btn-primary">
                            <i class="fas fa-save mr-1"></i> Guardar Lugar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- SECCIÓN 3: SEGURO Y PENSIÓN -->
        <!-- ============================================ -->
        <div class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-4 mb-5 bg-white dark:bg-[#0e1726]">
            <div class="flex items-center mb-5">
                <div class="w-1 h-6 bg-green-500 rounded-full mr-3"></div>
                <h6 class="text-lg font-bold text-gray-800 dark:text-white">SEGURO Y PENSIÓN</h6>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Seguro de salud -->
                <div class="bg-gradient-to-br from-green-50/50 to-transparent dark:from-[#1e293b] p-5 rounded-lg border border-green-100/50 dark:border-green-900/30">
                    <div class="flex items-center gap-2 mb-4">
                        <i class="fas fa-shield-alt text-green-600 dark:text-green-400 text-lg"></i>
                        <label class="font-semibold text-gray-700 dark:text-gray-300 leading-none">
                            Tipo de Seguro de Salud
                        </label>
                    </div>

                    @php $seguroSalud = $fichaGeneral->seguroSalud ?? ''; @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-green-300 dark:hover:border-green-700 transition-colors">
                            <input type="radio" id="sis" name="seguroSalud" value="SIS" class="w-4 h-4 text-green-600"
                                {{ $seguroSalud == 'SIS' ? 'checked' : '' }}>
                            <label for="sis" class="ml-2 text-sm font-medium cursor-pointer flex-1">SIS</label>
                        </div>
                        <div class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-green-300 dark:hover:border-green-700 transition-colors">
                            <input type="radio" id="essalud" name="seguroSalud" value="ESSALUD" class="w-4 h-4 text-green-600"
                                {{ $seguroSalud == 'ESSALUD' ? 'checked' : '' }}>
                            <label for="essalud" class="ml-2 text-sm font-medium cursor-pointer flex-1">ESSALUD</label>
                        </div>
                        <div class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-green-300 dark:hover:border-green-700 transition-colors">
                            <input type="radio" id="eps" name="seguroSalud" value="EPS" class="w-4 h-4 text-green-600"
                                {{ $seguroSalud == 'EPS' ? 'checked' : '' }}>
                            <label for="eps" class="ml-2 text-sm font-medium cursor-pointer flex-1">EPS</label>
                        </div>
                    </div>
                </div>

                <!-- Sistema de pensiones -->
                <div class="bg-gradient-to-br from-purple-50/50 to-transparent dark:from-[#1e293b] p-5 rounded-lg border border-purple-100/50 dark:border-purple-900/30">
                    <div class="flex items-center gap-2 mb-4">
                        <i class="fas fa-piggy-bank text-purple-600 dark:text-purple-400 text-lg"></i>
                        <label class="font-semibold text-gray-700 dark:text-gray-300 leading-none">
                            Sistema de Pensiones
                        </label>
                    </div>

                    @php $sistemaPensiones = $fichaGeneral->sistemaPensiones ?? ''; @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">
                        <div class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-purple-300 dark:hover:border-purple-700 transition-colors">
                            <input type="radio" id="onp" name="sistemaPensiones" value="ONP" class="w-4 h-4 text-purple-600"
                                {{ $sistemaPensiones == 'ONP' ? 'checked' : '' }}>
                            <label for="onp" class="ml-2 text-sm font-medium cursor-pointer">ONP</label>
                        </div>
                        <div class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-purple-300 dark:hover:border-purple-700 transition-colors">
                            <input type="radio" id="afp" name="sistemaPensiones" value="AFP" class="w-4 h-4 text-purple-600"
                                {{ $sistemaPensiones == 'AFP' ? 'checked' : '' }}>
                            <label for="afp" class="ml-2 text-sm font-medium cursor-pointer">AFP</label>
                        </div>
                        <div class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-purple-300 dark:hover:border-purple-700 transition-colors">
                            <input type="radio" id="noaplica" name="sistemaPensiones" value="NA" class="w-4 h-4 text-purple-600"
                                {{ $sistemaPensiones == 'NA' ? 'checked' : '' }}>
                            <label for="noaplica" class="ml-2 text-sm font-medium cursor-pointer">No Aplica</label>
                        </div>
                    </div>

                    <!-- AFP compañía -->
                    <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center">
                            <i class="fas fa-building text-gray-400 mr-2 text-xs"></i>
                            <label for="afpCompania" class="block text-sm font-medium text-gray-600 dark:text-gray-400">Compañía AFP</label>
                        </div>
                        <select id="afpCompania" name="afpCompania" class="form-input w-full mt-2" 
                            {{ $sistemaPensiones != 'AFP' ? 'disabled' : '' }}>
                            <option value="">Seleccione AFP</option>
                            <option value="Integra" {{ ($fichaGeneral->afpCompania ?? '') == 'Integra' ? 'selected' : '' }}>Integra</option>
                            <option value="Horizonte" {{ ($fichaGeneral->afpCompania ?? '') == 'Horizonte' ? 'selected' : '' }}>Horizonte</option>
                            <option value="Profuturo" {{ ($fichaGeneral->afpCompania ?? '') == 'Profuturo' ? 'selected' : '' }}>Profuturo</option>
                            <option value="Prima" {{ ($fichaGeneral->afpCompania ?? '') == 'Prima' ? 'selected' : '' }}>Prima</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end mt-4">
                <button type="button" id="guardar-seguro-pension" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Guardar Seguro y Pensión
                </button>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- SECCIÓN 4: DIRECCIÓN ACTUAL -->
        <!-- ============================================ -->
        <div class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-4 bg-white dark:bg-[#0e1726]">
            <div class="flex items-center mb-5">
                <div class="w-1 h-6 bg-amber-500 rounded-full mr-3"></div>
                <h6 class="text-lg font-bold text-gray-800 dark:text-white">DIRECCIÓN ACTUAL</h6>
            </div>

            <form id="direccion-form">
                @csrf
                @method('PUT')

                <div class="mb-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="nacionalidad" class="text-sm font-medium flex items-center">
                            <i class="fas fa-flag mr-2 text-gray-400"></i>
                            Nacionalidad
                        </label>
                        <input id="nacionalidad" name="nacionalidad" type="text"
                            value="{{ old('nacionalidad', $usuario->nacionalidad) }}" class="form-input mt-1 w-full"
                            placeholder="Ej: Peruana" />
                    </div>
                    <div>
                        <label for="departamento" class="text-sm font-medium flex items-center">
                            <i class="fas fa-map mr-2 text-gray-400"></i>
                            Departamento
                        </label>
                        <select id="departamento" name="departamento" class="form-input w-full mt-1">
                            <option value="" disabled>Seleccionar Departamento</option>
                            @foreach ($departamentos as $departamento)
                                <option value="{{ $departamento['id_ubigeo'] }}"
                                    {{ old('departamento', $usuario->departamento) == $departamento['id_ubigeo'] ? 'selected' : '' }}>
                                    {{ $departamento['nombre_ubigeo'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="provincia" class="text-sm font-medium flex items-center">
                            <i class="fas fa-location-dot mr-2 text-gray-400"></i>
                            Provincia
                        </label>
                        <select id="provincia" name="provincia" class="form-input w-full mt-1">
                            <option value="" disabled>Seleccionar Provincia</option>
                            @foreach ($provinciasDelDepartamento as $provincia)
                                <option value="{{ $provincia['id_ubigeo'] }}"
                                    {{ old('provincia', $usuario->provincia) == $provincia['id_ubigeo'] ? 'selected' : '' }}>
                                    {{ $provincia['nombre_ubigeo'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="distrito" class="text-sm font-medium flex items-center">
                            <i class="fas fa-location-dot mr-2 text-gray-400"></i>
                            Distrito
                        </label>
                        <select id="distrito" name="distrito" class="form-input w-full mt-1">
                            <option value="" disabled>Seleccionar Distrito</option>
                            @foreach ($distritosDeLaProvincia as $distrito)
                                <option value="{{ $distrito['id_ubigeo'] }}"
                                    {{ old('distrito', $usuario->distrito) == $distrito['id_ubigeo'] ? 'selected' : '' }}>
                                    {{ $distrito['nombre_ubigeo'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="direccion" class="text-sm font-medium flex items-center">
                            <i class="fas fa-home mr-2 text-gray-400"></i>
                            Dirección
                        </label>
                        <input id="direccion" name="direccion" type="text" class="form-input w-full mt-1"
                            value="{{ old('direccion', $usuario->direccion) }}"
                            placeholder="Ingrese su dirección completa">
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn btn-primary px-6 py-2 flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Actualizar dirección
                    </button>
                </div>
            </form>
        </div>

        <!-- ============================================ -->
        <!-- SEPARADOR VISUAL -->
        <!-- ============================================ -->
        <div class="my-6">
            <div class="flex items-center justify-center gap-4">
                <div class="h-px w-10 bg-gradient-to-r from-transparent to-gray-300 dark:to-gray-700"></div>
                <div class="flex items-center gap-2 text-gray-400 dark:text-gray-600">
                    <i class="fas fa-arrow-down text-xs"></i>
                    <span class="text-xs font-medium uppercase tracking-wider">Información Académica</span>
                    <i class="fas fa-arrow-down text-xs"></i>
                </div>
                <div class="h-px w-10 bg-gradient-to-l from-transparent to-gray-300 dark:to-gray-700"></div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- SECCIÓN 5: INFORMACIÓN ACADÉMICA -->
        <!-- ============================================ -->
        <div class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-4 mb-5 bg-white dark:bg-[#0e1726]">
            <div class="flex items-center mb-4">
                <div class="w-1 h-6 bg-secondary rounded-full mr-3"></div>
                <h6 class="text-lg font-bold text-gray-800 dark:text-white">INFORMACIÓN ACADEMICA</h6>
            </div>

            <!-- SECUNDARIA -->
            <div class="mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center mb-3">
                    <div class="w-5 h-5 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                        <i class="fas fa-school text-blue-600 text-xs"></i>
                    </div>
                    <span class="font-semibold text-gray-800 dark:text-gray-200">Secundaria</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-check-circle text-blue-600 text-xs"></i>
                            ¿Terminó?
                        </label>
                        <div class="flex space-x-4 mt-1">
                            <div class="flex items-center">
                                <input type="radio" name="secundaria_termino" value="1" class="w-4 h-4 text-blue-600"
                                    {{ isset($estudioSecundaria) && $estudioSecundaria->termino == 1 ? 'checked' : '' }}>
                                <label class="ml-1 text-sm flex items-center gap-1">
                                    <i class="fas fa-check text-green-600 text-xs"></i> SI
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" name="secundaria_termino" value="0" class="w-4 h-4 text-blue-600"
                                    {{ !isset($estudioSecundaria) || $estudioSecundaria->termino == 0 ? 'checked' : '' }}>
                                <label class="ml-1 text-sm flex items-center gap-1">
                                    <i class="fas fa-times text-red-600 text-xs"></i> NO
                                </label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-building text-blue-600 text-xs"></i>
                            Centro de Estudios
                        </label>
                        <input type="text" name="secundaria_centro" class="form-input mt-1 w-full estudio-input" 
                            value="{{ $estudioSecundaria->centroEstudios ?? '' }}"
                            placeholder="Nombre del colegio"
                            data-nivel="secundaria"
                            {{ !isset($estudioSecundaria) ? 'disabled' : '' }}>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-calendar text-blue-600 text-xs"></i>
                            Año de culminación
                        </label>
                        <input type="text" name="secundaria_fin" class="form-input mt-1 w-full estudio-input" 
                            value="{{ $estudioSecundaria ? date('Y', strtotime($estudioSecundaria->fechaFin)) : '' }}"
                            placeholder="Ej: 2018"
                            data-nivel="secundaria"
                            {{ !isset($estudioSecundaria) ? 'disabled' : '' }}>
                    </div>
                </div>
            </div>

            <!-- TÉCNICO / INSTITUTO -->
            <div class="mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center mb-3">
                    <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center mr-2">
                        <i class="fas fa-tools text-green-600 text-xs"></i>
                    </div>
                    <span class="font-semibold text-gray-800 dark:text-gray-200">Técnico / Instituto</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-check-circle text-green-600 text-xs"></i>
                            ¿Terminó?
                        </label>
                        <div class="flex space-x-4 mt-1">
                            <div class="flex items-center">
                                <input type="radio" name="tecnico_termino" value="1" class="w-4 h-4 text-green-600"
                                    {{ isset($estudioTecnico) && $estudioTecnico->termino == 1 ? 'checked' : '' }}>
                                <label class="ml-1 text-sm flex items-center gap-1">
                                    <i class="fas fa-check text-green-600 text-xs"></i> SI
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" name="tecnico_termino" value="0" class="w-4 h-4 text-green-600"
                                    {{ !isset($estudioTecnico) || $estudioTecnico->termino == 0 ? 'checked' : '' }}>
                                <label class="ml-1 text-sm flex items-center gap-1">
                                    <i class="fas fa-times text-red-600 text-xs"></i> NO
                                </label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-building text-green-600 text-xs"></i>
                            Centro de Estudios
                        </label>
                        <input type="text" name="tecnico_centro" class="form-input mt-1 w-full estudio-input" 
                            value="{{ $estudioTecnico->centroEstudios ?? '' }}"
                            placeholder="Nombre del instituto"
                            data-nivel="tecnico"
                            {{ !isset($estudioTecnico) ? 'disabled' : '' }}>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-book text-green-600 text-xs"></i>
                            Carrera
                        </label>
                        <input type="text" name="tecnico_especialidad" class="form-input mt-1 w-full estudio-input" 
                            value="{{ $estudioTecnico->especialidad ?? '' }}"
                            placeholder="Ej: Administración"
                            data-nivel="tecnico"
                            {{ !isset($estudioTecnico) ? 'disabled' : '' }}>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-calendar-plus text-green-600 text-xs"></i>
                            Año inicio
                        </label>
                        <input type="text" name="tecnico_inicio" class="form-input mt-1 w-full estudio-input" 
                            value="{{ $estudioTecnico ? date('Y', strtotime($estudioTecnico->fechaInicio)) : '' }}"
                            placeholder="Ej: 2019"
                            data-nivel="tecnico"
                            {{ !isset($estudioTecnico) ? 'disabled' : '' }}>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-calendar-check text-green-600 text-xs"></i>
                            Año fin
                        </label>
                        <input type="text" name="tecnico_fin" class="form-input mt-1 w-full estudio-input" 
                            value="{{ $estudioTecnico ? date('Y', strtotime($estudioTecnico->fechaFin)) : '' }}"
                            placeholder="Ej: 2022"
                            data-nivel="tecnico"
                            {{ !isset($estudioTecnico) ? 'disabled' : '' }}>
                    </div>
                </div>
            </div>

            <!-- UNIVERSITARIO -->
            <div class="mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center mb-3">
                    <div class="w-5 h-5 bg-indigo-100 rounded-full flex items-center justify-center mr-2">
                        <i class="fas fa-university text-indigo-600 text-xs"></i>
                    </div>
                    <span class="font-semibold text-gray-800 dark:text-gray-200">Universitario</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-check-circle text-indigo-600 text-xs"></i>
                            ¿Terminó?
                        </label>
                        <div class="flex space-x-4 mt-1">
                            <div class="flex items-center">
                                <input type="radio" name="universitario_termino" value="1" class="w-4 h-4 text-indigo-600"
                                    {{ isset($estudioUniversitario) && $estudioUniversitario->termino == 1 ? 'checked' : '' }}>
                                <label class="ml-1 text-sm flex items-center gap-1">
                                    <i class="fas fa-check text-green-600 text-xs"></i> SI
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" name="universitario_termino" value="0" class="w-4 h-4 text-indigo-600"
                                    {{ !isset($estudioUniversitario) || $estudioUniversitario->termino == 0 ? 'checked' : '' }}>
                                <label class="ml-1 text-sm flex items-center gap-1">
                                    <i class="fas fa-times text-red-600 text-xs"></i> NO
                                </label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-building-columns text-indigo-600 text-xs"></i>
                            Universidad
                        </label>
                        <input type="text" name="universitario_centro" class="form-input mt-1 w-full estudio-input" 
                            value="{{ $estudioUniversitario->centroEstudios ?? '' }}"
                            placeholder="Nombre de la universidad"
                            data-nivel="universitario"
                            {{ !isset($estudioUniversitario) ? 'disabled' : '' }}>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-graduation-cap text-indigo-600 text-xs"></i>
                            Carrera
                        </label>
                        <input type="text" name="universitario_especialidad" class="form-input mt-1 w-full estudio-input" 
                            value="{{ $estudioUniversitario->especialidad ?? '' }}"
                            placeholder="Ej: Ingeniería"
                            data-nivel="universitario"
                            {{ !isset($estudioUniversitario) ? 'disabled' : '' }}>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-award text-indigo-600 text-xs"></i>
                            Grado
                        </label>
                        <input type="text" name="universitario_grado" class="form-input mt-1 w-full estudio-input" 
                            value="{{ $estudioUniversitario->gradoAcademico ?? '' }}"
                            placeholder="Ej: Bachiller"
                            data-nivel="universitario"
                            {{ !isset($estudioUniversitario) ? 'disabled' : '' }}>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-calendar-plus text-indigo-600 text-xs"></i>
                            Año inicio
                        </label>
                        <input type="text" name="universitario_inicio" class="form-input mt-1 w-full estudio-input" 
                            value="{{ $estudioUniversitario ? date('Y', strtotime($estudioUniversitario->fechaInicio)) : '' }}"
                            placeholder="Ej: 2020"
                            data-nivel="universitario"
                            {{ !isset($estudioUniversitario) ? 'disabled' : '' }}>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-calendar-check text-indigo-600 text-xs"></i>
                            Año fin
                        </label>
                        <input type="text" name="universitario_fin" class="form-input mt-1 w-full estudio-input" 
                            value="{{ $estudioUniversitario ? date('Y', strtotime($estudioUniversitario->fechaFin)) : '' }}"
                            placeholder="Ej: 2024"
                            data-nivel="universitario"
                            {{ !isset($estudioUniversitario) ? 'disabled' : '' }}>
                    </div>
                </div>
            </div>

            <!-- POST GRADO / MAESTRÍA -->
            <div class="mb-2">
                <div class="flex items-center mb-3">
                    <div class="w-5 h-5 bg-purple-100 rounded-full flex items-center justify-center mr-2">
                        <i class="fas fa-user-graduate text-purple-600 text-xs"></i>
                    </div>
                    <span class="font-semibold text-gray-800 dark:text-gray-200">Post Grado / Maestría</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-check-circle text-purple-600 text-xs"></i>
                            ¿Terminó?
                        </label>
                        <div class="flex space-x-4 mt-1">
                            <div class="flex items-center">
                                <input type="radio" name="postgrado_termino" value="1" class="w-4 h-4 text-purple-600"
                                    {{ isset($estudioPostgrado) && $estudioPostgrado->termino == 1 ? 'checked' : '' }}>
                                <label class="ml-1 text-sm flex items-center gap-1">
                                    <i class="fas fa-check text-green-600 text-xs"></i> SI
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" name="postgrado_termino" value="0" class="w-4 h-4 text-purple-600"
                                    {{ !isset($estudioPostgrado) || $estudioPostgrado->termino == 0 ? 'checked' : '' }}>
                                <label class="ml-1 text-sm flex items-center gap-1">
                                    <i class="fas fa-times text-red-600 text-xs"></i> NO
                                </label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-building-columns text-purple-600 text-xs"></i>
                            Universidad
                        </label>
                        <input type="text" name="postgrado_centro" class="form-input mt-1 w-full estudio-input" 
                            value="{{ $estudioPostgrado->centroEstudios ?? '' }}"
                            placeholder="Nombre de la universidad"
                            data-nivel="postgrado"
                            {{ !isset($estudioPostgrado) ? 'disabled' : '' }}>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-flask text-purple-600 text-xs"></i>
                            Especialidad
                        </label>
                        <input type="text" name="postgrado_especialidad" class="form-input mt-1 w-full estudio-input" 
                            value="{{ $estudioPostgrado->especialidad ?? '' }}"
                            placeholder="Ej: Maestría en Gestión"
                            data-nivel="postgrado"
                            {{ !isset($estudioPostgrado) ? 'disabled' : '' }}>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-award text-purple-600 text-xs"></i>
                            Grado
                        </label>
                        <input type="text" name="postgrado_grado" class="form-input mt-1 w-full estudio-input" 
                            value="{{ $estudioPostgrado->gradoAcademico ?? '' }}"
                            placeholder="Ej: Magíster"
                            data-nivel="postgrado"
                            {{ !isset($estudioPostgrado) ? 'disabled' : '' }}>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-calendar-plus text-purple-600 text-xs"></i>
                            Año inicio
                        </label>
                        <input type="text" name="postgrado_inicio" class="form-input mt-1 w-full estudio-input" 
                            value="{{ $estudioPostgrado ? date('Y', strtotime($estudioPostgrado->fechaInicio)) : '' }}"
                            placeholder="Ej: 2024"
                            data-nivel="postgrado"
                            {{ !isset($estudioPostgrado) ? 'disabled' : '' }}>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-calendar-check text-purple-600 text-xs"></i>
                            Año fin
                        </label>
                        <input type="text" name="postgrado_fin" class="form-input mt-1 w-full estudio-input" 
                            value="{{ $estudioPostgrado ? date('Y', strtotime($estudioPostgrado->fechaFin)) : '' }}"
                            placeholder="Ej: 2026"
                            data-nivel="postgrado"
                            {{ !isset($estudioPostgrado) ? 'disabled' : '' }}>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end mt-4 pt-3 border-t border-gray-200 dark:border-gray-700">
                <button type="button" id="guardar-estudios" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Guardar Información Académica
                </button>
            </div>
        </div>
    </div>
</template>





<script>



    $(document).ready(function() {
     // ============================================
// ACTUALIZAR INFORMACIÓN GENERAL
// ============================================
$('#update-button').click(function(e) {
    e.preventDefault();
    
    console.log('1. Botón clickeado');
    
    let formData = new FormData($('#update-forma')[0]);
    let userId = $('#update-forma').data('userid');
    let csrfToken = $('meta[name="csrf-token"]').attr('content');
    
    console.log('2. User ID:', userId);
    console.log('3. URL:', '/usuario/' + userId + '/informacion-general');
    console.log('4. CSRF Token:', csrfToken ? 'Existe' : 'No existe');
    
    // Mostrar los datos que se van a enviar
    console.log('5. Datos del formulario:');
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }

    // Validar correos
    let correo = $('#correo').val();
    let correoPersonal = $('#correo_personal').val();

    if (correo && !isValidEmail(correo)) {
        toastr.error('El correo corporativo no tiene un formato válido');
        return;
    }

    if (correoPersonal && !isValidEmail(correoPersonal)) {
        toastr.error('El correo personal no tiene un formato válido');
        return;
    }

    console.log('6. Validación de correos pasada');

    $.ajax({
        url: '/usuario/' + userId + '/informacion-general',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        beforeSend: function() {
            console.log('7. Enviando petición AJAX...');
        },
        success: function(response) {
            console.log('8. Respuesta exitosa:', response);
            if (response.success) {
                toastr.success(response.message);
                
                // Actualizar avatar si cambió
                if (response.data && response.data.avatar) {
                    $('#profile-img').attr('src', response.data.avatar);
                }
            }
        },
        error: function(xhr, status, error) {
            console.log('9. Error en AJAX:');
            console.log('Status:', status);
            console.log('Error:', error);
            console.log('Respuesta:', xhr.responseText);
            console.log('Status Code:', xhr.status);
            
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                $.each(errors, function(key, value) {
                    toastr.error(value[0]);
                });
            } else if (xhr.status === 404) {
                toastr.error('La ruta no existe. Verifica que la URL sea correcta');
            } else if (xhr.status === 500) {
                toastr.error('Error en el servidor. Revisa los logs de Laravel');
            } else {
                toastr.error('Hubo un error al actualizar los datos: ' + error);
            }
        }
    });
});

        // ============================================
        // ACTUALIZAR FECHA DE NACIMIENTO
        // ============================================
        $('#guardar-fecha-nacimiento').click(function() {
            let userId = {{ $usuario->idUsuario }};
            let csrfToken = $('meta[name="csrf-token"]').attr('content');
            
            let dia = $('#nacimiento_dia').val();
            let mes = $('#nacimiento_mes').val();
            let anio = $('#nacimiento_anio').val();
            
            if (!dia || !mes || !anio) {
                toastr.error('Debe completar todos los campos de fecha');
                return;
            }
            
            let data = {
                nacimiento_dia: dia,
                nacimiento_mes: mes,
                nacimiento_anio: anio
            };
            
            $.ajax({
                url: '/usuario/' + userId + '/fecha-nacimiento',
                type: 'POST',
                data: JSON.stringify(data),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        // Actualizar la edad mostrada
                        $('.edad-span').text(response.data.edad + ' años');
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error('Error al actualizar la fecha de nacimiento');
                    }
                }
            });
        });

        // ============================================
        // ACTUALIZAR LUGAR DE NACIMIENTO
        // ============================================
        $('#guardar-lugar-nacimiento').click(function() {
            let userId = {{ $usuario->idUsuario }};
            let csrfToken = $('meta[name="csrf-token"]').attr('content');
            
            let departamento = $('#nacimiento_departamento').val();
            let provincia = $('#nacimiento_provincia').val();
            let distrito = $('#nacimiento_distrito').val();
            
            if (!departamento || !provincia || !distrito) {
                toastr.error('Debe seleccionar departamento, provincia y distrito');
                return;
            }
            
            let data = {
                nacimiento_departamento: departamento,
                nacimiento_provincia: provincia,
                nacimiento_distrito: distrito
            };
            
            $.ajax({
                url: '/usuario/' + userId + '/lugar-nacimiento',
                type: 'POST',
                data: JSON.stringify(data),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error('Error al actualizar el lugar de nacimiento');
                    }
                }
            });
        });

        // ============================================
        // GUARDAR SEGURO Y PENSIÓN
        // ============================================
        $('#guardar-seguro-pension').click(function() {
            let userId = {{ $usuario->idUsuario }};
            let csrfToken = $('meta[name="csrf-token"]').attr('content');
            
            let data = {
                seguroSalud: $('input[name="seguroSalud"]:checked').val(),
                sistemaPensiones: $('input[name="sistemaPensiones"]:checked').val(),
                afpCompania: $('#afpCompania').val()
            };
            
            $.ajax({
                url: '/usuario/' + userId + '/seguro-pension',
                type: 'POST',
                data: JSON.stringify(data),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error('Error al guardar seguro y pensión');
                }
            });
        });

        // ============================================
        // GUARDAR INFORMACIÓN ACADÉMICA
        // ============================================
        $('#guardar-estudios').click(function() {
            let userId = {{ $usuario->idUsuario }};
            let csrfToken = $('meta[name="csrf-token"]').attr('content');
            
            let data = {
                // Secundaria
                secundaria_termino: $('input[name="secundaria_termino"]:checked').val(),
                secundaria_centro: $('input[name="secundaria_centro"]').val(),
                secundaria_fin: $('input[name="secundaria_fin"]').val(),
                
                // Técnico
                tecnico_termino: $('input[name="tecnico_termino"]:checked').val(),
                tecnico_centro: $('input[name="tecnico_centro"]').val(),
                tecnico_especialidad: $('input[name="tecnico_especialidad"]').val(),
                tecnico_inicio: $('input[name="tecnico_inicio"]').val(),
                tecnico_fin: $('input[name="tecnico_fin"]').val(),
                
                // Universitario
                universitario_termino: $('input[name="universitario_termino"]:checked').val(),
                universitario_centro: $('input[name="universitario_centro"]').val(),
                universitario_especialidad: $('input[name="universitario_especialidad"]').val(),
                universitario_grado: $('input[name="universitario_grado"]').val(),
                universitario_inicio: $('input[name="universitario_inicio"]').val(),
                universitario_fin: $('input[name="universitario_fin"]').val(),
                
                // Postgrado
                postgrado_termino: $('input[name="postgrado_termino"]:checked').val(),
                postgrado_centro: $('input[name="postgrado_centro"]').val(),
                postgrado_especialidad: $('input[name="postgrado_especialidad"]').val(),
                postgrado_grado: $('input[name="postgrado_grado"]').val(),
                postgrado_inicio: $('input[name="postgrado_inicio"]').val(),
                postgrado_fin: $('input[name="postgrado_fin"]').val()
            };
            
            $.ajax({
                url: '/usuario/' + userId + '/estudios',
                type: 'POST',
                data: JSON.stringify(data),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error('Error al guardar la información académica');
                }
            });
        });

        function isValidEmail(email) {
            var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        // ============================================
        // PREVISUALIZAR IMAGEN
        // ============================================
        window.previewImage = function(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('profile-img');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        };

        // ============================================
        // HABILITAR/DESHABILITAR CAMPOS DE ESTUDIOS
        // ============================================
        function toggleEstudioFields(nivel) {
            $(`input[name="${nivel}_termino"]`).on('change', function() {
                let esSi = $(this).val() == '1';
                if (esSi) {
                    $(`input[name="${nivel}_centro"], input[name="${nivel}_especialidad"], 
                       input[name="${nivel}_grado"], input[name="${nivel}_inicio"], 
                       input[name="${nivel}_fin"]`).prop('disabled', false);
                } else {
                    $(`input[name="${nivel}_centro"], input[name="${nivel}_especialidad"], 
                       input[name="${nivel}_grado"], input[name="${nivel}_inicio"], 
                       input[name="${nivel}_fin"]`).val('').prop('disabled', true);
                }
            });
        }

        // Aplicar a todos los niveles
        toggleEstudioFields('secundaria');
        toggleEstudioFields('tecnico');
        toggleEstudioFields('universitario');
        toggleEstudioFields('postgrado');

        // ============================================
        // CALCULAR EDAD AUTOMÁTICAMENTE
        // ============================================
        function calcularEdad() {
            let dia = $('#nacimiento_dia').val();
            let mes = $('#nacimiento_mes').val();
            let anio = $('#nacimiento_anio').val();
            
            if (dia && mes && anio) {
                let fechaNacimiento = new Date(anio, mes - 1, dia);
                let hoy = new Date();
                let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
                let mesActual = hoy.getMonth();
                let diaActual = hoy.getDate();
                
                if (mesActual < (mes - 1) || (mesActual === (mes - 1) && diaActual < dia)) {
                    edad--;
                }
                
                if (edad >= 0 && edad < 150) {
                    $('.edad-span').text(edad + ' años');
                } else {
                    $('.edad-span').text('-- años');
                }
            } else {
                $('.edad-span').text('-- años');
            }
        }

        $('#nacimiento_dia, #nacimiento_mes, #nacimiento_anio').on('change keyup', calcularEdad);

        // ============================================
        // UBIGEO PARA DIRECCIÓN ACTUAL
        // ============================================
        function cargarProvincias(departamentoId) {
            if (!departamentoId) {
                $('#provincia').empty().prop('disabled', true).append('<option value="" disabled selected>Seleccionar Provincia</option>');
                $('#distrito').empty().prop('disabled', true).append('<option value="" disabled selected>Seleccionar Distrito</option>');
                return;
            }

            $.ajax({
                url: '/ubigeo/provincias/' + departamentoId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var provinciaSelect = $('#provincia');
                    provinciaSelect.empty().prop('disabled', false);
                    provinciaSelect.append('<option value="" disabled selected>Seleccionar Provincia</option>');

                    if (data && data.length > 0) {
                        $.each(data, function(index, provincia) {
                            provinciaSelect.append('<option value="' + provincia.id_ubigeo + '">' +
                                provincia.nombre_ubigeo + '</option>');
                        });

                        var provinciaSeleccionada = '{{ old('provincia', $usuario->provincia) }}';
                        if (provinciaSeleccionada) {
                            provinciaSelect.val(provinciaSeleccionada).trigger('change');
                        }
                    }
                },
                error: function() {
                    toastr.error('Error al cargar provincias');
                }
            });
        }

        function cargarDistritos(provinciaId) {
            if (!provinciaId) {
                $('#distrito').empty().prop('disabled', true).append('<option value="" disabled selected>Seleccionar Distrito</option>');
                return;
            }

            $.ajax({
                url: '/ubigeo/distritos/' + provinciaId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var distritoSelect = $('#distrito');
                    distritoSelect.empty().prop('disabled', false);
                    distritoSelect.append('<option value="" disabled selected>Seleccionar Distrito</option>');

                    if (data && data.length > 0) {
                        $.each(data, function(index, distrito) {
                            distritoSelect.append('<option value="' + distrito.id_ubigeo + '">' +
                                distrito.nombre_ubigeo + '</option>');
                        });

                        var distritoSeleccionado = '{{ old('distrito', $usuario->distrito) }}';
                        if (distritoSeleccionado) {
                            distritoSelect.val(distritoSeleccionado);
                        }
                    }
                },
                error: function() {
                    toastr.error('Error al cargar distritos');
                }
            });
        }

        // Cargar datos iniciales si existen
        var departamentoInicial = $('#departamento').val();
        var provinciaInicial = '{{ $usuario->provincia }}';
        var distritoInicial = '{{ $usuario->distrito }}';

        if (departamentoInicial) {
            cargarProvincias(departamentoInicial);
        }

        $('#departamento').on('change', function() {
            var departamentoId = $(this).val();
            cargarProvincias(departamentoId);
            $('#distrito').empty().prop('disabled', true).append('<option value="" disabled selected>Seleccionar Distrito</option>');
        });

        $('#provincia').on('change', function() {
            var provinciaId = $(this).val();
            cargarDistritos(provinciaId);
        });

        if (provinciaInicial) {
            cargarDistritos(provinciaInicial);
        }

        // ============================================
        // ACTUALIZAR DIRECCIÓN
        // ============================================
        $('#direccion-form').on('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(this);
            const formDataObj = {};
            formData.forEach((value, key) => {
                formDataObj[key] = value;
            });

            const userId = {{ $usuario->idUsuario }};
            const url = `/usuario/direccion/${userId}`;
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: url,
                method: 'PUT',
                data: JSON.stringify(formDataObj),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(data) {
                    if (data.success) {
                        toastr.success('Dirección actualizada correctamente');
                    } else {
                        toastr.error('Error al actualizar la dirección');
                    }
                },
                error: function() {
                    toastr.error('Error al intentar actualizar');
                }
            });
        });

        // ============================================
        // UBIGEO PARA LUGAR DE NACIMIENTO
        // ============================================
        var provinciasNacimiento = @json($provinciasNacimiento ?? []);
        var distritosNacimiento = @json($distritosNacimiento ?? []);

        function cargarProvinciasNacimiento(departamentoId) {
            if (!departamentoId) {
                $('#nacimiento_provincia').empty().prop('disabled', true).append('<option value="">Seleccionar Provincia</option>');
                $('#nacimiento_distrito').empty().prop('disabled', true).append('<option value="">Seleccionar Distrito</option>');
                return;
            }

            // Si ya tenemos los datos precargados del servidor
            if (provinciasNacimiento && provinciasNacimiento.length > 0) {
                var provinciaSelect = $('#nacimiento_provincia');
                provinciaSelect.empty().prop('disabled', false);
                provinciaSelect.append('<option value="">Seleccionar Provincia</option>');

                $.each(provinciasNacimiento, function(index, provincia) {
                    provinciaSelect.append('<option value="' + provincia.id_ubigeo + '">' +
                        provincia.nombre_ubigeo + '</option>');
                });

                var provinciaSeleccionada = '{{ $fichaGeneral->nacimientoProvincia ?? '' }}';
                if (provinciaSeleccionada) {
                    provinciaSelect.val(provinciaSeleccionada).trigger('change');
                }
                return;
            }

            // Si no hay datos precargados, hacer petición AJAX
            $.ajax({
                url: '/ubigeo/provincias/' + departamentoId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var provinciaSelect = $('#nacimiento_provincia');
                    provinciaSelect.empty().prop('disabled', false);
                    provinciaSelect.append('<option value="">Seleccionar Provincia</option>');

                    if (data && data.length > 0) {
                        $.each(data, function(index, provincia) {
                            provinciaSelect.append('<option value="' + provincia.id_ubigeo + '">' +
                                provincia.nombre_ubigeo + '</option>');
                        });

                        var provinciaSeleccionada = '{{ $fichaGeneral->nacimientoProvincia ?? '' }}';
                        if (provinciaSeleccionada) {
                            provinciaSelect.val(provinciaSeleccionada).trigger('change');
                        }
                    }
                },
                error: function() {
                    toastr.error('Error al cargar provincias');
                }
            });
        }

        function cargarDistritosNacimiento(provinciaId) {
            if (!provinciaId) {
                $('#nacimiento_distrito').empty().prop('disabled', true).append('<option value="">Seleccionar Distrito</option>');
                return;
            }

            // Si ya tenemos los datos precargados del servidor
            if (distritosNacimiento && distritosNacimiento.length > 0) {
                var distritoSelect = $('#nacimiento_distrito');
                distritoSelect.empty().prop('disabled', false);
                distritoSelect.append('<option value="">Seleccionar Distrito</option>');

                $.each(distritosNacimiento, function(index, distrito) {
                    distritoSelect.append('<option value="' + distrito.id_ubigeo + '">' +
                        distrito.nombre_ubigeo + '</option>');
                });

                var distritoSeleccionado = '{{ $fichaGeneral->nacimientoDistrito ?? '' }}';
                if (distritoSeleccionado) {
                    distritoSelect.val(distritoSeleccionado);
                }
                return;
            }

            // Si no hay datos precargados, hacer petición AJAX
            $.ajax({
                url: '/ubigeo/distritos/' + provinciaId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var distritoSelect = $('#nacimiento_distrito');
                    distritoSelect.empty().prop('disabled', false);
                    distritoSelect.append('<option value="">Seleccionar Distrito</option>');

                    if (data && data.length > 0) {
                        $.each(data, function(index, distrito) {
                            distritoSelect.append('<option value="' + distrito.id_ubigeo + '">' +
                                distrito.nombre_ubigeo + '</option>');
                        });

                        var distritoSeleccionado = '{{ $fichaGeneral->nacimientoDistrito ?? '' }}';
                        if (distritoSeleccionado) {
                            distritoSelect.val(distritoSeleccionado);
                        }
                    }
                },
                error: function() {
                    toastr.error('Error al cargar distritos');
                }
            });
        }

        // Event listeners para lugar de nacimiento
        $('#nacimiento_departamento').on('change', function() {
            var departamentoId = $(this).val();
            cargarProvinciasNacimiento(departamentoId);
            $('#nacimiento_distrito').empty().prop('disabled', true).append('<option value="">Seleccionar Distrito</option>');
        });

        $('#nacimiento_provincia').on('change', function() {
            var provinciaId = $(this).val();
            cargarDistritosNacimiento(provinciaId);
        });

        // Cargar datos iniciales si existen
        var deptoNacimiento = '{{ $fichaGeneral->nacimientoDepartamento ?? '' }}';
        var provinciaNacimiento = '{{ $fichaGeneral->nacimientoProvincia ?? '' }}';

        if (deptoNacimiento) {
            $('#nacimiento_departamento').val(deptoNacimiento).trigger('change');
            
            if (provinciaNacimiento) {
                setTimeout(function() {
                    $('#nacimiento_provincia').val(provinciaNacimiento).trigger('change');
                }, 500);
            }
        }

        // ============================================
        // CONTROL DE AFP SEGÚN PENSIÓN
        // ============================================
        $('input[name="sistemaPensiones"]').on('change', function() {
            if ($(this).val() === 'AFP') {
                $('#afpCompania').prop('disabled', false);
            } else {
                $('#afpCompania').prop('disabled', true).val('');
            }
        });
    });
</script>