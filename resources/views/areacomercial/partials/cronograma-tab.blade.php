<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h3 class="text-xl font-semibold text-gray-700">Cronograma de Actividades</h3>
        <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            Nueva Actividad
        </button>
    </div>

    @if(count($actividades) > 0)
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @foreach($actividades as $actividad)
                <li>
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-blue-600 truncate">
                                {{ $actividad->titulo }}
                            </p>
                            <div class="ml-2 flex-shrink-0 flex">
                                <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $actividad->estado == 'completado' ? 'bg-green-100 text-green-800' : 
                                       ($actividad->estado == 'en_progreso' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ str_replace('_', ' ', ucfirst($actividad->estado)) }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-2 sm:flex sm:justify-between">
                            <div class="sm:flex">
                                <p class="flex items-center text-sm text-gray-500">
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $actividad->fecha_inicio->format('d M Y') }} - {{ $actividad->fecha_fin->format('d M Y') }}
                                </p>
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                                {{ $actividad->responsable }}
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="text-center py-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h4 class="mt-2 text-sm font-medium text-gray-900">No hay actividades programadas</h4>
            <p class="mt-1 text-sm text-gray-500">Comienza agregando una nueva actividad al cronograma.</p>
        </div>
    @endif
</div>