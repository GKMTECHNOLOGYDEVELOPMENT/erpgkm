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
                <button type="button" id="add-familiar-btn" class="btn bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white border-none px-4 py-2 flex items-center gap-2 rounded-lg">
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
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                <i class="fas fa-users text-pink-500 text-2xl mb-2"></i>
                                <p>No hay familiares agregados</p>
                            </td>
                        </tr>
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
                            <label class="flex items-center gap-1">
                                <input type="radio" name="vacuna_covid" class="w-3.5 h-3.5 text-red-600"> Sí
                            </label>
                            <label class="flex items-center gap-1">
                                <input type="radio" name="vacuna_covid" class="w-3.5 h-3.5 text-red-600"> No
                            </label>
                        </div>
                    </div>
                    
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 block mb-1">1° Dosis</label>
                        <div class="relative">
                            <input type="text" class="flatpickr form-input w-full text-sm" placeholder="Fecha">
                            <i class="fas fa-calendar absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        </div>
                    </div>
                    
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 block mb-1">2° Dosis</label>
                        <div class="relative">
                            <input type="text" class="flatpickr form-input w-full text-sm" placeholder="Fecha">
                            <i class="fas fa-calendar absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        </div>
                    </div>
                    
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 block mb-1">3° Dosis</label>
                        <div class="relative">
                            <input type="text" class="flatpickr form-input w-full text-sm" placeholder="Fecha">
                            <i class="fas fa-calendar absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Operaciones Quirúrgicas -->
            <div class="mb-5 pb-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-6 h-6 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center">
                        <i class="fas fa-procedures text-purple-600 dark:text-purple-400 text-xs"></i>
                    </div>
                    <span class="font-semibold text-gray-800 dark:text-gray-200">Operaciones Quirúrgicas</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 block mb-1">¿Ha tenido alguna operación?</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-1">
                                <input type="radio" name="tiene_operacion" class="w-3.5 h-3.5 text-purple-600"> Sí
                            </label>
                            <label class="flex items-center gap-1">
                                <input type="radio" name="tiene_operacion" class="w-3.5 h-3.5 text-purple-600"> No
                            </label>
                        </div>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 block mb-1">Especificar</label>
                        <input type="text" class="form-input w-full text-sm" placeholder="Ej: Apendicectomía, Cesárea">
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
                            <label class="flex items-center gap-1">
                                <input type="radio" name="dolencia_cronica" class="w-3.5 h-3.5 text-orange-600"> Sí
                            </label>
                            <label class="flex items-center gap-1">
                                <input type="radio" name="dolencia_cronica" class="w-3.5 h-3.5 text-orange-600"> No
                            </label>
                        </div>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 block mb-1">Especificar</label>
                        <input type="text" class="form-input w-full text-sm" placeholder="Ej: Diabetes, Hipertensión, Asma">
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
                            <label class="flex items-center gap-1">
                                <input type="radio" name="discapacidad" class="w-3.5 h-3.5 text-blue-600"> Sí
                            </label>
                            <label class="flex items-center gap-1">
                                <input type="radio" name="discapacidad" class="w-3.5 h-3.5 text-blue-600"> No
                            </label>
                        </div>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400 block mb-1">Especificar</label>
                        <input type="text" class="form-input w-full text-sm" placeholder="Ej: Visual, Motora, Auditiva">
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
                
                <div class="grid grid-cols-4 sm:grid-cols-8 gap-2 max-w-2xl">
                    <label class="flex flex-col items-center p-2 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 cursor-pointer">
                        <input type="radio" name="tipo_sangre" class="w-3.5 h-3.5 text-red-600 mb-1">
                        <span class="text-xs font-medium">A+</span>
                    </label>
                    <label class="flex flex-col items-center p-2 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 cursor-pointer">
                        <input type="radio" name="tipo_sangre" class="w-3.5 h-3.5 text-red-600 mb-1">
                        <span class="text-xs font-medium">A-</span>
                    </label>
                    <label class="flex flex-col items-center p-2 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 cursor-pointer">
                        <input type="radio" name="tipo_sangre" class="w-3.5 h-3.5 text-red-600 mb-1">
                        <span class="text-xs font-medium">B+</span>
                    </label>
                    <label class="flex flex-col items-center p-2 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 cursor-pointer">
                        <input type="radio" name="tipo_sangre" class="w-3.5 h-3.5 text-red-600 mb-1">
                        <span class="text-xs font-medium">B-</span>
                    </label>
                    <label class="flex flex-col items-center p-2 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 cursor-pointer">
                        <input type="radio" name="tipo_sangre" class="w-3.5 h-3.5 text-red-600 mb-1">
                        <span class="text-xs font-medium">AB+</span>
                    </label>
                    <label class="flex flex-col items-center p-2 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 cursor-pointer">
                        <input type="radio" name="tipo_sangre" class="w-3.5 h-3.5 text-red-600 mb-1">
                        <span class="text-xs font-medium">AB-</span>
                    </label>
                    <label class="flex flex-col items-center p-2 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 cursor-pointer">
                        <input type="radio" name="tipo_sangre" class="w-3.5 h-3.5 text-red-600 mb-1">
                        <span class="text-xs font-medium">O+</span>
                    </label>
                    <label class="flex flex-col items-center p-2 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 cursor-pointer">
                        <input type="radio" name="tipo_sangre" class="w-3.5 h-3.5 text-red-600 mb-1">
                        <span class="text-xs font-medium">O-</span>
                    </label>
                </div>
            </div>

            <!-- Contactos de Emergencia -->
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-6 h-6 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center">
                        <i class="fas fa-phone-alt text-amber-600 dark:text-amber-400 text-xs"></i>
                    </div>
                    <span class="font-semibold text-gray-800 dark:text-gray-200">Contactos de Emergencia</span>
                </div>
                
                <!-- Contacto 1 -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-white dark:bg-[#0e1726] border border-gray-200 dark:border-gray-700 rounded-lg mb-3">
                    <div>
                        <label class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1 block">Contacto #1</label>
                        <input type="text" class="form-input w-full text-sm" placeholder="Nombre completo">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1 block">Parentesco</label>
                        <input type="text" class="form-input w-full text-sm" placeholder="Ej: Padre, Madre">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1 block">Teléfono / Dirección</label>
                        <input type="text" class="form-input w-full text-sm" placeholder="Ej: 987654321 - Calle 123">
                    </div>
                </div>

                <!-- Contacto 2 -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-gray-50 dark:bg-[#1a1f2e] border border-gray-200 dark:border-gray-700 rounded-lg">
                    <div>
                        <label class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1 block">Contacto #2</label>
                        <input type="text" class="form-input w-full text-sm" placeholder="Nombre completo">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1 block">Parentesco</label>
                        <input type="text" class="form-input w-full text-sm" placeholder="Ej: Esposo/a, Hijo/a">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1 block">Teléfono / Dirección</label>
                        <input type="text" class="form-input w-full text-sm" placeholder="Ej: 912345678 - Av. Principal">
                    </div>
                </div>

                <div class="flex justify-end mt-3">
                    <button type="button" id="add-contacto-btn" class="btn btn-outline-secondary btn-sm px-4 py-2 flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        Agregar contacto
                    </button>
                </div>
                
                <p class="text-xs text-gray-500 mt-4 flex items-center gap-1">
                    <i class="fas fa-shield-alt text-amber-500"></i>
                    Información confidencial, solo para emergencias médicas
                </p>
            </div>
        </div>
    </div>
</template>