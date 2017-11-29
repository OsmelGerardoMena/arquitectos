@if($catalogs['all']->count() == 0)
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
                        <a href="#concepts" aria-controls="concepts" role="tab" data-toggle="tab">
                            Concepto
                        </a>
                    </li>
                    <li role="presentation" class="disabled">
                        <a href="#">
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
                @include('panel.constructionwork.catalog.shared.content-search')
            </div>
            <div id="itemsList">
                @foreach ($catalogs['all'] as $index => $catalog)
                    @if ($catalogs['one']->tbCatalogoID == $catalog->tbCatalogoID)
                        <div id="item{{ $catalog->tbCatalogoID }}" class="list-group-item active">
                            <h4 class="list-group-item-heading">{{ $catalog->CatalogoConceptoCodigo }}</h4>
                            <p class="small">
                                {{ $catalog->level->UbicaNivelAlias }}
                            </p>
                        </div>
                        @continue
                    @endif
                    <a id="item{{ $catalog->tbCatalogoID }}" href="{{ $navigation['base'].$navigation['section'] }}/{{ $catalog->tbCatalogoID  }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="list-group-item">
                        <h4 class="list-group-item-heading">{{ $catalog->CatalogoConceptoCodigo }}</h4>
                        <p class="small text-muted">
                            {{ $catalog->level->UbicaNivelAlias }}
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
                        <a href="#concepts" aria-controls="concepts" role="tab" data-toggle="tab">
                            Concepto
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#general" aria-controls="general" role="tab" data-toggle="tab">
                            Generales
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#generators" aria-controls="generators" role="tab" data-toggle="tab">
                            Generadores
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#estimations" aria-controls="estimations" role="tab" data-toggle="tab">
                            Estimaciones
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#comments" aria-controls="comments" role="tab" data-toggle="tab">
                            Comentarios
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-content col-sm-12 margin-bottom--20">
                <div role="tabpanel" class="tab-pane active row" id="concepts">
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Código</label>
                        <p class="help-block">{{ $catalogs['one']->CatalogoConceptoCodigo  }}</p>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Ubicación del concepto</label>
                        <p class="help-block">{{ (!empty($catalogs['one']->level->UbicaNivelAlias)) ? $catalogs['one']->level->UbicaNivelAlias : ' - ' }}</p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-6">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">Descripción completa</label>
                                <p class="help-block help-block--textarea" style="height: 150px">{{ $catalogs['one']->CatalogoDescripcion  }}</p>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">Descripción corta</label>
                                <p class="help-block help-block--textarea">{{ $catalogs['one']->CatalogoDescripcionCorta  }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="form-label-full">Unidad</label>
                                <p class="help-block">{{ (!empty($catalogs['one']->unity->UnidadAlias)) ? $catalogs['one']->unity->UnidadAlias: ' - '  }}</p>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label-full">Cantidad</label>
                                <p class="help-block">{{ number_format(ifempty($catalogs['one']->CatalogoCantidad, 0), 2)  }}</p>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label-full">Precio unitario</label>
                                <p class="help-block">${{ number_format($catalogs['one']->CatalogoPrecioUnitario, 2)  }}</p>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label-full">Importe</label>
                                <p class="help-block">${{ number_format($catalogs['one']->CatalogoImporte, 2) }}</p>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">Código Externo</label>
                                <p class="help-block">{{ ifempty($catalogs['one']->CatalogoFolioExterno)  }}</p>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">Fecha de presupuesto</label>
                                <p class="help-block">{{ Carbon\Carbon::parse($catalogs['one']->CatalogoPresupuestoFecha)->formatLocalized('%A %d %B %Y')  }}</p>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">Status del concepto</label>
                                <p class="help-block">{{ ifempty($catalogs['one']->CatalogoConceptoStatus) }}</p>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group col-sm-12 text-right">
                                <label>
                                    Cerrado:
                                    @if (isset($filter['queries']['status']) && $filter['queries']['status'] == 'deleted')
                                        @if (!empty($catalogs['one']->RegistroCerrado))
                                            <input type="checkbox" name="cto34_close" value="1" data-id="{{ $catalogs['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" checked disabled>
                                            <br>
                                            <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                        @else
                                            <input type="checkbox" name="cto34_close" value="1" data-id="{{ $catalogs['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" disabled>
                                        @endif
                                    @else
                                        @if (!empty($catalogs['one']->RegistroCerrado))
                                            @if (Auth::user()['role'] < $catalogs['one']->RegistroRol || Auth::user()['role'] == 1)
                                                <input type="checkbox" name="cto34_close" value="1" data-id="{{ $catalogs['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" checked>
                                                <br>
                                                <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                            @else
                                                <input type="checkbox" name="cto34_close" value="1" data-id="{{ $catalogs['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" checked disabled>
                                                <br>
                                                <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                            @endif
                                        @else
                                            <input type="checkbox" name="cto34_close" value="1" data-id="{{ $catalogs['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}">
                                        @endif
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane row" id="general">
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Código</label>
                        <p class="help-block">{{ $catalogs['one']->CatalogoConceptoCodigo  }}</p>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Contratista</label>
                        @if (!empty($catalogs['one']->contract))
                            <p class="help-block">{{ $catalogs['one']->contract->contractor->business[0]->EmpresaAlias  }}</p>
                        @else
                            <p class="help-block"> - </p>
                        @endif
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Contrato</label>
                        @if (!empty($catalogs['one']->contract))
                            <p class="help-block">{{ $catalogs['one']->contract->ContratoAlias  }}</p>
                        @else
                            <p class="help-block">-</p>
                        @endif
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Tipo de obra</label>
                        <p class="help-block">{{ $catalogs['one']->CatalogoObraTipo }}</p>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Tipo de presupuesto</label>
                        <p class="help-block">{{ $catalogs['one']->CatalogoPresupuestoTipo }}</p>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Partida</label>
                        @if (!empty($catalogs['one']->departure))
                            <p class="help-block">{{ $catalogs['one']->departure->PartidaLabel }}</p>
                        @else
                            <p class="help-block">-</p>
                        @endif
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Subpartida</label>
                        @if (!empty($catalogs['one']->subdeparture))
                            <p class="help-block">{{ $catalogs['one']->subdeparture->SubpartidaLabel }}</p>
                        @else
                            <p class="help-block">-</p>
                        @endif
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane row" id="generators">
                    <div class="text-right" style="position: absolute; top: -40px; right: 10px; padding: 5px;">
                        <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#saveGeneratorModal">
                            <span class="fa fa-plus fa-fw text-success"></span>
                        </button>
                    </div>
                    <div class="col-sm-12">
                        <div class="list-group" style="height: 300px; overflow-x: auto">
                            @forelse ($catalogs['one']->generators as $generator)
                                <a href="#" data-id="{{ $generator->tbGeneradorID  }}" data-toggle="modal" data-target="#showGeneratorModal" class="list-group-item" style="border-bottom: 1px solid #ddd">
                                    <h5 class="list-group-item-heading">Folio {{ $generator->GeneradorFolio }}</h5>
                                    <p class="list-group-item-text small text-muted">
                                        {{ $generator->tbCatalogoID_Generador }}
                                    </p>
                                </a>
                            @empty
                                <div class="list-group-item">No hay generadores registrados</div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane row" id="estimations">
                    <div class="text-right" style="position: absolute; top: -40px; right: 10px; padding: 5px;">
                        <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#saveEstimationModal">
                            <span class="fa fa-plus fa-fw text-success"></span>
                        </button>
                    </div>
                    <div class="col-sm-12">
                        <div class="list-group" style="height: 300px; overflow-x: auto">
                            @forelse ($catalogs['one']->estimates as $estimate)
                                <a href="#" data-id="{{ $estimate->tbEstimacionID  }}" data-toggle="modal" data-target="#showEstimateModal" class="list-group-item" style="border-bottom: 1px solid #ddd">
                                    <h5 class="list-group-item-heading">{{ $estimate->EstimacionLabel }}</h5>
                                    <p class="list-group-item-text small text-muted">
                                        {{ $estimate->contract->ContratoAlias }}
                                    </p>
                                </a>
                            @empty
                                <div class="list-group-item">No hay estimaciones registradas</div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane row" id="comments">
                    <div class="text-right" style="position: absolute; top: -40px; right: 10px; padding: 5px;">
                        <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modalComment">
                            <span class="fa fa-plus fa-fw text-success"></span>
                        </button>
                    </div>
                    <div class="col-sm-12">
                        <div class="list-group" style="height: 300px; overflow-x: auto">
                            @forelse($catalogs['one']->comments as $comment)
                                <a href="#" data-id="{{ $local->tbUbicaLocalID  }}" data-toggle="modal" data-target="#showCommentModal" class="list-group-item" style="border-bottom: 1px solid #ddd">
                                    <h5 class="list-group-item-heading">Comentario por {{ $comment->user->person->PersonaNombreCompleto  }}</h5>
                                    <p class="list-group-item-text small text-muted">
                                        {{ $comment->Comentario }}<br>
                                        <small class="text-muted">
                                            {{  Carbon\Carbon::parse($comment->ComentarioFecha)->formatLocalized('%A %d %B %Y') }}
                                        </small>
                                    </p>
                                </a>
                            @empty
                                <div class="list-group-item">
                                    No hay comentarios registrados
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--
        Este campo sirve para indicar en el listado que registro se esta visualizando
    --}}
    <input type="hidden" name="_recordId" value="{{ $catalogs['one']->getKey() }}">
@endif