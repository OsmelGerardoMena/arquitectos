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
                                    'count' => $phones['all']->count(),
                                    'id' => ($phones['all']->count() > 0) ? $phones['one']->getKey() : 0,
                                    'inactive' => ($phones['all']->count() > 0) ? $phones['one']->RegistroInactivo : 0,
                                    'closed' => ($phones['all']->count() > 0) ? $phones['one']->RegistroCerrado : 0
                                ]
                            ]
                        )
					</div>
					<div class="clearfix"></div>
					{{--
                        Content Info
                        Se incluye la estructura donde se muestra la información del registro
                    --}}
					@include('panel.system.phone.shared.content-info')
				</div>
			</div>
		</div>
	</div>
	{{--
        Modals
        Se incluyen los modales que se requieren para el registro
    --}}
	@if ($phones['all']->count() > 0)
		@include('panel.system.phone.shared.filter-modal')
		@include('shared.record-closed-modal')
		@include('shared.record-opened-modal')
		@include('shared.record-delete-modal', [
            'id' => $phones['one']->getKey()
        ])
		@include('shared.record-restore-modal', [
            'id' => $phones['one']->getKey()
        ])
		@include('shared.comment-modal', [
                'model' => $phones['one']
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
                table: "{{  ($phones['all']->count() > 0) ? $phones['one']->getTable() : '' }}",
                tableId: "{{ ($phones['all']->count() > 0) ? $phones['one']->getKeyName() : '' }}"
            });
        })();
	</script>
@endpush