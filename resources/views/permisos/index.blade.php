<x-layout.default>
    <div x-data="sistemaPermisos()" x-init="init()" class="container mx-auto px-4 py-8">
        <!-- Alertas -->
        <div x-show="alert.show" x-transition x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
             class="mb-4 p-4 rounded-lg" 
             :class="{
                 'bg-green-100 text-green-800': alert.type === 'success',
                 'bg-red-100 text-red-800': alert.type === 'error',
                 'bg-blue-100 text-blue-800': alert.type === 'info'
             }">
            <span x-text="alert.message"></span>
        </div>

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Sistema de Permisos</h1>
            <div class="space-x-3">
                <button @click="activeTab = 'permisos'" 
                        :class="activeTab === 'permisos' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'"
                        class="px-4 py-2 rounded-lg font-medium transition-colors">
                    Gestión de Permisos
                </button>
                <button @click="activeTab = 'combinaciones'" 
                        :class="activeTab === 'combinaciones' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'"
                        class="px-4 py-2 rounded-lg font-medium transition-colors">
                    Combinaciones
                </button>
                <button @click="activeTab = 'asignar'" 
                        :class="activeTab === 'asignar' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'"
                        class="px-4 py-2 rounded-lg font-medium transition-colors">
                    Asignar Permisos
                </button>
            </div>
        </div>

        <!-- Tab: Gestión de Permisos -->
        <div x-show="activeTab === 'permisos'" x-transition class="space-y-6">
            <!-- Formulario Crear/Editar Permiso -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4" x-text="editingPermiso ? 'Editar Permiso' : 'Nuevo Permiso'"></h2>
                <form @submit.prevent="editingPermiso ? updatePermiso() : createPermiso()" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Permiso</label>
                        <input type="text" x-model="permisoForm.nombre" required 
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Módulo</label>
                        <input type="text" x-model="permisoForm.modulo" required 
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <input type="text" x-model="permisoForm.descripcion" 
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="md:col-span-3 flex justify-end space-x-3">
                        <button type="button" @click="cancelEditPermiso()" x-show="editingPermiso" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg">
                            <span x-text="editingPermiso ? 'Actualizar' : 'Crear'"></span> Permiso
                        </button>
                    </div>
                </form>
            </div>

            <!-- Lista de Permisos -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Lista de Permisos</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Módulo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <template x-for="permiso in permisos" :key="permiso.idPermiso">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900" x-text="permiso.nombre"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800" 
                                              x-text="permiso.modulo"></span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-500" x-text="permiso.descripcion || 'Sin descripción'"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <button @click="editPermiso(permiso)" class="text-indigo-600 hover:text-indigo-900">Editar</button>
                                        <button @click="deletePermiso(permiso.idPermiso)" class="text-red-600 hover:text-red-900">Eliminar</button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab: Combinaciones -->
        <div x-show="activeTab === 'combinaciones'" x-transition class="space-y-6">
            <!-- Formulario Crear Combinación -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Nueva Combinación</h2>
                <form @submit.prevent="createCombinacion()" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                        <select x-model="combinacionForm.idRol" required 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Seleccionar Rol</option>
                            <template x-for="rol in roles" :key="rol.idRol">
                                <option :value="rol.idRol" x-text="rol.nombre"></option>
                            </template>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo Usuario</label>
                        <select x-model="combinacionForm.idTipoUsuario" required 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Seleccionar Tipo</option>
                            <template x-for="tipo in tiposUsuario" :key="tipo.idTipoUsuario">
                                <option :value="tipo.idTipoUsuario" x-text="tipo.nombre"></option>
                            </template>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo Área</label>
                        <select x-model="combinacionForm.idTipoArea" required 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Seleccionar Área</option>
                            <template x-for="area in tiposArea" :key="area.idTipoArea">
                                <option :value="area.idTipoArea" x-text="area.nombre"></option>
                            </template>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Personalizado</label>
                        <input type="text" x-model="combinacionForm.nombre_combinacion" 
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                               placeholder="Opcional">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg w-full">
                            Crear Combinación
                        </button>
                    </div>
                </form>
            </div>

            <!-- Lista de Combinaciones -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Combinaciones Existentes</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo Usuario</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo Área</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permisos</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <template x-for="combinacion in combinaciones" :key="combinacion.idCombinacion">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900" x-text="combinacion.nombre_completo"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="combinacion.rol"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="combinacion.tipo_usuario"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="combinacion.tipo_area"></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs" 
                                              x-text="`${combinacion.permisos_count} permisos`"></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <button @click="selectCombinacion(combinacion)" class="text-indigo-600 hover:text-indigo-900">
                                            Asignar Permisos
                                        </button>
                                        <button @click="deleteCombinacion(combinacion.idCombinacion)" class="text-red-600 hover:text-red-900">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab: Asignar Permisos -->
        <div x-show="activeTab === 'asignar'" x-transition class="space-y-6">
            <!-- Seleccionar Combinación -->
            <div class="bg-white shadow-lg rounded-lg p-6" x-show="!selectedCombinacion">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Seleccionar Combinación</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <template x-for="combinacion in combinaciones" :key="combinacion.idCombinacion">
                        <button @click="selectCombinacion(combinacion)" 
                                class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 text-left transition-colors">
                            <div class="font-medium text-gray-900" x-text="combinacion.nombre_completo"></div>
                            <div class="text-sm text-gray-500 mt-1" x-text="`${combinacion.permisos_count} permisos asignados`"></div>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Asignar Permisos a Combinación -->
            <div x-show="selectedCombinacion" class="bg-white shadow-lg rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Asignar Permisos</h2>
                        <p class="text-sm text-gray-600" x-text="selectedCombinacion.nombre_completo"></p>
                    </div>
                    <button @click="selectedCombinacion = null" class="text-gray-600 hover:text-gray-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Lista de Permisos con Checkboxes -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                    <template x-for="permiso in permisos" :key="permiso.idPermiso">
                        <label class="flex items-start p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" :value="permiso.idPermiso" x-model="selectedPermisos" 
                                   class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <div class="ml-3">
                                <span class="text-sm font-medium text-gray-900" x-text="permiso.nombre"></span>
                                <p class="text-xs text-gray-500" x-text="permiso.descripcion"></p>
                                <span class="inline-block mt-1 px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded" 
                                      x-text="permiso.modulo"></span>
                            </div>
                        </label>
                    </template>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button @click="selectedCombinacion = null" class="px-6 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 border border-gray-300 rounded-lg">
                        Cancelar
                    </button>
                    <button @click="guardarPermisos()" class="px-6 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Guardar Permisos
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function sistemaPermisos() {
            return {
                activeTab: 'permisos',
                permisos: [],
                combinaciones: [],
                roles: [],
                tiposUsuario: [],
                tiposArea: [],
                selectedCombinacion: null,
                selectedPermisos: [],
                editingPermiso: null,
                
                permisoForm: {
                    nombre: '',
                    modulo: '',
                    descripcion: ''
                },
                
                combinacionForm: {
                    idRol: '',
                    idTipoUsuario: '',
                    idTipoArea: '',
                    nombre_combinacion: ''
                },
                
                alert: {
                    show: false,
                    type: 'success',
                    message: ''
                },

                async init() {
                    await this.loadData();
                },

                async loadData() {
                    try {
                        const response = await fetch('/permisos/data');
                        const data = await response.json();
                        
                        this.permisos = data.permisos;
                        this.combinaciones = data.combinaciones;
                        this.roles = data.roles;
                        this.tiposUsuario = data.tiposUsuario;
                        this.tiposArea = data.tiposArea;
                    } catch (error) {
                        this.showAlert('Error al cargar datos', 'error');
                    }
                },

                async createPermiso() {
                    try {
                        const response = await fetch('/permisos/permisos', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(this.permisoForm)
                        });

                        const result = await response.json();
                        
                        if (result.success) {
                            this.permisos.push(result.permiso);
                            this.permisoForm = { nombre: '', modulo: '', descripcion: '' };
                            this.showAlert('Permiso creado exitosamente', 'success');
                        } else {
                            throw new Error(result.error);
                        }
                    } catch (error) {
                        this.showAlert('Error al crear permiso: ' + error.message, 'error');
                    }
                },

                editPermiso(permiso) {
                    this.editingPermiso = permiso;
                    this.permisoForm = { ...permiso };
                },

                async updatePermiso() {
                    try {
                        const response = await fetch(`/permisos/permisos/${this.editingPermiso.idPermiso}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(this.permisoForm)
                        });

                        const result = await response.json();
                        
                        if (result.success) {
                            const index = this.permisos.findIndex(p => p.idPermiso === this.editingPermiso.idPermiso);
                            this.permisos[index] = result.permiso;
                            this.cancelEditPermiso();
                            this.showAlert('Permiso actualizado exitosamente', 'success');
                        } else {
                            throw new Error(result.error);
                        }
                    } catch (error) {
                        this.showAlert('Error al actualizar permiso: ' + error.message, 'error');
                    }
                },

                cancelEditPermiso() {
                    this.editingPermiso = null;
                    this.permisoForm = { nombre: '', modulo: '', descripcion: '' };
                },

                async deletePermiso(id) {
                    if (!confirm('¿Está seguro de eliminar este permiso?')) return;

                    try {
                        const response = await fetch(`/permisos/permisos/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        const result = await response.json();
                        
                        if (result.success) {
                            this.permisos = this.permisos.filter(p => p.idPermiso !== id);
                            this.showAlert('Permiso eliminado exitosamente', 'success');
                        } else {
                            throw new Error(result.error);
                        }
                    } catch (error) {
                        this.showAlert('Error al eliminar permiso: ' + error.message, 'error');
                    }
                },

               async createCombinacion() {
    try {
        // Validar que se hayan seleccionado todos los campos
        if (!this.combinacionForm.idRol || !this.combinacionForm.idTipoUsuario || !this.combinacionForm.idTipoArea) {
            this.showAlert('Debe seleccionar Rol, Tipo de Usuario y Tipo de Área', 'error');
            return;
        }

        const response = await fetch('/permisos/combinaciones', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(this.combinacionForm)
        });

        const result = await response.json();
        
        if (result.success) {
            // Agregar la nueva combinación al array
            this.combinaciones.push(result.combinacion);
            
            // Limpiar el formulario
            this.combinacionForm = { 
                idRol: '', 
                idTipoUsuario: '', 
                idTipoArea: '', 
                nombre_combinacion: '' 
            };
            
            this.showAlert('Combinación creada exitosamente', 'success');
        } else {
            throw new Error(result.error);
        }
    } catch (error) {
        this.showAlert('Error al crear combinación: ' + error.message, 'error');
    }
},

                async deleteCombinacion(id) {
                    if (!confirm('¿Está seguro de eliminar esta combinación?')) return;

                    try {
                        const response = await fetch(`/permisos/combinaciones/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        const result = await response.json();
                        
                        if (result.success) {
                            this.combinaciones = this.combinaciones.filter(c => c.idCombinacion !== id);
                            this.showAlert('Combinación eliminada exitosamente', 'success');
                        } else {
                            throw new Error(result.error);
                        }
                    } catch (error) {
                        this.showAlert('Error al eliminar combinación: ' + error.message, 'error');
                    }
                },

                async selectCombinacion(combinacion) {
                    this.selectedCombinacion = combinacion;
                    this.activeTab = 'asignar';
                    
                    try {
                        const response = await fetch(`/permisos/combinaciones/${combinacion.idCombinacion}/permisos`);
                        const result = await response.json();
                        
                        if (result.success) {
                            this.selectedPermisos = result.permisos;
                        } else {
                            throw new Error(result.error);
                        }
                    } catch (error) {
                        this.showAlert('Error al cargar permisos de la combinación', 'error');
                    }
                },

                async guardarPermisos() {
                    try {
                        const response = await fetch(`/permisos/combinaciones/${this.selectedCombinacion.idCombinacion}/permisos`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ permisos: this.selectedPermisos })
                        });

                        const result = await response.json();
                        
                        if (result.success) {
                            // Actualizar el contador en la lista de combinaciones
                            const index = this.combinaciones.findIndex(c => c.idCombinacion === this.selectedCombinacion.idCombinacion);
                            this.combinaciones[index].permisos_count = this.selectedPermisos.length;
                            this.combinaciones[index].permisos = this.selectedPermisos;
                            
                            this.showAlert('Permisos guardados exitosamente', 'success');
                        } else {
                            throw new Error(result.error);
                        }
                    } catch (error) {
                        this.showAlert('Error al guardar permisos: ' + error.message, 'error');
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