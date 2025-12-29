<x-layout.default>

    <!-- Incluir el archivo CSS de Nice Select -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">

    
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">




    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{ route('usuario') }}" class="text-primary hover:underline">Usuarios</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Agregar Usuario</span>
            </li>
        </ul>
        
        <div class="pt-5">
            <div x-data="{ tab: 'home' }">
                <ul
                    class="sm:flex font-semibold border-b border-[#ebedf2] dark:border-[#191e3a] mb-5 whitespace-nowrap overflow-y-auto">
                    <li class="inline-block">
                        <a href="javascript:;"
                            class="flex gap-2 p-4 border-b border-transparent hover:border-primary hover:text-primary"
                            :class="{ '!border-primary text-primary': tab == 'home' }" @click="tab='home'">

                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                <path opacity="0.5"
                                    d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z"
                                    stroke="currentColor" stroke-width="1.5" />
                                <path d="M12 15L12 18" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" />
                            </svg>
                            Perfil
                        </a>
                    </li>
                   
                </ul>
                <template x-if="tab === 'home'">
                    <div>
                    <form id="usuario-form" method="POST" enctype="multipart/form-data" class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-4 mb-5 bg-white dark:bg-[#0e1726]">

                            @csrf
                            <h6 class="text-lg font-bold mb-5">Información General</h6>
                            <div class="flex flex-col sm:flex-row">
                                <!-- Imagen de perfil -->
                                <div class="ltr:sm:mr-4 rtl:sm:ml-4 w-full sm:w-2/12 mb-5">
                                    <!-- Imagen que se puede hacer clic para cambiarla -->
                                    <label for="profile-image">
                                        <img id="profile-img" src="/assets/images/profile-34.jpeg" alt="image"
                                            class="w-20 h-20 md:w-32 md:h-32 rounded-full object-cover mx-auto cursor-pointer" />
                                    </label>
                                    <!-- Input file oculto -->
                                    <input type="file" id="profile-image" name="profile-image" style="display:none;" accept="image/*" onchange="previewImage(event)" />
                                </div>

                                <!-- Formulario de campos -->
                                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <!-- Nombre Completo -->
                                    <div>
                                        <label for="Nombre">Nombre Completo</label>
                                        <input id="Nombre" name="Nombre" type="text" placeholder="Darlin Josue" class="form-input" />
                                    </div>

                                    <!-- Apellido Paterno -->
                                    <div>
                                        <label for="apellidoPaterno">Apellido Paterno</label>
                                        <input id="apellidoPaterno" name="apellidoPaterno" type="text" placeholder="Saldarriaga" class="form-input" />
                                    </div>

                                    <!-- Apellido Materno -->
                                    <div>
                                        <label for="apellidoMaterno">Apellido Materno</label>
                                        <input id="apellidoMaterno" name="apellidoMaterno" type="text" placeholder="Cruz" class="form-input" />
                                    </div>

                                    <!-- Tipo Documento -->
                                    <div>
                                        <label for="idTipoDocumento" class="block text-sm font-medium">Tipo Documento</label>
                                        <select id="idTipoDocumento" name="idTipoDocumento" class="form-input" >
                                            <option value="" disabled selected>Seleccionar Tipo Documento</option>

                                            @foreach ($tiposDocumento as $tipoDocumento)
                                            <option value="{{ $tipoDocumento->idTipoDocumento }}">
                                                {{ $tipoDocumento->nombre }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Documento -->
                                    <div>
                                        <label for="documento">Documento</label>
                                        <input id="documento" name="documento" type="text" placeholder="12345678" class="form-input" />
                                    </div>

                                    <!-- Teléfono -->
                                    <div>
                                        <label for="telefono">Teléfono</label>
                                        <input id="telefono" type="text" name="telefono" placeholder="962 952 239" class="form-input" />
                                    </div>

                                    <!-- Agrega este campo después del correo corporativo -->

<!-- Correo Corporativo -->
<div>
    <label for="correo" class="flex items-center gap-1">
        Correo Corporativo
        <span class="text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded">Obligatorio</span>
    </label>
    <input id="correo" name="correo" type="email" placeholder="correo@empresa.com" class="form-input border-blue-300" />
    <p class="text-xs text-blue-500 mt-1">Correo oficial para comunicaciones de la empresa</p>
</div>

<!-- Correo Personal -->
<div>
    <label for="correo_personal" class="flex items-center gap-1">
        Correo Personal
        <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded">Opcional</span>
    </label>
    <input id="correo_personal" name="correo_personal" type="email" placeholder="correo@gmail.com" 
           class="form-input border-green-300" />
    <p class="text-xs text-green-500 mt-1">Correo personal para comunicaciones personales</p>
</div>
                                    <!-- Estado Civil -->
                                    <div>
                                        <label for="estadocivil">Estado Civil</label>
                                        <select id="estadocivil" name="estadocivil" class="form-input">
                                            <option value="" disabled selected>Seleccionar Estado Civil</option>
                                            <option value="1">Soltero</option>
                                            <option value="2">Casado</option>
                                            <option value="3">Divorciado</option>
                                            <option value="4">Viudo</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Botones -->
                                    <div class="sm:col-span-2 mt-3">
                                        <button type="submit" class="btn btn-primary mr-2">Guardar</button>
                                        <!-- <button type="reset" class="btn btn-primary">Limpiar</button> -->
                                    </div>
                                </div>
                            </div>
                        </form>


                     <script>
document.addEventListener('DOMContentLoaded', function () {
    // Cuando se envía el formulario
    document.getElementById('usuario-form').addEventListener('submit', function (e) {
        e.preventDefault();
        
        // Validar correos antes de enviar
        const correo = document.getElementById('correo').value;
        const correoPersonal = document.getElementById('correo_personal').value;
        
        // Validar formato de correo corporativo
        if (!isValidEmail(correo)) {
            toastr.error('El correo corporativo no tiene un formato válido');
            return;
        }
        
        // Validar formato de correo personal si se ingresó
        if (correoPersonal && !isValidEmail(correoPersonal)) {
            toastr.error('El correo personal no tiene un formato válido');
            return;
        }
        
        // Validar que no sean el mismo correo
        if (correoPersonal && correo.toLowerCase() === correoPersonal.toLowerCase()) {
            toastr.error('El correo personal no puede ser igual al correo corporativo');
            return;
        }

        // Continuar con el envío...
        var formData = new FormData(this);
        
        fetch(`/usuario/store`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                toastr.success(data.message, "Éxito", {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: 1000,
                    onHidden: function () {
                        window.location.href = '/usuario/' + data.usuarioId + '/edit';
                    }
                });
            } else if (data.errors) {
                let errorMessages = '';
                for (let key in data.errors) {
                    if (data.errors.hasOwnProperty(key)) {
                        errorMessages += data.errors[key].join('<br>') + '<br>';
                    }
                }
                toastr.error(errorMessages, "Errores en el formulario", {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: 5000
                });
            }
        })
        .catch(error => {
            console.error("Error durante la petición:", error);
            toastr.error("Ocurrió un error inesperado.", "Error", {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: 5000
            });
        });
    });
    
    // Función para validar formato de email
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tipoDocumentoSelect = document.getElementById('idTipoDocumento');
    const documentoInput = document.getElementById('documento');
    const formulario = document.getElementById('usuario-form');

    // Mapeo de los tipos de documentos con su longitud correspondiente
    const tipoDocumentoLongitudes = {
        'DNI': 8,
        'RUC': 11,
        'PASAPORTE': 12,
        'CPP': 12,
        'CARNET DE EXTRANJERIA': 20
    };

    // Función para ajustar la longitud del documento
    tipoDocumentoSelect.addEventListener('change', function() {
        const tipoSeleccionado = tipoDocumentoSelect.options[tipoDocumentoSelect.selectedIndex].text;

        // Establecemos el número de caracteres requeridos para el tipo de documento
        const longitudMaxima = tipoDocumentoLongitudes[tipoSeleccionado] || 255;  // por defecto, 255 caracteres si no coincide

        // Establecemos el atributo 'maxlength' del input de documento
        documentoInput.setAttribute('maxlength', longitudMaxima);
        documentoInput.placeholder = `Introduce un ${tipoSeleccionado} de ${longitudMaxima} dígitos`;
    });

    // Validación al enviar el formulario
    formulario.addEventListener('submit', function(event) {
        const tipoSeleccionado = tipoDocumentoSelect.options[tipoDocumentoSelect.selectedIndex].text;
        const longitudMaxima = tipoDocumentoLongitudes[tipoSeleccionado];

        // Verifica si la longitud del documento es válida
        if (documentoInput.value.length !== longitudMaxima) {
            event.preventDefault();  // Evita que el formulario se envíe
            toastr.error(`El número de dígitos para ${tipoSeleccionado} debe ser ${longitudMaxima} caracteres.`);
        }
    });

    // Disparar el evento de 'change' para inicializar la longitud del campo al cargar la página
    tipoDocumentoSelect.dispatchEvent(new Event('change'));
});
</script>

                        <form
                            class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-4 bg-white dark:bg-[#0e1726]">
                     
                            <h6 class="text-lg font-bold mb-5">Información Importante</h6>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                                <!-- Sueldo por Hora -->
                                <div>
                                    <label for="sueldoPorHora">Sueldo por Hora</label>
                                    <input type="number" name="sueldoPorHora" id="sueldoPorHora" placeholder="Ejemplo: 20.5" class="form-input" step="0.01" value="" />
                                </div>

                                <!-- Sucursal -->
                                <div>
                                    <label for="idSucursal">Sucursal</label>
                                    <select name="idSucursal" id="idSucursal" class="form-input">
                                        <option value="" disabled selected>Selecciona una Sucursal</option>
                                        @foreach ($sucursales as $sucursal)
                                        <option value="{{ $sucursal->idSucursal }}">{{ $sucursal->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Tipo de Usuario -->
                                <div>
                                    <label for="idTipoUsuario">Tipo de Usuario</label>
                                    <select name="idTipoUsuario" id="idTipoUsuario" class="form-input">
                                        <option value="" disabled selected>Selecciona un Tipo de Usuario</option>
                                        @foreach ($tiposUsuario as $tipoUsuario)
                                        <option value="{{ $tipoUsuario->idTipoUsuario }}">{{ $tipoUsuario->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Sexo -->
                                <div>
                                    <label for="idSexo">Sexo</label>
                                    <select name="idSexo" id="idSexo" class="form-input">
                                        <option value="" disabled selected>Selecciona un Sexo</option>
                                        @foreach ($sexos as $sexo)
                                        <option value="{{ $sexo->idSexo }}">{{ $sexo->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Rol -->
                                <div>
                                    <label for="idRol">Rol</label>
                                    <select name="idRol" id="idRol" class="form-input">
                                        <option value="" disabled selected>Selecciona un Rol</option>
                                        @foreach ($roles as $rol)
                                        <option value="{{ $rol->idRol }}">{{ $rol->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Tipo de Área -->
                                <div>
                                    <label for="idTipoArea">Tipo de Área</label>
                                    <select name="idTipoArea" id="idTipoArea" class="form-input">
                                        <option value="" disabled selected>Selecciona un Tipo de Área</option>
                                        @foreach ($tiposArea as $tipoArea)
                                        <option value="{{ $tipoArea->idTipoArea }}">{{ $tipoArea->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Botones -->
                                <div class="sm:col-span-2 mt-3">
                                    <button type="submit" disabled class="btn btn-primary mr-2">Actualizar</button>
                                    <!-- <button type="reset" class="btn btn-primary">Limpiar</button> -->
                                </div>
                        </form>
                    </div>
                </template>
                <template x-if="tab === 'payment-details'">
                    <div>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">
                            <div class="panel">
                                <div class="mb-5">
                                    <h5 class="font-semibold text-lg mb-4">Billing Address</h5>
                                    <p>Changes to your <span class="text-primary">Billing</span> information will take
                                        effect starting with scheduled payment and will be refelected on your next
                                        invoice.</p>
                                </div>
                                <div class="mb-5">
                                    <div class="border-b border-[#ebedf2] dark:border-[#1b2e4b]">
                                        <div class="flex items-start justify-between py-3">
                                            <h6 class="text-[#515365] font-bold dark:text-white-dark text-[15px]">
                                                Address #1 <span
                                                    class="block text-white-dark dark:text-white-light font-normal text-xs mt-1">2249
                                                    Caynor Circle, New Brunswick, New Jersey</span></h6>
                                            <div class="flex items-start justify-between ltr:ml-auto rtl:mr-auto">
                                                <button class="btn btn-dark">Edit</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border-b border-[#ebedf2] dark:border-[#1b2e4b]">
                                        <div class="flex items-start justify-between py-3">
                                            <h6 class="text-[#515365] font-bold dark:text-white-dark text-[15px]">
                                                Address #2 <span
                                                    class="block text-white-dark dark:text-white-light font-normal text-xs mt-1">4262
                                                    Leverton Cove Road, Springfield, Massachusetts</span></h6>
                                            <div class="flex items-start justify-between ltr:ml-auto rtl:mr-auto">
                                                <button class="btn btn-dark">Edit</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex items-start justify-between py-3">
                                            <h6 class="text-[#515365] font-bold dark:text-white-dark text-[15px]">
                                                Address #3 <span
                                                    class="block text-white-dark dark:text-white-light font-normal text-xs mt-1">2692
                                                    Berkshire Circle, Knoxville, Tennessee</span></h6>
                                            <div class="flex items-start justify-between ltr:ml-auto rtl:mr-auto">
                                                <button class="btn btn-dark">Edit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary">Add Address</button>
                            </div>
                            <div class="panel">
                                <div class="mb-5">
                                    <h5 class="font-semibold text-lg mb-4">Payment History</h5>
                                    <p>Changes to your <span class="text-primary">Payment Method</span> information
                                        will take effect starting with scheduled payment and will be refelected on your
                                        next invoice.</p>
                                </div>
                                <div class="mb-5">
                                    <div class="border-b border-[#ebedf2] dark:border-[#1b2e4b]">
                                        <div class="flex items-start justify-between py-3">
                                            <div class="flex-none ltr:mr-4 rtl:ml-4">
                                                <img src="/assets/images/card-americanexpress.svg"
                                                    alt="image" />
                                            </div>
                                            <h6 class="text-[#515365] font-bold dark:text-white-dark text-[15px]">
                                                Mastercard <span
                                                    class="block text-white-dark dark:text-white-light font-normal text-xs mt-1">XXXX
                                                    XXXX XXXX 9704</span></h6>
                                            <div class="flex items-start justify-between ltr:ml-auto rtl:mr-auto">
                                                <button class="btn btn-dark">Edit</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border-b border-[#ebedf2] dark:border-[#1b2e4b]">
                                        <div class="flex items-start justify-between py-3">
                                            <div class="flex-none ltr:mr-4 rtl:ml-4">
                                                <img src="/assets/images/card-mastercard.svg"
                                                    alt="image" />
                                            </div>
                                            <h6 class="text-[#515365] font-bold dark:text-white-dark text-[15px]">
                                                American Express<span
                                                    class="block text-white-dark dark:text-white-light font-normal text-xs mt-1">XXXX
                                                    XXXX XXXX 310</span></h6>
                                            <div class="flex items-start justify-between ltr:ml-auto rtl:mr-auto">
                                                <button class="btn btn-dark">Edit</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex items-start justify-between py-3">
                                            <div class="flex-none ltr:mr-4 rtl:ml-4">
                                                <img src="/assets/images/card-visa.svg"
                                                    alt="image" />
                                            </div>
                                            <h6 class="text-[#515365] font-bold dark:text-white-dark text-[15px]">
                                                Visa<span
                                                    class="block text-white-dark dark:text-white-light font-normal text-xs mt-1">XXXX
                                                    XXXX XXXX 5264</span></h6>
                                            <div class="flex items-start justify-between ltr:ml-auto rtl:mr-auto">
                                                <button class="btn btn-dark">Edit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary">Add Payment Method</button>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                            <div class="panel">
                                <div class="mb-5">
                                    <h5 class="font-semibold text-lg mb-4">Add Billing Address</h5>
                                    <p>Changes your New <span class="text-primary">Billing</span> Information.</p>
                                </div>
                                <div class="mb-5">
                                    <form>
                                        <div class="mb-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <label for="billingName">Name</label>
                                                <input id="billingName" type="text" placeholder="Enter Name"
                                                    class="form-input" />
                                            </div>
                                            <div>
                                                <label for="billingEmail">Email</label>
                                                <input id="billingEmail" type="email" placeholder="Enter Email"
                                                    class="form-input" />
                                            </div>
                                        </div>
                                        <div class="mb-5">
                                            <label for="billingAddress">Address</label>
                                            <input id="billingAddress" type="text" placeholder="Enter Address"
                                                class="form-input" />
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-5">
                                            <div class="md:col-span-2">
                                                <label for="billingCity">City</label>
                                                <input id="billingCity" type="text" placeholder="Enter City"
                                                    class="form-input" />
                                            </div>
                                            <div>
                                                <label for="billingState">State</label>
                                                <select id="billingState" class="form-select text-white-dark">
                                                    <option>Choose...</option>
                                                    <option>...</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label for="billingZip">Zip</label>
                                                <input id="billingZip" type="text" placeholder="Enter Zip"
                                                    class="form-input" />
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary">Add</button>
                                    </form>
                                </div>
                            </div>
                            <div class="panel">
                                <div class="mb-5">
                                    <h5 class="font-semibold text-lg mb-4">Add Payment Method</h5>
                                    <p>Changes your New <span class="text-primary">Payment Method</span> Information.
                                    </p>
                                </div>
                                <div class="mb-5">
                                    <form>
                                        <div class="mb-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <label for="payBrand">Card Brand</label>
                                                <select id="payBrand" class="form-select text-white-dark">
                                                    <option selected="">Mastercard</option>
                                                    <option>American Express</option>
                                                    <option>Visa</option>
                                                    <option>Discover</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label for="payNumber">Card Number</label>
                                                <input id="payNumber" type="text" placeholder="Card Number"
                                                    class="form-input" />
                                            </div>
                                        </div>
                                        <div class="mb-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <label for="payHolder">Holder Name</label>
                                                <input id="payHolder" type="text" placeholder="Holder Name"
                                                    class="form-input" />
                                            </div>
                                            <div>
                                                <label for="payCvv">CVV/CVV2</label>
                                                <input id="payCvv" type="text" placeholder="CVV"
                                                    class="form-input" />
                                            </div>
                                        </div>
                                        <div class="mb-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <label for="payExp">Card Expiry</label>
                                                <input id="payExp" type="text" placeholder="Card Expiry"
                                                    class="form-input" />
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary">Add</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <template x-if="tab === 'preferences'">
                    <div class="switch">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">
                            <div class="panel space-y-5">
                                <h5 class="font-semibold text-lg mb-4">Choose Theme</h5>
                                <div class="flex justify-around">
                                    <label class="inline-flex cursor-pointer">
                                        <input class="form-radio ltr:mr-4 rtl:ml-4 cursor-pointer" type="radio"
                                            name="flexRadioDefault" checked="" />
                                        <span>
                                            <img class="ms-3" width="100" height="68" alt="settings-dark"
                                                src="/assets/images/settings-light.svg" />
                                        </span>
                                    </label>

                                    <label class="inline-flex cursor-pointer">
                                        <input class="form-radio ltr:mr-4 rtl:ml-4 cursor-pointer" type="radio"
                                            name="flexRadioDefault" />
                                        <span>
                                            <img class="ms-3" width="100" height="68" alt="settings-light"
                                                src="/assets/images/settings-dark.svg" />
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="panel space-y-5">
                                <h5 class="font-semibold text-lg mb-4">Activity data</h5>
                                <p>Download your Summary, Task and Payment History Data</p>
                                <button type="button" class="btn btn-primary">Download Data</button>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div class="panel space-y-5">
                                <h5 class="font-semibold text-lg mb-4">Public Profile</h5>
                                <p>Your <span class="text-primary">Profile</span> will be visible to anyone on the
                                    network.</p>
                                <label class="w-12 h-6 relative">
                                    <input type="checkbox"
                                        class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                                        id="custom_switch_checkbox1" />
                                    <span for="custom_switch_checkbox1"
                                        class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                                </label>
                            </div>
                            <div class="panel space-y-5">
                                <h5 class="font-semibold text-lg mb-4">Show my email</h5>
                                <p>Your <span class="text-primary">Email</span> will be visible to anyone on the
                                    network.</p>
                                <label class="w-12 h-6 relative">
                                    <input type="checkbox"
                                        class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                                        id="custom_switch_checkbox2" />
                                    <span for="custom_switch_checkbox2"
                                        class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white  dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                                </label>
                            </div>
                            <div class="panel space-y-5">
                                <h5 class="font-semibold text-lg mb-4">Enable keyboard shortcuts</h5>
                                <p>When enabled, press <span class="text-primary">ctrl</span> for help</p>
                                <label class="w-12 h-6 relative">
                                    <input type="checkbox"
                                        class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                                        id="custom_switch_checkbox3" />
                                    <span for="custom_switch_checkbox3"
                                        class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white  dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                                </label>
                            </div>
                            <div class="panel space-y-5">
                                <h5 class="font-semibold text-lg mb-4">Hide left navigation</h5>
                                <p>Sidebar will be <span class="text-primary">hidden</span> by default</p>
                                <label class="w-12 h-6 relative">
                                    <input type="checkbox"
                                        class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                                        id="custom_switch_checkbox4" />
                                    <span for="custom_switch_checkbox4"
                                        class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white  dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                                </label>
                            </div>
                            <div class="panel space-y-5">
                                <h5 class="font-semibold text-lg mb-4">Advertisements</h5>
                                <p>Display <span class="text-primary">Ads</span> on your dashboard</p>
                                <label class="w-12 h-6 relative">
                                    <input type="checkbox"
                                        class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                                        id="custom_switch_checkbox5" />
                                    <span for="custom_switch_checkbox5"
                                        class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white  dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                                </label>
                            </div>
                            <div class="panel space-y-5">
                                <h5 class="font-semibold text-lg mb-4">Social Profile</h5>
                                <p>Enable your <span class="text-primary">social</span> profiles on this network</p>
                                <label class="w-12 h-6 relative">
                                    <input type="checkbox"
                                        class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                                        id="custom_switch_checkbox6" />
                                    <span for="custom_switch_checkbox6"
                                        class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white  dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </template>
                <template x-if="tab === 'danger-zone'">
                    <div class="switch">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div class="panel space-y-5">
                                <h5 class="font-semibold text-lg mb-4">Purge Cache</h5>
                                <p>Remove the active resource from the cache without waiting for the predetermined cache
                                    expiry time.</p>
                                <button class="btn btn-secondary">Clear</button>
                            </div>
                            <div class="panel space-y-5">
                                <h5 class="font-semibold text-lg mb-4">Deactivate Account</h5>
                                <p>You will not be able to receive messages, notifications for up to 24 hours.</p>
                                <label class="w-12 h-6 relative">
                                    <input type="checkbox"
                                        class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                                        id="custom_switch_checkbox7" />
                                    <span for="custom_switch_checkbox7"
                                        class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                                </label>
                            </div>
                            <div class="panel space-y-5">
                                <h5 class="font-semibold text-lg mb-4">Delete Account</h5>
                                <p>Once you delete the account, there is no going back. Please be certain.</p>
                                <button class="btn btn-danger btn-delete-account">Delete my account</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <script>
        // Inicializar Select2
        document.addEventListener("DOMContentLoaded", function() {
            // Inicializar todos los select con la clase "select2"
            document.querySelectorAll('.select2').forEach(function(select) {
                NiceSelect.bind(select, {
                    searchable: true
                });
            });
        })
    </script>
    <!-- <script src="{{ asset('assets/js/ubigeo.js') }}"></script> -->
    <!-- Agrega Select2 JS antes del cierre de </body> -->

    <!-- Cargar jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
    <!-- <script>
    $(document).ready(function() {
        $('select').niceSelect();  // Aplica Nice Select a todos los selectores en la página
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

        <!-- Toastr JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

</x-layout.default>