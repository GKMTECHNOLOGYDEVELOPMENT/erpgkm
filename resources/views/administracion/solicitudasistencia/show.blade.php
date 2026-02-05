<x-layout.default>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

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
                            Detalles de Solicitud
                        </span>
                    </li>
                </ul>
            </div>

            <!-- Header -->
            <div class="bg-white rounded-2xl border border-gray-200 p-5 mb-6 shadow-sm">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <h1 class="text-3xl font-bold text-gray-900">
                                <i class="fas fa-file-alt text-primary mr-2"></i>
                                Detalles de Solicitud
                            </h1>
                            <span
                                class="px-3 py-1 rounded-full text-xs font-bold
                                {{ $solicitud->estado == 'aprobado'
                                    ? 'bg-success-light text-success border border-success'
                                    : ($solicitud->estado == 'denegado'
                                        ? 'bg-danger-light text-danger border border-danger'
                                        : 'bg-warning-light text-warning border border-warning') }}">
                                <i class="fas fa-circle text-xs mr-1"></i>
                                {{ strtoupper($solicitud->estado) }}
                            </span>
                        </div>
                        <p class="text-gray-600">
                            <i class="fas fa-hashtag text-gray-400 mr-1"></i>
                            ID: <span class="font-semibold">{{ $solicitud->id_solicitud_asistencia }}</span> •
                            <i class="fas fa-tag text-gray-400 ml-2 mr-1"></i>
                            Tipo: <span class="font-semibold">{{ $solicitud->tipoSolicitud->nombre_tip }}</span>
                        </p>
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ route('administracion.solicitud-asistencia.index') }}"
                            class="px-5 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors shadow-sm flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Volver
                        </a>

                        @if ($solicitud->estado == 'pendiente' && Auth::id() == $solicitud->id_usuario_solicitante)
                            <a href="{{ route('administracion.solicitud-asistencia.edit', $solicitud->id_solicitud_asistencia) }}"
                                class="px-5 py-2.5 bg-primary-light text-primary font-medium rounded-xl hover:bg-primary hover:text-white transition-colors shadow-sm flex items-center">
                                <i class="fas fa-edit mr-2"></i>
                                Editar
                            </a>
                        @endif
                    </div>
                </div>
            </div>

<!-- Información principal -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Tarjeta 1: Información General -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
        <div class="flex items-center mb-4">
            <div class="p-2 rounded-lg bg-primary-light text-primary mr-3">
                <i class="fas fa-info-circle text-lg"></i>
            </div>
            <h2 class="text-lg font-semibold text-gray-800">Información General</h2>
        </div>

        <div class="space-y-3">
            <!-- Fecha de solicitud -->
            <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                <span class="text-sm text-gray-600 flex items-center">
                    <i class="fas fa-calendar-plus mr-2 text-gray-400"></i>
                    Fecha de solicitud
                </span>
                <span class="font-medium text-gray-900">
                    {{ $solicitud->fecha_solicitud->format('d/m/Y H:i') }}
                </span>
            </div>

            <!-- SOLICITANTE (usuario que crea la solicitud) -->
            <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                <span class="text-sm text-gray-600 flex items-center">
                    <i class="fas fa-user-tie mr-2 text-blue-400"></i>
                    Solicitante
                </span>
                <span class="font-medium text-gray-900">
                    @if($solicitud->usuarioSolicitante)
                        {{ $solicitud->usuarioSolicitante->Nombre }}
                        {{ $solicitud->usuarioSolicitante->apellidoPaterno }}
                        {{ $solicitud->usuarioSolicitante->apellidoMaterno }}
                    @else
                        Usuario no disponible
                    @endif
                </span>
            </div>

            <!-- DESTINATARIO (usuario para quien es la solicitud) -->
            <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                <span class="text-sm text-gray-600 flex items-center">
                    <i class="fas fa-user mr-2 text-green-400"></i>
                    Destinatario
                </span>
                <span class="font-medium text-gray-900">
                    @if($solicitud->usuarioDestino)
                        {{ $solicitud->usuarioDestino->Nombre }}
                        {{ $solicitud->usuarioDestino->apellidoPaterno }}
                        {{ $solicitud->usuarioDestino->apellidoMaterno }}
                    @else
                        Usuario no disponible
                    @endif
                </span>
            </div>

            <!-- Tipo de solicitud -->
            <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                <span class="text-sm text-gray-600 flex items-center">
                    <i class="fas fa-tag mr-2 text-gray-400"></i>
                    Tipo de solicitud
                </span>
                <span class="font-medium text-gray-900">
                    {{ $solicitud->tipoSolicitud->nombre_tip }}
                </span>
            </div>

            <!-- Tipo de educación (si aplica) -->
            @if ($solicitud->tipoEducacion)
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 flex items-center">
                        <i class="fas fa-graduation-cap mr-2 text-gray-400"></i>
                        Tipo de educación
                    </span>
                    <span class="font-medium text-gray-900">
                        {{ $solicitud->tipoEducacion->nombre }}
                    </span>
                </div>
            @endif
        </div>
    </div>

    <!-- Tarjeta 2: Rango de Tiempo -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
        <div class="flex items-center mb-4">
            <div class="p-2 rounded-lg bg-success-light text-success mr-3">
                <i class="fas fa-calendar-alt text-lg"></i>
            </div>
            <h2 class="text-lg font-semibold text-gray-800">Rango de Tiempo</h2>
        </div>

        <div class="space-y-4">
            <!-- INICIO -->
            <div class="bg-primary-light border border-primary rounded-xl p-4">
                <div class="flex items-center mb-2">
                    <div class="p-1.5 rounded-lg bg-primary text-white mr-3">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <div>
                        <p class="text-xs text-primary font-semibold">INICIO</p>
                        <p class="text-lg font-bold text-gray-900">
                            {{ $solicitud->rango_inicio_tiempo?->format('d/m/Y') ?? '-' }}
                        </p>
                        <p class="text-sm text-gray-600">
                            {{ $solicitud->rango_inicio_tiempo?->format('H:i') ?? '--:--' }} horas
                        </p>
                    </div>
                </div>
            </div>

            <!-- FINAL -->
            <div class="bg-danger-light border border-danger rounded-xl p-4">
                <div class="flex items-center mb-2">
                    <div class="p-1.5 rounded-lg bg-danger text-white mr-3">
                        <i class="fas fa-stop-circle"></i>
                    </div>
                    <div>
                        <p class="text-xs text-danger font-semibold">FINAL</p>
                        <p class="text-lg font-bold text-gray-900">
                            {{ $solicitud->rango_final_tiempo?->format('d/m/Y') ?? '-' }}
                        </p>
                        <p class="text-sm text-gray-600">
                            {{ $solicitud->rango_final_tiempo?->format('H:i') ?? '--:--' }} horas
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CONTADOR DE DÍAS -->
        <div class="mt-5 text-center">
            <span
                class="inline-flex items-center px-4 py-2 rounded-full bg-gray-100 text-gray-800 text-sm font-semibold">
                <i class="fas fa-clock mr-2 text-gray-500"></i>
                {{ $diasDuracion }} día{{ $diasDuracion !== 1 ? 's' : '' }} de duración
            </span>
        </div>
    </div>

    <!-- Tarjeta 3: Archivos - SIEMPRE VISIBLE -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
        <div class="flex items-center mb-4">
            <div class="p-2 rounded-lg {{ ($solicitud->archivos->count() > 0 || $solicitud->imagenes->count() > 0) ? 'bg-info-light text-info' : 'bg-gray-100 text-gray-400' }} mr-3">
                <i class="fas fa-paperclip text-lg"></i>
            </div>
            <h2 class="text-lg font-semibold {{ ($solicitud->archivos->count() > 0 || $solicitud->imagenes->count() > 0) ? 'text-gray-800' : 'text-gray-400' }}">
                Documentos Adjuntos
            </h2>
        </div>

        @if ($solicitud->archivos->count() > 0 || $solicitud->imagenes->count() > 0)
            <!-- Si hay archivos o imágenes -->
            <div class="space-y-3">
                {{-- ARCHIVOS --}}
                @foreach ($solicitud->archivos as $archivo)
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between
                               bg-gray-50 border border-gray-200 rounded-lg p-3
                               hover:bg-gray-100 transition-colors gap-3">

                        <div class="flex items-center gap-3 min-w-0">
                            <div class="p-2 rounded-lg bg-primary-light text-primary shrink-0">
                                <i class="fas fa-file-pdf"></i>
                            </div>

                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-gray-900 text-sm truncate">
                                    {{ basename($archivo->archivo_solicitud) }}
                                </p>
                                <p class="text-xs text-gray-500 capitalize">
                                    {{ str_replace('_', ' ', $archivo->tipo_archivo) }}
                                </p>
                                <!-- Tamaño del archivo -->
                                <p class="text-xs text-gray-400 mt-1">
                                    @php
                                        $bytes = $archivo->espacio_archivo ?? 0;
                                        if ($bytes == 0) {
                                            echo '0 Bytes';
                                        } else {
                                            $k = 1024;
                                            $sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                                            $i = floor(log($bytes) / log($k));
                                            echo number_format($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
                                        }
                                    @endphp
                                </p>
                            </div>
                        </div>

                        <a href="{{ asset($archivo->archivo_solicitud) }}"  target="_blank"
                            class="self-start sm:self-auto p-2 text-primary hover:text-primary-dark transition-colors"
                            title="Ver archivo">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </div>
                @endforeach

                {{-- IMÁGENES --}}
                @foreach ($solicitud->imagenes as $imagen)
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between
                               bg-gray-50 border border-gray-200 rounded-lg p-3
                               hover:bg-gray-100 transition-colors gap-3">

                        <div class="flex items-center gap-3 min-w-0">
                            <div class="p-2 rounded-lg bg-success-light text-success shrink-0">
                                <i class="fas fa-image"></i>
                            </div>

                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-gray-900 text-sm truncate">
                                    {{ basename($imagen->imagen) }}
                                </p>
                                <p class="text-xs text-gray-500">Imagen adjunta</p>
                            </div>
                        </div>

                        <a href="{{ asset($imagen->imagen) }}" target="_blank"
                            class="self-start sm:self-auto p-2 text-primary hover:text-primary-dark transition-colors"
                            title="Ver imagen">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Estado: Sin archivos ni imágenes -->
            <div class="text-center py-8">
                <div class="mb-4">
                    <i class="fas fa-folder-open text-4xl text-gray-300"></i>
                </div>
                <p class="text-gray-500 font-medium mb-2">Sin archivos ni imágenes</p>
                <p class="text-sm text-gray-400">Esta solicitud no requiere documentos adjuntos</p>
            </div>
        @endif
    </div>
</div>

            <!-- MATRÍCULA DE DÍAS PROGRAMADOS -->
            @if ($solicitud->dias->count() > 0)
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-6">
                    <!-- Header de la sección -->
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="p-3 rounded-xl bg-primary text-white mr-4">
                                    <i class="fas fa-calendar-alt text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900">Matrícula de Días Programados</h2>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Cronograma detallado de los días afectados por la solicitud
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-gray-500 mb-1">Total de días</div>
                                <div class="text-2xl font-bold text-gray-900">{{ $solicitud->dias->count() }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla tipo matrícula -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse rounded-2xl overflow-hidden shadow-sm">
                            <thead>
                                <tr>
                                    <th
                                        class="bg-primary px-6 py-4 text-center text-sm font-semibold text-white uppercase tracking-wider border-r border-white/10">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-calendar-day mb-1"></i>
                                            <span>DÍA</span>
                                        </div>
                                    </th>
                                    <th
                                        class="bg-primary px-6 py-4 text-center text-sm font-semibold text-white uppercase tracking-wider border-r border-white/10">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-calendar mb-1"></i>
                                            <span>FECHA</span>
                                        </div>
                                    </th>
                                    <th
                                        class="bg-primary px-6 py-4 text-center text-sm font-semibold text-white uppercase tracking-wider border-r border-white/10">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-clock mb-1"></i>
                                            <span>JORNADA</span>
                                        </div>
                                    </th>
                                    <th
                                        class="bg-primary px-6 py-4 text-center text-sm font-semibold text-white uppercase tracking-wider border-r border-white/10">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-sign-in-alt mb-1"></i>
                                            <span>HORA ENTRADA</span>
                                        </div>
                                    </th>
                                    <th
                                        class="bg-primary px-6 py-4 text-center text-sm font-semibold text-white uppercase tracking-wider border-r border-white/10">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-sign-out-alt mb-1"></i>
                                            <span>HORA SALIDA</span>
                                        </div>
                                    </th>
                                    <th
                                        class="bg-primary px-6 py-4 text-center text-sm font-semibold text-white uppercase tracking-wider border-r border-white/10">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-briefcase mb-1"></i>
                                            <span>LLEGADA TRABAJO</span>
                                        </div>
                                    </th>
                                    <th
                                        class="bg-primary px-6 py-4 text-center text-sm font-semibold text-white uppercase tracking-wider border-r border-white/10">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-comment-alt mb-1"></i>
                                            <span>OBSERVACIONES</span>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($solicitud->dias->sortBy('fecha') as $dia)
                                    @php
                                        $nombreDia = \Carbon\Carbon::parse($dia->fecha)->locale('es')->dayName;
                                        $colorDia =
                                            [
                                                'lunes' => 'bg-red-100 text-red-600',
                                                'martes' => 'bg-orange-100 text-orange-600',
                                                'miércoles' => 'bg-gray-100 text-gray-600',
                                                'jueves' => 'bg-green-100 text-green-600',
                                                'viernes' => 'bg-blue-100 text-blue-600',
                                                'sábado' => 'bg-purple-100 text-purple-600',
                                                'domingo' => 'bg-gray-100 text-gray-600',
                                            ][strtolower($nombreDia)] ?? 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <tr
                                        class="transition-all duration-200 hover:bg-primary/5 {{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
                                        <!-- Día con círculo de color -->
                                        <td class="px-6 py-4 border-b border-r border-gray-200">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold mr-3 {{ $colorDia }}">
                                                    {{ substr(strtoupper($nombreDia), 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="font-semibold text-gray-900 capitalize">
                                                        {{ $nombreDia }}</div>
                                                    <div class="text-xs text-gray-500">
                                                        Día {{ $loop->iteration }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Fecha -->
                                        <td class="px-6 py-4 border-b border-r border-gray-200 text-center">
                                            <div>
                                                <div class="font-bold text-gray-900 text-lg">
                                                    {{ \Carbon\Carbon::parse($dia->fecha)->format('d') }}
                                                </div>
                                                <div class="text-sm text-gray-600">
                                                    {{ \Carbon\Carbon::parse($dia->fecha)->format('M/Y') }}
                                                </div>
                                                <div class="text-xs text-gray-400 mt-1">
                                                    {{ \Carbon\Carbon::parse($dia->fecha)->format('l') }}
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Jornada (Todo el día o parcial) -->
                                        <td class="px-6 py-4 border-b border-r border-gray-200 text-center">
                                            @if ($dia->es_todo_el_dia)
                                                <div class="flex flex-col items-center">
                                                    <span
                                                        class="inline-flex items-center px-3 py-1.5 rounded-lg bg-danger text-white text-sm font-bold shadow-sm">
                                                        <i class="fas fa-sun mr-1.5"></i>
                                                        TODO EL DÍA
                                                    </span>
                                                    <span class="text-xs text-gray-500 mt-2">
                                                        Jornada completa
                                                    </span>
                                                </div>
                                            @else
                                                <div class="flex flex-col items-center">
                                                    <span
                                                        class="inline-flex items-center px-3 py-1.5 rounded-lg bg-primary-light text-primary border border-primary text-sm font-medium">
                                                        <i class="fas fa-clock mr-1.5"></i>
                                                        JORNADA PARCIAL
                                                    </span>
                                                    <span class="text-xs text-gray-500 mt-2">
                                                        Horario específico
                                                    </span>
                                                </div>
                                            @endif
                                        </td>

                                        <!-- Hora de entrada -->
                                        <td class="px-6 py-4 border-b border-r border-gray-200 text-center">
                                            @if ($dia->es_todo_el_dia)
                                                <span class="text-gray-400 italic">—</span>
                                            @elseif($dia->hora_entrada)
                                                <div class="flex flex-col items-center">
                                                    <span
                                                        class="inline-flex items-center px-3 py-1.5 rounded-lg bg-primary-light text-primary border border-primary text-sm font-medium">
                                                        <i class="fas fa-sign-in-alt mr-1.5"></i>
                                                        {{ \Carbon\Carbon::parse($dia->hora_entrada)->format('H:i') }}
                                                    </span>
                                                    <span class="text-xs text-gray-500 mt-1">
                                                        Horario matutino
                                                    </span>
                                                </div>
                                            @else
                                                <span class="text-gray-400 italic">No especificado</span>
                                            @endif
                                        </td>

                                        <!-- Hora de salida -->
                                        <td class="px-6 py-4 border-b border-r border-gray-200 text-center">
                                            @if ($dia->es_todo_el_dia)
                                                <span class="text-gray-400 italic">—</span>
                                            @elseif($dia->hora_salida)
                                                <div class="flex flex-col items-center">
                                                    <span
                                                        class="inline-flex items-center px-3 py-1.5 rounded-lg bg-success-light text-success border border-success text-sm font-medium">
                                                        <i class="fas fa-sign-out-alt mr-1.5"></i>
                                                        {{ \Carbon\Carbon::parse($dia->hora_salida)->format('H:i') }}
                                                    </span>
                                                    <span class="text-xs text-gray-500 mt-1">
                                                        Fin de jornada
                                                    </span>
                                                </div>
                                            @else
                                                <span class="text-gray-400 italic">No especificado</span>
                                            @endif
                                        </td>

                                        <!-- Hora de llegada al trabajo -->
                                        <td class="px-6 py-4 border-b border-r border-gray-200 text-center">
                                            @if ($dia->es_todo_el_dia)
                                                <span class="text-gray-400 italic">—</span>
                                            @elseif($dia->hora_llegada_trabajo)
                                                <div class="flex flex-col items-center">
                                                    <span
                                                        class="inline-flex items-center px-3 py-1.5 rounded-lg bg-info-light text-info border border-info text-sm font-medium">
                                                        <i class="fas fa-briefcase mr-1.5"></i>
                                                        {{ \Carbon\Carbon::parse($dia->hora_llegada_trabajo)->format('H:i') }}
                                                    </span>
                                                    <span class="text-xs text-gray-500 mt-1">
                                                        Retorno al trabajo
                                                    </span>
                                                </div>
                                            @else
                                                <span class="text-gray-400 italic">No aplica</span>
                                            @endif
                                        </td>

                                        <!-- Observaciones -->
                                        <td class="px-6 py-4 border-b border-gray-200">
                                            @if ($dia->observacion)
                                                <div
                                                    class="bg-warning-light border-l-4 border-warning pl-4 py-2 pr-3 rounded-r-lg">
                                                    <div class="flex items-start">
                                                        <i class="fas fa-sticky-note text-warning mt-0.5 mr-2"></i>
                                                        <div>
                                                            <p class="text-sm text-gray-700">{{ $dia->observacion }}
                                                            </p>
                                                            @if ($dia->es_todo_el_dia)
                                                                <div class="flex items-center mt-1">
                                                                    <i
                                                                        class="fas fa-info-circle text-warning text-xs mr-1"></i>
                                                                    <span class="text-xs text-warning">Jornada
                                                                        completa</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="text-center">
                                                    <i class="fas fa-minus text-gray-300"></i>
                                                    <p class="text-xs text-gray-400 mt-1">Sin observaciones</p>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer de la matrícula -->
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between">
                            <div class="flex items-center mb-3 sm:mb-0 flex-wrap gap-3">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-red-500 mr-2"></div>
                                    <span class="text-xs text-gray-600">Lunes</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-warning mr-2"></div>
                                    <span class="text-xs text-gray-600">Martes</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-dark mr-2"></div>
                                    <span class="text-xs text-gray-600">Miércoles</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                                    <span class="text-xs text-gray-600">Jueves</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-blue-500 mr-2"></div>
                                    <span class="text-xs text-gray-600">Viernes</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-secondary mr-2"></div>
                                    <span class="text-xs text-gray-600">Sábado</span>
                                </div>
                            </div>
                            <div class="text-sm text-gray-600">
                                <i class="fas fa-calendar-check mr-1"></i>
                                Matrícula generada el {{ now()->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Observación -->
            @if ($solicitud->observacion)
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-6">
                    <div class="flex items-center mb-4">
                        <div class="p-2 rounded-lg bg-warning-light text-warning mr-3">
                            <i class="fas fa-sticky-note text-lg"></i>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">Observación</h2>
                    </div>

                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <p class="text-gray-700 leading-relaxed">
                            <i class="fas fa-quote-left text-gray-300 mr-2"></i>
                            {{ $solicitud->observacion }}
                            <i class="fas fa-quote-right text-gray-300 ml-2"></i>
                        </p>
                    </div>
                </div>
            @endif

            <!-- Historial de Evaluaciones -->
            @if ($solicitud->evaluaciones->count() > 0)
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg bg-info-light text-info mr-3">
                                <i class="fas fa-history text-lg"></i>
                            </div>
                            <h2 class="text-lg font-semibold text-gray-800">Historial de Evaluaciones</h2>
                        </div>
                        <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                            <i class="fas fa-list-ol mr-1"></i>
                            Total: {{ $solicitud->evaluaciones->count() }} evaluaciones
                        </span>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @foreach ($solicitud->evaluaciones->sortByDesc('fecha') as $evaluacion)
                            <div class="p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            <div
                                                class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mr-3">
                                                <i class="fas fa-user text-gray-400"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-medium text-gray-900">
                                                    {{ $evaluacion->usuario->name ?? 'Usuario' }}
                                                </h3>
                                                <p class="text-sm text-gray-500 flex items-center">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    {{ $evaluacion->fecha->format('d/m/Y H:i') }}
                                                </p>
                                            </div>
                                        </div>

                                        @if ($evaluacion->comentario)
                                            <div class="ml-13 mt-2">
                                                <p
                                                    class="text-gray-600 bg-gray-50 border border-gray-200 rounded-lg p-3">
                                                    {{ $evaluacion->comentario }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>

                                    <div>
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-bold
                                    {{ $evaluacion->estado == 'aprobado'
                                        ? 'bg-success-light text-success border border-success'
                                        : ($evaluacion->estado == 'denegado'
                                            ? 'bg-danger-light text-danger border border-danger'
                                            : 'bg-warning-light text-warning border border-warning') }}">
                                            @if ($evaluacion->estado == 'aprobado')
                                                <i class="fas fa-check-circle mr-1"></i>
                                            @elseif($evaluacion->estado == 'denegado')
                                                <i class="fas fa-times-circle mr-1"></i>
                                            @else
                                                <i class="fas fa-clock mr-1"></i>
                                            @endif
                                            {{ strtoupper($evaluacion->estado) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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
    </script>
</x-layout.default>
