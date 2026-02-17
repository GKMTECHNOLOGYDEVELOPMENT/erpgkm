<template x-if="tab === 'info-salud'">
    <div class="space-y-6">
        <!-- ============================================ -->
        <!-- SECCIÓN 1: INFORMACIÓN FAMILIAR -->
        <!-- ============================================ -->
        <div class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-4 bg-white dark:bg-[#0e1726]">
            <div class="flex items-center mb-4">
                <div class="w-1 h-6 bg-info rounded-full mr-3"></div>
                <h6 class="text-lg font-bold text-gray-800 dark:text-white">INFORMACIÓN FAMILIAR</h6>
            </div>
            
            <!-- Botón agregar -->
            <div class="flex justify-end mb-4">
                <button type="button" id="add-familiar-btn" class="btn bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-black border-none px-4 py-2 flex items-center gap-2 rounded-lg">
                    <i class="fas fa-plus"></i>
                    Agregar Familiar
                </button>
            </div>

            <!-- Tabla de familiares -->
            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="w-full text-sm">
                    <thead class="bg-pink-50 dark:bg-pink-900/20 border-b border-pink-200 dark:border-pink-900/30">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-pink-800 dark:text-pink-300 uppercase">Parentesco</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-pink-800 dark:text-pink-300 uppercase">Apellidos y Nombres</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-pink-800 dark:text-pink-300 uppercase">N° Documento</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-pink-800 dark:text-pink-300 uppercase">Ocupación</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-pink-800 dark:text-pink-300 uppercase">Sexo</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-pink-800 dark:text-pink-300 uppercase">Fecha Nac.</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-pink-800 dark:text-pink-300 uppercase">Domicilio</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-pink-800 dark:text-pink-300 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="familiares-tbody" class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($familiares as $familiar)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors" data-id="{{ $familiar->idFamiliar }}">
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $familiar->parentesco ?? 'N/A' }}</td>
                            <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-200">{{ $familiar->apellidosNombres ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $familiar->nroDocumento ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $familiar->ocupacion ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $familiar->sexo ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $familiar->fechaNacimiento ? date('d/m/Y', strtotime($familiar->fechaNacimiento)) : 'N/A' }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $familiar->domicilioActual ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center items-center gap-2">
                                    <button class="text-blue-600 hover:text-blue-800 edit-familiar" data-id="{{ $familiar->idFamiliar }}" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-600 hover:text-red-800 delete-familiar" data-id="{{ $familiar->idFamiliar }}" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr class="empty-row">
                            <td colspan="8" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                <i class="fas fa-users text-pink-500 text-2xl mb-2"></i>
                                <p>No hay familiares agregados</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <p class="text-xs text-gray-500 mt-3 flex items-center gap-1">
                <i class="fas fa-info-circle text-pink-500"></i>
                Esta sección es opcional. Puede agregar sus familiares directos.
            </p>
        </div>

        <!-- ============================================ -->
        <!-- SECCIÓN 2: INFORMACIÓN DE SALUD -->
        <!-- ============================================ -->
        <div class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-4 bg-white dark:bg-[#0e1726]">
            <div class="flex items-center mb-4">
                <div class="w-1 h-6 bg-red-500 rounded-full mr-3"></div>
                <h6 class="text-lg font-bold text-gray-800 dark:text-white">INFORMACIÓN DE SALUD</h6>
            </div>

            <!-- Vacuna COVID-19 -->
            <div class="mb-5 pb-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-6 h-6 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                        <i class="fas fa-syringe text-red-600 dark:text-red-400 text-xs"></i>
                    </div>
                    <span class="font-semibold text-gray-800 dark:text-gray-200">Vacuna COVID-19</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 block mb-1">¿Ha recibido la vacuna?</label>
                        <div class="flex gap-4">
                            @php
                                $vacunaCovid = $salud->vacunaCovid ?? null;
                            @endphp
                            <label class="flex items-center gap-1">
                                <input type="radio" name="vacuna_covid" value="1" class="w-3.5 h-3.5 text-red-600 vacuna-radio" 
                                    {{ $vacunaCovid === true ? 'checked' : '' }}> Sí
                            </label>
                            <label class="flex items-center gap-1">
                                <input type="radio" name="vacuna_covid" value="0" class="w-3.5 h-3.5 text-red-600 vacuna-radio" 
                                    {{ $vacunaCovid === false ? 'checked' : '' }}> No
                            </label>
                        </div>
                    </div>
                    
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 block mb-1">1° Dosis</label>
                        <div class="relative">
                            <input type="text" id="covid_dosis1" name="covid_dosis1" class="flatpickr form-input w-full text-sm campo-vacuna" 
                                placeholder="Fecha" value="{{ $covidDosis1 ?? '' }}"
                                {{ $vacunaCovid === false ? 'disabled' : '' }}>
                            <i class="fas fa-calendar absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        </div>
                    </div>
                    
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 block mb-1">2° Dosis</label>
                        <div class="relative">
                            <input type="text" id="covid_dosis2" name="covid_dosis2" class="flatpickr form-input w-full text-sm campo-vacuna" 
                                placeholder="Fecha" value="{{ $covidDosis2 ?? '' }}"
                                {{ $vacunaCovid === false ? 'disabled' : '' }}>
                            <i class="fas fa-calendar absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        </div>
                    </div>
                    
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 block mb-1">3° Dosis</label>
                        <div class="relative">
                            <input type="text" id="covid_dosis3" name="covid_dosis3" class="flatpickr form-input w-full text-sm campo-vacuna" 
                                placeholder="Fecha" value="{{ $covidDosis3 ?? '' }}"
                                {{ $vacunaCovid === false ? 'disabled' : '' }}>
                            <i class="fas fa-calendar absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dolencias Crónicas -->
            <div class="mb-5 pb-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-6 h-6 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center">
                        <i class="fas fa-stethoscope text-orange-600 dark:text-orange-400 text-xs"></i>
                    </div>
                    <span class="font-semibold text-gray-800 dark:text-gray-200">Dolencias Crónicas</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 block mb-1">¿Padece alguna dolencia crónica?</label>
                        <div class="flex gap-4">
                            @php
                                $dolenciaCronica = $salud->dolenciaCronica ?? null;
                            @endphp
                            <label class="flex items-center gap-1">
                                <input type="radio" name="dolencia_cronica" value="1" class="w-3.5 h-3.5 text-orange-600 dolencia-radio" 
                                    {{ $dolenciaCronica === true ? 'checked' : '' }}> Sí
                            </label>
                            <label class="flex items-center gap-1">
                                <input type="radio" name="dolencia_cronica" value="0" class="w-3.5 h-3.5 text-orange-600 dolencia-radio" 
                                    {{ $dolenciaCronica === false ? 'checked' : '' }}> No
                            </label>
                        </div>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 block mb-1">Especificar</label>
                        <input type="text" name="dolencia_detalle" id="dolencia_detalle" class="form-input w-full text-sm campo-dolencia" 
                            placeholder="Ej: Diabetes, Hipertensión, Asma"
                            value="{{ $salud->dolenciaDetalle ?? '' }}"
                            {{ $dolenciaCronica === false ? 'disabled' : '' }}>
                    </div>
                </div>
            </div>

            <!-- Discapacidad -->
            <div class="mb-5 pb-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-6 h-6 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                        <i class="fas fa-wheelchair text-blue-600 dark:text-blue-400 text-xs"></i>
                    </div>
                    <span class="font-semibold text-gray-800 dark:text-gray-200">Discapacidad</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 block mb-1">¿Padece alguna discapacidad?</label>
                        <div class="flex gap-4">
                            @php
                                $discapacidad = $salud->discapacidad ?? null;
                            @endphp
                            <label class="flex items-center gap-1">
                                <input type="radio" name="discapacidad" value="1" class="w-3.5 h-3.5 text-blue-600 discapacidad-radio" 
                                    {{ $discapacidad === true ? 'checked' : '' }}> Sí
                            </label>
                            <label class="flex items-center gap-1">
                                <input type="radio" name="discapacidad" value="0" class="w-3.5 h-3.5 text-blue-600 discapacidad-radio" 
                                    {{ $discapacidad === false ? 'checked' : '' }}> No
                            </label>
                        </div>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 block mb-1">Especificar</label>
                        <input type="text" name="discapacidad_detalle" id="discapacidad_detalle" class="form-input w-full text-sm campo-discapacidad" 
                            placeholder="Ej: Visual, Motora, Auditiva"
                            value="{{ $salud->discapacidadDetalle ?? '' }}"
                            {{ $discapacidad === false ? 'disabled' : '' }}>
                    </div>
                </div>
            </div>

            <!-- Tipo de Sangre -->
            <div class="mb-5 pb-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-6 h-6 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                        <i class="fas fa-tint text-red-600 dark:text-red-400 text-xs"></i>
                    </div>
                    <span class="font-semibold text-gray-800 dark:text-gray-200">Tipo de Sangre</span>
                </div>
                
                @php $tipoSangre = $salud->tipoSangre ?? ''; @endphp
                <div class="grid grid-cols-4 sm:grid-cols-8 gap-2 max-w-2xl">
                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $sangre)
                    <label class="flex flex-col items-center p-2 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 cursor-pointer">
                        <input type="radio" name="tipo_sangre" value="{{ $sangre }}" class="w-3.5 h-3.5 text-red-600 mb-1" {{ $tipoSangre == $sangre ? 'checked' : '' }}>
                        <span class="text-xs font-medium">{{ $sangre }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Contactos de Emergencia -->
            <div class="mt-6">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-6 h-6 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center">
                        <i class="fas fa-phone-alt text-amber-600 dark:text-amber-400 text-xs"></i>
                    </div>
                    <span class="font-semibold text-gray-800 dark:text-gray-200">Contactos de Emergencia</span>
                </div>
                
                <!-- Botón agregar contacto -->
                <div class="flex justify-end mb-4">
                    <button type="button" id="add-contacto-btn" class="btn bg-gradient-to-r from-amber-500 to-yellow-500 hover:from-amber-600 hover:to-yellow-600 text-white border-none px-4 py-2 flex items-center gap-2 rounded-lg">
                        <i class="fas fa-plus"></i>
                        Agregar Contacto de Emergencia
                    </button>
                </div>

                <!-- Lista de contactos de emergencia -->
                <div id="contactos-container" class="space-y-3">
                    @forelse($contactosEmergencia as $contacto)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-white dark:bg-[#0e1726] border border-gray-200 dark:border-gray-700 rounded-lg relative group" data-id="{{ $contacto->idContacto }}">
                        <button class="absolute top-2 right-2 text-gray-400 hover:text-red-600 delete-contacto" data-id="{{ $contacto->idContacto }}" title="Eliminar">
                            <i class="fas fa-times"></i>
                        </button>
                        <div>
                            <label class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1 block">Nombre Completo</label>
                            <input type="text" class="form-input w-full text-sm contacto-nombre" value="{{ $contacto->apellidosNombres }}" placeholder="Nombre completo" data-id="{{ $contacto->idContacto }}">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1 block">Parentesco</label>
                            <input type="text" class="form-input w-full text-sm contacto-parentesco" value="{{ $contacto->parentesco }}" placeholder="Ej: Padre, Madre" data-id="{{ $contacto->idContacto }}">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1 block">Teléfono / Dirección</label>
                            <input type="text" class="form-input w-full text-sm contacto-direccion" value="{{ $contacto->direccionTelefono }}" placeholder="Ej: 987654321 - Calle 123" data-id="{{ $contacto->idContacto }}">
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-6 text-gray-500 dark:text-gray-400 empty-contactos">
                        <i class="fas fa-phone-alt text-amber-500 text-2xl mb-2"></i>
                        <p>No hay contactos de emergencia agregados</p>
                    </div>
                    @endforelse
                </div>
                
                <p class="text-xs text-gray-500 mt-4 flex items-center gap-1">
                    <i class="fas fa-shield-alt text-amber-500"></i>
                    Información confidencial, solo para emergencias médicas
                </p>
            </div>

            <!-- Botones de acción -->
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" id="guardar-salud-btn" class="btn bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white border-none px-6 py-2 flex items-center gap-2 rounded-lg">
                    <i class="fas fa-save"></i>
                    Guardar Información de Salud
                </button>
            </div>

            <p class="text-xs text-gray-500 mt-4 flex items-center gap-1">
                <i class="fas fa-shield-alt text-red-500"></i>
                Información confidencial, protegida por ley de protección de datos
            </p>
        </div>
    </div>
</template>