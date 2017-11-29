@if($generators['all']->count() == 0)
    <div class="panel-body padding-top--clear">
        <div class="list-group col-sm-5 col-md-4 col-lg-3 margin-clear panel-item" style="position: relative;">
            <div class="list-group-item padding-clear padding-bottom--5">
                <div class="input-group input-group-sm">
                    <input id="search" name="q" class="form-control form-control-plain" placeholder="Busqueda" disabled>
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="submit" disabled>
                            <span class="fa fa-search fa-fw"></span>
                        </button>
                        <button class="btn btn-default" type="button" data-toggle="modal" data-target="#modalFilter" disabled>
                            <span class="fa fa-filter fa-fw"></span>
                        </button>
                    </span>
                </div>
            </div>
            <div id="itemsList">
                <div id="item0" class="list-group-item">
                    <h4 class="list-group-item-heading">
                        Sin registros
                    </h4>
                    <p class="text-muted small">
                        {{ Carbon\Carbon::now()->formatLocalized('%A %d %B %Y') }}
                    </p>
                </div>
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
                        <a href="#catalog">
                            Datos del catálogo
                        </a>
                    </li>
                    <li role="presentation">
                        <a>
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
            </div>
            <div class="tab-content col-sm-12 margin-bottom--20">
                <div role="tabpanel" class="tab-pane active row" id="general">
                    <div class="col-sm-12 text-center">
                        <h4>No hay datos.</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
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
                @foreach($generators['all'] as $index => $generator)
                    @if ($generator->tbGeneradorID  == $generators['one']->tbGeneradorID )
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
            <div class="col-sm-12">
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
            </div>
            <div class="tab-content col-sm-12 margin-bottom--20">
                <div role="tabpanel" class="tab-pane active" id="catalog">
                    <form id="saveForm" action="{{ $navigation['base'].'/action/save' }}" method="post" accept-charset="utf-8" class="row">
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
                                                <input type="text" class="form-control" value="{{ $generators['one']->GeneradorCantidadRevisada - $generators['one']->GeneradorCantidadPresentada }}" readonly="readonly">
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
    {{--
        Este campo sirve para indicar en el listado que registro se esta visualizando
    --}}
    <input type="hidden" name="_recordId" value="{{ $generators['one']->getKey() }}">
@endif