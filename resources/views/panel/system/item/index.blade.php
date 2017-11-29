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
					<div class="col-sm-8"></div>
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
                                    'count' => $items['all']->count(),
                                    'id' => ($items['all']->count() > 0) ? $items['one']->getKey() : 0,
                                    'inactive' => ($items['all']->count() > 0) ? $items['one']->RegistroInactivo : 0,
                                    'closed' => ($items['all']->count() > 0) ? $items['one']->RegistroCerrado : 0
                                ]
                            ]
                        )
					</div>
					<div class="clearfix"></div>
					{{--
                        Content Info
                        Se incluye la estructura donde se muestra la información del registro
                    --}}
					@include('panel.system.item.shared.content-info')
				</div>
			</div>
		</div>
	</div>
	{{--
        Modals
        Se incluyen los modales que se requieren para el registro
    --}}
	@if ($items['all']->count() > 0)
		@include('panel.system.item.shared.filter-modal')
		@include('shared.record-closed-modal')
		@include('shared.record-opened-modal')
		@include('shared.record-delete-modal', [
            'id' => $items['one']->getKey()
        ])
		@include('shared.record-restore-modal', [
            'id' => $items['one']->getKey()
        ])
		@include('shared.comment-modal', [
                'model' => $items['one']
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
                table: "{{  ($items['all']->count() > 0) ? $items['one']->getTable() : '' }}",
                tableId: "{{ ($items['all']->count() > 0) ? $items['one']->getKeyName() : '' }}"
            });
        })();
	</script>
@endpush