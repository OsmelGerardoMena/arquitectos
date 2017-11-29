@extends('layouts.base')

@push('styles_head')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-datetimepicker.min.css') }}">
@endpush
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
<style>

    body{

        overflow-y: scroll !important;

    }

</style>
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
                    'count' => $contracts['all']->count(),
                    'id' => ($contracts['all']->count() > 0) ? $contracts['one']->getKey() : 0,
                    'inactive' => ($contracts['all']->count() > 0) ? $contracts['one']->RegistroInactivo : 0,
                    'closed' => ($contracts['all']->count() > 0) ? $contracts['one']->RegistroCerrado : 0
                    ]
                    ]
                    )
                </div>
                <div class="clearfix"></div>
                {{--
                Content Info
                Se incluye la estructura donde se muestra la información del registro
                --}}
                @include('panel.constructionwork.contract.shared.content-info')
            </div>
        </div>
    </div>
</div>
{{--
Modals
Se incluyen los modales que se requieren para el registro
--}}
@if (($contracts['all']->count() > 0))
@include('panel.constructionwork.contract.shared.filter-modal')
@include('panel.constructionwork.contract.shared.relation-modals')
@include('panel.constructionwork.contract.shared.modal-deliverables')
@include('shared.record-closed-modal')
@include('shared.record-opened-modal')
@include('shared.record-delete-modal', [
'id' => $contracts['one']->getKey(),
'work' => $works['one']->tbObraID,
])
@include('shared.record-restore-modal', [
'id' => $contracts['one']->getKey(),
'work' => $works['one']->tbObraID,
])
@include('shared.comment-modal', [
'model' => $contracts['one']
])
<input type="hidden" name="_recordId" value="{{ $contracts['one']->getKey() }}">
@endif
@endsection
@push('scripts_footer')
<script src="{{ asset('assets/js/moment.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
<script>
    (function() {
        var app = new App();
        app.initItemsList();
        app.filterModal();
        app.tooltip();
        app.highlightSearch();
        app.onPageTab();
        app.commentModal();
        app.dateTimePickerField('#cto34_deliveryDateContainer');
        app.dateTimePickerField('#cto34_saveDeliveryDateContainer');

        $('#cto34_saveDeliveryDateContainer').find('button, .fa-calendar').on('click', function() {

            var input = $('#cto34_saveDeliveryDateText');

            if (input.val().length > 0) {
                $('#modalSaveContractDeliverables').find('input[name="cto34_status"]').val('Entregado');
            } else {
                $('#modalSaveContractDeliverables').find('input[name="cto34_status"]').val('Pendiente');
            }

        });

        $('#cto34_saveDeliveryDateText').blur(function() {

            var input = $(this);

            if (input.val().length > 0) {
                $('#modalSaveContractDeliverables').find('input[name="cto34_status"]').val('Entregado');
            } else {
                $('#modalSaveContractDeliverables').find('input[name="cto34_status"]').val('Pendiente');
            }
        });

        $('#cto34_deliveryDateContainer').find('button, .fa-calendar').on('click', function() {

            var input = $('#cto34_deliveryDateText');

            if (input.val().length > 0) {
                $('#modalShowContractDeliverables').find('input[name="cto34_status"]').val('Entregado');
            } else {
                $('#modalShowContractDeliverables').find('input[name="cto34_status"]').val('Pendiente');
            }

        });

        $('#cto34_deliveryDateText').blur(function() {

            var input = $(this);

            if (input.val().length > 0) {
                $('#modalShowContractDeliverables').find('input[name="cto34_status"]').val('Entregado');
            } else {
                $('#modalShowContractDeliverables').find('input[name="cto34_status"]').val('Pendiente');
            }
        });

        app.closeRecord({
            url: "{{ url('/ajax/action/records/closed') }}",
            table: "{{  ($contracts['all']->count() > 0) ? $contracts['one']->getTable() : '' }}",
            tableId: "{{ ($contracts['all']->count() > 0) ? $contracts['one']->getKeyName() : '' }}"
        });

        app.relationModal('business', {
            info: {
                element: '#showBusinessModal',
                url: '{{ url('ajax/search/businessWork/one') }}'
            },
            save: {
                element: '#saveBusinessModal'
            }
        });

        $(document).on('info:request.rm.business', function(event) {

            var modal = event.modal;
            var response = event.response;
            var businessAll = response.businessAll;

            if (businessAll !== null) {

                // Mostramos los datos como texto
                $('#businessAlias').html(ifNull(businessAll.business[0], 'EmpresaAlias'));
                $('#businessLegalName').html(ifNull(businessAll.business[0], 'EmpresaRazonSocial'));
                $('#businessComercial').html(ifNull(businessAll.business[0], 'EmpresaNombreComercial'));
                $('#businessGroup').html(ifNull(businessAll.group, 'DirGrupoNombre'));
                $('#businessScope').html(ifNull(businessAll.DirEmpresaObraAlcance));
                $('#businessDependency').html(ifNull(businessAll.business[0], 'EmpresaDependencia'));
                $('#businessSpeciality').html(ifNull(businessAll.business[0], 'EmpresaEspecialidad'));
                $('#businessType').html(ifNull(businessAll.business[0], 'EmpresaTipoPersona'));
                $('#buinessSlogan').html(ifNull(businessAll.business[0], 'EmpresaSlogan'));
                $('#businessWeb').html(ifNull(businessAll.business[0], 'EmpresaPaginaWeb'));
                $('#businessSector').html(ifNull(businessAll.business[0], 'EmpresaSector'));
                $('#businessRFC').html(ifNull(businessAll.business[0], 'EmpresaRFC'));
                $('#businessIMSS').html(ifNull(businessAll.business[0], 'EmpresaIMSSNumero'));
                $('#businessInfonavit').html(ifNull(businessAll.business[0], 'EmpresaINFONAVITNumero'));

                // Agregamos los datos como valores
                modal.find('input[name="cto34_alias"]').val(ifNull(businessAll.business[0], 'EmpresaAlias', ''));
                modal.find('input[name="cto34_legalName"]').val(ifNull(businessAll.business[0], 'EmpresaRazonSocial', ''));
                modal.find('input[name="cto34_commercialName"]').val(ifNull(businessAll.business[0], 'EmpresaNombreComercial', ''));
                modal.find('select[name="cto34_groups"]').val(ifNull(businessAll.tbDirGrupoID_DirEmpresaObra, '', ''));
                modal.find('input[name="cto34_scope"]').val(ifNull(businessAll.DirEmpresaObraAlcance, '', ''));
                modal.find('input[name="cto34_dependency"]').val(ifNull(businessAll.business[0], 'EmpresaDependencia', ''));
                modal.find('input[name="cto34_especiality"]').val(ifNull(businessAll.business[0], 'EmpresaEspecialidad', ''));
                modal.find('select[name="cto34_type"]').val(ifNull(businessAll.business[0], 'EmpresaTipoPersona', ''));
                modal.find('input[name="cto34_slogan"]').val(ifNull(businessAll.business[0], 'EmpresaSlogan', ''));
                modal.find('input[name="cto34_website"]').val(ifNull(businessAll.business[0], 'EmpresaPaginaWeb', ''));
                modal.find('select[name="cto34_sector"]').val(ifNull(businessAll.business[0], 'EmpresaSector', ''));
                modal.find('input[name="cto34_legalId"]').val(ifNull(businessAll.business[0], 'EmpresaRFC', ''));
                modal.find('input[name="cto34_imssNum"]').val(ifNull(businessAll.business[0], 'EmpresaIMSSNumero', ''));
                modal.find('input[name="cto34_infonavitNum"]').val(ifNull(businessAll.business[0], 'EmpresaINFONAVITNumero', ''));
            }

        });

        app.relationModal('person', {
            info: {
                element: '#showPersonModal',
                url: '{{ url('ajax/search/personsWork/one') }}'
            },
            save: {
                element: '#savePersonModal'
            }
        });

        $(document).on('info:request.rm.person', function(event) {

            var modal = event.modal;
            var response = event.response;
            var personWork = response.personWork;
            console.log(event.response);

            if (personWork !== null) {

                $('#personAlias').html(ifNull(personWork.persons[0], 'PersonaAlias'));
                $('#personDirectName').html(ifNull(personWork.persons[0], 'PersonaNombreDirecto'));
                $('#personFullName').html(ifNull(personWork.persons[0], 'PersonaNombreCompleto'));
                $('#personCategory').html(ifNull(personWork.DirPersonaObraEmpresaCategoria));
                $('#personWorkJob').html(ifNull(personWork.DirPersonaObraEmpresaCargoEnLaObra));
                $('#personGender').html(ifNull(personWork.persons[0], 'PersonaGenero'));
                $('#personPrefix').html(ifNull(personWork.persons[0], 'PersonaPrefijo'));
                $('#personName').html(ifNull(personWork.persons[0], 'PersonaNombres'));
                $('#personLastName1').html(ifNull(personWork.persons[0], 'PersonaApellidoPaterno'));
                $('#personLastName2').html(ifNull(personWork.persons[0], 'PersonaApellidoMaterno'));
                $('#personHello').html(ifNull(personWork.persons[0], 'PersonaSaludo'));
                $('#personBirthday').html(ifNull(personWork.persons[0], 'PersonaFechaNacimiento'));
                $('#personCardID').html(ifNull(personWork.persons[0], 'PersonaIdentificacionTipo'));
                $('#personCardNum').html(ifNull(personWork.persons[0], 'PersonaIdentificacionNumero'));
                $('#personEmergency').html(ifNull(personWork.persons[0], 'PersonaContactoEmergencia'));
                $('#personRegisteredAt').html(ifNull(personWork.persons[0], 'PersonaFechaAlta'));
                $('#personDeletedAt').html(ifNull(personWork.persons[0], 'PersonaFechaBaja'));

            }
        });


        app.relationModal('deliverables', {
            info: {
                element: '#modalShowContractDeliverables',
                url: '{{ url('ajax/action/contract/deliverables/one') }}'
            },
            save: {
                element: '#modalSaveContractDeliverables'
            }
        });

        $(document).on('info:request.rm.deliverables', function(event) {

            var modal = event.modal;
            var response = event.response;
            var deliverable = response.deliverable;

            if (deliverable !== null) {

                var dateHuman = moment(deliverable.ContratoEntregableFechaEntregado).locale('es').format('dddd DD [de] MMMM [del] YYYY');

                $('#deliverableTitle').html(ifNull(deliverable.ContratoEntregableNombre));
                $('#deliverableTime').html(ifNull(deliverable.ContratoEntregablePlazo));
                $('#deliverableDescription').html(ifNull(deliverable.ContratoEntregableDescripcion));
                $('#deliverableStatus').html(ifNull(deliverable.ContratoEntregableStatus));
                $('#deliverableDate').html(ifNull(dateHuman));

                modal.find('input[name="cto34_title"]').val(ifNull(deliverable.ContratoEntregableNombre, '', ''));
                modal.find('input[name="cto34_time"]').val(ifNull(deliverable.ContratoEntregablePlazo, '', ''));
                modal.find('textarea[name="cto34_description"]').val(ifNull(deliverable.ContratoEntregableDescripcion, '', ''));
                modal.find('input[name="cto34_status"]').val(ifNull(deliverable.ContratoEntregableStatus, '', ''));
                modal.find('input[name="cto34_deliveryDate"]').val(deliverable.ContratoEntregableFechaEntregado);
                modal.find('input[name="cto34_deliveryDateText"]').val(dateHuman);
                modal.find('input[name="cto34_id"]').val(deliverable.tbContratoEntregableID);

            }
        });

        $('.btn-today').on('click', function() {

            var dateHuman = moment().locale('es').format('dddd DD [de] MMMM [del] YYYY');
            var date = moment().format('YYYY-MM-DD');

            $(this).parent().parent().find('.date-formated').focus();
            $(this).parent().parent().find('.date-formated').val(dateHuman);
            $(this).parent().parent().find('input[type="hidden"]').val(date);
            $(this).parent().parent().find('.date-formated').blur();
        });
    })();

</script>
@endpush