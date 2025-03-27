
$(document).ready(function () {
    // Inicializar nice-select2 en todos los selects con clase .select2
    document.querySelectorAll('.select2').forEach(function (select) {
        NiceSelect.bind(select, {
            searchable: true
        });
    });

    // Cambio de marca para cargar modelos vía AJAX
    $('#idMarca').change(function () {
        var idMarca = $(this).val();
        if (idMarca) {
            $.ajax({
                url: '/modelos/' + idMarca,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    var $modeloSelect = $('#idModelo');
                    $modeloSelect.empty();
                    $modeloSelect.append(
                        '<option value="" disabled selected>Seleccionar Modelo</option>'
                    );
                    $.each(data, function (key, modelo) {
                        $modeloSelect.append('<option value="' + modelo
                            .idModelo + '">' + modelo.nombre + '</option>');
                    });
                    // Si existe instancia previa se destruye y se reinicializa (solo para selects que usen nice-select2)
                    if ($modeloSelect.data('niceSelectInstance')) {
                        $modeloSelect.data('niceSelectInstance').destroy();
                    }
                    // Nota: idModelo no usará nice-select2
                },
                error: function (xhr, status, error) {
                    console.error("Error en AJAX:", error);
                }
            });
        } else {
            $('#idModelo').empty();
            $('#idModelo').append('<option value="" disabled selected>Seleccionar Modelo</option>');
        }
    });
});



document.addEventListener("DOMContentLoaded", function () {
    // Inicializar mapa con Leaflet
    const map = L.map('map').setView([-12.0464, -77.0428], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    let marker;

    // function buscarDireccion() {
    //     const direccion = document.getElementById("direccion").value.trim();
    //     if (direccion) {
    //         const url =
    //             `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(direccion)}`;
    //         $.get(url, function(data) {
    //             if (data && data.length > 0) {
    //                 const lat = data[0].lat;
    //                 const lon = data[0].lon;
    //                 map.setView([lat, lon], 13);
    //                 if (marker) {
    //                     marker.setLatLng([lat, lon]);
    //                 } else {
    //                     marker = L.marker([lat, lon]).addTo(map);
    //                 }
    //                 document.getElementById('latitud').value = lat;
    //                 document.getElementById('longitud').value = lon;
    //             } else {
    //                 alert("No se encontraron resultados para esa dirección.");
    //             }
    //         });
    //     }
    // }
    // document.getElementById("direccion").addEventListener("input", function() {
    //     if (this.value.trim() !== "") {
    //         buscarDireccion();
    //     }
    // });
    map.on('click', function (e) {
        document.getElementById('latitud').value = e.latlng.lat;
        document.getElementById('longitud').value = e.latlng.lng;
        if (marker) {
            marker.setLatLng(e.latlng);
        } else {
            marker = L.marker(e.latlng).addTo(map);
        }
    });
});






document.addEventListener("DOMContentLoaded", function () {
    const config = {
        dateFormat: "d/m/Y",
        altInput: true,
        altFormat: "F j, Y",
        locale: "es",
        allowInput: true,
        disableMobile: "true",
        maxDate: "today",
        defaultDate: "today", // 👈 Mostrar la fecha actual por defecto
        onChange: function (selectedDates, dateStr, instance) {
            instance.input.value = instance.formatDate(selectedDates[0], "Y-m-d");
        }
    };

    flatpickr("#fechaCompra", config);
    flatpickr("#fechaTicket", config);
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
                select.innerHTML = '<option value="" disabled selected>Seleccionar Marca</option>';

                // Verificar si hay datos
                if (data.length > 0) {
                    // Llenar el select con las marcas
                    data.forEach(marca => {
                        const option = document.createElement('option');
                        option.value = marca.idMarca;
                        option.textContent = marca.nombre;
                        select.appendChild(option);
                    });
                    // Mostrar el select después de cargar las marcas
                    select.style.display = 'block'; // Mostrar el select
                } else {
                    // Si no hay marcas disponibles, mostrar un mensaje
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'No hay marcas disponibles';
                    select.appendChild(option);
                }

                // Ocultar el preload después de cargar las marcas
                preloadElement.style.display = 'none';

                // Inicializa nice-select (si usas nice-select) y guarda la instancia
                if (select.niceSelectInstance) {
                    select.niceSelectInstance.destroy();
                }
                select.niceSelectInstance = NiceSelect.bind(select, { searchable: true });

            })
            .catch(error => {
                console.error('Error al cargar las marcas:', error);
                preloadElement.style.display = 'none'; // Ocultar el preload en caso de error
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

        fetch(`/marcas-por-cliente-general/${clienteGeneralId}`) // Realizamos la consulta al servidor para obtener las marcas de un cliente general específico
            .then(response => response.json()) // Convertir la respuesta en formato JSON
            .then(data => {
                // Limpiar las opciones actuales del select
                select.innerHTML = '<option value="" disabled selected>Seleccionar Marca</option>';

                // Verificar si hay datos
                if (data.length > 0) {
                    // Llenar el select con las marcas
                    data.forEach(marca => {
                        const option = document.createElement('option');
                        option.value = marca.idMarca;
                        option.textContent = marca.nombre;
                        select.appendChild(option);
                    });
                    // Mostrar el select después de cargar las marcas
                    select.style.display = 'block'; // Mostrar el select
                } else {
                    // Si no hay marcas asociadas, mostrar un mensaje
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'No hay marcas disponibles';
                    select.appendChild(option);
                }

                // Ocultar el preload después de cargar las marcas
                preloadElement.style.display = 'none';

                // Inicializa nice-select (si usas nice-select) y guarda la instancia
                if (select.niceSelectInstance) {
                    select.niceSelectInstance.destroy();
                }
                select.niceSelectInstance = NiceSelect.bind(select, { searchable: true });

            })
            .catch(error => {
                console.error('Error al cargar las marcas:', error);
                preloadElement.style.display = 'none'; // Ocultar el preload en caso de error
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
        const tiendaSelectContainer = $("#selectTiendaContainer"); // Contenedor del select de tiendas
        const tiendaSelect = $("#idTienda"); // Select de tiendas
        const tiendaInput = $("#nombreTiendaInput"); // Input de tienda

        // ✅ 🔥 Eliminamos cualquier clase o estilo raro en el select
        tiendaSelect.removeAttr("class style").addClass("form-input w-full");

        // Ocultar select de tiendas e input al inicio
        tiendaSelectContainer.hide();
        tiendaInput.hide();
        // 🔹 Escuchar cambios en el select de cliente
        clienteSelect.on("change", function () {
            let clienteId = clienteSelect.val();

            if (!clienteId) {
                console.warn("⚠️ No se ha seleccionado un cliente.");
                return;
            }

            console.log(`🔍 Cliente seleccionado: ${clienteId}`);

            // Llamar a la API para verificar si el cliente es tienda
            $.get(`/api/cliente/${clienteId}`, function (data) {
                console.log("📌 Datos del cliente:", data);

                // 👉 Establecer dirección automáticamente
                $("#direccion").val(data.direccion || "");

                if (data.esTienda === "SI" || data.esTienda === 1) {
                    console.log("🏪 Cliente es tienda, cargando tiendas...");
                    mostrarSelectTiendas(clienteId);
                } else {
                    console.log("📝 Cliente NO es tienda, mostrando input.");
                    ocultarSelectTiendas();
                }
            }).fail(function () {
                console.error("❌ Error al obtener los datos del cliente.");
            });
        });


        function mostrarSelectTiendas(clienteId) {
            tiendaSelectContainer.show();
            tiendaSelect.hide();
            tiendaInput.hide();

            // 🔹 Limpiar y resetear el select
            tiendaSelect.empty().append('<option value="">Seleccionar Tienda</option>');

            // 🔹 Llamar a la API para obtener las tiendas del cliente
            $.get(`/api/cliente/${clienteId}/tiendas`, function (data) {
                console.log("🏪 Tiendas obtenidas:", data);

                if (data.length > 0) {
                    data.forEach(tienda => {
                        tiendaSelect.append(`<option value="${tienda.idTienda}">${tienda.nombre}</option>`);
                    });
                } else {
                    tiendaSelect.append('<option value="">No hay tiendas registradas</option>');
                    console.warn("⚠️ No hay tiendas registradas para este cliente.");
                }
            }).fail(function () {
                console.error("❌ Error al obtener tiendas del cliente.");
            }).always(function () {
                tiendaSelect.show(); // ✅ Mostramos el select después de cargar los datos
            });
        }

        // ✅ Función para ocultar el select de tiendas y mostrar el input de texto
        function ocultarSelectTiendas() {
            tiendaSelectContainer.hide();
            tiendaSelect.hide();
            tiendaInput.show();
        }

        // Ejecutar la validación al cargar la página por si hay un cliente seleccionado
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
















