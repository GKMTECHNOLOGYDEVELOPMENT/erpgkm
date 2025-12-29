<x-layout.default>
    <div x-data="configData()">
        <script>
            function configData() {
                return {
                    modalOpen: false,
                    currentType: '',
                    currentTable: '',
                    currentData: {
                        'tipo_operacion': @json($tipoOperacion),
                        'tipo_venta': @json($tipoVenta),
                        'tipo_visita': @json($tipoVisita),
                        'tipoalmacen': @json($tipoAlmacen),
                        'tipoarea': @json($tipoArea),
                        'tipoarticulos': @json($tipoArticulos),
                        'tipodocumento': @json($tipoDocumentos),
                        'tipoigv': @json($tipoIgv),
                        'tipomensaje': @json($tipoMensaje),
                        'tipomovimiento': @json($tipoMovimiento),
                        'tipopago': @json($tipoPago),
                        'tipoprioridad': @json($tipoPrioridad),
                        'tiposervicio': @json($tipoServicio),
                        'tiposolicitud': @json($tipoSolicitud),
                        'tipotickets': @json($tipoTickets),
                        'tipotrabajo': @json($tipoTrabajo),
                        'tipousuario': @json($tipoUsuario),
                        'tipousuariosoporte': @json($tipoUsuarioSoporte),
                        'unidad': @json($unidad),
                        'monedas': @json($moneda),
                        'rol': @json($rol),
                        'rol_software': @json($rolSoftware),
                        'sexo': @json($sexo),
                        'importancia': @json($importancia),
                        'fuentes_captacion': @json($fuenteCaptacion)
                    },
                    openModal(type, table) {
                        this.currentType = type;
                        this.currentTable = table;
                        this.modalOpen = true;
                    },
                    closeModal() {
                        this.modalOpen = false;
                        this.currentType = '';
                        this.currentTable = '';
                    },
                    addItem() {
                        const input = document.getElementById(`input-${this.currentTable}`);
                        const value = input.value.trim();
                        if (value !== '') {
                            fetch('{{ route('configuracion.store') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    table: this.currentTable,
                                    column: 'nombre',
                                    value: value
                                })
                            })
                            .then(r => r.json())
                            .then(data => {
                                if (data.success) {
                                    this.currentData[this.currentTable].push(value);
                                    input.value = '';
                                } else {
                                    alert('Error al guardar el dato.');
                                }
                            })
                            .catch(err => {
                                console.error('Error:', err);
                                alert('Error al conectar con el servidor.');
                            });
                        }
                    },
                    deleteItem(index, value) {
                        if (!confirm('¿Estás seguro de eliminar este elemento?')) {
                            return;
                        }
                        
                        fetch('{{ route('configuracion.delete') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                table: this.currentTable,
                                column: 'nombre',
                                value: value
                            })
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                this.currentData[this.currentTable].splice(index, 1);
                            } else {
                                alert('Error al eliminar el dato.');
                            }
                        })
                        .catch(err => {
                            console.error('Error:', err);
                            alert('Error al conectar con el servidor.');
                        });
                    }
                };
            }
        </script>
        <div>
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>Configuración</span>
                </li>
            </ul>
        </div>

        <div class="panel mt-6">
            <!-- Encabezado -->
            <div class="md:flex md:justify-between md:items-center mb-5 gap-3">
                <h2 class="text-lg font-bold">CONFIGURACIÓN</h2>
            </div>

            <!-- Tabla -->
            <table id="configTable"
                class="table-auto w-full border-collapse border border-gray-300 text-center rounded-lg shadow-md overflow-hidden">
                <thead class="bg-gradient-to-r from-blue-500 to-blue-700">
                    <tr>
                        <th class="w-1/2 px-4 py-2 border text-center font-bold">Tipos</th>
                        <th class="w-1/2 px-4 py-2 border text-center font-bold">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([
                        'Tipo Operación' => 'tipo_operacion',
                        'Tipo Venta' => 'tipo_venta',
                        'Tipo Visita' => 'tipo_visita',
                        'Tipo Almacén' => 'tipoalmacen',
                        'Tipo Área' => 'tipoarea',
                        'Tipo Artículos' => 'tipoarticulos',
                        'Tipo Documentos' => 'tipodocumento',
                        'Tipo IGV' => 'tipoigv',
                        'Tipo Mensaje' => 'tipomensaje',
                        'Tipo Movimiento' => 'tipomovimiento',
                        'Tipo Pago' => 'tipopago',
                        'Tipo Prioridad' => 'tipoprioridad',
                        'Tipo Servicio' => 'tiposervicio',
                        'Tipo Solicitud' => 'tiposolicitud',
                        'Tipo Tickets' => 'tipotickets',
                        'Tipo Trabajo' => 'tipotrabajo',
                        'Tipo Usuario' => 'tipousuario',
                        'Tipo Usuario Soporte' => 'tipousuariosoporte',
                        'Unidad' => 'unidad',
                        'Moneda' => 'monedas',
                        'Rol' => 'rol',
                        'Rol Software' => 'rol_software',
                        'Sexo' => 'sexo',
                        'Importancia' => 'importancia',
                        'Fuente Captación' => 'fuentes_captacion',
                    ] as $tipo => $table)
                    <tr>
                        <td class="text-center align-middle">{{ $tipo }}</td>
                        <td class="flex justify-center align-middle">
                            <button type="button" class="btn btn-primary btn-sm m-1"
                                @click="openModal('{{ $tipo }}', '{{ $table }}')">
                                Configurar
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Modal Dinámico -->
        <div x-show="modalOpen" x-cloak>
            <div class="fixed inset-0 bg-[black]/60 z-[999] overflow-y-auto">
                <div class="flex items-start justify-center min-h-screen px-4" @click.self="closeModal()">
                    <div class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8 animate__animated animate__zoomInUp">
                        <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                            <h5 class="font-bold text-lg">Configurar <span x-text="currentType"></span></h5>
                            <button type="button" class="text-white-dark hover:text-dark" @click="closeModal()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </div>

                        <form class="p-5 space-y-4" @submit.prevent="addItem()">
                            <div>
                                <label :for="'input-' + currentTable" class="block text-sm font-medium">Nombre</label>
                                <input type="text" :id="'input-' + currentTable" class="form-input w-full"
                                    placeholder="Ingrese el nombre" required>
                            </div>

                            <div class="flex justify-end items-center mb-4">
                                <button type="button" class="btn btn-outline-danger" @click="closeModal()">Cancelar</button>
                                <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">Agregar</button>
                            </div>

                            <div class="mt-6">
                                <h4 class="font-bold mb-2">Datos Guardados:</h4>
                                <div class="max-h-64 overflow-y-auto">
                                    <ul>
                                        <template x-for="(item, index) in currentData[currentTable]" :key="index">
                                            <li class="flex justify-between items-center rounded mb-2 w-full bg-gray-50 dark:bg-gray-800 p-2">
                                                <input type="text"
                                                    class="form-input w-full border-none bg-transparent focus:ring-0"
                                                    :value="item" readonly>
                                                <button type="button" class="text-red-500 hover:text-red-700 ml-2"
                                                    @click="deleteItem(index, item)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout.default>