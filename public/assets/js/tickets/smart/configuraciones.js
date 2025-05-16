$(document).ready(function () {
    document.querySelectorAll('.select2').forEach(function (select) {
        NiceSelect.bind(select, { searchable: true });
    });

    // Ocultar inicialmente el contenedor del select modelo
    $('.select-modelo-container').hide();

    $('#idMarca').change(function () {
        var idMarca = $(this).val();
        if (idMarca) {
            $('#preload-modelo').show();

            $.ajax({
                url: '/modelos/' + idMarca,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    var $modeloSelect = $('#idModelo');

                    // 🔥 Elimina el nice-select anterior del DOM
                    $modeloSelect.next('.nice-select').remove();

                    // 🔥 Reinicia el select
                    $modeloSelect.empty().append('<option value="" disabled selected>Seleccionar Modelo</option>');

                    $.each(data, function (key, modelo) {
                        $modeloSelect.append('<option value="' + modelo.idModelo + '">' + modelo.nombre + '</option>');
                    });

                    // Mostrar contenedor
                    $('.select-modelo-container').show();

                    // 🔁 Re-bind
                    const instance = NiceSelect.bind($modeloSelect[0], { searchable: true });
                    $modeloSelect.data('niceSelectInstance', instance);
                },
                error: function (xhr, status, error) {
                    console.error("Error en AJAX:", error);
                },
                complete: function () {
                    $('#preload-modelo').hide();
                }
            });
        } else {
            $('#idModelo').empty().append('<option value="" disabled selected>Seleccionar Modelo</option>');
            $('.select-modelo-container').hide();
        }
    });
});



// document.addEventListener("DOMContentLoaded", function () {
//     // Inicializar mapa con Leaflet
//     const map = L.map('map').setView([-12.0464, -77.0428], 13);
//     L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
//         maxZoom: 19,
//         attribution: '© OpenStreetMap contributors'
//     }).addTo(map);

//     let marker;

 
//     map.on('click', function (e) {
//         document.getElementById('latitud').value = e.latlng.lat;
//         document.getElementById('longitud').value = e.latlng.lng;
//         if (marker) {
//             marker.setLatLng(e.latlng);
//         } else {
//             marker = L.marker(e.latlng).addTo(map);
//         }
//     });
// });


document.addEventListener("DOMContentLoaded", function () {
    // Configuración para la fecha de compra (solo fecha, sin hora)
    const configFechaCompra = {
        dateFormat: "d/m/Y", // Formato de fecha sin hora
        altInput: true, // Mostrar en un formato alternativo
        altFormat: "F j, Y", // Formato alternativo para mostrar
        locale: "es", // Idioma en español
        allowInput: true, // Permitir que el usuario ingrese manualmente la fecha
        disableMobile: "true", // Deshabilitar el calendario en móviles (si es necesario)
        maxDate: "today", // No permitir fechas futuras
        onChange: function (selectedDates, dateStr, instance) {
            // Convertir la fecha a formato Y-m-d al seleccionarla
            const formattedDate = instance.formatDate(selectedDates[0], "Y-m-d");
            instance.input.value = formattedDate; // Asignar el valor con el formato correcto

            // Mostrar en consola el valor formateado
            console.log("Fecha de compra seleccionada (Y-m-d):", formattedDate);
        }
    };

    flatpickr("#fechaCompra", configFechaCompra); // Inicializar para la fecha de compra

    // Configuración para la fecha del ticket (con fecha y hora)
    const configFechaTicket = {
        ...configFechaCompra, // Copiar la configuración anterior
        enableTime: true, // Activar la selección de la hora
        noCalendar: false, // Asegurar que el calendario de fechas esté visible
        dateFormat: "Y-m-d H:i", // Formato de fecha y hora (Ajustado para enviar Y-m-d H:i)
        altFormat: "F j, Y H:i", // Formato alternativo para mostrar
        time_24hr: true, // Utilizar formato de 24 horas para la hora
        onChange: function (selectedDates, dateStr, instance) {
            // Asignar el valor con el formato adecuado para fecha y hora
            const formattedDateTime = instance.formatDate(selectedDates[0], "Y-m-d H:i");
            instance.input.value = formattedDateTime; // Asignar el valor con el formato correcto

            // Mostrar en consola el valor formateado
            console.log("Fecha y hora seleccionada (Y-m-d H:i):", formattedDateTime);
        }
    };

    flatpickr("#fechaTicket", configFechaTicket); // Inicializar para la fecha de ticket
});



document.addEventListener("DOMContentLoaded", function () {
    const selectCliente = document.getElementById("idCliente");
    const latitudField = document.getElementById("latitud").closest("div");
    const longitudField = document.getElementById("longitud").closest("div");
    const mapaField = document.getElementById("map").closest("div");

    function verificarClienteEsTienda() {
        const clienteSeleccionado = selectCliente.options[selectCliente.selectedIndex];
        if (clienteSeleccionado) {
            const esTienda = clienteSeleccionado.dataset.tienda === "1";
            if (esTienda) {
                tiendaField.style.display = "none";
                latitudField.style.display = "none";
                longitudField.style.display = "none";
                mapaField.style.display = "none";
            } else {
                tiendaField.style.display = "";
                latitudField.style.display = "";
                longitudField.style.display = "";
                mapaField.style.display = "";
            }
        }
    }
    selectCliente.addEventListener("change", verificarClienteEsTienda);
    verificarClienteEsTienda();
});
document.addEventListener("DOMContentLoaded", function () {
    const selectTipoDocumento = document.getElementById("idTipoDocumento");
    const esTiendaContainer = document.getElementById("esTiendaContainer");

    selectTipoDocumento.addEventListener("change", function () {
        const selectedText = selectTipoDocumento.options[selectTipoDocumento.selectedIndex].text
            .trim();
        if (selectedText === "RUC") {
            esTiendaContainer.classList.remove("hidden");
        } else {
            esTiendaContainer.classList.add("hidden");
        }
    });
});






document.addEventListener('DOMContentLoaded', function () {
    let clientesCargados = false; // Variable para verificar si los clientes ya fueron cargados
    let marcasCargadas = false; // Flag para verificar si las marcas ya han sido cargadas
    // let tiendasCargadas = false; // Flag para verificar si las tiendas ya han sido cargadas





    // console.log(cargarClientesGenerales);

    // Función para cargar los clientes
    function cargarClientes() {
        fetch('/clientesdatoscliente')
            .then(response => response.json())
            .then(data => {
                const select = document.getElementById('idCliente');

                // Vaciar y llenar el select con las opciones
                select.innerHTML = '<option value="" disabled selected>Seleccionar Cliente</option>';
                data.forEach(cliente => {
                    const option = document.createElement('option');
                    option.value = cliente.idCliente;
                    option.textContent = `${cliente.nombre} - ${cliente.documento}`;
                    option.dataset.tienda = cliente.esTienda;
                    option.dataset.direccion = cliente.direccion; // <--- AÑADIDO
                    select.appendChild(option);
                });


                // Si ya existe una instancia previa de nice-select, la destruye
                if (select.niceSelectInstance) {
                    select.niceSelectInstance.destroy();
                }
                // Inicializa nice-select y guarda la instancia en el select
                select.niceSelectInstance = NiceSelect.bind(select, {
                    searchable: true
                });

                // Mostrar el select después de cargar los datos
                select.style.display = 'block'; // O 'inline-block' según tu diseño
            })
            .catch(error => console.error('Error al cargar clientes:', error));
    }




    // Ocultar el select de clientes inicialmente
    let selectCliente = document.getElementById('idCliente');
    selectCliente.style.display = 'none'; // Esto oculta el primer select de "Cliente" al principio

    // Cargar los clientes solo si no se han cargado previamente
    if (!clientesCargados) {
        cargarClientes();
        clientesCargados = true;
    }


    // Función para cargar todas las marcas desde el servidor
    function cargarTodasLasMarcas() {
        const select = document.getElementById('idMarca');
        const preloadElement = document.getElementById('preload');

        // Mostrar el preload (cargando) sobre el select
        preloadElement.style.display = 'flex';

        // Asegurarse de que el select esté oculto mientras se cargan las marcas
        select.style.display = 'none'; // Ocultar el select de marcas inicialmente

        fetch('/check-marcas') // Realizamos la consulta al servidor para obtener todas las marcas
            .then(response => response.json()) // Convertir la respuesta en formato JSON
            .then(data => {
                // Limpiar las opciones actuales del select
                select.innerHTML = '<option value="" disabled selected>Seleccionar </option>';

                if (data.length > 0) {
                    data.forEach(marca => {
                        const option = document.createElement('option');
                        option.value = marca.idMarca;
                        option.textContent = marca.nombre;
                        select.appendChild(option);
                    });
                    select.style.display = 'block';
                } else {
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'No hay marcas disponibles';
                    select.appendChild(option);
                }

                preloadElement.style.display = 'none';

                // Inicializa nice-select
                if (select.niceSelectInstance) {
                    select.niceSelectInstance.destroy();
                }
                select.niceSelectInstance = NiceSelect.bind(select, { searchable: true });

                select.style.opacity = '0';
                select.style.position = 'absolute';
                select.style.pointerEvents = 'none';
                select.style.width = '0';
                select.style.height = '0';
                select.style.fontSize = '0';
                select.classList.remove('border-red-500'); // Opcional si usas validaciones visuales



            })
            .catch(error => {
                console.error('Error al cargar las marcas:', error);
                preloadElement.style.display = 'none';
            });
    }


    // Función para cargar las marcas desde el servidor según el cliente general seleccionado
    function cargarMarcasPorClienteGeneral(clienteGeneralId) {
        const select = document.getElementById('idMarca');
        const preloadElement = document.getElementById('preload');

        // Mostrar el preload (cargando) sobre el select
        preloadElement.style.display = 'flex';

        // Asegurarse de que el select esté oculto mientras se cargan las marcas
        select.style.display = 'none'; // Ocultar el select de marcas inicialmente

        fetch(`/marcas-por-cliente-general/${clienteGeneralId}`)
            .then(response => response.json())
            .then(data => {
                // Limpiar las opciones actuales del select
                select.innerHTML = '<option value="" disabled selected>Seleccionar Marca</option>';

                if (data.length > 0) {
                    data.forEach(marca => {
                        const option = document.createElement('option');
                        option.value = marca.idMarca;
                        option.textContent = marca.nombre;
                        select.appendChild(option);
                    });
                    select.style.display = 'block';
                } else {
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'No hay marcas disponibles';
                    select.appendChild(option);
                }

                preloadElement.style.display = 'none';

                // Inicializa nice-select (si usas nice-select) y guarda la instancia
                if (select.niceSelectInstance) {
                    select.niceSelectInstance.destroy();
                }
                select.niceSelectInstance = NiceSelect.bind(select, { searchable: true });

                // Estilo para centrar texto verticalmente
                select.style.opacity = '0';
                select.style.position = 'absolute';
                select.style.pointerEvents = 'none';
                select.style.width = '0';
                select.style.height = '0';
                select.style.fontSize = '0';
                select.classList.remove('border-red-500'); // Opcional si usas validaciones visuales


            })
            .catch(error => {
                console.error('Error al cargar las marcas:', error);
                preloadElement.style.display = 'none';
            });
    }


    // Evento para cuando se selecciona un cliente general
    document.getElementById('idClienteGeneral').addEventListener('change', function () {
        let clienteGeneralId = this.value;
        if (clienteGeneralId) {
            console.log('Cliente General seleccionado:', clienteGeneralId);
            cargarMarcasPorClienteGeneral(clienteGeneralId);  // Llamamos la función para cargar las marcas según el cliente general
        } else {
            // Si no hay cliente general seleccionado, cargar todas las marcas
            cargarTodasLasMarcas();
        }
    });

    // Cargar todas las marcas inicialmente si no hay cliente general seleccionado
    window.onload = function () {
        let clienteGeneralId = document.getElementById('idClienteGeneral').value;
        if (!clienteGeneralId) {
            cargarTodasLasMarcas(); // Si no hay cliente general seleccionado al cargar la página, cargamos todas las marcas
        }
    }

    $(document).ready(function () {
        console.log("🔹 DOM completamente cargado");
    
        // Elementos
        const clienteSelect = $("#idCliente");
        const tiendaSelectContainer = $("#selectTiendaContainer");
        const tiendaSelect = $("#idTienda");
        let tipoDocumentoCliente = null; // Guardamos el tipo de documento del cliente
        let esTiendaCliente = null; // Guardamos el estado de la tienda del cliente
    
        // ✅ Limpiar clases raras y aplicar estilos
        tiendaSelect.removeAttr("class style").addClass("form-input w-full");
    
        // Ocultar select de tiendas al inicio
        tiendaSelectContainer.hide();
    
        // 🔹 Evento al cambiar cliente
        clienteSelect.on("change", function () {
            let clienteId = clienteSelect.val();
    
            if (!clienteId) {
                console.warn("⚠️ No se ha seleccionado un cliente.");
                return;
            }
    
            console.log(`🔍 Cliente seleccionado: ${clienteId}`);
    
            // Obtener datos del cliente
            $.get(`/api/cliente/${clienteId}`, function (data) {
                console.log("📌 Datos del cliente:", data);
    
                tipoDocumentoCliente = data.idTipoDocumento;
                esTiendaCliente = data.esTienda;
                
                console.log('tienda: ', esTiendaCliente);
    
                // Llenar dirección según el tipo de documento
                if (tipoDocumentoCliente == 8) {
                    // Tipo doc 8: dirección del cliente
                    $("#direccion").val(data.direccion || "");
                } else {
                    // Otro tipo: se usará la dirección de la tienda
                    $("#direccion").val("");
                }
    
                // Lógica de visualización de tiendas
                if (tipoDocumentoCliente == 8 || esTiendaCliente == "NO") {
                    // Tipo de documento 8 o tienda es 0, mostrar todas las tiendas
                    console.log("🌍 Cargando todas las tiendas...");
                    mostrarSelectTiendas(clienteId, true);
                } else {
                    // Otro tipo de documento y tienda = 1, mostrar solo tiendas relacionadas
                    console.log("🏪 Cargando tiendas relacionadas...");
                    mostrarSelectTiendas(clienteId, false);
                }
    
            }).fail(function () {
                console.error("❌ Error al obtener los datos del cliente.");
            });
        });
    
        // 🔄 Evento al cambiar la tienda seleccionada
        tiendaSelect.on("change", function () {
            const tiendaId = tiendaSelect.val();
    
            // Solo si el tipo de documento del cliente NO es 8
            if (!tiendaId || tipoDocumentoCliente == 8) return;
    
            $.get(`/api/tienda/${tiendaId}`, function (tienda) {
                console.log("📦 Datos de la tienda:", tienda);
                $("#direccion").val(tienda.direccion || "");
            }).fail(function () {
                console.error("❌ Error al obtener datos de la tienda.");
            });
        });
    
        // 🔧 Función para cargar tiendas
        function mostrarSelectTiendas(clienteId, cargarTodasTiendas) {
            tiendaSelectContainer.show();
            tiendaSelect.show();
    
            tiendaSelect.empty().append('<option value="" selected disabled>Seleccionar Tienda</option>');
    
            const urlTiendas = cargarTodasTiendas
                ? `/api/tiendas` // Si cargarTodasTiendas es true, cargamos todas las tiendas
                : `/api/cliente/${clienteId}/tiendas`; // Si no, cargamos solo las tiendas relacionadas al cliente
    
            $.get(urlTiendas, function (data) {
                console.log("🏪 Tiendas obtenidas:", data);
    
                if (data.length > 0) {
                    data.forEach(tienda => {
                        tiendaSelect.append(`<option value="${tienda.idTienda}">${tienda.nombre}</option>`);
                    });
                } else {
                    tiendaSelect.append('<option value="">No hay tiendas registradas</option>');
                }
            }).fail(function () {
                console.error("❌ Error al obtener tiendas.");
            }).always(function () {
                // 💄 Estilos y configuración de nice-select
                if (window.NiceSelect) {
                    tiendaSelect.next(".nice-select").remove();
                    tiendaSelect.show();
                    NiceSelect.bind(tiendaSelect[0], { searchable: true });
                    tiendaSelect.hide();
                    setTimeout(() => {
                        const nice = tiendaSelect.next(".nice-select");
                        nice.css({
                            'line-height': '2.2rem !important',
                            'height': '2.4rem',
                            'padding-top': '0.2rem',
                            'padding-bottom': '0.2rem'
                        });
                        nice.find('.current').css({
                            'line-height': '2.2rem !important',
                            'padding-top': '0 !important',
                            'padding-bottom': '0 !important'
                        });
                    }, 50);
                }
            });
        }
    
        // Ejecutar al cargar si ya hay cliente seleccionado
        if (clienteSelect.val()) {
            clienteSelect.trigger("change");
        }
    });
    
    







    // Evento para cuando se selecciona un cliente
    document.getElementById('idCliente').addEventListener('change', function () {
        let clienteId = this.value;
        if (clienteId) {
            console.log('Cliente seleccionado:', clienteId); // Verificar si el cliente es seleccionado
            fetch(`/clientes-generales/${clienteId}`)
                .then(response => response.json())
                .then(data => {
                    let select = document.getElementById('idClienteGeneral');
                    select.innerHTML = '<option value="" selected>Seleccionar Cliente General</option>'; // Limpiar

                    // Verificar si se recibió algún dato
                    console.log('Clientes generales:', data); // Verifica que se reciban los clientes generales

                    // Llenar el select con los clientes generales
                    data.forEach(clienteGeneral => {
                        let option = document.createElement('option');
                        option.value = clienteGeneral.idClienteGeneral;
                        option.textContent = clienteGeneral.descripcion;
                        select.appendChild(option);
                    });

                    // Si hay solo un cliente general, lo seleccionamos automáticamente
                    if (data.length === 1) {
                        select.value = data[0].idClienteGeneral; // Seleccionar automáticamente el único cliente
                        cargarMarcasPorClienteGeneral(data[0].idClienteGeneral); // Ejecutar la función automáticamente
                    }

                    // No inicializamos NiceSelect en el select de Cliente General
                    // Simplemente utilizamos el select estándar
                })
                .catch(error => console.error('Error al cargar clientes generales:', error));
        } else {
            // Limpiar el select si no hay cliente seleccionado
            document.getElementById('idClienteGeneral').innerHTML =
                '<option value="" selected>Seleccionar Cliente General</option>';
        }
    });








    // Evento de envío del formulario de cliente
    document.getElementById('clienteForm').addEventListener('submit', function (event) {
        event.preventDefault(); // Evitar el envío normal del formulario

        let formData = new FormData(this); // Obtener los datos del formulario
        console.log('Datos del formulario:', Object.fromEntries(formData.entries())); // Ver los datos del formulario

        fetch('/guardar-cliente', {
            method: 'POST',
            body: formData, // Enviar los datos del formulario
        })
            .then(response => response.json()) // Parsear la respuesta como JSON
            .then(data => {
                console.log('Respuesta del servidor (JSON):', data); // Verificar la respuesta
                if (data.errors) {
                    // Mostrar solo el primer error
                    for (let field in data.errors) {
                        toastr.error(data.errors[field][0]); // Mostrar solo el primer error del campo
                        break; // Salir del bucle después de mostrar el primer error
                    }
                } else {
                    // Mostrar mensaje de éxito
                    toastr.success(data.message);

                    // Recargar los clientes después de guardar el cliente
                    cargarClientes();

                    // Limpiar el formulario y cerrar el modal si es necesario
                    document.getElementById('clienteForm').reset();
                    openClienteModal = false; // Cerrar el modal si lo tienes
                }
            })
            .catch(error => {
                console.error('Error al guardar el cliente:', error);
            });
    });





    // Evento de envío del formulario de cliente
    document.getElementById('clientGeneralForm').addEventListener('submit', function (event) {
        event.preventDefault(); // Evitar el envío normal del formulario

        let formData = new FormData(this); // Obtener los datos del formulario
        console.log('Datos del formulario:', Object.fromEntries(formData
            .entries())); // Ver los datos del formulario

        fetch('/guardar-cliente-general-smart', {
            method: 'POST',
            body: formData, // Enviar los datos del formulario
        })
            .then(response => response.json()) // Parsear la respuesta como JSON
            .then(data => {
                console.log('Respuesta del servidor (JSON):', data); // Verificar la respuesta
                if (data.errors) {
                    // Mostrar errores si los hay
                    toastr.error(data.errors);
                } else {
                    // Mostrar mensaje de éxito
                    location.reload();
                    toastr.success(data.message);

                    // Recargar los clientes después de guardar el cliente
                    cargarClientes();

                    // Limpiar el formulario y cerrar el modal si es necesario
                    document.getElementById('clientGeneralForm').reset();
                    openClienteGeneralModal = false; // Cerrar el modal si lo tienes
                }
            })
            .catch(error => {
                console.error('Error al guardar el cliente:', error);
            });
    });


    // Evento de envío del formulario de marca
    document.getElementById('marcaForm').addEventListener('submit', function (event) {
        event.preventDefault(); // Evitar el envío normal del formulario

        let formData = new FormData(this); // Obtener los datos del formulario
        console.log('Datos del formulario:', Object.fromEntries(formData.entries())); // Ver los datos del formulario

        fetch('/guardar-marca-smart', {
            method: 'POST',
            body: formData, // Enviar los datos del formulario
        })
            .then(response => response.json()) // Parsear la respuesta como JSON
            .then(data => {
                console.log('Respuesta del servidor (JSON):', data); // Verificar la respuesta

                if (data.errors) {
                    // Mostrar solo el primer error de validación
                    for (let field in data.errors) {
                        toastr.error(data.errors[field][0]); // Mostrar solo el primer error de cada campo
                        break; // Salir del bucle después de mostrar el primer error
                    }
                } else if (data.error) {
                    // Si hay un error general (como el que mencionas)
                    toastr.error(data.error); // Mostrar el mensaje de error general
                } else {
                    // Mostrar mensaje de éxito
                    toastr.success(data.message);

                    // Recargar las marcas después de guardar la marca
                    cargarMarcas();
                    cargarMarcass();

                    // Limpiar el formulario y cerrar el modal si es necesario
                    document.getElementById('marcaForm').reset();
                    openMarcaModal = false; // Cerrar el modal si lo tienes
                }
            })
            .catch(error => {
                console.error('Error al guardar la marca:', error);
            });
    });







    // Evento de envío del marca
    document.getElementById('modeloForm').addEventListener('submit', function (event) {
        event.preventDefault(); // Evitar el envío normal del formulario

        let formData = new FormData(this); // Obtener los datos del formulario
        console.log('Datos del formulario:', Object.fromEntries(formData
            .entries())); // Ver los datos del formulario

        fetch('/guardar-modelo-smart', {
            method: 'POST',
            body: formData, // Enviar los datos del formulario
        })
            .then(response => response.json()) // Parsear la respuesta como JSON
            .then(data => {
                console.log('Respuesta del servidor (JSON):', data); // Verificar la respuesta
                if (data.errors) {
                    // Mostrar errores si los hay
                    toastr.error(data.errors);
                } else {
                    // Mostrar mensaje de éxito
                    toastr.success(data.message);

                    // Recargar los clientes después de guardar el cliente
                    cargarClientes();

                    // Limpiar el formulario y cerrar el modal si es necesario
                    document.getElementById('modeloForm').reset();
                    openClienteGeneralModal = false; // Cerrar el modal si lo tienes
                }
            })
            .catch(error => {
                console.error('Error al guardar el cliente:', error);
            });
    });










});





document.addEventListener("DOMContentLoaded", function () {
    // Inicializar nice-select2
    NiceSelect.bind(document.getElementById("idClienteGeneraloption"));

    const select = document.getElementById('idClienteGeneraloption');
    const selectedItemsContainer = document.getElementById('selected-items-list');

    // Función para actualizar los seleccionados
    function updateSelectedItems() {
        selectedItemsContainer.innerHTML = ''; // Limpiar el contenedor

        const selectedOptions = Array.from(select.selectedOptions); // Obtener las opciones seleccionadas

        selectedOptions.forEach(option => {
            const badge = document.createElement('span');
            badge.textContent = option.textContent;
            badge.className = 'badge bg-primary'; // Aplicar el estilo del badge
            selectedItemsContainer.appendChild(badge); // Agregar el badge al contenedor
        });
    }

    // Escuchar cambios en el select
    select.addEventListener('change', updateSelectedItems);

    // Actualizar los seleccionados al cargar la página
    updateSelectedItems();
});
document.addEventListener("DOMContentLoaded", function () {
    const tipoDocumento = document.getElementById("idTipoDocumento");
    const esTiendaContainer = document.getElementById("esTiendaContainer");

    tipoDocumento.addEventListener("change", function () {
        // Verificar si el texto del option seleccionado es "RUC"
        const selectedOptionText = tipoDocumento.options[tipoDocumento.selectedIndex].text;

        if (selectedOptionText === "RUC") {
            esTiendaContainer.classList.remove("hidden"); // Muestra el switch
        } else {
            esTiendaContainer.classList.add("hidden"); // Oculta el switch
        }
    });
});

selectCliente.addEventListener('change', function () {
    const selectedOption = this.options[this.selectedIndex];
    const direccion = selectedOption.dataset.direccion || '';
    document.getElementById('direccion').value = direccion;
});
















