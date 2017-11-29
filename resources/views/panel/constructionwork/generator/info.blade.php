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
                                <a href="{{ $navigation['base'].'/save' }}" class="is-tooltip" data-placement="bottom" title="Nuevo">
									<span class="fa-stack fa-lg">
										<i class="fa fa-circle fa-stack-2x"></i>
										<i class="fa fa-plus fa-stack-1x fa-inverse"></i>
									</span>
                                </a>
                            </li>
                            <li>
                                <a class="disabled">
									<span class="fa-stack fa-lg">
										<i class="fa fa-circle fa-stack-2x"></i>
										<i class="fa fa-floppy-o fa-stack-1x fa-inverse"></i>
									</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ $navigation['base'].'/update' }}" class="is-tooltip" data-placement="bottom" title="Editar">
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="#" id="confirmDeleteButton" class="is-tooltip" data-placement="bottom" title="Eliminar">
                                    <span class="fa-stack text-danger fa-lg">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <span class="text-danger"><i class="fa fa-trash fa-stack-1x fa-inverse"></i></span>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="disabled">
									<span class="fa-stack fa-lg">
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
                                @foreach($generators['all'] as $generator)
                                    @if ($generator->tbGeneradorID == $generators['one']->tbGeneradorID)
                                        <div id="item{{ $generator->tbGeneradorID }}" class="list-group-item active">
                                            <h4 class="list-group-item-heading">{{ $generator->GeneradorFolio }}</h4>
                                            <p class="small">
                                                Catálogo {{ $generator->tbCatalogoID_Generador  }}
                                            </p>
                                        </div>
                                        @continue
                                    @endif
                                    <a id="item{{ $generator->tbGeneradorID }}" href="{{ $navigation['base'] }}/info/{{ $generator->tbGeneradorID }}#item{{ $generator->tbGeneradorID }}" class="list-group-item">
                                        <h4 class="list-group-item-heading">{{ $generator->GeneradorFolio }}</h4>
                                        <p class="small">
                                            Catálogo {{ $generator->tbCatalogoID_Generador  }}
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
                        <div class="col-sm-7 col-md-8 col-lg-9 panel-item padding-left--clear padding-right--clear" style="position: relative;">
                            {{--

                                   Formulario para eliminar un registro
                                   Si es index hay que sobre escribre la variable $model['one']
                               --}}
                            @include('panel.constructionwork.generator.shared.delete-form', [
                                    'title' => 'Generador',
                                    'name' => $generators['all'][0]->GeneradorFolio,
                                    'id' => $generators['all'][0]->tbGeneradorID,
                                    'work' => $works['one']->tbObraID,
                                ])
                            <div class="col-sm-12 margin-bottom--5">
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
                                    <li role="presentation">
                                        <a href="#revision" aria-controls="revision" role="tab" data-toggle="tab">
                                            Revisión
                                        </a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#authorization" aria-controls="authorization" role="tab" data-toggle="tab">
                                            Autorización
                                        </a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#returned" aria-controls="returned" role="tab" data-toggle="tab">
                                            Regresado
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content margin-bottom--20 padding-top--5">
                                    <div role="tabpanel" class="tab-pane active" id="catalog">
                                        <form id="saveForm" action="{{ $navigation['base'] }}/action/save" method="post" accept-charset="utf-8" class="row">
                                            <div class="form-group col-sm-6">
                                                <div id="directCustomer" class="dropdown search-ajax">
                                                    <label class="form-label-full">Catálogo</label>
                                                    <input name="cto34_catalog" type="text" class="form-control" readonly="readonly" value="{{ $catalogs['one']->tbCatalogoID }}">
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="form-group col-sm-6">
                                                <label for="cto34_departure" class="form-label-full">Partida</label>
                                                <input id="cto34_departure"
                                                       name="cto34_departure"
                                                       type="text"
                                                       value="{{ $catalogs['one']->tbPartidaID_Catalogo }}"
                                                       readonly="readonly"
                                                       class="form-control form-control-plain input-sm">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="cto34_subHeading" class="form-label-full">Subpartida</label>
                                                <input id="cto34_subHeading"
                                                       name="cto34_subHeading"
                                                       type="text"
                                                       value="{{ $catalogs['one']->tbSubpartidaID_Catalogo }}"
                                                       readonly="readonly"
                                                       class="form-control form-control-plain input-sm">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="cto34_level" class="form-label-full">Ubicación</label>
                                                <input id="cto34_level"
                                                       name="cto34_level"
                                                       type="text"
                                                       value="{{ $catalogs['one']->tbUbicaNivelID_Catalogo }}"
                                                       readonly="readonly"
                                                       class="form-control form-control-plain input-sm">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="cto34_workType" class="form-label-full">Tipo de obra</label>
                                                <input id="cto34_workType"
                                                       name="cto34_workType"
                                                       type="text"
                                                       value="{{ $catalogs['one']->CatalogoObraTipo }}"
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
                                                               value="{{ $catalogs['one']->CatalogoConceptoCodigo }}"
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
                                                                  class="form-control form-control-plain">{{ $catalogs['one']->CatalogoDescripcion }}</textarea>
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
                                                               value="{{ $catalogs['one']->unity->UnidadAlias }}"
                                                               readonly="readonly"
                                                               class="form-control form-control-plain input-sm">
                                                    </div>
                                                    <div class="col-sm-6 margin-bottom--10">
                                                        <label for="cto34_quantity" class="form-label-full">Cantidad</label>
                                                        <input id="cto34_quantity"
                                                               name="cto34_quantity"
                                                               type="text"
                                                               value="{{ $catalogs['one']->CatalogoCantidad }}"
                                                               readonly="readonly"
                                                               class="form-control form-control-plain input-sm">
                                                    </div>
                                                    <div class="col-sm-6 margin-bottom--10">
                                                        <label for="cto34_unitPrice" class="form-label-full">Precio unitario</label>
                                                        <input id="cto34_unitPrice"
                                                               name="cto34_unitPrice"
                                                               type="text"
                                                               value="{{ $catalogs['one']->CatalogoPrecioUnitario }}"
                                                               readonly="readonly"
                                                               class="form-control form-control-plain input-sm">
                                                    </div>
                                                    <div class="col-sm-6 margin-bottom--10">
                                                        <label for="cto34_amount" class="form-label-full">Importe</label>
                                                        <input id="cto34_amount"
                                                               name="cto34_amount"
                                                               type="text"
                                                               value="{{ $catalogs['one']->CatalogoImporte }}"
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
                                                <input id="cto34_signatureDate"
                                                       name="cto34_signatureDate"
                                                       type="text"
                                                       value="{{ $generators['one']->GeneradorReciboFecha }}"
                                                       readonly="readonly"
                                                       class="form-control form-control-plain input-sm">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <div id="contractorSignature" class="dropdown search-ajax">
                                                    <label class="form-label-full">Recibido por</label>
                                                    <input id="cto34_contractorSignatureName"
                                                           name="cto34_contractorSignatureName"
                                                           type="text"
                                                           value="{{ !empty($generators['one']->reciver) ? $generators['one']->reciver->PersonaNombreDirecto : '-' }}"
                                                           placeholder="Ingresar nombre de persona"
                                                           autocomplete="off"
                                                           readonly="readonly"
                                                           class="form-control search-field">
                                                </div>
                                            </div>
                                            <div class="col-sm-12 margin-bottom--10">
                                                <h5 class="page-header margin-clear">Revisión preliminar</h5>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="cto34_departure" class="form-label-full">Partida del generador</label>
                                                <input id="cto34_departure"
                                                       name="cto34_departure"
                                                       type="text"
                                                       value="{{ $generators['one']->tbPartidaID_Generador }}"
                                                       autocomplete="off"
                                                       readonly="readonly"
                                                       class="form-control">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="cto34_subDeparture" class="form-label-full">Subpartida del generador</label>
                                                <input id="cto34_subDeparture"
                                                       name="cto34_subDeparture"
                                                       type="text"
                                                       value="{{ $generators['one']->tbSubPartidaID_Generador }}"
                                                       autocomplete="off"
                                                       readonly="readonly"
                                                       class="form-control">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="cto34_workType" class="form-label-full">Tipo de obra del generador</label>
                                                <input id="cto34_workType"
                                                       name="cto34_workType"
                                                       type="text"
                                                       value="{{ $generators['one']->GeneradorObraTipo }}"
                                                       autocomplete="off"
                                                       readonly="readonly"
                                                       class="form-control">
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="form-group col-sm-6">
                                                <label for="cto34_conceptLocation" class="form-label-full">Ubicación de lo generado</label>
                                                <input id="cto34_conceptLocation"
                                                       name="cto34_conceptLocation"
                                                       type="text"
                                                       value="{{ $generators['one']->tbUbicaNivelID_Generador }}"
                                                       autocomplete="off"
                                                       readonly="readonly"
                                                       class="form-control">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="cto34_unity" class="form-label-full">Unidad del generador</label>
                                                <input id="cto34_conceptLocation"
                                                       name="cto34_conceptLocation"
                                                       type="text"
                                                       value="{{ !empty($generators['one']->unity) ? $generators['one']->unity->UnidadAlias : '-' }}"
                                                       autocomplete="off"
                                                       readonly="readonly"
                                                       class="form-control">
                                            </div>
                                            <div class="col-sm-12 margin-bottom--10">
                                                <h5 class="page-header margin-clear">Cantidad presentada</h5>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="cto34_presentedQuantity" class="form-label-full">Cantidad presentada en este generador</label>
                                                <input id="cto34_presentedQuantity"
                                                       name="cto34_presentedQuantity"
                                                       type="text"
                                                       value="{{ $generators['one']->GeneradorCantidadPresentada }}"
                                                       readonly="readonly"
                                                       class="form-control form-control-plain input-sm">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="cto34_totalPresentedQuantity" class="form-label-full">Cantidad total presentada</label>
                                                <input id="cto34_totalPresentedQuantity"
                                                       name="cto34_totalPresentedQuantity"
                                                       type="text"
                                                       value="{{ $generators['one']->GeneradorCantidadPresentada }}"
                                                       readonly="readonly"
                                                       class="form-control form-control-plain input-sm">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="cto34_catalogQuantity" class="form-label-full">Cantidad en catálogo</label>
                                                <input id="cto34_catalogQuantity"
                                                       name="cto34_catalogQuantity"
                                                       type="text"
                                                       value="{{ $catalogs['one']->CatalogoCantidad }}"
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
                                        <form id="saveRevisionForm" action="{{ $navigation['base']  }}/action/save/revision" method="post" accept-charset="utf-8" class="row">
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
                                                                    <input id="presentedQuantity" name="presentedQuantity" type="text" class="form-control" value="{{ $generators['one']->GeneradorCantidadPresentada }}" readonly="readonly">
                                                                    <div class="input-group-addon">
                                                                        <span>0.0%</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control" readonly="readonly" value="{{ $generators['quantityPresentedPrevious'] }}">
                                                                    <div class="input-group-addon">
                                                                        <span>0.0%</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control" value="{{ $generators['quantityPresentedPrevious'] }}" readonly="readonly">
                                                                    <div class="input-group-addon">
                                                                        <span>0.0%</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control" value="{{ $generators['quantityPresentedTotal'] }}" readonly="readonly">
                                                                    <div class="input-group-addon">
                                                                        <span>0.0%</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control input-sm" value="${{ $generators['quantityPresentedAmount'] }}" readonly="readonly">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Cantidad revisada</td>
                                                            <td>
                                                                @if (empty($generators['one']->GeneradorCantidadRevisada))
                                                                    <div class="input-group input-group-sm">
                                                                        <input name="cto34_revisedQuantity" type="text" class="form-control" value="{{ old('cto34_revisedQuantity') }}">
                                                                        <div class="input-group-addon">
                                                                            <span>0.0%</span>
                                                                        </div>
                                                                    </div>
                                                                    <button type="button" id="revisionSameButton" class="btn btn-default btn-block btn-xs">
                                                                        Misma
                                                                    </button>
                                                                @else
                                                                    <div class="input-group input-group-sm">
                                                                        <input name="cto34_revisedQuantity"
                                                                               type="text"
                                                                               class="form-control"
                                                                               readonly="readonly"
                                                                               value="{{ $generators['one']->GeneradorCantidadRevisada }}">
                                                                        <div class="input-group-addon">
                                                                            <span>0.0%</span>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control" value="{{ $generators['quantityRevisedPrevious'] }}" readonly="readonly">
                                                                    <div class="input-group-addon">
                                                                        <span>0.0%</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control" value="{{ $generators['quantityRevisedPrevious'] }}" readonly="readonly">
                                                                    <div class="input-group-addon">
                                                                        <span>0.0%</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control" value="{{ $generators['quantityRevisedTotal'] }}" readonly="readonly">
                                                                    <div class="input-group-addon">
                                                                        <span>0.0%</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control input-sm" value="${{ $generators['quantityRevisedAmount'] }}" readonly="readonly">
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
                                                @if(!empty($generators['one']->GeneradorDiferenciaMotivos))
                                                    <input id="cto34_reasonDifference"
                                                           name="cto34_reasonDifference"
                                                           type="text"
                                                           value="{{ $generators['one']->GeneradorDiferenciaMotivos }}"
                                                           readonly="readonly"
                                                           class="form-control form-control-plain input-sm">
                                                @else
                                                    <select name="cto34_reasonDifference" id="cto34_reasonDifference" class="form-control input-sm">
                                                        <option value="">Seleccionar opción</option>
                                                        <option value="Generador repetido">Generador repetido</option>
                                                        <option value="Incluido en P.U.">Incluido en P.U.</option>
                                                        <option value="Mal cuantificado">Mal cuantificado</option>
                                                        <option value="Operaciones con errores">Operaciones con errores</option>
                                                        <option value="Trabajo no autorizado">Trabajo no autorizado</option>
                                                    </select>

                                                @endif
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="form-group col-sm-6">
                                                <label for="cto34_reviserDate" class="form-label-full">Fecha de la revisión</label>
                                                @if(!empty($generators['one']->GeneradorRevisoFecha))
                                                    <input id="cto34_reviserDate"
                                                           name="cto34_reviserDate"
                                                           type="text"
                                                           value="{{ Carbon\Carbon::parse($generators['one']->GeneradorRevisoFecha)->formatLocalized('%A %d %B %Y')  }}"
                                                           readonly="readonly"
                                                           class="form-control form-control-plain input-sm">
                                                @else
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
                                                @endif
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="cto34_personReviserName" class="form-label-full">Revisó</label>
                                                @if(!empty($generators['one']->reviewer->PersonaNombreDirecto))
                                                    <input id="cto34_personReviserName"
                                                           name="cto34_personReviserName"
                                                           type="text"
                                                           value="{{ $generators['one']->reviewer->PersonaNombreDirecto }}"
                                                           placeholder="Ingresar nombre de persona"
                                                           autocomplete="off"
                                                           readonly="readonly"
                                                           class="form-control input-sm">
                                                @else
                                                    <div class="input-group input-group-sm">
                                                        <select id="cto34_personReviser"
                                                                name="cto34_personReviser"
                                                                data-live-search="true"
                                                                data-width="100%"
                                                                data-style="btn-sm btn-default"
                                                                data-modal-title="Cliente directo"
                                                                class="selectpicker with-ajax">
                                                            @if(!empty(old('cto34_personReviser')))
                                                                <option value="{{ old('cto34_personReviser') }}" selected="selected">
                                                                    {{ old('cto34_personReviser')  }}
                                                                </option>
                                                            @endif
                                                        </select>
                                                        <input type="hidden" id="cto34_personReviserName" name="cto34_personReviserName" value="{{ old('cto34_personReviserName')  }}">
                                                        <span class="input-group-btn">
                                                        <button id="personReviserMe" type="button" class="btn btn-default" data-id="{{ Auth::id() }}" data-name="{{ Auth::user()['person']->PersonaNombreDirecto }}">Yo</button>
                                                    </span>
                                                    </div>
                                                @endif
                                            </div>
                                            <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                                            <input type="hidden" name="cto34_generator" value="{{ $generators['one']->tbGeneradorID }}">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="_method" value="put">
                                        </form>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="authorization">
                                        <form id="saveAuthorizationForm" action="{{ $navigation['base']  }}/action/save/authorization" method="post" accept-charset="utf-8" class="row">
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
                                                                    <input id="presentedQuantity" name="presentedQuantity" type="text" class="form-control" value="{{ $generators['one']->GeneradorCantidadPresentada }}" readonly="readonly">
                                                                    <div class="input-group-addon">
                                                                        <span>0.0%</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control" readonly="readonly" value="{{ $generators['quantityPresentedPrevious'] }}">
                                                                    <div class="input-group-addon">
                                                                        <span>0.0%</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control" value="{{ $generators['quantityPresentedPrevious'] }}" readonly="readonly">
                                                                    <div class="input-group-addon">
                                                                        <span>0.0%</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control" value="{{ $generators['quantityPresentedTotal'] }}" readonly="readonly">
                                                                    <div class="input-group-addon">
                                                                        <span>0.0%</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control input-sm" value="${{ $generators['quantityPresentedAmount'] }}" readonly="readonly">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Cantidad revisada</td>
                                                            <td>
                                                                @if (empty($generators['one']->GeneradorCantidadRevisada))
                                                                    <div class="input-group input-group-sm">
                                                                        <input name="cto34_revisedQuantity" type="text" class="form-control" value="{{ old('cto34_revisedQuantity') }}">
                                                                        <div class="input-group-addon">
                                                                            <span>0.0%</span>
                                                                        </div>
                                                                    </div>
                                                                    <button type="button" id="revisionSameButton" class="btn btn-default btn-block btn-xs">
                                                                        Misma
                                                                    </button>
                                                                @else
                                                                    <div class="input-group input-group-sm">
                                                                        <input name="cto34_revisedQuantity"
                                                                               type="text"
                                                                               class="form-control"
                                                                               readonly="readonly"
                                                                               value="{{ $generators['one']->GeneradorCantidadRevisada }}">
                                                                        <div class="input-group-addon">
                                                                            <span>0.0%</span>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control" value="{{ $generators['quantityRevisedPrevious'] }}" readonly="readonly">
                                                                    <div class="input-group-addon">
                                                                        <span>0.0%</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control" value="{{ $generators['quantityRevisedPrevious'] }}" readonly="readonly">
                                                                    <div class="input-group-addon">
                                                                        <span>0.0%</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control" value="{{ $generators['quantityRevisedTotal'] }}" readonly="readonly">
                                                                    <div class="input-group-addon">
                                                                        <span>0.0%</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control input-sm" value="${{ $generators['quantityRevisedAmount'] }}" readonly="readonly">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Cantidad autorizada</td>
                                                            <td>
                                                                @if (empty($generators['one']->GeneradorCantidadAutorizada))
                                                                    <div class="input-group input-group-sm">
                                                                        <input name="cto34_authorizedQuantity" type="text" class="form-control">
                                                                        <div class="input-group-addon">
                                                                            <span>0.0%</span>
                                                                        </div>
                                                                    </div>
                                                                    <button type="button" id="authSameButton" class="btn btn-default btn-block btn-xs">
                                                                        Misma
                                                                    </button>
                                                                @else
                                                                    <div class="input-group input-group-sm">
                                                                        <input name="cto34_authorizedQuantity"
                                                                               type="text"
                                                                               readonly="readonly"
                                                                               value="{{ $generators['one']->GeneradorCantidadAutorizada }}"
                                                                               class="form-control">
                                                                        <div class="input-group-addon">
                                                                            <span>0.0%</span>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control" value="{{ $generators['quantityAuthPrevious'] }}" readonly="readonly">
                                                                    <div class="input-group-addon">
                                                                        <span>0.0%</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control" value="{{ $generators['quantityAuthPrevious'] }}" readonly="readonly">
                                                                    <div class="input-group-addon">
                                                                        <span>0.0%</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control" value="{{ $generators['quantityAuthTotal'] }}" readonly="readonly">
                                                                    <div class="input-group-addon">
                                                                        <span>0.0%</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control input-sm" value="${{ $generators['quantityAuthAmount'] }}" readonly="readonly">
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
                                                <label for="cto34_reasonDifferenceAuth">Motivo de las diferencias</label>
                                                @if(!empty($generators['one']->GeneradorDiferenciaMotivos))
                                                    <input id="cto34_reasonDifferenceAuth"
                                                           name="cto34_reasonDifferenceAuth"
                                                           type="text"
                                                           value="{{ $generators['one']->GeneradorDiferenciaMotivos }}"
                                                           readonly="readonly"
                                                           class="form-control form-control-plain input-sm">
                                                @else
                                                    <select name="cto34_reasonDifferenceAuth" id="cto34_reasonDifferenceAuth" class="form-control input-sm">
                                                        <option value="">Seleccionar opción</option>
                                                        <option value="Generador repetido">Generador repetido</option>
                                                        <option value="Incluido en P.U.">Incluido en P.U.</option>
                                                        <option value="Mal cuantificado">Mal cuantificado</option>
                                                        <option value="Operaciones con errores">Operaciones con errores</option>
                                                        <option value="Trabajo no autorizado">Trabajo no autorizado</option>
                                                    </select>

                                                @endif
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="form-group col-sm-6">
                                                <label for="cto34_authorizerDate" class="form-label-full">Fecha de autorización</label>
                                                @if (empty($generators['one']->GeneradorAutorizaFecha))
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
                                                @else
                                                    <input id="cto34_authorizerDate"
                                                           name="cto34_authorizerDate"
                                                           type="text"
                                                           value="{{ $generators['one']->GeneradorAutorizaFecha }}"
                                                           readonly="readonly"
                                                           class="form-control form-control-plain input-sm">
                                                @endif
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <div id="personAuthorizer" class="dropdown search-ajax">
                                                    <label class="form-label-full">Autorizó</label>
                                                    @if (empty($generators['one']->tbDirPersonaObraID_GeneradorAutoriza))
                                                        <div class="input-group input-group-sm">
                                                            <select id="cto34_personAuthorizer"
                                                                    name="cto34_personAuthorizer"
                                                                    data-live-search="true"
                                                                    data-width="100%"
                                                                    data-style="btn-sm btn-default"
                                                                    data-modal-title="Cliente directo"
                                                                    class="selectpicker with-ajax">
                                                                @if(!empty(old('cto34_personAuthorizer')))
                                                                    <option value="{{ old('cto34_personAuthorizer') }}" selected="selected">
                                                                        {{ old('cto34_personAuthorizer')  }}
                                                                    </option>
                                                                @endif
                                                            </select>
                                                            <input type="hidden" id="cto34_personAuthorizerName" name="cto34_personAuthorizerName" value="{{ old('cto34_personAuthorizerName')  }}">
                                                            <span class="input-group-btn">
                                                            <button id="personAuthorizerMe" type="button" class="btn btn-default" data-id="{{ Auth::id() }}" data-name="{{ Auth::user()['person']->PersonaNombreDirecto }}">Yo</button>
                                                        </span>
                                                        </div>
                                                    @else
                                                        <input id="cto34_personAuthorizerName"
                                                               name="cto34_personAuthorizerName"
                                                               type="text"
                                                               value="{{ $generators['one']->authorizer->PersonaNombreDirecto }}"
                                                               placeholder="Ingresar nombre de persona"
                                                               autocomplete="off"
                                                               readonly="readonly"
                                                               class="form-control input-sm">
                                                    @endif
                                                </div>
                                            </div>
                                            <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                                            <input type="hidden" name="cto34_generator" value="{{ $generators['one']->tbGeneradorID }}">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="_method" value="put">
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
                                        <div role="tabpanel" id="catalogListInfo" class="col-sm-7 col-md-8 col-lg-9 panel-item padding-left--clear padding-right--clear" style="position: relative;"></div>
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
        app.animateSubmit("saveRevisionForm", "submitRevisionButton");
        app.animateSubmit("saveAuthorizationForm", "submitAuthorizationButton");
        app.tooltip();
        app.dateTimePickerField();

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