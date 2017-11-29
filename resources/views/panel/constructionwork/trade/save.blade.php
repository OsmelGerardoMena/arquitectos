@extends('layouts.base')
@push('styles_head')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}">
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
    @include('shared.alerts', ['errors' => $errors])
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

                        --}}
                        @include('shared.nav-actions-save')
                    </div>
                    <div class="clearfix"></div>
                    <div class="panel-body padding-top--clear">
                        <div class="list-group col-sm-5 col-md-4 col-lg-3 margin-clear panel-item" style="position: relative;">
                            <div class="list-group-item padding-clear padding-bottom--5">
                                {{--
                                    Content Search
                                    Se incluyen la forma para la busqueda y filtrado de datos
                                --}}
                                @include('panel.constructionwork.trade.shared.content-search')
                            </div>
                            <div id="itemsList">
                                <div class="list-group-item active">
                                    <h4 class="list-group-item-heading">
                                        Nuevo <span class="fa fa-caret-right fa-fw"></span>
                                    </h4>
                                    <p class="small">
                                        {{ Carbon\Carbon::now()->formatLocalized('%d %B %Y') }}
                                    </p>
                                </div>
                                @foreach ($trades['all'] as $index => $trade)
                                    <a id="item{{ $trade->tbOficioID }}" href="{{ $navigation['base'] }}/info/{{ $trade->tbOficioID  }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="list-group-item">
                                        <h4 class="list-group-item-heading">{{ $trade->OficioFolio }}</h4>
                                        <p class="text-muted small">
                                            {{ Carbon\Carbon::parse($trade->OficioFechaExpedicion)->formatLocalized('%A %d %B %Y') }}
                                        </p>
                                    </a>
                                @endforeach
                            </div>
                            <div class="list-group-item padding-clear padding-top--10">
                                <div class="row">
                                    <div class="col-sm-12 text-center">
                                        {{--
                                            Pagination
                                            Se muestra datos y botones de paginación
                                         --}}
                                        @include('shared.pagination')
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-7 col-md-8 col-lg-9 panel-item padding-left--clear padding-right--clear" style="position: relative;">
                            <div class="col-sm-12 margin-bottom--5">
                                <ul class="nav nav-tabs nav-tabs-works" role="tablist">
                                    <li role="presentation" class="active">
                                        <a href="#general" aria-controls="general" role="tab" data-toggle="tab" data-type="own">
                                            General
                                        </a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#paragraphs" aria-controls="general" role="tab" data-toggle="tab" data-type="own">
                                            Redacción
                                        </a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#details" aria-controls="general" role="tab" data-toggle="tab" data-type="own">
                                            Detalles
                                        </a>
                                    </li>
                                    <li role="presentation" class="disabled">
                                        <a href="#">
                                            Envios
                                        </a>
                                    </li>
                                    <li role="presentation" class="disabled">
                                        <a href="#">
                                            Pendientes
                                        </a>
                                    </li>
                                    <li role="presentation" class="disabled">
                                        <a>
                                            Comentarios
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <form id="saveForm" action="{{ $navigation['base'].'/action/save' }}" method="post" accept-charset="utf-8">
                                <div class="tab-content col-sm-12 margin-bottom--20">
                                    <div role="tabpanel" class="tab-pane active row padding-top--5" id="general">
                                        <div class="form-group col-sm-6">
                                            <label class="form-label-full">Folio</label>
                                            <input type="text" class="form-control input-sm" placeholder="{{ $works['one']->ObraClave }}-Oficio-0000" disabled>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_location" class="form-label-full">Localidad de expedición</label>
                                            <select name="cto34_location" id="cto34_location" class="form-control input-sm">
                                                <option value="">Seleccionar opción</option>
                                                @foreach($locations['all'] as $option)
                                                    <option value="{{ $option->tbLocalidadID }}">{{ $option->LocalidadAlias}}</option>
                                                @endforeach
                                            </select>
                                        <!--<input id="cto34_location"
                                                   name="cto34_location"
                                                   type="text"
                                                   value="{{ old('cto34_location') }}"
                                                   class="form-control form-control-plain input-sm">-->
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_expeditionDateText" class="form-label-full">Fecha de expedición</label>
                                            <div id="cto34_expeditionDateContainer" class="input-group input-group-sm date-field">
                                                <input id="cto34_expeditionDateText"
                                                       name="cto34_expeditionDateText"
                                                       type="text"
                                                       value="{{ old('cto34_expeditionDateText') }}"
                                                       readonly="readonly"
                                                       style="background:#fff;"
                                                       class="form-control form-control-plain input-sm date-formated">
                                                <input name="cto34_expeditionDate" type="hidden" value="{{ old('cto34_expeditionDate') }}">
                                                <span class="input-group-addon" style="background-color: #fff">
                                                    <span class="fa fa-calendar fa-fw text-primary"></span>
                                                </span>
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-default btn-date-minus">
                                                        <span class="fa fa-minus fa-fw"></span>
                                                    </button>
                                                    <button type="button" class="btn btn-default btn-today">Hoy</button>
                                                    <button type="button" class="btn btn-default btn-date-plus">
                                                        <span class="fa fa-plus fa-fw"></span>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_destinationPerson" class="form-label-full">Persona destinataria</label>
                                            <select id="cto34_destinationPerson"
                                                    name="cto34_destinationPerson"
                                                    data-live-search="true"
                                                    data-width="100%"
                                                    data-style="btn-sm btn-default"
                                                    data-modal-title="Persona destinataria"
                                                    class="selectpicker with-ajax">
                                                @if(!empty(old('cto34_destinationPerson')))
                                                    <option value="{{ old('cto34_destinationPerson') }}" selected="selected">
                                                        {{ old('cto34_destinationPersonName')  }}
                                                    </option>
                                                @endif
                                            </select>
                                            <input type="hidden" id="cto34_destinationPersonName" name="cto34_destinationPersonName" value="{{ old('cto34_destinationPersonName')  }}">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_destinationBusiness" class="form-label-full">Empresa destinataria</label>
                                            <select id="cto34_destinationBusiness"
                                                    name="cto34_destinationBusiness"
                                                    data-live-search="true"
                                                    data-width="100%"
                                                    data-style="btn-sm btn-default"
                                                    data-modal-title="Empresa destinataria"
                                                    class="selectpicker with-ajax">
                                                @if(!empty(old('cto34_destinationBusiness')))
                                                    <option value="{{ old('cto34_destinationBusiness') }}" selected="selected">
                                                        {{ old('cto34_destinationBusinessName')  }}
                                                    </option>
                                                @endif
                                            </select>
                                            <input type="hidden" id="cto34_destinationBusinessName" name="cto34_destinationBusinessName" value="{{ old('cto34_destinationBusinessName')  }}">
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_sender" class="form-label-full">Remitente</label>
                                            <select id="cto34_sender"
                                                    name="cto34_sender"
                                                    data-live-search="true"
                                                    data-width="100%"
                                                    data-style="btn-sm btn-default"
                                                    data-modal-title="Remitente"
                                                    class="selectpicker with-ajax">
                                                @if(!empty(old('cto34_sender')))
                                                    <option value="{{ old('cto34_sender') }}" selected="selected">
                                                        {{ old('cto34_senderName')  }}
                                                    </option>
                                                @endif
                                            </select>
                                            <input type="hidden" id="cto34_senderName" name="cto34_senderName" value="{{ old('cto34_senderName')  }}">
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane row padding-top--5" id="paragraphs">
                                        <div class="form-group col-sm-12">
                                            <label for="cto34_subject" class="form-label-full">Asunto </label>
                                            <textarea id="cto34_subject"
                                                      name="cto34_subject"
                                                      rows="2"
                                                      class="form-control form-control-plain"></textarea>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="cto34_goodbye" class="form-label-full">Saludo</label>
                                            <textarea id="cto34_goodbye"
                                                      name="cto34_welcome"
                                                      rows="3" maxlength="4000"
                                                      class="form-control form-control-plain"></textarea>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="cto34_paragraphs1" class="form-label-full">Cuerpo del oficio</label>
                                            <textarea id="cto34_paragraphs1"
                                                      name="cto34_paragraphs1"
                                                      rows="6"
                                                      class="form-control form-control-plain"></textarea>
                                        </div>                                                                            
                                    </div>
                                    <div role="tabpanel" class="tab-pane row padding-top--5" id="details">
                                        <div class="form-group col-sm-12">
                                            <label for="cto34_attachments" class="form-label-full">Adjuntos</label>
                                            <textarea id="cto34_attachments"
                                                      name="cto34_attachments"
                                                      rows="3"
                                                      class="form-control form-control-plain"></textarea>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="cto34_copy" class="form-label-full">Con copia para</label>
                                            <textarea id="cto34_copy"
                                                      name="cto34_copy"
                                                      rows="3"
                                                      class="form-control form-control-plain"></textarea>
                                        </div>
                                        <!--<div class="form-group col-sm-6">
                                            <label for="cto34_receiver" class="form-label-full">Recibido por</label>
                                            <select id="cto34_receiver"
                                                    name="cto34_receiver"
                                                    data-live-search="true"
                                                    data-width="100%"
                                                    data-style="btn-sm btn-default"
                                                    data-modal-title="Remitente"
                                                    class="selectpicker with-ajax">
                                                @if(!empty(old('cto34_receiver')))
                                                    <option value="{{ old('cto34_receiver') }}" selected="selected">
                                                        {{ old('cto34_receiverName')  }}
                                                    </option>
                                                @endif
                                            </select>
                                            <input type="hidden" id="cto34_receiverName" name="cto34_receiverName" value="{{ old('cto34_receiverName')  }}">
                                        </div>-->
                                        <!--<div class="form-group col-sm-6">
                                            <label for="cto34_receiverDateText" class="form-label-full">Recibido fecha</label>
                                            <div class="input-group input-group-sm date-field">
                                                <input id="cto34_receiverDateText"
                                                       name="cto34_receiverDateText"
                                                       type="text"
                                                       value="{{ old('cto34_receiverDateText') }}"
                                                       readonly="readonly"
                                                       class="form-control form-control-plain input-sm date-formated">
                                                <input name="cto34_receiverDate" type="hidden" value="{{ old('cto34_receiverDate') }}">
                                                <span class="input-group-addon" style="background-color: #fff">
                                                    <span class="fa fa-calendar fa-fw text-primary"></span>
                                                </span>
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-default btn-today">Hoy</button>
                                                </span>
                                            </div>
                                        </div>-->
                                        <!--<div class="form-group col-sm-12">
                                            <label>
                                                Seguimiento <input type="checkbox" name="cto34_follow" id="cto34_follow" value="1">
                                            </label>
                                        </div>-->
                                        <!--<div class="form-group col-sm-6">
                                            <label for="cto34_subjectCloseDateText" class="form-label-full">Asunto cerrado fecha</label>
                                            <div class="input-group input-group-sm date-field">
                                                <input id="cto34_subjectCloseDateText"
                                                       name="cto34_subjectCloseDateText"
                                                       type="text"
                                                       value="{{ old('cto34_subjectCloseDateText') }}"
                                                       readonly="readonly"
                                                       class="form-control form-control-plain input-sm date-formated">
                                                <input name="cto34_subjectCloseDate" type="hidden" value="{{ old('cto34_subjectCloseDate') }}">
                                                <span class="input-group-addon" style="background-color: #fff">
                                                    <span class="fa fa-calendar fa-fw text-primary"></span>
                                                </span>
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-default btn-today">Hoy</button>
                                                </span>
                                            </div>
                                        </div>-->
                                        <div class="clearfix"></div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_personDestination" class="form-label-full">Capturado por</label>
                                            <input type="text" value="{{ Auth::user()['person']->PersonaNombreDirecto }}" class="form-control input-sm" disabled>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label class="form-label-full">Registro creado timestamp</label>
                                            <input type="text" value="{{ Carbon\Carbon::now()->formatLocalized('%A %d %B %Y') }}" class="form-control input-sm" disabled>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_personDestination" class="form-label-full">Modificado por</label>
                                            <input type="text" class="form-control input-sm" disabled>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label class="form-label-full">Registro modificado timestamp</label>
                                            <input type="text" class="form-control input-sm" disabled>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                                <input type="hidden" name="_hashtag" value="">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Guarda los errores para marcar los campos con errores --}}
    <input type="hidden" name="_errors" value="{{ json_encode($errors->messages()) }}">
    {{--
        Modals
        Se incluyen los modales que se requieren para el registro
    --}}
    @include('panel.constructionwork.trade.shared.filter-modal')
@endsection
@push('scripts_footer')
    <script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/js/moment.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/constructionWork.js') }}"></script>
    <script src="{{ asset('assets/js/person.js') }}"></script>
    <script src="{{ asset('assets/js/business.js') }}"></script>
    <script>
        (function() {

            var app = new App();
            app.preventClose();
            app.formErrors('#saveForm');
            app.initItemsList();
            app.animateSubmit("saveForm", "addSubmitButton");
            app.tooltip();
            app.filterModal();
            app.dateTimePickerField('#cto34_expeditionDateContainer');
            app.onPageTab();

            var work = new ConstructionWork();
            var person = new Person();
            var business = new Business();
            /**
             *  Hacemos la busqueda para Firma por el cliente
             */
            person.searchInMyBusinessWithAjax('#cto34_destinationPerson',
                {
                    url: '{{ url("ajax/search/personsMyBusiness") }}',
                    token: '{{ csrf_token() }}',
                    optionClass: 'option-newPersonAutor',
                    optionListClass: 'option-personAutor',
                    canAdd: false

                }, function(data) {

                    if (data.action === 'newClicked') {
                        //beforeShowPersonModal(data.element);
                    }

                    if (data.action === 'optionClicked') {
                        searchedOptionSelected(data.element);
                    }
                }
            );

            /**
             *  Hacemos la busqueda para Firma por el cliente
             */
            person.searchInMyBusinessWithAjax('#cto34_sender',
                {
                    url: '{{ url("ajax/search/personsMyBusiness") }}',
                    token: '{{ csrf_token() }}',
                    optionClass: 'option-newSender',
                    optionListClass: 'option-sender',
                    canAdd: false

                }, function(data) {

                    if (data.action === 'newClicked') {
                        //beforeShowPersonModal(data.element);
                    }

                    if (data.action === 'optionClicked') {
                        searchedOptionSelected(data.element);
                    }
                }
            );

            /**
             *  Hacemos la busqueda para Firma por el cliente
             */
            person.searchInMyBusinessWithAjax('#cto34_receiver',
                {
                    url: '{{ url("ajax/search/personsMyBusiness") }}',
                    token: '{{ csrf_token() }}',
                    optionClass: 'option-newReceiver',
                    optionListClass: 'option-receiver',
                    canAdd: false

                }, function(data) {

                    if (data.action === 'newClicked') {
                        //beforeShowPersonModal(data.element);
                    }

                    if (data.action === 'optionClicked') {
                        searchedOptionSelected(data.element);
                    }
                }
            );

            /**
             *  Hacemos la busqueda para Cliente directo
             */
            business.searchInMyBusinessWithAjax('#cto34_destinationBusiness',
                {
                    url: '{{ url("ajax/search/businessMyBusiness") }}',
                    token: '{{ csrf_token() }}',
                    optionClass: 'option-newDirectCustomer',
                    optionListClass: 'option-directCustomer',
                    canAdd: false

                }, function(data) {

                    if (data.action === 'newClicked') {
                        //beforeShowBusinessModal(data.element);
                    }

                    if (data.action === 'optionClicked') {
                        searchedOptionSelected(data.element);
                    }
                }
            );

            $('.btn-today').on('click', function() {

                var dateHuman = moment().locale('es').format('dddd DD [de] MMMM [del] YYYY');
                var date = moment().format('YYYY-MM-DD');

                $(this).parent().parent().find('.date-formated').focus();
                $(this).parent().parent().find('.date-formated').val(dateHuman);
                $(this).parent().parent().find('input[type="hidden"]').val(date);
                $(this).parent().parent().find('.date-formated').blur();
            });

        })();

        function searchedOptionSelected(element) {
            var name = $(element).find('option:selected').text();
            $(element + 'Name').val(name);
        }

        function contractOptionSelected(element) {
            var name = $(element).find('option:selected').text();
            $(element + 'Name').val(name);
        }
    </script>
@endpush