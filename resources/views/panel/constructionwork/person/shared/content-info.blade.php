@if($persons['all']->count() == 0)
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
                            Teléfonos
                        </a>
                    </li>
                    <li role="presentation" class="disabled">
                        <a href="#">
                            Correos
                        </a>
                    </li>
                    <li role="presentation" class="disabled">
                        <a href="#">
                            Direcciones
                        </a>
                    </li>
                    <li role="presentation" class="disabled">
                        <a href="#">
                            Empresas
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
                @include('panel.constructionwork.person.shared.content-search')
            </div>
            <div id="itemsList">
                @foreach ($persons['all'] as $index => $person)
                    @if ($person->tbDirPersonaEmpresaObraID == $persons['one']->tbDirPersonaEmpresaObraID)
                        <div id="item{{ $person->tbDirPersonaEmpresaObraID }}" class="list-group-item active">
                            <h4 class="list-group-item-heading">
                                @if (!empty($person->personBusiness) )
                                    @if (!empty($person->personBusiness->person))
                                        {{ $person->personBusiness->person->PersonaAlias }}
                                    @else
                                        -
                                    @endif
                                @else
                                    -
                                @endif
                            </h4>
                            <p class="small">
                                @if (!empty($person->business) )
                                    {{ $person->business->EmpresaRazonSocial }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        @continue
                    @endif
                    <a id="item{{ $person->tbDirPersonaEmpresaObraID }}" href="{{ $navigation['base'].$navigation['section'] }}/{{ $person->tbDirPersonaEmpresaObraID  }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="list-group-item">
                        <h4 class="list-group-item-heading">
                            @if (!empty($person->personBusiness) )
                                @if (!empty($person->personBusiness->person))
                                    {{ $person->personBusiness->person->PersonaAlias }}
                                @else
                                    -
                                @endif
                            @else
                                -
                            @endif
                        </h4>
                        <p class="small">
                            @if (!empty($person->business) )
                                {{ $person->business->EmpresaRazonSocial }}
                            @else
                                -
                            @endif
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
                        <a href="#general" aria-controls="general" role="tab" data-toggle="tab"  data-type="own">General</a>
                    </li>
                    <li role="presentation">
                        <a href="#phones" aria-controls="concepts" role="tab" data-toggle="tab" data-type="relation" data-element="">Teléfonos</a>
                    </li>
                    <li role="presentation">
                        <a href="#emails" aria-controls="others" role="tab" data-toggle="tab" data-type="relation" data-element="">Correos</a>
                    </li>
                    <li role="presentation">
                        <a href="#addresses" aria-controls="others" role="tab" data-toggle="tab" data-type="relation" data-element="">Direcciones</a>
                    </li>
                    <li role="presentation">
                        <a href="#business" aria-controls="others" role="tab" data-toggle="tab" data-type="relation" data-element="">Empresas</a>
                    </li>
                    <li role="presentation">
                        <a href="#comments" aria-controls="comments" role="tab" data-toggle="tab" data-type="relation" data-element="#modalComment">Comentarios</a>
                    </li>
                </ul>
            </div>
            <div class="tab-content col-sm-12 margin-bottom--20">
                <div role="tabpanel" class="tab-pane row active" id="general">
                    <div class="col-sm-4">
                        <label for="cto34_imgLogo" class="form-label-full">Foto</label>
                        @if (!empty($persons['one']->personBusiness))
                            @if (!empty($persons['one']->personBusiness->person->PersonaFoto))
                                <div class="panel-item--image" style="background-image: url({{ url('panel/images/'.$persons['one']->personBusiness->person->PersonaFoto) }}) ">
                                    <div class="panel-item--image_nav">
                                        <button type="button" class="btn btn-primary btn-xs is-tooltip" title="Ver" data-image="{{ url('panel/images/'.$persons['one']->personBusiness->person->PersonaFoto) }}" data-toggle="modal" data-target="#showImageModal" data-placement="bottom">
                                            <span class="fa fa-eye fa-fw"></span>
                                        </button>
                                        <a href="{{ url('panel/images/'.$persons['one']->personBusiness->person->PersonaFoto) }}" class="btn btn-primary btn-xs is-tooltip" title="Descargar" data-placement="bottom" download>
                                            <span class="fa fa-download fa-fw"></span>
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="panel-item--image"><p>No disponible.</p></div>
                            @endif
                        @else
                            <div class="panel-item--image"><p>No disponible.</p></div>
                        @endif
                        <small>
                            @if (!empty($persons['one']->personBusiness) )
                                @if (!empty($persons['one']->personBusiness->person))
                                    {{ $persons['one']->personBusiness->person->PersonaNombreCompleto }}
                                @endif
                            @endif
                        </small>
                    </div>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="">Alias</label>
                                <p class="help-block">
                                    @if (!empty($persons['one']->personBusiness) )
                                        @if (!empty($persons['one']->personBusiness->person))
                                            <a href="#" data-id="{{  $persons['one']->tbDirPersonaID }}" data-toggle="modal" data-target="#showPersonModal" class="help-block">{{ $persons['one']->personBusiness->person->PersonaAlias }}</a>                                            
                                        @else
                                             -
                                        @endif
                                    @else
                                         --
                                    @endif
                                </p>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="">Empresa</label>
                                <p class="help-block">
                                    @if (!empty($persons['one']->business))
                                        {{ $persons['one']->business->EmpresaAlias }}
                                    @else
                                        --
                                    @endif
                                </p>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="">Categoría</label>
                                <p class="help-block">
                                    @if (!empty($persons['one']->personBusiness) )
                                        {{ $persons['one']->personBusiness->DirPersonaEmpresaCategoria }}
                                    @else
                                        --
                                    @endif
                                </p>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="">Cargo en la obra</label>
                                <p class="help-block">{{ ifempty($persons['one']->DirPersonaObraEmpresaCargoEnLaObra) }}</p>
                            </div>
                            <div class="form-group col-sm-12 text-right">
                                <label>
                                    Cerrado:
                                    @if (isset($filter['queries']['status']) && $filter['queries']['status'] == 'deleted')
                                        @if (!empty($persons['one']->RegistroCerrado))
                                            <input type="checkbox" name="cto34_close" value="1" data-id="{{ $persons['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" checked disabled>
                                            <br>
                                            <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                        @else
                                            <input type="checkbox" name="cto34_close" value="1" data-id="{{ $persons['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" disabled>
                                        @endif
                                    @else
                                        @if (!empty($persons['one']->RegistroCerrado))
                                            @if (Auth::user()['role'] < $persons['one']->RegistroRol || Auth::user()['role'] == 1)
                                                <input type="checkbox" name="cto34_close" value="1" data-id="{{ $persons['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" checked>
                                                <br>
                                                <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                            @else
                                                <input type="checkbox" name="cto34_close" value="1" data-id="{{ $persons['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" checked disabled>
                                                <br>
                                                <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                            @endif
                                        @else
                                            <input type="checkbox" name="cto34_close" value="1" data-id="{{ $persons['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}">
                                        @endif
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane row" id="phones">
                    <div class="col-sm-12">
                        @if (!empty($persons['one']->personBusiness) )
                            @if (!empty($persons['one']->personBusiness->person))
                                @forelse($persons['one']->personBusiness->person->phones as $row)
                                    <a href="#" data-id="{{ $row->tbDirTelefonoID  }}" data-toggle="modal" data-target="#showEstimateModal" class="list-group-item" style="border-bottom: 1px solid #ddd">
                                        <h5 class="list-group-item-heading">{{ $row->DirTelefonoCompleto }}</h5>
                                        <p class="list-group-item-text small text-muted">
                                            {{ $row->DirTelefonoEtiqueta }}
                                        </p>
                                    </a>
                                @empty
                                    <div class="list-group-item">
                                        No hay teléfonos registrados
                                    </div>
                                @endforelse
                            @else
                                <div class="list-group-item">
                                    No hay teléfonos registrados
                                </div>
                            @endif
                        @else
                            <div class="list-group-item">
                                No hay teléfonos registrados
                            </div>
                        @endif
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane row" id="emails"></div>
                <div role="tabpanel" class="tab-pane row" id="addresses"></div>
                <div role="tabpanel" class="tab-pane row" id="business">
                    <div class="col-sm-12">
                        @if (!empty($persons['one']->personBusiness) )
                            @if (!empty($persons['one']->personBusiness->businessAll))
                                @forelse($persons['one']->personBusiness->businessAll as $row)
                                    <a href="#" data-id="{{ $row->tbDirEmpresaID  }}" data-toggle="modal" data-target="#showEstimateModal" class="list-group-item" style="border-bottom: 1px solid #ddd">
                                        <h5 class="list-group-item-heading">{{ $row->EmpresaAlias }}</h5>
                                        <p class="list-group-item-text small text-muted">
                                            {{ $row->EmpresaRazonSocial }}
                                        </p>
                                    </a>
                                @empty
                                    <div class="list-group-item">
                                        No hay empresas registradas
                                    </div>
                                @endforelse
                            @else
                                <div class="list-group-item">
                                    No hay empresas registradas
                                </div>
                            @endif
                        @else
                            <div class="list-group-item">
                                No hay empresas registradas
                            </div>
                        @endif
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane row" id="comments">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-8"><b>Comentario</b></div>
                            <div class="col-sm-4"><b>Autor</b></div>
                            <div class="col-sm-12 margin-top--5 margin-bottom--5">
                                <hr class="margin-clear">
                            </div>
                        </div>
                        <div class="items-relation">
                            @forelse($persons['one']->comments as $comment)
                                <a href="#" data-id="{{ $comment->tbComentarioID  }}" data-toggle="modal" data-author="{{ $comment->tbCTOUsuarioID_Comentario }}" data-user="{{ Auth::id() }}" data-target="#showCommentModal" class="list-group-item" style="border-bottom: 1px solid #ddd">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <p id="commentDescription" class="help-block help-block--textarea" style="background-color: #f5f5f5; border: 1px solid #f5f5f5">{{ $comment->Comentario }}</p>
                                        </div>
                                        <div class="col-sm-4">
                                            <small class="text-muted">
                                                <b id="commentAuthor">{{ $comment->user->person->PersonaNombreCompleto  }}</b><br>
                                                <span id="commentDate">{{  Carbon\Carbon::parse($comment->ComentarioFecha)->formatLocalized('%A %d %B %Y') }}</span>
                                            </small>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="row">
                                    <div class="col-sm-12">No hay registros</div>
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
    <input type="hidden" name="_recordId" value="{{ $persons['one']->getKey() }}">
@endif