<!-- Sección 3: Información Familiar -->
<div class="form-section bg-white rounded-2xl shadow-lg p-6 md:p-8 mt-8 border border-gray-200">
    <!-- Encabezado de sección -->
    <div class="section-header mb-8 pb-6 border-b border-gray-200">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center">
                <div class="bg-gradient-to-r from-pink-500 to-rose-600 w-12 h-12 rounded-xl flex items-center justify-center mr-4 shadow-md">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl md:text-2xl font-bold text-gray-800">Información Familiar</h3>
                    <p class="text-gray-500 mt-1 text-sm md:text-base">Complete los datos de sus familiares directos (opcional)</p>
                </div>
            </div>
            <span class="bg-pink-50 text-pink-700 px-4 py-2 rounded-full text-sm font-semibold border border-pink-100">
                Sección 3
            </span>
        </div>
    </div>

    <!-- Instrucciones y botón para agregar hijos -->
    <div class="mb-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-gradient-to-r from-pink-50 to-rose-50 border border-pink-100 rounded-xl p-5">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-pink-500 text-lg mt-1 mr-3"></i>
                <div>
                    <h4 class="font-medium text-pink-800 mb-1">Instrucciones</h4>
                    <p class="text-pink-700 text-sm">Esta sección es <span class="font-bold">opcional</span>. Complete solo si tiene familiares directos.</p>
                </div>
            </div>
        </div>
        
        <div class="flex justify-end items-center">
            <button type="button" id="add-hijo-btn" 
                class="inline-flex items-center px-4 py-3 bg-gradient-to-r from-pink-500 to-rose-500 text-white rounded-xl hover:from-pink-600 hover:to-rose-600 transition-all shadow-md hover:shadow-lg">
                <i class="fas fa-plus mr-2"></i>
                <span>Agregar Hijo</span>
            </button>
        </div>
    </div>

    <!-- Tabla de información familiar -->
    <div class="overflow-hidden rounded-2xl border border-gray-200 shadow-sm">
        <!-- Encabezados para desktop -->
        <div class="hidden md:grid md:grid-cols-7 bg-gradient-to-r from-pink-50 to-rose-100 border-b border-pink-200">
            @php
                $headers = [
                    'Parentesco',
                    'Apellidos y Nombres',
                    'N° Documento',
                    'Ocupación',
                    'Sexo',
                    'Fecha Nacimiento',
                    'Domicilio Actual'
                ];
            @endphp
            @foreach ($headers as $header)
                <div class="px-4 py-4 text-left">
                    <span class="text-sm font-semibold text-pink-800 uppercase tracking-wide">{{ $header }}</span>
                </div>
            @endforeach
        </div>

        <!-- Contenido de la tabla -->
        <div class="divide-y divide-gray-100" id="familia-container">
            @php
                $familiares = [
                    [
                        'id' => 'conyuge',
                        'nombre' => 'Cónyuge',
                        'color' => 'pink',
                        'icon' => 'fas fa-ring',
                        'required' => false,
                        'placeholder_nombres' => 'Nombre completo del cónyuge (opcional)'
                    ],
                    [
                        'id' => 'concubino',
                        'nombre' => 'Concubin@',
                        'color' => 'rose',
                        'icon' => 'fas fa-heart',
                        'required' => false,
                        'placeholder_nombres' => 'Nombre completo (opcional)'
                    ]
                ];
            @endphp

            @foreach ($familiares as $index => $familiar)
                <!-- Fila para desktop -->
                <div class="hidden md:grid md:grid-cols-7 items-center p-4 hover:bg-gray-50 transition-colors duration-200 {{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                    <!-- Parentesco -->
                    <div class="px-4 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-{{ $familiar['color'] }}-100 flex items-center justify-center mr-3">
                                <i class="{{ $familiar['icon'] }} text-{{ $familiar['color'] }}-600"></i>
                            </div>
                            <span class="font-semibold text-gray-800">{{ $familiar['nombre'] }}</span>
                            <span class="ml-2 text-xs text-gray-500">(opcional)</span>
                        </div>
                    </div>

                    <!-- Apellidos y Nombres -->
                    <div class="px-4 py-4">
                        <div class="relative">
                            <input type="text" name="{{ $familiar['id'] }}_nombres"
                                class="campo-familiar w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-{{ $familiar['color'] }}-500 focus:ring-2 focus:ring-{{ $familiar['color'] }}-200 transition-all bg-white"
                                placeholder="{{ $familiar['placeholder_nombres'] }}"
                                data-tipo="{{ $familiar['id'] }}">
                        </div>
                    </div>

                    <!-- N° Documento -->
                    <div class="px-4 py-4">
                        <div class="relative">
                            <input type="text" name="{{ $familiar['id'] }}_documento"
                                class="campo-familiar w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-{{ $familiar['color'] }}-500 focus:ring-2 focus:ring-{{ $familiar['color'] }}-200 transition-all bg-white"
                                placeholder="N° de documento"
                                data-tipo="{{ $familiar['id'] }}">
                        </div>
                    </div>

                    <!-- Ocupación -->
                    <div class="px-4 py-4">
                        <div class="relative">
                            <input type="text" name="{{ $familiar['id'] }}_ocupacion"
                                class="campo-familiar w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-{{ $familiar['color'] }}-500 focus:ring-2 focus:ring-{{ $familiar['color'] }}-200 transition-all bg-white"
                                placeholder="Ocupación actual"
                                data-tipo="{{ $familiar['id'] }}">
                        </div>
                    </div>

                    <!-- Sexo -->
                    <div class="px-4 py-4">
                        <div class="relative">
                            <select name="{{ $familiar['id'] }}_sexo"
                                class="campo-familiar w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-{{ $familiar['color'] }}-500 focus:ring-2 focus:ring-{{ $familiar['color'] }}-200 transition-all appearance-none bg-white"
                                data-tipo="{{ $familiar['id'] }}">
                                <option value="">Seleccione</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                            <i class="fas fa-venus-mars text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                        </div>
                    </div>

                    <!-- Fecha Nacimiento -->
                    <div class="px-4 py-4">
                        <div class="relative">
                            <input type="text" 
                                   name="{{ $familiar['id'] }}_nacimiento"
                                   class="flatpickr-familia campo-familiar w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-{{ $familiar['color'] }}-500 focus:ring-2 focus:ring-{{ $familiar['color'] }}-200 transition-all bg-white"
                                   placeholder="Seleccione fecha"
                                   data-tipo="{{ $familiar['id'] }}">
                            <i class="fas fa-birthday-cake text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                        </div>
                    </div>

                    <!-- Domicilio Actual -->
                    <div class="px-4 py-4">
                        <div class="relative">
                            <input type="text" name="{{ $familiar['id'] }}_domicilio"
                                class="campo-familiar w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-{{ $familiar['color'] }}-500 focus:ring-2 focus:ring-{{ $familiar['color'] }}-200 transition-all bg-white"
                                placeholder="Dirección actual"
                                data-tipo="{{ $familiar['id'] }}">
                        </div>
                    </div>
                </div>

                <!-- Tarjeta para móvil -->
                <div class="md:hidden bg-white border border-gray-200 rounded-xl p-5 mb-4 shadow-sm">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-{{ $familiar['color'] }}-100 flex items-center justify-center mr-3">
                                <i class="{{ $familiar['icon'] }} text-{{ $familiar['color'] }}-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">{{ $familiar['nombre'] }}</h4>
                                <p class="text-sm text-gray-500">Familiar directo (opcional)</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <!-- Nombres -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Apellidos y Nombres</label>
                            <div class="relative">
                                <input type="text" name="{{ $familiar['id'] }}_nombres"
                                    class="campo-familiar w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-{{ $familiar['color'] }}-500 focus:ring-2 focus:ring-{{ $familiar['color'] }}-200 transition-all bg-white"
                                    placeholder="Nombre completo (opcional)"
                                    data-tipo="{{ $familiar['id'] }}">
                                <i class="fas fa-user text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Documento -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">N° Documento</label>
                                <div class="relative">
                                    <input type="text" name="{{ $familiar['id'] }}_documento"
                                        class="campo-familiar w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-{{ $familiar['color'] }}-500 focus:ring-2 focus:ring-{{ $familiar['color'] }}-200 transition-all bg-white"
                                        placeholder="N° DNI"
                                        data-tipo="{{ $familiar['id'] }}">
                                    <i class="fas fa-id-card text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                                </div>
                            </div>

                            <!-- Sexo -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Sexo</label>
                                <div class="relative">
                                    <select name="{{ $familiar['id'] }}_sexo"
                                        class="campo-familiar w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-{{ $familiar['color'] }}-500 focus:ring-2 focus:ring-{{ $familiar['color'] }}-200 transition-all appearance-none bg-white"
                                        data-tipo="{{ $familiar['id'] }}">
                                        <option value="">Seleccionar</option>
                                        <option value="M">Masculino</option>
                                        <option value="F">Femenino</option>
                                    </select>
                                    <i class="fas fa-venus-mars text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Ocupación -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ocupación</label>
                            <div class="relative">
                                <input type="text" name="{{ $familiar['id'] }}_ocupacion"
                                    class="campo-familiar w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-{{ $familiar['color'] }}-500 focus:ring-2 focus:ring-{{ $familiar['color'] }}-200 transition-all bg-white"
                                    placeholder="Profesión u oficio"
                                    data-tipo="{{ $familiar['id'] }}">
                                <i class="fas fa-briefcase text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                            </div>
                        </div>

                        <!-- Fecha Nacimiento -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Nacimiento</label>
                            <div class="relative">
                                <input type="text" 
                                       name="{{ $familiar['id'] }}_nacimiento"
                                       class="flatpickr-familia-mobile campo-familiar w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-{{ $familiar['color'] }}-500 focus:ring-2 focus:ring-{{ $familiar['color'] }}-200 transition-all bg-white"
                                       placeholder="Seleccione fecha"
                                       data-tipo="{{ $familiar['id'] }}">
                                <i class="fas fa-calendar text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                            </div>
                        </div>

                        <!-- Domicilio -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Domicilio Actual</label>
                            <div class="relative">
                                <input type="text" name="{{ $familiar['id'] }}_domicilio"
                                    class="campo-familiar w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-{{ $familiar['color'] }}-500 focus:ring-2 focus:ring-{{ $familiar['color'] }}-200 transition-all bg-white"
                                    placeholder="Dirección donde vive"
                                    data-tipo="{{ $familiar['id'] }}">
                                <i class="fas fa-home text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Sección de hijos (hasta 4 por defecto) -->
            @for ($i = 1; $i <= 4; $i++)
                @php
                    $hijoIndex = $i;
                    $isEven = $i % 2 === 0;
                @endphp
                
                <!-- Fila para desktop -->
                <div class="hidden md:grid md:grid-cols-7 items-center p-4 hover:bg-gray-50 transition-colors duration-200 {{ $isEven ? 'bg-gray-50' : 'bg-white' }}" data-hijo-index="{{ $hijoIndex }}">
                    <!-- Parentesco -->
                    <div class="px-4 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                                <i class="fas fa-child text-blue-600"></i>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-800 block">Hijo</span>
                                <span class="text-xs text-blue-600 font-medium bg-blue-50 px-2 py-1 rounded-full mt-1 inline-block">
                                    #{{ $hijoIndex }}
                                </span>
                                <span class="ml-2 text-xs text-gray-500">(opcional)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Apellidos y Nombres -->
                    <div class="px-4 py-4">
                        <div class="relative">
                            <input type="text" name="hijo{{ $hijoIndex }}_nombres"
                                class="campo-familiar w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-white"
                                placeholder="Nombre completo del hijo (opcional)"
                                data-tipo="hijo{{ $hijoIndex }}">
                        </div>
                    </div>

                    <!-- N° Documento -->
                    <div class="px-4 py-4">
                        <div class="relative">
                            <input type="text" name="hijo{{ $hijoIndex }}_documento"
                                class="campo-familiar w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-white"
                                placeholder="N° de documento"
                                data-tipo="hijo{{ $hijoIndex }}">
                        </div>
                    </div>

                    <!-- Ocupación -->
                    <div class="px-4 py-4">
                        <div class="relative">
                            <input type="text" name="hijo{{ $hijoIndex }}_ocupacion"
                                class="campo-familiar w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-white"
                                placeholder="Ocupación actual"
                                data-tipo="hijo{{ $hijoIndex }}">
                        </div>
                    </div>

                    <!-- Sexo -->
                    <div class="px-4 py-4">
                        <div class="relative">
                            <select name="hijo{{ $hijoIndex }}_sexo"
                                class="campo-familiar w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white"
                                data-tipo="hijo{{ $hijoIndex }}">
                                <option value="">Seleccione</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                            <i class="fas fa-venus-mars text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                        </div>
                    </div>

                    <!-- Fecha Nacimiento -->
                    <div class="px-4 py-4">
                        <div class="relative">
                            <input type="text" 
                                   name="hijo{{ $hijoIndex }}_nacimiento"
                                   class="flatpickr-familia campo-familiar w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-white"
                                   placeholder="Seleccione fecha"
                                   data-tipo="hijo{{ $hijoIndex }}">
                            <i class="fas fa-baby text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                        </div>
                    </div>

                    <!-- Domicilio Actual -->
                    <div class="px-4 py-4">
                        <div class="relative">
                            <input type="text" name="hijo{{ $hijoIndex }}_domicilio"
                                class="campo-familiar w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-white"
                                placeholder="Dirección actual"
                                data-tipo="hijo{{ $hijoIndex }}">
                        </div>
                    </div>
                </div>

                <!-- Tarjeta para móvil -->
                <div class="md:hidden bg-white border border-gray-200 rounded-xl p-5 mb-4 shadow-sm" data-hijo-index="{{ $hijoIndex }}">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                                <i class="fas fa-child text-blue-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Hijo #{{ $hijoIndex }}</h4>
                                <p class="text-sm text-gray-500">Familiar directo (opcional)</p>
                            </div>
                        </div>
                        @if($hijoIndex > 1)
                        <button type="button" class="remove-hijo-btn text-red-500 hover:text-red-700" data-index="{{ $hijoIndex }}">
                            <i class="fas fa-times"></i>
                        </button>
                        @endif
                    </div>

                    <div class="space-y-4">
                        <!-- Nombres -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Apellidos y Nombres</label>
                            <div class="relative">
                                <input type="text" name="hijo{{ $hijoIndex }}_nombres"
                                    class="campo-familiar w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-white"
                                    placeholder="Nombre completo (opcional)"
                                    data-tipo="hijo{{ $hijoIndex }}">
                                <i class="fas fa-user text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Documento -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">N° Documento</label>
                                <div class="relative">
                                    <input type="text" name="hijo{{ $hijoIndex }}_documento"
                                        class="campo-familiar w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-white"
                                        placeholder="N° DNI"
                                        data-tipo="hijo{{ $hijoIndex }}">
                                        <i class="fas fa-id-card text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                                </div>
                            </div>

                            <!-- Sexo -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Sexo</label>
                                <div class="relative">
                                    <select name="hijo{{ $hijoIndex }}_sexo"
                                        class="campo-familiar w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white"
                                        data-tipo="hijo{{ $hijoIndex }}">
                                        <option value="">Seleccionar</option>
                                        <option value="M">Masculino</option>
                                        <option value="F">Femenino</option>
                                    </select>
                                    <i class="fas fa-venus-mars text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Ocupación -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ocupación</label>
                            <div class="relative">
                                <input type="text" name="hijo{{ $hijoIndex }}_ocupacion"
                                    class="campo-familiar w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-white"
                                    placeholder="Profesión u oficio"
                                    data-tipo="hijo{{ $hijoIndex }}">
                                <i class="fas fa-briefcase text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                            </div>
                        </div>

                        <!-- Fecha Nacimiento -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Nacimiento</label>
                            <div class="relative">
                                <input type="text" 
                                       name="hijo{{ $hijoIndex }}_nacimiento"
                                       class="flatpickr-familia-mobile campo-familiar w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-white"
                                       placeholder="Seleccione fecha"
                                       data-tipo="hijo{{ $hijoIndex }}">
                                <i class="fas fa-calendar text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                            </div>
                        </div>

                        <!-- Domicilio -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Domicilio Actual</label>
                            <div class="relative">
                                <input type="text" name="hijo{{ $hijoIndex }}_domicilio"
                                    class="campo-familiar w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-white"
                                    placeholder="Dirección donde vive"
                                    data-tipo="hijo{{ $hijoIndex }}">
                                <i class="fas fa-home text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>

    <!-- Nota informativa -->
    <div class="mt-8 bg-gradient-to-r from-gray-50 to-white border border-gray-200 rounded-xl p-5">
        <div class="flex items-start">
            <div class="bg-green-100 rounded-lg p-3 mr-4">
                <i class="fas fa-check-circle text-green-600 text-lg"></i>
            </div>
            <div>
                <h4 class="font-medium text-gray-800 mb-1">Importante</h4>
                <p class="text-gray-600 text-sm">
                    Esta sección es <span class="font-bold text-green-600">COMPLETAMENTE OPCIONAL</span>. 
                    La barra de progreso mostrará 100% automáticamente si no tiene familiares directos. 
                    Complete solo si desea registrar información familiar.
                </p>
            </div>
        </div>
    </div>

    <!-- Indicador de progreso -->
    <div class="mt-6 pt-6 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                <span class="text-sm text-gray-600">Sección opcional - 100% si no tiene familiares</span>
            </div>
            <div class="flex items-center">
                <div class="w-32 bg-gray-200 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full transition-all duration-300" style="width: 100%" id="familia-progress"></div>
                </div>
                <span class="ml-3 text-sm font-medium text-gray-700" id="familia-percentage">100%</span>
            </div>
        </div>
    </div>
</div>

<script>
    // Inicializar Flatpickr para fechas de familiares
    document.addEventListener('DOMContentLoaded', function() {
        // Configuración para Flatpickr familiar
        const flatpickrFamiliaOptions = {
            locale: "es",
            dateFormat: "Y-m-d",
            altFormat: "d/m/Y",
            altInput: true,
            altInputClass: "flatpickr-alt-input",
            theme: "airbnb",
            maxDate: "today",
            disableMobile: false,
            allowInput: true,
            clickOpens: true,
            onValueUpdate: function(selectedDates, dateStr, instance) {
                calculateFamiliaProgress();
            }
        };

        // Inicializar Flatpickr para desktop
        document.querySelectorAll('.flatpickr-familia').forEach(function(element) {
            flatpickr(element, flatpickrFamiliaOptions);
        });

        // Inicializar Flatpickr para móvil
        document.querySelectorAll('.flatpickr-familia-mobile').forEach(function(element) {
            flatpickr(element, flatpickrFamiliaOptions);
        });

        // Variables para manejar hijos
        let currentHijoCount = 4;
        const maxHijos = 10;

        // Función para agregar hijo
        document.getElementById('add-hijo-btn').addEventListener('click', function() {
            if (currentHijoCount >= maxHijos) {
                alert(`Máximo ${maxHijos} hijos permitidos`);
                return;
            }

            currentHijoCount++;
            const hijoIndex = currentHijoCount;
            
            // Crear nueva fila para desktop
            const desktopRow = document.createElement('div');
            desktopRow.className = `hidden md:grid md:grid-cols-7 items-center p-4 hover:bg-gray-50 transition-colors duration-200 ${currentHijoCount % 2 === 0 ? 'bg-gray-50' : 'bg-white'}`;
            desktopRow.setAttribute('data-hijo-index', hijoIndex);
            desktopRow.innerHTML = `
                <!-- Parentesco -->
                <div class="px-4 py-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                            <i class="fas fa-child text-blue-600"></i>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-800 block">Hijo</span>
                            <span class="text-xs text-blue-600 font-medium bg-blue-50 px-2 py-1 rounded-full mt-1 inline-block">
                                #${hijoIndex}
                            </span>
                            <span class="ml-2 text-xs text-gray-500">(opcional)</span>
                        </div>
                    </div>
                </div>

                <!-- Apellidos y Nombres -->
                <div class="px-4 py-4">
                    <div class="relative">
                        <input type="text" name="hijo${hijoIndex}_nombres"
                            class="campo-familiar w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-white"
                            placeholder="Nombre completo del hijo (opcional)"
                            data-tipo="hijo${hijoIndex}">
                    </div>
                </div>

                <!-- N° Documento -->
                <div class="px-4 py-4">
                    <div class="relative">
                        <input type="text" name="hijo${hijoIndex}_documento"
                            class="campo-familiar w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-white"
                            placeholder="N° de documento"
                            data-tipo="hijo${hijoIndex}">
                    </div>
                </div>

                <!-- Ocupación -->
                <div class="px-4 py-4">
                    <div class="relative">
                        <input type="text" name="hijo${hijoIndex}_ocupacion"
                            class="campo-familiar w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-white"
                            placeholder="Ocupación actual"
                            data-tipo="hijo${hijoIndex}">
                    </div>
                </div>

                <!-- Sexo -->
                    <div class="px-4 py-4">
                        <div class="relative">
                            <select name="hijo${hijoIndex}_sexo"
                                class="campo-familiar w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white"
                                data-tipo="hijo${hijoIndex}">
                                <option value="">Seleccione</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                            <i class="fas fa-venus-mars text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                        </div>
                    </div>

                <!-- Fecha Nacimiento -->
                <div class="px-4 py-4">
                    <div class="relative">
                        <input type="text" 
                               name="hijo${hijoIndex}_nacimiento"
                               class="flatpickr-familia campo-familiar w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-white"
                               placeholder="Seleccione fecha"
                               data-tipo="hijo${hijoIndex}">
                        <i class="fas fa-baby text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                    </div>
                </div>

                <!-- Domicilio Actual -->
                <div class="px-4 py-4">
                    <div class="relative">
                        <input type="text" name="hijo${hijoIndex}_domicilio"
                            class="campo-familiar w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-white"
                            placeholder="Dirección actual"
                            data-tipo="hijo${hijoIndex}">
                    </div>
                </div>
            `;

            // Crear tarjeta para móvil
            const mobileCard = document.createElement('div');
            mobileCard.className = 'md:hidden bg-white border border-gray-200 rounded-xl p-5 mb-4 shadow-sm';
            mobileCard.setAttribute('data-hijo-index', hijoIndex);
            mobileCard.innerHTML = `
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                            <i class="fas fa-child text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800">Hijo #${hijoIndex}</h4>
                            <p class="text-sm text-gray-500">Familiar directo (opcional)</p>
                        </div>
                    </div>
                    <button type="button" class="remove-hijo-btn text-red-500 hover:text-red-700" data-index="${hijoIndex}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    <!-- Nombres -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Apellidos y Nombres</label>
                        <div class="relative">
                            <input type="text" name="hijo${hijoIndex}_nombres"
                                class="campo-familiar w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-white"
                                placeholder="Nombre completo (opcional)"
                                data-tipo="hijo${hijoIndex}">
                            <i class="fas fa-user text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Documento -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">N° Documento</label>
                            <div class="relative">
                                <input type="text" name="hijo${hijoIndex}_documento"
                                    class="campo-familiar w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-white"
                                    placeholder="N° DNI"
                                    data-tipo="hijo${hijoIndex}">
                                    <i class="fas fa-id-card text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                            </div>
                        </div>

                        <!-- Sexo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sexo</label>
                            <div class="relative">
                                <select name="hijo${hijoIndex}_sexo"
                                    class="campo-familiar w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white"
                                    data-tipo="hijo${hijoIndex}">
                                    <option value="">Seleccionar</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                </select>
                                <i class="fas fa-venus-mars text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Ocupación -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ocupación</label>
                        <div class="relative">
                            <input type="text" name="hijo${hijoIndex}_ocupacion"
                                class="campo-familiar w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-white"
                                placeholder="Profesión u oficio"
                                data-tipo="hijo${hijoIndex}">
                            <i class="fas fa-briefcase text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                        </div>
                    </div>

                    <!-- Fecha Nacimiento -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Nacimiento</label>
                        <div class="relative">
                            <input type="text" 
                                   name="hijo${hijoIndex}_nacimiento"
                                   class="flatpickr-familia-mobile campo-familiar w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-white"
                                   placeholder="Seleccione fecha"
                                   data-tipo="hijo${hijoIndex}">
                            <i class="fas fa-calendar text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                        </div>
                    </div>

                    <!-- Domicilio -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Domicilio Actual</label>
                        <div class="relative">
                            <input type="text" name="hijo${hijoIndex}_domicilio"
                                class="campo-familiar w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-white"
                                placeholder="Dirección donde vive"
                                data-tipo="hijo${hijoIndex}">
                            <i class="fas fa-home text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2"></i>
                        </div>
                    </div>
                </div>
            `;

            // Agregar al DOM
            const familiaContainer = document.getElementById('familia-container');
            familiaContainer.appendChild(desktopRow);
            familiaContainer.appendChild(mobileCard);

            // Inicializar Flatpickr para los nuevos campos
            flatpickr(mobileCard.querySelector('.flatpickr-familia-mobile'), flatpickrFamiliaOptions);
            flatpickr(desktopRow.querySelector('.flatpickr-familia'), flatpickrFamiliaOptions);

            // Agregar event listeners a los nuevos campos
            setupHijoEventListeners(hijoIndex);

            // Actualizar contador del botón si es necesario
            if (currentHijoCount >= maxHijos) {
                document.getElementById('add-hijo-btn').disabled = true;
                document.getElementById('add-hijo-btn').classList.add('opacity-50', 'cursor-not-allowed');
            }

            // Agregar listener para eliminar
            mobileCard.querySelector('.remove-hijo-btn').addEventListener('click', function() {
                removeHijo(hijoIndex);
            });

            calculateFamiliaProgress();
        });

        // Función para eliminar hijo
        function removeHijo(index) {
            if (index <= 4) {
                alert('No puede eliminar los primeros 4 hijos. Puede dejarlos en blanco si no los necesita.');
                return;
            }

            if (confirm('¿Está seguro de eliminar este hijo?')) {
                // Eliminar filas de desktop y mobile
                const desktopRow = document.querySelector(`[data-hijo-index="${index}"]`);
                const mobileCard = document.querySelector(`.md:hidden[data-hijo-index="${index}"]`);
                
                if (desktopRow) desktopRow.remove();
                if (mobileCard) mobileCard.remove();
                
                currentHijoCount--;
                
                // Habilitar botón de agregar si estaba deshabilitado
                if (currentHijoCount < maxHijos) {
                    document.getElementById('add-hijo-btn').disabled = false;
                    document.getElementById('add-hijo-btn').classList.remove('opacity-50', 'cursor-not-allowed');
                }
                
                calculateFamiliaProgress();
            }
        }

        // Agregar event listeners a los botones de eliminar existentes
        document.querySelectorAll('.remove-hijo-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const index = this.getAttribute('data-index');
                removeHijo(parseInt(index));
            });
        });

        // Función para calcular progreso de sección familiar (NUEVA LÓGICA)
        function calculateFamiliaProgress() {
            const camposFamiliares = document.querySelectorAll('.campo-familiar[data-tipo]');
            
            // Agrupar campos por tipo de familiar
            const familias = {};
            
            camposFamiliares.forEach(campo => {
                const tipo = campo.getAttribute('data-tipo');
                if (!familias[tipo]) {
                    familias[tipo] = [];
                }
                familias[tipo].push(campo);
            });
            
            let familiasCompletas = 0;
            let totalFamiliasConsideradas = 0;
            
            // Verificar cada tipo de familiar
            Object.keys(familias).forEach(tipo => {
                const campos = familias[tipo];
                let tieneDatos = false;
                
                // Verificar si ALGÚN campo de este familiar tiene datos
                campos.forEach(campo => {
                    if (campo.value && campo.value.trim() !== '') {
                        tieneDatos = true;
                    }
                });
                
                // Si el familiar tiene datos, considerar si está completo
                if (tieneDatos) {
                    totalFamiliasConsideradas++;
                    
                    // Contar campos completados para este familiar
                    let camposCompletados = 0;
                    campos.forEach(campo => {
                        if (campo.value && campo.value.trim() !== '') {
                            camposCompletados++;
                        }
                    });
                    
                    // Si tiene al menos 3 campos completados (nombre, documento, algo más), considerar completo
                    if (camposCompletados >= 3) {
                        familiasCompletas++;
                    }
                }
            });
            
            // NUEVA LÓGICA: Si no hay familias con datos, se considera 100%
            let porcentaje;
            if (totalFamiliasConsideradas === 0) {
                porcentaje = 100; // No hay familiares, sección opcional completada
            } else {
                // Si hay familiares, calcular porcentaje basado en cuántos están completos
                porcentaje = Math.round((familiasCompletas / totalFamiliasConsideradas) * 100);
                
                // Si el usuario empezó a llenar pero no completó, dar algo de progreso
                if (familiasCompletas === 0 && totalFamiliasConsideradas > 0) {
                    porcentaje = 25; // Al menos empezó a llenar
                }
            }
            
            // Asegurar que el porcentaje esté entre 0 y 100
            porcentaje = Math.min(100, Math.max(0, porcentaje));
            
            // Actualizar la barra de progreso
            document.getElementById('familia-percentage').textContent = `${porcentaje}%`;
            document.getElementById('familia-progress').style.width = `${porcentaje}%`;
            
            // Cambiar color de la barra
            const progressBar = document.getElementById('familia-progress');
            progressBar.classList.remove('bg-green-500', 'bg-yellow-500', 'bg-red-500', 'bg-green-400', 'bg-yellow-400', 'bg-gray-300');
            
            if (porcentaje === 100) {
                progressBar.classList.add('bg-green-500');
                document.querySelector('#familia-progress + .ml-3').textContent = '100%';
            } else if (porcentaje >= 75) {
                progressBar.classList.add('bg-green-400');
            } else if (porcentaje >= 50) {
                progressBar.classList.add('bg-yellow-500');
            } else if (porcentaje >= 25) {
                progressBar.classList.add('bg-yellow-400');
            } else {
                progressBar.classList.add('bg-red-500');
            }
        }

        // Agregar event listeners a todos los campos familiares
        function setupEventListeners() {
            document.querySelectorAll('.campo-familiar').forEach(field => {
                field.addEventListener('input', calculateFamiliaProgress);
                field.addEventListener('change', calculateFamiliaProgress);
            });
        }

        // Inicializar event listeners para hijos existentes
        function setupHijoEventListeners(index) {
            const fields = [
                `hijo${index}_nombres`,
                `hijo${index}_documento`,
                `hijo${index}_ocupacion`,
                `hijo${index}_sexo`,
                `hijo${index}_nacimiento`,
                `hijo${index}_domicilio`
            ];
            
            fields.forEach(fieldName => {
                const field = document.querySelector(`[name="${fieldName}"]`);
                if (field) {
                    field.addEventListener('input', calculateFamiliaProgress);
                    field.addEventListener('change', calculateFamiliaProgress);
                }
            });
        }

        // Configurar event listeners para todos los campos existentes
        for (let i = 1; i <= 4; i++) {
            setupHijoEventListeners(i);
        }

        // Agregar listeners a los campos de cónyuge y concubino
        const familiaresBase = ['conyuge', 'concubino'];
        familiaresBase.forEach(tipo => {
            const campos = document.querySelectorAll(`[data-tipo="${tipo}"]`);
            campos.forEach(campo => {
                campo.addEventListener('input', calculateFamiliaProgress);
                campo.addEventListener('change', calculateFamiliaProgress);
            });
        });

        // Inicializar el progreso
        setupEventListeners();
        calculateFamiliaProgress();
    });
</script>

<style>
.campo-familiar {
    transition: all 0.3s ease;
}

#familia-progress {
    transition: width 0.5s ease-in-out, background-color 0.5s ease;
}

/* Estilos para indicar que es opcional */
.text-gray-500 {
    font-size: 0.75rem;
}
</style>