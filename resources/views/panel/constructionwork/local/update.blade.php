@extends('layouts.base')
{{--
    Styles head
    Se agregan estilos css al archivo base de las vistas
--}}
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
    @include('layouts.alerts', ['errors' => $errors])
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
                        @include('shared.nav-actions-update', [ 'model' => [ 'id' => $locals['one']->getKey() ]])
                    </div>
                    <div class="clearfix"></div>
                    <div class="panel-body padding-top--clear">
                        <div class="list-group col-sm-5 col-md-4 col-lg-3 margin-clear panel-item" style="position: relative;">
                            <div class="list-group-item padding-clear padding-bottom--5">
                                {{--
                                    Content Search
                                    Se incluyen la forma para la busqueda y filtrado de datos
                                --}}
                                @include('panel.constructionwork.local.shared.content-search')
                            </div>
                            <div id="itemsList">
                                @foreach ($locals['all'] as $index => $local)
                                    @if ($local->tbUbicaLocalID == $locals['one']->tbUbicaLocalID)
                                        <div id="item{{ $local->tbUbicaLocalID }}" class="list-group-item active">
                                            <h4 class="list-group-item-heading">{{ $local->UbicaLocalAlias }}</h4>
                                            <p class="small">
                                                {{ $local->level->building->UbicaEdificioAlias }} / {{ $local->level->UbicaNivelAlias }}
                                            </p>
                                        </div>
                                        @continue
                                    @endif
                                    <a id="item{{  $local->tbUbicaLocalID }}" href="{{ $navigation['base'].$navigation['section'] }}/{{ $local->tbUbicaLocalID  }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="list-group-item">
                                        <h4 class="list-group-item-heading">{{ $local->UbicaLocalAlias }}</h4>
                                        <p class="text-muted small">
                                            {{ $local->level->building->UbicaEdificioAlias }} / {{ $local->level->UbicaNivelAlias }}
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
                                </ul>
                            </div>
                            <div class="tab-content col-sm-12 margin-bottom--20">
                                <div role="tabpanel" class="tab-pane active row padding-top--5" id="general">
                                    <form id="saveForm" action="{{ $navigation['base'].'/action/update' }}" method="post" accept-charset="utf-8">
                                        <div class="form-group col-sm-12">
                                            <label for="cto34_building" class="form-label-full">Edificio alias</label>
                                            <select name="cto34_building" id="cto34_building" class="form-control input-sm">
                                                <optgroup label="Opción seleccionada">
                                                    <option value="{{ ifempty($locals['one']->level->tbUbicaEdificioID_Nivel) }}" selected>{{ ifempty($locals['one']->level->building->UbicaEdificioAlias) }}</option>
                                                </optgroup>
                                                <option value="">Seleccionar opción</option>
                                                @foreach($buildings['all'] as $building)
                                                    <option value="{{ $building->tbUbicaEdificioID }}">{{ $building->UbicaEdificioAlias }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="cto34_level" class="form-label-full">Nivel alias</label>
                                            <select name="cto34_level" id="cto34_level" class="form-control input-sm" readonly>
                                                <optgroup label="Opción seleccionada">
                                                    <option value="{{ ifempty($locals['one']->level->tbUbicaNivelID) }}" selected>{{ ifempty($locals['one']->level->UbicaNivelAlias) }}</option>
                                                </optgroup>
                                                <option value="">Seleccionar opción</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="cto34_alias" class="form-label-full">Local alias</label>
                                            <input id="cto34_alias"
                                                   name="cto34_alias"
                                                   type="text"
                                                   placeholder="{{ ifempty($locals['one']->UbicaLocalAlias) }}"
                                                   disabled
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="cto34_code" class="form-label-full">Clave</label>
                                            <input id="cto34_code"
                                                   name="cto34_code"
                                                   type="text"
                                                   placeholder="{{ ifempty($locals['one']->UbicaLocalClave) }}"
                                                   maxlength="30"
                                                   class="form-control form-control-plain input-sm" readonly>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="cto34_name" class="form-label-full">Nombre</label>
                                            <input id="cto34_name"
                                                   name="cto34_name"
                                                   type="text"
                                                   value="{{ ifempty($locals['one']->UbicaLocalNombre) }}"
                                                   maxlength="100"
                                                   autocomplete="off"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_type" class="form-label-full">Tipo</label>
                                            <select name="cto34_type" id="cto34_type" class="form-control input-sm">
                                                <optgroup label="Opción seleccionada">
                                                    <option value="{{ ifempty($locals['one']->UbicaLocalTipo) }}" selected>{{ ifempty($locals['one']->UbicaLocalTipo) }}</option>
                                                </optgroup>
                                                <option value="">Seleccionar opción</option>
                                                <option value="Interior">Interior</option>
                                                <option value="Exterior">Exterior</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_area" class="form-label-full">Área</label>
                                            <input id="cto34_area"
                                                   name="cto34_area"
                                                   type="text"
                                                   value="{{ number_format(ifempty($locals['one']->UbicaLocalArea, 0), 2) }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-sm-6 col-sm-offset-6">
                                            @if ($locals['one']->UbicaLocalSumaAreaNivel == 1)
                                                <label for="cto34_sumArea" class="form-label-full">Suma a las áreas del proyecto
                                                    <input id="cto34_sumArea"
                                                           name="cto34_sumArea"
                                                           type="checkbox"
                                                           value="1"
                                                           checked>
                                                </label>
                                            @else
                                                <label for="cto34_sumArea" class="form-label-full">Suma a las áreas del proyecto
                                                    <input id="cto34_sumArea"
                                                           name="cto34_sumArea"
                                                           type="checkbox"
                                                           value="1">
                                                </label>
                                            @endif
                                        </div>
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="put">
                                        <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                                        <input type="hidden" name="cto34_id" value="{{ $locals['one']->tbUbicaLocalID }}">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Este campo sirve para indicar en el listado que registro se esta visualizando --}}
    <input type="hidden" name="_recordId" value="{{ $locals['one']->tbUbicaLocalID }}">
    {{-- Guarda los errores para marcar los campos con errores --}}
    <input type="hidden" name="_errors" value="{{ json_encode($errors->messages()) }}">
    {{--
        Modals
        Se incluyen los modales que se requieren para el registro
    --}}
    @include('panel.constructionwork.local.shared.filter-modal')
@endsection
@push('scripts_footer')
    <script>
        (function() {

            var app = new App();
            app.preventClose();
            app.formErrors('#saveForm');
            app.initItemsList();
            app.animateSubmit("saveForm", "addSubmitButton");
            app.tooltip();
            app.filterModal();

            $('#cto34_building').on('change', function() {

                var select = $(this);
                var levels = $('#cto34_level');

                if (select.val().length === 0) {
                    return;
                }

                var request = $.get('{{ url('/ajax/search/levels')  }}', { 'id': select.val() });

                request.done(function(response) {

                    var data = response;
                    var options = '';

                    $.each(data.levels, function(index, level) {

                        options += '<option value="' + level.tbUbicaNivelID + '">' + level.UbicaNivelAlias + '</option>';

                    });

                    levels.append(options);
                    levels.prop('readonly', false);

                }).fail(function() {
                    alert( "No se puede obetener los niveles." );
                    levels.prop('readonly', true);
                })

            });

        })();
    </script>
@endpush