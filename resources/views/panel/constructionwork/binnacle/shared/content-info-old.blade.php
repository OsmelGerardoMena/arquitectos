@if($business['all']->count() == 0)
    <div class="panel-body text-center">
        <h4>No hay empresas registradas</h4>
        <a href="{{ $navigation['base'].'/save' }}" class="btn btn-link">Nueva empresa</a>
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
                    <a id="item{{ $b->tbDirEmpresaObraID  }}" href="{{ $navigation['base'].$navigation['section'] }}/{{ $b->tbDirEmpresaObraID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="list-group-item">
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
                        <a href="#general" aria-controls="general" role="tab" data-toggle="tab">General</a>
                    </li>
                    <li role="presentation">
                        <a href="#concepts" aria-controls="concepts" role="tab" data-toggle="tab">Detalles</a>
                    </li>
                    <li role="presentation">
                        <a href="#public" aria-controls="public" role="tab" data-toggle="tab">Registros</a>
                    </li>
                    <li role="presentation">
                        <a href="#phones" aria-controls="phones" role="tab" data-toggle="tab">Teléfonos</a>
                    </li>
                    <li role="presentation">
                        <a href="#addresses" aria-controls="addresses" role="tab" data-toggle="tab">Direcciones</a>
                    </li>
                    <li role="presentation">
                        <a href="#emails" aria-controls="emails" role="tab" data-toggle="tab">Correos E.</a>
                    </li>
                    <li role="presentation">
                        <a href="#proceedings" aria-controls="proceedings" role="tab" data-toggle="tab">Actas</a>
                    </li>
                    <li role="presentation">
                        <a href="#partners" aria-controls="partners" role="tab" data-toggle="tab">Socios</a>
                    </li>
                    <li role="presentation">
                        <a href="#persons" aria-controls="persons" role="tab" data-toggle="tab">Personas</a>
                    </li>
                    <li role="presentation">
                        <a href="#comments" aria-controls="comments" role="tab" data-toggle="tab">Comentarios</a>
                    </li>
                </ul>
                <div class="tab-content col-sm-12 padding-top--5">
                    <div role="tabpanel" class="tab-pane active row" id="general">
                        <div class="form-group col-sm-6">
                            <label>Alias</label>
                            <p class="help-block">{{ ifempty($business['one']->business[0]->EmpresaAlias) }}</p>
                        </div>
                        <div class="form-group col-sm-6">
                            <label>Grupo</label>
                            <p class="help-block">{{ ifempty($business['one']->group->DirGrupoNombre) }}</p>
                        </div>
                        <div class="form-group col-sm-12">
                            <label>Alcance</label>
                            <p class="help-block">{{ ifempty($business['one']->DirEmpresaObraAlcance) }}</p>
                        </div>
                        <div class="form-group col-sm-12">
                            <label>Razón Social</label>
                            <p class="help-block">{{ ifempty($business['one']->business[0]->EmpresaRazonSocial) }}</p>
                        </div>
                        <div class="form-group col-sm-12">
                            <label>Nombre Comercial</label>
                            <p class="help-block">{{ ifempty($business['one']->business[0]->EmpresaNombreComercial) }}</p>
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
                    <div role="tabpanel" class="tab-pane" id="concepts">
                        <div class="form-group col-sm-12">
                            <label>Dependencia</label>
                            <p class="help-block">{{ ifempty($business['one']->business[0]->EmpresaDependencia) }}</p>
                        </div>
                        <div class="form-group col-sm-12">
                            <label>Especialidad</label>
                            <p class="help-block">{{ ifempty($business['one']->business[0]->EmpresaEspecialidad) }}</p>
                        </div>
                        <div class="form-group col-sm-12">
                            <label>Tipo de persona</label>
                            <p class="help-block">{{ ifempty($business['one']->business[0]->EmpresaTipoPersona) }}</p>
                        </div>
                        <div class="form-group col-sm-12">
                            <label>Slogan</label>
                            <p class="help-block">{{ ifempty($business['one']->business[0]->EmpresaSlogan) }}</p>
                        </div>
                        <div class="form-group col-sm-12">
                            <label>Página web</label>
                            <p class="help-block">{{ ifempty($business['one']->business[0]->EmpresaPaginaWeb) }}</p>
                        </div>
                        <div class="form-group col-sm-12">
                            <label>Sector</label>
                            <p class="help-block">{{ ifempty($business['one']->business[0]->EmpresaSector) }}</p>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="public">
                        <div class="form-group col-sm-12">
                            <label>RFC</label>
                            <p class="help-block">{{ ifempty($business['one']->business[0]->EmpresaRFC) }}</p>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group col-sm-12">
                            <label>Número de IMSS</label>
                            <p class="help-block">{{ ifempty($business['one']->business[0]->EmpresaIMSSNumero) }}</p>
                        </div>
                        <div class="form-group col-sm-12">
                            <label>Número de INFONAVIT</label>
                            <p class="help-block">{{ ifempty($business['one']->business[0]->EmpresaINFONAVITNumero) }}</p>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane row" id="comments">
                        <div class="form-group col-sm-12">
                            <!--                                     <label for="cto34_comments" class="form-label-full">Comentarios</label>
                                                                <p></p> -->
                            <!--                                             <div class="col-sm-12 margin-bottom--10">
                                                                            <a href="#" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modalComment">Agregar comentario</a>
                                                                        </div> -->
                            @forelse($business['one']->comments as $comment)
                                <div class="col-sm-12">
                                    <b>Comentario por {{ $comment->user->person->PersonaNombreCompleto  }}</b><br>
                                    <p>
                                        {{ $comment->Comentario }}<br>
                                        <small class="text-muted">
                                            {{  Carbon\Carbon::parse($comment->ComentarioFecha)->formatLocalized('%A %d %B %Y') }}
                                        </small>
                                    </p>
                                    <hr>
                                </div>
                            @empty
                                <div class="col-sm-12 text-center">
                                    <h3>No hay comentarios registrados</h3>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane row" id="phones"></div>
                    <div role="tabpanel" class="tab-pane row" id="addresses"></div>
                    <div role="tabpanel" class="tab-pane row" id="emails"></div>
                    <div role="tabpanel" class="tab-pane row" id="proceedings"></div>
                    <div role="tabpanel" class="tab-pane row" id="partners">

                    </div>
                    <div role="tabpanel" class="tab-pane row" id="persons" style="position: relative">
                        <div class="text-right" style="position: absolute; top: -40px; right: 10px; padding: 5px;">
                            <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#saveEstimationModal">
                                <span class="fa fa-plus fa-fw text-success"></span>
                            </button>
                        </div>
                        <div class="col-sm-12">
                            <div class="list-group" style="height: 300px; overflow-x: auto">
                                @if (!empty($business['one']->business[0]))
                                    @forelse ($business['one']->business[0]->persons as $person)
                                        <a href="#" data-id="{{ $person->tbDirPErsonaID  }}" data-toggle="modal" data-target="#showEstimateModal" class="list-group-item" style="border-bottom: 1px solid #ddd">
                                            <h5 class="list-group-item-heading">{{ $person->PersonaAlias }}</h5>
                                            <p class="list-group-item-text small text-muted">
                                                {{ $person->PersonaNombreCompleto }}
                                            </p>
                                        </a>
                                    @empty
                                        <div class="list-group-item">No hay personas registradas</div>
                                    @endforelse
                                @else
                                    <div class="list-group-item">No hay personas registradas</div>
                                @endif
                            </div>
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