@if($works['all']->count() == 0)
    <div class="panel-body text-center">
        <h4>No hay obras registrados</h4>
        <a href="{{ $navigation['base'].'/save' }}" class="btn btn-link">Nuevo nivel</a>
    </div>
@else
    <div class="panel-body padding-top--clear">
        <div class="list-group col-sm-5 col-md-4 col-lg-3 margin-clear panel-item" style="position: relative;">
            <div class="list-group-item padding-clear padding-bottom--5">
                {{--
                    Content Search
                    Se incluyen la forma para la busqueda y filtrado de datos
                --}}
                @include('panel.constructionwork.home.shared.content-search')
            </div>
            <div id="itemsList">
                @foreach ($works['all'] as $index => $work)
                    @if ($work->tbObraID  == $works['one']->tbObraID )
                        <a id="item{{ $work->tbObraID  }}" href="{{ $navigation['base'].$navigation['section'] }}/{{ $work->tbObraID  }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="list-group-item active">
                            <h4 class="list-group-item-heading">{{ $work->ObraAlias }}</h4>
                            <p class="small">
                                {{ $work->ObraNombreCompleto }}
                            </p>
                        </a>
                        @continue
                    @endif
                    <a id="item{{ $work->tbObraID  }}" href="{{ $navigation['base'].$navigation['section'] }}/{{ $work->tbObraID  }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="list-group-item">
                        <h4 class="list-group-item-heading">{{ $work->ObraAlias }}</h4>
                        <p class="text-muted small">
                            {{ $work->ObraNombreCompleto }}
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
                        <a href="#dates" aria-controls="general" role="tab" data-toggle="tab" data-type="own">
                            Fechas
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#details" aria-controls="general" role="tab" data-toggle="tab" data-type="own">
                            Detalles
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-content col-sm-12 margin-bottom--20">
                <div role="tabpanel" class="tab-pane active row padding-top--5" id="general">
                    <div class="col-sm-4">
                        @if( empty($works['one']->ObraImagen) )
                            <div class="panel-item--image"><p>No disponible.</p></div>
                        @else
                            <div class="panel-item--image" style="background-image: url({{ url('panel/images/'.$dailywork['one']->DiarioImagen) }}) ">
                                <div class="panel-item--image_nav">
                                    <button type="button" class="btn btn-primary btn-xs is-tooltip" title="Ver" data-image="{{ url('panel/images/'.$works['one']->ObraImagen) }}" data-toggle="modal" data-target="#showImageModal" data-placement="bottom">
                                        <span class="fa fa-eye fa-fw"></span>
                                    </button>

                                    <a href="{{ url('panel/images/'.$works['one']->ObraImagen) }}" class="btn btn-primary btn-xs is-tooltip" title="Descargar" data-placement="bottom" download>
                                        <span class="fa fa-download fa-fw"></span>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="form-group col-sm-8">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">Obra</label>
                                <p class="help-block">{{ ifempty($works['one']->ObraAlias) }}</p>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label-full">Índice</label>
                                <p class="help-block">{{ ifempty($works['one']->ObraIndice) }}</p>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label-full">Clave</label>
                                <p class="help-block">{{ ifempty($works['one']->ObraClave) }}</p>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">Propietario</label>
                                <p class="help-block">
                                    @if (!empty($works['one']->owner))
                                        {{ $works['one']->owner->business->EmpresaAlias }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Nombre completo</label>
                        <p class="help-block">{{ ifempty($works['one']->ObraNombreCompleto) }}</p>
                    </div>
                    <div class="form-group col-sm-8">
                        <label class="form-label-full">Descripción completa</label>
                        <p class="help-block help-block--textarea">{{ ifempty($works['one']->ObraDescripcion) }}</p>
                    </div>
                    <div class="form-group col-sm-4">
                        <label class="form-label-full">Descripción corta</label>
                        <p class="help-block help-block--textarea">{{ ifempty($works['one']->ObraDescripcionCorta) }}</p>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Sucursal </label>
                        <p class="help-block">{{ ifempty($works['one']->ObraSucursalNombre) }}</p>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Domicilio</label>
                        <p class="help-block"> - </p>
                    </div>
                    <div class="form-group col-sm-12 text-right">
                        <label>
                            Cerrado:
                            @if (isset($filter['queries']['status']) && $filter['queries']['status'] == 'deleted')
                                @if (!empty($works['one']->RegistroCerrado))
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $works['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" checked disabled>
                                    <br>
                                    <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                @else
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $works['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" disabled>
                                @endif
                            @else
                                @if (!empty($works['one']->RegistroCerrado))
                                    @if (Auth::user()['role'] < $works['one']->RegistroRol || Auth::user()['role'] == 1)
                                        <input type="checkbox" name="cto34_close" value="1" data-id="{{ $works['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" checked>
                                        <br>
                                        <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                    @else
                                        <input type="checkbox" name="cto34_close" value="1" data-id="{{ $works['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" checked disabled>
                                        <br>
                                        <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                    @endif
                                @else
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $works['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}">
                                @endif
                            @endif
                        </label>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane row padding-top--5" id="dates">
                    <div class="form-group col-sm-4">
                        <label class="form-label-full">Inicio oficial</label>
                        <p class="help-block">{{ Carbon\Carbon::parse( $works['one']->ObraFechaInicioOficial )->formatLocalized('%A %d %B %Y') }}</p>
                    </div>
                    <div class="form-group col-sm-4">
                        <label class="form-label-full">Término oficial</label>
                        <p class="help-block">{{ Carbon\Carbon::parse( $works['one']->ObraTerminoFechaOficial )->formatLocalized('%A %d %B %Y') }}</p>
                    </div>
                    <div class="form-group col-sm-4">
                        <label class="form-label-full">Duración oficial</label>
                        <p class="help-block">{{ ifempty($works['one']->ObraDuracionOficial, '0') }} Días</p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-4">
                        <label class="form-label-full">Inicio real</label>
                        <p class="help-block">{{ Carbon\Carbon::parse( $works['one']->ObraFechaInicioReal)->formatLocalized('%A %d %B %Y') }}</p>
                    </div>
                    <div class="form-group col-sm-4">
                        <label class="form-label-full">Término real</label>
                        <p class="help-block">{{ Carbon\Carbon::parse( $works['one']->ObraFechaTerminoReal )->formatLocalized('%A %d %B %Y') }}</p>
                    </div>
                    <div class="form-group col-sm-4">
                        <label class="form-label-full">Duración real</label>
                        <p class="help-block">{{ ifempty($works['one']->ObraDuracionReal, '0') }} Días</p>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane row padding-top--5" id="details">
                    <div class="form-group col-sm-4">
                        <label class="form-label-full">Superficie interior</label>
                        <p class="help-block">{{ number_format( ifempty($works['one']->ObraSuperficieInterior, 0), 2) }}</p>
                    </div>
                    <div class="form-group col-sm-4">
                        <label class="form-label-full">Superficie exterior</label>
                        <p class="help-block">{{ number_format( ifempty($works['one']->ObraSuperficieExterior, 0), 2) }}</p>
                    </div>
                    <div class="form-group col-sm-4">
                        <label class="form-label-full">Superficie total</label>
                        <p class="help-block">{{ number_format(ifempty($works['one']->ObraSuperficieTotal, 0), 2) }}</p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-4">
                        <label class="form-label-full">Tipo de obra</label>
                        <p class="help-block">{{ ifempty($works['one']->ObraTipo) }}</p>
                    </div>
                   <!-- <div class="form-group col-sm-4">
                        <label class="form-label-full">Genero</label>
                        <p class="help-block">{{ ifempty($works['one']->TnGeneroID_Obra)}}</p>
                    </div>-->
                </div>
            </div>
        </div>
    </div>
@endif