<x-layout.default>
    <!-- Incluye la biblioteca Signature Pad -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <!-- Cargar jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Incluir el archivo CSS de Nice Select -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">

    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Users</a>
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
                    <li class="inline-block">
                        <a href="javascript:;"
                            class="flex gap-2 p-4 border-b border-transparent hover:border-primary hover:text-primary"
                            :class="{ '!border-primary text-primary': tab == 'payment-details' }"
                            @click="tab='payment-details'">

                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                <circle opacity="0.5" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="1.5" />
                                <path d="M12 6V18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M15 9.5C15 8.11929 13.6569 7 12 7C10.3431 7 9 8.11929 9 9.5C9 10.8807 10.3431 12 12 12C13.6569 12 15 13.1193 15 14.5C15 15.8807 13.6569 17 12 17C10.3431 17 9 15.8807 9 14.5"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            Payment Details
                        </a>
                    </li>
                    <li class="inline-block">
                        <a href="javascript:;"
                            class="flex gap-2 p-4 border-b border-transparent hover:border-primary hover:text-primary"
                            :class="{ '!border-primary text-primary': tab == 'preferences' }"
                            @click="tab='preferences'">

                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                <circle cx="12" cy="6" r="4" stroke="currentColor"
                                    stroke-width="1.5" />
                                <ellipse opacity="0.5" cx="12" cy="17" rx="7" ry="4"
                                    stroke="currentColor" stroke-width="1.5" />
                            </svg>
                            Preferences
                        </a>
                    </li>
                    <li class="inline-block">
                        <a href="javascript:;"
                            class="flex gap-2 p-4 border-b border-transparent hover:border-primary hover:text-primary"
                            :class="{ '!border-primary text-primary': tab == 'danger-zone' }"
                            @click="tab='danger-zone'">

                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                <path
                                    d="M16.1007 13.359L16.5562 12.9062C17.1858 12.2801 18.1672 12.1515 18.9728 12.5894L20.8833 13.628C22.1102 14.2949 22.3806 15.9295 21.4217 16.883L20.0011 18.2954C19.6399 18.6546 19.1917 18.9171 18.6763 18.9651M4.00289 5.74561C3.96765 5.12559 4.25823 4.56668 4.69185 4.13552L6.26145 2.57483C7.13596 1.70529 8.61028 1.83992 9.37326 2.85908L10.6342 4.54348C11.2507 5.36691 11.1841 6.49484 10.4775 7.19738L10.1907 7.48257"
                                    stroke="currentColor" stroke-width="1.5" />
                                <path opacity="0.5"
                                    d="M18.6763 18.9651C17.0469 19.117 13.0622 18.9492 8.8154 14.7266C4.81076 10.7447 4.09308 7.33182 4.00293 5.74561"
                                    stroke="currentColor" stroke-width="1.5" />
                                <path opacity="0.5"
                                    d="M16.1007 13.3589C16.1007 13.3589 15.0181 14.4353 12.0631 11.4971C9.10807 8.55886 10.1907 7.48242 10.1907 7.48242"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            Danger Zone
                        </a>
                    </li>
                </ul>
                
                <template x-if="tab === 'home'">
                    <div>
                      
                    


                    
                    
                    <form id="update-form" method="POST" enctype="multipart/form-data" class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-4 mb-5 bg-white dark:bg-[#0e1726]">
    @csrf
    @method('PUT')
    <h6 class="text-lg font-bold mb-5">Información General</h6>
    <div class="flex flex-col sm:flex-row">
        <!-- Imagen de perfil -->
        <div class="ltr:sm:mr-4 rtl:sm:ml-4 w-full sm:w-2/12 mb-5">
            <label for="profile-image">
                <img id="profile-img" src="{{ $usuario->avatar ? 'data:image/jpeg;base64,'.base64_encode($usuario->avatar) : '/assets/images/profile-34.jpeg' }}" alt="image"
                    class="w-20 h-20 md:w-32 md:h-32 rounded-full object-cover mx-auto cursor-pointer" />
            </label>
            <input type="file" id="profile-image" name="profile-image" style="display:none;" accept="image/*" onchange="previewImage(event)" />
        </div>

        <!-- Formulario de campos -->
        <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-5">
            <!-- Nombre Completo -->
            <div>
                <label for="Nombre">Nombre Completo</label>
                <input id="Nombre" name="Nombre" type="text" value="{{ $usuario->Nombre }}" class="form-input" />
            </div>

            <!-- Apellido Paterno -->
            <div>
                <label for="apellidoPaterno">Apellido Paterno</label>
                <input id="apellidoPaterno" name="apellidoPaterno" type="text" value="{{ $usuario->apellidoPaterno }}" class="form-input" />
            </div>

            <!-- Apellido Materno -->
            <div>
                <label for="apellidoMaterno">Apellido Materno</label>
                <input id="apellidoMaterno" name="apellidoMaterno" type="text" value="{{ $usuario->apellidoMaterno }}" class="form-input" />
            </div>

            <!-- Tipo Documento -->
            <div>
                <label for="idTipoDocumento" class="block text-sm font-medium">Tipo Documento</label>
                <select id="idTipoDocumento" name="idTipoDocumento" class="select2 w-full" style="display:none">
                    <option value="" disabled>Seleccionar Tipo Documento</option>
                    @foreach ($tiposDocumento as $tipoDocumento)
                    <option value="{{ $tipoDocumento->idTipoDocumento }}" {{ $tipoDocumento->idTipoDocumento == $usuario->idTipoDocumento ? 'selected' : '' }}>
                        {{ $tipoDocumento->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Documento -->
            <div>
                <label for="documento">Documento</label>
                <input id="documento" name="documento" type="text" value="{{ $usuario->documento }}" class="form-input" />
            </div>

            <!-- Teléfono -->
            <div>
                <label for="telefono">Teléfono</label>
                <input id="telefono" type="text" name="telefono" value="{{ $usuario->telefono }}" class="form-input" />
            </div>

            <!-- Email -->
            <div>
                <label for="correo">Email</label>
                <input id="correo" name="correo" type="email" value="{{ $usuario->correo }}" class="form-input" />
            </div>

            <!-- Botones -->
            <div class="sm:col-span-2 mt-3">
                <button type="submit" class="btn btn-primary mr-2">Actualizar</button>
            </div>
        </div>
    </div>
</form>

<!-- jQuery (si no lo has incluido antes) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<!-- Toastr CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">

<script>
$(document).ready(function() {
    $('#update-form').on('submit', function(event) {
        event.preventDefault();  // Prevenir la recarga de la página
        
        var formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("usuarios.update", $usuario->idUsuario) }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    // Aquí puedes actualizar el contenido de la página con los nuevos datos
                    $('#profile-img').attr('src', 'data:image/jpeg;base64,' + btoa(String.fromCharCode.apply(null, new Uint8Array(response.usuario.avatar)))); 
                    $('#Nombre').val(response.usuario.Nombre);
                    $('#apellidoPaterno').val(response.usuario.apellidoPaterno);
                    $('#apellidoMaterno').val(response.usuario.apellidoMaterno);
                    $('#documento').val(response.usuario.documento);
                    $('#telefono').val(response.usuario.telefono);
                    $('#correo').val(response.usuario.correo);
                } else {
                    toastr.error('Hubo un problema al actualizar');
                }
            },
            error: function(xhr, status, error) {
                // Verificar si el error proviene de un problema de validación
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    // Mostrar todos los errores usando toastr
                    for (var field in errors) {
                        if (errors.hasOwnProperty(field)) {
                            toastr.error(errors[field].join(', ')); // Unir los mensajes de error si hay más de uno
                        }
                    }
                } else {
                    toastr.error('Hubo un error al intentar actualizar los datos');
                }
            }
        });
    });
});
</script>














<form id="config-form" method="POST" class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-4 bg-white dark:bg-[#0e1726]">
    @csrf
    @method('PUT')
    <h6 class="text-lg font-bold mb-5">Información Importante</h6>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        
        <!-- Sueldo por Hora -->
        <div>
            <label for="sueldoPorHora">Sueldo por Hora</label>
            <input type="number" name="sueldoPorHora" id="sueldoPorHora" placeholder="Ejemplo: 20.5" class="form-input" step="0.01" value="{{ $usuario->sueldoPorHora }}" />
        </div>

        <!-- Sucursal -->
        <div>
            <label for="idSucursal">Sucursal</label>
            <select name="idSucursal" id="idSucursal" class="form-input">
                <option value="" disabled>Selecciona una Sucursal</option>
                @foreach ($sucursales as $sucursal)
                    <option value="{{ $sucursal->idSucursal }}" {{ $usuario->idSucursal == $sucursal->idSucursal ? 'selected' : '' }}>{{ $sucursal->nombre }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tipo de Usuario -->
        <div>
            <label for="idTipoUsuario">Tipo de Usuario</label>
            <select name="idTipoUsuario" id="idTipoUsuario" class="form-input">
                <option value="" disabled>Selecciona un Tipo de Usuario</option>
                @foreach ($tiposUsuario as $tipoUsuario)
                    <option value="{{ $tipoUsuario->idTipoUsuario }}" {{ $usuario->idTipoUsuario == $tipoUsuario->idTipoUsuario ? 'selected' : '' }}>{{ $tipoUsuario->nombre }}</option>
                @endforeach
            </select>
        </div>

        <!-- Sexo -->
        <div>
            <label for="idSexo">Sexo</label>
            <select name="idSexo" id="idSexo" class="form-input">
                <option value="" disabled>Selecciona un Sexo</option>
                @foreach ($sexos as $sexo)
                    <option value="{{ $sexo->idSexo }}" {{ $usuario->idSexo == $sexo->idSexo ? 'selected' : '' }}>{{ $sexo->nombre }}</option>
                @endforeach
            </select>
        </div>

        <!-- Rol -->
        <div>
            <label for="idRol">Rol</label>
            <select name="idRol" id="idRol" class="form-input">
                <option value="" disabled>Selecciona un Rol</option>
                @foreach ($roles as $rol)
                    <option value="{{ $rol->idRol }}" {{ $usuario->idRol == $rol->idRol ? 'selected' : '' }}>{{ $rol->nombre }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tipo de Área -->
        <div>
            <label for="idTipoArea">Tipo de Área</label>
            <select name="idTipoArea" id="idTipoArea" class="form-input">
                <option value="" disabled>Selecciona un Tipo de Área</option>
                @foreach ($tiposArea as $tipoArea)
                    <option value="{{ $tipoArea->idTipoArea }}" {{ $usuario->idTipoArea == $tipoArea->idTipoArea ? 'selected' : '' }}>{{ $tipoArea->nombre }}</option>
                @endforeach
            </select>
        </div>

        <!-- Botones -->
        <div class="sm:col-span-2 mt-3">
            <button type="submit" class="btn btn-primary mr-2">Actualizar Información</button>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $('#config-form').on('submit', function(event) {
            event.preventDefault();  // Prevenir la recarga de la página

            var formData = $(this).serialize();  // Usamos serialize para enviar los datos como JSON
            
            $.ajax({
                url: '{{ route('usuario.config', $usuario->idUsuario) }}',
                type: 'PUT',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        // Aquí puedes actualizar los datos en el DOM si lo deseas
                        // Ejemplo: actualizar los valores en los campos de entrada
                        $('#sueldoPorHora').val(response.usuario.sueldoPorHora);
                        $('#idSucursal').val(response.usuario.idSucursal);
                        $('#idTipoUsuario').val(response.usuario.idTipoUsuario);
                        $('#idSexo').val(response.usuario.idSexo);
                        $('#idRol').val(response.usuario.idRol);
                        $('#idTipoArea').val(response.usuario.idTipoArea);
                    }
                },
                error: function(xhr) {
                    // Si hay errores, manejamos la respuesta de error
                    var errors = xhr.responseJSON.errors;
                    if (errors) {
                        // Mostrar los errores en el frontend
                        for (var field in errors) {
                            toastr.error(errors[field].join(", "));
                        }
                    } else {
                        toastr.error('Hubo un error al intentar actualizar los datos');
                    }
                }
            });
        });
    });
</script>








                    </div>
                </template>




                <template x-if="tab === 'payment-details'">



    <div>




    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">
      
    
    <div class="panel">
    <div class="mb-5">
        <h5 class="font-semibold text-lg mb-4">Firma Digital</h5>
        <p>Por favor, firme en el área de abajo para completar el proceso de validación.</p>
        
        <!-- Lienzo para la firma -->
        <canvas id="signature-pad" width="700" height="300" style="border: 1px solid #000;"></canvas>
        
        <!-- Botones para limpiar y guardar la firma -->
        <div class="mt-3">
            <button id="clear-btn" class="bg-gray-500 text-white px-4 py-2 rounded">Limpiar</button>
            <button id="save-btn" class="bg-blue-500 text-white px-4 py-2 rounded">Guardar Firma</button>
        </div>
    </div>
</div>


<script>
    // Obtén el lienzo y configura Signature Pad
    const canvas = document.getElementById('signature-pad');
    const signaturePad = new SignaturePad(canvas);

    // Botón para limpiar la firma
    document.getElementById('clear-btn').addEventListener('click', () => {
        signaturePad.clear();
    });

    // Botón para guardar la firma
    document.getElementById('save-btn').addEventListener('click', () => {
        if (signaturePad.isEmpty()) {
            alert("Por favor, proporciona tu firma primero.");
        } else {
            // Convierte la firma a una imagen en formato PNG
            const dataURL = signaturePad.toDataURL();
            
            // Aquí puedes enviar la firma al servidor o guardarla localmente
            console.log("Firma guardada:", dataURL);
            alert("Firma guardada correctamente.");
        }
    });
</script>









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
                                    <h5 class="font-semibold text-lg mb-4">Agregar dirección </h5>
                                    <!-- <p>Changes your New <span class="text-primary">Billing</span> Information.</p> -->
                                </div>
                                <div class="mb-5">
                                    <form action="{{ route('usuario.direccion', $usuario->idUsuario) }}" method="POST">
                                        @csrf
                                        @method('PUT') <!-- Usamos el método PUT para actualización -->

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
                                            <!-- Provincia -->
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

                                            <!-- Distrito -->
                                            <div>
                                                <label for="distrito" class="block text-sm font-medium">Distrito</label>
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

                                            <!-- Dirección -->
                                            <div>
                                                <label for="direccion" class="block text-sm font-medium">Dirección</label>
                                                <input id="direccion" name="direccion" type="text" class="form-input w-full"
                                                    value="{{ old('direccion', $usuario->direccion) }}" placeholder="Ingrese la dirección">
                                                <div id="direccion-error" class="text-red-500 text-sm" style="display: none;"></div>
                                            </div>
                                        </div>

                                        <!-- Botón de Actualización -->
                                        <button type="submit" class="btn btn-primary">Actualizar</button>
                                    </form>

                                </div>
                            </div>
                            <div class="panel">
                                <div class="mb-5">
                                    <h5 class="font-semibold text-lg mb-4">Numero de cuenta</h5>
                                    <!-- <p>Changes your New <span class="text-primary">Payment Method</span> Information.
                                    </p> -->
                                </div>
                                <div class="mb-5">
                                    <form>
                                        <div class="mb-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <label for="payBrand">Seleccione Tipo de cuenta</label>
                                                <select id="payBrand" class="form-select text-white-dark">
                                                    <option selected="">Numero interbancario</option>
                                                    <option>Numeero de cuenta</option>
                                                    <!-- <option>Visa</option>
                                                    <option>Discover</option> -->
                                                </select>
                                            </div>
                                            <div>
                                                <label for="payNumber">Numero de cuenta</label>
                                                <input id="payNumber" type="text" placeholder="Card Number"
                                                    class="form-input" />
                                            </div>
                                        </div>
                                        <!-- <div class="mb-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
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
                                        </div> -->
                                        <button type="button" class="btn btn-primary">Guardar</button>
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
                    var provinciaSeleccionada = '{{ old('
                    provincia ', $usuario->provincia) }}';
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
                    var distritoSeleccionado = '{{ old('
                    distrito ', $usuario->distrito) }}';
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
    <!-- Agrega Select2 JS antes del cierre de </body> -->



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





</x-layout.default>