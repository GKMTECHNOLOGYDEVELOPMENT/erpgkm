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
                        class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 bg-gray-50"
                        placeholder="Ingrese apellido" required>
                    <i class="fas fa-user text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                </div>
            </div>
            <div>
                <label for="materno" class="block text-gray-700 font-medium mb-2">Apellido Materno</label>
                <div class="relative">
                    <input type="text" id="materno" name="materno"
                        class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 bg-gray-50"
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
                        class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 bg-gray-50"
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
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
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
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white">
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
                            <input type="number" id="anio" name="anio" min="1900" max="2024"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                placeholder="AAAA">
                            <i
                                class="fas fa-calendar text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lugar de nacimiento -->
            <div class="bg-gradient-to-br from-blue-50 to-white p-6 rounded-2xl border border-blue-100">
                <label class="block text-gray-700 font-medium mb-4">Lugar de Nacimiento</label>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <label for="pais" class="block text-sm text-gray-600 mb-2 font-medium">País</label>
                        <input type="text" id="pais" name="pais"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                            placeholder="Ej: Perú">
                    </div>
                    <div>
                        <label for="departamento"
                            class="block text-sm text-gray-600 mb-2 font-medium">Departamento</label>
                        <input type="text" id="departamento" name="departamento"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                            placeholder="Ej: Lima">
                    </div>
                    <div>
                        <label for="provincia" class="block text-sm text-gray-600 mb-2 font-medium">Provincia</label>
                        <input type="text" id="provincia" name="provincia"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                            placeholder="Ej: Lima">
                    </div>
                    <div>
                        <label for="distrito" class="block text-sm text-gray-600 mb-2 font-medium">Distrito</label>
                        <input type="text" id="distrito" name="distrito"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                            placeholder="Ej: San Isidro">
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
                    <select id="tipo_documento" name="tipo_documento"
                        class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white"
                        required>
                        <option value="">Seleccione</option>
                        <option value="DNI">DNI</option>
                        <option value="Carnet de Extranjería">Carnet de Extranjería</option>
                        <option value="Pasaporte">Pasaporte</option>
                        <option value="Otro">Otro</option>
                    </select>
                    <i
                        class="fas fa-file-alt text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                </div>
            </div>
            <div>
                <label for="num_documento" class="block text-gray-700 font-medium mb-2 required">Nº Documento</label>
                <div class="relative">
                    <input type="text" id="num_documento" name="num_documento"
                        class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-gray-50"
                        placeholder="Ej: 12345678" required>
                    <i class="fas fa-hashtag text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
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
                <label for="sexo" class="block text-gray-700 font-medium mb-2">Sexo</label>
                <div class="relative">
                    <select id="sexo" name="sexo"
                        class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white">
                        <option value="">Seleccione</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                        <option value="O">Otro</option>
                    </select>
                    <i
                        class="fas fa-venus-mars text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                </div>
            </div>
            <div>
                <label for="nacionalidad" class="block text-gray-700 font-medium mb-2">Nacionalidad</label>
                <div class="relative">
                    <input type="text" id="nacionalidad" name="nacionalidad" value="Peruana"
                        class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-gray-50">
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
                                class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-gray-50"
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
                                class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-gray-50"
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

    <!-- Domicilio actual -->
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
                <div>
                    <label for="numero" class="block text-sm text-gray-600 mb-2 font-medium">Nº/Mz/Lt</label>
                    <div class="relative">
                        <input type="text" id="numero" name="numero"
                            class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                            placeholder="Número">
                        <i class="fas fa-hashtag text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                    </div>
                </div>
                <div>
                    <label for="urb_localidad" class="block text-sm text-gray-600 mb-2 font-medium">Urb. o
                        Localidad</label>
                    <div class="relative">
                        <input type="text" id="urb_localidad" name="urb_localidad"
                            class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                            placeholder="Urbanización">
                        <i class="fas fa-city text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                    </div>
                </div>
                <div>
                    <label for="departamento_dir"
                        class="block text-sm text-gray-600 mb-2 font-medium">Departamento</label>
                    <div class="relative">
                        <input type="text" id="departamento_dir" name="departamento_dir"
                            class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                            placeholder="Departamento">
                        <i
                            class="fas fa-map-marker-alt text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                    </div>
                </div>
                <div>
                    <label for="provincia_dir" class="block text-sm text-gray-600 mb-2 font-medium">Provincia</label>
                    <div class="relative">
                        <input type="text" id="provincia_dir" name="provincia_dir"
                            class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                            placeholder="Provincia">
                        <i
                            class="fas fa-map-marked-alt text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                    </div>
                </div>
                <div>
                    <label for="distrito_dir" class="block text-sm text-gray-600 mb-2 font-medium">Distrito</label>
                    <div class="relative">
                        <input type="text" id="distrito_dir" name="distrito_dir"
                            class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                            placeholder="Distrito">
                        <i class="fas fa-map-pin text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                    </div>
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
                        <input type="text" id="entidad_bancaria" name="entidad_bancaria"
                            class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                            placeholder="Ej: BCP">
                        <i
                            class="fas fa-building text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                    </div>
                </div>
                <div class="lg:col-span-1">
                    <label for="tipo_cuenta" class="block text-sm text-gray-600 mb-2 font-medium">Tipo de
                        Cuenta</label>
                    <div class="relative">
                        <select id="tipo_cuenta" name="tipo_cuenta"
                            class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white">
                            <option value="">Seleccione</option>
                            <option value="Ahorro">Ahorro</option>
                            <option value="Corriente">Corriente</option>
                            <option value="Sueldo">Sueldo</option>
                        </select>
                        <i
                            class="fas fa-wallet text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                    </div>
                </div>
                <div class="lg:col-span-1">
                    <label for="moneda" class="block text-sm text-gray-600 mb-2 font-medium">Moneda</label>
                    <div class="relative">
                        <select id="moneda" name="moneda"
                            class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white">
                            <option value="">Seleccione</option>
                            <option value="Soles">Soles</option>
                            <option value="Dólares">Dólares</option>
                        </select>
                        <i
                            class="fas fa-money-bill-wave text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                    </div>
                </div>
                <div class="lg:col-span-1">
                    <label for="numero_cuenta" class="block text-sm text-gray-600 mb-2 font-medium">Número de
                        Cuenta</label>
                    <div class="relative">
                        <input type="text" id="numero_cuenta" name="numero_cuenta"
                            class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                            placeholder="Ej: 001-123456789">
                        <i
                            class="fas fa-credit-card text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                    </div>
                </div>
                <div class="lg:col-span-1">
                    <label for="numero_cci" class="block text-sm text-gray-600 mb-2 font-medium">Número de CCI</label>
                    <div class="relative">
                        <input type="text" id="numero_cci" name="numero_cci"
                            class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
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
                    <span class="ml-2 text-sm text-gray-500">(Puede marcar varios)</span>
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @php
                        $seguros = [
                            ['id' => 'sin', 'value' => 'SIN', 'label' => 'SIN'],
                            ['id' => 'essalud', 'value' => 'ESSALUD', 'label' => 'ESSALUD'],
                            ['id' => 'eps', 'value' => 'EPS', 'label' => 'EPS'],
                            ['id' => 'integral', 'value' => 'Integral', 'label' => 'Integral'],
                            ['id' => 'horizonte', 'value' => 'Horizonte', 'label' => 'Horizonte'],
                            ['id' => 'profuturo', 'value' => 'Profuturo', 'label' => 'Profuturo'],
                            ['id' => 'prima', 'value' => 'Prima', 'label' => 'Prima'],
                        ];
                    @endphp
                    @foreach ($seguros as $seguro)
                        <div
                            class="flex items-center p-3 border border-gray-200 rounded-xl hover:bg-green-50 transition-colors">
                            <input type="checkbox" id="{{ $seguro['id'] }}" name="seguro_salud[]"
                                value="{{ $seguro['value'] }}"
                                class="h-5 w-5 text-green-600 rounded focus:ring-green-500">
                            <label for="{{ $seguro['id'] }}"
                                class="ml-3 text-gray-700 cursor-pointer flex-1">{{ $seguro['label'] }}</label>
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
                <div class="flex flex-col sm:flex-row gap-4">
                    <div
                        class="flex items-center p-4 border border-purple-200 rounded-xl hover:bg-purple-50 transition-colors flex-1">
                        <input type="radio" id="onp" name="sistema_pensiones" value="ONP"
                            class="h-5 w-5 text-purple-600 focus:ring-purple-500">
                        <label for="onp" class="ml-3 text-gray-700 cursor-pointer flex-1">
                            <span class="font-medium">ONP</span>
                            <p class="text-sm text-gray-500 mt-1">Oficina de Normalización Previsional</p>
                        </label>
                    </div>
                    <div
                        class="flex items-center p-4 border border-purple-200 rounded-xl hover:bg-purple-50 transition-colors flex-1">
                        <input type="radio" id="afp" name="sistema_pensiones" value="AFP"
                            class="h-5 w-5 text-purple-600 focus:ring-purple-500">
                        <label for="afp" class="ml-3 text-gray-700 cursor-pointer flex-1">
                            <span class="font-medium">AFP</span>
                            <p class="text-sm text-gray-500 mt-1">Administradora de Fondos de Pensiones</p>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
