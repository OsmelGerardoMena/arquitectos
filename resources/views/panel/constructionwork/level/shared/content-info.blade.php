@if($levels['all']->count() == 0)
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
                    <li role="presentation" class="disabled">
                        <a href="#">
                            Locales
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
                @include('panel.constructionwork.level.shared.content-search')
            </div>
            <div id="itemsList">
                @foreach ($levels['all'] as $index => $level)
                    @if ($level->tbUbicaNivelID == $levels['one']->tbUbicaNivelID)
                        <div id="item{{ $level->tbUbicaNivelID }}" class="list-group-item active">
                            <h4 class="list-group-item-heading">{{ $level->UbicaNivelAlias }}</h4>
                            <p class="small">
                                {{ $level->building->UbicaEdificioAlias }}
                            </p>
                        </div>
                        @continue
                    @endif
                    <a id="item{{  $level->tbUbicaNivelID }}" href="{{ $navigation['base'].$navigation['section'] }}/{{ $level->tbUbicaNivelID  }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="list-group-item">
                        <h4 class="list-group-item-heading">{{ $level->UbicaNivelAlias }}</h4>
                        <p class="small">
                            {{ $level->building->UbicaEdificioAlias }}
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
                        <a href="#locals" aria-controls="locals" role="tab" data-toggle="tab" data-type="relation" data-element="#saveLocalModal">
                            Locales
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-content col-sm-12 margin-bottom--20">
                <div role="tabpanel" class="tab-pane active row padding-top--5" id="general">
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Nivel alias</label>
                        <p class="help-block">{{ ifempty($levels['one']->UbicaNivelAlias) }}</p>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">
                            Edificio alias
                            <a href="{{ $navigation['base'].'/search?q=&filter=true&building='.ifempty($levels['one']->building->tbUbicaEdificioID) }}">
                                <span class="fa fa-filter fa-fw"></span>
                            </a>
                        </label>
                        <a href="#" data-id="{{ ifempty($levels['one']->building->tbUbicaEdificioID) }}" data-toggle="modal" data-target="#showBuildingModal" class="help-block">{{ $levels['one']->building->UbicaEdificioAlias }}</a>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-4">
                        <label for="cto34_code" class="form-label-full">Consecutivo</label>
                        <p class="help-block">{{ ifempty($levels['one']->UbicaNivelConsecutivo) }}</p>
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="cto34_code" class="form-label-full">Clave</label>
                        <p class="help-block">{{ ifempty($levels['one']->UbicaNivelClave) }}</p>
                    </div>
                    <div class="form-group col-sm-4">
                        <label class="form-label-full">Nombre</label>
                        <p class="help-block">{{ ifempty($levels['one']->UbicaNivelNombre) }}</p>
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="cto34_description" class="form-label-full">Descripción</label>
                        <p class="help-block help-block--textarea">{{ ifempty($levels['one']->UbicaNivelDescripcion) }}</p>
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="cto34_surfaceLevel">Suma a los niveles del proyecto</label>
                        @if ($levels['one']->UbicaNivelSumaNivelEdificio == 1)
                            <span class="fa fa-check-square fa-fw text-info"></span>
                        @else
                            <span class="fa fa-square-o fa-fw"></span>
                        @endif
                        <label>Suma a las áreas del proyecto</label>
                        @if ($levels['one']->UbicaNivelSumaAreaEdificio == 1)
                            <span class="fa fa-check-square fa-fw text-info"></span>
                        @else
                            <span class="fa fa-square-o fa-fw"></span>
                        @endif
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-6">
                        <label for="cto34_nptLevel" class="form-label-full">Nivel NPT</label>
                        <p class="help-block">{{ number_format(ifempty($levels['one']->UbicaNivelNPT, '0'), 2, ',', '.') }}</p>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="cto34_nptLevel" class="form-label-full">Locales en este nivel</label>
                        <p class="help-block">{{ number_format(ifempty($levels['one']->UbicaNivelLocalesSistema, '0'), 1, ',', '.') }}</p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-6">
                        <label for="cto34_surfaceLevel" class="form-label-full">Superficie de éste nivel</label>
                        <div class="row">
                            <div class="col-sm-6">
                                <p class="help-block">Interior: {{ number_format(ifempty($levels['one']->UbicaNivelSuperficie, '0'), 2, ',', '.') }}</p>
                            </div>
                            <div class="col-sm-6">
                                <p class="help-block">Exterior: {{ number_format(ifempty($levels['one']->UbicaNivelSuperficieExterior, '0'), 2, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="cto34_surfaceLevel" class="form-label-full">Superficie de locales registrados en éste nivel</label>
                        <div class="row">
                            <div class="col-sm-6">
                                <p class="help-block">Interior: {{ number_format(ifempty($levels['surfaces']['int'], '0'), 2, ',', '.') }}</p>
                            </div>
                            <div class="col-sm-6">
                                <p class="help-block">Exterior: {{ number_format(ifempty($levels['surfaces']['ext'], '0'), 2, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-12 text-right">
                        <label>
                            Cerrado:
                            @if (isset($filter['queries']['status']) && $filter['queries']['status'] == 'deleted')
                                @if (!empty($levels['one']->RegistroCerrado))
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $levels['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" checked disabled>
                                    <br>
                                    <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                @else
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $levels['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" disabled>
                                @endif
                            @else
                                @if (!empty($levels['one']->RegistroCerrado))
                                    @if (Auth::user()['role'] < $levels['one']->RegistroRol || Auth::user()['role'] == 1)
                                        <input type="checkbox" name="cto34_close" value="1" data-id="{{ $levels['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" checked>
                                        <br>
                                        <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                    @else
                                        <input type="checkbox" name="cto34_close" value="1" data-id="{{ $levels['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" checked disabled>
                                        <br>
                                        <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                    @endif
                                @else
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $levels['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}">
                                @endif
                            @endif
                        </label>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane row padding-top--5" id="locals">
                    <div class="col-sm-12">
                        <div class="row margin-bottom--5">
                            <div class="col-sm-12">
                                <p class="help-block">
                                    <b>Locales totales:</b> {{ number_format($locals['total'], 2, ',', '.')  }} /
                                    <b>Superficies:</b> Interior: {{ number_format($locals['area']['int'], 2, ',', '.')  }} - Exterior: {{ number_format($locals['area']['ext'], 2, ',', '.')  }}
                                </p>
                            </div>
                        </div>
                        <div class="items-relation">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Edificio</th>
                                    <th>Nivel</th>
                                    <th>Local</th>
                                    <th>Área Interior (m2)</th>
                                    <th>Área Exterior (m2)</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @forelse ($levels['one']->locals as $local)
                                        <tr data-id="{{ $local->tbUbicaLocalID   }}" data-toggle="modal" data-target="#showLocalModal">
                                            <td>
                                                {{ ifempty($buildings['one']->UbicaEdificioAlias) }}
                                            </td>
                                            <td>
                                                {{ str_limit($level->UbicaNivelAlias, 30) }}
                                            </td>
                                            <td>
                                                {{ str_limit($local->UbicaLocalAlias, 30) }}
                                            </td>
                                            <td>
                                                {{ number_format($local->UbicaLocalArea, 2, '.', ',') }}
                                            </td>
                                            <td>
                                                {{ number_format($local->UbicaLocalAreaExterior, 2, '.', ',') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">No hay registros</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif