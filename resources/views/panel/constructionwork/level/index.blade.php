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

                            @param $model['count'] total de registros
                            @param $model['id'] id del registro
                        --}}
                        @include('shared.nav-actions-info',
                            [ 'model' =>
                                [
                                    'count' => $levels['all']->count(),
                                    'id' => ($levels['all']->count() > 0) ? $levels['one']->getKey() : 0,
                                    'inactive' => ($levels['all']->count() > 0) ? $levels['one']->RegistroInactivo : 0,
                                    'closed' => ($levels['all']->count() > 0) ? $levels['one']->RegistroCerrado : 0
                                ]
                            ]
                        )
                    </div>
                    <div class="clearfix"></div>
                    {{--
                        Content Info
                        Se incluye la estructura donde se muestra la información del registro
                    --}}
                    @include('panel.constructionwork.level.shared.content-info')
                </div>
            </div>
        </div>
    </div>
    {{--
        Este campo sirve para indicar en el listado que registro se esta visualizando
    --}}
    <input type="hidden" name="_recordId" value="{{ ($levels['all']->count() > 0) ? $levels['one']->tbUbicaNivelID : 0 }}">
    {{--
        Modals
        Se incluyen los modales que se requieren para el registro
    --}}
    @if ($levels['all']->count() > 0)
        @include('panel.constructionwork.building.shared.content-info-modal')
        @include('panel.constructionwork.local.shared.content-info-modal')
        @include('panel.constructionwork.level.shared.filter-modal')
        @include('shared.record-closed-modal')
        @include('shared.record-opened-modal')
        @include('shared.record-delete-modal', [
            'id' => $levels['one']->getKey(),
            'work' => $works['one']->tbObraID,
        ])
        @include('shared.record-restore-modal', [
            'id' => $levels['one']->getKey(),
            'work' => $works['one']->tbObraID,
        ])
    @endif
@endsection
{{--
    Scripts footer
    Se agregan script js al archivo base de las vistas
--}}
@push('scripts_footer')
    <script src="{{ asset('assets/js/hr.js') }}"></script>
    <script src="{{ asset('assets/js/numeral.min.js') }}"></script>
    <script src="{{ asset('assets/js/accounting.min.js') }}"></script>
    <script>
        (function() {
            var app = new App();
            app.initItemsList({ fitRelationHeight: 275 });
            app.filterModal();
            app.tooltip();
            app.highlightSearch();
            app.onPageTab();

            app.closeRecord({
                url: "{{ url('/ajax/action/records/closed') }}",
                table: "{{  ($levels['all']->count() > 0) ? $levels['one']->getTable() : '' }}",
                tableId: "{{  ($levels['all']->count() > 0) ? $levels['one']->getKeyName() : '' }}"
            });

        })();
    </script>
    @include('panel.constructionwork.building.shared.content-info-js')
    @include('panel.constructionwork.local.shared.content-info-js')
@endpush