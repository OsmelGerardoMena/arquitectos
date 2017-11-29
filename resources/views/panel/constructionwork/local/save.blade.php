@extends('layouts.base')

@push('styles_head')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-select.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-datetimepicker.min.css') }}">
@endpush

@section('content')
    @include('layouts.alerts', ['errors' => $errors])
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default panel-section bg-white">
                    <div class="col-sm-8">
                        @include('panel.constructionwork.shared.submenu')
                    </div>
                    <div class="col-sm-4">
                        {{--
                            Nav Actions
                            Se incluyen los botones de acción para los registros

                        --}}
                        @include('shared.nav-actions-save')
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
                                <div class="list-group-item active">
                                    <h4 class="list-group-item-heading">
                                        Nuevo <span class="fa fa-caret-right fa-fw"></span>
                                    </h4>
                                    <p class="small">
                                        {{ Carbon\Carbon::now()->formatLocalized('%d %B %Y') }}
                                    </p>
                                </div>
                                @foreach ($locals['all'] as $index => $local)
                                    <a href="{{ $navigation['base'] }}/info/{{ $local->tbUbicaLocalID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="list-group-item">
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
                                    <form id="saveForm" action="{{ $navigation['base'].'/action/save' }}" method="post" accept-charset="utf-8">
                                        <div class="form-group col-sm-12">
                                            <label for="cto34_building" class="form-label-full">Edificio alias</label>
                                            <select name="cto34_building" id="cto34_building" class="form-control input-sm">
                                                <option value="">Seleccionar opción</option>
                                                @foreach($buildings['all'] as $building)
                                                    <option value="{{ $building->tbUbicaEdificioID }}">{{ $building->UbicaEdificioAlias }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="cto34_level" class="form-label-full">Nivel alias</label>
                                            <select name="cto34_level" id="cto34_level" class="form-control input-sm" disabled>
                                                <option value="">Seleccionar opción</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="cto34_alias" class="form-label-full">Local alias</label>
                                            <input id="cto34_alias"
                                                   name="cto34_alias"
                                                   type="text"
                                                   placeholder="Clave + Nombre"
                                                   disabled
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="cto34_code" class="form-label-full">Clave</label>
                                            <input id="cto34_code"
                                                   name="cto34_code"
                                                   type="text"
                                                   placeholder="NivelCódigo + 00 + LocalNumero"
                                                   maxlength="30"
                                                   class="form-control form-control-plain input-sm" readonly>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="cto34_name" class="form-label-full">Nombre</label>
                                            <input id="cto34_name"
                                                   name="cto34_name"
                                                   type="text"
                                                   value="{{ old('cto34_name') }}"
                                                   maxlength="100"
                                                   autocomplete="off"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_type" class="form-label-full">Tipo</label>
                                            <select name="cto34_type" id="cto34_type" class="form-control input-sm">
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
                                                   value="{{ old('cto34_area') }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-sm-6 col-sm-offset-6">
                                            <label for="cto34_sumArea" class="form-label-full">Suma a las áreas del proyecto
                                                <input id="cto34_sumArea"
                                                       name="cto34_sumArea"
                                                       type="checkbox"
                                                       value="1">
                                            </label>
                                        </div>
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="_errors" value="{{ json_encode($errors->messages()) }}">
    {{-- Carga la vista para los formularios de registro de personas y empresa --}}
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

                levels.prop('disabled', true);
                levels.html('<option value="">Cargando...</option>');

                var request = $.get('{{ url('/ajax/search/levels')  }}', { 'id': select.val() });
                request.done(function(response) {

                    var data = response;
                    var options = '';
                    levels.html('<option value="">Seleccionar opción</option>');

                    $.each(data.levels, function(index, level) {

                        options += '<option value="' + level.tbUbicaNivelID + '">' + level.UbicaNivelAlias + '</option>';

                    });

                    levels.append(options);
                    levels.prop('disabled', false);

                }).fail(function() {
                    alert( "No se puede obetener los niveles." );
                    levels.prop('disabled', true);
                })

            });

        })();
    </script>
@endpush