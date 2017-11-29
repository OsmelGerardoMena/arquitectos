<script>

    (function() {

        var app = new App();

        app.relationModal('locals', {
            info: {
                element: '#showLocalModal',
                url: '{{ url('ajax/search/locals/one') }}'
            },
            save: {
                element: '#saveLocalModal'
            }
        });

        $(document).on('info:request.rm.locals', function(event) {

            var modal = event.modal;
            var response = event.response;
            var local = response.local;

            if (local !== null) {

                // Mostramos los datos como texto
                $('#localAlias').html(ifNull(local.UbicaLocalAlias));
                $('#localBuilding').html(ifNull((ifNull(local.level, 'building')), 'UbicaEdificioAlias'));
                $('#localLevel').html(ifNull(local.level, 'UbicaNivelAlias'));
                $('#localCode').html(ifNull(local.UbicaLocalClave));
                $('#localName').html(ifNull(local.UbicaLocalNombre));
                $('#localType').html(ifNull(local.UbicaLocalTipo));
                $('#localArea').html(accounting.formatMoney(ifNull(local.UbicaLocalArea), '', 2, '.', ','));

                if (local.UbicaLocalSumaAreaNivel == 1) {
                    $('#localSumArea').html('<span class="fa fa-check-square fa-fw text-info"></span>');
                } else {
                    $('#localSumArea').html('<span class="fa fa-square-o fa-fw"></span>');
                }

                // Agregamos los datos como valores
                modal.find('input[name="cto34_alias"]').val(ifNull(local.UbicaLocalAlias, '', ''));

                var buildingID = ifNull((ifNull(local.level, 'building')), 'tbUbicaEdificioID', '');
                modal.find('select[name="cto34_building"]').val(buildingID);

                var requestLevels = $.get('{{ url('/ajax/search/levels')  }}', { 'id': buildingID });
                var selectLevels = modal.find('select[name="cto34_level"]');

                requestLevels.done(function(result) {

                    var data = result;
                    var options = '';

                    options += '<optgroup label="Opción seleccionada"><option value="' + ifNull(local.tbUbicaNivelID_Local, '', '') + '" selected>' + ifNull(local.level, 'UbicaNivelAlias', '') +'</option></optgroup>';
                    options += '<option value="">Seleccionar opción</option>';

                    $.each(data.levels, function(index, level) {
                        options += '<option value="' + level.tbUbicaNivelID + '">' + level.UbicaNivelAlias + '</option>';
                    });

                    selectLevels.html(options);
                    selectLevels.prop('disabled', false);

                }).fail(function() {

                    selectLevels.prop('disabled', true);
                });

                //modal.find('input[name="cto34_code"]').val(ifNull(local.UbicaLocalClave, '', ''));
                modal.find('input[name="cto34_code"]').val(ifNull(local.UbicaLocalClave, '', ''));
                modal.find('input[name="cto34_name"]').val(ifNull(local.UbicaLocalNombre, '', ''));
                modal.find('select[name="cto34_type"]').val(ifNull(local.UbicaLocalTipo, '', ''));
                modal.find('input[name="cto34_area"]').val(ifNull(local.UbicaLocalArea, '', 0));
                modal.find('input[name="cto34_id"]').val(local.tbUbicaLocalID);

                if (local.UbicaLocalSumaAreaNivel == 1) {
                    modal.find('input[name="cto34_sumArea"]').prop('checked', true);
                } else {
                    modal.find('input[name="cto34_sumArea"]').prop('checked', false);
                }

                modal.find('select[name="cto34_building"]').on('change', function() {

                    var select = $(this);
                    var levels = modal.find('select[name="cto34_level"]');

                    if (select.val().length === 0) {
                        return;
                    }

                    var request = $.get('{{ url('/ajax/search/levels')  }}', { 'id': select.val() });

                    request.done(function(response) {

                        var data = response;
                        var options = '<option value="">Seleccionar opción</option>';

                        $.each(data.levels, function(index, level) {
                            options += '<option value="' + level.tbUbicaNivelID + '">' + level.UbicaNivelAlias + '</option>';
                        });

                        levels.html(options);
                        levels.prop('disabled', false);

                    }).fail(function() {
                        alert( "No se puede obetener los niveles." );
                        levels.prop('disabled', true);
                    })

                });
            }
        });

        $(document).on('save:show.rm.locals', function(event) {

            var modal = event.modal;
            modal.find('select[name="cto34_building"]').on('change', function() {

                var select = $(this);
                var levels = modal.find('select[name="cto34_level"]');

                if (select.val().length === 0) {
                    return;
                }

                var request = $.get('{{ url('/ajax/search/levels')  }}', { 'id': select.val() });

                request.done(function(response) {

                    var data = response;
                    var options = '<option value="">Seleccionar opción</option>';

                    $.each(data.levels, function(index, level) {
                        options += '<option value="' + level.tbUbicaNivelID + '">' + level.UbicaNivelAlias + '</option>';
                    });

                    levels.html(options);
                    levels.prop('disabled', false);

                }).fail(function() {
                    alert( "No se puede obetener los niveles." );
                    levels.prop('disabled', true);
                })

            });

        });

    })();

</script>