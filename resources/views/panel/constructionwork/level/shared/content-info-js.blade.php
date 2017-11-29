<script>

    (function() {

        var app = new App();

        app.relationModal('levels', {
            info: {
                element: '#showLevelModal',
                url: '{{ url('ajax/search/levels/one') }}'
            },
            save: {
                element: '#saveLevelModal'
            }
        });

        $(document).on('info:request.rm.levels', function(event) {

            var modal = event.modal;
            var response = event.response;
            var level = response.level;

            if (level !== null) {

                // Mostramos los datos como texto
                $('#levelConsecutive').html(ifNull(level.UbicaNivelConsecutivo));
                $('#levelAlias').html(ifNull(level.UbicaNivelAlias));
                $('#levelBuilding').html(ifNull(level.building, 'UbicaEdificioAlias'));
                $('#levelCode').html(ifNull(level.UbicaNivelClave));
                $('#levelName').html(ifNull(level.UbicaNivelNombre));
                $('#levelDescription').html(ifNull(level.UbicaNivelDescripcion));

                if (level.UbicaNivelSumaNivelEdificio == 1) {
                    $('#levelSum').html('<span class="fa fa-check-square fa-fw text-info"></span>');
                } else {
                    $('#levelSum').html('<span class="fa fa-square-o fa-fw"></span>');
                }

                if (level.UbicaNivelSumaAreaEdificio == 1) {
                    $('#levelAreaSum').html('<span class="fa fa-check-square fa-fw text-info"></span>');
                } else {
                    $('#levelAreaSum').html('<span class="fa fa-square-o fa-fw"></span>');
                }

                var numberFormat = '0.00';

                /*
                var levelArea = numeral(ifNull(level.UbicaNivelSuperficie, '', 0)).language("pt-br").format(numberFormat);
                var levelAreaExt = numeral(ifNull(level.UbicaNivelSuperficieExterior, '', 0)).format(numberFormat);
                var levelAreaSystem = numeral(ifNull(response.area, 'int', 0)).format(numberFormat);
                var levelAreaSystemExt = numeral(ifNull(response.area, 'ext', 0)).format(numberFormat);
                var levelNPT = numeral(ifNull(level.UbicaNivelNPT, '', 0)).format(numberFormat);
                var locals = numeral(ifNull(response.total_locals, '', 0)).format(numberFormat);*/

                var levelArea = accounting.formatMoney(ifNull(level.UbicaNivelSuperficie, '', 0), '', 2, '.', ',');
                var levelAreaExt = accounting.formatMoney(ifNull(level.UbicaNivelSuperficieExterior, '', 0), '', 2, '.', ',');
                var levelAreaSystem = accounting.formatMoney(ifNull(response.area, 'int', 0), '', 2, '.', ',');
                var levelAreaSystemExt = accounting.formatMoney(ifNull(response.area, 'ext', 0), '', 2, '.', ',');
                var levelNPT = accounting.formatMoney(ifNull(level.UbicaNivelNPT, '', 0), '', 2, '.', ',');
                var locals = accounting.formatMoney(ifNull(response.total_locals, '', 0), '', 1, '.', ',');



                $('#levelArea').html(levelArea);
                $('#levelAreaExt').html(levelAreaExt);
                $('#levelAreaSystem').html(levelAreaSystem);
                $('#levelAreaSystemExt').html(levelAreaSystemExt);
                $('#levelNPT').html(levelNPT);
                $('#levelLocals').html(locals);

                // Agregamos los datos como valores
                modal.find('input[name="cto34_consecutive"]').val(ifNull(level.UbicaNivelConsecutivo, '', ''));
                modal.find('input[name="cto34_alias"]').val(ifNull(level.UbicaNivelAlias, '', ''));
                //modal.find('input[name="cto34_building"]').val(ifNull(level.building, ''));
                modal.find('input[name="cto34_code"]').val(ifNull(level.UbicaNivelClave, '', ''));
                modal.find('input[name="cto34_name"]').val(ifNull(level.UbicaNivelNombre, '', ''));
                modal.find('textarea[name="cto34_description"]').val(ifNull(level.UbicaNivelDescripcion, '', ''));
                modal.find('input[name="cto34_surfaceLevel"]').val(ifNull(level.UbicaNivelSuperficie, '', ''));
                modal.find('input[name="cto34_surfaceLevelExt"]').val(ifNull(level.UbicaNivelSuperficieExterior, '', ''));
                modal.find('input[name="cto34_surfaceLevelSystem"]').val(levelAreaSystem);
                modal.find('input[name="cto34_surfaceLevelSystemExt"]').val(levelAreaSystemExt);
                modal.find('input[name="cto34_nptLevel"]').val(ifNull(level.UbicaNivelNPT, '', 0));
                modal.find('input[name="cto34_locals"]').val(locals);
                modal.find('input[name="cto34_id"]').val(level.tbUbicaNivelID);


                if (level.UbicaNivelSumaNivelEdificio == 1) {
                    modal.find('input[name="cto34_sumLevel"]').prop('checked', true);
                } else {
                    modal.find('input[name="cto34_sumLevel"]').prop('checked', false);
                }

                if (level.UbicaNivelSumaAreaEdificio == 1) {
                    modal.find('input[name="cto34_sumArea"]').prop('checked', true);
                } else {
                    modal.find('input[name="cto34_sumArea"]').prop('checked', false);
                }

                app.limitInput('textarea[name="cto34_description"]', 4000);

            }
        });

    })();

</script>