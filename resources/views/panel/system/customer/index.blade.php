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
                                    'count' => $customers['all']->count(),
                                    'id' => ($customers['all']->count() > 0) ? $customers['one']->getKey() : 0,
                                    'inactive' => ($customers['all']->count() > 0) ? $customers['one']->RegistroInactivo : 0,
                                    'closed' => ($customers['all']->count() > 0) ? $customers['one']->RegistroCerrado : 0
                                ]
                            ]
                        )
					</div>
					<div class="clearfix"></div>
					{{--
                        Content Info
                        Se incluye la estructura donde se muestra la información del registro
                    --}}
					@include('panel.system.customer.shared.content-info')
				</div>
			</div>
		</div>
	</div>
	{{--
        Modals
        Se incluyen los modales que se requieren para el registro
    --}}
	@if ($customers['all']->count() > 0)
		@include('panel.system.customer.shared.filter-modal')
		@include('shared.record-closed-modal')
		@include('shared.record-opened-modal')
		@include('shared.record-delete-modal', [
            'id' => $customers['one']->getKey()
        ])
		@include('shared.record-restore-modal', [
            'id' => $customers['one']->getKey()
        ])
		@include('shared.comment-modal', [
                'model' => $customers['one']
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
                table: "{{  ($customers['all']->count() > 0) ? $customers['one']->getTable() : '' }}",
                tableId: "{{ ($customers['all']->count() > 0) ? $customers['one']->getKeyName() : '' }}"
            });
        })();
	</script>
@endpush