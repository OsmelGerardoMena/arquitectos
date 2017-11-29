@extends('layouts.base')

@push('styles_head')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-select.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-datetimepicker.min.css') }}">
@endpush

@section('content')
    {{--
        Menú de acciones

        Se muestra el título de la sección actual
        y los botones acción

        <navbar-actions>
    --}}
    <div class="navbar-actions">
        <div class="container">
            <div class="navbar navbar-static-top navbar-inverse margin-bottom--clear">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <p class="navbar-text">
                            {{ $page['title'] }} / Generadores / Nuevo
                        </p>
                    </div>
                    <div class="navbar-collapse collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li>
                                <a href="#" id="addSubmitButton" class="is-tooltip" data-placement="bottom" title="Guardar">
									<span class="fa-stack fa-lg">
										<i class="fa fa-circle fa-stack-2x"></i>
										<i class="fa fa-floppy-o fa-stack-1x fa-inverse"></i>
									</span>
                                </a>
                            </li>
                            <li class="li-works">
                                <a href="{{ $navigation['base'] }}/home" class="is-tooltip" data-placement="bottom" title="Obras">
									<span class="fa-stack fa-lg">
										<i class="fa fa-circle fa-stack-2x"></i>
										<i class="fa fa-th-list fa-stack-1x fa-inverse"></i>
									</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- </navbar-actions> --}}
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default panel-section bg-white">
                    @include('panel.constructionwork.shared.submenu')
                    <div class="panel-body padding-clear">
                        <div class="list-group col-sm-4 col-lg-3 margin-clear panel-item" style="position: relative; border-right: 0">
                        <!--<div class="list-group-item">
                                <form action="{{ $navigation['base'] }}/search" method="get">
                                    <div class="input-group">
                                        <input name="q" type="text" class="form-control form-control-plain" placeholder="Nombre de contrato o número" autocomplete="off">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="submit">
                                                <span class="fa fa-search fa-fw"></span>
                                            </button>
                                        </span>
                                    </div>
                                </form>
                            </div>-->
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" class="list-group-item collapsed">
                                Datos del catálogo
                            </a>
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" class="list-group-item">
                                Datos básicos del generador
                            </a>
                        </div>
                        <div class="col-sm-8 col-lg-9 panel-item" style="position: relative; border-left: 1px solid #ddd;">
                            <form id="saveForm" action="{{ $navigation['base'] }}/action/save" method="post" accept-charset="utf-8" class="form-inline row margin-bottom--20">
                                <div class="form-group col-sm-12">
                                    @include('layouts.alerts', ['errors' => $errors])
                                </div>
                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                    <div class="panel panel-default" style="border: 0; box-shadow: none">
                                        <div class="panel-heading" role="tab" id="partOne" style="background-color: #fafafa;">
                                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                Datos del catálogo
                                            </a>
                                        </div>
                                        <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="partOne">
                                            <div class="panel-body" style="border: 0">
                                                <div class="form-group form-group-table col-sm-12 col-md-6">
                                                    <div id="directCustomer" class="dropdown search-ajax form-group-row">
                                                        <label class="form-group-cell">Catálogo</label>
                                                        <input id="cto34_directCustomerName"
                                                               name="cto34_directCustomerName"
                                                               type="text"
                                                               value="{{ old('cto34_directCustomerName') }}"
                                                               placeholder="Ingresar nombre de catálogo"
                                                               autocomplete="off"
                                                               tabindex="1"
                                                               class="form-control form-group-cell search-field input-sm">
                                                        <input id="cto34_directCustomer" name="cto34_directCustomer" type="hidden" value="{{ old('cto34_directCustomer') }}" class="search-hidden">
                                                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                                                            <li>
                                                                <a href="#" class="search-new" data-id="#directCustomer" data-toggle="modal" data-target="#modalBusiness">Agregar empresa</a>
                                                            </li>
                                                            <li class="dropdown-header search-message"></li>
                                                            <div class="search-result"></div>
                                                        </ul>
                                                    </div>
                                                    <div class="form-group-row">
                                                        <label for="cto34_departure" class="form-group-cell">Partida</label>
                                                        <select name="cto34_departure"
                                                                id="cto34_departure"
                                                                tabindex="2"
                                                                class="form-control form-group-cell input-sm">
                                                            <option value="0">Seleccionar opción</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group-row">
                                                        <label for="cto34_conceptLocation" class="form-group-cell">Ubicación</label>
                                                        <select name="cto34_conceptLocation"
                                                                id="cto34_conceptLocation"
                                                                tabindex="4"
                                                                class="form-control form-group-cell input-sm">
                                                            <option value="0">Seleccionar opción</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-table col-sm-12 col-md-6">
                                                    <div class="form-group-row">
                                                        <label for="cto34_Subheading" class="form-group-cell form-group-space"></label>
                                                        <p class="form-group-cell form-group-space"></p>
                                                    </div>
                                                    <div class="form-group-row">
                                                        <label for="cto34_Subheading" class="form-group-cell">Subpartida</label>
                                                        <select name="cto34_Subheading"
                                                                id="cto34_Subheading"
                                                                tabindex="3"
                                                                class="form-control form-group-cell input-sm">
                                                            <option value="0">Seleccionar opción</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group-row">
                                                        <label for="cto34_workType" class="form-group-cell">Tipo de obra</label>
                                                        <select name="cto34_workType"
                                                                id="cto34_workType"
                                                                tabindex="5"
                                                                class="form-control form-group-cell input-sm">
                                                            <option value="0">Seleccionar opción</option>
                                                            <option value="1">Normal</option>
                                                            <option value="2">Adicional</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="form-group col-sm-12 col-md-6">
                                                    <div class="row">
                                                        <div class="form-group form-group-table col-sm-12">
                                                            <div class="form-group-row">
                                                                <label for="cto34_code" class="form-group-cell">Código</label>
                                                                <input id="cto34_code"
                                                                       name="cto34_code"
                                                                       type="text"
                                                                       value="{{ old('cto34_code') }}"
                                                                       tabindex="6"
                                                                       class="form-control form-control-plain form-group-cell input-sm">
                                                            </div>
                                                            <div class="form-group-row">
                                                                <label for="cto34_description" class="form-group-cell">Descripción</label>
                                                                <textarea id="cto34_description" 
                                                                          maxlength="4000"
                                                                          name="cto34_description"
                                                                          rows="4"
                                                                          tabindex="7"
                                                                          class="form-control form-group-cell form-control-plain">{{ old('cto34_description') }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-sm-12">

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-12 col-md-6">
                                                    <div class="row">
                                                        <div class="form-group col-sm-6 margin-bottom--10">
                                                            <label for="cto34_unity" class="form-label-full">Unidad</label>
                                                            <select name="cto34_unity"
                                                                    id="cto34_unity"
                                                                    tabindex="8"
                                                                    class="form-control form-control-full input-sm">
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
                                                                   type="text"
                                                                   value="{{ old('cto34_quantity') }}"
                                                                   tabindex="9"
                                                                   class="form-control form-control-plain form-control-full input-sm">
                                                        </div>
                                                        <div class="col-sm-6 margin-bottom--10">
                                                            <label for="cto34_unitPrice" class="form-label-full">Precio unitario</label>
                                                            <input id="cto34_unitPrice"
                                                                   name="cto34_unitPrice"
                                                                   type="text"
                                                                   value="{{ old('cto34_unitPrice') }}"
                                                                   tabindex="10"
                                                                   class="form-control form-control-plain form-control-full input-sm">
                                                        </div>
                                                        <div class="col-sm-6 margin-bottom--10">
                                                            <label for="cto34_amount" class="form-label-full">Importe</label>
                                                            <input id="cto34_amount"
                                                                   name="cto34_amount"
                                                                   type="text"
                                                                   value="{{ old('cto34_amount') }}"
                                                                   tabindex="11"
                                                                   class="form-control form-control-plain form-control-full input-sm">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-default" style="border: 0; box-shadow: none">
                                        <div class="panel-heading" role="tab" id="partTwo" style="background-color: #fafafa">
                                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" tabindex="12">
                                                Datos básicos del generador
                                            </a>
                                        </div>
                                        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="partTwo">
                                            <div class="panel-body">
                                                <div class="form-group col-sm-6">
                                                    <div id="contractorSignature" class="dropdown search-ajax">
                                                        <label class="form-label-full">Recibido por</label>
                                                        <input id="cto34_contractorSignatureName"
                                                               name="cto34_contractorSignatureName"
                                                               type="text"
                                                               value="{{ old('cto34_contractorSignatureName') }}"
                                                               placeholder="Ingresar nombre de persona"
                                                               autocomplete="off"
                                                               tabindex="13"
                                                               class="form-control search-field input-sm">
                                                        <input id="cto34_contractorSignature" name="cto34_contractorSignature" type="hidden" value="{{ old('cto34_contractorSignature') }}" class="search-hidden">
                                                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                                                            <li>
                                                                <a href="#" class="search-new" data-id="#contractorSignature" data-toggle="modal" data-target="#modalPerson">Agregar persona</a>
                                                            </li>
                                                            <li class="dropdown-header search-message"></li>
                                                            <div class="search-result"></div>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="cto34_unity" class="form-label-full">Unidad</label>
                                                    <select name="cto34_unity" id="cto34_unity" class="form-control input-sm">
                                                        <option value="0">Seleccionar opción</option>
                                                        @foreach($unities['all'] as $unity)
                                                            <option value="{{ $unity->tbUnidadID  }}">{{ $unity->UnidadAlias }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-6 margin-bottom--10">
                                                    <label for="cto34_unity" class="form-label-full">Unidad</label>
                                                    <select name="cto34_unity" id="cto34_unity" class="form-control input-sm">
                                                        <option value="0">Seleccionar opción</option>
                                                        @foreach($unities['all'] as $unity)
                                                            <option value="{{ $unity->tbUnidadID  }}">{{ $unity->UnidadAlias }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="cto34_workType" class="form-label-full">Tipo de obra</label>
                                                    <select name="cto34_workType" id="cto34_typeWork" class="form-control input-sm">
                                                        <option value="0">Seleccionar opción</option>
                                                        <option value="1">Normal</option>
                                                        <option value="2">Adicional</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="cto34_presentedQuantity" class="form-label-full">Cantidad presentada</label>
                                                    <input id="cto34_presentedQuantity"
                                                           name="cto34_presentedQuantity"
                                                           type="text"
                                                           value="{{ old('cto34_presentedQuantity') }}"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="cto34_conceptLocation" class="form-label-full">Ubicación del concepto</label>
                                                    <select name="cto34_conceptLocation" id="cto34_conceptLocation" class="form-control input-sm">
                                                        <option value="0">Seleccionar opción</option>
                                                    </select>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="form-group col-sm-6">
                                                    <label for="cto34_signatureDate" class="form-label-full">Fecha recibido</label>
                                                    <div class="input-group input-group-sm date-field">
                                                        <input id="cto34_signatureDate"
                                                               name="cto34_signatureDate"
                                                               type="text"
                                                               value="{{ old('cto34_signatureDate') }}"
                                                               readonly="readonly"
                                                               class="form-control form-control-plain input-sm">
                                                        <span class="input-group-addon" style="background-color: #fff">
                                                            <span class="fa fa-calendar fa-fw text-primary"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="cto34_signatureDate" class="form-label-full">Fecha regresado a contratista</label>
                                                    <div class="input-group input-group-sm date-field">
                                                        <input id="cto34_signatureDate"
                                                               name="cto34_signatureDate"
                                                               type="text"
                                                               value="{{ old('cto34_signatureDate') }}"
                                                               readonly="readonly"
                                                               class="form-control form-control-plain input-sm">
                                                        <span class="input-group-addon" style="background-color: #fff">
                                                            <span class="fa fa-calendar fa-fw text-primary"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <div id="customerSignature" class="dropdown search-ajax">
                                                        <label class="form-label-full">Revisado por</label>
                                                        <input id="cto34_contractorSignatureName"
                                                               name="cto34_contractorSignatureName"
                                                               type="text"
                                                               value="{{ old('cto34_contractorSignatureName') }}"
                                                               placeholder="Ingresar nombre de persona"
                                                               autocomplete="off"
                                                               class="form-control search-field input-sm">
                                                        <input id="cto34_contractorSignature" name="cto34_contractorSignature" type="hidden" value="{{ old('cto34_contractorSignature') }}" class="search-hidden">
                                                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                                                            <li>
                                                                <a href="#" class="search-new" data-id="#contractorSignature" data-toggle="modal" data-target="#modalPerson">Agregar persona</a>
                                                            </li>
                                                            <li class="dropdown-header search-message"></li>
                                                            <div class="search-result"></div>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="cto34_signatureDate" class="form-label-full">Fecha de revisión</label>
                                                    <div class="input-group input-group-sm date-field">
                                                        <input id="cto34_signatureDate"
                                                               name="cto34_signatureDate"
                                                               type="text"
                                                               value="{{ old('cto34_signatureDate') }}"
                                                               readonly="readonly"
                                                               class="form-control form-control-plain input-sm">
                                                        <span class="input-group-addon" style="background-color: #fff">
                                                            <span class="fa fa-calendar fa-fw text-primary"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-4">
                                                    <label for="cto34_presentedQuantity" class="form-label-full">Cantidad revisada</label>
                                                    <input id="cto34_presentedQuantity"
                                                           name="cto34_presentedQuantity"
                                                           type="text"
                                                           value="{{ old('cto34_presentedQuantity') }}"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <div class="form-group col-sm-4">
                                                    <label for="cto34_presentedQuantity" class="form-label-full">Diferencia vs presentado</label>
                                                    <input id="cto34_presentedQuantity"
                                                           name="cto34_presentedQuantity"
                                                           type="text"
                                                           value="{{ old('cto34_presentedQuantity') }}"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <div class="form-group col-sm-4">
                                                    <label for="cto34_presentedQuantity" class="form-label-full">Motivo de las diferencias</label>
                                                    <select name="cto34_presentedQuantity" id="cto34_presentedQuantity" class="form-control input-sm">
                                                        <option value="0">Seleccionar opción</option>
                                                        <option value="1">Normal</option>
                                                        <option value="2">Adicional</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <div id="authPerson" class="dropdown search-ajax">
                                                        <label class="form-label-full">Autorizado por</label>
                                                        <input id="cto34_contractorSignatureName"
                                                               name="cto34_contractorSignatureName"
                                                               type="text"
                                                               value="{{ old('cto34_contractorSignatureName') }}"
                                                               placeholder="Ingresar nombre de persona"
                                                               autocomplete="off"
                                                               class="form-control search-field input-sm">
                                                        <input id="cto34_contractorSignature" name="cto34_contractorSignature" type="hidden" value="{{ old('cto34_contractorSignature') }}" class="search-hidden">
                                                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                                                            <li>
                                                                <a href="#" class="search-new" data-id="#contractorSignature" data-toggle="modal" data-target="#modalPerson">Agregar persona</a>
                                                            </li>
                                                            <li class="dropdown-header search-message"></li>
                                                            <div class="search-result"></div>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="cto34_signatureDate" class="form-label-full">Fecha autorizado</label>
                                                    <div class="input-group input-group-sm date-field">
                                                        <input id="cto34_signatureDate"
                                                               name="cto34_signatureDate"
                                                               type="text"
                                                               value="{{ old('cto34_signatureDate') }}"
                                                               readonly="readonly"
                                                               class="form-control form-control-plain input-sm">
                                                        <span class="input-group-addon" style="background-color: #fff">
                                                            <span class="fa fa-calendar fa-fw text-primary"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-4">
                                                    <label for="cto34_presentedQuantity" class="form-label-full">Cantidad autorizada</label>
                                                    <input id="cto34_presentedQuantity"
                                                           name="cto34_presentedQuantity"
                                                           type="text"
                                                           value="{{ old('cto34_presentedQuantity') }}"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
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
        app.animateSubmit("saveForm", "addSubmitButton");
        app.scrollNavActions();
        app.tooltip();
        app.dateTimePickerField();

        var work = new ConstructionWork();

        work.searchBusinessWithAjax('#contractor', { url: '{{ url("ajax/search/businessWork") }}', token: '{{ csrf_token() }}' });
        work.searchContractWithAjax('#contract', { url: '{{ url("ajax/action/search/contracts") }}', token: '{{ csrf_token() }}', workId: '{{ $works['one']->tbObraID   }}' });
        work.searchPersonWithAjax('#customerSignature', { url: '{{ url("ajax/search/personsWork") }}', token: '{{ csrf_token() }}' });
    })();
</script>
@endpush