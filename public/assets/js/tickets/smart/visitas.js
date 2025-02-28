

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

        // CARD PRINCIPAL QUE ENVUELVE TODO
        const cardContainer = document.createElement('div');
        cardContainer.className = 'rounded-lg shadow-xl p-6 w-full sm:max-w-4xl mx-auto mt-6 border border-gray-200';

        // Header de la Card (Nombre de la Visita + Botón Seleccionar)
        const cardHeader = document.createElement('div');
        cardHeader.className = 'flex justify-between items-center mb-4 border-b pb-2';

        const visitaTitle = document.createElement('h2');
        visitaTitle.className = 'text-lg font-bold text-primary';
        // Nombre de la Visita con el Técnico al costado
        // Nombre de la Visita con el Técnico al costado (Aplicando badge-outline-primary a todo)
        visitaTitle.innerHTML = `
  <span class="badge badge-outline-primary text-lg font-semibold px-3 py-1 rounded-lg shadow-md">
    ${visita.nombre} - Técnico responsable: ${nombreTecnico}
  </span>
`;



        const selectButton = document.createElement('button');
        selectButton.className = 'btn btn-warning seleccionarVisitaButton';
        selectButton.setAttribute('data-id-ticket', visita.idTickets);
        selectButton.setAttribute('data-id-visita', visita.idVisita);
        selectButton.setAttribute('data-nombre-visita', visita.nombre_visita);
        selectButton.textContent = 'Seleccionar Visita';

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
            <!-- Encabezados -->
            <div class="grid grid-cols-1 sm:grid-cols-2 text-center gap-2">
              <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md">Estado</span>
              <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md">Fecha</span>
            </div>
        
            <!-- Contenido -->
            <div class="grid grid-cols-1 sm:grid-cols-2 text-center gap-2 p-2">
              <span class="badge bg-primary text-white text-xs px-3 py-1 rounded-lg shadow-md">Fecha de Programación</span>
              <span class="badge bg-primary text-white text-xs px-3 py-1 rounded-lg shadow-md">${fechaInicio} - ${fechaFinal}</span>
            </div>
          </div>
        `;
        







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
                  // toastr.success(data.message);  // Muestra el mensaje de éxito
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


        // Tarjeta de Técnico en Desplazamiento con el botón de "like"
        const tecnicoCard = document.createElement('div');
        tecnicoCard.className = 'rounded-lg shadow-md p-4 w-full sm:max-w-md mx-auto bg-[#deeffd]';
        tecnicoCard.innerHTML = `
          <div class="px-4 py-3 rounded-lg flex flex-col space-y-4">
            <!-- Encabezados -->
            <div class="grid grid-cols-3 text-center gap-2">
              <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md">Estado</span>
              <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md">Ubicación</span>
              <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md">Fecha</span>
            </div>
        
            <!-- Contenido -->
            <div class="grid grid-cols-3 text-center gap-2 p-2">
              <span class="badge bg-info text-white text-xs px-3 py-1 rounded-lg shadow-md">En Desplazamiento</span>
              <span class="badge bg-info text-white text-xs px-3 py-1 rounded-lg shadow-md">
              ${visita.ubicacion || 'Ubicación no disponible'}
            </span>
              <span id="fechaDesplazamiento-${visita.idVisitas}" class="badge bg-info text-white text-xs px-3 py-1 rounded-lg shadow-md">
            ${visita.fechas_desplazamiento ? formatDate(visita.fechas_desplazamiento) : 'Sin fecha de desplazamiento'}
        </span>

            </div>
        
            <!-- Botón de acción mejorado -->
            <div class="flex justify-center mt-2">
              <button class="badge bg-info text-white px-4 py-2 rounded-full shadow-md
                             transition-all duration-200 flex items-center gap-2 !bg-blue-600 !text-white"
                             id="likeButton-${visita.idVisitas}">
                <i class="fa-solid fa-route text-lg"></i> Iniciar Desplazamiento
              </button>
            </div>
          </div>
        `;


        // Agregar ambas tarjetas dentro del contenedor en la misma fila
        rowContainer.appendChild(visitaCard);
        rowContainer.appendChild(tecnicoCard);

        // Agregar la fila completa al contenedor de visitas
        // Agregar la fila completa dentro del contenedor principal
        cardContainer.appendChild(rowContainer);

        // Finalmente, agregar toda la card de la visita a la lista
        visitasList.appendChild(cardContainer);







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
                  <!-- Encabezados -->
                  <div class="grid grid-cols-3 text-center gap-2">
                    <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md">Estado</span>
                    <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md">Ubicación</span>
                    <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md">Fecha</span>
                  </div>
              
                  <!-- Contenido -->
                  <div class="grid grid-cols-3 text-center gap-2 p-2">
                    <span class="badge bg-success text-white text-xs px-3 py-1 rounded-lg shadow-md">Llegada al Servicio</span>
                    <span class="badge bg-success text-white text-xs px-3 py-1 rounded-lg shadow-md">
                      ${visita.ubicacion || 'Ubicación no disponible'}
                    </span>
                    <span class="badge bg-success text-white text-xs px-3 py-1 rounded-lg shadow-md">
                      ${visita.fecha_llegada ? formatDate(visita.fecha_llegada) : 'Sin fecha'}
                    </span>
                  </div>
              
                  <!-- Botones de acción -->
                  <div class="flex justify-center gap-3 mt-4">
                    <button class="bg-success hover:bg-green-700 text-white px-4 py-2 rounded-full shadow-md
                                   transition-all duration-200 flex items-center gap-2 !bg-success !text-white"
                                   id="uploadPhotoButton-${visita.idVisitas}">
                      <i class="fa-solid fa-camera text-lg"></i> Subir Foto
                    </button>
              
                    <button class="bg-success text-white px-4 py-2 rounded-full shadow-md
                                   transition-all duration-200 flex items-center gap-2 !bg-red-600 !text-white"
                                   id="siguiente-${visita.idVisitas}">
                      <i class="fa-solid fa-arrow-right text-lg"></i> Siguiente
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
    <!-- Encabezados -->
    <div class="grid grid-cols-3 text-center gap-2">
      <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md">Estado</span>
      <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md">Ubicación</span>
      <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md">Fecha</span>
    </div>

    <!-- Contenido -->
    <div class="grid grid-cols-3 text-center gap-2 p-2">
      <span class="badge bg-danger text-white text-xs px-3 py-1 rounded-lg shadow-md">Inicio de Servicio</span>
      <span class="badge bg-danger text-white text-xs px-3 py-1 rounded-lg shadow-md">
        ${visita.ubicacion || 'Ubicación no disponible'}
      </span>
      <span class="badge bg-danger text-white text-xs px-3 py-1 rounded-lg shadow-md">
        ${visita.fecha_inicio ? formatDate(visita.fecha_inicio) : 'Sin fecha'}
      </span>
    </div>

    <!-- Botón de continuar -->
    <div class="flex justify-center mt-4">
      <button class="badge bg-danger text-white px-5 py-2 rounded-full shadow-md
                     transition-all duration-200 flex items-center gap-2 !badge bg-danger !text-white"
                     id="continueButton-${visita.idVisitas}">
        <i class="fa-solid fa-check-circle text-lg"></i> Continuar
      </button>
    </div>
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
                finalServicioCard.className = 'rounded-lg shadow-md p-4 w-full sm:max-w-md mx-auto bg-[#fbe5e6] border border-gray-300';
                finalServicioCard.innerHTML = `
                  <div class="px-4 py-3 rounded-lg flex flex-col space-y-4">
                    <!-- Encabezados -->
                    <div class="grid grid-cols-3 text-center gap-2">
                      <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md">Estado</span>
                      <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md">Ubicación</span>
                      <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md">Fecha</span>
                    </div>
                
                    <!-- Contenido -->
                    <div class="grid grid-cols-3 text-center gap-2 p-2">
                      <span class="badge bg-danger text-white text-xs px-3 py-1 rounded-lg shadow-md">Final de Servicio</span>
                      <span class="badge bg-danger text-white text-xs px-3 py-1 rounded-lg shadow-md">
                        ${visita.ubicacion || 'Ubicación no disponible'}
                      </span>
                      <span class="badge bg-danger text-white text-xs px-3 py-1 rounded-lg shadow-md">
                        ${visita.fecha_final ? formatDate(visita.fecha_final) : 'Sin fecha'}
                      </span>
                    </div>
                
                    <!-- Mensaje de finalización -->
                    <div class="text-center">
                      <p class="text-gray-800 font-semibold">El servicio ha finalizado.</p>
                    </div>
                
                    <!-- Botón de continuar -->
                    <div class="flex justify-center mt-4">
                      <button class="bg-danger hover:bg-red-700 text-white px-5 py-2 rounded-full shadow-md
                                     transition-all duration-200 flex items-center gap-2"
                                     id="continueButton-${visita.idVisitas}">
                        <i class="fa-solid fa-check-circle text-lg"></i> Continuar
                      </button>
                    </div>
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
                          const event = new CustomEvent('toggle-modal-condiciones');

                          window.dispatchEvent(event);

                        });
                      } else {
                        toastr.error("Hubo un error al subir la foto.");
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

                  const geocodeUrl = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lng}&key=YOUR_GOOGLE_MAPS_API_KEY`;
                  fetch(geocodeUrl)
                    .then(response => response.json())
                    .then(data => {
                      const ubicacion = data.results[0]?.formatted_address || "Ubicación desconocida";

                      fetch(`/api/actualizarVisita/${visita.idVisitas}`, {
                        method: 'PATCH',
                        headers: {
                          'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                          fechas_desplazamiento: nuevaFechaDesplazamiento,
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

                                  const inicioServicioCard = document.createElement('div');
                                  inicioServicioCard.className = 'rounded-lg shadow-md p-4 w-full sm:max-w-md mx-auto bg-[#d9f2e6] border border-gray-300';
                                  inicioServicioCard.innerHTML = `
                                    <div class="px-4 py-3 rounded-lg flex flex-col space-y-4">
                                      <!-- Encabezados -->
                                      <div class="grid grid-cols-3 text-center gap-2">
                                        <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md">Estado</span>
                                        <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md">Ubicación</span>
                                        <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md">Fecha</span>
                                      </div>
                                  
                                      <!-- Contenido -->
                                      <div class="grid grid-cols-3 text-center gap-2 p-2">
                                        <span class="badge bg-green-600 text-white text-xs px-3 py-1 rounded-lg shadow-md">Llegada al Servicio</span>
                                        <span class="badge bg-green-600 text-white text-xs px-3 py-1 rounded-lg shadow-md">
                                          ${visita.ubicacion || 'Ubicación no disponible'}
                                        </span>
                                        <span class="badge bg-green-600 text-white text-xs px-3 py-1 rounded-lg shadow-md">
                                          ${visita.fecha_llegada ? formatDate(visita.fecha_llegada) : 'Sin fecha'}
                                        </span>
                                      </div>
                                  
                                      <!-- Mensaje -->
                                      <div class="text-center">
                                        <p class="text-gray-800 font-semibold">El servicio ha comenzado.</p>
                                      </div>
                                  
                                      <!-- Botones de acción -->
                                      <div class="flex justify-center gap-3 mt-4">
                                        <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-full shadow-md
                                                       transition-all duration-200 flex items-center gap-2"
                                                       id="uploadPhotoButton-${visita.idVisitas}">
                                          <i class="fa-solid fa-camera text-lg"></i> Subir Foto
                                        </button>
                                  
                                        <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-full shadow-md
                                                       transition-all duration-200 flex items-center gap-2"
                                                       id="siguiente-${visita.idVisitas}">
                                          <i class="fa-solid fa-arrow-right text-lg"></i> Siguiente
                                        </button>
                                      </div>
                                  
                                      <!-- Input oculto para subir foto -->
                                      <input type="file" id="fileInput-${visita.idVisitas}" class="hidden" accept="image/*">
                                    </div>
                                  `;

                                  visitasList.appendChild(inicioServicioCard);






                                  // Agregar el evento de clic al botón "Siguiente"
                                  const siguienteButton = document.getElementById(`siguiente-${visita.idVisitas}`);
                                  siguienteButton.addEventListener('click', () => {
                                    // Crear la card de "Final de Servicio"
                                    const finalServicioCard = document.createElement('div');
                                    finalServicioCard.className = 'rounded-lg shadow-md p-4 w-full sm:max-w-md mx-auto bg-[#fbe5e6] border border-gray-300';
                                    finalServicioCard.innerHTML = `
                                      <div class="px-4 py-3 rounded-lg flex flex-col space-y-4">
                                        <!-- Encabezados -->
                                        <div class="grid grid-cols-3 text-center gap-2">
                                          <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md">Estado</span>
                                          <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md">Ubicación</span>
                                          <span class="badge bg-dark text-white text-xs px-3 py-1 rounded-lg shadow-md">Fecha</span>
                                        </div>
                                    
                                        <!-- Contenido -->
                                        <div class="grid grid-cols-3 text-center gap-2 p-2">
                                          <span class="badge bg-danger text-white text-xs px-3 py-1 rounded-lg shadow-md">Inicio de Servicio</span>
                                          <span class="badge bg-danger text-white text-xs px-3 py-1 rounded-lg shadow-md">
                                            ${visita.ubicacion || 'Ubicación no disponible'}
                                          </span>
                                          <span class="badge bg-danger text-white text-xs px-3 py-1 rounded-lg shadow-md">
                                            ${visita.fecha_inicio ? formatDate(visita.fecha_inicio) : 'Sin fecha'}
                                          </span>
                                        </div>
                                    
                                        <!-- Mensaje de inicio -->
                                        <div class="text-center">
                                          <p class="text-gray-800 font-semibold">Inicio de servicio.</p>
                                        </div>
                                    
                                        <!-- Botón de continuar -->
                                        <div class="flex justify-center mt-4">
                                          <button class="bg-danger hover:bg-red-700 text-white px-5 py-2 rounded-full shadow-md
                                                         transition-all duration-200 flex items-center gap-2"
                                                         id="continueButton-${visita.idVisitas}">
                                            <i class="fa-solid fa-arrow-right text-lg"></i> Continuar
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
                                            toastr.error("Hubo un error al subir la foto.");
                                          }
                                        })
                                        .catch(error => {
                                          console.error('Error al subir la foto:', error);
                                          toastr.error("Hubo un error al subir la foto.");
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
      alert("No hay visitas para este ticket.");
    }
  })
  .catch(error => {
    console.error('Error al obtener las visitas:', error);
    alert('Ocurrió un error al obtener las visitas.');
  });



