
    

        $(document).ready(function() {
            // Cuando se selecciona un departamento, obtener las provincias relacionadas
            $('#departamento').change(function() {
                var departamentoId = $(this).val();
                if (departamentoId) {
                    $.get('/ubigeo/provincias/' + departamentoId, function(data) {
                        var provinciaSelect = $('#provincia');
                        provinciaSelect.empty().prop('disabled', false);
                        provinciaSelect.append(
                            '<option value="" disabled selected>Seleccionar Provincia</option>');

                        data.forEach(function(provincia) {
                            provinciaSelect.append('<option value="' + provincia.id_ubigeo +
                                '">' + provincia.nombre_ubigeo + '</option>');
                        });
                    });
                } else {
                    $('#provincia').empty().prop('disabled', true);
                    $('#distrito').empty().prop('disabled', true);
                }
            });

            // Cuando se selecciona una provincia, obtener los distritos relacionados
            $('#provincia').change(function() {
                var provinciaId = $(this).val();

                if (provinciaId) {
                    $.get('/ubigeo/distritos/' + provinciaId, function(data) {
                        var distritoSelect = $('#distrito');
                        distritoSelect.empty().prop('disabled', false);
                        distritoSelect.append(
                            '<option value="" disabled selected>Seleccionar Distrito</option>');

                        data.forEach(function(distrito) {
                            distritoSelect.append('<option value="' + distrito.id_ubigeo +
                                '">' + distrito.nombre_ubigeo + '</option>');
                        });
                    });
                } else {
                    $('#distrito').empty().prop('disabled', true);
                }
            });
        });
