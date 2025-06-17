<x-layout.default>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    <style>
        .clean-input {
            border: none;
            border-bottom: 1px solid #e0e6ed;
            border-radius: 0;
            padding-left: 35px;
            /* asegúrate de dejar espacio al ícono */
            padding-bottom: 8px;
            padding-top: 8px;
            background-color: transparent;
            height: 40px;
            /* controla la altura si es necesario */
            line-height: 1.25rem;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            color: #6b7280;
            font-size: 14px;
            pointer-events: none;
            z-index: 10;
        }
        .clean-input:focus {
            border-bottom: 2px solid #3b82f6;
            box-shadow: none;
        }
        .select2-container--default .select2-selection--single {
            background-color: transparent !important;
            border: none !important;
            border-bottom: 1px solid #e0e6ed !important;
            border-radius: 0 !important;
            height: 40px !important;
            padding-left: 35px !important;
            display: flex;
            align-items: center;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-bottom: 2px solid #3b82f6;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 6px !important;
            right: 10px !important;
        }


        .file-input-label {
            display: block;
            margin-top: 5px;
            color: #6b7280;
            font-size: 0.875rem;
        }

        /* Estilos para inputs con íconos */
        .input-with-icon {
            position: relative;
            margin-bottom: 1.5rem;
            /* Espacio para mensajes de error */
        }

        .input-with-icon .clean-input {
            padding-left: 35px !important;
            /* Forzar espacio para el ícono */
        }

        .input-with-icon .input-icon {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            z-index: 10;
            pointer-events: none;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: inherit !important;
        }

        /* Estilos para mensajes de error */
        .error-msg,
        .error-msg-duplicado {
            position: absolute;
            bottom: -1.25rem;
            left: 0;
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        /* Estilos para campos inválidos */
        .border-red-500 {
            border-color: #ef4444 !important;
        }

        .clean-input::placeholder {
            font-size: 0.85rem;
            /* o 0.75rem si lo quieres aún más pequeño */

        }
    </style>

    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{ route('ubicaciones.index') }}" class="text-primary hover:underline">
                    <i class="fas fa-arrow-left mr-1"></i> Ubicaciones
                </a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Crear Ubicacion nueva</span>
            </li>
        </ul>
    </div>

    <div class="panel mt-6 p-5 max-w-6x2 mx-auto">
        <h2 class="text-xl font-bold mb-5 flex items-center">
            <i class="fas fa-wrench text-primary mr-2"></i> Agregar Nueva Ubicacion
        </h2>

       <form id="ubicacionForm" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Nombre de la ubicación -->
                <div class="relative">
                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                    <div class="relative mt-1">
                        <i class="fas fa-location-dot input-icon"></i>
                        <input id="nombre" name="nombre" type="text" class="clean-input w-full"
                            placeholder="Ingrese nombre de la ubicación" required>
                    </div>
                </div>

                <!-- Sucursal (Select) -->
                <div class="relative">
                    <label for="idSucursal" class="block text-sm font-medium text-gray-700">Sucursal</label>
                    <div class="relative mt-1">
                        <i class="fas fa-building input-icon"></i>
                        <select id="idSucursal" name="idSucursal" class="select2-single clean-input w-full pl-10" required>
                            <option value="" disabled selected>Seleccione una sucursal</option>
                            @foreach ($sucursales as $sucursal)
                                <option value="{{ $sucursal->idSucursal }}">{{ $sucursal->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end mt-6 gap-4">
                <a href="{{ route('ubicaciones.index') }}" class="btn btn-outline-danger flex items-center">
                    <i class="fas fa-times mr-2"></i> Cancelar
                </a>
                <button type="button" id="btnGuardar" class="btn btn-primary flex items-center">
                    <i class="fas fa-save mr-2"></i> Guardar Ubicacion
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
   <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Inicializar Select2
        $('.select2-single').select2({
            placeholder: "Seleccione una sucursal",
            width: '100%',
            minimumResultsForSearch: 5
        });

        // Envío del formulario por AJAX
        document.getElementById("btnGuardar").addEventListener("click", function () {
            const form = document.getElementById("ubicacionForm");

            // Ejecutar validaciones manuales
            form.dispatchEvent(new Event('submit', {
                cancelable: true,
                bubbles: true
            }));

            // Verificar errores
            const errores = form.querySelectorAll(".error-msg, .error-msg-duplicado");
            if (errores.length > 0) {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
                return;
            }

            const formData = new FormData(form);

            fetch("/ubicaciones/store", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
                .then(response => response.json())
.then(data => {
    if (data.success) {
        toastr.success("Ubicación guardada correctamente");
        form.reset();
        $('#idSucursal').val(null).trigger('change');
    } else {
        if (data.duplicado) {
            toastr.error(data.message);
            document.getElementById("nombre").classList.add("border-red-500");
        } else {
            toastr.error("Error al guardar. Revise los campos.");
        }
    }
})
                .catch(error => {
                    toastr.error("Error en la comunicación con el servidor.");
                    console.error(error);
                });
        });
    });
</script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</x-layout.default>