<div x-data="{
    documentos: [],
    loading: false,
    tipoDocumento: 'CV',
    userId: {{ $usuario->idUsuario ?? 0 }},

    async loadDocumentos() {
        try {
            this.loading = true;
            const response = await fetch(`/usuario/${this.userId}/documentos`);
            const data = await response.json();
            
            if (data.success) {
                this.documentos = data.documentos;
            } else {
                toastr.error('Error al cargar documentos');
            }
        } catch (error) {
            console.error('Error:', error);
            toastr.error('Error al cargar documentos');
        } finally {
            this.loading = false;
        }
    },

    async uploadDocumento(tipo) {
        const fileInput = document.querySelector(`input[name='${tipo.toLowerCase()}']`);
        const file = fileInput.files[0];
        
        if (!file) {
            toastr.warning('Por favor selecciona un archivo');
            return;
        }

        const formData = new FormData();
        formData.append('tipo_documento', tipo);
        formData.append('archivo', file);

        try {
            this.loading = true;
            const response = await fetch(`/usuario/${this.userId}/documentos/upload`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            const data = await response.json();
            
            if (data.success) {
                toastr.success('Documento subido exitosamente');
                await this.loadDocumentos();
                fileInput.value = ''; // Limpiar input
            } else {
                toastr.error(data.message || 'Error al subir documento');
            }
        } catch (error) {
            console.error('Error:', error);
            toastr.error('Error al subir documento');
        } finally {
            this.loading = false;
        }
    },

    async downloadDocumento(documentoId) {
        window.open(`/usuario/documentos/${documentoId}/download`, '_blank');
    },

    async deleteDocumento(documentoId) {
        if (!confirm('¿Estás seguro de eliminar este documento?')) return;

        try {
            const response = await fetch(`/usuario/documentos/${documentoId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();
            
            if (data.success) {
                toastr.success('Documento eliminado');
                await this.loadDocumentos();
            } else {
                toastr.error(data.message || 'Error al eliminar documento');
            }
        } catch (error) {
            console.error('Error:', error);
            toastr.error('Error al eliminar documento');
        }
    },

    getDocumentosPorTipo(tipo) {
        return this.documentos.filter(doc => doc.tipo_documento === tipo);
    },

    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    },

    init() {
        this.loadDocumentos();
    }
}" x-init="init()">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">
        <!-- CV -->
        <div class="panel space-y-4">
            <h5 class="font-semibold text-lg">Subir CV</h5>
            <p class="text-sm text-gray-500 dark:text-gray-400">Archivos permitidos: .pdf, .doc, .docx</p>
            
            <!-- Lista de CVs existentes -->
            <template x-for="doc in getDocumentosPorTipo('CV')" :key="doc.idDocumento">
                <div class="bg-gray-100 dark:bg-gray-800 p-3 rounded flex justify-between items-center mb-2">
                    <div class="truncate w-2/3">
                        <span class="text-sm block" x-text="doc.nombre_archivo"></span>
                        <span class="text-xs text-gray-500" x-text="formatFileSize(doc.tamano)"></span>
                    </div>
                    <div class="flex space-x-2">
                        <button @click="downloadDocumento(doc.idDocumento)" class="btn btn-outline-primary btn-sm">
                            Descargar
                        </button>
                        <button @click="deleteDocumento(doc.idDocumento)" class="btn btn-outline-danger btn-sm">
                            Eliminar
                        </button>
                    </div>
                </div>
            </template>
            
            <form @submit.prevent="uploadDocumento('CV')" enctype="multipart/form-data" class="space-y-3">
                <input type="file" name="cv" accept=".pdf,.doc,.docx" class="file-input file-input-bordered w-full">
                <button type="submit" class="btn btn-primary w-full" :disabled="loading">
                    <span x-show="loading" class="animate-spin border-2 border-white border-t-transparent rounded-full w-4 h-4 inline-block mr-2"></span>
                    Subir CV
                </button>
            </form>
        </div>

        <!-- DNI -->
        <div class="panel space-y-4">
            <h5 class="font-semibold text-lg">Documento de identidad</h5>
            <p class="text-sm text-gray-500 dark:text-gray-400">Archivos permitidos: .jpg, .png, .pdf</p>
            
            <template x-for="doc in getDocumentosPorTipo('DNI')" :key="doc.idDocumento">
                <div class="bg-gray-100 dark:bg-gray-800 p-3 rounded flex justify-between items-center mb-2">
                    <div class="truncate w-2/3">
                        <span class="text-sm block" x-text="doc.nombre_archivo"></span>
                        <span class="text-xs text-gray-500" x-text="formatFileSize(doc.tamano)"></span>
                    </div>
                    <div class="flex space-x-2">
                        <button @click="downloadDocumento(doc.idDocumento)" class="btn btn-outline-primary btn-sm">
                            Descargar
                        </button>
                        <button @click="deleteDocumento(doc.idDocumento)" class="btn btn-outline-danger btn-sm">
                            Eliminar
                        </button>
                    </div>
                </div>
            </template>
            
            <form @submit.prevent="uploadDocumento('DNI')" enctype="multipart/form-data" class="space-y-3">
                <input type="file" name="dni" accept=".jpg,.jpeg,.png,.pdf" class="file-input file-input-bordered w-full">
                <button type="submit" class="btn btn-primary w-full" :disabled="loading">
                    <span x-show="loading" class="animate-spin border-2 border-white border-t-transparent rounded-full w-4 h-4 inline-block mr-2"></span>
                    Subir DNI
                </button>
            </form>
        </div>

        <!-- Antecedentes Penales -->
        <div class="panel space-y-4">
            <h5 class="font-semibold text-lg">Antecedentes Penales</h5>
            <p class="text-sm text-gray-500 dark:text-gray-400">Solo PDF</p>
            
            <template x-for="doc in getDocumentosPorTipo('PENALES')" :key="doc.idDocumento">
                <div class="bg-gray-100 dark:bg-gray-800 p-3 rounded flex justify-between items-center mb-2">
                    <div class="truncate w-2/3">
                        <span class="text-sm block" x-text="doc.nombre_archivo"></span>
                        <span class="text-xs text-gray-500" x-text="formatFileSize(doc.tamano)"></span>
                    </div>
                    <div class="flex space-x-2">
                        <button @click="downloadDocumento(doc.idDocumento)" class="btn btn-outline-primary btn-sm">
                            Descargar
                        </button>
                        <button @click="deleteDocumento(doc.idDocumento)" class="btn btn-outline-danger btn-sm">
                            Eliminar
                        </button>
                    </div>
                </div>
            </template>
            
            <form @submit.prevent="uploadDocumento('PENALES')" enctype="multipart/form-data" class="space-y-3">
                <input type="file" name="penales" accept=".pdf" class="file-input file-input-bordered w-full">
                <button type="submit" class="btn btn-primary w-full" :disabled="loading">
                    <span x-show="loading" class="animate-spin border-2 border-white border-t-transparent rounded-full w-4 h-4 inline-block mr-2"></span>
                    Subir documento
                </button>
            </form>
        </div>

        <!-- Antecedentes Judiciales -->
        <div class="panel space-y-4">
            <h5 class="font-semibold text-lg">Antecedentes Judiciales</h5>
            <p class="text-sm text-gray-500 dark:text-gray-400">Solo PDF</p>
            
            <template x-for="doc in getDocumentosPorTipo('JUDICIALES')" :key="doc.idDocumento">
                <div class="bg-gray-100 dark:bg-gray-800 p-3 rounded flex justify-between items-center mb-2">
                    <div class="truncate w-2/3">
                        <span class="text-sm block" x-text="doc.nombre_archivo"></span>
                        <span class="text-xs text-gray-500" x-text="formatFileSize(doc.tamano)"></span>
                    </div>
                    <div class="flex space-x-2">
                        <button @click="downloadDocumento(doc.idDocumento)" class="btn btn-outline-primary btn-sm">
                            Descargar
                        </button>
                        <button @click="deleteDocumento(doc.idDocumento)" class="btn btn-outline-danger btn-sm">
                            Eliminar
                        </button>
                    </div>
                </div>
            </template>
            
            <form @submit.prevent="uploadDocumento('JUDICIALES')" enctype="multipart/form-data" class="space-y-3">
                <input type="file" name="judiciales" accept=".pdf" class="file-input file-input-bordered w-full">
                <button type="submit" class="btn btn-primary w-full" :disabled="loading">
                    <span x-show="loading" class="animate-spin border-2 border-white border-t-transparent rounded-full w-4 h-4 inline-block mr-2"></span>
                    Subir documento
                </button>
            </form>
        </div>

        <!-- Otros documentos -->
        <div class="panel space-y-4 col-span-2">
            <h5 class="font-semibold text-lg">Otros documentos</h5>
            <p class="text-sm text-gray-500 dark:text-gray-400">Imágenes o PDFs adicionales</p>
            
            <template x-for="doc in getDocumentosPorTipo('OTROS')" :key="doc.idDocumento">
                <div class="bg-gray-100 dark:bg-gray-800 p-3 rounded flex justify-between items-center mb-2">
                    <div class="truncate w-2/3">
                        <span class="text-sm block" x-text="doc.nombre_archivo"></span>
                        <span class="text-xs text-gray-500" x-text="formatFileSize(doc.tamano)"></span>
                    </div>
                    <div class="flex space-x-2">
                        <button @click="downloadDocumento(doc.idDocumento)" class="btn btn-outline-primary btn-sm">
                            Descargar
                        </button>
                        <button @click="deleteDocumento(doc.idDocumento)" class="btn btn-outline-danger btn-sm">
                            Eliminar
                        </button>
                    </div>
                </div>
            </template>
            
            <form @submit.prevent="uploadDocumento('OTROS')" enctype="multipart/form-data" class="space-y-3">
                <input type="file" name="otros" multiple accept=".pdf,.jpg,.jpeg,.png" class="file-input file-input-bordered w-full">
                <button type="submit" class="btn btn-primary w-full" :disabled="loading">
                    <span x-show="loading" class="animate-spin border-2 border-white border-t-transparent rounded-full w-4 h-4 inline-block mr-2"></span>
                    Subir documentos
                </button>
            </form>
        </div>
    </div>
</div>