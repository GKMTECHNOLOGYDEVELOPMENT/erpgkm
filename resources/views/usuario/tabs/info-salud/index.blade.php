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
                        @forelse($familiares as $familiar)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
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
                        <tr>
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
                    <input type="radio" name="vacuna_covid" value="1" class="w-3.5 h-3.5 text-red-600" 
                        {{ $vacunaCovid === true ? 'checked' : '' }}> Sí
                </label>
                <label class="flex items-center gap-1">
                    <input type="radio" name="vacuna_covid" value="0" class="w-3.5 h-3.5 text-red-600" 
                        {{ $vacunaCovid === false ? 'checked' : '' }}> No
                </label>
            </div>
        </div>
        
        <div>
            <label class="text-xs font-medium text-gray-600 dark:text-gray-400 block mb-1">1° Dosis</label>
            <div class="relative">
                <input type="text" id="covid_dosis1" name="covid_dosis1" class="flatpickr form-input w-full text-sm" 
                    placeholder="Fecha" value="{{ $covidDosis1 ?? '' }}">
                <i class="fas fa-calendar absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            </div>
        </div>
        
        <div>
            <label class="text-xs font-medium text-gray-600 dark:text-gray-400 block mb-1">2° Dosis</label>
            <div class="relative">
                <input type="text" id="covid_dosis2" name="covid_dosis2" class="flatpickr form-input w-full text-sm" 
                    placeholder="Fecha" value="{{ $covidDosis2 ?? '' }}">
                <i class="fas fa-calendar absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            </div>
        </div>
        
        <div>
            <label class="text-xs font-medium text-gray-600 dark:text-gray-400 block mb-1">3° Dosis</label>
            <div class="relative">
                <input type="text" id="covid_dosis3" name="covid_dosis3" class="flatpickr form-input w-full text-sm" 
                    placeholder="Fecha" value="{{ $covidDosis3 ?? '' }}">
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
                    <input type="radio" name="dolencia_cronica" value="1" class="w-3.5 h-3.5 text-orange-600" 
                        {{ $dolenciaCronica === true ? 'checked' : '' }}> Sí
                </label>
                <label class="flex items-center gap-1">
                    <input type="radio" name="dolencia_cronica" value="0" class="w-3.5 h-3.5 text-orange-600" 
                        {{ $dolenciaCronica === false ? 'checked' : '' }}> No
                </label>
            </div>
        </div>
        
        <div class="md:col-span-2">
            <label class="text-xs font-medium text-gray-600 dark:text-gray-400 block mb-1">Especificar</label>
            <input type="text" name="dolencia_detalle" class="form-input w-full text-sm" 
                placeholder="Ej: Diabetes, Hipertensión, Asma"
                value="{{ $salud->dolenciaDetalle ?? '' }}">
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
                    <input type="radio" name="discapacidad" value="1" class="w-3.5 h-3.5 text-blue-600" 
                        {{ $discapacidad === true ? 'checked' : '' }}> Sí
                </label>
                <label class="flex items-center gap-1">
                    <input type="radio" name="discapacidad" value="0" class="w-3.5 h-3.5 text-blue-600" 
                        {{ $discapacidad === false ? 'checked' : '' }}> No
                </label>
            </div>
        </div>
        
        <div class="md:col-span-2">
            <label class="text-xs font-medium text-gray-600 dark:text-gray-400 block mb-1">Especificar</label>
            <input type="text" name="discapacidad_detalle" class="form-input w-full text-sm" 
                placeholder="Ej: Visual, Motora, Auditiva"
                value="{{ $salud->discapacidadDetalle ?? '' }}">
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
                <div class="space-y-3">
                    @forelse($contactosEmergencia as $contacto)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-white dark:bg-[#0e1726] border border-gray-200 dark:border-gray-700 rounded-lg relative group">
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
                    <div class="text-center py-6 text-gray-500 dark:text-gray-400">
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
                <button type="button" id="guardar-salud-btn" class="btn bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white border-none px-6 py-2 rounded-lg flex items-center gap-2">
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

<script>
    $(document).ready(function() {
        // ============================================
        // INICIALIZAR FLATPICKR PARA FECHAS COVID
        // ============================================
        if (typeof flatpickr !== 'undefined') {
            flatpickr(".flatpickr", {
                dateFormat: "Y-m-d",
                allowInput: true,
                locale: "es"
            });
        }

        // ============================================
        // GUARDAR INFORMACIÓN DE SALUD
        // ============================================
        $('#guardar-salud-btn').click(function() {
            let userId = {{ $usuario->idUsuario }};
            let csrfToken = $('meta[name="csrf-token"]').attr('content');
            
            let data = {
                vacuna_covid: $('input[name="vacuna_covid"]:checked').val(),
                covid_dosis1: $('#covid_dosis1').val(),
                covid_dosis2: $('#covid_dosis2').val(),
                covid_dosis3: $('#covid_dosis3').val(),
                dolencia_cronica: $('input[name="dolencia_cronica"]:checked').val(),
                dolencia_detalle: $('input[name="dolencia_detalle"]').val(),
                discapacidad: $('input[name="discapacidad"]:checked').val(),
                discapacidad_detalle: $('input[name="discapacidad_detalle"]').val(),
                tipo_sangre: $('input[name="tipo_sangre"]:checked').val()
            };
            
            $.ajax({
                url: '/usuario/' + userId + '/salud/guardar',
                type: 'POST',
                data: JSON.stringify(data),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success('Información de salud guardada correctamente');
                    } else {
                        toastr.error('Error al guardar la información');
                    }
                },
                error: function(xhr) {
                    toastr.error('Error al guardar la información de salud');
                }
            });
        });

        // ============================================
        // AGREGAR FAMILIAR
        // ============================================
        $('#add-familiar-btn').click(function() {
            Swal.fire({
                title: 'Agregar Familiar',
                html: `
                    <form id="familiar-form" class="space-y-3">
                        <select class="swal2-input" name="parentesco" required>
                            <option value="">Seleccionar parentesco</option>
                            <option value="CONYUGE">Cónyuge</option>
                            <option value="CONCUBINO">Concubino/a</option>
                            <option value="HIJO">Hijo/a</option>
                        </select>
                        <input type="text" class="swal2-input" name="apellidosNombres" placeholder="Apellidos y Nombres" required>
                        <input type="text" class="swal2-input" name="nroDocumento" placeholder="N° Documento">
                        <input type="text" class="swal2-input" name="ocupacion" placeholder="Ocupación">
                        <select class="swal2-input" name="sexo">
                            <option value="">Seleccionar sexo</option>
                            <option value="MASCULINO">Masculino</option>
                            <option value="FEMENINO">Femenino</option>
                        </select>
                        <input type="text" class="swal2-input flatpickr" name="fechaNacimiento" placeholder="Fecha Nacimiento">
                        <input type="text" class="swal2-input" name="domicilioActual" placeholder="Domicilio Actual">
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                width: '600px',
                didOpen: () => {
                    if (typeof flatpickr !== 'undefined') {
                        flatpickr(".flatpickr", {
                            dateFormat: "Y-m-d",
                            locale: "es"
                        });
                    }
                },
                preConfirm: () => {
                    let form = document.getElementById('familiar-form');
                    let formData = new FormData(form);
                    let data = Object.fromEntries(formData);
                    
                    if (!data.parentesco) {
                        Swal.showValidationMessage('Debe seleccionar un parentesco');
                        return false;
                    }
                    if (!data.apellidosNombres) {
                        Swal.showValidationMessage('Debe ingresar apellidos y nombres');
                        return false;
                    }
                    return data;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    let data = result.value;
                    data.idUsuario = {{ $usuario->idUsuario }};
                    
                    $.ajax({
                        url: '/usuario/familiar/guardar',
                        type: 'POST',
                        data: JSON.stringify(data),
                        contentType: 'application/json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success('Familiar agregado correctamente');
                                setTimeout(() => location.reload(), 1500);
                            } else {
                                toastr.error('Error al agregar familiar');
                            }
                        },
                        error: function() {
                            toastr.error('Error al agregar familiar');
                        }
                    });
                }
            });
        });

        // ============================================
        // ELIMINAR FAMILIAR
        // ============================================
        $(document).on('click', '.delete-familiar', function() {
            let id = $(this).data('id');
            Swal.fire({
                title: '¿Eliminar familiar?',
                text: 'Esta acción no se puede revertir',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/usuario/familiar/' + id,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success('Familiar eliminado');
                                setTimeout(() => location.reload(), 1500);
                            } else {
                                toastr.error('Error al eliminar familiar');
                            }
                        },
                        error: function() {
                            toastr.error('Error al eliminar familiar');
                        }
                    });
                }
            });
        });

        // ============================================
        // EDITAR FAMILIAR
        // ============================================
        $(document).on('click', '.edit-familiar', function() {
            let id = $(this).data('id');
            
            $.ajax({
                url: '/usuario/familiar/' + id,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        let familiar = response.familiar;
                        
                        Swal.fire({
                            title: 'Editar Familiar',
                            html: `
                                <form id="familiar-edit-form" class="space-y-3">
                                    <input type="hidden" name="idFamiliar" value="${familiar.idFamiliar}">
                                    <select class="swal2-input" name="parentesco" required>
                                        <option value="">Seleccionar parentesco</option>
                                        <option value="CONYUGE" ${familiar.parentesco == 'CONYUGE' ? 'selected' : ''}>Cónyuge</option>
                                        <option value="CONCUBINO" ${familiar.parentesco == 'CONCUBINO' ? 'selected' : ''}>Concubino/a</option>
                                        <option value="HIJO" ${familiar.parentesco == 'HIJO' ? 'selected' : ''}>Hijo/a</option>
                                    </select>
                                    <input type="text" class="swal2-input" name="apellidosNombres" value="${familiar.apellidosNombres || ''}" placeholder="Apellidos y Nombres" required>
                                    <input type="text" class="swal2-input" name="nroDocumento" value="${familiar.nroDocumento || ''}" placeholder="N° Documento">
                                    <input type="text" class="swal2-input" name="ocupacion" value="${familiar.ocupacion || ''}" placeholder="Ocupación">
                                    <select class="swal2-input" name="sexo">
                                        <option value="">Seleccionar sexo</option>
                                        <option value="MASCULINO" ${familiar.sexo == 'MASCULINO' ? 'selected' : ''}>Masculino</option>
                                        <option value="FEMENINO" ${familiar.sexo == 'FEMENINO' ? 'selected' : ''}>Femenino</option>
                                    </select>
                                    <input type="text" class="swal2-input flatpickr" name="fechaNacimiento" value="${familiar.fechaNacimiento || ''}" placeholder="Fecha Nacimiento">
                                    <input type="text" class="swal2-input" name="domicilioActual" value="${familiar.domicilioActual || ''}" placeholder="Domicilio Actual">
                                </form>
                            `,
                            showCancelButton: true,
                            confirmButtonText: 'Actualizar',
                            cancelButtonText: 'Cancelar',
                            confirmButtonColor: '#3b82f6',
                            cancelButtonColor: '#6b7280',
                            width: '600px',
                            didOpen: () => {
                                if (typeof flatpickr !== 'undefined') {
                                    flatpickr(".flatpickr", {
                                        dateFormat: "Y-m-d",
                                        locale: "es"
                                    });
                                }
                            },
                            preConfirm: () => {
                                let form = document.getElementById('familiar-edit-form');
                                let formData = new FormData(form);
                                return Object.fromEntries(formData);
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                let data = result.value;
                                
                                $.ajax({
                                    url: '/usuario/familiar/' + id,
                                    type: 'PUT',
                                    data: JSON.stringify(data),
                                    contentType: 'application/json',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function(response) {
                                        if (response.success) {
                                            toastr.success('Familiar actualizado correctamente');
                                            setTimeout(() => location.reload(), 1500);
                                        } else {
                                            toastr.error('Error al actualizar familiar');
                                        }
                                    },
                                    error: function() {
                                        toastr.error('Error al actualizar familiar');
                                    }
                                });
                            }
                        });
                    }
                },
                error: function() {
                    toastr.error('Error al cargar datos del familiar');
                }
            });
        });

        // ============================================
        // AGREGAR CONTACTO DE EMERGENCIA
        // ============================================
        $('#add-contacto-btn').click(function() {
            Swal.fire({
                title: 'Agregar Contacto de Emergencia',
                html: `
                    <form id="contacto-form" class="space-y-3">
                        <input type="text" class="swal2-input" name="apellidosNombres" placeholder="Nombre completo" required>
                        <input type="text" class="swal2-input" name="parentesco" placeholder="Parentesco (Ej: Padre, Madre)" required>
                        <input type="text" class="swal2-input" name="direccionTelefono" placeholder="Teléfono / Dirección" required>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                width: '500px',
                preConfirm: () => {
                    let form = document.getElementById('contacto-form');
                    let formData = new FormData(form);
                    let data = Object.fromEntries(formData);
                    
                    if (!data.apellidosNombres) {
                        Swal.showValidationMessage('Debe ingresar el nombre');
                        return false;
                    }
                    if (!data.parentesco) {
                        Swal.showValidationMessage('Debe ingresar el parentesco');
                        return false;
                    }
                    if (!data.direccionTelefono) {
                        Swal.showValidationMessage('Debe ingresar teléfono/dirección');
                        return false;
                    }
                    return data;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    let data = result.value;
                    data.idUsuario = {{ $usuario->idUsuario }};
                    
                    $.ajax({
                        url: '/usuario/contacto-emergencia/guardar',
                        type: 'POST',
                        data: JSON.stringify(data),
                        contentType: 'application/json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success('Contacto agregado correctamente');
                                setTimeout(() => location.reload(), 1500);
                            } else {
                                toastr.error('Error al agregar contacto');
                            }
                        },
                        error: function() {
                            toastr.error('Error al agregar contacto');
                        }
                    });
                }
            });
        });

        // ============================================
        // ELIMINAR CONTACTO DE EMERGENCIA
        // ============================================
        $(document).on('click', '.delete-contacto', function() {
            let id = $(this).data('id');
            Swal.fire({
                title: '¿Eliminar contacto?',
                text: 'Esta acción no se puede revertir',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/usuario/contacto-emergencia/' + id,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success('Contacto eliminado');
                                setTimeout(() => location.reload(), 1500);
                            } else {
                                toastr.error('Error al eliminar contacto');
                            }
                        },
                        error: function() {
                            toastr.error('Error al eliminar contacto');
                        }
                    });
                }
            });
        });

        // ============================================
        // ACTUALIZAR CONTACTO DE EMERGENCIA (al perder foco)
        // ============================================
        $('.contacto-nombre, .contacto-parentesco, .contacto-direccion').on('blur', function() {
            let id = $(this).data('id');
            let nombre = $(this).closest('.grid').find('.contacto-nombre').val();
            let parentesco = $(this).closest('.grid').find('.contacto-parentesco').val();
            let direccion = $(this).closest('.grid').find('.contacto-direccion').val();
            
            let data = {
                apellidosNombres: nombre,
                parentesco: parentesco,
                direccionTelefono: direccion
            };
            
            $.ajax({
                url: '/usuario/contacto-emergencia/' + id,
                type: 'PUT',
                data: JSON.stringify(data),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success('Contacto actualizado');
                    }
                },
                error: function() {
                    toastr.error('Error al actualizar contacto');
                }
            });
        });
    });
</script>