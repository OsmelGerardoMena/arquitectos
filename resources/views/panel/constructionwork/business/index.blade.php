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
                                    'count' => $business['all']->count(),
                                    'id' => ($business['all']->count() > 0) ? $business['one']->getKey() : 0,
                                    'inactive' => ($business['all']->count() > 0) ? $business['one']->RegistroInactivo : 0,
                                    'closed' => ($business['all']->count() > 0) ? $business['one']->RegistroCerrado : 0
                                ]
                            ]
                        )
                    </div>
                    <div class="clearfix"></div>
                    {{--
                        Content Info
                        Se incluye la estructura donde se muestra la información del registro
                    --}}
                    @include('panel.constructionwork.business.shared.content-info',[
                        'query' => '?'.$filter['query']
                    ])
                </div>
            </div>
        </div>
    </div>
    {{--
        Modals
        Se incluyen los modales que se requieren para el registro
    --}}
    @if($business['all']->count() > 0)
        @include('panel.constructionwork.business.shared.filter-modal')
        @include('shared.record-closed-modal')
        @include('shared.record-opened-modal')
        @include('shared.record-delete-modal', [
            'id' => ($business['all']->count() > 0) ? $business['one']->getKey() : 0,
            'work' => $works['one']->tbObraID,
        ])
        @include('shared.record-restore-modal', [
            'id' => ($business['all']->count() > 0) ? $business['one']->getKey() : 0,
            'work' => $works['one']->tbObraID,
        ])
        @include('shared.comment-modal', [
                'model' => ($business['all']->count() > 0) ? $business['one'] : null
            ])
    @endif
@endsection

@push('scripts_footer')
    <script src="{{ asset('assets/js/hr.js') }}"></script>
    <script src="{{ asset('assets/js/numeral.min.js') }}"></script>
    <script>
        (function() {
            var app = new App();
            app.initItemsList();
            app.filterModal();
            app.tooltip();
            app.highlightSearch();
            app.onPageTab();

            app.closeRecord({
                url: "{{ url('/ajax/action/records/closed') }}",
                table: "{{  ($business['all']->count() > 0) ? $business['one']->getTable() : '' }}",
                tableId: "{{ ($business['all']->count() > 0) ? $business['one']->getKeyName() : '' }}"
            });

        })();
    </script>
@endpush