$(document).ready(function () {
    let formValid = true; // Bandera que indica si el formulario es válido

    // Validar campos vacíos
    function checkEmptyFields() {
        formValid = true; // Asumimos que el formulario es válido inicialmente

        console.log("Verificando campos vacíos..."); // Log para ver si estamos entrando en la función

        // Definir los campos a validar
        const camposRequeridos = [
            '#ruc', '#nombre', '#email', '#celular', '#referencia', '#dirrecion',
            '#departamento', '#provincia', '#distrito', '#cliente', '#idCliente'
        ];

        // Comprobar si algún campo requerido está vacío
        camposRequeridos.forEach(function (campo) {
            console.log("Verificando campo: " + campo); // Ver qué campo estamos verificando

            if ($(campo).is('select')) {
                // Validación para campos de tipo select (comprobar si no se seleccionó una opción válida)
                if ($(campo).val() === "" || $(campo).val() === null) {
                    formValid = false; // Si algún campo está vacío, desactivar el envío
                    $(campo).addClass('border-red-500'); // Marcar el campo con borde rojo
                    $(campo).siblings('.text-red-500').text('Este campo es obligatorio').show(); // Mostrar el mensaje de error
                    console.log("Campo vacío: " + campo); // Log para mostrar el campo vacío
                } else {
                    $(campo).removeClass('border-red-500'); // Quitar el borde rojo si no está vacío
                    $(campo).siblings('.text-red-500').hide(); // Ocultar el mensaje de error si no está vacío
                }
            } else {
                // Validación para otros tipos de campos (input)
                if ($(campo).val() === '') {
                    formValid = false; // Si algún campo está vacío, desactivar el envío
                    $(campo).addClass('border-red-500'); // Marcar el campo con borde rojo
                    $(campo).siblings('.text-red-500').text('Este campo es obligatorio').show(); // Mostrar el mensaje de error
                    console.log("Campo vacío: " + campo); // Log para mostrar el campo vacío
                } else {
                    $(campo).removeClass('border-red-500'); // Quitar el borde rojo si no está vacío
                    $(campo).siblings('.text-red-500').hide(); // Ocultar el mensaje de error si no está vacío
                }
            }
        });
    }

    // Añadir evento para los select
    $('#departamento, #provincia, #distrito, #idCliente, #dirrecion, #referencia').on('change', function () {
        checkEmptyFields(); // Revalidar campos vacíos cada vez que cambie la selección
    });

    // Interceptar el envío del formulario
    $('#tiendaForm').submit(function (event) {
        console.log("Formulario a enviar..."); // Log para indicar que estamos interceptando el envío
        checkEmptyFields(); // Verificar si hay campos vacíos antes de enviar

        if (!formValid) {
            event.preventDefault(); // Evitar el envío del formulario
            console.log("Formulario no válido, se ha bloqueado el envío"); // Log para ver que el formulario no es válido
            alert('Hay campos vacíos o repetidos. Por favor, corrija los errores y vuelva a intentarlo.'); // Mostrar alerta
        } else {
            console.log("Formulario válido, se enviará"); // Log para ver que el formulario es válido
        }
    });





 // Validar RUC en tiempo real
$('#ruc').on('input', function() {
    let ruc = $(this).val();
    console.log("Verificando RUC: " + ruc); // Log para ver el valor del RUC

    // Verificar si el RUC contiene solo números
    if (/[^0-9]/.test(ruc)) {
        $('#ruc').addClass('border-red-500');
        $('#ruc-error').text('El RUC solo debe contener números').show();
        formValid = false;
        return;
    } else {
        $('#ruc').removeClass('border-red-500');
        $('#ruc-error').hide();
    }

    // Verificar que el RUC tenga más de 8 dígitos
    if (ruc.length < 8) { // Esto asegura que el RUC tenga al menos 8 dígitos
        $('#ruc').addClass('border-red-500');
        $('#ruc-error').text('El RUC debe tener al menos 8 dígitos').show();
        formValid = false;
        return;
    } else {
        $('#ruc').removeClass('border-red-500');
        $('#ruc-error').hide();
    }

    // Si el RUC es válido, proceder con la validación en el servidor usando fetch
    fetch(routeRuc, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ ruc: ruc }) // Enviar el RUC como parte del cuerpo de la solicitud
    })
    .then(response => response.json())  // Convertir la respuesta a JSON
    .then(data => {
        console.log("Respuesta RUC: ", data); // Log para ver la respuesta del servidor

        if (data.exists) {
            $('#ruc').addClass('border-red-500');
            $('#ruc-error').text('El RUC ya está registrado').show();
            formValid = false; // Desactivar el envío del formulario
        } else {
            $('#ruc').removeClass('border-red-500');
            $('#ruc-error').hide();
            checkEmptyFields(); // Revalidar campos vacíos
        }
    })
    .catch(error => {
        console.error('Error en la validación del RUC:', error);
        $('#ruc').addClass('border-red-500');
        $('#ruc-error').text('Hubo un error al validar el RUC').show();
        formValid = false;
    });
});








// Validar Nombre en tiempo real
$('#nombre').on('input', function () {
    let nombre = $(this).val();
    console.log("Verificando Nombre: " + nombre); // Log para ver el valor del nombre

    // Realizar la solicitud fetch
    fetch('/validar/nombre', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ nombre: nombre }) // Enviar el nombre como parte del cuerpo de la solicitud
    })
    .then(response => response.json())  // Convertir la respuesta a JSON
    .then(data => {
        console.log("Respuesta Nombre: ", data); // Log para ver la respuesta del servidor

        if (data.exists) {
            $('#nombre').addClass('border-red-500');
            $('#nombre-error').text('El nombre ya está registrado').show();
            formValid = false; // Desactivar el envío del formulario
        } else {
            $('#nombre').removeClass('border-red-500');
            $('#nombre-error').hide();
            checkEmptyFields(); // Revalidar campos vacíos
        }
    })
    .catch(error => {
        console.error('Error en la validación del nombre:', error);
        $('#nombre').addClass('border-red-500');
        $('#nombre-error').text('Hubo un error al validar el nombre').show();
        formValid = false;
    });
});




    // Validar Email en tiempo real
    $('#email').on('input', function () {
        let email = $(this).val();
        console.log("Verificando Email: " + email); // Log para ver el valor del email

        // Verificar si el correo tiene un formato válido
        let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailPattern.test(email)) {
            $('#email').addClass('border-red-500');
            $('#email-error').text('Por favor ingrese un correo válido').show();
            formValid = false;
            return;
        } else {
            $('#email').removeClass('border-red-500');
            $('#email-error').hide();
        }

        $.post('{{ route("validar.email") }}', { email: email, _token: '{{ csrf_token() }}' }, function (response) {
            console.log("Respuesta Email: ", response); // Log para ver la respuesta del servidor
            if (response.exists) {
                $('#email').addClass('border-red-500');
                $('#email-error').text('El correo electrónico ya está registrado').show();
                formValid = false; // Desactivar el envío del formulario
            } else {
                $('#email').removeClass('border-red-500');
                $('#email-error').hide();
                checkEmptyFields(); // Revalidar campos vacíos
            }
        });
    });

    // Validar Celular en tiempo real
    $('#celular').on('input', function () {
        let celular = $(this).val();
        console.log("Verificando Celular: " + celular); // Log para ver el valor del celular

       

        // Verificar que el celular tenga más de 8 dígitos
        if (celular.length < 30) {  // Esto asegura que el celular tenga al menos 9 dígitos
            $('#celular').addClass('border-red-500');
            $('#celular-error').text('El celular debe tener al menos 9 dígitos').show();
            formValid = false;
            return;
        } else {
            $('#celular').removeClass('border-red-500');
            $('#celular-error').hide();
        }


        $.post('{{ route("validar.celular") }}', { celular: celular, _token: '{{ csrf_token() }}' }, function (response) {
            console.log("Respuesta Celular: ", response); // Log para ver la respuesta del servidor
            if (response.exists) {
                $('#celular').addClass('border-red-500');
                $('#celular-error').text('El celular ya está registrado').show();
                formValid = false; // Desactivar el envío del formulario
            } else {
                $('#celular').removeClass('border-red-500');
                $('#celular-error').hide();
                checkEmptyFields(); // Revalidar campos vacíos
            }
        });
    });

});


let tiendasCargadas = false; // Flag para verificar si las tiendas ya han sido cargadas





    
    // Función para cargar las tiendas desde el servidor
    function cargarTiendas() {
        const select = document.getElementById('idTienda');
        const preloadElement = document.getElementById('preloadTienda');
    
        // Mostrar el preload (cargando) sobre el select
        preloadElement.style.display = 'flex';
    
        fetch('/check-tiendas') // Realizamos la consulta al servidor
            .then(response => response.json()) // Convertir la respuesta en formato JSON
            .then(data => {
                // Limpiar las opciones actuales del select
                select.innerHTML = '<option value="" disabled selected>Seleccionar Tienda</option>';
    
                // Llenar el select con las tiendas
                data.forEach(tienda => {
                    const option = document.createElement('option');
                    option.value = tienda.idTienda;
                    option.textContent = tienda.nombre;
                    select.appendChild(option);
                });
    
                // Si ya existe una instancia previa de nice-select, la destruye
                if (select.niceSelectInstance) {
                    select.niceSelectInstance.destroy();
                }
    
                // Inicializa nice-select (si usas nice-select) y guarda la instancia
                select.niceSelectInstance = NiceSelect.bind(select, { searchable: true });
    
                // Ocultar el preload después de cargar las tiendas
                preloadElement.style.display = 'none';
    
                // Mostrar el select después de cargar las tiendas
                select.style.display = 'block'; // O 'inline-block' según tu diseño
            })
            .catch(error => {
                console.error('Error al cargar las tiendas:', error);
                preloadElement.style.display = 'none'; // Ocultar el preload en caso de error
            });
    }
    
    // Ocultar el select de tiendas inicialmente
    let selectTienda = document.getElementById('idTienda');
    selectTienda.style.display = 'none'; // Esto oculta el select de tiendas al principio
    
    // Cargar las tiendas solo si no se han cargado previamente
    if (!tiendasCargadas) {
        cargarTiendas();
        tiendasCargadas = true;
    }



