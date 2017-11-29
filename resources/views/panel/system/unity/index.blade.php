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
                       <p style="padding-top: 12px;">{{ $page['title'] }}</p>
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
                                    'count' => $unities['all']->count(),
                                    'id' => ($unities['all']->count() > 0) ? $unities['one']->getKey() : 0,
                                    'inactive' => ($unities['all']->count() > 0) ? $unities['one']->RegistroInactivo : 0,
                                    'closed' => ($unities['all']->count() > 0) ? $unities['one']->RegistroCerrado : 0
                                ]
                            ]
                        )

                    </div>
                    <div class="clearfix"></div>
                    {{--
                        Content Info
                        Se incluye la estructura donde se muestra la información del registro
                    --}}
                    @include('panel.system.unity.shared.content-info')
                </div>
            </div>
        </div>
    </div>
    {{--
        Modals
        Se incluyen los modales que se requieren para el registro
    --}}

    @include('panel.system.unity.shared.filter-modal')
    @include('shared.record-closed-modal')
    @include('shared.record-opened-modal')
    @include('shared.record-delete-modal', [
        'id' => ($unities['all']->count() > 0) ? $unities['one']->getKey() : 0,
    ])

    @include('shared.record-restore-modal', [
        'id' => ($unities['all']->count() > 0) ? $unities['one']->getKey() : 0,
    ])
    @if (($unities['all']->count() > 0))
        @include('shared.comment-modal', [
            'model' => $unities['one']
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
            app.imgModal();
            app.tooltip();
            app.highlightSearch();
            app.onPageTab();

            app.closeRecord({
                url: "{{ url('/ajax/action/records/closed') }}",
                table: "{{  ($unities['all']->count() > 0) ? $unities['one']->getTable() : '' }}",
                tableId: "{{ ($unities['all']->count() > 0) ? $unities['one']->getKeyName() : '' }}"
            });
        })();
    </script>
@endpush