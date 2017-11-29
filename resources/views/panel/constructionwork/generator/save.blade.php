@extends('layouts.base')

@push('styles_head')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-select.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-datetimepicker.min.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default panel-section bg-white">
                    <div class="col-sm-8">
                        @include('panel.constructionwork.shared.submenu')
                    </div>
                    <div class="col-sm-4">
                        <ul class="nav nav-actions navbar-nav navbar-right">
                            <li>
                                <a class="disabled">
									<span class="fa-stack fa-lg">
										<i class="fa fa-circle fa-stack-2x"></i>
										<i class="fa fa-plus fa-stack-1x fa-inverse"></i>
									</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" id="addSubmitButton" class="is-tooltip" data-placement="bottom" title="Guardar">
									<span class="fa-stack fa-lg">
										<i class="fa fa-circle fa-stack-2x"></i>
										<i class="fa fa-floppy-o fa-stack-1x fa-inverse"></i>
									</span>
                                </a>
                            </li>
                            <li>
                                <a class="disabled">
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="disabled">
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <span class="text-danger"><i class="fa fa-trash fa-stack-1x fa-inverse"></i></span>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ $navigation['base'] }}" class="is-tooltip" data-placement="bottom" title="Cerrar">
                                <span class="fa-stack text-danger fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-times fa-stack-1x fa-inverse"></i>
                                </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                    <div class="panel-body padding-top--clear">
                        <div class="list-group col-sm-5 col-md-4 col-lg-3 margin-clear panel-item" style="position: relative; border-right: 0">
                            <div class="list-group-item padding-clear padding-bottom--5">
                                <form action="{{ $navigation['base'].'/search' }}" method="get">
                                    <div class="input-group input-group-sm">
                                        <input name="q" type="text" class="form-control form-control-plain" placeholder="Busqueda" autocomplete="off">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="submit">
                                                <span class="fa fa-search fa-fw"></span>
                                            </button>
                                            <button class="btn btn-default" type="button">
                                                <span class="fa fa-filter fa-fw"></span>
                                            </button>
                                        </span>
                                    </div>
                                </form>
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
                                @foreach($generators['all'] as $generator)
                                    <a href="{{ $navigation['base'] }}/info/{{ $generator->tbGeneradorID }}" class="list-group-item">
                                        <h4 class="list-group-item-heading">{{ $generator->GeneradorFolio }}</h4>
                                        <p class="small">
                                            Catálogo {{ $generator->tbCatalogoID_Generador }}
                                        </p>
                                    </a>
                                @endforeach
                            </div>
                            <div class="list-group-item padding-clear padding-top--10">
                                <div class="row">
                                    <div class="col-sm-12 text-center">
                                        <a href="{{ $navigation['pagination']['prev']  }}" class="btn btn-default btn-xs">
                                            <span class="fa fa-caret-left fa-fw"></span>
                                        </a>
                                        {{ $navigation['pagination']['current']  }} / {{ $navigation['pagination']['last']  }}
                                        <a href="{{ $navigation['pagination']['next']  }}" class="btn btn-default btn-xs">
                                            <span class="fa fa-caret-right fa-fw"></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-7 col-md-8 col-lg-9 panel-item" style="position: relative;">
                            <div class="col-sm-12">
                                @include('layouts.alerts', ['errors' => $errors])
                            </div>
                            <div class="clarfix"></div>
                            <ul class="nav nav-tabs nav-tabs-works" role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#catalog" aria-controls="catalog" role="tab" data-toggle="tab">
                                        Datos del catálogo
                                    </a>
                                </li>
                                <li role="presentation">
                                    <a href="#capture" data-target="#capture" aria-controls="capture" role="tab" data-toggle="tab">
                                        Captura
                                    </a>
                                </li>
                                <li role="presentation" class="disabled">
                                    <a>
                                        Revisión
                                    </a>
                                </li>
                                <li role="presentation" class="disabled">
                                    <a>
                                        Autorización
                                    </a>
                                </li>
                                <li role="presentation" class="disabled">
                                    <a>
                                        Regresado
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content margin-bottom--20 padding-top--5">
                                <div role="tabpanel" class="tab-pane active" id="catalog">
                                    <form id="saveForm" action="{{ $navigation['base'].'/action/save' }}" method="post" accept-charset="utf-8" class="row">
                                        <div class="form-group col-sm-6">
                                            <div id="directCustomer" class="dropdown search-ajax">
                                                <label class="form-label-full">Catálogo</label>
                                                <div class="input-group input-group-sm">
                                                    <input name="cto34_catalog" type="text" class="form-control" readonly="readonly" value="{{ old('cto34_catalog') }}">
                                                    <div class="input-group-btn">
                                                        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalCatalogs">
                                                            Buscar concepto
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_departure" class="form-label-full">Partida</label>
                                            <input id="cto34_departure"
                                                   name="cto34_departure"
                                                   type="text"
                                                   value="{{ old('cto34_departure') }}"
                                                   readonly="readonly"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_subHeading" class="form-label-full">Subpartida</label>
                                            <input id="cto34_subHeading"
                                                   name="cto34_subHeading"
                                                   type="text"
                                                   value="{{ old('cto34_subHeading') }}"
                                                   readonly="readonly"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_level" class="form-label-full">Ubicación</label>
                                            <input id="cto34_level"
                                                   name="cto34_level"
                                                   type="text"
                                                   value="{{ old('cto34_level') }}"
                                                   readonly="readonly"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_workType" class="form-label-full">Tipo de obra</label>
                                            <input id="cto34_workType"
                                                   name="cto34_workType"
                                                   type="text"
                                                   value="{{ old('cto34_workType') }}"
                                                   readonly="readonly"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-sm-6">
                                            <div class="row">
                                                <div class="form-group col-sm-12">
                                                    <label for="cto34_code" class="form-label-full">Código</label>
                                                    <input id="cto34_code"
                                                           name="cto34_code"
                                                           type="text"
                                                           value="{{ old('cto34_code') }}"
                                                           readonly="readonly"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label for="cto34_description" class="form-label-full">Descripción</label>
                                                    <textarea id="cto34_description"
                                                              name="cto34_description"
                                                              rows="5" 
                                                              maxlength="4000"                                                              
                                                              readonly="readonly"
                                                              class="form-control form-control-plain">{{ old('cto34_description') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <div class="row">
                                                <div class="col-sm-6 margin-bottom--10">
                                                    <label for="cto34_unity" class="form-label-full">Unidad</label>
                                                    <input id="cto34_unity"
                                                           name="cto34_unity"
                                                           type="text"
                                                           value="{{ old('cto34_unity') }}"
                                                           readonly="readonly"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <div class="col-sm-6 margin-bottom--10">
                                                    <label for="cto34_quantity" class="form-label-full">Cantidad</label>
                                                    <input id="cto34_quantity"
                                                           name="cto34_quantity"
                                                           type="text"
                                                           value="{{ old('cto34_quantity') }}"
                                                           readonly="readonly"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <div class="col-sm-6 margin-bottom--10">
                                                    <label for="cto34_unitPrice" class="form-label-full">Precio unitario</label>
                                                    <input id="cto34_unitPrice"
                                                           name="cto34_unitPrice"
                                                           type="text"
                                                           value="{{ old('cto34_unitPrice') }}"
                                                           readonly="readonly"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <div class="col-sm-6 margin-bottom--10">
                                                    <label for="cto34_amount" class="form-label-full">Importe</label>
                                                    <input id="cto34_amount"
                                                           name="cto34_amount"
                                                           type="text"
                                                           value="{{ old('cto34_amount') }}"
                                                           readonly="readonly"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="capture">
                                    <form id="saveCaptureForm" action="{{ $navigation['base'] }}/action/save" method="post" accept-charset="utf-8" class="row">
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
                                                <span class="input-group-btn">
                                                    <button id="calendarToday" type="button" class="btn btn-default">Hoy</button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <div id="contractorSignature" class="dropdown search-ajax">
                                                <label class="form-label-full">Recibido por</label>
                                                <div class="input-group input-group-sm">
                                                    <select id="cto34_reciver"
                                                            name="cto34_reciver"
                                                            data-live-search="true"
                                                            data-width="100%"
                                                            data-style="btn-sm btn-default"
                                                            data-modal-title="Cliente directo"
                                                            class="selectpicker with-ajax">
                                                        @if(!empty(old('cto34_reciver')))
                                                            <option value="{{ old('cto34_reciver') }}" selected="selected">
                                                                {{ old('cto34_reciver')  }}
                                                            </option>
                                                        @endif
                                                    </select>
                                                    <input type="hidden" id="cto34_reciverName" name="cto34_reciverName" value="{{ old('cto34_reciverName')  }}">
                                                    <span class="input-group-btn">
                                                        <button id="personMe" type="button" class="btn btn-default" data-id="{{ Auth::id() }}" data-name="{{ Auth::user()['person']->PersonaNombreDirecto }}">Mi</button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 margin-bottom--10">
                                            <h5 class="page-header margin-clear">Revisión preliminar</h5>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_departure" class="form-label-full">Partida del generador</label>
                                            <select name="cto34_departure" id="cto34_departureCapture" class="form-control input-sm need-verify">
                                                <option value="" class="option-default">Seleccionar opción</option>
                                                {{--@if(!empty(old('cto34_departureName')))
                                                    <optgroup label="Partida seleccionada">
                                                        <option value="{{ old('cto34_departure') }}">
                                                            {{ old('cto34_departureName') }}
                                                        </option>
                                                    </optgroup>
                                                @endif --}}
                                                @foreach($departures['all'] as $departure)
                                                    @if($departure->tbPartidaID == old('cto34_departureCapture'))
                                                        <option value="{{ $departure->tbPartidaID  }}" selected="selected">{{ $departure->PartidaLabel  }}</option>
                                                        @continue
                                                    @endif
                                                    <option value="{{ $departure->tbPartidaID  }}">{{ $departure->PartidaLabel  }}</option>
                                                @endforeach
                                                <option value="-1">Agregar nueva partida</option>
                                            </select>
                                            <input id="cto34_departureCaptureName"
                                                   name="cto34_departureCaptureName"
                                                   type="hidden"
                                                   value="{{ old('cto34_departureCaptureName') }}">
                                            <span class="help-block text-danger small"></span>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_subDeparture" class="form-label-full">Subpartida del generador</label>
                                                <select name="cto34_subdeparture" id="cto34_subdepartureCapture" class="form-control input-sm need-verify" disabled="disabled">
                                                    @if(!empty(old('cto34_subdepartureCaptureName')))
                                                        <optgroup label="Subpartida seleccionada">
                                                            <option value="{{ old('cto34_subdepartureCapture') }}" selected="selected">{{ old('cto34_subdepartureCaptureName') }}</option>
                                                        </optgroup>
                                                    @endif
                                                    <option value="0" class="option-default">Seleccionar opción</option>
                                                    <option value="-1">Agregar nueva subpartida</option>
                                                </select>
                                                <input id="cto34_subdepartureCaptureName"
                                                       name="cto34_subdepartureCaptureName"
                                                       type="hidden"
                                                       value="{{ old('cto34_subdepartureCaptureName') }}">
                                            <span class="help-block text-danger small"></span>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_workType" class="form-label-full">Tipo de obra del generador</label>
                                            <select name="cto34_workType" id="cto34_typeWorkCapture" class="form-control input-sm need-verify">
                                                <option value="">Seleccionar opción</option>
                                                <option value="Normal">Normal</option>
                                                <option value="Adicional">Adicional</option>
                                            </select>
                                            <span class="help-block text-danger small"></span>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_conceptLocation" class="form-label-full">Ubicación de lo generado</label>
                                            <select name="cto34_level" id="cto34_buildingCapture" class="form-control input-sm need-verify">
                                                <option value="">Seleccionar opción</option>
                                                @foreach($levels['all'] as $level)
                                                    <option value="{{ $level->tbUbicaNivelID }}">{{ $level->UbicaNivelAlias }}</option>
                                                @endforeach
                                            </select>
                                            <span class="help-block text-danger small"></span>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_unity" class="form-label-full">Unidad del generador</label>
                                            <select name="cto34_unity" id="cto34_unityCapture" class="form-control input-sm need-verify">
                                                <option value="0">Seleccionar opción</option>
                                                @foreach($unities['all'] as $unity)
                                                    <option value="{{ $unity->tbUnidadID  }}">{{ $unity->UnidadAlias }}</option>
                                                @endforeach
                                            </select>
                                            <span class="help-block text-danger small"></span>
                                        </div>
                                        <div class="col-sm-12 margin-bottom--10">
                                            <h5 class="page-header margin-clear">Cantidad presentada</h5>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_presentedQuantity" class="form-label-full">Cantidad presentada en este generador</label>
                                            <input id="cto34_presentedQuantity"
                                                   name="cto34_presentedQuantity"
                                                   type="text"
                                                   value="{{ old('cto34_presentedQuantity') }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_totalPresentedQuantity" class="form-label-full">Cantidad total presentada</label>
                                            <input id="cto34_totalPresentedQuantity"
                                                   name="cto34_totalPresentedQuantity"
                                                   type="text"
                                                   value="{{ old('cto34_totalPresentedQuantity') }}"
                                                   readonly="readonly"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_catalogQuantity" class="form-label-full">Cantidad en catálogo</label>
                                            <input id="cto34_catalogQuantity"
                                                   name="cto34_catalogQuantity"
                                                   type="text"
                                                   value="{{ old('cto34_catalogQuantity') }}"
                                                   readonly="readonly"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_exp" class="form-label-full">Cantidad rematante (catálogo - presentado)</label>
                                            <input id="cto34_exp"
                                                   name="cto34_exp"
                                                   type="text"
                                                   value="{{ !empty(old('cto34_exp')) ? old('cto34_exp') : '0.00' }}"
                                                   readonly="readonly"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                                        <input type="hidden" name="cto34_catalog" value="{{ old('cto34_catalog') }}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    </form>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="revision">
                                    <form action="" class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th>Este generador</th>
                                                            <th>Acum. anterios</th>
                                                            <th>Acum. a éste folio</th>
                                                            <th>Acumulado total</th>
                                                            <th>Importes</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Cantidad presentada</td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control" readonly="readonly">
                                                                    <div class="input-group-addon">
                                                                        <span>0.0%</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control" readonly="readonly">
                                                                    <div class="input-group-addon">
                                                                        <span>0.0%</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control" readonly="readonly">
                                                                    <div class="input-group-addon">
                                                                        <span>0.0%</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control" readonly="readonly">
                                                                    <div class="input-group-addon">
                                                                        <span>0.0%</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control input-sm" readonly="readonly">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Cantidad revisada</td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control">
                                                                    <div class="input-group-addon">
                                                                        <span>0.0%</span>
                                                                    </div>
                                                                </div>
                                                                <button class="btn btn-default btn-block btn-xs">
                                                                    Misma
                                                                </button>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control" readonly="readonly">
                                                                    <div class="input-group-addon">
                                                                        <span>0.0%</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control" readonly="readonly">
                                                                    <div class="input-group-addon">
                                                                        <span>0.0%</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control" readonly="readonly">
                                                                    <div class="input-group-addon">
                                                                        <span>0.0%</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control input-sm" readonly="readonly">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Cantidad rechazada</td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control" readonly="readonly">
                                                                    <div class="input-group-addon">
                                                                        <span>0.0%</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control" readonly="readonly">
                                                                    <div class="input-group-addon">
                                                                        <span>0.0%</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control" readonly="readonly">
                                                                    <div class="input-group-addon">
                                                                        <span>0.0%</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control" readonly="readonly">
                                                                    <div class="input-group-addon">
                                                                        <span>0.0%</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control input-sm" readonly="readonly">
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_reasonDifference">Motivo de las diferencias</label>
                                            <select name="cto34_reasonDifference" id="cto34_reasonDifference" class="form-control input-sm">
                                                <option value="">Seleccionar opción</option>
                                                <option value="Generador repetido">Generador repetido</option>
                                                <option value="Incluido en P.U.">Incluido en P.U.</option>
                                                <option value="Mal cuantificado">Mal cuantificado</option>
                                                <option value="Operaciones con errores">Operaciones con errores</option>
                                                <option value="Trabajo no autorizado">Trabajo no autorizado</option>
                                            </select>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_reviserDate" class="form-label-full">Fecha de la revisión</label>
                                            <div class="input-group input-group-sm date-field">
                                                <input id="cto34_reviserDate"
                                                       name="cto34_reviserDate"
                                                       type="text"
                                                       value="{{ old('cto34_reviserDate') }}"
                                                       readonly="readonly"
                                                       class="form-control form-control-plain input-sm">
                                                <span class="input-group-addon" style="background-color: #fff">
                                                    <span class="fa fa-calendar fa-fw text-primary"></span>
                                                </span>
                                                <span class="input-group-btn">
                                                    <button id="calendarReviserToday" type="button" class="btn btn-default">Hoy</button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <div id="personReviser" class="dropdown search-ajax">
                                                <label class="form-label-full">Revisó</label>
                                                <div class="input-group input-group-sm">
                                                    <input id="cto34_personReviserName"
                                                           name="cto34_personReviserName"
                                                           type="text"
                                                           value="{{ old('cto34_personReviserName') }}"
                                                           placeholder="Ingresar nombre de persona"
                                                           autocomplete="off"
                                                           class="form-control search-field">
                                                    <input id="cto34_personReviser" name="cto34_personReviser" type="hidden" value="{{ old('cto34_personReviser') }}" class="search-hidden">
                                                    <span class="input-group-btn">
                                                        <button id="personReviserMe" type="button" class="btn btn-default" data-id="{{ Auth::id() }}" data-name="{{ Auth::user()['person']->PersonaNombreDirecto }}">Mi</button>
                                                    </span>
                                                </div>
                                                <ul class="dropdown-menu" aria-labelledby="dLabel">
                                                    <li>
                                                        <a href="#" class="search-new" data-id="#personReviser" data-toggle="modal" data-target="#modalPerson">Agregar persona</a>
                                                    </li>
                                                    <li class="dropdown-header search-message"></li>
                                                    <div class="search-result"></div>
                                                </ul>
                                            </div>
                                        </div>
                                        <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    </form>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="authorization">
                                    <form action="" class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>Este generador</th>
                                                        <th>Acum. anterios</th>
                                                        <th>Acum. a éste folio</th>
                                                        <th>Acumulado total</th>
                                                        <th>Importes</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>Cantidad presentada</td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" class="form-control" readonly="readonly">
                                                                <div class="input-group-addon">
                                                                    <span>0.0%</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" class="form-control" readonly="readonly">
                                                                <div class="input-group-addon">
                                                                    <span>0.0%</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" class="form-control" readonly="readonly">
                                                                <div class="input-group-addon">
                                                                    <span>0.0%</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" class="form-control" readonly="readonly">
                                                                <div class="input-group-addon">
                                                                    <span>0.0%</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control input-sm" readonly="readonly">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Cantidad revisada</td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" class="form-control" readonly="readonly">
                                                                <div class="input-group-addon">
                                                                    <span>0.0%</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" class="form-control" readonly="readonly">
                                                                <div class="input-group-addon">
                                                                    <span>0.0%</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" class="form-control" readonly="readonly">
                                                                <div class="input-group-addon">
                                                                    <span>0.0%</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" class="form-control" readonly="readonly">
                                                                <div class="input-group-addon">
                                                                    <span>0.0%</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control input-sm" readonly="readonly">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Cantidad autorizada</td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" class="form-control">
                                                                <div class="input-group-addon">
                                                                    <span>0.0%</span>
                                                                </div>
                                                            </div>
                                                            <button class="btn btn-default btn-block btn-xs">
                                                                Misma
                                                            </button>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" class="form-control" readonly="readonly">
                                                                <div class="input-group-addon">
                                                                    <span>0.0%</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" class="form-control" readonly="readonly">
                                                                <div class="input-group-addon">
                                                                    <span>0.0%</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" class="form-control" readonly="readonly">
                                                                <div class="input-group-addon">
                                                                    <span>0.0%</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control input-sm" readonly="readonly">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Cantidad rechazada</td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" class="form-control" readonly="readonly">
                                                                <div class="input-group-addon">
                                                                    <span>0.0%</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" class="form-control" readonly="readonly">
                                                                <div class="input-group-addon">
                                                                    <span>0.0%</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" class="form-control" readonly="readonly">
                                                                <div class="input-group-addon">
                                                                    <span>0.0%</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" class="form-control" readonly="readonly">
                                                                <div class="input-group-addon">
                                                                    <span>0.0%</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control input-sm" readonly="readonly">
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_reasonDifference">Motivo de las diferencias</label>
                                            <select name="cto34_reasonDifference" id="cto34_reasonDifference" class="form-control input-sm">
                                                <option value="">Seleccionar opción</option>
                                                <option value="Generador repetido">Generador repetido</option>
                                                <option value="Incluido en P.U.">Incluido en P.U.</option>
                                                <option value="Mal cuantificado">Mal cuantificado</option>
                                                <option value="Operaciones con errores">Operaciones con errores</option>
                                                <option value="Trabajo no autorizado">Trabajo no autorizado</option>
                                            </select>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_authorizerDate" class="form-label-full">Fecha de autorización</label>
                                            <div class="input-group input-group-sm date-field">
                                                <input id="cto34_authorizerDate"
                                                       name="cto34_authorizerDate"
                                                       type="text"
                                                       value="{{ old('cto34_authorizerDate') }}"
                                                       readonly="readonly"
                                                       class="form-control form-control-plain input-sm">
                                                <span class="input-group-addon" style="background-color: #fff">
                                                    <span class="fa fa-calendar fa-fw text-primary"></span>
                                                </span>
                                                <span class="input-group-btn">
                                                    <button id="calendarAuthorizerToday" type="button" class="btn btn-default">Hoy</button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <div id="personAuthorizer" class="dropdown search-ajax">
                                                <label class="form-label-full">Autorizó</label>
                                                <div class="input-group input-group-sm">
                                                    <input id="cto34_personAuthorizerName"
                                                           name="cto34_personAuthorizerName"
                                                           type="text"
                                                           value="{{ old('cto34_personAuthorizerName') }}"
                                                           placeholder="Ingresar nombre de persona"
                                                           autocomplete="off"
                                                           class="form-control search-field">
                                                    <input id="cto34_personAuthorizer" name="cto34_personAuthorizer" type="hidden" value="{{ old('cto34_personAuthorizer') }}" class="search-hidden">
                                                    <span class="input-group-btn">
                                                        <button id="personAuthorizerMe" type="button" class="btn btn-default" data-id="{{ Auth::id() }}" data-name="{{ Auth::user()['person']->PersonaNombreDirecto }}">Mi</button>
                                                    </span>
                                                </div>
                                                <ul class="dropdown-menu" aria-labelledby="dLabel">
                                                    <li>
                                                        <a href="#" class="search-new" data-id="#personAuthorizer" data-toggle="modal" data-target="#modalPerson">Agregar persona</a>
                                                    </li>
                                                    <li class="dropdown-header search-message"></li>
                                                    <div class="search-result"></div>
                                                </ul>
                                            </div>
                                        </div>
                                        <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    </form>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="returned">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Carga la vista para los formularios de registro de personas y empresa @include('panel.constructionwork.contract.shared.modal-forms') --}}
    <div class="modal fade" id="modalCatalogs" tabindex="-1" role="dialog" aria-labelledby="modalCatalogs">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Catálogos</h4>
                        </div>
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="panel panel-default panel-section bg-white" style="border: 0">
                                    <div class="panel-body padding-clear">
                                        <div class="col-sm-12 margin-top--10">
                                            <input id="searchCatalogs"
                                                   name="q"
                                                   type="text"
                                                   class="form-control form-control-plain"
                                                   placeholder="Buscar catálogo"
                                                   autocomplete="off">
                                            <p id="searchCatalogsMessage" class="text-muted text-center margin-top--10"></p>
                                        </div>
                                        <div role="tablist" id="catalogList" class="list-group col-sm-5 col-md-4 col-lg-3 margin-clear panel-item" style="position: relative;"></div>
                                        <div id="catalogListInfo" class="tab-content col-sm-7 col-md-8 col-lg-9 panel-item padding-left--clear padding-right--clear" style="position: relative;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="catalogBody" style="display: none">
        <div class="col-sm-12 margin-bottom--20">
            <div class="col-sm-12">
                <label class="form-label-full">Contratista</label>
                <p class="helper-text cb-contractor"></p>
            </div>
            <div class="col-sm-12">
                <label class="form-label-full">Contrato</label>
                <p class="helper-text cb-contract"></p>
            </div>
            <div class="col-sm-6">
                <label class="form-label-full">Tipo de obra</label>
                <p class="helper-text cb-workType"></p>
            </div>
            <div class="col-sm-6">
                <label class="form-label-full">Tipo de presupuesto</label>
                <p class="helper-text cb-budgetType"></p>
            </div>
            <div class="col-sm-6">
                <label class="form-label-full">Partida</label>
                <p class="helper-text cb-departure"></p>
            </div>
            <div class="col-sm-6">
                <label class="form-label-full">Subpartida</label>
                <p class="helper-text cb-subDeparture"></p>
            </div>
            <div class="col-sm-6">
                <label class="form-label-full">Ubicación</label>
                <p class="helper-text cb-level"></p>
            </div>
            <div class="clearfix"></div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-12">
                        <label class="form-label-full">Código</label>
                        <p class="helper-text cb-code"></p>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label-full">Descripción completa</label>
                        <p class="helper-text cb-fullDescription"></p>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label-full">Descripción corta</label>
                        <p class="helper-text cb-shortDescription"></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-6">
                        <label class="form-label-full">Unidad</label>
                        <p class="helper-text cb-unity"></p>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label-full">Cantidad</label>
                        <p class="helper-text cb-quantity"></p>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label-full">Precio unitario</label>
                        <p class="helper-text cb-unitPrice"></p>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label-full">Importe</label>
                        <p class="helper-text cb-amount"></p>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-sm-4">
                <label class="form-label-full">Folio externo ID</label>
                <p class="helper-text cb-folio"></p>
            </div>
            <div class="col-sm-4">
                <label class="form-label-full">Fecha de presupuesto</label>
                <p class="helper-text cb-budgetDate"></p>
            </div>
            <div class="col-sm-4">
                <label class="form-label-full">Status del concepto</label>
                <p class="helper-text cb-status"></p>
            </div>
            <div class="col-sm-12">
                <label class="form-label-full">Observaciones</label>
                <p class="helper-text cb-observations"></p>
            </div>
        </div>
    </div>
    @include('panel.constructionwork.generator.shared.modal-forms')
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
<script src="{{ asset('assets/js/catalog.js') }}"></script>
<script>
    (function() {

        var app = new App();
        app.initItemsList();
        app.animateSubmit("saveForm", "addSubmitButton");
        app.animateSubmit("saveCaptureForm", "submitCaptureButton");
        app.tooltip();
        app.dateTimePickerField();

        var work = new ConstructionWork();
        var catalog = new Catalog();

        //work.searchBusinessWithAjax('#contractor', { url: '{{ url("ajax/search/businessWork") }}', token: '{{ csrf_token() }}' });
        //work.searchContractWithAjax('#contract', { url: '{{ url("ajax/action/search/contracts") }}', token: '{{ csrf_token() }}', workId: '{{ $works['one']->tbObraID   }}' });
        //work.searchPersonWithAjax('#contractorSignature', { url: '{{ url("ajax/search/personsWork") }}', token: '{{ csrf_token() }}' });


        work.searchPersonWithAjax('#cto34_reciver',
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

        $('#personMe').on('click',  function() {

            var id = $(this).data('id');
            var name = $(this).data('name');
            var elementId = "#cto34_reciver";

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

        $('#personReviserMe').on('click',  function() {

            var id = $(this).data('id');
            var name = $(this).data('name');

            $('#personReviser').find('.search-field').val(name);
            $('#personReviser').find('.search-hidden').val(id);

        });

        $('#personAuthorizerMe').on('click',  function() {

            var id = $(this).data('id');
            var name = $(this).data('name');

            $('#personAuthorizer').find('.search-field').val(name);
            $('#personAuthorizer').find('.search-hidden').val(id);

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

                message.html('Cargando...');

                var catalogList = '';
                var catalogInfo = '';
                var firstCatalog = 0;

                
                $.each(result.catalogs, function( index, value ) {

                    $('#catalogListInfo').html('');
                    $('#catalogList').html('');

                    if (index === 0) {
                        firstCatalog = value.tbCatalogoID;
                        catalogList += '<a href="#catalogList' + index +'" aria-controls="catalogList' + index +'" role="tab" data-toggle="tab" data-id="'+ value.tbCatalogoID +'" class="list-group-item active">';
                    } else {
                        catalogList += '<a href="#catalogList' + index +'" aria-controls="catalogList' + index +'" role="tab" data-toggle="tab" data-id="'+ value.tbCatalogoID +'" class="list-group-item">';
                    }

                    catalogList += '<h4 class="list-group-item-heading">' + value.CatalogoConceptoCodigo + '</h4>';
                    catalogList += '<p class="small">'+ value.tbUbicaNivelID_Catalogo +'</p>';
                    catalogList += '</a>';

                    $('#catalogList').append(catalogList);

                    if (index === 0) {
                        catalogInfo += '<div role="tabpanel" class="tab-pane active" id="catalogList' + index +'">';
                    } else {
                        catalogInfo += '<div role="tabpanel" class="tab-pane" id="catalogList' + index +'">';
                    }

                    body.find('.cb-contractor').html(ifNull(value.contractor, 'EmpresaRazonSocial'));
                    body.find('.cb-contract').html(ifNull(value.contract, 'ContratoAlias'));
                    body.find('.cb-code').html(value.CatalogoConceptoCodigo);
                    body.find('.cb-workType').html(value.CatalogoObraTipo);
                    body.find('.cb-unity').html(ifNull(value.unity, 'UnidadAlias'));
                    body.find('.cb-quantity').html(value.CatalogoCantidad);
                    body.find('.cb-unitPrice').html(value.CatalogoPrecioUnitario);
                    body.find('.cb-amount').html(value.CatalogoImporte);
                    body.find('.cb-fullDescription').html(value.CatalogoDescripcion);
                    body.find('.cb-shortDescription').html(value.CatalogoDescripcionCorta);
                    body.find('.cb-departure').html(ifNull(value.departure, 'PartidaLabel'));
                    body.find('.cb-subDeparture').html(ifNull(value.subdeparture, 'SubpartidaLabel'));
                    body.find('.cb-budgetType').html(value.CatalogoPresupuestoTipo);
                    body.find('.cb-level').html(value.tbUbicaNivelID_Catalogo);
                    catalogInfo += body.html();
                    catalogInfo += '</div>';

                    $('#catalogListInfo').append(catalogInfo);

                    //$(document).find('#catalogList' + index).prepend(elementNav);

                });

                var tempElement = $('<div></div>');
                var copyButton = $('<a href="#" id="selectCatalog" class="is-tooltip"  data-placement="bottom" title="Copiar" data-id="'+ firstCatalog +'" data-catalog="#catalogList0"><span class="fa-stack fa-lg"> <i class="fa fa-circle fa-stack-2x"></i> <i class="fa fa-paperclip fa-stack-1x fa-inverse"></i> </span></a>');

                //copyButton.html('<span class="fa-stack fa-lg"> <i class="fa fa-circle fa-stack-2x"></i> <i class="fa fa-paperclip fa-stack-1x fa-inverse"></i> </span>');
                copyButton.on('click', function(event){
                    event.preventDefault();

                    var selectedTab = $($(this).attr('data-catalog'));
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

                    $('input[name="cto34_catalog"]').val($(this).attr('data-id'));
                    $('#cto34_workType').val(workType.text());
                    $('#cto34_code').val(code.text());
                    $('#cto34_unity').val(unity.text());
                    $('#cto34_quantity').val(quantity.text());
                    $('#cto34_unitPrice').val(unitPrice.text());
                    $('#cto34_amount').val(amount.text());
                    $('#cto34_description').val(description.text());
                    $('#cto34_departure').val(departure.text());
                    $('#cto34_subHeading').val(subDeparture.text());
                    $('#cto34_catalogQuantity').val(quantity.text());
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

                $('#catalogListInfo').prepend(elementNav);
                message.html('');

        });

        $('.nav-tabs-works a[data-toggle="tab"]').on('show.bs.tab', function (e) {

            var currentTabByHash = $(e.target)[0].hash;

            switch (currentTabByHash) {
                case '#capture':
                    $('.is-submit').attr('id', 'submitCaptureButton').prop('disabled', false);
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
                    console.log('open tab');
                    break;

                default:

                    break;
            }
        }

        $(document).on('click', '#catalogList a', function (e) {
            e.preventDefault();

            var tab = $(this);

            $(document).find('#selectCatalog').attr('data-id', tab.data('id'));
            $(document).find('#selectCatalog').attr('data-catalog', tab.attr('href'));

            $('#catalogList a').removeClass('active');
            $(this).addClass('active');
            $(this).tab('show');

            console.log('tabbed!');
        });

        if ($('#cto34_subdeparture_departure').val() !== '') {
            ('#cto34_subdeparture_departure').prop('disabled', false);
        }

        work.saveDepartureWithAjax('#saveDepartureForm', function(error, result) {

            if (error !== null) {
                alert(error);
                return;
            }

            var data = result.data.business;
            var modal = $('#modalDeparture');
            var elementId = '#cto34_departureCapture';

            $(elementId).find('.option-default').after($('<option>', {
                value: data.id,
                text: data.name,
            }).attr('selected', 'selected'));

            $('#cto34_subdeparture_departure').append($('<option>', {
                value: data.id,
                text: data.name,
            }).attr('selected', 'selected'));

            $(elementId + 'Name').val(data.name);
            $('#cto34_subdepartureCapture').prop('disabled', false);

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
            var elementId = '#cto34_subdepartureCapture';

            $(elementId).find('.option-default').after($('<option>', {
                value: data.id,
                text: data.name,
            }).attr('selected', 'selected'));
            $(elementId + 'Name').val(data.name);
            $(elementId).prop('disabled', false);

            modal.modal('hide');
            modal.find('input[name="_element"]').val('');
        });

        $('#cto34_departureCapture').change(function() {

            var selected = $(this);
            var catalogValue = $('#cto34_departure');

            selected.parent().find('.help-block').html('');
            selected.parent().removeClass('has-error');

            console.log('Selected value: ' +selected.find('option:selected').text() + ' / Catalog value: ' + catalogValue.val());

            if (selected.find('option:selected').text() != catalogValue.val()) {
                selected.parent().find('.help-block').html('Diferente de catálogo');
                selected.parent().addClass('has-error');
            }
        });

        $('#cto34_subdepartureCapture').change(function() {

            var selected = $(this);
            var catalogValue = $('#cto34_subHeading');

            selected.parent().find('.help-block').html('');
            selected.parent().removeClass('has-error');

            console.log('Selected value: ' +selected.find('option:selected').text() + ' / Catalog value: ' + catalogValue.val());

            if (selected.find('option:selected').text() != catalogValue.val()) {
                selected.parent().find('.help-block').html('Diferente de catálogo');
                selected.parent().addClass('has-error');
            }
        });

        $('#cto34_typeWorkCapture').change(function() {

            var selected = $(this);
            var catalogValue = $('#cto34_workType');

            selected.parent().find('.help-block').html('');
            selected.parent().removeClass('has-error');

            if (selected.val() != catalogValue.val()) {
                selected.parent().find('.help-block').html('Diferente de catálogo');
                selected.parent().addClass('has-error');
            }
        });

        $('#cto34_levelCapture').change(function() {

            var selected = $(this);
            var catalogValue = $('#cto34_level');

            selected.parent().find('.help-block').html('');
            selected.parent().removeClass('has-error');

            if (selected.val() != catalogValue.val()) {
                selected.parent().find('.help-block').html('Diferente de catálogo');
                selected.parent().addClass('has-error');
            }
        });

        $('#cto34_unityCapture').change(function() {

            var selected = $(this);
            var catalogValue = $('#cto34_unity');

            selected.parent().find('.help-block').html('');
            selected.parent().removeClass('has-error');

            if (selected.find('option:selected').text() != catalogValue.val()) {
                selected.parent().find('.help-block').html('Diferente de catálogo');
                selected.parent().addClass('has-error');
            }
        });

        addDeparture();
        addSubDeparture();
    })();

    function onSearchOptionSelected(element) {
        var name = $(element).find('option:selected').text();
        $(element + 'Name').val(name);
    }

    function addDeparture() {

        $('#cto34_departureCapture').on('change', function() {

            var select = $(this);

            if (select.val() === '') {
                return;
            }

            if (select.val() === '-1') {
                $('#modalDeparture').modal('show');
                return;
            }

            $('#cto34_subdeparture_departure').val(select.val());

            var elementId = '#cto34_subdepartureCapture';
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
                        options += '<option value="' + value.tbSubPartidaID + '">' +  value.SubpartidaLabel + '</option>';
                    });

                    $(elementId).find('.option-default').after(options);
                }

                $(elementId).prop('disabled', false);

            });

            request.fail(function(xhr, textStatus) {
                searchMessage.html((options.hasOwnProperty('noResults')) ? options.error : 'Ocurrio un error');
            });

        });

    }

    function addSubDeparture() {

        $('#cto34_subdepartureCapture').on('change', function() {

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

    function ifNull(object, child) {

        if (object !== null) {

            if (object.hasOwnProperty(child)) {
                return object[child];
            }

            return object;
        }

        return '-';
    }
</script>
@endpush