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
                                @include('panel.constructionwork.building.shared.content-search')
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
                                @foreach ($levels['all'] as $index => $level)
                                    <a id="item{{ $level->tbUbicaNivelID }}" href="{{ $navigation['base'] }}/info/{{ $level->tbUbicaNivelID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="list-group-item">
                                        <h4 class="list-group-item-heading">{{ $level->UbicaNivelAlias }}</h4>
                                        <p class="text-muted small">
                                            {{ $level->building->UbicaEdificioAlias }}
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
                                    <li role="presentation" class="disabled">
                                        <a href="#">
                                            Locales
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content col-sm-12 margin-bottom--20">
                                <div role="tabpanel" class="tab-pane active row padding-top--5" id="general">
                                    <form id="saveForm" action="{{ $navigation['base'].'/action/save' }}" method="post" accept-charset="utf-8">
                                        <div class="form-group col-sm-12">
                                            <label for="cto34_alias" class="form-label-full">Nivel alias</label>
                                            <input id="cto34_alias"
                                                   name="cto34_alias"
                                                   type="text"
                                                   placeholder="Clave + Nombre"
                                                   disabled
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="cto34_building" class="form-label-full">Edificio alias</label>
                                            <select name="cto34_building" id="cto34_building" class="form-control input-sm">
                                                <option value="">Seleccionar opción</option>
                                                @foreach($buildings['all'] as $building)
                                                    <option value="{{ $building->tbUbicaEdificioID }}">{{ $building->UbicaEdificioAlias }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label class="form-label-full">Consecutivo</label>
                                            <input name="cto34_consecutive"
                                                   type="number"
                                                   value="{{ old('cto34_consecutive') }}"
                                                   maxlength="10"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="cto34_code" class="form-label-full">Clave</label>
                                            <input id="cto34_code"
                                                   name="cto34_code"
                                                   type="text"
                                                   value="{{ old('cto34_code') }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_name" class="form-label-full">Nombre</label>
                                            <input id="cto34_name"
                                                   name="cto34_name"
                                                   type="text"
                                                   value="{{ old('cto34_name') }}"
                                                   maxlength="100"
                                                   autocomplete="off"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="cto34_description" class="form-label-full">Descripción</label>
                                            <textarea id="cto34_description"
                                                      name="cto34_description"
                                                      rows="3"
                                                      maxlength="4000"
                                                      class="form-control form-control-plain">{{ old('cto34_description') }}</textarea>
                                            <small class="form-count text-muted"><span class="form-counter">0</span>/4000</small>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label>Suma a los niveles del proyecto
                                                <input id="cto34_sumLevel"
                                                       name="cto34_sumLevel"
                                                       type="checkbox"
                                                       value="1">
                                            </label>
                                            <label>Suma a las áreas del proyecto
                                                <input id="cto34_sumArea"
                                                       name="cto34_sumArea"
                                                       type="checkbox"
                                                       value="1">
                                            </label>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_nptLevel" class="form-label-full">Nivel NPT</label>
                                            <input id="cto34_nptLevel"
                                                   name="cto34_nptLevel"
                                                   type="text"
                                                   value="{{ old('cto34_nptLevel') }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label class="form-label-full">Locales en éste nivel</label>
                                            <input name="cto34_localLevels"
                                                   type="text"
                                                   class="form-control form-control-plain input-sm" disabled>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_surfaceLevel" class="form-label-full">Superficie de éste nivel</label>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-addon" style="background-color: #fff">Interior</span>
                                                        <input name="cto34_surfaceLevel" type="text" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-addon" style="background-color: #fff">Exterior</span>
                                                        <input name="cto34_surfaceLevelExt" type="text" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_surfaceLevelSystem" class="form-label-full">Superficie de locales registrados en éste nivel</label>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-addon" style="background-color: #fff">Interior</span>
                                                        <input type="text" class="form-control" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-addon" style="background-color: #fff">Exterior</span>
                                                        <input type="text" class="form-control" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                                    </form>
                                </div>
                                <div role="tabpanel" class="tab-pane row padding-top--5" id="locals"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Guarda los errores para marcar los campos con errores --}}
    <input type="hidden" name="_errors" value="{{ json_encode($errors->messages()) }}">
    {{--
        Modals
        Se incluyen los modales que se requieren para el registro
    --}}
    @include('panel.constructionwork.level.shared.filter-modal')
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
            app.limitInput("#cto34_description", 4000);
        })();
    </script>
@endpush