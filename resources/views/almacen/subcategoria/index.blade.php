<x-layout.default>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .panel { overflow: visible !important; }
        #myTable1 { min-width: 1000px; }
        .dataTables_length select { appearance: none; padding-right: 1.5rem; }
    </style>
    <div x-data="multipleTable">
        <div>
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li><a href="javascript:;" class="text-primary hover:underline">Almacen</a></li>
                <li class="before:content-['/'] ltr:before:mr-1"><span>Subcategorías</span></li>
            </ul>
        </div>
        <div class="panel mt-6">
            <div class="md:absolute md:top-5 md:left-5">
                <div class="flex flex-wrap items-center gap-2 mb-5">
                @if(\App\Helpers\PermisoHelper::tienePermiso('AGREGAR SUB CATEGORIA'))
                    <a href="{{ route('subcategoria.create') }}" class="btn btn-primary btn-sm">Agregar</a>
                @endif
                </div>
            </div>
            <table id="myTable1" class="w-full table whitespace-nowrap">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('assets/js/almacen/subcategoria/subcategoria.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>



    <script>
        window.permisos = {
            puedeEditar: {{ \App\Helpers\PermisoHelper::tienePermiso('EDITAR SUB CATEGORIA') ? 'true' : 'false' }},
            puedeEliminar: {{ \App\Helpers\PermisoHelper::tienePermiso('ELIMINAR SUB CATEGORIA') ? 'true' : 'false' }}
        };
    </script>
</x-layout.default>
