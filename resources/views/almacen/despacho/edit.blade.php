<x-layout.default>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- ESTILOS (mantener igual) -->
    <style>
        /* Tus estilos CSS actuales... mantenlos igual */
        :root {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --primary-light: #dbeafe;
            --success: #10b981;
            --success-dark: #059669;
            --success-light: #d1fae5;
            --warning: #f59e0b;
            --warning-dark: #d97706;
            --warning-light: #fef3c7;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            min-height: 100vh;
        }

        .step-progress {
            transition: width 0.5s ease;
        }

        .step-indicator {
            transition: all 0.3s ease;
            border-width: 3px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .step-indicator.active {
            border-color: var(--primary);
            background-color: var(--primary);
            color: white;
            transform: scale(1.1);
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3);
        }

        .step-indicator.completed {
            border-color: var(--success);
            background-color: var(--success);
            color: white;
            box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3);
        }

        .step-content {
            transition: all 0.5s ease;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            overflow: hidden;
            border: 1px solid var(--gray-200);
        }

        .card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 1.5rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success) 0%, var(--success-dark) 100%);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.4);
        }

        .btn-outline {
            background: white;
            color: var(--gray-700);
            border: 1px solid var(--gray-300);
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .btn-outline:hover {
            background: var(--gray-50);
            border-color: var(--gray-400);
        }

        .form-control {
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            padding: 0.75rem;
            transition: all 0.3s ease;
            width: 100%;
            font-size: 0.875rem;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .icon-container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border-radius: 12px;
            margin-right: 1rem;
        }

        .icon-primary {
            background: var(--primary-light);
            color: var(--primary);
        }

        .icon-success {
            background: var(--success-light);
            color: var(--success);
        }

        .icon-warning {
            background: var(--warning-light);
            color: var(--warning);
        }

        .progress-container {
            position: relative;
            height: 8px;
            background-color: var(--gray-200);
            border-radius: 4px;
            overflow: hidden;
            margin: 2rem 0;
        }

        .progress-bar {
            position: absolute;
            height: 100%;
            background: linear-gradient(90deg, var(--primary) 0%, var(--success) 100%);
            border-radius: 4px;
            transition: width 0.5s ease;
        }

        .step-label {
            font-weight: 600;
            margin-top: 0.5rem;
            font-size: 0.875rem;
        }

        .step-description {
            font-size: 0.75rem;
            color: var(--gray-600);
            margin-top: 0.25rem;
        }

        .article-item {
            background: var(--gray-50);
            border-radius: 8px;
            border: 1px solid var(--gray-200);
            padding: 1.25rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .article-item:hover {
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .total-box {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 12px;
            border: 1px solid var(--gray-200);
            padding: 1.5rem;
        }

        .section-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.5rem;
        }

        .section-subtitle {
            font-size: 0.875rem;
            color: var(--gray-600);
            margin-bottom: 1.5rem;
        }

        .search-box {
            position: relative;
        }

        .search-box i {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
        }

        .search-box input {
            padding-left: 2.5rem;
        }

        /* Estilos para Select2 */
        .select2-container--default .select2-selection--single {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            height: 42px;
            padding: 0.5rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
        }

        .border-red-500 {
            border-color: #ef4444 !important;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        /* Efecto de shake para inputs con error */
        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        .border-red-500 {
            animation: shake 0.5s ease-in-out;
        }
    </style>

    <div x-data="wizardDespachoEdit" class="min-h-screen py-8">
        <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
            <!-- Header -->
            <div class="text-center mb-12 fade-in">
                <div
                    class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full shadow-lg mb-6 border border-gray-200">
                    <i class="fas fa-edit text-3xl text-primary"></i>
                </div>
                <h1 class="text-4xl font-bold text-gray-800 mb-3">Editar Documento de Salida</h1>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">Modifique la información del documento de salida existente</p>
            </div>

            <!-- Progress Bar -->
            <div class="progress-container">
                <div class="progress-bar" :style="'width: ' + ((currentStep + 1) / 4 * 100) + '%'"></div>
            </div>

            <!-- Wizard Steps -->
            <div class="mb-10">
                <div class="w-full">
                    <div class="relative">
                        <!-- Step Indicators -->
                        <ul class="mb-8 grid grid-cols-4">
                            <li class="mx-auto text-center">
                                <a href="javascript:;"
                                    class="step-indicator border-gray-200 bg-white flex justify-center items-center w-14 h-14 rounded-full mx-auto"
                                    :class="{
                                        '!border-primary !bg-primary text-white': currentStep === 0,
                                        '!border-success !bg-success text-white': currentStep > 0
                                    }"
                                    @click="currentStep = 0">
                                    <i class="fas fa-file-alt text-lg" x-show="currentStep <= 0"></i>
                                    <i class="fas fa-check text-lg" x-show="currentStep > 0"></i>
                                </a>
                                <span class="step-label"
                                    :class="{ 'text-primary': currentStep === 0, 'text-success': currentStep > 0 }">Documento</span>
                                <p class="step-description">Información básica</p>
                            </li>
                            <li class="mx-auto text-center">
                                <a href="javascript:;"
                                    class="step-indicator border-gray-200 bg-white flex justify-center items-center w-14 h-14 rounded-full mx-auto"
                                    :class="{
                                        '!border-primary !bg-primary text-white': currentStep === 1,
                                        '!border-success !bg-success text-white': currentStep > 1
                                    }"
                                    @click="currentStep = 1">
                                    <span x-show="currentStep <= 1" class="text-lg font-semibold">2</span>
                                    <i class="fas fa-check text-lg" x-show="currentStep > 1"></i>
                                </a>
                                <span class="step-label"
                                    :class="{ 'text-primary': currentStep === 1, 'text-success': currentStep > 1 }">Direcciones</span>
                                <p class="step-description">Origen y destino</p>
                            </li>
                            <li class="mx-auto text-center">
                                <a href="javascript:;"
                                    class="step-indicator border-gray-200 bg-white flex justify-center items-center w-14 h-14 rounded-full mx-auto"
                                    :class="{
                                        '!border-primary !bg-primary text-white': currentStep === 2,
                                        '!border-success !bg-success text-white': currentStep > 2
                                    }"
                                    @click="currentStep = 2">
                                    <span x-show="currentStep <= 2" class="text-lg font-semibold">3</span>
                                    <i class="fas fa-check text-lg" x-show="currentStep > 2"></i>
                                </a>
                                <span class="step-label"
                                    :class="{ 'text-primary': currentStep === 2, 'text-success': currentStep > 2 }">Cliente</span>
                                <p class="step-description">Datos del cliente</p>
                            </li>
                            <li class="mx-auto text-center">
                                <a href="javascript:;"
                                    class="step-indicator border-gray-200 bg-white flex justify-center items-center w-14 h-14 rounded-full mx-auto"
                                    :class="{ '!border-primary !bg-primary text-white': currentStep === 3 }"
                                    @click="currentStep = 3">
                                    <span class="text-lg font-semibold">4</span>
                                </a>
                                <span class="step-label" :class="{ 'text-primary': currentStep === 3 }">Artículos</span>
                                <p class="step-description">Productos a enviar</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('despacho.update', $despacho->id) }}" id="despachoForm" @submit.prevent="submitForm">
                @csrf
                @method('PUT')

                <!-- Step 1: Información del Documento -->
                <div x-show="currentStep === 0" class="step-content fade-in" x-transition>
                    <div class="card">
                        <div class="p-8">
                            <div class="flex items-center mb-8">
                                <div class="icon-container icon-primary">
                                    <i class="fas fa-file-alt text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="section-title">Información del Documento</h3>
                                    <p class="section-subtitle">Datos principales del documento de salida</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div class="lg:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo Guía</label>
                                    <input type="text" name="tipo_guia" value="{{ old('tipo_guia', $despacho->tipo_guia) }}"
                                        class="form-control" required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Número</label>
                                    <input type="text" name="numero" value="{{ old('numero', $despacho->numero) }}" class="form-control" required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Documento</label>
                                    <select name="documento" class="form-control" required>
                                        <option value="guia" {{ old('documento', $despacho->documento) == 'guia' ? 'selected' : '' }}>Guía</option>
                                        <option value="factura" {{ old('documento', $despacho->documento) == 'factura' ? 'selected' : '' }}>Factura</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Entrega</label>
                                    <input type="date" name="fecha_entrega" value="{{ old('fecha_entrega', $despacho->fecha_entrega) }}"
                                        class="form-control" required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Traslado</label>
                                    <input type="date" name="fecha_traslado" value="{{ old('fecha_traslado', $despacho->fecha_traslado) }}"
                                        class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Direcciones -->
                <div x-show="currentStep === 1" class="step-content fade-in" x-transition>
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                        <!-- Partida -->
                        <div class="card">
                            <div class="p-8">
                                <div class="flex items-center mb-8">
                                    <div class="icon-container icon-success">
                                        <i class="fas fa-map-marker-alt text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="section-title">Dirección de Partida</h3>
                                        <p class="section-subtitle">Origen del despacho</p>
                                    </div>
                                </div>

                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Dirección</label>
                                        <input type="text" name="direccion_partida"
                                            value="{{ old('direccion_partida', $despacho->direccion_partida) }}" class="form-control"
                                            required>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-2">Departamento</label>
                                            <select name="dpto_partida" class="form-control" required>
                                                <option value="">Seleccionar</option>
                                                <template x-for="departamento in departamentos" :key="departamento">
                                                    <option :value="departamento" x-text="departamento"
                                                        :selected="departamento === '{{ old('dpto_partida', $despacho->dpto_partida) }}'"></option>
                                                </template>
                                            </select>
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-2">Provincia</label>
                                            <input type="text" name="provincia_partida" value="{{ old('provincia_partida', $despacho->provincia_partida) }}"
                                                class="form-control" required>
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-2">Distrito</label>
                                            <input type="text" name="distrito_partida" value="{{ old('distrito_partida', $despacho->distrito_partida) }}"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Llegada -->
                        <div class="card">
                            <div class="p-8">
                                <div class="flex items-center mb-8">
                                    <div class="icon-container icon-primary">
                                        <i class="fas fa-flag-checkered text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="section-title">Dirección de Llegada</h3>
                                        <p class="section-subtitle">Destino del despacho</p>
                                    </div>
                                </div>

                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Dirección</label>
                                        <input type="text" name="direccion_llegada"
                                            value="{{ old('direccion_llegada', $despacho->direccion_llegada) }}" class="form-control" required>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-2">Departamento</label>
                                            <select name="dpto_llegada" class="form-control" required>
                                                <option value="">Seleccionar</option>
                                                <template x-for="departamento in departamentos"
                                                    :key="departamento">
                                                    <option :value="departamento" x-text="departamento"
                                                        :selected="departamento === '{{ old('dpto_llegada', $despacho->dpto_llegada) }}'"></option>
                                                </template>
                                            </select>
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-2">Provincia</label>
                                            <input type="text" name="provincia_llegada"
                                                value="{{ old('provincia_llegada', $despacho->provincia_llegada) }}" class="form-control" required>
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-2">Distrito</label>
                                            <input type="text" name="distrito_llegada"
                                                value="{{ old('distrito_llegada', $despacho->distrito_llegada) }}" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Cliente y Transporte -->
                <div x-show="currentStep === 2" class="step-content fade-in" x-transition>
                    <div class="card">
                        <div class="p-8">
                            <div class="flex items-center mb-8">
                                <div class="icon-container icon-warning">
                                    <i class="fas fa-users text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="section-title">Cliente y Transporte</h3>
                                    <p class="section-subtitle">Información del cliente y datos de envío</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Cliente</label>
                                        <select name="cliente_id" id="cliente_select" class="form-control" required>
                                            <option value="">Seleccionar cliente</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Vendedor</label>
                                        <select name="vendedor_id" id="vendedor_select" class="form-control"
                                            required>
                                            <option value="">Seleccionar vendedor</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Trasbordo</label>
                                        <select name="trasbordo" class="form-control" required>
                                            <option value="si" {{ old('trasbordo', $despacho->trasbordo) == 'si' ? 'selected' : '' }}>Sí</option>
                                            <option value="no" {{ old('trasbordo', $despacho->trasbordo) == 'no' ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Modo
                                            Traslado</label>
                                        <select name="modo_traslado" class="form-control" required>
                                            <option value="publico" {{ old('modo_traslado', $despacho->modo_traslado) == 'publico' ? 'selected' : '' }}>Público</option>
                                            <option value="privado" {{ old('modo_traslado', $despacho->modo_traslado) == 'privado' ? 'selected' : '' }}>Privado</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Conductor</label>
                                        <select name="conductor_id" id="conductor_select" class="form-control"
                                            required>
                                            <option value="">Seleccionar conductor</option>
                                        </select>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-2">Condiciones</label>
                                            <select name="condiciones" class="form-control" required>
                                                <option value="contado" {{ old('condiciones', $despacho->condiciones) == 'contado' ? 'selected' : '' }}>Contado</option>
                                                <option value="contrato" {{ old('condiciones', $despacho->condiciones) == 'contrato' ? 'selected' : '' }}>Contrato</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo
                                                Traslado</label>
                                            <select name="tipo_traslado" class="form-control" required>
                                                <option value="venta_sujeta_confirmacion" {{ old('tipo_traslado', $despacho->tipo_traslado) == 'venta_sujeta_confirmacion' ? 'selected' : '' }}>Venta sujeta a confirmación</option>
                                                <option value="traslado_interno" {{ old('tipo_traslado', $despacho->tipo_traslado) == 'traslado_interno' ? 'selected' : '' }}>Traslado interno</option>
                                                <option value="importacion" {{ old('tipo_traslado', $despacho->tipo_traslado) == 'importacion' ? 'selected' : '' }}>Importación</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Artículos -->
                <div x-show="currentStep === 3" class="step-content fade-in" x-transition>
                    <div class="card">
                        <div class="card-header bg-gradient-to-r from-primary to-primary-dark">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div
                                        class="flex items-center justify-center w-12 h-12 rounded-xl bg-white bg-opacity-20 mr-4 backdrop-blur-sm">
                                        <i class="fas fa-boxes text-black text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold text-white">Gestión de Artículos</h3>
                                        <p class="text-white text-opacity-80 text-sm mt-1">Modifica y gestiona los productos del despacho</p>
                                    </div>
                                </div>
                                <button type="button" @click="agregarArticulo()"
                                    class="btn bg-white text-primary font-semibold rounded-lg px-4 py-2 hover:scale-105 transition-all duration-200 shadow-lg">
                                    <i class="fas fa-plus-circle mr-2"></i> Agregar Artículo
                                </button>
                            </div>
                        </div>

                        <div class="p-6">
                            <!-- Barra de búsqueda -->
                            <div class="mb-6 relative">
                                <div class="relative">
                                    <i
                                        class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                    <input type="text" x-model="searchTerm"
                                        placeholder="Buscar por código, descripción..."
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200">
                                </div>
                            </div>

                            <!-- Lista de artículos -->
                            <div class="space-y-4 max-h-96 overflow-y-auto pr-2 custom-scrollbar">
                                <!-- Estado vacío -->
                                <div x-show="articulos.length === 0" class="text-center py-12">
                                    <div class="max-w-md mx-auto">
                                        <div
                                            class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center">
                                            <i class="fas fa-box-open text-2xl text-gray-400"></i>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-600 mb-2">No hay artículos agregados
                                        </h3>
                                        <p class="text-gray-500 mb-6">Comienza agregando el primer artículo a tu
                                            despacho</p>
                                        <button type="button" @click="agregarArticulo()"
                                            class="btn btn-primary rounded-lg px-6 py-3">
                                            <i class="fas fa-plus-circle mr-2"></i>
                                            Agregar Primer Artículo
                                        </button>
                                    </div>
                                </div>

                                <!-- Artículos existentes -->
                                <template x-for="(articulo, index) in articulos" :key="articulo.id">
                                    <div
                                        class="article-item bg-white border border-gray-200 rounded-xl p-5 hover:shadow-lg transition-all duration-200 group">
                                        <!-- Header del artículo -->
                                        <div
                                            class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                                            <div class="flex items-center space-x-3">
                                                <div
                                                    class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center text-sm font-bold">
                                                    <span x-text="index + 1"></span>
                                                </div>
                                                <div>
                                                    <h4 class="font-semibold text-gray-800"
                                                        x-text="articulo.codigo || 'Sin código'"></h4>
                                                    <p class="text-sm text-gray-500"
                                                        x-text="articulo.descripcion || 'Sin descripción'"></p>
                                                </div>
                                            </div>
                                            <button type="button" @click="eliminarArticulo(index)"
                                                class="w-7 h-7 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600 transition-all duration-200 opacity-0 group-hover:opacity-100">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>

                                        <!-- Campos del artículo -->
                                        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-4 gap-4">
                                            <!-- Código y Descripción -->
                                            <div class="space-y-3">
                                                <div>
                                                    <label
                                                        class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                                        <i class="fas fa-barcode mr-2 text-primary text-xs"></i>
                                                        Código
                                                    </label>
                                                    <input type="text" x-model="articulo.codigo"
                                                        @change="cargarArticuloPorCodigo(index)"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-primary focus:border-primary transition text-sm">
                                                </div>
                                                <div>
                                                    <label
                                                        class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                                        <i class="fas fa-align-left mr-2 text-primary text-xs"></i>
                                                        Descripción
                                                    </label>
                                                    <input type="text" x-model="articulo.descripcion"
                                                        placeholder="Ingresa descripción"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-primary focus:border-primary transition text-sm">
                                                </div>
                                            </div>

                                            <!-- Stock y Unidad -->
                                            <div class="space-y-3">
                                                <div>
                                                    <label
                                                        class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                                        <i class="fas fa-boxes mr-2 text-primary text-xs"></i>
                                                        Stock
                                                    </label>
                                                    <div class="relative">
                                                        <input type="number" x-model="articulo.stock"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-primary focus:border-primary transition text-sm pr-10">
                                                        <i
                                                            class="fas fa-warehouse absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <label
                                                        class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                                        <i class="fas fa-balance-scale mr-2 text-primary text-xs"></i>
                                                        Unidad
                                                    </label>
                                                    <select x-model="articulo.unidad"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-primary focus:border-primary transition text-sm">
                                                        <option>Unidad</option>
                                                        <option>Kg</option>
                                                        <option>Litro</option>
                                                        <option>Caja</option>
                                                        <option>Paquete</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Precio y Cantidad -->
                                            <div class="space-y-3">
                                                <div>
                                                    <label
                                                        class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                                        <i class="fas fa-tag mr-2 text-primary text-xs"></i>
                                                        Precio Venta
                                                    </label>
                                                    <div class="relative">
                                                        <span
                                                            class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">S/</span>
                                                        <input type="number" step="0.01"
                                                            x-model="articulo.precio" @input="calcularTotales()"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-primary focus:border-primary transition text-sm pl-10">
                                                    </div>
                                                </div>
                                                <div>
                                                    <label
                                                        class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                                        <i class="fas fa-calculator mr-2 text-primary text-xs"></i>
                                                        Cantidad
                                                    </label>
                                                    <div class="relative">
                                                        <input type="number" x-model="articulo.cantidad"
                                                            @input="calcularTotales()"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-primary focus:border-primary transition text-sm">
                                                        <i
                                                            class="fas fa-sort-numeric-up absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Total del artículo -->
                                            <div
                                                class="flex flex-col justify-center space-y-2 bg-gray-50 rounded-lg p-4">
                                                <div class="text-center">
                                                    <p class="text-sm text-gray-600 mb-1">Total del Artículo</p>
                                                    <p class="text-2xl font-bold text-primary">
                                                        S/ <span
                                                            x-text="(articulo.precio * articulo.cantidad).toFixed(2)"></span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Totales -->
                            <div class="mt-8 bg-gray-50 rounded-lg p-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-receipt text-primary"></i>
                                        <div>
                                            <p class="text-sm text-gray-600">Total del despacho</p>
                                            <p class="text-lg font-semibold text-gray-800"
                                                x-text="`${articulos.length} artículo(s)`"></p>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <p class="text-2xl font-bold text-primary">S/ <span
                                                x-text="total.toFixed(2)"></span></p>
                                        <p class="text-sm text-gray-500">
                                            <span>S/ <span x-text="subtotal.toFixed(2)"></span></span>
                                            +
                                            <span>S/ <span x-text="igv.toFixed(2)"></span> IGV</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Campos hidden para enviar datos -->
                            <input type="hidden" name="subtotal_hidden" :value="subtotal.toFixed(2)">
                            <input type="hidden" name="igv_hidden" :value="igv.toFixed(2)">
                            <input type="hidden" name="total_hidden" :value="total.toFixed(2)">
                            <input type="hidden" name="articulos" :value="JSON.stringify(articulos)">
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-between pt-8 fade-in mt-4">
                    <button type="button" @click="previousStep()" x-show="currentStep > 0" class="btn btn-outline"
                        :disabled="currentStep === 0">
                        <i class="fas fa-arrow-left mr-2"></i> Anterior
                    </button>

                    <div class="flex gap-4 ml-auto">
                        <button type="button" class="btn btn-outline" @click="cancelar()">
                            <i class="fas fa-times mr-2"></i> Cancelar
                        </button>

                        <button type="button" @click="nextStep()" x-show="currentStep < 3" class="btn btn-primary"
                            :disabled="currentStep === 3">
                            Siguiente <i class="fas fa-arrow-right ml-2"></i>
                        </button>

                        <button type="submit" x-show="currentStep === 3" class="btn btn-success"
                            :disabled="articulos.length === 0 || submitting">
                            <i class="fas fa-save mr-2"></i>
                            <span x-text="submitting ? 'Actualizando...' : 'Actualizar Documento'"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts en el orden correcto - SOLO UNA VEZ -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        // PARCHE DE SEGURIDAD PARA custom.js
        (function() {
            'use strict';
            console.log('🛡️ Aplicando parche de seguridad para classList.remove...');

            // Guardar el método original
            const originalRemove = DOMTokenList.prototype.remove;

            // Sobrescribir el método para filtrar tokens vacíos
            DOMTokenList.prototype.remove = function(...tokens) {
                const validTokens = tokens.filter(token =>
                    token !== null &&
                    token !== undefined &&
                    token !== '' &&
                    String(token).trim() !== ''
                );

                if (validTokens.length > 0) {
                    return originalRemove.apply(this, validTokens);
                }
                return this;
            };

            console.log('✅ Parche aplicado correctamente');
        })();
    </script>

    <script>
        document.addEventListener('alpine:init', () => {
            // Verificar si ya está inicializado
            if (window.wizardDespachoEditInitialized) {
                console.log('Wizard Edit ya fue inicializado, ignorando...');
                return;
            }
            window.wizardDespachoEditInitialized = true;

            console.log('=== INICIALIZANDO WIZARD EDIT UNA SOLA VEZ ===');

            Alpine.data('wizardDespachoEdit', () => ({
                currentStep: 0,
                articulos: @json($despacho->articulos ?? []),
                searchTerm: '',
                subtotal: {{ $despacho->subtotal ?? 0 }},
                igv: {{ $despacho->igv ?? 0 }},
                total: {{ $despacho->total ?? 0 }},
                departamentos: [],
                clientes: [],
                usuarios: [],
                articulosDisponibles: [],
                datosCargados: false,
                errors: {},
                submitting: false,
                initCalled: false,

                async init() {
                    if (this.initCalled) {
                        console.log('Init ya fue llamado, ignorando...');
                        return;
                    }

                    this.initCalled = true;
                    console.log('=== INICIALIZANDO COMPONENTE EDIT ===');

                    await this.cargarDatosIniciales();
                    this.inicializarSelect2();
                    this.calcularTotales();
                    this.datosCargados = true;
                },

                async cargarDatosIniciales() {
                    try {
                        console.log('Cargando datos iniciales para edición...');

                        // Cargar departamentos
                        const deptResponse = await fetch('/api/departamentosdespacho');
                        if (deptResponse.ok) {
                            this.departamentos = await deptResponse.json();
                            console.log('Departamentos cargados:', this.departamentos.length);
                        }

                        // Cargar clientes
                        const clientesResponse = await fetch('/api/clientesdespacho');
                        if (clientesResponse.ok) {
                            const clientesData = await clientesResponse.json();
                            this.clientes = clientesData.map(cliente => ({
                                id: cliente.id,
                                text: cliente.text ||
                                    `${cliente.nombre} - ${cliente.documento}`
                            }));
                            console.log('Clientes cargados:', this.clientes.length);
                        }

                        // Cargar usuarios
                        const usuariosResponse = await fetch('/api/usuariosdespacho');
                        if (usuariosResponse.ok) {
                            this.usuarios = await usuariosResponse.json();
                            console.log('Usuarios cargados:', this.usuarios.length);
                        }

                        // Cargar artículos
                        const articulosResponse = await fetch('/api/articulosdespacho');
                        if (articulosResponse.ok) {
                            this.articulosDisponibles = await articulosResponse.json();
                            console.log('Artículos cargados:', this.articulosDisponibles.length);
                        }

                        console.log('=== DATOS CARGADOS EXITOSAMENTE PARA EDICIÓN ===');

                    } catch (error) {
                        console.error('Error cargando datos:', error);
                    }
                },

                inicializarSelect2() {
                    setTimeout(() => {
                        try {
                            console.log('Inicializando Select2 para edición...');

                            // Clientes - establecer valor actual
                            if ($('#cliente_select').length && !$('#cliente_select').hasClass(
                                    'select2-hidden-accessible')) {
                                $('#cliente_select').select2({
                                    data: this.clientes,
                                    placeholder: 'Seleccionar cliente',
                                    width: '100%',
                                    allowClear: true
                                }).val('{{ $despacho->cliente_id }}').trigger('change');
                            }

                            // Vendedores - establecer valor actual
                            if ($('#vendedor_select').length && !$('#vendedor_select').hasClass(
                                    'select2-hidden-accessible')) {
                                $('#vendedor_select').select2({
                                    data: this.usuarios,
                                    placeholder: 'Seleccionar vendedor',
                                    width: '100%',
                                    allowClear: true
                                }).val('{{ $despacho->vendedor_id }}').trigger('change');
                            }

                            // Conductores - establecer valor actual
                            if ($('#conductor_select').length && !$('#conductor_select')
                                .hasClass('select2-hidden-accessible')) {
                                $('#conductor_select').select2({
                                    data: this.usuarios,
                                    placeholder: 'Seleccionar conductor',
                                    width: '100%',
                                    allowClear: true
                                }).val('{{ $despacho->conductor_id }}').trigger('change');
                            }

                            console.log('Select2 para edición inicializado correctamente');

                        } catch (error) {
                            console.error('Error inicializando Select2:', error);
                        }
                    }, 500);
                },

                // Los métodos validarPaso0, validarPaso1, validarPaso2, validarPaso3, 
                // mostrarErrores, nextStep, previousStep, cargarArticuloPorCodigo,
                // agregarArticulo, eliminarArticulo, calcularTotales se mantienen igual
                // que en el create (omitiéndolos por brevedad, pero debes copiarlos)

                validarPaso0() {
                    /* igual que tu código original del create */
                    this.errors = {};
                    let isValid = true;

                    const tipoGuia = document.querySelector('input[name="tipo_guia"]');
                    if (!tipoGuia.value.trim()) {
                        this.errors.tipo_guia = 'El tipo de guía es requerido';
                        isValid = false;
                    }

                    const numero = document.querySelector('input[name="numero"]');
                    if (!numero.value.trim()) {
                        this.errors.numero = 'El número es requerido';
                        isValid = false;
                    }

                    const documento = document.querySelector('select[name="documento"]');
                    if (!documento.value) {
                        this.errors.documento = 'El documento es requerido';
                        isValid = false;
                    }

                    const fechaEntrega = document.querySelector('input[name="fecha_entrega"]');
                    if (!fechaEntrega.value) {
                        this.errors.fecha_entrega = 'La fecha de entrega es requerida';
                        isValid = false;
                    }

                    const fechaTraslado = document.querySelector('input[name="fecha_traslado"]');
                    if (!fechaTraslado.value) {
                        this.errors.fecha_traslado = 'La fecha de traslado es requerida';
                        isValid = false;
                    }

                    return isValid;
                },

                validarPaso1() {
                    this.errors = {};
                    let isValid = true;

                    const dirPartida = document.querySelector('input[name="direccion_partida"]');
                    if (!dirPartida.value.trim()) {
                        this.errors.direccion_partida = 'La dirección de partida es requerida';
                        isValid = false;
                    }

                    const dptoPartida = document.querySelector('select[name="dpto_partida"]');
                    if (!dptoPartida.value) {
                        this.errors.dpto_partida = 'El departamento de partida es requerido';
                        isValid = false;
                    }

                    const provinciaPartida = document.querySelector('input[name="provincia_partida"]');
                    if (!provinciaPartida.value.trim()) {
                        this.errors.provincia_partida = 'La provincia de partida es requerida';
                        isValid = false;
                    }

                    const distritoPartida = document.querySelector('input[name="distrito_partida"]');
                    if (!distritoPartida.value.trim()) {
                        this.errors.distrito_partida = 'El distrito de partida es requerido';
                        isValid = false;
                    }

                    const dirLlegada = document.querySelector('input[name="direccion_llegada"]');
                    if (!dirLlegada.value.trim()) {
                        this.errors.direccion_llegada = 'La dirección de llegada es requerida';
                        isValid = false;
                    }

                    const dptoLlegada = document.querySelector('select[name="dpto_llegada"]');
                    if (!dptoLlegada.value) {
                        this.errors.dpto_llegada = 'El departamento de llegada es requerido';
                        isValid = false;
                    }

                    const provinciaLlegada = document.querySelector('input[name="provincia_llegada"]');
                    if (!provinciaLlegada.value.trim()) {
                        this.errors.provincia_llegada = 'La provincia de llegada es requerida';
                        isValid = false;
                    }

                    const distritoLlegada = document.querySelector('input[name="distrito_llegada"]');
                    if (!distritoLlegada.value.trim()) {
                        this.errors.distrito_llegada = 'El distrito de llegada es requerido';
                        isValid = false;
                    }

                    return isValid;
                },

                validarPaso2() {
                    this.errors = {};
                    let isValid = true;

                    const cliente = document.querySelector('select[name="cliente_id"]');
                    if (!cliente.value) {
                        this.errors.cliente_id = 'El cliente es requerido';
                        isValid = false;
                    }

                    const vendedor = document.querySelector('select[name="vendedor_id"]');
                    if (!vendedor.value) {
                        this.errors.vendedor_id = 'El vendedor es requerido';
                        isValid = false;
                    }

                    const conductor = document.querySelector('select[name="conductor_id"]');
                    if (!conductor.value) {
                        this.errors.conductor_id = 'El conductor es requerido';
                        isValid = false;
                    }

                    const modoTraslado = document.querySelector('select[name="modo_traslado"]');
                    if (!modoTraslado.value) {
                        this.errors.modo_traslado = 'El modo de traslado es requerido';
                        isValid = false;
                    }

                    const condiciones = document.querySelector('select[name="condiciones"]');
                    if (!condiciones.value) {
                        this.errors.condiciones = 'Las condiciones son requeridas';
                        isValid = false;
                    }

                    const tipoTraslado = document.querySelector('select[name="tipo_traslado"]');
                    if (!tipoTraslado.value) {
                        this.errors.tipo_traslado = 'El tipo de traslado es requerido';
                        isValid = false;
                    }

                    return isValid;
                },

                validarPaso3() {
                    this.errors = {};
                    let isValid = true;

                    if (this.articulos.length === 0) {
                        this.errors.articulos = 'Debe agregar al menos un artículo';
                        isValid = false;
                        return isValid;
                    }

                    this.articulos.forEach((articulo, index) => {
                        if (!articulo.codigo || articulo.codigo.trim() === '') {
                            this.errors[`articulo_${index}_codigo`] =
                                `El artículo ${index + 1} debe tener un código`;
                            isValid = false;
                        }

                        if (!articulo.descripcion || articulo.descripcion.trim() === '') {
                            this.errors[`articulo_${index}_descripcion`] =
                                `El artículo ${index + 1} debe tener una descripción`;
                            isValid = false;
                        }

                        if (articulo.cantidad <= 0) {
                            this.errors[`articulo_${index}_cantidad`] =
                                `La cantidad del artículo ${index + 1} debe ser mayor a 0`;
                            isValid = false;
                        }

                        if (articulo.precio < 0) {
                            this.errors[`articulo_${index}_precio`] =
                                `El precio del artículo ${index + 1} no puede ser negativo`;
                            isValid = false;
                        }
                    });

                    return isValid;
                },

                validarPasoActual() {
                    switch (this.currentStep) {
                        case 0:
                            return this.validarPaso0();
                        case 1:
                            return this.validarPaso1();
                        case 2:
                            return this.validarPaso2();
                        case 3:
                            return this.validarPaso3();
                        default:
                            return true;
                    }
                },

                mostrarErrores() {
                    document.querySelectorAll('.error-message').forEach(el => el.remove());

                    Object.keys(this.errors).forEach(field => {
                        const errorMessage = this.errors[field];
                        const inputElement = document.querySelector(`[name="${field}"]`);
                        if (inputElement) {
                            inputElement.classList.add('border-red-500', 'border-2');
                            const errorDiv = document.createElement('div');
                            errorDiv.className =
                                'error-message text-red-500 text-sm mt-1 flex items-center';
                            errorDiv.innerHTML =
                                `<i class="fas fa-exclamation-circle mr-1"></i> ${errorMessage}`;
                            inputElement.parentNode.appendChild(errorDiv);
                        }
                    });

                    if (Object.keys(this.errors).length > 0) {
                        const errorCount = Object.keys(this.errors).length;
                        toastr.warning(`Tienes ${errorCount} error(es). Revisa los campos marcados.`);
                    }
                },

                async nextStep() {
                    if (!this.validarPasoActual()) {
                        this.mostrarErrores();
                        return;
                    }

                    this.errors = {};
                    document.querySelectorAll('.error-message').forEach(el => el.remove());
                    document.querySelectorAll('.border-red-500').forEach(el => el.classList.remove(
                        'border-red-500', 'border-2'));

                    if (this.currentStep < 3) {
                        this.currentStep++;
                        toastr.info('Avanzaste al siguiente paso.');
                    }
                },

                previousStep() {
                    this.errors = {};
                    document.querySelectorAll('.error-message').forEach(el => el.remove());
                    document.querySelectorAll('.border-red-500').forEach(el => el.classList.remove(
                        'border-red-500', 'border-2'));

                    if (this.currentStep > 0) {
                        this.currentStep--;
                        toastr.info('Retrocediste al paso anterior.');
                    }
                },

                async cargarArticuloPorCodigo(index) {
                    const articulo = this.articulos[index];
                    if (!articulo.codigo || articulo.codigo.trim() === '') return;

                    let articuloEncontrado = this.articulosDisponibles.find(a =>
                        a.codigo && a.codigo.toString().trim() === articulo.codigo.toString()
                        .trim()
                    );

                    if (!articuloEncontrado) {
                        articuloEncontrado = this.articulosDisponibles.find(a =>
                            a.text && a.text.toString().toLowerCase().includes(articulo.codigo
                                .toString().toLowerCase())
                        );
                    }

                    if (articuloEncontrado) {
                        this.articulos[index] = {
                            ...this.articulos[index],
                            descripcion: articuloEncontrado.text || articuloEncontrado
                                .descripcion || articuloEncontrado.nombre || 'Sin descripción',
                            precio: articuloEncontrado.precio || 0,
                            stock: articuloEncontrado.stock || 0
                        };
                        this.calcularTotales();
                    }
                },

                agregarArticulo() {
                    const nuevoId = this.articulos.length > 0 ? Math.max(...this.articulos.map(a => a
                        .id)) + 1 : 1;

                    this.articulos.push({
                        id: nuevoId,
                        codigo: '',
                        descripcion: '',
                        stock: 0,
                        unidad: 'Unidad',
                        precio: 0,
                        cantidad: 1,
                    });

                    this.calcularTotales();
                },

                eliminarArticulo(index) {
                    if (confirm('¿Está seguro de eliminar este artículo?')) {
                        this.articulos.splice(index, 1);
                        this.calcularTotales();
                    }
                },

                calcularTotales() {
                    this.subtotal = this.articulos.reduce((sum, articulo) => {
                        return sum + (articulo.precio * articulo.cantidad);
                    }, 0);

                    this.igv = this.subtotal * 0.18;
                    this.total = this.subtotal + this.igv;
                },

                async submitForm() {
                    console.log('=== INICIANDO ACTUALIZACIÓN ===');

                    if (this.submitting) return;
                    this.submitting = true;

                    if (!this.validarPaso0() || !this.validarPaso1() || !this.validarPaso2() || !
                        this.validarPaso3()) {
                        this.mostrarErrores();
                        toastr.error(
                            'Por favor completa todos los campos requeridos antes de actualizar.');
                        this.submitting = false;
                        return;
                    }

                    const form = document.getElementById('despachoForm');
                    const formData = new FormData(form);

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                            body: formData
                        });

                        const result = await response.json();

                        if (result.success) {
                            toastr.success('Despacho actualizado exitosamente.');
                            setTimeout(() => window.location.href = '/despacho', 1000);
                        } else {
                            let errorMessage = result.message || 'Error desconocido';
                            if (result.errors) errorMessage = Object.values(result.errors).flat()
                                .join(', ');
                            toastr.error(errorMessage);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        toastr.error('Error al actualizar el despacho: ' + error.message);
                    } finally {
                        this.submitting = false;
                    }
                },

                cancelar() {
                    if (confirm('¿Está seguro de cancelar la edición? Se perderán todos los cambios no guardados.')) {
                        window.location.href = '/despacho';
                    }
                }
            }));
        });
    </script>
</x-layout.default>