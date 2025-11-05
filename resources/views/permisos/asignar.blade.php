<x-layout.default>
    <div x-data="asignarPermisos()" class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Asignar Permisos</h1>
            <a href="{{ route('permisos.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>

        <!-- Alertas -->
        <div x-show="alert.show" x-transition x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="mb-4 p-4 rounded-lg" :class="alert.type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
            <span x-text="alert.message"></span>
        </div>

        <!-- Formulario de Asignación -->
        <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Nueva Asignación</h2>
            <form @submit.prevent="submitAsignacion" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Permiso</label>
                    <select x-model="form.idPermiso" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Seleccionar Permiso</option>
                        @foreach($permisos as $permiso)
                        <option value="{{ $permiso->idPermiso }}">{{ $permiso->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                    <select x-model="form.idRol" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Seleccionar Rol</option>
                        @foreach($roles as $rol)
                        <option value="{{ $rol->idRol }}">{{ $rol->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo Usuario</label>
                    <select x-model="form.idTipoUsuario" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Seleccionar Tipo</option>
                        @foreach($tiposUsuario as $tipo)
                        <option value="{{ $tipo->idTipoUsuario }}">{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo Área</label>
                    <select x-model="form.idTipoArea" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Seleccionar Área</option>
                        @foreach($tiposArea as $area)
                        <option value="{{ $area->idTipoArea }}">{{ $area->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-4 flex justify-end">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Asignar Permiso
                    </button>
                </div>
            </form>
        </div>

        <!-- Lista de Asignaciones -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Asignaciones Existentes</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permiso</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo Área</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="asignacion in asignaciones" :key="asignacion.id">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" x-text="asignacion.permiso"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="asignacion.rol"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="asignacion.tipo_usuario"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="asignacion.tipo_area"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button @click="eliminarAsignacion(asignacion.id)" class="text-red-600 hover:text-red-900">Eliminar</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function asignarPermisos() {
            return {
                form: {
                    idPermiso: '',
                    idRol: '',
                    idTipoUsuario: '',
                    idTipoArea: ''
                },
                asignaciones: [],
                alert: {
                    show: false,
                    type: 'success',
                    message: ''
                },
                
                async init() {
                    await this.loadAsignaciones();
                },
                
                async loadAsignaciones() {
                    try {
                        const response = await fetch('/permisos/asignaciones');
                        this.asignaciones = await response.json();
                    } catch (error) {
                        this.showAlert('Error al cargar asignaciones', 'error');
                    }
                },
                
                async submitAsignacion() {
                    if (!this.form.idPermiso) {
                        this.showAlert('Debe seleccionar un permiso', 'error');
                        return;
                    }
                    
                    if (!this.form.idRol && !this.form.idTipoUsuario && !this.form.idTipoArea) {
                        this.showAlert('Debe seleccionar al menos un Rol, Tipo de Usuario o Tipo de Área', 'error');
                        return;
                    }
                    
                    try {
                        const response = await fetch('/permisos/asignar', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(this.form)
                        });
                        
                        if (response.ok) {
                            this.showAlert('Permiso asignado exitosamente', 'success');
                            this.form = {
                                idPermiso: '',
                                idRol: '',
                                idTipoUsuario: '',
                                idTipoArea: ''
                            };
                            await this.loadAsignaciones();
                        } else {
                            throw new Error('Error en la respuesta del servidor');
                        }
                    } catch (error) {
                        this.showAlert('Error al asignar el permiso', 'error');
                    }
                },
                
                async eliminarAsignacion(id) {
                    if (confirm('¿Está seguro de eliminar esta asignación?')) {
                        try {
                            const response = await fetch(`/permisos/asignaciones/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            });
                            
                            const result = await response.json();
                            
                            if (result.success) {
                                this.showAlert('Asignación eliminada exitosamente', 'success');
                                await this.loadAsignaciones();
                            } else {
                                throw new Error(result.error);
                            }
                        } catch (error) {
                            this.showAlert('Error al eliminar la asignación', 'error');
                        }
                    }
                },
                
                showAlert(message, type = 'success') {
                    this.alert = { show: true, message, type };
                    setTimeout(() => {
                        this.alert.show = false;
                    }, 5000);
                }
            }
        }
    </script>
</x-layout.default>