<x-layout.default>
    <div id="unity-root" style="padding:0;margin:0;max-width:100%;">
        <script>
            window.__SOLICITUD_ID__ = @json($idSolicitud);
        </script>

        <iframe
            src="{{ asset('unity_public/index.html') }}#/unity/{{ $idSolicitud }}/solicitud"
            style="width:100%;height:85vh;border:0;display:block;"
            allow="autoplay; fullscreen; xr-spatial-tracking; clipboard-read; clipboard-write"
            allowfullscreen
            referrerpolicy="no-referrer"></iframe>
    </div>
</x-layout.default>
