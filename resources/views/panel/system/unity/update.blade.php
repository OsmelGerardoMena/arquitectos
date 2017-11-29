@extends('layouts.base')

{{-- Se registran los archivos css requeridos para esta sección --}}
@push('styles_head')
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-select.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}">
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
						@include('shared.nav-actions-update', [ 'model' => [ 'id' => $unities['one']->getKey() ]])
					</div>
					<div class="clearfix"></div>
					<div class="panel-body padding-top--clear">
						<div class="list-group col-sm-5 col-md-4 col-lg-3 margin-clear panel-item" style="position: relative;">
							<div class="list-group-item padding-clear padding-bottom--5">
								{{--
                                    Content Search
                                    Se incluyen la forma para la busqueda y filtrado de datos
                                --}}
								@include('panel.system.unity.shared.content-search')
							</div>
							<div id="itemsList">
								@foreach ($unities['all'] as $index => $unity)

									@if ($unity->tbUnidadID == $unities['one']->tbUnidadID)
										<div id="item{{ $unity->tbUnidadID }}" class="list-group-item active">
	                                      <h4 class="list-group-item-heading">
	                                            {{ $unity->UnidadAlias }} |
	                                            {{ $unity->UnidadTipo }}
	                                        </h4>
	                                        <p class="small">
	                                            {{ Carbon\Carbon::parse($unity->created_at )->formatLocalized('%A %d %B %Y') }}
	                                        </p>
										</div>
										@continue
									@endif

									<a id="item{{ $unity->tbUnidadID }}" href="{{ $navigation['base'].$navigation['section'] }}/{{ $unity->tbUnidadID  }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="list-group-item">
                                      <h4 class="list-group-item-heading">
                                            {{ $unity->UnidadAlias }} |
                                            {{ $unity->UnidadTipo }}
                                        </h4>
                                        <p class="small">
                                            {{ Carbon\Carbon::parse($unity->created_at )->formatLocalized('%A %d %B %Y') }}
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
								<form id="saveForm" action="{{ $navigation['base'] }}/action/update" method="POST" accept-charset="utf-8" class="col-sm-12">

									<div class="form-group col-sm-6">
										<label for="cto34_unity" class="form-label-full">Unidad</label>
										<input id="cto34_unity" 
											name="cto34_unity" 
											type="text" 
											value="{{ $unities['one']->UnidadAlias }}"
											class="form-control form-control-plain input-sm">
									</div>

									<div class="form-group col-sm-6">
										<label for="cto34_type" class="form-label-full">Tipo</label>
										<input id="cto34_type" 
											name="cto34_type" 
											type="text" 
											value="{{ $unities['one']->UnidadTipo }}"
											class="form-control form-control-plain input-sm">

									</div>
				
									<div class="form-group col-sm-6">
										<label for="cto34_name" class="form-label-full">Nombre</label>
										<input id="cto34_name" 
											name="cto34_name" 
											type="text" 
											value="{{ $unities['one']->UnidadNombre }}"
											class="form-control form-control-plain input-sm">
									</div>

									<div class="form-group col-sm-6">
										<label for="cto34_names" class="form-label-full">Nombre plural</label>
										<input id="cto34_names" 
											name="cto34_names" 
											type="text" 
											value="{{ $unities['one']->UnidadNombrePlural }}"
											class="form-control form-control-plain input-sm">
									</div>

									<div class="form-group col-sm-12">
										<label for="cto34_description" class="form-label-full">Descripcion</label>
										<input id="cto34_description" 
											name="cto34_description" 
											type="text" 
											value="{{ $unities['one']->UnidadDescripcion }}"
											class="form-control form-control-plain input-sm">

									</div>
	
									<div class="form-group col-sm-12 text-right">
										<label for="cto34_status" class="form-label-full">Cerrado</label>
										<p>{{ $unities['one']->RegistroCerrado == 1?'Cerrado':'' }}</p>
										<input type="checkbox" name="closed" value="{{ $unities['one']->RegistroCerrado == 0?'0':'1' }}">
	
									</div>
		
											<input type="hidden" name="cto34_id" value="{{ $unities['one']->tbUnidadID }}">
											<input type="hidden" name="_method" value="PUT">
											<input type="hidden" name="_token" value="{{ csrf_token() }}">

									</div>
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
	@include('panel.system.unity.shared.filter-modal')
@endsection
@push('scripts_footer')
	<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
	<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
	<script src="{{ asset('assets/js/select2.full.min.js') }}"></script>
	<script src="{{ asset('assets/js/person.js') }}"></script>
	<script src="{{ asset('assets/js/business.js') }}"></script>
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