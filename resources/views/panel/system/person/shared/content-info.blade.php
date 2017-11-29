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
                            Detalles
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
                @include('panel.system.person.shared.content-search')
            </div>
            <div id="itemsList">
                @foreach ($persons['all'] as $index => $person)
                    @if ($person->tbDirPersonaID == $persons['one']->tbDirPersonaID)
                        <div id="item{{ $person->tbDirPersonaID }}" class="list-group-item active">
                            <h4 class="list-group-item-heading">{{ $person->PersonaAlias }}</h4>
                            <p class="small">
                                {{ $person->PersonaNombreCompleto }}
                            </p>
                        </div>
                        @continue
                    @endif
                    <a id="item{{ $person->tbDirPersonaID }}" href="{{ $navigation['base'].$navigation['section'] }}/{{ $person->tbDirPersonaID  }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="list-group-item">
                        <h4 class="list-group-item-heading">{{ $person->PersonaAlias }}</h4>
                        <p class="text-muted small">
                            {{ $person->PersonaNombreCompleto }}
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
                        <a href="#details" aria-controls="details" role="tab" data-toggle="tab">
                            Detalles
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#" aria-controls="phones" role="tab" data-toggle="tab" data-type="relation" data-element="#">
                            Teléfonos
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#" aria-controls="emails" role="tab" data-toggle="tab" data-type="relation" data-element="">
                            Correos
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#" aria-controls="adressess" role="tab" data-toggle="tab" data-type="relation" data-element="">
                            Direcciones
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#comments" aria-controls="comments" role="tab" data-toggle="tab" data-type="relation" data-element="#modalComment">
                            Comentarios
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-content col-sm-12 margin-bottom--20">
                <div role="tabpanel" class="tab-pane active row padding-top--5" id="general">
                    <div class="col-sm-4">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">Imagen</label>
                                @if( empty($persons['one']->PersonaFoto) )
                                    <div class="panel-item--image"><p>No disponible.</p></div>
                                @else
                                    <div class="panel-item--image" style="background-image: url({{ url('panel/images/'.$dailywork['one']->DiarioImagen) }}) ">
                                        <div class="panel-item--image_nav">
                                            <button type="button" class="btn btn-primary btn-xs is-tooltip" title="Ver" data-image="{{ url('panel/images/'.$persons['one']->PersonaFoto) }}" data-toggle="modal" data-target="#showImageModal" data-placement="bottom">
                                                <span class="fa fa-eye fa-fw"></span>
                                            </button>
                                            <a href="{{ url('panel/images/'.$persons['one']->PersonaFoto) }}" class="btn btn-primary btn-xs is-tooltip" title="Descargar" data-placement="bottom" download>
                                                <span class="fa fa-download fa-fw"></span>
                                            </a>
                                        </div>
                                    </div>
                                @endif
                                <input id="cto34_img"
                                       name="cto34_img"
                                       type="file"
                                       value="{{ old('cto34_img') }}"
                                       class="form-control form-control-plain input-sm"
                                       disabled
                                       accept="image/gif, image/jpeg, image/jpg, image/png" />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">Nombre por apellidos</label>
                                <p class="help-block">{{ $persons['one']->PersonaAlias }}</p>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">Nombre directo</label>
                                <p class="help-block">{{ $persons['one']->PersonaNombreDirecto }}</p>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">Nombre completo</label>
                                <p class="help-block">{{ $persons['one']->PersonaNombreCompleto }}</p>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label-full">Genero</label>
                                <p class="help-block">{{ ifempty($persons['one']->PersonaGenero) }}</p>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label-full">Prefijo</label>
                                <p class="help-block">{{ ifempty($persons['one']->PersonaPrefijo) }}</p>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group col-sm-6">
                                <label class="form-label-full">Nombres</label>
                                <p class="help-block">{{ ifempty($persons['one']->PersonaNombres) }}</p>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label-full">Apellido paterno</label>
                                <p class="help-block">{{ ifempty($persons['one']->PersonaApellidoPaterno) }}</p>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label-full">Apellido materno</label>
                                <p class="help-block">{{ ifempty($persons['one']->PersonaApellidoMaterno) }}</p>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label-full">Saludo</label>
                                <p class="help-block">{{ ifempty($persons['one']->PersonaSaludo) }}</p>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label-full">Fecha de nacimiento</label>
                                <p class="help-block">
                                    @if ($persons['one']->PersonaFechaNacimiento != '0000-00-00' && !empty($persons['one']->PersonaFechaNacimiento))
                                        {{ Carbon\Carbon::parse($persons['one']->PersonaFechaNacimiento )->formatLocalized('%A %d %B %Y')  }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-12 text-right">
                        <label>
                            Cerrado:
                            @if (isset($filter['queries']['status']) && $filter['queries']['status'] == 'deleted')
                                @if (!empty($persons['one']->RegistroCerrado))
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $persons['one']->getKey() }}" checked disabled>
                                    <br>
                                    <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                @else
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $persons['one']->getKey() }}" disabled>
                                @endif
                            @else
                                @if (!empty($persons['one']->RegistroCerrado))
                                    @if (Auth::user()['role'] < $persons['one']->RegistroRol || Auth::user()['role'] == 1)
                                        <input type="checkbox" name="cto34_close" value="1" data-id="{{ $persons['one']->getKey() }}" checked>
                                        <br>
                                        <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                    @else
                                        <input type="checkbox" name="cto34_close" value="1" data-id="{{ $persons['one']->getKey() }}" checked disabled>
                                        <br>
                                        <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                    @endif
                                @else
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $persons['one']->getKey() }}">
                                @endif
                            @endif
                        </label>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane row padding-top--5" id="details">
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Identificación tipo</label>
                        <p class="help-block">{{ ifempty($persons['one']->PersonaIdentificacionTipo) }}</p>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Identificación número</label>
                        <p class="help-block">{{ ifempty($persons['one']->PersonaIdentificacionNumero) }}</p>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Contacto de emergencia</label>
                        <p class="help-block help-block--textarea">{{ ifempty($persons['one']->PersonaContactoEmergencia) }}</p>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Fecha alta</label>
                        <p class="help-block">
                            @if ($persons['one']->PersonaFechaAlta != '0000-00-00' && !empty($persons['one']->PersonaFechaAlta))
                                {{ Carbon\Carbon::parse($persons['one']->PersonaFechaAlta )->formatLocalized('%A %d %B %Y')  }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="form-group col-sm-6">
                        <lavel class="form-label-full">Fecha baja</lavel>
                        <p class="help-block">
                            @if ($persons['one']->PersonaFechaBaja != '0000-00-00' && !empty($persons['one']->PersonaFechaBaja))
                                {{ Carbon\Carbon::parse($persons['one']->PersonaFechaBaja )->formatLocalized('%A %d %B %Y')  }}
                            @else
                                -
                            @endif
                        </p>
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
    <input type="hidden" name="_recordId" value="{{ $persons['one']->getKey() }}">
@endif