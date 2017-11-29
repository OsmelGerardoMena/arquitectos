@if($binnacles['all']->count() == 0)
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
                            Redacción
                        </a>
                    </li>
                    <li role="presentation" class="disabled">
                        <a href="#">
                            Detalles
                        </a>
                    </li>
                    <li role="presentation" class="disabled">
                        <a href="#">
                            Pendientes
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
                @include('panel.constructionwork.binnacle.shared.content-search')
            </div>
            <div id="itemsList">
                @foreach ($binnacles['all'] as $index => $binnacle)
                    @if ($binnacles['one']->tbBitacoraID == $binnacle->tbBitacoraID)
                        <div id="item{{ $binnacle->tbBitacoraID }}" class="list-group-item active">
                            <h4 class="list-group-item-heading">{{ $binnacle->BitacoraNumero }}</h4>
                            <p class="small">
                                {{ $binnacle->contract->ContratoAlias }}
                            </p>
                        </div>
                        @continue
                    @endif
                    <a id="item{{ $binnacle->tbBitacoraID }}" href="{{ $navigation['base'].$navigation['section'] }}/{{ $binnacle->tbBitacoraID  }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="list-group-item">
                        <h4 class="list-group-item-heading">{{ $binnacle->BitacoraNumero }}</h4>
                        <p class="text-muted small">
                            {{ $binnacle->contract->ContratoAlias }}
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
                        <a href="#details" aria-controls="general" role="tab" data-toggle="tab" data-type="own">
                            Redacción
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#registers" aria-controls="general" role="tab" data-toggle="tab" data-type="own">
                            Detalles
                        </a>
                    </li>
                    <!--
                    <li role="presentation">
                        <a href="#shipments" aria-controls="general" role="tab" data-toggle="tab">
                            Envios
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#pendings" aria-controls="general" role="tab" data-toggle="tab">
                            Pendientes
                        </a>
                    </li>
                    -->
                    <li role="presentation" class="disabled">
                        <a href="#">
                            Pendientes
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
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Contrato</label>
                        <a href="#" class="help-block">{{ ifempty($binnacles['one']->contract->ContratoAlias)  }}</a>
                    </div>
                    <!--<div class="form-group col-sm-6">
                        <label class="form-label-full">Número</label>
                        <p class="help-block">{{ ifempty($binnacles['one']->BitacoraNumero, '0')  }}</p>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Fecha de ésta nota</label>
                        <p class="help-block">{{ Carbon\Carbon::parse($binnacles['one']->BitacoraFecha)->formatLocalized('%A %d %B %Y')  }}</p>
                    </div>-->
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Original o respuesta</label>
                        <p class="help-block">{{ ifempty($binnacles['one']->BitacoraNotaTipo)  }}</p>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Notas antecedente</label>
                        <p class="help-block"> - </p>
                    </div>
                    <div class="col-sm-12">

                        <label class="form-label-full">Borrador</label>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <p class="help-block">Nota No. {{ str_pad(ifempty($binnacles['one']->BitacoraNumero, '0'), 3, "0", STR_PAD_LEFT) }}</p>
                            </div>
                            <div class="form-group col-sm-6">
                                <p class="help-block">{{ Carbon\Carbon::parse($binnacles['one']->BitacoraFecha)->formatLocalized('%A %d %B %Y')  }}</p>
                            </div>
                            <div class="form-group col-sm-12">
                                <p class="help-block help-block--textarea" style="height: 210px">{{ ifempty($binnacles['one']->BitacoraNotaCompleta) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-12 text-right">
                        <label>
                            Cerrado:
                            @if (isset($filter['queries']['status']) && $filter['queries']['status'] == 'deleted')
                                @if (!empty($binnacles['one']->RegistroCerrado))
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $binnacles['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" checked disabled>
                                    <br>
                                    <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                @else
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $binnacles['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" disabled>
                                @endif
                            @else
                                @if (!empty($binnacles['one']->RegistroCerrado))
                                    @if (Auth::user()['role'] < $binnacles['one']->RegistroRol || Auth::user()['role'] == 1)
                                        <input type="checkbox" name="cto34_close" value="1" data-id="{{ $binnacles['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" checked>
                                        <br>
                                        <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                    @else
                                        <input type="checkbox" name="cto34_close" value="1" data-id="{{ $binnacles['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" checked disabled>
                                        <br>
                                        <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                    @endif
                                @else
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $binnacles['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}">
                                @endif
                            @endif
                        </label>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane row padding-top--5" id="details">
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="form-label-full">Grupo</label>
                                <p class="help-block">{{ ifempty($binnacles['one']->BitacoraGrupo)  }}</p>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label-full">Destino</label>
                                <p class="help-block">{{ ifempty($binnacles['one']->BitacoraDestino)  }}</p>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">Descripción</label>
                                <p class="help-block">{{ ifempty($binnacles['one']->BitacoraDescripcion)  }}</p>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">Ubicación</label>
                                <p class="help-block">{{ ifempty($binnacles['one']->BitacoraUbicacion)  }}</p>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">Causas</label>
                                <p class="help-block">{{ ifempty($binnacles['one']->BitacoraCausas)  }}</p>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">Solución</label>
                                <p class="help-block">{{ ifempty($binnacles['one']->BitacoraSolucion)  }}</p>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">Plazo Descripción</label>
                                <p class="help-block">{{ ifempty($binnacles['one']->BitacoraPlazoDescripcion)  }}</p>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">Fecha Compromiso</label>
                                <p class="help-block">{{ Carbon\Carbon::parse($binnacles['one']->BitacoraFechaCompromiso)->formatLocalized('%A %d %B %Y')  }}</p>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">Fecha Cumplimiento</label>
                                <p class="help-block"> - </p>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">Prevención</label>
                                <p class="help-block">{{ ifempty($binnacles['one']->BitacoraPrevencion)  }}</p>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">Costo</label>
                                <p class="help-block">{{ ifempty($binnacles['one']->BitacoraCosto)  }}</p>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">Sanciones</label>
                                <p class="help-block">{{ ifempty($binnacles['one']->BitacoraSanciones)  }}</p>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">Anexos</label>
                                <p class="help-block">{{ ifempty($binnacles['one']->BitacoraAnexos)  }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="form-label-full">Borrador</label>
                                <p class="help-block">Nota No. {{ str_pad(ifempty($binnacles['one']->BitacoraNumero, '0'), 3, "0", STR_PAD_LEFT) }}</p>
                            </div>
                            <div class="form-group col-sm-12">
                                <p class="help-block">{{ Carbon\Carbon::parse($binnacles['one']->BitacoraFecha)->formatLocalized('%A %d %B %Y')  }}</p>
                            </div>
                            <div class="form-group col-sm-12">
                                <p class="help-block">{{ ifempty($binnacles['one']->BitacoraNotaCompleta) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane row padding-top--5" id="registers">
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Autor</label>
                        @if (isset($binnacles['one']->author))
                            @if (isset($binnacles['one']->author->personBusiness))
                                @if (isset($binnacles['one']->author->personBusiness->person))
                                    <p class="help-block">{{ $binnacles['one']->author->personBusiness->person->PersonaNombreCompleto  }}</p>
                                @else
                                    <p class="help-block">-</p>
                                @endif
                            @else
                                <p class="help-block">-</p>
                            @endif
                        @else
                            <p class="help-block">-</p>
                        @endif
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Nota autorizada por</label>
                        @if (isset($binnacles['one']->auth))
                            @if (isset($binnacles['one']->auth->person))
                                <p class="help-block">{{ $binnacles['one']->auth->person->PersonaNombreCompleto  }}</p>
                            @else
                                <p class="help-block">-</p>
                            @endif
                        @else
                            <p class="help-block">-</p>
                        @endif
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Capturado por</label>
                        @if( empty( $binnacles['one']->catcher))
                            <p class="help-block"> - </p>
                        @else
                            @if( !empty( $binnacles['one']->catcher->person ) )
                                <a href="#" data-id="{{  $binnacles['one']->tbCTOUsuarioID_OficioCaptura  }}" data-toggle="modal" data-target="#showPersonModal" class="help-block">
                                    {{ ifempty($binnacles['one']->catcher->person->PersonaNombreDirecto)  }}
                                </a>
                            @else
                                <p class="help-block">-</p>
                            @endif
                        @endif
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Registro capturado timestamp</label>
                        @if (!empty($binnacles['one']->created_at))
                            <p class="help-block">{{ Carbon\Carbon::parse($binnacles['one']->created_at)->formatLocalized('%A %d %B %Y')  }}</p>
                        @else
                            <p class="help-block">-</p>
                        @endif
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Modificado por</label>
                        @if( empty( $binnacles['one']->modifier))
                            <p class="help-block"> - </p>
                        @else
                            @if( !empty( $binnacles['one']->modifier->person ) )
                                <a href="#" data-id="{{  $binnacles['one']->OficioModificadoPor  }}" data-toggle="modal" data-target="#showPersonModal" class="help-block">
                                    {{ ifempty($binnacles['one']->modifier->person->PersonaNombreDirecto)  }}
                                </a>
                            @else
                                <p class="help-block"> - </p>
                            @endif
                        @endif
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Registro modificado timestamp</label>
                        @if (!empty($binnacles['one']->updated_at))
                            <p class="help-block">{{ Carbon\Carbon::parse($binnacles['one']->updated_at)->formatLocalized('%A %d %B %Y')  }}</p>
                        @else
                            <p class="help-block">-</p>
                        @endif
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane row" id="pendings"></div>
                <div role="tabpanel" class="tab-pane row" id="comments">
                    <div class="col-sm-12 margin-top--5">
                        <div class="row">
                            <div class="col-sm-8"><b>Comentario</b></div>
                            <div class="col-sm-4"><b>Autor</b></div>
                            <div class="col-sm-12 margin-top--5 margin-bottom--5">
                                <hr class="margin-clear">
                            </div>
                        </div>
                        <div class="items-relation">
                            @forelse($binnacles['one']->comments as $comment)
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
    <input type="hidden" name="_recordId" value="{{ $binnacles['one']->getKey() }}">
@endif