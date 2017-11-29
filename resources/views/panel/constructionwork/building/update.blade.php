@extends('layouts.base')
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
                        @include('shared.nav-actions-update', [ 'model' => [ 'id' => $buildings['one']->tbUbicaEdificioID ]])
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
                                @foreach ($buildings['all'] as $index => $building)

                                    @if ($building->tbUbicaEdificioID == $buildings['one']->tbUbicaEdificioID)
                                        <div id="item{{ $building->tbUbicaEdificioID }}" class="list-group-item active">
                                            <h4 class="list-group-item-heading">{{ $building->UbicaEdificioNombre }}</h4>
                                            <p class="small">
                                                {{ $building->UbicaEdificioAlias }}
                                            </p>
                                        </div>
                                        @continue
                                    @endif
                                        @if (isset($search['query']))
                                            <a id="item{{ $building->tbUbicaEdificioID }}" href="{{ $navigation['base'] }}/search/{{ $building->tbUbicaEdificioID  }}{{ '?'.$filter['query'] }}" class="list-group-item">
                                                <h4 class="list-group-item-heading">{{ $building->UbicaEdificioNombre }}</h4>
                                                <p class="text-muted small">
                                                    {{ $building->UbicaEdificioAlias }}
                                                </p>
                                            </a>
                                        @else
                                            <a id="item{{ $building->tbUbicaEdificioID }}" href="{{ $navigation['base'] }}/update/{{ $building->tbUbicaEdificioID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="list-group-item">
                                                <h4 class="list-group-item-heading">{{ $building->UbicaEdificioNombre }}</h4>
                                                <p class="text-muted small">
                                                    {{ $building->UbicaEdificioAlias }}
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
                                    <li role="presentation" class="disabled">
                                        <a href="#">
                                            Niveles
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
                                    <form id="saveForm" action="{{ $navigation['base'].'/action/update' }}" method="post" accept-charset="utf-8">                                        
                                        <div class="form-group col-sm-12">
                                            <label for="cto34_name" class="form-label-full">Nombre</label>
                                            <input id="cto34_name"
                                                   name="cto34_name"
                                                   value="{{ ifempty($buildings['one']->UbicaEdificioNombre) }}"
                                                   type="text"
                                                   maxlength="100"
                                                   autocomplete="off"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_code" class="form-label-full">Clave</label>
                                            <input id="cto34_code"
                                                   name="cto34_code"
                                                   type="text"
                                                   value="{{ ifempty($buildings['one']->UbicaEdificioClave) }}"
                                                   maxlength="10"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_alias" class="form-label-full">Edificio Alias</label>
                                            <input id="cto34_alias"
                                                   name="cto34_alias"
                                                   type="text"
                                                   value="{{ old('cto34_alias') }}"
                                                   disabled
                                                   placeholder="{{ ifempty($buildings['one']->UbicaEdificioAlias) }}"
                                                   class="form-control form-control-plain input-sm" readonly>
                                        </div>                                        
                                        <div class="form-group col-sm-12">
                                            <label for="cto34_description" class="form-label-full">Descripción</label>
                                            <textarea id="cto34_description"
                                                      name="cto34_description"
                                                      rows="2"
                                                      maxlength="4000"
                                                      class="form-control form-control-plain">{{ ifempty($buildings['one']->UbicaEdificioDescripcion)  }}</textarea>
                                            <small class="form-count small text-muted"><span class="form-counter">0</span>/4000</small>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_shadyArea" class="form-label-full">Área de desplante (m2)</label>
                                            <input id="cto34_shadyArea"
                                                   name="cto34_shadyArea"
                                                   type="text"
                                                   value="{{ number_format(ifempty($buildings['one']->UbicaEdificioAreaDesplante, '0'), 2)  }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_totalArea" class="form-label-full">Área nominal (m2)</label>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-addon" style="background-color: #fff">Interior</span>
                                                        <input name="cto34_totalArea" type="text" class="form-control" value="{{ number_format(ifempty($buildings['one']->UbicaEdificioAreaTotal, '0'), 2)  }}">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-addon" style="background-color: #fff">Exterior</span>
                                                        <input name="cto34_totalAreaExt" type="text" class="form-control" value="{{ number_format(ifempty($buildings['one']->UbicaEdificioAreaTotalExterior, '0'), 2)  }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label class="form-label-full">Áreas registradas en sistema (m2)</label>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-addon" style="background-color: #fff">Interior</span>
                                                        <input name="" type="text" class="form-control" value="{{ number_format(ifempty($buildings['area']['int'], '0'), 2, ',', '.')  }}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-addon" style="background-color: #fff">Exterior</span>
                                                        <input name="" type="text" class="form-control" value="{{ number_format(ifempty($buildings['area']['ext'], '0'), 2, ',', '.')  }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_totalLevels" class="form-label-full">Niveles totales usuario</label>
                                            <input id="cto34_totalLevels"
                                                   name="cto34_totalLevels"
                                                   type="text"
                                                   value="{{ number_format(ifempty($buildings['one']->UbicaEdificioNiveles, '0'), 2) }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label class="form-label-full">Niveles totales sistema</label>
                                            <input value="{{ number_format(ifempty($buildings['one']->UbicaEdificioNivelesAutomatico, '0'), 2, ',', '.') }}"
                                                   class="form-control form-control-plain input-sm" disabled>
                                        </div>
                                        <input type="hidden" name="_method" value="put">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="cto34_id" value="{{ $buildings['one']->tbUbicaEdificioID }}">
                                        <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                                        <input type="hidden" name="_query" value="{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}">
                                        <input type="hidden" name="_hasSearch" value="{{ isset($filter['queries']['q']) ? 1 : 0 }}">
                                    </form>
                                </div>
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
    <input type="hidden" name="_recordId" value="{{ $buildings['one']->tbUbicaEdificioID }}">
    {{-- Guarda los errores para marcar los campos con errores --}}
    <input type="hidden" name="_errors" value="{{ json_encode($errors->messages()) }}">
    {{--
        Modals
        Se incluyen los modales que se requieren para el registro
    --}}
    @include('panel.constructionwork.building.shared.filter-modal')
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
            app.filterModal();

        })();
    </script>
@endpush