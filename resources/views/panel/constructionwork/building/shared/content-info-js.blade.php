<script>
    (function() {
        var app = new App();

        app.relationModal('buildings', {
            info: {
                element: '#showBuildingModal',
                url: '{{ url('ajax/search/buildings/one') }}'
            },
            save: {
                element: '#saveBuildingModal'
            }
        });

        $(document).on('info:request.rm.buildings', function(event) {

            var modal = event.modal;
            var response = event.response;
            var building = response.building;

            if (building !== null) {

                var shadeArea = accounting.formatMoney(ifNull(building.UbicaEdificioAreaDesplante, '', 0), '', 2, '.', ',');
                var totalArea = accounting.formatMoney(ifNull(building.UbicaEdificioAreaTotal, '', 0), '', 2, '.', ',');
                var totalAreaExt = accounting.formatMoney(ifNull(building.UbicaEdificioAreaTotalExterior, '', 0), '', 2, '.', ',');
                var totalLevels = accounting.formatMoney(ifNull(building.UbicaEdificioNiveles, '', 0), '', 1, '.', ',');
                var levels = accounting.formatMoney(ifNull(response.total_levels, '', 0), '', 1, '.', ',');
                var totalAreaSystem = accounting.formatMoney(ifNull(response.area, 'int', 0), '', 2, '.', ',');
                var totalAreaSystemExt = accounting.formatMoney(ifNull(response.area, 'ext', 0), '', 2, '.', ',');

                // Mostramos los datos como texto
                $('#buildingAlias').html(ifNull(building.UbicaEdificioAlias));
                $('#buildingCode').html(ifNull(building.UbicaEdificioClave));
                $('#buildingName').html(ifNull(building.UbicaEdificioNombre));
                $('#buildingDescription').html(ifNull(building.UbicaEdificioDescripcion));
                $('#buildingShadeArea').html(shadeArea);
                $('#buildingTotalArea').html(totalArea);
                $('#buildingTotalAreaExt').html(totalAreaExt);
                $('#buildingTotalLevels').html(totalLevels);
                $('#buildingLevels').html(levels);
                $('#buildingTotalAreaSystem').html(totalAreaSystem);
                $('#buildingTotalAreaSystemExt').html(totalAreaSystemExt);

                // Agregamos los datos como valores
                modal.find('input[name="cto34_alias"]').val(ifNull(building.UbicaEdificioAlias, '', ''))
                modal.find('input[name="cto34_code"]').val(ifNull(building.UbicaEdificioClave, '', ''));
                modal.find('input[name="cto34_name"]').val(ifNull(building.UbicaEdificioNombre, '', ''));
                modal.find('textarea[name="cto34_description"]').val(ifNull(building.UbicaEdificioDescripcion, '', ''));
                modal.find('input[name="cto34_shadeArea"]').val(ifNull(building.UbicaEdificioAreaDesplante, '', 0));
                modal.find('input[name="cto34_totalAreaInt"]').val(ifNull(building.UbicaEdificioAreaTotal, '', 0));
                modal.find('input[name="cto34_totalAreaExt"]').val(ifNull(building.UbicaEdificioAreaTotalExterior, '', 0));
                modal.find('input[name="cto34_totalAreaSystem"]').val(totalAreaSystem);
                modal.find('input[name="cto34_totalAreaSystemExt"]').val(totalAreaSystemExt);
                modal.find('input[name="cto34_totalArea"]').val();
                modal.find('input[name="cto34_levelsTotal"]').val(totalLevels);
                modal.find('input[name="cto34_levels"]').val(levels);
                modal.find('input[name="cto34_id"]').val(ifNull(building.tbUbicaEdificioID, '', 0));

                app.limitInput('textarea[name="cto34_description"]', 4000);
            }
        });

    })();
</script>