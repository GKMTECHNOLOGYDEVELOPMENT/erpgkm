<x-layout.default>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
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
    </style>

    <div x-data="wizardDespacho()" class="min-h-screen py-8">
        <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
            <!-- Header -->
            <div class="text-center mb-12 fade-in">
                <div
                    class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full shadow-lg mb-6 border border-gray-200">
                    <i class="fas fa-file-export text-3xl text-primary"></i>
                </div>
                <h1 class="text-4xl font-bold text-gray-800 mb-3">Documento de Salida</h1>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">Complete la información requerida paso a paso para
                    generar su documento de salida</p>
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
                                        '!border-primary !bg-primary text-white': currentStep ===
                                            0,
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
                                        '!border-primary !bg-primary text-white': currentStep ===
                                            1,
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
                                        '!border-primary !bg-primary text-white': currentStep ===
                                            2,
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

            <form method="POST" action="#" class="w-full">
                @csrf

                <!-- Step 1: Información del Documento -->
                <div x-show="currentStep === 0" class="step-content fade-in">
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
                                    <select name="guia_tipo" class="form-control">
                                        <option value="GR_Electronica_TI01">GR Electrónica TI01</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Número</label>
                                    <input type="text" name="numero" value="5778" class="form-control">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Documento</label>
                                    <select name="documento" class="form-control">
                                        <option value="factura">Factura</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Entrega</label>
                                    <input type="date" name="fecha_entrega" value="2025-06-23" class="form-control">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Traslado</label>
                                    <input type="date" name="fecha_traslado" value="2025-06-23" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Direcciones -->
                <div x-show="currentStep === 1" class="step-content fade-in">
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
                                            value="AV SANTA ELVIRA E MZ B LOTE 8 URBA SAN ELÍAS" class="form-control">
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-2">Departamento</label>
                                            <select name="dpto_partida" class="form-control">
                                                <option value="Lima">Lima</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-2">Provincia</label>
                                            <select name="provincia_partida" class="form-control">
                                                <option value="Lima">Lima</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-2">Distrito</label>
                                            <select name="distrito_partida" class="form-control">
                                                <option value="Los Olivos">Los Olivos</option>
                                            </select>
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
                                            placeholder="Ingrese dirección de llegada" class="form-control">
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-2">Departamento</label>
                                            <select name="dpto_llegada" class="form-control">
                                                <option value="Amazonas">Amazonas</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-2">Provincia</label>
                                            <select name="provincia_llegada" class="form-control">
                                                <option value="Bongara">Bongara</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-2">Distrito</label>
                                            <select name="distrito_llegada" class="form-control">
                                                <option value="Corosha">Corosha</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Cliente y Transporte -->
                <div x-show="currentStep === 2" class="step-content fade-in">
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
                                        <select name="cliente" class="form-control">
                                            <option value="network_industries">Network Industries Sac</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Vendedor</label>
                                        <select name="vendedor" class="form-control">
                                            <option value="paulino_pascual">Paulino, Pascual, EFRAIN Rodrigo</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Trasbordo</label>
                                        <select name="trasbordo" class="form-control">
                                            <option value="si">Sí</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Modo
                                            Traslado</label>
                                        <select name="modo_traslado" class="form-control">
                                            <option value="publico">Público</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Conductor</label>
                                        <input type="text" name="conductor_nombre" value="GKM TECHNOLOGY"
                                            class="form-control">
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-2">Condiciones</label>
                                            <select name="condiciones" class="form-control">
                                                <option value="contado">Contado</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo
                                                Traslado</label>
                                            <select name="tipo_traslado" class="form-control">
                                                <option value="venta_sujeta_confirmacion">Venta sujeta a confirmación
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Artículos - Estructura Mejorada -->
                <div x-show="currentStep === 3" class="step-content fade-in">
                    <div class="card">
                        <!-- Header Mejorado -->
                        <div class="card-header bg-gradient-to-r from-primary to-primary-dark">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div
                                        class="flex items-center justify-center w-12 h-12 rounded-xl bg-white bg-opacity-20 mr-4 backdrop-blur-sm">
                                        <i class="fas fa-boxes text-black text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold text-white">Gestión de Artículos</h3>
                                        <p class="text-white text-opacity-80 text-sm mt-1">Agrega y gestiona los
                                            productos del despacho</p>
                                    </div>
                                </div>
                                <button type="button" @click="agregarArticulo()"
                                    class="btn bg-white text-primary font-semibold rounded-lg px-4 py-2 hover:scale-105 transition-all duration-200 shadow-lg">
                                    <i class="fas fa-plus-circle mr-2"></i> Agregar Artículo
                                </button>
                            </div>
                        </div>

                        <div class="p-6">
                            <!-- Barra de búsqueda mejorada -->
                            <div class="mb-6 relative">
                                <div class="relative">
                                    <i
                                        class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                    <input type="text" x-model="searchTerm"
                                        placeholder="Buscar por código, descripción..."
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200">
                                </div>
                            </div>

                            <!-- Lista de artículos - Estructura simplificada -->
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

                                        <!-- Campos del artículo en grid simple -->
                                        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-4 gap-4">

                                            <!-- Columna 1: Código y Descripción -->
                                            <div class="space-y-3">
                                                <div>
                                                    <label
                                                        class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                                        <i class="fas fa-barcode mr-2 text-primary text-xs"></i>
                                                        Código
                                                    </label>
                                                    <input type="text" x-model="articulo.codigo"
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

                                            <!-- Columna 2: Stock y Unidad -->
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

                                            <!-- Columna 3: Precio y Cantidad -->
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

                                            <!-- Columna 4: Total del artículo -->
                                            <div
                                                class="flex flex-col justify-center space-y-2 bg-gray-50 rounded-lg p-4">
                                                <div class="text-center">
                                                    <p class="text-sm text-gray-600 mb-1">Total del Artículo</p>
                                                    <p class="text-2xl font-bold text-primary">
                                                        S/ <span
                                                            x-text="(articulo.precio * articulo.cantidad).toFixed(2)"></span>
                                                    </p>
                                                </div>
                                                <div class="flex justify-center space-x-3 text-xs text-gray-500">
                                                    <span x-show="articulo.codigo" class="flex items-center">
                                                        <i class="fas fa-barcode mr-1"></i>
                                                        <span x-text="articulo.codigo"></span>
                                                    </span>
                                                    <span class="flex items-center">
                                                        <i class="fas fa-boxes mr-1"></i>
                                                        <span x-text="`Stock: ${articulo.stock || 0}`"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Totales - Versión Minimalista -->
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
                        <button type="button" class="btn btn-outline">
                            <i class="fas fa-times mr-2"></i> Cancelar
                        </button>

                        <button type="button" @click="nextStep()" x-show="currentStep < 3" class="btn btn-primary"
                            :disabled="currentStep === 3">
                            Siguiente <i class="fas fa-arrow-right ml-2"></i>
                        </button>

                        <button type="submit" x-show="currentStep === 3" class="btn btn-success">
                            <i class="fas fa-check mr-2"></i> Guardar Documento
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="//unpkg.com/alpinejs" defer></script>
    <!-- Importar el archivo JS de despacho -->
    <script src="{{ asset('assets/js/almacen/despacho/despacho.js') }}"></script>
</x-layout.default>
