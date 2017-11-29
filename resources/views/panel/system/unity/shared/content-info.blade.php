@if($unities['all']->count() == 0)
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
                @include('panel.constructionwork.dailywork.shared.content-search')
            </div>
            <div id="itemsList">
                @foreach ($unities['all'] as $index => $unity)
                    @if ($unity->tbUnidadID == $unities['one']->tbUnidadID)
                        <div id="item{{ $unity->tbUnidadID }}" class="list-group-item active">
                            <h4 class="list-group-item-heading">
                                {{ $unity->UnidadAlias }} |
                                {{ $unity->UnidadTipo }}
                            </h4>
                            <p class="small">
                                {{ Carbon\Carbon::parse($unity->created_at )->formatLocalized('%A %d %B %Y') }}
                            </p>
                        </div>
                        @continue
                    @endif
                    <a id="item{{ $unity->tbUnidadID }}" href="{{ $navigation['base'].$navigation['section'] }}/{{ $unity->tbUnidadID  }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="list-group-item">
                        <h4 class="list-group-item-heading">
                            {{ $unity->UnidadAlias }} |
                            {{ $unity->UnidadTipo }}
                        </h4>
                        <p class="text-muted small">
                            {{ Carbon\Carbon::parse($unity->created_at )->formatLocalized('%A %d %B %Y') }}
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
                        <a href="#general" aria-controls="general" role="tab" data-toggle="tab">
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
                <div role="tabpanel" class="tab-pane active row" id="general">
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">Unidad</label>
                                <p class="help-block">{{ $unities['one']->UnidadAlias }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">Tipo</label>
                                <p class="help-block">{{ $unities['one']->UnidadTipo }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">Nombre</label>
                                <p class="help-block">{{ $unities['one']->UnidadNombre }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">Nombre plural</label>
                                <p class="help-block">{{ $unities['one']->UnidadNombrePlural }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">Descripcion</label>
                                <p class="help-block">{{ $unities['one']->UnidadDescripcion }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-12 text-right">
                        <label>
                            Cerrado:
                            @if (isset($filter['queries']['status']) && $filter['queries']['status'] == 'deleted')
                                @if (!empty($unities['one']->RegistroCerrado))
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $unities['one']->getKey() }}" checked disabled>
                                    <br>
                                    <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                @else
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $unities['one']->getKey() }}" disabled>
                                @endif
                            @else
                                @if (!empty($unities['one']->RegistroCerrado))
                                    @if (Auth::user()['role'] < $unities['one']->RegistroRol || Auth::user()['role'] == 1)
                                        <input type="checkbox" name="cto34_close" value="1" data-id="{{ $unities['one']->getKey() }}" checked>
                                        <br>
                                        <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                    @else
                                        <input type="checkbox" name="cto34_close" value="1" data-id="{{ $unities['one']->getKey() }}" checked disabled>
                                        <br>
                                        <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                    @endif
                                @else
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $unities['one']->getKey() }}">
                                @endif
                            @endif
                        </label>
                    </div>
<!--                     <div class="col-sm-7">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">Folio</label>
                                <p class="help-block">Just{{-- ifempty($dailywork['one']->DiarioFolio, '0') --}}</p>
                            </div>
                        </div>
                    </div> -->
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
                            @forelse($unities['one']->comments as $comment)
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
    <input type="hidden" name="_recordId" value="{{ $unities['one']->getKey() }}">
@endif