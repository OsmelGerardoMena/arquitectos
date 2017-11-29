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
                                @include('panel.constructionwork.person.shared.content-search')
                            </div>
                            <div id="itemsList">
                                <div class="list-group-item active">
                                    <h4 class="list-group-item-heading">
                                        Nuevo <span class="fa fa-caret-right fa-fw"></span>
                                    </h4>
                                    <p class="small">
                                        {{ Carbon\Carbon::now()->formatLocalized('%A %d %B %Y') }}
                                    </p>
                                </div>
                                @foreach ($persons['all'] as $index => $person)
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
                            <form id="saveForm" action="{{ $navigation['base'].'/action/save' }}" method="post" accept-charset="utf-8">
                                <div class="tab-content col-sm-12 margin-bottom--20">
                                    <div role="tabpanel" class="tab-pane active row" id="general">
                                        <div class="col-sm-4">
                                            <label for="cto34_imgLogo" class="form-label-full">Foto</label>
                                            <div class="panel-item--image"><p>No disponible.</p></div>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="row">
                                                <div class="form-group col-sm-12">
                                                    <label for="cto34_business">Empresa</label>
                                                    <select name="cto34_business" id="cto34_business" class="form-control form-control-sm">
                                                        <option value="">Seleccionar opción</option>
                                                        @foreach ($business['all'] as  $option)
                                                            <option value="{{ $option->business[0]->tbDirEmpresaID  }}">{{ $option->business[0]->EmpresaAlias }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label for="">Persona</label>
                                                    <select id="cto34_person" name="cto34_person" class="form-control form-control-sm" disabled>
                                                        <option value="" >Seleccionar opción</option>
                                                    </select>
                                                </div>

                                                <div class="form-group col-sm-12">
                                                    <label for="">Categoría</label>
                                                    <input id="cto34_category"
                                                           name="cto34_category"
                                                           type="text"
                                                           value="{{ old('cto34_category') }}"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label for="">Cargo en la obra</label>
                                                    <input id="cto34_job"
                                                           name="cto34_job"
                                                           type="text"
                                                           value="{{ old('cto34_job') }}"
                                                           class="form-control form-control-plain input-sm">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
    {{-- Carga la vista para los formularios de registro de personas y empresa --}}
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
        /*person.searchInBusinessWithAjax('#cto34_person',
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
        );*/

        /**
         *  Hacemos la busqueda para Cliente directo
         */
        /*work.searchBusinessWithAjax('#cto34_business',
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
        );*/

        searchPersonByBusinessId();

    })();

    function searchPersonByBusinessId() {

        $('#cto34_business').on('change', function() {

            var select = $(this);

            if (select.val() === '') {
                return;
            }

            if (select.val() === '-1') {
                $('#modalDeparture').modal('show');
                return;
            }

            //$('#cto34_subdeparture_departure').val(select.val());

            var elementId = '#cto34_person';
            $(elementId).prop('disabled', true);

            var request = $.ajax({
                url: '{{ url('/ajax/search/personWorkByBusinessId') }}',
                type: 'post',
                dataType: 'html',
                timeout: 90000,
                cache: false,
                data: {
                    id: select.val()
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            request.done(function(response) {

                var result = jQuery.parseJSON(response);

                if (result.personWork.length !== 0) {

                    var persons = result.personWork;
                    var options = '';
                    options += '<option value="" >Seleccionar opción</option>';

                    console.log(persons);

                    $.each(persons, function(index, value) {
                        options += '<option value="' + value.tbDirPersonaEmpresaID + '" data-category="' + value.DirPersonaEmpresaCategoria +'">' +  value.person.PersonaAlias + '</option>';
                    });

                    $(elementId).html(options);
                }

                $(elementId).prop('disabled', false);

            });

            request.fail(function(xhr, textStatus) {
                alert('Ocurrio un error');
            });

        });

        $('#cto34_person').on('change', function() {

            var cateogory = $(this).find('option:selected').attr('data-category');
            $('#cto34_category').val(cateogory);

        });

    }

    function optionSelected(element) {
        var name = $(element).find('option:selected').text();
        $(element + 'Name').val(name);
    }
</script>
@endpush