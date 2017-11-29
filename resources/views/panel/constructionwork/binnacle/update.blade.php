@extends('layouts.base')
@push('styles_head')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-datetimepicker.min.css') }}">
@endpush
{{--
    Content
    Cuerpo de la vista
--}}
@section('content')
    {{--
        Alertas
        Se mostraran las alertas que el sistema envíe
        si se redirecciona a index
    --}}
    @include('shared.alerts', ['errors' => $errors])
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default panel-section bg-white">
                    <div class="col-sm-8">
                        {{--
                            Submenu
                            Se incluyen el menu para obras
                        --}}
                        @include('panel.constructionwork.shared.submenu')
                    </div>
                    <div class="col-sm-4">
                        {{--
                            Nav Actions
                            Se incluyen los botones de acción para los registros

                            @param $model['id'] id del registro
                        --}}
                        @include('shared.nav-actions-update', [ 'model' => [ 'id' => $binnacles['one']->getKey() ]])
                    </div>
                    <div class="clearfix"></div>
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

                                    @if ($binnacle->tbBitacoraID == $binnacles['one']->tbBitacoraID)
                                        <div id="item{{ $binnacle->tbBitacoraID }}" class="list-group-item active">
                                            <h4 class="list-group-item-heading">{{ $binnacle->BitacoraNumero }}</h4>
                                            <p class="small">
                                                {{ $binnacle->contract->ContratoAlias }}
                                            </p>
                                        </div>
                                        @continue
                                    @endif
                                        @if (isset($search['query']))
                                            <a id="item{{ $binnacle->tbBitacoraID }}" href="{{ $navigation['base'] }}/search/{{ $binnacle->tbBitacoraID  }}{{ '?'.$filter['query'] }}" class="list-group-item">
                                                <h4 class="list-group-item-heading">{{ $binnacle->BitacoraNumero }}</h4>
                                                <p class="text-muted small">
                                                    {{ $binnacle->contract->ContratoAlias }}
                                                </p>
                                            </a>
                                        @else
                                            <a id="item{{ $binnacle->tbBitacoraID }}" href="{{ $navigation['base'] }}/update/{{ $binnacle->tbBitacoraID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="list-group-item">
                                                <h4 class="list-group-item-heading">{{ $binnacle->BitacoraNumero }}</h4>
                                                <p class="text-muted small">
                                                    {{ $binnacle->contract->ContratoAlias }}
                                                </p>
                                            </a>
                                        @endif
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
                                        <a href="#details" aria-controls="general" role="tab" data-toggle="tab">
                                            Redacción
                                        </a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#registers" aria-controls="general" role="tab" data-toggle="tab">
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
                            <form id="saveForm" action="{{ $navigation['base'].'/action/update' }}" method="post" accept-charset="utf-8">
                                <div class="tab-content col-sm-12 margin-bottom--20">
                                    <div role="tabpanel" class="tab-pane active row padding-top--5" id="general">
                                        <div class="form-group col-sm-12">
                                            <label for="cto34_contract" class="form-label-full">Contrato</label>
                                            <select id="cto34_contract"
                                                    name="cto34_contract"
                                                    data-live-search="true"
                                                    data-width="100%"
                                                    data-style="btn-sm btn-default"
                                                    data-modal-title="Contrato"
                                                    class="selectpicker with-ajax">
                                                @if(!empty($binnacles['one']->contract))
                                                    <option value="{{  $binnacles['one']->tbContratoID_Bitacora }}" selected>
                                                        {{ $binnacles['one']->contract->ContratoAlias  }}
                                                    </option>
                                                @endif
                                            </select>
                                            <input type="hidden" id="cto34_contractName" name="cto34_contractName" value="{{ old('cto34_contractName')  }}">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_number" class="form-label-full">Nota Número</label>
                                            <input id="cto34_number"
                                                   name="cto34_number"
                                                   type="text"
                                                   value="{{ ifempty($binnacles['one']->BitacoraNumero, '0')  }}"
                                                   class="form-control form-control-plain input-sm">
                                            <input id="cto34_numberAux"
                                                   name="cto34_numberAux"
                                                   type="hidden"
                                                   value="{{ ifempty($binnacles['one']->BitacoraNumero, '0')  }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_noteDateText" class="form-label-full">Fecha de ésta nota</label>
                                            <div class="input-group input-group-sm date-field">
                                                <input id="cto34_noteDateText"
                                                       name="cto34_noteDateText"
                                                       type="text"
                                                       placeholder="{{ Carbon\Carbon::parse($binnacles['one']->BitacoraFecha)->formatLocalized('%A %d %B %Y')  }}"
                                                       readonly="readonly"
                                                       class="form-control form-control-plain input-sm date-formated">
                                                <input name="cto34_noteDate" type="hidden" value="{{ $binnacles['one']->BitacoraFecha }}">
                                                <span class="input-group-addon" style="background-color: #fff">
                                                <span class="fa fa-calendar fa-fw text-primary"></span>
                                            </span>
                                                <span class="input-group-btn">
                                                <button type="button" class="btn btn-default btn-today">Hoy</button>
                                            </span>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_type" class="form-label-full">Original o respuesta</label>
                                            <select name="cto34_type" id="cto34_type" class="form-control input-sm">
                                                @if (!empty($binnacles['one']->BitacoraNotaTipo))
                                                    <optgroup label="Opción seleccionada">
                                                        <option value="{{ $binnacles['one']->BitacoraNotaTipo }}" selected>
                                                            {{ $binnacles['one']->BitacoraNotaTipo }}
                                                        </option>
                                                    </optgroup>
                                                @endif
                                                <option value="">Seleccionar opción</option>
                                                @foreach(binnacle_note_type_options() as $option)
                                                    <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_binnacle" class="form-label-full">Nota antecedente</label>
                                            <select name="cto34_binnacle" id="cto34_binnacle" class="form-control input-sm" disabled>
                                                <option value="">Seleccionar opción</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane row padding-top--5" id="details">
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <div class="form-group col-sm-6">
                                                    <label for="cto34_group" class="form-label-full">Grupo</label>
                                                    <select name="cto34_group" id="cto34_group" class="form-control input-sm">
                                                        @if (!empty($binnacles['one']->BitacoraGrupo))
                                                            <optgroup label="Opción seleccionada">
                                                                <option value="{{ $binnacles['one']->BitacoraGrupo }}" selected>
                                                                    {{ $binnacles['one']->BitacoraGrupo }}
                                                                </option>
                                                            </optgroup>
                                                        @endif
                                                        <option value="">Seleccionar opción</option>
                                                        @foreach(binnacle_groups_options() as $option)
                                                            <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="cto34_destination" class="form-label-full">Destino</label>
                                                    <select name="cto34_destination" id="cto34_destination" class="form-control input-sm">
                                                        @if (!empty($binnacles['one']->BitacoraDestino))
                                                            <optgroup label="Opción seleccionada">
                                                                <option value="{{ $binnacles['one']->BitacoraDestino }}" selected>
                                                                    {{ $binnacles['one']->BitacoraDestino }}
                                                                </option>
                                                            </optgroup>
                                                        @endif
                                                        <option value="">Seleccionar opción</option>
                                                        @foreach(binnacle_destination_options() as $option)
                                                            <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label for="cto34_description" class="form-label-full">Descripción</label>
                                                    <textarea id="cto34_description"
                                                              name="cto34_description"
                                                              rows="2"
                                                              maxlength="4000"
                                                              class="form-control form-control-plain">{{ ifempty($binnacles['one']->BitacoraDescripcion, '') }}</textarea>
                                                    <!--<p class="form-count small text-muted"><span class="form-counter">0</span>/4000</p>-->
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label for="cto34_location" class="form-label-full">Ubicación</label>
                                                    <textarea id="cto34_location"
                                                              name="cto34_location"
                                                              rows="2"
                                                              maxlength="4000"
                                                              class="form-control form-control-plain">{{ ifempty($binnacles['one']->BitacoraUbicacion, '') }}</textarea>
                                                    <!--<p class="form-count small text-muted"><span class="form-counter">0</span>/4000</p>-->
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label for="cto34_reasons" class="form-label-full">Causas</label>
                                                    <textarea id="cto34_reasons"
                                                              name="cto34_reasons"
                                                              rows="2"
                                                              maxlength="4000"
                                                              class="form-control form-control-plain">{{ ifempty($binnacles['one']->BitacoraCausas, '') }}</textarea>
                                                    <!--<p class="form-count small text-muted"><span class="form-counter">0</span>/4000</p>-->
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label for="cto34_solution" class="form-label-full">Solución</label>
                                                    <textarea id="cto34_solution"
                                                              name="cto34_solution"
                                                              rows="2"
                                                              maxlength="4000"
                                                              class="form-control form-control-plain">{{ ifempty($binnacles['one']->BitacoraSolucion, '') }}</textarea>
                                                    <!--<p class="form-count small text-muted"><span class="form-counter">0</span>/4000</p>-->
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label for="cto34_term" class="form-label-full">Plazo descripción</label>
                                                    <input id="cto34_term"
                                                           name="cto34_term"
                                                           type="text"
                                                           value="{{ ifempty($binnacles['one']->BitacoraPlazoDescripcion, '') }}"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="cto34_compromiseDateText" class="form-label-full">Fecha compromiso</label>
                                                    <div class="input-group input-group-sm date-field">
                                                        <input id="cto34_compromiseDateText"
                                                               name="cto34_compromiseDateText"
                                                               type="text"
                                                               placeholder="{{ Carbon\Carbon::parse($binnacles['one']->BitacoraFechaCompromiso)->formatLocalized('%A %d %B %Y') }}"
                                                               readonly="readonly"
                                                               class="form-control form-control-plain input-sm date-formated">
                                                        <input name="cto34_compromiseDate" type="hidden" value="{{ $binnacles['one']->BitacoraFechaCompromiso }}">
                                                        <span class="input-group-addon" style="background-color: #fff">
                                                            <span class="fa fa-calendar fa-fw text-primary"></span>
                                                        </span>
                                                        <span class="input-group-btn">
                                                            <button type="button" class="btn btn-default btn-today">Hoy</button>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="cto34_accomplishmentDateText" class="form-label-full">Fecha cumplimiento</label>
                                                    <div class="input-group input-group-sm date-field">
                                                        <input id="cto34_accomplishmentDateText"
                                                               name="cto34_accomplishmentDateText"
                                                               type="text"
                                                               placeholder="{{ !empty($binnacles['one']->BitacoraFechaCumplimiento) ? Carbon\Carbon::parse($binnacles['one']->BitacoraFechaCumplimiento)->formatLocalized('%A %d %B %Y') : '' }}"
                                                               readonly="readonly"
                                                               class="form-control form-control-plain input-sm date-formated">
                                                        <input name="cto34_accomplishmentDate" type="hidden" value="{{ $binnacles['one']->BitacoraFechaCumplimiento }}">
                                                        <span class="input-group-addon" style="background-color: #fff">
                                                            <span class="fa fa-calendar fa-fw text-primary"></span>
                                                        </span>
                                                        <span class="input-group-btn">
                                                            <button type="button" class="btn btn-default btn-today">Hoy</button>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label for="cto34_prevention" class="form-label-full">Prevención</label>
                                                    <textarea id="cto34_prevention"
                                                              name="cto34_prevention"
                                                              rows="2"
                                                              maxlength="4000"
                                                              class="form-control form-control-plain">{{ ifempty($binnacles['one']->BitacoraPrevencion, '') }}</textarea>
                                                    <!--<p class="form-count small text-muted"><span class="form-counter">0</span>/4000</p>-->
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label for="cto34_cost" class="form-label-full">Costo</label>
                                                    <textarea id="cto34_cost"
                                                              name="cto34_cost"
                                                              rows="2"
                                                              maxlength="4000"
                                                              class="form-control form-control-plain">{{ ifempty($binnacles['one']->BitacoraCosto, '') }}</textarea>
                                                    <!--<p class="form-count small text-muted"><span class="form-counter">0</span>/4000</p>-->
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label for="cto34_sanctions" class="form-label-full">Sanciones</label>
                                                    <textarea id="cto34_sanctions"
                                                              name="cto34_sanctions"
                                                              rows="2"
                                                              maxlength="4000"
                                                              class="form-control form-control-plain">{{ ifempty($binnacles['one']->BitacoraSanciones, '') }}</textarea>
                                                    <!--<p class="form-count small text-muted"><span class="form-counter">0</span>/4000</p>-->
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label for="cto34_annexed" class="form-label-full">Anexos</label>
                                                    <textarea id="cto34_annexed"
                                                              name="cto34_annexed"
                                                              rows="2"
                                                              maxlength="4000"
                                                              class="form-control form-control-plain">{{ ifempty($binnacles['one']->BitacoraAnexos, '') }}</textarea>
                                                    <!--<p class="form-count small text-muted"><span class="form-counter">0</span>/4000</p>-->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <div class="form-group col-sm-12">
                                                    <label class="form-label-full">Borrador</label>
                                                    <input value="Nota No. {{ str_pad(ifempty($binnacles['one']->BitacoraNumero, '0'), 3, "0", STR_PAD_LEFT) }}" class="form-control input-sm margin-bottom--10" disabled>
                                                    <input value="{{ Carbon\Carbon::parse($binnacles['one']->BitacoraFecha)->formatLocalized('%A %d %B %Y')  }}" class="form-control input-sm margin-bottom--10" disabled>
                                                    <textarea maxlength="4000" rows="35" class="form-control" disabled>{{ ifempty($binnacles['one']->BitacoraNotaCompleta) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane row padding-top--5" id="registers">
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_personAutor" class="form-label-full">Autor</label>
                                            <select id="cto34_personAutor"
                                                    name="cto34_personAutor"
                                                    data-live-search="true"
                                                    data-width="100%"
                                                    data-style="btn-sm btn-default"
                                                    data-modal-title="Autor"
                                                    class="selectpicker with-ajax">
                                                @if (isset($binnacles['one']->author))
                                                    @if (isset($binnacles['one']->author->personBusiness))
                                                        @if (isset($binnacles['one']->author->personBusiness->person))
                                                            <option value="{{  $binnacles['one']->tbDirPersonaObraID_BitacoraAutor }}" selected>
                                                                {{ $binnacles['one']->author->personBusiness->person->PersonaNombreCompleto  }}
                                                            </option>
                                                        @endif
                                                    @endif
                                                @endif
                                            </select>
                                            <input type="hidden" id="cto34_personAutorName" name="cto34_personAutorName" value="{{ old('cto34_personAutorName')  }}">
                                        </div>
                                        <!--<div class="form-group col-sm-6">
                                            <label for="cto34_personDestination" class="form-label-full">Destinatario</label>
                                            <select id="cto34_personDestination"
                                                    name="cto34_personDestination"
                                                    data-live-search="true"
                                                    data-width="100%"
                                                    data-style="btn-sm btn-default"
                                                    data-modal-title="Destinatario"
                                                    class="selectpicker with-ajax">
                                                @if (isset($binnacles['one']->receiver))
                                                    @if (isset($binnacles['one']->receiver->personBusiness))
                                                        @if (isset($binnacles['one']->receiver->personBusiness->person))
                                                            <option value="{{ $binnacles['one']->tbDirPersonaObraID_BitacoraDestinatario }}">{{ $binnacles['one']->receiver->personBusiness->person->PersonaNombreCompleto  }}</option>
                                                        @endif
                                                    @endif
                                                @endif
                                            </select>
                                            <input type="hidden" id="cto34_personDestinationName" name="cto34_personDestinationName" value="{{ old('cto34_personDestinationName')  }}">
                                        </div>-->
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_auth" class="form-label-full">Nota autorizada por</label>
                                            <select name="cto34_auth" id="cto34_auth" class="form-control input-sm">
                                                @if (isset($binnacles['one']->auth))
                                                    @if (isset($binnacles['one']->auth->person))
                                                        <optgroup label="Opción seleccionada">
                                                            <option value="{{ $binnacles['one']->tbCTOUsuarioID_BitacoraAutoriza }}">{{ $binnacles['one']->auth->person->PersonaNombreCompleto  }}</option>
                                                        </optgroup>
                                                    @endif
                                                @endif
                                                <option value="">Seleccionar opción</option>
                                                @foreach($users['all'] as $option)
                                                    <option value="{{ $option->getKey() }}">{{ $option->CTOUsuarioNombre }} - {{ $option->person->PersonaNombreCompleto }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_personDestination" class="form-label-full">Capturado por</label>
                                            <input type="text" value="{{ $binnacles['one']->catcher->person->PersonaNombreDirecto }}" class="form-control input-sm" disabled>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label class="form-label-full">Registro creado timestamp</label>
                                            <input type="text" value="{{ Carbon\Carbon::parse($binnacles['one']->created_at)->formatLocalized('%A %d %B %Y') }}" class="form-control input-sm" disabled>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_personDestination" class="form-label-full">Modificado por</label>
                                            <input type="text" value="{{ Auth::user()['person']->PersonaNombreDirecto }}" class="form-control input-sm" disabled>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label class="form-label-full">Registro modificado timestamp</label>
                                            <input type="text" value="{{ Carbon\Carbon::now()->formatLocalized('%A %d %B %Y') }}" class="form-control input-sm" disabled>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="put">
                                <input type="hidden" name="_query" value="{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}">
                                <input type="hidden" name="_hasSearch" value="{{ isset($filter['queries']['q']) ? 1 : 0 }}">
                                <input type="hidden" name="cto34_id" value="{{ $binnacles['one']->getKey() }}">
                                <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--
        Este campo sirve para indicar en el listado que registro se esta visualizando
    --}}
    <input type="hidden" name="_recordId" value="{{ $binnacles['one']->tbUbicaEdificioID }}">
    {{-- Guarda los errores para marcar los campos con errores --}}
    <input type="hidden" name="_errors" value="{{ json_encode($errors->messages()) }}">
    {{--
        Modals
        Se incluyen los modales que se requieren para el registro
    --}}
    @include('panel.constructionwork.binnacle.shared.filter-modal')
@endsection
@push('scripts_footer')
    <script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/js/moment.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/constructionWork.js') }}"></script>
    <script>
        (function() {

            var app = new App();
            app.preventClose();
            app.formErrors('#saveForm');
            app.initItemsList();
            app.animateSubmit("saveForm", "addSubmitButton");
            app.tooltip();
            app.filterModal();
            app.dateTimePickerField();
            app.onPageTab();

            var work = new ConstructionWork();
            work.searchAdvanceContractWithAjax('#cto34_contract', {
                    url: '{{ url("ajax/action/search/contracts") }}',
                    token: '{{ csrf_token() }}',
                    workId: '{{ $works['one']->tbObraID }}',
                    optionClass: 'option-newContractor',
                    optionListClass: 'option-contractor',
                    canAdd: false

                }, function(data) {

                    if (data.action === 'newClicked') {
                        //beforeShowBusinessModal(data.element);
                    }

                    if (data.action === 'optionClicked') {
                        contractOptionSelected(data.element);
                    }
                }
            );

            /**
             *  Hacemos la busqueda para Firma por el cliente
             */
            work.searchPersonWithAjax('#cto34_personAutor',
                {
                    url: '{{ url("ajax/search/personsWork") }}',
                    token: '{{ csrf_token() }}',
                    workId: '{{ $works['one']->tbObraID }}',
                    optionClass: 'option-newPersonAutor',
                    optionListClass: 'option-personAutor',
                    canAdd: false

                }, function(data) {

                    if (data.action === 'newClicked') {
                        //beforeShowPersonModal(data.element);
                    }

                    if (data.action === 'optionClicked') {
                        personOptionSelected(data.element);
                    }
                }
            );

            /**
             *  Hacemos la busqueda para Firma por el cliente
             */
            work.searchPersonWithAjax('#cto34_personDestination',
                {
                    url: '{{ url("ajax/search/personsWork") }}',
                    token: '{{ csrf_token() }}',
                    workId: '{{ $works['one']->tbObraID }}',
                    optionClass: 'option-newPersonDestination',
                    optionListClass: 'option-personDestination',
                    canAdd: false

                }, function(data) {

                    if (data.action === 'newClicked') {
                        //beforeShowPersonModal(data.element);
                    }

                    if (data.action === 'optionClicked') {
                        personOptionSelected(data.element);
                    }
                }
            );

            $('.btn-today').on('click', function() {

                var dateHuman = moment().locale('es').format('dddd DD [de] MMMM [del] YYYY');
                var date = moment().format('YYYY-MM-DD');

                $(this).parent().parent().find('.date-formated').focus();
                $(this).parent().parent().find('.date-formated').val(dateHuman);
                $(this).parent().parent().find('input[type="hidden"]').val(date);
                $(this).parent().parent().find('.date-formated').blur();
            });

        })();

        function personOptionSelected(element) {
            var name = $(element).find('option:selected').text();
            $(element + 'Name').val(name);
        }

        function contractOptionSelected(element) {
            var name = $(element).find('option:selected').text();
            $(element + 'Name').val(name);
        }
    </script>
@endpush