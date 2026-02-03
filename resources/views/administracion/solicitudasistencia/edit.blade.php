<x-layout.default>
<div class="max-w-5xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Editar Solicitud</h1>

    <form method="POST"
          action="{{ route('administracion.solicitud-asistencia.update', $solicitud->id_solicitud_asistencia) }}"
          enctype="multipart/form-data"
          class="bg-white p-6 rounded-xl space-y-6 border">
        @csrf
        @method('PUT')

        {{-- ERRORES --}}
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded">
                <ul class="list-disc ml-5 text-sm">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- TIPO --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="font-semibold text-sm">Tipo de solicitud</label>
                <select id="tipoSolicitud"
                        name="id_tipo_solicitud"
                        class="w-full rounded border mt-1"
                        required>
                    @foreach($tipos as $t)
                        <option value="{{ $t->id_tipo_solicitud }}"
                                data-nombre="{{ $t->nombre_tip }}"
                                @selected($solicitud->id_tipo_solicitud == $t->id_tipo_solicitud)>
                            {{ $t->nombre_tip }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div id="boxTipoEducacion" class="hidden">
                <label class="font-semibold text-sm">Tipo de educación</label>
                <select name="id_tipo_educacion" class="w-full rounded border mt-1">
                    <option value="">Seleccione…</option>
                    @foreach($tiposEducacion as $te)
                        <option value="{{ $te->id_tipo_educacion }}"
                                @selected($solicitud->id_tipo_educacion == $te->id_tipo_educacion)>
                            {{ $te->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- RANGO DATETIME --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="font-semibold text-sm">Rango inicio</label>
                <input type="datetime-local"
                       name="rango_inicio_tiempo"
                       value="{{ \Carbon\Carbon::parse($solicitud->rango_inicio_tiempo)->format('Y-m-d\TH:i') }}"
                       class="w-full rounded border mt-1"
                       required>
            </div>
            <div>
                <label class="font-semibold text-sm">Rango final</label>
                <input type="datetime-local"
                       name="rango_final_tiempo"
                       value="{{ \Carbon\Carbon::parse($solicitud->rango_final_tiempo)->format('Y-m-d\TH:i') }}"
                       class="w-full rounded border mt-1"
                       required>
            </div>
        </div>

        {{-- OBS --}}
        <div>
            <label class="font-semibold text-sm">Observación</label>
            <textarea name="observacion"
                      rows="3"
                      class="w-full rounded border mt-1">{{ old('observacion', $solicitud->observacion) }}</textarea>
        </div>

        {{-- LICENCIA MÉDICA --}}
        <div id="boxLicenciaMedica"
             class="hidden border border-red-200 bg-red-50 rounded-xl p-4 space-y-2">
            <label class="font-semibold text-sm text-red-700">
                Reemplazar foto de licencia médica
            </label>
            <input type="file"
                   name="imagen_licencia"
                   accept="image/*"
                   class="w-full border rounded p-2 bg-white">
        </div>

     {{-- EDUCATIVO --}}
<div id="boxEducativo"
     class="hidden border border-blue-200 bg-blue-50 rounded-xl p-4 space-y-4">

    {{-- Mostrar archivo existente --}}
    @if($solicitud->archivos->count() > 0)
    <div class="bg-white p-3 rounded border">
        <label class="font-semibold text-sm">Archivo actual:</label>
        <p class="text-sm text-gray-600">
            {{ $solicitud->archivos->first()->archivo_solicitud }}
        </p>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="font-semibold text-sm">
                {{ $solicitud->archivos->count() > 0 ? 'Reemplazar archivo educativo' : 'Nuevo archivo educativo' }}
            </label>
            <input type="file" name="archivo" class="w-full mt-1">
            <p class="text-xs text-gray-500 mt-1">
                {{ $solicitud->archivos->count() > 0 ? 'Dejar vacío para mantener el archivo actual' : '' }}
            </p>
        </div>
        <div>
            <label class="font-semibold text-sm">Imagen opcional</label>
            <input type="file" name="imagen_opcional" class="w-full mt-1">
        </div>
    </div>

    <table class="min-w-full text-sm bg-white rounded border">
        <thead class="bg-blue-100">
        <tr>
            <th class="p-2">Día</th>
            <th class="p-2">Todo el día</th>
            <th class="p-2">Entrada</th>
            <th class="p-2">Salida</th>
            <th class="p-2">Llegada</th>
            <th class="p-2">Obs.</th>
        </tr>
        </thead>
        <tbody id="diasEducativoBody"></tbody>
    </table>
</div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('administracion.solicitud-asistencia.index') }}"
               class="px-4 py-2 bg-gray-100 rounded">Cancelar</a>

            <button class="px-5 py-2 bg-blue-600 text-white rounded">
                Actualizar
            </button>
        </div>
    </form>
</div>

{{-- DATA PARA JS --}}
<script>
    window.__DIAS_EDIT__ = @json($diasEdit);
</script>


<script src="{{ asset('assets/js/solicitudasistencia/formedit.js') }}"></script>
</x-layout.default>
