@if($buildings['all']->count() == 0)
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
                            Niveles
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
                @include('panel.constructionwork.building.shared.content-search')
            </div>
            <div id="itemsList">
                @foreach ($buildings['all'] as $index => $building)
                    @if ($buildings['one']->tbUbicaEdificioID == $building->tbUbicaEdificioID)
                        <div id="item{{ $building->tbUbicaEdificioID }}" class="list-group-item active">
                            <h4 class="list-group-item-heading">{{ $building->UbicaEdificioNombre }}</h4>
                            <p class="small">
                                {{ $building->UbicaEdificioAlias }}
                            </p>
                        </div>
                        @continue
                    @endif
                    <a id="item{{ $building->tbUbicaEdificioID }}" href="{{ $navigation['base'].$navigation['section'] }}/{{ $building->tbUbicaEdificioID  }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="list-group-item">
                        <h4 class="list-group-item-heading">{{ $building->UbicaEdificioNombre }}</h4>
                        <p class="text-muted small">
                            {{ $building->UbicaEdificioAlias }}
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
            {{--
                Formulario para eliminar un registro
            --}}
            @include('shared.record-delete', [
                'id' => $buildings['one']->tbUbicaEdificioID,
                'work' => $works['one']->tbObraID,
            ])
            <div class="col-sm-12 margin-bottom--5">
                <ul class="nav nav-tabs nav-tabs-works" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#general" aria-controls="general" role="tab" data-toggle="tab" data-type="own">
                            General
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#levels" aria-controls="levels" role="tab" data-toggle="tab" data-type="relation" data-element="#saveLevelModal">
                            Niveles
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
                        <label class="form-label-full">Nombre</label>
                        <p class="help-block">{{ ifempty($buildings['one']->UbicaEdificioNombre)  }}</p>
                    </div>                                                         
                    <div class="form-group col-sm-6">                        
                        <label class="form-label-full">Clave</label>
                        <p class="help-block">{{ ifempty($buildings['one']->UbicaEdificioClave)  }}</p>
                    </div>                                        
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Edificio Alias</label>
                        <p class="help-block">{{ ifempty($buildings['one']->UbicaEdificioAlias)  }}</p>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Descripción</label>
                        <p class="help-block help-block--textarea">{{ ifempty($buildings['one']->UbicaEdificioDescripcion)  }}</p>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Área de desplante (m2)</label>
                        <p class="help-block">{{ number_format(ifempty($buildings['one']->UbicaEdificioAreaDesplante, '0'), 2) }}</p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Área nominal (m2)</label>
                        <div class="row">
                            <div class="col-sm-6">
                                <p class="help-block">Interior: {{ number_format(ifempty($buildings['one']->UbicaEdificioAreaTotal, '0'), 2) }}</p>
                            </div>
                            <div class="col-sm-6">
                                <p class="help-block">Exterior: {{ number_format(ifempty($buildings['one']->UbicaEdificioAreaTotalExterior, '0'), 2) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Áreas registradas en sistema (m2)</label>
                        <div class="row">
                            <div class="col-sm-6">
                                <p class="help-block">Interior: {{ number_format($buildings['area']['int'], 2) }}</p>
                            </div>
                            <div class="col-sm-6">
                                <p class="help-block">Exterior: {{ number_format($buildings['area']['ext'], 2) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Niveles totales conocidos</label>
                        <p class="help-block">{{ number_format(ifempty($buildings['one']->UbicaEdificioNiveles, '0'), 1) }}</p>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Niveles totales registrados</label>
                        <p class="help-block">{{ number_format($buildings['levels']['total'], 1)  }}</p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-12 text-right">
                        <label>
                            Cerrado:
                            @if (isset($filter['queries']['status']) && $filter['queries']['status'] == 'deleted')
                                @if (!empty($buildings['one']->RegistroCerrado))
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $buildings['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" checked disabled>
                                    <br>
                                    <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                @else
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $buildings['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" disabled>
                                @endif
                            @else
                                @if (!empty($buildings['one']->RegistroCerrado))
                                    @if (Auth::user()['role'] < $buildings['one']->RegistroRol || Auth::user()['role'] == 1)
                                        <input type="checkbox" name="cto34_close" value="1" data-id="{{ $buildings['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" checked>
                                        <br>
                                        <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                    @else
                                        <input type="checkbox" name="cto34_close" value="1" data-id="{{ $buildings['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" checked disabled>
                                        <br>
                                        <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                    @endif
                                @else
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $buildings['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}">
                                @endif
                            @endif
                        </label>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane row padding-top--5" id="levels">
                    <div class="col-sm-12">
                        <div class="row margin-bottom--5">
                            <div class="col-sm-12">
                                <p class="help-block">
                                    <b>Niveles totales:</b> {{ number_format($buildings['levels']['total'], 2, ',', '.')  }} /
                                    <b>Superficies:</b> Interior: {{ number_format($buildings['levels']['surfaces']['int'], 2, ',', '.')  }} - Exterior: {{ number_format($buildings['levels']['surfaces']['ext'], 2, ',', '.')  }}
                                </p>
                            </div>
                        </div>
                        <div class="items-relation">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Edificio</th>
                                        <th>Consecutivo</th>
                                        <th>Nivel</th>
                                        <th>Área Interior (m2)</th>
                                        <th>Área Exterior (m2)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse ($buildings['one']->levels as $level)
                                     <tr data-id="{{ $level->tbUbicaNivelID  }}" data-toggle="modal" data-target="#showLevelModal">
                                        <td>
                                            {{ $level->building->UbicaEdificioAlias }}
                                        </td>
                                        <td>
                                            {{ $level->UbicaNivelConsecutivo }}
                                        </td>
                                        <td>
                                            {{ str_limit($level->UbicaNivelAlias, 30) }}
                                        </td>
                                        <td>
                                            {{ number_format($level->UbicaNivelSuperficie, 2, ',', '.') }}
                                        </td>
                                        <td>
                                            {{ number_format($level->UbicaNivelSuperficieExterior, 2, ',', '.') }}
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
                    <input type="hidden" name="level_count" value="{{ $buildings['one']->levels->count() }}">
                </div>
                <div role="tabpanel" class="tab-pane row padding-top--5" id="locals">
                    <?php $totalLocals = 0 ?>
                    <div class="col-sm-12">
                        <div class="row margin-bottom--5">
                            <div class="col-sm-12">
                                <p class="help-block">
                                    <b>Locales totales:</b> {{ number_format($buildings['locals']['total'], 2, ',', '.')  }} /
                                    <b>Superficies:</b> Interior: {{ number_format($buildings['locals']['area']['int'], 2, ',', '.')  }} - Exterior: {{ number_format($buildings['locals']['area']['ext'], 2, ',', '.')  }}
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
                                @if (!empty($buildings['one']->levels))
                                    @foreach($buildings['one']->levels as $level)
                                        @if (!empty($level->locals))
                                            @foreach ($level->locals as $local)
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
                                                    {{ number_format($local->UbicaLocalArea, 2, ',', '.') }}
                                                </td>
                                                <td>
                                                    {{ number_format($local->UbicaLocalAreaExterior, 2, ',', '.') }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="5">No hay registros</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">No hay registros</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--
        Este campo sirve para indicar en el listado que registro se esta visualizando
    --}}
    <input type="hidden" name="_recordId" value="{{ $buildings['one']->getKey() }}">
@endif