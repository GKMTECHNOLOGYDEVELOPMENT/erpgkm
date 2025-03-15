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

                <script>
                    // Verifica si el script ya se ha ejecutado
                    if (!window.signaturePadInitialized) {
                        // Obtén el lienzo
                        const signatureCanvas = document.getElementById('signature-pad');

                        // Verifica si el lienzo ya tiene una instancia de SignaturePad
                        if (signatureCanvas && !signatureCanvas.signaturePadInstance) {
                            // Crea una nueva instancia de SignaturePad y la guarda en el lienzo
                            signatureCanvas.signaturePadInstance = new SignaturePad(signatureCanvas);

                            // Botón para limpiar la firma
                            document.getElementById('clear-btn').addEventListener('click', () => {
                                signatureCanvas.signaturePadInstance.clear();
                            });

                            // Botón para guardar la firma
                            document.getElementById('save-btn').addEventListener('click', () => {
                                if (signatureCanvas.signaturePadInstance.isEmpty()) {
                                    toastr.error("Por favor, proporciona tu firma primero.");
                                } else {
                                    // Convertir la firma a base64
                                    const dataURL = signatureCanvas.signaturePadInstance.toDataURL();
                                    console.log("Firma guardada:", dataURL); // Log de la firma

                                    // Convertir base64 a binario (ArrayBuffer)
                                    const binaryData = dataURL.split(',')[
                                        1]; // Eliminar el encabezado de la imagen (data:image/png;base64,)

                                    // Asume que tienes el ID del usuario disponible en una variable de JavaScript
                                    const userId =
                                        {{ $usuario->idUsuario }}; // Asegúrate de que esta variable sea correctamente insertada desde el backend

                                    // Crear la URL completa para el fetch
                                    const url = `/usuario/firma/${userId}`; // Usa la ruta correcta

                                    // Log para verificar la URL
                                    console.log("Enviando solicitud a la URL:", url);

                                    // Obtener el CSRF token del meta tag
                                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                                    // Enviar la firma al backend mediante fetch
                                    fetch(url, { // Usar el idUsuario dinámicamente en la URL
                                            method: "PUT", // Método PUT para actualizar la firma
                                            headers: {
                                                "Content-Type": "application/json",
                                                "X-CSRF-TOKEN": csrfToken // Agregar el token CSRF aquí
                                            },
                                            body: JSON.stringify({
                                                firma: binaryData // Aquí se envía la firma en binario
                                            })
                                        })
                                        .then(response => {
                                            console.log("Respuesta del servidor:",
                                                response); // Log de la respuesta del servidor
                                            if (!response.ok) {
                                                throw new Error(`Error al guardar la firma: ${response.statusText}`);
                                            }
                                            return response.json();
                                        })
                                        .then(data => {
                                            console.log("Datos del servidor:", data);
                                            toastr.success("Firma guardada o actualizada correctamente.");
                                        })
                                        .catch(error => {
                                            console.error("Error al guardar la firma:", error); // Log de errores
                                        });
                                }
                            });

                            // Función para cargar la firma del servidor
                            const cargarFirma = () => {
                                const userId =
                                    {{ $usuario->idUsuario }}; // Asegúrate de que esta variable sea correctamente insertada desde el backend
                                const url = `/usuario/firma/${userId}`; // URL para obtener la firma

                                // Fetch para obtener la firma
                                fetch(url)
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.firma) {
                                            // Si la firma existe, la mostramos en el canvas
                                            const image = new Image();
                                            image.src = data
                                                .firma; // La firma es una cadena base64 con el prefijo data:image/png;base64,

                                            image.onload = () => {
                                                // Cuando la imagen se cargue, dibujamos la firma en el lienzo
                                                const ctx = signatureCanvas.getContext('2d');
                                                ctx.clearRect(0, 0, signatureCanvas.width, signatureCanvas
                                                    .height); // Limpiamos el lienzo
                                                ctx.drawImage(image, 0, 0); // Dibujamos la firma en el lienzo
                                            };
                                        } else {
                                            console.log("No hay firma guardada para este usuario.");
                                        }
                                    })
                                    .catch(error => {
                                        console.error("Error al cargar la firma:", error);
                                    });
                            };

                            // Llamar a la función para cargar la firma al cargar el script
                            cargarFirma();

                            // Botón de refrescar: vuelve a cargar la firma
                            document.getElementById('refresh-btn').addEventListener('click', () => {
                                cargarFirma();
                            });

                            // Marca el script como inicializado
                            window.signaturePadInitialized = true;
                        }
                    }
                </script>

            </div>





            <div class="panel">
                <div x-data="{ customUserId: @json($usuario->idUsuario) }" x-init="cargarCuentasBancarias(customUserId)" class="mb-5">
                    <h5 class="font-semibold text-lg mb-4">Cuentas Bancarias</h5>
                    <!-- <p>Changes to your <span class="text-primary">Payment Method</span> information
                        will take effect starting with scheduled payment and will be refelected on your
                        next invoice.</p> -->
                </div>
                <div class="mb-5" id="cuentas-bancarias">
                    <!-- Aquí se cargarán las cuentas bancarias dinámicamente con JS -->
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
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <div class="panel">
                <div class="mb-5">
                    <h5 class="font-semibold text-lg mb-4">Agregar dirección </h5>
                    <!-- <p>Changes your New <span class="text-primary">Billing</span> Information.</p> -->
                </div>
                <div class="mb-5">
                    <form id="direccion-form">
                        @csrf
                        @method('PUT')

                        <!-- Información Básica -->
                        <div class="mb-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Nacionalidad -->
                            <div>
                                <label for="nacionalidad">Nacionalidad</label>
                                <input id="nacionalidad" name="nacionalidad" type="text"
                                    value="{{ old('nacionalidad', $usuario->nacionalidad) }}" class="form-input" />
                            </div>
                            <!-- Departamento -->
                            <div>
                                <label for="departamento" class="block text-sm font-medium">Departamento</label>
                                <select id="departamento" name="departamento" class="form-input w-full">
                                    <option value="" disabled selected>Seleccionar Departamento</option>
                                    @foreach ($departamentos as $departamento)
                                        <option value="{{ $departamento['id_ubigeo'] }}"
                                            {{ old('departamento', $usuario->departamento) == $departamento['id_ubigeo'] ? 'selected' : '' }}>
                                            {{ $departamento['nombre_ubigeo'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="departamento-error" class="text-red-500 text-sm" style="display: none;"></div>
                            </div>
                        </div>

                        <!-- Información de Ubicación -->
                        <div class="mb-5">
                            <div>
                                <label for="provincia" class="block text-sm font-medium">Provincia</label>
                                <select id="provincia" name="provincia" class="form-input w-full">
                                    <option value="" disabled>Seleccionar Provincia</option>
                                    @foreach ($provinciasDelDepartamento as $provincia)
                                        <option value="{{ $provincia['id_ubigeo'] }}"
                                            {{ old('provincia', $usuario->provincia) == $provincia['id_ubigeo'] ? 'selected' : '' }}>
                                            {{ $provincia['nombre_ubigeo'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="provincia-error" class="text-red-500 text-sm" style="display: none;"></div>
                            </div>
                            <div>
                                <label for="distrito" class="block text-sm font-medium mt-2">Distrito</label>
                                <select id="distrito" name="distrito" class="form-input w-full">
                                    <option value="" disabled>Seleccionar Distrito</option>
                                    @foreach ($distritosDeLaProvincia as $distrito)
                                        <option value="{{ $distrito['id_ubigeo'] }}"
                                            {{ old('distrito', $usuario->distrito) == $distrito['id_ubigeo'] ? 'selected' : '' }}>
                                            {{ $distrito['nombre_ubigeo'] }}
                                        </option>
                                    @endforeach
                                </select>

                                <div id="distrito-error" class="text-red-500 text-sm" style="display: none;"></div>
                            </div>

                            <div>
                                <label for="direccion" class="block text-sm font-medium mt-2">Dirección</label>
                                <input id="direccion" name="direccion" type="text" class="form-input w-full"
                                    value="{{ old('direccion', $usuario->direccion) }}"
                                    placeholder="Ingrese la dirección">
                                <div id="direccion-error" class="text-red-500 text-sm" style="display: none;"></div>
                            </div>
                        </div>

                        <!-- Botón de Actualización -->
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </form>

                    <script>
                        $(document).ready(function() {
                            // Cargar provincias y distritos al cargar el formulario si ya hay un departamento seleccionado
                            function cargarProvincias(departamentoId) {
                                $.get('/ubigeo/provincias/' + departamentoId, function(data) {
                                    var provinciaSelect = $('#provincia');
                                    provinciaSelect.empty().prop('disabled', false);
                                    provinciaSelect.append(
                                        '<option value="" disabled selected>Seleccionar Provincia</option>');

                                    data.forEach(function(provincia) {
                                        provinciaSelect.append('<option value="' + provincia.id_ubigeo + '">' +
                                            provincia.nombre_ubigeo + '</option>');
                                    });

                                    // Si hay provincia seleccionada previamente, se selecciona automáticamente
                                    var provinciaSeleccionada = '{{ old('provincia', $usuario->provincia) }}';
                                    if (provinciaSeleccionada) {
                                        $('#provincia').val(provinciaSeleccionada).change();
                                    }
                                });
                            }

                            function cargarDistritos(provinciaId) {
                                $.get('/ubigeo/distritos/' + provinciaId, function(data) {
                                    var distritoSelect = $('#distrito');
                                    distritoSelect.empty().prop('disabled', false);
                                    distritoSelect.append(
                                        '<option value="" disabled selected>Seleccionar Distrito</option>');

                                    data.forEach(function(distrito) {
                                        distritoSelect.append('<option value="' + distrito.id_ubigeo + '">' +
                                            distrito.nombre_ubigeo + '</option>');
                                    });

                                    // Si hay distrito seleccionado previamente, se selecciona automáticamente
                                    var distritoSeleccionado = '{{ old('distrito', $usuario->distrito) }}';
                                    if (distritoSeleccionado) {
                                        $('#distrito').val(distritoSeleccionado);
                                    }
                                });
                            }

                            // Si ya hay un departamento seleccionado al cargar la página
                            var departamentoId = $('#departamento').val();
                            if (departamentoId) {
                                cargarProvincias(departamentoId);
                            }

                            // Cargar distritos si ya hay una provincia seleccionada al cargar la página
                            var provinciaId = $('#provincia').val();
                            if (provinciaId) {
                                cargarDistritos(provinciaId);
                            }

                            // Cuando se selecciona un nuevo departamento
                            $('#departamento').change(function() {
                                var departamentoId = $(this).val();
                                if (departamentoId) {
                                    // Limpiar los selects de provincia y distrito
                                    $('#provincia').empty().prop('disabled', true);
                                    $('#distrito').empty().prop('disabled', true);

                                    cargarProvincias(departamentoId);
                                }
                            });

                            // Cuando se selecciona una provincia
                            $('#provincia').on('change', function() {
                                var provinciaId = $(this).val();
                                if (provinciaId) {
                                    // Limpiar el select de distritos
                                    $('#distrito').empty().prop('disabled', true);

                                    cargarDistritos(provinciaId);
                                }
                            });
                        });
                    </script>

                    <!-- <script src="{{ asset('assets/js/ubigeo.js') }}"></script> -->



                    <script>
                        document.getElementById('direccion-form').addEventListener('submit', function(event) {
                            event.preventDefault(); // Evita el envío normal del formulario

                            // Recolecta los datos del formulario
                            const formData = new FormData(this);
                            const formDataObj = {};
                            formData.forEach((value, key) => {
                                formDataObj[key] = value;
                            });

                            // ID del usuario (será inyectado en tu Blade)
                            const userId = {{ $usuario->idUsuario }};

                            // URL para actualizar la dirección
                            const url = `/usuario/direccion/${userId}`;

                            // Obtener el token CSRF
                            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                            // Realiza la solicitud `fetch` con el método PUT y los datos JSON
                            fetch(url, {
                                    method: 'PUT', // Usamos el método PUT para actualización
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken
                                    },
                                    body: JSON.stringify(formDataObj) // Convierte los datos del formulario a JSON
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Si la respuesta es exitosa, puedes mostrar un mensaje de éxito
                                        toastr.success('Dirección actualizada correctamente');
                                    } else {
                                        // Si ocurre un error, mostrar los mensajes de error
                                        toastr.error('Error al actualizar la dirección');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error al enviar la solicitud:', error);
                                    toastr.error('Error al intentar actualizar');
                                });
                        });
                    </script>





                </div>
            </div>
            <div class="panel">
                <div class="mb-5">
                    <h5 class="font-semibold text-lg mb-4">Numero de cuenta</h5>
                </div>
                <div class="mb-5">
                    <form>
                        <div class="mb-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="payBrand">Tipo de Cuenta Bancaria</label>
                                <select id="payBrand" class="form-select text-white-dark">
                                    <option selected>Seleccione una Opcion</option>
                                    <option value="1">Cuenta numero interbancario</option>
                                    <option value="2">Número de cuenta</option>
                                </select>
                            </div>
                            <div>
                                <label for="payNumber">Numero de cuenta</label>
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
    numeroCuentaInput.setAttribute('placeholder', 'Numero de cuenta');
    
    // Establecer el número máximo de dígitos en el campo según el tipo de cuenta
    if (tipoCuenta == "1") {
        // Si es Número interbancario, se establece 20 dígitos
        numeroCuentaInput.setAttribute('maxlength', '20');
        numeroCuentaInput.setAttribute('placeholder', 'Numero interbancario (20 digitos)');
    } else if (tipoCuenta == "2") {
        // Si es Número de cuenta, se establece entre 13 a 24 dígitos
        numeroCuentaInput.setAttribute('maxlength', '24');
        numeroCuentaInput.setAttribute('placeholder', 'Numero de cuenta (13-24 digitos)');
    }
});

document.getElementById('saveBtn').addEventListener('click', function() {
    const tipoCuenta = document.getElementById('payBrand').value;
    const numeroCuenta = document.getElementById('payNumber').value;

    // Validación según el tipo de cuenta
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
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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
            <!-- Contenedor donde se mostrará el número guardado -->
            <div id="displayArea" class="mt-4"></div>
        </div>
    </div>
</template>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const canvas = document.getElementById("signature-pad");
        const ctx = canvas.getContext("2d");
        const clearButton = document.getElementById("clear-btn");
        const saveButton = document.getElementById("save-btn");
        const refreshButton = document.getElementById("refresh-btn");

        let drawing = false;
        let lastX = 0;
        let lastY = 0;
        let storedSignature = null; // Guarda la firma para evitar perderla al redimensionar

        // 🔹 Ajustar el tamaño del canvas de forma responsive
        function resizeCanvas() {
            const parentWidth = canvas.parentElement.clientWidth;
            const aspectRatio = 300 / 700; // Mantener relación de aspecto 700x300

            // Ajustar dimensiones
            const newWidth = window.innerWidth >= 768 ? 700 : parentWidth;
            const newHeight = newWidth * aspectRatio;

            // Guardar la firma antes de cambiar el tamaño
            if (storedSignature) {
                const tempImage = ctx.getImageData(0, 0, canvas.width, canvas.height);
                storedSignature = tempImage;
            }

            // Redimensionar el canvas
            canvas.width = newWidth;
            canvas.height = newHeight;

            // Restaurar la firma después de redimensionar
            if (storedSignature) {
                ctx.putImageData(storedSignature, 0, 0);
            } else {
                ctx.fillStyle = "white";
                ctx.fillRect(0, 0, canvas.width, canvas.height);
            }
        }

        // 🔹 Obtener coordenadas precisas del cursor
        function getMousePos(event) {
            const rect = canvas.getBoundingClientRect();
            return {
                x: (event.clientX - rect.left) * (canvas.width / rect.width),
                y: (event.clientY - rect.top) * (canvas.height / rect.height)
            };
        }

        // 🔹 Eventos de dibujo
        function startDrawing(event) {
            drawing = true;
            const pos = getMousePos(event);
            lastX = pos.x;
            lastY = pos.y;
        }

        function draw(event) {
            if (!drawing) return;
            const pos = getMousePos(event);
            ctx.beginPath();
            ctx.moveTo(lastX, lastY);
            ctx.lineTo(pos.x, pos.y);
            ctx.strokeStyle = "#000";
            ctx.lineWidth = 2;
            ctx.lineCap = "round";
            ctx.stroke();
            lastX = pos.x;
            lastY = pos.y;
        }

        function stopDrawing() {
            drawing = false;
            storedSignature = ctx.getImageData(0, 0, canvas.width, canvas.height); // Guarda la firma
        }

        // 🔹 Limpiar el canvas
        clearButton.addEventListener("click", function() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = "white";
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            storedSignature = null;
        });

        // 🔹 Guardar la firma
        saveButton.addEventListener("click", function() {
            const imageURL = canvas.toDataURL("image/png");
            const link = document.createElement("a");
            link.href = imageURL;
            link.download = "firma.png";
            link.click();
        });

        // 🔹 Refrescar la página
        refreshButton.addEventListener("click", function() {
            location.reload();
        });

        // 🔹 Eventos de mouse
        canvas.addEventListener("mousedown", startDrawing);
        canvas.addEventListener("mousemove", draw);
        canvas.addEventListener("mouseup", stopDrawing);
        canvas.addEventListener("mouseleave", stopDrawing);

        // 🔹 Eventos táctiles (para móviles)
        canvas.addEventListener("touchstart", (e) => {
            e.preventDefault();
            startDrawing(e.touches[0]);
        });

        canvas.addEventListener("touchmove", (e) => {
            e.preventDefault();
            draw(e.touches[0]);
        });

        canvas.addEventListener("touchend", stopDrawing);

        // 🔹 Ajustar el tamaño al cargar y redimensionar
        resizeCanvas();
        window.addEventListener("resize", resizeCanvas);
    });


    // $(document).ready(function() {
    //     // Cargar provincias y distritos al cargar el formulario si ya hay un departamento seleccionado
    //     function cargarProvincias(departamentoId) {
    //         $.get('/ubigeo/provincias/' + departamentoId, function(data) {
    //             var provinciaSelect = $('#provincia');
    //             provinciaSelect.empty().prop('disabled', false);
    //             provinciaSelect.append(
    //                 '<option value="" disabled selected>Seleccionar Provincia</option>');

    //             data.forEach(function(provincia) {
    //                 provinciaSelect.append('<option value="' + provincia.id_ubigeo + '">' +
    //                     provincia.nombre_ubigeo + '</option>');
    //             });

    //             // Si hay provincia seleccionada previamente, se selecciona automáticamente
    //             var provinciaSeleccionada = '{{ old('provincia', $usuario->provincia) }}';
    //             if (provinciaSeleccionada) {
    //                 $('#provincia').val(provinciaSeleccionada).change();
    //             }
    //         });
    //     }

    //     function cargarDistritos(provinciaId) {
    //         $.get('/ubigeo/distritos/' + provinciaId, function(data) {
    //             var distritoSelect = $('#distrito');
    //             distritoSelect.empty().prop('disabled', false);
    //             distritoSelect.append(
    //                 '<option value="" disabled selected>Seleccionar Distrito</option>');

    //             data.forEach(function(distrito) {
    //                 distritoSelect.append('<option value="' + distrito.id_ubigeo + '">' +
    //                     distrito.nombre_ubigeo + '</option>');
    //             });

    //             // Si hay distrito seleccionado previamente, se selecciona automáticamente
    //             var distritoSeleccionado = '{{ old('distrito', $usuario->distrito) }}';
    //             if (distritoSeleccionado) {
    //                 $('#distrito').val(distritoSeleccionado);
    //             }
    //         });
    //     }

    //     // Si ya hay un departamento seleccionado al cargar la página
    //     var departamentoId = $('#departamento').val();
    //     if (departamentoId) {
    //         cargarProvincias(departamentoId);
    //     }

    //     // Cargar distritos si ya hay una provincia seleccionada al cargar la página
    //     var provinciaId = $('#provincia').val();
    //     if (provinciaId) {
    //         cargarDistritos(provinciaId);
    //     }

    //     // Cuando se selecciona un nuevo departamento
    //     $('#departamento').change(function() {
    //         var departamentoId = $(this).val();
    //         if (departamentoId) {
    //             // Limpiar los selects de provincia y distrito
    //             $('#provincia').empty().prop('disabled', true);
    //             $('#distrito').empty().prop('disabled', true);

    //             cargarProvincias(departamentoId);
    //         }
    //     });

    //     // Cuando se selecciona una provincia
    //     $('#provincia').on('change', function() {
    //         var provinciaId = $(this).val();
    //         if (provinciaId) {
    //             // Limpiar el select de distritos
    //             $('#distrito').empty().prop('disabled', true);

    //             cargarDistritos(provinciaId);
    //         }
    //     });
    // });

    // Inicializar Select2
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.select2').forEach(function(select) {
            NiceSelect.bind(select, {
                searchable: true
            });
        });
    });
</script>


<!-- Agrega Select2 JS antes del cierre de </body> -->



<!-- <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script> -->
<!-- <script>
    $(document).ready(function() {
        $('select').niceSelect(); // Aplica Nice Select a todos los selectores en la página
    });
</script> -->
<script>
    // Función para mostrar la imagen seleccionada
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('profile-img');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
