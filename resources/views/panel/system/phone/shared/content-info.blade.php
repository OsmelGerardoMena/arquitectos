@if($phones['all']->count() == 0)
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
                @include('panel.system.phone.shared.content-search')
            </div>
            <div id="itemsList">
                @foreach ($phones['all'] as $index => $phone)

                    @if ($phone->tbDirTelefonoID == $phones['one']->tbDirTelefonoID)
                        <div id="item{{ $phone->tbDirTelefonoID }}" class="list-group-item active">
                            <h4 class="list-group-item-heading">
                            {{ $phone->DirTelefonoNumero }} |
                            {{ $phone->DirTelefonoTipo }}
                            </h4>
                            <p class="small">
                                {{ Carbon\Carbon::parse($phone->created_at )->formatLocalized('%d %B %Y') }}
                            </p>
                        </div>
                        @continue
                    @endif

                    <a id="item{{ $phone->tbDirTelefonoID }}" href="{{ $navigation['base'].$navigation['section'] }}/{{ $phone->tbDirTelefonoID  }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="list-group-item">
                            <h4 class="list-group-item-heading">
                            {{ $phone->DirTelefonoNumero }} |
                            {{ $phone->DirTelefonoTipo }}
                            </h4>
                        <p class="small">
                            {{ Carbon\Carbon::parse($phone->created_at )->formatLocalized('%d %B %Y') }}
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
                        <a href="#comments" aria-controls="general" role="tab" data-toggle="tab" data-type="relation" data-element="#modalComment">
                            Comentarios
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-content col-sm-12 margin-bottom--20">
                <div role="tabpanel" class="tab-pane active row padding-top--5" id="general">
                   <div class="form-group col-sm-6 col-md-10">
                        <label for="cto34_business" class="form-label-full">Empresa</label>
                        <p class="help-block">{{ isset($phones['one']->business->EmpresaAlias) ?$phones['one']->business->EmpresaAlias:'Empresa no encontrada' }}</p>
                    </div> 
                    <div class="clearfix"></div>

                    <div class="form-group col-sm-6 col-md-10">
                        <label for="cto34_asigned" class="form-label-full">Persona</label>
                        <p class="help-block">{{ isset($phones['one']->person->PersonaAlias)?$phones['one']->person->PersonaAlias:'Persona no encontrada' }}</p>
                    </div>
                    <div class="clearfix"></div>

                    <div class="form-group col-sm-6 col-md-10">
                        <label for="cto34_label" class="form-label-full">Etiqueta</label>
                        <p class="help-block">{{ $phones['one']->DirTelefonoEtiqueta }}</p>
                    </div>
                    <div class="clearfix"></div>

                    <div class="form-group col-sm-6 col-md-6">
                        <label for="cto34_phone_type" class="form-label-full">Teléfono tipo</label>
                        <p class="help-block">{{ $phones['one']->DirTelefonoTipo }}</p>
                    </div>

                    
                    <div class="form-group col-sm-6 col-md-6">
                        <label for="cto34_area_code" class="form-label-full">Clave lada</label>
                        <p class="help-block">{{ $phones['one']->DirTelefonoLada }}</p>
                    </div>
                    <div class="clearfix"></div>


                    <div class="form-group col-sm-6 col-md-6">
                        <label for="cto34_phone_number" class="form-label-full">Número teléfonico</label>
                        <p class="help-block">{{ $phones['one']->DirTelefonoNumero }}</p>
                    </div>
                    <div class="form-group col-sm-6 col-md-6">
                        <label for="cto34_ext" class="form-label-full">Extensión</label>
                        <p class="help-block">{{ $phones['one']->DirTelefonoExtension }}</p>
                    </div>
                    <div class="form-group col-sm-3 col-sm-offset-6">
                        <label for="cto34_ext" class="form-label-full">
                            Status: 
                            {{ $phones['one']->RegistroInactivo == 1?'Inactivo':'Activo' }}
                        </label>
                    </div>
                    <div class="form-group col-sm-3 text-right">
                        <label>
                            Cerrado:
                            @if (isset($filter['queries']['status']) && $filter['queries']['status'] == 'deleted')
                                @if (!empty($phones['one']->RegistroCerrado))
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $phones['one']->getKey() }}" checked disabled>
                                    <br>
                                    <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                @else
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $phones['one']->getKey() }}" disabled>
                                @endif
                            @else
                                @if (!empty($phones['one']->RegistroCerrado))
                                    @if (Auth::user()['role'] < $phones['one']->RegistroRol || Auth::user()['role'] == 1)
                                        <input type="checkbox" name="cto34_close" value="1" data-id="{{ $phones['one']->getKey() }}" checked>
                                        <br>
                                        <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                    @else
                                        <input type="checkbox" name="cto34_close" value="1" data-id="{{ $phones['one']->getKey() }}" checked disabled>
                                        <br>
                                        <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                    @endif
                                @else
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $phones['one']->getKey() }}">
                                @endif
                            @endif
                        </label>
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
                            @forelse($phones['one']->comments as $comment)
                                <a href="#" data-id="{{ $comment->tbComentarioID  }}" data-toggle="modal" data-target="#showCommentModal" class="list-group-item" style="border-bottom: 1px solid #ddd">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <p class="help-block help-block--textarea" style="background-color: #f5f5f5; border: 1px solid #f5f5f5">{{ $comment->Comentario }}</p>
                                        </div>
                                        <div class="col-sm-4">
                                            <small class="text-muted">
                                                <b>{{ $comment->user->person->PersonaNombreCompleto  }}</b><br>
                                                {{  Carbon\Carbon::parse($comment->ComentarioFecha)->formatLocalized('%A %d %B %Y') }}
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
    <input type="hidden" name="_recordId" value="{{ $phones['one']->getKey() }}">
@endif