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
                                @include('panel.constructionwork.business.shared.content-search')
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
                                @foreach ($business['all'] as $b)
                                    <a id="item{{ $b->tbDirEmpresaObraID  }}" href="{{ $navigation['base'] }}/info/{{ $b->tbDirEmpresaObraID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="list-group-item">
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
                                    <li role="presentation" class="disabled">
                                        <a href="#">Teléfonos</a>
                                    </li>
                                    <li role="presentation" class="disabled">
                                        <a href="#">Correos E.</a>
                                    </li>
                                    <li role="presentation" class="disabled">
                                        <a href="#">Domicilios</a>
                                    </li>
                                    <li role="presentation" class="disabled">
                                        <a href="#">Actas</a>
                                    </li>
                                    <li role="presentation" class="disabled">
                                        <a href="#">Socios</a>
                                    </li>
                                    <li role="presentation" class="disabled">
                                        <a href="#">Personas</a>
                                    </li>
                                    <li role="presentation" class="disabled">
                                        <a href="#">Comentarios</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content padding-top--5 col-sm-12">
                                <form id="saveForm" action="{{ $navigation['base'].'/action/save' }}" method="post" accept-charset="utf-8">
                                    <div role="tabpanel" class="tab-pane row active" id="general">
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_business" class="form-label-full">Empresa</label>
                                            <select id="cto34_business"
                                                    name="cto34_business"
                                                    data-live-search="true"
                                                    data-width="100%"
                                                    data-style="btn-sm btn-default"
                                                    data-modal-title="Cliente directo"
                                                    class="selectpicker with-ajax">
                                                @if(!empty(old('cto34_business')))
                                                    <option value="{{ old('cto34_business') }}" selected="selected">
                                                        {{ old('cto34_businessName')  }}
                                                    </option>
                                                @endif
                                            </select>
                                            <input type="hidden" id="cto34_businessName" name="cto34_businessName" value="{{ old('cto34_businessName')  }}">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_groups" class="form-label-full">Grupo</label>
                                            <select name="cto34_groups" id="cto34_groups"
                                                    class="form-control input-sm">
                                                <option value="">Seleccionar opción</option>
                                                @foreach($business['groups'] as $option)
                                                    <option value="{{ $option->tbDirGrupoID }}">{{ $option->DirGrupoNombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="cto34_scope" class="form-label-full">Alcance</label>
                                            <input id="cto34_scope"
                                                   name="cto34_scope"
                                                   type="text"
                                                   value="{{ old('cto34_scope') }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="cto34_legalName" class="form-label-full">Razón social</label>
                                            <input id="cto34_legalName"
                                                   name="cto34_legalName"
                                                   type="text"
                                                   value="{{ old('cto34_legalName') }}"
                                                   readonly
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="cto34_commercialName" class="form-label-full">Nombre comercial</label>
                                            <input id="cto34_commercialName"
                                                   name="cto34_commercialName"
                                                   type="text"
                                                   value="{{ old('cto34_commercialName') }}"
                                                   readonly
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cto34_especiality" class="form-label-full">Especialidad</label>
                                            <input id="cto34_especiality"
                                                   name="cto34_especiality"
                                                   type="text"
                                                   value="{{ old('cto34_especiality') }}"
                                                   readonly
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                        <div class="form-group col-sm-12 text-right">
                                            <label>Cerrado <input type="checkbox" value="1" name="cto34_close"></label>
                                        </div>
                                    </div>
                                    <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Carga la vista para los formularios de registro de personas y empresa --}}
    {{-- @include('panel.constructionwork.contract.shared.modal-forms') --}}
    <input type="hidden" name="_errors" value="{{ json_encode($errors->messages()) }}">
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
<script src="{{ asset('assets/js/validations.js') }}"></script>
<script>
    (function() {

        var app = new App();
        app.preventClose();
        app.formErrors('#saveForm');
        app.initItemsList();
        app.animateSubmit("saveForm", "addSubmitButton");
        app.tooltip();
        app.dateTimePickerField();

        var business = new Business();

        business.searchWithAjax('#cto34_business',
            {
                url: '{{ url("ajax/search/business") }}',
                token: '{{ csrf_token() }}',
                optionClass: 'option-newcto34_business',
                optionListClass: 'option-cto34_business',
                canAdd: false

            }, function(data) {

                if (data.rows !== null) {

                    var id = $(data.element).find('option:selected').val();
                    var result = $.grep(data.rows, function(e){ return e.tbDirEmpresaID == id; });

                    if (result.length > 0) {

                        $('#cto34_legalName').val(result[0].EmpresaRazonSocial);
                        $('#cto34_commercialName').val(result[0].EmpresaNombreComercial);
                        $('#cto34_especiality').val(result[0].EmpresaEspecialidad);

                    }

                }

                if (data.action === 'newClicked') {
                    // add new person
                    console.log(data);
                }

                if (data.action === 'optionClicked') {
                    var name = $(data.element).find('option:selected').text();
                    $(data.element + 'Name').val(name);
                    $(data.element).selectpicker({title: name})
                        .selectpicker('refresh');
                }
            });
    })();
</script>
@endpush