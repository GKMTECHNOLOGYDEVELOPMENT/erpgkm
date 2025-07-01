document.addEventListener("DOMContentLoaded", function() {
    // Manejar el envío del formulario de subcategoría
    $('#subcategoriaForm').on('submit', function(e) {
        e.preventDefault();
        
        // Mostrar loader o deshabilitar botón para mejor UX
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Guardando...');

        $.ajax({
            url: '/subcategoriarepuesto/store',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if(response.success) {
                    toastr.success('Subcategoría creada correctamente');
                    
                    // Agregar la nueva opción al select de subcategorías
                    var newOption = new Option(
                        response.subcategoria.nombre,
                        response.subcategoria.id,
                        true,  // selected
                        true   // selected
                    );
                    
                    $('#idsubcategoria')
                        .append(newOption)
                        .trigger('change')
                        .closest('.select2-container') // Forzar actualización visual de Select2
                        .find('.select2-selection')
                        .addClass('border-green-500') // Feedback visual
                        .delay(1000)
                        .queue(function() {
                            $(this).removeClass('border-green-500').dequeue();
                        });

                    // Cerrar el modal después de 1 segundo (opcional)
                    setTimeout(() => {
                        window.dispatchEvent(new CustomEvent('toggle-subcategoria-modal'));
                    }, 800);
                    
                    // Resetear completamente el formulario
                    $('#subcategoriaForm')[0].reset();
                    
                    // Resetear estados de validación si usas algún plugin
                    $('#subcategoriaForm').find('.is-invalid').removeClass('is-invalid');
                    $('#subcategoriaForm').find('.error-msg').remove();
                    
                } else {
                    toastr.error(response.message || 'Error al crear la subcategoría');
                }
            },
            error: function(xhr) {
                let errorMsg = 'Error en el servidor';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    // Mostrar errores de validación del backend
                    const errors = xhr.responseJSON.errors;
                    errorMsg = Object.values(errors)[0][0];
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                toastr.error(errorMsg);
            },
            complete: function() {
                // Restaurar botón
                submitBtn.prop('disabled', false).html('Guardar');
            }
        });
    });

    // Limpiar formulario cuando se cierre el modal
    document.addEventListener('modal-closed', function() {
        $('#subcategoriaForm')[0].reset();
    });




    // Manejar el envío del formulario de unidad
    $('#unidadForm').on('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Guardando...');

        $.ajax({
            url: '/unidades/store',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if(response.success) {
                    toastr.success('Unidad creada correctamente');
                    
                    // Agregar nueva opción al select
                    var newOption = new Option(
                        response.unidad.nombre,
                        response.unidad.idUnidad,
                        true,
                        true
                    );
                    
                    $('#idUnidad')
                        .append(newOption)
                        .trigger('change')
                        .closest('.select2-container')
                        .find('.select2-selection')
                        .addClass('border-green-500')
                        .delay(1000)
                        .queue(function() {
                            $(this).removeClass('border-green-500').dequeue();
                        });

                    // Cerrar modal después de 800ms
                    setTimeout(() => {
                        window.dispatchEvent(new CustomEvent('toggle-unidad-modal'));
                    }, 800);
                    
                    // Limpiar formulario
                    $('#unidadForm')[0].reset();
                    $('#unidadForm').find('.is-invalid').removeClass('is-invalid');
                    
                } else {
                    toastr.error(response.message || 'Error al crear la unidad');
                }
            },
            error: function(xhr) {
                let errorMsg = 'Error en el servidor';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMsg = Object.values(xhr.responseJSON.errors)[0][0];
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                toastr.error(errorMsg);
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('Guardar');
            }
        });
    });

    // Limpiar formulario cuando se cierre el modal
    document.addEventListener('modal-unidad-closed', function() {
        $('#unidadForm')[0].reset();
    });


    // Manejar el envío del formulario del modelo
    $('#modeloForm').on('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Guardando...');

        $.ajax({
            url: '/modelorepuesto/store',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if(response && response.success) {
                    toastr.success('Modelo creado correctamente');
                    
                    // Verificar que existan todos los datos necesarios
                    if(response.modelo && response.marca && response.categoria) {
                        var newOption = new Option(
                            `${response.modelo.nombre} - ${response.marca.nombre} - ${response.categoria.nombre}`,
                            response.modelo.idModelo,
                            true,
                            true
                        );
                        
                        $('#idModelo').append(newOption).trigger('change');
                    }

                    // Cerrar el modal
                    window.dispatchEvent(new CustomEvent('toggle-modal'));
                    
                    // Resetear el formulario
                    $('#modeloForm')[0].reset();
                    $('#idMarca, #idCategoria').val(null).trigger('change');
                    
                } else {
                    toastr.error(response.message || 'Error al crear el modelo');
                }
            },
            error: function(xhr) {
                let errorMsg = 'Error en el servidor';
                if (xhr.responseJSON) {
                    if(xhr.responseJSON.errors) {
                        errorMsg = Object.values(xhr.responseJSON.errors)[0][0];
                    } else if(xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                }
                toastr.error(errorMsg);
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('Guardar');
            }
        });
    });
});
