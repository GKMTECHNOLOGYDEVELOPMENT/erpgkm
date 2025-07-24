<x-layout.default>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <div class="pt-5">
        <div class="grid grid-cols-1 gap-6">
            <!-- Panel de Registro de Cliente -->
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">Registro de Nuevo Cliente</h5>
                </div>
                <form id="formCliente" class="space-y-5" method="POST" action="{{ route('Seguimiento.store') }}">
                        @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nombre Completo -->
                        <div>
                            <label for="nombre">Nombre Completo <span class="text-danger">*</span></label>
                            <input 
                                id="nombre" 
                                name="nombre" 
                                type="text" 
                                placeholder="Ingrese nombre completo" 
                                class="form-input" 
                                required
                            />
                        </div>

                        <!-- Documento -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="idTipoDocumento">Tipo Documento <span class="text-danger">*</span></label>
                                <select 
                                    id="idTipoDocumento" 
                                    name="idTipoDocumento" 
                                    class="form-select" 
                                    required
                                >
                                    <option value="">Seleccione...</option>
                                    @foreach($tipoDocumentos as $tipo)
                                        <option value="{{ $tipo->idTipoDocumento }}">{{ $tipo->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="documento">N° Documento <span class="text-danger">*</span></label>
                                <input 
                                    id="documento" 
                                    name="documento" 
                                    type="text" 
                                    placeholder="Ingrese documento" 
                                    class="form-input" 
                                    required
                                />
                            </div>
                        </div>

                        <!-- Contacto -->
                        <div>
                            <label for="telefono">Teléfono <span class="text-danger">*</span></label>
                            <input 
                                id="telefono" 
                                name="telefono" 
                                type="text" 
                                placeholder="Ingrese teléfono" 
                                class="form-input" 
                                required
                            />
                        </div>
                        <div>
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input 
                                id="email" 
                                name="email" 
                                type="email" 
                                placeholder="Ingrese email" 
                                class="form-input" 
                                required
                            />
                        </div>

                        <!-- Servicio -->
                        <div class="md:col-span-2">
                            <label for="idservicio">Servicio <span class="text-danger">*</span></label>
                            <select 
                                id="idservicio" 
                                name="idservicio" 
                                class="form-select" 
                                required
                            >
                                <option value="">Seleccione servicio...</option>
                                @foreach($servicios as $servicio)
                                    <option value="{{ $servicio->idServicios }}">{{ $servicio->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end items-center mt-8 gap-4">
                        <button 
                            type="button" 
                            class="btn btn-outline-danger"
                            onclick="window.history.back()"
                        >
                            Cancelar
                        </button>
                        <button 
                            type="submit" 
                            class="btn btn-primary"
                            id="submitBtn"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                            </svg>
                            Registrar Cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const formCliente = document.getElementById('formCliente');
        
        if(formCliente) {
            formCliente.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const url = this.getAttribute('action');
                
                // Mostrar loader si es necesario
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
                submitBtn.disabled = true;
                
                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw response;
                    }
                    return response.json();
                })
                .then(data => {
                    // Mostrar notificación de éxito
                    toastr.success(data.message, 'Éxito');
                    
                    // Limpiar el formulario
                    formCliente.reset();
                    
                    // Resetear selects si es necesario
                    const selects = formCliente.querySelectorAll('select');
                    selects.forEach(select => {
                        select.selectedIndex = 0;
                    });
                })
                .catch(async (error) => {
                    try {
                        // Intenta parsear el error como JSON solo si es una respuesta
                        if (error instanceof Response) {
                            const errorData = await error.json();
                            
                            if (errorData.errors) {
                                // Obtener todos los mensajes de error
                                const errorMessages = Object.values(errorData.errors).flat();
                                
                                // Función para mostrar errores secuencialmente
                                function showErrorsSequentially(index) {
                                    if (index < errorMessages.length) {
                                        toastr.error(errorMessages[index], 'Error de validación', {
                                            onHidden: function() {
                                                // Mostrar el siguiente error cuando se cierre el actual
                                                showErrorsSequentially(index + 1);
                                            }
                                        });
                                    }
                                }
                                
                                // Comenzar a mostrar errores desde el primero
                                showErrorsSequentially(0);
                                
                            } else if (errorData.message) {
                                // Otros mensajes de error del servidor
                                toastr.error(errorData.message, 'Error');
                            } else {
                                toastr.error('Error desconocido', 'Error');
                            }
                        } else {
                            // Error de red u otro tipo de error
                            console.error('Error:', error);
                            toastr.error('Error de conexión o en el servidor', 'Error');
                        }
                    } catch (e) {
                        console.error('Error al procesar el error:', e);
                        toastr.error('Error inesperado', 'Error');
                    }
                })
                .finally(() => {
                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.disabled = false;
                });
            });
        }
        
        // Configuración de Toastr para que no se acumulen las notificaciones
        toastr.options = {
            "closeButton": true,
            "newestOnTop": true,
            "progressBar": true,
            "preventDuplicates": true,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
    });
</script>
</x-layout.default>