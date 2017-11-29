@extends('layouts.base')

@push('styles_head')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-datetimepicker.min.css') }}">
@endpush

@section('content')
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

                            @param $model['id'] id del registro
                        --}}
                        @include('shared.nav-actions-update', [ 'model' => [ 'id' => $catalogs['one']->getKey() ]])
                    </div>
                    <div class="clearfix"></div>
                    <div class="panel-body padding-top--clear">
                        <div class="list-group col-sm-5 col-md-4 col-lg-3 margin-clear panel-item" style="position: relative;">
                            <div class="list-group-item padding-clear padding-bottom--5">
                                {{--
                                    Content Search
                                    Se incluyen la forma para la busqueda y filtrado de datos
                                --}}
                                @include('panel.constructionwork.catalog.shared.content-search')
                            </div>
                            <div id="itemsList">
                                @foreach ($catalogs['all'] as $index => $catalog)
                                    @if ($catalog->tbCatalogoID == $catalogs['one']->tbCatalogoID)
                                        <div id="item{{ $catalog->tbCatalogoID }}" class="list-group-item active">
                                            <h4 class="list-group-item-heading">{{ $catalog->CatalogoConceptoCodigo }}</h4>
                                            <p class="small">
                                                {{ $catalog->level->UbicaNivelAlias }}
                                            </p>
                                        </div>
                                        @continue
                                    @endif
                                    @if (isset($search['query']))
                                        <a id="item{{ $catalog->tbCatalogoID }}" href="{{ $navigation['base'] }}/search/{{ $catalog->tbCatalogoID  }}{{ '?'.$filter['query'] }}" class="list-group-item">
                                            <h4 class="list-group-item-heading">{{ $catalog->CatalogoConceptoCodigo }}</h4>
                                            <p class="text-muted small">
                                                {{ $catalog->level->UbicaNivelAlias }}
                                            </p>
                                        </a>
                                    @else
                                        <a id="item{{ $catalog->tbCatalogoID }}" href="{{ $navigation['base'] }}/info/{{ $catalog->tbCatalogoID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="list-group-item">
                                            <h4 class="list-group-item-heading">{{ $catalog->CatalogoConceptoCodigo }}</h4>
                                            <p class="text-muted small">
                                                {{ $catalog->level->UbicaNivelAlias }}
                                            </p>
                                        </a>
                                    @endif
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
                            <form id="saveForm" action="{{ $navigation['base'].'/action/save' }}" method="post" accept-charset="utf-8">
                                <div class="col-sm-12 margin-bottom--5">
                                    <ul class="nav nav-tabs nav-tabs-works" role="tablist">
                                        <li role="presentation" class="active">
                                            <a href="#concepts" aria-controls="concepts" role="tab" data-toggle="tab">
                                                Concepto
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a href="#general" aria-controls="general" role="tab" data-toggle="tab">
                                                Generales
                                            </a>
                                        </li>
                                        <li role="presentation" class="disabled">
                                            <a href="#">
                                                Generadores
                                            </a>
                                        </li>
                                        <li role="presentation" class="disabled">
                                            <a href="#">
                                                Estimaciones
                                            </a>
                                        </li>
                                        <li role="presentation" class="disabled">
                                            <a href="#">
                                                Comentarios
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tab-content col-sm-12 margin-bottom--20">
                                    <div role="tabpanel" class="tab-pane active row" id="concepts">
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_level" class="form-label-full">Ubicación del concepto</label>
                                            <select name="cto34_level" id="cto34_level" class="form-control input-sm">
                                                <option value="">Seleccionar opción</option>
                                                @foreach($levels['all'] as $level)
                                                    <option value="{{ $level->tbUbicaNivelID }}">{{ $level->UbicaNivelAlias }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_fullDescription" class="form-label-full margin-top--10">Descripción completa</label>
                                            <textarea id="cto34_fullDescription"
                                                      name="cto34_fullDescription"
                                                      rows="5"
                                                      maxlength="4000"
                                                      class="form-control form-control-plain">{{ ifempty($catalogs['one']->CatalogoDescripcion)  }}</textarea>
                                            <label class="form-label-full margin-top--10">Descripción corta</label>
                                            <textarea rows="3"
                                                      maxlength="4000"
                                                      class="form-control form-control-plain" disabled>{{ ifempty($catalogs['one']->CatalogoDescripcionCorta)  }}</textarea>

                                        </div>
                                        <div class="form-group col-sm-6">
                                            <div class="row">
                                                <div class="col-sm-6 margin-bottom--10">
                                                    <label for="cto34_unity" class="form-label-full">Unidad</label>
                                                    <select name="cto34_unity" id="cto34_unity" class="form-control input-sm">
                                                        <option value="0">Seleccionar opción</option>
                                                        @foreach($unities['all'] as $unity)
                                                            <option value="{{ $unity->tbUnidadID  }}">{{ $unity->UnidadAlias }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-6 margin-bottom--10">
                                                    <label for="cto34_quantity" class="form-label-full">Cantidad</label>
                                                    <input id="cto34_quantity"
                                                           name="cto34_quantity"
                                                           type="number"
                                                           value="{{ ifempty($catalogs['one']->CatalogoCantidad)  }}"
                                                           min="1"
                                                           autocomplete="off"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <div class="col-sm-6 margin-bottom--10">
                                                    <label for="cto34_unitPrice" class="form-label-full">Precio unitario</label>
                                                    <input id="cto34_unitPrice"
                                                           name="cto34_unitPrice"
                                                           type="text"
                                                           value="{{ $catalogs['one']->CatalogoPrecioUnitario }}"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <div class="col-sm-6 margin-bottom--10">
                                                    <label for="cto34_amount" class="form-label-full">Importe</label>
                                                    <input id="cto34_amount"
                                                           name="cto34_amount"
                                                           type="text"
                                                           value="{{ $catalogs['one']->CatalogoImporte }}"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="form-group col-sm-12">
                                                    <label for="cto34_folioId" class="form-label-full">Código Externo</label>
                                                    <input id="cto34_folioId"
                                                           name="cto34_folioId"
                                                           type="text"
                                                           value="{{ ifempty($catalogs['one']->CatalogoFolioExterno, '') }}"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label for="cto34_bugetDateFormated" class="form-label-full">Fecha de presupuesto</label>
                                                    <div class="input-group input-group-sm date-field">
                                                        <input id="cto34_bugetDateFormated"
                                                               name="cto34_bugetDateFormated"
                                                               type="text"
                                                               placeholder="{{ Carbon\Carbon::parse($catalogs['one']->CatalogoPresupuestoFecha)->formatLocalized('%A %d %B %Y')  }}"
                                                               readonly="readonly"
                                                               class="form-control form-control-plain input-sm date-formated">
                                                        <input name="cto34_bugetDate" type="hidden" value="{{ $catalogs['one']->CatalogoPresupuestoFecha }}">
                                                        <span class="input-group-addon" style="background-color: #fff">
                                                    <span class="fa fa-calendar fa-fw text-primary"></span>
                                                </span>
                                                        <span class="input-group-btn">
                                                    <button type="button" class="btn btn-default btn-today">Hoy</button>
                                                </span>
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label for="cto34_conceptStatus" class="form-label-full">Status del concepto</label>
                                                    <select name="cto34_conceptStatus" id="cto34_conceptStatus" class="form-control input-sm">
                                                        @foreach(catalogstatus_options() as $option)
                                                            <option value="{{ $option['value']  }}">{{ $option['text']  }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane row" id="general">
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_code" class="form-label-full">Código</label>
                                        <!--<div class="input-group input-group-sm">
                                                <span class="input-group-addon" id="basic-addon1">CAT</span>
                                                <input id="cto34_code"
                                                       name="cto34_code"
                                                       type="number"
                                                       min="0"
                                                       autocomplete="off"
                                                       value="{{ old('cto34_code') }}"
                                                       class="form-control form-control-plain input-sm">
                                            </div>-->
                                            <input id="cto34_code"
                                                   name="cto34_code"
                                                   type="text"
                                                   autocomplete="off"
                                                   value="{{ ifempty($catalogs['one']->CatalogoConceptoCodigo)  }}"
                                                   maxlength="30"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_contractor" class="form-label-full">Contratista</label>
                                            <select id="cto34_contractor"
                                                    name="cto34_contractor"
                                                    data-live-search="true"
                                                    data-width="100%"
                                                    data-style="btn-sm btn-default"
                                                    data-modal-title="Cliente directo"
                                                    class="selectpicker with-ajax">
                                                @if(!empty(old('cto34_contractor')))
                                                    <option value="{{ old('cto34_contractor') }}" selected="selected">
                                                        {{ old('cto34_contractorName')  }}
                                                    </option>
                                                @endif
                                            </select>
                                            <input type="hidden" id="cto34_contractorName" name="cto34_contractorName" value="{{ old('cto34_contractorName')  }}">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_contract" class="form-label-full">Contrato</label>
                                            <select name="cto34_contract" id="cto34_contract" class="form-control input-sm" readonly>
                                                <option value="">Seleccionar opción</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_workType" class="form-label-full">Tipo de obra</label>
                                            <select name="cto34_workType" id="cto34_workType" class="form-control input-sm">
                                                @if (!empty($catalogs['one']->CatalogoObraTipo))
                                                    <optgroup label="Opción seleccionada">
                                                        <option value="{{ ifempty($catalogs['one']->CatalogoObraTipo, '')  }}">{{ ifempty($catalogs['one']->CatalogoObraTipo, 'Ninguna')  }}</option>
                                                    </optgroup>
                                                @endif
                                                <option value="">Seleccionar opción</option>
                                                @foreach(worktypes_options() as $option)
                                                    <option value="{{ $option['value']  }}">{{ $option['text']  }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_bugetType" class="form-label-full">Tipo de presupuesto</label>
                                            <select name="cto34_bugetType" id="cto34_bugetType" class="form-control input-sm">
                                                @if (!empty($catalogs['one']->CatalogoPresupuestoTipo))
                                                    <optgroup label="Opción seleccionada">
                                                        <option value="{{ ifempty($catalogs['one']->CatalogoPresupuestoTipo, '')  }}">{{ ifempty($catalogs['one']->CatalogoPresupuestoTipo, 'Ninguna')  }}</option>
                                                    </optgroup>
                                                @endif
                                                <option value="0">Seleccionar opción</option>
                                                @foreach(budgettypes_options() as $option)
                                                    <option value="{{ $option['value']  }}">{{ $option['text']  }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_departure" class="form-label-full">Partida</label>
                                            <select name="cto34_departure" id="cto34_departure" class="form-control input-sm">
                                                <option value="" class="option-default">Seleccionar opción</option>
                                                {{--@if(!empty(old('cto34_departureName')))
                                                    <optgroup label="Partida seleccionada">
                                                        <option value="{{ old('cto34_departure') }}">
                                                            {{ old('cto34_departureName') }}
                                                        </option>
                                                    </optgroup>
                                                @endif --}}
                                                @foreach($departures['all'] as $departure)
                                                    @if($departure->tbPartidaID == old('cto34_departure'))
                                                        <option value="{{ $departure->tbPartidaID  }}" selected="selected">
                                                            {{ $departure->PartidaNombre  }}
                                                        </option>
                                                        @continue
                                                    @endif
                                                    <option value="{{ $departure->tbPartidaID  }}">
                                                        {{ $departure->PartidaNombre  }}
                                                    </option>
                                                @endforeach
                                                <option value="-1">Agregar nueva partida</option>
                                            </select>
                                            <input id="cto34_departureName"
                                                   name="cto34_departureName"
                                                   type="hidden"
                                                   value="{{ old('cto34_departureName') }}">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_subdeparture" class="form-label-full">Subpartida</label>
                                            <select name="cto34_subdeparture" id="cto34_subdeparture" class="form-control input-sm" disabled="disabled">
                                                @if(!empty(old('cto34_subdepartureName')))
                                                    <optgroup label="Subpartida seleccionada">
                                                        <option value="{{ old('cto34_subdeparture') }}" selected="selected">
                                                            {{ old('cto34_subdepartureName') }}
                                                        </option>
                                                    </optgroup>
                                                @endif
                                                <option value="0" class="option-default">Seleccionar opción</option>
                                                <option value="-1">Agregar nueva subpartida</option>
                                            </select>
                                            <input id="cto34_subdepartureName"
                                                   name="cto34_subdepartureName"
                                                   type="hidden"
                                                   value="{{ old('cto34_subdepartureName') }}">
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="put">
                                <input type="hidden" name="_query" value="{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}">
                                <input type="hidden" name="_hasSearch" value="{{ isset($filter['queries']['q']) ? 1 : 0 }}">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="_recordId" value="{{ $catalogs['one']->tbCatalogoID }}">
    {{-- Carga la vista para los formularios de registro de personas y empresa --}}
    @include('panel.constructionwork.catalog.shared.modal-forms')
@endsection
@push('scripts_footer')
    <script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/js/moment.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/constructionWork.js') }}"></script>
    <script src="{{ asset('assets/js/business.js') }}"></script>
    <script src="{{ asset('assets/js/person.js') }}"></script>
    <script>
        (function() {

            var app = new App();
            app.preventClose();
            app.formErrors('#saveForm');
            app.animateSubmit("saveForm", "addSubmitButton");
            app.initItemsList();
            app.tooltip();
            app.dateTimePickerField();
            app.filterModal();
            app.onPageTab();

            var work = new ConstructionWork();

            if ($('#cto34_subdeparture_departure').val() !== '') {
                ('#cto34_subdeparture_departure').prop('disabled', false);
            }

            work.searchBusinessWithAjax('#cto34_contractor',
                {
                    url: '{{ url("ajax/search/businessWork") }}',
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
                        businessOptionSelected(data.element);
                    }
                }
            );

            work.saveDepartureWithAjax('#saveDepartureForm', function(error, result) {

                if (error !== null) {
                    alert(error);
                    return;
                }

                var data = result.data.business;
                var modal = $('#modalDeparture');
                var elementId = '#cto34_departure';

                $(elementId).find('.option-default').after($('<option>', {
                    value: data.id,
                    text: data.name,
                }).attr('selected', 'selected'));

                $('#cto34_subdeparture_departure').append($('<option>', {
                    value: data.id,
                    text: data.name,
                }).attr('selected', 'selected'));

                $(elementId + 'Name').val(data.name);
                $('#cto34_subdeparture').prop('disabled', false);

                modal.modal('hide');
                modal.find('input[name="_element"]').val('');
            });

            work.saveSubdepartureWithAjax('#saveSubdepartureForm', function(error, result) {

                if (error !== null) {
                    alert(error);
                    return;
                }

                var data = result.data.business;
                var modal = $('#modalSubdeparture');
                var elementId = '#cto34_subdeparture';

                $(elementId).find('.option-default').after($('<option>', {
                    value: data.id,
                    text: data.name,
                }).attr('selected', 'selected'));

                $(elementId + 'Name').val(data.name);
                $(elementId).prop('disabled', false);

                modal.modal('hide');
                modal.find('input[name="_element"]').val('');
            });

            work.searchContractWithAjax('#contract', { url: '{{ url("ajax/action/search/contracts") }}', token: '{{ csrf_token() }}', workId: '{{ $works['one']->tbObraID   }}' });
            addDeparture();
            addSubDeparture();

            $('.btn-today').on('click', function() {

                var dateHuman = moment().locale('es').format('dddd DD [de] MMMM [del] YYYY');
                var date = moment().format('YYYY-MM-DD');

                $(this).parent().parent().find('.date-formated').focus();
                $(this).parent().parent().find('.date-formated').val(dateHuman);
                $(this).parent().parent().find('input[type="hidden"]').val(date);
                $(this).parent().parent().find('.date-formated').blur();
            });
        })();

        function businessOptionSelected(element) {
            var name = $(element).find('option:selected').text();
            $(element + 'Name').val(name);
        }

        function addDeparture() {

            $('#cto34_departure').on('change', function() {

                var select = $(this);

                if (select.val() === '') {
                    return;
                }

                if (select.val() === '-1') {
                    $('#modalDeparture').modal('show');
                    return;
                }

                $('#cto34_subdeparture_departure').val(select.val());

                var elementId = '#cto34_subdeparture';
                $(elementId).prop('disabled', true);

                var request = $.ajax({
                    url: '{{ url('/ajax/action/search/subdeparture') }}',
                    type: 'post',
                    dataType: 'html',
                    timeout: 90000,
                    cache: false,
                    data: {
                        id: select.val()
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    }
                });

                request.done(function(response) {

                    var result = jQuery.parseJSON(response);

                    if (result.subdepartures.length !== 0) {

                        var subdepartures = result.subdepartures;
                        var options = '';

                        $.each(subdepartures, function(index, value) {
                            options += '<option value="' + value.tbSubPartidaID + '">' +  value.SubpartidaNombre + '</option>';
                        });

                        $(elementId).find('.option-default').after(options);
                    }

                    $(elementId).prop('disabled', false);

                });

                request.fail(function(xhr, textStatus) {
                    searchMessage.html((options.hasOwnProperty('noResults')) ? options.error : 'Ocurrio un error');
                });

            })

        }

        function addSubDeparture() {

            $('#cto34_subdeparture').on('change', function() {

                var select = $(this);

                if (select.val() === '') {
                    return;
                }

                if (select.val() === '-1') {
                    $('#modalSubdeparture').modal('show');
                    return;
                }

                $('#cto34_subdepartureName').val(select.find('option:selected').text());

            })
        }

    </script>
@endpush