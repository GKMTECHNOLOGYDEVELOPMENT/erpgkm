<template x-if="tab === 'perfil'">
    <div>
        <!-- ============================================ -->
        <!-- SECCIÓN 1: INFORMACIÓN GENERAL (ORIGINAL + SEXO) -->
        <!-- ============================================ -->
        <form data-userid="{{ $usuario->idUsuario }}" method="POST" enctype="multipart/form-data" id="update-forma"
            class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-4 mb-5 bg-white dark:bg-[#0e1726]">

            <div class="flex items-center mb-5">
                <div class="w-1 h-6 bg-primary rounded-full mr-3"></div>
                <h6 class="text-lg font-bold text-gray-800 dark:text-white">INFORMACIÓN GENERAL </h6>
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

                    <!-- SEXO AGREGADO AQUÍ -->
                    <div>
                        <label for="sexo_static" class="flex items-center gap-1 text-sm font-medium">
                            <i class="fas fa-venus-mars text-gray-500 text-xs"></i>
                            Sexo
                        </label>
                        <select id="sexo_static" name="sexo_static" class="form-input w-full">
                            <option>Seleccione</option>
                            <option>Masculino</option>
                            <option>Femenino</option>
                            <option>Otro</option>
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
                            <option value="3" {{ $usuario->estadocivil == 3 ? 'selected' : '' }}>Divorciado
                            </option>
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
                            <i class="fas fa-save mr-1"></i> Actualizar
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- ============================================ -->
        <!-- SECCIÓN 2: FECHA Y LUGAR DE NACIMIENTO (MEJORADA) -->
        <!-- ============================================ -->
        <div class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-4 mb-5 bg-white dark:bg-[#0e1726]">
            <div class="flex items-center mb-5">
                <div class="w-1 h-6 bg-blue-500 rounded-full mr-3"></div>
                <h6 class="text-lg font-bold text-gray-800 dark:text-white">FECHA Y LUGAR DE NACIMIENTO</h6>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Fecha de nacimiento - Card style -->
                <div
                    class="bg-gradient-to-br from-blue-50/50 to-transparent dark:from-[#1e293b] p-5 rounded-lg border border-blue-100/50 dark:border-blue-900/30">
                    <div class="flex items-center gap-2 mb-3">
                        <i class="fas fa-calendar-alt text-blue-600 dark:text-blue-400 text-lg"></i>
                        <label class="font-semibold text-gray-700 dark:text-gray-300 leading-none required">
                            Fecha de Nacimiento
                            <span class="ml-1 text-xs text-gray-400 font-normal align-middle">
                                (obligatorio)
                            </span>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <input type="number" min="1" max="31" placeholder="Día"
                                class="form-input w-full text-center placeholder:text-gray-400" />
                        </div>
                        <div>
                            <select class="form-input w-full">
                                <option>Mes</option>
                                <option>Enero</option>
                                <option>Febrero</option>
                                <option>Marzo</option>
                                <option>Abril</option>
                                <option>Mayo</option>
                                <option>Junio</option>
                                <option>Julio</option>
                                <option>Agosto</option>
                                <option>Septiembre</option>
                                <option>Octubre</option>
                                <option>Noviembre</option>
                                <option>Diciembre</option>
                            </select>
                        </div>
                        <div>
                            <input type="number" min="1900" max="2026" placeholder="Año"
                                class="form-input w-full text-center placeholder:text-gray-400" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-3 text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Edad:</span>
                        <span
                            class="ml-2 font-semibold text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900/30 px-3 py-1 rounded-full">--
                            años</span>
                    </div>
                </div>

                <!-- Lugar de nacimiento - Card style -->
                <div
                    class="bg-gradient-to-br from-indigo-50/50 to-transparent dark:from-[#1e293b] p-5 rounded-lg border border-indigo-100/50 dark:border-indigo-900/30">
                    <div class="flex items-center gap-2 mb-3">
                        <i class="fas fa-map-marker-alt text-indigo-600 dark:text-indigo-400 text-lg"></i>
                        <label class="font-semibold text-gray-700 dark:text-gray-300 leading-none">
                            Lugar de Nacimiento
                        </label>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <select class="form-input w-full">
                                <option>Departamento</option>
                                <option>Amazonas</option>
                                <option>Áncash</option>
                                <option>Apurímac</option>
                                <option>Arequipa</option>
                                <option>Ayacucho</option>
                                <option>Cajamarca</option>
                                <option>Callao</option>
                                <option>Cusco</option>
                                <option>Huancavelica</option>
                                <option>Huánuco</option>
                                <option>Ica</option>
                                <option>Junín</option>
                                <option>La Libertad</option>
                                <option>Lambayeque</option>
                                <option>Lima</option>
                                <option>Loreto</option>
                                <option>Madre de Dios</option>
                                <option>Moquegua</option>
                                <option>Pasco</option>
                                <option>Piura</option>
                                <option>Puno</option>
                                <option>San Martín</option>
                                <option>Tacna</option>
                                <option>Tumbes</option>
                                <option>Ucayali</option>
                            </select>
                        </div>
                        <div>
                            <select class="form-input w-full bg-gray-50 dark:bg-[#1e293b]" disabled>
                                <option>Provincia</option>
                            </select>
                        </div>
                        <div>
                            <select class="form-input w-full bg-gray-50 dark:bg-[#1e293b]" disabled>
                                <option>Distrito</option>
                            </select>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2 flex items-center">
                        <i class="fas fa-info-circle mr-1"></i>
                        Seleccione un departamento para habilitar provincia y distrito
                    </p>
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- SECCIÓN 3: SEGURO Y PENSIÓN (MEJORADA) -->
        <!-- ============================================ -->
        <div class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-4 mb-5 bg-white dark:bg-[#0e1726]">
            <div class="flex items-center mb-5">
                <div class="w-1 h-6 bg-green-500 rounded-full mr-3"></div>
                <h6 class="text-lg font-bold text-gray-800 dark:text-white">SEGURO Y PENSIÓN</h6>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Seguro de salud - Card mejorado -->
                <div
                    class="bg-gradient-to-br from-green-50/50 to-transparent dark:from-[#1e293b] p-5 rounded-lg border border-green-100/50 dark:border-green-900/30">
                    <div class="flex items-center gap-2 mb-4">
                        <i class="fas fa-shield-alt text-green-600 dark:text-green-400 text-lg"></i>
                        <label class="font-semibold text-gray-700 dark:text-gray-300 leading-none">
                            Tipo de Seguro de Salud
                        </label>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div
                            class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-green-300 dark:hover:border-green-700 transition-colors">
                            <input type="radio" id="sis_static" name="seguro_salud_static"
                                class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 focus:ring-green-500">
                            <label for="sis_static" class="ml-2 text-sm font-medium cursor-pointer flex-1">SIS</label>
                        </div>
                        <div
                            class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-green-300 dark:hover:border-green-700 transition-colors">
                            <input type="radio" id="essalud_static" name="seguro_salud_static"
                                class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 focus:ring-green-500">
                            <label for="essalud_static"
                                class="ml-2 text-sm font-medium cursor-pointer flex-1">ESSALUD</label>
                        </div>
                        <div
                            class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-green-300 dark:hover:border-green-700 transition-colors">
                            <input type="radio" id="eps_static" name="seguro_salud_static"
                                class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 focus:ring-green-500">
                            <label for="eps_static" class="ml-2 text-sm font-medium cursor-pointer flex-1">EPS</label>
                        </div>
                    </div>
                </div>

                <!-- Sistema de pensiones - Card mejorado -->
                <div
                    class="bg-gradient-to-br from-purple-50/50 to-transparent dark:from-[#1e293b] p-5 rounded-lg border border-purple-100/50 dark:border-purple-900/30">
                    <div class="flex items-center gap-2 mb-4">
                        <i class="fas fa-piggy-bank text-purple-600 dark:text-purple-400 text-lg"></i>
                        <label class="font-semibold text-gray-700 dark:text-gray-300 leading-none">
                            Sistema de Pensiones
                        </label>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">
                        <div
                            class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-purple-300 dark:hover:border-purple-700 transition-colors">
                            <input type="radio" id="onp_static" name="pension_static"
                                class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 focus:ring-purple-500">
                            <label for="onp_static" class="ml-2 text-sm font-medium cursor-pointer">ONP</label>
                        </div>
                        <div
                            class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-purple-300 dark:hover:border-purple-700 transition-colors">
                            <input type="radio" id="afp_static" name="pension_static"
                                class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 focus:ring-purple-500">
                            <label for="afp_static" class="ml-2 text-sm font-medium cursor-pointer">AFP</label>
                        </div>
                        <div
                            class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-purple-300 dark:hover:border-purple-700 transition-colors">
                            <input type="radio" id="noaplica_static" name="pension_static"
                                class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 focus:ring-purple-500">
                            <label for="noaplica_static" class="ml-2 text-sm font-medium cursor-pointer">No
                                Aplica</label>
                        </div>
                    </div>

                    <!-- AFP compañía - más integrado -->
                    <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center">
                            <i class="fas fa-building text-gray-400 mr-2 text-xs"></i>
                            <label for="afp_compania_static"
                                class="block text-sm font-medium text-gray-600 dark:text-gray-400">Compañía AFP</label>
                        </div>
                        <select id="afp_compania_static" class="form-input w-full mt-2">
                            <option>Seleccione AFP</option>
                            <option>Integra</option>
                            <option>Horizonte</option>
                            <option>Profuturo</option>
                            <option>Prima</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- SECCIÓN 4: DIRECCIÓN (ORIGINAL MEJORADA) -->
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
                            <option value="" disabled selected>Seleccionar Departamento</option>
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
        <!-- SECCIÓN 5: INFORMACIÓN ACADÉMICA (ESTÁTICO) -->
        <!-- ============================================ -->
        <div class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-4 mb-5 bg-white dark:bg-[#0e1726]">
            <div class="flex items-center mb-4">
                <div class="w-1 h-6 bg-secondary rounded-full mr-3"></div>
                <h6 class="text-lg font-bold text-gray-800 dark:text-white">INFORMACIÓN ACADEMICA</h6>
            </div>

            <!-- Secundaria -->
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
                                <input type="radio" name="secundaria_termino" class="w-4 h-4 text-blue-600">
                                <label class="ml-1 text-sm flex items-center gap-1">
                                    <i class="fas fa-check text-green-600 text-xs"></i> SI
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" name="secundaria_termino" class="w-4 h-4 text-blue-600">
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
                        <input type="text" class="form-input mt-1 w-full" placeholder="Nombre del colegio">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-calendar text-blue-600 text-xs"></i>
                            Año
                        </label>
                        <input type="text" class="form-input mt-1 w-full" placeholder="Ej: 2018">
                    </div>
                </div>
            </div>

            <!-- Técnico / Instituto -->
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
                                <input type="radio" name="tecnico_termino" class="w-4 h-4 text-green-600">
                                <label class="ml-1 text-sm flex items-center gap-1">
                                    <i class="fas fa-check text-green-600 text-xs"></i> SI
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" name="tecnico_termino" class="w-4 h-4 text-green-600">
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
                        <input type="text" class="form-input mt-1 w-full" placeholder="Nombre del instituto">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-book text-green-600 text-xs"></i>
                            Carrera
                        </label>
                        <input type="text" class="form-input mt-1 w-full" placeholder="Ej: Administración">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-calendar-plus text-green-600 text-xs"></i>
                            Año inicio
                        </label>
                        <input type="text" class="form-input mt-1 w-full" placeholder="Ej: 2019">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-calendar-check text-green-600 text-xs"></i>
                            Año fin
                        </label>
                        <input type="text" class="form-input mt-1 w-full" placeholder="Ej: 2022">
                    </div>
                </div>
            </div>

            <!-- Universitario -->
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
                                <input type="radio" name="universitario_termino" class="w-4 h-4 text-indigo-600">
                                <label class="ml-1 text-sm flex items-center gap-1">
                                    <i class="fas fa-check text-green-600 text-xs"></i> SI
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" name="universitario_termino" class="w-4 h-4 text-indigo-600">
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
                        <input type="text" class="form-input mt-1 w-full" placeholder="Nombre de la universidad">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-graduation-cap text-indigo-600 text-xs"></i>
                            Carrera
                        </label>
                        <input type="text" class="form-input mt-1 w-full" placeholder="Ej: Ingeniería">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-award text-indigo-600 text-xs"></i>
                            Grado
                        </label>
                        <input type="text" class="form-input mt-1 w-full" placeholder="Ej: Bachiller">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-calendar-plus text-indigo-600 text-xs"></i>
                            Año inicio
                        </label>
                        <input type="text" class="form-input mt-1 w-full" placeholder="Ej: 2020">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-calendar-check text-indigo-600 text-xs"></i>
                            Año fin
                        </label>
                        <input type="text" class="form-input mt-1 w-full" placeholder="Ej: 2024">
                    </div>
                </div>
            </div>

            <!-- Post Grado -->
            <div class="mb-2">
                <div class="flex items-center mb-3">
                    <div class="w-5 h-5 bg-purple-100 rounded-full flex items-center justify-center mr-2">
                        <i class="fas fa-user-graduate text-purple-600 text-xs"></i>
                    </div>
                    <span class="font-semibold text-gray-800 dark:text-gray-200">Post Grado</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-check-circle text-purple-600 text-xs"></i>
                            ¿Terminó?
                        </label>
                        <div class="flex space-x-4 mt-1">
                            <div class="flex items-center">
                                <input type="radio" name="postgrado_termino" class="w-4 h-4 text-purple-600">
                                <label class="ml-1 text-sm flex items-center gap-1">
                                    <i class="fas fa-check text-green-600 text-xs"></i> SI
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" name="postgrado_termino" class="w-4 h-4 text-purple-600">
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
                        <input type="text" class="form-input mt-1 w-full" placeholder="Nombre de la universidad">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-flask text-purple-600 text-xs"></i>
                            Especialidad
                        </label>
                        <input type="text" class="form-input mt-1 w-full" placeholder="Ej: Maestría">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-award text-purple-600 text-xs"></i>
                            Grado
                        </label>
                        <input type="text" class="form-input mt-1 w-full" placeholder="Ej: Magíster">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-calendar-plus text-purple-600 text-xs"></i>
                            Año inicio
                        </label>
                        <input type="text" class="form-input mt-1 w-full" placeholder="Ej: 2024">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-calendar-check text-purple-600 text-xs"></i>
                            Año fin
                        </label>
                        <input type="text" class="form-input mt-1 w-full" placeholder="Ej: 2026">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    $(document).ready(function() {
        $('#update-button').click(function(e) {
            e.preventDefault();

            let formData = new FormData($('#update-forma')[0]);
            let userId = $('#update-forma').data('userid');
            let csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Validar correos
            let correo = $('#correo').val();
            let correoPersonal = $('#correo_personal').val();

            // Validar formato de correos
            if (correo && !isValidEmail(correo)) {
                toastr.error('El correo corporativo no tiene un formato válido');
                return;
            }

            if (correoPersonal && !isValidEmail(correoPersonal)) {
                toastr.error('El correo personal no tiene un formato válido');
                return;
            }

            $.ajax({
                url: '/usuarios/' + userId + '/update',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    toastr.success(response.success);

                    // Actualizar visualmente el correo en la interfaz si es necesario
                    if (correoPersonal) {
                        // Puedes agregar aquí lógica para mostrar el correo personal actualizado
                    }
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 422) {
                        // Mostrar errores de validación
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error('Hubo un error al actualizar los datos');
                    }
                }
            });
        });

        function isValidEmail(email) {
            var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        // Función para previsualizar imagen
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('profile-img');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
        window.previewImage = previewImage;
    });
</script>
<script>
    $(document).ready(function() {
        // Cargar provincias y distritos al cargar el formulario si ya hay un departamento seleccionado
        function cargarProvincias(departamentoId) {
            $.get('/ubigeo/provincias/' + departamentoId, function(data) {
                var provinciaSelect = $('#provincia');
                provinciaSelect.empty().prop('disabled', false);
                provinciaSelect.append(
                    '<option value="" disabled selected>Seleccionar Provincia</option>');

                data.forEach(function(provincia) {
                    provinciaSelect.append('<option value="' + provincia.id_ubigeo + '">' +
                        provincia.nombre_ubigeo + '</option>');
                });

                // Si hay provincia seleccionada previamente, se selecciona automáticamente
                var provinciaSeleccionada = '{{ old('provincia', $usuario->provincia) }}';
                if (provinciaSeleccionada) {
                    $('#provincia').val(provinciaSeleccionada).change();
                }
            });
        }

        function cargarDistritos(provinciaId) {
            $.get('/ubigeo/distritos/' + provinciaId, function(data) {
                var distritoSelect = $('#distrito');
                distritoSelect.empty().prop('disabled', false);
                distritoSelect.append(
                    '<option value="" disabled selected>Seleccionar Distrito</option>');

                data.forEach(function(distrito) {
                    distritoSelect.append('<option value="' + distrito.id_ubigeo + '">' +
                        distrito.nombre_ubigeo + '</option>');
                });

                // Si hay distrito seleccionado previamente, se selecciona automáticamente
                var distritoSeleccionado = '{{ old('distrito', $usuario->distrito) }}';
                if (distritoSeleccionado) {
                    $('#distrito').val(distritoSeleccionado);
                }
            });
        }

        // Si ya hay un departamento seleccionado al cargar la página
        var departamentoId = $('#departamento').val();
        if (departamentoId) {
            cargarProvincias(departamentoId);
        }

        // Cargar distritos si ya hay una provincia seleccionada al cargar la página
        var provinciaId = $('#provincia').val();
        if (provinciaId) {
            cargarDistritos(provinciaId);
        }

        // Cuando se selecciona un nuevo departamento
        $('#departamento').change(function() {
            var departamentoId = $(this).val();
            if (departamentoId) {
                // Limpiar los selects de provincia y distrito
                $('#provincia').empty().prop('disabled', true);
                $('#distrito').empty().prop('disabled', true);

                cargarProvincias(departamentoId);
            }
        });

        // Cuando se selecciona una provincia
        $('#provincia').on('change', function() {
            var provinciaId = $(this).val();
            if (provinciaId) {
                // Limpiar el select de distritos
                $('#distrito').empty().prop('disabled', true);

                cargarDistritos(provinciaId);
            }
        });
    });
</script>

<!-- <script src="{{ asset('assets/js/ubigeo.js') }}"></script> -->
<script>
    document.getElementById('direccion-form').addEventListener('submit', function(event) {
        event.preventDefault(); // Evita el envío normal del formulario

        // Recolecta los datos del formulario
        const formData = new FormData(this);
        const formDataObj = {};
        formData.forEach((value, key) => {
            formDataObj[key] = value;
        });

        // ID del usuario (será inyectado en tu Blade)
        const userId = {{ $usuario->idUsuario }};

        // URL para actualizar la dirección
        const url = `/usuario/direccion/${userId}`;

        // Obtener el token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Realiza la solicitud `fetch` con el método PUT y los datos JSON
        fetch(url, {
                method: 'PUT', // Usamos el método PUT para actualización
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(formDataObj) // Convierte los datos del formulario a JSON
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Si la respuesta es exitosa, puedes mostrar un mensaje de éxito
                    toastr.success('Dirección actualizada correctamente');
                } else {
                    // Si ocurre un error, mostrar los mensajes de error
                    toastr.error('Error al actualizar la dirección');
                }
            })
            .catch(error => {
                console.error('Error al enviar la solicitud:', error);
                toastr.error('Error al intentar actualizar');
            });
    });
</script>
