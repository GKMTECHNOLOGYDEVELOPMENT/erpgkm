

function formatDate(dateString) {
  const date = new Date(dateString);
  const options = { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit', hour12: true };
  return date.toLocaleString('en-US', options).replace(',', '');
}






fetch(`/api/obtenerVisitas/${ticketId}`)
  .then(response => response.json())
  .then(data => {
    const visitasList = document.getElementById('visitasList');
    visitasList.innerHTML = '';
    if (data && data.length > 0) {
      data.forEach(visita => {
        const fechaInicio = formatDate(visita.fecha_inicio_hora);
        const fechaFinal = formatDate(visita.fecha_final_hora);
        const nombreTecnico = visita.nombre_tecnico || 'Nombre del Técnico'; // Nombre del técnico
        const nombre_visita = visita.nombre_visita || 'Nombre de la visita';
        const tipoServicio = visita.tipoServicio;
    
        // Hacer el console.log para ver el tipoServicio
        console.log("Tipo de servicio:", tipoServicio);

        let tipoResponsable = '';
        if (visita.idTipoUsuario === 5) {
          tipoResponsable = 'Chofer responsable'; // Para idTipoUsuario = 5
        } else if (visita.idTipoUsuario === 1) {
          tipoResponsable = 'Técnico responsable'; // Para idTipoUsuario = 1
        }

        // CARD PRINCIPAL QUE ENVUELVE TODO
        const cardContainer = document.createElement('div');
        cardContainer.className = 'rounded-lg shadow-xl p-6 w-full sm:max-w-4xl mx-auto mt-6';

        // 📌 Botón para ocultar/mostrar la card
        const toggleCardButton = document.createElement('button');
        toggleCardButton.className = 'px-4 py-2 mb-2 bg-gray-300 text-gray-800 rounded-lg shadow hover:bg-gray-400 transition font-semibold w-full flex justify-between items-center';
        toggleCardButton.innerHTML = `<span>Ocultar ${nombre_visita}</span> <i class="fa-solid fa-chevron-up"></i>`;

        // 📌 Evento para ocultar/mostrar la card
        toggleCardButton.addEventListener('click', function () {
          cardContainer.classList.toggle('hidden');
          if (cardContainer.classList.contains('hidden')) {
            toggleCardButton.innerHTML = `<span>Mostrar ${nombre_visita}</span> <i class="fa-solid fa-chevron-down"></i>`;
          } else {
            toggleCardButton.innerHTML = `<span>Ocultar ${nombre_visita}</span> <i class="fa-solid fa-chevron-up"></i>`;
          }
        });
        visitasList.appendChild(toggleCardButton);

        // Header de la Card (Nombre de la Visita + Botón Seleccionar)
        const cardHeader = document.createElement('div');
        cardHeader.className = 'flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 border-b pb-2 space-y-2 sm:space-y-0';

        const visitaTitle = document.createElement('h2');
        visitaTitle.className = 'text-lg font-bold text-primary';
        visitaTitle.innerHTML = `
        <span class="badge badge-outline-primary text-lg font-semibold px-3 py-1 rounded-lg shadow-md block text-center sm:text-left break-words w-full">
        ${nombre_visita} <br class="hidden sm:block"> ${tipoResponsable}: ${nombreTecnico}
    </span>
`;

        const selectButton = document.createElement('button');
        selectButton.className = 'btn btn-warning w-full sm:w-auto seleccionarVisitaButton';
        selectButton.setAttribute('data-id-ticket', visita.idTickets);
        selectButton.setAttribute('data-id-visita', visita.idVisita);
        selectButton.setAttribute('data-nombre-visita', nombre_visita);
        selectButton.textContent = 'Seleccionar Visita';

        const idVisita = visita.idVisita;

        // Realizar la consulta al backend para verificar si la visita ha sido seleccionada
        fetch(`/api/visita-seleccionada/${idVisita}`)
          .then(response => response.json())
          .then(data => {
            if (data.seleccionada) {
              selectButton.classList.remove('btn-warning');
              selectButton.classList.add('btn-danger');
              selectButton.textContent = 'Visita Seleccionada';
            }
          })
          .catch(error => {
            console.error('Error al verificar si la visita está seleccionada:', error);
          });

        // Agregar título y botón al header
        cardHeader.appendChild(visitaTitle);
        cardHeader.appendChild(selectButton);
        cardContainer.appendChild(cardHeader);

        // Contenedor de fila (Fecha de Programación + Técnico en Desplazamiento)
        const rowContainer = document.createElement('div');
        rowContainer.className = 'grid grid-cols-1 sm:grid-cols-2 gap-4 w-full';

        const visitaCard = document.createElement('div');
        visitaCard.className = 'rounded-lg shadow-md p-4 w-full sm:max-w-md mx-auto bg-[#e3e7fc]';
        visitaCard.innerHTML = `
          <div class="px-4 py-3 rounded-lg flex flex-col space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 text-center gap-2">
              <div class="flex flex-col items-center">
                <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">Fase</span>
                <span class="badge bg-primary text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">
                  ${visita.tipoServicio === 7 ? 'Fecha de Aprobación' : 'Fecha de Programación'}
                </span>
              </div>
              <div class="flex flex-col items-center">
                <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">Fecha</span>
                <span class="badge bg-primary text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">${fechaInicio} - ${fechaFinal}</span>
              </div>
            </div>

            <!-- Botón para ver imagen -->
            <div class="flex justify-center mt-2">
              <button class="badge bg-primary text-white px-2 py-1 sm:px-3 sm:py-1.5 rounded-full shadow-md transition-all duration-200 flex items-center gap-1 sm:gap-2 !bg-blue-600 !text-white text-xs sm:text-sm"
                  id="viewImageButton-${visita.idVisitas}" 
                  data-image-type="visita"
                  data-id="${visita.idVisitas}"
                  title="Ver imagen"
                  style="display: ${visita.tipoServicio === 7 ? 'none' : 'block'};">
                <i class="fa-solid fa-image text-sm sm:text-base"></i>
              </button>
            </div>                   
          </div>
        `;




// Función para formatear fechas
function formatDatos(dateString) {
  const date = new Date(dateString);
  if (isNaN(date.getTime())) {
    return '';
  }
  const year = date.getFullYear();
  const month = (date.getMonth() + 1).toString().padStart(2, '0');
  const day = date.getDate().toString().padStart(2, '0');
  const hours = date.getHours().toString().padStart(2, '0');
  const minutes = date.getMinutes().toString().padStart(2, '0');
  return `${year}-${month}-${day}T${hours}:${minutes}`;
}

// Botón de Detalles
const detailsButton = document.createElement('button');
detailsButton.className = 'btn btn-info w-full sm:w-auto mt-2 sm:mt-0';
detailsButton.innerHTML = `
  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M12 8v4l3 2m5-2a9 9 0 11-3.1-6.9M16 3v4h4" />
  </svg>
`;

detailsButton.addEventListener('click', function () {
  console.log('Botón "Ver Detalles" clickeado');

  // Rellenar los detalles del modal con valores dinámicos
  document.getElementById('detalleNombre').innerText = nombre_visita;

  // Actualizando los campos de fecha
  document.getElementById('detalleFechaInicioHora').value = visita.fecha_inicio_hora ? formatDatos(visita.fecha_inicio_hora) : '';
  document.getElementById('detalleFechaFinalHora').value = visita.fecha_final_hora ? formatDatos(visita.fecha_final_hora) : '';

  // Obtener la lista de técnicos y cargarlos en el select
  fetch('/api/usuarios/tecnico') // Ajusta esta ruta a tu API
    .then(response => response.json())
    .then(usuarios => {
      const select = document.getElementById('detalleUsuario');
      select.innerHTML = ''; // Limpiar las opciones anteriores

      // Crear una opción para cada usuario
      usuarios.forEach(usuario => {
        const option = document.createElement('option');
        option.value = usuario.idUsuario;
        option.textContent = `${usuario.Nombre} ${usuario.apellidoPaterno}`;
        select.appendChild(option);
      });

      // Seleccionar el técnico actual de la visita
      select.value = visita.idUsuario; // Asigna el usuario que ya está asociado a la visita
    })
    .catch(error => console.error('Error al obtener usuarios:', error));

    
// Obtener los técnicos de apoyo para la visita y ticket
fetch(`/api/ticketapoyo/${visita.idVisitas}/${visita.idTickets}`)
  .then(response => response.json())
  .then(tecnicosApoyo => {
    const listaTecnicos = document.getElementById('detalleTecnicosApoyo');
    listaTecnicos.innerHTML = ''; // Limpiar la lista antes de agregar los técnicos

    // Crear un item de lista para cada técnico de apoyo
    tecnicosApoyo.forEach(tecnico => {
      const listItem = document.createElement('li');
      listItem.classList.add('px-2', 'py-1', 'border', 'rounded-lg', 'text-gray-700', 'cursor-pointer');
      listItem.textContent = `${tecnico.Nombre} ${tecnico.apellidoPaterno}`;

      // Crear botón de eliminar
      const removeButton = document.createElement('button');
      removeButton.textContent = 'Eliminar';
      removeButton.classList.add('ml-2', 'text-red-500', 'hover:text-red-700', 'text-xs');

      // Evento para eliminar el técnico de apoyo
      removeButton.addEventListener('click', function () {
        if (confirm(`¿Estás seguro de eliminar a ${tecnico.Nombre} ${tecnico.apellidoPaterno}?`)) {
          // Llamar a la API para eliminar el técnico de apoyo usando el idTicketApoyo

             // Llamar a la API para eliminar el técnico de apoyo usando el idTicketApoyo
             const idTicketApoyo = tecnico.idTicketApoyo; // Aquí obtenemos el idTicketApoyo de cada técnico
          
             // Asegúrate de que `idTicketApoyo` esté presente
             console.log('Eliminando técnico con idTicketApoyo:', idTicketApoyo);
             
          fetch(`/api/eliminar/tecnicoapoyo/${tecnico.idTicketApoyo}`, {
            method: 'DELETE',
            headers: {
              'Content-Type': 'application/json',
            },
          })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                // Si la eliminación fue exitosa, eliminar el técnico de la lista
                listItem.remove();
                alert('Técnico de apoyo eliminado correctamente');
              } else {
                alert('Hubo un error al eliminar el técnico de apoyo.');
              }
            })
            .catch(error => {
              console.error('Error al eliminar técnico de apoyo:', error);
              alert('Ocurrió un problema al eliminar el técnico de apoyo.');
            });
        }
      });

      // Agregar el botón de eliminar al listItem
      listItem.appendChild(removeButton);

      // Agregar el técnico a la lista
      listaTecnicos.appendChild(listItem);
    });
  })
  .catch(error => console.error('Error al obtener técnicos de apoyo:', error));



     // Guardar el idVisitas en el modal o en el botón de actualizar
  const actualizarButton = document.getElementById('actualizarButton');
  actualizarButton.setAttribute('data-id', visita.idVisitas); // Guarda el ID de la visita en un atributo data

  // Mostrar el modal
  document.getElementById('modalDetallesVisita').classList.remove('hidden');
});

// Evento para cerrar el modal
document.getElementById('closeModalButton').addEventListener('click', function() {
  document.getElementById('modalDetallesVisita').classList.add('hidden');
});

document.getElementById('closeModalButtonFooter').addEventListener('click', function() {
  document.getElementById('modalDetallesVisita').classList.add('hidden');
});

document.getElementById('actualizarButton').addEventListener('click', function() {
  const actualizarButton = document.getElementById('actualizarButton');
  const idVisita = actualizarButton.getAttribute('data-id'); // Obtener el ID de la visita del atributo 'data-id'
  
  const fechaInicio = document.getElementById('detalleFechaInicioHora').value;
  const fechaFinal = document.getElementById('detalleFechaFinalHora').value;
  const idUsuario = document.getElementById('detalleUsuario').value;

  // Validar si los campos son correctos
  if (!fechaInicio || !fechaFinal || !idUsuario) {
    alert('Por favor complete todos los campos.');
    return;
  }

  // Realizar la petición PUT para actualizar la visita con el id específico
fetch(`/api/actualizar/visitas/${idVisita}`, {
  method: 'PUT',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    fecha_inicio_hora: fechaInicio,
    fecha_final_hora: fechaFinal,
    idUsuario: idUsuario,
  }),
})
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      // Si la respuesta es exitosa, cerrar el modal y actualizar la vista
      alert('Visita actualizada exitosamente!');
      location.reload();
      document.getElementById('modalDetallesVisita').classList.add('hidden');
      // Aquí puedes actualizar la tarjeta de la visita en la interfaz si es necesario
    } else {
      // Mostrar el mensaje de error recibido desde el servidor
      alert(data.message || 'Hubo un error al actualizar la visita.');
    }
  })
  .catch(error => {
    // En caso de que haya un error en la petición, mostrarlo
    console.error('Error:', error);
    alert('Ocurrió un problema al realizar la actualización.');
  });

});

// Agregar el botón Detalles debajo de la información de la visita
visitaCard.appendChild(detailsButton);







        const tecnicoCard = document.createElement('div');
        tecnicoCard.className = 'rounded-lg shadow-md p-4 w-full sm:max-w-md mx-auto bg-[#deeffd]';
        tecnicoCard.innerHTML = `
          <div class="px-4 py-3 rounded-lg flex flex-col space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-3 text-center gap-2">
              <div class="flex flex-col items-center">
                <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">Fase</span>
                <span class="badge bg-info text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">
                  ${tipoServicio === 7 ? 'En Llegada' : 'En Desplazamiento'}
                </span>
              </div>
              <div class="flex flex-col items-center">
                <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">Ubicación</span>
                <span id="ubicacion-${visita.idVisitas}" class="badge bg-info text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">
                  ${visita.tipoServicio === 7 ? 'GKM TECHNOLOGY' :
                    (visita.anexos_visitas.length > 0 && visita.anexos_visitas.find(anexo => anexo.idTipovisita === 2) 
                    ? visita.anexos_visitas.find(anexo => anexo.idTipovisita === 2).ubicacion 
                    : 'Ubicación no disponible')}
                </span>
              </div>
              <div class="flex flex-col items-center">
                <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">
                  ${visita.tipoServicio === 7 ? 'Solicitud' : 'Fecha'}
                </span>
                ${visita.tipoServicio === 7 ? 
                  `<span class="badge bg-info text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">Aprobada</span>` : 
                  `<span id="fechaDesplazamiento-${visita.idVisitas}" class="badge bg-info text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">
                    ${visita.fechas_desplazamiento ? formatDate(visita.fechas_desplazamiento) : 'Sin fecha de desplazamiento'}
                  </span>`}
              </div>
            </div>
        
            <div class="flex justify-center mt-2">
              <button class="badge bg-info text-white px-2 py-1 sm:px-3 sm:py-1.5 rounded-full shadow-md transition-all duration-200 flex items-center gap-1 sm:gap-2 !bg-blue-600 !text-white text-xs sm:text-sm"
                      id="likeButton-${visita.idVisitas}" style="display: ${visita.tipoServicio === 7 ? 'none' : 'block'};">
                <i class="fa-solid fa-route text-sm sm:text-base"></i>
                <span class="text-xs sm:text-sm">Iniciar Desplazamiento</span>
              </button>
            </div>                   
          </div>
        `;

        rowContainer.appendChild(visitaCard);
        rowContainer.appendChild(tecnicoCard);

        cardContainer.appendChild(rowContainer);
        visitasList.appendChild(cardContainer);

        document.querySelectorAll('.seleccionarVisitaButton').forEach(button => {
          button.addEventListener('click', function () {
            const idTicket = this.getAttribute('data-id-ticket');
            const idVisita = this.getAttribute('data-id-visita');
            const nombreVisita = this.getAttribute('data-nombre-visita');

            fetch('/api/seleccionar-visita', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
              },
              body: JSON.stringify({
                idTickets: idTicket,
                idVisitas: idVisita,
                vistaseleccionada: nombreVisita
              })
            })
              .then(response => response.json())
              .then(data => {
                if (data.success) {
                  location.reload();
                } else {
                  toastr.error(data.message);
                }
              })
              .catch(error => {
                console.error('Error al seleccionar la visita:', error);
                toastr.error('Hubo un error al seleccionar la visita.');
              });
          });
        });







        // Verificar si ya existe un registro de "Inicio de Servicio"
        fetch(`/api/verificarRegistroAnexo/${visita.idVisitas}`)
          .then(response => response.json())
          .then(anexoData => {
            if (anexoData && anexoData.idVisitas) {
              // Si existe el registro, mostrar la tarjeta de "Inicio de Servicio"
              const inicioServicioCard = document.createElement('div');
              inicioServicioCard.className = 'rounded-lg shadow-md p-4 w-full sm:max-w-md mx-auto bg-[#d9f2e6]';
              inicioServicioCard.innerHTML = `
                <div class="px-4 py-3 rounded-lg flex flex-col space-y-4">
                  <!-- Encabezados y Contenido Responsivo -->
                  <div class="grid grid-cols-1 sm:grid-cols-3 text-center gap-2">
                    <div class="flex flex-col items-center">
                      <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">Fase</span>
                      <span class="badge bg-success text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">Llegada al Servicio</span>
                    </div>
                    <div class="flex flex-col items-center">
                      <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">Ubicación</span>
                   <span id="ubicacion-${visita.idVisitas}" class="badge bg-success text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">
          <!-- Aquí verificamos si existen anexos, y si es así, mostramos la primera ubicación -->

${visita.anexos_visitas.length > 0 && visita.anexos_visitas.find(anexo => anexo.idTipovisita === 3) ? visita.anexos_visitas.find(anexo => anexo.idTipovisita === 3).ubicacion : 'Ubicación no disponible'}
        </span>
                    </div>
                    <div class="flex flex-col items-center">
                      <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">Fecha</span>
                      <span class="badge bg-success text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">
                        ${visita.fecha_llegada ? formatDate(visita.fecha_llegada) : 'Sin fecha'}
                      </span>
                    </div>
                  </div>
        
                  <!-- Botones de acción -->
                  <div class="flex flex-col sm:flex-row justify-center gap-2 sm:gap-3 mt-4">
                    <button class="bg-success hover:bg-green-700 text-white px-2 py-1 sm:px-3 sm:py-1.5 rounded-full shadow-md
                                   transition-all duration-200 flex items-center gap-1 sm:gap-2 !bg-success !text-white text-xs sm:text-sm"
                            id="uploadPhotoButton-${visita.idVisitas}">
                      <i class="fa-solid fa-camera text-sm sm:text-base"></i> 
                      <span class="text-xs sm:text-sm">Subir Foto</span>
                    </button>
                  
                    <button class="bg-success text-white px-2 py-1 sm:px-3 sm:py-1.5 rounded-full shadow-md
                                   transition-all duration-200 flex items-center gap-1 sm:gap-2 !bg-red-600 !text-white text-xs sm:text-sm"
                            id="siguiente-${visita.idVisitas}">
                      <i class="fa-solid fa-arrow-right text-sm sm:text-base"></i> 
                      <span class="text-xs sm:text-sm">Siguiente</span>
                    </button>
                    
                    <!-- Botón para ver imagen -->
                    <button 
                    class="flex items-center justify-center gap-2 
                           px-2 py-1 sm:px-3 sm:py-1.5
                           text-xs sm:text-sm md:text-base
                           font-semibold rounded-full shadow-md 
                           transition-all duration-200
                           bg-success hover:bg-blue-700 focus:ring focus:ring-blue-300 
                           text-white"
                    id="viewImageButton-${visita.idVisitas}" 
                    data-image-type="inicioServicio"
                    data-id="${visita.idVisitas}"
                    title="Ver imagen">
                    <i class="fa-solid fa-image text-base md:text-lg"></i> 
                </button>
                
                  </div>
                
                  <!-- Input oculto para subir foto -->
                  <input type="file" id="fileInput-${visita.idVisitas}" class="hidden" accept="image/*">
                </div>
              `;



              // visitasList.appendChild(inicioServicioCard);
              tecnicoCard.insertAdjacentElement('afterend', inicioServicioCard);

              // Verificar si ya existe una fecha de llegada
              fetch(`/api/verificarFechaExistente/${visita.idVisitas}`)
                .then(response => response.json())
                .then(data => {
                  if (data.existe) {
                    // Si ya existe una fecha de llegada, deshabilitar el botón "Siguiente"
                    const siguienteButton = document.getElementById(`siguiente-${visita.idVisitas}`);
                    if (siguienteButton) {
                      siguienteButton.disabled = true; // Deshabilitar el botón
                      siguienteButton.classList.add('disabled'); // Opcional: agregar una clase CSS para estilos
                      // toastr.error('Ya existe una fecha de llegada para esta visita.');
                    }

                    // Mostrar la tarjeta de "Final de Servicio" automáticamente
                    const finalServicioCard = document.createElement('div');
                    finalServicioCard.className = 'rounded-lg shadow-md p-4 w-full sm:max-w-md mx-auto bg-[#fbe5e6]';
                    finalServicioCard.innerHTML = `
                      <div class="px-4 py-3 rounded-lg flex flex-col space-y-4">
                        <!-- Encabezados y Contenido Responsivo -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 text-center gap-2">
                          <div class="flex flex-col items-center">
                            <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">Fase</span>
                            <span class="badge bg-danger text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">Inicio de Servicio</span>
                          </div>
                          <div class="flex flex-col items-center">
                            <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">Ubicación</span>
                           <span id="ubicacion-${visita.idVisitas}" class="badge bg-danger text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">
          <!-- Aquí verificamos si existen anexos, y si es así, mostramos la primera ubicación -->

${visita.anexos_visitas.length > 0 && visita.anexos_visitas.find(anexo => anexo.idTipovisita === 4) ? visita.anexos_visitas.find(anexo => anexo.idTipovisita === 4).ubicacion : 'Ubicación no disponible'}
        </span>
                          </div>
                          <div class="flex flex-col items-center">
                            <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">Fecha</span>
                            <span class="badge bg-danger text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">
                              ${visita.fecha_inicio ? formatDate(visita.fecha_inicio) : 'Sin fecha'}
                            </span>
                          </div>
                        </div>
                    


                        
                        <!-- Verificar si el cliente es titular -->
                        ${visita.titular === 1 ?
                        `<label class="badge bg-danger text-white text-xs sm:text-sm md:text-base rounded-full shadow-md py-1 px-3 mx-auto">
                               El cliente es titular
                            </label>`
                        :
                        `${visita.titular === 0 ?
                          `<div class="flex flex-col sm:flex-row items-center sm:justify-start gap-2 w-full">
                              <label class="badge bg-danger text-white text-xs sm:text-sm md:text-base rounded-full shadow-md py-1 px-3">
                              CLIENTE NO ES TITULAR
                          </label>
                              <label class="badge bg-danger text-white text-xs sm:text-sm md:text-base rounded-full shadow-md py-1 px-3">
                                  Nombre: ${visita.nombre}
                              </label>
                              <label class="badge bg-danger text-white text-xs sm:text-sm md:text-base rounded-full shadow-md py-1 px-3">
                                  Dni: ${visita.dni}
                              </label>
                              <label class="badge bg-danger text-white text-xs sm:text-sm md:text-base rounded-full shadow-md py-1 px-3">
                                  Teléfono: ${visita.telefono}
                              </label>
                          </div>`
                          :
                          `${visita.servicio === 1 ?
                            `<div class="flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-4 w-full">
                            <label class="badge bg-danger text-white text-xs sm:text-sm md:text-base rounded-full shadow-md py-1 px-3">
                                Servicio No Finalizado
                            </label>
                    
                            <!-- Motivo en otro badge -->
                            <label class="badge bg-danger text-white text-xs sm:text-sm md:text-base rounded-full shadow-md py-1 px-3">
                                Motivo: ${visita.motivo}
                            </label>
                    
                            <!-- Botón Ver Imagen -->
                            <button class="flex items-center justify-center gap-2
                                    px-2 py-1 sm:px-3 sm:py-1.5
                                    text-xs sm:text-sm md:text-base
                                    font-semibold rounded-full shadow-md 
                                    transition-all duration-200
                                    bg-danger hover:bg-red-700 focus:ring focus:ring-red-300 
                                    text-white"
                                id="viewImageButton-${visita.idVisitas}" 
                                data-image-type="finalServicio"
                                data-id="${visita.idVisitas}"
                                title="Ver imagen">
                                <i class="fa-solid fa-image text-lg sm:text-xl"></i> 
                            </button>
                        </div>
                        `
                            :
                            // Si el servicio no está finalizado, mostrar el botón "Continuar"
                            `
                        <button class="flex items-center justify-center gap-2 
                                px-2 py-1 sm:px-3 sm:py-2 md:px-4 md:py-2.5
                                text-xs sm:text-sm md:text-base
                                font-semibold rounded-full shadow-md 
                                transition-all duration-200
                                badge bg-danger focus:ring focus:ring-blue-300 
                                text-white"
                            id="continueButton-${visita.idVisitas}"
                            data-visita-id="${visita.idVisitas}"
                            @click="
                                console.log('Button clicked');
                                visitaId = $event.currentTarget.getAttribute('data-visita-id'); 
                                console.log('Visita ID:', visitaId);
                                $dispatch('set-visita-id', visitaId);  
                                openCondiciones = true;
                            ">
                            <i class="fa-solid fa-check-circle text-base sm:text-lg"></i>
                            <span class="text-xs sm:text-sm">Continuar Aquí</span>
                        </button>
                        `
                          }`}`
                      }
                        
                        <!-- Verificar si el servicio está finalizado -->
                        ${visita.servicio === 1 ?
                        `

                            `
                        :
                        ''
                      }

                        
                      </div>
                    `;



                    // Insertar la tarjeta de "Final de Servicio" debajo de la tarjeta de "Inicio de Servicio"
                    inicioServicioCard.insertAdjacentElement('afterend', finalServicioCard);

                    // Agregar el evento de clic al botón "Continuar"
                    const continueButton = document.getElementById(`continueButton-${visita.idVisitas}`);
                    if (continueButton) {
                      continueButton.addEventListener('click', () => {
                        // Mostrar el modal al hacer clic en "Continuar"
                        const event = new CustomEvent('toggle-modal-condiciones');
                        window.dispatchEvent(event);
                      });
                    }
                  }
                })
                .catch(error => {
                  console.error('Error:', error);
                  toastr.error('Hubo un error al verificar la fecha de llegada.');
                });







              // Agregar el evento de clic al botón "Siguiente"
              const siguienteButton = document.getElementById(`siguiente-${visita.idVisitas}`);
              siguienteButton.addEventListener('click', () => {
                // Crear la card de "Final de Servicio"
                const finalServicioCard = document.createElement('div');
                finalServicioCard.className = 'rounded-lg shadow-md p-4 w-full sm:max-w-md mx-auto bg-[#fbe5e6]';
                finalServicioCard.innerHTML = `
                  <div class="px-4 py-3 rounded-lg flex flex-col space-y-4">
                    <!-- Encabezados y Contenido Responsivo -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 text-center gap-2">
                      <div class="flex flex-col items-center">
                        <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">Fase</span>
                        <span class="badge bg-danger text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">Final de Servicio</span>
                      </div>
                      <div class="flex flex-col items-center">
                        <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">Ubicación</span>
                        <span class="badge bg-danger text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">
                          ${visita.ubicacion || 'Ubicación no disponible'}
                        </span>
                      </div>
                      <div class="flex flex-col items-center">
                        <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">Fecha</span>
                        <span class="badge bg-danger text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">
                          ${visita.fecha_final ? formatDate(visita.fecha_final) : 'Sin fecha'}
                        </span>
                      </div>
                    </div>
                
                <!-- Botones de acción -->
                        <div class="flex flex-col sm:flex-row justify-center gap-2 sm:gap-3 mt-4 w-full">
                        <!-- Botón Continuar -->
                        <button class="sm:w-auto badge bg-danger text-white px-3 py-2 sm:px-4 sm:py-2 md:px-5 md:py-2.5 rounded-full shadow-md transition-all duration-200 flex items-center justify-center gap-2 sm:gap-3 !bg-blue-600 !text-white text-sm sm:text-base md:text-lg"
                            id="continueButton-${visita.idVisitas}"
                            data-visita-id="${visita.idVisitas}"
                            @click="
                                console.log('Button clicked');
                                visitaId = $event.currentTarget.getAttribute('data-visita-id'); 
                                console.log('Visita ID:', visitaId);
                                $dispatch('set-visita-id', visitaId);  
                                openCondiciones = true;
                            ">
                            <i class="fa-solid fa-check-circle text-base sm:text-lg"></i>
                            <span class="text-xs sm:text-sm">Continuar Aqui si estoy </span>
                        </button>
                    




                          
                          <!-- Botón para ver imagen -->
                          <button class="flex items-center justify-center gap-2 
                          px-2 py-1 sm:px-3 sm:py-1.5
                          text-xs sm:text-sm md:text-base
                          font-semibold rounded-full shadow-md 
                          transition-all duration-200
                          bg-danger hover:bg-blue-700 focus:ring focus:ring-blue-300 
                          text-white"
                          id="viewImageButton-${visita.idVisitas}" 
                          data-image-type="finalServicio"
                          data-id="${visita.idVisitas}"
                          title="Ver imagen">
                          <i class="fa-solid fa-image text-lg sm:text-xl"></i> 
                      </button>

                        
                      </div>
                `;



                // Insertar la card de "Final de Servicio" debajo de la card de "Inicio de Servicio"
                inicioServicioCard.insertAdjacentElement('afterend', finalServicioCard);





                // Agregar el evento de clic al botón "Finalizar"
                const finalizarServicioButton = document.getElementById(`continueButton-${visita.idVisitas}`);

                // Asegúrate de que solo se agregue un solo evento
                if (finalizarServicioButton) {
                  finalizarServicioButton.addEventListener('click', () => {
                    // Mostrar el modal al hacer clic en "Finalizar"
                    const event = new CustomEvent('toggle-modal-condiciones');
                    window.dispatchEvent(event);
                  });
                }



                // Obtener la fecha actual
                const fechaLlegada = new Date().toISOString(); // Formato YYYY-MM-DDTHH:mm:ss.sssZ

                // Verificar si ya existe una fecha de llegada antes de actualizar
                fetch(`/api/verificarFechaExistente/${visita.idVisitas}`)
                  .then(response => response.json())
                  .then(data => {
                    if (data.existe) {
                      // Si ya existe una fecha de llegada, mostrar un error y deshabilitar el botón "Siguiente"
                      toastr.error('Ya existe una fecha de llegada para esta visita.');
                      const siguienteButton = document.getElementById(`siguiente-${visita.idVisitas}`);
                      if (siguienteButton) {
                        siguienteButton.disabled = true; // Deshabilitar el botón
                        siguienteButton.classList.add('disabled'); // Opcional: agregar una clase CSS para estilos
                      }
                    } else {
                      // Si no existe, proceder con la actualización
                      fetch(`/api/actualizarFechaLlegada/${visita.idVisitas}`, {
                        method: 'POST',
                        headers: {
                          'Content-Type': 'application/json',
                          'X-CSRF-TOKEN': '{{ csrf_token() }}' // Si estás usando CSRF en Laravel
                        },
                        body: JSON.stringify({
                          fecha_llegada: fechaLlegada
                        })
                      })
                        .then(response => response.json())
                        .then(data => {
                          if (data.success) {
                            toastr.success('Fecha de llegada actualizada correctamente.');

                            // Deshabilitar el botón "Siguiente" después de actualizar la fecha
                            const siguienteButton = document.getElementById(`siguiente-${visita.idVisitas}`);
                            if (siguienteButton) {
                              siguienteButton.disabled = true; // Deshabilitar el botón
                              siguienteButton.classList.add('disabled'); // Opcional: agregar una clase CSS para estilos
                            }

                            // Actualizar la tabla en el frontend
                            actualizarFechaTabla(visita.idVisitas, fechaLlegada);
                          } else {
                            toastr.error('Hubo un error al actualizar la fecha de llegada.');
                          }
                        })
                        .catch(error => {
                          console.error('Error:', error);
                          toastr.error('Hubo un error al actualizar la fecha de llegada.');
                        });
                    }
                  })
                  .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Hubo un error al verificar la fecha de llegada.');
                  });





              });



              // Función para actualizar la tabla en el frontend
              function actualizarFechaTabla(idVisitas, nuevaFecha) {
                // Buscar la fila en la tabla correspondiente a la visita
                const filaVisita = document.querySelector(`#fila-visita-${idVisitas}`);
                if (filaVisita) {
                  // Actualizar la celda de la fecha de llegada con la nueva fecha
                  const fechaLlegadaCell = filaVisita.querySelector('.fecha-llegada');
                  if (fechaLlegadaCell) {
                    fechaLlegadaCell.textContent = new Date(nuevaFecha).toLocaleString();
                  }
                }
              }






              // Verificar si ya existe una foto para la visita
              fetch(`/api/verificarFoto/${visita.idVisitas}`)
                .then(response => response.json())
                .then(data => {
                  if (data.success) {
                    const uploadPhotoButton = document.getElementById(`uploadPhotoButton-${visita.idVisitas}`);
                    const siguienteButton = document.getElementById(`siguiente-${visita.idVisitas}`); // Cambiar a "Siguiente"
                    uploadPhotoButton.style.display = 'none';
                    siguienteButton.style.display = 'block'; // Habilitar el botón "Siguiente"
                    // Agregar el evento de clic al botón "Continuar"
                    continueButton.addEventListener('click', () => {
                      // Mostrar el modal
                      const event = new CustomEvent('toggle-modal-condiciones');
                      window.dispatchEvent(event);
                    });
                  }
                })
                .catch(error => {
                  console.error('Error al verificar la foto de la visita:', error);
                });

              // Agregar el evento de clic al botón de "Subir Foto"
              const uploadPhotoButton = document.getElementById(`uploadPhotoButton-${visita.idVisitas}`);
              const fileInput = document.getElementById(`fileInput-${visita.idVisitas}`);

              uploadPhotoButton.addEventListener('click', () => {
                fileInput.click(); // Simula el clic en el input de archivo
              });

              // Manejar la selección de archivo
              fileInput.addEventListener('change', () => {
                const file = fileInput.files[0];
                if (file) {
                  const formData = new FormData();
                  formData.append('photo', file);
                  formData.append('visitaId', visita.idVisitas);

                  // Obtener la ubicación (latitud y longitud)
                  if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                      (position) => {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        // Hacer la solicitud a Nominatim para obtener la ubicación
                        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                          .then(response => response.json())
                          .then(data => {
                            const location = data.display_name; // La dirección obtenida

                            // Agregar la latitud, longitud y la ubicación al FormData
                            formData.append('lat', lat);
                            formData.append('lng', lng);
                            formData.append('ubicacion', location); // Ubicación (dirección)

                            // Hacer la solicitud para subir la foto con la ubicación
                            fetch('/api/subirFoto', {
                              method: 'POST',
                              body: formData,
                            })
                              .then(response => response.json())
                              .then(data => {
                                if (data.success) {
                                  toastr.success("Foto subida con éxito.");
                                  const uploadPhotoButton = document.getElementById(`uploadPhotoButton-${visita.idVisitas}`);
                                  const siguienteButton = document.getElementById(`siguiente-${visita.idVisitas}`); // Cambiar a "Siguiente"
                                  uploadPhotoButton.style.display = 'none';
                                  siguienteButton.style.display = 'block'; // Habilitar el botón "Siguiente"
                                  // Agregar el evento de clic al botón "Continuar" después de subir la foto
                                  continueButton.addEventListener('click', () => {
                                    // Mostrar el modal
                                    const event = new CustomEvent('toggle-modal-condiciones');
                                    window.dispatchEvent(event);
                                  });
                                } else {
                                  // toastr.error("Hubo un error al subir la foto.");
                                }
                              })
                              .catch(error => {
                                console.error('Error al subir la foto:', error);
                                // toastr.error("Hubo un error al subir la foto.");
                              });
                          })
                          .catch(error => {
                            console.error('Error al obtener la ubicación:', error);
                            // toastr.error("Hubo un error al obtener la ubicación.");
                          });
                      },
                      (error) => {
                        console.error('Error al obtener la ubicación:', error);
                        toastr.error("No se pudo obtener la ubicación.");
                      }
                    );
                  } else {
                    toastr.error("La geolocalización no está disponible en tu navegador.");
                  }
                } else {
                  toastr.error("Por favor selecciona una foto.");
                }
              });

            }
          })
          .catch(error => {
            console.error('Error al verificar el registro de anexo:', error);
          });




























        // Agregar el evento de clic al botón de like
        const likeButton = document.getElementById(`likeButton-${visita.idVisitas}`);
        likeButton.addEventListener('click', function () {
          // Verificar si ya hay un registro en anexos_visitas para esa visita
          fetch(`/api/verificarRegistroAnexo/${visita.idVisitas}`)
            .then(response => response.json())
            .then(data => {
              if (data && data.idVisitas) {
                toastr.error("El técnico ya se encuentra en desplazamiento para esta visita.");
                return;
              }

              // Si no existe un registro, proceder con la actualización
              const nuevaFechaDesplazamiento = new Date().toISOString().slice(0, 19).replace("T", " ");

              if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                  const lat = position.coords.latitude;
                  const lng = position.coords.longitude;

                  const geocodeUrl = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`;
                  fetch(geocodeUrl)
                    .then(response => response.json())
                    .then(data => {
                      const ubicacion = data.address ? `${data.address.road || ''}, ${data.address.city || ''}, ${data.address.country || ''}` : "Ubicación desconocida";

                      fetch(`/api/actualizarVisita/${visita.idVisitas}`, {
                        method: 'PATCH',
                        headers: {
                          'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                          fechas_desplazamiento: nuevaFechaDesplazamiento,
                          // ubicacion: nuevaUbicacion,  // El nuevo valor de la ubicación

                        }),
                      })
                        .then(response => response.json())
                        .then(updatedVisita => {
                          if (updatedVisita) {
                            const fechaDesplazamientoElement = document.getElementById(`fechaDesplazamiento-${visita.idVisitas}`);



                            if (fechaDesplazamientoElement) {
                              fechaDesplazamientoElement.textContent = formatDate(updatedVisita.fechas_desplazamiento);
                            } else {
                              console.warn(`No se encontró el elemento con el id fechaDesplazamiento-${visita.idVisitas}`);
                            }




                            fetch('/api/guardarAnexoVisita', {
                              method: 'POST',
                              headers: {
                                'Content-Type': 'application/json',
                              },
                              body: JSON.stringify({
                                idVisitas: visita.idVisitas,
                                idTipovisita: 2,
                                lat: lat,
                                lng: lng,
                                ubicacion: ubicacion,
                              }),
                            })
                              .then(response => response.json())
                              .then(data => {
                                if (data.error) {
                                  toastr.error(data.error);
                                  return;
                                }

                                if (data.success) {
                                  toastr.success("Tecnico en desplazamiento.");

                                  // Si existe el registro, mostrar la tarjeta de "Inicio de Servicio"
                                  const inicioServicioCard = document.createElement('div');
                                  inicioServicioCard.className = 'rounded-lg shadow-md p-4 w-full sm:max-w-md mx-auto bg-[#d9f2e6]';
                                  inicioServicioCard.innerHTML = `
                <div class="px-4 py-3 rounded-lg flex flex-col space-y-4">
                  <!-- Encabezados y Contenido Responsivo -->
                  <div class="grid grid-cols-1 sm:grid-cols-3 text-center gap-2">
                    <div class="flex flex-col items-center">
                      <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">Fase</span>
                      <span class="badge bg-success text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">Llegada al Servicio</span>
                    </div>
                    <div class="flex flex-col items-center">
                      <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">Ubicación</span>
                      <span id="ubicacion-${visita.idVisitas}" class="badge bg-success text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">
          <!-- Aquí verificamos si existen anexos, y si es así, mostramos la primera ubicación -->

${visita.anexos_visitas.length > 0 && visita.anexos_visitas.find(anexo => anexo.idTipovisita === 3) ? visita.anexos_visitas.find(anexo => anexo.idTipovisita === 3).ubicacion : 'Ubicación no disponible'}
        </span>
                    </div>
                    <div class="flex flex-col items-center">
                      <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">Fecha</span>
                      <span class="badge bg-success text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">
                        ${visita.fecha_llegada ? formatDate(visita.fecha_llegada) : 'Sin fecha'}
                      </span>
                    </div>
                  </div>
        
                  <!-- Botones de acción -->
                  <div class="flex flex-col sm:flex-row justify-center gap-2 sm:gap-3 mt-4">
                    <button class="bg-success hover:bg-green-700 text-white px-2 py-1 sm:px-3 sm:py-1.5 rounded-full shadow-md
                                   transition-all duration-200 flex items-center gap-1 sm:gap-2 !bg-success !text-white text-xs sm:text-sm"
                            id="uploadPhotoButton-${visita.idVisitas}">
                      <i class="fa-solid fa-camera text-sm sm:text-base"></i> 
                      <span class="text-xs sm:text-sm">Subir Foto</span>
                    </button>
                  
                    <button class="bg-success text-white px-2 py-1 sm:px-3 sm:py-1.5 rounded-full shadow-md
                                   transition-all duration-200 flex items-center gap-1 sm:gap-2 !bg-red-600 !text-white text-xs sm:text-sm"
                            id="siguiente-${visita.idVisitas}">
                      <i class="fa-solid fa-arrow-right text-sm sm:text-base"></i> 
                      <span class="text-xs sm:text-sm">Siguiente</span>
                    </button>
                    
                    <!-- Botón para ver imagen -->
                    <button class="bg-success text-white px-2 py-1 sm:px-3 sm:py-1.5 rounded-full shadow-md
                                   transition-all duration-200 flex items-center gap-1 sm:gap-2 !bg-blue-600 !text-white text-xs sm:text-sm"
                            id="viewImageButton-${visita.idVisitas}" title="Ver imagen">
                      <i class="fa-solid fa-image text-sm sm:text-base"></i> 
                    </button>
                  </div>
                
                  <!-- Input oculto para subir foto -->
                  <input type="file" id="fileInput-${visita.idVisitas}" class="hidden" accept="image/*">
                </div>
              `;

                                  tecnicoCard.insertAdjacentElement('afterend', inicioServicioCard);

                                  // visitasList.appendChild(inicioServicioCard);






                                  // Agregar el evento de clic al botón "Siguiente"
                                  const siguienteButton = document.getElementById(`siguiente-${visita.idVisitas}`);
                                  siguienteButton.addEventListener('click', () => {
                                    // Crear la card de "Final de Servicio"
                                    const finalServicioCard = document.createElement('div');
                                    finalServicioCard.className = 'rounded-lg shadow-md p-4 w-full sm:max-w-md mx-auto bg-[#fbe5e6]';
                                    finalServicioCard.innerHTML = `
    <div class="px-4 py-3 rounded-lg flex flex-col space-y-4">
        <!-- Encabezados y Contenido Responsivo -->
        <div class="grid grid-cols-1 sm:grid-cols-3 text-center gap-2">
            <div class="flex flex-col items-center">
                <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">Fase</span>
                <span class="badge bg-danger text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">Inicio de Servicio</span>
            </div>
            <div class="flex flex-col items-center">
                <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">Ubicación</span>
                <span id="ubicacion-${visita.idVisitas}" class="badge bg-danger text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">
                    <!-- Aquí verificamos si existen anexos, y si es así, mostramos la primera ubicación -->
                    ${visita.anexos_visitas.length > 0 && visita.anexos_visitas.find(anexo => anexo.idTipovisita === 4) ? visita.anexos_visitas.find(anexo => anexo.idTipovisita === 4).ubicacion : 'Ubicación no disponible'}
                </span>
            </div>
            <div class="flex flex-col items-center">
                <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">Fecha</span>
                <span class="badge bg-danger text-white text-xs px-3 py-1 rounded-lg shadow-md w-full">
                    ${visita.fecha_inicio ? formatDate(visita.fecha_inicio) : 'Sin fecha'}
                </span>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-4 mt-4 w-full">
    <!-- Servicio Finalizado como badge -->
    <label class="badge bg-danger text-white text-xs sm:text-sm md:text-base rounded-full shadow-md py-1 px-3">
        Servicio Finalizado
    </label>

    <!-- Botón Continuar -->
    <button class="flex items-center justify-center gap-2
            px-2 py-1 sm:px-3 sm:py-2 md:px-4 md:py-2.5
            text-xs sm:text-sm md:text-base
            font-semibold rounded-full shadow-md 
            transition-all duration-200
            badge bg-danger focus:ring focus:ring-blue-300 
            text-white"
        id="continueButton-${visita.idVisitas}"
        data-visita-id="${visita.idVisitas}"
        @click="
            console.log('Button clicked');
            visitaId = $event.currentTarget.getAttribute('data-visita-id'); 
            console.log('Visita ID:', visitaId);
            $dispatch('set-visita-id', visitaId);  
            openCondiciones = true;
        ">
        <i class="fa-solid fa-check-circle text-base sm:text-lg"></i>
        <span class="text-xs sm:text-sm">Continuar</span>
    </button>
</div>

    </div>
    `;



                                    // Insertar la card de "Final de Servicio" debajo de la card de "Inicio de Servicio"
                                    inicioServicioCard.insertAdjacentElement('afterend', finalServicioCard);

                                    // Agregar el evento de clic al botón "Finalizar"
                                    const finalizarServicioButton = document.getElementById(`continueButton-${visita.idVisitas}`);
                                    finalizarServicioButton.addEventListener('click', () => {
                                      // Mostrar el modal
                                      const event = new CustomEvent('toggle-modal-condiciones');
                                      window.dispatchEvent(event);

                                      // Aquí ya no hay llamada al fetch, solo se oculta la card de "Final de Servicio"
                                      finalServicioCard.style.display = 'block'; // Ocultar la card de "Final de Servicio"
                                    });

                                    // Aquí agregamos la actualización de la fecha de llegada cuando se hace clic en "Siguiente"
                                    const fechaLlegada = new Date().toISOString().slice(0, 19).replace("T", " "); // Obtener la fecha y hora actual
                                    fetch(`/api/actualizarFechaLlegada/${visita.idVisitas}`, {
                                      method: 'POST',
                                      headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Si estás usando CSRF en Laravel
                                      },
                                      body: JSON.stringify({
                                        fecha_llegada: fechaLlegada
                                      })
                                    })
                                      .then(response => response.json())
                                      .then(data => {
                                        if (data.success) {
                                          toastr.success('Fecha de llegada actualizada correctamente.');

                                          // Deshabilitar el botón "Siguiente" después de actualizar la fecha
                                          const siguienteButton = document.getElementById(`siguiente-${visita.idVisitas}`);
                                          if (siguienteButton) {
                                            siguienteButton.disabled = true; // Deshabilitar el botón
                                            siguienteButton.classList.add('disabled'); // Opcional: agregar una clase CSS para estilos
                                          }
                                        } else {
                                          toastr.error('Hubo un error al actualizar la fecha de llegada.');
                                        }
                                      })
                                      .catch(error => {
                                        console.error('Error al actualizar la fecha de llegada:', error);
                                        toastr.error('Hubo un error al actualizar la fecha de llegada.');
                                      });

                                  });


                                  document.querySelectorAll('.seleccionarVisitaButton').forEach(button => {
                                    button.addEventListener('click', function () {
                                      const idTicket = this.getAttribute('data-id-ticket'); // ID del ticket
                                      const idVisita = this.getAttribute('data-id-visita'); // ID de la visita
                                      const nombreVisita = this.getAttribute('data-nombre-visita'); // Nombre de la visita

                                      // Llamada al backend para guardar la visita seleccionada
                                      fetch('/api/seleccionar-visita', {
                                        method: 'POST',
                                        headers: {
                                          'Content-Type': 'application/json',
                                          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // CSRF Token si estás usando Blade en Laravel
                                        },
                                        body: JSON.stringify({
                                          idTickets: idTicket,
                                          idVisitas: idVisita,
                                          vistaseleccionada: nombreVisita
                                        })
                                      })
                                        .then(response => response.json())
                                        .then(data => {
                                          if (data.success) {

                                            // toastr.success("Actualizada correctamente");

                                            toastr.success(data.message, 'Esta es la ultima');  // Muestra el mensaje de éxito
                                          } else {
                                            toastr.error(data.message);  // Muestra el mensaje de error
                                          }
                                        })
                                        .catch(error => {
                                          console.error('Error al seleccionar la visita:', error);
                                          toastr.error('Hubo un error al seleccionar la visita.');
                                        });
                                    });
                                  });












                                  fetch(`/api/verificarFoto/${visita.idVisitas}`)
                                    .then(response => response.json())
                                    .then(data => {
                                      if (data.success) {
                                        const uploadPhotoButton = document.getElementById(`uploadPhotoButton-${visita.idVisitas}`);
                                        const siguienteButton = document.getElementById(`siguiente-${visita.idVisitas}`); // Cambiar a "Siguiente"
                                        uploadPhotoButton.style.display = 'none';
                                        siguienteButton.style.display = 'block'; // Habilitar el botón "Siguiente"
                                        // Agregar el evento de clic al botón "Continuar"
                                        continueButton.addEventListener('click', () => {
                                          // Mostrar el modal
                                          const modal = Alpine.$data(document.querySelector('[x-data="modal"]'));
                                          modal.toggle();
                                        });
                                      }
                                    })
                                    .catch(error => {
                                      console.error('Error al verificar la foto de la visita:', error);
                                    });

                                  // Agregar el evento de clic al botón de "Subir Foto"
                                  const uploadPhotoButton = document.getElementById(`uploadPhotoButton-${visita.idVisitas}`);
                                  const fileInput = document.getElementById(`fileInput-${visita.idVisitas}`);

                                  uploadPhotoButton.addEventListener('click', () => {
                                    fileInput.click(); // Simula el clic en el input de archivo
                                  });

                                  // Manejar la selección de archivo
                                  fileInput.addEventListener('change', () => {
                                    const file = fileInput.files[0];
                                    if (file) {
                                      const formData = new FormData();
                                      formData.append('photo', file);
                                      formData.append('visitaId', visita.idVisitas);

                                      // Hacer la solicitud para subir la foto
                                      fetch('/api/subirFoto', {
                                        method: 'POST',
                                        body: formData,
                                      })
                                        .then(response => response.json())
                                        .then(data => {
                                          if (data.success) {
                                            toastr.success("Foto subida con éxito.");
                                            const uploadPhotoButton = document.getElementById(`uploadPhotoButton-${visita.idVisitas}`);
                                            const siguienteButton = document.getElementById(`siguiente-${visita.idVisitas}`); // Cambiar a "Siguiente"
                                            uploadPhotoButton.style.display = 'none';
                                            siguienteButton.style.display = 'block'; // Habilitar el botón "Siguiente"
                                            // Agregar el evento de clic al botón "Continuar" después de subir la foto
                                            continueButton.addEventListener('click', () => {
                                              // Mostrar el modal
                                              const modal = Alpine.$data(document.querySelector('[x-data="modal"]'));
                                              modal.toggle();
                                            });
                                          } else {
                                            // toastr.error("Hubo un error al subir la foto.");
                                          }
                                        })
                                        .catch(error => {
                                          console.error('Error al subir la foto:', error);
                                          // toastr.error("Hubo un error al subir la foto.");
                                        });
                                    } else {
                                      toastr.error("Por favor selecciona una foto.");
                                    }
                                  });
                                }
                              })
                              .catch(error => {
                                console.error('Error al guardar la ubicación:', error);
                              });
                          }
                        })
                        .catch(error => {
                          console.error('Error al actualizar la visita:', error);
                          alert('Hubo un error al actualizar la visita.');
                        });
                    })
                    .catch(error => {
                      console.error('Error al obtener la ubicación:', error);
                    });
                });
              } else {
                alert("La geolocalización no está disponible en este navegador.");
              }
            });
        });
      });

      document.getElementById('visitasContainer').style.display = 'block';
    } else {
      toastr.warning("No hay visitas para este ticket.");
    }
  })
  .catch(error => {
    console.error('Error al obtener las visitas:', error);
    alert('Ocurrió un error al obtener las visitas.');
  });






