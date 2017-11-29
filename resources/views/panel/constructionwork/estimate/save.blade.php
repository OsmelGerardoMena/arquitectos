@extends('layouts.base')

@push('styles_head')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-select.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-datetimepicker.min.css') }}">
@endpush

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
                                @include('panel.constructionwork.estimate.shared.content-search')
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
                                @foreach ($estimates['all'] as $index => $estimate)
                                    <a id="item{{ $estimate->tbEstimacionID }}" href="{{ $navigation['base'].$navigation['section'] }}/{{ $estimate->tbEstimacionID  }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="list-group-item">
                                        <h4 class="list-group-item-heading">{{ $estimate->EstimacionLabel }}</h4>
                                        <p class="small">
                                            {{ $estimate->contract->ContratoAlias}}
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
                                        <a href="#amounts" aria-controls="amounts" role="tab" data-toggle="tab" data-type="own">
                                            Importes
                                        </a>
                                    </li>
                                    <li role="presentation" class="disabled">
                                        <a>
                                            Facturas
                                        </a>
                                    </li>
                                    <li role="presentation" class="disabled">
                                        <a>
                                            Pagos
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
                                <div class="tab-content col-sm-12 margin-bottom--20 padding-top--5">
                                    <div role="tabpanel" class="tab-pane active row" id="general">
                                        <div class="form-group col-sm-12">
                                            <label for="cto34_label" class="form-label-full">Etiqueta</label>
                                            <input id="cto34_label"
                                                   name="cto34_label"
                                                   type="text"
                                                   value="{{ old('cto34_label') }}"
                                                   readonly
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-sm-7">
                                            <label for="" class="form-label-full">Empresa contratista</label>
                                            <select id="cto34_business"
                                                    name="cto34_business"
                                                    data-live-search="true"
                                                    data-width="100%"
                                                    data-style="btn-sm btn-default"
                                                    data-modal-title="Cliente directo"
                                                    class="selectpicker with-ajax">
                                                @if(!empty(old('cto34_business')))
                                                    <option value="{{ old('cto34_business') }}" selected="selected">
                                                        {{ old('cto34_businessName')  }}
                                                    </option>
                                                @endif
                                            </select>
                                            <input type="hidden" id="cto34_businessName" name="cto34_businessName" value="{{ old('cto34_businessName')  }}">
                                        </div>
                                        <div class="form-group col-sm-5">
                                            <label for="cto34_contractedAmount" class="form-label-full">Importe contratado</label>
                                            <input id="cto34_contractedAmount"
                                                   name="cto34_contractedAmount"
                                                   type="text"
                                                   value="{{ old('cto34_contractedAmount') }}"
                                                   readonly="readonly"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="form-group col-sm-7">
                                            <label for="" class="form-label-full">Contrato</label>
                                            <select id="cto34_contract"
                                                    name="cto34_contract"
                                                    data-live-search="true"
                                                    data-width="100%"
                                                    data-style="btn-sm btn-default"
                                                    data-modal-title="Cliente directo"
                                                    class="selectpicker with-ajax">
                                                @if(!empty(old('cto34_contract')))
                                                    <option value="{{ old('cto34_contract') }}" selected="selected">
                                                        {{ old('cto34_contractName')  }}
                                                    </option>
                                                @endif
                                            </select>
                                            <input type="hidden" id="cto34_contractName" name="cto34_contractName" value="{{ old('cto34_contractName')  }}">
                                        </div>
                                        <div class="form-group col-sm-5">
                                            <label for="cto34_advanceAmount" class="form-label-full">Importe anticipo</label>
                                            <input id="cto34_advanceAmount"
                                                   name="cto34_advanceAmount"
                                                   type="text"
                                                   value="{{ old('cto34_advanceAmount') }}"
                                                   readonly="readonly"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-sm-4">
                                            <label for="cto34_workType" class="form-label-full">Tipo de obra</label>
                                            <select name="cto34_workType" id="cto34_typeWork" class="form-control input-sm">
                                                <option value="0">Seleccionar opción</option>
                                                @foreach(worktypes_options() as $option)
                                                    <option value="{{ $option['value']  }}">{{ $option['text']  }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label for="cto34_concept" class="form-label-full">Concepto</label>
                                            <select name="cto34_concept" id="cto34_concept" class="form-control input-sm">
                                                <option value="0">Seleccionar opción</option>
                                                <option value="1">Anticipo</option>
                                                <option value="2">Estimación</option>
                                                <option value="3">Devolución fondo de garantía</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label for="cto34_number" class="form-label-full">Núm.</label>
                                            <input id="cto34_number"
                                                   name="cto34_number"
                                                   type="text"
                                                   value="{{ old('cto34_number') }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-sm-7">
                                            <label for="cto34_estimationDate" class="form-label-full">Fecha estimación</label>
                                            <div class="input-group input-group-sm date-field">
                                                <input id="cto34_estimationDate"
                                                       name="cto34_estimationDate"
                                                       type="text"
                                                       value="{{ old('cto34_estimationDate') }}"
                                                       readonly="readonly"
                                                       class="form-control form-control-plain input-sm">
                                                <span class="input-group-addon" style="background-color: #fff">
                                            <span class="fa fa-calendar fa-fw text-primary"></span>
                                        </span>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-5">
                                            <label for="cto34_sequence" class="form-label-full">Secuencia</label>
                                            <input id="cto34_sequence"
                                                   name="cto34_sequence"
                                                   type="text"
                                                   value="{{ old('cto34_sequence') }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="form-group col-sm-12 text-right">
                                            <label>&nbsp;</label>
                                            <label>
                                                <input type="checkbox" name="cto34_preestimation" value="1"> Es Preestimación
                                            </label>
                                            <label>
                                                <input type="checkbox" name="cto34_settlement" value="1"> Es finiquito
                                            </label>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane row padding-top--5" id="amounts">
                                        <div class="form-group col-sm-8 margin-top--10">
                                            <div class="row">
                                                <!--<div class="form-group col-sm-8 col-sm-offset-4">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control form-control-plain" placeholder="Username" value="Contrato" aria-describedby="basic-addon1" disabled="disabled">
                                                        <span class="input-group-btn">
                                                    <button type="button" class="btn btn-default">
                                                        &gt;&gt;
                                                    </button>
                                                </span>
                                                        <input type="text" class="form-control form-control-plain" placeholder="Username" value="Ésta estimación" aria-describedby="basic-addon1" disabled="disabled">
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-4">
                                                    <p class="help-block" style="color: #000;">Anticipio amortizado en %</p>
                                                </div>-->
                                                <div class="col-sm-4" style="padding-right: 1px"></div>
                                                <div class="col-sm-4" style="padding: 0 1px">Contrato</div>
                                                <div class="col-sm-4 text-right" style="padding: 0 1px">Ésta estimación</div>
                                                <div class="clearfix"></div>
                                                <div class="col-sm-4" style="padding-right: 1px">
                                                    <label style="font-size: 13px">Anticipo amortizado (%)</label>
                                                </div>
                                                <div class="form-group col-sm-8" style="padding: 0 1px">
                                                    <div class="input-group input-group-sm">
                                                        <input id="cto34_contractAdvanceAmount" type="text" class="form-control form-control-plain" readonly="readonly">
                                                        <span class="input-group-btn" id="basic-addon1"> &gt;
                                                            <button type="button" class="btn btn-default">
                                                                &gt;&gt;
                                                            </button>
                                                        </span>
                                                        <input type="text" class="form-control form-control-plain" placeholder="" aria-describedby="basic-addon1">
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="form-group col-sm-4" style="padding-right: 1px">
                                                    <label style="font-size: 13px">Garantía retenida en %</label>
                                                </div>
                                                <div class="form-group col-sm-8" style="padding: 0 1px">
                                                    <div class="input-group input-group-sm">
                                                        <input id="cto34_contractGuarantyPercentage" type="text" class="form-control form-control-plain" readonly="readonly">
                                                        <span class="input-group-btn" id="basic-addon1">
                                                            <button type="button" class="btn btn-default">
                                                                &gt;&gt;
                                                            </button>
                                                        </span>
                                                        <input type="text" class="form-control form-control-plain" placeholder="" aria-describedby="basic-addon1">
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="form-group col-sm-4" style="padding-right: 1px">
                                                    <label style="font-size: 13px">Otras retenciones en %</label>
                                                </div>
                                                <div class="form-group col-sm-8" style="padding: 0 1px">
                                                    <div class="input-group input-group-sm">
                                                        <input id="cto34_contractOtherRententios" type="text" class="form-control form-control-plain" placeholder="" aria-describedby="basic-addon1" readonly>
                                                        <span class="input-group-btn">
                                                            <button type="button" class="btn btn-default">
                                                                &gt;&gt;
                                                            </button>
                                                        </span>
                                                        <input type="text" class="form-control form-control-plain" placeholder="" aria-describedby="basic-addon1">
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="form-group col-sm-4" style="padding-right: 1px">
                                                    <label style="font-size: 13px">Otras retenciones concepto</label>
                                                </div>
                                                <div class="form-group col-sm-8" style="padding: 0 1px">
                                                    <input id="cto34_conceptRetention"
                                                           name="cto34_conceptRetention"
                                                           type="text"
                                                           value="{{ old('cto34_conceptRetention') }}"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="form-group col-sm-4" style="padding-right: 1px">
                                                    <label style="font-size: 13px">Descuentos en %</label>
                                                </div>
                                                <div class="form-group col-sm-8" style="padding: 0 1px">
                                                    <input id="cto34_discounts"
                                                           name="cto34_discounts"
                                                           type="text"
                                                           value="{{ old('cto34_discounts') }}"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="form-group col-sm-4" style="padding-right: 1px">
                                                    <label style="font-size: 13px">Descuentos conceptos</label>
                                                </div>
                                                <div class="form-group col-sm-8" style="padding: 0 1px">
                                                    <input id="cto34_discountsConcept"
                                                           name="cto34_discountsConcept"
                                                           type="text"
                                                           value="{{ old('cto34_discountsConcept') }}"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="row">
                                                <div class="form-group col-sm-12">
                                                    <label for="">Importe generadores</label>
                                                    <div class="input-group input-group-sm">
                                                        <input id="cto34_generatorAmount"
                                                               name="cto34_generatorAmount"
                                                               type="text"
                                                               value="{{ old('cto34_generatorAmount') }}"
                                                               readonly="readonly"
                                                               class="form-control form-control-plain input-sm">
                                                        <div class="input-group-btn">
                                                            <button id="searchGenerator" type="button" class="btn btn-default" >
                                                                Generadores
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--<div class="form-group col-sm-2">
                                                    <input id="cto34_generatorAmountCurrency"
                                                           name="cto34_generatorAmountCurrency"
                                                           type="text"
                                                           value="{{ old('cto34_generatorAmountCurrency') }}"
                                                           placeholder="Moneda"
                                                           readonly="readonly"
                                                           class="form-control form-control-plain input-sm">
                                                </div>-->
                                                <div class="form-group col-sm-12">
                                                    <label for="">Importe presentado (sin IVA)</label>
                                                    <input id="cto34_presentedAmount"
                                                           name="cto34_presentedAmount"
                                                           type="text"
                                                           value="{{ old('cto34_presentedAmount') }}"
                                                           class="form-control form-control-plain input-sm" >
                                                </div>
                                                <!--<div class="form-group col-sm-2">
                                                    <input id="cto34_presentedAmountCurrency"
                                                           name="cto34_presentedAmountCurrency"
                                                           type="text"
                                                           value="{{ old('cto34_presentedAmountCurrency') }}"
                                                           readonly="readonly"
                                                           placeholder="Moneda"
                                                           class="form-control form-control-plain input-sm">
                                                </div>-->
                                                <div class="form-group col-sm-12">
                                                    <label for="">Importe estimado</label>
                                                    <input id="cto34_estimateAmount"
                                                           name="cto34_estimateAmount"
                                                           type="text"
                                                           value="{{ old('cto34_estimateAmount') }}"
                                                           readonly="readonly"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <!--<div class="form-group col-sm-2">
                                                    <input id="cto34_estimateAmountCurrency"
                                                           name="cto34_estimateAmountCurrency"
                                                           type="text"
                                                           value="{{ old('cto34_estimateAmountCurrency') }}"
                                                           placeholder="Moneda"
                                                           readonly="readonly"
                                                           class="form-control form-control-plain input-sm">
                                                </div>-->
                                                <div class="form-group col-sm-12">
                                                    <label for="">Anticipo amortizado importe</label>
                                                    <input id="cto34_advanceAmortizedAmount"
                                                           name="cto34_advanceAmortizedAmount"
                                                           type="text"
                                                           value="{{ old('cto34_advanceAmortizedAmount') }}"
                                                           readonly="readonly"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <!--<div class="form-group col-sm-2">
                                                    <input id="cto34_advanceAmortizedAmountCurrency"
                                                           name="cto34_advanceAmortizedAmountCurrency"
                                                           type="text"
                                                           value="{{ old('cto34_advanceAmortizedAmountCurrency') }}"
                                                           readonly="readonly"
                                                           placeholder="Moneda"
                                                           class="form-control form-control-plain input-sm">
                                                </div>-->
                                                <div class="form-group col-sm-12">
                                                    <label for="">Garantía retenida importe</label>
                                                    <input id="cto34_heldGarantyAmount"
                                                           name="cto34_heldGarantyAmount"
                                                           type="text"
                                                           value="{{ old('cto34_heldGarantyAmount') }}"
                                                           readonly="readonly"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <!--<div class="form-group col-sm-2">
                                                    <input id="cto34_heldGarantyAmountCurrency"
                                                           name="cto34_heldGarantyAmountCurrency"
                                                           type="text"
                                                           value="{{ old('cto34_heldGarantyAmountCurrency') }}"
                                                           readonly="readonly"
                                                           placeholder="Moneda"
                                                           class="form-control form-control-plain input-sm">
                                                </div>-->
                                                <div class="form-group col-sm-12">
                                                    <label for="">Otras retenciones importe</label>
                                                    <input id="cto34_otherRetentionAmount"
                                                           name="cto34_otherRetentionAmount"
                                                           type="text"
                                                           value="{{ old('cto34_otherRetentionAmount') }}"
                                                           readonly="readonly"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <!--<div class="form-group col-sm-2">
                                                    <input id="cto34_otherRetentionAmountCurrency"
                                                           name="cto34_otherRetentionAmountCurrency"
                                                           type="text"
                                                           value="{{ old('cto34_otherRetentionAmountCurrency') }}"
                                                           readonly="readonly"
                                                           placeholder="Moneda"
                                                           class="form-control form-control-plain input-sm">
                                                </div>-->
                                                <div class="form-group col-sm-12">
                                                    <label for="">Descuentos importe</label>
                                                    <input id="cto34_discountsAmount"
                                                           name="cto34_discountsAmount"
                                                           type="text"
                                                           value="{{ old('cto34_discountsAmount') }}"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <!--<div class="form-group col-sm-2">
                                                    <input id="cto34_discountsAmountCurrency"
                                                           name="cto34_discountsAmountCurrency"
                                                           type="text"
                                                           value="{{ old('cto34_discountsAmountCurrency') }}"
                                                           class="form-control form-control-plain input-sm">
                                                </div>-->
                                                <div class="form-group col-sm-12">
                                                    <label for="">Subtotal</label>
                                                    <input id="cto34_subtotal"
                                                           name="cto34_subtotal"
                                                           type="text"
                                                           value="{{ old('cto34_subtotal') }}"
                                                           readonly="readonly"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <!--<div class="form-group col-sm-2">
                                                    <input id="cto34_subtotalCurrency"
                                                           name="cto34_subtotalCurrency"
                                                           type="text"
                                                           value="{{ old('cto34_subtotalCurrency') }}"
                                                           readonly="readonly"
                                                           placeholder="Moneda"
                                                           class="form-control form-control-plain input-sm">
                                                </div>-->
                                                <!--<div class="form-group col-sm-12">
                                                    <label for="cto34_observations" class="form-label-full">Observaciones</label>
                                                    <textarea id="cto34_observations"
                                                              name="cto34_observations"
                                                              rows="5"
                                                              class="form-control form-control-plain">{{ old('cto34_observations') }}</textarea>
                                                </div>-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Carga la vista para los formularios de registro de personas y empresa @include('panel.constructionwork.contract.shared.modal-forms') --}}
    @include('panel.constructionwork.estimate.shared.modal-forms')
@endsection
@push('scripts_footer')
<script src="{{ asset('assets/js/jquery.matchHeight.js') }}"></script>
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
        app.initItemsList();
        app.animateSubmit("saveForm", "addSubmitButton");
        app.tooltip();
        app.dateTimePickerField();
        app.filterModal();
        app.onPageTab();

        $('#searchGenerator').on('click', function() {

            if ($('#cto34_contractor').val() === '') {
                alert('Debes elegir un contrato.');
                return;
            }

            $('#modalGenerator').modal('show');
            getGenerators()
        });

        var work = new ConstructionWork();

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

        work.searchAdvanceContractWithAjax('#cto34_contract',
            {
                url: '{{ url("ajax/action/search/contracts") }}',
                token: '{{ csrf_token() }}',
                workId: '{{ $works['one']->tbObraID }}',
                optionClass: 'option-newContract',
                optionListClass: 'option-contract',
                canAdd: false

            }, function(data) {

                if (data.action === 'newClicked') {
                    //beforeShowBusinessModal(data.element);
                }

                if (data.action === 'optionClicked' || data.action === 'hide') {

                    if (!data.hasOwnProperty('rows')) {
                        return;
                    }

                    if (data.rows.length == 0) {
                        return;
                    }

                    var selectedContract;

                    $.each(data.rows, function (index, value) {

                        if ($(data.element).find('option:selected').val() == value.tbContratoID ) {
                            selectedContract = data.rows[index];
                            return false;
                        }
                    });

                    console.log($(data.element).find('option:selected').val());
                    console.log(data.rows);

                    //console.log(selectedContract);
                    $('#cto34_contractedAmount').val(selectedContract.ContratoImporteContratado);
                    $('#cto34_advanceAmount').val(selectedContract.ContratoAnticipoMonto);
                    $('#cto34_contractor').val(selectedContract.TbDirEmpresaObraID_Contratista);
                    $('#cto34_contractorName').val(selectedContract.contractor.business[0].EmpresaAlias);
                    $('#cto34_contractAdvanceAmount').val(selectedContract.ContratoAnticipoPCT);
                    $('#cto34_contractGuarantyPercentage').val(selectedContract.ContratoFianzaGarantiaPCT);
                    $('#cto34_contractOtherRententios').val(selectedContract.ContratoOtrasRetencionesPCT);
                }
            }
        );
    })();

    function businessOptionSelected(element) {
        var name = $(element).find('option:selected').text();
        $(element + 'Name').val(name);
    }

    function getGenerators() {

        var id = $('#cto34_contract').find('option:selected');

        var request = $.post('{{ url('ajax/search/generators')  }}', {workId: '{{ $works['one']->tbObraID }}', contractId: id.val(),_token: '{{ csrf_token() }}' });
        request.done(function (data) {
            console.log(data);
            var catalogs = data.catalogs;
            var estimates = data.estimates;
            var html = '';
            var generatorsContent = $('#modalGenerator').find('tbody');
            var tr = '';

            if (estimates.length > 0) {

                $.each(estimates, function (i, estimate) {

                    $.each(estimate.catalogs, function (j, catalog) {

                        tr = '';
                        tr += '<tr data-row=' + j + '><td class="selectCatalog"><input name="catalogs[]" type="checkbox" value="' + j + '" data-id="' + catalog.tbCatalogoID + '"></td>';
                        tr += '<td class="catalogAmount">' + catalog.CatalogoImporte + '</td>';
                        tr += '<td>' + catalog.pivot.GenEstimCantidadEstimadaEstaEstim + '</td>';
                        tr += '<td>' + catalog.pivot.GenEstimCantidadEstimadaEstimAnteriores + '</td>';
                        tr += '<td>' + catalog.pivot.GenEstimCantidadEstimadaEstimPosteriores + '</td>';
                        tr += '<td>' + catalog.pivot.GenEstimCantidadEstimadaTotal + '</td>';
                        tr += '<td class="estimateAmount">0</td>';
                        tr += '<td class="addEstimateAmount"><input name="cto34_estimateAmount" class="form-control input-sm"></td></tr>';
                        html += tr;
                    });
                });

            } else {

                if (catalogs.length === 0) {
                    html = '<tr><td colspan="4">No se han encontrado catálogos.</td></tr>';
                    generatorsContent.html(html);
                    return;
                }

                $.each(catalogs, function (i, catalog) {

                    tr = '';
                    tr += '<tr data-row=' + i + '><td class="selectCatalog"><input name="catalogs[]" type="checkbox" value="' + i + '" data-id="' + catalog.tbCatalogoID + '"></td>';
                    tr += '<td class="catalogAmount">' + catalog.CatalogoImporte + '</td>';
                    tr += '<td class="estimateAmount">0</td>';
                    tr += '<td class="addEstimateAmount"><input name="cto34_estimateAmount" class="form-control input-sm"></td></tr>';
                    html += tr;
                });

            }

            generatorsContent.html(html);

        });

        request.fail(function () {
            alert('Ocurrio un error.');
        });
    }

</script>
@endpush