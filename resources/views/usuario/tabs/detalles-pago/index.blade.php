<template x-if="tab === 'payment-details'">
    <div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">
            <div class="panel">
                <h5 class="font-semibold text-lg mb-2">Firma Digital</h5>
                <p>Por favor, firme en el área de abajo para completar el proceso de validación.</p>
                <div class="mb-5 text-center">
                    <!-- Contenedor del Canvas centrado -->
                    <div class="flex justify-center">
                        <div class="w-full max-w-[700px]">
                            <canvas id="signature-pad" class="border border-black mx-auto"></canvas>
                        </div>
                    </div>

                    <!-- Botones centrados -->
                    <div class="mt-4 flex flex-col md:flex-row justify-center gap-3">
                        <button id="clear-btn" class="px-4 py-2 w-full md:w-auto btn btn-warning">
                            Limpiar
                        </button>
                        <button id="save-btn" class="px-4 py-2 w-full md:w-auto btn btn-success">
                            Guardar
                        </button>
                        <button id="refresh-btn" class="px-4 py-2 w-full md:w-auto btn btn-secondary">
                            Refrescar
                        </button>
                    </div>

                    <!-- Mensaje de error también centrado -->
                    <p id="no-signature-message" class="text-red-500 mt-2"></p>
                </div>
            </div>




            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Columna 1: Lista de cuentas bancarias -->
                <div class="panel">
                    <div x-data x-init="$nextTick(() => initPaymentDetails({{ $usuario->idUsuario }}))">
                        <h5 class="font-semibold text-lg mb-4">Cuentas Bancarias</h5>
                        <!-- <p>Changes to your <span class="text-primary">Payment Method</span> information
                will take effect starting with scheduled payment and will be reflected on your
                next invoice.</p> -->
                    </div>
                    <div class="mb-5" id="cuentas-bancarias">
                        <!-- Aquí se cargarán las cuentas bancarias dinámicamente con JS -->
                    </div>
                </div>

                <!-- Columna 2: Formulario de nueva cuenta -->
                <div class="panel">
                    <div class="mb-5">
                        <h5 class="font-semibold text-lg mb-4">Número de cuenta</h5>
                    </div>
                    <div class="mb-5">
                        <form>
                            <div class="mb-5 grid grid-cols-1 gap-4">
                                <!-- Fila 1: Banco -->
                                <div>
                                    <label for="banco">Banco</label>
                                    <select id="banco" class="form-select text-white-dark">
                                        <option selected>Seleccione una Opción</option>
                                        <option value="1">Banco de Crédito del Perú</option>
                                        <option value="2">BBVA Perú</option>
                                        <option value="3">Scotiabank Perú</option>
                                        <option value="4">Interbank</option>
                                        <option value="5">Banco de la Nación</option>
                                        <option value="6">Banco de Comercio</option>
                                        <option value="7">BanBif</option>
                                        <option value="8">Banco Pichincha</option>
                                        <option value="9">Citibank Perú</option>
                                        <option value="10">MiBanco</option>
                                        <option value="11">Banco GNB Perú</option>
                                        <option value="12">Banco Falabella</option>
                                        <option value="13">Banco Ripley</option>
                                        <option value="14">Banco Santander Perú</option>
                                        <option value="15">Alfin Banco</option>
                                        <option value="16">Bank of China</option>
                                        <option value="17">Bci Perú</option>
                                        <option value="18">ICBC Perú Bank</option>
                                    </select>
                                </div>

                                <!-- Fila 2: Tipo de cuenta -->
                                <div>
                                    <label for="payBrand">Tipo de Cuenta Bancaria</label>
                                    <select id="payBrand" class="form-select text-white-dark">
                                        <option selected>Seleccione una Opción</option>
                                        <option value="1">Cuenta número interbancario</option>
                                        <option value="2">Número de cuenta</option>
                                    </select>
                                </div>

                                <!-- Fila 3: Número de cuenta -->
                                <div>
                                    <label for="payNumber">Número de cuenta</label>
                                    <input id="payNumber" type="text" placeholder="Número de cuenta"
                                        class="form-input" />
                                </div>
                            </div>

                            <button type="button" class="btn btn-primary" id="saveBtn">Guardar</button>
                        </form>



                        <script>
                            document.getElementById('payBrand').addEventListener('change', function() {
                                const tipoCuenta = this.value;
                                const numeroCuentaInput = document.getElementById('payNumber');

                                // Limpiar campo de número de cuenta antes de validar
                                numeroCuentaInput.value = '';
                                numeroCuentaInput.removeAttribute('maxlength');
                                numeroCuentaInput.setAttribute('placeholder', 'Número de cuenta');

                                if (tipoCuenta == "1") {
                                    numeroCuentaInput.setAttribute('maxlength', '20');
                                    numeroCuentaInput.setAttribute('placeholder', 'Número interbancario (20 dígitos)');
                                } else if (tipoCuenta == "2") {
                                    numeroCuentaInput.setAttribute('maxlength', '24');
                                    numeroCuentaInput.setAttribute('placeholder', 'Número de cuenta (13-24 dígitos)');
                                }
                            });

                            document.getElementById('saveBtn').addEventListener('click', function() {
                                const tipoCuenta = document.getElementById('payBrand').value;
                                const numeroCuenta = document.getElementById('payNumber').value;

                                if (tipoCuenta == "1" && numeroCuenta.length !== 20) {
                                    toastr.error('El número interbancario debe tener exactamente 20 dígitos.');
                                    return;
                                }

                                if (tipoCuenta == "2" && (numeroCuenta.length < 13 || numeroCuenta.length > 24)) {
                                    toastr.error('El número de cuenta debe tener entre 13 y 24 dígitos.');
                                    return;
                                }

                                if (tipoCuenta && numeroCuenta) {
                                    const usuarioId = @json($usuario->idUsuario);

                                    fetch('/api/guardar-cuenta', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                                    'content'),
                                            },
                                            body: JSON.stringify({
                                                tipoCuenta: tipoCuenta,
                                                numeroCuenta: numeroCuenta,
                                                usuarioId: usuarioId,
                                            })
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.success) {
                                                toastr.success('Cuenta bancaria guardada con éxito');
                                            } else {
                                                toastr.error('Hubo un error al guardar la cuenta bancaria');
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Error al guardar la cuenta:', error);
                                            toastr.error('Error al guardar la cuenta bancaria');
                                        });
                                } else {
                                    toastr.error('Por favor, complete todos los campos');
                                }
                            });
                        </script>
                    </div>
                </div>
            </div>







            <script>
                // Recibe el `customUserId` pasado desde Alpine.js
                function cargarCuentasBancarias(customUserId) {
                    console.log("Cargando cuentas bancarias para el usuario con ID:", customUserId);

                    // Hacer una solicitud AJAX a la API de cuentas bancarias
                    fetch(`/api/cuentas-bancarias/${customUserId}`)
                        .then(response => {
                            if (!response.ok) {
                                console.error("Error en la solicitud de cuentas bancarias:", response.status);
                            }
                            return response.json(); // Parsear la respuesta JSON
                        })
                        .then(cuentasBancarias => {
                            console.log("Cuentas bancarias obtenidas:",
                                cuentasBancarias); // Ver los datos de las cuentas bancarias

                            const container = document.getElementById('cuentas-bancarias');
                            container.innerHTML = ''; // Limpiar contenido previo

                            if (cuentasBancarias.length === 0) {
                                console.log("No se encontraron cuentas bancarias para este usuario.");
                            }

                            cuentasBancarias.forEach(cuenta => {
                                console.log("Procesando cuenta bancaria:",
                                    cuenta); // Ver cada cuenta que estamos procesando

                                // Crear un nuevo elemento para cada cuenta bancaria
                                const cuentaElement = document.createElement('div');
                                cuentaElement.classList.add('border-b', 'border-[#ebedf2]', 'dark:border-[#1b2e4b]');
                                cuentaElement.innerHTML = `
                    <div class="flex items-start justify-between py-3">
                        <div class="flex-none ltr:mr-4 rtl:ml-4">
                            <!-- Aquí puedes agregar una imagen del tipo de tarjeta si es necesario -->
                            <img src="/assets/images/card-visa.svg" alt="image" />
                        </div>
                        <h6 class="text-[#515365] font-bold dark:text-white-dark text-[15px]">
                            ${cuenta.tipodecuenta === 1 ? 'Mastercard' : 'Visa'} 
                            <span class="block text-white-dark dark:text-white-light font-normal text-xs mt-1">
                                XXXX XXXX XXXX ${cuenta.numerocuenta.slice(-4)}
                            </span>
                        </h6>
                        <div class="flex items-start justify-between ltr:ml-auto rtl:mr-auto gap-2">
                            <button class="btn btn-primary">Ver</button>
                            <button class="btn btn-dark">Edit</button>
                        </div>

                    </div>
                `;
                                container.appendChild(cuentaElement);
                            });
                        })
                        .catch(error => {
                            console.error('Error al cargar las cuentas bancarias:',
                                error); // Mostrar cualquier error que ocurra
                        });
                }

                // Cargar las cuentas bancarias cuando la página esté lista
                document.addEventListener('DOMContentLoaded', function() {
                    const customUserId = @json($usuario->idUsuario);
                    cargarCuentasBancarias(customUserId);
                });
            </script>



        </div>

        <div class="grid grid-cols-1 lg:grid-cols-1 gap-5">
            <!-- Tabla de pagos de quincena -->
            <div class="bg-white shadow rounded-lg p-4">
                <h5 class="text-lg font-semibold mb-4">Pagos de Quincena</h5>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="text-left px-4 py-2 text-sm font-medium text-gray-700">Empleado</th>
                                <th class="text-left px-4 py-2 text-sm font-medium text-gray-700">DNI</th>
                                <th class="text-left px-4 py-2 text-sm font-medium text-gray-700">Cargo</th>
                                <th class="text-left px-4 py-2 text-sm font-medium text-gray-700">Fecha</th>
                                <th class="text-left px-4 py-2 text-sm font-medium text-gray-700">Monto (S/)</th>
                                <th class="text-left px-4 py-2 text-sm font-medium text-gray-700">Estado</th>
                                <th class="text-left px-4 py-2 text-sm font-medium text-gray-700">Opciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm text-gray-800">
                            <!-- Pagado -->
                            <tr>
                                <td class="px-4 py-2">STUAR</td>
                                <td class="px-4 py-2">12345678</td>
                                <td class="px-4 py-2">Programador Junior</td>
                                <td class="px-4 py-2">15/04/2025</td>
                                <td class="px-4 py-2">1,200.00</td>
                                <td class="px-4 py-2">
                                    <span class="badge badge-outline-success">Pagado</span>
                                </td>
                                <!-- Opciones con solo iconos -->
                                <td class="px-4 py-2 space-x-2 text-lg">
                                    <a href="#" class="text-info" title="Ver Detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" class="text-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" class="text-danger" title="Eliminar">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                    <a href="#" class="text-success" title="Generar Reporte">
                                        <i class="fas fa-file-alt"></i>
                                    </a>
                                </td>

                            </tr>

                            <!-- No Pagado -->
                            <tr class="bg-gray-50">
                                <td class="px-4 py-2">STUAR</td>
                                <td class="px-4 py-2">87654321</td>
                                <td class="px-4 py-2">Programador Junior</td>
                                <td class="px-4 py-2">15/04/2025</td>
                                <td class="px-4 py-2">1,500.00</td>
                                <td class="px-4 py-2">
                                    <span class="badge badge-outline-danger">No Pagado</span>
                                </td>
                                <!-- Opciones con solo iconos -->
                                <td class="px-4 py-2 space-x-2 text-lg">
                                    <a href="#" class="text-info" title="Ver Detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" class="text-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" class="text-danger" title="Eliminar">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                    <a href="#" class="text-success" title="Generar Reporte">
                                        <i class="fas fa-file-alt"></i>
                                    </a>
                                </td>

                            </tr>

                            <!-- Pendiente -->
                            <tr>
                                <td class="px-4 py-2">STUAR</td>
                                <td class="px-4 py-2">45678912</td>
                                <td class="px-4 py-2">Programador Junior</td>
                                <td class="px-4 py-2">15/04/2025</td>
                                <td class="px-4 py-2">2,300.00</td>
                                <td class="px-4 py-2">
                                    <span class="badge badge-outline-warning">Pendiente</span>
                                </td>
                                <!-- Opciones con solo iconos -->
                                <td class="px-4 py-2 space-x-2 text-lg">
                                    <a href="#" class="text-info" title="Ver Detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" class="text-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" class="text-danger" title="Eliminar">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                    <a href="#" class="text-success" title="Generar Reporte">
                                        <i class="fas fa-file-alt"></i>
                                    </a>
                                </td>

                            </tr>

                            <!-- Por Realizar -->
                            <tr class="bg-gray-50">
                                <td class="px-4 py-2">STUAR</td>
                                <td class="px-4 py-2">11223344</td>
                                <td class="px-4 py-2">Programador Junior</td>
                                <td class="px-4 py-2">15/04/2025</td>
                                <td class="px-4 py-2">900.00</td>
                                <td class="px-4 py-2">
                                    <span class="badge badge-outline-info">Por Realizar</span>
                                </td>
                                <!-- Opciones con solo iconos -->
                                <td class="px-4 py-2 space-x-2 text-lg">
                                    <a href="#" class="text-info" title="Ver Detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" class="text-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" class="text-danger" title="Eliminar">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                    <a href="#" class="text-success" title="Generar Reporte">
                                        <i class="fas fa-file-alt"></i>
                                    </a>
                                </td>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>



            <!-- Otro contenido, si lo deseas -->
            <div id="displayArea" class="mt-4"></div>
        </div>

    </div>
</template>
