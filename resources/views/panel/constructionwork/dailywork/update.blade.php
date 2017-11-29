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

                        --}}
                        @include('shared.nav-actions-update', [ 'model' => [ 'id' => $dailywork['one']->getKey() ]])
                    </div>
                    <div class="clearfix"></div>
                    <div class="panel-body padding-top--clear">
                        <div class="list-group col-sm-5 col-md-4 col-lg-3 margin-clear panel-item" style="position: relative;">
                            <div class="list-group-item padding-clear padding-bottom--5">
                                {{--
                                    Content Search
                                    Se incluyen la forma para la busqueda y filtrado de datos
                                --}}
                                @include('panel.constructionwork.dailywork.shared.content-search')
                            </div>
                            <div id="itemsList">
                                @foreach ($dailywork['all'] as $index => $daily)
                                    @if ($daily->tbDiarioID == $dailywork['one']->tbDiarioID)
                                        <a id="item{{ $daily->tbDiarioID }}" href="{{ $navigation['base'] }}/info/{{ $daily->tbDiarioID  }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="list-group-item active">
                                            <h4 class="list-group-item-heading">
                                                {{ $daily->DiarioFolio }} |
                                                {{ $daily->DiarioAsunto }}
                                            </h4>
                                            <p class="small">
                                                {{ Carbon\Carbon::parse($daily->DiarioFecha )->formatLocalized('%A %d %B %Y') }}
                                            </p>
                                        </a>
                                        @continue
                                    @endif
                                    @if (isset($search['query']))
                                        <a id="item{{ $daily->tbDiarioID }}" href="{{ $navigation['base'] }}/search/{{ $daily->tbDiarioID  }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="list-group-item">
                                            <h4 class="list-group-item-heading">
                                                {{ $daily->DiarioFolio }} |
                                                {{ $daily->DiarioAsunto }}
                                            </h4>
                                            <p class="text-muted small">
                                                {{ Carbon\Carbon::parse($daily->DiarioFecha )->formatLocalized('%A %d %B %Y') }}
                                            </p>
                                        </a>
                                    @else
                                        <a id="item{{ $daily->tbDiarioID }}" href="{{ $navigation['base'] }}/info/{{ $daily->tbDiarioID  }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="list-group-item">
                                            <h4 class="list-group-item-heading">
                                                {{ $daily->DiarioFolio }} |
                                                {{ $daily->DiarioAsunto }}
                                            </h4>
                                            <p class="text-muted small">
                                                {{ Carbon\Carbon::parse($daily->DiarioFecha )->formatLocalized('%A %d %B %Y') }}
                                            </p>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                            <!-- Paginator -->
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
                                            Comentarios
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content col-sm-12 margin-bottom--20">
                                <div role="tabpanel" class="tab-pane active row" id="general">
                                    @include('panel.constructionwork.dailywork.shared.form', [
                                        'form' => [
                                            'id'=> 'updateForm',
                                            'mode' => 'update',
                                            'action' => $navigation['base'].'/action/update' ,
                                            'method' => 'post',
                                            'crud' => 'put',
                                            'with' => [
                                                'model' => true,
                                                'values' => false
                                            ]
                                        ]
                                    ])
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
        además de indicar el id del registro en algunas acciones.
    --}}
    <input type="hidden" name="_recordId" value="{{ $dailywork['one']->getKey() }}">
    <input type="hidden" name="_errors" value="{{ json_encode($errors->messages()) }}">
    @include('panel.constructionwork.dailywork.shared.filter-modal')
    @include('shared.image-show-modal')
@endsection
@push('scripts_footer')
    <script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/js/moment.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/constructionWork.js') }}"></script>
    <script src="{{ asset('assets/js/validations.js') }}"></script>
    <script src="{{ asset('assets/js/user.js') }}"></script>
    <script>
        (function() {

            var app = new App();
            app.initItemsList();
            app.formErrors('#updateForm');
            app.animateSubmit("updateForm", "addSubmitButton");
            app.tooltip();
            app.limitInput("#cto34_description", 4000);
            app.dateTimePickerField('#cto34_date_dailyContainer');
            app.imgModal();
            app.preventClose();

            var validate = new Validations();

            validate.limitCharacters('#cto34_subject',100);
            validate.limitCharacters('#cto34_description',4000);
            validate.limitCharacters('#cto34_bottom_copy',250);

            $('#personMe').on('click',  function() {

                var id = $(this).data('id');
                var name = $(this).data('name');
                var elementId = "#cto34_author";

                if ($(elementId + 'Name').val() == name) {
                    return;
                }

                $(elementId).append($('<option>', {
                    value: id,
                    text: name,
                }).attr('selected', 'selected'));

                $(elementId + 'Name').val(name);

                $(elementId).selectpicker({title: name})
                            .selectpicker('refresh');

            });

            var work = new ConstructionWork();

            /**
             *  Hacemos la busqueda para Firma por el cliente
             */
            work.searchPersonWithAjax('#cto34_author',
                {
                    url: '{{ url("ajax/search/personsWork") }}',
                    token: '{{ csrf_token() }}',
                    workId: '{{ $works['one']->tbObraID }}',
                    optionClass: 'option-newPersonAuthor',
                    optionListClass: 'option-personAuthor',
                    canAdd: false

                }, function(data) {

                    if (data.action === 'newClicked') {
                        //beforeShowPersonModal(data.element);
                    }

                    if (data.action === 'optionClicked') {

                        var name = $(data.element).find('option:selected').text();
                        var id = $(data.element).find('option:selected').val();

                        if (data.persons !== null) {
                            var result = $.grep(data.rows, function(e){ return e.tbDirPersonaEmpresaObraID == id; });

                            if (result.length > 0) {
                                console.log(result);

                                if (result[0].persons_business.length > 0) {
                                    $(data.element).find('option:selected').val(result[0].persons_business[0].person.tbDirPersonaID);
                                }
                            }
                        }

                        $(data.element + 'Name').val(name);
                        $(data.element).selectpicker()
                            .selectpicker('refresh');

                    }
                }
            );

        })();
    </script>
@endpush