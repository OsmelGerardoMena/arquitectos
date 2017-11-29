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
                                @include('panel.constructionwork.binnacle.shared.content-search')
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
                                @foreach ($binnacles['all'] as $index => $binnacle)
                                    <a id="item{{ $binnacle->tbBitacoraID }}" href="{{ $navigation['base'] }}/info/{{ $binnacle->tbBitacoraID  }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="list-group-item">
                                        <h4 class="list-group-item-heading">{{ $binnacle->BitacoraNumero }}</h4>
                                        <p class="text-muted small">
                                            {{ $binnacle->contract->ContratoAlias }}
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
                                        <a href="#general" aria-controls="general" role="tab" data-toggle="tab">
                                            General
                                        </a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#details" aria-controls="general" role="tab" data-toggle="tab">
                                            Redacción
                                        </a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#registers" aria-controls="general" role="tab" data-toggle="tab">
                                            Detalles
                                        </a>
                                    </li>
                                    <li role="presentation" class="disabled">
                                        <a href="#">
                                            Pendientes
                                        </a>
                                    </li>
                                    <li role="presentation" class="disabled">
                                        <a href="#">
                                            Comentarios
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <form id="saveForm" action="{{ $navigation['base'].'/action/save' }}" method="post" accept-charset="utf-8">
                                <div class="tab-content col-sm-12 margin-bottom--20">
                                    <div role="tabpanel" class="tab-pane active row padding-top--5" id="general">
                                        <div class="form-group col-sm-12">
                                            <label for="cto34_contract" class="form-label-full">Contrato</label>
                                            <select id="cto34_contract"
                                                    name="cto34_contract"
                                                    data-live-search="true"
                                                    data-width="100%"
                                                    data-style="btn-sm btn-default"
                                                    data-modal-title="Contrato"
                                                    class="selectpicker with-ajax">
                                                @if(!empty(old('cto34_contract')))
                                                    <option value="{{ old('cto34_contract') }}" selected="selected">
                                                        {{ old('cto34_contractName')  }}
                                                    </option>
                                                @endif
                                            </select>
                                            <input type="hidden" id="cto34_contractName" name="cto34_contractName" value="{{ old('cto34_contractName')  }}">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_number" class="form-label-full">Nota Número</label>
                                            <input id="cto34_number"
                                                   name="cto34_number"
                                                   type="text"
                                                   value="{{ old('cto34_number') }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div id="cto34_noteDateContainer" class="form-group col-sm-6">
                                            <label for="cto34_noteDateText" class="form-label-full">Fecha de ésta nota</label>
                                            <div class="input-group input-group-sm date-field">
                                                <input id="cto34_noteDateText"
                                                       name="cto34_noteDateText"
                                                       type="text"
                                                       value="{{ old('cto34_noteDateText') }}"
                                                       readonly="readonly"
                                                       class="form-control form-control-plain input-sm date-formated">
                                                <input name="cto34_noteDate" type="hidden" value="{{ old('cto34_noteDate') }}">
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
                                            <label for="cto34_type" class="form-label-full">Original o respuesta</label>
                                            <select name="cto34_type" id="cto34_type" class="form-control input-sm">
                                                <option value="">Seleccionar opción</option>
                                                @foreach(binnacle_note_type_options() as $option)
                                                    <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_binnacle" class="form-label-full">Nota antecedente</label>
                                            <select name="cto34_binnacle" id="cto34_binnacle" class="form-control input-sm" disabled>
                                                <option value="">Seleccionar opción</option>
                                            </select>
                                        </div>
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                                    </div>
                                    <div role="tabpanel" class="tab-pane row padding-top--5" id="details">
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <div class="form-group col-sm-6">
                                                    <label for="cto34_group" class="form-label-full">Grupo</label>
                                                    <select name="cto34_group" id="cto34_group" class="form-control input-sm">
                                                        <option value="">Seleccionar opción</option>
                                                        @foreach(binnacle_groups_options() as $option)
                                                            <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="cto34_destination" class="form-label-full">Destino</label>
                                                    <select name="cto34_destination" id="cto34_destination" class="form-control input-sm">
                                                        <option value="">Seleccionar opción</option>
                                                        @foreach(binnacle_destination_options() as $option)
                                                            <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label for="cto34_description" class="form-label-full">Descripción</label>
                                                    <textarea id="cto34_description"
                                                              name="cto34_description"
                                                              rows="2"
                                                              maxlength="4000"
                                                              class="form-control form-control-plain">{{ old('cto34_description') }}</textarea>
                                                    <!--<p class="form-count small text-muted"><span class="form-counter">0</span>/4000</p>-->
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label for="cto34_location" class="form-label-full">Ubicación</label>
                                                    <textarea id="cto34_location"
                                                              name="cto34_location"
                                                              rows="2"
                                                              maxlength="4000"
                                                              class="form-control form-control-plain">{{ old('cto34_location') }}</textarea>
                                                    <!--<p class="form-count small text-muted"><span class="form-counter">0</span>/4000</p>-->
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label for="cto34_reasons" class="form-label-full">Causas</label>
                                                    <textarea id="cto34_reasons"
                                                              name="cto34_reasons"
                                                              rows="2"
                                                              maxlength="4000"
                                                              class="form-control form-control-plain">{{ old('cto34_reasons') }}</textarea>
                                                    <!--<p class="form-count small text-muted"><span class="form-counter">0</span>/4000</p>-->
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label for="cto34_solution" class="form-label-full">Solución</label>
                                                    <textarea id="cto34_solution"
                                                              name="cto34_solution"
                                                              rows="2"
                                                              maxlength="4000"
                                                              class="form-control form-control-plain">{{ old('cto34_solution') }}</textarea>
                                                    <!--<p class="form-count small text-muted"><span class="form-counter">0</span>/4000</p>-->
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label for="cto34_term" class="form-label-full">Plazo descripción</label>
                                                    <input id="cto34_term"
                                                           name="cto34_term"
                                                           type="text"
                                                           value="{{ old('cto34_term') }}"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <div id="cto34_compromiseDateContainer" class="form-group col-sm-12">
                                                    <label for="cto34_compromiseDateText" class="form-label-full">Fecha compromiso</label>
                                                    <div class="input-group input-group-sm date-field">
                                                        <input id="cto34_compromiseDateText"
                                                               name="cto34_compromiseDateText"
                                                               type="text"
                                                               value="{{ old('cto34_compromiseDateText') }}"
                                                               readonly="readonly"
                                                               class="form-control form-control-plain input-sm date-formated">
                                                        <input name="cto34_compromiseDate" type="hidden" value="{{ old('cto34_compromiseDate') }}">
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
                                                <div id="cto34_accomplishmentDateContainer" class="form-group col-sm-12">
                                                    <label for="cto34_accomplishmentDateText" class="form-label-full">Fecha cumplimiento</label>
                                                    <div class="input-group input-group-sm date-field">
                                                        <input id="cto34_accomplishmentDateText"
                                                               name="cto34_accomplishmentDateText"
                                                               type="text"
                                                               value="{{ old('cto34_accomplishmentDateText') }}"
                                                               readonly="readonly"
                                                               class="form-control form-control-plain input-sm date-formated">
                                                        <input name="cto34_accomplishmentDate" type="hidden" value="{{ old('cto34_accomplishmentDate') }}">
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
                                                <div class="form-group col-sm-12">
                                                    <label for="cto34_prevention" class="form-label-full">Prevención</label>
                                                    <textarea id="cto34_prevention"
                                                              name="cto34_prevention"
                                                              rows="2"
                                                              maxlength="4000"
                                                              class="form-control form-control-plain">{{ old('cto34_prevention') }}</textarea>
                                                    <!--<p class="form-count small text-muted"><span class="form-counter">0</span>/4000</p>-->
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label for="cto34_cost" class="form-label-full">Costo</label>
                                                    <textarea id="cto34_cost"
                                                              name="cto34_cost"
                                                              rows="2"
                                                              maxlength="4000"
                                                              class="form-control form-control-plain">{{ old('cto34_cost') }}</textarea>
                                                    <!--<p class="form-count small text-muted"><span class="form-counter">0</span>/4000</p>-->
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label for="cto34_sanctions" class="form-label-full">Sanciones</label>
                                                    <textarea id="cto34_sanctions"
                                                              name="cto34_sanctions"
                                                              rows="2"
                                                              maxlength="4000"
                                                              class="form-control form-control-plain">{{ old('cto34_sanctions') }}</textarea>
                                                    <!--<p class="form-count small text-muted"><span class="form-counter">0</span>/4000</p>-->
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label for="cto34_annexed" class="form-label-full">Anexos</label>
                                                    <textarea id="cto34_annexed"
                                                              name="cto34_annexed"
                                                              rows="2"
                                                              maxlength="4000"
                                                              class="form-control form-control-plain">{{ old('cto34_annexed') }}</textarea>
                                                    <!--<p class="form-count small text-muted"><span class="form-counter">0</span>/4000</p>-->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <div class="form-group col-sm-12">
                                                    <label class="form-label-full">Borrador</label>
                                                    <input class="form-control input-sm margin-bottom--10" disabled>
                                                    <input class="form-control input-sm margin-bottom--10" disabled>
                                                    <textarea  rows="35" class="form-control" disabled></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane row padding-top--5" id="registers">
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_personAutor" class="form-label-full">Autor</label>
                                            <select id="cto34_personAutor"
                                                    name="cto34_personAutor"
                                                    data-live-search="true"
                                                    data-width="100%"
                                                    data-style="btn-sm btn-default"
                                                    data-modal-title="Autor"
                                                    class="selectpicker with-ajax">
                                                @if(!empty(old('cto34_personAutor')))
                                                    <option value="{{ old('cto34_personAutor') }}" selected="selected">
                                                        {{ old('cto34_personAutorName')  }}
                                                    </option>
                                                @endif
                                            </select>
                                            <input type="hidden" id="cto34_personAutorName" name="cto34_personAutorName" value="{{ old('cto34_personAutorName')  }}">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_auth" class="form-label-full">Nota autorizada por</label>
                                            <select name="cto34_auth" id="cto34_auth" class="form-control input-sm">
                                                <option value="">Seleccionar opción</option>
                                                @foreach($users['all'] as $option)
                                                    <option value="{{ $option->getKey() }}">{{ $option->CTOUsuarioNombre }} - {{ $option->person->PersonaNombreCompleto }}</option>
                                                @endforeach
                                            </select>
                                        </div>
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
    @include('panel.constructionwork.binnacle.shared.filter-modal')
@endsection
@push('scripts_footer')
    <script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/js/moment.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/constructionWork.js') }}"></script>
    <script>
        (function() {

            var app = new App();
            app.preventClose();
            app.formErrors('#saveForm');
            app.initItemsList();
            app.animateSubmit("saveForm", "addSubmitButton");
            app.tooltip();
            app.filterModal();
            app.dateTimePickerField('#cto34_noteDateContainer');
            app.dateTimePickerField('#cto34_accomplishmentDateContainer');
            app.dateTimePickerField('#cto34_compromiseDateContainer');
            app.onPageTab();

            var work = new ConstructionWork();

            work.searchAdvanceContractWithAjax('#cto34_contract', {
                    url: '{{ url("ajax/action/search/contracts") }}',
                    token: '{{ csrf_token() }}',
                    workId: '{{ $works['one']->tbObraID }}',
                    optionClass: 'option-newContractor',
                    optionListClass: 'option-contractor',
                    canAdd: false

                }, function(data) {

                    if (data.action === 'newClicked') {
                        //beforeShowBusinessModal(data.element);
                    }

                    if (data.action === 'optionClicked') {
                        contractOptionSelected(data.element);
                    }
                }
            );

            /**
             *  Hacemos la busqueda para Firma por el cliente
             */
            work.searchPersonWithAjax('#cto34_personAutor',
                {
                    url: '{{ url("ajax/search/personsWork") }}',
                    token: '{{ csrf_token() }}',
                    workId: '{{ $works['one']->tbObraID }}',
                    optionClass: 'option-newPersonAutor',
                    optionListClass: 'option-personAutor',
                    canAdd: false

                }, function(data) {

                    if (data.action === 'newClicked') {
                        //beforeShowPersonModal(data.element);
                    }

                    if (data.action === 'optionClicked') {
                        personOptionSelected(data.element);
                    }
                }
            );

            /**
             *  Hacemos la busqueda para Firma por el cliente
             */
            work.searchPersonWithAjax('#cto34_personDestination',
                {
                    url: '{{ url("ajax/search/personsWork") }}',
                    token: '{{ csrf_token() }}',
                    workId: '{{ $works['one']->tbObraID }}',
                    optionClass: 'option-newPersonDestination',
                    optionListClass: 'option-personDestination',
                    canAdd: false

                }, function(data) {

                    if (data.action === 'newClicked') {
                        //beforeShowPersonModal(data.element);
                    }

                    if (data.action === 'optionClicked') {
                        personOptionSelected(data.element);
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

        function personOptionSelected(element) {
            var name = $(element).find('option:selected').text();
            $(element + 'Name').val(name);
        }

        function contractOptionSelected(element) {
            var name = $(element).find('option:selected').text();
            $(element + 'Name').val(name);
        }
    </script>
@endpush