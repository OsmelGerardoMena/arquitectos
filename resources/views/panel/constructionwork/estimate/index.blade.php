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
                                    'count' => $estimates['all']->count(),
                                    'id' => ($estimates['all']->count() > 0) ? $estimates['one']->tbUbicaEdificioID : 0,
                                    'inactive' => ($estimates['all']->count() > 0) ? $estimates['one']->RegistroInactivo : 0,
                                    'closed' => ($estimates['all']->count() > 0) ? $estimates['one']->RegistroCerrado : 0
                                ]
                            ]
                        )
					</div>
					<div class="clearfix"></div>
					{{--
                        Content Info
                        Se incluye la estructura donde se muestra la información del registro
                    --}}
					@include('panel.constructionwork.estimate.shared.content-info')
				</div>
			</div>
		</div>
	</div>
	{{--
        Este campo sirve para indicar en el listado que registro se esta visualizando
    --}}
	@if ($estimates['all']->count() > 0)
		@include('panel.constructionwork.estimate.shared.filter-modal')
		@include('shared.record-closed-modal')
		@include('shared.record-opened-modal')
		@include('shared.record-delete-modal', [
            'id' => $estimates['one']->getKey(),
            'work' => $works['one']->tbObraID,
        ])
		@include('shared.record-restore-modal', [
            'id' => $estimates['one']->getKey(),
            'work' => $works['one']->tbObraID,
        ])
		@include('shared.comment-modal', [
            'model' => $estimates['one']
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
            app.onPageTab();
            app.commentModal();

            app.closeRecord({
                url: "{{ url('/ajax/action/records/closed') }}",
                table: "{{  ($estimates['all']->count() > 0) ? $estimates['one']->getTable() : '' }}",
                tableId: "{{  ($estimates['all']->count() > 0) ? $estimates['one']->getKeyName() : ''}}"
            });

        })();
	</script>
@endpush