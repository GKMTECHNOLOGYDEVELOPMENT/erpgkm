<x-layout.default>
<div class="max-w-5xl mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detalles de Solicitud #{{ $solicitud->id_solicitud_asistencia }}</h1>
            <p class="text-sm text-gray-500">Información completa de la solicitud</p>
        </div>
        
        <a href="{{ route('administracion.solicitud-asistencia.index') }}"
           class="px-4 py-2 bg-gray-100 rounded hover:bg-gray-200">
            Volver
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-6">
        {{-- Información básica --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-semibold text-gray-700 mb-2">Información General</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Tipo:</span> {{ $solicitud->tipoSolicitud->nombre_tip }}</p>
                    <p><span class="font-medium">Estado:</span>
                        @php
                            $badge = match($solicitud->estado) {
                                'aprobado' => 'bg-green-100 text-green-800',
                                'denegado' => 'bg-red-100 text-red-800',
                                default => 'bg-yellow-100 text-yellow-800'
                            };
                        @endphp
                        <span class="px-2 py-1 rounded text-xs font-bold {{ $badge }}">
                            {{ strtoupper($solicitud->estado) }}
                        </span>
                    </p>
                    <p><span class="font-medium">Fecha solicitud:</span> 
                        {{ $solicitud->fecha_solicitud->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>
            
            <div>
                <h3 class="font-semibold text-gray-700 mb-2">Rango de Tiempo</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Inicio:</span> 
                        {{ $solicitud->rango_inicio_tiempo->format('d/m/Y H:i') }}
                    </p>
                    <p><span class="font-medium">Final:</span> 
                        {{ $solicitud->rango_final_tiempo->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Tipo educativo --}}
        @if($solicitud->tipoEducacion)
        <div>
            <h3 class="font-semibold text-gray-700 mb-2">Información Educativa</h3>
            <p><span class="font-medium">Tipo educación:</span> {{ $solicitud->tipoEducacion->nombre }}</p>
            
            @if($solicitud->archivos->count() > 0)
                <p class="mt-2">
                    <span class="font-medium">Archivo:</span>
                    <a href="{{ Storage::url($solicitud->archivos->first()->archivo_solicitud) }}" 
                       target="_blank" 
                       class="text-blue-600 hover:underline ml-2">
                        Ver archivo
                    </a>
                </p>
            @endif
        </div>
        @endif

        {{-- Días (si es educativo) --}}
        @if($solicitud->dias->count() > 0)
        <div>
            <h3 class="font-semibold text-gray-700 mb-2">Días Programados</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-2 text-left">Fecha</th>
                            <th class="p-2 text-left">Día</th>
                            <th class="p-2 text-left">Todo el día</th>
                            <th class="p-2 text-left">Entrada</th>
                            <th class="p-2 text-left">Salida</th>
                            <th class="p-2 text-left">Llegada</th>
                            <th class="p-2 text-left">Observación</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($solicitud->dias as $dia)
                        <tr class="border-t">
                            <td class="p-2">{{ $dia->fecha }}</td>
                            <td class="p-2">{{ Carbon\Carbon::parse($dia->fecha)->locale('es')->dayName }}</td>
                            <td class="p-2">{{ $dia->es_todo_el_dia ? 'Sí' : 'No' }}</td>
                            <td class="p-2">{{ $dia->hora_entrada ?? '-' }}</td>
                            <td class="p-2">{{ $dia->hora_salida ?? '-' }}</td>
                            <td class="p-2">{{ $dia->hora_llegada_trabajo ?? '-' }}</td>
                            <td class="p-2">{{ $dia->observacion ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Observación --}}
        @if($solicitud->observacion)
        <div>
            <h3 class="font-semibold text-gray-700 mb-2">Observación</h3>
            <p class="bg-gray-50 p-3 rounded">{{ $solicitud->observacion }}</p>
        </div>
        @endif

        {{-- Historial de evaluaciones --}}
        @if($solicitud->evaluaciones->count() > 0)
        <div>
            <h3 class="font-semibold text-gray-700 mb-2">Historial de Evaluaciones</h3>
            <div class="space-y-3">
                @foreach($solicitud->evaluaciones as $evaluacion)
                <div class="border-l-4 {{ $evaluacion->estado == 'aprobado' ? 'border-green-500' : ($evaluacion->estado == 'denegado' ? 'border-red-500' : 'border-yellow-500') }} pl-4 py-2">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="font-medium">{{ $evaluacion->usuario->name ?? 'Usuario' }}</span>
                            <span class="text-sm text-gray-500 ml-2">
                                {{ $evaluacion->fecha->format('d/m/Y H:i') }}
                            </span>
                        </div>
                        <span class="px-2 py-1 rounded text-xs font-bold 
                            {{ $evaluacion->estado == 'aprobado' ? 'bg-green-100 text-green-800' : 
                               ($evaluacion->estado == 'denegado' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ strtoupper($evaluacion->estado) }}
                        </span>
                    </div>
                    @if($evaluacion->comentario)
                        <p class="mt-1 text-gray-600">{{ $evaluacion->comentario }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
</x-layout.default>