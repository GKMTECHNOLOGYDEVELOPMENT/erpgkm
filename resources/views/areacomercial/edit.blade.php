<x-layout.default>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <div class="pt-5">
        <div class="grid grid-cols-1 gap-6">
            <!-- Panel de Edición de Cliente -->
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">Editar Cliente</h5>
                </div>
                <form id="formClienteEdit" class="space-y-5" method="POST" action="{{ route('Seguimiento.update', $cliente->idCliente) }}">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nombre Completo -->
                        <div>
                            <label for="nombre">Nombre Completo <span class="text-danger">*</span></label>
                            <input 
                                id="nombre" 
                                name="nombre" 
                                type="text" 
                                value="{{ $cliente->nombre }}"
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
                                        <option value="{{ $tipo->idTipoDocumento }}" {{ $cliente->idTipoDocumento == $tipo->idTipoDocumento ? 'selected' : '' }}>
                                            {{ $tipo->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="documento">N° Documento <span class="text-danger">*</span></label>
                                <input 
                                    id="documento" 
                                    name="documento" 
                                    type="text" 
                                    value="{{ $cliente->documento }}"
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
                                value="{{ $cliente->telefono }}"
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
                                value="{{ $cliente->email }}"
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
                                    <option value="{{ $servicio->idServicios }}" {{ $cliente->idservicio == $servicio->idServicios ? 'selected' : '' }}>
                                        {{ $servicio->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end items-center mt-8 gap-4">
                        <a 
                            href="{{ route('Seguimiento.index') }}" 
                            class="btn btn-outline-danger"
                        >
                            Cancelar
                        </a>
                        <button 
                            type="submit" 
                            class="btn btn-primary"
                            id="submitBtn"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h5a2 2 0 012 2v7a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h5v5.586l-1.293-1.293zM9 4a1 1 0 012 0v2H9V4z" />
                            </svg>
                            Actualizar Cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        // Similar al script de create pero con el formulario de edición
        document.addEventListener('DOMContentLoaded', function() {
            const formClienteEdit = document.getElementById('formClienteEdit');
            
            if(formClienteEdit) {
                formClienteEdit.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    const url = this.getAttribute('action');
                    
                    // Mostrar loader
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalBtnText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualizando...';
                    submitBtn.disabled = true;
                    
                    fetch(url, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'X-HTTP-Method-Override': 'PUT' // Para simular PUT con fetch
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw response;
                        }
                        return response.json();
                    })
                    .then(data => {
                        toastr.success(data.message, 'Éxito');
                        // Redirigir después de actualizar (opcional)
                        // window.location.href = "{{ route('Seguimiento.index') }}";
                    })
                    .catch(async (error) => {
                        try {
                            if (error instanceof Response) {
                                const errorData = await error.json();
                                
                                if (errorData.errors) {
                                    Object.values(errorData.errors).flat().forEach(msg => {
                                        toastr.error(msg, 'Error de validación');
                                    });
                                } else if (errorData.message) {
                                    toastr.error(errorData.message, 'Error');
                                }
                            } else {
                                console.error('Error:', error);
                                toastr.error('Error de conexión', 'Error');
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
        });
    </script>
</x-layout.default>