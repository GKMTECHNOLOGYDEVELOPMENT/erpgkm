

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
        const fechaInicio = formatDate(visita.fecha_inicio);
        const fechaFinal = formatDate(visita.fecha_final);
        const nombreTecnico = visita.nombre_tecnico || 'Nombre del Técnico'; // Nombre del técnico

        const visitaCard = document.createElement('div');
        visitaCard.className = 'rounded-lg shadow-2xl p-5 w-full sm:max-w-md mx-auto transform transition-transform hover:scale-105';
        visitaCard.style.backgroundColor = "#e3e7fc"; // Color azul claro
        visitaCard.innerHTML = `
        <div class="flex flex-col sm:flex-row justify-between items-center mb-3">
        <span class="text-lg font-semibold mb-4 badge bg-primary">${visita.nombre}</span>
        <button type="button" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-red-700 active:bg-red-800 transition-all w-full sm:w-auto mt-2 sm:mt-0 flex items-center justify-center gap-2 shadow-md relative after:content-[''] after:absolute after:bg-white/30 after:w-full after:h-full after:rounded-lg after:scale-150 after:opacity-0 after:transition-all hover:after:opacity-100 active:scale-95" id="detallesVisitaButton-${visita.idVisitas}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5a6 6 0 000 12 6 6 0 100-12zM21 21l-4.35-4.35" />
        </svg>
        <span>Detalles</span>
    </button>
    
    </div>
    <div class="text-center px-4 py-2 rounded font-semibold mb-2">
    <span class="text-primary text-sm sm:text-base">Fecha de Programación</span><br>
    <span class="font-bold" style="color:black">${fechaInicio} - ${fechaFinal}</span>
</div>
`;
        visitasList.appendChild(visitaCard);

        // Agregar el evento de clic al botón "Detalles de Visita"
        const detallesVisitaButton = document.getElementById(`detallesVisitaButton-${visita.idVisitas}`);
        detallesVisitaButton.addEventListener('click', () => {
          // Llenar el modal con los detalles de la visita
          document.getElementById('detalleNombre').textContent = visita.nombre;
          document.getElementById('detalleFechaProgramada').textContent = formatDate(visita.fecha_programada);
          document.getElementById('detalleFechaAsignada').textContent = formatDate(visita.fecha_asignada);
          document.getElementById('detalleFechaDesplazamiento').textContent = formatDate(visita.fechas_desplazamiento);
          document.getElementById('detalleFechaLlegada').textContent = formatDate(visita.fecha_llegada);
          document.getElementById('detalleFechaInicio').textContent = formatDate(visita.fecha_inicio);
          document.getElementById('detalleFechaFinal').textContent = formatDate(visita.fecha_final);
          document.getElementById('detalleEstado').textContent = visita.estado ? 'Activo' : 'Inactivo';

          // Mostrar el modal
          const event = new CustomEvent('toggle-modal-detalles-visita');
          window.dispatchEvent(event);
        });

        // Tarjeta de Técnico en Desplazamiento con el botón de "like"
        const tecnicoCard = document.createElement('div');
        tecnicoCard.className = 'rounded-lg shadow-2xl p-5 w-full sm:max-w-md mx-auto transform transition-transform hover:scale-105 mt-4';
        tecnicoCard.style.backgroundColor = "#deeffd"; // Color celeste claro
        tecnicoCard.innerHTML = `
        <div class="relative px-6 py-4 rounded-lg font-semibold text-center mb-2">
        <span class="text-primary text-sm sm:text-base">Técnico en Desplazamiento</span>
        <span class="font-bold text-black text-base sm:text-lg block mt-1">${nombreTecnico}</span>
        <span id="fechaDesplazamiento-${visita.idVisitas}" class="text-gray-600 text-sm sm:text-base block mt-1">
            ${visita.fechas_desplazamiento ? formatDate(visita.fechas_desplazamiento) : 'Sin fecha de desplazamiento'}
        </span>
        <button class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-white text-gray-700 border border-gray-300 hover:bg-gray-100 hover:border-gray-400 transition-all duration-200 ease-in-out active:bg-green-500 active:text-white active:border-green-600 focus:ring-2 focus:ring-green-300 p-2 rounded-full shadow-md hover:shadow-lg hover:scale-105 active:scale-95 flex items-center justify-center" id="likeButton-${visita.idVisitas}">
        <i class="fa-solid fa-forward text-lg"></i> <!-- Ícono de Siguiente -->
    </button>
    
    
    </div>
`;
        visitasList.appendChild(tecnicoCard);

        
        
        // Verificar si ya existe un registro de "Inicio de Servicio"
        fetch(`/api/verificarRegistroAnexo/${visita.idVisitas}`)
          .then(response => response.json())
          .then(anexoData => {
            if (anexoData && anexoData.idVisitas) {
              // Si existe el registro, mostrar la tarjeta de "Inicio de Servicio"
              const inicioServicioCard = document.createElement('div');
              inicioServicioCard.className = 'border border-gray-200 rounded-lg shadow-2xl p-5 w-full sm:max-w-md mx-auto transform transition-transform hover:scale-105 mt-4';
              inicioServicioCard.style.backgroundColor = "#d9f2e6"; // Color verde claro
              inicioServicioCard.innerHTML = `
                <div class="text-center px-4 py-2 rounded font-semibold mb-2 bg-gray-300">
                  <h3 class="text-primary text-sm sm:text-base">Llegada al Servicio</h3>
                  <p class="font-bold">El servicio ha comenzado.</p>
                </div>


                <button type="button" class="btn btn-success" id="uploadPhotoButton-${visita.idVisitas}">
                  Subir Foto
                </button>

                
                <button type="button" class="btn btn-outline-danger" id="siguiente-${visita.idVisitas}" style="display: none;">
                  Siguient
                </button>
           
                <input type="file" id="fileInput-${visita.idVisitas}" class="w-full mt-4 p-2 border border-gray-300 rounded-lg" accept="image/*" style="display: none;">
              `;
              visitasList.appendChild(inicioServicioCard);


              



// Agregar el evento de clic al botón "Siguiente"
const siguienteButton = document.getElementById(`siguiente-${visita.idVisitas}`);
siguienteButton.addEventListener('click', () => {
  // Crear la card de "Final de Servicio"
  const finalServicioCard = document.createElement('div');
  finalServicioCard.className = 'border border-gray-200 rounded-lg shadow-2xl p-5 w-full sm:max-w-md mx-auto transform transition-transform hover:scale-105 mt-4';
  finalServicioCard.style.backgroundColor = "#f8d7da"; // Color rojo claro para indicar finalización
  finalServicioCard.innerHTML = `
    <div class="text-center px-4 py-2 rounded font-semibold mb-2 bg-gray-300">
      <h3 class="text-primary text-sm sm:text-base">Inicio de Servicio</h3>
      <p class="font-bold">El servicio ha finalizado.</p>
    </div>
       <button type="button" class="btn btn-outline-primary" id="continueButton-${visita.idVisitas}" >
                  Continuar
                </button>
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


    // Actualizar la fecha de llegada en la base de datos
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

        // Ocultar el botón "Siguiente"

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
                      toastr.error("Hubo un error al subir la foto.");
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
                            fechaDesplazamientoElement.textContent = formatDate(updatedVisita.fechas_desplazamiento);

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
                                  inicioServicioCard.className = 'border border-gray-200 rounded-lg shadow-2xl p-5 max-w-md mx-auto transform transition-transform hover:scale-105 mt-4';
                                  inicioServicioCard.innerHTML = `
                                    <div class="text-center text-gray-600">
                                      <h3 class="text-lg font-semibold text-gray-800">Llegada al servicio</h3>
                                      <p class="text-gray-800 mt-2">El servicio ha comenzado.</p>
                                    </div>

                                           
  


                                    <button type="button" class="btn btn-success" id="uploadPhotoButton-${visita.idVisitas}">
                                          Subir Foto
                                      </button>

                                  <button type="button" class="btn btn-outline-danger" id="siguiente-${visita.idVisitas}" style="display: none;">
                                    Siguiente
                                  </button>
                                      


                                    <input type="file" id="fileInput-${visita.idVisitas}" class="w-full mt-4 p-2 border border-gray-300 rounded-lg" accept="image/*" style="display: none;">
                                  `;
                                  visitasList.appendChild(inicioServicioCard);





                                  
// Agregar el evento de clic al botón "Siguiente"
const siguienteButton = document.getElementById(`siguiente-${visita.idVisitas}`);
siguienteButton.addEventListener('click', () => {
  // Crear la card de "Final de Servicio"
  const finalServicioCard = document.createElement('div');
  finalServicioCard.className = 'border border-gray-200 rounded-lg shadow-2xl p-5 w-full sm:max-w-md mx-auto transform transition-transform hover:scale-105 mt-4';
  finalServicioCard.style.backgroundColor = "#f8d7da"; // Color rojo claro para indicar finalización
  finalServicioCard.innerHTML = `
    <div class="text-center px-4 py-2 rounded font-semibold mb-2 bg-gray-300">
      <h3 class="text-primary text-sm sm:text-base">Inicio de Servicio</h3>
      <p class="font-bold">Inicio de servicio .</p>
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
    finalServicioCard.style.display = 'none'; // Ocultar la card de "Final de Servicio"
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
