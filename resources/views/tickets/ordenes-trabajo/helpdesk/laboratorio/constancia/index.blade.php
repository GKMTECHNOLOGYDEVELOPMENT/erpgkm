<div x-data="invoiceAdd">

    <form id="constanciaForm" x-data="constanciaForm" @submit.prevent="submitForm">
        <div class="flex xl:flex-row flex-col gap-2.5">
            <div class="panel px-0 flex-1 py-6 ltr:xl:mr-6 rtl:xl:ml-6">
                <div class="flex justify-between flex-wrap px-4">
                    <div class="mb-6 lg:w-1/2 w-full">
                        <div class="flex items-center text-black dark:text-white shrink-0">
                            <img src="/assets/images/auth/profile.png" alt="image" class="w-14" />
                        </div>
                        <div class="space-y-1 mt-6 text-gray-500 dark:text-gray-400">
                            <div>Av. Santa Elvira E Mz. B Lote 8 Urbanización San Elias - Los Olivos</div>
                            <div>atencionalcliente@gkmtechnology.com.pe</div>
                            <div>0800-80142</div>
                            <input type="hidden" id="ticketId" value="{{ $id }}">

                        </div>
                    </div>



                    <div class="lg:w-1/2 w-full lg:max-w-fit">
                        <div class="flex items-center">
                            <label for="nrticket" class="flex-1 ltr:mr-2 rtl:ml-2 mb-0">Numero Ticket</label>
                            <input id="nrticket" type="text" name="nrticket" class="form-input lg:w-[250px] w-2/3"
                                placeholder="#8801" value="{{ $orden->numero_ticket }}" />
                        </div>
                        <div class="flex items-center mt-4">
                            <label for="tipo" class="flex-1 ltr:mr-2 rtl:ml-2 mb-0">Tipo de Entrega</label>
                            <input id="tipo" type="text" name="tipo" class="form-input lg:w-[250px] w-2/3"
                                placeholder="Constancia de Entrega" value="Constancia de Entrega" />
                        </div>

                        <div class="flex items-center mt-4">
                            <label for="fechacompra" class="flex-1 ltr:mr-2 rtl:ml-2 mb-0">Fecha Compra</label>
                            <input id="fechacompra" type="date" name="fechacompra"
                                class="form-input lg:w-[250px] w-2/3"
                                value="{{ \Carbon\Carbon::parse($orden->fechaCompra)->format('Y-m-d') }}" />
                        </div>
                    </div>
                </div>
                <hr class="border-[#e0e6ed] dark:border-[#1b2e4b] my-6">
                <div class="mt-8 px-4">
                    <div class="flex justify-between lg:flex-row flex-col">
                        <div class="lg:w-1/2 w-full ltr:lg:mr-6 rtl:lg:ml-6 mb-6">
                            <div class="text-lg font-semibold">Datos de cliente</div>
                            <div class="mt-4 flex items-center">
                                <label for="nombrecliente" class="ltr:mr-2 rtl:ml-2 w-1/3 mb-0">Nombre</label>
                                <input id="nombrecliente" type="text" name="nombrecliente" class="form-input flex-1"
                                    value="{{ optional($orden->cliente)->nombre }}" placeholder="Enter Name" />
                            </div>

                            <div class="mt-4 flex items-center">
                                <label for="emailcliente" class="ltr:mr-2 rtl:ml-2 w-1/3 mb-0">Email</label>
                                <input id="emailcliente" type="email" name="emailcliente" class="form-input flex-1"
                                    value="{{ optional($orden->cliente)->email }}" placeholder="Enter Email" />
                            </div>
                            <div class="mt-4 flex items-center">
                                <label for="direccioncliente" class="ltr:mr-2 rtl:ml-2 w-1/3 mb-0">Direccion</label>
                                <input id="direccioncliente" type="text" name="direccioncliente"
                                    class="form-input flex-1" value="{{ optional($orden->cliente)->direccion }}"
                                    placeholder="Enter Address" />
                            </div>
                            <div class="mt-4 flex items-center">
                                <label for="telefonocliente" class="ltr:mr-2 rtl:ml-2 w-1/3 mb-0">Telefono</label>
                                <input id="telefonocliente" type="text" name="telefonocliente"
                                    class="form-input flex-1" value="{{ optional($orden->cliente)->telefono }}"
                                    placeholder="Enter Phone Number" />
                            </div>
                        </div>

                    </div>
                </div>
                <div class="mt-8 px-4">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Campo de Observaciones -->
                        <div>
                            <label for="observaciones">Observaciones</label>
                            <textarea id="observaciones" x-model="params.observaciones" name="observaciones"
                                class="form-textarea min-h-[130px] w-full" placeholder="Observaciones..."></textarea>
                        </div>

                        <!-- Campo para Subir Fotos -->
                        <div>
                            <label for="photos">Fotos Adjuntas</label>
                            <div class="mt-1 flex items-center">
                                <input type="file" id="photos" name="photos[]" multiple
                                    class="form-input file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0
                    file:text-sm file:font-semibold file:bg-primary/10 file:text-primary
                    hover:file:bg-primary/20"
                                    accept="image/*" @change="handleFileUpload">
                            </div>

                            <!-- Vista previa de imágenes existentes y nuevas -->
                            <template x-if="params.fotosExistentes.length > 0 || params.photos.length > 0">
                                <div class="mt-4 grid grid-cols-3 gap-2">
                                    <!-- Fotos existentes -->
                                    <template x-for="(foto, index) in params.fotosExistentes"
                                        :key="'existente-' + index">
                                        <div class="relative">
                                            <img :src="foto.preview"
                                                class="h-24 w-full rounded object-cover border">
                                            <button type="button" @click="eliminarFotoExistente(index)"
                                                class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center">
                                                ×
                                            </button>
                                            <div
                                                class="absolute bottom-0 left-0 bg-black bg-opacity-50 text-white text-xs p-1 w-full">
                                                Existente
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Fotos nuevas -->
                                    <template x-for="(photo, index) in params.photos" :key="'nueva-' + index">
                                        <div class="relative">
                                            <img :src="photo.preview"
                                                class="h-24 w-full rounded object-cover border">
                                            <button type="button" @click="removePhoto(index)"
                                                class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center">
                                                ×
                                            </button>
                                            <div
                                                class="absolute bottom-0 left-0 bg-black bg-opacity-50 text-white text-xs p-1 w-full">
                                                Nueva
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>


            </div>


            <div class="xl:w-96 w-full xl:mt-0 mt-6">

                <div class="panel">
                    <div class="grid xl:grid-cols-1 lg:grid-cols-4 sm:grid-cols-2 grid-cols-1 gap-4">
                        <button type="submit" class="btn btn-success w-full gap-2">

                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2 shrink-0">
                                <path
                                    d="M3.46447 20.5355C4.92893 22 7.28595 22 12 22C16.714 22 19.0711 22 20.5355 20.5355C22 19.0711 22 16.714 22 12C22 11.6585 22 11.4878 21.9848 11.3142C21.9142 10.5049 21.586 9.71257 21.0637 9.09034C20.9516 8.95687 20.828 8.83317 20.5806 8.58578L15.4142 3.41944C15.1668 3.17206 15.0431 3.04835 14.9097 2.93631C14.2874 2.414 13.4951 2.08581 12.6858 2.01515C12.5122 2 12.3415 2 12 2C7.28595 2 4.92893 2 3.46447 3.46447C2 4.92893 2 7.28595 2 12C2 16.714 2 19.0711 3.46447 20.5355Z"
                                    stroke="currentColor" stroke-width="1.5" />
                                <path
                                    d="M17 22V21C17 19.1144 17 18.1716 16.4142 17.5858C15.8284 17 14.8856 17 13 17H11C9.11438 17 8.17157 17 7.58579 17.5858C7 18.1716 7 19.1144 7 21V22"
                                    stroke="currentColor" stroke-width="1.5" />
                                <path opacity="0.5" d="M7 8H13" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" />
                            </svg>
                            Guardar </button>





                        <button type="button" @click="descargarPDF" class="btn btn-secondary w-full gap-2">

                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2 shrink-0">
                                <path opacity="0.5"
                                    d="M17 9.00195C19.175 9.01406 20.3529 9.11051 21.1213 9.8789C22 10.7576 22 12.1718 22 15.0002V16.0002C22 18.8286 22 20.2429 21.1213 21.1215C20.2426 22.0002 18.8284 22.0002 16 22.0002H8C5.17157 22.0002 3.75736 22.0002 2.87868 21.1215C2 20.2429 2 18.8286 2 16.0002L2 15.0002C2 12.1718 2 10.7576 2.87868 9.87889C3.64706 9.11051 4.82497 9.01406 7 9.00195"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                <path d="M12 2L12 15M12 15L9 11.5M12 15L15 11.5" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            Download </button>
                    </div>
                </div>
            </div>



        </div>
    </form>
</div>

<script>
    document.addEventListener('alpine:init', () => {
            Alpine.data('invoiceAdd', () => ({
        init() {
            console.log('invoiceAdd inicializado');
        }
    }));
        Alpine.data('constanciaForm', () => ({
            params: {
                nrticket: '{{ $orden->numero_ticket }}',
                tipo: 'Constancia de Entrega',
                fechacompra: '{{ \Carbon\Carbon::parse($orden->fechaCompra)->format('Y-m-d') }}',
                nombrecliente: '{{ optional($orden->cliente)->nombre }}',
                emailcliente: '{{ optional($orden->cliente)->email }}',
                direccioncliente: '{{ optional($orden->cliente)->direccion }}',
                telefonocliente: '{{ optional($orden->cliente)->telefono }}',
                observaciones: '',
                photos: [],
                idticket: document.getElementById('ticketId')?.value || null,
                fotosExistentes: [] // Nuevo array para fotos existentes
            },

            init() {
                // Cargar datos existentes cuando el componente se inicializa
                this.cargarDatosExistente();
            },

            async cargarDatosExistente() {
                const ticketId = this.params.idticket;
                if (!ticketId) return;

                try {
                    const response = await fetch(`/api/constancias/por-ticket/${ticketId}`);
                    const data = await response.json();

                    if (data.success && data.constancia) {
                        // Cargar observaciones
                        this.params.observaciones = data.constancia.observaciones || '';

                        // Cargar fotos existentes
                        if (data.constancia.fotos && data.constancia.fotos.length > 0) {
                            this.params.fotosExistentes = data.constancia.fotos.map(foto => ({
                                id: foto.idfoto,
                                preview: foto
                                .imagen_url, // Asegúrate que tu API devuelva esta URL
                                esExistente: true
                            }));
                        }
                    }
                } catch (error) {
                    console.error('Error cargando datos existentes:', error);
                }
            },


            handleFileUpload(event) {
                const files = event.target.files;
                if (!files.length) return;

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    if (!file.type.match('image.*')) continue;

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.params.photos.push({
                            file: file,
                            preview: e.target.result
                        });
                    };
                    reader.readAsDataURL(file);
                }
            },

            removePhoto(index) {
                this.params.photos.splice(index, 1);
            },

            // Método para eliminar foto existente
            eliminarFotoExistente(index) {
                if (confirm('¿Estás seguro de eliminar esta foto?')) {
                    const fotoId = this.params.fotosExistentes[index].id;
                    this.eliminarFotoDelServidor(fotoId);
                    this.params.fotosExistentes.splice(index, 1);
                }


            },


            async descargarPDF() {
                const id = this.params.idticket;
                if (!id) {
                    Swal.fire('Error', 'ID de ticket no válido', 'error');
                    return;
                }

                window.open(`/constancia/pdf/${id}`, '_blank');
            },

            // Método para eliminar foto del servidor
            async eliminarFotoDelServidor(fotoId) {
                try {
                    const response = await fetch(`/api/constancias/fotos/${fotoId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    });

                    const data = await response.json();
                    if (!response.ok) throw new Error(data.message || 'Error al eliminar');

                    toastr.success('Foto eliminada correctamente');
                } catch (error) {
                    console.error('Error eliminando foto:', error);
                    toastr.error(error.message || 'Error al eliminar foto');
                }
            },



            async submitForm() {
                // 1. Obtener el botón de forma segura usando Alpine.js
                const submitBtn = this.$refs.submitBtn || document.querySelector(
                    '#constanciaForm button[type="submit"]');


                try {
                    // 2. Verificar que el botón existe
                    if (!submitBtn) {
                        console.error('Error: No se encontró el botón de submit');
                        throw new Error('No se puede procesar el formulario');
                    }

                    // 3. Configurar estado de carga
                    submitBtn.innerHTML = '<span class="animate-spin">⏳</span> Guardando...';
                    submitBtn.disabled = true;

                    // 4. Preparar FormData
                    const formData = new FormData();
                    // Agregar IDs de fotos existentes que no fueron eliminadas
                    const fotosExistentesIds = this.params.fotosExistentes.map(f => f.id);
                    formData.append('fotos_existentes', JSON.stringify(fotosExistentesIds));
                    // Agregar campos de texto
                    const campos = {
                        nrticket: this.params.nrticket,
                        tipo: this.params.tipo,
                        fechacompra: this.params.fechacompra,
                        nombrecliente: this.params.nombrecliente,
                        emailcliente: this.params.emailcliente,
                        direccioncliente: this.params.direccioncliente,
                        telefonocliente: this.params.telefonocliente,
                        observaciones: this.params.observaciones,
                        idticket: this.params.idticket
                    };

                    Object.entries(campos).forEach(([key, value]) => {
                        if (value !== undefined && value !== null) {
                            formData.append(key, value);
                        }
                    });

                    // 5. Agregar archivos (fotos)
                    if (Array.isArray(this.params.photos)) {
                        this.params.photos.forEach((photo, index) => {
                            if (photo?.file instanceof File) {
                                formData.append(`photos[${index}]`, photo.file);
                            }
                        });
                    }

                    // 6. Enviar datos al servidor
                    const response = await fetch('{{ route('constancias.store') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    });

                    // 7. Procesar respuesta
                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Error al guardar la constancia');
                    }

                    // 8. Mostrar feedback al usuario
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: data.message || 'Constancia guardada correctamente',
                        timer: 3000
                    });

                    // 9. Resetear formulario
                    this.resetForm();

                } catch (error) {
                    console.error('Error en submitForm:', error);

                    // 10. Mostrar error al usuario
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'Ocurrió un error al guardar',
                        timer: 3000
                    });

                } finally {
                    // 11. Restaurar estado del botón
                    const btn = this.$refs.submitBtn || document.querySelector(
                        '#constanciaForm button[type="submit"]');
                    if (btn) {
                        btn.innerHTML = `
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2 shrink-0">
                    <path d="M3.46447 20.5355C4.92893 22 7.28595 22 12 22C16.714 22 19.0711 22 20.5355 20.5355C22 19.0711 22 16.714 22 12C22 11.6585 22 11.4878 21.9848 11.3142C21.9142 10.5049 21.586 9.71257 21.0637 9.09034C20.9516 8.95687 20.828 8.83317 20.5806 8.58578L15.4142 3.41944C15.1668 3.17206 15.0431 3.04835 14.9097 2.93631C14.2874 2.414 13.4951 2.08581 12.6858 2.01515C12.5122 2 12.3415 2 12 2C7.28595 2 4.92893 2 3.46447 3.46447C2 4.92893 2 7.28595 2 12C2 16.714 2 19.0711 3.46447 20.5355Z" stroke="currentColor" stroke-width="1.5" />
                    <path d="M17 22V21C17 19.1144 17 18.1716 16.4142 17.5858C15.8284 17 14.8856 17 13 17H11C9.11438 17 8.17157 17 7.58579 17.5858C7 18.1716 7 19.1144 7 21V22" stroke="currentColor" stroke-width="1.5" />
                    <path opacity="0.5" d="M7 8H13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                </svg>
                Guardar
            `;
                        btn.disabled = false;
                    }
                }
            },

            resetForm() {
                this.params.observaciones = '';
                this.params.photos = [];
                document.getElementById('photos').value = '';
            }
        }));
    });
</script>
