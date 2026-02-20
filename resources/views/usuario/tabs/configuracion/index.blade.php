<!-- PRIMERO: Agrega este CDN de Font Awesome en tu head o antes del código -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<!-- CÓDIGO ACTUALIZADO -->
<div x-data="{
    documentos: [],
    loading: false,
    userId: {{ $usuario->idUsuario ?? 0 }},
    
    // Tipos de documentos según tu tabla
    tiposDocumentos: [
        { id: 'CV', nombre: 'Currículum Vitae', acepta: '.pdf,.doc,.docx', icono: 'fa-solid fa-file-lines' },
        { id: 'PENALES', nombre: 'Certificado de antecedentes policiales', acepta: '.pdf', icono: 'fa-solid fa-gavel' },
        { id: 'DOMICILIO', nombre: 'Declaración Jurada de domicilio', acepta: '.pdf,.jpg,.jpeg,.png', icono: 'fa-solid fa-house' },
        { id: 'DNI', nombre: 'Copia de DNI Vigente', acepta: '.pdf,.jpg,.jpeg,.png', icono: 'fa-solid fa-id-card' },
        { id: 'TRABAJOS', nombre: 'Certificados de trabajos anteriores', acepta: '.pdf,.doc,.docx', icono: 'fa-solid fa-briefcase' },
        { id: 'MATRIMONIO', nombre: 'Partida de Matrimonio u otros', acepta: '.pdf,.jpg,.jpeg,.png', icono: 'fa-solid fa-ring' },
        { id: 'VACUNACION', nombre: 'Cartilla de Vacunación', acepta: '.pdf,.jpg,.jpeg,.png', icono: 'fa-solid fa-syringe' },
        { id: 'ESTUDIOS', nombre: 'Certificados de estudios técnicos u otros', acepta: '.pdf,.doc,.docx,.jpg,.jpeg,.png', icono: 'fa-solid fa-graduation-cap' },
        { id: 'DNI_HIJOS', nombre: 'Copia de DNI de hijos', acepta: '.pdf,.jpg,.jpeg,.png', icono: 'fa-solid fa-child' }
    ],

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

    async uploadDocumento(tipo, event) {
        const fileInput = event.target.closest('form').querySelector('input[type=file]');
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
                toastr.success('✅ Documento subido exitosamente');
                await this.loadDocumentos();
                fileInput.value = '';
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

    const result = await Swal.fire({
        title: 'Eliminar Documento',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-xl'
        }
    });

    if (!result.isConfirmed) return;

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

            await Swal.fire({
                title: 'Eliminado',
                text: 'El documento fue eliminado correctamente.',
                icon: 'success',
                confirmButtonColor: '#10b981',
                customClass: {
                    popup: 'rounded-xl'
                }
            });

            await this.loadDocumentos();

        } else {
            toastr.error(data.message || 'Error al eliminar documento');
        }

    } catch (error) {
        console.error(error);
        toastr.error('Error al eliminar documento');
    }
},

    async viewDocumento(documentoId) {
        window.open(`/usuario/documentos/${documentoId}/view`, '_blank');
    },

    getDocumentosPorTipo(tipo) {
        return this.documentos
            .filter(doc => doc.tipo_documento === tipo)
            .sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    },

    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    },

    getFileIcon(fileName) {
        const ext = fileName.split('.').pop().toLowerCase();
        const icons = {
            pdf: 'fa-solid fa-file-pdf text-red-500',
            doc: 'fa-solid fa-file-word text-blue-500',
            docx: 'fa-solid fa-file-word text-blue-500',
            jpg: 'fa-solid fa-file-image text-green-500',
            jpeg: 'fa-solid fa-file-image text-green-500',
            png: 'fa-solid fa-file-image text-green-500',
            default: 'fa-solid fa-file text-gray-500'
        };
        return icons[ext] || icons.default;
    },

    getEstadoDocumento(tipo) {
        const docs = this.getDocumentosPorTipo(tipo);
        if (docs.length > 0) {
            return {
                class: 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
                texto: `${docs.length} documento(s) subido(s)`,
                icono: 'fa-solid fa-check-circle'
            };
        }
        return {
            class: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100',
            texto: 'Pendiente',
            icono: 'fa-solid fa-clock'
        };
    },

    init() {
        this.loadDocumentos();
    }
}" x-init="init()" class="space-y-6">

    <!-- Grid de documentos por categoría -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <template x-for="tipo in tiposDocumentos" :key="tipo.id">
            <div class="panel hover:shadow-lg transition-shadow" 
                 x-bind:id="'doc-' + tipo.id">
                
                <!-- Cabecera del documento -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="text-2xl text-blue-600 dark:text-blue-400">
                            <i :class="tipo.icono"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-base" x-text="tipo.nombre"></h4>
                            <p class="text-xs text-gray-500">Formatos: <span x-text="tipo.acepta"></span></p>
                        </div>
                    </div>
                    <span x-show="getDocumentosPorTipo(tipo.id).length > 0" 
                          class="bg-green-500 text-white text-xs px-2 py-1 rounded-full flex items-center gap-1"
                          x-text="getDocumentosPorTipo(tipo.id).length">
                    </span>
                </div>

                <!-- Lista de documentos subidos -->
                <div class="space-y-2 max-h-60 overflow-y-auto mb-4 custom-scrollbar">
                    <template x-for="doc in getDocumentosPorTipo(tipo.id)" :key="doc.idDocumento">
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2 truncate">
                                    <i :class="getFileIcon(doc.nombre_archivo) + ' text-lg'"></i>
                                    <div class="truncate">
                                        <span class="text-sm font-medium block truncate" 
                                              x-text="doc.nombre_archivo.length > 25 ? 
                                                     doc.nombre_archivo.substring(0, 25) + '...' : 
                                                     doc.nombre_archivo">
                                        </span>
                                        <span class="text-xs text-gray-500" 
                                              x-text="formatFileSize(doc.tamano) + ' • ' + 
                                                     (doc.created_at ? new Date(doc.created_at).toLocaleDateString('es-ES') : '')">
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1">
                                    <!-- Botón VER - btn-info -->
                                    <button @click="viewDocumento(doc.idDocumento)" 
                                            class="btn btn-info btn-xs"
                                            title="Ver documento">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    
                                    <!-- Botón DESCARGAR - btn-success -->
                                    <button @click="downloadDocumento(doc.idDocumento)" 
                                            class="btn btn-success btn-xs"
                                            title="Descargar">
                                        <i class="fa-solid fa-download"></i>
                                    </button>
                                    
                                    <!-- Botón ELIMINAR - btn-danger -->
                                    <button @click="deleteDocumento(doc.idDocumento)" 
                                            class="btn btn-danger btn-xs"
                                            title="Eliminar">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                    
                    <!-- Mensaje cuando no hay documentos -->
                    <div x-show="getDocumentosPorTipo(tipo.id).length === 0" 
                         class="text-center py-8 text-gray-500">
                        <i class="fa-solid fa-folder-open text-4xl block mb-2"></i>
                        <p class="text-sm">No hay documentos subidos</p>
                    </div>
                </div>

                <!-- Formulario de subida -->
                <form @submit.prevent="uploadDocumento(tipo.id, $event)" 
                      enctype="multipart/form-data" 
                      class="space-y-3 border-t dark:border-gray-700 pt-4">
                    <div class="relative">
                        <input type="file" 
                               :name="tipo.id.toLowerCase()" 
                               :accept="tipo.acepta"
                               class="file-input file-input-bordered w-full text-sm"
                               :disabled="loading">
                    </div>
                    <button type="submit" 
                            class="btn btn-primary w-full flex items-center justify-center gap-2" 
                            :disabled="loading">
                        <i x-show="!loading" class="fa-solid fa-cloud-upload-alt"></i>
                        <i x-show="loading" class="fa-solid fa-spinner fa-spin"></i>
                        <span x-show="!loading">Subir documento</span>
                        <span x-show="loading">Subiendo...</span>
                    </button>
                </form>
            </div>
        </template>
    </div>
</div>
