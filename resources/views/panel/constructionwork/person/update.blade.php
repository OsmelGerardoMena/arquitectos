@extends('layouts.base')

@push('styles_head')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-datetimepicker.min.css') }}">
@endpush

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

                        --}}
                        @include('shared.nav-actions-update', [ 'model' => [ 'id' => $persons['one']->getKey() ]])
                    </div>
                    <div class="clearfix"></div>
                    <div class="panel-body padding-top--clear">
                        <div class="list-group col-sm-5 col-md-4 col-lg-3 margin-clear panel-item" style="position: relative;">
                            <div class="list-group-item padding-clear padding-bottom--5">
                                {{--
                                    Content Search
                                    Se incluyen la forma para la busqueda y filtrado de datos
                                --}}
                                @include('panel.constructionwork.person.shared.content-search')
                            </div>
                            <div id="itemsList">
                                @foreach ($persons['all'] as $index => $person)
                                    @if ($person->tbDirPersonaEmpresaObraID == $persons['one']->tbDirPersonaEmpresaObraID)
                                        <div id="item{{ $person->tbDirPersonaEmpresaObraID }}" class="list-group-item active">
                                            <h4 class="list-group-item-heading">
                                                @if (!empty($person->personBusiness) )
                                                    @if (!empty($person->personBusiness->person))
                                                        {{ $person->personBusiness->person->PersonaAlias }}
                                                    @else
                                                        -
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </h4>
                                            <p class="small">
                                                @if (!empty($person->business) )
                                                    {{ $person->business->EmpresaRazonSocial }}
                                                @else
                                                    -
                                                @endif
                                            </p>
                                        </div>
                                        @continue
                                    @endif
                                    <a id="item{{ $person->tbDirPersonaEmpresaObraID }}" href="{{ $navigation['base'].$navigation['section'] }}/{{ $person->tbDirPersonaEmpresaObraID  }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="list-group-item">
                                        <h4 class="list-group-item-heading">
                                            @if (!empty($person->personBusiness) )
                                                @if (!empty($person->personBusiness->person))
                                                    {{ $person->personBusiness->person->PersonaAlias }}
                                                @else
                                                    -
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </h4>
                                        <p class="small">
                                            @if (!empty($person->business) )
                                                {{ $person->business->EmpresaRazonSocial }}
                                            @else
                                                -
                                            @endif
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
                                        <a href="#general" aria-controls="general" role="tab" data-toggle="tab"  data-type="own">General</a>
                                    </li>
                                    <li role="presentation" class="disabled">
                                        <a href="#">Teléfonos</a>
                                    </li>
                                    <li role="presentation" class="disabled">
                                        <a href="#">Correos</a>
                                    </li>
                                    <li role="presentation" class="disabled">
                                        <a href="#">Direcciones</a>
                                    </li>
                                    <li role="presentation" class="disabled">
                                        <a href="#">Empresas</a>
                                    </li>
                                    <li role="presentation" class="disabled">
                                        <a href="">Comentarios</a>
                                    </li>
                                </ul>
                            </div>
                            <form id="saveForm" action="{{ $navigation['base'].'/action/update' }}" method="post" accept-charset="utf-8">
                                <div class="tab-content col-sm-12 margin-bottom--20">
                                    <div role="tabpanel" class="tab-pane active row" id="general">
                                        <div class="col-sm-4">
                                            <label for="cto34_imgLogo" class="form-label-full">Foto</label>
                                            <div class="panel-item--image"><p>No disponible.</p></div>
                                            <small>
                                                @if (!empty($persons['one']->personBusiness) )
                                                    @if (!empty($persons['one']->personBusiness->person))
                                                            {{ $persons['one']->personBusiness->person->PersonaNombreCompleto }}
                                                    @endif
                                                @endif
                                            </small>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="row">
                                                <div class="form-group col-sm-12">
                                                    <label for="">Persona</label>
                                                    <select id="cto34_person"
                                                            name="cto34_person"
                                                            data-live-search="true"
                                                            data-width="100%"
                                                            data-style="btn-sm btn-default"
                                                            data-modal-title="Empresa"
                                                            class="selectpicker with-ajax">
                                                        @if (!empty($persons['one']->personBusiness) )
                                                            @if (!empty($persons['one']->personBusiness->person))
                                                                <option value="{{ $persons['one']->tbDirPersonaEmpresaID_DirPersonaEmpresaObra }}" selected="selected">
                                                                    {{ $persons['one']->personBusiness->person->PersonaAlias }}
                                                                </option>
                                                            @endif
                                                        @endif
                                                    </select>
                                                    @if (!empty($persons['one']->personBusiness) )
                                                        @if (!empty($persons['one']->personBusiness->person))
                                                            <input type="hidden" id="cto34_personName" name="cto34_personName" value="{{ $persons['one']->personBusiness->person->PersonaAlias }}">
                                                        @else
                                                            <input type="hidden" id="cto34_businessName" name="cto34_businessName" value="">
                                                        @endif
                                                    @else
                                                        <input type="hidden" id="cto34_businessName" name="cto34_businessName" value="">
                                                    @endif
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label for="">Empresa</label>
                                                    <select id="cto34_business"
                                                            name="cto34_business"
                                                            data-live-search="true"
                                                            data-width="100%"
                                                            data-style="btn-sm btn-default"
                                                            data-modal-title="Empresa"
                                                            class="selectpicker with-ajax">
                                                        @if (!empty($persons['one']->business))
                                                            <option value="{{ $persons['one']->tbDirEmpresaID_DirPersonaEmpresaObra }}" selected="selected">
                                                                {{ $persons['one']->business->EmpresaAlias }}
                                                            </option>
                                                        @endif
                                                    </select>
                                                    @if (!empty($persons['one']->business))
                                                        <input type="hidden" id="cto34_businessName" name="cto34_businessName" value="{{ $persons['one']->business->EmpresaAlias }}">
                                                    @else
                                                        <input type="hidden" id="cto34_businessName" name="cto34_businessName" value="">
                                                    @endif
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label for="">Categoría</label>
                                                    @if (!empty($persons['one']->personBusiness))
                                                        <input id="cto34_category"
                                                               name="cto34_category"
                                                               type="text"
                                                               value="{{ $persons['one']->personBusiness->DirPersonaEmpresaCategoria }}"
                                                               readonly
                                                               class="form-control form-control-plain input-sm">
                                                    @else
                                                        <input id="cto34_category"
                                                               name="cto34_category"
                                                               type="text"
                                                               value=" - "
                                                               readonly
                                                               class="form-control form-control-plain input-sm">
                                                    @endif
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label for="">Cargo en la obra</label>
                                                    <input id="cto34_job"
                                                           name="cto34_job"
                                                           type="text"
                                                           value="{{ ifempty($persons['one']->DirPersonaObraEmpresaCargoEnLaObra, '') }}"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" value="{{ $persons['one']->getKey() }}" name="cto34_id">
                                <input type="hidden" value="{{ $works['one']->getKey() }}" name="cto34_work">
                                <input type="hidden" name="_method" value="put">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_query" value="{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}">
                                <input type="hidden" name="_hasSearch" value="{{ isset($filter['queries']['q']) ? 1 : 0 }}">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Carga la vista para los formularios de registro de personas y empresa --}}
@endsection
@push('scripts_footer')
    <script src="{{ asset('assets/js/jquery.matchHeight.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/js/moment.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/constructionWork.js') }}"></script>
    <script src="{{ asset('assets/js/business.js') }}"></script>
    <script src="{{ asset('assets/js/person.js') }}"></script>
    <script>
        (function() {

            var app = new App();
            app.preventClose();
            app.formErrors('#saveForm');
            app.initItemsList();
            app.animateSubmit("saveForm", "addSubmitButton");
            app.tooltip();

            var work = new ConstructionWork();
            var person = new Person();

            /**
             *  Hacemos la busqueda para Cliente directo
             */
            person.searchInBusinessWithAjax('#cto34_person',
                {
                    url: '{{ url("ajax/search/personsBusiness") }}',
                    token: '{{ csrf_token() }}',
                    workId: '{{ $works['one']->tbObraID }}',
                    optionClass: 'option-newPerson',
                    optionListClass: 'option-person',
                    canAdd: false

                }, function(data) {

                    if (data.action === 'newClicked') {
                        //beforeShowBusinessModal(data.element);
                    }

                    if (data.action === 'optionClicked') {
                        optionSelected(data.element);
                    }
                }
            );

            /**
             *  Hacemos la busqueda para Cliente directo
             */
            work.searchBusinessWithAjax('#cto34_business',
                {
                    url: '{{ url("ajax/search/businessWork") }}',
                    token: '{{ csrf_token() }}',
                    workId: '{{ $works['one']->tbObraID }}',
                    optionClass: 'option-newBusiness',
                    optionListClass: 'option-business',
                    canAdd: false

                }, function(data) {

                    if (data.action === 'newClicked') {
                        //beforeShowBusinessModal(data.element);
                    }

                    if (data.action === 'optionClicked') {

                        var id = $(data.element).find('option:selected').val();

                        if (data.rows !== null) {
                            var result = $.grep(data.rows, function(e){ return e.tbDirEmpresaObraID == id; });

                            if (result.length > 0) {

                                $(data.element).find('option:selected').val(result[0].tbDirEmpresaID_DirEmpresaObra);
                            }
                        }

                        console.log(data.rows);
                        optionSelected(data.element);
                    }
                }
            );
        })();

        function optionSelected(element) {
            var name = $(element).find('option:selected').text();
            $(element + 'Name').val(name);
        }
    </script>
@endpush