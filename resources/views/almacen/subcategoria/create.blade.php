<x-layout.default>
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
            font-size: 0.875rem;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            color: #6b7280;
            font-size: 12px;
            pointer-events: none;
            z-index: 10;
        }

        .select2-container--default .select2-selection--multiple {
            background-color: transparent !important;
            border: none !important;
            border-bottom: 1px solid #e0e6ed !important;
            border-radius: 0;
            padding-left: 5px;
            padding-bottom: 5px;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-bottom: 2px solid #3b82f6;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            color: #000 !important;
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

        /* Ajustes específicos para Select2 */
        .select2-container--default .select2-selection--multiple {
            padding-left: 35px !important;
            min-height: 40px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            padding-left: 0;
        }

        /* Quita el overflow del contenedor externo */
        .select2-container--default .select2-selection--multiple {
            max-height: none;
            overflow: visible;
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
        }

        /* Mantén el scroll solo aquí */
        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            max-height: 80px;
            overflow-y: auto;
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
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 6px !important;
            right: 10px !important;
        }
        .clean-input::placeholder {
            font-size: 0.85rem;
            /* o 0.75rem si lo quieres aún más pequeño */

        }
    </style>

  <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{ route('subcategoria.index') }}" class="text-primary hover:underline">
                    <i class="fas fa-arrow-left mr-1"></i> Subcategorías
                </a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Crear Subcategoría nueva</span>
            </li>
        </ul>
    </div>

     <div class="panel mt-6 p-5 max-w-4xl mx-auto">
        <h2 class="text-xl font-bold mb-5 flex items-center">
            <i class="fas fa-plus-circle text-primary mr-2"></i> Agregar Nueva Subcategoría
        </h2>

        <form id="subcategoriaForm" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nombre -->
                <div class="relative input-with-icon">
                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                    <i class="fas fa-tag input-icon"></i>
                    <input id="nombre" name="nombre" type="text" class="clean-input w-full"
                           placeholder="Nombre de la subcategoría">
                </div>

                <!-- Descripción -->
                <div class="relative input-with-icon md:col-span-2">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                    <i class="fas fa-align-left input-icon"></i>
                    <textarea id="descripcion" name="descripcion" rows="3"
                              class="clean-input w-full resize-none"
                              placeholder="Descripción de la subcategoría"></textarea>
                </div>
            </div>

            <div class="flex justify-end mt-6 gap-4">
                <a href="{{ route('subcategoria.index') }}" class="btn btn-outline-danger flex items-center">
                    <i class="fas fa-times mr-2"></i> Cancelar
                </a>

                <button type="button" id="btnLimpiar" class="btn btn-outline-warning flex items-center">
                    <i class="fas fa-eraser mr-2"></i> Limpiar
                </button>

                <button type="button" id="btnGuardar" class="btn btn-primary flex items-center">
                    <i class="fas fa-save mr-2"></i> Guardar Subcategoría
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
   <script>
        document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("subcategoriaForm");
    const btnGuardar = document.getElementById("btnGuardar");
    const inputNombre = document.getElementById("nombre");

    btnGuardar.addEventListener("click", function () {
        const formData = new FormData(form);

        // Limpiar estado anterior
        inputNombre.classList.remove("border-red-500");

        fetch("/subcategoria/store", {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(async response => {
            if (response.status === 422) {
                const data = await response.json();
                const errors = data.errors;

                if (errors.nombre) {
                    inputNombre.classList.add("border-red-500");
                    toastr.error(errors.nombre[0]); // Muestra solo Toastr
                }

                return;
            }

            return response.json();
        })
        .then(data => {
            if (!data) return;

            if (data.success) {
                toastr.success("Subcategoría guardada correctamente");
                form.reset();
            } else {
                toastr.error(data.message || "Ocurrió un error al guardar la subcategoría.");
                console.error(data);
            }
        })
        .catch(error => {
            toastr.error("Error en la comunicación con el servidor.");
            console.error(error);
        });
    });

    // Botón Limpiar
    document.getElementById("btnLimpiar").addEventListener("click", function () {
        form.reset();
        inputNombre.classList.remove("border-red-500");
    });
});

    </script>
    <script src="{{ asset('assets/js/almacen/subcategoria/subcategoriaValidaciones.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</x-layout.default>
