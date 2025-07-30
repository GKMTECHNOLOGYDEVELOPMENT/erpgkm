$(document).ready(function () {
    // Ocultar inicialmente el contenedor
    $('.select-modelo-container').hide();

    // Inicializar select2 para Marca (con AJAX)
    $('#idMarca').select2({
        placeholder: 'Buscar marca...',
        width: '100%',
        ajax: {
            url: '/api/marcas',
            dataType: 'json',
            delay: 250,
            processResults: (data) => ({
                results: data.map((m) => ({
                    id: m.id,
                    text: m.nombre,
                })),
            }),
            cache: true,
        },
    });

    // Inicializar select2 para Modelo (vacío hasta que se seleccione una marca)
    $('#idModelo').select2({
        placeholder: 'Seleccionar Modelo',
        width: '100%',
        data: [] // vacío al inicio
    });

    // Evento al cambiar marca
    $('#idMarca').on('change', function () {
        const idMarca = $(this).val();

        if (idMarca) {
            $('#preload-modelo').show();

            $.ajax({
                url: '/modelos/' + idMarca,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    const $modelo = $('#idModelo');
                    $modelo.empty(); // limpiar opciones previas

                    $modelo.append('<option value="" disabled selected>Seleccionar Modelo</option>');

                    data.forEach(modelo => {
                        $modelo.append(new Option(modelo.nombre, modelo.idModelo));
                    });

                    // Forzar recarga de Select2
                    $modelo.trigger('change');

                    $('.select-modelo-container').show();
                },
                error: function (xhr, status, error) {
                    console.error("Error al cargar modelos:", error);
                },
                complete: function () {
                    $('#preload-modelo').hide();
                }
            });
        } else {
            $('#idModelo').empty().append('<option value="" disabled selected>Seleccionar Modelo</option>').trigger('change');
            $('.select-modelo-container').hide();
        }
    });
});


document.addEventListener('DOMContentLoaded', function () {
    // Configuración para la fecha de compra (solo fecha, sin hora)
    const configFechaCompra = {
        dateFormat: 'd/m/Y', // Formato de fecha sin hora
        altInput: true, // Mostrar en un formato alternativo
        altFormat: 'F j, Y', // Formato alternativo para mostrar
        locale: 'es', // Idioma en español
        allowInput: true, // Permitir que el usuario ingrese manualmente la fecha
        disableMobile: 'true', // Deshabilitar el calendario en móviles (si es necesario)
        maxDate: 'today', // No permitir fechas futuras
        onChange: function (selectedDates, dateStr, instance) {
            // Convertir la fecha a formato Y-m-d al seleccionarla
            const formattedDate = instance.formatDate(selectedDates[0], 'Y-m-d');
            instance.input.value = formattedDate; // Asignar el valor con el formato correcto

            // Mostrar en consola el valor formateado
            console.log('Fecha de compra seleccionada (Y-m-d):', formattedDate);
        },
    };

    flatpickr('#fechaCompra', configFechaCompra); // Inicializar para la fecha de compra

    // Configuración para la fecha del ticket (con fecha y hora)
    const configFechaTicket = {
        ...configFechaCompra, // Copiar la configuración anterior
        enableTime: true, // Activar la selección de la hora
        noCalendar: false, // Asegurar que el calendario de fechas esté visible
        dateFormat: 'Y-m-d H:i', // Formato de fecha y hora (Ajustado para enviar Y-m-d H:i)
        altFormat: 'F j, Y H:i', // Formato alternativo para mostrar
        time_24hr: true, // Utilizar formato de 24 horas para la hora
        onChange: function (selectedDates, dateStr, instance) {
            // Asignar el valor con el formato adecuado para fecha y hora
            const formattedDateTime = instance.formatDate(selectedDates[0], 'Y-m-d H:i');
            instance.input.value = formattedDateTime; // Asignar el valor con el formato correcto

            // Mostrar en consola el valor formateado
            console.log('Fecha y hora seleccionada (Y-m-d H:i):', formattedDateTime);
        },
    };

    flatpickr('#fechaTicket', configFechaTicket); // Inicializar para la fecha de ticket
});

document.addEventListener('DOMContentLoaded', function () {
    const selectCliente = document.getElementById('idCliente');
    const latitudField = document.getElementById('latitud').closest('div');
    const longitudField = document.getElementById('longitud').closest('div');
    const mapaField = document.getElementById('map').closest('div');

    function verificarClienteEsTienda() {
        const clienteSeleccionado = selectCliente.options[selectCliente.selectedIndex];
        if (clienteSeleccionado) {
            const esTienda = clienteSeleccionado.dataset.tienda === '1';
            if (esTienda) {
                tiendaField.style.display = 'none';
                latitudField.style.display = 'none';
                longitudField.style.display = 'none';
                mapaField.style.display = 'none';
            } else {
                tiendaField.style.display = '';
                latitudField.style.display = '';
                longitudField.style.display = '';
                mapaField.style.display = '';
            }
        }
    }
    selectCliente.addEventListener('change', verificarClienteEsTienda);
    verificarClienteEsTienda();
});
document.addEventListener('DOMContentLoaded', function () {
    const selectTipoDocumento = document.getElementById('idTipoDocumento');
    const esTiendaContainer = document.getElementById('esTiendaContainer');

    selectTipoDocumento.addEventListener('change', function () {
        const selectedText = selectTipoDocumento.options[selectTipoDocumento.selectedIndex].text.trim();
        if (selectedText === 'RUC') {
            esTiendaContainer.classList.remove('hidden');
        } else {
            esTiendaContainer.classList.add('hidden');
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
            .then((response) => response.json())
            .then((data) => {
                const select = document.getElementById('idCliente');

                // Vaciar y llenar el select manualmente
                select.innerHTML = '<option value="">Seleccionar Cliente</option>';
                data.forEach((cliente) => {
                    const option = document.createElement('option');
                    option.value = cliente.idCliente;
                    option.textContent = `${cliente.nombre} - ${cliente.documento}`;
                    option.dataset.tienda = cliente.esTienda;
                    option.dataset.direccion = cliente.direccion;
                    select.appendChild(option);
                });

                // ✅ Inicializa Select2
                if ($(select).hasClass('select2-hidden-accessible')) {
                    $(select).select2('destroy');
                }
                $(select).select2({
                    placeholder: 'Seleccionar Cliente',
                    width: '100%',
                });
            })
            .catch((error) => console.error('Error al cargar clientes:', error));
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
    const $select2 = $('#idMarca');
    const preloadElement = document.getElementById('preload');

    preloadElement.style.display = 'flex';
    select.style.display = 'none';

    fetch('/check-marcas')
        .then((response) => response.json())
        .then((data) => {
            select.innerHTML = '<option value="" disabled selected>Seleccionar</option>';

            if (data.length > 0) {
                data.forEach((marca) => {
                    const option = document.createElement('option');
                    option.value = marca.idMarca;
                    option.textContent = marca.nombre;
                    select.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'No hay marcas disponibles';
                select.appendChild(option);
            }

            // Reiniciar Select2
            if ($select2.hasClass('select2-hidden-accessible')) {
                $select2.select2('destroy');
            }
            $select2.select2({
                placeholder: 'Seleccionar Marca',
                width: '100%',
            });

            select.style.display = 'block';
            preloadElement.style.display = 'none';
        })
        .catch((error) => {
            console.error('Error al cargar marcas:', error);
            preloadElement.style.display = 'none';
        });
}

function cargarMarcasPorClienteGeneral(clienteGeneralId) {
    const $select2 = $('#idMarca');
    const preloadElement = document.getElementById('preload');

    console.log('Cargando marcas para cliente general ID:', clienteGeneralId);
    preloadElement.style.display = 'flex';

    fetch(`/marcas-por-cliente-general/${clienteGeneralId}`)
        .then((response) => response.json())
        .then((data) => {
            console.log('Marcas recibidas:', data);

            if ($select2.hasClass('select2-hidden-accessible')) {
                $select2.select2('destroy');
            }

            $select2.empty();

            if (data.length > 0) {
                $select2.append('<option value="">Seleccionar Marca</option>');
                data.forEach((marca) => {
                    $select2.append(new Option(marca.nombre, marca.idMarca));
                });
            } else {
                $select2.append('<option value="">No hay marcas disponibles</option>');
            }

            $select2.select2({
                placeholder: 'Seleccionar Marca',
                width: '100%',
            });

            preloadElement.style.display = 'none';
        })
        .catch((error) => {
            console.error('Error al cargar marcas por cliente general:', error);
            preloadElement.style.display = 'none';
        });
}




  $(document).on('change', '#idClienteGeneral', function () {
    let clienteGeneralId = this.value;
    if (clienteGeneralId) {
        console.log('Cliente General seleccionado:', clienteGeneralId);
        cargarMarcasPorClienteGeneral(clienteGeneralId);
    } else {
        cargarTodasLasMarcas();
    }
});


    // Cargar todas las marcas inicialmente si no hay cliente general seleccionado
    window.onload = function () {
        let clienteGeneralId = document.getElementById('idClienteGeneral').value;
        if (!clienteGeneralId) {
            cargarTodasLasMarcas(); // Si no hay cliente general seleccionado al cargar la página, cargamos todas las marcas
        }
    };

    $(document).ready(function () {
    const clienteSelect = $('#idCliente');
    const tiendaSelect = $('#idTienda');
    const tiendaSelectContainer = $('#selectTiendaContainer');
    let tipoDocumentoCliente = null;
    let esTiendaCliente = null;

    tiendaSelectContainer.hide(); // Ocultar al inicio

    // Iniciar Select2 para tiendas
    tiendaSelect.select2({
        placeholder: 'Seleccionar Tienda',
        width: '100%'
    });

    clienteSelect.on('change', function () {
        const clienteId = $(this).val();
        if (!clienteId) return;

        $.get(`/api/cliente/${clienteId}`, function (data) {
            tipoDocumentoCliente = data.idTipoDocumento;
            esTiendaCliente = data.esTienda;

            // Asignar dirección
            if (tipoDocumentoCliente == 8) {
                $('#direccion').val(data.direccion || '');
            } else {
                $('#direccion').val('');
            }

            const cargarTodas = (tipoDocumentoCliente == 8 || esTiendaCliente == 0);
            mostrarSelectTiendas(clienteId, cargarTodas);
        }).fail(() => {
            console.error('❌ Error al obtener los datos del cliente.');
        });
    });

    tiendaSelect.on('change', function () {
        const tiendaId = $(this).val();
        if (!tiendaId || tipoDocumentoCliente == 8) return;

        $.get(`/api/tienda/${tiendaId}`, function (tienda) {
            $('#direccion').val(tienda.direccion || '');
        }).fail(() => {
            console.error('❌ Error al obtener datos de la tienda.');
        });
    });

    function mostrarSelectTiendas(clienteId, cargarTodasTiendas) {
        tiendaSelectContainer.show();
        tiendaSelect.prop('disabled', false).empty()
            .append('<option value="" selected disabled>Seleccionar Tienda</option>');

        const url = cargarTodasTiendas
            ? '/api/tiendas'
            : `/api/cliente/${clienteId}/tiendas`;

        $.get(url, function (data) {
            if (data.length > 0) {
                data.forEach(t => {
                    tiendaSelect.append(`<option value="${t.idTienda}">${t.nombre}</option>`);
                });
            } else {
                tiendaSelect.append('<option value="">No hay tiendas registradas</option>');
            }

            // 🔁 Refrescar Select2
            tiendaSelect.trigger('change.select2');
        }).fail(() => {
            console.error('❌ Error al obtener tiendas.');
        });
    }

    // Cargar si ya hay cliente seleccionado
    if (clienteSelect.val()) {
        clienteSelect.trigger('change');
    }
});

$(document).ready(function () {
    // ✅ Inicializar Select2 para Cliente General al cargar la página
    const $selectClienteGeneral = $('#idClienteGeneral');
    if (!$selectClienteGeneral.hasClass('select2-hidden-accessible')) {
        $selectClienteGeneral.select2({
            placeholder: 'Seleccionar Cliente General',
            width: '100%',
        });
    }
});
$('#idCliente').on('change', function () {
    const clienteId = $(this).val();
    const select = document.getElementById('idClienteGeneral');
    const $select2 = $(select);

    if (!clienteId) {
        console.log('Cliente no seleccionado, limpiando cliente general y marcas');
        select.innerHTML = '<option value="" selected>Seleccionar Cliente General</option>';
        if ($select2.hasClass('select2-hidden-accessible')) $select2.select2('destroy');
        $select2.select2({ placeholder: 'Seleccionar Cliente General', width: '100%' });
        cargarTodasLasMarcas(); // Limpia marcas si se quita el cliente
        return;
    }

    console.log('Cliente seleccionado:', clienteId);

    fetch(`/clientes-generales/${clienteId}`)
        .then((response) => response.json())
        .then((data) => {
            console.log('Clientes generales recibidos:', data);

            select.innerHTML = '<option value="" selected>Seleccionar Cliente General</option>';
            data.forEach((clienteGeneral) => {
                const option = document.createElement('option');
                option.value = clienteGeneral.idClienteGeneral;
                option.textContent = clienteGeneral.descripcion;
                select.appendChild(option);
            });

            if ($select2.hasClass('select2-hidden-accessible')) $select2.select2('destroy');
            $select2.select2({ placeholder: 'Seleccionar Cliente General', width: '100%' });

            if (data.length === 1) {
                console.log('Solo hay un cliente general, se selecciona automáticamente');
                $select2.val(data[0].idClienteGeneral).trigger('change');
            }
        })
        .catch((error) => console.error('Error al cargar clientes generales:', error));
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
            .then((response) => response.json()) // Parsear la respuesta como JSON
            .then((data) => {
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
            .catch((error) => {
                console.error('Error al guardar el cliente:', error);
            });
    });

    // Evento de envío del formulario de cliente
    document.getElementById('clientGeneralForm').addEventListener('submit', function (event) {
        event.preventDefault(); // Evitar el envío normal del formulario

        let formData = new FormData(this); // Obtener los datos del formulario
        console.log('Datos del formulario:', Object.fromEntries(formData.entries())); // Ver los datos del formulario

        fetch('/guardar-cliente-general-smart', {
            method: 'POST',
            body: formData, // Enviar los datos del formulario
        })
            .then((response) => response.json()) // Parsear la respuesta como JSON
            .then((data) => {
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
            .catch((error) => {
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
            .then((response) => response.json()) // Parsear la respuesta como JSON
            .then((data) => {
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
            .catch((error) => {
                console.error('Error al guardar la marca:', error);
            });
    });

    // Evento de envío del marca
    document.getElementById('modeloForm').addEventListener('submit', function (event) {
        event.preventDefault(); // Evitar el envío normal del formulario

        let formData = new FormData(this); // Obtener los datos del formulario
        console.log('Datos del formulario:', Object.fromEntries(formData.entries())); // Ver los datos del formulario

        fetch('/guardar-modelo-smart', {
            method: 'POST',
            body: formData, // Enviar los datos del formulario
        })
            .then((response) => response.json()) // Parsear la respuesta como JSON
            .then((data) => {
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
            .catch((error) => {
                console.error('Error al guardar el cliente:', error);
            });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const $select = $('#idClienteGeneraloption');
    const selectedItemsContainer = document.getElementById('selected-items-list');

    // Inicializar Select2
    $select.select2({
        placeholder: 'Seleccionar Cliente General',
        width: '100%',
    });

    // Función para mostrar los seleccionados como badges
    function updateSelectedItems() {
        selectedItemsContainer.innerHTML = '';
        const selectedOptions = $select.find('option:selected');

        selectedOptions.each(function () {
            const badge = document.createElement('span');
            badge.textContent = $(this).text();
            badge.className = 'badge bg-primary me-1 mb-1';
            selectedItemsContainer.appendChild(badge);
        });
    }

    // Actualizar al cambiar selección
    $select.on('change', updateSelectedItems);

    // Actualizar al cargar
    updateSelectedItems();
});

document.addEventListener('DOMContentLoaded', function () {
    const tipoDocumento = document.getElementById('idTipoDocumento');
    const esTiendaContainer = document.getElementById('esTiendaContainer');

    tipoDocumento.addEventListener('change', function () {
        // Verificar si el texto del option seleccionado es "RUC"
        const selectedOptionText = tipoDocumento.options[tipoDocumento.selectedIndex].text;

        if (selectedOptionText === 'RUC') {
            esTiendaContainer.classList.remove('hidden'); // Muestra el switch
        } else {
            esTiendaContainer.classList.add('hidden'); // Oculta el switch
        }
    });
});

selectCliente.addEventListener('change', function () {
    const selectedOption = this.options[this.selectedIndex];
    const direccion = selectedOption.dataset.direccion || '';
    document.getElementById('direccion').value = direccion;
});
