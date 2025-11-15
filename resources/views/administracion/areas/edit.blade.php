<x-layout.default>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        .cliente-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 8px;
            background: white;
        }

        .cliente-item:last-child {
            margin-bottom: 0;
        }

        .select2-container--default .select2-selection--multiple {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            min-height: 42px;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
    </style>

    <div x-data="editArea">
        <div>
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="{{ route('areas.index') }}" class="text-primary hover:underline">Administración</a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <a href="{{ route('areas.index') }}" class="text-primary hover:underline">Áreas</a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>Editar Área</span>
                </li>
            </ul>
        </div>

        <div class="panel mt-6">
            <!-- Header con botón volver -->
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold">Editar Área: {{ $area->nombre }}</h2>
                <a href="{{ route('areas.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver a Áreas
                </a>
            </div>

            <!-- Formulario de Edición -->
            <form @submit.prevent="submitForm" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Nombre del Área -->
                <div>
                    <label for="nombre" class="block text-sm font-medium mb-2">Nombre del Área</label>
                    <input type="text" id="nombre" x-model="formData.nombre" 
                        class="form-input w-full" 
                        placeholder="Ingrese el nombre del área" 
                        required>
                    <template x-if="errors.nombre">
                        <span class="text-red-500 text-sm" x-text="errors.nombre[0]"></span>
                    </template>
                </div>

                <!-- Clientes Generales -->
                <div>
                    <label for="clientes_generales" class="block text-sm font-medium mb-2">
                        Clientes Generales Asociados
                    </label>
                    <select id="clientes_generales" x-ref="clientesSelect" class="form-select w-full" multiple>
                        <!-- Las opciones se cargarán con JavaScript -->
                    </select>
                    <p class="mt-1 text-xs text-gray-500">
                        Seleccione los clientes generales que pertenecerán a esta área
                    </p>
                    <template x-if="errors.clientes_generales">
                        <span class="text-red-500 text-sm" x-text="errors.clientes_generales[0]"></span>
                    </template>
                </div>

                <!-- Resumen de selección -->
                <div x-show="selectedClientes.length > 0" class="p-4 bg-gray-50 rounded-lg">
                    <h4 class="font-medium text-gray-800 mb-3">
                        <i class="fas fa-users mr-2"></i>
                        Clientes Seleccionados: <span x-text="selectedClientes.length"></span>
                    </h4>
                    <div class="max-h-40 overflow-y-auto space-y-2">
                        <template x-for="cliente in selectedClientes" :key="cliente.id">
                            <div class="flex items-center justify-between bg-white px-3 py-2 rounded border">
                                <span class="text-sm font-medium" x-text="cliente.text"></span>
                                <button type="button" 
                                    @click="removeCliente(cliente.id)"
                                    class="text-red-500 hover:text-red-700 text-sm">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end items-center space-x-3 pt-4 border-t">
                    <a href="{{ route('areas.index') }}" class="btn btn-outline-danger">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary" :disabled="loading">
                        <template x-if="loading">
                            <i class="fas fa-spinner fa-spin mr-2"></i>
                        </template>
                        <template x-if="!loading">
                            <i class="fas fa-save mr-2"></i>
                        </template>
                        Actualizar Área
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('editArea', () => ({
                formData: {
                    nombre: '{{ $area->nombre }}',
                    clientes_generales: {!! json_encode($clientesAsignadosIds) !!}
                },
                selectedClientes: [],
                loading: false,
                errors: {},
                select2Instance: null,
                allClientes: [],

                init() {
                    this.loadClientesGenerales().then(() => {
                        this.initSelect2();
                    });
                },

                async loadClientesGenerales() {
                    try {
                        const response = await fetch('/areas/api/clientes-generales');
                        this.allClientes = await response.json();
                        
                    } catch (error) {
                        console.error('Error cargando clientes:', error);
                        this.showError('Error al cargar la lista de clientes');
                    }
                },

                initSelect2() {
                    // Limpiar el select primero
                    const selectElement = $('#clientes_generales');
                    selectElement.empty();

                    // Llenar con todos los clientes
                    this.allClientes.forEach(cliente => {
                        const option = new Option(
                            cliente.descripcion, 
                            cliente.idClienteGeneral, 
                            false, 
                            false
                        );
                        selectElement.append(option);
                    });

                    // Inicializar Select2
                    this.select2Instance = selectElement.select2({
                        placeholder: 'Seleccione clientes generales',
                        allowClear: true,
                        width: '100%',
                        closeOnSelect: false
                    });

                    // Preseleccionar los clientes ya asignados
                    if (this.formData.clientes_generales && this.formData.clientes_generales.length > 0) {
                        const selectedIds = this.formData.clientes_generales.map(id => id.toString());
                        this.select2Instance.val(selectedIds).trigger('change');
                        this.updateSelectedClientes(selectedIds);
                    }

                    // Escuchar cambios en el select2
                    this.select2Instance.on('change', (e) => {
                        const selectedValues = $(e.target).val() || [];
                        this.updateSelectedClientes(selectedValues);
                    });
                },

                updateSelectedClientes(selectedIds) {
                    const selectedClientesData = selectedIds.map(id => {
                        const cliente = this.allClientes.find(c => c.idClienteGeneral.toString() === id.toString());
                        return {
                            id: id,
                            text: cliente ? cliente.descripcion : 'Cliente no encontrado'
                        };
                    });

                    this.selectedClientes = selectedClientesData;
                    this.formData.clientes_generales = selectedIds;
                },

                removeCliente(clienteId) {
                    const currentValues = this.select2Instance.val() || [];
                    const newValues = currentValues.filter(id => id.toString() !== clienteId.toString());
                    this.select2Instance.val(newValues).trigger('change');
                },

                async submitForm() {
                    this.loading = true;
                    this.errors = {};

                    try {
                        const formData = new FormData();
                        formData.append('nombre', this.formData.nombre);
                        formData.append('_method', 'PUT');

                        // Agregar clientes seleccionados
                        if (this.formData.clientes_generales && this.formData.clientes_generales.length > 0) {
                            this.formData.clientes_generales.forEach(id => {
                                formData.append('clientes_generales[]', id);
                            });
                        }

                        const response = await fetch('{{ route('areas.update', $area->idTipoArea) }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        });

                        // Verificar si la respuesta es JSON
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            const text = await response.text();
                            console.error('Respuesta no JSON:', text.substring(0, 200));
                            throw new Error('El servidor devolvió una respuesta no JSON. Verifica la consola para más detalles.');
                        }

                        const data = await response.json();

                        if (!response.ok) {
                            if (data.errors) {
                                this.errors = data.errors;
                            }
                            throw new Error(data.message || 'Error al actualizar el área');
                        }

                        // Éxito
                        this.showSuccess('Área actualizada exitosamente');
                        
                        setTimeout(() => {
                            window.location.href = '{{ route('areas.index') }}';
                        }, 1500);

                    } catch (error) {
                        console.error('Error completo:', error);
                        this.showError(error.message || 'Error al actualizar el área');
                    } finally {
                        this.loading = false;
                    }
                },

                showSuccess(message) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        alert(message);
                    }
                },

                showError(message) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: message,
                            confirmButtonText: 'Entendido'
                        });
                    } else {
                        alert('Error: ' + message);
                    }
                }
            }));
        });

        // Manejar navegación away con cambios sin guardar
        document.addEventListener('DOMContentLoaded', function() {
            let formChanged = false;
            const form = document.querySelector('form');
            
            if (form) {
                form.addEventListener('input', () => {
                    formChanged = true;
                });

                form.addEventListener('submit', () => {
                    formChanged = false;
                });

                window.addEventListener('beforeunload', (e) => {
                    if (formChanged) {
                        e.preventDefault();
                        e.returnValue = '';
                    }
                });

                document.addEventListener('click', (e) => {
                    if (formChanged && e.target.closest('a') && !e.target.closest('button')) {
                        if (!confirm('Tienes cambios sin guardar. ¿Estás seguro de que quieres salir?')) {
                            e.preventDefault();
                        }
                    }
                });
            }
        });
    </script>
</x-layout.default>