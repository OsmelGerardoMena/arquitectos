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
                                    'count' => $dailywork['all']->count(),
                                    'id' => ($dailywork['all']->count() > 0) ? $dailywork['one']->getKey() : 0,
                                    'inactive' => ($dailywork['all']->count() > 0) ? $dailywork['one']->RegistroInactivo : 0,
                                    'closed' => ($dailywork['all']->count() > 0) ? $dailywork['one']->RegistroCerrado : 0
                                ]
                            ]
                        )
                    </div>
                    <div class="clearfix"></div>
                    {{--
                        Content Info
                        Se incluye la estructura donde se muestra la información del registro
                    --}}
                    @include('panel.constructionwork.dailywork.shared.content-info')
                </div>
            </div>
        </div>
    </div>
    {{--
        Modals
        Se incluyen los modales que se requieren para el registro
    --}}
    @if (($dailywork['all']->count() > 0))
        @include('panel.constructionwork.dailywork.shared.relation-modals')
        @include('panel.constructionwork.dailywork.shared.filter-modal')
        @include('shared.record-closed-modal')
        @include('shared.record-opened-modal')
        @include('shared.record-delete-modal', [
            'id' => $dailywork['one']->getKey(),
            'work' => $works['one']->tbObraID,
        ])
        @include('shared.record-restore-modal', [
            'id' => $dailywork['one']->getKey(),
            'work' => $works['one']->tbObraID,
        ])

        @include('shared.comment-modal', [
            'model' => $dailywork['one']
        ])
    @endif
    @include('shared.image-show-modal')
@endsection

@push('scripts_footer')
    <script src="{{ asset('assets/js/hr.js') }}"></script>
    <script src="{{ asset('assets/js/numeral.min.js') }}"></script>
    <script>
        (function() {
            var app = new App();
            app.initItemsList();
            app.filterModal();
            app.imgModal();
            app.tooltip();
            app.highlightSearch();
            app.onPageTab();
            app.commentModal();

            app.closeRecord({
                url: "{{ url('/ajax/action/records/closed') }}",
                table: "{{  ($dailywork['all']->count() > 0) ? $dailywork['one']->getTable() : '' }}",
                tableId: "{{ ($dailywork['all']->count() > 0) ? $dailywork['one']->getKeyName() : '' }}"
            });
        })();
    </script>
@endpush