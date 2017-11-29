@if($business['all']->count() == 0)
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
                            Correos E.
                        </a>
                    </li>
                    <li role="presentation" class="disabled">
                        <a href="#">
                            Domicilios
                        </a>
                    </li>
                    <li role="presentation" class="disabled">
                        <a href="#">
                            Actas
                        </a>
                    </li>
                    <li role="presentation" class="disabled">
                        <a href="#">
                            Socios
                        </a>
                    </li>
                    <li role="presentation" class="disabled">
                        <a href="#">
                            Personas
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
                @include('panel.constructionwork.business.shared.content-search')
            </div>
            <div id="itemsList">
                @foreach ($business['all'] as $index => $b)
                    @if ($b->tbDirEmpresaObraID  == $business['one']->tbDirEmpresaObraID )
                        <div id="item{{ $b->tbDirEmpresaObraID  }}" class="list-group-item active">
                            <h4 class="list-group-item-heading">{{ $b->business[0]->EmpresaAlias }}</h4>
                            <p class="small">
                                {{ $b->DirEmpresaObraAlcance }}
                            </p>
                        </div>
                        @continue
                    @endif
                    <a id="item{{ $b->tbDirEmpresaObraID  }}" href="{{ $navigation['base'].$navigation['section'] }}/{{ $b->tbDirEmpresaObraID  }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="list-group-item">
                        <h4 class="list-group-item-heading">{{ $b->business[0]->EmpresaAlias }}</h4>
                        <p class="text-muted small">
                            {{ $b->DirEmpresaObraAlcance }}
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
                        <a href="#general" aria-controls="general" role="tab" data-toggle="tab" data-type="own">General</a>
                    </li>
                    <li role="presentation">
                        <a href="#phones" aria-controls="phones" role="tab" data-toggle="tab" data-type="relation" data-element="">Teléfonos</a>
                    </li>
                    <li role="presentation">
                        <a href="#emails" aria-controls="emails" role="tab" data-toggle="tab" data-type="relation" data-element="">Correos E.</a>
                    </li>
                    <li role="presentation">
                        <a href="#addresses" aria-controls="addresses" role="tab" data-toggle="tab" data-type="relation" data-element="">Domicilios</a>
                    </li>
                    <li role="presentation">
                        <a href="#proceedings" aria-controls="proceedings" role="tab" data-toggle="tab" data-type="relation" data-element="">Actas</a>
                    </li>
                    <li role="presentation">
                        <a href="#partners" aria-controls="partners" role="tab" data-toggle="tab" data-type="relation" data-element="">Socios</a>
                    </li>
                    <li role="presentation">
                        <a href="#persons" aria-controls="persons" role="tab" data-toggle="tab" data-type="relation" data-element="">Personas</a>
                    </li>
                    <li role="presentation">
                        <a href="#comments" aria-controls="comments" role="tab" data-toggle="tab" data-type="relation" data-element="#modalComment">Comentarios</a>
                    </li>
                </ul>
            </div>
            <div class="tab-content col-sm-12 padding-top--5">
                <div role="tabpanel" class="tab-pane active row" id="general">
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Alias</label>
                        <p class="help-block">{{ ifempty($business['one']->business[0]->EmpresaAlias) }}</p>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Grupo</label>
                        <p class="help-block">
                            @if ($business['one']->group)
                                {{ ifempty($business['one']->group->DirGrupoNombre) }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Alcance</label>
                        <p class="help-block">{{ ifempty($business['one']->DirEmpresaObraAlcance) }}</p>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Razón social</label>
                        <p class="help-block">
                            @if ($business['one']->business[0])
                                {{ ifempty($business['one']->business[0]->EmpresaRazonSocial) }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label-full">Nombre comercial</label>
                        <p class="help-block">
                            @if ($business['one']->business[0])
                                {{ ifempty($business['one']->business[0]->EmpresaNombreComercial) }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="form-label-full">Especialidad</label>
                        <p class="help-block">
                            @if ($business['one']->business[0])
                                {{ ifempty($business['one']->business[0]->EmpresaEspecialidad) }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-12 text-right">
                        <label>
                            Cerrado:
                            @if (isset($filter['queries']['status']) && $filter['queries']['status'] == 'deleted')
                                @if (!empty($business['one']->RegistroCerrado))
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $business['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" checked disabled>
                                @else
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $business['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" disabled>
                                @endif
                            @else
                                @if (!empty($business['one']->RegistroCerrado))
                                    @if (Auth::user()['role'] < $business['one']->RegistroRol || Auth::user()['role'] == 1)
                                        <input type="checkbox" name="cto34_close" value="1" data-id="{{ $business['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" checked>
                                    @else
                                        <input type="checkbox" name="cto34_close" value="1" data-id="{{ $business['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}" checked disabled>
                                    @endif
                                @else
                                    <input type="checkbox" name="cto34_close" value="1" data-id="{{ $business['one']->getKey() }}" data-work="{{ $works['one']->tbObraID }}">
                                @endif
                            @endif
                        </label>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane row" id="phones">
                    <div class="col-sm-12">
                        <div class="items-relation">
                            <div class="list-group">
                                @forelse ($business['one']->phones as $row)
                                    <a href="#" data-id="{{ $row->tbDirTelefonoID  }}" data-toggle="modal" data-target="#showEstimateModal" class="list-group-item" style="border-bottom: 1px solid #ddd">
                                        <h5 class="list-group-item-heading">{{ $row->DirTelefonoCompleto }}</h5>
                                        <p class="list-group-item-text small text-muted">
                                            {{ $row->DirTelefonoEtiqueta }}
                                        </p>
                                    </a>
                                @empty
                                    <div class="list-group-item">No hay teléfonos registrados</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane row" id="emails">
                    <div class="col-sm-12">
                        <div class="items-relation">
                            <div class="list-group">
                                @if (!empty($business['one']->business[0]))
                                    @forelse ($business['one']->business[0]->emails as $row)
                                        @if (!empty($row->email))
                                            <a href="#" data-id="{{ $row->tbDirEmailEmpresaID  }}" data-toggle="modal" data-target="#showEstimateModal" class="list-group-item" style="border-bottom: 1px solid #ddd">
                                                <h5 class="list-group-item-heading">{{ $row->DirEmailEmpresaEtiqueta }}</h5>
                                                <p class="list-group-item-text small text-muted">
                                                    {{ $row->email->DirEmailCorreoE }}
                                                </p>
                                            </a>
                                        @endif
                                    @empty
                                        <div class="list-group-item">No hay correos registrados</div>
                                    @endforelse
                                @else
                                    <div class="list-group-item">No hay correos registradas</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane row" id="addresses">
                    <div class="col-sm-12">
                        <div class="items-relation">
                            <div class="list-group">
                                @if (!empty($business['one']->business[0]))
                                    @forelse ($business['one']->business[0]->addresses as $address)
                                        @if (!empty($address->address))
                                            <a href="#" data-id="{{ $address->tbDirEmpresaDomicilioID  }}" data-toggle="modal" data-target="#showEstimateModal" class="list-group-item" style="border-bottom: 1px solid #ddd">
                                                <h5 class="list-group-item-heading">{{ $address->EmpresaDomicilioEtiqueta }}</h5>
                                                <p class="list-group-item-text small text-muted">
                                                    {{ $address->address->DirDomicilioCompleto }}
                                                </p>
                                            </a>
                                        @endif
                                    @empty
                                        <div class="list-group-item">No hay direcciones registradas</div>
                                    @endforelse
                                @else
                                    <div class="list-group-item">No hay direcciones registradas</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane row" id="proceedings">
                    <div class="col-sm-12">
                        <div class="items-relation">
                            <div class="list-group">
                                @forelse ($business['one']->proceedings as $row)
                                    <a href="#" data-id="{{ $row->tbDirEmpresaActaID  }}" data-toggle="modal" data-target="#showEstimateModal" class="list-group-item" style="border-bottom: 1px solid #ddd">
                                        <h5 class="list-group-item-heading">{{ $row->EmpresaActaEtiqueta }} {{ $row->EmpresaActaNumero }}</h5>
                                        <p class="list-group-item-text small text-muted">
                                            {{ Carbon\Carbon::parse($row->EmpresaActaFecha)->formatLocalized('%A %d %B %Y') }}
                                        </p>
                                    </a>
                                @empty
                                    <div class="list-group-item">No hay teléfonos registrados</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane row" id="partners">
                    <div class="col-sm-12">
                        <div class="items-relation">
                            <div class="list-group">
                                @forelse ($business['one']->partners as $row)
                                    <a href="#" data-id="{{ $row->tbDirEmpresaSocioID  }}" data-toggle="modal" data-target="#showEstimateModal" class="list-group-item" style="border-bottom: 1px solid #ddd">
                                        <h5 class="list-group-item-heading">{{ $row->person->PersonaNombreCompleto }} {{ $row->EmpresaSocioCargo }}</h5>
                                        <p class="list-group-item-text small text-muted">
                                           Porcentaje: {{ number_format($row->EmpresaSocio_Porcentaje, 2) }}%
                                        </p>
                                    </a>
                                @empty
                                    <div class="list-group-item">No hay teléfonos registrados</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane row" id="persons">
                    <div class="col-sm-12">
                        <div class="items-relation">
                                @if (!empty($business['one']->personsWork))
                                    @forelse ($business['one']->personsWork as $row)
                                        <a href="#" data-id="{{ $row->tbDirPersonaEmpresaObraID  }}" data-toggle="modal" data-target="#showEstimateModal" class="list-group-item" style="border-bottom: 1px solid #ddd">
                                            <h5 class="list-group-item-heading">{{ $row->personBusiness->person->PersonaNombreCompleto }}</h5>
                                            <p class="list-group-item-text small text-muted">
                                                {{ $row->DirPersonaObraEmpresaCargoEnLaObra }}
                                            </p>
                                        </a>
                                    @empty
                                        <div class="list-group-item">No hay personas registradas 1</div>
                                    @endforelse
                                @else
                                    <div class="list-group-item">No hay personas registradas 2</div>
                                @endif
                        </div>
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
                            @forelse($business['one']->comments as $comment)
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
    <input type="hidden" name="_recordId" value="{{ $business['one']->getKey() }}">
@endif