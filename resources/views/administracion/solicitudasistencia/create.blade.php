<x-layout.default>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    <style>
        .table-days {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .table-days th {
            background-color: #f8fafc;
            font-weight: 600;
            color: #374151;
            padding: 0.75rem;
            text-align: center;
        }

        .table-days td {
            padding: 0.75rem;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }

        .table-days tr:hover {
            background-color: #f9fafb;
        }

        .flatpickr-input {
            background-color: white !important;
            cursor: pointer !important;
        }

        .select2-container--default .select2-selection--single {
            height: 44px;
            /* ajusta: 44–48px queda perfecto */
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 44px;
            /* igual a la altura */
            padding-left: 1rem;
            padding-right: 2.5rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px;
        }
    </style>

    <div class="min-h-screen bg-gray-50">
        <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Breadcrumb -->
            <div class="mb-4">
                <ul class="flex space-x-2 rtl:space-x-reverse text-sm">
                    <li>
                        <a href="{{ route('administracion.solicitud-asistencia.index') }}"
                            class="text-primary hover:underline font-medium">
                            <i class="fas fa-arrow-left mr-1"></i>
                            Solicitudes de Asistencia
                        </a>
                    </li>
                    <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1 text-gray-400">
                        <span class="text-gray-600 font-medium">
                            Nueva Solicitud
                        </span>
                    </li>
                </ul>
            </div>

            <!-- Header -->
            <div class="bg-white rounded-2xl border border-gray-200 p-5 mb-6 shadow-sm">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            <i class="fas fa-plus-circle text-blue-600 mr-2"></i>
                            Nueva Solicitud de Asistencia
                        </h1>
                        <p class="text-gray-600 mt-2">
                            <i class="fas fa-info-circle text-gray-400 mr-1"></i>
                            Complete todos los campos requeridos para crear una nueva solicitud
                        </p>
                    </div>
                </div>
            </div>

            <!-- Formulario -->
            <form method="POST" action="{{ route('administracion.solicitud-asistencia.store') }}"
                enctype="multipart/form-data"
                class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-8">
                @csrf

                <!-- Mensajes de error -->
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-600 text-lg"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Corrige los siguientes errores:</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach ($errors->all() as $e)
                                            <li>{{ $e }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Sección: Usuarios -->
                <div class="space-y-4">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-user-friends text-blue-500 mr-2"></i>
                        Información de Usuarios
                    </h2>

                    <!-- Información del solicitante (usuario autenticado) -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-4">
                        <div class="flex items-center">
                            <div class="p-3 rounded-lg bg-blue-100 text-blue-600 mr-4">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Solicitante</p>
                                <p class="text-sm text-gray-900">
                                    {{ $usuarioAutenticado->Nombre }} {{ $usuarioAutenticado->apellidoPaterno }}
                                    {{ $usuarioAutenticado->apellidoMaterno }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-id-card mr-1"></i>
                                    {{ $usuarioAutenticado->documento ?? 'Sin documento' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Selector de usuario DESTINO -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user-check text-gray-400 mr-1"></i>
                            Usuario para quien es la solicitud *
                        </label>
                        <select id="usuarioDestinoSelect" name="id_usuario" class="w-full" required>
                            <option value="">Seleccione un usuario...</option>
                            @foreach ($usuariosDestino as $usuario)
                                <option value="{{ $usuario->idUsuario }}"
                                    {{ old('id_usuario') == $usuario->idUsuario ? 'selected' : '' }}>
                                    {{ $usuario->Nombre }} {{ $usuario->apellidoPaterno }}
                                    {{ $usuario->apellidoMaterno }}
                                    @if ($usuario->documento)
                                        - {{ $usuario->documento }}
                                    @endif
                                </option>
                            @endforeach
                        </select>

                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Solo se muestran usuarios de tu mismo tipo de área
                        </p>
                    </div>
                </div>

                <!-- Sección: Tipo de solicitud -->
                <div class="space-y-4">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-tag text-blue-500 mr-2"></i>
                        Tipo de Solicitud
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-file-alt text-gray-400 mr-1"></i>
                                Tipo de solicitud *
                            </label>
                            <select id="tipoSolicitud" name="id_tipo_solicitud"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                required>
                                <option value="">Seleccione un tipo...</option>
                                @foreach ($tipos as $t)
                                    <option value="{{ $t->id_tipo_solicitud }}" data-nombre="{{ $t->nombre_tip }}"
                                        {{ old('id_tipo_solicitud') == $t->id_tipo_solicitud ? 'selected' : '' }}>
                                        {{ $t->nombre_tip }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div id="boxTipoEducacion" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-graduation-cap text-gray-400 mr-1"></i>
                                Tipo de educación
                            </label>
                            <select name="id_tipo_educacion"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Seleccione tipo de educación...</option>
                                @foreach ($tiposEducacion as $te)
                                    <option value="{{ $te->id_tipo_educacion }}"
                                        {{ old('id_tipo_educacion') == $te->id_tipo_educacion ? 'selected' : '' }}>
                                        {{ $te->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Sección: Rango de Tiempo -->
                <div class="space-y-4">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
                        Rango de Tiempo
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Fecha inicio -->
                        <div>
                            <label for="fechaInicio"
                                class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                                <i class="fas fa-calendar-day text-gray-400"></i>
                                Fecha inicio
                            </label>
                            <input type="text" name="rango_inicio_tiempo" id="fechaInicio"
                                value="{{ old('rango_inicio_tiempo') }}"
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors flatpickr-input"
                                placeholder="Selecciona fecha y hora de inicio" required readonly>
                        </div>

                        <!-- Fecha fin -->
                        <div>
                            <label for="fechaFin"
                                class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                                <i class="fas fa-calendar-check text-gray-400"></i>
                                Fecha fin
                            </label>
                            <input type="text" name="rango_final_tiempo" id="fechaFin"
                                value="{{ old('rango_final_tiempo') }}"
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors flatpickr-input"
                                placeholder="Selecciona fecha y hora de fin" required readonly>
                        </div>
                    </div>
                </div>

                <!-- Sección: Observaciones -->
                <div class="space-y-4">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-sticky-note text-blue-500 mr-2"></i>
                        Observaciones
                    </h2>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-comment-alt text-gray-400 mr-1"></i>
                            Observación
                        </label>
                        <textarea name="observacion" rows="3"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                            placeholder="Escribe aquí cualquier observación adicional...">{{ old('observacion') }}</textarea>
                    </div>
                </div>

                <!-- Sección: Licencia Médica (condicional) -->
                <div id="boxLicenciaMedica"
                    class="hidden border border-red-200 bg-gradient-to-br from-red-50 to-red-50/50 rounded-2xl p-6 space-y-4">
                    <div class="flex items-start">
                        <div class="p-3 rounded-xl bg-red-100 text-red-600 mr-4">
                            <i class="fas fa-file-medical text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">
                                <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                                Documentación de Licencia Médica
                            </h2>
                            <p class="text-sm text-gray-600 mt-1">
                                Este tipo de solicitud requiere adjuntar la licencia médica como respaldo.
                            </p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-camera text-red-400 mr-1"></i>
                                Foto de licencia médica <span class="text-red-500">*</span>
                            </label>

                            <div class="file-upload-area border-2 border-dashed border-red-300 rounded-xl p-6 text-center hover:border-red-400 hover:bg-red-50 transition-all cursor-pointer"
                                onclick="document.getElementById('licenciaInput').click()">
                                <input type="file" id="licenciaInput" name="imagen_licencia" accept="image/*"
                                    class="hidden" onchange="handleLicenciaUpload(event)">

                                <div class="space-y-3">
                                    <div
                                        class="mx-auto w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                                        <i class="fas fa-cloud-upload-alt text-red-500 text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-700">Subir licencia médica</p>
                                        <p class="text-sm text-gray-500 mt-1">Haz clic o arrastra la imagen aquí</p>
                                        <p class="text-xs text-gray-400 mt-1">Formatos: JPG, PNG, GIF • Máx. 5MB</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview de la imagen -->
                            <div id="licenciaPreview" class="hidden mt-4">
                                <div
                                    class="flex items-center justify-between bg-white border border-gray-200 rounded-xl p-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="relative">
                                            <img id="licenciaPreviewImg"
                                                class="h-16 w-16 object-cover rounded-lg border">
                                            <div
                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center">
                                                <i class="fas fa-file-medical text-xs"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900" id="licenciaFileName">
                                                licencia_medica.jpg</p>
                                            <p class="text-sm text-gray-500" id="licenciaFileSize">2.4 MB</p>
                                        </div>
                                    </div>
                                    <button type="button" onclick="removeLicenciaPreview()"
                                        class="text-red-500 hover:text-red-700 transition-colors p-2">
                                        <i class="fas fa-times text-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección: Educativo (condicional) -->
                <div id="boxEducativo"
                    class="hidden border border-blue-200 bg-gradient-to-br from-blue-50 to-blue-50/50 rounded-2xl p-6 space-y-6">
                    <div class="flex items-start">
                        <div class="p-3 rounded-xl bg-blue-100 text-blue-600 mr-4">
                            <i class="fas fa-graduation-cap text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">
                                <i class="fas fa-book-open text-blue-500 mr-2"></i>
                                Información Educativa
                            </h2>
                            <p class="text-sm text-gray-600 mt-1">
                                Complete la información adicional para solicitudes de tipo educativo.
                            </p>
                        </div>
                    </div>

                    <!-- Archivos con preview -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Archivo educativo -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-file-pdf text-blue-400 mr-1"></i>
                                Archivo educativo (PDF, Word, Excel)
                            </label>

                            <div class="file-upload-area border-2 border-dashed border-blue-300 rounded-xl p-4 text-center hover:border-blue-400 hover:bg-blue-50 transition-all cursor-pointer"
                                onclick="document.getElementById('archivoInput').click()">
                                <input type="file" id="archivoInput" name="archivo"
                                    accept=".pdf,.doc,.docx,.xls,.xlsx" class="hidden"
                                    onchange="handleArchivoUpload(event)">

                                <div class="space-y-2">
                                    <div
                                        class="mx-auto w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-file-alt text-blue-500"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-700">Subir archivo</p>
                                        <p class="text-xs text-gray-500">Formatos: PDF, DOC, XLS</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview del archivo -->
                            <div id="archivoPreview" class="hidden mt-3">
                                <div
                                    class="flex items-center justify-between bg-white border border-gray-200 rounded-xl p-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="relative">
                                            <div
                                                class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center">
                                                <i class="fas fa-file-pdf text-blue-500"></i>
                                            </div>
                                            <div
                                                class="absolute -top-2 -right-2 bg-blue-500 text-white rounded-full w-5 h-5 flex items-center justify-center">
                                                <i class="fas fa-check text-xs"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-900 truncate" id="archivoFileName">
                                                documento_educativo.pdf</p>
                                            <p class="text-sm text-gray-500" id="archivoFileSize">1.8 MB</p>
                                        </div>
                                    </div>
                                    <button type="button" onclick="removeArchivoPreview()"
                                        class="text-gray-400 hover:text-red-500 transition-colors p-1">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Imagen opcional -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-image text-blue-400 mr-1"></i>
                                Imagen opcional
                            </label>

                            <div class="file-upload-area border-2 border-dashed border-blue-300 rounded-xl p-4 text-center hover:border-blue-400 hover:bg-blue-50 transition-all cursor-pointer"
                                onclick="document.getElementById('imagenInput').click()">
                                <input type="file" id="imagenInput" name="imagen_opcional" accept="image/*"
                                    class="hidden" onchange="handleImagenUpload(event)">

                                <div class="space-y-2">
                                    <div
                                        class="mx-auto w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-image text-blue-500"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-700">Subir imagen</p>
                                        <p class="text-xs text-gray-500">Formatos: JPG, PNG, GIF</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview de la imagen -->
                            <div id="imagenPreview" class="hidden mt-3">
                                <div
                                    class="flex items-center justify-between bg-white border border-gray-200 rounded-xl p-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="relative">
                                            <img id="imagenPreviewImg"
                                                class="h-12 w-12 object-cover rounded-lg border">
                                            <div
                                                class="absolute -top-2 -right-2 bg-green-500 text-white rounded-full w-5 h-5 flex items-center justify-center">
                                                <i class="fas fa-check text-xs"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-900 truncate" id="imagenFileName">
                                                imagen_opcional.jpg</p>
                                            <p class="text-sm text-gray-500" id="imagenFileSize">850 KB</p>
                                        </div>
                                    </div>
                                    <button type="button" onclick="removeImagenPreview()"
                                        class="text-gray-400 hover:text-red-500 transition-colors p-1">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de días -->
                    <div class="space-y-4">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-calendar-check text-blue-400 mr-1"></i>
                            Días de la semana afectados
                        </label>

                        <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm">
                            <table class="min-w-full divide-y divide-gray-200 bg-white">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            <div class="flex items-center">
                                                <i class="fas fa-calendar-day mr-2 text-blue-500"></i>
                                                Día
                                            </div>
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            <div class="flex items-center justify-center">
                                                <i class="fas fa-check-circle mr-2 text-green-500"></i>
                                                Todo el día
                                            </div>
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            <div class="flex items-center justify-center">
                                                <i class="fas fa-sign-in-alt mr-2 text-blue-500"></i>
                                                Entrada
                                            </div>
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            <div class="flex items-center justify-center">
                                                <i class="fas fa-sign-out-alt mr-2 text-blue-500"></i>
                                                Salida
                                            </div>
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            <div class="flex items-center justify-center">
                                                <i class="fas fa-briefcase mr-2 text-blue-500"></i>
                                                Llegada
                                            </div>
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            <div class="flex items-center justify-center">
                                                <i class="fas fa-comment-alt mr-2 text-gray-500"></i>
                                                Observación
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="diasEducativoBody" class="divide-y divide-gray-200 bg-white"></tbody>
                            </table>
                        </div>

                        <!-- Nota informativa -->
                        <div class="flex items-start bg-blue-50 border border-blue-100 rounded-lg p-3">
                            <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
                            <p class="text-sm text-gray-700">
                                <span class="font-medium">Nota:</span> Selecciona "Todo el día" para indicar que la
                                ausencia cubre la jornada completa.
                                De lo contrario, especifica los horarios correspondientes.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div
                    class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-6 border-t border-gray-200">
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Los campos marcados con * son obligatorios
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ route('administracion.solicitud-asistencia.index') }}"
                            class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors shadow-sm flex items-center">
                            <i class="fas fa-times mr-2"></i>
                            Cancelar
                        </a>

                        <button type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg hover:shadow-xl flex items-center">
                            <i class="fas fa-save mr-2"></i>
                            Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>
    <script>
        // Configurar Toastr
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": true,
            "timeOut": "5000"
        };

        // Mostrar mensajes de sesión
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        // Configuración de Flatpickr
        document.addEventListener('DOMContentLoaded', function() {
            const flatpickrConfig = {
                locale: "es",
                enableTime: true,
                time_24hr: true,
                dateFormat: "Y-m-d H:i",
                altInput: true,
                altFormat: "d/m/Y H:i",
                allowInput: true,
                minDate: "null",
                minuteIncrement: 15,
                defaultHour: 8,
                defaultMinute: 0
            };

            // Configurar fecha de inicio
            const fechaInicioPicker = flatpickr("#fechaInicio", {
                ...flatpickrConfig,
                onChange: function(selectedDates, dateStr) {
                    if (dateStr && fechaFinPicker) {
                        fechaFinPicker.set('minDate', selectedDates[0]);

                        const fechaFinValue = document.getElementById('fechaFin').value;
                        if (fechaFinValue && new Date(fechaFinValue) < selectedDates[0]) {
                            fechaFinPicker.clear();
                        }
                    }
                }
            });

            // Configurar fecha final
            const fechaFinPicker = flatpickr("#fechaFin", {
                ...flatpickrConfig,
                minDate: "null"
            });

            initFileUploads();
        });

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function handleLicenciaUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            if (!file.type.startsWith('image/')) {
                toastr.error('Por favor, sube solo archivos de imagen');
                return;
            }

            if (file.size > 5 * 1024 * 1024) {
                toastr.error('El archivo es demasiado grande. Máximo 5MB');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const previewDiv = document.getElementById('licenciaPreview');
                const previewImg = document.getElementById('licenciaPreviewImg');
                const fileName = document.getElementById('licenciaFileName');
                const fileSize = document.getElementById('licenciaFileSize');

                previewImg.src = e.target.result;
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);
                previewDiv.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }

        function handleArchivoUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            const allowedTypes = ['.pdf', '.doc', '.docx', '.xls', '.xlsx'];
            const fileExtension = '.' + file.name.split('.').pop().toLowerCase();

            if (!allowedTypes.includes(fileExtension)) {
                toastr.error('Formato de archivo no permitido. Use PDF, Word o Excel');
                return;
            }

            if (file.size > 10 * 1024 * 1024) {
                toastr.error('El archivo es demasiado grande. Máximo 10MB');
                return;
            }

            const previewDiv = document.getElementById('archivoPreview');
            const fileName = document.getElementById('archivoFileName');
            const fileSize = document.getElementById('archivoFileSize');

            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            previewDiv.classList.remove('hidden');
        }

        function handleImagenUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            if (!file.type.startsWith('image/')) {
                toastr.error('Por favor, sube solo archivos de imagen');
                return;
            }

            if (file.size > 3 * 1024 * 1024) {
                toastr.error('La imagen es demasiado grande. Máximo 3MB');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const previewDiv = document.getElementById('imagenPreview');
                const previewImg = document.getElementById('imagenPreviewImg');
                const fileName = document.getElementById('imagenFileName');
                const fileSize = document.getElementById('imagenFileSize');

                previewImg.src = e.target.result;
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);
                previewDiv.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }

        function removeLicenciaPreview() {
            const previewDiv = document.getElementById('licenciaPreview');
            const input = document.getElementById('licenciaInput');
            previewDiv.classList.add('hidden');
            input.value = '';
        }

        function removeArchivoPreview() {
            const previewDiv = document.getElementById('archivoPreview');
            const input = document.getElementById('archivoInput');
            previewDiv.classList.add('hidden');
            input.value = '';
        }

        function removeImagenPreview() {
            const previewDiv = document.getElementById('imagenPreview');
            const input = document.getElementById('imagenInput');
            previewDiv.classList.add('hidden');
            input.value = '';
        }

        function initFileUploads() {
            const uploadAreas = document.querySelectorAll('.file-upload-area');
            uploadAreas.forEach(area => {
                area.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    this.classList.add('border-blue-400', 'bg-blue-50');
                });

                area.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    this.classList.remove('border-blue-400', 'bg-blue-50');
                });

                area.addEventListener('drop', function(e) {
                    e.preventDefault();
                    this.classList.remove('border-blue-400', 'bg-blue-50');

                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        const input = this.querySelector('input[type="file"]');
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(files[0]);
                        input.files = dataTransfer.files;

                        const event = new Event('change', {
                            bubbles: true
                        });
                        input.dispatchEvent(event);
                    }
                });
            });
        }

        document.querySelector('form').addEventListener('submit', function(e) {
            const fechaInicio = document.getElementById('fechaInicio').value;
            const fechaFin = document.getElementById('fechaFin').value;

            if (fechaInicio && fechaFin) {
                const inicio = new Date(fechaInicio);
                const fin = new Date(fechaFin);

                if (fin <= inicio) {
                    e.preventDefault();
                    toastr.error('La fecha final debe ser mayor que la fecha de inicio');
                    return false;
                }
            }

            const boxLicencia = document.getElementById('boxLicenciaMedica');
            if (!boxLicencia.classList.contains('hidden')) {
                const licenciaInput = document.getElementById('licenciaInput');
                if (!licenciaInput || !licenciaInput.files.length) {
                    e.preventDefault();
                    toastr.error('Debe adjuntar la licencia médica para este tipo de solicitud');
                    boxLicencia.scrollIntoView({
                        behavior: 'smooth'
                    });
                    return false;
                }
            }

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Procesando...';
            submitBtn.disabled = true;

            return true;
        });

        $(document).ready(function() {
            $('#usuarioDestinoSelect').select2({
                placeholder: 'Seleccione un usuario...',
                allowClear: true,
                width: '100%'
            });
        });
    </script>

    <!-- Mantener tu JS original -->
    <script src="{{ asset('assets/js/solicitudasistencia/form.js') }}"></script>
</x-layout.default>
