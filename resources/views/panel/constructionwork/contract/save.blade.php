@extends('layouts.base')

@push('styles_head')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-select.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-datetimepicker.min.css') }}">


<style>

    body{

        overflow-y: scroll !important;

    }

</style>
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
                        <div class="list-group-item padding-clear padding-bottom--10">
                            {{--
                            Content Search
                            Se incluyen la forma para la busqueda y filtrado de datos
                            --}}
                            @include('panel.constructionwork.contract.shared.content-search')
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
                            @foreach ($contracts['all'] as $index => $contract)
                            <a id="item{{ $contract->tbContratoID }}" href="{{ $navigation['base'] }}/info/{{ $contract->tbContratoID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}#item{{ $contract->tbContratoID }}" class="list-group-item">
                                <h4 class="list-group-item-heading">{{ $contract->ContratoAlias }}</h4>
                                <p class="text-muted small">
                                    {{ $contract->ContratoAlcanceCorto }}
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
                    <div class="col-sm-7 col-md-8 col-lg-9 panel-item" style="position: relative;">
                        <div class="row">
                            <div class="col-sm-12">
                                @include('layouts.alerts', ['errors' => $errors])
                            </div>
                        </div>
                        <div class="clarfix"></div>
                        <ul class="nav nav-tabs nav-tabs-works" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#general" aria-controls="general" role="tab" data-toggle="tab">
                                    General
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#signatures" aria-controls="signatures" role="tab" data-toggle="tab">
                                    Firmas
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#dates" aria-controls="dates" role="tab" data-toggle="tab">
                                    Fechas
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#guarantees" aria-controls="guarantees" role="tab" data-toggle="tab">
                                    Garantías
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#address" aria-controls="address" role="tab" data-toggle="tab">
                                    Domicilios
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#details" aria-controls="details" role="tab" data-toggle="tab">
                                    Detalles
                                </a>
                            </li>
                            <li role="presentation" class="disabled">
                                <a href="#">
                                    Entregables
                                </a>
                            </li>
                            <li role="presentation" class="disabled">
                                <a href="#">
                                    Catálogos
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
                        <form id="saveForm" action="{{ $navigation['base'] }}/action/save" method="post" accept-charset="utf-8" class="tab-content margin-bottom--20">
                            <div role="tabpanel" class="tab-pane row active margin-top--10" id="general">
                                <div class="form-group col-sm-12">
                                    <label for="cto34_alias" class="form-label-full">Alias</label>
                                    <input id="cto34_alias"
                                           name="cto34_alias"
                                           type="text"
                                           value="{{ old('cto34_alias') }}"
                                           placeholder=""
                                           class="form-control form-control-plain input-sm" readonly>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="cto34_contractor" class="form-label-full">Contratista</label>
                                    <select id="cto34_contractor"
                                            name="cto34_contractor"
                                            data-live-search="true"
                                            data-width="100%"
                                            data-style="btn-sm btn-default"
                                            data-modal-title="Contratista"
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
                                    <label for="cto34_modality" class="form-label-full">Modalidad</label>
                                    <select name="cto34_modality" id="cto34_modality" class="form-control input-sm">
                                        <option value="">Seleccionar opción</option>
                                        @foreach(contractmodality_options() as $option)
                                        <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group col-sm-6">
                                    <label for="cto34_typeAgreement" class="form-label-full">Tipo de acuerdo</label>
                                    <select name="cto34_typeAgreement" id="cto34_typeAgreement" class="form-control input-sm">
                                        <option value="">Seleccionar opción</option>
                                        @foreach(agreementtypes_options() as $option)
                                        <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <div class="form-group col-sm-6">
                                        <div id="originalContract" class="dropdown search-ajax">
                                            <label for="cto34_originalContractName" class="form-label-full">Contrato original</label>
                                            <input id="cto34_originalContractName"
                                                   name="cto34_originalContractName"
                                                   type="text"
                                                   value="{{ old('cto34_originalContractName') }}"
                                                   placeholder="Ingresar nombre de contrato"
                                                   disabled="disabled"
                                                   class="form-control search-field input-sm">
                                            <input id="cto34_originalContract" name="cto34_originalContract" type="hidden" value="{{ old('cto34_originalContract') }}" class="search-hidden">
                                            <ul class="dropdown-menu" aria-labelledby="dLabel">
                                                <!--<li>
<a href="#" class="search-new" data-id="#cto34_contract" data-toggle="modal" data-target="#modalBusiness">Agregar empresa</a>
</li>-->
                                                <li class="dropdown-header search-message"></li>
                                                <div class="search-result"></div>
                                            </ul>
                                        </div>
                                    </div>
                                    <!--<div id="addendumAmount" class="form-group col-sm-6 col-sm-offset-6" style="display: none">
<label for="cto34_addendumAmount" class="form-label-full">Importe de Addendum</label>
<input id="cto34_addendumAmount"
name="cto34_addendumAmount"
type="text"
value="{{ old('cto34_addendumAmount') }}"
class="form-control form-control-plain input-sm">
</div>-->
                                    <div class="form-group col-sm-6">
                                        <label for="cto34_contractNumber" class="form-label-full">Número</label>
                                        <input id="cto34_contractNumber"
                                               name="cto34_contractNumber"
                                               type="text"
                                               value="{{ old('cto34_contractNumber') }}"
                                               class="form-control form-control-plain input-sm">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="cto34_typeAssignment" class="form-label-full">Tipo de asignación</label>
                                        <select name="cto34_typeAssignment" id="cto34_typeAssignment" class="form-control input-sm">
                                            <option value="">Seleccionar opción</option>
                                            @foreach(assignmenttypes_options() as $option)
                                            <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group col-sm-6">
                                        <label for="cto34_amountWithoutTax" class="form-label-full">Importe sin IVA</label>
                                        <input id="cto34_amountWithoutTax"
                                               name="cto34_amountWithoutTax"
                                               type="text"
                                               value="{{ old('cto34_amountWithoutTax') }}"
                                               class="form-control form-control-plain input-sm">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="cto34_currency" class="form-label-full">Moneda</label>
                                        <select name="cto34_currency" id="cto34_currency" class="form-control input-sm">
                                            <option value="0">Seleccionar opción</option>
                                            @foreach($currencies['all'] as $currency)
                                            <option value="{{ $currency->tbMonedasID  }}">{{ $currency->MonedaAbreviatura }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group col-sm-6">
                                        <label for="cto34_directCustomer" class="form-label-full">Cliente directo</label>
                                        <select id="cto34_directCustomer"
                                                name="cto34_directCustomer"
                                                data-live-search="true"
                                                data-width="100%"
                                                data-style="btn-sm btn-default"
                                                data-modal-title="Cliente directo"
                                                class="selectpicker with-ajax">
                                            @if(!empty(old('cto34_directCustomer')))
                                            <option value="{{ old('cto34_directCustomer') }}" selected="selected">
                                                {{ old('cto34_directCustomerName')  }}
                                            </option>
                                            @endif
                                        </select>
                                        <input type="hidden" id="cto34_directCustomerName" name="cto34_directCustomerName" value="{{ old('cto34_directCustomerName')  }}">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="cto34_contractCustomer" class="form-label-full">Cliente contratante</label>
                                        <select id="cto34_contractCustomer"
                                                name="cto34_contractCustomer"
                                                data-live-search="true"
                                                data-width="100%"
                                                data-style="btn-sm btn-default"
                                                data-modal-title="Cliente contratante"
                                                class="selectpicker with-ajax">
                                            @if(!empty(old('cto34_contractCustomer')))
                                            <option value="{{ old('cto34_contractCustomer') }}" selected="selected">
                                                {{ old('cto34_contractCustomerName')  }}
                                            </option>
                                            @endif
                                        </select>
                                        <input type="hidden" id="cto34_contractCustomerName" name="cto34_contractCustomerName" value="{{ old('cto34_contractCustomerName')  }}">
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label for="cto34_contractObjects" class="form-label-full">Objeto del contrato</label>
                                        <textarea id="cto34_contractObjects"
                                                  name="cto34_contractObjects"
                                                  rows="3"
                                                  maxlength="4000"
                                                  class="form-control form-control-plain"></textarea>
                                        <small class="form-count small text-muted"><span class="form-counter">0</span>/4000</small>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="cto34_shortScope" class="form-label-full">Alcance corto</label>
                                        <input id="cto34_shortScope"
                                               name="cto34_shortScope"
                                               type="text"
                                               value="{{ old('cto34_shortScope') }}"
                                               maxlength="50"
                                               class="form-control form-control-plain input-sm">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="cto34_supervisingCompany" class="form-label-full">Empresa supervisora</label>
                                        <select id="cto34_supervisingCompany"
                                                name="cto34_supervisingCompany"
                                                data-live-search="true"
                                                data-width="100%"
                                                data-style="btn-sm btn-default"
                                                data-modal-title="Responsable en obra"
                                                class="selectpicker with-ajax">
                                            @if(!empty(old('cto34_supervisingCompany')))
                                            <option value="{{ old('cto34_supervisingCompany') }}" selected="selected">
                                                {{ old('cto34_supervisingCompanyName')  }}
                                            </option>
                                            @endif
                                        </select>
                                        <input type="hidden" id="cto34_supervisingCompanyName" name="cto34_supervisingCompanyName" value="{{ old('cto34_supervisingCompanyName')  }}">
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane row margin-top--10" id="signatures">
                                <div class="form-group col-sm-6">
                                    <label class="form-label-full">Firma por el cliente</label>
                                    <select id="cto34_customerSignature"
                                            name="cto34_customerSignature"
                                            data-live-search="true"
                                            data-width="100%"
                                            data-style="btn-sm btn-default"
                                            data-modal-title="Firma por el cliente"
                                            class="selectpicker with-ajax">
                                        @if(!empty(old('cto34_customerSignature')))
                                        <option value="{{ old('cto34_customerSignature') }}" selected="selected">
                                            {{ old('cto34_customerSignatureName')  }}
                                        </option>
                                        @endif
                                    </select>
                                    <input type="hidden" id="cto34_customerSignatureName" name="cto34_customerSignatureName" value="{{ old('cto34_customerSignatureName')  }}">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="cto34_customerRepresentative">Repr. del cliente en obra</label>
                                    <select id="cto34_customerRepresentative"
                                            name="cto34_customerRepresentative"
                                            data-live-search="true"
                                            data-width="100%"
                                            data-style="btn-sm btn-default"
                                            data-modal-title="Repr. del cliente en obra"
                                            class="selectpicker with-ajax">
                                        @if(!empty(old('cto34_customerRepresentative')))
                                        <option value="{{ old('cto34_customerRepresentative') }}" selected="selected">
                                            {{ old('cto34_customerRepresentativeName')  }}
                                        </option>
                                        @endif
                                    </select>
                                    <input type="hidden" id="cto34_customerRepresentativeName" name="cto34_customerRepresentativeName" value="{{ old('cto34_customerRepresentativeName')  }}">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="form-label-full">Firma por el contratista</label>
                                    <select id="cto34_contractorSignature"
                                            name="cto34_contractorSignature"
                                            data-live-search="true"
                                            data-width="100%"
                                            data-style="btn-sm btn-default"
                                            data-modal-title="Firma por el contratista"
                                            class="selectpicker with-ajax">
                                        @if(!empty(old('cto34_contractorSignature')))
                                        <option value="{{ old('cto34_contractorSignature') }}" selected="selected">
                                            {{ old('cto34_contractorSignatureName')  }}
                                        </option>
                                        @endif
                                    </select>
                                    <input type="hidden" id="cto34_contractorSignatureName" name="cto34_contractorSignatureName" value="{{ old('cto34_contractorSignatureName')  }}">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="form-label-full">Responsable en obra</label>
                                    <select id="cto34_workManager"
                                            name="cto34_workManager"
                                            data-live-search="true"
                                            data-width="100%"
                                            data-style="btn-sm btn-default"
                                            data-modal-title="Responsable en obra"
                                            class="selectpicker with-ajax">
                                        @if(!empty(old('cto34_workManager')))
                                        <option value="{{ old('cto34_workManager') }}" selected="selected">
                                            {{ old('cto34_workManagerName')  }}
                                        </option>
                                        @endif
                                    </select>
                                    <input type="hidden" id="cto34_workManagerName" name="cto34_workManagerName" value="{{ old('cto34_workManagerName')  }}">
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane row margin-top--10" id="dates">
                                <div id="cto34_formatedDateSDContainer" class="form-group col-sm-6">
                                    <label for="cto34_formatedDateSD" class="form-label-full">Fecha firma</label>
                                    <div class="input-group input-group-sm date-field">
                                        <input id="cto34_formatedDateSD"
                                               name="cto34_formatedDateSD"
                                               type="text"
                                               value="{{ old('cto34_formatedDateSD') }}"
                                               readonly="readonly"
                                               class="form-control form-control-plain input-sm date-formated">
                                        <input name="cto34_signatureDate" type="hidden" value="{{ old('cto34_signatureDate') }}">
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
                                <div class="clearfix"></div>
                                <div id="cto34_formatedDateCSDContainer" class="form-group col-sm-4">
                                    <label for="cto34_formatedDateCSD" class="form-label-full">Inicio contrato</label>
                                    <div class="input-group input-group-sm date-field">
                                        <input id="cto34_formatedDateCSD"
                                               name="cto34_formatedDateCSD"
                                               type="text"
                                               value="{{ old('cto34_formatedDateCSD') }}"
                                               readonly="readonly"
                                               class="form-control form-control-plain input-sm date-formated">
                                        <input name="cto34_contractStartDate" type="hidden" value="{{ old('cto34_contractStartDate') }}">
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
                                <div id="cto34_formatedDateEDContainer" class="form-group col-sm-4">
                                    <label for="cto34_formatedDateED" class="form-label-full">Término contrato</label>
                                    <div class="input-group input-group-sm date-field">
                                        <input id="cto34_formatedDateED"
                                               name="cto34_formatedDateED"
                                               type="text"
                                               value="{{ old('cto34_formatedDateED') }}"
                                               readonly="readonly"
                                               class="form-control form-control-plain input-sm date-formated">
                                        <input name="cto34_contractEndDate" type="hidden" value="{{ old('cto34_contractEndDate') }}">
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
                                <div class="form-group col-sm-4">
                                    <label for="cto34_contractDuration" class="form-label-full">Duración contrato</label>
                                    <input id="cto34_contractDuration"
                                           name="cto34_contractDuration"
                                           type="text"
                                           value="{{ old('cto34_contractDuration') }}"
                                           readonly="readonly"
                                           class="form-control form-control-plain input-sm">
                                </div>
                                <div class="clearfix"></div>
                                <div id="cto34_formatedDateCASDContainer" class="form-group col-sm-4">
                                    <label for="cto34_formatedDateCASD" class="form-label-full">Inicio real</label>
                                    <div class="input-group input-group-sm date-field">
                                        <input id="cto34_formatedDateCASD"
                                               name="cto34_formatedDateCASD"
                                               type="text"
                                               value="{{ old('cto34_formatedDateCASD') }}"
                                               readonly="readonly"
                                               class="form-control form-control-plain input-sm date-formated">
                                        <input name="cto34_contractActualStartDate" type="hidden" value="{{ old('cto34_contractActualStartDate') }}">
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
                                <div id="cto34_formatedDateCAEDContainer" class="form-group col-sm-4">
                                    <label for="cto34_formatedDateCAED" class="form-label-full">Término real</label>
                                    <div class="input-group input-group-sm date-field">
                                        <input id="cto34_formatedDateCAED"
                                               name="cto34_formatedDateCAED"
                                               type="text"
                                               value="{{ old('cto34_formatedDateCAED') }}"
                                               readonly="readonly"
                                               class="form-control form-control-plain input-sm date-formated">
                                        <input name="cto34_contractActualEndDate" type="hidden" value="{{ old('cto34_contractActualEndDate') }}">
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
                                <div class="form-group col-sm-4">
                                    <label for="cto34_contractActualDuration" class="form-label-full">Duración real</label>
                                    <input id="cto34_contractActualDuration"
                                           name="cto34_contractActualDuration"
                                           type="text"
                                           value="{{ old('cto34_contractActualDuration') }}"
                                           readonly="readonly"
                                           class="form-control form-control-plain input-sm">
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane row margin-top--10" id="guarantees">
                                <div class="form-group col-sm-4">
                                    <label for="cto34_advancePayment" class="form-label-full">Anticipo %</label>
                                    <div class="input-group input-group-sm">
                                        <input id="cto34_advancePayment"
                                               name="cto34_advancePayment"
                                               type="number"
                                               value="{{ old('cto34_advancePayment') }}"
                                               min="0"
                                               class="form-control form-control-plain input-sm">
                                        <span class="input-group-addon" style="background-color: #fff">
                                            &#37;
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="cto34_advancePaymentAmount" class="form-label-full">Anticipo monto</label>
                                    <input id="cto34_advancePaymentAmount"
                                           name="cto34_advancePaymentAmount"
                                           type="text"
                                           readonly
                                           value="{{ old('cto34_advancePaymentAmount') }}"
                                           class="form-control form-control-plain input-sm">
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group col-sm-4">
                                    <label for="cto34_guaranteeFund" class="form-label-full">Fondo garantía %</label>
                                    <div class="input-group input-group-sm">
                                        <input id="cto34_guaranteeFund"
                                               name="cto34_guaranteeFund"
                                               type="number"
                                               value="{{ old('cto34_guaranteeFund') }}"
                                               min="0"
                                               class="form-control form-control-plain input-sm">
                                        <span class="input-group-addon" style="background-color: #fff">
                                            &#37;
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="cto34_guaranteeFundAmount" class="form-label-full">Fondo garantía monto</label>
                                    <div>
                                        <input id="cto34_guaranteeFundAmount"
                                               name="cto34_guaranteeFundAmount"
                                               type="number"
                                               value="{{ old('cto34_guaranteeFundAmount') }}"
                                               min="0"
                                               readonly
                                               class="form-control form-control-plain input-sm">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group col-sm-4">
                                    <label for="cto34_otherRetentions" class="form-label-full">Otras retenciones %</label>
                                    <div class="input-group input-group-sm">
                                        <input id="cto34_otherRetentions"
                                               name="cto34_otherRetentions"
                                               type="number"
                                               value="{{ old('cto34_otherRetentions') }}"
                                               min="0"
                                               class="form-control form-control-plain input-sm">
                                        <span class="input-group-addon" style="background-color: #fff">
                                            &#37;
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="cto34_otherRetentionsAmount" class="form-label-full">Otras retenciones monto</label>
                                    <div>
                                        <input id="cto34_otherRetentionsAmount"
                                               name="cto34_otherRetentionsAmount"
                                               type="number"
                                               value="{{ old('cto34_otherRetentionsAmount') }}"
                                               min="0"
                                               readonly
                                               class="form-control form-control-plain input-sm">
                                    </div>
                                </div>
                                <div class="form-group col-sm-12">
                                    <label for="cto34_otherRetentionsConcept" class="form-label-full">Otras retenciones concepto</label>
                                    <input id="cto34_otherRetentionsConcept"
                                           name="cto34_otherRetentionsConcept"
                                           type="text"
                                           value="{{ old('cto34_otherRetentionsConcept') }}"
                                           class="form-control form-control-plain input-sm">
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group col-sm-4">
                                    <label for="cto34_depositBond" class="form-label-full">Fianza anticipo</label>
                                    <div class="input-group input-group-sm">
                                        <input id="cto34_depositBond"
                                               name="cto34_depositBond"
                                               type="number"
                                               value="{{ old('cto34_depositBond') }}"
                                               min="0"
                                               class="form-control form-control-plain input-sm">
                                        <span class="input-group-addon" style="background-color: #fff">
                                            &#37;
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="cto34_guaranteeBond" class="form-label-full">Fianza garantía</label>
                                    <div class="input-group input-group-sm">
                                        <input id="cto34_guaranteeBond"
                                               name="cto34_guaranteeBond"
                                               type="number"
                                               value="{{ old('cto34_guaranteeBond') }}"
                                               min="0"
                                               class="form-control form-control-plain input-sm">
                                        <span class="input-group-addon" style="background-color: #fff">
                                            &#37;
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="cto34_hiddenViceBond" class="form-label-full">Fianza vicios ocultos</label>
                                    <div class="input-group input-group-sm">
                                        <input id="cto34_hiddenViceBond"
                                               name="cto34_hiddenViceBond"
                                               type="number"
                                               value="{{ old('cto34_hiddenViceBond') }}"
                                               min="0"
                                               class="form-control form-control-plain input-sm">
                                        <span class="input-group-addon" style="background-color: #fff">
                                            &#37;
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12">
                                    <label for="cto34_otherBondsGuarantees" class="form-label-full">Otras fianzas y garantías</label>
                                    <textarea id="cto34_otherBondsGuarantees"
                                              name="cto34_otherBondsGuarantees"
                                              rows="5"
                                              maxlength="4000"
                                              class="form-control form-control-plain">{{ old('cto34_otherBondsGuarantees') }}</textarea>
                                    <small class="form-count small text-muted"><span class="form-counter">0</span>/4000</small>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane row margin-top--10" id="address">
                                <div class="form-group col-sm-12">
                                    <label for="cto34_customerAddress" class="form-label-full">Domicilio cliente</label>
                                    <select name="cto34_customerAddress" id="cto34_customerAddress" class="form-control input-sm" data-id="" disabled="disabled">
                                        <option value="">Seleccionar opción</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-12">
                                    <label for="cto34_contractorAddress" class="form-label-full">Domicilio contratista</label>
                                    <select name="cto34_contractorAddress" id="cto34_contractorAddress" class="form-control input-sm" data-id="" disabled="disabled">
                                        <option value="">Seleccionar opción</option>
                                    </select>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane row margin-top--10" id="details">
                                <div class="form-group col-sm-6">
                                    <label class="form-label-full">Creado por</label>
                                    <input type="text"
                                           value="{{ Auth::user()['person']->PersonaNombreDirecto }}"
                                           class="form-control form-control-plain input-sm" disabled>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="form-label-full">Creado timestamp</label>
                                    <input type="text"
                                           value="{{ Carbon\Carbon::now()->formatLocalized('%A %d %B %Y')  }}"
                                           class="form-control form-control-plain input-sm" disabled>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="form-label-full">Modificado por</label>
                                    <input type="text"
                                           value="-"
                                           class="form-control form-control-plain input-sm" disabled>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="form-label-full">Modificado timestamp</label>
                                    <input type="text"
                                           value="-"
                                           class="form-control form-control-plain input-sm" disabled>
                                </div>
                            </div>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Guarda los errores para marcar los campos con errores --}}
<input type="hidden" name="_errors" value="{{ json_encode($errors->messages()) }}">
{{-- Carga la vista para los formularios de registro de personas y empresa --}}
@include('panel.constructionwork.contract.shared.modal-forms')
@include('panel.constructionwork.contract.shared.filter-modal')
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
        app.animateSubmit("saveForm", "addSubmitButton");
        app.initItemsList();
        app.tooltip();
        app.dateTimePickerField('#cto34_formatedDateSDContainer');
        app.dateTimePickerField('#cto34_formatedDateCSDContainer');
        app.dateTimePickerField('#cto34_formatedDateEDContainer');
        app.dateTimePickerField('#cto34_formatedDateCASDContainer');
        app.dateTimePickerField('#cto34_formatedDateCAEDContainer');
        app.filterModal();
        app.onPageTab();

        app.limitInput("#cto34_contractObjects", 4000);
        app.limitInput("#cto34_otherBondsGuarantees", 4000);

        var work = new ConstructionWork();
        var business = new Business();
        var person = new Person();


        $('#cto34_amountWithoutTax').on('keyup change', function() {

            var amount = $(this);
            var advance = $('#cto34_advancePayment');
            var guarantee = $('#cto34_guaranteeFund');
            var retention = $('#cto34_otherRetentions');

            if (advance.val().length > 0) {

                var total = (amount.val() / 100) * advance.val();
                $('#cto34_advancePaymentAmount').val(total);

            }

            if (guarantee.val().length > 0) {

                var total = (amount.val() / 100) * guarantee.val();
                $('#cto34_guaranteeFundAmount').val(total);

            }

            if (retention.val().length > 0) {

                var total = (amount.val() / 100) * retention.val();
                $('#cto34_otherRetentionsAmount').val(total);

            }

        });

        $('#cto34_advancePayment').on('keyup change', function() {

            var amount = $('#cto34_amountWithoutTax');
            var advance = $(this);

            if (amount.val().length > 0) {

                var total = (amount.val() / 100) * advance.val();
                $('#cto34_advancePaymentAmount').val(total);

            }

        });

        $('#cto34_guaranteeFund').on('keyup change', function() {

            var amount = $('#cto34_amountWithoutTax');
            var guarantee = $(this);

            if (amount.val().length > 0) {

                var total = (amount.val() / 100) * guarantee.val();
                $('#cto34_guaranteeFundAmount').val(total);

            }

        });

        $('#cto34_otherRetentions').on('keyup change', function() {

            var amount = $('#cto34_amountWithoutTax');
            var retention = $(this);

            if (amount.val().length > 0) {

                var total = (amount.val() / 100) * retention.val();
                $('#cto34_otherRetentionsAmount').val(total);

            }

        });

        customerSearchContractWithAjax("#originalContract",
                                       {
            url: '{{ url("ajax/action/search/contracts") }}',
            token: '{{ csrf_token() }}',
            workId: '{{ $works['one']->tbObraID }}' });

        /**
         *  Hacemos la busqueda para Cliente directo
         */
        work.searchBusinessWithAjax('#cto34_directCustomer',
                                    {
            url: '{{ url("ajax/search/businessWork") }}',
            token: '{{ csrf_token() }}',
            workId: '{{ $works['one']->tbObraID }}',
            optionClass: 'option-newDirectCustomer',
            optionListClass: 'option-directCustomer'

        }, function(data) {

            if (data.action === 'newClicked') {
                beforeShowBusinessModal(data.element);
            }

            if (data.action === 'optionClicked') {
                businessOptionSelected(data.element);
            }
        }
                                   );

        /**
         *  Hacemos la busqueda para Cliente contratante
         */
        work.searchBusinessWithAjax('#cto34_contractCustomer',
                                    {
            url: '{{ url("ajax/search/businessWork") }}',
            token: '{{ csrf_token() }}',
            workId: '{{ $works['one']->tbObraID }}',
            optionClass: 'option-newContractCustomer',
            optionListClass: 'option-contractCustomer'

        }, function(data) {

            if (data.action === 'newClicked') {
                beforeShowBusinessModal(data.element);
            }

            if (data.action === 'optionClicked') {
                businessOptionSelected(data.element);

                var id = $(data.element).find('option:selected').val();
                $('#cto34_customerAddress').attr('data-id', id).prop('disabled', false);

                var select = $('#cto34_customerAddress');
                var businessId = select.attr('data-id');

                if (businessId === '') {
                    alert('Debes seleccionar un cliente contratante');
                    $(this).blur();
                    return;
                }

                var request = $.ajax({
                    url: '{{ url("ajax/search/businessAddressWork") }}',
                    type: 'post',
                    dataType: 'html',
                    timeout: 90000,
                    cache: false,
                    data: {
                        id: businessId
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                request.done(function(response) {

                    var result = jQuery.parseJSON(response);
                    var addresses = result.businessWork.business_one.addresses;

                    console.log(result);
                    console.log(addresses);

                    var options = '';

                    $.each(addresses, function(index, value) {
                        options += '<option value="' + value.tbDirEmpresaDomicilioID + '" >' + value.address.DirDomicilioCompleto + '</option>';
                    });

                    select.append(options);
                });

                request.fail(function(xhr, textStatus) {
                    alert('Error ' + textStatus);
                });
            }
        }
                                   );

        /**
         *  Hacemos la busqueda para Contratista
         */
        work.searchBusinessWithAjax('#cto34_contractor',
                                    {
            url: '{{ url("ajax/search/businessWork") }}',
            token: '{{ csrf_token() }}',
            workId: '{{ $works['one']->tbObraID }}',
            optionClass: 'option-newContractor',
            optionListClass: 'option-contractor'

        }, function(data) {

            if (data.action === 'newClicked') {
                beforeShowBusinessModal(data.element);
            }

            if (data.action === 'optionClicked') {
                businessOptionSelected(data.element);

                var id = $(data.element).find('option:selected').val();
                $('#cto34_contractorAddress').attr('data-id', id).prop('disabled', false);

                var select = $('#cto34_contractorAddress');
                var businessId = select.attr('data-id');

                if (businessId === '') {
                    alert('Debes seleccionar un cliente contratante');
                    $(this).blur();
                    return;
                }

                var request = $.ajax({
                    url: '{{ url("ajax/search/businessAddressWork") }}',
                    type: 'post',
                    dataType: 'html',
                    timeout: 90000,
                    cache: false,
                    data: {
                        id: businessId
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                request.done(function(response) {
                    console.log(response);
                    var result = jQuery.parseJSON(response);
                    var addresses = result.businessWork.business_one.addresses;
                    console.log(result.businessWork);
                    console.log(result.businessWork.business_one.addresses);

                    var options = '';

                    $.each(addresses, function(index, value) {
                        options += '<option value="' + value.tbDirEmpresaDomicilioID + '" >' + value.address.DirDomicilioCompleto + '</option>';
                    });

                    select.append(options);
                });

                request.fail(function(xhr, textStatus) {
                    alert('Error ' + textStatus);
                });
            }
        }
                                   );

        /**
         * Hacemos busqueda Empresa supervisora
         */
        work.searchBusinessWithAjax('#cto34_supervisingCompany',
                                    {
            url: '{{ url("ajax/search/businessWork") }}',
            token: '{{ csrf_token() }}',
            workId: '{{ $works['one']->tbObraID }}',
            optionClass: 'option-newSupervisingCompany',
            optionListClass: 'option-supervisingCompany'

        }, function(data) {

            if (data.action === 'newClicked') {
                beforeShowBusinessModal(data.element);
            }

            if (data.action === 'optionClicked') {
                businessOptionSelected(data.element);
            }
        });

        /**
         * Hacemos busqueda general de empresas
         */
        business.searchWithAjax('#searchBusiness',
                                {
            url: '{{ url("ajax/search/business") }}',
            token: '{{ csrf_token() }}',
            optionClass: 'option-newSearchBusiness',
            optionListClass: 'option-searchBusiness'

        }, function(data) {

            if (data.action === 'newClicked') {

                $('#saveBusinessForm').show();
                $('#saveSearchedBusinessForm').hide();
            }

            if (data.action === 'optionClicked') {
                businessOptionSelected(data.element);
            }
        });

        /**
         * Hacemos busqueda general de empresas para nueva persona
         */
        business.searchWithAjax('#cto34_searchBusiness',
                                {
            url: '{{ url("ajax/search/business") }}',
            token: '{{ csrf_token() }}',
            optionClass: 'option-newcto34_searchBusiness',
            optionListClass: 'option-cto34_searchBusiness',
            canAdd: false

        }, function(data) {

            if (data.action === 'newClicked') {

                $('#saveBusinessForm').show();
                $('#saveSearchedBusinessForm').hide();
            }

            if (data.action === 'optionClicked') {
                businessOptionSelected(data.element);
            }
        });

        business.searchWithAjax('#cto34_person_searchBusiness',
                                {
            url: '{{ url("ajax/search/business") }}',
            token: '{{ csrf_token() }}',
            optionClass: 'option-newcto34_person_searchBusiness',
            optionListClass: 'option-cto34_person_searchBusiness',
            canAdd: false

        }, function(data) {

            if (data.action === 'newClicked') {
                //todo
            }

            if (data.action === 'optionClicked') {
                businessOptionSelected(data.element);
            }
        });

        /**
         * Esta acción se ejecuta despues de guardar un empresa en la obra
         */
        work.saveBusinessWithAjax('#saveSearchedBusinessForm', function(error, result) {

            if (error !== null) {
                alert(error);
                return;
            }

            var data = result.data.business;
            var modal = $('#modalBusiness');
            var form = $('#saveSearchedBusinessForm');
            var elementId = form.find('input[name="_element"]').val();

            $(elementId).append($('<option>', {
                value: data.id,
                text: data.name,
            }).attr('selected', 'selected'));

            $(document).find(elementId + 'Name').val(data.name);
            $(elementId).selectpicker('refresh');

            modal.modal('hide');
            modal.find('input[name="_element"]').val('');
        });

        /**
         * Esta acción se ejecuta despues de guardar una nueva empresa en la obra
         */
        work.saveBusinessWithAjax('#saveBusinessForm', function(error, result) {

            if (error !== null) {
                alert(error);
                return;
            }

            var data = result.data.business;
            var modal = $('#modalBusiness');
            var form = $('#saveBusinessForm');
            var elementId = form.find('input[name="_element"]').val();

            $(elementId).append($('<option>', {
                value: data.id,
                text: data.name,
            }).attr('selected', 'selected'));

            $(document).find(elementId + 'Name').val(data.name);
            $(elementId).selectpicker('refresh');

            modal.modal('hide');
            modal.find('input[name="_element"]').val('');
        });

        $('#modalBusiness').on('hide.bs.modal', function () {
            var modal = $(this);
            modal.find('input').not('input[name="_token"], input[name="cto34_work"], input[name="_from"]').val('');
            modal.find('select').val('');
        });

        $('#returnBusiness').on('click', function(event){
            event.preventDefault();

            $('#saveBusinessForm').hide();
            $('#saveSearchedBusinessForm').show();
        });

        /**
         *  Hacemos la busqueda para Firma por el cliente
         */
        work.searchPersonWithAjax('#cto34_customerSignature',
                                  {
            url: '{{ url("ajax/search/personsWork") }}',
            token: '{{ csrf_token() }}',
            workId: '{{ $works['one']->tbObraID }}',
            optionClass: 'option-newCustomerSignature',
            optionListClass: 'option-customerSignature'

        }, function(data) {

            if (data.action === 'newClicked') {
                beforeShowPersonModal(data.element);
            }

            if (data.action === 'optionClicked') {
                personOptionSelected(data.element);
            }
        }
                                 );

        /**
         *  Hacemos la busqueda para Repr. del cliente en obra
         */
        work.searchPersonWithAjax('#cto34_customerRepresentative',
                                  {
            url: '{{ url("ajax/search/personsWork") }}',
            token: '{{ csrf_token() }}',
            workId: '{{ $works['one']->tbObraID }}',
            optionClass: 'option-newCustomerRepresentative',
            optionListClass: 'option-customerRepresentative'

        }, function(data) {

            if (data.action === 'newClicked') {
                beforeShowPersonModal(data.element);
            }

            if (data.action === 'optionClicked') {
                personOptionSelected(data.element);
            }
        }
                                 );

        /**
         *  Hacemos la busqueda para Firma por el contratista
         */
        work.searchPersonWithAjax('#cto34_contractorSignature',
                                  {
            url: '{{ url("ajax/search/personsWork") }}',
            token: '{{ csrf_token() }}',
            workId: '{{ $works['one']->tbObraID }}',
            optionClass: 'option-newContractorSignature',
            optionListClass: 'option-contractorSignature'

        }, function(data) {

            if (data.action === 'newClicked') {
                beforeShowPersonModal(data.element);
            }

            if (data.action === 'optionClicked') {
                personOptionSelected(data.element);
            }
        }
                                 );

        /**
         *  Hacemos la busqueda para Responsable en obra Nothing selected
         */
        work.searchPersonWithAjax('#cto34_workManager',
                                  {
            url: '{{ url("ajax/search/personsWork") }}',
            token: '{{ csrf_token() }}',
            workId: '{{ $works['one']->tbObraID }}',
            optionClass: 'option-newWorkManager',
            optionListClass: 'option-workManager'

        }, function(data) {

            if (data.action === 'newClicked') {
                beforeShowPersonModal(data.element);
            }

            if (data.action === 'optionClicked') {
                personOptionSelected(data.element);
            }
        }
                                 );

        /**
         * Hacemos busqueda general de empresas para nueva persona
         */
        person.searchWithAjax('#cto34_searchPerson',
                              {
            url: '{{ url("ajax/search/persons") }}',
            token: '{{ csrf_token() }}',
            optionClass: 'option-newSearchPerson',
            optionListClass: 'option-searchPerson'

        }, function(data) {

            if (data.action === 'newClicked') {

                $('#savePersonForm').show();
                $('#saveSearchedPersonForm').hide();
            }

            if (data.action === 'optionClicked') {
                businessOptionSelected(data.element);
            }
        });


        $('#returnPerson').on('click', function(event){
            event.preventDefault();

            $('#savePersonForm').hide();
            $('#saveSearchedPersonForm').show();
        });

        work.savePersonWithAjax('#saveSearchedPersonForm', function(error, result) {

            if (error !== null) {
                alert(error);
                return;
            }

            var data = result.data.persons;
            var modal = $('#modalPerson');
            var form = $('#saveSearchedPersonForm');
            var elementId = form.find('input[name="_element"]').val();

            $(elementId).append($('<option>', {
                value: data.id,
                text: data.name,
            }).attr('selected', 'selected'));

            $(document).find(elementId + 'Name').val(data.name);
            $(elementId).selectpicker('refresh');

            modal.modal('hide');
            modal.find('input[name="_element"]').val('');
        });

        work.savePersonWithAjax('#savePersonForm', function(error, result) {

            if (error !== null) {
                alert(error);
                return;
            }

            var data = result.data.persons;
            var modal = $('#modalPerson');
            var form = $('#savePersonForm');
            var elementId = form.find('input[name="_element"]').val();

            $(elementId).append($('<option>', {
                value: data.id,
                text: data.name,
            }).attr('selected', 'selected'));

            $(document).find(elementId + 'Name').val(data.name);
            $(elementId).selectpicker('refresh');

            modal.modal('hide');
            modal.find('input[name="_element"]').val('');
        });

        var personForm = '#savePersonForm';

        person.addNameByLast(
            personForm + ' input[name="cto34_name"]',
            personForm + ' input[name="cto34_lastName"]',
            personForm + ' input[name="cto34_lastName2"]',
            personForm + ' input[name="cto34_nameByLast"]');
        person.addDirectName(
            personForm + ' input[name="cto34_name"]',
            personForm + ' input[name="cto34_lastName"]',
            personForm + ' input[name="cto34_lastName2"]',
            personForm + ' input[name="cto34_directName"]');
        person.addFullName(
            personForm + ' input[name="cto34_personPrefix"]',
            personForm + ' input[name="cto34_name"]',
            personForm + ' input[name="cto34_lastName"]',
            personForm + ' input[name="cto34_lastName2"]',
            personForm + ' input[name="cto34_fullName"]');


        $('#cto34_typeAgreement').change(function() {

            $('#cto34_originalContractName').prop('disabled', true);
            //$('#addendumAmount').hide();

            if ($(this).val() === "Addendum") {
                $('#cto34_originalContractName').prop('disabled', false);
                //$('#addendumAmount').show();
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

        $('#cto34_formatedDateED').focusout(function() {

            if ($('input[name="cto34_contractStartDate"]').val() === '') {
                return;
            }

            if ($('input[name="cto34_contractEndDate"]').val() === '') {
                return;
            }

            var start = moment($('input[name="cto34_contractStartDate"]').val(), 'YYYY-MM-DD');
            var end = moment($('input[name="cto34_contractEndDate"]').val(), 'YYYY-MM-DD');

            $('#cto34_contractDuration').val(end.diff(start, 'days')  + ' días');
        });

        $('#cto34_formatedDateCAED').focusout(function() {

            if ($('input[name="cto34_contractActualStartDate"]').val() === '') {
                return;
            }

            if ($('input[name="cto34_contractActualEndDate"]').val() === '') {
                return;
            }

            var start = moment($('input[name="cto34_contractActualStartDate"]').val(), 'YYYY-MM-DD');
            var end = moment($('input[name="cto34_contractActualEndDate"]').val(), 'YYYY-MM-DD');

            $('#cto34_contractActualDuration').val(end.diff(start, 'days')  + ' días');
        });

    })();

    function beforeShowBusinessModal(element) {
        var modal = $('#modalBusiness');

        modal.find('.modal-title').html($(element).data('modalTitle'));
        modal.find('input[name="_element"]').val(element);
        modal.modal('show');

        $(element).find('[value="0"]').remove();
        $(element).find('[value="-1"]').remove();
        $(element).selectpicker('refresh');
    }

    function businessOptionSelected(element) {
        var name = $(element).find('option:selected').text();
        $(element + 'Name').val(name);
    }

    function beforeShowPersonModal(element) {
        var modal = $('#modalPerson');

        modal.find('.modal-title').html($(element).data('modalTitle'));
        modal.find('input[name="_element"]').val(element);
        modal.modal('show');

        $(element).find('[value="0"]').remove();
        $(element).find('[value="-1"]').remove();
        $(element).selectpicker('refresh');
    }

    function personOptionSelected(element) {
        businessOptionSelected(element);
    }

    function customerSearchContractWithAjax(element, options) {

        var search = $(element);
        var inputSearch = search.find('.search-field');
        var inputSearchHidden = search.find('.search-hidden');
        var searchMessage = search.find('.search-message');
        var searchOptions = search.find('.search-result');
        var optionSelected = search.find('.search-option');

        $(inputSearch).on('keydown', function() {

            var inputSearch = $(this);

            var request = $.ajax({
                url: (options.hasOwnProperty('url')) ? options.url : '',
                type: 'post',
                dataType: 'html',
                timeout: 90000,
                cache: false,
                data: {
                    q: inputSearch.val(),
                    workId: (options.hasOwnProperty('workId')) ? options.workId : 0,
                },
                headers: {
                    'X-CSRF-TOKEN': (options.hasOwnProperty('token')) ? options.token : '',
                }
            });

            request.done(function(response) {

                var result = jQuery.parseJSON(response);

                searchMessage.nextAll('li').remove();

                if (result.contracts.length == 0 || result.contracts == null ) {
                    searchMessage.html((options.hasOwnProperty('noResults')) ? options.noResults : 'No hay coincidencias de tu busqueda');
                    searchOptions.html('');
                    return;
                }

                var resultList = '';
                var elementList;

                searchMessage.html('');

                $.each(result.contracts, function(index, value) {
                    elementList = $('<li></li>');
                    var elementLink = $('<a href="#" class="search-option" data-id="' + value.TbContratosID + '">' + value.ContratoAlias +'</a>');

                    elementLink.on('click', function(event) {
                        event.preventDefault();

                        var id = $(this).data('id');
                        var name = $(this).html();

                        inputSearch.val(name);
                        inputSearchHidden.val(id);

                        searchMessage.nextAll('li').remove();
                        searchMessage.html('');

                        $('#cto34_contractNumber').val(value.ContratoNumero);
                        $('#cto34_typeAssignment').val(value.ContratoAsignacionTipo);
                        $('#cto34_amountWithoutTax').val(value.ContratoImporteContratado);
                        $('#cto34_modality').val(value.ContratoModalidad);
                        $('#cto34_currency').val(value.tbMonedasID_Contrato);
                        $('#cto34_contractObjects').val(value.ContratoObjeto);
                        $('#cto34_shortScope').val(value.ContratoAlcanceCorto);

                    });

                    elementList.html(elementLink);
                    searchMessage.after(elementList);
                })

            });

            request.fail(function(xhr, textStatus) {
                searchMessage.html((options.hasOwnProperty('noResults')) ? options.error : 'Ocurrio un error');
            });

        }).focusout(function() {
            searchMessage.html('');
            searchOptions.html('');

            if (inputSearchHidden.val() == '') {
                inputSearch.val('');
            }

            setTimeout(function() {
                searchMessage.nextAll('li').remove();
                search.removeClass('open');
            }, 200);

        }).focus(function() {
            search.addClass('open');
        });


    }
</script>
@endpush