@extends('layouts.base')

@push('styles_head')
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-select.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.min.css') }}">
@endpush

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
					<div class="col-sm-8"></div>
					<div class="col-sm-4">
						{{--
                            Nav Actions
                            Se incluyen los botones de acción para los registros

                        --}}
						@include('shared.nav-actions-save')
					</div>
					<div class="clearfix"></div>
					<div class="panel-body padding-top--clear">
						<div class="list-group col-sm-5 col-md-4 col-lg-3 margin-clear panel-item" style="position: relative;">
							<div class="list-group-item padding-clear padding-bottom--5">
								{{--
                                    Content Search
                                    Se incluyen la forma para la busqueda y filtrado de datos
                                --}}
								@include('panel.system.item.shared.content-search')
							</div>
							<div id="itemsList">
								<div class="list-group-item active">
									<h4 class="list-group-item-heading">
										Nuevo <span class="fa fa-caret-right fa-fw"></span>
									</h4>
									<p class="small">
										{{ Carbon\Carbon::now()->formatLocalized('%A %d %B %Y') }}
									</p>
								</div>
								@foreach ($items['all'] as $index => $item)
                                    <a id="item{{ $item->tbRubroID }}" href="{{ $navigation['base'].$navigation['section'] }}/{{ $item->tbRubroID  }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="list-group-item">
			                            <h4 class="list-group-item-heading">{{ $item->RubroAlias }}</h4>
			                            <p class="small">
			                                {{ Carbon\Carbon::parse($item->created_at )->formatLocalized('%d %B %Y') }}
			                            </p>
                                    </a>
								@endforeach
							</div>
							<div class="list-group-item padding-clear padding-top--10">
								<div class="row">
									<div class="col-sm-12 text-center">
										{{--
                                            Pagination
                                            Se muestra datos y botones de paginación
                                         --}}
										@include('shared.pagination')
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-7 col-md-8 col-lg-9 panel-item padding-left--clear padding-right--clear" style="position: relative;">
							<div class="col-sm-12 margin-bottom--5">
								<ul class="nav nav-tabs nav-tabs-works" role="tablist">
									<li role="presentation" class="active">
										<a href="#general" aria-controls="general" role="tab" data-toggle="tab">
											General
										</a>
									</li>
								</ul>
							</div>
							<div class="tab-content col-sm-12 margin-bottom--20">
								<div role="tabpanel" class="tab-pane active row padding-top--5" id="general">
									<form id="saveForm" action="{{ $navigation['base'].'/action/save' }}" method="post" accept-charset="utf-8">

										
												<div class="form-group col-sm-6">
													<label for="cto34_item" class="form-label-full">Rubro</label>
													<input id="cto34_item" 
														name="cto34_item" 
														type="text" 
														value="{{ old('cto34_item') }}"
														class="form-control form-control-plain input-sm">
												</div>

												<div class="form-group col-sm-6">
													<label for="cto34_type" class="form-label-full">Gasto tipo</label>
													<input id="cto34_type" 
														name="cto34_type" 
														type="text" 
														value="{{ old('cto34_type') }}"
														class="form-control form-control-plain input-sm">

												</div>

												<div class="form-group col-sm-6">
													<label for="cto34_class" class="form-label-full">Gasto clase</label>
													<input id="cto34_class" 
														name="cto34_class" 
														type="text" 
														value="{{ old('cto34_class') }}"
														class="form-control form-control-plain input-sm">
												</div>

												<div class="form-group col-sm-6">
													<label for="cto34_description" class="form-label-full">Descripción</label>
													<input id="cto34_description" 
														name="cto34_description" 
														type="text" 
														value="{{ old('cto34_description') }}"
														class="form-control form-control-plain input-sm">
												</div>


												<div class="form-group col-sm-12 text-right">
													<label for="cto34_status" class="form-label-full">Cerrado</label>
													<input type="checkbox" name="cto34_closed">
												</div>

										<input type="hidden" name="_token" value="{{ csrf_token() }}">
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	{{-- Guarda los errores para marcar los campos con errores --}}
	<input type="hidden" name="_errors" value="{{ json_encode($errors->messages()) }}">
	{{--
        Modals
        Se incluyen los modales que se requieren para el registro
    --}}
	@include('panel.system.item.shared.filter-modal')
@endsection
@push('scripts_footer')
	<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
	<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
	<script src="{{ asset('assets/js/select2.full.min.js') }}"></script>
	<script src="{{ asset('assets/js/person.js') }}"></script>
	<script>
        (function() {

            var app = new App();
            app.preventClose();
            app.formErrors('#saveForm');
            app.initItemsList();
            app.animateSubmit("saveForm", "addSubmitButton");
            app.tooltip();
            app.limitInput("#cto34_description", 4000);
            app.filterModal();

        })();

	</script>
@endpush