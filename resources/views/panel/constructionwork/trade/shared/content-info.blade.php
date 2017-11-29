@if($trades['all']->count() == 0)
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
                            Envios
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
                @include('panel.constructionwork.trade.shared.content-search')
            </div>
            <div id="itemsList">
                @foreach ($trades['all'] as $index => $trade)
                    @if ($trades['one']->tbOficioID == $trade->tbOficioID)
                        <div id="item{{ $trade->tbOficioID }}" class="list-group-item active">
                            <h4 class="list-group-item-heading">{{ $trade->OficioFolio }}</h4>
                            <p class="small">
                                {{ Carbon\Carbon::parse($trade->OficioFechaExpedicion)->formatLocalized('%A %d %B %Y') }}
                            </p>
                        </div>
                        @continue
                    @endif
                    <a id="item{{ $trade->tbOficioID }}" href="{{ $navigation['base'].$navigation['section'] }}/{{ $trade->tbOficioID  }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="list-group-item">
                        <h4 class="list-group-item-heading">{{ $trade->OficioFolio }}</h4>
                        <p class="small text-muted">
                            {{ Carbon\Carbon::parse($trade->OficioFechaExpedicion)->formatLocalized('%A %d %B %Y') }}
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
                        <a href="#paragraphs" aria-controls="general" role="tab" data-toggle="tab">
                            Redacción
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#details" aria-controls="general" role="tab" data-toggle="tab">
                            Detalles
                        </a>
                    </li>
                    <li role="presentation" class="disabled">
                        <a href="#">
                            Envios
                        </a>
                    </li>
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
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Folio</label>
                        <p class="help-block">
                            {{ ifempty($trades['one']->OficioFolio) }}
                        </p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Localidad</label>
                        <p class="help-block">
                            @if (isset($trades['one']->location))
                                {{ $trades['one']->location->LocalidadAlias }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Fecha de expedición</label>
                        <p class="help-block">
                            {{ Carbon\Carbon::parse($trades['one']->OficioFechaExpedicion)->formatLocalized('%A %d %B %Y') }}
                        </p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Persona destinataria</label>
                        <p class="help-block">
                            @if (isset($trades['one']->personReceiver))
                                @if (isset($trades['one']->personReceiver->person))
                                    {{ $trades['one']->personReceiver->person->PersonaNombreCompleto }}
                                @else
                                    -
                                @endif
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Empresa destinataria</label>
                        <p class="help-block">
                            @if (isset($trades['one']->businessReceiver))
                                @if (isset($trades['one']->businessReceiver->business))
                                    {{ $trades['one']->businessReceiver->business->EmpresaAlias }}
                                @else
                                    -
                                @endif
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Remitente</label>
                        <p class="help-block">
                            @if (isset($trades['one']->personSender))
                                @if (isset($trades['one']->personSender->person))
                                    {{ $trades['one']->personSender->person->PersonaNombreCompleto }}
                                @else
                                    -
                                @endif
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Redacción</label>
                        <p class="help-block help-block--textarea" style="height: 160px">
                            {{ ifempty($trades['one']->OficioAsunto) }}
                            {{ ifempty($trades['one']->OficioParrafo1) }}
                            {{ ifempty($trades['one']->OficioParrafo2) }}
                            {{ ifempty($trades['one']->OficioDespedida) }}
                        </p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-12 text-right">
                        <label>
                            Cerrado:
                            @if (isset($filter['queries']['status']) && $filter['queries']['status'] == 'deleted')
                                @if (!empty($trades['one']->RegistroCerrado))
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $trades['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" checked disabled>
                                    <br>
                                    <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                @else
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $trades['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" disabled>
                                @endif
                            @else
                                @if (!empty($trades['one']->RegistroCerrado))
                                    @if (Auth::user()['role'] < $trades['one']->RegistroRol || Auth::user()['role'] == 1)
                                        <input type="checkbox" name="cto34_close" value="1" data-id="{{ $trades['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" checked>
                                        <br>
                                        <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                    @else
                                        <input type="checkbox" name="cto34_close" value="1" data-id="{{ $trades['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" checked disabled>
                                        <br>
                                        <small class="text-muted">Por: {{ auth_permissions_data(Auth::user()['role'])->CTOUsuarioGrupoNombre }}</small>
                                    @endif
                                @else
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $trades['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}">
                                @endif
                            @endif
                        </label>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane row padding-top--5" id="paragraphs">
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Asunto</label>
                        <p class="help-block help-block--textarea">
                            {{ ifempty($trades['one']->OficioAsunto) }}
                        </p>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Parrafo 1</label>
                        <p class="help-block help-block--textarea">
                            {{ ifempty($trades['one']->OficioParrafo1) }}
                        </p>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Parrafo 2</label>
                        <p class="help-block help-block--textarea">
                            {{ ifempty($trades['one']->OficioParrafo2) }}
                        </p>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Despedida</label>
                        <p class="help-block help-block--textarea">
                            {{ ifempty($trades['one']->OficioDespedida) }}
                        </p>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane row padding-top--5" id="details">
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Adjuntos</label>
                        <p class="help-block">
                            {{ ifempty($trades['one']->OficioAdjuntos) }}
                        </p>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Con copia para</label>
                        <p class="help-block">
                            {{ ifempty($trades['one']->OficioCCP) }}
                        </p>
                    </div>
                    <!--<div class="form-group col-sm-6">
                        <label class="form-label-full">Recibido por</label>
                        <p class="help-block">
                            @if (isset($trades['one']->personReceivedBy))
                                @if (isset($trades['one']->personReceivedBy->person))
                                    {{ $trades['one']->personReceivedBy->person->PersonaNombreCompleto }}
                                @else
                                    -
                                @endif
                            @else
                                -
                            @endif
                        </p>
                    </div>-->
                    <!--<div class="form-group col-sm-6">
                        <label class="form-label-full">Recibido fecha</label>
                        <p class="help-block">
                            {{ Carbon\Carbon::parse($trades['one']->OficioRecibidoFecha)->formatLocalized('%A %d %B %Y') }}
                        </p>
                    </div>-->
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Seguimiento</label>
                        @if ($trades['one']->OficioSeguimiento == 1)
                            <span class="fa fa-check-square fa-fw text-info"></span>
                        @else
                            <span class="fa fa-square-o fa-fw"></span>
                        @endif
                    </div>
                    <!--<div class="form-group col-sm-6">
                        <label class="form-label-full">Asunto cerrado fecha</label>
                        <p class="help-block">
                            {{ Carbon\Carbon::parse($trades['one']->OficioAsuntoCerradoFecha)->formatLocalized('%A %d %B %Y') }}
                        </p>
                    </div>-->
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Capturado por</label>
                        @if( empty( $trades['one']->catcher))
                            <p class="help-block"> - </p>
                        @else
                            @if( !empty( $trades['one']->catcher->person ) )
                                <a href="#" data-id="{{  $trades['one']->tbCTOUsuarioID_OficioCaptura  }}" data-toggle="modal" data-target="#showPersonModal" class="help-block">
                                    {{ ifempty($trades['one']->catcher->person->PersonaNombreDirecto)  }}
                                </a>
                            @else
                                <p class="help-block">-</p>
                            @endif
                        @endif
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Registro capturado timestamp</label>
                        <p class="help-block">{{ Carbon\Carbon::parse( $trades['one']->OficioTimestamp )->formatLocalized('%A %d %B %Y') }}</p>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Modificado por</label>
                        @if( empty( $trades['one']->modifier))
                            <p class="help-block"> - </p>
                        @else
                            @if( !empty( $trades['one']->modifier->person ) )
                                <a href="#" data-id="{{  $trades['one']->OficioModificadoPor  }}" data-toggle="modal" data-target="#showPersonModal" class="help-block">
                                    {{ ifempty($trades['one']->modifier->person->PersonaNombreDirecto)  }}
                                </a>
                            @else
                                <p class="help-block"> - </p>
                            @endif
                        @endif
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Registro modificado timestamp</label>
                        @if (!empty($trades['one']->OficioModificaTimestamp))
                            <p class="help-block">{{ Carbon\Carbon::parse( $trades['one']->OficioModificaTimestamp )->formatLocalized('%A %d %B %Y') }}</p>
                        @else
                            <p class="help-block"> - </p>
                        @endif
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane row padding-top--5" id="comments">
                    <div class="col-sm-12">
                        @forelse($trades['one']->comments as $comment)
                            <a href="#" data-id="{{ $comment->tbComentarioID  }}" data-toggle="modal" data-target="#showCommentModal" class="list-group-item" style="border-bottom: 1px solid #ddd">
                                <h5 class="list-group-item-heading">Comentario por {{ $comment->user->person->PersonaNombreCompleto  }}</h5>
                                <p class="list-group-item-text small text-muted">
                                    {{ $comment->Comentario }}<br>
                                    <small class="text-muted">
                                        @if (!empty($comment->ComentarioFecha))
                                            {{  Carbon\Carbon::parse($comment->ComentarioFecha)->formatLocalized('%A %d %B %Y') }}
                                        @endif
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
    {{--
        Este campo sirve para indicar en el listado que registro se esta visualizando
    --}}
    <input type="hidden" name="_recordId" value="{{ $trades['one']->getKey() }}">
@endif