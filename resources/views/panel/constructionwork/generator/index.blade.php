@extends('layouts.base')
{{--
    Content
    Cuerpo de la vista
--}}
@section('content')
    {{--
        Alertas
        Se mostraran las alertas que el sistema envíe
        si se redirecciona a index
    --}}
    @include('layouts.alerts', ['errors' => $errors])
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default panel-section bg-white">
                    <div class="col-sm-8">
                        {{--
                            Submenu
                            Se incluyen el menu para obras
                        --}}
                        @include('panel.constructionwork.shared.submenu')
                    </div>
                    <div class="col-sm-4">
                        {{--
                            Nav Actions
                            Se incluyen los botones de acción para los registros

                            @param $model['count'] total de registros
                            @param $model['id'] id del registro
                        --}}
                        @include('shared.nav-actions-info',
                            [ 'model' =>
                                [
                                    'count' => $generators['all']->count(),
                                    'id' => ($generators['all']->count() > 0) ? $generators['one']->getKey() : 0,
                                    'inactive' => ($generators['all']->count() > 0) ? $generators['one']->RegistroInactivo : 0,
                                    'closed' => ($generators['all']->count() > 0) ? $generators['one']->RegistroCerrado : 0
                                ]
                            ]
                        )
                    </div>
                    <div class="clearfix"></div>
                    {{--
                        Content Info
                        Se incluye la estructura donde se muestra la información del registro
                    --}}
                    @include('panel.constructionwork.generator.shared.content-info')
                </div>
            </div>
        </div>
    </div>
    {{--
        Modals
        Se incluyen los modales que se requieren para el registro
    --}}
    @if (($generators['all']->count() > 0))
        @include('panel.constructionwork.generator.shared.filter-modal')
        @include('shared.record-closed-modal')
        @include('shared.record-opened-modal')
        @include('shared.record-delete-modal', [
            'id' => $generators['one']->getKey(),
            'work' => $works['one']->tbObraID,
        ])
        @include('shared.record-restore-modal', [
            'id' => $generators['one']->getKey(),
            'work' => $works['one']->tbObraID,
        ])
        @include('shared.comment-modal', [
                'model' => $generators['one']
            ])
    @endif
@endsection
@push('scripts_footer')
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/moment.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ asset('assets/js/constructionWork.js') }}"></script>
<script src="{{ asset('assets/js/business.js') }}"></script>
<script src="{{ asset('assets/js/person.js') }}"></script>
<script src="{{ asset('assets/js/catalog.js') }}"></script>
<script>
    (function() {

        var app = new App();
        app.initItemsList();
        app.deleteConfirm('confirmDeleteAlert', 'confirmDeleteButton', 'cancelDeleteButton');
        app.animateSubmit("saveForm", "addSubmitButton");
        app.animateSubmit("saveCaptureForm", "submitCaptureButton");
        app.animateSubmit("saveRevisionForm", "submitRevisionButton");
        app.animateSubmit("saveAuthorizationForm", "submitAuthorizationButton");
        app.tooltip();
        app.dateTimePickerField();
        app.filterModal();
        app.highlightSearch();
        app.onPageTab();

        app.closeRecord({
            url: "{{ url('/ajax/action/records/closed') }}",
            table: "{{  ($generators['all']->count() > 0) ? $generators['one']->getTable() : '' }}",
            tableId: "{{ ($generators['all']->count() > 0) ? $generators['one']->getKeyName() : '' }}"
        });

        var work = new ConstructionWork();
        var catalog = new Catalog();

        work.searchPersonWithAjax('#cto34_personReviser',
            {
                url: '{{ url("ajax/search/personsWork") }}',
                token: '{{ csrf_token() }}',
                workId: '{{ $works['one']->tbObraID }}',
                optionClass: 'option-newReciver',
                optionListClass: 'option-receiver',
                canAdd: false

            }, function(data) {

                if (data.action === 'newClicked') {

                }

                if (data.action === 'optionClicked') {
                    onSearchOptionSelected(data.element);
                }
            }
        );

        $('#personReviserMe').on('click',  function() {

            var id = $(this).data('id');
            var name = $(this).data('name');
            var elementId = "#cto34_personReviser";

            if ($(elementId + 'Name').val() == name) {
                return;
            }

            $(elementId).append($('<option>', {
                value: id,
                text: name,
            }).attr('selected', 'selected'));

            $(elementId + 'Name').val(name);

            $(elementId).selectpicker({title: name})
                .selectpicker('refresh');

        });


        work.searchPersonWithAjax('#cto34_personAuthorizer',
            {
                url: '{{ url("ajax/search/personsWork") }}',
                token: '{{ csrf_token() }}',
                workId: '{{ $works['one']->tbObraID }}',
                optionClass: 'option-newPersonAuthorizer',
                optionListClass: 'option-personAuthorizer',
                canAdd: false

            }, function(data) {

                if (data.action === 'newClicked') {

                }

                if (data.action === 'optionClicked') {
                    onSearchOptionSelected(data.element);
                }
            }
        );

        $('#personAuthorizerMe').on('click',  function() {

            var id = $(this).data('id');
            var name = $(this).data('name');
            var elementId = "#cto34_personAuthorizer";

            if ($(elementId + 'Name').val() == name) {
                return;
            }

            $(elementId).append($('<option>', {
                value: id,
                text: name,
            }).attr('selected', 'selected'));

            $(elementId + 'Name').val(name);

            $(elementId).selectpicker({title: name})
                .selectpicker('refresh');

        });

        $('#calendarToday').on('click', function() {

            var field = $('#cto34_signatureDate');
            field.val(moment().format('YYYY-MM-DD'));
        });

        $('#calendarReviserToday').on('click', function() {

            var field = $('#cto34_reviserDate');
            field.val(moment().format('YYYY-MM-DD'));
        });

        $('#calendarAuthorizerToday').on('click', function() {

            var field = $('#cto34_authorizerDate');
            field.val(moment().format('YYYY-MM-DD'));
        });

        catalog.advanceSearchWithAjax('#searchCatalogs',
            {url: '{{ url('ajax/action/search/catalogs')  }}', token: '{{ csrf_token() }}', workId: '{{ $works['one']->tbObraID }}'},
            function(error, result) {

                var message = $('#searchCatalogsMessage');
                var body = $('#catalogBody').clone();

                if (error !== null) {
                    alert(error);
                    return;
                }

                if (result.catalogs.length === 0 || result.catalogs === null ) {
                    message.html('No hay resultados');
                    return;
                }

                message.html('');

                $('#catalogList').empty();
                $('#catalogListInfo').empty();

                var catalogList = '';
                var catalogInfo = '';

                $.each(result.catalogs, function( index, value ) {

                    if (index === 0) {
                        catalogList += '<a href="#catalogList' + index +'" aria-controls="catalogList' + index +'" role="tab" data-toggle="tab" class="list-group-item active">';
                    } else {
                        catalogList += '<a href="#catalogList' + index +'" aria-controls="catalogList' + index +'" role="tab" data-toggle="tab" class="list-group-item">';
                    }

                    catalogList += '<h4 class="list-group-item-heading">' + value.CatalogoConceptoCodigo + '</h4>';
                    catalogList += '<p class="small">'+ value.tbUbicaNivelID_Catalogo +'</p>';
                    catalogList += '</a>';
                    $('#catalogList').append(catalogList);

                    if (index === 0) {
                        catalogInfo += '<div class="tab-pane active" id="catalogList' + index +'">';
                    } else {
                        catalogInfo += '<div class="tab-pane" id="catlogList' + index +'">';
                    }

                    var tempElement = $('<div></div>');

                    var copyButton = $('<a href="#" class="is-tooltip"  data-placement="bottom" title="Copiar" data-id="'+ value.tbCatalogoID +'" data-catalog="#catalogList' + index +'"><span class="fa-stack fa-lg"> <i class="fa fa-circle fa-stack-2x"></i> <i class="fa fa-paperclip fa-stack-1x fa-inverse"></i> </span></a>');
                    //copyButton.html('<span class="fa-stack fa-lg"> <i class="fa fa-circle fa-stack-2x"></i> <i class="fa fa-paperclip fa-stack-1x fa-inverse"></i> </span>');
                    copyButton.on('click', function(event){
                        event.preventDefault();

                        var selectedTab = $($(this).data('catalog'));
                        var workType = selectedTab.find('.cb-workType');
                        var code = selectedTab.find('.cb-code');
                        var unity = selectedTab.find('.cb-unity');
                        var quantity = selectedTab.find('.cb-quantity');
                        var unitPrice = selectedTab.find('.cb-unitPrice');
                        var amount = selectedTab.find('.cb-amount');
                        var description = selectedTab.find('.cb-fullDescription');
                        var departure = selectedTab.find('.cb-departure');
                        var subDeparture = selectedTab.find('.cb-subDeparture');
                        var budgetType = selectedTab.find('.cb-budgetType');
                        var level = selectedTab.find('.cb-level');

                        $('input[name="cto34_catalog"]').val($(this).data('id'));
                        $('#cto34_workType').val(workType.text());
                        $('#cto34_code').val(code.text());
                        $('#cto34_unity').val(unity.text());
                        $('#cto34_quantity').val(quantity.text());
                        $('#cto34_unitPrice').val(unitPrice.text());
                        $('#cto34_amount').val(amount.text());
                        $('#cto34_description').val(description.text());
                        $('#cto34_departure').val(departure.text());
                        $('#cto34_subHeading').val(subDeparture.text());
                        $('#cto34_catalogQuantity').val(amount.text());
                        $('#cto34_budgetType').val(budgetType.text());
                        $('#cto34_level').val(level.text());
                        $('#modalCatalogs').modal('hide');

                    });

                    var nav = '<div class="col-sm-12">';
                    nav += '<ul class="nav nav-actions navbar-nav">';
                    nav += '<li class="nav-li"></li>';
                    nav += '</ul>';
                    nav += '</div>';
                    var elementNav = $(nav);
                    elementNav.find('.nav-li').append(copyButton);

                    body.find('.cb-contractor').html(value.contractor.EmpresaRazonSocial);
                    body.find('.cb-contract').html(value.contract.ContratoAlias);
                    body.find('.cb-code').html(value.CatalogoConceptoCodigo);
                    body.find('.cb-workType').html(value.CatalogoObraTipo);
                    body.find('.cb-unity').html(value.unity.UnidadAlias);
                    body.find('.cb-quantity').html(value.CatalogoCantidad);
                    body.find('.cb-unitPrice').html(value.CatalogoPrecioUnitario);
                    body.find('.cb-amount').html(value.CatalogoImporte);
                    body.find('.cb-fullDescription').html(value.CatalogoDescripcion);
                    body.find('.cb-shortDescription').html(value.CatalogoDescripcionCorta);
                    body.find('.cb-departure').html(value.tbPartidaID_Catalogo);
                    body.find('.cb-subDeparture').html(value.tbSubpartidaID_Catalogo);
                    body.find('.cb-budgetType').html(value.CatalogoPresupuestoTipo);
                    body.find('.cb-level').html(value.tbUbicaNivelID_Catalogo);
                    catalogInfo += body.html();
                    catalogInfo += '</div>';

                    $('#catalogListInfo').append(catalogInfo).prepend(elementNav);

                });

            });

        $('.nav-tabs-works a[data-toggle="tab"]').on('show.bs.tab', function (e) {

            var currentTabByHash = $(e.target)[0].hash;

            $('.is-submit').prop('disabled', true);

            switch (currentTabByHash) {
                case '#revision':
                    if ($('input[name="cto34_revisedQuantity"]').val() === '') {
                        $('.is-submit').attr('id', 'submitRevisionButton').prop('disabled', false);
                    }
                    break;

                case '#authorization':
                    if ($('input[name="cto34_authorizedQuantity"]').val() === '') {
                        $('.is-submit').attr('id', 'submitAuthorizationButton').prop('disabled', false);
                    }
                    break;

                default:
                    $('.is-submit').removeAttr('id', false).prop('disabled', true);
                    break;
            }

        });

        if (window.location.hash) {

            var currentHash = window.location.hash;
            console.log(currentHash);

            switch (currentHash) {

                case '#capture':
                    $('a[href="#capture"]').tab('show');
                    break;
                case '#revision':
                    $('a[href="#revision"]').tab('show');
                    break;

                case '#authorization':
                    $('a[href="#authorization"]').tab('show');
                    break;

                default:

                    break;
            }
        }

        $('#revisionSameButton').on('click', function() {

            var presentedQuantity = $('#presentedQuantity').val();
            $('input[name="cto34_revisedQuantity"]').val(presentedQuantity);

        });

        $('#authSameButton').on('click', function() {

            var presentedQuantity = $('#presentedQuantity').val();
            $('input[name="cto34_authorizedQuantity"]').val(presentedQuantity);

        });

    })();

    function onSearchOptionSelected(element) {
        var name = $(element).find('option:selected').text();
        $(element + 'Name').val(name);
    }
</script>
@endpush