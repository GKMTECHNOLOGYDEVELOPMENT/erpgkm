<x-layout.default>

    <style>
        .tab-btn {
            font-weight: 600;
            color: #6B7280;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
            cursor: pointer;
        }

        .active-tab {
            color: #3B82F6;
            border-color: #3B82F6;
        }

        .tab-content {
            transition: all 0.3s;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        input:focus,
        select:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            border-color: #3B82F6;
        }

        .btn-hover:hover {
            transform: translateY(-1px);
        }
    </style>
<div class="text-center mb-6">
    <h2 class="text-2xl font-bold mb-2">GESTIÓN DE PROSPECTOS COMERCIALES</h2>
    <span class="badge badge-outline-primary">Área Comercial</span>
</div>
    <div class="panel mt-6 p-5 max-w-6x2 mx-auto">
        {{-- Tabs --}}
        <div class="flex space-x-4 border-b border-gray-200 mb-6">
            <button id="tabEmpresaBtn" class="tab-btn active-tab px-6 py-3 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M3 21h18v-2H3v2zm2-4h2v-2H5v2zm0-4h2v-2H5v2zm0-4h2V7H5v2zm4 8h2v-2H9v2zm0-4h2v-2H9v2zm0-4h2V7H9v2zm4 8h2v-2h-2v2zm0-4h2v-2h-2v2zm0-4h2V7h-2v2zm4 8h2v-6h-2v6zm0-8h2V7h-2v2z" />
                </svg>

                Empresa
            </button>
            <button id="tabContactoBtn" class="tab-btn px-6 py-3 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                        clip-rule="evenodd" />
                </svg>
                Contacto
            </button>
        </div>

        {{-- Panel Empresa --}}
        <div id="tabEmpresa" class="tab-content">
            <div class="panel">
                <div class="panel-header">
                    <h3 class="text-xl font-semibold mb-6 text-gray-700 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Registrar Nueva Empresa
                    </h3>
                </div>
                <div class="panel-body">
                    <form id="formEmpresa" class="space-y-6">
                        <!-- Nombre o Razón Social -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre o Razón Social *</label>
                            <div class="flex">
                                <div
                                    class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-gray-600 dark:text-gray-400" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" name="razon_social" placeholder="Ingrese nombre o razón social"
                                    class="form-input ltr:rounded-l-none rtl:rounded-r-none h-[46px]" required>
                            </div>
                        </div>

                        <!-- RUC -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">RUC *</label>
                            <div class="flex space-x-2">
                                <div class="flex flex-grow">
                                    <div
                                        class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5 text-gray-600 dark:text-gray-400" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="text" name="ruc" id="inputRuc" placeholder="Ingrese RUC"
                                        class="form-input ltr:rounded-l-none rtl:rounded-r-none h-[46px]" required>
                                </div>

                                <!-- Botón Buscar -->
                                <button type="button" id="btnBuscarRuc"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center transition-colors h-[46px]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Buscar
                                </button>

                                <!-- Botón Limpiar -->
                                <button type="button" id="btnLimpiarRuc" class="btn btn-info">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M6 6a1 1 0 011.414 0L10 8.586 12.586 6a1 1 0 011.414 1.414L11.414 10l2.586 2.586a1 1 0 01-1.414 1.414L10 11.414 7.414 14a1 1 0 01-1.414-1.414L8.586 10 6 7.414A1 1 0 016 6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Limpiar
                                </button>
                            </div>
                        </div>

                        <!-- Rubro o Giro Comercial -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rubro o Giro Comercial *</label>
                            <div class="flex">
                                <div
                                    class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-gray-600 dark:text-gray-400" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" name="rubro" placeholder="Ingrese rubro o giro comercial"
                                    class="form-input ltr:rounded-l-none rtl:rounded-r-none h-[46px]" required>
                            </div>
                        </div>

                        <!-- Ubicación Geográfica -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ubicación Geográfica</label>
                            <div class="flex">
                                <div
                                    class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-gray-600 dark:text-gray-400" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" name="ubicacion" placeholder="Ingrese ubicación geográfica"
                                    class="form-input ltr:rounded-l-none rtl:rounded-r-none h-[46px]">
                            </div>
                        </div>

                        <!-- Fuente de Captación -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fuente de Captación *</label>
                            <div class="flex">
                                <div
                                    class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-gray-600 dark:text-gray-400" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                    </svg>
                                </div>
                                <select name="fuente_captacion_id" id="fuenteCaptacion"
                                    class="form-select ltr:rounded-l-none rtl:rounded-r-none text-white-dark h-[46px] w-full"
                                    required>
                                    <option value="">-- Seleccione --</option>
                                </select>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="pt-2 flex flex-wrap justify-center gap-4">
                            <!-- Botón Registrar -->
                            <button type="submit"
                                class="btn btn-primary flex items-center justify-center px-6 h-[46px]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                        clip-rule="evenodd" />
                                </svg>
                                Registrar Empresa
                            </button>

                            <!-- Botón Limpiar -->
                            <button type="button" id="btnLimpiarFormulario"
                                class="btn btn-dark flex items-center justify-center px-6 h-[46px]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M6 6a1 1 0 011.414 0L10 8.586 12.586 6a1 1 0 011.414 1.414L11.414 10l2.586 2.586a1 1 0 01-1.414 1.414L10 11.414 7.414 14a1 1 0 01-1.414-1.414L8.586 10 6 7.414A1 1 0 016 6z"
                                        clip-rule="evenodd" />
                                </svg>
                                Limpiar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Panel Contacto --}}
        <div id="tabContacto" class="tab-content hidden">
            <div class="panel">
                <div class="panel-header">
                    <h3 class="text-xl font-semibold mb-6 text-gray-700 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Registrar Nuevo Contacto
                    </h3>
                </div>
                <div class="panel-body">
                    <form id="formContacto" class="space-y-6">
                        <!-- Sección Documento (2 columnas en pantallas grandes) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Campo Tipo de Documento -->
                            <div class="form-group">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Documento *</label>
                                <div class="flex">
                                    <div
                                        class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5 text-gray-600 dark:text-gray-400" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <select name="tipo_documento" id="tipoDocumento"
                                        class="form-select ltr:rounded-l-none rtl:rounded-r-none text-white-dark h-[42px] w-full"
                                        required>
                                        <option value="">-- Seleccione --</option>
                                    </select>
                                </div>
                            </div>


                            <!-- Campo Número de Documento -->
                            <div class="form-group">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Número de Documento
                                    *</label>
                                <div class="flex space-x-2">
                                    <div class="flex flex-grow">
                                        <div
                                            class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 text-gray-600 dark:text-gray-400" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input type="text" name="numero_documento" id="numeroDocumento"
                                            class="form-input ltr:rounded-l-none rtl:rounded-r-none h-[46px]"
                                            placeholder="Ingrese número de documento" required>
                                    </div>

                                    <!-- Botón Buscar -->
                                    <button type="button" id="buscarClienteBtn"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center transition-colors h-[46px]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Buscar
                                    </button>

                                    <!-- Botón Limpiar -->
                                    <button type="button" id="btnLimpiarNumeroDocumento" class="btn btn-info">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M6 6a1 1 0 011.414 0L10 8.586 12.586 6a1 1 0 011.414 1.414L11.414 10l2.586 2.586a1 1 0 01-1.414 1.414L10 11.414 7.414 14a1 1 0 01-1.414-1.414L8.586 10 6 7.414A1 1 0 016 6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Limpiar
                                    </button>
                                </div>
                            </div>

                        </div>

                        <!-- Resto de campos -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo *</label>
                            <div class="flex">
                                <div
                                    class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-gray-600 dark:text-gray-400" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" name="nombre_completo"
                                    class="form-input ltr:rounded-l-none rtl:rounded-r-none h-[42px]"
                                    placeholder="Ingrese nombre completo" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cargo</label>
                            <div class="flex">
                                <div
                                    class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-gray-600 dark:text-gray-400" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z"
                                            clip-rule="evenodd" />
                                        <path
                                            d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z" />
                                    </svg>
                                </div>
                                <input type="text" name="cargo"
                                    class="form-input ltr:rounded-l-none rtl:rounded-r-none h-[42px]"
                                    placeholder="Ingrese cargo">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                            <div class="flex">
                                <div
                                    class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-gray-600 dark:text-gray-400" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                    </svg>
                                </div>
                                <input type="email" name="correo"
                                    class="form-input ltr:rounded-l-none rtl:rounded-r-none h-[42px]"
                                    placeholder="ejemplo@correo.com">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono o WhatsApp</label>
                            <div class="flex">
                                <div
                                    class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-gray-600 dark:text-gray-400" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                    </svg>
                                </div>
                                <input type="text" placeholder="+51987654321" name="telefono"
                                    class="form-input ltr:rounded-l-none rtl:rounded-r-none h-[42px]">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nivel de Decisión</label>
                            <div class="flex">
                                <div
                                    class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-gray-600 dark:text-gray-400" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <select name="nivel_decision" id="nivelDecision"
                                    class="form-select ltr:rounded-l-none rtl:rounded-r-none text-white-dark h-[42px]">
                                    <option value="">-- Seleccione --</option>
                                </select>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="pt-2 flex flex-wrap justify-center gap-4">
                            <!-- Botón Registrar -->
                            <button type="submit"
                                class="btn btn-primary flex items-center justify-center px-6 h-[46px]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                        clip-rule="evenodd" />
                                </svg>
                                Registrar Contacto
                            </button>

                            <!-- Botón Limpiar Formulario -->
                            <button type="button" id="btnLimpiarFormularioContacto"
                                class="btn btn-dark flex items-center justify-center px-6 h-[46px]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M6 6a1 1 0 011.414 0L10 8.586 12.586 6a1 1 0 011.414 1.414L11.414 10l2.586 2.586a1 1 0 01-1.414 1.414L10 11.414 7.414 14a1 1 0 01-1.414-1.414L8.586 10 6 7.414A1 1 0 016 6z"
                                        clip-rule="evenodd" />
                                </svg>
                                Limpiar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="{{ asset('assets/js/areacomercial/crear.js') }}"></script>
    <script src="{{ asset('assets/js/areacomercial/catalago.js') }}"></script>
    <script src="{{ asset('assets/js/areacomercial/buscarcliente.js') }}"></script>
    <script src="{{ asset('assets/js/areacomercial/buscarempresa.js') }}"></script>

    <script>
        document.getElementById('btnLimpiarRuc').addEventListener('click', function() {
            document.getElementById('inputRuc').value = '';
        });
        document.getElementById('btnLimpiarFormulario').addEventListener('click', function() {
            document.getElementById('formEmpresa').reset();
        });

        document.getElementById('btnLimpiarNumeroDocumento').addEventListener('click', function() {
            document.getElementById('numeroDocumento').value = '';
        });

        document.getElementById('btnLimpiarFormularioContacto').addEventListener('click', function() {
            document.getElementById('formContacto').reset();
        });
    </script>
</x-layout.default>
