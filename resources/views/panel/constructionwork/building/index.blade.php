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
                                    'count' => $buildings['all']->count(),
                                    'id' => ($buildings['all']->count() > 0) ? $buildings['one']->tbUbicaEdificioID : 0,
                                    'inactive' => ($buildings['all']->count() > 0) ? $buildings['one']->RegistroInactivo : 0,
                                    'closed' => ($buildings['all']->count() > 0) ? $buildings['one']->RegistroCerrado : 0
                                ]
                            ]
                        )
                    </div>
                    <div class="clearfix"></div>
                    {{--
                        Content Info
                        Se incluye la estructura donde se muestra la información del registro
                    --}}
                    @include('panel.constructionwork.building.shared.content-info',[
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
    @if ($buildings['all']->count() > 0)
        @include('panel.constructionwork.level.shared.content-info-modal')
        @include('panel.constructionwork.local.shared.content-info-modal')
        @include('panel.constructionwork.building.shared.filter-modal')
        @include('shared.record-closed-modal')
        @include('shared.record-opened-modal')
        @include('shared.record-delete-modal', [
            'id' =>  $buildings['one']->getKey(),
            'work' => $works['one']->tbObraID,
        ])
        @include('shared.record-restore-modal', [
            'id' => $buildings['one']->getKey(),
            'work' => $works['one']->tbObraID,
        ])
    @endif
@endsection

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
                table: "{{  ($buildings['all']->count() > 0) ? $buildings['one']->getTable() : '' }}",
                tableId: "{{  ($buildings['all']->count() > 0) ? $buildings['one']->getKeyName() : '' }}"
            });

            $('#saveLevelModal').on('show.bs.modal', function() {

                var modal = $(this);
                var consecutive = parseInt($('input[name="level_count"]').val()) + 1;

                modal.find('input[name="cto34_consecutive"]').val(consecutive);
            });

        })();
    </script>
    @include('panel.constructionwork.level.shared.content-info-js')
    @include('panel.constructionwork.local.shared.content-info-js')
@endpush