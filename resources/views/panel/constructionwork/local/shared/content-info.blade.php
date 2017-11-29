@if($locals['all']->count() == 0)
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
                        <a href="#general" aria-controls="general" role="tab" data-toggle="tab">
                            General
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
                @include('panel.constructionwork.local.shared.content-search')
            </div>
            <div id="itemsList">
                @foreach ($locals['all'] as $index => $local)
                    @if ($local->tbUbicaLocalID == $locals['one']->tbUbicaLocalID)
                        <div id="item{{ $local->tbUbicaLocalID }}" class="list-group-item active">
                            <h4 class="list-group-item-heading">{{ $local->UbicaLocalAlias }}</h4>
                            <p class="small">
                                {{ $local->level->building->UbicaEdificioAlias }} / {{ $local->level->UbicaNivelAlias }}
                            </p>
                        </div>
                        @continue
                    @endif
                    <a id="item{{  $local->tbUbicaLocalID }}" href="{{ $navigation['base'].$navigation['section'] }}/{{ $local->tbUbicaLocalID  }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="list-group-item">
                        <h4 class="list-group-item-heading">{{ $local->UbicaLocalAlias }}</h4>
                        <p class="text-muted small">
                            {{ $local->level->building->UbicaEdificioAlias }} / {{ $local->level->UbicaNivelAlias }}
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
                </ul>
            </div>
            <div class="tab-content col-sm-12 margin-bottom--20">
                <div role="tabpanel" class="tab-pane active row padding-top--5" id="general">
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">
                            Edificio alias
                            <a href="{{ $navigation['base'].'/search?q=&filter=true&building='.$locals['one']->level->tbUbicaEdificioID_Nivel }}">
                                <span class="fa fa-filter fa-fw"></span>
                            </a>
                        </label>
                        <a href="#" data-id="{{ ifempty($locals['one']->level->tbUbicaEdificioID_Nivel) }}" data-toggle="modal" data-target="#showBuildingModal" class="help-block">{{ ifempty($locals['one']->level->building->UbicaEdificioAlias) }}</a>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">
                            Nivel alias
                            <a href="{{ $navigation['base'].'/search?q=&filter=true&level='.ifempty($locals['one']->level->tbUbicaNivelID) }}">
                                <span class="fa fa-filter fa-fw"></span>
                            </a>
                        </label>
                        <a href="#" data-id="{{ ifempty($locals['one']->level->tbUbicaNivelID) }}" data-toggle="modal" data-target="#showLevelModal" class="help-block">{{ ifempty($locals['one']->level->UbicaNivelAlias) }}</a>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Local alias</label>
                        <p class="help-block">{{ ifempty($locals['one']->UbicaLocalAlias) }}</p>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Clave</label>
                        <p class="help-block">{{ ifempty($locals['one']->UbicaLocalClave) }}</p>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Nombre</label>
                        <p class="help-block">{{ ifempty($locals['one']->UbicaLocalNombre) }}</p>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">
                            Tipo
                            <a href="{{ $navigation['base'].'/search?q=&filter=true&type='.ifempty($locals['one']->UbicaLocalTipo, '') }}">
                                <span class="fa fa-filter fa-fw"></span>
                            </a>
                        </label>
                        <p class="help-block">{{ ifempty($locals['one']->UbicaLocalTipo) }}</p>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Área</label>
                        <p class="help-block">{{ number_format(ifempty($locals['one']->UbicaLocalArea, 0), 2, ',', '.') }}</p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-6 col-sm-offset-6">
                        <label for="cto34_surfaceLevel">Suma a las áreas del proyecto</label>
                        @if ($locals['one']->UbicaLocalSumaAreaNivel == 1)
                            <span class="fa fa-check-square fa-fw text-info"></span>
                        @else
                            <span class="fa fa-square-o fa-fw"></span>
                        @endif
                    </div>
                    <div class="form-group col-sm-12 text-right">
                        <label>
                            Cerrado:
                            @if (isset($filter['queries']['status']) && $filter['queries']['status'] == 'deleted')
                                @if (!empty($locals['one']->RegistroCerrado))
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $locals['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" checked disabled>
                                    <br>
                                    <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                @else
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $locals['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" disabled>
                                @endif
                            @else
                                @if (!empty($locals['one']->RegistroCerrado))
                                    @if (Auth::user()['role'] < $locals['one']->RegistroRol || Auth::user()['role'] == 1)
                                        <input type="checkbox" name="cto34_close" value="1" data-id="{{ $locals['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" checked>
                                        <br>
                                        <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                    @else
                                        <input type="checkbox" name="cto34_close" value="1" data-id="{{ $locals['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" checked disabled>
                                        <br>
                                        <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                    @endif
                                @else
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $locals['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}">
                                @endif
                            @endif
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="_recordId" value="{{ $locals['one']->getKey() }}">
@endif