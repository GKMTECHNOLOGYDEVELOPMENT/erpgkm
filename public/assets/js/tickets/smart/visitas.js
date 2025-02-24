

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
                const nombreTecnico = visita.nombre_tecnico || 'Nombre del Técnico';

                const visitaCard = document.createElement('div');
                visitaCard.className = 'bg-white border border-gray-200 rounded-lg shadow-lg p-5 w-full max-w-full sm:max-w-md mx-auto transform transition-transform hover:scale-105';
                visitaCard.innerHTML = `
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-3">
                        <h3 class="text-lg font-semibold text-gray-800">${visita.nombre}</h3>
                        <button type="button" class="bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-700 transition w-full sm:w-auto mt-2 sm:mt-0" id="detallesVisitaButton-${visita.idVisitas}">
                            Detalles de Visita
                        </button>
                    </div>
                    <div class="text-center px-4 py-2 rounded font-semibold mb-2" style="background-color: #e3e7fc">
                        <span class="text-gray-800">Fecha de Programación</span><br>
                        <span class="font-bold">${fechaInicio} - ${fechaFinal}</span>
                    </div>
                `;
                visitasList.appendChild(visitaCard);

                const detallesVisitaButton = document.getElementById(`detallesVisitaButton-${visita.idVisitas}`);
                detallesVisitaButton.addEventListener('click', () => {
                    document.getElementById('detalleNombre').textContent = visita.nombre;
                    document.getElementById('detalleFechaProgramada').textContent = formatDate(visita.fecha_programada);
                    document.getElementById('detalleFechaAsignada').textContent = formatDate(visita.fecha_asignada);
                    document.getElementById('detalleFechaDesplazamiento').textContent = formatDate(visita.fechas_desplazamiento);
                    document.getElementById('detalleFechaLlegada').textContent = formatDate(visita.fecha_llegada);
                    document.getElementById('detalleFechaInicio').textContent = formatDate(visita.fecha_inicio);
                    document.getElementById('detalleFechaFinal').textContent = formatDate(visita.fecha_final);
                    document.getElementById('detalleEstado').textContent = visita.estado ? 'Activo' : 'Inactivo';
                    document.getElementById('detalleEstado').className = visita.estado ? 'text-green-600 font-bold' : 'text-red-600 font-bold';

                    const event = new CustomEvent('toggle-modal-detalles-visita');
                    window.dispatchEvent(event);
                });

                const tecnicoCard = document.createElement('div');
                tecnicoCard.className = 'bg-white border border-gray-200 rounded-lg shadow-lg p-5 w-full max-w-full sm:max-w-md mx-auto transform transition-transform hover:scale-105 mt-4';
                tecnicoCard.innerHTML = `
                    <div class="text-center text-gray-600 flex flex-col sm:flex-row items-center justify-center" style="background-color: #deeffd">
                        <span class="font-medium text-yellow-600">Técnico en Desplazamiento:</span>
                        <span class="text-gray-800 font-semibold mx-2">${nombreTecnico}</span>
                        <span id="fechaDesplazamiento-${visita.idVisitas}" class="text-gray-500"> ${visita.fechas_desplazamiento ? formatDate(visita.fechas_desplazamiento) : 'Sin fecha de desplazamiento'}</span>
                        <button class="ml-4 hover:scale-110 transition" id="likeButton-${visita.idVisitas}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6 text-green-500 hover:text-green-700 transition">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
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
              inicioServicioCard.className = 'bg-white border border-gray-200 rounded-lg shadow-2xl p-5 max-w-md mx-auto transform transition-transform hover:scale-105 mt-4';
              inicioServicioCard.innerHTML = `
                  <div class="text-center text-gray-600">
                    <h3 class="text-lg font-semibold text-gray-800">Llegada al Servicio</h3>
                    <p class="text-gray-800 mt-2">El servicio ha comenzado.</p>
                  </div>
                  <button type="button" class="btn btn-success" id="uploadPhotoButton-${visita.idVisitas}">
                    Subir Foto
                  </button>
                  <button type="button" class="btn btn-outline-primary" id="continueButton-${visita.idVisitas}" style="display: none;">
                    Continuar
                  </button>
                  <input type="file" id="fileInput-${visita.idVisitas}" class="w-full mt-4 p-2 border border-gray-300 rounded-lg" accept="image/*" style="display: none;">
                `;
              visitasList.appendChild(inicioServicioCard);

              // Verificar si ya existe una foto para la visita
              fetch(`/api/verificarFoto/${visita.idVisitas}`)
                .then(response => response.json())
                .then(data => {
                  if (data.success) {
                    const uploadPhotoButton = document.getElementById(`uploadPhotoButton-${visita.idVisitas}`);
                    const continueButton = document.getElementById(`continueButton-${visita.idVisitas}`);
                    uploadPhotoButton.style.display = 'none';
                    continueButton.style.display = 'block';
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
                        const continueButton = document.getElementById(`continueButton-${visita.idVisitas}`);
                        uploadPhotoButton.style.display = 'none';
                        continueButton.style.display = 'block';
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
                                  inicioServicioCard.className = 'bg-white border border-gray-200 rounded-lg shadow-2xl p-5 max-w-md mx-auto transform transition-transform hover:scale-105 mt-4';
                                  inicioServicioCard.innerHTML = `
                                      <div class="text-center text-gray-600">
                                        <h3 class="text-lg font-semibold text-gray-800">Llegada al servicio</h3>
                                        <p class="text-gray-800 mt-2">El servicio ha comenzado.</p>
                                      </div>
                                      <button type="button" class="btn btn-success" id="uploadPhotoButton-${visita.idVisitas}">
                                            Subir Foto
                                        </button>
                                        <button type="button" class="btn btn-outline-primary" id="continueButton-${visita.idVisitas}" style="display: none;">
                                            Continuar
                                        </button>
                                      <input type="file" id="fileInput-${visita.idVisitas}" class="w-full mt-4 p-2 border border-gray-300 rounded-lg" accept="image/*" style="display: none;">
                                    `;
                                  visitasList.appendChild(inicioServicioCard);

                                  fetch(`/api/verificarFoto/${visita.idVisitas}`)
                                    .then(response => response.json())
                                    .then(data => {
                                      if (data.success) {
                                        const uploadPhotoButton = document.getElementById(`uploadPhotoButton-${visita.idVisitas}`);
                                        const continueButton = document.getElementById(`continueButton-${visita.idVisitas}`);
                                        uploadPhotoButton.style.display = 'none';
                                        continueButton.style.display = 'block';
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
                                            const continueButton = document.getElementById(`continueButton-${visita.idVisitas}`);
                                            uploadPhotoButton.style.display = 'none';
                                            continueButton.style.display = 'block';
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
