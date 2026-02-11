<!-- Sección 1: Información General -->
<div class="form-section bg-white rounded-2xl shadow-lg p-6 md:p-8 mt-6 border border-gray-200">
    <!-- Encabezado de sección -->
    <div class="section-header mb-8 pb-6 border-b border-gray-200">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center">
                <div
                    class="bg-gradient-to-r from-blue-500 to-blue-600 w-12 h-12 rounded-xl flex items-center justify-center mr-4 shadow-md">
                    <i class="fas fa-user-circle text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl md:text-2xl font-bold text-gray-800">Información General</h3>
                    <p class="text-gray-500 mt-1 text-sm md:text-base">Complete sus datos personales básicos</p>
                </div>
            </div>
            <span class="bg-blue-50 text-blue-700 px-4 py-2 rounded-full text-sm font-semibold border border-blue-100">
                Sección 1
            </span>
        </div>
    </div>

    <!-- Nombres -->
    <div class="mb-10">
        <h4 class="text-lg font-semibold text-gray-700 mb-6 flex items-center">
            <i class="fas fa-signature text-blue-500 mr-2 text-sm"></i>
            Datos Personales
        </h4>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
                <label for="paterno" class="block text-gray-700 font-medium mb-2 required flex items-center">
                    Apellido Paterno
                    <span class="ml-1 text-xs text-gray-400">(obligatorio)</span>
                </label>
                <div class="relative">
                    <input type="text" id="paterno" name="paterno"
                        class="campo-general w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 bg-gray-50"
                        placeholder="Ingrese apellido" required>
                    <i class="fas fa-user text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                </div>
            </div>
            <div>
                <label for="materno" class="block text-gray-700 font-medium mb-2">Apellido Materno</label>
                <div class="relative">
                    <input type="text" id="materno" name="materno"
                        class="campo-general w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 bg-gray-50"
                        placeholder="Ingrese apellido">
                    <i class="fas fa-user text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                </div>
            </div>
            <div class="sm:col-span-2 lg:col-span-1">
                <label for="nombres" class="block text-gray-700 font-medium mb-2 required flex items-center">
                    Nombres Completos
                    <span class="ml-1 text-xs text-gray-400">(obligatorio)</span>
                </label>
                <div class="relative">
                    <input type="text" id="nombres" name="nombres"
                        class="campo-general w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 bg-gray-50"
                        placeholder="Ingrese nombres completos" required>
                    <i class="fas fa-id-card text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Fecha y lugar de nacimiento -->
    <div class="mb-10">
        <h4 class="text-lg font-semibold text-gray-700 mb-6 flex items-center">
            <i class="fas fa-birthday-cake text-blue-500 mr-2 text-sm"></i>
            Fecha y Lugar de Nacimiento
        </h4>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Fecha de nacimiento -->
            <div class="bg-gradient-to-br from-blue-50 to-white p-6 rounded-2xl border border-blue-100">
                <label class="block text-gray-700 font-medium mb-4 required flex flex-wrap items-center">
                    Fecha de Nacimiento
                    <span class="ml-1 text-xs text-gray-400">(obligatorio)</span>
                </label>

                <!-- Mobile: vertical stack, Tablet: 2 columns, Desktop: 3 columns -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Día -->
                    <div class="w-full">
                        <label for="dia" class="block text-sm text-gray-600 mb-2 font-medium">Día</label>
                        <div class="relative">
                            <input type="number" id="dia" name="dia" min="1" max="31"
                                class="campo-general fecha-nacimiento w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                placeholder="DD">
                            <i
                                class="fas fa-calendar-day text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                        </div>
                    </div>

                    <!-- Mes -->
                    <div class="w-full">
                        <label for="mes" class="block text-sm text-gray-600 mb-2 font-medium">Mes</label>
                        <div class="relative">
                            <select id="mes" name="mes"
                                class="campo-general fecha-nacimiento w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white">
                                <option value="">Seleccione</option>
                                <option value="01">Enero</option>
                                <option value="02">Febrero</option>
                                <option value="03">Marzo</option>
                                <option value="04">Abril</option>
                                <option value="05">Mayo</option>
                                <option value="06">Junio</option>
                                <option value="07">Julio</option>
                                <option value="08">Agosto</option>
                                <option value="09">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                            <i
                                class="fas fa-calendar-alt text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                        </div>
                    </div>

                    <!-- Año -->
                    <div class="w-full sm:col-span-2 lg:col-span-1">
                        <label for="anio" class="block text-sm text-gray-600 mb-2 font-medium">Año</label>
                        <div class="relative">
                            <input type="number" id="anio" name="anio" min="1900" max="{{ date('Y') }}"
                                class="campo-general fecha-nacimiento w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                placeholder="AAAA">
                            <i
                                class="fas fa-calendar text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                        </div>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-3 text-center" id="edad-display">
                    Edad: <span id="edad-value">--</span> años
                </p>
            </div>

            <!-- Lugar de nacimiento (UBIGEO) -->
            <div class="bg-gradient-to-br from-blue-50 to-white p-6 rounded-2xl border border-blue-100">
                <label class="block text-gray-700 font-medium mb-4">Lugar de Nacimiento</label>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <label for="nacimientoDepartamento" class="block text-sm text-gray-600 mb-2 font-medium">Departamento</label>
                        <select id="nacimientoDepartamento" name="nacimientoDepartamento"
                            class="campo-general w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white">
                            <option value="" disabled selected>Seleccionar Departamento</option>
                            @foreach ($departamentos as $departamento)
                                <option value="{{ $departamento['id_ubigeo'] }}">
                                    {{ $departamento['nombre_ubigeo'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="nacimientoProvincia" class="block text-sm text-gray-600 mb-2 font-medium">Provincia</label>
                        <select id="nacimientoProvincia" name="nacimientoProvincia"
                            class="campo-general w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white"
                            disabled>
                            <option value="" disabled selected>Seleccionar Provincia</option>
                        </select>
                    </div>
                    <div>
                        <label for="nacimientoDistrito" class="block text-sm text-gray-600 mb-2 font-medium">Distrito</label>
                        <select id="nacimientoDistrito" name="nacimientoDistrito"
                            class="campo-general w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white"
                            disabled>
                            <option value="" disabled selected>Seleccionar Distrito</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Documento, edad, sexo, nacionalidad -->
    <div class="mb-10">
        <h4 class="text-lg font-semibold text-gray-700 mb-6 flex items-center">
            <i class="fas fa-id-badge text-blue-500 mr-2 text-sm"></i>
            Documentación
        </h4>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            <div>
                <label for="tipo_documento" class="block text-gray-700 font-medium mb-2 required">Tipo de
                    Documento</label>
                <div class="relative">
                    <select id="idTipoDocumento" name="idTipoDocumento"
                        class="campo-general w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white"
                        required>
                        <option value="" disabled selected>Seleccionar Tipo Documento</option>
                        @foreach ($tiposDocumento as $tipo)
                            <option value="{{ $tipo->idTipoDocumento }}">{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                    <i
                        class="fas fa-file-alt text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                </div>
            </div>
            <div>
                <label for="num_documento" class="block text-gray-700 font-medium mb-2 required">
                    Nº Documento
                    <span class="text-xs text-gray-500 font-normal" id="documento-hint"></span>
                </label>
                <div class="relative">
                    <input type="text" id="num_documento" name="num_documento"
                        class="campo-general w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-gray-50"
                        placeholder="Ej: 12345678" required>
                    <i class="fas fa-hashtag text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                    <div id="documento-error" class="text-red-500 text-sm mt-1" style="display: none;"></div>
                </div>
            </div>
            <div>
                <label for="edad" class="block text-gray-700 font-medium mb-2">Edad</label>
                <div class="relative">
                    <input type="number" id="edad" name="edad" min="18" max="100"
                        class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-gray-50"
                        placeholder="Años" readonly>
                    <i class="fas fa-user-clock text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                </div>
                <p class="text-xs text-gray-500 mt-1">Calculada automáticamente</p>
            </div>
            <div>
                <label for="idSexo" class="block text-gray-700 font-medium mb-2">Sexo</label>
                <div class="relative">
                    <select id="idSexo" name="idSexo"
                        class="campo-general w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white">
                        <option value="" disabled selected>Seleccione</option>
                        @foreach ($sexos as $sexo)
                            <option value="{{ $sexo->idSexo }}">{{ $sexo->nombre }}</option>
                        @endforeach
                    </select>
                    <i
                        class="fas fa-venus-mars text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                </div>
            </div>
            <div>
                <label for="nacionalidad" class="block text-gray-700 font-medium mb-2">Nacionalidad</label>
                <div class="relative">
                    <input type="text" id="nacionalidad" name="nacionalidad" value="Peruana"
                        class="campo-general w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-gray-50">
                    <i class="fas fa-flag text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Estado civil, email, teléfono -->
    <div class="mb-10">
        <h4 class="text-lg font-semibold text-gray-700 mb-6 flex items-center">
            <i class="fas fa-heart text-blue-500 mr-2 text-sm"></i>
            Información de Contacto
        </h4>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Estado civil -->
            <div class="lg:col-span-1">
                <label class="block text-gray-700 font-medium mb-4">Estado Civil</label>
                <div class="grid grid-cols-2 gap-3">
                    <div
                        class="flex items-center p-3 border border-gray-200 rounded-xl hover:bg-blue-50 transition-colors">
                        <input type="radio" id="soltero" name="estado_civil" value="S"
                            class="h-5 w-5 text-blue-600 focus:ring-blue-500">
                        <label for="soltero" class="ml-3 text-gray-700 cursor-pointer flex-1">Soltero(a)</label>
                    </div>
                    <div
                        class="flex items-center p-3 border border-gray-200 rounded-xl hover:bg-blue-50 transition-colors">
                        <input type="radio" id="casado" name="estado_civil" value="C"
                            class="h-5 w-5 text-blue-600 focus:ring-blue-500">
                        <label for="casado" class="ml-3 text-gray-700 cursor-pointer flex-1">Casado(a)</label>
                    </div>
                    <div
                        class="flex items-center p-3 border border-gray-200 rounded-xl hover:bg-blue-50 transition-colors">
                        <input type="radio" id="viudo" name="estado_civil" value="V"
                            class="h-5 w-5 text-blue-600 focus:ring-blue-500">
                        <label for="viudo" class="ml-3 text-gray-700 cursor-pointer flex-1">Viudo(a)</label>
                    </div>
                    <div
                        class="flex items-center p-3 border border-gray-200 rounded-xl hover:bg-blue-50 transition-colors">
                        <input type="radio" id="divorciado" name="estado_civil" value="D"
                            class="h-5 w-5 text-blue-600 focus:ring-blue-500">
                        <label for="divorciado" class="ml-3 text-gray-700 cursor-pointer flex-1">Divorciado(a)</label>
                    </div>
                </div>
            </div>

            <!-- Email y teléfono -->
            <div class="lg:col-span-2">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="block text-gray-700 font-medium mb-2 required">Correo
                            Electrónico</label>
                        <div class="relative">
                            <input type="email" id="email" name="email"
                                class="campo-general w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-gray-50"
                                placeholder="ejemplo@correo.com" required>
                            <i
                                class="fas fa-envelope text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Ingrese un correo válido</p>
                    </div>
                    <div>
                        <label for="telefono" class="block text-gray-700 font-medium mb-2 required">Teléfono /
                            Celular</label>
                        <div class="relative">
                            <input type="tel" id="telefono" name="telefono"
                                class="campo-general w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-gray-50"
                                placeholder="Ej: 987654321" required>
                            <i
                                class="fas fa-phone text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Incluya código de área si aplica</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Domicilio actual (UBIGEO) -->
    <div class="mb-10">
        <h4 class="text-lg font-semibold text-gray-700 mb-6 flex items-center">
            <i class="fas fa-home text-blue-500 mr-2 text-sm"></i>
            Domicilio Actual
        </h4>
        <div class="bg-gradient-to-br from-gray-50 to-white p-6 rounded-2xl border border-gray-200">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                <div class="sm:col-span-2 lg:col-span-2">
                    <label for="direccion"
                        class="block text-sm text-gray-600 mb-2 font-medium">Jr/Calle/Paseo/Av</label>
                    <div class="relative">
                        <input type="text" id="direccion" name="direccion"
                            class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                            placeholder="Nombre de la vía">
                        <i class="fas fa-road text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                    </div>
                </div>
            </div>

            <!-- Ubigeo domicilio -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="domicilioDepartamento" class="block text-sm text-gray-600 mb-2 font-medium">Departamento</label>
                    <select id="domicilioDepartamento" name="domicilioDepartamento"
                        class="campo-general w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white">
                        <option value="" disabled selected>Seleccionar Departamento</option>
                        @foreach ($departamentos as $departamento)
                            <option value="{{ $departamento['id_ubigeo'] }}">
                                {{ $departamento['nombre_ubigeo'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="domicilioProvincia" class="block text-sm text-gray-600 mb-2 font-medium">Provincia</label>
                    <select id="domicilioProvincia" name="domicilioProvincia"
                        class="campo-general w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white"
                        disabled>
                        <option value="" disabled selected>Seleccionar Provincia</option>
                    </select>
                </div>
                <div>
                    <label for="domicilioDistrito" class="block text-sm text-gray-600 mb-2 font-medium">Distrito</label>
                    <select id="domicilioDistrito" name="domicilioDistrito"
                        class="campo-general w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white"
                        disabled>
                        <option value="" disabled selected>Seleccionar Distrito</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Información bancaria -->
    <div class="mb-10">
        <h4 class="text-lg font-semibold text-gray-700 mb-6 flex items-center">
            <i class="fas fa-university text-blue-500 mr-2 text-sm"></i>
            Información Bancaria
        </h4>
        <div class="bg-gradient-to-br from-gray-50 to-white p-6 rounded-2xl border border-gray-200">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="lg:col-span-1">
                    <label for="entidad_bancaria" class="block text-sm text-gray-600 mb-2 font-medium">Entidad
                        Bancaria</label>
                    <div class="relative">
                        <input type="text" id="entidadBancaria" name="entidadBancaria"
                            class="campo-general w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                            placeholder="Ej: BCP">
                        <i
                            class="fas fa-building text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                    </div>
                </div>
                <div class="lg:col-span-1">
                    <label for="tipo_cuenta" class="block text-sm text-gray-600 mb-2 font-medium">Tipo de
                        Cuenta</label>
                    <div class="relative">
                        <select id="tipoCuenta" name="tipoCuenta"
                            class="campo-general w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white">
                            <option value="" disabled selected>Seleccione</option>
                            <option value="Ahorros">Ahorros</option>
                            <option value="Corriente">Corriente</option>
                            <option value="CTS">CTS</option>
                        </select>
                        <i
                            class="fas fa-wallet text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                    </div>
                </div>
                <div class="lg:col-span-1">
                    <label for="moneda" class="block text-sm text-gray-600 mb-2 font-medium">Moneda</label>
                    <div class="relative">
                        <select id="moneda" name="moneda"
                            class="campo-general w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white">
                            <option value="" disabled selected>Seleccione</option>
                            <option value="PEN">Soles</option>
                            <option value="USD">Dólares</option>
                        </select>
                        <i
                            class="fas fa-money-bill-wave text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                    </div>
                </div>
                <div class="lg:col-span-1">
                    <label for="numero_cuenta" class="block text-sm text-gray-600 mb-2 font-medium">Número de
                        Cuenta</label>
                    <div class="relative">
                        <input type="text" id="numeroCuenta" name="numeroCuenta"
                            class="campo-general w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                            placeholder="Ej: 001-123456789">
                        <i
                            class="fas fa-credit-card text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                    </div>
                </div>
                <div class="lg:col-span-1">
                    <label for="numeroCCI" class="block text-sm text-gray-600 mb-2 font-medium">Número de CCI</label>
                    <div class="relative">
                        <input type="text" id="numeroCCI" name="numeroCCI"
                            class="campo-general w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                            placeholder="Ej: 002-1234567890123">
                        <i class="fas fa-barcode text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Seguro de salud y sistema de pensiones -->
    <div class="mb-6">
        <h4 class="text-lg font-semibold text-gray-700 mb-6 flex items-center">
            <i class="fas fa-heartbeat text-blue-500 mr-2 text-sm"></i>
            Seguro y Pensión
        </h4>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Seguro de salud -->
            <div class="bg-gradient-to-br from-green-50 to-white p-6 rounded-2xl border border-green-100">
                <label class="block text-gray-700 font-medium mb-4 flex items-center">
                    <i class="fas fa-shield-alt text-green-500 mr-2"></i>
                    Tipo de Seguro de Salud
                </label>
                <div class="flex flex-col gap-3">
                    @php
                        $seguros = [
                            ['id' => 'SIS', 'label' => 'SIS'],
                            ['id' => 'ESSALUD', 'label' => 'ESSALUD'],
                            ['id' => 'EPS', 'label' => 'EPS'],
                        ];
                    @endphp
                    @foreach ($seguros as $seguro)
                        <div class="flex items-center p-3 border border-gray-200 rounded-xl hover:bg-green-50 transition-colors">
                            <input type="radio" id="seguro_{{ $seguro['id'] }}" name="seguroSalud" value="{{ $seguro['id'] }}"
                                class="radio-seguro h-5 w-5 text-green-600 focus:ring-green-500">
                            <label for="seguro_{{ $seguro['id'] }}" class="ml-3 text-gray-700 cursor-pointer flex-1">{{ $seguro['label'] }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Sistema de pensiones -->
            <div class="bg-gradient-to-br from-purple-50 to-white p-6 rounded-2xl border border-purple-100">
                <label class="block text-gray-700 font-medium mb-4 flex items-center">
                    <i class="fas fa-piggy-bank text-purple-500 mr-2"></i>
                    Sistema de Pensiones
                </label>
                <div class="space-y-4">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex items-center p-4 border border-purple-200 rounded-xl hover:bg-purple-50 transition-colors flex-1">
                            <input type="radio" id="onp" name="sistemaPensiones" value="ONP"
                                class="radio-pension h-5 w-5 text-purple-600 focus:ring-purple-500">
                            <label for="onp" class="ml-3 text-gray-700 cursor-pointer flex-1">
                                <span class="font-medium">ONP</span>
                                <p class="text-sm text-gray-500 mt-1">Oficina de Normalización Previsional</p>
                            </label>
                        </div>
                        <div class="flex items-center p-4 border border-purple-200 rounded-xl hover:bg-purple-50 transition-colors flex-1">
                            <input type="radio" id="afp" name="sistemaPensiones" value="AFP"
                                class="radio-pension h-5 w-5 text-purple-600 focus:ring-purple-500">
                            <label for="afp" class="ml-3 text-gray-700 cursor-pointer flex-1">
                                <span class="font-medium">AFP</span>
                                <p class="text-sm text-gray-500 mt-1">Administradora de Fondos de Pensiones</p>
                            </label>
                        </div>
                        <div class="flex items-center p-4 border border-purple-200 rounded-xl hover:bg-purple-50 transition-colors flex-1">
                            <input type="radio" id="na" name="sistemaPensiones" value="NA"
                                class="radio-pension h-5 w-5 text-purple-600 focus:ring-purple-500">
                            <label for="na" class="ml-3 text-gray-700 cursor-pointer flex-1">
                                <span class="font-medium">No Aplica</span>
                                <p class="text-sm text-gray-500 mt-1">No tiene sistema de pensiones</p>
                            </label>
                        </div>
                    </div>
                    
                    <!-- AFP compañía (solo visible si selecciona AFP) -->
                    <div id="afpCompaniaContainer" class="hidden">
                        <label for="afpCompania" class="block text-sm text-gray-600 mb-2 font-medium">Compañía AFP</label>
                        <select id="afpCompania" name="afpCompania"
                            class="campo-general w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white">
                            <option value="" disabled selected>Seleccione AFP</option>
                            <option value="Integra">Integra</option>
                            <option value="Horizonte">Horizonte</option>
                            <option value="Profuturo">Profuturo</option>
                            <option value="Prima">Prima</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Indicador de progreso de la sección -->
    <div class="mt-8 pt-6 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                <span class="text-sm text-gray-600">Progreso de la sección</span>
            </div>
            <div class="flex items-center">
                <div class="w-32 bg-gray-200 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full transition-all duration-300" style="width: 0%" id="general-progress"></div>
                </div>
                <span class="ml-3 text-sm font-medium text-gray-700" id="general-percentage">0%</span>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript para funcionalidades -->
<script>
// Función para verificar si jQuery está cargado
function loadjQuery(callback) {
    if (window.jQuery) {
        callback();
    } else {
        var script = document.createElement('script');
        script.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
        script.onload = callback;
        document.head.appendChild(script);
    }
}

loadjQuery(function() {
    $(document).ready(function() {
        // ========== CÁLCULO DEL PROGRESO DE LA SECCIÓN ==========
        function calculateGeneralProgress() {
            const campos = [
                // Campos obligatorios (valen más puntos)
                { selector: '#paterno', required: true, weight: 2 },
                { selector: '#nombres', required: true, weight: 2 },
                { selector: '#dia', required: true, weight: 1 },
                { selector: '#mes', required: true, weight: 1 },
                { selector: '#anio', required: true, weight: 1 },
                { selector: '#idTipoDocumento', required: true, weight: 2 },
                { selector: '#num_documento', required: true, weight: 2 },
                { selector: '#email', required: true, weight: 2 },
                { selector: '#telefono', required: true, weight: 2 },
                
                // Campos opcionales (valen menos puntos)
                { selector: '#materno', required: false, weight: 1 },
                { selector: '#nacimientoDepartamento', required: false, weight: 1 },
                { selector: '#nacimientoProvincia', required: false, weight: 1 },
                { selector: '#nacimientoDistrito', required: false, weight: 1 },
                { selector: '#idSexo', required: false, weight: 1 },
                { selector: '#nacionalidad', required: false, weight: 1 },
                { selector: '#domicilioVia', required: false, weight: 1 },
                { selector: '#domicilioMzLt', required: false, weight: 1 },
                { selector: '#domicilioUrb', required: false, weight: 1 },
                { selector: '#domicilioDepartamento', required: false, weight: 1 },
                { selector: '#domicilioProvincia', required: false, weight: 1 },
                { selector: '#domicilioDistrito', required: false, weight: 1 },
                { selector: '#entidadBancaria', required: false, weight: 1 },
                { selector: '#tipoCuenta', required: false, weight: 1 },
                { selector: '#moneda', required: false, weight: 1 },
                { selector: '#numeroCuenta', required: false, weight: 1 },
                { selector: '#numeroCCI', required: false, weight: 1 }
            ];

            let puntosObtenidos = 0;
            let puntosTotales = 0;
            let camposRequeridosCompletados = 0;
            let totalCamposRequeridos = 0;

            // Verificar campos de texto y selects
            campos.forEach(campo => {
                if (campo.required) {
                    totalCamposRequeridos += campo.weight;
                }
                
                const element = $(campo.selector);
                if (element.length > 0) {
                    puntosTotales += campo.weight;
                    
                    if (element.is('input[type="text"], input[type="email"], input[type="tel"], input[type="number"]')) {
                        if (element.val() && element.val().trim() !== '') {
                            puntosObtenidos += campo.weight;
                            if (campo.required) {
                                camposRequeridosCompletados += campo.weight;
                            }
                        }
                    } else if (element.is('select')) {
                        if (element.val() && element.val() !== '') {
                            puntosObtenidos += campo.weight;
                            if (campo.required) {
                                camposRequeridosCompletados += campo.weight;
                            }
                        }
                    }
                }
            });

            // Verificar radios de estado civil (al menos uno debe estar seleccionado)
            const estadoCivilSeleccionado = $('input[name="estadoCivil"]:checked').length > 0;
            if (estadoCivilSeleccionado) {
                puntosObtenidos += 2; // Puntos por tener estado civil
                puntosTotales += 2;
            } else {
                puntosTotales += 2;
            }

            // Verificar radios de seguro de salud (al menos uno debe estar seleccionado)
            const seguroSaludSeleccionado = $('input[name="seguroSalud"]:checked').length > 0;
            if (seguroSaludSeleccionado) {
                puntosObtenidos += 2; // Puntos por tener seguro
                puntosTotales += 2;
            } else {
                puntosTotales += 2;
            }

            // Verificar radios de sistema de pensiones (al menos uno debe estar seleccionado)
            const sistemaPensionesSeleccionado = $('input[name="sistemaPensiones"]:checked').length > 0;
            if (sistemaPensionesSeleccionado) {
                puntosObtenidos += 2; // Puntos por tener sistema de pensiones
                puntosTotales += 2;
                
                // Si seleccionó AFP, verificar que también seleccionó compañía
                if ($('input[name="sistemaPensiones"]:checked').val() === 'AFP') {
                    const afpCompaniaSeleccionada = $('#afpCompania').val() && $('#afpCompania').val() !== '';
                    if (afpCompaniaSeleccionada) {
                        puntosObtenidos += 1;
                        puntosTotales += 1;
                    } else {
                        puntosTotales += 1;
                    }
                }
            } else {
                puntosTotales += 2;
            }

            // Calcular porcentaje
            let porcentaje = 0;
            if (puntosTotales > 0) {
                porcentaje = Math.round((puntosObtenidos / puntosTotales) * 100);
            }

            // Asegurarnos de que si todos los campos requeridos están llenos, sea 100%
            if (camposRequeridosCompletados === totalCamposRequeridos && 
                estadoCivilSeleccionado && 
                seguroSaludSeleccionado && 
                sistemaPensionesSeleccionado) {
                porcentaje = 100;
            }

            // Actualizar la barra de progreso
            $('#general-percentage').text(`${porcentaje}%`);
            $('#general-progress').css('width', `${porcentaje}%`);
            
            // Cambiar color según el porcentaje
            const progressBar = $('#general-progress');
            progressBar.removeClass('bg-green-500 bg-yellow-500 bg-red-500 bg-green-400 bg-yellow-400 bg-gray-300');
            
            if (porcentaje >= 100) {
                progressBar.addClass('bg-green-500');
            } else if (porcentaje >= 80) {
                progressBar.addClass('bg-green-400');
            } else if (porcentaje >= 60) {
                progressBar.addClass('bg-yellow-500');
            } else if (porcentaje >= 40) {
                progressBar.addClass('bg-yellow-400');
            } else if (porcentaje > 0) {
                progressBar.addClass('bg-red-500');
            } else {
                progressBar.addClass('bg-gray-300');
            }

            return porcentaje;
        }

        // ========== UBIGEO LUGAR DE NACIMIENTO ==========
        
        // Cuando se selecciona un departamento (nacimiento)
        $('#nacimientoDepartamento').change(function() {
            var departamentoId = $(this).val();
            if (departamentoId) {
                // Cargar provincias - USANDO TU RUTA EXISTENTE
                $.get('/get-provincia/' + departamentoId, function(response) {
                    if (response.provincias) {
                        var provinciaSelect = $('#nacimientoProvincia');
                        provinciaSelect.empty().prop('disabled', false);
                        provinciaSelect.append('<option value="" disabled selected>Seleccionar Provincia</option>');

                        response.provincias.forEach(function(provincia) {
                            provinciaSelect.append('<option value="' + provincia.id_ubigeo + '">' + provincia.nombre_ubigeo + '</option>');
                        });
                        
                        // Limpiar distrito
                        $('#nacimientoDistrito').empty().prop('disabled', true);
                        $('#nacimientoDistrito').append('<option value="" disabled selected>Seleccionar Distrito</option>');
                    } else if (response.error) {
                        console.error('Error al cargar provincias:', response.error);
                    }
                }).fail(function(xhr, status, error) {
                    console.error('Error en la solicitud AJAX:', error);
                    alert('Error al cargar provincias. Por favor, intente nuevamente.');
                });
            } else {
                $('#nacimientoProvincia').empty().prop('disabled', true);
                $('#nacimientoProvincia').append('<option value="" disabled selected>Seleccionar Provincia</option>');
                
                $('#nacimientoDistrito').empty().prop('disabled', true);
                $('#nacimientoDistrito').append('<option value="" disabled selected>Seleccionar Distrito</option>');
            }
            calculateGeneralProgress();
        });

        // Cuando se selecciona una provincia (nacimiento)
        $('#nacimientoProvincia').change(function() {
            var provinciaId = $(this).val();

            if (provinciaId) {
                // Cargar distritos - USANDO TU RUTA EXISTENTE
                $.get('/get-distrito/' + provinciaId, function(response) {
                    if (response.distritos) {
                        var distritoSelect = $('#nacimientoDistrito');
                        distritoSelect.empty().prop('disabled', false);
                        distritoSelect.append('<option value="" disabled selected>Seleccionar Distrito</option>');

                        response.distritos.forEach(function(distrito) {
                            distritoSelect.append('<option value="' + distrito.id_ubigeo + '">' + distrito.nombre_ubigeo + '</option>');
                        });
                    } else if (response.error) {
                        console.error('Error al cargar distritos:', response.error);
                    }
                }).fail(function(xhr, status, error) {
                    console.error('Error en la solicitud AJAX:', error);
                    alert('Error al cargar distritos. Por favor, intente nuevamente.');
                });
            } else {
                $('#nacimientoDistrito').empty().prop('disabled', true);
                $('#nacimientoDistrito').append('<option value="" disabled selected>Seleccionar Distrito</option>');
            }
            calculateGeneralProgress();
        });

        // ========== UBIGEO DOMICILIO ==========
        
        // Cuando se selecciona un departamento (domicilio)
        $('#domicilioDepartamento').change(function() {
            var departamentoId = $(this).val();
            if (departamentoId) {
                // Cargar provincias - USANDO TU RUTA EXISTENTE
                $.get('/get-provincia/' + departamentoId, function(response) {
                    if (response.provincias) {
                        var provinciaSelect = $('#domicilioProvincia');
                        provinciaSelect.empty().prop('disabled', false);
                        provinciaSelect.append('<option value="" disabled selected>Seleccionar Provincia</option>');

                        response.provincias.forEach(function(provincia) {
                            provinciaSelect.append('<option value="' + provincia.id_ubigeo + '">' + provincia.nombre_ubigeo + '</option>');
                        });
                        
                        // Limpiar distrito
                        $('#domicilioDistrito').empty().prop('disabled', true);
                        $('#domicilioDistrito').append('<option value="" disabled selected>Seleccionar Distrito</option>');
                    } else if (response.error) {
                        console.error('Error al cargar provincias:', response.error);
                    }
                }).fail(function(xhr, status, error) {
                    console.error('Error en la solicitud AJAX:', error);
                    alert('Error al cargar provincias. Por favor, intente nuevamente.');
                });
            } else {
                $('#domicilioProvincia').empty().prop('disabled', true);
                $('#domicilioProvincia').append('<option value="" disabled selected>Seleccionar Provincia</option>');
                
                $('#domicilioDistrito').empty().prop('disabled', true);
                $('#domicilioDistrito').append('<option value="" disabled selected>Seleccionar Distrito</option>');
            }
            calculateGeneralProgress();
        });

        // Cuando se selecciona una provincia (domicilio)
        $('#domicilioProvincia').change(function() {
            var provinciaId = $(this).val();

            if (provinciaId) {
                // Cargar distritos - USANDO TU RUTA EXISTENTE
                $.get('/get-distrito/' + provinciaId, function(response) {
                    if (response.distritos) {
                        var distritoSelect = $('#domicilioDistrito');
                        distritoSelect.empty().prop('disabled', false);
                        distritoSelect.append('<option value="" disabled selected>Seleccionar Distrito</option>');

                        response.distritos.forEach(function(distrito) {
                            distritoSelect.append('<option value="' + distrito.id_ubigeo + '">' + distrito.nombre_ubigeo + '</option>');
                        });
                    } else if (response.error) {
                        console.error('Error al cargar distritos:', response.error);
                    }
                }).fail(function(xhr, status, error) {
                    console.error('Error en la solicitud AJAX:', error);
                    alert('Error al cargar distritos. Por favor, intente nuevamente.');
                });
            } else {
                $('#domicilioDistrito').empty().prop('disabled', true);
                $('#domicilioDistrito').append('<option value="" disabled selected>Seleccionar Distrito</option>');
            }
            calculateGeneralProgress();
        });

        // ========== CÁLCULO DE EDAD ==========
        function calcularEdad() {
            const dia = $('#dia').val();
            const mes = $('#mes').val();
            const anio = $('#anio').val();
            
            if (dia && mes && anio) {
                const fechaNacimiento = new Date(anio, mes - 1, dia);
                const hoy = new Date();
                
                let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
                const mesActual = hoy.getMonth();
                const diaActual = hoy.getDate();
                
                if (mesActual < (mes - 1) || (mesActual === (mes - 1) && diaActual < dia)) {
                    edad--;
                }
                
                $('#edad').val(edad);
                $('#edad-value').text(edad);
            }
        }

        // Escuchar cambios en fecha de nacimiento
        $('#dia, #mes, #anio').on('change input', function() {
            calcularEdad();
            calculateGeneralProgress();
        });

        // ========== VALIDACIÓN DE DOCUMENTO ==========
        $('#idTipoDocumento').change(function() {
            const selectedOption = $(this).find('option:selected');
            const tipoTexto = selectedOption.text().trim();
            const documentoHint = $('#documento-hint');
            
            // Mapeo de tipos de documento y sus longitudes
            const longitudesDocumento = {
                'DNI': 8,
                'Carnet de Extranjería': 12,
                'Pasaporte': 9,
                'RUC': 11
            };
            
            if (longitudesDocumento[tipoTexto]) {
                documentoHint.text(`(${longitudesDocumento[tipoTexto]} caracteres)`);
            } else {
                documentoHint.text('');
            }
            calculateGeneralProgress();
        });

        // Validar documento al escribir
        $('#num_documento').on('input', function() {
            const tipoDocumentoSelect = $('#idTipoDocumento');
            const selectedOption = tipoDocumentoSelect.find('option:selected');
            const tipoTexto = selectedOption.text().trim();
            const numeroDocumento = $(this).val();
            const documentoError = $('#documento-error');
            
            documentoError.hide();
            
            if (!tipoTexto || !numeroDocumento) {
                calculateGeneralProgress();
                return true;
            }
            
            // Validar solo números para DNI, RUC y CE
            if ((tipoTexto === 'DNI' || tipoTexto === 'RUC' || tipoTexto === 'Carnet de Extranjería') && !/^\d+$/.test(numeroDocumento)) {
                documentoError.text('Solo se permiten números para ' + tipoTexto);
                documentoError.show();
                calculateGeneralProgress();
                return false;
            }
            
            // Mapeo de longitudes
            const longitudesDocumento = {
                'DNI': 8,
                'Carnet de Extranjería': 12,
                'Pasaporte': 9,
                'RUC': 11
            };
            
            // Validar longitud
            if (longitudesDocumento[tipoTexto] && numeroDocumento.length !== longitudesDocumento[tipoTexto]) {
                documentoError.text(`${tipoTexto} debe tener ${longitudesDocumento[tipoTexto]} caracteres`);
                documentoError.show();
                calculateGeneralProgress();
                return false;
            }
            
            calculateGeneralProgress();
            return true;
        });

        // ========== MOSTRAR/OCULTAR AFP COMPAÑÍA ==========
        $('input[name="sistemaPensiones"]').change(function() {
            if ($(this).val() === 'AFP') {
                $('#afpCompaniaContainer').removeClass('hidden');
            } else {
                $('#afpCompaniaContainer').addClass('hidden');
                $('#afpCompania').val('');
            }
            calculateGeneralProgress();
        });

        // ========== ESCUCHAR CAMBIOS EN TODOS LOS CAMPOS ==========
        // Campos de texto y selects
        $('.campo-general').on('input change', function() {
            calculateGeneralProgress();
        });
        
        // Fecha de nacimiento
        $('.fecha-nacimiento').on('input change', function() {
            calculateGeneralProgress();
        });
        
        // Radio buttons
        $('.radio-estado-civil, .radio-seguro, .radio-pension').on('change', function() {
            calculateGeneralProgress();
        });
        
        // AFP compañía
        $('#afpCompania').on('change', function() {
            calculateGeneralProgress();
        });

        // ========== VALIDACIÓN GENERAL AL ENVIAR ==========
        $('form').submit(function(event) {
            // Validar documento
            const numDoc = $('#num_documento').val();
            const tipoDocSelect = $('#idTipoDocumento');
            const selectedOption = tipoDocSelect.find('option:selected');
            const tipoTexto = selectedOption.text().trim();
            
            if (tipoTexto && numDoc) {
                const longitudesDocumento = {
                    'DNI': 8,
                    'Carnet de Extranjería': 12,
                    'Pasaporte': 9,
                    'RUC': 11
                };
                
                if (longitudesDocumento[tipoTexto] && numDoc.length !== longitudesDocumento[tipoTexto]) {
                    alert(`${tipoTexto} debe tener ${longitudesDocumento[tipoTexto]} caracteres`);
                    event.preventDefault();
                    return false;
                }
            }
            
            // Validar fecha de nacimiento
            const dia = $('#dia').val();
            const mes = $('#mes').val();
            const anio = $('#anio').val();
            
            if (dia && mes && anio) {
                const fechaNacimiento = new Date(anio, mes - 1, dia);
                const hoy = new Date();
                
                if (fechaNacimiento > hoy) {
                    alert('La fecha de nacimiento no puede ser futura');
                    event.preventDefault();
                    return false;
                }
                
                const edad = parseInt($('#edad').val());
                if (edad < 18) {
                    alert('Debe ser mayor de 18 años');
                    event.preventDefault();
                    return false;
                }
            }
            
            return true;
        });

        // Calcular progreso inicial
        calculateGeneralProgress();
    });
});
</script>

<style>
/* Estilos para la barra de progreso */
#general-progress {
    transition: width 0.5s ease-in-out, background-color 0.5s ease;
}
</style>
