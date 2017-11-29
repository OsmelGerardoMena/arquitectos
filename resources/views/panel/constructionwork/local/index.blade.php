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

                            @param $model['count'] total de registros
                            @param $model['id'] id del registro
                        --}}
                        @include('shared.nav-actions-info',
                            [ 'model' =>
                                [
                                    'count' => $locals['all']->count(),
                                    'id' => ($locals['all']->count() > 0) ? $locals['one']->tbUbicaEdificioID : 0,
                                    'inactive' => ($locals['all']->count() > 0) ? $locals['one']->RegistroInactivo : 0,
                                    'closed' => ($locals['all']->count() > 0) ? $locals['one']->RegistroCerrado : 0
                                ]
                            ]
                        )
                    </div>
                    <div class="clearfix"></div>
                    {{--
                        Content Info
                        Se incluye la estructura donde se muestra la información del registro
                    --}}
                    @include('panel.constructionwork.local.shared.content-info')
                </div>
            </div>
        </div>
    </div>
    {{--
        Este campo sirve para indicar en el listado que registro se esta visualizando
    --}}
    @if ($locals['all']->count() > 0)
        @include('panel.constructionwork.building.shared.content-info-modal')
        @include('panel.constructionwork.level.shared.content-info-modal')
        @include('panel.constructionwork.local.shared.filter-modal')
        @include('shared.record-closed-modal')
        @include('shared.record-opened-modal')
        @include('shared.record-delete-modal', [
            'id' => $locals['one']->getKey(),
            'work' => $works['one']->tbObraID,
        ])
        @include('shared.record-restore-modal', [
            'id' => $locals['one']->getKey(),
            'work' => $works['one']->tbObraID,
        ])
    @endif
@endsection
@push('scripts_footer')
    <script src="{{ asset('assets/js/accounting.min.js') }}"></script>
    <script src="{{ asset('assets/js/numeral.min.js') }}"></script>
    <script src="{{ asset('assets/js/hr.js') }}"></script>
    <script>
        (function() {
            var app = new App();
            app.initItemsList();
            app.filterModal();
            app.deleteConfirm('confirmDeleteAlert', 'confirmDeleteButton', 'cancelDeleteButton');
            app.tooltip();
            app.highlightSearch();

            app.closeRecord({
                url: "{{ url('/ajax/action/records/closed') }}",
                table: "{{  ($locals['all']->count() > 0) ? $locals['one']->getTable() : '' }}",
                tableId: "{{  ($locals['all']->count() > 0) ? $locals['one']->getKeyName() : ''}}"
            });

        })();
    </script>
    @include('panel.constructionwork.building.shared.content-info-js')
    @include('panel.constructionwork.level.shared.content-info-js')
@endpush